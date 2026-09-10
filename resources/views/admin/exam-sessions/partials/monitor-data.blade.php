{{-- Wrap everything in Alpine.js detection from parent --}}
<div x-data="{ 
    showModal: false, 
    modalData: { name: '', violations: [] },
    selectedIds: [],
    selectAll: false,
    allIds: {{ $results->pluck('id')->toJson() }},
    
    toggleAll() {
        if (this.selectAll) {
            this.selectedIds = [...this.allIds];
        } else {
            this.selectedIds = [];
        }
    },
    
    updateSelectAll() {
        this.selectAll = this.selectedIds.length === this.allIds.length;
    },
    
    bulkForceFinish() {
        if (!confirm('Yakin ingin menyelesaikan secara paksa semua siswa terpilih?')) return;
        
        $.ajax({
            url: '{{ route('admin.exam-sessions.bulk-force-finish', $examSession) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: this.selectedIds
            },
            success: function(res) {
                if (res.success) {
                    window.location.reload();
                } else {
                    alert('Gagal memproses tindakan massal.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan memproses tindakan massal.');
            }
        });
    },
    
    bulkReset() {
        if (!confirm('PERINGATAN! Yakin ingin me-reset ujian semua siswa terpilih? Semua jawaban mereka akan dihapus permanen!')) return;
        
        $.ajax({
            url: '{{ route('admin.exam-sessions.bulk-reset', $examSession) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: this.selectedIds
            },
            success: function(res) {
                if (res.success) {
                    window.location.reload();
                } else {
                    alert('Gagal memproses tindakan massal.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan memproses tindakan massal.');
            }
        });
    }
}" 
x-show="activeTab === 'monitor'" 
@open-violation-modal.window="showModal = true; modalData = $event.detail"
x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
    
    {{-- Violation Detail Modal --}}
    <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <div class="px-8 pt-8 pb-6 bg-slate-50/50 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Log Pelanggaran</h3>
                            <h2 class="text-xl font-black text-slate-800 tracking-tight" x-text="modalData.name"></h2>
                        </div>
                        <button @click="showModal = false" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-100 hover:bg-rose-50 transition-all flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-4 top-0 bottom-0 w-px bg-slate-100"></div>
                        
                        <div class="space-y-8 relative">
                            <template x-for="(v, index) in modalData.violations" :key="index">
                                <div class="flex items-start gap-6 group">
                                    <div class="relative">
                                        <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center z-10 relative group-hover:bg-rose-600 group-hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 pb-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest" x-text="v.type"></span>
                                            <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-lg" x-text="v.time"></span>
                                        </div>
                                        <p class="text-[13px] font-bold text-slate-600 leading-relaxed" x-text="v.details || 'Deteksi otomatis sistem'"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50/50 border-t border-slate-100 text-center">
                    <button @click="showModal = false" class="px-8 py-3 bg-white border border-slate-200 text-slate-800 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm">
                        Tutup Panel Log
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Card 1: Total Peserta -->
        <div class="bg-orange-500 p-5 rounded-2xl border border-orange-500 shadow-sm flex flex-col justify-between transition-all hover:shadow-md text-white">
            <div class="flex items-start justify-between">
                <h3 class="text-xs font-bold text-orange-100">Total Peserta</h3>
                <div class="p-2 bg-white/20 text-white rounded-xl">
                    <i class="ti ti-users text-base"></i>
                </div>
            </div>
            <p class="mt-4 text-3xl font-black">{{ $stats['total_participants'] }}</p>
        </div>
        
        <!-- Card 2: Sedang Ujian -->
        <div class="bg-amber-500 p-5 rounded-2xl border border-amber-500 shadow-sm flex flex-col justify-between transition-all hover:shadow-md text-white">
            <div class="flex items-start justify-between">
                <h3 class="text-xs font-bold text-amber-50">Sedang Ujian</h3>
                <div class="p-2 bg-white/20 text-white rounded-xl @if($stats['in_progress'] > 0) animate-pulse @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="mt-4 text-3xl font-black">{{ $stats['in_progress'] }}</p>
        </div>

        <!-- Card 3: Telah Selesai -->
        <div class="bg-emerald-600 p-5 rounded-2xl border border-emerald-600 shadow-sm flex flex-col justify-between transition-all hover:shadow-md text-white">
            <div class="flex items-start justify-between">
                <h3 class="text-xs font-bold text-emerald-50">Telah Selesai</h3>
                <div class="p-2 bg-white/20 text-white rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="mt-4 text-3xl font-black">{{ $stats['completed'] }}</p>
        </div>

        <!-- Card 4: Rata-rata Skor -->
        <div class="bg-indigo-600 p-5 rounded-2xl border border-indigo-600 shadow-sm flex flex-col justify-between transition-all hover:shadow-md text-white">
            <div class="flex items-start justify-between">
                <h3 class="text-xs font-bold text-indigo-50">Rata-rata Skor</h3>
                <div class="p-2 bg-white/20 text-white rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
            </div>
            <p class="mt-4 text-3xl font-black">{{ number_format($stats['avg_score'], 1) }}</p>
        </div>
    </div>

    {{-- Participant List Header with Select All --}}
    <div class="mb-4 mt-6 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-base text-slate-800">Daftar Peserta Ujian</h3>
            <p class="text-xs text-slate-500">Pemantauan aktivitas siswa secara langsung</p>
        </div>
        @if($results->count() > 0)
            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-655 cursor-pointer select-none">
                <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500 w-4 h-4">
                Pilih Semua
            </label>
        @endif
    </div>

    {{-- Bulk Action Bar --}}
    <div x-show="selectedIds.length > 0" 
         x-transition
         style="display: none;"
         class="bg-orange-50/70 border border-orange-100 p-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 shadow-sm">
        <div class="flex items-center gap-2 text-xs font-bold text-orange-900">
            <i class="ti ti-users-group text-base text-orange-600"></i>
            <span x-text="selectedIds.length + ' siswa terpilih'"></span>
        </div>
        <div class="flex items-center gap-2">
            <button @click="bulkForceFinish()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 border border-amber-600">
                Selesaikan Terpilih
            </button>
            <button @click="bulkReset()" class="px-4 py-2 bg-white hover:bg-rose-50 text-rose-600 border border-rose-250 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                Reset Terpilih
            </button>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($results as $result)
            <div class="bg-white border border-slate-150/80 rounded-2xl p-5 hover:shadow-md transition-all flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6">
                <!-- Checkbox Selection -->
                <div class="flex items-center shrink-0">
                    <input type="checkbox" :value="{{ $result->id }}" x-model="selectedIds" @change="updateSelectAll()" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500 w-4 h-4">
                </div>
                
                <!-- Left: Participant info & avatar -->
                <div class="flex items-center gap-4 min-w-0 md:w-1/4">
                    <div class="w-11 h-11 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center shrink-0">
                        <span class="text-lg font-black text-orange-600">{{ substr($result->user->name, 0, 1) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-base font-bold text-slate-800 truncate" title="{{ $result->user->name }}">{{ $result->user->name }}</p>
                        <p class="text-xs font-bold text-slate-450 truncate mt-0.5 flex items-center gap-1" title="{{ $result->user->email }}">
                            <i class="ti ti-mail text-slate-400"></i>
                            {{ $result->user->email }}
                        </p>
                    </div>
                </div>

                <!-- Columns on desktop: status, log waktu, pelanggaran, skor -->
                <div class="flex flex-1 flex-wrap md:flex-nowrap items-center justify-between gap-4 md:gap-6 border-y md:border-y-0 border-slate-100 py-3 md:py-0">
                    
                    <!-- Status Badge -->
                    <div class="flex flex-col md:items-center gap-1">
                        <span class="text-xs font-bold text-slate-400 block md:hidden">Status Pengerjaan</span>
                        @if($result->status === 'completed')
                            <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Selesai
                            </span>
                        @elseif($result->status === 'in_progress')
                            <span class="px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                Mengerjakan
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-500 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Belum Mulai
                            </span>
                        @endif
                    </div>

                    <!-- Progress Subtest / Waktu Mulai -->
                    <div class="flex flex-col md:items-center gap-1">
                        <span class="text-xs font-bold text-slate-400 block md:hidden">Riwayat Waktu</span>
                        <div class="text-xs font-bold text-slate-655 flex items-center gap-1.5">
                            <i class="ti ti-clock text-sm text-slate-400"></i>
                            <span>{{ $result->started_at ? $result->started_at->format('H:i:s') : '-' }}</span>
                            <span class="text-slate-350">→</span>
                            <span>{{ $result->completed_at ? $result->completed_at->format('H:i:s') : '...' }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">
                            {{ $result->started_at ? $result->started_at->format('d M Y') : 'Belum Mulai' }}
                        </span>
                    </div>

                    <!-- Pelanggaran Terdeteksi -->
                    <div class="flex flex-col md:items-center gap-1">
                        <span class="text-xs font-bold text-slate-400 block md:hidden">Pelanggaran Sistem</span>
                        @php
                            $violationCount = $result->violations->count();
                        @endphp
                        @if($violationCount > 0)
                            <div class="flex items-center gap-1.5 px-3 py-1 bg-rose-50 border border-rose-150 rounded-xl text-rose-600">
                                <i class="ti ti-alert-triangle text-sm"></i>
                                <span class="text-xs font-black">{{ $violationCount }}x Pelanggaran</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-50 rounded-xl text-slate-450">
                                <i class="ti ti-shield-check text-sm text-emerald-500"></i>
                                <span class="text-xs font-bold">Tertib / Aman</span>
                            </div>
                        @endif
                    </div>

                    <!-- Skor Akhir -->
                    <div class="flex flex-col md:items-center gap-1">
                        <span class="text-xs font-bold text-slate-400 block md:hidden">Skor Akhir</span>
                        <div class="inline-flex flex-col items-start md:items-center justify-center">
                            <div class="flex items-baseline gap-1">
                                <i class="ti ti-award text-base {{ $result->status === 'completed' ? 'text-amber-500' : 'text-slate-400' }}"></i>
                                <span class="text-xl font-black {{ $result->status === 'completed' ? 'text-orange-600' : 'text-amber-500' }}">{{ $result->total_score }}</span>
                                <span class="text-xs font-bold text-slate-400">Poin</span>
                            </div>
                            @if($result->status === 'in_progress')
                                <span class="text-xs font-bold text-amber-500 mt-0.5 animate-pulse">Live Tracker</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right/Action button -->
                <div class="flex items-center justify-end pt-4 md:pt-0 shrink-0 gap-2">
                    @if($result->status === 'completed')
                        <a href="{{ route('admin.exam-sessions.student-results', [$examSession, $result]) }}" class="w-full md:w-auto px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                            <i class="ti ti-eye text-sm"></i>
                            Lihat Hasil
                        </a>
                    @endif
                    @if($result->status === 'in_progress')
                        <form action="{{ route('admin.exam-sessions.force-finish', [$examSession, $result]) }}" method="POST" class="w-full md:w-auto" 
                              @submit.prevent="if (confirm('Yakin ingin menyelesaikan ujian siswa ini secara paksa?')) $el.submit()">
                            @csrf
                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"/></svg>
                                Selesaikan
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.exam-sessions.reset-student', [$examSession, $result]) }}" method="POST" class="w-full md:w-auto" 
                          @submit.prevent="if (confirm('Yakin ingin me-reset progress siswa ini? Semua jawabannya akan dihapus permanen!')) $el.submit()">
                        @csrf
                        <button type="submit" class="w-full md:w-auto px-4 py-2 bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:text-rose-600 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            Reset Ujian
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-16 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <p class="text-xs font-bold text-slate-400">Belum ada peserta yang mengikuti sesi ujian ini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Analytics Tab --}}
<div x-show="activeTab === 'analytics'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Distribution Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-6">
                Distribusi Skor Peserta (%)
            </h3>
            <div class="h-[300px] relative">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>

        {{-- Subject Comparison Chart --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-6">
                Performa per Subtest
            </h3>
            <div class="h-[300px] relative">
                <canvas id="subjectChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Subtest Detailed Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($subtestStats as $subtest)
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition-all flex items-center gap-4 group">
                <div class="p-3 bg-orange-50 text-orange-600 rounded-xl shrink-0 group-hover:bg-orange-500 group-hover:text-white transition-all">
                    <i class="ti ti-chart-bar text-xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-xs font-bold text-slate-800 leading-snug truncate" title="{{ $subtest['title'] }}">{{ $subtest['title'] }}</h4>
                    <div class="flex items-center justify-between gap-2 mt-1.5">
                        <span class="text-[11px] font-bold text-orange-600">{{ $subtest['percentage'] }}% Benar</span>
                        <span class="text-[10px] font-semibold text-slate-400">{{ $subtest['avg_correct'] }}/{{ $subtest['total_questions'] }} Soal</span>
                    </div>
                    <div class="mt-2 w-full h-1 bg-slate-50 rounded-full overflow-hidden border border-slate-100/50">
                        <div class="bg-orange-500 h-full rounded-full" style="width: {{ $subtest['percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Leaderboard Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Card Header (Unified Header) -->
        <div class="bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500">
                    <i class="ti ti-trophy text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Leaderboard (Peringkat Teratas)</h3>
                    <p class="text-[10px] text-slate-400">10 siswa dengan nilai tertinggi pada sesi ini</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-orange-500 text-white select-none">
                        <th class="px-6 py-3.5 text-xs font-bold text-white w-20">Peringkat</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-white">Peserta</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-white">Sekolah</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-white text-right">Skor Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($results->where('status', 'completed')->take(10) as $index => $res)
                        <tr class="hover:bg-orange-50/20 transition-all">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm
                                    @if($index === 0) bg-amber-400 text-white shadow-lg shadow-amber-200
                                    @elseif($index === 1) bg-slate-300 text-white shadow-lg shadow-slate-200
                                    @elseif($index === 2) bg-amber-600 text-white shadow-lg shadow-amber-300
                                    @else bg-slate-100 text-slate-500 @endif">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($res->user->name) }}&background=f97316&color=fff&bold=true" class="w-7 h-7 rounded-lg ring-2 ring-slate-50">
                                    <span class="text-sm font-bold text-slate-800">{{ $res->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-650">
                                {{ $res->user->school ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black text-orange-600">{{ $res->total_score }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Matrix Tab --}}
<div x-show="activeTab === 'matrix'" x-data="{ matrixTab: 'table' }" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Card Header -->
        <div class="bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                    <i class="ti ti-table text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Matriks Jawaban Siswa (Analisis Butir Soal)</h3>
                    <p class="text-[10px] text-slate-400">Analisis respon jawaban siswa (1 = Benar, 0 = Salah, - = Tidak Dijawab)</p>
                </div>
            </div>
        </div>

        <!-- Sub-tabs navigation -->
        <div class="flex items-center gap-1.5 p-4 border-b border-slate-100 bg-slate-50/30">
            <button @click="matrixTab = 'table'"
                :class="matrixTab === 'table' ? 'bg-orange-500 text-white shadow-xs' : 'text-slate-655 hover:bg-slate-100'"
                class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">
                Tabel Matriks
            </button>
            <button @click="matrixTab = 'questions'"
                :class="matrixTab === 'questions' ? 'bg-orange-500 text-white shadow-xs' : 'text-slate-655 hover:bg-slate-100'"
                class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">
                Daftar Soal
            </button>
        </div>

        <div x-show="matrixTab === 'table'" class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max text-xs">
                <thead>
                    <!-- Row 1: Subtest Names -->
                    <tr class="bg-slate-100 text-slate-700 border-b border-slate-200">
                        <th rowspan="2" class="px-6 py-4 font-bold border-r border-slate-200 align-middle sticky left-0 bg-slate-100 z-30 w-48 min-w-[12rem] max-w-[12rem]">Nama Peserta</th>
                        <th rowspan="2" class="px-6 py-4 font-bold border-r border-slate-200 align-middle sticky left-[12rem] bg-slate-100 z-30 w-64 min-w-[16rem] max-w-[16rem]">Pilihan Kampus</th>
                        @foreach($matrixSubtests as $subtest)
                            <th colspan="{{ $subtest['questions']->count() }}" class="px-3 py-2 text-center font-bold border-r border-slate-200">
                                {{ $subtest['title'] }}
                            </th>
                        @endforeach
                    </tr>
                    <!-- Row 2: Question Numbers -->
                    <tr class="bg-slate-50 text-slate-650 border-b border-slate-200">
                        @foreach($matrixSubtests as $subtest)
                            @foreach($subtest['questions'] as $index => $q)
                                <th class="px-2 py-2 text-center font-bold border-r border-slate-100 w-8" title="Soal ID: {{ $q->id }}">
                                    {{ $index + 1 }}
                                </th>
                            @endforeach
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $result)
                        @php
                            $studentAnswers = $result->answers->keyBy('question_id');
                        @endphp
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <!-- Student Name -->
                            <td class="px-6 py-3 border-r border-slate-200 font-medium text-slate-800 sticky left-0 bg-white group-hover:bg-slate-50 transition-colors z-10 w-48 min-w-[12rem] max-w-[12rem]">
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm">{{ $result->user->name }}</span>
                                    <span class="text-[9px] text-slate-450 block">{{ $result->user->school ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <!-- Pilihan Kampus -->
                            <td class="px-6 py-3 border-r border-slate-200 font-medium text-slate-800 sticky left-[12rem] bg-white group-hover:bg-slate-50 transition-colors z-10 w-64 min-w-[16rem] max-w-[16rem]">
                                @if($result->user->targets->count() > 0)
                                    <div class="flex flex-col gap-1.5">
                                        @foreach($result->user->targets as $target)
                                            @if($target->campusProdi)
                                                <span class="text-[9px] bg-slate-50 border border-slate-100 rounded px-2 py-0.5 text-slate-600 font-medium truncate block w-full" title="Pil {{ $target->order }}: {{ $target->campusProdi->campus_name }} - {{ $target->campusProdi->prodi_name }}">
                                                    Pil {{ $target->order }}: {{ $target->campusProdi->campus_name }} - {{ $target->campusProdi->prodi_name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-[9px] text-slate-400 italic">Belum memilih</span>
                                @endif
                            </td>
                            <!-- Student Answers -->
                            @foreach($matrixSubtests as $subtest)
                                @foreach($subtest['questions'] as $q)
                                    @php
                                        $ans = $studentAnswers->get($q->id);
                                        $isCorrect = $ans && $ans->is_correct;
                                    @endphp
                                    <td class="px-2 py-3 text-center border-r border-slate-100">
                                        @if($ans)
                                            @if($isCorrect)
                                                <span class="text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded text-[10px]">1</span>
                                            @else
                                                <span class="text-rose-500 font-bold bg-rose-50 px-1.5 py-0.5 rounded text-[10px]">0</span>
                                            @endif
                                        @else
                                            <span class="text-slate-350">-</span>
                                        @endif
                                    </td>
                                		@endforeach
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 1 + collect($matrixSubtests)->pluck('questions')->flatten()->count() }}" class="px-6 py-16 text-center">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada data peserta.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Question Reference Legend --}}
        <div x-show="matrixTab === 'questions'" class="p-6 bg-slate-50/20">
            <h4 class="text-xs font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Referensi Soal (Kunci Kolom Matriks)
            </h4>
            <div class="space-y-4">
                @foreach($matrixSubtests as $subtest)
                    <div class="bg-white rounded-xl border border-slate-100 p-4">
                        <h5 class="text-xs font-bold text-orange-600 mb-3">{{ $subtest['title'] }}</h5>
                        <div class="divide-y divide-slate-100">
                            @foreach($subtest['questions'] as $index => $q)
                                <div class="py-3.5 flex gap-3 text-xs leading-relaxed">
                                    <span class="w-6 h-6 rounded bg-orange-50 text-orange-600 flex items-center justify-center text-[10px] font-bold shrink-0">{{ $index + 1 }}</span>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-slate-700 font-semibold prose prose-sm max-w-none">{!! $q->content !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<script>
    function initCharts() {
        // Wrap in timeout to ensure Alpine has finished rendering tabs
        setTimeout(() => {
            const distributionCtx = document.getElementById('distributionChart');
            if (distributionCtx) {
                if (window.distChartInstance) window.distChartInstance.destroy();
                window.distChartInstance = new Chart(distributionCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($distribution)) !!},
                        datasets: [{
                            label: 'Jumlah Peserta',
                            data: {!! json_encode(array_values($distribution)) !!},
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            borderColor: '#f97316',
                            borderWidth: 2,
                            borderRadius: 8,
                            hoverBackgroundColor: '#f97316'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1, font: { weight: 'bold' } } },
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                        }
                    }
                });
            }

            const subjectCtx = document.getElementById('subjectChart');
            if (subjectCtx) {
                if (window.subjChartInstance) window.subjChartInstance.destroy();
                window.subjChartInstance = new Chart(subjectCtx, {
                    type: 'polarArea',
                    data: {
                        labels: {!! json_encode(collect($subtestStats)->pluck('title')) !!},
                        datasets: [{
                            data: {!! json_encode(collect($subtestStats)->pluck('percentage')) !!},
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.5)',
                                'rgba(16, 185, 129, 0.5)',
                                'rgba(245, 158, 11, 0.5)',
                                'rgba(239, 68, 68, 0.5)',
                                'rgba(139, 92, 246, 0.5)',
                                'rgba(236, 72, 153, 0.5)'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { font: { weight: 'bold', size: 10 } } } },
                        scales: { r: { ticks: { display: false }, grid: { circular: true } } }
                    }
                });
            }
        }, 50);
    }

    // Call on load
    initCharts();
</script>
