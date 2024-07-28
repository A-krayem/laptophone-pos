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
class measure extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
    }
    public function add_new_unit()
    {
        $measures = $this->model("measures");
        $info["unit_name"] = filter_input(INPUT_POST, "unit_name", self::conversion_php_version_filter());
        $last_insert_id = $measures->add_new_unit($info);
        $info_to_return = array();
        $info_to_return["id"] = $last_insert_id;
        $info_to_return["unit_name"] = $info["unit_name"];
        echo json_encode($info_to_return);
    }
}

?>