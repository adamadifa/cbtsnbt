@extends('layouts.admin')

@section('page_title', 'Manajemen Paket Tryout')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Paket Tryout</h1>
        <p class="text-xs text-slate-400 mt-1">Kelola kumpulan ujian (Paket) beserta subtest di dalamnya.</p>
    </div>
    <div class="flex items-center gap-3 self-start md:self-center">
        <!-- Add Button -->
        <a href="{{ route('admin.exam-packages.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#153c96] hover:bg-blue-800 text-white rounded-xl font-bold text-xs shadow-md shadow-blue-500/10 transition-all shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 5l0 14"></path>
                <path d="M5 12l14 0"></path>
            </svg>
            Buat Paket Baru
        </a>
        
        <!-- Breadcrumbs -->
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400 bg-white px-4 py-2.5 rounded-xl border border-slate-100 shadow-sm shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
            </svg>
            <span>/</span>
            <span>Dashboard</span>
            <span>/</span>
            <span class="text-slate-650 font-semibold leading-none">Paket Tryout</span>
        </div>
    </div>
</div>

<div>
    <!-- Modern Full-Width Filter Bar (Outside the Grid) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <form action="{{ route('admin.exam-packages.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="w-full sm:flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                        <path d="M21 21l-6 -6"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-100 text-xs transition-all focus:outline-none" 
                    placeholder="Cari nama paket tryout...">
            </div>

            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shrink-0">
                Filter Pencarian
            </button>
        </form>
    </div>

    <!-- Exam Packages Modern Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $package)
            <div class="bg-white rounded-3xl border-l-4 border-l-[#153c96] border border-slate-100 shadow-sm hover:shadow-md hover:scale-[1.01] transition-all duration-300 flex flex-col justify-between p-6 relative overflow-hidden group">
                
                <!-- Floating Decorative Glow -->
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-blue-50/40 filter blur-xl transition-all duration-500 group-hover:scale-150"></div>
                
                <div>
                    <!-- Card Header (Badges Row) -->
                    <div class="flex items-center justify-between gap-2 mb-4">
                        @if($package->type === 'free')
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Gratis
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-100">
                                Premium
                            </span>
                        @endif

                        @if($package->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-blue-50 text-[#153c96] border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#153c96] animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-slate-50 text-slate-400 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                Draft
                            </span>
                        @endif
                    </div>

                    <!-- Title & Description -->
                    <div class="flex items-start gap-3.5 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50/50 text-[#153c96] border border-blue-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z"></path>
                                <path d="M19 16h-12a2 2 0 0 0 -2 2"></path>
                                <path d="M9 8h6"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 leading-snug group-hover:text-[#153c96] transition-colors truncate" title="{{ $package->title }}">{{ $package->title }}</h3>
                            <p class="text-xs text-slate-450 mt-1 leading-relaxed line-clamp-2">{{ $package->description }}</p>
                        </div>
                    </div>

                    <!-- Metadata Pill Badges (Modern Replacement of Gray Box) -->
                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50/60 border border-blue-100 text-[#153c96] text-[10px] font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 4h6v6h-6z"></path>
                                <path d="M14 4h6v6h-6z"></path>
                                <path d="M4 14h6v6h-6z"></path>
                                <path d="M14 14h6v6h-6z"></path>
                            </svg>
                            {{ $package->subtests_count }} Subtest
                        </div>
                        
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-100 text-slate-500 text-[10px] font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                <path d="M12 7l0 5l3 3"></path>
                            </svg>
                            {{ $package->total_duration }} Menit
                        </div>
                    </div>
                </div>

                <!-- Footer area (Price & Outlined Buttons) -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div>
                        @if($package->type === 'premium')
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none">Harga Paket</p>
                            <p class="text-sm font-black text-slate-800 mt-1">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        @else
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none">Akses Paket</p>
                            <p class="text-sm font-black text-emerald-600 mt-1">Gratis</p>
                        @endif
                    </div>

                    <!-- Modern Outlined Action Buttons -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.exam-packages.edit', $package) }}" 
                           class="p-2 bg-slate-50 hover:bg-blue-50 hover:text-[#153c96] text-slate-400 rounded-xl transition-all border border-slate-100 shadow-sm" 
                           title="Edit Paket">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                <path d="M13.5 6.5l4 4"></path>
                            </svg>
                        </a>
                        
                        <form id="delete-form-{{ $package->id }}" action="{{ route('admin.exam-packages.destroy', $package) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="confirmDelete({{ $package->id }})" 
                                    class="p-2 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 text-slate-400 rounded-xl transition-all border border-slate-100 shadow-sm" 
                                    title="Hapus Paket">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-slate-100 p-16 text-center shadow-sm">
                <div class="flex flex-col items-center gap-2">
                    <div class="p-4 bg-slate-50 text-slate-350 rounded-3xl">
                        📭
                    </div>
                    <p class="text-slate-400 font-bold">Belum ada paket tryout ditambahkan.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if ($packages->hasPages())
        <div class="mt-6">
            {{ $packages->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(packageId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Paket tryout ini akan dihapus secara permanen beserta data subtest di dalamnya!",
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
                document.getElementById('delete-form-' + packageId).submit();
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
