# Connection Pool Driver for Mysql

This Package is using **openswoole** to achieve connection pooling in Laravel.


## Supported Database :

- MySql

## Instalation :
- ```composer require "bardiz12/laravel-connection```
- setup database config
    - change database's driver to `mysql-pool`
    - you can set `pool_count` config to define default connection that will be made
    - you can set `fill` config to define if the connection should auto fill the connection pool.
- example : 
    ```php
    ///config/database.php
    <?php
    ...
    'mysql' => [
            'driver' => 'mysql-pool',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            // 'fill' => true,
            // 'pool_count' => 11
        ],
    ...
    ```



## What this package do : 
- Override laravel's **DatabaseManager class** with custom **DatabaseManager Class**.
- Override laravel's **ConnectionFactory class**
- Retrive a connection from pool when Laravel call ```DatabaseManager::connection(string $name)``` if the database config is set to ```mysql-pool```

## Known Bug : 
- Cannot handle transaction using laravel way 

## TODO :
- Create custom transaction method for pooling
- Handle Schema Function (mainly for migration)
- Write test case