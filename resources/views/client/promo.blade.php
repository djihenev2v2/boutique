@php
    use Illuminate\Support\Facades\Storage;
    $shopName  ??= config('app.name', 'Boutique');
    $logoPath  ??= null;
    $cartCount ??= 0;
    $promoCodes ??= collect();
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codes Promo � {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #F9F8F6; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }
        .fade-up { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
    </style>
</head>
<body>

@include('client.partials.navbar')

<div style="background:#001832; border-bottom:1px solid rgba(237,232,223,.18); position:relative; overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 2px 2px, rgba(255,255,255,.05) 1px, transparent 0);background-size:28px 28px;pointer-events:none;"></div>
    <div style="position:absolute; width:360px; height:360px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 65%); top:-180px; right:-30px; pointer-events:none;"></div>
    <div class="relative max-w-[1320px] mx-auto px-5 lg:px-8 py-10">
        <nav class="flex items-center gap-2 mb-3 text-[12px]" style="color:rgba(255,255,255,.5)">
            <a href="/" style="color:rgba(255,255,255,.5)">Accueil</a>
            <svg style="width:10px;height:10px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span style="color:rgba(237,232,223,.95)">Codes Promo</span>
        </nav>
        <h1 class="text-[28px] lg:text-[34px] font-extrabold text-[#EDE8DF] tracking-tight">Codes Promo</h1>
        <p style="color:rgba(255,255,255,.6);font-size:14px;font-weight:500;margin-top:4px">
            Copiez un code et appliquez-le lors du checkout pour b�n�ficier de la r�duction.
        </p>
    </div>
</div>

<main class="max-w-[1320px] mx-auto px-5 lg:px-8 py-10">
    <div class="max-w-[800px] mx-auto fade-up">
        @if($promoCodes->isEmpty())
            <div class="bg-white border border-[#EBEBEB] rounded-2xl p-12 text-center shadow-sm">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#F3F4F6">
                    <svg class="w-7 h-7 text-[#9CA3AF]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/>
                    </svg>
                </div>
                <p class="text-[15px] font-semibold text-[#374151]">Aucun code promo disponible</p>
                <p class="text-[13px] text-[#6B7280] mt-1">Revenez bient�t pour d�couvrir nos offres.</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($promoCodes as $promo)
                <div class="bg-white border border-[#EBEBEB] rounded-2xl shadow-sm overflow-hidden hover:shadow-md hover:border-[#b4c2d8] transition-all duration-200">
                    <div class="flex items-stretch">
                        <div class="w-1.5 bg-gradient-to-b from-[#002352] to-[#003D8F] flex-shrink-0"></div>
                        <div class="flex-1 p-5 flex items-center gap-4">
                            <div class="w-[68px] h-[68px] rounded-xl bg-[#EEF3FF] flex flex-col items-center justify-center flex-shrink-0 border border-[#DCE8FF]">
                                <span class="text-[20px] font-extrabold text-[#002352] leading-none">
                                    {{ $promo->is_percentage ? $promo->discount.'%' : number_format($promo->discount, 0) }}
                                </span>
                                <span class="text-[10px] font-bold text-[#27467b] uppercase tracking-wide mt-0.5">
                                    {{ $promo->is_percentage ? 'remise' : 'DA' }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                    <span class="font-mono text-[14px] font-bold text-[#002352] tracking-widest uppercase">{{ $promo->code }}</span>
                                    @if($promo->is_percentage)
                                        <span class="text-[10px] font-bold bg-[#DCFCE7] text-[#166534] px-2 py-0.5 rounded-full">-{{ $promo->discount }}%</span>
                                    @else
                                        <span class="text-[10px] font-bold bg-[#EEF3FF] text-[#002352] px-2 py-0.5 rounded-full">-{{ number_format($promo->discount, 0) }} DA</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-x-4 gap-y-0.5">
                                    @if($promo->min_order > 0)
                                        <p class="text-[12px] text-[#6B7280]">Min : <span class="font-semibold text-[#374151]">{{ number_format($promo->min_order, 0) }} DA</span></p>
                                    @endif
                                    @if($promo->max_uses)
                                        <p class="text-[12px] text-[#6B7280]">Utilisations : <span class="font-semibold text-[#374151]">{{ $promo->used_count }} / {{ $promo->max_uses }}</span></p>
                                    @endif
                                    @if($promo->expires_at)
                                        <p class="text-[12px] text-[#6B7280]">Expire : <span class="font-semibold text-[#374151]">{{ $promo->expires_at->format('d/m/Y') }}</span></p>
                                    @endif
                                </div>
                            </div>
                            <button onclick="copyCode('{{ $promo->code }}', this)"
                                class="flex-shrink-0 flex items-center gap-2 bg-[#002352] hover:bg-[#003D8F] text-white text-[12px] font-semibold px-4 py-2.5 rounded-xl transition-colors duration-150">
                                <svg class="w-4 h-4 icon-copy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/>
                                </svg>
                                <svg class="w-4 h-4 icon-check hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                                <span class="btn-label">Copier</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-[#002352] hover:bg-[#003D8F] text-white text-[13px] font-semibold px-6 py-3 rounded-full transition-colors shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 2.189a3.004 3.004 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
                    Aller au catalogue
                </a>
            </div>
        @endif
    </div>
</main>

@include('client.partials.catalogue-footer')

<script>
function copyCode(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
        const copyIcon = btn.querySelector('.icon-copy');
        const checkIcon = btn.querySelector('.icon-check');
        const label = btn.querySelector('.btn-label');
        copyIcon.classList.add('hidden');
        checkIcon.classList.remove('hidden');
        label.textContent = 'Copie !';
        btn.style.background = '#16a34a';
        setTimeout(() => {
            copyIcon.classList.remove('hidden');
            checkIcon.classList.add('hidden');
            label.textContent = 'Copier';
            btn.style.background = '';
        }, 2000);
    });
}
</script>
</body>
</html>
