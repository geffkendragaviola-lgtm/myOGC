# Figure 4.1 — Three-Tier System Architecture of my.OGC

## Purpose
Shows the three-layer architecture of the platform: Presentation, Application Logic, and Data Storage.

## Chapter 4 Explanation
The my.OGC platform follows a web-based three-tier architecture. The Presentation Layer
handles all user-facing views rendered via Laravel Blade and Tailwind CSS. The Application
Logic Layer, built on Laravel 12, processes all requests through middleware, controllers,
and the GoogleCalendarService. The Data Storage Layer uses PostgreSQL accessed through
Laravel's Eloquent ORM.

## Assumptions
- None. All layers and components confirmed from codebase.

## Items Needing Confirmation
- None.

---

```mermaid
graph TB
    subgraph PL["Presentation Layer (Client Browser)"]
        direction TB
        V1["Blade Templates + Tailwind CSS"]
        V2["Student Views\n(Dashboard, Appointments,\nMental Health Corner, Events)"]
        V3["Counselor Views\n(Dashboard, Appointments,\nSession Notes, Student Records)"]
        V4["Admin Views\n(User Management, Analytics,\nAnnouncements, Events)"]
        V5["Email Notification Templates\n(Blade HTML Mailables)"]
    end

    subgraph AL["Application Logic Layer (Laravel 12 — PHP)"]
        direction TB
        MW["Middleware Stack\n(auth, verified, CounselorMiddleware)"]
        RT["Routes\n(web.php)"]
        subgraph CTRL["Controllers"]
            C1["AppointmentController"]
            C2["CounselorController"]
            C3["AdminController"]
            C4["SessionNoteController"]
            C5["EventController /\nEventRegistrationController"]
            C6["FeedbackController"]
            C7["AnalyticsController"]
            C8["Auth Controllers\n(Breeze)"]
            C9["NotificationController\nResourceController\nAnnouncementController"]
        end
        SVC["GoogleCalendarService\n(OAuth 2.0, event CRUD,\navailability & conflict check)"]
        NOTIF["Notification System\n(In-App DB + Mailable classes)"]
        SCHED["Scheduled Command\nMarkNoShowAppointments\n(runs every 5 minutes)"]
    end

    subgraph DS["Data Storage Layer (PostgreSQL)"]
        direction TB
        DB1["users / students / counselors / admins"]
        DB2["appointments / session_notes"]
        DB3["student sub-profiles\n(personal, family, academic,\npsychosocial, learning, needs)"]
        DB4["events / event_registrations\nannouncements / resources / faqs"]
        DB5["feedbacks / notifications"]
        DB6["colleges / services"]
        DB7["sessions / cache / jobs / migrations"]
    end

    EXT["Google Calendar API\n(External)"]

    PL -- "HTTP Requests" --> AL
    AL -- "Server-Rendered Responses" --> PL
    AL -- "Eloquent ORM Queries" --> DS
    DS -- "Query Results" --> AL
    SVC -- "OAuth 2.0 / REST API" --> EXT
    EXT -- "Calendar Data" --> SVC
```
