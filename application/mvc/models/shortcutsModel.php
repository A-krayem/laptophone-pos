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
class shortcutsModel
{
    public function add_new_shortcut($info)
    {
        $query = "insert into shortcuts(description,creation_date,created_by,derived_from_group) values('" . $info["shortcut_name"] . "','" . my_sql::datetime_now() . "'," . $_SESSION["id"] . "," . $info["derived_from_group"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function add_item_to_shortcut($shortcut_id, $item_id)
    {
        $query = "insert into shortcuts_details(item_id,shortcut_id) values('" . $item_id . "'," . $shortcut_id . ")";
        my_sql::query($query);
    }
    public function add_new_item_qty_to_shortcut($shortcut_id, $item_id, $qty)
    {
        $query = "insert into shortcuts_details(item_id,shortcut_id,qty) values('" . $item_id . "'," . $shortcut_id . "," . $qty . ")";
        my_sql::query($query);
    }
    public function set_group_as_shortcut($item_id)
    {
        $query_item = "select * from items where id=" . $item_id;
        $result_item = my_sql::fetch_assoc(my_sql::query($query_item));
        $group_id = $result_item[0]["item_group"];
        if (0 < $group_id) {
            $info = array();
            $info["shortcut_name"] = $result_item[0]["description"];
            $info["derived_from_group"] = 0;
            $shortcut_id = self::add_new_shortcut($info);
            $query_group = "select * from items where item_group=" . $group_id;
            $result_group = my_sql::fetch_assoc(my_sql::query($query_group));
            for ($i = 0; $i < count($result_group); $i++) {
                self::add_item_to_shortcut($shortcut_id, $result_group[$i]["id"]);
            }
            return $shortcut_id;
        }
        return 0;
    }
    public function get_all_shortcuts()
    {
        $query = "select * from shortcuts where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_qty()
    {
        $query = "SELECT COALESCE(sum(si.quantity), 0) as total_stock ,sd.shortcut_id FROM `shortcuts_details` sd left join store_items si on si.item_id=sd.`item_id` where si.quantity IS NOT NULL group by sd.shortcut_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_shortcuts_by_group($id)
    {
        $query_grp = "select item_group from items where id=" . $id;
        $result_grp = my_sql::fetch_assoc(my_sql::query($query_grp));
        $query = "select * from shortcuts where deleted=0 and derived_from_group=" . $result_grp[0]["item_group"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_shortcut($shotcut_id)
    {
        $query = "update shortcuts set deleted=1 where id=" . $shotcut_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function delete_shortcut_by_group_id($group_id)
    {
        $query = "update shortcuts set deleted=1 where derived_from_group=" . $group_id;
        $result = my_sql::query($query);
        return $result;
    }
    public function get_all_items_in_shortcut($shotcut_id)
    {
        $query = "select sd.id,sd.qty,it.barcode,it.description,it.id as item_id,it.size_id,it.color_text_id from shortcuts_details sd,items it where it.id=sd.item_id and sd.shortcut_id=" . $shotcut_id . " and sd.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_items_to_add_to_shortcut($shortcut_id)
    {
        $query = "select id,barcode,description,color_text_id,size_id from items where deleted=0 and id not in (select item_id from shortcuts_details where deleted=0 and shortcut_id=" . $shortcut_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_item_from_shortcut($id)
    {
        $query = "update shortcuts_details set deleted=1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function update_item_qty_shortcut($id, $qty)
    {
        $query = "update shortcuts_details set qty=" . $qty . " where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
}

?>