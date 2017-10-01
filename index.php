<?php
session_start();
use \Project\Config\Autoloader;
use \Project\Config\Router;
require('config/config.php');
require(APP_PATH.'twig/twig-init.php');
require(ROOT_PATH.'config/Autoloader.php');
Autoloader::register();

// Routing
$branch = !empty($_GET['b']) ? $_GET['b'] : 'home';
$page   = !empty($_GET['p']) ? $_GET['p'] : 'home';

$router = new Router($branch, $page, $twig);
$router->run();


/**
 * Example of routing :
 *
 * user call : ?b=account&p=profile
 *
 * The GET b = account
 * The GET p = profile
 *
 * With this, you have the branch and the page.
 * The branch is the directory controllers and the page is the controller :)
 *
 * controllers (directory) :
 *              account (directory) :
 *                          profile (class)
 *
 * Don't forget htaccess for more beauty ;)
 *
 * ENJOY :D
 */