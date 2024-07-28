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
class servicesModel
{
    public function insertItem($info)
    {
        $query = "insert into items(description,item_category,buying_cost,selling_price,barcode,supplier_reference) " . "value('" . $info["description"] . "'," . $info["item_category"] . "," . $info["buying_cost"] . "," . $info["selling_price"] . ",'" . $info["barcode"] . "'," . $info["supplier_reference"] . ")";
        $result = my_sql::query($query);
        return $result;
    }
    public function insertSupplier($info)
    {
        $query = "insert into suppliers(name,country_id,contact_name,address) value('" . $info["name"] . "'," . rand(1, 245) . ",'" . $info["contact_name"] . "','" . $info["address"] . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
}

?>