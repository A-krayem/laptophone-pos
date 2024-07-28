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
$targetUrl = "https://demo.upsilon.systems/api/api.php?k=Wes1tR3&endpoint=add_quotation";
$items = array();
array_push($items, array("pos_item_id" => 349, "qty" => 2, "selling_price" => 5.2, "foreign_item_id" => 2));
$postData = array("client_id" => "15", "client_name" => "Youssef", "invoice_id" => "1", "items" => json_encode($items));
$ch = curl_init($targetUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo "Curl error: " . curl_error($ch);
}
curl_close($ch);
echo $response;

?>