# PowerShell Script untuk mengubah php.ini
# Jalankan dengan Administrator
# .\fix_php_post_size.ps1

Write-Host "=== PHP Post Size Configuration Fix ===" -ForegroundColor Green
Write-Host ""

# Cari php.ini
$phpPaths = @(
    "C:\PHP\php.ini",
    "C:\xampp\php\php.ini",
    "C:\wamp\bin\php\php*\php.ini",
    "C:\laragon\bin\php\php-*\php.ini"
)

$phpIniPath = $null
foreach ($path in $phpPaths) {
    if (Test-Path $path -ErrorAction SilentlyContinue) {
        $phpIniPath = $path
        break
    }
}

if (-not $phpIniPath) {
    Write-Host "ERROR: php.ini tidak ditemukan!" -ForegroundColor Red
    Write-Host "Silakan lokasi php.ini secara manual dan ubah nilai berikut:" -ForegroundColor Yellow
    Write-Host "  post_max_size = 1G" -ForegroundColor Cyan
    Write-Host "  upload_max_filesize = 1G" -ForegroundColor Cyan
    Write-Host "  max_input_time = 300" -ForegroundColor Cyan
    Write-Host "  max_execution_time = 300" -ForegroundColor Cyan
    exit
}

Write-Host "✓ Found: $phpIniPath" -ForegroundColor Green
Write-Host ""

# Backup php.ini
$backup = "$phpIniPath.backup_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
Copy-Item $phpIniPath $backup
Write-Host "✓ Backup created: $backup" -ForegroundColor Green
Write-Host ""

# Read file
$content = Get-Content $phpIniPath

# Function untuk update atau add value
function Update-IniValue {
    param (
        [string]$content,
        [string]$key,
        [string]$value
    )
    
    # Cari line yang sudah ada
    if ($content -match "^\s*;?\s*$key\s*=") {
        # Replace existing line (commented atau tidak)
        $content = $content -replace "^\s*;?\s*$key\s*=.*$", "$key = $value"
        return $content, $true
    }
    return $content, $false
}

# Update values
$updates = @{
    'post_max_size' = '1G'
    'upload_max_filesize' = '1G'
    'max_input_time' = '300'
    'max_execution_time' = '300'
}

foreach ($key in $updates.Keys) {
    $value = $updates[$key]
    $content, $found = Update-IniValue -content $content -key $key -value $value
    
    if ($found) {
        Write-Host "✓ Updated: $key = $value" -ForegroundColor Green
    } else {
        Write-Host "⚠ WARNING: $key not found, please add manually" -ForegroundColor Yellow
    }
}

# Simpan file
Set-Content -Path $phpIniPath -Value $content
Write-Host ""
Write-Host "✓ php.ini updated successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Restart PHP-FPM or Web Server" -ForegroundColor White
Write-Host "2. For XAMPP: Restart Apache from control panel" -ForegroundColor White
Write-Host "3. For Laragon: Restart PHP from control panel" -ForegroundColor White
Write-Host "4. For Valet: Run 'valet restart'" -ForegroundColor White
Write-Host ""
Write-Host "Verify changes:" -ForegroundColor Cyan
Write-Host "  php -r \"echo ini_get('post_max_size');\"" -ForegroundColor Gray
