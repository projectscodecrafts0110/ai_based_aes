<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\JobVacancy;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'contact_number',
        'address',
        'job_id',
        'higher_education',
        'major',
        'application_letter',
        'resume',
        'pds',
        'otr',
        'certificates',
        'ratings',
        'status',
        'ai_score',
        'ai_recommendation',
        'ai_summary',
        'ai_evaluated_at',
        'qualification_match',
    ];

    protected $casts = [
        'certificates' => 'array', // JSON for multiple certificates
    ];

    // Optional: Helper to get full path for uploaded files
    public function getFileUrl($field)
    {
        return $this->$field ? Storage::url($this->$field) : null;
    }

    public function job()
    {
        return $this->belongsTo(JobVacancy::class, 'job_id'); // job_id FK
    }
}
