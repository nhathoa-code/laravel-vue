<?php

namespace App\Relations;

use App\Models\Acquisition\BudgetFund;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait BudgetRelation
{
    public function funds(): HasMany
    {
        return $this->hasMany(BudgetFund::class,'budget_id');
    }
}