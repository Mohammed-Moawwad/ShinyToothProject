# ShinyTooth - Railway Deployment Guide

## Step-by-Step Deployment Instructions

### **Prerequisites**

- GitHub account
- Railway account (https://railway.app)
- Project pushed to GitHub repository

---

## **Step 1: Push Project to GitHub**

If not already done:

```bash
cd "C:\Users\abomo\OneDrive\Desktop\College\472-Semester\CS379\laragon\www\ShinyToothProject-Fresh"

# Initialize git if not already done
git init
git add .
git commit -m "Initial commit: ShinyTooth dental services platform"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/ShinyTooth.git
git push -u origin main
```

**Replace `YOUR_USERNAME` with your actual GitHub username.**

---

## **Step 2: Set Up Railway Project**

1. **Go to Railway:** https://railway.app
2. **Login** with your Railway account
3. **Click** "New Project" button
4. **Select** "Deploy from GitHub"
5. **Connect GitHub** (authorize Railway to access your repos)
6. **Select** your `ShinyTooth` repository
7. Click **"Deploy Now"**

Railway will auto-detect it's a PHP/Laravel project and start building.

---

## **Step 3: Configure Environment Variables**

Once deployment starts, **go to Project Settings** and add these environment variables:

### **Critical Variables:**

```
APP_NAME=ShinyTooth
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:+JOM8nASmvAmhT2M7YMWROClngHs8NCsCTA5/Y+NxhA=
APP_URL=[YOUR_RAILWAY_URL] (e.g., https://shinytooth-production.up.railway.app)

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_LEVEL=debug

# Database (Railway will provide these)
DB_CONNECTION=mysql
DB_HOST=[RAILWAY_MYSQL_HOST]
DB_PORT=3306
DB_DATABASE=[RAILWAY_MYSQL_DB]
DB_USERNAME=[RAILWAY_MYSQL_USER]
DB_PASSWORD=[RAILWAY_MYSQL_PASSWORD]

SESSION_DRIVER=database
SESSION_SECURE_COOKIES=true
SESSION_SAME_SITE=lax
```

---

## **Step 4: Add MySQL Database to Railway**

1. **In your Railway Project**, click **"New"** button
2. **Select** "MySQL"
3. Railway will provision a MySQL database automatically
4. **Copy the connection variables** and add them to your app's environment variables

---

## **Step 5: Run Migrations**

The `Procfile` will automatically run migrations on deploy:

```
release: cd app && php artisan migrate --force
```

This will:

- ✅ Create all tables
- ✅ Run all migrations
- ✅ Seed initial data (if you add seeders)

---

## **Step 6: Build Assets**

Add this to your **Procfile** if needed:

```
prebuild: cd app && npm install && npm run build
```

Or in railway.json:

```json
{
  "builder": "nixpacks",
  "buildCommand": "cd app && npm install && npm run build",
  "startCommand": "cd app && php artisan serve --host=0.0.0.0 --port=$PORT"
}
```

---

## **Step 7: Access Your Deployed App**

Once deployment completes:

1. Go to **Railway Dashboard**
2. Your project will have a URL like: `https://shinytooth-production.up.railway.app`
3. Click the URL to view your live app!
4. Visit `/services` to see your services page

---

## **Step 8: Custom Domain (Optional)**

To use a custom domain like `shinytooth.com`:

1. **In Railway**, go to **Project Settings** → **Domains**
2. **Add Custom Domain**
3. **Update your domain's DNS records** to point to Railway
   - Add CNAME record pointing to Railway's domain
4. Railway will auto-provision SSL certificate

---

## **Troubleshooting**

### **If migrations fail:**

```
# Check logs in Railway dashboard
# Click "View Logs" under deployment
# Look for migration errors
```

### **If app won't start:**

- Check `Procfile` syntax
- Verify all env variables are set
- Check logs for PHP errors

### **If database connection fails:**

- Verify `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`
- Make sure MySQL service is running in Railway
- Check MySQL networking settings

### **If images don't load:**

- Images are stored in `public/images/services/`
- These folders are static files and should be served automatically
- Check that paths are correct in database

---

## **Post-Deployment Checklist**

- ✅ Database migrations completed
- ✅ App accessible at Railway URL
- ✅ Homepage loads correctly
- ✅ Services page with all 35 services displays
- ✅ Filters working (search, category, price)
- ✅ Service images loading
- ✅ Doctor avatars showing
- ✅ Login/registration pages working
- ✅ All links functional

---

## **Key Files for Deployment**

```
ShinyToothProject-Fresh/
├── Procfile                    (defines how to run app)
├── .railwayignore             (excludes unnecessary files)
├── .php-version               (specifies PHP 8.2)
├── app/
│   ├── .env                   (local development)
│   ├── database/
│   │   ├── migrations/        (all tables)
│   │   └── seeders/           (sample data)
│   ├── public/
│   │   ├── images/            (service photos, doctors)
│   │   └── build/             (compiled assets)
│   └── resources/
│       ├── views/             (Blade templates)
│       ├── css/               (styling)
│       └── js/                (JavaScript)
└── README.md
```

---

## **Need Help?**

- **Railway Docs:** https://docs.railway.app
- **Laravel Deployment:** https://laravel.com/docs/deployment
- **PHP Buildpack:** https://nixpacks.com/docs/php

---

**Happy Deploying! 🚀**
