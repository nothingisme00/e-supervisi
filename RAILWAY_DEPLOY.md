# Railway Deployment Guide - e-supervisi

## 🚀 Deploy Laravel ke Railway

Railway adalah platform yang sangat cocok untuk Laravel karena:
- ✅ Support database MySQL/PostgreSQL built-in
- ✅ Persistent storage untuk file upload
- ✅ No cold starts
- ✅ Free tier $5/month credit
- ✅ Easy setup dengan GitHub integration

---

## 📋 Langkah-Langkah Deploy

### 1. Push Code ke GitHub

```bash
git add .
git commit -m "Add Railway deployment configuration"
git push origin main
```

### 2. Buat Akun Railway

1. Pergi ke https://railway.app
2. Sign up dengan GitHub account
3. Authorize Railway untuk akses repository

### 3. Create New Project

1. Klik **"New Project"**
2. Pilih **"Deploy from GitHub repo"**
3. Pilih repository **`nothingisme00/e-supervisi`**
4. Railway akan otomatis detect sebagai PHP project

### 4. Tambahkan MySQL Database

1. Di project dashboard, klik **"+ New"**
2. Pilih **"Database"**
3. Pilih **"Add MySQL"**
4. Railway akan auto-create database dan variables

### 5. Set Environment Variables

Klik tab **"Variables"** pada service Laravel kamu, tambahkan:

#### Required Variables:
```env
APP_NAME=e-supervisi
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database - Railway akan auto-inject ini dari MySQL service
DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

# Cache
CACHE_DRIVER=database
CACHE_PREFIX=

# Queue
QUEUE_CONNECTION=database

# Filesystem - Default local untuk Railway
FILESYSTEM_DISK=local

# Mail (optional - configure later)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@e-supervisi.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Connect Database Variables:
Railway akan otomatis create variables dari MySQL service. Pastikan di-link dengan klik:
1. Tab **"Variables"** pada Laravel service
2. Klik **"Reference Variables"**
3. Pilih MySQL service
4. Link: `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`

### 6. Generate APP_KEY

Di terminal local:
```bash
php artisan key:generate --show
```

Copy hasilnya (format: `base64:xxxxx...`) dan paste ke variable `APP_KEY` di Railway.

### 7. Deploy!

1. Klik **"Deploy"** atau push commit baru ke GitHub
2. Railway akan otomatis:
   - Install dependencies (composer & npm)
   - Build assets (Vite)
   - Run migrations
   - Start server
3. Tunggu sampai status **"Success"** ✅

### 8. Setup Public Domain

1. Pergi ke tab **"Settings"**
2. Scroll ke **"Networking"**
3. Klik **"Generate Domain"**
4. Railway akan create domain: `your-app.up.railway.app`
5. Update `APP_URL` variable dengan domain tersebut

### 9. Run Initial Setup (First Time Only)

Setelah deploy pertama, jalankan seed atau setup data awal via Railway CLI atau connect ke database:

**Via Railway CLI:**
```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link to project
railway link

# Run artisan commands
railway run php artisan db:seed
railway run php artisan storage:link
```

**Via MySQL Client:**
Connect ke database dan insert user admin manual jika perlu.

---

## 🔧 Troubleshooting

### Error: "No application encryption key has been specified"
- Pastikan `APP_KEY` sudah di-set dengan nilai dari `php artisan key:generate --show`

### Error: Database connection refused
- Pastikan MySQL service sudah running
- Check variables `DB_HOST`, `DB_PORT`, dll sudah ter-reference dengan benar

### Error: Storage link not created
- Run manual via Railway CLI: `railway run php artisan storage:link`

### Assets tidak muncul (CSS/JS tidak load)
- Check apakah build sukses: logs akan show "Building assets with Vite..."
- Pastikan `APP_URL` benar
- Force rebuild: `railway up --detach`

### File upload hilang setelah redeploy
- Railway memiliki persistent storage, file tidak akan hilang
- Check permission folder storage: `chmod -R 775 storage`

---

## 📊 Monitoring

### View Logs
```bash
railway logs
```

### Check Database
```bash
railway connect mysql
```

### Run Artisan Commands
```bash
railway run php artisan [command]
```

---

## 💰 Pricing

**Free Tier:**
- $5 credit per month
- Cukup untuk small project
- ~500 hours runtime

**Pro Plan:** $20/month
- $20 credit + usage-based billing
- Untuk production app

---

## 🔄 Update Project

Setiap kali push ke GitHub, Railway akan otomatis redeploy:

```bash
git add .
git commit -m "Update feature"
git push origin main
```

Railway akan:
1. Pull latest code
2. Run `railway-deploy.sh`
3. Restart server

---

## ✅ Post-Deployment Checklist

- [ ] APP_KEY sudah di-set
- [ ] Database connection sukses
- [ ] Migrations berhasil dijalankan
- [ ] Storage link created
- [ ] Assets (CSS/JS) load dengan benar
- [ ] Login admin works
- [ ] File upload works
- [ ] Public domain sudah di-set di APP_URL

---

## 🆘 Need Help?

- Railway Docs: https://docs.railway.app
- Laravel Docs: https://laravel.com/docs
- Railway Discord: https://discord.gg/railway

---

**Happy Deploying! 🚂**
