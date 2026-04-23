@php
    $firstImage  = $product->images->first();
    $minVariant  = $product->variants->sortBy('price')->first();
    $maxVariant  = $product->variants->sortByDesc('price')->first();
    $totalStock  = $product->variants->sum('stock');
    $isPromo     = $product->discount_price && $product->discount_price < $product->base_price;
    $isNew       = $product->created_at >= now()->subDays(30);
    $discount    = $isPromo ? round((1 - $product->discount_price / $product->base_price) * 100) : 0;
    $logoPath   ??= null;
    $whatsapp   ??= '';

    // Color name → CSS color map (shared with admin predefined list)
    $colorMap = [
        'blanc'     => '#FFFFFF', 'noir'      => '#111111',
        'rouge'     => '#DC2626', 'bleu'      => '#2563EB',
        'vert'      => '#16A34A', 'jaune'     => '#EAB308',
        'orange'    => '#EA580C', 'rose'      => '#EC4899',
        'violet'    => '#9333EA', 'gris'      => '#6B7280',
        'marron'    => '#92400E', 'beige'     => '#D4B483',
        'turquoise' => '#0D9488', 'corail'    => '#F87171',
        'bordeaux'  => '#881337', 'marine'    => '#1E3A5F',
        'kaki'      => '#78716C', 'or'        => '#CA8A04',
        'argent'    => '#9CA3AF', 'crème'     => '#FEF9EE',
        'creme'     => '#FEF9EE', 'lavande'   => '#C4B5FD',
        'mint'      => '#6EE7B7', 'chocolat'  => '#6B3F1F',
        'pêche'     => '#FDBA74', 'peche'     => '#FDBA74',
        'écru'      => '#F5F0E1', 'ecru'      => '#F5F0E1',
        'caramel'   => '#D97706', 'lilas'     => '#A78BFA',
        'saumon'    => '#FCA5A5', 'indigo'    => '#4338CA',
        'cyan'      => '#0891B2',
    ];
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} — {{ $shopName }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($product->description ?? ''), 155) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F8F6; }

        /* ─── GALLERY ───────────────────────────────── */
        #mainImg {
            transition: opacity .25s ease, transform .25s ease;
            will-change: transform, opacity;
        }
        .thumb-item {
            border: 2px solid transparent;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: border-color .2s, transform .2s;
            aspect-ratio: 1/1;
            background: #F3F4F6;
        }
        .thumb-item:hover { transform: scale(1.04); }
        .thumb-item.active { border-color: #002352; }
        .thumb-item img { width:100%; height:100%; object-fit:cover; display:block; }

        /* Zoom on hover */
        .gallery-zoom-wrapper { position: relative; overflow: hidden; border-radius: 24px; background: #F3F4F6; }
        .gallery-zoom-wrapper img { transition: transform .4s cubic-bezier(.4,0,.2,1); display:block; width:100%; }
        .gallery-zoom-wrapper:hover img { transform: scale(1.07); cursor: zoom-in; }

        /* ─── ATTR BUTTONS ──────────────────────────── */
        .attr-btn {
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: all .2s;
            user-select: none;
            background: #fff;
            position: relative;
        }
        .attr-btn:hover:not(.disabled) { border-color: #002352; color: #002352; }
        .attr-btn.selected {
            border-color: #002352;
            background: #002352;
            color: #fff;
            box-shadow: 0 3px 12px rgba(0,35,82,.3);
        }
        .attr-btn.disabled {
            opacity: .4;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        /* ─── COLOR BUTTON ──────────────────────────── */
        .color-btn {
            width: 32px; height: 32px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all .2s;
            position: relative;
            flex-shrink: 0;
        }
        .color-btn:hover:not(.disabled) { transform: scale(1.15); }
        .color-btn.selected {
            border-color: #002352;
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #002352;
        }
        .color-btn.disabled { opacity: .35; cursor: not-allowed; }

        /* ─── QTY STEPPER ───────────────────────────── */
        .qty-btn {
            width: 36px; height: 36px;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            background: #F9FAFB;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 18px;
            font-weight: 500;
            color: #374151;
            transition: all .15s;
            user-select: none;
        }
        .qty-btn:hover:not(:disabled) { background: #002352; color: #fff; border-color: #002352; }
        .qty-btn:disabled { opacity: .3; cursor: not-allowed; }
        #qtyInput {
            width: 52px;
            text-align: center;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 7px 0;
            font-size: 15px;
            font-weight: 700;
            color: #0D1B2A;
            background: #fff;
            outline: none;
        }
        #qtyInput:focus { border-color: #002352; }

        /* ─── CTA BUTTONS ───────────────────────────── */
        .btn-cart {
            flex: 1;
            padding: 14px 24px;
            border-radius: 14px;
            font-size: 14px; font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #002352 0%, #003D8F 100%);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            cursor: pointer;
            border: none;
            transition: all .25s cubic-bezier(.4,0,.2,1);
            box-shadow: 0 4px 20px rgba(0,35,82,.3);
            position: relative;
            overflow: hidden;
        }
        .btn-cart::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,.1));
            opacity: 0;
            transition: opacity .2s;
        }
        .btn-cart:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,35,82,.4); }
        .btn-cart:hover::after { opacity: 1; }
        .btn-cart:active { transform: translateY(0); }
        .btn-cart:disabled { background: #9CA3AF; cursor: not-allowed; transform: none; box-shadow: none; }

        .btn-whatsapp {
            padding: 14px 20px;
            border-radius: 14px;
            font-size: 14px; font-weight: 700;
            color: #fff;
            background: #25D366;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            cursor: pointer;
            border: none;
            transition: all .25s;
            box-shadow: 0 4px 16px rgba(37,211,102,.3);
            white-space: nowrap;
        }
        .btn-whatsapp:hover { background: #1BAE54; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,211,102,.4); }

        /* ─── TABS ──────────────────────────────────── */
        .tab-trigger {
            padding: 10px 20px;
            font-size: 14px; font-weight: 600;
            color: #6B7280;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all .2s;
            user-select: none;
        }
        .tab-trigger.active { color: #002352; border-bottom-color: #002352; }
        .tab-trigger:hover:not(.active) { color: #374151; }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* ─── BADGE ─────────────────────────────────── */
        .badge { font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; padding:4px 10px; border-radius:99px; }
        .badge-promo   { background:#FEE2E2; color:#DC2626; }
        .badge-new     { background:#D1FAE5; color:#059669; }
        .badge-rupture { background:#F3F4F6; color:#6B7280; }
        .badge-stock   { background:#D1FAE5; color:#059669; }

        /* ─── RELATED CARDS ─────────────────────────── */
        .rel-card {
            background: #fff;
            border: 1px solid #EBEBEB;
            border-radius: 18px;
            overflow: hidden;
            transition: transform .28s cubic-bezier(.4,0,.2,1), box-shadow .28s ease, border-color .2s;
        }
        .rel-card:hover { transform: translateY(-5px); box-shadow: 0 20px 44px rgba(0,35,82,.1); border-color: #C5D3E8; }
        .rel-card-img { transition: transform .38s cubic-bezier(.4,0,.2,1); }
        .rel-card:hover .rel-card-img { transform: scale(1.06); }

        /* ─── TOAST ─────────────────────────────────── */
        #toast {
            position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(80px);
            background: #002352; color: #fff;
            padding: 13px 24px; border-radius: 14px;
            font-size: 14px; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 8px 32px rgba(0,35,82,.35);
            z-index: 9999;
            transition: transform .4s cubic-bezier(.22,1,.36,1), opacity .4s;
            opacity: 0; pointer-events: none;
            white-space: nowrap;
        }
        #toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

        /* ─── BREADCRUMB ────────────────────────────── */
        .bc-sep { color: #D1D5DB; font-size: 12px; }

        /* ─── STICKY CTA (mobile) ───────────────────── */
        @media (max-width: 767px) {
            #stickyBar { display: flex; }
        }

        /* ─── ANIMATIONS ────────────────────────────── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:none} }
        .fade-up-1 { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .05s both; }
        .fade-up-2 { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .15s both; }
        .fade-up-3 { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .25s both; }
        .fade-up-4 { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .35s both; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════════════ --}}
@include('client.partials.navbar')

{{-- Flash messages --}}
@if(session('success'))
<div class="max-w-[1320px] mx-auto px-5 lg:px-8 mt-4">
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-[13px] font-600">
        <svg class="w-4 h-4 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
</div>
@endif
@if(session('error'))
<div class="max-w-[1320px] mx-auto px-5 lg:px-8 mt-4">
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-[13px] font-600">
        <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        {{ session('error') }}
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════
     BREADCRUMB
════════════════════════════════════════════════════════════ --}}
<div class="max-w-[1320px] mx-auto px-5 lg:px-8 pt-6 pb-2">
    <nav class="flex items-center gap-2 text-[12.5px] font-600 text-[#9CA3AF]">
        <a href="/" class="hover:text-[#002352] transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            Accueil
        </a>
        <span class="bc-sep">/</span>
        <a href="{{ route('catalogue') }}" class="hover:text-[#002352] transition-colors">Catalogue</a>
        @if($product->category)
        <span class="bc-sep">/</span>
        <a href="{{ route('catalogue', ['categorie' => $product->category->id]) }}" class="hover:text-[#002352] transition-colors">{{ $product->category->name }}</a>
        @endif
        <span class="bc-sep">/</span>
        <span class="text-[#374151] truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>
</div>

{{-- ═══════════════════════════════════════════════════════════
     PRODUCT SECTION
════════════════════════════════════════════════════════════ --}}
<main class="max-w-[1320px] mx-auto px-5 lg:px-8 py-6 lg:py-10">
    <div class="flex flex-col lg:flex-row gap-10 xl:gap-16">

        {{-- ─── IMAGE GALLERY ──────────────────────────────── --}}
        <div class="lg:w-[52%] xl:w-[50%] flex-shrink-0 fade-up-1">
            {{-- Main image --}}
            <div class="gallery-zoom-wrapper mb-4" style="aspect-ratio:4/5;">
                @if($product->images->count())
                <img id="mainImg"
                     src="{{ Storage::url($product->images->first()->path) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex flex-col items-center justify-center text-[#9CA3AF]">
                    <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    <p class="text-[13px] font-500">Aucune image</p>
                </div>
                @endif

                {{-- Badge overlay on main image --}}
                @if($totalStock <= 0)
                <div class="absolute top-4 left-4">
                    <span class="badge badge-rupture text-[12px] py-1.5 px-3">Rupture de stock</span>
                </div>
                @elseif($isPromo)
                <div class="absolute top-4 left-4 flex flex-col gap-2">
                    <span class="badge badge-promo text-[12px] py-1.5 px-3">-{{ $discount }}%</span>
                </div>
                @elseif($isNew)
                <div class="absolute top-4 left-4">
                    <span class="badge badge-new text-[12px] py-1.5 px-3">✨ Nouveau</span>
                </div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
            <div class="grid grid-cols-5 sm:grid-cols-6 gap-2.5" id="thumbGrid">
                @foreach($product->images as $i => $img)
                <div class="thumb-item {{ $i === 0 ? 'active' : '' }}"
                     onclick="switchImage('{{ Storage::url($img->path) }}', this)">
                    <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }} {{ $i+1 }}" loading="lazy">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ─── PRODUCT INFO ────────────────────────────────── --}}
        <div class="flex-1 min-w-0 fade-up-2">

            {{-- Category + Brand --}}
            <div class="flex items-center gap-3 mb-3 flex-wrap">
                @if($product->category)
                <a href="{{ route('catalogue', ['categorie' => $product->category->id]) }}"
                   class="text-[11px] font-800 uppercase tracking-wider text-[#002352] bg-[#EEF2FF] px-3 py-1 rounded-full hover:bg-[#E0E7FF] transition-colors">
                    {{ $product->category->name }}
                </a>
                @endif
                @if($product->brand)
                <span class="text-[12px] font-600 text-[#6B7280] bg-[#F3F4F6] px-3 py-1 rounded-full">{{ $product->brand }}</span>
                @endif
                @if($isPromo)<span class="badge badge-promo">-{{ $discount }}% PROMO</span>@endif
                @if($isNew && !$isPromo)<span class="badge badge-new">Nouveau</span>@endif
            </div>

            {{-- Title --}}
            <h1 class="text-[26px] lg:text-[30px] font-800 text-[#0D1B2A] leading-[1.2] tracking-tight mb-4">{{ $product->name }}</h1>

            {{-- Price --}}
            <div class="flex items-baseline gap-3 mb-2 flex-wrap" id="priceBlock">
                @if($isPromo)
                    <span class="text-[32px] font-800 text-[#DC2626]" id="displayPrice">{{ number_format($product->discount_price, 0, ',', ' ') }} DA</span>
                    <span class="text-[18px] text-[#9CA3AF] line-through" id="originalPrice">{{ number_format($product->base_price, 0, ',', ' ') }} DA</span>
                    <span class="text-[13px] font-700 text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Économisez {{ number_format($product->base_price - $product->discount_price, 0, ',', ' ') }} DA</span>
                @else
                    <span class="text-[32px] font-800 text-[#002352]" id="displayPrice">
                        @if($minVariant && $maxVariant && $minVariant->price != $maxVariant->price)
                            À partir de {{ number_format($minVariant->price, 0, ',', ' ') }} DA
                        @elseif($minVariant)
                            {{ number_format($minVariant->price, 0, ',', ' ') }} DA
                        @else
                            {{ number_format($product->base_price, 0, ',', ' ') }} DA
                        @endif
                    </span>
                @endif
            </div>

            {{-- Stock status --}}
            <div id="stockStatus" class="flex items-center gap-2 mb-6">
                @if($totalStock > 0)
                <span class="flex items-center gap-2 text-[13px] font-600 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    En stock
                    @if($totalStock <= 5)<span class="text-orange-500">(Plus que {{ $totalStock }} disponible{{ $totalStock > 1 ? 's' : '' }})</span>@endif
                </span>
                @else
                <span class="flex items-center gap-2 text-[13px] font-600 text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Rupture de stock
                </span>
                @endif
            </div>

            {{-- Divider --}}
            <div class="h-px bg-[#F3F4F6] mb-6"></div>

            {{-- ── Variant Selectors ── --}}
            @if(count($attributeGroups))
            <form id="cartForm" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                <input type="hidden" name="qty" id="selectedQty" value="1">

                <div class="space-y-5 mb-6" id="attrSelectors">
                    @foreach($attributeGroups as $attrName => $values)
                    @php
                        $isColor = stripos($attrName, 'couleur') !== false || stripos($attrName, 'color') !== false;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[13px] font-700 text-[#374151] uppercase tracking-wider">
                                {{ $attrName }}
                                <span id="label_{{ Str::slug($attrName) }}" class="text-[#002352] font-800 normal-case tracking-normal ml-1 text-[14px]"></span>
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2" id="group_{{ Str::slug($attrName) }}">
                            @foreach($values as $valId => $value)
                            @if($isColor)
                            @php $cssColor = $colorMap[mb_strtolower($value)] ?? $value; @endphp
                            <button type="button"
                                class="color-btn"
                                data-attr="{{ Str::slug($attrName) }}"
                                data-val-id="{{ $valId }}"
                                data-val="{{ $value }}"
                                title="{{ $value }}"
                                style="background: {{ $cssColor }};{{ $cssColor === '#FFFFFF' || $cssColor === '#FEF9EE' || $cssColor === '#F5F0E1' ? 'border-color:#E5E7EB;' : '' }}"
                                onclick="selectAttr(this)">
                            </button>
                            @else
                            <button type="button"
                                class="attr-btn"
                                data-attr="{{ Str::slug($attrName) }}"
                                data-val-id="{{ $valId }}"
                                data-val="{{ $value }}"
                                onclick="selectAttr(this)">
                                {{ $value }}
                            </button>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Combination not available --}}
                <div id="noComboMsg" class="hidden mb-4 flex items-center gap-2 text-[13px] font-600 text-orange-600 bg-orange-50 border border-orange-200 px-4 py-3 rounded-xl">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    Cette combinaison n'est pas disponible.
                </div>

                {{-- Select variant reminder --}}
                <div id="selectVariantMsg" class="mb-4 flex items-center gap-2 text-[13px] font-600 text-[#6B7280] bg-[#F9FAFB] border border-[#E5E7EB] px-4 py-3 rounded-xl">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"/></svg>
                    Veuillez sélectionner toutes les options pour ajouter au panier.
                </div>

                {{-- Quantity --}}
                <div class="flex items-center gap-3 mb-6">
                    <p class="text-[13px] font-700 text-[#374151] uppercase tracking-wider">Quantité</p>
                    <div class="flex items-center gap-2">
                        <button type="button" class="qty-btn" id="qtyMinus" onclick="changeQty(-1)">−</button>
                        <input type="text" id="qtyInput" value="1" readonly>
                        <button type="button" class="qty-btn" id="qtyPlus" onclick="changeQty(1)">+</button>
                    </div>
                    <span id="stockLabel" class="text-[12px] text-[#9CA3AF] font-500"></span>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex gap-3 flex-wrap sm:flex-nowrap">
                    <button type="submit" class="btn-cart" id="addToCartBtn" disabled>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                        Ajouter au panier
                    </button>
                    @if($whatsapp)
                    <button type="button" class="btn-whatsapp" onclick="orderWhatsApp()">
                        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </button>
                    @endif
                </div>

            </form>
            @else
            {{-- No variants — direct add --}}
            <form method="POST" action="{{ route('cart.add') }}" class="mb-6">
                @csrf
                <input type="hidden" name="variant_id" value="{{ $product->variants->first()?->id }}">
                <input type="hidden" name="qty" value="1">
                <div class="flex gap-3">
                    <button type="submit" class="btn-cart" {{ $totalStock <= 0 ? 'disabled' : '' }}>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                        {{ $totalStock > 0 ? 'Ajouter au panier' : 'Rupture de stock' }}
                    </button>
                </div>
            </form>
            @endif

            {{-- Divider --}}
            <div class="h-px bg-[#F3F4F6] my-6"></div>

            {{-- Trust badges --}}
            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="flex flex-col items-center gap-2 p-3 bg-[#F9FAFB] rounded-xl text-center">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125"/></svg>
                    </div>
                    <p class="text-[11px] font-700 text-[#374151] leading-tight">Livraison<br>partout</p>
                </div>
                <div class="flex flex-col items-center gap-2 p-3 bg-[#F9FAFB] rounded-xl text-center">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    </div>
                    <p class="text-[11px] font-700 text-[#374151] leading-tight">Paiement<br>à la livraison</p>
                </div>
                <div class="flex flex-col items-center gap-2 p-3 bg-[#F9FAFB] rounded-xl text-center">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <p class="text-[11px] font-700 text-[#374151] leading-tight">Qualité<br>garantie</p>
                </div>
            </div>

            {{-- ── Tabs ── --}}
            <div>
                <div class="flex border-b border-[#E5E7EB] gap-0 -mb-px">
                    <button class="tab-trigger active" onclick="switchTab(this,'tabDesc')">Description</button>
                    <button class="tab-trigger" onclick="switchTab(this,'tabShip')">Livraison & Retours</button>
                    @if($product->brand)<button class="tab-trigger" onclick="switchTab(this,'tabInfo')">Informations</button>@endif
                </div>
                <div class="pt-5">
                    <div id="tabDesc" class="tab-pane active text-[14px] text-[#374151] leading-relaxed">
                        @if($product->description)
                            {!! nl2br(e($product->description)) !!}
                        @else
                            <p class="text-[#9CA3AF] italic">Aucune description disponible pour ce produit.</p>
                        @endif
                    </div>
                    <div id="tabShip" class="tab-pane space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125"/></svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-700 text-[#0D1B2A]">Livraison dans les 58 wilayas</p>
                                <p class="text-[13px] text-[#6B7280]">Délai de livraison estimé : 2 à 5 jours ouvrables. Les frais de livraison varient selon la wilaya.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-700 text-[#0D1B2A]">Paiement à la livraison (COD)</p>
                                <p class="text-[13px] text-[#6B7280]">Aucune avance requise. Vous payez uniquement à la réception de votre commande.</p>
                            </div>
                        </div>
                    </div>
                    @if($product->brand)
                    <div id="tabInfo" class="tab-pane">
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 py-2 border-b border-[#F3F4F6]">
                                <span class="text-[13px] text-[#9CA3AF] font-600 w-24">Marque</span>
                                <span class="text-[13px] font-700 text-[#0D1B2A]">{{ $product->brand }}</span>
                            </div>
                            @if($product->category)
                            <div class="flex items-center gap-3 py-2 border-b border-[#F3F4F6]">
                                <span class="text-[13px] text-[#9CA3AF] font-600 w-24">Catégorie</span>
                                <span class="text-[13px] font-700 text-[#0D1B2A]">{{ $product->category->name }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-3 py-2">
                                <span class="text-[13px] text-[#9CA3AF] font-600 w-24">Référence</span>
                                <span class="text-[13px] font-600 text-[#6B7280]">PRD-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</main>

{{-- ═══════════════════════════════════════════════════════════
     RELATED PRODUCTS
════════════════════════════════════════════════════════════ --}}
@if($related->count())
<section class="max-w-[1320px] mx-auto px-5 lg:px-8 py-12 lg:py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-[11px] font-800 uppercase tracking-[.16em] text-[#002352] mb-1.5 flex items-center gap-2">
                <span class="w-4 h-0.5 bg-[#002352] rounded-full inline-block"></span>
                Vous aimerez aussi
            </p>
            <h2 class="text-[22px] lg:text-[26px] font-800 text-[#0D1B2A] tracking-tight">Produits similaires</h2>
        </div>
        @if($product->category)
        <a href="{{ route('catalogue', ['categorie' => $product->category->id]) }}"
           class="hidden sm:flex items-center gap-2 text-[13px] font-700 text-[#002352] hover:text-[#003D8F] transition-colors">
            Voir tout <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        @endif
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
        @foreach($related as $rel)
        @php
            $relImg     = $rel->images->first();
            $relMin     = $rel->variants->min('price') ?? $rel->base_price;
            $relTotal   = $rel->variants->sum('stock');
            $relPromo   = $rel->discount_price && $rel->discount_price < $rel->base_price;
            $relDisc    = $relPromo ? round((1 - $rel->discount_price / $rel->base_price) * 100) : 0;
        @endphp
        <a href="{{ route('product.show', $rel->slug) }}" class="rel-card group">
            <div class="aspect-[4/5] overflow-hidden bg-[#F3F4F6] relative">
                @if($relImg)
                    <img src="{{ Storage::url($relImg->path) }}" alt="{{ $rel->name }}" class="rel-card-img w-full h-full object-cover" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-[#D1D5DB]" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25z"/></svg>
                    </div>
                @endif
                @if($relPromo)<div class="absolute top-2 left-2"><span class="badge badge-promo">-{{ $relDisc }}%</span></div>@endif
            </div>
            <div class="p-4">
                <p class="text-[13px] font-700 text-[#0D1B2A] leading-snug line-clamp-2 mb-2">{{ $rel->name }}</p>
                <div class="flex items-center gap-2">
                    @if($relPromo)
                        <span class="text-[14px] font-800 text-[#DC2626]">{{ number_format($rel->discount_price, 0, ',', ' ') }} DA</span>
                        <span class="text-[12px] text-[#9CA3AF] line-through">{{ number_format($rel->base_price, 0, ',', ' ') }} DA</span>
                    @else
                        <span class="text-[14px] font-800 text-[#002352]">{{ number_format($relMin, 0, ',', ' ') }} DA</span>
                    @endif
                    @if($relTotal <= 0)<span class="badge badge-rupture ml-auto">Rupture</span>@endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ --}}
<footer style="background:#F8F7F4; border-top:1px solid #E8E4DC;" class="mt-8">
    <div class="max-w-[1320px] mx-auto px-5 lg:px-8">

        {{-- Main grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr_1fr] gap-10 py-14" style="border-bottom:1px solid #E8E4DC">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    @include('partials.shop-logo', [
                        'logoPath' => $logoPath,
                        'shopName' => $shopName,
                        'containerClass' => 'w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center',
                        'imageClass' => 'w-full h-full object-contain p-1',
                        'iconClass' => 'w-4 h-4 text-white',
                    ])
                    <span class="font-extrabold text-[17px] tracking-tight text-[#0D1B2A]">{{ $shopName }}</span>
                </div>
                <p class="text-[13.5px] leading-[1.7] mb-6 text-[#6B7280]" style="max-width:280px">Boutique algérienne en ligne. Livraison à domicile dans toutes les wilayas, paiement uniquement à la réception.</p>
                <div class="flex flex-col gap-2.5">
                    @if($shopPhone)
                    <a href="tel:{{ $shopPhone }}" class="inline-flex items-center gap-2.5 text-[13px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        {{ $shopPhone }}
                    </a>
                    @endif
                    @if($shopEmail)
                    <a href="mailto:{{ $shopEmail }}" class="inline-flex items-center gap-2.5 text-[13px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        {{ $shopEmail }}
                    </a>
                    @endif
                </div>
            </div>

            {{-- Navigation --}}
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.16em] mb-6 text-[#9CA3AF]">Navigation</p>
                <ul class="space-y-3.5">
                    <li><a href="/" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Accueil</a></li>
                    <li><a href="/catalogue" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Catalogue</a></li>
                    <li><a href="/catalogue?promo=1" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Promotions</a></li>
                    <li><a href="/suivi-commande" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Suivi commande</a></li>
                    <li><a href="/conditions-de-vente" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Conditions de vente</a></li>
                </ul>
            </div>

            {{-- Livraison --}}
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.16em] mb-6 text-[#9CA3AF]">Livraison</p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#EDE8DF">
                            <svg class="w-3.5 h-3.5 text-[#002352]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold leading-none mb-1 text-[#0D1B2A]">58 wilayas</p>
                            <p class="text-[12px] text-[#9CA3AF]">Toute l'Algérie</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#EDE8DF">
                            <svg class="w-3.5 h-3.5 text-[#002352]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold leading-none mb-1 text-[#0D1B2A]">Livraison 48h</p>
                            <p class="text-[12px] text-[#9CA3AF]">Délai moyen</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#EDE8DF">
                            <svg class="w-3.5 h-3.5 text-[#002352]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold leading-none mb-1 text-[#0D1B2A]">Paiement à la livraison</p>
                            <p class="text-[12px] text-[#9CA3AF]">Zéro avance requise</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="py-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <p class="text-[12px] text-[#9CA3AF]">&copy; {{ date('Y') }} {{ $shopName }}. Tous droits réservés.</p>
            <p class="text-[12px] text-[#9CA3AF]">Algérie &mdash; Paiement à la livraison</p>
        </div>

    </div>
</footer>

{{-- ═══════════════════════════════════════════════════════════
     MOBILE STICKY BAR
════════════════════════════════════════════════════════════ --}}
<div id="stickyBar" class="fixed bottom-0 left-0 right-0 z-40 md:hidden hidden bg-white border-t border-[#E5E7EB] p-3 gap-2">
    <button type="button" onclick="document.getElementById('cartForm')?.querySelector('button[type=submit]')?.click()"
        class="btn-cart flex-1 py-3.5 text-[14px]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
        Ajouter au panier
    </button>
</div>

{{-- ═══════════════════════════════════════════════════════════
     TOAST NOTIFICATION
════════════════════════════════════════════════════════════ --}}
<div id="toast">
    <svg class="w-4 h-4 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span id="toastMsg">Produit ajouté au panier !</span>
</div>

{{-- ═══════════════════════════════════════════════════════════
     JAVASCRIPT — VARIANT PICKER
════════════════════════════════════════════════════════════ --}}
<script>
// ── Data ──────────────────────────────────────────────────────
const variants = @json($variantsData);
const selectedAttrs = {}; // { attrName: valueId }

// Track all attributes
const attrGroups = @json(array_map(fn($v) => array_keys($v), $attributeGroups));
const attrNames  = @json(array_keys($attributeGroups));

// ── Select an attribute value ─────────────────────────────────
function selectAttr(btn) {
    const attr   = btn.dataset.attr;
    const valId  = parseInt(btn.dataset.valId);
    const valTxt = btn.dataset.val;

    // Toggle off if already selected
    if (selectedAttrs[attr] === valId) {
        delete selectedAttrs[attr];
        btn.classList.remove('selected');
    } else {
        // Deselect previous in same group
        document.querySelectorAll(`[data-attr="${attr}"]`).forEach(b => b.classList.remove('selected'));
        selectedAttrs[attr] = valId;
        btn.classList.add('selected');
    }

    // Update label
    const labelEl = document.getElementById(`label_${attr}`);
    if (labelEl) labelEl.textContent = selectedAttrs[attr] ? valTxt : '';

    updateVariantState();
}

// ── Find matching variant ─────────────────────────────────────
function findMatchingVariant() {
    const selectedIds = Object.values(selectedAttrs).sort();
    if (selectedIds.length === 0) return null;

    return variants.find(v => {
        const vIds = [...v.attrs].sort();
        return JSON.stringify(vIds) === JSON.stringify(selectedIds);
    }) || null;
}

// ── Check if all attrs selected ───────────────────────────────
function allAttrsSelected() {
    return attrNames.every(name => selectedAttrs[Str_slug(name)] !== undefined);
}

// ── Simple slug helper ────────────────────────────────────────
function Str_slug(str) {
    return str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}

// ── Update UI based on current selection ─────────────────────
function updateVariantState() {
    const matchedVariant = findMatchingVariant();
    const allSelected    = allAttrsSelected();
    const noComboMsg     = document.getElementById('noComboMsg');
    const selectMsg      = document.getElementById('selectVariantMsg');
    const cartBtn        = document.getElementById('addToCartBtn');
    const varInput       = document.getElementById('selectedVariantId');
    const stockLbl       = document.getElementById('stockLabel');
    const stockStatus    = document.getElementById('stockStatus');
    const displayPrice   = document.getElementById('displayPrice');
    const qtyPlus        = document.getElementById('qtyPlus');
    const qtyMinus       = document.getElementById('qtyMinus');
    const qtyInput       = document.getElementById('qtyInput');

    if (!allSelected) {
        // Still selecting
        noComboMsg.classList.add('hidden');
        selectMsg.classList.remove('hidden');
        cartBtn.disabled = true;
        varInput.value = '';
        if (stockLbl) stockLbl.textContent = '';
        return;
    }

    selectMsg.classList.add('hidden');

    if (!matchedVariant || matchedVariant.stock <= 0) {
        noComboMsg.classList.remove('hidden');
        cartBtn.disabled = true;
        varInput.value = '';
        if (stockLbl) stockLbl.textContent = '';

        // Update stock status
        if (stockStatus) {
            stockStatus.innerHTML = `<span class="flex items-center gap-2 text-[13px] font-600 text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Rupture de stock
            </span>`;
        }
        return;
    }

    // Matched variant available
    noComboMsg.classList.add('hidden');
    cartBtn.disabled = false;
    varInput.value = matchedVariant.id;

    // Update price
    if (displayPrice) {
        displayPrice.textContent = new Intl.NumberFormat('fr-DZ').format(matchedVariant.price) + ' DA';
    }

    // Update stock status
    const stock = matchedVariant.stock;
    if (stockStatus) {
        let html = `<span class="flex items-center gap-2 text-[13px] font-600 text-emerald-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            En stock`;
        if (stock <= 5) html += ` <span class="text-orange-500">(Plus que ${stock} disponible${stock > 1 ? 's' : ''})</span>`;
        html += `</span>`;
        stockStatus.innerHTML = html;
    }

    // Update stock label & qty
    if (stockLbl) stockLbl.textContent = `(max ${stock})`;
    const currentQty = parseInt(qtyInput?.value || 1);
    if (currentQty > stock) {
        qtyInput.value = stock;
        document.getElementById('selectedQty').value = stock;
    }
    updateQtyButtons(parseInt(qtyInput?.value || 1), stock);
}

// ── Quantity stepper ──────────────────────────────────────────
let maxQty = 99;

function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const hiddenQty = document.getElementById('selectedQty');
    const variant = findMatchingVariant();
    if (variant) maxQty = variant.stock;

    let val = parseInt(input.value) + delta;
    val = Math.max(1, Math.min(val, maxQty));
    input.value = val;
    if (hiddenQty) hiddenQty.value = val;
    updateQtyButtons(val, maxQty);
}

function updateQtyButtons(qty, max) {
    const minus = document.getElementById('qtyMinus');
    const plus  = document.getElementById('qtyPlus');
    if (minus) minus.disabled = qty <= 1;
    if (plus)  plus.disabled  = qty >= max;
}

// ── Image gallery ─────────────────────────────────────────────
function switchImage(src, thumbEl) {
    const mainImg = document.getElementById('mainImg');
    if (!mainImg) return;

    mainImg.style.opacity = '0';
    mainImg.style.transform = 'scale(0.97)';

    setTimeout(() => {
        mainImg.src = src;
        mainImg.style.opacity = '1';
        mainImg.style.transform = 'scale(1)';
    }, 200);

    // Update active thumb
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    thumbEl.classList.add('active');
}

// ── Tabs ──────────────────────────────────────────────────────
function switchTab(trigger, paneId) {
    document.querySelectorAll('.tab-trigger').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    trigger.classList.add('active');
    document.getElementById(paneId).classList.add('active');
}

// ── WhatsApp order ────────────────────────────────────────────
function orderWhatsApp() {
    const phone    = '{{ $whatsapp }}';
    const variant  = findMatchingVariant();
    const qty      = document.getElementById('qtyInput')?.value || 1;
    const price    = variant ? new Intl.NumberFormat('fr-DZ').format(variant.price) + ' DA' : '—';
    const varLabel = variant ? variant.label : '';

    let msg = `Bonjour, je souhaite commander :\n\n`;
    msg += `🛍️ Produit : {{ addslashes($product->name) }}\n`;
    if (varLabel) msg += `📦 Variante : ${varLabel}\n`;
    msg += `💰 Prix : ${price}\n`;
    msg += `🔢 Quantité : ${qty}`;

    const url = `https://wa.me/${phone.replace(/\D/g,'')}?text=${encodeURIComponent(msg)}`;
    window.open(url, '_blank', 'noopener,noreferrer');
}

// ── Toast ─────────────────────────────────────────────────────
function showToast(msg = 'Produit ajouté au panier !') {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ── Disable buttons for out-of-stock variants on load ─────────
function initDisabledStates() {
    attrNames.forEach(attrName => {
        const slug = Str_slug(attrName);
        const buttons = document.querySelectorAll(`[data-attr="${slug}"]`);
        buttons.forEach(btn => {
            const valId = parseInt(btn.dataset.valId);
            // A value is "available" if at least one variant with this value has stock
            const hasStock = variants.some(v => v.attrs.includes(valId) && v.stock > 0);
            if (!hasStock) {
                btn.classList.add('disabled');
                btn.disabled = true;
            }
        });
    });
}

// ── Init ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initDisabledStates();

    // Show sticky bar on mobile after scrolling past product section
    const obs = new IntersectionObserver(entries => {
        const bar = document.getElementById('stickyBar');
        if (bar) bar.style.display = entries[0].isIntersecting ? 'none' : 'flex';
    }, { threshold: 0 });
    const hero = document.querySelector('.btn-cart');
    if (hero) obs.observe(hero);

    // Intercept cart form submission to show toast (then submit normally)
    const form = document.getElementById('cartForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Let form submit normally; success msg comes back via session
        });
    }
});
</script>
</body>
</html>
