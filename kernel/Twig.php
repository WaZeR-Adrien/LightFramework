<?php
namespace Kernel;

class Twig
{
    public static function init()
    {
        $loader = new \Twig_Loader_Filesystem('../app/views/');
        $twig = new \Twig_Environment($loader, [
            'debug' => true
        ]);

        self::addExtension($twig);
        self::addGlobal($twig);

        return $twig;
    }

    public static function addExtension($twig)
    {
        $twig->addExtension(new \Twig_Extension_Debug());
    }

    public static function addGlobal($twig)
    {
        $twig->addGlobal('g_site_name', 'Heroes Team');
        $twig->addGlobal('g_url', 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $twig->addGlobal('g_css', '/css/');
        $twig->addGlobal('g_js', '/js/');
        $twig->addGlobal('g_img', '/img/');
        $twig->addGlobal('g_session', $_SESSION);
        $twig->addGlobal('g_alerts', !empty($_SESSION['alerts']) ? $_SESSION['alerts'] : null);
    }
}