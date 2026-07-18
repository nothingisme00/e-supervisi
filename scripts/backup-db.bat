@echo off
REM Wrapper untuk backup-db.ps1 — bisa diklik-dua-kali atau dipakai Task Scheduler.
REM Menjalankan skrip PowerShell dengan bypass execution policy (hanya untuk proses ini).
setlocal
set "SCRIPT_DIR=%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%SCRIPT_DIR%backup-db.ps1" %*
set "RC=%ERRORLEVEL%"
if not "%RC%"=="0" (
    echo.
    echo [backup-db] GAGAL dengan kode %RC%.
    if "%~1"=="" pause
)
exit /b %RC%
