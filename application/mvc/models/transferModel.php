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
class transferModel
{
    public function get_item_transferred($item_id)
    {
        $query = "select id,to_store_id,from_store_id from transfers where id in (select transfer_id from transfers_details where item_id=" . $item_id . ")";
        if (isset($_SESSION["global_admin_exist"]) && $_SESSION["global_admin_exist"] == 1) {
            $query_primary = "select * from store where primary_db=1";
            $result_primary = my_sql::fetch_assoc(my_sql::query($query_primary));
            $primary_host = $result_primary[0]["ip_address"];
            $primary_username = $result_primary[0]["username"];
            $primary_password = $result_primary[0]["password"];
            $primary_db = $result_primary[0]["db"];
            error_reporting(0);
            $primary_db_connection = mysqli_connect($primary_host, $primary_username, $primary_password, $primary_db);
            if ($primary_db_connection) {
                mysqli_query($primary_db_connection, "SET NAMES utf8");
                $result = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $query));
            } else {
                return array();
            }
        } else {
            $result = my_sql::fetch_assoc(my_sql::query($query));
        }
        return $result;
    }
    public function getAllTransfers($info)
    {
        $query = "select * from transfers where deleted=0 and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function confirm_transfer($id, $current_store_id, $user_id)
    {
        $store_db_connection_warehouse = self::get_warehouse_connection();
        if ($store_db_connection_warehouse) {
            $query = "update transfers set confirmed_by_receiver_id=" . $user_id . ",confirmed_by_receiver_id_date='" . my_sql::datetime_now() . "' where id=" . $id . " and to_store_id=" . $current_store_id;
            my_sql::custom_connection_query($query, $store_db_connection_warehouse);
        }
    }
    public function get_warehouse_connection()
    {
        $query_warehouse = "select * from store where warehouse=1";
        $result_warehouse = my_sql::fetch_assoc(my_sql::query($query_warehouse));
        $wh_host = $result_warehouse[0]["ip_address"];
        $wh_username = $result_warehouse[0]["username"];
        $wh_password = $result_warehouse[0]["password"];
        $wh_db = $result_warehouse[0]["db"];
        $store_db_connection_warehouse = mysqli_connect($wh_host, $wh_username, $wh_password, $wh_db);
        return $store_db_connection_warehouse;
    }
    public function get_transfer_details($id)
    {
        $query = "select trs.id,trs.created_by,trs.creation_date,st.name as from_store,st_t.name as to_store,td.item_id,it.description as item_name,td.qty,sit.quantity as cqty,uc.name as color_name,us.name as size_name,trs.confirmed_by_receiver_id,trs.confirmed_by_receiver_id_date,td.unit_price as selling_price,it.item_group from transfers_details td left join transfers trs on trs.id=td.transfer_id left join store st on st.id=trs.from_store_id left join store st_t on st_t.id=trs.to_store_id left join items it on it.id=td.item_id left join store_items sit on it.id=sit.item_id left join unit_color uc on uc.id=it.color_text_id left join unit_size us on us.id=it.size_id where td.deleted=0  and trs.deleted=0 and td.transfer_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function show_to_confirm_transfer($id, $current_store_id)
    {
        $return = array();
        $return["transfer"] = array();
        $return["transfer_details"] = array();
        $store_db_connection_warehouse = self::get_warehouse_connection();
        if ($store_db_connection_warehouse) {
            $query_trs_details = "select trs.id,trs.creation_date,st.name as from_store,st_t.name as to_store,td.item_id,it.description as item_name,td.qty,sit.quantity as cqty,uc.name as color_name,us.name as size_name,trs.confirmed_by_receiver_id,trs.confirmed_by_receiver_id_date,td.unit_price,td.unit_price*td.qty as total_price from transfers_details td left join transfers trs on trs.id=td.transfer_id left join store st on st.id=trs.from_store_id left join store st_t on st_t.id=trs.to_store_id left join items it on it.id=td.item_id left join store_items sit on it.id=sit.item_id left join unit_color uc on uc.id=it.color_text_id left join unit_size us on us.id=it.size_id where td.deleted=0 and trs.to_store_id=" . $current_store_id . " and trs.synced_destination=1 and trs.synced_source=1 and trs.deleted=0 and td.transfer_id=" . $id;
            $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query_trs_details, $store_db_connection_warehouse));
            $return["transfer_details"] = $result;
            return $return;
        }
        return array();
    }
    public function transfer_pi($transfer_id, $pi_id)
    {
        $query = "select * from receive_stock where receive_stock_invoice_id=" . $pi_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            $query_ = "update transfers_details set deleted=1 where transfer_id=" . $transfer_id . " and item_id=" . $result[$i]["item_id"];
            my_sql::query($query_);
            $query_item = "select * from items where id=" . $result[$i]["item_id"];
            $result_item = my_sql::fetch_assoc(my_sql::query($query_item));
            $info = array();
            $info["transfer_id"] = $transfer_id;
            $info["item_id"] = $result[$i]["item_id"];
            $info["qty"] = $result[$i]["qty"];
            $info["selling_price"] = $result_item[0]["selling_price"];
            $info["buying_cost"] = $result_item[0]["buying_cost"];
            self::add_to_transfer_list($info);
        }
    }
    public function add_shortcut_to_transfer($transfer_id, $shortcut_id)
    {
        $query = "select * from shortcuts_details where shortcut_id=" . $shortcut_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            $query_ = "update transfers_details set deleted=1 where transfer_id=" . $transfer_id . " and item_id=" . $result[$i]["item_id"];
            my_sql::query($query_);
            $query_item = "select * from items where id=" . $result[$i]["item_id"];
            $result_item = my_sql::fetch_assoc(my_sql::query($query_item));
            $info = array();
            $info["transfer_id"] = $transfer_id;
            $info["item_id"] = $result[$i]["item_id"];
            $info["qty"] = $result[$i]["qty"];
            $info["selling_price"] = $result_item[0]["selling_price"];
            $info["buying_cost"] = $result_item[0]["buying_cost"];
            self::add_to_transfer_list($info);
        }
    }
    public function re_tr($id)
    {
        $query = "update transfers set synced_destination=0 where id=" . $id;
        my_sql::query($query);
    }
    public function delete_transfer($id)
    {
        $query = "update transfers set deleted=1 where id=" . $id . " and submit_transfer=0";
        my_sql::query($query);
        return my_sql::get_mysqli_rows_num();
    }
    public function qty_tr_minus($id)
    {
        $query = "update transfers_details set qty=qty-1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function qty_tr_plus($id)
    {
        $query = "update transfers_details set qty=qty+1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function update_tr_qty($id, $val)
    {
        $query = "update transfers_details set qty=" . $val . " where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_transfer_details_item($id)
    {
        $query = "update transfers_details set deleted=1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_all_items_in_transfer_list($transfer_id)
    {
        $query = "update transfers_details set deleted=1 where transfer_id=" . $transfer_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function set_source_as_synced($id)
    {
        $query = "update transfers set synced_source=1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function get_transfer_by_id($id)
    {
        $query = "select * from transfers where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsInTransferDetails($id)
    {
        $query = "select * from transfers_details where transfer_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function duplicate_transfer($info)
    {
        $query_d = "insert into transfers(description,creation_date,to_store_id,from_store_id,created_by) values('" . $info["transfer_description"] . "','" . my_sql::datetime_now() . "'," . $info["to_store_id"] . "," . $info["from_store_id"] . "," . $info["created_by"] . ")";
        my_sql::query($query_d);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        $query = "select * from transfers_details where transfer_id=" . $info["id_to_duplicate"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            my_sql::query("insert into transfers_details (transfer_id,item_id,added_date,qty,unit_price,unit_cost) values(" . $last_insert_id . "," . $result[$i]["item_id"] . ",'" . my_sql::datetime_now() . "'," . $result[$i]["qty"] . "," . $result[$i]["unit_price"] . "," . $result[$i]["unit_cost"] . ")");
        }
    }
    public function add_new_transfer($info)
    {
        $query = "insert into transfers(description,creation_date,to_store_id,from_store_id,created_by,pricing_type) values('" . $info["transfer_description"] . "','" . my_sql::datetime_now() . "'," . $info["to_store_id"] . "," . $info["from_store_id"] . "," . $info["created_by"] . "," . $info["pricing_type"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function get_total_amount_of_transfer_id($transfer_id)
    {
        $query = "select COALESCE(sum(unit_price*qty)) as total_price from transfers_details where deleted=0 and transfer_id=" . $transfer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["total_price"];
    }
    public function add_to_transfer_list($info)
    {
        $query = "insert into transfers_details(transfer_id,item_id,added_date,qty,unit_price,unit_cost) values('" . $info["transfer_id"] . "'," . $info["item_id"] . ",'" . my_sql::datetime_now() . "'," . $info["qty"] . "," . $info["selling_price"] . "," . $info["buying_cost"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function update_transfer_list_qty($info)
    {
        $query = "update transfers_details set qty=qty+" . $info["qty"] . " where transfer_id=" . $info["transfer_id"] . " and  item_id=" . $info["item_id"];
        my_sql::query($query);
    }
    public function get_all_transfer_list($transfer_id)
    {
        $query = "select * from transfers_details where transfer_id = " . $transfer_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_transfer($info)
    {
        $query = "update transfers set description='" . $info["transfer_description"] . "',to_store_id=" . $info["to_store_id"] . ",from_store_id=" . $info["from_store_id"] . " where id=" . $info["id_to_edit"] . " and submit_transfer=0";
        my_sql::query($query);
    }
    public function submit_transfer($id)
    {
        $query = "update transfers set submit_transfer=1 where id=" . $id;
        my_sql::query($query);
    }
    public function pos_stock_transfer($info)
    {
        $query_item = "select * from items where id=" . $info["trs_item_id"];
        $result_item = my_sql::fetch_assoc(my_sql::query($query_item));
        $query = "insert into transfers_new(status,creation_date,created_by,from_store_id,to_store_id,item_id,transfer_qty,unit_price,unit_cost) values(0,now()," . $info["by"] . "," . $info["from_store_id"] . "," . $info["to_store_id"] . "," . $info["trs_item_id"] . "," . $info["qty"] . "," . $result_item[0]["selling_price"] . "," . $result_item[0]["buying_cost"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function get_branch_transfer_by_cnx($cnx, $store_id, $daterange, $transfer_from, $transfer_to)
    {
        $filter_from = "";
        if (0 < $transfer_from) {
            $filter_from = " and from_store_id=" . $transfer_from;
        }
        $filter_to = "";
        if (0 < $transfer_to) {
            $filter_to = " and to_store_id=" . $transfer_to;
        }
        $query = "select ts.id as transfer_id,ts.creation_date,ts.from_store_id,ts.to_store_id,st.location as store_name,sto.location as store_name_to,ts.item_id,it.description,ts.unit_price,ts.unit_cost,ts.transfer_qty,us.name as size_name,uc.name as color_name,sti.quantity,ts.status,ts.confirmed_by,ts.cancelled_by,ts.confirmed_date,ts.cancelled_date  from transfers_new ts left join store st on st.id=ts.from_store_id left join store sto on sto.id=ts.to_store_id left join items it on it.id=ts.item_id left join unit_color uc on it.color_text_id=uc.id left join unit_size us on it.size_id=us.id left join store_items sti on sti.item_id=it.id  where  date(ts.creation_date)>='" . $daterange[0] . "' and  date(ts.creation_date)<='" . $daterange[1] . "' " . $filter_from . " " . $filter_to . "  and (ts.to_store_id = " . $store_id . " or  ts.from_store_id=" . $store_id . ")";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function get_transfer_branch_by_id($id)
    {
        $query = "select * from transfers_new where id = " . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_remote_transfer_branch_by_id($id, $cnx)
    {
        $query = "select * from transfers_new where id = " . $id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function excute_stock_transfer($id)
    {
        $query_transfer = "select * from transfers_new where id = " . $id;
        $result_transfer = my_sql::fetch_assoc(my_sql::query($query_transfer));
        if (0 < count($result_transfer) && 0 < my_sql::get_mysqli_rows_num()) {
            $query_from = "select * from store where id=" . $result_transfer[0]["from_store_id"];
            $result_from = my_sql::fetch_assoc(my_sql::query($query_from));
            $query_to = "select * from store where id=" . $result_transfer[0]["to_store_id"];
            $result_to = my_sql::fetch_assoc(my_sql::query($query_to));
            $from_host = $result_from[0]["ip_address"];
            $from_username = $result_from[0]["username"];
            $from_password = $result_from[0]["password"];
            $from_db = $result_from[0]["db"];
            $to_host = $result_to[0]["ip_address"];
            $to_username = $result_to[0]["username"];
            $to_password = $result_to[0]["password"];
            $to_db = $result_to[0]["db"];
            $store_db_connection_from = mysqli_connect($from_host, $from_username, $from_password, $from_db);
            $store_db_connection_to = mysqli_connect($to_host, $to_username, $to_password, $to_db);
            if ($store_db_connection_from && $store_db_connection_to) {
                mysqli_query($store_db_connection_to, "SET NAMES utf8");
                mysqli_query($store_db_connection_from, "SET NAMES utf8");
                $query_from = "update store_items set quantity=quantity-" . $result_transfer[0]["transfer_qty"] . " where item_id=" . $result_transfer[0]["item_id"] . " and store_id=" . $result_transfer[0]["from_store_id"];
                my_sql::custom_connection_query($query_from, $store_db_connection_from);
                $query_last = "select * from store_items where item_id=" . $result_transfer[0]["item_id"];
                $result_last = my_sql::fetch_assoc(my_sql::custom_connection_query($query_last, $store_db_connection_from));
                my_sql::custom_connection_query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source,is_pos_transfer) values(0," . $result_transfer[0]["item_id"] . ",'" . my_sql::datetime_now() . "',-" . $result_transfer[0]["transfer_qty"] . "," . $result_transfer[0]["from_store_id"] . "," . $result_last[0]["quantity"] . ",'POSTRS_" . $result_transfer[0]["id"] . "',1)", $store_db_connection_from);
                my_sql::custom_connection_query("update transfers_new set status=1 where id=" . $id, $store_db_connection_from);
                return true;
            }
        }
        return false;
    }
    public function confirm_branch_transfer($transfer_id, $connection)
    {
        if ($connection) {
            $query_transfer_info = "select * from transfers_new where id = " . $transfer_id;
            $result_transfer_info = my_sql::fetch_assoc(my_sql::custom_connection_query($query_transfer_info, $connection));
            $query_to_stock = "select * from store_items where item_id=" . $result_transfer_info[0]["item_id"];
            $result_to_stock = my_sql::fetch_assoc(my_sql::query($query_to_stock));
            if (0 < count($result_transfer_info)) {
                $query = "update transfers_new set status=3,confirmed_by=" . $_SESSION["id"] . ",confirmed_date=now() where id=" . $transfer_id . " and status=1";
                my_sql::custom_connection_query($query, $connection);
                if (0 < my_sql::get_mysqli_rows_num_remote($connection)) {
                    $query_qty = "update store_items set quantity=quantity+" . $result_transfer_info[0]["transfer_qty"] . " where item_id=" . $result_transfer_info[0]["item_id"] . " and store_id=" . $result_transfer_info[0]["to_store_id"];
                    my_sql::query($query_qty);
                    my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source,is_pos_transfer) values(0," . $result_transfer_info[0]["item_id"] . ",'" . my_sql::datetime_now() . "'," . $result_transfer_info[0]["transfer_qty"] . "," . $result_transfer_info[0]["to_store_id"] . "," . ($result_to_stock[0]["quantity"] + $result_transfer_info[0]["transfer_qty"]) . ",'POSTRS_" . $transfer_id . "',1)");
                }
            }
        }
    }
    public function cancel_branch_transfer($transfer_id)
    {
        $query_transfer_info = "select * from transfers_new where id = " . $transfer_id;
        $result_transfer_info = my_sql::fetch_assoc(my_sql::query($query_transfer_info));
        if (0 < count($result_transfer_info)) {
            $query = "update transfers_new set status=5,cancelled_by=" . $_SESSION["id"] . ",cancelled_date=now() where id=" . $transfer_id . " and status=1";
            my_sql::query($query);
            if (0 < my_sql::get_mysqli_rows_num()) {
                $query_last = "select * from store_items where item_id=" . $result_transfer_info[0]["item_id"];
                $result_last = my_sql::fetch_assoc(my_sql::query($query_last));
                $query_qty = "update store_items set quantity=quantity+" . $result_transfer_info[0]["transfer_qty"] . " where item_id=" . $result_transfer_info[0]["item_id"] . " and store_id=" . $result_transfer_info[0]["from_store_id"];
                my_sql::query($query_qty);
                my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source,is_pos_transfer) values(0," . $result_transfer_info[0]["item_id"] . ",'" . my_sql::datetime_now() . "'," . $result_transfer_info[0]["transfer_qty"] . "," . $result_transfer_info[0]["from_store_id"] . "," . ($result_last[0]["quantity"] + $result_transfer_info[0]["transfer_qty"]) . ",'POSTRS_C_" . $transfer_id . "',1)");
            }
        }
    }
}

?>