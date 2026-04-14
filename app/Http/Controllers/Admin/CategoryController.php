<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        if ($search) {
            // Flat filtered list when searching
            $categories = Category::with(['parent', 'children', 'products'])
                ->where('name', 'like', '%' . $search . '%')
                ->orderBy('name')
                ->get();
            $tree = null;
        } else {
            $categories = null;
            $tree = Category::whereNull('parent_id')
                ->with(['children.children', 'children.products', 'products'])
                ->orderBy('name')
                ->get();
        }

        $allCategories = Category::orderBy('name')->get();

        return view('admin.categories.index', compact('tree', 'categories', 'allCategories', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', Rule::unique('categories', 'name')],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        // Prevent a category from being its own parent or creating a loop
        if ($data['parent_id'] && (int)$data['parent_id'] === $category->id) {
            return back()->withErrors(['parent_id' => 'Une catégorie ne peut pas être son propre parent.']);
        }

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category)
    {
        // Detach products (set category_id = null)
        $category->products()->update(['category_id' => null]);

        // Detach children (set parent_id = null)
        $category->children()->update(['parent_id' => null]);

        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }
}
