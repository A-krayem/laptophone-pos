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
class mobileStoreModel
{
    public function getOperators()
    {
        $query = "select * from mobile_operators where disabled=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_international_calls()
    {
        $query = "select * from mobile_international_calls where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_international_call($id)
    {
        $query = "select * from mobile_international_calls where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_international_calls_balance()
    {
        $query = "select * from international_calls_balance where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_international_call($info)
    {
        $query = "insert into mobile_international_calls(country_id,rate) values(" . $info["country_id"] . ",'" . $info["country_rate"] . "')";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function add_international_call_balance($info)
    {
        $query = "insert into international_calls_balance(value,date,description,rate,current_balance,current_rate) values(" . $info["int_balance"] . ",'" . my_sql::datetime_now() . "','" . $info["int_description"] . "'," . $info["int_balance_rate"] . "," . $info["current_balance"] . "," . $info["current_rate"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function international_calc($info)
    {
        $current_balance = $info["international_calls_balance"] + $info["international_calls_balance_current"];
        $average = ($info["international_calls_balance_current"] * $info["international_calls_source_rate_current"] + $info["international_calls_balance"] * $info["international_calls_source_rate"]) / ($info["international_calls_balance"] + $info["international_calls_balance_current"]);
        my_sql::query("update settings set value='" . $current_balance . "' where name='international_calls_balance'");
        my_sql::query("update settings set value='" . $average . "' where name='international_calls_source_rate'");
    }
    public function delete_international_calls_balance($id)
    {
        $query = "update international_calls_balance set deleted=1 where id = " . $id;
        my_sql::query($query);
        $query = "select * from international_calls_balance where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        my_sql::query("update settings set value=value-" . $result[0]["value"] . " where name='international_calls_balance'");
        my_sql::query("update settings set value='" . $result[0]["current_rate"] . "' where name='international_calls_source_rate'");
    }
    public function update_international_call($info)
    {
        $query = "update mobile_international_calls set country_id=" . $info["country_id"] . ",rate='" . $info["country_rate"] . "' where id = " . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function delete_internationl_call($info)
    {
        $query = "update mobile_international_calls set deleted=1 where id = " . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function get_credits_losts($date_range, $regular_filter)
    {
        if ($regular_filter == 1) {
            $query = "select * from mobile_credits_history where (sms_fees>0 or additional_fees>0) and date(returned_date)>='" . $date_range[0] . "' and date(returned_date)<='" . $date_range[1] . "' and returned_by>0 ";
        }
        if ($regular_filter == 0) {
            $query = "select * from mobile_credits_history where (sms_fees>0 or additional_fees>0) and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_fees_as_returned($id, $extra_sms_fees, $extra_sms_fees_value, $return_sms_fees, $sms_fees)
    {
        $query = "update mobile_credits_history set returned=1,returned_date='" . my_sql::datetime_now() . "',returned_by=" . $_SESSION["id"] . " where id = " . $id;
        my_sql::query($query);
        if ($extra_sms_fees == 1) {
            $query = "update mobile_credits_history set additional_fees=" . $extra_sms_fees_value . " where id=" . $id;
            my_sql::query($query);
        }
        if ($return_sms_fees == 1) {
            $query = "update mobile_credits_history set returned_fees=1 where id=" . $id;
            my_sql::query($query);
        }
    }
    public function addSimPackage($info)
    {
        $query = "insert into mobile_dollars(qty,price,operator_id,sms_cost,days,return_credits,credit_cost,type) values(" . $info["dollars_nb"] . "," . $info["price"] . "," . $info["operator_id"] . "," . $info["sms_cost"] . ",0," . $info["c_return"] . "," . $info["credits_cost"] . "," . $info["type"] . ")";
        my_sql::query($query);
    }
    public function addPackage($info)
    {
        $query = "insert into mobile_dollars(qty,price,operator_id,sms_cost,days,credit_cost,description,no_sms_fees,alias) values(" . $info["dollars_nb"] . "," . $info["price"] . "," . $info["operator_id"] . "," . $info["sms_cost"] . ",0," . $info["credits_cost"] . ",'" . $info["description"] . "'," . $info["no_sms_cost"] . ",'" . $info["note"] . "')";
        my_sql::query($query);
    }
    public function addDaysPackage($info)
    {
        $query = "insert into mobile_dollars(qty,price,operator_id,sms_cost,days,return_credits,credit_cost,description,item_related,store_recharge) values(" . $info["dollars_nb"] . "," . $info["price"] . "," . $info["operator_id"] . "," . $info["sms_cost"] . "," . $info["days_nb"] . "," . $info["c_return"] . "," . $info["credits_cost"] . ",'" . $info["description"] . "'," . $info["comp_item_id"] . "," . $info["recharge_line"] . ")";
        my_sql::query($query);
    }
    public function addDevice($info)
    {
        $query = "insert into mobile_devices(description,balance,operator_id,store_id,expiry_date) values('" . $info["description"] . "'," . $info["balance"] . "," . $info["operator_id"] . "," . $info["store_id"] . ",'" . $info["expiry_date"] . "')";
        my_sql::query($query);
    }
    public function updatePackage($info)
    {
        $query = "update mobile_dollars set qty='" . $info["dollars_nb"] . "',price=" . $info["price"] . ",sms_cost=" . $info["sms_cost"] . ",credit_cost=" . $info["credits_cost"] . ",description='" . $info["description"] . "',no_sms_fees=" . $info["no_sms_cost"] . ",alias='" . $info["note"] . "' where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function updateDaysPackage($info)
    {
        $query = "update mobile_dollars set qty=" . $info["dollars_nb"] . ",price=" . $info["price"] . ",days=" . $info["days_nb"] . ",return_credits=" . $info["c_return"] . ",credit_cost=" . $info["credits_cost"] . ",description='" . $info["description"] . "',item_related=" . $info["comp_item_id"] . ",store_recharge=" . $info["recharge_line"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function updateSimPackage($info)
    {
        $query = "update mobile_dollars set qty=" . $info["dollars_nb"] . ",price=" . $info["price"] . ",return_credits=" . $info["c_return"] . ",credit_cost=" . $info["credits_cost"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function updateCredits($info)
    {
        $query = "update mobile_devices set balance=balance+" . $info["balance"] . " where id=" . $info["id"];
        my_sql::query($query);
    }
    public function updateCreditsFees($info)
    {
        $query = "update mobile_devices set balance=balance-" . $info["extra_sms_fees"] . " where id=" . $info["id"];
        my_sql::query($query);
    }
    public function updateDevice($info)
    {
        $query = "update mobile_devices set description='" . $info["description"] . "',balance=" . $info["balance"] . ",expiry_date='" . $info["expiry_date"] . "' where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function delete_pkg($id)
    {
        $query = "update mobile_dollars set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function delete_device($id)
    {
        $query = "update mobile_devices set deleted=1 where id=" . $id;
        my_sql::query($query);
    }
    public function getPackages()
    {
        $query = "select * from mobile_dollars where deleted=0 and days=0 and operator_id in (select id from mobile_operators where disabled=0) order by operator_id asc,qty asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllPackages()
    {
        $query = "select * from mobile_dollars where deleted=0 and store_recharge=0 and operator_id in (select id from mobile_operators where disabled=0) order by no_sms_fees,qty asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getDaysPackages()
    {
        if ($_SESSION["role"] == 1) {
            $query = "select * from mobile_dollars where deleted=0 and days>=1 and operator_id in (select id from mobile_operators where disabled=0) order by operator_id asc,days asc";
        } else {
            $query = "select * from mobile_dollars where deleted=0 and store_recharge=0 and days>=1 and operator_id in (select id from mobile_operators where disabled=0) order by operator_id asc,days asc";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSIMPackages()
    {
        $query = "select * from mobile_dollars where deleted=0 and type=1 and operator_id in (select id from mobile_operators where disabled=0) order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_recharge_history($device_id)
    {
        $query = "select * from mobile_line_recharge where deleted=0 and device_id=" . $device_id . " order by id desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function cancel_recharge($recharge_id)
    {
        $query_info = "select * from mobile_line_recharge where id=" . $recharge_id;
        $result_info = my_sql::fetch_assoc(my_sql::query($query_info));
        $query_pkg_info = "select * from mobile_dollars where id=" . $result_info[0]["package_id"];
        $result_pkg_info = my_sql::fetch_assoc(my_sql::query($query_pkg_info));
        $query = "update mobile_devices set balance=balance-" . $result_pkg_info[0]["qty"] . ",expiry_date=DATE_ADD(expiry_date, INTERVAL -" . $result_pkg_info[0]["days"] . " DAY) where id=" . $result_info[0]["device_id"];
        my_sql::query($query);
        $query = "update store_items set quantity=quantity+1 where item_id=" . $result_pkg_info[0]["item_related"];
        my_sql::query($query);
        $query_stock = "select * from  store_items where item_id=" . $result_pkg_info[0]["item_related"];
        $result_stock = my_sql::fetch_assoc(my_sql::query($query_stock));
        my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $_SESSION["id"] . "," . $result_pkg_info[0]["item_related"] . ",'" . my_sql::datetime_now() . "',1,1," . $result_stock[0]["quantity"] . ",'recharge')");
        my_sql::query("update mobile_line_recharge set deleted=1 where id=" . $recharge_id);
    }
    public function execute_recharge($device_id, $package_id)
    {
        $device_info = self::get_device($device_id);
        $package_info = self::getPackage($package_id);
        $query = "select * from items where id=" . $package_info[0]["item_related"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query = "update store_items set quantity=quantity-1 where item_id=" . $package_info[0]["item_related"];
        my_sql::query($query);
        $query_stock = "select * from  store_items where item_id=" . $package_info[0]["item_related"];
        $result_stock = my_sql::fetch_assoc(my_sql::query($query_stock));
        my_sql::query("insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) values(" . $_SESSION["id"] . "," . $package_info[0]["item_related"] . ",'" . my_sql::datetime_now() . "',-1,1," . $result_stock[0]["quantity"] . ",'recharge')");
        $query = "update mobile_devices set balance=balance+" . $package_info[0]["qty"] . ",expiry_date=DATE_ADD(expiry_date, INTERVAL " . $package_info[0]["days"] . " DAY) where id=" . $device_id;
        my_sql::query($query);
        $query = "insert into mobile_line_recharge(device_id,create_date,cashbox_id,operator_id,item_id,deleted,package_id,cost,from_date,to_date) values(" . $device_id . ",'" . my_sql::datetime_now() . "'," . $_SESSION["cashbox_id"] . "," . $_SESSION["id"] . "," . $package_info[0]["item_related"] . ",0," . $package_id . "," . $package_info[0]["credit_cost"] . ",'" . $device_info[0]["expiry_date"] . "','" . date("Y-m-d", strtotime($device_info[0]["expiry_date"] . " + " . $package_info[0]["days"] . " days")) . "')";
        my_sql::query($query);
    }
    public function get_all_items_related_to_recharge($device_id)
    {
        $device_info = self::get_device($device_id);
        $query = "select * from mobile_dollars where item_related>0 and deleted=0 and store_recharge=1 and operator_id=" . $device_info[0]["operator_id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getPackage($id)
    {
        $query = "select * from mobile_dollars where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_device($id)
    {
        $query = "select * from mobile_devices where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_devices_even_deleted()
    {
        $query = "select * from mobile_devices";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_mobile_stock_value_alfa()
    {
        $query = "select COALESCE(sum(balance), 0) as sum from mobile_devices where deleted=0 and operator_id=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_mobile_stock_value_mtc()
    {
        $query = "select COALESCE(sum(balance), 0) as sum from mobile_devices where deleted=0 and operator_id=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_internationnal_calls_balance()
    {
        $query = "select COALESCE(sum(value), 0) as sum from international_calls_balance where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getDevices($store_id)
    {
        $query = "select * from mobile_devices where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function creditsHistory($info)
    {
        $query = "insert into mobile_credits_history(invoice_item_id,device_id,qty,sms_fees,created_by,creation_date) values(" . $info["invoice_item_id"] . "," . $info["device_id"] . "," . $info["qty"] . "," . $info["sms_fees"] . "," . $_SESSION["id"] . ",'" . my_sql::datetime_now() . "')";
        my_sql::query($query);
    }
    public function getCreditHistoryByItemId($invoice_item_id)
    {
        $query = "select * from mobile_credits_history where invoice_item_id=" . $invoice_item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function credits_transfer_sms_cost($operator_id)
    {
        if ($operator_id == 2) {
            $query = "select * from settings where name='touch_sms_fees'";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            return $result[0]["value"];
        }
        $query = "select * from settings where name='alfa_sms_fees'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["value"];
    }
    public function reduceCredits($id, $package_id)
    {
        $pkg = self::getPackage($package_id);
        $pkg[0]["sms_cost"] = 0;
        if ($pkg[0]["no_sms_fees"] == 0) {
            $pkg[0]["sms_cost"] = self::credits_transfer_sms_cost($pkg[0]["operator_id"]);
        }
        if (floatval($pkg[0]["days"]) == 0) {
            my_sql::query("update mobile_devices set balance=balance-" . floatval($pkg[0]["sms_cost"]) . " where id=" . $id);
            my_sql::query("update mobile_devices set balance=balance-" . floatval($pkg[0]["qty"]) . " where id=" . $id);
        } else {
            my_sql::query("update mobile_devices set balance=balance+" . floatval($pkg[0]["return_credits"]) . " where id=" . $id);
        }
    }
}

?>