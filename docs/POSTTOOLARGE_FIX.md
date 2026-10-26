# Fix PostTooLargeException - Solusi Lengkap

## Problem
```
Illuminate\Http\Exceptions\PostTooLargeException
The POST data is too large.
```

**Penyebab**: File upload 1GB melebihi batas POST size di server PHP (default 8MB).

---

## Solusi

### STEP 1: Cek Konfigurasi PHP Saat Ini

Jalankan script diagnostic:
```bash
php scripts/check_php_config.php
```

Atau cek langsung dengan command:
```bash
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL . 'upload_max_filesize: ' . ini_get('upload_max_filesize');"
```

### STEP 2: Temukan File php.ini

**Windows (XAMPP):**
```
C:\xampp\php\php.ini
```

**Windows (Laragon):**
```
C:\laragon\bin\php\php-x.x.x\php.ini
```

**Windows (Valet):**
```
Buka dengan: valet edit-config
```

**Linux (Ubuntu):**
```
/etc/php/x.x/fpm/php.ini
/etc/php/x.x/cli/php.ini
```

**macOS:**
```
/usr/local/etc/php/x.x/php.ini
/opt/homebrew/etc/php/x.x/php.ini
```

### STEP 3: Update php.ini

Cari dan ubah nilai berikut:

```ini
; ===== UPLOAD SETTINGS =====

; Maximum size of POST data that PHP will accept
post_max_size = 1G

; Maximum allowed size for uploaded files
upload_max_filesize = 1G

; ===== TIMEOUT SETTINGS =====

; Time limit untuk parse input (untuk file besar)
max_input_time = 300

; Time limit untuk script execution
max_execution_time = 300

; ===== MEMORY SETTINGS =====

; Increase memory untuk process file besar
memory_limit = 512M
```

**PENTING**: Nilai `post_max_size` HARUS lebih besar atau sama dengan `upload_max_filesize`

### STEP 4: Konfigurasi Web Server

#### Jika menggunakan Nginx:

Edit file nginx config:
```
/etc/nginx/nginx.conf
atau
/etc/nginx/sites-available/default
```

Tambahkan dalam block `http` atau `server`:
```nginx
client_max_body_size 1G;
fastcgi_send_timeout 300s;
fastcgi_read_timeout 300s;
proxy_send_timeout 300s;
proxy_read_timeout 300s;
```

Restart Nginx:
```bash
sudo systemctl restart nginx
```

#### Jika menggunakan Apache:

Konfigurasi sudah di php.ini, Apache tidak perlu setting khusus.

Restart Apache:
```bash
sudo systemctl restart apache2
# atau untuk XAMPP
# Klik Restart pada Apache module
```

### STEP 5: Restart Services

**XAMPP (Windows):**
1. Buka XAMPP Control Panel
2. Klik "Restart" pada Apache module
3. Klik "Restart" pada MySQL (jika ada)

**Laragon (Windows):**
1. Klik icon Laragon di system tray
2. Pilih "PHP" → "Restart"

**Valet (macOS):**
```bash
valet restart
```

**Linux:**
```bash
# Untuk PHP-FPM
sudo systemctl restart php8.x-fpm

# Untuk Nginx
sudo systemctl restart nginx

# Untuk Apache
sudo systemctl restart apache2
```

### STEP 6: Verifikasi Perubahan

```bash
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL . 'upload_max_filesize: ' . ini_get('upload_max_filesize');"
```

Output harus menunjukkan:
```
post_max_size: 1G
upload_max_filesize: 1G
```

### STEP 7: Test Upload

Coba upload file 1GB melalui form kontrak. Jika masih error, lanjut ke troubleshooting.

---

## Troubleshooting

### Error Masih Muncul Setelah Update php.ini

**Kemungkinan:**
1. File yang diubah bukan php.ini yang aktif
2. Service belum di-restart
3. Multiple PHP installations

**Solusi:**
```bash
# Cari php.ini yang digunakan
php -r "echo php_ini_loaded_file();"

# Cek semua php.ini yang di-scan
php -r "echo implode(PHP_EOL, php_ini_scanned_files());"

# Cek direktori PHP
php -v
which php
```

### Timeout saat Upload

Jika timeout terjadi meskipun sudah update:

**Update konfigurasi:**
```ini
max_input_time = -1
max_execution_time = -1
```

**Atau update nilai lebih besar:**
```ini
max_input_time = 600
max_execution_time = 600
```

### Memory Error saat Upload

Tambah memory limit di php.ini:
```ini
memory_limit = 1024M
```

### Upload Berhasil tapi File Corrupt

Periksa disk space:
```bash
df -h
```

Pastikan ada ruang kosong yang cukup.

---

## Rekomendasi Alternatif

### Jika 1GB Terlalu Besar

Pertimbangkan kurangi ke ukuran yang lebih praktis untuk server Anda:

| Ukuran | Use Case | Config |
|--------|----------|---------|
| 100 MB | Small files, dokumen | `post_max_size = 100M`<br/>`upload_max_filesize = 100M` |
| 500 MB | Medium files | `post_max_size = 500M`<br/>`upload_max_filesize = 500M` |
| 1 GB | Large files (current) | `post_max_size = 1G`<br/>`upload_max_filesize = 1G` |

### Implementasi Chunked Upload

Untuk file sangat besar, gunakan chunked upload:

1. Split file menjadi chunks kecil (misal 50MB per chunk)
2. Upload chunks satu per satu
3. Combine chunks di server

Ini mengurangi beban server dan lebih reliable.

---

## Monitoring & Best Practices

1. **Monitor Disk Space**: Pastikan `/storage` memiliki space cukup
2. **Log Upload Activity**: Catat semua upload untuk audit
3. **Rate Limiting**: Batasi upload frequency per user
4. **Virus Scanning**: Scan file setelah upload
5. **Backup**: Backup file yang di-upload secara regular

---

## Script Helper

Gunakan script yang sudah disiapkan:

**Check Configuration:**
```bash
php scripts/check_php_config.php
```

**Auto Fix (Windows):**
```bash
.\fix_php_post_size.ps1
```

---

## Referensi

- [PHP Official: post_max_size](https://www.php.net/manual/en/ini.core.php#ini.post-max-size)
- [PHP Official: upload_max_filesize](https://www.php.net/manual/en/ini.core.php#ini.upload-max-filesize)
- [Nginx: client_max_body_size](http://nginx.org/en/docs/http/ngx_http_core_module.html#client_max_body_size)
- [Laravel: File Uploads](https://laravel.com/docs/uploads)
