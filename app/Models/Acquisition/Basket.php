<?php

namespace App\Models\Acquisition;

use App\Relations\BasketRelation;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use BasketRelation;

    protected $guarded = [];
    public $timestamps = false;

    public function addItem(array $data)
    {
        $this->items()->create($data);
    }

    public function getItems()
    {
        $items = BasketItem::with(['fund','book'])
            ->where('basket',$this->id)->get();
        return $items;
    }

    public function getItemsByIds(array $items)
    {
        return BasketItem::with(['book','fund','basket.vendor'])
                ->whereIn('id',$items)
                ->get();
    }

    public function close()
    {
        $this->status = 'closed';
        $this->save();
    }

    function checkItemExists(string|int $bookId)
    {
        return $this->items()->where('book_id',$bookId)
            ->exists();
    }

    public function updateItemReceived(array $data)
    {
        $basketItem = BasketItem::findOrFail($data['item_id']);
        $basketItem->received = $data['received'];
        $basketItem->save();
    }

    public function updateStatus(string $status)
    {
        $this->status = $status;
        if($status == 'closed'){
            $this->closed_on = now()->toDateString();
        }
        $this->save();
    }

    protected function casts(): array
    {
        return [
            'opened_on' => 'datetime:d-m-Y',
            'closed_on' => 'datetime:d-m-Y',
        ];
    }
}
