<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\Library;

class LibraryController extends Controller
{
    public function index()
    {
        return Library::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:100",
            "address" => "required|string|max:255",
            "contact" => "required|string|max:100"
        ]);
        $library = Library::create($validated);
        return response()->json([
            'data' => $library,
            'message' => 'Library created successfully'
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, Library $library)
    {
        $validated = $request->validate([
            "name" => "required|string|max:100",
            "address" => "required|string|max:255",
            "contact" => "required|string|max:100"
        ]);
        $library->update($validated);
        return response()->json([
            'data' => $library,
            'message' => 'Library updated successfully'
        ], 200);
    }

    public function destroy(Library $library)
    {
        $library->delete();
        return response()->json([
            'message' => 'Library deleted successfully'
        ], 200);
    }
}
