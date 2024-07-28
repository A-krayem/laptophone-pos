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
class dashboardModel
{
    public function get_items_num($store_id)
    {
        $query = "select COALESCE(sum(qty), 0) as num from invoice_items where invoice_id in (select id from invoices where other_branche=0 and date(creation_date)=date('" . my_sql::datetime_now() . "') and store_id=" . $store_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_low_items_num($store_id)
    {
        $query = "select count(id) as num from store_items where store_id=" . $store_id . " and quantity<5";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_suppliers_num()
    {
        $query = "select count(id) as num from suppliers";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_users_num()
    {
        $query = "select count(id) as num from users";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_items_categories_num()
    {
        $query = "select count(id) as num from items_categories";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_profit($store_id)
    {
        $query = "SELECT COALESCE(sum(profit_after_discount), 0) as profit FROM invoices where other_branche=0 and date(creation_date)=date('" . my_sql::datetime_now() . "') and store_id=" . $store_id . "";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_debts($store_id)
    {
        $query = "SELECT COALESCE(sum(final_price_disc_qty), 0) as sum_of_debts FROM invoice_items WHERE invoice_id in (select id from invoices where other_branche=0 and (closed=0 and auto_closed=0) and store_id=" . $store_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function total_mtc_balance($store_id)
    {
        $query = "SELECT COALESCE(sum(balance), 0) as sum_of_mtc FROM mobile_devices where operator_id=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function total_alfa_balance($store_id)
    {
        $query = "SELECT COALESCE(sum(balance), 0) as sum_of_alfa FROM mobile_devices where operator_id=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getProfitOfDays($store_id, $daterange)
    {
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $query = "SELECT date(creation_date) as creation_date,sum(profit_after_discount) as sum_profit,sum(total_value) as total_value FROM invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)>='" . $daterange["start_date"] . "' and date(creation_date)<='" . $daterange["end_date"] . "' group by date(creation_date)";
        } else {
            $query = "SELECT date(creation_date) as creation_date,sum(0) as sum_profit,sum(total_value) as total_value FROM invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)>='" . $daterange["start_date"] . "' and date(creation_date)<='" . $daterange["end_date"] . "' group by date(creation_date)";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalSalesByTypes($store_id, $daterange)
    {
        $query = "SELECT ROUND(sum(COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0))) as sum_value,payment_method FROM invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date)>='" . $daterange["start_date"] . "' and date(creation_date)<='" . $daterange["end_date"] . "' group by payment_method";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getProfitOfMonths($store_id)
    {
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $query = "SELECT date(creation_date) as creation_date,sum(total_profit+invoice_discount) as sum_profit,sum(total_value+invoice_discount) as total_value FROM invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date) >= DATE_ADD(CURDATE(), INTERVAL -6 MONTH) group by MONTH(creation_date)";
        } else {
            $query = "SELECT date(creation_date) as creation_date,sum(0) as sum_profit,sum(total_value+invoice_discount) as total_value FROM invoices where other_branche=0 and store_id=" . $store_id . " and date(creation_date) >= DATE_ADD(CURDATE(), INTERVAL -6 MONTH) group by MONTH(creation_date)";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getBestSellerLifeTime($store_id)
    {
        $query = "SELECT count(item_id) as qty,item_id FROM invoice_items where invoice_id in (select id from invoices where other_branche=0 and store_id=" . $store_id . " ) group by item_id order by qty desc limit 10";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getMostProfitable($store_id)
    {
        $query = "SELECT COALESCE(sum(profit), 0) as sum_of_profit,count(item_id) as qty,item_id FROM invoice_items where invoice_id in (select id from invoices where other_branche=0 and store_id=" . $store_id . " ) group by item_id order by sum_of_profit desc limit 10";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>