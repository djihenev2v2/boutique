@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $currentSort   = request('tri', 'recent');
    $currentQ      = request('q', '');
    $currentCats   = request()->has('categorie') ? (array) request('categorie') : [];
    $currentMin    = request('min_prix', '');
    $currentMax    = request('max_prix', '');
    $currentPromo  = request()->boolean('promo');
    $currentNew    = request()->boolean('nouveau');
    $currentStock  = request()->boolean('en_stock');
    $currentTaille = request()->has('taille') ? (array) request('taille') : [];
    $currentColor  = request()->has('couleur') ? (array) request('couleur') : [];
    $currentPointe = request()->has('pointure') ? (array) request('pointure') : [];
    $attrMap       = request()->has('attr') ? (array) request('attr') : [];

    $totalFilters = count($currentCats) + count($currentTaille) + count($currentColor) + count($currentPointe)
        + ($currentPromo ? 1 : 0) + ($currentNew ? 1 : 0) + ($currentStock ? 1 : 0)
        + ($currentMin !== '' ? 1 : 0) + ($currentMax !== '' ? 1 : 0)
        + array_sum(array_map(fn($v) => count((array)$v), $attrMap));

    // Color swatches mapping
    $colorMap = [
        'Noir' => '#111827', 'Blanc' => '#F9FAFB', 'Rouge' => '#EF4444', 'Bleu' => '#3B82F6',
        'Vert' => '#22C55E', 'Jaune' => '#EAB308', 'Rose' => '#EC4899', 'Gris' => '#9CA3AF',
        'Marron' => '#92400E', 'Beige' => '#F5F0E8', 'Violet' => '#A855F7', 'Orange' => '#F97316',
        'Navy' => '#002352', 'Kaki' => '#7D7C4F', 'Bordeaux' => '#7F1D1D',
    ];
@endphp
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue — {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F7F4; }

        /* CARD */
        .p-card {
            background: #fff; border: 1px solid #EAECF0; border-radius: 20px;
            overflow: hidden; display: flex; flex-direction: column;
            transition: transform .28s cubic-bezier(.4,0,.2,1), box-shadow .28s ease, border-color .2s;
        }
        .p-card:hover { transform: translateY(-6px); box-shadow: 0 24px 48px rgba(0,35,82,.12); border-color: #B4C2D8; }
        .p-card-img { transition: transform .38s cubic-bezier(.4,0,.2,1); }
        .p-card:hover .p-card-img { transform: scale(1.06); }

        /* Card overlay */
        .p-card-overlay {
            position: absolute; inset: 0; z-index: 5;
            background: linear-gradient(to top, rgba(0,35,82,.75) 0%, rgba(0,35,82,.05) 50%, transparent 100%);
            display: flex; align-items: flex-end; justify-content: center;
            padding-bottom: 16px; opacity: 0;
            transition: opacity .28s ease;
        }
        .p-card:hover .p-card-overlay { opacity: 1; }
        .p-card-overlay-btn {
            background: white; color: #002352;
            font-size: 12px; font-weight: 700; letter-spacing: .02em;
            padding: 9px 22px; border-radius: 99px;
            transform: translateY(10px);
            transition: transform .28s cubic-bezier(.22,1,.36,1);
            box-shadow: 0 4px 16px rgba(0,0,0,.2);
            white-space: nowrap;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .p-card:hover .p-card-overlay-btn { transform: translateY(0); }

        /* FILTER SIDEBAR */
        .filter-section { border-bottom: 1px solid #E8E3DB; padding-bottom: 20px; margin-bottom: 20px; }
        .filter-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

        /* Checkbox custom */
        .cb-wrap { display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 5px 8px; border-radius: 8px; transition: background .15s; }
        .cb-wrap:hover { background: #F5F0E8; }
        .cb-wrap input[type=checkbox] { accent-color: #002352; width: 15px; height: 15px; cursor: pointer; flex-shrink: 0; }
        .cb-label { font-size: 13px; font-weight: 500; color: #374151; user-select: none; }
        .cb-count { margin-left: auto; font-size: 11px; font-weight: 600; color: #9CA3AF; background: #F3F4F6; border-radius: 99px; padding: 1px 7px; }

        /* Color button */
        .color-btn {
            width: 28px; height: 28px; border-radius: 50%; cursor: pointer;
            border: 2px solid transparent; transition: all .18s; position: relative;
            flex-shrink: 0;
        }
        .color-btn:hover { transform: scale(1.15); }
        .color-btn.active { border-color: #002352; box-shadow: 0 0 0 2px white, 0 0 0 4px #002352; }
        .color-btn-white { border-color: #D1D5DB !important; }
        .color-btn-white.active { box-shadow: 0 0 0 2px white, 0 0 0 4px #002352 !important; }

        /* Size pill */
        .size-pill {
            padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;
            border: 1.5px solid #E5E7EB; cursor: pointer; transition: all .18s;
            background: white; color: #374151; white-space: nowrap;
        }
        .size-pill:hover { border-color: #002352; color: #002352; background: #F5F0E8; }
        .size-pill.active { border-color: #002352; background: #002352; color: white; }

        /* BADGES */
        .badge-promo { background: #FEF2F2; color: #DC2626; font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; padding: 2px 8px; border-radius: 6px; }
        .badge-new   { background: #F0FDF4; color: #16A34A; font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; padding: 2px 8px; border-radius: 6px; }
        .badge-rupture { background: #F9FAFB; color: #9CA3AF; font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; padding: 2px 8px; border-radius: 6px; border: 1px solid #E5E7EB; }

        /* PRICE RANGE */
        input[type=range] { accent-color: #002352; }

        /* Active filter chips */
        .filter-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: #F5F0E8; color: #002352; border-radius: 99px;
            padding: 4px 10px; font-size: 11.5px; font-weight: 600;
        }
        .filter-chip-close { display: inline-flex; align-items: center; justify-content: center; width: 14px; height: 14px; border-radius: 50%; cursor: pointer; transition: background .15s; background: rgba(0,35,82,.15); }
        .filter-chip-close:hover { background: #002352; color: white; }

        /* SKELETON */
        @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
        .skeleton { background: linear-gradient(90deg, #F3F4F6 25%, #E5E7EB 50%, #F3F4F6 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 12px; }

        /* SCROLL REVEAL */
        [data-sr] { opacity: 0; transform: translateY(20px); transition: opacity .55s cubic-bezier(.22,1,.36,1), transform .55s cubic-bezier(.22,1,.36,1); }
        [data-sr].visible { opacity: 1; transform: none; }
        [data-sr-d="1"] { transition-delay: .05s; }
        [data-sr-d="2"] { transition-delay: .1s; }
        [data-sr-d="3"] { transition-delay: .15s; }
        [data-sr-d="4"] { transition-delay: .2s; }
        [data-sr-d="5"] { transition-delay: .25s; }
        [data-sr-d="6"] { transition-delay: .3s; }

        /* RESPONSIVE FILTER DRAWER */
        .filter-drawer { position: fixed; inset: 0; z-index: 60; }
        .filter-drawer-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,.45); backdrop-filter: blur(4px); }
        .filter-drawer-panel { position: absolute; left: 0; top: 0; bottom: 0; width: min(340px, 90vw); background: #FFFDF9; overflow-y: auto; padding: 24px 20px; box-shadow: 20px 0 40px rgba(0,0,0,.12); }

        /* SORT SELECT */
        .sort-select {
            appearance: none; background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%236B7280' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") no-repeat right 10px center / 16px;
            border: 1.5px solid #E5E7EB; border-radius: 12px;
            padding: 8px 32px 8px 14px; font-size: 13px; font-weight: 600;
            color: #374151; cursor: pointer; transition: border-color .18s;
        }
        .sort-select:hover, .sort-select:focus { border-color: #002352; outline: none; }

        /* SEARCH INPUT */
        .search-wrap { position: relative; }
        .search-input-dark::placeholder { color: rgba(255,255,255,.4); }
        .search-input {
            width: 100%; background: white; border: 1.5px solid #E5E7EB;
            border-radius: 14px; padding: 11px 46px 11px 44px;
            font-size: 14px; font-family: inherit; color: #111827;
            transition: border-color .2s, box-shadow .2s;
        }
        .search-input:focus { outline: none; border-color: #002352; box-shadow: 0 0 0 3px rgba(0,35,82,.08); }
        .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9CA3AF; pointer-events: none; }
        .search-clear { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: #F3F4F6; border: none; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #6B7280; transition: background .15s; }
        .search-clear:hover { background: #E5E7EB; }

        /* PAGINATION */
        .page-btn {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 38px; height: 38px; px: 3; border-radius: 10px;
            font-size: 13.5px; font-weight: 600; border: 1.5px solid #E5E7EB;
            background: white; color: #374151; cursor: pointer;
            transition: all .18s; text-decoration: none;
        }
        .page-btn:hover { border-color: #002352; color: #002352; background: #EBF0FA; }
        .page-btn.active { background: #002352; color: white; border-color: #002352; }
        .page-btn:disabled, .page-btn.disabled { opacity: .4; cursor: default; pointer-events: none; }

        /* LINE CLAMP */
        .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
        .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }

        /* STICKY SIDEBAR */
        @media (min-width: 1024px) {
            .sidebar-sticky { position: sticky; top: 80px; }
        }

        /* MARQUEE */
        @keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .marquee-track { display: flex; animation: ticker 40s linear infinite; will-change: transform; }
        .marquee-track:hover { animation-play-state: paused; }

        /* COLLAPSE */
        .collapse-content { overflow: hidden; transition: max-height .3s ease, opacity .3s ease; }
        .collapse-content.collapsed { max-height: 0 !important; opacity: 0; }

        /* TOAST */
        .toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            background: #002352; color: white;
            padding: 14px 20px; border-radius: 16px;
            font-size: 14px; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 16px 40px rgba(0,35,82,.35);
            transform: translateY(80px); opacity: 0;
            transition: transform .35s cubic-bezier(.22,1,.36,1), opacity .35s;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
    </style>
</head>
<body>

{{-- HEADER --}}
@include('client.partials.navbar')

{{-- PAGE HERO BAR --}}
<div style="background:#001832; border-bottom:1px solid rgba(237,232,223,.18);" class="relative overflow-hidden">
    <div style="position:absolute; inset:0; background-image:radial-gradient(circle at 2px 2px, rgba(255,255,255,.04) 1px, transparent 0); background-size:28px 28px; pointer-events:none;"></div>
    {{-- Decorative circles --}}
    <div style="position:absolute; width:560px; height:560px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.05) 0%, transparent 65%); top:-280px; right:-60px; pointer-events:none;"></div>
    <div style="position:absolute; width:280px; height:280px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.04) 0%, transparent 65%); bottom:-140px; left:8%; pointer-events:none;"></div>
    <div class="max-w-[1400px] mx-auto px-5 lg:px-8 relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-8 py-10">
            <div>
                {{-- Breadcrumb --}}
                <div class="inline-flex items-center gap-2 text-white/50 text-[11px] font-semibold uppercase tracking-widest mb-4">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    <a href="/" class="hover:text-white transition-colors">Accueil</a>
                    <span class="text-white/20">/</span>
                    <span class="text-[#EDE8DF] font-bold">Catalogue</span>
                </div>
                {{-- Title --}}
                <h1 class="text-[#EDE8DF] text-[34px] sm:text-[44px] font-extrabold tracking-tight leading-none mb-4">
                    Notre Catalogue
                </h1>
                {{-- Count pill --}}
                <div class="inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 border border-white/20" style="background:rgba(255,255,255,.1); backdrop-filter:blur(8px)">
                    <span class="text-[12.5px] font-semibold text-white/80">
                        {{ $products->total() }} produit{{ $products->total() !== 1 ? 's' : '' }}
                        @if($currentQ) &mdash; "<em class="text-white not-italic font-bold">{{ $currentQ }}</em>"@endif
                    </span>
                </div>
            </div>

            {{-- Search bar --}}
            <div class="w-full sm:w-auto sm:min-w-[360px]">
                <form id="searchForm" method="GET" action="{{ route('catalogue') }}">
                    @foreach(request()->except(['q','page']) as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach
                    <div class="search-wrap">
                        <svg class="search-icon w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:rgba(255,255,255,.5)"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="q" id="searchInput" value="{{ $currentQ }}" placeholder="Rechercher un produit, une marque…"
                               class="search-input search-input-dark" style="background:rgba(255,255,255,.12); border-color:rgba(255,255,255,.2); color:white;"
                               oninput="handleSearch(this)">
                        @if($currentQ)
                        <button type="button" class="search-clear" onclick="clearSearch()" style="background:rgba(255,255,255,.2); color:white;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MAIN LAYOUT --}}
<div class="max-w-[1400px] mx-auto px-5 lg:px-8 py-8">

    {{-- ACTIVE FILTER CHIPS + SORT BAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
        {{-- Filter toggle (mobile) + active chips --}}
        <div class="flex items-center gap-2 flex-wrap flex-1">
            <button onclick="openFilterDrawer()" class="lg:hidden inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-[#002352] text-[#002352] font-semibold text-[13px] hover:bg-[#EBF0FA] transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                Filtres
                @if($totalFilters > 0)<span class="bg-[#002352] text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $totalFilters }}</span>@endif
            </button>

            {{-- Chips --}}
            @foreach($currentCats as $catId)
                @php $catName = $categories->find($catId)?->name ?? $catId; @endphp
                <a href="{{ request()->fullUrlWithoutParameters(['categorie', 'page']) }}" class="filter-chip">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15M3.75 9h16.5M3.75 15h16.5"/></svg>
                    {{ $catName }}
                    <span class="filter-chip-close"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span>
                </a>
            @endforeach
            @if($currentPromo)<a href="{{ request()->fullUrlWithoutParameters(['promo','page']) }}" class="filter-chip">🏷️ Promotions <span class="filter-chip-close"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span></a>@endif
            @if($currentNew)<a href="{{ request()->fullUrlWithoutParameters(['nouveau','page']) }}" class="filter-chip">✨ Nouveautés <span class="filter-chip-close"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span></a>@endif
            @if($currentStock)<a href="{{ request()->fullUrlWithoutParameters(['en_stock','page']) }}" class="filter-chip">✅ En stock <span class="filter-chip-close"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span></a>@endif
            @foreach($currentTaille as $val)
                <a href="{{ request()->fullUrlWithoutParameters(['taille','page']) }}" class="filter-chip">📏 {{ $val }} <span class="filter-chip-close"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span></a>
            @endforeach
            @foreach($currentColor as $val)
                <a href="{{ request()->fullUrlWithoutParameters(['couleur','page']) }}" class="filter-chip">🎨 {{ $val }} <span class="filter-chip-close"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span></a>
            @endforeach            @foreach($attrMap as $attrNameKey => $attrVals)
                @foreach((array)$attrVals as $val)
                <a href="{{ request()->fullUrlWithoutParameters(['attr','page']) }}" class="filter-chip">
                    <span class="text-[#002352]/60 text-[10.5px]">{{ $attrNameKey }}:</span> {{ $val }}
                    <span class="filter-chip-close"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span>
                </a>
                @endforeach
            @endforeach            @if($totalFilters > 0)
            <a href="{{ route('catalogue', $currentQ ? ['q' => $currentQ] : []) }}" class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-red-500 hover:text-red-700 transition-colors ml-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Tout effacer
            </a>
            @endif
        </div>

        {{-- Sort --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <span class="text-[12px] font-semibold text-[#9CA3AF] hidden sm:block">Trier par</span>
            <form method="GET" action="{{ route('catalogue') }}" id="sortForm">
                @foreach(request()->except(['tri','page']) as $key => $val)
                    @if(is_array($val))
                        @foreach($val as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <select name="tri" class="sort-select" onchange="this.form.submit()">
                    <option value="recent"    {{ $currentSort === 'recent'    ? 'selected' : '' }}>Plus récent</option>
                    <option value="prix_asc"  {{ $currentSort === 'prix_asc'  ? 'selected' : '' }}>Prix croissant</option>
                    <option value="prix_desc" {{ $currentSort === 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                    <option value="nom"       {{ $currentSort === 'nom'       ? 'selected' : '' }}>Nom A → Z</option>
                </select>
            </form>
        </div>
    </div>

    <div class="flex gap-8">

        {{-- ============ SIDEBAR FILTERS (Desktop) ============ --}}
        <aside class="hidden lg:block w-[260px] flex-shrink-0">
            <div class="rounded-2xl border border-[#DDD6CA] p-6 sidebar-sticky" style="background:#FFFDF9; border-top:3px solid #002352;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#002352]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                        <h2 class="text-[15px] font-bold text-[#0D1B2A]">Filtres</h2>
                    </div>
                    @if($totalFilters > 0)
                    <a href="{{ route('catalogue', $currentQ ? ['q' => $currentQ] : []) }}" class="text-[11.5px] font-semibold text-red-500 hover:text-red-700 transition-colors">Réinitialiser</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('catalogue') }}" id="filterForm">
                    @if($currentQ)<input type="hidden" name="q" value="{{ $currentQ }}">@endif
                    <input type="hidden" name="tri" value="{{ $currentSort }}">

                    {{-- CATEGORIES --}}
                    <div class="filter-section" id="sec-categories">
                        <button type="button" class="flex items-center justify-between w-full mb-3" onclick="toggleSection('categories')">
                            <h3 class="text-[13px] font-bold text-[#374151] uppercase tracking-wider">Catégorie</h3>
                            <svg id="icon-categories" class="w-4 h-4 text-[#9CA3AF] transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div class="collapse-content space-y-0.5" id="content-categories" style="max-height:999px">
                            @foreach($categories as $cat)
                            <label class="cb-wrap">
                                <input type="checkbox" name="categorie[]" value="{{ $cat->id }}" {{ in_array($cat->id, $currentCats) ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="flex items-center gap-1.5 flex-1 min-w-0">
                                    <span class="text-[#002352] flex-shrink-0">@include('partials.category-icon', ['icon' => $cat->icon, 'class' => 'w-3.5 h-3.5'])</span>
                                    <span class="cb-label">{{ $cat->name }}</span>
                                </span>
                                <span class="cb-count">{{ $cat->products->count() }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- PRICE RANGE --}}
                    <div class="filter-section" id="sec-prix">
                        <button type="button" class="flex items-center justify-between w-full mb-3" onclick="toggleSection('prix')">
                            <h3 class="text-[13px] font-bold text-[#374151] uppercase tracking-wider">Prix (DA)</h3>
                            <svg id="icon-prix" class="w-4 h-4 text-[#9CA3AF] transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div class="collapse-content" id="content-prix" style="max-height:999px">
                            @php
                                $pMin = (int)($priceRange->min ?? 0);
                                $pMax = (int)($priceRange->max ?? 100000);
                                $curMin = $currentMin !== '' ? (int)$currentMin : null;
                                $curMax = $currentMax !== '' ? (int)$currentMax : null;
                            @endphp
                            <p class="text-[11px] text-[#9CA3AF] font-medium mb-3">
                                Intervalle : {{ number_format($pMin, 0, ',', ' ') }} — {{ number_format($pMax, 0, ',', ' ') }} DA
                            </p>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div>
                                    <label class="text-[10.5px] font-bold text-[#9CA3AF] uppercase tracking-wide block mb-1">Min (DA)</label>
                                    <input type="number" name="min_prix" id="minPriceInput" value="{{ $curMin }}" min="{{ $pMin }}" max="{{ $pMax }}"
                                           class="w-full border border-[#E8E3DB] rounded-xl px-3 py-2 text-[12.5px] font-semibold text-[#374151] focus:outline-none focus:border-[#002352] transition-colors"
                                           placeholder="{{ $pMin }}">
                                </div>
                                <div>
                                    <label class="text-[10.5px] font-bold text-[#9CA3AF] uppercase tracking-wide block mb-1">Max (DA)</label>
                                    <input type="number" name="max_prix" id="maxPriceInput" value="{{ $curMax }}" min="{{ $pMin }}" max="{{ $pMax }}"
                                           class="w-full border border-[#E8E3DB] rounded-xl px-3 py-2 text-[12.5px] font-semibold text-[#374151] focus:outline-none focus:border-[#002352] transition-colors"
                                           placeholder="{{ $pMax }}">
                                </div>
                            </div>
                            <button type="submit" class="w-full text-[12.5px] font-bold py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 hover:opacity-90" style="background:#002352; color:white;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Appliquer
                            </button>
                        </div>
                    </div>

                    {{-- SIZES --}}
                    @if(isset($attributes['taille']) && $attributes['taille']->values->count())
                    <div class="filter-section" id="sec-taille">
                        <button type="button" class="flex items-center justify-between w-full mb-3" onclick="toggleSection('taille')">
                            <h3 class="text-[13px] font-bold text-[#374151] uppercase tracking-wider">Taille</h3>
                            <svg id="icon-taille" class="w-4 h-4 text-[#9CA3AF] transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div class="collapse-content flex flex-wrap gap-1.5" id="content-taille" style="max-height:999px">
                            @foreach($attributes['taille']->values as $val)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="taille[]" value="{{ $val->value }}" {{ in_array($val->value, $currentTaille) ? 'checked' : '' }} class="sr-only" onchange="this.form.submit()">
                                <span class="size-pill {{ in_array($val->value, $currentTaille) ? 'active' : '' }}">{{ $val->value }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- COLORS --}}
                    @if(isset($attributes['couleur']) && $attributes['couleur']->values->count())
                    <div class="filter-section" id="sec-couleur">
                        <button type="button" class="flex items-center justify-between w-full mb-3" onclick="toggleSection('couleur')">
                            <h3 class="text-[13px] font-bold text-[#374151] uppercase tracking-wider">Couleur</h3>
                            <svg id="icon-couleur" class="w-4 h-4 text-[#9CA3AF] transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div class="collapse-content space-y-0.5" id="content-couleur" style="max-height:999px">
                            @foreach($attributes['couleur']->values as $val)
                            @php
                                $hex = $colorMap[$val->value] ?? '#9CA3AF';
                                $isLight = in_array($val->value, ['Blanc','Beige']);
                                $isActive = in_array($val->value, $currentColor);
                            @endphp
                            <label class="cb-wrap cursor-pointer">
                                <input type="checkbox" name="couleur[]" value="{{ $val->value }}" {{ $isActive ? 'checked' : '' }} class="sr-only" onchange="this.form.submit()">
                                <span class="w-4 h-4 rounded-full flex-shrink-0 {{ $isLight ? 'border border-[#D1D5DB]' : '' }} {{ $isActive ? 'ring-2 ring-[#002352] ring-offset-1' : '' }}" style="background:{{ $hex }}"></span>
                                <span class="cb-label flex-1">{{ $val->value }}</span>
                                @if($isActive)<svg class="w-3.5 h-3.5 text-[#002352] ml-auto flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>@endif
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- POINTURES --}}
                    @if(isset($attributes['pointure']) && $attributes['pointure']->values->count())
                    <div class="filter-section" id="sec-pointure">
                        <button type="button" class="flex items-center justify-between w-full mb-3" onclick="toggleSection('pointure')">
                            <h3 class="text-[13px] font-bold text-[#374151] uppercase tracking-wider">Pointure</h3>
                            <svg id="icon-pointure" class="w-4 h-4 text-[#9CA3AF] transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div class="collapse-content flex flex-wrap gap-1.5" id="content-pointure" style="max-height:999px">
                            @foreach($attributes['pointure']->values as $val)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="pointure[]" value="{{ $val->value }}" {{ in_array($val->value, $currentPointe) ? 'checked' : '' }} class="sr-only" onchange="this.form.submit()">
                                <span class="size-pill {{ in_array($val->value, $currentPointe) ? 'active' : '' }}">{{ $val->value }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- DYNAMIC ATTRIBUTE FILTERS --}}
                    @foreach($attributes as $attrName => $attr)
                        @php
                            $attrNameLow = strtolower($attrName);
                            $currentAttrVals = isset($attrMap[$attrName]) ? (array) $attrMap[$attrName] : [];
                            $dynSecId = 'dyn_' . preg_replace('/[^a-z0-9]/', '_', $attrNameLow);
                        @endphp
                        @continue(in_array($attrNameLow, ['taille', 'couleur', 'pointure']))
                        @continue(!$attr->values->count())
                        <div class="filter-section" id="sec-{{ $dynSecId }}">
                            <button type="button" class="flex items-center justify-between w-full mb-3" onclick="toggleSection('{{ $dynSecId }}')">
                                <h3 class="text-[13px] font-bold text-[#374151] uppercase tracking-wider">{{ ucfirst($attrName) }}</h3>
                                <svg id="icon-{{ $dynSecId }}" class="w-4 h-4 text-[#9CA3AF] transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            <div class="collapse-content flex flex-wrap gap-1.5" id="content-{{ $dynSecId }}" style="max-height:999px">
                                @foreach($attr->values as $val)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="attr[{{ $attrName }}][]" value="{{ $val->value }}" {{ in_array($val->value, $currentAttrVals) ? 'checked' : '' }} class="sr-only" onchange="this.form.submit()">
                                    <span class="size-pill {{ in_array($val->value, $currentAttrVals) ? 'active' : '' }}">{{ $val->value }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- SPECIAL FILTERS --}}
                    <div class="filter-section" id="sec-options">
                        <button type="button" class="flex items-center justify-between w-full mb-3" onclick="toggleSection('options')">
                            <h3 class="text-[13px] font-bold text-[#374151] uppercase tracking-wider">Options</h3>
                            <svg id="icon-options" class="w-4 h-4 text-[#9CA3AF] transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div class="collapse-content space-y-0.5" id="content-options" style="max-height:999px">
                            <label class="cb-wrap">
                                <input type="checkbox" name="promo" value="1" {{ $currentPromo ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg flex-shrink-0" style="background:#FEF2F2">
                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                                </span>
                                <span class="cb-label">En promotion</span>
                            </label>
                            <label class="cb-wrap">
                                <input type="checkbox" name="nouveau" value="1" {{ $currentNew ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg flex-shrink-0" style="background:#F0FDF4">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                                </span>
                                <span class="cb-label">Nouveautés</span>
                            </label>
                            <label class="cb-wrap">
                                <input type="checkbox" name="en_stock" value="1" {{ $currentStock ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg flex-shrink-0" style="background:#EFF6FF">
                                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                </span>
                                <span class="cb-label">En stock uniquement</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        {{-- ============ PRODUCT GRID ============ --}}
        <div class="flex-1 min-w-0">
            @if($products->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 rounded-3xl flex items-center justify-center mb-6" style="background:linear-gradient(145deg,#EBF0FA,#D0DFF2)">
                    <svg class="w-10 h-10 text-[#002352]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <h3 class="text-[20px] font-bold text-[#111827] mb-2">Aucun produit trouvé</h3>
                <p class="text-[14px] text-[#9CA3AF] max-w-[320px] mb-6">Essayez d'autres mots-clés ou réinitialisez les filtres.</p>
                <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-[13.5px] text-white transition-all hover:scale-105" style="background:#002352">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                    Voir tous les produits
                </a>
            </div>
            @else
            {{-- GRID --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="productsGrid">
                @foreach($products as $i => $product)
                @php
                    $img         = $product->images->first();
                    $totalStock  = $product->variants->sum('stock');
                    $isNew       = $product->created_at >= now()->subDays(30);
                    $isPromo     = $product->discount_price && $product->discount_price < $product->base_price;
                    $displayPrice= $isPromo ? $product->discount_price : $product->base_price;
                    $minPrice    = $product->variants->min('price') ?? $product->base_price;
                    $maxPrice    = $product->variants->max('price') ?? $product->base_price;
                    $hasMultiple = $product->variants->count() > 1 && $minPrice != $maxPrice;
                    $discount    = $isPromo ? round((1 - $product->discount_price / $product->base_price) * 100) : 0;
                    $delay       = ($i % 4) + 1;
                @endphp
                <a href="{{ route('product.show', $product->slug) }}" class="p-card group" data-sr data-sr-d="{{ $delay }}">
                    {{-- Image --}}
                    <div class="relative overflow-hidden" style="aspect-ratio:1/1; background:#F5F5F5">
                        @if($img)
                            <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover p-card-img" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center p-card-img">
                                <svg class="w-12 h-12 text-[#D1D5DB]" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Badges top-left --}}
                        <div class="absolute top-2.5 left-2.5 z-10 flex flex-col gap-1.5">
                            @if($isPromo)
                                <span class="badge-promo">-{{ $discount }}%</span>
                            @elseif($isNew)
                                <span class="badge-new">Nouveau</span>
                            @endif
                            @if($totalStock === 0)
                                <span class="badge-rupture">Rupture</span>
                            @endif
                        </div>

                        {{-- Overlay on hover --}}
                        <div class="p-card-overlay">
                            <span class="p-card-overlay-btn">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Voir le produit
                            </span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-4 flex-1 flex flex-col">
                        @if($product->brand)
                        <p class="text-[10.5px] font-bold uppercase tracking-widest text-[#9CA3AF] mb-1">{{ $product->brand }}</p>
                        @endif
                        <h3 class="text-[13.5px] font-semibold text-[#111827] line-clamp-2 leading-snug mb-2 flex-1">{{ $product->name }}</h3>

                        {{-- Price --}}
                        <div class="flex items-baseline gap-2 mt-auto">
                            @if($isPromo)
                                <span class="text-[15px] font-extrabold text-[#DC2626]">{{ number_format($product->discount_price, 0, ',', ' ') }} DA</span>
                                <span class="text-[12px] text-[#9CA3AF] line-through">{{ number_format($product->base_price, 0, ',', ' ') }} DA</span>
                            @elseif($hasMultiple)
                                <span class="text-[13px] font-semibold text-[#9CA3AF]">À partir de</span>
                                <span class="text-[15px] font-extrabold text-[#111827]">{{ number_format($minPrice, 0, ',', ' ') }} DA</span>
                            @else
                                <span class="text-[15px] font-extrabold text-[#111827]">{{ number_format($displayPrice, 0, ',', ' ') }} DA</span>
                            @endif
                        </div>

                        {{-- Stock indicator --}}
                        <div class="mt-2.5 flex items-center gap-1.5">
                            @if($totalStock === 0)
                                <span class="w-1.5 h-1.5 rounded-full bg-[#9CA3AF] flex-shrink-0"></span>
                                <span class="text-[11px] text-[#9CA3AF] font-medium">Rupture de stock</span>
                            @elseif($totalStock <= 5)
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                <span class="text-[11px] text-amber-600 font-medium">Plus que {{ $totalStock }}</span>
                            @else
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span class="text-[11px] text-emerald-600 font-medium">En stock</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            @if($products->hasPages())
            <div class="mt-10 flex items-center justify-center gap-2">
                {{-- Prev --}}
                @if($products->onFirstPage())
                    <span class="page-btn disabled" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="page-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </a>
                @endif

                {{-- Pages --}}
                @foreach($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                    <a href="{{ $url }}" class="page-btn px-3 {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                {{-- Next --}}
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="page-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                @else
                    <span class="page-btn disabled" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </span>
                @endif
            </div>
            <p class="text-center text-[12px] text-[#9CA3AF] mt-3">
                Affichage {{ $products->firstItem() }}–{{ $products->lastItem() }} sur {{ $products->total() }} produits
            </p>
            @endif
            @endif
        </div>
    </div>
</div>

{{-- MOBILE FILTER DRAWER --}}
<div id="filterDrawer" class="filter-drawer" style="display:none" aria-hidden="true">
    <div class="filter-drawer-backdrop" onclick="closeFilterDrawer()"></div>
    <div class="filter-drawer-panel">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[17px] font-bold text-[#111827]">Filtres
                @if($totalFilters > 0)<span class="ml-2 text-[11px] font-bold bg-[#002352] text-white px-2 py-0.5 rounded-full">{{ $totalFilters }}</span>@endif
            </h2>
            <button onclick="closeFilterDrawer()" class="p-2 rounded-xl hover:bg-[#F3F4F6] transition-colors">
                <svg class="w-5 h-5 text-[#374151]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Same filters as desktop, cloned by JS --}}
        <div id="drawerFiltersContent"></div>
        @if($totalFilters > 0)
        <a href="{{ route('catalogue', $currentQ ? ['q' => $currentQ] : []) }}" class="w-full flex items-center justify-center gap-2 mt-4 py-3 rounded-xl text-[13.5px] font-bold border-2 border-red-500 text-red-500 hover:bg-red-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Réinitialiser les filtres
        </a>
        @endif
    </div>
</div>

{{-- FOOTER --}}
@include('client.partials.catalogue-footer')

<script>
// ============================================================
// SCROLL REVEAL
// ============================================================
const observer = new IntersectionObserver((entries) => {
    entries.forEach(el => {
        if (el.isIntersecting) {
            el.target.classList.add('visible');
            observer.unobserve(el.target);
        }
    });
}, { threshold: 0.08 });
document.querySelectorAll('[data-sr]').forEach(el => observer.observe(el));

// ============================================================
// COLLAPSE SECTIONS
// ============================================================
function toggleSection(id) {
    const content = document.getElementById('content-' + id);
    const icon    = document.getElementById('icon-' + id);
    if (!content) return;
    const isOpen = !content.classList.contains('collapsed');
    if (isOpen) {
        content.classList.add('collapsed');
        icon && icon.style.setProperty('transform', 'rotate(-90deg)');
    } else {
        content.classList.remove('collapsed');
        icon && icon.style.removeProperty('transform');
    }
}

// ============================================================
// SEARCH with debounce
// ============================================================
let searchTimer;
function handleSearch(input) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500);
}
function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchForm').submit();
}

// ============================================================
// PRICE RANGE SYNC
// ============================================================
function syncRange() {
    const min = document.getElementById('minPriceInput').value;
    const max = document.getElementById('maxPriceInput').value;
    const rMin = document.getElementById('rangeMin');
    const rMax = document.getElementById('rangeMax');
    if (rMin) rMin.value = min;
    if (rMax) rMax.value = max;
}

// ============================================================
// MOBILE FILTER DRAWER
// ============================================================
function openFilterDrawer() {
    const drawer  = document.getElementById('filterDrawer');
    const content = document.getElementById('drawerFiltersContent');
    // Clone desktop sidebar content into drawer
    const sidebar = document.querySelector('aside form');
    if (sidebar && !content.children.length) {
        content.appendChild(sidebar.cloneNode(true));
    }
    drawer.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => drawer.setAttribute('aria-hidden', 'false'));
}
function closeFilterDrawer() {
    const drawer = document.getElementById('filterDrawer');
    drawer.style.display = 'none';
    document.body.style.overflow = '';
    drawer.setAttribute('aria-hidden', 'true');
}

// ============================================================
// SEARCH INPUT PLACEHOLDER COLOR FIX
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const inp = document.getElementById('searchInput');
    if (inp) {
        inp.style.caretColor = 'white';
    }
});
</script>
</body>
</html>
