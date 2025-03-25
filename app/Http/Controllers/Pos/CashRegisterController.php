<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Pos\CashRegister;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    private $cashRegisterModel;

    public function __construct(CashRegister $cashRegisterModel)
    {
        $this->cashRegisterModel = $cashRegisterModel;
    }

    public function index()
    {
        return CashRegister::with('toLibrary')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:cash_registers',
            'description' => 'required|string|max:255',
            'initial_amount' => 'required|integer|min:0',
            'library' => 'required|exists:libraries,id'
        ]);
        CashRegister::insert($validated);
        return response()->json([
            'message' => 'Cash register created successfully'
        ], 201);
    }

    public function show(string $id)
    {
        $cashRegister = $this->cashRegisterModel->getById($id);
        $cashRegister->load('toLibrary','cashupHistories.cashupSumary','cashupHistories.operator');
        $cashRegister->transactions->each(function($item){
            $item->total = $item->culculateTotal($item->details);
        });
        return $cashRegister;
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
