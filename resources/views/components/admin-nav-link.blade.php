@props(['active' => false, 'icon' => '', 'label' => '', 'href' => '#'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold rounded-xl transition-all duration-200 ' . ($active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800')]) }}>
    @if($icon)
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}" />
        </svg>
    @endif
    <span x-show="sidebarOpen" class="whitespace-nowrap truncate">{{ $label }}</span>
</a>
