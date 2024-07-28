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
class authorizationModel
{
    public function add_authorization_code($info)
    {
        $query = "select count(id) as num from authorized_devices where operator_id=" . $info["operator_id"] . " and authorized_key='" . $info["authorized_key"] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            return 0;
        }
        $query = "insert into authorized_devices(operator_id,authorized_key,creation_date,cookie,browser_info) values('" . $info["operator_id"] . "','" . $info["authorized_key"] . "',now(),'" . $info["cookies"] . "','" . $info["browser_info"] . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function user_authorized($operator_id, $cookie)
    {
        $query = "select count(id) as num from authorized_devices where operator_id=" . $operator_id . " and authorized_key='" . $cookie . "' and deleted=0 and accepted=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            return true;
        }
        return false;
    }
    public function delete_auth($id)
    {
        $query = "update authorized_devices set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function update_authorization_code($authorization_info)
    {
        $query = "update authorized_devices set authorized_key='" . $authorization_info["authorized_key"] . "',creation_date=now() where operator_id=" . $authorization_info["operator_id"] . " and accepted=0 and TIMESTAMPDIFF(MINUTE, creation_date, now()) >= 3 ";
        my_sql::query($query);
    }
    public function authorization_log($log)
    {
        $query = "insert into authorized_devices_logs(browser_info,ip_address,creation_date) values('" . $log["browser_info"] . "','" . $log["ip"] . "',now())";
        my_sql::query($query);
    }
    public function set_as_authorized($user_id, $scode)
    {
        $query = "update authorized_devices set accepted=1 where operator_id=" . $user_id . " and authorized_key='" . $scode . "' and deleted=0";
        my_sql::query($query);
        return my_sql::get_mysqli_rows_num();
    }
    public function authorization_exist($operator_id, $cookie)
    {
        $query = "select count(id) as num from authorized_devices where operator_id=" . $operator_id . " and authorized_key='" . $cookie . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            return true;
        }
        return false;
    }
    public function authorization_requested($operator_id, $cookie)
    {
        $query = "select * from authorized_devices where operator_id=" . $operator_id . " and authorized_key='" . $cookie . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function all_authorization()
    {
        $query = "select * from authorized_devices where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function check_authorization_code($operator_id, $cookie)
    {
        $query = "select count(id) as num from authorized_devices where operator_id=" . $operator_id . " and authorized_key='" . $cookie . "' and accepted=1 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            return true;
        }
        return false;
    }
    public function authorization_code_exist($operator_id, $cookie)
    {
        $query = "select count(id) as num from authorized_devices where operator_id=" . $operator_id . " and authorized_key='" . $cookie . "' and accepted=0 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            return true;
        }
        return false;
    }
}

?>