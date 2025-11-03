# E-Supervisi

Sistem Elektronik Supervisi untuk manajemen supervisi dan evaluasi pembelajaran di sekolah.

## About E-Supervisi

E-Supervisi adalah aplikasi web berbasis Laravel yang dirancang untuk memudahkan proses supervisi dan evaluasi pembelajaran di sekolah. Aplikasi ini menyediakan platform digital untuk kepala sekolah dan guru dalam melakukan supervisi, dokumentasi, dan evaluasi proses pembelajaran.

### Fitur Utama

- **Dashboard Admin** - Manajemen pengguna (Admin, Guru, Kepala Sekolah)
- **Dashboard Guru** - Pengelolaan supervisi dan proses pembelajaran
- **Dashboard Kepala Sekolah** - Monitoring dan evaluasi supervisi
- **Sistem Login Multi-Role** - Autentikasi terpisah untuk setiap role
- **Manajemen Tingkatan** - Support untuk berbagai tingkat pendidikan
- **Responsive Design** - Optimal di desktop dan mobile

## Teknologi

- Laravel 12.x
- PHP 8.2+
- MySQL/MariaDB
- Tailwind CSS
- Vite

## Instalasi

1. Clone repository
```bash
git clone https://github.com/nothingisme00/e-supervisi.git
cd e-supervisi
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Setup database
```bash
# Edit .env dengan kredensial database Anda
php artisan migrate
php artisan db:seed
```

5. Build assets
```bash
npm run dev
# atau untuk production:
npm run build
```

6. Jalankan aplikasi
```bash
php artisan serve
```

## Branch Management

⚠️ **Penting**: Repository ini menggunakan branch `main` sebagai branch utama.

Semua commit terbaru ada di branch `main`. Branch lainnya (develop, master, feature/crud-admin) adalah branch lama yang akan dihapus.

Untuk instruksi pembersihan branch, lihat [BRANCH_CLEANUP_INSTRUCTIONS.md](BRANCH_CLEANUP_INSTRUCTIONS.md)

Atau jalankan script otomatis:
```bash
# Buat script executable terlebih dahulu
chmod +x cleanup-branches.sh

# Jalankan script
./cleanup-branches.sh
```

## Default Users

Setelah seeding, Anda dapat login dengan:

**Admin**
- Email: admin@example.com
- Password: password

## Kontribusi

Untuk berkontribusi ke project ini:
1. Fork repository
2. Buat branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request ke branch `main`

## License

Project ini adalah open-sourced software dengan lisensi [MIT license](https://opensource.org/licenses/MIT).
