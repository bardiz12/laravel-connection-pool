<?php

namespace Bardiz12\LaravelConnectionPool\Facades;

use Illuminate\Support\Facades\Facade;

class ConnectionPool extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'laravel-connection-pool';
    }
}
