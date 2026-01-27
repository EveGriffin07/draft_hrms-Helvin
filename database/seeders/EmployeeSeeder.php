<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Dummy Department (if one doesn't exist)
        $dept = Department::firstOrCreate(
            ['department_name' => 'IT Department'], // Check this
            ['de_description' => 'Information Technology'] // Create this if missing
        );

        // 2. Create a Dummy Position
        $pos = Position::firstOrCreate(
            ['position_name' => 'Software Engineer'],
            ['pos_description' => 'Standard Dev Role']
        );

        // 3. Create the User Login (The Account)
        $user = User::create([
            'user_id' => '3', // We use ID 2 because Admin is ID 1
            'name' => 'John Employee',
            'email' => 'employee@example.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
        ]);

        // 4. Create the Employee Profile (The Data)
        Employee::create([
            'user_id' => $user->user_id,         // Link to the User above
            'department_id' => $dept->department_id, // Link to Dept above
            'position_id' => $pos->position_id,   // Link to Position above
            'employee_code' => 'EMP-001',
            'employee_status' => 'active',
            'hire_date' => now(),
            'base_salary' => 5000.00,
            'phone' => '0123456789',
            'address' => '123 Tech Street'
        ]);
    }
}