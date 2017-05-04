<?php

namespace Acacha\Users\Providers;

use AcachaUsers;
use Illuminate\Support\ServiceProvider;

/**
 * Class UsersManagementServiceProvider.
 *
 * @package Acacha\Users\Providers
 */
class UsersManagementServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     */
    public function register() {
        if (!defined('ACACHA_USERS_PATH')) {
            define('ACACHA_USERS_PATH', realpath(__DIR__.'/../../'));
        }
        $this->app->bind('AcachaUsers', function () {
            return new \Acacha\Users\AcachaUsers();
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function boot() {
        $this->defineRoutes();

        //Publish
        $this->publishLanguages();
        $this->publishViews();
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

    /**
     * Publish package language to Laravel project.
     */
    private function publishLanguages()
    {
        $this->loadTranslationsFrom(ACACHA_USERS_PATH.'/resources/lang/', 'acacha_users_lang');

        $this->publishes(AcachaUsers::languages(), 'acacha_users_lang');
    }

    /**
     * Publish package views to Laravel project.
     */
    private function publishViews()
    {
        $this->loadViewsFrom(ACACHA_USERS_PATH.'/resources/views/', 'acacha_users');

        $this->publishes(AcachaUsers::views(), 'acacha_users');
    }
}