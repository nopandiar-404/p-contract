# Script untuk menjalankan Laravel + Vite dev server
# Jalankan script ini dengan PowerShell

$IP = "10.201.36.41"
$APP_PORT = 8000
$VITE_PORT = 5173

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "   MEMULAI DEVELOPMENT SERVER" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "IP Komputer: $IP" -ForegroundColor Green
Write-Host "App URL: http://$IP`:$APP_PORT" -ForegroundColor Green
Write-Host "Vite Dev Server: http://$IP`:$VITE_PORT" -ForegroundColor Green
Write-Host ""
Write-Host "Akses dari device lain menggunakan URL di atas" -ForegroundColor Yellow
Write-Host ""

# Cek apakah sudah ada node_modules
if (-not (Test-Path "node_modules")) {
    Write-Host "Installing Node dependencies..." -ForegroundColor Yellow
    npm install
}

# Buka tab baru untuk Laravel server
Write-Host "Memulai Laravel development server..." -ForegroundColor Cyan
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$PWD'; php artisan serve --host=0.0.0.0 --port=$APP_PORT"

# Tunggu sebentar
Start-Sleep -Seconds 2

# Buka tab baru untuk Vite dev server
Write-Host "Memulai Vite dev server..." -ForegroundColor Cyan
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$PWD'; npm run dev"

Write-Host ""
Write-Host "Kedua server sudah berjalan!" -ForegroundColor Green
Write-Host "Tekan Ctrl+C untuk menghentikan server" -ForegroundColor Yellow
