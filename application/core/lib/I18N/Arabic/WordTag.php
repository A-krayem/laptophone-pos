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
class I18N_Arabic_WordTag
{
    private static $_particlePreNouns = array("عن", "في", "مذ", "منذ", "من", "الى", "على", "حتى", "الا", "غير", "سوى", "خلا", "عدا", "حاشا", "ليس");
    private static $_normalizeAlef = array("أ", "إ", "آ");
    private static $_normalizeDiacritics = array("َ", "ً", "ُ", "ٌ", "ِ", "ٍ", "ْ", "ّ");
    public function __construct()
    {
    }
    public static function isNoun($word, $word_befor)
    {
        $word = trim($word);
        $word_befor = trim($word_befor);
        $word = str_replace(self::$_normalizeAlef, "ا", $word);
        $word_befor = str_replace(self::$_normalizeAlef, "ا", $word_befor);
        $wordLen = strlen($word);
        if (in_array($word_befor, self::$_particlePreNouns)) {
            return true;
        }
        if (is_numeric($word) || is_numeric($word_befor)) {
            return true;
        }
        if (mb_substr($word, -1, 1) == "ً" || mb_substr($word, -1, 1) == "ٌ" || mb_substr($word, -1, 1) == "ٍ") {
            return true;
        }
        $word = str_replace(self::$_normalizeDiacritics, "", $word);
        $wordLen = mb_strlen($word);
        if (mb_substr($word, 0, 1) == "ا" && mb_substr($word, 1, 1) == "ل" && 5 <= $wordLen) {
            return true;
        }
        if (3 <= mb_substr_count($word, "ا")) {
            return true;
        }
        if ((mb_substr($word, -1, 1) == "ة" || mb_substr($word, -1, 1) == "ء" || mb_substr($word, -1, 1) == "ى") && 4 <= $wordLen) {
            return true;
        }
        if (mb_substr($word, -1, 1) == "ت" && mb_substr($word, -2, 1) == "ا" && 5 <= $wordLen) {
            return true;
        }
        if (mb_substr($word, 0, 1) == "ن" && (mb_substr($word, 1, 1) == "ر" || mb_substr($word, 1, 1) == "ل" || mb_substr($word, 1, 1) == "ن") && 3 < $wordLen) {
            return false;
        }
        if (mb_substr($word, 0, 1) == "ي" && mb_strpos("يذجهخزشصضطظغك", mb_substr($word, 1, 1)) !== false && 3 < $wordLen) {
            return false;
        }
        if ((mb_substr($word, 0, 1) == "ب" || mb_substr($word, 0, 1) == "م") && (mb_substr($word, 1, 1) == "ب" || mb_substr($word, 1, 1) == "ف" || mb_substr($word, 1, 1) == "م") && 3 < $wordLen) {
            return true;
        }
        if (preg_match("/^[^ايتن]\\S{2}[اوي]ن\$/u", $word)) {
            return true;
        }
        if (preg_match("/^م\\S{3}\$/u", $word) || preg_match("/^م\\S{2}ا\\S\$/u", $word) || preg_match("/^م\\S{3}ة\$/u", $word) || preg_match("/^\\S{2}ا\\S\$/u", $word) || preg_match("/^\\Sا\\Sو\\S\$/u", $word) || preg_match("/^\\S{2}و\\S\$/u", $word) || preg_match("/^\\S{2}ي\\S\$/u", $word) || preg_match("/^م\\S{2}و\\S\$/u", $word) || preg_match("/^م\\S{2}ي\\S\$/u", $word) || preg_match("/^\\S{3}ة\$/u", $word) || preg_match("/^\\S{2}ا\\Sة\$/u", $word) || preg_match("/^\\Sا\\S{2}ة\$/u", $word) || preg_match("/^\\Sا\\Sو\\Sة\$/u", $word) || preg_match("/^ا\\S{2}و\\Sة\$/u", $word) || preg_match("/^ا\\S{2}ي\\S\$/u", $word) || preg_match("/^ا\\S{3}\$/u", $word) || preg_match("/^\\S{3}ى\$/u", $word) || preg_match("/^\\S{3}اء\$/u", $word) || preg_match("/^\\S{3}ان\$/u", $word) || preg_match("/^م\\Sا\\S{2}\$/u", $word) || preg_match("/^من\\S{3}\$/u", $word) || preg_match("/^مت\\S{3}\$/u", $word) || preg_match("/^مست\\S{3}\$/u", $word) || preg_match("/^م\\Sت\\S{2}\$/u", $word) || preg_match("/^مت\\Sا\\S{2}\$/u", $word) || preg_match("/^\\Sا\\S{2}\$/u", $word)) {
            return true;
        }
        return false;
    }
    public static function tagText($str)
    {
        $text = array();
        $words = explode(" ", $str);
        $prevWord = "";
        foreach ($words as $word) {
            if ($word == "") {
                continue;
            }
            if (self::isNoun($word, $prevWord)) {
                $text[] = array($word, 1);
            } else {
                $text[] = array($word, 0);
            }
            $prevWord = $word;
        }
        return $text;
    }
    public static function highlightText($str, $style = NULL)
    {
        $html = "";
        $prevTag = 0;
        $prevWord = "";
        $taggedText = self::tagText($str);
        foreach ($taggedText as $wordTag) {
            list($word, $tag) = $wordTag;
            if ($prevTag == 1) {
                if (in_array($word, self::$_particlePreNouns)) {
                    $prevWord = $word;
                    continue;
                }
                if ($tag == 0) {
                    $html .= "</span> \r\n";
                }
            } else {
                if ($tag == 1) {
                    $html .= " \r\n<span class=\"" . $style . "\">";
                }
            }
            $html .= " " . $prevWord . " " . $word;
            if ($prevWord != "") {
                $prevWord = "";
            }
            $prevTag = $tag;
        }
        if ($prevTag == 1) {
            $html .= "</span> \r\n";
        }
        return $html;
    }
}

?>