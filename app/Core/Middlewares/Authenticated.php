<?php
namespace Core\Middlewares;

class Authenticated
{

  public function handle()
  {
    if (!$_SESSION['user'] ?? false) {
      header('location: /login');
      exit();
    }
  }
}