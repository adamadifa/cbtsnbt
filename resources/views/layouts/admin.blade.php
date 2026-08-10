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
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Custom Scrollbar for sidebar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        /* Main scrollbar */
        .main-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .main-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .main-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }
        .main-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-[#f4f7fa] text-slate-800 antialiased overflow-hidden" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <div class="h-screen flex overflow-hidden">
        <!-- Sidebar -->
        <aside :class="{ 'translate-x-0': mobileSidebarOpen, '-translate-x-full': !mobileSidebarOpen, 'md:translate-x-0': true, 'md:w-64': sidebarOpen, 'md:w-20': !sidebarOpen }"
               class="fixed inset-y-0 left-0 z-50 flex flex-col bg-[#153c96] text-white transition-all duration-300 transform md:relative md:translate-x-0 select-none shrink-0">
            
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-white/10 shrink-0 relative z-10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#153c96] font-bold text-lg shadow-sm">
                        A
                    </div>
                    @php $siteTitle = \App\Models\Setting::getValue('site_title', 'CBT SNBT'); @endphp
                    <span class="text-base font-bold text-white tracking-tight" x-show="sidebarOpen" x-transition>
                        {{ explode(' ', $siteTitle)[0] }} <span class="text-blue-100">{{ explode(' ', $siteTitle)[1] ?? '' }}</span>
                    </span>
                </a>
                
                <!-- Toggle Button (desktop) -->
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex items-center justify-center w-8 h-8 rounded-lg hover:bg-white/10 text-blue-100 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                </button>
                
                <!-- Close button (mobile) -->
                <button @click="mobileSidebarOpen = false" class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg hover:bg-white/10 text-blue-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Menu Navigation -->
            <div class="flex-1 overflow-y-auto sidebar-scrollbar px-4 py-6 space-y-7 relative z-10">
                {{-- User Info Section --}}
                <div class="px-3 pb-6 border-b border-white/10 flex items-center gap-3" :class="{ 'justify-center': !sidebarOpen }">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ffffff&color=153c96&bold=true&size=80" 
                         alt="User Avatar" 
                         class="w-10 h-10 rounded-xl object-cover shadow-sm shrink-0 border border-white/20">
                    <div x-show="sidebarOpen" x-transition class="min-w-0">
                        <h4 class="text-sm font-bold text-white truncate leading-snug">{{ auth()->user()->name }}</h4>
                        <span class="text-[10px] font-semibold text-blue-200 capitalize">
                            {{ str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'Admin') }}
                        </span>
                    </div>
                </div>

                <!-- MAIN MENU -->
                <div class="space-y-2">
                    <div class="text-[10px] font-semibold text-white/55 uppercase tracking-widest px-3" x-show="sidebarOpen">Menu Utama</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Dashboard</span>
                        </a>

                        @role('admin|super_admin')
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A9.642 9.642 0 0018 19.5M21.75 19.5v.75m-6.75-2.25a3.375 3.375 0 000-6.75M12 18.75a7.185 7.185 0 01-3.623-.972m6.377.972a7.185 7.185 0 003.623-.972M3 18.75V19.5M12 18.75c0-1.353-.277-2.64-.775-3.812M12 18.75a9.642 9.642 0 01-3 1.2M3 19.5h18M3 19.5v-.75a8.217 8.217 0 012.986-6.37M3 19.5a9.63 9.63 0 013-1.2M7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0M9.75 9.75a3 3 0 100-6 3 3 0 000 6z" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Manajemen User</span>
                        </a>

                        <a href="{{ route('admin.subjects.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.subjects.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Materi Uji</span>
                        </a>

                        <a href="{{ route('admin.campus-prodis.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.campus-prodis.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.685 0-5.3.233-7.875.682V21A2.25 2.25 0 006.375 23.25h11.25A2.25 2.25 0 0019.5 21z" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Kampus & Prodi</span>
                        </a>
                        @endrole
                    </div>
                </div>

                <!-- BANK & UJIAN -->
                <div class="space-y-2">
                    <div class="text-[10px] font-semibold text-white/55 uppercase tracking-widest px-3" x-show="sidebarOpen">Bank & Ujian</div>
                    <div class="space-y-1">
                        @role('admin|super_admin|guru')
                        <a href="{{ route('admin.questions.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.questions.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Bank Soal</span>
                        </a>
                        @endrole

                        <a href="{{ route('admin.exam-packages.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.exam-packages.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Paket Tryout</span>
                        </a>

                        <a href="{{ route('admin.exam-sessions.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ (request()->routeIs('admin.exam-sessions.*') && !request('status')) ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Sesi Ujian</span>
                        </a>
                    </div>
                </div>

                <!-- LAPORAN & SISTEM -->
                <div class="space-y-2">
                    <div class="text-[10px] font-semibold text-white/55 uppercase tracking-widest px-3" x-show="sidebarOpen">Laporan & Sistem</div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.settings.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869L9.59 3.94z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-show="sidebarOpen" class="transition-opacity">Pengaturan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer / Logout -->
            <div class="p-4 border-t border-white/10 shrink-0 relative z-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-200 hover:bg-white/10 font-semibold text-sm transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span x-show="sidebarOpen">Log Out</span>
                    </button>
                </form>
            </div>

            <!-- Wave Ornament at Sidebar Bottom -->
            <div class="absolute bottom-0 left-0 w-full overflow-hidden pointer-events-none select-none z-0">
                <svg viewBox="0 0 120 28" class="w-full h-20 -mb-1 text-white/5 fill-current" preserveAspectRatio="none">
                    <path d="M0 15 c 30 0, 30 -10, 60 -10 c 30 0, 30 10, 60 10 l 0 15 l -120 0 Z" />
                    <path d="M0 10 c 30 0, 30 15, 60 15 c 30 0, 30 -15, 60 -15 l 0 25 l -120 0 Z" class="text-white/10" />
                </svg>
            </div>
        </aside>

        <!-- Backdrop (mobile) -->
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden" x-cloak></div>

        <!-- Page Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-6 z-30 shrink-0">
                <!-- Left: Title & Toggle Button -->
                <div class="flex items-center gap-4">
                    <button @click="mobileSidebarOpen = true" class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg hover:bg-slate-50 text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
                        <p class="text-[10px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                            Sistem Online
                        </p>
                    </div>
                </div>

                <!-- Right: Quick actions & Profile -->
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-50 text-slate-500 hover:text-slate-800 transition-colors relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- Divider -->
                    <div class="w-px h-8 bg-slate-100"></div>

                    <!-- User Profile Dropdown -->
                    <div class="flex items-center gap-3 pl-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&bold=true" alt="Avatar" class="w-9 h-9 rounded-xl object-cover ring-2 ring-slate-100">
                        <div class="hidden lg:block text-left">
                            <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] font-semibold text-[#153c96] capitalize mt-0.5">{{ Auth::user()->roles->first()->name ?? 'Administrator' }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Slot (独立滚动区域) -->
            <main class="flex-1 overflow-y-auto main-scrollbar p-6 md:p-8">
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
