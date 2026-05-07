# Sequence Diagrams — my.OGC

---

## Sequence Diagram 1 — Student Books an Appointment

### Purpose
Shows the message flow between the Student, the system (Laravel), GoogleCalendarService,
and the Counselor when a student books an appointment.

### Chapter 4 Explanation
When a student submits an appointment request, the system validates the input, checks
counselor availability through the GoogleCalendarService, saves the appointment record,
and dispatches notifications to both the student and the counselor.

### Assumptions
- Google Calendar check happens before the appointment is saved.
- Notification is sent via both in-app (database) and email (Mailable).

### Items Needing Confirmation
- None.

---

```mermaid
sequenceDiagram
    actor Student
    participant Browser
    participant AppointmentController
    participant GoogleCalendarService
    participant Database
    participant NotificationSystem

    Student->>Browser: Navigate to Book Appointment
    Browser->>AppointmentController: GET /appointments/create
    AppointmentController->>Database: Fetch available counselors
    Database-->>AppointmentController: Counselor list
    AppointmentController-->>Browser: Render booking form

    Student->>Browser: Select counselor, date, time, type, category
    Browser->>AppointmentController: POST /appointments (form data)
    AppointmentController->>AppointmentController: Validate input

    AppointmentController->>GoogleCalendarService: checkConflicts(counselor, date, time)
    GoogleCalendarService->>GoogleCalendarService: Query Google Calendar API
    GoogleCalendarService-->>AppointmentController: No conflict / Conflict found

    alt Conflict found
        AppointmentController-->>Browser: Return error (slot unavailable)
    else No conflict
        AppointmentController->>Database: Save Appointment (status: pending)
        Database-->>AppointmentController: Appointment saved
        AppointmentController->>NotificationSystem: Dispatch AppointmentBooked notification
        NotificationSystem->>Database: Store in-app notification (counselor)
        NotificationSystem->>NotificationSystem: Send AppointmentBooked email (counselor)
        NotificationSystem->>NotificationSystem: Send AppointmentBooked email (student)
        AppointmentController-->>Browser: Redirect with success message
        Browser-->>Student: Appointment submitted (status: Pending)
    end
```

---

## Sequence Diagram 2 — Counselor Accepts an Appointment

### Purpose
Shows the message flow when a counselor accepts a pending appointment request.

### Chapter 4 Explanation
When a counselor accepts an appointment, the system updates the appointment status to
Approved, creates a Google Calendar event, and notifies the student. Counselors cannot
reject appointments — they may only accept, reschedule, or refer to another counselor.

### Assumptions
- Google Calendar event creation is part of the accept flow.
- Notification is sent via both in-app and email.

### Items Needing Confirmation
- None.

---

```mermaid
sequenceDiagram
    actor Counselor
    participant Browser
    participant AppointmentController
    participant GoogleCalendarService
    participant Database
    participant NotificationSystem

    Counselor->>Browser: View pending appointment
    Browser->>AppointmentController: GET /counselor/appointments/{id}
    AppointmentController->>Database: Fetch appointment details
    Database-->>AppointmentController: Appointment data
    AppointmentController-->>Browser: Render appointment detail

    Counselor->>Browser: Click "Accept"
    Browser->>AppointmentController: POST /counselor/appointments/{id}/approve
    AppointmentController->>Database: Update status to "approved"
    AppointmentController->>GoogleCalendarService: createEvent(appointment)
    GoogleCalendarService->>GoogleCalendarService: Create event via Google Calendar API
    GoogleCalendarService-->>AppointmentController: google_calendar_event_id
    AppointmentController->>Database: Save google_calendar_event_id
    AppointmentController->>NotificationSystem: Dispatch AppointmentStatusChanged notification
    NotificationSystem->>Database: Store in-app notification (student)
    NotificationSystem->>NotificationSystem: Send status email (student)
    AppointmentController-->>Browser: Redirect with success message
    Browser-->>Counselor: Appointment approved
```

---

## Sequence Diagram 3 — Counselor Records a Session Note

### Purpose
Shows the message flow when a counselor records a session note after a completed appointment.

### Chapter 4 Explanation
After an appointment is completed, the counselor opens the session note form, fills in
the required fields, and saves the note. The system links the note to both the appointment
and the student record.

### Assumptions
- Session notes can only be created for appointments that exist in the system.
- High-risk flag update is a separate action after saving the note.

### Items Needing Confirmation
- None.

---

```mermaid
sequenceDiagram
    actor Counselor
    participant Browser
    participant SessionNoteController
    participant Database

    Counselor->>Browser: Open completed appointment
    Browser->>SessionNoteController: GET /counselor/session-notes/create?appointment_id={id}
    SessionNoteController->>Database: Fetch appointment & student data
    Database-->>SessionNoteController: Appointment + student info
    SessionNoteController-->>Browser: Render session note form

    Counselor->>Browser: Fill in session type, mood, notes, root causes, follow-up
    Browser->>SessionNoteController: POST /counselor/session-notes
    SessionNoteController->>SessionNoteController: Validate input
    SessionNoteController->>Database: Save SessionNote (linked to appointment + student)
    Database-->>SessionNoteController: Note saved

    alt Requires follow-up
        SessionNoteController->>Database: Store next_session_date on note
    end

    SessionNoteController-->>Browser: Redirect with success message
    Browser-->>Counselor: Session note saved
```

---

## Sequence Diagram 4 — Student Receives and Responds to a Reschedule Request

### Purpose
Shows the message flow for the reschedule workflow between counselor and student.

### Chapter 4 Explanation
When a counselor proposes a reschedule, the appointment status changes to
"reschedule_requested" and the student is notified. The student can accept or reject
the proposed new schedule.

### Assumptions
- Reschedule cutoff is enforced at the application level.
- Both accept and reject paths notify the counselor.

### Items Needing Confirmation
- None.

---

```mermaid
sequenceDiagram
    actor Counselor
    actor Student
    participant AppointmentController
    participant Database
    participant NotificationSystem

    Counselor->>AppointmentController: POST reschedule (proposed date/time + reason)
    AppointmentController->>Database: Update status to "reschedule_requested"\nSave proposed_date, proposed_start_time
    AppointmentController->>NotificationSystem: Dispatch AppointmentRescheduled notification
    NotificationSystem->>Database: Store in-app notification (student)
    NotificationSystem->>NotificationSystem: Send reschedule email (student)
    NotificationSystem-->>Student: Notification received

    Student->>AppointmentController: POST /appointments/{id}/reschedule-response (accept/reject)

    alt Student accepts
        AppointmentController->>Database: Update appointment_date to proposed_date\nStatus: "rescheduled"
        AppointmentController->>NotificationSystem: Notify counselor (accepted)
    else Student rejects
        AppointmentController->>Database: Status: "reschedule_rejected"
        AppointmentController->>NotificationSystem: Notify counselor (rejected)
    end

    NotificationSystem-->>Counselor: Notification received
```
