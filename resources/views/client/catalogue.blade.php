@extends('layouts.app')

@section('title', 'Catalogue')
@section('page-title', 'Catalogue')
@section('page-description', 'Tous nos produits')

@section('content')

<div class="flex gap-6">

    {{-- ── Sidebar filtres (desktop) ─────────────────────────── --}}
    <aside class="hidden lg:flex flex-col gap-5 w-56 flex-shrink-0">

        {{-- Catégories --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-5">
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-[#747780] mb-3">Catégories</h3>
            <div class="space-y-1.5">
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" form="filterForm" name="categories[]" value="all"
                           class="w-4 h-4 rounded accent-[#002352] cursor-pointer"
                           onchange="clearCategories(this)"
                           {{ !request('categories') ? 'checked' : '' }}>
                    <span class="text-[13px] text-[#002352] font-medium group-hover:text-[#18396e]">Toutes</span>
                </label>
                @foreach($categories as $cat)
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" form="filterForm" name="categories[]" value="{{ $cat->id }}"
                           class="w-4 h-4 rounded accent-[#002352] cursor-pointer cat-checkbox"
                           {{ in_array($cat->id, (array) request('categories', [])) ? 'checked' : '' }}>
                    <span class="text-[13px] text-[#5d5f5f] group-hover:text-[#18396e]">{{ $cat->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Prix --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-5">
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-[#747780] mb-3">Prix (DA)</h3>
            <div class="flex gap-2 items-center">
                <input type="number" form="filterForm" name="price_min" min="0"
                       value="{{ request('price_min') }}" placeholder="Min"
                       class="w-full bg-[#f2f4f6] text-[12px] text-[#002352] rounded-xl px-3 py-2 border-none outline-none placeholder-[#9ca3af]">
                <span class="text-[#9ca3af] text-[11px]">—</span>
                <input type="number" form="filterForm" name="price_max" min="0"
                       value="{{ request('price_max') }}" placeholder="Max"
                       class="w-full bg-[#f2f4f6] text-[12px] text-[#002352] rounded-xl px-3 py-2 border-none outline-none placeholder-[#9ca3af]">
            </div>
        </div>

        {{-- Attributs (Taille, Couleur, Pointure…) --}}
        @foreach($attributes as $attr)
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-5">
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-[#747780] mb-3">{{ $attr->name }}</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($attr->values as $val)
                <label class="cursor-pointer">
                    <input type="checkbox" form="filterForm" name="attribute_values[]" value="{{ $val->id }}"
                           class="sr-only peer"
                           {{ in_array($val->id, (array) request('attribute_values', [])) ? 'checked' : '' }}>
                    <span class="inline-block px-3 py-1.5 rounded-lg text-[12px] font-medium border border-[#edeef0] text-[#5d5f5f]
                                 peer-checked:bg-[#002352] peer-checked:text-white peer-checked:border-[#002352]
                                 hover:border-[#002352] transition-all cursor-pointer select-none">
                        {{ $val->value }}
                    </span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Disponibilité --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-5">
            <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" form="filterForm" name="in_stock" value="1"
                       class="w-4 h-4 rounded accent-[#002352]"
                       {{ request('in_stock') ? 'checked' : '' }}>
                <span class="text-[13px] text-[#5d5f5f] group-hover:text-[#18396e]">En stock uniquement</span>
            </label>
        </div>

        <button form="filterForm" type="submit"
                class="w-full bg-[#002352] text-white text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
            Appliquer les filtres
        </button>

        @if(request()->except(['page']))
        <a href="{{ route('catalogue') }}"
           class="w-full text-center text-[12px] text-[#747780] hover:text-[#002352] font-medium py-2 rounded-xl hover:bg-[#f2f4f6] transition-colors block">
            Réinitialiser les filtres
        </a>
        @endif
    </aside>

    {{-- ── Zone principale ────────────────────────────────────── --}}
    <div class="flex-1 min-w-0">

        {{-- Barre du haut : recherche + tri + résultats --}}
        <form id="filterForm" method="GET" action="{{ route('catalogue') }}">
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher un produit..."
                       class="w-full pl-9 pr-4 py-2.5 bg-white rounded-xl text-[13px] text-[#002352] shadow-[0px_4px_20px_rgba(24,57,110,0.06)] border-none outline-none placeholder-[#9ca3af] focus:ring-2 focus:ring-[#002352]/20">
            </div>

            <select name="sort" onchange="this.form.submit()"
                    class="bg-white text-[13px] text-[#002352] rounded-xl px-4 py-2.5 shadow-[0px_4px_20px_rgba(24,57,110,0.06)] border-none outline-none cursor-pointer">
                <option value="newest"    {{ request('sort','newest')==='newest'    ? 'selected' : '' }}>Plus récents</option>
                <option value="price_asc" {{ request('sort')==='price_asc'          ? 'selected' : '' }}>Prix croissant</option>
                <option value="price_desc"{{ request('sort')==='price_desc'         ? 'selected' : '' }}>Prix décroissant</option>
                <option value="name_asc"  {{ request('sort')==='name_asc'           ? 'selected' : '' }}>Nom A → Z</option>
            </select>

            <button type="submit"
                    class="bg-[#002352] text-white text-[12px] font-semibold px-5 py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm whitespace-nowrap">
                Rechercher
            </button>

            <span class="text-[12px] text-[#747780] whitespace-nowrap ml-auto">
                {{ $products->total() }} produit(s)
            </span>
        </div>
        </form>

        {{-- Grille produits --}}
        @if($products->isEmpty())
        <div class="flex flex-col items-center gap-4 py-20 bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)]">
            <svg class="w-12 h-12 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <p class="text-[14px] font-semibold text-[#5d5f5f]">Aucun produit trouvé</p>
            <a href="{{ route('catalogue') }}" class="text-[12px] text-[#18396e] font-medium hover:underline">Voir tous les produits</a>
        </div>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($products as $product)
            @php
                $totalStock = $product->variants->sum('stock');
                $minPrice   = $product->variants->min('price') ?? $product->base_price;
                $isNew      = $product->created_at->diffInDays(now()) <= 7;
            @endphp
            <a href="{{ route('product.show', $product->slug) }}"
               class="group bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.04)] hover:shadow-[0px_8px_30px_rgba(24,57,110,0.12)] transition-all duration-200 overflow-hidden flex flex-col">

                {{-- Image --}}
                <div class="relative aspect-square bg-[#f8f9fb] overflow-hidden">
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

                    {{-- Badges --}}
                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                        @if($isNew)
                        <span class="bg-[#002352] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Nouveau</span>
                        @endif
                        @if($totalStock === 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Rupture</span>
                        @endif
                    </div>

                    {{-- Bouton favori --}}
                    @php $isFav = in_array($product->id, $favoriteIds); @endphp
                    <form method="POST" action="{{ route('favoris.toggle') }}" class="absolute top-2 right-2" onclick="event.stopPropagation(); event.preventDefault(); this.submit();">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                                class="w-7 h-7 rounded-full flex items-center justify-center transition-all duration-150 shadow-sm {{ $isFav ? 'bg-red-500 text-white' : 'bg-white/90 text-[#c4c6d1] hover:text-red-500' }}"
                                title="{{ $isFav ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                            <svg class="w-3.5 h-3.5" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.239-4.5-5-4.5-1.876 0-3.51.93-4.337 2.306a5.84 5.84 0 00-.326.6 5.84 5.84 0 00-.326-.6C10.51 4.68 8.876 3.75 7 3.75c-2.761 0-5 2.015-5 4.5 0 7.22 9.337 12 9.337 12S21 15.47 21 8.25z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Info --}}
                <div class="p-3 flex-1 flex flex-col gap-1">
                    @if($product->category)
                    <p class="text-[10px] text-[#747780] font-medium uppercase tracking-wide">{{ $product->category->name }}</p>
                    @endif
                    <p class="text-[13px] font-semibold text-[#002352] leading-tight line-clamp-2 group-hover:text-[#18396e]">{{ $product->name }}</p>
                    <p class="text-[13px] font-bold text-[#002352] mt-auto pt-2">
                        À partir de {{ number_format($minPrice, 0, ',', ' ') }} DA
                    </p>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="mt-6 flex items-center justify-center gap-1.5">
            {{-- Précédent --}}
            @if($products->onFirstPage())
            <span class="px-3 py-2 rounded-xl text-[12px] text-[#c4c6d1] bg-white shadow-sm cursor-not-allowed">← Préc.</span>
            @else
            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-2 rounded-xl text-[12px] text-[#5d5f5f] bg-white shadow-sm hover:bg-[#002352] hover:text-white transition-all">← Préc.</a>
            @endif

            {{-- Pages --}}
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            <a href="{{ $url }}"
               class="w-8 h-8 flex items-center justify-center rounded-xl text-[12px] font-semibold transition-all
                      {{ $page == $products->currentPage() ? 'bg-[#002352] text-white shadow-sm' : 'bg-white text-[#5d5f5f] shadow-sm hover:bg-[#f2f4f6]' }}">
                {{ $page }}
            </a>
            @endforeach

            {{-- Suivant --}}
            @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 rounded-xl text-[12px] text-[#5d5f5f] bg-white shadow-sm hover:bg-[#002352] hover:text-white transition-all">Suiv. →</a>
            @else
            <span class="px-3 py-2 rounded-xl text-[12px] text-[#c4c6d1] bg-white shadow-sm cursor-not-allowed">Suiv. →</span>
            @endif
        </div>
        @endif
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
function clearCategories(allCheckbox) {
    if (allCheckbox.checked) {
        document.querySelectorAll('.cat-checkbox').forEach(cb => cb.checked = false);
    }
}
document.querySelectorAll('.cat-checkbox').forEach(cb => {
    cb.addEventListener('change', () => {
        document.querySelector('[value="all"]').checked = false;
    });
});
</script>
@endpush
