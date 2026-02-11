<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeRecord extends Model
{
    protected $primaryKey = 'ot_id';

    protected $fillable = [
        'employee_id',
        'period_id',
        'date',
        'hours',
        'rate_type',
        'ot_status',
        'reason',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
        'rate_type' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function period()
    {
        return $this->belongsTo(PayrollPeriod::class, 'period_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }
}
