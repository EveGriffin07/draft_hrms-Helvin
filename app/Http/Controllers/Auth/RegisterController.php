<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register'); // your register.blade.php
    }

    public function register(Request $request)
{
    // 1. Validate input
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // 2. Create user (default: applicant)
    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'applicant',
    ]);

    // 3. Redirect after register
    return redirect()->route('login')->with('success', 'Registration successful. Please login.');
}

}
