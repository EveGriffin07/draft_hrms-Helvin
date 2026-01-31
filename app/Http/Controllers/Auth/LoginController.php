<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
<<<<<<< HEAD
        return view('auth.login'); 
=======
        return view('auth.login'); // your login.blade.php
>>>>>>> chai-training
    }

    public function login(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

<<<<<<< HEAD
        // 2. Attempt Login
=======
        // 2. Auth::attempt will use email + getAuthPassword() from User model
>>>>>>> chai-training
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
<<<<<<< HEAD
            $request->session()->regenerate(); 

            $user = Auth::user();

            // 3. Redirect based on Role column in `users` table
=======
            $request->session()->regenerate(); // prevent session fixation

            $user = Auth::user();

            // 3. Redirect based on role
>>>>>>> chai-training
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'employee') {
                return redirect()->route('employee.dashboard');
            }

<<<<<<< HEAD
            // Default fallback for applicants
=======
            // default: applicant
>>>>>>> chai-training
            return redirect()->route('applicant.jobs');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> chai-training
