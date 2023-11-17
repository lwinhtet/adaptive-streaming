<?php

return [
  "default" => env('DB_CONNECTION', "mysql"),
  "connections" => [
    "mysql" => [
      'driver' => 'mysql',
      'host' => env('DB_HOST', 'mysql'),
      'port' => env('DB_PORT', '3306'),
      'dbname' => env('DB_DATABASE', 'vsp'),
      'username' => env('DB_USERNAME', 'lwinhtetthu'),
      'password' => env('DB_PASSWORD', 'password'),
      'charset' => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci',
    ],

    // "mysql2" => [
    //   'driver' => 'mysql',
    //   'host' => 'mysql',
    //   'port' => 3306,
    //   'dbname' => 'php_demo',
    //   // vsp
    //   'charset' => 'utf8mb4'
    // ]
  ]
];