@extends('layouts.student')

@section('page_title', 'Ujian Selesai')
@section('page_subtitle', 'Terima kasih telah mengikuti ujian ini.')

@section('content')
<div class="max-w-xl mx-auto py-20 text-center space-y-8">
    <div class="relative inline-block">
        <div class="w-32 h-32 bg-green-50 text-green-500 rounded-[2.5rem] flex items-center justify-center mx-auto shadow-xl shadow-green-100 rotate-12 group hover:rotate-0 transition-transform duration-500">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="absolute -top-4 -right-4 w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center animate-bounce shadow-lg shadow-indigo-100">
             <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
    </div>

    <div class="space-y-4">
        <h2 class="text-4xl font-black text-slate-800 tracking-tight uppercase leading-tight">YAY! KAMU BERHASIL!</h2>
        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] leading-relaxed">Ujian: {{ $session->title }}</p>
        
        <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm space-y-4 max-w-md mx-auto">
            <p class="text-sm font-medium text-slate-600 leading-relaxed">
                Jawaban kamu telah tersimpan dengan aman di server kami. Sesuai dengan kebijakan sesi ini, skor tidak ditampilkan secara langsung.
            </p>
            <div class="h-px bg-slate-50 w-full"></div>
            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                Silakan hubungi admin atau cek pengumuman berkala untuk melihat hasil resmi.
            </p>
        </div>
    </div>

    <div class="pt-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl shadow-slate-200 hover:bg-indigo-600 transition-all hover:-translate-y-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
