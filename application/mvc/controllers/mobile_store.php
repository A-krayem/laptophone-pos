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
class mobile_store extends Controller
{
    public $settings_info = NULL;
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function mobile_dollars_pkg()
    {
        self::giveAccessTo();
        $this->view("mobile_dollars_pkg");
    }
    public function international_calls()
    {
        self::giveAccessTo();
        $this->view("international_calls");
    }
    public function get_all_items_related_to_recharge($device_id)
    {
        $mobileStore = $this->model("mobileStore");
        $info = array();
        $items_to_recharge = $mobileStore->get_all_items_related_to_recharge($device_id);
        for ($i = 0; $i < count($items_to_recharge); $i++) {
            $info[$i]["id"] = $items_to_recharge[$i]["id"];
            $info[$i]["description"] = $items_to_recharge[$i]["description"];
        }
        echo json_encode($info);
    }
    public function execute_recharge($_device_id, $_package_id)
    {
        $mobileStore = $this->model("mobileStore");
        $mobileStore->execute_recharge($_device_id, $_package_id);
        echo json_encode(array());
    }
    public function cancel_recharge($recharge_id)
    {
        $mobileStore = $this->model("mobileStore");
        $mobileStore->cancel_recharge($recharge_id);
        echo json_encode(array());
    }
    public function get_credits_losts($date_range, $filter)
    {
        $mobileStore = $this->model("mobileStore");
        $date_filter = filter_var($date_range, self::conversion_php_version_filter());
        $date_range = array();
        if ($date_filter == "thismonth") {
            $date_range[0] = date("Y-m-d", strtotime(date("Y") . "-" . date("M") . "-01"));
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" - ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
        }
        $regular_filter = filter_var($filter, FILTER_SANITIZE_NUMBER_INT);
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $info = $mobileStore->get_credits_losts($date_range, $regular_filter);
        $data_array["data"] = array();
        $data_array["total_add_fees"] = 0;
        $data_array["total_fees"] = 0;
        $total_additionnal_fees = 0;
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            if (0 < $info[$i]["created_by"]) {
                array_push($tmp, $users_array[$info[$i]["created_by"]]["username"]);
            } else {
                array_push($tmp, "");
            }
            if (0 < $info[$i]["creation_date"]) {
                $dt = explode(" ", $info[$i]["creation_date"]);
                array_push($tmp, $dt[0]);
                array_push($tmp, $dt[1]);
            } else {
                array_push($tmp, "");
                array_push($tmp, "");
            }
            if (0 < $info[$i]["sms_fees"]) {
                if ($info[$i]["returned_fees"] == 1) {
                    array_push($tmp, number_format($info[$i]["sms_fees"], 2) . " <b style='color:#ed3939'>Returned</b>");
                } else {
                    $data_array["total_fees"] += $info[$i]["sms_fees"];
                    array_push($tmp, number_format($info[$i]["sms_fees"], 2));
                }
            } else {
                array_push($tmp, "");
            }
            if (0 < $info[$i]["returned_by"]) {
                array_push($tmp, $users_array[$info[$i]["returned_by"]]["username"]);
            } else {
                array_push($tmp, "");
            }
            if (0 < $info[$i]["returned_date"]) {
                $dt = explode(" ", $info[$i]["returned_date"]);
                array_push($tmp, $dt[0]);
                array_push($tmp, $dt[1]);
            } else {
                array_push($tmp, "");
                array_push($tmp, "");
            }
            if (0 < $info[$i]["additional_fees"]) {
                array_push($tmp, number_format($info[$i]["additional_fees"], 2));
                $data_array["total_add_fees"] += $info[$i]["additional_fees"];
            } else {
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_recharge_history($_device_id)
    {
        $mobileStore = $this->model("mobileStore");
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $info = $mobileStore->get_recharge_history($_device_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            $dt = explode(" ", $info[$i]["create_date"]);
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $dt[0]);
            array_push($tmp, $users_array[$info[$i]["operator_id"]]["username"]);
            $package_id = $mobileStore->getPackage($info[$i]["package_id"]);
            array_push($tmp, $package_id[0]["description"]);
            $fr_date = explode(" ", $info[$i]["from_date"]);
            $to_date = explode(" ", $info[$i]["to_date"]);
            array_push($tmp, $fr_date[0]);
            array_push($tmp, $to_date[0]);
            array_push($tmp, "");
            if ($info[$i]["cashbox_id"] == $_SESSION["cashbox_id"] && $info[$i]["operator_id"] == $_SESSION["id"]) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_devices($remind)
    {
        $mobileStore = $this->model("mobileStore");
        $devices = $mobileStore->getDevices(0);
        $operators = $mobileStore->getOperators();
        $operators_array = array();
        for ($i = 0; $i < count($operators); $i++) {
            $operators_array[$operators[$i]["id"]] = $operators[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($devices); $i++) {
            $tmp = array();
            array_push($tmp, $devices[$i]["id"]);
            array_push($tmp, $devices[$i]["description"] . " (<b>" . $operators_array[$devices[$i]["operator_id"]]["name"] . "</b>)");
            array_push($tmp, $devices[$i]["balance"]);
            $dt = explode(" ", $devices[$i]["expiry_date"]);
            array_push($tmp, $dt[0]);
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function international_calls_balance()
    {
        self::giveAccessTo();
        $this->view("international_calls_balance");
    }
    public function mobile_days_pkg()
    {
        self::giveAccessTo();
        $this->view("mobile_days_pkg");
    }
    public function mobile_sim_pkg()
    {
        self::giveAccessTo();
        $this->view("mobile_sim_pkg");
    }
    public function mobile_devices()
    {
        self::giveAccessTo();
        $this->view("mobile_devices");
    }
    public function delete_internationl_call($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $mobileStore->delete_internationl_call($id);
        echo json_encode(array());
    }
    public function add_international_call_balance()
    {
        self::giveAccessTo();
        $mobileStore = $this->model("mobileStore");
        $info = array();
        $info["int_balance"] = filter_input(INPUT_POST, "int_balance", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["int_description"] = filter_input(INPUT_POST, "desc_ic", self::conversion_php_version_filter());
        $info["int_balance_rate"] = filter_input(INPUT_POST, "int_balance_rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["current_balance"] = $this->settings_info["international_calls_balance"];
        $info["current_rate"] = $this->settings_info["international_calls_source_rate"];
        $id = $mobileStore->add_international_call_balance($info);
        $info_bal = array();
        $info_bal["international_calls_balance"] = $this->settings_info["international_calls_balance"];
        $info_bal["international_calls_source_rate"] = $this->settings_info["international_calls_source_rate"];
        $info_bal["international_calls_balance_current"] = $info["int_balance"];
        $info_bal["international_calls_source_rate_current"] = $info["int_balance_rate"];
        $mobileStore->international_calc($info_bal);
        echo json_encode(array($id));
    }
    public function add_international_call()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["country_id"] = filter_input(INPUT_POST, "country_id", FILTER_SANITIZE_NUMBER_INT);
        $info["country_rate"] = filter_input(INPUT_POST, "country_rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $mobileStore = $this->model("mobileStore");
        if ($info["id_to_edit"] == 0) {
            $id = $mobileStore->add_international_call($info);
            echo json_encode(array($id));
        } else {
            $mobileStore->update_international_call($info);
            echo json_encode(array($info["id_to_edit"]));
        }
    }
    public function get_international_call($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->get_international_call($id);
        echo json_encode($info);
    }
    public function get_all_international_calls_details()
    {
        self::giveAccessTo(array(2, 4));
        $mobileStore = $this->model("mobileStore");
        $countries = $this->model("countries");
        $info = $mobileStore->get_all_international_calls();
        $countries_data = $countries->getCountries();
        $countries_info = array();
        for ($i = 0; $i < count($countries_data); $i++) {
            $countries_info[$countries_data[$i]["id"]] = $countries_data[$i];
        }
        for ($i = 0; $i < count($info); $i++) {
            $info[$i]["country_txt"] = $countries_info[$info[$i]["country_id"]]["country_name"];
            $info[$i]["rate_format"] = number_format($info[$i]["rate"], 2);
        }
        echo json_encode($info);
    }
    public function delete_international_calls_balance($_id)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $mobileStore->delete_international_calls_balance($id);
        echo json_encode(array());
    }
    public function refresh_all_balance()
    {
        $info = array();
        $info["usd_balance"] = number_format($this->settings_info["international_calls_balance"], 2);
        $info["avg_rate"] = number_format($this->settings_info["international_calls_source_rate"], 2);
        $info["lbp_balance"] = number_format($this->settings_info["international_calls_balance"] * $this->settings_info["international_calls_source_rate"], 0);
        echo json_encode($info);
    }
    public function update_int_rate($p0)
    {
        $settings = $this->model("settings");
        $settings->update_value($p0, "international_calls_source_rate");
        echo json_encode(array());
    }
    public function get_int_rate()
    {
        $settings = $this->model("settings");
        $info = $settings->get_settings_by_name("international_calls_source_rate");
        echo json_encode(array($info[0]["value"]));
    }
    public function get_all_international_calls_balance()
    {
        self::giveAccessTo(array(2, 4));
        $data_array["data"] = array();
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->get_all_international_calls_balance();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $info[$i]["date"]);
            array_push($tmp, $info[$i]["description"]);
            array_push($tmp, number_format($info[$i]["value"], 2));
            array_push($tmp, number_format($info[$i]["rate"], 2));
            array_push($tmp, number_format($info[$i]["value"] * $info[$i]["rate"], 0));
            array_push($tmp, "");
            if ($i == count($info) - 1 && $info[$i]["value"] <= $this->settings_info["international_calls_balance"]) {
                array_push($tmp, "1");
            } else {
                array_push($tmp, "0");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_international_calls()
    {
        self::giveAccessTo(array(2, 4));
        $mobileStore = $this->model("mobileStore");
        $countries = $this->model("countries");
        $info = $mobileStore->get_all_international_calls();
        $countries_data = $countries->getCountries();
        $countries_info = array();
        for ($i = 0; $i < count($countries_data); $i++) {
            $countries_info[$countries_data[$i]["id"]] = $countries_data[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            if (isset($countries_info[$info[$i]["country_id"]])) {
                array_push($tmp, $countries_info[$info[$i]["country_id"]]["country_name"] . "(" . $countries_info[$info[$i]["country_id"]]["country_code"] . ")");
            } else {
                array_push($tmp, "Unknown");
            }
            $this->settings_info["default_currency_symbol"] = "";
            array_push($tmp, self::value_format_custom($info[$i]["rate"], $this->settings_info));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getDevicesIDs()
    {
        self::giveAccessTo(array(2, 4));
        $store_id = $_SESSION["store_id"];
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->getDevices($store_id);
        echo json_encode($info);
    }
    public function getDevices()
    {
        self::giveAccessTo(array(2, 4));
        $store_id = $_SESSION["store_id"];
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->getDevices($store_id);
        $operators_types = $mobileStore->getOperators();
        $types = array();
        for ($i = 0; $i < count($operators_types); $i++) {
            $types[$operators_types[$i]["id"]] = $operators_types[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_mobileDEV($info[$i]["id"]));
            array_push($tmp, $info[$i]["description"]);
            array_push($tmp, number_format($info[$i]["balance"], 2));
            array_push($tmp, $types[$info[$i]["operator_id"]]);
            $dt = explode(" ", $info[$i]["expiry_date"]);
            array_push($tmp, $dt[0]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getOperators()
    {
        self::giveAccessTo();
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->getOperators();
        echo json_encode($info);
    }
    public function getSIMPackages()
    {
        self::giveAccessTo();
        $mobileStore = $this->model("mobileStore");
        $mobileStore_data = $mobileStore->getSIMPackages();
        $operators_types = $mobileStore->getOperators();
        $types = array();
        for ($i = 0; $i < count($operators_types); $i++) {
            $types[$operators_types[$i]["id"]] = $operators_types[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($mobileStore_data); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_mobilePkg($mobileStore_data[$i]["id"]));
            array_push($tmp, $types[$mobileStore_data[$i]["operator_id"]]);
            array_push($tmp, $mobileStore_data[$i]["qty"]);
            array_push($tmp, $mobileStore_data[$i]["return_credits"]);
            array_push($tmp, number_format($mobileStore_data[$i]["price"], 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, $mobileStore_data[$i]["credit_cost"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getDaysPackages()
    {
        self::giveAccessTo();
        $mobileStore = $this->model("mobileStore");
        $items = $this->model("items");
        $mobileStore_data = $mobileStore->getDaysPackages();
        $operators_types = $mobileStore->getOperators();
        $types = array();
        for ($i = 0; $i < count($operators_types); $i++) {
            $types[$operators_types[$i]["id"]] = $operators_types[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($mobileStore_data); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_mobilePkg($mobileStore_data[$i]["id"]));
            array_push($tmp, $types[$mobileStore_data[$i]["operator_id"]]);
            array_push($tmp, $mobileStore_data[$i]["qty"]);
            array_push($tmp, $mobileStore_data[$i]["days"]);
            array_push($tmp, $mobileStore_data[$i]["return_credits"]);
            array_push($tmp, self::value_format_custom($mobileStore_data[$i]["price"], $this->settings_info));
            array_push($tmp, self::value_format_custom($mobileStore_data[$i]["credit_cost"], $this->settings_info));
            array_push($tmp, $mobileStore_data[$i]["description"]);
            if (0 < $mobileStore_data[$i]["item_related"]) {
                $item_info = $items->get_item($mobileStore_data[$i]["item_related"]);
                array_push($tmp, $item_info[0]["description"]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getPackages()
    {
        self::giveAccessTo();
        $mobileStore = $this->model("mobileStore");
        $mobileStore_data = $mobileStore->getPackages();
        $operators_types = $mobileStore->getOperators();
        $types = array();
        for ($i = 0; $i < count($operators_types); $i++) {
            $types[$operators_types[$i]["id"]] = $operators_types[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($mobileStore_data); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_mobilePkg($mobileStore_data[$i]["id"]));
            array_push($tmp, $types[$mobileStore_data[$i]["operator_id"]]);
            array_push($tmp, number_format($mobileStore_data[$i]["qty"], 2));
            array_push($tmp, self::value_format_custom($mobileStore_data[$i]["price"], $this->settings_info));
            array_push($tmp, $mobileStore_data[$i]["sms_cost"]);
            array_push($tmp, self::value_format_custom($mobileStore_data[$i]["credit_cost"], $this->settings_info));
            array_push($tmp, $mobileStore_data[$i]["description"]);
            array_push($tmp, $mobileStore_data[$i]["alias"]);
            if ($mobileStore_data[$i]["no_sms_fees"] == 1) {
                array_push($tmp, "No SMS Fees");
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function delete_pkg($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $mobileStore->delete_pkg($id);
        echo json_encode(array());
    }
    public function delete_dayspkg($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $mobileStore->delete_dayspkg($id);
        echo json_encode(array());
    }
    public function delete_device($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $mobileStore->delete_device($id);
        echo json_encode(array());
    }
    public function get_days_package($id_)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->getPackage($id);
        $item_info = $items->get_item($info[0]["item_related"]);
        if (0 < count($item_info)) {
            $info[0]["item_description"] = $item_info[0]["description"];
        }
        echo json_encode($info);
    }
    public function get_package($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->getPackage($id);
        echo json_encode($info);
    }
    public function get_device($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $info = $mobileStore->get_device($id);
        echo json_encode($info);
    }
    public function add_new_package()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["operator_id"] = filter_input(INPUT_POST, "operator_id", FILTER_SANITIZE_NUMBER_INT);
        $info["dollars_nb"] = filter_input(INPUT_POST, "dollars_nb", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["price"] = filter_input(INPUT_POST, "price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["sms_cost"] = filter_input(INPUT_POST, "sms_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["credits_cost"] = filter_input(INPUT_POST, "credits_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["description"] = filter_input(INPUT_POST, "description", self::conversion_php_version_filter());
        $info["note"] = filter_input(INPUT_POST, "note", self::conversion_php_version_filter());
        if (isset($_POST["no_sms_cost"])) {
            $info["no_sms_cost"] = 1;
        } else {
            $info["no_sms_cost"] = 0;
        }
        $mobileStore = $this->model("mobileStore");
        if ($info["id_to_edit"] == 0) {
            $mobileStore->addPackage($info);
        } else {
            $mobileStore->updatePackage($info);
        }
        $data = array();
        $data["id"] = $info["id_to_edit"];
        echo json_encode($data);
    }
    public function add_sim_package()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["operator_id"] = filter_input(INPUT_POST, "operator_id", FILTER_SANITIZE_NUMBER_INT);
        $info["dollars_nb"] = filter_input(INPUT_POST, "dollars_nb", FILTER_SANITIZE_NUMBER_INT);
        $info["price"] = filter_input(INPUT_POST, "price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["c_return"] = filter_input(INPUT_POST, "c_return", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["credits_cost"] = filter_input(INPUT_POST, "credits_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["sms_cost"] = 0;
        $info["type"] = 1;
        $mobileStore = $this->model("mobileStore");
        if ($info["id_to_edit"] == 0) {
            $mobileStore->addSimPackage($info);
        } else {
            $mobileStore->updateSimPackage($info);
        }
        $data = array();
        $data["id"] = $info["id_to_edit"];
        echo json_encode($data);
    }
    public function add_days_package()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["operator_id"] = filter_input(INPUT_POST, "operator_id", FILTER_SANITIZE_NUMBER_INT);
        $info["dollars_nb"] = filter_input(INPUT_POST, "dollars_nb", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["days_nb"] = filter_input(INPUT_POST, "days_nb", FILTER_SANITIZE_NUMBER_INT);
        $info["price"] = filter_input(INPUT_POST, "price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["c_return"] = filter_input(INPUT_POST, "c_return", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["credits_cost"] = filter_input(INPUT_POST, "credits_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["description"] = filter_input(INPUT_POST, "description", self::conversion_php_version_filter());
        $info["comp_item_id"] = filter_input(INPUT_POST, "comp_item_id", FILTER_SANITIZE_NUMBER_INT);
        $info["sms_cost"] = 0;
        $info["recharge_line"] = 0;
        if (isset($_POST["recharge_line"])) {
            $info["recharge_line"] = 1;
        }
        $mobileStore = $this->model("mobileStore");
        if ($info["id_to_edit"] == 0) {
            $mobileStore->addDaysPackage($info);
        } else {
            $mobileStore->updateDaysPackage($info);
        }
        $data = array();
        $data["id"] = $info["id_to_edit"];
        echo json_encode($data);
    }
    public function add_new_device($store_id_)
    {
        self::giveAccessTo();
        $info = array();
        $info["store_id"] = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["operator_id"] = filter_input(INPUT_POST, "operator_id", FILTER_SANITIZE_NUMBER_INT);
        $info["description"] = filter_input(INPUT_POST, "description", self::conversion_php_version_filter());
        $info["balance"] = filter_input(INPUT_POST, "balance", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["expiry_date"] = filter_input(INPUT_POST, "expiry_date", self::conversion_php_version_filter());
        $mobileStore = $this->model("mobileStore");
        if ($info["id_to_edit"] == 0) {
            $mobileStore->addDevice($info);
        } else {
            $mobileStore->updateDevice($info);
        }
        $data = array();
        $data["id"] = $info["id_to_edit"];
        echo json_encode($data);
    }
}

?>