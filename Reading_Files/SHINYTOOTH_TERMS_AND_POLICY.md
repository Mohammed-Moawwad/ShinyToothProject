# ShinyTooth Platform
## Complete Terms, Policies & Rules

---

## Table of Contents

### Website Overview
1. [Platform Overview](#platform-overview)
2. [Core Services](#core-services)

### User Management 
3. [User Accounts](#user-accounts)
4. [Privacy & Data](#privacy--data)

### Booking & Appointments
5. [General Booking Terms](#general-booking-terms)
6. [Appointment Management](#appointment-management)
7. [Booking Block System](#booking-block-system)

### Doctor Subscription System
8. [Subscription Overview](#subscription-overview)
9. [Subscription States](#subscription-states)
10. [Eligibility & Enrollment](#eligibility--enrollment)
11. [Treatment Plan](#treatment-plan)
12. [Idle Subscription Management](#idle-subscription-management)
13. [Rating & Bonus System](#rating--bonus-system)
14. [Cancellation & Doctor Switch](#cancellation--doctor-switch)
15. [Subscription UI/UX](#subscription-ui--ux)

### Administration
16. [Admin Controls](#admin-controls)

---

## Platform Overview

### About ShinyTooth
ShinyTooth is a comprehensive dental care management platform connecting patients with qualified dentists. The platform provides flexible booking options and premium subscription plans for personalized dental care.

### Platform Goals
- ✅ Connect patients with qualified, specialized dentists
- ✅ Streamline dental appointment booking and management
- ✅ Provide flexible subscription plans for dedicated care
- ✅ Maintain appointment attendance accountability
- ✅ Ensure quality care through rating and bonus systems
- ✅ Enable flexible subscription management (pause, cancel, switch)

### Supported User Types
- **Patients** — Individuals seeking dental care services
- **Dentists** — Licensed dental professionals providing services
- **Admin Users** — Platform administrators managing policies and disputes

---

## Core Services

### Available Services
ShinyTooth offers a range of dental services including:
- Consultations (Free 5-minute initial consultations available)
- General checkups and cleanings
- Root canals and endodontic procedures
- Cosmetic dentistry procedures
- Specialized treatments based on dentist expertise

### Service Delivery
- **One-time booking** — Book individual appointments with any dentist
- **Subscription plans** — Subscribe to a dentist for personalized treatment plans
- **Follow-up care** — Flexible scheduling for multi-visit treatments

---

## User Accounts

### Account Creation
- All users (patients and dentists) must create accounts
- Accounts require: Email, password, basic profile information
- Email verification: Required before account activation

### User Roles
| Role | Access | Capabilities |
|------|--------|--------------|
| **Patient** | Limited | Book appointments, subscribe to doctors, rate experiences |
| **Dentist** | Full provider | Create treatment plans, manage subscriptions, set availability |
| **Admin** | System-wide | Manage users, approve/reject actions, monitor disputes |

### Account Security
- Passwords must be securely hashed (bcrypt recommended)
- Session management: Email-based authentication
- Password recovery: Email-based reset link
- Account deactivation: Available upon user request

---

## Privacy & Data

### Data Collection
- **Patient data** — Name, email, phone, medical history, appointment records
- **Dentist data** — Name, email, credentials, specialization, career description
- **Transaction data** — Booking details, payments, ratings, bonus information

### Data Usage
- Patient appointments used only for scheduling and billing
- Rating information used for quality assurance and doctor profiles
- Billing information used for payment processing
- Contact information used for appointment reminders and communications

### Data Retention
- Active account data: Retained for duration of active account
- Archived/deleted accounts: Retained for 90 days then purged
- Appointment records: Retained for 3 years (legal/audit requirements)

### Third-Party Access
- No third-party data sharing without explicit patient consent
- Payment processing handled securely by authorized payment providers

---

## General Booking Terms

### Booking Availability
- Patients can view dentist availability based on published schedules
- Dentists manage their own availability through admin panel
- Bookings restricted by: Appointment slots, doctor specialization, patient eligibility

### Booking Confirmation
- Booking is confirmed upon submission and admin verification
- Confirmation email sent to both patient and dentist
- Booking reference number provided for tracking

### Booking Restrictions
- **Minimum notice**: 24 hours (dentist can customize)
- **Maximum advance booking**: 90 days ahead
- **Geographic limits**: None (online and in-person available)
- **Blocked patients**: Cannot book if `booking_blocked = true` (See [Booking Block System](#booking-block-system))

### Cancellation Policy
- **By patient**: Cancel up to 24 hours before appointment
  - Cancellations within 24 hours may forfeit deposit
- **By dentist**: Cancellations notified within 24 hours
  - Dentist must reschedule or refund patient
- **Automatic cancellation**: No-shows after 2 hours forfeit appointment

---

## Appointment Management

### Appointment Lifecycle

| Stage | Duration | Details |
|-------|----------|---------|
| **Scheduled** | T-24h to T+0h | Appointment confirmed and awaiting |
| **In-progress** | T+0h to T+duration | Appointment occurring |
| **Completed** | T+duration to T+24h | Service rendered, awaiting attendance marking |
| **Archived** | T+24h onward | Historical record, available for reference |

### Attendance Marking

#### Doctor Responsibility
- Doctor marks attendance status within 24 hours of appointment
- Options: `attended` (true) or `not attended` (false)
- Marking triggers automatic booking block check (see section below)

#### Patient Perspective
- Cannot self-mark attendance
- Receives notification of marked attendance
- Can dispute via support contact (initiates admin review)

### No-Show Handling
- No-show defined as: Appointment time passed without patient arrival
- After 2 hours: Appointment automatically marked as no-show candidate
- Doctor confirms no-show within 24 hours
- No-show counts toward booking block calculation

---

## Booking Block System

### What is Booking Block?
A safety mechanism that automatically prevents patients from booking new appointments if they have poor attendance history.

### Block Conditions
Patient is **automatically blocked** when **EITHER** of these conditions is met:

#### Condition 1: Three Consecutive No-Shows
- Patient misses 3 appointments in a row (marked `attended = false`)
- System auto-blocks on the third no-show

#### Condition 2: Bad Attendance Rate After 6+ Appointments
- Patient has attended **6 or more total appointments**
- **AND** has a no-show rate of **≥1/3** (33% or more)
  - Example: 6 appointments with 2+ no-shows = blocked
  - Example: 9 appointments with 3+ no-shows = blocked

### Auto-Check Mechanism
- Triggered when doctor marks attendance: `AppointmentAttendanceController@markAttendance()`
- System recalculates all conditions after each marking
- Patient blocked status: `patients.booking_blocked = true`

### Block Impact
- Blocked patients **cannot create new appointments**
- Blocking is checked in: `BookingController@submitConfirm()`
- Returns error: "Your account is temporarily blocked due to missed appointments"

### Unblocking
- Only **admin** can unblock via: `POST /admin/patients/{patientId}/unblock`
- Admin reviews patient history and manually unblocks if appropriate
- Admin should provide explanation for manual unblock

### Block Appeal Process
- Patients can contact support to dispute blocks
- Disputes reviewed by admin within 48 hours
- Appeal available if: Documented emergency, verified account issue, or unique circumstances

---

## Subscription Overview

### Purpose
The Subscription System allows patients to subscribe to a specific dentist and receive a personalized treatment plan. Each subscription goes through multiple states and includes quality assurance measures, bonus incentives, and flexible management options.



### Subscription Benefits
- Dedicated doctor-patient relationship
- Personalized treatment plan tailored to patient needs
- Priority scheduling and follow-up care
- Performance-based bonuses for quality care (doctor incentive)
- Flexible pause/resume options via idle status
- Quality assurance through rating system

Every subscription exists in one of 8 states:

| State | Description | Who Initiates | Next Possible States |
|-------|-------------|---------------|---------------------|
| **pending** | Awaiting doctor or admin approval | Patient sends request | active, rejected |
| **active** | Approved and in treatment | Doctor accepts | idle, completed, switched, cancelled |
| **idle** | Paused/inactive subscription | Doctor marks idle or system pauses | active (reactivated) |
| **completed** | All treatment plan items completed | Doctor marks completed | (terminal state) |
| **cancelled** | Patient-initiated cancellation (approved) | Admin approves patient's cancel request | (terminal state) |
| **switched** | Switched to different doctor (approved) | Admin approves patient's switch request | (terminal state) |
| **rejected** | Doctor rejected subscription request | Doctor rejects during pending | (terminal state) |
| **removed** | Patient removed by admin | Admin removes patient | (terminal state) |

### State Transition Flow
```
pending → active → idle ↔ active → completed
        ↘ rejected
        
active → switched (after admin approval)
active → cancelled (after admin approval)
```

---

## Eligibility & Enrollment

### Eligibility Requirements
A patient **MUST** have:
- ✅ Attended **at least one appointment** in the system (marked as `attended = true`)
- ✅ No active booking block status

### Enrollment Process
1. Patient views doctor profile page (`/doctors/{id}`)
2. Patient clicks **"Subscribe Now"** button
3. System checks eligibility:
   - If **eligible**: Submits subscription request to `/subscriptions/request`
   - If **ineligible**: Shows popup: "You need to attend at least one appointment before subscribing"
     - Popup offers: "Book Free Consultation" (`/services/35`) or "No thanks"
4. Doctor reviews pending subscription request
5. Doctor **accepts** → subscription becomes `active`
   - OR Doctor **rejects** → subscription becomes `rejected`

### One Active Subscription Limit
- A patient can have **only ONE active or pending subscription** at a time
- If patient already has active/pending subscription:
  - Cannot create new subscription until current one reaches terminal state
  - Error message: "You already have an active or pending subscription"

---

## Booking Block System

### What is Booking Block?
A safety mechanism that automatically prevents patients from booking new appointments if they have poor attendance history.

### Block Conditions
Patient is **automatically blocked** when **EITHER** of these conditions is met:

#### Condition 1: Three Consecutive No-Shows
- Patient misses 3 appointments in a row (marked `attended = false`)
- System auto-blocks on the third no-show

#### Condition 2: Bad Attendance Rate After 6+ Appointments
- Patient has attended **6 or more total appointments**
- **AND** has a no-show rate of **≥1/3** (33% or more)
  - Example: 6 appointments with 2+ no-shows = blocked
  - Example: 9 appointments with 3+ no-shows = blocked

### Auto-Check Mechanism
- Triggered when doctor marks attendance: `AppointmentAttendanceController@markAttendance()`
- System recalculates all conditions after each marking
- Patient blocked status: `patients.booking_blocked = true`

### Block Impact
- Blocked patients **cannot create new appointments**
- Blocking is checked in: `BookingController@submitConfirm()`
- Returns error: "Your account is temporarily blocked due to missed appointments"

### Unblocking
- Only **admin** can unblock via: `POST /admin/patients/{patientId}/unblock`
- Admin reviews patient history and manually unblocks if appropriate

---

## Treatment Plan

### Plan Creation
- **Who**: Doctor creates the plan
- **When**: After subscription is `active` (optional timing, doctor can create anytime)
- **Where**: Via plan endpoints: `POST /doctor/plan`, `POST /doctor/plan/items`

### Plan Components

#### Main Plan (SubscriptionPlan)
- `subscription_id` — Links to specific subscription
- `total_price` — Sum of all service prices
- `created_by_dentist_id` — Doctor who created plan
- Auto-calculated via: `recalculateTotal()` method

#### Plan Items (SubscriptionPlanItem)
Each item represents one service in the treatment plan:

| Field | Type | Notes |
|-------|------|-------|
| `plan_id` | FK | Links to parent plan |
| `service_id` | FK | The dental service to be provided |
| `assigned_dentist_id` | FK (nullable) | Can differ from primary doctor if specialist needed |
| `order_index` | int | Sequential order (1, 2, 3...) |
| `status` | enum | See status table below |

### Item Status Lifecycle

| Status | Meaning | Who Sets |
|--------|---------|----------|
| `pending` | Not started | Default on creation |
| `in_progress` | Doctor is working on it | Doctor via `PATCH /doctor/items/{itemId}/complete` (partial) |
| `completed` | Service finished | Doctor via `PATCH /doctor/items/{itemId}/complete` (full) |

### Plan Management Operations
- **Create plan**: `POST /doctor/plan`
- **Add item**: `POST /doctor/plan/items`
- **Remove item**: `DELETE /doctor/items/{itemId}`
- **Reorder items**: `PATCH /doctor/items/reorder`
- **Complete item**: `PATCH /doctor/items/{itemId}/complete`

---

## Idle Subscription Management

### What is Idle Status?
`idle` state means the subscription is temporarily paused (no active treatment but not completed).

### How Does a Subscription Become Idle?

#### Option 1: Doctor Marks Idle
- Doctor calls: `POST /doctor/subscriptions/{id}/idle`
- Sets subscription status to `idle`
- Reason: Doctor can specify reason for idle marking

#### Option 2: System Auto-Pauses (Future Enhancement)
- (Currently manual only, but framework exists for automation)

### Reactivating from Idle

#### Option 1: Doctor Manually Reactivates
- Doctor calls: `POST /doctor/subscriptions/{id}/active` (or `reactivate`)
- Sets status back to `active`
- Treatment resumes

#### Option 2: Patient Auto-Reactivates (Automatic)
- Patient books a new appointment with same doctor
- System triggers: `BookingController@submitConfirm()`
- Code auto-reactivates: 
  ```php
  DoctorSubscription::where('patient_id', $patientId)
      ->where('status', 'idle')
      ->update(['status' => 'active']);
  ```
- Subscription automatically becomes `active` again

---

## Rating & Bonus System

### Patient Rating

#### When Can Patient Rate?
- After **all plan items are marked `completed`** by doctor
- Subscription status is `active` or `completed`
- Patient has **not already rated** this subscription

#### Rating Process
1. Patient navigates to `/my-subscription`
2. If eligible: See **"Rate Your Experience"** button
3. Patient selects **1-5 stars** and optional review text
4. Submit to: `POST /subscriptions/{id}/rate`

#### Rating Storage
- Stored in: `SubscriptionRating` table
- Fields:
  - `subscription_id` — Which subscription
  - `patient_id` — Who rated
  - `rating` — 1-5 stars (integer)
  - `review` — Optional text (max 2000 chars)

### Bonus System

#### Bonus Eligibility
Bonus is **automatically calculated and paid** when **ALL** these conditions are met:

1. ✅ Doctor marks subscription as `completed`
   - Via: `POST /doctor/subscriptions/{id}/complete`
2. ✅ Patient has rated the subscription
3. ✅ Patient's rating is **≥ 4 stars**

#### Bonus Amount
```
Bonus = Plan Total Price × 0.05 (5%)

Example:
- Plan total: $1,000
- Bonus: $50
```

#### Auto-Payment
- Bonus is **automatically marked as paid** (`is_paid = true`)
- Set on creation in `DoctorSubscriptionController@markCompleted()`
- No manual approval needed

#### Bonus Tracking
- Stored in: `SubscriptionBonus` table
- Fields:
  - `subscription_id` — Which subscription (unique)
  - `dentist_id` — Which doctor receives bonus
  - `plan_total` — Original plan cost
  - `bonus_amount` — Calculated bonus (5% of plan)
  - `rating` — Patient's rating (for reference)
  - `is_paid` — Boolean (always `true` on creation)

#### Bonus Access
- **Doctor perspective**: Can view bonuses earned via `GET /doctor/subscriptions` (if dashboard exists)
- **Admin perspective**: Can view all bonuses via `GET /admin/subscriptions`
- **Patient perspective**: Cannot directly access bonus info

---

## Cancellation & Doctor Switch

### Patient-Initiated Cancellation

#### Request Process
1. Patient clicks **"Request Cancellation"** on `/my-subscription` page
2. Patient provides reason (required, max 1000 chars)
3. Submit to: `POST /subscriptions/{id}/cancel-request`
4. Sets: `admin_action_status = 'pending_cancel'`
5. Stores: `patient_cancel_reason` (patient's explanation)

#### Admin Review & Decision
- Admin views pending requests via: `GET /admin/subscriptions`
- Admin approves: `POST /admin/subscriptions/{id}/approve-action`
  - Subscription status → `cancelled`
  - `admin_action_status` → `none`
- OR Admin rejects: `POST /admin/subscriptions/{id}/reject-action`
  - Subscription stays `active`
  - `admin_action_status` → `none`

#### Cancellation Rules
- Can only cancel if status is **`active`**
- Cannot cancel from `pending`, `idle`, `completed`, or terminal states
- Once cancelled (terminal state), cannot be reactivated

---

### Patient-Initiated Doctor Switch

#### Request Process
1. Patient clicks **"Switch Doctor"** on `/my-subscription` page
2. Patient specifies: **New dentist ID**
3. Patient provides reason (required, max 1000 chars)
4. Submit to: `POST /subscriptions/{id}/switch-request`
5. Sets:
   - `admin_action_status = 'pending_switch'`
   - `switch_to_dentist_id = {new dentist}`
   - `patient_switch_reason = {reason text}`

#### Validation
- Cannot switch to the **same doctor** (already subscribed to them)
- Can only switch if status is **`active`**

#### Admin Review & Decision

**Option 1: Admin Approves Switch**
- `POST /admin/subscriptions/{id}/approve-action`
- Creates **new subscription** with:
  - `patient_id` = same
  - `dentist_id` = new doctor
  - `status = 'pending'` (new request to new doctor)
- Old subscription:
  - `status` → `switched` (terminal state)
  - `admin_action_status` → `none`
- New doctor receives pending subscription request to review

**Option 2: Admin Rejects Switch**
- `POST /admin/subscriptions/{id}/reject-action`
- Subscription stays `active` with current doctor
- `admin_action_status` → `none`

#### Switch Rules
- Cannot switch from `pending`, `idle`, `completed`, or terminal states
- Original subscription becomes terminal (`switched`)
- New subscription starts in `pending` state (new doctor reviews)

---

## Subscription UI/UX

### Subscribe Button Behavior

#### Button Visibility
- **Always visible** and **always green/enabled**
- Location: Doctor profile page (`/doctors/{id}`) sidebar
- Never disabled, always clickable

#### On Click Behavior
1. System checks patient eligibility
2. If **eligible**: Direct subscription request submission
3. If **ineligible**: Show modal popup with:
   - Icon: ⚠️ Warning/Alert
   - Title: "Almost There!"
   - Message: "You need to attend at least one appointment before subscribing to a doctor. Book a free 5-minute consultation to get started!"
   - Button 1: "Book Free Consultation" → Links to `/services/35`
   - Button 2: "No thanks" → Closes popup

### Navigate Away from Ineligible Popup
- Click outside popup (overlay) → closes
- Click "No thanks" button → closes
- Smooth fade animations on open/close

### Subscription Status Badges
Displayed on `/my-subscription` page:

| Status | Badge Color | Icon | Text |
|--------|-------------|------|------|
| `active` | Green (#d4f5e4) | ✓ | Active |
| `pending` | Orange (#fff4e5) | ⏳ | Pending |
| `idle` | Gray (#e9ecef) | ⏸ | Idle |
| `completed` | Teal (#e6f5f3) | 🏆 | Completed |

### Treatment Plan Progress Display
- Progress bar showing: `completed_items / total_items`
- Example: "3/5" with visual bar
- Each item shows status badge:
  - `pending` — Gray "PENDING"
  - `in_progress` — Blue "IN PROGRESS"
  - `completed` — Green checkmark "COMPLETED"

---

## Admin Controls

### Admin Subscription Management Routes

| Route | Method | Purpose |
|-------|--------|---------|
| `/admin/subscriptions` | GET | View all subscriptions (JSON stub) |
| `/admin/subscriptions/{id}/approve-action` | POST | Approve cancel/switch request |
| `/admin/subscriptions/{id}/reject-action` | POST | Reject cancel/switch request |
| `/admin/subscriptions/{id}/remove` | POST | Manually remove patient from subscription |
| `/admin/patients/{patientId}/unblock` | POST | Unblock patient from booking restrictions |

### Admin Actions

#### Approve Action
- Processes pending cancel or switch requests
- If **cancel**: Sets status to `cancelled`
- If **switch**: Creates new pending subscription + marks old as `switched`

#### Reject Action
- Denies patient's cancel or switch request
- Subscription returns to `active` state
- Patient remains subscribed to current doctor

#### Remove Patient
- Force-removes patient from subscription
- Sets status to `removed` (terminal state)
- Reason can be stored for audit trail

#### Unblock Patient
- Manually resets `patients.booking_blocked = false`
- Allows patient to book appointments again
- Typically done after reviewing attendance

---

## Summary Table: Key Workflows

### New Subscription Workflow
```
1. Patient visits doctor profile page
2. Patient has attended ≥1 appointment
3. Patient clicks "Subscribe Now"
4. System creates subscription in 'pending' state
5. Doctor reviews request
6. Doctor accepts → 'active' | rejects → 'rejected'
```

### Treatment Workflow (Active Subscription)
```
1. Doctor creates personalized treatment plan
2. Plan contains multiple services with order_index
3. Patient sees plan progress on /my-subscription
4. Doctor marks items as in_progress/completed
5. Patient rates after all items complete
6. If rating ≥4: Bonus automatically calculated & paid
```

### Pause/Resume Workflow (Idle)
```
1. Doctor marks subscription 'idle' (pause)
2. Subscription status changes to 'idle'
3. Doctor can manually 'reactivate' OR
4. Patient books appointment → auto-reactivates
```

### Cancellation Workflow
```
1. Patient requests cancellation with reason
2. Subscription enters 'pending_cancel' (admin review)
3. Admin approves → status becomes 'cancelled'
   OR rejects → stays 'active'
```

### Doctor Switch Workflow
```
1. Patient requests switch with new doctor ID & reason
2. Subscription enters 'pending_switch' (admin review)
3. Admin approves:
   - New subscription created (pending with new doctor)
   - Old subscription marked 'switched' (terminal)
   OR rejects → stays 'active' with current doctor
```

---

## Booking Block Workflow

```
Doctor marks appointment 'not attended' (no-show)
     ↓
System checks: CheckBookingBlock()
     ↓
Check Condition 1: Count consecutive no-shows
  - If 3 consecutive → BLOCK
     ↓
Check Condition 2: Overall rate after 6+ appointments
  - If no_shows ≥ 1/3 → BLOCK
     ↓
If blocked: patients.booking_blocked = true
     ↓
Next booking attempt → ERROR: "Account blocked"
     ↓
Admin manual review → unblock if appropriate
```

---

## Key Entities & Relationships

### Core Tables
- **doctor_subscriptions** — Main subscription records
- **subscription_plans** — Treatment plans (1:1 with subscription)
- **subscription_plan_items** — Individual services in plan (1:many)
- **subscription_ratings** — Patient ratings (1:1 with subscription)
- **subscription_bonuses** — Bonus calculations (1:1 with subscription)
- **appointments** — Has `attended` boolean column
- **patients** — Has `booking_blocked` boolean column
- **dentists** — Updated with `career_description` text column

### Key Relationships
- Subscription → Patient (many:one)
- Subscription → Dentist (many:one)
- Subscription → SubscriptionPlan (one:one)
- SubscriptionPlan → SubscriptionPlanItem (one:many)
- SubscriptionPlanItem → Service (many:one)
- SubscriptionPlanItem → Dentist (many:one, for assigned specialist)
- Subscription → SubscriptionRating (one:one)
- Subscription → SubscriptionBonus (one:one)

---

## Implementation Timeline

### Phase 1: Backend (COMPLETE ✓)
- ✅ Migrations: 6 new tables
- ✅ Models: 5 new models
- ✅ Controllers: 6 controllers
- ✅ Routes: 98 routes registered
- ✅ Booking block logic
- ✅ Bonus auto-calculation

### Phase 2: Patient Views (COMPLETE ✓)
- ✅ `/doctors` — Listing page
- ✅ `/doctors/{id}` — Profile page
- ✅ `/my-subscription` — Patient subscription view

### Phase 3: Doctor Dashboard (TODO)
- Dashboard to view pending requests
- Plan creation interface
- Appointment marking interface
- Bonus tracking view

### Phase 4: Admin Dashboard (TODO)
- Subscription management
- Approval workflows
- Patient blocking/unblocking
- Bonus tracking

---

## Amendment History

| Date | Change | Version |
|------|--------|---------|
| 2026-03-20 | Initial master terms & policies document created | 1.0 |
| 2026-03-20 | Added general booking, appointment, and user account sections | 1.1 |

---

## Document Notes

### Purpose of This Document
This is the **master terms and policies document** for the ShinyTooth platform. It serves as:
- A reference guide for all platform rules and policies
- A development checklist for implementing features
- A legal/compliance reference for terms and conditions
- A growing document that will be updated as new features are added

### How to Use This Document
1. **For Development**: Reference this document when building new features
2. **For Updates**: Add new sections as you implement new features
3. **For Clarification**: When rules need to be clarified, update this document first
4. **For Compliance**: Ensure all platform behavior aligns with policies in this document

### Sections to Add (Future)
- [ ] Payment & Billing Terms
- [ ] Refund Policy
- [ ] Dispute Resolution
- [ ] User Rights & Responsibilities
- [ ] Accessibility Standards
- [ ] Service Level Agreement (SLA)
- [ ] Intellectual Property Rights
- [ ] Terms of Service (Legal)
- [ ] Cookie & Tracking Policy
- [ ] Two-Factor Authentication
- [ ] Doctor Credentials Verification
- [ ] Patient Medical Records Management
- [ ] Insurance Integration (if applicable)

---

**Last Updated:** March 20, 2026  
**Current Version:** 1.1  
**Status:** ACTIVE & GROWING  
**Maintenance:** Add new sections as features are developed
