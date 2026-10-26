# WhatsApp Setup

Instruksi singkat untuk mengaktifkan pengiriman WhatsApp pada aplikasi.

## Dependensi

- Paket HTTP (Guzzle) dipakai oleh `App\Services\WhatsAppService`.

## Templates & Media

The `WhatsAppService` now supports sending pre-approved templates and media.

- Template example (in PHP):

```php
$wa = new App\Services\WhatsAppService();
$wa->sendTemplate('628123456789', 'reminder_template_name', 'id_ID', [
  [
    'type' => 'body',
    'parameters' => [
      ['type' => 'text', 'text' => 'Kontrak A'],
      ['type' => 'text', 'text' => '30 hari']
    ]
  ]
]);
```

- Media example (image by URL):

```php
$wa->sendMedia('628123456789', 'image', ['link' => 'https://example.com/image.jpg'], 'Caption here');
```

Notes:

- Templates must be pre-approved in your Meta Business Manager and the `name` must match exactly.
- Media can be sent by `link` (public URL) or by `id` (if you uploaded media to Meta and received a media ID).
  Jalankan:

```bash
composer require guzzlehttp/guzzle
```

## Environment

Tambahkan variabel berikut ke `.env` (atau ke `config/services.php` bagian `whatsapp`):

```env
WHATSAPP_API_URL=https://your-whatsapp-proxy.example.com/send
WHATSAPP_ACCESS_TOKEN=your_api_token_here
```

Optional env for Meta Cloud API (preferred when using Meta):

```env
WHATSAPP_PHONE_NUMBER_ID=123456789012345
WHATSAPP_ACCESS_TOKEN=EAA...your_access_token...
```

Contoh env untuk template per-hari (tambahan):

```env
WHATSAPP_TEMPLATE_DAY_15=contract_reminder_15_days
```

- `WHATSAPP_API_URL` harus menerima POST JSON `{ "to": "<phone>", "message": "<text>" }`.
- Jika menggunakan provider lain (Meta/Twilio), sesuaikan URL dan token, atau buat adapter kecil yang memetakan payload.

## Contoh payload untuk WhatsApp Cloud API (Meta)

Meta menggunakan endpoint berbeda dan butuh `phone_number_id` dan `access_token`:

```json
POST https://graph.facebook.com/v15.0/{PHONE_NUMBER_ID}/messages
Authorization: Bearer {ACCESS_TOKEN}
Content-Type: application/json

{
  "messaging_product": "whatsapp",
  "to": "628123456789",
  "type": "text",
  "text": { "body": "Pesan contoh" }
}
```

Jika ingin memakai Meta langsung, sesuaikan `App\Services\WhatsAppService` atau buat service adapter.

## Testing cepat (Tinker)

1. Isi `.env` dan jalankan `composer install` jika perlu.
2. Buka Tinker:

```bash
php artisan tinker
```

3. Jalankan contoh pengiriman:

```php
$c = App\Models\Contract::first();
App\Models\ContractNotification::createExpiryNotification($c, 30);
```

Periksa tabel `notifications` untuk record baru dan cek log/endpoint provider.

## Logging & retries

Implementasi awal sekarang menggunakan job/queue untuk pengiriman WhatsApp sehingga pengiriman dilakukan secara asinkron dan dapat di-retry.

- A job `App\Jobs\SendWhatsAppMessage` telah ditambahkan. Notifikasi sekarang mendispatch job ini ke antrian `whatsapp`.
- Pastikan koneksi queue dikonfigurasi di `.env` (mis. `QUEUE_CONNECTION=database` atau `redis`).

Menjalankan worker untuk antrian WhatsApp:

```bash
php artisan queue:work --queue=whatsapp --tries=5 --sleep=3
```

Jika menggunakan driver `database` untuk queue, buat tabel jobs terlebih dahulu:

```bash
php artisan queue:table
php artisan migrate
```

Jalankan juga migration baru untuk menambahkan kolom `sent_date` pada tabel `notifications`:

```bash
php artisan migrate
```

Job merekam kegagalan (`Log::error`) dan melempar ulang exception agar Laravel dapat melakukan retry sesuai properti `tries` dan `backoff`. Ketika job gagal permanen, method `failed()` juga akan mencatat log.

Jika Anda ingin menerima alert di Slack ketika job gagal permanen, tambahkan webhook URL berikut ke `.env`:

```env
```

Ketika `WHATSAPP_ALERT_SLACK_WEBHOOK` diisi, sistem akan mengirim notifikasi ringkas ke Slack berisi nomor tujuan, error message, dan `contract_id` bila tersedia.

Jika Anda ingin saya otomatis menyesuaikan `WhatsAppService` untuk Meta atau Twilio, beri tahu provider mana yang dipakai dan saya akan patch service untuknya.
