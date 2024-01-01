<?php

// Home, About, Contact
$router->get("/", "index.php");
$router->get('/about', 'about.php');
$router->get('/contact', 'contact.php');

// Session Login Logout
$router->get("/login", "session/create.php")->only('guest');
$router->post("/session", "session/store.php")->only('guest');
$router->delete("/session", "session/destroy.php")->only('auth');

// Session Register
$router->get('/register', 'registration/create.php')->only('guest');
$router->post('/register', 'registration/store.php')->only('guest');

$router->get('/videos', 'videos/index.php');
$router->get('/video', 'videos/show_video.php');
$router->post('/video', 'videos/show_video.php');

$router->get('/upload-video', 'videos/create.php');
$router->post('/upload-video', 'videos/upload.php');


