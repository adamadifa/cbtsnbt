<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- jQuery (Required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Summernote Lite (Truly Free) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Custom Scrollbar for sidebar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
        /* Main scrollbar */
        .main-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .main-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .main-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 99px;
        }
        .main-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</head>
<body class="bg-[#f8f9fa] text-slate-800 antialiased overflow-hidden font-sans" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <div class="h-screen flex overflow-hidden">
        <!-- Sidebar -->
        <aside :class="{ 'translate-x-0': mobileSidebarOpen, '-translate-x-full': !mobileSidebarOpen, 'md:translate-x-0': true, 'w-64': sidebarOpen, 'md:w-64': sidebarOpen, 'w-[76px]': !sidebarOpen, 'md:w-[76px]': !sidebarOpen }"
               class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-slate-100 text-slate-700 transition-all duration-300 transform md:relative md:translate-x-0 select-none shrink-0">
            
            <!-- Sidebar Header / Brand -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-100/80 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                    @php 
                        $siteTitle = \App\Models\Setting::getValue('site_title', 'CBT SNBT');
                        $siteLogo = \App\Models\Setting::getValue('site_logo');
                    @endphp
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="w-9 h-9 rounded-xl object-contain shrink-0 border border-slate-100 shadow-sm">
                    @else
                        <div class="w-9 h-9 rounded-xl bg-orange-500 flex items-center justify-center text-white font-bold text-base shadow-sm shrink-0 bg-gradient-to-br from-orange-400 to-orange-600">
                            {{ substr($siteTitle, 0, 1) }}
                        </div>
                    @endif
                    <div x-show="sidebarOpen" x-transition class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-bold text-slate-800 tracking-tight truncate">{{ $siteTitle }}</span>
                            <i class="ti ti-selector text-slate-400 text-sm shrink-0"></i>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium truncate">Education & Tryout</p>
                    </div>
                </a>
                
                <!-- Toggle Button (desktop) -->
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex items-center justify-center w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors shrink-0">
                    <i class="ti ti-menu-2 text-base"></i>
                </button>
                
                <!-- Close button (mobile) -->
                <button @click="mobileSidebarOpen = false" class="md:hidden flex items-center justify-center w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                    <i class="ti ti-x text-base"></i>
                </button>
            </div>

            <!-- Sidebar Menu Navigation -->
            <div class="flex-1 overflow-y-auto sidebar-scrollbar px-3 py-4 space-y-6">
                <!-- MAIN MENU -->
                <div class="space-y-1">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 pb-1.5" x-show="sidebarOpen">Main</div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ request()->routeIs('admin.dashboard') ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.dashboard'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-layout-grid text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Overview</span>
                        <i x-show="sidebarOpen && {{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}" class="ti ti-chevron-right text-xs text-slate-400 ml-auto shrink-0"></i>
                    </a>

                    @role('admin|super_admin')
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ request()->routeIs('admin.users.*') ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.users.*'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-users text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Pengguna</span>
                    </a>

                    <a href="{{ route('admin.subjects.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ request()->routeIs('admin.subjects.*') ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.subjects.*'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ request()->routeIs('admin.subjects.*') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-book-2 text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Materi Uji</span>
                    </a>

                    <a href="{{ route('admin.campus-prodis.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ request()->routeIs('admin.campus-prodis.*') ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.campus-prodis.*'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ request()->routeIs('admin.campus-prodis.*') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-building text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Kampus & Prodi</span>
                    </a>
                    @endrole
                </div>

                <!-- CBT / UJIAN -->
                <div class="space-y-1">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 pb-1.5" x-show="sidebarOpen">Ujian & Tryout</div>
                    
                    @role('admin|super_admin|guru')
                    <a href="{{ route('admin.questions.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ request()->routeIs('admin.questions.*') ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.questions.*'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ request()->routeIs('admin.questions.*') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-help-hexagon text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Bank Soal</span>
                    </a>
                    @endrole

                    <a href="{{ route('admin.exam-packages.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ request()->routeIs('admin.exam-packages.*') ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.exam-packages.*'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ request()->routeIs('admin.exam-packages.*') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-file-certificate text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Paket Tryout</span>
                    </a>

                    <a href="{{ route('admin.exam-sessions.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ (request()->routeIs('admin.exam-sessions.*') && !request('status')) ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.exam-sessions.*') && !request('status'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ (request()->routeIs('admin.exam-sessions.*') && !request('status')) ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-clock-play text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Sesi Ujian</span>
                    </a>
                </div>

                <!-- PREFERENCES / SETTINGS -->
                <div class="space-y-1 pt-2">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 pb-1.5" x-show="sidebarOpen">Preferences</div>

                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all relative group {{ request()->routeIs('admin.settings.*') ? 'bg-slate-50 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50/80 hover:text-slate-800' }}">
                        @if(request()->routeIs('admin.settings.*'))
                            <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-orange-500 rounded-r-full"></span>
                        @endif
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i class="ti ti-settings text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Settings</span>
                    </a>

                    <a href="https://wa.me/" target="_blank" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-50/80 hover:text-slate-800 transition-all">
                        <div class="w-5 h-5 flex items-center justify-center shrink-0 text-slate-400">
                            <i class="ti ti-headset text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="flex-1 truncate">Support</span>
                    </a>
                </div>
            </div>

            <!-- Sidebar Bottom User Profile Card -->
            <div class="p-3 border-t border-slate-100 shrink-0">
                <div class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition-colors group">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=fdba74&color=7c2d12&bold=true&size=80" 
                         alt="Avatar" 
                         class="w-9 h-9 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                    <div x-show="sidebarOpen" x-transition class="min-w-0 flex-1">
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-bold text-slate-800 truncate leading-tight">{{ auth()->user()->name }}</span>
                            <span class="w-3.5 h-3.5 bg-blue-500 text-white rounded-full flex items-center justify-center shrink-0 text-[9px]">
                                <i class="ti ti-check text-[10px]"></i>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 truncate block">{{ auth()->user()->email }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" x-show="sidebarOpen" class="shrink-0">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="ti ti-logout text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Backdrop (mobile) -->
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden" x-cloak></div>

        <!-- Page Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0 bg-[#f8f9fa]">
            <!-- Top Navbar (Clean, Functional, Modern UI matching enterprise SaaS) -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200/60 flex items-center justify-between px-6 z-30 shrink-0 sticky top-0"
                    x-data="{ userMenuOpen: false, searchModalOpen: false, notifOpen: false }">
                
                <!-- Left: Mobile Toggle & Page Context / Greeting -->
                <div class="flex items-center gap-3.5">
                    <button @click="mobileSidebarOpen = true" class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                        <i class="ti ti-menu-2 text-lg"></i>
                    </button>

                    <div class="hidden sm:block">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-slate-800">CBT SNBT</span>
                            <span class="text-slate-300">/</span>
                            <span class="text-slate-500 font-medium">@yield('page_title', 'Dashboard')</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Functional Controls & User Profile Dropdown -->
                <div class="flex items-center gap-2.5">
                    <!-- Global Search Button / Trigger -->
                    <div class="relative hidden sm:block">
                        <form action="{{ route('admin.users.index') }}" method="GET" class="relative">
                            <i class="ti ti-search text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
                            <input type="text" name="search" placeholder="Cari siswa, ujian... (Ctrl+K)" 
                                   class="w-48 lg:w-64 pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200/70 rounded-xl text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100/50 transition-all">
                        </form>
                    </div>

                    <!-- Date Badge (Real Today) -->
                    <div class="hidden md:flex items-center gap-1.5 bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-600 select-none">
                        <i class="ti ti-calendar text-sm text-orange-500"></i>
                        <span>{{ now()->translatedFormat('d M Y') }}</span>
                    </div>

                    <!-- Notification Popover -->
                    <div class="relative">
                        <button @click="notifOpen = !notifOpen; userMenuOpen = false" 
                                class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors relative">
                            <i class="ti ti-bell text-base"></i>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-500 rounded-full ring-2 ring-white"></span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="notifOpen" 
                             @click.outside="notifOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 p-3 z-50" 
                             x-cloak>
                            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
                                <span class="text-xs font-bold text-slate-800">Notifikasi</span>
                                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-md">Realtime</span>
                            </div>
                            <div class="space-y-2">
                                <div class="p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/70 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0">
                                            <i class="ti ti-check"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-slate-800 truncate">Sistem Berjalan Normal</p>
                                            <p class="text-[10px] text-slate-400">Database & CBT Engine aktif</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="h-5 w-px bg-slate-200 mx-0.5"></div>

                    <!-- User Profile Dropdown Menu -->
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen; notifOpen = false" 
                                class="flex items-center gap-2 p-1 pl-1.5 pr-2.5 rounded-xl hover:bg-slate-100 transition-colors text-left group">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fdba74&color=7c2d12&bold=true&size=80" 
                                 alt="Avatar" 
                                 class="w-7 h-7 rounded-lg object-cover ring-1 ring-slate-200">
                            <div class="hidden sm:block text-left">
                                <span class="text-xs font-bold text-slate-800 block leading-tight group-hover:text-orange-600 transition-colors">{{ Str::limit(Auth::user()->name, 14) }}</span>
                                <span class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->roles->first()->name ?? 'Administrator' }}</span>
                            </div>
                            <i class="ti ti-chevron-down text-xs text-slate-400 ml-0.5 group-hover:text-slate-600 transition-colors"></i>
                        </button>

                        <!-- User Dropdown Popover -->
                        <div x-show="userMenuOpen" 
                             @click.outside="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 p-1.5 z-50" 
                             x-cloak>
                            <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('admin.settings.index') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                <i class="ti ti-settings text-sm"></i>
                                <span>Pengaturan Sistem</span>
                            </a>

                            <div class="border-t border-slate-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                    <i class="ti ti-logout text-sm"></i>
                                    <span>Keluar Aplikasi</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Slot (Clean, compact scroll area) -->
            <main class="flex-1 overflow-y-auto main-scrollbar px-6 pb-6 pt-2">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @stack('scripts')
</body>
</html>
