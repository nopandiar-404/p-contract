# Panduan Menjalankan Server di Network Lokal

## IP Komputer Anda
**IP Address: `10.201.36.41`**

Gunakan IP ini untuk mengakses aplikasi dari device lain di network yang sama.

---

## Cara 1: Menggunakan Script Otomatis (PALING MUDAH)

### Windows (Command Prompt atau PowerShell):
```bash
# Double-click file ini atau jalankan:
START_SERVER.bat
```

### PowerShell:
```powershell
.\START_SERVER.ps1
```

---

## Cara 2: Menjalankan Manual (Jika Script Tidak Bekerja)

### Terminal 1 - Laravel Server:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Terminal 2 - Vite Dev Server:
```bash
npm run dev
```

---

## Akses Aplikasi

### Dari Komputer Lokal:
- Browser: **http://localhost:8000**
- atau: **http://10.201.36.41:8000**

### Dari Device Lain (Laptop, Mobile, Tablet):
- Browser: **http://10.201.36.41:8000**

---

## Konfigurasi yang Telah Diubah

### 1. `.env` - Application URL
```env
APP_URL=http://10.201.36.41:8000
```

### 2. `vite.config.js` - Dev Server Configuration
```javascript
server: {
    host: '0.0.0.0',           // Listen on all interfaces
    port: 5173,                // Vite dev server port
    hmr: {
        host: '10.201.36.41',  // Hot reload dari IP ini
        port: 5173,
    },
},
```

---

## Troubleshooting

### 1. "Port 8000 already in use"
Ubah port di command:
```bash
php artisan serve --host=0.0.0.0 --port=9000
```
Dan update APP_URL di `.env`:
```env
APP_URL=http://10.201.36.41:9000
```

### 2. Tidak bisa akses dari device lain
- **Cek Windows Firewall**: Pastikan port 8000 dan 5173 tidak diblokir
- **Cek IP Network**: Pastikan device lain di network yang sama
- **Cek Router**: Jika device berbeda network, mungkin perlu konfigurasi router

### 3. Hot reload tidak bekerja dari device lain
Pastikan `hmr.host` di `vite.config.js` sesuai dengan IP komputer:
```javascript
hmr: {
    host: '10.201.36.41',  // Ganti dengan IP komputer Anda
    port: 5173,
},
```

---

## Membuka Firewall Windows untuk Port

Jika akses dari device lain tidak berfungsi, buka Windows Defender Firewall:

### PowerShell (Jalankan sebagai Admin):
```powershell
# Allow port 8000 (Laravel)
New-NetFirewallRule -DisplayName "Laravel Dev Server" -Direction Inbound -Action Allow -Protocol TCP -LocalPort 8000

# Allow port 5173 (Vite)
New-NetFirewallRule -DisplayName "Vite Dev Server" -Direction Inbound -Action Allow -Protocol TCP -LocalPort 5173
```

---

## Port yang Digunakan

| Service | Port | URL |
|---------|------|-----|
| Laravel App | 8000 | http://10.201.36.41:8000 |
| Vite Dev Server | 5173 | http://10.201.36.41:5173 |

---

## Catatan Penting

✅ **Server berjalan dengan:**
- Hot Module Replacement (HMR) untuk auto-reload CSS/JS
- Database SQLite di `database/database.sqlite`
- Session dalam file system

⚠️ **Saat Development:**
- Jangan matikan terminal server
- Perubahan file akan di-reload otomatis
- Clear browser cache jika ada masalah

---

## Kembali ke Localhost Saja

Jika ingin kembali ke localhost:

### 1. Update `.env`:
```env
APP_URL=http://localhost
```

### 2. Update `vite.config.js`:
```javascript
server: {
    host: '127.0.0.1',  // atau 'localhost'
    port: 5173,
},
```

---

**Setup selesai! Silakan akses aplikasi dari device manapun di network yang sama.** 🚀
