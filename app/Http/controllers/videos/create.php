<?php

view("videos/create.view.php", [
  "heading" => "Upload Page",
  "errors" => Core\Session::get('errors')
]);