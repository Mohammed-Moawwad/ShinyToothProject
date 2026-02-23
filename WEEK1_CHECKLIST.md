# Week 1 Action Checklist - Get Everyone Ready

**Duration:** 5 working days  
**Goal:** All team members ready to start coding Week 2  
**Daily Time Commitment:** 4-6 hours

---

## 🎯 Week 1 Objectives

By the end of this week:

- ✅ Everyone has working development environment
- ✅ GitHub repository set up with proper branching
- ✅ Database schema finalized and documented
- ✅ API endpoints documented
- ✅ Team aligned on architecture
- ✅ First commits made and code review process tested

---

## 📅 Daily Breakdown

### **Day 1: Getting Started (Monday)**

#### Morning (2 hours):

- [ ] **Team meeting (30 min)**
  - Review all 4 documentation guides together
  - Confirm everyone understands the project scope
  - Discuss any questions about the 8-week plan
  - Decide on communication tools (Slack, Discord, etc.)

- [ ] **Individual setup (1.5 hours)**
  - Each person: Follow QUICKSTART.md
  - Install all prerequisites
  - Clone the repository
  - Get Laravel running locally
  - Verify database connection

#### Afternoon (2 hours):

- [ ] **Team verification meeting (1 hour)**
  - Everyone shows their working http://localhost:8000
  - Test that all can see homepage
  - Fix any environmental issues as a group
- [ ] **GitHub setup (1 hour)**
  - One person creates GitHub repository
  - Add all team members as collaborators
  - Create `develop` branch from `main`
  - Set up branch protection rules
  - Share GitHub link with team

---

### **Day 2: Architecture & Planning (Tuesday)**

#### Morning (2.5 hours):

- [ ] **Database schema finalization (1.5 hours)**
  - Review the database schema in ARCHITECTURE.md
  - Draw/diagram the schema (use Draw.io, Lucidchart, or Miro)
  - Confirm all relationships are correct
  - Discuss any modifications needed
  - Document final version

- [ ] **API endpoint specification (1 hour)**
  - Review all API endpoints in DEVELOPMENT_GUIDE.md sections:
    - Authentication endpoints
    - Appointment endpoints
    - Dentist endpoints
    - Patient endpoints
    - Admin endpoints
  - Document request/response examples
  - Clarify any questions

#### Afternoon (2 hours):

- [ ] **Technology stack confirmation (1 hour)**
  - Confirm Laravel version: 11+
  - Confirm Bootstrap version: 5+
  - Confirm PHP version: 8.2+
  - Test `php artisan --version` on all machines

- [ ] **Create project documentation folder (1 hour)**
  - In `docs/` folder, create:
    - `DATABASE_SCHEMA.md` (with diagram or detailed tables)
    - `API_ENDPOINTS.md` (with all endpoints and examples)
    - `TEAM_ROLES.txt` (who does what in Week 2)
  - Commit to GitHub

---

### **Day 3: Database & Structure (Wednesday)**

#### Morning (3 hours):

- [ ] **Laravel project structure review (1 hour)**
  - Verify folder structure matches DEVELOPMENT_GUIDE.md
  - Create missing directories:
    ```
    app/Models/
    app/Http/Controllers/Auth/
    app/Http/Controllers/Patient/
    app/Http/Controllers/Dentist/
    app/Http/Controllers/Admin/
    app/Http/Requests/
    app/Services/
    app/Traits/
    resources/views/pages/
    resources/views/auth/
    resources/views/components/
    ```
  - Commit folder structure

- [ ] **Create base Laravel configuration (2 hours)**
  - Copy `.env.example` to `.env`
  - Set `APP_DEBUG=true` for development
  - Set `JWT_SECRET` (if using JWT)
  - Configure mail settings (use Mailtrap for testing)
  - Document all `.env` variables
  - Test: `php artisan config:cache`
  - Commit `.env.example` (not `.env`)

#### Afternoon (2 hours):

- [ ] **Create initial models (2 hours)**
  - Create model files (just structure, no implementation):
    ```bash
    php artisan make:model User
    php artisan make:model Patient
    php artisan make:model Dentist
    php artisan make:model Appointment
    php artisan make:model Service
    php artisan make:model Rating
    # ... etc for all models
    ```
  - Don't implement yet, just create files
  - Add comment headers explaining relationships
  - Commit all models

---

### **Day 4: Git Workflow Practice (Thursday)**

#### Morning (2.5 hours):

- [ ] **Git workflow training (30 min)**
  - Each person reviews GITHUB_SETUP.md
  - Practice creating feature branch:
    ```bash
    git checkout develop
    git pull origin develop
    git checkout -b feature/week1-setup
    ```
  - Practice commit message format

- [ ] **Create initial migrations (2 hours)**
  - Create migrations (don't run yet):
    ```bash
    php artisan make:migration create_users_table
    php artisan make:migration create_patients_table
    php artisan make:migration create_dentists_table
    # ... etc for all tables
    ```
  - Add table structure (columns) but leave commented out
  - These will be finalized in Week 2
  - Stage and commit:
    ```bash
    git add database/migrations/
    git commit -m "feat: add migration files for all tables"
    git push origin feature/week1-setup
    ```

#### Afternoon (2 hours):

- [ ] **First Pull Request & Code Review (2 hours)**
  - Each person creates their first Pull Request:
    - Go to GitHub → "Create Pull Request"
    - Base: `develop`
    - Compare: `feature/week1-setup`
    - Add description of changes
    - Request review from another team member
  - Practice code review:
    - Reviewer checks "Files changed"
    - Add comment if needed (even if "Looks good!")
    - Click "Approve"
  - Merge PRs after approval:
    ```bash
    # Back in VS Code
    git switch develop
    git pull origin develop
    git branch -d feature/week1-setup
    ```

---

### **Day 5: Documentation & Planning (Friday)**

#### Morning (2 hours):

- [ ] **Finalize API documentation (1 hour)**
  - Create `docs/API_DOCUMENTATION.md`
  - Document request/response format for each endpoint
  - Include authentication header format
  - Include error response format
  - Add code examples (curl, JavaScript fetch)
  - Example:

    ````markdown
    ## POST /api/auth/login

    **Purpose:** Authenticate user and get JWT token

    **Request:**

    ```json
    {
      "email": "user@example.com",
      "password": "password123"
    }
    ```
    ````

    **Response (200):**

    ```json
    {
      "token": "eyJhbGciOiJIUzI1NiIs...",
      "user": { ... }
    }
    ```

    ```

    ```

- [ ] **Database schema documentation (1 hour)**
  - Create `docs/DATABASE_SCHEMA.md`
  - Document each table:
    - Table name
    - All columns with types
    - Primary keys
    - Foreign keys
    - Relationships
  - Create visual ER diagram
  - Save diagram as PNG in `docs/`

#### Afternoon (3 hours):

- [ ] **Team assignments for Week 2 (1 hour)**
  - Review tasks in DEVELOPMENT_GUIDE.md Week 2-3 section
  - Divide tasks among 4 people:
    - **Person A:** Database migrations + Unit tests
    - **Person B:** Authentication controller + Login API
    - **Person C:** User registration + Validation
    - **Person D:** Role-based access control
  - Document assignments in `WEEK2_ASSIGNMENTS.md`:

    ```markdown
    # Week 2 Task Assignments

    ## Person A (Database)

    - Create all migration files with proper structure
    - Set up seeders for initial data
    - Test all migrations with migrate/rollback

    ## Person B (Authentication)

    - Create AuthController with login method
    - Implement JWT token generation
    - Create authentication middlewares

    ## Person C (Registration)

    - Create registration controller
    - Implement form validation
    - Create patient/dentist records on registration

    ## Person D (RBAC)

    - Create role middleware
    - Set up authorization policies
    - Protect routes by role
    ```

- [ ] **Final team meetings (2 hours)**
  - **1-hour status meeting:**
    - Everyone reports what they completed this week
    - Show working development environment
    - Review any blockers
    - Confirm Week 2 assignments
  - **30-min Git workflow review:**
    - Everyone demonstrates:
      - Creating a feature branch
      - Making a commit
      - Pushing to GitHub
      - Creating a PR
      - Code review process
  - **30-min Q&A:**
    - Address any remaining questions
    - Clarify DEVELOPMENT_GUIDE.md confusions
    - Discuss communication schedule

---

## ✅ Week 1 Deliverables Checklist

By end of Friday, these should exist in GitHub:

- [ ] **Code/Setup**
  - [ ] Laravel project initialized
  - [ ] `.env.example` configured with all variables
  - [ ] Models created for all entities
  - [ ] Migration files created (structure defined)
  - [ ] Seeder files created (not run yet)

- [ ] **Documentation**
  - [ ] `DEVELOPMENT_GUIDE.md` (already provided)
  - [ ] `GITHUB_SETUP.md` (already provided)
  - [ ] `ARCHITECTURE.md` (already provided)
  - [ ] `QUICKSTART.md` (already provided)
  - [ ] `docs/DATABASE_SCHEMA.md` (team created)
  - [ ] `docs/API_DOCUMENTATION.md` (team created)
  - [ ] `WEEK2_ASSIGNMENTS.md` (who does what)

- [ ] **Git/GitHub**
  - [ ] GitHub repository created and accessible
  - [ ] `develop` branch created
  - [ ] Branch protection rules set
  - [ ] At least 1 PR per person (merged)
  - [ ] Code review process tested

- [ ] **Team Alignment**
  - [ ] All 4 people have working dev environment
  - [ ] Database schema confirmed
  - [ ] API endpoints documented
  - [ ] Week 2 tasks assigned
  - [ ] Communication tools set up

---

## 📊 Week 1 Success Metrics

✅ If all of these are TRUE, Week 1 is successful:

- [ ] All team members can clone repo and run `php artisan serve`
- [ ] GitHub repository has `develop` branch with at least 2 commits
- [ ] Each person created at least 1 feature branch and PR
- [ ] Database schema document agreed upon by team
- [ ] API endpoints documented in `docs/`
- [ ] No major blockers for Week 2
- [ ] Team can explain the 8-week plan
- [ ] At least 1 team meeting completed

---

## 🎯 Command Reference (Copy-Paste Ready)

### Repository Setup

```bash
# One person runs this:
cd c:\Users\[YourUsername]\OneDrive\Desktop\College\471-Semester\CS331\CS331Project\
mkdir ShinyToothDental
cd ShinyToothDental
composer create-project laravel/laravel .

# Then push to GitHub:
git remote add origin https://github.com/[YourOrg]/ShinyToothDental.git
git branch -M main
git push -u origin main
```

### For Each Team Member

```bash
# Clone and setup
git clone https://github.com/[YourOrg]/ShinyToothDental.git
cd ShinyToothDental
composer install
npm install
cp .env.example .env
php artisan key:generate

# Start development
php artisan serve              # Terminal 1
npm run dev                    # Terminal 2
```

### Creating Feature Branch

```bash
git checkout develop
git pull origin develop
git checkout -b feature/your-task-name
# ... make changes ...
git add .
git commit -m "feat: describe your changes"
git push origin feature/your-task-name
# Then create PR on GitHub
```

---

## 🚨 Common Issues This Week

### "Composer install fails"

- Check PHP version: `php -v`
- Clear composer cache: `composer clear-cache`
- Try again: `composer install`

### "Database connection fails"

- Check MySQL is running
- Check `.env` credentials match your setup
- Create database: `mysql -u root -p -e "CREATE DATABASE shinytooth_dental_dev;"`

### "npm run dev doesn't work"

- Delete `node_modules`: `rmdir /s node_modules`
- Delete `package-lock.json`
- Reinstall: `npm install`
- Try again: `npm run dev`

### "Git push rejected"

- Make sure you're on feature branch: `git branch`
- Pull latest: `git pull origin feature/your-branch`
- Try push again: `git push origin feature/your-branch`

### "Port 8000 already in use"

- Use different port: `php artisan serve --port=8001`
- Or kill process on port 8000 (if you know how)

**→ See QUICKSTART.md - Common Issues section for more**

---

## 📞 Need Help?

### By Day of Week:

- **Monday:** Database & structure questions
- **Tuesday:** API design questions
- **Wednesday:** Laravel setup help
- **Thursday:** Git workflow help
- **Friday:** General clarifications

### Escalation path:

1. Check relevant guide (DEVELOPMENT_GUIDE, GITHUB_SETUP, QUICKSTART)
2. Ask on team Slack/Discord
3. Create GitHub issue with details
4. Schedule quick call with team

---

## 🎓 Learning Objectives This Week

By end of Week 1, team should understand:

✅ What the final application will do  
✅ How the database is structured  
✅ What APIs will be built  
✅ How to work with Git and GitHub  
✅ How to create Laravel models  
✅ How to write database migrations  
✅ The overall project timeline  
✅ How the team will collaborate

---

## 📝 Notes Section

Use this to track week 1 progress:

```
Monday Progress:
-

Tuesday Progress:
-

Wednesday Progress:
-

Thursday Progress:
-

Friday Progress:
-

Blockers/Issues:
-

Lessons Learned:
-

For Next Week:
-
```

---

## 🎉 Week 1 Complete!

Once all checklist items are done:

1. Take a team photo/screenshot of your setup
2. Celebrate that foundation is ready!
3. Rest a bit (you'll need energy for Week 2)
4. Review Week 2-3 section of DEVELOPMENT_GUIDE.md
5. Prepare for backend coding next week

---

**Ready? Start with Day 1 Monday morning. You've got this! 💪**

_Questions? Create a GitHub issue or ask on team chat._
