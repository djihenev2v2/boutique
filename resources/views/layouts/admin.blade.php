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
                            600: '#253270',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link { position: relative; }
        .sidebar-link.active { background: rgba(255,255,255,0.07); color: #fff; }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 3px;
            background: #3B82F6;
            border-radius: 0 4px 4px 0;
        }
        .sidebar-link.active .sidebar-icon { color: #60A5FA; }
        .sidebar-link:not(.active):hover { background: rgba(255,255,255,0.04); color: #e2e8f0; }
        .sidebar-link:not(.active):hover .sidebar-icon { color: #94a3b8; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 10px; }
    </style>
    @livewireStyles
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased">

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-navy-950/70 z-40 lg:hidden hidden transition-opacity duration-200" onclick="toggleSidebar()"></div>

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-[260px] bg-navy-900 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out">

        {{-- Brand --}}
        <div class="h-16 flex items-center gap-3 px-6 border-b border-white/[0.06]">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/>
                </svg>
            </div>
            <span class="text-white font-bold text-[17px] tracking-tight">{{ config('app.name', 'Boutique') }}</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1">
            <p class="px-3 mb-3 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500/80">Menu</p>

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium text-slate-400 transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="sidebar-icon w-[20px] h-[20px] flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Produits --}}
            <a href="#"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium text-slate-400 transition-all duration-150 {{ request()->routeIs('admin.produits*') ? 'active' : '' }}">
                <svg class="sidebar-icon w-[20px] h-[20px] flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
                <span>Produits</span>
            </a>

            {{-- Commandes --}}
            <a href="#"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium text-slate-400 transition-all duration-150 {{ request()->routeIs('admin.commandes*') ? 'active' : '' }}">
                <svg class="sidebar-icon w-[20px] h-[20px] flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                </svg>
                <span>Commandes</span>
            </a>

            {{-- Clients --}}
            <a href="#"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium text-slate-400 transition-all duration-150 {{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
                <svg class="sidebar-icon w-[20px] h-[20px] flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                <span>Clients</span>
            </a>

            <p class="px-3 mt-6 mb-3 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500/80">Gestion</p>

            {{-- Livraison --}}
            <a href="#"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium text-slate-400 transition-all duration-150 {{ request()->routeIs('admin.livraison*') ? 'active' : '' }}">
                <svg class="sidebar-icon w-[20px] h-[20px] flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.143-.504 1.125-1.125a17.902 17.902 0 00-3.213-9.174L16.5 3.676A1.125 1.125 0 0015.443 3H12.75v14.25m0 0h-3m3 0h3m-6 0V5.625m0 12.625H5.625"/>
                </svg>
                <span>Livraison</span>
            </a>

            {{-- Marketing --}}
            <a href="#"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium text-slate-400 transition-all duration-150 {{ request()->routeIs('admin.marketing*') ? 'active' : '' }}">
                <svg class="sidebar-icon w-[20px] h-[20px] flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                <span>Marketing</span>
            </a>

            {{-- Paramètres --}}
            <a href="#"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium text-slate-400 transition-all duration-150 {{ request()->routeIs('admin.parametres*') ? 'active' : '' }}">
                <svg class="sidebar-icon w-[20px] h-[20px] flex-shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Paramètres</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="border-t border-white/[0.06] px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-blue-400 text-sm font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-slate-500">Administrateur</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-500 hover:text-red-400 transition-colors duration-150" title="Déconnexion">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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
        <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 text-slate-400 hover:text-navy-900 transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-[15px] font-semibold text-navy-900 leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-[11px] text-slate-400 leading-tight hidden sm:block">@yield('page-description', '')</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @yield('header-actions')
                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-navy-900 rounded-lg flex items-center justify-center">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[13px] font-medium text-slate-700 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-400 leading-tight">Admin</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-4 lg:p-8">
            @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
