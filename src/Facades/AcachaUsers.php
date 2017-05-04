<?php

namespace Acacha\Users\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class AcachaUsers.
 */
class AcachaUsers extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AcachaUsers';
    }
}
