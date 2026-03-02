# Technical Architecture & System Design

## Project Overview

**ShinyTooth Dental System** is a comprehensive Dental Clinic Management System built with a modern web stack.

---

## Technology Stack

### Frontend
- **abc**
- **Framework:** HTML5, CSS3, Bootstrap 5
- **Interactivity:** JavaScript (Vanilla/ES6+)
- **Build tool:** Webpack (via Laravel Mix/Vite)
- **State Management:** LocalStorage + fetch API
- **Charts:** Chart.js (admin dashboards)

### Backend

- **Framework:** Laravel 11
- **Language:** PHP 8.2+
- **API:** RESTful API with JWT authentication
- **ORM:** Eloquent
- **Validation:** Laravel Form Requests
- **Queue:** Laravel Queue (for async emails)

### Database

- **DBMS:** MySQL 8.0+
- **Hosted on:** Railway.app
- **Migration Tool:** Laravel Migrations
- **Seeded Data:** Laravel Seeders

### Deployment

- **Platform:** Railway.app
- **Server:** Node.js server
- **SSL:** HTTPS with Railway SSL
- **Email Service:** SMTP (Mailtrap for dev, production SMTP)

### Version Control

- **Git Repository:** GitHub
- **Workflow:** Git Flow (main, develop, feature branches)

---

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                      Frontend (Browser)                     │
├─────────────────────────────────────────────────────────────┤
│ HTML5 + CSS3 + Bootstrap | JavaScript | Responsive Design  │
│                                                             │
│ Pages: Home | Auth | Services | Doctors | Dashboards       │
└────────────────────────┬────────────────────────────────────┘
                         │ HTTP/HTTPS
                         │
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Backend (API)                    │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Routes (web.php / api.php)                                │
│         ↓                                                   │
│  Middleware (Auth, RBAC) → Controllers                     │
│         ↓                                                   │
│  Requests (Validation) → Services (Business Logic)        │
│         ↓                                                   │
│  Models (Eloquent ORM) ← ← ← ← ← ← ← ← → ← ← ← ← ← ← ←  │
│                                │                            │
│                                ↓                            │
└────────────────────────────────┼────────────────────────────┘
                                 │
                    ┌────────────┴────────────┐
                    │                         │
         ┌──────────▼──────────┐   ┌───────────▼────────┐
         │   MySQL Database    │   │  Email Service     │
         │   (Railway.app)     │   │  (Mailtrap/SMTP)   │
         └─────────────────────┘   └────────────────────┘
```

---

## Database Schema Overview

### User Management Tables

```
users (Base table)
├── Core fields: id, name, email, password, phone, role
├── Profile: profile_image, is_active
├── Timestamps: created_at, updated_at
└── Relationships: hasOne(Patient) | hasOne(Dentist)

patients (Extends users)
├── user_id → users.id
├── Health info: date_of_birth, gender, blood_type
├── Medical: medical_history, allergies
├── Contact: address, city, postal_code
└── Relationships: hasMany(Appointment), hasMany(Rating)

dentists (Extends users)
├── user_id → users.id
├── Qualifications: specialty, qualification, license_number
├── Stats: experience_years, average_rating, total_ratings
├── Pricing: consultation_fee
└── Relationships: belongsToMany(Service), hasMany(Appointment)
```

### Appointment Management Tables

```
appointments
├── id, patient_id, dentist_id, service_id
├── appointment_date, appointment_time, status
├── notes, created_at, updated_at
└── Relationships: hasOne(Rating), hasOne(Recipe), hasOne(Finance)

dentist_availabilities
├── dentist_id, day_of_week (0-6)
├── start_time, end_time, is_active
└── Used for: Generating available time slots

services
├── id, name, description, price
├── estimated_duration_minutes, icon_image
└── Relationships: belongsToMany(Dentist)

dentist_services (Pivot)
├── dentist_id, service_id
├── Tracks: Which dentist offers which service
```

### Additional Tables

```
ratings_feedback
├── appointment_id, patient_id, dentist_id
├── rating (1-5), feedback_text
├── created_at
└── Purpose: Patient reviews for dentists

recipes
├── appointment_id, dentist_id, patient_id
├── prescription_details (JSON)
├── medicines, instructions
└── Generated: After completed appointment

finances
├── appointment_id, patient_id, amount
├── status (pending/paid/failed), payment_method
├── transaction_id, paid_at
└── Purpose: Payment tracking
```

---

## Authentication & Authorization

### Authentication Flow

```
User Login
    ↓
POST /api/auth/login
    ↓
Verify credentials
    ↓
Issue JWT Token
    ↓
Store token (localStorage)
    ↓
Include in Authorization header for API calls
```

### JWT Token Structure

```
Header: {
  "alg": "HS256",
  "typ": "JWT"
}

Payload: {
  "sub": user_id,
  "email": user_email,
  "role": user_role,
  "iat": issued_at,
  "exp": expiration_time
}

Signature: HMACSHA256(header.payload, secret)
```

### Role-Based Access Control (RBAC)

```
Patient
├── Can: Book appointments, view own appointments, rate doctors
│         view doctors, edit own profile, view own records
└── Cannot: Access admin panel, view other patients' data

Dentist
├── Can: View appointments, manage schedule, write prescriptions
│         view assigned patients, generate reports
└── Cannot: Access admin panel, manage finances (except reports)

Admin
├── Can: Manage all users, manage appointments, manage finances
│         view all ratings, manage services, generate system reports
└── Cannot: Nothing (full access)
```

### Protected Routes

```php
// Patient routes
Route::middleware(['auth:sanctum', 'role:patient'])->group(function () {
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments', [AppointmentController::class, 'userAppointments']);
});

// Dentist routes
Route::middleware(['auth:sanctum', 'role:dentist'])->group(function () {
    Route::get('/dentists/appointments', [DentistController::class, 'appointments']);
    Route::post('/recipes', [RecipeController::class, 'store']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::resource('admin/users', UserController::class);
    Route::resource('admin/finances', FinanceController::class);
});
```

---

## API Endpoint Structure

### Base URL

```
Development: http://localhost:8000/api/v1
Production: https://shinytooth-dental.app/api/v1
```

### Endpoint Categories

#### Authentication (Public)

```
POST   /auth/register          Register new user
POST   /auth/login             Login user
POST   /auth/logout            Logout (requires token)
POST   /auth/refresh-token     Refresh JWT token
GET    /auth/me                Get current user
```

#### Appointments (Protected)

```
GET    /appointments           Get user's appointments
POST   /appointments           Create new appointment
GET    /appointments/{id}      Get appointment details
PUT    /appointments/{id}      Update appointment
DELETE /appointments/{id}      Cancel appointment
GET    /appointments/available-slots
```

#### Dentists (Mixed)

```
GET    /dentists               List all dentists (Public)
GET    /dentists/{id}          Get dentist profile (Public)
GET    /dentists/{id}/availability  Get availability (Public)
GET    /dentists/{id}/appointments  Get own appointments (Protected)
GET    /dentists/{id}/patients      Get my patients (Protected)
```

#### Ratings (Mixed)

```
POST   /ratings                Submit rating (Protected - Patient)
GET    /ratings/dentist/{id}   Get dentist ratings (Admin/Public)
DELETE /ratings/{id}           Delete own rating (Protected - Patient)
```

#### Admin (Protected)

```
GET    /admin/dashboard        Dashboard statistics
GET    /admin/users            List all users
POST   /admin/users            Create user
PUT    /admin/users/{id}       Update user
DELETE /admin/users/{id}       Delete user
GET    /admin/finances         Financial data
```

### API Response Format

**Success Response:**

```json
{
  "data": {
    "id": 1,
    "name": "John Appointment",
    "status": "confirmed"
  },
  "message": "Appointment created successfully",
  "success": true
}
```

**Error Response:**

```json
{
  "error": "Unauthorized",
  "message": "You don't have permission to access this resource",
  "status_code": 403,
  "success": false
}
```

**Validation Error Response:**

```json
{
  "errors": {
    "email": ["Email is required", "Email must be valid"],
    "password": ["Password must be at least 8 characters"]
  },
  "message": "Validation failed",
  "status_code": 422,
  "success": false
}
```

---

## File Structure

```
ShinyToothDental/
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Patient.php
│   │   ├── Dentist.php
│   │   ├── Appointment.php
│   │   ├── Service.php
│   │   ├── Rating.php
│   │   ├── Recipe.php
│   │   ├── Finance.php
│   │   └── DentistAvailability.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── AppointmentController.php
│   │   │   │   ├── DentistController.php
│   │   │   │   ├── PatientController.php
│   │   │   │   ├── AdminController.php
│   │   │   │   └── RatingController.php
│   │   │   └── Frontend/
│   │   │       └── PageController.php
│   │   ├── Requests/
│   │   │   ├── StoreAppointmentRequest.php
│   │   │   ├── StoreRatingRequest.php
│   │   │   └── RegisterUserRequest.php
│   │   └── Middleware/
│   │       ├── EnsureUserRole.php
│   │       └── ApiMiddleware.php
│   ├── Services/
│   │   ├── AppointmentService.php
│   │   ├── DentistService.php
│   │   ├── PatientService.php
│   │   ├── EmailService.php
│   │   └── ReportService.php
│   ├── Policies/
│   │   ├── AppointmentPolicy.php
│   │   └── UserPolicy.php
│   └── Traits/
│       └── HasRoles.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_create_users_table.php
│   │   ├── 2024_01_02_create_patients_table.php
│   │   ├── 2024_01_03_create_dentists_table.php
│   │   └── ...
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── DentistSeeder.php
│       └── ServiceSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── pages/
│   │   │   ├── home.blade.php
│   │   │   ├── services.blade.php
│   │   │   ├── doctors.blade.php
│   │   │   └── contact.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   └── components/
│   │       ├── navbar.blade.php
│   │       └── footer.blade.php
│   └── css/
│       └── app.css
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
├── config/
│   ├── app.php
│   ├── database.php
│   └── mail.php
├── .env.example
├── package.json
├── composer.json
└── README.md
```

---

## Data Flow Examples

### Appointment Booking Flow

```
1. User fills form (Service → Dentist → Date/Time)
   ↓
2. Frontend validates input
   ↓
3. Frontend calls API: POST /appointments
   ↓
4. Backend receives request, validates:
   - User is authenticated (JWT token)
   - Appointment date is future
   - Time slot is available
   - Patient exists
   ↓
5. Backend checks dentist availability
   - Compare selected time with dentist_availabilities
   ↓
6. Backend checks for conflicts
   - Query appointments table for same patient/time
   ↓
7. If all valid:
   - Save appointment record
   - Create Finance record (pending)
   - Send confirmation email (async queue)
   - Return success response with appointment ID
   ↓
8. Frontend displays confirmation message
   ↓
9. Frontend redirects to appointment details page
```

### Ratings & Feedback Flow

```
1. Patient completes appointment (status = completed)
   ↓
2. Frontend notification appears: "Rate your appointment"
   ↓
3. Patient fills rating form (1-5 stars + feedback)
   ↓
4. Frontend submits: POST /ratings
   - appointment_id, rating, feedback_text
   ↓
5. Backend validates:
   - Appointment belongs to patient and is completed
   - Rating not already submitted
   - Rating is 1-5
   ↓
6. Backend saves rating record
   ↓
7. Backend updates dentist model:
   - Sum all ratings for this dentist
   - Calculate average
   - Update average_rating and total_ratings fields
   ↓
8. Backend sends notification to dentist:
   - Email or in-app notification
   ↓
9. Admin can view all ratings on dashboard (ratings only visible to admin)
```

### Report Generation Flow

```
1. Admin/Dentist requests report
   - Selects type (Appointments, Financial, Performance)
   - Selects date range
   - Selects format (PDF, CSV, Excel)
   ↓
2. Frontend calls API: GET /reports?type=appointments&start_date=...&format=pdf
   ↓
3. Backend processes request:
   - Verify user has permission
   - Query database for data
   - Format data for report
   ↓
4. Backend generates file:
   - PDF: Use DomPDF library
   - CSV: Generate CSV string
   - Excel: Use Laravel-Excel
   ↓
5. Backend returns file download
   OR sends file to email (async)
   ↓
6. Frontend triggers download
```

---

## Security Considerations

### Password Security

- Hashed with bcrypt (salted)
- Minimum 8 characters required
- Stored as hash in database (never plain text)

### API Security

- JWT tokens with expiration (typically 1 hour)
- HTTPS only in production
- CORS configured for specific domains
- Rate limiting on login attempts
- Input validation on all endpoints
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)

### Database Security

- Connection over TLS
- Strong database password
- Backups performed regularly
- No sensitive data in logs

### Data Privacy

- Patient medical records encrypted
- Ratings visible only to admin
- Users can only access own data
- Delete account functionality

---

## Performance Optimization

### Database

- Indexes on frequently queried columns (email, user_id, status)
- Eager loading with Eloquent (avoid N+1 queries)
- Database connection pooling
- Query caching for static data

### Frontend

- Lazy loading images
- Code splitting
- Minified CSS/JS
- Gzip compression
- Browser caching

### Backend

- Redis caching layer
- Response caching for public data
- Database query optimization
- Async jobs for emails

### Scaling Strategies

- Load balancing (Railway auto-scales)
- Database read replicas if needed
- CDN for static assets
- API rate limiting

---

## Deployment Process

### Development Environment

```
localhost:8000 → Local Laravel server
localhost:3000 → Frontend dev server (npm run dev)
localhost:3306 → Local MySQL
```

### Production Environment

```
https://shinytooth-dental.app → Railway
Database: MySQL on Railway
Email: Production SMTP service
```

### CI/CD Pipeline (GitHub Actions - Optional)

```
Code pushed to GitHub
   ↓
Run tests
   ↓
Build frontend assets
   ↓
Run linting
   ↓
Deploy to Railway (if develop branch)
   ↓
Run migrations (production database)
   ↓
Clear cache
```

---

## Monitoring & Logging

### Logs Location

- Application logs: `storage/logs/laravel.log`
- Server logs: Railway dashboard
- Error tracking: (Optional) Sentry

### Metrics to Monitor

- API response times
- Database query performance
- Error rate
- User registration rate
- Appointment booking rate

### Alerts

- High error rate (> 5%)
- Slow database queries (> 1 second)
- Failed email sends
- Low disk space

---

## Development Guidelines

### Code Quality

- PSR-12 PHP coding standards
- 2 spaces for JavaScript
- Comments for complex logic
- Type hints in PHP 8
- Unit tests for services

### Commit Standards

- Meaningful commit messages
- One feature per commit
- Small, focused commits

### Review Process

- At least 1 code review
- Automated tests must pass
- No merge conflicts
- Documentation updated

---

**This architecture ensures scalability, security, and maintainability for the 8-week development cycle. 🏗️**
