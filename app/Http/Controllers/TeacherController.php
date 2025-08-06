<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = auth()->user();
        
        $stats = [
            'total_students' => Enrollment::whereHas('class', function($query) use ($teacher) {
                $query->whereHas('timetables', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                });
            })->count(),
            'classes_today' => Timetable::where('teacher_id', $teacher->id)
                ->where('day_of_week', now()->format('D'))
                ->count(),
            'attendance_today' => Attendance::whereHas('timetable', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->whereDate('date', today())->count(),
            'grades_entered' => Grade::whereHas('enrollment', function($query) use ($teacher) {
                $query->whereHas('class', function($q) use ($teacher) {
                    $q->whereHas('timetables', function($t) use ($teacher) {
                        $t->where('teacher_id', $teacher->id);
                    });
                });
            })->count(),
        ];

        $myTimetable = Timetable::where('teacher_id', $teacher->id)
            ->with(['class', 'section', 'subject'])
            ->orderBy('day_of_week')
            ->orderBy('period')
            ->get();

        $recentAttendance = Attendance::whereHas('timetable', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with(['student', 'timetable'])->latest()->take(5)->get();

        return view('teacher.dashboard', compact('stats', 'myTimetable', 'recentAttendance'));
    }

    public function timetable()
    {
        $teacher = auth()->user();
        
        $timetable = Timetable::where('teacher_id', $teacher->id)
            ->with(['class', 'section', 'subject'])
            ->orderBy('day_of_week')
            ->orderBy('period')
            ->get()
            ->groupBy('day_of_week');

        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        return view('teacher.timetable', compact('timetable', 'days'));
    }

    public function attendance()
    {
        $teacher = auth()->user();
        
        $classes = Timetable::where('teacher_id', $teacher->id)
            ->with(['class', 'section'])
            ->get()
            ->unique(function ($item) {
                return $item->class_id . '-' . $item->section_id;
            });

        $selectedDate = request('date', today()->format('Y-m-d'));
        $selectedClass = request('class');

        $attendance = collect();
        if ($selectedClass) {
            $attendance = Attendance::whereHas('timetable', function($query) use ($teacher, $selectedClass) {
                $query->where('teacher_id', $teacher->id)
                    ->where('class_id', explode('-', $selectedClass)[0])
                    ->where('section_id', explode('-', $selectedClass)[1]);
            })->whereDate('date', $selectedDate)
            ->with(['student', 'timetable'])
            ->get();
        }

        return view('teacher.attendance', compact('classes', 'attendance', 'selectedDate', 'selectedClass'));
    }

    public function markAttendance(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:users,id',
            'attendance.*.status' => 'required|in:Present,Absent,Late',
        ]);

        $teacher = auth()->user();

        // Get timetable entries for this teacher, class, and section
        $timetables = Timetable::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->get();

        foreach ($validated['attendance'] as $record) {
            foreach ($timetables as $timetable) {
                // Check if attendance already exists for this student, timetable, and date
                $existingAttendance = Attendance::where('student_id', $record['student_id'])
                    ->where('timetable_id', $timetable->id)
                    ->whereDate('date', $validated['date'])
                    ->first();

                if ($existingAttendance) {
                    $existingAttendance->update(['status' => $record['status']]);
                } else {
                    Attendance::create([
                        'student_id' => $record['student_id'],
                        'timetable_id' => $timetable->id,
                        'status' => $record['status'],
                        'date' => $validated['date'],
                    ]);
                }
            }
        }

        return redirect()->route('teacher.attendance')
            ->with('success', 'Attendance marked successfully.');
    }

    public function grades()
    {
        $teacher = auth()->user();
        
        $enrollments = Enrollment::whereHas('class', function($query) use ($teacher) {
            $query->whereHas('timetables', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            });
        })->with(['student', 'class', 'section'])->get();

        $subjects = Timetable::where('teacher_id', $teacher->id)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id');

        $terms = \App\Models\Term::all();

        $selectedEnrollment = request('enrollment');
        $selectedSubject = request('subject');
        $selectedTerm = request('term');

        $grades = collect();
        if ($selectedEnrollment && $selectedSubject && $selectedTerm) {
            $grades = Grade::where('enrollment_id', $selectedEnrollment)
                ->where('subject_id', $selectedSubject)
                ->where('term_id', $selectedTerm)
                ->with(['enrollment.student'])
                ->get();
        }

        return view('teacher.grades', compact('enrollments', 'subjects', 'terms', 'grades', 'selectedEnrollment', 'selectedSubject', 'selectedTerm'));
    }

    public function saveGrades(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'subject_id' => 'required|exists:subjects,id',
            'term_id' => 'required|exists:terms,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            'grades.*.score' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated['grades'] as $gradeData) {
            $existingGrade = Grade::where('enrollment_id', $validated['enrollment_id'])
                ->where('subject_id', $validated['subject_id'])
                ->where('term_id', $validated['term_id'])
                ->whereHas('enrollment', function($query) use ($gradeData) {
                    $query->where('student_id', $gradeData['student_id']);
                })
                ->first();

            if ($existingGrade) {
                $existingGrade->update(['score' => $gradeData['score']]);
            } else {
                // Find the enrollment for this student
                $enrollment = Enrollment::where('student_id', $gradeData['student_id'])
                    ->where('id', $validated['enrollment_id'])
                    ->first();

                if ($enrollment) {
                    Grade::create([
                        'enrollment_id' => $enrollment->id,
                        'subject_id' => $validated['subject_id'],
                        'term_id' => $validated['term_id'],
                        'score' => $gradeData['score'],
                    ]);
                }
            }
        }

        return redirect()->route('teacher.grades')
            ->with('success', 'Grades saved successfully.');
    }

    public function students()
    {
        $teacher = auth()->user();
        
        $students = User::where('type', 'Student')
            ->whereHas('enrollments', function($query) use ($teacher) {
                $query->whereHas('class', function($q) use ($teacher) {
                    $q->whereHas('timetables', function($t) use ($teacher) {
                        $t->where('teacher_id', $teacher->id);
                    });
                });
            })
            ->with(['enrollments.class', 'enrollments.section', 'student'])
            ->paginate(15);

        return view('teacher.students', compact('students'));
    }
}