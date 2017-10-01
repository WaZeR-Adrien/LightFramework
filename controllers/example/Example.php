<?php
namespace Project\Controllers\Example;

use Project\Controllers\Tools\Reg;

class Example extends Controller
{
    private static $_error;

    /**
     * @param $twig object
     * @return twig template
     */
    public static function index($twig)
    {
        $array = [
            'example' => \Project\Models\Example::get()
        ];

        if (!empty($_POST)) {
            // Hydrate
            // Object of datas posted
            $datas = new \StdClass();
            $datas->id   = $_POST['id'];
            $datas->name = $_POST['name'];
            $datas->pw   = $_POST['pw'];

            $datas = self::hydrate($datas);

            // Search if has error
            foreach ($datas as $k => $v)
            {
                if (!empty($v) && !$v) self::$_error = true;
            }

            // Send datas to DB
            if (!self::$_error) {
                self::send($datas);
            }
        }

        return $twig->render('example.html.twig', $array);
    }

    /**
     * Hydrate all datas posted
     * @param $datas object
     * @return $datas object
     */
    public static function hydrate($datas)
    {
        foreach ($datas as $k => $v)
        {
            switch ($k)
            {
                case 'pw':
                    if (Reg::match($v, 'pw')) {
                        $datas->$k = htmlspecialchars($v);
                    } else {
                        $datas->$k = false;
                    }
                    break;

                default:
                    if (Reg::match($v, 'main')) {
                        $datas->$k = htmlspecialchars($v);
                    } else {
                        $datas->$k = false;
                    }
                    break;
            }
        }

        return $datas;
    }

    /**
     * Send datas to DB
     * @param $datas object
     */
    public static function send($datas)
    {
        if (!empty($datas))
        {
            $example = new \Project\Models\Example($datas);
            $example->insert();
        }
    }
}