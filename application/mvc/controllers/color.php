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
class color extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
    }
    public function add_new_color()
    {
        $colors = $this->model("colors");
        $info["color_name"] = filter_input(INPUT_POST, "color_name", self::conversion_php_version_filter());
        $last_insert_id = $colors->add_new_color($info);
        $info_to_return = array();
        $info_to_return["id"] = $last_insert_id;
        $info_to_return["color_name"] = $info["color_name"];
        echo json_encode($info_to_return);
    }
}

?>