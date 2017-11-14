<?php
namespace Controllers;
use Kernel\Config;
use Kernel\Tools\Alert;
use Kernel\Twig;
use Models\Recruitment\Classe;
use Models\Recruitment\Hero;
use Models\Recruitment\Rank;

class Controller
{
    protected static $_datas = [];
    protected static $_alert = [];

    /**
     * Generate view
     * @param $view
     */
    protected static function _view($view)
    {
        $twig = Twig::init();

        self::_removeAlerts();

        echo $twig->render($view, self::$_datas);
        exit();
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
     * Remove alerts SESSION
     */
    protected static function _removeAlerts()
    {
        if (!empty($_SESSION['alerts'])) {
            unset($_SESSION['alerts']);
        }
    }

    protected static function _redirect($url)
    {
        header('location: '. $url);
        exit();
    }

    protected static function _createToken()
    {
        $token = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($token, 50)), 0, 50);
    }

    protected static function _toJson($var)
    {
        header('Content-Type:application/json');
        echo json_encode($var);
        exit();
    }
}
