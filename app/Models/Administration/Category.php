<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    public function storeCat(array $data) : self
    {
        $this->name = $data['name'];
        $this->parent_id = $data["parent"];
        $this->save();
        return $this;
    }

    public function updateCat(array $data,string $id) : Category
    {
        $category = self::findOrFail($id);
        $category->name = $data['name'];
        if($category->parent_id != $data["parent"]){
            if($category->hasChildren()){
                $category->getChildren()->each(function($item) use($category){
                    $item->parent_id = $category->parent_id;
                    $item->save();
                });
            }
            $category->parent_id = $data["parent"];
        }
        $category->save();
        return $category;
    }

    public function deleteCat(string $id) : void
    {
        $category = self::findOrFail($id);
        $category->delete();
    }

    public function hasChildren() : bool
    {
        return $this->where("parent_id",$this->id)
                    ->exists();
    }

    public function getChildren() : Collection
    {
        return $this->where("parent_id",$this->id)->get();
    }

    public function getAll(Collection|null $categories = null) : Collection
    {
        if(!$categories){
            $categories = $this->whereNull("parent_id")->get();
        }
        $categories->each(function ($category) {
            if($category->hasChildren()){
                $category->children = $this->getAll($category->getChildren());
            }
        });
        return $categories;
    }
}
