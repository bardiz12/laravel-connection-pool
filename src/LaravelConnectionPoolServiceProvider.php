<?php

namespace Bardiz12\LaravelConnectionPool;

use Closure;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\MySqlConnection;
use Bardiz12\LaravelConnectionPool\Database\Connection;
use Illuminate\Database\Connection as LaravelConnection;
use Bardiz12\LaravelConnectionPool\Database\Connectors\ConnectionFactory;

class LaravelConnectionPoolServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->app->singleton('db.connection', Connection::class);
        // $this->app->alias('db.connection', Connection::class);
        
        // $this->app->bind(MySqlConnection::class)
        // $this->app->singleton('db.factory', function ($app) {
        //     return new ConnectionFactory($app);
        // });
    }
}
