<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Administration\Library;
use App\Models\Acquisition\InvoiceItem;
use App\Models\Acquisition\Vendor;

trait InvoiceRelation
{
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceItem::class,'invoice_id');
    }
    
    public function billingPlace(): BelongsTo
    {
        return $this->belongsTo(Library::class,'billing_place');
    }
}