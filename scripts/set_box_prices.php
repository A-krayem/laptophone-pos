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
require_once "../config/define.php";
require_once "../config/my_sql.php";
require_once "../application/core/lib/my_sql.php";
$query = "SELECT id FROM `items` WHERE is_composite=1 order by id desc";
$result = my_sql::fetch_assoc(my_sql::query($query));
for ($i = 0; $i < count($result); $i++) {
    $query_ = "SELECT id,item_id,qty FROM `items_composite` WHERE composite_item_id=" . $result[$i]["id"];
    $result_ = my_sql::fetch_assoc(my_sql::query($query_));
    if (0 < count($result_)) {
        $query_info = "SELECT * FROM `items` WHERE id=" . $result_[0]["item_id"];
        $result_info = my_sql::fetch_assoc(my_sql::query($query_info));
        my_sql::query("update items set buying_cost=" . $result_info[0]["buying_cost"] * $result_[0]["qty"] . ",selling_price=" . $result_info[0]["selling_price"] * $result_[0]["qty"] . ",wholesale_price=" . $result_info[0]["wholesale_price"] * $result_[0]["qty"] . ",second_wholesale_price=" . $result_info[0]["second_wholesale_price"] * $result_[0]["qty"] . " where id=" . $result[$i]["id"] . " and is_composite=1 and selling_price=0");
    }
}

?>