<?php

use Core\Schema;

class CreateUsersTable
{
  public function up()
  {
    /* 
    Bcrypt: If you're using bcrypt, a common choice for password hashing, you might want to set 
    the password column to VARCHAR(60) to accommodate the hash. 
    Argon2: If you're using Argon2, it has variable output lengths, and you may need more space. 
    A length of 255 or higher should be safe.
    */
    return Schema::createTable('users', [
      'id INT AUTO_INCREMENT PRIMARY KEY ',
      'email  VARCHAR(60) NOT NULL',
      'password VARCHAR(60) NOT NULL',
      'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
      'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
    ]);
  }

  public function down()
  {
    Schema::dropTable('users');
  }
}