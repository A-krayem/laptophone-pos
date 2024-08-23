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
class stockModel
{
    public function get_pi_more($id)
    {
        $query = "select * from receive_stock_invoice_fees where pi_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function invoices($item_id)
    {
        $query = "select inv.id,inv_it.qty,inv.creation_date,inv.customer_id,cs.name from invoices inv left join invoice_items inv_it on inv_it.invoice_id=inv.id left join customers cs on cs.id=inv.customer_id where inv_it.qty>0 and inv_it.item_id=" . $item_id . " and inv_it.deleted=0 and inv.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function transfers($item_id)
    {
        $query = "select * from history_quantities where item_id=" . $item_id . " and source like 'TRANS-%'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_qty_in_store($item_id, $cnx)
    {
        $query = "select * from store_items where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function get_item_from_pis($item_id)
    {
        $query = "select rs.item_id,rs.receive_stock_invoice_id,rsi.creation_date,rsi.id,rs.qty  from receive_stock rs left join receive_stock_invoices rsi on rs.receive_stock_invoice_id=rsi.id where rsi.deleted=0 and rs.item_id=" . $item_id . "";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_credit_note($item_id)
    {
        $query = "select cn.creation_date,cnd.qty,cnd.credit_note_id from credit_notes_details cnd left join credit_notes cn on cn.id=cnd.credit_note_id where cnd.item_id=" . $item_id . " and cnd.deleted=0 and cn.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_from_pis_debit_note($item_id)
    {
        $query = "select rs.item_id,rs.receive_stock_invoice_id,rsi.creation_date,rsi.id,rs.qty,rs.returned_debit  from receive_stock rs left join receive_stock_invoices rsi on rs.receive_stock_invoice_id=rsi.id where rsi.deleted=0 and rs.item_id=" . $item_id . " and returned_debit>0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_from_pis_debit_note_on_the_fly($item_id)
    {
        $query = "select dnd.qty,dn.creation_date,dn.id from debit_notes_details dnd left join debit_notes dn on dn.id=dnd.debit_note_id where dnd.item_id= " . $item_id . " and dnd.deleted=0 and dn.deleted=0 ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_from_pis_free($item_id)
    {
        $query = "select rs.item_id,rs.receive_stock_invoice_id,rsi.creation_date,rsi.id,rs.qty,rs.fqty  from receive_stock rs left join receive_stock_invoices rsi on rs.receive_stock_invoice_id=rsi.id where rsi.deleted=0 and rs.item_id=" . $item_id . " and rs.fqty>0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_log_pi($pi_id)
    {
        $query = "select rsl.id,rsl.creation_date,rsl.created_by,rsl.description,rsl.related_to_item_id,it.description as item_description,u.username from receive_stock_logs rsl left join items it on it.id=rsl.related_to_item_id left join users u on u.id=rsl.created_by where rsl.pi_id=" . $pi_id . " ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_total_qty($id)
    {
        $query = "select COALESCE(sum(qty+fqty), 0) as total_qty from receive_stock where receive_stock_invoice_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        my_sql::query("update receive_stock_invoices set total_qty=" . $result[0]["total_qty"] . " where id=" . $id);
    }
    public function get_pi_details($pi_id)
    {
        $query = "select * from receive_stock where receive_stock_invoice_id =" . $pi_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_debit_notes($supplier_id)
    {
        if ($supplier_id == -1 || $supplier_id == 0) {
            $query = "select p_invoice from debit_notes where  p_invoice>0 and deleted=0";
        } else {
            $query = "select p_invoice from debit_notes where supplier_id=" . $supplier_id . " and p_invoice>0 and deleted=0";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $tmp = array();
        for ($i = 0; $i < count($result); $i++) {
            array_push($tmp, $result[$i]["p_invoice"]);
        }
        return $tmp;
    }
    public function get_all_rate_of_pi_related_to_item_id($item_id)
    {
        $query = "select id,cur_rate from receive_stock_invoices where deleted=0 and id in (select receive_stock_invoice_id from receive_stock  where item_id=" . $item_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_pi_of_item($item_id)
    {
        $query = "select rs.id,rsi.creation_date,rsi.supplier_id,rs.qty,rs.cost,rs.discount_percentage,rs.returned_debit,sp.name as sup_name,rsi.id as pi_id,rsi.invoice_reference from receive_stock rs left join receive_stock_invoices rsi on rsi.id=rs.receive_stock_invoice_id left join suppliers sp on sp.id=rsi.supplier_id where rsi.deleted=0 and item_id=" . $item_id . "";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStockInvoices_for_transfer()
    {
        $query = "select * from receive_stock_invoices where deleted=0 and transferred=0 order by creation_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_currency_pi($pi_id, $currency_id)
    {
        $query = "update receive_stock_invoices set currency_id=" . $currency_id . " where id=" . $pi_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function apply_more_fees_discount_to_pi($pi_id)
    {
        $pi_qty_info = self::getSumOfQtyOfStockInvoice($pi_id);
        $pi_more_types = self::get_all_pi_more_types();
        $pi_more_types_array = array();
        for ($i = 0; $i < count($pi_more_types); $i++) {
            $pi_more_types_array[$pi_more_types[$i]["id"]] = $pi_more_types[$i];
        }
        $total = 0;
        $query = "select * from receive_stock_invoice_fees where pi_id=" . $pi_id . " and deleted=0 and apply_to_pi=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            if ($pi_more_types_array[$result[$i]["type_id"]]["discount_fees"] == 1) {
                $total -= $result[$i]["value"];
            } else {
                $total += $result[$i]["value"];
            }
        }
        if (0 < $pi_qty_info[0]["sum"]) {
            $margin = $total / $pi_qty_info[0]["sum"];
        } else {
            $margin = 0;
        }
        $query = "update receive_stock set pi_more_value=" . $margin . " where receive_stock_invoice_id=" . $pi_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function getSumOfQtyOfStockInvoice($pi_id)
    {
        $query = "select COALESCE(sum(qty), 0) as sum from receive_stock where receive_stock_invoice_id=" . $pi_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_pi_more_types()
    {
        $query = "select * from receive_stock_invoice_fees_types where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pi_more_by_id($id)
    {
        $query = "select * from receive_stock_invoice_fees where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_pi_more($id)
    {
        $query = "update receive_stock_invoice_fees set deleted=1 where id=" . $id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function add_more_pi($info)
    {
        $query = "insert into receive_stock_invoice_fees(value,type_id,deleted,note,pi_id,apply_to_pi) values(" . $info["value"] . "," . $info["type"] . ",0,'" . $info["description"] . "'," . $info["id_to_edit"] . "," . $info["apply_to_items"] . ")";
        my_sql::query($query);
        $id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $id;
    }
    public function update_more_pi($info)
    {
        my_sql::query("update receive_stock_invoice_fees set value=" . $info["value"] . ",note='" . $info["description"] . "',type_id=" . $info["type"] . ",apply_to_pi=" . $info["apply_to_items"] . " where id=" . $info["id_to_edit_more"]);
    }
    public function getItemsInStock()
    {
        $query = "select * from items";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_stock_qty($id)
    {
        $query = "select * from store_items where item_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function check_if_moved_to_store($id)
    {
        $query = "select moved_to_stock from receive_stock_invoices where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_pi_value($supplier_id, $date_range)
    {
        $supplier_qr = "";
        if (0 < $supplier_id) {
            $supplier_qr = " and supplier_id=" . $supplier_id;
        }
        $query = "select COALESCE(sum(total*cur_rate), 0) as total from receive_stock_invoices where deleted=0 and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "' " . $supplier_qr;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["total"];
    }
    public function get_all_pi_vat_value($supplier_id, $date_range)
    {
        $supplier_qr = "";
        if (0 < $supplier_id) {
            $supplier_qr = " and supplier_id=" . $supplier_id;
        }
        $query = "select COALESCE(sum(invoice_tax*cur_rate), 0) as total from receive_stock_invoices where deleted=0 and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "' " . $supplier_qr;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["total"];
    }
    public function get_all_pi_vat_value_on_invoice($supplier_id, $date_range)
    {
        $supplier_qr = "";
        if (0 < $supplier_id) {
            $supplier_qr = " and supplier_id=" . $supplier_id;
        }
        $query = "select COALESCE(sum(value), 0) as total from receive_stock_invoice_fees where deleted=0  and type_id=1 and pi_id in (select id from receive_stock_invoices where deleted=0 " . $supplier_qr . " and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "') ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["total"];
    }
    public function update_pi_picture_name($pi_id, $pi_name)
    {
        $query = "update receive_stock_invoices set pi_picture_name='" . $pi_name . "' where id=" . $pi_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function reset_pi_picture_name($pi_id)
    {
        $query = "update receive_stock_invoices set pi_picture_name=NULL where id=" . $pi_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function delete_purchase_invoice($pi_id)
    {
        $query = "update receive_stock_invoices set deleted=1 where id=" . $pi_id . " and moved_to_stock=0";
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function lock_pi_set($pi_id)
    {
        $query = "update receive_stock_invoices set moved_to_stock=1 where id=" . $pi_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function check_stock_movement($store_id)
    {
        $query = "select count(id) as num from stock_movement where MONTH(stock_date)=MONTH(CURRENT_DATE()) AND YEAR(stock_date) = YEAR(CURRENT_DATE()) and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($result[0]["num"] == 0) {
            my_sql::query("insert into stock_movement(stock_date,store_id) values('" . my_sql::datetime_now() . "'," . $store_id . ")");
            $last_insert_id = my_sql::get_mysqli_insert_id();
            $query_items_stock = "select * from store_items where store_id=" . $store_id;
            $result_items_stock = my_sql::fetch_assoc(my_sql::query($query_items_stock));
            $query_stck_qty = "insert into stock_movement_details(item_id,qty,stock_movement_id)VALUES";
            for ($i = 0; $i < count($result_items_stock); $i++) {
                if ($i == count($result_items_stock) - 1) {
                    $query_stck_qty .= "(" . $result_items_stock[$i]["item_id"] . "," . $result_items_stock[$i]["quantity"] . "," . $last_insert_id . ");";
                } else {
                    $query_stck_qty .= "(" . $result_items_stock[$i]["item_id"] . "," . $result_items_stock[$i]["quantity"] . "," . $last_insert_id . "),";
                }
            }
            my_sql::query($query_stck_qty);
        }
    }
    public function getStockInvoicesById($id)
    {
        $query = "select * from receive_stock_invoices where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pi_closed()
    {
        $query = "select * from receive_stock_invoices where deleted=0 and id not in (select p_invoice from debit_notes where deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pi_closed_by_supplier_id($supplier_id)
    {
        if ($supplier_id == 0) {
            $query = "select * from receive_stock_invoices where deleted=0 and id not in (select p_invoice from debit_notes where deleted=0)";
        } else {
            $query = "select * from receive_stock_invoices where deleted=0 and supplier_id=" . $supplier_id . " and id not in (select p_invoice from debit_notes where deleted=0)";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStockInvoiceItems($invoice_id)
    {
        $query = "select * from receive_stock where receive_stock_invoice_id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStockInvoiceItemById($invoice_id, $item_id)
    {
        $query = "select * from receive_stock where receive_stock_invoice_id=" . $invoice_id . " and item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_invoice_order_as_moved($invoice_id)
    {
        $query = "update receive_stock_invoices set moved_to_stock=1 where id=" . $invoice_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function getStockInvoiceItemsInOrderToMove($invoice_id)
    {
        $query = "select * from receive_stock where receive_stock_invoice_id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getStockInvoices($supplier_id, $payment_status_id, $date_range)
    {
        if ($payment_status_id == NULL) {
            $payment_status_id = 0;
        }
        $result = array();
        if ($supplier_id == 0 && $payment_status_id == 0) {
            $query = "select * from receive_stock_invoices where deleted=0 and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "' order by creation_date desc";
            $result = my_sql::fetch_assoc(my_sql::query($query));
        } else {
            if (0 < $supplier_id && $payment_status_id == 0) {
                $query = "select * from receive_stock_invoices where supplier_id=" . $supplier_id . " and deleted=0 and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "' order by creation_date desc";
                $result = my_sql::fetch_assoc(my_sql::query($query));
            }
            if ($supplier_id == 0 && 0 < $payment_status_id) {
                $query = "select * from receive_stock_invoices where paid_status=" . $payment_status_id . " and deleted=0 and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "' order by creation_date desc";
                $result = my_sql::fetch_assoc(my_sql::query($query));
            }
            if (0 < $supplier_id && 0 < $payment_status_id) {
                $query = "select * from receive_stock_invoices where supplier_id=" . $supplier_id . " and deleted=0 and paid_status=" . $payment_status_id . " and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "' order by creation_date desc";
                $result = my_sql::fetch_assoc(my_sql::query($query));
            }
            if ($supplier_id == -1) {
                $query = "select * from receive_stock_invoices where deleted=0 and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "' and id in (select DISTINCT(receive_stock_invoice_id) from receive_stock)  order by creation_date desc";
                $result = my_sql::fetch_assoc(my_sql::query($query));
            }
        }
        return $result;
    }
    public function addStockInvoice($invoice_info)
    {
        $query = "insert into receive_stock_invoices(creation_date,receive_invoice_date,delivery_date,supplier_id,subtotal,discount,total,invoice_tax,paid_status,invoice_reference,created_by) values('" . my_sql::datetime_now() . "','" . $invoice_info["receive_invoice_date"] . "','" . $invoice_info["delivery_date"] . "'," . $invoice_info["supplier_id"] . "," . $invoice_info["invoice_subtotal"] . "," . $invoice_info["invoice_discount"] . "," . $invoice_info["invoice_total"] . "," . $invoice_info["invoice_tax"] . ",2,'" . $invoice_info["invoice_reference"] . "'," . $_SESSION["id"] . ")";
        my_sql::query($query);
        $id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $id;
    }
    public function receive_stock_logs($log)
    {
        $query = "insert into receive_stock_logs(creation_date,created_by,pi_id,description,related_to_item_id) " . "values('" . my_sql::datetime_now() . "','" . $log["created_by"] . "'," . $log["pi_id"] . ",'" . $log["description"] . "'," . $log["related_to_item_id"] . ")";
        my_sql::query($query);
    }
    public function generate_purshace_invoice($para)
    {
        $query_default_currency = "select id from currencies where system_default=1";
        $result_default_currency = my_sql::fetch_assoc(my_sql::query($query_default_currency));
        $query = "insert into receive_stock_invoices(creation_date,receive_invoice_date,delivery_date,auto_filled,currency_id,vat,created_by,charge_type) values('" . my_sql::datetime_now() . "','" . my_sql::datetime_now() . "','" . my_sql::datetime_now() . "',1," . $result_default_currency[0]["id"] . "," . $para["vat"] . "," . $_SESSION["id"] . "," . $para["charge_type"] . ")";
        my_sql::query($query);
        $result = my_sql::query("select MAX(id) as maxid from receive_stock_invoices;");
        $row = mysqli_fetch_assoc($result);
        $id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $row['maxid'];
       // return $id;
    }
    public function updateStockInvoice($invoice_info)
    {
        $query = "update receive_stock_invoices set receive_invoice_date='" . $invoice_info["receive_invoice_date"] . "',delivery_date='" . $invoice_info["delivery_date"] . "',supplier_id=" . $invoice_info["supplier_id"] . ",subtotal=" . $invoice_info["invoice_subtotal"] . ",discount=" . $invoice_info["invoice_discount"] . ",total=" . $invoice_info["invoice_total"] . ",invoice_tax='" . $invoice_info["invoice_tax"] . "',invoice_reference='" . $invoice_info["invoice_reference"] . "',auto_filled=" . $invoice_info["autofill_id"] . ",charge_type=" . $invoice_info["charge_type_id"] . ",currency_id=" . $invoice_info["currency_id"] . ",cur_rate=" . $invoice_info["cur_rate"] . " where id=" . $invoice_info["action_type"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function addStockInvoiceItems($info, $invoice_stock_id)
    {
        if ($info["expiry_date"] == "" || $info["expiry_date"] == "NULL" || $info["expiry_date"] == NULL || $info["expiry_date"] == 0 || !isset($info["expiry_date"])) {
            $exp = "NULL";
            $query = "insert into receive_stock(item_id,location_id,qty,cost,receive_stock_invoice_id,vat,supplier_ref,discount_percentage,discount_after_vat,discount_percentage_2,fqty,charge) values(" . $info["item_id"] . "," . $info["location_id"] . "," . $info["qty"] . "," . $info["cost"] . "," . $invoice_stock_id . "," . $info["vat"] . ",'" . $info["supplier_item_ref"] . "','" . $info["unit_discount"] . "','" . $info["unit_discount_after_vat"] . "','" . $info["unit_discount_2"] . "'," . $info["fqty"] . "," . $info["charge"] . ")";
        } else {
            $exp = "'" . $info["expiry_date"] . "'";
            $query = "insert into receive_stock(item_id,location_id,qty,cost,receive_stock_invoice_id,vat,supplier_ref,discount_percentage,discount_after_vat,discount_percentage_2,fqty,expiry_date,charge) values(" . $info["item_id"] . "," . $info["location_id"] . "," . $info["qty"] . "," . $info["cost"] . "," . $invoice_stock_id . "," . $info["vat"] . ",'" . $info["supplier_item_ref"] . "','" . $info["unit_discount"] . "','" . $info["unit_discount_after_vat"] . "','" . $info["unit_discount_2"] . "'," . $info["fqty"] . "," . $exp . "," . $info["charge"] . ")";
        }
        my_sql::query($query);
        $id = my_sql::get_mysqli_insert_id();
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $id;
    }
    public function updateStockInvoiceItems($info)
    {
        $query = "update receive_stock set item_id=" . $info["item_id"] . ", qty=" . $info["qty"] . ", cost=" . $info["cost"] . ",vat=" . $info["vat"] . ",supplier_ref='" . $info["supplier_item_ref"] . "',discount_percentage='" . $info["unit_discount"] . "',discount_after_vat=" . $info["unit_discount_after_vat"] . ",discount_percentage_2='" . $info["unit_discount_2"] . "',fqty=" . $info["fqty"] . ",expiry_date='" . $info["expiry_date"] . "' where id=" . $info["index"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function delete_item_from_invoice_order($id)
    {
        $query = "delete from receive_stock where id=" . $id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function get_latest_cost_of_item($id)
    {
        $query = "select * from receive_stock where item_id=" . $id . " order by id desc limit 1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_from_invoice_order($id)
    {
        $query = "select * from receive_stock where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_returned_items_from_pi($pi_id)
    {
        $query = "select * from receive_stock where receive_stock_invoice_id=" . $pi_id . " and returned_debit>0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_returned_items_fly($_id)
    {
        $query = "select * from debit_notes_details where debit_note_id=" . $_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_history_price($receive_stock_invoice_id, $item_id)
    {
        $query = "delete from history_prices where source='" . $receive_stock_invoice_id . "' and item_id=" . $item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function get_supplier_of_invoice($invoice_id)
    {
        $query = "select * from receive_stock_invoices where id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalInvoicesForSupplier($supplier_id)
    {
        $query = "select count(id) as num from receive_stock_invoices where supplier_id=" . $supplier_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalInvoicesValueForSupplier($supplier_id)
    {
        $query = "select COALESCE(sum(total), 0) as total from receive_stock_invoices where deleted=0 and supplier_id=" . $supplier_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_suppliers_invoices()
    {
        $query = "select supplier_id,count(id) as num from receive_stock_invoices where supplier_id!=0 group by supplier_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>