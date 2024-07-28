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
class cashback extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function delete_cashback_payment($_customer_id)
    {
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        $customers->delete_cashback_payment($customer_id);
        echo json_encode(array());
    }
    public function add_new_cashback()
    {
        $invoice = $this->model("invoice");
        $cashbox = $this->model("cashbox");
        $info = array();
        $info["customer_id"] = filter_input(INPUT_POST, "customer_id", FILTER_SANITIZE_NUMBER_INT);
        $info["cashback_value"] = filter_input(INPUT_POST, "cashback_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (isset($_SESSION["cashbox_id"])) {
            $info["cashbox_id"] = $_SESSION["cashbox_id"];
        } else {
            $info["cashbox_id"] = 0;
        }
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $info["currency_id"] = $currency_default_id;
        $invoice->add_new_cashback($info);
        if (isset($_SESSION["cashbox_id"])) {
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        }
        echo json_encode(array($info["customer_id"]));
    }
    public function show_all_cashback_customers($_p0, $_p1, $_p2, $_p3)
    {
        $data_array["data"] = array();
        $customers = $this->model("customers");
        $all_cashback_customers = $customers->get_all_cashback_customers();
        $all_cashback_customers_invoices_nb = $customers->get_all_cashback_customers_invoices_nb();
        $all_cashback_customers_invoices_nb_array = array();
        $all_cashback_total_invoices_array = array();
        $all_cashback_total_array = array();
        for ($i = 0; $i < count($all_cashback_customers_invoices_nb); $i++) {
            $all_cashback_customers_invoices_nb_array[$all_cashback_customers_invoices_nb[$i]["invoice_customer_referrer"]] = $all_cashback_customers_invoices_nb[$i]["num"];
            $all_cashback_total_invoices_array[$all_cashback_customers_invoices_nb[$i]["invoice_customer_referrer"]] = $all_cashback_customers_invoices_nb[$i]["total_amount"];
            $all_cashback_total_array[$all_cashback_customers_invoices_nb[$i]["invoice_customer_referrer"]] = $all_cashback_customers_invoices_nb[$i]["cashback_value"];
        }
        $all_customers = $customers->getCustomersEvenDeleted();
        $all_customers_array = array();
        for ($i = 0; $i < count($all_customers); $i++) {
            $all_customers_array[$all_customers[$i]["id"]] = trim($all_customers[$i]["name"] . " " . $all_customers[$i]["middle_name"] . " " . $all_customers[$i]["last_name"]);
        }
        $all_cashback_sum = $customers->get_cashback_sum();
        $all_cashback_sum_array = array();
        for ($i = 0; $i < count($all_cashback_sum); $i++) {
            $all_cashback_sum_array[$all_cashback_sum[$i]["customer_id"]] = $all_cashback_sum[$i]["sum"];
        }
        $all_cancelled_cashback_sum = $customers->get_cancelled_cashback_sum();
        $all_cancelled_cashback_sum_array = array();
        for ($i = 0; $i < count($all_cancelled_cashback_sum); $i++) {
            $all_cancelled_cashback_sum_array[$all_cancelled_cashback_sum[$i]["customer_id"]] = $all_cancelled_cashback_sum[$i]["sum"];
        }
        for ($i = 0; $i < count($all_cashback_customers); $i++) {
            $tmp = array();
            if (isset($all_cashback_sum_array[$all_cashback_customers[$i]["id"]])) {
                $cashback_paid = $all_cashback_sum_array[$all_cashback_customers[$i]["id"]];
            } else {
                $cashback_paid = 0;
            }
            $remain = $all_cashback_total_array[$all_cashback_customers[$i]["id"]] - $cashback_paid;
            array_push($tmp, $all_cashback_customers[$i]["id"]);
            array_push($tmp, $all_customers_array[$all_cashback_customers[$i]["id"]]);
            array_push($tmp, $all_cashback_customers_invoices_nb_array[$all_cashback_customers[$i]["id"]]);
            array_push($tmp, self::value_format_custom($all_cashback_total_invoices_array[$all_cashback_customers[$i]["id"]], $this->settings_info));
            array_push($tmp, self::value_format_custom($all_cashback_total_array[$all_cashback_customers[$i]["id"]], $this->settings_info));
            array_push($tmp, self::value_format_custom($cashback_paid, $this->settings_info));
            array_push($tmp, self::value_format_custom($remain, $this->settings_info));
            array_push($tmp, "<b class='redtowhite'>" . self::value_format_custom($all_cancelled_cashback_sum_array[$all_cashback_customers[$i]["id"]], $this->settings_info) . "</b>");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function show_all_cashback_for_customers($_p0, $_p1, $_p2, $_p3)
    {
        $customer_id = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $info = $customers->get_all_cashback_for_customers($customer_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, number_format($info[$i]["cashback_value"]));
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, $users_array[$info[$i]["by_user_id"]]["username"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>