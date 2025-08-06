<?php

namespace Database\Seeders;

use App\Models\Timetable;
use App\Models\Class as SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::take(3)->get();
        $sections = Section::take(3)->get();
        $subjects = Subject::take(6)->get();
        $teachers = User::where('type', 'Teacher')->get();
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];

        foreach ($classes as $class) {
            foreach ($sections as $section) {
                foreach ($days as $dayIndex => $day) {
                    for ($period = 1; $period <= 6; $period++) {
                        Timetable::create([
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'day_of_week' => $day,
                            'period' => $period,
                            'subject_id' => $subjects[($dayIndex + $period) % $subjects->count()]->id,
                            'teacher_id' => $teachers[($dayIndex + $period) % $teachers->count()]->id,
                        ]);
                    }
                }
            }
        }
    }
}