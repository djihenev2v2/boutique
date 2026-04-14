@extends('layouts.admin')

@section('title', 'Commande ' . $order->order_number)
@section('page-title', 'Commande ' . $order->order_number)
@section('page-description', 'Créée le ' . $order->created_at?->format('d/m/Y à H:i'))

@php
$statusConfig = \App\Models\Order::STATUSES;
$statusKeys   = ['pending','confirmed','shipped','delivered','cancelled'];
@endphp

@section('header-actions')
    <a href="{{ route('admin.orders.index') }}"
       class="inline-flex items-center gap-2 bg-white border border-[#c4c6d1]/40 text-[#5d5f5f] text-[12px] font-semibold px-4 py-2 rounded-full shadow-sm hover:shadow transition-all">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Retour aux commandes
    </a>
    <button onclick="window.print()"
            class="inline-flex items-center gap-2 bg-white border border-[#c4c6d1]/40 text-[#5d5f5f] text-[12px] font-semibold px-4 py-2 rounded-full shadow-sm hover:shadow transition-all print:hidden">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
        </svg>
        Imprimer
    </button>
@endsection

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ── Colonne principale (2/3) ────────────────────────────── --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Section 1 — En-tête statut --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-[20px] font-bold text-[#002352]">{{ $order->order_number }}</h2>
                        @php $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'color' => 'slate']; @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold
                            @if($order->status==='pending')   bg-amber-50 text-amber-700
                            @elseif($order->status==='confirmed') bg-blue-50 text-blue-700
                            @elseif($order->status==='shipped')  bg-violet-50 text-violet-700
                            @elseif($order->status==='delivered') bg-emerald-50 text-emerald-700
                            @else bg-red-50 text-red-700
                            @endif">
                            <span class="w-2 h-2 rounded-full
                                @if($order->status==='pending')   bg-amber-400
                                @elseif($order->status==='confirmed') bg-blue-500
                                @elseif($order->status==='shipped')  bg-violet-500
                                @elseif($order->status==='delivered') bg-emerald-500
                                @else bg-red-500
                                @endif"></span>
                            {{ $sc['label'] }}
                        </span>
                    </div>
                    <p class="text-[12px] text-[#747780]">
                        Créée le {{ $order->created_at?->format('d/m/Y à H:i') }}
                        · {{ $order->payment_method_label }}
                    </p>
                </div>

                {{-- Changement de statut --}}
                @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="flex items-center gap-2">
                    @csrf @method('PATCH')
                    <select name="status"
                            class="text-[13px] text-[#002352] bg-[#f2f4f6] border-none rounded-xl px-3 py-2 outline-none cursor-pointer">
                        @foreach($statusConfig as $sk => $scfg)
                        <option value="{{ $sk }}" {{ $order->status === $sk ? 'selected' : '' }}>
                            {{ $scfg['label'] }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="bg-[#002352] text-white text-[12px] font-semibold px-4 py-2 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                        Confirmer
                    </button>
                </form>
                @endif
            </div>

            {{-- Barre de progression --}}
            <div class="mt-5 flex items-center gap-0">
                @foreach($statusKeys as $i => $sk)
                    @if($sk === 'cancelled') @continue @endif
                    @php
                        $sIdx = array_search($order->status, array_filter($statusKeys, fn($s) => $s !== 'cancelled'));
                        $cIdx = array_search($sk, array_filter($statusKeys, fn($s) => $s !== 'cancelled'));
                        $done = !($order->status === 'cancelled') && $cIdx <= $sIdx;
                    @endphp
                    <div class="flex-1 flex items-center">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-bold
                                        {{ $done ? 'bg-[#002352] text-white' : 'bg-[#edeef0] text-[#9ca3af]' }}">
                                {{ $cIdx + 1 }}
                            </div>
                            <span class="text-[9px] font-medium mt-1 {{ $done ? 'text-[#002352]' : 'text-[#9ca3af]' }}">
                                {{ $statusConfig[$sk]['label'] }}
                            </span>
                        </div>
                        @if(!$loop->last || true)
                        <div class="flex-1 h-0.5 {{ $done && $cIdx < $sIdx ? 'bg-[#002352]' : 'bg-[#edeef0]' }} mb-4"></div>
                        @endif
                    </div>
                    @if($loop->last)
                    <div class="flex flex-col items-center">
                        @php $deliveredDone = $order->status === 'delivered'; @endphp
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-bold
                                    {{ $deliveredDone ? 'bg-emerald-600 text-white' : 'bg-[#edeef0] text-[#9ca3af]' }}">
                            4
                        </div>
                        <span class="text-[9px] font-medium mt-1 {{ $deliveredDone ? 'text-emerald-600' : 'text-[#9ca3af]' }}">Livrée</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Section 3 — Produits commandés --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#f2f4f6]">
                <h3 class="text-[15px] font-bold text-[#002352]">Produits commandés</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#f8f9fb]">
                            <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Produit</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">SKU</th>
                            <th class="text-right px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Prix unit.</th>
                            <th class="text-center px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Qté</th>
                            <th class="text-right px-5 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f2f4f6]">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Image miniature --}}
                                    @php
                                        $img = $item->variant?->product?->images->first();
                                    @endphp
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-[#f2f4f6] flex-shrink-0">
                                        @if($img)
                                            <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-semibold text-[#002352]">{{ $item->product_name }}</p>
                                        @if($item->variant_label)
                                        <p class="text-[11px] text-[#747780]">{{ $item->variant_label }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-mono text-[11px] text-[#747780] bg-[#f2f4f6] px-2 py-0.5 rounded">{{ $item->sku ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-[13px] text-[#5d5f5f]">{{ number_format($item->unit_price, 0, ',', ' ') }} DA</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="w-7 h-7 inline-flex items-center justify-center bg-[#f2f4f6] rounded-lg text-[13px] font-semibold text-[#002352]">{{ $item->quantity }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="text-[13px] font-semibold text-[#002352]">{{ number_format($item->subtotal, 0, ',', ' ') }} DA</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Section 4 — Récapitulatif financier --}}
            <div class="px-6 py-5 border-t border-[#f2f4f6] bg-[#f8f9fb]">
                <div class="max-w-xs ml-auto space-y-2">
                    <div class="flex justify-between text-[13px] text-[#5d5f5f]">
                        <span>Sous-total produits</span>
                        <span class="font-medium text-[#002352]">{{ number_format($order->subtotal, 0, ',', ' ') }} DA</span>
                    </div>
                    <div class="flex justify-between text-[13px] text-[#5d5f5f]">
                        <span>Frais de livraison</span>
                        <span class="font-medium text-[#002352]">{{ number_format($order->shipping_cost, 0, ',', ' ') }} DA</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-[13px] text-emerald-600">
                        <span>
                            Code promo
                            @if($order->promoCode)
                                <span class="font-mono text-[10px] bg-emerald-100 px-1.5 py-0.5 rounded">{{ $order->promoCode->code }}</span>
                            @endif
                        </span>
                        <span class="font-medium">−{{ number_format($order->discount, 0, ',', ' ') }} DA</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-[#edeef0]">
                        <span class="text-[15px] font-bold text-[#002352]">Total</span>
                        <span class="text-[18px] font-bold text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 5 — Livraison --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-[15px] font-bold text-[#002352]">Informations de livraison</h3>
                @if($order->shipment)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold
                    @if($order->shipment->status==='pending')  bg-amber-50 text-amber-700
                    @elseif($order->shipment->status==='shipped') bg-violet-50 text-violet-700
                    @else bg-emerald-50 text-emerald-700 @endif">
                    @if($order->shipment->status==='pending') En attente
                    @elseif($order->shipment->status==='shipped') Expédiée
                    @else Livrée @endif
                </span>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.orders.updateShipment', $order) }}" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Transporteur</label>
                        <select name="carrier" class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-3 py-2.5 border-none outline-none">
                            <option value="">— Choisir —</option>
                            @foreach(['Yalidine','ZR Express','Guepex','Ecotrack','Procolis','Autre'] as $carrier)
                            <option value="{{ $carrier }}" {{ ($order->shipment?->carrier ?? '') === $carrier ? 'selected' : '' }}>{{ $carrier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Numéro de tracking</label>
                        <input type="text" name="tracking_number"
                               value="{{ $order->shipment?->tracking_number }}"
                               placeholder="Ex: YAL-123456789"
                               class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-3 py-2.5 border-none outline-none placeholder-[#9ca3af]">
                    </div>
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#002352] text-white text-[12px] font-semibold px-5 py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Enregistrer la livraison
                </button>
            </form>
        </div>

        {{-- Section 6 — Notes internes --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6">
            <h3 class="text-[15px] font-bold text-[#002352] mb-4">Notes internes</h3>
            <form method="POST" action="{{ route('admin.orders.updateNote', $order) }}" class="space-y-3">
                @csrf @method('PATCH')
                <textarea name="notes" rows="4"
                          placeholder="Ajouter une note interne sur cette commande..."
                          class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-3 border-none outline-none resize-none placeholder-[#9ca3af] focus:ring-2 focus:ring-[#002352]/20">{{ $order->notes }}</textarea>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#002352] text-white text-[12px] font-semibold px-5 py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Sauvegarder la note
                </button>
            </form>
        </div>
    </div>

    {{-- ── Colonne latérale (1/3) ──────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Section 2 — Informations client --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6">
            <h3 class="text-[15px] font-bold text-[#002352] mb-4">Informations client</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#f2f4f6] flex items-center justify-center flex-shrink-0">
                        <svg class="w-4.5 h-4.5 w-5 h-5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#747780] font-semibold uppercase tracking-widest">Nom</p>
                        <p class="text-[13px] font-semibold text-[#002352]">{{ $order->customer_name }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#f2f4f6] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#747780] font-semibold uppercase tracking-widest">Téléphone</p>
                        <a href="tel:{{ $order->customer_phone }}" class="text-[13px] font-semibold text-[#18396e] hover:underline">{{ $order->customer_phone }}</a>
                    </div>
                </div>

                @if($order->customer_email)
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#f2f4f6] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#747780] font-semibold uppercase tracking-widest">Email</p>
                        <a href="mailto:{{ $order->customer_email }}" class="text-[13px] font-semibold text-[#18396e] hover:underline">{{ $order->customer_email }}</a>
                    </div>
                </div>
                @endif

                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#f2f4f6] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#747780] font-semibold uppercase tracking-widest">Wilaya</p>
                        <p class="text-[13px] font-semibold text-[#002352]">{{ $order->wilaya->name ?? '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#f2f4f6] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75V19.5a1.5 1.5 0 001.5 1.5h4.5v-5.25a1.5 1.5 0 011.5-1.5h0a1.5 1.5 0 011.5 1.5V21H18a1.5 1.5 0 001.5-1.5V9.75"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#747780] font-semibold uppercase tracking-widest">Adresse</p>
                        <p class="text-[13px] font-semibold text-[#002352]">{{ $order->address }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 7 — Historique des statuts --}}
        <div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6">
            <h3 class="text-[15px] font-bold text-[#002352] mb-4">Historique</h3>

            @if($order->statusHistory->isEmpty())
            <p class="text-[12px] text-[#747780] text-center py-3">Aucun changement de statut enregistré.</p>
            @else
            <div class="space-y-4">
                {{-- Création --}}
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-[#002352] flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </div>
                        <div class="flex-1 w-px bg-[#edeef0] mt-1"></div>
                    </div>
                    <div class="pb-4">
                        <p class="text-[12px] font-semibold text-[#002352]">Commande créée</p>
                        <p class="text-[11px] text-[#747780]">{{ $order->created_at?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @foreach($order->statusHistory as $i => $hist)
                @php $hsc = $statusConfig[$hist->to_status] ?? ['label' => $hist->to_status, 'color' => 'slate']; @endphp
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0
                            @if($hist->to_status==='pending')   bg-amber-100
                            @elseif($hist->to_status==='confirmed') bg-blue-100
                            @elseif($hist->to_status==='shipped')  bg-violet-100
                            @elseif($hist->to_status==='delivered') bg-emerald-100
                            @else bg-red-100 @endif">
                            <span class="w-2 h-2 rounded-full
                                @if($hist->to_status==='pending')   bg-amber-500
                                @elseif($hist->to_status==='confirmed') bg-blue-500
                                @elseif($hist->to_status==='shipped')  bg-violet-500
                                @elseif($hist->to_status==='delivered') bg-emerald-500
                                @else bg-red-500 @endif"></span>
                        </div>
                        @unless($loop->last)
                        <div class="flex-1 w-px bg-[#edeef0] mt-1"></div>
                        @endunless
                    </div>
                    <div class="pb-4">
                        <p class="text-[12px] font-semibold text-[#002352]">
                            {{ $statusConfig[$hist->from_status]['label'] ?? '—' }}
                            → {{ $hsc['label'] }}
                        </p>
                        <p class="text-[11px] text-[#747780]">{{ $hist->changed_at->format('d/m/Y H:i') }}</p>
                        @if($hist->note)
                        <p class="text-[11px] text-[#5d5f5f] italic mt-0.5">{{ $hist->note }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Annuler la commande --}}
        @if(!in_array($order->status, ['cancelled','delivered']))
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <h4 class="text-[13px] font-bold text-red-700 mb-2">Zone de danger</h4>
            <p class="text-[12px] text-red-600 mb-3">Annuler restituera le stock des variantes commandées.</p>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler la commande {{ $order->order_number }} ? Le stock sera restauré.')">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white text-[12px] font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    Annuler la commande
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

@endsection
