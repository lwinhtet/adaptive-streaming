<?php

use Core\App;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $videos = App::resolve('mysql')->query('SELECT * FROM videos')->get();

  view("videos/list.view.php", [
    "heading" => "Videos Page",
    "videos" => $videos ? $videos : []
  ]);

} else {
  echo 'Invalid Request!';
}
