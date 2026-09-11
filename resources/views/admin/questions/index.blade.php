@extends('layouts.admin')

@section('page_title', 'Bank Soal')

@section('content')

@if(!request('subject_id'))
    <!-- ========================================== -->
    <!-- DEFAULT STATE: SHOW SUBJECT CARDS GRID    -->
    <!-- ========================================== -->
    
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Bank Soal</h1>
            <p class="text-xs text-slate-400 mt-1">Pilih kategori materi uji di bawah ini untuk mengelola bank soal.</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-400 self-start sm:self-center bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
            </svg>
            <span>/</span>
            <span>Dashboard</span>
            <span>/</span>
            <span class="text-slate-650 font-semibold">Bank Soal</span>
        </div>
    </div>

    <!-- Subject Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($subjects as $subj)
            @php
                $cardColor = $subj->color ?? '#6366f1';
            @endphp
            <a href="{{ route('admin.questions.index', ['subject_id' => $subj->id]) }}" 
               class="flex flex-col justify-between p-5 rounded-[24px] text-white transition-all duration-300 hover:shadow-xl hover:scale-[1.02] group relative overflow-hidden h-44 shadow-sm"
               style="background-color: {{ $cardColor }};">
                
                <!-- Floating Glow Spot for depth -->
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white/10 filter blur-xl transition-all duration-500 group-hover:scale-150"></div>
                
                <!-- Action Chevron Arrow (Top Right) -->
                <div class="absolute top-5 right-5 text-white/50 group-hover:text-white transition-all duration-300 group-hover:translate-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M9 6l6 6l-6 6"></path>
                    </svg>
                </div>
                
                <div class="space-y-4">
                    <!-- Icon / Initial -->
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black shadow-sm transition-all duration-500 bg-white group-hover:rotate-6 group-hover:scale-105" 
                         style="color: {{ $cardColor }}">
                        {{ substr($subj->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-bold leading-snug line-clamp-1 pr-6 text-white">{{ $subj->name }}</h3>
                        <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase bg-white/15 border border-white/10 text-white/90 mt-1.5 inline-block">
                            {{ $subj->component }}
                        </span>
                    </div>
                </div>
                
                <!-- Footer stats -->
                <div class="flex items-center justify-between border-t border-white/10 pt-3 mt-3">
                    <span class="text-[10px] font-semibold text-white/70">Total Soal</span>
                    <span class="text-xs font-black px-2.5 py-1 rounded-xl transition-all duration-300 bg-white/15 border border-white/10 text-white group-hover:bg-white group-hover:text-slate-800">
                        {{ $subj->questions_count }} Soal
                    </span>
                </div>
            </a>
        @endforeach
    </div>

@else
    <!-- ========================================== -->
    <!-- DETAIL STATE: SHOW QUESTIONS OF SUBJECT   -->
    <!-- ========================================== -->
    @php 
        $selectedSubj = $subjects->firstWhere('id', request('subject_id')); 
    @endphp

    <!-- Page Header with Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.questions.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-orange-600 hover:text-orange-700 hover:underline mb-3">
            <i class="ti ti-arrow-left text-sm"></i>
            Kembali ke Materi Uji
        </a>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-5 h-5 rounded-lg flex items-center justify-center font-extrabold text-white text-[10px] shadow-sm" style="background-color: {{ $selectedSubj->color ?? '#6366f1' }}">
                        {{ substr($selectedSubj->name ?? 'M', 0, 1) }}
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Bank Soal: {{ $selectedSubj->name ?? '' }}</h1>
                </div>
                <p class="text-xs text-slate-400 mt-1">Mengelola daftar pertanyaan untuk materi uji {{ $selectedSubj->name ?? '' }}.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-400 self-start sm:self-center bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm">
                <span>Dashboard</span>
                <span>/</span>
                <span>Bank Soal</span>
                <span>/</span>
                <span class="text-slate-650 font-semibold leading-none">{{ $selectedSubj->name ?? '' }}</span>
            </div>
        </div>
    </div>

    <div x-data="{
        showImportWordModal: false,
        selectedQuestions: [],
        allQuestionIds: {{ json_encode($questions->pluck('id')->toArray()) }},
        get allSelected() {
            return this.allQuestionIds.length > 0 && this.allQuestionIds.every(id => this.selectedQuestions.includes(id));
        },
        toggleSelectAll() {
            if (this.allSelected) {
                this.selectedQuestions = [];
            } else {
                this.selectedQuestions = [...this.allQuestionIds];
            }
        }
    }">
        <!-- Filters & Search Toolbar (Cardless) -->
        <div class="mb-4">
            <form action="{{ route('admin.questions.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5 items-center justify-between">
                <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                
                <div class="w-full md:flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="ti ti-search text-base"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        class="block w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200/80 rounded-xl text-slate-800 placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all focus:outline-none shadow-2xs" 
                        placeholder="Cari konten soal...">
                </div>

                <div class="flex items-center gap-2.5 w-full md:w-auto shrink-0">
                    <select name="type" onchange="this.form.submit()" class="py-2.5 pl-3 pr-8 bg-white border border-slate-200/80 rounded-xl text-slate-700 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all shadow-2xs">
                        <option value="">Semua Tipe</option>
                        <option value="pilihan_ganda" @selected(request('type') == 'pilihan_ganda')>Pilihan Ganda</option>
                        <option value="pilihan_ganda_kompleks" @selected(request('type') == 'pilihan_ganda_kompleks')>PG Kompleks</option>
                        <option value="essai" @selected(request('type') == 'essai')>Essai</option>
                    </select>

                    <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shadow-2xs flex items-center justify-center gap-1.5">
                        <i class="ti ti-filter text-sm"></i>
                        <span>Filter</span>
                    </button>

                    @if(request('search') || request('type'))
                        <a href="{{ route('admin.questions.index', ['subject_id' => request('subject_id')]) }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all shadow-2xs">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Questions Cards Container -->
        <div class="space-y-2.5">
            <!-- Top Action & Selection Bar -->
            <div class="bg-orange-500 text-white px-5 py-3.5 rounded-2xl shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <!-- Select All Checkbox -->
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" 
                               @change="toggleSelectAll()" 
                               :checked="allSelected" 
                               class="w-4 h-4 rounded border-white/40 text-orange-600 focus:ring-0 focus:ring-offset-0 bg-white/20 checked:bg-white checked:border-white transition-all cursor-pointer">
                        <span class="text-xs font-bold tracking-wide">Pilih Semua</span>
                    </label>
                    <span class="text-white/40">|</span>
                    <span class="text-[11px] text-white/85 font-medium">
                        Total: <strong class="text-white">{{ $questions->total() }}</strong> soal
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Bulk Delete Button (appears when items are selected) -->
                    <button type="button" 
                            x-show="selectedQuestions.length > 0" 
                            @click="confirmBulkDeleteQuestions(selectedQuestions)"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all"
                            x-cloak>
                        <i class="ti ti-trash text-sm"></i>
                        <span>Hapus (<span x-text="selectedQuestions.length"></span>)</span>
                    </button>

                    <button @click="showImportWordModal = true" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-xl font-bold text-xs border border-white/20 transition-all">
                        <i class="ti ti-file-text text-sm"></i>
                        <span>Import Word</span>
                    </button>

                    <a href="{{ route('admin.questions.create', ['subject_id' => request('subject_id')]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-orange-50 text-orange-600 rounded-xl font-bold text-xs shadow-sm transition-all">
                        <i class="ti ti-plus text-sm"></i>
                        <span>Tambah Soal</span>
                    </a>
                </div>
            </div>

            <!-- List of Questions (Compact Full-Width Cards) -->
            <div class="space-y-2">
                @forelse($questions as $question)
                @php
                    $itemIndex = ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration;
                @endphp
                <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-sm hover:border-orange-300 transition-all px-4 py-3 flex flex-col md:flex-row md:items-center justify-between gap-3 group"
                     :class="selectedQuestions.includes({{ $question->id }}) ? 'border-orange-400 bg-orange-50/20 ring-1 ring-orange-200' : ''">
                    
                    <!-- Left: Checkbox, Number & Question Content -->
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <div class="flex items-center gap-2.5 pt-0.5 shrink-0">
                            <input type="checkbox" 
                                   value="{{ $question->id }}" 
                                   x-model.number="selectedQuestions"
                                   class="w-4 h-4 rounded border-slate-300 text-orange-600 focus:ring-orange-400 transition-all cursor-pointer">
                            <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 font-bold text-[11px] flex items-center justify-center shrink-0">
                                {{ $itemIndex }}
                            </span>
                        </div>

                        <!-- Question Content Snippet -->
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('admin.questions.show', $question) }}" class="text-xs font-semibold text-slate-800 hover:text-orange-600 transition-colors line-clamp-2 leading-relaxed">
                                {!! strip_tags($question->content) !!}
                            </a>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-tight bg-slate-100 text-slate-600">
                                    {{ str_replace('_', ' ', $question->type) }}
                                </span>
                                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-md">
                                    +{{ $question->points }} Poin
                                </span>
                                @if($question->difficulty)
                                    @php
                                        $diffColors = [
                                            'mudah' => 'text-emerald-700 bg-emerald-50',
                                            'sedang' => 'text-amber-700 bg-amber-50',
                                            'sulit' => 'text-rose-700 bg-rose-50',
                                        ];
                                    @endphp
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md capitalize {{ $diffColors[$question->difficulty] ?? 'text-slate-600 bg-slate-100' }}">
                                        {{ $question->difficulty }}
                                    </span>
                                @endif
                                @if($question->passageGroup)
                                    <span class="text-[10px] text-blue-600 bg-blue-50 font-semibold px-2 py-0.5 rounded-md flex items-center gap-1">
                                        <i class="ti ti-files text-xs"></i>
                                        {{ Str::limit($question->passageGroup->title, 20) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right: Subject Indicator & Action Buttons -->
                    <div class="flex items-center justify-between md:justify-end gap-3 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md flex items-center justify-center font-bold text-white text-[9px] shrink-0" 
                                 style="background-color: {{ $question->subject->color ?? '#6366f1' }}">
                                {{ substr($question->subject->name ?? 'S', 0, 1) }}
                            </div>
                            <span class="text-xs font-bold text-slate-700 truncate max-w-[130px]">{{ $question->subject->name ?? '' }}</span>
                        </div>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.questions.show', $question) }}" 
                               class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-orange-600 hover:bg-orange-50 transition-colors" 
                               title="Lihat Detail">
                                <i class="ti ti-eye text-base"></i>
                            </a>

                            <a href="{{ route('admin.questions.edit', $question) }}" 
                               class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" 
                               title="Edit Soal">
                                <i class="ti ti-edit text-base"></i>
                            </a>

                            <form id="delete-form-{{ $question->id }}" action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        @click="confirmDelete({{ $question->id }})" 
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" 
                                        title="Hapus Soal">
                                    <i class="ti ti-trash text-base"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-notes-off text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800">Belum ada soal ditemukan</h4>
                    <p class="text-xs text-slate-400 mt-1">Silakan tambahkan butir soal atau lakukan import file Word.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($questions->hasPages())
            <div class="pt-3">
                {{ $questions->links() }}
            </div>
            @endif
        </div>

        <!-- Import Word Modal -->
        <div x-show="showImportWordModal" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm" x-cloak>
            <div @click.away="showImportWordModal = false" class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 transform transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-bold text-slate-800 uppercase tracking-wider">Import Soal Word</h3>
                        <button @click="showImportWordModal = false" class="text-slate-400 hover:text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M18 6l-12 12"></path>
                                <path d="M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl mb-5">
                        <p class="text-[11px] font-semibold text-blue-700 leading-relaxed uppercase tracking-wider">
                            Pilih file Microsoft Word (.docx) untuk meng-import bank soal secara massal ke materi uji: <span class="bg-blue-600 text-white px-1.5 py-0.5 rounded font-bold">{{ $selectedSubj->name ?? '' }}</span>
                        </p>
                    </div>

                    <form action="{{ route('admin.questions.import-word') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                        
                        <div class="space-y-4">
                            <!-- File Input -->
                            <div class="relative group">
                                <input type="file" name="word_file" id="word_file_input" required accept=".docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('wordFileNameDetail').textContent = this.files[0].name; document.getElementById('wordFileNameDetail').classList.remove('text-slate-400'); document.getElementById('wordFileNameDetail').classList.add('text-indigo-600')">
                                <div class="p-6 border-2 border-dashed border-slate-200 group-hover:border-blue-400 group-hover:bg-blue-50/20 rounded-2xl transition-all text-center">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-600 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path>
                                            <path d="M9 15l3 -3l3 3"></path>
                                            <path d="M12 12l0 9"></path>
                                        </svg>
                                    </div>
                                    <p id="wordFileNameDetail" class="text-xs font-bold text-slate-600">Klik atau seret berkas .docx ke sini</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Hanya mendukung format .docx</p>
                                </div>
                            </div>

                            <div class="mt-2 text-left">
                                <a href="{{ route('admin.questions.download-template') }}" class="text-[10px] font-bold text-orange-600 hover:text-orange-700 hover:underline uppercase tracking-wider inline-flex items-center gap-1">
                                    <i class="ti ti-download text-sm"></i>
                                    UNDUH TEMPLATE WORD (.DOCX)
                                </a>
                            </div>
                            
                            <button type="submit" class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center gap-2">
                                <i class="ti ti-cloud-upload text-base"></i>
                                Mulai Proses Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Hidden Bulk Delete Form -->
        <form id="bulk-delete-form" action="{{ route('admin.questions.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmBulkDeleteQuestions(questionIds) {
        if (!questionIds || questionIds.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + questionIds.length + ' Soal Terpilih?',
            text: "Semua soal yang Anda pilih akan dihapus secara permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus terpilih!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl border border-slate-100',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider',
                cancelButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulk-delete-form');
                form.innerHTML = '@csrf';
                questionIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'question_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                form.submit();
            }
        });
    }

    function confirmDelete(questionId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Soal ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl border border-slate-100',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider',
                cancelButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + questionId).submit();
            }
        })
    }

    // Show flash message alert if exists
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#f97316',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'rounded-3xl border border-slate-100',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-xs'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#f97316',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'rounded-3xl border border-slate-100',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-xs'
            }
        });
    @endif
</script>
@endpush
@endsection
