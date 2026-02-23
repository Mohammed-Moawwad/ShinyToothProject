# ShinyTooth Dental System - Development Guide

## Project Overview

**System:** Dental Clinic Management System (ShinyTooth)  
**Technology:** Laravel, MySQL, Bootstrap, JavaScript  
**Duration:** 8 weeks  
**Deployment:** Railway (Frontend + Backend + Database)

---

## Table of Contents

1. [Week 1: Project Setup & Planning](#week-1-project-setup--planning)
2. [Week 2-3: Database & Authentication](#week-2-3-database--authentication)
3. [Week 3-4: Frontend Foundation](#week-3-4-frontend-foundation)
4. [Week 5-6: Core Features & Dashboards](#week-5-6-core-features--dashboards)
5. [Week 7: Advanced Features](#week-7-advanced-features)
6. [Week 8: Testing & Deployment](#week-8-testing--deployment)
7. [Git Workflow & Best Practices](#git-workflow--best-practices)

---

# WEEK 1: Project Setup & Planning

## Overview

Establish the foundation for the entire project. This week is critical for team alignment and preventing rework.

## Deliverables

- [ ] Laravel project created and initialized
- [ ] GitHub repository set up with branching strategy
- [ ] Complete database schema (ER diagram)
- [ ] API endpoint documentation
- [ ] Development environment documented
- [ ] Team setup guide created
- [ ] Initial commits pushed to GitHub

---

## 1.1 Create Laravel Project Structure

### Tasks:

1. **Initialize Laravel project**

   ```bash
   composer create-project laravel/laravel ShinyToothDental
   cd ShinyToothDental
   ```

2. **Install required packages**

   ```bash
   composer require laravel/breeze:latest
   composer require laravel/permission
   php artisan breeze:install blade
   ```

3. **Create folder structure** (in `app/` directory)
   ```
   app/
   ├── Models/
   │   ├── User.php
   │   ├── Patient.php
   │   ├── Dentist.php
   │   ├── Appointment.php
   │   ├── DentistAvailability.php
   │   ├── Service.php
   │   ├── Rating.php
   │   ├── Recipe.php
   │   └── Finance.php
   ├── Http/
   │   ├── Controllers/
   │   │   ├── Auth/
   │   │   ├── Patient/
   │   │   ├── Dentist/
   │   │   ├── Admin/
   │   │   └── Frontend/
   │   ├── Requests/
   │   └── Middleware/
   ├── Services/
   │   ├── AppointmentService.php
   │   ├── DentistService.php
   │   └── FinanceService.php
   └── Traits/
       └── HasRoles.php
   ```

---

## 1.2 GitHub Repository Setup

### Tasks:

1. **Initialize Git repository**
   - Create GitHub repository: `ShinyToothDental`
   - Clone to local machine
   - Copy Laravel project files into the repository

2. **Create `.gitignore`**

   ```
   /vendor/
   node_modules/
   .env
   .env.local
   .env.*.local
   /storage/
   /bootstrap/cache/
   .vscode/
   .idea/
   *.log
   /public/hot
   /public/storage
   npm-debug.log*
   yarn-error.log*
   .DS_Store
   ```

3. **Set up branching strategy**
   - `main` - Production branch (only merged releases)
   - `develop` - Staging branch (development integration)
   - `feature/*` - Feature branches (from develop)
   - `bugfix/*` - Bug fix branches (from develop)

4. **Create branch protection rules**
   - `main`: Require PR reviews, require status checks
   - `develop`: Require PR reviews

5. **Initial commit**
   ```bash
   git add .
   git commit -m "initial: setup Laravel project structure"
   git push -u origin main
   ```

---

## 1.3 Database Schema Design

### Create Entity-Relationship Diagram (ERD)

**Tables and Relationships:**

```
users (Base table for all roles)
├── id (PK)
├── name
├── email (UNIQUE)
├── password
├── phone
├── role (enum: patient, dentist, admin)
├── profile_image
├── is_active
├── created_at / updated_at

patients
├── id (PK)
├── user_id (FK → users)
├── date_of_birth
├── gender
├── blood_type
├── medical_history (TEXT)
├── allergies (TEXT)
├── address
├── city
├── postal_code

dentists
├── id (PK)
├── user_id (FK → users)
├── specialty
├── qualification (e.g., DDS, DMD)
├── experience_years
├── bio
├── license_number
├── average_rating (DECIMAL)
├── total_ratings (INT)
├── consultation_fee

services
├── id (PK)
├── name
├── description
├── icon_image
├── price
├── estimated_duration_minutes
├── created_at / updated_at

dentist_services (Pivot table)
├── id (PK)
├── dentist_id (FK → dentists)
├── service_id (FK → services)

dentist_availabilities
├── id (PK)
├── dentist_id (FK → dentists)
├── day_of_week (0-6, where 0=Sunday)
├── start_time (TIME)
├── end_time (TIME)
├── is_active

appointments
├── id (PK)
├── patient_id (FK → patients)
├── dentist_id (FK → dentists)
├── service_id (FK → services)
├── appointment_date (DATE)
├── appointment_time (TIME)
├── status (enum: pending, confirmed, completed, cancelled)
├── notes
├── created_at / updated_at

ratings_feedback
├── id (PK)
├── appointment_id (FK → appointments)
├── patient_id (FK → patients)
├── dentist_id (FK → dentists)
├── rating (1-5 stars)
├── feedback_text
├── created_at

recipes
├── id (PK)
├── appointment_id (FK → appointments)
├── dentist_id (FK → dentists)
├── patient_id (FK → patients)
├── prescription_details (TEXT)
├── medicines (JSON)
├── instructions (TEXT)
├── created_at / updated_at

finances
├── id (PK)
├── appointment_id (FK → appointments)
├── patient_id (FK → patients)
├── amount (DECIMAL)
├── status (enum: pending, paid, failed)
├── payment_method (enum: credit_card, bank_transfer, cash)
├── transaction_id
├── paid_at (NULLABLE)
├── created_at / updated_at
```

### Tasks:

1. **Create database diagram** (use Lucidchart, Draw.io, or similar)
2. **Document all relationships** - primary keys, foreign keys
3. **Store diagram** in `docs/` folder in repository
4. **Share with team** for feedback before migrations

---

## 1.4 API Endpoint Documentation

### Create comprehensive endpoint list

**Authentication Endpoints**

```
POST   /api/auth/register           - Register new user
POST   /api/auth/login              - Login user
POST   /api/auth/logout             - Logout user
POST   /api/auth/refresh-token      - Refresh JWT token
GET    /api/auth/me                 - Get current user info
```

**Patient Endpoints**

```
GET    /api/patients/{id}           - Get patient details
PUT    /api/patients/{id}           - Update patient profile
GET    /api/patients/{id}/appointments - Get patient's appointments
```

**Appointment Endpoints**

```
GET    /api/appointments            - Get all appointments (filtered by role)
POST   /api/appointments            - Create new appointment
GET    /api/appointments/{id}       - Get appointment details
PUT    /api/appointments/{id}       - Update appointment
DELETE /api/appointments/{id}       - Cancel appointment
GET    /api/appointments/available-slots - Get available slots
```

**Dentist Endpoints**

```
GET    /api/dentists                - Get all dentists
GET    /api/dentists/{id}           - Get dentist details
GET    /api/dentists/{id}/availability - Get dentist availability
GET    /api/dentists/{id}/appointments - Get dentist's appointments
GET    /api/dentists/{id}/ratings   - Get dentist ratings (admin/public view)
```

**Services Endpoints**

```
GET    /api/services                - Get all services
GET    /api/services/{id}           - Get service details
```

**Ratings & Feedback Endpoints**

```
POST   /api/ratings                 - Submit rating and feedback
GET    /api/ratings/dentist/{id}    - Get dentist ratings (admin only)
```

**Admin Endpoints**

```
GET    /api/admin/dashboard-stats   - Get dashboard statistics
GET    /api/admin/users             - Get all users
GET    /api/admin/finances          - Get financial data
POST   /api/admin/dentists          - Create/manage dentists
```

### Tasks:

1. **Create detailed API documentation** (`docs/API_DOCUMENTATION.md`)
2. **Include request/response examples** for each endpoint
3. **Document authentication method** (JWT tokens)
4. **Define error responses**
5. **Version API** (v1)

---

## 1.5 Environment Configuration

### Create `.env` file template

```
APP_NAME="ShinyTooth Dental"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=shinytooth_dental
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=

JWT_SECRET=

RAILWAY_DATABASE_URL=

FILAMENT_PASSWORD_RESET_LINK_LIFETIME=15
```

### Tasks:

1. **Create `.env.example`** in repository
2. **Document all environment variables**
3. **Create Railway database** and get connection string
4. **Ensure each team member has `.env` configured locally**

---

## 1.6 Team Documentation

### Create `README.md` with:

**Project Setup Instructions**

```bash
# Clone repository
git clone [repo-url]
cd ShinyToothDental

# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
npm run dev

# Access at http://localhost:8000
```

**Branching Workflow**

- Always create feature branches from `develop`
- Branch naming: `feature/appointment-system`, `bugfix/auth-issue`
- Commit messages: `feat: add appointment booking`, `fix: validation error`

**Code Style Guidelines**

- Use PSR-12 for PHP
- 2 spaces for JavaScript/CSS
- Add comments for complex logic
- Use meaningful variable names

**Database Migrations**

- Create migration files in `database/migrations/`
- Always reverse migrations (rollback)
- Format: `YYYY_MM_DD_HHMMSS_create_table_name.php`

**Pull Request Process**

- Create PR from feature branch to `develop`
- Add description of changes
- Link related issues
- Wait for code review approval
- Delete branch after merge

### Tasks:

1. **Create comprehensive README.md**
2. **Create CONTRIBUTING.md** with guidelines
3. **Create CODE_STYLE.md** with standards
4. **Create DEPLOYMENT.md** with Railway setup
5. **Commit all documentation**

---

## 1.7 Development Environment Setup

### Tasks:

1. **Ensure all team members have:**
   - PHP 8.2+
   - Composer
   - Node.js 18+
   - npm or yarn
   - MySQL/MariaDB locally OR access to Railway database
   - Git configured

2. **Create local database**

   ```bash
   mysql -u root -p
   CREATE DATABASE shinytooth_dental;
   ```

3. **Run initial migrations**

   ```bash
   php artisan migrate
   ```

4. **Seed sample data**
   ```bash
   php artisan db:seed
   ```

---

## Week 1 Checklist

- [ ] Laravel project created and structure organized
- [ ] GitHub repository initialized with branches
- [ ] `.gitignore` configured properly
- [ ] Database schema designed and documented
- [ ] API endpoints documented
- [ ] `.env.example` created with all variables
- [ ] README and contributing guidelines written
- [ ] All team members can clone and run project locally
- [ ] Initial commit pushed to GitHub
- [ ] Database created and migrations tested
- [ ] README reviewed by team

---

# WEEK 2-3: Database & Authentication

## Overview

Build the backend foundation: database migrations, models, and authentication system with role-based access control.

## Deliverables

- [ ] All database migrations created and tested
- [ ] Models created with relationships
- [ ] Authentication system working
- [ ] Role-based access control (RBAC) implemented
- [ ] User registration for all roles
- [ ] Login system with JWT tokens
- [ ] Middleware for route protection
- [ ] All API auth endpoints tested

---

## 2.1 Create Database Migrations

### Tasks:

1. **Create users table migration**

   ```bash
   php artisan make:migration create_users_table
   ```

   - Include: id, name, email, password, phone, role, profile_image, is_active, timestamps

2. **Create patients table migration**

   ```bash
   php artisan make:migration create_patients_table
   ```

   - Include: id, user_id, date_of_birth, gender, blood_type, medical_history, allergies, address, city, postal_code

3. **Create dentists table migration**

   ```bash
   php artisan make:migration create_dentists_table
   ```

   - Include: id, user_id, specialty, qualification, experience_years, bio, license_number, average_rating, total_ratings, consultation_fee

4. **Create services table migration**

   ```bash
   php artisan make:migration create_services_table
   ```

   - Include: id, name, description, icon_image, price, estimated_duration_minutes, timestamps

5. **Create pivot tables**
   - `dentist_services` (dentist_id, service_id)
   - `dentist_availabilities`
   - `appointments`
   - `ratings_feedback`
   - `recipes`
   - `finances`

6. **Test all migrations**
   ```bash
   php artisan migrate
   php artisan migrate:rollback
   php artisan migrate
   ```

### Important Notes:

- Use proper foreign keys with onDelete cascade
- Add indexes on frequently queried columns
- Use appropriate data types (email, date, time, decimal)
- Add nullable fields where appropriate

---

## 2.2 Create Eloquent Models

### Tasks:

1. **Create User model** (with relationships)

   ```bash
   php artisan make:model User
   ```

   - Relationships: hasOne(Patient), hasOne(Dentist), hasMany(Appointment)
   - Methods: isPatient(), isDentist(), isAdmin()

2. **Create Patient model**
   - Relationships: belongsTo(User), hasMany(Appointment), hasMany(Rating)

3. **Create Dentist model**
   - Relationships: belongsTo(User), belongsToMany(Service), hasMany(Appointment), hasMany(DentistAvailability), hasMany(Rating)

4. **Create Appointment model**
   - Relationships: belongsTo(Patient), belongsTo(Dentist), belongsTo(Service), hasOne(Rating), hasOne(Recipe), hasOne(Finance)

5. **Create related models**
   - Service, DentistAvailability, Rating, Recipe, Finance

6. **Test relationships**
   - Verify all relationships work correctly
   - Test eager loading

---

## 2.3 User Authentication

### Tasks:

1. **Set up Laravel Sanctum** (for API authentication)

   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

2. **Create AuthController**
   - `register()` - Register new user (patient/dentist)
   - `login()` - Authenticate and issue token
   - `logout()` - Revoke token
   - `me()` - Get current user
   - `refreshToken()` - Refresh authentication token

3. **Implement registration** with role selection
   - Validate email uniqueness
   - Hash password
   - Create user and corresponding patient/dentist record
   - Return authentication token

4. **Implement login**
   - Validate credentials
   - Check if user is active
   - Return token with user data

5. **Create authentication routes** in `routes/api.php`
   ```php
   Route::post('/auth/register', [AuthController::class, 'register']);
   Route::post('/auth/login', [AuthController::class, 'login']);
   Route::middleware('auth:sanctum')->group(function () {
       Route::post('/auth/logout', [AuthController::class, 'logout']);
       Route::get('/auth/me', [AuthController::class, 'me']);
   });
   ```

### Security Requirements:

- Password should be minimum 8 characters
- Validate email format
- Hash passwords using bcrypt
- Use HTTPS in production
- Implement rate limiting on login attempts
- Return appropriate error messages (generic for security)

---

## 2.4 Role-Based Access Control (RBAC)

### Tasks:

1. **Create roles**
   - Patient
   - Dentist
   - Admin

2. **Create middleware** for role checking

   ```bash
   php artisan make:middleware EnsureUserRole
   ```

   - Check user role
   - Throw 403 if unauthorized

3. **Create authorization policies**

   ```bash
   php artisan make:policy AppointmentPolicy
   ```

   - Patients can view/edit only their appointments
   - Dentists can view/edit their appointments and patients
   - Admins can view/edit everything

4. **Protect routes by role**

   ```php
   Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
       // Admin routes
   });

   Route::middleware(['auth:sanctum', 'role:dentist'])->group(function () {
       // Dentist routes
   });

   Route::middleware(['auth:sanctum', 'role:patient'])->group(function () {
       // Patient routes
   });
   ```

---

## 2.5 Seeders for Development Data

### Tasks:

1. **Create AdminSeeder**
   - Create 1 admin user (email: admin@shinytooth.com)

2. **Create DentistSeeder**
   - Create 5 sample dentists with different specialties
   - Add availabilities, services

3. **Create PatientSeeder**
   - Create 10 sample patients

4. **Create ServiceSeeder**
   - Add all services (Cleaning, Root Canal, Extraction, etc.)

5. **Create AppointmentSeeder**
   - Create sample appointments for testing

6. **Run seeders**
   ```bash
   php artisan db:seed
   ```

---

## 2.6 Testing Authentication

### Tasks:

1. **Test user registration**
   - Register as patient
   - Register as dentist
   - Verify email is unique
   - Verify password validation

2. **Test user login**
   - Login with valid credentials
   - Get authentication token
   - Login with invalid credentials (should fail)

3. **Test token validation**
   - Access protected route with token
   - Access protected route without token (should fail)
   - Access protected route with invalid token (should fail)

4. **Test role authorization**
   - Patient accessing admin route (should fail)
   - Admin accessing patient route (should succeed)
   - Dentist accessing their own data (should succeed)

5. **Create Postman/Insomnia collection**
   - Document all auth endpoints
   - Include sample requests and responses

---

## Week 2-3 Checklist

- [ ] All migrations created and tested
- [ ] All models created with relationships
- [ ] User authentication implemented
- [ ] Role-based access control working
- [ ] Registration endpoints working for all roles
- [ ] Login endpoint returning tokens
- [ ] Route middleware protecting endpoints
- [ ] Sample data seeded
- [ ] Authentication tested with tools like Postman
- [ ] All API auth tests passing
- [ ] Code reviewed and merged to develop

---

# WEEK 3-4: Frontend Foundation

## Overview

Build the responsive frontend using Bootstrap and vanilla JavaScript. Create reusable components and structure.

## Deliverables

- [ ] Homepage with all main sections
- [ ] Authentication pages (login/register)
- [ ] Responsive navigation bar
- [ ] Bootstrap grid system implemented
- [ ] Reusable components (cards, modals, forms)
- [ ] JavaScript for interactivity
- [ ] Mobile-responsive on all screen sizes
- [ ] CSS organization and theming

---

## 3.1 Project Structure

### Tasks:

1. **Organize frontend files**

   ```
   resources/
   ├── views/
   │   ├── layouts/ (Master layouts)
   │   │   └── app.blade.php
   │   ├── pages/ (Main pages)
   │   │   ├── home.blade.php
   │   │   ├── about.blade.php
   │   │   ├── services.blade.php
   │   │   ├── contact.blade.php
   │   │   ├── doctors.blade.php
   │   │   └── book-appointment.blade.php
   │   ├── auth/ (Authentication)
   │   │   ├── login.blade.php
   │   │   ├── register.blade.php
   │   │   └── forgot-password.blade.php
   │   └── components/ (Reusable)
   │       ├── navbar.blade.php
   │       ├── footer.blade.php
   │       ├── cards.blade.php
   │       └── modals.blade.php
   ├── css/
   │   ├── bootstrap.css (or cdn)
   │   ├── app.css (custom styles)
   │   └── variables.css (colors, fonts)
   └── js/
       ├── app.js (main)
       ├── auth.js (auth logic)
       └── components.js (interactive components)
   ```

2. **Set up Bootstrap**
   - Use CDN or npm install
   - Configure Bootstrap colors and typography

3. **Set up CSS variables**
   ```css
   :root {
     --primary-color: #1e40af;
     --secondary-color: #10b981;
     --danger-color: #ef4444;
     --font-primary: "Poppins", sans-serif;
   }
   ```

---

## 3.2 Create Layout Templates

### Tasks:

1. **Create master layout** (`app.blade.php`)
   - Include navigation bar
   - Include footer
   - Block for page content
   - Include CSS and JS files

2. **Create responsive navbar component**
   - Logo/brand
   - Navigation links: Home, Services, About, Contact
   - Conditional buttons: Login/Logout, Book Appointment
   - Hamburger menu for mobile
   - Active link highlighting

3. **Create footer component**
   - Company info (address, phone, email)
   - Quick links section
   - Services list
   - Social media icons
   - Copyright notice

4. **Create base styles**
   - Typography (headings, paragraphs)
   - Color scheme
   - Spacing (margins, padding)
   - Box shadows
   - Border radius

---

## 3.3 Build Homepage

### Homepage Sections:

#### 3.3.1 Hero Section

- Headline: "Healthy Smiles Start Here"
- Subheading: "Professional Dental Care at Your Convenience"
- Call-to-action buttons: "Book Appointment" (primary), "Learn More" (secondary)
- Background image or video
- Responsive hero height and text sizing

#### 3.3.2 Services Section

- Grid of 6 service cards (3 columns on desktop, 1 on mobile)
- Each card includes:
  - Service icon
  - Service name
  - Short description
  - "Learn More" link/button
- Use Bootstrap card component
- Hover effects

#### 3.3.3 Why Choose Us Section

- Highlight 4 key features:
  1. Easy Online Booking
  2. Experienced Dentists
  3. Modern Equipment
  4. Patient-Centric Care
- Use icons and short descriptions

#### 3.3.4 Dentists Preview Section

- Display 3-4 featured dentists
- Card layout with:
  - Doctor photo
  - Name
  - Specialty
  - Rating stars
  - "Book with" button
  - For admin only: Link to view detailed ratings

#### 3.3.5 Testimonials Section

- 2-3 testimonial cards
- Include: Patient quote, patient name, rating
- Carousel or static grid

#### 3.3.6 Call-to-Action Section

- "Ready to book your appointment?"
- Button to appointment booking
- Brief text

#### 3.3.7 Contact Preview

- Address, phone, email
- Google Map placeholder
- Quick contact form (name, email, message)

### Tasks:

1. **Create home.blade.php** with all sections
2. **Use Bootstrap grid system** (container, row, col-md-4, etc.)
3. **Add custom CSS** for styling beyond Bootstrap
4. **Add hover animations** with CSS/JS
5. **Test responsiveness** on all breakpoints (xs, sm, md, lg, xl)
6. **Optimize images** for web

---

## 3.4 Create Authentication Pages

### Tasks:

1. **Create login page**
   - Email input
   - Password input
   - Remember me checkbox
   - "Forgot Password" link
   - "Don't have account? Register" link
   - Submit button
   - Client-side validation
   - Error message display

2. **Create registration page**
   - Role selection (Patient/Dentist)
   - Full name input
   - Email input
   - Password input
   - Password confirmation
   - Phone number
   - Terms & conditions checkbox
   - Submit button
   - Link to login page
   - Client-side validation based on role

3. **Add form styling**
   - Bootstrap form-group classes
   - Input validation feedback
   - Error messages
   - Success messages

4. **Create JavaScript for authentication**
   - Form validation before submission
   - API call to backend
   - Handle response (success/error)
   - Store JWT token in localStorage
   - Redirect on successful login

---

## 3.5 Create Services Page

### Tasks:

1. **List all services**
   - Fetch from API
   - Display in grid (3-4 columns)
   - Card layout with icon, name, description, price

2. **Add filtering**
   - Filter by specialty (if applicable)
   - Filter by price range

3. **Add search functionality**
   - Search by service name

4. **Service detail view**
   - Full service description
   - Associated dentists
   - Average duration
   - Related services
   - Booking button

---

## 3.6 Create Doctors Page

### Tasks:

1. **Display all dentists**
   - Fetch from API
   - Grid layout (2-3 columns on desktop)
   - Card with:
     - Doctor photo
     - Name
     - Specialty
     - Experience
     - Average rating
     - Services offered
     - "View Profile" button
     - "Book Appointment" button

2. **Doctor profile view**
   - Full bio
   - Qualifications
   - Experience years
   - Services offered
   - Available time slots
   - Patient reviews (if admin viewing)
   - Book appointment button

3. **Add filtering/search**
   - Filter by specialty
   - Search by name
   - Sort by rating

---

## 3.7 Add Navigation & Routing

### Tasks:

1. **Set up routing** in `routes/web.php`

   ```php
   Route::get('/', [FrontendController::class, 'home'])->name('home');
   Route::get('/services', [FrontendController::class, 'services'])->name('services');
   Route::get('/doctors', [FrontendController::class, 'doctors'])->name('doctors');
   Route::get('/doctors/{id}', [FrontendController::class, 'doctorDetail'])->name('doctor.detail');
   Route::get('/about', [FrontendController::class, 'about'])->name('about');
   Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
   Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
   Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
   ```

2. **Create FrontendController**
   - Fetch data from API
   - Pass to views
   - Handle frontend logic

3. **Active navigation link highlighting**
   - Add active class to current page
   - Use Laravel url() helper

---

## 3.8 JavaScript for Interactivity

### Tasks:

1. **Create app.js** for common functionality
   - Navigation toggle on mobile
   - Smooth scrolling
   - Header fadeout on scroll

2. **Create auth.js** for authentication
   - Register form handling
   - Login form handling
   - Token storage
   - Logout functionality
   - Redirect based on authentication state

3. **Create components.js**
   - Modal interactions
   - Form validation
   - API calls (fetch)
   - Error handling

4. **Add Bootstrap JavaScript** (if not using CDN)
   ```bash
   npm install bootstrap
   ```

---

## 3.9 Mobile Responsiveness

### Tasks:

1. **Test on all breakpoints**
   - XS (< 576px)
   - SM (576px+)
   - MD (768px+)
   - LG (992px+)
   - XL (1200px+)

2. **Implement mobile-first approach**
   - Start with mobile design
   - Progressive enhancement for larger screens

3. **Hide/show elements responsively**
   - Navigation toggle
   - Service card grid adjustment
   - Doctor cards layout

4. **Touch-friendly interactions**
   - Large buttons
   - Proper spacing
   - No hover-only interactions

5. **Mobile navigation**
   - Hamburger menu
   - Full-screen or side drawer
   - Touch-friendly implementation

---

## 3.10 CSS Organization

### Tasks:

1. **Create modular CSS**

   ```
   app.css
   ├── variables (colors, fonts, sizes)
   ├── utilities (margins, padding, display)
   ├── components (buttons, cards, forms)
   ├── pages (homepage, auth pages)
   └── responsive (media queries)
   ```

2. **Add custom styles**
   - Animations
   - Transitions
   - Hover effects
   - Loading states
   - Error states

3. **Use CSS variables** for consistency
   - Colors
   - Font sizes
   - Spacing
   - Shadows

---

## Week 3-4 Checklist

- [ ] Homepage created with all sections
- [ ] Authentication pages working
- [ ] Navigation bar responsive
- [ ] Footer implemented
- [ ] Services page functional
- [ ] Doctors page displaying list
- [ ] Bootstrap grid system properly used
- [ ] CSS organized and maintainable
- [ ] JavaScript for interactivity working
- [ ] Mobile responsiveness tested
- [ ] Forms with validation
- [ ] Error message handling
- [ ] API integration for fetching data started
- [ ] Code reviewed and merged to develop

---

# WEEK 5-6: Core Features & Dashboards

## Overview

Implement appointment booking, patient dashboard, doctor dashboard, and admin panel.

## Deliverables

- [ ] Appointment booking system fully functional
- [ ] Patient dashboard with appointments and records
- [ ] Doctor dashboard with schedule and patients
- [ ] Admin dashboard with statistics and management
- [ ] Appointment notifications (email/in-app)
- [ ] Payment integration (if applicable)
- [ ] All dashboards responsive

---

## 4.1 Appointment Booking System

### Backend Tasks:

1. **Create AppointmentController**
   - Store appointment (validation, payment, confirmation)
   - Update appointment status
   - Get user's appointments
   - Get available slots
   - Calculate available time slots based on dentist availability

2. **API Endpoints**

   ```
   POST   /api/appointments           - Create appointment
   GET    /api/appointments           - List user appointments
   GET    /api/appointments/{id}      - Get appointment details
   PUT    /api/appointments/{id}      - Update appointment
   DELETE /api/appointments/{id}      - Cancel appointment
   GET    /api/appointments/available-slots?dentist_id=X&date=Y
   ```

3. **Appointment Validation**
   - Patient exists
   - Dentist exists
   - Selected time slot is available
   - Appointment in future
   - Patient doesn't have appointment at same time
   - Dentist has service available

4. **Send confirmation email**
   - Use Laravel Mail
   - Include appointment details
   - Provide cancellation link

### Frontend Tasks:

1. **Create booking form** (multi-step or single page)
   - Step 1: Select service
   - Step 2: Select dentist
   - Step 3: Select date and time
   - Step 4: Confirm appointment
   - Step 5: Payment (if required)

2. **Date/Time picker**
   - Calendar widget to select date
   - Dropdown/slots for time selection
   - Show only available slots
   - Real-time validation

3. **Dentist selection**
   - Filter by service
   - Show dentist info (photo, rating, experience)
   - Show patient reviews

4. **Confirmation page**
   - Appointment details summary
   - Confirmation message
   - Email confirmation sent notification
   - Option to add to calendar

5. **Integration steps**

   ```js
   // Fetch available dentists for service
   GET /api/services/{id}/dentists

   // Fetch available slots
   GET /api/appointments/available-slots?dentist_id=1&date=2024-03-15

   // Create appointment
   POST /api/appointments { patient_id, dentist_id, service_id, date, time }
   ```

---

## 4.2 Patient Dashboard

### Backend Tasks:

1. **Create PatientController methods**
   - Get dashboard overview (upcoming appointments, statistics)
   - Get appointment list
   - Get patient medical records
   - Update patient profile

2. **API Endpoints**
   ```
   GET    /api/patients/dashboard     - Dashboard overview
   GET    /api/patients/appointments  - Patient appointments list
   GET    /api/patients/{id}          - Patient profile
   PUT    /api/patients/{id}          - Update profile
   GET    /api/patients/prescriptions - Get recipes/prescriptions
   ```

### Frontend Tasks:

1. **Dashboard overview section**
   - Upcoming appointments (next 3)
   - Total appointments (stats)
   - Quick actions (Book, View Records, Message Doctor)

2. **Appointments section**
   - List all appointments with status badges
   - Upcoming and past appointments tabs
   - Filter by status (pending, confirmed, completed)
   - Option to cancel upcoming appointments
   - Option to reschedule

3. **Medical records section**
   - Patient health information
   - Allergies
   - Medical history
   - Prescriptions/Recipes

4. **Profile section**
   - Edit personal information
   - Upload profile photo
   - Change password
   - Notification preferences

5. **Ratings section**
   - Rate completed appointments
   - View rating history
   - See doctor feedback

---

## 4.3 Doctor Dashboard

### Backend Tasks:

1. **Create DentistController methods**
   - Dashboard overview
   - Schedule management
   - Appointments list
   - Patient list
   - Recipe/prescription management
   - Report generation

2. **API Endpoints**
   ```
   GET    /api/dentists/dashboard     - Dashboard overview
   GET    /api/dentists/schedule      - Doctor schedule
   GET    /api/dentists/appointments  - Doctor appointments
   GET    /api/dentists/patients      - Doctor's patients
   POST   /api/dentists/recipes       - Create prescription
   GET    /api/dentists/reports       - Generate reports
   ```

### Frontend Tasks:

1. **Dashboard overview section**
   - Today's appointments
   - Week overview
   - Total patients
   - Average rating
   - Quick stats

2. **Schedule section**
   - Calendar view of schedule
   - Ability to add/edit availability
   - Show booked appointment slots
   - Block/unblock time slots

3. **Appointments section**
   - Today's appointments
   - Upcoming this week
   - Status indicators (pending, confirmed, completed)
   - Quick actions (Start appointment, Add notes, Write prescription)
   - Patient information sidebar

4. **Patients section**
   - List of all patients
   - Search and filter
   - Patient details
   - View medical history
   - Add/edit patient notes

5. **Recipes/Prescriptions section**
   - Create prescription after appointment
   - Add medicines (multiple)
   - Add dosage and instructions
   - Generate PDF prescription
   - Send to patient email

6. **Reports section**
   - Generate appointment reports (daily, weekly, monthly)
   - Patient statistics
   - Income reports
   - Export to PDF/CSV

---

## 4.4 Admin Dashboard

### Backend Tasks:

1. **Create AdminController methods**
   - Dashboard statistics
   - User management
   - Dentist management
   - Appointment management
   - Finance/payment management
   - System reports

2. **API Endpoints**
   ```
   GET    /api/admin/dashboard        - Dashboard stats
   GET    /api/admin/users            - All users list
   POST   /api/admin/users            - Create user
   PUT    /api/admin/users/{id}       - Update user
   DELETE /api/admin/users/{id}       - Delete user
   GET    /api/admin/dentists         - All dentists
   POST   /api/admin/dentists         - Create dentist
   GET    /api/admin/appointments     - All appointments
   GET    /api/admin/finances         - Financial data
   ```

### Frontend Tasks:

1. **Dashboard overview section**
   - Total users (patients, dentists, admins)
   - Total appointments (this month)
   - Total revenue
   - Key statistics cards
   - Charts (appointments over time, revenue, etc.)

2. **User management section**
   - Table of all users
   - Filter by role
   - Search functionality
   - Edit user information
   - Activate/deactivate users
   - Delete users
   - Assign roles

3. **Dentist management section**
   - List all dentists
   - Create new dentist
   - Edit dentist info
   - View dentist ratings
   - View dentist schedule
   - Assign services to dentists

4. **Appointment management section**
   - List all appointments
   - Filter by status
   - Search by patient/dentist
   - View appointment details
   - Modify appointment
   - Cancel appointment

5. **Finance management section**
   - List all payments
   - Filter by status (pending, paid, failed)
   - View payment details
   - Generate financial reports
   - Export to CSV/PDF

6. **Service management section**
   - CRUD operations for services
   - Edit service details
   - Manager service pricing

7. **Ratings management section**
   - View all doctor ratings
   - Filter by dentist
   - Sort by rating
   - View patient feedback
   - (Admin-only view)

---

## 4.5 Appointment Notifications

### Tasks:

1. **Email notifications**
   - Appointment confirmation email
   - Appointment reminder 24 hours before
   - Appointment completion email
   - Doctor feedback/rating request email

2. **In-app notifications**
   - Bell icon in navbar
   - Notification dropdown
   - Mark as read
   - Clear notifications

3. **Create MailNotification classes**

   ```php
   AppointmentConfirmation
   AppointmentReminder
   PrescriptionReady
   NewRating
   ```

4. **Implement notification queues**
   - Use Laravel queues for email sending
   - Configure Redis or database driver

---

## 4.6 Dashboard Styling & Layout

### Tasks:

1. **Create responsive dashboard layout**
   - Sidebar navigation (collapsible on mobile)
   - Top navigation bar with user profile
   - Breadcrumb navigation
   - Main content area

2. **Dashboard components**
   - Stat cards
   - Charts (Chart.js or similar)
   - Tables (sortable, filterable)
   - Forms for editing
   - Modals for confirmations

3. **Consistency**
   - Use same styling across all dashboards
   - Consistent color scheme
   - Responsive tables/grids

---

## Week 5-6 Checklist

- [ ] Appointment booking system end-to-end working
- [ ] Patient dashboard fully functional
- [ ] Doctor dashboard with all features
- [ ] Admin dashboard with all features
- [ ] Appointment notifications implemented
- [ ] Email notifications working
- [ ] In-app notifications working
- [ ] All dashboards responsive on mobile
- [ ] Charts and stats displaying correctly
- [ ] Role-based access enforced
- [ ] Data validation on all forms
- [ ] Error handling and user feedback
- [ ] Code reviewed and merged to develop

---

# WEEK 7: Advanced Features

## Overview

Implement ratings, recipes, report generation, and other advanced features.

## Deliverables

- [ ] Ratings and feedback system fully working
- [ ] Recipes/prescriptions generation
- [ ] Report generation (PDF export)
- [ ] Patient medical records system
- [ ] Payment integration (if applicable)
- [ ] Appointment reminders (scheduled jobs)
- [ ] Advanced search and filtering

---

## 5.1 Ratings & Feedback System

### Backend Tasks:

1. **Create RatingController**
   - Store rating and feedback
   - Get ratings for dentist
   - Get patient rating history
   - Validate rating (only after completed appointment)

2. **Validation**
   - Only patients can rate
   - Only rate completed appointments
   - Rating must be 1-5 stars
   - One rating per appointment

3. **API Endpoints**

   ```
   POST   /api/ratings                   - Submit rating
   GET    /api/ratings/dentist/{id}      - Get dentist ratings (admin/public)
   GET    /api/ratings/patient-history   - Patient rating history
   DELETE /api/ratings/{id}              - Delete own rating
   ```

4. **Update Dentist Model**
   - Calculate average rating
   - Update total rating count
   - Store in database for quick access

### Frontend Tasks:

1. **Rating form**
   - Star rating selector (1-5)
   - Text feedback box
   - Submit button
   - Validation (required feedback if low rating)
   - Success message

2. **Dentist ratings display**
   - Show average rating on doctor card
   - Show review count
   - Link to see all reviews
   - Reviews page showing all feedback

3. **Patient review history**
   - Show all ratings given by patient
   - Ability to edit/delete own ratings
   - Show only to patient and admin

---

## 5.2 Recipes/Prescriptions

### Backend Tasks:

1. **Create RecipeController**
   - Create recipe after appointment
   - Get recipes for patient
   - Get recipes created by dentist
   - Edit recipe

2. **RecipeItem (Medicines)**
   - Medicine name
   - Dosage
   - Frequency
   - Duration
   - Instructions

3. **API Endpoints**
   ```
   POST   /api/recipes                   - Create recipe
   GET    /api/recipes/{id}              - Get recipe details
   PUT    /api/recipes/{id}              - Edit recipe
   GET    /api/patients/recipes          - Patient's recipes
   GET    /api/recipes/{id}/pdf          - Download PDF
   ```

### Frontend Tasks:

1. **Doctor recipe creation form**
   - Dynamic medicine fields (add multiple)
   - Instructions text area
   - Save button
   - Confirm with patient data display
   - Send to patient email option

2. **Patient recipes view**
   - List all recipes
   - Recipe details (medicines, dosage, instructions)
   - Download PDF button
   - Print option
   - Share with another doctor

---

## 5.3 Report Generation

### Backend Tasks:

1. **Create ReportService**
   - Generate appointment reports
   - Generate financial reports
   - Generate patient reports
   - Generate dentist performance reports

2. **Report types**
   - Daily appointments report
   - Weekly appointments report
   - Monthly financial report
   - Dentist performance (appointments, ratings, revenue)
   - Patient statistics

3. **Export formats**
   - PDF (use Laravel DomPDF)
   - CSV (for Excel)
   - Excel (using Laravel Excel)

### Frontend Tasks:

1. **Report download interface**
   - Select report type
   - Select date range
   - Select format (PDF, CSV, Excel)
   - Download button
   - Email report option

2. **Dentist reports**
   - Appointments report
   - Patient list (currently attached)
   - Revenue report
   - Export as PDF

3. **Admin reports**
   - System-wide appointment statistics
   - Financial summary
   - User statistics
   - Dentist performance ranking

---

## 5.4 Patient Medical Records

### Backend Tasks:

1. **Create PatientRecordsController**
   - Store medical history
   - Store allergies
   - Store medications
   - Store past diagnoses

2. **API Endpoints**
   ```
   GET    /api/patients/{id}/medical-records
   PUT    /api/patients/{id}/medical-records
   POST   /api/patients/{id}/records/add-entry
   ```

### Frontend Tasks:

1. **Medical records section**
   - Display patient medical history
   - Display allergies
   - Display current medications
   - Edit medical information (patient or doctor)
   - Timeline of past treatments

---

## 5.5 Appointment Reminders (Scheduled Jobs)

### Backend Tasks:

1. **Create appointment reminder job**

   ```bash
   php artisan make:job SendAppointmentReminder
   ```

   - Send reminder 24 hours before appointment
   - Send reminder 1 hour before appointment

2. **Set up scheduling**

   ```php
   // In app/Console/Kernel.php
   protected function schedule(Schedule $schedule)
   {
       $schedule->job(new SendAppointmentReminder)
           ->everyFiveMinutes();
   }
   ```

3. **Implement reminder logic**
   - Query appointments within 24 hours
   - Send email
   - Log reminder sent

---

## 5.6 Payment Integration (Optional)

### Tasks (if applicable):

1. **Choose payment gateway**
   - Stripe, PayPal, or local option

2. **Implement payment flow**
   - Create checkout form
   - Store payment info safely
   - Handle payment confirmation
   - Update appointment status

3. **Create payment model and API**
   - Store payment transactions
   - Track payment status
   - Handle refunds

---

## Week 7 Checklist

- [ ] Ratings system fully functional
- [ ] Feedback visible to admin and public
- [ ] Dentist average rating calculated correctly
- [ ] Recipes/prescriptions creation working
- [ ] Patients can view recipes
- [ ] Recipe PDF export working
- [ ] Report generation for all report types
- [ ] CSV/Excel export working
- [ ] Medical records editable and viewable
- [ ] Appointment reminders sending
- [ ] All advanced features tested
- [ ] Code reviewed and merged to develop

---

# WEEK 8: Testing & Deployment

## Overview

Comprehensive testing, bug fixes, and deployment to Railway.

## Deliverables

- [ ] All features tested (manual and automated)
- [ ] Mobile responsiveness verified
- [ ] Performance optimized
- [ ] Security review completed
- [ ] Database backups configured
- [ ] Deployed to Railway (production)
- [ ] Documentation complete

---

## 6.1 Testing

### Tasks:

1. **Unit Tests**
   - Test models (relationships, calculations)
   - Test services (business logic)
   - Test form validation

   ```bash
   php artisan make:test UserModelTest
   php artisan test
   ```

2. **Feature Tests**
   - Test authentication flow
   - Test appointment booking
   - Test dashboard functionality
   - Test role-based access

3. **API Tests**
   - Test all API endpoints
   - Test error responses
   - Test status codes
   - Test authentication

4. **Manual Testing**
   - User registration flow
   - Appointment booking flow
   - Doctor schedule management
   - Admin functions
   - Email notifications
   - PDF generation

5. **Cross-browser testing**
   - Chrome, Firefox, Safari, Edge
   - Mobile browsers (Chrome, Safari)

6. **Mobile responsiveness**
   - Test on multiple device sizes
   - Test touch interactions
   - Test performance on slow networks

---

## 6.2 Performance Optimization

### Tasks:

1. **Database optimization**
   - Add indexes to frequently queried columns
   - Optimize queries (eager loading)
   - Use pagination for large datasets

2. **Frontend optimization**
   - Minify CSS and JavaScript
   - Compress images
   - Enable gzip compression
   - Use CDN for static assets

3. **Backend optimization**
   - Cache database queries
   - Use Redis for session storage
   - Optimize API responses (select only needed fields)

4. **Load testing**
   - Test with multiple concurrent users
   - Identify bottlenecks
   - Optimize database queries if needed

---

## 6.3 Security Review

### Tasks:

1. **Authentication security**
   - Verify JWT token expiration
   - Check password hashing
   - Test session security
   - Verify CSRF protection

2. **Authorization**
   - Verify role-based access control
   - Test authorization on all endpoints
   - Ensure users can't access other's data

3. **Input validation**
   - Test SQL injection prevention
   - Verify XSS protection
   - Test file upload security

4. **Environment setup**
   - Verify `.env` variables are not exposed
   - Check error reporting (disable in production)
   - Verify database credentials are secure

---

## 6.4 Bug Fixes

### Tasks:

1. **Document all found bugs**
   - Create GitHub issues
   - Assign priority
   - Assign to team members

2. **Fix bugs** in development branches
   - Create bugfix branches
   - Test fixes thoroughly
   - Merge to develop after review

3. **Regression testing**
   - Test fixed features
   - Ensure no new issues introduced

---

## 6.5 Deploy to Railway

### Setup Tasks:

1. **Railway project setup**
   - Create Railway project
   - Connect GitHub repository
   - Create database instance
   - Configure environment variables

2. **Configure `.env` for production**

   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   DB_CONNECTION=mysql
   DB_HOST=railway-db-host
   DB_DATABASE=shinytooth_prod
   DB_USERNAME=root
   DB_PASSWORD=secure-password
   MAIL_MAILER=smtp
   MAIL_HOST=smtp service
   JWT_SECRET=long-secure-string
   ```

3. **Database migration**
   - Run migrations on production
   - Seed initial data
   - Backup production database

4. **Configure domain**
   - Point domain to Railway
   - Configure HTTPS/SSL
   - Set up email service

5. **Deploy**

   ```bash
   git push origin main
   # Railway auto-deploys on push to main
   ```

6. **Post-deployment**
   - Verify all features working
   - Check logs for errors
   - Monitor performance
   - Set up monitoring/alerts

---

## 6.6 Documentation

### Tasks:

1. **User documentation**
   - Patient user guide
   - Doctor user guide
   - Admin user guide
   - FAQ section

2. **Technical documentation**
   - API documentation (finalized)
   - Database schema documentation
   - Architecture overview
   - Deployment guide

3. **Code documentation**
   - PHP comments and docblocks
   - JavaScript comments
   - README files in each directory

4. **Update README.md**
   - Project description
   - Technology stack
   - Setup instructions
   - Deployment instructions
   - Team members

---

## 6.7 Monitoring & Maintenance

### Tasks:

1. **Set up monitoring**
   - Application errors
   - Performance metrics
   - Database performance
   - API response times

2. **Backup strategy**
   - Daily database backups
   - Code version control (GitHub)
   - Configuration backups

3. **Maintenance plan**
   - Regular security updates
   - Dependency updates
   - Performance monitoring
   - User support plan

---

## Week 8 Checklist

- [ ] All unit tests passing
- [ ] All feature tests passing
- [ ] Manual testing completed
- [ ] Mobile responsiveness verified
- [ ] Cross-browser testing done
- [ ] Performance optimized
- [ ] Security review completed
- [ ] All bugs fixed
- [ ] User documentation created
- [ ] Technical documentation complete
- [ ] Railway deployment configured
- [ ] Database migrations run on production
- [ ] All features tested on production
- [ ] Email notifications working
- [ ] Monitoring and alerts set up
- [ ] Backup strategy configured
- [ ] Project completed and handed over

---

# Git Workflow & Best Practices

## Branching Strategy

```
main (Production - only releases)
  ↓
develop (Integration - active development)
  ├── feature/authentication
  ├── feature/appointment-booking
  ├── feature/doctor-dashboard
  ├── feature/admin-panel
  ├── bugfix/auth-issue
  └── hotfix/critical-bug
```

## Branch Naming Convention

- **Feature branches:** `feature/short-description`
- **Bug fix branches:** `bugfix/issue-description`
- **Hot fix branches:** `hotfix/critical-issue`

Examples:

- `feature/appointment-booking-system`
- `bugfix/login-validation-error`
- `hotfix/database-connection-timeout`

## Commit Message Format

Use conventional commits:

```
type(scope): subject

body

footer
```

**Types:** feat, fix, docs, style, refactor, test, chore

**Examples:**

```
feat(auth): add JWT token refresh functionality
fix(appointment): resolve double-booking issue
docs(readme): update deployment instructions
```

## Pull Request Process

1. **Create feature branch from develop**

   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/my-feature
   ```

2. **Make changes and commit**

   ```bash
   git add .
   git commit -m "feat: add new feature"
   git push origin feature/my-feature
   ```

3. **Create Pull Request**
   - Title: Clear description
   - Description: What changed and why
   - Link related issues
   - Screenshots if UI changes

4. **Code review**
   - At least 1 approval required
   - Address review comments
   - Re-request review after changes

5. **Merge to develop**
   - Keep branch history (don't squash unless necessary)
   - Delete branch after merge

6. **Merge to main** (only for releases)
   - Create release PR from develop to main
   - Tag version: `v1.0.0`
   - Document release notes

## Code Review Checklist

- [ ] Code follows project style guide
- [ ] No console errors or warnings
- [ ] Database migrations are tested
- [ ] API responses are validated
- [ ] Frontend is responsive
- [ ] Code is commented where necessary
- [ ] No hardcoded values/credentials
- [ ] All tests passing
- [ ] No performance issues

## Team Communication

- **Daily Standups:** Brief sync on progress (async-friendly)
- **Weekly Planning:** Prioritize tasks for coming week
- **Code Reviews:** Timely feedback (24 hours max)
- **Slack/Discord:** For quick questions and blockers support

---

## Project Checklist (Throughout 8 Weeks)

**Week 1:**

- [ ] Team aligned on architecture and database schema
- [ ] GitHub repository set up and accessible
- [ ] Development environment working for all team members
- [ ] Initial commit made

**Week 2-3:**

- [ ] Database migrations all working
- [ ] Authentication system complete
- [ ] RBAC implemented
- [ ] Sample data seeded

**Week 3-4:**

- [ ] Homepage responsive and complete
- [ ] Authentication pages working
- [ ] Main navigation functional
- [ ] Services and doctors pages displaying data

**Week 5-6:**

- [ ] Appointment booking end-to-end working
- [ ] Patient dashboard functional
- [ ] Doctor dashboard functional
- [ ] Admin dashboard functional
- [ ] Notifications working

**Week 7:**

- [ ] Ratings system complete
- [ ] Recipes functionality working
- [ ] Report generation working
- [ ] Medical records system functional

**Week 8:**

- [ ] All testing completed
- [ ] Project deployed to Railway
- [ ] Documentation finalized
- [ ] Team handover complete

---

**Good luck with your project! 🚀**
