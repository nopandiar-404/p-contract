@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('title', 'Daftar - Contract Monitoring')

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
        <main class="login-container register-container" aria-labelledby="registerHeading">
            <section class="login-card auth-card" role="region" aria-label="Register form">
                <header class="brand center">
                    <img src="{{ asset('images/logo-pelindo-white.png') }}" alt="Pelindo Logo" width="120" height="auto"
                        class="brand-logo" />
                    <h1 id="registerHeading">Buat Akun Baru</h1>
                    <p class="muted">Isi data Anda untuk membuat akun</p>
                </header>

                <form method="POST" action="{{ route('register') }}" class="login-form" novalidate>
                    @csrf

                    <div class="field">
                        <label for="name">Nama Lengkap</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-person"></i></span>
                            <input id="name" name="name" type="text" required value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap Anda"
                                class="input with-icon @error('name') is-invalid @enderror">
                        </div>
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input id="email" name="email" type="email" inputmode="email" required
                                value="{{ old('email') }}" placeholder="Masukkan email Anda"
                                class="input with-icon @error('email') is-invalid @enderror" aria-describedby="emailHelp">
                        </div>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control input-group password-control">
                            <span class="input-icon"><i class="bi bi-lock"></i></span>
                            <input id="password" name="password" type="password" required
                                placeholder="Buat password minimal 8 karakter"
                                class="input with-icon @error('password') is-invalid @enderror" aria-describedby="pwdHelp">

                        </div>
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <small class="muted">Password harus mengandung huruf besar, kecil, angka, dan simbol</small>
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                placeholder="Ulangi password Anda" class="input with-icon">
                        </div>
                    </div>

                    <div class="form-actions">
                        <label class="remember">
                            <input type="checkbox" name="terms" required>
                            <span>Saya menyetujui Syarat & Ketentuan</span>
                        </label>
                    </div>

                    <button type="submit" class="btn primary">Daftar Sekarang</button>

                    <div class="divider"><span>atau</span></div>

                    <div class="login-footer muted">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                    </div>
                </form>
            </section>

            @include('auth.partials.login-promo')
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
                // Simple password toggle + small client-side checks for register
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.password-toggle').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            const input = this.parentElement.querySelector('input');
                            if (!input) return;
                            const icon = this.querySelector('i');
                            if (input.type === 'password') {
                                input.type = 'text';
                                if (icon) {
                                    icon.classList.remove('bi-eye-slash');
                                    icon.classList.add('bi-eye');
                                }
                            } else {
                                input.type = 'password';
                                if (icon) {
                                    icon.classList.remove('bi-eye');
                                    icon.classList.add('bi-eye-slash');
                                }
                            }
                        });
                    });

                    // simple strength preview for password (non-blocking)
                    const pwd = document.getElementById('password');
                    if (pwd) {
                        pwd.addEventListener('input', function() {
                            // reuse logic from login page script if available
                            const evt = new Event('input', {
                                bubbles: true
                            });
                            pwd.dispatchEvent(evt);
                        });
                    }
                });
            </script>
        @endif
    @endpush

@endsection
