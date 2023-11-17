<?php

namespace Http\Traits;

/* you don't need to include protected $errors = []; in LoginForm and SignUpForm. The $errors property 
is defined in the trait, and when you use the trait in the classes (LoginForm and SignUpForm), the property 
becomes part of those classes. */
trait FormErrorTrait
{
  protected $errors = [];

  public function errors()
  {
    return $this->errors;
  }

  public function addError($field, $message)
  {
    $this->errors[$field] = $message;
    return $this;
  }
}
