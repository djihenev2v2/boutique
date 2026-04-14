@extends('layouts.app')

@section('title', 'Accueil')
@section('page-title', 'Accueil')
@section('page-description', 'Bienvenue dans votre espace client')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-b-800">Bienvenue, {{ Auth::user()->name }} 👋</h1>
    <p class="text-slate-400 text-sm mt-1">Découvrez nos produits et passez vos commandes</p>
</div>

<div class="bg-white border border-b-200/25 rounded-2xl p-12 text-center shadow-sm">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto bg-b-800 shadow-lg shadow-b-800/20">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
        </svg>
    </div>
    <h2 class="text-base font-semibold text-b-800 mt-5">Boutique en construction</h2>
    <p class="text-sm text-slate-400 mt-1.5">Le catalogue sera bientôt disponible.</p>
</div>
@endsection
