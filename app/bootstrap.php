<?php

use Core\Container;
use Core\App;
use Core\Database\Database;

// Loading env file with "vlucas/phpdotenv" package
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new Container();
$config = require base_path("configs/databases.php");

foreach ($config['connections'] as $key => $connection) {
  // I added use ($connection) in the closure. This is important because the closure does not automatically 
  // have access to the $connection variable defined in the loop. The use statement allows you to bring the 
  // $connection variable into the closure's scope.
  $container->bind($key, function () use ($connection) {
    return Database::createConnection($connection);
  });
}

App::setContainer($container);
