# Vercel Deployment Instructions

## 🚀 Deploy ke Vercel

### 1. Install Vercel CLI (Opsional)
```bash
npm install -g vercel
```

### 2. Environment Variables yang Perlu Diset di Vercel Dashboard

Masuk ke **Project Settings > Environment Variables** dan tambahkan:

```
APP_NAME=e-supervisi
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-project.vercel.app

LOG_CHANNEL=stderr
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password

SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_DRIVER=array
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Database Setup

⚠️ **PENTING**: Vercel adalah serverless platform, kamu perlu database eksternal:

**Pilihan Database:**
- **PlanetScale** (MySQL serverless) - Recommended
- **Railway** (PostgreSQL/MySQL)
- **AWS RDS**
- **DigitalOcean Database**
- **Neon** (PostgreSQL serverless)

### 4. Generate APP_KEY

Di local, jalankan:
```bash
php artisan key:generate --show
```

Copy hasilnya dan paste ke environment variable `APP_KEY` di Vercel.

### 5. Deploy

**Via GitHub:**
1. Push code ke GitHub
2. Import project di https://vercel.com/new
3. Pilih repository `e-supervisi`
4. Root Directory: `./` (root)
5. Framework Preset: Other
6. Build Command: `bash vercel-build.sh`
7. Output Directory: `public`
8. Install Command: `composer install && npm install`

**Via CLI:**
```bash
vercel
```

### 6. Post-Deployment

Setelah deploy, jalankan migrations via Vercel CLI atau connection string:
```bash
# Dari local dengan DATABASE_URL production
php artisan migrate --force
```

## ⚠️ Keterbatasan Vercel untuk Laravel

1. **No persistent storage** - File uploads harus pakai cloud storage (S3, Cloudinary)
2. **Serverless functions** - Max execution time 30 detik
3. **Cold starts** - Request pertama bisa lambat
4. **Session storage** - Harus pakai database/cookie session

## 🔄 Alternative Platforms yang Lebih Cocok untuk Laravel

- **Railway** - Lebih mudah untuk Laravel, support persistent storage
- **Fly.io** - Full VM, support semua fitur Laravel
- **Laravel Forge + DigitalOcean** - Professional deployment
- **Heroku** - Classic PaaS

Apakah tetap mau lanjut dengan Vercel atau mau coba platform lain?
