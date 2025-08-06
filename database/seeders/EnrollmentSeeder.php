<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Class as SchoolClass;
use App\Models\Section;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('type', 'Student')->get();
        $academicYear = AcademicYear::where('is_active', true)->first();
        $term = Term::where('academic_year_id', $academicYear->id)->first();
        $classes = SchoolClass::take(3)->get();
        $sections = Section::take(3)->get();

        foreach ($students as $index => $student) {
            Enrollment::create([
                'student_id' => $student->id,
                'academic_year_id' => $academicYear->id,
                'term_id' => $term->id,
                'class_id' => $classes[$index % $classes->count()]->id,
                'section_id' => $sections[$index % $sections->count()]->id,
                'enrollment_date' => now(),
            ]);
        }
    }
}