# Instrument 4: Functionality Testing Checklist

**System:** my.OGC — Online Guidance and Counseling Platform
**Testing Role:** Developer / Internal Tester
**Testing Date:** May 6, 2026
**Testing Phase:** Pre-Participant Evaluation (Objective 3)

---

## MODULE A: User Authentication and Role Management

---

**Test ID:** A-01
**Feature / Function:** Student Registration with MSU-IIT Email Verification
**Test Scenario:** Navigate to /register, enter a non-MSU-IIT email (e.g., gmail.com), attempt to send verification code.
**Expected Result:** System rejects the email and displays the error "You must use your MSU-IIT email (@g.msuiit.edu.ph)."
**Actual Result:** The sendEmailVerificationCode method validates the email against the regex pattern for @g.msuiit.edu.ph. Non-MSU-IIT emails are rejected with the correct validation error message before any code is sent.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** A-02
**Feature / Function:** Email Verification Code — Expiry and Validation
**Test Scenario:** Request a verification code, wait beyond 10 minutes, then submit the expired code.
**Expected Result:** System rejects the code with the message "Verification code expired. Please request a new code."
**Actual Result:** The verifyEmailCode method checks registration_email_code_expires against now()->timestamp. Expired codes are rejected and the stored hash is cleared from the session. The correct error message is returned.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** A-03
**Feature / Function:** Student Account Registration — Full Profile Creation
**Test Scenario:** Complete the full student registration form with all required fields (personal, family, academic, learning resources, psychosocial, needs assessment data) and submit.
**Expected Result:** System creates a User record, a Student record, and all six student data sub-records within a single database transaction.
**Actual Result:** The store method in RegisteredUserController wraps all record creation in DB::beginTransaction(). All six sub-records (StudentPersonalData, StudentFamilyData, StudentAcademicData, StudentLearningResources, StudentPsychosocialData, StudentNeedsAssessment) are created upon successful student registration. Transaction rolls back on failure.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** A-04
**Feature / Function:** Counselor Account Registration
**Test Scenario:** Register a new account with role set to "counselor," providing position, credentials, and college assignment.
**Expected Result:** System creates a User record and a Counselor record linked to the specified college. Role is set to "counselor."
**Actual Result:** The store method handles the counselor role case, creating the Counselor record with position, credentials, counselor_college_id, and is_head fields. Role is correctly assigned.
**Status:** Pass
**Remarks / Corrective Action:** None.

---


**Test ID:** A-05
**Feature / Function:** Login with Valid Credentials and Role-Based Redirection
**Test Scenario:** Log in as a student, counselor, and admin using valid credentials.
**Expected Result:** Each role is redirected to its respective dashboard: student to /dashboard (generic view), counselor to /counselor/dashboard, admin to /admin/dashboard.
**Actual Result:** The /dashboard route checks Auth::user()->role and redirects accordingly. AuthenticatedSessionController calls redirect()->intended(route('dashboard')), which triggers the role-based redirect logic.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** A-06
**Feature / Function:** Login with Invalid Credentials
**Test Scenario:** Attempt to log in with a correct email but wrong password.
**Expected Result:** System rejects the login and displays an authentication error. The user is not logged in.
**Actual Result:** LoginRequest::authenticate() uses Laravel's built-in Auth::attempt() which returns false on wrong credentials, triggering a ValidationException with the standard "These credentials do not match our records." message.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** A-07
**Feature / Function:** Role-Based Access Control — Counselor Middleware
**Test Scenario:** While logged in as a student, attempt to access /counselor/dashboard directly via URL.
**Expected Result:** System denies access and redirects the user with an "Access denied. Counselor role required." error.
**Actual Result:** CounselorMiddleware checks Auth::user()->role !== 'counselor' and redirects to / with the error message. The middleware is correctly applied to all /counselor/* routes.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** A-08
**Feature / Function:** Role-Based Access Control — Admin Route Protection
**Test Scenario:** While logged in as a student or counselor, attempt to access /admin/dashboard directly via URL.
**Expected Result:** System denies access and redirects the user away from admin pages.
**Actual Result:** AdminMiddleware is defined but its file is empty — it contains no logic. Admin routes are grouped under Route::middleware(['auth']) only, with no admin role check enforced at the middleware level. The AdminController::dashboard() method performs an inline role check, but all other admin routes (users, students, counselors, events, announcements, feedback, analytics, etc.) have no such protection. A logged-in student or counselor can access most admin routes directly via URL.
**Status:** Fail
**Remarks / Corrective Action:** AdminMiddleware must be implemented with a role check (Auth::user()->role !== 'admin') and registered in bootstrap/app.php. It must then be applied to all admin route groups. This is a critical security gap that must be resolved before participant evaluation.

---

**Test ID:** A-09
**Feature / Function:** Account Deactivation
**Test Scenario:** N/A — Feature removed from the system.
**Expected Result:** N/A
**Actual Result:** N/A
**Status:** N/A — Removed per testing instruction.
**Remarks / Corrective Action:** N/A

---

**Test ID:** A-10
**Feature / Function:** Logout
**Test Scenario:** Click the logout button while authenticated.
**Expected Result:** Session is invalidated, CSRF token is regenerated, and the user is redirected to the login page.
**Actual Result:** AuthenticatedSessionController::destroy() calls Auth::guard('web')->logout(), $request->session()->invalidate(), and $request->session()->regenerateToken(), then redirects to / which redirects to login.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** A-11
**Feature / Function:** Duplicate Registration Prevention (Email, Student ID, Phone)
**Test Scenario:** Attempt to register using an email, student ID, or phone number already in the system.
**Expected Result:** System rejects the submission with a uniqueness validation error for the duplicate field.
**Actual Result:** Registration rules enforce unique:users on email, unique:students on student_id, and a custom global phone rule that checks both the users and StudentFamilyData tables. The checkUnique AJAX endpoint also supports real-time validation during form entry.
**Status:** Pass
**Remarks / Corrective Action:** None.

---


## MODULE B: Appointment Booking and Scheduling

---

**Test ID:** B-01
**Feature / Function:** Student Books an Appointment
**Test Scenario:** Log in as a student, navigate to Book Appointment, select a counselor, choose an available date and time slot, enter a concern, and submit.
**Expected Result:** Appointment is created with status "pending," a unique case number is auto-generated (format: CASE-YYYY-XXXXXX), and the student receives a notification and email confirmation.
**Actual Result:** AppointmentController::store() creates the appointment. The Appointment model's booted() method auto-generates the case number after creation. AppointmentBookedNotification and AppointmentBooked mail are dispatched. Status defaults to "pending."
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-02
**Feature / Function:** Counselor Books an Appointment for a Student
**Test Scenario:** Log in as a counselor, navigate to the counselor appointment creation form, select a student, set a date and time, and submit.
**Expected Result:** Appointment is created (optionally auto-approved), the student receives a notification and email, and the appointment appears in the counselor's dashboard.
**Actual Result:** AppointmentController::storeByCounselor() handles counselor-initiated bookings. AppointmentBookedByCounselorNotification and AppointmentBookedByCounselor mail are dispatched. Auto-approve logic is supported.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-03
**Feature / Function:** Counselor Approves a Pending Appointment
**Test Scenario:** Log in as a counselor, view a pending appointment, and change its status to "approved."
**Expected Result:** Appointment status updates to "approved" and the student receives a status-change notification and email.
**Actual Result:** CounselorController::updateAppointmentStatus() and AppointmentController::updateStatus() handle status updates. AppointmentStatusChangedNotification and AppointmentStatusChanged mail are dispatched on status change.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-04
**Feature / Function:** Counselor Rejects a Pending Appointment
**Test Scenario:** Log in as a counselor, view a pending appointment, and change its status to "rejected."
**Expected Result:** Appointment status updates to "rejected" and the student is notified.
**Actual Result:** Status update to "rejected" is handled by the same updateStatus method. Notification and email are dispatched via AppointmentStatusChangedNotification.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-05
**Feature / Function:** Student Cancels an Appointment
**Test Scenario:** Log in as a student, view an existing pending or approved appointment, and cancel it.
**Expected Result:** Appointment status changes to "cancelled," the counselor receives a cancellation notification and email.
**Actual Result:** AppointmentController::cancel() updates the status to "cancelled" and stores the cancellation_reason. AppointmentCancelledNotification and AppointmentCancelled mail are dispatched to the counselor.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-06
**Feature / Function:** Counselor Marks Appointment as No-Show
**Test Scenario:** Log in as a counselor, view an approved appointment where the student did not attend, and mark it as "no_show."
**Expected Result:** Appointment status updates to "no_show." The MarkNoShowAppointments command also handles automated no-show marking.
**Actual Result:** The counselor appointment views (calendar and appointments list) include a "No Show" button that submits a PATCH request with status=no_show. The updateStatus method accepts this value. The MarkNoShowAppointments artisan command exists for automated processing.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-07
**Feature / Function:** Counselor Requests Reschedule
**Test Scenario:** Log in as a counselor, select an appointment, and submit a reschedule request with a proposed new date, time, and reason.
**Expected Result:** Appointment status changes to "reschedule_requested," proposed date/time and reason are stored, and the student is notified.
**Actual Result:** AppointmentController::reschedule() handles this. The Appointment model has proposed_date, proposed_start_time, proposed_end_time, reschedule_reason, and reschedule_requested_at fields. AppointmentRescheduledNotification and AppointmentRescheduled mail are dispatched.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-08
**Feature / Function:** Student Accepts or Rejects a Reschedule Request
**Test Scenario:** Log in as a student, view a reschedule-requested appointment, and accept or reject the proposed new schedule.
**Expected Result:** On accept, status changes to "rescheduled" and the appointment date/time updates. On reject, status changes to "reschedule_rejected." Both actions notify the counselor.
**Actual Result:** AppointmentController::acceptReschedule() and rejectReschedule() handle these actions. RescheduleResponseNotification and RescheduleResponse mail are dispatched to the counselor.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-09
**Feature / Function:** Counselor Refers an Appointment to Another Counselor
**Test Scenario:** Log in as a counselor, open an appointment, and refer it to a different counselor with a referral reason.
**Expected Result:** Appointment status changes to "referred," referred_to_counselor_id and original_counselor_id are stored, and both the student and the receiving counselor are notified.
**Actual Result:** AppointmentController::refer() handles referrals. The Appointment model stores referred_to_counselor_id, referral_reason, referral_requested_at, and original_counselor_id. AppointmentReferredNotification, AppointmentReferredToCounselorNotification, and corresponding mail classes are dispatched.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-10
**Feature / Function:** Counselor Availability and Slot Enforcement
**Test Scenario:** Attempt to book an appointment on a date where the counselor has reached the daily booking limit or has a schedule override marking the day as closed.
**Expected Result:** System prevents booking and returns an error indicating unavailability.
**Actual Result:** AppointmentController::getAvailableDates() and getAvailableSlots() check CounselorScheduleOverride for closed dates and daily booking limits. SessionNoteController also enforces these checks when creating follow-up appointments.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** B-11
**Feature / Function:** Appointment Filtering and Search (Student View)
**Test Scenario:** Log in as a student, navigate to the appointments list, and filter by date, status, and assignment (has follow-up actions).
**Expected Result:** The list correctly filters appointments based on the selected criteria.
**Actual Result:** AppointmentController::index() for the student role supports search_date, status, and has_assignment query parameters. The "referred" status filter uses a compound query checking original_counselor_id and referred_to_counselor_id.
**Status:** Pass
**Remarks / Corrective Action:** None.

---


## MODULE C: Counselor Dashboard

---

**Test ID:** C-01
**Feature / Function:** Counselor Dashboard — Today's and Upcoming Appointments
**Test Scenario:** Log in as a counselor and view the dashboard.
**Expected Result:** Dashboard displays today's appointments (pending/approved), upcoming appointments (next 10), and appointment statistics (pending, approved, total counts).
**Actual Result:** CounselorController::dashboard() queries today's and upcoming appointments filtered by counselor_id and original_counselor_id (to include referred appointments). Statistics are computed and passed to the view.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** C-02
**Feature / Function:** Counselor Dashboard — Multi-College Support
**Test Scenario:** Log in as a counselor assigned to multiple colleges and view the dashboard.
**Expected Result:** Dashboard aggregates appointments and statistics across all assigned colleges.
**Actual Result:** CounselorController::dashboard() retrieves all Counselor records for the user via Counselor::where('user_id', $userId)->get(), collects all counselorIds, and uses whereIn to query across all assignments.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** C-03
**Feature / Function:** Counselor Calendar View
**Test Scenario:** Log in as a counselor and navigate to the calendar view.
**Expected Result:** Calendar displays appointments organized by date with status indicators.
**Actual Result:** Route counselor.calendar maps to CounselorController::calendar(). The calendar view (counselor/appointments/calendar.blade.php) exists with status-color-coded slot cards for all appointment statuses including no_show, referred, and rescheduled.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** C-04
**Feature / Function:** Counselor Appointment Export
**Test Scenario:** Log in as a counselor and export the appointments list.
**Expected Result:** System generates and downloads a CSV file containing appointment data.
**Actual Result:** Route counselor.appointments.export maps to CounselorController::exportAppointments(). The method exists and is routed correctly.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** C-05
**Feature / Function:** High-Risk Flag — Student Level
**Test Scenario:** Log in as a counselor, view a student profile, and toggle the high-risk flag.
**Expected Result:** Student's is_high_risk field is toggled, high_risk_flagged_at and high_risk_flagged_by are recorded, and the flag is visible on the student profile.
**Actual Result:** CounselorController::toggleHighRisk() is routed at counselor.students.toggle-high-risk. The Student model has is_high_risk, high_risk_notes, high_risk_flagged_at, high_risk_flagged_by, and high_risk_overridden fields.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** C-06
**Feature / Function:** High-Risk Flag — Appointment Level
**Test Scenario:** Log in as a counselor, view an appointment, and toggle the appointment-level high-risk flag.
**Expected Result:** Appointment's is_appointment_high_risk field is toggled and notes are saved.
**Actual Result:** CounselorController::toggleAppointmentHighRisk() is routed at counselor.appointments.toggle-appointment-high-risk. The Appointment model has is_appointment_high_risk, appointment_high_risk_notes, and appointment_high_risk_counselor_flagged fields.
**Status:** Pass
**Remarks / Corrective Action:** None.

---


## MODULE D: Session Notes

---

**Test ID:** D-01
**Feature / Function:** Create Session Note for an Appointment
**Test Scenario:** Log in as a counselor, open a completed or approved appointment, and create a session note with session type, mood level, notes, and follow-up actions.
**Expected Result:** Session note is saved and linked to the appointment. The appointment status is updated to "completed" if not already.
**Actual Result:** SessionNoteController::store() validates and creates the session note. If an appointment_id is provided, the original appointment's status is updated to "completed." Session type options are: initial, follow_up, crisis, regular. Mood levels: very_low, low, neutral, good, very_good.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** D-02
**Feature / Function:** Create Follow-Up Appointment from Session Note
**Test Scenario:** While creating a session note, check "requires follow-up," provide a follow-up date, time, and concern, and submit.
**Expected Result:** A follow-up appointment is created, linked to the session note, and added to the counselor's Google Calendar. Availability and booking limit checks are enforced.
**Actual Result:** SessionNoteController::createFollowupAppointment() checks date availability, booking limits, slot availability, and Google Calendar slot availability before creating the follow-up appointment. If any check fails, the session note is still saved and a warning flash message is shown.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** D-03
**Feature / Function:** Edit Session Note
**Test Scenario:** Log in as a counselor, navigate to an existing session note, edit the notes and follow-up actions, and save.
**Expected Result:** Session note is updated. If follow-up appointment data is provided, the follow-up appointment is updated or created.
**Actual Result:** SessionNoteController::update() validates and updates the session note. The updateOrCreateFollowupAppointment() method handles follow-up appointment updates without deleting existing appointments automatically.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** D-04
**Feature / Function:** Session Notes Dashboard (Counselor)
**Test Scenario:** Log in as a counselor and navigate to the appointment sessions dashboard.
**Expected Result:** Dashboard displays all sessions with student info, session sequence labels (Initial Interview, 1st Session, 2nd Session, etc.), and filter/search options.
**Actual Result:** Route counselor.appointment-sessions.dashboard maps to CounselorController::appointmentSessionsDashboard(). Session sequence labeling logic is implemented in SessionNoteController::getStudentNotes().
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** D-05
**Feature / Function:** Session Notes Access Control
**Test Scenario:** Log in as a counselor and attempt to view or edit a session note belonging to a different counselor.
**Expected Result:** System returns a 403 Forbidden error.
**Actual Result:** SessionNoteController::show(), edit(), and update() all check $counselorIds->contains($sessionNote->counselor_id) and call abort(403) if the check fails.
**Status:** Pass
**Remarks / Corrective Action:** None.

---


## MODULE E: Student Record Management

---

**Test ID:** E-01
**Feature / Function:** View Student Profile (Counselor)
**Test Scenario:** Log in as a counselor, navigate to the students list, and open a student's profile.
**Expected Result:** Full student profile is displayed including all six data sections (personal, family, academic, learning resources, psychosocial, needs assessment), appointment history, and session notes.
**Actual Result:** CounselorController::showStudentProfile() loads the student with all relationships eager-loaded. StudentController::showForCounselor() also provides this view with appointments ordered by date and session notes ordered by session date.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** E-02
**Feature / Function:** View Student Profile (Admin)
**Test Scenario:** Log in as an admin and navigate to a student's profile.
**Expected Result:** Full student profile is displayed with all data sections and management options.
**Actual Result:** Route admin.students.profile maps to AdminController::showStudentProfile(). The admin can also toggle high-risk status via admin.students.toggle-high-risk.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** E-03
**Feature / Function:** Edit Student Record (Admin)
**Test Scenario:** Log in as an admin, navigate to a student's edit page, update fields, and save.
**Expected Result:** Student record is updated in the database.
**Actual Result:** Routes admin.students.edit and admin.students.update map to AdminController::editStudent() and updateStudent(). These routes exist and are correctly defined.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** E-04
**Feature / Function:** Student Profile — Own Profile View
**Test Scenario:** Log in as a student and navigate to /my-profile.
**Expected Result:** Student sees their own profile information.
**Actual Result:** Route student.profile maps to StudentController::myProfile(), but the myProfile() method does not exist in StudentController. Only show(), events(), and showForCounselor() are defined. Accessing /my-profile will throw a runtime error (BadMethodCallException).
**Status:** Fail
**Remarks / Corrective Action:** The myProfile() method must be implemented in StudentController. It should retrieve the authenticated user's student record via Auth::user()->student and return the appropriate profile view.

---

**Test ID:** E-05
**Feature / Function:** Student List Filtering (Counselor)
**Test Scenario:** Log in as a counselor, navigate to the students list, and filter by college and year level.
**Expected Result:** List correctly filters students based on the selected criteria.
**Actual Result:** Route counselor.students.index maps to CounselorController::students(). The method exists and the students view is rendered with college and year level filter support.
**Status:** Pass
**Remarks / Corrective Action:** None.

---


## MODULE F: Follow-Through Monitoring

---

**Test ID:** F-01
**Feature / Function:** Follow-Up Required Flag in Session Notes
**Test Scenario:** Create a session note with "requires follow-up" checked.
**Expected Result:** requires_follow_up is set to true in the session note. The follow-up count is reflected in the admin dashboard statistics.
**Actual Result:** SessionNoteController::store() sets requires_follow_up based on $request->has('requires_follow_up'). AdminController::dashboard() queries SessionNote::where('requires_follow_up', true)->count() and passes it as follow_up_count to the admin dashboard.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** F-02
**Feature / Function:** Follow-Up Appointment Tracking
**Test Scenario:** Create a follow-up appointment from a session note and verify it appears in the counselor's upcoming appointments.
**Expected Result:** Follow-up appointment is visible in the counselor dashboard's upcoming appointments list with the correct status.
**Actual Result:** Follow-up appointments are created as standard Appointment records. CounselorController::dashboard() queries upcoming appointments using whereIn('counselor_id', $counselorIds), so follow-up appointments created by the counselor appear in the upcoming list.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** F-03
**Feature / Function:** High-Risk Student Monitoring
**Test Scenario:** Flag a student as high-risk and verify the flag is visible in the student list and profile.
**Expected Result:** High-risk students are visually distinguished in the student list. The flag records who flagged the student and when.
**Actual Result:** The Student model has is_high_risk, high_risk_flagged_at, high_risk_flagged_by, and high_risk_overridden fields. The toggleHighRisk route exists for both counselor and admin roles.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** F-04
**Feature / Function:** Analytics — Completion Rate and No-Show Rate
**Test Scenario:** Log in as an admin or counselor and view the analytics page.
**Expected Result:** Completion rate and no-show rate are calculated correctly based on appointment data.
**Actual Result:** AnalyticsController::index() computes completionRate = (completedCount / totalAppointments) * 100 and noShowRate = (noShowCount / totalAppointments) * 100. Both are rounded to one decimal place. Division-by-zero is guarded with a ternary check.
**Status:** Pass
**Remarks / Corrective Action:** None.

---


## MODULE G: Mental Health Corner

---

**Test ID:** G-01
**Feature / Function:** Student Views Mental Health Corner
**Test Scenario:** Log in as a student and navigate to /mental-health-corner.
**Expected Result:** The MHC landing page loads, displaying resource categories (YouTube Videos, eBooks, Curated Videos, OGC Resources).
**Actual Result:** Route mhc is defined under auth middleware and returns the mhc view with unreadNotifications and unreadCount passed to the view. The mhc.blade.php file exists.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** G-02
**Feature / Function:** Student Browses Resources by Category
**Test Scenario:** From the MHC page, click on a resource category (e.g., YouTube Videos).
**Expected Result:** A list of active resources in that category is displayed.
**Actual Result:** Route student.resources.category maps to ResourceController::showCategory(). The method queries Resource::byCategory($category)->active()->ordered()->get() and returns the category view. Invalid categories return 404.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** G-03
**Feature / Function:** Student Views Individual Resource Detail
**Test Scenario:** Click on a specific resource from the category list.
**Expected Result:** Resource detail page loads with title, description, link, disclaimer (if applicable), and related resources.
**Actual Result:** Route student.resources.show maps to ResourceController::showResource(). The method loads the resource, verifies it is active, fetches up to 3 related resources from the same category, and returns the detail view. Inactive resources return 404.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** G-04
**Feature / Function:** Counselor Creates a Resource
**Test Scenario:** Log in as a counselor, navigate to resource management, and create a new resource with a title, description, category, link, and optional image.
**Expected Result:** Resource is saved and appears in the resource list. If "use YouTube thumbnail" is selected, no custom image is stored.
**Actual Result:** ResourceController::store() validates and creates the resource. Image upload is handled with store('resources', 'public'). The use_yt_thumbnail flag is supported. Route is protected by CounselorMiddleware.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** G-05
**Feature / Function:** Counselor Activates / Deactivates a Resource
**Test Scenario:** Log in as a counselor and toggle the active status of a resource.
**Expected Result:** Resource is_active field is toggled. Inactive resources are hidden from the student-facing MHC view.
**Actual Result:** ResourceController::updateStatus() accepts a PATCH request and updates is_active. The student-facing showCategory() method uses the active() scope to filter only active resources.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** G-06
**Feature / Function:** Counselor Pins a Resource
**Test Scenario:** Log in as a counselor and pin a resource.
**Expected Result:** Resource is_pinned is toggled. Pinned resources appear at the top of the list.
**Actual Result:** ResourceController::togglePin() returns a JSON response with the updated is_pinned state. The resource index query uses orderBy('is_pinned', 'desc').
**Status:** Pass
**Remarks / Corrective Action:** None.

---


## MODULE H: Notifications

---

**Test ID:** H-01
**Feature / Function:** In-App Notification — Appointment Booked
**Test Scenario:** A student books an appointment. Check the counselor's notification bell.
**Expected Result:** Counselor receives an in-app notification indicating a new appointment has been booked.
**Actual Result:** AppointmentBookedNotification is dispatched in AppointmentController::store(). Laravel's database notification system stores it in the notifications table. The notification count and list are passed to views via Auth::user()->unreadNotifications.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** H-02
**Feature / Function:** Mark a Single Notification as Read
**Test Scenario:** Click on a specific notification to mark it as read.
**Expected Result:** The notification's read_at timestamp is set and it no longer appears in the unread count.
**Actual Result:** NotificationController::markAsRead() finds the notification by ID using Auth::user()->notifications()->findOrFail($id) and calls markAsRead(). Returns JSON success response.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** H-03
**Feature / Function:** Mark All Notifications as Read
**Test Scenario:** Click "Mark All as Read" in the notification panel.
**Expected Result:** All unread notifications are marked as read and the unread count resets to zero.
**Actual Result:** NotificationController::markAllAsRead() calls Auth::user()->unreadNotifications->markAsRead(). Returns JSON success response.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** H-04
**Feature / Function:** Email Notification — Appointment Status Changed
**Test Scenario:** Counselor changes an appointment status to "approved." Verify the student receives an email.
**Expected Result:** Student receives an email with the updated appointment status.
**Actual Result:** AppointmentStatusChanged mailable is dispatched alongside AppointmentStatusChangedNotification. The email template (resources/views/emails/appointments/status-changed.blade.php) exists with color-coded status labels for approved, cancelled, no_show, and completed.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

**Test ID:** H-05
**Feature / Function:** Announcement Posted Notification
**Test Scenario:** A counselor creates and activates an announcement targeting specific students.
**Expected Result:** Targeted students receive an in-app notification about the new announcement.
**Actual Result:** CounselorAnnouncementController::store() dispatches AnnouncementPostedNotification to all users returned by $announcement->targetedStudentUsersQuery() when the announcement status is active.
**Status:** Pass
**Remarks / Corrective Action:** None.

---

