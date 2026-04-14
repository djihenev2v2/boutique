@extends('layouts.admin')

@section('title', 'Produits')
@section('page-title', 'Produits')
@section('page-description', 'Gestion de votre catalogue')

@section('header-actions')
    <a href="{{ route('admin.products.create') }}"
       class="inline-flex items-center gap-2 bg-[#002352] text-white text-[13px] font-semibold px-5 py-2.5 rounded-full shadow-[0px_4px_14px_rgba(0,35,82,0.25)] hover:bg-[#18396e] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Ajouter un produit
    </a>
@endsection

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-[#747780] mb-4">
    <span>Gestion</span>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="text-[#002352]">Produits</span>
</nav>

<h1 class="text-[26px] font-bold text-[#002352] tracking-tight mb-6">Liste des Produits</h1>

{{-- Filters card --}}
<div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-5 mb-6">
    <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
        <div class="flex flex-wrap items-end gap-3">

            {{-- Catégorie --}}
            <div class="flex flex-col gap-1 min-w-[130px]">
                <label class="text-[10px] font-bold uppercase tracking-widest text-[#747780]">Catégorie</label>
                <select name="category" onchange="this.form.submit()"
                    class="text-[13px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#002352]/20 transition-all cursor-pointer">
                    <option value="">Toutes les cat.</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Statut --}}
            <div class="flex flex-col gap-1 min-w-[120px]">
                <label class="text-[10px] font-bold uppercase tracking-widest text-[#747780]">Statut</label>
                <select name="status" onchange="this.form.submit()"
                    class="text-[13px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#002352]/20 transition-all cursor-pointer">
                    <option value="">Tous les stati.</option>
                    <option value="actif" {{ request('status') === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('status') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>

            {{-- Stock --}}
            <div class="flex flex-col gap-1 min-w-[130px]">
                <label class="text-[10px] font-bold uppercase tracking-widest text-[#747780]">Stock</label>
                <select name="stock" onchange="this.form.submit()"
                    class="text-[13px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#002352]/20 transition-all cursor-pointer">
                    <option value="">Tous les nive.</option>
                    <option value="disponible" {{ request('stock') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="faible" {{ request('stock') === 'faible' ? 'selected' : '' }}>Faible (≤5)</option>
                    <option value="rupture" {{ request('stock') === 'rupture' ? 'selected' : '' }}>Rupture</option>
                </select>
            </div>

            {{-- Marque --}}
            <div class="flex flex-col gap-1 min-w-[130px]">
                <label class="text-[10px] font-bold uppercase tracking-widest text-[#747780]">Marque</label>
                <select name="brand" onchange="this.form.submit()"
                    class="text-[13px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#002352]/20 transition-all cursor-pointer">
                    <option value="">Toutes les m.</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Prix --}}
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-bold uppercase tracking-widest text-[#747780]">Gamme de prix (DA)</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0"
                        class="w-20 text-[13px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#002352]/20 transition-all"/>
                    <span class="text-[#747780] text-xs">—</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0"
                        class="w-20 text-[13px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#002352]/20 transition-all"/>
                </div>
            </div>

            {{-- Search --}}
            <div class="flex flex-col gap-1 flex-1 min-w-[160px]">
                <label class="text-[10px] font-bold uppercase tracking-widest text-[#747780]">Rechercher</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, marque..."
                        class="w-full pl-9 text-[13px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#002352]/20 transition-all"/>
                </div>
            </div>

            {{-- Apply & Reset --}}
            <button type="submit"
                class="px-4 py-2 bg-[#002352] text-white text-[12px] font-semibold rounded-xl hover:bg-[#18396e] transition-colors">
                Filtrer
            </button>
            @if(request()->hasAny(['search','category','status','stock','brand','min_price','max_price']))
            <a href="{{ route('admin.products.index') }}"
               class="px-4 py-2 bg-[#f2f4f6] text-[#5d5f5f] text-[12px] font-semibold rounded-xl hover:bg-[#e7e8ea] transition-colors">
                Réinitialiser
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Products table --}}
<div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-[#f2f4f6]">
                <th class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Produit</th>
                <th class="px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Catégorie</th>
                <th class="px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Prix de base</th>
                <th class="px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Stock total</th>
                <th class="px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Statut</th>
                <th class="px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f] text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#f2f4f6]">
            @forelse($products as $product)
            @php $totalStock = $product->variants->sum('stock'); @endphp
            <tr class="hover:bg-[#f8f9fb] transition-colors group">

                {{-- Product --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-[#f2f4f6] flex-shrink-0">
                            @if($product->images->isNotEmpty())
                                <img src="{{ Storage::url($product->images->first()->path) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13.5px] font-semibold text-[#191c1e] truncate max-w-[200px]">{{ $product->name }}</p>
                            @if($product->brand)
                            <p class="text-[11px] text-[#747780] mt-0.5">{{ $product->brand }}</p>
                            @endif
                            <p class="text-[10px] text-[#c4c6d1] font-mono mt-0.5">{{ $product->variants->count() }} variante(s)</p>
                        </div>
                    </div>
                </td>

                {{-- Category --}}
                <td class="px-4 py-4">
                    @if($product->category)
                        <span class="text-[12.5px] text-[#43474f]">{{ $product->category->name }}</span>
                    @else
                        <span class="text-[12px] text-[#c4c6d1]">—</span>
                    @endif
                </td>

                {{-- Price --}}
                <td class="px-4 py-4">
                    <span class="text-[13.5px] font-bold text-[#002352]">{{ number_format($product->base_price, 0, ',', ' ') }} DA</span>
                </td>

                {{-- Stock bar --}}
                <td class="px-4 py-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-16 h-1.5 rounded-full bg-[#edeef0] overflow-hidden">
                            @php
                                $maxStock = 200;
                                $pct = $totalStock > 0 ? min(100, ($totalStock / $maxStock) * 100) : 0;
                                $barColor = $totalStock === 0 ? 'bg-red-400' : ($totalStock <= 5 ? 'bg-amber-400' : 'bg-[#002352]');
                            @endphp
                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-[12.5px] font-semibold {{ $totalStock === 0 ? 'text-red-500' : ($totalStock <= 5 ? 'text-amber-600' : 'text-[#191c1e]') }}">{{ $totalStock }}</span>
                    </div>
                </td>

                {{-- Status --}}
                <td class="px-4 py-4">
                    <button
                        data-product-id="{{ $product->id }}"
                        data-active="{{ $product->is_active ? '1' : '0' }}"
                        data-toggle-url="{{ route('admin.products.toggle-active', $product) }}"
                        class="product-toggle inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition-all
                            {{ $product->is_active
                                ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                : 'bg-[#f2f4f6] text-[#747780] hover:bg-[#e7e8ea]' }}">
                        <span class="w-1.5 h-1.5 rounded-full inline-block {{ $product->is_active ? 'bg-emerald-500' : 'bg-[#c4c6d1]' }}"></span>
                        {{ $product->is_active ? 'Actif' : 'Inactif' }}
                    </button>
                </td>

                {{-- Actions --}}
                <td class="px-4 py-4">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="p-2 text-[#747780] hover:text-[#002352] hover:bg-[#f2f4f6] rounded-lg transition-all"
                           title="Modifier">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                              class="product-delete-form" data-name="{{ $product->name }}">
                            @csrf @method('DELETE')
                            <button type="button"
                                    class="product-delete-btn p-2 text-[#747780] hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                    title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 rounded-2xl bg-[#f2f4f6] flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                            </svg>
                        </div>
                        <p class="text-[13.5px] font-semibold text-[#43474f]">Aucun produit trouvé</p>
                        <p class="text-[12px] text-[#747780] mt-1">Ajoutez votre premier produit pour commencer</p>
                        <a href="{{ route('admin.products.create') }}"
                           class="mt-4 inline-flex items-center gap-2 bg-[#002352] text-white text-[12px] font-semibold px-4 py-2 rounded-full hover:bg-[#18396e] transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Ajouter un produit
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="flex items-center justify-between px-6 py-4 border-t border-[#f2f4f6]">
        <p class="text-[12px] text-[#5d5f5f]">
            Affichage de <span class="font-semibold text-[#191c1e]">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span>
            sur <span class="font-semibold text-[#191c1e]">{{ $products->total() }}</span> produits
        </p>
        <div class="flex items-center gap-1">
            {{-- Prev --}}
            @if($products->onFirstPage())
                <span class="px-3 py-1.5 text-[12px] text-[#c4c6d1] rounded-lg cursor-not-allowed">← Précédent</span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1.5 text-[12px] text-[#5d5f5f] hover:bg-[#f2f4f6] rounded-lg transition-colors">← Précédent</a>
            @endif

            {{-- Pages --}}
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center text-[12px] font-bold text-white bg-[#002352] rounded-full">{{ $page }}</span>
                @elseif(abs($page - $products->currentPage()) <= 2 || $page == 1 || $page == $products->lastPage())
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-[12px] text-[#5d5f5f] hover:bg-[#f2f4f6] rounded-full transition-colors">{{ $page }}</a>
                @elseif(abs($page - $products->currentPage()) == 3)
                    <span class="px-1 text-[#747780] text-[12px]">…</span>
                @endif
            @endforeach

            {{-- Next --}}
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1.5 text-[12px] text-[#5d5f5f] hover:bg-[#f2f4f6] rounded-lg transition-colors">Suivant →</a>
            @else
                <span class="px-3 py-1.5 text-[12px] text-[#c4c6d1] rounded-lg cursor-not-allowed">Suivant →</span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- Confirm delete modal --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#191c1e]/40 backdrop-blur-sm" id="deleteModalBackdrop"></div>
    <div class="relative bg-white rounded-2xl shadow-[0px_20px_60px_rgba(0,35,82,0.15)] p-6 w-full max-w-sm mx-4">
        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
            </svg>
        </div>
        <h3 class="text-[16px] font-bold text-[#191c1e] mb-1">Supprimer le produit</h3>
        <p class="text-[13px] text-[#5d5f5f] mb-5">Voulez-vous vraiment supprimer <strong id="deleteProductName" class="text-[#191c1e]"></strong> ? Cette action est irréversible.</p>
        <div class="flex gap-3">
            <button id="deleteCancelBtn" class="flex-1 px-4 py-2.5 bg-[#f2f4f6] text-[#43474f] text-[13px] font-semibold rounded-xl hover:bg-[#e7e8ea] transition-colors">
                Annuler
            </button>
            <button id="deleteConfirmBtn" class="flex-1 px-4 py-2.5 bg-red-500 text-white text-[13px] font-semibold rounded-xl hover:bg-red-600 transition-colors">
                Supprimer
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module" src="{{ asset('js/modules/products.js') }}"></script>
@endpush
