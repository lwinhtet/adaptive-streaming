<?php
/*
1. An abstract class serves as a blueprint or template for other classes. It defines a set of common methods 
and properties that its subclasses are expected to implement. By declaring certain methods as abstract, 
you're essentially saying to any class that extends the abstract class: "You must provide an implementation 
for these methods." This enforces a contract, ensuring that specific behavior is implemented. 
2. The Database class contains methods (get, find, findOrFail) that are likely to be used for both MySQL 
and Postgres connections. By placing these common methods in the abstract class, you avoid duplication of code.
3. Subclasses of an abstract class can be used interchangeably with objects of the abstract class. This allows 
you to write code that operates on the abstract class, but at runtime, it can work with instances of the concrete 
subclasses. For example, you can have an array of Database objects and call common methods on them, even 
though they might be instances of different concrete classes.
4. Abstract methods are declared without an implementation. Any class that extends an abstract class with abstract 
methods must provide concrete implementations for those methods. This ensures that each subclass adheres to a certain 
interface.
*/

namespace Core\Database;

use PDO;
use InvalidArgumentException;

abstract class Database
{
  protected $connection;
  protected $statement;
  protected $options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ];

  const DbsMap = [
    "mysql" => MysqlConnection::class,
    "postgres" => PostgresConnection::class
  ];

  abstract public function __construct(array $config);

  abstract public function buildDSN($config): string;

  public static function createConnection($config)
  {
    if (!isset($config['driver'])) {
      throw new InvalidArgumentException('A driver must be specified.');
    }

    $key = $config['driver'];
    $con = static::DbsMap[$key] ?? false;

    if (!$con) {
      throw new InvalidArgumentException("Unsupported driver [{$key}].");
    }

    return new $con($config);
  }

  public static function getConfig()
  {
    return require base_path('configs/databases.php');
  }

  public function query($query, $params = [])
  {
    try {
      $this->statement = $this->connection->prepare($query);
      $this->statement->execute($params);
      return $this;
    } catch (\PDOException $e) {
      handleDatabaseError($e);
      return false;
    }
  }

  public function exec($query)
  {
    return $this->connection->exec($query);

  }

  public function get()
  {
    // will return assoc array with multiple record, will return empty arr if no records or no match or no table
    return $this->statement->fetchAll();
  }

  public function find()
  {
    // will return assoc array with single record, will return false if no records or no match or no table
    return $this->statement->fetch();
  }

  public function findOrFail()
  {
    $result = $this->find();

    if (!$result) {
      abort();
    }

    return $result;
  }

  protected function createPdoConnection($dsn, $username, $password, $options = null)
  {
    $opt = $options ?? $this->options;
    return new PDO($dsn, $username, $password, $opt);
  }

  public static function getCurrentActiveDatabaseConfig(string|null $connection = null)
  {
    $config = Database::getConfig();
    $default = env('DB_CONNECTION', 'mysql');
    $current = $connection ? $config['connections'][$connection] : $config['connections'][$default];

    if (!$current) {
      throw new InvalidArgumentException('default db configuration is not provided 💥 !!!');
    }

    return $current;
  }

  public static function createAndGetConnection(string|null $connection = null)
  {
    $config = self::getCurrentActiveDatabaseConfig($connection);
    return self::createConnection($config);
  }
}

