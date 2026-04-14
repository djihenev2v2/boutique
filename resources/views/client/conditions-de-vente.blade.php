@extends('layouts.app')

@section('title', 'Conditions de vente')
@section('page-title', 'Conditions de vente')
@section('page-description', 'Lisez nos conditions générales de vente')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header card --}}
    <div class="rounded-2xl bg-gradient-to-br from-[#002352] to-[#18396e] p-8 mb-6 shadow-[0px_20px_50px_rgba(24,57,110,0.20)] relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image:radial-gradient(circle at 70% 50%, white 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white leading-tight">Conditions de vente</h1>
                <p class="text-white/70 text-sm mt-1">Conditions générales applicables à toutes les commandes</p>
            </div>
        </div>
    </div>

    {{-- Content card --}}
    <div class="bg-white rounded-2xl shadow-[0px_20px_40px_rgba(24,57,110,0.06)] p-8">

        @if($terms)
            {{-- Render the HTML-safe text with preserved line breaks --}}
            <div class="prose prose-slate max-w-none text-[#43474f] text-[15px] leading-relaxed">
                {!! nl2br(e($terms)) !!}
            </div>
        @else
            {{-- Empty state --}}
            <div class="flex flex-col items-center text-center py-12">
                <div class="w-16 h-16 rounded-2xl bg-[#f2f4f6] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <p class="text-[15px] font-semibold text-[#43474f]">Aucune condition de vente n'a été définie.</p>
                <p class="text-[13px] text-[#747780] mt-1">Le commerçant n'a pas encore renseigné les conditions de vente.</p>
            </div>
        @endif

    </div>

    {{-- Back button --}}
    <div class="mt-6 flex justify-center">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[13px] font-medium text-[#27467b] hover:text-[#002352] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Retour à l'accueil
        </a>
    </div>

</div>
@endsection
