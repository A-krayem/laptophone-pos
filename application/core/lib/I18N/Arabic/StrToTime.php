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
class I18N_Arabic_StrToTime
{
    private static $_hj = array();
    private static $_strtotimeSearch = array();
    private static $_strtotimeReplace = array();
    public function __construct()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . "/data/ArStrToTime.xml");
        foreach ($xml->xpath("//str_replace[@function='strtotime']/pair") as $pair) {
            array_push(self::$_strtotimeSearch, (string) $pair->search);
            array_push(self::$_strtotimeReplace, (string) $pair->replace);
        }
        foreach ($xml->hj_month->month as $month) {
            array_push(self::$_hj, (string) $month);
        }
    }
    public static function strtotime($text, $now)
    {
        $int = 0;
        for ($i = 0; $i < 12; $i++) {
            if (0 < strpos($text, self::$_hj[$i])) {
                preg_match("/.*(\\d{1,2}).*(\\d{4}).*/", $text, $matches);
                include dirname(__FILE__) . "/Mktime.php";
                $temp = new I18N_Arabic_Mktime();
                $fix = $temp->mktimeCorrection($i + 1, $matches[2]);
                $int = $temp->mktime(0, 0, 0, $i + 1, $matches[1], $matches[2], $fix);
                $temp = NULL;
                break;
            }
        }
        if ($int == 0) {
            $patterns = array();
            $replacements = array();
            array_push($patterns, "/َ|ً|ُ|ٌ|ِ|ٍ|ْ|ّ/");
            array_push($replacements, "");
            array_push($patterns, "/\\s*ال(\\S{3,})\\s+ال(\\S{3,})/");
            array_push($replacements, " \\2 \\1");
            array_push($patterns, "/\\s*ال(\\S{3,})/");
            array_push($replacements, " \\1");
            $text = preg_replace($patterns, $replacements, $text);
            $text = str_replace(self::$_strtotimeSearch, self::$_strtotimeReplace, $text);
            $pattern = "[ابتثجحخدذرزسشصضطظعغفقكلمنهوي]";
            $text = preg_replace("/" . $pattern . "/", "", $text);
            $int = strtotime($text, $now);
        }
        return $int;
    }
}

?>