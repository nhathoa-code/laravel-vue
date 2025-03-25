<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book\BookItem;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'homebranchs' => 'nullable|array',
            'homebranchs.*' => 'required|exists:libraries,id', 
            'holdingbranchs' => 'nullable|array',
            'holdingbranchs.*' => 'required|exists:libraries,id', 
            'locs' => 'nullable|array',
            'locs.*' => 'required|exists:authorized_values,id',
            'ccodes' => 'nullable|array',
            'ccodes.*' => 'required|exists:authorized_values,id',
            'fields' => 'nullable|array',
            'fields.*.c' => 'required|in:and,or',
            'fields.*.f' => 'required|string',
            'fields.*.op' => 'required|in:=,!=',
            'fields.*.q' => 'required|string|min:1',
        ]);
        $query = BookItem::join('books as b','b.id','=','book_items.book_id');
        if(isset($validated['homebranchs'])){
            $query->orWhere(function($query) use($request,$validated){
                if($request->query('homebranch_op') == '='){
                    $query->whereIn('home_library',$validated['homebranchs']);
                }else{
                    $query->whereNotIn('home_library',$validated['homebranchs']);
                }
            });
        }
        if(isset($validated['holdingbranchs'])){
            $query->orWhere(function($query) use($request,$validated){
                if($request->query('holdingbranch_op') == '='){
                    $query->whereIn('current_location',$validated['holdingbranchs']);
                }else{
                    $query->whereNotIn('current_location',$validated['holdingbranchs']);
                }
            });
        }
        if(isset($validated['locs'])){
            $query->orWhere(function($query) use($request,$validated){
                if($request->query('loc_op') == '='){
                    $query->whereIn('shelving_location',$validated['locs']);
                }else{
                    $query->whereNotIn('shelving_location',$validated['locs']);
                }
            });
        }
        if(isset($validated['ccodes'])){
            $query->join('book_collection as bc','bc.book_id','=','b.id');
            $query->orWhere(function($query) use($request,$validated){
                if($request->query('ccode_op') == '='){
                    $query->whereIn('bc.collection_id',$validated['ccodes']);
                }else{
                    $query->whereNotIn('bc.collection_id',$validated['ccodes']);
                }
            });
        }
        if(isset($validated['fields'])){
            foreach($validated['fields'] as $field){
                if($field['c'] == 'and'){
                    $query->where(function($query) use($field){
                        $query->where('b.' + $field['f'],$field['c'],$field['q']);
                    });
                }else{
                    $query->orWhere(function($query) use($field){
                        $query->where('b.' + $field['f'],$field['c'],$field['q']);
                    });
                }
            }
        }
        return $query->get();
    }
}
