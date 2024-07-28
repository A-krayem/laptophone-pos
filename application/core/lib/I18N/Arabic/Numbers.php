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
class I18N_Arabic_Numbers
{
    private $_individual = array();
    private $_complications = array();
    private $_arabicIndic = array();
    private $_ordering = array();
    private $_currency = array();
    private $_spell = array();
    private $_feminine = 1;
    private $_format = 1;
    private $_order = 1;
    public function __construct()
    {
        $xml = simplexml_load_file(dirname(__FILE__) . "/data/ArNumbers.xml");
        foreach ($xml->xpath("//individual/number[@gender='male']") as $num) {
            if (isset($num["grammar"])) {
                $grammar = $num["grammar"];
                $this->_individual[(string) $num["value"]][1][(string) $grammar] = (string) $num;
            } else {
                $this->_individual[(string) $num["value"]][1] = (string) $num;
            }
        }
        foreach ($xml->xpath("//individual/number[@gender='female']") as $num) {
            if (isset($num["grammar"])) {
                $grammar = $num["grammar"];
                $this->_individual[(string) $num["value"]][2][(string) $grammar] = (string) $num;
            } else {
                $this->_individual[(string) $num["value"]][2] = (string) $num;
            }
        }
        foreach ($xml->xpath("//individual/number[@value>19]") as $num) {
            if (isset($num["grammar"])) {
                $grammar = $num["grammar"];
                $this->_individual[(string) $num["value"]][(string) $grammar] = (string) $num;
            } else {
                $this->_individual[(string) $num["value"]] = (string) $num;
            }
        }
        foreach ($xml->complications->number as $num) {
            $scale = $num["scale"];
            $format = $num["format"];
            $this->_complications[(string) $scale][(string) $format] = (string) $num;
        }
        foreach ($xml->arabicIndic->number as $html) {
            $value = $html["value"];
            $this->_arabicIndic[(string) $value] = $html;
        }
        foreach ($xml->xpath("//order/number[@gender='male']") as $num) {
            $this->_ordering[(string) $num["value"]][1] = (string) $num;
        }
        foreach ($xml->xpath("//order/number[@gender='female']") as $num) {
            $this->_ordering[(string) $num["value"]][2] = (string) $num;
        }
        foreach ($xml->xpath("//individual/number[@value<11 or @value>19]") as $num) {
            $str = str_replace(array("أ", "إ", "آ"), "ا", (string) $num);
            $this->_spell[$str] = (int) $num["value"];
        }
        $xml = simplexml_load_file(dirname(__FILE__) . "/data/arab_countries.xml");
        foreach ($xml->xpath("//currency") as $info) {
            $this->_currency[(string) $info->iso]["ar"]["basic"] = $info->money->arabic->basic;
            $this->_currency[(string) $info->iso]["ar"]["fraction"] = $info->money->arabic->fraction;
            $this->_currency[(string) $info->iso]["en"]["basic"] = $info->money->english->basic;
            $this->_currency[(string) $info->iso]["en"]["fraction"] = $info->money->english->fraction;
            $this->_currency[(string) $info->iso]["decimals"] = $info->money->decimals;
        }
    }
    public function setFeminine($value)
    {
        if ($value == 1 || $value == 2) {
            $this->_feminine = $value;
        }
        return $this;
    }
    public function setFormat($value)
    {
        if ($value == 1 || $value == 2) {
            $this->_format = $value;
        }
        return $this;
    }
    public function setOrder($value)
    {
        if ($value == 1 || $value == 2) {
            $this->_order = $value;
        }
        return $this;
    }
    public function getFeminine()
    {
        return $this->_feminine;
    }
    public function getFormat()
    {
        return $this->_format;
    }
    public function getOrder()
    {
        return $this->_format;
    }
    public function int2str($number)
    {
        if ($number == 1 && $this->_order == 2) {
            if ($this->_feminine == 1) {
                $string = "الأول";
            } else {
                $string = "الأولى";
            }
        } else {
            if ($number < 0) {
                $string = "سالب ";
                $number = (string) -1 * $number;
            } else {
                $string = "";
            }
            $temp = explode(".", $number);
            $string .= $this->subInt2str($temp[0]);
            if (!empty($temp[1])) {
                $dec = $this->subInt2str($temp[1]);
                $string .= " فاصلة " . $dec;
            }
        }
        return $string;
    }
    public function money2str($number, $iso = "SYP", $lang = "ar")
    {
        $iso = strtoupper($iso);
        $lang = strtolower($lang);
        $number = sprintf("%01." . $this->_currency[$iso]["decimals"] . "f", $number);
        $temp = explode(".", $number);
        $string = $this->subInt2str($temp[0]);
        $string .= " " . $this->_currency[$iso][$lang]["basic"];
        if (!empty($temp[1])) {
            if ($lang == "ar") {
                $string .= " و ";
            } else {
                $string .= " and ";
            }
            $string .= $this->subInt2str((int) $temp[1]);
            $string .= " " . $this->_currency[$iso][$lang]["fraction"];
        }
        return $string;
    }
    public function str2int($str)
    {
        $str = str_replace(array("أ", "إ", "آ"), "ا", $str);
        $str = str_replace("ه", "ة", $str);
        $str = preg_replace("/\\s+/", " ", $str);
        $str = str_replace(array("ـ", "َ", "ً", "ُ", "ٌ", "ِ", "ٍ", "ْ", "ّ"), "", $str);
        $str = str_replace("مائة", "مئة", $str);
        $str = str_replace(array("احدى", "احد"), "واحد", $str);
        $str = str_replace(array("اثنا", "اثني", "اثنتا", "اثنتي"), "اثنان", $str);
        $str = trim($str);
        if (strpos($str, "ناقص") === false && strpos($str, "سالب") === false) {
            $negative = false;
        } else {
            $negative = true;
        }
        $segment = array();
        $max = count($this->_complications);
        for ($scale = $max; 0 < $scale; $scale--) {
            $key = pow(1000, $scale);
            $format1 = str_replace(array("أ", "إ", "آ"), "ا", $this->_complications[$scale][1]);
            $format2 = str_replace(array("أ", "إ", "آ"), "ا", $this->_complications[$scale][2]);
            $format3 = str_replace(array("أ", "إ", "آ"), "ا", $this->_complications[$scale][3]);
            $format4 = str_replace(array("أ", "إ", "آ"), "ا", $this->_complications[$scale][4]);
            if (strpos($str, $format1) !== false) {
                list($temp, $str) = explode($format1, $str);
                $segment[$key] = "اثنان";
            } else {
                if (strpos($str, $format2) !== false) {
                    list($temp, $str) = explode($format2, $str);
                    $segment[$key] = "اثنان";
                } else {
                    if (strpos($str, $format3) !== false) {
                        list($segment[$key], $str) = explode($format3, $str);
                    } else {
                        if (strpos($str, $format4) !== false) {
                            list($segment[$key], $str) = explode($format4, $str);
                            if ($segment[$key] == "") {
                                $segment[$key] = "واحد";
                            }
                        }
                    }
                }
            }
            if ($segment[$key] != "") {
                $segment[$key] = trim($segment[$key]);
            }
        }
        $segment[1] = trim($str);
        $total = 0;
        $subTotal = 0;
        foreach ($segment as $scale => $str) {
            $str = " " . $str . " ";
            foreach ($this->_spell as $word => $value) {
                if (strpos($str, (string) $word . " ") !== false) {
                    $str = str_replace((string) $word . " ", " ", $str);
                    $subTotal += $value;
                }
            }
            $total += $subTotal * $scale;
            $subTotal = 0;
        }
        if ($negative) {
            $total = -1 * $total;
        }
        return $total;
    }
    protected function subInt2str($number, $zero = true)
    {
        $blocks = array();
        $items = array();
        $zeros = "";
        $string = "";
        $number = $zero != false ? trim($number) : trim((double) $number);
        if (0 < $number) {
            if ($zero != false) {
                $fulnum = $number;
                while ($fulnum[0] == "0") {
                    $zeros = "صفر " . $zeros;
                    $fulnum = substr($fulnum, 1, strlen($fulnum));
                }
            }
            while (3 < strlen($number)) {
                array_push($blocks, substr($number, -3));
                $number = substr($number, 0, strlen($number) - 3);
            }
            array_push($blocks, $number);
            $blocks_num = count($blocks) - 1;
            for ($i = $blocks_num; 0 <= $i; $i--) {
                $number = floor($blocks[$i]);
                $text = $this->writtenBlock($number);
                if ($text) {
                    if ($number == 1 && $i != 0) {
                        $text = $this->_complications[$i][4];
                        if ($this->_order == 2) {
                            $text = "ال" . $text;
                        }
                    } else {
                        if ($number == 2 && $i != 0) {
                            $text = $this->_complications[$i][$this->_format];
                            if ($this->_order == 2) {
                                $text = "ال" . $text;
                            }
                        } else {
                            if (2 < $number && $number < 11 && $i != 0) {
                                $text .= " " . $this->_complications[$i][3];
                                if ($this->_order == 2) {
                                    $text = "ال" . $text;
                                }
                            } else {
                                if ($i != 0) {
                                    $text .= " " . $this->_complications[$i][4];
                                    if ($this->_order == 2) {
                                        $text = "ال" . $text;
                                    }
                                }
                            }
                        }
                    }
                    if ($text != "" && $zeros != "" && $zero != false) {
                        $text = $zeros . " " . $text;
                        $zeros = "";
                    }
                    array_push($items, $text);
                }
            }
            $string = implode(" و ", $items);
        } else {
            $string = "صفر";
        }
        return $string;
    }
    protected function writtenBlock($number)
    {
        $items = array();
        $string = "";
        if (99 < $number) {
            $hundred = floor($number / 100) * 100;
            $number = $number % 100;
            if ($this->_order == 2) {
                $pre = "ال";
            } else {
                $pre = "";
            }
            if ($hundred == 200) {
                array_push($items, $pre . $this->_individual[$hundred][$this->_format]);
            } else {
                array_push($items, $pre . $this->_individual[$hundred]);
            }
        }
        if ($number != 0) {
            if ($this->_order == 2) {
                if ($number <= 10) {
                    array_push($items, $this->_ordering[$number][$this->_feminine]);
                } else {
                    if ($number < 20) {
                        $number -= 10;
                        $item = "ال" . $this->_ordering[$number][$this->_feminine];
                        if ($this->_feminine == 1) {
                            $item .= " عشر";
                        } else {
                            $item .= " عشرة";
                        }
                        array_push($items, $item);
                    } else {
                        $ones = $number % 10;
                        $tens = floor($number / 10) * 10;
                        array_push($items, "ال" . $this->_ordering[$ones][$this->_feminine]);
                        array_push($items, "ال" . $this->_individual[$tens][$this->_format]);
                    }
                }
            } else {
                if ($number == 2 || $number == 12) {
                    array_push($items, $this->_individual[$number][$this->_feminine][$this->_format]);
                } else {
                    if ($number < 20) {
                        array_push($items, $this->_individual[$number][$this->_feminine]);
                    } else {
                        $ones = $number % 10;
                        $tens = floor($number / 10) * 10;
                        if ($ones == 2) {
                            array_push($items, $this->_individual[$ones][$this->_feminine][$this->_format]);
                        } else {
                            if (0 < $ones) {
                                array_push($items, $this->_individual[$ones][$this->_feminine]);
                            }
                        }
                        array_push($items, $this->_individual[$tens][$this->_format]);
                    }
                }
            }
        }
        $items = array_diff($items, array(""));
        $string = implode(" و ", $items);
        return $string;
    }
    public function int2indic($number)
    {
        $str = strtr((string) $number, $this->_arabicIndic);
        return $str;
    }
}

?>