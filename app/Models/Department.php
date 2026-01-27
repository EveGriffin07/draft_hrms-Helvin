<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'department_id';
    protected $guarded = [];

    // Relationship: One Department has many Employees
    public function employees() {
        return $this->hasMany(Employee::class, 'department_id');
    }
}
