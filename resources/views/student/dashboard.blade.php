@extends('layouts.student')

@section('page_title', 'Dashboard Siswa')
@section('page_subtitle', 'Halo, ' . Auth::user()->name . '! Pilih ujian yang ingin kamu kerjakan hari ini.')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($sessions as $session)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1 flex flex-col group">
            <!-- Header Card -->
            <div class="p-5 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="text-right flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg mb-1">
                        {{ $session->examPackage->type }}
                    </span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sesi #{{ $session->id }}</p>
                </div>
            </div>

            <!-- Body Card -->
            <div class="p-5 flex-1">
                <h3 class="text-base font-black text-slate-800 tracking-tight mb-1 group-hover:text-indigo-600 transition-colors uppercase leading-tight">{{ $session->title }}</h3>
                <p class="text-xs font-bold text-slate-400 mb-4">{{ $session->examPackage->title }}</p>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-slate-500">
                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Berakhir Pada</p>
                            <p class="text-[11px] font-bold text-slate-700 leading-none">{{ $session->end_time->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-slate-500">
                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Durasi Paket</p>
                            <p class="text-[11px] font-bold text-slate-700 leading-none">{{ $session->examPackage->subtests->sum('duration_minutes') }} Menit</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Area -->
            <div class="p-5 bg-slate-50/20 border-t border-slate-50">
                @if($session->in_progress_result)
                    <a href="{{ route('student.exam.show', $session->in_progress_result) }}" class="w-full py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Lanjutkan Ujian
                    </a>
                @else
                    {{-- Open Start Gateway --}}
                    <button onclick="openTokenModal({{ $session->id }}, '{{ addslashes($session->title) }}')" class="w-full py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Mulai Kerjakan
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white rounded-3xl border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Belum Ada Ujian Aktif</h4>
            <p class="text-[11px] text-slate-400 font-bold mt-1 max-w-xs leading-relaxed uppercase tracking-widest">Cek kembali jadwal dari admin untuk melihat daftar pengerjaan ujian.</p>
        </div>
    @endforelse
</div>

{{-- Exam History Section --}}
<div id="riwayat-ujian" class="mt-12 space-y-6 pb-12 scroll-mt-24">
    <div class="flex items-center gap-3 ml-2">
        <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase leading-none">Riwayat Ujian</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lihat hasil dan pembahasan ujian yang sudah kamu selesaikan.</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Sesi & Paket</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Selesai Pada</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center">Skor Akhir</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($completedResults as $result)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <p class="text-sm font-black text-slate-800 group-hover:text-indigo-600 transition-colors uppercase leading-tight">{{ $result->examSession->title }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $result->examSession->examPackage->title }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs font-bold text-slate-600">{{ $result->finished_at->format('d M Y, H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-base font-black text-slate-800">{{ $result->total_score }}</span>
                                    <span class="text-[8px] font-black text-indigo-400 uppercase tracking-widest leading-none">Poin</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('student.exam.results', $result) }}" class="px-4 py-2 bg-slate-50 border border-slate-100 text-[10px] font-black text-slate-600 uppercase tracking-widest rounded-xl hover:bg-white hover:border-indigo-200 hover:text-indigo-600 transition-all shadow-sm shadow-slate-100">
                                        Lihat Hasil
                                    </a>
                                    @if($result->examSession->examPackage->show_explanation)
                                        <a href="{{ route('student.exam.explanation', $result) }}" class="px-4 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                                            Pembahasan
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada riwayat ujian yang diselesaikan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Token Entry Modal --}}
<div id="tokenModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeTokenModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
            <form action="{{ route('student.exam.start') }}" method="POST">
                @csrf
                <input type="hidden" name="exam_session_id" id="modal_session_id">
                
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-amber-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest" id="modal_session_title">Verifikasi Akses</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Masukkan token ujian untuk memulai.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-widest">Token Ujian</label>
                            <input type="text" name="token" required placeholder="CONTOH: XJ72AB" maxlength="10"
                                   class="w-full border-slate-100 rounded-xl text-xl font-black tracking-[0.2em] text-indigo-600 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 py-4 transition-all bg-slate-50/50 uppercase text-center shadow-sm">
                        </div>

                        <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                            <div class="flex gap-3">
                                <svg class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[10px] font-bold text-indigo-700 leading-normal uppercase tracking-wider">
                                    PENTING: Pastikan koneksi internet stabil. Setelah token diverifikasi, waktu pengerjaan akan langsung berjalan secara real-time.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-50/50 border-t border-slate-50 flex gap-3">
                    <button type="button" onclick="closeTokenModal()" class="flex-1 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
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
