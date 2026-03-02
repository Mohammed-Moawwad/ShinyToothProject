# Composer Installation Guide for ShinyTooth Team

## Problem: Composer SSL Certificate Error

Some team members may encounter an SSL certificate error when trying to run `composer install`:

```
curl error 60 while downloading https://repo.packagist.org/packages.json:
SSL certificate problem: unable to get local issuer certificate
```

This is usually caused by:

- **Avast Antivirus** intercepting HTTPS connections
- **Windows Firewall** blocking package downloads
- **Corporate Proxy** requiring authentication
- **Local network issues** with certificate validation

---

## Solution 1: Use Docker (Recommended) ⭐

If your system has Docker installed, use this to install Composer dependencies:

```bash
# From the app directory
docker run --rm -v %cd%:/app -w /app composer:latest install
```

**Windows (PowerShell):**

```powershell
docker run --rm -v ${PWD}:/app -w /app composer:latest install
```

---

## Solution 2: Use WSL2 (Windows Subsystem for Linux)

If you have WSL2 enabled, install dependencies in a Linux terminal:

```bash
# Open WSL terminal
wsl

# Navigate to project
cd /mnt/c/Users/[YourUsername]/OneDrive/Desktop/College/471-Semester/CS331/CS331Project/ShinyToothProject/app

# Install dependencies
composer install
```

---

## Solution 3: GitHub Codespaces (Easiest Online)

1. Go to your GitHub repository
2. Click **Code → Codespaces → Create codespace on main**
3. In the terminal, run:

```bash
cd app
composer install
npm install  # Already done locally
```

4. Download the `vendor/` folder and `composer.lock` file back to your local machine

---

## Solution 4: Disable Antivirus (Temporary)

**For Avast Antivirus:**

1. Click Avast icon in system tray
2. Select **Virus chest**
3. Check if any Composer/SSL files are quarantined
4. Right-click and select **Restore**
5. Add exception for Composer in Avast settings:
   - Open Avast
   - Settings → Exceptions
   - Add `C:\Users\[YourUsername]\Downloads\PHPSetUp\composer.phar`

Then try:

```powershell
php "C:\Users\abomo\Downloads\PHPSetUp\composer.phar" install
```

---

## Solution 5: Use Different Network

Try installing dependencies on:

- **Mobile hotspot** from your phone
- **Public WiFi** (library, café)
- **University network** if available
- **VPN** that bypasses Avast interception

---

## Solution 6: Disable SSL Verification (Not Recommended)

As a last resort, you can disable SSL verification:

```powershell
# Set environment variable
$env:COMPOSER_ALLOW_SUPERUSER = 1

# Edit composer.json temporarily to allow insecure protocols
php "C:\Users\abomo\Downloads\PHPSetUp\composer.phar" config secure-http false

# Try install
php "C:\Users\abomo\Downloads\PHPSetUp\composer.phar" install
```

**⚠️ SECURITY WARNING:** This reduces security. Only use as a temporary measure and re-enable SSL after.

---

## Verification After Installation

Once `composer install` succeeds, you should see:

- ✅ `vendor/` directory created (150+ MB)
- ✅ `composer.lock` file created
- ✅ Zero errors in console

---

## Team Workflow

Since some members may struggle with Composer:

### Option A: One person installs, shares with team

1. **Developer A** successfully runs `composer install` at home/café
2. uploads `vendor/` and `composer.lock` to GitHub
3. Other developers pull those files

### Option B: Use GitHub Actions

1. Set up GitHub Actions to run `composer install` automatically
2. Download `vendor/` from Actions artifacts
3. All members use the same dependencies

### Option C: Use Requirements File

If vendor/ directory is too large (>200MB), Git can store only `composer.json` and `composer.lock`:

1. Add `vendor/` to `.gitignore` (it's already there)
2. Before starting development, download pre-built vendor from:
   - GitHub Releases (CI pipeline artifacts)
   - Team shared drive
   - Dropbox/OneDrive share

---

## Recommended Team Resolution

**For Week 1:**

1. Try Solution 1 (Docker) - simplest
2. If Docker not available, try Solution 3 (Codespaces) - no setup needed
3. If time is critical, skip Composer and use pre-built vendor archive

**For Week 2+:**

- Ensure at least one team member can run `composer install` successfully
- Configure CI/CD to build vendor/ automatically
- Share vendor/ via GitHub large file storage or releases

---

## Quick Checklist

Before reporting "Composer not working":

- [ ] Tried on different network (hotspot, café WiFi)
- [ ] Checked Avast exceptions and quarantine
- [ ] Attempted Docker solution (if Docker available)
- [ ] Tried GitHub Codespaces solution (easiest)
- [ ] Confirmed PHP is in PATH: `php --version` works
- [ ] Confirmed Composer is accessible: `php composer.phar --version` works

---

## Still Not Working?

If none of above solutions work:

1. **Document your exact error:**

   ```powershell
   php "C:\Users\abomo\Downloads\PHPSetUp\composer.phar" install 2>&1 | Out-File -FilePath error.log
   cat error.log
   ```

2. **Share with team:**
   - Screenshot of error
   - Output of `systeminfo` (check antivirus section)
   - Output of `ipconfig /all` (check proxy settings)

3. **Escalate to team:**
   - Post error in Discord/Slack
   - Create GitHub Issue with error logs
   - Schedule quick call to troubleshoot together

---

## Current Status (March 2, 2026)

✅ Laravel project extracted from GitHub (no Composer needed)  
✅ npm dependencies installed (Bootstrap 5, JavaScript tools)  
✅ .env file configured for ShinyTooth Dental  
❌ Composer dependencies NOT installed (blocked by Avast SSL)

**Next step:** Team members install vendor/ using one of solutions above before Week 1 coding starts.

---

_For more help, see GITHUB_SETUP.md or ask in team Discord!_
