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
$query = "select * from invoice_items where item_id>=568 and item_id not in (select item_id from history_quantities where `source`='pos')";
$result = my_sql::fetch_assoc(my_sql::query($query));
for ($i = 0; $i < count($result); $i++) {
    $query_h = "SELECT * FROM `history_quantities` WHERE `item_id` = " . $result[$i]["item_id"];
    $result_h = my_sql::fetch_assoc(my_sql::query($query_h));
    if (count($result_h) == 1) {
        $query_invoice = "SELECT * FROM `invoices` WHERE `id` = " . $result[$i]["invoice_id"];
        $result_invoice = my_sql::fetch_assoc(my_sql::query($query_invoice));
        $query_only_one = "select count(id) as num from invoice_items where item_id=" . $result[$i]["item_id"];
        $result_only_one = my_sql::fetch_assoc(my_sql::query($query_only_one));
        if ($result_only_one[0]["num"] == 1) {
            echo "INSERT INTO `history_quantities` (`user_id`, `item_id`, `creation_date`, `qty`, `store_id`, `qty_afer_action`, `source`, `is_pos_transfer`, `description`) VALUES ('2', '" . $result[$i]["item_id"] . "', '" . $result_invoice[0]["creation_date"] . "', '-1.00000', '1', '" . ($result_h[0]["qty"] - 1) . "', 'pos', '0', NULL); <br/>";
            echo "update store_items set quantity=" . ($result_h[0]["qty"] - 1) . " where item_id=" . $result[$i]["item_id"] . " and store_id=1; <br/><br/>";
        }
    }
}

?>