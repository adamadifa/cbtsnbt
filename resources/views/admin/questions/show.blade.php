@extends('layouts.admin')

@section('page_title', 'Detail Soal Ujian')

@section('content')
<div class="space-y-5 pb-8">
    <!-- Top Action & Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/70 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.questions.index', ['subject_id' => $question->subject_id]) }}"
                class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shadow-2xs" 
                title="Kembali ke Bank Soal">
                <i class="ti ti-arrow-left text-base"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-800 tracking-tight">Detail Butir Soal</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-orange-50 text-orange-600">ID: #{{ $question->id }}</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Materi Uji: <strong class="text-slate-700">{{ $question->subject->name ?? 'Umum' }}</strong></p>
            </div>
        </div>

        <div class="flex items-center gap-2 self-end sm:self-center">
            <a href="{{ route('admin.questions.edit', $question) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl shadow-sm shadow-orange-500/20 transition-all">
                <i class="ti ti-edit text-sm"></i>
                <span>Edit Soal</span>
            </a>
        </div>
    </div>

    <!-- Main Question & Options Card Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Left: Question Body & Options (lg:col-span-8) -->
        <div class="lg:col-span-8 space-y-5">
            <!-- Question Content Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-xs">
                <!-- Badges Header -->
                <div class="flex flex-wrap items-center gap-2 pb-4 mb-5 border-b border-slate-100">
                    <!-- Type Badge -->
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider rounded-lg flex items-center gap-1">
                        <i class="ti ti-category text-xs text-slate-500"></i>
                        {{ str_replace('_', ' ', $question->type) }}
                    </span>

                    <!-- Difficulty Badge -->
                    @if($question->difficulty === 'mudah')
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-lg flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Mudah
                        </span>
                    @elseif($question->difficulty === 'sedang')
                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider rounded-lg flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Sedang
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-wider rounded-lg flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Sulit
                        </span>
                    @endif

                    @if($question->passageGroup)
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold tracking-wider rounded-lg flex items-center gap-1">
                            <i class="ti ti-files text-xs"></i>
                            {{ Str::limit($question->passageGroup->title, 24) }}
                        </span>
                    @endif
                </div>

                <!-- Reading Passage if applicable -->
                @if($question->passageGroup && $question->passageGroup->content)
                    <div class="mb-6 p-4 rounded-xl bg-slate-50/80 border border-slate-200/80 text-xs text-slate-700 leading-relaxed">
                        <div class="flex items-center gap-1.5 font-bold text-slate-800 mb-2">
                            <i class="ti ti-file-text text-orange-500"></i>
                            <span>Wacana / Stimulus: {{ $question->passageGroup->title }}</span>
                        </div>
                        <div class="prose prose-sm max-w-none text-slate-600">
                            {!! $question->passageGroup->content !!}
                        </div>
                    </div>
                @endif

                <!-- Question Text -->
                <div class="prose prose-slate max-w-none text-slate-800 leading-relaxed text-sm font-medium mb-6">
                    {!! $question->content !!}
                </div>

                <!-- Options / Jawaban Section -->
                <div class="pt-2">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="ti ti-list-check text-orange-500 text-sm"></i>
                        Pilihan Jawaban & Kunci
                    </h4>

                    @if($question->options->count() > 0)
                        @if($question->type === 'menjodohkan')
                            <div class="rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-200">
                                            <th class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-1/2">
                                                Item Kiri (Premis)</th>
                                            <th class="px-4 py-2.5 text-[10px] font-bold text-orange-600 uppercase tracking-wider w-1/2">
                                                Item Kanan (Pasangan)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($question->options as $option)
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-4 py-3 text-xs font-semibold text-slate-600 leading-relaxed">
                                                    {!! $option->label ?: '<span class="text-slate-350 italic font-normal">Kosong (Distraktor)</span>' !!}
                                                </td>
                                                <td class="px-4 py-3 text-xs font-semibold text-orange-700 leading-relaxed bg-orange-50/20">
                                                    {!! $option->content !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="space-y-2.5">
                                @foreach($question->options as $option)
                                    <div class="flex items-start gap-3 p-3.5 rounded-xl border transition-all {{ $option->is_correct ? 'border-emerald-500 bg-emerald-50/25 ring-1 ring-emerald-200' : 'border-slate-200/80 bg-white hover:border-slate-300' }}">
                                        <div class="shrink-0">
                                            <div class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-bold transition-colors {{ $option->is_correct ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 font-semibold' }}">
                                                {{ $option->label }}
                                            </div>
                                        </div>
                                        <div class="pt-1 text-xs font-semibold text-slate-700 flex-1 leading-relaxed">
                                            {!! $option->content !!}
                                        </div>
                                        @if($option->is_correct)
                                            <div class="shrink-0 pt-0.5">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 font-bold text-[10px]">
                                                    <i class="ti ti-check text-xs"></i>
                                                    Kunci Jawaban
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-center text-xs text-slate-400">
                            Soal ini tidak memiliki opsi pilihan berganda (isian atau uraian).
                        </div>
                    @endif
                </div>
            </div>

            <!-- Explanation / Pembahasan Card -->
            @if($question->explanation)
                <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-xs">
                    <div class="flex items-center gap-2 mb-3 pb-3 border-b border-slate-100">
                        <div class="w-7 h-7 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
                            <i class="ti ti-bulb text-base"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pembahasan Soal</h4>
                    </div>
                    <div class="prose prose-slate max-w-none text-slate-600 text-xs font-medium leading-relaxed">
                        {!! $question->explanation !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Question Metrics & Meta Widget (lg:col-span-4) -->
        <div class="lg:col-span-4 space-y-4">
            <!-- Card 1: Score & Points Breakdown (Dashboard Style) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-xs font-bold text-slate-700">Bobot Nilai</h3>
                        <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">Scoring</span>
                </div>

                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">+{{ $question->points }}</span>
                    <span class="text-xs font-semibold text-slate-400">Poin Benar</span>
                </div>

                <!-- Segmented bar -->
                <div class="h-2 w-full rounded-full bg-slate-100 flex overflow-hidden gap-1 mb-3">
                    <div class="bg-emerald-500 rounded-full h-full" style="width: 75%;"></div>
                    <div class="bg-rose-400 rounded-full h-full" style="width: 25%;"></div>
                </div>

                <div class="space-y-2 text-xs pt-1 border-t border-slate-100">
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Poin Benar
                        </span>
                        <span class="font-bold text-slate-800">+{{ $question->points }} Poin</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span> Penalti Salah
                        </span>
                        <span class="font-bold text-rose-600">{{ $question->negative_points }} Poin</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Question Meta Information -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-700">Informasi Metadata</h3>
                    <span class="text-[10px] text-slate-400 font-medium">Database Info</span>
                </div>

                <div class="space-y-3 text-xs font-semibold">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-normal flex items-center gap-1.5">
                            <i class="ti ti-book text-slate-400"></i> Materi Uji
                        </span>
                        <span class="text-slate-800 font-bold truncate max-w-[150px] text-right">
                            {{ $question->subject->name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-normal flex items-center gap-1.5">
                            <i class="ti ti-calendar text-slate-400"></i> Waktu Dibuat
                        </span>
                        <span class="text-slate-800 font-bold">
                            {{ $question->created_at->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-normal flex items-center gap-1.5">
                            <i class="ti ti-history text-slate-400"></i> Terakhir Diubah
                        </span>
                        <span class="text-slate-800 font-bold">
                            {{ $question->updated_at->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection