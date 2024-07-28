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
class printer extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function get_all_printers($type)
    {
        self::giveAccessTo();
        $printer_list = printer_list(PRINTER_ENUM_LOCAL | PRINTER_ENUM_SHARED);
        $printer_list = printer_list(PRINTER_ENUM_LOCAL | PRINTER_ENUM_SHARED);
        $printers__ = array();
        $index = 0;
        if ($type == "r") {
            $_pn = $this->settings_info["printer_name"];
        } else {
            $_pn = $this->settings_info["printer_barcode_name"];
        }
        foreach ($printer_list as $printer) {
            $printers__[$index]["name"] = $printer["NAME"];
            if ($_pn == $printer["NAME"]) {
                $printers__[$index]["selected"] = 1;
            } else {
                $printers__[$index]["selected"] = 0;
            }
            $index++;
        }
        echo json_encode($printers__);
    }
}

?>