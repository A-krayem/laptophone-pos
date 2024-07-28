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
class rate extends Controller
{
    public $settings_info = false;
    public function __construct()
    {
        $this->settings_info = self::getSettings();
    }
    public function get_rate()
    {
        $logged_in = 0;
        if (isset($_SESSION["id"])) {
            $logged_in = 1;
        }
        echo json_encode(array("rate" => $this->settings_info["usdlbp_rate"], "logged_in" => $logged_in));
    }
}

?>