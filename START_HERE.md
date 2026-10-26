# 🚀 QUICK START - Fix PostTooLargeException

## 🔴 Error
```
Illuminate\Http\Exceptions\PostTooLargeException
The POST data is too large.
```

---

## ⚡ WHAT'S DONE (Form Side)

✅ Form optimized for 100-300MB uploads  
✅ Real-time progress bar added  
✅ Better error messages  
✅ Enhanced user experience  

---

## 🔴 WHAT YOU NEED TO DO (Server Side)

### Choose Your Setup:

#### **XAMPP (Windows) - 2 Minutes**
1. Open: `C:\xampp\php\php.ini`
2. Find: `post_max_size = 8M`
3. Change to: `post_max_size = 512M`
4. Find: `upload_max_filesize = 2M`
5. Change to: `upload_max_filesize = 256M`
6. Restart Apache (XAMPP Control Panel → Restart button)

#### **Laragon (Windows) - 2 Minutes**
1. Right-click: `setup_php_upload_limits.ps1`
2. Select: "Run with PowerShell as Administrator"
3. Wait for completion
4. Restart: Laragon tray → PHP → Restart

#### **Linux/Ubuntu - 3 Minutes**
```bash
# Find php.ini
php -r "echo php_ini_loaded_file();"

# Edit it
sudo nano /path/from/above

# Change these values:
# post_max_size = 512M
# upload_max_filesize = 256M

# Save: Ctrl+X, Y, Enter

# Restart
sudo systemctl restart php8.x-fpm nginx
```

#### **VPS with cPanel - 1 Minute**
1. cPanel → MultiPHP INI Editor
2. Update same values (512M, 256M)
3. Click Apply

---

## ✅ VERIFY (After Restart)

Run this command:
```bash
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL . 'upload_max_filesize: ' . ini_get('upload_max_filesize');"
```

Should show:
```
post_max_size: 512M
upload_max_filesize: 256M
```

---

## 🧪 TEST

1. Refresh: `http://your-app/contracts/create`
2. Upload: File 50-100MB PDF
3. Should work ✓ without error

---

## 📚 Need More Details?

- **Full guide**: `IMPLEMENTATION_GUIDE.md`
- **Quick ref**: `QUICK_FIX.md`
- **Error summary**: `ERROR_FIX_SUMMARY.md`
- **Auto-fix (Windows)**: `setup_php_upload_limits.ps1`

---

## ⏰ Time Required

| Task | Time |
|------|------|
| XAMPP | 2 min |
| Laragon | 2 min |
| Linux | 3 min |
| Verify | 1 min |
| **Total** | **~5 min** |

---

## 🎯 That's It!

Once server config is done, your form will support 100MB per file uploads!

Go to: `IMPLEMENTATION_GUIDE.md` for your specific setup.
