# Quick Start Guide - Get Running in 30 Minutes

## Prerequisites Check

Before starting, ensure you have:

- [ ] PHP 8.2 or higher
- [ ] Composer 2.0+
- [ ] Node.js 18+ and npm
- [ ] MySQL 8.0+ (or access to Railway database)
- [ ] Git client
- [ ] Code editor (VS Code recommended)

### Install Prerequisites (Windows):

**PHP:**

```bash
# Download from https://windows.php.net/
# Install to: C:\PHP
# Add to PATH
```

**Composer:**

```bash
# Download from https://getcomposer.org/download/
# Run installer
composer -v  # Verify installation
```

**Node.js:**

```bash
# Download from https://nodejs.org/ (LTS version)
# Install and verify
node -v
npm -v
```

**Git:**

```bash
# Download from https://git-scm.com/
# Install and verify
git --version
```

**MySQL:**

```bash
# Download from https://dev.mysql.com/downloads/mysql/
# Or use: https://www.mysql.com/products/community/ (Community Server)
# Or use WSL with: wsl apt-get install mysql-server
```

---

## Step 1: Clone Repository (5 minutes)

```bash
# Navigate to your project directory
cd c:\Users\[YourUsername]\OneDrive\Desktop\College\471-Semester\CS331\CS331Project\

# Clone the project (replace with your repo URL)
git clone https://github.com/[YourOrg]/ShinyToothDental.git
cd ShinyToothDental

# Verify you're in develop branch
git checkout develop
```

---

## Step 2: Set Up Laravel (10 minutes)

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file
copy .env.example .env
# Or on Linux/Mac: cp .env.example .env

# Generate app key
php artisan key:generate

# Generate JWT secret (if using JWT)
php artisan jwt:secret
```

---

## Step 3: Configure Database (5 minutes)

### Option A: Local MySQL

```bash
# Create database in MySQL
mysql -u root -p
CREATE DATABASE shinytooth_dental_dev;
EXIT;

# Update .env file
# Set these values:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=shinytooth_dental_dev
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate
```

### Option B: Railway Database

```bash
# Get connection string from Railway
# Update .env with Railway connection details:
# DB_HOST=[railway-host]
# DB_DATABASE=[database-name]
# DB_USERNAME=[username]
# DB_PASSWORD=[password]

# Run migrations
php artisan migrate
```

---

## Step 4: Seed Sample Data (5 minutes)

```bash
# Create sample data in database
php artisan db:seed

# This creates:
# - 1 admin user (admin@shinytooth.com / password)
# - 5 sample dentists
# - 10 sample patients
# - 20 sample services
# - Various appointments and availability schedules
```

---

## Step 5: Start Development Server (5 minutes)

### Terminal 1: Start Laravel Backend

```bash
php artisan serve

# Server runs at: http://localhost:8000
```

### Terminal 2: Start Frontend Dev Server

```bash
npm run dev

# Compiles assets and watches for changes
# Assets compiled to: public/css/app.css, public/js/app.js
```

---

## Step 6: Verify Installation

Open browser and check:

1. **Frontend:** http://localhost:8000
   - You should see the homepage
   - Navigation bar working
   - Try to book appointment (may not persist without backend)

2. **API:** http://localhost:8000/api/services
   - Should return JSON list of services
   - This verifies API is working

3. **Database:** Check MySQL database
   ```bash
   mysql -u root -p
   USE shinytooth_dental_dev;
   SELECT COUNT(*) FROM users;
   ```

---

## Test Credentials

### Admin Account

```
Email: admin@shinytooth.com
Password: password
Role: Admin
```

### Sample Dentist

```
Email: dentist1@shinytooth.com
Password: password
Role: Dentist
```

### Sample Patient

```
Email: patient1@shinytooth.com
Password: password
Role: Patient
```

---

## Common Issues & Solutions

### Issue: "Class 'App\Models\User' not found"

**Solution:**

```bash
composer dump-autoload
php artisan cache:clear
php artisan config:clear
```

### Issue: "No application encryption key has been specified"

**Solution:**

```bash
php artisan key:generate
```

### Issue: Database connection error

**Solution:**

- Check `.env` database credentials
- Ensure MySQL is running
- Verify database exists:
  ```bash
  mysql -u root -p -e "SHOW DATABASES LIKE 'shinytooth%';"
  ```

### Issue: "Port 8000 already in use"

**Solution:**

```bash
# Use different port
php artisan serve --port=8001
```

### Issue: Node modules not installing

**Solution:**

```bash
rm -r node_modules
rm package-lock.json
npm install
```

### Issue: npm run dev not compiling

**Solution:**

```bash
npm run build  # One-time compile
npm run dev    # Watch mode
```

---

## File Organization

After setup, your project should look like:

```
ShinyToothDental/
├── app/                    ← PHP code
├── database/               ← Migrations, seeders
├── public/                 ← Static files (images, css, js)
├── resources/              ← Views, raw CSS/JS
├── routes/                 ← URL routes
├── storage/                ← Logs, cache
├── .env                    ← Configuration (generated)
├── .env.example            ← Template
├── package.json            ← JavaScript dependencies
├── composer.json           ← PHP dependencies
├── laravel.log             ← Error logs
└── README.md               ← This repo's documentation
```

---

## Daily Development Workflow

### Starting your day:

```bash
# Terminal 1
cd ShinyToothProject/ShinyToothDental
git pull origin develop       # Get latest code
php artisan serve            # Start backend

# Terminal 2
npm run dev                  # Start frontend build watcher
```

### Before committing:

```bash
# Check your changes
git status
git diff

# Stage and commit
git add .
git commit -m "feat: describe your changes"

# Push to your feature branch
git push origin feature/your-feature-name

# Create Pull Request on GitHub
```

### Before ending day:

```bash
# Commit WIP (Work In Progress) if needed
git commit -m "wip: incomplete feature - continuing tomorrow"
git push origin feature/your-feature-name
```

---

## Useful Commands Reference

### Laravel Commands

```bash
# Migrations
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Rollback last batch
php artisan migrate:fresh        # Drop all tables and re-migrate
php artisan make:migration name  # Create new migration

# Seeders
php artisan db:seed              # Run all seeders
php artisan db:seed --class=UserSeeder  # Run specific seeder

# Models
php artisan make:model ModelName
php artisan make:model ModelName -m  # With migration

# Controllers
php artisan make:controller ControllerName
php artisan make:controller ControllerName --resource  # With CRUD methods

# Tinker (Interactive Shell)
php artisan tinker              # Test code interactively

# Cache & Config
php artisan cache:clear         # Clear all caches
php artisan config:clear        # Clear config cache
php artisan view:clear          # Clear view cache

# Testing
php artisan test                # Run all tests
php artisan test --filter=TestName  # Run specific test
```

### NPM Commands

```bash
npm install package-name        # Install package
npm update                       # Update all packages
npm run dev                      # Development build with watch
npm run build                    # Production build
npm run lint                     # Lint JavaScript (if configured)
```

### Git Commands

```bash
git status                       # See current state
git log --oneline               # See commit history
git branch                      # List branches
git checkout -b feature/name    # Create feature branch
git add .                       # Stage all changes
git commit -m "message"         # Commit changes
git push origin branch-name     # Push to GitHub
git pull origin branch-name     # Pull from GitHub
```

---

## Directory Permissions (if issues)

Windows usually handles this automatically, but if you get permission errors:

```bash
# Give full permissions to storage and bootstrap directories
# Windows (in Command Prompt as Admin):
icacls storage /grant Users:F /T

# Or using WSL/Git Bash:
chmod -R 755 storage bootstrap/cache
```

---

## IDE Setup (VS Code Recommended)

### Install Extensions:

1. PHP Intelephense
2. Laravel Blade Snippets
3. Laravel Artisan
4. Prettier - Code formatter
5. Thunder Client or REST Client (for API testing)

### Settings (in VS Code `settings.json`):

```json
{
  "[php]": {
    "editor.formatOnSave": true,
    "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
  },
  "[blade]": {
    "editor.defaultFormatter": "SillyCross.blade"
  },
  "files.exclude": {
    "**/node_modules": true,
    "**/vendor": true
  }
}
```

---

## Debugging

### Enable debugging in .env

```
APP_DEBUG=true  # Already set in development
```

### View logs in real-time

```bash
# On a new terminal
tail -f storage/logs/laravel.log

# Or using Laravel Telescope (install for advanced debugging)
php artisan telescope:install
```

### Use dd() for debugging

```php
// In Laravel controller/model
dd($variable);  // Dies and dumps variable - stops execution
dump($variable); // Dumps without stopping

// Multiple dumps
dd($user, $appointment, $services);
```

---

## Performance Check

### Check if everything is optimized:

```bash
# Production-ready optimizations
php artisan optimize              # Optimize autoloader
php artisan config:cache          # Cache configuration
php artisan route:cache           # Cache routes
php artisan view:cache            # Cache views

# For development, clear these
php artisan optimize:clear        # Clear all optimizations
```

---

## Weekly Checklist

- [ ] Pull latest changes every Monday
- [ ] Create feature branch before starting work
- [ ] Commit with meaningful messages
- [ ] Push to GitHub daily
- [ ] Create PR before Friday
- [ ] Request code review
- [ ] Respond to code review comments
- [ ] Merge to develop after approval

---

## Getting Help

### Common resources:

- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [PHP Documentation](https://www.php.net/manual/en)
- [GitHub Help](https://docs.github.com)

### Within team:

- Ask on Slack/Discord
- Link related GitHub issues
- Create an issue if blocked
- Schedule quick call if complex

---

## Next Steps

1. ✅ Complete Quick Start setup (you are here)
2. 📖 Read `DEVELOPMENT_GUIDE.md` for Week 1 tasks
3. 🌳 Read `GITHUB_SETUP.md` for GitHub workflow
4. 🏗️ Read `ARCHITECTURE.md` for system design
5. 🚀 Start with Week 1 tasks
6. 📝 Contribute to documentation as you learn

---

**Ready to start? Run the commands above and let's build ShinyTooth! 🦷✨**

> Questions? Create an issue on GitHub or ask on team chat!
