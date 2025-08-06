<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AcademicYearSeeder::class,
            TermSeeder::class,
            ClassSeeder::class,
            SectionSeeder::class,
            SubjectSeeder::class,
            UserSeeder::class,
            ParentSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            ClassSubjectSeeder::class,
            EnrollmentSeeder::class,
            TimetableSeeder::class,
            FeeSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}