<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') — {{ config('app.name', 'Boutique') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        navy: {
                            950: '#060B1F',
                            900: '#0B1437',
                            800: '#111C44',
                            700: '#1B2559',
                        }
                    }
                }
            }
        }
    </script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased bg-white">

    <div class="min-h-screen flex">
        {{-- Left brand panel — desktop only --}}
        <div class="hidden lg:flex lg:w-[42%] xl:w-[45%] bg-navy-900 relative flex-col items-center justify-center px-12 overflow-hidden">
            {{-- Subtle accent lines --}}
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
            <div class="absolute top-0 bottom-0 right-0 w-px bg-gradient-to-b from-transparent via-blue-500/10 to-transparent"></div>

            <div class="relative z-10 text-center max-w-xs">
                {{-- Logo --}}
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-8">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-tight mb-3">{{ config('app.name', 'Boutique') }}</h1>
                <div class="w-10 h-[2px] bg-blue-500 mx-auto mb-4"></div>
                <p class="text-slate-400 text-[15px] leading-relaxed">Gérez votre boutique en ligne simplement et efficacement</p>

                {{-- Decorative grid dots --}}
                <div class="mt-16 flex items-center justify-center gap-1.5 opacity-20">
                    @for($i = 0; $i < 5; $i++)
                    <div class="w-1 h-1 bg-blue-400 rounded-full"></div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Right form panel --}}
        <div class="flex-1 flex flex-col min-h-screen">
            {{-- Mobile header --}}
            <div class="lg:hidden bg-navy-900 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg tracking-tight">{{ config('app.name', 'Boutique') }}</span>
                </div>
            </div>

            {{-- Form area --}}
            <div class="flex-1 flex items-center justify-center px-5 sm:px-8 py-10">
                <div class="w-full max-w-[400px]">
                    @yield('content')
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 sm:px-8 py-4 text-center">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} {{ config('app.name', 'Boutique') }}. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
