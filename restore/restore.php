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
require_once "../config/my_sql.php";
$cnx = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
$file = fopen("log-2019-06-24.txt", "r") or exit("Unable to open file!");
while (!feof($file)) {
    $qry = fgets($file);
    $qry_2 = explode(": ", $qry);
    if (strpos($qry_2[1], "insert ") !== false || strpos($qry_2[1], "update ") !== false || strpos($qry_2[1], "delete ") !== false) {
        echo $qry_2[1] . "<br/>";
    }
}
fclose($file);

?>