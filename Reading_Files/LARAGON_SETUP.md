# ShinyTooth - Laragon Setup Guide

**Complete step-by-step guide for setting up ShinyTooth with Laragon on Windows.**

This document covers everything we learned during initial setup. Follow these steps to avoid 4+ hours of troubleshooting!

---

## Prerequisites

Before starting, ensure you have:

- [ ] **Laragon 6.0** installed (download from [laragon.org](https://laragon.org))
- [ ] **Project cloned** into `C:\Users\YourUsername\...laragon\www\ShinyToothProject\`
- [ ] **Git** configured on your machine

---

## Step 1: Install PHP 8.2 in Laragon

The default PHP 8.1 **will not work** — the Laravel dependencies require PHP 8.2+.

### Download PHP 8.2

1. Go to https://www.php.net/downloads
2. Click **"Old Archives"** (top right)
3. Find **PHP 8.2** (latest version, e.g., 8.2.30)
4. Download the **Zip file** under **"VS17 x64 Non Thread Safe"** section
   - ✅ Correct: `php-8.2.30-Win32-vs16-x64-nts.zip`
   - ❌ Wrong: tar.gz files (those are for Linux)

### Extract and Install

1. **Extract the ZIP** to get a folder like `php-8.2.30-Win32-vs16-x64`
2. **Move it to Laragon's PHP folder:**
   ```
   C:\Users\YourUsername\...laragon\bin\php\php-8.2.30-Win32-vs16-x64\
   ```
3. **Close Laragon completely**
4. **Reopen Laragon**
5. **Switch PHP version:**
   - Click **Menu** → **Tools** → **PHP** → **Version [php-8.x.xx]**
   - Select the new **php-8.2.30-Win32-vs16-x64**
6. **Close and reopen Laragon** to apply changes

**Verify it worked:**

- Laragon title bar should show: `php-8.2.30-Win32-vs16-x64`

---

## Step 2: Navigate to Your Project

Open **Laragon Terminal** (Laragon → Terminal button) and navigate to the app folder:

```powershell
cd C:\Users\YourUsername\OneDrive\Desktop\College\472-Semester\CS379\laragon\www\ShinyToothProject\app
```

---

## Step 3: Install PHP Dependencies

Run Composer install:

```powershell
C:\Users\YourUsername\OneDrive\Desktop\College\472-Semester\CS379\laragon\bin\composer\composer.bat install
```

**Note:** Replace `YourUsername` with your actual Windows username throughout.

This will download all Laravel packages and dependencies.

---

## Step 4: Create Environment File

From the `app` folder, copy the example environment file:

```powershell
copy .env.example .env
```

---

## Step 5: Configure Database (SQLite)

Edit `app\.env` file and locate the database section.

**Change from MySQL to SQLite:**

```ini
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ShinyToothDB
# DB_USERNAME=root
# DB_PASSWORD=
```

---

## Step 6: Enable SQLite Extension

**Critical step** — without this, migrations will fail!

1. Open this file in a text editor:

   ```
   C:\Users\YourUsername\...laragon\bin\php\php-8.2.30-Win32-vs16-x64\php.ini
   ```

2. Find the line:

   ```
   ;extension=pdo_sqlite
   ```

3. **Remove the semicolon** at the beginning:

   ```
   extension=pdo_sqlite
   ```

4. **Save the file**

5. **Restart Laragon** (close and reopen)

---

## Step 7: Run Database Migrations

Still in the `app` folder, run:

```powershell
C:\Users\YourUsername\OneDrive\Desktop\College\472-Semester\CS379\laragon\bin\php\php-8.2.30-Win32-vs16-x64\php.exe artisan migrate --force
```

**Expected output:**

```
INFO  Preparing database.

  Creating migration table .......................................... DONE

INFO  Running migrations.

  0001_01_01_000000_create_users_table ........................... DONE
  0001_01_01_000001_create_cache_table ........................... DONE
  0001_01_01_000002_create_jobs_table ............................ DONE
```

---

## Step 8: Generate Application Key

```powershell
C:\Users\YourUsername\OneDrive\Desktop\College\472-Semester\CS379\laragon\bin\php\php-8.2.30-Win32-vs16-x64\php.exe artisan key:generate
```

This creates the encryption key needed to run the application.

---

## Step 9: Start the Development Server

```powershell
C:\Users\YourUsername\OneDrive\Desktop\College\472-Semester\CS379\laragon\bin\php\php-8.2.30-Win32-vs16-x64\php.exe artisan serve
```

**Expected output:**

```
INFO  Server running on [http://127.0.0.1:8000].

Press Ctrl+C to stop the server
```

---

## Step 10: Verify It Works

1. **Open your browser** and go to: `http://localhost:8000`
2. You should see the **Laravel welcome page** with the Laravel logo
3. **Success!** Your project is running ✅

---

## Common Issues & Fixes

### Issue: `'composer' is not recognized`

**Solution:** Use the full path:

```powershell
C:\Users\YourUsername\...laragon\bin\composer\composer.bat install
```

### Issue: `could not find driver (SQLite)`

**Solution:** Make sure you enabled `extension=pdo_sqlite` in php.ini and restarted Laragon.

### Issue: `No application encryption key has been specified`

**Solution:** Run `artisan key:generate` to create the APP_KEY.

### Issue: MySQL authentication errors (for later)

**Note:** We used SQLite for initial setup. To switch to MySQL later:

1. Update `.env` with `DB_CONNECTION=mysql`
2. Create the database in Laragon's MySQL
3. Update DB_HOST, DB_USERNAME, DB_PASSWORD in `.env`
4. Run migrations again

---

## Summary of Key Paths

Replace `YourUsername` with your actual Windows username:

| What               | Path                                                                   |
| ------------------ | ---------------------------------------------------------------------- |
| **Laragon folder** | `C:\Users\YourUsername\...\laragon\`                                   |
| **PHP 8.2**        | `C:\Users\YourUsername\...\laragon\bin\php\php-8.2.30-Win32-vs16-x64\` |
| **Composer**       | `C:\Users\YourUsername\...\laragon\bin\composer\`                      |
| **Project**        | `C:\Users\YourUsername\...\laragon\www\ShinyToothProject\`             |
| **App folder**     | `C:\Users\YourUsername\...\laragon\www\ShinyToothProject\app\`         |

---

## Next Steps

Once the project is running:

1. ✅ Verify it loads on `http://localhost:8000`
2. 📖 Read `DEVELOPMENT_GUIDE.md` for Week 1 tasks
3. 🌳 Read `GITHUB_SETUP.md` for branching workflow
4. 👥 Share this guide with your team!

---

## Troubleshooting Help

If something doesn't work:

1. **Check Laragon status** — Make sure Apache & MySQL are running (green indicators)
2. **Verify PHP version** — Laragon title should show `php-8.2.30`
3. **Re-read this guide** — Most issues are in steps 1, 5, or 6
4. **Check exact paths** — Your local paths might differ slightly
5. **Ask the team** — One person solved it, others can too!

---

_Created: March 12, 2026_  
_For questions, refer to README_GUIDES.md or ARCHITECTURE.md_
