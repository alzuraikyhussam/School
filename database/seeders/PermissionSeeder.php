<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Academic Management
            'view_academic_years',
            'create_academic_years',
            'edit_academic_years',
            'delete_academic_years',
            
            'view_terms',
            'create_terms',
            'edit_terms',
            'delete_terms',
            
            'view_classes',
            'create_classes',
            'edit_classes',
            'delete_classes',
            
            'view_sections',
            'create_sections',
            'edit_sections',
            'delete_sections',
            
            'view_subjects',
            'create_subjects',
            'edit_subjects',
            'delete_subjects',
            
            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            'view_teachers',
            'create_teachers',
            'edit_teachers',
            'delete_teachers',
            
            'view_students',
            'create_students',
            'edit_students',
            'delete_students',
            
            'view_parents',
            'create_parents',
            'edit_parents',
            'delete_parents',
            
            // Enrollment Management
            'view_enrollments',
            'create_enrollments',
            'edit_enrollments',
            'delete_enrollments',
            
            // Timetable Management
            'view_timetables',
            'create_timetables',
            'edit_timetables',
            'delete_timetables',
            
            // Attendance Management
            'view_attendance',
            'create_attendance',
            'edit_attendance',
            'delete_attendance',
            
            // Grade Management
            'view_grades',
            'create_grades',
            'edit_grades',
            'delete_grades',
            
            // Report Cards
            'view_report_cards',
            'create_report_cards',
            'edit_report_cards',
            'delete_report_cards',
            
            // Fee Management
            'view_fees',
            'create_fees',
            'edit_fees',
            'delete_fees',
            
            'view_payments',
            'create_payments',
            'edit_payments',
            'delete_payments',
            
            // Announcements
            'view_announcements',
            'create_announcements',
            'edit_announcements',
            'delete_announcements',
            
            // Gallery
            'view_gallery',
            'create_gallery',
            'edit_gallery',
            'delete_gallery',
            
            // Notifications
            'view_notifications',
            'create_notifications',
            'edit_notifications',
            'delete_notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}