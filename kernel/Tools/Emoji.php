<?php
namespace Kernel\Tools;

class Emoji
{
    private static $_emojis = [':)', ':(', '>:(', ':P', ':p', ':D', ';)', '^^', '<3', ':poop:'];
    private static $_img = ['smile', 'sad', 'angry', 'tongue_out', 'tongue_out', 'smile_with_open_eye', 'wink', 'smile_face_and_eye', 'heart', 'poop'];

    public static function changeToEmoji($string)
    {
        $tab = explode(' ', $string);

        foreach ($tab as $k => $v) {
            $value = htmlspecialchars_decode($v);

            if (in_array($value, self::$_emojis)) {
                $keyEmoji = array_search($value, self::$_emojis);
                $tab[$k] = str_replace( $value, '<img class="emoji" src="../img/emojis/'. self::$_img[$keyEmoji] .'.png" alt="'. $value .'">', $value );
            }

        }

        $string = implode(' ', $tab);

        return $string;
    }
}
