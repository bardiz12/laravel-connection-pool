<?php

namespace Bardiz12\LaravelConnectionPool\Connection;

use Illuminate\Contracts\Foundation\Application;
use OpenSwoole\Core\Coroutine\Client\PDOClient;
use OpenSwoole\Core\Coroutine\Pool\ClientPool;
use OpenSwoole\Core\Coroutine\Client\PDOConfig;
use OpenSwoole\Core\Coroutine\Client\PDOClientFactory;
use OpenSwoole\Coroutine as Co;

class ConnectionPool
{
    private $initFinish = FALSE;
    protected array $pools = [];

    protected ?Application $app;

    public function get($name): PDOClient
    {
        $name = $this->getConnectionName($name);
        $this->load($name);
        return $this->pools[$name]->get();
    }

    public function load($name)
    {
        if (!isset($this->pools[$name])) {
            echo "init " . $name . "\n";
            // $pools[$name] = 
            $dbConfig = $this->app['config']['database']['connections'][$name];
            // dump($dbConfig);
            $poolSize = $dbConfig['pool_count'] ?? ClientPool::DEFAULT_SIZE;
            $fill = $dbConfig['fill'] ?? false;
            $config = (new PDOConfig())
                ->withHost($dbConfig['write']['host'] ?? $dbConfig['host'])
                ->withDbname($dbConfig['database'])
                ->withUsername($dbConfig['username'])
                ->withPassword($dbConfig['password'])
                ->withDriver(
                    match ($dbConfig['driver']) {
                        'mysql-pool' => 'mysql',
                        default => $dbConfig['driver']
                    }
                )
                ->withPort($dbConfig['port'])
                ->withCharset($dbConfig['charset'])
                ->withOptions($dbConfig['options'] ?? []);

            $pool   = new ClientPool(
                factory: PDOClientFactory::class,
                config: $config,
                size: $poolSize
            );

            if ($fill) {
                $pool->fill();
            }
            $this->pools[$name] = $pool;
        }
    }

    public function putBack($name, $client)
    {
        return $this->pools[$name]->put($client);
    }

    protected function getConnectionName($name)
    {
        return $name === 'default'
            ? $this->app['config']['database']['default']
            : $name;
    }

    public function setApp(Application $app)
    {
        $this->app = $app;
        return $this;
    }

    public function initAll()
    {
        $connections = ($this->app['config']['database']['connections']);

        foreach ($connections as $connection => $value) {
            if ($value['driver'] === 'mysql-pool') {
                $this->load($connection);
            }
        }

        $this->initFinish = true;
        return $this;
    }

    public function isInitFinished()
    {
        return $this->initFinish;
    }
}
