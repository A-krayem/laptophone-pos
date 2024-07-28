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
class sizesModel
{
    public function getSizes()
    {
        $query = "select * from unit_size order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSizesOnlyAvailable()
    {
        $query = "select * from unit_size where deleted=0 order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function checkSize($size)
    {
        $query = "select * from unit_size where lower(name)='" . strtolower($size) . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_size($info)
    {
        $query = "insert into unit_size(name) values('" . $info["size_name"] . "')";
        my_sql::query($query);
        $mysqli_insert_id = 0;
        if (0 < my_sql::get_mysqli_rows_num()) {
            $mysqli_insert_id = my_sql::get_mysqli_insert_id();
            my_sql::global_query_sync("insert into unit_size(id,name) values(" . $mysqli_insert_id . ",'" . $info["size_name"] . "')");
        }
        return $mysqli_insert_id;
    }
}

?>