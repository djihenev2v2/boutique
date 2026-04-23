@php
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\Setting;

    $footerShopName = $shopName ?? Setting::get('shop_name', config('app.name', 'Boutique'));
    $footerLogoPath = $logoPath ?? Setting::get('shop_logo');
    $footerShopPhone = $shopPhone ?? Setting::get('shop_phone');

    $footerCategories = isset($categories)
        ? $categories->take(5)
        : Category::whereHas('products', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->limit(5)
            ->get();

    $footerHasPromo = isset($promoProducts)
        ? $promoProducts->count() > 0
        : Product::where('is_active', true)
            ->whereNotNull('discount_price')
            ->whereColumn('discount_price', '<', 'base_price')
            ->exists();
@endphp

<footer style="background:#F8F7F4; border-top:1px solid #E8E4DC;">
    <div class="max-w-[1400px] mx-auto px-5 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr_1fr_1fr] gap-10 py-14" style="border-bottom:1px solid #E8E4DC">

            <div>
                <div class="flex items-center gap-3 mb-5">
                    @include('partials.shop-logo', [
                        'logoPath' => $footerLogoPath,
                        'shopName' => $footerShopName,
                        'containerClass' => 'w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center',
                        'imageClass' => 'w-full h-full object-contain p-1',
                        'iconClass' => 'w-4 h-4 text-white',
                    ])
                    <span class="font-extrabold text-[17px] tracking-tight text-[#0D1B2A]">{{ $footerShopName }}</span>
                </div>
                <p class="text-[13.5px] leading-[1.7] mb-6 text-[#6B7280]" style="max-width:260px">Boutique algérienne en ligne. Livraison à domicile dans toutes les wilayas.</p>
                @if($footerShopPhone)
                <a href="tel:{{ $footerShopPhone }}" class="inline-flex items-center gap-2.5 text-[13px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    {{ $footerShopPhone }}
                </a>
                @endif
            </div>

            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.16em] mb-6 text-[#9CA3AF]">Boutique</p>
                <ul class="space-y-3.5">
                    <li><a href="/" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Accueil</a></li>
                    <li><a href="{{ route('catalogue') }}" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Catalogue</a></li>
                    @if($footerHasPromo)
                    <li><a href="{{ route('catalogue', ['promo' => 1]) }}" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Promotions</a></li>
                    @endif
                    <li><a href="{{ route('cart.index') }}" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Mon panier</a></li>
                    <li><a href="{{ route('order.tracking') }}" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">Suivi commande</a></li>
                </ul>
            </div>

            @if($footerCategories->count())
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.16em] mb-6 text-[#9CA3AF]">Catégories</p>
                <ul class="space-y-3.5">
                    @foreach($footerCategories as $cat)
                    <li><a href="{{ route('catalogue', ['categorie' => $cat->id]) }}" class="text-[13.5px] font-medium text-[#6B7280] hover:text-[#002352] transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif

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

        <div class="py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[12px] text-[#9CA3AF]">&copy; {{ date('Y') }} {{ $footerShopName }}. Tous droits réservés.</p>
            <p class="text-[12px] text-[#9CA3AF]">Algérie &mdash; Paiement à la livraison</p>
        </div>

    </div>
</footer>
