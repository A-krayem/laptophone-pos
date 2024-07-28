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
class suppliers extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo(array(2, 4));
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function _default()
    {
        self::giveAccessTo();
        $data = $this->settings_info;
        $this->view("suppliers", $data);
    }
    public function overview()
    {
        self::giveAccessTo();
        $data = $this->settings_info;
        $this->view("suppliers_overview", $data);
    }
    public function print_supplier_statement($supplier_id, $currency, $daterange)
    {
        $suppliers = $this->model("suppliers");
        $cashbox = $this->model("cashbox");
        $user = $this->model("user");
        $data = array();
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($daterange == "today") {
            $date_range[0] = date("Y-m-d");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" - ", $daterange);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
        }
        $data["info"] = $suppliers->get_balances_suppliers_details($supplier_id, $currency, $date_range);
        $data["cashboxes"] = array();
        $cashboxes_info = $cashbox->get_all_cashboxes_employee();
        for ($i = 0; $i < count($cashboxes_info); $i++) {
            $data["cashboxes"][$cashboxes_info[$i]["id"]] = $cashboxes_info[$i]["username"];
        }
        $data["users"] = array();
        $users_info = $user->getAllUsersEvenDeleted();
        for ($i = 0; $i < count($users_info); $i++) {
            $data["users"][$users_info[$i]["id"]] = $users_info[$i]["username"];
        }
        self::__USORT_TIMESTAMP($data["info"]);
        if ($suppliers->check_if_start_date_equal_starting_balance_date_supplier($date_range[0], $supplier_id) == false) {
            $data["brought_balance_flag"] = -1;
        } else {
            $data["brought_balance_flag"] = 0;
        }
        $data["brought_balance"] = $suppliers->get_balances_suppliers($currency, $supplier_id, $daterange, 0, 0);
        $data["shop_name"] = $this->settings_info["shop_name"];
        $data["settings"] = $this->settings_info;
        $data["supplier"] = $suppliers->get_supplier_by_id($supplier_id);
        $data["start_date_supplier_days"] = $suppliers->start_date_supplier($supplier_id, $currency);
        $data["self"] = $this;
        $this->view("printing/supplier_stmt", $data);
    }
    public function suppliers_payments()
    {
        self::giveAccessTo();
        $this->view("suppliers_payments");
    }
    public function get_suppliers_payment_by_id($_id)
    {
        self::giveAccessTo(array(2));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $payments = $this->model("payments");
        $result = $payments->getSupplierPaymentById($id);
        echo json_encode($result);
    }
    public function suppliers_statement($_sup_id)
    {
        self::giveAccessTo();
        $sup_id = filter_var($_sup_id, FILTER_SANITIZE_NUMBER_INT);
        $data["sup_id"] = $sup_id;
        $this->view("suppliers_statement", $data);
    }
    public function suppliers_statement_newversion($_sup_id)
    {
        self::giveAccessTo();
        $currency = $this->model("currency");
        $sup_id = filter_var($_sup_id, FILTER_SANITIZE_NUMBER_INT);
        $data["sup_id"] = $sup_id;
        $data["currencies"] = $currency->getAllActiveCurrencies();
        $this->view("suppliers_statement_newversion", $data);
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
    public function add_supplier_payment_new()
    {
        self::giveAccessTo(array(2, 4));
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["payment_method"] = 1;
        $info["supplier_id"] = filter_input(INPUT_POST, "supplier_id", FILTER_SANITIZE_NUMBER_INT);
        $info["payment_note"] = filter_input(INPUT_POST, "payment_note", self::conversion_php_version_filter());
        $info["voucher_nb"] = "";
        $info["payment_currency"] = 1;
        $info["reference"] = "";
        $info["payment_owner"] = "";
        $info["currency_rate_value"] = $this->settings_info["usdlbp_rate"];
        $info["currency_rate"] = $this->settings_info["usdlbp_rate"];
        $info["payment_date"] = filter_input(INPUT_POST, "payment_date", self::conversion_php_version_filter());
        $info["payment_value"] = filter_input(INPUT_POST, "payment_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["bank_source"] = 0;
        $info["invoice_order_id"] = "NULL";
        if (isset($_SESSION["cashbox_id"])) {
            $info["cashbox_id"] = $_SESSION["cashbox_id"];
        } else {
            $info["cashbox_id"] = 0;
        }
        $info["cash_usd_to_return"] = filter_input(INPUT_POST, "r_cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["cash_lbp_to_return"] = filter_input(INPUT_POST, "r_cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["returned_cash_lbp"] = filter_input(INPUT_POST, "r_cash_lbp_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["returned_cash_usd"] = filter_input(INPUT_POST, "r_cash_usd_action", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["cash_lbp_in"] = filter_input(INPUT_POST, "cash_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["cash_usd_in"] = filter_input(INPUT_POST, "cash_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["rate"] = $this->settings_info["usdlbp_rate"];
        if (!isset($info["cash_usd_to_return"])) {
            $info["cash_usd_to_return"] = 0;
        }
        if (!isset($info["cash_lbp_to_return"])) {
            $info["cash_lbp_to_return"] = 0;
        }
        if (!isset($info["returned_cash_lbp"])) {
            $info["returned_cash_lbp"] = 0;
        }
        if (!isset($info["returned_cash_usd"])) {
            $info["returned_cash_usd"] = 0;
        }
        if (!isset($info["cash_lbp_in"])) {
            $info["cash_lbp_in"] = 0;
        }
        if (!isset($info["cash_usd_in"])) {
            $info["cash_usd_in"] = 0;
        }
        if ($info["id_to_edit"] == 0) {
            $id = $payments->add_supplier_payment_new($info);
            if (isset($_SESSION["cashbox_id"])) {
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
                $suppliers = $this->model("suppliers");
                $supplier_info = $suppliers->get_supplier_by_id($info["supplier_id"]);
                $info_tel = array();
                $info_tel["message"] = "<strong>Supplier Debt Payment</strong> \n";
                $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                $info_tel["message"] .= "<strong>Supplier Name:</strong> " . $supplier_info[0]["name"] . " \n";
                $info_tel["message"] .= "<strong>Payment Amount:</strong> " . $info["payment_value"] . " USD \n";
                $info_tel["message"] .= "<strong>Note:</strong> " . $info["payment_note"] . " \n";
                self::send_to_telegram($info_tel, 1);
            }
        } else {
            $id = $info["id_to_edit"];
        }
        echo json_encode(array($info["supplier_id"]));
    }
    public function add_supplier_payment()
    {
        self::giveAccessTo(array(2, 4));
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $currency = $this->model("currency");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["payment_method"] = filter_input(INPUT_POST, "payment_method", FILTER_SANITIZE_NUMBER_INT);
        $info["supplier_id"] = filter_input(INPUT_POST, "supplier_id", FILTER_SANITIZE_NUMBER_INT);
        $info["payment_note"] = filter_input(INPUT_POST, "payment_note", self::conversion_php_version_filter());
        $info["voucher_nb"] = filter_input(INPUT_POST, "voucher_nb", self::conversion_php_version_filter());
        $info["payment_currency"] = filter_input(INPUT_POST, "payment_currency", FILTER_SANITIZE_NUMBER_INT);
        $info["reference"] = filter_input(INPUT_POST, "reference", self::conversion_php_version_filter());
        $info["payment_owner"] = filter_input(INPUT_POST, "payment_owner", self::conversion_php_version_filter());
        $info["currency_rate_value"] = filter_input(INPUT_POST, "currency_rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $currencies = $currency->getAllCurrencies();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        if ($info["payment_currency"] == $currency_default_id) {
            $info["currency_rate"] = 1;
        } else {
            if ($currency_default_id == 1 && $info["payment_currency"] == 2) {
                $info["currency_rate"] = 1 / $info["currency_rate_value"];
            } else {
                if ($currency_default_id == 2 && $info["payment_currency"] == 1) {
                    $info["currency_rate"] = $info["currency_rate_value"];
                }
            }
        }
        $info["payment_date"] = filter_input(INPUT_POST, "payment_date", self::conversion_php_version_filter());
        $info["payment_value"] = filter_input(INPUT_POST, "payment_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["bank_source"] = filter_input(INPUT_POST, "bank_source", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["bank_source"])) {
            $info["bank_source"] = 0;
        }
        if ($info["payment_method"] == 1) {
            $info["bank_source"] = 0;
        }
        $info["invoice_order_id"] = filter_input(INPUT_POST, "invoice_order_id", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["invoice_order_id"])) {
            $info["invoice_order_id"] = "NULL";
        }
        if (isset($_SESSION["cashbox_id"])) {
            $info["cashbox_id"] = $_SESSION["cashbox_id"];
        } else {
            $info["cashbox_id"] = 0;
        }
        $info["id_to_edit"] = "null" || !isset($info["id_to_edit"]);
        if ($info["id_to_edit"] == 0 || $info["id_to_edit"]) {
            $id = $payments->add_supplier_payment($info);
            if (isset($_SESSION["cashbox_id"])) {
                $cashbox->updateCashBox($_SESSION["cashbox_id"]);
            }
        } else {
            $id = $info["id_to_edit"];
        }
        if (is_uploaded_file($_FILES["cheque_picture"]["tmp_name"])) {
            $supplier_id_for_cheque = filter_input(INPUT_POST, "supplier_id_for_cheque", FILTER_SANITIZE_NUMBER_INT);
            $supplier_id_for_cheque_name = self::uploade_picture($_FILES["cheque_picture"]["name"], $_FILES["cheque_picture"]["tmp_name"], $supplier_id_for_cheque, "sup_p_pic/");
            if ($supplier_id_for_cheque_name != NULL && $supplier_id_for_cheque_name != "" && 0 < strlen($supplier_id_for_cheque_name)) {
                $payments->update_cheque_picture_name_fir_supplier_payment($id, $supplier_id_for_cheque_name);
            }
        }
        echo json_encode(array($info["supplier_id"]));
    }
    public function delete_cheque_picture($id_)
    {
        self::giveAccessTo();
        $payments = $this->model("payments");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $payment_info = $payments->getSupplierPaymentById($id);
        if (file_exists($payment_info[0]["payment_picture"])) {
            $tmp = explode("/", $payment_info[0]["payment_picture"]);
            rename($payment_info[0]["payment_picture"], $tmp[0] . "/" . $tmp[1] . "/trash/" . time() . "_" . $tmp[2]);
            $payments->delete_cheque_picture($id);
        }
        echo json_encode(array());
    }
    public function getInvoicesOfSupplier($id_)
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $data = $suppliers->getInvoicesOfSupplier($id);
        echo json_encode($data);
    }
    public function delete_suppliers($id_)
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        if ($suppliers->supplier_payment_exist($id) == 0) {
            $return["status"] = $suppliers->delete_suppliers($id);
        } else {
            $return["status"] = -1;
        }
        echo json_encode($return);
    }
    public function add_new_supplier()
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $phones = $this->model("phones");
        $info = array();
        $info["sup_name"] = filter_input(INPUT_POST, "sup_name", self::conversion_php_version_filter());
        $info["sup_contact"] = filter_input(INPUT_POST, "sup_contact", self::conversion_php_version_filter());
        $info["sup_country"] = filter_input(INPUT_POST, "sup_country", FILTER_SANITIZE_NUMBER_INT);
        $info["sup_adr"] = filter_input(INPUT_POST, "sup_adr", self::conversion_php_version_filter());
        $info["deb_cred"] = filter_input(INPUT_POST, "deb_cred", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["deb_cred"])) {
            $info["deb_cred"] = 0;
        }
        $info["email"] = filter_input(INPUT_POST, "sup_email", self::conversion_php_version_filter());
        $info["sup_phone"] = filter_input(INPUT_POST, "sup_phone", self::conversion_php_version_filter());
        $info["starting_balance"] = filter_input(INPUT_POST, "sup_starting_balance", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["user_id"] = $_SESSION["id"];
        $info["usd_starting_balance"] = filter_input(INPUT_POST, "sup_starting_balance_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["lbp_starting_balance"] = filter_input(INPUT_POST, "sup_starting_balance_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["deb_cred_lbp"] = filter_input(INPUT_POST, "deb_cred_lbp", FILTER_SANITIZE_NUMBER_INT);
        $info["deb_cred_usd"] = filter_input(INPUT_POST, "deb_cred_usd", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["deb_cred_lbp"])) {
            $info["deb_cred_lbp"] = 0;
        }
        if (!isset($info["deb_cred_usd"])) {
            $info["deb_cred_usd"] = 0;
        }
        if ($info["deb_cred_lbp"] == 2) {
            $info["lbp_starting_balance"] = 0 - $info["lbp_starting_balance"];
        }
        if ($info["deb_cred_usd"] == 2) {
            $info["usd_starting_balance"] = 0 - $info["usd_starting_balance"];
        }
        $id = $suppliers->addSupplier($info);
        $phones->add_phones($info, $id);
        $return = array();
        $return["id"] = $id;
        $return["sup_name"] = $info["sup_name"];
        echo json_encode($return);
    }
    public function get_supplier_details($id_sup)
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $phones = $this->model("phones");
        $id = filter_var($id_sup, FILTER_SANITIZE_NUMBER_INT);
        $info = $suppliers->getSupplier($id);
        $info_to_send = array();
        $info_to_send["sup"]["name"] = $info[0]["name"];
        $info_to_send["sup"]["contact_name"] = $info[0]["contact_name"];
        $contacts = $phones->getSupplierContacts($id);
        $info_to_send["sup"]["phone"] = $contacts[0]["phone_number"];
        echo json_encode($info_to_send);
    }
    public function get_supplier($id_sup)
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $phones = $this->model("phones");
        $id = filter_var($id_sup, FILTER_SANITIZE_NUMBER_INT);
        $info = $suppliers->getSupplier($id);
        $contacts = $phones->getSupplierContacts($id);
        if (0 < count($contacts)) {
            $info[0]["phone"] = $contacts[0]["phone_number"];
        } else {
            $info[0]["phone"] = "";
        }
        echo json_encode($info);
    }
    public function update_supplier()
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $info = array();
        $info["id"] = filter_input(INPUT_POST, "id_to_edit", self::conversion_php_version_filter());
        $info["sup_name"] = filter_input(INPUT_POST, "sup_name", self::conversion_php_version_filter());
        $info["sup_contact"] = filter_input(INPUT_POST, "sup_contact", self::conversion_php_version_filter());
        $info["sup_country"] = filter_input(INPUT_POST, "sup_country", FILTER_SANITIZE_NUMBER_INT);
        $info["sup_adr"] = filter_input(INPUT_POST, "sup_adr", self::conversion_php_version_filter());
        $info["sup_phone"] = filter_input(INPUT_POST, "sup_phone", self::conversion_php_version_filter());
        $info["email"] = filter_input(INPUT_POST, "sup_email", self::conversion_php_version_filter());
        $info["starting_balance"] = filter_input(INPUT_POST, "sup_starting_balance", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["deb_cred"] = filter_input(INPUT_POST, "deb_cred", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["deb_cred"])) {
            $info["deb_cred"] = 0;
        }
        $info["usd_starting_balance"] = filter_input(INPUT_POST, "sup_starting_balance_usd", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["lbp_starting_balance"] = filter_input(INPUT_POST, "sup_starting_balance_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["deb_cred_lbp"] = filter_input(INPUT_POST, "deb_cred_lbp", FILTER_SANITIZE_NUMBER_INT);
        $info["deb_cred_usd"] = filter_input(INPUT_POST, "deb_cred_usd", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["deb_cred_lbp"])) {
            $info["deb_cred_lbp"] = 0;
        }
        if (!isset($info["deb_cred_usd"])) {
            $info["deb_cred_usd"] = 0;
        }
        if ($info["deb_cred_lbp"] == 2) {
            $info["lbp_starting_balance"] = 0 - $info["lbp_starting_balance"];
        }
        if ($info["deb_cred_usd"] == 2) {
            $info["usd_starting_balance"] = 0 - $info["usd_starting_balance"];
        }
        $suppliers->updateSupplier($info);
        $data = array();
        $data["id"] = $info["id"];
        echo json_encode($data);
    }
    public function payment_done($_payment_id)
    {
        self::giveAccessTo(array(2));
        $payment_id = filter_var($_payment_id, FILTER_SANITIZE_NUMBER_INT);
        $payments = $this->model("payments");
        $payments->payment_supplier_done($payment_id);
        echo json_encode(array());
    }
    public function delete_supplier_payment($_payment_id)
    {
        self::giveAccessTo(array(2));
        $payment_id = filter_var($_payment_id, FILTER_SANITIZE_NUMBER_INT);
        $suppliers = $this->model("suppliers");
        $cashbox = $this->model("cashbox");
        $suppliers->delete_supplier_payment($payment_id);
        if (isset($_SESSION["cashbox_id"])) {
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        }
        echo json_encode(array());
    }
    public function get_supplier_statement_new_version($p_0)
    {
        $suppliers = $this->model("suppliers");
        $suppliers_info = $suppliers->getSuppliers();
        $all = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $all[$i]["id"] = $suppliers_info[$i]["id"];
            $all[$i]["name"] = $suppliers_info[$i]["name"];
            $all[$i]["contact_name"] = $suppliers_info[$i]["contact_name"];
            $all[$i]["currency"] = 1;
        }
        $data_array["data"] = array();
        $date_range[0] = "all";
        $date_range[1] = "all";
        for ($i = 0; $i < count($all); $i++) {
            $tmp = array();
            array_push($tmp, $all[$i]["id"]);
            array_push($tmp, $all[$i]["name"]);
            array_push($tmp, $all[$i]["contact_name"]);
            $contacts = $suppliers->getSupplierContacts($all[$i]["id"]);
            if (0 < count($contacts)) {
                array_push($tmp, $contacts[0]["phone_number"]);
            } else {
                array_push($tmp, "-");
            }
            $t_usd = $suppliers->get_balances_suppliers(1, $all[$i]["id"], $date_range, 0, 0);
            array_push($tmp, self::global_number_formatter($t_usd, $this->settings_info));
            $t_lbp = $suppliers->get_balances_suppliers(2, $all[$i]["id"], $date_range, 0, 0);
            array_push($tmp, number_format($t_lbp, 0));
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_supplier_statement($_supplier_id)
    {
        self::giveAccessTo(array(2));
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $data_array["data"] = array();
        if ($supplier_id == 0) {
            echo json_encode($data_array);
        } else {
            $suppliers = $this->model("suppliers");
            $payments = $this->model("payments");
            $currency = $this->model("currency");
            $debit_note = $this->model("debitnote");
            $settings = $this->model("settings");
            $debit_notes = $debit_note->get_debit_note_for_suppliers($supplier_id);
            $suppliers_all_invoices = $suppliers->getAllInvoicesOfSupplier($supplier_id);
            $suppliers_all_payments = $payments->getAllPaymentForSupplier($supplier_id);
            $currencies = $currency->getAllCurrencies();
            $currencies_info = array();
            $currency_default_id = 0;
            for ($i = 0; $i < count($currencies); $i++) {
                $currencies_info[$currencies[$i]["id"]] = $currencies[$i];
                if ($currencies[$i]["system_default"] == 1) {
                    $currency_default_id = $currencies[$i]["id"];
                }
            }
            $payment_types = $settings->get_payment_method();
            $payment_methods = array();
            for ($i = 0; $i < count($payment_types); $i++) {
                $payment_methods[$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
            }
            $supplier_info = $suppliers->getSupplier($supplier_id);
            $stm = array();
            $array_index = 0;
            $stm[$array_index] = array("creation_date" => "<b>Starting Debts</b>", "invoice_id" => "", "payment_note" => "", "total_invoice_value" => $supplier_info[0]["starting_balance"], "total_payment_value" => "", "credit" => 1, "deleted" => 0, "ref_payment" => "", "debit_note" => 0, "cur_id" => "", "payment_m" => "", "invoice_reference" => "", "created_by" => 0);
            $array_index++;
            for ($i = 0; $i < count($suppliers_all_invoices); $i++) {
                if ($suppliers_all_invoices[$i]["currency_id"] != $currency_default_id) {
                    $suppliers_all_invoices[$i]["total"] = $suppliers_all_invoices[$i]["total"] * $suppliers_all_invoices[$i]["cur_rate"];
                }
                $stm[$array_index] = array("timestamp" => strtotime($suppliers_all_invoices[$i]["creation_date"]), "creation_date" => $suppliers_all_invoices[$i]["creation_date"], "invoice_id" => $suppliers_all_invoices[$i]["id"], "payment_note" => "", "total_invoice_value" => $suppliers_all_invoices[$i]["total"], "total_payment_value" => "", "credit" => 1, "deleted" => 0, "ref_payment" => "", "cur_id" => $currencies_info[$suppliers_all_invoices[$i]["currency_id"]]["name"] . " -> " . number_format($suppliers_all_invoices[$i]["cur_rate"], 5), "payment_m" => "", "invoice_reference" => $suppliers_all_invoices[$i]["invoice_reference"], "created_by" => 0);
                $array_index++;
            }
            for ($i = 0; $i < count($suppliers_all_payments); $i++) {
                $stm[$array_index] = array("timestamp" => strtotime($suppliers_all_payments[$i]["creation_date"]), "creation_date" => $suppliers_all_payments[$i]["creation_date"], "invoice_id" => "", "payment_note" => $suppliers_all_payments[$i]["payment_note"], "total_invoice_value" => "", "total_payment_value" => $suppliers_all_payments[$i]["payment_value"] * $suppliers_all_payments[$i]["currency_rate"], "credit" => 0, "deleted" => $suppliers_all_payments[$i]["deleted"], "ref_payment" => $suppliers_all_payments[$i]["id"], "debit_note" => 0, "cur_id" => "", "payment_m" => $payment_methods[$suppliers_all_payments[$i]["payment_method"]], "invoice_reference" => "", "created_by" => $suppliers_all_payments[$i]["cashbox_id"]);
                $array_index++;
            }
            for ($i = 0; $i < count($debit_notes); $i++) {
                if ($debit_notes[$i]["payment_currency"] != $currency_default_id) {
                    $debit_notes[$i]["debit_value"] = $debit_notes[$i]["debit_value"] * $debit_notes[$i]["currency_rate"];
                }
                $stm[$array_index] = array("timestamp" => strtotime($debit_notes[$i]["creation_date"]), "creation_date" => $debit_notes[$i]["creation_date"], "invoice_id" => "", "payment_note" => "-", "total_invoice_value" => "", "total_payment_value" => $debit_notes[$i]["debit_value"], "credit" => 0, "deleted" => $debit_notes[$i]["deleted"], "ref_payment" => $debit_notes[$i]["id"], "store_id" => $debit_notes[$i]["store_id"], "auto_closed" => "", "payment_method" => $debit_notes[$i]["debit_payment_method"], "paid_directly" => -1, "debit_note" => 1, "cur_id" => "", "payment_m" => "", "invoice_reference" => "", "created_by" => 0);
                $array_index++;
            }
            self::__USORT_TIMESTAMP($stm);
            $cashbox = $this->model("cashbox");
            $data["cashboxes"] = array();
            $cashboxes_info = $cashbox->get_all_cashboxes_employee();
            for ($i = 0; $i < count($cashboxes_info); $i++) {
                $data["cashboxes"][$cashboxes_info[$i]["id"]] = $cashboxes_info[$i]["username"];
            }
            $total_remain = 0;
            $cnt = 0;
            $tmp = array();
            array_push($tmp, "<b>Previews Balance</b>");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
            foreach ($stm as $key => $value) {
                $tmp = array();
                $invoice_id = "";
                if ($value["invoice_id"] != "") {
                    $invoice_id = self::idFormat_stockInv($value["invoice_id"]);
                }
                if ($value["credit"] == 1) {
                    if ($value["deleted"] == 0) {
                        $total_remain += $value["total_invoice_value"];
                    }
                } else {
                    if ($value["deleted"] == 0) {
                        $total_remain -= $value["total_payment_value"];
                    }
                }
                $dtt = explode(" ", $value["creation_date"]);
                array_push($tmp, $dtt[0]);
                if ($invoice_id != "") {
                    $rf = "";
                    if (0 < strlen($value["invoice_reference"])) {
                        $rf = " - " . $value["invoice_reference"];
                    }
                    if ($_SESSION["role"] == 1) {
                        array_push($tmp, "<a class='show_det' onclick='show_details(" . $value["invoice_id"] . ")'>" . $invoice_id . $rf . "</a>");
                    } else {
                        array_push($tmp, $invoice_id . $rf);
                    }
                } else {
                    array_push($tmp, "");
                }
                if ($value["ref_payment"] != "") {
                    if ($value["debit_note"] == 0) {
                        array_push($tmp, self::idFormat_supplier_payment($value["ref_payment"]));
                    } else {
                        array_push($tmp, self::idFormat_debitnote($value["ref_payment"]));
                    }
                } else {
                    array_push($tmp, "");
                }
                if (0 < $value["created_by"]) {
                    array_push($tmp, $data["cashboxes"][$value["created_by"]]);
                } else {
                    array_push($tmp, "");
                }
                if ($value["deleted"] == 0) {
                    array_push($tmp, $value["payment_note"]);
                } else {
                    array_push($tmp, "<span class='linethrough'>" . $value["payment_note"] . "</span>");
                }
                if ($value["deleted"] == 0) {
                    array_push($tmp, self::value_format_custom($value["total_invoice_value"], $this->settings_info));
                } else {
                    array_push($tmp, "<span class='linethrough'>" . self::value_format_custom($value["total_invoice_value"], $this->settings_info) . "</span>");
                }
                if ($value["deleted"] == 0) {
                    array_push($tmp, self::value_format_custom($value["total_payment_value"], $this->settings_info));
                } else {
                    array_push($tmp, "<span class='linethrough'>" . self::value_format_custom($value["total_payment_value"], $this->settings_info) . "</span>");
                }
                if ($cnt < count($stm) - 1) {
                    array_push($tmp, self::value_format_custom((double) $total_remain, $this->settings_info));
                } else {
                    array_push($tmp, "<span class='final_remain'>" . self::value_format_custom((double) $total_remain, $this->settings_info) . "</span>");
                }
                array_push($tmp, $value["deleted"]);
                array_push($tmp, $value["cur_id"]);
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($data_array["data"], $tmp);
                $cnt++;
            }
            echo json_encode($data_array);
        }
    }
    public function get_suppliers()
    {
        self::giveAccessTo(array(2, 4));
        $suppliers = $this->model("suppliers");
        $suppliers_data = $suppliers->getSuppliers();
        $info = array();
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $info[$i]["id"] = $suppliers_data[$i]["id"];
            $info[$i]["name"] = $suppliers_data[$i]["name"];
            $info[$i]["c_name"] = $suppliers_data[$i]["contact_name"];
            $info[$i]["address"] = $suppliers_data[$i]["address"];
        }
        echo json_encode($info);
    }
    public function get_suppliers_with_info()
    {
        self::giveAccessTo(array(2, 4));
        $suppliers = $this->model("suppliers");
        $currency = $this->model("currency");
        $suppliers_data = $suppliers->getSuppliers();
        $info = array();
        $info["suppliers"] = array();
        $info["currency"] = $currency->getDefaultActiveCurrency();
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $info["suppliers"][$i]["id"] = $suppliers_data[$i]["id"];
            $info["suppliers"][$i]["name"] = $suppliers_data[$i]["name"];
            $info["suppliers"][$i]["c_name"] = $suppliers_data[$i]["contact_name"];
            $info["suppliers"][$i]["address"] = $suppliers_data[$i]["address"];
        }
        echo json_encode($info);
    }
    public function getSuppliersPayments()
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $suppliers = $this->model("suppliers");
        $payments = $this->model("payments");
        $suppliers_info = $suppliers->getSuppliers();
        $suppliers_in = array();
        $suppliers_balance = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_in[$suppliers_info[$i]["id"]] = $suppliers_info[$i]["name"];
            $suppliers_balance[$suppliers_info[$i]["id"]] = $suppliers_info[$i]["starting_balance"];
        }
        $suppliers_invoices = $stock->get_suppliers_invoices();
        $data_array["data"] = array();
        for ($i = 0; $i < count($suppliers_invoices); $i++) {
            $tmp = array();
            $totalPaymentForSupplier = $payments->getTotalPaymentForSupplier($suppliers_invoices[$i]["supplier_id"]);
            $totalInvoicesValueForSupplier = $stock->getTotalInvoicesValueForSupplier($suppliers_invoices[$i]["supplier_id"]);
            array_push($tmp, self::idFormat_supplier($suppliers_invoices[$i]["supplier_id"]));
            array_push($tmp, $suppliers_in[$suppliers_invoices[$i]["supplier_id"]]);
            array_push($tmp, $suppliers_invoices[$i]["num"]);
            array_push($tmp, self::value_format_custom($totalPaymentForSupplier[0]["sum"], $this->settings_info));
            array_push($tmp, self::value_format_custom($totalInvoicesValueForSupplier[0]["total"] - $totalPaymentForSupplier[0]["sum"] + $suppliers_balance[$suppliers_invoices[$i]["supplier_id"]], $this->settings_info));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function _getSuppliersPayments()
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $payments = $this->model("payments");
        $stock = $this->model("stock");
        $suppliers_data = $suppliers->getSuppliers();
        $data_array["data"] = array();
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $tmp = array();
            $totalPaymentForSupplier = $payments->getTotalPaymentForSupplier($suppliers_data[$i]["id"]);
            $totalInvoicesForSupplier = $stock->getTotalInvoicesForSupplier($suppliers_data[$i]["id"]);
            array_push($tmp, self::idFormat_supplier($suppliers_data[$i]["id"]));
            array_push($tmp, $suppliers_data[$i]["name"]);
            array_push($tmp, $totalInvoicesForSupplier[0]["num"]);
            array_push($tmp, self::value_format_custom($totalPaymentForSupplier[0]["sum"], $this->settings_info));
            array_push($tmp, "-");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getSuppliers($_p0)
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $phones = $this->model("phones");
        $delivery = $this->model("delivery");
        $remain_post = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $suppliers_data = $suppliers->getSuppliers();
        $data_array["data"] = array();
        $data_array["remain"] = 0;
        $debit = 0;
        $credit = 0;
        $rm = 0;
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $totalSuppliersInvoicesValue = $suppliers->getTotalSuppliersInvoicesValue($suppliers_data[$i]["id"]);
            $totalSuppliersPaid = $suppliers->getTotalSuppliersPaid($suppliers_data[$i]["id"]);
            $totalSuppliersDebitNote = $suppliers->getTotalSuppliersDebitNote($suppliers_data[$i]["id"]);
            $debit += $totalSuppliersPaid[0]["sum"] + $totalSuppliersDebitNote[0]["sum"];
            $credit += $totalSuppliersInvoicesValue[0]["sum"] - $suppliers_data[$i]["usd_starting_balance"];
            $rm = $totalSuppliersInvoicesValue[0]["sum"] - $suppliers_data[$i]["usd_starting_balance"] - $totalSuppliersPaid[0]["sum"] - $totalSuppliersDebitNote[0]["sum"];
            $bal = $totalSuppliersPaid[0]["sum"] + $totalSuppliersDebitNote[0]["sum"] - ($totalSuppliersInvoicesValue[0]["sum"] - $suppliers_data[$i]["usd_starting_balance"]);
            $tmp = array();
            array_push($tmp, self::idFormat_supplier($suppliers_data[$i]["id"]));
            array_push($tmp, $suppliers_data[$i]["name"]);
            array_push($tmp, $suppliers_data[$i]["contact_name"]);
            $contacts = $phones->getSupplierContacts($suppliers_data[$i]["id"]);
            if (0 < count($contacts)) {
                array_push($tmp, $contacts[0]["phone_number"]);
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, $suppliers_data[$i]["email"]);
            array_push($tmp, $suppliers_data[$i]["country_name"]);
            array_push($tmp, $suppliers_data[$i]["address"]);
            array_push($tmp, self::global_number_formatter($suppliers_data[$i]["starting_balance"], $this->settings_info));
            array_push($tmp, self::global_number_formatter($bal, $this->settings_info));
            if ($this->settings_info["delivery_items_plugin"] == 1) {
                array_push($tmp, number_format($delivery->get_total_cod_for_supplier_id($suppliers_data[$i]["id"]), 2) . " " . $this->settings_info["default_currency_symbol"]);
                array_push($tmp, number_format($delivery->get_total_not_cod_for_supplier_id($suppliers_data[$i]["id"]), 2) . " " . $this->settings_info["default_currency_symbol"]);
            } else {
                array_push($tmp, "");
                array_push($tmp, "");
            }
            array_push($tmp, "-");
            array_push($data_array["data"], $tmp);
        }
        $data_array["debit"] = self::global_number_formatter($debit, $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["credit"] = self::global_number_formatter(abs($credit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["balance"] = self::global_number_formatter($debit - abs($credit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        echo json_encode($data_array);
    }
    public function getSuppliersOverview($_p0)
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $phones = $this->model("phones");
        $delivery = $this->model("delivery");
        $remain_post = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $suppliers_data = $suppliers->getSuppliers();
        $data_array["data"] = array();
        $data_array["remain"] = 0;
        $debit = 0;
        $credit = 0;
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $totalSuppliersInvoicesValue = $suppliers->getTotalSuppliersInvoicesValue($suppliers_data[$i]["id"]);
            $totalSuppliersPaid = $suppliers->getTotalSuppliersPaid($suppliers_data[$i]["id"]);
            $totalSuppliersDebitNote = $suppliers->getTotalSuppliersDebitNote($suppliers_data[$i]["id"]);
            if ($suppliers_data[$i]["debit_credit"] == 0) {
                $rm = $totalSuppliersInvoicesValue[0]["sum"] - ($suppliers_data[$i]["starting_balance"] + $suppliers_data[$i]["usd_starting_balance"]) - $totalSuppliersPaid[0]["sum"] - $totalSuppliersDebitNote[0]["sum"];
            } else {
                $rm = $totalSuppliersInvoicesValue[0]["sum"] + $suppliers_data[$i]["starting_balance"] + $suppliers_data[$i]["usd_starting_balance"] - $totalSuppliersPaid[0]["sum"] - $totalSuppliersDebitNote[0]["sum"];
            }
            $debit += $totalSuppliersPaid[0]["sum"] + $totalSuppliersDebitNote[0]["sum"];
            $credit += $totalSuppliersInvoicesValue[0]["sum"] - $suppliers_data[$i]["usd_starting_balance"];
            $bal = $totalSuppliersPaid[0]["sum"] + $totalSuppliersDebitNote[0]["sum"] - ($totalSuppliersInvoicesValue[0]["sum"] - $suppliers_data[$i]["usd_starting_balance"]);
            if ($remain_post == 0 || $remain_post == 1 && 0 < $bal || $remain_post == 2 && $bal < 0 || $remain_post == 3 && $bal == 0) {
                $tmp = array();
                array_push($tmp, self::idFormat_supplier($suppliers_data[$i]["id"]));
                array_push($tmp, $suppliers_data[$i]["name"]);
                array_push($tmp, $suppliers_data[$i]["contact_name"]);
                array_push($tmp, self::global_number_formatter($suppliers_data[$i]["starting_balance"] + $suppliers_data[$i]["usd_starting_balance"], $this->settings_info));
                array_push($tmp, self::global_number_formatter($totalSuppliersInvoicesValue[0]["sum"], $this->settings_info));
                array_push($tmp, self::global_number_formatter($totalSuppliersPaid[0]["sum"], $this->settings_info));
                array_push($tmp, self::global_number_formatter($totalSuppliersDebitNote[0]["sum"], $this->settings_info));
                array_push($tmp, self::global_number_formatter($bal, $this->settings_info));
                array_push($tmp, "");
                array_push($data_array["data"], $tmp);
            }
        }
        $data_array["debit"] = self::global_number_formatter($debit, $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["credit"] = self::global_number_formatter(abs($credit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        $data_array["balance"] = self::global_number_formatter($debit - abs($credit), $this->settings_info) . " <small class='currency'>" . $this->settings_info["default_currency_symbol"] . "</small>";
        echo json_encode($data_array);
    }
    public function search($_search, $_page)
    {
        $search = filter_var($_search, FILTER_SANITIZE_ADD_SLASHES);
        $page = filter_var($_page, FILTER_SANITIZE_NUMBER_INT);
        $suppliers = $this->model("suppliers");
        $results = $suppliers->search($search, $page, 20);
        $return = array();
        $return["results"] = array();
        $index = 0;
        foreach ($results as $result) {
            $return["results"][$index] = array("id" => $result["id"], "text" => $result["name"] . " " . $result["middle_name"] . " " . $result["last_name"]);
            $index++;
        }
        if (count($results) == 20) {
            $return["pagination"]["more"] = $suppliers->search($search, $page, 20, true);
        } else {
            $return["pagination"]["more"] = false;
        }
        echo json_encode($return);
    }
}

?>