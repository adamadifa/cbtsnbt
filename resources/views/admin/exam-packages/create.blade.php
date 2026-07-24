@extends('layouts.admin')

@section('page_title', 'Buat Paket Tryout Baru')

@section('content')
<div class="max-w-6xl mx-auto" x-data="packageForm()">
    <form action="{{ route('admin.exam-packages.store') }}" method="POST" enctype="multipart/form-data" @submit="submitForm($event)" novalidate>
        @csrf

        {{-- Flash Alerts --}}
        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-rose-700">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-rose-700">Terdapat kesalahan pengisian form:</p>
                </div>
                <ul class="list-disc list-inside text-xs text-rose-600 space-y-1 pl-11">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- Elegant Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('admin.exam-packages.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#153c96] transition-colors mb-2 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    Kembali ke Daftar Paket
                </a>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Buat Paket Tryout Baru</h1>
                <p class="text-xs text-slate-500 mt-0.5">Konfigurasikan judul, tipe akses, dan rincian subtest tryout Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 bg-[#153c96] hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-md shadow-blue-500/10 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <circle cx="12" cy="14" r="2" />
                        <polyline points="14 4 14 8 10 8 10 4" />
                    </svg>
                    Simpan Paket
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            {{-- Left Column: Main Configuration --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Basic Details --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md"
                     :class="(touched.title && !title) || (touched.description && !description) ? 'ring-2 ring-rose-500 border-transparent' : ''">
                    <div class="p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                        <div class="w-9 h-9 bg-blue-50 text-[#153c96] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                <line x1="3" y1="6" x2="3" y2="19" />
                                <line x1="12" y1="6" x2="12" y2="19" />
                                <line x1="21" y1="6" x2="21" y2="19" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Informasi Dasar Paket</h3>
                            <p class="text-[10px] text-slate-400">Tentukan judul dan deskripsi lengkap dari paket ujian</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Title -->
                        <div class="relative">
                            <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                                 :class="touched.title && !title ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                                <label for="title" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                                       :class="touched.title && !title ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-[#153c96]'">Judul Paket <span class="text-rose-500 font-bold">*</span></label>
                                <input type="text" name="title" id="title" x-model="title" @blur="touched.title = true" required placeholder="Contoh: Tryout Akbar Nasional Vol. 1" 
                                       class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1" />
                            </div>
                            <template x-if="touched.title && !title">
                                <p class="text-[10px] font-semibold text-rose-500 mt-1 ml-1">Judul paket wajib diisi</p>
                            </template>
                        </div>

                        <!-- Description -->
                        <div class="relative">
                            <div class="group border rounded-xl px-3 py-1.5 flex items-start gap-2 transition-all duration-200"
                                 :class="touched.description && !description ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                                <label for="description" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                                       :class="touched.description && !description ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-[#153c96]'">Deskripsi <span class="text-rose-500 font-bold">*</span></label>
                                <textarea name="description" id="description" x-model="description" @blur="touched.description = true" rows="4" required placeholder="Jelaskan detail paket ujian ini..." 
                                          class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1 resize-none"></textarea>
                            </div>
                            <template x-if="touched.description && !description">
                                <p class="text-[10px] font-semibold text-rose-500 mt-1 ml-1">Deskripsi paket wajib diisi</p>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Subtest Builder --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md"
                     :class="touched.subtests && hasEmptySubject ? 'ring-2 ring-rose-500 border-transparent' : ''">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <rect x="4" y="4" width="6" height="6" rx="1" />
                                     <rect x="14" y="4" width="6" height="6" rx="1" />
                                     <rect x="4" y="14" width="6" height="6" rx="1" />
                                     <rect x="14" y="14" width="6" height="6" rx="1" />
                                 </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Konfigurasi Subtest <span class="text-rose-500 font-bold">*</span></h3>
                                <p class="text-[10px] text-slate-400">Susun materi uji beserta durasi dan alokasi soal</p>
                            </div>
                        </div>
                        <button type="button" @click="addSubtest()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-[#153c96] hover:bg-blue-100 rounded-xl text-xs font-bold transition-all group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah Subtest
                        </button>
                    </div>

                    {{-- Summary Statistics Bar --}}
                    <div class="bg-slate-50/50 border-b border-slate-100 px-6 py-3 flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Total Subtest:</span>
                                <span class="text-xs font-extrabold text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-200" x-text="subtests.length"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Total Durasi:</span>
                                <span class="text-xs font-extrabold text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-200"><span x-text="calculateTotalDuration()"></span> Menit</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Total Soal:</span>
                                <span class="text-xs font-extrabold text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-200" x-text="calculateTotalQuestions()"></span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <template x-for="(subtest, index) in subtests" :key="subtest.id">
                            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/30 flex flex-col md:flex-row md:items-center gap-4 transition-all hover:bg-white hover:border-blue-200 group shadow-sm relative"
                                 :class="touched.subtests && !subtest.subject_id ? 'ring-2 ring-rose-500 border-transparent bg-white' : ''">
                                
                                {{-- Row Index Indicator --}}
                                <div class="flex-shrink-0 w-7 h-7 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-xs font-extrabold text-slate-500 shadow-sm" x-text="index + 1"></div>
                                
                                <div class="flex-grow grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <!-- Subject -->
                                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] rounded-xl px-2.5 py-1 bg-white md:col-span-2">
                                        <label class="absolute -top-2 left-2 px-1 bg-white text-[8px] font-extrabold text-slate-400 group-focus-within:text-[#153c96]">Materi Uji</label>
                                        <select :name="`subtests[${index}][subject_id]`" x-model="subtest.subject_id" required class="w-full bg-transparent border-0 p-0 text-xs text-slate-600 focus:ring-0 focus:outline-none py-1">
                                            <option value="">Pilih...</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Duration -->
                                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] rounded-xl px-2.5 py-1 bg-white">
                                        <label class="absolute -top-2 left-2 px-1 bg-white text-[8px] font-extrabold text-slate-400 group-focus-within:text-[#153c96]">Durasi (Menit)</label>
                                        <input type="number" :name="`subtests[${index}][duration_minutes]`" x-model="subtest.duration_minutes" required min="1" 
                                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 focus:ring-0 focus:outline-none py-1 text-center font-bold" />
                                    </div>

                                    <!-- Questions count -->
                                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] rounded-xl px-2.5 py-1 bg-white">
                                        <label class="absolute -top-2 left-2 px-1 bg-white text-[8px] font-extrabold text-slate-400 group-focus-within:text-[#153c96]">Jumlah Soal</label>
                                        <input type="number" :name="`subtests[${index}][total_questions]`" x-model="subtest.total_questions" required min="1" 
                                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 focus:ring-0 focus:outline-none py-1 text-center font-bold" />
                                        <input type="hidden" :name="`subtests[${index}][order]`" :value="index">
                                    </div>
                                </div>

                                {{-- Delete Button --}}
                                <button type="button" @click="removeSubtest(index)" x-show="subtests.length > 1" 
                                        class="flex-shrink-0 p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 border border-slate-200 hover:border-rose-100 bg-white rounded-xl transition-all shadow-sm self-end md:self-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        <line x1="10" y1="11" x2="10" y2="17" />
                                        <line x1="14" y1="11" x2="14" y2="17" />
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <template x-if="touched.subtests && hasEmptySubject">
                            <p class="text-[10px] font-semibold text-rose-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                Semua subtest harus memiliki pilihan materi uji.
                            </p>
                        </template>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                            <button type="button" @click="addSubtest()" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#153c96] hover:text-blue-800 transition-colors group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:scale-110" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                Tambah Subtest
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Settings / Attributes Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-6">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Atribut Paket</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Status Switch Toggle --}}
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Status Publikasi</label>
                            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/30 cursor-pointer hover:bg-slate-50 transition-all">
                                <span class="text-xs font-bold text-slate-700">Aktifkan Paket</span>
                                <div class="relative inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                                </div>
                            </label>
                        </div>

                        {{-- Pricing Visual Selection --}}
                        <div class="space-y-4">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Jenis Akses</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="free" x-model="packageType" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/30 hover:bg-slate-50 transition-all text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 mb-1 transition-colors" :class="packageType === 'free' ? 'text-emerald-600' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="8" width="18" height="12" rx="2" />
                                            <path d="M12 5a3 3 0 1 0-3 3h6a3 3 0 1 0-3-3z" />
                                        </svg>
                                        <span class="text-[10px] font-bold text-slate-700">Gratis</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="premium" x-model="packageType" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 peer-checked:border-amber-600 peer-checked:bg-amber-50/20 hover:bg-slate-50 transition-all text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 mb-1 transition-colors" :class="packageType === 'premium' ? 'text-amber-600' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                        <span class="text-[10px] font-bold text-slate-700">Premium</span>
                                    </div>
                                </label>
                            </div>
                            
                            <div x-show="packageType === 'premium'" x-transition class="relative">
                                <div class="group border border-slate-200 focus-within:border-[#153c96] rounded-xl px-3 py-1.5 flex items-center gap-2 bg-white">
                                    <label for="price" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-500 group-focus-within:text-[#153c96] transition-all duration-200">Harga (IDR)</label>
                                    <input type="number" name="price" id="price" placeholder="50000" class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1 font-bold" />
                                </div>
                            </div>
                        </div>

                        {{-- Results & Explanation Settings --}}
                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Fitur Hasil & Pembahasan</label>
                            
                            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/30 cursor-pointer hover:bg-slate-50 transition-all">
                                <span class="text-xs font-bold text-slate-700">Tampilkan Hasil</span>
                                <div class="relative inline-flex items-center">
                                    <input type="checkbox" name="show_result" value="1" checked class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                </div>
                            </label>
                            
                            <label class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/30 cursor-pointer hover:bg-slate-50 transition-all">
                                <span class="text-xs font-bold text-slate-700">Tampilkan Pembahasan</span>
                                <div class="relative inline-flex items-center">
                                    <input type="checkbox" name="show_explanation" value="1" checked class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                </div>
                            </label>
                        </div>

                        {{-- Actions Button Panel --}}
                        <div class="pt-5 border-t border-slate-100 space-y-2.5">
                            <button type="submit" class="w-full py-2.5 bg-[#153c96] hover:bg-blue-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md shadow-blue-500/10 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                                Simpan Paket
                            </button>
                            <a href="{{ route('admin.exam-packages.index') }}" class="w-full py-2.5 bg-slate-50 text-slate-500 text-center rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-all block border border-slate-200">
                                Batalkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function packageForm() {
        return {
            title: '{{ old("title") }}',
            description: '{{ old("description") }}',
            packageType: '{{ old("type", "free") }}',
            hasEmptySubject: false,
            subtests: [
                { id: Date.now(), subject_id: '', duration_minutes: 30, total_questions: 15 }
            ],
            touched: {
                title: false,
                description: false,
                subtests: false
            },

            addSubtest() {
                this.subtests.push({
                    id: Date.now() + Math.random(),
                    subject_id: '',
                    duration_minutes: 30,
                    total_questions: 15
                });
            },

            removeSubtest(index) {
                if (this.subtests.length > 1) {
                    this.subtests.splice(index, 1);
                }
            },

            calculateTotalDuration() {
                return this.subtests.reduce((total, sub) => total + (parseInt(sub.duration_minutes) || 0), 0);
            },

            calculateTotalQuestions() {
                return this.subtests.reduce((total, sub) => total + (parseInt(sub.total_questions) || 0), 0);
            },

            submitForm(e) {
                this.touched.title = true;
                this.touched.description = true;
                this.touched.subtests = true;

                this.hasEmptySubject = this.subtests.some(s => !s.subject_id);

                const isInvalid = !this.title || !this.description || this.hasEmptySubject;

                if (isInvalid) {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return false;
                }
            }
        }
    }
</script>
@endpush

