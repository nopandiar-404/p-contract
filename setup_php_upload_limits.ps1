# PowerShell Script untuk Auto-Fix PostTooLargeException
# Jalankan: .\setup_php_upload_limits.ps1
# Requires: Administrator rights

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║   PHP Upload Limits Auto-Configuration Script             ║" -ForegroundColor Cyan
Write-Host "║   Fixes: PostTooLargeException                            ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Check if running as admin
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")

if (-not $isAdmin) {
    Write-Host "❌ ERROR: Script must be run as Administrator!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Steps:" -ForegroundColor Yellow
    Write-Host "  1. Right-click on PowerShell"
    Write-Host "  2. Select 'Run as Administrator'"
    Write-Host "  3. Run: .\setup_php_upload_limits.ps1"
    Write-Host ""
    exit 1
}

# Find php.ini
Write-Host "🔍 Searching for php.ini..." -ForegroundColor Yellow
Write-Host ""

$phpIniPaths = @(
    "C:\xampp\php\php.ini",
    "C:\laragon\bin\php\php-*\php.ini",
    "C:\wamp\bin\php\php*\php.ini",
    "C:\php\php.ini"
)

$phpIniPath = $null

foreach ($pattern in $phpIniPaths) {
    $matches = @(Get-Item $pattern -ErrorAction SilentlyContinue)
    if ($matches.Count -gt 0) {
        $phpIniPath = $matches[-1].FullName  # Get latest version if multiple
        break
    }
}

if (-not $phpIniPath) {
    Write-Host "❌ php.ini not found in common locations:" -ForegroundColor Red
    foreach ($path in $phpIniPaths) {
        Write-Host "   - $path" -ForegroundColor Gray
    }
    Write-Host ""
    Write-Host "⚠️  Please manually locate your php.ini and update:" -ForegroundColor Yellow
    Write-Host "   post_max_size = 512M" -ForegroundColor Cyan
    Write-Host "   upload_max_filesize = 256M" -ForegroundColor Cyan
    Write-Host ""
    exit 1
}

Write-Host "✓ Found php.ini:" -ForegroundColor Green
Write-Host "  $phpIniPath" -ForegroundColor Cyan
Write-Host ""

# Backup php.ini
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupPath = "$phpIniPath.backup_$timestamp"

Write-Host "📦 Creating backup..." -ForegroundColor Yellow
try {
    Copy-Item $phpIniPath $backupPath -Force
    Write-Host "✓ Backup created:" -ForegroundColor Green
    Write-Host "  $backupPath" -ForegroundColor Cyan
    Write-Host ""
}
catch {
    Write-Host "❌ Failed to create backup: $_" -ForegroundColor Red
    exit 1
}

# Read content
$content = Get-Content $phpIniPath
$originalContent = $content

# Update values
Write-Host "🔧 Updating PHP configuration..." -ForegroundColor Yellow
Write-Host ""

$replacements = @{
    'post_max_size' = '512M'
    'upload_max_filesize' = '256M'
    'max_input_time' = '300'
    'max_execution_time' = '300'
}

foreach ($key in $replacements.Keys) {
    $value = $replacements[$key]
    
    # Pattern untuk match baris (commented atau tidak)
    $pattern = '^\s*;?\s*' + [regex]::Escape($key) + '\s*='
    
    if ($content -match $pattern) {
        $content = $content -replace $pattern, "$key = $value"
        Write-Host "  ✓ $key = $value" -ForegroundColor Green
    }
    else {
        Write-Host "  ⚠️  $key not found (will be added)" -ForegroundColor Yellow
        # Add to end of file if not found
        $content += "`n$key = $value"
    }
}

# Write back
try {
    Set-Content -Path $phpIniPath -Value $content -Force
    Write-Host ""
    Write-Host "✓ Configuration updated successfully!" -ForegroundColor Green
}
catch {
    Write-Host ""
    Write-Host "❌ Failed to write changes: $_" -ForegroundColor Red
    Write-Host "⚠️  Restoring backup..." -ForegroundColor Yellow
    Copy-Item $backupPath $phpIniPath -Force
    exit 1
}

# Prompt to restart services
Write-Host ""
Write-Host "═════════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
Write-Host "⚠️  IMPORTANT: You must restart your web server!" -ForegroundColor Yellow
Write-Host ""
Write-Host "Choose your server:" -ForegroundColor Cyan
Write-Host ""
Write-Host "  1) XAMPP - Click Restart button next to Apache" -ForegroundColor White
Write-Host "  2) Laragon - Click PHP → Restart" -ForegroundColor White
Write-Host "  3) Manual - Will prompt you now" -ForegroundColor White
Write-Host ""
Write-Host "═════════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

$response = Read-Host "Restart Apache now? (Y/n)"

if ($response -ne 'n' -and $response -ne 'N') {
    # Try to restart Apache if installed
    Write-Host ""
    Write-Host "🔄 Attempting to restart Apache..." -ForegroundColor Yellow
    
    try {
        # Stop Apache
        net stop Apache2.4 2>$null
        Start-Sleep -Seconds 2
        
        # Start Apache
        net start Apache2.4 2>$null
        Start-Sleep -Seconds 2
        
        Write-Host "✓ Apache restarted" -ForegroundColor Green
    }
    catch {
        Write-Host "⚠️  Could not auto-restart Apache" -ForegroundColor Yellow
        Write-Host "   Please restart manually via XAMPP/Laragon Control Panel" -ForegroundColor Gray
    }
}

# Verify
Write-Host ""
Write-Host "✅ Verifying configuration..." -ForegroundColor Cyan
Write-Host ""

$postMaxSize = php -r "echo ini_get('post_max_size');" 2>$null
$uploadMaxSize = php -r "echo ini_get('upload_max_filesize');" 2>$null

if ($postMaxSize) {
    Write-Host "  post_max_size: $postMaxSize" -ForegroundColor Green
}
else {
    Write-Host "  Could not verify (restart services first)" -ForegroundColor Yellow
}

if ($uploadMaxSize) {
    Write-Host "  upload_max_filesize: $uploadMaxSize" -ForegroundColor Green
}

Write-Host ""
Write-Host "═════════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
Write-Host "✨ Setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "  1. Restart your web server (Apache/PHP)" -ForegroundColor White
Write-Host "  2. Try uploading a large file in the form" -ForegroundColor White
Write-Host "  3. Should work without PostTooLargeException" -ForegroundColor White
Write-Host ""
Write-Host "Need help?" -ForegroundColor Yellow
Write-Host "  - Check: IMPLEMENTATION_GUIDE.md" -ForegroundColor Gray
Write-Host "  - Check: QUICK_FIX.md" -ForegroundColor Gray
Write-Host ""
