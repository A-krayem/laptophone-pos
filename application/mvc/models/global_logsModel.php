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
class global_logsModel
{
    public function add_global_log($info)
    {
        if (!isset($info["related_to_client_id"])) {
            $info["related_to_client_id"] = 0;
        }
        $query = "insert into global_logs(created_by,creation_date,related_to_item_id,description,log_type,other_info,related_to_client_id) " . "values('" . $info["operator_id"] . "','" . my_sql::datetime_now() . "','" . $info["related_to_item_id"] . "','" . $info["description"] . "','" . $info["log_type"] . "','" . $info["other_info"] . "','" . $info["related_to_client_id"] . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function get_global_logs($log_type, $date_range, $client_id)
    {
        $client_filter = "";
        if (0 < $client_id) {
            $client_filter = " and related_to_client_id=" . $client_id;
        }
        $query = "select * from global_logs where log_type=" . $log_type . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' " . $client_filter . " order by creation_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>