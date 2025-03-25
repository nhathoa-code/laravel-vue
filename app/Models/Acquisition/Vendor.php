<?php

namespace App\Models\Acquisition;

use App\Relations\VendorRelation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    use VendorRelation;

    protected $guarded = [];
    public $timestamps = false;

    public function createBasket(array $data)
    {
        $data['opened_on'] = now()->toDateString();
        $this->baskets()->create($data);
    }

    public function getSpendingOrders(array $items = null)
    {
        $relations = ['items.book','items.fund'];
        if($items && is_array($items)){
            $relations['items'] = function($query) use($items){
                $query->whereIn('id', $items);
            };
        }
        return $this->baskets()->with($relations)
            ->where('finish',false)
            ->where('status','closed')
            ->get();
    }

    public function countSpendingBaskets()
    {
        return $this->baskets()
            ->where('status','spending')
            ->count();
    }

    public function countAllBaskets()
    {
        return $this->baskets()->count();
    }

    public function getItemsFromBaskets(Collection $baskets)
    {
        $itemArr = [];
        $baskets->each(function($item) use(&$itemArr){
            $itemArr += [...$item->items];
        });
        return collect($itemArr);
    }

    public function getUnfinishBaskets(bool $closed = false)
    {
        $query = $this->baskets()
            ->where('finish',false);
        if($closed){
            $query->where('status','closed');
        }
        return $query->get();
    }

    public function markAsFinish(Collection $unFinishBaskets)
    {
        $unFinishBaskets->each(function($basket){
            $basket->finish = true;
            $basket->save();
        });
    }

    public function generateInvoice(array $data)
    {
        $invoice = $this->invoices()->create([
            'invoice_number' => $data['invoice_number'],
            'shipping_date' => $data['shipping_date'],
            'billing_place' => $data['billing_place']
        ]);
        return $invoice;
    }
}
