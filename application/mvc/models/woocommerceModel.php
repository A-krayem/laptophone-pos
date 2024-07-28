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
class woocommerceModel
{
    public function add_update_woocommerce_item($info)
    {
        $woocommerce_pos_item_info = self::get_woocommerce_by_pos_item_id($info["item_id"], 0);
        if (0 < count($woocommerce_pos_item_info)) {
            $query = "update  woocommerce_items set response ='" . $info["response"] . "',sync_date='" . my_sql::datetime_now() . "' where  pos_item_id='" . $info["item_id"] . "' and woocommerce_item_id='" . $info["product_id"] . "' and deleted=0";
        } else {
            $query = "insert into woocommerce_items(pos_item_id,woocommerce_item_id,response)  values('" . $info["item_id"] . "','" . $info["product_id"] . "','" . $info["response"] . "')";
        }
        $result = my_sql::query($query);
        return $result;
    }
    public function get_woocommerce_by_pos_item_id($item_id, $is_variable_item)
    {
        $query = "select * from woocommerce_items  where deleted=0 and pos_item_id=" . $item_id . " and is_item_variation=" . $is_variable_item;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_update_POS_parent_categories($info)
    {
        $woocommerce_pos_cat_info = self::get_woocommerce_parent_category_by_pos_cat_id($info["pos_p_category_id"]);
        if (0 < count($woocommerce_pos_cat_info)) {
            $query = "update  woocommerce_parent_categories set response ='" . $info["response"] . "', sync_date='" . my_sql::datetime_now() . "' where  pos_parent_category_id='" . $info["pos_p_category_id"] . "' and woocommerce_parent_category_id='" . $info["woc_p_category_id"] . "'  and deleted=0";
        } else {
            $query = "insert into woocommerce_parent_categories(pos_parent_category_id,woocommerce_parent_category_id,response,sync_date)  values('" . $info["pos_p_category_id"] . "','" . $info["woc_p_category_id"] . "','" . $info["response"] . "','" . my_sql::datetime_now() . "')";
        }
        $result = my_sql::query($query);
        return $result;
    }
    public function get_woocommerce_parent_category_by_pos_cat_id($p_category_id)
    {
        $query = "select * from woocommerce_parent_categories  where pos_parent_category_id=" . $p_category_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_update_POS_categories($info)
    {
        $woocommerce_pos_cat_info = self::get_woocommerce_category_by_pos_cat_id($info["pos_category_id"]);
        if (0 < count($woocommerce_pos_cat_info)) {
            $query = "update  woocommerce_categories set response ='" . $info["response"] . "' , sync_date='" . my_sql::datetime_now() . "' where  pos_category_id='" . $info["pos_category_id"] . "' and woocommerce_category_id='" . $info["woc_category_id"] . "'  and deleted=0";
        } else {
            $query = "insert into woocommerce_categories(pos_category_id,woocommerce_category_id,response,sync_date)  values('" . $info["pos_category_id"] . "','" . $info["woc_category_id"] . "','" . $info["response"] . "','" . my_sql::datetime_now() . "')";
        }
        $result = my_sql::query($query);
        return $result;
    }
    public function get_woocommerce_category_by_pos_cat_id($category_id)
    {
        $query = "select * from woocommerce_categories  where pos_category_id=" . $category_id . "  and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_woocommerce_item($item_id)
    {
        $query = "update woocommerce_items   set  deleted=1 where  pos_item_id=" . $item_id . " and is_item_variation=0";
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_woocommerce_pos_category($category_id)
    {
        $query = "update  woocommerce_categories set  deleted=1  where pos_category_id=" . $category_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_woocommerce_parent_category($p_category_id)
    {
        $query = "update  woocommerce_parent_categories set  deleted=1  where pos_parent_category_id=" . $p_category_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function get_all_woocommerce_items($is_variation_item)
    {
        $query = "select * from woocommerce_items  where deleted=0 and is_item_variation=" . $is_variation_item;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_woocommerce_items_logs($info)
    {
        $query = "insert into woocommerce_items_logs(created_by,related_to_pos_item_id,woocommerce_item_id,woocommerce_id,description,creation_date) values('" . $info["operator_id"] . "','" . $info["pos_item_id"] . "','" . $info["woo_item_id"] . "','" . $info["woo_id"] . "','" . $info["description"] . "','" . my_sql::datetime_now() . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function getAll_POS_CategoriesByParent($filter)
    {
        $filter_parent_category = "";
        if (isset($filter["parent_category_id"]) && 0 < $filter["parent_category_id"]) {
            $filter_parent_category = " and parent=" . $filter["parent_category_id"];
        }
        $query = "select * from items_categories where deleted=0 " . $filter_parent_category;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_woocommerce_categories_logs($info)
    {
        $query = "insert into woocommerce_categories_logs(created_by,related_to_pos_category_id,woocommerce_category_id,woocommerce_id,description,creation_date) values('" . $info["operator_id"] . "','" . $info["pos_category_id"] . "','" . $info["woo_category_id"] . "','" . $info["woo_id"] . "','" . $info["description"] . "','" . my_sql::datetime_now() . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function get_woo_categories_by_woo_category_id($woo_category_id)
    {
        $query = "select * from woocommerce_categories  where woocommerce_category_id=" . $woo_category_id . "  and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_woo_pcategories_by_woo_pcategory_id($woo_pcategory_id)
    {
        $query = "select * from woocommerce_parent_categories  where woocommerce_parent_category_id=" . $woo_pcategory_id . "  and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_woo_category_by_woo_category_id($woo_category_id)
    {
        $query = "update  woocommerce_categories set  deleted=1  where woocommerce_category_id=" . $woo_category_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function update_is_sync_category_id($category_id, $is_synced)
    {
        $query = "update  items_categories set  is_synced= " . $is_synced . " where id=" . $category_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function update_is_sync_parentcategory_id($p_category_id, $is_synced)
    {
        $query = "update  items_categories_parents set  is_synced= " . $is_synced . "  where id=" . $p_category_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function add_woocommerce_parent_categories_logs($info)
    {
        $query = "insert into woocommerce_parent_categories_logs(created_by,related_to_pos_parent_category_id,woocommerce_parent_category_id,woocommerce_id,description,creation_date) values('" . $info["operator_id"] . "','" . $info["pos_parent_category_id"] . "','" . $info["woo_parent_category_id"] . "','" . $info["woo_id"] . "','" . $info["description"] . "','" . my_sql::datetime_now() . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function get_all_woocommerce_parent_categories($from_woocommerce)
    {
        $from_woocommerce_filter = "";
        if ($from_woocommerce != -1) {
            $from_woocommerce_filter = " and  from_woocommerce= " . $from_woocommerce;
        }
        $query = "select * from woocommerce_parent_categories  where  deleted=0 " . $from_woocommerce_filter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_woocommerce_categories()
    {
        $query = "select * from woocommerce_categories  where  deleted=0 ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_update_woocommerce_parent_categories($info)
    {
        if (0 < $info["woo_id"]) {
            $query = "update  woocommerce_parent_categories set from_woocommerce='" . $info["from_woocommerce"] . "', response ='" . $info["response"] . "', sync_date='" . my_sql::datetime_now() . "' where  id='" . $info["woo_id"] . "' ";
            $result = my_sql::query($query);
            $last_id = my_sql::get_mysqli_rows_num();
        } else {
            $query = "insert into woocommerce_parent_categories(pos_parent_category_id,woocommerce_parent_category_id,response,sync_date,from_woocommerce)  values('" . $info["pos_p_category_id"] . "','" . $info["woc_p_category_id"] . "','" . $info["response"] . "','" . my_sql::datetime_now() . "','" . $info["from_woocommerce"] . "')";
            $result = my_sql::query($query);
            $last_id = my_sql::get_mysqli_insert_id();
        }
        if (0 < $last_id) {
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["pos_parent_category_id"] = $info["pos_p_category_id"];
            $logs_info["description"] = "Sync From Woocommerce Parent Category ID (" . $info["woc_p_category_id"] . ")";
            $logs_info["woo_parent_category_id"] = $info["woc_p_category_id"];
            if (0 < $info["woo_id"]) {
                $logs_info["woo_id"] = $info["woo_id"];
            } else {
                $logs_info["woo_id"] = $last_id;
            }
            self::add_woocommerce_parent_categories_logs($logs_info);
        }
        return $result;
    }
    public function add_update_woocommerce_categories($info)
    {
        if (0 < $info["woo_id"]) {
            $query = "update  woocommerce_categories set from_woocommerce='" . $info["from_woocommerce"] . "', response ='" . $info["response"] . "', sync_date='" . my_sql::datetime_now() . "',woo_parent_id='" . $info["parent"] . "' where  id='" . $info["woo_id"] . "' ";
            $result = my_sql::query($query);
            $last_id = my_sql::get_mysqli_rows_num();
        } else {
            $query = "insert into woocommerce_categories(pos_category_id,woocommerce_category_id,response,sync_date,from_woocommerce,woo_parent_id)  values('" . $info["pos_category_id"] . "','" . $info["woc_category_id"] . "','" . $info["response"] . "','" . my_sql::datetime_now() . "','" . $info["from_woocommerce"] . "','" . $info["parent"] . "')";
            $result = my_sql::query($query);
            $last_id = my_sql::get_mysqli_insert_id();
        }
        if (0 < $last_id) {
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["pos_category_id"] = $info["pos_category_id"];
            $logs_info["description"] = "Sync From Woocommerce Category ID (" . $info["woc_category_id"] . ")";
            $logs_info["woo_category_id"] = $info["woc_category_id"];
            if (0 < $info["woo_id"]) {
                $logs_info["woo_id"] = $info["woo_id"];
            } else {
                $logs_info["woo_id"] = $last_id;
            }
            self::add_woocommerce_categories_logs($logs_info);
        }
        return $result;
    }
    public function get_all_woocommerce_categories_with_filters($info)
    {
        $from_woocommerce_filter = "";
        if ($info["from_woocommerce"] != -1) {
            $from_woocommerce_filter = " and  from_woocommerce= " . $info["from_woocommerce"];
        }
        $parent_category_filter = "";
        if ($info["parent_category_id"] != -1) {
            $parent_category_filter = " and  woo_parent_id= " . $info["parent_category_id"];
        }
        $query = "select * from woocommerce_categories  where  deleted=0 " . $from_woocommerce_filter . " " . $parent_category_filter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_woo_category_by_woo_pcategory_id($woo_pcategory_id)
    {
        $query = "update  woocommerce_parent_categories set  deleted=1  where woocommerce_parent_category_id=" . $woo_pcategory_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_woocommerce_parent_categories($p_category_ids)
    {
        $query = "update  woocommerce_parent_categories set  deleted=1  where woocommerce_parent_category_id  in ('" . implode("','", $p_category_ids) . "')";
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_woocommerce_categories($category_ids)
    {
        $query = "update  woocommerce_categories set  deleted=1  where woocommerce_category_id  in ('" . implode("','", $category_ids) . "')";
        $result = my_sql::query($query);
        return $result;
    }
    public function unsync_pos_woo_parent_categories($p_category_ids)
    {
        $query = "update  items_categories_parents set  is_synced=0 where id in ('" . implode("','", $p_category_ids) . "')";
        $result = my_sql::query($query);
        return $result;
    }
    public function unsync_pos_woo_categories($category_ids)
    {
        $query = "update  items_categories set  is_synced=0 where id in ('" . implode("','", $category_ids) . "')";
        $result = my_sql::query($query);
        return $result;
    }
    public function get_pos_pcategories_by_woo_pcategories_by_ids($p_category_ids)
    {
        $query = "select * from woocommerce_parent_categories  where  deleted=0 and pos_parent_category_id!=0 and  woocommerce_parent_category_id in ('" . implode("','", $p_category_ids) . "')";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function search($search, $page, $perPage, $checkHasMore = false, $is_variable)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $variable_item_filter = "  AND id IN ( SELECT item_group FROM items WHERE deleted = 0 GROUP BY item_group HAVING COUNT(item_group) > 1 )";
        if ($is_variable == 0) {
            $variable_item_filter = "  AND id IN ( SELECT item_group FROM items WHERE deleted = 0 GROUP BY item_group HAVING COUNT(item_group) = 1 ) ";
        }
        $query = "SELECT " . $select . " FROM items where deleted=0 " . $variable_item_filter . " and (description like \"%" . $search . "%\" or barcode like \"%" . $search . "%\" or second_barcode like \"%" . $search . "%\" or sku_code like \"%" . $search . "%\"  or id =\"" . $search . "\") " . $limiter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
    public function search_but_no_boxes($search, $page, $perPage, $checkHasMore = false, $is_variable)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $variable_item_filter = "  AND id IN ( SELECT item_group FROM items WHERE deleted = 0 GROUP BY item_group HAVING COUNT(item_group) > 1 )";
        if ($is_variable == 0) {
            $variable_item_filter = "  AND id IN ( SELECT item_group FROM items WHERE deleted = 0 GROUP BY item_group HAVING COUNT(item_group) = 1 ) ";
        }
        $query = "SELECT " . $select . " FROM items where deleted=0 " . $variable_item_filter . " and id not in (select composite_item_id from items_composite where qty>1) and (description like \"%" . $search . "%\" or barcode like \"%" . $search . "%\" or second_barcode like \"%" . $search . "%\" or sku_code like \"%" . $search . "%\"  or id =\"" . $search . "\") " . $limiter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
    public function add_update_woocommerce_item_variation($info)
    {
        $woocommerce_pos_item_info = self::get_woocommerce_by_pos_item_id($info["item_id"], 1);
        if (0 < count($woocommerce_pos_item_info)) {
            $query = "update  woocommerce_items set response ='" . $info["response"] . "',sync_date='" . my_sql::datetime_now() . "' where  pos_item_id='" . $info["item_id"] . "' and woocommerce_item_id='" . $info["product_id"] . "' and deleted=0   and is_item_variation=" . $info["is_item_variation"];
        } else {
            $query = "insert into woocommerce_items(pos_item_id,woocommerce_item_id,response,is_item_variation,item_group)  values('" . $info["item_id"] . "','" . $info["product_id"] . "','" . $info["response"] . "','" . $info["is_item_variation"] . "','" . $info["item_group"] . "')";
        }
        $result = my_sql::query($query);
        return $result;
    }
    public function get_all_item_group_variations($item_group)
    {
        $query = "select  GROUP_CONCAT(pos_item_id SEPARATOR ',') AS item_ids,GROUP_CONCAT(woocommerce_item_id SEPARATOR ',') AS woo_item_ids from woocommerce_items where deleted=0 and  item_group=" . $item_group;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_item_group_variations($item_group)
    {
        $variations = self::get_all_item_group_variations($item_group);
        $query = "update  woocommerce_items set  deleted=1 where  item_group=" . $item_group;
        $result = my_sql::query($query);
        $last_id = my_sql::get_mysqli_rows_num();
        if (0 < $last_id) {
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["pos_item_id"] = 0;
            $logs_info["description"] = "Delete Item Variations IDs (" . $variations[0]["item_ids"] . ")  of Woocommerce IDs (" . $variations[0]["woo_item_ids"] . ")  of Product ID (" . $item_group . ") ";
            $logs_info["woo_item_id"] = 0;
            $logs_info["woo_id"] = 0;
            self::add_woocommerce_items_logs($logs_info);
        }
    }
    public function get_woocommerce_by_woo_item_id($item_id)
    {
        $query = "select * from woocommerce_items  where deleted=0 and woocommerce_item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_woocommerce_items()
    {
        $query = "select * from woocommerce_items  where deleted=0 and woocommerce_item_id > 0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>