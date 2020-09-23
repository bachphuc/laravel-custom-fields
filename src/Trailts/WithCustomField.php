<?php

namespace bachphuc\LaravelCustomFields\Trails;

use bachphuc\LaravelCustomFields\Models\Field;

trait WithCustomField
{
    public function field($field){
        return Field::getValueFor($this, $field);
    }   

    public function hasCustomField($field){
        return Field::hasCustomField($this, $field);
    }
}