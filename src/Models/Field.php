<?php

namespace bachphuc\LaravelCustomFields\Models;

use bachphuc\LaravelCustomFields\Models\FieldItem;
use bachphuc\LaravelCustomFields\Models\FieldOption;

class Field extends FieldBase
{
    protected $table = 'dsoft_fields';
    protected $itemType = 'dsoft_field';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'field_type', 'title', 'alias', 'category_id', 'category_alias', 'object_type', 'is_disabled', 'options', 'data_type',
    ];

    public function user(){
        return $this->belongsTo('\App\User', 'user_id');
    }

    public static function queryField($name, $params = []){
        $query = Field::where('alias', $name);
        if(isset($params['category_alias'])){
            $query->where('category_alias', $params['category_alias']);
        }
        if(isset($params['object_type'])){
            $query->where('object_type', $params['object_type']);
        }
        return $query->first();
    }   

    public static function createField($data = [], $params = []){
        $data['user_id'] = user_id();
        if(!isset($data['alias']) && isset($data['title'])){
            $data['alias'] = strtolower(str_slug($data['title']));
        }
        if(!isset($data['field_type']) || empty($data['field_type'])){
            $data['field_type'] = 'text';
        }
        $field = Field::create($data);

        $field->createOptions($params);

        return $field;
    }

    public function createOptions($params = []){
        if(!$this->isSelect() && !$this->isSelectMulti()){
            return;
        }
        if(isset($params['options']) && !empty($params['options'])){
            $options = $params['options'];
            if(is_string($options)){
                $options = explode(',', $options);
            }

            $strOptions = implode(',', $options);
            if($this->options != $strOptions){
                $this->options = $strOptions;
                $this->save();
            }

            $ids = [];
            foreach($options as $opt){
                $value = is_array($opt) ? $opt['value'] : $opt;
                $title = is_array($opt) ? $opt['title'] : $opt;

                // create or update option
                $option = FieldOption::where('field_id', $this->id)
                ->where('value', $value)
                ->first();
                
                if(!$option){
                    $option = FieldOption::create([
                        'user_id' => user_id(),
                        'field_id' => $this->id,
                        'title' => $title,
                        'value' => $value
                    ]);
                }
                else{
                    if($option->title != $title){
                        $option->update([
                            'title' => $title
                        ]);
                    }
                }

                $ids[] = $option->id;
            }

            // remove delete options
            FieldOption::where('field_id', $this->id)
            ->whereNotIn('id', $ids)
            ->delete();
        }
    }

    public function isText(){
        if(empty($this->field_type)) return true;
        return $this->field_type == 'text' || $this->field_type == 'text_content';
    }

    public function isSelect(){
        return $this->field_type == 'select';
    }

    public function isSelectMulti(){
        return $this->field_type == 'select_multi';
    }

    public function addItem($item, $value){
        $fieldItem = $this->getFieldItem($item);
        $strOptions = 0;
        $strSearchValues = '';
        $strSearchIds = '';

        if($this->isSelect()){
            $option = FieldOption::findByValue($this, $value);
            $strOptions = $option ? $option->id : 0;
        }
        else if($this->isSelectMulti()){
            $fieldItem = $this->getFieldItem($item);
            $options = FieldOption::findByValues($this, $value);
            $optionIds = [];
            $searchIds = [];
            $searchValues = [];
            foreach($options as $opt){
                $optionIds[] = $opt->id;
                $searchIds[] = '[' . $opt->id . ']';
                $searchValues[] = '[' . $opt->value . ']';
            }
            $strOptions = implode(',', $optionIds);
            $strSearchIds = implode('', $searchIds);
            $strSearchValues = implode('', $searchValues);
        }

        if(!$fieldItem){
            $fieldItem = FieldItem::create([
                'user_id' => user_id(), 
                'field_id' => $this->id, 
                'item_type' => $item->getType(), 
                'item_id' => $item->getId(), 
                'value' => $value, 
                'option_id' => $strOptions,
                'field_alias' => $this->alias,
                'search_values' => $strSearchValues,
                'search_ids' => $strSearchIds
            ]);
        } 
        else{
            // update value
            if($fieldItem->value != $value){
                $fieldItem->update([
                    'value' => $value,
                    'option_id' => $strOptions
                ]);
            }
        }       
    }

    public function getFieldItem($item){
        $item = FieldItem::where('field_id', $this->id)
        ->where('item_type', $item->getType())
        ->where('item_id', $item->getId())
        ->first();

        return $item;
    }

    public function getValue($item){
        if($this->isText()){
            $value = $this->getFieldItem($item);
            if($value){
                return $value->value;
            }
            return '';
        }
        else if($this->isSelect()){
            $value = $this->getFieldItem($item);
            if($value){
                return $value->value;
            }
            return '';
        }
        else if($this->isSelectMulti()){
            $value = $this->getFieldItem($item);
            if($value){
                return $value->value;
            }
            return '';
        }
    }

    public function getFieldType(){
        return $this->field_type;
    }

    public function getOptions(){
        if(empty($this->options)) return [];
        return explode(',', $this->options);
    }

    public static function findByObjectType($objectType, $params = []){
        $query = Field::where('object_type', $objectType)
        ->where('is_disabled', 0);

        if(isset($params['field_type'])){
            $query->where('field_type', $params['field_type']);
        }
        
        return $query->get();
    }

    public function element(){
        $customElement = '\bachphuc\LaravelCustomFields\Components\CustomFieldElement';
        return [
            'title' => $this->title,
            'type' => $customElement,
            'object_type' => $this->object_type,
            'field' => $this->alias,
            'field_type' => $this->field_type
        ];
    }

    public static function supportTypes(){
        return  [
            'text',
            'text_content',
            'select',
            'select_multi'
        ];
    }

    public static function supportDataTypes(){
        return [
            'text',
            'number',
            'color',
        ];
    }

    public static function getValueFor($item, $field){
        $fieldItem = FieldItem::where('item_type', $item->getType())
        ->where('item_id', $item->getId())
        ->where('field_alias', $field)
        ->first();
        
        return $fieldItem ? $fieldItem->value : null;
    }

    public static function hasCustomField($item, $field){
        // TODO: optimize custom field for item
        $fieldItem = FieldItem::where('item_type', $item->getType())
        ->where('item_id', $item->getId())
        ->where('field_alias', $field)
        ->first();
        
        return $fieldItem ? true : false;
    }

    public function display(){
        $results = parent::toArray();
        $results['options'] = $this->getOptions();
        return $results;
    }
}