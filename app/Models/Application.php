<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $primaryKey = 'application_id';

    public function applicant() {
    // Assuming linked to ApplicantProfile or User
    return $this->belongsTo(ApplicantProfile::class, 'applicant_id'); 
}

public function job() {
    return $this->belongsTo(JobPost::class, 'job_id');
}
}
