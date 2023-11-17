<?php

namespace Core\Errors;

use Exception;

class ValidationException extends Exception
{
  // The use of read-only properties ensures that once the exception is thrown, its properties 
  // cannot be modified, maintaining the integrity of the exception state.
  public readonly array $errors;

  public readonly array $old;

  public static function throw ($errors, $old)
  {
    $instance = new static('The form failed to validate.');
    $instance->errors = $errors;
    $instance->old = $old;

    throw $instance;
  }
}