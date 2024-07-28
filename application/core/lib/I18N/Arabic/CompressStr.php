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
class I18N_Arabic_CompressStr
{
    private static $_encode = NULL;
    private static $_binary = NULL;
    private static $_hex = NULL;
    private static $_bin = NULL;
    public function __construct()
    {
        self::$_encode = iconv("utf-8", "cp1256", " الميوتة");
        self::$_binary = "0000|0001|0010|0011|0100|0101|0110|0111|";
        self::$_hex = "0123456789abcdef";
        self::$_bin = "0000|0001|0010|0011|0100|0101|0110|0111|1000|";
        self::$_bin = self::$_bin . "1001|1010|1011|1100|1101|1110|1111|";
    }
    public static function setLang($lang)
    {
        switch ($lang) {
            case "en":
                self::$_encode = " etaoins";
                break;
            case "fr":
                self::$_encode = " enasriu";
                break;
            case "gr":
                self::$_encode = " enristu";
                break;
            case "it":
                self::$_encode = " eiaorln";
                break;
            case "sp":
                self::$_encode = " eaosrin";
                break;
            default:
                self::$_encode = iconv("utf-8", "cp1256", " الميوتة");
        }
        self::$_binary = "0000|0001|0010|0011|0100|0101|0110|0111|";
        return $this;
    }
    public static function compress($str)
    {
        $str = iconv("utf-8", "cp1256", $str);
        $bits = self::str2bits($str);
        $hex = self::bits2hex($bits);
        $bin = pack("h*", $hex);
        return $bin;
    }
    public static function decompress($bin)
    {
        $temp = unpack("h*", $bin);
        $bytes = $temp[1];
        $bits = self::hex2bits($bytes);
        $str = self::bits2str($bits);
        $str = iconv("cp1256", "utf-8", $str);
        return $str;
    }
    public static function search($bin, $word)
    {
        $wBits = self::str2bits($word);
        $temp = unpack("h*", $bin);
        $bytes = $temp[1];
        $bits = self::hex2bits($bytes);
        if (strpos($bits, $wBits)) {
            return true;
        }
        return false;
    }
    public static function length($bin)
    {
        $temp = unpack("h*", $bin);
        $bytes = $temp[1];
        $bits = self::hex2bits($bytes);
        $count = 0;
        $i = 0;
        while (isset($bits[$i])) {
            $count++;
            if ($bits[$i] == 1) {
                $i += 9;
            } else {
                $i += 4;
            }
        }
        return $count;
    }
    protected static function str2bits($str)
    {
        $bits = "";
        $total = strlen($str);
        $i = -1;
        while (++$i < $total) {
            $char = $str[$i];
            $pos = strpos(self::$_encode, $char);
            if ($pos !== false) {
                $bits .= substr(self::$_binary, $pos * 5, 4);
            } else {
                $int = ord($char);
                $bits .= "1" . substr(self::$_bin, (int) ($int / 16) * 5, 4);
                $bits .= substr(self::$_bin, $int % 16 * 5, 4);
            }
        }
        $add = strlen($bits) % 4;
        $bits .= str_repeat("0", $add);
        return $bits;
    }
    protected static function bits2str($bits)
    {
        $str = "";
        while ($bits) {
            $flag = substr($bits, 0, 1);
            $bits = substr($bits, 1);
            if ($flag == 1) {
                $byte = substr($bits, 0, 8);
                $bits = substr($bits, 8);
                if ($bits || strlen($code) == 8) {
                    $int = base_convert($byte, 2, 10);
                    $char = chr($int);
                    $str .= $char;
                }
            } else {
                $code = substr($bits, 0, 3);
                $bits = substr($bits, 3);
                if ($bits || strlen($code) == 3) {
                    $pos = strpos(self::$_binary, "0" . $code . "|");
                    $str .= substr(self::$_encode, $pos / 5, 1);
                }
            }
        }
        return $str;
    }
    protected static function bits2hex($bits)
    {
        $hex = "";
        $total = strlen($bits) / 4;
        for ($i = 0; $i < $total; $i++) {
            $nibbel = substr($bits, $i * 4, 4);
            $pos = strpos(self::$_bin, $nibbel);
            $hex .= substr(self::$_hex, $pos / 5, 1);
        }
        return $hex;
    }
    protected static function hex2bits($hex)
    {
        $bits = "";
        $total = strlen($hex);
        for ($i = 0; $i < $total; $i++) {
            $pos = strpos(self::$_hex, $hex[$i]);
            $bits .= substr(self::$_bin, $pos * 5, 4);
        }
        return $bits;
    }
}

?>