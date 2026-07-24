@extends('layouts.student')

@section('page_title', 'Hasil Evaluasi')
@section('page_subtitle', 'Analisis komprehensif capaian dan performa pengerjaan tryout Anda.')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-16">
    {{-- Main Performance Dashboard Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
            {{-- Left: Big Score & Accuracy Visualizer (5 Cols) --}}
            <div class="lg:col-span-5 p-8 flex flex-col items-center justify-center text-center bg-gradient-to-br from-blue-700 to-indigo-900 text-white">
                <div class="relative flex items-center justify-center shrink-0 mb-6">
                    {{-- Circular Ring Progress --}}
                    <svg class="w-44 h-44 transform -rotate-90">
                        <circle cx="88" cy="88" r="76" stroke="currentColor" stroke-width="8" fill="transparent" class="text-white/10"/>
                        <circle cx="88" cy="88" r="76" stroke="currentColor" stroke-width="8" fill="transparent" 
                                stroke-dasharray="{{ 2 * pi() * 76 }}" 
                                stroke-dashoffset="{{ (1 - ($stats['accuracy'] / 100)) * (2 * pi() * 76) }}" 
                                class="text-white transition-all duration-1000 ease-out" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-extrabold text-white tracking-tight leading-none">{{ $stats['accuracy'] }}%</span>
                        <span class="text-[9px] font-bold text-blue-200 uppercase tracking-widest mt-1.5">Rasio Akurasi</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <h3 class="text-3xl font-extrabold text-white tracking-tight leading-none">{{ $totalScore }}</h3>
                    <p class="text-[10px] font-bold text-blue-200 uppercase tracking-wider">Total Skor Diperoleh</p>
                </div>
            </div>

            {{-- Right: Exam Details & Summary (7 Cols) --}}
            <div class="lg:col-span-7 p-8 flex flex-col justify-between gap-6 bg-white">
                <div class="space-y-4">
                    <div class="space-y-1">
                        <span class="px-2.5 py-0.5 bg-blue-50 border border-blue-100 rounded text-[9px] font-bold text-blue-700 uppercase tracking-wider">
                            {{ str_replace('_', ' ', $package->type) }}
                        </span>
                        <h2 class="text-xl font-bold text-slate-800 leading-snug pt-1">{{ $session->title }}</h2>
                        <p class="text-xs text-slate-500">{{ $package->title }}</p>
                    </div>

                    <div class="flex flex-wrap gap-2.5 pt-1">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $duration }} Menit Durasi
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Selesai: {{ $examResult->finished_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-xl">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Evaluasi ini membandingkan rasio jawaban benar, salah, serta ketepatan pengerjaan di seluruh subtest. Gunakan data analisis di bawah untuk mengidentifikasi topik materi yang memerlukan perhatian khusus.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-5 border border-slate-200 rounded-xl flex items-center justify-between shadow-xs">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jawaban Benar</span>
                <span class="text-2xl font-bold text-slate-800 block">{{ $stats['correct'] }}</span>
            </div>
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 border border-slate-200 rounded-xl flex items-center justify-between shadow-xs">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jawaban Salah</span>
                <span class="text-2xl font-bold text-slate-800 block">{{ $stats['wrong'] }}</span>
            </div>
            <div class="w-10 h-10 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 border border-slate-200 rounded-xl flex items-center justify-between shadow-xs">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tidak Dijawab</span>
                <span class="text-2xl font-bold text-slate-800 block">{{ $stats['empty'] }}</span>
            </div>
            <div class="w-10 h-10 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Subtest breakdown and Radar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Left: Analysis per Subtest (2 Cols) --}}
        <div class="md:col-span-2 space-y-6">
            <div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Performa Tiap Subtest</h3>
            </div>
            
            <div class="space-y-3">
                @foreach($breakdown as $sub)
                <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-xs flex items-center gap-6">
                    <div class="flex-1 min-w-0 space-y-1">
                        <div class="flex justify-between items-baseline">
                            <h4 class="text-xs font-bold text-slate-700 truncate leading-snug">{{ $sub['title'] }}</h4>
                            <span class="text-xs font-bold text-slate-800 shrink-0 ml-4">{{ $sub['score'] }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-700" style="width: {{ $sub['score'] }}%"></div>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block pt-0.5">{{ $sub['correct'] }} dari {{ $sub['total'] }} soal benar</span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Strength & Weakness Visualization --}}
            <div class="bg-white p-6 border border-slate-200 rounded-xl shadow-xs">
                <div class="mb-4">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Radar Distribusi Nilai</h3>
                </div>
                <div class="h-[280px] relative">
                    <canvas id="performanceRadar"></canvas>
                </div>
            </div>
        </div>

        {{-- Right: What's Next & Recommendations --}}
        <div class="space-y-6">
            <div>
                 <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rekomendasi & Tindakan</h3>
            </div>

            <div class="bg-slate-900 rounded-xl p-5 text-white space-y-5 shadow-xs border border-slate-800">
                <div class="space-y-1.5">
                    <span class="text-blue-400 text-[9px] font-bold uppercase tracking-wider">Saran Pemantapan</span>
                    <p class="text-xs font-medium leading-relaxed text-slate-300">
                        Berdasarkan rasio skor terendah Anda, disarankan untuk melakukan peninjauan kembali materi pelajaran pada subtest:
                        <span class="text-blue-300 font-bold block mt-1">"{{ $breakdown->sortBy('score')->first()['title'] ?? '-' }}"</span>
                    </p>
                </div>

                <div class="space-y-2.5 pt-4 border-t border-slate-800">
                    <a href="{{ route('dashboard') }}" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider text-center transition-all shadow-sm">
                        Kembali ke Dashboard
                    </a>
                    @if($package->show_explanation)
                    <a href="{{ route('student.exam.explanation', $examResult) }}" class="block w-full py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider text-center transition-all">
                        Pelajari Pembahasan
                    </a>
                    @endif
                    <button onclick="window.print()" class="block w-full py-2 bg-transparent hover:text-white text-slate-400 text-[10px] font-bold uppercase tracking-wider text-center transition-all">
                        Cetak Laporan PDF
                    </button>
                    <a href="{{ route('student.exam.certificate', $examResult) }}" class="block w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider text-center transition-all shadow-sm">
                        Unduh Sertifikat Resmi
                    </a>
                </div>
            </div>

            {{-- Support Card --}}
            <div class="p-4 bg-amber-50/50 border border-amber-100 rounded-xl flex gap-3">
                <div class="w-8 h-8 bg-amber-100 text-amber-700 rounded-lg flex items-center justify-center shrink-0 border border-amber-200/50">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-amber-900 leading-none">Punya Pertanyaan?</p>
                    <p class="text-[10px] font-semibold text-amber-700 leading-normal mt-1.5">
                        Hubungi pengawas ujian atau administrator di sekolah Anda untuk klarifikasi lebih lanjut.
                    </p>
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
                        backgroundColor: isRadar ? 'rgba(37, 99, 235, 0.08)' : 'rgba(37, 99, 235, 0.8)',
                        borderColor: '#2563eb',
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#2563eb',
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
                                font: { size: 9, weight: '700', family: 'system-ui' },
                                color: '#64748b'
                            },
                            grid: { color: 'rgba(226, 232, 240, 0.8)' }
                        }
                    } : {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                font: { size: 9, weight: '600' },
                                color: '#64748b'
                            },
                            grid: { color: 'rgba(226, 232, 240, 0.5)' }
                        },
                        x: {
                            ticks: {
                                font: { size: 9, weight: '700' },
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

<style>
    @media print {
        header, .action-buttons, .no-print { display: none !important; }
        .print-only { display: block !important; }
        body { background: white; }
        .max-w-5xl { max-width: 100%; margin: 0; padding: 0; }
        .bg-slate-900 { background: #f8fafc !important; color: #1e293b !important; }
        .text-blue-400, .text-blue-300 { color: #2563eb !important; }
    }
</style>
@endsection
