@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div>
    <h1 class="text-[28px] font-bold text-[#18396e] tracking-tight leading-tight">Bon retour parmi nous</h1>
    <p class="text-slate-400 text-[13.5px] mt-1.5 mb-8">Veuillez renseigner vos identifiants pour continuer</p>

    @if ($errors->any())
    <div class="mb-6 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-600 text-[13px] px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
        </svg>
        <p>{{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Email --}}
        <div class="mb-5">
            <label for="email" class="block text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-2">Adresse Email</label>
            <div class="relative">
                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <input
                    type="email" id="email" name="email"
                    value="{{ old('email') }}"
                    required autofocus autocomplete="email"
                    class="w-full pl-10 pr-3.5 py-[11px] border {{ $errors->has('email') ? 'bg-red-50 border-red-400' : 'bg-slate-50 border-slate-200' }} rounded-[10px] text-[13.5px] text-[#18396e] placeholder:text-slate-400 transition-colors duration-150 focus:outline-none focus:border-[#18396e] focus:bg-white"
                    placeholder="nom@entreprise.com"
                >
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-5">
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-[11px] font-bold uppercase tracking-widest text-slate-500">Mot de Passe</label>
                <a href="#" class="text-[12px] font-medium text-[#18396e] hover:text-[#1e4a8a] transition-colors">Oublié ?</a>
            </div>
            <div class="relative">
                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                </div>
                <input
                    type="password" id="password" name="password"
                    required autocomplete="current-password"
                    class="w-full pl-10 pr-10 py-[11px] border {{ $errors->has('password') ? 'bg-red-50 border-red-400' : 'bg-slate-50 border-slate-200' }} rounded-[10px] text-[13.5px] text-[#18396e] placeholder:text-slate-400 transition-colors duration-150 focus:outline-none focus:border-[#18396e] focus:bg-white"
                    placeholder="••••••••"
                >
                <button type="button" onclick="togglePwd()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#18396e] transition-colors">
                    <svg id="eyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Remember + submit --}}
        <div class="flex items-center mb-7">
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <div class="relative">
                    <input type="checkbox" name="remember" class="sr-only peer" {{ old('remember') ? 'checked' : '' }}>
                    <div class="w-4 h-4 rounded border-2 border-slate-300 peer-checked:border-[#18396e] peer-checked:bg-[#18396e] transition-all flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-white hidden peer-checked:block" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                </div>
                <span class="text-[13px] text-slate-500">Se souvenir de moi</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 flex items-center justify-center gap-2 bg-[#18396e] hover:bg-b-900 text-white font-semibold text-[14px] rounded-full transition-colors duration-150 cursor-pointer">
            Connecter
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </button>
    </form>

    <div class="mt-7 pt-6 border-t border-slate-100 text-center">
        <p class="text-[13px] text-slate-400 mb-3">Pas encore de compte marchand ?</p>
        <a href="{{ route('register') }}" class="inline-block px-6 py-2.5 rounded-full border border-slate-300 text-[13px] font-medium text-[#18396e] hover:border-[#18396e]/50 hover:bg-b-50 transition-colors">
            S'inscrire
        </a>
    </div>
</div>

@push('scripts')
<script type="module" src="{{ asset('js/auth.js') }}"></script>
@endpush
@endsection
