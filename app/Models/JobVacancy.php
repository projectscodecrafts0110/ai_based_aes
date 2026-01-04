<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'qualifications'];

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}
