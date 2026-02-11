<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    use HasFactory;

    // Explicitly define table name since migration used singular 'onboarding'
    protected $table = 'onboarding';
    protected $primaryKey = 'onboarding_id';

    protected $fillable = [
        'employee_id',
        'assigned_by',
        'start_date',
        'end_date',
        'status', // 'pending', 'in_progress', 'completed'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function tasks()
    {
        return $this->hasMany(OnboardingTask::class, 'onboarding_id', 'onboarding_id');
    }

    // Helper to calculate progress % for the dashboard
    public function getProgressAttribute()
    {
        $total = $this->tasks->count();
        if ($total == 0) return 0;

        $completed = $this->tasks->where('is_completed', true)->count();
        return round(($completed / $total) * 100);
    }
}