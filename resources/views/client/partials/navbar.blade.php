@php
    $shopName = $shopName ?? config('app.name', 'Boutique');
    $logoPath = $logoPath ?? null;
    $cartCount = $cartCount ?? 0;

    $isHome = request()->routeIs('landing');
    $isCatalogue = request()->routeIs('catalogue') || request()->routeIs('product.show');
    $isTracking = request()->routeIs('order.tracking') || request()->routeIs('order.tracking.search');
    $isPromo = request()->routeIs('client.promo');
    $isCgv = request()->routeIs('cgv');

    $desktopLinkBase = 'px-4 py-2 text-[13.5px] font-medium text-[#6B7280] hover:text-[#0D1B2A] hover:bg-[#F3F4F6] rounded-full transition-colors';
    $desktopLinkActive = 'px-4 py-2 text-[13.5px] font-semibold text-[#0D1B2A] bg-[#F3F4F6] rounded-full';
@endphp

<header class="sticky top-0 z-50 bg-white/95 border-b border-[#EBEBEB]" style="backdrop-filter:blur(20px)">
    <div class="max-w-[1320px] mx-auto px-5 lg:px-8 h-[64px] flex items-center justify-between gap-4">
        <a href="{{ route('landing') }}" class="flex items-center gap-2.5 flex-shrink-0">
            @include('partials.shop-logo', [
                'logoPath' => $logoPath,
                'shopName' => $shopName,
                'containerClass' => 'w-8 h-8 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center',
                'imageClass' => 'w-full h-full object-contain p-1',
                'iconClass' => 'w-4 h-4 text-white',
            ])
            <span class="font-bold text-[15px] tracking-tight text-[#0D1B2A]">{{ $shopName }}</span>
        </a>

        <nav class="hidden md:flex items-center gap-0.5">
            <a href="{{ route('landing') }}" class="{{ $isHome ? $desktopLinkActive : $desktopLinkBase }}">Accueil</a>
            <a href="{{ route('catalogue') }}" class="{{ $isCatalogue ? $desktopLinkActive : $desktopLinkBase }}">Catalogue</a>
            <a href="{{ route('order.tracking') }}" class="{{ $isTracking ? $desktopLinkActive : $desktopLinkBase }}">Suivi commande</a>
            <a href="{{ route('client.promo') }}" class="{{ $isPromo ? $desktopLinkActive : $desktopLinkBase }}">Code promo</a>
            <a href="{{ route('cgv') }}" class="{{ $isCgv ? $desktopLinkActive : $desktopLinkBase }}">CGV</a>
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('cart.index') }}" class="relative hidden sm:inline-flex items-center gap-2 h-[38px] px-5 text-white text-[13px] font-semibold rounded-full transition-all hover:scale-[1.03]" style="background:#002352">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                Panier
                @if($cartCount > 0)
                    <span class="bg-white text-[#002352] text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </a>

            <a href="{{ route('cart.index') }}" class="sm:hidden relative flex items-center justify-center w-9 h-9 rounded-full hover:bg-[#F3F4F6] transition-colors">
                <svg class="w-5 h-5 text-[#374151]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 bg-[#002352] text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </a>

            <button class="md:hidden flex items-center justify-center w-9 h-9 rounded-full hover:bg-[#F3F4F6] transition-colors" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                <svg class="w-5 h-5 text-[#374151]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden border-t border-[#EBEBEB] px-5 py-4 space-y-1 bg-white">
        <a href="{{ route('landing') }}" class="block px-4 py-2.5 text-[14px] {{ $isHome ? 'font-semibold text-[#0D1B2A] bg-[#F3F4F6]' : 'font-medium text-[#6B7280] hover:bg-[#F3F4F6]' }} rounded-xl transition-colors">Accueil</a>
        <a href="{{ route('catalogue') }}" class="block px-4 py-2.5 text-[14px] {{ $isCatalogue ? 'font-semibold text-[#0D1B2A] bg-[#F3F4F6]' : 'font-medium text-[#6B7280] hover:bg-[#F3F4F6]' }} rounded-xl transition-colors">Catalogue</a>
        <a href="{{ route('order.tracking') }}" class="block px-4 py-2.5 text-[14px] {{ $isTracking ? 'font-semibold text-[#0D1B2A] bg-[#F3F4F6]' : 'font-medium text-[#6B7280] hover:bg-[#F3F4F6]' }} rounded-xl transition-colors">Suivi commande</a>
        <a href="{{ route('client.promo') }}" class="block px-4 py-2.5 text-[14px] {{ $isPromo ? 'font-semibold text-[#0D1B2A] bg-[#F3F4F6]' : 'font-medium text-[#6B7280] hover:bg-[#F3F4F6]' }} rounded-xl transition-colors">Code promo</a>
        <a href="{{ route('cgv') }}" class="block px-4 py-2.5 text-[14px] {{ $isCgv ? 'font-semibold text-[#0D1B2A] bg-[#F3F4F6]' : 'font-medium text-[#6B7280] hover:bg-[#F3F4F6]' }} rounded-xl transition-colors">CGV</a>
    </div>
</header>
