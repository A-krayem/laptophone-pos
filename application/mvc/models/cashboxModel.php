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
class cashboxModel
{
    public function getTodayCashbox($store_id, $vendor_id)
    {
        $query = "select * from cashbox where vendor_id=" . $vendor_id . " and closed=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_cashboxes_by_filter($filter)
    {
        $query = "select * from cashbox where date(starting_cashbox_date)>=date('" . $filter["start_date"] . "') and date(starting_cashbox_date)<=date('" . $filter["end_date"] . "')";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashboxes_employee()
    {
        $query = "select cs.id,u.username from cashbox cs,users u where u.id=cs.vendor_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_suppliers_transacions_details($cashbox_id)
    {
        $query_cashbox = "select sp.supplier_id,sp.id,sp.payment_value,sp.creation_date,sp.usd_to_lbp as rate,sp.cash_in_lbp,sp.cash_in_usd,sp.returned_usd,sp.returned_lbp,sp.to_returned_usd,sp.to_returned_lbp from suppliers_payments sp where sp.deleted=0 and sp.cashbox_id=" . $cashbox_id;
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        return $result_cashbox;
    }
    public function get_suppliers_transacions_details_remote($cashbox_id, $connection)
    {
        $query_cashbox = "select sp.supplier_id,sp.id,sp.payment_value,sp.creation_date,sp.usd_to_lbp as rate,sp.cash_in_lbp,sp.cash_in_usd,sp.returned_usd,sp.returned_lbp,sp.to_returned_usd,sp.to_returned_lbp from suppliers_payments sp where sp.deleted=0 and sp.cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query_cashbox, $connection));
        return $result;
    }
    public function get_cash_details($cashbox_id)
    {
        $query = "select * from cash_details where cashbox_id=" . $cashbox_id . " and invoice_id>0 and invoice_id in (select id from invoices where cashbox_id=" . $cashbox_id . " and deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_cash_details_of_invoice($invoice_id)
    {
        $query = "select * from cash_details where invoice_id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function geCashboxById($cashbox_id)
    {
        $query = "select * from cashbox where id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function geCashboxById_remote($cashbox_id, $cnx)
    {
        $query = "select * from cashbox where id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function get_sales_invoice_transacions_details_remote($cashbox_id, $connection)
    {
        $query_cashbox = "select cd.id,cd.cash_usd,cd.cash_lbp,cd.invoice_id,cd.base_usd_amount,cd.rate,cd.cashbox_id,cd.must_return_cash_usd,cd.must_return_cash_lbp,cd.returned_cash_usd,cd.returned_cash_lbp,inv.employee_id,inv.creation_date,inv.closed,inv.customer_id,inv.auto_closed  from cash_details cd left join invoices inv on inv.id=cd.invoice_id where inv.deleted=0 and inv.cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query_cashbox, $connection));
        return $result;
    }
    public function getCashboxDetails($cashbox_id, $currency)
    {
        if ($currency == 1) {
            $query = "select COALESCE(sum(cash_usd-returned_cash_usd), 0) as sum from cash_details where cashbox_id=" . $cashbox_id . " and invoice_id in (select id from invoices where cashbox_id=" . $cashbox_id . " and deleted=0)";
        }
        if ($currency == 2) {
            $query = "select COALESCE(sum(cash_lbp-returned_cash_lbp), 0) as sum from cash_details where cashbox_id=" . $cashbox_id . " and invoice_id in (select id from invoices where cashbox_id=" . $cashbox_id . " and deleted=0)";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($currency == 1) {
            $query_changes = "select COALESCE(sum(cash_usd_in-returned_cash_usd), 0) as sum from cashbox_changes_info where cashbox_id=" . $cashbox_id;
        }
        if ($currency == 2) {
            $query_changes = "select COALESCE(sum(cash_lbp_in-returned_cash_usd), 0) as sum from cashbox_changes_info where cashbox_id=" . $cashbox_id;
        }
        $result_changes = my_sql::fetch_assoc(my_sql::query($query_changes));
        if ($currency == 1) {
            return $result[0]["sum"] + $result_changes[0]["sum"];
        }
        return $result[0]["sum"] + $result_changes[0]["sum"];
    }
    public function get_all_opened_cashbox()
    {
        $query = "select * from cashbox where closed=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_opened_cashbox_remote($cnx)
    {
        $query = "select * from cashbox where closed=0";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function get_all_opened_cashbox_but_not_me($cashbox_id)
    {
        $query = "select cb.id,cb.vendor_id,u.username from cashbox cb left join users u on u.id=cb.vendor_id where cb.closed=0 and cb.id!=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_cashboxes($date_range)
    {
        $query = "select * from cashbox where date(starting_cashbox_date)>='" . $date_range[0] . "' and date(starting_cashbox_date)<='" . $date_range[1] . "' order by id desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function main_cashbox_is_open($vendor_id)
    {
        $query = "select count(id) as num from " . DATABASE . ".cashbox where vendor_id=" . $vendor_id . " and closed=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function check_if_cashbox_is_open($vendor_id)
    {
        $query = "select count(id) as num from cashbox where closed=0 and vendor_id=" . $vendor_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function setCashbox($store_id, $vendor_id, $value, $value_lbp)
    {
        $query_ = "select * from users where id=" . $vendor_id;
        $result_ = my_sql::fetch_assoc(my_sql::query($query_));
        if (self::check_if_cashbox_is_open($vendor_id) == 0 && $result_[0]["role_id"] == 2) {
            $query = "insert into cashbox(store_id,vendor_id,starting_cashbox_date,cash,closed,cashbox_lbp) value(" . $store_id . "," . $vendor_id . ",'" . my_sql::datetime_now() . "'," . $value . ",0," . $value_lbp . ")";
            my_sql::query($query);
            //$last_id = my_sql::get_mysqli_insert_id();
            $result = my_sql::query("select MAX(id) as maxid from cashbox;");
            $row = mysqli_fetch_assoc($result);
            $last_id =  $row['maxid'];
            if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
                $query = "insert into " . DATABASE_SYNC . ".cashbox(id,store_id,vendor_id,starting_cashbox_date,cash,closed) value(" . $last_id . "," . $store_id . "," . $vendor_id . ",'" . my_sql::datetime_now() . "',0,0)";
                my_sql::query($query);
            }
            $query = "insert into cash_in_out_starting(usd_amount,lbp_amount,create_date,shift_id) value(0,0,'" . my_sql::datetime_now() . "'," . $last_id . ")";
            my_sql::query($query);
            return $last_id;
        }
    }
    public function closeCashbox($store_id, $vendor_id)
    {
        $retuned_info = self::updateCashBox($_SESSION["cashbox_id"]);
        $cash_on_close = self::getTotalCashbox($vendor_id, $store_id);
        my_sql::query("update cashbox set closed=1,cash_on_close=" . $cash_on_close . ",ending_cashbox_date='" . my_sql::datetime_now() . "',fixed_info='" . json_encode($retuned_info) . "' where store_id=" . $store_id . " and vendor_id=" . $vendor_id . " and closed=0");
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            my_sql::query("update " . DATABASE_SYNC . ".cashbox set closed=1,cash_on_close=" . $cash_on_close . ",ending_cashbox_date='" . my_sql::datetime_now() . "',fixed_info='" . json_encode($retuned_info) . "' where store_id=" . $store_id . " and vendor_id=" . $vendor_id . " and closed=0");
        }
    }
    public function addValueToCashbox($store_id, $vendor_id, $value)
    {
        my_sql::query("update cashbox set current_cash_box_value=current_cash_box_value+" . $value . " where store_id=" . $store_id . " and vendor_id=" . $vendor_id . " and closed=0");
    }
    public function reduceValueToCashbox($store_id, $vendor_id, $value)
    {
        my_sql::query("update cashbox set current_cash_box_value=current_cash_box_value-" . $value . " where store_id=" . $store_id . " and vendor_id=" . $vendor_id . " and closed=0");
    }
    public function get_cashbox_new_version($cashbox_id)
    {
        $query_2 = "select COALESCE(sum(value), 0) as sum from expenses where deleted=0 and cashbox_id=" . $cashbox_id;
        $result_2 = my_sql::fetch_assoc(my_sql::query($query_2));
        $expense_sum = $result_2[0]["sum"];
        $query_3 = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and payment_method=1 and cashbox_id=" . $cashbox_id;
        $result_3 = my_sql::fetch_assoc(my_sql::query($query_3));
        $customer_balance_sum = $result_3[0]["sum"];
        $query_8 = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where payment_method=1 and deleted=0 and cashbox_id=" . $cashbox_id;
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        $return_sum_8 = $result_8[0]["sum"];
        $query_5 = "select COALESCE(sum(qty*selling_price*(1-discount/100)), 0) as sum from returned_purchases where item_id>0 and deleted=0 and vat=0 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and other_branche=0)";
        $result_5 = my_sql::fetch_assoc(my_sql::query($query_5));
        $return_sum_5 = $result_5[0]["sum"];
        $query_7 = "select COALESCE(sum(qty*selling_price*(1-discount/100)*(vat_value-1)), 0) as sum from returned_purchases where item_id>0 and deleted=0 and vat=1 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and deleted=0 and other_branche=0)";
        $result_7 = my_sql::fetch_assoc(my_sql::query($query_7));
        $return_sum_7 = $result_7[0]["sum"];
        $query_diff_lbp = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and cashbox_id!=old_cashbox_id and (added_value>0 || return_value>0) and invoice_id not in (select id from invoices where deleted=0 and closed=0 and other_branche=0)";
        $result_diff_lbp = my_sql::fetch_assoc(my_sql::query($query_diff_lbp));
        $diff_changed = 0;
        if (0 < count($result_diff_lbp)) {
            $diff_changed = $result_diff_lbp[0]["diff_changed"];
        }
        return $customer_balance_sum - ($expense_sum + $return_sum_8 + $return_sum_5 + $return_sum_7);
    }
    public function updateCashBox($cashbox_id)
    {
        $returned_info = array();
        $returned_info["expenses"] = 0;
        $returned_info["invoices"] = 0;
        $returned_info["customer_payments"] = 0;
        $returned_info["suppliers_payments"] = 0;
        $returned_info["returned_purchases"] = 0;
        $returned_info["cashin"] = 0;
        $returned_info["cashout"] = 0;
        $returned_info["cashback"] = 0;
        $query_1 = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and payment_method=1 and closed=1 and auto_closed=0 and deleted=0";
        $result_1 = my_sql::fetch_assoc(my_sql::query($query_1));
        $invoice_sum = $result_1[0]["sum"];
        $returned_info["invoices"] = $invoice_sum;
        $query_2 = "select COALESCE(sum(value), 0) as sum from expenses where deleted=0 and cashbox_id=" . $cashbox_id;
        $result_2 = my_sql::fetch_assoc(my_sql::query($query_2));
        $expense_sum = $result_2[0]["sum"];
        $returned_info["expenses"] = $expense_sum;
        $query_3 = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and payment_method=1 and cashbox_id=" . $cashbox_id;
        $result_3 = my_sql::fetch_assoc(my_sql::query($query_3));
        $customer_balance_sum = $result_3[0]["sum"];
        $returned_info["customer_payments"] = $customer_balance_sum;
        $query_5 = "select COALESCE(sum(qty*selling_price*(1-discount/100)), 0) as sum from returned_purchases where item_id>0 and deleted=0 and vat=0 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and other_branche=0)";
        $result_5 = my_sql::fetch_assoc(my_sql::query($query_5));
        $return_sum_5 = $result_5[0]["sum"];
        $returned_info["returned_purchases"] += $return_sum_5;
        $query_7 = "select COALESCE(sum(qty*selling_price*(1-discount/100)*(vat_value-1)), 0) as sum from returned_purchases where item_id>0 and deleted=0 and vat=1 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and deleted=0 and other_branche=0)";
        $result_7 = my_sql::fetch_assoc(my_sql::query($query_7));
        $return_sum_7 = $result_7[0]["sum"];
        $returned_info["returned_purchases"] += $return_sum_7;
        $query_55 = "select COALESCE(sum(qty*selling_price*(1-discount/100)), 0) as sum from returned_purchases where deleted=0 and vat=0 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1";
        $result_55 = my_sql::fetch_assoc(my_sql::query($query_55));
        $return_sum_55 = 0;
        $query_77 = "select COALESCE(sum(qty*selling_price*(1-discount/100)*(vat_value-1)), 0) as sum from returned_purchases where deleted=0 and vat=1 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1";
        $result_77 = my_sql::fetch_assoc(my_sql::query($query_77));
        $return_sum_77 = 0;
        $query_8 = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where payment_method=1 and deleted=0 and cashbox_id=" . $cashbox_id;
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        $return_sum_8 = $result_8[0]["sum"];
        $returned_info["suppliers_payments"] = $return_sum_8;
        $query_6 = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and cashbox_id!=old_cashbox_id and ( (return_value>0 and only_return=1) or (added_value>0 and return_value>0)  ) and invoice_id not in (select id from invoices where deleted=0 and closed=0 and other_branche=0)";
        $result_6 = my_sql::fetch_assoc(my_sql::query($query_6));
        $diff_changed = 0;
        if (0 < count($result_6)) {
            $diff_changed = $result_6[0]["diff_changed"];
            $returned_info["returned_purchases"] += $diff_changed;
        }
        $returned_info["diff_branches"] = 0;
        $query_11 = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and cashbox_id=old_cashbox_id and added_value>0 and return_value>0 and invoice_id in (select id from invoices where deleted=0 and closed=0 and other_branche>0)";
        $result_11 = my_sql::fetch_assoc(my_sql::query($query_11));
        $diff_changed_branches = 0;
        if (0 < count($result_11)) {
            $diff_changed_branches = $result_11[0]["diff_changed"];
            $returned_info["diff_branches"] = $result_11[0]["diff_changed"];
        }
        $cashin_out_query = "select COALESCE(sum(amount_lbp), 0) as sum from cash_in_out where cashbox_id=" . $cashbox_id . " and deleted=0 and cash_in_out=1";
        $cashin_out_result = my_sql::fetch_assoc(my_sql::query($cashin_out_query));
        $total_in_out = $cashin_out_result[0]["sum"];
        $returned_info["cashin"] = $total_in_out;
        $cashin_out_query_out = "select COALESCE(sum(amount_lbp), 0) as sum from cash_in_out where cashbox_id=" . $cashbox_id . " and deleted=0 and cash_in_out=2";
        $cashin_out_result_out = my_sql::fetch_assoc(my_sql::query($cashin_out_query_out));
        $total_in_out_out = $cashin_out_result_out[0]["sum"];
        $returned_info["cashout"] = $total_in_out_out;
        $cashback_query = "select COALESCE(sum(cashback_value), 0) as sum from cashback where cashbox_id=" . $cashbox_id . " and deleted=0";
        $cashback_result = my_sql::fetch_assoc(my_sql::query($cashback_query));
        $total_cashback = $cashback_result[0]["sum"];
        $returned_info["cashback"] = $total_cashback;
        $query_99 = "select COALESCE(sum(qty*selling_price*(1-discount/100)), 0) as sum from returned_purchases where item_id>0 and deleted=0 and vat=0 and cashbox_id=" . $cashbox_id . " and old_cashbox_id=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and other_branche=0)";
        $result_99 = my_sql::fetch_assoc(my_sql::query($query_99));
        $return_sum_99 = $result_99[0]["sum"];
        $returned_info["returned_purchases"] += $return_sum_99;
        $query_999 = "select COALESCE(sum(qty*selling_price*(1-discount/100)*(vat_value-1)), 0) as sum from returned_purchases where deleted=0 and vat=1 and cashbox_id=" . $cashbox_id . " and old_cashbox_id=cashbox_id and only_return=1";
        $result_999 = my_sql::fetch_assoc(my_sql::query($query_999));
        $return_sum_999 = $result_999[0]["sum"];
        $returned_info["returned_purchases"] += $return_sum_999;
        $query_discount = "select COALESCE(sum(abs(invoice_discount)), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and payment_method=1 and closed=1 and auto_closed=0 and deleted=0";
        $result_discount = my_sql::fetch_assoc(my_sql::query($query_discount));
        $returned_info["invoices_discounts"] = $result_discount[0]["sum"];
        $query_profit = "select COALESCE(sum(total_profit), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and deleted=0";
        $result_profit = my_sql::fetch_assoc(my_sql::query($query_profit));
        $returned_info["total_profit"] = $result_profit[0]["sum"];
        my_sql::query("update cashbox set current_cash_box_value=" . ($invoice_sum - $return_sum_8 + $customer_balance_sum - $expense_sum - $return_sum_5 - $return_sum_55 - $return_sum_7 - $return_sum_77 + $diff_changed + $total_in_out - $total_in_out_out - $total_cashback + $diff_changed_branches) . ",fixed_info='" . json_encode($returned_info) . "' where id=" . $cashbox_id);
        return $returned_info;
    }
    public function get_total_invoices_current_shift($cashbox_id)
    {
        $query_1 = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where cashbox_id=" . $cashbox_id . " and payment_method=1 and closed=1 and auto_closed=0 and deleted=0 and other_branche=0";
        $result_1 = my_sql::fetch_assoc(my_sql::query($query_1));
        return $result_1[0]["sum"];
    }
    public function posmonitor($filter)
    {
        $vendor_filter = "";
        if (0 < $filter["vendor_id"]) {
            $vendor_filter = " and us.id=" . $filter["vendor_id"];
        }
        $query = "select pm.creation_date,pm.cashbox_id,pm.item_id,us.username, it.description, it.barcode, pm.qty,sit.quantity from pos_monitor pm left join cashbox cb on cb.id=pm.cashbox_id left join users us on us.id=cb.vendor_id left join items it on it.id=pm.item_id left join store_items sit on sit.item_id=pm.item_id where date(pm.creation_date)>='" . $filter["start_date"] . "' and date(pm.creation_date)<='" . $filter["end_date"] . "' " . $vendor_filter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_changes_and_return_for_another_branches($cashbox_id)
    {
        $query_11 = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and cashbox_id=old_cashbox_id and added_value>0 and return_value>0 and invoice_id in (select id from invoices where deleted=0 and other_branche>0)";
        $result_11 = my_sql::fetch_assoc(my_sql::query($query_11));
        $diff_changed_other_branche = 0;
        if (0 < count($result_11)) {
            $diff_changed_other_branche = $result_11[0]["diff_changed"];
        }
        return $diff_changed_other_branche;
    }
    public function get_total_changes_and_return_for_another_shift($cashbox_id)
    {
        $query_5 = "select COALESCE(sum(qty*selling_price*(1-discount/100)), 0) as sum from returned_purchases where deleted=0 and vat=0 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and deleted=0 and other_branche=0)";
        $result_5 = my_sql::fetch_assoc(my_sql::query($query_5));
        $return_sum_5 = 0;
        if (0 < count($result_5)) {
            $return_sum_5 = $result_5[0]["sum"];
        }
        $query_7 = "select COALESCE(sum(qty*selling_price*(1-discount/100)*(vat_value-1)), 0) as sum from returned_purchases where deleted=0 and vat=1 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1";
        $result_7 = my_sql::fetch_assoc(my_sql::query($query_7));
        $return_sum_7 = 0;
        if (0 < count($result_7)) {
            $return_sum_7 = $result_7[0]["sum"];
        }
        $query_6 = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and cashbox_id!=old_cashbox_id and added_value>0 and return_value>0";
        $result_6 = my_sql::fetch_assoc(my_sql::query($query_6));
        $diff_changed = 0;
        if (0 < count($result_6)) {
            $diff_changed = $result_6[0]["diff_changed"];
        }
        $query_9 = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and added_value>0 and return_value>0 and invoice_id in (select id from invoices where deleted=0 and other_branche>0)";
        $result_9 = my_sql::fetch_assoc(my_sql::query($query_9));
        $diff_changed_other_branches = 0;
        if (0 < count($result_9)) {
            $diff_changed_other_branches = $result_9[0]["diff_changed"];
        }
        return 0 - ($return_sum_5 + $return_sum_7) + $diff_changed + $diff_changed_other_branches;
    }
    public function get_total_changes($cashbox_id)
    {
        $query_changes_diff = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id;
        $result_changes_diff = my_sql::fetch_assoc(my_sql::query($query_changes_diff));
        $diff_changed = 0;
        if (0 < count($result_changes_diff)) {
            $diff_changed = $result_changes_diff[0]["diff_changed"];
        }
        return $diff_changed;
    }
    public function updateItemChangeInfo($inv_it_id, $cashbox_id)
    {
        my_sql::query("update invoice_items set item_change_cashbox=" . $cashbox_id . ",item_change_date='" . my_sql::datetime_now() . "' where id=" . $inv_it_id);
    }
    public function getTotalCashbox($vendor_id, $store_id)
    {
        $query_cashbox = "select cash,current_cash_box_value from cashbox where store_id=" . $store_id . " and vendor_id=" . $vendor_id . " and closed=0";
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        if (0 < count($result_cashbox)) {
            return $result_cashbox[0]["cash"] + $result_cashbox[0]["current_cash_box_value"];
        }
        return 0;
    }
    public function getHistoryOfCashboxes($vendor_id, $store_id, $date_range)
    {
        $query_cashbox = "select * from cashbox where store_id=" . $store_id . " and vendor_id=" . $vendor_id . " and closed=1 and date(starting_cashbox_date)>='" . $date_range[0] . "' and date(starting_cashbox_date)<='" . $date_range[1] . "'";
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        return $result_cashbox;
    }
    public function get_sales_invoice_transacions_details($cashbox_id)
    {
        $query_cashbox = "select cd.id,cd.cash_usd,cd.cash_lbp,cd.invoice_id,cd.base_usd_amount,cd.rate,cd.cashbox_id,cd.must_return_cash_usd,cd.must_return_cash_lbp,cd.returned_cash_usd,cd.returned_cash_lbp,inv.employee_id,inv.creation_date,inv.closed,inv.customer_id,inv.auto_closed  from cash_details cd left join invoices inv on inv.id=cd.invoice_id where inv.deleted=0 and inv.cashbox_id=" . $cashbox_id;
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        return $result_cashbox;
    }
    public function get_changes_invoice_transacions_details_remote($cashbox_id, $cnx)
    {
        $query_cashbox = "select cci.id,cci.change_date,cci.invoice_id,cci.return_value,cci.added_value,cci.rate,cci.cash_lbp_in,cci.cash_usd_in,cci.returned_cash_lbp,cci.returned_cash_usd,cci.cash_usd_to_return,cci.cash_lbp_to_return from cashbox_changes_info cci where invoice_id in (select id from invoices where deleted=0) and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query_cashbox, $cnx));
        return $result;
    }
    public function get_changes_invoice_transacions_details($cashbox_id)
    {
        $query_cashbox = "select cci.id,cci.change_date,cci.invoice_id,cci.return_value,cci.added_value,cci.rate,cci.cash_lbp_in,cci.cash_usd_in,cci.returned_cash_lbp,cci.returned_cash_usd,cci.cash_usd_to_return,cci.cash_lbp_to_return from cashbox_changes_info cci where invoice_id in (select id from invoices where deleted=0) and cashbox_id=" . $cashbox_id;
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        return $result_cashbox;
    }
    public function get_changes_invoice_transacions_details_other_cashbox_remote($cashbox_id, $cnx)
    {
        $query_cashbox = "select cci.id,cci.change_date,cci.invoice_id,cci.return_value,cci.added_value,cci.rate,cci.cash_lbp_in,cci.cash_usd_in,cci.returned_cash_lbp,cci.returned_cash_usd,cci.cash_usd_to_return,cci.cash_lbp_to_return from cashbox_changes_info cci where invoice_id in (select id from invoices where deleted=1) and cashbox_id=" . $cashbox_id . " and cashbox_id!=old_cashbox_id";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query_cashbox, $cnx));
        return $result;
    }
    public function get_changes_invoice_transacions_details_other_cashbox($cashbox_id)
    {
        $query_cashbox = "select cci.id,cci.change_date,cci.invoice_id,cci.return_value,cci.added_value,cci.rate,cci.cash_lbp_in,cci.cash_usd_in,cci.returned_cash_lbp,cci.returned_cash_usd,cci.cash_usd_to_return,cci.cash_lbp_to_return from cashbox_changes_info cci where invoice_id in (select id from invoices where deleted=1) and cashbox_id=" . $cashbox_id . " and cashbox_id!=old_cashbox_id";
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        return $result_cashbox;
    }
    public function get_clients_payment_transacions_details($cashbox_id)
    {
        $query_cashbox = "select cb.id,cb.customer_id,cb.value_date,cb.vendor_id,cb.balance,cb.p_rate,cb.cash_in_usd,cb.cash_in_lbp,cb.returned_usd,cb.returned_lbp,cb.to_returned_usd,cb.to_returned_lbp from customer_balance cb where cb.deleted=0 and cb.cashbox_id=" . $cashbox_id;
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        return $result_cashbox;
    }
    public function get_clients_payment_transacions_details_remote($cashbox_id, $cnx)
    {
        $query_cashbox = "select cb.id,cb.customer_id,cb.value_date,cb.vendor_id,cb.balance,cb.p_rate,cb.cash_in_usd,cb.cash_in_lbp,cb.returned_usd,cb.returned_lbp,cb.to_returned_usd,cb.to_returned_lbp from customer_balance cb where cb.deleted=0 and cb.cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query_cashbox, $cnx));
        return $result;
    }
    public function get_expenses_transacions_details($cashbox_id)
    {
        $query_cashbox = "select ex.id,ex.creation_date,ex.rate,ex.cash_lbp_in,ex.cash_usd_in,ex.returned_cash_lbp,ex.returned_cash_usd,ex.cash_usd_to_return,ex.cash_lbp_to_return,ex.value from expenses ex where ex.deleted=0 and ex.cashbox_id=" . $cashbox_id;
        $result_cashbox = my_sql::fetch_assoc(my_sql::query($query_cashbox));
        return $result_cashbox;
    }
    public function get_expenses_transacions_details_remote($cashbox_id, $cnx)
    {
        $query_cashbox = "select ex.id,ex.creation_date,ex.rate,ex.cash_lbp_in,ex.cash_usd_in,ex.returned_cash_lbp,ex.returned_cash_usd,ex.cash_usd_to_return,ex.cash_lbp_to_return from expenses ex where ex.deleted=0 and ex.cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query_cashbox, $cnx));
        return $result;
    }
    public function add_change($info)
    {
        if (!isset($info["cash_lbp_in"])) {
            $info["cash_lbp_in"] = 0;
        }
        if (!isset($info["cash_usd_in"])) {
            $info["cash_usd_in"] = 0;
        }
        if (!isset($info["only_return"])) {
            $info["only_return"] = 0;
        }
        if (!isset($info["invoice_item_id"])) {
            $info["invoice_item_id"] = 0;
        }
        if (!isset($info["invoice_item_return_id"])) {
            $info["invoice_item_return_id"] = 0;
        }
        if (!isset($info["cash_usd_to_return"])) {
            $info["cash_usd_to_return"] = 0;
        }
        if (!isset($info["cash_lbp_to_return"])) {
            $info["cash_lbp_to_return"] = 0;
        }
        if (!isset($info["returned_cash_lbp"])) {
            $info["returned_cash_lbp"] = 0;
        }
        if (!isset($info["returned_cash_usd"])) {
            $info["returned_cash_usd"] = 0;
        }
        $query = "insert into cashbox_changes_info(invoice_id,return_value,added_value,change_date,cashbox_id,old_cashbox_id,cash_usd_to_return,cash_lbp_to_return,returned_cash_lbp,returned_cash_usd,cash_lbp_in,cash_usd_in,rate,only_return,invoice_item_id,invoice_item_return_id) values('" . $info["invoice_id"] . "','" . $info["return_value"] . "','" . $info["added_value"] . "','" . my_sql::datetime_now() . "','" . $info["cashbox_id"] . "','" . $info["old_cashbox_id"] . "','" . $info["cash_usd_to_return"] . "','" . $info["cash_lbp_to_return"] . "','" . $info["returned_cash_lbp"] . "','" . $info["returned_cash_usd"] . "','" . $info["cash_lbp_in"] . "','" . $info["cash_usd_in"] . "','" . $info["rate"] . "','" . $info["only_return"] . "','" . $info["invoice_item_id"] . "','" . $info["invoice_item_return_id"] . "')";
        my_sql::query($query);
    }
}

?>