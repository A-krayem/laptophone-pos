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
class I18N_Arabic_Normalise
{
    private $_unshapeMap = array();
    private $_unshapeKeys = array();
    private $_unshapeValues = array();
    private $_chars = array();
    private $_charGroups = array();
    private $_charArNames = array();
    public function __construct()
    {
        include dirname(__FILE__) . "/data/charset/ArUnicode.constants.php";
        $this->_unshapeMap = $ligature_map;
        $this->_unshapeKeys = array_keys($this->_unshapeMap);
        $this->_unshapeValues = array_values($this->_unshapeMap);
        $this->_chars = $char_names;
        $this->_charGroups = $char_groups;
        $this->_charArNames = $char_ar_names;
    }
    public function stripTatweel($text)
    {
        return str_replace($this->_chars["TATWEEL"], "", $text);
    }
    public function stripTashkeel($text)
    {
        $tashkeel = array($this->_chars["FATHATAN"], $this->_chars["DAMMATAN"], $this->_chars["KASRATAN"], $this->_chars["FATHA"], $this->_chars["DAMMA"], $this->_chars["KASRA"], $this->_chars["SUKUN"], $this->_chars["SHADDA"]);
        return str_replace($tashkeel, "", $text);
    }
    public function normaliseHamza($text)
    {
        $this->_chars["WAW_HAMZA"] = $this->_chars["WAW"];
        $this->_chars["YEH_HAMZA"] = $this->_chars["YEH"];
        $replace = array($this->_chars["WAW_HAMZA"], $this->_chars["YEH_HAMZA"]);
        $alephs = array($this->_chars["ALEF_MADDA"], $this->_chars["ALEF_HAMZA_ABOVE"], $this->_chars["ALEF_HAMZA_BELOW"], $this->_chars["HAMZA_ABOVE,HAMZA_BELOW"]);
        $text = str_replace(array_keys($replace), array_values($replace), $text);
        $text = str_replace($alephs, $this->_chars["ALEF"], $text);
        return $text;
    }
    public function normaliseLamaleph($text)
    {
        $text = str_replace($this->_chars["LAM_ALEPH"], $simple_LAM_ALEPH, $text);
        $text = str_replace($this->_chars["LAM_ALEPH_HAMZA_ABOVE"], $simple_LAM_ALEPH_HAMZA_ABOVE, $text);
        $text = str_replace($this->_chars["LAM_ALEPH_HAMZA_BELOW"], $simple_LAM_ALEPH_HAMZA_BELOW, $text);
        $text = str_replace($this->_chars["LAM_ALEPH_MADDA_ABOVE"], $simple_LAM_ALEPH_MADDA_ABOVE, $text);
        return $text;
    }
    public function unichr($u)
    {
        return mb_convert_encoding("&#" . intval($u) . ";", "UTF-8", "HTML-ENTITIES");
    }
    public function normalise($text)
    {
        $text = $this->stripTashkeel($text);
        $text = $this->stripTatweel($text);
        $text = $this->normaliseHamza($text);
        $text = $this->normaliseLamaleph($text);
        return $text;
    }
    public function unshape($text)
    {
        return str_replace($this->_unshapeKeys, $this->_unshapeValues, $text);
    }
    public function utf8Strrev($str, $reverse_numbers = false)
    {
        preg_match_all("/./us", $str, $ar);
        if ($reverse_numbers) {
            return join("", array_reverse($ar[0]));
        }
        $temp = array();
        foreach ($ar[0] as $value) {
            if (is_numeric($value) && !empty($temp[0]) && is_numeric($temp[0])) {
                foreach ($temp as $key => $value2) {
                    if (is_numeric($value2)) {
                        $pos = $key + 1;
                    } else {
                        break;
                    }
                }
                $temp2 = array_splice($temp, $pos);
                $temp = array_merge($temp, array($value), $temp2);
            } else {
                array_unshift($temp, $value);
            }
        }
        return implode("", $temp);
    }
    public function isTashkeel($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["TASHKEEL"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isHaraka($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["HARAKAT"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isShortharaka($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["SHORTHARAKAT"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isTanwin($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["TANWIN"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isLigature($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["LIGUATURES"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isHamza($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["HAMZAT"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isAlef($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["ALEFAT"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isWeak($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["WEAK"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isYehlike($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["YEHLIKE"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isWawlike($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["WAWLIKE"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isTehlike($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["TEHLIKE"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isSmall($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["SMALL"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isMoon($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["MOON"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function isSun($archar)
    {
        $key = array_search($archar, $this->_chars);
        if (in_array($key, $this->_charGroups["SUN"])) {
            $value = true;
        } else {
            $value = false;
        }
        return $value;
    }
    public function charName($archar)
    {
        $key = array_search($archar, $this->_chars);
        $name = $this->_charArNames[(string) $key];
        return $name;
    }
}

?>