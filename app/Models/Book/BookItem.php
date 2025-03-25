<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;
use App\Relations\BookItemRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookItem extends Model
{
    use HasFactory;
    use BookItemRelation;

    public $timestamps = false;
    protected $guarded = [];

    public static function findByBarcode(string $barcode)
    {
        return self::where("barcode",$barcode)->first();
    }

    public function increaseCheckouts()
    {
        $this->checkouts++;
        $this->save();
        return $this;
    }

    public function increaseRenewals()
    {
        $this->renewals++;
        $this->save();
        return $this;
    }

    public function updateLastSeen()
    {
        $this->last_seen = now()->toDateString();
        $this->save();
        return $this;
    }

    public function updateStatus(string $status)
    {
        $this->status = $status;
        $this->save();
        return $this;
    }

    protected function casts()
    {
        return [
            'date_acquired' => 'datetime:d-m-Y',
            'last_seen' => 'datetime:d-m-Y'
        ];
    }
}
