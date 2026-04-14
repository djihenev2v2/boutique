@extends('layouts.app')

@section('title', 'Commande confirmée')
@section('page-title', 'Commande confirmée !')
@section('page-description', $order->order_number)

@section('content')
<div class="max-w-2xl mx-auto py-4">

    {{-- ── Success banner ──────────────────────────────────────── --}}
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-3xl p-8 text-center mb-6">
        <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 border-4 border-emerald-200 flex items-center justify-center mb-5 shadow-lg shadow-emerald-100">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-[22px] font-bold text-[#002352] mb-2">Merci pour votre commande !</h1>
        <p class="text-[13px] text-[#5d5f5f] mb-4">Votre commande a bien été enregistrée et est en cours de traitement.</p>
        <div class="inline-flex items-center gap-2 bg-white border border-emerald-200 rounded-xl px-5 py-2.5 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
            </svg>
            <span class="font-mono font-bold text-[#002352] text-[15px]">{{ $order->order_number }}</span>
        </div>
    </div>

    {{-- ── Info cards ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-white rounded-2xl p-4 shadow-[0px_2px_12px_rgba(24,57,110,0.06)]">
            <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider mb-1">Statut</p>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                En attente
            </span>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-[0px_2px_12px_rgba(24,57,110,0.06)]">
            <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider mb-1">Paiement</p>
            <p class="text-[13px] font-semibold text-[#002352]">{{ $order->paymentMethodLabel }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-[0px_2px_12px_rgba(24,57,110,0.06)]">
            <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider mb-1">Livraison</p>
            <p class="text-[13px] font-semibold text-[#002352]">{{ $order->wilaya->name }}</p>
            <p class="text-[11px] text-[#747780] truncate">{{ Str::limit($order->address, 40) }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-[0px_2px_12px_rgba(24,57,110,0.06)]">
            <p class="text-[10px] font-bold text-[#747780] uppercase tracking-wider mb-1">Total</p>
            <p class="text-[18px] font-bold text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</p>
        </div>
    </div>

    {{-- ── Order items ──────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-5 mb-6">
        <p class="text-[12px] font-bold text-[#002352] uppercase tracking-wider mb-4">Articles commandés</p>
        <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="flex items-center justify-between py-2 border-b border-[#f2f4f6] last:border-0">
                <div class="flex-1 min-w-0 mr-4">
                    <p class="text-[13px] font-semibold text-[#002352] truncate">{{ $item->product_name }}</p>
                    @if($item->variant_label)
                    <p class="text-[11px] text-[#747780]">{{ $item->variant_label }}</p>
                    @endif
                    <p class="text-[11px] text-[#747780]">Qté: {{ $item->quantity }}</p>
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
                <span>Remise</span>
                <span class="font-semibold">−{{ number_format($order->discount, 0, ',', ' ') }} DA</span>
            </div>
            @endif
            <div class="flex justify-between text-[#5d5f5f]">
                <span>Livraison</span>
                <span class="font-semibold">{{ number_format($order->shipping_cost, 0, ',', ' ') }} DA</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-[#edeef0]">
                <span class="font-bold text-[#002352]">Total</span>
                <span class="font-bold text-[15px] text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
            </div>
        </div>
    </div>

    {{-- ── Actions ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('orders.show', $order->order_number) }}"
           class="flex-1 flex items-center justify-center gap-2 bg-[#002352] text-white text-[13px] font-semibold py-3 rounded-xl hover:bg-[#18396e] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
            </svg>
            Suivre ma commande
        </a>
        <a href="{{ route('catalogue') }}"
           class="flex-1 flex items-center justify-center gap-2 bg-[#f2f4f6] text-[#002352] text-[13px] font-semibold py-3 rounded-xl hover:bg-[#edeef0] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5A2.25 2.25 0 0118.75 19.5H5.25A2.25 2.25 0 013 17.25V6.75z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75h18"/>
            </svg>
            Continuer mes achats
        </a>
    </div>

</div>
@endsection
