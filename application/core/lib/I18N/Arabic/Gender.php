<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 5.6
 * @ Decoder version: 1.0.4
 * @ Release: 02/06/2020
 *
 * @ ZendGuard Decoder PHP 5.6
 */

// Decoded file for php version 53.
class I18N_Arabic_Gender
{
    public function __construct()
    {
    }
    public static function isFemale($str)
    {
        $female = false;
        $words = explode(" ", $str);
        $str = $words[0];
        $str = str_replace(array("أ", "إ", "آ"), "ا", $str);
        $last = mb_substr($str, -1, 1, "UTF-8");
        $beforeLast = mb_substr($str, -2, 1, "UTF-8");
        if ($last == "ة" || $last == "ه" || $last == "ى" || $last == "ا" || $last == "ء" && $beforeLast == "ا") {
            $female = true;
        } else {
            if (preg_match("/^[اإ].{2}ا.\$/u", $str) || preg_match("/^[إا].ت.ا.+\$/u", $str)) {
                $female = true;
            } else {
                $names = file(dirname(__FILE__) . "/data/female.txt");
                $names = array_map("trim", $names);
                if (0 < array_search($str, $names)) {
                    $female = true;
                }
            }
        }
        return $female;
    }
}

?>