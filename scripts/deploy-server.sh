#!/bin/bash
# Deploy otomatis e-supervisi di server cPanel Gavia (e-supervisiazzahroh.com).
#
# Dipanggil cron tiap 5 menit: cek apakah origin/main punya commit baru;
# kalau tidak ada, keluar diam-diam. Kalau ada (atau dipaksa via --force),
# tarik lalu sinkron ke struktur split server:
#   kode aplikasi -> ~/esupervisi   (web root TIDAK di sini)
#   aset publik   -> ~/public_html  (document root; index.php & symlink
#                                    storage milik server, TIDAK disentuh)
# Jangan tambah config:cache/route:cache di sini — OPcache host ini tidak
# invalidasi bootstrap/cache (lihat PANDUAN-DEPLOY.md bagian A7).
set -euo pipefail

REPO=/home/esupervi/repositories/e-supervisi
APPDIR=/home/esupervi/esupervisi
WEBROOT=/home/esupervi/public_html

cd "$REPO"
git fetch origin main
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/main)
if [ "$LOCAL" = "$REMOTE" ] && [ "${1:-}" != "--force" ]; then
    exit 0
fi

echo "[$(date '+%F %T')] Deploy mulai: $LOCAL -> $REMOTE"
git reset --hard origin/main

# 1. Kode repo -> folder aplikasi (state server dikecualikan dari sinkron & delete)
rsync -a --delete-after \
    --exclude=.git/ \
    --exclude=.env \
    --exclude=storage/ \
    --exclude=vendor/ \
    --exclude=node_modules/ \
    --exclude=bootstrap/cache/ \
    --exclude=composer.lock \
    --exclude=package-lock.json \
    "$REPO"/ "$APPDIR"/

# 2. Aset publik -> web root (index.php SENGAJA tidak di-copy)
rsync -a --delete-after "$APPDIR"/public/build/ "$WEBROOT"/build/
rsync -a "$APPDIR"/public/fonts/ "$WEBROOT"/fonts/
rsync -a "$APPDIR"/public/images/ "$WEBROOT"/images/
cp -f "$APPDIR"/public/.htaccess "$WEBROOT"/.htaccess
cp -f "$APPDIR"/public/robots.txt "$WEBROOT"/robots.txt
cp -f "$APPDIR"/public/favicon.ico "$WEBROOT"/favicon.ico

# 3. Migrasi DB + bersihkan cache
cd "$APPDIR"
php artisan migrate --force
php artisan view:clear
php artisan config:clear

echo "[$(date '+%F %T')] Deploy selesai di commit: $REMOTE"
