@extends('layouts.admin')

@section('page_title', 'Pratinjau Soal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Navigation & Action Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.questions.index', ['subject_id' => $question->subject_id]) }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Bank Soal
        </a>
        <a href="{{ route('admin.questions.edit', $question) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-xl border border-amber-200 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Soal
        </a>
    </div>

    {{-- Question Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/70 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100">
            {{-- Category Badges --}}
            <div class="flex flex-wrap items-center gap-2 mb-6">
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-indigo-100/50">
                    {{ str_replace('_', ' ', $question->type) }}
                </span>
                
                @if($question->difficulty === 'mudah')
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-emerald-100/50">
                        Kesulitan: Mudah
                    </span>
                @elseif($question->difficulty === 'sedang')
                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-amber-100/50">
                        Kesulitan: Sedang
                    </span>
                @else
                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-rose-100/50">
                        Kesulitan: Sulit
                    </span>
                @endif
            </div>

            {{-- Question Content --}}
            <div class="prose prose-slate max-w-none text-slate-800 leading-relaxed text-base font-medium mb-8">
                {!! $question->content !!}
            </div>

            {{-- Options List --}}
            @if($question->options->count() > 0)
                @if($question->type === 'menjodohkan')
                    <div class="rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-1/2">Item Kiri (Premis)</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-indigo-700 uppercase tracking-wider w-1/2">Item Kanan (Kunci Pasangan)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($question->options as $option)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-5 py-4 text-xs font-semibold text-slate-600 leading-relaxed">
                                            {!! $option->label ?: '<span class="text-slate-350 italic font-normal">Kosong (Distraktor)</span>' !!}
                                        </td>
                                        <td class="px-5 py-4 text-xs font-semibold text-indigo-600 leading-relaxed bg-indigo-50/10">
                                            {!! $option->content !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($question->options as $option)
                            <div class="flex items-start gap-3.5 p-4 rounded-xl border transition-all duration-200 {{ $option->is_correct ? 'border-emerald-500 bg-emerald-50/20' : 'border-slate-200/80 bg-white hover:border-slate-300' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors {{ $option->is_correct ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-50 border border-slate-200 text-slate-500' }}">
                                        {{ $option->label }}
                                    </div>
                                </div>
                                <div class="pt-1.5 text-sm font-semibold text-slate-700 flex-grow leading-relaxed">
                                    {!! $option->content !!}
                                </div>
                                @if($option->is_correct)
                                    <div class="flex-shrink-0 pt-1">
                                        <div class="bg-emerald-600 rounded-full p-1 shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        {{-- Explanation Section --}}
        @if($question->explanation)
            <div class="bg-slate-50/60 p-6 md:p-8 border-t border-slate-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pembahasan Soal</h4>
                </div>
                <div class="prose prose-indigo max-w-none text-slate-600 text-sm font-medium leading-relaxed">
                    {!! $question->explanation !!}
                </div>
            </div>
        @endif
    </div>

    {{-- Metadata Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-sm flex items-center gap-4 hover:border-slate-350 transition-colors duration-200">
            <div class="p-3 bg-slate-50 text-slate-400 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Waktu Input</p>
                <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $question->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-sm flex items-center gap-4 hover:border-slate-350 transition-colors duration-200">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Bobot Nilai</p>
                <p class="text-xs font-bold text-slate-800 mt-0.5">+{{ $question->points }} Poin</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-sm flex items-center gap-4 hover:border-slate-350 transition-colors duration-200">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                 </svg>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Penalti Salah</p>
                <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $question->negative_points }} Poin</p>
            </div>
        </div>
    </div>
</div>
@endsection
