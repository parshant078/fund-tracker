<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withSum('expenses', 'amount')->withCount('expenses')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:60|unique:categories,name']);
        Category::create($data);

        return back()->with('ok', 'Category added.');
    }

    public function destroy(Category $category)
    {
        if ($category->expenses()->exists()) {
            return back()->with('error', 'This category has expenses and cannot be deleted.');
        }
        $category->delete();

        return back()->with('ok', 'Category deleted.');
    }
}
