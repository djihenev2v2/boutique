@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-500 mt-1">Bienvenue, {{ Auth::user()->name }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">💰</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Chiffre d'affaires</p>
                <p class="text-xl font-bold text-gray-900">0 DA</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">📦</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Commandes du jour</p>
                <p class="text-xl font-bold text-gray-900">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">👥</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Clients</p>
                <p class="text-xl font-bold text-gray-900">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <span class="text-2xl">⚠️</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Produits en rupture</p>
                <p class="text-xl font-bold text-gray-900">0</p>
            </div>
        </div>
    </div>
</div>
@endsection
