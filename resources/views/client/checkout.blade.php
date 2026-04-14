@extends('layouts.app')

@section('title', 'Commander')
@section('page-title', 'Finaliser ma commande')
@section('page-description', 'Renseignez vos informations de livraison')

@php
    $items    = $cart['items'] ?? [];
    $promoCode      = $cart['promo_code'] ?? null;
    $promoPercent   = $cart['promo_discount_percentage'] ?? false;
    $promoValue     = $cart['promo_discount_value'] ?? 0;

    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $discountAmount = $promoCode
        ? ($promoPercent ? round($subtotal * ($promoValue / 100), 2) : min($promoValue, $subtotal))
        : 0;
@endphp

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[12px] text-[#747780] mb-6">
        <a href="{{ route('cart.index') }}" class="hover:text-[#002352] transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Panier
        </a>
        <span>/</span>
        <span class="text-[#002352] font-semibold">Informations de livraison</span>
    </nav>

    <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
        @csrf
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- ── LEFT: Form ─────────────────────────────────────── --}}
            <div class="flex-1 space-y-5">

                {{-- Section : Infos client --}}
                <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-[#002352] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <p class="text-[14px] font-bold text-[#002352]">Informations personnelles</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-1.5">Nom complet *</label>
                            <input type="text" name="customer_name" required
                                   value="{{ old('customer_name', $user->name) }}"
                                   placeholder="Prénom et nom"
                                   class="w-full bg-[#f8f9fb] border border-[#edeef0] rounded-xl px-4 py-2.5 text-[13px] text-[#002352] placeholder-[#c4c6d1] focus:outline-none focus:border-[#002352] focus:ring-2 focus:ring-[#002352]/10 transition-all @error('customer_name') border-red-400 @enderror">
                            @error('customer_name') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-1.5">Téléphone *</label>
                            <input type="tel" name="customer_phone" required
                                   value="{{ old('customer_phone', $user->phone) }}"
                                   placeholder="05 XX XX XX XX"
                                   class="w-full bg-[#f8f9fb] border border-[#edeef0] rounded-xl px-4 py-2.5 text-[13px] text-[#002352] placeholder-[#c4c6d1] focus:outline-none focus:border-[#002352] focus:ring-2 focus:ring-[#002352]/10 transition-all @error('customer_phone') border-red-400 @enderror">
                            @error('customer_phone') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-1.5">Email</label>
                            <input type="email" value="{{ $user->email }}" readonly
                                   class="w-full bg-[#f2f4f6] border border-[#edeef0] rounded-xl px-4 py-2.5 text-[13px] text-[#747780] cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Section : Adresse de livraison --}}
                <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-[#002352] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                        </div>
                        <p class="text-[14px] font-bold text-[#002352]">Adresse de livraison</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-1.5">Wilaya *</label>
                            <select name="wilaya_id" required id="wilayaSelect"
                                    class="w-full bg-[#f8f9fb] border border-[#edeef0] rounded-xl px-4 py-2.5 text-[13px] text-[#002352] focus:outline-none focus:border-[#002352] focus:ring-2 focus:ring-[#002352]/10 transition-all appearance-none @error('wilaya_id') border-red-400 @enderror">
                                <option value="">Sélectionnez votre wilaya</option>
                                @foreach($wilayas as $w)
                                <option value="{{ $w->id }}"
                                        data-cost="{{ $w->shipping_cost }}"
                                        {{ old('wilaya_id') == $w->id ? 'selected' : '' }}>
                                    {{ $w->code }} — {{ $w->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('wilaya_id') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-1.5">Adresse complète *</label>
                            <textarea name="address" required rows="3"
                                      placeholder="Numéro, rue, quartier, commune..."
                                      class="w-full bg-[#f8f9fb] border border-[#edeef0] rounded-xl px-4 py-2.5 text-[13px] text-[#002352] placeholder-[#c4c6d1] focus:outline-none focus:border-[#002352] focus:ring-2 focus:ring-[#002352]/10 transition-all resize-none @error('address') border-red-400 @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-wider mb-1.5">Notes (optionnel)</label>
                            <textarea name="notes" rows="2"
                                      placeholder="Instructions spéciales pour la livraison..."
                                      class="w-full bg-[#f8f9fb] border border-[#edeef0] rounded-xl px-4 py-2.5 text-[13px] text-[#002352] placeholder-[#c4c6d1] focus:outline-none focus:border-[#002352] focus:ring-2 focus:ring-[#002352]/10 transition-all resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section : Mode de paiement --}}
                <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-[#002352] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                            </svg>
                        </div>
                        <p class="text-[14px] font-bold text-[#002352]">Mode de paiement</p>
                    </div>

                    <div class="space-y-2.5">
                        @php
                        $paymentOptions = [
                            'cod'       => ['icon' => '💵', 'label' => 'Paiement à la livraison', 'desc' => 'Payez en cash à la réception'],
                            'baridimob' => ['icon' => '📱', 'label' => 'BaridiMob', 'desc' => 'Paiement via Algérie Poste'],
                            'cib'       => ['icon' => '💳', 'label' => 'CIB', 'desc' => 'Carte interbancaire CIB'],
                        ];
                        @endphp

                        @foreach($paymentOptions as $value => $opt)
                        <label class="flex items-center gap-4 p-4 rounded-xl border border-[#edeef0] cursor-pointer hover:border-[#002352] hover:bg-[#f8f9fb] transition-all has-[:checked]:border-[#002352] has-[:checked]:bg-[#f0f3f8] group">
                            <input type="radio" name="payment_method" value="{{ $value }}" {{ $value === 'cod' ? 'checked' : '' }} class="accent-[#002352]">
                            <span class="text-xl">{{ $opt['icon'] }}</span>
                            <div>
                                <p class="text-[13px] font-semibold text-[#002352]">{{ $opt['label'] }}</p>
                                <p class="text-[11px] text-[#747780]">{{ $opt['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('payment_method') <p class="text-red-500 text-[11px] mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ── RIGHT: Order summary ─────────────────────────── --}}
            <div class="lg:w-80">
                <div class="sticky top-24 space-y-4">

                    {{-- Items summary --}}
                    <div class="bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] p-5">
                        <p class="text-[12px] font-bold text-[#002352] uppercase tracking-wider mb-4">
                            Récapitulatif ({{ count($items) }} article{{ count($items) > 1 ? 's' : '' }})
                        </p>

                        <div class="space-y-3 max-h-52 overflow-y-auto pr-1">
                            @foreach($items as $item)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-[#f8f9fb] flex-shrink-0">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[12px] font-medium text-[#002352] truncate">{{ $item['name'] }}</p>
                                    @if($item['variant_label'])
                                    <p class="text-[10px] text-[#747780]">{{ $item['variant_label'] }}</p>
                                    @endif
                                    <p class="text-[11px] text-[#747780]">×{{ $item['quantity'] }}</p>
                                </div>
                                <p class="text-[12px] font-bold text-[#002352] flex-shrink-0">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} DA
                                </p>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t border-[#edeef0] space-y-2 text-[12px]">
                            <div class="flex justify-between text-[#5d5f5f]">
                                <span>Sous-total</span>
                                <span class="font-semibold text-[#002352]">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
                            </div>

                            @if($discountAmount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Remise ({{ $promoCode }})</span>
                                <span class="font-semibold">−{{ number_format($discountAmount, 0, ',', ' ') }} DA</span>
                            </div>
                            @endif

                            <div class="flex justify-between text-[#5d5f5f]">
                                <span>Livraison</span>
                                <span id="shippingDisplay" class="font-semibold text-[#747780]">—</span>
                            </div>

                            <div class="pt-2 border-t border-[#edeef0] flex justify-between">
                                <span class="font-bold text-[#002352]">Total</span>
                                <span id="totalDisplay" class="font-bold text-[18px] text-[#002352]">{{ number_format($subtotal - $discountAmount, 0, ',', ' ') }} DA</span>
                            </div>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <button type="submit" form="checkoutForm"
                            class="w-full flex items-center justify-center gap-2.5 bg-[#002352] text-white text-[14px] font-semibold py-4 rounded-2xl hover:bg-[#18396e] transition-colors shadow-lg shadow-[#002352]/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Confirmer la commande
                    </button>

                    <p class="text-center text-[11px] text-[#747780]">
                        En confirmant, vous acceptez nos conditions générales de vente.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const wilayaSelect   = document.getElementById('wilayaSelect');
    const shippingDisp   = document.getElementById('shippingDisplay');
    const totalDisp      = document.getElementById('totalDisplay');
    const subtotal       = {{ $subtotal }};
    const discount       = {{ $discountAmount }};

    function fmt(n) {
        return new Intl.NumberFormat('fr-DZ').format(Math.round(n)) + ' DA';
    }

    function updateTotal() {
        const opt = wilayaSelect.options[wilayaSelect.selectedIndex];
        const cost = parseFloat(opt?.dataset?.cost || 0);
        if (cost > 0) {
            shippingDisp.textContent = fmt(cost);
            shippingDisp.classList.remove('text-[#747780]');
            shippingDisp.classList.add('text-[#002352]');
        } else {
            shippingDisp.textContent = '—';
            shippingDisp.classList.add('text-[#747780]');
        }
        totalDisp.textContent = fmt(Math.max(0, subtotal - discount + cost));
    }

    wilayaSelect.addEventListener('change', updateTotal);
    updateTotal();
})();
</script>
@endpush
