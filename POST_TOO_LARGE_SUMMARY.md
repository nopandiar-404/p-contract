# PostTooLargeException - Ringkasan Solusi

## Error yang Terjadi
```
Illuminate\Http\Exceptions\PostTooLargeException
The POST data is too large.
```

## Root Cause
1. Default PHP `post_max_size` = 8MB
2. Form mengizinkan upload 1GB
3. Ketika submit, POST data melebihi limit → Exception

## Solusi Implementasi

### Opsi 1: Update Server Config (REKOMENDASI)

**Tingkat Kesulitan**: Mudah  
**Efek**: Permanent, aman

Ikuti panduan di `docs/POSTTOOLARGE_FIX.md`:

1. Update php.ini:
   ```ini
   post_max_size = 500M
   upload_max_filesize = 500M
   max_input_time = 300
   max_execution_time = 300
   ```

2. Jika Nginx, update config:
   ```nginx
   client_max_body_size 500M;
   ```

3. Restart services

4. Verifikasi:
   ```bash
   php -r "echo ini_get('post_max_size');"
   ```

### Opsi 2: Gunakan Chunked Upload (ADVANCED)

**Tingkat Kesulitan**: Sulit  
**Efek**: Lebih scalable, support file lebih besar

Implementasi upload dengan splitting file menjadi chunks.

---

## Perubahan yang Sudah Dilakukan

### 1. Update Form Limit
- **Dari**: 1GB
- **Menjadi**: 500MB  
- **Alasan**: 
  - Server umumnya tidak support 1GB POST data
  - 500MB sudah cukup untuk dokumen kontrak
  - Performa lebih optimal
  - Rekomendasi teks: "< 100MB untuk performa optimal"

### 2. Create New Files
1. **scripts/check_php_config.php** - Diagnostic tool
2. **fix_php_post_size.ps1** - Auto-fix script untuk Windows
3. **docs/POSTTOOLARGE_FIX.md** - Dokumentasi lengkap
4. **docs/NGINX_CONFIG.conf** - Konfigurasi Nginx
5. **docs/APACHE_CONFIG.conf** - Konfigurasi Apache
6. **app/Http/Middleware/HandlePostTooLarge.php** - Custom middleware

---

## Langkah Berikutnya

### Step 1: Diagnosis
```bash
php scripts/check_php_config.php
```

### Step 2: Tentukan Environment Anda

**Windows XAMPP:**
```bash
.\fix_php_post_size.ps1
```

**Linux/Mac:**
1. Locate php.ini: 
   ```bash
   php -r "echo php_ini_loaded_file();"
   ```

2. Edit php.ini:
   ```bash
   sudo nano /path/to/php.ini
   ```

3. Restart PHP-FPM:
   ```bash
   sudo systemctl restart php8.x-fpm
   ```

### Step 3: Verify
```bash
php -r "echo 'post_max_size: ' . ini_get('post_max_size');"
```

### Step 4: Test Upload
Upload file melalui form kontrak.

---

## Tabel Rekomendasi Ukuran

| Environment | Recommended Config | Use Case |
|------------|-------------------|----------|
| Local Development | 500M | Testing |
| Shared Hosting | 100M | Cost-effective, limited resources |
| VPS/Cloud | 500M - 1G | Good performance |
| Enterprise | 1G+ | High-traffic, large files |

---

## Troubleshooting Checklist

- [ ] php.ini sudah diupdate dengan nilai baru
- [ ] Services sudah di-restart (Apache/Nginx/PHP-FPM)
- [ ] Verify perubahan dengan `php -r "echo ini_get('post_max_size');"`
- [ ] Disk space cukup di `/storage`
- [ ] Memory limit di php.ini cukup (≥ 512M)
- [ ] Timeout settings sudah diupdate (max_input_time, max_execution_time)

---

## Monitoring & Maintenance

### Log Upload Activity
```bash
# Check recent uploads
tail -f storage/logs/laravel.log

# Filter upload errors
grep -i "upload\|post" storage/logs/laravel.log
```

### Monitor Disk Usage
```bash
# Check storage space
df -h

# Check database backup size
du -sh storage/
```

### Regular Cleanup
```bash
# Remove old uploaded files (if retention policy exists)
find storage/contracts_export -type f -mtime +30 -delete
```

---

## Performance Tips

1. **Compress PDF sebelum upload**
   - Gunakan tools: GhostScript, ImageMagick
   - Reduce file size 50-80%

2. **Implement Async Processing**
   - Queue file uploads
   - Process di background
   - Improve user experience

3. **Add Progress Bar**
   - Show upload progress
   - Better UX untuk file besar

4. **Validate File Content**
   - Scan file type
   - Check file integrity
   - Prevent malicious uploads

---

## Reference Files

- Dokumentasi: `docs/POSTTOOLARGE_FIX.md`
- Script Diagnostic: `scripts/check_php_config.php`
- Auto-fix Script: `fix_php_post_size.ps1`
- Middleware: `app/Http/Middleware/HandlePostTooLarge.php`
- Form: `resources/views/contracts/create.blade.php`

---

## Need Help?

Jika masih error:
1. Verify dengan `php scripts/check_php_config.php`
2. Check yang server environment Anda (Apache/Nginx, OS)
3. Lihat log: `storage/logs/laravel.log`
4. Restart services lagi (pastikan reload config baru)
