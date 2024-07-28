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
class journal extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function get_journal($p0, $p1, $p2, $p3, $p4)
    {
        $data_array["data"] = array();
        $date = filter_var($p0, self::conversion_php_version_filter());
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
        $journal = $this->model("journal");
        $customers = $this->model("customers");
        $suppliers = $this->model("suppliers");
        $settings = $this->model("settings");
        $expenses = $this->model("expenses");
        $settings_payments_methos = $settings->get_all_payment_method();
        $p_method = array();
        for ($i = 0; $i < count($settings_payments_methos); $i++) {
            $p_method[$settings_payments_methos[$i]["id"]] = $settings_payments_methos[$i]["method_name"];
        }
        $customers_info = $customers->getCustomersEvenDeleted();
        $customers_info_array = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $customers_info_array[$customers_info[$i]["id"]] = $customers_info[$i];
        }
        $suppliers_info = $suppliers->getAllSuppliersEvenDeleted();
        $suppliers_info_array = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_info_array[$suppliers_info[$i]["id"]] = $suppliers_info[$i];
        }
        $invoices = $journal->get_invoices($date_range);
        $payments_info = $journal->getPaymentsByIntervalOfDate($date_range);
        $credit_notes = $journal->get_credit_note_by_daterange($date_range);
        $pinv = $journal->get_pi_by_date_range($date_range);
        $sup_payments = $journal->get_suppliers_payment_by_daterange($date_range);
        $debit_notes = $journal->debit_notes($date_range);
        $info = array();
        for ($i = 0; $i < count($debit_notes); $i++) {
            $description = "DEBIT NOTE <b>REF: " . $debit_notes[$i]["id"] . "</b> TO <b>" . $suppliers_info_array[$debit_notes[$i]["supplier_id"]]["name"] . "";
            $debts = 0;
            $receipt = $debit_notes[$i]["debit_value"] * $debit_notes[$i]["currency_rate"];
            $payment = 0;
            $purshace = 0;
            array_push($info, array("timestamp" => strtotime($debit_notes[$i]["creation_date"]), "datetime" => $debit_notes[$i]["creation_date"], "description" => $description, "payment_method" => "", "purchase" => $purshace, "sales" => 0, "payment" => $payment, "receipt" => $receipt, "debt" => $debts));
        }
        for ($i = 0; $i < count($sup_payments); $i++) {
            $description = "SUPPLIER PAYMENT: <b>REF: " . $sup_payments[$i]["id"] . "</b> TO <b>" . $suppliers_info_array[$sup_payments[$i]["supplier_id"]]["name"] . "";
            $debts = 0;
            $receipt = 0;
            $payment = $sup_payments[$i]["payment_value"] * $sup_payments[$i]["currency_rate"];
            $purshace = 0;
            array_push($info, array("timestamp" => strtotime($sup_payments[$i]["creation_date"]), "datetime" => $sup_payments[$i]["creation_date"], "description" => $description, "payment_method" => "", "purchase" => $purshace, "sales" => 0, "payment" => $payment, "receipt" => $receipt, "debt" => $debts));
        }
        for ($i = 0; $i < count($pinv); $i++) {
            $piref = "";
            if (0 < strlen($pinv[$i]["invoice_reference"])) {
                $piref = " - PIREF: " . $pinv[$i]["invoice_reference"];
            }
            $description = "<b class='red'>PURCHASE INVOICE SYS REF: " . $pinv[$i]["id"] . $piref . "</b> FROM <b>" . $suppliers_info_array[$pinv[$i]["supplier_id"]]["name"] . "</b>";
            $debts = 1;
            $receipt = 0;
            $payment = 0;
            $purshace = $pinv[$i]["total"];
            array_push($info, array("timestamp" => strtotime($pinv[$i]["delivery_date"]), "datetime" => $pinv[$i]["delivery_date"], "description" => $description, "payment_method" => "", "purchase" => $purshace, "sales" => 0, "payment" => $payment, "receipt" => $receipt, "debt" => $debts));
        }
        for ($i = 0; $i < count($credit_notes); $i++) {
            $description = "CREDIT NOTE <b>REF: " . $credit_notes[$i]["id"] . "</b>";
            if (0 < $credit_notes[$i]["customer_id"]) {
                $description .= " TO <b>" . $customers_info_array[$credit_notes[$i]["customer_id"]]["name"] . " " . $customers_info_array[$credit_notes[$i]["customer_id"]]["middle_name"] . " " . $customers_info_array[$credit_notes[$i]["customer_id"]]["last_name"] . "</b>";
            } else {
                $description .= "";
            }
            $debts = 0;
            $receipt = 0;
            $payment = $credit_notes[$i]["credit_value"] * $credit_notes[$i]["currency_rate"];
            array_push($info, array("timestamp" => strtotime($credit_notes[$i]["creation_date"]), "datetime" => $credit_notes[$i]["creation_date"], "description" => $description, "payment_method" => "", "purchase" => 0, "sales" => 0, "payment" => $payment, "receipt" => $receipt, "debt" => $debts));
        }
        for ($i = 0; $i < count($payments_info); $i++) {
            $description = "";
            $description = "PAYMENT ON ACCOUNT <b>REF: " . $payments_info[$i]["id"] . "</b> ";
            if (0 < $payments_info[$i]["customer_id"]) {
                $description .= "FROM <b>" . $customers_info_array[$payments_info[$i]["customer_id"]]["name"] . " " . $customers_info_array[$payments_info[$i]["customer_id"]]["middle_name"] . " " . $customers_info_array[$payments_info[$i]["customer_id"]]["last_name"] . "</b>";
            } else {
                $description .= "";
            }
            $payment_method = "";
            $debts = 0;
            $payment = 0;
            $receipt = $payments_info[$i]["balance"] * $payments_info[$i]["rate"];
            array_push($info, array("timestamp" => strtotime($payments_info[$i]["balance_date"]), "datetime" => $payments_info[$i]["balance_date"], "description" => $description, "payment_method" => $payment_method, "purchase" => 0, "sales" => 0, "payment" => $payment, "receipt" => $receipt, "debt" => $debts));
        }
        for ($i = 0; $i < count($invoices); $i++) {
            $payment = 0;
            $receipt = 0;
            $debts = 0;
            $description = "SALES INVOICE <b>REF:" . $invoices[$i]["id"] . "</b> ";
            if (0 < $invoices[$i]["customer_id"]) {
                $description .= "TO <b>" . $customers_info_array[$invoices[$i]["customer_id"]]["name"] . " " . $customers_info_array[$invoices[$i]["customer_id"]]["middle_name"] . " " . $customers_info_array[$invoices[$i]["customer_id"]]["last_name"] . "</b>";
            } else {
                $description .= "";
            }
            if ($invoices[$i]["closed"] == 0 || $invoices[$i]["auto_closed"] == 1) {
                $description .= "";
                $debts = 1;
            }
            $payment_method = "";
            if (0 < $invoices[$i]["payment_method"]) {
                if ($invoices[$i]["closed"] == 1 && $invoices[$i]["auto_closed"] == 0) {
                    $payment_method = $p_method[$invoices[$i]["payment_method"]];
                } else {
                    $payment_method = "<b class='red'>Debt</b>";
                }
            }
            if ($invoices[$i]["closed"] == 1 && $invoices[$i]["auto_closed"] == 0) {
                $receipt = $invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"];
            }
            array_push($info, array("timestamp" => strtotime($invoices[$i]["creation_date"]), "datetime" => $invoices[$i]["creation_date"], "description" => $description, "payment_method" => $payment_method, "purchase" => 0, "sales" => $invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"], "payment" => $payment, "receipt" => $receipt, "debt" => $debts));
        }
        $expenses_info = $expenses->getExpensesByDateRange($_SESSION["store_id"], $date_range);
        $expenses_info_types = $expenses->getTypesEvenDeleted();
        $expenses_info_types_array = array();
        for ($i = 0; $i < count($expenses_info_types); $i++) {
            $expenses_info_types_array[$expenses_info_types[$i]["id"]] = $expenses_info_types[$i];
        }
        for ($i = 0; $i < count($expenses_info); $i++) {
            $payment = $expenses_info[$i]["value"];
            $receipt = 0;
            $debts = 0;
            array_push($info, array("timestamp" => strtotime($expenses_info[$i]["creation_date"]), "datetime" => $expenses_info[$i]["creation_date"], "description" => "EXPENSES: <b>" . $expenses_info_types_array[$expenses_info[$i]["type_id"]]["name"] . "</b> " . $expenses_info[$i]["description"], "payment_method" => "", "purchase" => 0, "sales" => 0, "payment" => $payment, "receipt" => $receipt, "debt" => $debts));
        }
        self::__USORT_TIMESTAMP($info);
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["datetime"]);
            if ($info[$i]["debt"] == 0) {
                array_push($tmp, $info[$i]["description"]);
            } else {
                array_push($tmp, "<b class='red'>" . $info[$i]["description"] . "</b>");
            }
            array_push($tmp, $info[$i]["payment_method"]);
            if (0 < $info[$i]["purchase"]) {
                array_push($tmp, "<b class='red'>" . self::value_format_custom_no_currency($info[$i]["purchase"], $this->settings_info) . "</b>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $info[$i]["sales"]) {
                if ($info[$i]["debt"] == 0) {
                    array_push($tmp, self::value_format_custom_no_currency($info[$i]["sales"], $this->settings_info));
                } else {
                    array_push($tmp, "<b class='red'>" . self::value_format_custom_no_currency($info[$i]["sales"], $this->settings_info) . "</b>");
                }
            } else {
                if ($info[$i]["debt"] == 0) {
                    array_push($tmp, 0);
                } else {
                    array_push($tmp, "<b class='red'>0</b>");
                }
            }
            if (0 < $info[$i]["payment"]) {
                array_push($tmp, self::value_format_custom_no_currency($info[$i]["payment"], $this->settings_info));
            } else {
                array_push($tmp, "");
            }
            if (0 < $info[$i]["receipt"]) {
                array_push($tmp, self::value_format_custom_no_currency($info[$i]["receipt"], $this->settings_info));
            } else {
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>