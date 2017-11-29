<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $entitiesToRegister = [];

        foreach ($entitiesToRegister as $className) {
            $this->app->bind($className, function($app) use ($className) {
                return new $className();
            });
        }
    }
}
