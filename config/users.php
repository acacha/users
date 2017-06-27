<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Register spatie permission service provider?
    |--------------------------------------------------------------------------
    | Set this value to true to register Spatie\Permission\PermissionServiceProvider.
    | Remember to manually register the service provider in your config/app.php file
    | if this value is set to false.
    |
    */
    'register_spatie_permission_service_provider' => true,

    /*
    |--------------------------------------------------------------------------
    | Register Laravel passport service provider?
    |--------------------------------------------------------------------------
    | Set this value to true to register Laravel\Passport\PassportServiceProvider.
    | Remember to manually register the service provider in your config/app.php
    | file if this value is set to false.
    |
    */
    'register_laravel_passport_service_provider' => true,

    /*
    |--------------------------------------------------------------------------
    | Register Acacha  Stateful Service Provider?
    |--------------------------------------------------------------------------
    | Set this value to true to register  Acacha\Stateful\Providers\StatefulServiceProvider.
    | Remember to manually register the service provider in your config/app.php file if
    | this value is set to false.
    |
    */

    'register_acacha_stateful_service_provider' => true,

    /*
    |-------------------------------------------------------------------------------------------------
    | Register Acacha  Stateful Service Provider?
    |-------------------------------------------------------------------------------------------------
    | This option will make public user invitations functionality so unauthenticated and unprivileged
    | users can invite other users.
    |
    | This functionallity is protected by rate limiting.
    */

    'users_can_invite_other_users' => true,

    /*
    |-------------------------------------------------------------------------------------------------
    | Users Migration source database connection
    |-------------------------------------------------------------------------------------------------
    | The connection name of source database connection name.
    |
    */

    'source_database_connection_name' => 'ebre_escool',

];
