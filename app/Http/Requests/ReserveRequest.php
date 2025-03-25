<?php

namespace App\Http\Requests;

use App\Models\Administration\Library;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReserveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notes' => 'nullable|string',
            'pickup_library' => 'required|exists:libraries,id',
            'book_item_id' => 'nullable|exists:book_items,id',
            'expires_on' => 'nullable|date|after_or_equal:today',
            'method' => 'required|string|in:available-item,specific-item'
        ];
    }

    public function after(): array
    {
        $user = $this->route('user');
        $book = $this->route('book');
        return [
            function (Validator $validator) use($user,$book){
                $validated = $this->validated();
                if ($validator->errors()->isEmpty()) {
                    if(
                        $user->userMeta->library && 
                        $user->userMeta->library->id != $validated['pickup_library']
                    ){
                        $validator->errors()->add("message", "Pickup library not allowed for the user");
                        return;
                    }   
                    if($validated['method'] == 'specific-item'){
                        if(!isset($validated['book_item_id'])){
                            $validator->errors()->add("message", "Please provide a book item id");
                            return;
                        }
                        if(!$book->items()->where('id',$validated['book_item_id'])->exists()){
                            $validator->errors()->add("message", "Book item does not belong to the book");
                            return;
                        }
                        $library = Library::findOrFail($validated['pickup_library']);
                        if(!$library->bookItems()->where('id',$validated['book_item_id'])->exists()){
                            $validator->errors()->add("message", "Book item does not belong to the Pickup Library");
                            return;
                        }
                    }
                }
            }
        ];
    }
}
