@extends('layouts.student-mobile')

@section('content')
<div class="space-y-6">
    {{-- Session Flash Alerts --}}
    @if(session('error'))
        <div class="p-3.5 bg-rose-50 border border-rose-100 rounded-xl text-xs font-semibold text-rose-600 shadow-sm animate-in fade-in duration-200">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-100 rounded-xl text-xs font-semibold text-emerald-650 shadow-sm animate-in fade-in duration-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Mobile Greeting Card --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-5 shadow-md text-white relative overflow-hidden">
        <div class="absolute right-0 bottom-0 opacity-5 translate-y-6 translate-x-6 pointer-events-none">
            <svg class="w-28 h-28" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <div class="relative z-10 space-y-4">
            <div>
                <span class="text-[9px] font-bold tracking-widest text-indigo-200 uppercase bg-white/10 px-2 py-0.5 rounded-full">Siswa Portal</span>
                <h2 class="text-base font-extrabold text-white mt-1.5 leading-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h2>
                <p class="text-xs text-indigo-100 mt-0.5">Siap untuk memulai evaluasi tryout hari ini?</p>
            </div>
            
            <div class="grid grid-cols-2 gap-3 pt-1">
                <div class="bg-white/10 border border-white/10 rounded-xl p-3 text-center backdrop-blur-xs">
                    <span class="block text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Ujian Aktif</span>
                    <span class="text-lg font-black text-white mt-0.5 block">{{ $sessions->count() }}</span>
                </div>
                <div class="bg-white/10 border border-white/10 rounded-xl p-3 text-center backdrop-blur-xs">
                    <span class="block text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Telah Selesai</span>
                    <span class="text-lg font-black text-white mt-0.5 block">{{ $completedResults->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Exam Sessions List --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sesi Ujian Aktif</h3>
            <span class="text-[10px] font-semibold text-slate-400">Total: {{ $sessions->count() }}</span>
        </div>

        @forelse($sessions as $session)
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4 transition-all active:scale-[0.99] active:bg-slate-50/50">
                <div class="flex items-center justify-between gap-2">
                    <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-100 rounded text-[9px] font-extrabold text-indigo-600 uppercase tracking-wider">
                        {{ str_replace('_', ' ', $session->examPackage->type) }}
                    </span>
                    <span class="text-[9px] font-bold text-slate-400">#{{ $session->id }}</span>
                </div>

                <div class="space-y-1">
                    <h4 class="text-xs font-bold text-slate-800 leading-snug">
                        {{ $session->title }}
                    </h4>
                    <p class="text-[11px] text-slate-500 font-medium">{{ $session->examPackage->title }}</p>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[10px] text-slate-450 border-t border-slate-100 pt-3">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="truncate">Durasi: {{ $session->examPackage->subtests->sum('duration_minutes') }}m</span>
                    </div>
                    <div class="flex items-center gap-1.5 justify-end">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="truncate">Batas: {{ $session->end_time->format('d/m H:i') }}</span>
                    </div>
                </div>

                <div class="pt-1">
                    @if($session->in_progress_result)
                        <a href="{{ route('student.exam.show', $session->in_progress_result) }}" 
                           class="flex items-center justify-center gap-1.5 w-full py-2.5 bg-amber-550 hover:bg-amber-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Lanjutkan Ujian
                        </a>
                    @else
                        <button type="button" onclick="openTokenModal({{ $session->id }}, '{{ addslashes($session->title) }}')" 
                                class="flex items-center justify-center gap-1.5 w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm shadow-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mulai Kerjakan
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-10 flex flex-col items-center justify-center text-center bg-white border border-slate-200/80 rounded-2xl px-6">
                <div class="w-9 h-9 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-3 border border-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Belum Ada Sesi Ujian</h4>
                <p class="text-[11px] text-slate-450 mt-1 max-w-[200px]">Saat ini tidak ada sesi evaluasi tryout aktif untuk Anda.</p>
            </div>
        @endforelse
    </div>

    {{-- Exam History Section --}}
    <div id="riwayat-ujian" class="space-y-3 pt-2 scroll-mt-16">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Riwayat & Hasil</h3>
            <span class="text-[10px] font-semibold text-slate-400">Telah Selesai: {{ $completedResults->count() }}</span>
        </div>

        <div class="space-y-3">
            @forelse($completedResults as $result)
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-slate-850 leading-snug">
                                {{ $result->examSession->title }}
                            </h4>
                            <p class="text-[10px] text-slate-450 font-medium">{{ $result->examSession->examPackage->title }}</p>
                        </div>
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-1.5 text-center shrink-0">
                            <span class="block text-[8px] font-bold text-indigo-650 uppercase tracking-wider">Skor</span>
                            <span class="text-xs font-black text-indigo-700 block mt-0.5">{{ $result->total_score }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[10px] text-slate-450 border-t border-slate-100 pt-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Selesai: {{ $result->finished_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <div class="flex items-center gap-2.5 pt-1">
                        <a href="{{ route('student.exam.results', $result) }}" 
                           class="flex-1 flex items-center justify-center py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all">
                            Lihat Hasil
                        </a>
                        @if($result->examSession->examPackage->show_explanation)
                            <a href="{{ route('student.exam.explanation', $result) }}" 
                               class="flex-1 flex items-center justify-center py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm">
                                Pembahasan
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-xs font-medium text-slate-400 bg-white rounded-xl border border-slate-100 p-4">
                    Belum ada riwayat ujian yang diselesaikan.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Token Entry Modal --}}
<div id="tokenModal" class="{{ $errors->has('token') ? '' : 'hidden' }} fixed inset-0 z-[100] flex items-end sm:items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeTokenModal()"></div>
    
    {{-- Modal Body --}}
    <div class="relative bg-white rounded-t-2xl sm:rounded-2xl text-left overflow-hidden shadow-xl transform transition-all w-full max-w-sm border-t sm:border border-slate-100 z-10 animate-in slide-in-from-bottom duration-300">
        <form action="{{ route('student.exam.start') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_session_id" id="modal_session_id" value="{{ old('exam_session_id') }}">
            
            {{-- Header --}}
            <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50/30 to-blue-50/10 flex items-start gap-3">
                <div class="w-9 h-9 bg-indigo-50 text-indigo-650 rounded-xl flex items-center justify-center border border-indigo-100 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-800" id="modal_session_title">
                        {{ old('exam_session_id') ? \App\Models\ExamSession::find(old('exam_session_id'))->title ?? 'Mulai Pengerjaan Ujian' : 'Mulai Pengerjaan Ujian' }}
                    </h3>
                    <p class="text-[10px] text-slate-450 mt-0.5">Memerlukan kode akses (token) ujian.</p>
                </div>
            </div>

            {{-- Body Content --}}
            <div class="p-5 space-y-4">
                @if($errors->has('token'))
                    <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl text-[11px] font-semibold text-rose-600">
                        {{ $errors->first('token') }}
                    </div>
                @endif
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-slate-500 ml-1 uppercase tracking-wider">Token Akses</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </span>
                        <input type="text" name="token" required placeholder="TOKEN" maxlength="10"
                               class="w-full pl-10 pr-4 border-slate-200 rounded-xl text-center text-xs font-bold tracking-[0.25em] text-indigo-700 focus:ring-2 focus:ring-indigo-150 focus:border-indigo-500 py-2.5 transition-all bg-slate-50/50 uppercase">
                    </div>
                </div>

                {{-- Alert --}}
                <div class="p-3 bg-indigo-50/40 rounded-xl border border-indigo-100/30">
                    <div class="flex gap-2">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-[10px] font-semibold text-indigo-850 leading-normal">
                            Setelah token diverifikasi, waktu pengerjaan otomatis berjalan. Pastikan internet stabil.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                <button type="button" onclick="closeTokenModal()" 
                        class="flex-1 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-650 rounded-lg text-xs font-bold uppercase tracking-wider transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
                    Mulai
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTokenModal(sessionId, sessionTitle) {
        document.getElementById('modal_session_id').value = sessionId;
        document.getElementById('modal_session_title').innerText = sessionTitle;
        document.getElementById('tokenModal').classList.remove('hidden');
        document.getElementById('tokenModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeTokenModal() {
        document.getElementById('tokenModal').classList.add('hidden');
        document.getElementById('tokenModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush
@endsection
