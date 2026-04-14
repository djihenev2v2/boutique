@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')
@section('page-description', 'Gérez vos informations personnelles')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    {{-- Section 1 : Informations personnelles --}}
    <div class="bg-white rounded-3xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-[#edeef0] flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#002352] flex items-center justify-center flex-shrink-0 shadow-lg shadow-[#002352]/20">
                <span class="text-white font-extrabold text-[22px]">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div>
                <h2 class="text-[15px] font-bold text-[#002352]">{{ Auth::user()->name }}</h2>
                <p class="text-[12px] text-[#747780]">{{ Auth::user()->email }}</p>
                <span class="inline-block mt-1 bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">Client</span>
            </div>
        </div>

        <div class="p-6">
            @if(session('success'))
            <div class="mb-4 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-[13px]">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('client.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid sm:grid-cols-2 gap-4">
                    {{-- Nom --}}
                    <div>
                        <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-widest mb-1.5">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                               class="w-full bg-[#f8f9fb] border {{ $errors->has('name') ? 'border-red-400' : 'border-[#edeef0]' }} rounded-xl px-4 py-2.5 text-[13px] text-[#002352] outline-none focus:ring-2 focus:ring-[#002352]/20 focus:border-[#002352] transition-all"
                               placeholder="Votre nom complet" required>
                        @error('name')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Téléphone --}}
                    <div>
                        <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-widest mb-1.5">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                               class="w-full bg-[#f8f9fb] border {{ $errors->has('phone') ? 'border-red-400' : 'border-[#edeef0]' }} rounded-xl px-4 py-2.5 text-[13px] text-[#002352] outline-none focus:ring-2 focus:ring-[#002352]/20 focus:border-[#002352] transition-all"
                               placeholder="0X XX XX XX XX">
                        @error('phone')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-widest mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                           class="w-full bg-[#f8f9fb] border {{ $errors->has('email') ? 'border-red-400' : 'border-[#edeef0]' }} rounded-xl px-4 py-2.5 text-[13px] text-[#002352] outline-none focus:ring-2 focus:ring-[#002352]/20 focus:border-[#002352] transition-all"
                           placeholder="votre@email.com" required>
                    @error('email')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#002352] text-white text-[13px] font-semibold rounded-full shadow-md shadow-[#002352]/20 hover:bg-[#18396e] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Section 2 : Changer le mot de passe --}}
    <div class="bg-white rounded-3xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#edeef0] flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-[#002352]">Sécurité</h2>
                <p class="text-[11px] text-[#747780]">Modifier votre mot de passe</p>
            </div>
        </div>

        <div class="p-6">
            @if(session('password_success'))
            <div class="mb-4 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-[13px]">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                {{ session('password_success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('client.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-widest mb-1.5">Mot de passe actuel</label>
                    <input type="password" name="current_password"
                           class="w-full bg-[#f8f9fb] border {{ $errors->has('current_password') ? 'border-red-400' : 'border-[#edeef0]' }} rounded-xl px-4 py-2.5 text-[13px] text-[#002352] outline-none focus:ring-2 focus:ring-[#002352]/20 focus:border-[#002352] transition-all"
                           placeholder="••••••••" required>
                    @error('current_password')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-widest mb-1.5">Nouveau mot de passe</label>
                        <input type="password" name="password"
                               class="w-full bg-[#f8f9fb] border {{ $errors->has('password') ? 'border-red-400' : 'border-[#edeef0]' }} rounded-xl px-4 py-2.5 text-[13px] text-[#002352] outline-none focus:ring-2 focus:ring-[#002352]/20 focus:border-[#002352] transition-all"
                               placeholder="Min. 8 caractères" required>
                        @error('password')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[#747780] uppercase tracking-widest mb-1.5">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-[#f8f9fb] border border-[#edeef0] rounded-xl px-4 py-2.5 text-[13px] text-[#002352] outline-none focus:ring-2 focus:ring-[#002352]/20 focus:border-[#002352] transition-all"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-500 text-white text-[13px] font-semibold rounded-full shadow-md shadow-amber-500/20 hover:bg-amber-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Section 3 : Compte --}}
    <div class="bg-white rounded-3xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] p-6">
        <h2 class="text-[13px] font-bold text-[#002352] mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#747780]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            Informations du compte
        </h2>
        <div class="grid sm:grid-cols-3 gap-4 text-[12px]">
            <div class="bg-[#f8f9fb] rounded-xl px-4 py-3">
                <p class="text-[#747780] font-medium mb-0.5">Membre depuis</p>
                <p class="text-[#002352] font-bold">{{ Auth::user()->created_at->format('M Y') }}</p>
            </div>
            <div class="bg-[#f8f9fb] rounded-xl px-4 py-3">
                <p class="text-[#747780] font-medium mb-0.5">Mes commandes</p>
                <a href="{{ route('orders.index') }}" class="text-[#18396e] font-bold hover:underline">
                    {{ Auth::user()->orders()->count() }} commande(s)
                </a>
            </div>
            <div class="bg-[#f8f9fb] rounded-xl px-4 py-3">
                <p class="text-[#747780] font-medium mb-0.5">Mes favoris</p>
                <a href="{{ route('favoris.index') }}" class="text-red-500 font-bold hover:underline">
                    {{ Auth::user()->favorites()->count() }} favori(s)
                </a>
            </div>
        </div>
    </div>

</div>

@endsection