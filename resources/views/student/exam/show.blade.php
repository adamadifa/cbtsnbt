@extends('layouts.student')

{{-- Clear layout for Exam Mode --}}
@section('page_title', '')
@section('page_subtitle', '')

@section('content')
<div x-data="examShell()" x-init="initExam()" class="fixed inset-0 bg-slate-50 z-[50] flex flex-col overflow-hidden select-none">
    {{-- Top Bar Navigation --}}
    <div class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-6 shrink-0 relative z-[60]">
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-600/20">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div class="hidden sm:block">
                <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest leading-none">{{ $session->title }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[9px] text-indigo-600 font-extrabold uppercase tracking-widest bg-indigo-50 px-1.5 py-0.5 rounded">SUBTEST: {{ $currentSubtest->title ?? $currentSubtest->subject->name }}</span>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="ml-4 px-3 py-1.5 border border-slate-100 rounded-lg text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Dashboard
            </a>
        </div>

        {{-- Timer & Progress --}}
        <div class="flex items-center gap-3">
             {{-- Info Subtest --}}
             <div class="hidden lg:flex items-center gap-2 mr-4">
                <template x-for="(st, index) in allSubtests" :key="st.id">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 rounded-full" 
                             :class="st.id == currentSubtestId ? 'bg-indigo-600 ring-4 ring-indigo-50' : (completedSubtests.includes(st.id) ? 'bg-green-500' : 'bg-slate-200')">
                        </div>
                        <div x-show="index < allSubtests.length - 1" class="w-4 h-0.5" :class="completedSubtests.includes(st.id) ? 'bg-green-200' : 'bg-slate-100'"></div>
                    </div>
                </template>
             </div>

             <div class="px-4 py-2 bg-slate-900 rounded-xl flex items-center gap-3 shadow-lg shadow-slate-200">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex flex-col">
                    <span class="text-[7px] font-black text-indigo-300 uppercase tracking-tighter leading-none mb-0.5">Waktu Subtest</span>
                    <span class="text-sm font-black text-white tabular-nums leading-none tracking-wider" x-text="formatTime(remainingSeconds)">00:00:00</span>
                </div>
            </div>

            @if($allSubtests->last()->id == $currentSubtest->id)
                <button @click="finishExam()" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-100 hover:bg-red-700 transition-all">
                    Selesai Ujian
                </button>
            @endif
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        {{-- Left: Main Question Area --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar bg-white p-6 md:p-12">
            <div class="max-w-3xl mx-auto space-y-8 pb-32">
                
                {{-- Empty State --}}
                <template x-if="!allQuestions || allQuestions.length === 0">
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Belum Ada Soal</h3>
                        <p class="text-sm text-slate-400 font-bold mt-2 max-w-xs">Admin belum menambahkan soal ke dalam subtest ini. Silakan hubungi admin atau kembali ke dashboard.</p>
                        <a href="{{ route('dashboard') }}" class="mt-8 px-6 py-3 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100">Kembali ke Dashboard</a>
                    </div>
                </template>

                {{-- Question Header --}}
                <div class="flex items-start gap-4" x-show="!isTransitioning && currentQuestion">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0 border border-slate-200 shadow-sm">
                        <span class="text-base font-black text-slate-800" x-text="currentIndex + 1">1</span>
                    </div>
                    <div class="flex-1 min-w-0">
                         <div class="prose prose-slate max-w-none text-slate-700 font-medium leading-relaxed exam-question-content" x-html="currentQuestion ? currentQuestion.content : ''"></div>
                    </div>
                </div>

                {{-- Options List (Dynamic based on Type) --}}
                <div class="ml-14" x-show="!isTransitioning && currentQuestion">
                    {{-- Standard Multiple Choice (Radio) --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda'">
                        <div class="space-y-4">
                            <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer transition-all hover:bg-white group hover:border-indigo-200"
                                       :class="selectedOptionId == option.id ? 'bg-indigo-50/50 ring-2 ring-indigo-200 border-transparent shadow-md' : 'bg-white shadow-sm'">
                                    <input type="radio" :name="'question_' + currentQuestion.id" :value="option.id" x-model="selectedOptionId" @change="saveAnswer()" class="hidden">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-4 shrink-0 transition-all text-sm font-black"
                                         :class="selectedOptionId == option.id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600'">
                                        <span x-text="getOptionLabel(index)">A</span>
                                    </div>
                                    <div class="flex-1 text-sm font-bold text-slate-700 leading-snug" x-html="option.content"></div>
                                </label>
                            </template>
                        </div>
                    </template>

                    {{-- Multiple Response (Checkbox) --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda_kompleks'">
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4"></path></svg>
                                Pilih satu atau lebih jawaban benar
                            </p>
                            <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                                <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer transition-all hover:bg-white group hover:border-indigo-200"
                                       :class="selectedOptionIds.includes(option.id) ? 'bg-indigo-50/50 ring-2 ring-indigo-200 border-transparent shadow-md' : 'bg-white shadow-sm'">
                                    <input type="checkbox" :value="option.id" x-model="selectedOptionIds" @change="saveAnswer()" class="w-5 h-5 text-indigo-600 border-slate-200 rounded-lg focus:ring-offset-0 focus:ring-4 focus:ring-indigo-50 mr-4 transition-all">
                                    <div class="flex-1 text-sm font-bold text-slate-700 leading-snug" x-html="option.content"></div>
                                </label>
                            </template>
                        </div>
                    </template>

                    {{-- Matching (Drag & Drop) --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'menjodohkan'">
                        <div class="space-y-8">
                            <div class="p-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4 4"></path></svg>
                                </div>
                                <p class="text-[10px] font-black text-indigo-800 uppercase tracking-widest leading-relaxed">
                                    Tarik kotak jawaban dari kolom kanan ke kotak kosong di samping pertanyaan yang sesuai.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                                {{-- Left: Premises --}}
                                <div class="space-y-4">
                                    <template x-for="(option, index) in currentQuestion.options" :key="'premise-'+option.id">
                                        <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm min-h-[80px]">
                                            <div class="flex-1 text-xs font-bold text-slate-600" x-html="option.label"></div>
                                            <div class="w-8 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </div>
                                            {{-- Drop Target --}}
                                            <div class="flex-1 min-h-[60px] p-2 rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 matching-drop-target transition-all duration-300 flex items-center justify-center"
                                                 :data-premise-id="option.id"
                                                 x-init="initSortable($el)">
                                                {{-- If matched, content here --}}
                                                <template x-if="matchingAnswers[option.id]">
                                                    <div class="w-full bg-indigo-600 text-white p-3 rounded-lg text-[10px] font-black uppercase text-center shadow-md cursor-move flex items-center justify-between gap-2"
                                                         :data-match-id="matchingAnswers[option.id]">
                                                        <span x-html="getMatchContent(matchingAnswers[option.id])" class="truncate"></span>
                                                        <button @click.stop="unmatch(option.id)" class="hover:text-red-300 transition-colors">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Right: Pool of Shuffled Matches --}}
                                <div class="space-y-4 p-6 bg-slate-50 rounded-3xl border border-slate-100 shadow-inner">
                                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest text-center mb-4">Pilihan Jawaban (Tarik Ke Kiri)</h4>
                                    <div class="space-y-2 matching-pool min-h-[100px]" x-init="initSortable($el)">
                                        <template x-for="match in shuffledMatches" :key="match.id">
                                            <div x-show="!isMatched(match.id)"
                                                 class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-[10px] font-black uppercase text-center cursor-move hover:border-indigo-600 hover:text-indigo-600 transition-all duration-200 active:scale-95 flex items-center justify-center"
                                                 :data-match-id="match.id">
                                                <span x-html="match.content"></span>
                                            </div>
                                        </template>
                                        <template x-if="getAvailableMatches().length === 0">
                                            <div class="py-12 flex flex-col items-center justify-center opacity-40">
                                                <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Sudah Terpasang Semua</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Essay / Text (Textarea) --}}
                    <template x-if="currentQuestion && currentQuestion.type === 'essai'">
                        <div class="space-y-4 max-w-2xl">
                             <div class="p-5 bg-white rounded-3xl border border-slate-100 shadow-sm focus-within:border-indigo-200 focus-within:ring-4 focus-within:ring-indigo-50 transition-all">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Jawaban Anda</label>
                                <textarea x-model="essayAnswer" 
                                          @input.debounce.1000ms="saveAnswer()" 
                                          class="w-full min-h-[200px] border-0 focus:ring-0 text-sm font-bold text-slate-700 leading-relaxed placeholder-slate-300 resize-none"
                                          placeholder="Tuliskan jawaban lengkap Anda di sini..."></textarea>
                             </div>
                             <div class="flex items-center gap-2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-[9px] font-bold uppercase tracking-widest italic">Jawaban akan tersimpan otomatis saat Anda mengetik</span>
                             </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Right: Navigation Sidebar --}}
        <div class="w-80 bg-white border-l border-slate-100 flex flex-col shrink-0">
            <div class="p-6 border-b border-slate-50 bg-slate-50/20">
                <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Navigasi Soal
                </h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Hanya Subtest Berjalan</p>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                <div class="grid grid-cols-5 gap-2">
                    <template x-for="(q, idx) in allQuestions" :key="q.id">
                        <button @click="goToQuestion(idx)"
                                class="h-10 rounded-xl text-xs font-black transition-all border flex items-center justify-center shadow-sm"
                                :class="getQuestionNavClass(q.id, idx)">
                            <span x-text="idx + 1"></span>
                        </button>
                    </template>
                </div>

                {{-- Status Card --}}
                <div class="mt-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                         <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Progres Subtest</span>
                         <span class="text-xs font-black text-slate-800" x-text="Math.round((Object.keys(answers).length / allQuestions.length) * 100) + '%'">0%</span>
                    </div>
                    <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full transition-all duration-500" :style="'width: ' + ((Object.keys(answers).length / allQuestions.length) * 100) + '%'"></div>
                    </div>
                </div>
            </div>

            {{-- Navigation Action --}}
            <div class="p-6 bg-white border-t border-slate-50 flex items-center justify-between gap-3 shrink-0">
                <button @click="prevQuestion()" :disabled="currentIndex === 0" 
                        class="flex-1 py-3 border border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-800 hover:bg-slate-50 disabled:opacity-30 transition-all">
                    Sebelumnya
                </button>
                <template x-if="currentIndex < allQuestions.length - 1">
                    <button @click="nextQuestion()"
                            class="flex-1 py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                        Selanjutnya
                    </button>
                </template>
                <template x-if="currentIndex === allQuestions.length - 1">
                    @if($currentSubtest->id !== $allSubtests->last()->id)
                        <button @click="skipToTransition()" class="flex-1 py-3 bg-amber-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-100 hover:bg-amber-600 transition-all">
                            Lanjut Subtest
                        </button>
                    @else
                        <button @click="finishExam()"
                                class="flex-1 py-3 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-100 hover:bg-red-700 transition-all">
                            Selesai Ujian
                        </button>
                    @endif
                </template>
            </div>
        </div>
    </div>

    {{-- Transition Overlay (Break/Countdown) --}}
    <div x-show="isTransitioning" x-transition.opacity class="fixed inset-0 z-[100] bg-slate-900/90 backdrop-blur-md flex items-center justify-center p-6 text-center">
        <div class="max-w-md w-full space-y-8">
            <div class="w-24 h-24 bg-indigo-600 rounded-full flex items-center justify-center mx-auto shadow-2xl shadow-indigo-500/40 relative">
                 <svg class="w-10 h-10 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                 <div class="absolute inset-0 border-4 border-indigo-400 rounded-full animate-ping opacity-25"></div>
            </div>
            
            <div class="space-y-2">
                <h3 class="text-2xl font-black text-white uppercase tracking-tight" x-text="remainingSeconds <= 0 ? 'Waktu Subtest Habis' : 'Pindah Subtest'"></h3>
                <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest">Mempersiapkan subtest berikutnya...</p>
            </div>

            <div class="py-12">
                <span class="text-8xl font-black text-white tracking-tighter tabular-nums" x-text="transitionSeconds">10</span>
                <p class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.3em] mt-4">Detik Tersisa</p>
            </div>

            <div class="p-6 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm">
                 <p class="text-xs text-white font-bold leading-relaxed">
                    Jawaban Anda pada subtest ini telah tersimpan secara otomatis. Silakan bersiap untuk pengerjaan soal berikutnya.
                 </p>
            </div>
        </div>
    </div>

    {{-- Finish Confirmation Modal --}}
    <div x-show="showFinishModal" x-transition class="fixed inset-0 z-[100] flex items-center justify-center p-6">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showFinishModal = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden relative border border-slate-100">
            <div class="p-8 text-center pt-10">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2 uppercase">Selesaikan Ujian?</h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                    Ini adalah subtest terakhir. Pastikan semua jawaban sudah benar sebelum mengirim hasil.
                </p>
                
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Terjawab</p>
                        <p class="text-lg font-black text-slate-800 leading-none" x-text="Object.keys(answers).length"></p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Kosong</p>
                        <p class="text-lg font-black text-slate-600 leading-none" x-text="totalExamQuestions - Object.keys(answers).length"></p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-slate-50/50 border-t border-slate-50 flex gap-4">
                 <button @click="showFinishModal = false" class="flex-1 py-4 bg-white border border-slate-200 text-slate-500 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                    Batal
                </button>
                <form action="{{ route('student.exam.finish', $examResult) }}" method="POST" class="flex-1 flex">
                    @csrf
                    <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-red-100 hover:bg-red-700 transition-all">
                        Ya, Selesai!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .exam-question-content img { max-width: 100%; height: auto; border-radius: 1rem; margin: 1.5rem 0; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .exam-question-content table { border-collapse: collapse; margin: 1rem 0; width: 100%; }
    .exam-question-content td, .exam-question-content th { border: 1px solid #e2e8f0; padding: 0.75rem; font-size: 0.875rem; }
    .exam-question-content th { background-color: #f8fafc; font-weight: 700; }
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
            
            timerInterval: null,
            showFinishModal: false,
            
            // Subtest States
            currentSubtestId: {{ $currentSubtest->id }},
            allSubtests: @json($allSubtests),
            completedSubtests: @json($metadata['completed_subtests'] ?? []),
            isTransitioning: false,
            transitionSeconds: 10,
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

                fetch(`{{ route('student.exam.log-violation', $examResult) }}`, {
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
                this.transitionSeconds = 10;
                
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
                form.action = `{{ route('student.exam.next-subtest', $examResult) }}`;
                
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
                } else if (q.type === 'menjodohkan') {
                    this.matchingAnswers = ans.matching_answers || {};
                    this.prepareShuffledMatches();
                } else if (q.type === 'essai') {
                    this.essayAnswer = ans.essay_answer || '';
                }
            },

            prepareShuffledMatches() {
                const q = this.currentQuestion;
                // Seed based on question ID to keep shuffle consistent for this specific question view
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
                                // If drop to premise target
                                this.matchingAnswers[premiseId] = matchId;
                            } else {
                                // If drop back to pool
                                Object.keys(this.matchingAnswers).forEach(pId => {
                                    if (this.matchingAnswers[pId] == matchId) {
                                        delete this.matchingAnswers[pId];
                                    }
                                });
                            }
                            
                            this.saveAnswer();
                            // Sortable handles visual move, but we let Alpine re-render
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
                } else if (q.type === 'menjodohkan') {
                    payload.matching_answers = this.matchingAnswers;
                } else if (q.type === 'essai') {
                    payload.essay_answer = this.essayAnswer;
                }

                // Update local answers cache for navigation colors
                this.answers[q.id] = payload;

                // Send to Server
                fetch(`{{ route('student.exam.save-answer', $examResult) }}`, {
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
                
                let isAnswered = false;
                if (ans) {
                    const q = this.allQuestions[idx];
                    if (q.type === 'pilihan_ganda') isAnswered = !!ans.option_id || !!ans;
                    else if (q.type === 'pilihan_ganda_kompleks') isAnswered = ans.option_ids && ans.option_ids.length > 0;
                    else if (q.type === 'menjodohkan') isAnswered = ans.matching_answers && Object.keys(ans.matching_answers).length > 0;
                    else if (q.type === 'essai') isAnswered = ans.essay_answer && ans.essay_answer.trim().length > 0;
                }

                if (isCurrent) return 'bg-indigo-600 text-white border-indigo-600 shadow-indigo-100 ring-4 ring-indigo-50';
                if (isAnswered) return 'bg-white text-indigo-600 border-indigo-200 shadow-sm font-black ring-1 ring-indigo-50';
                return 'bg-white text-slate-400 border-slate-100 hover:bg-slate-50';
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
