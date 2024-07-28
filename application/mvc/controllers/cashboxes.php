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
class cashboxes extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
    }
    public function get_all_cashboxes()
    {
        $store = $this->model("store");
        $cashbox = $this->model("cashbox");
        $all_stores = $store->getStoresNotGlobal();
        $info["stores"] = array();
        $index = 0;
        for ($i = 0; $i < count($all_stores); $i++) {
            $cnx = self::get_store_connection($all_stores[$i]["id"]);
            $opened_casboxes = $cashbox->get_all_opened_cashbox_remote($cnx);
            for ($k = 0; $k < count($opened_casboxes); $k++) {
                $info["stores"][$index]["id"] = $all_stores[$i]["id"];
                $info["stores"][$index]["name"] = $all_stores[$i]["name"];
                $info_c = self::_get_full_remote_report_table(1, $opened_casboxes[$k]["id"], $all_stores[$i]["id"]);
                $info["stores"][$index]["net_usd"] = number_format($info_c["net_usd"], 0);
                $info["stores"][$index]["net_lbp"] = number_format($info_c["net_lbp"], 0);
                $index++;
            }
        }
        echo json_encode($info);
    }
    public function all_cashboxes()
    {
        $data = array();
        $this->view("cashboxes", $data);
    }
}

?>