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
class invoiceModel
{
    public function recurring_invoice_is_generated($invoice)
    {
        $query_base_in_this_month = "select count(id) as num from invoices where id=" . $invoice["id"] . " and deleted=0 and  YEAR(creation_date)=YEAR('" . my_sql::datetime_now() . "') and MONTH(creation_date)=MONTH('" . my_sql::datetime_now() . "') ";
        $result_base_in_this_month = my_sql::fetch_assoc(my_sql::query($query_base_in_this_month));
        if ($result_base_in_this_month[0]["num"] == 0) {
            $query = "select count(id) as num from invoices where recurring=0 and deleted=0 and recurring_parent_id=" . $invoice["id"] . " and YEAR(creation_date)=YEAR('" . my_sql::datetime_now() . "') and MONTH(creation_date)=MONTH('" . my_sql::datetime_now() . "') ";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            if ($result[0]["num"] == 0) {
                return false;
            }
            return true;
        }
        return true;
    }
    public function refresh_commissions($vendor_id)
    {
        $vendor_commission_percentage = 0;
        $query = "select * from users where id=" . $vendor_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $vendor_commission_percentage = $result[0]["commission"];
        }
        my_sql::query("update quotations set vendor_commission_percentage=" . $vendor_commission_percentage . " where created_by=" . $vendor_id . " and vendor_commission_percentage=0");
        my_sql::query("update invoices set vendor_commission_percentage=" . $vendor_commission_percentage . " where (employee_id=" . $vendor_id . " or  id in (select invoice_id from quotations where created_by=" . $vendor_id . " and invoice_id>0 )) and vendor_commission_percentage=0");
    }
    public function get_all_invoices_of_item($item_id)
    {
        $query = "select ii.qty,inv.creation_date,inv.customer_id,c.name as cname,inv.id as invoice_id,ii.final_price_disc_qty,ii.profit from invoice_items ii left join invoices inv on inv.id=ii.invoice_id left join customers c on c.id=inv.customer_id where ii.item_id=" . $item_id . " and ii.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_cashinfo_for_invoice($invoice_id, $cashbox_info)
    {
        $query = "update invoices set cashbox_info='" . json_encode($cashbox_info) . "' where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function get_sum_invoices_by_delivery_code($codes)
    {
        if (0 < count($codes)) {
            $query = "select COALESCE(sum(total_value+invoice_discount), 0) as sum from invoices inv where inv.delivery_ref in ('" . implode("','", $codes) . "') and inv.deleted=0 and auto_closed=0";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            return $result[0]["sum"];
        }
        return 0;
    }
    public function get_all_invoices_already_paid_code($codes)
    {
        if (0 < count($codes)) {
            $query = "select inv.id,inv.delivery_ref from invoices inv where inv.delivery_ref in ('" . implode("','", $codes) . "') and inv.deleted=0 and auto_closed=1";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            return $result;
        }
        return array();
    }
    public function get_all_invoices_by_delivery_code($codes)
    {
        if (0 < count($codes)) {
            $query = "select inv.id,inv.delivery_ref,(inv.total_value+inv.invoice_discount) as amount,inv.customer_id from invoices inv where inv.delivery_ref in ('" . implode("','", $codes) . "') and inv.deleted=0 and auto_closed=0";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            return $result;
        }
        return array();
    }
    public function get_all_invoices_code_not_exist($codes)
    {
        if (0 < count($codes)) {
            $query = "select delivery_ref from invoices inv where inv.delivery_ref in ('" . implode("','", $codes) . "') and inv.deleted=0 and auto_closed=0";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            $exist_code = array();
            for ($i = 0; $i < count($result); $i++) {
                array_push($exist_code, $result[$i]["delivery_ref"]);
            }
            return implode(", ", array_diff($codes, $exist_code));
        }
        return array();
    }
    public function getIMEI($invoice_id)
    {
        $query = "select * from unique_items where invoice_id=" . $invoice_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_invoice_client($invoice_id, $new_client_id)
    {
        $query = "update invoices set customer_id=" . $new_client_id . " where id=" . $invoice_id;
        my_sql::query($query);
        return my_sql::get_mysqli_rows_num();
    }
    public function update_payment_of_invoice($invoice_id, $payment)
    {
        if ($payment == 1) {
            $query = "update invoices set closed=1 where id=" . $invoice_id;
        }
        if ($payment == 2) {
            $query = "update invoices set closed=0 where id=" . $invoice_id;
        }
        my_sql::query($query);
        return my_sql::get_mysqli_rows_num();
    }
    public function get_total_taxes_freight($info)
    {
        $query = "select COALESCE(sum(total_value+invoice_discount)*(tax/100), 0) as sum_taxes,COALESCE(sum(freight),0) as sum_freight from invoices where deleted=0 and total_value>0 and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_taxes_freight_remote($info, $cnx)
    {
        $query = "select COALESCE(sum(total_value+invoice_discount)*(tax/100), 0) as sum_taxes,COALESCE(sum(freight),0) as sum_freight from invoices where deleted=0 and total_value>0 and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function set_invoices_to_send($invoice_id)
    {
        $query = "update invoices set sent_to_telegram=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function update_invoice_more_invoice($invoice_id, $invoice_freight, $invoice_taxes)
    {
        $query = "update invoices set tax=" . $invoice_taxes . ",freight=" . $invoice_freight . " where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function get_invoices_to_send()
    {
        $query = "select * from invoices where sent_to_telegram=0 and total_value>0 and deleted=0 order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_invoices_to_send_by_email()
    {
        $query = "select * from invoices where sent_by_email=0 and customer_id>0 limit 5";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_invoice_sent_by_email($invoice_id)
    {
        $query = "update invoices set sent_by_email=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function add_cash_details($cash_details)
    {
        $cash_info["returned_cash_lbp"] = $cash[0]["returned_cash_lbp"];
        $cash_info["returned_cash_usd"] = $cash[0]["returned_cash_usd"];
        $cash_info["must_return_cash_lbp"] = $cash[0]["must_return_cash_lbp"];
        $cash_info["must_return_cash_usd"] = $cash[0]["must_return_cash_usd"];
        $query = "insert into cash_details(cash_usd,cash_lbp,invoice_id,base_usd_amount,rate,cashbox_id,returned_cash_lbp,returned_cash_usd,must_return_cash_lbp,must_return_cash_usd) " . "values('" . $cash_details["cash_usd"] . "','" . $cash_details["cash_lbp"] . "','" . $cash_details["invoice_id"] . "','" . $cash_details["base_amount"] . "','" . $cash_details["rate"] . "','" . $cash_details["cashbox_id"] . "','" . $cash_details["returned_cash_lbp"] . "','" . $cash_details["returned_cash_usd"] . "','" . $cash_details["must_return_cash_lbp"] . "','" . $cash_details["must_return_cash_usd"] . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_returned_by_invoice($invoice_id)
    {
        $query = "select * from returned_purchases where invoice_id=" . $invoice_id . " and only_return=0 order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_cost($inv_item_id, $cost)
    {
        $query = "update invoice_items set buying_cost=" . $cost . " where id=" . $inv_item_id;
        my_sql::query($query);
    }
    public function get_item_from_inv($inv_item_id)
    {
        $query = "select * from invoice_items where id=" . $inv_item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function recurring_invoices()
    {
        $query = "select * from invoices where recurring>0 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            if (!self::recurring_invoice_is_generated($result[$i])) {
                $timestamp = 0;
                if ($result[$i]["recurring"] == 1) {
                    $timestamp = strtotime("+1 month", strtotime($result[$i]["creation_date"]));
                }
                if ($result[$i]["recurring"] == 2) {
                    $timestamp = strtotime("first day of " . date("F Y"));
                }
                $invoice_date = date("Y-m-d H:i:s", $timestamp);
                $query_invoice = "insert into invoices(" . "creation_date," . "closed," . "customer_id," . "store_id," . "employee_id," . "total_value," . "invoice_discount," . "total_profit," . "profit_after_discount," . "payment_method," . "total_profit_limited," . "total_value_limited," . "deleted," . "discount_note," . "payment_note," . "sales_person," . "total_vat_value," . "vat_value," . "official," . "delivery_cost," . "delivery_ref," . "delivery_done," . "invoice_id," . "invoice_customer_referrer," . "cashback_value," . "currency_id," . "to_curreny_id," . "rate," . "recurring," . "recurring_parent_id" . ") values (" . "'" . $invoice_date . "'," . "0," . "'" . $result[$i]["customer_id"] . "'," . "'" . $result[$i]["store_id"] . "'," . "'" . $result[$i]["employee_id"] . "'," . "'" . $result[$i]["total_value"] . "'," . "'" . $result[$i]["invoice_discount"] . "'," . "'" . $result[$i]["total_profit"] . "'," . "'" . $result[$i]["profit_after_discount"] . "'," . "'" . $result[$i]["payment_method"] . "'," . "'" . $result[$i]["total_profit_limited"] . "'," . "'" . $result[$i]["total_value_limited"] . "'," . "'" . $result[$i]["deleted"] . "'," . "'" . $result[$i]["discount_note"] . "'," . "'" . $result[$i]["payment_note"] . "'," . "'" . $result[$i]["sales_person"] . "'," . "'" . $result[$i]["total_vat_value"] . "'," . "'" . $result[$i]["vat_value"] . "'," . "'" . $result[$i]["official"] . "'," . "'" . $result[$i]["delivery_cost"] . "'," . "'" . $result[$i]["delivery_ref"] . "'," . "'" . $result[$i]["delivery_done"] . "'," . "'" . $result[$i]["invoice_id"] . "'," . "'" . $result[$i]["invoice_customer_referrer"] . "'," . "'" . $result[$i]["cashback_value"] . "'," . "'" . $result[$i]["currency_id"] . "'," . "'" . $result[$i]["to_curreny_id"] . "'," . "'" . $result[$i]["rate"] . "'," . "0," . "'" . $result[$i]["id"] . "')";
                my_sql::query($query_invoice);
                $invid = my_sql::get_mysqli_insert_id();
                $items = self::get_items_for_invoice($result[$i]["id"]);
                for ($k = 0; $k < count($items); $k++) {
                    $qry = "insert into invoice_items (" . "invoice_id," . "item_id," . "qty," . "buying_cost," . "vat," . "selling_price," . "discount," . "final_cost_vat_qty," . "final_price_disc_qty," . "profit," . "vat_value," . "description," . "price_after_manual_discount," . "user_role," . "deleted," . "additional_description" . ") values(" . "'" . $invid . "'," . "'" . $items[$k]["item_id"] . "'," . "'" . $items[$k]["qty"] . "'," . "'" . $items[$k]["buying_cost"] . "'," . "'" . $items[$k]["vat"] . "'," . "'" . $items[$k]["selling_price"] . "'," . "'" . $items[$k]["discount"] . "'," . "'" . $items[$k]["final_cost_vat_qty"] . "'," . "'" . $items[$k]["final_price_disc_qty"] . "'," . "'" . $items[$k]["profit"] . "'," . "'" . $items[$k]["vat_value"] . "'," . "'" . $items[$k]["description"] . "'," . "'" . $items[$k]["price_after_manual_discount"] . "'," . "'" . $items[$k]["user_role"] . "'," . "'" . $items[$k]["deleted"] . "'," . "'" . $items[$k]["additional_description"] . "'" . ")";
                    my_sql::query($qry);
                }
            }
        }
    }
    public function get_default_curreny()
    {
        $query = "select id from currencies where system_default=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["id"];
    }
    public function get_second_curreny()
    {
        $query = "select id from currencies where second_currency=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return $result[0]["id"];
        }
        return 0;
    }
    public function update_second_currency_rate($invoice_id, $to_second_currency_rate)
    {
        $query = "update invoices set rate='" . $to_second_currency_rate . "' where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function assign_to_acc($returned_id, $on_customer_acc_id)
    {
        $query = "update returned_purchases set on_account_id='" . $on_customer_acc_id . "' where id=" . $returned_id;
        my_sql::query($query);
    }
    public function update_add_item_description($invoice_item_id, $description)
    {
        $query = "update invoice_items set additional_description='" . $description . "' where id=" . $invoice_item_id;
        my_sql::query($query);
    }
    public function generateInvoiceId($store_id, $employee_id, $payment_method = "", $payment_note, $sales_person, $vat_value, $cus_ref)
    {
        $days = 15;
        $query_dn = "select * from settings where name='default_due_date_invoice'";
        $result_nb = my_sql::fetch_assoc(my_sql::query($query_dn));
        if (0 < count($result_nb)) {
            $days = $result_nb[0]["value"];
        }
        $query_tx = "select * from settings where name='invoice_taxable_enabled'";
        $result_tx = my_sql::fetch_assoc(my_sql::query($query_tx));
        if (0 < count($result_tx) && $result_tx[0]["invoice_taxable_enabled"] == 0) {
            $vat_value = 0;
        }
        $query = "insert into invoices(creation_date,store_id,employee_id,cashbox_id,payment_method,due_date,payment_note,sales_person,vat_value,invoice_customer_referrer,currency_id,to_curreny_id) values('" . my_sql::datetime_now() . "'," . $store_id . "," . $employee_id . ",'" . ($_SESSION["cashbox_id"] ? $_SESSION["cashbox_id"] : 0) . "'," . $payment_method . ",DATE_ADD('" . my_sql::datetime_now() . "', INTERVAL " . $days . " DAY),'" . $payment_note . "'," . $sales_person . "," . $vat_value . "," . $cus_ref . "," . self::get_default_curreny() . "," . self::get_second_curreny() . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function generateInvoiceId_station($store_id, $employee_id, $payment_method = "", $payment_note, $sales_person, $vat_value, $cus_ref)
    {
        $days = 15;
        $query_dn = "select * from settings where name='default_due_date_invoice'";
        $result_nb = my_sql::fetch_assoc(my_sql::query($query_dn));
        if (0 < count($result_nb)) {
            $days = $result_nb[0]["value"];
        }
        $query_tx = "select * from settings where name='invoice_taxable_enabled'";
        $result_tx = my_sql::fetch_assoc(my_sql::query($query_tx));
        if (0 < count($result_tx) && $result_tx[0]["invoice_taxable_enabled"] == 0) {
            $vat_value = 0;
        }
        $query = "insert into invoices(creation_date,store_id,employee_id,cashbox_id,payment_method,due_date,payment_note,sales_person,vat_value,invoice_customer_referrer,currency_id,to_curreny_id,station_generated) values('" . my_sql::datetime_now() . "'," . $store_id . "," . $employee_id . ",'" . ($_SESSION["cashbox_id"] ? $_SESSION["cashbox_id"] : 0) . "'," . $payment_method . ",DATE_ADD('" . my_sql::datetime_now() . "', INTERVAL " . $days . " DAY),'" . $payment_note . "'," . $sales_person . "," . $vat_value . "," . $cus_ref . "," . self::get_default_curreny() . "," . self::get_second_curreny() . ",1)";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_total_cashback($cashbox_id)
    {
        $cashback_query = "select COALESCE(sum(cashback_value), 0) as sum from cashback where cashbox_id=" . $cashbox_id . " and deleted=0";
        $cashback_result = my_sql::fetch_assoc(my_sql::query($cashback_query));
        return $cashback_result[0]["sum"];
    }
    public function update_invoice_date($invoice_id, $date)
    {
        $query = "update invoices set creation_date='" . $date . "' where id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_cashback($info)
    {
        $query = "insert into cashback(customer_id,cashbox_id,creation_date,currency_id,cashback_value,by_user_id) values(" . $info["customer_id"] . "," . $info["cashbox_id"] . ",'" . my_sql::datetime_now() . "'," . $info["currency_id"] . "," . $info["cashback_value"] . "," . $_SESSION["id"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function calculate_cashback_value($invoice_id, $settings)
    {
        $invoice_items = self::getItemsOfInvoice($invoice_id);
        $total_cashback_value = 0;
        for ($i = 0; $i < count($invoice_items); $i++) {
            if ($invoice_items[$i]["discount"] == 0) {
                $total_cashback_value += $invoice_items[$i]["selling_price"] * $invoice_items[$i]["qty"] * $settings["cashback_not_discounted_percentage"] / 100;
            } else {
                if ($settings["cashback_discount_limit"] <= $invoice_items[$i]["discount"]) {
                    $total_cashback_value += 0;
                } else {
                    if (0 < $invoice_items[$i]["discount"] && $invoice_items[$i]["discount"] < $settings["cashback_discount_limit"]) {
                        $total_cashback_value += $invoice_items[$i]["selling_price"] * $invoice_items[$i]["qty"] * $settings["cashback_discounted_percentage"] / 100;
                    }
                }
            }
        }
        $query = "update invoices set cashback_value=" . $total_cashback_value . " where id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_invoices_pending_deliveries($filter)
    {
        $filter_query = " and delivery>0 ";
        if (0 < $filter["status_id"]) {
            if ($filter["status_id"] == 1) {
                $filter_query = " and delivery=1 ";
            }
            if ($filter["status_id"] == 2) {
                $filter_query = " and delivery=2 ";
            }
            if ($filter["status_id"] == 3) {
                $filter_query = " and delivery=3 ";
            }
        }
        $query = "select * from invoices where 1 " . $filter_query . " and deleted=0 and other_branche=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function change_delivery_reference($invoice_id, $delivery_reference)
    {
        $query = "update invoices set delivery_ref='" . $delivery_reference . "' where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function set_delivery_as_done($invoice_id)
    {
        $query = "update invoices set delivery_done=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function update_official_nb($invoice_id)
    {
        $nb = self::tax_invoice_number_generate();
        my_sql::query("update invoices set official=1,invoice_nb_official=" . $nb . " where id=" . $invoice_id);
    }
    public function update_official_tax_value($invoice_id)
    {
        $query_tax = "select * from settings where name='vat'";
        $result_tax = my_sql::fetch_assoc(my_sql::query($query_tax));
        my_sql::query("update invoices set tax='" . ($result_tax[0]["value"] - 1) * 100 . "',vat_value=" . $result_tax[0]["value"] . " where id=" . $invoice_id . " and official=1");
    }
    public function tax_invoice_number_generate()
    {
        $query = "select MAX(invoice_nb_official) as mx from invoices";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return $result[0]["mx"] + 1;
        }
        return 1;
    }
    public function generate_empty_invoice($store_id, $employee_id, $vat_value)
    {
        $days = 15;
        $query_dn = "select * from settings where name='default_due_date_invoice'";
        $result_nb = my_sql::fetch_assoc(my_sql::query($query_dn));
        if (0 < count($result_nb)) {
            $days = $result_nb[0]["value"];
        }
        $query_tx = "select * from settings where name='invoice_taxable_enabled'";
        $result_tx = my_sql::fetch_assoc(my_sql::query($query_tx));
        if (0 < count($result_tx) && $result_tx[0]["invoice_taxable_enabled"] == 0) {
            $vat_value = 0;
        }
        $query = "insert into invoices(creation_date,store_id,employee_id,currency_id,to_curreny_id,vat_value,due_date) values('" . my_sql::datetime_now() . "'," . $store_id . "," . $employee_id . "," . self::get_default_curreny() . "," . self::get_second_curreny() . "," . $vat_value . ",DATE_ADD('" . my_sql::datetime_now() . "', INTERVAL " . $days . " DAY))";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function generate_empty_invoice_for_branch($store_id, $employee_id, $vat_value, $branch_id)
    {
        $days = 15;
        $query_dn = "select * from settings where name='default_due_date_invoice'";
        $result_nb = my_sql::fetch_assoc(my_sql::query($query_dn));
        if (0 < count($result_nb)) {
            $days = $result_nb[0]["value"];
        }
        $query_tx = "select * from settings where name='invoice_taxable_enabled'";
        $result_tx = my_sql::fetch_assoc(my_sql::query($query_tx));
        if (0 < count($result_tx) && $result_tx[0]["invoice_taxable_enabled"] == 0) {
            $vat_value = 0;
        }
        $query = "insert into invoices(creation_date,store_id,employee_id,currency_id,to_curreny_id,vat_value,due_date,new_branch_id) values('" . my_sql::datetime_now() . "'," . $store_id . "," . $employee_id . "," . self::get_default_curreny() . "," . self::get_second_curreny() . "," . $vat_value . ",DATE_ADD('" . my_sql::datetime_now() . "', INTERVAL " . $days . " DAY)," . $branch_id . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function generateInvoiceId_manual($store_id, $employee_id, $vat)
    {
        $query_tx = "select * from settings where name='invoice_taxable_enabled'";
        $result_tx = my_sql::fetch_assoc(my_sql::query($query_tx));
        if (0 < count($result_tx) && $result_tx[0]["invoice_taxable_enabled"] == 0) {
            $vat = 0;
        }
        $query = "insert into invoices(creation_date,store_id,employee_id,currency_id,to_curreny_id,vat_value) values('" . my_sql::datetime_now() . "'," . $store_id . "," . $employee_id . "," . self::get_default_curreny() . "," . self::get_second_curreny() . "," . $vat . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function update_delivery_pos($invoice_id, $delivery_cost, $delivery_ref)
    {
        $query = "update invoices set delivery=1,delivery_cost=" . $delivery_cost . ",delivery_ref='" . $delivery_ref . "' where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function status_changed($invoice_id, $status)
    {
        $query = "update invoices set delivery=" . $status . " where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function customer_latest_price($customer_id, $item_id)
    {
        $query = "select inv.creation_date as creation_date,inv.id,inv_it.final_price_disc_qty,inv_it.qty from invoice_items inv_it,invoices inv where inv.id=inv_it.invoice_id and inv.deleted=0 and inv_it.item_id=" . $item_id . " and inv.customer_id=" . $customer_id . " and inv.other_branche=0 order by id desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_official_nb_manual($invoice_id, $nb)
    {
        $query = "update invoices set invoice_nb_official=" . $nb . " where id=" . $invoice_id;
        my_sql::query($query);
        if ($nb == 0) {
            my_sql::query("update invoices set invoice_nb_official=" . $nb . " where id=" . $invoice_id);
        } else {
            my_sql::query("update invoices set invoice_nb_official=" . $nb . " where id=" . $invoice_id);
        }
    }
    public function set_status($invoice_id)
    {
        $query = "update invoices set closed=0,auto_closed=0 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function check_if_credit_transfer($id)
    {
        $query = "select * from invoice_items where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function check_if_fees($id)
    {
        $query = "select * from mobile_credits_history where invoice_item_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalReturns($info)
    {
        $query = "select COALESCE(sum(return_value), 0) as sum from cashbox_changes_info where date(change_date)>='" . $info["start_date"] . "' and date(change_date)<='" . $info["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalReturns_remote($info, $cnx)
    {
        $query = "select COALESCE(sum(return_value), 0) as sum from cashbox_changes_info where date(change_date)>='" . $info["start_date"] . "' and date(change_date)<='" . $info["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function check_internationnal_call($call)
    {
        $query = "select count(id) as num from invoice_items where deleted=0 and description='" . $call . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_international_calls($date)
    {
        $query = "select * from invoice_items where deleted=0 and invoice_id in (select id from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date[0] . "' and date(creation_date)<='" . $date[1] . "') and international_calls=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function generateInvoiceIdForDeliveryItems()
    {
        $query = "insert into invoices(creation_date,store_id,due_date,currency_id,to_curreny_id) values('" . my_sql::datetime_now() . "'," . $_SESSION["store_id"] . ",DATE_ADD('" . my_sql::datetime_now() . "', INTERVAL 15 DAY)," . self::get_default_curreny() . "," . self::get_second_curreny() . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_sold_items_for_item_movement($store_id, $date)
    {
        $query = "select item_id,sum(qty) as item_qty,sum(final_price_disc_qty) as item_price_sum,sum(profit) as item_total_profit from invoice_items where invoice_id in (SELECT id FROM `invoices` WHERE other_branche=0 and MONTH(creation_date)=MONTH('" . $date . "') and YEAR(creation_date) = YEAR('" . $date . "') and store_id=" . $store_id . ") GROUP BY item_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function addDiscount($invoice_id, $value, $discount_note)
    {
        $query = "update invoices set invoice_discount=" . $value . ",discount_note='" . $discount_note . "' where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function get_due_invoices()
    {
        $query = "select count(id) as num from invoices where other_branche=0 and closed=0 and date('" . my_sql::datetime_now() . "')>=date(due_date) and deleted=0 and total_value>0 and auto_closed=1 and closed_date IS NOT NULL order by due_date asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_due_invoices_nb()
    {
        $query = "select count(id) as num from invoices where other_branche=0 and closed=0 and date('" . my_sql::datetime_now() . "')>=date(due_date) and deleted=0 and total_value>0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_invoice_by_id($id)
    {
        $query = "select * from invoices where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_invoice_item($item_id, $qty, $discount)
    {
        $query = "update invoice_items set qty='" . $qty . "',discount='" . $discount . "' where id=" . $item_id;
        my_sql::query($query);
    }
    public function update_invoice($info)
    {
        $query = "update invoices set invoice_discount=-" . $info["invoice_discount"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function delete_invoice($invoice_id)
    {
        $query = "update invoices set deleted=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function update_return_and_set_deleted($invoice_id)
    {
        $query = "update returned_purchases set invoice_deleted=1 where invoice_id=" . $invoice_id;
        my_sql::query($query);
    }
    public function cancelDiscount($invoice_id)
    {
        $query = "update invoices set invoice_discount=0 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function update_salesperson_of_invoice($invoice_id, $salesperson_id)
    {
        $query = "update invoices set sales_person=" . $salesperson_id . " where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function save_manual_invoice_items($invoice_item_id, $price, $discount, $vat, $qty)
    {
        $query_old = "select * from invoice_items  where id=" . $invoice_item_id;
        $result_old = my_sql::fetch_assoc(my_sql::query($query_old));
        $query = "update invoice_items set selling_price=" . $price . ",discount=" . $discount . ",vat=" . $vat . ",qty=" . $qty . " where id=" . $invoice_item_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            if (floatval($price) != $result_old[0]["selling_price"]) {
                $info = array();
                $info["operator_id"] = $_SESSION["id"];
                $info["related_to_item_id"] = $result_old[0]["item_id"];
                $info["description"] = "Item (IT-" . $result_old[0]["item_id"] . ") price changed from " . floatval($result_old[0]["selling_price"]) . " to " . floatval($price);
                $info["log_type"] = 5;
                $info["other_info"] = $result_old[0]["invoice_id"];
                $info["related_to_client_id"] = 0;
                $query = "insert into global_logs(created_by,creation_date,related_to_item_id,description,log_type,other_info,related_to_client_id) " . "values('" . $info["operator_id"] . "','" . my_sql::datetime_now() . "','" . $info["related_to_item_id"] . "','" . $info["description"] . "','" . $info["log_type"] . "','" . $info["other_info"] . "','" . $info["related_to_client_id"] . "')";
                my_sql::query($query);
            }
            if (floatval($discount) != $result_old[0]["discount"]) {
                $info = array();
                $info["operator_id"] = $_SESSION["id"];
                $info["related_to_item_id"] = $result_old[0]["item_id"];
                $info["description"] = "Item (IT-" . $result_old[0]["item_id"] . ") discount changed from " . floatval($result_old[0]["discount"]) . "% to " . floatval($discount) . "%";
                $info["log_type"] = 5;
                $info["other_info"] = $result_old[0]["invoice_id"];
                $info["related_to_client_id"] = 0;
                $query = "insert into global_logs(created_by,creation_date,related_to_item_id,description,log_type,other_info,related_to_client_id) " . "values('" . $info["operator_id"] . "','" . my_sql::datetime_now() . "','" . $info["related_to_item_id"] . "','" . $info["description"] . "','" . $info["log_type"] . "','" . $info["other_info"] . "','" . $info["related_to_client_id"] . "')";
                my_sql::query($query);
            }
            if (floatval($qty) != $result_old[0]["qty"]) {
                $info = array();
                $info["operator_id"] = $_SESSION["id"];
                $info["related_to_item_id"] = $result_old[0]["item_id"];
                $info["description"] = "Item (IT-" . $result_old[0]["item_id"] . ") quantity changed from " . floatval($result_old[0]["qty"]) . " to " . floatval($qty) . "";
                $info["log_type"] = 5;
                $info["other_info"] = $result_old[0]["invoice_id"];
                $info["related_to_client_id"] = 0;
                $query = "insert into global_logs(created_by,creation_date,related_to_item_id,description,log_type,other_info,related_to_client_id) " . "values('" . $info["operator_id"] . "','" . my_sql::datetime_now() . "','" . $info["related_to_item_id"] . "','" . $info["description"] . "','" . $info["log_type"] . "','" . $info["other_info"] . "','" . $info["related_to_client_id"] . "')";
                my_sql::query($query);
            }
        }
    }
    public function delete_item_from_manual_invoice($invoice_item_id)
    {
        $query = "update invoice_items set deleted=1 where id=" . $invoice_item_id;
        my_sql::query($query);
    }
    public function updateCustomerInvoice($invoice_id, $customer_id)
    {
        $query = "update invoices set customer_id=" . $customer_id . " where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function resetCustomerInvoice($invoice_id)
    {
        $query = "update invoices set customer_id=NULL where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function update_invoice_discount($invoice_id, $invoice_discount)
    {
        $query = "update invoices set invoice_discount=-" . $invoice_discount . " where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function recurring_update($invoice_id, $recurring_id)
    {
        $query = "update invoices set recurring=" . $recurring_id . " where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function update_invoice_info_manual($invoice_id, $invoice_discount, $note, $salesman, $rate, $invoice_date, $paid, $change_date)
    {
        $query_old = "select * from invoices  where id=" . $invoice_id;
        $result_old = my_sql::fetch_assoc(my_sql::query($query_old));
        $query_time = "select CURRENT_TIME() as tm";
        $result_time = my_sql::fetch_assoc(my_sql::query($query_time));
        $method = 1;
        $closed = 0;
        if ($paid == 1) {
            $closed = 1;
        }
        $change_date_qry = "";
        if ($change_date == 1) {
            $change_date_qry = ",creation_date='" . $invoice_date . " " . $result_time[0]["tm"] . "'";
        }
        $query = "update invoices set closed=" . $closed . ",payment_method=" . $method . ", invoice_discount=-" . $invoice_discount . ",payment_note='" . $note . "',sales_person=" . $salesman . ",rate=" . $rate . " " . $change_date_qry . " where id=" . $invoice_id;
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num() && floatval($invoice_discount) != floatval($result_old[0]["invoice_discount"])) {
            $info = array();
            $info["operator_id"] = $_SESSION["id"];
            $info["related_to_item_id"] = $result_old[0]["item_id"];
            $info["description"] = "Invoice discount changed from " . abs(floatval($result_old[0]["invoice_discount"])) . " to " . floatval($invoice_discount);
            $info["log_type"] = 5;
            $info["other_info"] = $invoice_id;
            $info["related_to_client_id"] = 0;
            $query = "insert into global_logs(created_by,creation_date,related_to_item_id,description,log_type,other_info,related_to_client_id) " . "values('" . $info["operator_id"] . "','" . my_sql::datetime_now() . "','" . $info["related_to_item_id"] . "','" . $info["description"] . "','" . $info["log_type"] . "','" . $info["other_info"] . "','" . $info["related_to_client_id"] . "')";
            my_sql::query($query);
        }
    }
    public function setInvoiceAsPaid($invoice_id)
    {
        $query = "update invoices set closed=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function setInvoiceAsUnpaid($invoice_id)
    {
        $query = "update invoices set closed=0 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function delete_customer_invoices($customer_id)
    {
        $query = "update invoices set deleted=1 where customer_id=" . $customer_id;
        my_sql::query($query);
    }
    public function delete_customer_invoice_items($invoice_id)
    {
        $query = "update invoice_items set deleted=1 where invoice_id=" . $invoice_id;
        my_sql::query($query);
    }
    public function delete_customer_invoice_items_returned($invoice_id)
    {
        $query = "update returned_purchases set deleted=1 where invoice_id=" . $invoice_id;
        my_sql::query($query);
    }
    public function addItemsToInvoice($info)
    {
        $query = "insert into invoice_items(invoice_id,item_id,qty,buying_cost,vat,selling_price,discount,final_cost_vat_qty,final_price_disc_qty,profit,vat_value,mobile_transfer_credits,user_role,official) values(" . $info["invoice_id"] . "," . $info["item_id"] . "," . $info["qty"] . "," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["final_cost"] . "," . $info["final_price"] . "," . $info["profit"] . "," . $info["vat_value"] . ",NULL," . $_SESSION["role"] . "," . $info["is_official"] . ");";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function addAllItemsToInvoice($query)
    {
        try {
            my_sql::query($query);
            return NULL;
        } catch (Exception $e) {
        }
    }
    public function getCustomItemsWasSold()
    {
        $query = "select inv_item.id,inv_item.description,inv_item.buying_cost,inv_item.final_price_disc_qty,inv.creation_date from invoice_items inv_item INNER JOIN invoices inv ON inv.id=inv_item.invoice_id and inv_item.custom_item in (1,2,3) and inv_item.deleted=0 and inv.other_branche=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function addCustomItemsToInvoice($info)
    {
        $query = "insert into invoice_items(invoice_id,item_id,qty,buying_cost,vat,selling_price,discount,final_cost_vat_qty,final_price_disc_qty,profit,vat_value,description,mobile_transfer_credits,custom_item,user_role) values(" . $info["invoice_id"] . ",NULL," . $info["qty"] . "," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["final_cost"] . "," . $info["final_price"] . "," . $info["profit"] . "," . $info["vat_value"] . "," . $info["description"] . "," . $info["mobile_transfer_item"] . "," . $info["custom_item"] . "," . $_SESSION["role"] . ")";
        my_sql::query($query);
    }
    public function addTransferCreditsToInvoice($info)
    {
        $query = "insert into invoice_items(invoice_id,item_id,qty,buying_cost,vat,selling_price,discount,final_cost_vat_qty,final_price_disc_qty,profit,vat_value,description,mobile_transfer_credits,custom_item,user_role,pos_discounted) values(" . $info["invoice_id"] . ",NULL," . $info["qty"] . "," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["final_cost"] . "," . $info["final_price"] . "," . $info["profit"] . "," . $info["vat_value"] . "," . $info["description"] . "," . $info["mobile_transfer_item"] . ",0," . $_SESSION["role"] . "," . $info["manual_discounted"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_item_from_invoice($id)
    {
        $query = "select * from invoice_items where id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_item_from_invoice_with_details($id)
    {
        $query = "select inv_it.final_price_disc_qty,inv_it.qty,inv.rate from invoice_items inv_it left join invoices inv on inv_it.invoice_id=inv.id where inv_it.id=" . $id . " and inv_it.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_for_invoice($id)
    {
        $query = "select * from invoice_items where invoice_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function search_invoice_by_ref($id)
    {
        $query = "select id from invoices where other_branche=0 and invoice_nb_official=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_cost_of_custom_item($info)
    {
        $query = "update invoice_items set buying_cost=" . $info["items_cost"] . ",final_cost_vat_qty=" . $info["items_cost"] . ",profit=final_price_disc_qty-final_cost_vat_qty where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function closeInvoice($invoice_id)
    {
        $query = "update invoices set closed=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function setAutoClosedInvoice($invoice_id)
    {
        $query = "update invoices set closed_date='" . my_sql::datetime_now() . "',auto_closed=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function getUnpaidInvoicesOfCustomers($customer_id)
    {
        $query = "select * from invoices where other_branche=0 and customer_id=" . $customer_id . " and closed=0 order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoicesOfCustomers($customer_id)
    {
        if ($_SESSION["role"] == 3) {
            $query = "select * from invoices where other_branche=0 and customer_id=" . $customer_id . " and official=1 and deleted=0 ";
        } else {
            $query = "select * from invoices where other_branche=0 and customer_id=" . $customer_id . " and deleted=0 ";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_due_date($invoice_id, $date)
    {
        $query = "update invoices set due_date='" . $date . "' where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function dismiss_due_date($invoice_id)
    {
        $query = "update invoices set due_date_dismiss=1 where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function set_as_new_item($id)
    {
        $query = "update invoice_items set added_new=1 where id=" . $id;
        my_sql::query($query);
    }
    public function getInvoicesMustPay($store_id)
    {
        $query = "select * from invoices where other_branche=0 and closed=0 and deleted=0 and due_date_dismiss=0  order by due_date asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoices($store_id)
    {
        $query = "select * from invoices where other_branche=0 and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoices_search_by_note($note, $settings)
    {
        $hide_zero_invoices = "";
        if ($settings["hide_zero_invoices"] == 1) {
            $hide_zero_invoices = " (total_value>0 || invoice_discount>0) and ";
        }
        $query = "select * from invoices where other_branche=0 and " . $hide_zero_invoices . " deleted=0 and payment_note like '%" . $note . "%'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoicesForGarage($store_id)
    {
        $query = "select id,total_value,invoice_discount from invoices where other_branche=0 and store_id=" . $store_id . " and deleted=0 limit 5000";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_quick_invoices($date_range, $status)
    {
        if ($status == 0) {
            $query = "select * from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        }
        if ($status == 1) {
            $query = "select * from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and closed=1 and auto_closed=0 and payment_method=" . $status;
        }
        if ($status == 2) {
            $query = "select * from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and closed=1 and payment_method=" . $status;
        }
        if ($status == 4) {
            $query = "select * from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and (closed=0 || auto_closed=1)";
        }
        if ($status == 3) {
            $query = "select * from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and closed=1 and auto_closed=0 and payment_method=" . $status;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoices_list($store_id, $date_range, $settings)
    {
        $hide_zero_invoices = "";
        if ($settings["hide_zero_invoices"] == 1) {
            $hide_zero_invoices = " (total_value>0 || invoice_discount>0) and ";
        }
        if ($settings["invoice_show_only_for_sold_pos"] == 1) {
            $query = "select * from invoices where other_branche=0 and " . $hide_zero_invoices . " store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and employee_id=" . $_SESSION["id"];
        } else {
            $query = "select * from invoices where other_branche=0 and " . $hide_zero_invoices . " store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' ";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoices_list_filtered($store_id, $date_range, $settings, $operations_type)
    {
        $hide_zero_invoices = "";
        if ($settings["hide_zero_invoices"] == 1) {
            $hide_zero_invoices = " (total_value>0 || invoice_discount>0) and ";
        }
        ${$operations_ffltr} = "";
        if ($operations_type == 1) {
            ${$operations_ffltr} = " and cashbox_id=" . $_SESSION["cashbox_id"] . " ";
        } else {
            if ($operations_type == 1) {
                ${$operations_ffltr} = " and (employee_id=" . $_SESSION["id"] . " or id in (select invoice_id from quotations where deleted=0 and created_by=" . $_SESSION["id"] . ") ) ";
            }
        }
        if ($settings["invoice_show_only_for_sold_pos"] == 1) {
            $query = "select * from invoices where other_branche=0 and " . $hide_zero_invoices . " store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and (employee_id=" . $_SESSION["id"] . " or id in (select invoice_id from quotations where deleted=0 and created_by=" . $_SESSION["id"] . "))";
        } else {
            $query = "select * from invoices where other_branche=0 and " . $hide_zero_invoices . " store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' " . ${$operations_ffltr} . " ";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoices_other_branches_list($store_id, $date_range, $settings)
    {
        $hide_zero_invoices = "";
        if ($settings["hide_zero_invoices"] == 1) {
            $hide_zero_invoices = " (total_value>0 || invoice_discount>0) and ";
        }
        if ($settings["invoice_show_only_for_sold_pos"] == 1) {
            $query = "select * from invoices where other_branche>0 and " . $hide_zero_invoices . " store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and employee_id=" . $_SESSION["id"];
        } else {
            $query = "select * from invoices where other_branche>0 and " . $hide_zero_invoices . " store_id=" . $store_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' ";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoicesSwitch($store_id, $daterange, $filter_invoices, $settings, $filter_salesperson, $vendor_id, $is_taxable)
    {
        $closed_condition = "";
        if ($filter_invoices == 1) {
            $closed_condition = " and closed=0 ";
        }
        if ($filter_invoices == 2) {
            $closed_condition = " and closed=1 ";
        }
        $hide_zero_invoices = "";
        if ($settings["hide_zero_invoices"] == 1) {
            $hide_zero_invoices = " (total_value>0 || invoice_discount>0) and ";
        }
        $filter_salesperson_condition = "";
        if (0 < $filter_salesperson) {
            $filter_salesperson_condition = " and sales_person=" . $filter_salesperson . " ";
        }
        $filter_vendor_condition = "";
        if (0 < $vendor_id) {
            $filter_vendor_condition = " and (employee_id=" . $vendor_id . " or id in (select invoice_id from quotations where deleted=0 and created_by=" . $vendor_id . " )) ";
        }
        $filter_taxable_condition = "";
        if (0 < $is_taxable) {
            if ($is_taxable == 1) {
                $filter_taxable_condition = " and invoice_nb_official>0 ";
            } else {
                $filter_taxable_condition = " and invoice_nb_official=0 ";
            }
        }
        $query = "select * from invoices where other_branche=0 and " . $hide_zero_invoices . "  date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' " . $closed_condition . " " . $filter_salesperson_condition . " " . $filter_vendor_condition . " " . $filter_taxable_condition . " order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoices()
    {
        $query = "select * from invoices where other_branche=0 order by id desc limit 60";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoiceById($id)
    {
        $query_dn = "select * from settings where name='invoice_show_only_for_sold_pos'";
        $result_nb = my_sql::fetch_assoc(my_sql::query($query_dn));
        $ffltr = "";
        if ($result_nb[0]["value"] == 1) {
            $ffltr = " and (employee_id=" . $_SESSION["id"] . " or id in (select invoice_id from quotations where deleted=0 and created_by=" . $_SESSION["id"] . ") )";
        }
        if ($_SESSION["role"] == 1) {
            $ffltr = "";
        }
        $query = "select * from invoices where id=" . $id . $ffltr;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoiceByIdOtherBranche($id, $branche)
    {
        $query = "select * from invoices where invoice_id=" . $id . " and other_branche=" . $branche;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoiceByCatAndParenCat($cat_id, $cashbox_id)
    {
        $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.qty,inv_it.description,inv_it.discount from invoices inv,invoice_items inv_it where inv.other_branche=0 and inv.cashbox_id=" . $cashbox_id . " and inv.id=inv_it.invoice_id and inv.deleted=0 and inv_it.item_id in (select id from items where item_category=" . $cat_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsNbOfInvoice($invoice_id)
    {
        $query = "select sum(qty) as num from invoice_items where invoice_id=" . $invoice_id . "";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalSoldItems($info)
    {
        $query = "select sum(qty) as num from invoice_items where invoice_id in (select id from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "') ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function getTotalSoldItems_remote($info, $cnx)
    {
        $query = "select sum(qty) as num from invoice_items where invoice_id in (select id from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "') ";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["num"];
    }
    public function getInvoiceImeis($invoice_id, $item_id)
    {
        if ($item_id != NULL) {
            $query = "select * from unique_items where invoice_id=" . $invoice_id . " and item_id=" . $item_id;
            $result = my_sql::fetch_assoc(my_sql::query($query));
        } else {
            $result = array();
        }
        return $result;
    }
    public function getInvoiceItemsDetails($invoice_id)
    {
        $info = array();
        $info["invoice"] = self::getInvoiceById($invoice_id);
        $info["invoice_items"] = self::getItemsOfInvoice($invoice_id);
        return $info;
    }
    public function getItemsOfInvoice($invoice_id)
    {
        $query = "select * from invoice_items where invoice_id=" . $invoice_id . " and deleted=0 order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsOfInvoiceDetails($invoice_id)
    {
        $query = "select id,item_id,qty,final_price_disc_qty,description,selling_price from invoice_items where invoice_id=" . $invoice_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsOfInvoice_Basic($invoice_id)
    {
        $query = "select * from invoice_items where invoice_id=" . $invoice_id . " and deleted=0 and item_change_cashbox=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsOfInvoice_Basic_Details($invoice_id)
    {
        $query = "select inv_it.item_id,inv_it.discount,it.description,inv_it.qty,inv_it.final_price_disc_qty from invoice_items inv_it left join items it on inv_it.item_id=it.id where inv_it.invoice_id=" . $invoice_id . " and inv_it.deleted=0 and inv_it.item_change_cashbox=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function checkIfNoItemsInInvoice($invoice_id)
    {
        $query = "select count(id) as num from invoice_items where invoice_id=" . $invoice_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAmount($invoice_id)
    {
        $query = "select COALESCE(sum(final_price_disc_qty), 0) as sum from invoice_items where invoice_id=" . $invoice_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAmountVatDiff($invoice_id)
    {
        $query = "select COALESCE(sum(final_price_disc_qty*(vat_value-1)), 0) as sum from invoice_items where invoice_id=" . $invoice_id . " and deleted=0 and vat=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAmount_limited($invoice_id)
    {
        $query = "select COALESCE(sum(final_price_disc_qty), 0) as sum from invoice_items where invoice_id=" . $invoice_id . " and user_role=4 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAmountVatDiff_limited($invoice_id)
    {
        $query = "select COALESCE(sum(final_price_disc_qty*(vat_value-1)), 0) as sum from invoice_items where invoice_id=" . $invoice_id . " and user_role=4 and deleted=0 and vat=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function resend_email($invoice_id)
    {
        my_sql::query("update invoices set sent_by_email=0 where id=" . $invoice_id);
    }
    public function getSalesByCashboxID($cashbox_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and closed=1 and auto_closed=0 and payment_method=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getCashSalesInvoicesByCashboxID($cashbox_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and closed=1 and auto_closed=0 and payment_method=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query1 = "select COALESCE(sum(ABS(qty*(selling_price*(1-discount/100)))), 0) as sum from returned_purchases where old_cashbox_id=" . $cashbox_id . " and invoice_id in (select id from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and closed=1 and auto_closed=0 and payment_method=1)";
        $result1 = my_sql::fetch_assoc(my_sql::query($query1));
        return $result[0]["sum"] + $result1[0]["sum"];
    }
    public function getCashSalesInvoicesByDateAndStore($info)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $info["store_id"] . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and closed=1 and auto_closed=0 and payment_method=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getCashSalesInvoicesByDateAndStore_remote($info, $cnx, $store_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and closed=1 and auto_closed=0 and payment_method=1";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function getCashSalesInvoicesByCashboxID_vat_diff($cashbox_id)
    {
        $query1 = "select COALESCE(sum(ABS(qty*(selling_price*(1-discount/100))*(vat_value-1))), 0) as sum from returned_purchases where old_cashbox_id=" . $cashbox_id . " and vat=1 and invoice_id in (select id from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and closed=1 and auto_closed=0 and payment_method=1)";
        $result1 = my_sql::fetch_assoc(my_sql::query($query1));
        return $result1[0]["sum"];
    }
    public function getSalesByCreditCard($cashbox_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and closed=1 and payment_method=3";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getSalesByCreditCardByStoreAndDate($info)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $info["store_id"] . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and closed=1 and payment_method=3";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getSalesByCreditCardByStoreAndDate_remote($info, $cnx, $store_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and closed=1 and payment_method=3";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function getSalesByCheque($cashbox_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and closed=1 and payment_method=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getSalesByChequeByStoreAndCheque($info)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $info["store_id"] . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and closed=1 and payment_method=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getSalesByChequeByStoreAndCheque_remote($info, $cnx, $store_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and closed=1 and payment_method=2";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function get_returned_of_cashbox($cashbox_id)
    {
        $query_5 = "select COALESCE(sum(qty*selling_price*(1-discount/100)), 0) as sum from returned_purchases where item_id>0 and deleted=0 and vat=0 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and other_branche=0)";
        $result_5 = my_sql::fetch_assoc(my_sql::query($query_5));
        $return_sum_5 = $result_5[0]["sum"];
        $query_7 = "select COALESCE(sum(qty*selling_price*(1-discount/100)*(vat_value-1)), 0) as sum from returned_purchases where item_id>0 and deleted=0 and vat=1 and cashbox_id=" . $cashbox_id . " and old_cashbox_id!=cashbox_id and only_return=1 and invoice_id not in (select id from invoices where closed=0 and deleted=0 and other_branche=0)";
        $result_7 = my_sql::fetch_assoc(my_sql::query($query_7));
        $return_sum_7 = $result_7[0]["sum"];
        $query_6 = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and cashbox_id!=old_cashbox_id and (added_value>0 && return_value>0) and invoice_id not in (select id from invoices where deleted=0 and closed=0 and other_branche=0)";
        $result_6 = my_sql::fetch_assoc(my_sql::query($query_6));
        $diff_changed = 0;
        if (0 < count($result_6)) {
            $diff_changed = $result_6[0]["diff_changed"];
        }
        $query_11 = "select COALESCE(sum(added_value-return_value), 0) as diff_changed from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and cashbox_id=old_cashbox_id and added_value>0 and return_value>0 and invoice_id in (select id from invoices where deleted=0 and closed=0 and other_branche>0)";
        $result_11 = my_sql::fetch_assoc(my_sql::query($query_11));
        $diff_changed_branches = 0;
        if (0 < count($result_11)) {
            $diff_changed_branches = $result_11[0]["diff_changed"];
        }
        return $return_sum_5 + $return_sum_7 + $diff_changed + $diff_changed_branches;
    }
    public function getSalesReturnedByCashboxID($cashbox_id)
    {
        return self::get_returned_of_cashbox($cashbox_id);
    }
    public function getSalesReturnedByCashboxID_vat_diff($cashbox_id)
    {
        $query = "select COALESCE(sum(selling_price*(1-discount/100)*(vat_value-1)), 0) as sum from returned_purchases where vat=1 and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getSalesReturnedBySameCashboxID($cashbox_id)
    {
        $query = "select COALESCE(sum(selling_price), 0) as sum from returned_purchases where old_cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalInvoiceDiscountByCashboxID($cashbox_id)
    {
        $query = "select COALESCE(sum(invoice_discount), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and closed=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getAllInvoicesItemsByCashboxID($cashbox_id)
    {
        $query = "select * from invoice_items where invoice_id in (select id from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " )";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_added_items_to_invoice_for_diffenrent_cashbox($cashbox_id)
    {
        $query = "select * from invoice_items where item_change_cashbox=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoicesByCashboxID($cashbox_id)
    {
        $query = "select * from invoices where deleted=0 and other_branche=0 and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSalesNotPaidByCashboxID($cashbox_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and (closed=0 or auto_closed=1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query1 = "select COALESCE(sum(ABS(selling_price)), 0) as sum from returned_purchases where invoice_id in (select id from invoices where other_branche=0 and cashbox_id=" . $cashbox_id . " and (closed=0 or auto_closed=1))";
        $result1 = my_sql::fetch_assoc(my_sql::query($query1));
        return $result[0]["sum"];
    }
    public function getSalesNotPaidByStoreAndDate($info)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $info["store_id"] . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and (closed=0 or auto_closed=1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getSalesNotPaidByStoreAndDate_remote($info, $cnx, $store_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "' and (closed=0 or auto_closed=1)";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function getTotal($customer_id)
    {
        $query = "select sum(final_price_disc_qty) as sum from invoice_items where invoice_id in (select id from invoices where other_branche=0 and closed=0 and customer_id = " . $customer_id . ") and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalPaid($customer_id)
    {
        $query = "select sum(final_price_disc_qty) as sum from invoice_items where invoice_id in (select id from invoices where other_branche=0 and customer_id = " . $customer_id . " and closed=1) and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalUnpaid($customer_id)
    {
        if ($_SESSION["role"] == 3) {
            $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and customer_id = " . $customer_id . " and official=1 and  (closed=0 || auto_closed=1) and deleted=0";
        } else {
            $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and customer_id = " . $customer_id . " and (closed=0 || auto_closed=1) and deleted=0";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalUnpaidGroup()
    {
        $query = "select customer_id,COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and (closed=0 || auto_closed=1) and deleted=0 and customer_id is not null group by customer_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_internationnal_calls_invoices()
    {
        $query = "select COALESCE(sum(buying_cost), 0) as sum from invoice_items where custom_item=1 and deleted=0 and description like '%IC_%' ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getAllUnpaid()
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and (closed=0 || auto_closed=1) and deleted=0 and customer_id in (select id from customers where deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function calculate_total_value($invoice_id)
    {
        $amount = self::getAmount($invoice_id);
        $query = "update invoices set total_value=" . $amount[0]["sum"] . " where id=" . $invoice_id;
        my_sql::query($query);
        my_sql::query("update invoices set invoice_discount=0 where total_value=0 and id=" . $invoice_id);
        self::calculate_total_value_limited($invoice_id);
        return $amount[0]["sum"];
    }
    public function calculate_total_value_with_vat($invoice_id)
    {
        $amount = self::getAmount($invoice_id);
        $amount_vat_diff = self::getAmountVatDiff($invoice_id);
        $query = "update invoices set total_value=" . ($amount[0]["sum"] + $amount_vat_diff[0]["sum"]) . " where id=" . $invoice_id;
        my_sql::query($query);
        self::calculate_total_value_limited($invoice_id);
        return $amount[0]["sum"] + $amount_vat_diff[0]["sum"];
    }
    public function calculate_total_value_limited($invoice_id)
    {
        $amount = self::getAmount_limited($invoice_id);
        $amountVatDiff = self::getAmountVatDiff_limited($invoice_id);
        $query = "update invoices set total_value_limited=" . ($amount[0]["sum"] + $amountVatDiff[0]["sum"]) . " where id=" . $invoice_id;
        my_sql::query($query);
    }
    public function getPurchases($store_id, $date)
    {
        if ($_SESSION["role"] == 4) {
            $query = "select * from invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)='" . $date . "' and employee_id in (select id from users where role_id=4) order by id desc";
        } else {
            $query = "select * from invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)='" . $date . "' order by id desc";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function returnPurchasedItem($info)
    {
        $query = "insert into returned_purchases(invoice_id,item_id,returned_by_vendor_id,returned_to_store_id,return_date,qty,custom_item,mobile_transfer_credits_id,description,buying_cost,vat,selling_price,discount,cashbox_id,old_cashbox_id,vat_value) values(" . $info["invoice_id"] . "," . $info["item_id"] . "," . $info["returned_by_vendor_id"] . "," . $info["returned_to_store_id"] . ",'" . my_sql::datetime_now() . "'," . $info["qty"] . "," . $info["custom_item"] . "," . $info["mobile_transfer_credits"] . ",'" . $info["description"] . "'," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["cashbox_id"] . "," . $info["old_cashbox_id"] . "," . $info["vat_value"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function getReturnedItemsByCashbox($cashbox_id)
    {
        $query = "select * from returned_purchases where cashbox_id=" . $cashbox_id . " or old_cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReturnedItemsByAnotherCashbox($cashbox_id)
    {
        $query = "select * from returned_purchases where cashbox_id=" . $cashbox_id . " and old_cashbox_id!=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReturnedItemsByAnotherCashbox_with_details($cashbox_id)
    {
        $query = "select rp.id,rp.invoice_id,rp.item_id,rp.returned_by_vendor_id,rp.selling_price,rp.discount,cci.cash_lbp_in,cci.cash_usd_in,cci.returned_cash_lbp,cci.returned_cash_usd from returned_purchases rp left join cashbox_changes_info cci on cci.invoice_item_return_id=rp.id where rp.cashbox_id=" . $cashbox_id . " and rp.old_cashbox_id!=" . $cashbox_id . " and rp.only_return=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getDeleted_invoices($cashbox_id)
    {
        $query = "select * from invoices where cashbox_id=" . $cashbox_id . " and deleted=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getDeleted_invoices_remote($cashbox_id, $cnx)
    {
        $query = "select * from invoices where cashbox_id=" . $cashbox_id . " and deleted=1";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function getChangesItemsByAnotherCashbox_with_details($cashbox_id)
    {
        $query = "select * from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and old_cashbox_id!=" . $cashbox_id . "  and invoice_item_return_id=0 ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReturnedItemsByInvoiceId($invoice_id, $cashbox_id = 0)
    {
        if ($cashbox_id == 0) {
            $query = "select * from returned_purchases where invoice_id=" . $invoice_id;
        } else {
            $query = "select * from returned_purchases where invoice_id=" . $invoice_id . " and old_cashbox_id=" . $cashbox_id;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReturnedItemsByInvoiceIdAndByCashbox($invoice_id, $cashbox_id = 0)
    {
        $query = "select * from returned_purchases where invoice_id=" . $invoice_id . " and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReturnedItemsByCashbox_old($cashbox_id)
    {
        $query = "select * from returned_purchases where old_cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReturnedItemsById($id)
    {
        $query = "select * from returned_purchases where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function deletePurchasedItem($id, $invoice_id)
    {
        $query = "delete from invoice_items where id=" . $id;
        my_sql::query($query);
        $nb_it = self::checkIfNoItemsInInvoice($invoice_id);
        if ($nb_it[0]["num"] == 0) {
            my_sql::query("update invoices set closed=1 where id=" . $invoice_id);
        }
        self::calculate_total_value($invoice_id);
    }
    public function updateDiscount($id, $disc, $invoice_id)
    {
        my_sql::query("update invoice_items set discount=" . $disc . " where id=" . $id);
        self::calculate_total_cost_price($id);
        self::calculate_total_value($invoice_id);
    }
    public function update_invoice_nb_official($invoice_id)
    {
        $query = "select COALESCE(max(invoice_nb_official), 0)  as mx_ from invoices where other_branche=0 and deleted=0 and total_vat_value>0 and year('" . my_sql::datetime_now() . "')=year(creation_date)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        my_sql::query("update invoices set invoice_nb_official=" . ($result[0]["mx_"] + 1) . " where id=" . $invoice_id . " and invoice_nb_official=0");
    }
    public function calculate_total_vat_value_for_invoice($invoice_id)
    {
        $query = "select COALESCE(abs(sum( final_price_disc_qty-(final_price_disc_qty/vat_value) )), 0) as sumvat from invoice_items where invoice_id=" . $invoice_id . " and vat=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        my_sql::query("update invoices set total_vat_value=" . $result[0]["sumvat"] . " where id=" . $invoice_id);
        my_sql::query("update invoices set total_vat_value=total_vat_value+(invoice_discount*(vat_value-1)) where id=" . $invoice_id);
        if (0 < $result[0]["sumvat"]) {
            self::update_invoice_nb_official($invoice_id);
        }
    }
    public function calculate_total_profit_for_invoice($invoice_id)
    {
        $tp = self::getSumProfitOfInvoiceItems($invoice_id);
        my_sql::query("update invoices set total_profit=" . $tp[0]["sum"] . " where id=" . $invoice_id);
        my_sql::query("update invoices set profit_after_discount=total_profit+invoice_discount where id=" . $invoice_id);
        self::calculate_total_profit_limited_for_invoice($invoice_id);
        self::calculate_total_vat_value_for_invoice($invoice_id);
    }
    public function calculate_total_profit_limited_for_invoice($invoice_id)
    {
        $tp = self::getSumProfitLimitedOfInvoiceItems($invoice_id);
        my_sql::query("update invoices set total_profit_limited=" . $tp[0]["sum"] . ",profit_after_discount=total_profit+invoice_discount where id=" . $invoice_id);
    }
    public function getSumProfitLimitedOfInvoiceItems($invoice_id)
    {
        $query = "select COALESCE(sum(profit), 0) as sum from invoice_items where invoice_id=" . $invoice_id . " and user_role=4 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSumProfitOfInvoiceItems($invoice_id)
    {
        $query = "select COALESCE(sum(profit), 0) as sum from invoice_items where invoice_id=" . $invoice_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function calculate_total_profit_for_invoices()
    {
        $invoices = self::getInvoices();
        for ($i = 0; $i < count($invoices); $i++) {
            self::calculate_total_cost_price_for_invoice($invoices[$i]["id"]);
            self::calculate_total_value($invoices[$i]["id"]);
            self::calculate_total_profit_for_invoice($invoices[$i]["id"]);
        }
    }
    public function calculate_total_cost_price_for_invoice($id)
    {
        $invoice_items = self::getItemsOfInvoice($id);
        for ($i = 0; $i < count($invoice_items); $i++) {
            self::calculate_total_cost_price($invoice_items[$i]["id"]);
        }
    }
    public function total_profit($info)
    {
        $query = "select COALESCE(sum(profit_after_discount), 0) as sum from invoices where other_branche=0 and deleted=0 and store_id=" . $info["store_id"] . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function total_profit_remote($info, $cnx)
    {
        $query = "select COALESCE(sum(profit_after_discount), 0) as sum from invoices where other_branche=0 and deleted=0 and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function total_cost($info)
    {
        $query = "select COALESCE(sum(final_cost_vat_qty), 0) as sum from invoice_items where invoice_id in ( select id from invoices where other_branche=0 and deleted=0 and store_id=" . $info["store_id"] . " and date(creation_date)>='" . $info["start_date"] . "' and date(creation_date)<='" . $info["end_date"] . "')";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function calculate_total_cost_price($id)
    {
        $item = self::get_item_from_invoice($id);
        if (0 < count($item)) {
            $vat = 1;
            if (floatval($item[0]["vat"]) == 1) {
                $vat = floatval($item[0]["vat_value"]);
            }
            $disc = 1;
            if (0 <= floatval($item[0]["discount"])) {
                $disc = 1 - floatval($item[0]["discount"]) / 100;
            }
            my_sql::query("update invoice_items set final_cost_vat_qty=qty*buying_cost*" . $vat . " where id=" . $id);
            my_sql::query("update invoice_items set final_price_disc_qty=qty*selling_price*" . $disc . "*" . $vat . " where id=" . $id);
            my_sql::query("update invoice_items set profit=final_price_disc_qty-final_cost_vat_qty where id=" . $id);
        }
    }
    public function setofficial($id, $action)
    {
        $query = "update invoice_items set official=" . $action . " where id=" . $id;
        my_sql::query($query);
    }
    public function set_invoice_official($id, $action)
    {
        $query = "update invoices set official=" . $action . " where id=" . $id;
        my_sql::query($query);
    }
    public function deleteOnePurchasedItem($id, $invoice_id)
    {
        $query = "update invoice_items set qty=qty-1 where id=" . $id;
        my_sql::query($query);
        self::calculate_total_cost_price($id);
        self::calculate_total_value($invoice_id);
    }
    public function reduceQtyOfPurchasedItem($id, $invoice_id, $qty_to_return)
    {
        $query = "update invoice_items set qty=qty-" . $qty_to_return . " where id=" . $id;
        my_sql::query($query);
        my_sql::query("delete from invoice_items where qty=0 and id=" . $id);
        self::calculate_total_cost_price($id);
        self::calculate_total_value($invoice_id);
    }
    public function getAllItemsWasSold($store_id, $date_range)
    {
        if ($date_range == "today") {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)=date('" . my_sql::datetime_now() . "') and inv.other_branche=0 order by inv.id desc limit 5000";
        } else {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)>='" . $date_range[0] . "' and date(inv.creation_date)<='" . $date_range[1] . "' and inv.other_branche=0 order by inv.id desc limit 5000";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsWasSoldByInvoice_id_switch($invoice_id)
    {
        if ($_SESSION["role"] == 3) {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv.id as invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and inv_it.invoice_id=" . $invoice_id . " and inv_it.user_role=4 order by inv.id desc";
        } else {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv.id as invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and inv_it.invoice_id=" . $invoice_id . " order by inv.id desc";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_transfer_info($id)
    {
        $query = "select * from mobile_credits_history where invoice_item_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsWasSoldByInvoice_id_switch__($invoice_id, $settings)
    {
        $ffltr = "";
        if ($settings["invoice_show_only_for_sold_pos"] == 1) {
            $ffltr = " and inv.employee_id=" . $_SESSION["id"];
        }
        $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv.id as invoice_id,inv_it.vat,inv_it.vat_value,inv_it.pos_discounted,inv_it.mobile_transfer_credits from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and inv_it.invoice_id=" . $invoice_id . " " . $ffltr . " order by inv.id desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsOfInvoice_items_not_null($store_id, $invoice_id)
    {
        if ($_SESSION["role"] == 3) {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv.id as invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and invoice_id=" . $invoice_id . " and inv.other_branche=0 and inv_it.user_role=4 and inv_it.item_id is not null order by inv.id desc";
        } else {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv.id as invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and invoice_id=" . $invoice_id . " and inv.other_branche=0 and inv_it.item_id is not null order by inv.id desc";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsWasSold_switch($store_id, $date_range)
    {
        if ($date_range == "today") {
            if ($_SESSION["role"] == 3) {
                $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv_it.invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)=date('" . my_sql::datetime_now() . "') and inv_it.official=1 and inv.other_branche=0 order by inv.id desc";
            } else {
                $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv_it.invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)=date('" . my_sql::datetime_now() . "') and inv.other_branche=0 order by inv.id desc";
            }
        } else {
            if ($_SESSION["role"] == 3) {
                $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv_it.invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)>='" . $date_range[0] . "' and date(inv.creation_date)<='" . $date_range[1] . "' and inv_it.official=1 and inv.other_branche=0 order by inv.id desc limit 5000";
            } else {
                $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv_it.invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)>='" . $date_range[0] . "' and date(inv.creation_date)<='" . $date_range[1] . "' and inv.other_branche=0 order by inv.id desc limit 5000";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsWasSold_switch__($date_range, $settings, $operations_type)
    {
        $ffltr = "";
        if ($settings["invoice_show_only_for_sold_pos"] == 1) {
            $ffltr = " and (inv.employee_id=" . $_SESSION["id"] . " or inv.id in (select invoice_id from quotations where deleted=0 and created_by=" . $_SESSION["id"] . ") )";
        }
        ${$operations_ffltr} = "";
        if ($operations_type == 1) {
            ${$operations_ffltr} = " and inv.cashbox_id=" . $_SESSION["cashbox_id"] . " ";
        } else {
            if ($operations_type == 1) {
                ${$operations_ffltr} = " and inv.employee_id=" . $_SESSION["id"] . " ";
            }
        }
        if ($date_range == "today") {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv_it.invoice_id,inv_it.vat,inv_it.vat_value,inv_it.pos_discounted,inv_it.mobile_transfer_credits,inv.cashbox_id as cashbox_id,inv.employee_id from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)=date('" . my_sql::datetime_now() . "') and inv.other_branche=0 " . $ffltr . " " . ${$operations_ffltr} . " order by inv.id desc";
        } else {
            $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv_it.invoice_id,inv_it.vat,inv_it.vat_value,inv_it.pos_discounted,inv_it.mobile_transfer_credits,inv.cashbox_id as cashbox_id,inv.employee_id from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and date(inv.creation_date)>='" . $date_range[0] . "' and inv.other_branche=0 and date(inv.creation_date)<='" . $date_range[1] . "' " . $ffltr . " " . ${$operations_ffltr} . " order by inv.id desc limit 5000";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllItemsWasSoldByBarcode_switch($store_id, $barcode)
    {
        $query = "select inv_it.id,inv_it.final_cost_vat_qty,inv_it.final_price_disc_qty,inv_it.item_id,inv_it.description,inv_it.custom_item,inv_it.qty,inv_it.selling_price,inv_it.discount, inv.creation_date,inv.closed,inv.auto_closed,inv.customer_id,inv_it.official,inv_it.invoice_id,inv_it.vat,inv_it.vat_value from invoice_items inv_it,invoices inv where inv.id =inv_it.invoice_id and inv_it.deleted=0 and (inv_it.item_id in (select id from items where barcode='" . $barcode . "') or inv_it.item_id in ( select item_id from unique_items where code1='" . $barcode . "' or code2='" . $barcode . "'))  order by inv.id desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_returned_as_not_only_return($id)
    {
        $query = "update returned_purchases set only_return=0 where id=" . $id;
        my_sql::query($query);
    }
    public function set_returned_invoice_item_id($id, $invoice_item_id)
    {
        $query = "update returned_purchases set invoice_item_id=" . $invoice_item_id . " where id=" . $id;
        my_sql::query($query);
    }
    public function clone_invoice($remote_invoice, $invoice_items, $virtual_invoice_id, $other_branche_id)
    {
        $query_invoice = "update invoices set " . "total_value=" . $remote_invoice[0]["total_value"] . "," . "invoice_discount=" . $remote_invoice[0]["invoice_discount"] . "," . "total_profit=" . $remote_invoice[0]["total_profit"] . "," . "profit_after_discount=" . $remote_invoice[0]["profit_after_discount"] . "," . "cashbox_id=" . $_SESSION["cashbox_id"] . "," . "payment_method=" . $remote_invoice[0]["payment_method"] . "," . "discount_note='" . $remote_invoice[0]["discount_note"] . "'," . "payment_note='" . $remote_invoice[0]["payment_note"] . "'," . "total_vat_value=" . $remote_invoice[0]["total_vat_value"] . "," . "vat_value=" . $remote_invoice[0]["vat_value"] . ", " . "other_branche=" . $other_branche_id . ", " . "invoice_id=" . $remote_invoice[0]["id"] . " " . "where id=" . $virtual_invoice_id;
        my_sql::query($query_invoice);
        for ($i = 0; $i < count($invoice_items); $i++) {
            $query_invoice_items = "insert into invoice_items (invoice_id,item_id,qty,buying_cost,vat,selling_price,discount,final_cost_vat_qty,final_price_disc_qty,profit,vat_value,description,price_after_manual_discount,synced,official) values(" . $virtual_invoice_id . "," . $invoice_items[$i]["item_id"] . "," . $invoice_items[$i]["qty"] . "," . $invoice_items[$i]["buying_cost"] . "," . $invoice_items[$i]["vat"] . "," . $invoice_items[$i]["selling_price"] . "," . $invoice_items[$i]["discount"] . "," . $invoice_items[$i]["final_cost_vat_qty"] . "," . $invoice_items[$i]["final_price_disc_qty"] . "," . $invoice_items[$i]["profit"] . "," . $invoice_items[$i]["vat_value"] . ",'" . $invoice_items[$i]["description"] . "'," . $invoice_items[$i]["price_after_manual_discount"] . "," . $invoice_items[$i]["synced"] . "," . $invoice_items[$i]["official"] . ")";
            my_sql::query($query_invoice_items);
        }
    }
    public function prepare_new_invoice($client_id)
    {
        $query = "insert into prepare_invoices(creation_date,created_by,client_id) " . "values(now()," . $_SESSION["id"] . "," . $client_id . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_pre_invoice_items($preinvoice_id)
    {
        if ($preinvoice_id == 0) {
            return array();
        }
        $query = "select pid.item_id,it.description,pid.qty,pid.price,(pid.qty*pid.price) as total,it.sku_code,it.barcode,it.is_composite,pid.composite_qty from prepare_invoices_details pid left join items it on it.id=pid.item_id where pid.prepare_invoice_id=" . $preinvoice_id . " and pid.deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function already_item_id_exist_in_preinvoice($preinvoice_id, $item_id)
    {
        $query = "select id from prepare_invoices_details  where prepare_invoice_id=" . $preinvoice_id . " and item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return true;
        }
        return false;
    }
    public function get_pre_invoice_details_by_id($id)
    {
        $query = "select item_id,qty from prepare_invoices_details where prepare_invoice_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pre_invoice_by_id($id)
    {
        $query = "select client_id from prepare_invoices where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pre_invoices()
    {
        $query = "select prei.id,prei.client_id,prei.creation_date,prei.created_by,cs.name as client_name,prei.total_amount from prepare_invoices prei left join customers cs on cs.id=prei.client_id where prei.deleted=0 order by prei.creation_date desc limit 100";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_preinvoice($preinvoice_id)
    {
        my_sql::query("update prepare_invoices set deleted=1 where id=" . $preinvoice_id);
    }
    public function delete_item_from_preinvoice($preinvoice_id, $item_id)
    {
        my_sql::query("update prepare_invoices_details set deleted=1 where prepare_invoice_id=" . $preinvoice_id . " and item_id=" . $item_id);
    }
    public function set_preinvoice_qty($item_id, $preinvoice_id, $qty)
    {
        my_sql::query("update prepare_invoices_details set qty=" . $qty . " where prepare_invoice_id=" . $preinvoice_id . " and item_id=" . $item_id);
    }
    public function get_preinvoices()
    {
        $query = "select prei.id,prei.client_id,prei.creation_date,prei.created_by,cs.name as client_name,prei.total_amount from prepare_invoices prei left join customers cs on cs.id=prei.client_id where prei.deleted=0 order by prei.creation_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_total_preinvoice($preinvoice_id)
    {
        $query = "select COALESCE(sum(price*qty), 0) as sum from prepare_invoices_details where deleted=0 and prepare_invoice_id=" . $preinvoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        my_sql::query("update prepare_invoices set total_amount=" . $result[0]["sum"] . " where id=" . $preinvoice_id);
    }
    public function change_client_preinvoice($client_id, $preinvoice_id)
    {
        my_sql::query("update prepare_invoices set client_id=" . $client_id . " where id=" . $preinvoice_id);
    }
    public function add_item_prepare_invoice_by_barcode($barcode, $preinvoice_id, $customer_type)
    {
        $info = array();
        $query = "select * from items where (barcode='" . $barcode . "' or barcode='" . ltrim($barcode, "0") . "' or barcode='0" . ltrim($barcode, "0") . "' or barcode='00" . ltrim($barcode, "0") . "' or barcode='000" . ltrim($barcode, "0") . "' or barcode='0000" . ltrim($barcode, "0") . "' or second_barcode='" . $barcode . "') and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $exist = self::already_item_id_exist_in_preinvoice($preinvoice_id, $result[0]["id"]);
            if (!$exist) {
                $pre_invoice_details = self::get_pre_invoice_by_id($preinvoice_id);
                $price = $result[0]["selling_price"];
                if (0 < $pre_invoice_details[0]["client_id"]) {
                    $customer_query = "select customer_type from customers where id=" . $pre_invoice_details[0]["client_id"];
                    $customer_result = my_sql::fetch_assoc(my_sql::query($customer_query));
                    if ($customer_result[0]["customer_type"] == 2) {
                        $price = $result[0]["wholesale_price"];
                    }
                    if ($customer_result[0]["customer_type"] == 3) {
                        $price = $result[0]["second_wholesale_price"];
                    }
                }
                $composite_qty = 0;
                $qty = 1;
                $query_composite = "select * from items_composite where item_id =" . $result[0]["id"];
                $result_composite = my_sql::fetch_assoc(my_sql::query($query_composite));
                if (0 < count($result_composite) && $customer_type == 3) {
                    $composite_qty = $result_composite[0]["qty"];
                    $qty = $result_composite[0]["qty"];
                }
                my_sql::query("insert into prepare_invoices_details (prepare_invoice_id,item_id,qty,price,composite_qty) values(" . $preinvoice_id . "," . $result[0]["id"] . "," . $qty . "," . $price . "," . $composite_qty . ")");
                $info["barcode"] = $barcode;
                $info["added"] = 1;
            } else {
                $composite_qty = 1;
                $query_composite = "select * from items_composite where item_id =" . $result[0]["id"];
                $result_composite = my_sql::fetch_assoc(my_sql::query($query_composite));
                if (0 < count($result_composite) && $customer_type == 3) {
                    $composite_qty = $result_composite[0]["qty"];
                }
                my_sql::query("update prepare_invoices_details set qty=qty+" . $composite_qty . " where prepare_invoice_id=" . $preinvoice_id . " and item_id=" . $result[0]["id"]);
                $info["barcode"] = $barcode;
                $info["added"] = 1;
            }
        } else {
            $info["barcode"] = $barcode;
            $info["added"] = 0;
        }
        self::update_total_preinvoice($preinvoice_id);
        return $info;
    }
}

?>