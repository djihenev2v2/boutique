<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Accueil') — {{ config('app.name', 'Boutique') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
</head>
<body class="bg-[#f8f9fb] min-h-screen font-sans antialiased">

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-[#0f2445]/50 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar client --}}
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-[260px] bg-white/80 backdrop-blur-xl rounded-r-3xl flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out shadow-[0px_20px_40px_rgba(24,57,110,0.06)]">

        {{-- Brand --}}
        <div class="h-[68px] flex items-center gap-3 px-5 border-b border-[#edeef0]">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-[#002352] shadow-md shadow-[#002352]/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/>
                </svg>
            </div>
            <div>
                <p class="text-[#18396e] font-bold text-[15px] leading-tight tracking-tight">{{ config('app.name', 'Boutique') }}</p>
                <p class="text-slate-400 text-[10px] font-medium uppercase tracking-widest">Espace Client</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-0.5">
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.14em] text-[#747780]">Navigation</p>



            <a href="{{ route('catalogue') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('catalogue', 'product.show') ? 'bg-[#002352] text-white shadow-md' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('catalogue', 'product.show') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5A2.25 2.25 0 0118.75 19.5H5.25A2.25 2.25 0 013 17.25V6.75z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75h18"/>
                </svg>
                <span>Catalogue</span>
            </a>



            <a href="{{ route('cart.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('cart.*', 'checkout*', 'orders.confirmation') ? 'bg-[#002352] text-white shadow-md' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('cart.*', 'checkout*', 'orders.confirmation') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                <span class="flex-1">Panier</span>
                @php $cartCount = \App\Http\Controllers\Client\CartController::getCount(); @endphp
                @if($cartCount > 0)
                <span class="ml-auto text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center {{ request()->routeIs('cart.*', 'checkout*', 'orders.confirmation') ? 'bg-white text-[#002352]' : 'bg-[#002352] text-white' }}">{{ $cartCount }}</span>
                @endif
            </a>

            <a href="{{ route('orders.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('orders.index', 'orders.show') ? 'bg-[#002352] text-white shadow-md' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('orders.index', 'orders.show') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
                <span>Mes commandes</span>
            </a>

            <a href="{{ route('favoris.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('favoris.*') ? 'bg-[#002352] text-white shadow-md' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('favoris.*') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.239-4.5-5-4.5-1.876 0-3.51.93-4.337 2.306a5.84 5.84 0 00-.326.6 5.84 5.84 0 00-.326-.6C10.51 4.68 8.876 3.75 7 3.75c-2.761 0-5 2.015-5 4.5 0 7.22 9.337 12 9.337 12S21 15.47 21 8.25z"/>
                </svg>
                <span>Favoris</span>
            </a>

            <p class="px-3 mt-5 mb-2 text-[10px] font-bold uppercase tracking-[0.14em] text-[#747780]">Compte</p>

            <a href="{{ route('client.profile') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('client.profile') ? 'bg-[#002352] text-white shadow-md' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('client.profile') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                <span>Mon profil</span>
            </a>



            <a href="{{ route('terms') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('terms') ? 'bg-[#002352] text-white shadow-md' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('terms') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span>Conditions de vente</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="px-3 pb-4">
            <div class="flex items-center gap-3 bg-[#f2f4f6] rounded-2xl px-3 py-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-[#002352]">
                    <span class="text-white text-[13px] font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-[#002352] truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-[#5d5f5f]">Client</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50" title="Déconnexion">
                        <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="lg:ml-[260px] min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="h-[68px] bg-transparent flex items-center justify-between px-4 lg:px-12 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 text-slate-400 hover:text-[#18396e] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-[15px] font-semibold text-[#002352] leading-tight">@yield('page-title', 'Espace client')</h1>
                    <p class="text-[11px] text-[#5d5f5f] leading-tight hidden sm:block">@yield('page-description', 'Explorez, commandez et suivez vos achats')</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                @yield('header-actions')

                {{-- Search --}}
                <div class="hidden md:flex items-center gap-2 bg-[#f2f4f6] border-none rounded-full px-4 py-2 w-56 transition-colors">
                    <svg class="w-3.5 h-3.5 text-[#747780] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <span class="text-[12px] text-[#747780]">Rechercher un produit...</span>
                </div>

                {{-- Panier --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-400 hover:text-[#18396e] hover:bg-[#f2f4f6] rounded-full transition-all duration-150" title="Panier">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.837L5.61 7.5m0 0L6.75 13.5h10.69c.55 0 1.02-.374 1.137-.911l1.219-5.625a1.125 1.125 0 00-1.099-1.364H5.61zM6.75 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                    @if(isset($cartCount) && $cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#002352] text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>

                {{-- Notifications --}}
                <button class="relative p-2 text-slate-400 hover:text-[#18396e] hover:bg-[#f2f4f6] rounded-full transition-all duration-150" title="Notifications">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </button>

                <div class="w-px h-6 bg-[#c4c6d1]/30"></div>

                {{-- User --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-[#002352]">
                        <span class="text-white text-[12px] font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[13px] font-semibold text-[#002352] leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-[#5d5f5f] leading-tight">Compte client</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-5 lg:p-8">
            @if (session('success'))
            <div data-alert class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if (session('error'))
            <div data-alert class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                </svg>
                <p>{{ session('error') }}</p>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script type="module" src="{{ asset('js/client.js') }}"></script>
    @stack('scripts')
</body>
</html>
