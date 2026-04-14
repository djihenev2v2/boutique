<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────────

    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    private function buildCartArray(Cart $cart): array
    {
        $cartItems = $cart->items()
            ->with(['variant.product.images', 'variant.attributeValues.attribute'])
            ->get();

        $items = [];
        foreach ($cartItems as $ci) {
            $variant = $ci->variant;
            if (! $variant) continue;
            $items[(string) $variant->id] = [
                'variant_id'    => $variant->id,
                'product_id'    => $variant->product_id,
                'name'          => $variant->product->name,
                'slug'          => $variant->product->slug,
                'variant_label' => $variant->label,
                'sku'           => $variant->sku,
                'price'         => (float) $variant->price,
                'image'         => $variant->product->images->first()?->path,
                'quantity'      => $ci->quantity,
            ];
        }

        $promo = $cart->promoCode;
        return [
            'items'                     => $items,
            'promo_code_id'             => $cart->promo_code_id,
            'promo_code'                => $promo?->code,
            'promo_discount_value'      => $promo ? (float) $promo->discount : 0,
            'promo_discount_percentage' => $promo ? (bool) $promo->is_percentage : false,
        ];
    }

    public static function getCount(): int
    {
        if (! auth()->check()) return 0;
        $cart = Cart::where('user_id', auth()->id())->first();
        if (! $cart) return 0;
        return (int) $cart->items()->sum('quantity');
    }

    // ── Show cart page ────────────────────────────────────────────

    public function index()
    {
        $cart = $this->buildCartArray($this->getOrCreateCart());
        return view('client.cart', compact('cart'));
    }

    // ── Add item ──────────────────────────────────────────────────

    public function add(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $variant = ProductVariant::with(['product.images'])->findOrFail($validated['variant_id']);

        if ($variant->stock < 1) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Rupture de stock'], 422);
            }
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        $cart     = $this->getOrCreateCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($cartItem) {
            $newQty = min($cartItem->quantity + $validated['quantity'], $variant->stock);
            $cartItem->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id'            => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity'           => min($validated['quantity'], $variant->stock),
            ]);
        }

        $count = self::getCount();

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'cart_count' => $count,
                'message'    => 'Produit ajoute au panier',
            ]);
        }

        return back()->with('success', 'Produit ajoute au panier.');
    }

    // ── Update quantity ───────────────────────────────────────────

    public function update(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|integer',
            'quantity'   => 'required|integer|min:0',
        ]);

        $cart = $this->getOrCreateCart();

        if ($validated['quantity'] <= 0) {
            CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $validated['variant_id'])
                ->delete();
        } else {
            $variant = ProductVariant::find($validated['variant_id']);
            $max     = $variant ? $variant->stock : 99;
            CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $validated['variant_id'])
                ->update(['quantity' => min($validated['quantity'], $max)]);
        }

        return redirect()->route('cart.index');
    }

    // ── Remove item ───────────────────────────────────────────────

    public function remove(int $variantId)
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variantId)
                ->delete();
        }
        return redirect()->route('cart.index')->with('success', 'Article supprime du panier.');
    }

    // ── Clear cart ────────────────────────────────────────────────

    public function clear()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['promo_code_id' => null]);
        }
        return redirect()->route('cart.index');
    }

    // ── Apply promo code ──────────────────────────────────────────

    public function applyPromo(Request $request)
    {
        $validated = $request->validate([
            'promo_code' => 'required|string|max:50',
        ]);

        $promo = PromoCode::where('code', strtoupper(trim($validated['promo_code'])))
            ->where('is_active', true)
            ->first();

        if (! $promo) {
            return back()->with('error', 'Code promo invalide ou inactif.');
        }

        if ($promo->expires_at && $promo->expires_at->isPast()) {
            return back()->with('error', 'Ce code promo a expire.');
        }

        if ($promo->max_uses && $promo->used_count >= $promo->max_uses) {
            return back()->with('error', 'Ce code promo a atteint sa limite d utilisation.');
        }

        $cart = $this->getOrCreateCart();
        $cart->update(['promo_code_id' => $promo->id]);

        return back()->with('success', 'Code promo applique avec succes !');
    }

    // ── Remove promo code ─────────────────────────────────────────

    public function removePromo()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->update(['promo_code_id' => null]);
        }
        return back()->with('success', 'Code promo supprime.');
    }
}
