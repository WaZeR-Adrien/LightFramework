<?php
namespace Controllers;
use Kernel\Config;
use Kernel\Tools\Alert;
use Kernel\Twig;

class Controller
{
    protected static $_datas = [];
    protected static $_alert = [];
    protected static $_days = [
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
    ];

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

    /**
     * Remove attributes on array
     * @param array $array
     * @param array $attrs
     * @param string $type
     * @return array
     */
    protected static function _removeAttrs($array = [], $attrs = [], $type = 'obj')
    {
        foreach ($array as $k => $v) {
            switch ($type) {
                case 'obj':
                    foreach ($attrs as $attr) {
                        unset($v->$attr);
                    }
                    break;

                case 'array':
                    foreach ($attrs as $attr) {
                        unset($v[$attr]);
                    }
                    break;
            }
        }
        return $array;
    }

    /**
     * Redirect towards url
     * @param $url
     */
    protected static function _redirect($url)
    {
        header('location: '. $url);
        exit();
    }

    /**
     * Create random token
     * @return bool|string
     */
    protected static function _createToken()
    {
        $token = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($token, 50)), 0, 50);
    }

    /**
     * @param $var
     */
    protected static function _toJson($var)
    {
        header('Content-Type:application/json');
        echo json_encode($var);
        exit();
    }

    /**
     * Check if needle is between min and max values
     * @param int $needle
     * @param int $min
     * @param int $max
     * @return bool
     */
    protected static function _between($needle, $min = null, $max = null)
    {
        switch (true) {
            // If null == $min && null == $max
            case null == $min && null == $max:
                return true;

            // If needle == null && (null != $min || null != $max)
            case null == $needle:
                return false;

            // If needle != null && (null != $min && null == $max)
            case null != $min && null == $max:
                return ($needle >= $min) ? true : false;

            // If needle != null && (null == $min && null != $max)
            case null == $min && null != $max:
                return ($needle <= $max) ? true : false;

            // If all var != null
            default:
                return ($needle >= $min && $needle <= $max) ? true : false;
        }
    }

    /**
     * Transform date Fr to Us
     * @param $date
     * @return string
     */
    protected static function _dateUs($date)
    {
        $tabDate = explode('/', $date);
        return $tabDate[0] . '-' . $tabDate[1] . '-' . $tabDate[2];
    }

    /**
     * Get the timestamp of the date
     * @param $date
     * @return int
     */
    protected static function _toTimestamp($date)
    {
        $newDate = new \DateTime($date);
        return $newDate->getTimestamp();
    }

    /**
     * @param $pattern
     * @param $subject
     * @return int
     */
    protected static function _match($pattern, $subject)
    {
        return preg_match(Config::getReg()[$pattern], $subject);
    }
}
