<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
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
    <body class="font-jakarta antialiased bg-[#f4f7fa] text-slate-800 overflow-hidden" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar -->
            <aside :class="{ 'translate-x-0': mobileSidebarOpen, '-translate-x-full': !mobileSidebarOpen, 'md:translate-x-0': true, 'md:w-64': sidebarOpen, 'md:w-20': !sidebarOpen }"
                   class="fixed inset-y-0 left-0 z-50 flex flex-col bg-[#153c96] text-white transition-all duration-300 transform md:relative md:translate-x-0 select-none shrink-0">
                
                <!-- Sidebar Header -->
                <div class="h-16 flex items-center justify-between px-5 border-b border-white/10 shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#153c96] font-bold text-lg shadow-sm">
                            S
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight" x-show="sidebarOpen" x-transition>SmartHR</span>
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
                <div class="flex-1 overflow-y-auto sidebar-scrollbar px-4 py-6 space-y-7">
                    <!-- MAIN MENU -->
                    <div class="space-y-2">
                        <div class="text-[10px] font-semibold text-white/55 uppercase tracking-widest px-3" x-show="sidebarOpen">Main Menu</div>
                        <div class="space-y-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/20 text-white font-semibold text-sm transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                                <span x-show="sidebarOpen" class="transition-opacity">Dashboard</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-blue-100 hover:text-white font-medium text-sm transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A9.642 9.642 0 0018 19.5M21.75 19.5v.75m-6.75-2.25a3.375 3.375 0 000-6.75M12 18.75a7.185 7.185 0 01-3.623-.972m6.377.972a7.185 7.185 0 003.623-.972M3 18.75V19.5M12 18.75c0-1.353-.277-2.64-.775-3.812M12 18.75a9.642 9.642 0 01-3 1.2M3 19.5h18M3 19.5v-.75a8.217 8.217 0 012.986-6.37M3 19.5a9.63 9.63 0 013-1.2M7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0M9.75 9.75a3 3 0 100-6 3 3 0 000 6z" />
                                </svg>
                                <span x-show="sidebarOpen" class="transition-opacity">Employees</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-blue-100 hover:text-white font-medium text-sm transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span x-show="sidebarOpen" class="transition-opacity">Leave & Attendance</span>
                            </a>
                        </div>
                    </div>

                    <!-- APPLICATIONS -->
                    <div class="space-y-2">
                        <div class="text-[10px] font-semibold text-white/55 uppercase tracking-widest px-3" x-show="sidebarOpen">Applications</div>
                        <div class="space-y-1">
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-blue-100 hover:text-white font-medium text-sm transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v12a2.25 2.25 0 01-2.25 2.25H5.25" />
                                </svg>
                                <span x-show="sidebarOpen" class="transition-opacity">Projects</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-blue-100 hover:text-white font-medium text-sm transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span x-show="sidebarOpen" class="transition-opacity">Tasks</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Footer / Logout -->
                <div class="p-4 border-t border-white/10 shrink-0">
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
            </aside>

            <!-- Backdrop (mobile) -->
            <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden" x-cloak></div>

            <!-- Page Content Wrapper -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
                <!-- Top Navbar (Fixed height, absolute position/fixed look) -->
                <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-6 z-30 shrink-0">
                    <!-- Left: Search Bar & Toggle Button -->
                    <div class="flex items-center gap-4 flex-1 max-w-lg">
                        <button @click="mobileSidebarOpen = true" class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg hover:bg-slate-50 text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                            </svg>
                        </button>
                        
                        <!-- Search Box -->
                        <div class="relative w-full hidden sm:block">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" placeholder="Search in HRMS" class="w-full pl-10 pr-20 py-2 bg-slate-50 border-none rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-100 text-sm transition-all focus:outline-none">
                            <span class="absolute inset-y-0 right-3 flex items-center text-[10px] font-semibold text-slate-400 uppercase bg-white border border-slate-100 px-1.5 py-0.5 rounded-lg my-1.5 shadow-sm">CTRL + /</span>
                        </div>
                    </div>

                    <!-- Right: Quick actions & Profile -->
                    <div class="flex items-center gap-4">
                        <!-- Lang Toggle -->
                        <button class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-50 text-slate-600 transition-colors">
                            <span class="text-sm font-bold">EN</span>
                        </button>

                        <!-- Notification -->
                        <button class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-50 text-slate-500 hover:text-slate-800 transition-colors relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                        </button>

                        <!-- User Profile Dropdown -->
                        <div class="flex items-center gap-3 pl-3 border-l border-slate-100">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=100" alt="Avatar" class="w-9 h-9 rounded-xl object-cover ring-2 ring-slate-100">
                            <div class="hidden lg:block text-left">
                                <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-medium text-slate-400">Employee</div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Header (Optional template header slot) -->
                @isset($header)
                    <div class="bg-white border-b border-slate-100 px-8 py-5 shrink-0">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Main Content Slot (独立滚动区域) -->
                <main class="flex-1 overflow-y-auto main-scrollbar p-6 md:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
