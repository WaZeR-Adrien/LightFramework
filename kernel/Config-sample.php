<?php
namespace Kernel;

class Config
{
    private static $_config = [
        'database' => [
            'host' => 'YOUR_HOST',
            'db'   => 'YOUR_DB',
            'user' => 'YOUR_USER',
            'pw'   => 'YOUR_PW'
        ],
        'path' => []
    ];

    public static function getDatabase()
    {
        return self::$_config['database'];
    }

    public static function getPath()
    {
        return self::$_config['path'];
    }
}