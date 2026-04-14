@extends('layouts.admin')
@section('title', 'Clients')
@section('page-title', 'Clients')
@section('page-description', 'Gestion de la clientèle')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h2 class="text-xl font-bold text-[#18396e]">Clients</h2>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $clients->total() }} client{{ $clients->total() !== 1 ? 's' : '' }} enregistré{{ $clients->total() !== 1 ? 's' : '' }}
            </p>
        </div>
        <form method="GET" action="{{ route('admin.clients.index') }}" class="flex items-center gap-2">
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="q" value="{{ $search }}" placeholder="Nom, téléphone, email…"
                       class="pl-9 pr-4 py-2 border border-[#dde1ea] rounded-full text-[13px] w-60 bg-white focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#18396e] text-white text-[13px] font-medium rounded-full hover:bg-[#0f2445] transition-colors">
                Rechercher
            </button>
            @if($search)
            <a href="{{ route('admin.clients.index') }}" class="px-3 py-2 text-[13px] text-slate-500 border border-[#dde1ea] rounded-full hover:bg-slate-50 transition-colors">✕</a>
            @endif
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] overflow-hidden">
        @if($clients->isEmpty())
        <div class="py-24 flex flex-col items-center gap-4 text-center">
            <div class="w-16 h-16 rounded-2xl bg-[#e8edf5] flex items-center justify-center">
                <svg class="w-8 h-8 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-700 text-[15px]">Aucun client trouvé</p>
                <p class="text-sm text-slate-400 mt-1">
                    @if($search)Aucun résultat pour « {{ $search }} »@else Les clients apparaîtront ici après leur première commande.@endif
                </p>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#edeef0] bg-[#f8f9fb]">
                        @php
                            $th = "px-5 py-3.5 text-left text-[10.5px] font-bold uppercase tracking-wider text-[#747780]";
                            $sortLink = fn($col) => route('admin.clients.index', array_merge(request()->except('sort','dir','page'), ['sort'=>$col,'dir'=>($sort===$col&&$dir==='asc'?'desc':'asc')]));
                            $arrow = fn($col) => $sort === $col ? ($dir==='asc' ? ' ↑' : ' ↓') : '';
                        @endphp
                        <th class="{{ $th }}">
                            <a href="{{ $sortLink('name') }}" class="inline-flex items-center gap-1 hover:text-[#18396e] transition-colors">
                                Client <span class="text-[#18396e]">{{ $arrow('name') }}</span>
                            </a>
                        </th>
                        <th class="{{ $th }}">Téléphone</th>
                        <th class="{{ $th }}">Wilaya</th>
                        <th class="{{ $th }}">
                            <a href="{{ $sortLink('orders_count') }}" class="inline-flex items-center gap-1 hover:text-[#18396e] transition-colors">
                                Commandes <span class="text-[#18396e]">{{ $arrow('orders_count') }}</span>
                            </a>
                        </th>
                        <th class="{{ $th }}">Total dépensé</th>
                        <th class="{{ $th }}">Dernière commande</th>
                        <th class="{{ $th }}"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f1f3]">
                    @foreach($clients as $client)
                    <tr class="hover:bg-[#fafbfc] transition-colors cursor-pointer group"
                        onclick="window.location='{{ route('admin.clients.show', $client) }}'">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-[#e8edf5] to-[#d0d9eb]">
                                    <span class="text-[#18396e] font-bold text-[13px]">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 group-hover:text-[#18396e] transition-colors leading-tight">{{ $client->name }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $client->email ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $client->phone ?? '—' }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $client->wilaya?->name ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-full bg-[#e8edf5] text-[#18396e] font-bold text-[12px]">
                                {{ $client->orders_count }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-800">
                            {{ number_format((float)($client->total_spent ?? 0), 0, ',', ' ') }} <span class="font-normal text-slate-400 text-[12px]">DA</span>
                        </td>
                        <td class="px-5 py-4 text-slate-500">
                            {{ $client->last_order_at ? \Carbon\Carbon::parse($client->last_order_at)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-5 py-4" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.clients.show', $client) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#e8edf5] text-[#18396e] text-[12px] font-semibold rounded-full hover:bg-[#18396e] hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Voir
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($clients->hasPages())
        <div class="px-5 py-4 border-t border-[#edeef0]">
            {{ $clients->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection