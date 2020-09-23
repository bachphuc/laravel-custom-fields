<?php

namespace bachphuc\LaravelCustomFields\Components;

use bachphuc\LaravelHTMLElements\Components\BaseElement;
use bachphuc\LaravelCustomFields\Models\Field;
use bachphuc\LaravelCustomFields\Models\FieldItem;

class CustomFieldElement extends BaseElement
{
    protected $baseViewPath = 'bachphuc.fields';
    protected $viewPath = 'field';

    public function getTitle(){
        $title = $this->getAttribute('title');
        if(!empty($title)){
            return $title;
        }
        $title = $this->getAttribute('name');
        if(!empty($title)){
            return $title;
        }
        return null;
    }

    public function render($params = []){
        $this->setAttribute('title', title_case($this->getTitle()));
        return parent::render($params);
    }

    public function setFieldAttribute($value){
        $params = [];
        if(!empty($this->_data)){
            if(isset($this->_data['object_type'])){
                $params['object_type'] = $this->_data['object_type'];
            }
            if(isset($this->_data['category_type'])){
                $params['category_type'] = $this->_data['category_type'];
            }
        }
        
        $field = Field::queryField($value, $params);

        if($field){
            $this->setAttribute('currentField', $field);
            $this->setAttribute('field_type', $field->getFieldType());

            if($field->isSelect() || $field->isSelectMulti()){
                $options = $field->getOptions();
                $this->setAttribute('items', $options);
                $this->setAttribute('dataType', 'array');
            }
        }
    }

    public function process($item = null, $data = []){
        if(!$item) return;
        
        $name = $this->getAttribute('name');
        $value = $data[$name];

        $field = $this->getAttribute('currentField');
        if(!$field){
            $autoCreate = $this->getAttribute('auto_create', false);
            if($autoCreate){
                $fieldType = $this->getAttribute('field_type', 'text');
                $insert = [
                    'object_type' => $this->getAttribute('object_type'),
                    'title' => $this->getAttribute('field'),
                    'field_type' => $fieldType,
                ];
                $params = [];
                if($fieldType === 'select' || $fieldType === 'select_multi'){
                    $options = $this->getAttribute('options');
                    if($options && isset($options['data'])){
                        $params['options'] = $options['data'];
                    }
                }
                $field = Field::createField($insert, $params);
                // create field item
                $field->addItem($item, $value);
            }
        }
        else{
            $field->addItem($item, $value);
        }
    }

    public function setItem($item){
        parent::setItem($item);

        $field = $this->getAttribute('currentField');
        if($field){
            $value = $field->getValue($item);
            $this->setAttribute('value', $value);
        }
    }

    public function getViewPath(){
        $fieldType = $this->getAttribute('field_type', 'text');

        if($fieldType === 'select'){
            $this->viewPath = 'select';
        }
        else if($fieldType === 'select_multi'){
            $this->viewPath = 'select-multi';
        }
        else if($fieldType === 'text_content'){
            $this->viewPath = 'text-content';
        }

        return parent::getViewPath();
    }

    public function setOptionsAttribute($options){
        if(!empty($this->getAttribute('dataType'))) return;

        if(isset($options['model']) && !empty($options['model'])){
            $class = BaseElement::getModelClass($options['model']);
            if(!empty($class)){
                if(!isset($options['conditions']) || empty($options['conditions'])){
                    $items = $class::all();
                }
                else{
                    $query = null;
                    foreach($options['conditions'] as $key => $v){
                        if(!$query){
                            $query = $class::where($key, $v);
                        }
                        else{
                            $query->where($key, $v);
                        }

                        $items = $query->get();
                    }
                }
                $this->setAttribute('items', $items);
                $this->setAttribute('dataType', 'model');
            }
        }
        else if(isset($options['data']) && !empty($options['data'])){
            $this->setAttribute('items', $options['data']);
            $this->setAttribute('dataType', 'array');
        }
    }

    public function getFieldType(){
        return $this->getAttribute('field_type', 'text');
    }

    public function prepareProcess(&$item = null, &$data = []){
        $fieldType = $this->getFieldType();

        if($fieldType === 'select_multi'){
            $name = $this->getAttribute('name');
            if(!isset($data[$name])){
                $data[$name] = '';
            }
            $value = $data[$name];
            if(is_array($value)){
                $data[$name] = implode(',', $value);
            }
        }        
    }

    public function setValueAttribute($value){
        $fieldType = $this->getAttribute('field_type', 'text');
        if($fieldType === 'select_multi'){
            if(is_string($value)){
                $value = explode(',', $value);
            }
            $this->attributes['value'] = $value;
        }
    }

    public function resetDefaultValue(){
        $this->setValueAttribute([]);
    }
}