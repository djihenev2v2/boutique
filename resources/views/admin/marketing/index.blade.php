@extends('layouts.admin')
@section('title', 'Marketing')
@section('page-title', 'Marketing')
@section('page-description', 'Codes promotionnels')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h2 class="text-xl font-bold text-[#18396e]">Codes promotionnels</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ $promos->total() }} code{{ $promos->total() !== 1 ? 's' : '' }} au total</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Search --}}
            <form method="GET" action="{{ route('admin.marketing.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Rechercher un code…"
                           class="pl-9 pr-4 py-2 border border-[#dde1ea] rounded-full text-[13px] w-52 bg-white focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                </div>
                @if($search)
                <a href="{{ route('admin.marketing.index') }}" class="px-3 py-2 text-[13px] text-slate-500 border border-[#dde1ea] rounded-full hover:bg-slate-50 transition-colors">✕</a>
                @endif
            </form>
            {{-- Create button --}}
            <button type="button" id="btnCreate"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#18396e] text-white text-[13px] font-medium rounded-full hover:bg-[#0f2445] transition-colors shadow-md shadow-[#18396e]/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Créer un code
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-[#edeef0] overflow-hidden">
        @if($promos->isEmpty())
        <div class="py-24 flex flex-col items-center gap-4 text-center">
            <div class="w-16 h-16 rounded-2xl bg-[#e8edf5] flex items-center justify-center">
                <svg class="w-8 h-8 text-[#18396e]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-700 text-[15px]">Aucun code promo</p>
                <p class="text-sm text-slate-400 mt-1">
                    @if($search)Aucun résultat pour « {{ $search }} »@else Créez votre premier code promotionnel.@endif
                </p>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#edeef0] bg-[#f8f9fb]">
                        <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Code</th>
                        <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Type</th>
                        <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Valeur</th>
                        <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Min commande</th>
                        <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Utilisations</th>
                        <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Expiration</th>
                        <th class="text-left px-5 py-3.5 text-[10.5px] font-bold uppercase tracking-wider text-[#747780]">Statut</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f1f3]">
                    @foreach($promos as $promo)
                    @php
                    $isExpired = $promo->expires_at && $promo->expires_at->isPast();
                    if ($isExpired) {
                        $statusClass = 'bg-red-100 text-red-600';
                        $statusLabel = 'Expiré';
                    } elseif (!$promo->is_active) {
                        $statusClass = 'bg-slate-100 text-slate-500';
                        $statusLabel = 'Inactif';
                    } else {
                        $statusClass = 'bg-emerald-100 text-emerald-700';
                        $statusLabel = 'Actif';
                    }
                    $maxUses = $promo->max_uses ? number_format($promo->max_uses) : '∞';
                    @endphp
                    <tr class="hover:bg-[#fafbfc] transition-colors">
                        <td class="px-5 py-4">
                            <span class="font-mono font-bold text-[#18396e] bg-[#e8edf5] px-2.5 py-1 rounded-lg text-[12px] tracking-wider">
                                {{ $promo->code }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($promo->is_percentage)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-violet-100 text-violet-700 text-[11px] font-semibold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-4.125-2.25-3.375 1.5-3.375-1.5-4.125 2.25V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                                Pourcentage
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-[11px] font-semibold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375"/></svg>
                                Montant fixe
                            </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-800">
                            @if($promo->is_percentage)
                            <span class="text-violet-700">{{ (float)$promo->discount }}%</span>
                            @else
                            <span class="text-blue-700">-{{ number_format((float)$promo->discount, 0, ',', ' ') }} DA</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            {{ (float)$promo->min_order > 0 ? number_format((float)$promo->min_order, 0, ',', ' ') . ' DA' : '—' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-slate-700 font-medium">{{ $promo->used_count }}</span>
                            <span class="text-slate-400"> / {{ $maxUses }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-500">
                            {{ $promo->expires_at ? $promo->expires_at->format('d/m/Y') : 'Aucune' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                {{-- Toggle --}}
                                <form method="POST" action="{{ route('admin.marketing.toggle', $promo) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="{{ $promo->is_active ? 'Désactiver' : 'Activer' }}"
                                            class="p-1.5 rounded-lg transition-colors {{ $promo->is_active ? 'text-emerald-600 hover:bg-emerald-50' : 'text-slate-400 hover:bg-slate-100' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/>
                                        </svg>
                                    </button>
                                </form>
                                {{-- Edit --}}
                                <button type="button"
                                        class="p-1.5 rounded-lg text-[#18396e] hover:bg-[#e8edf5] transition-colors btn-edit"
                                        data-code="{{ $promo->code }}"
                                        data-discount="{{ (float)$promo->discount }}"
                                        data-is_percentage="{{ $promo->is_percentage ? '1' : '0' }}"
                                        data-min_order="{{ (float)$promo->min_order }}"
                                        data-max_uses="{{ $promo->max_uses ?? '' }}"
                                        data-expires_at="{{ $promo->expires_at ? $promo->expires_at->format('Y-m-d') : '' }}"
                                        data-is_active="{{ $promo->is_active ? '1' : '0' }}"
                                        data-update_url="{{ route('admin.marketing.update', $promo) }}"
                                        title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                    </svg>
                                </button>
                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.marketing.destroy', $promo) }}"
                                      onsubmit="return confirm('Supprimer le code {{ $promo->code }} ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($promos->hasPages())
        <div class="px-5 py-4 border-t border-[#edeef0]">
            {{ $promos->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

{{-- ═══ Create / Edit Modal ═══ --}}
<div id="promoModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0f2445]/40 backdrop-blur-sm" onclick="closePromoModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-auto z-10 overflow-y-auto max-h-[90vh]">
        {{-- Modal Header --}}
        <div class="sticky top-0 bg-white rounded-t-3xl px-6 pt-6 pb-4 border-b border-[#edeef0] flex items-center justify-between">
            <h3 id="modalTitle" class="text-[16px] font-bold text-[#18396e]">Créer un code promo</h3>
            <button type="button" onclick="closePromoModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        {{-- Form --}}
        <form id="promoForm" method="POST" action="{{ route('admin.marketing.store') }}">
            @csrf
            <input type="hidden" name="_method" id="promoMethod" value="">
            <div class="px-6 py-5 space-y-4">
                {{-- Code --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Code promo <span class="text-red-500">*</span></label>
                    <input type="text" id="fieldCode" name="code" placeholder="Ex: SOLDES2026"
                           oninput="this.value=this.value.toUpperCase()"
                           class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] font-mono font-semibold tracking-widest uppercase text-[#18396e] focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                </div>
                {{-- Type --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Type de réduction <span class="text-red-500">*</span></label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-3 border border-[#dde1ea] rounded-xl px-4 py-3 cursor-pointer has-[:checked]:border-[#18396e] has-[:checked]:bg-[#e8edf5] transition-colors">
                            <input type="radio" id="typeFixed" name="is_percentage" value="0" class="accent-[#18396e]" checked>
                            <div>
                                <p class="text-[13px] font-semibold text-slate-800">Montant fixe</p>
                                <p class="text-[11px] text-slate-400">Ex: -500 DA</p>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center gap-3 border border-[#dde1ea] rounded-xl px-4 py-3 cursor-pointer has-[:checked]:border-[#18396e] has-[:checked]:bg-[#e8edf5] transition-colors">
                            <input type="radio" id="typePercent" name="is_percentage" value="1" class="accent-[#18396e]">
                            <div>
                                <p class="text-[13px] font-semibold text-slate-800">Pourcentage</p>
                                <p class="text-[11px] text-slate-400">Ex: -10%</p>
                            </div>
                        </label>
                    </div>
                </div>
                {{-- Valeur + Min --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Valeur <span class="text-red-500">*</span></label>
                        <input type="number" id="fieldDiscount" name="discount" min="0.01" step="0.01" placeholder="0"
                               class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Commande min (DA)</label>
                        <input type="number" id="fieldMinOrder" name="min_order" min="0" step="100" placeholder="0"
                               class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                    </div>
                </div>
                {{-- Max uses + Expiration --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Utilisations max</label>
                        <input type="number" id="fieldMaxUses" name="max_uses" min="1" placeholder="Illimité"
                               class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Date d'expiration</label>
                        <input type="date" id="fieldExpiresAt" name="expires_at"
                               class="w-full border border-[#dde1ea] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#18396e]/30 focus:border-[#18396e]">
                    </div>
                </div>
                {{-- Active --}}
                <div class="flex items-center justify-between bg-[#f8f9fb] rounded-2xl px-4 py-3">
                    <div>
                        <p class="text-[13px] font-semibold text-slate-800">Code actif</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Activer immédiatement ce code.</p>
                    </div>
                    <label class="relative inline-block w-11 h-6 cursor-pointer">
                        <input type="checkbox" id="fieldIsActive" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-checked:bg-[#18396e] rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </label>
                </div>
            </div>
            {{-- Footer --}}
            <div class="sticky bottom-0 bg-white rounded-b-3xl px-6 pb-6 pt-4 border-t border-[#edeef0] flex gap-3">
                <button type="button" onclick="closePromoModal()"
                        class="flex-1 py-2.5 text-[13px] font-medium text-slate-600 border border-[#dde1ea] rounded-full hover:bg-slate-50 transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-[#18396e] text-white text-[13px] font-semibold rounded-full hover:bg-[#0f2445] transition-colors shadow-md">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const storeUrl = '{{ route("admin.marketing.store") }}';

function openPromoModal() {
    document.getElementById('promoModal').classList.remove('hidden');
}
function closePromoModal() {
    document.getElementById('promoModal').classList.add('hidden');
}

// Create
document.getElementById('btnCreate').addEventListener('click', function() {
    document.getElementById('modalTitle').textContent = 'Créer un code promo';
    document.getElementById('promoMethod').value = '';
    document.getElementById('promoForm').action = storeUrl;
    document.getElementById('promoForm').reset();
    document.getElementById('fieldIsActive').checked = true;
    openPromoModal();
});

// Edit
document.querySelectorAll('.btn-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const d = this.dataset;
        document.getElementById('modalTitle').textContent = 'Modifier le code promo';
        document.getElementById('promoMethod').value = 'PUT';
        document.getElementById('promoForm').action = d.update_url;
        document.getElementById('fieldCode').value      = d.code;
        document.getElementById('fieldDiscount').value  = d.discount;
        document.getElementById('fieldMinOrder').value  = d.min_order;
        document.getElementById('fieldMaxUses').value   = d.max_uses;
        document.getElementById('fieldExpiresAt').value = d.expires_at;
        document.getElementById('fieldIsActive').checked = (d.is_active === '1');
        // radio type
        if (d.is_percentage === '1') {
            document.getElementById('typePercent').checked = true;
        } else {
            document.getElementById('typeFixed').checked = true;
        }
        openPromoModal();
    });
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePromoModal();
});
</script>
@endpush
@endsection