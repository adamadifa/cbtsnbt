@extends('layouts.student')

{{-- Clear layout for Review Mode --}}
@section('page_title', '')
@section('page_subtitle', '')

@section('content')
<div x-data="reviewShell()" x-init="init()" class="fixed inset-0 bg-slate-50 z-[50] flex flex-col overflow-hidden">
    {{-- Top Bar Navigation --}}
    <div class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-6 shrink-0 relative z-[60]">
        <div class="flex items-center gap-4">
             <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center shadow-lg shadow-amber-500/20">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div>
                <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest leading-none">Mode Pembahasan</h2>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $session->title }}</p>
            </div>
            <a href="{{ route('student.exam.results', $examResult) }}" class="ml-4 px-3 py-1.5 border border-slate-100 rounded-lg text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Hasil
            </a>
        </div>

        <div class="flex items-center gap-4">
             <div class="flex items-center gap-4 text-[9px] font-black uppercase tracking-widest">
                <div class="flex items-center gap-1.5 text-green-600">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Benar
                </div>
                <div class="flex items-center gap-1.5 text-red-600">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Salah
                </div>
                <div class="flex items-center gap-1.5 text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                    Kosong
                </div>
             </div>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        {{-- Left: Review Area --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar bg-white p-6 md:p-12 relative">
            <div class="max-w-3xl mx-auto space-y-10 pb-32">
                {{-- Question Header --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                         <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded tracking-widest uppercase" x-text="currentQuestion.subtest_title"></span>
                         <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                         <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Soal #<span x-text="currentIndex + 1"></span></span>
                    </div>

                    <div class="flex items-start gap-5">
                        <div class="w-10 h-10 rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-base font-black text-slate-800" x-text="currentIndex + 1">1</span>
                        </div>
                        <div class="flex-1 prose prose-slate max-w-none text-slate-700 font-medium leading-relaxed exam-question-content" x-html="currentQuestion.content"></div>
                    </div>
                </div>

                {{-- Options List (Dynamic based on Type) --}}
                <div class="ml-14" x-show="currentQuestion">
                    {{-- Standard Multiple Choice --}}
                    <template x-if="currentQuestion.type === 'pilihan_ganda'">
                        <div class="space-y-4">
                            <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                <div class="relative flex items-center p-5 rounded-2xl border transition-all"
                                     :class="getOptionClass(option)">
                                    
                                    {{-- Correct/User Badge --}}
                                    <div class="absolute -right-2 -top-2 flex gap-1 z-10">
                                        <span x-show="option.is_correct" class="px-2 py-1 bg-green-500 text-white text-[8px] font-black uppercase rounded-lg shadow-lg shadow-green-100">Kunci Jawaban</span>
                                        <span x-show="userChoiceId == option.id" 
                                              class="px-2 py-1 text-white text-[8px] font-black uppercase rounded-lg shadow-lg"
                                              :class="option.is_correct ? 'bg-indigo-600 shadow-indigo-100' : 'bg-red-500 shadow-red-100'">Jawaban Kamu</span>
                                    </div>

                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-5 shrink-0"
                                         :class="getOptionLetterClass(option)">
                                        <span class="text-sm font-black" x-text="getOptionLabel(index + 1)">A</span>
                                    </div>
                                    <div class="flex-1 text-sm font-bold leading-snug" :class="option.is_correct ? 'text-green-900' : (userChoiceId == option.id ? 'text-red-900' : 'text-slate-700')" x-html="option.content"></div>

                                    {{-- Icon Indicator --}}
                                    <div class="ml-4 shrink-0">
                                        <template x-if="option.is_correct">
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </template>
                                        <template x-if="!option.is_correct && userChoiceId == option.id">
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Multiple Response (Checkbox) --}}
                    <template x-if="currentQuestion.type === 'pilihan_ganda_kompleks'">
                        <div class="space-y-4">
                            <template x-for="(option, index) in currentQuestion.options" :key="'mc-'+option.id">
                                <div class="relative flex items-center p-5 rounded-2xl border transition-all"
                                     :class="getComplexOptionClass(option)">
                                    <div class="flex-1 text-sm font-bold text-slate-700" x-html="option.content"></div>
                                    <div class="flex items-center gap-3">
                                        <template x-if="option.is_correct">
                                            <span class="px-2 py-1 bg-green-500 text-white text-[8px] font-black uppercase rounded-lg">Kunci</span>
                                        </template>
                                        <template x-if="selectedOptionIds.includes(option.id)">
                                            <span class="px-2 py-1 text-white text-[8px] font-black uppercase rounded-lg"
                                                  :class="option.is_correct ? 'bg-indigo-600' : 'bg-red-500'">Pilihan Anda</span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Menjodohkan (Comparison Table) --}}
                    <template x-if="currentQuestion.type === 'menjodohkan'">
                        <div class="overflow-hidden border border-slate-100 rounded-3xl shadow-sm bg-white">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Item Pertanyaan</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilihan Anda</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kunci Jawaban</th>
                                        <th class="px-6 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <template x-for="option in currentQuestion.options" :key="'match-row-'+option.id">
                                        <tr>
                                            <td class="px-6 py-4 text-xs font-bold text-slate-600" x-html="option.label"></td>
                                            <td class="px-6 py-4">
                                                <div x-show="matchingAnswers[option.id]" class="px-3 py-2 rounded-xl text-[9px] font-black uppercase inline-block ring-2 ring-offset-1"
                                                     :class="matchingAnswers[option.id] == option.id ? 'bg-green-50 text-green-700 ring-green-100' : 'bg-red-50 text-red-700 ring-red-100'">
                                                    <span x-html="getMatchContent(matchingAnswers[option.id])"></span>
                                                </div>
                                                <div x-show="!matchingAnswers[option.id]" class="text-[9px] text-slate-400 italic">Tidak Terisi</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="px-3 py-2 bg-indigo-50 text-indigo-700 rounded-xl text-[9px] font-black uppercase inline-block ring-2 ring-indigo-100 ring-offset-1">
                                                    <span x-html="option.content"></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <svg x-show="matchingAnswers[option.id] == option.id" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                <svg x-show="matchingAnswers[option.id] && matchingAnswers[option.id] != option.id" class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>

                    {{-- Essay --}}
                    <template x-if="currentQuestion.type === 'essai'">
                        <div class="space-y-4">
                            <div class="p-6 bg-white border border-slate-100 rounded-3xl shadow-sm">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Jawaban Anda</label>
                                <div class="text-sm font-bold text-slate-700 leading-relaxed whitespace-pre-wrap" x-text="essayAnswer || 'Tidak ada jawaban.'"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Explanation Box --}}
                <div class="ml-14 p-8 bg-slate-900 rounded-[2rem] text-white space-y-4 shadow-xl shadow-slate-200 scroll-mt-20" id="explanation-box">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-indigo-300">Pembahasan Soal</h4>
                    </div>
                    <div class="prose prose-invert prose-sm max-w-none text-slate-300 font-medium leading-relaxed" x-show="currentQuestion.explanation" x-html="currentQuestion.explanation"></div>
                    <div class="text-sm font-bold text-slate-500 italic" x-show="!currentQuestion.explanation">Belum ada teks pembahasan untuk soal ini.</div>
                </div>
            </div>
        </div>

        {{-- Right: Navigation Sidebar --}}
        <div class="w-80 bg-white border-l border-slate-100 flex flex-col shrink-0 no-print">
            <div class="p-6 border-b border-slate-50 bg-slate-50/20">
                <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Daftar Soal
                </h3>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar space-y-6">
                @foreach($subtests as $subtest)
                    <div class="space-y-3">
                         <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">{{ $subtest->title ?: ($subtest->subject->name ?? 'Subtest') }}</p>
                         <div class="grid grid-cols-5 gap-2">
                            <template x-for="(q, idx) in allQuestions.filter(qu => qu.subtest_id == {{ $subtest->id }})" :key="q.id">
                                <button @click="goToQuestionById(q.id)"
                                        class="h-10 rounded-xl text-xs font-black transition-all border flex items-center justify-center"
                                        :class="getNavClass(q)">
                                    <span x-text="allQuestions.findIndex(qu => qu.id == q.id) + 1"></span>
                                </button>
                            </template>
                         </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer Info --}}
            <div class="p-6 bg-slate-50/50 border-t border-slate-50 no-print">
                 <div class="grid grid-cols-2 gap-3">
                    <button @click="prevQuestion()" :disabled="currentIndex === 0" class="py-3 bg-white border border-slate-200 text-[10px] font-black uppercase tracking-widest rounded-xl text-slate-400 enabled:hover:text-slate-800 transition-all disabled:opacity-30">Sebelumnya</button>
                    <button @click="nextQuestion()" :disabled="currentIndex === allQuestions.length - 1" class="py-3 bg-white border border-slate-200 text-[10px] font-black uppercase tracking-widest rounded-xl text-slate-400 enabled:hover:text-slate-800 transition-all disabled:opacity-30">Selanjutnya</button>
                 </div>
                 <button onclick="window.print()" class="w-full mt-3 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-slate-200">
                    Cetak Pembahasan (PDF)
                 </button>
            </div>
        </div>
    </div>
</div>

<style>
    .exam-question-content img { max-width: 100%; height: auto; border-radius: 1rem; margin: 1.5rem 0; }
    .exam-question-content table { border-collapse: collapse; margin: 1rem 0; width: 100%; }
    .exam-question-content td, .exam-question-content th { border: 1px solid #e2e8f0; padding: 0.75rem; font-size: 0.875rem; }
    
    @media print {
        .no-print { display: none !important; }
        .fixed { position: relative !important; overflow: visible !important; display: block !important; }
        .flex-1 { overflow: visible !important; }
        .bg-slate-50 { background: white !important; }
        .h-16 { display: none !important; }
        .md\:p-12 { padding: 0 !important; }
        #explanation-box { box-shadow: none !important; background: #f8fafc !important; color: #1e293b !important; page-break-inside: avoid; }
        .exam-question-content { color: black !important; }
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
                this.updateQuestionState();
            },

            get currentQuestion() {
                return this.allQuestions[this.currentIndex];
            },

            getOptionLabel(index) {
                return String.fromCharCode(64 + index);
            },

            updateQuestionState() {
                if (!this.allQuestions || this.allQuestions.length === 0) return;
                const q = this.currentQuestion;
                const answer = this.userAnswers[q.id] || {};

                this.userChoiceId = null;
                this.selectedOptionIds = [];
                this.matchingAnswers = {};
                this.essayAnswer = '';

                if (q.type === 'pilihan_ganda') {
                    this.userChoiceId = answer.option_id || null;
                } else if (q.type === 'pilihan_ganda_kompleks') {
                    this.selectedOptionIds = answer.option_ids || [];
                } else if (q.type === 'menjodohkan') {
                    this.matchingAnswers = answer.matching_answers || {};
                } else if (q.type === 'essai') {
                    this.essayAnswer = answer.essay_answer || '';
                }
            },

            goToQuestionById(id) {
                this.currentIndex = this.allQuestions.findIndex(q => q.id == id);
                this.updateQuestionState();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            nextQuestion() {
                if (this.currentIndex < this.allQuestions.length - 1) {
                    this.currentIndex++;
                    this.updateQuestionState();
                }
            },

            prevQuestion() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                    this.updateQuestionState();
                }
            },

            getOptionClass(option) {
                if (option.is_correct) return 'bg-green-50 border-green-200 ring-2 ring-green-100';
                if (this.userChoiceId == option.id) return 'bg-red-50 border-red-200 ring-2 ring-red-100';
                return 'bg-white border-slate-100';
            },

            getOptionLetterClass(option) {
                if (option.is_correct) return 'bg-green-500 text-white shadow-lg shadow-green-100';
                if (this.userChoiceId == option.id) return 'bg-red-500 text-white shadow-lg shadow-red-100';
                return 'bg-slate-50 text-slate-400';
            },

            getNavClass(q) {
                const answer = this.userAnswers[q.id];
                const isCurrent = this.allQuestions[this.currentIndex].id == q.id;
                
                let base = isCurrent ? 'ring-2 ring-indigo-300 ring-offset-2 ' : '';
                
                // If not atttempted
                if (!answer) return base + 'bg-slate-100 text-slate-400 border-slate-200';
                
                // For essay, we don't have is_correct automatically sometimes, 
                // but we should show it as attempted at least.
                // However, the controller saves is_correct as false by default for Essay.
                // Let's rely on is_correct field we calculated in controller saveAnswer.
                if (answer.is_correct) return base + 'bg-green-500 text-white border-green-600 shadow-md shadow-green-100';
                
                // If it was attempted but not correct
                const wasAttempted = !!answer.option_id || 
                                     (answer.option_ids && answer.option_ids.length > 0) || 
                                     (answer.matching_answers && Object.keys(answer.matching_answers).length > 0) ||
                                     !!answer.essay_answer;
                
                if (wasAttempted) return base + 'bg-red-500 text-white border-red-600 shadow-md shadow-red-100';
                
                return base + 'bg-slate-100 text-slate-400 border-slate-200';
            },

            getComplexOptionClass(option) {
                const isSelected = this.selectedOptionIds.includes(option.id);
                if (option.is_correct) return 'bg-green-50 border-green-200 ring-2 ring-green-100';
                if (isSelected && !option.is_correct) return 'bg-red-50 border-red-200 ring-2 ring-red-100';
                return 'bg-white border-slate-100';
            },

            getMatchContent(matchId) {
                const match = this.currentQuestion.options.find(o => o.id == matchId);
                return match ? match.content : '';
            }
        }
    }
</script>
@endpush
