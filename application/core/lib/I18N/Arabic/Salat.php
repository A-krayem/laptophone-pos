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
class I18N_Arabic_Salat
{
    protected $year = 1975;
    protected $month = 8;
    protected $day = 2;
    protected $zone = 2;
    protected $long = 37.15861;
    protected $lat = 36.20278;
    protected $elevation = 0;
    protected $AB2 = -0.833333;
    protected $AG2 = -18;
    protected $AJ2 = -18;
    protected $school = "Shafi";
    protected $view = "Sunni";
    public function __construct()
    {
    }
    public function setDate($m = 8, $d = 2, $y = 1975)
    {
        if (is_numeric($y) && 0 < $y && $y < 3000) {
            $this->year = floor($y);
        }
        if (is_numeric($m) && 1 <= $m && $m <= 12) {
            $this->month = floor($m);
        }
        if (is_numeric($d) && 1 <= $d && $d <= 31) {
            $this->day = floor($d);
        }
        return $this;
    }
    public function setLocation($l1 = 36.20278, $l2 = 37.15861, $z = 2, $e = 0)
    {
        if (is_numeric($l1) && -180 <= $l1 && $l1 <= 180) {
            $this->lat = $l1;
        }
        if (is_numeric($l2) && -180 <= $l2 && $l2 <= 180) {
            $this->long = $l2;
        }
        if (is_numeric($z) && -12 <= $z && $z <= 12) {
            $this->zone = floor($z);
        }
        if (is_numeric($e)) {
            $this->elevation = $e;
        }
        return $this;
    }
    public function setConf($sch = "Shafi", $sunriseArc = -0.833333, $ishaArc = -17.5, $fajrArc = -19.5, $view = "Sunni")
    {
        $sch = ucfirst($sch);
        if ($sch == "Shafi" || $sch == "Hanafi") {
            $this->school = $sch;
        }
        if (is_numeric($sunriseArc) && -180 <= $sunriseArc && $sunriseArc <= 180) {
            $this->AB2 = $sunriseArc;
        }
        if (is_numeric($ishaArc) && -180 <= $ishaArc && $ishaArc <= 180) {
            $this->AG2 = $ishaArc;
        }
        if (is_numeric($fajrArc) && -180 <= $fajrArc && $fajrArc <= 180) {
            $this->AJ2 = $fajrArc;
        }
        if ($view == "Sunni" || $view == "Shia") {
            $this->view = $view;
        }
        return $this;
    }
    public function getPrayTime()
    {
        $prayTime = $this->getPrayTime2();
        return $prayTime;
    }
    public function getPrayTime2()
    {
        $unixtimestamp = mktime(0, 0, 0, $this->month, $this->day, $this->year);
        if ($this->month <= 2) {
            $year = $this->year - 1;
            $month = $this->month + 12;
        } else {
            $year = $this->year;
            $month = $this->month;
        }
        $A = floor($year / 100);
        $B = 2 - $A + floor($A / 4);
        $jd = floor(365.25 * ($year + 4716)) + floor(30.6001 * ($month + 1)) + $this->day + $B - 1524.5;
        $d = $jd - 2451545;
        $g = 357.529 + 0.98560028 * $d;
        $g = $g % 360 + $g - ceil($g) + 1;
        $q = 280.459 + 0.98564736 * $d;
        $q = $q % 360 + $q - ceil($q) + 1;
        $L = $q + 1.915 * sin(deg2rad($g)) + 0.02 * sin(deg2rad(2 * $g));
        $L = $L % 360 + $L - ceil($L) + 1;
        $R = 1.00014 - 0.01671 * cos(deg2rad($g)) - 0.00014 * cos(deg2rad(2 * $g));
        $e = 23.439 - 3.6E-7 * $d;
        $RA = rad2deg(atan2(cos(deg2rad($e)) * sin(deg2rad($L)), cos(deg2rad($L)))) / 15;
        if ($RA < 0) {
            $RA = 24 + $RA;
        }
        $D = rad2deg(asin(sin(deg2rad($e)) * sin(deg2rad($L))));
        $EqT = $q / 15 - $RA;
        $Dhuhr = 12 + $this->zone - $this->long / 15 - $EqT;
        $alpha = 0.833 + 0.0347 * sqrt($this->elevation);
        $n = -1 * sin(deg2rad($alpha)) - sin(deg2rad($this->lat)) * sin(deg2rad($D));
        $d = cos(deg2rad($this->lat)) * cos(deg2rad($D));
        $Sunrise = $Dhuhr - 1 / 15 * rad2deg(acos($n / $d));
        $Sunset = $Dhuhr + 1 / 15 * rad2deg(acos($n / $d));
        $n = -1 * sin(deg2rad(abs($this->AJ2))) - sin(deg2rad($this->lat)) * sin(deg2rad($D));
        $Fajr = $Dhuhr - 1 / 15 * rad2deg(acos($n / $d));
        $Imsak = $Fajr - 10 / 60;
        $n = -1 * sin(deg2rad(abs($this->AG2))) - sin(deg2rad($this->lat)) * sin(deg2rad($D));
        $Isha = $Dhuhr + 1 / 15 * rad2deg(acos($n / $d));
        if ($this->school == "Shafi") {
            $n = sin(atan(1 / (1 + tan(deg2rad($this->lat - $D))))) - sin(deg2rad($this->lat)) * sin(deg2rad($D));
        } else {
            $n = sin(atan(1 / (2 + tan(deg2rad($this->lat - $D))))) - sin(deg2rad($this->lat)) * sin(deg2rad($D));
        }
        $Asr = $Dhuhr + 1 / 15 * rad2deg(acos($n / $d));
        $MaghribSunni = $Sunset + 2 / 60;
        $n = -1 * sin(deg2rad(4)) - sin(deg2rad($this->lat)) * sin(deg2rad($D));
        $MaghribShia = $Dhuhr + 1 / 15 * rad2deg(acos($n / $d));
        if ($this->view == "Sunni") {
            $Maghrib = $MaghribSunni;
        } else {
            $Maghrib = $MaghribShia;
        }
        $MidnightSunni = $Sunset + 0.5 * ($Sunrise - $Sunset);
        if (12 < $MidnightSunni) {
            $MidnightSunni = $MidnightSunni - 12;
        }
        $MidnightShia = 0.5 * ($Fajr - $Sunset);
        if (12 < $MidnightShia) {
            $MidnightShia = $MidnightShia - 12;
        }
        if ($this->view == "Sunni") {
            $Midnight = $MidnightSunni;
        } else {
            $Midnight = $MidnightShia;
        }
        $times = array($Fajr, $Sunrise, $Dhuhr, $Asr, $Maghrib, $Isha, $Sunset, $Midnight, $Imsak);
        foreach ($times as $index => $time) {
            $hours = floor($time);
            $minutes = round(($time - $hours) * 60);
            if ($minutes < 10) {
                $minutes = "0" . $minutes;
            }
            $times[$index] = (string) $hours . ":" . $minutes;
            $times[9][$index] = $unixtimestamp + 3600 * $hours + 60 * $minutes;
            if ($index == 7 && $hours < 6) {
                $times[9][$index] += 24 * 3600;
            }
        }
        return $times;
    }
    public function getQibla()
    {
        $K_latitude = 21.423333;
        $K_longitude = 39.823333;
        $latitude = $this->lat;
        $longitude = $this->long;
        $numerator = sin(deg2rad($K_longitude - $longitude));
        $denominator = cos(deg2rad($latitude)) * tan(deg2rad($K_latitude)) - sin(deg2rad($latitude)) * cos(deg2rad($K_longitude - $longitude));
        $q = atan($numerator / $denominator);
        $q = rad2deg($q);
        if (21.423333 < $this->lat) {
            $q += 180;
        }
        return $q;
    }
    public function coordinate2deg($value)
    {
        $pattern = "/(\\d{1,2})°((\\d{1,2})')?((\\d{1,2})\")?([NSEW])/i";
        preg_match($pattern, $value, $matches);
        $degree = $matches[1] + $matches[3] / 60 + $matches[5] / 3600;
        $direction = strtoupper($matches[6]);
        if ($direction == "S" || $direction == "W") {
            $degree = -1 * $degree;
        }
        return $degree;
    }
}

?>