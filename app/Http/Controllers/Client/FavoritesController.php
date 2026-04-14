<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with(['product.images', 'product.variants', 'product.category'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('client.favoris', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|integer|exists:products,id']);

        $existing = Favorite::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            Favorite::create([
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
                'created_at' => now(),
            ]);
            $favorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['favorited' => $favorited]);
        }

        return back()->with('success', $favorited ? 'Ajouté aux favoris.' : 'Retiré des favoris.');
    }
}
