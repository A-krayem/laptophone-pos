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
class logsModel
{
    public function get_user_customers_logs($daterange, $user_id)
    {
        if ($user_id == 0) {
            $query = "select * from customers_logs where date(action_date)>='" . $daterange[0] . "' and date(action_date)<='" . $daterange[1] . "'";
        } else {
            $query = "select * from customers_logs where user_id=" . $user_id . " and date(action_date)>='" . $daterange[0] . "' and date(action_date)<='" . $daterange[1] . "'";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_user_customers_logs_by_id($id)
    {
        $query = "select * from customers_logs where customer_id=" . $id . " order by action_date asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>