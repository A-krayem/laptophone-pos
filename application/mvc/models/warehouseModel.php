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
class warehouseModel
{
    public function get_stores()
    {
        $query_primary = "select id from store";
        $result_primary = my_sql::fetch_assoc(my_sql::query($query_primary));
        return $result_primary;
    }
    public function sync_clients($cnx, $store_id)
    {
        $query = "select id from customers where warehouse_synced=0";
        $clients = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        $c = array();
        for ($i = 0; $i < count($clients); $i++) {
            array_push($c, $clients[$i]["id"]);
            my_sql::query("insert into global_clients(sync_date,client_id,store_id) values(now()," . $clients[$i]["id"] . "," . $store_id . ")");
        }
        if (0 < count($c)) {
            my_sql::custom_connection_query("update customers set warehouse_synced=1 where id in (" . implode(",", $c) . ")", $cnx);
        }
    }
    public function sync_clients_imei($cnx, $store_id)
    {
        $query = "select * from unique_items where customer_id>0 and deleted=0 and warehouse_synced=0";
        $imeis = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        for ($i = 0; $i < count($imeis); $i++) {
            my_sql::query("update unique_items set customer_id=" . $imeis[$i]["customer_id"] . ",registered_in_store_id=" . $store_id . " where id=" . $imeis[$i]["id"]);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::custom_connection_query("update unique_items set warehouse_synced=1 where id=" . $imeis[$i]["id"], $cnx);
            }
        }
    }
    public function sync_clients_imei_warehouse_connected($cnx, $store_id)
    {
        $query = "update unique_items set registered_in_store_id=" . $store_id . " where customer_id>0 and invoice_id>0 and registered_in_store_id=0";
        my_sql::custom_connection_query($query, $cnx);
    }
}

?>