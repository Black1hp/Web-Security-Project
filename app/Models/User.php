<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;
    
    /**
     * Generate and save a verification code for the user
     * 
     * @return string
     */
    public function generateVerificationCode()
    {
        // Generate a random 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Save the code and set expiration time (30 minutes from now)
        $this->verification_code = $code;
        $this->verification_code_expires_at = now()->addMinutes(30);
        $this->save();
        
        return $code;
    }
    
    /**
     * Verify the email with the provided code
     * 
     * @param string $code
     * @return bool
     * @throws \Exception
     */
    public function verifyEmailWithCode($code)
    {
        // Check if the code matches and is not expired
        if ($this->verification_code !== $code) {
            throw new \Exception('Invalid verification code.');
        }
        
        if ($this->verification_code_expires_at < now()) {
            throw new \Exception('Verification code has expired. Please request a new one.');
        }
        
        // Mark email as verified
        $this->email_verified_at = now();
        $this->verification_code = null;
        $this->verification_code_expires_at = null;
        $this->save();
        
        return true;
    }
    


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'verification_code',
        'verification_code_expires_at',
        'google_id',
        'google_token',
        'google_refresh_token',
        'github_id',
        'github_token',
        'github_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        // Generate a new verification code
        $code = $this->generateVerificationCode();
        
        // Send the notification with the code
        $this->notify(new \App\Notifications\VerificationCodeNotification($code));
    }
}
