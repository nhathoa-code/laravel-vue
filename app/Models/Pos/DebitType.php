<?php

namespace App\Models\Pos;

use Illuminate\Database\Eloquent\Model;

class DebitType extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function storeDebitType(array $data)
    {
        return self::create($data);
    }
}
