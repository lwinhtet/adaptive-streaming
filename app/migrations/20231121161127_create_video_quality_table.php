<?php

use Core\Schema;

class CreateVideoQualityTable
{
  public function up()
  {
    Schema::createTable('video_quality', [
      'id INT AUTO_INCREMENT PRIMARY KEY',
      'video_id INT',
      'quality VARCHAR(60) NOT NULL',
      'file_path VARCHAR(60) NOT NULL',
      'duration INT NOT NULL',
      'FOREIGN KEY (video_id) REFERENCES videos(id)'
    ]);
  }

  public function down()
  {
    Schema::dropTable('video_quality');
  }
}