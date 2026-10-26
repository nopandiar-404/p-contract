# QUICK REFERENCE - PostTooLargeException Fix

## ⚡ Fastest Fix (5 Minutes)

### Windows XAMPP
1. Buka `C:\xampp\php\php.ini`
2. Cari dan ubah:
   ```ini
   post_max_size = 500M
   upload_max_filesize = 500M
   max_input_time = 300
   max_execution_time = 300
   ```
3. Klik Restart Apache di XAMPP Control Panel
4. Done! ✓

### Windows Laragon
1. Klik Laragon tray → PHP → Restart
2. Buka editor Laragon
3. Update php.ini sama seperti di atas
4. Restart lagi
5. Done! ✓

### Linux
```bash
# Edit php.ini
sudo nano /etc/php/8.x/fpm/php.ini

# Update values (lihat bagian "SOLUTION" di bawah)

# Restart
sudo systemctl restart php8.x-fpm nginx
sudo systemctl restart apache2

# Verify
php -r "echo ini_get('post_max_size');"
```

### macOS (Homebrew)
```bash
# Locate php.ini
php -r "echo php_ini_loaded_file();"

# Edit
nano /usr/local/etc/php/8.x/php.ini

# Restart
brew services restart php
```

---

## 📋 SOLUTION - Exact Values to Change

Cari dan ubah nilai ini di php.ini:

```ini
; Find these lines (may be commented with ;)
; Remove the ; if commented

post_max_size = 500M
upload_max_filesize = 500M
max_input_time = 300
max_execution_time = 300
memory_limit = 512M
```

**PENTING**: `post_max_size` harus ≥ `upload_max_filesize`

---

## 🌐 Nginx Config (if using Nginx)

Edit `/etc/nginx/nginx.conf` atau virtual host config, tambahkan dalam block `http` atau `server`:

```nginx
client_max_body_size 500M;
fastcgi_send_timeout 300s;
fastcgi_read_timeout 300s;
```

Restart:
```bash
sudo systemctl restart nginx
```

---

## ✅ Verification

Run this command:
```bash
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL . 'upload_max_filesize: ' . ini_get('upload_max_filesize');"
```

Expected output:
```
post_max_size: 500M
upload_max_filesize: 500M
```

---

## 🔧 Diagnostic

Jika masih error, jalankan:
```bash
php scripts/check_php_config.php
```

---

## 📁 Form Update

✓ File sudah diupdate: `resources/views/contracts/create.blade.php`
- Max size: 500MB
- Added helper text: "Rekomendasi < 100 MB untuk performa optimal"

---

## 🚀 Common Mistakes

❌ **Mistake 1**: Edit php.ini tapi tidak restart service
✓ **Fix**: ALWAYS restart service setelah edit php.ini

❌ **Mistake 2**: Edit php.ini yang salah (ada multiple files)
✓ **Fix**: Verify dengan `php -r "echo php_ini_loaded_file();"`

❌ **Mistake 3**: post_max_size < upload_max_filesize
✓ **Fix**: post_max_size HARUS lebih besar atau sama

❌ **Mistake 4**: Hanya update php.ini tapi tidak update Nginx/Apache
✓ **Fix**: Update keduanya jika menggunakan Nginx

---

## 📞 Still Having Issues?

1. Check: `php scripts/check_php_config.php`
2. Verify: `php -r "echo php_ini_loaded_file();"`
3. Restart: Services (Apache/Nginx/PHP-FPM)
4. Check logs: `storage/logs/laravel.log`
5. Check disk space: `df -h`

---

## 📚 Documentation Files

- Full Guide: `docs/POSTTOOLARGE_FIX.md`
- Summary: `POST_TOO_LARGE_SUMMARY.md`
- Nginx Config: `docs/NGINX_CONFIG.conf`
- Apache Config: `docs/APACHE_CONFIG.conf`
- Diagnostic Script: `scripts/check_php_config.php`
- Auto-fix Script: `fix_php_post_size.ps1`
