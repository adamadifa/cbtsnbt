<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Masuk</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .app-bg-gradient {
            background: linear-gradient(180deg, #f4f6f8 0%, #ffffff 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-indigo-100 selection:text-indigo-700 min-h-screen flex flex-col justify-between app-bg-gradient">

    {{-- App Header --}}
    <header class="px-4 py-4 flex items-center justify-between z-10">
        <a href="/" class="w-10 h-10 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-650 active:scale-90 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <span class="text-sm font-extrabold text-slate-700 uppercase tracking-wider">Masuk Portal</span>
        <div class="w-10"></div> {{-- Spacer --}}
    </header>

    {{-- Main Container --}}
    <main class="flex-grow px-6 flex flex-col justify-center max-w-md mx-auto w-full space-y-8 pb-12">
        @php 
            $siteTitle = \App\Models\Setting::getValue('site_title', 'Lulus SNBT'); 
            $siteLogo = \App\Models\Setting::getValue('site_logo');
        @endphp

        {{-- Branding Area --}}
        <div class="text-center space-y-4">
            <div class="inline-flex justify-center">
                @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="w-16 h-16 rounded-2xl object-contain bg-white p-1 shadow-md border border-slate-100">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black text-2xl shadow-md shadow-indigo-600/20">
                        {{ substr($siteTitle, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="space-y-1">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                <p class="text-slate-400 text-xs font-semibold">Silakan masuk untuk melanjutkan belajar & simulasi</p>
            </div>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div class="space-y-1.5">
                <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                        </svg>
                    </span>
                    <input id="email" 
                           class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200/80 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-xs focus:outline-none" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username" 
                           placeholder="nama@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-[10px] font-extrabold text-indigo-600 hover:text-indigo-700" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </span>
                    <input id="password" 
                           class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200/80 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-xs focus:outline-none"
                           type="password"
                           name="password"
                           required 
                           autocomplete="current-password"
                           placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me & Forgot Password wrapper -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4 transition-all" name="remember">
                <span class="ms-2 text-xs text-slate-500 font-semibold">Ingat saya di perangkat ini</span>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all active:scale-[0.98] shadow-lg shadow-indigo-600/10 text-xs tracking-wider uppercase">
                    Masuk Sekarang
                </button>
            </div>

            @if (Route::has('register'))
                <div class="text-center pt-2">
                    <p class="text-xs text-slate-400 font-semibold">
                        Belum punya akun? 
                        <a class="text-indigo-600 hover:text-indigo-700 font-black" href="{{ route('register') }}">
                            Daftar Sekarang
                        </a>
                    </p>
                </div>
            @endif
        </form>
    </main>

    {{-- Simple Copyright Footer --}}
    <footer class="py-6 text-center text-[10px] text-slate-400/80">
        &copy; {{ date('Y') }} {{ $siteTitle }}.
    </footer>

</body>
</html>
