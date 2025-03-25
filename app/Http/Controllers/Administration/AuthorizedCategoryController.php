<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Administration\AuthorizedCategory;
use App\Models\Administration\AuthorizedValue;
use Illuminate\Http\Request;

class AuthorizedCategoryController extends Controller
{
    private $authorizedCategoryModel;
    protected $validCategories = ['LOC','CCODE'];

    public function __construct(AuthorizedCategory $authorizedCategoryModel)
    {
        $this->authorizedCategoryModel = $authorizedCategoryModel;
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:' . join(',',$this->validCategories)
        ]);
        $category = $this->authorizedCategoryModel->getOrCreate($validated['category']);
        return $category->values;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'category' => 'required|in:' . join(',',$this->validCategories),
            'library' => 'nullable|exists:libraries,id'
        ]);
        $this->authorizedCategoryModel->storeCategoryValue($validated);
        return response()->json([
            'message' => "Category {$validated['category']}'s value created successfully"
        ], 201);
    }

    public function show(AuthorizedValue $authorized_value)
    {
        return $authorized_value;
    }

    public function update(Request $request, AuthorizedValue $authorized_value)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'library' => 'nullable|exists:libraries,id'
        ]);
        $authorized_value->update($validated);
        return response()->json([
            'message' => "Category's value updated successfully"
        ], 200);
    }

    public function destroy(AuthorizedValue $authorized_value)
    {
        $authorized_value->delete();
        return response()->json([
            'message' => "Category's value created successfully"
        ], 200); 
    }
}
