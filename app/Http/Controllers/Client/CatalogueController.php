<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Client\CartController;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::where('is_active', true)
            ->with([
                'images'   => fn ($q) => $q->orderBy('sort_order')->limit(1),
                'variants',
                'category',
            ]);

        // Search
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter (supports multiple: ?categorie=1,2,3 or ?categorie[]=1&categorie[]=2)
        $categorieIds = $this->parseArrayParam($request, 'categorie');
        if ($categorieIds) {
            $query->whereIn('category_id', $categorieIds);
        }

        // Price range
        if ($minPrice = $request->get('min_prix')) {
            $query->where('base_price', '>=', (float) $minPrice);
        }
        if ($maxPrice = $request->get('max_prix')) {
            $query->where('base_price', '<=', (float) $maxPrice);
        }

        // Only promo
        if ($request->boolean('promo')) {
            $query->whereNotNull('discount_price')
                  ->whereColumn('discount_price', '<', 'base_price');
        }

        // Only new (30 days)
        if ($request->boolean('nouveau')) {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        // Only in stock
        if ($request->boolean('en_stock')) {
            $query->whereHas('variants', fn ($q) => $q->where('stock', '>', 0));
        }

        // Attribute filters (taille, couleur, pointure)
        $attrFilters = [];
        foreach (['taille', 'couleur', 'pointure'] as $attr) {
            $values = $this->parseArrayParam($request, $attr);
            if ($values) {
                $attrFilters[$attr] = $values;
                $query->whereHas('variants.attributeValues', function ($q) use ($attr, $values) {
                    $q->whereIn('value', $values)
                      ->whereHas('attribute', fn ($aq) => $aq->where('name', $attr));
                });
            }
        }

        // Dynamic attribute filters: attr[AttrName][] params from sidebar
        if ($dynAttrs = $request->get('attr')) {
            foreach ((array) $dynAttrs as $attrName => $values) {
                $values = array_filter((array) $values);
                if ($values) {
                    $query->whereHas('variants.attributeValues', function ($q) use ($attrName, $values) {
                        $q->whereIn('value', $values)
                          ->whereHas('attribute', fn ($aq) => $aq->where('name', $attrName));
                    });
                }
            }
        }

        // Sort
        $sort = $request->get('tri', 'recent');
        match ($sort) {
            'prix_asc'  => $query->orderBy('base_price', 'asc'),
            'prix_desc' => $query->orderBy('base_price', 'desc'),
            'nom'       => $query->orderBy('name', 'asc'),
            default     => $query->latest(),
        };

        $products = $query->paginate(16)->withQueryString();

        // Filter data
        $categories = Category::whereHas('products', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $attributes = ProductAttribute::with(['values' => function ($q) {
            $q->whereHas('variants.product', fn ($pq) => $pq->where('is_active', true));
        }])->get()->keyBy(fn ($a) => strtolower($a->name));

        $priceRange = Product::where('is_active', true)->selectRaw('MIN(base_price) as min, MAX(base_price) as max')->first();

        // Shop settings
        $shopName  = Setting::get('shop_name', config('app.name', 'Boutique'));
        $shopPhone = Setting::get('shop_phone');
        $logoPath  = Setting::get('shop_logo');
        $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0;

        return view('client.catalogue', compact(
            'products',
            'categories',
            'attributes',
            'priceRange',
            'shopName',
            'shopPhone',
            'logoPath',
            'cartCount',
        ));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'images'   => fn ($q) => $q->orderBy('sort_order'),
                'variants.attributeValues.attribute',
                'category',
            ])
            ->firstOrFail();

        $related = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 'variants'])
            ->latest()
            ->limit(4)
            ->get();

        // Build attribute groups: [attrName => [valId => value]]
        $attributeGroups = [];
        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $av) {
                $attrName = $av->attribute->name ?? 'Option';
                $attributeGroups[$attrName][$av->id] = $av->value;
            }
        }

        // Build variants data for JS picker
        $variantsData = $product->variants->map(function ($variant) {
            $attrs = $variant->attributeValues->pluck('id')->toArray();
            $label = $variant->attributeValues->map(fn ($av) => $av->value)->implode(' / ');
            return [
                'id'    => $variant->id,
                'attrs' => $attrs,
                'stock' => $variant->stock,
                'price' => (float) $variant->price,
                'label' => $label,
            ];
        })->values()->all();

        $shopName  = Setting::get('shop_name', config('app.name', 'Boutique'));
        $shopPhone = Setting::get('shop_phone');
        $shopEmail = Setting::get('shop_email');
        $logoPath  = Setting::get('shop_logo');
        $whatsapp  = Setting::get('shop_whatsapp', $shopPhone ?? '');
        $cartCount = CartController::getCount();

        return view('client.product', compact(
            'product',
            'related',
            'attributeGroups',
            'variantsData',
            'shopName',
            'shopPhone',
            'shopEmail',
            'logoPath',
            'whatsapp',
            'cartCount',
        ));
    }

    private function parseArrayParam(Request $request, string $key): array
    {
        $val = $request->get($key);
        if (is_array($val)) {
            return array_filter($val);
        }
        if (is_string($val) && $val !== '') {
            return array_filter(explode(',', $val));
        }
        return [];
    }
}
