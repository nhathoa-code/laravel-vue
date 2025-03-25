<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Pos\DebitType;
use Illuminate\Http\Request;

class DebitTypeController extends Controller
{
    private $debitTypeModel;

    public function __construct(DebitType $debitTypeModel)
    {
        $this->debitTypeModel = $debitTypeModel;
    }

    public function index()
    {
        return DebitType::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:debit_types',
            'default_amount' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
            'can_be_sold' => 'boolean',
            'library' => 'nullable|exists:libraries,id'
        ]);
        $newDebitType = $this->debitTypeModel->storeDebitType($validated);
        return response()->json([
            'data' => $newDebitType,
            'message' => 'Debit type created successfully'
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
