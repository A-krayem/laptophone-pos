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
class system_info extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
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
    public function get_printers_info()
    {
        $printer_list = printer_list(PRINTER_ENUM_LOCAL);
        $printers__ = array();
        foreach ($printer_list as $printer) {
            $printers__[] = $printer;
        }
        $handle = printer_open("Xprinter XP-360B");
        printer_start_doc($handle, "My Document");
        printer_start_page($handle);
        printer_draw_bmp($handle, "D:\\5054598263625.bmp", 1, 1);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>