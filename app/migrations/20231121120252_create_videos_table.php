<?php

use Core\Schema;

class CreateVideosTable
{
  public function up()
  {
    Schema::createTable("videos", [
      'id INT AUTO_INCREMENT PRIMARY KEY',
      'title VARCHAR(60) NOT NULL',
      'descripition TEXT',
      'original_file VARCHAR(265)',
      'master_file VARCHAR(265)',
      'folder_name VARCHAR(265)',
      'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
      'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ]);
  }

  public function down()
  {
    Schema::dropTable("videos");
  }
}