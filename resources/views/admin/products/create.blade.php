@extends('layouts.admin')

@section('title', 'Nouveau produit')
@section('page-title', 'Produits')
@section('page-description', 'Créer un nouveau produit')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-[#747780] mb-4">
    <span>Catalogue</span>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <a href="{{ route('admin.products.index') }}" class="hover:text-[#002352] transition-colors">Produits</a>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="text-[#002352]">Nouveau</span>
</nav>

<div class="flex items-center gap-4 mb-6">
    <h1 class="text-[26px] font-bold text-[#002352] tracking-tight">Créer un nouveau produit</h1>
</div>

@include('admin.products._form', [
    'formAction' => route('admin.products.store'),
    'formMethod' => 'POST',
])

@endsection

@push('scripts')
<script type="module" src="{{ asset('js/modules/products.js') }}"></script>
@endpush
