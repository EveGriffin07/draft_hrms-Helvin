<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validate
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Create User
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'applicant',
        ]);

        // 3. Create Profile
        // NOW THIS WORKS: We send the email, and we don't need to send a phone.
        ApplicantProfile::create([
            'user_id'   => $user->user_id,
            'full_name' => $request->name,
            'email'     => $request->email, // This is now allowed!
            // 'phone'  => (We can skip this now because we made it nullable)
        ]);

        // 4. Login
        Auth::login($user);

        return redirect()->route('applicant.jobs')
                         ->with('success', 'Registration successful! Welcome.');
    }
}