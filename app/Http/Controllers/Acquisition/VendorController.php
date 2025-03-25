<?php

namespace App\Http\Controllers\Acquisition;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddBasketItemRequest;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\StoreBasketRequest;
use App\Http\Requests\StoreVendorRequest;
use App\Models\Acquisition\BasketItem;
use Illuminate\Http\Request;
use App\Models\Acquisition\Vendor;
use Illuminate\Database\Eloquent\Collection;

class VendorController extends Controller
{
    public function index()
    {
        return Vendor::with([
            'baskets' => function ($query) {
                $query->where('finish', false);
            },
            'baskets.createdBy',
            'baskets.items'
        ])->get();
    }

    public function store(StoreVendorRequest $request)
    {
        $validated = $request->validated();
        Vendor::create($validated);
        return response()->json([
            'message' => 'Vendor created successfully'
        ], 201);
    }

    public function show(Vendor $vendor)
    {
        $vendor->load([
            'baskets' => function($query){
                $query->where('finish',false);
            },
            'baskets.createdBy',
            'baskets.items'
        ]);
        $vendor->number_of_baskets = $vendor->countAllBaskets();
        return $vendor;
    }

    public function update(StoreVendorRequest $request, Vendor $vendor)
    {
        $validated = $request->validated();
        $vendor->update($validated);
        return response()->json([
            'message' => 'Vendor updated successfully'
        ], 200);
    }

    public function destroy(Vendor $vendor)
    {
        if($vendor->countSpendingBaskets()){
            return response()->json([
                'message' => 'Vendor has spending baskets'
            ], 400);
        }
        $vendor->delete();
        return response()->json([
            'message' => 'Vendor deleted successfully'
        ], 200);
    }

    public function addBasket(StoreBasketRequest $request,Vendor $vendor)
    {
        $validated = $request->validated();
        $vendor->createBasket($validated);
        return response()->json([
            'message' => 'Basket created successfully for '. $vendor->name
        ], 200);
    }

    public function showBasket(Vendor $vendor,string $basketId)
    {
        $basket = $vendor->baskets()->findOrFail($basketId);
        return response()->json([
            'vendor' => $vendor,
            'basket' => $basket->load('items.book','items.fund','billingPlace','createdBy')
        ], 200);
    }

    public function showBaskets(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:all,active'
        ]);
        $query = $vendor->baskets()->with('createdBy','items');
        if($validated['status'] == 'active'){
            $query->where('finish',false);
        }
        return $query->get();
    }

    public function updateBasket(StoreBasketRequest $request,Vendor $vendor,string $basketId)
    {
        $validated = $request->validated();
        $basket = $vendor->baskets()->findOrFail($basketId);
        if($basket->finish == true){
            return response()->json([
                'message' => 'This basket is already finished'
            ], 400);
        }
        if($request->query('updateStatus') == true){
            $basket->updateStatus($validated['status']);
            return response()->json([
                'status' => $basket->status,
                'message' => 'Basket\'s status updated successfully'
            ], 200);
        }
        $basket->update($validated);
        return response()->json([
            'message' => 'Basket updated successfully for '. $vendor->name
        ], 200);
    }

    public function deleteBasket(Vendor $vendor,string $basketId)
    {
        $basket = $vendor->baskets()->findOrFail($basketId);
        $basket->delete();
        return response()->json([
            'message' => 'Basket deleted successfully'
        ], 200);
    }

    public function addItem(AddBasketItemRequest $request,Vendor $vendor,string $basketId)
    {
        $basket = $vendor->baskets()->findOrFail($basketId);
        if($basket->status == 'closed'){
            return response()->json([
                'message' => 'Can not add to a closed basket'
            ], 400);
        }
        $validated = $request->validated();
        if($basket->checkItemExists($validated['book_id'])){
            return response()->json([
                'message' => 'Book item already exists in the basket'
            ], 400);
        }
        $basket->addItem($validated);
        return response()->json([
            'message' => 'Basket item added successfully'
        ], 200);
    }

    public function deleteItem(Request $request,Vendor $vendor,string $basketId)
    {
        $basket = $vendor->baskets()->findOrFail($basketId);
        $validated = $request->validate([
            'item_id' => 'required|exists:basket_items,id'
        ]);
        $basket->items()->where('id',$validated['item_id'])->limit(1)->delete();
        return response()->json([
            'message' => 'Basket item deleted successfully'
        ], 200);
    }

    public function spendingOrders(Request $request, Vendor $vendor)
    {
        $items = $request->query('orders');
        if($items){
            $items = explode(',',$items);
            $baskets = $vendor->getSpendingOrders($items);
            return response()->json([
                'vendor' => $vendor,
                'items' => $vendor->getItemsFromBaskets($baskets)
            ], 200);
        }
        $spendingOrders = $vendor->getSpendingOrders();
        return $spendingOrders;
    }

    public function invoice(InvoiceRequest $request, Vendor $vendor)
    {
        $validated = $request->validated();
        $basketItems = array();
        foreach($validated['items'] as $item){
            $basketItem = BasketItem::find($item['basket_item']); 
            $basketItem->received = $item['received'];
            $basketItem->status = 'received';
            $basketItem->save();
            $basketItems[] = $basketItem;
        }
        if($request->query('confirm_invoice') == 'true'){
            return (new Collection($basketItems))->load('book','basket','fund');
        }
        $invoice = $vendor->generateInvoice($validated);
        $invoice->insertDetails($basketItems);
        $unFinishBaskets = $vendor->getUnfinishBaskets();
        $vendor->markAsFinish($unFinishBaskets);
        return response()->json([
            'invoice' => $invoice->load('details'),
        ], 200); 
    }
}
