<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class StudentController extends Controller
{
    /**
     * Display the specified student's details.
     */
    public function show($id)
    {
        $student = Student::with([
            'user',
            'college',
            'personalData',
            'familyData',
            'academicData',
            'learningResources',
            'psychosocialData',
            'needsAssessment',
            'appointments',
            'events'
        ])->findOrFail($id);

        return view('student.show', compact('student'));
    }
// In Student Controller
public function events()
{
    $student = Auth::user()->student;

    $events = Event::active()
        ->upcoming()
        ->forCollege($student->college_id)
        ->with(['colleges', 'registrations' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
        ->orderBy('event_start_date')
        ->orderBy('start_time')
        ->get();

    return view('student.events.index', compact('events'));
}
    /**
     * Display student details for counselor view.
     */
    public function showForCounselor($id)
    {
        $student = Student::with([
            'user',
            'college',
            'personalData',
            'familyData',
            'academicData',
            'learningResources',
            'psychosocialData',
            'needsAssessment',
            'appointments' => function($query) {
                $query->orderBy('appointment_date', 'desc');
            },
            'sessionNotes' => function($query) {
                $query->orderBy('session_date', 'desc');
            }
        ])->findOrFail($id);

        return view('counselor.students.profile', compact('student'));
    }
}
