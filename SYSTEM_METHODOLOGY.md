# System Methodology - myOGC Student Counseling Management System

## 1. System Overview

### 1.1 Purpose
The myOGC (Mental Health Corner) system is a comprehensive student counseling management platform designed for MSU-IIT. It streamlines mental health services by managing appointments, student assessments, counselor workflows, events, and resources through a role-based web application with Google Calendar integration.

### 1.2 Core Objectives
- Enable efficient appointment booking and management between students and counselors
- Track comprehensive student profiles and mental health assessments
- Facilitate counselor collaboration through referral system
- Organize wellness events and provide mental health resources
- Maintain detailed session documentation and follow-up tracking
- Integrate with Google Calendar for seamless scheduling

### 1.3 Technology Stack
- **Framework:** Laravel 12
- **Database:** SQLite (development) / MySQL (production)
- **Authentication:** Laravel Breeze
- **Frontend:** Blade templates with Tailwind CSS
- **External Integration:** Google Calendar API (Spatie package)
- **Session/Queue/Cache:** Database-driven

---

## 2. User Roles & Access Control

### 2.1 Student Role
**Primary Functions:**
- Register with email verification
- Complete comprehensive profile assessments
- Book counseling appointments (Initial Interview, Counseling, Consultation)
- Register for wellness events
- View appointment history and session notes
- Access mental health resources
- Submit feedback

**Access Restrictions:**
- Cannot view other students' data
- Cannot access counselor or admin dashboards
- Must complete initial interview before booking other appointment types (1st/2nd year only)

### 2.2 Counselor Role
**Primary Functions:**
- Manage appointment requests (approve, reject, reschedule)
- Conduct counseling sessions and write session notes
- Create follow-up appointments
- Refer students to other counselors
- Set availability and schedule overrides
- Create and manage events
- Create and manage resources
- View comprehensive student profiles
- Export appointment data

**Access Restrictions:**
- Can only manage appointments assigned to them
- Can view student profiles only when appointment exists
- Cannot access admin functions

### 2.3 Admin Role
**Primary Functions:**
- Manage all users (create, edit, delete)
- Manage students and counselors
- Manage system-wide events, resources, FAQs
- View all appointments and feedback
- Export system data
- System configuration

**Access Level:**
- Full system access
- Can perform all counselor functions
- Can manage all content types

---

## 3. Appointment Management Methodology

### 3.1 Appointment Lifecycle

```
┌─────────────────────────────────────────────────────────────┐
│                    APPOINTMENT WORKFLOW                      │
└─────────────────────────────────────────────────────────────┘

Student Books → [PENDING] → Counselor Reviews
                    │
                    ├─→ [APPROVED] → Session Conducted → [COMPLETED]
                    │                                          │
                    ├─→ [REJECTED]                            │
                    │                                          │
                    ├─→ [CANCELLED] (by student/counselor)    │
                    │                                          │
                    ├─→ [RESCHEDULE_REQUESTED] → Counselor Reviews
                    │         │                        │
                    │         ├─→ [RESCHEDULED]       │
                    │         └─→ [RESCHEDULE_REJECTED]
                    │
                    └─→ [REFERRED] → New Counselor → [PENDING]
                              │
                              └─→ Student Accepts/Rejects
```

### 3.2 Booking Types

**A. Initial Interview**
- **Purpose:** First-time assessment for new students
- **Requirement:** Mandatory for 1st and 2nd year students before other bookings
- **Duration:** Typically 60 minutes
- **Outcome:** Comprehensive student profile completion

**B. Counseling**
- **Purpose:** Regular counseling sessions
- **Requirement:** Initial interview must be completed (for 1st/2nd year)
- **Duration:** 30-60 minutes
- **Outcome:** Session notes and follow-up plan

**C. Consultation**
- **Purpose:** Brief consultations or follow-ups
- **Duration:** 15-30 minutes
- **Outcome:** Quick guidance or referral

### 3.3 Appointment Scheduling Rules

**Availability Determination:**
1. Check counselor's weekly availability (JSON field)
2. Check schedule overrides (unavailable dates)
3. Check daily booking limit
4. Check Google Calendar for conflicts
5. Verify no overlapping appointments in system

**Time Slot Validation:**
```php
// Pseudo-logic
if (date is in schedule_overrides) {
    return unavailable;
}

if (appointments_count_for_date >= daily_booking_limit) {
    return unavailable;
}

if (google_calendar_has_conflict(date, time)) {
    return unavailable;
}

if (existing_appointment_overlaps(date, time)) {
    return unavailable;
}

return available;
```

### 3.4 Referral System

**Referral Workflow:**
1. Counselor identifies need for specialist/different counselor
2. Counselor initiates referral with reason
3. Appointment status changes to `referred`
4. Student receives notification
5. Student accepts or rejects referral
6. If accepted: new appointment created with referred counselor
7. If rejected: appointment returns to original counselor

**Referral Types:**
- Cross-college referrals (allowed)
- Specialization-based referrals
- Workload distribution referrals

### 3.5 Rescheduling Process

**Student-Initiated:**
1. Student requests reschedule with proposed date/time
2. Status changes to `reschedule_requested`
3. Counselor reviews and approves/rejects
4. If approved: appointment updated, Google Calendar synced
5. If rejected: appointment returns to original schedule

**Counselor-Initiated:**
1. Counselor proposes new date/time
2. Student receives notification
3. Student accepts or rejects
4. If accepted: appointment updated automatically

### 3.6 Case Number System

**Format:** `CASE-YYYY-XXXXXX`
- **YYYY:** Year of appointment
- **XXXXXX:** Zero-padded appointment ID

**Purpose:**
- Unique identifier for tracking
- Documentation reference
- Report generation
- Historical tracking

---

## 4. Student Profiling Methodology

### 4.1 Comprehensive Assessment Structure

The system captures multi-dimensional student data through six assessment modules:

**A. Personal Data** (`StudentPersonalData`)
- Nickname, home address, living situation
- Talents, skills, hobbies, leisure activities
- Medical conditions, disabilities
- Sexual identity, romantic attraction
- Personal characteristics

**B. Family Data** (`StudentFamilyData`)
- Family structure and relationships
- Parental information
- Sibling information
- Family dynamics

**C. Academic Data** (`StudentAcademicData`)
- SHS GPA and track/strand
- Scholarship information
- School history
- Awards, honors, organizations
- Career options and future plans
- Course choice reasons

**D. Psychosocial Data** (`StudentPsychosocialData`)
- Personality characteristics
- Coping mechanisms
- Mental health perception
- Previous counseling history
- Problem-sharing targets
- Future counseling concerns
- Immediate counseling needs flag

**E. Learning Resources** (`StudentLearningResources`)
- Study habits
- Learning support systems
- Academic challenges

**F. Needs Assessment** (`StudentNeedsAssessment`)
- Areas requiring improvement
- Support needs
- Intervention priorities

### 4.2 Assessment Workflow

```
Student Registration → Initial Interview Booking → Session Conducted
                                                          │
                                                          ↓
                                    Counselor Completes Assessment Forms
                                                          │
                                                          ↓
                                    Data Stored in Profile Modules
                                                          │
                                                          ↓
                                    Available for Future Sessions
```

### 4.3 Data Privacy & Access

**Access Rules:**
- Students can view their own profile data
- Counselors can view profiles only for students with appointments
- Admins have full access for management purposes
- Session notes are confidential (student + assigned counselor only)

---

## 5. Session Documentation Methodology

### 5.1 Session Note Structure

**Required Fields:**
- Session date and type
- Counselor and student IDs
- Appointment reference

**Documentation Fields:**
- **Notes:** Detailed session observations and discussions
- **Follow-up Actions:** Assignments, recommendations, next steps
- **Root Causes:** Array of identified underlying issues
- **Mood Level:** Numerical assessment (1-10)
- **Session Type:** Initial, follow-up, crisis, regular
- **Appointment Type:** Initial interview, counseling, consultation

**Follow-up Tracking:**
- Requires follow-up flag (boolean)
- Next session date (if applicable)

### 5.2 Documentation Best Practices

**During Session:**
1. Take brief notes during session
2. Observe student mood and behavior
3. Identify key concerns and root causes

**Post-Session:**
1. Complete session note within 24 hours
2. Document follow-up actions clearly
3. Set follow-up flag if needed
4. Schedule next appointment if required

**Confidentiality:**
- All session notes are confidential
- Access restricted to assigned counselor and student
- Admins can view for oversight purposes only

---

## 6. Event Management Methodology

### 6.1 Event Types

**A. Webinar**
- Online format
- Large audience capacity
- Educational focus

**B. Workshop**
- Interactive format
- Skill-building focus
- Limited capacity

**C. Seminar**
- Lecture format
- Information dissemination
- Moderate capacity

**D. Activity**
- Hands-on participation
- Wellness focus
- Variable capacity

**E. Conference**
- Multi-session format
- Professional development
- Large capacity

### 6.2 Event Configuration

**Targeting Options:**
- **All Colleges:** Event available to all students
- **Specific Colleges:** Event limited to selected colleges

**Registration Settings:**
- **Max Attendees:** Capacity limit (optional)
- **Is Required:** Auto-register eligible students
- **Is Active:** Enable/disable registration

### 6.3 Registration Workflow

```
Event Created → Published → Student Browses → Registers
                                                  │
                                                  ↓
                                    Registration Status: REGISTERED
                                                  │
                                    ┌─────────────┴─────────────┐
                                    │                           │
                                    ↓                           ↓
                            Student Attends              Student Cancels
                                    │                    (24hrs before)
                                    ↓                           │
                            Status: ATTENDED                    ↓
                                                        Status: CANCELLED
                                                                │
                                                                ↓
                                                        Can Re-register
```

### 6.4 Required Events

**Auto-Registration Logic:**
1. Event marked as `is_required`
2. System identifies eligible students (by college)
3. Auto-creates registrations with status `registered`
4. Students receive notification
5. Attendance tracked manually by counselor/admin

---

## 7. Google Calendar Integration Methodology

### 7.1 OAuth Authentication Flow

```
Counselor Account Created → Admin Runs Token Generation Command
                                        │
                                        ↓
                            Google OAuth Consent Screen
                                        │
                                        ↓
                            Authorization Code Received
                                        │
                                        ↓
                            Token Saved to Storage
                            (storage/app/google-calendar/tokens/{user_id}.json)
                                        │
                                        ↓
                            Calendar ID Saved to Counselor Record
```

### 7.2 Appointment Synchronization

**Event Creation:**
1. Appointment approved by counselor
2. System retrieves counselor's OAuth token
3. Creates Google Calendar event with:
   - Title: Student name + appointment type
   - Date/time: Appointment schedule
   - Description: Concern and notes
4. Event ID stored in appointment record

**Event Deletion:**
1. Appointment cancelled or rejected
2. System retrieves event ID from appointment
3. Deletes event from Google Calendar
4. Clears event ID from appointment record

**Event Update:**
1. Appointment rescheduled
2. System deletes old event
3. Creates new event with updated details
4. Updates event ID in appointment record

### 7.3 Availability Checking

**Busy Interval Detection:**
```php
// Pseudo-logic
function getAvailableSlots(counselor, date) {
    // Get counselor's weekly availability
    weeklySchedule = counselor.availability[date.dayOfWeek];
    
    // Get schedule overrides
    if (counselor.scheduleOverrides.contains(date)) {
        return [];
    }
    
    // Get Google Calendar busy intervals
    busyIntervals = googleCalendar.getBusyIntervals(counselor.calendar_id, date);
    
    // Get existing appointments
    existingAppointments = appointments.where(counselor_id, date);
    
    // Calculate available slots
    availableSlots = weeklySchedule
        .subtract(busyIntervals)
        .subtract(existingAppointments)
        .filter(slot => !overlaps);
    
    return availableSlots;
}
```

---

## 8. Resource Management Methodology

### 8.1 Resource Categories

**A. YouTube Videos**
- External YouTube links
- Automatic thumbnail extraction
- Embedded player support

**B. eBooks**
- PDF uploads
- Download capability
- Preview support

**C. Private Videos**
- Internal video hosting
- Restricted access
- Streaming support

**D. OGC Resources**
- Custom content
- Mixed media support
- Internal documentation

### 8.2 Resource Organization

**Ordering System:**
- Manual ordering field
- Allows custom arrangement
- Display priority control

**Status Management:**
- Active/inactive toggle
- Visibility control
- Archive capability

---

## 9. Feedback Collection Methodology

### 9.1 Feedback Types

**A. Anonymous Feedback**
- No user identification
- General service feedback
- System improvement suggestions

**B. Identified Feedback**
- Linked to user account
- Counselor-specific feedback
- Follow-up capability

### 9.2 Feedback Metrics

**Satisfaction Rating:**
- Scale: 1-5 stars
- Service quality indicator
- Trend analysis support

**Survey Questions (SQD):**
- Standardized questionnaire
- Quantitative data collection
- Comparative analysis

**CSM Fields:**
- Customer Service Management metrics
- Service delivery assessment
- Performance indicators

---

## 10. Data Export & Reporting Methodology

### 10.1 Export Capabilities

**Appointment Data:**
- Date range filtering
- Status filtering
- Counselor filtering
- Format: CSV/Excel

**Event Registrations:**
- Event-specific exports
- Attendance tracking
- Registration statistics

**Feedback Data:**
- Counselor-specific reports
- Date range filtering
- Satisfaction trends

### 10.2 Report Types

**A. Appointment Reports**
- Total appointments by status
- Counselor workload distribution
- Peak booking times
- Cancellation rates
- Referral statistics

**B. Event Reports**
- Registration rates
- Attendance rates
- Cancellation patterns
- Popular event types

**C. Student Reports**
- Profile completion rates
- Appointment history
- Assessment summaries
- Follow-up tracking

---

## 11. System Workflows

### 11.1 Student Onboarding Workflow

```
1. Student Registration
   ├─→ Email verification
   ├─→ Basic profile creation
   └─→ College assignment

2. Initial Interview Booking (1st/2nd year)
   ├─→ Select counselor
   ├─→ Choose available slot
   └─→ Submit booking request

3. Counselor Approval
   ├─→ Review request
   ├─→ Approve/reject
   └─→ Google Calendar sync

4. Session Conducted
   ├─→ Complete assessment forms
   ├─→ Write session notes
   └─→ Mark appointment complete

5. Ongoing Services
   ├─→ Book additional appointments
   ├─→ Register for events
   └─→ Access resources
```

### 11.2 Counselor Daily Workflow

```
Morning:
├─→ Check appointment dashboard
├─→ Review pending requests
├─→ Approve/schedule appointments
└─→ Prepare for sessions

During Sessions:
├─→ Conduct counseling
├─→ Take session notes
└─→ Identify follow-up needs

Post-Session:
├─→ Complete session notes
├─→ Schedule follow-ups
├─→ Process referrals if needed
└─→ Update student profiles

End of Day:
├─→ Review completed appointments
├─→ Export data if needed
└─→ Plan next day schedule
```

### 11.3 Admin Maintenance Workflow

```
Weekly:
├─→ Review user accounts
├─→ Manage content (events, resources, FAQs)
├─→ Monitor feedback
└─→ Generate reports

Monthly:
├─→ Analyze appointment trends
├─→ Review counselor workload
├─→ Update system content
└─→ Export historical data

Quarterly:
├─→ System performance review
├─→ User satisfaction analysis
├─→ Feature planning
└─→ Training needs assessment
```

---

## 12. Business Rules & Constraints

### 12.1 Appointment Rules

1. **Initial Interview Requirement**
   - 1st and 2nd year students must complete initial interview first
   - Enforced at booking validation level
   - Flag stored in student record

2. **Daily Booking Limits**
   - Configurable per counselor
   - Default: 8 appointments per day
   - Prevents counselor overload

3. **Time Slot Duration**
   - Initial Interview: 60 minutes
   - Counseling: 30-60 minutes
   - Consultation: 15-30 minutes

4. **Cancellation Policy**
   - Students can cancel up to 24 hours before
   - Counselors can cancel anytime with notification
   - Cancelled slots become available immediately

5. **Rescheduling Limits**
   - Maximum 2 reschedule requests per appointment
   - Must provide reason for reschedule
   - Subject to counselor approval

### 12.2 Event Rules

1. **Registration Deadlines**
   - Registration closes 24 hours before event
   - Cancellation allowed up to 24 hours before
   - Re-registration allowed after cancellation

2. **Capacity Management**
   - First-come, first-served basis
   - Waitlist not implemented (future feature)
   - Required events bypass capacity limits

3. **Attendance Tracking**
   - Manual marking by counselor/admin
   - Cannot be changed after event ends
   - Used for participation reports

### 12.3 Data Retention Rules

1. **Appointment Records**
   - Retained indefinitely for historical tracking
   - Soft delete implementation
   - Archive after 2 years (future feature)

2. **Session Notes**
   - Retained for duration of student enrollment
   - Confidential access only
   - Export capability for student upon request

3. **User Accounts**
   - Active during enrollment
   - Deactivated upon graduation/withdrawal
   - Data retained for 5 years per policy

---

## 13. Security & Privacy Methodology

### 13.1 Authentication Security

- Password hashing with bcrypt
- Session-based authentication
- CSRF protection on all forms
- Email verification required
- Password reset with token expiration

### 13.2 Authorization Controls

- Role-based access control (RBAC)
- Middleware enforcement on routes
- Model-level policy checks
- Resource ownership validation

### 13.3 Data Privacy

- Student data accessible only to assigned counselors
- Session notes confidential
- Feedback anonymization option
- PII protection in exports
- GDPR-compliant data handling

### 13.4 API Security

- Google Calendar OAuth 2.0
- Token encryption at rest
- Secure token storage
- Token refresh handling
- API rate limiting

---

## 14. Error Handling & Logging

### 14.1 Error Categories

**A. User Errors**
- Validation failures
- Booking conflicts
- Permission denials
- Display user-friendly messages

**B. System Errors**
- Database connection failures
- Google Calendar API errors
- File upload failures
- Log and notify admin

**C. Integration Errors**
- OAuth token expiration
- Calendar sync failures
- Email delivery failures
- Retry logic with fallback

### 14.2 Logging Strategy

**Application Logs:**
- User actions (login, booking, cancellation)
- System events (cron jobs, exports)
- Error occurrences with stack traces

**Audit Logs:**
- Admin actions (user management, content changes)
- Counselor actions (appointment management, referrals)
- Data exports and report generation

---

## 15. Performance Optimization

### 15.1 Database Optimization

- Indexed foreign keys
- Composite indexes on frequently queried columns
- Eager loading for relationships
- Query result caching

### 15.2 Caching Strategy

- Cache counselor availability
- Cache event listings
- Cache resource listings
- Cache FAQ data
- Clear cache on updates

### 15.3 Asset Optimization

- Image compression for uploads
- Thumbnail generation for events
- Lazy loading for images
- CDN for static assets (future)

---

## 16. Future Enhancements

### 16.1 Planned Features

1. **Mobile Application**
   - Native iOS/Android apps
   - Push notifications
   - Offline capability

2. **Video Counseling**
   - Integrated video conferencing
   - Session recording (with consent)
   - Screen sharing capability

3. **AI-Powered Features**
   - Chatbot for initial triage
   - Sentiment analysis on feedback
   - Appointment recommendation engine

4. **Advanced Analytics**
   - Predictive analytics for student needs
   - Counselor performance dashboards
   - Trend analysis and forecasting

5. **Waitlist Management**
   - Automatic slot filling
   - Priority queuing
   - Notification system

6. **Group Counseling**
   - Multi-student sessions
   - Group event management
   - Collaborative notes

---

## 17. Maintenance & Support

### 17.1 Regular Maintenance Tasks

**Daily:**
- Monitor system logs
- Check Google Calendar sync status
- Verify email delivery

**Weekly:**
- Database backup
- Review error logs
- Update content

**Monthly:**
- Security updates
- Performance review
- User feedback analysis

### 17.2 Support Procedures

**User Support:**
- Help documentation
- FAQ section
- Email support
- In-app guidance

**Technical Support:**
- Error reporting system
- Bug tracking
- Feature requests
- System monitoring

---

## 18. Deployment & Configuration

### 18.1 Environment Setup

**Development:**
- SQLite database
- Local file storage
- Debug mode enabled
- SSL verification disabled for Google Calendar

**Production:**
- MySQL database
- Cloud storage (S3/similar)
- Debug mode disabled
- SSL verification enabled
- HTTPS required

### 18.2 Configuration Files

**Key Configuration:**
- `.env` - Environment variables
- `config/google-calendar.php` - Calendar settings
- `config/database.php` - Database connections
- `config/mail.php` - Email settings

### 18.3 Deployment Checklist

1. Update environment variables
2. Run database migrations
3. Seed initial data (colleges, services)
4. Configure Google Calendar OAuth
5. Set up cron jobs for scheduled tasks
6. Configure email service
7. Set up backup procedures
8. Configure monitoring and logging
9. Test all critical workflows
10. Train users and administrators

---

## 19. Conclusion

This system methodology provides a comprehensive framework for understanding, maintaining, and extending the myOGC Student Counseling Management System. It covers all aspects of system operation from user workflows to technical implementation, ensuring consistent and effective mental health service delivery at MSU-IIT.

For specific implementation details, refer to the codebase documentation and inline comments. For operational procedures, consult the user manuals and training materials.

---

**Document Version:** 1.0  
**Last Updated:** March 20, 2026  
**Maintained By:** Development Team
