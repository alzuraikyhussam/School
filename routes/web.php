<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/announcements', [PublicController::class, 'announcements'])->name('announcements');
Route::get('/announcements/{announcement}', [PublicController::class, 'showAnnouncement'])->name('announcements.show');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'sendContact'])->name('contact.send');

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile.edit');
    Route::patch('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile', [DashboardController::class, 'destroyProfile'])->name('profile.destroy');
    
    // Admin Routes
    Route::middleware(['role:Admin|Super Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Academic Management
        Route::resource('academic-years', AdminController::class)->names([
            'index' => 'academic-years.index',
            'create' => 'academic-years.create',
            'store' => 'academic-years.store',
            'show' => 'academic-years.show',
            'edit' => 'academic-years.edit',
            'update' => 'academic-years.update',
            'destroy' => 'academic-years.destroy',
        ]);
        
        Route::resource('terms', AdminController::class)->names([
            'index' => 'terms.index',
            'create' => 'terms.create',
            'store' => 'terms.store',
            'show' => 'terms.show',
            'edit' => 'terms.edit',
            'update' => 'terms.update',
            'destroy' => 'terms.destroy',
        ]);
        
        Route::resource('classes', AdminController::class)->names([
            'index' => 'classes.index',
            'create' => 'classes.create',
            'store' => 'classes.store',
            'show' => 'classes.show',
            'edit' => 'classes.edit',
            'update' => 'classes.update',
            'destroy' => 'classes.destroy',
        ]);
        
        Route::resource('sections', AdminController::class)->names([
            'index' => 'sections.index',
            'create' => 'sections.create',
            'store' => 'sections.store',
            'show' => 'sections.show',
            'edit' => 'sections.edit',
            'update' => 'sections.update',
            'destroy' => 'sections.destroy',
        ]);
        
        Route::resource('subjects', AdminController::class)->names([
            'index' => 'subjects.index',
            'create' => 'subjects.create',
            'store' => 'subjects.store',
            'show' => 'subjects.show',
            'edit' => 'subjects.edit',
            'update' => 'subjects.update',
            'destroy' => 'subjects.destroy',
        ]);
        
        // User Management
        Route::resource('users', AdminController::class)->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);
        
        Route::resource('teachers', AdminController::class)->names([
            'index' => 'teachers.index',
            'create' => 'teachers.create',
            'store' => 'teachers.store',
            'show' => 'teachers.show',
            'edit' => 'teachers.edit',
            'update' => 'teachers.update',
            'destroy' => 'teachers.destroy',
        ]);
        
        Route::resource('students', AdminController::class)->names([
            'index' => 'students.index',
            'create' => 'students.create',
            'store' => 'students.store',
            'show' => 'students.show',
            'edit' => 'students.edit',
            'update' => 'students.update',
            'destroy' => 'students.destroy',
        ]);
        
        Route::resource('parents', AdminController::class)->names([
            'index' => 'parents.index',
            'create' => 'parents.create',
            'store' => 'parents.store',
            'show' => 'parents.show',
            'edit' => 'parents.edit',
            'update' => 'parents.update',
            'destroy' => 'parents.destroy',
        ]);
        
        // Enrollment Management
        Route::resource('enrollments', AdminController::class)->names([
            'index' => 'enrollments.index',
            'create' => 'enrollments.create',
            'store' => 'enrollments.store',
            'show' => 'enrollments.show',
            'edit' => 'enrollments.edit',
            'update' => 'enrollments.update',
            'destroy' => 'enrollments.destroy',
        ]);
        
        // Timetable Management
        Route::resource('timetables', AdminController::class)->names([
            'index' => 'timetables.index',
            'create' => 'timetables.create',
            'store' => 'timetables.store',
            'show' => 'timetables.show',
            'edit' => 'timetables.edit',
            'update' => 'timetables.update',
            'destroy' => 'timetables.destroy',
        ]);
        
        // Attendance Management
        Route::resource('attendance', AdminController::class)->names([
            'index' => 'attendance.index',
            'create' => 'attendance.create',
            'store' => 'attendance.store',
            'show' => 'attendance.show',
            'edit' => 'attendance.edit',
            'update' => 'attendance.update',
            'destroy' => 'attendance.destroy',
        ]);
        
        // Grade Management
        Route::resource('grades', AdminController::class)->names([
            'index' => 'grades.index',
            'create' => 'grades.create',
            'store' => 'grades.store',
            'show' => 'grades.show',
            'edit' => 'grades.edit',
            'update' => 'grades.update',
            'destroy' => 'grades.destroy',
        ]);
        
        // Report Cards
        Route::resource('report-cards', AdminController::class)->names([
            'index' => 'report-cards.index',
            'create' => 'report-cards.create',
            'store' => 'report-cards.store',
            'show' => 'report-cards.show',
            'edit' => 'report-cards.edit',
            'update' => 'report-cards.update',
            'destroy' => 'report-cards.destroy',
        ]);
        
        Route::get('report-cards/{reportCard}/pdf', [AdminController::class, 'generatePdf'])->name('report-cards.pdf');
        
        // Fee Management
        Route::resource('fees', AdminController::class)->names([
            'index' => 'fees.index',
            'create' => 'fees.create',
            'store' => 'fees.store',
            'show' => 'fees.show',
            'edit' => 'fees.edit',
            'update' => 'fees.update',
            'destroy' => 'fees.destroy',
        ]);
        
        Route::resource('payments', AdminController::class)->names([
            'index' => 'payments.index',
            'create' => 'payments.create',
            'store' => 'payments.store',
            'show' => 'payments.show',
            'edit' => 'payments.edit',
            'update' => 'payments.update',
            'destroy' => 'payments.destroy',
        ]);
        
        // Announcements
        Route::resource('announcements', AdminController::class)->names([
            'index' => 'announcements.index',
            'create' => 'announcements.create',
            'store' => 'announcements.store',
            'show' => 'announcements.show',
            'edit' => 'announcements.edit',
            'update' => 'announcements.update',
            'destroy' => 'announcements.destroy',
        ]);
        
        // Gallery
        Route::resource('gallery', AdminController::class)->names([
            'index' => 'gallery.index',
            'create' => 'gallery.create',
            'store' => 'gallery.store',
            'show' => 'gallery.show',
            'edit' => 'gallery.edit',
            'update' => 'gallery.update',
            'destroy' => 'gallery.destroy',
        ]);
        
        // Reports
        Route::get('reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('reports/attendance', [AdminController::class, 'attendanceReport'])->name('reports.attendance');
        Route::get('reports/grades', [AdminController::class, 'gradesReport'])->name('reports.grades');
        Route::get('reports/fees', [AdminController::class, 'feesReport'])->name('reports.fees');
    });
    
    // Teacher Routes
    Route::middleware(['role:Teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('timetable', [TeacherController::class, 'timetable'])->name('timetable');
        Route::get('attendance', [TeacherController::class, 'attendance'])->name('attendance');
        Route::post('attendance', [TeacherController::class, 'markAttendance'])->name('attendance.mark');
        Route::get('grades', [TeacherController::class, 'grades'])->name('grades');
        Route::post('grades', [TeacherController::class, 'saveGrades'])->name('grades.save');
        Route::get('students', [TeacherController::class, 'students'])->name('students');
    });
    
    // Parent Routes
    Route::middleware(['role:Parent'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/', [ParentController::class, 'dashboard'])->name('dashboard');
        Route::get('children', [ParentController::class, 'children'])->name('children');
        Route::get('attendance', [ParentController::class, 'attendance'])->name('attendance');
        Route::get('grades', [ParentController::class, 'grades'])->name('grades');
        Route::get('timetable', [ParentController::class, 'timetable'])->name('timetable');
        Route::get('fees', [ParentController::class, 'fees'])->name('fees');
        Route::post('fees/pay', [ParentController::class, 'payFees'])->name('fees.pay');
    });
    
    // Student Routes
    Route::middleware(['role:Student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('profile', [StudentController::class, 'profile'])->name('profile');
        Route::get('attendance', [StudentController::class, 'attendance'])->name('attendance');
        Route::get('grades', [StudentController::class, 'grades'])->name('grades');
        Route::get('timetable', [StudentController::class, 'timetable'])->name('timetable');
        Route::get('report-card', [StudentController::class, 'reportCard'])->name('report-card');
    });
});

// API Routes for AJAX requests
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('students/search', [AdminController::class, 'searchStudents'])->name('students.search');
    Route::get('classes/{class}/sections', [AdminController::class, 'getSections'])->name('classes.sections');
    Route::get('attendance/date/{date}', [AdminController::class, 'getAttendanceByDate'])->name('attendance.by-date');
    Route::post('attendance/bulk', [AdminController::class, 'bulkAttendance'])->name('attendance.bulk');
});