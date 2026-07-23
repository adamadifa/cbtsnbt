<section class="space-y-6">
    <div class="p-6 bg-red-50/50 rounded-2xl border border-red-100">
        <p class="text-sm text-red-800 font-medium leading-relaxed">
            <span class="font-black">Peringatan:</span> Setelah akun Anda dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen. Pastikan Anda telah mengunduh data penting yang ingin dipertahankan.
        </p>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-3 rounded-xl font-bold"
    >{{ __('Hapus Akun Saya') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-3 text-sm text-slate-500 font-medium leading-relaxed">
                {{ __('Tindakan ini tidak dapat dibatalkan. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full"
                    placeholder="{{ __('Konfirmasi dengan Password Anda') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <x-secondary-button x-on:click="$dispatch('close')" class="px-6 py-3 rounded-xl font-bold">
                    {{ __('Batalkan') }}
                </x-secondary-button>

                <x-danger-button class="px-6 py-3 rounded-xl font-bold">
                    {{ __('Ya, Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
