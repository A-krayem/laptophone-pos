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
class customersModel
{
    public function getCustomers()
    {
        $query = "select * from customers where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllCustomersPaymentsDateRange___($daterange)
    {
        $query_8 = "select * from customer_balance where deleted=0 and date(balance_date)>='" . $daterange[0] . "' and date(balance_date)<='" . $daterange[1] . "'";
        $result_8 = my_sql::fetch_assoc(my_sql::query($query_8));
        return $result_8;
    }
    public function get_client_by_code($code)
    {
        $query = "select id as cid from customers where code='" . $code . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return $result[0];
        }
        return array();
    }
    public function is_connected_to_supplier($customer_id)
    {
        $query = "select connected_to_supplier from customers where id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["connected_to_supplier"];
    }
    public function getCustomersByIDSArray($ids)
    {
        if (0 < count($ids)) {
            $query = "select * from customers where id in (" . implode(",", $ids) . ") ";
            $result = my_sql::fetch_assoc(my_sql::query($query));
            return $result;
        }
        return array();
    }
    public function start_date_customer($customer_id)
    {
        $query = "select DATEDIFF('" . my_sql::datetime_now() . "', creation_date) as days from customers where id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["days"];
    }
    public function getCustomersByIDSArray_remote($ids, $cnx)
    {
        if (0 < count($ids)) {
            $query = "select * from customers where id in (" . implode(",", $ids) . ") ";
            $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
            return $result;
        }
        return array();
    }
    public function bal_need_update($customer_id)
    {
        $query = "update customers set omt_bal_need_update=1 where id=" . $customer_id;
        my_sql::query($query);
    }
    public function get_all_customers_acc()
    {
        $query = "select id,name,middle_name,last_name,phone from customers where deleted=0 and omt_account=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_customers_typeahead()
    {
        $query = "select id,CONCAT(name,' ',middle_name,' ',last_name,' ',phone) as name from customers where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_customers_typeahead_for_edit($id)
    {
        $query = "select CONCAT(name,' ',middle_name,' ',last_name,' ',phone) as name from customers where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_cashback_payment($id)
    {
        $query = "update cashback set deleted=1,deleted_by_user_id=" . $_SESSION["id"] . ",deleted_date='" . my_sql::datetime_now() . "' where id=" . $id;
        my_sql::query($query);
    }
    public function get_cashback_sum()
    {
        $query = "select customer_id,COALESCE(sum(cashback_value), 0) as sum from cashback where deleted=0 group by customer_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_cancelled_cashback_sum()
    {
        $query = "select customer_id,COALESCE(sum(cashback_value), 0) as sum from cashback where deleted=1 group by customer_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashback_customers()
    {
        $query = "select * from customers where deleted=0 and id in (select DISTINCT(invoice_customer_referrer) from invoices where deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashback_for_customers($customer_id)
    {
        $query = "select * from cashback where deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashback_customers_invoices_nb()
    {
        $query = "SELECT invoice_customer_referrer,count(id) as num,sum(total_value) as total_amount,sum(cashback_value) as cashback_value FROM invoices where invoice_customer_referrer>0 group by invoice_customer_referrer";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCollectedCustomers()
    {
        $query = "select * from collected_customers where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getNotSynced()
    {
        $query = "select * from customers where synced=0 limit 5";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function setSynced($id)
    {
        $query = "update customers set synced=1 where id=" . $id;
        my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "update " . DATABASE_SYNC . ".customers set synced=1 where id=" . $id;
            my_sql::query($query);
        }
    }
    public function get_customer_by_phone($phone)
    {
        $query = "select * from customers where phone='" . $phone . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getfirstname_for_type_head()
    {
        $query = "select id,name as name from customers group by name";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getmiddlename_for_type_head()
    {
        $query = "select id,middle_name as name from customers group by middle_name";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getlastname_for_type_head()
    {
        $query = "select id,last_name as name from customers group by last_name";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getRetailWholesaleCustomers($type)
    {
        $query = "select * from customers where deleted=0 and customer_type=" . $type;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersMinified()
    {
        if (OMT_VERSION == 1) {
            $query = "select id,company,name,middle_name,last_name,phone,address,discount,balance as 'customer_balance' from customers where deleted=0 and omt_account=1";
        } else {
            $query = "select id,company,name,middle_name,last_name,phone,address,discount,balance as 'customer_balance' from customers where deleted=0";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersnb()
    {
        $query = "select count(id) as num from customers where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersReatailsnb()
    {
        $query = "select count(id) as num from customers where deleted=0 and customer_type=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersEvenDeleted()
    {
        $query = "select * from customers";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function check_identity($id)
    {
        $query = "select * from customers where id_nb='" . $id . "' and id_nb!='0'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersRetail()
    {
        $query = "select * from customers where deleted=0 and customer_type=2";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomers_l()
    {
        $query = "select * from customers where deleted=0 and created_by=" . $_SESSION["id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersRetail_l()
    {
        $query = "select * from customers where deleted=0 and customer_type=2 and created_by=" . $_SESSION["id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersTypes()
    {
        $query = "select * from customers_types";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getEnabledCustomersTypes()
    {
        $query = "select * from customers_types where enabled=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersToPay()
    {
        $query = "select * from customers where deleted=0 order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersToPay_l()
    {
        $query = "select * from customers where deleted=0  and created_by=" . $_SESSION["id"] . " order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_customer($id)
    {
        $query = "update customers set deleted=1 where id=" . $id;
        my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "update " . DATABASE_SYNC . ".customers set deleted=1 where id=" . $id;
            my_sql::query($query);
        }
    }
    public function update_identity_1($id, $identity_1, $new)
    {
        $query = "update customers set identity_pic_1='" . $identity_1 . "' where id=" . $id;
        $old_data = self::getCustomersById($id);
        my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "update " . DATABASE_SYNC . ".customers set identity_pic_1='" . $identity_1 . "' where id=" . $id;
            my_sql::query($query);
        }
        if (0 < my_sql::get_mysqli_rows_num() && 0 < $new) {
            $description = "";
            $description .= self::prepare_log_field("Identity 1: ", $old_data[0]["identity_pic_1"], $identity_1);
            self::customers_logs("update", $description, $id);
        }
    }
    public function update_identity_2($id, $identity_2, $new)
    {
        $query = "update customers set identity_pic_2='" . $identity_2 . "' where id=" . $id;
        $old_data = self::getCustomersById($id);
        my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "update " . DATABASE_SYNC . ".customers set identity_pic_2='" . $identity_2 . "' where id=" . $id;
            my_sql::query($query);
        }
        if (0 < my_sql::get_mysqli_rows_num() && 0 < $new) {
            $description = "";
            $description .= self::prepare_log_field("Identity 2: ", $old_data[0]["identity_pic_2"], $identity_2);
            self::customers_logs("update", $description, $id);
        }
    }
    public function delete_customer_balance($id)
    {
        $query = "update customer_balance set deleted=1 where customer_id=" . $id;
        my_sql::query($query);
    }
    public function get_customer_payment($id)
    {
        $query = "select * from customer_balance  where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCustomersById($id)
    {
        $query = "select * from customers where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getIdentitiesTypes()
    {
        $query = "select * from identities_type where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getIdentitiesTypesEvenDeleted()
    {
        $query = "select * from identities_type";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_customer($info)
    {
        if ($info["id_expiry"] == 0) {
            $info["id_expiry"] = "NULL";
        } else {
            $info["id_expiry"] = "'" . $info["id_expiry"] . "'";
        }
        $query = "update customers set name='" . $info["name"] . "',company='" . $info["company"] . "',phone='" . $info["phone"] . "',address='" . $info["address"] . "',customer_type='" . $info["customer_type"] . "',starting_balance='" . $info["starting_balance"] . "',mof='" . $info["customer_mof"] . "',discount=" . $info["customer_discount"] . ",middle_name='" . $info["middle_name"] . "',last_name='" . $info["last_name"] . "',city_id=" . $info["city_id"] . ",dob='" . $info["dob"] . "',id_type=" . $info["id_type"] . ",id_expiry=" . $info["id_expiry"] . ",id_nb='" . $info["id_nb"] . "',cob='" . $info["cob"] . "',coi='" . $info["coi"] . "',account_nb='" . $info["account_nb"] . "',note='" . $info["note"] . "',reference_id='" . $info["reference_id"] . "',address_area='" . $info["address_area"] . "',address_city='" . $info["address_city"] . "',address_street='" . $info["address_street"] . "',address_floor='" . $info["address_floor"] . "',address_note='" . $info["address_note"] . "',address_building='" . $info["address_building"] . "',email='" . $info["email"] . "',connected_to_supplier='" . $info["connected_to_supplier"] . "',code='" . $info["cus_code"] . "',created_by=" . $info["vendor_id"] . " where id=" . $info["id_to_edit"];
        $old_data = self::getCustomersById($info["id_to_edit"]);
        my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "update " . DATABASE_SYNC . ".customers set name='" . $info["name"] . "',phone='" . $info["phone"] . "',address='" . $info["address"] . "',customer_type='" . $info["customer_type"] . "',starting_balance='" . $info["starting_balance"] . "',mof='" . $info["customer_mof"] . "',discount=" . $info["customer_discount"] . ",middle_name='" . $info["middle_name"] . "',last_name='" . $info["last_name"] . "',city_id=" . $info["city_id"] . ",dob='" . $info["dob"] . "',id_type=" . $info["id_type"] . ",id_expiry=" . $info["id_expiry"] . ",id_nb='" . $info["id_nb"] . "',cob='" . $info["cob"] . "',coi='" . $info["coi"] . "',account_nb='" . $info["account_nb"] . "',note='" . $info["note"] . "',reference_id='" . $info["reference_id"] . "',address_area='" . $info["address_area"] . "',address_city='" . $info["address_city"] . "',address_street='" . $info["address_street"] . "',address_floor='" . $info["address_floor"] . "',address_note='" . $info["address_note"] . "',address_building='" . $info["address_building"] . "',email='" . $info["email"] . "' where id=" . $info["id_to_edit"];
            my_sql::query($query);
        }
        if (0 < my_sql::get_mysqli_rows_num()) {
            $description = "";
            $description .= self::prepare_log_field("First name: ", $old_data[0]["name"], $info["name"]);
            $description .= self::prepare_log_field("Middle name: ", $old_data[0]["middle_name"], $info["middle_name"]);
            $description .= self::prepare_log_field("Last name: ", $old_data[0]["last_name"], $info["last_name"]);
            $description .= self::prepare_log_field("Phone: ", $old_data[0]["phone"], $info["phone"]);
            $description .= self::prepare_log_field("Address: ", $old_data[0]["address"], $info["address"]);
            $description .= self::prepare_log_field("Customer Type: ", $old_data[0]["customer_type"], $info["customer_type"]);
            $description .= self::prepare_log_field("Starting Balance: ", $old_data[0]["starting_balance"], $info["starting_balance"]);
            $description .= self::prepare_log_field("MOF: ", $old_data[0]["mof"], $info["customer_mof"]);
            $description .= self::prepare_log_field("Discount: ", (double) $old_data[0]["discount"], (double) $info["customer_discount"]);
            $description .= self::prepare_log_field("City ID: ", $old_data[0]["city_id"], $info["city_id"]);
            $description .= self::prepare_log_field("Dob: ", $old_data[0]["dob"], $info["dob"]);
            $description .= self::prepare_log_field("Identity Type: ", $old_data[0]["id_type"], $info["id_type"]);
            $description .= self::prepare_log_field("Identity Expiry: ", str_replace("'", "", $old_data[0]["id_expiry"]), str_replace("'", "", $info["id_expiry"]));
            $description .= self::prepare_log_field("Identity Number: ", $old_data[0]["id_nb"], $info["id_nb"]);
            $description .= self::prepare_log_field("Country of birth: ", $old_data[0]["cob"], $info["cob"]);
            $description .= self::prepare_log_field("Country Of issue: ", $old_data[0]["coi"], $info["coi"]);
            $description .= self::prepare_log_field("Vendor ID: ", $old_data[0]["created_by"], $info["created_by"]);
            self::customers_logs("update", $description, $info["id_to_edit"]);
        }
        $query = "update customers set synced=0 where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function prepare_log_field($pre_msg, $old, $new)
    {
        if ($old != $new) {
            return $pre_msg . " # " . $old . " # " . $new . " ## ";
        }
        return "";
    }
    public function customers_logs($action_type, $description, $customer_id)
    {
        $query = "insert into customers_logs(action_type,user_id,description,action_date,customer_id) values ('" . $action_type . "'," . $_SESSION["id"] . ",\"" . $description . "\",'" . my_sql::datetime_now() . "'," . $customer_id . ")";
        my_sql::query($query);
    }
    public function addCustomer($info)
    {
        if ($info["id_expiry"] == 0) {
            $info["id_expiry"] = "NULL";
        } else {
            $info["id_expiry"] = "'" . $info["id_expiry"] . "'";
        }
        $query = "insert into customers(name,phone,creation_date,address,customer_type,starting_balance,mof,discount,middle_name,last_name,city_id,dob,id_type,id_expiry,id_nb,cob,coi,created_by,account_nb,note,reference_id,address_area,address_city,address_street,address_floor,address_note,address_building,email,company,connected_to_supplier,code) values('" . $info["name"] . "','" . $info["phone"] . "','" . my_sql::datetime_now() . "','" . $info["address"] . "'," . $info["customer_type"] . "," . $info["starting_balance"] . ",'" . $info["customer_mof"] . "'," . $info["customer_discount"] . ",'" . $info["middle_name"] . "','" . $info["last_name"] . "'," . $info["city_id"] . ",'" . $info["dob"] . "'," . $info["id_type"] . "," . $info["id_expiry"] . ",'" . $info["id_nb"] . "'," . $info["cob"] . "," . $info["coi"] . "," . $_SESSION["id"] . ",'" . $info["account_nb"] . "','" . $info["note"] . "','" . $info["reference_id"] . "','" . $info["address_area"] . "','" . $info["address_city"] . "','" . $info["address_street"] . "','" . $info["address_floor"] . "','" . $info["address_note"] . "','" . $info["address_building"] . "','" . $info["email"] . "','" . $info["company"] . "','" . $info["connected_to_supplier"] . "','" . $info["cus_code"] . "')";
        my_sql::query($query);
        if (defined("ENABLE_SYNC_FOR_OMT") && ENABLE_SYNC_FOR_OMT == true) {
            $query = "insert into " . DATABASE_SYNC . ".customers(name,phone,creation_date,address,customer_type,starting_balance,mof,discount,middle_name,last_name,city_id,dob,id_type,id_expiry,id_nb,cob,coi,created_by,account_nb,note,reference_id,address_area,address_city,address_street,address_floor,address_note,address_building,email) values('" . $info["name"] . "','" . $info["phone"] . "','" . my_sql::datetime_now() . "','" . $info["address"] . "'," . $info["customer_type"] . "," . $info["starting_balance"] . ",'" . $info["customer_mof"] . "'," . $info["customer_discount"] . ",'" . $info["middle_name"] . "','" . $info["last_name"] . "'," . $info["city_id"] . ",'" . $info["dob"] . "'," . $info["id_type"] . "," . $info["id_expiry"] . ",'" . $info["id_nb"] . "'," . $info["cob"] . "," . $info["coi"] . "," . $_SESSION["id"] . ",'" . $info["account_nb"] . "','" . $info["note"] . "','" . $info["reference_id"] . "','" . $info["address_area"] . "','" . $info["address_city"] . "','" . $info["address_street"] . "','" . $info["address_floor"] . "','" . $info["address_note"] . "','" . $info["address_building"] . "','" . $info["email"] . "')";
            my_sql::query($query);
        }
        if (my_sql::get_mysqli_rows_num() == 0) {
        }
        $last_id = my_sql::get_mysqli_insert_id();
        if (0 < $last_id) {
            self::customers_logs("created", "", $last_id);
        }
        return $last_id;
    }
    public function getTotalPaymentBalanceByCashboxID($cashbox_id)
    {
        $query = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalPaymentBalanceByCashboxIDMethod($cashbox_id, $method)
    {
        $query = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and payment_method=" . $method . " and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalCustomersPaymentsByDateRange($daterange)
    {
        $query = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and date(balance_date)>='" . $daterange["start_date"] . "' and date(balance_date)<='" . $daterange["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalCustomersPaymentsByDateRange_remote($daterange, $cnx)
    {
        $query = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and date(balance_date)>='" . $daterange["start_date"] . "' and date(balance_date)<='" . $daterange["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function get_all_starting_balance()
    {
        $query = "select COALESCE(sum(starting_balance), 0) as sum from customers where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_invoice_of_customer($customer_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and (closed=0 or auto_closed=1) and deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_invoices_of_customer($customer_id)
    {
        $query = "select * from invoices where other_branche=0 and deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_invoices_of_customerDateRange($customer_id, $daterange)
    {
        $query = "select * from invoices where other_branche=0 and deleted=0 and customer_id=" . $customer_id . " and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_unclosed_invoice_of_customer($customer_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where other_branche=0 and (closed=1 && auto_closed=1) and deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_latest_unpaid_invoices_of_customer_without_first_unpaid($customer_id)
    {
        $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from (select * from invoices where other_branche=0 and (closed=0 and auto_closed=0) and deleted=0 and customer_id=" . $customer_id . " order by id asc limit 1,99999999999) AS total";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_credit_note_of_customer($customer_id)
    {
        $query = "select COALESCE(sum(credit_value*currency_rate), 0) as sum from credit_notes where deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_payment_of_customer($customer_id)
    {
        $query = "select COALESCE(sum(balance*rate), 0) as sum from customer_balance where deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_payment_of_customer_by_cashbox($cashbox_id)
    {
        $query = "select cb.cash_in_usd,cb.cash_in_lbp,cb.returned_usd,cb.returned_lbp,c.name from customer_balance cb left join customers c on c.id=cb.customer_id where cb.deleted=0 and cb.cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_payment_details_of_customer($customer_id)
    {
        $query = "select * from customer_balance where deleted=0 and customer_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_balance($customer_id)
    {
        $customer_info = self::getCustomersById($customer_id);
        $get_all_invoice_of_customer = self::get_all_unclosed_invoice_of_customer($customer_id);
        $get_all_payment_of_customer = self::get_all_payment_of_customer($customer_id);
        $get_all_credit_note_of_customer = self::get_all_credit_note_of_customer($customer_id);
        return $get_all_payment_of_customer[0]["sum"] - ($get_all_invoice_of_customer[0]["sum"] - $get_all_credit_note_of_customer[0]["sum"]) - $customer_info[0]["starting_balance"];
    }
    public function get_total_remain_of_customer($customer_id)
    {
        $get_all_invoice_of_customer = self::get_all_invoice_of_customer($customer_id);
        $get_all_credit_note_of_customer = self::get_all_credit_note_of_customer($customer_id);
        $get_all_payment_of_customer = self::get_all_payment_of_customer($customer_id);
        $get_latest_unpaid_invoices_of_customer_without_first_unpaid = self::get_latest_unpaid_invoices_of_customer_without_first_unpaid($customer_id);
        return $get_all_invoice_of_customer[0]["sum"] - $get_all_credit_note_of_customer[0]["sum"] - $get_all_payment_of_customer[0]["sum"] - $get_latest_unpaid_invoices_of_customer_without_first_unpaid[0]["sum"];
    }
    public function search($search, $page, $perPage, $checkHasMore = false)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $query_settings = "select * from settings where name='quotation_show_clients_only_by_users_created'";
        $result_settings = my_sql::fetch_assoc(my_sql::query($query_settings));
        $customers_limited = false;
        if (0 < count($result_settings) && $result_settings[0]["value"] == "1") {
            $customers_limited = true;
        }
        if (!$customers_limited || $_SESSION["role"] == 1) {
            $query = "SELECT " . $select . " FROM customers where deleted=0 and (concat(name,\" \",middle_name,\" \",last_name) like \"%" . $search . "%\" or phone like \"%" . $search . "%\")   " . $limiter;
        } else {
            $query = "SELECT " . $select . " FROM customers where deleted=0 and created_by=" . $_SESSION["id"] . " and (concat(name,\" \",middle_name,\" \",last_name) like \"%" . $search . "%\" or phone like \"%" . $search . "%\" )   " . $limiter;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
    public function search_centralize($search, $page, $perPage, $checkHasMore = false)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $query_settings = "select * from settings where name='quotation_show_clients_only_by_users_created'";
        $result_settings = my_sql::fetch_assoc(my_sql::query($query_settings));
        $customers_limited = false;
        if (0 < count($result_settings) && $result_settings[0]["value"] == "1") {
            $customers_limited = true;
        }
        if (!$customers_limited || $_SESSION["role"] == 1) {
            $query = "SELECT " . $select . " FROM customers where deleted=0 and  (concat(name,\" \",middle_name,\" \",last_name) like \"%" . $search . "%\" or phone like \"%" . $search . "%\")   " . $limiter;
        } else {
            $query = "SELECT " . $select . " FROM customers where deleted=0 and  created_by=" . $_SESSION["id"] . " and (concat(name,\" \",middle_name,\" \",last_name) like \"%" . $search . "%\" or phone like \"%" . $search . "%\" )   " . $limiter;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
    public function save_customer_info($info)
    {
        $query = "insert into customers(" . implode(",", $info["col"]) . ",creation_date) values('" . implode("','", $info["val"]) . "','" . my_sql::datetime_now() . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
    public function update_client_info($info)
    {
        $query = "update  customers set  phone='" . $info["phone"] . "',name='" . $info["name"] . "',middle_name='" . $info["middle_name"] . "' ,last_name='" . $info["last_name"] . "',pd='" . $info["pd"] . "',note='" . $info["note"] . "',address='" . $info["address"] . "',doctor='" . $info["doctor"] . "' where id=" . $info["id"];
        $result = my_sql::query($query);
        if ($result) {
            return 1;
        }
        return 0;
    }
}

?>