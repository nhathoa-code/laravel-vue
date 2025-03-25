<?php

namespace App\Models\Circulation;

use App\Models\Book\BookItem;
use App\Models\Commons\Option;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Relations\CirculationRelation;

class Circulation extends Model
{
    use CirculationRelation;

    protected $table = 'loans';
    public $timestamps = false;

    public function checkout(User $patron,BookItem $bookItem)
    {
        $this->checkout_date = Carbon::now()->toDateString();
        $this->due_date = Carbon::now()->addMonth()->toDateString();
        $this->user_id = $patron->id;
        $this->book_item_id = $bookItem->id;
        $this->save();    
    }

    public function isCheckedOut(BookItem $bookItem)
    {
        return $this->where("book_item_id",$bookItem->id)->exists();
    }

    public function getCirculation(BookItem $bookItem)
    {
        return $this->where("book_item_id",$bookItem->id)->first();
    }

    public function checkin(BookItem $bookItem)
    {
        $this->where("book_item_id",$bookItem->id)->limit(1)->delete();
    }

    public function renew(BookItem $bookItem,string|null $dueDate = null)
    {
        $rules = $this->getRules();
        if($rules){
            $due_date = $rules->loan_period;
        }else{
            $due_date = $dueDate ?? 30;
        }
        $this->where("book_item_id",$bookItem->id)->limit(1)->update(
            ['due_date' => Carbon::now()->addDays($due_date)->toDateString()]
        );
    }

    public function getOverdues()
    {
        return $this->where('due_date','<',Carbon::now())
            ->with(['patron','bookItem.book'])    
            ->get();
    }

    public function defineRules(array $data)
    {
        Option::updateOrInsert(
            ['key' => 'circulation_rules'],
            ['value' => json_encode($data)]
        );
    }

    public function getRules()
    {
        return Option::get('circulation_rules');
    }

    protected function casts()
    {
        return [
            'due_date' => 'datetime:d-m-Y',
            'checkout_date' => 'datetime:d-m-Y'
        ];
    }
}
