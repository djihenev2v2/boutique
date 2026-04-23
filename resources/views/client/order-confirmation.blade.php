@php
    $shopName ??= config('app.name', 'Boutique');
    $logoPath ??= null;
    $cartCount ??= 0;
    $paymentLabels = [
        'cod'       => 'Paiement à la livraison (COD)',
        'baridimob' => 'BaridiMob',
        'cib'       => 'CIB / Edahabia',
    ];
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande confirmée — {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F8F6; }

        /* ─── CARDS ──────────────────────────────────── */
        .conf-card {
            background: #fff; border: 1px solid #EBEBEB;
            border-radius: 20px; padding: 28px;
        }
        .section-title {
            font-size: 13.5px; font-weight: 800; color: #6B7280;
            letter-spacing: .06em; text-transform: uppercase;
            margin-bottom: 14px; display: flex; align-items: center; gap-8px;
        }

        /* ─── SUCCESS BANNER ─────────────────────────── */
        .success-banner {
            background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
            border: 1.5px solid #6EE7B7;
            border-radius: 20px;
            padding: 36px 32px;
            text-align: center;
        }
        .check-circle {
            width: 68px; height: 68px; border-radius: 50%;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 28px rgba(5,150,105,.35);
        }
        .order-number-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; border: 1.5px solid #6EE7B7;
            border-radius: 12px; padding: 8px 18px;
            font-size: 17px; font-weight: 800; color: #047857;
            letter-spacing: .03em;
        }

        /* ─── INFO GRID ──────────────────────────────── */
        .info-row {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 12px; padding: 10px 0;
            border-bottom: 1px solid #F3F4F6;
            font-size: 13.5px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-key { color: #6B7280; font-weight: 500; flex-shrink: 0; }
        .info-val { color: #0D1B2A; font-weight: 700; text-align: right; }

        /* ─── ITEMS TABLE ────────────────────────────── */
        .item-row {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 0; border-bottom: 1px solid #F3F4F6;
        }
        .item-row:last-child { border-bottom: none; }

        /* ─── TOTAL BLOCK ────────────────────────────── */
        .sum-row { display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0; }

        /* ─── BUTTONS ────────────────────────────────── */
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 28px; border-radius: 14px;
            font-size: 14.5px; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, #002352 0%, #003D8F 100%);
            border: none; cursor: pointer; text-decoration: none;
            box-shadow: 0 4px 20px rgba(0,35,82,.30);
            transition: all .25s cubic-bezier(.4,0,.2,1);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,35,82,.38); }

        .btn-secondary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 26px; border-radius: 14px;
            font-size: 14.5px; font-weight: 700; color: #374151;
            background: #fff; border: 1.5px solid #E5E7EB; cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-secondary:hover { background: #F9FAFB; border-color: #D1D5DB; }

        /* ─── ANIMATIONS ─────────────────────────────── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:none} }
        @keyframes pop    { 0%{transform:scale(.5);opacity:0} 60%{transform:scale(1.1)} 100%{transform:scale(1);opacity:1} }
        .fade-up    { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-d1 { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .08s both; }
        .fade-up-d2 { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .18s both; }
        .fade-up-d3 { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .28s both; }
        .pop        { animation: pop .5s cubic-bezier(.22,1,.36,1) .1s both; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════ --}}
@include('client.partials.navbar')

{{-- ═══════════════════════════════════════════════════
     MAIN
════════════════════════════════════════════════════ --}}
<main class="max-w-[820px] mx-auto px-5 lg:px-8 py-10 space-y-6">

    {{-- ─── SUCCESS BANNER ──────────────────────────── --}}
    <div class="success-banner fade-up">
        <div class="check-circle pop">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>

        <h1 class="text-[22px] font-800 text-[#065F46] tracking-tight mb-2">
            ✅ Votre commande a été enregistrée avec succès !
        </h1>
        <p class="text-[13.5px] text-[#047857] font-500 mb-5">
            Vous serez contacté par téléphone pour la confirmation de votre commande.
        </p>

        <div class="order-number-badge">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664"/>
            </svg>
            Commande #{{ $order->order_number }}
        </div>

        <p class="mt-4 text-[12px] text-[#6B7280]">
            Passée le {{ \Carbon\Carbon::parse($order->created_at)->locale('fr')->isoFormat('D MMMM YYYY [à] HH:mm') }}
        </p>
    </div>

    {{-- ─── ORDER DETAILS ───────────────────────────── --}}
    <div class="conf-card fade-up-d1">
        <p class="section-title" style="gap:8px">
            <svg class="w-4 h-4 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            Détails de la commande
        </p>

        <div>
            <div class="info-row">
                <span class="info-key">Numéro de commande</span>
                <span class="info-val text-[#002352]">#{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Statut</span>
                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-3 py-1 text-[11.5px] font-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                    En attente de confirmation
                </span>
            </div>
            <div class="info-row">
                <span class="info-key">Méthode de paiement</span>
                <span class="info-val">{{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}</span>
            </div>
        </div>
    </div>

    {{-- ─── CUSTOMER & DELIVERY ─────────────────────── --}}
    <div class="conf-card fade-up-d1">
        <p class="section-title" style="gap:8px">
            <svg class="w-4 h-4 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
            Adresse de livraison
        </p>

        <div>
            <div class="info-row">
                <span class="info-key">Nom</span>
                <span class="info-val">{{ $order->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Téléphone</span>
                <span class="info-val">{{ $order->customer_phone }}</span>
            </div>
            @if($order->customer_email)
            <div class="info-row">
                <span class="info-key">Email</span>
                <span class="info-val">{{ $order->customer_email }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-key">Wilaya</span>
                <span class="info-val">{{ $order->wilaya->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Adresse</span>
                <span class="info-val" style="max-width:60%">{{ $order->address }}</span>
            </div>
        </div>
    </div>

    {{-- ─── ITEMS ───────────────────────────────────── --}}
    <div class="conf-card fade-up-d2">
        <p class="section-title" style="gap:8px">
            <svg class="w-4 h-4 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
            Produits commandés
        </p>

        <div>
            @foreach($order->items as $item)
            <div class="item-row">
                <div class="w-10 h-10 rounded-[10px] bg-[#F3F4F6] flex-shrink-0 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#D1D5DB]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13.5px] font-700 text-[#0D1B2A] truncate">{{ $item->product_name }}</p>
                    @if($item->variant_label)
                    <p class="text-[11.5px] text-[#9CA3AF] font-500">{{ $item->variant_label }}</p>
                    @endif
                    @if($item->sku)
                    <p class="text-[10.5px] text-[#C0C7D0] font-500">SKU : {{ $item->sku }}</p>
                    @endif
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-[13.5px] font-800 text-[#002352]">{{ number_format($item->subtotal, 0, ',', ' ') }} DA</p>
                    <p class="text-[11.5px] text-[#9CA3AF] font-500">{{ $item->quantity }} × {{ number_format($item->unit_price, 0, ',', ' ') }} DA</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Financial summary --}}
        <div class="mt-5 pt-5 border-t border-[#F3F4F6] space-y-1.5">
            <div class="sum-row">
                <span class="text-[#6B7280] font-500">Sous-total</span>
                <span class="font-700 text-[#0D1B2A]">{{ number_format($order->subtotal, 0, ',', ' ') }} DA</span>
            </div>
            <div class="sum-row">
                <span class="text-[#6B7280] font-500">Livraison</span>
                <span class="font-700 text-[#0D1B2A]">{{ number_format($order->shipping_cost, 0, ',', ' ') }} DA</span>
            </div>
            @if($order->discount > 0)
            <div class="sum-row">
                <span class="text-emerald-600 font-600">Code promo</span>
                <span class="font-700 text-emerald-600">− {{ number_format($order->discount, 0, ',', ' ') }} DA</span>
            </div>
            @endif

            <div class="pt-3 mt-2 border-t border-[#EBEBEB] flex justify-between items-center">
                <span class="text-[15px] font-700 text-[#0D1B2A]">Total payé</span>
                <span class="text-[22px] font-800 text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
            </div>
        </div>
    </div>

    {{-- ─── NOTICE ──────────────────────────────────── --}}
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3.5 fade-up-d2">
        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
        <p class="text-[12.5px] text-blue-800 font-600">
            Notre équipe vous contactera au <span class="font-800">{{ $order->customer_phone }}</span> dans les plus brefs délais pour confirmer votre commande et convenir des détails de livraison.
        </p>
    </div>

    {{-- ─── CTAs ────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row gap-3 fade-up-d3">
        <a href="/" class="btn-primary flex-1 text-center
        ">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            Retour à l'accueil
        </a>
        <a href="{{ route('catalogue') }}" class="btn-secondary flex-1 text-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
            Continuer mes achats
        </a>
    </div>

</main>

{{-- ═══════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ --}}
<footer style="background:#0D0D0D;color:#9CA3AF;" class="mt-16">
    <div class="max-w-[820px] mx-auto px-5 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background:#002352">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                </div>
                <span class="text-white font-700 text-[13px]">{{ $shopName }}</span>
            </div>
            <p class="text-[12px]">© {{ date('Y') }} — Tous droits réservés.</p>
        </div>
    </div>
</footer>

</body>
</html>
