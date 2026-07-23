@extends('layouts.admin')

@section('page_title', 'Dashboard Admin')

@section('content')
<!-- Alert Banner -->
<div class="mb-6 bg-[#eff6ff] border border-blue-100 rounded-2xl p-4 flex items-center justify-between text-blue-800" x-data="{ show: true }" x-show="show">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm font-medium">Sistem ujian online (CBT) berjalan normal. Tidak ada kendala server terdeteksi saat ini.</p>
    </div>
    <button @click="show = false" class="text-blue-500 hover:text-blue-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- Top Row: Admin Profile & Stats -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Admin Profile Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 relative overflow-hidden shadow-sm flex flex-col justify-between">
        <div class="flex items-start gap-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&bold=true&size=120" alt="Profile" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-slate-50">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">{{ Auth::user()->name }}</h3>
                <p class="text-xs font-semibold text-slate-400 mt-0.5">{{ Auth::user()->roles->first()->name ?? 'Administrator' }} · CBT Manager</p>
            </div>
        </div>
        <div class="border-t border-slate-100/80 my-5 pt-5 space-y-3.5">
            <div class="flex justify-between text-xs">
                <span class="text-slate-400 font-semibold uppercase">Email</span>
                <span class="text-slate-700 font-bold">{{ Auth::user()->email }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-slate-400 font-semibold uppercase">Server Time</span>
                <span class="text-slate-700 font-bold" id="server-time">{{ date('d M Y, H:i') }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-slate-400 font-semibold uppercase">Status Ujian</span>
                <span class="text-emerald-600 font-bold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $stats['active_sessions'] }} Sesi Berjalan
                </span>
            </div>
        </div>
    </div>

    <!-- Live Session Status Donut Chart Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Status Pengerjaan</h3>
            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">Hari Ini</span>
        </div>
        
        <div class="flex items-center gap-6 py-3">
            <div class="relative w-28 h-28 shrink-0 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#e2e8f0" stroke-width="3"></circle>
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#6366f1" stroke-width="3" stroke-dasharray="45 55" stroke-dashoffset="0"></circle>
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#f59e0b" stroke-width="3" stroke-dasharray="25 75" stroke-dashoffset="-45"></circle>
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#ef4444" stroke-width="3" stroke-dasharray="10 90" stroke-dashoffset="-70"></circle>
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#10b981" stroke-width="3" stroke-dasharray="20 80" stroke-dashoffset="-80"></circle>
                </svg>
                <div class="absolute text-center">
                    <span class="text-xs font-bold text-slate-700">Aktif</span>
                </div>
            </div>
            <!-- Legend -->
            <div class="flex-1 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5 font-semibold text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#6366f1]"></span>
                        <span>Mengerjakan</span>
                    </div>
                    <span class="font-bold text-slate-700">{{ $stats['active_sessions'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5 font-semibold text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                        <span>Selesai</span>
                    </div>
                    <span class="font-bold text-slate-700">{{ number_format($stats['total_students'] - $stats['active_sessions']) }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5 font-semibold text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span>
                        <span>Curang</span>
                    </div>
                    <span class="font-bold text-red-600">{{ $stats['total_violations'] }}</span>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-4 text-xs font-semibold text-slate-500 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Sistem Keamanan Ujian <span class="text-slate-800 font-bold">100% Aktif</span>
        </div>
    </div>

    <!-- CBT Stats Grid Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-slate-800">CBT Statistics</h3>
            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">Database</span>
        </div>

        <div class="grid grid-cols-2 gap-4 py-4">
            <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Total Siswa</div>
                <div class="text-lg font-black text-slate-800 mt-1">{{ number_format($stats['total_students']) }}</div>
            </div>
            <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Bank Soal</div>
                <div class="text-lg font-black text-slate-800 mt-1">{{ number_format($stats['total_questions']) }}</div>
            </div>
            <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Sesi Berjalan</div>
                <div class="text-lg font-black text-slate-800 mt-1">{{ $stats['active_sessions'] }}</div>
            </div>
            <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Pelanggaran</div>
                <div class="text-lg font-black text-red-600 mt-1">{{ $stats['total_violations'] }}</div>
            </div>
        </div>

        <button onclick="window.location.href='{{ route('admin.exam-sessions.index') }}'" class="w-full py-3 bg-[#111827] hover:bg-slate-800 text-white font-bold rounded-2xl transition-colors text-xs">
            Kelola Sesi Ujian
        </button>
    </div>
</div>

<!-- Row 2: Attendance / Server Status & Stats -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Server Status / Attendance Sim -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="text-center">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Waktu Sesi Berjalan</span>
            <span class="text-base font-black text-slate-800 mt-1 block">Tryout Berlangsung</span>
        </div>

        <div class="flex justify-center py-6">
            <!-- Circular timer SVG -->
            <div class="relative w-36 h-36 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#f1f5f9" stroke-width="2.5"></circle>
                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-dasharray="80 20"></circle>
                </svg>
                <div class="absolute text-center">
                    <span class="text-xs text-slate-400 font-semibold block">Sesi Ujian Aktif</span>
                    <span class="text-lg font-black text-slate-800 mt-0.5">{{ $stats['active_sessions'] }} Sesi</span>
                </div>
            </div>
        </div>

        <div class="text-center space-y-4">
            <span class="inline-block px-3 py-1 bg-slate-50 border border-slate-100 rounded-full text-xs font-bold text-slate-600">Sistem: Siap Menerima Jawaban</span>
            <p class="text-xs font-semibold text-slate-400">Terakhir Sinkronisasi <span class="text-slate-700 font-bold">Baru saja</span></p>
            <button onclick="window.location.href='{{ route('admin.exam-sessions.index') }}'" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-500/20 text-xs">
                Mulai Sesi Baru
            </button>
        </div>
    </div>

    <!-- Small Hours/System Stats -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm lg:col-span-2 flex flex-col justify-between">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="border border-slate-100 rounded-2xl p-4">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span>
                    <span class="text-xs font-bold text-slate-700">{{ $stats['active_sessions'] }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Sesi Berjalan</span>
            </div>
            <div class="border border-slate-100 rounded-2xl p-4">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-slate-700">{{ number_format($stats['total_students']) }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Siswa Terdaftar</span>
            </div>
            <div class="border border-slate-100 rounded-2xl p-4">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-bold text-slate-700">{{ number_format($stats['total_questions']) }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Soal Database</span>
            </div>
            <div class="border border-slate-100 rounded-2xl p-4">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-xs font-bold text-slate-700">{{ $stats['total_violations'] }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Total Pelanggaran</span>
            </div>
        </div>

        <!-- System statistics row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-b border-slate-100/80 py-5 my-5">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase block">Rata-rata Durasi Sesi</span>
                <span class="text-base font-black text-slate-700 mt-1 block">120 Menit</span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Paket Ujian</span>
                <span class="text-base font-black text-slate-700 mt-1 block">{{\App\Models\ExamPackage::count()}} Paket</span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Mata Pelajaran</span>
                <span class="text-base font-black text-slate-700 mt-1 block">{{\App\Models\Subject::count()}} Mapel</span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase block">Aktivitas Hari Ini</span>
                <span class="text-base font-black text-slate-700 mt-1 block">Sangat Aktif</span>
            </div>
        </div>

        <!-- Progress Timeline (Simulated) -->
        <div class="space-y-3">
            <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden flex">
                <div class="h-full bg-indigo-500" style="width: 45%"></div>
                <div class="h-full bg-emerald-500" style="width: 35%"></div>
                <div class="h-full bg-yellow-400" style="width: 15%"></div>
                <div class="h-full bg-rose-500" style="width: 5%"></div>
            </div>
            <div class="flex justify-between text-[10px] text-slate-400 font-bold">
                <span>00:00</span>
                <span>06:00</span>
                <span>12:00</span>
                <span>18:00</span>
                <span>24:00</span>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Active Students (Projects) & System Log (Tasks) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Active Students List (Mocking Ongoing Projects) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-slate-800">Siswa Aktif Ujian</h3>
            <span class="text-xs font-bold text-indigo-600 hover:underline cursor-pointer">Monitoring Sesi</span>
        </div>
        
        <div class="space-y-4">
            @forelse ($active_students as $student)
                @php $attempt = $student->examAttempts->first(); @endphp
                <div class="p-4 border border-slate-100 rounded-2xl flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=6366f1&color=fff&bold=true&size=50" alt="Avatar" class="w-10 h-10 rounded-xl object-cover">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">{{ $student->name }}</h4>
                            <span class="text-[10px] font-semibold text-slate-400">{{ $student->school ?? 'Umum' }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-600 block">Ujian</span>
                        <span class="text-[10px] font-medium text-slate-400 block mt-0.5 truncate max-w-[120px]">{{ $attempt->examPackage->title ?? 'Tryout' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-600 block">Progres</span>
                        <span class="text-sm font-bold text-indigo-600 block mt-0.5">45%</span>
                    </div>
                </div>
            @empty
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 text-slate-300">
                        📭
                    </div>
                    <p class="text-slate-400 text-sm font-medium">Belum ada siswa aktif saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- System Logs List (Mocking Tasks) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-slate-800">Log Aktivitas Terbaru</h3>
            <span class="text-xs font-bold text-indigo-600 hover:underline cursor-pointer">Sistem</span>
        </div>

        <div class="space-y-3.5">
            @forelse($latest_logs as $log)
                <div class="flex items-center justify-between p-3.5 hover:bg-slate-50/50 rounded-2xl transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-sm">
                            @if(in_array($log->action, ['tab_switch', 'window_blur', 'fullscreen_exit', 'copy_attempt', 'right_click']))
                                ⚠️
                            @elseif($log->action == 'exam_started')
                                🚀
                            @elseif($log->action == 'exam_finished')
                                ✅
                            @else
                                🔔
                            @endif
                        </span>
                        <div>
                            <span class="text-sm font-bold text-slate-700 capitalize">{{ str_replace('_', ' ', $log->action) }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 block">{{ $log->user->name ?? 'User' }}</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 text-slate-300">
                        📭
                    </div>
                    <p class="text-slate-400 text-sm font-medium">Belum ada aktivitas terekam.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
