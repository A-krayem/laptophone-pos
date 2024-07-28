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
class barcode extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
    }
    public function print_barcode()
    {
        $this->view("printing/barcode");
    }
    public function get_info($id_)
    {
        $items = $this->model("items");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $info = $items->get_item($id);
        echo json_encode($info);
    }
}

?>