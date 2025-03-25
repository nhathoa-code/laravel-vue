<?php

namespace App\Http\Controllers\Acquisition;

use App\Http\Controllers\Controller;
use App\Models\Acquisition\Invoice;
use App\Models\Acquisition\Vendor;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private $invoiceModel;

    public function __construct(Invoice $invoiceModel)
    {
        $this->invoiceModel = $invoiceModel;
    }

    public function index()
    {
        $invoices = Invoice::with('vendor')->get();
        $invoices->each(function($item){
            $item->received_items = $item->countReceivedItems();
        });
        return $invoices;
    }

    public function store(Request $request,Vendor $vendorModel)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'shipping_date' => 'required|date|after_or_equal:today',
            'vendor' => 'required|exists:vendors,id',
            'library' => 'required|exists:libraries,id'
        ]);
        $invoice = $this->invoiceModel->storeInvoice($request->except('library'));
        $vendor = $vendorModel->getById($validated['vendor']);
        $items = $vendor->getItemsFromUnfishBaskets();
        $invoice->insertItems($items,$validated['library']);
        return response()->json([
            'message' => 'invoice created sucessfully'
        ], 200);
    }

    public function show(string $id)
    {
        $invoice = $this->invoiceModel->getById($id);
        return $invoice;
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
