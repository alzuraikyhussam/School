<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function home()
    {
        $announcements = Announcement::latest()->take(3)->get();
        
        return view('public.home', compact('announcements'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function announcements()
    {
        $announcements = Announcement::latest()->paginate(10);
        
        return view('public.announcements', compact('announcements'));
    }

    public function showAnnouncement(Announcement $announcement)
    {
        return view('public.announcement-show', compact('announcement'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you would typically send an email
        // For now, we'll just redirect with a success message
        
        return redirect()->route('contact')
            ->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}