<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900 overflow-hidden select-none">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Exam Interface</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; height: 100%; overflow: hidden; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Prevent selection during exam */
        .no-select {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="antialiased overflow-hidden h-full flex flex-col no-select"
    oncontextmenu="return false"
    oncopy="return false"
    oncut="return false"
    onpaste="return false">

    <!-- Modern Exam Header -->
    <header class="bg-indigo-700 text-white shadow-xl px-6 py-4 flex items-center justify-between z-50 shrink-0">
        <div class="flex items-center gap-6">
            <!-- Brand/Logo Area -->
            <div class="flex items-center gap-3 pr-6 border-r border-indigo-500/50">
                <div class="p-2 bg-white/10 rounded-xl backdrop-blur-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="hidden md:block">
                    <h1 class="text-sm font-bold tracking-wider uppercase text-indigo-100">CBT Tryout SNBT</h1>
                    <p class="text-base font-bold truncate max-w-[200px]">@yield('exam_title', 'Loading...')</p>
                </div>
            </div>

            <!-- Subtest Title -->
            <div class="hidden lg:flex flex-col">
                <span class="text-xs font-semibold text-indigo-200 uppercase">Subtes Aktif</span>
                <span class="text-lg font-bold">@yield('subtest_title', '...')</span>
            </div>
        </div>

        <!-- Timer & Actions -->
        <div class="flex items-center gap-4 sm:gap-10">
            <!-- Global Timer -->
            <div class="flex items-center gap-3 bg-indigo-800/50 px-5 py-2.5 rounded-2xl border border-indigo-400/20 backdrop-blur-sm">
                <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex flex-col leading-none">
                    <span class="text-[10px] font-bold text-indigo-200 uppercase mb-0.5">Sisa Waktu</span>
                    <span class="text-xl font-bold mono" id="exam-timer">--:--:--</span>
                </div>
            </div>

            <!-- User Info -->
            <div class="flex items-center gap-4 pl-4 border-l border-indigo-500/50">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-indigo-200 uppercase">Peserta</p>
                    <p class="text-sm font-bold truncate max-w-[150px]">{{ Auth::user()->name }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" 
                     class="w-10 h-10 rounded-xl border-2 border-indigo-400/30">
            </div>
            
            <button class="lg:hidden p-2 bg-indigo-800 rounded-lg" id="toggle-nav">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- Sidebar Navigation (Nomor Soal) -->
        @yield('exam_sidebar')

        <!-- Scrollable Content Area (Soal) -->
        <main class="flex-1 bg-slate-50 overflow-y-auto p-4 md:p-8 lg:p-12 relative">
            <div class="max-w-4xl mx-auto">
                @if (session('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl" role="alert">
                        <p class="font-bold">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
            
            <!-- Bottom Spacer for mobile -->
            <div class="h-24 md:hidden"></div>
        </main>
    </div>

    <!-- Exam Status Bar (Bottom) -->
    <footer class="bg-white border-t border-slate-200 px-6 py-4 flex items-center justify-between shrink-0 shadow-2xl z-50">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-full text-slate-600">
                <div class="w-2 h-2 rounded-full bg-slate-400 animate-pulse"></div>
                <span class="text-xs font-bold uppercase tracking-wider">Status: Terkoneksi</span>
            </div>
            <div class="hidden sm:flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-full text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="text-xs font-bold uppercase tracking-wider">Anti-Cheat Aktif</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
             @yield('exam_actions')
        </div>
    </footer>

    <!-- Anti-Cheat Overlay (Tab Switch) -->
    <div id="cheat-warning" class="fixed inset-0 bg-slate-900/95 z-[9999] hidden flex items-center justify-center p-6 text-center backdrop-blur-xl">
        <div class="max-w-md bg-white rounded-3xl p-8 shadow-2xl scale-in-center">
            <div class="w-20 h-20 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Peringatan Berbahaya!</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                Anda dilarang berpindah tab atau aplikasi selama ujian berlangsung. Aktivitas ini telah dicatat oleh sistem dan dilaporkan ke admin.
            </p>
            <button onclick="document.getElementById('cheat-warning').classList.add('hidden')" 
                    class="w-full bg-indigo-600 text-white font-bold py-4 px-6 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200">
                Lanjutkan Ujian
            </button>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
