<?php

namespace bachphuc\LaravelCustomFields\Models;

class Category extends FieldBase
{
    protected $table = 'dsoft_field_categories';
    protected $itemType = 'dsoft_field_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'alias', 'object_type',
    ];
}