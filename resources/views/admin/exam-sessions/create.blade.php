@extends('layouts.admin')

@section('page_title', 'Jadwalkan Sesi Ujian')

@section('content')
<div class="max-w-7xl mx-auto" x-data="sessionForm()">
    <form action="{{ route('admin.exam-sessions.store') }}" method="POST" @submit="submitForm($event)" novalidate>
        @csrf
        
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.exam-sessions.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-blue-600 transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l14 0"></path>
                    <path d="M5 12l6 6"></path>
                    <path d="M5 12l6 -6"></path>
                </svg>
                Kembali
            </a>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M14 4l0 4l-4 0l0 -4"></path>
                    </svg>
                    Simpan Sesi
                </button>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-5 p-4 bg-rose-50 border border-rose-100 rounded-xl">
                <p class="text-xs font-bold text-rose-600 mb-1">Terjadi kesalahan input:</p>
                <ul class="list-disc list-inside text-[11px] text-rose-500 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Left Column: Content (3/4) --}}
            <div class="lg:col-span-3 space-y-5">
                {{-- Main Configuration --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md"
                     :class="touched.title && !title ? 'ring-2 ring-rose-500 border-transparent' : ''">
                    <div class="p-5 border-b border-slate-50 flex items-center gap-3 bg-slate-50/20">
                        <div class="w-9 h-9 bg-[#153c96] text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                <path d="M16 3l0 4"></path>
                                <path d="M8 3l0 4"></path>
                                <path d="M4 11l16 0"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Detail Penjadwalan</h3>
                            <p class="text-[10px] text-slate-400">Tentukan waktu dan identitas sesi ujian</p>
                        </div>
                    </div>
                    
                    <div class="p-5 space-y-6">
                        <!-- Title -->
                        <div class="relative">
                            <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                                 :class="touched.title && !title ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                                <label for="title" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                                       :class="touched.title && !title ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-[#153c96]'">Nama Sesi Ujian <span class="text-rose-500 font-bold">*</span></label>
                                <input type="text" name="title" id="title" x-model="title" @blur="touched.title = true" required placeholder="E.g., Tryout Mandiri Vol. 1" 
                                       class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1" />
                            </div>
                            <template x-if="touched.title && !title">
                                <p class="text-[10px] font-semibold text-rose-500 mt-1 ml-1">Nama sesi wajib diisi</p>
                            </template>
                        </div>

                        <!-- Date Time grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Start Time -->
                            <div class="relative">
                                <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                                     :class="touched.startTime && !startTime ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                                    <label for="start_time" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                                           :class="touched.startTime && !startTime ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-[#153c96]'">Waktu Mulai <span class="text-rose-500 font-bold">*</span></label>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                        <path d="M16 3l0 4"></path>
                                        <path d="M8 3l0 4"></path>
                                        <path d="M4 11l16 0"></path>
                                    </svg>
                                    <input type="text" id="start_time" name="start_time" x-model="startTime" required placeholder="Pilih Tanggal & Waktu"
                                           class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-350 focus:ring-0 focus:outline-none py-1 cursor-pointer" />
                                </div>
                            </div>

                            <!-- End Time -->
                            <div class="relative">
                                <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                                     :class="touched.endTime && (!endTime || endTime <= startTime) ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                                    <label for="end_time" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                                           :class="touched.endTime && (!endTime || endTime <= startTime) ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-[#153c96]'">Waktu Selesai <span class="text-rose-500 font-bold">*</span></label>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                        <path d="M12 7l0 5l3 3"></path>
                                    </svg>
                                    <input type="text" id="end_time" name="end_time" x-model="endTime" required placeholder="Pilih Tanggal & Waktu"
                                           class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-350 focus:ring-0 focus:outline-none py-1 cursor-pointer" />
                                </div>
                                <template x-if="touched.endTime && endTime && endTime <= startTime">
                                    <p class="text-[9px] font-semibold text-rose-500 mt-1 ml-1">Waktu selesai harus setelah waktu mulai</p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Access Token --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
                    <div class="p-5 border-b border-slate-50 flex items-center gap-3 bg-slate-50/20">
                        <div class="w-9 h-9 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-amber-100">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M15 7a2 2 0 0 1 2 2m4 0a6 6 0 0 1 -7.743 5.743L11 17H9v2H7v2H4a1 1 0 0 1 -1 -1v-2.586a1 1 0 0 1 .293-.707l5.964-5.964A6 6 0 1 1 21 9z"></path>
                             </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Keamanan & Token</h3>
                            <p class="text-[10px] text-slate-400">Kode unik akses pengerjaan sesi tryout</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-col md:flex-row items-center gap-5">
                            <div class="relative flex-1 w-full">
                                <div class="group border border-slate-200 focus-within:border-[#153c96] rounded-xl px-3 py-1.5 flex items-center gap-2">
                                    <label for="token" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-500 group-focus-within:text-[#153c96] transition-all duration-200">Token Akses (Opsional)</label>
                                    <input type="text" name="token" id="token" x-model="token" placeholder="AUTO-GENERATE" maxlength="10"
                                           class="w-full bg-transparent border-0 p-0 text-base font-black tracking-widest text-[#153c96] focus:ring-0 focus:outline-none py-1 uppercase text-center md:text-left" />
                                </div>
                            </div>
                            <div class="flex-1 w-full p-4 bg-blue-50/50 border border-blue-100 rounded-xl">
                                <p class="text-[10px] font-bold text-blue-700 leading-relaxed uppercase tracking-wider flex items-center gap-1.5">
                                    ℹ️ Tip: Biarkan kosong untuk generate kode acak 6 digit secara otomatis oleh sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Settings (1/4) --}}
            <div class="lg:col-span-1 space-y-5">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden sticky top-5">
                    <div class="p-5 border-b border-slate-50 bg-slate-50/20">
                        <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">Konfigurasi</h3>
                    </div>
                    <div class="p-5 space-y-5">
                        {{-- Package Selection --}}
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 ml-1">Pilih Paket Ujian <span class="text-rose-500 font-bold">*</span></label>
                            <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1 flex flex-col gap-2 custom-scrollbar">
                                @foreach($packages as $pkg)
                                    <label class="relative flex items-center p-3 rounded-xl border border-slate-100 cursor-pointer transition-all hover:bg-slate-50 group"
                                           :class="selectedPackage == {{ $pkg->id }} ? 'bg-blue-50 ring-2 ring-blue-100 border-transparent shadow-sm' : 'bg-white'">
                                        <input type="radio" name="exam_package_id" value="{{ $pkg->id }}" x-model="selectedPackage" @change="touched.selectedPackage = true" class="hidden">
                                        <div class="flex-shrink-0 w-7 h-7 rounded-lg bg-[#153c96] flex items-center justify-center text-white mr-3 shadow-sm" x-show="selectedPackage == {{ $pkg->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M5 12l5 5l10 -10"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-shrink-0 w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-350 mr-3 border border-slate-100" x-show="selectedPackage != {{ $pkg->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 3l8 4.5l-8 4.5l-8 -4.5z"></path>
                                                <path d="M12 12l8 4.5l-8 4.5l-8 -4.5z"></path>
                                                <path d="M12 21l8 -4.5l-8 -4.5l-8 4.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 pr-2">
                                            <p class="text-[11px] font-bold text-slate-800 truncate" title="{{ $pkg->title }}">{{ $pkg->title }}</p>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-[8px] font-black px-1.5 py-0.5 rounded uppercase bg-slate-100 text-slate-500 border border-slate-200">{{ $pkg->type }}</span>
                                                <span class="text-[8px] font-bold text-slate-400 capitalize">{{ $pkg->total_questions }} Soal</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <template x-if="touched.selectedPackage && !selectedPackage">
                                <p class="text-[9px] font-semibold text-rose-500 ml-1">Wajib pilih paket ujian</p>
                            </template>
                        </div>

                        {{-- Participants --}}
                        <div class="relative">
                            <div class="group border border-slate-200 focus-within:border-[#153c96] rounded-xl px-3 py-1.5 flex items-center gap-2">
                                <label for="max_participants" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-500 group-focus-within:text-[#153c96]">Limit Peserta</label>
                                <input type="number" name="max_participants" id="max_participants" placeholder="Tak Terbatas" 
                                       class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1 text-center font-bold" />
                            </div>
                        </div>

                        {{-- Final Actions --}}
                        <div class="pt-4 border-t border-slate-100">
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200/80 bg-slate-50/20 cursor-pointer hover:bg-slate-50 transition-all">
                                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 border-slate-350 rounded focus:ring-indigo-500">
                                <span class="text-xs font-semibold text-slate-700">Aktifkan Sesi</span>
                            </label>
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
    function sessionForm() {
        return {
            title: '',
            startTime: '',
            endTime: '',
            token: '',
            selectedPackage: null,
            touched: {
                title: false,
                startTime: false,
                endTime: false,
                selectedPackage: false
            },

            init() {
                this.$nextTick(() => {
                    const self = this;
                    
                    // Initialize Flatpickr for Start Time
                    flatpickr("#start_time", {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        minDate: "today",
                        onChange: function(selectedDates, dateStr) {
                            self.startTime = dateStr;
                            self.touched.startTime = true;
                        }
                    });

                    // Initialize Flatpickr for End Time
                    flatpickr("#end_time", {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        minDate: "today",
                        onChange: function(selectedDates, dateStr) {
                            self.endTime = dateStr;
                            self.touched.endTime = true;
                        }
                    });
                });
            },

            submitForm(e) {
                this.touched.title = true;
                this.touched.startTime = true;
                this.touched.endTime = true;
                this.touched.selectedPackage = true;

                const isInvalid = !this.title || !this.startTime || !this.endTime || !this.selectedPackage || (this.endTime <= this.startTime);

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
