<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('type', 'Teacher')->get();
        $specialties = ['Mathematics', 'English', 'Science', 'History', 'Geography'];

        foreach ($teachers as $index => $teacher) {
            Teacher::create([
                'user_id' => $teacher->id,
                'subject_specialty' => $specialties[$index % count($specialties)],
                'hire_date' => now()->subYears(rand(1, 5)),
            ]);
        }
    }
}