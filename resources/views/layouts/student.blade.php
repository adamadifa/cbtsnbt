<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Siswa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
    <!-- SortableJS for Matching -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Header -->
        <header class="h-[72px] bg-white border-b border-slate-100 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    @php $siteTitle = \App\Models\Setting::getValue('site_title', 'LULUS SNBT'); @endphp
                    <span class="font-extrabold text-lg text-slate-800 tracking-tight block uppercase">{{ explode(' ', $siteTitle)[0] }} <span class="text-indigo-600">{{ explode(' ', $siteTitle)[1] ?? '' }}</span></span>
                </div>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden md:flex items-center gap-1 mx-8 flex-1">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">Dashboard</a>
                    <a href="{{ route('dashboard') }}#riwayat-ujian" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all">Riwayat Ujian</a>
                </nav>

                <!-- User Profile -->
                <div class="flex items-center gap-4">
                    <div class="w-px h-8 bg-slate-100 hidden sm:block"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-black text-slate-800 uppercase tracking-wider">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest">Siswa Pro</p>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center focus:outline-none transition-transform active:scale-95">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&bold=true" alt="Avatar" class="w-9 h-9 rounded-xl ring-2 ring-slate-50 shadow-sm">
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                                <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Akun Siswa</p>
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Edit Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar Sistem
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-8">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">@yield('page_title', 'Dashboard Siswa')</h1>
                <p class="text-sm text-slate-400 font-bold mt-1">@yield('page_subtitle', 'Selamat datang di panel pengerjaan tryout.')</p>
            </div>

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 py-8">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">© 2024 Lulus SNBT. Platform Persiapan Ujian Terbaik.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">Panduan</a>
                    <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">Bantuan</a>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
