<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
     public function boot()
     {
         $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
         if ($this->app->environment() !== 'production') {
             $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
         }

         Validator::extend('base64', function ($attribute, $value, $params, $validator) {
             $explodeBase64 = explode('data:image/',$value);
             return count($explodeBase64) > 1;
         });
     }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
