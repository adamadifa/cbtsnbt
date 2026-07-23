@extends('layouts.admin')

@section('page_title', 'Pengaturan Sistem')
@section('page_subtitle', 'Kelola identitas dan konfigurasi global platform CBT.')

@section('content')
<div x-data="{ activeTab: '{{ array_keys($settings->toArray())[0] ?? 'general' }}' }" class="max-w-4xl space-y-8">
    
    {{-- Tabs --}}
    <div class="flex items-center gap-2 p-1 bg-white border border-slate-100 rounded-2xl w-fit shadow-sm overflow-x-auto custom-scrollbar">
        @foreach($settings as $group => $items)
        <button @click="activeTab = '{{ $group }}'" 
                :class="activeTab === '{{ $group }}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-extrabold uppercase tracking-widest transition-all whitespace-nowrap">
            {{ strtoupper($group) }}
        </button>
        @endforeach
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        @foreach($settings as $group => $items)
        <div x-show="activeTab === '{{ $group }}'" x-transition class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-indigo-600 rounded-full"></div>
                    Kelola Pengaturan {{ ucfirst($group) }}
                </h3>
            </div>
            
            <div class="p-10 space-y-8">
                @foreach($items as $item)
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 uppercase tracking-widest flex items-center justify-between">
                        {{ str_replace('_', ' ', $item->key) }}
                        <span class="text-[9px] font-bold text-slate-400">KEY: {{ $item->key }}</span>
                    </label>
                    
                    @if($item->type === 'image')
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                @if($item->value)
                                    <img src="{{ Storage::url($item->value) }}" class="w-full h-full object-contain">
                                @else
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002-2z" /></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="{{ $item->key }}" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                                <p class="mt-2 text-[10px] font-bold text-slate-400">Pilih file gambar baru untuk mengganti. Format: PNG, JPG (Maks 1MB)</p>
                            </div>
                        </div>
                    @else
                        <input type="text" name="{{ $item->key }}" value="{{ $item->value }}" class="w-full h-14 px-6 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-600/20 transition-all" placeholder="Masukkan {{ str_replace('_', ' ', $item->key) }}...">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="flex items-center justify-end">
            <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-600/30 hover:bg-slate-900 transition-all flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
