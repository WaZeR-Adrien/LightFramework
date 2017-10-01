<?php
namespace Project\Controllers\Tools;

class Reg
{
    /**
     * Search if entry has allow, else edit with ''
     * @param $string
     * @param $type
     * @return string
     */
    public static function replace($string,$type)
    {
        switch ($type)
        {
            case 'main':
                $string = preg_replace('/^[a-zA-Zéèàê0-9@#_\. ]+/i', '', $string);
                break;

            case 'email':
                $string = preg_replace('/^[a-zA-Zéèàê0-9@\-\.]+/i', '', $string);
                break;

            case 'pw':
                $string = preg_replace('/^[a-zA-Z0-9@&_\-\.]+/i', '', $string);
                break;

            case 'url':
                $string = preg_replace('/^[a-zA-Z0-9#&_\-\.\/:]+/i','',$string);
                break;

            case 'html':
                $string = preg_replace('/^[a-zA-Zéèàê0-9@<="\/>_\. ]+/i', '', $string);
                break;
        }

        return $string;
    }

    /**
     * Search if entry has allow
     * @param $string
     * @param $type
     * @return int|mixed
     */
    public static function match($string,$type)
    {
        switch ($type)
        {
            case 'main':
                $string = preg_match('/^[a-zA-Zéèàê0-9@#_\. ]+/i',$string);
                break;

            case 'email':
                $string = preg_match('/^[a-zA-Zéèàê0-9@\-\.]+/i', $string);
                break;

            case 'pw':
                $string = preg_match('/^[a-zA-Z0-9@&_\-\.]+/i',$string);
                break;

            case 'url':
                $string = preg_match('/^[a-zA-Z0-9#&_\-\.\/:]+/i',$string);
                break;

            case 'html':
                $string = preg_match('/^[a-zA-Zéèàê0-9@<="\/>_\. ]+/i',$string);
                break;
        }

        return $string;
    }
}