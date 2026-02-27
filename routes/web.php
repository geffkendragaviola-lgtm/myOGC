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
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// In your web.php routes file
// For student viewing their own profile
Route::get('/student/{student}', [StudentController::class, 'show'])->name('student.show');

// OR create a dedicated route for student's own profile (recommended)
Route::get('/my-profile', [StudentController::class, 'myProfile'])->name('student.profile');

// Registration routes must be public (no auth)
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);
Route::post('register/email/send', [RegisteredUserController::class, 'sendEmailVerificationCode'])
    ->name('register.email.send');
Route::post('register/email/verify', [RegisteredUserController::class, 'verifyEmailCode'])
    ->name('register.email.verify');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mental Health Corner route
    Route::get('/mental-health-corner', function () {
        return view('mhc'); // Your MHC blade file
    })->name('mhc');

    Route::get('/book-appointment', function () {
        return view('bap'); // Your BAP blade file
    })->name('bap');

    // Feedback routes
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Admin feedback management - REMOVED role:admin middleware
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    });

    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('bap');

    Route::middleware(['auth'])->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/available-dates', [AppointmentController::class, 'getAvailableDates'])->name('appointments.available-dates');
        Route::get('/appointments/available-slots', [AppointmentController::class, 'getAvailableSlots'])->name('appointments.available-slots');

        Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::patch('/appointments/{appointment}/reschedule/accept', [AppointmentController::class, 'acceptReschedule'])
            ->name('appointments.reschedule.accept');
        Route::patch('/appointments/{appointment}/reschedule/reject', [AppointmentController::class, 'rejectReschedule'])
            ->name('appointments.reschedule.reject');
        Route::patch('/appointments/{appointment}/referral/accept', [AppointmentController::class, 'acceptReferral'])
            ->name('appointments.referral.accept');
        Route::patch('/appointments/{appointment}/referral/reject', [AppointmentController::class, 'rejectReferral'])
            ->name('appointments.referral.reject');

        // Counselor/Admin routes
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    });

    Route::middleware(['auth', CounselorMiddleware::class])->prefix('counselor')->group(function () {
        Route::get('/dashboard', [CounselorController::class, 'dashboard'])->name('counselor.dashboard');
        Route::get('/calendar', [CounselorController::class, 'calendar'])->name('counselor.calendar');
        Route::get('/appointments', [CounselorController::class, 'appointments'])->name('counselor.appointments');
        Route::patch('/appointments/{appointment}/status', [CounselorController::class, 'updateAppointmentStatus'])->name('counselor.appointments.update-status');
        Route::get('/appointments/{appointment}/details', [CounselorController::class, 'getAppointmentDetails'])->name('counselor.appointments.details');
        Route::get('/appointments/{appointment}/session', [CounselorController::class, 'showAppointmentSession'])
            ->name('counselor.appointments.session');
        Route::post('/appointments/{appointment}/session', [CounselorController::class, 'storeAppointmentSession'])
            ->name('counselor.appointments.session.store');
        Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])
            ->name('counselor.appointments.update-status');
    Route::patch('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])
        ->name('counselor.appointments.reschedule');
        // Feedback management routes
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('counselor.feedback.index');
        Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('counselor.feedback.show');
        Route::get('/feedback/export', [FeedbackController::class, 'export'])->name('counselor.feedback.export');
        Route::get('/availability', [ProfileController::class, 'editAvailability'])->name('counselor.availability.edit');
        Route::patch('/availability', [ProfileController::class, 'updateAvailability'])->name('counselor.availability.update');

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

        // Follow-up appointment slots
        Route::get('/appointments/followup-available-slots', [AppointmentController::class, 'getFollowupAvailableSlots'])->name('counselor.appointments.followup-available-slots');

        // Student profile route
        Route::get('/students/{student}/profile', [CounselorController::class, 'showStudentProfile'])
            ->name('counselor.students.profile');

        // Export appointments
        Route::get('/appointments/export', [CounselorController::class, 'exportAppointments'])
            ->name('counselor.appointments.export');

        // Referral routes
        Route::get('/appointments/{appointment}/refer-form', [CounselorController::class, 'showReferralForm'])->name('counselor.appointments.refer-form');
        Route::patch('/appointments/{appointment}/refer', [AppointmentController::class, 'refer'])->name('counselor.appointments.refer');
        Route::patch('/appointments/{appointment}/referral/accept', [AppointmentController::class, 'acceptReferralByCounselor'])
            ->name('counselor.appointments.referral.accept');
        Route::patch('/appointments/{appointment}/referral/reject', [AppointmentController::class, 'rejectReferralByCounselor'])
            ->name('counselor.appointments.referral.reject');

        // API route for getting available counselors (for referrals)
        Route::get('/appointments/available-counselors', [AppointmentController::class, 'getAvailableCounselors'])->name('counselor.appointments.available-counselors');
    });
});

// Counselor announcements
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
    Route::patch('/profile/admin', [ProfileController::class, 'updateAdmin'])->name('profile.admin.update');

    // Student-specific profile routes
    Route::patch('/profile/student', [ProfileController::class, 'updateStudent'])->name('profile.student.update');

    // Counselor-specific profile routes
    Route::patch('/profile/counselor', [ProfileController::class, 'updateCounselor'])->name('profile.counselor.update');
});

// Student event routes
Route::middleware(['auth'])->group(function () {
    Route::get('/events/available', [EventRegistrationController::class, 'availableEvents'])->name('student.events.available');
    Route::get('/events/my-registrations', [EventRegistrationController::class, 'myRegistrations'])->name('student.events.my-registrations');
    Route::get('/events/{event}/details', [EventRegistrationController::class, 'eventDetails'])->name('student.events.details');
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'register'])->name('student.events.register');
    Route::post('/events/{event}/cancel', [EventRegistrationController::class, 'cancelRegistration'])->name('student.events.cancel');
});

// Admin routes - REMOVED role:admin middleware
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

    // Admin Event Management Routes
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.destroy');
    Route::patch('/events/{event}/toggle-status', [AdminController::class, 'toggleEventStatus'])->name('events.toggle-status');

    // Event Registrations
    Route::get('/events/{event}/registrations', [AdminController::class, 'showEventRegistrations'])->name('events.registrations');
    Route::patch('/events/{event}/registrations/{registration}/status', [AdminController::class, 'updateEventRegistrationStatus'])->name('events.update-registration-status');
    Route::get('/events/{event}/export-registrations', [AdminController::class, 'exportEventRegistrations'])->name('events.export-registrations');

    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::get('/feedback/export', [FeedbackController::class, 'export'])->name('feedback.export');
    // Counselor event registration management
Route::post('/counselor/events/{event}/registrations/{registration}/reregister', [EventController::class, 'reRegisterStudent'])
    ->name('counselor.events.re-register-student');
    // Counselor event registration status updates
Route::patch('/counselor/events/{event}/registrations/{registration}/status', [EventController::class, 'updateRegistrationStatus'])
    ->name('counselor.events.update-registration-status');

Route::delete('/counselor/announcements/{announcement}/remove-image', [CounselorAnnouncementController::class, 'removeImage'])
    ->name('counselor.announcements.remove-image');
});
// Student Event Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {

    Route::post('/events/{event}/re-register', [EventRegistrationController::class, 'reRegister'])
        ->name('events.re-register');
});

// Counselor announcement routes
Route::middleware(['auth'])->prefix('counselor')->name('counselor.')->group(function () {
    Route::resource('announcements', CounselorAnnouncementController::class);

    // Image removal route
    Route::delete('announcements/{announcement}/remove-image',
        [CounselorAnnouncementController::class, 'removeImage']
    )->name('announcements.remove-image');

    // Your other announcement routes
    Route::patch('announcements/{announcement}/complete',
        [CounselorAnnouncementController::class, 'complete']
    )->name('announcements.complete');

    Route::patch('announcements/{announcement}/toggle-status',
        [CounselorAnnouncementController::class, 'toggleStatus']
    )->name('announcements.toggle-status');
});

// Debug route
Route::get('/check-admin-status', function() {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }

    $admin = \App\Models\Admin::where('user_id', $user->id)->first();

    return response()->json([
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_role' => $user->role,
        'has_admin_profile' => $admin ? 'Yes' : 'No',
        'admin_profile' => $admin
    ]);
});

Route::get('/oauth2callback', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'code' => $request->query('code'),
        'error' => $request->query('error'),
    ]);
});

// Student resource routes
Route::get('/mental-health-corner/{category}', [ResourceController::class, 'showCategory'])
    ->name('student.resources.category');

Route::get('/appointments/referred-counselors', [AppointmentController::class, 'getReferredCounselors'])->name('appointments.referred-counselors');
require __DIR__.'/auth.php';
