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
class branchModel
{
    public function add_branch($info)
    {
        $query = "insert into branches(branch_name,location_name,creation_date,created_by) " . "values('" . $info["branch_name"] . "','" . $info["location_name"] . "','" . my_sql::datetime_now() . "','" . $info["created_by"] . "')";
        my_sql::query($query);
    }
    public function get_branches_stock($stock_id)
    {
        $query = "select * from branches_stock where branch_id=" . $stock_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_stock_value_branch($branch_id)
    {
        $query = "select COALESCE(sum(it.buying_cost*bs.qty), 0) as sv  from branches_stock bs left join items it on it.id=bs.item_id where bs.branch_id=" . $branch_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sv"];
    }
    public function submit_transfer_branch($info)
    {
        $current_qty_from = 0;
        $current_qty_to = 0;
        if (0 < $info["from_branch"]) {
            $query = "select * from branches_stock where branch_id=" . $info["from_branch"] . " and item_id=" . $info["item_id"];
            $result = my_sql::fetch_assoc(my_sql::query($query));
            if (count($result) == 0) {
                my_sql::query("insert into branches_stock (branch_id,item_id,qty) values(" . $info["from_branch"] . "," . $info["item_id"] . "," . $info["qty_to_transfer"] . ")");
            } else {
                $current_qty_from = $result[0]["qty"];
                my_sql::query("update branches_stock set qty=qty-" . $info["qty_to_transfer"] . " where branch_id=" . $info["from_branch"] . " and item_id=" . $info["item_id"]);
            }
        }
        if (0 < $info["to_branch"]) {
            $query = "select * from branches_stock where branch_id=" . $info["to_branch"] . " and item_id=" . $info["item_id"];
            $result = my_sql::fetch_assoc(my_sql::query($query));
            if (count($result) == 0) {
                my_sql::query("insert into branches_stock (branch_id,item_id,qty) values(" . $info["to_branch"] . "," . $info["item_id"] . "," . $info["qty_to_transfer"] . ")");
            } else {
                $current_qty_to = $result[0]["qty"];
                my_sql::query("update branches_stock set qty=qty+" . $info["qty_to_transfer"] . " where branch_id=" . $info["to_branch"] . " and item_id=" . $info["item_id"]);
            }
        }
        $updated_stock_from = 0 - $info["qty_to_transfer"] + $current_qty_from;
        $updated_stock_to = $info["qty_to_transfer"] + $current_qty_to;
        if ($info["from_branch"] == 0) {
            $q = "select quantity from store_items where item_id=" . $info["item_id"];
            $result = my_sql::fetch_assoc(my_sql::query($q));
            if (0 < count($result)) {
                $updated_stock_from = $result[0]["quantity"];
            }
        }
        if ($info["to_branch"] == 0) {
            $q = "select quantity from store_items where item_id=" . $info["item_id"];
            $result = my_sql::fetch_assoc(my_sql::query($q));
            if (0 < count($result)) {
                $updated_stock_to = $result[0]["quantity"];
            }
        }
        $query = "insert into branches_stock_logs (creation_date,from_branch_id,to_branch_id,qty,item_id,action_type,updated_stock_from,updated_stock_to) values('" . my_sql::datetime_now() . "'," . $info["from_branch"] . "," . $info["to_branch"] . "," . $info["qty_to_transfer"] . "," . $info["item_id"] . ",0," . $updated_stock_from . "," . $updated_stock_to . ")";
        my_sql::query($query);
    }
    public function get_branches_stock_item_id($stock_id, $item_id)
    {
        $query = "select * from branches_stock where branch_id=" . $stock_id . " and item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return $result[0]["qty"];
        }
        return 0;
    }
    public function tot_qty($stock_id)
    {
        $query = "select COALESCE(sum(qty), 0) as sum from branches_stock where branch_id=" . $stock_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getAllItems($store_id, $branch_id)
    {
        $query = "select it.id,it.description,it.barcode,it.buying_cost,it.vat,it.selling_price,it.discount,bs.qty as quantity,it.size_id,it.color_text_id from items it left join store_items si on si.item_id=it.id left join branches_stock bs on bs.item_id=it.id where bs.branch_id=" . $branch_id . " and it.id=si.item_id and si.store_id=" . $store_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_my_access_branches()
    {
        $return = array();
        $query = "select new_branches_permission from users where id=" . $_SESSION["id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            if ($result[0]["new_branches_permission"] == NULL || $result[0]["new_branches_permission"] == "") {
                return array();
            }
            $tmp = explode(",", $result[0]["new_branches_permission"]);
            for ($i = 0; $i < count($tmp); $i++) {
                array_push($return, (int) $tmp[$i]);
            }
        }
        return $return;
    }
    public function get_branches()
    {
        $query = "select * from branches where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_branches_limited()
    {
        $query = "select id,branch_name,location_name from branches where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_branches_even_deleted()
    {
        $query = "select * from branches";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function deleted_branch($id)
    {
        my_sql::query("update branches set deleted=1 where id=" . $id);
        if (0 < my_sql::get_mysqli_rows_num()) {
            return 1;
        }
    }
}

?>