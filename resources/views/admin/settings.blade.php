@extends('layouts.admin')

@use(Illuminate\Support\Facades\Storage)

@section('title', 'Paramètres')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#18396e] tracking-tight">Paramètres</h1>
        <p class="text-slate-500 text-sm mt-1">Informations boutique, modes de paiement et conditions de vente.</p>
    </div>

    {{-- Flash success --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl px-5 py-3.5 text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── Section 1 : Informations boutique ── --}}
        <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#edeef0] flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#e8edf5] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/>
                    </svg>
                </div>
                <h2 class="text-[14px] font-semibold text-[#18396e]">Informations de la boutique</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Nom --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nom de la boutique <span class="text-red-500">*</span></label>
                    <input type="text" name="shop_name" value="{{ old('shop_name', $settings['shop_name']) }}"
                           class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e] @error('shop_name') border-red-400 @enderror">
                    @error('shop_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Téléphone --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Téléphone</label>
                    <input type="text" name="shop_phone" value="{{ old('shop_phone', $settings['shop_phone']) }}"
                           class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Email</label>
                    <input type="email" name="shop_email" value="{{ old('shop_email', $settings['shop_email']) }}"
                           class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e] @error('shop_email') border-red-400 @enderror">
                    @error('shop_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Adresse --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Adresse</label>
                    <input type="text" name="shop_address" value="{{ old('shop_address', $settings['shop_address']) }}"
                           class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                </div>

                {{-- Logo --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">Logo de la boutique</label>
                    <div class="flex flex-col sm:flex-row items-start gap-5 p-4 bg-slate-50 border border-[#dde1ea] rounded-2xl">
                        {{-- Aperçu logo actuel --}}
                        <div class="flex-shrink-0">
                            @if(!empty($settings['shop_logo']))
                                <img src="{{ Storage::url($settings['shop_logo']) }}"
                                     id="logoPreview"
                                     alt="Logo actuel"
                                     class="h-20 w-auto max-w-[140px] object-contain rounded-xl border border-[#dde1ea] bg-white p-2 shadow-sm">
                            @else
                                <div id="logoPreviewEmpty" class="h-20 w-20 rounded-xl border-2 border-dashed border-[#dde1ea] bg-white flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <label for="logoInput" class="inline-flex items-center gap-2 cursor-pointer px-4 py-2 bg-white border border-[#dde1ea] rounded-xl text-[13px] font-medium text-slate-700 hover:bg-slate-100 transition shadow-sm">
                                <svg class="w-4 h-4 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                                Choisir un logo
                            </label>
                            <input type="file" name="logo" id="logoInput" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="sr-only"
                                   onchange="previewLogo(this)">
                            <p class="text-xs text-slate-400 mt-2">JPG, PNG, GIF ou WEBP — max 2 Mo.</p>
                            @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            @if(!empty($settings['shop_logo']))
                            <label class="flex items-center gap-2 mt-3 cursor-pointer select-none">
                                <input type="checkbox" name="remove_logo" id="remove_logo" class="w-4 h-4 rounded border-slate-300 text-red-500 focus:ring-red-400">
                                <span class="text-xs text-red-500 font-medium">Supprimer le logo actuel</span>
                            </label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Section 2 : Modes de paiement ── --}}
        <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#edeef0] flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#e8edf5] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                </div>
                <h2 class="text-[14px] font-semibold text-[#18396e]">Modes de paiement</h2>
            </div>
            <div class="p-6 flex flex-col gap-4">
                {{-- COD --}}
                <label class="flex items-center justify-between gap-4 cursor-pointer">
                    <div>
                        <p class="text-[13px] font-semibold text-slate-800">Paiement à la livraison (COD)</p>
                        <p class="text-xs text-slate-500 mt-0.5">Le client règle en espèces à la réception du colis.</p>
                    </div>
                    <div class="relative inline-block">
                        <input type="checkbox" name="cod_enabled" id="cod_enabled" class="sr-only peer" {{ $settings['cod_enabled'] === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-checked:bg-[#18396e] rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </div>
                </label>

                <hr class="border-[#edeef0]">

                {{-- BaridiMob --}}
                <label class="flex items-center justify-between gap-4 cursor-pointer">
                    <div>
                        <p class="text-[13px] font-semibold text-slate-800">BaridiMob</p>
                        <p class="text-xs text-slate-500 mt-0.5">Paiement mobile via Algérie Poste BaridiMob.</p>
                    </div>
                    <div class="relative inline-block">
                        <input type="checkbox" name="baridimob_enabled" id="baridimob_enabled" class="sr-only peer" {{ $settings['baridimob_enabled'] === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-checked:bg-[#18396e] rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </div>
                </label>

                <hr class="border-[#edeef0]">

                {{-- CIB --}}
                <label class="flex items-center justify-between gap-4 cursor-pointer">
                    <div>
                        <p class="text-[13px] font-semibold text-slate-800">Carte CIB / Dahabia</p>
                        <p class="text-xs text-slate-500 mt-0.5">Paiement en ligne par carte bancaire.</p>
                    </div>
                    <div class="relative inline-block">
                        <input type="checkbox" name="cib_enabled" id="cib_enabled" class="sr-only peer" {{ $settings['cib_enabled'] === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-checked:bg-[#18396e] rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </div>
                </label>
            </div>
        </div>

        {{-- ── Section 3 : Conditions de vente ── --}}
        <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#edeef0] flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#e8edf5] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-[14px] font-semibold text-[#18396e]">Conditions de vente</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ce texte sera affiché sur la page Conditions de vente visible par les clients.</p>
                </div>
            </div>
            <div class="p-6">
                <textarea name="terms" rows="18"
                    placeholder="Rédigez ici vos conditions générales de vente. Chaque saut de ligne sera respecté à l'affichage."
                    class="w-full border border-[#dde1ea] rounded-xl px-4 py-3 text-[13px] text-slate-800 leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e] resize-y font-mono">{{ old('terms', $settings['terms']) }}</textarea>
                <p class="text-xs text-slate-400 mt-2">Le texte sera affiché avec les sauts de ligne préservés.</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-[#dde1ea] rounded-full hover:bg-slate-50 transition">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#18396e] hover:bg-[#0f2445] text-white text-sm font-semibold rounded-full shadow-md shadow-[#18396e]/20 transition-colors duration-150 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Enregistrer les paramètres
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
function previewLogo(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        // Replace or show the preview image
        let preview = document.getElementById('logoPreview');
        let empty   = document.getElementById('logoPreviewEmpty');
        if (!preview) {
            preview = document.createElement('img');
            preview.id = 'logoPreview';
            preview.alt = 'Aperçu';
            preview.className = 'h-20 w-auto max-w-[140px] object-contain rounded-xl border border-[#dde1ea] bg-white p-2 shadow-sm';
            if (empty) { empty.replaceWith(preview); }
        }
        preview.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
@endsection