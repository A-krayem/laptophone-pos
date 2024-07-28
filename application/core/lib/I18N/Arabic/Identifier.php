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
class I18N_Arabic_Identifier
{
    public function __construct()
    {
    }
    public static function identify($str)
    {
        $minAr = 55436;
        $maxAr = 55698;
        $probAr = false;
        $arFlag = false;
        $arRef = array();
        $max = strlen($str);
        $i = -1;
        while (++$i < $max) {
            $cDec = ord($str[$i]);
            if (33 <= $cDec && $cDec <= 58) {
                continue;
            }
            if (!$probAr && ($cDec == 216 || $cDec == 217)) {
                $probAr = true;
                continue;
            }
            if (0 < $i) {
                $pDec = ord($str[$i - 1]);
            } else {
                $pDec = NULL;
            }
            if ($probAr) {
                $utfDecCode = ($pDec << 8) + $cDec;
                if ($minAr <= $utfDecCode && $utfDecCode <= $maxAr) {
                    if (!$arFlag) {
                        $arFlag = true;
                        $arRef[] = $i - 1;
                    }
                } else {
                    if ($arFlag) {
                        $arFlag = false;
                        $arRef[] = $i - 1;
                    }
                }
                $probAr = false;
                continue;
            }
            if ($arFlag && !preg_match("/^\\s\$/", $str[$i])) {
                $arFlag = false;
                $arRef[] = $i;
            }
        }
        return $arRef;
    }
    public static function isArabic($str)
    {
        $isArabic = false;
        $arr = self::identify($str);
        if (count($arr) == 1 && $arr[0] == 0) {
            $isArabic = true;
        }
        return $isArabic;
    }
}

?>