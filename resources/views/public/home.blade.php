@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">
                    Welcome to <span class="text-warning">School Management</span>
                </h1>
                <p class="lead mb-4">
                    Empowering education through technology. Our comprehensive school management system provides everything you need to manage your educational institution efficiently.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('login') }}" class="btn btn-warning btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="text-center">
                    <i class="bi bi-mortarboard-fill display-1 text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Why Choose Our System?</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Comprehensive features designed to streamline school operations and enhance learning outcomes.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-people-fill text-primary display-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Student Management</h5>
                        <p class="card-text text-muted">
                            Complete student information management including enrollment, attendance, and academic records.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-calendar-check text-success display-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Attendance Tracking</h5>
                        <p class="card-text text-muted">
                            Automated attendance tracking with real-time monitoring and detailed reports.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-graph-up text-info display-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Grade Management</h5>
                        <p class="card-text text-muted">
                            Comprehensive grade management system with automated report card generation.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-clock text-warning display-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Timetable Management</h5>
                        <p class="card-text text-muted">
                            Efficient timetable creation and management for classes, teachers, and subjects.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-cash-coin text-danger display-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Fee Management</h5>
                        <p class="card-text text-muted">
                            Complete fee management system with payment tracking and financial reports.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="700">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-bell text-primary display-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Communication</h5>
                        <p class="card-text text-muted">
                            Integrated communication system for announcements, notifications, and messaging.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                <div class="stats-card">
                    <div class="number counter" data-target="500">0</div>
                    <div class="label">Students</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card">
                    <div class="number counter" data-target="50">0</div>
                    <div class="label">Teachers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card">
                    <div class="number counter" data-target="20">0</div>
                    <div class="label">Classes</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card">
                    <div class="number counter" data-target="15">0</div>
                    <div class="label">Subjects</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Announcements -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Latest News & Announcements</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Stay updated with the latest news and important announcements from our school.
                </p>
            </div>
        </div>

        <div class="row g-4">
            @foreach($announcements ?? [] as $announcement)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-megaphone text-primary me-2"></i>
                            <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                        </div>
                        <h5 class="card-title fw-bold">{{ $announcement->title }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($announcement->content, 100) }}
                        </p>
                        <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-outline-primary btn-sm">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('announcements') }}" class="btn btn-primary btn-lg">
                View All Announcements
            </a>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="display-6 fw-bold mb-3">Ready to Get Started?</h2>
                <p class="lead mb-0">
                    Contact us today to learn more about our school management system and how it can benefit your institution.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <a href="{{ route('contact') }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</section>
@endsection