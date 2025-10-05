<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $adminEmployee = Employee::create([
            'employee_id' => 'EMP001',
            'name' => 'Administrator',
            'email' => 'admin@archemi.com',
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'position' => 'System Administrator',
            'department_id' => 2, // IT Department
            'hire_date' => '2024-01-01',
            'salary' => 15000000,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@archemi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'employee_id' => $adminEmployee->id,
            'is_active' => true
        ]);

        // Create HR User
        $hrEmployee = Employee::create([
            'employee_id' => 'EMP002',
            'name' => 'HR Manager',
            'email' => 'hr@archemi.com',
            'phone' => '081234567891',
            'address' => 'Jakarta, Indonesia',
            'birth_date' => '1988-05-15',
            'gender' => 'female',
            'position' => 'HR Manager',
            'department_id' => 1, // HR Department
            'hire_date' => '2024-01-01',
            'salary' => 12000000,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@archemi.com',
            'password' => Hash::make('hr123'),
            'role' => 'hr',
            'employee_id' => $hrEmployee->id,
            'is_active' => true
        ]);

        // Create Sample Employee
        $employee = Employee::create([
            'employee_id' => 'EMP003',
            'name' => 'John Doe',
            'email' => 'john@archemi.com',
            'phone' => '081234567892',
            'address' => 'Jakarta, Indonesia',
            'birth_date' => '1995-03-20',
            'gender' => 'male',
            'position' => 'Software Developer',
            'department_id' => 2, // IT Department
            'hire_date' => '2024-02-01',
            'salary' => 8000000,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@archemi.com',
            'password' => Hash::make('employee123'),
            'role' => 'employee',
            'employee_id' => $employee->id,
            'is_active' => true
        ]);
    }
}
