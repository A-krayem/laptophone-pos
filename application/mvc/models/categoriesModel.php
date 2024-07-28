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
class categoriesModel
{
    public function getAllCategories()
    {
        $query = "select * from items_categories where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllCategoriesEvenDeleted()
    {
        $query = "select * from items_categories";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function move_subcategory_action($id_from, $id_to)
    {
        $query_log = "select id from items where item_category=" . $id_from;
        $result_log = my_sql::fetch_assoc(my_sql::query($query_log));
        $lg = array();
        for ($i = 0; $i < count($result_log); $i++) {
            $lg[$i]["id"] = $result_log[$i]["id"];
        }
        $query_lg = "insert into items_categories_mov_logs(creation_date,logs,from_subcategory_id,to_subcategory_id) values(" . "now(),'" . json_encode($lg) . "','" . $id_from . "','" . $id_to . "')";
        my_sql::query($query_lg);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query_lg);
        }
        $query = "update items set item_category=" . $id_to . " where item_category=" . $id_from;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function items_count_in_subcategory($subcategory_id)
    {
        $query = "select count(id) as num from items where deleted=0 and item_category =" . $subcategory_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function checkCategory($category_text, $parent_id)
    {
        $query = "select * from items_categories where deleted=0 and parent=" . $parent_id . " and description='" . $category_text . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function checkParentCategory($pcategory_text)
    {
        $query = "select * from items_categories_parents where deleted=0 and name='" . $pcategory_text . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllParentCategories()
    {
        $query = "select * from items_categories_parents where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllParentCategoriesEvenDeleted()
    {
        $query = "select * from items_categories_parents";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllCategoriesByParent($id)
    {
        $query = "select * from items_categories where deleted=0 and parent=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_category($id)
    {
        $query_items_exist = "select count(id) as num from items where item_category=" . $id . " and deleted=0";
        $result_items_exist = my_sql::fetch_assoc(my_sql::query($query_items_exist));
        if ($result_items_exist[0]["num"] == 0) {
            $query = "update items_categories set deleted=1 where id=" . $id;
            my_sql::query($query);
            if (0 < my_sql::get_mysqli_rows_num()) {
                my_sql::global_query_sync($query);
            }
            return true;
        }
        return false;
    }
    public function delete_parent_category($id)
    {
        $query_subcategories_exist = "select count(id) as num from items_categories where parent=" . $id . " and deleted=0";
        $result_subcategories_exist = my_sql::fetch_assoc(my_sql::query($query_subcategories_exist));
        if ($result_subcategories_exist[0]["num"] == 0) {
            $query = "update items_categories_parents set deleted=1 where deny_delete=0 and id=" . $id;
            my_sql::query($query);
            $rows_num = my_sql::get_mysqli_rows_num();
            if (0 < $rows_num) {
                my_sql::global_query_sync($query);
                return $rows_num;
            }
            return -1;
        }
        return 0;
    }
    public function add_new_category($info)
    {
        $query = "insert into items_categories(description,parent) values('" . $info["cat_desc"] . "'," . $info["parent_cat_id"] . ")";
        my_sql::query($query);
        $mysqli_insert_id = 0;
        if (0 < my_sql::get_mysqli_rows_num()) {
            $mysqli_insert_id = my_sql::get_mysqli_insert_id();
            my_sql::global_query_sync("insert into items_categories(id,description,parent) values(" . $mysqli_insert_id . ",'" . $info["cat_desc"] . "'," . $info["parent_cat_id"] . ")");
        }
        return $mysqli_insert_id;
    }
    public function parent_category_is_exist($name)
    {
        $query = "select count(id) as num from items_categories_parents where name='" . $name . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function subcategory_is_exist($name, $parent_cat_id)
    {
        $query = "select count(id) as num from items_categories where parent=" . $parent_cat_id . " and description='" . $name . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function add_new_parent_category($info)
    {
        $query = "insert into items_categories_parents(name) values('" . $info["cat_desc"] . "')";
        my_sql::query($query);
        $mysqli_insert_id = 0;
        if (0 < my_sql::get_mysqli_rows_num()) {
            $mysqli_insert_id = my_sql::get_mysqli_insert_id();
            my_sql::global_query_sync("insert into items_categories_parents(id,name) values(" . $mysqli_insert_id . ",'" . $info["cat_desc"] . "')");
        }
        return $mysqli_insert_id;
    }
    public function get_category($id)
    {
        $query = "select * from items_categories where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_parent_category($id)
    {
        $query = "select * from items_categories_parents where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalItems($id)
    {
        $query = "SELECT item_category,count(item_category) as num FROM items where deleted = 0 group by item_category";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalSubCategories($id)
    {
        $query = "select count(id) as num from items_categories where deleted=0 and parent=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_category($info)
    {
        $query = "update items_categories set description='" . $info["cat_desc"] . "',parent=" . $info["parent_cat_id"] . " where id=" . $info["id_to_edit"];
        $result = my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $result;
    }
    public function update_parent_category($info)
    {
        $query = "update items_categories_parents set name='" . $info["cat_desc"] . "' where id=" . $info["id_to_edit"];
        $result = my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $result;
    }
    public function getAllSubCategoriesQty($store_id)
    {
        $query = "SELECT sum(quantity) as sum_qty,it.item_category from items it,store_items si where si.item_id=it.id and it.deleted=0 and si.store_id=" . $store_id . " group by it.item_category ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>