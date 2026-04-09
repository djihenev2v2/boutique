<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Boutique') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        b: {
                            900: '#0f2445',
                            800: '#18396e',
                            700: '#1e4a8a',
                            600: '#2a5fa8',
                            100: '#e8f0fb',
                            50:  '#f0f5ff',
                        }
                    }
                }
            }
        }
    </script>
    @livewireStyles
</head>
<body class="bg-[#f4f7fc] min-h-screen font-sans antialiased">

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-[#18396e]/40 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-[260px] bg-white border-r border-slate-200 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out shadow-sm">

        {{-- Brand --}}
        <div class="h-[68px] flex items-center gap-3 px-5 border-b border-slate-100">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-[#18396e]">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/>
                </svg>
            </div>
            <div>
                <p class="text-[#18396e] font-bold text-[15px] leading-tight tracking-tight">{{ config('app.name', 'Boutique') }}</p>
                <p class="text-slate-400 text-[10px] font-medium uppercase tracking-widest">Management Suite</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-0.5">
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Principal</p>

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Commandes --}}
            <a href="#"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.commandes*') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.commandes*') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
                <span>Commandes</span>
            </a>

            {{-- Produits --}}
            <a href="#"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.produits*') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.produits*') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
                <span>Produits</span>
            </a>

            {{-- Catégories --}}
            <a href="#"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.categories*') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.categories*') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                </svg>
                <span>Catégories</span>
            </a>

            {{-- Clients --}}
            <a href="#"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.clients*') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.clients*') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                <span>Clients</span>
            </a>

            <p class="px-3 mt-5 mb-2 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Gestion</p>

            {{-- Livraison --}}
            <a href="#"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.livraison*') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.livraison*') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.143-.504 1.125-1.125a17.902 17.902 0 00-3.213-9.174L16.5 3.676A1.125 1.125 0 0015.443 3H12.75v14.25m0 0h-3m3 0h3m-6 0V5.625m0 12.625H5.625"/>
                </svg>
                <span>Livraison</span>
            </a>

            {{-- Marketing --}}
            <a href="#"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.marketing*') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.marketing*') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                <span>Marketing</span>
            </a>

            {{-- Paramètres --}}
            <a href="#"
               class="group flex items-center gap-3 px-3 py-[9px] rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('admin.parametres*') ? 'bg-[#18396e] text-white' : 'text-slate-600 hover:bg-b-50 hover:text-[#18396e]' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('admin.parametres*') ? 'text-white' : 'text-slate-400 group-hover:text-[#18396e]' }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Paramètres</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="border-t border-slate-100 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-[#18396e]">
                    <span class="text-white text-[13px] font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-slate-700 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-slate-400">Administrateur</p>
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
        <header class="h-[68px] bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30 shadow-[0_1px_3px_0_rgba(24,57,110,0.04)]">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 text-slate-400 hover:text-[#18396e] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-[15px] font-semibold text-[#18396e] leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-[11px] text-slate-400 leading-tight hidden sm:block">@yield('page-description', '')</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                @yield('header-actions')

                {{-- Search --}}
                <div class="hidden md:flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 w-52 hover:border-[#18396e]/30 transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <span class="text-[12px] text-slate-400">Rechercher...</span>
                </div>

                {{-- Notifications --}}
                <button class="relative p-2 text-slate-400 hover:text-[#18396e] hover:bg-b-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </button>

                <div class="w-px h-6 bg-slate-200"></div>

                {{-- User --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-[#18396e]">
                        <span class="text-white text-[12px] font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[13px] font-semibold text-slate-700 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-400 leading-tight">Admin Premium</p>
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

    <script>
    function toggleSidebar() {
        var s = document.getElementById('sidebar');
        var o = document.getElementById('sidebarOverlay');
        if (s.classList.contains('-translate-x-full')) {
            s.classList.remove('-translate-x-full');
            o.classList.remove('hidden');
        } else {
            s.classList.add('-translate-x-full');
            o.classList.add('hidden');
        }
    }
    </script>
    @livewireScripts
</body>
</html>
