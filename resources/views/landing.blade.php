@php
    $cartCount    = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0;
    $logoPath     = \App\Models\Setting::get('shop_logo');
    $heroProducts = $newProducts->merge($promoProducts)->unique('id')->take(4);
@endphp
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shopName }} — Boutique en ligne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/boutique.css') }}">
    <style>
        html  { scroll-behavior: smooth; }
        *, *::before, *::after { -webkit-font-smoothing: antialiased; box-sizing: border-box; }
        body  { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #fff; }
        .line-through-price { text-decoration: line-through; }
        .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }

        /* MARQUEE */
        @keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .marquee-track { display: flex; animation: ticker 44s linear infinite; will-change: transform; }
        .marquee-track:hover { animation-play-state: paused; }

        /* HERO */
        .hero-section {
            background-color: #EDE8DF;
            background-image: radial-gradient(circle at 2px 2px, rgba(0,35,82,.08) 1px, transparent 0);
            background-size: 28px 28px;
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,35,82,.07) 0%, transparent 65%);
            top: -200px; right: -100px;
            pointer-events: none;
        }
        .hero-title {
            font-size: clamp(50px, 5.8vw, 86px);
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 1.0;
            color: #0D1B2A;
        }
        .hero-title .accent {
            background: linear-gradient(115deg, #002352 0%, #003D8F 55%, #001A3D 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* PRODUCT CARDS */
        .p-card {
            background: #fff;
            border: 1px solid #E8E8E8;
            border-radius: 20px;
            overflow: hidden;
            display: block;
            transition: transform .28s cubic-bezier(.4,0,.2,1), box-shadow .28s ease, border-color .2s;
        }
        .p-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 28px 56px rgba(0,35,82,.13);
            border-color: #B4C2D8;
        }
        .p-card-img { transition: transform .38s cubic-bezier(.4,0,.2,1); }
        .p-card:hover .p-card-img { transform: scale(1.07); }

        /* CARD HOVER OVERLAY */
        .p-card-overlay {
            position: absolute; inset: 0; z-index: 5;
            background: linear-gradient(to top, rgba(0,35,82,.78) 0%, rgba(0,35,82,.06) 55%, transparent 100%);
            display: flex; align-items: flex-end; justify-content: center;
            padding-bottom: 18px;
            opacity: 0;
            transition: opacity .28s ease;
        }
        .p-card:hover .p-card-overlay { opacity: 1; }
        .p-card-overlay-btn {
            background: white; color: #002352;
            font-size: 12px; font-weight: 700;
            padding: 9px 22px; border-radius: 99px;
            white-space: nowrap;
            transform: translateY(10px);
            transition: transform .28s cubic-bezier(.22,1,.36,1);
            box-shadow: 0 4px 16px rgba(0,0,0,.2);
            letter-spacing: .02em;
        }
        .p-card:hover .p-card-overlay-btn { transform: translateY(0); }

        /* CATEGORY CARDS */
        .c-card { border-radius: 22px; transition: transform .22s ease, box-shadow .22s ease; }
        .c-card:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 20px 42px rgba(0,35,82,.12); }
        .cg-0 { background: linear-gradient(145deg, #F5F0E8, #EDE8DF); }
        .cg-1 { background: linear-gradient(145deg, #EBF0FA, #D0DFF2); }
        .cg-2 { background: linear-gradient(145deg, #FFF7ED, #FED7AA); }
        .cg-3 { background: linear-gradient(145deg, #F0FDF4, #BBF7D0); }
        .cg-4 { background: linear-gradient(145deg, #FFF1F2, #FECDD3); }
        .cg-5 { background: linear-gradient(145deg, #FEFCE8, #FEF08A); }

        /* HERO MINI-CARDS */
        .hero-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,.13);
            width: 160px;
        }
        @media (min-width: 1280px) { .hero-card { width: 185px; } }

        /* ANIMATIONS */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .a1 { animation: fade-up .7s cubic-bezier(.22,1,.36,1) .05s both; }
        .a2 { animation: fade-up .7s cubic-bezier(.22,1,.36,1) .2s  both; }
        .a3 { animation: fade-up .7s cubic-bezier(.22,1,.36,1) .35s both; }
        .a4 { animation: fade-up .7s cubic-bezier(.22,1,.36,1) .5s  both; }
        .a5 { animation: fade-up .7s cubic-bezier(.22,1,.36,1) .65s both; }

        @keyframes float-y { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .fl-a { animation: float-y 5s   ease-in-out         infinite; }
        .fl-b { animation: float-y 6.5s ease-in-out .9s    infinite; }
        .fl-c { animation: float-y 5.5s ease-in-out 1.7s   infinite; }
        .fl-d { animation: float-y 7s   ease-in-out .4s    infinite; }

        /* scroll reveal */
        [data-sr] { opacity: 0; transform: translateY(22px); transition: opacity .65s cubic-bezier(.22,1,.36,1), transform .65s cubic-bezier(.22,1,.36,1); }
        [data-sr].is-visible { opacity: 1; transform: none; }
        [data-sr-d="1"] { transition-delay: .1s; }
        [data-sr-d="2"] { transition-delay: .2s; }
        [data-sr-d="3"] { transition-delay: .3s; }
        [data-sr-d="4"] { transition-delay: .4s; }

        /* EYEBROW */
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 11px; font-weight: 700; letter-spacing: .16em;
            text-transform: uppercase; color: #002352; margin-bottom: 14px;
        }
        .eyebrow::before {
            content: ''; display: block; width: 18px; height: 2px;
            background: #002352; border-radius: 99px;
        }

        /* DARK */
        .dark-strip { background: #002352; }

        /* LIVE DOT */
        @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.3} }
        .live-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* CTA SECTION */
        .cta-section {
            background: #001832;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,.04) 1px, transparent 0);
            background-size: 28px 28px;
            pointer-events: none;
        }
        .cta-section::after {
            content: '';
            position: absolute;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(237,232,223,.06) 0%, transparent 65%);
            bottom: -200px; left: -100px;
            pointer-events: none;
        }
        .cta-stat {
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 18px;
            padding: 22px 24px;
            background: rgba(255,255,255,.04);
            transition: background .2s, border-color .2s;
        }
        .cta-stat:hover { background: rgba(255,255,255,.07); border-color: rgba(255,255,255,.15); }

        /* FOOTER */
        .footer-root { background: #0D0D0D; }
        .footer-trust-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.11);
            border-radius: 99px; padding: 9px 18px;
            font-size: 13px; font-weight: 600;
            color: rgba(255,255,255,.75);
            white-space: nowrap;
            transition: background .2s, border-color .2s;
        }
        .footer-trust-pill:hover { background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.2); }
    </style>
</head>
<body class="text-[#0D1B2A] antialiased">

{{-- HEADER --}}
@include('client.partials.navbar')

{{-- HERO --}}
<section class="hero-section">
    <div class="relative z-10 w-full max-w-[1320px] mx-auto px-5 lg:px-8 py-20 lg:py-28">
        <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-8 xl:gap-16">
            <div class="flex-1 max-w-[580px]">
               
                <h1 class="hero-title a2 mb-6">
                    Commandez.<br>
                    Recevez.<br>
                    <span class="accent">Partout.</span>
                </h1>
                <p class="a3 text-[17px] text-[#6B7280] leading-[1.75] max-w-[460px] mb-10">
                    Découvrez notre sélection de produits et faites&#x2011;vous livrer à domicile, dans toutes les wilayas. Paiement uniquement à la réception.
                </p>
                <div class="a4 flex flex-wrap gap-3 mb-14">
                    <a href="/catalogue" class="inline-flex items-center gap-2.5 text-[14.5px] font-bold px-7 py-3.5 rounded-full text-white transition-all hover:scale-[1.03] hover:shadow-lg hover:shadow-[#002352]/40" style="background:linear-gradient(135deg,#002352,#003D8F)">
                        Voir les produits
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    @if($promoProducts->count())
                    <a href="#promotions" class="inline-flex items-center gap-2 text-[14.5px] font-semibold px-7 py-3.5 rounded-full bg-white border border-[#D4D8E2] text-[#0D1B2A] hover:border-[#002352] hover:text-[#002352] transition-colors shadow-sm">
                        Voir les promos
                    </a>
                    @endif
                </div>
                <div class="a5 flex items-center gap-8">
                    <div>
                        <p class="text-[32px] font-extrabold text-[#0D1B2A] leading-none tabular-nums">58</p>
                        <p class="text-[12px] text-[#9CA3AF] font-medium mt-1">Wilayas</p>
                    </div>
                    <div class="w-px h-10 bg-[#D1D5DB]"></div>
                    <div>
                        <p class="text-[32px] font-extrabold text-[#0D1B2A] leading-none">48h</p>
                        <p class="text-[12px] text-[#9CA3AF] font-medium mt-1">Livraison</p>
                    </div>
                    <div class="w-px h-10 bg-[#D1D5DB]"></div>
                    <div>
                        <p class="text-[32px] font-extrabold text-[#0D1B2A] leading-none">0 DA</p>
                        <p class="text-[12px] text-[#9CA3AF] font-medium mt-1">Avance requise</p>
                    </div>
                </div>
            </div>

            <div class="hidden lg:flex flex-1 items-center justify-center">
                @if($heroProducts->count() >= 2)
                <div class="grid grid-cols-2 gap-5 w-fit">
                    @foreach($heroProducts->take(4) as $idx => $product)
                    @php
                        $img = $product->images->first();
                        $floatClass = ['fl-a','fl-b','fl-c','fl-d'][$idx] ?? 'fl-a';
                        $offsetClass = $idx % 2 === 1 ? 'mt-10' : '';
                    @endphp
                    <a href="/produit/{{ $product->slug }}" class="hero-card {{ $floatClass }} {{ $offsetClass }} hover:shadow-2xl hover:scale-[1.04] transition-all duration-300">
                        <div class="aspect-square bg-[#F3F4F6] overflow-hidden">
                            @if($img)
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-9 h-9 text-[#C8CACD]" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3.5">
                            <p class="text-[12px] font-semibold text-[#111827] leading-tight line-clamp-2 mb-2">{{ Str::limit($product->name, 32) }}</p>
                            <p class="text-[13px] font-extrabold text-[#002352]">
                                @if($product->discount_price && $product->discount_price < $product->base_price)
                                    {{ number_format($product->discount_price, 0, ',', ' ') }} DA
                                @else
                                    {{ number_format($product->base_price, 0, ',', ' ') }} DA
                                @endif
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="grid grid-cols-2 gap-4 w-full max-w-[400px]">
                    <div class="bg-white rounded-2xl p-6 border border-[#EBEBEB] shadow-sm fl-a">
                        <div class="w-10 h-10 rounded-xl mb-4 flex items-center justify-center" style="background:linear-gradient(145deg,#F5F0E8,#EDE8DF)">
                            <svg class="w-5 h-5 text-[#002352]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        </div>
                        <p class="text-[28px] font-extrabold text-[#0D1B2A] leading-none">48h</p>
                        <p class="text-[12px] text-[#9CA3AF] font-medium mt-1.5 leading-tight">Délai de livraison</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-[#EBEBEB] shadow-sm fl-b mt-8">
                        <div class="w-10 h-10 rounded-xl mb-4 flex items-center justify-center" style="background:linear-gradient(145deg,#EBF0FA,#D0DFF2)">
                            <svg class="w-5 h-5 text-[#002352]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        <p class="text-[28px] font-extrabold text-[#0D1B2A] leading-none">58</p>
                        <p class="text-[12px] text-[#9CA3AF] font-medium mt-1.5 leading-tight">Wilayas couvertes</p>
                    </div>
                    <div class="rounded-2xl p-6 col-span-2 fl-c" style="background:#0D1B2A">
                        <div class="w-10 h-10 rounded-xl mb-4 flex items-center justify-center bg-white/10">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        </div>
                        <p class="text-[20px] font-extrabold text-white">Paiement a la livraison</p>
                        <p class="text-[12px] text-white/50 font-medium mt-1.5">Aucune avance. Payez uniquement a la reception.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- MARQUEE --}}
<div style="background:#002352; overflow:hidden; padding:13px 0; border-top:1px solid rgba(255,255,255,.08); border-bottom:1px solid rgba(255,255,255,.08);">
    <div class="marquee-track">
        @for($i = 0; $i < 2; $i++)
        <div class="flex items-center flex-shrink-0">
            @foreach(['Livraison dans les 58 wilayas', 'Paiement à la livraison', 'Produits de qualité', 'Commandez en toute sécurité', 'Livraison rapide 48h', 'Service client disponible', 'Retours faciles', '100% Algérien'] as $item)
            <span class="inline-flex items-center gap-5 px-7 whitespace-nowrap">
                <span style="color:#EDE8DF; font-size:11px; opacity:.55; font-weight:800; letter-spacing:.02em;">✦</span>
                <span style="font-size:12.5px; font-weight:600; color:rgba(255,255,255,.88); letter-spacing:.07em; text-transform:uppercase;">{{ $item }}</span>
            </span>
            @endforeach
        </div>
        @endfor
    </div>
</div>

{{-- CATEGORIES --}}
@if($categories->count())
<section id="categories" class="bg-white py-20 px-5 lg:px-8">
    <div class="max-w-[1320px] mx-auto">
        <div class="flex items-end justify-between gap-4 mb-10 flex-wrap" data-sr>
            <div>
                <p class="eyebrow">Explorer</p>
                <h2 class="text-[38px] sm:text-[46px] font-extrabold tracking-tight text-[#0D1B2A] leading-[1.05]">Nos Catégories</h2>
            </div>
            <a href="/catalogue" class="flex-shrink-0 inline-flex items-center gap-2 text-[13.5px] font-bold text-[#002352] hover:text-[#003D8F] transition-colors">
                Tout voir
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @foreach($categories as $catIdx => $cat)
            @php $palIdx = $catIdx % 6; @endphp
            <a href="/catalogue?categorie={{ $cat->id }}" class="c-card cg-{{ $palIdx }} group p-5 flex flex-col gap-3 cursor-pointer" data-sr data-sr-d="{{ min($catIdx + 1, 4) }}">
                <div class="w-11 h-11 bg-white/70 rounded-xl flex items-center justify-center shadow-sm text-[#374151]">
                    @include('partials.category-icon', ['icon' => $cat->icon, 'class' => 'w-6 h-6'])
                </div>
                <div class="flex items-center justify-between gap-2 mt-auto">
                    <span class="text-[13.5px] font-bold text-[#111827] leading-tight">{{ $cat->name }}</span>
                    <svg class="w-4 h-4 text-[#9CA3AF] group-hover:text-[#374151] group-hover:translate-x-0.5 transition-all flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- PROMOTIONS --}}
@if($promoProducts->count())
<section id="promotions" class="py-20 px-5 lg:px-8" style="background:#F8F7F4">
    <div class="max-w-[1320px] mx-auto">
        <div class="flex items-start sm:items-end justify-between gap-6 mb-12 flex-wrap" data-sr>
            <div class="flex items-center gap-5">
                <div class="flex-shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center text-[28px] font-extrabold text-white shadow-lg" style="background:#DC2626">%</div>
                <div>
                    <p class="eyebrow" style="color:#DC2626">Offres spéciales</p>
                    <h2 class="text-[38px] sm:text-[46px] font-extrabold tracking-tight text-[#0D1B2A] leading-[1.05]">Produits en Promotion</h2>
                </div>
            </div>
            <a href="/catalogue?promo=1" class="flex-shrink-0 inline-flex items-center gap-2 text-[13.5px] font-bold text-[#DC2626] hover:text-[#B91C1C] transition-colors">
                Toutes les promos
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
            @foreach($promoProducts as $pIdx => $product)
            @php
                $img        = $product->images->first();
                $totalStock = $product->variants->sum('stock');
                $discount   = round((($product->base_price - $product->discount_price) / $product->base_price) * 100);
            @endphp
            <a href="/produit/{{ $product->slug }}" class="p-card" data-sr data-sr-d="{{ min($pIdx + 1, 4) }}">
                <div class="relative overflow-hidden bg-[#F5F5F5]" style="aspect-ratio:4/5">
                    @if($img)
                        <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}" class="p-card-img w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-[#D1D5DB]" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </div>
                    @endif
                    <div class="p-card-overlay"><span class="p-card-overlay-btn">Voir le produit</span></div>
                    <span class="absolute top-3 left-3 z-10 bg-[#DC2626] text-white text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide">-{{ $discount }}%</span>
                    @if($totalStock === 0)
                    <span class="absolute top-3 right-3 z-10 bg-black/70 text-white text-[10px] font-semibold px-2.5 py-1 rounded-full">Rupture</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-[13.5px] font-semibold text-[#111827] leading-snug mb-2 line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-1.5 mb-3">
                        @if($totalStock > 0)
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full"><span class="w-1 h-1 rounded-full bg-emerald-500"></span>En stock</span>
                        @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#9CA3AF] bg-[#F3F4F6] px-2 py-0.5 rounded-full">Sur commande</span>
                        @endif
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-[17px] font-extrabold text-[#DC2626]">{{ number_format($product->discount_price, 0, ',', ' ') }} DA</span>
                        <span class="text-[12px] text-[#9CA3AF] line-through-price">{{ number_format($product->base_price, 0, ',', ' ') }} DA</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-10 flex items-center justify-center" data-sr>
            <a href="/catalogue?promo=1" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl font-bold text-white text-[14px] transition-all hover:scale-[1.02] hover:shadow-xl hover:shadow-[#DC2626]/25" style="background:#DC2626">
                Voir toutes nos promotions
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- NOUVEAUX ARRIVAGES --}}
@if($newProducts->count())
<section id="nouveautes" class="bg-white py-20 px-5 lg:px-8">
    <div class="max-w-[1320px] mx-auto">
        <div class="flex items-start sm:items-end justify-between gap-6 mb-12 flex-wrap" data-sr>
            <div class="flex items-center gap-5">
                <div class="flex-shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center text-[22px] font-extrabold text-white shadow-lg" style="background:#002352">✦</div>
                <div>
                    <p class="eyebrow">Tout juste arrivé</p>
                    <h2 class="text-[38px] sm:text-[46px] font-extrabold tracking-tight text-[#0D1B2A] leading-[1.05]">Nouveaux Arrivages</h2>
                </div>
            </div>
            <a href="/catalogue" class="flex-shrink-0 inline-flex items-center gap-2 text-[13.5px] font-bold text-[#002352] hover:text-[#003D8F] transition-colors">
                Tout le catalogue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
            @foreach($newProducts as $nIdx => $product)
            @php
                $img        = $product->images->first();
                $totalStock = $product->variants->sum('stock');
                $isPromo    = $product->discount_price && $product->discount_price < $product->base_price;
                $minPrice   = $product->variants->min('price') ?? $product->base_price;
            @endphp
            <a href="/produit/{{ $product->slug }}" class="p-card" data-sr data-sr-d="{{ min($nIdx + 1, 4) }}">
                <div class="relative overflow-hidden bg-[#F5F5F5]" style="aspect-ratio:4/5">
                    @if($img)
                        <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}" class="p-card-img w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-[#D1D5DB]" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </div>
                    @endif
                    <div class="p-card-overlay"><span class="p-card-overlay-btn">Voir le produit</span></div>
                    @if($isPromo)
                        @php $discount = round((($product->base_price - $product->discount_price) / $product->base_price) * 100); @endphp
                        <span class="absolute top-3 left-3 z-10 bg-[#DC2626] text-white text-[10px] font-bold px-2.5 py-1 rounded-full">-{{ $discount }}%</span>
                    @else
                        <span class="absolute top-3 left-3 z-10 text-[10px] font-bold px-2.5 py-1 rounded-full text-white" style="background:#002352">NOUVEAU</span>
                    @endif
                    @if($totalStock === 0)
                    <span class="absolute top-3 right-3 z-10 bg-black/70 text-white text-[10px] font-semibold px-2.5 py-1 rounded-full">Rupture</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-[13.5px] font-semibold text-[#111827] leading-snug mb-2 line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-1.5 mb-3">
                        @if($totalStock > 0)
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full"><span class="w-1 h-1 rounded-full bg-emerald-500"></span>En stock</span>
                        @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#9CA3AF] bg-[#F3F4F6] px-2 py-0.5 rounded-full">Sur commande</span>
                        @endif
                    </div>
                    @if($isPromo)
                    <div class="flex items-baseline gap-2">
                        <span class="text-[17px] font-extrabold text-[#DC2626]">{{ number_format($product->discount_price, 0, ',', ' ') }} DA</span>
                        <span class="text-[12px] text-[#9CA3AF] line-through-price">{{ number_format($product->base_price, 0, ',', ' ') }} DA</span>
                    </div>
                    @else
                    <span class="text-[17px] font-extrabold text-[#002352]">
                        @if($product->variants->count() > 1 && $product->variants->pluck('price')->unique()->count() > 1)
                            À partir de {{ number_format($minPrice, 0, ',', ' ') }} DA
                        @else
                            {{ number_format($product->base_price, 0, ',', ' ') }} DA
                        @endif
                    </span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-10 flex items-center justify-center" data-sr>
            <a href="/catalogue" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl font-bold text-[#002352] text-[14px] border-2 border-[#002352] transition-all hover:bg-[#002352] hover:text-white hover:shadow-xl hover:shadow-[#002352]/25">
                Découvrir tout le catalogue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- CTA STRIP --}}
<section class="cta-section py-20 px-5 lg:px-8">
    <div class="relative z-10 max-w-[1320px] mx-auto">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-20">

            {{-- Left: text --}}
            <div class="flex-1 max-w-[560px]" data-sr>
            
                <h2 class="font-extrabold tracking-tight text-white leading-[1.05] mb-6" style="font-size:clamp(36px,4.5vw,60px)">
                    Commandez depuis<br>
                    <span style="color:#EDE8DF">n'importe quelle wilaya.</span>
                </h2>
                <p class="text-[15.5px] leading-[1.75] mb-8" style="color:rgba(255,255,255,.5); max-width:420px">
                    Livraison à domicile en 48h. Aucune avance requise &mdash; payez uniquement à la réception de votre commande.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="/catalogue" class="inline-flex items-center gap-2.5 text-[14.5px] font-bold px-7 py-3.5 rounded-full transition-all hover:scale-[1.03] hover:shadow-xl hover:shadow-black/40" style="background:#EDE8DF; color:#002352">
                        Voir les produits
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="/suivi" class="inline-flex items-center gap-2 text-[14.5px] font-semibold px-7 py-3.5 rounded-full transition-colors" style="border:1px solid rgba(255,255,255,.18); color:rgba(255,255,255,.7)">
                        Suivre une commande
                    </a>
                </div>
            </div>

            {{-- Right: stats --}}
            <div class="flex-shrink-0 grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-3 w-full lg:w-[240px]" data-sr data-sr-d="2">
                <div class="cta-stat text-right">
                    <p class="text-[34px] font-extrabold text-white leading-none mb-1">58</p>
                    <p class="text-[12.5px] font-medium" style="color:rgba(255,255,255,.45)">Wilayas livrées</p>
                </div>
                <div class="cta-stat text-right">
                    <p class="text-[34px] font-extrabold text-white leading-none mb-1">48h</p>
                    <p class="text-[12.5px] font-medium" style="color:rgba(255,255,255,.45)">Délai de livraison</p>
                </div>
                <div class="cta-stat text-right">
                    <p class="text-[34px] font-extrabold leading-none mb-1" style="color:#EDE8DF">0 DA</p>
                    <p class="text-[12.5px] font-medium" style="color:rgba(255,255,255,.45)">Avance requise</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer style="background:#F8F7F4; border-top:1px solid #E8E4DC;">
    <div class="max-w-[1320px] mx-auto px-5 lg:px-8">

        {{-- Main grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr_1fr_1fr] gap-12 py-16" style="border-bottom:1px solid #E8E4DC">

            {{-- Brand column --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    @include('partials.shop-logo', [
                        'logoPath' => $logoPath,
                        'shopName' => $shopName,
                        'containerClass' => 'w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center shadow-sm',
                        'imageClass' => 'w-full h-full object-contain p-1.5',
                        'iconClass' => 'w-4 h-4 text-white',
                    ])
                    <span class="font-extrabold text-[17px] tracking-tight text-[#0D1B2A]">{{ $shopName }}</span>
                </div>
                <p class="text-[13.5px] leading-[1.7] mb-7 text-[#6B7280]" style="max-width:280px">Boutique algérienne en ligne. Livraison à domicile dans toutes les wilayas, paiement uniquement à la réception.</p>
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

            {{-- Nav: Boutique --}}
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.16em] mb-6 text-[#9CA3AF]">Boutique</p>
                <ul class="space-y-3.5">
                    <li><a href="/" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Accueil</a></li>
                    <li><a href="/catalogue" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Catalogue</a></li>
                    @if($promoProducts->count())
                    <li><a href="/catalogue?promo=1" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Promotions</a></li>
                    @endif
                    <li><a href="/panier" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Mon panier</a></li>
                    <li><a href="/suivi" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Suivi commande</a></li>
                    <li><a href="/code-promo" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Code Promo</a></li>
                </ul>
            </div>

            {{-- Nav: Catégories --}}
            @if($categories->count())
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.16em] mb-6 text-[#9CA3AF]">Catégories</p>
                <ul class="space-y-3.5">
                    @foreach($categories->take(6) as $cat)
                    <li><a href="/catalogue?categorie={{ $cat->id }}" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Livraison info --}}
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.16em] mb-6 text-[#9CA3AF]">Livraison</p>
                <div class="space-y-5">
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
                            <p class="text-[13px] font-semibold leading-none mb-1 text-[#0D1B2A]">48h</p>
                            <p class="text-[12px] text-[#9CA3AF]">Délai de livraison</p>
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
                    @if($shopCgv)
                    <a href="/conditions-de-vente" class="inline-flex text-[12.5px] font-medium mt-2 text-[#9CA3AF] hover:text-[#002352] transition-colors">Conditions de vente &rarr;</a>
                    @endif
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

<script>
    (function() {
        var els = document.querySelectorAll('[data-sr]');
        var io  = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
            });
        }, { threshold: 0.12 });
        els.forEach(function(el) { io.observe(el); });
    })();
</script>

</body>
</html>
