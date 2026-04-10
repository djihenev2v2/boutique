<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') — {{ config('app.name', 'Boutique') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/boutique.css') }}">
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased bg-[#18396e]">

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6">
        {{-- Card --}}
        <div class="w-full max-w-[880px] bg-white rounded-2xl shadow-[0_25px_80px_-10px_rgba(15,36,69,0.45)] overflow-hidden flex">

            {{-- Left brand panel — desktop only --}}
            <div class="hidden lg:flex lg:w-[44%] bg-[#f5f6f9] border-r border-slate-200 flex-col justify-between p-10">

                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#18396e] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/>
                        </svg>
                    </div>
                    <span class="text-[#18396e] font-bold text-[16px] tracking-tight">{{ config('app.name', 'Boutique') }}</span>
                </div>

                {{-- Headline --}}
                <div>
                    <h2 class="text-[36px] font-extrabold leading-[1.1] tracking-tight mb-5">
                        <span class="text-[#1a2540]">L'excellence en</span><br>
                        <span class="text-[#18396e]">gestion marchande.</span>
                    </h2>
                    <p class="text-slate-500 text-[14px] leading-relaxed max-w-[280px]">
                        Accédez à votre suite d'outils analytiques et pilotez votre croissance avec une précision architecturale.
                    </p>
                </div>

                {{-- Bottom --}}
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.15em]">Secured by Enterprise Trust ————</p>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 flex flex-col">
                {{-- Mobile header --}}
                <div class="lg:hidden px-7 pt-7 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#18396e] flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-white w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614"/>
                        </svg>
                    </div>
                    <span class="font-bold text-[#18396e] text-[16px] tracking-tight">{{ config('app.name', 'Boutique') }}</span>
                </div>

                {{-- Form area --}}
                <div class="flex-1 flex items-center justify-center px-7 sm:px-10 py-10">
                    <div class="w-full max-w-[360px]">
                        @yield('content')
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-7 pb-6 text-center">
                    <p class="text-[11px] text-slate-400 uppercase tracking-widest">© {{ date('Y') }} {{ config('app.name', 'Boutique') }} • Digital Ecosystem V4.2</p>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    @livewireScripts
</body>
</html>
