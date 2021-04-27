<?php

if (!function_exists('fields')) {
    function fields($field, $params = []){ 
        return CustomField::field($field, $params);
    }
}