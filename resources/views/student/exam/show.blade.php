@extends('layouts.student')

{{-- Clear layout for Exam Mode --}}
@section('page_title', '')
@section('page_subtitle', '')

@section('content')
    <div x-data="examShell()" x-init="initExam()"
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
                <div class="hidden sm:block">
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider leading-none">{{ $session->title }}
                    </h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span
                            class="text-[9px] text-blue-700 font-bold uppercase tracking-wider bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100/50">SUBTEST:
                            {{ $currentSubtest ? ($currentSubtest->title ?? ($currentSubtest->subject->name ?? 'Subtest')) : 'Subtest' }}</span>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="ml-2 px-3 py-1.5 border border-slate-200 rounded-lg text-[10px] font-semibold uppercase tracking-wider text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Keluar
                </a>
            </div>

            {{-- Timer & Progress --}}
            <div class="flex items-center gap-3">
                {{-- Info Subtest Progress Steps --}}
                <div class="hidden lg:flex items-center gap-2 mr-4">
                    <template x-for="(st, index) in allSubtests" :key="st.id">
                        <div class="flex items-center">
                            <div class="w-2.5 h-2.5 rounded-full"
                                :class="st.id == currentSubtestId ? 'bg-blue-600 ring-4 ring-blue-50' : (completedSubtests.includes(st.id) ? 'bg-emerald-500' : 'bg-slate-200')">
                            </div>
                            <div x-show="index < allSubtests.length - 1" class="w-4 h-0.5"
                                :class="completedSubtests.includes(st.id) ? 'bg-emerald-200' : 'bg-slate-100'"></div>
                        </div>
                    </template>
                </div>

                <div class="px-4 py-1.5 bg-slate-900 rounded-lg flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex flex-col">
                        <span class="text-[8px] font-bold text-blue-300 uppercase tracking-wider leading-none mb-0.5">Sisa
                            Waktu</span>
                        <span class="text-xs font-bold text-white tabular-nums leading-none tracking-wider"
                            x-text="formatTime(remainingSeconds)">00:00:00</span>
                    </div>
                </div>

                @if($currentSubtest && $allSubtests->last()->id == $currentSubtest->id)
                    <button @click="finishExam()"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
                        Selesai
                    </button>
                @endif
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden">
            {{-- Left: Main Question Area --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar bg-white p-6 md:p-10">
                <div class="max-w-3xl mx-auto space-y-6 pb-24">

                    {{-- Empty State --}}
                    <template x-if="!allQuestions || allQuestions.length === 0">
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div
                                class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-4 border border-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-slate-800">Belum Ada Soal</h4>
                            <p class="text-xs text-slate-450 mt-1 max-w-xs leading-relaxed">Admin belum menambahkan daftar
                                soal pada subtest ini.</p>
                            <a href="{{ route('dashboard') }}"
                                class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider">Kembali
                                ke Dashboard</a>
                        </div>
                    </template>

                    {{-- Question Header --}}
                    <div class="flex items-start gap-4" x-show="!isTransitioning && currentQuestion">
                        <div
                            class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                            <span class="text-xs font-bold text-slate-700" x-text="currentIndex + 1">1</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="prose prose-slate max-w-none text-slate-800 text-sm font-medium leading-relaxed exam-question-content"
                                x-html="currentQuestion ? currentQuestion.content : ''"></div>
                        </div>
                    </div>

                    {{-- Options List (Dynamic based on Type) --}}
                    <div class="pl-12" x-show="!isTransitioning && currentQuestion">
                        {{-- Standard Multiple Choice (Radio) --}}
                        <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda'">
                            <div class="space-y-3">
                                <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                    <label
                                        class="relative flex items-center p-3.5 rounded-xl border border-slate-200 cursor-pointer transition-all hover:bg-slate-50/50 hover:border-blue-400 group"
                                        :class="selectedOptionId == option.id ? 'bg-blue-50/30 border-blue-500 ring-2 ring-blue-50' : 'bg-white shadow-xs'">
                                        <input type="radio" :name="'question_' + currentQuestion.id" :value="option.id"
                                            x-model="selectedOptionId" @change="saveAnswer()" class="hidden">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-4 shrink-0 transition-all text-xs font-bold"
                                            :class="selectedOptionId == option.id ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-450 group-hover:bg-blue-50 group-hover:text-blue-600'">
                                            <span x-text="getOptionLabel(index)">A</span>
                                        </div>
                                        <div class="flex-1 text-xs font-semibold text-slate-750 leading-relaxed"
                                            x-html="option.content"></div>
                                    </label>
                                </template>
                            </div>
                        </template>

                        {{-- Multiple Response (Checkbox) --}}
                        <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda_kompleks'">
                            <div class="space-y-3">
                                <p
                                    class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M9 12l2 2 4-4"></path>
                                    </svg>
                                    Pilih satu atau lebih jawaban benar
                                </p>
                                <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                    <label
                                        class="relative flex items-center p-3.5 rounded-xl border border-slate-200 cursor-pointer transition-all hover:bg-slate-50/50 hover:border-blue-400 group"
                                        :class="selectedOptionIds.map(Number).includes(option.id) ? 'bg-blue-50/30 border-blue-500 ring-2 ring-blue-50' : 'bg-white shadow-xs'">
                                        <input type="checkbox" :value="option.id" x-model="selectedOptionIds"
                                            @change="saveAnswer()"
                                            class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-100 mr-4 transition-all">
                                        <div class="flex-1 text-xs font-semibold text-slate-750 leading-relaxed"
                                            x-html="option.content"></div>
                                    </label>
                                </template>
                            </div>
                        </template>

                        {{-- Matching (Drag & Drop) --}}
                        <template x-if="currentQuestion && currentQuestion.type === 'menjodohkan'">
                            <div class="space-y-6">
                                <div class="p-4 bg-blue-50/40 border border-blue-100 rounded-xl flex items-center gap-3">
                                    <div
                                        class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shrink-0 border border-blue-200/50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4 4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wide leading-normal">
                                        Tarik kotak jawaban dari kolom kanan ke kotak kosong di samping pertanyaan yang
                                        sesuai.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                                    {{-- Left: Premises --}}
                                    <div class="space-y-3">
                                        <template x-for="(option, index) in currentQuestion.options"
                                            :key="'premise-'+option.id">
                                            <div
                                                class="flex items-center gap-3 bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs min-h-[70px]">
                                                <div class="flex-1 text-xs font-semibold text-slate-600 leading-normal"
                                                    x-html="option.label"></div>
                                                <div class="w-6 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4 text-slate-350" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                    </svg>
                                                </div>
                                                {{-- Drop Target --}}
                                                <div class="flex-1 min-h-[50px] p-1.5 rounded-lg bg-slate-50 border-2 border-dashed border-slate-200 matching-drop-target transition-all duration-200 flex items-center justify-center"
                                                    :data-premise-id="option.id" x-init="initSortable($el)">
                                                    {{-- If matched, content here --}}
                                                    <template x-if="matchingAnswers[option.id]">
                                                        <div class="w-full bg-blue-600 text-white p-2 rounded-md text-[10px] font-bold uppercase text-center shadow-xs cursor-move flex items-center justify-between gap-1.5"
                                                            :data-match-id="matchingAnswers[option.id]">
                                                            <span x-html="getMatchContent(matchingAnswers[option.id])"
                                                                class="truncate"></span>
                                                            <button @click.stop="unmatch(option.id)"
                                                                class="hover:text-red-200 transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Right: Pool of Shuffled Matches --}}
                                    <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                        <h4
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-wider text-center mb-2">
                                            Pilihan Jawaban (Tarik Ke Kiri)</h4>
                                        <div class="space-y-2 matching-pool min-h-[80px]" x-init="initSortable($el)">
                                            <template x-for="match in shuffledMatches" :key="match.id">
                                                <div x-show="!isMatched(match.id)"
                                                    class="bg-white p-3 rounded-lg border border-slate-200 shadow-xs text-[10px] font-bold uppercase text-center cursor-move hover:border-blue-600 hover:text-blue-600 transition-all active:scale-95 flex items-center justify-center"
                                                    :data-match-id="match.id">
                                                    <span x-html="match.content"></span>
                                                </div>
                                            </template>
                                            <template x-if="getAvailableMatches().length === 0">
                                                <div class="py-8 flex flex-col items-center justify-center opacity-40">
                                                    <svg class="w-6 h-6 text-slate-350 mb-1.5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <p
                                                        class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider italic">
                                                        Terpasang Semua</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Benar / Salah (Radio Row Table) --}}
                        <template x-if="currentQuestion && currentQuestion.type === 'benar_salah'">
                            <div class="space-y-4">
                                <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-xs bg-white">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50 border-b border-slate-200">
                                                <th class="px-6 py-3.5 text-xs font-bold text-slate-550 uppercase tracking-wider">Pernyataan</th>
                                                <th class="px-6 py-3.5 text-xs font-bold text-slate-550 uppercase tracking-wider text-center w-28">Benar</th>
                                                <th class="px-6 py-3.5 text-xs font-bold text-slate-550 uppercase tracking-wider text-center w-28">Salah</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-150">
                                            <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="px-6 py-4 text-xs font-semibold text-slate-700 leading-relaxed" x-html="option.content"></td>
                                                    <td class="px-6 py-4 text-center">
                                                        <label class="inline-flex items-center justify-center cursor-pointer w-full py-2">
                                                            <input type="radio" 
                                                                :name="'statement_' + option.id" 
                                                                value="benar"
                                                                x-model="matchingAnswers[option.id]"
                                                                @change="saveAnswer()"
                                                                class="w-4.5 h-4.5 text-blue-600 border-slate-300 focus:ring-blue-100 transition-all cursor-pointer">
                                                        </label>
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        <label class="inline-flex items-center justify-center cursor-pointer w-full py-2">
                                                            <input type="radio" 
                                                                :name="'statement_' + option.id" 
                                                                value="salah"
                                                                x-model="matchingAnswers[option.id]"
                                                                @change="saveAnswer()"
                                                                class="w-4.5 h-4.5 text-rose-600 border-slate-300 focus:ring-rose-100 transition-all cursor-pointer">
                                                        </label>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>

                        {{-- Essay / Text (Textarea) --}}
                        <template x-if="currentQuestion && currentQuestion.type === 'essai'">
                            <div class="space-y-3 max-w-2xl">
                                <div
                                    class="p-4 bg-white rounded-2xl border border-slate-200 focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                                    <label
                                        class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Jawaban
                                        Anda</label>
                                    <textarea x-model="essayAnswer" @input.debounce.1000ms="saveAnswer()"
                                        class="w-full min-h-[160px] border-0 focus:ring-0 text-xs font-semibold text-slate-700 leading-relaxed placeholder-slate-300 resize-none"
                                        placeholder="Tuliskan jawaban lengkap Anda di sini..."></textarea>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-[9px] font-medium tracking-wide italic">Jawaban akan tersimpan
                                        otomatis saat Anda mengetik</span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Question Navigation & Ragu-ragu Bar --}}
                    <div class="pt-8 border-t border-slate-150 flex items-center justify-between gap-4"
                        x-show="!isTransitioning && currentQuestion">
                        <button @click="prevQuestion()" :disabled="currentIndex === 0"
                            class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 disabled:opacity-40 rounded-lg text-xs font-semibold transition-all">
                            ← Sebelumnya
                        </button>

                        <button @click="toggleDoubtful()"
                            :class="doubtfulAnswers[currentQuestion.id] ? 'bg-amber-500 text-white border-transparent' : 'bg-white hover:bg-amber-50/50 border-slate-200 text-slate-600'"
                            class="px-4 py-2 border rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Ragu-Ragu
                        </button>

                        <template x-if="currentIndex < allQuestions.length - 1">
                            <button @click="nextQuestion()"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-all shadow-xs">
                                Selanjutnya →
                            </button>
                        </template>
                        <template x-if="currentIndex === allQuestions.length - 1">
                            @if($currentSubtest && $currentSubtest->id !== $allSubtests->last()->id)
                                <button @click="skipToTransition()"
                                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold transition-all shadow-xs">
                                    Lanjut Subtest →
                                </button>
                            @else
                                <button @click="finishExam()"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold transition-all shadow-xs">
                                    Selesai Ujian
                                </button>
                            @endif
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right: Navigation Sidebar --}}
            <div class="w-80 bg-white border-l border-slate-200 flex flex-col shrink-0">
                <div class="p-5 border-b border-slate-200 bg-slate-50/30">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        Navigasi Soal
                    </h3>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Hanya Subtest
                        Berjalan</p>
                </div>

                {{-- Navigation Buttons Grid --}}
                <div class="flex-1 overflow-y-auto p-5 custom-scrollbar space-y-6">
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="(q, idx) in allQuestions" :key="q.id">
                            <button @click="goToQuestion(idx)"
                                class="h-9 rounded-lg text-xs font-bold transition-all border flex items-center justify-center shadow-xs"
                                :class="getQuestionNavClass(q.id, idx)">
                                <span x-text="idx + 1"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Navigation Legend --}}
                    <div class="pt-5 border-t border-slate-100 space-y-2">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Keterangan Warna</div>
                        <div class="grid grid-cols-2 gap-2 text-[10px] font-medium text-slate-600">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3.5 h-3.5 rounded border border-blue-400 bg-blue-50"></span>
                                <span>Soal Aktif</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3.5 h-3.5 rounded border border-emerald-300 bg-emerald-50"></span>
                                <span>Sudah Dijawab</span>
                            </div>
                            <div class="flex items-center gap-1.5 col-span-2">
                                <span class="w-3.5 h-3.5 rounded border border-amber-300 bg-amber-500"></span>
                                <span>Ragu-Ragu (Kuning)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Card --}}
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Progres
                                Subtest</span>
                            <span class="text-xs font-bold text-slate-800"
                                x-text="Math.round((Object.keys(answers).length / allQuestions.length) * 100) + '%'">0%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full transition-all duration-300"
                                :style="'width: ' + ((Object.keys(answers).length / allQuestions.length) * 100) + '%'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transition Overlay (Break/Countdown) --}}
        <div x-show="isTransitioning" x-transition.opacity
            class="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-6 text-center">
            <div class="max-w-md w-full space-y-8">
                {{-- Modern Circular Progress Timer --}}
                <div
                    class="w-32 h-32 border-4 border-slate-800 rounded-full flex flex-col items-center justify-center mx-auto relative bg-slate-900 shadow-xl">
                    {{-- Rotating Elegant Progress Arc --}}
                    <div
                        class="absolute -inset-1.5 rounded-full border-2 border-t-blue-500 border-r-transparent border-b-transparent border-l-transparent animate-spin [animation-duration:3s]">
                    </div>

                    {{-- Decorative Inner Circle Track --}}
                    <div class="absolute inset-0 rounded-full border-4 border-blue-500/10"></div>

                    {{-- Countdown Text with gentle heartbeat --}}
                    <div class="z-10 flex flex-col items-center justify-center animate-[pulse_2s_infinite]">
                        <span class="text-4xl font-bold text-white tabular-nums tracking-tight"
                            x-text="transitionSeconds">10</span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Detik</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider"
                        x-text="remainingSeconds <= 0 ? 'Waktu Subtest Selesai' : 'Lanjut ke Subtest Berikutnya'"></h3>
                    <p class="text-xs text-slate-400">Lembar soal berikutnya sedang dimuat secara otomatis.</p>
                </div>

                {{-- Info Banner Box --}}
                <div class="p-4 bg-slate-900/60 rounded-xl border border-slate-800/80 text-left flex items-start gap-3.5">
                    <div
                        class="w-8 h-8 bg-blue-900/40 text-blue-400 rounded-lg flex items-center justify-center shrink-0 border border-blue-800/30">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium">
                        Jangan khawatir, semua jawaban Anda pada subtest sebelumnya telah **tersimpan dengan aman** di
                        server. Harap tetap tenang dan tunggu hitungan mundur selesai.
                    </p>
                </div>
            </div>
        </div>

        {{-- Finish Confirmation Modal --}}
        <div x-show="showFinishModal" x-transition class="fixed inset-0 z-[100] flex items-center justify-center p-6">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs" @click="showFinishModal = false"></div>
            <div class="bg-white rounded-2xl w-full max-w-sm shadow-xl overflow-hidden relative border border-slate-200">
                <div class="p-6 text-center pt-8">
                    <div
                        class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight mb-1 uppercase">Selesaikan Ujian?</h3>
                    <p class="text-[11px] font-semibold text-slate-450 uppercase tracking-wider leading-relaxed">
                        Ini adalah subtest terakhir. Pastikan semua jawaban sudah benar sebelum mengirim hasil.
                    </p>

                    <div class="grid grid-cols-2 gap-3 mt-6">
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-2">
                                Terjawab</p>
                            <p class="text-base font-bold text-slate-800 leading-none" x-text="Object.keys(answers).length">
                            </p>
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-2">Kosong
                            </p>
                            <p class="text-base font-bold text-slate-600 leading-none"
                                x-text="totalExamQuestions - Object.keys(answers).length"></p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex gap-3">
                    <button @click="showFinishModal = false"
                        class="flex-1 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-all">
                        Batal
                    </button>
                    <form action="{{ route('student.exam.finish', $examResult) }}" method="POST" class="flex-1 flex">
                        @csrf
                        <button type="submit"
                            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
                            Ya, Selesai!
                        </button>
                    </form>
                </div>
            </div>
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

        .exam-question-content th {
            background-color: #f8fafc;
            font-weight: 700;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function examShell() {
            return {
                currentIndex: 0,
                allQuestions: @json($questions),
                totalExamQuestions: {{ $totalExamQuestions }},
                answers: @json($userAnswers),
                examEndTime: new Date("{{ $metadata['subtest_end_time'] }}").getTime(),
                remainingSeconds: 0,

                // Per-question state
                selectedOptionId: null,      // MCQ
                selectedOptionIds: [],     // Multi-MCQ
                matchingAnswers: {},       // Matching {premiseId: matchId}
                essayAnswer: '',           // Essay
                shuffledMatches: [],       // Local shuffle for matching

                // Ragu-ragu state (Local Storage based)
                doubtfulAnswers: JSON.parse(localStorage.getItem('doubtful_answers_' + {{ $examResult->id }}) || '{}'),

                timerInterval: null,
                showFinishModal: false,

                // Subtest States
                currentSubtestId: {{ $currentSubtest->id ?? 'null' }},
                allSubtests: @json($allSubtests),
                completedSubtests: @json($metadata['completed_subtests'] ?? []),
                isTransitioning: false,
                transitionSeconds: {{ $nextSubtestDelay }},
                transitionInterval: null,

                initExam() {
                    this.updateRemainingTime();
                    this.timerInterval = setInterval(() => {
                        if (this.isTransitioning) return;

                        this.updateRemainingTime();
                        if (this.remainingSeconds <= 0) {
                            this.startTransition();
                        }
                    }, 1000);

                    this.updateCurrentQuestionState();

                    // Anti-Cheat Tracking
                    document.addEventListener('visibilitychange', () => {
                        if (document.visibilityState === 'hidden') {
                            this.sendViolation('tab_switch');
                        }
                    });

                    window.addEventListener('blur', () => {
                        this.sendViolation('focus_lost');
                    });
                },

                sendViolation(type) {
                    if (this.isTransitioning) return;

                    fetch(`/exam/{{ $examResult->id }}/log-violation`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ type: type })
                    })
                        .then(res => res.json())
                        .catch(err => console.error('Failed to log violation'));
                },

                updateRemainingTime() {
                    const now = new Date().getTime();
                    this.remainingSeconds = Math.max(0, Math.floor((this.examEndTime - now) / 1000));
                },

                skipToTransition() {
                    if (confirm('Yakin ingin menyimpan dan lanjut ke subtest berikutnya? Waktu subtest ini akan hangus dan kamu TIDAK BISA KEMBALI ke soal di subtest ini.')) {
                        this.startTransition();
                    }
                },

                startTransition() {
                    clearInterval(this.timerInterval);
                    this.isTransitioning = true;
                    this.transitionSeconds = {{ $nextSubtestDelay }};

                    this.transitionInterval = setInterval(() => {
                        this.transitionSeconds--;
                        if (this.transitionSeconds <= 0) {
                            clearInterval(this.transitionInterval);
                            this.moveToNextSubtest();
                        }
                    }, 1000);
                },

                moveToNextSubtest() {
                    // Post to server to update metadata & calculate next end_time
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/exam/{{ $examResult->id }}/next-subtest`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                },

                get currentQuestion() {
                    return this.allQuestions[this.currentIndex];
                },

                getOptionLabel(index) {
                    return String.fromCharCode(65 + index);
                },

                toggleDoubtful() {
                    const qId = this.currentQuestion.id;
                    if (this.doubtfulAnswers[qId]) {
                        delete this.doubtfulAnswers[qId];
                    } else {
                        this.doubtfulAnswers[qId] = true;
                    }
                    localStorage.setItem('doubtful_answers_' + {{ $examResult->id }}, JSON.stringify(this.doubtfulAnswers));
                },

                updateCurrentQuestionState() {
                    if (!this.allQuestions || this.allQuestions.length === 0) return;
                    const q = this.currentQuestion;
                    const ans = this.answers[q.id] || {};

                    this.selectedOptionId = null;
                    this.selectedOptionIds = [];
                    this.matchingAnswers = {};
                    this.essayAnswer = '';

                    if (q.type === 'pilihan_ganda') {
                        this.selectedOptionId = typeof ans === 'object' ? ans.option_id : ans;
                    } else if (q.type === 'pilihan_ganda_kompleks') {
                        this.selectedOptionIds = ans.option_ids || [];
                    } else if (q.type === 'menjodohkan' || q.type === 'benar_salah') {
                        this.matchingAnswers = ans.matching_answers || {};
                        if (q.type === 'menjodohkan') {
                            this.prepareShuffledMatches();
                        }
                    } else if (q.type === 'essai') {
                        this.essayAnswer = ans.essay_answer || '';
                    }
                },

                prepareShuffledMatches() {
                    const q = this.currentQuestion;
                    this.shuffledMatches = JSON.parse(JSON.stringify(q.options))
                        .sort(() => Math.random() - 0.5);
                },

                initSortable(el) {
                    this.$nextTick(() => {
                        new Sortable(el, {
                            group: 'matching-' + this.currentQuestion.id,
                            animation: 150,
                            onAdd: (evt) => {
                                const matchId = evt.item.dataset.matchId;
                                const premiseId = evt.to.dataset.premiseId;

                                if (premiseId) {
                                    this.matchingAnswers[premiseId] = matchId;
                                } else {
                                    Object.keys(this.matchingAnswers).forEach(pId => {
                                        if (this.matchingAnswers[pId] == matchId) {
                                            delete this.matchingAnswers[pId];
                                        }
                                    });
                                }

                                this.saveAnswer();
                                evt.item.remove();
                            }
                        });
                    });
                },

                isMatched(matchId) {
                    return Object.values(this.matchingAnswers).includes(matchId.toString());
                },

                getAvailableMatches() {
                    return this.shuffledMatches.filter(m => !this.isMatched(m.id));
                },

                getMatchContent(matchId) {
                    const match = this.currentQuestion.options.find(o => o.id == matchId);
                    return match ? match.content : '';
                },

                unmatch(premiseId) {
                    delete this.matchingAnswers[premiseId];
                    this.saveAnswer();
                },

                saveAnswer() {
                    if (this.isTransitioning) return;

                    const q = this.currentQuestion;
                    let payload = { question_id: q.id };

                    if (q.type === 'pilihan_ganda') {
                        payload.option_id = this.selectedOptionId;
                    } else if (q.type === 'pilihan_ganda_kompleks') {
                        payload.option_ids = this.selectedOptionIds;
                    } else if (q.type === 'menjodohkan' || q.type === 'benar_salah') {
                        payload.matching_answers = this.matchingAnswers;
                    } else if (q.type === 'essai') {
                        payload.essay_answer = this.essayAnswer;
                    }

                    this.answers[q.id] = payload;

                    fetch(`/exam/{{ $examResult->id }}/save-answer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) console.error('Failed to save answer');
                        })
                        .catch(err => console.error('Network error during save'));
                },

                goToQuestion(index) {
                    this.currentIndex = index;
                    this.updateCurrentQuestionState();
                },

                nextQuestion() {
                    if (this.currentIndex < this.allQuestions.length - 1) {
                        this.currentIndex++;
                        this.updateCurrentQuestionState();
                    }
                },

                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                        this.updateCurrentQuestionState();
                    }
                },

                finishExam() {
                    this.showFinishModal = true;
                },

                getQuestionNavClass(qId, idx) {
                    const isCurrent = this.currentIndex === idx;
                    const ans = this.answers[qId];
                    const isDoubtful = !!this.doubtfulAnswers[qId];

                    let isAnswered = false;
                    if (ans) {
                        const q = this.allQuestions[idx];
                        if (q.type === 'pilihan_ganda') isAnswered = !!ans.option_id || !!ans;
                        else if (q.type === 'pilihan_ganda_kompleks') isAnswered = ans.option_ids && ans.option_ids.length > 0;
                        else if (q.type === 'menjodohkan' || q.type === 'benar_salah') isAnswered = ans.matching_answers && Object.keys(ans.matching_answers).length > 0;
                        else if (q.type === 'essai') isAnswered = ans.essay_answer && ans.essay_answer.trim().length > 0;
                    }

                    if (isCurrent && isDoubtful) {
                        return 'bg-amber-500 text-white border-amber-600 shadow-sm ring-2 ring-amber-100';
                    }
                    if (isCurrent) {
                        return 'bg-blue-600 text-white border-blue-600 shadow-sm ring-2 ring-blue-50';
                    }
                    if (isDoubtful) {
                        return 'bg-amber-400 text-white border-amber-500 shadow-xs font-bold';
                    }
                    if (isAnswered) {
                        return 'bg-emerald-50 text-emerald-700 border-emerald-200 shadow-xs font-bold';
                    }
                    return 'bg-white text-slate-400 border-slate-200 hover:bg-slate-50';
                },

                formatTime(seconds) {
                    const h = Math.floor(seconds / 3600);
                    const m = Math.floor((seconds % 3600) / 60);
                    const s = seconds % 60;

                    if (h > 0) {
                        return [h, m, s].map(v => v < 10 ? '0' + v : v).join(':');
                    }
                    return [m, s].map(v => v < 10 ? '0' + v : v).join(':');
                }
            }
        }
    </script>
@endpush