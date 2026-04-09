@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-description', 'Vue d\'ensemble de votre boutique')

@section('content')
<div class="mb-8">
    <h2 class="text-xl font-bold text-navy-900">Bonjour, {{ Auth::user()->name }}</h2>
    <p class="text-slate-400 text-sm mt-1">Voici le résumé de votre activité</p>
</div>

{{-- Stats grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5">

    {{-- Revenue --}}
    <div class="bg-white border border-slate-200 rounded-lg p-5">
        <div class="flex items-center justify-between mb-4">
            <p class="text-[13px] font-medium text-slate-500">Chiffre d'affaires</p>
            <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy-900">0 DA</p>
        <p class="text-[11px] text-slate-400 mt-1">Ce mois-ci</p>
    </div>

    {{-- Orders today --}}
    <div class="bg-white border border-slate-200 rounded-lg p-5">
        <div class="flex items-center justify-between mb-4">
            <p class="text-[13px] font-medium text-slate-500">Commandes du jour</p>
            <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy-900">0</p>
        <p class="text-[11px] text-slate-400 mt-1">Aujourd'hui</p>
    </div>

    {{-- Clients --}}
    <div class="bg-white border border-slate-200 rounded-lg p-5">
        <div class="flex items-center justify-between mb-4">
            <p class="text-[13px] font-medium text-slate-500">Clients</p>
            <div class="w-9 h-9 bg-violet-50 rounded-lg flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy-900">0</p>
        <p class="text-[11px] text-slate-400 mt-1">Total inscrits</p>
    </div>

    {{-- Out of stock --}}
    <div class="bg-white border border-slate-200 rounded-lg p-5">
        <div class="flex items-center justify-between mb-4">
            <p class="text-[13px] font-medium text-slate-500">Produits en rupture</p>
            <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-navy-900">0</p>
        <p class="text-[11px] text-slate-400 mt-1">À réapprovisionner</p>
    </div>
</div>

{{-- Recent orders placeholder --}}
<div class="mt-8 bg-white border border-slate-200 rounded-lg">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="text-sm font-semibold text-navy-900">Commandes récentes</h3>
    </div>
    <div class="px-5 py-12 text-center">
        <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
        </svg>
        <p class="text-sm text-slate-400 mt-3">Aucune commande pour le moment</p>
    </div>
</div>
@endsection
