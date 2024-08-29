<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 5.6
 * @ Decoder version: 1.0.4
 * @ Release: 02/06/2020
 *
 * @ ZendGuard Decoder PHP 5.6
 */

// Decoded file for php version 53.
class cashinout extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function get_full_report($cashbox_id)
    {
        echo json_encode(array());
    }
    public function update_cashinout()
    {
        if ($this->settings_info["pos_disable_edit_payment"] == 1) {
            exit;
        }
        $cashinout = $this->model("cashinout");
        $payment_type = filter_input(INPUT_POST, "payment_type", FILTER_SANITIZE_NUMBER_INT);
        $transaction_id = filter_input(INPUT_POST, "transaction_id", FILTER_SANITIZE_NUMBER_INT);
        $cash_usd = filter_input(INPUT_POST, "cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $cash_lbp = filter_input(INPUT_POST, "cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $returned_cash_usd = filter_input(INPUT_POST, "r_cash_usd_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $returned_cash_lbp = filter_input(INPUT_POST, "r_cash_lbp_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $r_cash_usd = filter_input(INPUT_POST, "must_return_cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $r_cash_lbp = filter_input(INPUT_POST, "must_return_cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $old_cash_details = $cashinout->get_cash_details_by_id($transaction_id, $payment_type);
        $update = array();
        $update["id"] = $transaction_id;
        $update["payment_type"] = $payment_type;
        $update["cash_usd"] = $cash_usd;
        $update["cash_lbp"] = $cash_lbp;
        $update["returned_cash_usd"] = $returned_cash_usd;
        $update["returned_cash_lbp"] = $returned_cash_lbp;
        $update["must_return_cash_usd"] = $r_cash_usd;
        $update["must_return_cash_lbp"] = $r_cash_lbp;
        if (!isset($update["cash_usd"]) || $update["cash_usd"] == "" || $update["cash_usd"] == NULL) {
            $update["cash_usd"] = 0;
        }
        if (!isset($update["cash_lbp"]) || $update["cash_lbp"] == "" || $update["cash_lbp"] == NULL) {
            $update["cash_lbp"] = 0;
        }
        if (!isset($update["returned_cash_usd"]) || $update["returned_cash_usd"] == "" || $update["returned_cash_usd"] == NULL) {
            $update["returned_cash_usd"] = 0;
        }
        if (!isset($update["returned_cash_lbp"]) || $update["returned_cash_lbp"] == "" || $update["returned_cash_lbp"] == NULL) {
            $update["returned_cash_lbp"] = 0;
        }
        if (!isset($update["must_return_cash_lbp"]) || $update["must_return_cash_lbp"] == "" || $update["must_return_cash_lbp"] == NULL) {
            $update["must_return_cash_lbp"] = 0;
        }
        if (!isset($update["must_return_cash_usd"]) || $update["must_return_cash_usd"] == "" || $update["must_return_cash_usd"] == NULL) {
            $update["must_return_cash_usd"] = 0;
        }
        $cashinout->update_cash_details($update);
        $log = array();
        if ($payment_type == 1) {
            $log["cash_usd"] = $old_cash_details[0]["cash_usd"];
            $log["cash_lbp"] = $old_cash_details[0]["cash_lbp"];
            $log["returned_cash_usd"] = $old_cash_details[0]["returned_cash_usd"];
            $log["returned_cash_lbp"] = $old_cash_details[0]["returned_cash_lbp"];
            $log["must_return_cash_usd"] = $old_cash_details[0]["must_return_cash_usd"];
            $log["must_return_cash_lbp"] = $old_cash_details[0]["must_return_cash_lbp"];
        }
        if ($payment_type == 2) {
            $log["cash_usd"] = $old_cash_details[0]["cash_usd_in"];
            $log["cash_lbp"] = $old_cash_details[0]["cash_lbp_in"];
            $log["returned_cash_usd"] = $old_cash_details[0]["returned_cash_usd"];
            $log["returned_cash_lbp"] = $old_cash_details[0]["returned_cash_lbp"];
            $log["must_return_cash_usd"] = $old_cash_details[0]["cash_usd_to_return"];
            $log["must_return_cash_lbp"] = $old_cash_details[0]["cash_lbp_to_return"];
        }
        $log["cashbox_id"] = $_SESSION["cashbox_id"];
        $log["rate"] = $old_cash_details[0]["rate"];
        $log["transaction_id"] = $old_cash_details[0]["invoice_id"];
        $log["transaction_type"] = $payment_type;
        $log["cash_details_id"] = $transaction_id;
        $cashinout->cash_details_log($log);
        echo json_encode(array());
    }
    public function get_full_report_table($return_type, $cashbox_id)
    {
        self::_get_full_report_table($return_type, $cashbox_id);
    }
    public function get_cashinout_by_id($_payment_type, $_transaction_id)
    {
        $payment_type = filter_var($_payment_type, FILTER_SANITIZE_NUMBER_INT);
        $transaction_id = filter_var($_transaction_id, FILTER_SANITIZE_NUMBER_INT);
        $cashinout = $this->model("cashinout");
        $info = $cashinout->get_cashinout_by_id($payment_type, $transaction_id);
        if ($payment_type == 2) {
            if ($info[0]["added_value"] <= $info[0]["return_value"]) {
                $info[0]["base_usd_amount"] = 0 - ($info[0]["return_value"] - $info[0]["added_value"]);
            } else {
                $info[0]["base_usd_amount"] = $info[0]["added_value"] - $info[0]["return_value"];
            }
        }
        echo json_encode($info);
    }
    public function show_cashin_out_report()
    {
        self::giveAccessTo();
        $cashinout = $this->model("cashinout");
        echo json_encode(array());
    }
    // wish cashbox transaction
   function add_new_wish_transaction($transaction_type, $transaction_to, $amount_usd, $amount_lbp, $transaction_note)
    {
        $cbox_type = 0;
        if($transaction_type == 1){
            $cbox_type = 2;
        }elseif ($transaction_type == 2){
            $cbox_type = 1;
        }
        if($cbox_type == 1 || $cbox_type == 2){
            $transactions = $this->model("transactions");
            $info = array();
            $info["amount_usd"] = $amount_usd;
            $info["amount_lbp"] = $amount_lbp;
            $info["transaction_type"] = $cbox_type;
            $info["transaction_to_cashbox_id"] = $transaction_to;
            $info["transaction_note"] = 'WISH';
            $info["created_by"] = $_SESSION["id"];
            $info["current_cashbox_id"] = $_SESSION["cashbox_id"];
            if (!isset($info["transaction_to_cashbox_id"])) {
                $info["transaction_to_cashbox_id"] = 0;
            }
            if (!isset($info["amount_usd"]) || $info["amount_usd"] == "") {
                $info["amount_usd"] = 0;
            }
            if (!isset($info["amount_lbp"]) || $info["amount_lbp"] == "") {
                $info["amount_lbp"] = 0;
            }
            $transactions->add_new_transaction($info);
        }
    }
    public function add_new_cashinout()
    {
        $cashinout = $this->model("cashinout");
        $cashbox = $this->model("cashbox");
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["cash_in_out"] = filter_input(INPUT_POST, "cashtype", FILTER_SANITIZE_NUMBER_INT);
        $info["type_id"] = filter_input(INPUT_POST, "operationtype", FILTER_SANITIZE_NUMBER_INT);
        $info["currency_id"] = filter_input(INPUT_POST, "cash_currency", FILTER_SANITIZE_NUMBER_INT);
        $info["currency_rate"] = filter_input(INPUT_POST, "rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["cash_value"] = filter_input(INPUT_POST, "value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["op_ref"] = filter_input(INPUT_POST, "op_ref", self::conversion_php_version_filter());
        $info["amount_lbp"] = filter_input(INPUT_POST, "value_lbp_clean", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["amount_usd"] = filter_input(INPUT_POST, "value_usd_clean", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["note"] = "";
        $info["user_id"] = $_SESSION["id"];
        $info["cashbox_id"] = $_SESSION["cashbox_id"];
        $cashinout->add($info);
        $this->add_new_wish_transaction($info["cash_in_out"], 0, $info["amount_usd"], $info["amount_lbp"], $info["op_ref"]);
        if ($this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $currency = "";
            $dec = 0;
            if ($info["currency_id"] == 1) {
                $currency = "USD";
                $dec = 2;
            }
            if ($info["currency_id"] == 2) {
                $currency = "LBP";
            }
            $operation_type_info = $cashinout->getOperationTypeById($info["type_id"]);
            $operation_type = $operation_type_info[0]["name"];
            $cashtype = "IN";
            if ($info["cash_in_out"] == 2) {
                $cashtype = "OUT";
            }
            $info_tel = array();
            $info_tel["message"] = "<strong>Money operations:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Operation Type:</strong> " . $operation_type . " \n";
            $info_tel["message"] .= "<strong>Operation Reference:</strong> " . $info["op_ref"] . " \n";
            $info_tel["message"] .= "<strong>Cash Type:</strong> " . $cashtype . " \n";
            $info_tel["message"] .= "<strong>Base Amount:</strong> " . number_format($info["cash_value"], $dec) . " \n";
            $info_tel["message"] .= "<strong>Currency:</strong> " . $currency . " \n";
            $info_tel["message"] .= "<strong>Cash " . $cashtype . " LBP:</strong> " . number_format($info["amount_lbp"], 0) . " \n";
            $info_tel["message"] .= "<strong>Cash " . $cashtype . " USD:</strong> " . number_format($info["amount_usd"], 2) . " \n";
            $info_tel["message"] .= "<strong>\nSummary</strong>\n";
            $cash_info = self::update_cashin_out_info(0, 0);
            $info_tel["message"] .= "<strong>Starting Amount USD:</strong> " . number_format($cash_info["starting_usd_amount"], 2) . " \n";
            $info_tel["message"] .= "<strong>Starting Amount LBP:</strong> " . number_format($cash_info["starting_lbp_amount"], 0) . " \n";
            $info_tel["message"] .= "<strong>Total Cash In USD:</strong> " . $cash_info["total_cash_in_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Cash In LBP:</strong> " . $cash_info["total_cash_in_lbp"] . " \n";
            $info_tel["message"] .= "<strong>Total Cash OUT USD:</strong> " . $cash_info["total_cash_out_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Cash OUT LBP:</strong> " . $cash_info["total_cash_out_lbp"] . " \n";
            $info_tel["message"] .= "<strong>Total USD:</strong> " . $cash_info["ending_usd_amount"] . " \n";
            $info_tel["message"] .= "<strong>Total LBP:</strong> " . $cash_info["ending_lbp_amount"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount IN USD:</strong> " . $cash_info["total_amount_in_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount IN LBP:</strong> " . $cash_info["total_amount_in_lbp"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount OUT USD:</strong> " . $cash_info["total_amount_out_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount OUT LBP:</strong> " . $cash_info["total_amount_out_lbp"] . " \n";
            self::send_to_telegram($info_tel, 1);
        }
        $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        echo json_encode(array());
    }

    public function get_all_cashinout($_p0, $_p1, $_p2)
    {
        self::giveAccessTo(array(2, 4));
        $data_array["data"] = array();
        $cashinout = $this->model("cashinout");
        $currency = $this->model("currency");
        $user = $this->model("user");
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($_p0 == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $_p0);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $all_currencies = $currency->getAllEnabledCurrencies();
        $all_currencies_array = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $all_currencies_array[$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $user_info = $user->getAllUsersEvenDeleted();
        $user_info_array = array();
        for ($i = 0; $i < count($user_info); $i++) {
            $user_info_array[$user_info[$i]["id"]] = $user_info[$i];
        }
        $types = $cashinout->getAllTypesEvenDeleted();
        $cashinout_types = array();
        for ($i = 0; $i < count($types); $i++) {
            $cashinout_types[$types[$i]["id"]] = $types[$i];
        }
        $cashinout_list = $cashinout->get_all_cashinout($date_range);
        for ($i = 0; $i < count($cashinout_list); $i++) {
            $tmp = array();
            array_push($tmp, $cashinout_list[$i]["id"]);
            array_push($tmp, $cashinout_types[$cashinout_list[$i]["type_id"]]["name"]);
            array_push($tmp, $cashinout_list[$i]["operation_reference"]);
            if ($cashinout_list[$i]["cash_in_out"] == 1) {
                array_push($tmp, "<b class='in'>IN</b>");
            } else {
                array_push($tmp, "<b class='out'>OUT</b>");
            }
            array_push($tmp, $all_currencies_array[$cashinout_list[$i]["currency_id"]]["symbole"]);
            array_push($tmp, number_format($cashinout_list[$i]["currency_rate"], 2));
            array_push($tmp, $user_info_array[$cashinout_list[$i]["user_id"]]["username"]);
            array_push($tmp, $cashinout_list[$i]["creation_date"]);
            array_push($tmp, $cashinout_list[$i]["note"]);
            array_push($tmp, self::value_format_custom_no_currency_no_round(floatval($cashinout_list[$i]["cash_value"]), $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency_no_round(floatval($cashinout_list[$i]["amount_lbp"]), $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency_no_round(floatval($cashinout_list[$i]["amount_usd"]), $this->settings_info));
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function update_cashin_out_info_report($_p0, $_operationtype, $_cashtype, $shift_id)
    {
        self::giveAccessTo();
        $cashinout = $this->model("cashinout");
        $operationtype = filter_var($_operationtype, FILTER_SANITIZE_NUMBER_INT);
        $cashtype = filter_var($_cashtype, FILTER_SANITIZE_NUMBER_INT);
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($_p0 == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $_p0);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $cashinout_info = array();
        $result_usd = $cashinout->get_all_cash_in_out_report_sum($date_range, 1, $operationtype, $shift_id);
        $result_lbp = $cashinout->get_all_cash_in_out_report_sum($date_range, 2, $operationtype, $shift_id);
        $result_usd_amount = $cashinout->get_all_cash_in_out_report_sum_amount($date_range, 1, $operationtype, $shift_id);
        $result_lbp_amount = $cashinout->get_all_cash_in_out_report_sum_amount($date_range, 2, $operationtype, $shift_id);
        $cashinout_info["total_in_usd"] = self::value_format_custom_no_currency_no_round(floatval($result_usd["total_in"]), $this->settings_info);
        $cashinout_info["total_out_usd"] = self::value_format_custom_no_currency_no_round(floatval($result_usd["total_out"]), $this->settings_info);
        $cashinout_info["total_in_lbp"] = self::value_format_custom_no_currency_no_round(floatval($result_lbp["total_in"]), $this->settings_info);
        $cashinout_info["total_out_lbp"] = self::value_format_custom_no_currency_no_round(floatval($result_lbp["total_out"]), $this->settings_info);
        $cashinout_info["total_usd"] = self::value_format_custom_no_currency_no_round(floatval($result_usd["total_in"] - $result_usd["total_out"]), $this->settings_info);
        $cashinout_info["total_lbp"] = self::value_format_custom_no_currency_no_round(floatval($result_lbp["total_in"] - $result_lbp["total_out"]), $this->settings_info);
        $cashinout_info["total_in_usd_amount"] = self::value_format_custom_no_currency_no_round(floatval($result_usd_amount["total_in_amount"]), $this->settings_info);
        $cashinout_info["total_out_usd_amount"] = self::value_format_custom_no_currency_no_round(floatval($result_usd_amount["total_out_amount"]), $this->settings_info);
        $cashinout_info["total_in_lbp_amount"] = self::value_format_custom_no_currency_no_round(floatval($result_lbp_amount["total_in_amount"]), $this->settings_info);
        $cashinout_info["total_out_lbp_amount"] = self::value_format_custom_no_currency_no_round(floatval($result_lbp_amount["total_out_amount"]), $this->settings_info);
        $cashinout_info["total_usd_amount"] = self::value_format_custom_no_currency_no_round(floatval($result_usd_amount["total_in_amount"] - $result_usd_amount["total_out_amount"]), $this->settings_info);
        $cashinout_info["total_lbp_amount"] = self::value_format_custom_no_currency_no_round(floatval($result_lbp_amount["total_in_amount"] - $result_lbp_amount["total_out_amount"]), $this->settings_info);
        $cashinout_info["starting_lbp_amount"] = number_format($cashinout->starting(2, $shift_id), 0);
        $cashinout_info["starting_usd_amount"] = number_format($cashinout->starting(1, $shift_id), 2);
        echo json_encode($cashinout_info);
    }
    public function get_all_cashinout_report($_p0, $_operationtype, $_cashtype, $shift_id)
    {
        self::giveAccessTo();
        $data_array["data"] = array();
        $cashinout = $this->model("cashinout");
        $currency = $this->model("currency");
        $user = $this->model("user");
        $operationtype = filter_var($_operationtype, FILTER_SANITIZE_NUMBER_INT);
        $cashtype = filter_var($_cashtype, FILTER_SANITIZE_NUMBER_INT);
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($_p0 == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $_p0);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $all_currencies = $currency->getAllEnabledCurrencies();
        $all_currencies_array = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $all_currencies_array[$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $user_info = $user->getAllUsersEvenDeleted();
        $user_info_array = array();
        for ($i = 0; $i < count($user_info); $i++) {
            $user_info_array[$user_info[$i]["id"]] = $user_info[$i];
        }
        $types = $cashinout->getAllTypesEvenDeleted();
        $cashinout_types = array();
        for ($i = 0; $i < count($types); $i++) {
            $cashinout_types[$types[$i]["id"]] = $types[$i];
        }
        $cashinout_list = $cashinout->get_all_cashinout_report_shift($date_range, $operationtype, $cashtype, $shift_id);
        for ($i = 0; $i < count($cashinout_list); $i++) {
            $tmp = array();
            array_push($tmp, $cashinout_list[$i]["id"]);
            array_push($tmp, $cashinout_types[$cashinout_list[$i]["type_id"]]["name"]);
            array_push($tmp, $cashinout_list[$i]["operation_reference"]);
            if ($cashinout_list[$i]["cash_in_out"] == 1) {
                array_push($tmp, "<b class='in'>IN</b>");
            } else {
                array_push($tmp, "<b class='out'>OUT</b>");
            }
            array_push($tmp, $all_currencies_array[$cashinout_list[$i]["currency_id"]]["name"] . " " . $all_currencies_array[$cashinout_list[$i]["currency_id"]]["symbole"]);
            array_push($tmp, number_format($cashinout_list[$i]["currency_rate"], 2));
            array_push($tmp, $user_info_array[$cashinout_list[$i]["user_id"]]["username"]);
            array_push($tmp, $cashinout_list[$i]["creation_date"]);
            array_push($tmp, $cashinout_list[$i]["note"]);
            array_push($tmp, self::value_format_custom_no_currency_no_round(floatval($cashinout_list[$i]["cash_value"]), $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($cashinout_list[$i]["amount_lbp"], $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($cashinout_list[$i]["amount_usd"], $this->settings_info));
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function delete_cashinout($_id)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, self::conversion_php_version_filter());
        $cashinout = $this->model("cashinout");
        $cashinout->delete($id);
        if ($this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $info_tel = array();
            $info_tel["message"] = "<strong>Money operations:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Operation Type:</strong> Deleted \n";
            $info_tel["message"] .= "<strong>Operation ID:</strong> " . $id . " \n";
            $info_tel["message"] .= "<strong>\nSummary</strong>\n";
            $cash_info = self::update_cashin_out_info(0, 0);
            $info_tel["message"] .= "<strong>Starting Amount USD:</strong> " . number_format($cash_info["starting_usd_amount"], 2) . " \n";
            $info_tel["message"] .= "<strong>Starting Amount LBP:</strong> " . number_format($cash_info["starting_lbp_amount"], 0) . " \n";
            $info_tel["message"] .= "<strong>Total Cash In USD:</strong> " . $cash_info["total_cash_in_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Cash In LBP:</strong> " . $cash_info["total_cash_in_lbp"] . " \n";
            $info_tel["message"] .= "<strong>Total Cash OUT USD:</strong> " . $cash_info["total_cash_out_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Cash OUT LBP:</strong> " . $cash_info["total_cash_out_lbp"] . " \n";
            $info_tel["message"] .= "<strong>Total USD:</strong> " . $cash_info["ending_usd_amount"] . " \n";
            $info_tel["message"] .= "<strong>Total LBP:</strong> " . $cash_info["ending_lbp_amount"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount IN USD:</strong> " . $cash_info["total_amount_in_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount IN LBP:</strong> " . $cash_info["total_amount_in_lbp"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount OUT USD:</strong> " . $cash_info["total_amount_out_usd"] . " \n";
            $info_tel["message"] .= "<strong>Total Amount OUT LBP:</strong> " . $cash_info["total_amount_out_lbp"] . " \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array());
    }
    public function update_cashin_out_info($_operations_type, $echo)
    {
        self::giveAccessTo(array(2, 4));
        $operations_type = filter_var($_operations_type, self::conversion_php_version_filter());
        $cashinout = $this->model("cashinout");
        $service_in_lbp = $cashinout->get_info_services(1, 2, $operations_type);
        $service_in_usd = $cashinout->get_info_services(1, 1, $operations_type);
        $service_out_lbp = $cashinout->get_info_services(2, 2, $operations_type);
        $service_out_usd = $cashinout->get_info_services(2, 1, $operations_type);
        $transfer_in_lbp = $cashinout->get_info_transfer(1, 2, $operations_type);
        $transfer_in_usd = $cashinout->get_info_transfer(1, 1, $operations_type);
        $transfer_out_lbp = $cashinout->get_info_transfer(2, 2, $operations_type);
        $transfer_out_usd = $cashinout->get_info_transfer(2, 1, $operations_type);
        $total_cash_USD_in = $cashinout->get_total_cash_USD(1, $operations_type);
        $total_cash_USD_out = $cashinout->get_total_cash_USD(2, $operations_type);
        $total_cash_LBP_in = $cashinout->get_total_cash_LBP(1, $operations_type);
        $total_cash_LBP_out = $cashinout->get_total_cash_LBP(2, $operations_type);
        $starting = $cashinout->get_starting($_SESSION["cashbox_id"]);
        $info = array();
        $info["starting_usd_amount"] = $starting[0]["usd_amount"];
        $info["starting_lbp_amount"] = $starting[0]["lbp_amount"];
        $info["transfer_in_lbp"] = number_format($transfer_in_lbp, 0);
        $info["transfer_in_usd"] = number_format($transfer_in_usd, 0);
        $info["transfer_out_lbp"] = number_format($transfer_out_lbp, 0);
        $info["transfer_out_usd"] = number_format($transfer_out_usd, 0);
        $info["transfer_balance_lbp"] = number_format($transfer_in_lbp - $transfer_out_lbp, 0);
        $info["transfer_balance_usd"] = number_format($transfer_in_usd - $transfer_out_usd, 0);
        $info["service_in_lbp"] = number_format($service_in_lbp, 0);
        $info["service_in_usd"] = number_format($service_in_usd, 0);
        $info["service_out_lbp"] = number_format($service_out_lbp, 0);
        $info["service_out_usd"] = number_format($service_out_usd, 0);
        $info["service_balance_lbp"] = number_format($service_in_lbp - $service_out_lbp, 0);
        $info["service_balance_usd"] = number_format($service_in_usd - $service_out_usd, 0);
        $info["total_amount_in_lbp"] = self::value_format_custom_no_currency_no_round(floatval($transfer_in_lbp + $service_in_lbp), $this->settings_info);
        $info["total_amount_in_usd"] = self::value_format_custom_no_currency_no_round(floatval($transfer_in_usd + $service_in_usd), $this->settings_info);
        $info["total_amount_out_lbp"] = self::value_format_custom_no_currency_no_round(floatval($transfer_out_lbp + $service_out_lbp), $this->settings_info);
        $info["total_amount_out_usd"] = self::value_format_custom_no_currency_no_round(floatval($transfer_out_usd + $service_out_usd), $this->settings_info);
        $info["total_cash_in_usd"] = number_format($total_cash_USD_in, 0);
        $info["total_cash_out_usd"] = number_format($total_cash_USD_out, 0);
        $info["total_cash_in_lbp"] = number_format($total_cash_LBP_in, 0);
        $info["total_cash_out_lbp"] = number_format($total_cash_LBP_out, 0);
        $info["ending_usd_amount"] = number_format($info["starting_usd_amount"] + $total_cash_USD_in - $total_cash_USD_out, 0);
        $info["ending_lbp_amount"] = number_format($info["starting_lbp_amount"] + $total_cash_LBP_in - $total_cash_LBP_out, 0);
        if ($echo == 1) {
            echo json_encode($info);
        } else {
            return $info;
        }
    }
    public function update_starting_usd($value)
    {
        $cashinout = $this->model("cashinout");
        $cashinout->update_starting_usd($value, $_SESSION["cashbox_id"]);
        if ($this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $info_tel = array();
            $info_tel["message"] = "<strong>Money Operations</strong> \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Operator:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Starting balance USD changed to:</strong> " . number_format($value, 2) . " USD \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array());
    }
    public function update_starting_lbp($value)
    {
        $cashinout = $this->model("cashinout");
        $cashinout->update_starting_lbp($value, $_SESSION["cashbox_id"]);
        if ($this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $info_tel = array();
            $info_tel["message"] = "<strong>Money Operations</strong> \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Operator:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Starting balance LBP changed to:</strong> " . number_format($value, 0) . " LBP \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array());
    }
}

?>