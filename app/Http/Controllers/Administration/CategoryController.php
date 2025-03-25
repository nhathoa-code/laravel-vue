<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\Category;

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    public function index()
    {
       return $this->categoryModel->getAll();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'parent' => 'nullable|exists:categories,id'
        ]);
        $category = $this->categoryModel->storeCat($validated);
        return response()->json([
            'data' => $category,
            'message' => 'Category created successfully'
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {   
        $validated = $request->validate([
            'name' => 'required',
            'parent' => 'nullable|exists:categories,id'
        ]);
        $category = $this->categoryModel->updateCat($validated,$id);
        return response()->json([
            'data' => $category,
            'message' => 'Category updated successfully'
        ], 200);
    }

    public function destroy(string $id)
    {
        $this->categoryModel->deleteCat($id);
        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
