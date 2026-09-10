<!-- Create Subject Modal -->
<div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm" x-cloak>
    <div @click.away="showCreateModal = false" class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden border border-slate-100 transform transition-all"
         x-data="{
             name: '',
             code: '',
             component: 'umum',
             order: '0',
             description: '',
             color: '#6366f1',
             isCodeManual: false,
             errors: { name: '', code: '' },
             touched: { name: false, code: false },
             validateName() {
                 this.errors.name = this.name.trim() === '' ? 'Nama materi uji wajib diisi' : '';
                 if (!this.isCodeManual && this.name) {
                     this.code = this.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                 }
             },
             validateCode() {
                 this.errors.code = this.code.trim() === '' ? 'Kode materi uji wajib diisi' : '';
             },
             checkSubmit(e) {
                 this.touched.name = true;
                 this.touched.code = true;
                 this.validateName();
                 this.validateCode();
                 if (this.errors.name || this.errors.code) {
                     e.preventDefault();
                 }
             }
         }">
        
        <!-- Modal Header -->
        <div class="bg-slate-50 border-b border-slate-100 px-6 py-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800 tracking-wide">Tambah Materi Uji</h3>
                <p class="text-[11px] text-slate-400 mt-1">Lengkapi data materi uji baru untuk ditambahkan ke sistem.</p>
            </div>
            <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 transition-colors shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M18 6l-12 12"></path>
                    <path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.subjects.store') }}" method="POST" @submit="checkSubmit" novalidate class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="relative">
                        <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                             :class="touched.name && errors.name ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50'">
                            <label for="create_name" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold transition-all duration-200"
                                   :class="touched.name && errors.name ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-orange-600'">Nama Materi Uji <span class="text-rose-500 font-bold">*</span></label>
                            <i class="ti ti-book text-slate-400 shrink-0 text-base"></i>
                            <input id="create_name" name="name" type="text"
                                   x-model="name"
                                   @input="validateName"
                                   @blur="touched.name = true; validateName()"
                                   class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1" 
                                   placeholder="Contoh: Penalaran Matematika" />
                        </div>
                        <p x-show="touched.name && errors.name" x-text="errors.name" class="text-[10px] text-rose-500 font-semibold mt-1 ml-1" x-cloak></p>
                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                    </div>

                    <!-- Code -->
                    <div class="relative">
                        <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                             :class="touched.code && errors.code ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50'">
                            <label for="create_code" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold transition-all duration-200"
                                   :class="touched.code && errors.code ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-orange-600'">Kode Materi <span class="text-rose-500 font-bold">*</span></label>
                            <i class="ti ti-id text-slate-400 shrink-0 text-base"></i>
                            <input id="create_code" name="code" type="text"
                                   x-model="code"
                                   @input="isCodeManual = true; validateCode()"
                                   @blur="touched.code = true; validateCode()"
                                   class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1"
                                   placeholder="Contoh: PM-001" />
                        </div>
                        <p x-show="touched.code && errors.code" x-text="errors.code" class="text-[10px] text-rose-500 font-semibold mt-1 ml-1" x-cloak></p>
                        <x-input-error class="mt-1" :messages="$errors->get('code')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Component -->
                    <div class="group relative border border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50 rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all">
                        <label for="create_component" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-500 group-focus-within:text-orange-600 transition-all duration-200">Komponen</label>
                        <i class="ti ti-category text-slate-400 shrink-0 text-base"></i>
                        <select id="create_component" name="component" x-model="component" class="w-full bg-transparent border-0 p-0 text-xs text-slate-600 focus:ring-0 focus:outline-none py-1">
                            <option value="TPS">TPS</option>
                            <option value="Literasi">Literasi</option>
                        </select>
                    </div>

                    <!-- Order -->
                    <div class="group relative border border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50 rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all">
                        <label for="create_order" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-500 group-focus-within:text-orange-600 transition-all duration-200">Urutan Tampilan</label>
                        <i class="ti ti-sort-ascending-numbers text-slate-400 shrink-0 text-base"></i>
                        <input id="create_order" name="order" type="number" x-model="order"
                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1" />
                    </div>
                </div>

                <!-- Description -->
                <div class="group relative border border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50 rounded-xl px-3 py-1.5 flex items-start gap-2 transition-all">
                    <label for="create_description" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-500 group-focus-within:text-orange-600 transition-all duration-200">Deskripsi</label>
                    <i class="ti ti-notes text-slate-400 shrink-0 text-base mt-1"></i>
                    <textarea id="create_description" name="description" rows="3" x-model="description"
                              class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1 resize-none"
                              placeholder="Penjelasan singkat mengenai materi uji ini..."></textarea>
                </div>

                <!-- Color Accentuation -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Warna Aksentuasi</label>
                    <div class="flex flex-wrap items-center gap-4 py-1.5">
                        @php
                            $colors = [
                                'orange' => '#f97316',
                                'indigo' => '#6366f1',
                                'blue' => '#3b82f6',
                                'emerald' => '#10b981',
                                'rose' => '#f43f5e',
                                'violet' => '#8b5cf6',
                                'amber' => '#f59e0b',
                                'sky' => '#0ea5e9',
                                'slate' => '#64748b'
                            ];
                        @endphp
                        <input type="hidden" name="color" :value="color">
                        @foreach ($colors as $name => $hex)
                            <button type="button" @click="color = '{{ $hex }}'" 
                                     class="w-8 h-8 rounded-xl border-2 transition-all hover:scale-110 flex items-center justify-center" 
                                     :class="color === '{{ $hex }}' ? 'border-orange-500 scale-105 shadow-sm ring-2 ring-orange-200' : 'border-transparent'"
                                     style="background-color: {{ $hex }}">
                                <span x-show="color === '{{ $hex }}'" class="text-white text-[10px]">✓</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 text-xs tracking-wider flex items-center gap-2">
                        <i class="ti ti-check text-sm"></i>
                        SIMPAN MATERI UJI
                    </button>
                    <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-xs transition-colors">
                        BATAL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
