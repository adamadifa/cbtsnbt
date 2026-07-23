@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white rounded-xl shadow-sm transition-all duration-200 py-3']) }}>
