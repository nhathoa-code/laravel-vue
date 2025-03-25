<?php

namespace App\Http\Controllers\Acquisition;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFundRequest;
use App\Http\Requests\StoreBudgetRequest;
use App\Models\Acquisition\Budget;
use App\Models\Acquisition\BudgetFund;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        return Budget::all();
    }

    public function store(StoreBudgetRequest $request)
    {
        $validated = $request->validated();
        $budget = Budget::create($validated);
        return response()->json([
            'budget' => $budget,
            'message' => 'Budget created successfully'
        ], 201);
    }

    public function show(Budget $budget)
    {
        $Budget = $budget->load(['funds.orders' => function($query){
            $query->whereHas('basket',function($query){
                $query->where('finish',false);
            });
        },'funds.invoiceDetails']);
        $Budget->funds->each(function($item){
            $item->totalOrders = $item->countBaseLevelOrdered($item->orders);
            $item->totalSpent = $item->countTotalSpent($item->invoiceDetails);
            $item->availableAmount = $item->amount - ($item->totalOrders + $item->totalSpent);
        });
        return $Budget;
    }

    public function update(StoreBudgetRequest $request, Budget $budget)
    {
        $validated = $request->validated();
        if($budget->getTotalFundsAmount() > $validated['total_amount']){
            return response()->json([
                'message' => 'Fund amount exceeds period allocation'
            ], 400);
        }
        $budget->updateBudget($validated);
        return response()->json([
            'message' => 'Budget updated successfully'
        ], 200);
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return response()->json([
            'message' => 'Budget deleted successfully'
        ], 200);
    }

    public function addFund(AddFundRequest $request,Budget $budget)
    {
        $validated = $request->validated();
        if(($budget->getTotalFundsAmount() + $validated['amount']) > $budget->total_amount){
            return response()->json([
                'message' => 'Fund amount exceeds period allocation'
            ], 400);
        }
        if(Auth::user()->id != $validated['owner']){
            return response()->json([
                'message' => 'Onwer not matched currently authenticated'
            ], 400);
        }
        $budget->funds()->create($validated);
        return response()->json([
            'message' => 'Fund added successfully'
        ], 200);
    }

    public function fund(Budget $budget, string $fundId)
    {
        $fund = $budget->funds()->findOrFail($fundId);
        if($fund->users !== null){
            $fund->users = User::whereIn('id',$fund->users)->get();
        }
        return response()->json([
            'fund' => $fund,
            'budget' => $budget
        ], 200);
    }

    public function updateFund(AddFundRequest $request,Budget $budget,string $fundId)
    {
        $validated = $request->validated();
        $fund = $budget->funds()->findOrFail($fundId);
        if(($budget->getTotalFundsAmount($fund) + $validated['amount']) > $budget->total_amount){
            return response()->json([
                'message' => 'Fund amount exceeds period allocation'
            ], 400);
        }
        $fund->update($validated);
        return response()->json([
            'message' => 'Fund updated successfully'
        ], 200);
    }

    public function deleteFund(Budget $budget,string $fundId)
    {
        $budget->funds()->findOrFail($fundId)->delete();
        return response()->json([
            'message' => 'Fund deleted successfully'
        ], 200);
    }

    public function funds(Request $request)
    {
        if($request->query('getAvailableAmount') == 'true'){
            $funds = BudgetFund::with(['orders'=>function($query){
                $query->whereHas('basket',function($query){
                    $query->where('finish',false);
                });
            },'invoiceDetails'])->get();
            $funds->each(function($item){
                $totalOrders = $item->countBaseLevelOrdered($item->orders);
                $totalSpent = $item->countTotalSpent($item->invoiceDetails);
                $item->availableAmount = $item->amount - ($totalOrders + $totalSpent);
            });
            return $funds;
        }
        return BudgetFund::all();
    }
}
