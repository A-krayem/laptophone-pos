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
class expenses extends Controller
{
    public $settings_info = NULL;
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function _default()
    {
    }
    public function add()
    {
        $this->view("expenses");
    }
    public function getExpensesTypes($_date)
    {
        $date = filter_var($_date, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $expenses = $this->model("expenses");
        $info = $expenses->getTypes();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            $total = $expenses->getTotalByTypes($info[$i]["id"], $date_range);
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, "<input onchange='update_name(" . $info[$i]["id"] . ")' class='exp_type_edit' id='et_" . $info[$i]["id"] . "' value='" . $info[$i]["name"] . "' />");
            array_push($tmp, self::value_format_custom($total, $this->settings_info));
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_expenses_needs()
    {
        $info = array();
        $expenses = $this->model("expenses");
        $info["expenses_types"] = $expenses->getTypes();
        echo json_encode($info);
    }
    public function update_expense_type_name($id_, $name_)
    {
        $expenses = $this->model("expenses");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($name_, self::conversion_php_version_filter());
        $expenses->update_expense_type_name($id, $name);
        $return = array();
        echo json_encode($return);
    }
    public function add_new_category()
    {
        $expenses = $this->model("expenses");
        $info["name"] = filter_input(INPUT_POST, "category_val", self::conversion_php_version_filter());
        $return = array();
        $return["category_id"] = $expenses->add_new_category($info);
        $return["category_name"] = $info["name"];
        echo json_encode($return);
    }
    public function delete_expense($id_)
    {
        $expenses = $this->model("expenses");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $expense_info = $expenses->get_expense($id);
        $return["status"] = $expenses->delete_expense($id);
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
            $info_tel["message"] = "<strong>Expenses Deleted:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Expenses ID:</strong> " . $id . " \n";
            $info_tel["message"] .= "<strong>Expenses Amount:</strong> " . $expense_info[0]["value"] . " USD \n";
            $info_tel["message"] .= "<strong>Description:</strong> " . $expense_info[0]["description"] . " USD \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode($return);
    }
    public function delete_expense_type($id_)
    {
        $expenses = $this->model("expenses");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $expenses->delete_expense_type($id);
        echo json_encode($return);
    }
    public function get_expense($id_)
    {
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $expenses = $this->model("expenses");
        $info = $expenses->get_expense($id);
        $info[0]["value"] = floatval($info[0]["value"]);
        $date = new DateTime($info[0]["date"]);
        $info[0]["date"] = $date->format("m/d/Y");
        echo json_encode($info);
    }
    public function getExpenses($store_id_, $_daterange)
    {
        $expenses = $this->model("expenses");
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($_daterange, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date == "today") {
            $date_range[0] = date("Y-m-d");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $expenses_data = $expenses->getExpensesByDateRange($store_id, $date_range);
        $expenses_types = $expenses->getTypes();
        $types = array();
        for ($i = 0; $i < count($expenses_types); $i++) {
            $types[$expenses_types[$i]["id"]] = $expenses_types[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($expenses_data); $i++) {
            $tmp = array();
            $expenses_data_exploaded = explode(" ", $expenses_data[$i]["date"]);
            array_push($tmp, self::idFormat_expenses($expenses_data[$i]["id"]));
            array_push($tmp, $types[$expenses_data[$i]["type_id"]]);
            array_push($tmp, $expenses_data[$i]["description"]);
            array_push($tmp, $expenses_data_exploaded[0]);
            array_push($tmp, self::value_format_custom($expenses_data[$i]["value"], $this->settings_info));
            array_push($tmp, "");
            if ($_SESSION["role"] == 1) {
                array_push($tmp, "1");
            } else {
                if ($expenses_data[$i]["cashbox_id"] == $_SESSION["cashbox_id"]) {
                    array_push($tmp, "1");
                } else {
                    array_push($tmp, "0");
                }
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function add_new_expense($store_id_)
    {
        if ($_SESSION["demo"] == 1) {
            $data["id"] = 0;
            $data["cashBoxTotal"] = 0;
            echo json_encode($data);
        } else {
            $expenses = $this->model("expenses");
            $cashbox = $this->model("cashbox");
            $info = array();
            $info["store_id"] = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
            $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
            $info["type_id"] = filter_input(INPUT_POST, "expense_type", FILTER_SANITIZE_NUMBER_INT);
            $info["description"] = filter_input(INPUT_POST, "expense_description", self::conversion_php_version_filter());
            $info["value"] = filter_input(INPUT_POST, "expense_val", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["date"] = filter_input(INPUT_POST, "expense_date", self::conversion_php_version_filter());
            $info["reflected_to_profit"] = 0;
            if (isset($_POST["reflected_to_profit"])) {
                $info["reflected_to_profit"] = 1;
            }
            $dateString = $info["date"];
            $timestamp = strtotime($dateString);
            $info["date"] = date("Y-m-d H:i:s", $timestamp);
            $info["vendor_id"] = $_SESSION["id"];
            $info["cash_usd_to_return"] = filter_input(INPUT_POST, "r_cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["cash_lbp_to_return"] = filter_input(INPUT_POST, "r_cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["returned_cash_lbp"] = filter_input(INPUT_POST, "r_cash_lbp_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["returned_cash_usd"] = filter_input(INPUT_POST, "r_cash_usd_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["cash_lbp_in"] = filter_input(INPUT_POST, "cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["cash_usd_in"] = filter_input(INPUT_POST, "cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["rate"] = $this->settings_info["usdlbp_rate"];
            if (!isset($info["cash_usd_to_return"]) || $info["cash_usd_to_return"] == "") {
                $info["cash_usd_to_return"] = 0;
            }
            if (!isset($info["cash_lbp_to_return"]) || $info["cash_lbp_to_return"] == "") {
                $info["cash_lbp_to_return"] = 0;
            }
            if (!isset($info["returned_cash_lbp"]) || $info["returned_cash_lbp"] == "") {
                $info["returned_cash_lbp"] = 0;
            }
            if (!isset($info["returned_cash_usd"]) || $info["returned_cash_usd"] == "") {
                $info["returned_cash_usd"] = 0;
            }
            if (!isset($info["cash_lbp_in"]) || $info["cash_lbp_in"] == "") {
                $info["cash_lbp_in"] = 0;
            }
            if (!isset($info["cash_usd_in"]) || $info["cash_usd_in"] == "") {
                $info["cash_usd_in"] = 0;
            }
            if ($info["id_to_edit"] == 0) {
                $id = $expenses->add_expense($info);
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
                    $info_tel["message"] = "<strong>Expenses:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                    $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                    $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                    $info_tel["message"] .= "<strong>Expenses ID:</strong> " . $id . " \n";
                    $info_tel["message"] .= "<strong>Expenses Amount:</strong> " . $info["value"] . " USD \n";
                    $info_tel["message"] .= "<strong>Description:</strong> " . $info["description"] . " USD \n";
                    self::send_to_telegram($info_tel, 1);
                }
                if ($_SESSION["role"] == 2) {
                    $cashbox->updateCashBox($_SESSION["cashbox_id"]);
                }
            } else {
                $expenses->update_expense($info);
            }
            $data["id"] = $info["id_to_edit"];
            if ($_SESSION["role"] == 2) {
                $data["cashBoxTotal"] = number_format($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
            }
            echo json_encode($data);
        }
    }
    public function gettypes()
    {
        $expenses = $this->model("expenses");
        $info = $expenses->getTypes();
        echo json_encode($info);
    }
}

?>