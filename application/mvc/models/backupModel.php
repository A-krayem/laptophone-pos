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
class backupModel
{
    public function getAllTables()
    {
        $query = "SHOW TABLES";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_last_backup()
    {
        my_sql::query("update settings set value='" . my_sql::datetime_now() . "' where name='last_backup'");
    }
    public function get_last_backup()
    {
        $query = "select value as tt from settings where name='last_backup'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["tt"];
    }
}

?>