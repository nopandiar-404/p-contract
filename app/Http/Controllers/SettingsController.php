<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContractNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        // Ambil notifikasi untuk display di top bar
        $unreadNotifications = ContractNotification::where('is_read', false)->count();
        
        // Ambil data user yang login
        $user = User::findOrFail(Auth::id());
        
        // Ambil statistik untuk sidebar
        try {
            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';

            $totalContractsQuery = \App\Models\Contract::query();
            $activeContractsQuery = \App\Models\Contract::where('status', 'active');
            $gracePeriodContractsQuery = \App\Models\Contract::where('status', 'grace_period');

            if (! $isAdmin) {
                $totalContractsQuery->where('created_by', $userId);
                $activeContractsQuery->where('created_by', $userId);
                $gracePeriodContractsQuery->where('created_by', $userId);
            }

            $totalContracts = $totalContractsQuery->count();
            $activeContracts = $activeContractsQuery->count();
            $gracePeriodContracts = $gracePeriodContractsQuery->count();
        } catch (\Throwable $e) {
            $totalContracts = 0;
            $activeContracts = 0;
            $gracePeriodContracts = 0;
        }
        
        $gmailCreds = \App\Services\GmailService::getCredentials();
        $gmailWatch = [
            'active'          => (bool) \Illuminate\Support\Facades\Cache::get('gmail_watch_active', false),
            'topic'           => \Illuminate\Support\Facades\Cache::get('gmail_watch_topic') ?: $gmailCreds['topic_name'],
            'expiration'      => \Illuminate\Support\Facades\Cache::get('gmail_watch_expiration'),
            'last_history_id' => \Illuminate\Support\Facades\Cache::get('gmail_last_history_id'),
        ];

        return view('settings.index', [
            'user'                 => $user,
            'unreadNotifications'  => $unreadNotifications,
            'totalContracts'       => $totalContracts,
            'activeContracts'      => $activeContracts,
            'gracePeriodContracts' => $gracePeriodContracts,
            'gmailCreds'           => $gmailCreds,
            'gmailWatch'           => $gmailWatch,
        ]);
    }

    /**
     * Update konfigurasi Gmail API
     */
    public function updateGmail(Request $request)
    {
        $validated = $request->validate([
            'gmail_client_id'     => 'nullable|string|max:255',
            'gmail_client_secret' => 'nullable|string|max:255',
            'gmail_refresh_token' => 'nullable|string|max:255',
            'gmail_pubsub_topic'  => 'nullable|string|max:255',
            'gmail_from_email'    => 'nullable|email|max:255',
            'gmail_from_name'     => 'nullable|string|max:255',
        ]);

        \App\Services\GmailService::updateEnv([
            'GMAIL_CLIENT_ID'     => $validated['gmail_client_id'] ?? '',
            'GMAIL_CLIENT_SECRET' => $validated['gmail_client_secret'] ?? '',
            'GMAIL_REFRESH_TOKEN' => $validated['gmail_refresh_token'] ?? '',
            'GMAIL_PUBSUB_TOPIC'  => $validated['gmail_pubsub_topic'] ?? '',
            'GMAIL_FROM_EMAIL'    => $validated['gmail_from_email'] ?? '',
            'GMAIL_FROM_NAME'     => $validated['gmail_from_name'] ?? '',
        ]);

        return redirect()->to(route('settings.index') . '#gmail')
            ->with('success', 'Konfigurasi Gmail API berhasil disimpan.');
    }

    /**
     * Display profile page
     */
    public function profile()
    {
        $unreadNotifications = ContractNotification::where('is_read', false)->count();
        $user = User::findOrFail(Auth::id());

        try {
            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';

            $totalContractsQuery = \App\Models\Contract::query();
            $activeContractsQuery = \App\Models\Contract::where('status', 'active');
            $gracePeriodContractsQuery = \App\Models\Contract::where('status', 'grace_period');

            if (! $isAdmin) {
                $totalContractsQuery->where('created_by', $userId);
                $activeContractsQuery->where('created_by', $userId);
                $gracePeriodContractsQuery->where('created_by', $userId);
            }

            $totalContracts = $totalContractsQuery->count();
            $activeContracts = $activeContractsQuery->count();
            $gracePeriodContracts = $gracePeriodContractsQuery->count();
        } catch (\Throwable $e) {
            $totalContracts = 0;
            $activeContracts = 0;
            $gracePeriodContracts = 0;
        }

        return view('profile.index', [
            'user' => $user,
            'unreadNotifications' => $unreadNotifications,
            'totalContracts' => $totalContracts,
            'activeContracts' => $activeContracts,
            'gracePeriodContracts' => $gracePeriodContracts,
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'language' => 'required|in:id,en',
            'timezone' => 'required|string',
            'notifications_email' => 'boolean',
            'notifications_dashboard' => 'boolean',
            'notifications_whatsapp' => 'boolean',
            'theme_dark' => 'boolean',
        ]);

        // Update user profile
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'position' => $validated['position'] ?? null,
            'division' => $validated['division'] ?? null,
        ]);

        // Store settings in session or database (you may need to create a settings table)
        session([
            'user_language' => $validated['language'],
            'user_timezone' => $validated['timezone'],
            'notifications_email' => $validated['notifications_email'] ?? false,
            'notifications_dashboard' => $validated['notifications_dashboard'] ?? false,
            'notifications_whatsapp' => $validated['notifications_whatsapp'] ?? false,
            'theme_dark' => $validated['theme_dark'] ?? false,
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }

    /**
     * Change password dengan validasi keamanan
     */
    public function changePassword(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',           // Minimal satu huruf kecil
                'regex:/[A-Z]/',           // Minimal satu huruf besar
                'regex:/[0-9]/',           // Minimal satu angka
                'regex:/[@$!%*?&]/',       // Minimal satu karakter spesial
                'confirmed',
                'different:current_password', // Password baru berbeda dari password lama
            ],
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'password.required' => 'Password baru harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*?&)',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.different' => 'Password baru harus berbeda dengan password sebelumnya',
        ]);

        $user = User::findOrFail(Auth::id());

        // Verifikasi password saat ini
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                ->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log the password change for security audit
        try {
            Log::info('Password changed for user: ' . $user->email, [
                'user_id' => $user->id,
                'timestamp' => now(),
                'ip_address' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            // Silent fail untuk logging
        }

        return back()
            ->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru.');
    }
}
