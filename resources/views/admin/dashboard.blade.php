@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Vue d\'ensemble de votre activité')

@section('content')

{{-- Welcome row --}}
<div class="flex items-center justify-between mb-7">
    <div>
        <h2 class="text-xl font-bold text-[#18396e] tracking-tight">Bonjour, {{ Auth::user()->name }} 👋</h2>
        <p class="text-slate-400 text-[13px] mt-0.5">Voici le résumé de votre activité du jour</p>
    </div>
    <div class="hidden sm:flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shadow-sm">
        <svg class="w-4 h-4 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
        </svg>
        <span class="text-[13px] font-medium text-slate-600">{{ now()->isoFormat('D MMM YYYY') }}</span>
    </div>
</div>

{{-- KPI Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5 mb-8">

    {{-- Chiffre d'affaires --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-200 hover:shadow-[0_8px_32px_-4px_rgba(24,57,110,0.10)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-slate-400">Chiffre d'affaires</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-b-100">
                <svg class="w-5 h-5 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#18396e] leading-none">0 DA</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-emerald-600 bg-emerald-50 rounded-full px-2 py-0.5">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                +12.4%
            </span>
            <span class="text-[11px] text-slate-400">ce mois-ci</span>
        </div>
    </div>

    {{-- Commandes --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-200 hover:shadow-[0_8px_32px_-4px_rgba(24,57,110,0.10)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-slate-400">Commandes</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-orange-50">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#18396e] leading-none">0</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-emerald-600 bg-emerald-50 rounded-full px-2 py-0.5">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                +5.2%
            </span>
            <span class="text-[11px] text-slate-400">vs hier</span>
        </div>
    </div>

    {{-- Nouveaux clients --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-200 hover:shadow-[0_8px_32px_-4px_rgba(24,57,110,0.10)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-slate-400">Nouveaux clients</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-indigo-50">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#18396e] leading-none">0</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-slate-500 bg-slate-100 rounded-full px-2 py-0.5">— Stable</span>
        </div>
    </div>

    {{-- Produits en rupture --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all duration-200 hover:shadow-[0_8px_32px_-4px_rgba(24,57,110,0.10)] hover:-translate-y-0.5">
        <div class="flex items-start justify-between mb-4">
            <p class="text-[12px] font-semibold uppercase tracking-widest text-slate-400">En rupture</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-50">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
        </div>
        <p class="text-[28px] font-bold text-[#18396e] leading-none">0</p>
        <div class="flex items-center gap-1.5 mt-2">
            <span class="text-[11px] text-slate-400">À réapprovisionner</span>
        </div>
    </div>
</div>

{{-- Bottom grid --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Commandes récentes --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-[15px] font-bold text-[#18396e]">Commandes Récentes</h3>
            <a href="#" class="text-[12px] font-medium text-[#18396e] hover:underline">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Commande</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Client</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Date</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Total</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 bg-b-50">
                                    <svg class="w-7 h-7 text-[#18396e]/40" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                                    </svg>
                                </div>
                                <p class="text-[13.5px] font-semibold text-slate-500">Aucune commande pour l'instant</p>
                                <p class="text-[12px] text-slate-400 mt-1">Les nouvelles commandes apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right column --}}
    <div class="flex flex-col gap-5">

        {{-- Best-sellers --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="text-[15px] font-bold text-[#18396e]">Best-Sellers</h3>
                <button class="p-1.5 text-slate-400 hover:text-[#18396e] hover:bg-b-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                    </svg>
                </button>
            </div>
            <div class="px-5 py-4 space-y-3">
                {{-- Empty state --}}
                <div class="flex flex-col items-center py-6 text-center">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-2.5 bg-b-50">
                        <svg class="w-6 h-6 text-[#18396e]/40" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                        </svg>
                    </div>
                    <p class="text-[12.5px] text-slate-400">Aucun produit vendu</p>
                </div>
            </div>
        </div>

        {{-- Promo banner --}}
        <div class="rounded-2xl p-5 bg-[#18396e]">
            <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-2">Expansion</p>
            <h4 class="text-white font-bold text-[17px] leading-snug mb-4">Optimisez votre<br>inventaire avec l'IA</h4>
            <button class="bg-white/15 hover:bg-white/25 border border-white/20 text-white text-[12px] font-semibold px-4 py-2 rounded-lg transition-all">
                Découvrir
            </button>
        </div>
    </div>
</div>
@endsection
