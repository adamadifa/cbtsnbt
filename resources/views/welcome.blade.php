<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    @php 
        $siteTitle = \App\Models\Setting::getValue('site_title', 'Lulus SNBT'); 
        $siteLogo = \App\Models\Setting::getValue('site_logo');
        $contactWa = \App\Models\Setting::getValue('contact_whatsapp', '6281234567890');
        $adminEmail = \App\Models\Setting::getValue('admin_email', 'admin@cbt.test');
    @endphp

    <title>{{ $siteTitle }} - Platform Ujian Online Terpercaya</title>
    <meta name="description" content="Persiapkan diri Anda untuk menghadapi SNBT dan ujian masuk perguruan tinggi dengan simulasi CBT realtime, analisis nilai IRT, pembahasan lengkap, dan sertifikat hasil.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(99, 102, 241, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-indigo-500 selection:text-white min-h-screen flex flex-col justify-between bg-grid-pattern">

    {{-- Header / Navbar --}}
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-slate-50 p-0.5 shadow-md">
                @else
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-indigo-600/20">
                        {{ substr($siteTitle, 0, 1) }}
                    </div>
                @endif
                <span class="text-lg font-black text-slate-900 tracking-tight">
                    {{ explode(' ', $siteTitle)[0] }} <span class="text-indigo-600">{{ explode(' ', $siteTitle)[1] ?? '' }}</span>
                </span>
            </a>

            <nav class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-indigo-600/10 flex items-center gap-2">
                            Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-5 py-2.5 text-slate-700 hover:text-slate-900 text-xs font-bold uppercase tracking-wider transition-all">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-indigo-600/10">
                                Daftar Akun
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-grow">
        {{-- Hero Section --}}
        <section class="max-w-7xl mx-auto px-6 pt-16 pb-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                    <span class="text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest">Platform Simulasi Terkini</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-none">
                    Persiapkan Kelulusan <br class="hidden md:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">Impian Ujian Anda</span>
                </h1>
                
                <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Rasakan pengalaman ujian sesungguhnya dengan sistem Computer Based Test (CBT) tercanggih. Dilengkapi pembatasan waktu per-subtest, pemantauan pelanggaran realtime, grafik radar statistik akurasi, dan sertifikat instan.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-lg shadow-indigo-600/20 text-center">
                            Mulai Simulasi Ujian
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-lg shadow-indigo-600/20 text-center">
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" 
                           class="w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-bold uppercase tracking-wider transition-all text-center">
                            Masuk Portal Siswa
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Illustration / Mockup --}}
            <div class="lg:col-span-5 relative flex justify-center">
                <div class="relative w-full max-w-md aspect-square bg-gradient-to-tr from-indigo-500/10 to-violet-500/10 rounded-3xl p-6 border border-indigo-50/50 flex flex-col justify-between shadow-2xl overflow-hidden">
                    {{-- Graphic Ornaments --}}
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-violet-600/10 rounded-full blur-3xl"></div>
                    
                    {{-- Mini Header UI Mockup --}}
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm space-y-3 relative z-10">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">Simulasi Ujian Aktif</span>
                            <span class="px-2 py-0.5 bg-emerald-50 rounded text-[9px] font-bold text-emerald-600">Berjalan</span>
                        </div>
                        <div class="h-2 w-3/4 bg-slate-100 rounded"></div>
                        <div class="h-1.5 w-1/2 bg-slate-100 rounded"></div>
                    </div>

                    {{-- Center Visual Chart Mockup --}}
                    <div class="my-6 flex justify-center relative z-10">
                        <div class="w-32 h-32 rounded-full border-8 border-indigo-600/15 border-t-indigo-600 flex items-center justify-center relative shadow-inner bg-white">
                            <div class="text-center">
                                <span class="text-3xl font-black text-slate-900">85%</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase block tracking-wider mt-0.5">Akurasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- Mini Footer UI Mockup --}}
                    <div class="bg-slate-900 rounded-2xl p-4 text-white shadow-md flex items-center justify-between relative z-10">
                        <div class="space-y-1">
                            <span class="text-[8px] font-extrabold text-indigo-400 uppercase tracking-wider">Subtest Penawaran</span>
                            <span class="text-[10px] font-bold text-slate-200 block">Penalaran Kuantitatif</span>
                        </div>
                        <span class="text-[10px] font-black bg-white/10 px-2 py-1 rounded-lg">08:45</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Grid Section --}}
        <section class="bg-white border-y border-slate-200/60 py-20">
            <div class="max-w-7xl mx-auto px-6 space-y-12">
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Kenapa Memilih Platform Kami?</h2>
                    <p class="text-slate-500 text-xs font-semibold leading-relaxed">
                        Kami merancang fitur yang lengkap dan modern untuk membantu kesiapan akademis Anda secara komprehensif.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Feature 1 --}}
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-200 transition-all space-y-4 shadow-xs">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Manajemen Subtest Ketat</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Membatasi durasi pengerjaan untuk setiap subtest secara mandiri demi melatih ketepatan dan manajemen waktu seperti ujian SNBT asli.
                        </p>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-200 transition-all space-y-4 shadow-xs">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Analisis Hasil Ujian Lengkap</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Lihat skor akurasi Anda melalui visualisasi diagram radar, analisis benar/salah per-soal, dan penjelasan lengkap di setiap materi uji.
                        </p>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-200 transition-all space-y-4 shadow-xs">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Sistem Anti-Curang Terpadu</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Mendeteksi perpindahan tab browser (*tab switch*) dan hilangnya fokus pengerjaan secara otomatis untuk memastikan keadilan ujian.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-white border-t border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-6 space-y-4">
                <a href="/" class="flex items-center gap-3">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain bg-slate-800 p-0.5 shadow-sm">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-md">
                            {{ substr($siteTitle, 0, 1) }}
                        </div>
                    @endif
                    <span class="text-base font-black text-white tracking-tight">
                        {{ $siteTitle }}
                    </span>
                </a>
                <p class="text-slate-400 text-xs max-w-sm leading-relaxed">
                    Platform CBT nomor satu untuk menguji kesiapan masuk Perguruan Tinggi Negeri impian Anda dengan standar nasional.
                </p>
            </div>
            
            <div class="md:col-span-3 space-y-3">
                <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Navigasi</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-all">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-all">Pendaftaran</a></li>
                </ul>
            </div>

            <div class="md:col-span-3 space-y-3">
                <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Hubungi Kami</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-4a2 2 0 011.78 0l8 4A2 2 0 0122 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.25 0l-2.25 1.5"></path>
                        </svg>
                        <span class="truncate">{{ $adminEmail }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>+{{ $contactWa }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-8 mt-8 border-t border-slate-800 text-center text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} {{ $siteTitle }}. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
