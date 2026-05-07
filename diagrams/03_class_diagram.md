# Figure 4.3 — Class Diagram of my.OGC

## Purpose
Shows the object-oriented structure of the platform — the major Eloquent model classes,
their key attributes, methods, and relationships.

## Chapter 4 Explanation
The class diagram presents the major Laravel Eloquent model classes used in my.OGC.
The `User` class is the central class, extended by role-specific profile classes: `Student`,
`Counselor`, and `Admin`. The `Appointment` class connects `Student` and `Counselor` and
carries the full booking lifecycle including referral and reschedule tracking. `SessionNote`
is linked to both `Appointment` and `Student`. Supporting classes include `Event`,
`EventRegistration`, `Announcement`, `Resource`, `Feedback`, `College`, and
`CounselorScheduleOverride`.

## Assumptions
- Only key attributes and methods are shown for readability. Full attribute lists are in the ERD.
- GoogleCalendarService is shown as a service class, not an Eloquent model.

## Items Needing Confirmation
- None. All classes confirmed from model files.

---

```mermaid
classDiagram
    class User {
        +bigint id
        +string first_name
        +string last_name
        +string email
        +string role
        +string phone_number
        +student() HasOne
        +counselor() HasOne
        +admin() HasOne
        +isAdmin() bool
        +isCounselor() bool
        +isStudent() bool
        +getFullNameAttribute() string
    }

    class Student {
        +bigint id
        +bigint user_id
        +bigint college_id
        +string student_id
        +string year_level
        +string course
        +boolean is_high_risk
        +boolean initial_interview_completed
        +user() BelongsTo
        +college() BelongsTo
        +appointments() HasMany
        +sessionNotes() HasMany
        +eventRegistrations() HasMany
        +personalData() HasOne
        +familyData() HasOne
        +academicData() HasOne
        +psychosocialData() HasOne
        +learningResources() HasOne
        +needsAssessment() HasOne
    }

    class Counselor {
        +bigint id
        +bigint user_id
        +bigint college_id
        +string position
        +string google_calendar_id
        +json availability
        +int daily_booking_limit
        +boolean is_head
        +user() BelongsTo
        +college() BelongsTo
        +appointments() HasMany
        +scheduleOverrides() HasMany
        +events() BelongsToMany
        +getDailyBookingLimit() int
        +getAvailability() array
    }

    class Admin {
        +bigint id
        +bigint user_id
        +string credentials
        +user() BelongsTo
    }

    class College {
        +bigint id
        +string name
        +string code
        +students() HasMany
        +counselors() HasMany
    }

    class Appointment {
        +bigint id
        +string case_number
        +bigint student_id
        +bigint counselor_id
        +date appointment_date
        +string booking_type
        +string booking_category
        +string status
        +bigint referred_to_counselor_id
        +bigint original_counselor_id
        +string google_calendar_event_id
        +boolean is_appointment_high_risk
        +student() BelongsTo
        +counselor() BelongsTo
        +sessionNotes() HasMany
        +referredCounselor() BelongsTo
        +originalCounselor() BelongsTo
        +canBeManagedBy(counselorId) bool
        +getStatuses()$ array
        +getDisplayStatusAttribute() string
    }

    class SessionNote {
        +bigint id
        +bigint appointment_id
        +bigint counselor_id
        +bigint student_id
        +string session_type
        +string mood_level
        +text notes
        +json root_causes
        +date session_date
        +date next_session_date
        +boolean requires_follow_up
        +appointment() BelongsTo
        +counselor() BelongsTo
        +student() BelongsTo
        +getSessionTypes()$ array
        +getMoodLevels()$ array
    }

    class CounselorScheduleOverride {
        +bigint id
        +bigint counselor_id
        +date date
        +boolean is_closed
        +json time_slots
        +counselor() BelongsTo
    }

    class Event {
        +bigint id
        +bigint user_id
        +string title
        +date event_start_date
        +date event_end_date
        +boolean is_required
        +boolean for_all_colleges
        +json year_levels
        +string google_calendar_event_id
        +registrations() HasMany
        +colleges() BelongsToMany
        +assignedCounselors() BelongsToMany
        +hasAvailableSlots() bool
        +isCancellationAllowed() bool
        +isAvailableForStudent(student) bool
    }

    class EventRegistration {
        +bigint id
        +bigint event_id
        +bigint student_id
        +string status
        +timestamp registered_at
        +event() BelongsTo
        +student() BelongsTo
        +markAsAttended() bool
        +cancel() bool
    }

    class Announcement {
        +bigint id
        +bigint user_id
        +string title
        +text content
        +boolean for_all_colleges
        +json year_levels
        +boolean is_active
        +user() BelongsTo
        +colleges() BelongsToMany
        +getStatusAttribute() string
    }

    class Resource {
        +bigint id
        +bigint user_id
        +string title
        +string category
        +string link
        +boolean is_active
        +user() BelongsTo
        +getCategories()$ array
    }

    class Feedback {
        +bigint id
        +bigint user_id
        +bigint target_counselor_id
        +string service_availed
        +int satisfaction_rating
        +text comments
        +boolean is_anonymous
        +user() BelongsTo
        +targetCounselor() BelongsTo
    }

    class GoogleCalendarService {
        +getAvailableSlots(counselor, date) array
        +createEvent(appointment) string
        +deleteEvent(eventId) void
        +checkConflicts(counselor, date, time) bool
        +getBusyIntervals(counselor, date) array
    }

    User "1" --> "0..1" Student : has one
    User "1" --> "0..1" Counselor : has one
    User "1" --> "0..1" Admin : has one
    Student "many" --> "1" College : belongs to
    Counselor "many" --> "1" College : belongs to
    Student "1" --> "many" Appointment : has many
    Counselor "1" --> "many" Appointment : has many
    Appointment "1" --> "many" SessionNote : has many
    Counselor "1" --> "many" CounselorScheduleOverride : has many
    Student "1" --> "many" EventRegistration : has many
    Event "1" --> "many" EventRegistration : has many
    Counselor ..> GoogleCalendarService : uses
    Appointment ..> GoogleCalendarService : uses
```
