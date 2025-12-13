# 🖥️ SPESIFIKASI HOSTING UNTUK E-SUPERVISI

## 📊 Analisis Aplikasi

**Karakteristik Aplikasi E-Supervisi:**
- Framework: Laravel 12 (PHP 8.2+)
- Database: MySQL
- Session: Database-driven
- Cache: Database-driven
- File Upload: Ya (modul ajar, dokumen supervisi)
- Real-time Features: Livewire components
- Total Size Project: ~824 MB (termasuk vendor)
- Total Files: ~17,000 files

---

## 🎯 REKOMENDASI SPESIFIKASI HOSTING

### 📦 **TIER 1: MINIMAL (Untuk Testing/Development)**

**Tidak Direkomendasikan untuk Production**

| Spesifikasi | Minimal |
|-------------|---------|
| **CPU** | 1 Core (Shared) |
| **RAM** | 512 MB - 1 GB |
| **Storage** | 5 GB SSD |
| **Bandwidth** | 10 GB/bulan |
| **PHP Version** | 8.2+ |
| **MySQL** | 5.7+ / MariaDB 10.3+ |
| **Max Concurrent Users** | 5-10 users |

**Cocok untuk:**
- Testing deployment
- Development environment
- Sekolah sangat kecil (<50 siswa)

**Paket Niagahoster:**
- ❌ **Tidak direkomendasikan** - Terlalu lambat untuk production

---

### ✅ **TIER 2: RECOMMENDED (Untuk Sekolah Kecil-Menengah)**

**Rekomendasi Utama untuk Production**

| Spesifikasi | Recommended |
|-------------|-------------|
| **CPU** | 2 Cores (Shared/Dedicated) |
| **RAM** | 2 GB - 4 GB |
| **Storage** | 20 GB SSD |
| **Bandwidth** | Unlimited atau 50 GB/bulan |
| **PHP Version** | 8.2+ |
| **MySQL** | 5.7+ / MariaDB 10.3+ |
| **Database Size Limit** | 2 GB+ |
| **Max Concurrent Users** | 50-100 users |
| **Inode Limit** | 300,000+ |

**Cocok untuk:**
- Sekolah kecil-menengah (50-500 siswa)
- 10-30 guru aktif
- 5-10 admin/kepala sekolah
- Upload dokumen reguler

**Paket Niagahoster yang Cocok:**
- ✅ **Bayi** (Shared Hosting) - Rp 20.000/bulan
  - 1 Website
  - Unlimited Bandwidth
  - 500 MB Storage (⚠️ Mungkin kurang)
  
- ✅ **Pelajar** (Shared Hosting) - Rp 30.000/bulan
  - Unlimited Website
  - Unlimited Bandwidth
  - Unlimited Storage
  - **RECOMMENDED untuk mulai**

- ✅✅ **Personal** (Shared Hosting) - Rp 40.000/bulan
  - Unlimited Website
  - Unlimited Bandwidth
  - Unlimited Storage
  - Gratis Domain
  - **BEST VALUE**

**Estimasi Penggunaan:**
- Storage awal: ~1 GB (aplikasi)
- Storage growth: +100-500 MB/bulan (upload dokumen)
- Database size: 50-200 MB (tergantung aktivitas)
- RAM usage: 1-2 GB (peak hours)

---

### 🚀 **TIER 3: OPTIMAL (Untuk Sekolah Besar)**

**Untuk Performa Maksimal**

| Spesifikasi | Optimal |
|-------------|---------|
| **CPU** | 4 Cores (Dedicated) |
| **RAM** | 4 GB - 8 GB |
| **Storage** | 50 GB SSD |
| **Bandwidth** | Unlimited |
| **PHP Version** | 8.2+ |
| **MySQL** | 8.0+ / MariaDB 10.6+ |
| **Database Size Limit** | 5 GB+ |
| **Max Concurrent Users** | 200-500 users |
| **Inode Limit** | 500,000+ |

**Cocok untuk:**
- Sekolah besar (500+ siswa)
- 50+ guru aktif
- Multiple admin/kepala sekolah
- Upload dokumen intensif
- Akses simultan tinggi

**Paket Niagahoster yang Cocok:**
- ✅ **Cloud Hosting Bisnis** - Rp 150.000/bulan
  - 3 CPU Cores
  - 4 GB RAM
  - 80 GB SSD Storage
  - Unlimited Bandwidth
  
- ✅✅ **VPS KVM 1** - Rp 100.000/bulan
  - 2 CPU Cores
  - 2 GB RAM
  - 60 GB SSD Storage
  - Full Root Access
  - **RECOMMENDED untuk scaling**

**Estimasi Penggunaan:**
- Storage awal: ~1 GB (aplikasi)
- Storage growth: +500 MB - 2 GB/bulan
- Database size: 200 MB - 1 GB
- RAM usage: 2-4 GB (peak hours)

---

## 🔧 PERSYARATAN TEKNIS WAJIB

### ✅ **PHP Requirements**

```
✓ PHP Version: 8.2 atau lebih tinggi
✓ PHP Extensions:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - Filter
  - Hash
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Session
  - Tokenizer
  - XML
  - GD atau Imagick (untuk image processing)
  - JSON
  - ZIP
```

### ✅ **Database Requirements**

```
✓ MySQL 5.7+ atau MariaDB 10.3+
✓ InnoDB storage engine
✓ UTF8MB4 character set support
✓ Max connections: 100+
```

### ✅ **Server Configuration**

```
✓ max_execution_time: 300 (5 menit)
✓ max_input_time: 300
✓ memory_limit: 256M - 512M
✓ post_max_size: 50M - 100M
✓ upload_max_filesize: 50M - 100M
✓ max_file_uploads: 20
```

---

## 📈 ESTIMASI KEBUTUHAN BERDASARKAN SKALA

### 🏫 **Sekolah Kecil (50-200 siswa)**

| Resource | Kebutuhan |
|----------|-----------|
| CPU | 1-2 Cores |
| RAM | 2 GB |
| Storage | 10-20 GB |
| Concurrent Users | 20-50 |
| **Paket Recommended** | **Niagahoster Pelajar/Personal** |
| **Biaya/bulan** | **Rp 30.000 - 40.000** |

**Aktivitas Harian:**
- 10-20 guru login
- 5-10 supervisi per hari
- 20-50 upload dokumen per bulan

---

### 🏫 **Sekolah Menengah (200-500 siswa)**

| Resource | Kebutuhan |
|----------|-----------|
| CPU | 2-3 Cores |
| RAM | 4 GB |
| Storage | 30-50 GB |
| Concurrent Users | 50-100 |
| **Paket Recommended** | **Niagahoster Personal/Bisnis** |
| **Biaya/bulan** | **Rp 40.000 - 80.000** |

**Aktivitas Harian:**
- 30-50 guru login
- 10-20 supervisi per hari
- 50-100 upload dokumen per bulan

---

### 🏫 **Sekolah Besar (500+ siswa)**

| Resource | Kebutuhan |
|----------|-----------|
| CPU | 4+ Cores |
| RAM | 8 GB |
| Storage | 50-100 GB |
| Concurrent Users | 100-200+ |
| **Paket Recommended** | **Cloud Hosting / VPS** |
| **Biaya/bulan** | **Rp 100.000 - 200.000** |

**Aktivitas Harian:**
- 50+ guru login
- 20-50 supervisi per hari
- 100-200 upload dokumen per bulan

---

## 💰 REKOMENDASI PAKET NIAGAHOSTER

### 🥉 **Budget: Shared Hosting "Pelajar"**
- **Harga**: ~Rp 30.000/bulan (promo)
- **CPU**: Shared (1-2 cores)
- **RAM**: ~2 GB
- **Storage**: Unlimited
- **Cocok untuk**: Sekolah kecil, testing production

### 🥈 **Recommended: Shared Hosting "Personal"**
- **Harga**: ~Rp 40.000/bulan (promo)
- **CPU**: Shared (2 cores)
- **RAM**: ~2-4 GB
- **Storage**: Unlimited
- **Bonus**: Gratis domain
- **Cocok untuk**: Sekolah kecil-menengah

### 🥇 **Best Performance: VPS KVM 1**
- **Harga**: ~Rp 100.000/bulan
- **CPU**: 2 Dedicated Cores
- **RAM**: 2 GB
- **Storage**: 60 GB SSD
- **Cocok untuk**: Sekolah menengah-besar, full control

---

## ⚡ OPTIMASI PERFORMA

### 🔧 **Untuk Shared Hosting**

1. **Enable OPcache** (PHP opcode caching)
2. **Database Caching** (sudah diset di .env)
3. **Route Caching**: `php artisan route:cache`
4. **Config Caching**: `php artisan config:cache`
5. **View Caching**: `php artisan view:cache`
6. **Lazy Loading** untuk gambar
7. **Compress images** sebelum upload

### 🚀 **Untuk VPS/Cloud**

Semua di atas, plus:
1. **Redis/Memcached** untuk session & cache
2. **Queue Workers** untuk background jobs
3. **CDN** untuk static assets
4. **Nginx** sebagai web server
5. **PHP-FPM** optimization
6. **MySQL Query Optimization**

---

## 📊 MONITORING & MAINTENANCE

### 📈 **Metrics to Monitor**

- **CPU Usage**: Jangan melebihi 80% sustained
- **RAM Usage**: Jangan melebihi 85%
- **Storage**: Sisakan minimal 20% free space
- **Database Size**: Monitor growth rate
- **Response Time**: Target <2 detik
- **Error Rate**: Target <1%

### 🔄 **Regular Maintenance**

- **Weekly**: Backup database
- **Monthly**: Clean old logs (`storage/logs/`)
- **Monthly**: Optimize database tables
- **Quarterly**: Review storage usage
- **Yearly**: Evaluate hosting upgrade

---

## ✅ KESIMPULAN & REKOMENDASI

### 🎯 **Untuk Sebagian Besar Sekolah**

**Paket: Niagahoster Shared Hosting "Personal"**
- ✅ Harga terjangkau (~Rp 40.000/bulan)
- ✅ Unlimited storage & bandwidth
- ✅ Cukup untuk 50-200 concurrent users
- ✅ Mudah di-manage via cPanel
- ✅ Support 24/7

**Spesifikasi Efektif:**
- CPU: 2 Cores (shared)
- RAM: 2-4 GB
- Storage: Unlimited (fair usage)
- Bandwidth: Unlimited

### 🚀 **Untuk Scaling Future**

Jika aplikasi berkembang:
1. **Mulai dengan**: Shared Hosting Personal
2. **Upgrade ke**: Cloud Hosting (jika >100 concurrent users)
3. **Upgrade ke**: VPS (jika >200 concurrent users atau butuh custom config)

---

## 🔗 LINK PAKET NIAGAHOSTER

- **Shared Hosting**: https://www.niagahoster.co.id/hosting-murah
- **Cloud Hosting**: https://www.niagahoster.co.id/cloud-hosting
- **VPS**: https://www.niagahoster.co.id/vps-murah

---

**💡 Pro Tip**: Mulai dengan paket **Personal** (Rp 40.000/bulan). Ini memberikan room untuk growth tanpa overpaying di awal. Monitor usage selama 1-3 bulan pertama, lalu upgrade jika diperlukan.
