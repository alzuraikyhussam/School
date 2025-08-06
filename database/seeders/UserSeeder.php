<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::create([
            'username' => 'superadmin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'full_name' => 'Super Administrator',
            'phone' => '+1234567890',
            'type' => 'Admin',
        ]);
        $superAdmin->assignRole('Super Admin');

        // Admin
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin2@school.com',
            'password' => Hash::make('password'),
            'full_name' => 'School Administrator',
            'phone' => '+1234567891',
            'type' => 'Admin',
        ]);
        $admin->assignRole('Admin');

        // Teachers
        $teachers = [
            [
                'username' => 'teacher1',
                'email' => 'teacher1@school.com',
                'full_name' => 'John Smith',
                'phone' => '+1234567892',
            ],
            [
                'username' => 'teacher2',
                'email' => 'teacher2@school.com',
                'full_name' => 'Sarah Johnson',
                'phone' => '+1234567893',
            ],
            [
                'username' => 'teacher3',
                'email' => 'teacher3@school.com',
                'full_name' => 'Michael Brown',
                'phone' => '+1234567894',
            ],
        ];

        foreach ($teachers as $teacherData) {
            $teacher = User::create([
                'username' => $teacherData['username'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password'),
                'full_name' => $teacherData['full_name'],
                'phone' => $teacherData['phone'],
                'type' => 'Teacher',
            ]);
            $teacher->assignRole('Teacher');
        }

        // Parents
        $parents = [
            [
                'username' => 'parent1',
                'email' => 'parent1@school.com',
                'full_name' => 'David Wilson',
                'phone' => '+1234567895',
            ],
            [
                'username' => 'parent2',
                'email' => 'parent2@school.com',
                'full_name' => 'Lisa Davis',
                'phone' => '+1234567896',
            ],
        ];

        foreach ($parents as $parentData) {
            $parent = User::create([
                'username' => $parentData['username'],
                'email' => $parentData['email'],
                'password' => Hash::make('password'),
                'full_name' => $parentData['full_name'],
                'phone' => $parentData['phone'],
                'type' => 'Parent',
            ]);
            $parent->assignRole('Parent');
        }

        // Students
        $students = [
            [
                'username' => 'student1',
                'email' => 'student1@school.com',
                'full_name' => 'Emma Wilson',
                'phone' => '+1234567897',
            ],
            [
                'username' => 'student2',
                'email' => 'student2@school.com',
                'full_name' => 'James Davis',
                'phone' => '+1234567898',
            ],
            [
                'username' => 'student3',
                'email' => 'student3@school.com',
                'full_name' => 'Sophia Brown',
                'phone' => '+1234567899',
            ],
        ];

        foreach ($students as $studentData) {
            $student = User::create([
                'username' => $studentData['username'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'),
                'full_name' => $studentData['full_name'],
                'phone' => $studentData['phone'],
                'type' => 'Student',
            ]);
            $student->assignRole('Student');
        }
    }
}