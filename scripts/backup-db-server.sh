#!/bin/bash
# Backup database harian e-supervisi di server cPanel (pelengkap JetBackup mingguan).
#
# Membaca kredensial DB dari ~/esupervisi/.env, membuat dump ter-kompres
# (gzip) ber-timestamp ke ~/backups/, lalu menyimpan hanya 30 file terbaru
# (rotasi otomatis). Folder ~/backups/ di LUAR public_html -> tidak bisa
# diunduh publik. Dijalankan cron 1x/hari.
set -euo pipefail

APPDIR=/home/esupervi/esupervisi
BACKUP_DIR=/home/esupervi/backups
KEEP=30   # jumlah backup harian yang disimpan (30 hari terakhir)

# Ambil nilai satu variabel dari .env (tahan spasi & tanda kutip)
env_get() {
    grep -E "^$1=" "$APPDIR/.env" | head -1 | cut -d= -f2- | sed 's/^["'\'']//;s/["'\'']$//'
}

DB_DATABASE=$(env_get DB_DATABASE)
DB_USERNAME=$(env_get DB_USERNAME)
DB_PASSWORD=$(env_get DB_PASSWORD)
DB_HOST=$(env_get DB_HOST)
DB_HOST=${DB_HOST:-127.0.0.1}

mkdir -p "$BACKUP_DIR"
STAMP=$(date +%Y%m%d_%H%M%S)
OUTFILE="$BACKUP_DIR/${DB_DATABASE}_${STAMP}.sql.gz"

# Dump + kompres. --single-transaction: konsisten tanpa mengunci tabel.
mysqldump --single-transaction --quick --no-tablespaces \
    -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" \
    | gzip > "$OUTFILE"

echo "[$(date '+%F %T')] Backup dibuat: $OUTFILE ($(du -h "$OUTFILE" | cut -f1))"

# Rotasi: hapus yang lebih tua, sisakan $KEEP terbaru
cd "$BACKUP_DIR"
ls -1t "${DB_DATABASE}"_*.sql.gz 2>/dev/null | tail -n +$((KEEP + 1)) | while read -r old; do
    rm -f "$old"
    echo "[$(date '+%F %T')] Backup lama dihapus: $old"
done
