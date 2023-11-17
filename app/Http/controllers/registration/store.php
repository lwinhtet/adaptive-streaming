<?php

use Core\App;
use Core\Authenticator;
use Core\RegistrationService;
use Http\Forms\SignUpForm;

$db = App::resolve("mysql");

$email = $_POST['email'];
$password = $_POST['password'];
$attributes = [
  'email' => $email,
  'password' => $password
];

$form = SignUpForm::validate($attributes);
$service = new RegistrationService(App::resolve('mysql'));
$isEmailExist = $service->isEmailExist($email);

if ($isEmailExist) {
  $form->addError('email', 'Email address is already taken!!!')->throw();
} else {
  $service->register($attributes);
  (new Authenticator)->login(['email' => $email]);
  redirect('/');
}



