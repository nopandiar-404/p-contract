# PostTooLargeException - Complete Implementation Guide

## 🔴 Error Occurring
```
Illuminate\Http\Exceptions\PostTooLargeException
The POST data is too large.
```

---

## 📊 Current Status

| Component | Status | Limit |
|-----------|--------|-------|
| Form UI | ✓ Updated | 100MB per file |
| Form Validation | ✓ Enhanced | 300MB total |
| Real-time Monitor | ✓ Added | Shows progress |
| Server Config | ❌ NEEDS FIX | Set limits |

---

## ⚡ IMMEDIATE ACTION REQUIRED

### Server Configuration is MISSING
Error terjadi karena server PHP/Nginx/Apache tidak dikonfigurasi untuk support file besar.

**Anda harus melakukan ONE dari opsi dibawah:**

---

## OPSI 1: XAMPP Windows (Recommended - Easiest)

**Step 1: Locate PHP.ini**
```
C:\xampp\php\php.ini
```

**Step 2: Edit dengan Notepad++**
- Buka file di atas dengan Notepad++
- Cari: `post_max_size`
- Ubah dari: `post_max_size = 8M`
- Menjadi: `post_max_size = 512M`

**Step 3: Cari & Ubah Lagi**
- Cari: `upload_max_filesize`
- Ubah dari: `upload_max_filesize = 2M`
- Menjadi: `upload_max_filesize = 256M`

**Step 4: Restart Apache**
1. Buka XAMPP Control Panel
2. Klik tombol **Restart** di samping Apache
3. Tunggu hingga "Running" (hijau)

**Step 5: Verify**
Buka Terminal/CMD di folder project, jalankan:
```bash
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL . 'upload_max_filesize: ' . ini_get('upload_max_filesize');"
```

Expected output:
```
post_max_size: 512M
upload_max_filesize: 256M
```

---

## OPSI 2: Laragon Windows (Easy)

**Step 1: Restart PHP**
1. Klik Laragon icon di system tray (bawah kanan)
2. Pilih **PHP** → **Restart**

**Step 2: Edit php.ini**
1. Klik Laragon icon
2. Pilih **Menu** → **PHP** → **php.ini**
3. Cari dan ubah:
   ```ini
   post_max_size = 512M
   upload_max_filesize = 256M
   ```

**Step 3: Restart Lagi**
Klik PHP → Restart

---

## OPSI 3: Linux/Ubuntu (Medium)

**Step 1: Find PHP Config**
```bash
php -r "echo php_ini_loaded_file();"
```

Contoh output: `/etc/php/8.2/fpm/php.ini`

**Step 2: Edit File**
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Ubah nilai:
```ini
post_max_size = 512M
upload_max_filesize = 256M
max_input_time = 300
```

**Step 3: Restart PHP-FPM**
```bash
sudo systemctl restart php8.2-fpm
```

**Step 4: If Using Nginx**
Edit `/etc/nginx/nginx.conf`:
```nginx
http {
    client_max_body_size 256M;
    fastcgi_send_timeout 300s;
    fastcgi_read_timeout 300s;
}
```

Restart Nginx:
```bash
sudo systemctl restart nginx
```

---

## OPSI 4: VPS with cPanel/WHM

1. Log in ke cPanel
2. Pilih **MultiPHP INI Editor**
3. Ubah values:
   - `post_max_size = 512M`
   - `upload_max_filesize = 256M`
4. Klik **Apply**

---

## ✅ Verification Steps

Setelah update config:

**1. Check PHP Settings**
```bash
php -r "echo 'post_max_size: ' . ini_get('post_max_size');"
```

**2. Try Upload di Form**
1. Buka halaman kontrak
2. Upload file 50-100MB
3. Should work tanpa error

**3. Check Logs**
Jika masih error:
```bash
tail -f storage/logs/laravel.log
```

---

## 📋 Form Changes Made

### Updated Limits
- ✓ Per file: **100MB** (was 500MB)
- ✓ Total: **300MB** (was 1GB)
- ✓ Real-time progress indicator
- ✓ Better error messages

### Why These Values?
- **100MB per file**: Reasonable untuk dokumen PDF
- **300MB total**: Safe untuk kebanyakan server
- **Real-time monitor**: User tahu berapa sudah terupload

### New Features in Form
1. Progress bar showing total upload size
2. Color changes (green → red) when approaching limit
3. Detailed error messages with exact sizes
4. Better visual feedback

---

## 🚨 Troubleshooting

### Still Getting PostTooLargeException?

**Check 1: Verify Config Updated**
```bash
php -r "echo ini_get('post_max_size');"
```
Should show: `512M`

**Check 2: Restart Services**
- Apache/Nginx/PHP must be restarted after php.ini change
- Don't just reload - full RESTART

**Check 3: Wrong php.ini Edited?**
Multiple php.ini files can exist:
```bash
php -i | grep "ini"
```

**Check 4: Check Disk Space**
```bash
df -h
```
Ensure `/` atau `/home` tidak penuh

**Check 5: Check Error Logs**
```bash
tail storage/logs/laravel.log
```

---

## 📂 Recommended Configuration

### php.ini Values
```ini
; Upload settings
post_max_size = 512M
upload_max_filesize = 256M

; Timeout settings (for large file processing)
max_input_time = 300
max_execution_time = 300

; Memory
memory_limit = 512M
```

### Nginx Configuration (if applicable)
```nginx
client_max_body_size 256M;
fastcgi_send_timeout 300s;
fastcgi_read_timeout 300s;
proxy_send_timeout 300s;
proxy_read_timeout 300s;
```

---

## ✨ Features Already Implemented

### In Form (resources/views/contracts/create.blade.php)
- ✓ Max 100MB per file validation
- ✓ Max 300MB total validation
- ✓ Real-time file size monitoring
- ✓ Progress indicator with color coding
- ✓ Detailed error messages
- ✓ Individual file size display
- ✓ Delete button for each file

### JavaScript Enhancements
```javascript
// New features:
- Better formatFileSize()
- Real-time size calculation
- Progress bar in file list
- Color indicator (green/red)
- Detailed error with MB amounts
```

---

## 🔧 Quick Checklist

Before testing, ensure:

- [ ] You know which PHP setup (XAMPP/Laragon/Linux/cPanel)
- [ ] Located php.ini file
- [ ] Updated post_max_size to at least 512M
- [ ] Updated upload_max_filesize to at least 256M
- [ ] Restarted Apache/Nginx/PHP-FPM
- [ ] Verified with `php -r "echo ini_get('post_max_size');"`
- [ ] Disk space is sufficient (> 500MB free)
- [ ] Firewall/security not blocking large uploads
- [ ] Tested upload in form

---

## 📞 Getting Help

If still not working:

1. **Tell us your setup:**
   - OS: Windows / Linux / macOS
   - Server: XAMPP / Laragon / Standalone / VPS
   - PHP version: Run `php -v`

2. **Check logs:**
   ```bash
   storage/logs/laravel.log
   ```

3. **Verify config:**
   ```bash
   php -r "phpinfo();" | grep -i "post\|upload"
   ```

4. **Check process running:**
   ```bash
   # Windows - Check XAMPP/Laragon running
   # Linux - Check: sudo systemctl status php8.x-fpm
   ```

---

## 📚 Reference Files

Created for this project:
- `QUICK_FIX.md` - 5-minute quick reference
- `docs/POSTTOOLARGE_FIX.md` - Detailed documentation
- `scripts/check_php_config.php` - Diagnostic tool
- `fix_php_post_size.ps1` - Auto-fix for Windows

---

## ✅ Success Indicators

You'll know it's working when:
1. Form accepts 50MB+ files without error
2. `php -r "echo ini_get('post_max_size');"` shows 512M
3. No more "PostTooLargeException" in logs
4. Files upload successfully to storage

---

## 🎯 Next Steps

1. **Identify your server setup** (XAMPP/Laragon/Linux/etc)
2. **Follow the appropriate OPSI above**
3. **Restart services**
4. **Test upload in form**
5. **Verify with `php -r` command**

**That's it!** The form is already optimized for 100-300MB files.
