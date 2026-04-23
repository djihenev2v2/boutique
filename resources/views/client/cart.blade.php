@php
    $shopName  ??= config('app.name', 'Boutique');
    $logoPath  ??= null;
    $shopPhone ??= '';
    $shopEmail ??= '';
    $cartCount = \App\Http\Controllers\Client\CartController::getCount();

    $deliveryFee = 0; // Can be made dynamic later
    $total = $subtotal + $deliveryFee;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mon Panier — {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F8F6; }

        /* ─── QTY STEPPER ───────────────────────────── */
        .qty-btn {
            width: 32px; height: 32px;
            border: 1px solid #E5E7EB; border-radius: 8px;
            background: #F9FAFB;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 17px; font-weight: 500; color: #374151;
            transition: all .15s; user-select: none; line-height: 1;
        }
        .qty-btn:hover { background: #002352; color: #fff; border-color: #002352; }
        .qty-input {
            width: 42px; text-align: center;
            border: 1px solid #E5E7EB; border-radius: 8px;
            padding: 4px 0; font-size: 14px; font-weight: 700; color: #0D1B2A;
            background: #fff; outline: none;
        }
        .qty-input:focus { border-color: #002352; }

        /* ─── CART ROW ──────────────────────────────── */
        .cart-row {
            background: #fff;
            border: 1px solid #EBEBEB;
            border-radius: 18px;
            transition: box-shadow .2s;
        }
        .cart-row:hover { box-shadow: 0 4px 20px rgba(0,35,82,.07); }

        /* ─── SUMMARY CARD ──────────────────────────── */
        .summary-card {
            background: #fff;
            border: 1px solid #EBEBEB;
            border-radius: 20px;
        }

        /* ─── REMOVE BTN ────────────────────────────── */
        .remove-btn {
            display: flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 8px;
            color: #9CA3AF; transition: all .15s; cursor: pointer;
            background: transparent; border: none;
        }
        .remove-btn:hover { color: #DC2626; background: #FEE2E2; }

        /* ─── CTA ───────────────────────────────────── */
        .btn-primary {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 15px 24px; border-radius: 14px;
            font-size: 15px; font-weight: 700; color: #fff;
            background: linear-gradient(135deg, #002352 0%, #003D8F 100%);
            border: none; cursor: pointer;
            transition: all .25s cubic-bezier(.4,0,.2,1);
            box-shadow: 0 4px 20px rgba(0,35,82,.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,35,82,.4); }
        .btn-secondary {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 11px 20px; border-radius: 12px;
            font-size: 13px; font-weight: 600; color: #374151;
            background: #F9FAFB; border: 1px solid #E5E7EB; cursor: pointer;
            transition: all .15s;
        }
        .btn-secondary:hover { background: #F3F4F6; border-color: #D1D5DB; }

        /* ─── ANIMATIONS ────────────────────────────── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }
        .fade-up { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-1 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .05s both; }
        .fade-up-2 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .15s both; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════════════ --}}
@include('client.partials.navbar')

{{-- ═══════════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════════ --}}
<div style="background:#001832; border-bottom:1px solid rgba(237,232,223,.18); position:relative; overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 2px 2px, rgba(255,255,255,.04) 1px, transparent 0);background-size:28px 28px;pointer-events:none;"></div>
    <div style="position:absolute; width:360px; height:360px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 65%); top:-180px; right:-30px; pointer-events:none;"></div>
    <div class="relative max-w-[1320px] mx-auto px-5 lg:px-8 py-10">
        <nav class="flex items-center gap-1.5 mb-4 text-[12px]" style="color:rgba(255,255,255,.55)">
            <a href="/" class="hover:text-white transition-colors font-medium" style="color:rgba(255,255,255,.55)">Accueil</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold" style="color:rgba(237,232,223,.95)">Mon Panier</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/15" style="background:rgba(237,232,223,.14)">
                <svg class="w-6 h-6 text-[#EDE8DF]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
            </div>
            <div>
                <h1 class="text-[28px] lg:text-[34px] font-extrabold text-[#EDE8DF] tracking-tight leading-tight">Mon Panier</h1>
                <p class="text-[13.5px] font-medium mt-0.5" style="color:rgba(255,255,255,.62)">
                    {{ $cartCount }} article{{ $cartCount > 1 ? 's' : '' }}
                    @if($cartCount > 0) <span class="mx-1" style="color:rgba(255,255,255,.32)">&bull;</span> <span class="font-semibold text-[#EDE8DF]">{{ number_format($subtotal, 0, ',', ' ') }} DA</span> @endif
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     FLASH MESSAGES
════════════════════════════════════════════════════════════ --}}
@if(session('success'))
<div class="max-w-[1320px] mx-auto px-5 lg:px-8 mt-5">
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-[13px] font-600">
        <svg class="w-4 h-4 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
</div>
@endif
@if(session('error'))
<div class="max-w-[1320px] mx-auto px-5 lg:px-8 mt-5">
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-[13px] font-600">
        <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        {{ session('error') }}
    </div>
</div>
@endif
@if(session('warning'))
<div class="max-w-[1320px] mx-auto px-5 lg:px-8 mt-5">
    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-[13px] font-600">
        <svg class="w-4 h-4 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        {{ session('warning') }}
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════════════ --}}
<main class="max-w-[1320px] mx-auto px-5 lg:px-8 py-8">

    @if(count($cartItems) > 0)
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ─── CART ITEMS ─────────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-4 fade-up-1">

            {{-- Clear cart --}}
            <div class="flex items-center justify-between">
                <p class="text-[13px] font-600 text-[#6B7280]">{{ count($cartItems) }} article{{ count($cartItems) > 1 ? 's' : '' }} dans votre panier</p>
                <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Vider le panier ?')">
                    @csrf
                    <button type="submit" class="text-[12px] font-600 text-[#9CA3AF] hover:text-[#DC2626] transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Vider le panier
                    </button>
                </form>
            </div>

            @foreach($cartItems as $item)
            @php
                $variant = $item['variant'];
                $product = $variant->product;
                $img     = $product->images->first();
                $attrs   = $variant->attributeValues ?? collect();
                $isPromo = $product->discount_price && $product->discount_price < $product->base_price;
            @endphp
            <div class="cart-row p-4 sm:p-5">
                <div class="flex gap-4">
                    {{-- Product image --}}
                    <a href="{{ route('product.show', $product->slug) }}" class="flex-shrink-0 w-20 h-20 sm:w-24 sm:h-24 rounded-14px overflow-hidden bg-[#F3F4F6]" style="border-radius:14px">
                        @if($img)
                            <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#D1D5DB]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                            </div>
                        @endif
                    </a>

                    {{-- Info + controls --}}
                    <div class="flex-1 min-w-0 flex flex-col gap-2">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                @if($product->category)
                                <p class="text-[10px] font-800 uppercase tracking-wider text-[#9CA3AF] mb-0.5">{{ $product->category->name }}</p>
                                @endif
                                <a href="{{ route('product.show', $product->slug) }}" class="text-[14px] font-700 text-[#0D1B2A] hover:text-[#002352] transition-colors leading-snug block truncate">
                                    {{ $product->name }}
                                </a>
                                {{-- Variant attributes --}}
                                @if($attrs->count())
                                <div class="flex flex-wrap gap-1.5 mt-1.5">
                                    @foreach($attrs as $attrVal)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-600 text-[#374151] bg-[#F3F4F6] px-2 py-0.5 rounded-md">
                                        <span class="text-[#9CA3AF]">{{ $attrVal->attribute->name ?? '' }}:</span>
                                        {{ $attrVal->value }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            {{-- Remove --}}
                            <form method="POST" action="{{ route('cart.remove', $variant->id) }}" class="flex-shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn" title="Retirer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>

                        {{-- Price + Qty row --}}
                        <div class="flex items-center justify-between flex-wrap gap-3 mt-auto">
                            {{-- Price --}}
                            <div class="flex items-baseline gap-2">
                                <span class="text-[16px] font-800 text-[#002352]">
                                    {{ number_format($item['line_total'], 0, ',', ' ') }} DA
                                </span>
                                @if($item['qty'] > 1)
                                <span class="text-[12px] text-[#9CA3AF] font-500">
                                    ({{ number_format($variant->price, 0, ',', ' ') }} DA × {{ $item['qty'] }})
                                </span>
                                @endif
                            </div>

                            {{-- Qty stepper --}}
                            <form method="POST" action="{{ route('cart.update', $variant->id) }}" class="flex items-center gap-1.5" id="qtyForm_{{ $variant->id }}">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="qty-btn"
                                    onclick="stepQty('{{ $variant->id }}', -1, {{ $variant->stock }})"
                                    {{ $item['qty'] <= 1 ? 'disabled style=opacity:.35;cursor:not-allowed' : '' }}>−</button>
                                <input type="number" name="qty" class="qty-input"
                                    id="qty_{{ $variant->id }}"
                                    value="{{ $item['qty'] }}"
                                    min="1" max="{{ $variant->stock }}"
                                    onchange="document.getElementById('qtyForm_{{ $variant->id }}').submit()">
                                <button type="button" class="qty-btn"
                                    onclick="stepQty('{{ $variant->id }}', 1, {{ $variant->stock }})"
                                    {{ $item['qty'] >= $variant->stock ? 'disabled style=opacity:.35;cursor:not-allowed' : '' }}>+</button>
                            </form>
                        </div>

                        {{-- Stock warning --}}
                        @if($variant->stock <= 5)
                        <p class="text-[11px] font-600 text-orange-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            Plus que {{ $variant->stock }} en stock
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Continue shopping --}}
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 text-[13px] font-600 text-[#6B7280] hover:text-[#002352] transition-colors mt-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Continuer les achats
            </a>
        </div>

        {{-- ─── ORDER SUMMARY ──────────────────────────── --}}
        <div class="w-full lg:w-[340px] flex-shrink-0 sticky top-[80px] fade-up-2">
            <div class="summary-card p-6 space-y-5">
                <h2 class="text-[16px] font-800 text-[#0D1B2A]">Récapitulatif</h2>

                {{-- Lines --}}
                <div class="space-y-3 text-[13px]">
                    <div class="flex justify-between">
                        <span class="text-[#6B7280] font-500">Sous-total ({{ $cartCount }} art.)</span>
                        <span class="font-700 text-[#0D1B2A]">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#6B7280] font-500">Livraison</span>
                        <span class="font-600 text-emerald-600">
                            @if($deliveryFee > 0)
                                {{ number_format($deliveryFee, 0, ',', ' ') }} DA
                            @else
                                Calculée à la commande
                            @endif
                        </span>
                    </div>
                </div>

                <div class="h-px bg-[#F3F4F6]"></div>

                <div class="flex justify-between items-baseline">
                    <span class="text-[15px] font-700 text-[#0D1B2A]">Total</span>
                    <span class="text-[22px] font-800 text-[#002352]">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
                </div>

                {{-- Checkout CTA --}}
                <a href="{{ route('checkout.index') }}" class="btn-primary">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Passer la commande
                </a>

                {{-- Trust --}}
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center gap-2.5 text-[12px] text-[#6B7280]">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Paiement à la livraison (COD)
                    </div>
                    <div class="flex items-center gap-2.5 text-[12px] text-[#6B7280]">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125"/></svg>
                        Livraison dans les 58 wilayas
                    </div>
                    <div class="flex items-center gap-2.5 text-[12px] text-[#6B7280]">
                        <svg class="w-4 h-4 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        Produits de qualité garantie
                    </div>
                </div>
            </div>
        </div>

    </div>

    @else
    {{-- ─── EMPTY CART ──────────────────────────────────── --}}
    <div class="flex flex-col items-center justify-center py-28 text-center fade-up">
        <div class="w-24 h-24 rounded-3xl flex items-center justify-center mb-6" style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF)">
            <svg class="w-11 h-11 text-[#6366F1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
        </div>
        <h2 class="text-[22px] font-800 text-[#0D1B2A] mb-2">Votre panier est vide</h2>
        <p class="text-[14px] text-[#6B7280] max-w-[320px] mb-8 leading-relaxed">
            Explorez notre catalogue et ajoutez des produits qui vous plaisent.
        </p>
        <a href="{{ route('catalogue') }}"
           class="inline-flex items-center gap-2 px-8 py-3.5 text-white text-[14px] font-700 rounded-xl hover:scale-[1.02] transition-all"
           style="background:linear-gradient(135deg,#002352,#003D8F);box-shadow:0 4px 20px rgba(0,35,82,.3)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21H3.75a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 013.75 4.5h9m4.5-1.5H21m0 0v5.25M21 3l-6.75 6.75M21 21H9.75A2.25 2.25 0 017.5 18.75V9"/></svg>
            Découvrir le catalogue
        </a>
    </div>
    @endif

</main>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ --}}
@include('client.partials.catalogue-footer')

<script>
function stepQty(variantId, delta, maxStock) {
    const input = document.getElementById('qty_' + variantId);
    if (!input) return;
    let val = parseInt(input.value) + delta;
    val = Math.max(1, Math.min(val, maxStock));
    input.value = val;
    document.getElementById('qtyForm_' + variantId).submit();
}
</script>
</body>
</html>
