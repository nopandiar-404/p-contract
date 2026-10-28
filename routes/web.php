<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\BoqController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\GmailAuthController;
use App\Http\Controllers\GmailWebhookController;

// Ensure {contract} route parameter only matches numeric IDs so named routes like /contracts/export are not treated as {contract}
Route::pattern('contract', '[0-9]+');

// Google Cloud Pub/Sub Webhook for Gmail Push Notifications (Unauthenticated & CSRF exempt)
Route::post('/api/gmail/webhook', [GmailWebhookController::class, 'handleWebhook'])->name('gmail.webhook');
Route::post('/gmail/webhook', [GmailWebhookController::class, 'handleWebhook']);
Route::post('/gmail/webhook/test', [GmailWebhookController::class, 'testWebhook'])->name('gmail.webhook.test');


Route::get('/', function () {
    return redirect()->route('dashboard');
});



// Route dashboard dilindungi oleh middleware auth dan dibatasi 10 permintaan per menit
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/grace-period', [DashboardController::class, 'gracePeriodReport'])->name('dashboard.grace-period');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/distribution', [DashboardController::class, 'distribution'])->name('reports.distribution');
    // JSON summary endpoint used by AJAX filter in reports view
    Route::get('/reports/summary', [DashboardController::class, 'distribution'])->name('reports.summary');
    // Also expose under /api prefix but keep web middleware so session cookies work for frontend fetches
    Route::get('/api/reports/summary', [DashboardController::class, 'distribution'])->name('api.reports.summary');
    
    // Analytics detailed page
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/profile', [SettingsController::class, 'profile'])->name('profile.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/gmail', [SettingsController::class, 'updateGmail'])->name('settings.gmail.update');
    Route::post('/settings/change-password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');

    // Exports: declare before resource routes so '/contracts/export' is matched explicitly
    Route::get('/contracts/export', [ContractController::class, 'export'])->name('contracts.export');
    Route::post('/contracts/bulk-delete', [ContractController::class, 'bulkDelete'])->name('contracts.bulk-delete');

    // Contract-specific routes
    Route::post('/contracts/{contract}/update-status', [ContractController::class, 'updateStatus'])
        ->name('contracts.update-status');
    Route::post('/contracts/{contract}/renew', [ContractController::class, 'renew'])
        ->name('contracts.renew');
    // Send reminder (WhatsApp / push)
    Route::post('/contracts/{contract}/send-reminder', [ContractController::class, 'sendReminder'])
        ->name('contracts.send-reminder');
    // Send reminder via Gmail API
    Route::post('/contracts/{contract}/send-gmail-reminder', [ContractController::class, 'sendGmailReminder'])
        ->name('contracts.send-gmail-reminder');

    // Gmail Watch & Pub/Sub management
    Route::post('/gmail/watch', [GmailWebhookController::class, 'watch'])->name('gmail.watch');
    Route::post('/gmail/stop', [GmailWebhookController::class, 'stop'])->name('gmail.stop');
    Route::get('/gmail/watch/status', [GmailWebhookController::class, 'status'])->name('gmail.watch.status');

    // Gmail OAuth authorization and callback routes
    Route::get('/gmail/oauth', [GmailAuthController::class, 'redirect'])->name('gmail.oauth');
    Route::get('/gmail/callback', [GmailAuthController::class, 'callback'])->name('gmail.callback');

    Route::post('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])
        ->name('contracts.terminate');
    Route::get('/contracts/statistics', [ContractController::class, 'statistics'])
        ->name('contracts.statistics');
    Route::get('/contracts/expiring-soon', [ContractController::class, 'expiringSoon'])
        ->name('contracts.expiring-soon');
    Route::post('/contracts/bulk-actions', [ContractController::class, 'bulkActions'])
        ->name('contracts.bulk-actions');
    Route::post('/contracts/check-number', [ContractController::class, 'checkContractNumber'])
        ->name('contracts.check-number');
    Route::get('/clients/{client}/contracts', [ContractController::class, 'getByClient'])
        ->name('contracts.by-client');
    Route::get('/clients/{client}/details', [ContractController::class, 'getClientDetails'])
        ->name('clients.details');

    // Download contract documents
    Route::get('/contracts/{contract}/print-pdf', [ContractController::class, 'printPdf'])
        ->name('contracts.print-pdf');
    Route::get('/contracts/{contract}/download-pdf', [ContractController::class, 'downloadPdf'])
        ->name('contracts.download-pdf');
    
    // Manage PDF files
    Route::post('/contracts/{contract}/rename-pdf', [ContractController::class, 'renamePdf'])
        ->name('contracts.rename-pdf');
    Route::post('/contracts/{contract}/delete-pdf', [ContractController::class, 'deletePdf'])
        ->name('contracts.delete-pdf');

    // Resource routes
    Route::resource('contracts', ContractController::class);
    Route::get('/boq', [BoqController::class, 'index'])->name('boq.index');
    Route::resource('clients', ClientController::class);
    Route::resource('notifications', NotificationController::class)->only(['index', 'show']);
    Route::resource('contract-types', ContractTypeController::class)->except(['show', 'create', 'edit']);

    // Chart of Accounts (Jenis COA)
    Route::resource('coa', CoaController::class);

    // Notification actions
    Route::prefix('notifications')->group(function () {
        Route::get('/{notification}/status', [NotificationController::class, 'status'])->name('notifications.status');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::post('/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');
        Route::post('/{notification}/details', [NotificationController::class, 'details'])->name('notifications.details');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // Client extra routes
    Route::prefix('clients')->group(function () {
        Route::post('/bulk-actions', [ClientController::class, 'bulkActions'])->name('clients.bulk-actions');
        Route::get('/export', [ClientController::class, 'export'])->name('clients.export');
    });

    // Debug routes (local only)
    if (app()->environment('local')) {
        Route::get('/_debug/export/save/{type}', [ContractController::class, 'exportAndSave'])->name('debug.export.save');
        Route::get('/_debug/status', function () {
            $status = [
                'php' => phpversion(),
                'extensions' => [
                    'mbstring' => extension_loaded('mbstring'),
                    'gd' => extension_loaded('gd'),
                    'dom' => extension_loaded('dom'),
                    'zip' => extension_loaded('zip'),
                ],
                'classes' => [
                    'PhpSpreadsheet' => class_exists('\\PhpOffice\\PhpSpreadsheet\\Spreadsheet'),
                    'DomPDF_Facade' => class_exists('\\Barryvdh\\DomPDF\\Facade\\Pdf'),
                    'Dompdf_Raw' => class_exists('\\Dompdf\\Dompdf'),
                ]
            ];

            return response()->json($status);
        })->name('debug.status');
        // Quick preview route for grace period report (no auth, local only)
        Route::get('/preview/grace-period', function () {
            $contracts = \App\Models\Contract::inGracePeriod()->get();
            return view('dashboard.grace-period-report', ['contracts' => $contracts]);
        })->name('preview.grace-period');

        // Local-only WhatsApp health check
        Route::get('/debug/health/whatsapp', function () {
            $phoneId = config('services.whatsapp.phone_number_id') ?? env('WHATSAPP_PHONE_NUMBER_ID');
            $accessToken = config('services.whatsapp.access_token') ?? env('WHATSAPP_ACCESS_TOKEN');
            $apiUrl = config('services.whatsapp.api_url') ?? env('WHATSAPP_API_URL');

            return response()->json([
                'configured' => !empty($accessToken) && (!empty($phoneId) || !empty($apiUrl)),
                'phone_number_id' => $phoneId ?: null,
                'access_token_set' => !empty($accessToken),
                'api_url' => $apiUrl ?: null,
            ]);
        })->name('debug.health.whatsapp');

        // Dev-only quick delete for automated tests (local/debug only)
        Route::get('/dev/contracts-delete/{id}', function ($id) {
            $contract = \App\Models\Contract::find($id);
            if (!$contract) {
                return response()->json(['deleted' => false, 'message' => 'Not found'], 404);
            }
            try {
                $contract->delete();
                return response()->json(['deleted' => true]);
            } catch (\Exception $e) {
                return response()->json(['deleted' => false, 'message' => $e->getMessage()], 500);
            }
        })->name('dev.contracts.delete');

        // Gmail OAuth routes are registered in the authenticated routes above.
    }
});

Route::get('/contracts/{contract}/download-pdf/scan', [ContractController::class, 'downloadPdfScan'])
    ->name('contracts.download-pdf.scan')
    ->middleware('signed');

// Route::middleware(['auth'])->group(function () {
//     Route::resource('contracts', ContractController::class);
//     Route::resource('clients', ClientController::class);
// });

// // Jika belum setup auth, gunakan route tanpa middleware
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::get('/dashboard/grace-period', [DashboardController::class, 'gracePeriodReport'])->name('dashboard.grace-period');

// Route::middleware(['api'])->group(function () {
//     Route::get('/dashboard/stats', [DashboardApiController::class, 'getStats']);
//     Route::get('/notifications/unread-count', [DashboardApiController::class, 'unreadCount']);
//     Route::get('/chart-data', [DashboardApiController::class, 'chartData']);
//     Route::post('/contracts/{contract}/send-reminder', [DashboardApiController::class, 'sendReminder']);
// });
Auth::routes();

// Direct password update from request page (CSRF protected, rate-limited)
Route::post('/password/direct-update', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'directUpdate'])
    ->name('password.direct-update')
    ->middleware('throttle:5,1');

// Local-only quick login helper (no auth required) to facilitate dev smoke-checks
if (app()->environment('local')) {
    Route::get('/_debug/login-test', function () {
        $email = 'local-test@example.com';
        $user = \App\Models\User::where('email', $email)->first();
        if (! $user) {
            $user = \App\Models\User::create([
                'name' => 'Local Test',
                'email' => $email,
                'password' => bcrypt('secret'),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);
        return redirect()->route('dashboard');
    })->name('debug.login-test');
}

// Note: temporary dev-only preview and unauthenticated delete routes removed.
// For local debugging use the authenticated debug routes defined earlier inside the auth middleware block.

// Override default logout to redirect to login page
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    $request->session()->flash('success', 'Anda telah logout.');
    return redirect()->route('login');
})->name('logout');

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');
