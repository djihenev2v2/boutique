@extends('layouts.admin')

@section('title', 'Modifier ' . $product->name)
@section('page-title', 'Produits')
@section('page-description', 'Modifier un produit')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-[#747780] mb-4">
    <span>Catalogue</span>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <a href="{{ route('admin.products.index') }}" class="hover:text-[#002352] transition-colors">Produits</a>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="text-[#002352]">Modifier</span>
</nav>

<div class="flex items-center gap-4 mb-6">
    <h1 class="text-[26px] font-bold text-[#002352] tracking-tight truncate">{{ $product->name }}</h1>
    <span class="flex-shrink-0 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full
        {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-[#f2f4f6] text-[#747780]' }}">
        {{ $product->is_active ? 'Actif' : 'Inactif' }}
    </span>
</div>

@include('admin.products._form', [
    'formAction' => route('admin.products.update', $product),
    'formMethod' => 'PATCH',
])

@endsection

@push('scripts')
<script type="module" src="{{ asset('js/modules/products.js') }}"></script>
@endpush
