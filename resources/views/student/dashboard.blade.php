@extends('layouts.student')

@section('page_title', 'Dashboard Siswa')
@section('page_subtitle', 'Selamat datang di portal ujian. Silakan pilih sesi ujian aktif Anda.')

@section('content')
<div class="space-y-8">
    {{-- Session Flash Alerts --}}
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-150 rounded-xl text-xs font-semibold text-rose-650 shadow-sm animate-in fade-in duration-250">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-150 rounded-xl text-xs font-semibold text-emerald-650 shadow-sm animate-in fade-in duration-250">
            {{ session('success') }}
        </div>
    @endif

    {{-- Top Greeting & Summary Bar --}}
    <div class="bg-gradient-to-r from-blue-700 to-indigo-800 border border-transparent rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 text-white">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center shrink-0 border border-white/15">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Siap untuk Memulai Evaluasi?</h2>
                <p class="text-xs text-blue-100 mt-0.5">Pilih salah satu sesi aktif di bawah untuk mulai mengerjakan simulasi tryout.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white/10 border border-white/15 rounded-lg text-center min-w-[100px]">
                <span class="block text-[10px] font-semibold text-blue-200 uppercase tracking-wider">Ujian Aktif</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $sessions->count() }}</span>
            </div>
            <div class="px-4 py-2 bg-white/10 border border-white/15 rounded-lg text-center min-w-[100px]">
                <span class="block text-[10px] font-semibold text-blue-200 uppercase tracking-wider">Telah Selesai</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $completedResults->count() }}</span>
            </div>
        </div>
    </div>

    {{-- Main Sections Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Active Exam Sessions List --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="mb-2">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sesi Ujian yang Tersedia</h3>
            </div>

            @forelse($sessions as $session)
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:border-blue-300 transition-all flex flex-col md:flex-row justify-between md:items-center p-5 gap-4 group">
                    <div class="space-y-2.5 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 bg-blue-50 border border-blue-100 rounded text-[9px] font-bold text-blue-700 uppercase tracking-wide">
                                {{ str_replace('_', ' ', $session->examPackage->type) }}
                            </span>
                            <span class="text-[10px] font-semibold text-slate-400">ID Sesi: #{{ $session->id }}</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-800 leading-snug group-hover:text-blue-600 transition-colors">
                                {{ $session->title }}
                            </h4>
                            <p class="text-xs text-slate-450 mt-0.5">{{ $session->examPackage->title }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-slate-500 pt-1 flex-wrap">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Batas: {{ $session->end_time->format('d M Y, H:i') }} WIB
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Durasi: {{ $session->examPackage->subtests->sum('duration_minutes') }} Menit
                            </span>
                        </div>
                    </div>

                    <div class="shrink-0">
                        @if($session->in_progress_result)
                            <a href="{{ route('student.exam.show', $session->in_progress_result) }}" 
                               class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold uppercase tracking-wider transition-colors shadow-sm w-full md:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Lanjutkan
                            </a>
                        @else
                            <button type="button" onclick="openTokenModal({{ $session->id }}, '{{ addslashes($session->title) }}')" 
                                    class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold uppercase tracking-wider transition-colors w-full md:w-auto">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Kerjakan
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-12 flex flex-col items-center justify-center text-center bg-white rounded-xl border border-dashed border-slate-350 px-6">
                    <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-3.5 border border-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Belum Ada Sesi Ujian Aktif</h4>
                    <p class="text-xs text-slate-450 mt-1 max-w-sm">Saat ini tidak ada sesi evaluasi tryout yang dijadwalkan untuk Anda.</p>
                </div>
            @endforelse
        </div>

        {{-- Right: Profile Information Card --}}
        <div class="space-y-4">
            <div class="mb-2">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Identitas Peserta</h3>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                <div class="flex items-center gap-3.5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e0f2fe&color=0369a1&bold=true" 
                         alt="User Avatar" 
                         class="w-11 h-11 rounded-lg border border-slate-200">
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 truncate leading-snug">{{ Auth::user()->name }}</h4>
                        <span class="text-[10px] font-semibold text-slate-400 block mt-0.5">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-2.5 text-xs">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-450">Asal Sekolah</span>
                        <span class="font-semibold text-slate-700">{{ Auth::user()->school ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-450">Tipe Anggota</span>
                        <span class="px-2 py-0.5 rounded bg-blue-50 border border-blue-100 text-[9px] font-bold text-blue-700 uppercase tracking-wide">Siswa Pro</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Exam History Section --}}
    <div id="riwayat-ujian" class="space-y-4 pt-4 scroll-mt-24">
        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Riwayat Hasil & Pembahasan Ujian</h3>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Sesi & Detail Paket Ujian</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Selesai Pada</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Skor Akhir</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($completedResults as $result)
                            <tr class="group hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold text-slate-800 block group-hover:text-blue-600 transition-colors">{{ $result->examSession->title }}</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $result->examSession->examPackage->title }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-550">{{ $result->finished_at->format('d M Y, H:i') }} WIB</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-xs font-bold text-slate-800">{{ $result->total_score }}</span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Poin</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('student.exam.results', $result) }}" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-all">
                                            Lihat Hasil
                                        </a>
                                        @if($result->examSession->examPackage->show_explanation)
                                            <a href="{{ route('student.exam.explanation', $result) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-all shadow-sm">
                                                Pembahasan
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-xs font-medium text-slate-400">
                                    Belum ada riwayat ujian yang diselesaikan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Token Entry Modal --}}
<div id="tokenModal" class="{{ $errors->has('token') ? '' : 'hidden' }} fixed inset-0 z-[100] overflow-y-auto animate-in fade-in duration-200" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeTokenModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200">
            <form action="{{ route('student.exam.start') }}" method="POST">
                @csrf
                <input type="hidden" name="exam_session_id" id="modal_session_id" value="{{ old('exam_session_id') }}">
                
                {{-- Header / Hero Area --}}
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-blue-50/40 to-indigo-50/20 flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100 shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800" id="modal_session_title">{{ old('exam_session_id') ? \App\Models\ExamSession::find(old('exam_session_id'))->title ?? 'Mulai Pengerjaan Ujian' : 'Mulai Pengerjaan Ujian' }}</h3>
                        <p class="text-xs text-slate-450 mt-0.5">Sesi ujian ini memerlukan verifikasi kode akses (token).</p>
                    </div>
                </div>

                {{-- Input / Instruction Content --}}
                <div class="p-6 space-y-4">
                    @if($errors->has('token'))
                        <div class="p-3.5 bg-rose-50 border border-rose-100 rounded-xl text-xs font-semibold text-rose-600">
                            {{ $errors->first('token') }}
                        </div>
                    @endif
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-600 ml-1">Token Akses</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </span>
                            <input type="text" name="token" required placeholder="MASUKKAN TOKEN" maxlength="10"
                                   class="w-full pl-11 pr-4 border-slate-200 rounded-xl text-center text-sm font-bold tracking-[0.25em] text-blue-700 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 py-3 transition-all bg-slate-50/30 uppercase">
                        </div>
                    </div>

                    {{-- Stable Connection Alert --}}
                    <div class="p-3.5 bg-blue-50/50 rounded-xl border border-blue-100/50">
                        <div class="flex gap-2.5">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-[11px] font-medium text-blue-800 leading-normal">
                                Setelah token diverifikasi, waktu pengerjaan akan segera berjalan secara otomatis. Pastikan koneksi internet Anda stabil.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="p-5 bg-slate-50 border-t border-slate-150 flex gap-3">
                    <button type="button" onclick="closeTokenModal()" 
                            class="flex-1 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold uppercase tracking-wider transition-all shadow-sm">
                        Mulai Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openTokenModal(sessionId, sessionTitle) {
        document.getElementById('modal_session_id').value = sessionId;
        document.getElementById('modal_session_title').innerText = sessionTitle;
        document.getElementById('tokenModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTokenModal() {
        document.getElementById('tokenModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush
@endsection
