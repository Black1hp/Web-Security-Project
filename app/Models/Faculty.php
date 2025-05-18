<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'dean_id',
    ];

    /**
     * Get the dean of the faculty.
     */
    public function dean()
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    /**
     * Get the departments in this faculty.
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get all courses in this faculty through departments.
     */
    public function courses()
    {
        return $this->hasManyThrough(Course::class, Department::class);
    }
}
