@extends('layouts.admin')

{{-- Clear layout for Review Mode --}}
@section('page_title', '')
@section('page_subtitle', '')

@section('content')
    <div x-data="reviewShell()" x-init="init()"
        class="fixed inset-0 bg-slate-50 z-[50] flex flex-col overflow-hidden select-none">
        {{-- Top Bar Navigation --}}
        <div
            class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 relative z-[60]">
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider leading-none">Pembahasan Jawaban: {{ $examResult->user->name }}</h2>
                    <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider mt-1">{{ $session->title }}
                    </p>
                </div>
                <a href="{{ route('admin.exam-sessions.student-results', [$examSession, $examResult]) }}"
                    class="ml-2 px-3 py-1.5 border border-slate-200 rounded-lg text-[10px] font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Hasil
                </a>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-4 text-[9px] font-bold uppercase tracking-wider">
                    <div class="flex items-center gap-1.5 text-emerald-600">
                        <span class="w-2.5 h-2.5 rounded bg-emerald-500"></span>
                        Benar
                    </div>
                    <div class="flex items-center gap-1.5 text-rose-600">
                        <span class="w-2.5 h-2.5 rounded bg-rose-500"></span>
                        Salah
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-400">
                        <span class="w-2.5 h-2.5 rounded bg-slate-300"></span>
                        Kosong
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Workspace Grid --}}
        <div class="flex-1 flex overflow-hidden">
            {{-- Empty State Placeholder if no questions --}}
            <template x-if="!allQuestions || allQuestions.length === 0">
                <div class="flex-1 flex flex-col items-center justify-center text-center bg-white p-8">
                    <div
                        class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-4 border border-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-800">Tidak Ada Data Soal</h4>
                    <p class="text-xs text-slate-450 mt-1 max-w-xs leading-relaxed">Belum ada soal tryout yang dikerjakan pada subtest ini.</p>
                    <a href="{{ route('admin.exam-sessions.student-results', [$examSession, $examResult]) }}"
                        class="mt-6 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">Kembali ke Hasil</a>
                </div>
            </template>

            <template x-if="allQuestions && allQuestions.length > 0">
                <div class="flex-1 flex overflow-hidden">
                    {{-- Left: Review Area --}}
                    <div class="flex-1 overflow-y-auto custom-scrollbar bg-white p-6 md:p-10 relative">
                        <div class="max-w-3xl mx-auto space-y-8 pb-24">
                            {{-- Question Header --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[9px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded tracking-wide uppercase border border-blue-100/50"
                                        x-text="currentQuestion ? currentQuestion.subtest_title : ''"></span>
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase">Soal #<span
                                            x-text="currentIndex + 1"></span></span>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center shrink-0">
                                        <span class="text-xs font-bold text-slate-700" x-text="currentIndex + 1">1</span>
                                    </div>
                                    <div class="flex-1 prose prose-slate max-w-none text-slate-800 text-sm font-medium leading-relaxed exam-question-content"
                                        x-html="currentQuestion ? currentQuestion.content : ''"></div>
                                </div>
                            </div>

                            {{-- Options List (Dynamic based on Type) --}}
                            <div class="pl-12" x-show="currentQuestion">
                                {{-- Standard Multiple Choice --}}
                                <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda'">
                                    <div class="space-y-3">
                                        <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                            <div class="relative flex items-center p-3.5 rounded-xl border transition-all"
                                                :class="getOptionClass(option)">

                                                {{-- Correct/User Badge --}}
                                                <div class="absolute -right-2 -top-2 flex gap-1 z-10">
                                                    <span x-show="option.is_correct"
                                                        class="px-2 py-0.5 bg-emerald-600 text-white text-[8px] font-bold uppercase rounded-md shadow-xs">Kunci Jawaban</span>
                                                    <span x-show="userChoiceId == option.id"
                                                        class="px-2 py-0.5 text-white text-[8px] font-bold uppercase rounded-md shadow-xs"
                                                        :class="option.is_correct ? 'bg-blue-600' : 'bg-rose-600'">Jawaban Peserta</span>
                                                </div>

                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-4 shrink-0 text-xs font-bold"
                                                    :class="getOptionLetterClass(option)">
                                                    <span x-text="getOptionLabel(index + 1)">A</span>
                                                </div>
                                                <div class="flex-1 text-xs font-semibold leading-relaxed"
                                                    :class="option.is_correct ? 'text-emerald-900' : (userChoiceId == option.id ? 'text-rose-900' : 'text-slate-700')"
                                                    x-html="option.content"></div>

                                                {{-- Icon Indicator --}}
                                                <div class="ml-4 shrink-0">
                                                    <template x-if="option.is_correct">
                                                        <svg class="w-4 h-4 text-emerald-650" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </template>
                                                    <template x-if="!option.is_correct && userChoiceId == option.id">
                                                        <svg class="w-4 h-4 text-rose-650" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                {{-- Multiple Response (Checkbox) --}}
                                <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda_kompleks'">
                                    <div class="space-y-3">
                                        <template x-for="(option, index) in currentQuestion.options" :key="'mc-'+option.id">
                                            <div class="relative flex items-center p-3.5 rounded-xl border transition-all bg-white border-slate-200"
                                                :class="getComplexOptionClass(option)">
                                                <div class="flex-1 text-xs font-semibold text-slate-700 leading-relaxed"
                                                    x-html="option.content"></div>
                                                <div class="flex items-center gap-2">
                                                    <template x-if="option.is_correct">
                                                        <span
                                                            class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[8px] font-bold uppercase rounded">Kunci</span>
                                                    </template>
                                                    <template x-if="selectedOptionIds.map(Number).includes(option.id)">
                                                        <span
                                                            class="px-2 py-0.5 text-white text-[8px] font-bold uppercase rounded"
                                                            :class="option.is_correct ? 'bg-blue-600' : 'bg-rose-600'">Pilihan Peserta</span>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                {{-- Menjodohkan (Comparison Table) --}}
                                <template x-if="currentQuestion && currentQuestion.type === 'menjodohkan'">
                                    <div class="overflow-hidden border border-slate-200 rounded-xl bg-white shadow-xs">
                                        <table class="w-full text-left border-collapse">
                                            <thead>
                                                <tr class="bg-slate-50 border-b border-slate-200">
                                                    <th class="px-5 py-3 text-[9px] font-bold text-slate-500 uppercase tracking-wider">Item Pertanyaan</th>
                                                    <th class="px-5 py-3 text-[9px] font-bold text-slate-500 uppercase tracking-wider">Pilihan Peserta</th>
                                                    <th class="px-5 py-3 text-[9px] font-bold text-slate-500 uppercase tracking-wider">Kunci Jawaban</th>
                                                    <th class="px-5 py-3"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-150">
                                                <template x-for="option in currentQuestion.options"
                                                    :key="'match-row-'+option.id">
                                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                                        <td class="px-5 py-3 text-xs font-semibold text-slate-650"
                                                            x-html="option.label"></td>
                                                        <td class="px-5 py-3">
                                                            <div x-show="matchingAnswers[option.id]"
                                                                class="px-2.5 py-1 rounded text-[9px] font-bold uppercase inline-block border"
                                                                :class="matchingAnswers[option.id] == option.id ? 'bg-emerald-50 text-emerald-750 border-emerald-200' : 'bg-rose-50 text-rose-750 border-rose-200'">
                                                                <span
                                                                    x-html="getMatchContent(matchingAnswers[option.id])"></span>
                                                            </div>
                                                            <div x-show="!matchingAnswers[option.id]"
                                                                class="text-[9px] text-slate-400 italic">Tidak Terisi</div>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <div
                                                                class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded text-[9px] font-bold uppercase inline-block">
                                                                <span x-html="option.content"></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <svg x-show="matchingAnswers[option.id] == option.id"
                                                                class="w-4.5 h-4.5 text-emerald-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            <svg x-show="matchingAnswers[option.id] && matchingAnswers[option.id] != option.id"
                                                                class="w-4.5 h-4.5 text-rose-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>

                                {{-- Benar / Salah (Comparison Table) --}}
                                <template x-if="currentQuestion && currentQuestion.type === 'benar_salah'">
                                    <div class="overflow-hidden border border-slate-200 rounded-xl bg-white shadow-xs">
                                        <table class="w-full text-left border-collapse">
                                            <thead>
                                                <tr class="bg-slate-50 border-b border-slate-200">
                                                    <th class="px-5 py-3 text-[9px] font-bold text-slate-555 uppercase tracking-wider">Pernyataan</th>
                                                    <th class="px-5 py-3 text-[9px] font-bold text-slate-555 uppercase tracking-wider">Pilihan Peserta</th>
                                                    <th class="px-5 py-3 text-[9px] font-bold text-slate-555 uppercase tracking-wider">Kunci Jawaban</th>
                                                    <th class="px-5 py-3 w-16"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-150">
                                                <template x-for="option in currentQuestion.options" :key="'bs-row-'+option.id">
                                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                                        <td class="px-5 py-4 text-xs font-semibold text-slate-700 leading-relaxed" x-html="option.content"></td>
                                                        <td class="px-5 py-4">
                                                            <div x-show="matchingAnswers[option.id]"
                                                                class="px-2.5 py-1 rounded text-[9px] font-bold uppercase inline-block border"
                                                                :class="matchingAnswers[option.id] == (option.is_correct ? 'benar' : 'salah') ? 'bg-emerald-50 text-emerald-750 border-emerald-200' : 'bg-rose-50 text-rose-750 border-rose-200'">
                                                                <span x-text="matchingAnswers[option.id]"></span>
                                                            </div>
                                                            <div x-show="!matchingAnswers[option.id]"
                                                                class="text-[9px] text-slate-400 italic">Tidak Terisi</div>
                                                        </td>
                                                        <td class="px-5 py-4">
                                                            <div class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded text-[9px] font-bold uppercase inline-block">
                                                                <span x-text="option.is_correct ? 'Benar' : 'Salah'"></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-5 py-4 text-center">
                                                            <svg x-show="matchingAnswers[option.id] == (option.is_correct ? 'benar' : 'salah')"
                                                                class="w-4.5 h-4.5 text-emerald-500 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <svg x-show="matchingAnswers[option.id] && matchingAnswers[option.id] != (option.is_correct ? 'benar' : 'salah')"
                                                                class="w-4.5 h-4.5 text-rose-500 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>

                                {{-- Essay --}}
                                <template x-if="currentQuestion && currentQuestion.type === 'essai'">
                                    <div class="space-y-3">
                                        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Jawaban Peserta</label>
                                            <div class="text-xs font-semibold text-slate-700 leading-relaxed whitespace-pre-wrap"
                                                x-text="essayAnswer || 'Tidak ada jawaban.'"></div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Isian Singkat --}}
                                <template x-if="currentQuestion && currentQuestion.type === 'isian_singkat'">
                                    <div class="space-y-4">
                                        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Jawaban Peserta</label>
                                            <div class="text-xs font-semibold text-slate-750 leading-relaxed" x-text="essayAnswer || 'Tidak ada jawaban.'"></div>
                                        </div>
                                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl shadow-xs">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Kunci Jawaban yang Benar</label>
                                            <div class="space-y-1">
                                                <template x-for="opt in currentQuestion.options" :key="opt.id">
                                                    <div class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <span x-text="opt.content"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Explanation Box --}}
                            <div class="pl-12 scroll-mt-20" id="explanation-box" x-show="currentQuestion">
                                <div class="p-6 bg-slate-900 rounded-xl text-white space-y-3.5 shadow-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-blue-300">Pembahasan Soal</h4>
                                    </div>
                                    <div class="prose prose-invert prose-sm max-w-none text-slate-300 text-xs font-medium leading-relaxed"
                                        x-show="currentQuestion && currentQuestion.explanation"
                                        x-html="currentQuestion ? currentQuestion.explanation : ''"></div>
                                    <div class="text-xs font-semibold text-slate-500 italic"
                                        x-show="currentQuestion && !currentQuestion.explanation">Belum ada pembahasan tertulis untuk soal ini.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Navigation Sidebar --}}
                    <div class="w-80 bg-white border-l border-slate-200 flex flex-col shrink-0 no-print">
                        <div class="p-5 border-b border-slate-200 bg-slate-50/30">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                Daftar Soal
                            </h3>
                        </div>

                        <div class="flex-1 overflow-y-auto p-5 custom-scrollbar space-y-6">
                            @foreach($subtests as $subtest)
                                <div class="space-y-2">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider pl-1">
                                        {{ $subtest->title ?: ($subtest->subject->name ?? 'Subtest') }}</p>
                                    <div class="grid grid-cols-5 gap-2">
                                        <template
                                            x-for="(q, idx) in allQuestions.filter(qu => qu.subtest_id == {{ $subtest->id }})"
                                            :key="q.id">
                                            <button @click="goToQuestionById(q.id)"
                                                class="h-9 rounded-lg text-xs font-bold transition-all border flex items-center justify-center shadow-xs"
                                                :class="getNavClass(q)">
                                                <span x-text="allQuestions.findIndex(qu => qu.id == q.id) + 1"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Info --}}
                        <div class="p-5 bg-slate-50 border-t border-slate-200 no-print">
                            <div class="grid grid-cols-2 gap-3">
                                <button @click="prevQuestion()" :disabled="currentIndex === 0"
                                    class="py-2.5 bg-white border border-slate-200 text-xs font-bold uppercase tracking-wider rounded-lg text-slate-500 enabled:hover:bg-slate-50 hover:text-slate-700 transition-all disabled:opacity-40 shadow-xs">Sebelumnya</button>
                                <button @click="nextQuestion()" :disabled="currentIndex === allQuestions.length - 1"
                                    class="py-2.5 bg-white border border-slate-200 text-xs font-bold uppercase tracking-wider rounded-lg text-slate-500 enabled:hover:bg-slate-50 hover:text-slate-700 transition-all disabled:opacity-40 shadow-xs">Selanjutnya</button>
                            </div>
                            <button onclick="window.print()"
                                class="w-full mt-3 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
                                Cetak Pembahasan (PDF)
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <style>
        .exam-question-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        .exam-question-content table {
            border-collapse: collapse;
            margin: 1rem 0;
            width: 100%;
        }

        .exam-question-content td,
        .exam-question-content th {
            border: 1px solid #e2e8f0;
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .fixed {
                position: relative !important;
                overflow: visible !important;
                display: block !important;
            }

            .flex-1 {
                overflow: visible !important;
            }

            .bg-slate-50 {
                background: white !important;
            }

            .h-16 {
                display: none !important;
            }

            .md\:p-12 {
                padding: 0 !important;
            }

            #explanation-box {
                box-shadow: none !important;
                background: #f8fafc !important;
                page-break-inside: avoid;
            }

            .exam-question-content {
                color: black !important;
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        function reviewShell() {
            return {
                currentIndex: 0,
                allQuestions: @json($allQuestions),
                userAnswers: @json($userAnswers),
                userChoiceId: null,
                selectedOptionIds: [],
                matchingAnswers: {},
                essayAnswer: '',

                init() {
                    this.updateCurrentState();
                },

                get currentQuestion() {
                    if (!this.allQuestions || this.allQuestions.length === 0) return null;
                    return this.allQuestions[this.currentIndex];
                },

                getOptionLabel(num) {
                    return String.fromCharCode(64 + num);
                },

                goToQuestionById(id) {
                    const idx = this.allQuestions.findIndex(q => q.id === id);
                    if (idx !== -1) {
                        this.currentIndex = idx;
                        this.updateCurrentState();
                    }
                },

                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                        this.updateCurrentState();
                    }
                },

                nextQuestion() {
                    if (this.currentIndex < this.allQuestions.length - 1) {
                        this.currentIndex++;
                        this.updateCurrentState();
                    }
                },

                updateCurrentState() {
                    const q = this.currentQuestion;
                    if (!q) return;

                    const ans = this.userAnswers[q.id] || {};

                    this.userChoiceId = null;
                    this.selectedOptionIds = [];
                    this.matchingAnswers = {};
                    this.essayAnswer = '';

                    if (q.type === 'pilihan_ganda') {
                        this.userChoiceId = ans.option_id || null;
                    } else if (q.type === 'pilihan_ganda_kompleks') {
                        this.selectedOptionIds = ans.option_ids || [];
                    } else if (q.type === 'menjodohkan' || q.type === 'benar_salah') {
                        this.matchingAnswers = ans.matching_answers || {};
                    } else if (q.type === 'essai' || q.type === 'isian_singkat') {
                        this.essayAnswer = ans.essay_answer || '';
                    }
                },

                getMatchContent(matchId) {
                    if (!this.currentQuestion) return '';
                    const opt = this.currentQuestion.options.find(o => o.id == matchId);
                    return opt ? opt.content : '';
                },

                getNavClass(q) {
                    const ans = this.userAnswers[q.id];
                    if (!this.currentQuestion) return 'bg-white text-slate-400 border-slate-200';
                    const isCurrent = this.allQuestions[this.currentIndex].id === q.id;

                    let isCorrect = false;
                    let isAnswered = false;

                    if (ans) {
                        isAnswered = true;
                        if (q.type === 'pilihan_ganda') {
                            const correctOpt = q.options.find(o => o.is_correct);
                            isCorrect = correctOpt && ans.option_id == correctOpt.id;
                        } else if (q.type === 'pilihan_ganda_kompleks') {
                            const correctIds = q.options.filter(o => o.is_correct).map(o => o.id);
                            const userIds = (ans.option_ids || []).map(Number);
                            isCorrect = correctIds.length === userIds.length && correctIds.every(id => userIds.includes(id));
                        } else if (q.type === 'menjodohkan' || q.type === 'benar_salah') {
                            const matches = ans.matching_answers || {};
                            const totalOptions = q.options.length;
                            let correctCount = 0;
                            q.options.forEach(o => {
                                const expected = q.type === 'benar_salah' ? (o.is_correct ? 'benar' : 'salah') : o.id;
                                if (matches[o.id] == expected) correctCount++;
                            });
                            isCorrect = correctCount === totalOptions;
                        } else if (q.type === 'isian_singkat') {
                            const submittedVal = (ans.essay_answer || '').trim().toLowerCase();
                            isCorrect = q.options.some(o => o.content.trim().toLowerCase() === submittedVal);
                        } else if (q.type === 'essai') {
                            isCorrect = true;
                        }
                    }

                    if (isCurrent) return 'bg-blue-600 text-white border-blue-600 ring-2 ring-blue-50';
                    if (!isAnswered) return 'bg-white text-slate-400 border-slate-200';
                    if (isCorrect) return 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    return 'bg-rose-50 text-rose-700 border-rose-200';
                },

                getOptionClass(option) {
                    const isUserChoice = this.userChoiceId == option.id;
                    if (option.is_correct) {
                        return 'bg-emerald-50 border-emerald-300 ring-1 ring-emerald-100';
                    }
                    if (isUserChoice && !option.is_correct) {
                        return 'bg-rose-50 border-rose-300 ring-1 ring-rose-100';
                    }
                    return 'bg-white border-slate-200 hover:bg-slate-50';
                },

                getOptionLetterClass(option) {
                    const isUserChoice = this.userChoiceId == option.id;
                    if (option.is_correct) {
                        return 'bg-emerald-600 text-white shadow-xs';
                    }
                    if (isUserChoice && !option.is_correct) {
                        return 'bg-rose-600 text-white shadow-xs';
                    }
                    return 'bg-slate-50 text-slate-500';
                },

                getComplexOptionClass(option) {
                    const isUserChoice = this.selectedOptionIds.includes(option.id);
                    if (option.is_correct) {
                        return 'bg-emerald-50/50 border-emerald-300 ring-1 ring-emerald-100';
                    }
                    if (isUserChoice && !option.is_correct) {
                        return 'bg-rose-50/50 border-rose-300 ring-1 ring-rose-100';
                    }
                    return 'bg-white border-slate-200 hover:bg-slate-50';
                }
            }
        }
    </script>
@endpush
