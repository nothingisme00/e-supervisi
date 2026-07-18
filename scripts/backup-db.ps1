<#
    Backup otomatis database e-supervisi (MySQL/MariaDB).

    - Membaca setelan DB dari file .env di root project (DB_DATABASE, DB_HOST, dst).
    - Menyimpan dump ber-timestamp ke database/backups/.
    - Menyimpan hanya N backup terbaru (rotasi), sisanya dihapus otomatis.

    Jalankan manual:  powershell -ExecutionPolicy Bypass -File scripts\backup-db.ps1
    Atau lewat wrapper:  scripts\backup-db.bat
    Untuk dijadwalkan otomatis, lihat instruksi Task Scheduler di README backup.

    Opsi:
      -KeepCount <n>   berapa file backup terbaru yang disimpan (default 30)
#>
param(
    [int]$KeepCount = 30
)

$ErrorActionPreference = 'Stop'

# --- Lokasi ---------------------------------------------------------------
$ScriptDir   = Split-Path -Parent $MyInvocation.MyCommand.Path
$ProjectRoot = Split-Path -Parent $ScriptDir
$EnvFile     = Join-Path $ProjectRoot '.env'
$BackupDir   = Join-Path $ProjectRoot 'database\backups'

# --- Cari mysqldump -------------------------------------------------------
$MysqlDump = $null
$Candidates = @(
    'C:\xampp2\mysql\bin\mysqldump.exe',
    'C:\xampp\mysql\bin\mysqldump.exe'
)
foreach ($c in $Candidates) { if (Test-Path $c) { $MysqlDump = $c; break } }
if (-not $MysqlDump) {
    $cmd = Get-Command mysqldump -ErrorAction SilentlyContinue
    if ($cmd) { $MysqlDump = $cmd.Source }
}
if (-not $MysqlDump) {
    Write-Error "mysqldump.exe tidak ditemukan. Sesuaikan path di scripts\backup-db.ps1."
    exit 1
}

# --- Baca .env ------------------------------------------------------------
if (-not (Test-Path $EnvFile)) { Write-Error "File .env tidak ditemukan di $EnvFile"; exit 1 }

function Get-EnvValue([string]$key, [string]$default) {
    $line = Select-String -Path $EnvFile -Pattern "^\s*$key\s*=" | Select-Object -First 1
    if (-not $line) { return $default }
    $val = ($line.Line -split '=', 2)[1].Trim()
    $val = $val -replace '^"(.*)"$', '$1' -replace "^'(.*)'$", '$1'   # buang tanda kutip
    if ([string]::IsNullOrEmpty($val)) { return $default }
    return $val
}

$DbHost = Get-EnvValue 'DB_HOST'     '127.0.0.1'
$DbPort = Get-EnvValue 'DB_PORT'     '3306'
$DbName = Get-EnvValue 'DB_DATABASE' 'e_supervisi'
$DbUser = Get-EnvValue 'DB_USERNAME' 'root'
$DbPass = Get-EnvValue 'DB_PASSWORD' ''

# --- Siapkan folder & nama file ------------------------------------------
if (-not (Test-Path $BackupDir)) { New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null }
$Stamp    = Get-Date -Format 'yyyyMMdd_HHmmss'
$OutFile  = Join-Path $BackupDir "$($DbName)_$Stamp.sql"

Write-Host "[backup-db] Database : $DbName @ $DbHost`:$DbPort"
Write-Host "[backup-db] Tujuan   : $OutFile"

# --- Jalankan mysqldump ---------------------------------------------------
# Password dikirim lewat env var MYSQL_PWD agar tidak muncul di daftar proses.
$prevPwd = $env:MYSQL_PWD
$env:MYSQL_PWD = $DbPass
try {
    & $MysqlDump `
        --host=$DbHost --port=$DbPort --user=$DbUser `
        --single-transaction --routines --triggers --events `
        --default-character-set=utf8mb4 `
        --databases $DbName `
        --result-file="$OutFile"
    $code = $LASTEXITCODE
}
finally {
    $env:MYSQL_PWD = $prevPwd
}

if ($code -ne 0) {
    Write-Error "[backup-db] mysqldump gagal (exit $code). Backup dibatalkan."
    if (Test-Path $OutFile) { Remove-Item $OutFile -Force }
    exit $code
}

$sizeKB = [math]::Round((Get-Item $OutFile).Length / 1KB, 1)
if ($sizeKB -le 0) {
    Write-Error "[backup-db] File backup kosong. Ada yang salah."
    Remove-Item $OutFile -Force
    exit 1
}
Write-Host "[backup-db] OK - $sizeKB KB tersimpan."

# --- Rotasi: simpan hanya $KeepCount terbaru ------------------------------
$all = Get-ChildItem -Path $BackupDir -Filter "$($DbName)_*.sql" | Sort-Object LastWriteTime -Descending
if ($all.Count -gt $KeepCount) {
    $old = $all | Select-Object -Skip $KeepCount
    foreach ($f in $old) { Remove-Item $f.FullName -Force; Write-Host "[backup-db] Hapus lama: $($f.Name)" }
}
Write-Host "[backup-db] Selesai. Total backup tersimpan: $([math]::Min($all.Count, $KeepCount))."
