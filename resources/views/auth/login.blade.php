@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div>
    <h1 class="text-[26px] font-bold text-navy-900 tracking-tight">Connexion</h1>
    <p class="text-slate-400 text-sm mt-1 mb-8">Entrez vos identifiants pour accéder à votre espace</p>

    @if ($errors->any())
    <div class="mb-6 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
        </svg>
        <p>{{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="mb-5">
            <label for="email" class="block text-[13px] font-medium text-slate-600 mb-1.5">Adresse email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-navy-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors @error('email') border-red-400 bg-red-50 @enderror"
                placeholder="exemple@email.com"
            >
        </div>

        <div class="mb-5">
            <label for="password" class="block text-[13px] font-medium text-slate-600 mb-1.5">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-navy-900 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-colors @error('password') border-red-400 bg-red-50 @enderror"
                placeholder="••••••••"
            >
        </div>

        <div class="flex items-center justify-between mb-7">
            <label class="flex items-center gap-2 cursor-pointer">
                <input
                    type="checkbox"
                    name="remember"
                    class="h-[15px] w-[15px] text-blue-600 border-slate-300 rounded focus:ring-blue-500"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <span class="text-[13px] text-slate-500 select-none">Se souvenir de moi</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-navy-900 hover:bg-navy-800 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Se connecter
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="text-sm text-slate-400">
            Pas encore inscrit ?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">Créer un compte</a>
        </p>
    </div>
</div>
@endsection
