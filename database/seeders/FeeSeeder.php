<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        $fees = [
            ['name' => 'Tuition Fee', 'amount' => 5000.00],
            ['name' => 'Library Fee', 'amount' => 200.00],
            ['name' => 'Laboratory Fee', 'amount' => 300.00],
            ['name' => 'Sports Fee', 'amount' => 150.00],
            ['name' => 'Transportation Fee', 'amount' => 800.00],
        ];

        foreach ($fees as $fee) {
            Fee::create([
                'name' => $fee['name'],
                'amount' => $fee['amount'],
                'academic_year_id' => $academicYear->id,
            ]);
        }
    }
}