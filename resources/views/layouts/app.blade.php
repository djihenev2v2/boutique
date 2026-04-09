<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Boutique') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Header --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('home') }}" class="text-xl font-bold text-blue-600">
                        {{ config('app.name', 'Boutique') }}
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ Auth::user()->isAdmin() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ Auth::user()->isAdmin() ? 'Admin' : 'Client' }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
