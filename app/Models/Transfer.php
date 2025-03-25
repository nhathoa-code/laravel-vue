<?php

namespace App\Models;

use App\Relations\TransferRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    use TransferRelation;

    public $timestamps = false;
    protected $guarded = [];

    public function bookItem(): BelongsTo
    {
        return $this->belongsTo(BookItem::class,'book_item');
    }

    public function getTransfers(int|string $library)
    {
        return $this->where('to',$library)
            ->with('fromLibrary','bookItem.book','bookItem.homeLibrary')
            ->get()->groupBy('fromLibrary.name');
    }

    public function transfer(BookItem $bookItem,Library $library)
    {
        $insertedData = [
            'from' => 1,
            'to' => $library->id,
            'book_item' => $bookItem->id,
            'date_of_transfer' => now()->toDateString(),
        ]; 
        self::create($insertedData);
    }

    public function receive(string|int $transferId)
    {
        $transfer = self::findOrFail($transferId);
        $bookItem = $transfer->bookItem;
        $bookItem->current_location = 2;
        $bookItem->save();
        $transfer->delete();
    }

    public function cancel(string|int $transferId)
    {
        $transfer = self::findOrFail($transferId);
        $transfer->delete();
    }
}
