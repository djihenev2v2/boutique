@php
/**
 * Category icon partial.
 * Variables:
 *   $icon  — the category's icon key (e.g. 'shirt', 'dress')
 *   $class — CSS class(es) for the <svg> element (default: 'w-5 h-5')
 */
$__iconSvgs = [
    'shirt' => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>',
    'dress' => '<path d="M9 2h6M9 2l-4.5 7 3 1v12h9V10l3-1L15 2"/><path d="M9 2c.6 1.8 1.6 3 3 3s2.4-1.2 3-3"/>',
    'pants' => '<path d="M4 3h16l1 9H3L4 3z"/><path d="M4 12l1.5 9H10V12m4 0v9h4.5L20 12"/>',
    'shoe'  => '<path d="M2 18h14c1.7 0 3-1.3 3-3v-1.5c1.7-.3 3-1.2 3-2.5s-1.3-2-3-2H13V5.5C13 4.1 11.9 3 10.5 3h-2C7.1 3 6 4.1 6 5.5V9H4C2.9 9 2 9.9 2 11v7z"/>',
    'sneaker' => '<path d="M2 17c0-1.5 1.3-2.5 3-2.5h4.5L11 9h2.5l.5 2.5 4 .5c1.7 0 3.5.8 3.5 2.5v1.5c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2v-1z"/><path d="M6 14.5l.5-2.5M10 14.5l.5-2.5"/>',
    'bag'   => '<path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>',
    'jacket'=> '<path d="M9 2L4 5.5V19h4.5v-7H12v7h7.5V5.5L15 2l-1.5 3a1.5 1.5 0 01-3 0L9 2z"/><path d="M9 2L7 8m8-6l2 6"/>',
    'hat'   => '<path d="M12 3C7.5 3 4 5.7 4 9c0 1.2.5 2.3 1.4 3.2L6.5 14H18l.8-1.8c.9-.9 1.7-2 1.7-3.2C20.5 5.7 16.5 3 12 3z"/><path d="M2 16h20v1.5C22 19 21 20 20 20H4c-1 0-2-1-2-2.5V16z"/>',
    'watch' => '<circle cx="12" cy="12" r="6"/><path d="M12 9v3l2 2M9 3.5h6M9 20.5h6"/>',
    'jewelry' => '<path d="M6 3h12l4 6-10 13L2 9l4-6zm0 0l4 6m6-6l-4 6M2 9h20M6 9l6 13M18 9l-6 13"/>',
    'sunglasses' => '<circle cx="7" cy="12" r="3.5"/><circle cx="17" cy="12" r="3.5"/><path d="M10.5 12h3M3 11L1.5 9M21 11l1.5-2"/>',
    'kids'  => '<circle cx="12" cy="8" r="4"/><path d="M4 20c0-3.5 3.6-6 8-6s8 2.5 8 6"/><path d="M18.5 3l.7 2-1.7-.8.8 1.8L16.5 5l.8 1.7-1.7-.7.7 2" opacity="0.65"/>',
    'sport' => '<path d="M13 2L4.5 13.5H11L9 22l9.5-12H12.5L13 2z"/>',
    'accessories' => '<path d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><circle cx="6.25" cy="6.25" r="1" fill="currentColor" stroke="none"/>',
    'perfume' => '<rect x="7" y="9" width="10" height="12" rx="2"/><path d="M10 9V7c0-1 .9-1.5 2-1.5S14 6 14 7v2"/><path d="M10 5.5h-.5C8.7 5.5 8 5 8 4.2V4c0-.9.7-1.5 1.5-1.5H11"/><path d="M14 5.5h.5c.8 0 1.5-.5 1.5-1.3V4c0-.9-.7-1.5-1.5-1.5H13"/><path d="M10 13h4M10 16h4"/>',
    'folder'=> '<path d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>',
];
$__paths   = $__iconSvgs[$icon ?? ''] ?? $__iconSvgs['folder'];
$__class   = $class ?? 'w-5 h-5';
@endphp
<svg class="{{ $__class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $__paths !!}</svg>
