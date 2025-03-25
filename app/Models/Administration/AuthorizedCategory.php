<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuthorizedCategory extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function values(): HasMany
    {
        return $this->hasMany(AuthorizedValue::class,'category_id')->with('library');
    }

    public function getOrCreate(string $category)
    {
        $authorizedCategory = $this->where('code',$category)->first();
        if(!$authorizedCategory){
            $authorizedCategory = $this->create(['code'=>$category]);
        }
        return $authorizedCategory;
    }

    public function storeCategoryValue(array $data)
    {
        $category = $data['category']; unset($data['category']); unset($data['library']);
        $authorizedCategory = $this->getOrCreate($category);
        $value = $authorizedCategory->values()->create($data);
        return $value;
    }
}
