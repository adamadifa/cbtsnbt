@extends('layouts.admin')

@section('page_title', isset($user) ? 'Edit User' : 'Tambah User')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
               <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
               <path d="M5 12l14 0"></path>
               <path d="M5 12l6 6"></path>
               <path d="M5 12l6 -6"></path>
            </svg>
            Kembali ke Daftar User
        </a>
        <h3 class="text-xl font-bold text-slate-800 tracking-tight">{{ isset($user) ? 'Perbarui Data User' : 'Buat User Baru' }}</h3>
        <p class="text-xs text-slate-400 mt-1">Lengkapi formulir di bawah ini dengan informasi akses yang sesuai</p>
    </div>

    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST"
          x-data="{
              name: '{{ old('name', isset($user) ? addslashes($user->name) : '') }}',
              email: '{{ old('email', isset($user) ? addslashes($user->email) : '') }}',
              role: '{{ old('role', isset($user) ? $user->roles[0]->name : 'siswa') }}',
              school: '{{ old('school', isset($user) ? addslashes($user->school) : '') }}',
              password: '',
              errors: { name: '', email: '', password: '' },
              touched: { name: false, email: false, password: false },
              isEdit: {{ isset($user) ? 'true' : 'false' }},
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
                  if (!this.isEdit && this.password === '') {
                      this.errors.password = 'Password wajib diisi';
                  } else if (this.password !== '' && this.password.length < 8) {
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
          }"
          @submit="checkSubmit"
          novalidate
          class="space-y-6">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <!-- Card: Identitas -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/20">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Identitas & Akses</h4>
            </div>
            <div class="p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="relative">
                        <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                             :class="touched.name && errors.name ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50'">
                            <label for="name" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold transition-all duration-200"
                                   :class="touched.name && errors.name ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-orange-600'">Nama Lengkap <span class="text-rose-500 font-bold">*</span></label>
                            <i class="ti ti-user text-slate-400 text-sm shrink-0"></i>
                            <input id="name" name="name" type="text" autofocus
                                   x-model="name"
                                   @input="validateName"
                                   @blur="touched.name = true; validateName()"
                                   class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1" 
                                   placeholder="Contoh: Ahmad Fauzi" />
                        </div>
                        <p x-show="touched.name && errors.name" x-text="errors.name" class="text-[10px] text-rose-500 font-semibold mt-1 ml-1" x-cloak></p>
                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                             :class="touched.email && errors.email ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50'">
                            <label for="email" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold transition-all duration-200"
                                   :class="touched.email && errors.email ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-orange-600'">Alamat Email <span class="text-rose-500 font-bold">*</span></label>
                            <i class="ti ti-mail text-slate-400 text-sm shrink-0"></i>
                            <input id="email" name="email" type="email"
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
                    <div class="group relative border border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50 rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all">
                        <label for="role" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-400 group-focus-within:text-orange-600 transition-all duration-200">Role User</label>
                        <i class="ti ti-shield-check text-slate-400 text-sm shrink-0"></i>
                        <select name="role" id="role" x-model="role" class="w-full bg-transparent border-0 p-0 text-xs text-slate-600 focus:ring-0 focus:outline-none py-1">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-1" :messages="$errors->get('role')" />
                    </div>

                    <!-- School -->
                    <div class="group relative border border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50 rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all">
                        <label for="school" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold text-slate-400 group-focus-within:text-orange-600 transition-all duration-200">Asal Sekolah (Opsional)</label>
                        <i class="ti ti-school text-slate-400 text-sm shrink-0"></i>
                        <input id="school" name="school" type="text" x-model="school"
                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1"
                               placeholder="Contoh: SMAN 1 Jakarta" />
                        <x-input-error class="mt-1" :messages="$errors->get('school')" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Password -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/20">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Keamanan</h4>
            </div>
            <div class="p-6 md:p-8">
                <div class="max-w-md">
                    <div class="group border rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all duration-200"
                         :class="touched.password && errors.password ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-100/50' : 'border-slate-200 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-100/50'">
                        <label for="password" class="absolute -top-2.5 left-3 px-1.5 bg-white text-[10px] font-bold transition-all duration-200"
                               :class="touched.password && errors.password ? 'text-rose-500 group-focus-within:text-rose-600' : 'text-slate-500 group-focus-within:text-orange-600'">Password <span class="text-rose-500 font-bold" x-show="!isEdit">*</span></label>
                        <i class="ti ti-lock text-slate-400 text-sm shrink-0"></i>
                        <input id="password" name="password" type="password"
                               x-model="password"
                               @input="validatePassword"
                               @blur="touched.password = true; validatePassword()"
                               class="w-full bg-transparent border-0 p-0 text-xs text-slate-800 placeholder-slate-300 focus:ring-0 focus:outline-none py-1"
                               placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Min. 8 karakter' }}" />
                    </div>
                    <p x-show="touched.password && errors.password" x-text="errors.password" class="text-[10px] text-rose-500 font-semibold mt-1 ml-1" x-cloak></p>
                    <x-input-error class="mt-1" :messages="$errors->get('password')" />
                    @if(isset($user))
                        <p class="text-[10px] text-slate-400 mt-2 ml-1">Kosongkan kolom password jika Anda tidak ingin mengubahnya.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 text-xs tracking-wider flex items-center gap-2">
                <i class="ti ti-device-floppy text-sm"></i>
                {{ isset($user) ? 'SIMPAN PERUBAHAN' : 'BUAT USER' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-3 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-xs transition-colors">
                BATAL
            </a>
        </div>
    </form>
</div>
@endsection
