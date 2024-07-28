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
class quick_display extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function get_all_expenses_quick($_date)
    {
        $data_array["data"] = array();
        $date = filter_var($_date, self::conversion_php_version_filter());
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
        $expenses = $this->model("expenses");
        $expenses__ = $expenses->getExpensesByDateRange($_SESSION["store_id"], $date_range);
        $expenses_types = $expenses->getTypesEvenDeleted();
        $expenses_types_array = array();
        for ($i = 0; $i < count($expenses_types); $i++) {
            $expenses_types_array[$expenses_types[$i]["id"]] = $expenses_types[$i];
        }
        for ($i = 0; $i < count($expenses__); $i++) {
            $tmp = array();
            array_push($tmp, $expenses__[$i]["id"]);
            array_push($tmp, $expenses_types_array[$expenses__[$i]["type_id"]]["name"]);
            array_push($tmp, number_format($expenses__[$i]["value"], $this->settings_info["number_of_decimal_points"]));
            $dt_ = explode(" ", $expenses__[$i]["date"]);
            array_push($tmp, $dt_[0]);
            array_push($tmp, $expenses__[$i]["description"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_payments_quick($_date)
    {
        $data_array["data"] = array();
        $date = filter_var($_date, self::conversion_php_version_filter());
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
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
        $suppliers = $this->model("suppliers");
        $payments__ = $suppliers->getAllSuppliersPaymentsDateRange___($date_range);
        $suppliers_info = $suppliers->getAllSuppliersEvenDeleted();
        $suppliers_info_array = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_info_array[$suppliers_info[$i]["id"]] = $suppliers_info[$i];
        }
        $sum = 0;
        for ($i = 0; $i < count($payments__); $i++) {
            $tmp = array();
            array_push($tmp, $payments__[$i]["id"]);
            array_push($tmp, $suppliers_info_array[$payments__[$i]["supplier_id"]]["name"]);
            array_push($tmp, number_format($payments__[$i]["payment_value"] * $payments__[$i]["currency_rate"], $this->settings_info["number_of_decimal_points"]));
            $sum += $payments__[$i]["payment_value"] * $payments__[$i]["currency_rate"];
            $dt_ = explode(" ", $payments__[$i]["payment_date"]);
            array_push($tmp, $dt_[0]);
            array_push($tmp, $payments__[$i]["payment_note"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_customers_payments_quick($_date)
    {
        $data_array["data"] = array();
        $date = filter_var($_date, self::conversion_php_version_filter());
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
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
        $users = $this->model("user");
        $users_info = $users->getAllUsersEvenDeleted();
        $users_info_array = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_info_array[$users_info[$i]["id"]] = $users_info[$i]["username"];
        }
        $customers = $this->model("customers");
        $payments__ = $customers->getAllCustomersPaymentsDateRange___($date_range);
        $customers_info = $customers->getCustomersEvenDeleted();
        $customers_info_array = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $customers_info_array[$customers_info[$i]["id"]] = $customers_info[$i];
        }
        $sum = 0;
        for ($i = 0; $i < count($payments__); $i++) {
            $tmp = array();
            array_push($tmp, $payments__[$i]["id"]);
            array_push($tmp, $customers_info_array[$payments__[$i]["customer_id"]]["name"] . " " . $customers_info_array[$payments__[$i]["customer_id"]]["middle_name"] . " " . $customers_info_array[$payments__[$i]["customer_id"]]["last_name"]);
            array_push($tmp, $users_info_array[$payments__[$i]["vendor_id"]]);
            array_push($tmp, number_format($payments__[$i]["balance"], $this->settings_info["number_of_decimal_points"]));
            $sum += $payments__[$i]["balance"];
            $dt_ = explode(" ", $payments__[$i]["balance_date"]);
            array_push($tmp, $dt_[0]);
            array_push($tmp, $payments__[$i]["note"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_invoices_quick($_date, $_status)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $settings = $this->model("settings");
        $customers = $this->model("customers");
        $date = filter_var($_date, self::conversion_php_version_filter());
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
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
        $payment_method = $settings->get_all_payment_method();
        $payment_method_info = array();
        for ($i = 0; $i < count($payment_method); $i++) {
            $payment_method_info[$payment_method[$i]["id"]] = $payment_method[$i]["method_name"];
        }
        $info = $invoice->get_quick_invoices($date_range, $status);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
            array_push($tmp, $info[$i]["creation_date"]);
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                if ($info[$i]["closed"] == 0) {
                    array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . " " . $customer[0]["middle_name"] . " " . $customer[0]["last_name"] . "</span>");
                } else {
                    array_push($tmp, $customer[0]["name"] . " " . $customer[0]["middle_name"] . " " . $customer[0]["last_name"]);
                }
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, self::value_format_custom($info[$i]["total_value"], $this->settings_info));
            array_push($tmp, self::value_format_custom($info[$i]["invoice_discount"], $this->settings_info));
            if (0 < $info[$i]["tax"]) {
                array_push($tmp, number_format(($info[$i]["tax"] - 1) * 100, 2) . " %");
            } else {
                array_push($tmp, "0");
            }
            array_push($tmp, number_format($info[$i]["freight"], 2));
            if (0 < $info[$i]["tax"]) {
                array_push($tmp, self::value_format_custom($info[$i]["total_value"] + $info[$i]["invoice_discount"] + ($info[$i]["total_value"] + $info[$i]["invoice_discount"]) * $info[$i]["tax"] / 100 + $info[$i]["freight"], $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($info[$i]["total_value"] + $info[$i]["invoice_discount"] + $info[$i]["freight"], $this->settings_info));
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($info[$i]["profit_after_discount"], $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom("***", $this->settings_info));
            }
            if ($info[$i]["closed"] == 1 && $info[$i]["auto_closed"] == 0) {
                array_push($tmp, $payment_method_info[$info[$i]["payment_method"]]);
            } else {
                if ($info[$i]["closed"] == 0 && $info[$i]["auto_closed"] == 0) {
                    array_push($tmp, "Debt");
                } else {
                    array_push($tmp, "");
                }
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>