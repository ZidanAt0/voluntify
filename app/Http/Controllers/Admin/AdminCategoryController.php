<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:150',
        ]);

        $data['slug'] = $this->makeSlug($data['name'], $data['slug'] ?? null);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:150',
        ]);

        $data['slug'] = $this->makeSlug($data['name'], $data['slug'] ?? null, $category->id);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }

    private function makeSlug(string $name, ?string $slug = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $final = $base;
        $i = 1;
        while (
            Category::where('slug', $final)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $final = $base . '-' . $i;
            $i++;
        }
        return $final;
    }
}
