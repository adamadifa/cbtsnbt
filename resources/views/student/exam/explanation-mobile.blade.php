@extends('layouts.student-mobile')

@section('content')
    <div x-data="reviewShell()" x-init="init()" class="space-y-6 pb-28">
        {{-- Empty State Placeholder --}}
        <template x-if="!allQuestions || allQuestions.length === 0">
            <div
                class="flex flex-col items-center justify-center text-center bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <div
                    class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-4 border border-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold text-slate-800">Tidak Ada Data Soal</h4>
                <p class="text-xs text-slate-450 mt-1 max-w-xs leading-relaxed">Belum ada soal tryout yang ditambahkan pada
                    subtest yang telah diselesaikan.</p>
                <a href="{{ route('student.exam.results', $examResult) }}"
                    class="mt-6 px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">Kembali</a>
            </div>
        </template>

        <template x-if="allQuestions && allQuestions.length > 0">
            <div class="space-y-6">
                {{-- Question Content Card --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <span
                            class="text-[9px] font-bold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded tracking-wide uppercase border border-indigo-100"
                            x-text="currentQuestion ? currentQuestion.subtest_title : ''"></span>
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

                {{-- Options Card --}}
                <div class="space-y-3" x-show="currentQuestion">
                    {{-- Standard Multiple Choice --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda'">
                        <div class="space-y-3">
                            <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                <div class="relative flex items-center p-4 rounded-xl border transition-all"
                                    :class="getOptionClass(option)">

                                    {{-- Correct/User Badge --}}
                                    <div class="absolute -right-2 -top-2 flex gap-1 z-10">
                                        <span x-show="option.is_correct"
                                            class="px-2 py-0.5 bg-emerald-600 text-white text-[8px] font-bold uppercase rounded-md shadow-xs">Kunci</span>
                                        <span x-show="userChoiceId == option.id"
                                            class="px-2 py-0.5 text-white text-[8px] font-bold uppercase rounded-md shadow-xs"
                                            :class="option.is_correct ? 'bg-indigo-600' : 'bg-rose-600'">Anda</span>
                                    </div>

                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-3 shrink-0 text-xs font-extrabold"
                                        :class="getOptionLetterClass(option)">
                                        <span x-text="getOptionLabel(index + 1)">A</span>
                                    </div>
                                    <div class="flex-1 text-xs font-semibold leading-relaxed"
                                        :class="option.is_correct ? 'text-emerald-900' : (userChoiceId == option.id ? 'text-rose-900' : 'text-slate-700')"
                                        x-html="option.content"></div>

                                    {{-- Icon Indicator --}}
                                    <div class="ml-3 shrink-0">
                                        <template x-if="option.is_correct">
                                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </template>
                                        <template x-if="!option.is_correct && userChoiceId == option.id">
                                            <svg class="w-4 h-4 text-rose-650" fill="currentColor" viewBox="0 0 20 20">
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

                    {{-- Multiple Response (Complex MC) --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda_kompleks'">
                        <div class="space-y-3">
                            <template x-for="(option, index) in currentQuestion.options" :key="'mc-'+option.id">
                                <div class="relative flex items-center p-4 rounded-xl border transition-all bg-white border-slate-200"
                                    :class="getComplexOptionClass(option)">
                                    <div class="flex-1 text-xs font-semibold text-slate-700 leading-relaxed"
                                        x-html="option.content"></div>
                                    <div class="flex items-center gap-1.5 ml-3">
                                        <template x-if="option.is_correct">
                                            <span
                                                class="px-2 py-0.5 bg-emerald-50 border border-emerald-250 text-emerald-700 text-[8px] font-extrabold uppercase rounded">Kunci</span>
                                        </template>
                                        <template x-if="selectedOptionIds.map(Number).includes(option.id)">
                                            <span class="px-2 py-0.5 text-white text-[8px] font-extrabold uppercase rounded"
                                                :class="option.is_correct ? 'bg-indigo-655' : 'bg-rose-600'">Anda</span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Menjodohkan (Matching) --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'menjodohkan'">
                        <div class="overflow-hidden border border-slate-200 rounded-xl bg-white shadow-xs">
                            <div class="p-4 bg-slate-50 border-b border-slate-200">
                                <h5 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Hasil Menjodohkan
                                </h5>
                            </div>
                            <div class="divide-y divide-slate-100 p-2 space-y-3">
                                <template x-for="option in currentQuestion.options" :key="'match-row-'+option.id">
                                    <div class="p-3.5 space-y-2 border border-slate-100 rounded-lg">
                                        <div class="text-xs font-bold text-slate-700" x-html="option.label"></div>
                                        <div class="flex items-center gap-2 flex-wrap text-[10px] font-bold">
                                            <span class="text-slate-400 font-semibold uppercase">Anda:</span>
                                            <div x-show="matchingAnswers[option.id]" class="px-2 py-0.5 rounded border"
                                                :class="matchingAnswers[option.id] == option.id ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'">
                                                <span x-html="getMatchContent(matchingAnswers[option.id])"></span>
                                            </div>
                                            <span x-show="!matchingAnswers[option.id]"
                                                class="text-slate-400 italic font-medium">Tidak Terisi</span>

                                            <span class="text-slate-400 font-semibold uppercase ml-2">Kunci:</span>
                                            <div
                                                class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded">
                                                <span x-html="option.content"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Benar / Salah (Comparison Table) --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'benar_salah'">
                        <div class="overflow-hidden border border-slate-200 rounded-xl bg-white shadow-xs">
                            <div class="p-4 bg-slate-50 border-b border-slate-200">
                                <h5 class="text-[10px] font-bold text-slate-550 uppercase tracking-wider">Hasil Benar / Salah</h5>
                            </div>
                            <div class="divide-y divide-slate-100 p-2 space-y-3">
                                <template x-for="option in currentQuestion.options" :key="'bs-row-'+option.id">
                                    <div class="p-3.5 space-y-2 border border-slate-100 rounded-lg">
                                        <div class="text-xs font-semibold text-slate-700 leading-relaxed" x-html="option.content"></div>
                                        <div class="flex items-center gap-2 flex-wrap text-[10px] font-bold">
                                            <span class="text-slate-450 font-semibold uppercase">Anda:</span>
                                            <div x-show="matchingAnswers[option.id]" class="px-2 py-0.5 rounded border uppercase"
                                                :class="matchingAnswers[option.id] == (option.is_correct ? 'benar' : 'salah') ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'">
                                                <span x-text="matchingAnswers[option.id]"></span>
                                            </div>
                                            <span x-show="!matchingAnswers[option.id]" class="text-slate-400 italic font-medium">Tidak Terisi</span>

                                            <span class="text-slate-450 font-semibold uppercase ml-2">Kunci:</span>
                                            <div class="px-2 py-0.5 bg-indigo-50 text-indigo-750 border border-indigo-100 rounded">
                                                <span x-text="option.is_correct ? 'Benar' : 'Salah'"></span>
                                            </div>
                                            
                                            <span class="ml-auto">
                                                <svg x-show="matchingAnswers[option.id] == (option.is_correct ? 'benar' : 'salah')"
                                                    class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <svg x-show="matchingAnswers[option.id] && matchingAnswers[option.id] != (option.is_correct ? 'benar' : 'salah')"
                                                    class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Essay --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'essai'">
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Jawaban
                                Anda</label>
                            <div class="text-xs font-semibold text-slate-700 leading-relaxed whitespace-pre-wrap"
                                x-text="essayAnswer || 'Tidak ada jawaban.'"></div>
                        </div>
                    </template>
                </div>

                {{-- Explanation Box --}}
                <div class="p-5 bg-slate-900 rounded-xl text-white space-y-3 shadow-md" x-show="currentQuestion">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-indigo-650 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-indigo-305">Pembahasan</h4>
                    </div>
                    <div class="prose prose-invert prose-sm max-w-none text-slate-300 text-[11px] font-semibold leading-relaxed"
                        x-show="currentQuestion && currentQuestion.explanation"
                        x-html="currentQuestion ? currentQuestion.explanation : ''"></div>
                    <div class="text-xs font-semibold text-slate-500 italic"
                        x-show="currentQuestion && !currentQuestion.explanation">Belum ada pembahasan tertulis untuk soal
                        ini.</div>
                </div>
            </div>
        </template>

        {{-- Bottom Review Control Panel --}}
        <div class="fixed bottom-16 left-0 right-0 z-40 bg-white border-t border-slate-200 px-4 py-3 flex gap-2 shadow-lg">
            <button @click="prevQuestion()" :disabled="currentIndex === 0"
                class="flex-1 py-2.5 bg-white border border-slate-200 text-xs font-bold uppercase tracking-wider rounded-lg text-slate-650 enabled:active:bg-slate-100 disabled:opacity-40 shadow-xs">
                Sebelumnya
            </button>
            <button @click="showQuestionsDrawer = true"
                class="px-4 py-2.5 bg-indigo-50 border border-indigo-150 text-indigo-650 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center justify-center shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <button @click="nextQuestion()" :disabled="currentIndex === allQuestions.length - 1"
                class="flex-1 py-2.5 bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg enabled:active:bg-indigo-750 disabled:opacity-40 shadow-xs">
                Selanjutnya
            </button>
        </div>

        {{-- Jump to Question Drawer (Alpine Sheet) --}}
        <div x-show="showQuestionsDrawer" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                @click="showQuestionsDrawer = false"></div>

            {{-- Sheet --}}
            <div
                class="fixed bottom-0 inset-x-0 bg-white rounded-t-2xl border-t border-slate-200 max-h-[75vh] flex flex-col z-10 overflow-hidden transform transition-all duration-300">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        Daftar Soal Pembahasan
                    </h3>
                    <button @click="showQuestionsDrawer = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-6">
                    @foreach($subtests as $subtest)
                        <div class="space-y-2">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider pl-1">
                                {{ $subtest->title ?: ($subtest->subject->name ?? 'Subtest') }}</p>
                            <div class="grid grid-cols-5 gap-2">
                                <template x-for="(q, idx) in allQuestions.filter(qu => qu.subtest_id == {{ $subtest->id }})"
                                    :key="q.id">
                                    <button @click="goToQuestionById(q.id); showQuestionsDrawer = false"
                                        class="h-9 rounded-lg text-xs font-bold transition-all border flex items-center justify-center shadow-xs"
                                        :class="getNavClass(q)">
                                        <span x-text="allQuestions.findIndex(qu => qu.id == q.id) + 1"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div
                    class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-center gap-4 text-[9px] font-bold uppercase tracking-wider text-slate-500">
                    <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-emerald-500"></span> Benar
                    </div>
                    <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-rose-500"></span> Salah</div>
                    <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-slate-350"></span> Kosong</div>
                </div>
            </div>
        </div>
    </div>

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
                    showQuestionsDrawer: false,

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
                        } else if (q.type === 'essai') {
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
                            } else if (q.type === 'essai') {
                                isCorrect = true;
                            }
                        }

                        if (isCurrent) return 'bg-indigo-600 text-white border-indigo-600 ring-2 ring-indigo-50';
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
                        return 'bg-white border-slate-200 active:bg-slate-50';
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
                        return 'bg-white border-slate-200 active:bg-slate-50';
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