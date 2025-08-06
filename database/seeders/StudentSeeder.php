<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('type', 'Student')->get();
        $genders = ['M', 'F'];

        foreach ($students as $index => $student) {
            Student::create([
                'user_id' => $student->id,
                'dob' => now()->subYears(rand(6, 18)),
                'gender' => $genders[$index % count($genders)],
                'photo' => null,
            ]);
        }
    }
}