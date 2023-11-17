<?php

namespace Core;

use Core\Schema;

class Migration
{

  protected static $migrationsPath = "/../migrations/*.php";

  public static function getPath()
  {
    return self::$migrationsPath;
  }

  public static function getClassName(string $fileName): string
  {
    $exploded = explode('_', $fileName);
    array_shift($exploded);
    return implode('', array_map('ucfirst', $exploded));
  }

  public static function insertNew($fileName)
  {
    Schema::db()->query("INSERT INTO migrations (migration_name) VALUES (:name)", ['name' => $fileName]);
  }

  // Add appropriate error handling
  public static function getExecutedMigrations(): array
  {
    if (!Schema::isTableExist('migrations')) {
      return [];
    }
    return self::getMigrationNames();
  }

  public static function getMigrationNames()
  {
    $names = Schema::db()->query("SELECT migration_name FROM migrations")->get();
    // Extract the "migration_name" values into a new array
    return array_column($names, 'migration_name');
  }

  public static function getFiles(bool $isReverse = false)
  {
    /* __DIR__ is a predefined constant that represents the directory of the current PHP file.
    'migrations/*.php' is a file pattern. It specifies that the function should look for files 
    with the .php extension in the migrations subdirectory. */
    if ($isReverse) {
      return array_reverse(glob(__DIR__ . self::getPath()));
    }
    return glob(__DIR__ . self::getPath());
  }
}