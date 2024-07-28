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
class I18N_Arabic_Transliteration
{
    private static $_arFinePatterns = array("/'+/u", "/([\\- ])'/u", "/(.)#/u");
    private static $_arFineReplacements = array("'", "\\1", "\\1'\\1");
    private static $_en2arPregSearch = array();
    private static $_en2arPregReplace = array();
    private static $_en2arStrSearch = array();
    private static $_en2arStrReplace = array();
    private static $_ar2enPregSearch = array();
    private static $_ar2enPregReplace = array();
    private static $_ar2enStrSearch = array();
    private static $_ar2enStrReplace = array();
    private static $_diariticalSearch = array();
    private static $_diariticalReplace = array();
    private static $_iso233Search = array();
    private static $_iso233Replace = array();
    private static $_rjgcSearch = array();
    private static $_rjgcReplace = array();
    private static $_sesSearch = array();
    private static $_sesReplace = array();
    public function __construct()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . "/data/Transliteration.xml");
        foreach ($xml->xpath("//preg_replace[@function='ar2en']/pair") as $pair) {
            array_push(self::$_ar2enPregSearch, (string) $pair->search);
            array_push(self::$_ar2enPregReplace, (string) $pair->replace);
        }
        foreach ($xml->xpath("//str_replace[@function='diaritical']/pair") as $pair) {
            array_push(self::$_diariticalSearch, (string) $pair->search);
            array_push(self::$_diariticalReplace, (string) $pair->replace);
        }
        foreach ($xml->xpath("//str_replace[@function='ISO233']/pair") as $pair) {
            array_push(self::$_iso233Search, (string) $pair->search);
            array_push(self::$_iso233Replace, (string) $pair->replace);
        }
        foreach ($xml->xpath("//str_replace[@function='RJGC']/pair") as $pair) {
            array_push(self::$_rjgcSearch, (string) $pair->search);
            array_push(self::$_rjgcReplace, (string) $pair->replace);
        }
        foreach ($xml->xpath("//str_replace[@function='SES']/pair") as $pair) {
            array_push(self::$_sesSearch, (string) $pair->search);
            array_push(self::$_sesReplace, (string) $pair->replace);
        }
        foreach ($xml->xpath("//str_replace[@function='ar2en']/pair") as $pair) {
            array_push(self::$_ar2enStrSearch, (string) $pair->search);
            array_push(self::$_ar2enStrReplace, (string) $pair->replace);
        }
        foreach ($xml->xpath("//preg_replace[@function='en2ar']/pair") as $pair) {
            array_push(self::$_en2arPregSearch, (string) $pair->search);
            array_push(self::$_en2arPregReplace, (string) $pair->replace);
        }
        foreach ($xml->xpath("//str_replace[@function='en2ar']/pair") as $pair) {
            array_push(self::$_en2arStrSearch, (string) $pair->search);
            array_push(self::$_en2arStrReplace, (string) $pair->replace);
        }
    }
    public static function en2ar($string)
    {
        $string = strtolower($string);
        $words = explode(" ", $string);
        $string = "";
        foreach ($words as $word) {
            $word = preg_replace(self::$_en2arPregSearch, self::$_en2arPregReplace, $word);
            $word = str_replace(self::$_en2arStrSearch, self::$_en2arStrReplace, $word);
            $string .= " " . $word;
        }
        return $string;
    }
    public static function ar2en($string, $standard = "UNGEGN")
    {
        $words = explode(" ", $string);
        $string = "";
        for ($i = 0; $i < count($words) - 1; $i++) {
            $words[$i] = str_replace("ة", "ت", $words[$i]);
        }
        foreach ($words as $word) {
            $temp = $word;
            if ($standard == "UNGEGN+") {
                $temp = str_replace(self::$_diariticalSearch, self::$_diariticalReplace, $temp);
            } else {
                if ($standard == "RJGC") {
                    $temp = str_replace(self::$_diariticalSearch, self::$_diariticalReplace, $temp);
                    $temp = str_replace(self::$_rjgcSearch, self::$_rjgcReplace, $temp);
                } else {
                    if ($standard == "SES") {
                        $temp = str_replace(self::$_diariticalSearch, self::$_diariticalReplace, $temp);
                        $temp = str_replace(self::$_sesSearch, self::$_sesReplace, $temp);
                    } else {
                        if ($standard == "ISO233") {
                            $temp = str_replace(self::$_iso233Search, self::$_iso233Replace, $temp);
                        }
                    }
                }
            }
            $temp = preg_replace(self::$_ar2enPregSearch, self::$_ar2enPregReplace, $temp);
            $temp = str_replace(self::$_ar2enStrSearch, self::$_ar2enStrReplace, $temp);
            $temp = preg_replace(self::$_arFinePatterns, self::$_arFineReplacements, $temp);
            if (preg_match("/[a-z]/", mb_substr($temp, 0, 1))) {
                $temp = ucwords($temp);
            }
            $pos = strpos($temp, "-");
            if (0 < $pos) {
                if (preg_match("/[a-z]/", mb_substr($temp, $pos + 1, 1))) {
                    $temp2 = substr($temp, 0, $pos);
                    $temp2 .= "-" . strtoupper($temp[$pos + 1]);
                    $temp2 .= substr($temp, $pos + 2);
                } else {
                    $temp2 = $temp;
                }
            } else {
                $temp2 = $temp;
            }
            $string .= " " . $temp2;
        }
        return $string;
    }
    public static function enNum($string)
    {
        $html = "";
        $digits = str_split((string) $string);
        foreach ($digits as $digit) {
            $html .= preg_match("/\\d/", $digit) ? "&#x3" . $digit . ";" : $digit;
        }
        return $html;
    }
    public static function arNum($string)
    {
        $html = "";
        $digits = str_split((string) $string);
        foreach ($digits as $digit) {
            $html .= preg_match("/\\d/", $digit) ? "&#x066" . $digit . ";" : $digit;
        }
        return $html;
    }
}

?>