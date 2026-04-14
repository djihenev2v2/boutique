<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use App\Models\PromoCode;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // ── Helper : charge le panier depuis la DB ────────────────────

    private function getCartArray(): array
    {
        $dbCart = Cart::where('user_id', auth()->id())
            ->with(['items.variant.product.images', 'items.variant.attributeValues.attribute', 'promoCode'])
            ->first();

        if (! $dbCart) {
            return [
                'items'                     => [],
                'promo_code_id'             => null,
                'promo_code'                => null,
                'promo_discount_value'      => 0,
                'promo_discount_percentage' => false,
            ];
        }

        $items = [];
        foreach ($dbCart->items as $ci) {
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

        $promo = $dbCart->promoCode;

        return [
            'items'                     => $items,
            'promo_code_id'             => $dbCart->promo_code_id,
            'promo_code'                => $promo?->code,
            'promo_discount_value'      => $promo ? (float) $promo->discount : 0,
            'promo_discount_percentage' => $promo ? (bool) $promo->is_percentage : false,
        ];
    }

    // ── Afficher le formulaire de commande ────────────────────────

    public function index()
    {
        $cart = $this->getCartArray();

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $wilayas = Wilaya::orderBy('name')->get();
        $user    = auth()->user();

        return view('client.checkout', compact('cart', 'wilayas', 'user'));
    }

    // ── Traiter la commande ───────────────────────────────────────

    public function store(Request $request)
    {
        $cart = $this->getCartArray();

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'wilaya_id'      => 'required|integer|exists:wilayas,id',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:cod,baridimob,cib',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $wilaya = Wilaya::findOrFail($validated['wilaya_id']);

        // Calcul du sous-total
        $subtotal = 0;
        foreach ($cart['items'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = (float) $wilaya->shipping_cost;

        // Remise code promo
        $discount    = 0;
        $promoCodeId = $cart['promo_code_id'] ?? null;
        if ($promoCodeId) {
            $promo = PromoCode::find($promoCodeId);
            if ($promo && $promo->is_active) {
                $discount = $promo->is_percentage
                    ? round($subtotal * ($promo->discount / 100), 2)
                    : (float) $promo->discount;
                $discount = min($discount, $subtotal);
            }
        }

        $total = max(0, $subtotal + $shippingCost - $discount);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => auth()->id(),
                'customer_name'  => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => auth()->user()->email,
                'wilaya_id'      => $validated['wilaya_id'],
                'address'        => $validated['address'],
                'subtotal'       => $subtotal,
                'shipping_cost'  => $shippingCost,
                'discount'       => $discount,
                'total'          => $total,
                'promo_code_id'  => $promoCodeId,
                'status'         => 'pending',
                'payment_method' => $validated['payment_method'],
                'notes'          => $validated['notes'] ?? null,
            ]);

            foreach ($cart['items'] as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item['variant_id'],
                    'product_name'       => $item['name'],
                    'variant_label'      => $item['variant_label'],
                    'sku'                => $item['sku'],
                    'unit_price'         => $item['price'],
                    'quantity'           => $item['quantity'],
                    'subtotal'           => round($item['price'] * $item['quantity'], 2),
                ]);

                ProductVariant::where('id', $item['variant_id'])
                    ->decrement('stock', $item['quantity']);
            }

            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'from_status' => null,
                'to_status'   => 'pending',
                'note'        => 'Commande passée en ligne',
                'changed_at'  => now(),
            ]);

            if ($promoCodeId) {
                PromoCode::where('id', $promoCodeId)->increment('used_count');
            }

            DB::commit();

            // Vider le panier en base
            $dbCart = Cart::where('user_id', auth()->id())->first();
            if ($dbCart) {
                $dbCart->items()->delete();
                $dbCart->update(['promo_code_id' => null]);
            }

            return redirect()->route('orders.confirmation', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace'   => $e->getTraceAsString(),
            ]);
            $errorMsg = config('app.debug')
                ? 'Erreur: ' . $e->getMessage()
                : 'Une erreur est survenue. Veuillez réessayer.';
            return back()->with('error', $errorMsg);
        }
    }
}

