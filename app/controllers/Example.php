<?php
namespace Controllers;
use Kernel\Twig;
use Models\Testt;

class Example
{
    public static function show($slug, $id)
    {
        $twig = Twig::init();

        echo $twig->render('example.html.twig', ['slug' => $slug, 'id' => $id]);
    }

    public static function update()
    {
        $example = new \Models\Example($_POST['id']);
        $example->field1 = 'lorem ipsum';
        $example->field2 = 'lorem ipsum';
        $example->field3 = 'lorem ipsum';
        $example->update();
    }

    public static function add()
    {
        $example = new \Models\Example();
        $example->field1 = $_POST['key'];
        $example->field2 = $_POST['key'];
        $example->field3 = $_POST['key'];
        $example->insert();
    }
}