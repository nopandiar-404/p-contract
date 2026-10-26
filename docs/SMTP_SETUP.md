# SMTP Setup and Google App Password (Gmail)

This document explains how to configure SMTP for Laravel using Gmail's SMTP (App Password) and how to obtain an App Password from Google.

## Recommended approach
- If you prefer a simple SMTP setup, use Gmail SMTP with an App Password (requires 2-Step Verification).
- If you need server-to-server with OAuth, continue using the Gmail API (we added helpers). App Password is easier for most deployments.
- Do not use your regular Gmail account password for `MAIL_PASSWORD`; Gmail blocks SMTP authentication unless an App Password is used or OAuth is enabled.

## .env settings (example)

Add or update these values in your `.env` (see `.env.example`):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # Gmail App Password, not your regular account password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Your App Name"
```

If you prefer Gmail OAuth, enable Gmail API and add these values instead:

```env
GMAIL_CLIENT_ID=
GMAIL_CLIENT_SECRET=
GMAIL_REFRESH_TOKEN=
GMAIL_FROM_EMAIL=your-email@gmail.com
GMAIL_FROM_NAME="Your App Name"
```

## How to get a Google App Password

1. Sign in to the Google account you want to send from.
2. Ensure **2-Step Verification** is enabled for that account:
   - Go to https://myaccount.google.com/security
   - Under "Signing in to Google" enable "2-Step Verification" and complete the setup.
3. Once 2-Step Verification is enabled, go to **App passwords**:
   - https://myaccount.google.com/apppasswords
4. Under "Select app" choose "Mail". Under "Select device" choose "Other" and enter a label (e.g., "p-contract app").
5. Click **Generate**. Google will display a 16-character app password.
6. Copy that password and place it into your `.env` as `MAIL_PASSWORD`.

Notes:
- App Passwords are only available for accounts with 2-Step Verification enabled.
- Do not share the app password; treat it like a secret.
- For Google Workspace accounts, admins may restrict App Passwords or require SMTP relay — consult your admin.

## Alternative: Google Workspace SMTP relay

- If you're on Google Workspace and managing many senders, configure the SMTP relay service in Google Admin Console and point Laravel `MAIL_HOST` to the relay host. This avoids per-user app passwords.

## Testing email sending locally

Start the queue worker (we queue mail in this app):

```bash
php artisan queue:work --queue=mail,whatsapp
```

Quick test using Tinker (sends immediately via configured mailer):

```bash
php artisan tinker
>>> Mail::to('you@example.com')->send(new \App\Mail\ReminderMail('Test','<p>Hello from p-contract</p>'));
```

If you see no errors and the email arrives, SMTP is correctly configured. Check `storage/logs/laravel.log` for errors.

## When to use Gmail API instead

- Use the Gmail API when you need OAuth-managed access, domain-wide delegation, or to send on behalf of other users.
- Our repo includes `GmailService` and `GmailAuthController` if you prefer the OAuth route.

## Gmail API environment variables

To enable Gmail API reminders, add these variables to your `.env`:

```env
GMAIL_CLIENT_ID=
GMAIL_CLIENT_SECRET=
GMAIL_REFRESH_TOKEN=
GMAIL_FROM_EMAIL=your-email@gmail.com
GMAIL_FROM_NAME="Your App Name"
```

The application will use these settings to send 60-day contract reminder emails through Gmail.

## Security & deployment tips

- Store secrets in environment variables managed by your host (e.g., Azure Key Vault, AWS Secrets Manager, Heroku config vars).
- Avoid committing `.env` or any credential files to source control.
