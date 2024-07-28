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
class posModel
{
    public function add_item_to_interface($info)
    {
        $query = "update store_items set on_pos_interface=1 where item_id=" . $info["item_id"];
        $result = my_sql::query($query);
        return $result;
    }
    public function remove_item_to_interface($info)
    {
        $query = "update store_items set on_pos_interface=0 where item_id=" . $info["item_id"];
        $result = my_sql::query($query);
        return $result;
    }
    public function monitor_pos_items($item_id)
    {
        my_sql::query("insert into pos_monitor(item_id,creation_date,created_by,cashbox_id) values(" . $item_id . ",'" . my_sql::datetime_now() . "'," . $_SESSION["id"] . "," . $_SESSION["cashbox_id"] . ")");
    }
    public function monitor_pos_items_adv($item_id, $qty)
    {
        my_sql::query("insert into pos_monitor(item_id,creation_date,created_by,cashbox_id,qty) values(" . $item_id . ",'" . my_sql::datetime_now() . "'," . $_SESSION["id"] . "," . $_SESSION["cashbox_id"] . "," . $qty . ")");
    }
    public function getItemsForInstantReport($store_id)
    {
        $query = "select it.id,it.description,si.quantity from items it inner join store_items si where it.id=si.item_id and it.instant_report=1 and si.store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_sub_categories_in_array($subcats_array)
    {
        $query = "select * from items_categories where id in (" . implode(",", $subcats_array) . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_custom_items($store_id, $settings)
    {
        $query = "select item_id,it.description,it.selling_price,it.discount,si.pos_col_users,it.item_category  from store_items si join items as it on it.id=si.item_id and si.on_pos_interface=1 and si.store_id=" . $store_id . " and it.deleted=0 order by si.pos_order asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getNonBarcodeItems($store_id)
    {
        $query = "select item_id,it.description,it.selling_price,it.discount,si.quantity,it.unit_measure_id from store_items si join items as it on it.id=si.item_id and it.barcode IS NULL and it.deleted=0 and si.store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsBarcoded($store_id, $settings)
    {
        $query = "select item_id,it.description,it.selling_price,it.discount,si.quantity,it.unit_measure_id from store_items si join items as it on it.id=si.item_id and it.barcode IS NOT NULL and si.store_id=" . $store_id . " and it.barcode NOT LIKE '" . $settings["plu_prefix"] . "%' and it.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsPurchasedList($info, $date, $customer_id)
    {
        if ($_SESSION["role"] == 4) {
            $query = "select inv.id,inv_it.id as inv_item_id,inv_it.id as item_id,inv_it.final_price_disc_qty,inv_it.description as descr,inv_it.qty,inv_it.item_id,inv.customer_id,inv.creation_date,inv.closed,inv.auto_closed from invoice_items inv_it inner join invoices inv on inv.id=inv_it.invoice_id and inv_it.user_role=" . $_SESSION["role"] . " and date(inv.creation_date) = '" . $date . "' and inv.other_branche=0";
        } else {
            $query = "select inv.id,inv_it.id as inv_item_id,inv_it.id as item_id,inv_it.final_price_disc_qty,inv_it.description as descr,inv_it.qty,inv_it.item_id,inv.customer_id,inv.creation_date,inv.closed,inv.auto_closed from invoice_items inv_it inner join invoices inv on inv.id=inv_it.invoice_id and date(inv.creation_date) = '" . $date . "' and inv.other_branche=0";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPurchasedListOfCustomer($info, $customer_id)
    {
        $query = "select inv.id,inv_it.id as inv_item_id,inv_it.id as item_id,inv_it.final_price_disc_qty,inv_it.description as descr,inv_it.qty,inv_it.item_id,inv.customer_id,inv.creation_date,inv.closed from invoice_items inv_it inner join invoices inv on inv.id=inv_it.invoice_id and other_branche=0 and inv.customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>