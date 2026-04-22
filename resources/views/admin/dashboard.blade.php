@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Vue d\'ensemble de votre activité')

@section('content')

{{-- Welcome row --}}
<div class="flex items-center justify-between mb-7">
    <div>
        <h2 class="text-xl font-bold text-[#002352] tracking-tight">Bonjour, {{ Auth::user()->name }} 👋</h2>
        <p class="text-[#5d5f5f] text-[13px] mt-0.5">Voici le résumé de votre activité du jour</p>
    </div>
    <div class="hidden sm:flex items-center gap-2 bg-white border border-[#c4c6d1]/30 rounded-xl px-4 py-2.5 shadow-[0px_4px_12px_rgba(24,57,110,0.06)]">
        <svg class="w-4 h-4 text-[#27467b]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
        </svg>
        <span class="text-[13px] font-medium text-[#191c1e]">{{ now()->isoFormat('D MMM YYYY') }}</span>
    </div>
</div>

{{-- KPI Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5 mb-8">

    {{-- Chiffre d'affaires --}}
    <div class="bg-white rounded-2xl p-5 shadow-[0px_20px_40px_rgba(24,57,110,0.06)] transition-all duration-200 hover:shadow-[0px_25px_50px_rgba(24,57,110,0.12)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-[#5d5f5f]">Chiffre d'affaires</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-[#d8e2ff]">
                <svg class="w-5 h-5 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#002352] leading-none">{{ number_format($revenue, 0, ',', ' ') }} DA</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="text-[11px] text-[#5d5f5f]">Commandes livrées</span>
        </div>
        <div class="mt-4 h-1 w-full bg-[#edeef0] rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-[#002352] to-[#18396e] rounded-full" style="width:{{ $revenue > 0 ? '75' : '5' }}%"></div>
        </div>
    </div>

    {{-- Commandes du jour --}}
    <div class="bg-white rounded-2xl p-5 shadow-[0px_20px_40px_rgba(24,57,110,0.06)] transition-all duration-200 hover:shadow-[0px_25px_50px_rgba(24,57,110,0.12)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-[#5d5f5f]">Commandes du jour</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-orange-50">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#002352] leading-none">{{ $todayOrders }}</p>
        <div class="flex items-center gap-1.5 mt-2">
            @if($pendingCount > 0)
            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-amber-600 bg-amber-50 rounded-full px-2 py-0.5">
                {{ $pendingCount }} en attente
            </span>
            @else
            <span class="text-[11px] text-[#5d5f5f]">Aujourd'hui</span>
            @endif
        </div>
        <div class="mt-4 h-1 w-full bg-[#edeef0] rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-[#ffb783] to-[#dc945f] rounded-full" style="width:{{ min(100, $todayOrders * 10) }}%"></div>
        </div>
    </div>

    {{-- Total commandes --}}
    <div class="bg-white rounded-2xl p-5 shadow-[0px_20px_40px_rgba(24,57,110,0.06)] transition-all duration-200 hover:shadow-[0px_25px_50px_rgba(24,57,110,0.12)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-[#5d5f5f]">Commandes</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-indigo-50">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#002352] leading-none">{{ $totalOrders }}</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="text-[11px] text-[#5d5f5f]">Total des commandes</span>
        </div>
        <div class="mt-4 h-1 w-full bg-[#edeef0] rounded-full overflow-hidden">
            <div class="h-full bg-indigo-400 rounded-full" style="width:{{ min(100, max(5, $totalOrders)) }}%"></div>
        </div>
    </div>

    {{-- Produits en rupture --}}
    <div class="bg-white rounded-2xl p-5 shadow-[0px_20px_40px_rgba(24,57,110,0.06)] transition-all duration-200 hover:shadow-[0px_25px_50px_rgba(24,57,110,0.12)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-[#5d5f5f]">En rupture</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-50">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#002352] leading-none">{{ $outOfStock }}</p>
        <div class="flex items-center gap-1.5 mt-2">
            @if($outOfStock > 0)
            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-red-600 bg-red-50 rounded-full px-2 py-0.5">
                À réapprovisionner
            </span>
            @else
            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-emerald-600 bg-emerald-50 rounded-full px-2 py-0.5">
                Tout en stock ✓
            </span>
            @endif
        </div>
        <div class="mt-4 h-1 w-full bg-[#edeef0] rounded-full overflow-hidden">
            <div class="h-full bg-amber-400 rounded-full" style="width:{{ $outOfStock > 0 ? min(100, $outOfStock * 5) : 5 }}%"></div>
        </div>
    </div>
</div>

{{-- Sales chart --}}
<div class="bg-white rounded-2xl shadow-[0px_20px_40px_rgba(24,57,110,0.06)] p-6 mb-5">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-[15px] font-bold text-[#002352]">Évolution des ventes</h3>
            <p class="text-[12px] text-[#747780] mt-0.5">Chiffre d'affaires par jour (DA)</p>
        </div>
        <div class="flex items-center gap-1.5 bg-[#f2f4f6] p-1 rounded-xl">
            <button id="btn7" onclick="switchChart(7)"
                class="text-[12px] font-semibold px-3 py-1.5 rounded-lg transition-all bg-white text-[#002352] shadow-sm">
                7 jours
            </button>
            <button id="btn30" onclick="switchChart(30)"
                class="text-[12px] font-semibold px-3 py-1.5 rounded-lg transition-all text-[#747780]">
                30 jours
            </button>
        </div>
    </div>
    <div class="h-[220px]">
        <canvas id="salesChart"></canvas>
    </div>
</div>

{{-- Bottom grid --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Commandes récentes --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-[0px_20px_40px_rgba(24,57,110,0.06)] overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#f2f4f6]">
            <h3 class="text-[15px] font-bold text-[#002352]">Commandes Récentes</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-[12px] font-medium text-[#27467b] hover:text-[#002352] hover:underline">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#f2f4f6] border-b border-[#edeef0]">
                        <th class="text-left px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Commande</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Client</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Wilaya</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Total</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#5d5f5f]">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    @php
                        $statusColors = [
                            'pending'   => 'bg-amber-100 text-amber-700',
                            'confirmed' => 'bg-blue-100 text-blue-700',
                            'shipped'   => 'bg-violet-100 text-violet-700',
                            'delivered' => 'bg-emerald-100 text-emerald-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                        $statusLabels = [
                            'pending'   => 'En attente',
                            'confirmed' => 'Confirmée',
                            'shipped'   => 'Expédiée',
                            'delivered' => 'Livrée',
                            'cancelled' => 'Annulée',
                        ];
                        $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700';
                        $label = $statusLabels[$order->status] ?? $order->status;
                    @endphp
                    <tr class="border-b border-[#f2f4f6] hover:bg-[#f8f9fb] transition-colors cursor-pointer"
                        onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
                        <td class="px-6 py-3">
                            <span class="text-[13px] font-semibold text-[#002352]">{{ $order->order_number }}</span>
                            <p class="text-[11px] text-[#747780]">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-4 py-3 text-[13px] text-[#43474f]">{{ Str::limit($order->customer_name, 20) }}</td>
                        <td class="px-4 py-3 text-[13px] text-[#5d5f5f]">{{ $order->wilaya?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-[13px] font-semibold text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 bg-[#f2f4f6]">
                                    <svg class="w-7 h-7 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                                    </svg>
                                </div>
                                <p class="text-[13.5px] font-semibold text-[#43474f]">Aucune commande pour l'instant</p>
                                <p class="text-[12px] text-[#5d5f5f] mt-1">Les nouvelles commandes apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right column --}}
    <div class="flex flex-col gap-5">

        {{-- Best-sellers --}}
        <div class="bg-white rounded-2xl shadow-[0px_20px_40px_rgba(24,57,110,0.06)] overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#f2f4f6]">
                <h3 class="text-[15px] font-bold text-[#002352]">Top 5 Produits</h3>
                <span class="text-[11px] text-[#747780]">Par quantité vendue</span>
            </div>
            <div class="px-5 py-3 divide-y divide-[#f2f4f6]">
                @forelse($topProducts as $i => $product)
                <div class="flex items-center gap-3 py-3">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold flex-shrink-0
                        {{ $i === 0 ? 'bg-amber-100 text-amber-700' : ($i === 1 ? 'bg-slate-100 text-slate-600' : ($i === 2 ? 'bg-orange-100 text-orange-600' : 'bg-[#f2f4f6] text-[#5d5f5f]')) }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-[#002352] truncate">{{ $product->product_name }}</p>
                        <p class="text-[11px] text-[#747780]">{{ $product->total_qty }} vendus</p>
                    </div>
                    <span class="text-[12px] font-bold text-[#27467b] flex-shrink-0">{{ number_format($product->total_revenue, 0, ',', ' ') }} DA</span>
                </div>
                @empty
                <div class="flex flex-col items-center py-6 text-center">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-2.5 bg-[#f2f4f6]">
                        <svg class="w-6 h-6 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                        </svg>
                    </div>
                    <p class="text-[12.5px] text-[#5d5f5f]">Aucun produit vendu</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Quick links --}}
        <div class="rounded-2xl p-5 bg-gradient-to-br from-[#002352] to-[#18396e] shadow-[0px_20px_40px_rgba(0,35,82,0.25)] relative overflow-hidden">
            <p class="text-white/70 text-[10px] font-bold uppercase tracking-widest mb-2">Accès rapide</p>
            <h4 class="text-white font-bold text-[17px] leading-snug mb-4">Gérer votre boutique</h4>
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-[12px] font-semibold px-4 py-2.5 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                    </svg>
                    @if($pendingCount > 0)
                    <span class="flex-1">Commandes</span>
                    <span class="text-[11px] font-bold bg-amber-400 text-amber-900 rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                    @else
                    Commandes
                    @endif
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-[12px] font-semibold px-4 py-2.5 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                    <span class="flex-1">Produits</span>
                    @if($outOfStock > 0)
                    <span class="text-[11px] font-bold bg-red-400 text-red-900 rounded-full px-2 py-0.5">{{ $outOfStock }} rupture</span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chart7Data  = @json($chart7);
const chart30Data = @json($chart30);
let salesChart    = null;

function buildChart(data) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 220);
    gradient.addColorStop(0, 'rgba(24, 57, 110, 0.18)');
    gradient.addColorStop(1, 'rgba(24, 57, 110, 0.00)');
    if (salesChart) salesChart.destroy();
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Ventes (DA)',
                data: data.revenues,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#18396e',
                borderWidth: 2.5,
                tension: 0.4,
                pointBackgroundColor: '#18396e',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#002352',
                    padding: 10,
                    cornerRadius: 10,
                    titleFont: { size: 12 },
                    bodyFont: { size: 13, weight: 'bold' },
                    callbacks: {
                        label: ctx => ' ' + new Intl.NumberFormat('fr-DZ').format(ctx.raw) + ' DA',
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#747780' },
                },
                y: {
                    grid: { color: '#f2f4f6' },
                    ticks: {
                        font: { size: 11 }, color: '#747780',
                        callback: v => new Intl.NumberFormat('fr-DZ', { notation: 'compact' }).format(v) + ' DA',
                    }
                }
            }
        }
    });
}

function switchChart(days) {
    buildChart(days === 7 ? chart7Data : chart30Data);
    document.getElementById('btn7').className  = days === 7
        ? 'text-[12px] font-semibold px-3 py-1.5 rounded-lg transition-all bg-white text-[#002352] shadow-sm'
        : 'text-[12px] font-semibold px-3 py-1.5 rounded-lg transition-all text-[#747780]';
    document.getElementById('btn30').className = days === 30
        ? 'text-[12px] font-semibold px-3 py-1.5 rounded-lg transition-all bg-white text-[#002352] shadow-sm'
        : 'text-[12px] font-semibold px-3 py-1.5 rounded-lg transition-all text-[#747780]';
}

document.addEventListener('DOMContentLoaded', () => buildChart(chart7Data));
</script>
@endpush