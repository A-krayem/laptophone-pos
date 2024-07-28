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
$query = "SELECT si.item_id,si.quantity,it.description FROM `store_items` si left join items it on it.id=si.item_id WHERE si.item_id in (select item_id from unique_items where deleted=0) and si.item_id in (select id from items where deleted=0) and si.quantity>0;";
$result = my_sql::fetch_assoc(my_sql::query($query));
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n<html>\n    <head>\n        <title>IMEI Checker</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <style type=\"text/css\">\n            table tr td{\n                border: 1px solid #ccc;\n            }\n        </style>\n    </head>\n    <body>\n        <table>\n            <tr>\n                <td>Item ID</td>\n                <td>Description</td>\n                <td>Quantity</td>\n                <td>Not Paid</td>\n            </tr>\n            ";
for ($i = 0; $i < count($result); $i++) {
    $result_ = my_sql::fetch_assoc(my_sql::query("SELECT item_id,count(item_id) as num FROM `unique_items` where deleted=0 and customer_id=0 and invoice_id=0 and item_id=" . $result[$i]["item_id"] . " and invoice_id=0 group by `item_id`"));
    echo "            <tr style=\"";
    if ($result[$i]["quantity"] != $result_[0]["num"]) {
        echo "background-color: #ccc; ";
    }
    echo "\">\n                <td>";
    echo $result[$i]["item_id"];
    echo "</td>\n                <td>";
    echo $result[$i]["description"];
    echo "</td>\n                <td>";
    echo $result[$i]["quantity"];
    echo "</td>\n                \n                \n                <td>";
    if (0 < count($result_)) {
        echo $result_[0]["num"];
    } else {
        echo "";
    }
    echo "</td>\n            </tr>\n            ";
}
echo "            \n        </table>\n        \n    </body>\n</html>\n";

?>