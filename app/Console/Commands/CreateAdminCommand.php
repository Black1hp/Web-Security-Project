<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new admin user...');
        
        $name = $this->ask('Enter admin name');
        $email = $this->ask('Enter admin email');
        $password = $this->secret('Enter admin password');
        $confirmPassword = $this->secret('Confirm admin password');
        
        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $confirmPassword,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        if ($validator->fails()) {
            $this->error('Admin creation failed!');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        
        // Create admin
        $admin = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        
        $this->info('Admin user created successfully!');
        $this->table(
            ['Name', 'Email'],
            [[$admin->name, $admin->email]]
        );
        
        return 0;
    }
}
