<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilaya;
use Illuminate\Http\Request;

class WilayaController extends Controller
{
    public function index()
    {
        $wilayas = Wilaya::orderBy('code')->get();
        return view('admin.livraison.index', compact('wilayas'));
    }

    public function saveAll(Request $request)
    {
        $costs   = $request->input('shipping_cost', []);
        $actives = $request->input('is_active', []);

        foreach ($costs as $id => $cost) {
            Wilaya::where('id', (int) $id)->update([
                'shipping_cost' => max(0, (float) $cost),
                'is_active'     => isset($actives[$id]) ? 1 : 0,
            ]);
        }

        return back()->with('success', 'Frais de livraison mis à jour avec succès.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate(['price' => 'required|numeric|min:0|max:99999']);
        Wilaya::query()->update(['shipping_cost' => $request->price]);
        return back()->with('success', 'Tarif global appliqué à toutes les wilayas.');
    }
}
