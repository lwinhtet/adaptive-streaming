<?php

view("session/create.view.php", [
  "heading" => "Login",
  "errors" => Core\Session::get('errors')
]);