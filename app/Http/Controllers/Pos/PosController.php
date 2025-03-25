<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Pos\CashRegister;
use App\Models\Pos\TransactionDetail;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function transaction(TransactionRequest $request)
    {
        $validated = $request->validated();
        $cashRegister = CashRegister::find($validated['cash_register']);
        $transaction = $cashRegister->insertTransaction($validated);
        $transaction->insertDetails($validated['transaction_items']);
        $total = $transaction->calculateTotalFromDebitTypes($validated['transaction_items']);
        $cashRegister->updateTotalIncome($total,'increase');
        if($validated['payment_type'] == 'CASH'){
            $cashRegister->updateCash($total,'increase');
            $cashRegister->updateTotalIncome($total,'increase',true);
        }
        return response()->json([
            'message' => 'Transaction created successfully'
        ], 201);
    }

    public function registerCashup(CashRegister $cashRegister)
    {
        $cashRegister->cashup();
        return response()->json([
            'message' => 'Register cashed up successfully'
        ], 200);
    }

    public function refundItem(Request $request,TransactionDetail $item)
    {
        $validated = $request->validate([
            'payment_type' => 'required|in:CASH,CC',
            'refund' => 'required|integer|min:1000',
            'debit_type' => 'required|string'
        ]);
        if($validated['refund'] > ($item->price * $item->quantity)){
            return response()->json([
                'message' => 'Refunded money must less than or equal to ' . $item->price * $item->quantity
            ], 400);
        }
        $item->markAsRefunded();
        $cashRegister = CashRegister::find($item->tran->register->id);
        $transaction = $cashRegister->insertTransaction($validated,'decrease');
        $transaction->insertDetails(array(
            array(
                'debit_type' => $validated['debit_type'],
                'quantity' => 1,
                'price' => $validated['refund']
            )
        ));
        $cashRegister->updateOutgoing($validated['refund'],'increase');
        if($validated['payment_type'] == 'CASH'){
            $cashRegister->updateCash($validated['refund'],'decrease');
            $cashRegister->updateOutgoing($validated['refund'],'increase',true);
        }
        return response()->json([
            'message' => 'Refund item successfully'
        ], 200);
    }
}
