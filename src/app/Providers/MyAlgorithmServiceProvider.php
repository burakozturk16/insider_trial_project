<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\MyAlgorithm;

class MyAlgorithmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("MyAlgorithm", function($app){
            return new MyAlgorithm;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
