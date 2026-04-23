@php
    $shopName  ??= config('app.name', 'Boutique');
    $logoPath  ??= null;
    $cartCount ??= 0;
    $payBaridimob ??= false;
    $payCib       ??= false;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout — {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F8F6; }

        /* ─── FORM FIELDS ────────────────────────────── */
        .field-label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: #374151; margin-bottom: 6px; letter-spacing: .01em;
        }
        .field-input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #E5E7EB; border-radius: 12px;
            font-size: 14px; color: #0D1B2A; background: #fff;
            outline: none; transition: border-color .15s, box-shadow .15s;
            font-family: inherit;
        }
        .field-input:focus { border-color: #002352; box-shadow: 0 0 0 3px rgba(0,35,82,.08); }
        .field-input.error { border-color: #EF4444; box-shadow: 0 0 0 3px rgba(239,68,68,.08); }
        .field-error { font-size: 11.5px; color: #EF4444; font-weight: 600; margin-top: 4px; }

        /* ─── CARDS ──────────────────────────────────── */
        .checkout-card {
            background: #fff; border: 1px solid #EBEBEB;
            border-radius: 20px; padding: 24px;
        }
        .card-title {
            font-size: 15px; font-weight: 800; color: #0D1B2A;
            margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
        }
        .card-title .icon {
            width: 34px; height: 34px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg,#EEF2FF,#E0E7FF); flex-shrink: 0;
        }

        /* ─── PAYMENT RADIO ──────────────────────────── */
        .pay-option {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 14px; border: 1.5px solid #E5E7EB; border-radius: 12px;
            cursor: pointer; transition: all .15s; user-select: none;
        }
        .pay-option:has(input:checked) { border-color: #002352; background: #EEF2FF; }
        .pay-option input[type=radio] { accent-color: #002352; width: 16px; height: 16px; }
        .pay-label { font-size: 13.5px; font-weight: 600; color: #0D1B2A; }
        .pay-sub { font-size: 11.5px; color: #9CA3AF; font-weight: 500; }

        /* ─── PROMO ──────────────────────────────────── */
        .promo-wrap {
            display: flex; gap: 8px;
        }
        .promo-input {
            flex: 1; padding: 10px 14px;
            border: 1.5px solid #E5E7EB; border-radius: 10px;
            font-size: 13.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
            outline: none; color: #0D1B2A; transition: border-color .15s;
            font-family: inherit;
        }
        .promo-input:focus { border-color: #002352; }
        .promo-btn {
            padding: 10px 18px; border-radius: 10px;
            font-size: 13px; font-weight: 700; color: #002352;
            background: #EEF2FF; border: 1.5px solid #C7D2FE;
            cursor: pointer; transition: all .15s; white-space: nowrap;
        }
        .promo-btn:hover { background: #E0E7FF; }

        /* ─── CTA ────────────────────────────────────── */
        .btn-confirm {
            width: 100%; padding: 16px 24px; border-radius: 14px;
            font-size: 16px; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border: none; cursor: pointer;
            box-shadow: 0 4px 24px rgba(5,150,105,.35);
            transition: all .25s cubic-bezier(.4,0,.2,1);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(5,150,105,.45); }
        .btn-confirm:disabled { opacity:.55; cursor: not-allowed; transform: none; }

        /* ─── SUMMARY LINE ───────────────────────────── */
        .sum-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
        .sum-total { font-size: 20px; font-weight: 800; color: #002352; }

        /* ─── ANIMATIONS ─────────────────────────────── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }
        .fade-up { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-d1 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .05s both; }
        .fade-up-d2 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .12s both; }
        .fade-up-d3 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .2s both; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════ --}}
@include('client.partials.navbar')

{{-- ═══════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════ --}}
<div class="bg-white border-b border-[#EBEBEB]">
    <div class="max-w-[1180px] mx-auto px-5 lg:px-8 py-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 bg-[#EEF2FF] shadow-sm">
                <svg class="w-6 h-6 text-[#002352]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"/></svg>
            </div>
            <div>
                <h1 class="text-[26px] font-extrabold text-[#0D1B2A] tracking-tight leading-tight">Finaliser la commande</h1>
                <p class="text-[#6B7280] text-[13.5px] font-medium mt-0.5">Remplissez vos informations pour recevoir votre colis</p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MAIN
════════════════════════════════════════════════════ --}}
<main class="max-w-[1180px] mx-auto px-5 lg:px-8 py-8">

    {{-- General error --}}
    @if($errors->has('general'))
    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-xl text-[13px] font-600">
        <svg class="w-4 h-4 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        {{ $errors->first('general') }}
    </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
        @csrf

        <div class="flex flex-col lg:flex-row gap-7 items-start">

            {{-- ─── LEFT COLUMN ─────────────────────── --}}
            <div class="flex-1 min-w-0 space-y-6 fade-up-d1">

                {{-- ── 1. ORDER SUMMARY ────────────────── --}}
                <div class="checkout-card">
                    <div class="card-title">
                        <div class="icon">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                        </div>
                        Récapitulatif de la commande
                        <a href="{{ route('cart.index') }}" class="ml-auto text-[11.5px] font-600 text-[#6B7280] hover:text-[#002352] transition-colors">Modifier</a>
                    </div>

                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        @php
                            $v   = $item['variant'];
                            $img = $v->product->images->first();
                            $attrs = $v->attributeValues->pluck('value')->implode(' / ');
                        @endphp
                        <div class="flex items-center gap-3 py-2 border-b border-[#F3F4F6] last:border-0 last:pb-0">
                            <div class="w-12 h-12 rounded-10px overflow-hidden bg-[#F3F4F6] flex-shrink-0" style="border-radius:10px">
                                @if($img)
                                    <img src="{{ Storage::url($img->path) }}" alt="{{ $v->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#D1D5DB]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-700 text-[#0D1B2A] truncate">{{ $v->product->name }}</p>
                                @if($attrs)
                                <p class="text-[11px] text-[#9CA3AF] font-500">{{ $attrs }}</p>
                                @endif
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[13px] font-800 text-[#002352]">{{ number_format($item['line_total'], 0, ',', ' ') }} DA</p>
                                <p class="text-[11px] text-[#9CA3AF]">× {{ $item['qty'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── 2. DELIVERY INFO ─────────────────── --}}
                <div class="checkout-card fade-up-d2">
                    <div class="card-title">
                        <div class="icon">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        Informations de livraison
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nom --}}
                        <div class="sm:col-span-2">
                            <label class="field-label" for="customer_name">Nom complet <span class="text-red-500">*</span></label>
                            <input type="text" id="customer_name" name="customer_name"
                                value="{{ old('customer_name') }}"
                                placeholder="Ex : Mohamed Benali"
                                class="field-input {{ $errors->has('customer_name') ? 'error' : '' }}">
                            @error('customer_name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label class="field-label" for="customer_phone">Téléphone <span class="text-red-500">*</span></label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                value="{{ old('customer_phone') }}"
                                placeholder="0X XX XX XX XX"
                                class="field-input {{ $errors->has('customer_phone') ? 'error' : '' }}">
                            @error('customer_phone')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="field-label" for="customer_email">Email <span class="text-[#9CA3AF] font-500">(optionnel)</span></label>
                            <input type="email" id="customer_email" name="customer_email"
                                value="{{ old('customer_email') }}"
                                placeholder="vous@exemple.com"
                                class="field-input {{ $errors->has('customer_email') ? 'error' : '' }}">
                            @error('customer_email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Wilaya --}}
                        <div>
                            <label class="field-label" for="wilaya_id">Wilaya <span class="text-red-500">*</span></label>
                            <select id="wilaya_id" name="wilaya_id"
                                class="field-input {{ $errors->has('wilaya_id') ? 'error' : '' }}"
                                onchange="updateShipping(this.value)">
                                <option value="">Sélectionner votre wilaya…</option>
                                @foreach($wilayas as $wilaya)
                                <option value="{{ $wilaya->id }}" {{ old('wilaya_id') == $wilaya->id ? 'selected' : '' }}>
                                    {{ $wilaya->code }} — {{ $wilaya->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('wilaya_id')<p class="field-error">{{ $message }}</p>@enderror
                            <div id="shippingInfo" class="mt-2 hidden">
                                <p class="text-[12px] font-700 text-emerald-600 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125"/></svg>
                                    <span id="shippingLabel"></span>
                                </p>
                            </div>
                        </div>

                        {{-- Adresse --}}
                        <div>
                            <label class="field-label" for="address">Adresse complète <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address"
                                value="{{ old('address') }}"
                                placeholder="Rue, quartier, commune…"
                                class="field-input {{ $errors->has('address') ? 'error' : '' }}">
                            @error('address')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- ── 3. PAYMENT ───────────────────────── --}}
                <div class="checkout-card fade-up-d3">
                    <div class="card-title">
                        <div class="icon">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        </div>
                        Méthode de paiement
                    </div>
                    @error('payment_method')<p class="field-error -mt-3 mb-3">{{ $message }}</p>@enderror

                    <div class="space-y-2.5">
                        <label class="pay-option">
                            <input type="radio" name="payment_method" value="cod" {{ old('payment_method','cod') === 'cod' ? 'checked' : '' }}>
                            <div>
                                <p class="pay-label flex items-center gap-2">
                                    <span>💵</span> Paiement à la livraison (COD)
                                </p>
                                <p class="pay-sub">Payez en espèces à la réception du colis</p>
                            </div>
                        </label>

                        @if($payBaridimob)
                        <label class="pay-option">
                            <input type="radio" name="payment_method" value="baridimob" {{ old('payment_method') === 'baridimob' ? 'checked' : '' }}>
                            <div>
                                <p class="pay-label flex items-center gap-2"><span>📱</span> BaridiMob</p>
                                <p class="pay-sub">Paiement mobile via BaridiMob</p>
                            </div>
                        </label>
                        @endif

                        @if($payCib)
                        <label class="pay-option">
                            <input type="radio" name="payment_method" value="cib" {{ old('payment_method') === 'cib' ? 'checked' : '' }}>
                            <div>
                                <p class="pay-label flex items-center gap-2"><span>💳</span> CIB / Edahabia</p>
                                <p class="pay-sub">Paiement par carte bancaire algérienne</p>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ─── RIGHT COLUMN ─────────────────────── --}}
            <div class="w-full lg:w-[340px] flex-shrink-0 sticky top-[80px] space-y-5 fade-up-d2">

                {{-- PROMO CODE --}}
                <div class="checkout-card">
                    <div class="card-title mb-3">
                        <div class="icon">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                        </div>
                        Code promo
                    </div>
                    <div class="promo-wrap">
                        <input type="text" id="promoInput" placeholder="SOLDES2026"
                            class="promo-input" autocomplete="off" maxlength="50">
                        <button type="button" class="promo-btn" onclick="applyPromo()">Appliquer</button>
                    </div>
                    <div id="promoFeedback" class="mt-2 hidden text-[12px] font-700"></div>
                    <input type="hidden" name="promo_code_id" id="promoCodeId" value="{{ old('promo_code_id') }}">
                </div>

                {{-- ORDER TOTAL --}}
                <div class="checkout-card space-y-4">
                    <h2 class="text-[15px] font-800 text-[#0D1B2A]">Votre commande</h2>

                    <div class="space-y-2.5">
                        <div class="sum-row">
                            <span class="text-[#6B7280] font-500">Sous-total</span>
                            <span class="font-700 text-[#0D1B2A]" id="displaySubtotal">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
                        </div>
                        <div class="sum-row">
                            <span class="text-[#6B7280] font-500">Livraison</span>
                            <span class="font-700 text-[#0D1B2A]" id="displayShipping">— DA</span>
                        </div>
                        <div class="sum-row hidden" id="discountRow">
                            <span class="text-emerald-600 font-600">Code promo</span>
                            <span class="font-700 text-emerald-600" id="displayDiscount">— DA</span>
                        </div>
                    </div>

                    <div class="h-px bg-[#F3F4F6]"></div>

                    <div class="sum-row">
                        <span class="text-[15px] font-700 text-[#0D1B2A]">Total</span>
                        <span class="sum-total" id="displayTotal">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
                    </div>

                    {{-- CONFIRM BUTTON --}}
                    <button type="submit" class="btn-confirm" id="confirmBtn">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Confirmer la commande
                    </button>

                    {{-- Trust badges --}}
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center gap-2 text-[11.5px] text-[#6B7280]">
                            <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Paiement sécurisé à la livraison
                        </div>
                        <div class="flex items-center gap-2 text-[11.5px] text-[#6B7280]">
                            <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125"/></svg>
                            Livraison dans les 58 wilayas
                        </div>
                        <div class="flex items-center gap-2 text-[11.5px] text-[#6B7280]">
                            <svg class="w-3.5 h-3.5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            Contacté par téléphone pour confirmation
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

</main>

{{-- ═══════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ --}}
<footer style="background:#0D0D0D;color:#9CA3AF;" class="mt-16">
    <div class="max-w-[1180px] mx-auto px-5 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background:#002352">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                </div>
                <span class="text-white font-700 text-[13px]">{{ $shopName }}</span>
            </div>
            <p class="text-[12px]">© {{ date('Y') }} — Tous droits réservés.</p>
        </div>
    </div>
</footer>

<script>
// ─── State ───────────────────────────────────────────────
const SUBTOTAL      = {{ $subtotal }};
let   shippingCost  = 0;
let   discountAmt   = 0;
let   shippingSet   = false;

// ─── Format number ───────────────────────────────────────
function fmt(n) {
    return new Intl.NumberFormat('fr-DZ').format(Math.round(n)) + ' DA';
}

// ─── Recalculate totals ──────────────────────────────────
function recalc() {
    const total = Math.max(0, SUBTOTAL - discountAmt + shippingCost);
    document.getElementById('displayShipping').textContent = shippingSet ? fmt(shippingCost) : '— DA';
    document.getElementById('displayTotal').textContent    = fmt(shippingSet ? total : SUBTOTAL - discountAmt);
}

// ─── Wilaya shipping ─────────────────────────────────────
function updateShipping(wilayaId) {
    if (! wilayaId) {
        shippingSet  = false;
        shippingCost = 0;
        document.getElementById('shippingInfo').classList.add('hidden');
        recalc();
        return;
    }
    fetch('{{ url("/checkout/wilaya") }}/' + wilayaId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        shippingCost = data.shipping_cost;
        shippingSet  = true;
        document.getElementById('shippingLabel').textContent =
            'Livraison vers ' + data.name + ' : ' + fmt(data.shipping_cost);
        document.getElementById('shippingInfo').classList.remove('hidden');
        recalc();
    })
    .catch(() => {});
}

// ─── Promo code ──────────────────────────────────────────
function applyPromo() {
    const code = document.getElementById('promoInput').value.trim().toUpperCase();
    const fb   = document.getElementById('promoFeedback');
    if (! code) return;

    fetch('{{ route("checkout.promo") }}', {
        method:  'POST',
        headers: {
            'Content-Type':  'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ code, subtotal: SUBTOTAL }),
    })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
        fb.classList.remove('hidden');
        if (ok) {
            discountAmt = data.discount;
            document.getElementById('promoCodeId').value = data.id;

            fb.className = 'mt-2 text-[12px] font-700 text-emerald-600';
            fb.textContent = '✓ Code "' + data.code + '" appliqué : ' + data.label;

            const dr = document.getElementById('discountRow');
            dr.classList.remove('hidden');
            document.getElementById('displayDiscount').textContent = '-' + fmt(data.discount);
        } else {
            discountAmt = 0;
            document.getElementById('promoCodeId').value = '';
            fb.className = 'mt-2 text-[12px] font-700 text-red-600';
            fb.textContent = '✕ ' + (data.error || 'Code promo invalide.');
            document.getElementById('discountRow').classList.add('hidden');
        }
        recalc();
    })
    .catch(() => {
        fb.classList.remove('hidden');
        fb.className = 'mt-2 text-[12px] font-700 text-red-600';
        fb.textContent = 'Erreur réseau. Réessayez.';
    });
}

// Allow Enter key in promo input
document.getElementById('promoInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); applyPromo(); }
});

// Prevent double-submit
document.getElementById('checkoutForm').addEventListener('submit', function () {
    document.getElementById('confirmBtn').disabled = true;
    document.getElementById('confirmBtn').textContent = 'Traitement en cours…';
});

// Restore wilaya on back-navigation (old value)
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('wilaya_id');
    if (sel && sel.value) updateShipping(sel.value);
});
</script>
</body>
</html>
