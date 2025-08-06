<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = auth()->user();
        
        // Get children (students) of this parent
        $children = User::where('type', 'Student')
            ->whereHas('enrollments')
            ->get(); // In a real system, you'd have a parent-child relationship

        $stats = [
            'total_children' => $children->count(),
            'total_fees_paid' => Payment::where('parent_id', $parent->id)->sum('amount_paid'),
            'pending_fees' => Fee::sum('amount') - Payment::where('parent_id', $parent->id)->sum('amount_paid'),
            'attendance_rate' => $this->calculateAttendanceRate($children),
        ];

        $recentPayments = Payment::where('parent_id', $parent->id)
            ->with('fee')
            ->latest()
            ->take(5)
            ->get();

        $recentGrades = Grade::whereHas('enrollment', function($query) use ($children) {
            $query->whereIn('student_id', $children->pluck('id'));
        })->with(['enrollment.student', 'subject'])
        ->latest()
        ->take(5)
        ->get();

        return view('parent.dashboard', compact('stats', 'children', 'recentPayments', 'recentGrades'));
    }

    public function children()
    {
        $parent = auth()->user();
        
        $children = User::where('type', 'Student')
            ->whereHas('enrollments')
            ->with(['enrollments.class', 'enrollments.section', 'student'])
            ->get();

        return view('parent.children', compact('children'));
    }

    public function attendance()
    {
        $parent = auth()->user();
        
        $children = User::where('type', 'Student')
            ->whereHas('enrollments')
            ->get();

        $selectedChild = request('child', $children->first()?->id);
        $selectedMonth = request('month', now()->format('Y-m'));

        $attendance = collect();
        if ($selectedChild) {
            $attendance = Attendance::where('student_id', $selectedChild)
                ->whereYear('date', explode('-', $selectedMonth)[0])
                ->whereMonth('date', explode('-', $selectedMonth)[1])
                ->with(['timetable.subject', 'timetable.class', 'timetable.section'])
                ->get()
                ->groupBy('date');
        }

        return view('parent.attendance', compact('children', 'attendance', 'selectedChild', 'selectedMonth'));
    }

    public function grades()
    {
        $parent = auth()->user();
        
        $children = User::where('type', 'Student')
            ->whereHas('enrollments')
            ->get();

        $selectedChild = request('child', $children->first()?->id);
        $selectedTerm = request('term');

        $grades = collect();
        if ($selectedChild && $selectedTerm) {
            $grades = Grade::whereHas('enrollment', function($query) use ($selectedChild) {
                $query->where('student_id', $selectedChild);
            })->where('term_id', $selectedTerm)
            ->with(['subject', 'term'])
            ->get()
            ->groupBy('subject.name');
        }

        $terms = \App\Models\Term::all();

        return view('parent.grades', compact('children', 'grades', 'selectedChild', 'selectedTerm', 'terms'));
    }

    public function timetable()
    {
        $parent = auth()->user();
        
        $children = User::where('type', 'Student')
            ->whereHas('enrollments')
            ->get();

        $selectedChild = request('child', $children->first()?->id);

        $timetable = collect();
        if ($selectedChild) {
            $enrollment = Enrollment::where('student_id', $selectedChild)->first();
            if ($enrollment) {
                $timetable = Timetable::where('class_id', $enrollment->class_id)
                    ->where('section_id', $enrollment->section_id)
                    ->with(['subject', 'teacher'])
                    ->orderBy('day_of_week')
                    ->orderBy('period')
                    ->get()
                    ->groupBy('day_of_week');
            }
        }

        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        return view('parent.timetable', compact('children', 'timetable', 'selectedChild', 'days'));
    }

    public function fees()
    {
        $parent = auth()->user();
        
        $fees = Fee::with('academicYear')->get();
        $payments = Payment::where('parent_id', $parent->id)
            ->with('fee')
            ->latest()
            ->get();

        $feeSummary = [];
        foreach ($fees as $fee) {
            $paid = $payments->where('fee_id', $fee->id)->sum('amount_paid');
            $feeSummary[] = [
                'fee' => $fee,
                'total_amount' => $fee->amount,
                'paid_amount' => $paid,
                'remaining_amount' => $fee->amount - $paid,
                'status' => $paid >= $fee->amount ? 'Paid' : ($paid > 0 ? 'Partial' : 'Unpaid'),
            ];
        }

        return view('parent.fees', compact('feeSummary', 'payments'));
    }

    public function payFees(Request $request)
    {
        $validated = $request->validate([
            'fee_id' => 'required|exists:fees,id',
            'amount_paid' => 'required|numeric|min:0',
            'method' => 'required|in:Cash,Bank,Online',
        ]);

        $parent = auth()->user();

        Payment::create([
            'parent_id' => $parent->id,
            'fee_id' => $validated['fee_id'],
            'amount_paid' => $validated['amount_paid'],
            'paid_at' => now(),
            'method' => $validated['method'],
        ]);

        return redirect()->route('parent.fees')
            ->with('success', 'Payment recorded successfully.');
    }

    private function calculateAttendanceRate($children)
    {
        if ($children->isEmpty()) {
            return 0;
        }

        $totalAttendance = 0;
        $totalSessions = 0;

        foreach ($children as $child) {
            $attendance = Attendance::where('student_id', $child->id)
                ->whereMonth('date', now()->month)
                ->get();

            $totalAttendance += $attendance->where('status', 'Present')->count();
            $totalSessions += $attendance->count();
        }

        return $totalSessions > 0 ? round(($totalAttendance / $totalSessions) * 100, 2) : 0;
    }
}