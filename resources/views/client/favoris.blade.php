@extends('layouts.app')

@section('title', 'Mes Favoris')
@section('page-title', 'Mes Favoris')
@section('page-description', 'Vos articles préférés sauvegardés')

@section('content')

@if($favorites->isEmpty())
{{-- Empty state --}}
<div class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl shadow-[0px_4px_20px_rgba(24,57,110,0.05)]">
    <div class="w-20 h-20 rounded-3xl bg-red-50 flex items-center justify-center mb-5">
        <svg class="w-10 h-10 text-red-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.239-4.5-5-4.5-1.876 0-3.51.93-4.337 2.306a5.84 5.84 0 00-.326.6 5.84 5.84 0 00-.326-.6C10.51 4.68 8.876 3.75 7 3.75c-2.761 0-5 2.015-5 4.5 0 7.22 9.337 12 9.337 12S21 15.47 21 8.25z"/>
        </svg>
    </div>
    <h2 class="text-[15px] font-bold text-[#002352] mb-1.5">Aucun favori pour l'instant</h2>
    <p class="text-[13px] text-[#747780] text-center max-w-xs mb-6">Parcourez notre catalogue et cliquez sur le ❤ pour sauvegarder vos articles préférés.</p>
    <a href="{{ route('catalogue') }}"
       class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#002352] text-white text-[13px] font-semibold rounded-full shadow-md shadow-[#002352]/20 hover:bg-[#18396e] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5A2.25 2.25 0 0118.75 19.5H5.25A2.25 2.25 0 013 17.25V6.75z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75h18"/>
        </svg>
        Explorer le catalogue
    </a>
</div>

@else
{{-- Header avec compteur --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-red-50 flex items-center justify-center">
            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-[16px] font-bold text-[#002352]">Mes Favoris</h1>
            <p class="text-[12px] text-[#747780]">{{ $favorites->count() }} article{{ $favorites->count() > 1 ? 's' : '' }} sauvegardé{{ $favorites->count() > 1 ? 's' : '' }}</p>
        </div>
    </div>
    <a href="{{ route('catalogue') }}"
       class="inline-flex items-center gap-1.5 text-[12px] font-medium text-[#18396e] hover:text-[#002352] transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Continuer mes achats
    </a>
</div>

{{-- Grid produits favoris --}}
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach($favorites as $fav)
    @php
        $product    = $fav->product;
        $totalStock = $product->variants->sum('stock');
        $minPrice   = $product->variants->min('price') ?? $product->base_price;
    @endphp

    @if($product)
    <div class="group bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.04)] hover:shadow-[0px_8px_30px_rgba(24,57,110,0.12)] transition-all duration-200 overflow-hidden flex flex-col">

        {{-- Image + lien produit --}}
        <a href="{{ route('product.show', $product->slug) }}" class="relative aspect-square bg-[#f8f9fb] overflow-hidden block">
            @if($product->images->isNotEmpty())
                <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                </div>
            @endif

            {{-- Rupture badge --}}
            @if($totalStock === 0)
            <div class="absolute top-2 left-2">
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Rupture</span>
            </div>
            @endif

            {{-- Bouton retirer favori --}}
            <form method="POST" action="{{ route('favoris.toggle') }}" class="absolute top-2 right-2" onclick="event.stopPropagation();">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit"
                        class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center shadow-sm hover:bg-red-600 transition-colors"
                        title="Retirer des favoris">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                    </svg>
                </button>
            </form>
        </a>

        {{-- Info --}}
        <div class="p-3 flex-1 flex flex-col gap-1">
            @if($product->category)
            <p class="text-[10px] text-[#747780] font-medium uppercase tracking-wide">{{ $product->category->name }}</p>
            @endif
            <a href="{{ route('product.show', $product->slug) }}"
               class="text-[13px] font-semibold text-[#002352] leading-tight line-clamp-2 hover:text-[#18396e] transition-colors">
                {{ $product->name }}
            </a>
            <p class="text-[13px] font-bold text-[#002352] mt-auto pt-2">
                À partir de {{ number_format($minPrice, 0, ',', ' ') }} DA
            </p>

            {{-- Bouton ajouter au panier --}}
            @if($totalStock > 0)
            <a href="{{ route('product.show', $product->slug) }}"
               class="mt-2 w-full text-center bg-[#002352] text-white text-[11px] font-semibold py-2 rounded-xl hover:bg-[#18396e] transition-colors">
                Voir le produit
            </a>
            @else
            <span class="mt-2 w-full text-center bg-[#f2f4f6] text-[#747780] text-[11px] font-semibold py-2 rounded-xl cursor-not-allowed block">
                Rupture de stock
            </span>
            @endif
        </div>
    </div>
    @endif
    @endforeach
</div>
@endif

@endsection