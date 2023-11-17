<?php

namespace Core;

use Core\Session;

class Authenticator
{
  public function attempt($email, $password)
  {
    $user = (App::resolve('mysql'))->query('SELECT * FROM users WHERE email = :email', [
      'email' => $email
    ])->find();

    if ($user && password_verify($password, $user['password'])) {
      $this->login([
        'email' => $email
      ]);

      return true;
    }

    return false;
  }

  public function login($user)
  {
    $_SESSION['user'] = [
      'email' => $user['email']
    ];
    // as a good practise, we should re-generate sessionId(session data file stored in the server)
    // this will update the cookie and update the file stored in the server
    // just in case that malicious user has that key and do some thing with it
    session_regenerate_id(true); // boolean value is to indicate to clear out the old session file or not
  }
  public function logout()
  {
    Session::destory();
  }
}