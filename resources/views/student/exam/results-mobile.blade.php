@extends('layouts.student-mobile')

@section('content')
<div class="space-y-6">
    {{-- Main Score & Performance Header --}}
    <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 rounded-2xl p-6 text-white shadow-md text-center relative overflow-hidden space-y-6">
        <div class="absolute right-0 top-0 opacity-5 translate-y-3 translate-x-3 pointer-events-none">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <div class="relative z-10 space-y-1.5 w-full">
            <span class="px-2.5 py-0.5 bg-white/10 border border-white/10 rounded text-[9px] font-bold text-indigo-200 uppercase tracking-widest">
                {{ str_replace('_', ' ', $package->type) }}
            </span>
            <h2 class="text-sm font-extrabold text-white mt-1">{{ $session->title }}</h2>
            <p class="text-[11px] text-indigo-200">{{ $package->title }}</p>
        </div>

        {{-- Centered Larger Ring --}}
        <div class="flex flex-col items-center justify-center relative z-10">
            <div class="relative flex items-center justify-center shrink-0">
                <svg class="w-36 h-36 transform -rotate-90">
                    <circle cx="72" cy="72" r="62" stroke="currentColor" stroke-width="8" fill="transparent" class="text-white/10"/>
                    <circle cx="72" cy="72" r="62" stroke="currentColor" stroke-width="8" fill="transparent" 
                            stroke-dasharray="{{ 2 * pi() * 62 }}" 
                            stroke-dashoffset="{{ (1 - ($stats['accuracy'] / 100)) * (2 * pi() * 62) }}" 
                            class="text-white transition-all duration-1000 ease-out" stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-black text-white leading-none">{{ $stats['accuracy'] }}%</span>
                    <span class="text-[9px] font-bold text-indigo-200 uppercase mt-1">Akurasi</span>
                </div>
            </div>
        </div>

        {{-- Details Grid --}}
        <div class="grid grid-cols-2 gap-4 border-t border-white/10 pt-5 relative z-10">
            <div class="text-center space-y-0.5 border-r border-white/10">
                <span class="text-xl font-black text-white leading-none block">{{ $totalScore }}</span>
                <span class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider block mt-1">Total Skor</span>
            </div>
            <div class="text-center space-y-0.5 flex flex-col items-center justify-center">
                <span class="text-sm font-extrabold text-white leading-none block">{{ $duration }}m</span>
                <span class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider block mt-1">Durasi</span>
            </div>
        </div>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-3 gap-2.5">
        <div class="bg-white border border-slate-200 rounded-xl p-3 text-center shadow-xs">
            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Benar</span>
            <span class="text-sm font-black text-emerald-600 mt-1 block">{{ $stats['correct'] }}</span>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-3 text-center shadow-xs">
            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Salah</span>
            <span class="text-sm font-black text-rose-600 mt-1 block">{{ $stats['wrong'] }}</span>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-3 text-center shadow-xs">
            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Kosong</span>
            <span class="text-sm font-black text-amber-650 mt-1 block">{{ $stats['empty'] }}</span>
        </div>
    </div>

    {{-- Subtest breakdown --}}
    <div class="space-y-3">
        <div class="px-1">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Performa Per Subtest</h3>
        </div>

        <div class="space-y-2.5">
            @foreach($breakdown as $sub)
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs space-y-2">
                <div class="flex justify-between items-center gap-4">
                    <h4 class="text-xs font-bold text-slate-700 truncate leading-snug">{{ $sub['title'] }}</h4>
                    <span class="text-xs font-black text-indigo-650 shrink-0">{{ $sub['score'] }}%</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $sub['score'] }}%"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold text-slate-400 uppercase tracking-wider pt-0.5">
                    <span>Benar: {{ $sub['correct'] }} / {{ $sub['total'] }} Soal</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Radar Distribution Chart --}}
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Radar Distribusi Nilai</h3>
        <div class="h-[200px] relative">
            <canvas id="performanceRadar"></canvas>
        </div>
    </div>

    {{-- Recommendations & Actions --}}
    <div class="bg-slate-900 rounded-2xl p-5 text-white space-y-4 border border-slate-800 shadow-md">
        <div class="space-y-1">
            <span class="text-indigo-400 text-[9px] font-bold uppercase tracking-wider">Saran Pemantapan</span>
            <p class="text-xs font-medium leading-relaxed text-slate-350">
                Berdasarkan evaluasi, review kembali materi di subtest:
                <span class="text-indigo-200 font-bold block mt-0.5 text-xs">"{{ $breakdown->sortBy('score')->first()['title'] ?? '-' }}"</span>
            </p>
        </div>

        <div class="space-y-2 pt-2 border-t border-slate-800 text-center">
            <a href="{{ route('dashboard') }}" class="block w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
                Dashboard
            </a>
            @if($package->show_explanation)
            <a href="{{ route('student.exam.explanation', $examResult) }}" class="block w-full py-2.5 bg-slate-800 hover:bg-slate-750 border border-slate-750 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all">
                Pelajari Pembahasan
            </a>
            @endif
            <a href="{{ route('student.exam.certificate', $examResult) }}" class="block w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
                Unduh Sertifikat
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceRadar');
        if (ctx) {
            const labels = {!! json_encode($breakdown->pluck('title')) !!};
            const scores = {!! json_encode($breakdown->pluck('score')) !!};
            const isRadar = labels.length >= 3;

            new Chart(ctx, {
                type: isRadar ? 'radar' : 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Skor Anda',
                        data: scores,
                        fill: true,
                        backgroundColor: isRadar ? 'rgba(99, 102, 241, 0.08)' : 'rgba(99, 102, 241, 0.8)',
                        borderColor: '#6366f1',
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#6366f1',
                        borderWidth: isRadar ? 2 : 1,
                        borderRadius: isRadar ? 0 : 6,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: isRadar ? {
                        r: {
                            angleLines: { display: true, color: 'rgba(226, 232, 240, 0.5)' },
                            suggestedMin: 0,
                            suggestedMax: 100,
                            ticks: { display: false, stepSize: 20 },
                            pointLabels: {
                                font: { size: 8, weight: '700', family: 'system-ui' },
                                color: '#64748b'
                            },
                            grid: { color: 'rgba(226, 232, 240, 0.8)' }
                        }
                    } : {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                font: { size: 8, weight: '600' },
                                color: '#64748b'
                            },
                            grid: { color: 'rgba(226, 232, 240, 0.5)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 8, weight: '700' },
                                color: '#64748b'
                            },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
