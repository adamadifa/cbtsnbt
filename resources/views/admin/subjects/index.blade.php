@extends('layouts.admin')

@section('page_title', 'Manajemen Materi Uji')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Materi Uji</h1>
        <p class="text-xs text-slate-400 mt-1">Kelola kategori materi ujian yang tersedia di sistem.</p>
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
        <span class="text-slate-650 font-semibold">Materi Uji</span>
    </div>
</div>

<div x-data="{ 
    showImportModal: false, 
    showCreateModal: false,
    showEditModal: false,
    editId: null,
    editName: '',
    editCode: '',
    editComponent: 'umum',
    editOrder: 0,
    editDescription: '',
    editColor: '#6366f1',
    editAction: ''
}">
    <!-- Filters & Search Toolbar (Outside the Card) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <form action="{{ route('admin.subjects.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
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
                    placeholder="Cari materi uji berdasarkan nama atau kode...">
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shrink-0">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Subjects Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Card Header (Unified Blue Bar) -->
        <div class="bg-[#153c96] text-white px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z"></path>
                        <path d="M19 16h-12a2 2 0 0 0 -2 2"></path>
                        <path d="M9 8h6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm tracking-wide">Daftar Materi Uji</h3>
                    <p class="text-[10px] text-white/70">Kelola kategori materi ujian yang tersedia di sistem</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button @click="showImportModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold text-xs border border-white/15 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                        <path d="M7 9l5 -5l5 5"></path>
                        <path d="M12 4l0 12"></path>
                    </svg>
                    Import Excel
                </button>

                <button @click="showCreateModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-blue-50 text-[#153c96] rounded-xl font-bold text-xs shadow-sm transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 5l0 14"></path>
                        <path d="M5 12l14 0"></path>
                    </svg>
                    Tambah Materi Uji
                </button>
            </div>
        </div>

        <!-- Subjects Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#153c96] text-white select-none">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Urutan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Materi Uji</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Komponen</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Kode</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-slate-50/20 transition-colors group">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400">
                            #{{ $subject->order }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-white text-xs shadow-sm" style="background-color: {{ $subject->color ?? '#6366f1' }}">
                                    {{ substr($subject->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ $subject->name }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ Str::limit($subject->description, 50) ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter bg-slate-100 text-slate-600">
                                {{ $subject->component }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-[11px] font-bold text-[#153c96] bg-blue-50 px-2 py-1 rounded-md">{{ $subject->code }}</code>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button type="button" @click="showEditModal = true; 
                                                              editId = {{ $subject->id }}; 
                                                              editName = '{{ addslashes($subject->name) }}'; 
                                                              editCode = '{{ addslashes($subject->code) }}'; 
                                                              editComponent = '{{ $subject->component }}'; 
                                                              editOrder = {{ $subject->order ?? 0 }};
                                                              editDescription = '{{ addslashes($subject->description ?? '') }}';
                                                              editColor = '{{ $subject->color ?? '#6366f1' }}';
                                                              editAction = '{{ route('admin.subjects.update', $subject) }}';" 
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                        <path d="M13.5 6.5l4 4"></path>
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="confirmDelete({{ $subject->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete">
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
                                <p class="text-slate-400 font-bold">Belum ada materi uji yang ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($subjects->hasPages())
        <div class="p-4 border-t border-slate-50">
            {{ $subjects->links() }}
        </div>
        @endif
    </div>

    <!-- Import Modal -->
    <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm" x-cloak>
        <div @click.away="showImportModal = false" class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-slate-800 uppercase tracking-wider">Import Materi Uji</h3>
                    <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl mb-5">
                    <p class="text-[11px] font-semibold text-blue-700 leading-relaxed uppercase tracking-wider">
                        Pilih berkas Excel (.xlsx, .xls) untuk mengunggah dan meng-import materi uji secara masal ke sistem.
                    </p>
                </div>

                <form action="{{ route('admin.subjects.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div class="relative group">
                            <input type="file" name="file" id="import_file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="p-6 border-2 border-dashed border-slate-200 group-hover:border-blue-400 group-hover:bg-blue-50/20 rounded-2xl transition-all text-center">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-600 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path>
                                        <path d="M9 15l3 -3l3 3"></path>
                                        <path d="M12 12l0 9"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-600">Klik atau seret file ke sini</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Format file: xlsx, xls, csv</p>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-[#153c96] hover:bg-blue-700 text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-blue-500/25 transition-all">
                            Mulai Proses Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Subject Modal -->
    @include('admin.subjects.create')

    <!-- Edit Subject Modal -->
    @include('admin.subjects.edit')
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(subjectId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Materi uji ini akan dihapus secara permanen beserta data terkait!",
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
                document.getElementById('delete-form-' + subjectId).submit();
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
