<?php

view("registration/create.view.php", [
  "heading" => "Registration",
  'errors' => Core\Session::get('errors')
]);