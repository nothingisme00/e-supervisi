# 📚 E-Supervisi - Sistem Supervisi Pembelajaran

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.36.1-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**Sistem Manajemen Supervisi Pembelajaran Berbasis Web**

[Demo](#) • [Dokumentasi](#fitur-utama) • [Instalasi](#instalasi) • [Kontribusi](#kontribusi)

</div>

---

## 📋 Deskripsi

**E-Supervisi** adalah sistem informasi berbasis web yang dirancang untuk memudahkan proses supervisi dan evaluasi pembelajaran di sekolah. Sistem ini memungkinkan guru untuk mengajukan supervisi, kepala sekolah untuk melakukan evaluasi, dan administrator untuk mengelola seluruh sistem dengan efisien.

### 🎯 Tujuan Sistem

-   Digitalisasi proses supervisi pembelajaran
-   Meningkatkan efisiensi evaluasi kinerja guru
-   Mempermudah monitoring dan pelaporan
-   Menyediakan feedback yang terstruktur dan terukur

---

## ✨ Fitur Utama

### 👨‍🏫 **Fitur Guru**

-   ✅ Dashboard dengan statistik supervisi
-   📝 Pengajuan supervisi baru
-   📤 Upload dokumen pembelajaran (RPP, Materi, Evaluasi)
-   🔄 Revisi dokumen berdasarkan feedback
-   📊 Tracking status supervisi (Draft, Submitted, Under Review, Completed)
-   💬 Melihat feedback dari Kepala Sekolah

### 👔 **Fitur Kepala Sekolah**

-   📈 Dashboard monitoring semua supervisi
-   👁️ Review dokumen supervisi guru
-   ✍️ Memberikan feedback dan komentar
-   ⚠️ Request revisi dokumen
-   ✅ Menandai supervisi selesai ditinjau
-   📊 Statistik evaluasi per guru dan mata pelajaran

### 🛡️ **Fitur Administrator**

-   👥 Manajemen pengguna (CRUD users)
-   🔐 Pengaturan role dan hak akses
-   🔄 Reset password pengguna
-   ✏️ Edit data profil pengguna
-   📊 Dashboard overview sistem
-   🔍 Filter dan sorting data

### 🎨 **Fitur UI/UX**

-   🌓 Dark Mode / Light Mode
-   📱 Fully Responsive (Mobile, Tablet, Laptop)
-   🎯 Modern & Clean Interface
-   ⚡ Smooth Animations & Transitions
-   🔔 Real-time Notifications
-   📥 Pull-to-Refresh (Mobile)
-   🔄 Auto-hide Header on Scroll

---

## 🛠️ Teknologi yang Digunakan

### Backend

-   **Laravel 12.36.1** - PHP Framework
-   **PHP 8.2+** - Programming Language
-   **MySQL 8.0** - Database
-   **Laravel Sanctum** - API Authentication
-   **Intervention Image** - Image Processing

### Frontend

-   **Tailwind CSS 4** - CSS Framework
-   **Alpine.js** - JavaScript Framework
-   **Vite** - Build Tool
-   **Blade Templates** - Templating Engine

### Tools & Libraries

-   **Composer** - PHP Dependency Manager
-   **NPM** - JavaScript Package Manager
-   **Git** - Version Control

---

## 📦 Instalasi

### Prasyarat

Pastikan sistem Anda sudah terinstall:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MySQL 8.0
-   Git

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone https://github.com/nothingisme00/e-supervisi.git
    cd e-supervisi
    ```

2. **Install Dependencies**

    ```bash
    # Install PHP dependencies
    composer install

    # Install JavaScript dependencies
    npm install
    ```

3. **Konfigurasi Environment**

    ```bash
    # Copy file .env
    cp .env.example .env

    # Generate application key
    php artisan key:generate
    ```

4. **Setup Database**

    Buat database MySQL:

    ```sql
    CREATE DATABASE e_supervisi;
    ```

    Edit file `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=e_supervisi
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Migrasi & Seeder**

    ```bash
    # Jalankan migrasi
    php artisan migrate

    # Jalankan seeder (opsional)
    php artisan db:seed
    ```

6. **Storage Link**

    ```bash
    php artisan storage:link
    ```

7. **Build Assets**

    ```bash
    npm run build
    ```

8. **Jalankan Aplikasi**

    ```bash
    # Development
    php artisan serve

    # Di terminal lain untuk development assets
    npm run dev
    ```

9. **Akses Aplikasi**

    Buka browser dan akses: `http://localhost:8000`

---

## 👤 Default User Credentials

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

| Role           | NIK                | Password   | Keterangan          |
| -------------- | ------------------ | ---------- | ------------------- |
| Administrator  | `1234567890123456` | `password` | Akses penuh sistem  |
| Kepala Sekolah | `1234567890123457` | `password` | Evaluasi supervisi  |
| Guru           | `1234567890123458` | `password` | Pengajuan supervisi |

> ⚠️ **PENTING**: Ganti password default setelah login pertama kali!

---

## 📁 Struktur Folder

```
e-supervisi/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controllers
│   │   └── Middleware/      # Custom Middleware
│   ├── Models/              # Eloquent Models
│   ├── Services/            # Business Logic Services
│   └── Helpers/             # Helper Functions
├── database/
│   ├── migrations/          # Database Migrations
│   └── seeders/             # Database Seeders
├── public/
│   ├── storage/             # Public Storage (symlink)
│   └── build/               # Compiled Assets
├── resources/
│   ├── views/               # Blade Templates
│   ├── css/                 # CSS Files
│   └── js/                  # JavaScript Files
├── routes/
│   └── web.php              # Web Routes
└── storage/
    └── app/public/          # File Storage
```

---

## 🔐 Keamanan

-   ✅ Password Hashing (bcrypt)
-   ✅ CSRF Protection
-   ✅ SQL Injection Prevention (Eloquent ORM)
-   ✅ XSS Protection
-   ✅ Authentication & Authorization
-   ✅ Secure File Upload (validation & sanitization)

---

## 🚀 Deployment

### Production Setup

1. **Set Environment Production**

    ```env
    APP_ENV=production
    APP_DEBUG=false
    ```

2. **Optimize Application**

    ```bash
    # Cache configuration
    php artisan config:cache

    # Cache routes
    php artisan route:cache

    # Cache views
    php artisan view:cache

    # Optimize autoloader
    composer install --optimize-autoloader --no-dev
    ```

3. **Build Production Assets**

    ```bash
    npm run build
    ```

4. **Set Permissions**
    ```bash
    chmod -R 755 storage bootstrap/cache
    ```

---

## 📸 Screenshots

### Login Page

![Login Page](https://via.placeholder.com/800x450/4F46E5/FFFFFF?text=Login+Page)

### Dashboard Guru

![Dashboard Guru](https://via.placeholder.com/800x450/10B981/FFFFFF?text=Dashboard+Guru)

### Dashboard Kepala Sekolah

![Dashboard Kepala](https://via.placeholder.com/800x450/F59E0B/FFFFFF?text=Dashboard+Kepala+Sekolah)

### Kelola Pengguna

![Kelola Pengguna](https://via.placeholder.com/800x450/EF4444/FFFFFF?text=Kelola+Pengguna)

---

## 🐛 Bug Report & Feature Request

Jika Anda menemukan bug atau ingin mengajukan fitur baru, silakan buat issue di:

👉 [GitHub Issues](https://github.com/nothingisme00/e-supervisi/issues)

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Untuk berkontribusi:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini menggunakan lisensi **MIT License**. Lihat file [LICENSE](LICENSE) untuk detail.

---

## 👨‍💻 Developer

Dikembangkan dengan ❤️ oleh **[nothingisme00](https://github.com/nothingisme00)**

### Support

Jika Anda merasa proyek ini bermanfaat, berikan ⭐️ pada repository ini!

---

## 📞 Kontak

-   **GitHub**: [@nothingisme00](https://github.com/nothingisme00)
-   **Email**: [Email]
-   **Project Link**: [https://github.com/nothingisme00/e-supervisi](https://github.com/nothingisme00/e-supervisi)

---

<div align="center">

**© 2025 E-Supervisi. All Rights Reserved.**

Made with ❤️ using Laravel & Tailwind CSS

</div>
