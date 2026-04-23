<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        $shopName  = Setting::get('shop_name', config('app.name'));
        $logoPath  = Setting::get('shop_logo');
        $cartCount = CartController::getCount();

        return view('client.order-tracking', compact('shopName', 'logoPath', 'cartCount'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'phone'        => ['required', 'string', 'max:20'],
            'order_number' => ['required', 'string', 'max:50'],
        ]);

        $shopName  = Setting::get('shop_name', config('app.name'));
        $logoPath  = Setting::get('shop_logo');
        $cartCount = CartController::getCount();

        $order = Order::with(['shipment', 'statusHistory'])
            ->where('order_number', strtoupper(trim($request->order_number)))
            ->where('customer_phone', trim($request->phone))
            ->first();

        return view('client.order-tracking', compact(
            'shopName', 'logoPath', 'cartCount', 'order'
        ))->with([
            'searched'     => true,
            'searched_phone'  => $request->phone,
            'searched_order'  => $request->order_number,
        ]);
    }
}
