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
class creditnoteModel
{
    public function credit_notes($date_range)
    {
        $query = "select * from credit_notes where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function generate_credit_note($store_id, $created_by)
    {
        $query = "insert into credit_notes(creation_date,store_id,created_by) values('" . my_sql::datetime_now() . "'," . $store_id . "," . $created_by . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function sum_credit_notes($date_range)
    {
        $query = "select COALESCE(sum(credit_value), 0) as sum from credit_notes where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function total_credit_notes($customer_id)
    {
        $query = "select COALESCE(sum(credit_value), 0) as sum from credit_notes where deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_credit_note($info)
    {
        $query = "insert into credit_notes(creation_date,customer_id,credit_payment_method,credit_value,deleted,store_id,note,bank_id,reference,payment_owner,currency_rate,payment_currency,cr_rate_to_lbp,auto_sum) values('" . my_sql::datetime_now() . "'," . $info["customer_id"] . "," . $info["payment_method_id"] . "," . $info["credit_value"] . ",0,1,'" . $info["note"] . "'," . $info["bank_id"] . ",'" . $info["reference"] . "','" . $info["payment_owner"] . "'," . $info["currency_rate"] . "," . $info["payment_currency"] . "," . $info["cr_rate_to_lbp"] . "," . $info["auto_sum"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_credit_note($id)
    {
        $query = "select * from credit_notes where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_credit_note_for_customers($customer_id)
    {
        $query = "select * from credit_notes where customer_id=" . $customer_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_credit_note_for_customersDateRange($customer_id, $daterange)
    {
        $query = "select * from credit_notes where customer_id=" . $customer_id . " and deleted=0 and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_sum_creditnote($customer_id)
    {
        $query = "select  COALESCE(sum(credit_value), 0) as sum from credit_notes where customer_id=" . $customer_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_sum_creditnote_group()
    {
        $query = "select customer_id,COALESCE(sum(credit_value), 0) as sum from credit_notes where deleted=0 group by customer_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_sum_creditnote()
    {
        $query = "select  COALESCE(sum(credit_value), 0) as sum from credit_notes where deleted=0 and customer_id in (select id from customers where deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_creditnote($store_id, $date_range)
    {
        $query = "select COALESCE(sum(credit_value), 0) as sum from credit_notes where store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_credit_note($info)
    {
        $query = "update credit_notes set customer_id=" . $info["customer_id"] . ",credit_payment_method=" . $info["payment_method_id"] . ",credit_value=" . $info["credit_value"] . ",store_id=1,note='" . $info["note"] . "',bank_id=" . $info["bank_id"] . ",reference='" . $info["reference"] . "',payment_owner='" . $info["payment_owner"] . "',currency_rate=" . $info["currency_rate"] . ",payment_currency=" . $info["payment_currency"] . ",cr_rate_to_lbp=" . $info["cr_rate_to_lbp"] . ",auto_sum=" . $info["auto_sum"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function delete_credit_note($id)
    {
        $query = "update credit_notes set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function delete_credit_notes_of_customer($id)
    {
        $query = "update credit_notes set deleted=1 where customer_id=" . $id;
        my_sql::query($query);
    }
    public function cn_price_changed($cn_details_id, $price)
    {
        $query = "update credit_notes_details set price=" . $price . " where id=" . $cn_details_id;
        my_sql::query($query);
    }
    public function cn_qty_changed($cn_details_id, $qty)
    {
        $query = "update credit_notes_details set qty=" . $qty . " where id=" . $cn_details_id;
        my_sql::query($query);
    }
    public function delete_row_from_cr($cn_details_id)
    {
        $query = "update credit_notes_details set deleted=1 where id=" . $cn_details_id;
        my_sql::query($query);
    }
    public function add_item_to_credit_note($credit_note_id, $item_id)
    {
        $query = "insert into credit_notes_details(item_id,qty,price,credit_note_id) values(" . $item_id . ",0,0," . $credit_note_id . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_credit_note_details($credit_note_id)
    {
        $query = "select * from credit_notes_details where credit_note_id=" . $credit_note_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_details_from_cn($credit_note_id)
    {
        $query = "select * from credit_notes_details where id=" . $credit_note_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_items_details_from_cn($credit_note_id)
    {
        $query = "select * from credit_notes_details where credit_note_id=" . $credit_note_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>