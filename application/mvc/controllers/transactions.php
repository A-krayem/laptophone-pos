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
class transactions extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function get_all_transactions($_date_range, $_deleted)
    {
        $transactions = $this->model("transactions");
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $date_filter = filter_var($_date_range, self::conversion_php_version_filter());
        $date_range = array();
        if ($date_filter == "thismonth") {
            $date_range[0] = date("Y-m-d", strtotime(date("Y") . "-" . date("M") . "-01"));
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" - ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
        }
        $filter = array();
        list($filter["start_date"], $filter["end_date"]) = $date_range;
        if (in_array($_deleted, array(0, 1, 2))) {
            $filter["deleted"] = $_deleted;
        } else {
            $filter["deleted"] = 0;
        }

        $transactions_records = $transactions->get_all_transactions_filters($filter);
        //print_r($transactions_records);
        $data_array["data"] = array();
        for ($i = 0; $i < count($transactions_records); $i++) {
            $tmp = array();
            array_push($tmp, $transactions_records[$i]["id"]);
            array_push($tmp, $transactions_records[$i]["creation_date"]);
            array_push($tmp, $users_array[$transactions_records[$i]["created_by"]]["username"]);
            if ($transactions_records[$i]["transaction_type"] == 1) {
                array_push($tmp, "<b class='text-success'>CASH IN</b>");
            } else {
                if ($transactions_records[$i]["transaction_type"] == 2) {
                    array_push($tmp, "<b class='text-danger'>CASH OUT</b>");
                } else {
                    array_push($tmp, "TRANSFER");
                }
            }
            array_push($tmp, number_format($transactions_records[$i]["amount_usd"], 0));
            array_push($tmp, number_format($transactions_records[$i]["amount_lbp"], 0));
            array_push($tmp, number_format($transactions_records[$i]["note"], 0));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function delete_transaction($transaction_id)
    {
        $transactions = $this->model("transactions");
        $result = $transactions->delete_transaction($transaction_id);
        if (0 < $result && $this->settings_info["telegram_enable"] == 1) {
            $user = $this->model("user");
            $store = $this->model("store");
            $employees_info = $user->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $transactions_info_d = $transactions->get_transaction_by_id($transaction_id);
            $transactions_info = $transactions_info_d[0];
            $action_type = "";
            if ($transactions_info["transaction_type"] == 1) {
                $action_type = "Transaction DELETED - Cash IN";
            } else {
                if ($transactions_info["transaction_type"] == 2) {
                    $action_type = "Transaction DELETED - Cash OUT";
                } else {
                    if ($transactions_info["transaction_type"] == 3) {
                        $action_type = "Transaction DELETED - Transfer";
                    }
                }
            }
            $info_tel = array();
            $info_tel["message"] = "<strong>" . $action_type . ":</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Amount USD:</strong> " . number_format($transactions_info["amount_usd"], 0) . " \n";
            $info_tel["message"] .= "<strong>Amount LBP:</strong> " . number_format($transactions_info["amount_lbp"], 0) . " \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array());
    }
    public function add_new_transaction()
    {
        $transactions = $this->model("transactions");
        $info = array();
        $info["amount_usd"] = filter_input(INPUT_POST, "amount_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["amount_lbp"] = filter_input(INPUT_POST, "amount_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["transaction_type"] = filter_input(INPUT_POST, "transaction_type", FILTER_SANITIZE_NUMBER_INT);
        $info["transaction_to_cashbox_id"] = filter_input(INPUT_POST, "transaction_to", FILTER_SANITIZE_NUMBER_INT);
        $info["transaction_note"] = filter_input(INPUT_POST, "transaction_note", self::conversion_php_version_filter());
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
        $id = $transactions->add_new_transaction($info);
        if (0 < $id && $this->settings_info["telegram_enable"] == 1) {
            $user = $this->model("user");
            $store = $this->model("store");
            $employees_info = $user->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $action_type = "";
            if ($info["transaction_type"] == 1) {
                $action_type = "Transaction - Cash IN";
            } else {
                if ($info["transaction_type"] == 2) {
                    $action_type = "Transaction - Cash OUT";
                } else {
                    if ($info["transaction_type"] == 3) {
                        $action_type = "Transaction - Transfer";
                    }
                }
            }
            $info_tel = array();
            $info_tel["message"] = "<strong>" . $action_type . ":</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Amount USD:</strong> " . number_format($info["amount_usd"], 0) . " \n";
            $info_tel["message"] .= "<strong>Amount LBP:</strong> " . number_format($info["amount_lbp"], 0) . " \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array($id));
    }

    public function get_info()
    {
        $cashbox = $this->model("cashbox");
        $return_info = array();
        $return_info["opened"] = $cashbox->get_all_opened_cashbox_but_not_me($_SESSION["cashbox_id"]);
        echo json_encode($return_info);
    }
}

?>