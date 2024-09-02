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
class suppliersModel
{
    public function getSuppliers()
    {
        $query = "select sup.id,sup.name,sup.contact_name,sup.email,sup.address,ct.country_name,sup.starting_balance,sup.usd_starting_balance from suppliers as sup join countries as ct on ct.id=sup.country_id and sup.deleted=0  order by sup.name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSuppliersByArray($ids)
    {
        $query = "select * from suppliers where id in (" . implode(",", $ids) . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSuppliersByArray_remote($ids, $cnx)
    {
        $query = "select * from suppliers where id in (" . implode(",", $ids) . ")";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function getSupplierContacts($sup_id)
    {
        $query = "select * from phones where supplier_id=" . $sup_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function start_date_supplier($supplier_id, $currency_id)
    {
        $query = "select DATEDIFF('" . my_sql::datetime_now() . "', creation_date) as days from suppliers where id=" . $supplier_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["days"];
    }
    public function check_if_start_date_equal_starting_balance_date_supplier($start_date, $supplier_id)
    {
        $query = "select count(id) as num from suppliers where id=" . $supplier_id . " and date(creation_date)>='" . $start_date . "' ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            return true;
        }
        return false;
    }
    public function get_balances_suppliers($currency, $supplier_id, $date_range, $return_details, $without_starting_date)
    {
        $brought_balance = "";
        if ($date_range[0] != "all") {
            $date_range_tmp = explode(" - ", $date_range);
            $date = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $brought_balance = " and date(creation_date)<'" . $date . "'";
        }
        $brought_balance_starting = "";
        if ($date_range[0] != "all") {
            $date_range_tmp = explode(" - ", $date_range);
            $date = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $brought_balance_starting = " and date(creation_date)<'" . $date . "'";
        }
        $brought_payment_date = "";
        if ($date_range[0] != "all") {
            $date_range_tmp = explode(" - ", $date_range);
            $date = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $brought_payment_date = " and date(payment_date)<'" . $date . "'";
        }
        $debit = 0;
        $credit = 0;
        if ($without_starting_date == 0) {
            if ($supplier_id == 0) {
                $query_starting_balance = "select * from suppliers where deleted=0 " . $brought_balance_starting;
            } else {
                $query_starting_balance = "select * from suppliers where deleted=0 and id=" . $supplier_id . " " . $brought_balance_starting;
            }
            $result_starting_balance = my_sql::fetch_assoc(my_sql::query($query_starting_balance));
            for ($i = 0; $i < count($result_starting_balance); $i++) {
                if ($currency == 1) {
                    if ($result_starting_balance[$i]["usd_starting_balance"] < 0) {
                        $credit += abs($result_starting_balance[$i]["usd_starting_balance"]);
                    } else {
                        $debit += abs($result_starting_balance[$i]["usd_starting_balance"]);
                    }
                }
                if ($currency == 2) {
                    if ($result_starting_balance[$i]["lbp_starting_balance"] < 0) {
                        $credit += abs($result_starting_balance[$i]["lbp_starting_balance"]);
                    } else {
                        $debit += abs($result_starting_balance[$i]["lbp_starting_balance"]);
                    }
                }
            }
        }
        if ($supplier_id == 0) {
            $query_pi = "select COALESCE(sum(total), 0) as sum from receive_stock_invoices where deleted=0 and supplier_id in (select id from suppliers where deleted=0) and currency_id=" . $currency . " " . $brought_balance;
        } else {
            $query_pi = "select COALESCE(sum(total), 0) as sum from receive_stock_invoices where deleted=0 and supplier_id in (select id from suppliers where deleted=0) and currency_id=" . $currency . " and supplier_id=" . $supplier_id . " " . $brought_balance;
        }
        $query_pi_result = my_sql::fetch_assoc(my_sql::query($query_pi));
        $credit += $query_pi_result[0]["sum"];
        if ($supplier_id == 0) {
            $query_pi_debit = "select COALESCE(sum(debit_value), 0) as sum from debit_notes where deleted=0 and supplier_id in (select id from suppliers where deleted=0) and payment_currency=" . $currency . " " . $brought_balance;
        } else {
            $query_pi_debit = "select COALESCE(sum(debit_value), 0) as sum from debit_notes where deleted=0 and supplier_id in (select id from suppliers where deleted=0) and payment_currency=" . $currency . " and supplier_id=" . $supplier_id . " " . $brought_balance;
        }
        $query_pi_debit_result = my_sql::fetch_assoc(my_sql::query($query_pi_debit));
        $debit += $query_pi_debit_result[0]["sum"];
        if ($supplier_id == 0) {
            $query_pi_pay = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where deleted=0 and supplier_id in (select id from suppliers where deleted=0) and payment_currency=" . $currency . " " . $brought_payment_date;
        } else {
            $query_pi_pay = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where deleted=0 and supplier_id in (select id from suppliers where deleted=0) and payment_currency=" . $currency . " and supplier_id=" . $supplier_id . " " . $brought_payment_date;
        }
        $query_pi_pay_result = my_sql::fetch_assoc(my_sql::query($query_pi_pay));
        $debit += $query_pi_pay_result[0]["sum"];
        if ($return_details == 0) {
            return $debit - $credit;
        }
        $_info = array();
        $_info["debit"] = $debit;
        $_info["credit"] = $credit;
        $_info["balance"] = $debit - $credit;
        return $_info;
    }
    public function get_suppliers_pi($date_range)
    {
        $stmt = array();
        $query_pi_ = "select * from receive_stock_invoices where deleted=0 and date(receive_invoice_date)>='" . $date_range[0] . "' and date(receive_invoice_date)<='" . $date_range[1] . "'";
        $query_pi_result = my_sql::fetch_assoc(my_sql::query($query_pi_));
        for ($i = 0; $i < count($query_pi_result); $i++) {
            array_push($stmt, array("timestamp" => strtotime($query_pi_result[$i]["receive_invoice_date"]), "creation_date" => $query_pi_result[$i]["receive_invoice_date"], "created_by" => $query_pi_result[$i]["created_by"], "reference" => $query_pi_result[$i]["invoice_reference"], "debit" => 0, "credit" => $query_pi_result[$i]["total"], "st_balance" => 0, "desc" => "PI (" . $query_pi_result[$i]["id"] . ")", "qin" => $query_pi_result[$i]["total_qty"], "qout" => 0, "total_amount_out" => $query_pi_result[$i]["total"], "total_amount_in" => 0));
        }
        $qty_sum = array();
        $query_inv_qty = "select invoice_id,sum(qty) as tqty from invoice_items where invoice_id in (select id from invoices where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "') group by invoice_id";
        $query_inv_qty_result = my_sql::fetch_assoc(my_sql::query($query_inv_qty));
        for ($i = 0; $i < count($query_inv_qty_result); $i++) {
            $qty_sum[$query_inv_qty_result[$i]["invoice_id"]] = $query_inv_qty_result[$i]["tqty"];
        }
        $query_inv_ = "select * from invoices where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $query_inv_result = my_sql::fetch_assoc(my_sql::query($query_inv_));
        for ($i = 0; $i < count($query_inv_result); $i++) {
            array_push($stmt, array("timestamp" => strtotime($query_inv_result[$i]["creation_date"]), "creation_date" => $query_inv_result[$i]["creation_date"], "created_by" => $query_inv_result[$i]["created_by"], "reference" => $query_inv_result[$i]["invoice_reference"], "debit" => $query_inv_result[$i]["total_value"], "credit" => 0, "st_balance" => 0, "desc" => "INV (" . $query_inv_result[$i]["id"] . ")", "qin" => 0, "qout" => $qty_sum[$query_inv_result[$i]["id"]], "total_amount_out" => 0, "total_amount_in" => $query_inv_result[$i]["total_value"]));
        }
        $query_exp = "select * from expenses where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $query_exp_result = my_sql::fetch_assoc(my_sql::query($query_exp));
        for ($i = 0; $i < count($query_exp_result); $i++) {
            array_push($stmt, array("timestamp" => strtotime($query_exp_result[$i]["creation_date"]), "creation_date" => $query_exp_result[$i]["creation_date"], "created_by" => $query_exp_result[$i]["vendor_id"], "reference" => $query_exp_result[$i]["id"], "debit" => 0, "credit" => $query_exp_result[$i]["value"], "st_balance" => 0, "desc" => "EXP (" . $query_exp_result[$i]["id"] . ")", "qin" => 0, "qout" => 0, "total_amount_out" => $query_exp_result[$i]["value"], "total_amount_in" => 0));
        }
        return $stmt;
    }
    public function get_balances_suppliers_details($supplier_id, $currency, $date_range)
    {
        $stmt = array();
        $query_starting_balance = "select * from suppliers where deleted=0 and id=" . $supplier_id;
        $result_starting_balance = my_sql::fetch_assoc(my_sql::query($query_starting_balance));
        if ($currency == 1) {
            if ($result_starting_balance[0]["usd_starting_balance"] < 0) {
                array_push($stmt, array("timestamp" => strtotime($result_starting_balance[0]["creation_date"]), "creation_date" => $result_starting_balance[0]["creation_date"], "created_by" => 0, "reference" => "", "debit" => 0, "credit" => abs($result_starting_balance[0]["usd_starting_balance"]), "st_balance" => 1, "desc" => "Starting Balance"));
            } else {
                array_push($stmt, array("timestamp" => strtotime($result_starting_balance[0]["creation_date"]), "creation_date" => $result_starting_balance[0]["creation_date"], "created_by" => 0, "reference" => "", "debit" => $result_starting_balance[0]["usd_starting_balance"], "credit" => 0, "st_balance" => 1, "desc" => "Starting Balance"));
            }
        }
        if ($currency == 2) {
            if ($result_starting_balance[0]["lbp_starting_balance"] < 0) {
                array_push($stmt, array("timestamp" => strtotime($result_starting_balance[0]["creation_date"]), "creation_date" => $result_starting_balance[0]["creation_date"], "created_by" => 0, "reference" => "", "debit" => 0, "credit" => abs($result_starting_balance[0]["lbp_starting_balance"]), "st_balance" => 1, "desc" => "Starting Balance"));
            } else {
                array_push($stmt, array("timestamp" => strtotime($result_starting_balance[0]["creation_date"]), "creation_date" => $result_starting_balance[0]["creation_date"], "created_by" => 0, "reference" => "", "debit" => $result_starting_balance[0]["lbp_starting_balance"], "credit" => 0, "st_balance" => 1, "desc" => "Starting Balance"));
            }
        }
        $query_pi_ = "select * from receive_stock_invoices where deleted=0 and currency_id=" . $currency . " and supplier_id=" . $supplier_id . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $query_pi_result = my_sql::fetch_assoc(my_sql::query($query_pi_));
        for ($i = 0; $i < count($query_pi_result); $i++) {
            array_push($stmt, array("timestamp" => strtotime($query_pi_result[$i]["creation_date"]), "creation_date" => $query_pi_result[$i]["creation_date"], "created_by" => $query_pi_result[$i]["created_by"], "reference" => $query_pi_result[$i]["invoice_reference"], "debit" => 0, "credit" => $query_pi_result[$i]["total"], "st_balance" => 0, "desc" => "Purchase Invoice (" . $query_pi_result[$i]["id"] . ")"));
        }
        $query_pi_dn = "select * from debit_notes where deleted=0 and payment_currency=" . $currency . " and supplier_id=" . $supplier_id . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $query_pi_dn_result = my_sql::fetch_assoc(my_sql::query($query_pi_dn));
        for ($i = 0; $i < count($query_pi_dn_result); $i++) {
            array_push($stmt, array("timestamp" => strtotime($query_pi_dn_result[$i]["creation_date"]), "creation_date" => $query_pi_dn_result[$i]["creation_date"], "created_by" => $query_pi_dn_result[$i]["created_by"], "reference" => $query_pi_dn_result[$i]["reference"], "debit" => $query_pi_dn_result[$i]["debit_value"], "credit" => 0, "st_balance" => 0, "desc" => "Debit Note (" . $query_pi_dn_result[$i]["id"] . ")"));
        }
        $query_pi_pay = "select * from suppliers_payments where deleted=0 and payment_currency=" . $currency . " and supplier_id=" . $supplier_id . " and date(payment_date)>='" . $date_range[0] . "' and date(payment_date)<='" . $date_range[1] . "'";
        $query_pi_pay_result = my_sql::fetch_assoc(my_sql::query($query_pi_pay));
        for ($i = 0; $i < count($query_pi_pay_result); $i++) {
            if ($_SESSION["role"] == 1) {
                $del = "<i onclick='delete_payment(" . $query_pi_pay_result[$i]["id"] . ")' class='glyphicon glyphicon-trash tohide' style='font-size:12px;color:red;cursor:pointer'></i>";
            }
            if ($_SESSION["role"] == 2 && $_SESSION["cashbox_id"] == $query_pi_pay_result[$i]["cashbox_id"]) {
                $del = "<i onclick='delete_payment(" . $query_pi_pay_result[$i]["id"] . ")' class='glyphicon glyphicon-trash tohide' style='font-size:12px;color:red;cursor:pointer'></i>";
            }
            array_push($stmt, array("timestamp" => strtotime($query_pi_pay_result[$i]["payment_date"]), "creation_date" => $query_pi_pay_result[$i]["payment_date"], "created_by" => "c-" . $query_pi_pay_result[$i]["cashbox_id"], "reference" => $query_pi_pay_result[$i]["reference"], "debit" => $query_pi_pay_result[$i]["payment_value"], "credit" => 0, "st_balance" => 0, "desc" => "Payment On Account " . $del));
        }
        return $stmt;
    }
    public function getAllSuppliersPaymentsDateRange___($daterange)
    {
        $query_8 = "select * from suppliers_payments where deleted=0 and date(payment_date)>='" . $daterange[0] . "' and date(payment_date)<='" . $daterange[1] . "'";
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8;
    }
    public function get_all_payment_of_suppliers_by_cashbox($cashbox_id)
    {
        $query = "select sp.cash_in_usd,sp.cash_in_lbp,sp.returned_usd,sp.returned_lbp,s.name from suppliers_payments sp left join suppliers s on s.id=sp.supplier_id where sp.deleted=0 and sp.cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllSuppliersEvenDeleted()
    {
        $query = "select * from suppliers";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_supplier_by_id($id)
    {
        $query = "select * from suppliers where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_previews_balance($supplier_id, $start_date)
    {
        $query_suppliers_payment = "select COALESCE(sum(payment_value*currency_rate), 0) as sum from suppliers_payments where deleted=0 and supplier_id=" . $supplier_id . " and date(payment_date)<'" . $start_date . "'";
        $result_suppliers_payment = my_sql::fetch_assoc(my_sql::query($query_suppliers_payment));
        $query_invoices = "select COALESCE(sum(total*cur_rate), 0) as sum from receive_stock_invoices where supplier_id=" . $supplier_id . " and paid_status!=1 and deleted=0 and date(receive_invoice_date)<'" . $start_date . "'";
        $result_invoices = my_sql::fetch_assoc(my_sql::query($query_invoices));
        $query_debit = "select COALESCE(sum(debit_value*currency_rate), 0) as sum from debit_notes where supplier_id=" . $supplier_id . " and deleted=0 and date(creation_date)<'" . $start_date . "'";
        $result_debit = my_sql::fetch_assoc(my_sql::query($query_debit));
        return $result_invoices[0]["sum"] - $result_suppliers_payment[0]["sum"] - $result_debit[0]["sum"];
    }
    public function getsup_name_for_type_head()
    {
        $query = "select id,name as name from suppliers group by name";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function supplier_payment_exist($id)
    {
        $query = "select count(id) as num from suppliers_payments where supplier_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function delete_suppliers($id)
    {
        $query = "update suppliers set deleted=1 where id=" . $id;
        $result = my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        return $result;
    }
    public function getTotalSuppliersPaymentsByCashbox($id)
    {
        $query_8 = "select COALESCE(sum(payment_value*currency_rate), 0) as sum from suppliers_payments where payment_method=1 and deleted=0 and cashbox_id=" . $id;
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8[0]["sum"];
    }
    public function getTotalSuppliersPaymentsByCashboxMethod($id, $method)
    {
        $query_8 = "select COALESCE(sum(payment_value*currency_rate), 0) as sum from suppliers_payments where payment_method=" . $method . " and deleted=0 and cashbox_id=" . $id;
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8[0]["sum"];
    }
    public function getTotalSuppliersPaymentsByDateRange($info)
    {
        $query_8 = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where deleted=0 and date(payment_date)>='" . $info["start_date"] . "' and date(payment_date)<='" . $info["end_date"] . "'";
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8[0]["sum"];
    }
    public function getAllSuppliersPaymentsByCashbox($id)
    {
        $query_8 = "select * from suppliers_payments where payment_method=1 and deleted=0 and cashbox_id=" . $id;
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8;
    }
    public function getAllSuppliersPayments($supplier_id)
    {
        $query_8 = "select * from suppliers_payments where deleted=0 and supplier_id=" . $supplier_id;
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8;
    }
    public function getAllSuppliersPaymentsDateRange($supplier_id, $daterange)
    {
        $query_8 = "select * from suppliers_payments where deleted=0 and supplier_id=" . $supplier_id . " and date(payment_date)>='" . $daterange[0] . "' and date(payment_date)<='" . $daterange[1] . "'";
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8;
    }
    public function delete_supplier_payment($id)
    {
        $query = "update suppliers_payments set deleted=1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function addSupplier($info)
    {
        $query = "insert into suppliers(name,country_id,contact_name,address,user_id,starting_balance,email,creation_date,debit_credit,usd_starting_balance,lbp_starting_balance) values('" . $info["sup_name"] . "'," . $info["sup_country"] . ",'" . $info["sup_contact"] . "','" . $info["sup_adr"] . "'," . $info["user_id"] . "," . $info["starting_balance"] . ",'" . $info["email"] . "','" . my_sql::datetime_now() . "'," . $info["deb_cred"] . "," . $info["usd_starting_balance"] . "," . $info["lbp_starting_balance"] . ")";
        my_sql::query($query);
        $result = my_sql::query("select MAX(id) as maxid from suppliers;");
        $row = mysqli_fetch_assoc($result);
        $last_id =  $row['maxid'];
        if (0 < $last_id) {
            my_sql::global_query_sync("insert into suppliers(id,name,country_id,contact_name,address,user_id,starting_balance,email,usd_starting_balance,lbp_starting_balance) values(" . $mysqli_insert_id . ",'" . $info["sup_name"] . "'," . $info["sup_country"] . ",'" . $info["sup_contact"] . "','" . $info["sup_adr"] . "'," . $info["user_id"] . "," . $info["starting_balance"] . ",'" . $info["email"] . "'," . $info["usd_starting_balance"] . "," . $info["lbp_starting_balance"] . ")");
        }
        return $mysqli_insert_id;
    }
    public function updateSupplier($info)
    {
        $query = "update suppliers set name='" . $info["sup_name"] . "',country_id=" . $info["sup_country"] . ",contact_name='" . $info["sup_contact"] . "',address='" . $info["sup_adr"] . "',starting_balance='" . $info["starting_balance"] . "',email='" . $info["email"] . "',debit_credit=" . $info["deb_cred"] . ",usd_starting_balance=" . $info["usd_starting_balance"] . ",lbp_starting_balance=" . $info["lbp_starting_balance"] . " where id=" . $info["id"];
        my_sql::query($query);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
        my_sql::query("update phones set phone_number='" . $info["sup_phone"] . "' where supplier_id=" . $info["id"]);
        if (0 < my_sql::get_mysqli_rows_num()) {
            my_sql::global_query_sync($query);
        }
    }
    public function getSupplier($id)
    {
        $query = "select sup.id,sup.name,sup.contact_name,sup.address,ct.country_name,sup.email,ct.id as c_id,sup.starting_balance,sup.debit_credit,sup.usd_starting_balance,sup.lbp_starting_balance from suppliers as sup join countries as ct on ct.id=sup.country_id where sup.id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoicesOfSupplier($id)
    {
        $query = "select * from receive_stock_invoices where supplier_id=" . $id . " and paid_status!=1 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoicesOfSupplierDateRange($id, $daterange)
    {
        $query = "select * from receive_stock_invoices where supplier_id=" . $id . " and paid_status!=1 and deleted=0 and date(receive_invoice_date)>='" . $daterange[0] . "' and date(receive_invoice_date)<='" . $daterange[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPIItemsOfSupplier($id)
    {
        $query = "select rsi.receive_invoice_date as receive_invoice_date,rs.qty,rs.cost,rs.discount_percentage,rs.discount_percentage_2,rs.vat,rs.discount_after_vat,it.description  from receive_stock rs,receive_stock_invoices rsi,items it where rs.item_id=it.id and rs.receive_stock_invoice_id=rsi.id and rsi.supplier_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInvoicesOfSupplier($id)
    {
        $query = "select * from receive_stock_invoices where deleted=0 and supplier_id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalSuppliersPaid($supplier_id)
    {
        $query = "select COALESCE(sum(payment_value), 0) as sum from suppliers_payments where supplier_id=" . $supplier_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllSuppliersPaid()
    {
        $query = "select COALESCE(sum(payment_value*currency_rate), 0) as sum from suppliers_payments where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalSuppliersDebitNote($supplier_id)
    {
        $query = "select COALESCE(sum(debit_value), 0) as sum from debit_notes where supplier_id=" . $supplier_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllSuppliersDebitNote_byid($supplier_id, $date_range)
    {
        $query = "select * from debit_notes where supplier_id=" . $supplier_id . " and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllSuppliersDebitNote()
    {
        $query = "select COALESCE(sum(debit_value*currency_rate), 0) as sum from debit_notes where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalSuppliersInvoicesValue($supplier_id)
    {
        $query = "select COALESCE(sum(total), 0) as sum from receive_stock_invoices where supplier_id=" . $supplier_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllSuppliersInvoicesValue()
    {
        $query = "select COALESCE(sum(total*cur_rate), 0) as sum from receive_stock_invoices where deleted=0 and supplier_id in (select id from suppliers where deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_starting_balance()
    {
        $query = "select COALESCE(sum(starting_balance), 0) as sum from suppliers where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function search($search, $page, $perPage, $checkHasMore = false)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $query = "SELECT " . $select . " FROM suppliers where name like \"%" . $search . "%\"  " . $limiter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
}

?>