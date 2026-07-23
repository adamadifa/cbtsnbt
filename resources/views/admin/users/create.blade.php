<!-- Create User Modal -->
<div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm" x-cloak>
    <div @click.away="showCreateModal = false" class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden border border-slate-100 transform transition-all"
         x-data="{
             name: '',
             email: '',
             role: 'siswa',
             school: '',
             password: '',
             errors: { name: '', email: '', password: '' },
             touched: { name: false, email: false, password: false },
             validateName() {
                 this.errors.name = this.name.trim() === '' ? 'Nama lengkap wajib diisi' : '';
             },
             validateEmail() {
                 const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                 if (this.email.trim() === '') {
                     this.errors.email = 'Alamat email wajib diisi';
                 } else if (!emailRegex.test(this.email)) {
                     this.errors.email = 'Format email tidak valid';
                 } else {
                     this.errors.email = '';
                 }
             },
             validatePassword() {
                 if (this.password === '') {
                     this.errors.password = 'Password wajib diisi';
                 } else if (this.password.length < 8) {
                     this.errors.password = 'Password minimal 8 karakter';
                 } else {
                     this.errors.password = '';
                 }
             },
             checkSubmit(e) {
                 this.touched.name = true;
                 this.touched.email = true;
                 this.touched.password = true;
                 this.validateName();
                 this.validateEmail();
                 this.validatePassword();
                 if (this.errors.name || this.errors.email || this.errors.password) {
                     e.preventDefault();
                 }
             }
         }">
        
        <!-- Modal Header with Background and Description -->
        <div class="bg-slate-50 border-b border-slate-100 px-6 py-5 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800 tracking-wide">Tambah User Baru</h3>
                <p class="text-[11px] text-slate-400 mt-1">Lengkapi data identitas dan pengaturan hak akses di bawah ini untuk membuat akun baru.</p>
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
            <form action="{{ route('admin.users.store') }}" method="POST" @submit="checkSubmit" novalidate class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="relative">
                        <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                             :class="touched.name && errors.name ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                            <label for="create_name" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                                   :class="touched.name && errors.name ? 'text-rose-500 group-focus-within:text-rose-650' : 'text-slate-500 group-focus-within:text-[#153c96]'">Nama Lengkap <span class="text-rose-500 font-bold">*</span></label>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            </svg>
                            <input id="create_name" name="name" type="text"
                                   x-model="name"
                                   @input="validateName"
                                   @blur="touched.name = true; validateName()"
                                   class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1" 
                                   placeholder="Masukkan nama lengkap" />
                        </div>
                        <p x-show="touched.name && errors.name" x-text="errors.name" class="text-[10px] text-rose-500 font-semibold mt-1 ml-1" x-cloak></p>
                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                             :class="touched.email && errors.email ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                            <label for="create_email" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                                   :class="touched.email && errors.email ? 'text-rose-500 group-focus-within:text-rose-655' : 'text-slate-500 group-focus-within:text-[#153c96]'">Alamat Email <span class="text-rose-500 font-bold">*</span></label>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                                <path d="M3 7l9 6l9 -6"></path>
                            </svg>
                            <input id="create_email" name="email" type="email"
                                   x-model="email"
                                   @input="validateEmail"
                                   @blur="touched.email = true; validateEmail()"
                                   class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1"
                                   placeholder="nama@email.com" />
                        </div>
                        <p x-show="touched.email && errors.email" x-text="errors.email" class="text-[10px] text-rose-500 font-semibold mt-1 ml-1" x-cloak></p>
                        <x-input-error class="mt-1" :messages="$errors->get('email')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role -->
                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50 rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all">
                        <label for="create_role" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold text-slate-500 group-focus-within:text-[#153c96] transition-all duration-200">Role User</label>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 12l2 2l4 -4"></path>
                            <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"></path>
                        </svg>
                        <select name="role" id="create_role" x-model="role" class="w-full bg-transparent border-0 p-0 text-xs text-slate-600 focus:ring-0 focus:outline-none py-1">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-1" :messages="$errors->get('role')" />
                    </div>

                    <!-- School -->
                    <div class="group relative border border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50 rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all">
                        <label for="create_school" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold text-slate-500 group-focus-within:text-[#153c96] transition-all duration-200">Asal Sekolah (Opsional)</label>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 21l18 0"></path>
                            <path d="M5 21v-14a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14"></path>
                            <path d="M9 21v-4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4"></path>
                            <path d="M10 9l4 0"></path>
                            <path d="M10 12l4 0"></path>
                        </svg>
                        <input id="create_school" name="school" type="text" x-model="school"
                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1"
                               placeholder="Contoh: SMAN 1 Jakarta" />
                        <x-input-error class="mt-1" :messages="$errors->get('school')" />
                    </div>
                </div>

                <!-- Password -->
                <div class="relative">
                    <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                         :class="touched.password && errors.password ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-[#153c96] focus-within:ring-2 focus-within:ring-blue-100/50'">
                        <label for="create_password" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-extrabold transition-all duration-200"
                               :class="touched.password && errors.password ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-[#153c96]'">Password <span class="text-rose-500 font-bold">*</span></label>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z"></path>
                            <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                            <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path>
                        </svg>
                        <input id="create_password" name="password" type="password"
                               x-model="password"
                               @input="validatePassword"
                               @blur="touched.password = true; validatePassword()"
                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1"
                               placeholder="Min. 8 karakter" />
                    </div>
                    <p x-show="touched.password && errors.password" x-text="errors.password" class="text-[10px] text-rose-500 font-semibold mt-1 ml-1" x-cloak></p>
                    <x-input-error class="mt-1" :messages="$errors->get('password')" />
                </div>

                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="px-6 py-2.5 bg-[#153c96] hover:bg-blue-800 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/10 text-xs tracking-wider">
                        BUAT USER
                    </button>
                    <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-xs transition-colors">
                        BATAL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
