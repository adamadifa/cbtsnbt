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

<div x-data="{
    selectedSessions: [],
    allSessionIds: {{ json_encode($sessions->pluck('id')->toArray()) }},
    get allSelected() {
        return this.allSessionIds.length > 0 && this.allSessionIds.every(id => this.selectedSessions.includes(id));
    },
    toggleSelectAll() {
        if (this.allSelected) {
            this.selectedSessions = [];
        } else {
            this.selectedSessions = [...this.allSessionIds];
        }
    }
}">
    <!-- Filters & Search Toolbar (Cardless) -->
    <div class="mb-4">
        <form action="{{ route('admin.exam-sessions.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5 items-center justify-between">
            <div class="w-full md:flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="ti ti-search text-base"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200/80 rounded-xl text-slate-800 placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all focus:outline-none shadow-2xs" 
                    placeholder="Cari nama sesi atau token...">
            </div>

            <div class="flex items-center gap-2.5 w-full md:w-auto shrink-0">
                <select name="status" onchange="this.form.submit()" class="py-2.5 pl-3 pr-8 bg-white border border-slate-200/80 rounded-xl text-slate-700 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all shadow-2xs">
                    <option value="">Semua Status</option>
                    <option value="scheduled" @selected(request('status') == 'scheduled')>Mendatang</option>
                    <option value="active" @selected(request('status') == 'active')>Aktif</option>
                    <option value="completed" @selected(request('status') == 'completed')>Selesai</option>
                    <option value="cancelled" @selected(request('status') == 'cancelled')>Dibatalkan</option>
                </select>

                <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shadow-2xs flex items-center justify-center gap-1.5">
                    <i class="ti ti-filter text-sm"></i>
                    <span>Cari</span>
                </button>

                @if(request('search') || request('status'))
                    <a href="{{ route('admin.exam-sessions.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all shadow-2xs">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Exam Sessions Container -->
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
                    Total: <strong class="text-white">{{ $sessions->total() }}</strong> sesi
                </span>
            </div>

            <div class="flex items-center gap-2">
                <!-- Bulk Delete Button -->
                <button type="button" 
                        x-show="selectedSessions.length > 0" 
                        @click="confirmBulkDeleteSessions(selectedSessions)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all"
                        x-cloak>
                    <i class="ti ti-trash text-sm"></i>
                    <span>Hapus (<span x-text="selectedSessions.length"></span>)</span>
                </button>

                <a href="{{ route('admin.exam-sessions.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-orange-50 text-orange-600 rounded-xl font-bold text-xs shadow-sm transition-all">
                    <i class="ti ti-plus text-sm"></i>
                    <span>Jadwalkan Sesi</span>
                </a>
            </div>
        </div>

        <!-- List of Sessions (Compact Full-Width Cards) -->
        <div class="space-y-2">
            @forelse($sessions as $session)
            @php
                $itemIndex = ($sessions->currentPage() - 1) * $sessions->perPage() + $loop->iteration;
                $status = $session->computed_status;
                $colors = [
                    'scheduled' => 'bg-amber-50 text-amber-700 border-amber-200/70',
                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
                    'completed' => 'bg-slate-100 text-slate-600 border-slate-200',
                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200/70',
                ];
                $labels = [
                    'scheduled' => 'Mendatang',
                    'active' => 'Aktif',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ];
            @endphp
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-sm hover:border-orange-300 transition-all px-4 py-3 flex flex-col md:flex-row md:items-center justify-between gap-3 group"
                 :class="selectedSessions.includes({{ $session->id }}) ? 'border-orange-400 bg-orange-50/20 ring-1 ring-orange-200' : ''">
                
                <!-- Left: Checkbox, Number, Title & Package -->
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    <div class="flex items-center gap-2.5 pt-0.5 shrink-0">
                        <input type="checkbox" 
                               value="{{ $session->id }}" 
                               x-model.number="selectedSessions"
                               class="w-4 h-4 rounded border-slate-300 text-orange-600 focus:ring-orange-400 transition-all cursor-pointer">
                        <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 font-bold text-[11px] flex items-center justify-center shrink-0">
                            {{ $itemIndex }}
                        </span>
                    </div>

                    <!-- Session Info -->
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.exam-sessions.show', $session) }}" class="text-xs font-bold text-slate-800 hover:text-orange-600 transition-colors">
                                {{ $session->title }}
                            </a>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $colors[$status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $labels[$status] ?? $status }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 mt-1 text-[11px] text-slate-400 flex-wrap">
                            <span class="font-semibold text-orange-600 flex items-center gap-1">
                                <i class="ti ti-box text-xs"></i>
                                {{ $session->examPackage->title ?? '-' }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <i class="ti ti-clock-play text-xs text-emerald-500"></i>
                                {{ $session->start_time->format('d M Y, H:i') }} - {{ $session->end_time->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Middle & Right: Token & Actions -->
                <div class="flex items-center justify-between md:justify-end gap-4 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
                    <!-- Token Pill -->
                    <div class="flex items-center gap-1.5 group/token bg-slate-50 border border-slate-200/80 rounded-lg px-2.5 py-1">
                        <span class="text-[10px] font-bold uppercase text-slate-400">Token:</span>
                        <code class="text-xs font-black text-slate-800 tracking-wider select-all">{{ $session->token }}</code>
                        <button onclick="copyToken('{{ $session->token }}')" class="text-slate-400 hover:text-orange-600 transition-colors ml-0.5" title="Salin Token">
                            <i class="ti ti-copy text-sm"></i>
                        </button>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.exam-sessions.show', $session) }}" 
                           class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" 
                           title="Monitor Sesi">
                            <i class="ti ti-chart-bar text-base"></i>
                        </a>

                        <a href="{{ route('admin.exam-sessions.edit', $session) }}" 
                           class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" 
                           title="Edit Sesi">
                            <i class="ti ti-edit text-base"></i>
                        </a>

                        <form id="delete-form-{{ $session->id }}" action="{{ route('admin.exam-sessions.destroy', $session) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    @click="confirmDelete({{ $session->id }})" 
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" 
                                    title="Hapus Sesi">
                                <i class="ti ti-trash text-base"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center mx-auto mb-3">
                    <i class="ti ti-calendar-off text-2xl"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum ada sesi ujian</h4>
                <p class="text-xs text-slate-400 mt-1">Silakan jadwalkan sesi ujian baru untuk paket tryout yang telah dibuat.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($sessions->hasPages())
        <div class="pt-3">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>

    <!-- Hidden Bulk Delete Form -->
    <form id="bulk-delete-sessions-form" action="{{ route('admin.exam-sessions.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
    </form>
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
                document.getElementById('delete-form-' + sessionId).submit();
            }
        })
    function confirmBulkDeleteSessions(sessionIds) {
        if (!sessionIds || sessionIds.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + sessionIds.length + ' Sesi Terpilih?',
            text: "Semua sesi ujian yang Anda pilih beserta data hasil ujian siswa terkait akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus terpilih!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl border border-slate-100',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider',
                cancelButton: 'rounded-xl px-5 py-2.5 font-bold text-xs uppercase tracking-wider'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulk-delete-sessions-form');
                form.innerHTML = '@csrf';
                sessionIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'session_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                form.submit();
            }
        });
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
