<?php

namespace App\Models\Administration;

use App\Models\Administration\Library;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorizedValue extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class,'fund_id');
    }
}
