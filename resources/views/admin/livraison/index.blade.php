@extends('layouts.admin')
@section('title', 'Livraison')
@section('page-title', 'Livraison')
@section('page-description', 'Frais de livraison par wilaya')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h2 class="text-xl font-bold text-[#18396e]">Frais de livraison</h2>
            @php $activeCount = $wilayas->where('is_active', true)->count(); @endphp
            <p class="text-sm text-slate-500 mt-0.5">
                <span class="font-semibold text-emerald-600">{{ $activeCount }}</span> wilayas actives sur {{ $wilayas->count() }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Search client-side --}}
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" id="wilayaSearch" placeholder="Rechercher une wilaya…"
                       oninput="filterWilayas(this.value)"
                       class="pl-9 pr-4 py-2 border border-[#dde1ea] rounded-full text-[13px] w-56 bg-white focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
            </div>
            {{-- Tarif global --}}
            <button type="button" onclick="document.getElementById('bulkModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#18396e] text-white text-[13px] font-medium rounded-full hover:bg-[#0f2445] transition-colors shadow-md shadow-[#18396e]/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                Tarif global
            </button>
        </div>
    </div>

    {{-- Main Form --}}
    <form method="POST" action="{{ route('admin.livraison.save') }}" id="livForm">
        @csrf
        <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]" id="wilayaTable">
                    <thead>
                        <tr class="border-b border-[#edeef0] bg-[#f8f9fb]">
                            <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780] w-14">Code</th>
                            <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Wilaya</th>
                            <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780] w-52">Prix livraison (DA)</th>
                            <th class="text-center px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780] w-32">Active</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0f1f3]" id="wilayaBody">
                        @foreach($wilayas as $w)
                        <tr class="hover:bg-[#fafbfc] transition-colors wilaya-row" data-name="{{ strtolower($w->name) }}">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#e8edf5] text-[#18396e] font-bold text-[12px]">
                                    {{ $w->code }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-medium text-slate-700">{{ $w->name }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <input type="number"
                                           name="shipping_cost[{{ $w->id }}]"
                                           value="{{ (float)$w->shipping_cost }}"
                                           min="0" step="50"
                                           class="w-28 border border-[#dde1ea] rounded-xl px-3 py-2 text-[13px] text-right font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e] transition">
                                    <span class="text-slate-400 text-[12px] font-medium">DA</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <label class="relative inline-block w-11 h-6 cursor-pointer">
                                    <input type="checkbox"
                                           name="is_active[{{ $w->id }}]"
                                           value="1"
                                           class="sr-only peer"
                                           {{ $w->is_active ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-checked:bg-[#18396e] rounded-full transition-colors duration-200"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Footer --}}
            <div class="px-5 py-4 border-t border-[#edeef0] bg-[#f8f9fb] flex items-center justify-between gap-4">
                <p class="text-[12px] text-slate-400">Modifiez les prix de livraison et les wilayas actives, puis cliquez sur Sauvegarder.</p>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#18396e] hover:bg-[#0f2445] text-white text-[13px] font-semibold rounded-full shadow-md shadow-[#18396e]/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Sauvegarder tout
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ═══ Bulk Modal ═══ --}}
<div id="bulkModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/40 backdrop-blur-sm" onclick="document.getElementById('bulkModal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md z-10">
        <h3 class="text-[16px] font-bold text-[#18396e] mb-1">Appliquer un tarif global</h3>
        <p class="text-[13px] text-slate-500 mb-5">Ce prix sera appliqué à toutes les wilayas.</p>
        <form method="POST" action="{{ route('admin.livraison.bulk') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Prix (DA)</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="price" min="0" step="50" placeholder="Ex: 500"
                           class="flex-1 border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                    <span class="text-slate-400 font-medium">DA</span>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('bulkModal').classList.add('hidden')"
                        class="flex-1 py-2.5 text-[13px] font-medium text-slate-600 border border-[#dde1ea] rounded-full hover:bg-slate-50 transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-[#18396e] text-white text-[13px] font-semibold rounded-full hover:bg-[#0f2445] transition-colors shadow-md">
                    Appliquer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function filterWilayas(q) {
    const rows = document.querySelectorAll('.wilaya-row');
    const lower = q.toLowerCase().trim();
    rows.forEach(row => {
        row.style.display = (!lower || row.dataset.name.includes(lower)) ? '' : 'none';
    });
}
</script>
@endpush
@endsection