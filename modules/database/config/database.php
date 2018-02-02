<?php defined('SYSPATH') or die('No direct access allowed.');

return [
    'default' => [
        'type' => 'MySQL',
        'connection' => [
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             * array    variables    system variables as "key => value" pairs
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname' => 'localhost',
            'database' => 'kohana',
            'username' => false,
            'password' => false,
            'persistent' => false,
        ],
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => false,
    ],
    'alternate' => [
        'type' => 'PDO',
        'connection' => [
            /**
             * The following options are available for PDO:
             *
             * string   dsn         Data Source Name
             * string   username    database username
             * string   password    database password
             * boolean  persistent  use persistent connections?
             */
            'dsn' => 'mysql:host=localhost;dbname=kohana',
            'username' => 'root',
            'password' => 'r00tdb',
            'persistent' => false,
        ],
        /**
         * The following extra options are available for PDO:
         *
         * string   identifier  set the escaping identifier
         */
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => false,
    ],
];
