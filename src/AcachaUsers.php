<?php

namespace Acacha\Users;

/**
 * Class AcachaUsers.
 */
class AcachaUsers
{
    /**
     * Languages assets copy path.
     *
     * @return array
     */
    public function languages()
    {
        return [
            ACACHA_USERS_PATH.'/resources/lang' => resource_path('lang/vendor/acacha_users_lang'),
        ];
    }

    /**
     * Views copy path.
     *
     * @return array
     */
    public function views()
    {
        return [
            ACACHA_USERS_PATH.'/resources/views/managment.blade.php' =>
                resource_path('views/vendor/acacha_users/managment.blade.php')
        ];
    }

    /**
     * Config auth copy path.
     *
     * @return array
     */
    public function configAuth()
    {
        return [
            ACACHA_USERS_PATH.'/config/auth.php' =>
                config_path('auth.php')
        ];
    }

    /**
     * Config auth copy path.
     *
     * @return array
     */
    public function configUsers()
    {
        return [
            ACACHA_USERS_PATH.'/config/users.php' =>
                config_path('users.php')
        ];
    }

    /**
     * Seeds copy path.
     *
     * @return array
     */
    public function seeds()
    {
        return [
            ACACHA_USERS_PATH.'/database/seeds' =>
                database_path('seeds')
        ];
    }

    /**
     * Factories copy path.
     *
     * @return array
     */
    public function factories()
    {
        return [
            ACACHA_USERS_PATH.'/database/factories' =>
                database_path('factories')
        ];
    }
}
