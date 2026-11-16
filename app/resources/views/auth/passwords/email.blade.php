@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('title', 'Reset Password - Contract Monitoring')

@section('content')
    @php
        $manifestPath = public_path('build/manifest.json');
        $useVite = false;
        if (file_exists($manifestPath)) {
            $content = @file_get_contents($manifestPath);
            $manifest = @json_decode($content, true) ?: [];
            if (is_array($manifest)) {
                if (
                    array_key_exists('resources/css/login.css', $manifest) ||
                    array_key_exists('resources/js/login.js', $manifest)
                ) {
                    $useVite = true;
                }
            }
        }
    @endphp

    <div class="login-page register-page">
        <main class="register-container" aria-labelledby="resetHeading">
            <section class="login-card auth-card" role="region" aria-label="Reset password form">
                <header class="brand center">
                    <img src="{{ asset('images/pelindo-contract.svg') }}" alt="Pelindo Logo" width="120"
                        height="auto"
                        class="brand-logo" />
                    <h1 id="resetHeading">Reset Password</h1>
                    <p class="muted">Masukkan email serta password baru untuk memvalidasi akun Anda.</p>
                </header>

                <form method="POST" action="{{ route('password.direct-update') }}" class="login-form" novalidate>
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input id="email" type="email" name="email" required
                                value="{{ old('email') }}" placeholder="Masukkan email Anda"
                                class="input with-icon @error('email') is-invalid @enderror" autocomplete="email"
                                autofocus>
                        </div>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password Baru</label>
                        <div class="control input-group password-control">
                            <span class="input-icon"><i class="bi bi-lock"></i></span>
                            <input id="password" type="password" name="password" required
                                placeholder="Masukkan password baru minimal 8 karakter"
                                class="input with-icon @error('password') is-invalid @enderror"
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <small class="muted">Password harus mengandung huruf besar, kecil, angka, dan simbol</small>
                    </div>

                    <div class="field">
                        <label for="password-confirm">Konfirmasi Password Baru</label>
                        <div class="control input-group password-control">
                            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                            <input id="password-confirm" type="password" name="password_confirmation" required
                                placeholder="Ulangi password baru Anda"
                                class="input with-icon @error('password_confirmation') is-invalid @enderror"
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" aria-label="Toggle confirmation password visibility">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <label class="remember">
                            <input type="checkbox" id="showPasswordCheckbox">
                            <span>Tampilkan password</span>
                        </label>
                    </div>

                    <button type="submit" class="btn primary">Reset Password</button>

                    <div class="divider"><span>atau</span></div>

                    <div class="login-footer muted">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                    </div>
                </form>
            </section>
        </main>
    </div>

    @push('styles')
        @if ($useVite)
            @vite(['resources/css/login.css'])
        @else
            <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        @endif
    @endpush

    @push('scripts')
        @if ($useVite)
            @vite(['resources/js/login.js'])
        @else
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const password = document.getElementById('password');
                    const passwordConfirm = document.getElementById('password-confirm');
                    const showCheckbox = document.getElementById('showPasswordCheckbox');
                    const toggleButtons = document.querySelectorAll('.password-toggle');

                    function toggleVisibility(input) {
                        if (!input) return;
                        input.type = input.type === 'password' ? 'text' : 'password';
                    }

                    toggleButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
                            const input = this.parentElement.querySelector('input');
                            toggleVisibility(input);
                            const icon = this.querySelector('i');
                            if (icon) {
                                icon.classList.toggle('bi-eye');
                                icon.classList.toggle('bi-eye-slash');
                            }
                        });
                    });

                    if (showCheckbox) {
                        showCheckbox.addEventListener('change', function() {
                            const type = this.checked ? 'text' : 'password';
                            if (password) password.type = type;
                            if (passwordConfirm) passwordConfirm.type = type;
                            toggleButtons.forEach(function(button) {
                                const icon = button.querySelector('i');
                                if (icon) {
                                    icon.classList.toggle('bi-eye', this.checked);
                                    icon.classList.toggle('bi-eye-slash', !this.checked);
                                }
                            }, this);
                        });
                    }

                    let mismatchEl = document.getElementById('passwordMismatch');
                    if (!mismatchEl && passwordConfirm) {
                        mismatchEl = document.createElement('div');
                        mismatchEl.id = 'passwordMismatch';
                        mismatchEl.className = 'error';
                        mismatchEl.style.display = 'none';
                        mismatchEl.textContent = 'Password tidak cocok.';
                        passwordConfirm.parentNode.appendChild(mismatchEl);
                    }

                    function validateMatch() {
                        if (!password || !passwordConfirm) return true;
                        if (password.value !== passwordConfirm.value) {
                            mismatchEl.style.display = 'block';
                            return false;
                        }
                        mismatchEl.style.display = 'none';
                        return true;
                    }

                    [password, passwordConfirm].forEach(function(el) {
                        if (!el) return;
                        el.addEventListener('input', validateMatch);
                    });

                    const form = document.querySelector('form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            if (!validateMatch()) {
                                e.preventDefault();
                                passwordConfirm.focus();
                            }
                        });
                    }
                });
            </script>
        @endif
    @endpush
@endsection
