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
class paymentsModel
{
    public function add_payment($info)
    {
        $query_insert = "insert into payments(invoice_id,date_of_pay,value,vendor_id,store_id) values(" . $info["invoice_id"] . ",'" . my_sql::datetime_now() . "'," . $info["value"] . "," . $info["vendor_id"] . "," . $info["store_id"] . ")";
        my_sql::query($query_insert);
    }
    public function get_total_payments($supplier_id, $date_range)
    {
        if ($supplier_id == 0) {
            $query = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and deleted=0 and supplier_id in (select id from suppliers where deleted=0)";
        } else {
            $query = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and deleted=0 and supplier_id=" . $supplier_id;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_due_cheques_suppliers()
    {
        $query = "select * from suppliers_payments where payment_method=2 and date('" . my_sql::datetime_now() . "')>=date(payment_date) and deleted=0 and payment_done=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPaymentsSuppliersByIntervalOfDate($date_range)
    {
        $query = "select * from suppliers_payments where date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and deleted=0 order by creation_date asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_due_cheques_customers()
    {
        $query = "select * from customer_balance where payment_method=2 and date('" . my_sql::datetime_now() . "')>=date(value_date) and deleted=0 and payment_done=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pending_cheques_suppliers()
    {
        $query = "select * from suppliers_payments where payment_method=2 and deleted=0 and payment_done=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pending_cheques_suppliers_due_date()
    {
        $query = "select * from suppliers_payments where payment_method=2 and deleted=0 and payment_done=0 and DATEDIFF(payment_date,'" . my_sql::datetime_now() . "')<=3";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pending_cheques_customers()
    {
        $query = "select * from customer_balance where payment_method=2 and deleted=0 and payment_done=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pending_cheques_customers_due_date()
    {
        $query = "select * from customer_balance where payment_method=2 and  deleted=0 and payment_done=0 and DATEDIFF(value_date,'" . my_sql::datetime_now() . "')<=3";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_supplier_payment($info)
    {
        $query_insert = "insert into suppliers_payments(supplier_id,payment_value,payment_date,payment_method,creation_date,payment_note,invoice_order_id,bank_id,reference,payment_owner,currency_rate,payment_currency,voucher,cashbox_id,usd_to_lbp) values(" . $info["supplier_id"] . "," . $info["payment_value"] . ",'" . $info["payment_date"] . " " . my_sql::time_now() . "'," . $info["payment_method"] . ",'" . my_sql::datetime_now() . "','" . $info["payment_note"] . "'," . $info["invoice_order_id"] . "," . $info["bank_source"] . ",'" . $info["reference"] . "','" . $info["payment_owner"] . "',1," . $info["payment_currency"] . ",'" . $info["voucher_nb"] . "'," . $info["cashbox_id"] . "," . $info["currency_rate_value"] . ")";
        my_sql::query($query_insert);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function add_supplier_payment_new($info)
    {
        $query_insert = "insert into suppliers_payments(supplier_id,payment_value,payment_date,payment_method,creation_date,payment_note,invoice_order_id,bank_id,reference,payment_owner,currency_rate,payment_currency,voucher,cashbox_id,usd_to_lbp,cash_in_usd,cash_in_lbp,returned_usd,returned_lbp,to_returned_usd,to_returned_lbp) " . "values(" . $info["supplier_id"] . "," . $info["payment_value"] . ",'" . $info["payment_date"] . " " . my_sql::time_now() . "'," . $info["payment_method"] . ",'" . my_sql::datetime_now() . "','" . $info["payment_note"] . "'," . $info["invoice_order_id"] . "," . $info["bank_source"] . ",'" . $info["reference"] . "','" . $info["payment_owner"] . "',1," . $info["payment_currency"] . ",'" . $info["voucher_nb"] . "'," . $info["cashbox_id"] . "," . $info["currency_rate_value"] . "," . $info["cash_usd_in"] . "," . $info["cash_lbp_in"] . "," . $info["returned_cash_usd"] . "," . $info["returned_cash_lbp"] . "," . $info["cash_usd_to_return"] . "," . $info["cash_lbp_to_return"] . ")";
        my_sql::query($query_insert);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function update_cheque_picture_name_fir_supplier_payment($id, $name)
    {
        my_sql::query("update suppliers_payments set payment_picture='" . $name . "' where id=" . $id);
    }
    public function payment_customer_done($id)
    {
        my_sql::query("update customer_balance set payment_done=1 where id=" . $id);
    }
    public function payment_supplier_done($id)
    {
        my_sql::query("update suppliers_payments set payment_done=1 where id=" . $id);
    }
    public function update_cheque_picture_name_for_customer_payment($id, $name)
    {
        my_sql::query("update customer_balance set picture='" . $name . "' where id=" . $id);
    }
    public function delete_cheque_picture($id)
    {
        $query = "update suppliers_payments set payment_picture=NULL where id=" . $id;
        my_sql::query($query);
    }
    public function add_payment_to_customer($info)
    {
        $query = "update customers set balance=balance+" . $info["value"] * $info["rate"] . " where id=" . $info["customer_id"];
        my_sql::query($query);
        $query_balance = "insert into customer_balance(customer_id,vendor_id,store_id,balance,balance_date,cashbox_id,payment_method,value_date,currency_id,rate,note,bank_id,reference_nb,owner,picture,voucher,usd_to_lbp) values(" . $info["customer_id"] . "," . $info["vendor_id"] . "," . $info["store_id"] . "," . $info["value"] . ",'" . $info["creation_date"] . " " . date("H:i:s") . "'," . $info["cashbox_id"] . "," . $info["payment_method"] . ",'" . $info["value_date"] . "'," . $info["currency_id"] . "," . $info["rate"] . ",'" . $info["note"] . "'," . $info["bank_id"] . ",'" . $info["reference_nb"] . "','" . $info["owner"] . "','" . $info["picture"] . "','" . $info["voucher"] . "','" . $info["rate_value"] . "')";
        my_sql::query($query_balance);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function add_payment_to_customer_new($info)
    {
        $query = "update customers set balance=balance+" . $info["value"] . " where id=" . $info["customer_id"];
        my_sql::query($query);
        $query_balance = "insert into customer_balance(customer_id,vendor_id,store_id,balance,balance_date,cashbox_id,payment_method,value_date,currency_id,rate,note,bank_id,reference_nb,owner,picture,voucher,usd_to_lbp,cash_in_usd,cash_in_lbp,returned_usd,returned_lbp,to_returned_usd,to_returned_lbp,p_rate,invoice_id,quotation_id) values(" . $info["customer_id"] . "," . $info["vendor_id"] . "," . $info["store_id"] . "," . $info["value"] . ",'" . $info["creation_date"] . " " . date("H:i:s") . "'," . $info["cashbox_id"] . "," . $info["payment_method"] . ",'" . my_sql::datetime_now() . "'," . $info["currency_id"] . "," . $info["rate"] . ",'" . $info["note"] . "'," . $info["bank_id"] . ",'" . $info["reference_nb"] . "','" . $info["owner"] . "','" . $info["picture"] . "','" . $info["voucher"] . "','" . $info["rate_value"] . "','" . $info["cash_in_usd"] . "','" . $info["cash_in_lbp"] . "','" . $info["returned_usd"] . "','" . $info["returned_lbp"] . "','" . $info["to_returned_usd"] . "','" . $info["to_returned_lbp"] . "','" . $info["p_rate"] . "'," . $info["invoice_id"] . "," . $info["quotation_id"] . ")";
        my_sql::query($query_balance);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function add_balance($info)
    {
        $query_balance = "insert into customer_balance(customer_id,vendor_id,store_id,balance,balance_date) values(" . $info["customer_id"] . "," . $info["vendor_id"] . "," . $info["store_id"] . "," . $info["value"] . ",'" . my_sql::datetime_now() . "')";
        my_sql::query($query_balance);
    }
    public function reduce_payment_to_customer($info)
    {
        $query = "update customers set balance=balance-" . $info["value"] . " where id=" . $info["customer_id"];
        my_sql::query($query);
    }
    public function getTotalPayments($invoice_id)
    {
        $query = "select COALESCE(sum(value), 0) as sum from payments where invoice_id=" . $invoice_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPaymentOfCustomer($id, $invoice_id)
    {
        $query = "select * from payments where invoice_id in (select id from invoices where id=" . $invoice_id . " and other_branche=0 and customer_id=" . $id . ") and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllDebtsPaymentOfCustomer($id)
    {
        $query = "select * from customer_balance where customer_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllDebtsPaymentOfCustomerDateRange($id, $date_range)
    {
        $query = "select * from customer_balance where customer_id=" . $id . " and deleted=0 and date(balance_date)>='" . $date_range[0] . "' and date(balance_date)<='" . $date_range[1] . "' ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_previews_balance($customer_id, $limit_date)
    {
        $query_payments = "select COALESCE(sum(balance*rate), 0) as sum  from customer_balance where customer_id=" . $customer_id . " and deleted=0 and balance_date<'" . $limit_date . "'";
        $result_payments = my_sql::fetch_assoc(my_sql::query($query_payments));
        $query_invoices = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and deleted=0 and customer_id=" . $customer_id . " and creation_date<'" . $limit_date . "' and (closed=0 || auto_closed=1)";
        $result_invoices = my_sql::fetch_assoc(my_sql::query($query_invoices));
        $query_cn = "select COALESCE(sum(credit_value*currency_rate), 0) as sum from credit_notes where deleted=0 and customer_id=" . $customer_id . " and creation_date<'" . $limit_date . "'";
        $result_cn = my_sql::fetch_assoc(my_sql::query($query_cn));
        return $result_invoices[0]["sum"] - $result_payments[0]["sum"] - $result_cn[0]["sum"];
    }
    public function get_total_balance($customer_id)
    {
        $query_payments = "select COALESCE(sum(balance*rate), 0) as sum  from customer_balance where customer_id=" . $customer_id . " and deleted=0 ";
        $result_payments = my_sql::fetch_assoc(my_sql::query($query_payments));
        $query_invoices = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and deleted=0 and (closed=0 or auto_closed=1) and customer_id=" . $customer_id;
        $result_invoices = my_sql::fetch_assoc(my_sql::query($query_invoices));
        $query_cn = "select COALESCE(sum(credit_value*currency_rate), 0) as sum from credit_notes where deleted=0 and customer_id=" . $customer_id;
        $result_cn = my_sql::fetch_assoc(my_sql::query($query_cn));
        return $result_invoices[0]["sum"] - $result_payments[0]["sum"] - $result_cn[0]["sum"];
    }
    public function getPaymentDetails($id)
    {
        $query = "select * from customer_balance where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllDebtsPayment($cashbox_id)
    {
        $query = "select * from customer_balance where cashbox_id=" . $cashbox_id . " order by balance_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_customer_payment($payment_id)
    {
        $query = "select customer_id,balance from customer_balance where id=" . $payment_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            my_sql::query("update customers set balance=balance-" . $result[0]["balance"] . " where id=" . $result[0]["customer_id"]);
            my_sql::query("update customer_balance set deleted=1 where id=" . $payment_id);
            return true;
        }
        return false;
    }
    public function getAllPaymentOfCustomer($id)
    {
        $query = "select * from payments where invoice_id in (select id from invoices where customer_id=" . $id . " and other_branche=0) and deleted=0 order by date_of_pay desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllBalancePaymentOfCustomer($id)
    {
        $query = "select * from customer_balance where customer_id=" . $id . " and deleted=0 order by balance_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPaymentsByDate($store_id, $date)
    {
        $query = "select * from customer_balance where store_id=" . $store_id . " and date(balance_date)='" . $date . "' and deleted=0 order by balance_date asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPaymentsByIntervalOfDate($date_range)
    {
        $query = "select * from customer_balance where date(balance_date)>='" . $date_range[0] . "' and date(balance_date)<='" . $date_range[1] . "' and deleted=0 order by balance_date asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalPaymentForSupplier($id)
    {
        $query = "select COALESCE(sum(payment_value*currency_rate), 0) as sum from suppliers_payments where supplier_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalPaymentForCustomer($id)
    {
        $query = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where customer_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalPaymentForCustomer_group()
    {
        $query = "select customer_id,COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 group by customer_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllPaymentForCustomer()
    {
        $query = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and customer_id in (select id from customers where deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllPaymentForSupplier($id)
    {
        $query = "select * from suppliers_payments where supplier_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSupplierPaymentById($id)
    {
        $query = "select * from suppliers_payments where id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_customer_invoice_payments($invoice_id)
    {
        my_sql::query("update payments set deleted=1 where invoice_id=" . $invoice_id);
    }
}

?>