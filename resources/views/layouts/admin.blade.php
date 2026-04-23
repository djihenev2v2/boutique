@php
    $adminShopName = \App\Models\Setting::get('shop_name', config('app.name', 'Boutique'));
    $adminLogoPath = \App\Models\Setting::get('shop_logo');
@endphp
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $adminShopName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind.config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
</head>
<body class="bg-[#f8f9fb] min-h-screen font-sans antialiased">

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-[#0f2445]/50 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-[260px] bg-white/80 backdrop-blur-xl rounded-r-3xl flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out shadow-[0px_20px_40px_rgba(24,57,110,0.06)]">  

        {{-- Brand --}}
        <div class="h-[68px] flex items-center gap-3 px-5 border-b border-[#edeef0]">
            @include('partials.shop-logo', [
                'logoPath' => $adminLogoPath,
                'shopName' => $adminShopName,
                'containerClass' => 'w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 bg-[#002352] flex items-center justify-center shadow-md shadow-[#002352]/20',
                'imageClass' => 'w-full h-full object-contain p-1',
                'iconClass' => 'w-5 h-5 text-white',
            ])
            <div class="min-w-0">
                <p class="text-[#18396e] font-bold text-[15px] leading-tight tracking-tight truncate">{{ $adminShopName }}</p>
                <p class="text-slate-400 text-[10px] font-medium uppercase tracking-widest">Management Suite</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-0.5">
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.14em] text-[#747780]">Principal</p>

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-[#18396e] text-white shadow-lg' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Commandes --}}
            <a href="{{ route('admin.orders.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.orders*') ? 'bg-[#18396e] text-white shadow-lg' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.orders*') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
                <span>Commandes</span>
            </a>

            {{-- Produits --}}
            <a href="{{ route('admin.products.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.products*') ? 'bg-[#18396e] text-white shadow-lg' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.products*') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
                <span>Produits</span>
            </a>

            {{-- Catégories --}}
            <a href="{{ route('admin.categories.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.categories*') ? 'bg-[#18396e] text-white shadow-lg' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.categories*') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                </svg>
                <span>Catégories</span>
            </a>

            <p class="px-3 mt-5 mb-2 text-[10px] font-bold uppercase tracking-[0.14em] text-[#747780]">Gestion</p>

            {{-- Livraison --}}
            <a href="{{ route('admin.livraison.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.livraison*') ? 'bg-[#18396e] text-white shadow-lg' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.livraison*') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.143-.504 1.125-1.125a17.902 17.902 0 00-3.213-9.174L16.5 3.676A1.125 1.125 0 0015.443 3H12.75v14.25m0 0h-3m3 0h3m-6 0V5.625m0 12.625H5.625"/>
                </svg>
                <span>Livraison</span>
            </a>

            {{-- Codes Promo --}}
            <a href="{{ route('admin.marketing.index') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.marketing*') ? 'bg-[#18396e] text-white shadow-lg' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.marketing*') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-4.125-2.25-3.375 1.5-3.375-1.5-4.125 2.25V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.016h-.008V9.75zm5.625 4.5c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.016h-.008v-.016z"/>
                </svg>
                <span>Codes Promo</span>
            </a>

            {{-- Paramètres --}}
            <a href="{{ route('admin.settings') }}"
               class="group flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.settings*') ? 'bg-[#18396e] text-white shadow-lg' : 'text-[#5d5f5f] hover:bg-[#f2f4f6] hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.parametres*') ? 'text-white' : 'text-[#747780] group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Paramètres</span>
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
                    <p class="text-[11px] text-[#5d5f5f]">Administrateur</p>
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

    {{-- ═══════════════════════ MAIN AREA ═══════════════════════ --}}
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
                    <h1 class="text-[15px] font-semibold text-[#002352] leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-[11px] text-[#5d5f5f] leading-tight hidden sm:block">@yield('page-description', '')</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                @yield('header-actions')

                {{-- Search --}}
                <div class="hidden md:flex items-center gap-2 bg-[#f2f4f6] border-none rounded-full px-4 py-2 w-56 transition-colors">
                    <svg class="w-3.5 h-3.5 text-[#747780] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <span class="text-[12px] text-[#747780]">Rechercher...</span>
                </div>

                {{-- Notifications --}}
                <button class="relative p-2 text-slate-400 hover:text-[#18396e] hover:bg-[#f2f4f6] rounded-full transition-all duration-150">
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
                        <p class="text-[11px] text-[#5d5f5f] leading-tight">Admin Premium</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-5 lg:p-8">
            @if(session('success'))
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                </svg>
                <p>{{ session('error') }}</p>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script type="module" src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>
