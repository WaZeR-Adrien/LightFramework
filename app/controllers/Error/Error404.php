<?php
namespace Controllers\Error;
use Controllers\Controller;
use Kernel\Twig;

class Error404 extends Controller
{
    public static function index()
    {
        header('HTTP/1.0 404 Not Found');
        self::_view('404.html.twig');
    }
}
