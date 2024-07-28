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
class outside_connection_Model
{
    public function recovery_items_table($warehouse_connection, $store_connection)
    {
        $query = "select * from items";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $warehouse_connection));
        for ($i = 0; $i < count($result); $i++) {
            $query_update = "update items set " . "description='" . $result[$i]["description"] . "'," . "item_category='" . $result[$i]["item_category"] . "'," . "buying_cost='" . $result[$i]["buying_cost"] . "'," . "selling_price='" . $result[$i]["selling_price"] . "', " . "barcode='" . $result[$i]["barcode"] . "', " . "supplier_reference='" . $result[$i]["supplier_reference"] . "', " . "discount='" . $result[$i]["discount"] . "', " . "vat='" . $result[$i]["vat"] . "', " . "lack_warning='" . $result[$i]["lack_warning"] . "', " . "vendor_quantity_access='" . $result[$i]["vendor_quantity_access"] . "', " . "instant_report='" . $result[$i]["instant_report"] . "', " . "unit_measure_id='" . $result[$i]["unit_measure_id"] . "', " . "color_id='" . $result[$i]["color_id"] . "', " . "size_id='" . $result[$i]["size_id"] . "', " . "deleted='" . $result[$i]["deleted"] . "', " . "item_alias='" . $result[$i]["item_alias"] . "', " . "is_composite='" . $result[$i]["is_composite"] . "', " . "wholesale_price='" . $result[$i]["wholesale_price"] . "', " . "supplier_ref='" . $result[$i]["supplier_ref"] . "', " . "is_official='" . $result[$i]["is_official"] . "', " . "color_text_id='" . $result[$i]["color_text_id"] . "', " . "creation_date='" . $result[$i]["creation_date"] . "', " . "user_id='0', " . "sku_code='" . $result[$i]["sku_code"] . "', " . "second_barcode='" . $result[$i]["second_barcode"] . "', " . "material_id='" . $result[$i]["material_id"] . "', " . "vat_on_sale='" . $result[$i]["vat_on_sale"] . "', " . "material_id='" . $result[$i]["material_id"] . "', " . "item_group='" . $result[$i]["item_group"] . "', " . "another_description='" . $result[$i]["another_description"] . "' " . "where id=" . $result[$i]["id"];
            my_sql::custom_connection_query($query_update, $store_connection);
        }
    }
    public function getAllStockOfLocation($connection)
    {
        $query = "select * from store_items";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $connection));
        return $result;
    }
    public function getQtyOfItem($item_id, $connection)
    {
        $query = "select * from store_items where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $connection));
        return $result;
    }
    public function getAllItemsWithQTY_by_group_in_location($connection)
    {
        $query = "select it.item_group,COALESCE(sum(si.quantity), 0) as qty from items it,store_items si where it.id=si.item_id and deleted=0 group by it.item_group";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $connection));
        return $result;
    }
    public function getAllItemsWithQTY_inlocation($connection)
    {
        $query = "select it.id,si.quantity as quantity from items it,store_items si where it.id=si.item_id and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $connection));
        return $result;
    }
    public function getAllItemsWasSoldByInvoice_id_switch($custom_connection, $invoice_id)
    {
        $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv.id as invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and inv_it.invoice_id=" . $invoice_id . " order by inv.id desc";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $custom_connection));
        return $result;
    }
    public function get_remote_invoice_items($custom_connection, $invoice_id)
    {
        $query = "select * from invoice_items where invoice_id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $custom_connection));
        return $result;
    }
    public function get_remote_invoice($custom_connection, $invoice_id)
    {
        $query = "select * from invoices where id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $custom_connection));
        return $result;
    }
}

?>