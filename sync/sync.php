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
ini_set("max_execution_time", 1200);
require_once "../config/define.php";
require_once "../config/my_sql.php";
require_once "../application/core/lib/my_sql.php";
$query = "select * from queries where";
$result = my_sql::fetch_assoc(my_sql::query($query));
for ($i = 0; $i < count($result); $i++) {
    echo $result[$i]["query"] . "<br/>";
}

?>