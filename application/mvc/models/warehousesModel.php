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
class warehousesModel
{
    public function getAllWarehouses()
    {
        $query = "select * from warehouse where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_warehouse($info)
    {
        $query = "insert into warehouse(location) values('" . $info["warehouse_desc"] . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_warehouse($id)
    {
        $query = "select * from warehouse where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_warehouse($info)
    {
        $query = "update warehouse set location='" . $info["warehouse_desc"] . "' where id=" . $info["id_to_edit"];
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_warehouse($id)
    {
        $query = "update warehouse set deleted = 1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
}

?>