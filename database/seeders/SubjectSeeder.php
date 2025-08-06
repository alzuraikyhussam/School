<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Mathematics',
            'English',
            'Science',
            'History',
            'Geography',
            'Physics',
            'Chemistry',
            'Biology',
            'Computer Science',
            'Art',
            'Music',
            'Physical Education',
            'Literature',
            'Economics',
            'Business Studies',
            'French',
            'Spanish',
            'Arabic',
        ];

        foreach ($subjects as $subject) {
            Subject::create(['name' => $subject]);
        }
    }
}