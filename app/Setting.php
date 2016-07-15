<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $casts = [
        'value' => 'array'
    ];

    public static function get(string $key, $default=null){
        $record = self::where('key', $key)->first();
        if($record){
            return $record->value;
        }else{
            return $default;
        }
    }

    public static function set($key, $value, $name=''){
        $record = self::where('key', $key)->first();
        if($record){
            $record->value = $value;
            return $record->save();
        }else{
            $setting = new self;
            $setting->key = $key;
            $setting->value = $value;
            $setting->name = $name;
            return $setting->save();
        }
    }
}
