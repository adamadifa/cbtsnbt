@extends('layouts.admin')

@section('page_title', 'Penjadwalan Sesi Ujian')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Sesi Ujian</h1>
        <p class="text-xs text-slate-400 mt-1">Atur jadwal aktifasi paket tryout untuk dikerjakan siswa.</p>
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
        <span class="text-slate-650 font-semibold">Sesi Ujian</span>
    </div>
</div>

<div>
    <!-- Filters & Search Toolbar (Outside the Card) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <form action="{{ route('admin.exam-sessions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
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
                    placeholder="Cari nama sesi atau token...">
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shrink-0">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Exam Sessions Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Card Header (Unified Blue Bar) -->
        <div class="bg-[#153c96] text-white px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                        <path d="M16 3l0 4"></path>
                        <path d="M8 3l0 4"></path>
                        <path d="M4 11l16 0"></path>
                        <path d="M8 15h2v2h-2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm tracking-wide">Daftar Sesi Ujian</h3>
                    <p class="text-[10px] text-white/70">Atur jadwal aktifasi paket tryout untuk dikerjakan siswa</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.exam-sessions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-blue-50 text-[#153c96] rounded-xl font-bold text-xs shadow-sm transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 5l0 14"></path>
                        <path d="M5 12l14 0"></path>
                    </svg>
                    Jadwalkan Sesi
                </a>
            </div>
        </div>

        <!-- Exam Sessions Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#153c96] text-white select-none">
                        <th class="px-6 py-4 text-xs font-bold text-white/95">Info Sesi</th>
                        <th class="px-6 py-4 text-xs font-bold text-white/95">Jadwal / Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-white/95">Token Akses</th>
                        <th class="px-6 py-4 text-xs font-bold text-white/95">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-white/95 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-slate-50/20 transition-colors group">
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $session->title }}</p>
                                <p class="text-[10px] text-[#153c96] font-bold mt-0.5">{{ $session->examPackage->title }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                        <path d="M16 3l0 4"></path>
                                        <path d="M8 3l0 4"></path>
                                        <path d="M4 11l16 0"></path>
                                        <path d="M8 15h2v2h-2z"></path>
                                    </svg>
                                    {{ $session->start_time->format('d M Y, H:i') }}
                                </div>
                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-rose-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                        <path d="M12 7l0 5l3 3"></path>
                                    </svg>
                                    {{ $session->end_time->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 group/token">
                                <code class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold tracking-widest border border-slate-200 select-all">
                                    {{ $session->token }}
                                </code>
                                <button onclick="copyToken('{{ $session->token }}')" class="p-1.5 text-slate-350 hover:text-blue-600 transition-colors opacity-0 group-hover/token:opacity-100" title="Salin Token">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"></path>
                                        <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $status = $session->computed_status;
                                $colors = [
                                     'scheduled' => 'bg-amber-50 text-amber-700 border-amber-100',
                                     'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                     'completed' => 'bg-slate-50 text-slate-500 border-slate-100',
                                     'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                                ];
                                $labels = [
                                     'scheduled' => 'Mendatang',
                                     'active' => 'Aktif',
                                     'completed' => 'Selesai',
                                     'cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $colors[$status] }}">
                                {{ $labels[$status] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.exam-sessions.show', $session) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Monitor Ujian">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.exam-sessions.edit', $session) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all" title="Edit Sesi">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                        <path d="M13.5 6.5l4 4"></path>
                                    </svg>
                                </a>
                                <form id="delete-form-{{ $session->id }}" action="{{ route('admin.exam-sessions.destroy', $session) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="confirmDelete({{ $session->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete">
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
                                <p class="text-slate-400 font-bold">Belum ada sesi ujian yang dijadwalkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sessions->hasPages())
        <div class="p-4 border-t border-slate-50">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyToken(token) {
        navigator.clipboard.writeText(token);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        Toast.fire({
            icon: 'success',
            title: 'Token berhasil disalin!'
        });
    }

    function confirmDelete(sessionId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Sesi ujian ini akan dihapus secara permanen beserta data hasil siswa!",
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
                document.getElementById('delete-form-' + sessionId).submit();
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
