<?php

namespace App\Http\Requests;

use App\Models\Acquisition\BasketItem;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class InvoiceRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vendor = $this->route('vendor');
        $baskets = $vendor->getUnfinishBaskets(true);
        $ids = $baskets->map(function($item){return $item->id;});
        return [
            'invoice_number' => 'required|string',
            'shipping_date' => 'required|date|after_or_equal:today',
            'billing_place' => 'required|exists:libraries,id',
            'items' => 'required|array|filled',
            'items.*.basket_item' => [
                'bail',
                'required', 
                Rule::exists('basket_items','id')->where(function (Builder $query) use($ids){
                    $query->whereIn('basket_id',$ids);
                }),
            ],
            'items.*.received' => ['required','integer','min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isEmpty()) {
                    $items = $this->post('items');
                    foreach($items as $item){
                        $basketItem = BasketItem::with('book')->find($item['basket_item']);
                        if($item['received'] > $basketItem->quantity){
                            $validator->errors()->add("exceed", "Quantity of {$basketItem->book->title} exceeds " . $basketItem->quantity);
                            return;
                        }
                    }
                }
            }
        ];
    }
}
