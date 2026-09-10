@extends('layouts.admin')

@section('page_title', 'Manajemen Kampus & Program Studi')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Kampus & Program Studi</h1>
        <p class="text-xs text-slate-400 mt-1">Kelola data kampus, program studi, dan jenjang pendidikan.</p>
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
        <span class="text-slate-650 font-semibold">Kampus & Prodi</span>
    </div>
</div>

<!-- Alert Success -->
@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold">{{ session('success') }}</p>
        </div>
    </div>
@endif

<!-- Dashboard-style Stats Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <!-- CARD 1: Kampus Terdaftar (Dashboard Style) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-1.5">
                    <h3 class="text-xs font-bold text-slate-700">Perguruan Tinggi</h3>
                    <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200/80 bg-white">
                    Nasional
                </span>
            </div>

            <!-- Big Metric & Badge -->
            <div class="flex items-baseline gap-2.5 mb-3">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_campuses']) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                    Kampus Aktif
                </span>
            </div>

            <!-- Segmented Progress Bar (Orange) -->
            <div class="h-2 w-full rounded-full bg-slate-100 flex overflow-hidden gap-1 mb-3">
                <div class="bg-orange-500 rounded-full h-full" style="width: 100%;"></div>
            </div>

            <div class="flex items-center justify-between text-[11px] font-medium text-slate-500 mb-3">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span> PTN & PTS Terdata
                </span>
                <span class="font-bold text-slate-700">{{ $stats['total_campuses'] }} Kampus</span>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-3 text-xs font-semibold">
            <div class="flex items-center justify-between text-slate-600">
                <span class="flex items-center gap-1.5 font-medium">
                    <i class="ti ti-building text-slate-400 text-sm"></i>
                    Database Kampus
                </span>
                <span class="text-emerald-600 font-bold text-[11px]">Terverifikasi</span>
            </div>
        </div>
    </div>

    <!-- CARD 2: Program Studi & Jenjang (Donut & Breakdown Style) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-1.5">
                    <h3 class="text-xs font-bold text-slate-700">Program Studi Unik</h3>
                    <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                </div>
                <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded-md">SNBT / Mandiri</span>
            </div>

            <!-- Big Metric & Badge -->
            <div class="flex items-baseline gap-2.5 mb-3">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_prodis']) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                    Jurusan Berbeda
                </span>
            </div>

            <!-- Jenjang Breakdown (S1, D4, D3) -->
            @php
                $totalJenjang = max(1, $stats['total_s1'] + $stats['total_d4'] + $stats['total_d3']);
                $s1Pct = round(($stats['total_s1'] / $totalJenjang) * 100);
                $d4Pct = round(($stats['total_d4'] / $totalJenjang) * 100);
                $d3Pct = max(0, 100 - ($s1Pct + $d4Pct));
            @endphp
            <div class="h-2 w-full rounded-full bg-slate-100 flex overflow-hidden gap-1 mb-3">
                <div class="bg-orange-500 rounded-full h-full" style="width: {{ max($s1Pct, 10) }}%;"></div>
                <div class="bg-amber-400 rounded-full h-full" style="width: {{ max($d4Pct, 10) }}%;"></div>
                <div class="bg-cyan-500 rounded-full h-full" style="width: {{ max($d3Pct, 10) }}%;"></div>
            </div>

            <div class="flex items-center gap-3 text-[11px] font-medium text-slate-500 mb-3">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span> S1 ({{ $stats['total_s1'] }})
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span> D4 ({{ $stats['total_d4'] }})
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-cyan-500"></span> D3 ({{ $stats['total_d3'] }})
                </span>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-3 text-xs font-semibold">
            <div class="flex items-center justify-between text-slate-600">
                <span class="flex items-center gap-1.5 font-medium">
                    <i class="ti ti-school text-slate-400 text-sm"></i>
                    Jenjang Terbanyak
                </span>
                <span class="font-bold text-slate-800 text-[11px]">Sarjana (S1)</span>
            </div>
        </div>
    </div>

    <!-- CARD 3: Relasi & Pemetaan (Dashboard Metric Breakdown Style) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-1.5">
                    <h3 class="text-xs font-bold text-slate-700">Total Pemetaan Prodi</h3>
                    <span class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[9px] font-bold">i</span>
                </div>
                <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200/80 bg-white">
                    <span>Database</span>
                </div>
            </div>

            <!-- Big Metric & Badge -->
            <div class="flex items-baseline gap-2.5 mb-3">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_relations']) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                    Total Entri
                </span>
            </div>

            <!-- Equalizer / Visual Indicators matching dashboard style -->
            <div class="bg-slate-50/70 rounded-xl p-2.5 border border-slate-100 mb-3">
                <div class="flex items-end justify-between gap-1 h-6 px-1">
                    <div class="w-1.5 bg-orange-500 rounded-full h-5"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-4"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-6"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-5"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-3"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-6"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-5"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-4"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-6"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-5"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-4"></div>
                    <div class="w-1.5 bg-orange-500 rounded-full h-6"></div>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-3 text-xs font-semibold">
            <div class="flex items-center justify-between text-slate-600">
                <span class="flex items-center gap-1.5 font-medium">
                    <i class="ti ti-database text-slate-400 text-sm"></i>
                    Rata-rata Prodi / Kampus
                </span>
                <span class="font-bold text-slate-900 text-[11px]">
                    {{ $stats['total_campuses'] > 0 ? round($stats['total_relations'] / $stats['total_campuses'], 1) : 0 }} Prodi
                </span>
            </div>
        </div>
    </div>
</div>

<div x-data="{
    selectedCampuses: [],
    allCampuses: {{ json_encode($records->pluck('campus_name')->toArray()) }},
    get allSelected() {
        return this.allCampuses.length > 0 && this.allCampuses.every(name => this.selectedCampuses.includes(name));
    },
    toggleSelectAll() {
        if (this.allSelected) {
            this.selectedCampuses = [];
        } else {
            this.selectedCampuses = [...this.allCampuses];
        }
    },
    confirmBulkDelete() {
        if (this.selectedCampuses.length === 0) return;
        Swal.fire({
            title: 'Hapus ' + this.selectedCampuses.length + ' Kampus?',
            text: 'Semua program studi di kampus terpilih akan dihapus secara permanen!',
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
                document.getElementById('bulk-delete-campus-form').submit();
            }
        });
    }
}">
    <!-- Filters & Search Toolbar (Cardless) -->
    <div class="mb-4">
        <form action="{{ route('admin.campus-prodis.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5 items-center justify-between">
            <div class="w-full md:flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="ti ti-search text-base"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200/80 rounded-xl text-slate-800 placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-100/50 text-xs transition-all focus:outline-none shadow-2xs" 
                    placeholder="Cari nama kampus atau program studi...">
            </div>

            <div class="flex items-center gap-2.5 w-full md:w-auto shrink-0">
                <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors shadow-2xs flex items-center justify-center gap-1.5">
                    <i class="ti ti-filter text-sm"></i>
                    <span>Cari</span>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.campus-prodis.index') }}" class="w-full md:w-auto text-center px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all shadow-2xs">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Campus Cards Container -->
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
                    Total: <strong class="text-white">{{ $records->total() }}</strong> kampus
                </span>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                <!-- Bulk Delete Action Trigger -->
                <button type="button" 
                        x-show="selectedCampuses.length > 0" 
                        x-transition 
                        @click="confirmBulkDelete()" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all animate-pulse" 
                        x-cloak>
                    <i class="ti ti-trash text-sm"></i>
                    <span>Hapus (<span x-text="selectedCampuses.length"></span>)</span>
                </button>

                <button onclick="openImportModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white/15 hover:bg-white/25 text-white rounded-xl font-bold text-xs border border-white/20 transition-all active:scale-95">
                    <i class="ti ti-file-import text-sm"></i>
                    Import Excel
                </button>

                @if($records->total() > 0)
                    <form action="{{ route('admin.campus-prodis.destroy-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA data kampus & prodi? Tindakan ini tidak bisa dibatalkan.');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all active:scale-95">
                            <i class="ti ti-trash-x text-sm"></i>
                            Hapus Semua
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Compact Campus Cards List -->
        <div class="space-y-2">
            @forelse($records as $index => $record)
            <div class="bg-white px-4 py-3 rounded-xl border border-slate-100 shadow-2xs hover:border-orange-200 transition-all flex flex-col md:flex-row md:items-center justify-between gap-3 group"
                 :class="selectedCampuses.includes('{{ addslashes($record->campus_name) }}') ? 'border-orange-400 bg-orange-50/20 ring-1 ring-orange-200' : ''">
                
                <!-- Left Section: Checkbox, Index, Avatar & Campus Name -->
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <!-- Row Checkbox -->
                    <input type="checkbox" 
                           value="{{ $record->campus_name }}" 
                           x-model="selectedCampuses" 
                           class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-100 cursor-pointer shrink-0">
                    
                    <!-- Index Badge -->
                    <span class="w-6 h-6 rounded-md bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-[10px] font-bold shrink-0">
                        #{{ $records->firstItem() + $index }}
                    </span>

                    <!-- Campus Avatar Icon -->
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center font-black text-xs shrink-0 ring-1 ring-orange-100">
                        {{ substr($record->campus_name, 0, 1) }}
                    </div>
                    
                    <!-- Campus Title & Quick Meta -->
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-orange-600 transition-colors truncate">{{ $record->campus_name }}</h4>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5 flex items-center gap-1.5">
                            <i class="ti ti-school text-slate-400 text-xs"></i>
                            <span>Perguruan Tinggi / Universitas</span>
                        </p>
                    </div>
                </div>

                <!-- Middle Section: Total Prodi Pill -->
                <div class="flex items-center gap-4 px-2 md:px-0 shrink-0">
                    <span class="bg-orange-50 text-orange-700 border border-orange-100 px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-tight flex items-center gap-1.5">
                        <i class="ti ti-books text-xs"></i>
                        {{ $record->total_prodi }} Program Studi
                    </span>
                </div>

                <!-- Right Section: Action Button -->
                <div class="flex items-center justify-end gap-1.5 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-50">
                    <button onclick="openDetailModal('{{ addslashes($record->campus_name) }}')" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 hover:bg-orange-50 hover:text-orange-600 text-slate-600 rounded-lg font-bold text-xs transition-all border border-slate-100" 
                            title="Lihat Rincian Program Studi">
                        <i class="ti ti-list-details text-sm"></i>
                        <span>Detail Prodi</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center">
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="p-3 bg-orange-50 text-orange-500 rounded-xl">
                        <i class="ti ti-inbox-off text-2xl"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-700">Belum Ada Data Kampus</h4>
                    <p class="text-[11px] text-slate-400">Silakan upload data kampus & prodi melalui tombol import Excel.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($records->hasPages())
        <div class="bg-white px-4 py-3 rounded-xl border border-slate-100 shadow-2xs mt-3">
            {{ $records->links() }}
        </div>
        @endif
    </div>

    <!-- Hidden Bulk Delete Form -->
    <form id="bulk-delete-campus-form" action="{{ route('admin.campus-prodis.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        <template x-for="name in selectedCampuses" :key="name">
            <input type="hidden" name="campus_names[]" :value="name">
        </template>
    </form>
</div>

<!-- Detail Prodi Modal -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" x-cloak>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl relative z-10 mx-4 border border-slate-100 transform transition-all scale-95 opacity-0 duration-300 flex flex-col max-h-[85vh]" id="detailModalContent">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
            <div>
                <h3 class="text-base font-bold text-slate-800" id="detailCampusTitle">Detail Program Studi</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Daftar jurusan beserta jenjang pendidikan yang tersedia</p>
            </div>
            <button onclick="closeDetailModal()" class="w-8 h-8 rounded-xl hover:bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="ti ti-x text-base"></i>
            </button>
        </div>
        
        <!-- Table Area (Scrollable) -->
        <div class="overflow-y-auto flex-1 py-4 main-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold text-[10px] uppercase tracking-wider select-none">
                        <th class="px-4 py-2.5">No</th>
                        <th class="px-4 py-2.5">Program Studi</th>
                        <th class="px-4 py-2.5 text-right">Jenjang</th>
                    </tr>
                </thead>
                <tbody id="detailProdiTbody" class="divide-y divide-slate-100 text-xs text-slate-700">
                    <!-- Loaded via AJAX -->
                </tbody>
            </table>
        </div>
        
        <div class="pt-4 border-t border-slate-100 flex justify-end shrink-0">
            <button onclick="closeDetailModal()" class="px-5 py-2 border border-slate-200 text-slate-650 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all">
                Tutup
            </button>
        </div>

        <!-- Inner Loader Overlay -->
        <div id="detail-loader" class="absolute inset-0 bg-white/95 rounded-3xl z-20 flex flex-col items-center justify-center space-y-4">
            <div class="w-8 h-8 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
            <div class="text-xs font-bold text-slate-700">Memuat data prodi...</div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" x-cloak>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative z-10 mx-4 border border-slate-100 transform transition-all scale-95 opacity-0 duration-300" id="importModalContent">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Import Kampus & Prodi</h3>
            <button onclick="closeImportModal()" class="w-8 h-8 rounded-xl hover:bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Step 1: Upload File -->
        <div id="step-upload" class="mt-5 space-y-4">
            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-blue-400 hover:bg-blue-50/20 transition-all cursor-pointer relative" id="drop-area">
                <input type="file" id="excelFile" class="absolute inset-0 opacity-0 cursor-pointer" accept=".xlsx,.xls,.csv" onchange="handleFileSelect(this)">
                <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>
                    </div>
                    <div class="font-bold text-slate-700 text-xs">Pilih atau Seret File Excel</div>
                    <div class="text-[10px] text-slate-400">Mendukung format .xlsx, .xls, atau .csv (Maks. 15MB)</div>
                </div>
            </div>
            
            <div class="text-[11px] text-slate-500 bg-slate-50 p-4 rounded-xl leading-relaxed border border-slate-100">
                <span class="font-bold text-slate-700">Tips:</span> Sistem ini akan otomatis membaca nama kampus dari merged cells Excel Anda. Pastikan kolom minimal berisi judul/header seperti "Program Studi/Jurusan/Prodi" dan "Jenjang".
            </div>
        </div>

        <!-- Step 2: Select Sheet -->
        <div id="step-sheet" class="mt-5 space-y-4 hidden animate-fade-in">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Sheet Excel</label>
                <select id="sheetSelect" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all">
                    <!-- Options filled via JS -->
                </select>
            </div>
            
            <div class="flex items-center gap-3 justify-end pt-4 border-t border-slate-100">
                <button onclick="backToUpload()" class="px-4 py-2 border border-slate-200 text-slate-650 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all">
                    Kembali
                </button>
                <button onclick="submitImport()" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                    <i class="ti ti-cloud-upload text-sm"></i>
                    Mulai Import
                </button>
            </div>
        </div>

        <!-- Loader Overlay -->
        <div id="import-loader" class="absolute inset-0 bg-white/95 rounded-3xl z-20 flex flex-col items-center justify-center space-y-4 hidden">
            <div class="w-10 h-10 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
            <div class="text-xs font-bold text-slate-700" id="loader-text">Mengunggah file...</div>
            <div class="text-[10px] text-slate-400">Mohon tunggu beberapa saat</div>
        </div>
    </div>
</div>

<script>
    let tempFilePath = null;

    // Detail Modal Actions
    function openDetailModal(campusName) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailModalContent');
        const loader = document.getElementById('detail-loader');
        const tbody = document.getElementById('detailProdiTbody');
        
        document.getElementById('detailCampusTitle').innerText = 'Detail Program Studi - ' + campusName;
        tbody.innerHTML = '';
        
        // Show modal and loading state
        modal.classList.remove('hidden');
        loader.classList.remove('hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Fetch prodi details via AJAX
        $.ajax({
            url: "{{ route('admin.campus-prodis.prodis') }}",
            type: 'GET',
            data: { campus: campusName },
            success: function(res) {
                loader.classList.add('hidden');
                if (res.success && res.prodis.length > 0) {
                    res.prodis.forEach((prodi, idx) => {
                        const row = `
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-2.5 text-slate-400 font-medium">${idx + 1}</td>
                                <td class="px-4 py-2.5 font-bold text-slate-800">${prodi.prodi_name}</td>
                                <td class="px-4 py-2.5 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-tighter ${prodi.jenjang === 'S1' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700'}">
                                        ${prodi.jenjang}
                                    </span>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-400">Tidak ada data program studi.</td>
                        </tr>
                    `;
                }
            },
            error: function() {
                loader.classList.add('hidden');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-rose-500 font-medium">Gagal memuat data program studi.</td>
                    </tr>
                `;
            }
        });
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Import Modal Actions
    function openImportModal() {
        const modal = document.getElementById('importModal');
        const content = document.getElementById('importModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeImportModal() {
        const modal = document.getElementById('importModal');
        const content = document.getElementById('importModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            resetModalState();
        }, 300);
    }

    function resetModalState() {
        tempFilePath = null;
        document.getElementById('excelFile').value = '';
        document.getElementById('step-upload').classList.remove('hidden');
        document.getElementById('step-sheet').classList.add('hidden');
        document.getElementById('sheetSelect').innerHTML = '';
        hideLoader();
    }

    function showLoader(text) {
        document.getElementById('loader-text').innerText = text;
        document.getElementById('import-loader').classList.remove('hidden');
    }

    function hideLoader() {
        document.getElementById('import-loader').classList.add('hidden');
    }

    function handleFileSelect(input) {
        if (!input.files || input.files.length === 0) return;
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);

        showLoader('Mengunggah dan membaca file excel...');

        $.ajax({
            url: "{{ route('admin.campus-prodis.upload') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                hideLoader();
                if (res.success) {
                    tempFilePath = res.temp_path;
                    const sheetSelect = document.getElementById('sheetSelect');
                    sheetSelect.innerHTML = '';
                    
                    res.sheets.forEach(sheet => {
                        const opt = document.createElement('option');
                        opt.value = sheet;
                        opt.text = sheet;
                        sheetSelect.appendChild(opt);
                    });

                    document.getElementById('step-upload').classList.add('hidden');
                    document.getElementById('step-sheet').classList.remove('hidden');
                } else {
                    alert(res.message || 'Gagal memproses file.');
                    resetModalState();
                }
            },
            error: function(xhr) {
                hideLoader();
                let msg = 'Gagal mengupload file.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
                resetModalState();
            }
        });
    }

    function backToUpload() {
        document.getElementById('step-sheet').classList.add('hidden');
        document.getElementById('step-upload').classList.remove('hidden');
        document.getElementById('excelFile').value = '';
        tempFilePath = null;
    }

    function submitImport() {
        const sheetName = document.getElementById('sheetSelect').value;
        if (!sheetName || !tempFilePath) {
            alert('Pilih sheet terlebih dahulu.');
            return;
        }

        showLoader('Sedang mengimport data ke database...');

        $.ajax({
            url: "{{ route('admin.campus-prodis.import') }}",
            type: 'POST',
            data: JSON.stringify({
                temp_path: tempFilePath,
                sheet_name: sheetName
            }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                hideLoader();
                if (res.success) {
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert(res.message || 'Gagal memproses import.');
                }
            },
            error: function(xhr) {
                hideLoader();
                let msg = 'Gagal memproses import.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }
        });
    }
</script>
@endsection
