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
class licenseModel
{
    public function updateLicense($info)
    {
        $query = "update settings set value='" . $info["activation_code"] . "' where name='activation_code'";
        my_sql::query($query);
        $query = "update settings set value='" . $info["serial_number"] . "' where name='serial_number'";
        my_sql::query($query);
    }
}

?>