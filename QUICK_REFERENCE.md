# 🚀 Quick Reference Guide - Settings Security Features

## 📍 Lokasi File Penting

```
📦 Project Root
├── app/Http/Controllers/
│   └── SettingsController.php ..................... Controller
├── resources/views/
│   ├── settings/
│   │   └── index.blade.php ........................ Main view
│   └── layouts/
│       └── dashboard.blade.php ................... Navigation
├── routes/
│   └── web.php .................................. Routes
├── tests/Feature/
│   └── PasswordChangeSecurityTest.php ........... Tests
├── SECURITY_FEATURES.md .......................... Dokumentasi keamanan
└── IMPLEMENTATION_SUMMARY.md ..................... Ringkasan implementasi
```

---

## 🔗 Routes Quick Reference

```php
// GET - Display settings page
GET /settings
→ SettingsController@index
→ Route name: settings.index
→ View: settings.index

// PUT - Update profile/preferences
PUT /settings
→ SettingsController@update
→ Route name: settings.update

// POST - Change password
POST /settings/change-password
→ SettingsController@changePassword
→ Route name: settings.changePassword
```

---

## 🎯 Password Requirements

**Must have all 5:**
- [x] Length: 8+ characters
- [x] Uppercase: A-Z
- [x] Lowercase: a-z
- [x] Number: 0-9
- [x] Special: @$!%*?&

**Examples:**
```
✅ ValidPassword123@
✅ SecurePass456!
✅ MyP@ssw0rd

❌ weak123        (no uppercase, special)
❌ ALLUPPERCASE   (no lowercase, number, special)
❌ 12345678       (no letters, special)
```

---

## 🔐 Server-side Validation

```php
// Location: app/Http/Controllers/SettingsController.php::changePassword()

$validated = $request->validate([
    'current_password' => 'required|string',
    'password' => [
        'required',
        'string',
        'min:8',
        'regex:/[a-z]/',          // Lowercase
        'regex:/[A-Z]/',          // Uppercase
        'regex:/[0-9]/',          // Number
        'regex:/[@$!%*?&]/',      // Special
        'confirmed',              // Match confirmation
        'different:current_password', // Not same as old
    ],
]);

// Verify current password
Hash::check($validated['current_password'], $user->password);

// Hash new password
Hash::make($validated['password']);
```

---

## 💻 JavaScript Functions

### checkPasswordStrength(password)
Checks all 5 requirements and updates:
- Strength bar (0-100%)
- Strength text color
- Requirements checklist icons
- Submit button state

```javascript
checkPasswordStrength(password_value);
```

### togglePasswordVisibility(fieldId)
Toggle between password and text visibility

```javascript
togglePasswordVisibility('password');
togglePasswordVisibility('password_confirmation');
```

### updateRequirement(id, isMet)
Update individual requirement icon/color

### checkPasswordMatch()
Verify password and confirmation match

### updateSubmitButton()
Enable/disable submit button based on validation state

---

## 📊 UI Components

### Password Strength Bar
```html
<div id="strengthBar">
  <div id="strengthIndicator" style="width: 0%;"></div>
</div>
<small id="strengthText">Lemah</small>
```

### Requirements Checklist
```html
<div id="req-length">
  <i class="fas fa-times-circle"></i> Minimal 8 karakter
</div>
<!-- 4 more requirements -->
```

### Password Match Indicator
```html
<div id="confirmationMatch" style="display: none;"></div>
```

---

## 🧪 Testing Commands

```bash
# Run all settings security tests
php artisan test tests/Feature/PasswordChangeSecurityTest.php

# Run specific test
php artisan test tests/Feature/PasswordChangeSecurityTest.php --filter=test_user_can_change_password_with_valid_credentials

# Run with verbose output
php artisan test tests/Feature/PasswordChangeSecurityTest.php --verbose

# Generate test report
php artisan test tests/Feature/PasswordChangeSecurityTest.php --coverage
```

---

## 🔍 Debugging Tips

### Check Routes
```bash
php artisan route:list --name=settings
```

### Check Controller Syntax
```bash
php -l app/Http/Controllers/SettingsController.php
```

### Database Check
```bash
# No migrations needed, but verify users table has password column
php artisan tinker
>>> Schema::hasColumn('users', 'password')
```

### View Cache
```bash
php artisan view:clear
php artisan cache:clear
```

---

## 📱 Responsive Breakpoints

```css
/* Desktop (> 992px) */
.settings-grid {
  grid-template-columns: 250px 1fr;
}

/* Tablet (≤ 992px) */
.settings-sidebar {
  flex-direction: row;  /* Horizontal menu */
  overflow-x: auto;
}

/* Mobile (≤ 768px) */
.settings-section {
  padding: 20px;
}

/* Extra Small (≤ 480px) */
.sidebar-brand span {
  display: none;
}
```

---

## 🛠️ Common Customizations

### Change Password Minimum Length
```php
// In SettingsController::changePassword()
// Change from 'min:8' to 'min:12'
'password' => [
    'min:12',  // Changed
    // ... other rules
]
```

### Add More Special Characters
```php
// In SettingsController::changePassword()
// Add more chars to regex
'regex:/[@$!%*?&#-+]/',  // Added # - +
```

### Change Strength Indicator Colors
```javascript
// In checkPasswordStrength()
let color = '#dc3545';  // Change red
let color = '#f39c12';  // Change yellow
let color = '#27ae60';  // Change green
```

### Add More Timezone Options
```php
// In view: settings/index.blade.php
<option value="UTC">UTC (UTC+0)</option>
<option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
```

---

## 🔄 Flow Diagram

```
User visits /settings
    ↓
SettingsController::index() loads
    ↓
Display settings page with 5 tabs
    ↓
User clicks "Keamanan" tab
    ↓
Form with 3 password fields shown
    ↓
User types password
    ↓
JavaScript validates real-time
    ↓
Strength bar & requirements update
    ↓
Submit button enabled when all valid
    ↓
User submits form
    ↓
Server-side validation in controller
    ↓
Current password verified with Hash::check()
    ↓
New password hashed with Hash::make()
    ↓
User password updated in database
    ↓
Password change logged
    ↓
Success message shown
```

---

## 📋 Checklist Sebelum Deploy

- [ ] Semua tests passing
  ```bash
  php artisan test tests/Feature/PasswordChangeSecurityTest.php
  ```

- [ ] Routes registered
  ```bash
  php artisan route:list --name=settings
  ```

- [ ] No PHP syntax errors
  ```bash
  php -l app/Http/Controllers/SettingsController.php
  ```

- [ ] View renders correctly
  - Buka /settings di browser

- [ ] Responsive design OK
  - Test di mobile, tablet, desktop

- [ ] Security features working
  - Password strength updating
  - Requirements checklist working
  - Toggle visibility working
  - Error messages showing

- [ ] Logging configured
  - Check config/logging.php

- [ ] Database backup done
  - Before any password changes

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Route not found | Run `php artisan route:list --name=settings` |
| 500 error | Check SettingsController syntax & imports |
| JS not working | Clear browser cache, check console |
| Password not updating | Verify Hash::make() is used |
| Logging not working | Check config/logging.php |
| View not showing | Run `php artisan view:clear` |
| Middleware error | Ensure `auth` middleware in route |

---

## 📞 API Reference

### SettingsController Methods

#### index()
```php
// Returns: View with settings page
return view('settings.index', $data);

// Required data:
- user: Current authenticated user
- unreadNotifications: Count of unread notifications
- totalContracts: Total contracts count
- activeContracts: Active contracts count
- gracePeriodContracts: Grace period contracts count
- coaCount: Chart of accounts count
```

#### update(Request $request)
```php
// Updates: User profile & preferences
// Validates: name, email, language, timezone, notifications, theme

// Returns:
back()->with('success', 'Pengaturan berhasil disimpan');
```

#### changePassword(Request $request)
```php
// Validates: current_password, password (with all requirements)
// Updates: User password

// Returns:
back()->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru.');

// Or errors:
back()->withErrors(['current_password' => '...']);
back()->withErrors(['password' => '...']);
```

---

## 📚 External Resources

- Laravel Validation: https://laravel.com/docs/validation
- Hash Facade: https://laravel.com/docs/hashing
- OWASP Password Guidelines: https://owasp.org/www-community/
- Regex Tester: https://regex101.com

---

**Last Updated:** April 28, 2026  
**Version:** 1.0.0  
**Status:** ✅ Production Ready
