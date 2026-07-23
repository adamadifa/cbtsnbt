@extends('layouts.admin')

@section('page_title', 'Kelola Soal Subtest: ' . ($examSubtest->title ?: $examSubtest->subject->name))

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" x-data="questionManager()">
    <div>
        <a href="{{ route('admin.exam-packages.edit', $examPackage) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-blue-600 mb-2.5 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M5 12l14 0"></path>
                <path d="M5 12l6 6"></path>
                <path d="M5 12l6 -6"></path>
            </svg>
            Kembali ke Edit Paket
        </a>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Kelola Soal: {{ $examSubtest->subject->name }}</h1>
        <p class="text-xs text-slate-400 mt-1">Pilih dan tentukan soal-soal yang masuk ke dalam subtest ini.</p>
    </div>
    
    <div class="flex items-center gap-4 text-xs text-slate-400 self-start sm:self-center">
        <div class="px-4 py-2 bg-blue-50/50 border border-blue-100 rounded-xl flex items-center gap-3">
            <span class="text-[10px] font-bold text-[#153c96] uppercase tracking-wider">Terpilih: <span x-text="selectedCount()"></span> / {{ $examSubtest->total_questions }}</span>
            <div class="w-20 h-1.5 bg-blue-100 rounded-full overflow-hidden shrink-0">
                <div class="h-full bg-[#153c96] transition-all duration-500" :style="`width: ${Math.min((selectedCount() / {{ $examSubtest->total_questions }}) * 100, 100)}%`"></div>
            </div>
        </div>
    </div>
</div>

<div x-data="questionManager()">
    <form action="{{ route('admin.exam-packages.subtests.update-questions', [$examPackage, $examSubtest]) }}" method="POST">
        @csrf
        
        <!-- Filters & Search Toolbar (Outside the Card) -->
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="w-full md:max-w-md relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                        <path d="M21 21l-6 -6"></path>
                    </svg>
                </div>
                <input type="text" x-model="search" 
                    class="block w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-100 text-xs transition-all focus:outline-none" 
                    placeholder="Cari konten soal...">
            </div>
            
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Menampilkan total {{ count($availableQuestions) }} soal tersedia
            </div>
        </div>

        <!-- Questions Table Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Card Header (Unified Blue Bar) -->
            <div class="bg-[#153c96] text-white px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                            <path d="M9 14l2 2l4 -4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm tracking-wide">Pilih Soal Subtest</h3>
                        <p class="text-[10px] text-white/70">Centang kotak untuk memasukkan soal ke dalam subtest ini</p>
                    </div>
                </div>
            </div>

            <!-- Questions Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#153c96] text-white select-none">
                            <th class="px-6 py-4 w-12 text-center text-white/95">
                                <input type="checkbox" @change="toggleAll($event)" class="w-4.5 h-4.5 text-[#153c96] border-white/25 rounded-md focus:ring-0 focus:outline-none cursor-pointer bg-white/10">
                            </th>
                            <th class="px-6 py-4 w-20 text-xs font-bold uppercase tracking-wider text-white/95">ID</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Konten Soal</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95">Tipe</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-white/95 text-right">Bobot Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($availableQuestions as $question)
                        <tr class="hover:bg-slate-50/20 transition-colors group" x-show="matchesSearch(`{{ addslashes(strip_tags($question->content)) }}`)">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" 
                                       x-model="selectedQuestions"
                                       class="w-4.5 h-4.5 text-[#153c96] border-slate-200 rounded-md focus:ring-4 focus:ring-blue-100 cursor-pointer">
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-400">#{{ $question->id }}</td>
                            <td class="px-6 py-4 max-w-xl">
                                <div class="text-xs font-medium text-slate-700 line-clamp-2 leading-relaxed">
                                    {!! strip_tags($question->content) !!}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-tight bg-slate-100 text-slate-500">
                                    {{ str_replace('_', ' ', $question->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-bold text-slate-800">
                                {{ $question->points }} Poin
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="p-4 bg-slate-50 text-slate-300 rounded-2xl">
                                        📭
                                    </div>
                                    <p class="text-slate-400 font-bold">Tidak ada bank soal yang tersedia untuk materi uji ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Save Bar -->
            <div class="p-6 border-t border-slate-50 bg-slate-50/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Minimal Soal Subtest: {{ $examSubtest->total_questions }}</p>
                    <template x-if="selectedCount() < {{ $examSubtest->total_questions }}">
                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-xl text-[9px] font-black uppercase tracking-wider border border-amber-100 flex items-center gap-1">
                            ⚠️ Pilihan masih kurang
                        </span>
                    </template>
                </div>
                
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-[#153c96] hover:bg-blue-800 text-white rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/10 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                    Simpan Pilihan Soal
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function questionManager() {
        return {
            search: '',
            selectedQuestions: [
                @foreach($examSubtest->questions as $q)
                '{{ $q->id }}',
                @endforeach
            ],

            selectedCount() {
                return this.selectedQuestions.length;
            },

            matchesSearch(content) {
                if (!this.search) return true;
                return content.toLowerCase().includes(this.search.toLowerCase());
            },

            toggleAll(e) {
                if (e.target.checked) {
                    // select all visible questions
                    const visibleIds = [
                        @foreach($availableQuestions as $q)
                        '{{ $q->id }}',
                        @endforeach
                    ];
                    this.selectedQuestions = [...new Set([...this.selectedQuestions, ...visibleIds])];
                } else {
                    this.selectedQuestions = [];
                }
            }
        }
    }
</script>
@endpush
