<?php
use Core\Migration;
use Core\Schema;
use Core\Utilities\FileManager;
use Core\Database\Database;

/* To run php script with docker
Make sure you are running the php migrate.php command within the php container. This means you'll need 
to open a shell inside the php container to execute the command.
You can open a shell in the php container using the following command:
"docker-compose exec php bash" run this commend where docker-compose.yaml exist. Then, navigate to the 
/app directory and run php migrate.php.

If you're running the PHP script outside of the Docker environment (on your local machine), it won't be able to 
connect to the MySQL container using "mysql" as the hostname. When you run services in Docker, each service runs 
in its own isolated environment, including its own networking namespace. This means that within a Docker container, 
you can use service names as hostnames to communicate with other containers on the same Docker network.
However, if you're running a script outside of the Docker environment (on your local machine), it doesn't have direct
access to the Docker networking namespace. As a result, it can't resolve service names like mysql to an IP address 
because they are not known in your local machine's networking configuration.
For example, if you're running the PHP script on your local machine, it doesn't have access to Docker's internal 
DNS resolution system. So, if you use mysql as the hostname, your local machine won't know how to resolve that 
to an actual IP address.
To connect to a MySQL server running in a Docker container from your local machine, you would typically use the 
IP address or hostname of your local machine and configure Docker to forward the necessary ports (in this case, 
port 3306 for MySQL) to your local machine.
*/
const BASE_PATH = __DIR__ . '/';

session_start();

require BASE_PATH . 'vendor/autoload.php';
require BASE_PATH . 'utils/error_handler.php';
require BASE_PATH . 'utils/functions.php';

// Loading env file with "vlucas/phpdotenv" package
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// setting db connection
$connection = Database::createAndGetConnection();
Schema::setDefaultConnection($connection);

$db_connection = env('DB_CONNECTION', 'mysql');
$config = require base_path('configs/databases.php');

if (!isset($argv[1])) {
  throw new Exception("Empty operation key '{$argv[1]}'");
}

$arg1 = $argv[1];
$arg2 = $argv[2] ?? null;

if ($arg1 !== '--up' && $arg1 !== '--down' && $arg1 !== '--create') {
  throw new Exception("No operation for key {$arg1}");
}

if ($arg1 === '--up') {
  $executedMigrations = Migration::getExecutedMigrations();

  $migrationFiles = Migration::getFiles();
  // dd($executedMigrations);
  foreach ($migrationFiles as $file) {
    // 20231104050236_create_migrations_table
    $fileName = basename($file, '.php');
    // CreateMigrationsTable
    $className = Migration::getClassName($fileName);

    if (!in_array($fileName, $executedMigrations)) {
      // if the code from a file has already been included, it will not be included again
      include_once $file;

      $migration = new $className();
      $result = $migration->up();

      // store migration record
      if ($result !== false) {
        Migration::insertNew($fileName);
      }
      // Update executed migrations
      $executedMigrations[] = $fileName;
    }
  }
}

if ($arg1 === '--down') {
  $migrationFiles = Migration::getFiles(isReverse: true);

  foreach ($migrationFiles as $file) {
    $fileName = basename($file, '.php');
    $className = Migration::getClassName($fileName);

    // if the code from a file has already been included, it will not be included again
    include_once $file;
    $migration = new $className();
    $migration->down();
  }
}

if ($arg1 === '--create') {
  if (isset($arg2)) {
    $migrationName = $arg2;
    $filePath = FileManager::createMigrationFile($migrationName);

    if ($filePath !== false) {
      echo "Migration file created successfully at: $filePath\n";
    } else {
      echo "Error creating migration file.\n";
    }
  } else {
    echo "Please provide a name for the migration.\n";
  }
}

