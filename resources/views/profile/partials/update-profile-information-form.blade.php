<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Alamat Email')" />
                <x-text-input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <p class="text-xs text-amber-700 font-medium leading-relaxed">
                            {{ __('Email Anda belum diverifikasi.') }}
                            <button form="send-verification" class="underline font-bold hover:text-amber-800">
                                {{ __('Kirim ulang link verifikasi?') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-bold text-xs text-green-600">
                                {{ __('Link verifikasi baru telah dikirim.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- School -->
        <div>
            <x-input-label for="school" :value="__('Asal Sekolah')" />
            <x-text-input id="school" name="school" type="text" class="block w-full" :value="old('school', $user->school)" placeholder="Contoh: SMAN 1 Jakarta" />
            <x-input-error class="mt-2" :messages="$errors->get('school')" />
            <p class="mt-2 text-xs text-slate-400 italic">Informasi ini akan muncul pada sertifikat hasil tryout Anda.</p>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-50">
            <x-primary-button class="px-8 shadow-lg shadow-indigo-100">{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
