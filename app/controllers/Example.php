<?php
namespace Controllers;
use Kernel\Twig;
use Models\Testt;

class Example extends Controller
{
    /**
     * Show view with datas sended to twig
     * @param $slug
     * @param $id
     */
    public static function show($slug, $id)
    {
        self::$_datas = ['slug' => $slug, 'id' => $id];

        self::_view('example.html.twig');
    }

    /**
     * Update values to Database
     */
    public static function update()
    {
        $example = new \Models\Example($_POST['id']);
        $example->field1 = 'lorem ipsum';
        $example->field2 = 'lorem ipsum';
        $example->field3 = 'lorem ipsum';
        $example->update();
    }

    /**
     * Add new row to Database
     */
    public static function add()
    {
        $example = new \Models\Example();
        $example->field1 = $_POST['key'];
        $example->field2 = $_POST['key'];
        $example->field3 = $_POST['key'];
        $example->insert();
    }
}