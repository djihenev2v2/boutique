@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div>
    <h1 class="text-[28px] font-bold text-[#191c1e] tracking-tight leading-tight">Créer votre compte</h1>
    <p class="text-[#5d5f5f] text-[13.5px] mt-1.5 mb-7">Rejoignez notre espace marchand en quelques secondes</p>

    @if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-[13px] px-4 py-3 rounded-xl">
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
            <label for="name" class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-2">Nom complet</label>
            <input
                type="text" id="name" name="name"
                value="{{ old('name') }}" required autofocus autocomplete="name"
                class="w-full px-3.5 py-[11px] border {{ $errors->has('name') ? 'bg-red-50 border-red-400' : 'bg-[#f2f4f6] border-[#e1e2e4]' }} rounded-[10px] text-[13.5px] text-[#191c1e] placeholder:text-[#747780]/70 transition-colors duration-150 focus:outline-none focus:border-[#002352] focus:bg-white"
                placeholder="Votre nom complet"
            >
        </div>

        <div class="mb-4">
            <label for="email" class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-2">Adresse Email</label>
            <input
                type="email" id="email" name="email"
                value="{{ old('email') }}" required autocomplete="email"
                class="w-full px-3.5 py-[11px] border {{ $errors->has('email') ? 'bg-red-50 border-red-400' : 'bg-[#f2f4f6] border-[#e1e2e4]' }} rounded-[10px] text-[13.5px] text-[#191c1e] placeholder:text-[#747780]/70 transition-colors duration-150 focus:outline-none focus:border-[#002352] focus:bg-white"
                placeholder="nom@entreprise.com"
            >
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-2">Téléphone</label>
            <input
                type="tel" id="phone" name="phone"
                value="{{ old('phone') }}" required autocomplete="tel"
                class="w-full px-3.5 py-[11px] border {{ $errors->has('phone') ? 'bg-red-50 border-red-400' : 'bg-[#f2f4f6] border-[#e1e2e4]' }} rounded-[10px] text-[13.5px] text-[#191c1e] placeholder:text-[#747780]/70 transition-colors duration-150 focus:outline-none focus:border-[#002352] focus:bg-white"
                placeholder="05 XX XX XX XX"
            >
        </div>

        <div class="grid grid-cols-2 gap-3 mb-6">
            <div>
                <label for="password" class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-2">Mot de Passe</label>
                <input
                    type="password" id="password" name="password"
                    required autocomplete="new-password"
                    class="w-full px-3.5 py-[11px] border {{ $errors->has('password') ? 'bg-red-50 border-red-400' : 'bg-[#f2f4f6] border-[#e1e2e4]' }} rounded-[10px] text-[13.5px] text-[#191c1e] placeholder:text-[#747780]/70 transition-colors duration-150 focus:outline-none focus:border-[#002352] focus:bg-white"
                    placeholder="Min. 8 car."
                >
            </div>
            <div>
                <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-widest text-[#616363] mb-2">Confirmation</label>
                <input
                    type="password" id="password_confirmation" name="password_confirmation"
                    required autocomplete="new-password"
                    class="w-full px-3.5 py-[11px] border bg-[#f2f4f6] border-[#e1e2e4] rounded-[10px] text-[13.5px] text-[#191c1e] placeholder:text-[#747780]/70 transition-colors duration-150 focus:outline-none focus:border-[#002352] focus:bg-white"
                    placeholder="Confirmez"
                >
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 flex items-center justify-center gap-2 bg-gradient-to-r from-[#002352] to-[#18396e] text-white font-semibold text-[14px] rounded-full shadow-[0px_10px_25px_rgba(0,35,82,0.2)] hover:shadow-[0px_15px_30px_rgba(0,35,82,0.3)] hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 cursor-pointer">
            Créer mon compte
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </button>
    </form>

    <div class="mt-7 pt-6 border-t border-[#edeef0] text-center">
        <p class="text-[13px] text-[#5d5f5f]">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="font-semibold text-[#27467b] hover:text-[#002352] transition-colors">Se connecter</a>
        </p>
    </div>
</div>
@endsection
