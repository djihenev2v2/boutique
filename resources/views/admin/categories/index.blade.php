@extends('layouts.admin')

@section('title', 'Catégories')
@section('page-title', 'Catégories')
@section('page-description', 'Gérez les catégories et sous-catégories de votre boutique')

@section('header-actions')
    <button onclick="openModal('modalAdd')"
            class="inline-flex items-center gap-2 bg-[#002352] text-white text-[12px] font-semibold px-4 py-2 rounded-full shadow-sm hover:bg-[#18396e] transition-all">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Ajouter une catégorie
    </button>
@endsection

@section('content')

<div class="bg-white rounded-2xl shadow-[0px_4px_20px_rgba(24,57,110,0.06)] overflow-hidden">

    {{-- Search bar --}}
    <div class="px-6 py-4 border-b border-[#f2f4f6] flex items-center gap-3">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2 flex-1 max-w-sm">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Rechercher une catégorie..."
                       class="w-full pl-9 pr-3 py-2 bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl border-none outline-none placeholder-[#9ca3af] focus:ring-2 focus:ring-[#002352]/20">
            </div>
            <button type="submit" class="bg-[#002352] text-white text-[12px] font-semibold px-4 py-2 rounded-xl hover:bg-[#18396e] transition-colors">
                Rechercher
            </button>
            @if($search)
            <a href="{{ route('admin.categories.index') }}"
               class="text-[12px] text-[#747780] hover:text-[#002352] font-medium px-2 py-2 rounded-xl hover:bg-[#f2f4f6] transition-colors whitespace-nowrap">
                Réinitialiser
            </a>
            @endif
        </form>
        <div class="ml-auto text-[12px] text-[#747780]">
            {{ $tree ? $tree->count() . ' catégorie(s) racine' : ($categories?->count() ?? 0) . ' résultat(s)' }}
        </div>
    </div>

    {{-- Categories tree / flat list --}}
    <div class="p-4 space-y-2">

        @if($search && $categories !== null)
            {{-- Flat filtered results --}}
            @forelse($categories as $cat)
                <div class="flex items-center gap-3 px-4 py-3 bg-[#f8f9fb] rounded-xl group hover:bg-[#f2f4f6] transition-colors">
                    <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-4 h-4 text-[#002352]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                        </svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-[#002352]">{{ $cat->name }}</p>
                        @if($cat->parent)
                        <p class="text-[11px] text-[#747780]">Sous-catégorie de : {{ $cat->parent->name }}</p>
                        @endif
                    </div>
                    <span class="text-[11px] text-[#747780] bg-white px-2.5 py-1 rounded-lg shadow-sm font-medium">
                        {{ $cat->products->count() }} produit(s)
                    </span>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->parent_id ?? '' }}')"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-[#002352] hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </button>
                        <button onclick="confirmDelete({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->products->count() }}, {{ $cat->children->count() }})"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-red-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-16 flex flex-col items-center gap-3 text-[#9ca3af]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <p class="text-[13px] font-medium">Aucune catégorie ne correspond à « {{ $search }} »</p>
                </div>
            @endforelse

        @elseif($tree !== null)
            {{-- Tree view --}}
            @forelse($tree as $parent)
                {{-- Parent category --}}
                <div class="rounded-xl overflow-hidden border border-[#f2f4f6]">
                    <div class="flex items-center gap-3 px-4 py-3 bg-[#f8f9fb] group hover:bg-[#f2f4f6] transition-colors">
                        <button onclick="toggleChildren('children-{{ $parent->id }}')"
                                class="w-6 h-6 flex items-center justify-center rounded-md text-[#747780] hover:bg-white transition-all flex-shrink-0">
                            <svg id="chevron-{{ $parent->id }}" class="w-3.5 h-3.5 transition-transform {{ $parent->children->isEmpty() ? 'opacity-0' : '' }}"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>
                        <span class="w-8 h-8 rounded-lg bg-[#002352] flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                            </svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-bold text-[#002352]">{{ $parent->name }}</p>
                            @if($parent->children->isNotEmpty())
                            <p class="text-[11px] text-[#747780]">{{ $parent->children->count() }} sous-catégorie(s)</p>
                            @endif
                        </div>
                        <span class="text-[11px] text-[#747780] bg-white px-2.5 py-1 rounded-lg shadow-sm font-medium">
                            {{ $parent->products->count() }} produit(s)
                        </span>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openEdit({{ $parent->id }}, '{{ addslashes($parent->name) }}', '')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-[#002352] hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                </svg>
                            </button>
                            <button onclick="confirmDelete({{ $parent->id }}, '{{ addslashes($parent->name) }}', {{ $parent->products->count() }}, {{ $parent->children->count() }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-red-600 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Children --}}
                    @if($parent->children->isNotEmpty())
                    <div id="children-{{ $parent->id }}" class="border-t border-[#f2f4f6] divide-y divide-[#f2f4f6]">
                        @foreach($parent->children as $child)
                        <div class="flex items-center gap-3 px-4 py-3 pl-14 bg-white group hover:bg-[#f8f9fb] transition-colors">
                            <span class="w-6 h-6 rounded-md bg-[#f2f4f6] flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-[#747780]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                                </svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-medium text-[#002352]">{{ $child->name }}</p>
                            </div>
                            <span class="text-[11px] text-[#747780] bg-[#f2f4f6] px-2.5 py-1 rounded-lg font-medium">
                                {{ $child->products->count() }} produit(s)
                            </span>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEdit({{ $child->id }}, '{{ addslashes($child->name) }}', '{{ $child->parent_id }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-[#002352] hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                </button>
                                <button onclick="confirmDelete({{ $child->id }}, '{{ addslashes($child->name) }}', {{ $child->products->count() }}, 0)"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[#747780] hover:bg-red-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @empty
                <div class="py-16 flex flex-col items-center gap-3 text-[#9ca3af]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                    </svg>
                    <p class="text-[13px] font-medium">Aucune catégorie créée pour le moment.</p>
                    <button onclick="openModal('modalAdd')" class="text-[12px] text-[#18396e] font-semibold hover:underline">
                        + Créer la première catégorie
                    </button>
                </div>
            @endforelse
        @endif
    </div>
</div>

{{-- ══════════════════════════ MODAL ADD ══════════════════════════ --}}
<div id="modalAdd" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/50 backdrop-blur-sm" onclick="closeModal('modalAdd')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[16px] font-bold text-[#002352]">Ajouter une catégorie</h3>
            <button onclick="closeModal('modalAdd')" class="w-8 h-8 flex items-center justify-center rounded-xl text-[#747780] hover:bg-[#f2f4f6] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Nom de la catégorie *</label>
                <input type="text" name="name" required maxlength="255"
                       placeholder="Ex: Chaussures Homme"
                       class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none placeholder-[#9ca3af] focus:ring-2 focus:ring-[#002352]/20">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Catégorie parente</label>
                <select name="parent_id"
                        class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none cursor-pointer">
                    <option value="">— Catégorie racine —</option>
                    @foreach($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalAdd')"
                        class="flex-1 bg-[#f2f4f6] text-[#5d5f5f] text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#edeef0] transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 bg-[#002352] text-white text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════ MODAL EDIT ══════════════════════════ --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/50 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[16px] font-bold text-[#002352]">Modifier la catégorie</h3>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 flex items-center justify-center rounded-xl text-[#747780] hover:bg-[#f2f4f6] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Nom de la catégorie *</label>
                <input id="editName" type="text" name="name" required maxlength="255"
                       class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none focus:ring-2 focus:ring-[#002352]/20">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-[#747780] uppercase tracking-widest mb-1.5">Catégorie parente</label>
                <select id="editParent" name="parent_id"
                        class="w-full bg-[#f2f4f6] text-[13px] text-[#002352] rounded-xl px-4 py-2.5 border-none outline-none cursor-pointer">
                    <option value="">— Catégorie racine —</option>
                    @foreach($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalEdit')"
                        class="flex-1 bg-[#f2f4f6] text-[#5d5f5f] text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#edeef0] transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 bg-[#002352] text-white text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#18396e] transition-colors shadow-sm">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════ MODAL DELETE ══════════════════════════ --}}
<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/50 backdrop-blur-sm" onclick="closeModal('modalDelete')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
        <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h3 class="text-[16px] font-bold text-[#002352] mb-2">Supprimer la catégorie ?</h3>
        <p id="deleteMessage" class="text-[12px] text-[#747780] mb-5"></p>
        <form id="deleteForm" method="POST" action="">
            @csrf @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modalDelete')"
                        class="flex-1 bg-[#f2f4f6] text-[#5d5f5f] text-[13px] font-semibold py-2.5 rounded-xl hover:bg-[#edeef0] transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 bg-red-600 text-white text-[13px] font-semibold py-2.5 rounded-xl hover:bg-red-700 transition-colors">
                    Supprimer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/modules/categories.js') }}"></script>
@endpush
