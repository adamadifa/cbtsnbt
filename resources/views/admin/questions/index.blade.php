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
        <a href="{{ route('admin.questions.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#153c96] hover:underline mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
               <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
               <path d="M5 12l14 0"></path>
               <path d="M5 12l6 6"></path>
               <path d="M5 12l6 -6"></path>
            </svg>
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

    <div x-data="{ showImportWordModal: false }">
        <!-- Filters & Search Toolbar (Outside the Card) -->
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6">
            <form action="{{ route('admin.questions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                
                <div class="w-full md:flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        class="block w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-100 text-xs transition-all focus:outline-none" 
                        placeholder="Cari konten soal...">
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <select name="type" onchange="this.form.submit()" class="block w-full md:w-44 py-2 bg-slate-50 border-none rounded-xl text-slate-600 focus:bg-white focus:ring-2 focus:ring-blue-100 text-xs transition-all focus:outline-none">
                        <option value="">Semua Tipe</option>
                        <option value="pilihan_ganda" @selected(request('type') == 'pilihan_ganda')>Pilihan Ganda</option>
                        <option value="pilihan_ganda_kompleks" @selected(request('type') == 'pilihan_ganda_kompleks')>PG Kompleks</option>
                        <option value="essai" @selected(request('type') == 'essai')>Essai</option>
                    </select>

                    <button type="submit" class="w-full md:w-auto px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shrink-0">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Questions Table Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Card Header (Unified Blue Bar) -->
            <div class="bg-[#153c96] text-white px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M8 8m-5 0a5 5 0 1 0 10 0a5 5 0 1 0 -10 0"></path>
                            <path d="M8 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                            <path d="M2 8h4"></path>
                            <path d="M10 8h4"></path>
                            <path d="M8 2h1"></path>
                            <path d="M8 10h1"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm tracking-wide">Daftar Soal: {{ $selectedSubj->name ?? '' }}</h3>
                        <p class="text-[10px] text-white/70">Kelola bank soal ujian secara detail dan sistematis</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Bulk Delete Button (hidden by default) -->
                    <button id="bulk-delete-btn" style="display: none;" onclick="confirmBulkDelete()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-750 text-white rounded-xl font-bold text-xs shadow-sm transition-all animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 7l16 0"></path>
                            <path d="M10 11l0 6"></path>
                            <path d="M14 11l0 6"></path>
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                        </svg>
                        Hapus Terpilih (<span id="bulk-delete-count">0</span>)
                    </button>

                    <button @click="showImportWordModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold text-xs border border-white/15 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                            <path d="M7 9l5 -5l5 5"></path>
                            <path d="M12 4l0 12"></path>
                        </svg>
                        Import Word
                    </button>

                    <a href="{{ route('admin.questions.create', ['subject_id' => request('subject_id')]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-blue-50 text-[#153c96] rounded-xl font-bold text-xs shadow-sm transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        Tambah Soal
                    </a>
                </div>
            </div>

            <!-- Questions Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#153c96] text-white select-none">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95 w-24 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="checkbox" id="select-all-checkbox" class="rounded border-slate-300 text-[#153c96] focus:ring-[#153c96] w-4 h-4 cursor-pointer bg-white/10">
                                    <span>No</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Konten Soal</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Materi Uji</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Tipe & Bobot</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($questions as $question)
                        <tr class="hover:bg-slate-50/20 transition-colors group">
                            <td class="px-6 py-4 text-center w-24">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="checkbox" value="{{ $question->id }}" class="question-checkbox rounded border-slate-300 text-[#153c96] focus:ring-[#153c96] w-4 h-4 cursor-pointer">
                                    <span class="text-xs font-bold text-slate-500">{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-xl">
                                <div class="text-sm font-medium text-slate-700 line-clamp-2 leading-relaxed">
                                    {!! strip_tags($question->content) !!}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-white text-[10px]" style="background-color: {{ $question->subject->color ?? '#6366f1' }}">
                                        {{ substr($question->subject->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-800">{{ $question->subject->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-tight w-fit bg-slate-100 text-slate-500">
                                        {{ str_replace('_', ' ', $question->type) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-[#153c96] bg-blue-50 px-2 py-0.5 rounded-md w-fit">{{ $question->points }} Poin</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.questions.show', $question) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                            <path d="M13.5 6.5l4 4"></path>
                                        </svg>
                                    </a>
                                    <form id="delete-form-{{ $question->id }}" action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="confirmDelete({{ $question->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 7l16 0"></path>
                                                <path d="M10 11l0 6"></path>
                                                <path d="M14 11l0 6"></path>
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="p-4 bg-slate-50 text-slate-300 rounded-2xl">
                                        📭
                                    </div>
                                    <p class="text-slate-400 font-bold">Belum ada soal ditemukan untuk materi uji ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($questions->hasPages())
            <div class="p-4 border-t border-slate-50">
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
                                <a href="{{ route('admin.questions.download-template') }}" class="text-[10px] font-bold text-[#153c96] hover:underline uppercase tracking-wider inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                        <path d="M7 11l5 5l5 -5"></path>
                                        <path d="M12 4l0 12"></path>
                                    </svg>
                                    UNDUH TEMPLATE WORD (.DOCX)
                                </a>
                            </div>
                            
                            <button type="submit" class="w-full py-3 bg-[#153c96] hover:bg-blue-700 text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-blue-500/25 transition-all">
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
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const questionCheckboxes = document.querySelectorAll('.question-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const bulkDeleteCount = document.getElementById('bulk-delete-count');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                questionCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
                updateBulkDeleteUI();
            });

            questionCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    // Update Select All state
                    const allChecked = Array.from(questionCheckboxes).every(c => c.checked);
                    const someChecked = Array.from(questionCheckboxes).some(c => c.checked);
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    updateBulkDeleteUI();
                });
            });
        }

        function updateBulkDeleteUI() {
            const checkedCount = document.querySelectorAll('.question-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteBtn.style.display = 'inline-flex';
                bulkDeleteCount.textContent = checkedCount;
            } else {
                bulkDeleteBtn.style.display = 'none';
                bulkDeleteCount.textContent = '0';
            }
        }
    });

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
        const count = checkedBoxes.length;

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `${count} soal yang dipilih akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl border border-slate-100',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider',
                cancelButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulk-delete-form');
                
                // Clear any existing dynamically added inputs
                form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

                // Append selected question IDs
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
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
            confirmButtonColor: '#153c96',
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
            confirmButtonColor: '#153c96',
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
            confirmButtonColor: '#153c96',
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
