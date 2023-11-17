<?php

namespace Core;

use Core\Database\Database;

use Core\Database\MysqlConnection;
use Core\Database\PostgresConnection;

/* When you use a Laravel Facade like DB, it is typically a static proxy to an instance of the 
Illuminate\Database\Connection class. Laravel's service container manages the actual instances and 
allows you to switch database connections or mock them for testing. */
class Schema
{
  protected static $connection;

  public static function setDefaultConnection(Database $connection)
  {
    self::$connection = $connection;
  }
  public static function db($connection = null): MysqlConnection|PostgresConnection
  {
    return self::$connection;
  }

  public static function createTable(string $tableName, array $columns): bool
  {
    self::ensureConnection();

    $columnsString = implode(", ", $columns);
    $query = "CREATE TABLE IF NOT EXISTS $tableName ($columnsString)";
    self::db()->exec($query);

    my_echo("{$tableName} table created 🟢\n");

    return true;
  }

  public static function dropTable(string $tableName): void
  {
    self::ensureConnection();
    $query = "DROP TABLE IF EXISTS $tableName;";
    self::db()->exec($query);
    my_echo("{$tableName} table deleted 🟢\n");
  }

  private static function addColumn(string $tableName, string $columnName, string $columnDefinition)
  {
    self::ensureConnection();
    $query = "ALTER TABLE $tableName ADD COLUMN $columnName $columnDefinition";
    self::executeQuery($query);
  }

  private static function executeQuery($query, $params = [])
  {
    return self::$connection->query($query, $params);
  }

  public static function isTableExist(string $tableName)
  {
    $tableSchema = env('DB_DATABASE', null);
    // if table does not exist, it will return bool 'false', if exist return asso array
    $result = self::db()->query("SELECT 1 FROM information_schema.tables 
      WHERE table_schema = :tableSchema 
      AND table_name = :tableName", [
      ":tableSchema" => $tableSchema,
      ":tableName" => $tableName
    ])->find();

    return $result ? true : false;
  }

  private static function ensureConnection()
  {
    if (!self::$connection) {
      throw new \InvalidArgumentException("Default database connection not set.");
    }
  }

}