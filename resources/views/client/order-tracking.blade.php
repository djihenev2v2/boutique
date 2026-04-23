@php
    use Illuminate\Support\Facades\Storage;
    $shopName  ??= config('app.name', 'Boutique');
    $logoPath  ??= null;
    $cartCount ??= 0;
    $order     ??= null;
    $searched  ??= false;

    $statusOrder = ['pending', 'confirmed', 'shipped', 'delivered'];
    $statusLabels = [
        'pending'   => 'En attente',
        'confirmed' => 'Confirmée',
        'shipped'   => 'Expédiée',
        'delivered' => 'Livrée',
        'cancelled' => 'Annulée',
    ];
    $statusColors = [
        'pending'   => '#8D7350',
        'confirmed' => '#002352',
        'shipped'   => '#1F3D63',
        'delivered' => '#27467B',
        'cancelled' => '#EF4444',
    ];
    $statusBg = [
        'pending'   => '#F3EBDD',
        'confirmed' => '#E6EDF6',
        'shipped'   => '#EDE8DF',
        'delivered' => '#DDE7F3',
        'cancelled' => '#FEE2E2',
    ];
    $currentStatusIndex = $order ? array_search($order->status, $statusOrder) : -1;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de commande — {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F8F6; }

        /* ─── FORM ──────────────────────────────────── */
        .field-label { display: block; font-size: 12.5px; font-weight: 700; color: #374151; margin-bottom: 6px; }
        .field-input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #E5E7EB; border-radius: 12px;
            font-size: 14px; color: #0D1B2A; background: #fff;
            outline: none; transition: border-color .15s, box-shadow .15s; font-family: inherit;
        }
        .field-input:focus { border-color: #002352; box-shadow: 0 0 0 3px rgba(0,35,82,.08); }

        /* ─── CARD ──────────────────────────────────── */
        .track-card {
            background: #fff; border: 1px solid #EBEBEB;
            border-radius: 20px; padding: 28px;
        }

        /* ─── TIMELINE ──────────────────────────────── */
        .timeline-step { display: flex; align-items: flex-start; gap: 14px; position: relative; }
        .timeline-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 17px; top: 36px;
            width: 2px; height: calc(100% + 4px);
            background: #E5E7EB;
        }
        .timeline-step.done:not(:last-child)::after { background: #27467B; }
        .timeline-dot {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            border: 2px solid #E5E7EB; background: #F9FAFB;
            display: flex; align-items: center; justify-content: center;
            position: relative; z-index: 1;
        }
        .timeline-dot.done { background: #27467B; border-color: #27467B; }
        .timeline-dot.current { background: #002352; border-color: #002352; box-shadow: 0 0 0 4px rgba(0,35,82,.12); }
        .timeline-dot.cancelled { background: #EF4444; border-color: #EF4444; }

        /* ─── CTA ───────────────────────────────────── */
        .btn-search {
            width: 100%; padding: 13px 24px; border-radius: 12px;
            font-size: 14px; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, #001832 0%, #002352 100%);
            border: none; cursor: pointer;
            box-shadow: 0 4px 20px rgba(0,35,82,.3);
            transition: all .25s cubic-bezier(.4,0,.2,1);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-search:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,35,82,.4); }

        /* ─── BADGE ─────────────────────────────────── */
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 99px;
        }

        /* ─── ANIMATIONS ────────────────────────────── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }
        .fade-up { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-1 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .05s both; }
        .fade-up-2 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .15s both; }
        .fade-up-3 { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .25s both; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════════════ --}}
@include('client.partials.navbar')

{{-- ═══════════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════════ --}}
<div style="background:#001832; border-bottom:1px solid rgba(237,232,223,.18); position:relative; overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 2px 2px, rgba(255,255,255,.04) 1px, transparent 0);background-size:28px 28px;pointer-events:none;"></div>
    <div style="position:absolute; width:360px; height:360px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 65%); top:-180px; right:-30px; pointer-events:none;"></div>
    <div class="relative max-w-[1320px] mx-auto px-5 lg:px-8 py-10">
        <nav class="flex items-center gap-1.5 mb-4 text-[12px]" style="color:rgba(255,255,255,.55)">
            <a href="/" class="hover:text-white transition-colors font-medium" style="color:rgba(255,255,255,.55)">Accueil</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold" style="color:rgba(237,232,223,.95)">Suivi de commande</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/15" style="background:rgba(237,232,223,.14)">
                <svg class="w-6 h-6 text-[#EDE8DF]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.143-.504 1.125-1.125a17.902 17.902 0 00-3.213-9.174L16.5 3.676A1.125 1.125 0 0015.443 3H12.75v14.25m0 0h-3m3 0h3m-6 0V5.625m0 12.625H5.625"/></svg>
            </div>
            <div>
                <h1 class="text-[28px] lg:text-[34px] font-extrabold text-[#EDE8DF] tracking-tight leading-tight">Suivre ma commande</h1>
                <p class="text-[14px] font-medium mt-0.5" style="color:rgba(255,255,255,.58)">Entrez vos informations pour suivre l'état de votre commande</p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════════════ --}}
<main class="max-w-[1320px] mx-auto px-5 lg:px-8 py-10">
    <div class="max-w-[700px] mx-auto space-y-6">

        {{-- ─── SEARCH FORM ──────────────────────────────── --}}
        <div class="track-card fade-up">
            <h2 class="text-[16px] font-extrabold text-[#0D1B2A] mb-6 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 border border-[#D8CCBB]" style="background:#EDE8DF">
                    <svg class="w-4.5 h-4.5 text-[#002352]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                Rechercher ma commande
            </h2>

            <form method="POST" action="{{ route('order.tracking.search') }}" class="space-y-4">
                @csrf

                @error('general')
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[13px] font-semibold">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ $message }}
                </div>
                @enderror

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="field-label" for="phone">Numéro de téléphone <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone" name="phone"
                               class="field-input @error('phone') border-red-400 @enderror"
                               value="{{ old('phone', $searched ? ($searched_phone ?? '') : '') }}"
                               placeholder="0X XX XX XX XX"
                               autocomplete="tel"
                               required>
                        @error('phone')
                        <p class="text-[11.5px] text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="field-label" for="order_number">Numéro de commande <span class="text-red-500">*</span></label>
                        <input type="text" id="order_number" name="order_number"
                               class="field-input @error('order_number') border-red-400 @enderror"
                               value="{{ old('order_number', $searched ? ($searched_order ?? '') : '') }}"
                               placeholder="CMD-XXXXXXXX"
                               autocomplete="off"
                               style="text-transform:uppercase"
                               required>
                        @error('order_number')
                        <p class="text-[11.5px] text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-search">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    Rechercher ma commande
                </button>
            </form>
        </div>

        {{-- ─── RESULT ──────────────────────────────────── --}}
        @if($searched)
            @if($order)

            {{-- ─── ORDER FOUND ─────────────────────────── --}}

            {{-- Status banner --}}
            <div class="track-card fade-up-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-[#9CA3AF] mb-1">Commande</p>
                        <h3 class="text-[22px] font-extrabold text-[#0D1B2A]">{{ $order->order_number }}</h3>
                        <p class="text-[12.5px] text-[#9CA3AF] mt-1">
                            Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    @if($order->status === 'cancelled')
                    <span class="status-badge" style="background:{{ $statusBg[$order->status] ?? '#F3F4F6' }};color:{{ $statusColors[$order->status] ?? '#374151' }}">
                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $statusColors[$order->status] }}"></span>
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                    @else
                    <span class="status-badge" style="background:{{ $statusBg[$order->status] ?? '#F3F4F6' }};color:{{ $statusColors[$order->status] ?? '#374151' }}">
                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $statusColors[$order->status] }}"></span>
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                    @endif
                </div>

                {{-- ─── TIMELINE ──────────────────────────── --}}
                @if($order->status !== 'cancelled')
                <div class="space-y-5 mb-2">
                    @foreach($statusOrder as $idx => $step)
                    @php
                        $isDone    = $currentStatusIndex > $idx;
                        $isCurrent = $currentStatusIndex === $idx;
                        $dotClass  = $isDone ? 'done' : ($isCurrent ? 'current' : '');
                        $stepDates = [
                            'pending'   => $order->created_at,
                            'confirmed' => $order->confirmed_at,
                            'shipped'   => $order->shipped_at,
                            'delivered' => $order->delivered_at,
                        ];
                        $stepDate = $stepDates[$step] ?? null;
                    @endphp
                    <div class="timeline-step {{ $isDone ? 'done' : '' }}">
                        <div class="timeline-dot {{ $dotClass }}">
                            @if($isDone)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @elseif($isCurrent)
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            @else
                            <span class="w-2 h-2 rounded-full bg-[#D1D5DB]"></span>
                            @endif
                        </div>
                        <div class="pb-5 flex-1 min-w-0">
                            <p class="text-[14px] font-bold {{ ($isDone || $isCurrent) ? 'text-[#0D1B2A]' : 'text-[#9CA3AF]' }}">
                                {{ $statusLabels[$step] }}
                            </p>
                            @if($stepDate)
                            <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">{{ $stepDate->format('d/m/Y à H:i') }}</p>
                            @elseif($isCurrent)
                            <p class="text-[11.5px] text-[#9CA3AF] mt-0.5">En cours…</p>
                            @else
                            <p class="text-[11.5px] text-[#D1D5DB] mt-0.5">En attente</p>
                            @endif

                            {{-- Shipping info on shipped step --}}
                            @if($step === 'shipped' && $order->shipment && ($isDone || $isCurrent))
                            <div class="mt-2 inline-flex flex-wrap gap-3">
                                @if($order->shipment->carrier)
                                <span class="inline-flex items-center gap-1.5 text-[11.5px] font-semibold text-[#374151] bg-[#F3F4F6] px-3 py-1 rounded-lg">
                                    <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25"/></svg>
                                    {{ $order->shipment->carrier }}
                                </span>
                                @endif
                                @if($order->shipment->tracking_number)
                                <span class="inline-flex items-center gap-1.5 text-[11.5px] font-semibold text-[#002352] bg-[#EDE8DF] px-3 py-1 rounded-lg">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                    N° {{ $order->shipment->tracking_number }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                {{-- Cancelled state --}}
                <div class="flex items-center gap-3 bg-red-50 border border-red-100 rounded-xl px-4 py-3 mb-2">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <p class="text-[13px] font-semibold text-red-700">Cette commande a été annulée.</p>
                </div>
                @endif
            </div>

            {{-- ─── DELIVERY INFO ────────────────────────── --}}
            <div class="track-card fade-up-2">
                <h3 class="text-[14px] font-extrabold text-[#0D1B2A] mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Adresse de livraison
                </h3>
                <div class="space-y-2 text-[13.5px]">
                    <div class="flex justify-between gap-2">
                        <span class="text-[#9CA3AF] font-medium w-28 flex-shrink-0">Wilaya</span>
                        <span class="font-semibold text-[#0D1B2A] text-right">{{ $order->wilaya->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-[#9CA3AF] font-medium w-28 flex-shrink-0">Adresse</span>
                        <span class="font-semibold text-[#0D1B2A] text-right">{{ $order->address }}</span>
                    </div>
                </div>
            </div>

            {{-- ─── ORDER TOTAL ──────────────────────────── --}}
            <div class="track-card fade-up-3">
                <h3 class="text-[14px] font-extrabold text-[#0D1B2A] mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/></svg>
                    Récapitulatif financier
                </h3>
                <div class="space-y-2 text-[13.5px]">
                    <div class="flex justify-between gap-2">
                        <span class="text-[#9CA3AF] font-medium">Sous-total</span>
                        <span class="font-semibold text-[#0D1B2A]">{{ number_format($order->subtotal, 0, ',', ' ') }} DA</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-[#9CA3AF] font-medium">Livraison</span>
                        <span class="font-semibold text-[#0D1B2A]">{{ number_format($order->shipping_cost, 0, ',', ' ') }} DA</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between gap-2">
                        <span class="text-[#9CA3AF] font-medium">Réduction</span>
                        <span class="font-semibold text-emerald-600">-{{ number_format($order->discount, 0, ',', ' ') }} DA</span>
                    </div>
                    @endif
                    <div class="h-px bg-[#F3F4F6] my-2"></div>
                    <div class="flex justify-between gap-2">
                        <span class="text-[15px] font-bold text-[#0D1B2A]">Total</span>
                        <span class="text-[18px] font-extrabold text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
                    </div>
                </div>
            </div>

            @else
            {{-- ─── ORDER NOT FOUND ──────────────────────── --}}
            <div class="track-card fade-up-1 text-center py-10">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background:linear-gradient(135deg,#FEF2F2,#FEE2E2)">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <h3 class="text-[18px] font-extrabold text-[#0D1B2A] mb-2">Aucune commande trouvée</h3>
                <p class="text-[13.5px] text-[#6B7280] max-w-[340px] mx-auto leading-relaxed">
                    Aucune commande trouvée avec ces informations. Vérifiez votre numéro de téléphone et votre numéro de commande.
                </p>
                <p class="text-[12px] text-[#9CA3AF] mt-3">
                    Le numéro de commande est au format <strong>CMD-XXXXXXXX</strong> et se trouve dans votre confirmation de commande.
                </p>
            </div>
            @endif
        @endif

        {{-- ─── HELP BOX ────────────────────────────────── --}}
        <div class="bg-[#EDE8DF] border border-[#D8CCBB] rounded-2xl p-5 text-[13px] text-[#27467B] fade-up">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-[#002352]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                <div>
                    <p class="font-bold mb-1">Besoin d'aide ?</p>
                    <p class="font-medium text-[#27467B]">Contactez-nous par téléphone ou retrouvez votre numéro de commande dans le SMS/email de confirmation reçu après votre achat.</p>
                </div>
            </div>
        </div>

    </div>
</main>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ --}}
@include('client.partials.catalogue-footer')

<script>
    // Auto-uppercase order number field
    document.getElementById('order_number').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
</body>
</html>
