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
class I18N_Arabic_Date
{
    private $_mode = 1;
    private $_xml = NULL;
    public function __construct()
    {
        $this->_xml = simplexml_load_file(dirname(__FILE__) . "/data/ArDate.xml");
    }
    public function setMode($mode = 1)
    {
        $mode = (int) $mode;
        if (0 < $mode && $mode < 8) {
            $this->_mode = $mode;
        }
        return $this;
    }
    public function getMode()
    {
        return $this->_mode;
    }
    public function date($format, $timestamp, $correction = 0)
    {
        if ($this->_mode == 1) {
            foreach ($this->_xml->hj_month->month as $month) {
                $hj_txt_month[(string) $month["id"]] = (string) $month;
            }
            $patterns = array();
            $replacements = array();
            array_push($patterns, "Y");
            array_push($replacements, "x1");
            array_push($patterns, "y");
            array_push($replacements, "x2");
            array_push($patterns, "M");
            array_push($replacements, "x3");
            array_push($patterns, "F");
            array_push($replacements, "x3");
            array_push($patterns, "n");
            array_push($replacements, "x4");
            array_push($patterns, "m");
            array_push($replacements, "x5");
            array_push($patterns, "j");
            array_push($replacements, "x6");
            array_push($patterns, "d");
            array_push($replacements, "x7");
            $format = str_replace($patterns, $replacements, $format);
            $str = date($format, $timestamp);
            $str = $this->en2ar($str);
            $timestamp = $timestamp + 3600 * 24 * $correction;
            list($Y, $M, $D) = explode(" ", date("Y m d", $timestamp));
            list($hj_y, $hj_m, $hj_d) = $this->hjConvert($Y, $M, $D);
            $patterns = array();
            $replacements = array();
            array_push($patterns, "x1");
            array_push($replacements, $hj_y);
            array_push($patterns, "x2");
            array_push($replacements, substr($hj_y, -2));
            array_push($patterns, "x3");
            array_push($replacements, $hj_txt_month[$hj_m]);
            array_push($patterns, "x4");
            array_push($replacements, $hj_m);
            array_push($patterns, "x5");
            array_push($replacements, sprintf("%02d", $hj_m));
            array_push($patterns, "x6");
            array_push($replacements, $hj_d);
            array_push($patterns, "x7");
            array_push($replacements, sprintf("%02d", $hj_d));
            $str = str_replace($patterns, $replacements, $str);
        } else {
            if ($this->_mode == 5) {
                $year = date("Y", $timestamp);
                $year -= 632;
                $yr = substr((string) $year, -2);
                $format = str_replace("Y", $year, $format);
                $format = str_replace("y", $yr, $format);
                $str = date($format, $timestamp);
                $str = $this->en2ar($str);
            } else {
                $str = date($format, $timestamp);
                $str = $this->en2ar($str);
            }
        }
        if (0) {
            if ($outputCharset == NULL) {
                $outputCharset = $main->getOutputCharset();
            }
            $str = $main->coreConvert($str, "utf-8", $outputCharset);
        }
        return $str;
    }
    protected function en2ar($str)
    {
        $patterns = array();
        $replacements = array();
        $str = strtolower($str);
        foreach ($this->_xml->xpath("//en_day/mode[@id='full']/search") as $day) {
            array_push($patterns, (string) $day);
        }
        foreach ($this->_xml->ar_day->replace as $day) {
            array_push($replacements, (string) $day);
        }
        foreach ($this->_xml->xpath("//en_month/mode[@id='full']/search") as $month) {
            array_push($patterns, (string) $month);
        }
        $replacements = array_merge($replacements, $this->arabicMonths($this->_mode));
        foreach ($this->_xml->xpath("//en_day/mode[@id='short']/search") as $day) {
            array_push($patterns, (string) $day);
        }
        foreach ($this->_xml->ar_day->replace as $day) {
            array_push($replacements, (string) $day);
        }
        foreach ($this->_xml->xpath("//en_month/mode[@id='short']/search") as $m) {
            array_push($patterns, (string) $m);
        }
        $replacements = array_merge($replacements, $this->arabicMonths($this->_mode));
        foreach ($this->_xml->xpath("//preg_replace[@function='en2ar']/pair") as $p) {
            array_push($patterns, (string) $p->search);
            array_push($replacements, (string) $p->replace);
        }
        $str = str_replace($patterns, $replacements, $str);
        return $str;
    }
    protected function arabicMonths($mode)
    {
        $replacements = array();
        foreach ($this->_xml->xpath("//ar_month/mode[@id=" . $mode . "]/replace") as $month) {
            array_push($replacements, (string) $month);
        }
        return $replacements;
    }
    protected function hjConvert($Y, $M, $D)
    {
        if (function_exists("GregorianToJD")) {
            $jd = GregorianToJD($M, $D, $Y);
        } else {
            $jd = $this->gregToJd($M, $D, $Y);
        }
        list($year, $month, $day) = $this->jdToIslamic($jd);
        return array($year, $month, $day);
    }
    protected function jdToIslamic($jd)
    {
        $l = (int) $jd - 1948440 + 10632;
        $n = (int) (($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = (int) ((10985 - $l) / 5316) * (int) (50 * $l / 17719) + (int) ($l / 5670) * (int) (43 * $l / 15238);
        $l = $l - (int) ((30 - $j) / 15) * (int) (17719 * $j / 50) - (int) ($j / 16) * (int) (15238 * $j / 43) + 29;
        $m = (int) (24 * $l / 709);
        $d = $l - (int) (709 * $m / 24);
        $y = (int) (30 * $n + $j - 30);
        return array($y, $m, $d);
    }
    protected function islamicToJd($year, $month, $day)
    {
        $jd = (int) ((11 * $year + 3) / 30) + (int) (354 * $year) + (int) (30 * $month) - (int) (($month - 1) / 2) + $day + 1948440 - 385;
        return $jd;
    }
    protected function gregToJd($m, $d, $y)
    {
        if ($m < 3) {
            $y--;
            $m += 12;
        }
        if ($y < 1582 || $y == 1582 && $m < 10 || $y == 1582 && $m == 10 && $d <= 15) {
            $b = 0;
        } else {
            $a = (int) ($y / 100);
            $b = 2 - $a + (int) ($a / 4);
        }
        $jd = (int) (365.25 * ($y + 4716)) + (int) (30.6001 * ($m + 1)) + $d + $b - 1524.5;
        return round($jd);
    }
    public function dateCorrection($time)
    {
        $calc = $time - $this->date("j", $time) * 3600 * 24;
        $file = dirname(__FILE__) . "/data/um_alqoura.txt";
        $content = file_get_contents($file);
        $y = $this->date("Y", $time);
        $m = $this->date("n", $time);
        $offset = (($y - 1420) * 12 + $m) * 11;
        $d = substr($content, $offset, 2);
        $m = substr($content, $offset + 3, 2);
        $y = substr($content, $offset + 6, 4);
        $real = mktime(0, 0, 0, $m, $d, $y);
        $diff = (int) (($calc - $real) / (3600 * 24));
        return $diff;
    }
}

?>