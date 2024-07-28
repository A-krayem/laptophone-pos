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
class discountsModel
{
    public function getDiscounts()
    {
        $query = "select * from discounts where deleted=0 and category_parent_id is not null and category_id is not null";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getDiscounts_bygroups()
    {
        $query = "select * from discounts where deleted=0 and category_parent_id is null and category_id is null";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getDiscountsByItemId($id)
    {
        $query = "select * from discounts where deleted=0 and id in (select discount_id from discounts_details where item_id=" . $id . " order by id asc)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_items_under_discounts()
    {
        $query = "SELECT DISTINCT dd.item_id,dd.discount_value FROM discounts_details dd INNER JOIN ( SELECT item_id, MAX(id) AS latest FROM discounts_details dd where dd.discount_id in (select id from discounts dts where dts.deleted=0 and dts.disabled=0 and (('" . my_sql::datetime_now() . "'>=dts.start_date and '" . my_sql::datetime_now() . "'<=dts.end_date) or dts.never_end=1) ) GROUP BY item_id ) AS groupedd ON groupedd.item_id = dd.item_id AND groupedd.latest = dd.id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_discount($id)
    {
        $query = "select * from discounts where id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_discount($info)
    {
        $query = "update discounts set discount_name='" . $info["discount_name"] . "',start_date='" . $info["start_date"] . "',end_date='" . $info["end_date"] . "',category_parent_id=" . $info["parent_category_id"] . ",category_id=" . $info["category_id"] . ",discount_value='" . $info["discount_value"] . "' where id=" . $info["id_to_edit"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        self::update_discount_details($info["id_to_edit"], "update");
    }
    public function update_discount_group($info)
    {
        $query = "update discounts set discount_name='" . $info["discount_name"] . "',start_date='" . $info["start_date"] . "',end_date='" . $info["end_date"] . "',discount_value='" . $info["discount_value"] . "',group_id=" . $info["group_id"] . ",never_end=" . $info["never_end"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        self::update_discount_bygroup_details($info["id_to_edit"], "update");
    }
    public function add_new_discount($info)
    {
        $query = "insert into discounts(start_date,end_date,creation_date,discount_value,category_parent_id,category_id,discount_name) values('" . $info["start_date"] . "','" . $info["end_date"] . "','" . my_sql::datetime_now() . "','" . $info["discount_value"] . "'," . $info["parent_category_id"] . "," . $info["category_id"] . ",'" . $info["discount_name"] . "')";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync("insert into discounts(id,start_date,end_date,creation_date,discount_value,category_parent_id,category_id,discount_name) values(" . $last_insert_id . ",'" . $info["start_date"] . "','" . $info["end_date"] . "','" . my_sql::datetime_now() . "','" . $info["discount_value"] . "'," . $info["parent_category_id"] . "," . $info["category_id"] . ",'" . $info["discount_name"] . "')");
        }
        self::update_discount_details($last_insert_id, "add");
    }
    public function add_new_discount_group($info)
    {
        $query = "insert into discounts(start_date,end_date,creation_date,discount_value,discount_name,group_id,never_end) values('" . $info["start_date"] . "','" . $info["end_date"] . "','" . my_sql::datetime_now() . "','" . $info["discount_value"] . "','" . $info["discount_name"] . "'," . $info["group_id"] . "," . $info["never_end"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync("insert into discounts(id,start_date,end_date,creation_date,discount_value,discount_name,group_id,never_end) values(" . $last_insert_id . ",'" . $info["start_date"] . "','" . $info["end_date"] . "','" . my_sql::datetime_now() . "','" . $info["discount_value"] . "','" . $info["discount_name"] . "'," . $info["group_id"] . "," . $info["never_end"] . ")");
        }
        self::update_discount_bygroup_details($last_insert_id, "add");
    }
    public function delete_discount($id)
    {
        $query = "update discounts set deleted=1 where id=" . $id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        self::update_discount_details($id, "update");
    }
    public function update_discount_bygroup_details($discount_id, $action)
    {
        $query_discount = "select * from discounts where id=" . $discount_id;
        $result_discount = my_sql::fetch_assoc(my_sql::query($query_discount));
        if ($action == "update") {
            my_sql::query("delete from discounts_details where discount_id=" . $discount_id);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync("delete from discounts_details where discount_id=" . $discount_id);
            }
        }
        if ($result_discount[0]["deleted"] == 1) {
            my_sql::query("delete from discounts_details where discount_id=" . $discount_id);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync("delete from discounts_details where discount_id=" . $discount_id);
                return NULL;
            }
        } else {
            $query_items = "select id from items where item_group=" . $result_discount[0]["group_id"] . " and deleted=0";
            $result_items = my_sql::fetch_assoc(my_sql::query($query_items));
            for ($i = 0; $i < count($result_items); $i++) {
                $qry = "insert into discounts_details(item_id,store_id,discount_id,discount_value) values(" . $result_items[$i]["id"] . ",1," . $result_discount[0]["id"] . "," . $result_discount[0]["discount_value"] . ")";
                my_sql::query($qry);
                if (0 < my_sql::get_mysqli_rows_num()) {
                    my_sql::global_query_sync($qry);
                }
            }
        }
    }
    public function update_discount_details($discount_id, $action)
    {
        $query_discount = "select * from discounts where id=" . $discount_id;
        $result_discount = my_sql::fetch_assoc(my_sql::query($query_discount));
        if ($action == "update") {
            my_sql::query("delete from discounts_details where discount_id=" . $discount_id);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync("delete from discounts_details where discount_id=" . $discount_id);
            }
        }
        if ($result_discount[0]["deleted"] == 1) {
            my_sql::query("delete from discounts_details where discount_id=" . $discount_id);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync("delete from discounts_details where discount_id=" . $discount_id);
                return NULL;
            }
        } else {
            if ($result_discount[0]["category_id"] == 0) {
                $query_items = "select id from items where item_category in (select id from items_categories where parent=" . $result_discount[0]["category_parent_id"] . ") and deleted=0";
                $result_items = my_sql::fetch_assoc(my_sql::query($query_items));
                for ($i = 0; $i < count($result_items); $i++) {
                    $qry = "insert into discounts_details(item_id,store_id,discount_id,discount_value) values(" . $result_items[$i]["id"] . ",1," . $result_discount[0]["id"] . "," . $result_discount[0]["discount_value"] . ")";
                    my_sql::query($qry);
                    if (0 < my_sql::get_mysqli_rows_num()) {
                        my_sql::global_query_sync($qry);
                    }
                }
            } else {
                $query_items = "select id from items where item_category=" . $result_discount[0]["category_id"] . " and deleted=0";
                $result_items = my_sql::fetch_assoc(my_sql::query($query_items));
                for ($i = 0; $i < count($result_items); $i++) {
                    $qry = "insert into discounts_details(item_id,store_id,discount_id,discount_value) values(" . $result_items[$i]["id"] . ",1," . $result_discount[0]["id"] . "," . $result_discount[0]["discount_value"] . ")";
                    my_sql::query($qry);
                    if (0 < my_sql::get_mysqli_rows_num()) {
                        my_sql::global_query_sync($qry);
                    }
                }
            }
        }
    }
    public function get_items_under_discount($id)
    {
        $query = "select * from discounts where id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $result_items = array();
        if ($result[0]["category_parent_id"] != NULL) {
            if (0 < $result[0]["category_id"]) {
                $query_items = "select * from items where item_category=" . $result[0]["category_id"] . " and deleted=0";
                $result_items = my_sql::fetch_assoc(my_sql::query($query_items));
                return $result_items;
            }
            $query_items = "select * from items where item_category in (select id from items_categories where parent=" . $result[0]["category_parent_id"] . ") and deleted=0";
            $result_items = my_sql::fetch_assoc(my_sql::query($query_items));
            return $result_items;
        }
        return $result_items;
    }
    public function get_items_under_discount_group($id)
    {
        $query = "select * from discounts where id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query_items = "select * from items where item_group=" . $result[0]["group_id"] . " and deleted=0";
        $result_items = my_sql::fetch_assoc(my_sql::query($query_items));
        return $result_items;
    }
}

?>