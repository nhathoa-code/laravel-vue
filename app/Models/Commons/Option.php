<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Option extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    private static function isJson(string $str)
    {
        json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    } 

    public static function get(string $key, bool $single = true)
    {
        $query = self::where('key',$key);
        if($single){
            $options = $query->first();
        }else{
            $options = $query->get();
        }
        if(!$options) return null;
        if ($options instanceof Model) {
            $value = $options->value;
            if(self::isJson($value)){
                return json_decode($value);
            }
        } elseif ($options instanceof Collection) {
            $arr = [];
            $options->each(function($item){
                if(self::isJson($item->value)){
                    $arr[] = json_decode($item->value);
                }
            });
            return $arr;
        }
    }
}
