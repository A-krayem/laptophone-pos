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
class journalModel
{
    public function get_invoices($date_range)
    {
        $query = "select * from invoices where total_value>0 and other_branche=0 and deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPaymentsByIntervalOfDate($date_range)
    {
        $query = "select * from customer_balance where deleted=0 and date(balance_date)>='" . $date_range[0] . "' and date(balance_date)<='" . $date_range[1] . "' order by balance_date asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_credit_note_by_daterange($date_range)
    {
        $query = "select * from credit_notes where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_pi_by_date_range($date_range)
    {
        $query = "select * from receive_stock_invoices where deleted=0 and date(delivery_date)>='" . $date_range[0] . "' and date(delivery_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_suppliers_payment_by_daterange($date_range)
    {
        $query = "select * from suppliers_payments where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function debit_notes($date_range)
    {
        $query = "select * from debit_notes where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>