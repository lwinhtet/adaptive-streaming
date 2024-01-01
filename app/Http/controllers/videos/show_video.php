<?php

use Core\App;

$db = App::resolve('mysql');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['id']) {
  $video = $db->query('SELECT * FROM videos WHERE id = :id', [
    'id' => $_GET['id']
  ])->findOrFail();

  view("videos/show.view.php", [
    "heading" => "Show Page",
    "video" => $video
    // "errors" => Core\Session::get('errors')
  ]);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['id']) {

  $video = $db->query('SELECT * FROM videos WHERE id = :id', [
    'id' => $_GET['id']
  ])->findOrFail();

  $db->query('DELETE FROM videos WHERE id = :id', [
    'id' => $_GET['id']
  ]);

  removeDirectory('adaptive_streaming/' . $video['folder_name']);

  redirect('videos');
} else {
  echo 'Invalid Request!';
}