@props(['wide' => false])
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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased selection:bg-blue-100 selection:text-blue-700 bg-slate-50/50">
        @if($wide)
            <div class="min-h-screen flex items-center justify-center p-4 md:p-8 bg-[#e8f1fc] relative overflow-hidden">
                <!-- Large soft decorative background elements -->
                <div class="absolute -bottom-48 -right-48 w-[600px] h-[600px] bg-blue-200/40 rounded-full blur-3xl"></div>
                <div class="absolute -top-48 -left-48 w-[600px] h-[600px] bg-indigo-100/40 rounded-full blur-3xl"></div>
                
                <div class="w-full max-w-5xl z-10 bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(148,163,184,0.15)] overflow-hidden border border-slate-100">
                    {{ $slot }}
                </div>
            </div>
        @else
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 relative overflow-hidden">
                <!-- Decorative Background Elements -->
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-indigo-500/10 to-transparent"></div>
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>

                <div class="z-10 mb-8 transform transition-transform hover:scale-105 duration-300">
                    <a href="/" class="flex flex-col items-center gap-2">
                        <div class="p-4 bg-white rounded-3xl shadow-xl shadow-indigo-100/50 border border-slate-100">
                            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-2xl font-black text-slate-800 tracking-tight">CBT <span class="text-indigo-600">SNBT</span></span>
                    </a>
                </div>

                <div class="w-full sm:max-w-md z-10 px-8 py-10 bg-white shadow-2xl shadow-slate-200/50 sm:rounded-3xl border border-slate-100/50">
                    {{ $slot }}
                </div>
                
                <div class="mt-8 text-slate-400 text-sm font-medium">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </div>
        @endif
    </body>
</html>
