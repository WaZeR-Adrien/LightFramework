<?php
setlocale (LC_TIME, 'fr_FR.utf8','fra');
session_start();

require '../vendor/autoload.php';

$router = new \Kernel\Router\Router($_GET['url']);

$router->get('/', function () {
    echo 'Home';
}, 'home');

$router->get('/example/:slug-:id', "Example#show", "example.show")
    ->with('id', '[0-9]+')
    ->with('slug', '[a-z\-0-9]+');

$router->post('/example/update', "Example#update", "example.update");
$router->post('/example/add', "Example#add", "example.add");

$router->run();