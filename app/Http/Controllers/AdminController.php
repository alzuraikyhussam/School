<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Class as SchoolClass;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\Gallery;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\ReportCard;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDF;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'students' => User::where('type', 'Student')->count(),
            'teachers' => User::where('type', 'Teacher')->count(),
            'parents' => User::where('type', 'Parent')->count(),
            'classes' => SchoolClass::count(),
            'subjects' => Subject::count(),
            'enrollments' => Enrollment::count(),
            'attendance_today' => Attendance::whereDate('date', today())->count(),
            'payments_this_month' => Payment::whereMonth('paid_at', now()->month)->sum('amount_paid'),
        ];

        $recentEnrollments = Enrollment::with(['student', 'class', 'section'])->latest()->take(5)->get();
        $recentPayments = Payment::with(['parent', 'fee'])->latest()->take(5)->get();
        $recentAnnouncements = Announcement::with('author')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEnrollments', 'recentPayments', 'recentAnnouncements'));
    }

    // Academic Years
    public function academicYears()
    {
        $academicYears = AcademicYear::latest()->paginate(10);
        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function createAcademicYear()
    {
        return view('admin.academic-years.create');
    }

    public function storeAcademicYear(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        if ($validated['is_active']) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create($validated);

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Academic year created successfully.');
    }

    // Terms
    public function terms()
    {
        $terms = Term::with('academicYear')->latest()->paginate(10);
        return view('admin.terms.index', compact('terms'));
    }

    public function createTerm()
    {
        $academicYears = AcademicYear::all();
        return view('admin.terms.create', compact('academicYears'));
    }

    public function storeTerm(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Term::create($validated);

        return redirect()->route('admin.terms.index')
            ->with('success', 'Term created successfully.');
    }

    // Classes
    public function classes()
    {
        $classes = SchoolClass::withCount('sections')->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function createClass()
    {
        return view('admin.classes.create');
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes',
        ]);

        SchoolClass::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    // Sections
    public function sections()
    {
        $sections = Section::with('class')->paginate(10);
        return view('admin.sections.index', compact('sections'));
    }

    public function createSection()
    {
        $classes = SchoolClass::all();
        return view('admin.sections.create', compact('classes'));
    }

    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
        ]);

        Section::create($validated);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    // Subjects
    public function subjects()
    {
        $subjects = Subject::paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function createSubject()
    {
        return view('admin.subjects.create');
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    // Users
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:Admin,Teacher,Parent,Student',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Assign role based on type
        $user->assignRole($validated['type']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    // Teachers
    public function teachers()
    {
        $teachers = Teacher::with('user')->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function createTeacher()
    {
        $users = User::where('type', 'Teacher')->whereDoesntHave('teacher')->get();
        return view('admin.teachers.create', compact('users'));
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:teachers',
            'subject_specialty' => 'required|string|max:255',
            'hire_date' => 'required|date',
        ]);

        Teacher::create($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher profile created successfully.');
    }

    // Students
    public function students()
    {
        $students = Student::with('user')->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function createStudent()
    {
        $users = User::where('type', 'Student')->whereDoesntHave('student')->get();
        return view('admin.students.create', compact('users'));
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:students',
            'dob' => 'required|date',
            'gender' => 'required|in:M,F',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student profile created successfully.');
    }

    // Enrollments
    public function enrollments()
    {
        $enrollments = Enrollment::with(['student', 'academicYear', 'term', 'class', 'section'])->paginate(10);
        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function createEnrollment()
    {
        $students = User::where('type', 'Student')->get();
        $academicYears = AcademicYear::all();
        $terms = Term::all();
        $classes = SchoolClass::all();
        $sections = Section::all();

        return view('admin.enrollments.create', compact('students', 'academicYears', 'terms', 'classes', 'sections'));
    }

    public function storeEnrollment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'required|exists:terms,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'enrollment_date' => 'required|date',
        ]);

        Enrollment::create($validated);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment created successfully.');
    }

    // Timetables
    public function timetables()
    {
        $timetables = Timetable::with(['class', 'section', 'subject', 'teacher'])->paginate(10);
        return view('admin.timetables.index', compact('timetables'));
    }

    public function createTimetable()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $teachers = User::where('type', 'Teacher')->get();

        return view('admin.timetables.create', compact('classes', 'sections', 'subjects', 'teachers'));
    }

    public function storeTimetable(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'day_of_week' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat',
            'period' => 'required|integer|min:1|max:10',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        Timetable::create($validated);

        return redirect()->route('admin.timetables.index')
            ->with('success', 'Timetable entry created successfully.');
    }

    // Attendance
    public function attendance()
    {
        $attendance = Attendance::with(['student', 'timetable'])->latest()->paginate(10);
        return view('admin.attendance.index', compact('attendance'));
    }

    public function createAttendance()
    {
        $students = User::where('type', 'Student')->get();
        $timetables = Timetable::all();
        return view('admin.attendance.create', compact('students', 'timetables'));
    }

    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'timetable_id' => 'required|exists:timetables,id',
            'status' => 'required|in:Present,Absent,Late',
            'date' => 'required|date',
        ]);

        Attendance::create($validated);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance marked successfully.');
    }

    // Grades
    public function grades()
    {
        $grades = Grade::with(['enrollment', 'subject', 'term'])->paginate(10);
        return view('admin.grades.index', compact('grades'));
    }

    public function createGrade()
    {
        $enrollments = Enrollment::all();
        $subjects = Subject::all();
        $terms = Term::all();
        return view('admin.grades.create', compact('enrollments', 'subjects', 'terms'));
    }

    public function storeGrade(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'subject_id' => 'required|exists:subjects,id',
            'term_id' => 'required|exists:terms,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        Grade::create($validated);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade recorded successfully.');
    }

    // Report Cards
    public function reportCards()
    {
        $reportCards = ReportCard::with(['enrollment', 'term'])->paginate(10);
        return view('admin.report-cards.index', compact('reportCards'));
    }

    public function generatePdf(ReportCard $reportCard)
    {
        $pdf = PDF::loadView('admin.report-cards.pdf', compact('reportCard'));
        return $pdf->download('report-card.pdf');
    }

    // Fees
    public function fees()
    {
        $fees = Fee::with('academicYear')->paginate(10);
        return view('admin.fees.index', compact('fees'));
    }

    public function createFee()
    {
        $academicYears = AcademicYear::all();
        return view('admin.fees.create', compact('academicYears'));
    }

    public function storeFee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        Fee::create($validated);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee created successfully.');
    }

    // Payments
    public function payments()
    {
        $payments = Payment::with(['parent', 'fee'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function createPayment()
    {
        $parents = User::where('type', 'Parent')->get();
        $fees = Fee::all();
        return view('admin.payments.create', compact('parents', 'fees'));
    }

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required|exists:users,id',
            'fee_id' => 'required|exists:fees,id',
            'amount_paid' => 'required|numeric|min:0',
            'paid_at' => 'required|date',
            'method' => 'required|in:Cash,Bank,Online',
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    // Announcements
    public function announcements()
    {
        $announcements = Announcement::with('author')->latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function createAnnouncement()
    {
        return view('admin.announcements.create');
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['author_id'] = auth()->id();

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    // Gallery
    public function gallery()
    {
        $gallery = Gallery::with('uploadedBy')->latest()->paginate(10);
        return view('admin.gallery.index', compact('gallery'));
    }

    public function createGallery()
    {
        return view('admin.gallery.create');
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['image_url'] = $request->file('image')->store('gallery', 'public');
        $validated['uploaded_by'] = auth()->id();
        $validated['uploaded_at'] = now();

        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Image uploaded successfully.');
    }

    // Reports
    public function reports()
    {
        return view('admin.reports.index');
    }

    public function attendanceReport()
    {
        $attendance = Attendance::with(['student', 'timetable'])
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->get();

        return view('admin.reports.attendance', compact('attendance'));
    }

    public function gradesReport()
    {
        $grades = Grade::with(['enrollment.student', 'subject', 'term'])
            ->whereHas('term', function($query) {
                $query->where('academic_year_id', AcademicYear::active()->first()->id);
            })
            ->get();

        return view('admin.reports.grades', compact('grades'));
    }

    public function feesReport()
    {
        $payments = Payment::with(['parent', 'fee'])
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get();

        return view('admin.reports.fees', compact('payments'));
    }

    // API Methods
    public function searchStudents(Request $request)
    {
        $query = $request->get('q');
        $students = User::where('type', 'Student')
            ->where('full_name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'full_name', 'username']);

        return response()->json($students);
    }

    public function getSections(SchoolClass $class)
    {
        $sections = $class->sections;
        return response()->json($sections);
    }

    public function getAttendanceByDate($date)
    {
        $attendance = Attendance::with(['student', 'timetable'])
            ->whereDate('date', $date)
            ->get();

        return response()->json($attendance);
    }

    public function bulkAttendance(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:users,id',
            'attendance.*.timetable_id' => 'required|exists:timetables,id',
            'attendance.*.status' => 'required|in:Present,Absent,Late',
        ]);

        foreach ($validated['attendance'] as $record) {
            $record['date'] = $validated['date'];
            Attendance::create($record);
        }

        return response()->json(['message' => 'Attendance marked successfully']);
    }
}