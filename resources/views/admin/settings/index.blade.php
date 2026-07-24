@extends('layouts.admin')

@section('page_title', 'Pengaturan Sistem')
@section('page_subtitle', 'Kelola identitas dan konfigurasi global platform CBT.')

@section('content')
<div x-data="{ activeTab: '{{ array_keys($settings->toArray())[0] ?? 'general' }}' }" class="max-w-4xl space-y-6">
    
    {{-- Tabs Navigation --}}
    <div class="border-b border-slate-200">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            @foreach($settings as $group => $items)
            <button type="button" @click="activeTab = '{{ $group }}'" 
                    :class="activeTab === '{{ $group }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-xs uppercase tracking-wider transition-all whitespace-nowrap bg-transparent">
                {{ str_replace('_', ' ', $group) }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        @foreach($settings as $group => $items)
        <div x-show="activeTab === '{{ $group }}'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            
            {{-- Group Header --}}
            <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Pengaturan {{ str_replace('_', ' ', $group) }}
                </h3>
            </div>
            
            {{-- Fields List --}}
            <div class="p-6 md:p-8 space-y-6">
                @foreach($items as $item)
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-700 flex items-center justify-between">
                        <span class="capitalize">{{ str_replace('_', ' ', $item->key) }}</span>
                        <code class="text-[10px] font-bold text-slate-400 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md">KEY: {{ $item->key }}</code>
                    </label>
                    
                    @if($item->type === 'image')
                        <div class="flex items-center gap-5 p-4 rounded-xl border border-slate-200 bg-slate-50/20">
                            {{-- Preview image --}}
                            <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                @if($item->value)
                                    <img src="{{ Storage::url($item->value) }}" class="w-full h-full object-contain p-1">
                                @else
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002-2z" />
                                    </svg>
                                @endif
                            </div>
                            
                            {{-- Input file --}}
                            <div class="flex-grow">
                                <input type="file" name="{{ $item->key }}" class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                                <p class="mt-1.5 text-[10px] text-slate-400">Pilih gambar baru untuk memperbarui (Format: PNG, JPG, maks 1MB)</p>
                            </div>
                        </div>
                    @else
                        <input type="text" name="{{ $item->key }}" value="{{ $item->value }}" 
                               class="w-full border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 py-2.5 px-4 transition-all bg-slate-50/20" 
                               placeholder="Masukkan {{ str_replace('_', ' ', $item->key) }}...">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Submit Button --}}
        <div class="flex items-center justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
