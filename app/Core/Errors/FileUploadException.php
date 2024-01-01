<?php

namespace Core\Errors;

use Exception;

class FileUploadException extends Exception
{
  public readonly array $errors;
  public static function throw ($errors)
  {
    $instance = new static('File Upload Failed!');
    $instance->errors = $errors;

    throw $instance;
  }
}