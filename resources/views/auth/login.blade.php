@extends('layouts.app')

@section('hideNavbar')
@endsection
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

    <div class="login-page">
        <main class="login-container" aria-labelledby="loginHeading">

            <section class="login-card auth-card" role="region" aria-label="Login form">
                <header class="brand center">
                    <img src="{{ asset('images/logo-pelindo-white.png') }}" alt="Pelindo Logo" width="120" height="auto"
                        class="brand-logo" />
                    <h1 id="loginHeading">Selamat Datang</h1>
                    <p class="muted">Masuk untuk mengelola kontrak dan memantau status masa tenggang.</p>
                </header>

                <form method="POST" action="{{ route('login') }}" class="login-form" novalidate>
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="control">
                            <input id="email" name="email" type="email" inputmode="email" autocomplete="email" autofocus required
                                value="{{ old('email') }}" placeholder="Masukkan email Anda"
                                class="input @error('email') invalid @enderror" aria-describedby="emailHelp">
                        </div>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control password-control">
                            <input id="password" name="password" type="password" required autocomplete="current-password"
                                placeholder="Masukkan password Anda" class="input @error('password') invalid @enderror"
                                aria-describedby="pwdHelp">
                            <button type="button" class="password-toggle" aria-label="Tampilkan/Sembunyikan Password" title="Tampilkan atau sembunyikan password" aria-pressed="false">
                                <span class="icon-eye"></span>
                            </button>
                        </div>
                        {{-- <div id="pwdStrength" class="pwd-strength" aria-hidden></div> --}}
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <label class="remember">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="link-muted" href="{{ route('password.request') }}">Lupa Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn primary">Masuk</button>

                    <div class="divider"><span>atau</span></div>

                    <div class="login-footer muted">Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
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
            <script src="{{ asset('js/login.js') }}" defer></script>
        @endif
    @endpush

@endsection
