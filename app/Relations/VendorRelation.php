<?php

namespace App\Relations;

use App\Models\Acquisition\Basket;
use App\Models\Acquisition\Invoice;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait VendorRelation
{
    public function baskets(): HasMany
    {
       return $this->hasMany(Basket::class,'vendor_id');
    }

    public function invoices(): HasMany
    {
       return $this->hasMany(Invoice::class,'vendor_id');
    }
}