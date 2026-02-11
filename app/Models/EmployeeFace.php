<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFace extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'embedding',
        'model_name',
    ];

    protected $casts = [
        'embedding' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
