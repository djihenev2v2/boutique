<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $promos = PromoCode::when($search, fn ($q) => $q->where('code', 'like', "%{$search}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.marketing.index', compact('promos', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'          => 'required|string|max:50|unique:promo_codes,code',
            'discount'      => 'required|numeric|min:0.01',
            'is_percentage' => 'nullable|boolean',
            'min_order'     => 'nullable|numeric|min:0',
            'max_uses'      => 'nullable|integer|min:1',
            'expires_at'    => 'nullable|date|after:today',
            'is_active'     => 'nullable|boolean',
        ]);

        PromoCode::create([
            'code'          => strtoupper($data['code']),
            'discount'      => $data['discount'],
            'is_percentage' => $request->boolean('is_percentage'),
            'min_order'     => $data['min_order'] ?? 0,
            'max_uses'      => $data['max_uses'] ?? null,
            'expires_at'    => $data['expires_at'] ?? null,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Code promo créé avec succès.');
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $data = $request->validate([
            'code'          => "required|string|max:50|unique:promo_codes,code,{$promoCode->id}",
            'discount'      => 'required|numeric|min:0.01',
            'is_percentage' => 'nullable|boolean',
            'min_order'     => 'nullable|numeric|min:0',
            'max_uses'      => 'nullable|integer|min:1',
            'expires_at'    => 'nullable|date',
            'is_active'     => 'nullable|boolean',
        ]);

        $promoCode->update([
            'code'          => strtoupper($data['code']),
            'discount'      => $data['discount'],
            'is_percentage' => $request->boolean('is_percentage'),
            'min_order'     => $data['min_order'] ?? 0,
            'max_uses'      => $data['max_uses'] ?? null,
            'expires_at'    => $data['expires_at'] ?? null,
            'is_active'     => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Code promo modifié.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return back()->with('success', 'Code promo supprimé.');
    }

    public function toggle(PromoCode $promoCode)
    {
        $promoCode->update(['is_active' => ! $promoCode->is_active]);
        return back()->with('success', $promoCode->fresh()->is_active ? 'Code activé.' : 'Code désactivé.');
    }
}
