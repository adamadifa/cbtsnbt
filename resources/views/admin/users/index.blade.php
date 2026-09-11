@extends('layouts.admin')

@section('page_title', 'Manajemen User')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen User</h1>
        <p class="text-xs text-slate-400 mt-1">Kelola data siswa, guru, dan administrator sistem</p>
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
        <span class="text-slate-650 font-semibold">Manajemen User</span>
    </div>
</div>

<div x-data="{ 
    showImportModal: false, 
    showCreateModal: false,
    showEditModal: false,
    editId: null,
    editName: '',
    editEmail: '',
    editRole: 'siswa',
    editSchool: '',
    editPassword: '',
    editAction: '',
    selectedUsers: [],
    allUserIds: {{ json_encode($users->pluck('id')->toArray()) }},
    get allSelected() {
        return this.allUserIds.length > 0 && this.allUserIds.every(id => this.selectedUsers.includes(id));
    },
    toggleSelectAll() {
        if (this.allSelected) {
            this.selectedUsers = [];
        } else {
            this.selectedUsers = [...this.allUserIds];
        }
    },
    toggleUser(id) {
        if (this.selectedUsers.includes(id)) {
            this.selectedUsers = this.selectedUsers.filter(item => item !== id);
        } else {
            this.selectedUsers.push(id);
        }
    },
    confirmBulkDelete() {
        if (this.selectedUsers.length === 0) return;
        Swal.fire({
            title: 'Hapus ' + this.selectedUsers.length + ' Pengguna?',
            text: 'Semua pengguna yang dipilih akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus Terpilih!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-slate-100',
                confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5 shadow-md shadow-orange-500/20',
                cancelButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulk-delete-form').submit();
            }
        });
    }
}">
    <!-- Filters & Search Toolbar (Cardless) -->
    <div class="mb-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5 items-center justify-between">
            <div class="w-full md:flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="ti ti-search text-base"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200/80 rounded-xl text-slate-800 placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all focus:outline-none shadow-2xs" 
                    placeholder="Cari nama atau email pengguna...">
            </div>

            <div class="flex items-center gap-2.5 w-full md:w-auto flex-wrap sm:flex-nowrap">
                <select name="role" onchange="this.form.submit()" class="block w-full sm:w-36 py-2.5 px-3 bg-white border border-slate-200/80 rounded-xl text-slate-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all focus:outline-none font-medium shadow-2xs">
                    <option value="">Semua Peran</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>

                <select name="per_page" onchange="this.form.submit()" class="block w-full sm:w-36 py-2.5 px-3 bg-white border border-slate-200/80 rounded-xl text-slate-600 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all focus:outline-none font-medium shadow-2xs">
                    <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10 / Halaman</option>
                    <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20 / Halaman</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 / Halaman</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 / Halaman</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Tampilkan Semua</option>
                </select>

                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shrink-0 flex items-center justify-center gap-1.5 shadow-2xs">
                    <i class="ti ti-filter text-sm"></i>
                    <span>Filter</span>
                </button>

                @if(request('search') || request('role') || (request('per_page') && request('per_page') != '10'))
                    <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all text-center shadow-2xs">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Cards Container -->
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
                    Total: <strong class="text-white">{{ $users->total() ?? count($users) }}</strong> pengguna
                </span>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                <!-- Bulk Delete Action Trigger -->
                <button type="button" 
                        x-show="selectedUsers.length > 0" 
                        x-transition 
                        @click="confirmBulkDelete()" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all animate-pulse" 
                        x-cloak>
                    <i class="ti ti-trash text-sm"></i>
                    <span>Hapus (<span x-text="selectedUsers.length"></span>)</span>
                </button>

                <a href="{{ route('admin.users.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-xl font-bold text-xs border border-white/20 transition-all active:scale-95">
                    <i class="ti ti-download text-sm"></i>
                    Export
                </a>

                <button @click="showImportModal = true" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-xl font-bold text-xs border border-white/20 transition-all active:scale-95">
                    <i class="ti ti-file-import text-sm"></i>
                    Import
                </button>

                <button @click="showCreateModal = true" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-orange-50 text-orange-600 rounded-xl font-bold text-xs shadow-sm transition-all active:scale-95">
                    <i class="ti ti-plus text-sm"></i>
                    Tambah User
                </button>
            </div>
        </div>

        <!-- Compact User Cards List -->
        <div class="space-y-2">
            @forelse($users as $user)
            <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-100 shadow-2xs hover:border-orange-200 transition-all flex flex-col md:flex-row md:items-center justify-between gap-3 group"
                 :class="selectedUsers.includes({{ $user->id }}) ? 'border-orange-400 bg-orange-50/20 ring-1 ring-orange-200' : ''">
                
                <!-- Left Section: Checkbox, Avatar, Name & Role -->
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <!-- Row Checkbox -->
                    <input type="checkbox" 
                           :value="{{ $user->id }}" 
                           x-model="selectedUsers" 
                           class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-100 cursor-pointer shrink-0">
                    
                    <!-- Avatar -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f97316&color=fff&bold=true" 
                         alt="{{ $user->name }}" 
                         class="w-8 h-8 rounded-lg object-cover ring-1 ring-orange-100 shrink-0">
                    
                    <!-- Name, Email, & Role -->
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-bold text-slate-800 group-hover:text-orange-600 transition-colors truncate">{{ $user->name }}</span>
                            @foreach($user->roles as $role)
                                <span class="px-2 py-0.2 text-[9px] font-bold uppercase tracking-wider rounded-md inline-block
                                    {{ $role->name == 'super_admin' ? 'bg-purple-50 text-purple-700 border border-purple-100' : '' }}
                                    {{ $role->name == 'admin' ? 'bg-orange-50 text-orange-700 border border-orange-200' : '' }}
                                    {{ $role->name == 'guru' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                    {{ $role->name == 'siswa' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}">
                                    {{ str_replace('_', ' ', $role->name) }}
                                </span>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-400 truncate flex items-center gap-1 mt-0.5">
                            <i class="ti ti-mail text-slate-400 text-xs"></i>
                            <span>{{ $user->email }}</span>
                        </p>
                    </div>
                </div>

                <!-- Middle Section: School & Date (Compact) -->
                <div class="flex items-center gap-4 text-[11px] text-slate-500 px-2 md:px-0 shrink-0 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-1.5" title="Asal Sekolah">
                        <i class="ti ti-school text-slate-400 text-sm"></i>
                        <span class="font-semibold text-slate-600 truncate max-w-[140px]">{{ $user->school ?? '-' }}</span>
                    </div>

                    <div class="flex items-center gap-1.5 text-slate-400" title="Tanggal Terdaftar">
                        <i class="ti ti-calendar text-xs"></i>
                        <span>{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Right Section: Compact Action Buttons -->
                <div class="flex items-center justify-end gap-1 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-50">
                    <button type="button" @click="showEditModal = true; 
                                                  editId = {{ $user->id }}; 
                                                  editName = '{{ addslashes($user->name) }}'; 
                                                  editEmail = '{{ addslashes($user->email) }}'; 
                                                  editRole = '{{ $user->roles[0]->name ?? 'siswa' }}'; 
                                                  editSchool = '{{ addslashes($user->school ?? '') }}'; 
                                                  editPassword = ''; 
                                                  editAction = '{{ route('admin.users.update', $user) }}';" 
                            class="p-1.5 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all" title="Edit Pengguna">
                        <i class="ti ti-edit text-base"></i>
                    </button>
                    
                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="confirmDelete({{ $user->id }})" 
                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Pengguna">
                            <i class="ti ti-trash text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="p-3 bg-orange-50 text-orange-500 rounded-xl">
                        <i class="ti ti-inbox-off text-2xl"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-700">Tidak Ada Pengguna Ditemukan</h4>
                    <p class="text-[11px] text-slate-400">Silakan ubah filter atau kata kunci pencarian.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 rounded-xl border border-slate-100 shadow-2xs mt-3">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Hidden Bulk Delete Form -->
    <form id="bulk-delete-form" action="{{ route('admin.users.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        <template x-for="id in selectedUsers" :key="id">
            <input type="hidden" name="ids[]" :value="id">
        </template>
    </form>

    <!-- Import Modal -->
    <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm" x-cloak>
        <div @click.away="showImportModal = false" class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 transform transition-all overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-slate-800 tracking-wide">Import Masal Peserta</h3>
                    <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                </div>

                <div class="p-4 bg-orange-50/70 border border-orange-100 rounded-2xl mb-5">
                    <p class="text-[11px] font-semibold text-orange-800 leading-relaxed">
                        Unduh template CSV, isi data peserta, dan unggah kembali di sini. Password default: <span class="bg-orange-500 text-white px-1.5 py-0.5 rounded ml-1 font-bold">12345678</span>
                    </p>
                    <a href="{{ route('admin.users.download-template') }}" class="mt-3 inline-flex items-center gap-2 text-xs font-bold text-orange-600 hover:text-orange-700 hover:underline">
                        <i class="ti ti-download text-sm"></i>
                        DOWNLOAD TEMPLATE CSV
                    </a>
                </div>

                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div class="relative group">
                            <input type="file" name="file" id="import_file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="p-6 border-2 border-dashed border-slate-200 group-hover:border-orange-400 group-hover:bg-orange-50/20 rounded-2xl transition-all text-center">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 group-hover:bg-orange-100 group-hover:text-orange-600 transition-all">
                                    <i class="ti ti-upload text-xl"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-600">Klik atau seret file ke sini</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Format file: xlsx, xls, csv</p>
                            </div>
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

    <!-- Create User Modal -->
    @include('admin.users.create')

    <!-- Edit User Modal -->
    @include('admin.users.edit')
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengguna ini akan dihapus secara permanen!",
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
                document.getElementById('delete-form-' + userId).submit();
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
