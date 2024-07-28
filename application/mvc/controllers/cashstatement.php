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
class cashstatement extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public $currencies = NULL;
    public $currency_default = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
        $currencies = self::getCurrencies();
        for ($i = 0; $i < count($currencies); $i++) {
            $this->currencies[$currencies[$i]["id"]] = $currencies[$i];
            if ($currencies[$i]["system_default"] == 1) {
                $this->currency_default = $currencies[$i];
            }
        }
    }
    public function get_cs($_p0, $_p1, $_p2, $_p3, $_p4, $_p5)
    {
        $date = filter_var($_p0, self::conversion_php_version_filter());
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
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $payments = $this->model("payments");
        $suppliers = $this->model("suppliers");
        $expenses = $this->model("expenses");
        $expenses_details = $expenses->getExpensesByDateRange($_SESSION["store_id"], $date_range);
        $expenses_details_array = array();
        for ($i = 0; $i < count($expenses_details); $i++) {
            $expenses_details_array[$expenses_details[$i]["id"]] = $expenses_details[$i];
        }
        $customers_details = $customers->getCustomersEvenDeleted();
        $customers_details_array = array();
        for ($i = 0; $i < count($customers_details); $i++) {
            $customers_details_array[$customers_details[$i]["id"]] = $customers_details[$i];
        }
        $suppliers_details = $suppliers->getAllSuppliersEvenDeleted();
        $suppliers_details_array = array();
        for ($i = 0; $i < count($suppliers_details); $i++) {
            $suppliers_details_array[$suppliers_details[$i]["id"]] = $suppliers_details[$i];
        }
        $customers_invoices = $invoice->getAllInvoices_list($_SESSION["store_id"], $date_range, $this->settings_info);
        $payments_info = $payments->getPaymentsByIntervalOfDate($date_range);
        $payments_suppliers_info = $payments->getPaymentsSuppliersByIntervalOfDate($date_range);
        $data_array["data"] = array();
        $tmp = array();
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, number_format(0, $this->currency_default["pi_decimal"]) . " " . $this->settings_info["default_currency_symbol"]);
        array_push($tmp, "");
        array_push($data_array["data"], $tmp);
        $cs = array();
        $balance = 0;
        for ($i = 0; $i < count($customers_invoices); $i++) {
            $in = 0;
            $out = number_format($customers_invoices[$i]["total_value"], $this->currency_default["pi_decimal"]) . " " . $this->settings_info["default_currency_symbol"];
            if ($customers_invoices[$i]["closed"] == 1 && $customers_invoices[$i]["auto_closed"] == 0) {
                $out = "";
                $in = $customers_invoices[$i]["total_value"];
                if (0 < $customers_invoices[$i]["customer_id"]) {
                    $cus_inf = " (" . $customers_details_array[$customers_invoices[$i]["customer_id"]]["name"] . " " . $customers_details_array[$customers_invoices[$i]["customer_id"]]["middle_name"] . " " . $customers_details_array[$customers_invoices[$i]["customer_id"]]["last_name"] . ")";
                } else {
                    $cus_inf = "";
                }
                array_push($cs, array("timestamp" => strtotime($customers_invoices[$i]["creation_date"]), "creation_date" => $customers_invoices[$i]["creation_date"], "id" => "Invoice ID - " . $customers_invoices[$i]["id"] . $cus_inf . "", "note" => $customers_invoices[$i]["note"], "out" => $out, "in" => $in));
            }
        }
        for ($i = 0; $i < count($payments_info); $i++) {
            $out = 0;
            $in = $payments_info[$i]["balance"] * $payments_info[$i]["rate"];
            array_push($cs, array("timestamp" => strtotime($payments_info[$i]["balance_date"]), "creation_date" => $payments_info[$i]["balance_date"], "id" => "Payment ID - " . $payments_info[$i]["id"], "note" => $payments_info[$i]["note"], "out" => $out, "in" => $in));
        }
        for ($i = 0; $i < count($payments_suppliers_info); $i++) {
            $in = 0;
            $sup_inf = "";
            if (0 < $payments_suppliers_info[$i]["supplier_id"]) {
                $sup_inf = " (" . $suppliers_details_array[$payments_suppliers_info[$i]["supplier_id"]]["name"] . ")";
            } else {
                $sup_inf = "";
            }
            $out = $payments_suppliers_info[$i]["payment_value"] * $payments_suppliers_info[$i]["currency_rate"];
            array_push($cs, array("timestamp" => strtotime($payments_suppliers_info[$i]["payment_date"]), "creation_date" => $payments_suppliers_info[$i]["payment_date"], "id" => "Supplier Payment ID - " . $payments_suppliers_info[$i]["id"] . $sup_inf, "note" => $payments_suppliers_info[$i]["payment_note"], "out" => $out, "in" => $in));
        }
        for ($i = 0; $i < count($expenses_details); $i++) {
            $in = 0;
            $out = $expenses_details[$i]["value"];
            array_push($cs, array("timestamp" => strtotime($expenses_details[$i]["date"]), "creation_date" => $expenses_details[$i]["date"], "id" => "Expense ID - " . $expenses_details[$i]["id"], "note" => $payments_suppliers_info[$i]["description"], "out" => $out, "in" => $in));
        }
        self::__USORT_TIMESTAMP($cs);
        for ($i = 0; $i < count($cs); $i++) {
            $tmp = array();
            $balance += floatval($cs[$i]["in"]);
            $balance -= floatval($cs[$i]["out"]);
            array_push($tmp, $cs[$i]["creation_date"]);
            array_push($tmp, $cs[$i]["id"]);
            if (0 < $cs[$i]["in"]) {
                array_push($tmp, number_format($cs[$i]["in"], $this->currency_default["pi_decimal"]) . " " . $this->settings_info["default_currency_symbol"]);
            } else {
                array_push($tmp, "");
            }
            if (0 < $cs[$i]["out"]) {
                array_push($tmp, number_format($cs[$i]["out"], $this->currency_default["pi_decimal"]) . " " . $this->settings_info["default_currency_symbol"]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, number_format($balance, $this->currency_default["pi_decimal"]) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, $cs[$i]["note"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>