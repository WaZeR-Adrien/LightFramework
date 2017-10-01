<?php
require_once(APP_PATH.'twig/lib/Twig/Autoloader.php');
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(VIEW_PATH);
$twig = new Twig_Environment($loader, array(
    "cache" => CACHE,
    'debug' => DEBUG,
));
$twig->addGlobal('siteName',SITE_NAME);
$twig->addGlobal('css',CSS_PATH);
$twig->addGlobal('js',JS_PATH);
$twig->addGlobal('uploads',UPLOADS_PATH);
$twig->addGlobal('session',$_SESSION);
$twig->addExtension(new Twig_Extension_Debug());