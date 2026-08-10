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

<!-- Stats Summary Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.685 0-5.3.233-7.875.682V21A2.25 2.25 0 006.375 23.25h11.25A2.25 2.25 0 0019.5 21z" />
            </svg>
        </div>
        <div>
            <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Total Kampus</div>
            <div class="text-xl font-black text-slate-800 mt-0.5">
                {{ number_format(\App\Models\CampusProdi::distinct('campus_name')->count()) }}
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.9c4.956-1.936 8.285-6.427 8.285-11.543a48.667 48.667 0 00-16.025-1.579zm11.66-3.414a48.667 48.667 0 00-11.661 0v-.543a3.375 3.375 0 016.75 0v.543a3.375 3.375 0 014.911 0v.543z" />
            </svg>
        </div>
        <div>
            <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Total Program Studi</div>
            <div class="text-xl font-black text-slate-800 mt-0.5">
                {{ number_format(\App\Models\CampusProdi::distinct('prodi_name')->count()) }}
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
        </div>
        <div>
            <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Total Kombinasi Data</div>
            <div class="text-xl font-black text-slate-800 mt-0.5">
                {{ number_format(\App\Models\CampusProdi::count()) }}
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search Toolbar -->
<div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6">
    <form action="{{ route('admin.campus-prodis.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                    <path d="M21 21l-6 -6"></path>
                </svg>
            </div>
            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-100 text-xs transition-all focus:outline-none" 
                placeholder="Cari nama kampus atau prodi...">
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto shrink-0">
            <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.campus-prodis.index') }}" class="w-full md:w-auto text-center px-4 py-2.5 border border-slate-200 text-slate-650 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Main Table Card -->
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
                <h3 class="font-bold text-sm tracking-wide">Daftar Kampus</h3>
                <p class="text-[10px] text-white/70">Klik detail untuk melihat daftar program studi di setiap kampus</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="openImportModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold text-xs border border-white/15 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                    <path d="M7 9l5 -5l5 5"></path>
                    <path d="M12 4l0 12"></path>
                </svg>
                Import Excel
            </button>

            @if($records->total() > 0)
                <form action="{{ route('admin.campus-prodis.destroy-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua data kampus & prodi? Tindakan ini tidak bisa dibatalkan.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-xs shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#153c96] text-white select-none">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">No</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Nama Kampus</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Jumlah Prodi</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($records as $index => $record)
                    <tr class="hover:bg-slate-50/20 transition-colors group">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400">
                            #{{ $records->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-white text-xs shadow-sm bg-gradient-to-tr from-blue-600 to-indigo-500">
                                    {{ substr($record->campus_name, 0, 1) }}
                                </div>
                                <div class="font-bold text-slate-800 leading-tight">
                                    {{ $record->campus_name }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                            <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg">
                                {{ $record->total_prodi }} Prodi
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openDetailModal('{{ addslashes($record->campus_name) }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#153c96] rounded-xl font-bold text-xs transition-colors">
                                Detail Prodi
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400 space-y-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.89m-18 0h18" />
                                </svg>
                                <div class="font-semibold text-sm">Belum Ada Data</div>
                                <div class="text-xs">Silakan upload data kampus & prodi melalui tombol import Excel.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($records->hasPages())
        <div class="p-6 border-t border-slate-50">
            {{ $records->links() }}
        </div>
    @endif
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
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
            <div class="w-8 h-8 border-4 border-blue-200 border-t-[#153c96] rounded-full animate-spin"></div>
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
                <button onclick="submitImport()" class="px-5 py-2 bg-[#153c96] hover:bg-blue-800 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
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
