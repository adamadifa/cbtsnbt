@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 pb-6">

    <!-- ==================== LEFT & MIDDLE TWO-COLUMN AREA (lg:col-span-8) ==================== -->
    <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-5 self-start">

        <!-- CARD 1: Sesi Ujian & Peserta (Marketing Channels style) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-xs font-bold text-slate-700">Tingkat Penyelesaian Ujian</h3>
                        <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                    </div>
                    <a href="{{ route('admin.exam-sessions.index') }}" class="text-[11px] font-semibold text-slate-600 hover:text-slate-900 px-2.5 py-1 rounded-lg border border-slate-200/80 bg-white shadow-2xs hover:bg-slate-50 transition-colors">
                        Details
                    </a>
                </div>

                <!-- Big Metric & Badge -->
                <div class="flex items-baseline gap-2.5 mb-3">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['completion_rate'] }}%</span>
                    <span class="text-[11px] font-bold {{ $stats['finished_attempts'] > 0 ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500 bg-slate-50' }} px-1.5 py-0.5 rounded-md">
                        {{ $stats['finished_attempts'] }} selesai <span class="font-normal text-slate-500">dari {{ $stats['total_attempts'] }} attempt</span>
                    </span>
                </div>

                <!-- Segmented Progress Bar (Orange, Amber, Cyan) -->
                @php
                    $activePercent = $stats['total_attempts'] > 0 ? round(($stats['active_attempts'] / $stats['total_attempts']) * 100) : 0;
                    $finishedPercent = $stats['total_attempts'] > 0 ? round(($stats['finished_attempts'] / $stats['total_attempts']) * 100) : 0;
                    $otherPercent = max(0, 100 - ($activePercent + $finishedPercent));
                @endphp
                <div class="h-2 w-full rounded-full bg-slate-100 flex overflow-hidden gap-1 mb-3">
                    <div class="bg-orange-500 rounded-full h-full" style="width: {{ max($activePercent, 10) }}%;"></div>
                    <div class="bg-amber-400 rounded-full h-full" style="width: {{ max($finishedPercent, 10) }}%;"></div>
                    <div class="bg-emerald-400 rounded-full h-full" style="width: {{ max($otherPercent, 10) }}%;"></div>
                </div>

                <!-- Legend indicators -->
                <div class="flex items-center gap-4 text-[11px] font-medium text-slate-500 mb-4">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span> Aktif ({{ $stats['active_attempts'] }})
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span> Selesai ({{ $stats['finished_attempts'] }})
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Sesi ({{ $stats['active_sessions'] }})
                    </span>
                </div>

                <!-- Metric Breakdown Table -->
                <div class="border-t border-slate-100 pt-3">
                    <div class="grid grid-cols-12 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                        <span class="col-span-6">Kategori</span>
                        <span class="col-span-3 text-right">Data</span>
                        <span class="col-span-3 text-right">Status</span>
                    </div>

                    <div class="space-y-2.5 text-xs font-semibold">
                        <div class="grid grid-cols-12 items-center text-slate-700">
                            <span class="col-span-6 flex items-center gap-2 text-slate-600 font-medium">
                                <i class="ti ti-users text-slate-400 text-sm shrink-0"></i>
                                Siswa Terdaftar
                            </span>
                            <span class="col-span-3 text-right font-bold text-slate-900">{{ number_format($stats['total_students']) }}</span>
                            <span class="col-span-3 text-right text-emerald-600 font-bold text-[11px]">Real DB</span>
                        </div>

                        <div class="grid grid-cols-12 items-center text-slate-700">
                            <span class="col-span-6 flex items-center gap-2 text-slate-600 font-medium">
                                <i class="ti ti-clock text-slate-400 text-sm shrink-0"></i>
                                Durasi Rata-rata
                            </span>
                            <span class="col-span-3 text-right font-bold text-slate-900">{{ $stats['avg_duration_minutes'] }} Menit</span>
                            <span class="col-span-3 text-right text-slate-400 font-medium text-[11px]">Sesi</span>
                        </div>

                        <div class="grid grid-cols-12 items-center text-slate-700">
                            <span class="col-span-6 flex items-center gap-2 text-slate-600 font-medium">
                                <i class="ti ti-circle-check text-slate-400 text-sm shrink-0"></i>
                                Kelulusan Passing
                            </span>
                            <span class="col-span-3 text-right font-bold text-slate-900">{{ $stats['passing_rate'] }}%</span>
                            <span class="col-span-3 text-right text-emerald-600 font-bold text-[11px]">Score ≥50</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Reports Link Button -->
            <a href="{{ route('admin.exam-sessions.index') }}" class="mt-4 w-full py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold text-xs rounded-xl border border-slate-100 text-center transition-colors">
                Lihat Laporan Sesi
            </a>
        </div>

        <!-- CARD 2: Bank Soal & Materi Uji (Product Categories style) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-xs font-bold text-slate-700">Bank & Kategori Soal</h3>
                        <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                    </div>
                    <a href="{{ route('admin.questions.index') }}" class="text-[11px] font-semibold text-slate-600 hover:text-slate-900 px-2.5 py-1 rounded-lg border border-slate-200/80 bg-white shadow-2xs hover:bg-slate-50 transition-colors">
                        Details
                    </a>
                </div>

                <!-- Big Metric & Badge -->
                <div class="flex items-baseline gap-2.5 mb-4">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_questions']) }}</span>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                        {{ $stats['total_packages'] }} Paket Tryout
                    </span>
                </div>

                <!-- Orange Equalizer / Vertical Bar Stripes (exact match to screenshot) -->
                <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-100 mb-4">
                    <div class="flex items-end justify-between gap-1 h-9 px-1">
                        <div class="w-1.5 bg-orange-500 rounded-full h-8"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-6"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-9"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-7"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-5"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-8"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-9"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-6"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-7"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-9"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-5"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-8"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-6"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-9"></div>
                        <div class="w-1.5 bg-orange-500 rounded-full h-7"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs font-semibold text-slate-700 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-600">Materi Uji</span>
                        <div class="flex items-center gap-0.5 text-slate-400 text-xs">
                            <button class="hover:text-slate-600"><i class="ti ti-chevron-left text-xs"></i></button>
                            <button class="hover:text-slate-600"><i class="ti ti-chevron-right text-xs"></i></button>
                        </div>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900">{{ $stats['total_subjects'] }} Subtest</span>
                        <span class="text-emerald-600 font-bold text-[11px] ml-1">Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Segmen Donut Chart (Customer Segments style) -->
            <div class="pt-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-xs font-bold text-slate-700">Distribusi Sekolah Siswa</h3>
                        <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                    </div>
                    <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded-md">Total {{ $stats['total_students'] }}</span>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Donut SVG (Orange, Amber, Cyan) -->
                    @php
                        $smaDash = max(1, round($stats['sma_percent'] * 0.88));
                        $smkDash = max(1, round($stats['smk_percent'] * 0.88));
                        $umumDash = max(1, round($stats['umum_percent'] * 0.88));
                    @endphp
                    <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="14" fill="none" stroke="#e2e8f0" stroke-width="4"></circle>
                            @if($stats['sma_percent'] > 0)
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#f97316" stroke-width="4" stroke-dasharray="{{ $smaDash }} {{ 88 - $smaDash }}" stroke-dashoffset="0"></circle>
                            @endif
                            @if($stats['smk_percent'] > 0)
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#fbbf24" stroke-width="4" stroke-dasharray="{{ $smkDash }} {{ 88 - $smkDash }}" stroke-dashoffset="-{{ $smaDash }}"></circle>
                            @endif
                            @if($stats['umum_percent'] > 0)
                                <circle cx="18" cy="18" r="14" fill="none" stroke="#06b6d4" stroke-width="4" stroke-dasharray="{{ $umumDash }} {{ 88 - $umumDash }}" stroke-dashoffset="-{{ $smaDash + $smkDash }}"></circle>
                            @endif
                        </svg>
                    </div>

                    <!-- Breakdown Numbers -->
                    <div class="flex-1 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-orange-500 shrink-0"></span>
                                <span class="text-slate-500 font-medium text-[11px]">SMA/MA</span>
                            </div>
                            <span class="font-bold text-slate-800 text-[11px]">{{ $stats['sma_count'] }} <span class="text-slate-400 font-normal">{{ $stats['sma_percent'] }}%</span></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                                <span class="text-slate-500 font-medium text-[11px]">SMK</span>
                            </div>
                            <span class="font-bold text-slate-800 text-[11px]">{{ $stats['smk_count'] }} <span class="text-slate-400 font-normal">{{ $stats['smk_percent'] }}%</span></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-cyan-500 shrink-0"></span>
                                <span class="text-slate-500 font-medium text-[11px]">Umum</span>
                            </div>
                            <span class="font-bold text-slate-800 text-[11px]">{{ $stats['umum_count'] }} <span class="text-slate-400 font-normal">{{ $stats['umum_percent'] }}%</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 3: Tracking Kehadiran / Grafik Step (Shipping Tracking style) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-xs font-bold text-slate-700">Kehadiran Peserta</h3>
                        <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200/80 bg-white">
                        <span>Database</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-2.5 mb-3">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_students']) }}</span>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">Total Siswa</span>
                </div>

                <!-- Pill Badges (Delivered, In-transit, Returned) -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-[11px] font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-lg">Hadir: {{ $stats['finished_attempts'] }}</span>
                    <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">Aktif: {{ $stats['active_attempts'] }}</span>
                    <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">Belum: {{ max(0, $stats['total_students'] - $stats['total_attempts']) }}</span>
                </div>

                <!-- Sub-header & Controls -->
                <div class="flex items-center justify-between text-xs font-semibold text-slate-700 mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-md bg-orange-100 flex items-center justify-center text-orange-600 text-xs">
                            <i class="ti ti-file-text"></i>
                        </div>
                        <span class="text-slate-700 font-bold text-xs">Paket Tryout UTBK SNBT</span>
                    </div>
                    <div class="flex items-center gap-1 text-slate-400 text-xs">
                        <button class="hover:text-slate-600"><i class="ti ti-chevron-left text-xs"></i></button>
                        <button class="hover:text-slate-600"><i class="ti ti-chevron-right text-xs"></i></button>
                    </div>
                </div>

                <!-- Step Chart (Orange stair graph matching screenshot) -->
                <div class="h-28 w-full relative pt-2">
                    <svg class="w-full h-full" viewBox="0 0 240 70" preserveAspectRatio="none">
                        <!-- Grid lines -->
                        <line x1="0" y1="18" x2="240" y2="18" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="0" y1="35" x2="240" y2="35" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="0" y1="52" x2="240" y2="52" stroke="#f1f5f9" stroke-width="1" />

                        <!-- Step line -->
                        <polyline fill="none" stroke="#f97316" stroke-width="1.8"
                            points="0,55 20,55 20,40 35,40 35,32 50,32 50,48 65,48 65,30 85,30 85,42 100,42 100,52 115,52 115,35 130,35 130,48 150,48 150,38 170,38 170,45 190,45 190,25 215,25 215,52 240,52" />
                    </svg>

                    <!-- Days label -->
                    <div class="flex justify-between text-[10px] font-semibold text-slate-400 mt-1">
                        @foreach($weeklyChart as $chartItem)
                            <span class="{{ $chartItem['is_today'] ? 'text-orange-600 font-bold' : '' }}">{{ $chartItem['day'] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 4: Channel Sesi & Grafik Tren (Sales Channels + Campaign style) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col justify-between">
            <div>
                <!-- Top Header -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-xs font-bold text-slate-700">Tingkat Aktivitas Tryout</h3>
                        <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200/80 bg-white">
                        <span>Aktif</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-2.5 mb-1">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['active_sessions'] }}</span>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">Sesi Berjalan</span>
                </div>
                <div class="flex justify-between text-[10px] text-slate-400 font-semibold mb-3">
                    <span>1 Sep 2026</span>
                    <span>10 Sep 2026</span>
                </div>

                <!-- Multi-color Segmented Pill Blocks (Blue, Sky, Violet, Orange, Yellow, Green) -->
                <div class="flex items-center gap-1 h-5 rounded-lg overflow-hidden mb-5">
                    <div class="bg-blue-600 h-full rounded-md" style="width: 25%;"></div>
                    <div class="bg-sky-400 h-full rounded-md" style="width: 20%;"></div>
                    <div class="bg-indigo-500 h-full rounded-md" style="width: 15%;"></div>
                    <div class="bg-orange-500 h-full rounded-md" style="width: 20%;"></div>
                    <div class="bg-amber-400 h-full rounded-md" style="width: 12%;"></div>
                    <div class="bg-emerald-500 h-full rounded-md" style="width: 8%;"></div>
                </div>

                <!-- Campaign / Submission Rate Section -->
                <div class="border-t border-slate-100 pt-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5">
                            <h3 class="text-xs font-bold text-slate-700">Tingkat Jawaban Masuk</h3>
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                        </div>
                        <a href="{{ route('admin.exam-sessions.index') }}" class="text-[11px] font-semibold text-slate-600 hover:text-slate-900 px-2 py-0.5 rounded-lg border border-slate-200/80 bg-white">
                            Details
                        </a>
                    </div>

                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-lg font-black text-slate-900 tracking-tight">{{ number_format($stats['total_answers']) }}</span>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">{{ $stats['recent_answers_count'] }} 15 hari terakhir</span>
                    </div>

                    <!-- Sparkline & Completion percent -->
                    <div class="flex items-end justify-between gap-3">
                        <div class="h-10 flex-1 relative">
                            <svg class="w-full h-full" viewBox="0 0 160 40" preserveAspectRatio="none">
                                <path fill="none" stroke="#f97316" stroke-width="1.8"
                                    d="M0,32 Q20,20 40,30 T80,18 T120,28 T160,10" />
                            </svg>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-sm font-black text-slate-900 block leading-tight">{{ $stats['completion_rate'] }}%</span>
                            <span class="text-[10px] font-medium text-slate-400 block">Selesai tepat waktu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ==================== RIGHT COLUMN (lg:col-span-4) - Analytics & Support Style ==================== -->
    <div class="lg:col-span-4 self-start">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col">
            
            <!-- Header: Support Analytics -->
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-1.5">
                    <h3 class="text-xs font-bold text-slate-700">Analisis CBT</h3>
                    <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                </div>
                <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200/80 bg-white">
                    <span>Mingguan</span>
                    <i class="ti ti-chevron-down text-xs text-slate-400"></i>
                </div>
            </div>

            <!-- Big Number -->
            <div class="flex items-baseline gap-2.5 mb-3">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_students'] + $stats['total_questions']) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                    Total Siswa & Soal
                </span>
            </div>

            <!-- Pill Tabs (Technical, Billing, Account, Product equivalent) -->
            <div class="flex items-center gap-1.5 mb-4 overflow-x-auto pb-1 text-[11px] font-semibold">
                <span class="px-2.5 py-1 rounded-lg bg-orange-50 text-orange-600 font-bold shrink-0">Tryout ({{ $stats['total_packages'] }})</span>
                <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 shrink-0 cursor-pointer">Siswa ({{ $stats['total_students'] }})</span>
                <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 shrink-0 cursor-pointer">Sesi ({{ $stats['active_sessions'] }})</span>
                <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 shrink-0 cursor-pointer">Pelanggaran ({{ $stats['total_violations'] }})</span>
            </div>

            <!-- Bar Chart with Target Tooltip (exact match to screenshot) -->
            <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100 mb-4 relative">
                <!-- Target Tooltip -->
                <div class="absolute top-4 left-4 bg-slate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs flex items-center gap-1 z-10">
                    <span>Target: 30m</span>
                </div>

                <!-- Dashed Target Line -->
                <div class="absolute top-1/2 left-4 right-4 border-b border-dashed border-slate-300 z-0"></div>

                <!-- Bars (Mon-Sun) using Real DB Data -->
                <div class="flex items-end justify-between gap-2 h-24 pt-4 px-2 relative z-1">
                    @foreach($weeklyChart as $item)
                        @php
                            $heightPercent = $maxWeeklyCount > 0 ? max(15, round(($item['count'] / $maxWeeklyCount) * 100)) : ($item['is_today'] ? 30 : 12);
                        @endphp
                        <div class="flex flex-col items-center gap-1 flex-1">
                            <div class="w-full max-w-[20px] {{ $item['is_today'] ? 'bg-orange-500 shadow-xs' : 'bg-slate-200' }} rounded-t-md" style="height: {{ $heightPercent }}%;"></div>
                            <span class="text-[9px] font-semibold {{ $item['is_today'] ? 'text-orange-600 font-bold' : 'text-slate-400' }}">{{ $item['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab Underline: All Tickets / Open / Solved -->
            <div class="flex items-center gap-4 text-xs font-bold border-b border-slate-100 pb-2 mb-3">
                <span class="text-orange-600 relative pb-2 -mb-2 border-b-2 border-orange-500 cursor-pointer">Aktivitas Ujian</span>
                <span class="text-slate-400 hover:text-slate-600 cursor-pointer">Sesi Aktif ({{ $stats['active_sessions'] }})</span>
                <span class="text-slate-400 hover:text-slate-600 cursor-pointer">Pelanggaran ({{ $stats['total_violations'] }})</span>
            </div>

            <!-- Channels / Performance Table -->
            <div class="space-y-2.5 text-xs font-semibold pb-4 border-b border-slate-100 mb-4">
                <div class="grid grid-cols-12 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    <span class="col-span-6">Metrik Database</span>
                    <span class="col-span-3 text-right">Nilai</span>
                    <span class="col-span-3 text-right">Status</span>
                </div>

                <div class="grid grid-cols-12 items-center">
                    <div class="col-span-6">
                        <span class="text-slate-800 font-bold block leading-tight text-xs">Jawaban Tersimpan</span>
                        <span class="text-[10px] text-slate-400 font-normal">Tabel student_answers</span>
                    </div>
                    <span class="col-span-3 text-right font-bold text-slate-900">{{ number_format($stats['total_answers']) }}</span>
                    <span class="col-span-3 text-right text-emerald-600 font-bold text-[11px]">Online</span>
                </div>

                <div class="grid grid-cols-12 items-center">
                    <div class="col-span-6">
                        <span class="text-slate-800 font-bold block leading-tight text-xs">Rata-rata Durasi Ujian</span>
                        <span class="text-[10px] text-slate-400 font-normal">Hasil ujian selesai</span>
                    </div>
                    <span class="col-span-3 text-right font-bold text-slate-900">{{ $stats['avg_duration_minutes'] }}m</span>
                    <span class="col-span-3 text-right text-slate-500 font-bold text-[11px]">Sesuai</span>
                </div>

                <div class="grid grid-cols-12 items-center">
                    <div class="col-span-6">
                        <span class="text-slate-800 font-bold block leading-tight text-xs">Total Pelanggaran</span>
                        <span class="text-[10px] text-slate-400 font-normal">Deteksi integritas browser</span>
                    </div>
                    <span class="col-span-3 text-right font-bold {{ $stats['total_violations'] > 0 ? 'text-red-600' : 'text-slate-900' }}">{{ $stats['total_violations'] }}</span>
                    <span class="col-span-3 text-right {{ $stats['total_violations'] > 0 ? 'text-red-500' : 'text-emerald-600' }} font-bold text-[11px]">
                        {{ $stats['total_violations'] > 0 ? 'Perlu Cek' : 'Aman' }}
                    </span>
                </div>
            </div>

            <!-- Recent Tickets / Students / Logs List (matching bottom right of screenshot) -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas Siswa Terbaru</h4>
                </div>

                <div class="space-y-3">
                    @forelse($latestActivities as $index => $act)
                        @php
                            $initial = substr($act['user_name'] ?? 'S', 0, 1);
                            $badgeColors = [
                                ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
                                ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
                                ['bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                            ];
                            $style = $badgeColors[$index % 3];
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full {{ $style['bg'] }} {{ $style['text'] }} font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800 leading-snug">{{ $act['user_name'] }}</h5>
                                    <div class="flex items-center gap-1.5 text-[10px]">
                                        <span class="font-semibold text-slate-400 capitalize">{{ $act['action'] }}</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-bold {{ $act['badge_class'] }} px-1.5 py-0.2 rounded">
                                            {{ $act['time_ago'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-700 block truncate max-w-[110px]">{{ $act['package_title'] }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">#ID-{{ $act['id'] }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-slate-400 font-medium">
                            Belum ada log aktivitas.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
