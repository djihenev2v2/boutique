@extends('layouts.admin')

@php
$iconMap = [
    'shirt' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/></svg>',
        'label' => 'Haut / T-Shirt'
    ],
    'dress' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2h6M9 2l-4.5 7 3 1v12h9V10l3-1L15 2"/><path d="M9 2c.6 1.8 1.6 3 3 3s2.4-1.2 3-3"/></svg>',
        'label' => 'Robe'
    ],
    'pants' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 3h16l1 9H3L4 3z"/><path d="M4 12l1.5 9H10V12m4 0v9h4.5L20 12"/></svg>',
        'label' => 'Pantalon'
    ],
    'shoe' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18h14c1.7 0 3-1.3 3-3v-1.5c1.7-.3 3-1.2 3-2.5s-1.3-2-3-2H13V5.5C13 4.1 11.9 3 10.5 3h-2C7.1 3 6 4.1 6 5.5V9H4C2.9 9 2 9.9 2 11v7z"/></svg>',
        'label' => 'Chaussure'
    ],
    'sneaker' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 17c0-1.5 1.3-2.5 3-2.5h4.5L11 9h2.5l.5 2.5 4 .5c1.7 0 3.5.8 3.5 2.5v1.5c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2v-1z"/><path d="M6 14.5l.5-2.5M10 14.5l.5-2.5"/></svg>',
        'label' => 'Basket'
    ],
    'bag' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>',
        'label' => 'Sac'
    ],
    'jacket' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2L4 5.5V19h4.5v-7H12v7h7.5V5.5L15 2l-1.5 3a1.5 1.5 0 01-3 0L9 2z"/><path d="M9 2L7 8m8-6l2 6"/></svg>',
        'label' => 'Veste'
    ],
    'hat' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3C7.5 3 4 5.7 4 9c0 1.2.5 2.3 1.4 3.2L6.5 14H18l.8-1.8c.9-.9 1.7-2 1.7-3.2C20.5 5.7 16.5 3 12 3z"/><path d="M2 16h20v1.5C22 19 21 20 20 20H4c-1 0-2-1-2-2.5V16z"/></svg>',
        'label' => 'Chapeau'
    ],
    'watch' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="6"/><path d="M12 9v3l2 2M9 3.5h6M9 20.5h6"/></svg>',
        'label' => 'Montre'
    ],
    'jewelry' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9l4-6zm0 0l4 6m6-6l-4 6M2 9h20M6 9l6 13M18 9l-6 13"/></svg>',
        'label' => 'Bijoux'
    ],
    'sunglasses' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="7" cy="12" r="3.5"/><circle cx="17" cy="12" r="3.5"/><path d="M10.5 12h3M3 11L1.5 9M21 11l1.5-2"/></svg>',
        'label' => 'Lunettes'
    ],
    'kids' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-3.5 3.6-6 8-6s8 2.5 8 6"/><path d="M18.5 3l.7 2-1.7-.8.8 1.8L16.5 5l.8 1.7-1.7-.7.7 2" opacity="0.65"/></svg>',
        'label' => 'Enfant'
    ],
    'sport' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L4.5 13.5H11L9 22l9.5-12H12.5L13 2z"/></svg>',
        'label' => 'Sport'
    ],
    'accessories' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><circle cx="6.25" cy="6.25" r="1" fill="currentColor" stroke="none"/></svg>',
        'label' => 'Accessoires'
    ],
    'perfume' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="7" y="9" width="10" height="12" rx="2"/><path d="M10 9V7c0-1 .9-1.5 2-1.5S14 6 14 7v2"/><path d="M10 5.5h-.5C8.7 5.5 8 5 8 4.2V4c0-.9.7-1.5 1.5-1.5H11"/><path d="M14 5.5h.5c.8 0 1.5-.5 1.5-1.3V4c0-.9-.7-1.5-1.5-1.5H13"/><path d="M10 13h4M10 16h4"/></svg>',
        'label' => 'Parfum'
    ],
    'folder' => [
        'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>',
        'label' => 'Général'
    ],
];
@endphp

@section('title', 'Catégories')
@section('page-title', 'Catégories')
@section('page-description', 'Gérez les catégories et sous-catégories de votre boutique')

@section('header-actions')
    <button onclick="openModal('modalAdd')"
            class="inline-flex items-center gap-2 bg-[#002352] text-white text-[12px] font-semibold px-4 py-2 rounded-full shadow-sm hover:bg-[#18396e] transition-all">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Ajouter une catégorie
    </button>
@endsection

@section('content')

<div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden">

    {{-- Search bar --}}
    <div class="px-6 py-4 border-b border-[#f2f4f6] flex items-center gap-3">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2 flex-1 max-w-sm">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Rechercher une catégorie..."
                       class="w-full pl-9 pr-3 py-2 bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl border-none outline-none placeholder-[#9ca3af] focus:ring-2 focus:ring-[#002352]/20">
            </div>
            <button type="submit" class="bg-[#002352] text-white text-[12px] font-semibold px-4 py-2 rounded-xl hover:bg-[#18396e] transition-colors">
                Rechercher
            </button>
            @if($search)
            <a href="{{ route('admin.categories.index') }}"
               class="text-[12px] text-[#747780] hover:text-[#002352] font-medium px-2 py-2 rounded-xl hover:bg-[#f2f4f6] transition-colors whitespace-nowrap">
                Réinitialiser
            </a>
            @endif
        </form>
        <div class="ml-auto text-[12px] text-[#747780]">
            {{ $tree ? $tree->count() . ' catégorie(s) racine' : ($categories?->count() ?? 0) . ' résultat(s)' }}
        </div>
    </div>

    {{-- Categories tree / flat list --}}
    <div class="p-4 space-y-2">

        @if($search && $categories !== null)
            {{-- Flat filtered results --}}
            @forelse($categories as $cat)
                <div class="flex items-center gap-3 px-4 py-3 bg-[#f8f9fb] rounded-xl group hover:bg-[#f2f4f6] transition-colors">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-4 h-4 text-[#002352]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                        </svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-[#002352] flex items-center gap-2">
                            @if($cat->icon && isset($iconMap[$cat->icon]))
                                <span class="w-4 h-4 flex-shrink-0 text-[#002352]">{!! $iconMap[$cat->icon]['svg'] !!}</span>
                            @endif
                            {{ $cat->name }}
                        </p>
                        @if($cat->parent)
                        <p class="text-[11px] text-[#747780]">Sous-catégorie de : {{ $cat->parent->name }}</p>
                        @endif
                    </div>
                    <span class="text-[11px] text-[#747780] bg-white px-2.5 py-1 rounded-lg shadow-sm font-medium">
                        {{ $cat->products->count() }} produit(s)
                    </span>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->parent_id ?? '' }}', '{{ $cat->icon ?? '' }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-[#002352] hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </button>
                        <button onclick="confirmDelete({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->products->count() }}, {{ $cat->children->count() }})"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-red-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-16 flex flex-col items-center gap-3 text-[#9ca3af]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <p class="text-[13px] font-medium">Aucune catégorie ne correspond à « {{ $search }} »</p>
                </div>
            @endforelse

        @elseif($tree !== null)
            {{-- Tree view --}}
            @forelse($tree as $parent)
                {{-- Parent category --}}
                <div class="rounded-xl overflow-hidden border border-[#f2f4f6]">
                    <div class="flex items-center gap-3 px-4 py-3 bg-[#f8f9fb] group hover:bg-[#f2f4f6] transition-colors">
                        <button onclick="toggleChildren('children-{{ $parent->id }}')"
                                class="w-6 h-6 flex items-center justify-center rounded-md text-[#747780] hover:bg-white transition-all flex-shrink-0">
                            <svg id="chevron-{{ $parent->id }}" class="w-3.5 h-3.5 transition-transform {{ $parent->children->isEmpty() ? 'opacity-0' : '' }}"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>
                        <span class="w-8 h-8 rounded-lg bg-[#002352] flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                            </svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-bold text-[#002352] flex items-center gap-2">
                                @if($parent->icon && isset($iconMap[$parent->icon]))
                                    <span class="w-4 h-4 flex-shrink-0 text-[#002352]">{!! $iconMap[$parent->icon]['svg'] !!}</span>
                                @endif
                                {{ $parent->name }}
                            </p>
                            @if($parent->children->isNotEmpty())
                            <p class="text-[11px] text-[#747780]">{{ $parent->children->count() }} sous-catégorie(s)</p>
                            @endif
                        </div>
                        <span class="text-[11px] text-[#747780] bg-white px-2.5 py-1 rounded-lg shadow-sm font-medium">
                            {{ $parent->products->count() }} produit(s)
                        </span>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openEdit({{ $parent->id }}, '{{ addslashes($parent->name) }}', '', '{{ $parent->icon ?? '' }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-[#002352] hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                </svg>
                            </button>
                            <button onclick="confirmDelete({{ $parent->id }}, '{{ addslashes($parent->name) }}', {{ $parent->products->count() }}, {{ $parent->children->count() }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-red-600 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Children --}}
                    @if($parent->children->isNotEmpty())
                    <div id="children-{{ $parent->id }}" class="border-t border-[#f2f4f6] divide-y divide-[#f2f4f6]">
                        @foreach($parent->children as $child)
                        <div class="flex items-center gap-3 px-4 py-3 pl-14 bg-white group hover:bg-[#f8f9fb] transition-colors">
                            <span class="w-6 h-6 rounded-md bg-[#f2f4f6] flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                                </svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-medium text-[#002352] flex items-center gap-2">
                                    @if($child->icon && isset($iconMap[$child->icon]))
                                        <span class="w-4 h-4 flex-shrink-0 text-[#002352]">{!! $iconMap[$child->icon]['svg'] !!}</span>
                                    @endif
                                    {{ $child->name }}
                                </p>
                            </div>
                            <span class="text-[11px] text-[#747780] bg-[#f2f4f6] px-2.5 py-1 rounded-lg font-medium">
                                {{ $child->products->count() }} produit(s)
                            </span>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEdit({{ $child->id }}, '{{ addslashes($child->name) }}', '{{ $child->parent_id }}', '{{ $child->icon ?? '' }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-[#002352] hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                </button>
                                <button onclick="confirmDelete({{ $child->id }}, '{{ addslashes($child->name) }}', {{ $child->products->count() }}, 0)"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-red-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @empty
                <div class="py-16 flex flex-col items-center gap-3 text-[#9ca3af]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                    </svg>
                    <p class="text-[13px] font-medium">Aucune catégorie créée pour le moment.</p>
                    <button onclick="openModal('modalAdd')" class="text-[12px] text-[#18396e] font-semibold hover:underline">
                        + Créer la première catégorie
                    </button>
                </div>
            @endforelse
        @endif
    </div>
</div>

{{-- ══════════════════════════ MODAL ADD ══════════════════════════ --}}
<div id="modalAdd" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/50 backdrop-blur-sm" onclick="closeModal('modalAdd')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[16px] font-bold text-[#002352]">Ajouter une catégorie</h3>
            <button onclick="closeModal('modalAdd')" class="w-8 h-8 flex items-center justify-center rounded-xl text-[#747780] hover:bg-[#f2f4f6] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Nom de la catégorie *</label>
                <input type="text" name="name" required maxlength="255"
                       placeholder="Ex: Chaussures Homme"
                       class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none placeholder-[#9ca3af] focus:ring-2 focus:ring-[#002352]/20">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Catégorie parente</label>
                <select name="parent_id"
                        class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none cursor-pointer">
                    <option value="">— Catégorie racine —</option>
                    @foreach($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Icon picker --}}
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-2">Icône <span class="font-normal text-[#9ca3af] normal-case tracking-normal">(auto-suggérée selon le nom)</span></label>
                <input type="hidden" name="icon" id="addIconVal" value="">
                <div class="grid grid-cols-5 gap-1.5 p-3 bg-[#f2f4f6] rounded-xl max-h-52 overflow-y-auto">
                    <button type="button" data-icon-prefix="add" data-icon-key="" onclick="selectIcon('add','')"
                            class="flex flex-col items-center gap-1 p-2 rounded-xl text-[#9ca3af] hover:bg-white hover:text-[#374151] transition-all text-[18px] leading-none">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span class="text-[9px]">Aucune</span>
                    </button>
                    @foreach($iconMap as $key => $ic)
                    <button type="button" data-icon-prefix="add" data-icon-key="{{ $key }}" onclick="selectIcon('add','{{ $key }}')"
                            class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-white hover:shadow-sm transition-all group" title="{{ $ic['label'] }}">
                        <span class="w-6 h-6 flex items-center justify-center text-[#002352] group-hover:scale-110 transition-transform">{!! $ic['svg'] !!}</span>
                        <span class="text-[8.5px] text-[#747780] truncate w-full text-center leading-tight">{{ $ic['label'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalAdd')"
                        class="flex-1 bg-[#f2f4f6] text-[#5d5f5f] text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#edeef0] transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 bg-[#002352] text-white text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════ MODAL EDIT ══════════════════════════ --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/50 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[16px] font-bold text-[#002352]">Modifier la catégorie</h3>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 flex items-center justify-center rounded-xl text-[#747780] hover:bg-[#f2f4f6] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Nom de la catégorie *</label>
                <input id="editName" type="text" name="name" required maxlength="255"
                       class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none focus:ring-2 focus:ring-[#002352]/20">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Catégorie parente</label>
                <select id="editParent" name="parent_id"
                        class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none cursor-pointer">
                    <option value="">— Catégorie racine —</option>
                    @foreach($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Icon picker --}}
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-2">Icône</label>
                <input type="hidden" name="icon" id="editIconVal" value="">
                <div class="grid grid-cols-5 gap-1.5 p-3 bg-[#f2f4f6] rounded-xl max-h-52 overflow-y-auto">
                    <button type="button" data-icon-prefix="edit" data-icon-key="" onclick="selectIcon('edit','')"
                            class="flex flex-col items-center gap-1 p-2 rounded-xl text-[#9ca3af] hover:bg-white hover:text-[#374151] transition-all">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span class="text-[9px]">Aucune</span>
                    </button>
                    @foreach($iconMap as $key => $ic)
                    <button type="button" data-icon-prefix="edit" data-icon-key="{{ $key }}" onclick="selectIcon('edit','{{ $key }}')"
                            class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-white hover:shadow-sm transition-all group" title="{{ $ic['label'] }}">
                        <span class="w-6 h-6 flex items-center justify-center text-[#002352] group-hover:scale-110 transition-transform">{!! $ic['svg'] !!}</span>
                        <span class="text-[8.5px] text-[#747780] truncate w-full text-center leading-tight">{{ $ic['label'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalEdit')"
                        class="flex-1 bg-[#f2f4f6] text-[#5d5f5f] text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#edeef0] transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 bg-[#002352] text-white text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════ MODAL DELETE ══════════════════════════ --}}
<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/50 backdrop-blur-sm" onclick="closeModal('modalDelete')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
        <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h3 class="text-[16px] font-bold text-[#002352] mb-2">Supprimer la catégorie ?</h3>
        <p id="deleteMessage" class="text-[12px] text-[#747780] mb-5"></p>
        <form id="deleteForm" method="POST" action="">
            @csrf @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modalDelete')"
                        class="flex-1 bg-[#f2f4f6] text-[#5d5f5f] text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#edeef0] transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 bg-red-600 text-white text-[13px] font-semibold py-2.5 rounded-xl hover:bg-red-700 transition-colors">
                    Supprimer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/modules/categories.js') }}"></script>
@endpush
