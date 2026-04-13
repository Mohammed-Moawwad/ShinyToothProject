# Admin Dashboard Setup Guide

## Overview
A complete admin dashboard has been created for the ShinyTooth project with the following features:

### Features Included
- **Dashboard** - Overview with key statistics and recent data
- **User Management** - View all patients and dentists with search and filtering
- **Appointments** - Full appointment management with status tracking and filtering
- **Payments & Financial Reports** - Revenue tracking and payment status monitoring
- **Services Management** - Browse all dental services and their popularity
- **Analytics & Reports** - Charts and visualizations for business insights

## Setup Instructions

### 1. Admin Email Configuration
The admin panel is accessible only through a specific email address. You can configure this in the `.env` file:

```env
ADMIN_EMAIL=admin@shinytooth.com
```

**Change this to your desired admin email address.** Only users logged in with this email can access the admin panel.

### 2. Create Admin User Account
You need to create a user account with the admin email address. This can be a Patient or User account - the system will check the email against the `ADMIN_EMAIL` environment variable.

#### Option A: Using API (Recommended)
```bash
# Patient Admin Account
POST /api/auth/patient/register
{
    "name": "Admin User",
    "email": "admin@shinytooth.com",
    "password": "secure_password",
    "phone": "555-0000"
}

# Then login to get token:
POST /api/auth/patient/login
{
    "email": "admin@shinytooth.com",
    "password": "secure_password"
}
```

#### Option B: Database Insert (Alternative)
You can directly insert a user into the database:

```sql
-- For Patient Admin
INSERT INTO patients (name, email, password, phone, created_at, updated_at) 
VALUES (
    'Admin User', 
    'admin@shinytooth.com', 
    'bcrypt_hashed_password', 
    '555-0000',
    NOW(), 
    NOW()
);

-- Make sure password is properly hashed using Laravel's Hash::make()
```

### 3. Access the Admin Panel

Once you have created an admin account and are logged in with that email:

1. **Login to the application** at `http://localhost:8000`
2. **Navigate to:** `http://localhost:8000/admin/dashboard`

Or directly access any admin page:
- `/admin/dashboard` - Main dashboard with statistics
- `/admin/users` - User management
- `/admin/appointments` - Appointments management
- `/admin/payments` - Payment tracking and reports
- `/admin/services` - Services management
- `/admin/analytics` - Advanced analytics and charts

### 4. Security Features

The admin panel is protected by:
- **Authentication Middleware** - Must be logged in
- **Email Authorization** - Only the configured admin email can access
- **Session Management** - Uses Laravel's session driver (configured as database)

If someone tries to access the admin panel without proper authorization, they'll receive a "403 Access Denied" error.

## Dashboard Features

### Dashboard (Main)
- Total Patients, Dentists, Appointments, Users counts
- Total Revenue and Pending Payments
- Recent appointments and payments
- Quick access to all management sections

### Users Management
- View all patients and dentists separately
- Search by name or email
- View detailed user information
- Filter by user type

### Appointments
- View all appointments with status filtering
- Search by patient name or email
- Sort by latest or oldest
- Status breakdown (Pending, Confirmed, Completed, Cancelled)
- Detailed appointment information with patient and dentist details

### Payments & Financial Reports
- Revenue tracking and statistics
- Payment status breakdown (Completed, Pending, Failed)
- Date range filtering
- Detailed payment information linked to appointments
- Patient information with payment history

### Services Management
- Browse all available services
- View appointment count per service
- Service details including price and duration
- Search services

### Analytics & Reports
- Appointment trends by month
- Revenue trends by month
- Patient and dentist growth charts
- Most popular services
- Comprehensive business insights

## Logout

To logout from the admin panel, click the **"Logout"** button in the top-right corner of the navigation bar.

## Changing Admin Email

To change the admin email:

1. Edit `.env` file
2. Update the `ADMIN_EMAIL` value
3. Create a new admin user account with the new email
4. Login with the new credentials

## Troubleshooting

### "Access denied. Admin only." Error
- Make sure you're logged in with the correct email that matches `ADMIN_EMAIL` in `.env`
- Verify your user account email in the database matches the `ADMIN_EMAIL`

### Admin Dashboard Not Loading
- Ensure you're authenticated (logged in)
- Check that your email matches the `ADMIN_EMAIL` configuration
- Verify the database connection is working
- Check Laravel logs in `storage/logs/`

### Missing Data
- Some features require data in the database
- Make sure appointments, payments, and users exist
- Check data relationships (appointments need patients, dentists, services)

## Future Enhancements

Potential features to add:
- User creation/editing from admin panel
- Appointment rescheduling
- Payment status updates
- Report exports (CSV, PDF)
- Admin activity logs
- Role-based access control (multiple admin levels)
- Email alerts for important events
