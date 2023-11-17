<?php

namespace Core\Database;

class MysqlConnection extends Database
{

  protected $driver = 'mysql';
  public function __construct(array $config)
  {
    $dsn = $this->buildDSN($config);
    $this->connection = $this->createPdoConnection($dsn, $config['username'], $config['password']);
  }

  public function buildDSN($config): string
  {
    return sprintf(
      '%s:host=%s;port=%s;dbname=%s;charset=%s',
      $this->driver,
      $config['host'],
      $config['port'],
      $config['dbname'],
      $config['charset']
    );
  }
}
