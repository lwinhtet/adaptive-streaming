<?php

// Home 
$router->get("/", "index.php");

// Session Login Logout
$router->get("/login", "session/create.php")->only('guest');
$router->post("/session", "session/store.php")->only('guest');
$router->delete("/session", "session/destroy.php")->only('auth');

// Session Register
$router->get('/register', 'registration/create.php')->only('guest');
$router->post('/register', 'registration/store.php')->only('guest');
