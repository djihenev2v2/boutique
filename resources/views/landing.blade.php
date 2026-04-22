<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Boutique') }} — Bienvenue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #0a1628 0%, #18396e 45%, #1e4d9b 100%); }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(24,57,110,0.15); }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .float-anim { animation: float 4s ease-in-out infinite; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.7s ease-out forwards; }
        .fade-up-delay-1 { animation: fadeUp 0.7s ease-out 0.15s forwards; opacity: 0; }
        .fade-up-delay-2 { animation: fadeUp 0.7s ease-out 0.3s forwards; opacity: 0; }
        .fade-up-delay-3 { animation: fadeUp 0.7s ease-out 0.45s forwards; opacity: 0; }
    </style>
</head>
<body class="bg-[#f8f9fb] min-h-screen antialiased">

    {{-- ── NAVBAR ────────────────────────────────────────────── --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-[#edeef0] shadow-sm">
        <div class="max-w-6xl mx-auto px-6 h-[64px] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#002352] flex items-center justify-center shadow-md shadow-[#002352]/25">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/>
                    </svg>
                </div>
                <span class="text-[#18396e] font-bold text-[17px] tracking-tight">{{ config('app.name', 'Boutique') }}</span>
            </div>
            <div></div>
        </div>
    </nav>

    {{-- ── HERO ──────────────────────────────────────────────── --}}
    <section class="hero-gradient min-h-screen pt-[64px] flex items-center relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-20 -left-20 w-[400px] h-[400px] bg-white/5 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-white/[0.02] rounded-full"></div>
            {{-- Grid dots --}}
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 48px 48px;"></div>
        </div>

        <div class="max-w-6xl mx-auto px-6 py-20 relative z-10 grid lg:grid-cols-2 gap-16 items-center w-full">
            {{-- Left content --}}
            <div>
                <div class="fade-up inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-md shadow-emerald-400/50 animate-pulse"></span>
                    <span class="text-white/90 text-[12px] font-medium">Boutique en ligne · Algérie</span>
                </div>

                <h1 class="fade-up-delay-1 text-4xl lg:text-5xl font-extrabold text-white leading-[1.15] tracking-tight mb-5">
                    Découvrez notre
                    <span class="block text-transparent bg-clip-text" style="background: linear-gradient(90deg, #93c5fd, #60a5fa);">
                        Collection
                    </span>
                    exclusive
                </h1>

                <p class="fade-up-delay-2 text-white/65 text-[15px] leading-relaxed mb-8 max-w-md">
                    Des produits soigneusement sélectionnés, livrés partout en Algérie.
                </p>

                {{-- Trust badges --}}
                <div class="fade-up-delay-3 flex flex-wrap gap-5 mt-10">
                    <div class="flex items-center gap-2 text-white/60">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                        <span class="text-[12px] font-medium">Paiement sécurisé</span>
                    </div>
                    <div class="flex items-center gap-2 text-white/60">
                        <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                        </svg>
                        <span class="text-[12px] font-medium">Livraison dans les 58 wilayas</span>
                    </div>
                    <div class="flex items-center gap-2 text-white/60">
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.970c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        <span class="text-[12px] font-medium">Support disponible</span>
                    </div>
                </div>
            </div>

            {{-- Right illustration --}}
            <div class="hidden lg:flex justify-center items-center">
                <div class="relative float-anim">
                    {{-- Main card --}}
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-3xl p-8 w-[300px]">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-white/50 text-[11px] font-medium uppercase tracking-widest mb-1">Commande récente</p>
                                <p class="text-white font-bold text-[16px]">#1042</p>
                            </div>
                            <span class="bg-emerald-500/20 text-emerald-300 text-[11px] font-bold px-3 py-1 rounded-full border border-emerald-500/30">Livrée ✓</span>
                        </div>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xl bg-white/10"></div>
                                    <span class="text-white/80 text-[12px] font-medium">Produit Premium</span>
                                </div>
                                <span class="text-white font-semibold text-[13px]">2 400 DA</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xl bg-white/10"></div>
                                    <span class="text-white/80 text-[12px] font-medium">Article Tendance</span>
                                </div>
                                <span class="text-white font-semibold text-[13px]">1 850 DA</span>
                            </div>
                        </div>
                        <div class="border-t border-white/15 pt-4 flex justify-between items-center">
                            <span class="text-white/50 text-[12px]">Total</span>
                            <span class="text-white font-extrabold text-[18px]">4 250 DA</span>
                        </div>
                    </div>

                    {{-- Floating badge 1 --}}
                    <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl px-3 py-2 flex items-center gap-2">
                        <span class="text-emerald-500 text-[16px]">✓</span>
                        <div>
                            <p class="text-[10px] font-bold text-[#002352]">Commande confirmée</p>
                            <p class="text-[9px] text-[#747780]">il y a 2 minutes</p>
                        </div>
                    </div>

                    {{-- Floating badge 2 --}}
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl px-3 py-2 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-[#002352]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[#002352]">4.9/5 Avis clients</p>
                            <p class="text-[9px] text-[#747780]">+250 avis vérifiés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 80L1440 80L1440 40C1200 80 960 0 720 20C480 40 240 80 0 40L0 80Z" fill="#f8f9fb"/>
            </svg>
        </div>
    </section>

    {{-- ── FEATURES ──────────────────────────────────────────── --}}
    <section class="max-w-6xl mx-auto px-6 py-20">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-extrabold text-[#002352] mb-3">Pourquoi choisir notre boutique ?</h2>
            <p class="text-[#747780] text-[14px] max-w-md mx-auto">Une expérience d'achat simple, rapide et sécurisée</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z', 'title' => 'Commande facile', 'desc' => 'Ajoutez vos articles au panier et passez commande en quelques clics', 'color' => 'bg-blue-50 text-blue-600'],
                ['icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12', 'title' => 'Livraison 58 wilayas', 'desc' => 'Livraison disponible dans toutes les wilayas d\'Algérie à domicile', 'color' => 'bg-violet-50 text-violet-600'],
                ['icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z', 'title' => 'Paiement flexible', 'desc' => 'Paiement à la livraison (COD), BaridiMob ou CIB selon votre préférence', 'color' => 'bg-emerald-50 text-emerald-600'],
                ['icon' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z', 'title' => 'Suivi de commande', 'desc' => 'Suivez le statut de vos commandes en temps réel grâce à des notifications claires', 'color' => 'bg-amber-50 text-amber-600'],
            ] as $feat)
            <div class="card-hover bg-white rounded-2xl p-6 shadow-[0px_4px_20px_rgba(24,57,110,0.06)]">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4 {{ $feat['color'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feat['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-[#002352] text-[14px] mb-1.5">{{ $feat['title'] }}</h3>
                <p class="text-[#747780] text-[12px] leading-relaxed">{{ $feat['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── FOOTER ────────────────────────────────────────────── --}}
    <footer class="border-t border-[#edeef0] bg-white">
        <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-[#002352] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/>
                    </svg>
                </div>
                <span class="text-[#18396e] font-bold text-[14px]">{{ config('app.name', 'Boutique') }}</span>
            </div>
            <div class="flex items-center gap-6 text-[12px] text-[#747780]">
                <span>© {{ date('Y') }} — {{ config('app.name', 'Boutique') }}</span>
            </div>
        </div>
    </footer>

</body>
</html>