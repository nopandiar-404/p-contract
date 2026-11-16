<?php

namespace App\Http\Controllers;

use App\Services\GmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class GmailAuthController extends Controller
{
    /**
     * Memulai proses OAuth2 authorization dengan Google.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function redirect(Request $request)
    {
        // Izinkan di local environment atau jika user yang login adalah admin
        if (!app()->environment('local') && (!Auth::check() || Auth::user()->role !== 'admin')) {
            abort(403, 'Akses terbatas untuk Administrator.');
        }

        $creds = GmailService::getCredentials();
        $clientId     = $creds['client_id'];
        $clientSecret = $creds['client_secret'];

        if (!$clientId || !$clientSecret) {
            $msg = 'Harap isi GMAIL_CLIENT_ID dan GMAIL_CLIENT_SECRET di menu Pengaturan sebelum memulai otorisasi Gmail.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 422);
            }
            return redirect()->route('settings.index')
                ->with('error', $msg);
        }

        $state = bin2hex(random_bytes(32));
        $request->session()->put('gmail_oauth_state', $state);

        $query = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => URL::route('gmail.callback'),
            'response_type' => 'code',
            'scope'         => 'https://www.googleapis.com/auth/gmail.send https://www.googleapis.com/auth/gmail.readonly',
            'access_type'   => 'offline',
            'prompt'        => 'consent', // Wajib consent agar Google selalu mengembalikan refresh_token
            'state'         => $state,
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Menerima callback dari Google OAuth2 setelah otorisasi berhasil.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        $state = $request->session()->pull('gmail_oauth_state');
        if (!$state || !is_string($request->query('state')) || !hash_equals($state, $request->query('state'))) {
            $msg = 'State OAuth tidak valid atau telah kedaluwarsa. Silakan ulangi otorisasi.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return redirect()->route('settings.index')->with('error', $msg);
        }

        if ($request->filled('error')) {
            $err = $request->query('error');
            $msg = 'Otorisasi Google dibatalkan atau ditolak (' . $err . ').';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return redirect()->route('settings.index')->with('error', $msg);
        }

        $code = $request->query('code');
        if (!is_string($code) || $code === '') {
            $msg = 'Google tidak mengembalikan kode otorisasi.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return redirect()->route('settings.index')->with('error', $msg);
        }

        $creds = GmailService::getCredentials();
        $clientId     = $creds['client_id'];
        $clientSecret = $creds['client_secret'];

        if (!$clientId || !$clientSecret) {
            $msg = 'Kredensial Gmail OAuth tidak ditemukan.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 503);
            }
            return redirect()->route('settings.index')->with('error', $msg);
        }

        try {
            $response = Http::asForm()->timeout(15)->post('https://oauth2.googleapis.com/token', [
                'code'          => $code,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => URL::route('gmail.callback'),
                'grant_type'    => 'authorization_code',
            ]);
        } catch (\Throwable $exception) {
            Log::error('GmailAuth: Exception saat pertukaran token: ' . $exception->getMessage());
            $msg = 'Tidak dapat menghubungi server Google OAuth. Coba lagi beberapa saat lagi.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 502);
            }
            return redirect()->route('settings.index')->with('error', $msg);
        }

        if (!$response->successful()) {
            Log::error('GmailAuth: Google menolak token exchange', ['body' => $response->body()]);
            $msg = 'Google menolak pertukaran token OAuth: ' . ($response->json('error_description') ?? $response->body());
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 502);
            }
            return redirect()->route('settings.index')->with('error', $msg);
        }

        $tokenData    = $response->json();
        $refreshToken = $tokenData['refresh_token'] ?? null;

        if (!$refreshToken) {
            // Jika refresh token tidak ada, cek apakah sudah pernah tersimpan sebelumnya
            $existingCreds = GmailService::getCredentials();
            if (!empty($existingCreds['refresh_token'])) {
                $refreshToken = $existingCreds['refresh_token'];
            } else {
                $msg = 'Google tidak mengembalikan refresh token baru. Buka myaccount.google.com/permissions, hapus izin aplikasi ini, lalu otorisasi ulang.';
                if ($request->expectsJson()) {
                    return response()->json(['message' => $msg], 422);
                }
                return redirect()->route('settings.index')->with('warning', $msg);
            }
        }

        // Simpan refresh_token secara otomatis ke .env dan gmail_credentials.json!
        GmailService::updateEnv([
            'GMAIL_REFRESH_TOKEN' => $refreshToken,
        ]);

        Log::info('GmailAuth: Refresh token berhasil disimpan secara otomatis.');

        $successMessage = 'Akun Gmail berhasil dihubungkan! Refresh token telah disimpan secara otomatis ke konfigurasi.';

        if ($request->expectsJson()) {
            return response()->json([
                'success'       => true,
                'refresh_token' => $refreshToken,
                'message'       => $successMessage,
            ]);
        }

        return redirect()->to(route('settings.index') . '#gmail')
            ->with('success', $successMessage);
    }
}
