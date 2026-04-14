@extends('layouts.app')

@section('title', $product->name)
@section('page-title', $product->name)
@section('page-description', $product->category?->name ?? 'Produit')

@section('content')

@php
    // Build variant map: "val1_id-val2_id-..." => {id, price, stock}
    $variantMap = [];
    foreach ($product->variants as $v) {
        $key = $v->attributeValues->sortBy('attribute_id')->pluck('id')->implode('-');
        $variantMap[$key] = ['id' => $v->id, 'price' => (float) $v->price, 'stock' => $v->stock, 'label' => $v->label];
    }

    // Group attribute values by attribute
    $attributeGroups = [];
    foreach ($product->variants as $v) {
        foreach ($v->attributeValues as $av) {
            $attrName = $av->attribute->name;
            $attributeGroups[$attrName][$av->id] = $av->value;
        }
    }

    $minPrice = $product->variants->min('price') ?? $product->base_price;
@endphp

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-[12px] text-[#747780] mb-5">
    <a href="{{ route('catalogue') }}" class="hover:text-[#002352] transition-colors">Catalogue</a>
    @if($product->category)
    <span>/</span>
    <a href="{{ route('catalogue', ['categories[]' => $product->category_id]) }}" class="hover:text-[#002352] transition-colors">{{ $product->category->name }}</a>
    @endif
    <span>/</span>
    <span class="text-[#002352] font-medium truncate max-w-[200px]">{{ $product->name }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

    {{-- ── Section 1 : Galerie ────────────────────────────────── --}}
    <div class="space-y-3">
        {{-- Image principale --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden aspect-square">
            @if($product->images->isNotEmpty())
                <img id="mainImage"
                     src="{{ asset('storage/' . $product->images->first()->path) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-[#f8f9fb]">
                    <svg class="w-16 h-16 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Miniatures --}}
        @if($product->images->count() > 1)
        <div class="flex gap-2 overflow-x-auto pb-1">
            @foreach($product->images as $img)
            <button onclick="setMainImage('{{ asset('storage/' . $img->path) }}')"
                    class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-[#f8f9fb] border-2 border-transparent hover:border-[#002352] transition-all">
                <img src="{{ asset('storage/' . $img->path) }}" alt="" class="w-full h-full object-cover">
            </button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── Section 2-4 : Infos + variantes + ajout panier ─────── --}}
    <div class="space-y-5">

        {{-- En-tête produit --}}
        <div>
            @if($product->category)
            <p class="text-[11px] text-[#747780] font-semibold uppercase tracking-widest mb-1">{{ $product->category->name }}</p>
            @endif
            <h1 class="text-[22px] font-bold text-[#002352] leading-tight mb-2">{{ $product->name }}</h1>
            @if($product->brand)
            <p class="text-[12px] text-[#747780]">Marque : <span class="font-semibold text-[#5d5f5f]">{{ $product->brand }}</span></p>
            @endif
        </div>

        {{-- Prix --}}
        <div class="flex items-baseline gap-2">
            <span id="priceDisplay" class="text-[28px] font-bold text-[#002352]">
                {{ number_format($minPrice, 0, ',', ' ') }} DA
            </span>
            <span id="pricePrefix" class="text-[13px] text-[#747780]">à partir de</span>
        </div>

        {{-- Disponibilité --}}
        <div id="stockBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-[#f2f4f6] text-[#747780]">
            <span class="w-2 h-2 rounded-full bg-[#c4c6d1]"></span>
            Sélectionnez une variante
        </div>

        {{-- Description --}}
        @if($product->description)
        <p class="text-[13px] text-[#5d5f5f] leading-relaxed">{{ $product->description }}</p>
        @endif

        {{-- Section 3 : Sélecteurs de variantes --}}
        @if(!empty($attributeGroups))
        <div class="space-y-4" id="variantSelectors"
             data-variant-map="{{ json_encode($variantMap) }}">
            @foreach($attributeGroups as $attrName => $values)
            <div>
                <p class="text-[12px] font-bold text-[#747780] uppercase tracking-widest mb-2">
                    {{ $attrName }} : <span id="label-{{ Str::slug($attrName) }}" class="text-[#002352] normal-case tracking-normal font-semibold"></span>
                </p>
                <div class="flex flex-wrap gap-2"
                     data-attr="{{ Str::slug($attrName) }}">
                    @foreach($values as $valId => $valLabel)
                    <button type="button"
                            class="variant-btn px-3 py-2 rounded-xl text-[13px] font-medium border border-[#edeef0] text-[#5d5f5f] hover:border-[#002352] transition-all select-none"
                            data-value-id="{{ $valId }}"
                            data-attr="{{ Str::slug($attrName) }}">
                        {{ $valLabel }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Section 4 : Quantité + Boutons --}}
        <div class="space-y-3 pt-2">
            {{-- Quantité --}}
            <div class="flex items-center gap-3">
                <p class="text-[12px] font-bold text-[#747780] uppercase tracking-widest">Quantité</p>
                <div class="flex items-center gap-1 bg-[#f2f4f6] rounded-xl p-1">
                    <button type="button" onclick="changeQty(-1)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-[#5d5f5f] hover:bg-white hover:text-[#002352] transition-all font-bold text-lg">−</button>
                    <input id="qtyInput" type="number" value="1" min="1" max="999" readonly
                           class="w-10 text-center text-[14px] font-bold text-[#002352] bg-transparent border-none outline-none">
                    <button type="button" onclick="changeQty(1)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-[#5d5f5f] hover:bg-white hover:text-[#002352] transition-all font-bold text-lg">+</button>
                </div>
            </div>

            {{-- CTA --}}
            <button id="addToCartBtn" disabled
                    onclick="addToCart()"
                    class="w-full bg-[#002352] text-white text-[14px] font-semibold py-3.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                Ajouter au panier
            </button>

            <button id="whatsappBtn"
                    class="w-full flex items-center justify-center gap-2.5 bg-[#25D366] text-white text-[14px] font-semibold py-3.5 rounded-xl hover:bg-[#1ebe5d] transition-colors shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Commander via WhatsApp
            </button>
        </div>

        {{-- Input caché : la variante choisie --}}
        <input type="hidden" id="selectedVariantId" value="">
    </div>
</div>

{{-- ── Section 5 : Produits similaires ──────────────────────── --}}
@if($similar->isNotEmpty())
<div>
    <h2 class="text-[16px] font-bold text-[#002352] mb-4">Produits similaires</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach($similar as $s)
        @php
            $sMin   = $s->variants->min('price') ?? $s->base_price;
            $sStock = $s->variants->sum('stock');
        @endphp
        <a href="{{ route('product.show', $s->slug) }}"
           class="group bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.04)] hover:shadow-[0px_8px_30px_rgba(24,57,110,0.12)] transition-all overflow-hidden flex flex-col">
            <div class="relative aspect-square bg-[#f8f9fb] overflow-hidden">
                @if($s->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $s->images->first()->path) }}" alt="{{ $s->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                        </svg>
                    </div>
                @endif
                @if($sStock === 0)
                <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Rupture</span>
                @endif
            </div>
            <div class="p-3">
                <p class="text-[12px] font-semibold text-[#002352] line-clamp-2 group-hover:text-[#18396e]">{{ $s->name }}</p>
                <p class="text-[12px] font-bold text-[#002352] mt-1">{{ number_format($sMin, 0, ',', ' ') }} DA</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="{{ asset('js/modules/product.js') }}"></script>
@endpush
