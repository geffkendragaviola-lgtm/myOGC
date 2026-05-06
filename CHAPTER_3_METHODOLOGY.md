# CHAPTER 3: RESEARCH METHODOLOGY

## 3.1 Research Design

This study employed a **developmental research design** to design, develop, and evaluate a web-based counseling management system for the Office of Guidance and Counseling (OGC) of Mindanao State University – Iligan Institute of Technology (MSU-IIT). The development followed the **Rapid Application Development (RAD)** model, which emphasizes rapid prototyping, continuous user feedback, and iterative refinement over lengthy planning phases. RAD was chosen because it allowed the development team to quickly produce working prototypes of each system module, gather feedback from actual end-users of the OGC, and incorporate changes efficiently throughout the development cycle.

The system, named **my.OGC**, was built to digitize and streamline the counseling services of MSU-IIT, replacing manual and paper-based processes with an integrated web platform accessible to students, counselors, and administrators.

---

## 3.2 System Development Methodology

### 3.2.1 RAD Phases

The development of my.OGC followed the four phases of the **Rapid Application Development (RAD)** model:

#### Phase 1: Requirements Planning
In this phase, the development team collaborated with the MSU-IIT Office of Guidance and Counseling to identify the system's objectives, scope, and high-level requirements. Key stakeholders — including guidance counselors, administrative staff, and student representatives — participated in structured discussions to define the core needs of the system. The following requirements were identified:

- Online appointment booking with counselor availability management
- Comprehensive student profiling and intake forms
- Counseling session documentation and tracking
- Mental health event creation and student registration management
- Resource library for mental health materials
- Student feedback and satisfaction survey system
- Role-based access control for students, counselors, and administrators
- Google Calendar integration for counselor scheduling
- Administrative analytics and reporting dashboard

#### Phase 2: User Design
During this phase, the development team worked closely with end-users through workshops and prototype demonstrations to design the system's data models, workflows, and user interface. Prototypes of key modules — including the appointment booking form, counselor dashboard, and student profile — were presented to OGC staff for review and feedback. This phase was iterative; prototypes were revised multiple times based on user input before proceeding to construction.

Key design decisions made during this phase include:
- A role-based navigation system with separate layouts for students, counselors, and administrators
- A multi-step student intake form covering six data sections (personal, family, academic, learning resources, psychosocial, and needs assessment)
- A calendar-based appointment booking interface with real-time slot availability
- An informed consent modal integrated into the appointment booking flow
- A custom toast notification and confirmation dialog system consistent with MSU-IIT's institutional color palette

#### Phase 3: Construction
The system was constructed using the technologies described in Section 3.3. Development was organized into the following modules, each built and tested iteratively with continuous feedback from stakeholders:

1. Authentication and User Registration Module
2. Student Comprehensive Profiling Module
3. Appointment Booking and Management Module
4. Counseling Session Notes Module
5. Mental Health Event Management and Registration Module
6. Resource Library Module
7. Announcement Module
8. Feedback and Satisfaction Survey Module
9. Administrative Management Module
10. Analytics and Reporting Module

Each module was demonstrated to end-users upon completion, and feedback was incorporated before moving to the next module.

#### Phase 4: Cutover (Transition)
In the final phase, the completed system was subjected to user acceptance testing (UAT) with actual OGC staff and student users. Final adjustments were made based on UAT results. The system was then prepared for deployment, with user orientation conducted for counselors and administrators on system operation and management.

---

## 3.3 Technology Stack

The following technologies were used in the development of my.OGC:

### 3.3.1 Backend Framework
- **Laravel 12.0** – A PHP web application framework following the MVC architectural pattern. Laravel was chosen for its robust ORM (Eloquent), built-in authentication scaffolding (Breeze), routing system, middleware support, and extensive ecosystem.
- **PHP 8.2** – The server-side scripting language used to power the Laravel application.

### 3.3.2 Frontend Technologies
- **Blade Templating Engine** – Laravel's built-in templating engine used for server-side HTML rendering.
- **Tailwind CSS** – A utility-first CSS framework used for responsive and consistent UI design.
- **Bootstrap 5.3** – Used for additional UI components and grid layout support.
- **Font Awesome 6.4** – Icon library used throughout the interface.
- **Google Fonts (Inter)** – Typography used for a clean, modern interface.
- **Vanilla JavaScript** – Used for client-side interactivity including dynamic form behavior, custom modal dialogs, and toast notifications.

### 3.3.3 Database
- **MySQL** – Relational database management system used for persistent data storage, managed using Laravel's migration system for version-controlled schema management.

### 3.3.4 Third-Party Integrations
- **Google Calendar API** (`google/apiclient ^2.18`, `spatie/laravel-google-calendar ^3.8`) – Integrated to synchronize counselor availability with their Google Calendar, enabling real-time busy interval detection and appointment event creation.
- **Laravel Breeze** – Authentication scaffolding providing login, registration, password reset, and email verification functionality.

### 3.3.5 Development Tools
- **Laravel Herd** – Local development environment for Windows.
- **Composer** – PHP dependency manager.
- **Git / GitHub** – Version control and collaborative development.
- **Laravel Artisan** – Command-line interface for database migrations, seeding, and cache management.

---

## 3.4 System Architecture

my.OGC follows the **MVC (Model-View-Controller)** architectural pattern:

- **Models** encapsulate business logic and database interactions using Laravel's Eloquent ORM. Key models include `Appointment`, `Student`, `Counselor`, `Event`, `SessionNote`, and `Feedback`.
- **Views** are rendered using Blade templates, organized into role-specific layouts (`student.blade.php`, `app.blade.php` for counselors, `admin.blade.php` for administrators, and `guest.blade.php` for the landing page).
- **Controllers** handle HTTP requests, apply business logic, and return appropriate responses. Controllers are organized by feature domain (e.g., `AppointmentController`, `CounselorController`, `EventController`, `AdminController`).

### 3.4.1 Role-Based Access Control
The system implements three distinct user roles with separate access levels:

| Role | Access Level |
|---|---|
| **Student** | Dashboard, appointment booking, event registration, feedback, profile management, resource library |
| **Counselor** | Appointment management, session documentation, event creation, announcements, analytics, availability management |
| **Administrator** | Full system management including user accounts, appointments, events, resources, FAQs, and system-wide analytics |

Access control is enforced through Laravel middleware (`CounselorMiddleware`, `AdminMiddleware`) and route grouping in `web.php`.

---

## 3.5 System Features

### 3.5.1 User Registration and Authentication
The registration process includes MSU-IIT institutional email verification (`@g.msuiit.edu.ph`), a 6-digit time-limited verification code, and role-specific data collection. Authentication is session-based using Laravel Breeze with bcrypt password hashing.

### 3.5.2 Student Comprehensive Profiling
Upon registration, students complete a multi-section intake form covering:
- **Personal Data** – demographics, contact information, civil status
- **Family Data** – parents, guardians, siblings, family background
- **Academic Data** – GPA, scholarships, career aspirations, academic awards
- **Learning Resources** – internet access, devices, study environment
- **Psychosocial Data** – mental health history, coping mechanisms, counseling history
- **Needs Assessment** – areas for improvement, financial concerns, stress responses

Profile completion is tracked as a percentage, and incomplete profiles are flagged to encourage completion.

### 3.5.3 Appointment Booking and Management
Students can book counseling appointments by selecting a counselor, booking type (Initial Interview, Counseling, or Consultation), concern category, mood rating, date, and time slot. The system enforces counselor availability based on configured schedules and Google Calendar busy intervals, daily booking limits, and informed consent acknowledgment before booking confirmation. A unique case number (`CASE-YYYY-XXXXXX`) is auto-generated for each appointment.

Counselors can approve, complete, mark as no-show, reschedule, or refer appointments to other counselors.

**Appointment Statuses:**

| Status | Description |
|---|---|
| Pending | Awaiting counselor approval |
| Approved | Confirmed by counselor |
| Completed | Session conducted |
| Cancelled | Cancelled by student |
| No Show | Student did not attend |
| Referred | Transferred to another counselor |
| Rescheduled | New time proposed by counselor |
| Reschedule Requested | Awaiting student approval |
| Reschedule Rejected | Student declined reschedule |

### 3.5.4 Counseling Session Notes
Counselors document each session with structured notes including session type, mood level, presenting concerns, interventions, follow-up actions, and root cause analysis. Sessions are sequenced automatically (Initial Interview → 1st Session → 2nd Session, etc.).

### 3.5.5 Event Management
Counselors create mental health events (webinars, workshops, seminars, activities, conferences) with configurable settings including target colleges, year levels, maximum attendees, and required/optional designation. Required events auto-register eligible students. Students can register or cancel registrations subject to a 24-hour cancellation cutoff. Attendance is tracked by counselors.

### 3.5.6 Resource Library
A categorized library of mental health resources including YouTube videos (with automatic thumbnail extraction), eBooks, curated articles, and OGC-specific materials with a disclaimer system for sensitive content.

### 3.5.7 Announcements
Counselors publish announcements targeted to specific colleges with configurable active date ranges, image attachments, and status tracking (active, scheduled, expired, inactive).

### 3.5.8 Feedback System
Students submit satisfaction surveys rating their counseling experience on a 1–5 scale across multiple dimensions. Anonymous submission is supported. Feedback is viewable by counselors and administrators.

### 3.5.9 Administrative Management
Administrators manage all user accounts, view system-wide appointments, manage events, resources, and FAQs, and access analytics dashboards covering appointment statistics, referral tracking, session completion rates, and feedback analysis.

### 3.5.10 Google Calendar Integration
Counselors link their Google Calendar to the system. The `GoogleCalendarService` fetches busy intervals to prevent double-booking and creates calendar events for approved appointments.

---

## 3.6 User Interface Design

The user interface was designed following a consistent design system with the following characteristics:

- **Color Palette**: Maroon (`#820000`, `#5c1a1a`, `#7a2a2a`) and gold (`#d4af37`, `#c9a227`, `#FFC917`) reflecting MSU-IIT's institutional colors.
- **Typography**: Inter font family for clean, modern readability.
- **Layout**: Responsive sidebar navigation with collapsible support for mobile devices.
- **Feedback Components**: Custom toast notification system with success, error, warning, and info variants, featuring animated progress bars and auto-dismiss behavior.
- **Confirmation Dialogs**: Custom styled confirmation modals consistent with the system's design language, replacing native browser dialogs.
- **Landing Page**: A full-screen hero section with MSU-IIT OGC branding, an inspirational quote, and call-to-action buttons. Login is handled via an overlay modal without page navigation.

---

## 3.7 Data Flow

1. Users access the landing page (`my.OGC`) and authenticate via the login modal overlay.
2. Upon authentication, the system redirects users to their role-specific dashboard.
3. **Students** interact with the booking system, event registration, resource library, and feedback forms.
4. **Counselors** receive appointment requests, manage their calendar, document sessions, and create events and announcements.
5. **Administrators** oversee all system data, manage users, and access analytics.
6. All data is persisted to the MySQL database via Eloquent ORM.
7. Appointment-related actions trigger Google Calendar API calls for calendar synchronization.
8. Toast notifications provide real-time feedback to users after each action.

---

## 3.8 Evaluation Instrument

The system was evaluated using a structured questionnaire based on the **ISO/IEC 25010 Software Quality Model**, assessing the following quality characteristics:

| Quality Characteristic | Description |
|---|---|
| **Functional Suitability** | The system provides functions that meet stated and implied needs |
| **Performance Efficiency** | Response time and resource usage under normal conditions |
| **Usability** | Ease of use, learnability, and user satisfaction |
| **Reliability** | System availability and fault tolerance |
| **Security** | Protection of data and prevention of unauthorized access |
| **Maintainability** | Ease of modification and extension |
| **Portability** | Ability to operate in different environments |

Respondents rated each item on a **5-point Likert scale** (5 = Strongly Agree, 1 = Strongly Disagree). Mean scores were interpreted as follows:

| Mean Range | Interpretation |
|---|---|
| 4.50 – 5.00 | Strongly Agree |
| 3.50 – 4.49 | Agree |
| 2.50 – 3.49 | Neutral |
| 1.50 – 2.49 | Disagree |
| 1.00 – 1.49 | Strongly Disagree |

---

## 3.9 Respondents

The respondents of the study consisted of:

- **Students** of MSU-IIT who used the system for appointment booking and event registration
- **Guidance Counselors** of the MSU-IIT Office of Guidance and Counseling who used the system for appointment management and session documentation
- **System Administrators** who managed the system's user accounts and content

Purposive sampling was used to select respondents who had direct interaction with the system during the evaluation phase.

---

## 3.10 Data Collection and Analysis

Data was collected through:

1. **System Testing** – Functional testing of all modules to verify correctness of business logic, data validation, and role-based access control.
2. **User Acceptance Testing (UAT)** – End-users interacted with the system and provided feedback through structured questionnaires.
3. **Evaluation Questionnaires** – ISO/IEC 25010-based questionnaires administered to respondents after system demonstration.

Quantitative data from questionnaires were analyzed using **descriptive statistics** (mean and standard deviation). Qualitative feedback from UAT sessions was used to identify usability issues and inform iterative improvements to the system.

---

*End of Chapter 3*

---

## 3.2 System Development Methodology

The development of my.OGC followed the **Agile methodology**, specifically using iterative sprints to develop, test, and refine each module. The development lifecycle consisted of the following phases:

### 3.2.1 Requirements Gathering
Functional and non-functional requirements were gathered through consultations with the MSU-IIT Office of Guidance and Counseling staff, review of existing manual processes, and analysis of student counseling workflows. Key requirements identified include:

- Online appointment booking with counselor availability management
- Student comprehensive profiling and intake forms
- Counseling session documentation and tracking
- Mental health event creation and registration management
- Resource library for mental health materials
- Feedback and satisfaction survey system
- Role-based access control for students, counselors, and administrators
- Google Calendar integration for counselor scheduling
- Administrative analytics and reporting

### 3.2.2 System Design
The system architecture was designed using the **Model-View-Controller (MVC)** pattern provided by the Laravel framework. The database schema was designed to support multi-role access, relational data integrity, and extensibility.

**Entity-Relationship Design:**
The database consists of the following core entities and their relationships:

| Entity | Description |
|---|---|
| `users` | Base authentication table for all roles |
| `students` | Student academic and profile data |
| `counselors` | Counselor credentials and availability |
| `admins` | Administrator accounts |
| `colleges` | Organizational units of MSU-IIT |
| `appointments` | Counseling session bookings |
| `session_notes` | Documentation of counseling sessions |
| `events` | Mental health events and activities |
| `event_registrations` | Student event participation records |
| `resources` | Mental health learning materials |
| `announcements` | Counselor-created announcements |
| `feedback` | Student satisfaction surveys |
| `faqs` | Frequently asked questions |
| `student_personal_data` | Personal background information |
| `student_family_data` | Family background information |
| `student_academic_data` | Academic performance data |
| `student_learning_resources` | Learning environment data |
| `student_psychosocial_data` | Mental health and psychosocial data |
| `student_needs_assessments` | Student needs and concerns |
| `counselor_schedule_overrides` | Exceptions to counselor availability |

### 3.2.3 Implementation
The system was implemented using the technologies and tools described in Section 3.3. Development was organized into the following modules, each developed and tested iteratively:

1. Authentication and User Registration Module
2. Student Profiling Module
3. Appointment Booking and Management Module
4. Counseling Session Notes Module
5. Event Management and Registration Module
6. Resource Library Module
7. Announcement Module
8. Feedback and Survey Module
9. Administrative Management Module
10. Analytics and Reporting Module

### 3.2.4 Testing
Each module underwent unit testing and integration testing. User acceptance testing (UAT) was conducted with actual end-users from the MSU-IIT OGC to validate system functionality, usability, and correctness of business logic.

### 3.2.5 Deployment
The system was deployed on a local development environment using **Laravel Herd** for Windows, with plans for production deployment on a university-managed server.

---

## 3.3 Technology Stack

The following technologies were used in the development of my.OGC:

### 3.3.1 Backend Framework
- **Laravel 12.0** – A PHP web application framework following the MVC architectural pattern. Laravel was chosen for its robust ORM (Eloquent), built-in authentication scaffolding (Breeze), routing system, middleware support, and extensive ecosystem.
- **PHP 8.2** – The server-side scripting language used to power the Laravel application.

### 3.3.2 Frontend Technologies
- **Blade Templating Engine** – Laravel's built-in templating engine used for server-side HTML rendering.
- **Tailwind CSS** – A utility-first CSS framework used for responsive and consistent UI design.
- **Bootstrap 5.3** – Used for additional UI components and grid layout support.
- **Font Awesome 6.4** – Icon library used throughout the interface.
- **Google Fonts (Inter)** – Typography used for a clean, modern interface.
- **Vanilla JavaScript** – Used for client-side interactivity including dynamic form behavior, custom modal dialogs, and toast notifications.

### 3.3.3 Database
- **MySQL** – Relational database management system used for persistent data storage. The database was managed using Laravel's migration system for version-controlled schema management.

### 3.3.4 Third-Party Integrations
- **Google Calendar API** (`google/apiclient ^2.18`, `spatie/laravel-google-calendar ^3.8`) – Integrated to synchronize counselor availability with their Google Calendar, enabling real-time busy interval detection and appointment event creation.
- **Laravel Breeze** – Authentication scaffolding providing login, registration, password reset, and email verification functionality.

### 3.3.5 Development Tools
- **Laravel Herd** – Local development environment for Windows.
- **Composer** – PHP dependency manager.
- **Git / GitHub** – Version control and collaborative development.
- **Laravel Artisan** – Command-line interface for database migrations, seeding, and cache management.

---

## 3.4 System Architecture

my.OGC follows the **MVC (Model-View-Controller)** architectural pattern:

- **Models** encapsulate business logic and database interactions using Laravel's Eloquent ORM. Key models include `Appointment`, `Student`, `Counselor`, `Event`, `SessionNote`, and `Feedback`.
- **Views** are rendered using Blade templates, organized into role-specific layouts (`student.blade.php`, `app.blade.php` for counselors, `admin.blade.php` for administrators, and `guest.blade.php` for the landing page).
- **Controllers** handle HTTP requests, apply business logic, and return appropriate responses. Controllers are organized by feature domain (e.g., `AppointmentController`, `CounselorController`, `EventController`, `AdminController`).

### 3.4.1 Role-Based Access Control
The system implements three distinct user roles with separate access levels:

| Role | Access Level |
|---|---|
| **Student** | Dashboard, appointment booking, event registration, feedback, profile management, resource library |
| **Counselor** | Appointment management, session documentation, event creation, announcements, analytics, availability management |
| **Administrator** | Full system management including user accounts, appointments, events, resources, FAQs, and system-wide analytics |

Access control is enforced through Laravel middleware (`CounselorMiddleware`, `AdminMiddleware`) and route grouping in `web.php`.

---

## 3.5 System Features

### 3.5.1 User Registration and Authentication
The registration process includes MSU-IIT institutional email verification (`@g.msuiit.edu.ph`), a 6-digit time-limited verification code, and role-specific data collection. Authentication is session-based using Laravel Breeze with bcrypt password hashing.

### 3.5.2 Student Comprehensive Profiling
Upon registration, students complete a multi-section intake form covering:
- **Personal Data** – demographics, contact information, civil status
- **Family Data** – parents, guardians, siblings, family background
- **Academic Data** – GPA, scholarships, career aspirations, academic awards
- **Learning Resources** – internet access, devices, study environment
- **Psychosocial Data** – mental health history, coping mechanisms, counseling history
- **Needs Assessment** – areas for improvement, financial concerns, stress responses

Profile completion is tracked as a percentage, and incomplete profiles are flagged to encourage completion.

### 3.5.3 Appointment Booking and Management
Students can book counseling appointments by selecting a counselor, booking type (Initial Interview, Counseling, or Consultation), concern category, mood rating, date, and time slot. The system enforces:
- Counselor availability based on configured schedules and Google Calendar busy intervals
- Daily booking limits per counselor
- Duplicate appointment prevention
- Informed consent acknowledgment before booking confirmation

Counselors can approve, complete, mark as no-show, reschedule, or refer appointments to other counselors. A unique case number (`CASE-YYYY-XXXXXX`) is auto-generated for each appointment.

**Appointment Statuses:**

| Status | Description |
|---|---|
| Pending | Awaiting counselor approval |
| Approved | Confirmed by counselor |
| Completed | Session conducted |
| Cancelled | Cancelled by student |
| No Show | Student did not attend |
| Referred | Transferred to another counselor |
| Rescheduled | New time proposed by counselor |
| Reschedule Requested | Awaiting student approval |
| Reschedule Rejected | Student declined reschedule |

### 3.5.4 Counseling Session Notes
Counselors document each session with structured notes including session type, mood level, presenting concerns, interventions, follow-up actions, and root cause analysis. Sessions are sequenced automatically (Initial Interview → 1st Session → 2nd Session, etc.).

### 3.5.5 Event Management
Counselors create mental health events (webinars, workshops, seminars, activities, conferences) with configurable settings including target colleges, year levels, maximum attendees, and required/optional designation. Required events auto-register eligible students. Students can register or cancel registrations (subject to a 24-hour cancellation cutoff). Attendance is tracked by counselors.

### 3.5.6 Resource Library
The system provides a categorized library of mental health resources including YouTube videos (with automatic thumbnail extraction), eBooks, curated articles, and OGC-specific materials. Resources include a disclaimer system for sensitive content.

### 3.5.7 Announcements
Counselors can publish announcements targeted to specific colleges with configurable active date ranges, image attachments, and status tracking (active, scheduled, expired, inactive).

### 3.5.8 Feedback System
Students submit satisfaction surveys rating their counseling experience on a 1–5 scale across multiple dimensions (SQD fields). Anonymous submission is supported. Feedback is viewable by counselors and administrators.

### 3.5.9 Administrative Management
Administrators manage all user accounts, view system-wide appointments, manage events, resources, and FAQs, and access analytics dashboards covering appointment statistics, referral tracking, session completion rates, and feedback analysis.

### 3.5.10 Google Calendar Integration
Counselors link their Google Calendar to the system. The `GoogleCalendarService` fetches busy intervals to prevent double-booking and creates calendar events for approved appointments.

---

## 3.6 User Interface Design

The user interface was designed following a consistent design system with the following characteristics:

- **Color Palette**: Maroon (`#820000`, `#5c1a1a`, `#7a2a2a`) and gold (`#d4af37`, `#c9a227`, `#FFC917`) reflecting MSU-IIT's institutional colors.
- **Typography**: Inter font family for clean, modern readability.
- **Layout**: Responsive sidebar navigation with collapsible support for mobile devices.
- **Feedback Components**: Custom toast notification system (replacing native browser alerts) with success, error, warning, and info variants, featuring animated progress bars and auto-dismiss behavior.
- **Confirmation Dialogs**: Custom styled confirmation modals (replacing native `window.confirm`) consistent with the system's design language.
- **Landing Page**: A full-screen hero section with the MSU-IIT OGC branding, quote, and call-to-action buttons. Login is handled via an overlay modal without page navigation.

---

## 3.7 Data Flow

The general data flow of the system is as follows:

1. **Student** accesses the landing page (`my.OGC`) and logs in via the modal overlay.
2. Upon authentication, the system redirects the user to their role-specific dashboard.
3. **Students** interact with the booking system, event registration, resource library, and feedback forms.
4. **Counselors** receive appointment requests, manage their calendar, document sessions, and create events and announcements.
5. **Administrators** oversee all system data, manage users, and access analytics.
6. All data is persisted to the MySQL database via Eloquent ORM.
7. Appointment-related actions trigger Google Calendar API calls for calendar synchronization.
8. Flash messages and toast notifications provide real-time feedback to users after each action.

---

## 3.8 Evaluation Instrument

The system was evaluated using a structured questionnaire based on the **ISO/IEC 25010 Software Quality Model**, assessing the following quality characteristics:

| Quality Characteristic | Description |
|---|---|
| **Functional Suitability** | The system provides functions that meet stated and implied needs |
| **Performance Efficiency** | Response time and resource usage under normal conditions |
| **Usability** | Ease of use, learnability, and user satisfaction |
| **Reliability** | System availability and fault tolerance |
| **Security** | Protection of data and prevention of unauthorized access |
| **Maintainability** | Ease of modification and extension |
| **Portability** | Ability to operate in different environments |

Respondents rated each item on a **5-point Likert scale** (5 = Strongly Agree, 1 = Strongly Disagree). Mean scores were interpreted as follows:

| Mean Range | Interpretation |
|---|---|
| 4.50 – 5.00 | Strongly Agree |
| 3.50 – 4.49 | Agree |
| 2.50 – 3.49 | Neutral |
| 1.50 – 2.49 | Disagree |
| 1.00 – 1.49 | Strongly Disagree |

---

## 3.9 Respondents

The respondents of the study consisted of:

- **Students** of MSU-IIT who used the system for appointment booking and event registration
- **Guidance Counselors** of the MSU-IIT Office of Guidance and Counseling who used the system for appointment management and session documentation
- **System Administrators** who managed the system's user accounts and content

Purposive sampling was used to select respondents who had direct interaction with the system during the evaluation phase.

---

## 3.10 Data Collection and Analysis

Data was collected through:

1. **System Testing** – Functional testing of all modules to verify correctness of business logic, data validation, and role-based access control.
2. **User Acceptance Testing (UAT)** – End-users interacted with the system and provided feedback through structured questionnaires.
3. **Evaluation Questionnaires** – ISO/IEC 25010-based questionnaires administered to respondents after system demonstration.

Quantitative data from questionnaires were analyzed using **descriptive statistics** (mean and standard deviation). Qualitative feedback from UAT sessions was used to identify usability issues and inform iterative improvements to the system.

---

*End of Chapter 3*
