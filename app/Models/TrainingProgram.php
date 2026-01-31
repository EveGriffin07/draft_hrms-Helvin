<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    protected $primaryKey = 'training_id';
    
    protected $fillable = [
        'department_id',
        'training_name',
        'tr_description',
        'start_date',
        'end_date',
        'provider', // We will store 'Trainer Name' here
        'tr_status',
        'mode',
        'location'
    ];

    // Relationship: A program has many enrolled employees
    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class, 'training_id', 'training_id');
    }

    // Relationship: Get the Department details
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}