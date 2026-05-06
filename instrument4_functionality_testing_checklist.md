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
**Expected Result:** System denies access and returns a 403 Forbidden error for non-admin users on all admin routes.
**Actual Result:** AdminMiddleware was previously empty and not enforced. Fix applied: AdminMiddleware now checks Auth::user()->role !== 'admin' and calls abort(403, 'Access denied. Admin role required.'). The middleware is registered as the 'admin' alias in bootstrap/app.php and applied to all admin routes via Route::middleware(['auth', 'admin']). All admin routes are now protected.
**Status:** Pass
**Remarks / Corrective Action:** Fixed. AdminMiddleware implemented and applied to all admin route groups.

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
**Actual Result:** AppointmentContro