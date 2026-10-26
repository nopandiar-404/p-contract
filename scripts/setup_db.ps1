<#
PowerShell script to prepare local SQLite DB, run migrations and seeders for this Laravel project.
Usage (PowerShell):
  .\scripts\setup_db.ps1
This will:
- copy .env.example -> .env if missing
- run `composer install` if dependencies missing
- generate APP_KEY
- create `database/database.sqlite` if missing
- run `php artisan migrate --seed`
- clear config/cache
#>

try {
    Write-Host "Starting DB setup..." -ForegroundColor Cyan

    if (-not (Test-Path -Path ".env")) {
        if (Test-Path -Path ".env.example") {
            Copy-Item -Path ".env.example" -Destination ".env"
            Write-Host "Copied .env.example -> .env"
        } else {
            Write-Host "Warning: .env.example not found. Please create .env manually." -ForegroundColor Yellow
        }
    } else {
        Write-Host ".env already exists"
    }

    if (-not (Test-Path -Path "vendor")) {
        Write-Host "Running composer install..." -ForegroundColor Cyan
        composer install
    } else {
        Write-Host "Dependencies (vendor/) already present"
    }

    Write-Host "Generating APP_KEY if missing..." -ForegroundColor Cyan
    php artisan key:generate --ansi

    if (-not (Test-Path -Path "database")) {
        New-Item -ItemType Directory -Path "database" | Out-Null
    }

    $dbFile = Join-Path -Path "database" -ChildPath "database.sqlite"
    if (-not (Test-Path -Path $dbFile)) {
        New-Item -ItemType File -Path $dbFile | Out-Null
        Write-Host "Created database/database.sqlite"
    } else {
        Write-Host "database/database.sqlite already exists"
    }

    Write-Host "Running migrations and seeders..." -ForegroundColor Cyan
    php artisan migrate --seed --force

    Write-Host "Clearing config and cache..." -ForegroundColor Cyan
    php artisan config:clear
    php artisan cache:clear

    Write-Host "DB setup completed successfully." -ForegroundColor Green
    Write-Host "You can run the app: php artisan serve" -ForegroundColor Gray
    exit 0
} catch {
    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}
