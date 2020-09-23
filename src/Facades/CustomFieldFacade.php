<?php

namespace bachphuc\LaravelCustomFields\Facades;

use Illuminate\Support\Facades\Facade;

class CustomFieldFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'custom_field'; }

    /**
     * Register the routers for shopy.
     *
     * @return void
     */
    public static function routes($params = [])
    {
        $router = static::$app->make('router');

        $defaultNamespace = '\bachphuc\LaravelCustomFields\Http\Controllers\\';
        $namespace = isset($params['namespace']) ? $params['namespace'] : $defaultNamespace;

        $router->resource('fields', $namespace . 'FieldsController');
    }
}