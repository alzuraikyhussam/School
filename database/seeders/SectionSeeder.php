<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $classes = \App\Models\Class::all();
        $sections = ['A', 'B', 'C'];

        foreach ($classes as $class) {
            foreach ($sections as $section) {
                Section::create([
                    'class_id' => $class->id,
                    'name' => $section,
                ]);
            }
        }
    }
}