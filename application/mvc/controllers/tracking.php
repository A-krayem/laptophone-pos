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
class tracking extends Controller
{
    public $settings_info = NULL;
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function _default()
    {
        self::giveAccessTo();
    }
    public function tracking_quantities()
    {
        self::giveAccessTo();
        $this->view("tracking_quantities");
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>