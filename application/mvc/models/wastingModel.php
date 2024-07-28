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
class wastingModel
{
    public function getAllTypes()
    {
        $query = "select * from wasting_types where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_wasting_items()
    {
        $query = "select * from wasting where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_wasting_items_not_cleared()
    {
        $query = "select * from wasting where deleted=0 and clear=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_wasting_items_filtration($filter)
    {
        $vendor_filter = "";
        if (0 < $filter["vendor"]) {
            $vendor_filter = " and user_id=" . $filter["vendor"] . " ";
        }
        $date_filter = " and date(creation_date)>='" . $filter["date"][0] . "' and date(creation_date)<='" . $filter["date"][1] . "' ";
        $query = "select * from wasting where deleted=0 " . $vendor_filter . " " . $date_filter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_wasting_by_id($id)
    {
        $query = "select * from wasting where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_wasting_of_item_id($item_id)
    {
        $query = "select * from wasting where deleted=0 and item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_wasting($info)
    {
        $query = "select COALESCE(sum(cost), 0) as sum from wasting where deleted=0 and date(creation_date)>=date('" . $info["start_date"] . "') and date(creation_date)<=date('" . $info["end_date"] . "')";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_wasting_remote($info, $cnx)
    {
        $query = "select COALESCE(sum(cost), 0) as sum from wasting where deleted=0 and date(creation_date)>=date('" . $info["start_date"] . "') and date(creation_date)<=date('" . $info["end_date"] . "')";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function add($info)
    {
        $cashbox_id = 0;
        if (isset($_SESSION["cashbox_id"])) {
            $cashbox_id = $_SESSION["cashbox_id"];
        }
        $query = "insert into wasting(item_id,creation_date,user_id,cost,type,note,cashbox_id,qty,price) values(" . $info["item_id"] . ",'" . my_sql::datetime_now() . "'," . $info["user_id"] . "," . $info["cost"] . "," . $info["type"] . ",'" . $info["note"] . "'," . $cashbox_id . "," . $info["qty"] . "," . $info["price"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function delete($id)
    {
        my_sql::query("update wasting set deleted=1 where id=" . $id);
    }
    public function wasting_clear()
    {
        my_sql::query("update wasting set clear=1 where clear=0 and cashbox_id=" . $_SESSION["cashbox_id"]);
    }
    public function update($info)
    {
        $query = "update wasting set item_id=" . $info["item_id"] . ",type=" . $info["type"] . ",note='" . $info["note"] . "' where id=" . $info["id"];
        my_sql::query($query);
    }
}

?>