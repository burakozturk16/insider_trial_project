<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RoundRobin;

class RoundRobinServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("RoundRobin", function($app){
            return new RoundRobin;
        });
    }

    /**
     * Bootstrap services.s
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
