<?php
namespace Project\Config;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(array(__CLASS__,'autoload'));
    }

    public static function autoload($class)
    {
        $path = self::getClassPath($class); // Récupère le chemin de la classe

        require_once  $path . '.php';
    }

    /**
     * @param $class
     * @return string
     */
    public static function getClassPath($class)
    {
        $c = explode('\\',$class);
        $nb = count($c);

        if ($nb == 3) $path = ROOT_PATH . lcfirst($c[1] . '/' . $c[2]);
        elseif ($nb == 4) $path = ROOT_PATH . lcfirst($c[1] . '/' . lcfirst($c[2]) . '/' . $c[3]);
        elseif ($nb == 5) $path = ROOT_PATH . lcfirst($c[1] . '/' . lcfirst($c[2]) . '/' . lcfirst($c[3]) . '/' . $c[4]);


        return $path;
    }
}