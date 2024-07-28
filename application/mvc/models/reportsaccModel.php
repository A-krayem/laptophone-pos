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
class reportsaccModel
{
    public function get_total_sales_of_months($months)
    {
        $return = array();
        for ($i = 0; $i < count($months); $i++) {
            $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where deleted=0 and other_branche=0 and YEAR(creation_date)=YEAR('" . $months[$i] . "') and MONTH(creation_date)=MONTH('" . $months[$i] . "')";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            array_push($return, round(floatval($result[0]["sum"]), 2));
        }
        return $return;
    }
    public function get_total_sales_of_days($months)
    {
        $return = array();
        for ($i = 0; $i < count($months); $i++) {
            $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)=date('" . $months[$i] . "')";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            array_push($return, round(floatval($result[0]["sum"]), 2));
        }
        return $return;
    }
    public function top_sales_products($date_range, $products_limit)
    {
        $query = "select item_id,count(item_id) as num from invoice_items where 1 group by item_id order by num desc limit " . $products_limit;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function top_sales_customers($date_range, $customers_limit)
    {
        $query = "select customer_id,COALESCE(sum(total_value)) as sum from invoices where customer_id>0 group by customer_id order by sum desc limit " . $customers_limit;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function clients_creation_date($customers_limit, $_sdate, $_edate)
    {
        $query = "select customer_id,COALESCE(sum(total_value)) as sum from invoices where customer_id>0 and date(creation_date)>=date('" . $_sdate . "') and date(creation_date)<=date('" . $_edate . "')  group by customer_id order by sum desc limit " . $customers_limit;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_profits_of_months($months)
    {
        $return = array();
        for ($i = 0; $i < count($months); $i++) {
            $query = "select COALESCE(sum(profit_after_discount)) as sum from invoices where deleted=0 and other_branche=0 and YEAR(creation_date)=YEAR('" . $months[$i] . "') and MONTH(creation_date)=MONTH('" . $months[$i] . "')";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            array_push($return, round(floatval($result[0]["sum"]), 2));
        }
        return $return;
    }
    public function get_total_profits_of_days($months)
    {
        $return = array();
        for ($i = 0; $i < count($months); $i++) {
            $query = "select COALESCE(sum(profit_after_discount)) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)=date('" . $months[$i] . "')";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            array_push($return, round(floatval($result[0]["sum"]), 2));
        }
        return $return;
    }
    public function get_total_expenses_of_months($months)
    {
        $return = array();
        for ($i = 0; $i < count($months); $i++) {
            $query = "select COALESCE(sum(value)) as sum from expenses where deleted=0 and YEAR(date)=YEAR('" . $months[$i] . "') and MONTH(date)=MONTH('" . $months[$i] . "')";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            array_push($return, round(floatval($result[0]["sum"]), 2));
        }
        return $return;
    }
    public function get_total_expenses_of_days($months)
    {
        $return = array();
        for ($i = 0; $i < count($months); $i++) {
            $query = "select COALESCE(sum(value)) as sum from expenses where deleted=0 and date(date)=date('" . $months[$i] . "')";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            array_push($return, round(floatval($result[0]["sum"]), 2));
        }
        return $return;
    }
}

?>