<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Exports: Troubleshooting & Local Checklist 🔧

If PDF/Excel exports aren't working (downloads return HTML or files aren't generated), follow these steps:

1. Ensure dependencies are installed:
    - composer install
    - composer require phpoffice/phpspreadsheet --dev (if Excel .xlsx not generating)
    - composer require barryvdh/laravel-dompdf --no-interaction

2. Clear caches and regenerate autoloaders:
    - php artisan config:clear
    - composer dump-autoload -o

3. Verify PHP extensions required by DomPDF are enabled: mbstring, gd, dom, zip. Example:
    - php -m | grep -E "mbstring|gd|dom|zip"

4. Use the local debug endpoints (only available in local environment):
    - GET /\_debug/status — returns JSON with package & PHP extension checks
    - GET /\_debug/export/save/{pdf|excel|csv} — calls export and saves the result to storage/app/exports

5. Check logs for errors:
    - storage/logs/laravel.log
    - storage/logs/export-cli-debug.log
    - storage/logs/export-cli-error.log

6. Re-run tests:
    - php artisan export:save pdf
    - php scripts/run_export_cli.php pdf

If you want, I can try to apply defensive changes to the export flow (better error messages, more logging) or run the checks for you. ✅

## WhatsApp Integration

This project includes a lightweight WhatsApp integration using the WhatsApp Cloud API or a proxy provider. The code is implemented in `app/Services/WhatsAppService.php` and background delivery is handled by the `SendWhatsAppMessage` job.

**Note:** WhatsApp configuration is now optional. If not configured, the system will skip WhatsApp notifications and log warnings instead of throwing errors.

Quick setup checklist:

- **Set credentials in `.env`** (see `.env.example`):
    - `WHATSAPP_API_URL` — Base API URL (e.g. `https://graph.facebook.com` or a proxy URL).
    - `WHATSAPP_PHONE_NUMBER_ID` — Your Meta phone number id (when using Graph API).
    - `WHATSAPP_ACCESS_TOKEN` — Long-lived access token for the WhatsApp API or your proxy provider.

- **Optional templates**: Per-day template names are configured via `WHATSAPP_TEMPLATE_DAY_30`, `WHATSAPP_TEMPLATE_DAY_15`, etc., or in `config/services.php` under `services.whatsapp.templates`.

- **Run the queue worker for the `whatsapp` queue** (local example):

```bash
# Work the whatsapp queue (recommended for production: supervisor/systemd)
php artisan queue:work --queue=whatsapp --tries=5 --sleep=3
```

- **Health check** (local only):
    - Visit `GET /debug/health/whatsapp` (only available in `local` env) to see whether the token and phone id are configured.
    - The UI includes a small health card (partial: `resources/views/dashboard/partials/whatsapp-health.blade.php`) that calls this endpoint.

- **Troubleshooting**:
    - Logs for WhatsApp deliveries are written to `storage/logs/whatsapp.log` (channel `whatsapp`).
    - If jobs fail permanently, a Slack alert can be sent by setting `WHATSAPP_ALERT_SLACK_WEBHOOK` in your `.env`.

If you want, I can also add a Supervisor/example systemd config and an npm/Make task to start the worker locally.

## Database SQL Dump

The project keeps a SQL dump at `database/database.sql` for quick local restore and review.

To regenerate the dump after migrating and seeding the database, run:

```bash
composer dump-db-sql
```

This command will:
- run a fresh migration with seed data
- export `database/database.sqlite` into `database/database.sql`

Note: the export step uses `python scripts/export_sqlite_dump.py`, so Python must be available in your environment.

### Automatic provisioning

When the project is provisioned via Composer, `database/database.sql` is now generated automatically as part of setup and post-create-project provisioning.

- `composer setup` will build the app, migrate, seed, and export the SQL dump.
- `composer create-project` / `composer install` will run the post-create-project hook to create the SQLite file, migrate the schema, seed the database, and export `database/database.sql`.

## Email Notifications

The system can send automated email notifications for contracts in grace period (15 days remaining). Configure SMTP settings in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="Sistem Kontrak"

# Optional: Set admin email for notifications
ADMIN_EMAIL=admin@gmail.com
```

For Gmail, use an App Password instead of your regular password. Generate one at https://myaccount.google.com/apppasswords.

The system automatically sends emails daily at 08:30 via the scheduler. To run manually:

```bash
php artisan notifications:contracts
```

Queue jobs are dispatched to the default queue. Run the worker:

```bash
php artisan queue:work
```
