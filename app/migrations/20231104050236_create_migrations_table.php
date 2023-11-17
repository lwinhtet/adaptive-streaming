<?php

use Core\Schema;

class CreateMigrationsTable
{
  public function up()
  {
    return Schema::createTable('migrations', [
      'id INT AUTO_INCREMENT PRIMARY KEY',
      'migration_name  VARCHAR(60) NOT NULL',
      'migration_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ]);
  }

  public function down()
  {
    Schema::dropTable('migrations');
  }
}