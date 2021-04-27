<?php
    
namespace bachphuc\LaravelCustomFields;

use bachphuc\LaravelHTMLElements\Components\Form;
use bachphuc\LaravelCustomFields\Models\Field;

class CustomField
{
    public function field($objectType, $params = []){
        return Field::findByObjectType($objectType, $params);
    }

    public function convertFieldsToArray($fields){
        return Field::displays($fields);
    }
}