<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\ReportCard;
use App\Models\Timetable;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = auth()->user();
        
        $enrollment = Enrollment::where('student_id', $student->id)->first();
        
        $stats = [
            'attendance_rate' => $this->calculateAttendanceRate($student),
            'average_grade' => $this->calculateAverageGrade($student),
            'total_subjects' => $enrollment ? Timetable::where('class_id', $enrollment->class_id)
                ->where('section_id', $enrollment->section_id)
                ->distinct('subject_id')
                ->count() : 0,
            'classes_today' => $enrollment ? Timetable::where('class_id', $enrollment->class_id)
                ->where('section_id', $enrollment->section_id)
                ->where('day_of_week', now()->format('D'))
                ->count() : 0,
        ];

        $recentAttendance = Attendance::where('student_id', $student->id)
            ->with(['timetable.subject', 'timetable.class', 'timetable.section'])
            ->latest()
            ->take(5)
            ->get();

        $recentGrades = Grade::whereHas('enrollment', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })->with(['subject', 'term'])
        ->latest()
        ->take(5)
        ->get();

        return view('student.dashboard', compact('stats', 'recentAttendance', 'recentGrades', 'enrollment'));
    }

    public function profile()
    {
        $student = auth()->user();
        $studentProfile = $student->student;
        $enrollment = Enrollment::where('student_id', $student->id)->first();

        return view('student.profile', compact('student', 'studentProfile', 'enrollment'));
    }

    public function attendance()
    {
        $student = auth()->user();
        $selectedMonth = request('month', now()->format('Y-m'));

        $attendance = Attendance::where('student_id', $student->id)
            ->whereYear('date', explode('-', $selectedMonth)[0])
            ->whereMonth('date', explode('-', $selectedMonth)[1])
            ->with(['timetable.subject', 'timetable.class', 'timetable.section'])
            ->get()
            ->groupBy('date');

        $attendanceStats = [
            'total_sessions' => $attendance->flatten()->count(),
            'present' => $attendance->flatten()->where('status', 'Present')->count(),
            'absent' => $attendance->flatten()->where('status', 'Absent')->count(),
            'late' => $attendance->flatten()->where('status', 'Late')->count(),
        ];

        return view('student.attendance', compact('attendance', 'attendanceStats', 'selectedMonth'));
    }

    public function grades()
    {
        $student = auth()->user();
        $selectedTerm = request('term');

        $grades = collect();
        if ($selectedTerm) {
            $grades = Grade::whereHas('enrollment', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })->where('term_id', $selectedTerm)
            ->with(['subject', 'term'])
            ->get()
            ->groupBy('subject.name');
        }

        $terms = \App\Models\Term::all();
        $gradeStats = $this->calculateGradeStats($student);

        return view('student.grades', compact('grades', 'selectedTerm', 'terms', 'gradeStats'));
    }

    public function timetable()
    {
        $student = auth()->user();
        $enrollment = Enrollment::where('student_id', $student->id)->first();

        $timetable = collect();
        if ($enrollment) {
            $timetable = Timetable::where('class_id', $enrollment->class_id)
                ->where('section_id', $enrollment->section_id)
                ->with(['subject', 'teacher'])
                ->orderBy('day_of_week')
                ->orderBy('period')
                ->get()
                ->groupBy('day_of_week');
        }

        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        return view('student.timetable', compact('timetable', 'days', 'enrollment'));
    }

    public function reportCard()
    {
        $student = auth()->user();
        $selectedTerm = request('term');

        $reportCard = null;
        $grades = collect();

        if ($selectedTerm) {
            $enrollment = Enrollment::where('student_id', $student->id)->first();
            
            if ($enrollment) {
                $reportCard = ReportCard::where('enrollment_id', $enrollment->id)
                    ->where('term_id', $selectedTerm)
                    ->first();

                $grades = Grade::where('enrollment_id', $enrollment->id)
                    ->where('term_id', $selectedTerm)
                    ->with(['subject', 'term'])
                    ->get();
            }
        }

        $terms = \App\Models\Term::all();

        return view('student.report-card', compact('reportCard', 'grades', 'selectedTerm', 'terms'));
    }

    private function calculateAttendanceRate($student)
    {
        $attendance = Attendance::where('student_id', $student->id)
            ->whereMonth('date', now()->month)
            ->get();

        $totalSessions = $attendance->count();
        $presentSessions = $attendance->where('status', 'Present')->count();

        return $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 2) : 0;
    }

    private function calculateAverageGrade($student)
    {
        $grades = Grade::whereHas('enrollment', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })->get();

        return $grades->count() > 0 ? round($grades->avg('score'), 2) : 0;
    }

    private function calculateGradeStats($student)
    {
        $grades = Grade::whereHas('enrollment', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })->get();

        $totalGrades = $grades->count();
        
        if ($totalGrades === 0) {
            return [
                'average' => 0,
                'highest' => 0,
                'lowest' => 0,
                'total_subjects' => 0,
            ];
        }

        return [
            'average' => round($grades->avg('score'), 2),
            'highest' => $grades->max('score'),
            'lowest' => $grades->min('score'),
            'total_subjects' => $grades->unique('subject_id')->count(),
        ];
    }
}