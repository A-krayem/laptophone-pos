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
class userModel
{
    public function getAllUsers()
    {
        $query = "select id,username,role_id,name,commission from users where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllUsersCreateInvoices()
    {
        $query = "select id,username,role_id,name,commission from users where deleted=0 or (id in (select DISTINCT(employee_id) from invoices) or id in (select created_by from quotations)) ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllUsers_Passwords()
    {
        $query = "select id,username,role_id,name,commission,password from users where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_2fa_code($id, $secret)
    {
        $query = "update users set ga_2fa_secret='" . $secret . "' where id=" . $id . " and ga_2fa_secret IS NULL";
        my_sql::query($query);
        return my_sql::get_mysqli_rows_num();
    }
    public function set_enabled_2fa($id, $value)
    {
        if ($value == 1) {
            $query = "update users set ga_2fa_enabled=" . $value . " where id=" . $id;
        } else {
            $query = "update users set ga_2fa_enabled=" . $value . ",ga_2fa_secret=NULL where id=" . $id;
        }
        my_sql::query($query);
        return my_sql::get_mysqli_rows_num();
    }
    public function getAllVendors()
    {
        $query = "select id,username,role_id,name,commission from users where deleted=0 and role_id=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllUsersEvenDeleted()
    {
        $query = "select id,username,role_id,name from users";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_current_num_users()
    {
        $query = "select count(id) as num from users where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
    public function getAllVendorsEvenDeleted()
    {
        $query = "select id,username,role_id,name from users where role_id=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function search_client($search_client)
    {
        $query = "select id,name,phone from customers where deleted=0 and (name like '%" . $search_client . "%' or middle_name like '%" . $search_client . "%' or last_name like '%" . $search_client . "%' or phone like '%" . $search_client . "%' or id like '%" . $search_client . "%') order by name asc limit 10";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getEmployeesnb()
    {
        $query = "select count(id) as num from users where deleted=0 and role_id in (select id from users_role where delivery=1)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllDeliveryEmployees()
    {
        $query = "select * from users where role_id in (select id from users_role where delivery=1) and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllDeliveries()
    {
        $query = "select id,username from users where role_id in (select id from users_role where id=6) and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllUsersPOSEvenDeleted()
    {
        $query = "select * from users where role_id=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllUsersPOS()
    {
        $query = "select * from users where role_id=2 and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_user($id)
    {
        $query = "update users set deleted=1 where id=" . $id;
        $result = my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "update " . DATABASE_SYNC . ".users set deleted=1 where id=" . $id;
            $result = my_sql::query($query);
        }
        return 1;
    }
    public function update_check_key($id, $key)
    {
        $query = "update users set check_key='" . $key . "' where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function get_user_check_key($id)
    {
        $query = "select check_key from users where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_user($info)
    {
        $query = "select * from users where id=" . $info["id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_user_by_id($id)
    {
        $query = "select * from users where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllEmployeesDeliveryRole()
    {
        $query = "select * from users_role where delivery=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_user($info)
    {
        if (0 < strlen($info["password"])) {
            $query = "update users set username='" . $info["username"] . "',password='" . $info["password"] . "',role_id=" . $info["user_type"] . ",hide_critical_data='" . $info["user_critical"] . "',operator_is_admin='" . $info["operator_is_admin"] . "',commission='" . $info["commission"] . "',new_branches_permission='" . implode(",", $info["branches_ids"]) . "' where id=" . $info["id_to_edit"];
        } else {
            $query = "update users set username='" . $info["username"] . "',role_id=" . $info["user_type"] . ",hide_critical_data='" . $info["user_critical"] . "',operator_is_admin='" . $info["operator_is_admin"] . "',commission='" . $info["commission"] . "',new_branches_permission='" . implode(",", $info["branches_ids"]) . "' where id=" . $info["id_to_edit"];
        }
        my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            if (0 < strlen($info["password"])) {
                $query = "update " . DATABASE_SYNC . ".users set username='" . $info["username"] . "',password='" . $info["password"] . "',role_id=" . $info["user_type"] . ",hide_critical_data='" . $info["user_critical"] . "',operator_is_admin='" . $info["operator_is_admin"] . "' where id=" . $info["id_to_edit"];
            } else {
                $query = "update " . DATABASE_SYNC . ".users set username='" . $info["username"] . "',role_id=" . $info["user_type"] . ",hide_critical_data='" . $info["user_critical"] . "',operator_is_admin='" . $info["operator_is_admin"] . "' where id=" . $info["id_to_edit"];
            }
            my_sql::query($query);
        }
    }
    public function add_new_user($info)
    {
        $query = "insert into users(username,password,role_id,store_id,name,creation_date,hide_critical_data,operator_is_admin,commission,new_branches_permission) values('" . $info["username"] . "','" . $info["password"] . "'," . $info["user_type"] . "," . $_SESSION["store_id"] . ",'" . $info["user_id"] . "','" . my_sql::datetime_now() . "','" . $info["user_critical"] . "','" . $info["operator_is_admin"] . "','" . $info["commission"] . "','" . implode(",", $info["branches_ids"]) . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "insert into " . DATABASE_SYNC . ".users(id,username,password,role_id,store_id,name,creation_date,hide_critical_data,operator_is_admin) values(" . $last_id . ",'" . $info["username"] . "','" . $info["password"] . "'," . $info["user_type"] . "," . $_SESSION["store_id"] . ",'" . $info["user_id"] . "','" . my_sql::datetime_now() . "','" . $info["user_critical"] . "','" . $info["operator_is_admin"] . "')";
            my_sql::query($query);
        }
        return $last_id;
    }
    public function login_history($info)
    {
        $query = "insert into login_history(user_id,creation_date,login_out) values('" . $info["user_id"] . "','" . my_sql::datetime_now() . "','" . $info["login_out"] . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function close_login_history($id)
    {
        $query = "update login_history set closed_date='" . my_sql::datetime_now() . "' where id=" . $id;
        my_sql::query($query);
    }
    public function getUserInfo($info)
    {
        $query = "select * from users where username='" . $info["username"] . "' and password='" . $info["password"] . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getVendors()
    {
        $query = "select id,username from users where role_id=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function updateCustomerID($pID)
    {
        $query = "update settings set value='" . $pID . "' where name='customer_id'";
        my_sql::query($query);
    }
}

?>