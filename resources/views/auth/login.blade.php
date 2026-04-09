@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-8">
    {{-- Logo / Titre --}}
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Connexion</h1>
        <p class="text-sm text-gray-500 mt-1">Connectez-vous à votre compte</p>
    </div>

    {{-- Erreurs globales --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-700">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Email --}}
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-500 @enderror"
                placeholder="exemple@email.com"
            >
        </div>

        {{-- Mot de passe --}}
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('password') border-red-500 @enderror"
                placeholder="••••••••"
            >
        </div>

        {{-- Se souvenir de moi --}}
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
            </label>
        </div>

        {{-- Bouton connexion --}}
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Se connecter
        </button>
    </form>

    {{-- Lien inscription --}}
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            Pas encore inscrit ?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium">Créer un compte</a>
        </p>
    </div>
</div>
@endsection
