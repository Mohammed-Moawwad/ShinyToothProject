# GitHub & Repository Setup Guide

## Initial Repository Setup

### 1. Create GitHub Repository

1. Go to [github.com](https://github.com) and sign in
2. Click "New" to create a new repository
3. Repository name: `ShinyToothDental`
4. Description: `Dental Clinic Management System - Team Project`
5. Choose: Public or Private (Private recommended for team project)
6. Initialize with: README.md
7. Click "Create repository"

### 2. Clone Repository Locally

```bash
cd c:\Users\[YourUsername]\OneDrive\Desktop\College\471-Semester\CS331\CS331Project\
git clone https://github.com/[YourOrg]/ShinyToothDental.git
cd ShinyToothDental
```

---

## Branch Protection Rules

### Set up on main branch:

1. Go to repository → Settings → Branches
2. Add branch protection rule for `main`:
   - ✅ Require a pull request before merging
   - ✅ Require at least 1 approval
   - ✅ Require status checks to pass
   - ✅ Dismiss stale pull request approvals
3. Add branch protection rule for `develop`:
   - ✅ Require a pull request before merging
   - ✅ Require at least 1 approval
   - ✅ Allow force pushes (optional)

---

## Create Initial Branches

```bash
# Create develop branch from main
git checkout -b develop
git push -u origin develop

# Protect the branches in GitHub settings (as above)
```

---

## Team Member Onboarding

### Each team member should:

1. **Clone the repository**

   ```bash
   git clone https://github.com/[YourOrg]/ShinyToothDental.git
   cd ShinyToothDental
   ```

2. **Configure Git locally**

   ```bash
   git config user.name "Your Name"
   git config user.email "your.email@university.edu"
   ```

3. **Set up Laravel project**

   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   ```

4. **Create local database**

   ```bash
   mysql -u root -p
   CREATE DATABASE shinytooth_dental_dev;
   ```

5. **Run migrations**

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Verify setup**
   ```bash
   php artisan serve
   npm run dev
   # Visit http://localhost:8000
   ```

---

## Daily Workflow

### Starting work on a feature:

```bash
# Update develop branch
git checkout develop
git pull origin develop

# Create feature branch
git checkout -b feature/your-feature-name

# Make changes and commit
git add .
git commit -m "feat: add your feature description"

# Push to GitHub
git push origin feature/your-feature-name
```

### Creating a Pull Request:

1. Go to GitHub repository
2. Click "Compare & pull request"
3. Set:
   - **Base:** develop
   - **Compare:** feature/your-feature-name
4. Fill in title and description
5. Add screenshots if UI changes
6. Click "Create pull request"

### Reviewing a Pull Request:

1. Go to "Pull requests" tab
2. Click on the PR to review
3. Click "Files changed" to see code
4. Add comments on specific lines
5. Request changes or approve
6. After approval, merging is done by the PR author

### Merging a PR:

1. Take team member feedback and make updates if needed
2. Ensure all checks pass (green checkmark)
3. Click "Squash and merge" or "Create a merge commit"
4. Delete branch after merging

---

## GitHub Issues & Project Board

### Create Issues for Tasks:

1. Go to "Issues" tab
2. Click "New issue"
3. Title: Clear, actionable task
4. Description: Detailed requirements
5. Labels: enhancement, bug, documentation, etc.
6. Assign: To team member working on it
7. Project: Associate with project

**Issue Title Examples:**

- `Create appointment booking form`
- `Implement user authentication`
- `Fix navigation menu on mobile`
- `Add appointment confirmation email`

### Using Project Board:

1. Go to "Projects" tab (create new if needed)
2. Create board with columns:
   - **To Do:** Not started
   - **In Progress:** Currently being worked on
   - **In Review:** PR created, awaiting review
   - **Done:** Completed and merged

3. Link issues to cards
4. Drag cards as work progresses

---

## Commit Message Guidelines

### Commit Message Format:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Type (required):

- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation changes
- `style` - Code style changes (formatting, missing semicolons, etc.)
- `refactor` - Code refactoring without feature changes
- `test` - Test additions or updates
- `chore` - Dependency updates, build changes

### Scope (optional):

- auth, appointment, dashboard, admin, etc.

### Subject (required):

- Imperative, present tense: "add" not "added"
- No period at the end
- Max 50 characters
- Clear and specific

### Examples:

```
feat(auth): add JWT token refresh endpoint

fix(appointment): resolve double-booking validation

docs(readme): update installation instructions

refactor(models): simplify appointment relationships

test(auth): add login endpoint tests

chore(deps): update laravel to 11.0
```

---

## Handling Merge Conflicts

### When conflicts occur:

1. **Pull latest changes**

   ```bash
   git fetch origin
   git pull origin develop
   ```

2. **Resolve conflicts**
   - Open conflicted files
   - Look for `<<<<<<<`, `=======`, `>>>>>>>`
   - Choose which code to keep
   - Remove conflict markers

3. **Complete merge**

   ```bash
   git add .
   git commit -m "merge: resolve conflicts with develop"
   git push origin feature/your-feature
   ```

4. **Update PR** - It will automatically update with resolved conflicts

---

## Syncing Fork with Upstream (if working from fork)

```bash
# Add upstream remote
git remote add upstream https://github.com/[OriginalOrg]/ShinyToothDental.git

# Fetch upstream changes
git fetch upstream

# Sync your fork
git checkout develop
git merge upstream/develop
git push origin develop
```

---

## Team Collaboration Tips

### 1. Keep branches updated

```bash
git fetch origin
git rebase origin/develop
# Instead of merging, keeps history cleaner
```

### 2. Review code before pushing

```bash
git diff origin/develop
# See what you're about to push
```

### 3. Use `.gitignore` properly

Never commit:

- `.env` files
- `/vendor/` directory
- `node_modules/`
- `.idea/` or `.vscode/`
- Database dumps
- Media files

### 4. Keep commits small and focused

- One feature per commit
- Each commit should be a logical unit
- Easier to review and revert if needed

### 5. Write meaningful PR descriptions

```markdown
## Description

Brief description of changes

## Why

Reason for these changes

## What was tested

How to test the changes

## Screenshots (if applicable)

Visual changes if any
```

### 6. Communication

- Use GitHub comments for technical discussions
- Use Slack/Discord for quick messages
- Link related issues: `Fixes #123` in PR description

---

## Useful GitHub Features

### Linking Issues to PRs:

In PR description, use:

- `Fixes #123` - Auto-closes issue when merged
- `Closes #123` - Same as Fixes
- `Resolves #123` - Same as Fixes

### Reviewing Code:

1. Click "Files changed"
2. Hover over line to add comment
3. Write comment
4. Click "Add comment"
5. "Approve" or "Request changes"

### Using GitHub Discussions:

- For general discussion about features
- Design decisions
- Best practices
- Questions and answers

---

## Security Best Practices

### Never commit:

- API keys or secrets (use `.env`)
- Database passwords
- JWT secrets
- Private keys
- Personal information

### Before pushing:

```bash
# Check for secrets
git diff --cached

# Verify you're not committing sensitive data
git log --oneline
```

### If you accidentally commit secrets:

1. **Remove from local history**

   ```bash
   git reset HEAD~1  # Undo last commit
   git restore --staged <file>  # Unstage file
   ```

2. **Add to `.gitignore`**
3. **Commit again without secrets**

4. **Force push only on your branch**
   ```bash
   git push origin <your-branch> --force-with-lease
   ```

### Never force push to develop or main!

---

## Troubleshooting

### I accidentally committed to main:

```bash
git reset HEAD~1
git checkout -b feature/my-feature
git checkout main
git reset --hard origin/main
```

### I need to undo my changes:

```bash
# Undo uncommitted changes
git checkout -- .

# Undo last commit (keep changes)
git reset HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1
```

### My PR has conflicts:

```bash
git fetch origin
git merge origin/develop
# Resolve conflicts
git add .
git commit -m "merge: resolve conflicts"
git push origin feature/your-feature
```

### I'm on the wrong branch:

```bash
git checkout <correct-branch>
git cherry-pick <commit-hash>  # Move your changes
```

---

## Weekly Sync Recommendations

### Team Meeting Template:

- **5 min:** What did each person complete?
- **5 min:** What are blockers?
- **5 min:** What's the plan for this week?
- **10 min:** Code review or technical discussion

### Async Status Updates:

Post in Slack/Discord:

```
Daily standup (e.g., Monday 9 AM):
✅ Completed: [Task]
🔄 In Progress: [Task]
🚧 Blocked by: [Issue]
📅 Next: [Task]
```

---

**Happy coding! Let the team reference this guide whenever working with GitHub. 🚀**
