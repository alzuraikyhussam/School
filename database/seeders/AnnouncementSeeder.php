<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('type', 'Admin')->first();

        $announcements = [
            [
                'title' => 'Welcome to the New Academic Year',
                'content' => 'We are excited to welcome all students and parents to the new academic year. Please ensure all required documents are submitted by the end of this week.',
            ],
            [
                'title' => 'Parent-Teacher Meeting Schedule',
                'content' => 'Parent-teacher meetings will be held on Friday, September 15th, 2024. Please check your email for your scheduled time slot.',
            ],
            [
                'title' => 'Sports Day Announcement',
                'content' => 'Annual Sports Day will be held on October 20th, 2024. All students are encouraged to participate in various events.',
            ],
            [
                'title' => 'Library Hours Update',
                'content' => 'The school library will now be open from 8:00 AM to 4:00 PM on weekdays. Students can borrow books during lunch breaks.',
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create([
                'title' => $announcement['title'],
                'content' => $announcement['content'],
                'author_id' => $admin->id,
            ]);
        }
    }
}