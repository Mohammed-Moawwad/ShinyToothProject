# ShinyTooth Dental Clinic Dashboard

A complete dental clinic website with a **landing page** featuring services menu and an **admin/staff dashboard** with role-based views.

## Features

🏠 **Landing Page**
- Professional hero section with clinic information
- Custom SVG tooth illustration
- 6 service cards (Book Appointment, Check-up & Cleaning, Restorative Care, Cosmetic Dentistry, Emergency Care, Meet Team)
- Contact info section (Location, Hours, Phone)
- Staff Dashboard login button

📊 **Role-Based Staff Dashboard**
- **Admin**: Clinic overview (total patients, active dentists, daily revenue, appointment analytics)
- **Dentist**: Personal schedule & patient metrics (my patients, appointments, treatment pending)
- **Patient**: Personal dental profile (next appointment, visit history, pending work)

🎨 **Professional Design**
- Dental clinic branding with tooth emoji logo (🦷) in sticky header
- Click logo/header to return home from dashboard
- Minimal 3-color palette: Teal (#1e9b8b), Navy (#1e3a5f), Light Gray (#f5f7fa)
- Fully responsive grid layout (mobile-friendly)
- Interactive KPI cards with hover effects
- Chart.js visualizations (bar & line charts)

🔐 **Authentication**
- Mock client-side authentication (demo only)
- Role-based access control
- Login/Logout functionality

## Quick Start

**Option 1: Python HTTP Server (Recommended)**
```powershell
cd dashboard
python -m http.server 8000
# Open http://localhost:8000 in your browser
```

**Option 2: VS Code Live Server**
- Right-click `index.html` → "Open with Live Server"

## Files

| File | Purpose |
|------|---------|
| `index.html` | Landing page + 3 role-based dashboard views + login modal |
| `styles.css` | Dental clinic theme (3 colors, responsive, hero section, service cards) |
| `app.js` | Navigation, role-based auth, chart rendering, data binding |
| `data/sample-data.json` | Mock clinic data for all 3 roles |

## Login Credentials (Demo)

| Username | Password | Role     | View |
|----------|----------|----------|------|
| admin    | admin    | Admin    | Clinic overview |
| dentist  | pass     | Dentist  | Personal schedule |
| patient  | pass     | Patient  | Dental profile |

**Note:** Click the logo/header text to return to the landing page from any dashboard.

## Landing Page Sections

### Hero Section
- Clinic name, tagline, and custom SVG tooth illustration
- Gradient background (teal to navy)

### Services Menu (6 Cards)
1. **📅 Book Appointment** - Schedule your visit
2. **🔍 Check-up & Cleaning** - Dental examination & cleaning
3. **🦷 Restorative Care** - Fillings, crowns, implants
4. **✨ Cosmetic Dentistry** - Teeth whitening, smile enhancement
5. **🏥 Emergency Care** - Urgent dental treatment
6. **👨‍⚕️ Meet Our Team** - View dentists & staff

### Contact & Info Section
- **Location**: 123 Smile Street, Dental District, City
- **Hours**: Mon-Fri 9AM-6PM, Sat 9AM-2PM
- **Contact**: Phone, Email, 24/7 Emergency

## Admin Dashboard

**KPIs:**
- Total Patients: 342
- Active Dentists: 8
- Appointments Today: 24
- Daily Revenue: $3,250

**Chart:** Bar chart showing appointments by dentist

## Dentist Dashboard

**KPIs:**
- My Patients: 48
- My Appointments Today: 6
- Completed Today: 3
- Pending Treatments: 12

**Chart:** Line chart of weekly appointment schedule

## Patient Dashboard

**KPIs:**
- Next Appointment: March 5, 2026 @ 2:30 PM
- My Dentist: Dr. Sarah Johnson
- Total Visits: 8
- Pending Work: Root canal scheduled

**Chart:** Line chart of visit costs over history

## Customization

### Add More Services
Edit the services grid in `index.html` (`.services-grid` section):
```html
<div class="service-card">
  <div class="service-icon">🦴</div>
  <h4>Oral Surgery</h4>
  <p>Complex dental procedures</p>
  <button class="service-btn" data-service="surgery">Learn More</button>
</div>
```

### Change Colors
Modify CSS variables in `styles.css`:
```css
:root {
  --primary: #1e9b8b;      /* Teal (healthcare) */
  --secondary: #1e3a5f;    /* Navy (trust) */
  --bg: #f5f7fa;           /* Light gray background */
}
```

### Add More Users
Edit `mockAuth()` in `app.js`:
```javascript
const users = {
  admin: { password: 'admin', role: 'admin' },
  newuser: { password: 'newpass', role: 'dentist' }
};
```

### Update Sample Data
Edit `data/sample-data.json` to change clinic info, schedules, and patient data.

## Production Notes

⚠️ **This is a demo website.** For production:
- Replace `mockAuth()` with a real backend API authentication
- Connect to a database for actual appointment/patient data
- Use HTTPS and secure token storage
- Move all sensitive operations to a backend server
- Add form validation and error handling
- Implement proper appointment booking workflow
