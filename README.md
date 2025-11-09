# E-Supervisi - Sistem Supervisi Pembelajaran

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.36.1-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

Sistem manajemen supervisi dan evaluasi pembelajaran berbasis web

[Features](#features) • [Installation](#installation) • [Usage](#usage) • [Deployment](#deployment)

</div>

---

## About

**E-Supervisi** adalah sistem informasi berbasis web untuk mengelola proses supervisi pembelajaran di sekolah. Sistem ini menyediakan platform digital untuk pengajuan supervisi oleh guru, evaluasi oleh kepala sekolah, dan manajemen pengguna oleh administrator.

### Key Features

- **Multi-role System**: Administrator, Kepala Sekolah, dan Guru dengan hak akses berbeda
- **Document Management**: Upload dan manajemen dokumen pembelajaran (RPP, Materi, Evaluasi)
- **Workflow Tracking**: Status tracking dari draft hingga completed dengan sistem revisi
- **Feedback System**: Komunikasi terstruktur antara guru dan evaluator
- **User Management**: CRUD operations untuk manajemen pengguna dan hak akses
- **Responsive Design**: Kompatibel dengan desktop, tablet, dan mobile devices

---

## System Requirements

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x
- NPM >= 9.x
- MySQL >= 8.0
- Git

---

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/nothingisme00/e-supervisi.git
cd e-supervisi
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

Create MySQL database:

```sql
CREATE DATABASE e_supervisi;
```

Update `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_supervisi
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders

```bash
# Run database migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed
```

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Start Development Server

```bash
php artisan serve
```

Access application at: `http://localhost:8000`

---

## Default Credentials

After running `php artisan db:seed`, a default admin user will be created:

| Role          | NIK                | Email                | Password  |
| ------------- | ------------------ | -------------------- | --------- |
| Administrator | `1234567890123456` | admin@esupervisi.com | `admin123` |

> ⚠️ **IMPORTANT**:
> - Change the default password immediately after first login!
> - Create other users (Kepala Sekolah, Guru) through the admin panel after logging in.
> - For production, consider using stronger passwords and enable two-factor authentication.

---

## Usage

### Administrator

- Manage users (create, read, update, delete)
- Assign roles and permissions
- Reset user passwords
- Monitor system activity via dashboard
- Configure system settings

### Kepala Sekolah

- Review supervision submissions from teachers
- Provide feedback and comments on documents
- Request revisions when needed
- Approve and complete supervision evaluations
- Monitor teacher performance through statistics

### Guru

- Create and submit supervision requests
- Upload learning documents (Lesson Plans, Materials, Evaluations)
- Track submission status (Draft, Submitted, Under Review, Completed)
- Respond to feedback and revision requests
- View evaluation history and comments

---

## Tech Stack

### Backend
- **Laravel 12.36.1** - PHP Framework
- **PHP 8.2+** - Server-side language
- **MySQL 8.0** - Relational database
- **Eloquent ORM** - Database abstraction

### Frontend
- **Tailwind CSS 4** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Blade Templates** - Laravel templating engine
- **Vite** - Frontend build tool

---

## Project Structure

```
e-supervisi/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Middleware/          # Custom middleware
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Database seeders
├── public/                  # Web root directory
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
├── routes/
│   └── web.php              # Application routes
└── storage/
    └── app/public/          # Uploaded files
```

---

## Security Features

- **Password Hashing** - bcrypt encryption for passwords
- **CSRF Protection** - Token validation on forms
- **SQL Injection Prevention** - Eloquent ORM with parameter binding
- **XSS Protection** - Input sanitization and output escaping
- **Authentication & Authorization** - Role-based access control
- **File Upload Validation** - MIME type and size restrictions

---

## Deployment

### Production Configuration

1. **Update Environment Variables**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

2. **Optimize Application**

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev
```

3. **Build Production Assets**

```bash
npm run build
```

4. **Set File Permissions**

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

5. **Configure Web Server**

Point document root to `public/` directory and configure URL rewriting.

**Nginx Example:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**Apache Example:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

---

## Troubleshooting

### Common Issues

**Database Connection Error**
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check database exists and user has proper permissions

**Seeding Error (Column not found)**
If you encounter `email_verified_at` column error during seeding:
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
composer dump-autoload

# Reset database completely
php artisan migrate:fresh --seed
```

**Permission Denied**
```bash
chmod -R 755 storage bootstrap/cache
```

**Assets Not Loading**
```bash
npm run build
php artisan storage:link
```

**Session/Cache Issues**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

Follow PSR-12 coding standards for PHP code.

### Database Refresh

```bash
# Reset database and re-run migrations
php artisan migrate:fresh --seed
```

---

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## License

This project is licensed under the **MIT License**. See [LICENSE](LICENSE) file for details.

---

## Support

If you encounter any issues or have questions:

- Open an issue on [GitHub Issues](https://github.com/nothingisme00/e-supervisi/issues)
- Check existing documentation and FAQs

---

## Acknowledgments

Built with:
- [Laravel](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)

---

<div align="center">

**E-Supervisi** © 2025

Developed by [nothingisme00](https://github.com/nothingisme00)

⭐ Star this repository if you find it helpful!

</div>
