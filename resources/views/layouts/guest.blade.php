@php
    $shopName = \App\Models\Setting::get('shop_name', config('app.name', 'Boutique'));
    $logoPath = \App\Models\Setting::get('shop_logo');
@endphp
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') — {{ $shopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/boutique.css') }}">
</head>
<body class="min-h-screen font-sans antialiased bg-[linear-gradient(135deg,#002352_0%,#18396e_100%)] relative overflow-hidden">

    <div class="pointer-events-none absolute top-[-10%] left-[-5%] w-80 h-80 sm:w-96 sm:h-96 bg-[#18396e]/35 rounded-full blur-[120px]"></div>
    <div class="pointer-events-none absolute bottom-[-12%] right-[-8%] w-[420px] h-[420px] sm:w-[500px] sm:h-[500px] bg-[#001a41]/35 rounded-full blur-[150px]"></div>

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 relative z-10">
        {{-- Card --}}
        <div class="w-full max-w-[880px] bg-white rounded-[2rem] shadow-[0px_40px_100px_rgba(0,0,0,0.3)] overflow-hidden flex">

            {{-- Left brand panel — desktop only --}}
            <div class="hidden lg:flex lg:w-[44%] bg-[#f2f4f6] border-r border-[#e1e2e4] flex-col justify-between p-10">

                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    @include('partials.shop-logo', [
                        'logoPath' => $logoPath,
                        'shopName' => $shopName,
                        'containerClass' => 'w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center shadow-md shadow-[#002352]/20',
                        'imageClass' => 'w-full h-full object-contain p-1.5',
                        'iconClass' => 'w-5 h-5 text-white',
                    ])
                    <span class="text-[#002352] font-bold text-[16px] tracking-tight">{{ $shopName }}</span>
                </div>

                {{-- Headline --}}
                <div>
                    <h2 class="text-[36px] font-extrabold leading-[1.1] tracking-tight mb-5">
                        <span class="text-[#002352]">L'excellence en</span><br>
                        <span class="text-[#27467b]">gestion marchande.</span>
                    </h2>
                    <p class="text-[#5d5f5f] text-[14px] leading-relaxed max-w-[280px]">
                        Accédez à votre suite d'outils analytiques et pilotez votre croissance avec une précision architecturale.
                    </p>
                </div>

                {{-- Bottom --}}
                <div>
                    <p class="text-[#616363] text-[10px] font-bold uppercase tracking-[0.15em]">Secured by Enterprise Trust ————</p>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 flex flex-col">
                {{-- Mobile header --}}
                <div class="lg:hidden px-7 pt-7 flex items-center gap-3">
                    @include('partials.shop-logo', [
                        'logoPath' => $logoPath,
                        'shopName' => $shopName,
                        'containerClass' => 'w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center shadow-md shadow-[#002352]/20',
                        'imageClass' => 'w-full h-full object-contain p-1',
                        'iconClass' => 'w-5 h-5 text-white',
                    ])
                    <span class="font-bold text-[#002352] text-[16px] tracking-tight">{{ $shopName }}</span>
                </div>

                {{-- Form area --}}
                <div class="flex-1 flex items-center justify-center px-7 sm:px-10 py-10">
                    <div class="w-full max-w-[360px]">
                        @yield('content')
                    </div>
                </div>

              
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
