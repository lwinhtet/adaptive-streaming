<?php

namespace Core\Utilities;

class FileManager
{
  public static function createMigrationFile(string $name): bool|string
  {
    $timestamp = date('YmdHis');
    $fileName = "{$timestamp}_{$name}.php";
    $filePath = 'migrations/' . $fileName;

    $exploded = explode('_', $name);
    $capitalized = array_map('ucfirst', $exploded);
    $imploded = implode('', $capitalized);

    $migrationContent = "<?php\n\n";
    $migrationContent .= "class {$imploded} {\n}";

    return file_put_contents($filePath, $migrationContent) !== false ? $filePath : false;
  }
}