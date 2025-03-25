<?php

namespace App\Relations;

use App\Models\Book\BList;
use App\Models\Circulation\Circulation;
use App\Models\Circulation\Hold;
use App\Models\User\UserMeta;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserRelation
{
    public function loans(): HasMany
    {
        return $this->hasMany(Circulation::class);
    }

    public function lists(): HasMany
    {
        return $this->hasMany(BList::class, 'owner');
    }

    public function userMeta(): HasOne
    {
        return $this->hasOne(UserMeta::class,'user_id');
    }

    public function holds(): HasMany
    {
        return $this->hasMany(Hold::class,'patron');
    }
}