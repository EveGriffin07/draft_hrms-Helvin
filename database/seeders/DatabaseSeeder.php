<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\User;
use App\Models\ApplicantProfile;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Core Accounts (Admin & Employee from your specific seeders)
        $this->call([
            AdminSeeder::class,
            EmployeeSeeder::class,
        ]);

        // 2. Create Helper Data (KPIs, Departments, Positions)
        $this->call(KpiTemplateSeeder::class);
        $departments = Department::factory()->count(5)->create();
        $positions = Position::factory()->count(8)->create();

        // 3. Generate 20 Dummy EMPLOYEES
        User::factory(20)->create(['role' => 'employee'])->each(function ($user) use ($departments, $positions) {
            Employee::factory()->create([
                'user_id' => $user->user_id,
                'department_id' => $departments->random()->department_id,
                'position_id' => $positions->random()->position_id,
                'employee_status' => 'active',
            ]);
        });

        // 4. Generate 10 Dummy APPLICANTS (For Recruitment Module)
        // This creates a User, then an ApplicantProfile linked to it
        User::factory(10)->create(['role' => 'applicant'])->each(function ($user) {
            ApplicantProfile::factory()->create([
                'applicant_id' => $user->user_id, // Linking User ID to Applicant ID
                'full_name' => $user->name,
            ]);
        });

        // 5. Generate Transactional Data
        $this->call([
            PayrollSeeder::class,
            AttendanceSeeder::class, // Will generate ~100 rows as requested
        ]);
    }
}