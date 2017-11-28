<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\UserRole;
use App\Services\UserRoleService;
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
        /**
         * Models
         */
        $this->app->bind('Model.User', function($app) {
            return new User();
        });

        $this->app->bind('Model.UserRole', function($app) {
            return new UserRole();
        });

        $this->app->bind('Model.Category', function($app) {
            return new Category();
        });

        $this->app->bind('Model.Post', function($app) {
            return new Post();
        });

        /**
         * Services
         */
        $this->app->bind('Service.UserRoleService', function($app) {
            return new UserRoleService();
        });
    }
}
