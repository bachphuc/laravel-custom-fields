<?php

namespace bachphuc\LaravelCustomFields\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class CustomFieldsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         ManageCommand::class,
        //     ]);
        // }
        $packagePath = dirname(__DIR__);
      
        // $this->publishes([
        //     $packagePath . '/Config/elements.php' => config_path('elements.php'),
        // ], 'elements-config');

        // boot translator
        // $this->loadTranslationsFrom($packagePath . '/resources/lang' , 'elements');

        // publish translator
        // $this->publishes([
        //     $packagePath . '/resources/lang' => resource_path('lang/vendor/elements'),
        // ], 'elements-lang');

        // register view
        $this->loadViewsFrom($packagePath . '/resources/views', 'bachphuc.fields');

        // $this->publishes([
        //     $packagePath . '/resources/views' => resource_path('views/vendor/elements'),
        // ], 'elements-views');

        // $this->publishes([
        //     $packagePath . '/public' => public_path('vendor/elements'),
        // ], 'elements-assets');

        // load migrations
        $this->loadMigrationsFrom($packagePath.'/database/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */

        $packagePath = dirname(__DIR__);

        // register config
        // $this->mergeConfigFrom(
        //     $packagePath . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'elements.php' , 'elements'
        // );

        $this->app->bind('custom_field', function(){
            return new \bachphuc\LaravelCustomFields\CustomField();
        });
    }
}
