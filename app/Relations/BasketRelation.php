<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Acquisition\BasketItem;
use App\Models\User\User;
use App\Models\Acquisition\Vendor;
use App\Models\Administration\Library;

trait BasketRelation
{
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function billingPlace(): BelongsTo
    {
        return $this->belongsTo(Library::class,'billing_place');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BasketItem::class,'basket_id');
    }
}