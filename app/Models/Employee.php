<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $primaryKey = 'employee_id';
    protected $guarded = [];

    // Relationship: Belongs to Department
    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Relationship: Belongs to Position
    public function position() {
        return $this->belongsTo(Position::class, 'position_id');
    }

    // Relationship: Belongs to User (Login account)
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: One Employee has many Attendance records
    public function attendance() {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    // Add this relationship method
    public function onboarding()
    {
        // One Employee has One Onboarding record
        return $this->hasOne(Onboarding::class, 'employee_id', 'employee_id');
    }

    public function employeeKpis()
    {
        // Links to the EmployeeKpi model using 'employee_id'
        return $this->hasMany(EmployeeKpi::class, 'employee_id', 'employee_id');
    }

    public function faceData()
    {
        return $this->hasOne(EmployeeFace::class, 'employee_id', 'employee_id');
    }
}
