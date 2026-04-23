@php
    use Illuminate\Support\Facades\Storage;
    $shopName  ??= config('app.name', 'Boutique');
    $logoPath  ??= null;
    $cartCount ??= 0;
    $content   ??= '';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions de vente — {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F8F6; }
        .cgv-content { color: #374151; line-height: 1.8; font-size: 14.5px; white-space: pre-wrap; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }
        .fade-up { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════════════ --}}
@include('client.partials.navbar')

{{-- ═══════════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════════ --}}
<div style="background:#001832; border-bottom:1px solid rgba(237,232,223,.18); position:relative; overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 2px 2px, rgba(255,255,255,.05) 1px, transparent 0);background-size:28px 28px;pointer-events:none;"></div>
    <div style="position:absolute; width:360px; height:360px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 65%); top:-180px; right:-30px; pointer-events:none;"></div>
    <div class="relative max-w-[1320px] mx-auto px-5 lg:px-8 py-10">
        <nav class="flex items-center gap-2 mb-3 text-[12px]" style="color:rgba(255,255,255,.5)">
            <a href="/" style="color:rgba(255,255,255,.5)">Accueil</a>
            <svg style="width:10px;height:10px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span style="color:rgba(237,232,223,.95)">Conditions de vente</span>
        </nav>
        <h1 class="text-[28px] lg:text-[34px] font-extrabold text-[#EDE8DF] tracking-tight">
            Conditions Générales de Vente
        </h1>
        <p style="color:rgba(255,255,255,.6);font-size:14px;font-weight:500;margin-top:4px">
            {{ $shopName }}
        </p>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════════════ --}}
<main class="max-w-[1320px] mx-auto px-5 lg:px-8 py-10">
    <div class="max-w-[800px] mx-auto">
        <div class="bg-white border border-[#EBEBEB] rounded-2xl p-8 lg:p-12 fade-up">
            @if($content)
                <div class="cgv-content">{{ $content }}</div>
            @else
                <div class="text-center py-16">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#F3F4F6">
                        <svg class="w-7 h-7 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <p class="text-[15px] font-semibold text-[#6B7280]">Aucune condition de vente disponible pour le moment.</p>
                </div>
            @endif
        </div>
    </div>
</main>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ --}}
@include('client.partials.catalogue-footer')

</body>
</html>
