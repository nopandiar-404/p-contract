@echo off
REM Script untuk menjalankan Laravel + Vite dev server di Windows

setlocal enabledelayedexpansion

set IP=10.201.36.41
set APP_PORT=8000
set VITE_PORT=5173

cls
echo.
echo ==========================================
echo    MEMULAI DEVELOPMENT SERVER
echo ==========================================
echo.
echo IP Komputer: %IP%
echo App URL: http://%IP%:%APP_PORT%
echo Vite Dev Server: http://%IP%:%VITE_PORT%
echo.
echo Akses dari device lain menggunakan URL di atas
echo.

REM Cek apakah sudah ada node_modules
if not exist "node_modules" (
    echo Installing Node dependencies...
    call npm install
)

REM Memulai Laravel server di tab baru
echo Memulai Laravel development server...
start cmd /k "php artisan serve --host=0.0.0.0 --port=%APP_PORT%"

REM Tunggu sebentar
timeout /t 2 /nobreak

REM Memulai Vite server di tab baru
echo Memulai Vite dev server...
start cmd /k "npm run dev"

echo.
echo Kedua server sudah berjalan!
echo Tekan Ctrl+C untuk menghentikan server
echo.
pause
