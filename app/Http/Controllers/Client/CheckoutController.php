<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PromoCode;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Wilaya;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show the checkout form.
     */
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        // Build cart items (reuse same logic as CartController)
        $cartItems = [];
        $subtotal  = 0;

        foreach ($cart as $variantId => $item) {
            $variant = ProductVariant::with([
                'product.images' => fn ($q) => $q->orderBy('sort_order')->limit(1),
                'attributeValues.attribute',
            ])->find($variantId);

            if (! $variant || $variant->stock < 1) {
                session()->forget("cart.{$variantId}");
                continue;
            }

            $qty        = min($item['qty'], $variant->stock);
            $lineTotal  = $qty * (float) $variant->price;
            $subtotal  += $lineTotal;
            $cartItems[] = [
                'variant'    => $variant,
                'qty'        => $qty,
                'line_total' => $lineTotal,
            ];
        }

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $wilayas      = Wilaya::where('is_active', true)->orderBy('code')->get();
        $shopName     = Setting::get('shop_name', config('app.name'));
        $logoPath     = Setting::get('shop_logo');
        $shopPhone    = Setting::get('shop_phone', '');
        $shopEmail    = Setting::get('shop_email', '');
        $cartCount    = CartController::getCount();

        // Payment methods enabled
        $payBaridimob = (bool) Setting::get('pay_baridimob', 0);
        $payCib       = (bool) Setting::get('pay_cib', 0);

        return view('client.checkout', compact(
            'cartItems', 'subtotal', 'wilayas',
            'shopName', 'logoPath', 'shopPhone', 'shopEmail',
            'cartCount', 'payBaridimob', 'payCib'
        ));
    }

    /**
     * AJAX: get shipping cost for a wilaya.
     */
    public function wilayaShipping(int $wilayaId): JsonResponse
    {
        $wilaya = Wilaya::find($wilayaId);
        if (! $wilaya || ! $wilaya->is_active) {
            return response()->json(['error' => 'Wilaya non disponible'], 404);
        }
        return response()->json([
            'name'          => $wilaya->name,
            'shipping_cost' => (float) $wilaya->shipping_cost,
        ]);
    }

    /**
     * AJAX: validate a promo code.
     */
    public function applyPromo(Request $request): JsonResponse
    {
        $code     = strtoupper(trim($request->input('code', '')));
        $subtotal = (float) $request->input('subtotal', 0);

        $promo = PromoCode::where('code', $code)->first();

        if (! $promo || ! $promo->is_active) {
            return response()->json(['error' => 'Code promo invalide ou inactif.'], 422);
        }
        if ($promo->expires_at && $promo->expires_at->isPast()) {
            return response()->json(['error' => 'Ce code promo a expiré.'], 422);
        }
        if ($promo->max_uses !== null && $promo->used_count >= $promo->max_uses) {
            return response()->json(['error' => 'Ce code promo a atteint sa limite d\'utilisation.'], 422);
        }
        if ($subtotal < (float) $promo->min_order) {
            return response()->json([
                'error' => 'Montant minimum de commande requis : ' . number_format($promo->min_order, 0, ',', ' ') . ' DA.',
            ], 422);
        }

        // Calculate discount amount
        if ($promo->is_percentage) {
            $discount = round($subtotal * ((float) $promo->discount / 100), 2);
        } else {
            $discount = min((float) $promo->discount, $subtotal);
        }

        return response()->json([
            'id'            => $promo->id,
            'code'          => $promo->code,
            'discount'      => $discount,
            'is_percentage' => $promo->is_percentage,
            'label'         => $promo->is_percentage
                ? '-' . (int) $promo->discount . '%'
                : '-' . number_format($discount, 0, ',', ' ') . ' DA',
        ]);
    }

    /**
     * Process and store the order.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name'   => ['required', 'string', 'max:255'],
            'customer_phone'  => ['required', 'string', 'max:20', 'regex:/^(0[5-7][0-9]{8}|0[2-4][0-9]{7})$/'],
            'customer_email'  => ['nullable', 'email', 'max:255'],
            'wilaya_id'       => ['required', 'integer', 'exists:wilayas,id'],
            'address'         => ['required', 'string', 'min:10'],
            'payment_method'  => ['required', 'in:cod,baridimob,cib'],
            'promo_code_id'   => ['nullable', 'integer', 'exists:promo_codes,id'],
        ], [
            'customer_name.required'  => 'Le nom complet est obligatoire.',
            'customer_phone.required' => 'Le numéro de téléphone est obligatoire.',
            'customer_phone.regex'    => 'Numéro de téléphone invalide (format algérien requis).',
            'wilaya_id.required'      => 'Veuillez sélectionner votre wilaya.',
            'wilaya_id.exists'        => 'Wilaya invalide.',
            'address.required'        => 'L\'adresse est obligatoire.',
            'address.min'             => 'L\'adresse doit comporter au moins 10 caractères.',
            'payment_method.required' => 'Veuillez choisir une méthode de paiement.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        // Verify wilaya is active
        $wilaya = Wilaya::find($request->wilaya_id);
        if (! $wilaya || ! $wilaya->is_active) {
            return back()->withErrors(['wilaya_id' => 'Cette wilaya n\'est pas disponible pour la livraison.'])->withInput();
        }

        // Validate promo code if provided
        $promo    = null;
        $discount = 0;
        if ($request->promo_code_id) {
            $promo = PromoCode::find($request->promo_code_id);
            if ($promo && $promo->is_active && (! $promo->expires_at || $promo->expires_at->isFuture())) {
                // discount will be recalculated after subtotal is known
            } else {
                $promo = null;
            }
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $lines    = [];

            foreach ($cart as $variantId => $item) {
                $variant = ProductVariant::with('product', 'attributeValues.attribute')->lockForUpdate()->find($variantId);

                if (! $variant) {
                    throw new \Exception("Produit introuvable (variante #{$variantId}).");
                }
                $qty = (int) $item['qty'];
                if ($variant->stock < $qty) {
                    throw new \Exception("Stock insuffisant pour « {$variant->product->name} ».");
                }

                $unitPrice  = (float) $variant->price;
                $lineTotal  = round($unitPrice * $qty, 2);
                $subtotal  += $lineTotal;

                // Build variant label from attributes
                $attrValues = $variant->attributeValues->pluck('value')->implode(' / ');

                $lines[] = [
                    'variant'       => $variant,
                    'product_name'  => $variant->product->name,
                    'variant_label' => $attrValues ?: null,
                    'sku'           => $variant->sku,
                    'unit_price'    => $unitPrice,
                    'quantity'      => $qty,
                    'subtotal'      => $lineTotal,
                ];
            }

            if (empty($lines)) {
                throw new \Exception("Le panier est vide.");
            }

            // Recalculate discount on real subtotal
            if ($promo) {
                if ($subtotal >= (float) $promo->min_order) {
                    $discount = $promo->is_percentage
                        ? round($subtotal * ((float) $promo->discount / 100), 2)
                        : min((float) $promo->discount, $subtotal);
                } else {
                    $promo    = null;
                    $discount = 0;
                }
            }

            $shippingCost = (float) $wilaya->shipping_cost;
            $total        = max(0, round($subtotal - $discount + $shippingCost, 2));

            // Create order
            $order = Order::create([
                'customer_name'   => $request->customer_name,
                'customer_phone'  => $request->customer_phone,
                'customer_email'  => $request->customer_email,
                'wilaya_id'       => $wilaya->id,
                'address'         => $request->address,
                'subtotal'        => $subtotal,
                'shipping_cost'   => $shippingCost,
                'discount'        => $discount,
                'total'           => $total,
                'promo_code_id'   => $promo?->id,
                'status'          => 'pending',
                'payment_method'  => $request->payment_method,
            ]);

            // Create order items + decrement stock
            foreach ($lines as $line) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $line['variant']->id,
                    'product_name'       => $line['product_name'],
                    'variant_label'      => $line['variant_label'],
                    'sku'                => $line['sku'],
                    'unit_price'         => $line['unit_price'],
                    'quantity'           => $line['quantity'],
                    'subtotal'           => $line['subtotal'],
                ]);

                $line['variant']->decrement('stock', $line['quantity']);
            }

            // Status history
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'from_status' => null,
                'to_status'  => 'pending',
                'note'       => 'Commande passée.',
                'changed_at' => now(),
            ]);

            // Increment promo code usage
            if ($promo) {
                $promo->increment('used_count');
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            // Store confirmation data in session (security: only accessible once)
            session()->put('order_confirmed', [
                'order_number' => $order->order_number,
                'order_id'     => $order->id,
            ]);

            return redirect()->route('checkout.confirmation', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['general' => $e->getMessage()]);
        }
    }

    /**
     * Order confirmation page.
     */
    public function confirmation(string $orderNumber)
    {
        $confirmed = session('order_confirmed');

        if (! $confirmed || $confirmed['order_number'] !== $orderNumber) {
            return redirect()->route('landing');
        }

        $order = Order::with(['items', 'wilaya'])->where('order_number', $orderNumber)->firstOrFail();

        // Consume the session token so it can't be revisited
        session()->forget('order_confirmed');

        $shopName  = Setting::get('shop_name', config('app.name'));
        $logoPath  = Setting::get('shop_logo');
        $cartCount = CartController::getCount();

        return view('client.order-confirmation', compact('order', 'shopName', 'logoPath', 'cartCount'));
    }
}
