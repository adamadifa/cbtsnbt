@extends('layouts.admin')

@section('page_title', 'Tambah Soal Baru')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="questionForm()">
        {{-- Top Action / Navigation --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.questions.index') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Bank Soal
            </a>
        </div>

        <form action="{{ route('admin.questions.store') }}" method="POST" id="main-form"
            class="grid grid-cols-1 lg:grid-cols-4 gap-6" @submit="submitForm($event)" novalidate>
            @csrf

            {{-- Left Column: Content (3/4) --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Question Content Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden transition-all duration-300"
                    :class="touched.content && !hasContent ? 'ring-2 ring-red-500/20 border-red-500' : ''">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
                        <div class="flex items-center gap-3.5">
                            <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 tracking-tight">Konten Utama Soal <span
                                        class="text-red-500">*</span></h3>
                                <p class="text-xs text-slate-400 mt-0.5">Tuliskan pertanyaan atau stimulus soal secara
                                    lengkap</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div
                            class="rounded-xl border border-slate-200 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-indigo-100 focus-within:border-indigo-500 transition-all duration-200">
                            <textarea name="content" id="editor-main" class="hidden">{{ old('content') }}</textarea>
                        </div>
                        <template x-if="touched.content && !hasContent">
                            <p class="text-xs font-medium text-red-600 mt-2 ml-1 animate-in fade-in slide-in-from-top-1">
                                Konten soal wajib diisi</p>
                        </template>
                    </div>
                </div>

                {{-- Options Card --}}
                <div x-show="type !== 'essai' && type !== 'isian_singkat'" x-transition
                    class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden transition-all duration-300">
                    {{-- Options Header --}}
                    <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/40"
                        x-show="type === 'pilihan_ganda' || type === 'pilihan_ganda_kompleks' || type === 'benar_salah'">
                        <div class="flex items-center gap-3.5">
                            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16m-7 6h7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 tracking-tight" x-text="type === 'benar_salah' ? 'Daftar Pernyataan' : 'Pilihan Jawaban'"><span
                                        class="text-red-500">*</span></h3>
                                <p class="text-xs text-slate-400 mt-0.5" x-text="type === 'benar_salah' ? 'Tuliskan pernyataan dan centang pernyataan yang bernilai BENAR' : 'Tentukan opsi jawaban dan tandai opsi yang benar'">
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="addOption()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-semibold rounded-lg transition-colors group">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Opsi
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/40"
                        x-show="type === 'menjodohkan'">
                        <div class="flex items-center gap-3.5">
                            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 tracking-tight">Pasangan Menjodohkan <span
                                        class="text-red-500">*</span></h3>
                                <p class="text-xs text-slate-400 mt-0.5">Hubungkan item kiri dengan jawaban benar di kanan
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <template x-if="type === 'pilihan_ganda' || type === 'pilihan_ganda_kompleks' || type === 'benar_salah'">
                            <div class="space-y-4">
                                <template x-for="(option, index) in options" :key="option.id">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex gap-4 p-4 rounded-xl border border-slate-200/70 bg-white group relative hover:border-indigo-200 hover:shadow-sm transition-all duration-300"
                                            :class="touched.options && !option.hasContent ? 'ring-2 ring-red-500/20 border-red-500 bg-white' : ''">

                                            {{-- Selector (Radio / Checkbox) --}}
                                            <div class="flex-shrink-0 pt-2">
                                                <label class="relative flex items-center cursor-pointer group">
                                                    <input :type="(type === 'pilihan_ganda') ? 'radio' : 'checkbox'"
                                                        name="correct_answer_proxy" :checked="option.is_correct"
                                                        @change="setCorrect(index)"
                                                        class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-offset-0 focus:ring-2 focus:ring-indigo-100 transition-all cursor-pointer"
                                                        :class="(type === 'pilihan_ganda') ? 'rounded-full' : 'rounded'">
                                                    <input type="hidden" :name="`options[${option.label}][is_correct]`"
                                                        :value="option.is_correct ? 1 : 0">
                                                </label>
                                            </div>

                                            {{-- Label badge (A, B, C...) --}}
                                            <div class="flex-shrink-0 pt-0.5">
                                                <div class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-bold shadow-sm border transition-colors duration-200"
                                                    :class="option.is_correct 
                                                        ? 'bg-indigo-600 text-white border-indigo-600' 
                                                        : 'bg-slate-50 text-slate-600 border-slate-200 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-100'"
                                                    x-text="option.label"></div>
                                            </div>

                                            {{-- Summernote Editor Wrapper --}}
                                            <div class="flex-grow max-w-[calc(100%-110px)]">
                                                <div
                                                    class="rounded-lg border border-slate-200 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-50 overflow-hidden transition-all bg-white">
                                                    <textarea :id="`editor-opt-${option.id}`"
                                                        :name="`options[${option.label}][content]`"
                                                        x-init="initSummernoteOption($el, index)" class="hidden"></textarea>
                                                </div>
                                            </div>

                                            {{-- Remove button --}}
                                            <button type="button" @click="removeOption(index)" x-show="options.length > 2"
                                                class="flex-shrink-0 p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all h-fit self-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                        <template x-if="touched.options && !option.hasContent">
                                            <p class="text-[11px] font-medium text-red-600 ml-16 animate-pulse">Konten untuk
                                                pilihan <span x-text="option.label"></span> wajib diisi</p>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Menjodohkan (Matching) --}}
                        <template x-if="type === 'menjodohkan'">
                            <div class="space-y-4">
                                <template x-for="(pair, index) in matchingPairs" :key="pair.id">
                                    <div
                                        class="p-5 rounded-xl border border-slate-200 bg-slate-50/20 space-y-4 relative group hover:border-slate-300 hover:bg-white transition-all duration-300">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 flex items-center justify-center bg-indigo-50 border border-indigo-100 rounded-lg text-xs font-bold text-indigo-600"
                                                    x-text="index + 1"></div>
                                                <span class="text-xs font-semibold text-slate-600">Pasangan Ke-<span
                                                        x-text="index + 1"></span></span>
                                            </div>
                                            <button type="button" @click="removeMatchPair(index)"
                                                x-show="matchingPairs.length > 1"
                                                class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="space-y-2">
                                                <label
                                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Item
                                                    Kiri (Premis)</label>
                                                <div
                                                    class="rounded-lg border border-slate-200 overflow-hidden focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-50 transition-all bg-white">
                                                    <textarea :name="`match_options[${index}][left]`"
                                                        x-init="initSummernoteMatch($el, index, 'left')"
                                                        class="hidden"></textarea>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label
                                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Item
                                                    Kanan (Kunci)</label>
                                                <div
                                                    class="rounded-lg border border-slate-200 overflow-hidden focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-50 transition-all bg-white">
                                                    <textarea :name="`match_options[${index}][right]`"
                                                        x-init="initSummernoteMatch($el, index, 'right')"
                                                        class="hidden"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="addMatchPair()"
                                    class="w-full py-3.5 border border-dashed border-slate-300 rounded-xl text-slate-500 text-xs font-semibold hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50/20 transition-all flex items-center justify-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Pasangan Baru
                                </button>
                            </div>
                        </template>

                        <template x-if="touched.correctSelection && !hasCorrectSelection">
                            <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-center gap-3 mt-4">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-xs font-semibold text-red-600">Pilih setidaknya satu opsi sebagai jawaban
                                    yang benar!</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Isian Singkat Card --}}
                <div x-show="type === 'isian_singkat'" x-transition
                    class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/40">
                        <div class="flex items-center gap-3.5">
                            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 tracking-tight">Kunci Jawaban Isian Singkat <span class="text-red-500">*</span></h3>
                                <p class="text-xs text-slate-400 mt-0.5">Tentukan satu atau beberapa alternatif kunci jawaban yang benar (case-insensitive)</p>
                            </div>
                        </div>
                        <button type="button" @click="addIsianAnswer()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-semibold rounded-lg transition-colors group">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Kunci
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <template x-for="(ans, index) in isianAnswers" :key="ans.id">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-indigo-50 border border-indigo-100 text-indigo-600 shrink-0" x-text="index + 1"></div>
                                <input type="text" name="isian_answers[]" x-model="ans.content" required
                                    class="flex-grow border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 py-2.5 transition-all bg-slate-50/30"
                                    placeholder="Ketik alternatif kunci jawaban...">
                                <button type="button" @click="removeIsianAnswer(index)" x-show="isianAnswers.length > 1"
                                    class="p-2.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Explanation Card --}}
                <div
                    class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden transition-all duration-300">
                    <div class="p-5 border-b border-slate-100 flex items-center gap-3.5 bg-slate-50/40">
                        <div class="w-9 h-9 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800 tracking-tight">Penjelasan / Pembahasan</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Berikan ulasan jawaban lengkap untuk ditampilkan pasca
                                ujian</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div
                            class="rounded-xl border border-slate-200 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-indigo-100 focus-within:border-indigo-500 transition-all duration-200">
                            <textarea name="explanation" id="editor-explanation"
                                class="hidden">{{ old('explanation') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Settings (1/4) --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden sticky top-6">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/40">
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Atribut Ujian / Soal</h3>
                    </div>
                    <div class="p-5 space-y-5">
                        {{-- Subject --}}
                        <div class="space-y-1.5" x-data="{ localTouched: false }">
                            <label class="text-xs font-semibold text-slate-600">Mata Pelajaran <span
                                    class="text-red-500">*</span></label>
                            <select name="subject_id" x-model="subject_id"
                                @blur="localTouched = true; touched.subject_id = true" required
                                class="w-full border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 py-2.5 transition-all bg-slate-50/30 cursor-pointer"
                                :style="(localTouched || touched.subject_id) && !subject_id ? 'border-color: #ef4444 !important; box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1) !important;' : (subject_id ? 'border-color: #10b981 !important;' : '')">
                                <option value="">Pilih Mapel...</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                        {{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="(localTouched || touched.subject_id) && !subject_id">
                                <p class="text-[11px] font-semibold text-red-600 ml-1">Mata pelajaran wajib dipilih</p>
                            </template>
                        </div>

                        {{-- Type --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-600">Tipe Evaluasi</label>
                            <select name="type" x-model="type" required
                                class="w-full border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 py-2.5 transition-all bg-slate-50/30 cursor-pointer">
                                <option value="pilihan_ganda">Pilihan Ganda</option>
                                <option value="pilihan_ganda_kompleks">Pilihan Ganda Kompleks</option>
                                <option value="benar_salah">Benar / Salah</option>
                                <option value="menjodohkan">Menjodohkan</option>
                                <option value="isian_singkat">Isian Singkat</option>
                                <option value="essai">Essai (Grading Manual)</option>
                            </select>
                        </div>

                        {{-- Difficulty --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-600 block">Tingkat Kesulitan</label>
                            <div class="grid grid-cols-3 gap-1.5">
                                <template x-for="level in ['mudah', 'sedang', 'sulit']">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="difficulty" :value="level" x-model="difficulty"
                                            class="peer hidden">
                                        <div class="py-2 text-center rounded-lg border text-xs font-semibold transition-all capitalize"
                                            :class="{
                                                'bg-emerald-50 border-emerald-300 text-emerald-700': difficulty === level && level === 'mudah',
                                                'bg-amber-50 border-amber-300 text-amber-700': difficulty === level && level === 'sedang',
                                                'bg-rose-50 border-rose-300 text-rose-700': difficulty === level && level === 'sulit',
                                                'border-slate-200 text-slate-600 bg-white hover:bg-slate-50': difficulty !== level
                                             }" x-text="level"></div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Points --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-slate-600">Poin Benar</label>
                                <input type="number" name="points" step="0.5" x-model="points"
                                    class="w-full border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 py-2.5 bg-slate-50/30 text-center">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-slate-600">Poin Salah</label>
                                <input type="number" name="negative_points" step="0.5" x-model="negative_points"
                                    class="w-full border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-rose-100 focus:border-rose-400 py-2.5 bg-slate-50/30 text-center text-rose-600">
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-4 border-t border-slate-100 space-y-2">
                            <button type="submit"
                                class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24 animate-pulse">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Soal
                            </button>
                            <a href="{{ route('admin.questions.index') }}"
                                class="w-full py-3 bg-slate-50 hover:bg-slate-100 text-slate-500 text-center rounded-xl text-xs font-bold uppercase tracking-wider transition-all block">
                                Batalkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Global function for Summernote image upload
        function uploadImageSummernote(file, editorTarget) {
            let data = new FormData();
            data.append("upload", file);

            $.ajax({
                url: '{{ route('admin.questions.upload-image') }}',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    $(editorTarget).summernote('insertImage', response.url);
                },
                error: function (data) {
                    console.error("Summernote image upload failed:", data);
                    alert("Upload gambar gagal!");
                }
            });
        }

        function questionForm() {
            return {
                subject_id: '{{ old('subject_id', request()->query('subject_id', '')) }}',
                type: '{{ old('type', 'pilihan_ganda') }}',
                difficulty: '{{ old('difficulty', 'sedang') }}',
                points: '{{ old('points', 1) }}',
                negative_points: '{{ old('negative_points', 0) }}',
                hasContent: false,
                hasCorrectSelection: true,
                touched: {
                    subject_id: false,
                    content: false,
                    options: false,
                    correctSelection: false
                },
                options: [
                    { id: 1, label: 'A', is_correct: true, hasContent: false },
                    { id: 2, label: 'B', is_correct: false, hasContent: false },
                    { id: 3, label: 'C', is_correct: false, hasContent: false },
                    { id: 4, label: 'D', is_correct: false, hasContent: false },
                    { id: 5, label: 'E', is_correct: false, hasContent: false },
                ],
                matchingPairs: [
                    { id: 1, left: '', right: '' },
                    { id: 2, left: '', right: '' },
                ],
                isianAnswers: [
                    { id: Date.now(), content: '' }
                ],
                labels: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],

                init() {
                    this.initEditors();
                    this.$watch('type', () => { this.validateSelection(); });
                },

                getEditorConfig(placeholder, height, onUpdate) {
                    return {
                        placeholder: placeholder,
                        height: height,
                        tabsize: 2,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph', 'align']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ],
                        fontNames: ['Plus Jakarta Sans', 'Arial', 'Courier New', 'Georgia', 'Times New Roman'],
                        callbacks: {
                            onImageUpload: function (files) {
                                uploadImageSummernote(files[0], this);
                            },
                            onChange: (contents) => {
                                if (onUpdate) onUpdate(contents);
                            }
                        }
                    };
                },

                initEditors() {
                    // Main Question
                    $('#editor-main').summernote(this.getEditorConfig('Ketik pertanyaan di sini...', 300, (contents) => {
                        this.hasContent = $(contents).text().trim().length > 0 || contents.includes('<img');
                    }));

                    // Explanation
                    $('#editor-explanation').summernote(this.getEditorConfig('Tuliskan pembahasan di sini...', 180));
                },

                initSummernoteOption(el, index) {
                    const id = `editor-opt-${this.options[index].id}`;
                    $(el).attr('id', id);

                    $(el).summernote(this.getEditorConfig('Isi pilihan...', 100, (contents) => {
                        this.options[index].hasContent = $(contents).text().trim().length > 0 || contents.includes('<img');
                    }));
                },

                initSummernoteMatch(el, index, side) {
                    const id = `editor-match-${index}-${side}`;
                    $(el).attr('id', id);

                    this.$nextTick(() => {
                        $(el).summernote(this.getEditorConfig(side === 'left' ? 'Isi premis...' : 'Isi jawaban...', 100));
                    });
                },

                validateSelection() {
                    if (this.type === 'benar_salah') {
                        this.hasCorrectSelection = true;
                    } else {
                        this.hasCorrectSelection = this.options.some(opt => opt.is_correct);
                    }
                },

                addOption() {
                    if (this.options.length < this.labels.length) {
                        const newId = Date.now();
                        const nextLabel = this.labels[this.options.length];
                        this.options.push({ id: newId, label: nextLabel, is_correct: false, hasContent: false });
                    }
                },

                addMatchPair() {
                    this.matchingPairs.push({ id: Date.now(), left: '', right: '' });
                },

                addIsianAnswer() {
                    this.isianAnswers.push({ id: Date.now(), content: '' });
                },

                removeIsianAnswer(index) {
                    if (this.isianAnswers.length > 1) {
                        this.isianAnswers.splice(index, 1);
                    }
                },

                removeOption(index) {
                    if (this.options.length > 2) {
                        const id = `editor-opt-${this.options[index].id}`;
                        $(`#${id}`).summernote('destroy');
                        this.options.splice(index, 1);
                        this.options.forEach((opt, idx) => opt.label = this.labels[idx]);
                        this.validateSelection();
                    }
                },

                removeMatchPair(index) {
                    if (this.matchingPairs.length > 1) {
                        const idLeft = `editor-match-${index}-left`;
                        const idRight = `editor-match-${index}-right`;
                        $(`#${idLeft}`).summernote('destroy');
                        $(`#${idRight}`).summernote('destroy');
                        this.matchingPairs.splice(index, 1);
                    }
                },

                setCorrect(index) {
                    if (this.type === 'pilihan_ganda') {
                        this.options.forEach((opt, idx) => opt.is_correct = (idx === index));
                    } else {
                        this.options[index].is_correct = !this.options[index].is_correct;
                    }
                    this.validateSelection();
                },

                submitForm(e) {
                    this.touched.subject_id = true;
                    this.touched.content = true;
                    this.touched.options = true;
                    this.touched.correctSelection = true;

                    // Scrub content for validation
                    this.hasContent = $($('#editor-main').summernote('code')).text().trim().length > 0 || $('#editor-main').summernote('code').includes('<img');

                    this.options.forEach((opt) => {
                        const id = `editor-opt-${opt.id}`;
                        const code = $(`#${id}`).summernote('code');
                        opt.hasContent = $(code).text().trim().length > 0 || code.includes('<img');
                    });

                    this.validateSelection();

                    const isInvalid = !this.subject_id ||
                        !this.hasContent ||
                        (this.type !== 'essai' && this.type !== 'isian_singkat' && this.options.some(opt => !opt.hasContent)) ||
                        (this.type !== 'essai' && this.type !== 'isian_singkat' && !this.hasCorrectSelection) ||
                        (this.type === 'isian_singkat' && this.isianAnswers.some(ans => !ans.content.trim()));

                    if (isInvalid) {
                        e.preventDefault();
                        if (!this.hasContent) window.scrollTo({ top: 0, behavior: 'smooth' });
                        return false;
                    }
                }
            }
        }
    </script>
    <style>
        .note-editor.note-frame {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            overflow: hidden !important;
            background: white !important;
            box-shadow: none !important;
        }

        .note-toolbar {
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 6px 10px !important;
        }

        .note-btn {
            background: white !important;
            border: 1px solid #e2e8f0 !important;
            color: #64748b !important;
            padding: 4px 8px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            transition: all 0.2s !important;
            border-radius: 6px !important;
        }

        .note-btn:hover {
            background: #f1f5f9 !important;
            color: #4f46e5 !important;
            border-color: #cbd5e1 !important;
        }

        .note-btn.active {
            background: #e0e7ff !important;
            color: #4f46e5 !important;
            border-color: #c7d2fe !important;
        }

        .note-editable {
            color: #334155 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 13px !important;
            padding: 16px !important;
            line-height: 1.6 !important;
        }

        .note-statusbar {
            display: none !important;
        }
    </style>
@endpush