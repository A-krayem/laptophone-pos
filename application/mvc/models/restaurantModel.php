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
class restaurantModel
{
    public function getAllTables()
    {
        $query = "select * from restaurant_tables where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_table($info)
    {
        $query = "insert into restaurant_tables(description,table_number) values('" . $info["tab_desc"] . "'," . $info["tab_nb"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function opentable($info)
    {
        $query = "insert into restaurant_tables_active(table_id,start_date,opened_by_vendor_id) values(" . $info["table_id"] . ",'" . my_sql::datetime_now() . "'," . $info["vendor_id"] . ")";
        my_sql::query($query);
    }
    public function get_table($id)
    {
        $query = "select * from restaurant_tables where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_table($id)
    {
        $query = "update restaurant_tables set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function update_table($info)
    {
        $query = "update restaurant_tables set description='" . $info["tab_desc"] . "',table_number=" . $info["tab_nb"] . " where id=" . $info["id_to_edit"];
        $result = my_sql::query($query);
        return $result;
    }
    public function getAllOpenedTables()
    {
        $query = "select id,table_id from restaurant_tables_active where status=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>