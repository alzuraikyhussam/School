<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = \App\Models\AcademicYear::where('is_active', true)->first();

        Term::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'First Term',
            'start_date' => '2024-09-01',
            'end_date' => '2024-12-20',
        ]);

        Term::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Second Term',
            'start_date' => '2025-01-06',
            'end_date' => '2025-03-28',
        ]);

        Term::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Third Term',
            'start_date' => '2025-04-07',
            'end_date' => '2025-06-30',
        ]);
    }
}