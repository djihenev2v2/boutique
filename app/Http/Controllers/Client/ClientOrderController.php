<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;

class ClientOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items', 'wilaya'])
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function show(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items', 'wilaya', 'promoCode', 'shipment', 'statusHistory'])
            ->firstOrFail();

        return view('client.order-tracking', compact('order'));
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items', 'wilaya'])
            ->firstOrFail();

        return view('client.order-confirmation', compact('order'));
    }
}
