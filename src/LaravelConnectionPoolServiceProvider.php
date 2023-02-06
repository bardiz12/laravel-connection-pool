<?php

namespace Bardiz12\LaravelConnectionPool;

use Closure;
use OpenSwoole\Coroutine as Co;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Connection as LaravelConnection;
use Bardiz12\LaravelConnectionPool\Database\DatabaseManager;
use Bardiz12\LaravelConnectionPool\Connection\ConnectionPool;
use Bardiz12\LaravelConnectionPool\Database\MysqlPoolConnection;
use Bardiz12\LaravelConnectionPool\Database\Connectors\ConnectionFactory;
use Bardiz12\LaravelConnectionPool\Database\Connectors\MySqlPoolConnector;

class LaravelConnectionPoolServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('laravel-connection-pool', function ($app) {
            return (new ConnectionPool())->setApp($app)->initAll();
        });

        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });

        $this->loadMysqlPoolResolver();
    }

    private function loadMysqlPoolResolver()
    {
        $this->app->bind('db.connector.mysql-pool', MySqlPoolConnector::class);

        Connection::resolverFor('mysql-pool', function ($connection, $database, $prefix, $config) {
            return new MysqlPoolConnection($connection, $database, $prefix, $config);
        });
    }
}
