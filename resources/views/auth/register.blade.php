@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-8">
    {{-- Logo / Titre --}}
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Créer un compte</h1>
        <p class="text-sm text-gray-500 mt-1">Inscrivez-vous pour passer vos commandes</p>
    </div>

    {{-- Erreurs globales --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        {{-- Nom complet --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('name') border-red-500 @enderror"
                placeholder="Votre nom complet"
            >
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-500 @enderror"
                placeholder="exemple@email.com"
            >
        </div>

        {{-- Téléphone --}}
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                required
                autocomplete="tel"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('phone') border-red-500 @enderror"
                placeholder="05 XX XX XX XX"
            >
        </div>

        {{-- Mot de passe --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('password') border-red-500 @enderror"
                placeholder="Minimum 8 caractères"
            >
        </div>

        {{-- Confirmation mot de passe --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="Retapez votre mot de passe"
            >
        </div>

        {{-- Bouton inscription --}}
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Créer mon compte
        </button>
    </form>

    {{-- Lien connexion --}}
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">Se connecter</a>
        </p>
    </div>
</div>
@endsection
