<?php

namespace Http\Forms;

interface FormInterface
{
  public static function validate($attributes): self;

  public function failed(): bool;

  public function throw (): void;
}