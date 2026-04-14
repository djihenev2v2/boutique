@extends('layouts.app')

@section('title', 'Suivi — ' . $order->order_number)
@section('page-title', 'Suivi de commande')
@section('page-description', $order->order_number)

@php
    $steps = [
        'pending'   => ['label' => 'Commande reçue',   'desc' => 'Votre commande a été enregistrée'],
        'confirmed' => ['label' => 'Confirmée',         'desc' => 'Votre commande est confirmée'],
        'shipped'   => ['label' => 'En livraison',      'desc' => 'Votre colis est en route'],
        'delivered' => ['label' => 'Livrée',            'desc' => 'Votre commande a été livrée'],
    ];
    $stepOrder  = array_keys($steps);
    $currentIdx = array_search($order->status, $stepOrder);
    $isCancelled = $order->status === 'cancelled';

    $statusColors = [
        'pending'   => ['badge' => 'bg-amber-50 text-amber-700 border-amber-200',   'dot' => 'bg-amber-500'],
        'confirmed' => ['badge' => 'bg-blue-50 text-blue-700 border-blue-200',       'dot' => 'bg-blue-500'],
        'shipped'   => ['badge' => 'bg-violet-50 text-violet-700 border-violet-200', 'dot' => 'bg-violet-500'],
        'delivered' => ['badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200','dot' => 'bg-emerald-500'],
        'cancelled' => ['badge' => 'bg-red-50 text-red-600 border-red-200',          'dot' => 'bg-red-500'],
    ];
    $sc = $statusColors[$order->status] ?? $statusColors['pending'];
@endphp

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[12px] text-[#747780]">
        <a href="{{ route('orders.index') }}" class="hover:text-[#002352] transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Mes commandes
        </a>
        <span>/</span>
        <span class="font-mono font-semibold text-[#002352]">{{ $order->order_number }}</span>
    </nav>

    {{-- ── Header card ──────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <p class="font-mono font-bold text-[18px] text-[#002352]">{{ $order->order_number }}</p>
                <p class="text-[12px] text-[#747780] mt-1">
                    Passée le {{ $order->created_at->locale('fr')->isoFormat('D MMMM YYYY [à] HH[h]mm') }}
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-semibold border {{ $sc['badge'] }}">
                <span class="w-2 h-2 rounded-full {{ $sc['dot'] }}"></span>
                {{ $order->statusLabel }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5 pt-5 border-t border-[#f2f4f6]">
            <div>
                <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider">Total</p>
                <p class="text-[16px] font-bold text-[#002352] mt-0.5">{{ number_format($order->total, 0, ',', ' ') }} DA</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider">Paiement</p>
                <p class="text-[13px] font-semibold text-[#002352] mt-0.5">{{ $order->paymentMethodLabel }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider">Wilaya</p>
                <p class="text-[13px] font-semibold text-[#002352] mt-0.5">{{ $order->wilaya->name }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider">Articles</p>
                <p class="text-[13px] font-semibold text-[#002352] mt-0.5">{{ $order->items->count() }} article{{ $order->items->count() > 1 ? 's' : '' }}</p>
            </div>
        </div>
    </div>

    {{-- ── Stepper ──────────────────────────────────────────────── --}}
    @if(!$isCancelled)
    <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-6">
        <p class="text-[12px] font-bold text-[#002352] uppercase tracking-wider mb-6">Étapes de livraison</p>

        <div class="relative">
            {{-- Progress line --}}
            <div class="absolute top-5 left-5 right-5 h-0.5 bg-[#edeef0] z-0 hidden sm:block"></div>
            <div class="absolute top-5 left-5 h-0.5 bg-[#002352] z-10 hidden sm:block transition-all duration-700"
                 style="width: calc({{ $currentIdx }}/3 * (100% - 40px) + {{ $currentIdx > 0 ? '20px' : '0px' }})"></div>

            <div class="flex flex-col sm:flex-row justify-between gap-6 sm:gap-2 relative z-20">
                @foreach($steps as $stepKey => $step)
                @php
                    $idx      = array_search($stepKey, $stepOrder);
                    $isDone   = $idx <= $currentIdx;
                    $isActive = $idx === $currentIdx;
                @endphp
                <div class="flex sm:flex-col items-center sm:items-center gap-3 sm:gap-2 flex-1">
                    {{-- Circle --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 transition-all
                        {{ $isDone ? 'bg-[#002352] shadow-lg shadow-[#002352]/20' : 'bg-[#f2f4f6] border-2 border-[#edeef0]' }}">
                        @if($isDone)
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        @else
                            <span class="text-[#c4c6d1] text-[12px] font-bold">{{ $idx + 1 }}</span>
                        @endif
                    </div>
                    {{-- Label --}}
                    <div class="sm:text-center">
                        <p class="text-[12px] font-semibold {{ $isDone ? 'text-[#002352]' : 'text-[#747780]' }}">
                            {{ $step['label'] }}
                        </p>
                        @if($isActive)
                        <p class="text-[10px] text-[#747780] mt-0.5 hidden sm:block">{{ $step['desc'] }}</p>
                        @endif
                        @if($isActive && isset($order->statusHistory))
                        @php
                            $histEntry = $order->statusHistory->firstWhere('to_status', $stepKey);
                        @endphp
                        @if($histEntry)
                        <p class="text-[10px] text-[#002352]/60 mt-0.5 font-mono hidden sm:block">
                            {{ $histEntry->changed_at->locale('fr')->isoFormat('D MMM [à] HH[h]mm') }}
                        </p>
                        @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    {{-- Cancelled --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div>
            <p class="text-[14px] font-bold text-red-700">Commande annulée</p>
            @if($order->cancelled_at)
            <p class="text-[11px] text-red-500 mt-0.5">
                Le {{ $order->cancelled_at->locale('fr')->isoFormat('D MMMM YYYY [à] HH[h]mm') }}
            </p>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Shipment info ────────────────────────────────────────── --}}
    @if($order->shipment && $order->shipment->tracking_number)
    <div class="bg-violet-50 border border-violet-200 rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
            </svg>
            <p class="text-[13px] font-bold text-violet-700">Informations d'expédition</p>
        </div>
        <div class="grid grid-cols-2 gap-4 text-[12px]">
            @if($order->shipment->carrier)
            <div>
                <p class="text-[10px] text-violet-500 font-bold uppercase tracking-wider">Transporteur</p>
                <p class="font-semibold text-violet-700 mt-0.5">{{ $order->shipment->carrier }}</p>
            </div>
            @endif
            <div>
                <p class="text-[10px] text-violet-500 font-bold uppercase tracking-wider">N° de suivi</p>
                <p class="font-mono font-bold text-violet-700 mt-0.5">{{ $order->shipment->tracking_number }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Order items ──────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-5">
        <p class="text-[12px] font-bold text-[#002352] uppercase tracking-wider mb-4">Articles commandés</p>
        <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="flex items-center justify-between py-2 border-b border-[#f2f4f6] last:border-0">
                <div class="flex-1 min-w-0 mr-4">
                    <p class="text-[13px] font-semibold text-[#002352] truncate">{{ $item->product_name }}</p>
                    @if($item->variant_label)
                    <p class="text-[11px] text-[#747780]">{{ $item->variant_label }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-1">
                        <p class="text-[11px] text-[#747780]">{{ number_format($item->unit_price, 0, ',', ' ') }} DA × {{ $item->quantity }}</p>
                        @if($item->sku)
                        <p class="text-[10px] text-[#c4c6d1] font-mono">{{ $item->sku }}</p>
                        @endif
                    </div>
                </div>
                <p class="text-[13px] font-bold text-[#002352] flex-shrink-0">{{ number_format($item->subtotal, 0, ',', ' ') }} DA</p>
            </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="mt-3 pt-3 border-t border-[#edeef0] space-y-1.5 text-[12px]">
            <div class="flex justify-between text-[#5d5f5f]">
                <span>Sous-total</span>
                <span class="font-semibold">{{ number_format($order->subtotal, 0, ',', ' ') }} DA</span>
            </div>
            @if($order->discount > 0)
            <div class="flex justify-between text-emerald-600">
                <span>Remise @if($order->promoCode)({{ $order->promoCode->code }})@endif</span>
                <span class="font-semibold">−{{ number_format($order->discount, 0, ',', ' ') }} DA</span>
            </div>
            @endif
            <div class="flex justify-between text-[#5d5f5f]">
                <span>Livraison ({{ $order->wilaya->name }})</span>
                <span class="font-semibold">{{ number_format($order->shipping_cost, 0, ',', ' ') }} DA</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-[#edeef0]">
                <span class="font-bold text-[#002352]">Total</span>
                <span class="font-bold text-[16px] text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
            </div>
        </div>
    </div>

    {{-- ── Delivery info ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-5">
            <p class="text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-3">Adresse de livraison</p>
            <p class="text-[13px] font-semibold text-[#002352]">{{ $order->customer_name }}</p>
            <p class="text-[12px] text-[#5d5f5f]">{{ $order->customer_phone }}</p>
            <p class="text-[12px] text-[#5d5f5f] mt-1">{{ $order->address }}</p>
            <p class="text-[12px] font-semibold text-[#002352] mt-1">Wilaya de {{ $order->wilaya->name }}</p>
        </div>

        {{-- Status history --}}
        <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-5">
            <p class="text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-3">Historique</p>
            @if($order->statusHistory->isNotEmpty())
            <div class="space-y-2.5">
                @foreach($order->statusHistory->sortByDesc('changed_at') as $h)
                @php
                    $hColors = [
                        'pending'   => 'text-amber-600 bg-amber-50',
                        'confirmed' => 'text-blue-600 bg-blue-50',
                        'shipped'   => 'text-violet-600 bg-violet-50',
                        'delivered' => 'text-emerald-600 bg-emerald-50',
                        'cancelled' => 'text-red-600 bg-red-50',
                    ];
                    $hLabels = [
                        'pending'   => 'En attente',
                        'confirmed' => 'Confirmée',
                        'shipped'   => 'Expédiée',
                        'delivered' => 'Livrée',
                        'cancelled' => 'Annulée',
                    ];
                    $hc = $hColors[$h->to_status] ?? 'text-slate-600 bg-slate-50';
                @endphp
                <div class="flex items-start gap-2.5">
                    <span class="mt-0.5 w-5 h-5 flex-shrink-0 rounded-full {{ $hc }} flex items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12px] font-semibold text-[#002352]">{{ $hLabels[$h->to_status] ?? $h->to_status }}</p>
                        @if($h->note)
                        <p class="text-[10px] text-[#747780]">{{ $h->note }}</p>
                        @endif
                        <p class="text-[10px] text-[#747780] font-mono">
                            {{ $h->changed_at->locale('fr')->isoFormat('D MMM YYYY, HH[h]mm') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-[12px] text-[#747780]">Aucun historique disponible.</p>
            @endif
        </div>
    </div>

    {{-- ── Notes ────────────────────────────────────────────────── --}}
    @if($order->notes)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
        <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider mb-1">Notes</p>
        <p class="text-[12px] text-amber-700">{{ $order->notes }}</p>
    </div>
    @endif

    {{-- New order CTA --}}
    <div class="flex justify-end">
        <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-[#f2f4f6] text-[#002352] text-[13px] font-semibold px-5 py-2.5 rounded-xl hover:bg-[#edeef0] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5A2.25 2.25 0 0118.75 19.5H5.25A2.25 2.25 0 013 17.25V6.75z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75h18"/>
            </svg>
            Continuer mes achats
        </a>
    </div>

</div>
@endsection
