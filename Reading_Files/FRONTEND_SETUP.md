# Frontend Setup Guide — ShinyTooth Project

This guide explains how to install and configure the frontend tools for local development.

---

## Prerequisites

Before starting, ensure you have these installed on your machine:

1. **Node.js & npm**
   - Download from: https://nodejs.org/ (LTS version recommended)
   - Verify: Run `node --version` and `npm --version` in terminal

2. **PHP 8.2+**
   - Already installed via Laragon (should be in PATH)
   - Verify: Run `php --version`

3. **Composer**
   - Already installed via Laragon
   - Verify: Run `php C:\path\to\composer.phar --version`

4. **Git** (for cloning/pulling the repository)
   - Download from: https://git-scm.com/

---

## Step-by-Step Setup

### 1. Clone or Pull the Repository

```bash
git clone <repository-url>
cd ShinyToothProject-Fresh
cd app
```

Or if you already have it:

```bash
git pull origin main
cd app
```

---

### 2. Install Frontend Dependencies

Install all npm packages (Bootstrap, Alpine.js, Vite, etc.):

```bash
npm install
```

**What this does:**

- Downloads Bootstrap 5.3.0
- Downloads Alpine.js v3
- Downloads Vite (asset builder)
- Creates `node_modules/` folder (~500MB)
- Creates `package-lock.json`

**Expected output:**

```
added 142 packages in ~15s
```

---

### 3. Install Backend Dependencies (if not done)

Install PHP packages via Composer:

```bash
php C:\path\to\composer.phar install
```

Or if Composer is in PATH:

```bash
composer install
```

---

### 4. Set Up Environment

Copy `.env.example` to `.env` (if not already done):

```bash
copy .env.example .env
```

Update database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shinytooth
DB_USERNAME=root
DB_PASSWORD=Modtha@7
```

Generate app key:

```bash
php artisan key:generate
```

---

### 5. Build Frontend Assets

Compile CSS and JavaScript using Vite:

```bash
npm run build
```

**What this does:**

- Compiles `resources/css/app.css` → `public/build/assets/app-*.css`
- Compiles `resources/js/app.js` → `public/build/assets/app-*.js`
- Optimizes assets for production
- Creates `public/build/manifest.json` for Laravel to reference

**Expected output:**

```
✓ 112 modules transformed.
public/build/assets/app-DnUo9XXS.css    0.84 kB
public/build/assets/app-Dy-uvjOp.js   165.50 kB
✓ built in 1.14s
```

---

### 6. Run Database Migrations (first time only)

```bash
php artisan migrate
```

---

### 7. (Optional) Seed the Database

Load sample data:

```bash
php artisan db:seed
```

---

### 8. Start the Development Server

```bash
php artisan serve
```

Or with Laragon:

- Open Laragon → Click "Start All"
- Access http://ShinyToothProject-Fresh.test or http://127.0.0.1:8000

---

## During Development

### Rebuilding Assets After Changes

Whenever you edit `resources/css/app.css` or `resources/js/app.js`:

```bash
npm run build
```

Or use **watch mode** (rebuilds automatically on save):

```bash
npm run dev
```

Then keep it running in a separate terminal while you code.

---

## Common Issues & Fixes

### Issue: "npm: command not found"

**Solution:** Node.js isn't installed or not in PATH.

- Install from https://nodejs.org
- Restart your terminal after installing

### Issue: "node_modules folder missing"

**Solution:** Run `npm install` again to download all packages.

### Issue: Assets not loading (blank page or 404)

**Solution:** Run `npm run build` to compile assets.

### Issue: Livewire components not working

**Solution:** Ensure `@livewireStyles` and `@livewireScripts` are in the master layout ([resources/views/layouts/app.blade.php](app/resources/views/layouts/app.blade.php)).

### Issue: Bootstrap styles not applied

**Solution:**

1. Run `npm run build`
2. Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)
3. Check that `public/build/` folder exists with assets

### Issue: Port 8000 already in use

**Solution:** Use a different port:

```bash
php artisan serve --port=8001
```

---

## Frontend Stack Installed

| Tool          | Purpose                           | Version |
| ------------- | --------------------------------- | ------- |
| **Bootstrap** | CSS framework for styling         | 5.3.0   |
| **Alpine.js** | Lightweight JavaScript reactivity | v3      |
| **Livewire**  | Server-side reactivity for Blade  | v4.2.1  |
| **Vite**      | Asset bundler (replaces Mix)      | v6.4.1  |
| **Popper.js** | Positioning engine for dropdowns  | 2.11.8  |

---

## Next Steps

Once the frontend tools are installed:

1. Run `npm run build` to compile assets
2. Start the Laravel server with `php artisan serve`
3. Visit http://127.0.0.1:8000 to see the app
4. Begin building pages with Blade templates

---

## For Group Members

**Quick checklist before starting work:**

- [ ] Clone/pull latest code from `main` branch
- [ ] Run `npm install` (do this once per environment)
- [ ] Run `npm run build` (after any CSS/JS changes)
- [ ] Create `.env` from `.env.example` with correct DB credentials
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan serve`
- [ ] Verify http://127.0.0.1:8000 loads without errors

**Before pushing code:**

- [ ] Run `npm run build` (so compiled assets are up to date)
- [ ] Commit both `resources/` (source) AND `public/build/` (compiled)
- [ ] Push to your branch

---

**Questions?** Ask on the team Slack/Discord or add an issue to the repo.
