# ShinyTooth - Simple Setup Guide for Team Members

**Just follow these steps. It's simple!**

---

## What You Need to Install

1. PHP 8.2
2. MySQL (if you don't have it)
3. Composer
4. Node.js
5. GitHub Desktop (already have? skip this)

---

## ✅ Step 1: Install PHP 8.2

### Download

Go to: https://windows.php.net/download/

Look for: **"VS17 x64 Thread Safe"** → Click **"Zip"** (about 32MB)

### Extract

1. Right-click the ZIP file
2. Click "Extract All..."
3. Extract to: `C:\Users\[YourUsername]\Downloads\PHPSetUp`
   - **Important:** Extract directly to PHPSetUp folder, not a subfolder

### Verify

Open PowerShell and run:

```powershell
C:\Users\[YourUsername]\Downloads\PHPSetUp\php\php.exe -v
```

Should show: `PHP 8.2.x` ✅

---

## ✅ Step 2: Install Composer

### Download

Open PowerShell **as Admin** and run:

```powershell
cd C:\Users\[YourUsername]\Downloads\PHPSetUp
Invoke-WebRequest -Uri https://getcomposer.org/composer-stable.phar -OutFile composer.phar
```

Wait for it to download.

### Verify

```powershell
php composer.phar --version
```

Should show: `Composer version 2.x.x` ✅

---

## ✅ Step 3: Install Node.js

### Download

Go to: https://nodejs.org/

Download: **LTS version** (left button)

### Install

1. Run the installer
2. Click **"Next"** → **"Install"** → **"Finish"**
3. **Restart your computer** (important!)

### Verify

Open PowerShell (new one after restart):

```powershell
node -v
npm -v
```

Should show versions ✅

---

## ✅ Step 4: Check MySQL

You should already have MySQL installed.

Test it:

```powershell
mysql --version
```

If it works, great! If not, ask the team.

---

## ✅ Step 5: Clone the Repository

1. Open **GitHub Desktop**
2. Click **"File"** → **"Clone Repository"**
3. Paste repo URL
4. Click **"Clone"**

---

## ✅ All Set!

You now have:

- ✅ PHP 8.2
- ✅ MySQL
- ✅ Composer
- ✅ Node.js
- ✅ Repository cloned

---

## 🚀 Next: Run the Project

```powershell
cd [your-project-folder]
composer install
npm install
php artisan serve
```

Open browser: `http://localhost:8000`

---

## 🆘 Troubleshooting

### PHP not working?

Make sure you extracted to: `C:\Users\[YourUsername]\Downloads\PHPSetUp`
And extracted directly (not into a subfolder)

### Composer not working?

Use full path: `php C:\Users\[YourUsername]\Downloads\PHPSetUp\composer.phar --version`

### Node.js not working?

Restart your computer after installing Node.js

### Still stuck?

Ask the team on Slack/Discord - someone has solved it!

---

**That's it! Questions? Ask the team!** 👍
