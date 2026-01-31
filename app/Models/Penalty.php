<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $primaryKey = 'penalty_id';
<<<<<<< HEAD
=======

    protected $fillable = [
        'employee_id',
        'attendance_id',
        'penalty_name',
        'default_amount',
        'assigned_at',
        'removed_at',
        'status',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'removed_at'  => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
>>>>>>> chai-training
}
