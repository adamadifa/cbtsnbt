<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    
    @php 
        $siteTitle = \App\Models\Setting::getValue('site_title', 'Lulus SNBT'); 
        $siteLogo = \App\Models\Setting::getValue('site_logo');
        $contactWa = \App\Models\Setting::getValue('contact_whatsapp', '6281234567890');
        $adminEmail = \App\Models\Setting::getValue('admin_email', 'admin@cbt.test');
    @endphp

    <title>{{ $siteTitle }} - Platform Ujian Online</title>
    <meta name="description" content="Simulasi CBT realtime, analisis nilai IRT, pembahasan lengkap, dan sertifikat hasil untuk persiapan SNBT Anda.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .bg-grid-pattern {
            background-size: 30px 30px;
            background-image: linear-gradient(to right, rgba(10, 34, 64, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(10, 34, 64, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-indigo-600 selection:text-white min-h-screen flex flex-col justify-between bg-grid-pattern">

    {{-- Mobile Header --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 py-3 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            @if($siteLogo)
                <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain bg-slate-50 p-0.5 shadow-xs">
            @else
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-sm">
                    {{ substr($siteTitle, 0, 1) }}
                </div>
            @endif
            <span class="text-sm font-black text-slate-900 tracking-tight">
                {{ explode(' ', $siteTitle)[0] }} <span class="text-indigo-600">{{ explode(' ', $siteTitle)[1] ?? '' }}</span>
            </span>
        </a>

        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" 
                       class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm">
                        Masuk
                    </a>
                @endauth
            @endif
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-grow px-4 py-8 space-y-12">
        
        {{-- Hero Section --}}
        <section class="text-center space-y-6">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                <span class="text-[9px] font-extrabold text-indigo-700 uppercase tracking-wider">Simulasi CBT Terkini</span>
            </div>
            
            <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">
                Persiapkan Kelulusan <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">Impian Ujian Anda</span>
            </h1>
            
            <p class="text-slate-500 text-xs leading-relaxed max-w-sm mx-auto">
                Rasakan pengalaman ujian sesungguhnya dengan sistem Computer Based Test (CBT) tercanggih, analisis radar akurasi, dan sertifikat instan.
            </p>

            <div class="flex flex-col gap-3 pt-2 max-w-xs mx-auto">
                @auth
                    <a href="{{ url('/dashboard') }}" 
                       class="w-full py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md active:scale-[0.98] transition-transform text-center">
                        Mulai Simulasi Ujian
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="w-full py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md active:scale-[0.98] transition-transform text-center">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" 
                       class="w-full py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider active:scale-[0.98] transition-transform text-center">
                        Masuk Portal Siswa
                    </a>
                @endauth
            </div>
        </section>

        {{-- Mobile Mockup Widget --}}
        <section class="relative mx-auto max-w-xs bg-gradient-to-tr from-indigo-500/10 to-violet-500/10 rounded-2xl p-4 border border-indigo-50/50 space-y-4 shadow-xl">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-600/5 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-violet-600/5 rounded-full blur-2xl"></div>
            
            <div class="bg-white/90 backdrop-blur-md rounded-xl p-3 border border-slate-100 shadow-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[8px] font-black text-indigo-600 uppercase tracking-wider">Simulasi Ujian</span>
                    <span class="px-1.5 py-0.5 bg-emerald-50 rounded text-[8px] font-bold text-emerald-600">Aktif</span>
                </div>
                <div class="h-1.5 w-2/3 bg-slate-100 rounded"></div>
            </div>

            <div class="flex justify-center my-4">
                <div class="w-24 h-24 rounded-full border-4 border-indigo-600/15 border-t-indigo-600 flex items-center justify-center bg-white shadow-xs">
                    <div class="text-center">
                        <span class="text-xl font-black text-slate-900">85%</span>
                        <span class="text-[7px] font-bold text-slate-400 uppercase block tracking-wide">Akurasi</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-xl p-3 text-white shadow-xs flex items-center justify-between">
                <div class="space-y-0.5">
                    <span class="text-[7px] font-extrabold text-indigo-400 uppercase tracking-wider">Subtest</span>
                    <span class="text-[9px] font-bold text-slate-200 block truncate max-w-[130px]">Penalaran Kuantitatif</span>
                </div>
                <span class="text-[9px] font-black bg-white/10 px-1.5 py-0.5 rounded">08:45</span>
            </div>
        </section>

        {{-- Features Mobile --}}
        <section class="bg-white border border-slate-200/60 rounded-2xl p-5 space-y-6">
            <div class="text-center space-y-1">
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Kenapa Memilih Kami?</h2>
                <p class="text-slate-500 text-[10px] font-semibold">Fitur lengkap siap temani belajarmu.</p>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">Manajemen Waktu</h3>
                        <p class="text-slate-500 text-[10px] leading-relaxed mt-0.5">Batasan durasi per-subtest disesuaikan standar ujian nasional.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">Analisis Radar</h3>
                        <p class="text-slate-500 text-[10px] leading-relaxed mt-0.5">Statistik kelemahan & kekuatan materi divisualisasikan dengan grafik.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 border border-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">Anti Curang</h3>
                        <p class="text-slate-500 text-[10px] leading-relaxed mt-0.5">Deteksi perpindahan tab browser (*tab switch*) secara otomatis.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    {{-- Mobile Footer --}}
    <footer class="bg-slate-900 text-white border-t border-slate-800 p-6 space-y-6">
        <div class="space-y-3">
            <a href="/" class="flex items-center gap-2">
                @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="w-6 h-6 rounded-lg object-contain bg-slate-800 p-0.5 shadow-sm">
                @else
                    <div class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-xs">
                        {{ substr($siteTitle, 0, 1) }}
                    </div>
                @endif
                <span class="text-xs font-black text-white tracking-tight">
                    {{ $siteTitle }}
                </span>
            </a>
            <p class="text-slate-400 text-[10px] leading-relaxed">
                Platform CBT nomor satu untuk menguji kesiapan masuk Perguruan Tinggi Negeri impian Anda.
            </p>
        </div>

        <div class="border-t border-slate-800/80 pt-4 space-y-2 text-[10px] text-slate-400">
            <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-4a2 2 0 011.78 0l8 4A2 2 0 0122 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.25 0l-2.25 1.5"></path>
                </svg>
                <span>{{ $adminEmail }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <span>+{{ $contactWa }}</span>
            </div>
        </div>

        <div class="border-t border-slate-800/80 pt-4 text-center text-[9px] text-slate-500">
            <p>&copy; {{ date('Y') }} {{ $siteTitle }}.</p>
        </div>
    </footer>

</body>
</html>
