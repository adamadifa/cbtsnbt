@extends('layouts.student-mobile')

@section('content')
    <div x-data="examShell()" x-init="initExam()" class="space-y-6 pb-28">
        {{-- Transition Timer Panel (If next subtest transition) --}}
        <div x-show="isTransitioning"
            class="fixed inset-0 z-[120] bg-slate-900/95 flex flex-col items-center justify-center text-center p-6 text-white"
            style="display: none;">
            <div
                class="w-16 h-16 bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded-full flex items-center justify-center mb-6 animate-pulse">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-extrabold tracking-tight">Waktu Subtest Berakhir!</h3>
            <p class="text-xs text-slate-400 mt-2 max-w-xs leading-relaxed">Jawaban Anda telah disimpan. Sistem akan
                mengarahkan Anda ke subtest berikutnya secara otomatis dalam:</p>
            <span class="text-5xl font-black text-amber-500 mt-6 block tabular-nums" x-text="transitionSeconds">10</span>
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-8">Mohon tidak menutup halaman ini
            </p>
        </div>

        {{-- Top Bar Timer & Subtest Info --}}
        <div class="bg-slate-900 rounded-2xl p-4 text-white shadow-md flex items-center justify-between gap-4">
            <div class="min-w-0">
                <span class="text-[8px] font-black text-indigo-400 uppercase tracking-wider block">Subtest Ujian</span>
                <span class="text-xs font-bold text-slate-100 truncate block mt-0.5"
                    x-text="currentQuestion ? currentQuestion.subtest_title : '{{ $currentSubtest->title ?? 'Subtest' }}'"></span>
            </div>

            <div class="px-3.5 py-1.5 bg-white/10 rounded-xl flex items-center gap-2 border border-white/5">
                <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-black text-white tabular-nums tracking-wider"
                    x-text="formatTime(remainingSeconds)">00:00</span>
            </div>
        </div>

        {{-- Question Section --}}
        <div class="space-y-4" x-show="!isTransitioning && currentQuestion">
            {{-- Question Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <span
                        class="px-2 py-0.5 bg-indigo-50 border border-indigo-150 rounded text-[9px] font-extrabold text-indigo-600 uppercase tracking-wider">
                        Siswa Portal
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Soal #<span
                            x-text="currentIndex + 1"></span> dari <span x-text="allQuestions.length"></span></span>
                </div>

                <div class="flex items-start gap-3 border-t border-slate-50 pt-4">
                    <div
                        class="w-7 h-7 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center shrink-0">
                        <span class="text-xs font-black text-slate-700" x-text="currentIndex + 1">1</span>
                    </div>
                    <div class="flex-1 prose prose-slate max-w-none text-slate-800 text-xs font-semibold leading-relaxed exam-question-content"
                        x-html="currentQuestion ? currentQuestion.content : ''"></div>
                </div>
            </div>

            {{-- Answer Options List --}}
            <div class="space-y-3">
                {{-- Standard Multiple Choice --}}
                <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda'">
                    <div class="space-y-3">
                        <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 cursor-pointer transition-all active:bg-slate-50 group bg-white shadow-xs"
                                :class="selectedOptionId == option.id ? 'border-indigo-600 ring-2 ring-indigo-50 bg-indigo-50/10' : 'bg-white'">
                                <input type="radio" :name="'question_' + currentQuestion.id" :value="option.id"
                                    x-model="selectedOptionId" @change="saveAnswer()" class="hidden">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-3 shrink-0 transition-all text-xs font-extrabold"
                                    :class="selectedOptionId == option.id ? 'bg-indigo-600 text-white' : 'bg-slate-50 text-slate-450'">
                                    <span x-text="getOptionLabel(index)">A</span>
                                </div>
                                <div class="flex-1 text-xs font-semibold text-slate-700 leading-relaxed"
                                    x-html="option.content"></div>
                            </label>
                        </template>
                    </div>
                </template>

                {{-- Multiple Response (Complex MCQ) --}}
                <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda_kompleks'">
                    <div class="space-y-3">
                        <p
                            class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest pl-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4">
                                </path>
                            </svg>
                            Pilih satu atau lebih jawaban benar
                        </p>
                        <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                            <label
                                class="relative flex items-center p-4 rounded-xl border border-slate-200 cursor-pointer transition-all active:bg-slate-50 bg-white shadow-xs"
                                :class="selectedOptionIds.map(Number).includes(option.id) ? 'border-indigo-600 ring-2 ring-indigo-50 bg-indigo-50/10' : 'bg-white'">
                                <input type="checkbox" :value="option.id" x-model="selectedOptionIds" @change="saveAnswer()"
                                    class="w-4.5 h-4.5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-100 mr-3 transition-all">
                                <div class="flex-1 text-xs font-semibold text-slate-700 leading-relaxed"
                                    x-html="option.content"></div>
                            </label>
                        </template>
                    </div>
                </template>

                {{-- Matching (Drag & Drop) --}}
                <template x-if="currentQuestion && currentQuestion.type === 'menjodohkan'">
                    <div class="space-y-4">
                        <div class="p-3.5 bg-indigo-50/50 border border-indigo-100/50 rounded-xl flex items-start gap-2.5">
                            <svg class="w-4.5 h-4.5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4 4"></path>
                            </svg>
                            <p class="text-[10px] font-semibold text-indigo-850 leading-normal">
                                Tarik pilihan jawaban (bawah) ke area bergaris putus-putus (kiri/kanan pertanyaan).
                            </p>
                        </div>

                        <div class="space-y-3">
                            {{-- Premises --}}
                            <template x-for="(option, index) in currentQuestion.options" :key="'premise-'+option.id">
                                <div
                                    class="flex items-center gap-3 bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs min-h-[60px]">
                                    <div class="flex-1 text-xs font-semibold text-slate-650 leading-normal"
                                        x-html="option.label"></div>
                                    <div class="w-4 flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5 text-slate-350" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </div>
                                    {{-- Drop Target --}}
                                    <div class="flex-1 min-h-[44px] p-1.5 rounded-lg bg-slate-50 border-2 border-dashed border-slate-200 matching-drop-target transition-all duration-200 flex items-center justify-center"
                                        :data-premise-id="option.id" x-init="initSortable($el)">
                                        <template x-if="matchingAnswers[option.id]">
                                            <div class="w-full bg-indigo-600 text-white py-1.5 px-2.5 rounded-md text-[10px] font-bold uppercase text-center shadow-xs cursor-move flex items-center justify-between gap-1"
                                                :data-match-id="matchingAnswers[option.id]">
                                                <span x-html="getMatchContent(matchingAnswers[option.id])"
                                                    class="truncate"></span>
                                                <button @click.stop="unmatch(option.id)"
                                                    class="text-white/80 hover:text-white">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
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

                        {{-- Match Pool --}}
                        <div class="space-y-2 p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                            <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center mb-1">
                                Pilihan Jawaban</h4>
                            <div class="grid grid-cols-2 gap-2 matching-pool min-h-[50px]" x-init="initSortable($el)">
                                <template x-for="match in shuffledMatches" :key="match.id">
                                    <div x-show="!isMatched(match.id)"
                                        class="bg-white p-2 rounded-lg border border-slate-200 shadow-xs text-[10px] font-bold uppercase text-center cursor-move hover:border-indigo-500 transition-all flex items-center justify-center min-h-[36px]"
                                        :data-match-id="match.id">
                                        <span x-html="match.content" class="truncate"></span>
                                    </div>
                                </template>
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
                                        <th class="px-4 py-3 text-xs font-bold text-slate-550 uppercase tracking-wider">Pernyataan</th>
                                        <th class="px-2 py-3 text-xs font-bold text-slate-550 uppercase tracking-wider text-center w-20">Benar</th>
                                        <th class="px-2 py-3 text-xs font-bold text-slate-550 uppercase tracking-wider text-center w-20">Salah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-150">
                                    <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-4 py-3 text-[11px] font-semibold text-slate-700 leading-relaxed" x-html="option.content"></td>
                                            <td class="px-2 py-3 text-center">
                                                <label class="inline-flex items-center justify-center cursor-pointer w-full py-1">
                                                    <input type="radio" 
                                                        :name="'statement_' + option.id" 
                                                        value="benar"
                                                        x-model="matchingAnswers[option.id]"
                                                        @change="saveAnswer()"
                                                        class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-100 transition-all cursor-pointer">
                                                </label>
                                            </td>
                                            <td class="px-2 py-3 text-center">
                                                <label class="inline-flex items-center justify-center cursor-pointer w-full py-1">
                                                    <input type="radio" 
                                                        :name="'statement_' + option.id" 
                                                        value="salah"
                                                        x-model="matchingAnswers[option.id]"
                                                        @change="saveAnswer()"
                                                        class="w-4 h-4 text-rose-600 border-slate-300 focus:ring-rose-100 transition-all cursor-pointer">
                                                </label>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>

                {{-- Essay --}}
                <template x-if="currentQuestion && currentQuestion.type === 'essai'">
                    <div class="space-y-3">
                        <div
                            class="p-4 bg-white rounded-xl border border-slate-200 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-50 transition-all">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Jawaban
                                Anda</label>
                            <textarea x-model="essayAnswer" @input.debounce.1000ms="saveAnswer()"
                                class="w-full min-h-[140px] border-0 focus:ring-0 text-xs font-semibold text-slate-700 leading-relaxed placeholder-slate-300 resize-none p-0"
                                placeholder="Tuliskan jawaban Anda di sini..."></textarea>
                        </div>
                        <span class="text-[9px] font-semibold text-slate-400 italic block pl-1">Jawaban otomatis tersimpan
                            saat Anda mengetik</span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Bottom Control Bar --}}
        <div class="fixed bottom-16 left-0 right-0 z-40 bg-white border-t border-slate-200 px-4 py-3 flex gap-2 shadow-lg">
            <button @click="prevQuestion()" :disabled="currentIndex === 0"
                class="flex-1 py-2.5 bg-white border border-slate-200 text-xs font-bold uppercase tracking-wider rounded-lg text-slate-650 enabled:active:bg-slate-100 disabled:opacity-40 shadow-xs">
                Sebelumnya
            </button>
            <button @click="toggleDoubtful()"
                :class="doubtfulAnswers[currentQuestion.id] ? 'bg-amber-500 text-white border-transparent' : 'bg-white border-slate-200 text-slate-600'"
                class="px-4 py-2.5 border rounded-lg text-xs font-semibold flex items-center justify-center shadow-xs">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </button>
            <button @click="showQuestionsDrawer = true"
                class="px-4 py-2.5 bg-indigo-50 border border-indigo-150 text-indigo-650 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center justify-center shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>

            <template x-if="currentIndex < allQuestions.length - 1">
                <button @click="nextQuestion()"
                    class="flex-1 py-2.5 bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg active:bg-indigo-750 shadow-xs">
                    Selanjutnya
                </button>
            </template>
            <template x-if="currentIndex === allQuestions.length - 1">
                @if($currentSubtest && $currentSubtest->id !== $allSubtests->last()->id)
                    <button @click="skipToTransition()"
                        class="flex-1 py-2.5 bg-amber-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg active:bg-amber-700 shadow-xs">
                        Next Subtest
                    </button>
                @else
                    <button @click="finishExam()"
                        class="flex-1 py-2.5 bg-rose-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg active:bg-rose-750 shadow-xs">
                        Selesai
                    </button>
                @endif
            </template>
        </div>

        {{-- Jump to Question Drawer (Alpine Sheet) --}}
        <div x-show="showQuestionsDrawer" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                @click="showQuestionsDrawer = false"></div>

            {{-- Sheet --}}
            <div
                class="fixed bottom-0 inset-x-0 bg-white rounded-t-2xl border-t border-slate-200 max-h-[70vh] flex flex-col z-10 overflow-hidden transform transition-all duration-300">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        Navigasi Soal
                    </h3>
                    <button @click="showQuestionsDrawer = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4">
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="(q, idx) in allQuestions" :key="q.id">
                            <button @click="goToQuestion(idx); showQuestionsDrawer = false"
                                class="h-9 rounded-lg text-xs font-bold transition-all border flex items-center justify-center shadow-xs"
                                :class="getQuestionNavClass(q.id, idx)">
                                <span x-text="idx + 1"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div
                    class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-center gap-4 text-[9px] font-bold uppercase tracking-wider text-slate-500">
                    <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-emerald-500"></span> Terjawab
                    </div>
                    <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-amber-500"></span> Ragu-Ragu
                    </div>
                    <div class="flex items-center gap-1"><span
                            class="w-2.5 h-2.5 rounded bg-white border border-slate-200">Kosong</span></div>
                </div>
            </div>
        </div>

        {{-- Finish Confirmation Sheet (Mobile bottom sheet instead of modal) --}}
        <div x-show="showFinishModal" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showFinishModal = false">
            </div>
            <div
                class="fixed bottom-0 inset-x-0 bg-white rounded-t-2xl border-t border-slate-200 z-10 p-5 space-y-4 text-center animate-in slide-in-from-bottom duration-350">
                <div
                    class="w-11 h-11 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto border border-rose-100 shadow-sm">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>

                <div class="space-y-1">
                    <h4 class="text-sm font-bold text-slate-800">Selesaikan Ujian Sekarang?</h4>
                    <p class="text-[11px] text-slate-500 max-w-xs mx-auto leading-relaxed">Pastikan semua soal telah
                        terjawab. Setelah selesai, Anda tidak dapat mengubah jawaban lagi.</p>
                </div>

                <div class="flex gap-2 pt-2">
                    <button @click="showFinishModal = false"
                        class="flex-1 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-650 rounded-lg text-xs font-bold uppercase tracking-wider transition-all">
                        Batal
                    </button>
                    <form action="{{ route('student.exam.finish', $examResult) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm">
                            Selesai & Kirim
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

                    selectedOptionId: null,
                    selectedOptionIds: [],
                    matchingAnswers: {},
                    essayAnswer: '',
                    shuffledMatches: [],
                    doubtfulAnswers: JSON.parse(localStorage.getItem('doubtful_answers_' + {{ $examResult->id }}) || '{}'),

                    timerInterval: null,
                    showFinishModal: false,
                    showQuestionsDrawer: false,

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
                            return 'bg-indigo-600 text-white border-indigo-600 shadow-sm ring-2 ring-indigo-50';
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
    </style>
@endsection