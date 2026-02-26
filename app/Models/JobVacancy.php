<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'qualifications', 'course', 'is_open', 'job_type', 'employment_status', 'campus', 'department', 'available_positions', 'school_year'];

    protected $casts = [
        'qualifications' => 'array',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    public function alliedCourse()
    {
        return $this->belongsTo(AlliedCourse::class, 'course', 'course');
    }
}
