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
class size extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
    }
    public function sizes_mng()
    {
        self::giveAccessTo();
        $data = array();
        $this->view("sizes_mng", $data);
    }
    public function add_new_size()
    {
        $sizes = $this->model("sizes");
        $info["size_name"] = filter_input(INPUT_POST, "size_name", self::conversion_php_version_filter());
        $last_insert_id = $sizes->add_new_size($info);
        $info_to_return = array();
        $info_to_return["id"] = $last_insert_id;
        $info_to_return["size_name"] = $info["size_name"];
        echo json_encode($info_to_return);
    }
}

?>