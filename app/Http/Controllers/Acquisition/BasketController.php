<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acquisition\Basket;

class BasketController extends Controller
{
    private $basketModel;

    public function __construct(Basket $basketModel)
    {
        $this->basketModel = $basketModel;
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'billing_place' => 'required|exists:libraries,id',
            'vendor' => 'required|exists:vendors,id',
            'created_by' => 'required|exists:users,id'
        ]);
        $this->basketModel->storeBasket($validated);
        return response()->json([
            'message' => 'Basket created successfully'
        ], 201);
    }

    public function show(string $id)
    {
        $basket = $this->basketModel->getById($id);
        $basket->load('createdBy');
        $basket->load('vendor');
        $basket->load('billingPlace');
        $basket->items = $basket->getItems();
        return $basket;
    }

    public function update(Request $request, string $id)
    {
        $basket = $this->basketModel->getById($id);
        $action = $request->query('action');
        switch($action){
            case 'close':
                $basket->close();
                $message = 'Basket closed successfully';
                break;
        }
        return response()->json([
            'message' => $message
        ], 200);
    }

    public function destroy(string $id)
    {
        $this->basketModel->deleteBasket($id);
        return response()->json([
            'message' => 'Basket deleted successfully'
        ], 200);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'vendor_price' => 'required|integer|min:10000',
            'discount' => 'required|numeric',
            'fund' => 'required|exists:funds,id',
            'basket' => 'required|exists:baskets,id',
            'book' => 'required|exists:books,id'
        ]);
        $basket = $this->basketModel->getById($validated['basket']);
        $basket->addItem($validated);
        return response()->json([
            'message' => 'Item added to basket successfully'
        ], 200); 
    }

    public function getItems(Request $request)
    {
        $items = $request->query('orders');
        if($items){
            $items = explode(',',$items);
            return $this->basketModel->getItemsByIds($items);
        }
        return response()->json([
            'message' => 'please passed in orders query'
        ], 400); 
    }

    public function receiveItem(Request $request)
    {
        $validated = $request->validate([
            'received' => 'required|integer|min:1',
            'item_id' => 'required|exists:basket_items,id'
        ]);
        $this->basketModel->updateItemReceived($validated);
        return response()->json([
            'message' => 'updated receive successfully'
        ], 200); 
    }
}
