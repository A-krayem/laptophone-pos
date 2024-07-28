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
class colorsModel
{
    public function getColorsText()
    {
        $query = "select * from unit_color";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getColorsTextOnlyAvailable()
    {
        $query = "select * from unit_color where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function checkColor($color)
    {
        $query = "select * from unit_color where lower(name)='" . strtolower($color) . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_color($info)
    {
        $query = "insert into unit_color(name) values('" . $info["color_name"] . "')";
        my_sql::query($query);
        $mysqli_insert_id = 0;
        if (0 < my_sql::get_mysqli_rows_num()) {
            $mysqli_insert_id = my_sql::get_mysqli_insert_id();
            my_sql::global_query_sync("insert into unit_color(id,name) values(" . $mysqli_insert_id . ",'" . $info["color_name"] . "')");
        }
        return $mysqli_insert_id;
    }
}

?>