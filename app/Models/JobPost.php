<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_id'; // Important: Matches migration

    protected $fillable = [
        'job_title',
        'job_type',
        'department',
        'location',
        'salary_range',
        'job_description',
        'requirements',
        'closing_date',
        'job_status',
        'posted_by',
    ];

    protected $casts = [
        'closing_date' => 'date',
    ];

    // Relationship to Admin
    public function recruiter()
    {
        return $this->belongsTo(User::class, 'posted_by', 'user_id');
    }
    
    // Relationship to Applications (We will use this later)
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}