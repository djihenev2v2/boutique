@extends('layouts.app')

@section('title', 'Mon Panier')
@section('page-title', 'Mon Panier')
@section('page-description', 'Vérifiez vos articles avant de commander')

@php
    $items    = $cart['items'] ?? [];
    $promoCode      = $cart['promo_code'] ?? null;
    $promoPercent   = $cart['promo_discount_percentage'] ?? false;
    $promoValue     = $cart['promo_discount_value'] ?? 0;

    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $discountAmount = $promoCode
        ? ($promoPercent ? round($subtotal * ($promoValue / 100), 2) : min($promoValue, $subtotal))
        : 0;
@endphp

@section('content')

@if(empty($items))
{{-- ── Empty cart ── --}}
<div class="max-w-lg mx-auto text-center py-20">
    <div class="w-24 h-24 mx-auto rounded-3xl bg-[#f2f4f6] flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
        </svg>
    </div>
    <h2 class="text-[20px] font-bold text-[#002352] mb-2">Votre panier est vide</h2>
    <p class="text-[13px] text-[#747780] mb-8">Découvrez nos produits et ajoutez des articles à votre panier.</p>
    <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-[#002352] text-white text-[14px] font-semibold px-6 py-3 rounded-xl hover:bg-[#18396e] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Explorer le catalogue
    </a>
</div>

@else
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ── LEFT: Items list ──────────────────────────────── --}}
        <div class="flex-1 space-y-3">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-[14px] font-bold text-[#002352]">
                    {{ count($items) }} article{{ count($items) > 1 ? 's' : '' }}
                </h2>
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-[12px] text-red-400 hover:text-red-600 font-medium transition-colors">
                        Vider le panier
                    </button>
                </form>
            </div>

            {{-- Items --}}
            @foreach($items as $variantId => $item)
            <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-4 flex gap-4 group">

                {{-- Thumbnail --}}
                <div class="w-20 h-20 flex-shrink-0 rounded-xl overflow-hidden bg-[#f8f9fb]">
                    @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <a href="{{ route('product.show', $item['slug']) }}" class="text-[14px] font-semibold text-[#002352] hover:text-[#18396e] transition-colors line-clamp-1">
                        {{ $item['name'] }}
                    </a>
                    @if($item['variant_label'])
                    <p class="text-[11px] text-[#747780] mt-0.5">{{ $item['variant_label'] }}</p>
                    @endif
                    @if($item['sku'])
                    <p class="text-[10px] text-[#c4c6d1] font-mono mt-0.5">SKU: {{ $item['sku'] }}</p>
                    @endif

                    <div class="flex items-center justify-between mt-3 flex-wrap gap-3">
                        {{-- Qty controls --}}
                        <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-1 bg-[#f2f4f6] rounded-xl p-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="variant_id" value="{{ $variantId }}">
                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-[#5d5f5f] hover:bg-white hover:text-[#002352] transition-all text-base font-bold">−</button>
                            <span class="w-8 text-center text-[13px] font-bold text-[#002352]">{{ $item['quantity'] }}</span>
                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-[#5d5f5f] hover:bg-white hover:text-[#002352] transition-all text-base font-bold">+</button>
                        </form>

                        {{-- Price --}}
                        <p class="text-[15px] font-bold text-[#002352]">
                            {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} DA
                        </p>
                    </div>
                </div>

                {{-- Remove --}}
                <form method="POST" action="{{ route('cart.remove', $variantId) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="self-start p-1.5 text-[#c4c6d1] hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endforeach

            {{-- Continue shopping --}}
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 text-[12px] text-[#747780] hover:text-[#002352] font-medium transition-colors pt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Continuer mes achats
            </a>
        </div>

        {{-- ── RIGHT: Summary ────────────────────────────────── --}}
        <div class="lg:w-80 space-y-4">

            {{-- Promo code --}}
            <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-5">
                <p class="text-[12px] font-bold text-[#002352] uppercase tracking-wider mb-3">Code Promo</p>
                @if($promoCode)
                <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 mb-2">
                    <div>
                        <p class="text-[13px] font-bold text-emerald-700">{{ $promoCode }}</p>
                        <p class="text-[11px] text-emerald-600">
                            @if($promoPercent) −{{ $promoValue }}% @else −{{ number_format($promoValue, 0, ',', ' ') }} DA @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('cart.promo.remove') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-emerald-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @else
                <form method="POST" action="{{ route('cart.promo') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="promo_code" placeholder="Entrez votre code"
                           class="flex-1 bg-[#f8f9fb] border border-[#edeef0] rounded-xl px-3 py-2 text-[13px] text-[#002352] placeholder-[#c4c6d1] focus:outline-none focus:border-[#002352] transition-colors uppercase">
                    <button type="submit" class="bg-[#002352] text-white font-semibold text-[12px] px-3 py-2 rounded-xl hover:bg-[#18396e] transition-colors">
                        Appliquer
                    </button>
                </form>
                @endif
            </div>

            {{-- Order summary --}}
            <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-5">
                <p class="text-[12px] font-bold text-[#002352] uppercase tracking-wider mb-4">Récapitulatif</p>

                <div class="space-y-3 text-[13px]">
                    <div class="flex justify-between text-[#5d5f5f]">
                        <span>Sous-total</span>
                        <span class="font-semibold text-[#002352]">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
                    </div>

                    @if($discountAmount > 0)
                    <div class="flex justify-between text-emerald-600">
                        <span>Réduction ({{ $promoCode }})</span>
                        <span class="font-semibold">−{{ number_format($discountAmount, 0, ',', ' ') }} DA</span>
                    </div>
                    @endif

                    <div class="flex justify-between text-[#5d5f5f]">
                        <span>Livraison</span>
                        <span class="font-semibold text-[#747780]">Calculée au checkout</span>
                    </div>

                    <div class="pt-3 border-t border-[#edeef0] flex justify-between">
                        <span class="font-bold text-[#002352]">Total estimé</span>
                        <span class="font-bold text-[18px] text-[#002352]">{{ number_format($subtotal - $discountAmount, 0, ',', ' ') }} DA</span>
                    </div>
                    <p class="text-[10px] text-[#747780]">* Livraison non incluse</p>
                </div>

                <a href="{{ route('checkout') }}"
                   class="mt-5 w-full flex items-center justify-center gap-2 bg-[#002352] text-white text-[14px] font-semibold py-3.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                    Passer la commande
                </a>
            </div>

        </div>
    </div>
</div>
@endif

@endsection
