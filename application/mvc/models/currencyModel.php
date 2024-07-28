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
class currencyModel
{
    public function getAllCurrencies()
    {
        if ($_SESSION["ptype"] == 1) {
            $query = "select * from currencies where id=2";
        } else {
            $query = "select * from currencies";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllActiveCurrencies()
    {
        $query = "select * from currencies where disabled=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return count($result);
    }
    public function getDefaultActiveCurrency()
    {
        $query = "select id from currencies where system_default=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["id"];
    }
    public function getCurrency_by_id($id)
    {
        $query = "select * from currencies where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllCurrenciesEvenDeleted()
    {
        $query = "select * from currencies";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function save_rate($id, $value_rate)
    {
        $query = "update currencies set rate_to_system_default='" . $value_rate . "' where id=" . $id;
        my_sql::query($query);
    }
    public function getAllEnabledCurrencies()
    {
        if ($_SESSION["ptype"] == 1) {
            $query = "select * from currencies where disabled=0 and id=2";
        } else {
            $query = "select * from currencies where disabled=0";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>