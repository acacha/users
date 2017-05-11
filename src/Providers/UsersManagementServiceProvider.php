<?php

namespace Acacha\Users\Providers;

use Acacha\Stateful\Providers\StatefulServiceProvider;
use Acacha\Users\Models\UserInvitation;
use Acacha\Users\Observers\UserInvitationObserver;
use AcachaUsers;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

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

        if (config('acacha_users.register_spatie_permission_service_provider', true)) {
            $this->registerSpatiePermissionServiceProvider();
        }

        if (config('acacha_users.register_laravel_passport_service_provider', true)) {
            $this->registerLaravelPassportServiceProvider();
            Passport::routes();
        }

        if (config('acacha_users.register_acacha_stateful_service_provider', true)) {
            $this->registerAcachaStatefulServiceProvider();
            Passport::routes();
        }
    }

    /**
     * Register Spatie permissions service Provider.
     */
    protected function registerSpatiePermissionServiceProvider()
    {
        $this->app->register(PermissionServiceProvider::class);

        //TODO: publish spatie config file
        //Add Trait to App/User
    }

    /**
     * Register Laravel passport service Provider.
     */
    protected function registerLaravelPassportServiceProvider()
    {
        $this->app->register(PassportServiceProvider::class);

        //TODO: execute php artisan passport:install
        //Add Trait to App/User HasApiTokens
    }

    /**
     * Register Acacha Stateful service Provider.
     */
    protected function registerAcachaStatefulServiceProvider()
    {
        $this->app->register(StatefulServiceProvider::class);
    }

    /**
     * Bootstrap the application services.
     */
    public function boot() {
        $this->defineRoutes();

        //Publish
        $this->publishLanguages();
        $this->publishViews();
        $this->publishConfigAuth();
        $this->publishFactories();

        $this->loadMigrations();
        $this->publishSeeds();

        $this->defineObservers();
        
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

        $this->publishes(AcachaUsers::views(), 'acacha_users_views');
    }

    /**
     * Publish config auth.
     */
    private function publishConfigAuth() {
        $this->publishes(AcachaUsers::configAuth(), 'acacha_users_config');
    }

    /**
     * Load package migrations.
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(ACACHA_USERS_PATH .'/database/migrations');
    }

    /**
     * Publish seeds.
     */
    private function publishSeeds() {
        $this->publishes(AcachaUsers::seeds(), 'acacha_users_seeds');
    }

    /**
     * Publish factories.
     */
    private function publishFactories() {
        $this->publishes(AcachaUsers::factories(), 'acacha_users_factories');
    }

    /**
     * Define observers.
     */
    public function defineObservers()
    {
        UserInvitation::observe(UserInvitationObserver::class);
    }

}