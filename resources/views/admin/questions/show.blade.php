@extends('layouts.admin')

@section('page_title', 'Pratinjau Soal')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.questions.index') }}" class="text-[11px] font-bold text-slate-400 hover:text-indigo-600 transition-all flex items-center gap-2 uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
            Kembali ke Bank Soal
        </a>
        <a href="{{ route('admin.questions.edit', $question) }}" class="px-5 py-2.5 bg-amber-50 text-amber-600 rounded-xl text-xs font-bold hover:bg-amber-100 transition-all flex items-center gap-2 border border-amber-100 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Soal
        </a>
    </div>

    {{-- Question Card --}}
    <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden min-h-[400px]">
        <div class="p-8 md:p-12 border-b border-slate-50">
            <div class="flex items-center gap-3 mb-8">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-full">
                    {{ str_replace('_', ' ', $question->type) }}
                </span>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                    Difficulty: {{ $question->difficulty }}
                </span>
            </div>

            {{-- Question Content --}}
            <div class="prose prose-slate max-w-none text-slate-800 leading-relaxed text-lg font-bold mb-12">
                {!! $question->content !!}
            </div>

            {{-- Options List --}}
            @if($question->options->count() > 0)
                @if($question->type === 'menjodohkan')
                    <div class="rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-1/2">Item Kiri (Premis)</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest w-1/2">Item Kanan (Kunci Pasangan)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($question->options as $option)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="px-6 py-5 text-sm font-bold text-slate-600 leading-relaxed">
                                            {!! $option->label ?: '<span class="text-slate-300 italic font-normal">Kosong (Distraktor)</span>' !!}
                                        </td>
                                        <td class="px-6 py-5 text-sm font-bold text-indigo-600 leading-relaxed bg-indigo-50/10">
                                            {!! $option->content !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($question->options as $option)
                            <div class="flex items-start gap-4 p-5 rounded-3xl border-2 transition-all {{ $option->is_correct ? 'border-emerald-500 bg-emerald-50/30' : 'border-slate-50 bg-slate-50/10 hover:border-slate-100' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 flex items-center justify-center rounded-2xl text-sm font-black {{ $option->is_correct ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-white border border-slate-100 text-slate-400' }}">
                                        {{ $option->label }}
                                    </div>
                                </div>
                                <div class="pt-2 text-base font-bold text-slate-700 flex-grow leading-relaxed">
                                    {!! $option->content !!}
                                </div>
                                @if($option->is_correct)
                                    <div class="flex-shrink-0 pt-2 pr-2">
                                        <div class="bg-emerald-500 rounded-full p-1 shadow-sm">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        {{-- Explanation Footer --}}
        @if($question->explanation)
            <div class="bg-indigo-50/50 p-8 md:p-12 border-t border-indigo-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-indigo-100 rounded-xl">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xs font-black text-indigo-800 uppercase tracking-widest">Pembahasan Soal</h4>
                </div>
                <div class="prose prose-indigo max-w-none text-indigo-900/80 font-bold leading-relaxed">
                    {!! $question->explanation !!}
                </div>
            </div>
        @endif
    </div>

    {{-- Metadata Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-lg hover:shadow-slate-100/30 transition-all duration-300">
            <div class="p-3 bg-slate-50 rounded-2xl">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Waktu Input</p>
                <p class="text-xs font-black text-slate-800">{{ $question->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-lg hover:shadow-slate-100/30 transition-all duration-300">
            <div class="p-3 bg-emerald-50 rounded-2xl">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Bobot Nilai</p>
                <p class="text-xs font-black text-slate-800">+{{ $question->points }} Poin</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-lg hover:shadow-slate-100/30 transition-all duration-300">
            <div class="p-3 bg-rose-50 rounded-2xl">
                 <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Penalti Salah</p>
                <p class="text-xs font-black text-slate-800">{{ $question->negative_points }} Poin</p>
            </div>
        </div>
    </div>
</div>
@endsection
