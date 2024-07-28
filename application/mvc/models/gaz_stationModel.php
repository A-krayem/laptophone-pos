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
class gaz_stationModel
{
    public function add_gun($info)
    {
        $query = "insert into gaz_station(name,item_id,starting_counter,creation_date,created_by,max_stock) values('" . $info["gun_name"] . "','" . $info["search_item"] . "','" . $info["gun_st_counter"] . "','" . my_sql::datetime_now() . "','" . $info["created_by"] . "','" . $info["max_stock"] . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function get_all()
    {
        $query = "select * from gaz_station where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_gun_new_counter($info)
    {
        $query = "insert into gaz_station_counter (gaz_station_id,old_counter,new_counter,creation_date,created_by,invoice_id,debt,cash,available_stock) values(" . $info["gaz_station_id"] . "," . $info["old_counter"] . "," . $info["new_counter"] . "," . "'" . my_sql::datetime_now() . "'," . $_SESSION["id"] . "," . "0," . $info["total_debt"] . "," . $info["total_cash"] . "," . $info["stock"] . ")";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function delete_counter($id)
    {
        $query = "update gaz_station_counter set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function delete_disp_gun($id)
    {
        $query = "update gaz_station set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function update_invoice_id($counter_id, $invoice_id)
    {
        $query = "update gaz_station_counter set invoice_id=" . $invoice_id . " where id=" . $counter_id;
        my_sql::query($query);
    }
    public function get_all_counters($id)
    {
        $query = "select * from gaz_station_counter where gaz_station_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_set_counter_details_details($id)
    {
        $query = "select * from gaz_station_counter where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_gaz_station_details($id)
    {
        $query = "select * from gaz_station where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_debt_qty($item_id, $old_counter_date, $new_counter_date)
    {
        $query = "select COALESCE(sum(qty), 0) as sum from invoice_items where item_id=" . $item_id . " and deleted=0 and invoice_id in (select id from invoices where station_generated=0 and creation_date>='" . $old_counter_date . "' and creation_date<='" . $new_counter_date . "' )";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_last_counter_info($gaz_station_id)
    {
        $to_return = array();
        $query = "select * from gaz_station_counter where gaz_station_id=" . $gaz_station_id . " and deleted=0 order by creation_date desc limit 1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $to_return["new_counter"] = $result[0]["new_counter"];
            $to_return["creation_date"] = $result[0]["creation_date"];
            return $to_return;
        }
        $query_ = "select * from gaz_station where id=" . $gaz_station_id . " and deleted=0";
        $result_ = my_sql::fetch_assoc(my_sql::query($query_));
        if (0 < count($result_)) {
            $to_return["new_counter"] = $result_[0]["starting_counter"];
            $to_return["creation_date"] = $result_[0]["creation_date"];
            return $to_return;
        }
    }
}

?>