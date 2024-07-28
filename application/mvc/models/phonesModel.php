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
class phonesModel
{
    public function add_phones($info, $sup_id)
    {
        $query = "insert into phones(supplier_id,phone_type,phone_number,country_code) values(" . $sup_id . ",'phone','" . $info["sup_phone"] . "','ccc')";
        my_sql::query($query);
    }
    public function getSupplierContacts($sup_id)
    {
        $query = "select * from phones where supplier_id=" . $sup_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>