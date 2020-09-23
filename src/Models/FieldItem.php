<?php

namespace bachphuc\LaravelCustomFields\Models;

class FieldItem extends FieldBase
{
    protected $table = 'dsoft_field_items';
    protected $itemType = 'dsoft_field_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'field_id', 'item_type', 'item_id', 'value', 'option_id', 'field_alias', 'search_values', 'search_ids',
    ];
}