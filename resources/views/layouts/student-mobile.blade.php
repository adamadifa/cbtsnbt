<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Siswa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AlpineJS for interactive dropdowns/toggles -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            -webkit-tap-highlight-color: transparent;
        }
        /* Hide scrollbars but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-indigo-100 selection:text-indigo-800">
    <div class="min-h-screen flex flex-col pb-20">
        <!-- Top Compact Header -->
        <header class="sticky top-0 z-40 bg-white/85 backdrop-blur-md border-b border-slate-100 h-14 flex items-center px-4 justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                @php $siteTitle = \App\Models\Setting::getValue('site_title', 'LULUS SNBT'); @endphp
                <span class="font-bold text-sm text-slate-800 tracking-tight uppercase">
                    {{ explode(' ', $siteTitle)[0] }} <span class="text-indigo-600">{{ explode(' ', $siteTitle)[1] ?? '' }}</span>
                </span>
            </div>

            <!-- Profile and Logout Options -->
            <div class="flex items-center gap-3" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center focus:outline-none transition-transform active:scale-95">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&bold=true" alt="Avatar" class="w-7 h-7 rounded-lg border border-slate-200 shadow-sm">
                </button>
                
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="absolute right-4 top-12 w-48 bg-white rounded-xl shadow-lg border border-slate-200/80 py-1.5 z-50">
                    <div class="px-3.5 py-2 border-b border-slate-100 mb-1">
                        <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider mb-0.5">Siswa</p>
                        <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-650 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Edit Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-rose-500 hover:bg-rose-50 transition-colors text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 px-4 py-5 max-w-md mx-auto w-full">
            @yield('content')
        </main>

        <!-- Bottom Navigation Bar (App-like experience) -->
        <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/60 pb-safe pb-4 pt-2.5 flex items-center justify-around px-6 shadow-lg">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('dashboard') && !str_contains(request()->fullUrl(), '#riwayat-ujian') ? 'text-indigo-600 font-semibold' : 'text-slate-400 hover:text-slate-650' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px]">Dashboard</span>
            </a>

            <a href="#riwayat-ujian" class="flex flex-col items-center gap-1 transition-colors text-slate-400 hover:text-slate-650">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-[10px]">Riwayat</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('profile.edit') ? 'text-indigo-600 font-semibold' : 'text-slate-400 hover:text-slate-650' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px]">Profil</span>
            </a>
        </nav>
    </div>

    @stack('scripts')
</body>
</html>
