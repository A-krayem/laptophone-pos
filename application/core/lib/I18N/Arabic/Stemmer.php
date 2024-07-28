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
class I18N_Arabic_Stemmer
{
    private static $_verbPre = "وأسفلي";
    private static $_verbPost = "ومكانيه";
    private static $_verbMay = NULL;
    private static $_verbMaxPre = 4;
    private static $_verbMaxPost = 6;
    private static $_verbMinStem = 2;
    private static $_nounPre = "ابفكلوأ";
    private static $_nounPost = "اتةكمنهوي";
    private static $_nounMay = NULL;
    private static $_nounMaxPre = 4;
    private static $_nounMaxPost = 6;
    private static $_nounMinStem = 2;
    public function __construct()
    {
        self::$_verbMay = self::$_verbPre . self::$_verbPost;
        self::$_nounMay = self::$_nounPre . self::$_nounPost;
    }
    public static function stem($word)
    {
        $nounStem = self::roughStem($word, self::$_nounMay, self::$_nounPre, self::$_nounPost, self::$_nounMaxPre, self::$_nounMaxPost, self::$_nounMinStem);
        $verbStem = self::roughStem($word, self::$_verbMay, self::$_verbPre, self::$_verbPost, self::$_verbMaxPre, self::$_verbMaxPost, self::$_verbMinStem);
        if (mb_strlen($nounStem, "UTF-8") < mb_strlen($verbStem, "UTF-8")) {
            $stem = $nounStem;
        } else {
            $stem = $verbStem;
        }
        return $stem;
    }
    protected static function roughStem($word, $notChars, $preChars, $postChars, $maxPre, $maxPost, $minStem)
    {
        $right = -1;
        $left = -1;
        $max = mb_strlen($word, "UTF-8");
        for ($i = 0; $i < $max; $i++) {
            if (mb_strpos($notChars, mb_substr($word, $i, 1, "UTF-8"), 0, "UTF-8") === false) {
                if ($right == -1) {
                    $right = $i;
                }
                $left = $i;
            }
        }
        if ($maxPre < $right) {
            $right = $maxPre;
        }
        if ($maxPost < $max - $left - 1) {
            $left = $max - $maxPost - 1;
        }
        for ($i = 0; $i < $right; $i++) {
            if (mb_strpos($preChars, mb_substr($word, $i, 1, "UTF-8"), 0, "UTF-8") === false) {
                $right = $i;
                break;
            }
        }
        for ($i = $max - 1; $left < $i; $i--) {
            if (mb_strpos($postChars, mb_substr($word, $i, 1, "UTF-8"), 0, "UTF-8") === false) {
                $left = $i;
                break;
            }
        }
        if ($minStem <= $left - $right) {
            $stem = mb_substr($word, $right, $left - $right + 1, "UTF-8");
        } else {
            $stem = NULL;
        }
        return $stem;
    }
}

?>