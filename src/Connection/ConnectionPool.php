<?php

namespace Bardiz12\LaravelConnectionPool\Connection;

use OpenSwoole\Core\Coroutine\Pool\ClientPool;
use OpenSwoole\Core\Coroutine\Client\PDOConfig;
use OpenSwoole\Core\Coroutine\Client\PDOClientFactory;

class ConnectionPool
{
    public static $pools = [];

    public static function get($name): ClientPool
    {
        $name = $name === 'default' ? config('database.default') : $name;
        self::init([$name]);
        echo "Get " . $name . "\n";
        return self::$pools[$name];
    }

    public static function init($connections)
    {
        try {
            foreach ($connections as $name) {
                $name = $name === 'default' ? config('database.default') : $name;
                if (!isset(self::$pools[$name])) {
                    // $pools[$name] = 
                    $dbConfig = config('database.connections.' . $name);
                    // dump($dbConfig);
                    $config = (new PDOConfig())
                        ->withHost($dbConfig['write']['host'] ?? $dbConfig['host'])
                        ->withDbname($dbConfig['database'])
                        ->withUsername($dbConfig['username'])
                        ->withPassword($dbConfig['password'])
                        ->withDriver($dbConfig['driver'])
                        ->withPort($dbConfig['port'])
                        ->withCharset($dbConfig['charset'])
                        ->withOptions($dbConfig['options'] ?? []);

                    $pool   = new ClientPool(PDOClientFactory::class, $config);

                    echo "INIT $name\n";
                    self::$pools[$name] = $pool;
                }
            }
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public static function putBack($name, $client)
    {
        echo "Put back " . $name . "\n";
        return self::$pools[$name]->put($client);
    }
}
