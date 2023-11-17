<?php

namespace Core;

class Validator
{
  public static function string(string $value, int $min = 1, int $max = INF): bool
  {
    $value = trim($value);
    return strlen($value) >= $min || strlen($value) <= $max;
  }

  public static function email($email): bool
  {
    // Filters a variable with a specified filter
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  public static function greateerThan(int $value, int $greaterThan): bool
  {
    return $value > $greaterThan;
  }
}