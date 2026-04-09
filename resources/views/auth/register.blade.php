@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div>
    <h1 class="text-[26px] font-bold text-navy-900 tracking-tight">Créer un compte</h1>
    <p class="text-slate-400 text-sm mt-1 mb-8">Inscrivez-vous pour passer vos commandes</p>

    @if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
            <li class="flex items-start gap-2">
                <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $error }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-[13px] font-medium text-slate-600 mb-1.5">Nom complet</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-navy-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors @error('name') border-red-400 bg-red-50 @enderror"
                placeholder="Votre nom complet"
            >
        </div>

        <div class="mb-4">
            <label for="email" class="block text-[13px] font-medium text-slate-600 mb-1.5">Adresse email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-navy-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors @error('email') border-red-400 bg-red-50 @enderror"
                placeholder="exemple@email.com"
            >
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-[13px] font-medium text-slate-600 mb-1.5">Téléphone</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                required
                autocomplete="tel"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-navy-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors @error('phone') border-red-400 bg-red-50 @enderror"
                placeholder="05 XX XX XX XX"
            >
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="password" class="block text-[13px] font-medium text-slate-600 mb-1.5">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-navy-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors @error('password') border-red-400 bg-red-50 @enderror"
                    placeholder="Min. 8 caractères"
                >
            </div>
            <div>
                <label for="password_confirmation" class="block text-[13px] font-medium text-slate-600 mb-1.5">Confirmation</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-navy-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors"
                    placeholder="Retapez le mot de passe"
                >
            </div>
        </div>

        <button type="submit" class="w-full bg-navy-900 hover:bg-navy-800 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Créer mon compte
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="text-sm text-slate-400">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">Se connecter</a>
        </p>
    </div>
</div>
@endsection
