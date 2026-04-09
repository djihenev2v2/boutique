@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Bienvenue, {{ Auth::user()->name }} !</h1>
    <p class="text-gray-500 mt-1">Découvrez nos produits et passez vos commandes.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
    </svg>
    <h2 class="text-lg font-semibold text-gray-700 mt-4">Boutique en construction</h2>
    <p class="text-gray-500 mt-2">Le catalogue sera bientôt disponible.</p>
</div>
@endsection
