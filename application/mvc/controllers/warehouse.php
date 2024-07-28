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
class warehouse extends Controller
{
    public function __construct()
    {
    }
    public function sync_clients()
    {
        $warehouse = $this->model("warehouse");
        $stores = $warehouse->get_stores();
        for ($i = 0; $i < count($stores); $i++) {
            $cnx = self::get_store_connection($stores[$i]["id"]);
            $warehouse->sync_clients($cnx, $stores[$i]["id"]);
        }
        echo json_encode(array());
    }
}

?>