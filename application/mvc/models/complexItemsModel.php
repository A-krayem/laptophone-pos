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
class complexItemsModel
{
    public function get($complex_item_id)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        return $result[0];
    }
    public function get_item_in_complex_items($complex_item_id)
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where deleted=0 and complex_item_id=" . $complex_item_id));
    }
    public function complex_items_production_availability()
    {
        $return = array();
        $cstock = array();
        $current_stock = my_sql::fetch_assoc(my_sql::query("SELECT item_id,quantity FROM store_items where item_id in (SELECT item_id FROM complex_item_details where deleted=0)"));
        for ($i = 0; $i < count($current_stock); $i++) {
            $cstock[$current_stock[$i]["item_id"]] = $current_stock[$i]["quantity"];
        }
        $complex_items = my_sql::fetch_assoc(my_sql::query("SELECT id FROM complex_items where deleted=0"));
        for ($i = 0; $i < count($complex_items); $i++) {
            $complex_items_details = my_sql::fetch_assoc(my_sql::query("SELECT item_id,qty FROM complex_item_details where deleted=0 and complex_item_id=" . $complex_items[$i]["id"]));
            $availibility = 10000000000000.0;
            for ($j = 0; $j < count($complex_items_details); $j++) {
                if (0 < $complex_items_details[$j]["qty"]) {
                    $av = $cstock[$complex_items_details[$j]["item_id"]] / $complex_items_details[$j]["qty"];
                    if ($av < $availibility) {
                        $availibility = $av;
                    }
                } else {
                    $availibility = 0;
                }
            }
            $return[$complex_items[$i]["id"]] = $availibility;
        }
        return $return;
    }
    public function getAllComplexItemsFiltered($filters)
    {
        $condition = "";
        if ($filters["type"]) {
            $condition .= " and complex_items_type=" . $filters["type"] . " ";
        }
        if ($filters["date_range"]) {
            $condition .= " and date(complex_items.creation_date)>='" . $filters["date_range"][0] . "' and date(complex_items.creation_date)<='" . $filters["date_range"][1] . "' ";
        }
        $condition .= " and complex_items.complex_items_type=" . $filters["type"];
        if ($filters["user"]) {
            $condition .= " and complex_items.created_by=" . $filters["user"] . " ";
        }
        if ($filters["status"]) {
            if ($filters["status"] == 1) {
                $condition .= " and complex_items.deleted=1 ";
            }
        } else {
            $condition .= " and complex_items.deleted=0 ";
        }
        if ($filters["items"]) {
            foreach ($filters["items"] as $item) {
                $condition .= " and complex_items.id in (SELECT complex_item_id from complex_item_details where item_id=" . $item . " )";
            }
        }
        $query = "SELECT complex_items.*,users.username,(SELECT count(*) from complex_item_details where complex_item_id =complex_items.id and deleted=0) as items_count from complex_items left join users on users.id=complex_items.created_by where 1 " . $condition;
        return my_sql::fetch_assoc(my_sql::query($query));
    }
    public function duplicateCI($complex_item_id)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        $previous_complex_item = $result[0];
        $ci_items = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        $possibleComplexItemCols = array("complex_items_type", "sub_total", "cost", "discount", "total", "profit", "note", "name", "barcode");
        $resultInsert = array();
        foreach ($possibleComplexItemCols as $possibleRow) {
            $resultInsert[$possibleRow] = $previous_complex_item[$possibleRow];
        }
        $query = "INSERT into complex_items (" . implode(",", array_keys($resultInsert)) . ",created_by,creation_date) values ('" . implode("','", $resultInsert) . "'," . $_SESSION["id"] . ",NOW())";
        my_sql::query($query);
        $newComplexItemId = my_sql::get_mysqli_insert_id();
        if (0 < $newComplexItemId) {
            my_sql::global_query_sync($query);
        }
        $possibleCIItemCols = array("item_id", "additional_description", "buying_cost", "qty", "selling_price", "final_price", "final_cost", "profit", "deleted");
        $resultInsert = array();
        foreach ($ci_items as $rowKey => $ci_item) {
            $resultInsert[$rowKey]["complex_item_id"] = $newComplexItemId;
            foreach ($possibleCIItemCols as $col) {
                $resultInsert[$rowKey][$col] = $ci_item[$col];
            }
            $query = "INSERT into complex_item_details (" . implode(",", array_keys($resultInsert[$rowKey])) . ") values('" . implode("','", $resultInsert[$rowKey]) . "')";
            my_sql::query($query);
            $last_id = my_sql::get_mysqli_insert_id();
            if (0 < $last_id) {
                my_sql::global_query_sync($query);
            }
        }
        $res = my_sql::fetch_assoc(my_sql::query("SELECT *  FROM complex_items where id=" . $newComplexItemId));
        $new["item"] = $res[0];
        $new["details"] = my_sql::fetch_assoc(my_sql::query("SELECT *  from complex_item_details where complex_item_id=" . $newComplexItemId));
        $new["duplicate_from"] = $complex_item_id;
        self::log_complex_item($newComplexItemId, NULL, $new, array("complex_item_id" => $complex_item_id), 1);
        self::createItemFromCI($newComplexItemId);
        self::updateItemFromCI($newComplexItemId);
        return $newComplexItemId;
    }
    public function log_complex_item($complex_item_id, $old, $new, $request_data, $logType)
    {
        $data = json_encode(array("old" => $old, "new" => $new, "request_data" => $request_data));
        $query = "INSERT into complex_item_log (complex_item_id,creation_date,created_by,log_type,data) values(" . $complex_item_id . ",now()," . $_SESSION["id"] . "," . $logType . ",'" . $data . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        if (0 < $last_id) {
            my_sql::global_query_sync($query);
        }
    }
    public function generateEmpty($type)
    {
        $query = "insert into complex_items(complex_items_type,created_by,creation_date,deleted) values (" . $type . "," . $_SESSION["id"] . ",now(),0)";
        my_sql::query($query);
        $complex_item_id = my_sql::get_mysqli_insert_id();
        if (0 < $complex_item_id) {
            my_sql::global_query_sync($query);
        }
        $res = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        $new["item"] = $res[0];
        $new["details"] = array();
        self::log_complex_item($complex_item_id, NULL, $new, array("type" => $type), 1);
        self::createItemFromCI($complex_item_id);
        return $complex_item_id;
    }
    public function createItemFromCI($complex_item_id)
    {
        $query = "INSERT into items(description,buying_cost,selling_price,creation_date,complex_item_id) values('',0,0,now()," . $complex_item_id . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        if (0 < $last_insert_id) {
            my_sql::global_query_sync($query);
        }
        $query_store = "INSERT into store_items(store_id,item_id,quantity) values(" . $_SESSION["store_id"] . "," . $last_insert_id . ",0)";
        my_sql::query($query_store);
        $last_id = my_sql::get_mysqli_insert_id();
        if (0 < $last_id) {
            my_sql::global_query_sync($query);
        }
        self::updateItemFromCI($complex_item_id);
    }
    public function updateItemFromCI($complex_item_id)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id =" . $complex_item_id));
        $CI = $result[0];
        $query = "UPDATE items set description ='" . $CI["name"] . "',barcode ='" . $CI["barcode"] . "',selling_price ='" . $CI["total"] . "',buying_cost ='" . $CI["cost"] . "',deleted ='" . $CI["deleted"] . "',item_category='" . $CI["subcategory_id"] . "' where complex_item_id=" . $complex_item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function getItems($complex_item_id)
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT complex_item_details.*,items.barcode,items.description FROM complex_item_details left join items on items.id=complex_item_details.item_id where  complex_item_details.deleted=0 and complex_item_details.complex_item_id=" . $complex_item_id));
    }
    public function addItems($complex_item_id, $item_id)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM items where id=" . $item_id));
        $item = $result[0];
        $result_ = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        $old["item"] = $result_[0];
        $old["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        $query = "INSERT into complex_item_details (complex_item_id,item_id,additional_description,buying_cost,qty,selling_price,final_price,final_cost,profit,deleted) \n        values (" . $complex_item_id . "," . $item_id . ",'','" . $item["buying_cost"] . "',1,'" . $item["selling_price"] . "','" . $item["selling_price"] . "','" . $item["buying_cost"] . "','" . ($item["selling_price"] - $item["buying_cost"]) . "',0)";
        my_sql::query($query);
        $ci_item_id = my_sql::get_mysqli_insert_id();
        if (0 < $ci_item_id) {
            my_sql::global_query_sync($query);
        }
        self::autoUpdateCIData($complex_item_id);
        $res = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        $new["item"] = $res[0];
        $new["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        self::log_complex_item($complex_item_id, $old, $new, array("ci_item_id" => $ci_item_id, "item_id" => $item_id, "command" => "add_item"), 2);
        return $ci_item_id;
    }
    public function updateCIItemData($ci_item_id, $data)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM items where id=(SELECT item_id from complex_item_details where id=" . $ci_item_id . ")"));
        $item = $result[0];
        $res = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=(SELECT complex_item_id from complex_item_details where id=" . $ci_item_id . ")"));
        $old["item"] = $res[0];
        $complex_item_id = $old["item"]["id"];
        $old["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        $query = "UPDATE complex_item_details set additional_description='" . $data["description"] . "',buying_cost='" . $item["buying_cost"] . "',qty='" . $data["qty"] . "',selling_price='" . $data["price"] . "',final_price='" . $data["qty"] * $data["price"] . "',final_cost='" . $data["qty"] * $item["buying_cost"] . "',profit='" . ($data["qty"] * $data["price"] - $data["qty"] * $item["buying_cost"]) . "' where id =" . $ci_item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        $res_ = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where id =" . $ci_item_id));
        $ci_item = $res_[0];
        self::autoUpdateCIData($ci_item["complex_item_id"]);
        $res_1 = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        $new["item"] = $res_1[0];
        $new["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        self::log_complex_item($complex_item_id, $old, $new, array("data" => $data, "command" => "update_ci_item", "ci_item_id" => $ci_item_id), 2);
        return $ci_item;
    }
    public function autoUpdateCIData($complex_item_id)
    {
        $query = "UPDATE complex_items set sub_total=(select sum(final_price)  from complex_item_details where complex_item_id=" . $complex_item_id . " and deleted=0),cost=(select sum(final_cost)  from complex_item_details where complex_item_id=" . $complex_item_id . " and deleted=0), total=(select sum(final_price)  from complex_item_details where complex_item_id=" . $complex_item_id . " and deleted=0)-discount,profit=(select sum(profit)  from complex_item_details where complex_item_id=" . $complex_item_id . " and deleted=0)-discount where complex_items.id=" . $complex_item_id;
        self::updateItemFromCI($complex_item_id);
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function deleteCIItem($ci_item_id)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id= (SELECT complex_item_id from complex_item_details where id=" . $ci_item_id . ")"));
        $old["item"] = $result[0];
        $complex_item_id = $old["item"]["id"];
        $old["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        $query = "UPDATE complex_item_details set deleted=1 where id =" . $ci_item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        self::autoUpdateCIData($old["item"]["id"]);
        $res = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id= (SELECT complex_item_id from complex_item_details where id=" . $ci_item_id . ")"));
        $new["item"] = $res[0];
        $new["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        self::log_complex_item($complex_item_id, $old, $new, array("ci_item_id" => $ci_item_id, "command" => "delete"), 2);
    }
    public function updateCI($complex_item_id, $data)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        $old["item"] = $result[0];
        $old["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        $query = "UPDATE complex_items set discount='" . $data["discount"] . "',note='" . $data["note"] . "',name='" . $data["name"] . "',barcode='" . $data["barcode"] . "',category_id='" . $data["category_id"] . "',subcategory_id='" . $data["subcategory_id"] . "' where id =" . $complex_item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        self::autoUpdateCIData($complex_item_id);
        self::updateItemFromCI($complex_item_id);
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_items where id=" . $complex_item_id));
        $new["item"] = $result[0];
        $new["details"] = my_sql::fetch_assoc(my_sql::query("SELECT * FROM complex_item_details where complex_item_id=" . $complex_item_id));
        self::log_complex_item($complex_item_id, $old, $new, array("complex_item_id" => $complex_item_id, "data" => $data, "command" => "update_ci"), 3);
    }
    public function deleteCI($complex_item_id)
    {
        $query = "UPDATE complex_items set deleted=1 where id=" . $complex_item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        $result = 0 < my_sql::get_mysqli_rows_num();
        $query_ = "UPDATE items set deleted =1 where complex_item_id=" . $complex_item_id;
        my_sql::query($query_);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query_);
        }
        return $result;
    }
}

?>