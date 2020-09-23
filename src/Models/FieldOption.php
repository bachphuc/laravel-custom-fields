<?php

namespace bachphuc\LaravelCustomFields\Models;

class FieldOption extends FieldBase
{
    protected $table = 'dsoft_field_options';
    protected $itemType = 'dsoft_field_option';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'field_id', 'title', 'value',
    ];

    public static function findByValue($field, $value){
        return FieldOption::where('field_id', $field->id)
        ->where('value', $value)
        ->first();
    }

    public static function findByValues($field, $values){
        if(is_string($values)){
            $values = explode(',', $values);
        }

        return FieldOption::where('field_id', $field->id)
        ->whereIn('value', $values)
        ->get();
    }
}