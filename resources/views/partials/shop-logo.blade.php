@php
    $logoPath = $logoPath ?? null;
    $shopName = $shopName ?? config('app.name', 'Boutique');

    $containerClass = $containerClass ?? 'w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center shadow-md shadow-[#002352]/20';
    $imageClass = $imageClass ?? 'w-full h-full object-contain p-1';
    $iconClass = $iconClass ?? 'w-4 h-4 text-white';
@endphp

<div class="{{ $containerClass }}">
    @if($logoPath)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($logoPath) }}" alt="{{ $shopName }}" class="{{ $imageClass }}">
    @else
        <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
        </svg>
    @endif
</div>
