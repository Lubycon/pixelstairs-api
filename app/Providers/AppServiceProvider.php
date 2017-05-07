<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

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
