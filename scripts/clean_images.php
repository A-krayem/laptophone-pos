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
$query = "SELECT * FROM `items_images`";
$result = my_sql::fetch_assoc(my_sql::query($query));
for ($i = 0; $i < count($result); $i++) {
    $directory = "../data/images_items/";
    $imageName = $result[$i]["name"];
    $imagePath = $directory . "/" . $imageName;
    if (!file_exists($imagePath)) {
    }
}

?>