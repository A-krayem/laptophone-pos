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
class smsModel
{
    public function list_of_customer($sms_id)
    {
        $query = "select sd.id,sd.status_id,sd.sent_date,sd.excluded,cc.name,cc.phone from sms_details sd,collected_customers cc where sd.customer_id=cc.id and sd.sms_id=" . $sms_id . " ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function exclude($id, $exclude)
    {
        my_sql::query("update sms_details set excluded='" . $exclude . "' where id=" . $id);
    }
    public function get_all_phone_as_txt()
    {
        $query = "select phone from collected_customers where deleted=0 and LENGTH(phone)>=10 and LENGTH(phone)<=11";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_test($sms_id, $price)
    {
        $query = "insert into sms_test(created_by,sms_price,created_date,sms_id) value(" . $_SESSION["id"] . "," . $price . ",'" . my_sql::datetime_now() . "'," . $sms_id . ")";
        my_sql::query($query);
    }
    public function update_balance($value)
    {
        my_sql::query("update settings set value='" . $value . "' where name='sms_balance'");
    }
    public function add_new_sms($info)
    {
        $query = "insert into sms(title,body,start_date,creation_date) value('" . $info["title"] . "','" . $info["body"] . "','" . $info["start_date"] . "','" . my_sql::datetime_now() . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_sms_by_id($id)
    {
        $query = "select * from sms where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_sms_details($customer_info, $sms_id)
    {
        $query = "insert into sms_details(customer_id,status_id,sms_id) value('" . $customer_info["id"] . "',1," . $sms_id . ")";
        my_sql::query($query);
    }
    public function update_sms($info)
    {
        my_sql::query("update sms set title='" . $info["title"] . "',body='" . $info["body"] . "',start_date='" . $info["start_date"] . "' where id=" . $info["id_to_edit"]);
    }
    public function getAllSms()
    {
        $query = "select * from sms where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllSmsStatus()
    {
        $query = "select * from sms_status";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_sms($info)
    {
        my_sql::query("update sms set deleted=1 where id=" . $info["id"]);
    }
    public function validate_phone_number($phone)
    {
        $phone_trimed = preg_replace("/[^0-9]/", "", ltrim(trim($phone), "0"));
        if (8 < strlen($phone_trimed) && substr($phone_trimed, 0, 3) === "961") {
            $phone_base = substr($phone_trimed, 3, strlen($phone_trimed));
            $phone_base_left_zero_cleaned = ltrim($phone_base, "0");
        } else {
            $phone_base_left_zero_cleaned = ltrim($phone_trimed, "0");
        }
        if (0 < strlen($phone_base_left_zero_cleaned)) {
            return "961" . $phone_base_left_zero_cleaned;
        }
        return "";
    }
    public function collect_customers($custom_connection)
    {
        $query = "select CONCAT(name, ' ', middle_name,' ',last_name) as name,phone,id from customers where deleted=0 and id not in (select source_customers_id from collected_customers where deleted=0) group by phone";
        if ($custom_connection == 0) {
            $result = my_sql::fetch_assoc(my_sql::query($query));
        } else {
            $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $custom_connection));
        }
        for ($i = 0; $i < count($result); $i++) {
            $query_ = "insert into collected_customers(name,phone,source_customers_id) values('" . $result[$i]["name"] . "','" . self::validate_phone_number($result[$i]["phone"]) . "','" . $result[$i]["id"] . "')";
            my_sql::query($query_);
        }
    }
    public function collected_nb()
    {
        $query = "select count(id) as nb from collected_customers where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["nb"];
    }
    public function getSmsCustomers($sms_id)
    {
        $query = "select * from sms_details where sms_id=" . $sms_id . " and excluded=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSmsCustomers_to_send($sms_id, $nb)
    {
        $query = "select sd.id,sd.customer_id,cc.phone from sms_details sd,collected_customers cc where sd.customer_id=cc.id and sd.sms_id=" . $sms_id . " and sd.excluded=0 and cc.deleted=0 and sd.status_id=1 order by sd.id asc limit " . $nb;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_sent($id, $price)
    {
        my_sql::query("update sms_details set status_id=2,sent_date='" . my_sql::datetime_now() . "',sms_price=" . $price . " where id=" . $id);
    }
    public function getTotalSpent($sms_id)
    {
        $query = "select COALESCE(sum(sms_price), 0) as sum from sms_details where sms_id=" . $sms_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_failed($id)
    {
        my_sql::query("update sms_details set status_id=3 where id=" . $id);
    }
    public function get_total_pending($sms_id)
    {
        $query = "select count(id) as nb from sms_details where status_id=1 and sms_id=" . $sms_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["nb"];
    }
    public function get_total_sent($sms_id)
    {
        $query = "select count(id) as nb from sms_details where status_id=2 and sms_id=" . $sms_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["nb"];
    }
    public function get_total_failed($sms_id)
    {
        $query = "select count(id) as nb from sms_details where status_id=3 and sms_id=" . $sms_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["nb"];
    }
}

?>