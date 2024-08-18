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
class itemsModel
{
    public function getAllItems()
    {
        $query = "select * from items where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_sku_of_composite_of_item_id($item_id)
    {
        $query = "select composite_item_id from items_composite where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $query_ = "select sku_code from items where id=" . $result[0]["composite_item_id"];
            $result_ = my_sql::fetch_assoc(my_sql::query($query_));
            return $result_[0]["sku_code"];
        }
        return "";
    }
    public function items_variation_details($group_id)
    {
        $query = "select * from items where deleted=0 and item_group=" . $group_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function composite_set_cost_and_price($item_id)
    {
        $query_ = "SELECT id,item_id,qty FROM `items_composite` WHERE composite_item_id=" . $item_id;
        $result_ = my_sql::fetch_assoc(my_sql::query($query_));
        if (0 < count($result_)) {
            $query_info = "SELECT * FROM `items` WHERE id=" . $result_[0]["item_id"];
            $result_info = my_sql::fetch_assoc(my_sql::query($query_info));
            $query_update = "update items set buying_cost=" . $result_info[0]["buying_cost"] * $result_[0]["qty"] . ",selling_price=" . $result_info[0]["selling_price"] * $result_[0]["qty"] . ",wholesale_price=" . $result_info[0]["wholesale_price"] * $result_[0]["qty"] . ",second_wholesale_price=" . $result_info[0]["second_wholesale_price"] * $result_[0]["qty"] . " where id=" . $item_id . " and is_composite=1";
            my_sql::query($query_update);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync($query_update);
            }
        }
    }
    public function get_all_items_variation()
    {
        $query = "select item_group,count(item_group) as num from items where deleted=0 group by item_group having count(item_group)>1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $tmp = array();
            for ($i = 0; $i < count($result); $i++) {
                array_push($tmp, $result[$i]["item_group"]);
            }
            $query_v = "select id,description,item_category,selling_price,discount,item_group,color_text_id,size_id from items where deleted=0 and id in (" . implode(",", $tmp) . ")";
            return my_sql::fetch_assoc(my_sql::query($query_v));
        }
        return $result;
    }
    public function get_all_composites()
    {
        $query = "select it.id,it.description,si.packs_nb,si.packs_nb,ic.qty,ic.item_id as c_item_id,it2.description as it2description from items it left join store_items si on si.item_id=it.id left join items_composite ic on it.id=ic.composite_item_id left join items it2 on it2.id=ic.item_id  where si.store_id=" . $_SESSION["store_id"] . " and it.deleted=0 and it.is_composite=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_packs()
    {
        $query = "select it.id,it.description,si.packs_nb,si.packs_nb,ic.qty,ic.item_id as c_item_id,it2.description as it2description,it.buying_cost from items it left join store_items si on si.item_id=it.id left join items_composite ic on it.id=ic.composite_item_id left join items it2 on it2.id=ic.item_id  where si.store_id=" . $_SESSION["store_id"] . " and it.deleted=0 and it.is_composite=1 and ic.is_pack=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_item_pos($item_info)
    {
        $query = "INSERT INTO items (description,buying_cost,creation_date,user_id,item_category,selling_price,barcode) VALUES(" . "'" . $item_info["description"] . "','" . $item_info["cost"] . "',now()," . $_SESSION["id"] . ",1,'" . $item_info["selling"] . "','" . $item_info["item_barcode"] . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        my_sql::query("update items set item_group=" . $last_id . " where id=" . $last_id);
        return $last_id;
    }
    public function getItemsInPurchaseInvoice($pi_id)
    {
        $query = "select * from items where id in (select item_id from receive_stock where receive_stock_invoice_id=" . $pi_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function prepare_qties_in_box()
    {
        $query_boxes = "select * from items_composite where is_pack=0 and composite_item_id in (select id from items where deleted=0 and is_composite=1)";
        $result_boxes = my_sql::fetch_assoc(my_sql::query($query_boxes));
        $boxes = array();
        for ($i = 0; $i < count($result_boxes); $i++) {
            $boxes[$result_boxes[$i]["composite_item_id"]] = $result_boxes[$i]["qty"];
        }
        return $boxes;
    }
    public function get_all_items_that_have_boxes()
    {
        $return = array();
        $query_boxes = "select composite_item_id,item_id,qty from items_composite where is_pack=0 and composite_item_id in (select id from items where deleted=0)";
        $result_boxes = my_sql::fetch_assoc(my_sql::query($query_boxes));
        for ($i = 0; $i < count($result_boxes); $i++) {
            if (!in_array($result_boxes[$i]["item_id"], $return)) {
                array_push($return, $result_boxes[$i]["item_id"]);
            }
        }
        return $return;
    }
    public function prepare_all_boxes_qunatities()
    {
        $query_unit = "select item_id,quantity from store_items where item_id in (select item_id from items_composite)";
        $result_unit = my_sql::fetch_assoc(my_sql::query($query_unit));
        $units = array();
        for ($i = 0; $i < count($result_unit); $i++) {
            $units[$result_unit[$i]["item_id"]] = $result_unit[$i]["quantity"];
        }
        $query_boxes = "select * from items_composite where is_pack=0 and composite_item_id in (select id from items where deleted=0 and is_composite=1)";
        $result_boxes = my_sql::fetch_assoc(my_sql::query($query_boxes));
        $boxes = array();
        for ($i = 0; $i < count($result_boxes); $i++) {
            if (0 < $units[$result_boxes[$i]["item_id"]]) {
                if (0 < $result_boxes[$i]["qty"]) {
                    $boxes[$result_boxes[$i]["composite_item_id"]] = $units[$result_boxes[$i]["item_id"]] / $result_boxes[$i]["qty"];
                } else {
                    $boxes[$result_boxes[$i]["composite_item_id"]] = 0;
                }
            } else {
                $boxes[$result_boxes[$i]["composite_item_id"]] = 0;
            }
        }
        return $boxes;
    }
    public function get_all_available_boxes()
    {
        $return = array();
        $query_only_boxes = "select it.id,ic.composite_item_id,ic.item_id as citem_id,si.quantity,ic.qty,si.quantity/ic.qty as b from items it left join items_composite ic on ic.composite_item_id=it.id left join store_items si on si.item_id=ic.item_id where it.is_composite=1 and it.deleted=0";
        $result_only_boxes = my_sql::fetch_assoc(my_sql::query($query_only_boxes));
        for ($i = 0; $i < count($result_only_boxes); $i++) {
            if (1 <= $result_only_boxes[$i]["b"]) {
                array_push($return, $result_only_boxes[$i]["composite_item_id"]);
                array_push($return, $result_only_boxes[$i]["citem_id"]);
            }
        }
        return $return;
    }
    public function get_all_qty_of_boxitems_and_items()
    {
        $return = array();
        $query_only_items = "select it.id,it.is_composite,si.quantity from items it left join store_items si on si.item_id=it.id where it.is_composite=0 and it.deleted=0 and si.quantity>0";
        $result_only_items = my_sql::fetch_assoc(my_sql::query($query_only_items));
        for ($i = 0; $i < count($result_only_items); $i++) {
            array_push($return, $result_only_items[$i]["id"]);
        }
        $query_only_boxes = "select it.id,ic.composite_item_id,ic.item_id as citem_id,si.quantity,ic.qty from items it left join items_composite ic on ic.composite_item_id=it.id left join store_items si on si.item_id=ic.item_id where it.is_composite=1 and si.quantity>0";
        $result_only_boxes = my_sql::fetch_assoc(my_sql::query($query_only_boxes));
        for ($i = 0; $i < count($result_only_boxes); $i++) {
            array_push($return, $result_only_boxes[$i]["composite_item_id"]);
        }
        return $return;
    }
    public function get_all_items_images($filter)
    {
        $filter_sub_category = "";
        if (0 < $filter["subcategory"]) {
            $filter_sub_category = " and it.item_category=" . $filter["subcategory"] . " ";
        }
        $filter_category = "";
        if (0 < $filter["category"]) {
            $filter_category = " and it.item_category in (select id from items_categories where parent=" . $filter["category"] . ") ";
        }
        $query = "select itm.item_id,itm.name,it.description,it.selling_price,it.wholesale_price,it.second_wholesale_price,sit.quantity,it.is_composite as is_composite,it.buying_cost from items_images itm left join items it on it.id=itm.item_id left join store_items sit on sit.item_id=it.id where itm.deleted=0 " . $filter_sub_category . " order by it.id desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_invoice_info($invoice_item_id)
    {
        $query = "select inv.customer_id from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.id=" . $invoice_item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_qty_to_all($items_ids, $value)
    {
        $query = "select id,selling_price,item_group from items where item_group in (select item_group from items where id in (" . $items_ids . ") and item_group>0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $tmp_info = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp_info[$i]["id"] = $result[$i]["id"];
            $tmp_info[$i]["qty"] = $value;
        }
        for ($i = 0; $i < count($tmp_info); $i++) {
            $query = "update store_items set quantity=quantity+" . $value . " where item_id=" . $tmp_info[$i]["id"];
            my_sql::query($query);
            if (0 < my_sql::get_mysqli_rows_num()) {
                $qty_after_add = self::getQtyOfItem($_SESSION["store_id"], $tmp_info[$i]["id"]);
                my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $_SESSION["id"] . "," . $tmp_info[$i]["id"] . ",'" . my_sql::datetime_now() . "'," . $value . "," . $_SESSION["store_id"] . "," . $qty_after_add[0]["quantity"] . ",'manual')");
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $tmp_info[$i]["id"];
                if (0 < $value) {
                    $logs_info["description"] = "Added Qty " . $value . " of Item (IT-" . $tmp_info[$i]["id"] . ")";
                } else {
                    $logs_info["description"] = "Subtracted Qty " . $value . " of Item (IT-" . $tmp_info[$i]["id"] . ")";
                }
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                self::add_global_log($logs_info);
                if ($this->settings_info["telegram_enable"] == 1) {
                    $users = $this->model("user");
                    $employees_info = $users->getAllUsersEvenDeleted();
                    $employees_info_array = array();
                    for ($i = 0; $i < count($employees_info); $i++) {
                        $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                    }
                    $store = $this->model("store");
                    $store_info = $store->getStoresById($_SESSION["store_id"]);
                    $item_info = self::get_item($logs_info["related_to_item_id"]);
                    $info_tel = array();
                    $info_tel["message"] = "<strong>Qty Changed:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                    $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                    $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                    $info_tel["message"] .= "<strong>Item ID:</strong> " . $logs_info["related_to_item_id"] . " \n";
                    $info_tel["message"] .= "<strong>Description:</strong> " . $item_info[0]["description"] . " \n";
                    $info_tel["message"] .= "<strong>Qty:</strong> " . $value . " \n";
                    self::send_to_telegram($info_tel, 1);
                }
            }
        }
    }
    public function send_to_telegram($info, $telid)
    {
        $query = "insert into telegram(message,creation_date,status,telegram_id) values('" . $info["message"] . "',now(),0," . $telid . ")";
        my_sql::query($query);
    }
    public function add_global_log($info)
    {
        $query = "insert into global_logs(created_by,creation_date,related_to_item_id,description,log_type,other_info) " . "values('" . $info["operator_id"] . "','" . my_sql::datetime_now() . "','" . $info["related_to_item_id"] . "','" . $info["description"] . "','" . $info["log_type"] . "','" . $info["other_info"] . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function getQtyOfItem($store_id, $item_id)
    {
        $query = "select quantity from store_items where store_id=" . $store_id . " and item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_price_to_all($items_ids, $value)
    {
        $query = "select id,selling_price,item_group from items where item_group in (select item_group from items where id in (" . $items_ids . "))";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $tmp_info = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp_info[$i]["id"] = $result[$i]["id"];
            $tmp_info[$i]["selling_price"] = $value;
        }
        for ($i = 0; $i < count($tmp_info); $i++) {
            $query = "update items set selling_price=" . $tmp_info[$i]["selling_price"] . " where id=" . $tmp_info[$i]["id"];
            my_sql::query($query);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync($query);
            }
        }
    }
    public function add_value_to_price($items_ids, $value, $cost_also, $settings_info)
    {
        $query = "select id,selling_price,item_group,buying_cost from items where item_group in (select item_group from items where id in (" . $items_ids . "))";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $enable_rounding = false;
        if ($settings_info["enable_percentage_price_round"] == 1) {
            $enable_rounding = true;
        }
        $tmp_info = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp_info[$i]["id"] = $result[$i]["id"];
            $tmp_info[$i]["selling_price"] = $result[$i]["selling_price"] * (1 + $value / 100);
            $tmp_info[$i]["buying_cost"] = $result[$i]["buying_cost"] * (1 + $value / 100);
            $response = floor($tmp_info[$i]["selling_price"] / 1000);
            $rest = $tmp_info[$i]["selling_price"] % 1000;
            if ($enable_rounding && 0 < $rest) {
                $tmp_info[$i]["selling_price"] = $response * 1000 + 1000;
            }
        }
        for ($i = 0; $i < count($tmp_info); $i++) {
            $query = "update items set selling_price=" . $tmp_info[$i]["selling_price"] . " where id=" . $tmp_info[$i]["id"];
            my_sql::query($query);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync($query);
            }
            if ($cost_also == 1) {
                $query = "update items set buying_cost=" . $tmp_info[$i]["buying_cost"] . " where id=" . $tmp_info[$i]["id"];
                my_sql::query($query);
                if (0 < my_sql::get_mysqli_rows_num()) {
                    my_sql::global_query_sync($query);
                }
            }
        }
    }
    public function get_items_in_pi($pi_id)
    {
        $query = "select * from items where deleted=0 and id in (select DISTINCT(item_id) from receive_stock where receive_stock_invoice_id=" . $pi_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_in_dnote($id)
    {
        $query = "select * from items where deleted=0 and id in (select DISTINCT(item_id) from debit_notes_details where debit_note_id=" . $id . " and deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_images_info($id)
    {
        $query = "select name from items_images where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_item_images($id)
    {
        my_sql::query("update items_images set deleted=1 where id=" . $id);
    }
    public function update_prices_of_all_group($info)
    {
        $item_info = self::get_item($info["id_to_edit"]);
        if (0 < $item_info[0]["item_group"]) {
            $query = "update items set buying_cost=" . $info["item_cost"] . ",selling_price=" . $info["selling_price"] . ",discount=" . $info["item_disc"] . ",description='" . $info["item_desc"] . "',fixed_price='" . $info["fixed_price"] . "',fixed_price_value='" . $info["fixed_price_val"] . "' where item_group=" . $item_info[0]["item_group"] . " and item_group>0";
            my_sql::query($query);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync($query);
            }
        }
    }
    public function update_user_pos_col($item_id, $users_id)
    {
        if (!isset($users_id) || $users_id == "") {
            $users_id = 0;
        }
        my_sql::query("update store_items set pos_col_users='" . $users_id . "' where item_id=" . $item_id);
    }
    public function image_uploaded($item_id, $filename)
    {
        my_sql::query("insert into items_images(name,creation_date,item_id) value('" . $filename . "','" . my_sql::datetime_now() . "'," . $item_id . ")");
        return my_sql::get_mysqli_insert_id();
    }
    public function get_images_of_item($item_id)
    {
        $query = "select * from items_images where deleted=0 and item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function restore_item($id)
    {
        $query = "update items set deleted=0 where id=" . $id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function getAllTrashItems()
    {
        $query = "select * from items where deleted=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getLowItemsInAllStores($connection)
    {
        $query = "SELECT it.id,it.barcode,it.description,it.item_group,it.size_id,it.color_text_id,it.buying_cost,it.selling_price,it.discount,it.vat,count(it.color_text_id) as num,sum(si.quantity) as quantity FROM items it,store_items si where si.item_id=it.id and it.deleted=0 and it.item_group>0 group by it.item_group,color_text_id ORDER BY it.item_group,color_text_id,num asc";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $connection));
        return $result;
    }
    public function getAllItemsPOS($category_id, $sub_category_id)
    {
        $item_category_ = "";
        if ($category_id == 0 && 0 < $sub_category_id) {
            $item_category_ = " and item_category=" . $sub_category_id;
        } else {
            if (0 < $category_id && $sub_category_id == 0) {
                $item_category_ = " and item_category in (select id from items_categories where parent=" . $category_id . ") ";
            } else {
                if (0 < $category_id && 0 < $sub_category_id) {
                    $item_category_ = " and item_category=" . $sub_category_id;
                }
            }
        }
        $query = "select * from items where deleted=0 " . $item_category_;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function import_items_of_supplier($id)
    {
        $query = "select DISTINCT(item_id) from receive_stock where receive_stock_invoice_id in (select id from receive_stock_invoices where deleted=0 and supplier_id=" . $id . ") order by item_id asc ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function addItemToPI($item_id, $pi_id)
    {
        $query = "insert into receive_stock(item_id,location_id,qty,cost,receive_stock_invoice_id,supplier_ref) value(" . $item_id . "," . $_SESSION["store_id"] . ",0,0," . $pi_id . ",'')";
        my_sql::query($query);
        $id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $id;
    }
    public function getAllItemsByBarcode($barcode)
    {
        $query = "select * from items where barcode='" . $barcode . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsByGroup()
    {
        $query = "select *,count(id) as num from items where deleted=0 and item_group>0 group by item_group HAVING (num >1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsByGroup_Creationdate($creation_date)
    {
        $query_creation_date = "";
        if ($creation_date != 0) {
            $query_creation_date = " and date(creation_date)>='" . $creation_date[0] . "' and date(creation_date)<='" . $creation_date[1] . "' ";
        }
        $query = "select *,count(id) as num from items where deleted=0 and item_group>0 " . $query_creation_date . " group by item_group HAVING (num >1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsByGroup_onlyGroup()
    {
        $query = "select item_group,count(item_group) as num from items where deleted=0 and item_group>0 group by item_group HAVING (num >1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsByGroupBySubcategory($sub_id)
    {
        $query = "select *,count(id) as num from items where deleted=0 and item_group>0 and item_category=" . $sub_id . " and item_group>0 group by item_group HAVING (num >1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsByGroupBySubcategory_Creationdate($sub_id, $creation_date)
    {
        $query_creation_date = "";
        if ($creation_date != 0) {
            $query_creation_date = " and date(creation_date)>='" . $creation_date . "' ";
        }
        $query = "select *,count(id) as num from items where deleted=0 and item_group>0 and item_category=" . $sub_id . " and item_group>0 " . $query_creation_date . " group by item_group HAVING (num >1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItems_instant_report()
    {
        $query = "select * from items where deleted=0 and instant_report=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItems_withfilter_to_transfer($category_id, $sub_category_id, $itemboxes, $supplier_id)
    {
        $supplier_reference = "";
        if (0 < $supplier_id) {
            $supplier_reference = " and supplier_reference=" . $supplier_id;
        }
        $item_category_ = "";
        if ($category_id == 0 && 0 < $sub_category_id) {
            $item_category_ = " and item_category=" . $sub_category_id;
        } else {
            if (0 < $category_id && $sub_category_id == 0) {
                $item_category_ = " and item_category in (select id from items_categories where parent=" . $category_id . ") ";
            } else {
                if (0 < $category_id && 0 < $sub_category_id) {
                    $item_category_ = " and item_category=" . $sub_category_id;
                }
            }
        }
        $item_boxes_ = "";
        if (0 < $itemboxes) {
            if ($itemboxes == 1) {
                $item_boxes_ = " and is_composite=0 ";
            }
            if ($itemboxes == 2) {
                $item_boxes_ = " and is_composite=1 ";
            }
        }
        $query = "select * from items where deleted=0 and item_group>0 " . $supplier_reference . " " . $item_category_ . " " . $item_boxes_ . "  ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query_1 = "select * from items where deleted=0 and item_group=0 " . $supplier_reference . " " . $item_category_ . " " . $item_boxes_ . "";
        $result_1 = my_sql::fetch_assoc(my_sql::query($query_1));
        return array_merge($result, $result_1);
    }
    public function getAllItems_withfilter($category_id, $sub_category_id, $itemboxes, $supplier_id, $stock_status)
    {
        $supplier_reference = "";
        if (0 < $supplier_id) {
            $supplier_reference = " and supplier_reference=" . $supplier_id;
        }
        $item_category_ = "";
        if ($category_id == 0 && 0 < $sub_category_id) {
            $item_category_ = " and item_category=" . $sub_category_id;
        } else {
            if (0 < $category_id && $sub_category_id == 0) {
                $item_category_ = " and item_category in (select id from items_categories where parent=" . $category_id . ") ";
            } else {
                if (0 < $category_id && 0 < $sub_category_id) {
                    $item_category_ = " and item_category=" . $sub_category_id;
                }
            }
        }
        $item_boxes_ = "";
        if (0 < $itemboxes) {
            if ($itemboxes == 1) {
                $item_boxes_ = " and is_composite=0 and complex_item_id=0  ";
            }
            if ($itemboxes == 2) {
                $item_boxes_ = " and is_composite=1 and complex_item_id=0 ";
            }
            if ($itemboxes == 3) {
                $item_boxes_ = " and complex_item_id>0 ";
            }
        }
        $stock_filter = "";
        if (0 < $stock_status) {
            if ($stock_status == 1) {
                $stock_filter = " and id in (select item_id from store_items where quantity>0) ";
            }
            if ($stock_status == 2) {
                $stock_filter = " and id in (select item_id from store_items where quantity<=0) ";
            }
        }
        $query = "select * from items where deleted=0 and item_group>0 " . $supplier_reference . " " . $item_category_ . " " . $item_boxes_ . " group by item_group ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query_1 = "select * from items where deleted=0 and item_group=0 " . $supplier_reference . " " . $item_category_ . " " . $item_boxes_ . "";
        $result_1 = my_sql::fetch_assoc(my_sql::query($query_1));
        return array_merge($result, $result_1);
    }
    public function getAllItems_withfilter_Ajax($category_id, $sub_category_id, $itemboxes, $supplier_id, $filter, $only_count)
    {
        if (isset($filter["col_sort_index"])) {
            $filter["col_sort"] = $filter["columns"][$filter["col_sort_index"]];
        }
        $query_search_filters = "";
        if (0 < strlen($filter["search_filters"])) {
            $query_search_filters .= " and (";
            $index = 0;
            foreach ($filter["columns"] as $key => $value) {
                $query_search_filters .= " " . $value . " like '%" . $filter["search_filters"] . "%' ";
                $index++;
                if ($index < count($filter["columns"])) {
                    $query_search_filters .= " or ";
                }
            }
            $query_search_filters .= ")";
        }
        if (0 < count($filter["search_col_filters"])) {
            $query_search_filters = "";
            $index = 0;
            $query_search_filters .= " and (";
            foreach ($filter["search_col_filters"] as $key => $value) {
                $index++;
                $query_search_filters .= " " . $filter["columns"][$filter["search_col_filters"][$key]["colindex"] - 1] . " like '%" . $filter["search_col_filters"][$key]["colval"] . "%' ";
                if ($index < count($filter["search_col_filters"])) {
                    $query_search_filters .= " and ";
                }
            }
            $query_search_filters .= ")";
        }
        $supplier_reference = "";
        if (0 < $supplier_id) {
            $supplier_reference = " and it.supplier_reference=" . $supplier_id;
        }
        $item_category_ = "";
        if ($category_id == 0 && 0 < $sub_category_id) {
            $item_category_ = " and it.item_category=" . $sub_category_id;
        } else {
            if (0 < $category_id && $sub_category_id == 0) {
                $item_category_ = " and it.item_category in (select id from items_categories where parent=" . $category_id . ") ";
            } else {
                if (0 < $category_id && 0 < $sub_category_id) {
                    $item_category_ = " and it.item_category=" . $sub_category_id;
                }
            }
        }
        $item_boxes_ = "";
        if (0 < $itemboxes) {
            if ($itemboxes == 1) {
                $item_boxes_ = " and it.is_composite=0 ";
            }
            if ($itemboxes == 2) {
                $item_boxes_ = " and it.is_composite=1 ";
            }
        }
        $limit = "";
        if ($only_count == 0) {
            $limit = $query_search_filters . " order by " . $filter["col_sort"] . " " . $filter["order_by"] . " limit " . $filter["start"] . "," . $filter["row_per_page"];
        } else {
            $limit = $query_search_filters . " ";
        }
        $final_cost = " CASE WHEN it.vat='1' THEN (it.buying_cost *" . $filter["items_vat"] . ") ELSE it.buying_cost END  ";
        $discount_price = "it.selling_price-(it.discount*it.selling_price/100)";
        if ($filter["enable_wholasale"] == 0) {
            $price_after_discount = " CASE WHEN it.vat='1' THEN (" . $discount_price . ")*" . $filter["items_vat"] . " ELSE (" . $discount_price . ") END ";
        } else {
            $price_after_discount = " CASE WHEN it.vat='1' THEN CONCAT( round( (" . $discount_price . ")*" . $filter["items_vat"] . ",1),'/',round(it.wholesale_price*" . $filter["items_vat"] . ",1)) ELSE  CONCAT(round(" . $discount_price . ",1),'/',round(it.wholesale_price,1)) END ";
        }
        $price_discount = "CASE WHEN it.vat='1' THEN (" . $discount_price . ")*" . $filter["items_vat"] . " ELSE (" . $discount_price . ") END";
        if ($filter["enable_wholasale"] == 0) {
            $last_price_whole_price = $price_discount . "-" . $final_cost;
        } else {
            $whole_price = " CASE WHEN it.vat='1' THEN (it.wholesale_price*" . $filter["items_vat"] . ") ELSE  it.wholesale_price END ";
            $last_price_whole_price = "CONCAT(round(" . $price_discount . "-" . $final_cost . ",2),'/',round(" . $whole_price . "-" . $final_cost . ",2))";
        }
        $margin_profit = " CASE WHEN (" . $price_discount . ")>0 THEN (((" . $price_discount . ")-(" . $final_cost . "))/(" . $price_discount . "))*100  ELSE '0' END as margin_profit";
        $query = "select * from (select it.deleted,it.item_group ,it.is_composite,mat.name as material_label,it.color_id,it.vat,it.color_text_id,it.size_id,it.supplier_reference,it.material_id,it.id as item_id,it.barcode,it.description,it.sku_code,CASE WHEN sku_code IS  NULL   THEN it.description  ELSE CONCAT(it.description,' # ' ,it.sku_code) END as desc_sku,CASE WHEN it.size_id IS  NULL  THEN 'None' ELSE u_size.name    END as size_label,CASE WHEN it.color_text_id IS  NULL  THEN '' ELSE unit_c.name  END as color_text_label, " . $final_cost . "   as final_cost ," . $price_after_discount . " as price_after_discount," . $last_price_whole_price . " as last_price_whole_price ," . $margin_profit . " from items as it  left join unit_size as u_size on u_size.id=it.size_id left join unit_color as unit_c on unit_c.id=it.color_text_id left join materials as mat on mat.id=it.material_id  where 1 " . $supplier_reference . " " . $item_category_ . " " . $item_boxes_ . "  group by item_group ) as all_items where deleted=0 and item_group>0 " . $limit;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query_1 = "select * from (select it.deleted,it.item_group ,it.is_composite,mat.name as material_label,it.color_id,it.vat,it.color_text_id,it.size_id,it.supplier_reference,it.material_id,it.id as item_id,it.barcode,it.description,it.sku_code,CASE WHEN sku_code IS  NULL   THEN it.description  ELSE CONCAT(it.description,' # ' ,it.sku_code) END as desc_sku,CASE WHEN it.size_id IS  NULL  THEN 'None' ELSE u_size.name    END as size_label,CASE WHEN it.color_text_id IS  NULL  THEN '' ELSE unit_c.name  END as color_text_label, " . $final_cost . "   as final_cost ," . $price_after_discount . " as price_after_discount," . $last_price_whole_price . " as last_price_whole_price ," . $margin_profit . " from items as it  left join unit_size as u_size on u_size.id=it.size_id left join unit_color as unit_c on unit_c.id=it.color_text_id left join materials as mat on mat.id=it.material_id  where 1 " . $supplier_reference . " " . $item_category_ . " " . $item_boxes_ . " ) as all_items where deleted=0 and item_group=0 " . $limit;
        $result_1 = my_sql::fetch_assoc(my_sql::query($query_1));
        return array_merge($result, $result_1);
    }
    public function getAllItems_withfilter_without_grouping($category_id, $sub_category_id, $itemboxes, $supplier_id)
    {
        $supplier_reference = "";
        if (0 < $supplier_id) {
            $supplier_reference = " and (supplier_reference=" . $supplier_id . " or id in (select item_id from receive_stock where receive_stock_invoice_id in (select id from receive_stock_invoices where supplier_id=" . $supplier_id . ")) ) ";
        }
        $item_category_ = "";
        if ($category_id == 0 && 0 < $sub_category_id) {
            $item_category_ = " and item_category=" . $sub_category_id;
        } else {
            if (0 < $category_id && $sub_category_id == 0) {
                $item_category_ = " and item_category in (select id from items_categories where parent=" . $category_id . ") ";
            } else {
                if (0 < $category_id && 0 < $sub_category_id) {
                    $item_category_ = " and item_category=" . $sub_category_id;
                }
            }
        }
        $item_boxes_ = "";
        if (0 < $itemboxes) {
            if ($itemboxes == 1) {
                $item_boxes_ = " and is_composite=0 ";
            }
            if ($itemboxes == 2) {
                $item_boxes_ = " and is_composite=1 ";
            }
        }
        $query_1 = "select * from items where deleted=0 " . $supplier_reference . " " . $item_category_ . " " . $item_boxes_ . "";
        $result_1 = my_sql::fetch_assoc(my_sql::query($query_1));
        return $result_1;
    }
    public function getAllItemsEvenDeleted()
    {
        $query = "select * from items";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsEvenDeleted_limited()
    {
        $query = "select id,barcode,description,size_id,color_text_id,deleted from items";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsEvenDeleted_limited_by_barcodes_array($search_barcodes)
    {
        $query = "select id,barcode,description,size_id,color_text_id from items where barcode in ('" . implode("','", $search_barcodes) . "')";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllBoxesItemsQty()
    {
        $query = "select * from items_composite";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsBySub($id_sub, $store_id)
    {
        $query = "select it.id,it.description,it.barcode,it.buying_cost,it.vat,it.selling_price,it.discount,si.quantity as quantity,it.size_id,it.color_text_id from items it,store_items si where it.id=si.item_id and si.store_id=" . $store_id . " and it.item_category=" . $id_sub . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsBySub_andcreationdate($id_sub, $store_id, $creation_date)
    {
        $query_creation_date = "";
        if ($creation_date != 0) {
            $query_creation_date = " and date(creation_date)>='" . $creation_date . "'";
        }
        $query = "select it.id,it.description,it.barcode,it.buying_cost,it.vat,it.selling_price,it.discount,si.quantity as quantity,it.size_id,it.color_text_id from items it,store_items si where it.id=si.item_id and si.store_id=" . $store_id . " and it.item_category=" . $id_sub . " and it.deleted=0 " . $creation_date;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsWithQTY($store_id, $creation_date)
    {
        $query_creation_date = "";
        if ($creation_date != 0) {
            $query_creation_date = " and date(creation_date)>='" . $creation_date[0] . "' and date(creation_date)<='" . $creation_date[1] . "' ";
        }
        $query = "select it.id,it.description,it.barcode,it.buying_cost,it.vat,it.selling_price,it.discount,si.quantity as quantity,it.size_id,it.color_text_id from items it,store_items si where it.id=si.item_id and si.store_id=" . $store_id . " and deleted=0 " . $query_creation_date;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsWithQTY_by_group()
    {
        $query = "select  it.item_group,COALESCE(sum(si.quantity), 0) as qty from items it,store_items si where it.id=si.item_id and deleted=0 group by it.item_group";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_in($in, $store_id)
    {
        $query = "select it.id as id,it.buying_cost as buying_cost,it.is_official as is_official,it.vat as vat,it.selling_price as selling_price,it.description as description,it.discount as discount,it.is_composite as is_composite,si.quantity as quantity,it.fixed_price,it.fixed_price_value,it.complex_item_id from items it,store_items si where it.id=si.item_id and store_id=" . $store_id . " and it.id in " . $in;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getMaxItemId()
    {
        $query = "SELECT MAX(id) as mid FROM items";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getitems_for_type_head()
    {
        $query = "select id,description as name from items where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_composite_items($composite_item_id)
    {
        my_sql::query("delete from items_composite where composite_item_id=" . $composite_item_id);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync("delete from items_composite where composite_item_id=" . $composite_item_id);
        }
    }
    public function get_all_composite_of_item($id)
    {
        $query = "select it_comp.id,it_comp.composite_item_id,it_comp.item_id,CAST(it_comp.qty AS DECIMAL(20,2)) as qty,it.description,is_pack from items_composite as it_comp join items as it on it_comp.item_id=it.id and it_comp.composite_item_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_names()
    {
        $query = "select id,description,barcode,second_barcode from items where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_names_without_boxes()
    {
        $query = "select id,description,barcode,second_barcode,color_text_id,size_id from items where deleted=0 and is_composite=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_names_with_boxes()
    {
        $query = "select id,description,barcode,second_barcode,color_text_id,size_id from items where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_barcodes()
    {
        $query = "select id,barcode as name from items where deleted=0 and barcode is not null";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_vat($info)
    {
        $query = "update items set vat=" . $info["vat"] . " where id=" . $info["item_id"];
        my_sql::query($query);
    }
    public function force_item_price_equal_cost($selling_price, $item_id)
    {
        $query = "update items set selling_price=" . $selling_price . " where id=" . $item_id;
        my_sql::query($query);
    }
    public function get_history_cost_deprecated($item_id)
    {
        $query = "select * from history_prices where item_id=" . $item_id . " order by creation_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_global_average_cost($item_id)
    {
        $history_costs = self::get_history_cost($item_id);
        $return_cost = self::calculate_global_average_cost($history_costs);
        if ($return_cost != 0) {
            self::update_global_average_cost($return_cost, $item_id);
        }
    }
    public function get_history_cost($item_id)
    {
        $query = "select * from history_prices where item_id=" . $item_id . " order by creation_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function calculate_global_average_cost($result)
    {
        $history_array_ = array();
        $new_average = 0;
        $temp_id = 0;
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]["source"] == "force") {
                $new_average = $result[$i]["new_cost"];
                $temp_id = $result[$i]["id"];
                break;
            }
            array_push($history_array_, $result[$i]);
        }
        $history_array = array_reverse($history_array_);
        for ($i = 0; $i < count($history_array); $i++) {
            if (0 < $history_array[$i]["old_qty"] + $history_array[$i]["added_qty"] + $history_array[$i]["free_qty"]) {
                $new_average = ($history_array[$i]["old_qty"] * $history_array[$i]["old_cost"] + $history_array[$i]["added_qty"] * $history_array[$i]["new_cost"]) / ($history_array[$i]["old_qty"] + $history_array[$i]["added_qty"] + $history_array[$i]["free_qty"]);
            } else {
                $new_average = $history_array[$i]["new_cost"];
            }
            $query = "update history_prices set average_cost=" . round($new_average, 5) . " where id=" . $history_array[$i]["id"];
            my_sql::query($query);
        }
        if (count($history_array) == 0) {
            $query = "update history_prices set average_cost=" . round($new_average, 5) . " where id=" . $temp_id;
            my_sql::query($query);
        }
        return $new_average;
    }
    public function set_global_average_cost_deprecated($item_id)
    {
        $history_costs = self::get_history_cost($item_id);
        $return_cost = self::calculate_global_average_cost($history_costs);
        if ($return_cost != 0) {
            self::update_global_average_cost($return_cost, $item_id);
        }
    }
    public function calculate_global_average_cost_deprecated($result)
    {
        $sum_qty = 0;
        $sum_cost_qty = 0;
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]["source"] == "force") {
                $sum_qty += $result[$i]["old_qty"] + $result[$i]["free_qty"];
                $sum_cost_qty += $result[$i]["new_cost"] * $result[$i]["old_qty"];
            } else {
                if (0 < $result[$i]["added_qty"]) {
                    $sum_qty += $result[$i]["added_qty"] + $result[$i]["free_qty"];
                    $sum_cost_qty += $result[$i]["new_cost"] * $result[$i]["added_qty"];
                }
                if ($result[$i]["old_qty"] == 0) {
                    break;
                }
            }
            if ($result[$i]["source"] == "force") {
                break;
            }
        }
        if ($sum_qty != 0) {
            return $sum_cost_qty / $sum_qty;
        }
        return 0;
    }
    public function update_global_average_cost($cost, $item_id)
    {
        $query = "update items set buying_cost=" . $cost . " where id=" . $item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        self::update_global_average_cost_for_box($item_id);
    }
    public function update_global_average_cost_for_box($item_id)
    {
        $item_info = self::get_item($item_id);
        $result = self::get_all_boxes_of_item_id($item_id);
        for ($i = 0; $i < count($result); $i++) {
            $q = "update items set buying_cost=(" . (double) $item_info[0]["buying_cost"] . "*" . (double) $result[$i]["qty"] . ") where id=" . $result[$i]["composite_item_id"];
            my_sql::query($q);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync($q);
            }
        }
    }
    public function get_all_boxes_of_item_id($item_id)
    {
        $query = "select * from items_composite where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_composite_item_id($item_id)
    {
        $query = "select * from items_composite where composite_item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_average_cost($info)
    {
    }
    public function get_item_in_store($item_id)
    {
        $query = "select * from store_items where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_qty_in_store($item_id, $store_id)
    {
        $query = "select quantity,date(expiry_date) as expiry_date,packs_nb from store_items where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_item_qty_in_store($store_id)
    {
        $query = "select item_id,quantity from store_items where store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_item_qty_in_store_by_group($store_id)
    {
        $query = "SELECT it.id as item_id,it.item_group,sum(si.quantity) as quantity FROM items it,store_items si where it.id=si.item_id and si.store_id=" . $store_id . " and it.deleted=0  group by item_group";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item($id)
    {
        if ($id != NULL) {
            $query = "select * from items where id=" . $id;
            $result = my_sql::fetch_assoc(my_sql::query($query));
            return $result;
        }
        return array();
    }
    public function item_manual_qty_edit($item_id)
    {
        $query = "select * from history_quantities where item_id=" . $item_id . " and (source='manual' or source like '%NBTRANS%') ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function item_pack_qty_edit($item_id)
    {
        $query = "select * from history_quantities where item_id=" . $item_id . " and source='pack'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function item_as_composer($item_id)
    {
        $query = "select cid.complex_item_id,cid.qty,cid.item_id from complex_item_details cid where cid.deleted=0 and cid.item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $result_composer = array();
        $all = array();
        $all_index = 0;
        if (0 < count($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $query_it = "select id from items it where it.complex_item_id=" . $result[$i]["complex_item_id"];
                $result_it = my_sql::fetch_assoc(my_sql::query($query_it));
                $query_composer = "select inv.id,inv_it.qty*" . floatval($result[$i]["qty"]) . " as qty,inv.creation_date,inv.customer_id,cs.name from invoices inv left join invoice_items inv_it on inv_it.invoice_id=inv.id left join customers cs on cs.id=inv.customer_id where inv_it.qty>0 and inv_it.item_id=" . $result_it[0]["id"] . " and inv_it.deleted=0 and inv.deleted=0";
                $result_composer = my_sql::fetch_assoc(my_sql::query($query_composer));
                if (0 < count($result_composer)) {
                    for ($k = 0; $k < count($result_composer); $k++) {
                        $all[$all_index] = $result_composer[$k];
                        $all_index++;
                    }
                }
            }
        }
        return $result_composer;
    }
    public function item_as_boxes($item_id)
    {
        $query = "select * from items_composite where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $result_composit = array();
        $all = array();
        $all_index = 0;
        if (0 < count($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $query_composite = "select inv.id,inv_it.qty*" . floatval($result[$i]["qty"]) . " as qty,inv.creation_date,inv.customer_id,cs.name from invoices inv left join invoice_items inv_it on inv_it.invoice_id=inv.id left join customers cs on cs.id=inv.customer_id where inv_it.qty>0 and inv_it.item_id=" . $result[$i]["composite_item_id"] . " and inv_it.deleted=0 and inv.deleted=0";
                $result_composit = my_sql::fetch_assoc(my_sql::query($query_composite));
                if (0 < count($result_composit)) {
                    for ($k = 0; $k < count($result_composit); $k++) {
                        $all[$all_index] = $result_composit[$k];
                        $all_index++;
                    }
                }
            }
        }
        return $result_composit;
    }
    public function get_item_array($ids)
    {
        $query = "select * from items where id in (" . implode(",", $ids) . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_stock_array($ids)
    {
        $query = "select * from store_items where item_id in (" . implode(",", $ids) . ") and store_id=" . $_SESSION["store_id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_in_group($id)
    {
        $query = "select * from items where deleted=0 and item_group=" . $id . " limit 1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_by_group($group_id)
    {
        $query = "select * from items where item_group=" . $group_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_logs($id, $store_id)
    {
        $query = "select * from history_quantities where item_id=" . $id . " order by id desc limit 10000";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_manual_qty($id)
    {
        $query = "select * from history_quantities where item_id=" . $id . " and is_pos_transfer=0 and source='manual'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_logs_by_date_range($date_range)
    {
        $query = "select hq.id,hq.user_id,hq.item_id,hq.creation_date,hq.qty,hq.store_id,hq.qty_afer_action,hq.source,it.description  from history_quantities hq left join items it on it.id=hq.item_id where hq.is_pos_transfer=0 and date(hq.creation_date)>='" . $date_range[0] . "' and date(hq.creation_date)<='" . $date_range[1] . "' order by hq.creation_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsForPos($info)
    {
        $query = "select * from items where vendor_quantity_access=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_by_barcode_to_check($barcode)
    {
        $query = "select it.id,it.description,it.selling_price,si.quantity,it.barcode from items it left join store_items si on si.item_id=it.id where it.deleted=0 and  it.barcode='" . $barcode . "' or it.second_barcode='" . $barcode . "' and it.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_id_by_barcode($barcode)
    {
        $query = "select id from items where (barcode='" . $barcode . "' or barcode='" . ltrim($barcode, "0") . "' or barcode='0" . ltrim($barcode, "0") . "' or barcode='00" . ltrim($barcode, "0") . "' or barcode='000" . ltrim($barcode, "0") . "' or barcode='0000" . ltrim($barcode, "0") . "' or second_barcode='" . $barcode . "') and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function _get_item_by_barcode($barcode)
    {
        $query = "select * from items where (barcode='" . $barcode . "' or barcode='" . ltrim($barcode, "0") . "' or barcode='0" . ltrim($barcode, "0") . "' or barcode='00" . ltrim($barcode, "0") . "' or barcode='000" . ltrim($barcode, "0") . "' or barcode='0000" . ltrim($barcode, "0") . "' or second_barcode='" . $barcode . "') and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_by_barcode($barcode)
    {
        $query = "select * from items where (barcode='" . $barcode . "' or second_barcode='" . $barcode . "') and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_max_barcode()
    {
        $query = "select MAX(barcode) as mx from items";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return $result[0]["mx"];
        }
        return 1;
    }
    public function get_item_by_second_barcode_link($barcode)
    {
        $query = "select * from items where (second_barcode='" . $barcode . "' or second_barcode='" . ltrim($barcode, "0") . "' or second_barcode='0" . ltrim($barcode, "0") . "' or second_barcode='00" . ltrim($barcode, "0") . "' or second_barcode='000" . ltrim($barcode, "0") . "' or second_barcode='0000" . ltrim($barcode, "0") . "') and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function posit_changed($item_id, $order)
    {
        $query = "update store_items set pos_order=" . $order . " where item_id=" . $item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function get_items_in_store($store_id, $cat, $subcat)
    {
        $filter_condition = "";
        if (0 < $subcat) {
            $filter_condition = " and it.item_category =" . $subcat;
        } else {
            if (0 < $cat && $subcat == 0) {
                $filter_condition = " and it.item_category in (select id from items_categories where parent=" . $cat . ")";
            }
        }
        $query = "select si.id,si.store_id,si.item_id,si.on_pos_interface,it.description,it.sku_code,it.barcode,it.supplier_reference,si.quantity,it.buying_cost,it.vat,it.selling_price,it.discount,it.is_composite,si.pos_order,si.pos_col_users,it.color_text_id,it.size_id,si.packs_nb,it.wholesale_price,it.second_wholesale_price from store_items as si join items as it on it.id=si.item_id and it.deleted=0 " . $filter_condition;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_expired_items_in_store($store_id, $expiry_interval)
    {
        $query = "select si.id,si.item_id,it.description,it.barcode,si.quantity,it.vat,it.discount,it.buying_cost,it.selling_price,si.expiry_date,it.supplier_reference," . $expiry_interval . "-DATEDIFF(date('" . my_sql::datetime_now() . "'+INTERVAL " . $expiry_interval . " DAY),date(si.expiry_date)) as remain from store_items as si join items as it on it.id=si.item_id and si.store_id=" . $store_id . " and it.deleted=0 and si.quantity>0 and date(expiry_date)<date('" . my_sql::datetime_now() . "'+INTERVAL " . $expiry_interval . " DAY) and expiry_date IS NOT NULL order by remain asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_expired_items_nb($store_id, $expiry_interval)
    {
        $query = "select si.id,si.item_id,it.description,it.barcode,si.quantity,it.vat,it.discount,si.expiry_date,it.supplier_reference," . $expiry_interval . "-DATEDIFF(date('" . my_sql::datetime_now() . "'+INTERVAL " . $expiry_interval . " DAY),date(si.expiry_date)) as remain from store_items as si join items as it on it.id=si.item_id and si.store_id=" . $store_id . " and it.deleted=0 and si.quantity>0 and date(expiry_date)<date('" . my_sql::datetime_now() . "'+INTERVAL " . $expiry_interval . " DAY) and expiry_date IS NOT NULL order by remain asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_composite_items($info)
    {
        $query = "insert into items_composite(composite_item_id,item_id,qty,is_pack) values(" . $info["composite_item_reference"] . "," . $info["item_id"] . "," . $info["item_composite_qty"] . "," . $info["is_pack"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync("insert into items_composite(id,composite_item_id,item_id,qty,is_pack) values(" . $last_insert_id . "," . $info["composite_item_reference"] . "," . $info["item_id"] . "," . $info["item_composite_qty"] . "," . $info["is_pack"] . ")");
        }
        self::update_global_average_cost_for_box($info["item_id"]);
    }
    public function add_history_prices($info)
    {
        $query = "insert into history_prices(user_id,item_id,creation_date,old_cost,new_cost,old_qty,added_qty,source,receive_stock_id,free_qty) values(" . $info["user_id"] . "," . $info["item_id"] . ",'" . my_sql::datetime_now() . "'," . $info["old_cost"] . "," . $info["new_cost"] . "," . $info["old_qty"] . "," . $info["new_qty"] . ",'" . $info["source"] . "','" . $info["receive_stock_id"] . "'," . $info["free"] . ")";
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function update_bulk_queries($qry)
    {
        my_sql::query($qry);
    }
    public function update_history_prices($info)
    {
        $query = "update history_prices set added_qty=added_qty+" . $info["new_qty"] . ",new_cost=" . $info["new_cost"] . " where item_id=" . $info["item_id"] . " and source='" . $info["po_id"] . "' and receive_stock_id='" . $info["receive_stock_id"] . "'";
        my_sql::query($query);
    }
    public function update_history_prices_per_it($info)
    {
        $query = "update history_prices set added_qty=added_qty+" . $info["new_qty"] . " where item_id=" . $info["item_id"] . " and source='" . $info["po_id"] . "' and receive_stock_id='" . $info["receive_stock_id"] . "'";
        my_sql::query($query);
    }
    public function if_bulk_accepted($info)
    {
        $query = "select id from items where item_group=" . $info["item_group"] . " and size_id=" . $info["size_id"] . " and color_text_id=" . $info["color_text_id"] . " ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return false;
        }
        return true;
    }
    public function get_item_info_by_color_size_group($info)
    {
        $query = "select id from items where item_group=" . $info["item_group"] . " and size_id=" . $info["size_id"] . " and color_text_id=" . $info["color_text_id"] . " ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function bulk_item($info)
    {
        $query = "insert into items (description,item_category,buying_cost,selling_price,barcode,supplier_reference,discount,vat,lack_warning,vendor_quantity_access,instant_report,unit_measure_id,color_id,size_id,deleted,item_alias,is_composite,wholesale_price,supplier_ref,is_official,color_text_id,creation_date,user_id,sku_code,second_barcode,material_id,vat_on_sale,item_group) " . "select description,item_category,buying_cost,selling_price,'" . $info["barcode"] . "',supplier_reference,discount,vat,lack_warning,vendor_quantity_access,instant_report,unit_measure_id,color_id," . $info["size_id"] . ",deleted,item_alias,is_composite,wholesale_price,supplier_ref,is_official," . $info["color_text_id"] . ",creation_date,user_id,sku_code,second_barcode,material_id,vat_on_sale,item_group from items where id=" . $info["item_id"];
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $last_insert_id;
    }
    public function set_item_group($item_id, $group_item_id)
    {
        $query_set_group_id = "update items set item_group=" . $group_item_id . " where id=" . $item_id;
        my_sql::query($query_set_group_id);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query_set_group_id);
        }
    }
    public function add_new_item($info)
    {
        $query = "INSERT INTO items (description,item_category,buying_cost,selling_price,barcode,supplier_reference,discount,vat,lack_warning,vendor_quantity_access,instant_report,unit_measure_id,color_id,size_id,item_alias,is_composite,wholesale_price,supplier_ref,is_official,color_text_id,creation_date,user_id,sku_code,second_barcode,material_id,vat_on_sale,another_description,show_on_pos,depend_on_var_price,weight,fixed_price,fixed_price_value,second_wholesale_price,image_link) VALUES('" . str_replace("'", "", $info["item_desc"]) . "', " . $info["item_cat"] . "," . $info["item_cost"] . ", " . $info["selling_price"] . ", " . $info["item_barcode"] . ", " . $info["supplier_id"] . ", " . $info["item_disc"] . ", " . $info["item_vat"] . "," . $info["lack_warning"] . "," . $info["vendor_access"] . "," . $info["instant_report"] . "," . $info["item_unit_measure"] . ",'" . $info["item_color"] . "'," . $info["item_size"] . "," . $info["item_alias"] . "," . $info["is_composite"] . "," . $info["wholesale_price"] . ",'" . $info["supplier_ref"] . "'," . $info["is_official"] . "," . $info["item_text_color"] . ",'" . my_sql::datetime_now() . "'," . $info["user_id"] . "," . $info["item_sku"] . "," . $info["item_barcode_second"] . "," . $info["material_id"] . "," . $info["vat_on_sale"] . "," . $info["another_description"] . "," . $info["show_on_pos"] . "," . $info["depend_on_var_price"] . "," . $info["weight"] . "," . $info["fixed_price"] . "," . $info["fixed_price_val"] . "," . $info["second_wholesale_price"] . ",'" . $info["image_link"] . "')";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync("INSERT INTO items (id,description,item_category,buying_cost,selling_price,barcode,supplier_reference,discount,vat,lack_warning,vendor_quantity_access,instant_report,unit_measure_id,color_id,size_id,item_alias,is_composite,wholesale_price,supplier_ref,is_official,color_text_id,creation_date,user_id,sku_code,second_barcode,material_id,vat_on_sale,another_description,show_on_pos,depend_on_var_price,weight,fixed_price,fixed_price_value,second_wholesale_price,image_link) VALUES(" . $last_insert_id . ",'" . str_replace("'", "", $info["item_desc"]) . "', " . $info["item_cat"] . "," . $info["item_cost"] . ", " . $info["selling_price"] . ", " . $info["item_barcode"] . ", " . $info["supplier_id"] . ", " . $info["item_disc"] . ", " . $info["item_vat"] . "," . $info["lack_warning"] . "," . $info["vendor_access"] . "," . $info["instant_report"] . "," . $info["item_unit_measure"] . ",'" . $info["item_color"] . "'," . $info["item_size"] . "," . $info["item_alias"] . "," . $info["is_composite"] . "," . $info["wholesale_price"] . ",'" . $info["supplier_ref"] . "'," . $info["is_official"] . "," . $info["item_text_color"] . ",'" . my_sql::datetime_now() . "'," . $info["user_id"] . "," . $info["item_sku"] . "," . $info["item_barcode_second"] . "," . $info["material_id"] . "," . $info["vat_on_sale"] . "," . $info["another_description"] . "," . $info["show_on_pos"] . "," . $info["depend_on_var_price"] . "," . $info["weight"] . "," . $info["fixed_price"] . "," . $info["fixed_price_val"] . "," . $info["second_wholesale_price"] . ",'" . $info["image_link"] . "')");
            my_sql::query("insert into history_of_store_items(action,item_id,date_of_action,cost,vat,price,discount,description,item_category,supplier_reference,barcode,user_id) values('add_item'," . my_sql::get_mysqli_insert_id() . ",'" . my_sql::datetime_now() . "'," . $info["item_cost"] . "," . $info["item_vat"] . "," . $info["selling_price"] . "," . $info["item_disc"] . ",'" . $info["item_desc"] . "'," . $info["item_cat"] . "," . $info["supplier_id"] . "," . $info["item_barcode"] . "," . $info["user_id"] . ")");
        }
        $query_set_group_id = "update items set item_group=" . $last_insert_id . " where id=" . $last_insert_id;
        my_sql::query($query_set_group_id);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query_set_group_id);
        }
        return $last_insert_id;
    }
    public function sync_item_with_store($store_id)
    {
        $query = "select id from items where id not in (select item_id from store_items where store_id=" . $store_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $qry = "insert into store_items(store_id,item_id,quantity) values(" . $store_id . "," . $result[$i]["id"] . ",0)";
                my_sql::query($qry);
            }
        }
    }
    public function update_item($info)
    {
        $query = "update items set description='" . str_replace("'", "", $info["item_desc"]) . "',buying_cost='" . $info["item_cost"] . "',selling_price=" . $info["selling_price"] . ",barcode=" . $info["item_barcode"] . ",second_barcode=" . $info["item_barcode_second"] . ",supplier_reference='" . $info["supplier_id"] . "',item_category=" . $info["item_cat"] . ",discount=" . $info["item_disc"] . ",vat=" . $info["item_vat"] . ",lack_warning=" . $info["lack_warning"] . ",vendor_quantity_access=" . $info["vendor_access"] . ",instant_report=" . $info["instant_report"] . ",unit_measure_id=" . $info["item_unit_measure"] . ",color_id='" . $info["item_color"] . "',size_id=" . $info["item_size"] . ",item_alias=" . $info["item_alias"] . ",wholesale_price=" . $info["wholesale_price"] . ",second_wholesale_price=" . $info["second_wholesale_price"] . ",supplier_ref='" . $info["supplier_ref"] . "',is_official=" . $info["is_official"] . ",color_text_id=" . $info["item_text_color"] . ",sku_code=" . $info["item_sku"] . ",material_id=" . $info["material_id"] . ",vat_on_sale=" . $info["vat_on_sale"] . ",another_description=" . $info["another_description"] . ",show_on_pos=" . $info["show_on_pos"] . ",depend_on_var_price=" . $info["depend_on_var_price"] . ",weight=" . $info["weight"] . ",fixed_price=" . $info["fixed_price"] . ",fixed_price_value=" . $info["fixed_price_val"] . ",image_link='" . $info["image_link"] . "' where id=" . $info["id_to_edit"];
        $result = my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            if ($info["sync_only_cost"] == 0) {
                my_sql::global_query_sync($query);
            } else {
                $query__ = "update items set description='" . str_replace("'", "", $info["item_desc"]) . "',buying_cost='" . $info["item_cost"] . "',barcode=" . $info["item_barcode"] . ",second_barcode=" . $info["item_barcode_second"] . ",supplier_reference='" . $info["supplier_id"] . "',item_category=" . $info["item_cat"] . ",vat=" . $info["item_vat"] . ",lack_warning=" . $info["lack_warning"] . ",vendor_quantity_access=" . $info["vendor_access"] . ",instant_report=" . $info["instant_report"] . ",unit_measure_id=" . $info["item_unit_measure"] . ",color_id='" . $info["item_color"] . "',size_id=" . $info["item_size"] . ",item_alias=" . $info["item_alias"] . ",wholesale_price=" . $info["wholesale_price"] . ",second_wholesale_price=" . $info["second_wholesale_price"] . ",supplier_ref='" . $info["supplier_ref"] . "',is_official=" . $info["is_official"] . ",color_text_id=" . $info["item_text_color"] . ",sku_code=" . $info["item_sku"] . ",material_id=" . $info["material_id"] . ",vat_on_sale=" . $info["vat_on_sale"] . ",another_description=" . $info["another_description"] . ",show_on_pos=" . $info["show_on_pos"] . ",depend_on_var_price=" . $info["depend_on_var_price"] . ",weight=" . $info["weight"] . ",fixed_price=" . $info["fixed_price"] . ",fixed_price_value=" . $info["fixed_price_val"] . ",image_link='" . $info["image_link"] . "' where id=" . $info["id_to_edit"];
                my_sql::global_query_sync($query__);
            }
            my_sql::query("insert into history_of_store_items(action,item_id,date_of_action,cost,vat,price,discount,description,item_category,supplier_reference,barcode,user_id) values('update_item'," . $info["id_to_edit"] . ",'" . my_sql::datetime_now() . "'," . $info["item_cost"] . "," . $info["item_vat"] . "," . $info["selling_price"] . "," . $info["item_disc"] . ",'" . $info["item_desc"] . "'," . $info["item_cat"] . "," . $info["supplier_id"] . "," . $info["item_barcode"] . "," . $info["user_id"] . ")");
            self::update_global_average_cost_for_box($info["id_to_edit"]);
        }
        return $result;
    }
    public function delete_item($id)
    {
        $query = "update items set deleted=1 where id=" . $id;
        $result = my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $result;
    }
    public function getGroupId($item_id)
    {
        $query = "select item_group from items where id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["item_group"];
    }
    public function delete_all_items($ids)
    {
        $query = "select item_group from items where id in (" . $ids . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $gr_ids = "";
        $gr_exist = false;
        for ($i = 0; $i < count($result); $i++) {
            if (0 < $result[$i]["item_group"]) {
                $gr_exist = true;
                if ($i < count($result) - 1) {
                    $gr_ids .= $result[$i]["item_group"] . ",";
                } else {
                    $gr_ids .= $result[$i]["item_group"];
                }
            }
        }
        if ($gr_exist) {
            $query_delete = "update items set deleted=1 where id in (" . $ids . ") or item_group in ( " . $gr_ids . " )";
        } else {
            $query_delete = "update items set deleted=1 where id in (" . $ids . ")";
        }
        $result_delete = my_sql::query($query_delete);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query_delete);
        }
        return $result_delete;
    }
    public function getCategories()
    {
        $query = "select * from items_categories where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function check_if_sku_exist($sku_code)
    {
        $query = "select id from items where deleted=0 and sku_code='" . $sku_code . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function check_if_barcode_exist($barcodecode)
    {
        $query = "select id from items where deleted=0 and barcode='" . $barcodecode . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsPOS_AJAX($category_id, $sub_category_id, $filter, $only_count)
    {
        if (isset($filter["col_sort_index"])) {
            $filter["col_sort"] = $filter["columns"][$filter["col_sort_index"]];
        }
        $query_search_filters = "";
        if (0 < strlen($filter["search_filters"])) {
            $query_search_filters .= " and (";
            $index = 0;
            foreach ($filter["columns"] as $key => $value) {
                $query_search_filters .= " " . $value . " like '%" . $filter["search_filters"] . "%' ";
                $index++;
                if ($index < count($filter["columns"])) {
                    $query_search_filters .= " or ";
                }
            }
            $query_search_filters .= ")";
        }
        if (0 < count($filter["search_col_filters"])) {
            $query_search_filters = "";
            $index = 0;
            $query_search_filters .= " and (";
            foreach ($filter["search_col_filters"] as $key => $value) {
                $index++;
                $query_search_filters .= " " . $filter["columns"][$filter["search_col_filters"][$key]["colindex"] - 1] . " like '%" . $filter["search_col_filters"][$key]["colval"] . "%' ";
                if ($index < count($filter["search_col_filters"])) {
                    $query_search_filters .= " and ";
                }
            }
            $query_search_filters .= ")";
        }
        $item_category_ = "";
        if ($category_id == 0 && 0 < $sub_category_id) {
            $item_category_ = " and item_category=" . $sub_category_id;
        } else {
            if (0 < $category_id && $sub_category_id == 0) {
                $item_category_ = " and item_category in (select id from items_categories where parent=" . $category_id . ") ";
            } else {
                if (0 < $category_id && 0 < $sub_category_id) {
                    $item_category_ = " and item_category=" . $sub_category_id;
                }
            }
        }
        $limit = "";
        if ($only_count == 0) {
            $limit = $query_search_filters . " order by " . $filter["col_sort"] . " " . $filter["order_by"] . " limit " . $filter["start"] . "," . $filter["row_per_page"];
        } else {
            $limit = $query_search_filters . " ";
        }
        if ($filter["enable_wholasale"] == 0) {
            $selling_price = "it.selling_price";
        } else {
            $selling_price = "it.selling_price";
        }
        $price_after_discount = " CASE WHEN it.vat='1' THEN (it.selling_price-(it.discount*it.selling_price/100))*" . $filter["items_vat"] . " ELSE (it.selling_price-(it.discount*it.selling_price/100)) END ";
        $vat = " CASE WHEN it.vat='1'  THEN  CONCAT((it.vat-1)*" . $filter["items_vat"] . ",'%') ELSE '0 %' END ";
        $discount = "  CASE WHEN it.discount>0  THEN   CONCAT(it.discount,'%') ELSE '0 %' END ";
        $query = "select * from ( select it.item_category,it.deleted,st_it.quantity  as qty, it.id as item_id,it.sku_code as sku_code,CASE WHEN sku_code IS  NULL   THEN it.description  ELSE CONCAT(it.description,' # ' ,it.sku_code) END as desc_sku,it.barcode," . $selling_price . ",it.discount as discount," . $vat . " as item_vat," . $price_after_discount . "  as price_after_discount,  CASE WHEN it.size_id IS  NULL  THEN 'None' ELSE u_size.name    END as size_label,CASE WHEN it.color_text_id IS  NULL  THEN '' ELSE unit_c.name  END as color_text_label from items as it left join unit_size as u_size on u_size.id=it.size_id left join unit_color as unit_c on unit_c.id=it.color_text_id left join (select * from store_items  )   as st_it on st_it.item_id=it.id  ) as all_items where deleted=0 " . $item_category_ . " " . $limit;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsByBarcode_AJAX($barcode, $filter, $only_count)
    {
        if (isset($filter["col_sort_index"])) {
            $filter["col_sort"] = $filter["columns"][$filter["col_sort_index"]];
        }
        $query_search_filters = "";
        if (0 < strlen($filter["search_filters"])) {
            $query_search_filters .= " and (";
            $index = 0;
            foreach ($filter["columns"] as $key => $value) {
                $query_search_filters .= " " . $value . " like '%" . $filter["search_filters"] . "%' ";
                $index++;
                if ($index < count($filter["columns"])) {
                    $query_search_filters .= " or ";
                }
            }
            $query_search_filters .= ")";
        }
        if (0 < count($filter["search_col_filters"])) {
            $query_search_filters = "";
            $index = 0;
            $query_search_filters .= " and (";
            foreach ($filter["search_col_filters"] as $key => $value) {
                $index++;
                $query_search_filters .= " " . $filter["columns"][$filter["search_col_filters"][$key]["colindex"] - 1] . " like '%" . $filter["search_col_filters"][$key]["colval"] . "%' ";
                if ($index < count($filter["search_col_filters"])) {
                    $query_search_filters .= " and ";
                }
            }
            $query_search_filters .= ")";
        }
        $limit = "";
        if ($only_count == 0) {
            $limit = $query_search_filters . " order by " . $filter["col_sort"] . " " . $filter["order_by"] . " limit " . $filter["start"] . "," . $filter["row_per_page"];
        } else {
            $limit = $query_search_filters . " ";
        }
        if ($filter["enable_wholasale"] == 0) {
            $selling_price = "it.selling_price";
        } else {
            $selling_price = " CONCAT(round(it.selling_price,1),'/',round(it.wholesale_price,1)) as price ";
        }
        $price_after_discount = " CASE WHEN it.vat='1' THEN (it.selling_price-(it.discount*it.selling_price/100))*" . $filter["items_vat"] . " ELSE (it.selling_price-(it.discount*it.selling_price/100)) END ";
        $vat = " CASE WHEN it.vat='1'  THEN  CONCAT((it.vat-1)*" . $filter["items_vat"] . ",'%') ELSE '0 %' END ";
        $discount = "  CASE WHEN it.discount>0  THEN   CONCAT(it.discount,'%') ELSE '0 %' END ";
        $query = " select * from (select it.deleted,st_it.quantity  as qty, it.id as item_id,it.sku_code as sku_code,CASE WHEN sku_code IS  NULL   THEN it.description  ELSE CONCAT(it.description,' # ' ,it.sku_code) END as desc_sku,it.barcode," . $selling_price . ",it.discount as discount," . $vat . " as item_vat," . $price_after_discount . "  as price_after_discount,  CASE WHEN it.size_id IS  NULL  THEN 'None' ELSE u_size.name    END as size_label,CASE WHEN it.color_text_id IS  NULL  THEN '' ELSE unit_c.name  END as color_text_label from items as it left join unit_size as u_size on u_size.id=it.size_id left join unit_color as unit_c on unit_c.id=it.color_text_id left join (select * from store_items )   as st_it on st_it.item_id=it.id ) as all_items  where barcode=" . $barcode . " and deleted=0 " . " " . $limit;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function search($search, $page, $perPage, $checkHasMore = false)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $query = "SELECT " . $select . " FROM items where deleted=0 and (description like \"%" . $search . "%\" or barcode like \"%" . $search . "%\" or second_barcode like \"%" . $search . "%\" or sku_code like \"%" . $search . "%\"  or id =\"" . $search . "\") " . $limiter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
    public function search_but_no_boxes($search, $page, $perPage, $checkHasMore = false)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $query = "SELECT " . $select . " FROM items where deleted=0 and id not in (select composite_item_id from items_composite where qty>1) and (description like \"%" . $search . "%\" or barcode like \"%" . $search . "%\" or second_barcode like \"%" . $search . "%\" or sku_code like \"%" . $search . "%\"  or id =\"" . $search . "\") " . $limiter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
    public function getImageItem_by_id($item_id)
    {
        $query = "select * from items_images where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItems_by_filters($filters)
    {
        $condition = "";
        if ($filters["item_search"] && $filters["item_search"] != -1) {
            $condition .= " and id in (" . $filters["item_search"] . ")";
        }
        if (($filters["parent_category_id"] == 0 || 0 < $filters["parent_category_id"]) && 0 < $filters["category_id"]) {
            $condition .= " and item_category=" . $filters["category_id"];
        } else {
            if (0 < $filters["parent_category_id"] && $filters["category_id"] == 0) {
                $condition .= " and item_category in (select id from items_categories where parent=" . $filters["parent_category_id"] . ") ";
            }
        }
        $query = "select * from items where deleted=0 " . $condition;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_items_variation_by_filters()
    {
        $query = "select item_group,count(item_group) as num from items where deleted=0 group by item_group having count(item_group)>1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $tmp = array();
            for ($i = 0; $i < count($result); $i++) {
                array_push($tmp, $result[$i]["item_group"]);
            }
            $query_v = "select id,description,item_category,selling_price,discount,item_group,color_text_id,size_id from items where deleted=0 and id in (" . implode(",", $tmp) . ")";
            return my_sql::fetch_assoc(my_sql::query($query_v));
        }
        return $result;
    }
}
?>