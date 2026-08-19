@extends('layouts.admin')

@section('page_title', 'Pemantauan Sesi Ujian')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exam-sessions.index') }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l14 0"></path>
                    <path d="M5 12l6 6"></path>
                    <path d="M5 12l6 -6"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">{{ $examSession->title }}</h1>
        </div>
        <div class="flex items-center gap-3.5 mt-1.5 ml-11 text-xs font-semibold text-slate-500">
            <div class="flex items-center gap-1.5">
                <span>ID Sesi:</span>
                <span class="text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg font-bold select-all tracking-wider">{{ $examSession->id }}</span>
            </div>
            <div class="w-1 h-1 rounded-full bg-slate-300"></div>
            <div class="flex items-center gap-1.5">
                <span>Token:</span>
                <span class="text-[#153c96] bg-blue-50 px-2 py-0.5 rounded-lg font-bold select-all tracking-wider">{{ $examSession->token }}</span>
            </div>
            <div class="w-1 h-1 rounded-full bg-slate-300"></div>
            <div class="flex items-center gap-1.5">
                <span>Status:</span>
                <span class="text-slate-600 bg-slate-100 px-2 py-0.5 rounded-lg font-bold capitalize">{{ $examSession->status }}</span>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-2 self-start md:self-center">
        <a href="{{ route('admin.exam-sessions.export-pdf', $examSession) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-bold text-xs border border-rose-150 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                <path d="M12 11v6"></path>
                <path d="M9.5 13.5l2.5 2.5l2.5 -2.5"></path>
            </svg>
            Export PDF
        </a>
        <a href="{{ route('admin.exam-sessions.export-excel', $examSession) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs border border-emerald-150 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                <path d="M4 17h16"></path>
            </svg>
            Export Excel
        </a>
        <button onclick="window.location.reload()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-bold text-xs shadow-sm transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5"></path>
                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5"></path>
            </svg>
            Update Data
        </button>
    </div>
</div>

{{-- Tabs --}}
<div x-data="{ 
    activeTab: 'monitor',
    init() {
        this.$watch('activeTab', value => {
            if (value === 'analytics' && typeof initCharts === 'function') {
                this.$nextTick(() => initCharts());
            }
        })
    }
}" class="space-y-6">
    <div class="flex items-center gap-1.5 p-1 bg-white border border-slate-100 rounded-2xl w-fit shadow-xs">
        <button @click="activeTab = 'monitor'" 
                :class="activeTab === 'monitor' ? 'bg-[#153c96] text-white shadow-sm' : 'text-slate-655 hover:bg-slate-50'"
                class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all">
            Live Monitoring
        </button>
        <button @click="activeTab = 'analytics'" 
                :class="activeTab === 'analytics' ? 'bg-[#153c96] text-white shadow-sm' : 'text-slate-655 hover:bg-slate-50'"
                class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all">
            Analisis & Statistik
        </button>
        <button @click="activeTab = 'matrix'" 
                :class="activeTab === 'matrix' ? 'bg-[#153c96] text-white shadow-sm' : 'text-slate-655 hover:bg-slate-50'"
                class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all">
            Matriks Hasil Soal
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M5 12l5 5l10 -10"></path>
            </svg>
            <p class="text-sm font-bold text-emerald-600">{{ session('success') }}</p>
        </div>
    @endif

    <div id="monitor-content">
        @include('admin.exam-sessions.partials.monitor-data')
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fetchTimeout = null;
        const THROTTLE_MS = 10000; // Minimal 10 detik antar request AJAX

        const fetchMonitorData = () => {
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('monitor-content').innerHTML = html;
                // Re-initialize charts after AJAX update if in analytics tab
                if (typeof initCharts === 'function') initCharts();
            })
            .catch(error => console.error('Error fetching monitor data:', error));
        };

        // Throttled version: hanya eksekusi sekali per THROTTLE_MS
        const throttledFetch = () => {
            if (fetchTimeout) return; // Abaikan jika sudah ada antrean
            fetchTimeout = setTimeout(() => {
                fetchMonitorData();
                fetchTimeout = null;
            }, THROTTLE_MS);
        };

        // Listen to Reverb WebSockets
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel('exam-session.{{ $examSession->id }}')
                .listen('MonitorExamUpdated', (e) => {
                    throttledFetch();
                });
        }
    });
</script>
@endpush
@endsection
