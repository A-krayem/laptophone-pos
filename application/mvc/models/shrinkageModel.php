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
class shrinkageModel
{
    public function getAllShrinkages()
    {
        $query = "select * from shrinkages where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllShrinkagesDetails($id)
    {
        $query = "select count(id) as num from shrinkages_details where shrinkages_id=" . $id . " and excluded=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function failed_shrinkage($shrinkage_id)
    {
        $query = "select item_barcode,count(item_barcode) as num from shrinkage_failed where shrinkage_id=" . $shrinkage_id . " group by item_barcode";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_scanner_qty_bulk($query)
    {
        my_sql::query($query);
    }
    public function reset_scanner_qty($id)
    {
        my_sql::query("update shrinkages_details set scanner_qty=0 where shrinkages_id=" . $id);
    }
    public function reset_failed_scanner($id)
    {
        my_sql::query("delete from shrinkage_failed where shrinkage_id=" . $id);
    }
    public function update_total_rows_in_excel($id, $nb)
    {
        my_sql::query("update shrinkages set excel_total_rows_nb=" . $nb . " where id=" . $id);
    }
    public function add_failed_scanner($barcode, $id)
    {
        my_sql::query("insert into shrinkage_failed (shrinkage_id,item_barcode) values (" . $id . ",'" . $barcode . "')");
    }
    public function update_file_name($id, $new_file_name)
    {
        my_sql::query("update shrinkages set excel_name='" . $new_file_name . "' where id=" . $id);
    }
    public function update_total_scanner_qty_sucsess($id)
    {
        $query = "SELECT sum(scanner_qty) as total FROM shrinkages_details where shrinkages_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $total = 0;
        if (0 < count($result)) {
            $total = $result[0]["total"];
        }
        my_sql::query("update shrinkages set scanner_qty_success='" . $total . "' where id=" . $id);
    }
    public function update_scanner_qty($barcodes, $id)
    {
        $query = "update shrinkages_details set scanner_qty=scanner_qty+1 where shrinkages_id=" . $id . " and item_id in (select id from items where TRIM(LEADING '0' FROM barcode) in ('" . $barcodes . "')) ";
        my_sql::query($query);
        if (my_sql::get_mysqli_rows_num() == 0) {
            my_sql::query("insert into shrinkage_failed (shrinkage_id,item_barcode) values (" . $id . ",'" . $barcodes . "')");
        }
    }
    public function exclude_items_in_shrinkage($barcodes, $id)
    {
        my_sql::query("update shrinkages_details set excluded=0,checked_date=NULL,avg_cost=0 where shrinkages_id=" . $id);
        $query = "update shrinkages_details set excluded=1 where shrinkages_id=" . $id . " and item_id not in (select id from items where barcode in (" . $barcodes . "))";
        my_sql::query($query);
    }
    public function get_total_lost($id)
    {
        $query = "SELECT sum((old_stock_qty-new_stock_qty)*avg_cost) as total_lost FROM shrinkages_details where old_stock_qty>new_stock_qty and shrinkages_id=" . $id . " and excluded=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllShrinkagesDetailsCompared($id)
    {
        $query = "select count(id) as num from shrinkages_details where shrinkages_id=" . $id . " and excluded=0 and checked_date is not null";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllShrinkagesDetailsNotCompared($id)
    {
        $query = "select count(id) as num from shrinkages_details where shrinkages_id=" . $id . " and excluded=0 and checked_date is null";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getShrinkageById($id)
    {
        $query = "select * from shrinkages where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getShrinkageDetailsById($info)
    {
        $group_id_query = "";
        if ($info["group_id"] == 0) {
            $group_id_query = "";
        }
        if ($info["group_id"] == 1) {
            $group_id_query = " and checked_date is not null ";
        }
        if ($info["group_id"] == 2) {
            $group_id_query = " and checked_date is null ";
        }
        $supplier_id_query = "";
        if (0 < $info["supplier_id"]) {
            $supplier_id_query .= " and item_id in (select id from items where supplier_reference=" . $info["supplier_id"] . ") ";
        }
        $subcategory_id_query = "";
        if (0 < $info["subcategory_id"]) {
            $subcategory_id_query .= " and item_id in (select id from items where item_category=" . $info["subcategory_id"] . ") ";
        }
        $query = "select * from shrinkages_details where shrinkages_id=" . $info["id"] . " and excluded=0 " . $group_id_query . $supplier_id_query . $subcategory_id_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getShrinkageDetailsByDetailsId($id)
    {
        $query = "select * from shrinkages_details where id=" . $id . " and excluded=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function sync_shrinkage_with_stock($id)
    {
        $shrinkage_info = self::getShrinkageById($id);
        $query = "select * from store_items where store_id=" . $shrinkage_info[0]["store_id"] . " ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query_sh = "select count(id) as num from shrinkages_details where shrinkages_id=" . $id;
        $result_sh = my_sql::fetch_assoc(my_sql::query($query_sh));
        if (0 < $result_sh[0]["num"]) {
            return NULL;
        }
        $query_shr_insert = "insert into shrinkages_details(shrinkages_id,item_id,old_stock_qty,new_stock_qty) VALUES";
        for ($i = 0; $i < count($result); $i++) {
            if ($i == count($result) - 1) {
                $query_shr_insert .= "(" . $id . "," . $result[$i]["item_id"] . "," . $result[$i]["quantity"] . ",0);";
            } else {
                $query_shr_insert .= "(" . $id . "," . $result[$i]["item_id"] . "," . $result[$i]["quantity"] . ",0),";
            }
        }
        my_sql::query($query_shr_insert);
    }
    public function get_shrinkage_by_id($id)
    {
        $query = "select * from shrinkages where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function change_qty_sh($id, $qty, $cost)
    {
        $query = "update shrinkages_details set checked_date='" . my_sql::datetime_now() . "',new_stock_qty=" . $qty . ",avg_cost='" . $cost . "' where id=" . $id;
        my_sql::query($query);
    }
    public function add_new_shrinkage($info)
    {
        $query = "insert into shrinkages (creation_date,description,deleted,store_id) values('" . my_sql::datetime_now() . "','" . $info["shrinkage_description"] . "',0," . $info["stores_id"] . ")";
        my_sql::query($query);
    }
    public function update_shrinkage($info)
    {
        $query = "update shrinkages set description='" . $info["shrinkage_description"] . "',store_id='" . $info["stores_id"] . "' where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function delete_shrinkage($id)
    {
        $query = "update shrinkages set deleted=1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function getShrinkageDetailsById_New($info)
    {
        $group_id_query = "";
        if ($info["group_id"] == 0) {
            $group_id_query = "";
        }
        if ($info["group_id"] == 1) {
            $group_id_query = " and checked_date is not null ";
        }
        if ($info["group_id"] == 2) {
            $group_id_query = " and checked_date is null ";
        }
        $supplier_id_query = "";
        if (0 < $info["supplier_id"]) {
            $supplier_id_query .= " and item_id in (select id from items where supplier_reference=" . $info["supplier_id"] . ") ";
        }
        $subcategory_id_query = "";
        if (0 < count($info["subcategory_id"])) {
            $subcategory_id_query .= " and item_id in (select id from items where item_category in (" . implode(",", $info["subcategory_id"]) . ")) ";
        }
        if (count($info["subcategory_id"]) == 1 && $info["subcategory_id"][0] == 0) {
            $subcategory_id_query = "";
        }
        $qty_info_query = "";
        if ($info["qty_info"] == 1) {
            $qty_info_query = " and old_stock_qty !=scanner_qty ";
        } else {
            if ($info["qty_info"] == 2) {
                $qty_info_query = " and old_stock_qty !=scanner_qty and old_stock_qty>0 ";
            }
        }
        $query = "select * from shrinkages_details where shrinkages_id=" . $info["id"] . " and excluded=0 " . $group_id_query . $supplier_id_query . $subcategory_id_query . $qty_info_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>