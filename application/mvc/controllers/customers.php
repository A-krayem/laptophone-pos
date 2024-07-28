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
class customers extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function _default()
    {
        self::giveAccessTo(array(2));
        $data = $this->settings_info;
        $this->view("customers", $data);
    }
    public function get_customers_typeahead()
    {
        $customers = $this->model("customers");
        $info = $customers->get_customers_typeahead();
        echo json_encode($info);
    }
    public function customers_overview()
    {
        self::giveAccessTo();
        $data = $this->settings_info;
        $this->view("customers_overview", $data);
    }
    public function getClientsOverview($_p0)
    {
        self::giveAccessTo();
        $customers = $this->model("customers");
        $invoice_model = $this->model("invoice");
        $creditnote_model = $this->model("creditnote");
        $payments = $this->model("payments");
        $remain_post = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $customers_data = $customers->getCustomers();
        $data_array["data"] = array();
        $data_array["remain"] = 0;
        $invoices_info_group = $invoice_model->getTotalUnpaidGroup();
        $invoices_info_group_ = array();
        for ($i = 0; $i < count($invoices_info_group); $i++) {
            $invoices_info_group_[$invoices_info_group[$i]["customer_id"]] = $invoices_info_group[$i]["sum"];
        }
        $creditnote_sum_group = $creditnote_model->get_total_sum_creditnote_group();
        $creditnote_sum_group_ = array();
        for ($i = 0; $i < count($creditnote_sum_group); $i++) {
            $creditnote_sum_group_[$creditnote_sum_group[$i]["customer_id"]] = $creditnote_sum_group[$i]["sum"];
        }
        $total_payments_group = $payments->getTotalPaymentForCustomer_group();
        $total_payments_group_ = array();
        for ($i = 0; $i < count($total_payments_group); $i++) {
            $total_payments_group_[$total_payments_group[$i]["customer_id"]] = $total_payments_group[$i]["sum"];
        }
        $debit = 0;
        $credit = 0;
        for ($i = 0; $i < count($customers_data); $i++) {
            $debit += $customers_data[$i]["starting_balance"] + $invoices_info_group_[$customers_data[$i]["id"]];
            $credit += $creditnote_sum_group_[$customers_data[$i]["id"]] + $total_payments_group_[$customers_data[$i]["id"]];
            $rm = $customers_data[$i]["starting_balance"] + $invoices_info_group_[$customers_data[$i]["id"]] - $creditnote_sum_group_[$customers_data[$i]["id"]] - $total_payments_group_[$customers_data[$i]["id"]];
            if ($remain_post == 0 || $remain_post == 1 && 0 < $rm || $remain_post == 2 && $rm < 0 || $remain_post == 3 && $rm == 0) {
                $tmp = array();
                array_push($tmp, self::idFormat_customer($customers_data[$i]["id"]));
                array_push($tmp, $customers_data[$i]["name"]);
                array_push($tmp, $customers_data[$i]["phone"]);
                array_push($tmp, self::global_number_formatter($customers_data[$i]["starting_balance"], $this->settings_info));
                if (isset($invoices_info_group_[$customers_data[$i]["id"]])) {
                    array_push($tmp, self::global_number_formatter($invoices_info_group_[$customers_data[$i]["id"]], $this->settings_info));
                } else {
                    array_push($tmp, self::global_number_formatter(0, $this->settings_info));
                }
                if (isset($total_payments_group_[$customers_data[$i]["id"]])) {
                    array_push($tmp, self::global_number_formatter($total_payments_group_[$customers_data[$i]["id"]], $this->settings_info));
                } else {
                    array_push($tmp, self::global_number_formatter(0, $this->settings_info));
                }
                if (isset($creditnote_sum_group_[$customers_data[$i]["id"]])) {
                    array_push($tmp, self::global_number_formatter($creditnote_sum_group_[$customers_data[$i]["id"]], $this->settings_info));
                } else {
                    array_push($tmp, self::global_number_formatter(0, $this->settings_info));
                }
                array_push($tmp, self::global_number_formatter($rm, $this->settings_info));
                array_push($tmp, self::global_number_formatter($rm, $this->settings_info));
                array_push($data_array["data"], $tmp);
            }
        }
        $data_array["debit"] = self::global_number_formatter(abs($debit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["credit"] = self::global_number_formatter(abs($credit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["balance"] = self::global_number_formatter(abs($debit) - abs($credit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        echo json_encode($data_array);
    }
    public function check_identity($_id)
    {
        self::giveAccessTo(array(2));
        $customers = $this->model("customers");
        $id = filter_var($_id, self::conversion_php_version_filter());
        $info = $customers->check_identity($id);
        echo json_encode(array("nb" => count($info)));
    }
    public function get_all_payments_logs($_date, $client_id)
    {
        $global_logs = $this->model("global_logs");
        $date_filter = filter_var($_date, self::conversion_php_version_filter());
        $date_range = array();
        if ($date_filter == "thismonth") {
            $date_range[0] = date("Y-m-d", strtotime(date("Y") . "-" . date("M") . "-01"));
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" - ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
        }
        $result = $global_logs->get_global_logs(4, $date_range, $client_id);
        $users = $this->model("user");
        $employees_info = $users->getAllUsersEvenDeleted();
        $employees_info_array = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp = array();
            array_push($tmp, $result[$i]["other_info"]);
            array_push($tmp, $result[$i]["creation_date"]);
            array_push($tmp, $employees_info_array[$result[$i]["created_by"]]);
            array_push($tmp, $result[$i]["description"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function print_payment_receipt_customer_payment($payment_id)
    {
        self::giveAccessTo(array(2));
        $customers = $this->model("customers");
        $currency = $this->model("currency");
        $settings = $this->model("settings");
        $data["payment_info"] = $customers->get_customer_payment($payment_id);
        $data["customer_info"] = $customers->getCustomersById($data["payment_info"][0]["customer_id"]);
        $all_currencies = $currency->getAllEnabledCurrencies();
        $data["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $data["currencies"][$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $payment_types = $settings->get_payment_method();
        $data["pm"] = array();
        for ($i = 0; $i < count($payment_types); $i++) {
            $data["pm"][$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
        }
        if ($this->settings_info["payment_receipt_style"] == "a4") {
            $this->view("printing/payment_receipt", $data);
        } else {
            if ($this->settings_info["payment_receipt_style"] == "pos58") {
                header("Location: ?r=new_printing&f=print_receipt&p0=" . $payment_id);
                exit;
            }
            $this->view("printing/payment_receipt_1", $data);
        }
    }
    public function print_statement($_customer_id, $_currrency_id, $_full)
    {
        self::giveAccessTo(array(2));
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $currrency_id = filter_var($_currrency_id, FILTER_SANITIZE_NUMBER_INT);
        $full = filter_var($_full, FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        $currency = $this->model("currency");
        $data["statement"] = self::get_customer_statement_data($customer_id);
        $data["customer_info"] = $customers->getCustomersById($customer_id);
        $data["currency_request_id"] = $currrency_id;
        $all_currencies = $currency->getAllEnabledCurrencies();
        $data["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            if ($all_currencies[$i]["system_default"] == 1) {
                $data["currency_system_default"] = $all_currencies[$i]["id"];
            }
            $data["currencies"][$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $this->view("printing/customer_statement", $data);
    }
    public function payment_done($_payment_id)
    {
        self::giveAccessTo(array(2));
        $payment_id = filter_var($_payment_id, FILTER_SANITIZE_NUMBER_INT);
        $payments = $this->model("payments");
        $payments->payment_customer_done($payment_id);
        echo json_encode(array());
    }
    public function statements($_customer_id)
    {
        self::giveAccessTo(array(2));
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $data["customer_id"] = $customer_id;
        $this->view("customer_statements", $data);
    }
    public function get_customers_types()
    {
        $customers = $this->model("customers");
        $countries = $this->model("countries");
        $suppliers = $this->model("suppliers");
        $user = $this->model("user");
        $info = array();
        $info["customers_types"] = $customers->getCustomersTypes();
        $info["countries"] = $countries->getCountries();
        $info["areas"] = $countries->getAreas();
        $info["districts"] = $countries->getAllDistricts();
        $info["cities"] = $countries->getAllCities();
        $info["vendors"] = $user->getAllVendors();
        $info["id_types"] = $customers->getIdentitiesTypes();
        $info["default_city_location"] = $countries->getDefaultCityLocation();
        $info["enable_advanced_customer_info"] = $this->settings_info["enable_advanced_customer_info"];
        $info["advanced_customer_info_img_width"] = $this->settings_info["advanced_customer_info_img_width"];
        $info["phone_number_format"] = $this->settings_info["phone_number_format"];
        $info["identity_number_format"] = $this->settings_info["identity_number_format"];
        $info["suppliers"] = $suppliers->getSuppliers();
        echo json_encode($info);
    }
    public function delete_customer_payment($_delete_customer_payment)
    {
        self::giveAccessTo(array(2));
        if ($this->settings_info["disable_delete_payment_on_pos"] == 1) {
            echo json_encode(array(0));
            exit;
        }
        $delete_customer_payment = filter_var($_delete_customer_payment, FILTER_SANITIZE_NUMBER_INT);
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $payment_info = $payments->getPaymentDetails($delete_customer_payment);
        $del_res = $payments->delete_customer_payment($delete_customer_payment);
        $customers_class = $this->model("customers");
        $customer_info = $customers_class->getCustomersById($payment_info[0]["customer_id"]);
        $client_name = $customer_info[0]["name"];
        if (0 < strlen($customer_info[0]["middle_name"])) {
            $client_name .= " " . $customer_info[0]["middle_name"];
        }
        if (0 < strlen($customer_info[0]["last_name"])) {
            $client_name .= " " . $customer_info[0]["last_name"];
        }
        if ($del_res) {
            $global_logs = $this->model("global_logs");
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["related_to_item_id"] = 0;
            $logs_info["description"] = "Client (" . $client_name . ") Payment Deleted, Amount:" . $payment_info[0]["balance"];
            $logs_info["log_type"] = 4;
            $logs_info["other_info"] = $delete_customer_payment;
            $logs_info["related_to_client_id"] = $payment_info[0]["customer_id"];
            $global_logs->add_global_log($logs_info);
        }
        if ($_SESSION["role"] == 2) {
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        }
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
            $info_tel["message"] = "<strong>Payment Deleted:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Customer ID:</strong> " . $payment_info[0]["customer_id"] . " \n";
            $info_tel["message"] .= "<strong>Customer Name:</strong> " . $client_name . " \n";
            $info_tel["message"] .= "<strong>Payment ID:</strong> " . $delete_customer_payment . " \n";
            $info_tel["message"] .= "<strong>Payment Amount:</strong> " . $payment_info[0]["balance"] . " USD \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array(1));
    }
    public function getPaymentDetails($_id)
    {
        self::giveAccessTo(array(2));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $payments = $this->model("payments");
        $info = $payments->getPaymentDetails($id);
        echo json_encode($info);
    }
    public function delete_cheque_picture($id_)
    {
        self::giveAccessTo(array(2));
        $payments = $this->model("payments");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $payment_info = $payments->getPaymentDetails($id);
        if (file_exists($payment_info[0]["picture"])) {
            $tmp = explode("/", $payment_info[0]["picture"]);
            rename($payment_info[0]["picture"], $tmp[0] . "/" . $tmp[1] . "/trash/" . time() . "_" . $tmp[2]);
            $payments->delete_cheque_picture($id);
        }
        echo json_encode(array());
    }
    public function add_customer_payment_new()
    {
        self::giveAccessTo(array(2));
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["customer_id"] = filter_input(INPUT_POST, "customer_id", FILTER_SANITIZE_NUMBER_INT);
        $info["invoice_id"] = filter_input(INPUT_POST, "invoice_id", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["invoice_id"])) {
            $info["invoice_id"] = 0;
        }
        $info["quotation_id"] = filter_input(INPUT_POST, "quotation_id", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["quotation_id"])) {
            $info["quotation_id"] = 0;
        }
        $info["value"] = filter_input(INPUT_POST, "payment_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["note"] = filter_input(INPUT_POST, "payment_note", self::conversion_php_version_filter());
        $info["payment_method"] = filter_input(INPUT_POST, "payment_method", FILTER_SANITIZE_NUMBER_INT);
        $info["creation_date"] = filter_input(INPUT_POST, "creation_date", self::conversion_php_version_filter());
        $info["bank_id"] = 0;
        $info["reference_nb"] = "";
        $info["owner"] = "";
        $info["voucher"] = "";
        $info["picture"] = "";
        $info["vendor_id"] = $_SESSION["id"];
        $info["currency_id"] = 1;
        $info["rate_value"] = 1;
        $info["rate"] = 1;
        $info["store_id"] = $_SESSION["store_id"];
        $info["cashbox_id"] = $_SESSION["cashbox_id"];
        $info["cash_in_usd"] = filter_input(INPUT_POST, "cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["cash_in_lbp"] = filter_input(INPUT_POST, "cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["returned_usd"] = filter_input(INPUT_POST, "r_cash_usd_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["returned_lbp"] = filter_input(INPUT_POST, "r_cash_lbp_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["to_returned_usd"] = filter_input(INPUT_POST, "r_cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["to_returned_lbp"] = filter_input(INPUT_POST, "r_cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["p_rate"] = $this->settings_info["usdlbp_rate"];
        if (!isset($info["cash_in_usd"]) || $info["cash_in_usd"] == "" || $info["cash_in_usd"] == NULL) {
            $info["cash_in_usd"] = 0;
        }
        if (!isset($info["cash_in_lbp"]) || $info["cash_in_lbp"] == "" || $info["cash_in_lbp"] == NULL) {
            $info["cash_in_lbp"] = 0;
        }
        if (!isset($info["returned_usd"]) || $info["returned_usd"] == "" || $info["returned_usd"] == NULL) {
            $info["returned_usd"] = 0;
        }
        if (!isset($info["returned_lbp"]) || $info["returned_lbp"] == "" || $info["returned_lbp"] == NULL) {
            $info["returned_lbp"] = 0;
        }
        if (!isset($info["to_returned_usd"]) || $info["to_returned_usd"] == "" || $info["to_returned_usd"] == NULL) {
            $info["to_returned_usd"] = 0;
        }
        if (!isset($info["to_returned_lbp"]) || $info["to_returned_lbp"] == "" || $info["to_returned_lbp"] == NULL) {
            $info["to_returned_lbp"] = 0;
        }
        if ($this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $customers = $this->model("customers");
            $customer_info = $customers->getCustomersById($info["customer_id"]);
            $info_tel = array();
            $info_tel["message"] = "<strong>Client Debt Payment</strong> \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Client Name:</strong> " . $customer_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Payment Amount:</strong> " . $info["value"] . " USD \n";
            self::send_to_telegram($info_tel, 1);
        }
        $id = $payments->add_payment_to_customer_new($info);
        self::autoCloseInvoices($info["customer_id"]);
        echo json_encode(array());
    }
    public function add_customer_payment()
    {
        self::giveAccessTo(array(2));
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $invoice = $this->model("invoice");
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["customer_id"] = filter_input(INPUT_POST, "customer_id", FILTER_SANITIZE_NUMBER_INT);
        $info["payment_method"] = filter_input(INPUT_POST, "payment_method", FILTER_SANITIZE_NUMBER_INT);
        $info["value"] = filter_input(INPUT_POST, "payment_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["note"] = filter_input(INPUT_POST, "payment_note", self::conversion_php_version_filter());
        $info["creation_date"] = filter_input(INPUT_POST, "creation_date", self::conversion_php_version_filter());
        $info["bank_id"] = filter_input(INPUT_POST, "bank_source", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["bank_id"])) {
            $info["bank_id"] = 0;
        }
        $info["reference_nb"] = filter_input(INPUT_POST, "reference", self::conversion_php_version_filter());
        $info["owner"] = filter_input(INPUT_POST, "payment_owner", self::conversion_php_version_filter());
        $info["voucher"] = filter_input(INPUT_POST, "voucher_nb", self::conversion_php_version_filter());
        $info["picture"] = filter_input(INPUT_POST, "cheque_picture", self::conversion_php_version_filter());
        $info["vendor_id"] = $_SESSION["id"];
        $info["value_date"] = filter_input(INPUT_POST, "payment_date", self::conversion_php_version_filter());
        $info["currency_id"] = filter_input(INPUT_POST, "payment_currency", FILTER_SANITIZE_NUMBER_INT);
        $info["rate_value"] = filter_input(INPUT_POST, "currency_rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if ($info["currency_id"] == $currency_default_id) {
            $info["rate"] = 1;
        } else {
            if ($currency_default_id == 1 && $info["currency_id"] == 2) {
                $info["rate"] = 1 / $info["rate_value"];
            } else {
                if ($currency_default_id == 2 && $info["currency_id"] == 1) {
                    $info["rate"] = $info["rate_value"];
                }
            }
        }
        if ($_SESSION["role"] == 2) {
            $info["store_id"] = $_SESSION["store_id"];
        } else {
            $info["store_id"] = "NULL";
        }
        if (isset($_SESSION["cashbox_id"])) {
            $info["cashbox_id"] = $_SESSION["cashbox_id"];
        } else {
            $info["cashbox_id"] = 0;
        }
        if ($info["id_to_edit"] == 0) {
            $id_p = $payments->add_payment_to_customer($info);
        } else {
            $id_p = $info["id_to_edit"];
        }
        if (is_uploaded_file($_FILES["cheque_picture"]["tmp_name"])) {
            $customer_id_for_cheque = filter_input(INPUT_POST, "customer_id_for_cheque", FILTER_SANITIZE_NUMBER_INT);
            $customer_id_for_cheque_name = self::uploade_picture($_FILES["cheque_picture"]["name"], $_FILES["cheque_picture"]["tmp_name"], $id_p, "cus_p_pic/");
            if ($customer_id_for_cheque_name != NULL && $customer_id_for_cheque_name != "" && 0 < strlen($customer_id_for_cheque_name)) {
                $payments->update_cheque_picture_name_for_customer_payment($id_p, $customer_id_for_cheque_name);
            }
        }
        if ($_SESSION["role"] == 2) {
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        }
        self::autoCloseInvoices($info["customer_id"]);
        echo json_encode(array());
    }
    public function get_customer_statement($_customer_id)
    {
        self::giveAccessTo(array(2));
        $data_array["data"] = array();
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        if ($customer_id == 0 || $customer_id == "") {
            echo json_encode($data_array);
        } else {
            $settings = $this->model("settings");
            $settings_payments_methos = $settings->get_all_payment_method();
            $p_method = array();
            for ($i = 0; $i < count($settings_payments_methos); $i++) {
                $p_method[$settings_payments_methos[$i]["id"]] = $settings_payments_methos[$i]["method_name"];
            }
            $store = $this->model("store");
            $stores = $store->getStores();
            $stores_info = array();
            for ($i = 0; $i < count($stores); $i++) {
                $stores_info[$stores[$i]["id"]] = $stores[$i]["name"];
            }
            $stm = self::get_customer_statement_data($_customer_id);
            $total_remain = 0;
            $cnt = 0;
            foreach ($stm as $key => $value) {
                $tmp = array();
                $invoice_id = "";
                if ($value["invoice_id"] != "") {
                    $inv_nb = self::idFormat_invoice($value["invoice_id"]);
                    if (0 < $value["invoice_nb_official"]) {
                        $dt = explode("-", $value["creation_date"]);
                        $inv_nb = $dt[0] . "-" . sprintf("%07s", $value["invoice_nb_official"]);
                    }
                    $invoice_id = "<span class='inv_click' onclick='edit_manual_invoice(" . $value["invoice_id"] . ")'>" . $inv_nb . "</span>";
                }
                if ($value["credit"] == 1) {
                    if ($value["deleted"] == 0) {
                        $total_remain += floatval($value["total_invoice_value"]);
                    }
                } else {
                    if ($value["deleted"] == 0) {
                        $total_remain -= floatval($value["total_payment_value"]);
                    }
                }
                if ($value["creation_date"] != "") {
                    array_push($tmp, date("Y-m-d", strtotime($value["creation_date"])));
                } else {
                    array_push($tmp, "");
                }
                array_push($tmp, $invoice_id);
                if ($value["ref_payment"] != "") {
                    if ($value["credit_note"] == 0) {
                        array_push($tmp, self::idFormat_customer_payment($value["ref_payment"]));
                    } else {
                        array_push($tmp, self::idFormat_creditnote($value["ref_payment"]));
                    }
                } else {
                    array_push($tmp, "");
                }
                if ($value["deleted"] == 0) {
                    array_push($tmp, $value["payment_note"]);
                } else {
                    array_push($tmp, "<span class='linethrough'>" . $value["payment_note"] . "</span>");
                }
                if ($value["deleted"] == 0) {
                    array_push($tmp, self::global_number_formatter((double) $value["total_invoice_value"], $this->settings_info));
                } else {
                    array_push($tmp, "<span class='linethrough'>" . self::global_number_formatter((double) $value["total_invoice_value"], $this->settings_info) . "</span>");
                }
                if ($value["deleted"] == 0) {
                    if ($value["paid_directly"] == 1) {
                        array_push($tmp, self::global_number_formatter((double) $value["total_invoice_value"], $this->settings_info));
                    } else {
                        array_push($tmp, self::global_number_formatter((double) $value["total_payment_value"], $this->settings_info));
                    }
                } else {
                    array_push($tmp, "<span class='linethrough'>" . self::global_number_formatter((double) $value["total_payment_value"], $this->settings_info) . "</span>");
                }
                if (0 < $value["payment_method"]) {
                    array_push($tmp, $p_method[$value["payment_method"]]);
                } else {
                    array_push($tmp, "");
                }
                if ($cnt < count($stm) - 1) {
                    array_push($tmp, self::global_number_formatter((double) $total_remain, $this->settings_info));
                } else {
                    array_push($tmp, "<span class='final_remain'>" . self::global_number_formatter((double) $total_remain, $this->settings_info) . "</span>");
                }
                array_push($tmp, $value["deleted"]);
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($data_array["data"], $tmp);
                $cnt++;
            }
            echo json_encode($data_array);
        }
    }
    public function add_new_customer()
    {
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["name"] = filter_input(INPUT_POST, "customer_name", self::conversion_php_version_filter());
        $info["company"] = filter_input(INPUT_POST, "company", self::conversion_php_version_filter());
        $info["middle_name"] = filter_input(INPUT_POST, "middle_name", self::conversion_php_version_filter());
        $info["last_name"] = filter_input(INPUT_POST, "last_name", self::conversion_php_version_filter());
        $info["cus_code"] = filter_input(INPUT_POST, "cus_code", self::conversion_php_version_filter());
        $info["phone"] = filter_input(INPUT_POST, "customer_phone", self::conversion_php_version_filter());
        $info["address"] = filter_input(INPUT_POST, "customer_address", self::conversion_php_version_filter());
        $info["address_building"] = filter_input(INPUT_POST, "a_building", self::conversion_php_version_filter());
        $info["address_note"] = filter_input(INPUT_POST, "a_cnote", self::conversion_php_version_filter());
        $info["address_floor"] = filter_input(INPUT_POST, "a_floor", self::conversion_php_version_filter());
        $info["address_street"] = filter_input(INPUT_POST, "a_street", self::conversion_php_version_filter());
        $info["address_city"] = filter_input(INPUT_POST, "a_city", self::conversion_php_version_filter());
        $info["address_area"] = filter_input(INPUT_POST, "a_area", self::conversion_php_version_filter());
        $info["email"] = filter_input(INPUT_POST, "email", self::conversion_php_version_filter());
        $info["vendor_id"] = filter_input(INPUT_POST, "vendor_id", FILTER_SANITIZE_NUMBER_INT);
        $info["connected_to_supplier"] = filter_input(INPUT_POST, "connected_to_supplier", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["connected_to_supplier"])) {
            $info["connected_to_supplier"] = 0;
        }
        if (!isset($info["address_building"])) {
            $info["address_building"] = "";
        }
        if (!isset($info["address_note"])) {
            $info["address_note"] = "";
        }
        if (!isset($info["address_floor"])) {
            $info["address_floor"] = "";
        }
        if (!isset($info["address_street"])) {
            $info["address_street"] = "";
        }
        if (!isset($info["address_city"])) {
            $info["address_city"] = "";
        }
        if (!isset($info["address_area"])) {
            $info["address_area"] = "";
        }
        if (!isset($info["address"])) {
            $info["address"] = "";
        }
        $info["account_nb"] = filter_input(INPUT_POST, "account_nb", self::conversion_php_version_filter());
        if (!isset($info["account_nb"])) {
            $info["account_nb"] = "0";
        }
        $info["reference_id"] = filter_input(INPUT_POST, "reference_id", self::conversion_php_version_filter());
        if (!isset($info["reference_id"])) {
            $info["reference_id"] = "0";
        }
        $info["note"] = filter_input(INPUT_POST, "note", self::conversion_php_version_filter());
        if (!isset($info["note"])) {
            $info["note"] = "";
        }
        $info["customer_type"] = filter_input(INPUT_POST, "customer_type", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["customer_type"])) {
            $info["customer_type"] = 1;
        }
        $info["starting_balance"] = filter_input(INPUT_POST, "customer_starting_balance", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!isset($info["starting_balance"])) {
            $info["starting_balance"] = 0;
        }
        $info["customer_mof"] = filter_input(INPUT_POST, "customer_mof", self::conversion_php_version_filter());
        $info["customer_discount"] = filter_input(INPUT_POST, "customer_discount", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!isset($info["customer_discount"])) {
            $info["customer_discount"] = 0;
        }
        $info["dob"] = filter_input(INPUT_POST, "dob", self::conversion_php_version_filter());
        $info["dob"] = date("Y-m-d H:i:s", strtotime($info["dob"]));
        $info["coi"] = filter_input(INPUT_POST, "coi", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["coi"])) {
            $info["coi"] = 0;
        }
        $info["id_type"] = filter_input(INPUT_POST, "id_type", FILTER_SANITIZE_NUMBER_INT);
        if (!($this->settings_info["enable_advanced_customer_info"] == "1" && isset($info["id_type"]))) {
            $info["id_type"] = 0;
        }
        $info["id_expiry"] = filter_input(INPUT_POST, "id_expiry", self::conversion_php_version_filter());
        if ($this->settings_info["enable_advanced_customer_info"] == "1" && isset($info["id_expiry"]) && $info["id_expiry"] != NULL && 0 < strlen($info["id_expiry"])) {
            $info["id_expiry"] = date("Y-m-d H:i:s", strtotime($info["id_expiry"]));
        } else {
            $info["id_expiry"] = 0;
        }
        $info["id_nb"] = filter_input(INPUT_POST, "id_nb", self::conversion_php_version_filter());
        if (!($this->settings_info["enable_advanced_customer_info"] == "1" && isset($info["id_nb"]))) {
            $info["id_nb"] = 0;
        }
        $info["city"] = filter_input(INPUT_POST, "city", FILTER_SANITIZE_NUMBER_INT);
        if ($this->settings_info["enable_advanced_customer_info"] == "1" && isset($info["city"])) {
            $info["city_id"] = $info["city"];
        } else {
            $info["city_id"] = 0;
        }
        $info["cob"] = filter_input(INPUT_POST, "cob", FILTER_SANITIZE_NUMBER_INT);
        if (!($this->settings_info["enable_advanced_customer_info"] == "1" && isset($info["cob"]))) {
            $info["cob"] = 0;
        }
        $customers = $this->model("customers");
        $info_return = NULL;
        if ($info["id_to_edit"] == 0) {
            $info_return = $customers->addCustomer($info);
        } else {
            $customers->update_customer($info);
        }
        $return = array();
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        if (is_uploaded_file($_FILES["identity_1"]["tmp_name"])) {
            $ext = pathinfo($_FILES["identity_1"]["name"], PATHINFO_EXTENSION);
            $info["identity_name_1"] = $return["id"] . " - " . $info["name"] . " " . $info["middle_name"] . " " . $info["last_name"] . " -1";
            if (is_file("data/ids/" . $info["identity_name_1"] . "." . $ext)) {
                unlink("data/ids/" . $info["identity_name_1"] . "." . $ext);
            }
            $info["identity_1"] = self::uploade_picture($_FILES["identity_1"]["name"], $_FILES["identity_1"]["tmp_name"], $info["identity_name_1"], "ids/");
            $customers->update_identity_1($return["id"], "data/ids/" . $info["identity_name_1"] . "." . $ext, $info["id_to_edit"]);
        }
        if (is_uploaded_file($_FILES["identity_2"]["tmp_name"])) {
            $ext = pathinfo($_FILES["identity_2"]["name"], PATHINFO_EXTENSION);
            $info["identity_name_2"] = $return["id"] . " - " . $info["name"] . " " . $info["middle_name"] . " " . $info["last_name"] . " -2";
            if (is_file("data/ids/" . $info["identity_name_2"] . "." . $ext)) {
                unlink("data/ids/" . $info["identity_name_2"] . "." . $ext);
            }
            $info["identity_2"] = self::uploade_picture($_FILES["identity_2"]["name"], $_FILES["identity_2"]["tmp_name"], $info["identity_name_2"], "ids/");
            $customers->update_identity_2($return["id"], "data/ids/" . $info["identity_name_2"] . "." . $ext, $info["id_to_edit"]);
        }
        self::update_online_customers($this->settings_info);
        echo json_encode($return);
    }
    public function delete_customer_($id_)
    {
        $customers = $this->model("customers");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $customers->delete_customer($id);
        echo json_encode(array());
    }
    public function delete_customer($id_)
    {
        self::giveAccessTo(array(2));
        $customers = $this->model("customers");
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $creditnote = $this->model("creditnote");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $customers->delete_customer($id);
        $customers->delete_customer_balance($id);
        $invoice->delete_customer_invoices($id);
        $invoices = $invoice->getInvoicesOfCustomers($id);
        for ($i = 0; $i < count($invoices); $i++) {
            $invoice->delete_customer_invoice_items($invoices[$i]["id"]);
            $payments->delete_customer_invoice_payments($invoices[$i]["id"]);
            $invoice->delete_customer_invoice_items_returned($invoices[$i]["id"]);
            $creditnote->delete_credit_notes_of_customer($id);
            $invoice->calculate_total_value($invoices[$i]["id"]);
            $invoice->calculate_total_profit_for_invoice($invoices[$i]["id"]);
        }
        $return["status"] = true;
        echo json_encode($return);
    }
    public function getAllCustomers($_p0)
    {
        self::giveAccessTo(array(2));
        $customers = $this->model("customers");
        $user_class = $this->model("user");
        $payments = $this->model("payments");
        $invoice = $this->model("invoice");
        $creditnote = $this->model("creditnote");
        $setting = self::getSettings();
        $info = $customers->getCustomers();
        $users_info = $user_class->getAllUsersEvenDeleted();
        $users_info_array = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_info_array[$users_info[$i]["id"]] = $users_info[$i]["username"];
        }
        $remain_post = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $customers_types = $customers->getCustomersTypes();
        $customers_types_info = array();
        for ($i = 0; $i < count($customers_types); $i++) {
            $customers_types_info[$customers_types[$i]["id"]] = $customers_types[$i]["name"];
        }
        $invoices_info_group = $invoice->getTotalUnpaidGroup();
        $invoices_info_group_ = array();
        for ($i = 0; $i < count($invoices_info_group); $i++) {
            $invoices_info_group_[$invoices_info_group[$i]["customer_id"]] = $invoices_info_group[$i]["sum"];
        }
        $creditnote_sum_group = $creditnote->get_total_sum_creditnote_group();
        $creditnote_sum_group_ = array();
        for ($i = 0; $i < count($creditnote_sum_group); $i++) {
            $creditnote_sum_group_[$creditnote_sum_group[$i]["customer_id"]] = $creditnote_sum_group[$i]["sum"];
        }
        $total_payments_group = $payments->getTotalPaymentForCustomer_group();
        $total_payments_group_ = array();
        for ($i = 0; $i < count($total_payments_group); $i++) {
            $total_payments_group_[$total_payments_group[$i]["customer_id"]] = $total_payments_group[$i]["sum"];
        }
        $data_array["data"] = array();
        $data_array["remain"] = 0;
        $debit = 0;
        $credit = 0;
        for ($i = 0; $i < count($info); $i++) {
            $rm = $invoices_info_group_[$info[$i]["id"]] + $info[$i]["starting_balance"] - $total_payments_group_[$info[$i]["id"]] - $creditnote_sum_group_[$info[$i]["id"]];
            $debit += $invoices_info_group_[$info[$i]["id"]] + $info[$i]["starting_balance"];
            $credit += $total_payments_group_[$info[$i]["id"]] + $creditnote_sum_group_[$info[$i]["id"]];
            if ($remain_post == 0 || $remain_post == 1 && 0 < $rm) {
                $tmp = array();
                array_push($tmp, self::idFormat_customer($info[$i]["id"]));
                array_push($tmp, $info[$i]["name"] . " " . $info[$i]["middle_name"] . " " . $info[$i]["last_name"]);
                array_push($tmp, $customers_types_info[$info[$i]["customer_type"]]);
                array_push($tmp, $info[$i]["phone"]);
                array_push($tmp, $info[$i]["address"]);
                array_push($tmp, self::global_number_formatter($info[$i]["starting_balance"], $this->settings_info));
                if (!isset($invoices_info_group_[$info[$i]["id"]])) {
                    $invoices_info_group_[$info[$i]["id"]] = 0;
                }
                if (!isset($total_payments_group_[$info[$i]["id"]])) {
                    $total_payments_group_[$info[$i]["id"]] = 0;
                }
                if (!isset($creditnote_sum_group_[$info[$i]["id"]])) {
                    $creditnote_sum_group_[$info[$i]["id"]] = 0;
                }
                array_push($tmp, self::global_number_formatter($invoices_info_group_[$info[$i]["id"]] - $total_payments_group_[$info[$i]["id"]] + $info[$i]["starting_balance"] - $creditnote_sum_group_[$info[$i]["id"]], $this->settings_info));
                array_push($tmp, $info[$i]["mof"]);
                array_push($tmp, floor($info[$i]["discount"]) . " %");
                array_push($tmp, $users_info_array[$info[$i]["created_by"]]);
                array_push($tmp, "");
                array_push($data_array["data"], $tmp);
            }
        }
        $data_array["debit"] = self::global_number_formatter($debit, $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["credit"] = self::global_number_formatter($credit, $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["balance"] = self::global_number_formatter(abs($debit - $credit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        echo json_encode($data_array);
    }
    public function getCustomers()
    {
        self::giveAccessTo(array(2, 4));
        $customers = $this->model("customers");
        $customersInfo = $customers->getCustomers();
        $data = array();
        for ($i = 0; $i < count($customersInfo); $i++) {
            $data[$i]["id"] = $customersInfo[$i]["id"];
            $data[$i]["name"] = $customersInfo[$i]["name"] . " " . $customersInfo[$i]["middle_name"] . " " . $customersInfo[$i]["last_name"];
        }
        echo json_encode($data);
    }
    public function getCustomersToPay()
    {
        $customers = $this->model("customers");
        if ($this->settings_info["clients_show_only_by_users_created"] == 1) {
            $customersInfo = $customers->getCustomersToPay_l();
        } else {
            $customersInfo = $customers->getCustomersToPay();
        }
        $data = array();
        for ($i = 0; $i < count($customersInfo); $i++) {
            $data[$i]["id"] = $customersInfo[$i]["id"];
            $data[$i]["name"] = $customersInfo[$i]["name"];
            $data[$i]["middle_name"] = $customersInfo[$i]["middle_name"];
            $data[$i]["last_name"] = $customersInfo[$i]["last_name"];
            $data[$i]["phone"] = $customersInfo[$i]["phone"];
        }
        echo json_encode($data);
    }
    public function print_identities($id_)
    {
        self::giveAccessTo(array(2));
        $customers = $this->model("customers");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $data = array();
        $data["info"] = $customers->getCustomersById($id);
        $this->view("printing/identities", $data);
    }
    public function print_identities_1($id_)
    {
        self::giveAccessTo(array(2));
        $customers = $this->model("customers");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $data = array();
        $data["info"] = $customers->getCustomersById($id);
        $this->view("printing/identities_1", $data);
    }
    public function print_identities_2($id_)
    {
        self::giveAccessTo(array(2));
        $customers = $this->model("customers");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $data = array();
        $data["info"] = $customers->getCustomersById($id);
        $this->view("printing/identities_2", $data);
    }
    public function getCustomerInfoById($id_)
    {
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        $info = $customers->getCustomersById($id);
        echo json_encode($info);
    }
    public function getCustomersById($id_)
    {
        $customers = $this->model("customers");
        $countries = $this->model("countries");
        $countries_info = $countries->getCountriesEvenDeleted();
        $ctr_info = array();
        for ($i = 0; $i < count($countries_info); $i++) {
            $ctr_info[$countries_info[$i]["id"]] = $countries_info[$i];
        }
        $identities_types = $customers->getIdentitiesTypesEvenDeleted();
        $identities_info = array();
        for ($i = 0; $i < count($identities_types); $i++) {
            $identities_info[$identities_types[$i]["id"]] = $identities_types[$i];
        }
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $customersInfo = $customers->getCustomersById($id);
        $data = array();
        for ($i = 0; $i < count($customersInfo); $i++) {
            $data[$i]["id"] = $customersInfo[$i]["id"];
            $data[$i]["name"] = $customersInfo[$i]["name"];
            $data[$i]["middle_name"] = $customersInfo[$i]["middle_name"];
            $data[$i]["last_name"] = $customersInfo[$i]["last_name"];
            $data[$i]["phone"] = $customersInfo[$i]["phone"];
            $data[$i]["code"] = $customersInfo[$i]["code"];
            $data[$i]["created_by"] = $customersInfo[$i]["created_by"];
            $data[$i]["address"] = $customersInfo[$i]["address"];
            $data[$i]["company"] = $customersInfo[$i]["company"];
            $data[$i]["email"] = $customersInfo[$i]["email"];
            $data[$i]["connected_to_supplier"] = $customersInfo[$i]["connected_to_supplier"];
            $data[$i]["address_area"] = $customersInfo[$i]["address_area"];
            $data[$i]["address_city"] = $customersInfo[$i]["address_city"];
            $data[$i]["address_street"] = $customersInfo[$i]["address_street"];
            $data[$i]["address_floor"] = $customersInfo[$i]["address_floor"];
            $data[$i]["address_note"] = $customersInfo[$i]["address_note"];
            $data[$i]["address_building"] = $customersInfo[$i]["address_building"];
            $data[$i]["customer_type"] = $customersInfo[$i]["customer_type"];
            $data[$i]["starting_balance"] = $customersInfo[$i]["starting_balance"];
            $data[$i]["mof"] = $customersInfo[$i]["mof"];
            $data[$i]["discount"] = $customersInfo[$i]["discount"];
            $data[$i]["account_nb"] = $customersInfo[$i]["account_nb"];
            $data[$i]["reference_id"] = $customersInfo[$i]["reference_id"];
            $data[$i]["note"] = $customersInfo[$i]["note"];
            $date = date_create($customersInfo[$i]["dob"]);
            $data[$i]["dob"] = date_format($date, "d-m-Y");
            $data[$i]["id_type"] = $customersInfo[$i]["id_type"];
            $data[$i]["id_type_name"] = "";
            if (0 < $customersInfo[$i]["id_type"]) {
                if ($customersInfo[$i]["id_nb"] != "0" && $customersInfo[$i]["id_nb"] != "") {
                    $data[$i]["id_type_name"] = $identities_info[$customersInfo[$i]["id_type"]]["name"];
                } else {
                    $data[$i]["id_type_name"] = "";
                }
            }
            if ($customersInfo[$i]["id_expiry"] == NULL) {
                $data[$i]["id_expiry"] = NULL;
            } else {
                $date_exp = date_create($customersInfo[$i]["id_expiry"]);
                $data[$i]["id_expiry"] = date_format($date_exp, "d-m-Y");
            }
            $data[$i]["id_nb"] = $customersInfo[$i]["id_nb"];
            $data[$i]["cob"] = $customersInfo[$i]["cob"];
            $data[$i]["cob_name"] = "";
            if (0 < $customersInfo[$i]["cob"] && $customersInfo[$i]["id_nb"] != "" && $customersInfo[$i]["id_nb"] != "0") {
                $data[$i]["cob_name"] = $ctr_info[$customersInfo[$i]["cob"]]["country_name"];
            }
            if (file_exists($customersInfo[$i]["identity_pic_1"])) {
                $data[$i]["identity_pic_1"] = $customersInfo[$i]["identity_pic_1"];
            } else {
                $data[$i]["identity_pic_1"] = -1;
            }
            if (file_exists($customersInfo[$i]["identity_pic_2"])) {
                $data[$i]["identity_pic_2"] = $customersInfo[$i]["identity_pic_2"];
            } else {
                $data[$i]["identity_pic_2"] = -1;
            }
            $data[$i]["coi"] = $customersInfo[$i]["coi"];
            $data[$i]["coi_name"] = "";
            if (0 < $customersInfo[$i]["coi"] && $customersInfo[$i]["id_nb"] != "" && $customersInfo[$i]["id_nb"] != "0") {
                $data[$i]["coi_name"] = $ctr_info[$customersInfo[$i]["coi"]]["country_name"];
            }
            $data[$i]["expired"] = 0;
            if ($customersInfo[$i]["id_expiry"] != NULL) {
                $now = time();
                $target = strtotime($customersInfo[$i]["id_expiry"]);
                if ($target + 86400 - $now < 0) {
                    $data[$i]["expired"] = 1;
                }
            }
            $info_location = $countries->getAllInfoLocation($customersInfo[$i]["city_id"]);
            if (0 < $customersInfo[$i]["city_id"]) {
                $data[$i]["city_id"] = $info_location[0]["city_id"];
                $data[$i]["district_id"] = $info_location[0]["district_id"];
                $data[$i]["area_id"] = $info_location[0]["area_id"];
                $data[$i]["country_id"] = $info_location[0]["country_id"];
                $data[$i]["country_name"] = $info_location[0]["country_name"];
                $data[$i]["city_name"] = $info_location[0]["city_name"];
            } else {
                $data[$i]["city_id"] = 0;
                $data[$i]["district_id"] = 0;
                $data[$i]["area_id"] = 0;
                $data[$i]["country_id"] = 0;
                $data[$i]["country_name"] = "";
                $data[$i]["city_name"] = "";
            }
        }
        echo json_encode($data);
    }
    public function payments()
    {
        self::giveAccessTo(array(2));
        $this->view("customers_payments");
    }
    public function search($_search, $_page)
    {
        $search = filter_var($_search, self::conversion_php_version_filter());
        $page = filter_var($_page, FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        if ($_SESSION["centralize"] == 0) {
            $results = $customers->search($search, $page, 20);
        } else {
            $results = $customers->search_centralize($search, $page, 20);
        }
        $return = array();
        $return["results"] = array();
        $index = 0;
        foreach ($results as $result) {
            $return["results"][$index] = array("id" => $result["id"], "text" => $result["name"] . " " . $result["middle_name"] . " " . $result["last_name"] . " " . $result["phone"], "type" => $result["customer_type"]);
            $index++;
        }
        if (count($results) == 20) {
            if ($_SESSION["centralize"] == 0) {
                $return["pagination"]["more"] = $customers->search($search, $page, 20, true);
            } else {
                $return["pagination"]["more"] = $customers->search_centralize($search, $page, 20, true);
            }
        } else {
            $return["pagination"]["more"] = false;
        }
        echo json_encode($return);
    }
    public function get_client_info($_client_id)
    {
        $customers = $this->model("customers");
        $client_id = filter_var($_client_id, self::conversion_php_version_filter());
        $client_info = $customers->getCustomersById($client_id);
        $return_info = array();
        if (0 < Count($client_info)) {
            $return_info["client_info"] = $client_id . " | " . $client_info[0]["name"] . " " . $client_info[0]["middle_name"] . " " . $client_info[0]["last_name"] . " - Phone #" . $client_info[0]["phone"];
            $return_info["client_id"] = $client_id;
            $return_info["client_data"] = $client_info;
        }
        echo json_encode($return_info);
    }
    public function add_customer()
    {
        $customer = $this->model("customers");
        $result = array();
        $result["client_data"] = array();
        $result["error"] = 1;
        if (isset($_POST["optic_customer_id"])) {
            $mysql_array = array();
            foreach ($_POST as $key => $val) {
                $exp = explode("optic_customer_", $key);
                $mysql_array[$exp[1]] = $val;
            }
            $old_data = $customer->getCustomersById($_POST["optic_customer_id"]);
            $result_client = $customer->update_client_info($mysql_array);
            if (0 < $result_client) {
                $client_info = $customer->getCustomersById($_POST["optic_customer_id"]);
                $result["client_data"] = $customer->getCustomersById($_POST["optic_customer_id"]);
                $result["client_info"] = $client_info[0]["id"] . " | " . $client_info[0]["name"] . " " . $client_info[0]["middle_name"] . " " . $client_info[0]["last_name"] . " - Phone #" . $client_info[0]["phone"];
                $description = "";
                $description .= $customer->prepare_log_field("First name: ", $old_data[0]["name"], $_POST["optic_customer_name"]);
                $description .= $customer->prepare_log_field("Middle name: ", $old_data[0]["middle_name"], $_POST["optic_customer_middle_name"]);
                $description .= $customer->prepare_log_field("Last name: ", $old_data[0]["last_name"], $_POST["optic_customer_last_name"]);
                $description .= $customer->prepare_log_field("Phone: ", $old_data[0]["phone"], $_POST["optic_customer_phone"]);
                $description .= $customer->prepare_log_field("Address: ", $old_data[0]["address"], $_POST["optic_customer_address"]);
                $description .= $customer->prepare_log_field("Note: ", $old_data[0]["note"], $_POST["optic_customer_note"]);
                $description .= $customer->prepare_log_field("Pd: ", $old_data[0]["pd"], $_POST["optic_customer_pd"]);
                $description .= $customer->prepare_log_field("Doctor: ", $old_data[0]["doctor"], $_POST["optic_customer_doctor"]);
                $customer->customers_logs("updated", $description, $_POST["optic_customer_id"]);
                $result["error"] = 0;
            }
            echo json_encode($result);
            return NULL;
        } else {
            $mysql_array = array();
            $mysql_array["col"] = array();
            $mysql_array["val"] = array();
            foreach ($_POST as $key => $val) {
                $val = filter_var($val, self::conversion_php_version_filter());
                $exp = explode("optic_customer_", $key);
                array_push($mysql_array["col"], $exp[1]);
                array_push($mysql_array["val"], $val);
            }
            $result_client_id = $customer->save_customer_info($mysql_array);
            if (0 < $result_client_id) {
                $customer->customers_logs("created", "", $result_client_id);
                $client_info = $customer->getCustomersById($result_client_id);
                $result["client_info"] = $client_info[0]["id"] . " | " . $client_info[0]["name"] . " " . $client_info[0]["middle_name"] . " " . $client_info[0]["last_name"] . " - Phone #" . $client_info[0]["phone"];
                $result["client_data"] = $client_info;
                $result["error"] = 0;
            }
            echo json_encode($result);
        }
    }
}

?>