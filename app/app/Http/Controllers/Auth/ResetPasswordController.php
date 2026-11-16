<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\PasswordChangeLog;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'token.required' => 'Token reset password diperlukan.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }

    /**
     * Directly update a user's password from a request form (email + password).
     * This endpoint is CSRF-protected and rate-limited by route middleware.
     */
    public function directUpdate(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ], $this->validationErrorMessages());

        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        $user->password = Hash::make($data['password']);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Server-side audit logging (do NOT log the actual password)
        Log::info('Password changed via direct-update', [
            'user_id' => $user->getKey(),
            'email' => $user->email,
            'performed_by' => \Illuminate\Support\Facades\Auth::id() ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Persist audit record to DB (safe: do not store password)
        try {
            PasswordChangeLog::create([
                'user_id' => $user->getKey(),
                'email' => $user->email,
                'performed_by' => \Illuminate\Support\Facades\Auth::id() ?? null,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'source' => 'direct-update',
            ]);
        } catch (\Throwable $e) {
            // If DB logging fails, keep going but log the failure
            Log::warning('Failed to write password change audit record', ['error' => $e->getMessage()]);
        }

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan login.');
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';
}
