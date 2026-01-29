<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantProfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'applicant_id';

    // Add this to allow mass assignment
    protected $fillable = [
        'user_id',       // <--- Critical for your fix
        'full_name',
        'phone',
        'location',
        'avatar_path',
        'resume_path',
        'email'          // Ideally add 'email' to your DB too, but for now this is fine
    ];

    // Optional: Link back to the User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}