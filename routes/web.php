<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CounselorController;
use App\Http\Middleware\CounselorMiddleware;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SessionNoteController;
use App\Http\Controllers\CounselorAnnouncementController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Mental Health Corner route
    Route::get('/mental-health-corner', function () {
        return view('mhc'); // Your MHC blade file
    })->name('mhc');

    Route::get('/feedback', function () {
        return view('feedback'); // Your BAP blade file
    })->name('feedback');

    Route::get('/book-appointment', function () {
        return view('bap'); // Your BAP blade file
    })->name('bap');

    // Feedback routes
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback'); // Add this line
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Admin feedback management
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    });

    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('bap');

    Route::middleware(['auth'])->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/available-slots', [AppointmentController::class, 'getAvailableSlots'])->name('appointments.available-slots');

        Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

        // Counselor/Admin routes
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    });

    Route::middleware(['auth', CounselorMiddleware::class])->prefix('counselor')->group(function () {
        Route::get('/dashboard', [CounselorController::class, 'dashboard'])->name('counselor.dashboard');
        Route::get('/calendar', [CounselorController::class, 'calendar'])->name('counselor.calendar');
        Route::get('/appointments', [CounselorController::class, 'appointments'])->name('counselor.appointments');
        Route::patch('/appointments/{appointment}/status', [CounselorController::class, 'updateAppointmentStatus'])->name('counselor.appointments.update-status');
        Route::get('/appointments/{appointment}/details', [CounselorController::class, 'getAppointmentDetails'])->name('counselor.appointments.details');

        // Events management routes
        Route::get('/events', [EventController::class, 'index'])->name('counselor.events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('counselor.events.create');
        Route::post('/events', [EventController::class, 'store'])->name('counselor.events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('counselor.events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('counselor.events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('counselor.events.destroy');
        Route::patch('/events/{event}/toggle-status', [EventController::class, 'toggleStatus'])->name('counselor.events.toggle-status');

        Route::get('/events/{event}/registrations', [EventController::class, 'showRegistrations'])->name('counselor.events.registrations');
        Route::patch('/events/{event}/registrations/{registration}/status', [EventController::class, 'updateRegistrationStatus'])->name('counselor.events.update-registration-status');
        Route::get('/events/{event}/export-registrations', [EventController::class, 'exportRegistrations'])->name('counselor.events.export-registrations');

        // Resource management routes
        Route::get('/resources', [ResourceController::class, 'index'])->name('counselor.resources.index');
        Route::get('/resources/create', [ResourceController::class, 'create'])->name('counselor.resources.create');
        Route::post('/resources', [ResourceController::class, 'store'])->name('counselor.resources.store');
        Route::get('/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('counselor.resources.edit');
        Route::patch('/resources/{resource}', [ResourceController::class, 'update'])->name('counselor.resources.update');
        Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('counselor.resources.destroy');
        Route::patch('/resources/{resource}/status', [ResourceController::class, 'updateStatus'])->name('counselor.resources.update-status');

        // Session Notes Routes - CORRECTED ORDER
        // Dashboard route FIRST (specific route)
        Route::get('/session-notes/dashboard', [SessionNoteController::class, 'dashboard'])->name('counselor.session-notes.dashboard');

        // Student session notes
        Route::get('/students/{student}/session-notes', [SessionNoteController::class, 'index'])->name('counselor.session-notes.index');
        Route::get('/students/{student}/session-notes/create', [SessionNoteController::class, 'create'])->name('counselor.session-notes.create');
        Route::get('/appointments/{appointment}/session-notes/create', [SessionNoteController::class, 'createFromAppointment'])->name('counselor.session-notes.create-from-appointment');
        Route::post('/students/{student}/session-notes', [SessionNoteController::class, 'store'])->name('counselor.session-notes.store');
Route::get('/counselor/students/{student}/session-notes/create', [SessionNoteController::class, 'create'])
    ->name('counselor.session-notes.create');
        // AJAX endpoints
        Route::get('/students/{student}/session-notes/json', [SessionNoteController::class, 'getStudentNotes'])->name('counselor.session-notes.json');

        // Follow-up appointment routes
        Route::get('/session-notes/{sessionNote}/follow-up-slots', [SessionNoteController::class, 'getFollowUpSlots'])->name('counselor.session-notes.follow-up-slots');
        Route::post('/session-notes/{sessionNote}/follow-up-appointment', [SessionNoteController::class, 'createFollowUpAppointment'])->name('counselor.session-notes.follow-up-appointment');

        // Follow-up appointment slots
        Route::get('/appointments/followup-available-slots', [AppointmentController::class, 'getFollowupAvailableSlots'])->name('counselor.appointments.followup-available-slots');

        // Individual session note routes LAST (parameterized routes)
        Route::get('/session-notes/{sessionNote}', [SessionNoteController::class, 'show'])->name('counselor.session-notes.show');
        Route::get('/session-notes/{sessionNote}/edit', [SessionNoteController::class, 'edit'])->name('counselor.session-notes.edit');
        Route::patch('/session-notes/{sessionNote}', [SessionNoteController::class, 'update'])->name('counselor.session-notes.update');

          // Add this student profile route
    Route::get('/students/{student}/profile', [CounselorController::class, 'showStudentProfile'])
        ->name('counselor.students.profile');

         Route::get('/session-notes/dashboard', [SessionNoteController::class, 'dashboard'])->name('counselor.session-notes.dashboard');
    Route::get('/students/{student}/session-notes', [SessionNoteController::class, 'index'])->name('counselor.session-notes.index');
    Route::get('/students/{student}/session-notes/create', [SessionNoteController::class, 'create'])->name('counselor.session-notes.create');
    Route::post('/students/{student}/session-notes', [SessionNoteController::class, 'store'])->name('counselor.session-notes.store');
    Route::get('/session-notes/{sessionNote}', [SessionNoteController::class, 'show'])->name('counselor.session-notes.show');
    Route::get('/session-notes/{sessionNote}/edit', [SessionNoteController::class, 'edit'])->name('counselor.session-notes.edit');
    Route::put('/session-notes/{sessionNote}', [SessionNoteController::class, 'update'])->name('counselor.session-notes.update');
    Route::get('/students/{student}/session-notes/json', [SessionNoteController::class, 'getStudentNotes'])->name('counselor.session-notes.json');
// Appointments with search
Route::get('/counselor/appointments', [CounselorController::class, 'appointments'])
    ->name('counselor.appointments');

// Export appointments
Route::get('/counselor/appointments/export', [CounselorController::class, 'exportAppointments'])
    ->name('counselor.appointments.export');
    });
});

// In routes/web.php
Route::middleware(['auth'])->prefix('counselor')->name('counselor.')->group(function () {
    Route::resource('announcements', \App\Http\Controllers\CounselorAnnouncementController::class);
    Route::patch('announcements/{announcement}/toggle-status', [\App\Http\Controllers\CounselorAnnouncementController::class, 'toggleStatus'])
         ->name('announcements.toggle-status');
    Route::patch('announcements/{announcement}/complete', [\App\Http\Controllers\CounselorAnnouncementController::class, 'complete'])
         ->name('announcements.complete');
});

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Student-specific profile routes
    Route::patch('/profile/student', [ProfileController::class, 'updateStudent'])->name('profile.student.update');

    // Counselor-specific profile routes
    Route::patch('/profile/counselor', [ProfileController::class, 'updateCounselor'])->name('profile.counselor.update');
    Route::post('counselor/events/{event}/registrations/{registration}/status', [EventController::class, 'updateRegistrationStatus'])->name('counselor.events.update-registration-status');
});




// Student event routes
Route::middleware(['auth'])->group(function () {
    Route::get('/events/available', [EventRegistrationController::class, 'availableEvents'])->name('student.events.available');
    Route::get('/events/my-registrations', [EventRegistrationController::class, 'myRegistrations'])->name('student.events.my-registrations');
    Route::get('/events/{event}/details', [EventRegistrationController::class, 'eventDetails'])->name('student.events.details');
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'register'])->name('student.events.register');
    Route::post('/events/{event}/cancel', [EventRegistrationController::class, 'cancelRegistration'])->name('student.events.cancel');
});

// Admin routes - using same logic as counselor (no middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::get('/counselors', [AdminController::class, 'counselors'])->name('counselors');
});
// Add this to your web.php temporarily
Route::get('/check-admin-status', function() {
    $user = Auth::user();
    $admin = \App\Models\Admin::where('user_id', $user->id)->first();

    return response()->json([
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_role' => $user->role,
        'has_admin_profile' => $admin ? 'Yes' : 'No',
        'admin_profile' => $admin
    ]);
});
require __DIR__.'/auth.php';
