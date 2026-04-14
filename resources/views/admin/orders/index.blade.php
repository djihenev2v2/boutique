@extends('layouts.admin')

@section('title', 'Commandes')
@section('page-title', 'Commandes')
@section('page-description', 'Gestion et suivi de toutes les commandes')

@php
$statusConfig = \App\Models\Order::STATUSES;
$statusOrder  = ['pending','confirmed','shipped','delivered','cancelled'];
@endphp

@section('header-actions')
    <a href="{{ route('admin.orders.export', request()->query()) }}"
       class="inline-flex items-center gap-2 bg-white border border-[#c4c6d1]/40 text-[#002352] text-[12px] font-semibold px-4 py-2 rounded-full shadow-sm hover:shadow transition-all">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
        Exporter CSV
    </a>
@endsection

@section('content')

{{-- ── Compteurs rapides ───────────────────────────────────────────── --}}
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => ''])) }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full text-[12px] font-semibold border transition-all
              {{ !request('status') ? 'bg-[#002352] text-white border-[#002352] shadow' : 'bg-white text-[#5d5f5f] border-[#c4c6d1]/40 hover:border-[#002352]' }}">
        Toutes <span class="ml-1 opacity-70">{{ array_sum($counts) }}</span>
    </a>
    @foreach($statusOrder as $s)
    @php $cfg = $statusConfig[$s]; @endphp
    <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), ['status' => $s])) }}"
       class="flex items-center gap-2 px-4 py-2 rounded-full text-[12px] font-semibold border transition-all
              {{ request('status') === $s ? 'bg-[#002352] text-white border-[#002352] shadow' : 'bg-white text-[#5d5f5f] border-[#c4c6d1]/40 hover:border-[#002352]' }}">
        <span class="w-2 h-2 rounded-full inline-block
            @if($s==='pending')   bg-amber-400
            @elseif($s==='confirmed') bg-blue-500
            @elseif($s==='shipped')  bg-violet-500
            @elseif($s==='delivered') bg-emerald-500
            @else bg-red-500
            @endif"></span>
        {{ $cfg['label'] }}
        <span class="ml-0.5 opacity-70">{{ $counts[$s] ?? 0 }}</span>
    </a>
    @endforeach
</div>

{{-- ── Filtres ─────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-4 mb-5 flex flex-wrap gap-3 items-end">
    {{-- Recherche --}}
    <div class="flex-1 min-w-[200px]">
        <label class="block text-[10px] font-bold uppercase tracking-widest text-[#747780] mb-1.5">Recherche</label>
        <div class="flex items-center gap-2 bg-[#f2f4f6] rounded-xl px-3 py-2">
            <svg class="w-3.5 h-3.5 text-[#747780] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nom, téléphone, numéro..."
                   class="bg-transparent border-none outline-none text-[13px] text-[#002352] placeholder-[#9ca3af] w-full">
        </div>
    </div>

    {{-- Wilaya --}}
    <div class="min-w-[170px]">
        <label class="block text-[10px] font-bold uppercase tracking-widest text-[#747780] mb-1.5">Wilaya</label>
        <select name="wilaya_id" class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-3 py-2 border-none outline-none cursor-pointer">
            <option value="">Toutes les wilayas</option>
            @foreach($wilayas as $w)
            <option value="{{ $w->id }}" {{ request('wilaya_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Paiement --}}
    <div class="min-w-[160px]">
        <label class="block text-[10px] font-bold uppercase tracking-widest text-[#747780] mb-1.5">Paiement</label>
        <select name="payment_method" class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-3 py-2 border-none outline-none cursor-pointer">
            <option value="">Tous</option>
            @foreach(\App\Models\Order::PAYMENT_METHODS as $k => $v)
            <option value="{{ $k }}" {{ request('payment_method') === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>

    {{-- Date du --}}
    <div class="min-w-[140px]">
        <label class="block text-[10px] font-bold uppercase tracking-widest text-[#747780] mb-1.5">Du</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-3 py-2 border-none outline-none cursor-pointer">
    </div>

    {{-- Date au --}}
    <div class="min-w-[140px]">
        <label class="block text-[10px] font-bold uppercase tracking-widest text-[#747780] mb-1.5">Au</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-3 py-2 border-none outline-none cursor-pointer">
    </div>

    {{-- Boutons --}}
    <div class="flex gap-2">
        <button type="submit"
                class="inline-flex items-center gap-1.5 bg-[#002352] text-white text-[12px] font-semibold px-4 py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h17.25M3 9h9.75M3 13.5h9.75M3 18h9.75M15 17.25v-4.5m0 0l2.25 2.25M15 12.75l-2.25 2.25"/>
            </svg>
            Filtrer
        </button>
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center gap-1.5 bg-[#f2f4f6] text-[#5d5f5f] text-[12px] font-semibold px-4 py-2.5 rounded-xl hover:bg-[#e5e7eb] transition-colors">
            Réinitialiser
        </a>
    </div>
</form>

{{-- ── Tableau ──────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden">

    {{-- Header tableau --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#f2f4f6]">
        <p class="text-[13px] font-semibold text-[#002352]">
            {{ $orders->total() }} commande{{ $orders->total() > 1 ? 's' : '' }}
            @if(request('search')) pour "<span class="text-[#18396e]">{{ request('search') }}</span>"@endif
        </p>
        <div class="flex items-center gap-1 text-[11px] text-[#747780]">
            Trier par :
            @foreach(['created_at' => 'Date', 'total' => 'Total', 'status' => 'Statut'] as $col => $label)
            <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['sort' => $col, 'dir' => request('sort') === $col && request('dir') === 'asc' ? 'desc' : 'asc'])) }}"
               class="px-2 py-1 rounded-lg hover:bg-[#f2f4f6] transition-colors font-medium
                      {{ request('sort') === $col ? 'text-[#002352] bg-[#f2f4f6]' : '' }}">
                {{ $label }}
                @if(request('sort') === $col)
                    {{ request('dir') === 'asc' ? '↑' : '↓' }}
                @endif
            </a>
            @endforeach
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#f8f9fb] border-b border-[#edeef0]">
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">#</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Client</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Téléphone</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Wilaya</th>
                    <th class="text-right px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Total</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Statut</th>
                    <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Date</th>
                    <th class="text-center px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-[#747780]">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f2f4f6]">
                @forelse($orders as $order)
                <tr class="hover:bg-[#f8f9fb] transition-colors group">

                    {{-- Numéro --}}
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-[13px] font-semibold text-[#18396e] hover:text-[#002352] hover:underline">
                            {{ $order->order_number }}
                        </a>
                    </td>

                    {{-- Client --}}
                    <td class="px-4 py-3.5">
                        <span class="text-[13px] font-medium text-[#002352]">{{ $order->customer_name }}</span>
                    </td>

                    {{-- Téléphone --}}
                    <td class="px-4 py-3.5">
                        <a href="tel:{{ $order->customer_phone }}" class="text-[13px] text-[#5d5f5f] hover:text-[#18396e]">
                            {{ $order->customer_phone }}
                        </a>
                    </td>

                    {{-- Wilaya --}}
                    <td class="px-4 py-3.5">
                        <span class="text-[13px] text-[#5d5f5f]">{{ $order->wilaya->name ?? '—' }}</span>
                    </td>

                    {{-- Total --}}
                    <td class="px-4 py-3.5 text-right">
                        <span class="text-[13px] font-semibold text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
                    </td>

                    {{-- Statut --}}
                    <td class="px-4 py-3.5">
                        @php $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'color' => 'slate']; @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold
                            @if($order->status==='pending')   bg-amber-50 text-amber-700
                            @elseif($order->status==='confirmed') bg-blue-50 text-blue-700
                            @elseif($order->status==='shipped')  bg-violet-50 text-violet-700
                            @elseif($order->status==='delivered') bg-emerald-50 text-emerald-700
                            @else bg-red-50 text-red-700
                            @endif">
                            <span class="w-1.5 h-1.5 rounded-full
                                @if($order->status==='pending')   bg-amber-500
                                @elseif($order->status==='confirmed') bg-blue-500
                                @elseif($order->status==='shipped')  bg-violet-500
                                @elseif($order->status==='delivered') bg-emerald-500
                                @else bg-red-500
                                @endif"></span>
                            {{ $sc['label'] }}
                        </span>
                    </td>

                    {{-- Date --}}
                    <td class="px-4 py-3.5">
                        <span class="text-[12px] text-[#5d5f5f]">{{ $order->created_at?->format('d/m/Y H:i') }}</span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3.5">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Voir --}}
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="p-1.5 text-[#747780] hover:text-[#18396e] hover:bg-[#f2f4f6] rounded-lg transition-all"
                               title="Voir la commande">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </a>

                            {{-- Changement statut rapide --}}
                            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}"
                                  class="flex items-center gap-1" id="status-form-{{ $order->id }}">
                                @csrf @method('PATCH')
                                <select name="status"
                                        onchange="this.form.submit()"
                                        class="text-[11px] text-[#5d5f5f] bg-[#f2f4f6] border-none rounded-lg px-2 py-1.5 cursor-pointer outline-none hover:bg-[#e5e7eb] transition-colors">
                                    @foreach($statusConfig as $sk => $scfg)
                                    <option value="{{ $sk }}" {{ $order->status === $sk ? 'selected' : '' }}>
                                        {{ $scfg['label'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-[#f2f4f6] rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                                </svg>
                            </div>
                            <p class="text-[14px] font-semibold text-[#43474f]">Aucune commande trouvée</p>
                            <p class="text-[12px] text-[#747780] mt-1">Modifiez vos filtres ou attendez de nouvelles commandes</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-[#f2f4f6] flex items-center justify-between">
        <p class="text-[12px] text-[#747780]">
            Affichage {{ $orders->firstItem() }}–{{ $orders->lastItem() }} sur {{ $orders->total() }}
        </p>
        <div class="flex items-center gap-1">
            @if($orders->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg text-[12px] text-[#c4c6d1] cursor-not-allowed">← Précédent</span>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-[12px] text-[#5d5f5f] hover:bg-[#f2f4f6] transition-colors">← Précédent</a>
            @endif

            @foreach($orders->getUrlRange(max(1,$orders->currentPage()-2), min($orders->lastPage(),$orders->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="w-8 h-8 flex items-center justify-center rounded-lg text-[12px] font-medium transition-colors
                      {{ $page === $orders->currentPage() ? 'bg-[#002352] text-white' : 'text-[#5d5f5f] hover:bg-[#f2f4f6]' }}">
                {{ $page }}
            </a>
            @endforeach

            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-[12px] text-[#5d5f5f] hover:bg-[#f2f4f6] transition-colors">Suivant →</a>
            @else
                <span class="px-3 py-1.5 rounded-lg text-[12px] text-[#c4c6d1] cursor-not-allowed">Suivant →</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection
