<?php
namespace Kernel;

class Twig
{
    public static function init()
    {
        $loader = new \Twig_Loader_Filesystem('../app/views/');
        $twig = new \Twig_Environment($loader, []);

        self::addGlobal($twig);

        return $twig;
    }

    public static function addGlobal($twig)
    {
        $twig->addGlobal('g_site_name', 'YOUR_SITE_NAME');
        $twig->addGlobal('g_url', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $twig->addGlobal('g_css', '/css/');
        $twig->addGlobal('g_js', '/js/');
        $twig->addGlobal('g_img', '/img/');
    }
}