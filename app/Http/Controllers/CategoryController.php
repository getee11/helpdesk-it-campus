<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tickets')->orderBy('name')->get();
        
        return view('master.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        
        Category::create($validated);
        
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        
        $category->update($validated);
        
        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->tickets()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki tiket!');
        }
        
        $category->delete();
        
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}