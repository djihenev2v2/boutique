<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ──────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'images'])
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'actif' ? 1 : 0);
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'rupture') {
                $query->whereDoesntHave('variants', fn($q) => $q->where('stock', '>', 0));
            } elseif ($request->stock === 'faible') {
                $query->whereHas('variants', fn($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5));
            } elseif ($request->stock === 'disponible') {
                $query->whereHas('variants', fn($q) => $q->where('stock', '>', 5));
            }
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        $products   = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands     = Product::whereNotNull('brand')->distinct()->pluck('brand')->sort()->values();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    // ──────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->get();

        return view('admin.products.create', compact('categories', 'attributes'));
    }

    // ──────────────────────────────────────
    // STORE
    // ──────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'brand'       => 'nullable|string|max:100',
            'base_price'  => 'required|numeric|min:0',
            'is_active'   => 'boolean',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'variants'    => 'nullable|array',
            'variants.*.sku'   => 'nullable|string|max:100',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
            'variants.*.attr_texts'   => 'nullable|array',
            'variants.*.attr_texts.*' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $slug = Str::slug($validated['name']);
            $base = $slug;
            $i    = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            $product = Product::create([
                'name'        => $validated['name'],
                'slug'        => $slug,
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'brand'       => $validated['brand'] ?? null,
                'base_price'  => $validated['base_price'],
                'is_active'   => $request->boolean('is_active', true),
            ]);

            // Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create(['path' => $path, 'sort_order' => $i]);
                }
            }

            // Variants
            if (!empty($validated['variants'])) {
                foreach ($validated['variants'] as $variantData) {
                    $variant = $product->variants()->create([
                        'sku'   => $variantData['sku'] ?? null,
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                    ]);

                    if (!empty($variantData['attr_texts'])) {
                        $valueIds = [];
                        foreach ($variantData['attr_texts'] as $attrId => $textVal) {
                            $textVal = trim((string) $textVal);
                            if ($textVal === '') continue;
                            $av = ProductAttributeValue::firstOrCreate(
                                ['attribute_id' => (int) $attrId, 'value' => $textVal]
                            );
                            $valueIds[] = $av->id;
                        }
                        if ($valueIds) {
                            $variant->attributeValues()->sync($valueIds);
                        }
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    // ──────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────
    public function edit(Product $product)
    {
        $product->load(['category', 'images', 'variants.attributeValues.attribute']);
        $categories = Category::orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->get();

        return view('admin.products.edit', compact('product', 'categories', 'attributes'));
    }

    // ──────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'brand'       => 'nullable|string|max:100',
            'base_price'  => 'required|numeric|min:0',
            'is_active'   => 'boolean',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|min:1',
            'variants'    => 'nullable|array',
            'variants.*.id'    => 'nullable|exists:product_variants,id',
            'variants.*.sku'   => 'nullable|string|max:100',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
            'variants.*.attr_texts'   => 'nullable|array',
            'variants.*.attr_texts.*' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $validated, $product) {
            $product->update([
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'brand'       => $validated['brand'] ?? null,
                'base_price'  => $validated['base_price'],
                'is_active'   => $request->boolean('is_active', true),
            ]);

            // Delete selected images
            if (!empty($validated['delete_images'])) {
                $toDelete = $product->images()->whereIn('id', $validated['delete_images'])->get();
                foreach ($toDelete as $img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }

            // New images
            if ($request->hasFile('images')) {
                $nextOrder = $product->images()->max('sort_order') + 1;
                foreach ($request->file('images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create(['path' => $path, 'sort_order' => $nextOrder + $i]);
                }
            }

            // Variants — delete removed ones first
            $incomingIds = collect($validated['variants'] ?? [])
                ->pluck('id')->filter()->values()->toArray();

            $product->variants()
                ->when(!empty($incomingIds), fn($q) => $q->whereNotIn('id', $incomingIds))
                ->each(function (ProductVariant $v) {
                    $v->attributeValues()->detach();
                    $v->delete();
                });

            // Upsert variants
            foreach ($validated['variants'] ?? [] as $variantData) {
                if (!empty($variantData['id'])) {
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'sku'   => $variantData['sku'] ?? null,
                            'price' => $variantData['price'],
                            'stock' => $variantData['stock'],
                        ]);
                    }
                } else {
                    $variant = $product->variants()->create([
                        'sku'   => $variantData['sku'] ?? null,
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                    ]);
                }

                if ($variant && !empty($variantData['attr_texts'])) {
                    $valueIds = [];
                    foreach ($variantData['attr_texts'] as $attrId => $textVal) {
                        $textVal = trim((string) $textVal);
                        if ($textVal === '') continue;
                        $av = ProductAttributeValue::firstOrCreate(
                            ['attribute_id' => (int) $attrId, 'value' => $textVal]
                        );
                        $valueIds[] = $av->id;
                    }
                    $variant->attributeValues()->sync($valueIds);
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    // ──────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->path);
            }
            $product->variants->each(fn($v) => $v->attributeValues()->detach());
            $product->delete();
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }

    // ──────────────────────────────────────
    // TOGGLE ACTIVE (AJAX)
    // ──────────────────────────────────────
    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return response()->json(['is_active' => $product->is_active]);
    }
}
