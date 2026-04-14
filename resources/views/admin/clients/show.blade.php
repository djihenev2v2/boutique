@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', 'Fiche client')
@section('page-description', $user->name)

@section('content')
<div class="space-y-6 max-w-5xl">

    {{-- Retour --}}
    <a href="{{ route('admin.clients.index') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-[#18396e] transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Retour aux clients
    </a>

    {{-- Hero Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row gap-6 items-start">
            {{-- Avatar --}}
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold text-white flex-shrink-0"
                 style="background: linear-gradient(135deg, #18396e 0%, #2d5fa8 100%)">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            {{-- Info grid --}}
            <div class="flex-1 grid grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Nom complet</p>
                    <p class="text-[15px] font-semibold text-slate-800">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Téléphone</p>
                    <a href="tel:{{ $user->phone }}" class="text-[15px] font-semibold text-[#18396e] hover:underline">{{ $user->phone ?? '—' }}</a>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Email</p>
                    <p class="text-[14px] text-slate-700">{{ $user->email ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Wilaya</p>
                    <p class="text-[14px] text-slate-700">{{ $user->wilaya?->name ?? '—' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Adresse</p>
                    <p class="text-[13px] text-slate-600 leading-relaxed">{{ $user->address ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Client depuis</p>
                    <p class="text-[13px] text-slate-600">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $statCards = [
            [
                'label'  => 'Commandes totales',
                'value'  => $stats['total_orders'],
                'suffix' => '',
                'color'  => 'blue',
                'icon'   => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z',
            ],
            [
                'label'  => 'Total dépensé',
                'value'  => number_format($stats['total_spent'], 0, ',', ' '),
                'suffix' => ' DA',
                'color'  => 'emerald',
                'icon'   => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z',
            ],
            [
                'label'  => 'Panier moyen',
                'value'  => number_format($stats['avg_order'], 0, ',', ' '),
                'suffix' => ' DA',
                'color'  => 'violet',
                'icon'   => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
            ],
            [
                'label'  => 'Dernière commande',
                'value'  => $stats['last_order'] ? $stats['last_order']->format('d/m/Y') : '—',
                'suffix' => '',
                'color'  => 'amber',
                'icon'   => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5',
            ],
        ];
        $colorMap = [
            'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'bg-blue-100 text-blue-600',   'val' => 'text-blue-700'],
            'emerald'=> ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-100 text-emerald-600', 'val' => 'text-emerald-700'],
            'violet' => ['bg' => 'bg-violet-50',  'icon' => 'bg-violet-100 text-violet-600',  'val' => 'text-violet-700'],
            'amber'  => ['bg' => 'bg-amber-50',   'icon' => 'bg-amber-100 text-amber-600',   'val' => 'text-amber-700'],
        ];
        @endphp
        @foreach($statCards as $card)
        @php $c = $colorMap[$card['color']]; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-[#edeef0] p-5 {{ $c['bg'] }} border-0">
            <div class="flex items-start justify-between gap-2 mb-3">
                <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400 leading-tight">{{ $card['label'] }}</p>
                <div class="w-8 h-8 rounded-xl {{ $c['icon'] }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold {{ $c['val'] }} leading-none">
                {{ $card['value'] }}<span class="text-sm font-semibold opacity-60">{{ $card['suffix'] }}</span>
            </p>
        </div>
        @endforeach
    </div>

    {{-- Historique des commandes --}}
    <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#edeef0] flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-[#e8edf5] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
            </div>
            <h3 class="text-[14px] font-semibold text-[#18396e]">Historique des commandes</h3>
        </div>
        @if($orders->isEmpty())
        <div class="py-16 text-center">
            <p class="text-slate-400 text-sm">Aucune commande pour ce client.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#edeef0] bg-[#f8f9fb]">
                        <th class="text-left px-5 py-3 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">#</th>
                        <th class="text-left px-5 py-3 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Date</th>
                        <th class="text-left px-5 py-3 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Wilaya</th>
                        <th class="text-left px-5 py-3 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Total</th>
                        <th class="text-left px-5 py-3 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f1f3]">
                    @foreach($orders as $order)
                    @php
                    $statusStyle = [
                        'pending'   => 'bg-amber-100 text-amber-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'shipped'   => 'bg-violet-100 text-violet-700',
                        'delivered' => 'bg-emerald-100 text-emerald-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ][$order->status] ?? 'bg-slate-100 text-slate-600';
                    $statusLabel = \App\Models\Order::STATUSES[$order->status]['label'] ?? $order->status;
                    @endphp
                    <tr class="hover:bg-[#fafbfc] transition-colors cursor-pointer"
                        onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                        <td class="px-5 py-3.5">
                            <span class="font-mono font-semibold text-[#18396e] text-[12px]">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ $order->wilaya?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">
                            {{ number_format($order->total, 0, ',', ' ') }} <span class="font-normal text-slate-400 text-[11px]">DA</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusStyle }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-[#edeef0]">
            {{ $orders->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection