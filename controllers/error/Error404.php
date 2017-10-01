<?php
namespace Project\Controllers\Error;

class Error404
{
    public static function index($twig)
    {
        $array = [];

        return $twig->render('404.html.twig', $array);
    }
}
