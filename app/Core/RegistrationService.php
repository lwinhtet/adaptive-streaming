<?php

namespace Core;

class RegistrationService
{
  protected $db;
  public function __construct($dbInstance)
  {
    $this->db = $dbInstance;
  }
  public function register(array $attributes)
  {
    $this->db->query('INSERT INTO users (email, password) VALUES (:email, :password)', [
      'email' => $attributes['email'],
      'password' => password_hash($attributes['password'], PASSWORD_BCRYPT)
    ])->find();
  }

  public function isEmailExist(string $email): bool
  {
    $user = $this->db->query('SELECT email FROM users WHERE email = :email', [
      ':email' => $email
    ])->find();
    if (!$user) {
      return false;
    }
    return true;
  }

}