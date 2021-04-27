<?php

namespace bachphuc\LaravelCustomFields\Http\Controllers;

use Illuminate\Http\Request;
use bachphuc\LaravelHTMLElements\Components\Form;
use CustomField;
use bachphuc\LaravelCustomFields\Models\Field;

class FieldsController extends ManageBaseController
{
    protected $modelName = 'field';
    protected $model = '\bachphuc\LaravelCustomFields\Models\Field';
    protected $activeMenu = 'fields';
    protected $searchFields = ['title'];
    protected $modelRouteName = 'admin.fields';

    protected $itemDisplayField = 'title';
    protected $objectType = '';

    public function processTable(&$table){

    }

    public function createFormElements($isUpdate = false){
        return [
            'title' => [
                'validator' => 'required'
            ],
            'alias' => [
                'field' => 'title',
                'with_id' => false,
                'allow_edit' => true,
                'separate' => '_'
            ],
            'field_type' => [
                'validator' => 'required',
                'type' => 'select',
                'options' => [
                    'data' => Field::supportTypes()
                ]
            ],
            'data_type' => [
                'type' => 'select',
                'options' => [
                    'data' => Field::supportDataTypes()
                ]
            ],
            'object_type' => [
                'type' => empty($this->objectType) ? 'text' : 'hidden',
                'value' => $this->objectType
            ],
            'options',
            'is_disabled' => [
                'type' => 'checkbox'
            ],
            'user'
        ];
    }

    public function createTableFields(){
        return [
            'id',
            'title',
            'alias',
            'field_type',
            'data_type',
            'object_type',
            'options',
            'is_disabled',
        ];
    }

    public function afterStore(Request $request, $item, $data){
        if($request->has('options')){
            $item->createOptions([
                'options' => $request->input('options')
            ]);
        }
    }

    public function afterUpdate(Request $request, $item){
        if($request->has('options')){
            $item->createOptions([
                'options' => $request->input('options')
            ]);
        }
    }
}