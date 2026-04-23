<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        // Categories having at least 1 active product
        $categories = Category::whereHas('products', fn ($q) => $q->where('is_active', true))
            ->with(['products' => fn ($q) => $q->where('is_active', true)->limit(1)])
            ->orderBy('name')
            ->get();

        // Up to 8 promo products (discount_price defined and < base_price)
        $promoProducts = Product::where('is_active', true)
            ->whereNotNull('discount_price')
            ->whereColumn('discount_price', '<', 'base_price')
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 'variants'])
            ->latest()
            ->limit(8)
            ->get();

        // Up to 8 products created in the last 30 days
        $newProducts = Product::where('is_active', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 'variants'])
            ->latest()
            ->limit(8)
            ->get();

        // Shop settings
        $shopName  = Setting::get('shop_name', config('app.name', 'Boutique'));
        $shopPhone = Setting::get('shop_phone');
        $shopEmail = Setting::get('shop_email');
        $shopCgv   = Setting::get('shop_cgv');

        return view('landing', compact(
            'categories',
            'promoProducts',
            'newProducts',
            'shopName',
            'shopPhone',
            'shopEmail',
            'shopCgv',
        ));
    }
}
