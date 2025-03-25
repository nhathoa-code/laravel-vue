<?php

namespace App\Models\Acquisition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BudgetFund extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'funds';

    public function countBaseLevelOrdered(Collection $orders)
    {
        $baseLevelOrdered = 0;
        $orders->each(function($item) use(&$baseLevelOrdered){
            $baseLevelOrdered += $item->quantity * $item->vendor_price;
        });
        return $baseLevelOrdered;
    }

    public function countTotalSpent(Collection $invoiceDetails)
    {
        $totalSpent = 0;
        $invoiceDetails->each(function($item) use(&$totalSpent){
            $totalSpent += $item->cost * $item->quantity;
        });
        return $totalSpent;
    }

    public function checkPermission()
    {
        $user = Auth::user();
        switch($this->permission){
            case 0:
                return true;
                break;
            case 1:
                if($this->owner == $user->id){
                    return true;
                }
                return false;
                break;
            case 2:
                if($this->owner == $user->id){
                    return true;
                }
                if(in_array($user->id,$this->users ?? [])){
                    return true;
                } 
                return false;
                break;
            case 3:
                if(
                    $this->owner == $user->id && 
                    $this->library == $user->userMeta->library_id
                ){
                    return true;
                }
                if(
                    in_array($user->id,$this->users ?? []) && 
                    $this->library = $user->userMeta->library_id
                ){
                    return true;
                } 
                return false;
                break;
        }
    }

    public function orders(): HasMany
    {
        return $this->hasMany(BasketItem::class,'fund_id');
    }

    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceItem::class,'fund_id');
    }

    protected $casts = [
        'users' => 'array',
    ];
}
