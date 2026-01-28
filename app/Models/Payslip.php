<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $primaryKey = 'payslip_id';

    public function period() {
    return $this->belongsTo(PayrollPeriod::class, 'period_id');
}
}
