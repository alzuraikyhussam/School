<?php

namespace Database\Seeders;

use App\Models\ClassSubject;
use App\Models\Class as SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::take(5)->get(); // First 5 classes
        $subjects = Subject::take(8)->get(); // First 8 subjects
        $teachers = User::where('type', 'Teacher')->get();

        foreach ($classes as $class) {
            foreach ($subjects->take(6) as $index => $subject) {
                ClassSubject::create([
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teachers[$index % $teachers->count()]->id,
                ]);
            }
        }
    }
}