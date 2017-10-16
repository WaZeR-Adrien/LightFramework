<?php
namespace Controllers;
use Kernel\Tools\Alert;
use Kernel\Twig;

class Controller
{
    protected static $_datas = []; // Datas sended to Twig
    protected static $_alert = [];

    /**
     * Generate view
     * @param $view
     */
    protected static function _view($view)
    {
        $twig = Twig::init();

        self::_checkAlerts();

        echo $twig->render($view, self::$_datas);
    }

    /**
     * Hydrate and/or edit datas POST
     */
    protected static function _hydrate()
    {
        foreach ($_POST as $k => $v) {
            switch ($k)
            {
                case 'email':
                    if (!strpos($v, '@')) {
                        self::$_alert['email'] = new Alert('email', 'err');
                    }
                    break;
            }
        }
        self::_setAlerts();
    }

    /**
     * Set alerts for all errors or success
     */
    protected static function _setAlerts()
    {
        foreach (self::$_alert as $k => $v) {
            self::$_alert[$k]->setAlert();
        }
    }

    /**
     * Check if there are alert in SESSION
     * Send alert to datas for twig
     * And after, remove this alert in SESSION
     */
    protected static function _checkAlerts()
    {
        if (!empty($_SESSION['alerts'])) {
            foreach ($_SESSION['alerts'] as $k => $v) {
                self::$_datas['alerts'][$k] = $v;
            }
            unset($_SESSION['alerts']);
        }
    }

    protected static function _redirect($url)
    {
        header('location: '. $url);
    }
}