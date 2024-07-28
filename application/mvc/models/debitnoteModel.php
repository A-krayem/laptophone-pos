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
class debitnoteModel
{
    public function debit_notes($date_range)
    {
        $query = "select * from debit_notes where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_item_to_debit_note($debit_note_id, $item_id)
    {
        $query = "insert into debit_notes_details(item_id,qty,price,debit_note_id) values(" . $item_id . ",0,0," . $debit_note_id . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_item_debit_note($item_id)
    {
    }
    public function dn_price_changed($dn_details_id, $price)
    {
        $query = "update debit_notes_details set price=" . $price . " where id=" . $dn_details_id;
        my_sql::query($query);
    }
    public function dn_qty_changed($dn_details_id, $qty)
    {
        $query = "update debit_notes_details set qty=" . $qty . " where id=" . $dn_details_id;
        my_sql::query($query);
    }
    public function get_item_details_from_dn($debit_note_id)
    {
        $query = "select * from debit_notes_details where id=" . $debit_note_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function generate_debit_note($store_id, $created_by)
    {
        $query = "insert into debit_notes(creation_date,store_id,created_by,on_the_fly) values('" . my_sql::datetime_now() . "'," . $store_id . "," . $created_by . ",1)";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_debit_note_details($debit_note_id)
    {
        $query = "select * from debit_notes_details where debit_note_id=" . $debit_note_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function sum_debit_notes($date_range)
    {
        $query = "select COALESCE(sum(debit_value), 0) as sum from debit_notes where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function pi_return($id, $qty)
    {
        $query = "update receive_stock set returned_debit=" . $qty . " where id=" . $id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function add_debit_note($info)
    {
        $query = "insert into debit_notes(creation_date,supplier_id,debit_payment_method,debit_value,deleted,store_id,note,bank_id,reference,payment_owner,currency_rate,payment_currency,p_invoice) values('" . my_sql::datetime_now() . "'," . $info["supplier_id"] . "," . $info["payment_method"] . "," . $info["debit_value"] . ",0,1,'" . $info["note"] . "'," . $info["bank_id"] . ",'" . $info["reference"] . "','" . $info["payment_owner"] . "','" . $info["currency_rate"] . "'," . $info["payment_currency"] . "," . $info["pi_id"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_debit_note($id)
    {
        $query = "select * from debit_notes where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_debit_note_for_suppliers($supplier_id)
    {
        $query = "select * from debit_notes where supplier_id=" . $supplier_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_sum_debitnote($supplier_id)
    {
        $query = "select  COALESCE(sum(debit_value), 0) as sum from debit_notes where supplier_id=" . $supplier_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_debitnote($store_id, $date_range)
    {
        $query = "select COALESCE(sum(debit_value), 0) as sum from debit_notes where store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_debit_note($info)
    {
        $query = "update debit_notes set debit_value=" . $info["debit_value"] . ",note='" . $info["note"] . "',supplier_id=" . $info["supplier_id"] . ",payment_currency=" . $info["payment_currency"] . ",currency_rate=" . $info["currency_rate"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function delete_debit_note($id)
    {
        $query = "update debit_notes set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function delete_debit_notes_of_supplier($id)
    {
        $query = "update debit_notes set deleted=1 where supplier_id=" . $id;
        my_sql::query($query);
    }
    public function reset_returns($p_id)
    {
        $query = "select id,item_id,returned_debit from receive_stock where receive_stock_invoice_id=" . $p_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            if (0 < $result[$i]["returned_debit"]) {
                $info_add_qty["qty"] = $result[$i]["returned_debit"];
                $info_add_qty["item_id"] = $result[$i]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "DN-" . $result[$i]["id"];
                $query = "update store_items set quantity=quantity+" . $info_add_qty["qty"] . " where item_id=" . $info_add_qty["item_id"];
                my_sql::query($query);
                if (0 < my_sql::get_mysqli_rows_num()) {
                    $query_qty = "select quantity from store_items where store_id=" . $info_add_qty["store_id"] . " and item_id=" . $info_add_qty["item_id"];
                    $result_qty = my_sql::fetch_assoc(my_sql::query($query_qty));
                    my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $_SESSION["id"] . "," . $info_add_qty["item_id"] . ",'" . my_sql::datetime_now() . "'," . $info_add_qty["qty"] . "," . $info_add_qty["store_id"] . "," . $result_qty[0]["quantity"] . ",'" . $info_add_qty["source"] . "')");
                }
            }
        }
        $query = "update receive_stock set returned_debit=0 where receive_stock_invoice_id=" . $p_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
}

?>