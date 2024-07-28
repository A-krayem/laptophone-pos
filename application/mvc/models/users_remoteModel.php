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
class users_remoteModel
{
    public function getAllUsersInStore($store_id)
    {
        $store_db_connection_store = self::get_store_connection($store_id);
        if ($store_db_connection_store) {
            $query = "select id,username,role_id,name from users";
            $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $store_db_connection_store));
            return $result;
        }
        return array();
    }
    public function get_user_info_by_connection($connecton, $user_id)
    {
        $query = "select id,username,role_id,name from users where id=" . $user_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $connecton));
        return $result;
    }
    public function get_store_connection($id)
    {
        $query_store = "select * from store where id=" . $id;
        $result_store = my_sql::fetch_assoc(my_sql::query($query_store));
        $st_host = $result_store[0]["ip_address"];
        $st_username = $result_store[0]["username"];
        $st_password = $result_store[0]["password"];
        $st_db = $result_store[0]["db"];
        $store_db_connection = mysqli_connect($st_host, $st_username, $st_password, $st_db);
        return $store_db_connection;
    }
}

?>