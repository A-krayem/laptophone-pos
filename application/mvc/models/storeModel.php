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
class storeModel
{
    public function getStores()
    {
        $query = "select * from store where visible=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStores_c()
    {
        $query = "select id,name from store";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function vendor_is_exist()
    {
        $query = "select count(id) as num from users where role_id=2 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function getWarehouses()
    {
        $query = "select * from store where warehouse=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function reduce_qty_of_composite($item_composed)
    {
        $query = "select * from items where id=" . $item_composed["item_id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $query_d = "select * from complex_item_details where deleted=0 and complex_item_id=" . $result[0]["complex_item_id"];
            $result_d = my_sql::fetch_assoc(my_sql::query($query_d));
            for ($i = 0; $i < count($result_d); $i++) {
                $query__ = "select * from items where id=" . $result_d[$i]["item_id"];
                $result__ = my_sql::fetch_assoc(my_sql::query($query__));
                if ($result__[0]["is_composite"] == 0) {
                    self::reduce_qty_by_pos($_SESSION["store_id"], $result_d[$i]["item_id"], $result_d[$i]["qty"] * $item_composed["item_qty"], $_SESSION["id"]);
                } else {
                    $query_composite = "select it_comp.id,it_comp.composite_item_id,it_comp.item_id,CAST(it_comp.qty AS DECIMAL(20,2)) as qty,it.description,is_pack from items_composite as it_comp join items as it on it_comp.item_id=it.id and it_comp.composite_item_id=" . $result_d[$i]["item_id"];
                    $result_composite = my_sql::fetch_assoc(my_sql::query($query_composite));
                    for ($kk = 0; $kk < count($result_composite); $kk++) {
                        self::reduce_qty_by_pos($_SESSION["store_id"], $result_composite[$kk]["item_id"], $result_d[$i]["qty"] * $item_composed["item_qty"] * $result_composite[$kk]["qty"], $_SESSION["id"]);
                    }
                }
            }
        }
    }
    public function return_qty_of_composite($item_composed)
    {
        $query = "select * from items where id=" . $item_composed["item_id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $query_d = "select * from complex_item_details where deleted=0 and complex_item_id=" . $result[0]["complex_item_id"];
            $result_d = my_sql::fetch_assoc(my_sql::query($query_d));
            for ($i = 0; $i < count($result_d); $i++) {
                $store_info = array();
                $store_info["store_id"] = $_SESSION["store_id"];
                $store_info["user_id"] = $_SESSION["id"];
                $store_info["qty"] = $result_d[$i]["qty"] * $item_composed["item_qty"];
                $store_info["item_id"] = $result_d[$i]["item_id"];
                $store_info["source"] = "pos";
                self::add_qty($store_info);
            }
        }
    }
    public function get_other_stores($current_store_id)
    {
        $query = "select id,location from store where id!=" . $current_store_id . " and primary_db=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_currency_num()
    {
        $query = "select count(id) as num from currencies where disabled=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function getStoresById($id)
    {
        $query = "select * from store where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStoresGlobal()
    {
        $query = "select count(id) as num from store where primary_db=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStoresNotGlobal()
    {
        $query = "select * from store where primary_db=0 ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllStores()
    {
        $query = "select * from store";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllStores_options($stores)
    {
        $query = "select * from store where id in (" . $stores . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStoresNotGlobalInDetails()
    {
        $query = "select * from store where primary_db=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getQtyOfItem($store_id, $item_id)
    {
        $query = "select quantity from store_items where item_id=" . $item_id . " and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPackQtyOfItem($store_id, $item_id)
    {
        $query = "select packs_nb from store_items where item_id=" . $item_id . " and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getQtyOfItemInAllStore($item_id)
    {
        $query = "select sum(quantity) as quantity from store_items where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getQtyOfAllItemInAllStore()
    {
        $query = "select * from store_items";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalItemsQuantity($store_id)
    {
        $query = "select COALESCE(sum(quantity), 0) as sum from store_items where quantity>0 and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_qty($info)
    {
        ob_start();
        var_dump($info);
        $result_log = ob_get_clean();
        self::logmsg($result_log);
        $query = "update store_items set quantity=" . $info["qty"] . " where store_id=" . $info["store_id"] . " and item_id=" . $info["item_id"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $_SESSION["id"] . "," . $info["item_id"] . ",'" . my_sql::datetime_now() . "'," . $info["old_qty"] . "," . $info["store_id"] . "," . $info["qty"] . ",'" . $info["source"] . "')");
        }
    }
    public function logmsg($text)
    {
        if (QUERY_LOGS_ENABLE) {
            $file = QUERY_LOGS_PATH . "/textlog-" . date("Y-m-d") . ".txt";
            file_put_contents($file, date("h:i:sa") . " " . $text . "\n", FILE_APPEND | LOCK_EX);
        }
    }
    public function add_pack_qty($info)
    {
        $query = "update store_items set packs_nb=packs_nb+" . $info["qty"] . " where item_id=" . $info["item_id"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            $qty_after_add = self::getPackQtyOfItem($info["store_id"], $info["item_id"]);
            my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $_SESSION["id"] . "," . $info["item_id"] . ",'" . my_sql::datetime_now() . "'," . $info["qty"] . "," . $info["store_id"] . "," . $qty_after_add[0]["packs_nb"] . ",'" . $info["source"] . "')");
        }
    }
    public function add_qty($info)
    {
        $query = "update store_items set quantity=quantity+" . $info["qty"] . " where item_id=" . $info["item_id"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            $qty_after_add = self::getQtyOfItem($info["store_id"], $info["item_id"]);
            my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $_SESSION["id"] . "," . $info["item_id"] . ",'" . my_sql::datetime_now() . "'," . $info["qty"] . "," . $info["store_id"] . "," . $qty_after_add[0]["quantity"] . ",'" . $info["source"] . "')");
        }
    }
    public function update_expiry_date($info)
    {
        $query = "update store_items set expiry_date='" . $info["expiry_date"] . "' where item_id=" . $info["item_id"];
        my_sql::query($query);
    }
    public function one_query($query)
    {
        try {
            my_sql::query($query);
            return NULL;
        } catch (Exception $e) {
        }
    }
    public function reduce_qty($store_id, $item_id, $qty, $user_id)
    {
        $query = "update store_items set quantity=quantity-" . $qty . " where store_id=" . $store_id . " and item_id=" . $item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
        }
        $qty_after_add = self::getQtyOfItem($store_id, $item_id);
        if (0 < count($qty_after_add)) {
            my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action) values(" . $user_id . "," . $item_id . ",'" . my_sql::datetime_now() . "',-" . $qty . "," . $store_id . "," . $qty_after_add[0]["quantity"] . ")");
        }
    }
    public function reduce_qty_by_pos($store_id, $item_id, $qty, $user_id)
    {
        $query = "update store_items set quantity=quantity-" . $qty . " where store_id=" . $store_id . " and item_id=" . $item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
        }
        $qty_after_add = self::getQtyOfItem($store_id, $item_id);
        if (0 < count($qty_after_add)) {
            my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $user_id . "," . $item_id . ",'" . my_sql::datetime_now() . "',-" . $qty . "," . $store_id . "," . $qty_after_add[0]["quantity"] . ",'pos')");
        }
    }
    public function reduce_qty_by_admin($store_id, $item_id, $qty, $user_id, $invoice_id)
    {
        $query = "update store_items set quantity=quantity-" . $qty . " where store_id=" . $store_id . " and item_id=" . $item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
        }
        $qty_after_add = self::getQtyOfItem($store_id, $item_id);
        if (0 < count($qty_after_add)) {
            my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $user_id . "," . $item_id . ",'" . my_sql::datetime_now() . "',-" . $qty . "," . $store_id . "," . $qty_after_add[0]["quantity"] . ",'soldbyadmin-" . $invoice_id . "')");
        }
    }
    public function reduce_qty_transfer($store_id, $item_id, $qty, $user_id, $source)
    {
        $query = "update store_items set quantity=quantity-" . $qty . " where store_id=" . $store_id . " and item_id=" . $item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
        }
        $qty_after_add = self::getQtyOfItem($store_id, $item_id);
        if (0 < count($qty_after_add)) {
            my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $user_id . "," . $item_id . ",'" . my_sql::datetime_now() . "',-" . $qty . "," . $store_id . "," . $qty_after_add[0]["quantity"] . ",'" . $source . "')");
        }
    }
}

?>