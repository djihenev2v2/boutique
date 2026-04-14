<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['images', 'variants', 'category']);

        // Search
        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Category filter
        if ($categoryIds = $request->get('categories')) {
            $ids = array_filter((array) $categoryIds);
            if ($ids) {
                $query->whereIn('category_id', $ids);
            }
        }

        // Price range
        if ($priceMin = $request->get('price_min')) {
            $query->where('base_price', '>=', (float) $priceMin);
        }
        if ($priceMax = $request->get('price_max')) {
            $query->where('base_price', '<=', (float) $priceMax);
        }

        // Attribute value filters (size, color, etc.)
        if ($attrValues = $request->get('attribute_values')) {
            $ids = array_filter((array) $attrValues);
            if ($ids) {
                $query->whereHas('variants', function ($q) use ($ids) {
                    $q->where('stock', '>', 0)
                      ->whereHas('attributeValues', function ($q2) use ($ids) {
                          $q2->whereIn('product_attribute_values.id', $ids);
                      });
                });
            }
        }

        // In-stock only
        if ($request->boolean('in_stock')) {
            $query->whereHas('variants', fn ($q) => $q->where('stock', '>', 0));
        }

        // Sort
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default: // newest
                $query->latest();
                break;
        }

        $products   = $query->paginate(16)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->get()
            ->filter(fn ($a) => $a->values->isNotEmpty());

        return view('client.catalogue', compact('products', 'categories', 'attributes'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'images',
                'category',
                'variants.attributeValues.attribute',
            ])
            ->firstOrFail();

        // Similar products (same category, excluding current)
        $similar = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->with(['images', 'variants'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('client.product', compact('product', 'similar'));
    }
}
