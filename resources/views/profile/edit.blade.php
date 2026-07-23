@php
    $layout = Auth::user()->hasAnyRole(['admin', 'super_admin']) ? 'layouts.admin' : 'layouts.student';
@endphp

@extends($layout)

@section('page_title', 'Pengaturan Profil')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <!-- Header -->
    <div class="mb-10">
        <h3 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Akun</h3>
        <p class="text-sm text-slate-500 font-medium italic">Kelola data personal dan keamanan akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Navigation (Optional for Desktop) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" class="w-12 h-12 rounded-2xl shadow-inner">
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                <nav class="space-y-1">
                    <a href="#profile-info" class="flex items-center gap-3 px-4 py-3 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-sm transition-all group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Profil
                    </a>
                    <a href="#password-update" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-xl font-bold text-sm transition-all group">
                        <svg class="w-5 h-5 group-hover:text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Keamanan
                    </a>
                    <a href="#danger-zone" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-50 rounded-xl font-bold text-sm transition-all group">
                        <svg class="w-5 h-5 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Akun
                    </a>
                </nav>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Profile Info -->
            <div id="profile-info" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Informasi Umum</h4>
                </div>
                <div class="p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update -->
            <div id="password-update" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Pembaruan Keamanan</h4>
                </div>
                <div class="p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Danger Zone -->
            <div id="danger-zone" class="bg-white rounded-3xl border border-red-50 shadow-sm overflow-hidden scroll-mt-24">
                <div class="p-6 border-b border-red-50 bg-red-50/30">
                    <h4 class="text-sm font-black text-red-400 uppercase tracking-widest">Zona Berbahaya</h4>
                </div>
                <div class="p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
