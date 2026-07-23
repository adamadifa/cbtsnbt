@extends('layouts.student')

@section('page_title', 'Hasil Ujian')
@section('page_subtitle', 'Analisis performa kamu pada sesi ini.')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-12">
    {{-- Header Card: Main Score --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden relative group">
        <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
            <svg class="w-64 h-64 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        
        <div class="p-8 md:p-12 flex flex-col md:flex-row items-center gap-12 relative z-10">
            {{-- Radial Progress --}}
            <div class="relative flex items-center justify-center shrink-0">
                <svg class="w-48 h-48 transform -rotate-90">
                    <circle cx="96" cy="96" r="88" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-50"/>
                    <circle cx="96" cy="96" r="88" stroke="currentColor" stroke-width="12" fill="transparent" 
                            stroke-dasharray="{{ 2 * pi() * 88 }}" 
                            stroke-dashoffset="{{ (1 - ($stats['accuracy'] / 100)) * (2 * pi() * 88) }}" 
                            class="text-indigo-600 transition-all duration-1000 ease-out" stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-5xl font-black text-slate-800 tracking-tighter leading-none">{{ $stats['accuracy'] }}%</span>
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mt-1">Akurasi</span>
                </div>
            </div>

            {{-- Summary Text --}}
            <div class="flex-1 text-center md:text-left space-y-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase leading-tight">{{ $session->title }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $package->title }}</p>
                </div>
                
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <div class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[11px] font-black text-slate-600 uppercase tracking-widest">{{ $duration }} Menit</span>
                    </div>
                    <div class="px-4 py-2 bg-indigo-50 rounded-xl border border-indigo-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[11px] font-black text-indigo-700 uppercase tracking-widest">{{ $examResult->finished_at->format('d M Y') }}</span>
                    </div>
                </div>

                <p class="text-sm font-medium text-slate-500 leading-relaxed max-w-xl">
                    Kerja bagus! Kamu telah menyelesaikan seluruh subtest tepat waktu. Lihat rincian jawaban kamu di bawah ini untuk membantu proses evaluasi belajar.
                </p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stat_items = [
                ['label' => 'Total Skor', 'value' => $totalScore, 'color' => 'indigo', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ['label' => 'Jawaban Benar', 'value' => $stats['correct'], 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Jawaban Salah', 'value' => $stats['wrong'], 'color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Tidak Dijawab', 'value' => $stats['empty'], 'color' => 'amber', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp

        @foreach($stat_items as $item)
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-3">
                <div class="w-12 h-12 bg-{{ $item['color'] }}-50 text-{{ $item['color'] }}-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">{{ $item['label'] }}</p>
                    <p class="text-2xl font-black text-slate-800 leading-none">{{ $item['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Left: Analysis per Subtest --}}
        <div class="md:col-span-2 space-y-6">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-3 ml-4">
                <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                Analisis Per Subtest
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($breakdown as $sub)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-tight truncate">{{ $sub['title'] }}</h4>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $sub['correct'] }}/{{ $sub['total'] }} Benar</p>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-slate-800 leading-none">{{ $sub['score'] }}%</span>
                        </div>
                    </div>
                    <div class="w-full h-1.5 bg-slate-50 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full transition-all duration-700" style="width: {{ $sub['score'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Strength & Weakness Visualization --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm mt-8">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-8 flex items-center gap-3">
                    <div class="w-1.5 h-4 bg-indigo-600 rounded-full"></div>
                    Radar Performa (Kekuatan & Kelemahan)
                </h3>
                <div class="h-[350px] relative">
                    <canvas id="performanceRadar"></canvas>
                </div>
            </div>
        </div>

        {{-- Right: What's Next? --}}
        <div class="space-y-6">
             <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-3 ml-4">
                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                Langkah Selanjutnya
            </h3>

            <div class="bg-slate-900 rounded-[2rem] p-6 text-white space-y-6 shadow-xl shadow-slate-200">
                <div class="space-y-2">
                    <p class="text-indigo-400 text-[10px] font-black uppercase tracking-widest">Saran Belajar</p>
                    <p class="text-sm font-medium leading-relaxed">
                        Berdasarkan hasil kamu, sepertinya kamu perlu lebih mendalami 
                        <span class="text-indigo-300 font-bold">"{{ $breakdown->sortBy('score')->first()['title'] }}"</span>. Jangan berkecil hati dan teruslah berlatih!
                    </p>
                </div>

                <div class="space-y-3 pt-4">
                    <a href="{{ route('dashboard') }}" class="block w-full py-4 bg-indigo-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.1em] text-center shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 transition-all">
                        Kembali ke Dashboard
                    </a>
                    @if($package->show_explanation)
                    <a href="{{ route('student.exam.explanation', $examResult) }}" class="block w-full py-4 bg-white/10 text-white border border-white/10 rounded-2xl text-[11px] font-black uppercase tracking-[0.1em] text-center hover:bg-white/20 transition-all">
                        Lihat Pembahasan
                    </a>
                    @endif
                    <button onclick="window.print()" class="block w-full py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.1em] text-center hover:text-white transition-colors">
                        Simpan Sebagai PDF (Halaman Ini)
                    </button>
                    <a href="{{ route('student.exam.certificate', $examResult) }}" class="block w-full py-4 bg-emerald-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.1em] text-center shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 transition-all">
                        Unduh Sertifikat Resmi
                    </a>
                </div>
            </div>

            {{-- Support Card --}}
            <div class="p-6 bg-amber-50 border border-amber-100 rounded-3xl">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-amber-900 uppercase tracking-tight mb-1">Ada Kendala?</p>
                        <p class="text-[10px] font-bold text-amber-700 leading-normal uppercase">
                            Jika skor tidak sesuai atau ada soal yang error, silakan hubungi pengawas atau admin cabang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceRadar');
        if (ctx) {
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: {!! json_encode($breakdown->pluck('title')) !!},
                    datasets: [{
                        label: 'Skor Kamu',
                        data: {!! json_encode($breakdown->pluck('score')) !!},
                        fill: true,
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        borderColor: '#6366f1',
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#6366f1',
                        borderWidth: 3,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: { borderWidth: 3 }
                    },
                    scales: {
                        r: {
                            angleLines: { display: true, color: 'rgba(226, 232, 240, 0.5)' },
                            suggestedMin: 0,
                            suggestedMax: 100,
                            ticks: { display: false, stepSize: 20 },
                            pointLabels: {
                                font: { size: 10, weight: '800', family: 'Plus Jakarta Sans' },
                                color: '#64748b'
                            },
                            grid: { color: 'rgba(226, 232, 240, 0.8)' }
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

<style>
    @media print {
        header, .action-buttons, .no-print { display: none !important; }
        .print-only { display: block !important; }
        body { background: white; }
        .max-w-5xl { max-width: 100%; margin: 0; padding: 0; }
        .bg-slate-900 { background: #f8fafc !important; color: #1e293b !important; }
        .text-indigo-400, .text-indigo-300 { color: #4f46e5 !important; }
    }
</style>
@endsection
