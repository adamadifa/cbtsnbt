@extends('layouts.admin')

@section('page_title', 'Buat Paket Tryout Baru')

@section('content')
<div class="max-w-7xl mx-auto" x-data="packageForm()">
    <form action="{{ route('admin.exam-packages.store') }}" method="POST" enctype="multipart/form-data" @submit="submitForm($event)" novalidate>
        @csrf

        {{-- Flash Error --}}
        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-500 flex-shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                    <path d="M12 9v4"></path>
                    <path d="M12 16v.01"></path>
                </svg>
                <p class="text-sm font-bold text-rose-600">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                <p class="text-sm font-bold text-rose-600 mb-2">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside text-xs text-rose-500 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.exam-packages.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l14 0"></path>
                    <path d="M5 12l6 6"></path>
                    <path d="M5 12l6 -6"></path>
                </svg>
                Kembali ke Daftar Paket
            </a>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-[#153c96] hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-blue-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M14 4l0 4l-4 0l0 -4"></path>
                    </svg>
                    Simpan Paket
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Left Column: Content (3/4) --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Basic Details --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md"
                     :class="(touched.title && !title) || (touched.description && !description) ? 'ring-2 ring-rose-500 border-transparent' : ''">
                    <div class="p-6 border-b border-slate-50 flex items-center gap-3 bg-slate-50/20">
                        <div class="w-10 h-10 bg-[#153c96] text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0"></path>
                                <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0"></path>
                                <path d="M3 6l0 13"></path>
                                <path d="M12 6l0 13"></path>
                                <path d="M21 6l0 13"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 tracking-tight">Informasi Dasar Paket</h3>
                            <p class="text-[10px] text-slate-400">Judul dan deskripsi lengkap paket ujian tryout</p>
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
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md"
                     :class="touched.subtests && hasEmptySubject ? 'ring-2 ring-rose-500 border-transparent' : ''">
                    <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-100">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                     <path d="M4 4h6v6h-6z"></path>
                                     <path d="M14 4h6v6h-6z"></path>
                                     <path d="M4 14h6v6h-6z"></path>
                                     <path d="M14 14h6v6h-6z"></path>
                                 </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 tracking-tight">Konfigurasi Subtest <span class="text-rose-500 font-bold">*</span></h3>
                                <p class="text-[10px] text-slate-400">Susun bagian ujian dan alokasi waktunya</p>
                            </div>
                        </div>
                        <button type="button" @click="addSubtest()" class="p-2 bg-blue-50 text-[#153c96] rounded-xl hover:bg-blue-100 transition-colors group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <template x-for="(subtest, index) in subtests" :key="subtest.id">
                            <div class="p-4 rounded-2xl border border-slate-50 bg-slate-50/20 flex items-center gap-4 transition-all hover:bg-white hover:border-blue-100 group shadow-sm"
                                 :class="touched.subtests && !subtest.subject_id ? 'ring-2 ring-rose-500 border-transparent bg-white' : ''">
                                <div class="flex-shrink-0 w-8 h-8 bg-white border border-slate-100 rounded-lg flex items-center justify-center text-xs font-bold text-slate-400" x-text="index + 1"></div>
                                
                                <div class="flex-grow grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Subject -->
                                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] rounded-xl px-2.5 py-1 bg-white">
                                        <label class="absolute -top-2 left-2 px-1 bg-white text-[8px] font-extrabold text-slate-400 group-focus-within:text-[#153c96] uppercase">Materi Uji</label>
                                        <select :name="`subtests[${index}][subject_id]`" x-model="subtest.subject_id" required class="w-full bg-transparent border-0 p-0 text-xs text-slate-600 focus:ring-0 focus:outline-none py-1">
                                            <option value="">Pilih...</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Duration -->
                                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] rounded-xl px-2.5 py-1 bg-white">
                                        <label class="absolute -top-2 left-2 px-1 bg-white text-[8px] font-extrabold text-slate-400 group-focus-within:text-[#153c96] uppercase">Durasi (Menit)</label>
                                        <input type="number" :name="`subtests[${index}][duration_minutes]`" x-model="subtest.duration_minutes" required min="1" 
                                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 focus:ring-0 focus:outline-none py-1 text-center font-bold" />
                                    </div>

                                    <!-- Questions count -->
                                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] rounded-xl px-2.5 py-1 bg-white">
                                        <label class="absolute -top-2 left-2 px-1 bg-white text-[8px] font-extrabold text-slate-400 group-focus-within:text-[#153c96] uppercase">Jumlah Soal</label>
                                        <input type="number" :name="`subtests[${index}][total_questions]`" x-model="subtest.total_questions" required min="1" 
                                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 focus:ring-0 focus:outline-none py-1 text-center font-bold" />
                                        <input type="hidden" :name="`subtests[${index}][order]`" :value="index">
                                    </div>
                                </div>

                                <button type="button" @click="removeSubtest(index)" x-show="subtests.length > 1" class="flex-shrink-0 p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 7l16 0"></path>
                                        <path d="M10 11l0 6"></path>
                                        <path d="M14 11l0 6"></path>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <template x-if="touched.subtests && hasEmptySubject">
                            <p class="text-[10px] font-semibold text-rose-500 ml-1">Semua subtest harus memilih materi uji</p>
                        </template>

                        <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Waktu: <span class="text-[#153c96]" x-text="calculateTotalDuration()"></span> Menit</p>
                            <button type="button" @click="addSubtest()" class="text-xs font-bold text-[#153c96] hover:text-blue-800 flex items-center gap-1.5 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:scale-110" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                </svg>
                                Tambah Subtest
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Settings (1/4) --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden sticky top-8">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/20">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Atribut Paket</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Status --}}
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 ml-1">Status Publikasi</label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-50 bg-slate-50/20 cursor-pointer hover:bg-slate-50 transition-all">
                                <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-[#153c96] border-slate-200 rounded-lg focus:ring-4 focus:ring-blue-100">
                                <span class="text-xs font-bold text-slate-700">Aktifkan Paket</span>
                            </label>
                        </div>

                        {{-- Pricing --}}
                        <div class="space-y-4">
                            <label class="text-[11px] font-bold text-slate-500 ml-1">Jenis Akses</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="type" value="free" x-model="packageType" class="peer hidden">
                                    <div class="py-3 text-center rounded-xl border border-slate-100 text-[10px] font-bold uppercase tracking-widest transition-all peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 group-hover:bg-slate-50">Gratis</div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="type" value="premium" x-model="packageType" class="peer hidden">
                                    <div class="py-3 text-center rounded-xl border border-slate-100 text-[10px] font-bold uppercase tracking-widest transition-all peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600 group-hover:bg-slate-50">Premium</div>
                                </label>
                            </div>
                            
                            <div x-show="packageType === 'premium'" x-transition class="relative">
                                <div class="group border border-slate-200 focus-within:border-[#153c96] rounded-xl px-3 py-1.5 flex items-center gap-2">
                                    <label for="price" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-500 group-focus-within:text-[#153c96] transition-all duration-200">Harga (IDR)</label>
                                    <input type="number" name="price" id="price" placeholder="50000" class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1 font-bold" />
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-6 border-t border-slate-50 space-y-3">
                            <button type="submit" class="w-full py-3 bg-[#153c96] hover:bg-blue-800 text-white rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/10 active:scale-95 transition-all flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                Simpan Paket
                            </button>
                            <a href="{{ route('admin.exam-packages.index') }}" class="w-full py-3 bg-slate-50 text-slate-500 text-center rounded-2xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-all block border border-slate-100">
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
