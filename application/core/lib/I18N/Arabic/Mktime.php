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
class I18N_Arabic_Mktime
{
    public function __construct()
    {
    }
    public function mktime($hour, $minute, $second, $hj_month, $hj_day, $hj_year, $correction = 0)
    {
        list($year, $month, $day) = $this->convertDate($hj_year, $hj_month, $hj_day);
        $unixTimeStamp = mktime($hour, $minute, $second, $month, $day, $year);
        $unixTimeStamp = $unixTimeStamp + 3600 * 24 * $correction;
        return $unixTimeStamp;
    }
    protected function convertDate($Y, $M, $D)
    {
        if (function_exists("GregorianToJD")) {
            $str = JDToGregorian($this->islamicToJd($Y, $M, $D));
        } else {
            $str = $this->jdToGreg($this->islamicToJd($Y, $M, $D));
        }
        list($month, $day, $year) = explode("/", $str);
        return array($year, $month, $day);
    }
    protected function islamicToJd($year, $month, $day)
    {
        $jd = (int) ((11 * $year + 3) / 30) + (int) (354 * $year) + (int) (30 * $month) - (int) (($month - 1) / 2) + $day + 1948440 - 385;
        return $jd;
    }
    protected function jdToGreg($julian)
    {
        $julian = $julian - 1721119;
        $calc1 = 4 * $julian - 1;
        $year = floor($calc1 / 146097);
        $julian = floor($calc1 - 146097 * $year);
        $day = floor($julian / 4);
        $calc2 = 4 * $day + 3;
        $julian = floor($calc2 / 1461);
        $day = $calc2 - 1461 * $julian;
        $day = floor(($day + 4) / 4);
        $calc3 = 5 * $day - 3;
        $month = floor($calc3 / 153);
        $day = $calc3 - 153 * $month;
        $day = floor(($day + 5) / 5);
        $year = 100 * $year + $julian;
        if ($month < 10) {
            $month = $month + 3;
        } else {
            $month = $month - 9;
            $year = $year + 1;
        }
        if ($year < 1) {
            $year--;
        }
        return $month . "/" . $day . "/" . $year;
    }
    public function mktimeCorrection($m, $y)
    {
        if (1420 <= $y && $y < 1460) {
            $calc = $this->mktime(0, 0, 0, $m, 1, $y);
            $file = dirname(__FILE__) . "/data/um_alqoura.txt";
            $content = file_get_contents($file);
            $offset = (($y - 1420) * 12 + $m) * 11;
            $d = substr($content, $offset, 2);
            $m = substr($content, $offset + 3, 2);
            $y = substr($content, $offset + 6, 4);
            $real = mktime(0, 0, 0, $m, $d, $y);
            $diff = (int) (($real - $calc) / (3600 * 24));
        } else {
            $diff = 0;
        }
        return $diff;
    }
    public function hijriMonthDays($m, $y, $umAlqoura = true)
    {
        if (1320 <= $y && $y < 1460) {
            $begin = $this->mktime(0, 0, 0, $m, 1, $y);
            if ($m == 12) {
                $m2 = 1;
                $y2 = $y + 1;
            } else {
                $m2 = $m + 1;
                $y2 = $y;
            }
            $end = $this->mktime(0, 0, 0, $m2, 1, $y2);
            if ($umAlqoura === true) {
                $c1 = $this->mktimeCorrection($m, $y);
                $c2 = $this->mktimeCorrection($m2, $y2);
            } else {
                $c1 = 0;
                $c2 = 0;
            }
            $days = ($end - $begin) / (3600 * 24);
            $days = $days - $c1 + $c2;
        } else {
            $days = false;
        }
        return $days;
    }
}

?>