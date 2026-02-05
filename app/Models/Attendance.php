<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {
    protected $table = 'attendance'; // Because 'attendance' is singular, Laravel might look for 'attendances'
    protected $primaryKey = 'attendance_id';
    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
