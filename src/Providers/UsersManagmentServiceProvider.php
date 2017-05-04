<?php

namespace Acacha\Users\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class UsersManagmentServiceProvider.
 *
 * @package Acacha\Users\Providers
 */
class UsersManagmentServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     */
    public function register() {

    }

    /**
     * Bootstrap the application services.
     */
    public function boot() {
        $this->defineRoutes();
    }

    /**
     * Define the AdminLTETemplate routes.
     */
    protected function defineRoutes()
    {
        if (!$this->app->routesAreCached()) {
            $router = app('router');
            $router->group(['namespace' => 'Acacha\Users\Http\Controllers'], function () {
                require __DIR__.'/../Http/routes.php';
            });
        }
    }
}