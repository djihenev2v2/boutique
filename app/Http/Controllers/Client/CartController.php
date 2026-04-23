<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Return the total number of items in the cart (sum of quantities).
     * Called statically from Blade layouts.
     */
    public static function getCount(): int
    {
        $cart = session('cart', []);
        return (int) array_sum(array_column($cart, 'qty'));
    }

    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart      = session('cart', []);
        $cartItems = [];
        $subtotal  = 0;

        foreach ($cart as $variantId => $item) {
            $variant = ProductVariant::with(['product.images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 'attributeValues.attribute'])
                ->find($variantId);

            if (! $variant) {
                // Stale item — remove silently
                session()->forget("cart.{$variantId}");
                continue;
            }

            // Clamp quantity to available stock
            $qty = min($item['qty'], $variant->stock);
            if ($qty < 1) {
                session()->forget("cart.{$variantId}");
                continue;
            }
            if ($qty !== $item['qty']) {
                session()->put("cart.{$variantId}.qty", $qty);
                session()->flash('warning', 'La quantité de certains articles a été ajustée selon le stock disponible.');
            }

            $lineTotal   = $qty * (float) $variant->price;
            $subtotal   += $lineTotal;
            $cartItems[] = [
                'variant'    => $variant,
                'qty'        => $qty,
                'line_total' => $lineTotal,
            ];
        }

        $shopName  = Setting::get('shop_name', config('app.name', 'Boutique'));
        $logoPath  = Setting::get('shop_logo');
        $shopPhone = Setting::get('shop_phone', '');
        $shopEmail = Setting::get('shop_email', '');

        return view('client.cart', compact(
            'cartItems',
            'subtotal',
            'shopName',
            'logoPath',
            'shopPhone',
            'shopEmail'
        ));
    }

    /**
     * Add or update a variant in the cart.
     */
    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,id',
            'qty'        => 'required|integer|min:1|max:99',
        ]);

        $variantId = (int) $request->variant_id;
        $qty       = (int) $request->qty;

        $variant = ProductVariant::findOrFail($variantId);

        // Ensure stock is sufficient
        $currentQty  = session("cart.{$variantId}.qty", 0);
        $newQty      = $currentQty + $qty;
        $clampedQty  = min($newQty, $variant->stock);

        if ($clampedQty < 1) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        session()->put("cart.{$variantId}", ['qty' => $clampedQty]);

        return back()->with('success', 'Produit ajouté au panier !');
    }

    /**
     * Update quantity of a specific variant.
     */
    public function update(Request $request, int $variantId): RedirectResponse
    {
        $request->validate(['qty' => 'required|integer|min:1|max:99']);

        $variant = ProductVariant::findOrFail($variantId);
        $qty     = min((int) $request->qty, $variant->stock);

        if ($qty < 1) {
            session()->forget("cart.{$variantId}");
        } else {
            session()->put("cart.{$variantId}.qty", $qty);
        }

        return back();
    }

    /**
     * Remove a variant from the cart.
     */
    public function remove(int $variantId): RedirectResponse
    {
        session()->forget("cart.{$variantId}");
        return back()->with('success', 'Article retiré du panier.');
    }

    /**
     * Empty the entire cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');
        return back()->with('success', 'Panier vidé.');
    }
}
