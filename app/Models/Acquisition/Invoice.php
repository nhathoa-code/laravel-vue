<?php

namespace App\Models\Acquisition;

use App\Relations\InvoiceRelation;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use InvoiceRelation;

    public $timestamps = false;
    protected $guarded = [];

    public function insertDetails(array $basketItems)
    {
        foreach($basketItems as $item){
            $this->details()->create([
                'cost' => $item->vendor_price,
                'quantity' => $item->received,
                'basket_id' => $item->basket_id,
                'book_id' => $item->book_id,
                'fund_id' => $item->fund_id
            ]);
        }
    }

    public function countReceivedItems()
    {
        return $this->details()->sum('quantity');
    }

    public function getById(string|int $id)
    {
        $invoice = $this->findOrFail($id);
        $invoice->load(
            'vendor',
            'billingPlace',
            'details.fund',
            'details.book'
        );
        return $invoice;
    }

    protected function casts(): array
    {
        return [
            'shipping_date' => 'datetime:d-m-Y',
        ];
    }
}
