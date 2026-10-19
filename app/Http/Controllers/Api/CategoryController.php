<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::withSum('expenses', 'amount')->withCount('expenses')->orderBy('name')->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|max:60|unique:categories,name']);

        return response()->json(['data' => Category::create($data)], 201);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->expenses()->exists()) {
            return response()->json(['message' => 'This category has expenses and cannot be deleted.'], 422);
        }
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
