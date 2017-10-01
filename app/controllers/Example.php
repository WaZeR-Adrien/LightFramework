<?php
namespace Controllers;
use Kernel\Twig;

class Example
{
    public static function show($slug, $id)
    {
        $twig = Twig::init();

        echo $twig->render('example.html.twig', ['slug' => $slug, 'id' => $id]);
    }
}