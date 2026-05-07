# Figure 4.4 — Use Case Diagram of my.OGC

## Purpose
Shows the interactions between the three primary system actors and the platform's features.

## Chapter 4 Explanation
The use case diagram illustrates the three primary actors of my.OGC: Student, Guidance
Counselor, and Administrator. Each actor interacts with a distinct but overlapping set of
system features. Students can log in, book and manage appointments, access the Mental Health
Corner, view announcements and events, receive notifications, and submit feedback. Guidance
Counselors manage appointment requests — they can accept, reschedule, or refer appointments to
another counselor, but cannot outright reject them — record session notes, view student records,
monitor follow-through cases, manage and upload resources, and receive notifications. Administrators
oversee user accounts, resources, announcements, events, feedback, and system-wide activity.
Both Guidance Counselors and Administrators can manage resources, as confirmed by the presence
of dedicated resource management controllers for each role (ResourceController and
AdminResourceController). Student Peer Facilitators participated in the study as evaluators
only and are not primary system actors.

---

## Use Cases by Actor

### Student
- Log in
- View dashboard
- Request / book appointment
- View appointment status
- Cancel appointment
- Receive notifications
- Access Mental Health Corner
- View announcements and events
- Submit feedback

### Guidance Counselor
- Log in
- View counselor dashboard
- Manage appointment requests
- Accept / reschedule / refer appointments
- View student records
- Record session notes
- Monitor follow-through cases
- Manage / upload resources *(confirmed — ResourceController routes to counselor.resources views)*
- Receive notifications

### Administrator
- Log in
- Manage user accounts
- Manage resources *(confirmed — AdminResourceController)*
- Manage announcements
- Manage events
- View feedback
- View administrative logs
- Monitor system activity

---

## Items Needing Confirmation
- None. All use cases verified against implemented controllers.

---

```plantuml
@startuml
left to right direction
skinparam actorStyle awesome
skinparam packageStyle rectangle
skinparam ArrowColor #333333
skinparam ActorBorderColor #333333
skinparam UseCaseBorderColor #555555
skinparam UseCaseBackgroundColor #f9f9f9
skinparam NoteBackgroundColor #fffde7
skinparam NoteBorderColor #f0c040

actor "Student" as S
actor "Guidance\nCounselor" as C
actor "Administrator" as A

rectangle "my.OGC System" {

    package "Authentication" {
        usecase "Log In" as UC_LOGIN
    }

    package "Student Features" {
        usecase "View Dashboard" as UC_S1
        usecase "Request / Book Appointment" as UC_S2
        usecase "View Appointment Status" as UC_S3
        usecase "Cancel Appointment" as UC_S4
        usecase "Receive Notifications" as UC_S5
        usecase "Access Mental Health Corner" as UC_S6
        usecase "View Announcements & Events" as UC_S7
        usecase "Submit Feedback" as UC_S8
    }

    package "Guidance Counselor Features" {
        usecase "View Counselor Dashboard" as UC_C1
        usecase "Manage Appointment Requests" as UC_C2
        usecase "Accept / Reschedule / Refer Appointments" as UC_C3
        usecase "View Student Records" as UC_C4
        usecase "Record Session Notes" as UC_C5
        usecase "Monitor Follow-Through Cases" as UC_C6
        usecase "Manage / Upload Resources" as UC_C7
        usecase "Receive Notifications" as UC_C8
    }

    package "Administrator Features" {
        usecase "Manage User Accounts" as UC_A1
        usecase "Manage Resources" as UC_A2
        usecase "Manage Announcements" as UC_A3
        usecase "Manage Events" as UC_A4
        usecase "View Feedback" as UC_A5
        usecase "View Administrative Logs" as UC_A6
        usecase "Monitor System Activity" as UC_A7
    }
}

S --> UC_LOGIN
S --> UC_S1
S --> UC_S2
S --> UC_S3
S --> UC_S4
S --> UC_S5
S --> UC_S6
S --> UC_S7
S --> UC_S8

C --> UC_LOGIN
C --> UC_C1
C --> UC_C2
C --> UC_C3
C --> UC_C4
C --> UC_C5
C --> UC_C6
C --> UC_C7
C --> UC_C8

A --> UC_LOGIN
A --> UC_A1
A --> UC_A2
A --> UC_A3
A --> UC_A4
A --> UC_A5
A --> UC_A6
A --> UC_A7

@enduml
```

---

## Mermaid Version (alternative rendering)

```mermaid
graph LR
    S(["👤 Student"])
    C(["👤 Guidance Counselor"])
    A(["👤 Administrator"])

    subgraph AUTH["Authentication"]
        UC_LOGIN["Log In"]
    end

    subgraph STUDENT_UC["Student Features"]
        UC_S1["View Dashboard"]
        UC_S2["Request / Book Appointment"]
        UC_S3["View Appointment Status"]
        UC_S4["Cancel Appointment"]
        UC_S5["Receive Notifications"]
        UC_S6["Access Mental Health Corner"]
        UC_S7["View Announcements & Events"]
        UC_S8["Submit Feedback"]
    end

    subgraph COUNSELOR_UC["Guidance Counselor Features"]
        UC_C1["View Counselor Dashboard"]
        UC_C2["Manage Appointment Requests"]
        UC_C3["Accept / Reschedule / Refer Appointments"]
        UC_C4["View Student Records"]
        UC_C5["Record Session Notes"]
        UC_C6["Monitor Follow-Through Cases"]
        UC_C7["Manage / Upload Resources"]
        UC_C8["Receive Notifications"]
    end

    subgraph ADMIN_UC["Administrator Features"]
        UC_A1["Manage User Accounts"]
        UC_A2["Manage Resources"]
        UC_A3["Manage Announcements"]
        UC_A4["Manage Events"]
        UC_A5["View Feedback"]
        UC_A6["View Administrative Logs"]
        UC_A7["Monitor System Activity"]
    end

    S --> UC_LOGIN
    S --> UC_S1
    S --> UC_S2
    S --> UC_S3
    S --> UC_S4
    S --> UC_S5
    S --> UC_S6
    S --> UC_S7
    S --> UC_S8

    C --> UC_LOGIN
    C --> UC_C1
    C --> UC_C2
    C --> UC_C3
    C --> UC_C4
    C --> UC_C5
    C --> UC_C6
    C --> UC_C7
    C --> UC_C8

    A --> UC_LOGIN
    A --> UC_A1
    A --> UC_A2
    A --> UC_A3
    A --> UC_A4
    A --> UC_A5
    A --> UC_A6
    A --> UC_A7
```
