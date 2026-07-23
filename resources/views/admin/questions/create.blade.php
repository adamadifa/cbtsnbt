@extends('layouts.admin')

@section('page_title', 'Tambah Soal Baru')

@section('content')
<div class="max-w-7xl mx-auto" x-data="questionForm()">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.questions.index') }}" class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 hover:text-indigo-600 transition-colors group uppercase tracking-widest">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Bank Soal
        </a>
    </div>

    <form action="{{ route('admin.questions.store') }}" method="POST" id="main-form" class="grid grid-cols-1 lg:grid-cols-4 gap-8" @submit="submitForm($event)" novalidate>
        @csrf
        
        {{-- Left Column: Content (3/4) --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Question Content Card --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md"
                 :class="touched.content && !hasContent ? 'ring-2 ring-red-500 border-transparent' : ''">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-indigo-100">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 tracking-tight">Konten Utama Soal <span class="text-red-500">*</span></h3>
                            <p class="text-[10px] text-slate-400 font-bold">Tuliskan pertanyaan atau stimulus soal</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="rounded-2xl border border-slate-100 overflow-hidden bg-slate-50/50 focus-within:ring-4 focus-within:ring-indigo-50 focus-within:border-indigo-200 transition-all">
                        <textarea name="content" id="editor-main" class="hidden">{{ old('content') }}</textarea>
                    </div>
                    <template x-if="touched.content && !hasContent">
                        <p class="text-[10px] font-bold text-red-600 mt-2 ml-1 animate-in fade-in slide-in-from-top-1">Konten soal wajib diisi</p>
                    </template>
                </div>
            </div>

            {{-- Options Card --}}
            <div x-show="type !== 'essai'" x-transition class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
                {{-- Options Header --}}
                <div class="flex items-center justify-between p-6 border-b border-slate-50" x-show="type === 'pilihan_ganda' || type === 'pilihan_ganda_kompleks'">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 rounded-xl">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 tracking-tight">Pilihan Jawaban <span class="text-red-500">*</span></h3>
                            <p class="text-[10px] text-slate-400 font-bold">Gunakan Summernote untuk menyisipkan gambar di opsi</p>
                        </div>
                    </div>
                    <button type="button" @click="addOption()" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 transition-colors group">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>
                
                <div class="flex items-center justify-between p-6 border-b border-slate-50" x-show="type === 'menjodohkan'">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 rounded-xl">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 tracking-tight">Pasangan Menjodohkan <span class="text-red-500">*</span></h3>
                            <p class="text-[10px] text-slate-400 font-bold">Hubungkan item kiri dengan jawaban benar di kanan</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <template x-if="type === 'pilihan_ganda' || type === 'pilihan_ganda_kompleks'">
                        <div class="space-y-4">
                            <template x-for="(option, index) in options" :key="option.id">
                                <div class="flex flex-col gap-2">
                                    <div class="flex gap-4 p-4 rounded-2xl border border-slate-50 bg-slate-50/30 group relative hover:bg-white transition-all duration-300 shadow-sm"
                                         :class="touched.options && !option.hasContent ? 'ring-2 ring-red-500 border-transparent bg-white' : 'hover:border-indigo-100 hover:shadow-lg'">
                                        <div class="flex-shrink-0 pt-1">
                                            <label class="relative flex items-center cursor-pointer group">
                                                <input :type="type === 'pilihan_ganda' ? 'radio' : 'checkbox'" 
                                                       name="correct_answer_proxy"
                                                       :checked="option.is_correct"
                                                       @change="setCorrect(index)"
                                                       class="w-6 h-6 text-indigo-600 border-slate-200 rounded-lg focus:ring-offset-0 focus:ring-4 focus:ring-indigo-50 transition-all">
                                                <input type="hidden" :name="`options[${option.label}][is_correct]`" :value="option.is_correct ? 1 : 0">
                                            </label>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 flex items-center justify-center bg-white border border-slate-100 rounded-xl text-xs font-black text-indigo-600 shadow-sm" x-text="option.label"></div>
                                        </div>
                                        <div class="flex-grow max-w-[calc(100%-120px)]">
                                            <div class="rounded-xl border border-transparent focus-within:border-indigo-200 overflow-hidden transition-all bg-white shadow-sm">
                                                <textarea :id="`editor-opt-${option.id}`" 
                                                          :name="`options[${option.label}][content]`" 
                                                          x-init="initSummernoteOption($el, index)"
                                                          class="hidden"></textarea>
                                            </div>
                                        </div>
                                        <button type="button" @click="removeOption(index)" x-show="options.length > 2" class="flex-shrink-0 p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all h-fit self-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    <template x-if="touched.options && !option.hasContent">
                                        <p class="text-[9px] font-bold text-red-600 ml-14 animate-pulse">Isi pilihan <span x-text="option.label"></span> wajib diisi</p>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Menjodohkan (Matching) --}}
                    <template x-if="type === 'menjodohkan'">
                        <div class="space-y-4">
                            <template x-for="(pair, index) in matchingPairs" :key="pair.id">
                                <div class="p-4 rounded-2xl border border-slate-50 bg-slate-50/20 space-y-4 relative group hover:bg-white transition-all duration-300 shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 flex items-center justify-center bg-indigo-600 rounded-lg text-[10px] font-black text-white" x-text="index + 1"></div>
                                            <span class="text-[11px] font-bold text-slate-400">Pasangan Ke- <span x-text="index + 1"></span></span>
                                        </div>
                                        <button type="button" @click="removeMatchPair(index)" x-show="matchingPairs.length > 1" class="p-1.5 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Item Kiri (Premis)</label>
                                            <div class="rounded-xl border border-slate-100 overflow-hidden focus-within:border-indigo-200 transition-all bg-white shadow-sm">
                                                <textarea :name="`match_options[${index}][left]`" 
                                                          x-init="initSummernoteMatch($el, index, 'left')"
                                                          class="hidden"></textarea>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-indigo-600 uppercase tracking-wider">Item Kanan (Kunci)</label>
                                            <div class="rounded-xl border border-indigo-50 overflow-hidden focus-within:border-indigo-200 transition-all bg-white shadow-sm">
                                                <textarea :name="`match_options[${index}][right]`" 
                                                          x-init="initSummernoteMatch($el, index, 'right')"
                                                          class="hidden"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <button type="button" @click="addMatchPair()" class="w-full py-3 border-2 border-dashed border-slate-100 rounded-2xl text-slate-400 text-[11px] font-bold hover:border-indigo-200 hover:text-indigo-600 hover:bg-indigo-50/30 transition-all flex items-center justify-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Pasangan Baru
                            </button>
                        </div>
                    </template>

                    <template x-if="touched.correctSelection && !hasCorrectSelection">
                        <div class="p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 animate-bounce mt-4">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <p class="text-xs font-bold text-red-600">Pilih setidaknya satu jawaban yang benar!</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Explanation Card --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
                <div class="p-6 border-b border-slate-50 flex items-center gap-3 bg-slate-50/30">
                    <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-amber-100">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 tracking-tight">Penjelasan / Pembahasan</h3>
                        <p class="text-[10px] text-slate-400 font-bold">Informasi kunci untuk siswa setelah ujian selesai</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="rounded-2xl border border-slate-100 overflow-hidden bg-slate-50/50 focus-within:ring-4 focus-within:ring-indigo-50 focus-within:border-indigo-200 transition-all">
                        <textarea name="explanation" id="editor-explanation" class="hidden">{{ old('explanation') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Settings (1/4) --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden sticky top-8">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Atribut Soal</h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Subject --}}
                    <div class="space-y-2" x-data="{ localTouched: false }">
                        <label class="text-[11px] font-bold text-slate-500 ml-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select name="subject_id" x-model="subject_id" @blur="localTouched = true; touched.subject_id = true" required 
                                class="w-full border-slate-100 rounded-xl text-xs font-bold focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 py-3 transition-all bg-slate-50/50 cursor-pointer"
                                :style="(localTouched || touched.subject_id) && !subject_id ? 'border-color: #ef4444 !important; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;' : (subject_id ? 'border-color: #10b981 !important; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;' : '')">
                            <option value="">Pilih Mapel...</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <template x-if="(localTouched || touched.subject_id) && !subject_id">
                            <p class="text-[10px] font-bold text-red-600 ml-1">Mata pelajaran wajib dipilih</p>
                        </template>
                    </div>

                    {{-- Type --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 ml-1">Tipe Evaluasi</label>
                        <select name="type" x-model="type" required 
                                class="w-full border-slate-100 rounded-xl text-xs font-bold focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 py-3 transition-all bg-slate-50/50 cursor-pointer">
                            <option value="pilihan_ganda">Pilihan Ganda</option>
                            <option value="pilihan_ganda_kompleks">Pilihan Ganda Kompleks</option>
                            <option value="menjodohkan">Menjodohkan</option>
                            <option value="essai">Essai / Isian</option>
                        </select>
                    </div>

                    {{-- Difficulty --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 ml-1">Tingkat Kesulitan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="level in ['mudah', 'sedang', 'sulit']">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="difficulty" :value="level" x-model="difficulty" class="peer hidden">
                                    <div class="py-2 text-center rounded-lg border border-slate-100 text-[10px] font-bold transition-all peer-checked:bg-slate-800 peer-checked:text-white peer-checked:border-slate-800 group-hover:bg-slate-50 capitalize" x-text="level"></div>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Points --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 ml-1">Poin Benar</label>
                            <input type="number" name="points" step="0.5" x-model="points" class="w-full border-slate-100 rounded-xl text-xs font-black focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 py-3 transition-all bg-slate-50/50 text-center">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 ml-1">Poin Salah</label>
                            <input type="number" name="negative_points" step="0.5" x-model="negative_points" class="w-full border-slate-100 rounded-xl text-xs font-black focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 py-3 transition-all bg-slate-50/50 text-center text-rose-500">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-6 border-t border-slate-50 space-y-3">
                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Soal
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 text-center rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all block">
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
            success: function(response) {
                $(editorTarget).summernote('insertImage', response.url);
            },
            error: function(data) {
                console.error("Summernote image upload failed:", data);
                alert("Upload gambar gagal!");
            }
        });
    }

    function questionForm() {
        return {
            subject_id: '{{ old('subject_id', '') }}',
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
                        onImageUpload: function(files) {
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
                $('#editor-main').summernote(this.getEditorConfig('Ketik pertanyaan di sini...', 350, (contents) => {
                    this.hasContent = $(contents).text().trim().length > 0 || contents.includes('<img');
                }));

                // Explanation
                $('#editor-explanation').summernote(this.getEditorConfig('Tuliskan pembahasan di sini...', 200));
            },

            initSummernoteOption(el, index) {
                const id = `editor-opt-${this.options[index].id}`;
                $(el).attr('id', id);
                
                $(el).summernote(this.getEditorConfig('Isi pilihan...', 120, (contents) => {
                    this.options[index].hasContent = $(contents).text().trim().length > 0 || contents.includes('<img');
                }));
            },

            initSummernoteMatch(el, index, side) {
                const id = `editor-match-${index}-${side}`;
                $(el).attr('id', id);
                
                this.$nextTick(() => {
                    $(el).summernote(this.getEditorConfig(side === 'left' ? 'Isi premis...' : 'Isi jawaban...', 120));
                });
            },

            validateSelection() {
                this.hasCorrectSelection = this.options.some(opt => opt.is_correct);
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
                                  (this.type !== 'essai' && this.options.some(opt => !opt.hasContent)) ||
                                  (this.type !== 'essai' && !this.hasCorrectSelection);

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
    .note-editor.note-frame { border: 1px solid #f1f5f9 !important; border-radius: 12px !important; overflow: hidden !important; background: white !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; }
    .note-toolbar { background: #f8fafc !important; border-bottom: 1px solid #f1f5f9 !important; padding: 10px !important; }
    .note-btn { background: white !important; border: 1px solid #f1f5f9 !important; color: #64748b !important; padding: 5px 10px !important; font-size: 11px !important; font-weight: 700 !important; transition: all 0.2s !important; }
    .note-btn:hover { background: #f1f5f9 !important; color: #4f46e5 !important; }
    .note-btn.active { background: #f5f3ff !important; color: #6366f1 !important; border-color: #c4b5fd !important; }
    .note-editable { color: #334155 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 14px !important; padding: 20px !important; }
    .note-statusbar { display: none !important; }
</style>
@endpush
