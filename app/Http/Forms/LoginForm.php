<?php

namespace Http\Forms;

use Core\Validator;
use Core\Errors\ValidationException;
use Http\Forms\FormInterface;
use Http\Traits\FormErrorTrait;

class LoginForm implements FormInterface
{
  use FormErrorTrait;
  /* "public array $attributes" utilizes a feature known as constructor property promotion 
  Here, public array $attributes is a shorthand way of declaring a public class property named 
  $attributes and initializing it with the value passed to the constructor. */
  public function __construct(public array $attributes)
  {
    if (!Validator::email($attributes['email'])) {
      $this->errors['email'] = 'Please provide a valid email address';
    }

    if (!Validator::string($attributes['password'], 8, 255)) {
      $this->errors['password'] = 'Please provide a password of at least seven characters.';
    }
  }

  public static function validate($attributes): LoginForm
  {
    $instance = new static($attributes);
    return $instance->failed() ? $instance->throw() : $instance;
  }

  public function failed(): bool
  {
    return count($this->errors) > 0;
  }

  public function throw (): void
  {
    // passing $this->errors(), $this->attributes to create a new instance of ValidationException with those data
    ValidationException::throw($this->errors(), $this->attributes);
  }
}