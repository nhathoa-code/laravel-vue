<?php

namespace App\Models\Acquisition;

use App\Relations\BudgetRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    use BudgetRelation;
    
    public $timestamps = false;
    protected $guarded = [];

    public function updateBudget(array $data)
    {
        $data['active'] = $data['active'] ?? false;
        $data['lock'] = $data['lock'] ?? false;
        $this->update($data);
    }

    public function getById(string $id)
    {
        $budget = self::findOrFail($id);
        $budget->load('funds');
        return $budget;
    }

    public function getTotalFundsAmount(BudgetFund $fund = null)
    {
        $total = 0;
        $this->funds->each(function($f) use(&$total,$fund){
            if($fund && $fund->id == $f->id) return;
            $total += $f->amount;
        }); 
        return $total;
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime:d-m-Y',
            'end_date' => 'datetime:d-m-Y'
        ];
    }
}
