<?php
class pos extends Controller
{
    public $settings_info = NULL;
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function add_new_item_pos()
    {
        $items = $this->model("items");
        $item_array = array();
        $item_array["description"] = filter_input(INPUT_POST, "item_description", self::conversion_php_version_filter());
        $item_array["item_code1"] = filter_input(INPUT_POST, "item_code1", self::conversion_php_version_filter());
        $item_array["item_code2"] = filter_input(INPUT_POST, "item_code2", self::conversion_php_version_filter());
        $item_array["item_barcode"] = filter_input(INPUT_POST, "item_barcode", self::conversion_php_version_filter());
        $item_array["supplier_name"] = filter_input(INPUT_POST, "item_supplier", self::conversion_php_version_filter());
        $item_array["item_supplier_phone"] = filter_input(INPUT_POST, "item_supplier_phone", self::conversion_php_version_filter());
        $item_array["cost"] = filter_input(INPUT_POST, "item_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $item_array["selling"] = filter_input(INPUT_POST, "item_price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $item_array["qty"] = filter_input(INPUT_POST, "item_qty", FILTER_SANITIZE_NUMBER_INT);
        $item_id = $items->add_new_item_pos($item_array);
        if (0 < $item_id) {
            $global_logs = $this->model("global_logs");
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["related_to_item_id"] = $item_id;
            $logs_info["description"] = "New Item (IT-" . $item_id . ") has been added";
            $logs_info["log_type"] = 1;
            $logs_info["other_info"] = "";
            $global_logs->add_global_log($logs_info);
            $info_hist = array();
            $info_hist["user_id"] = $_SESSION["id"];
            $info_hist["item_id"] = $item_id;
            $info_hist["old_cost"] = 0;
            $info_hist["new_cost"] = $item_array["cost"];
            $info_hist["old_qty"] = 0;
            $info_hist["new_qty"] = 0;
            $info_hist["source"] = "manual";
            $info_hist["receive_stock_id"] = "-";
            $info_hist["free"] = 0;
            $items->add_history_prices($info_hist);
            $items->sync_item_with_store($_SESSION["store_id"]);
            $items->add_qty_to_all($item_id, $item_array["qty"]);
            $suppliers = $this->model("suppliers");
            $countries = $this->model("countries");
            $info = array();
            $info["sup_name"] = $item_array["supplier_name"];
            $info["sup_contact"] = "";
            $info["sup_country"] = $countries->get_default();
            $info["sup_adr"] = "";
            $info["deb_cred"] = 0;
            $info["email"] = "";
            $info["sup_phone"] = $item_array["item_supplier_phone"];
            $info["starting_balance"] = 0;
            $info["user_id"] = $_SESSION["id"];
            $info["deb_cred_lbp"] = 0;
            $info["deb_cred_usd"] = 0;
            $info["lbp_starting_balance"] = 0;
            $info["usd_starting_balance"] = 0;
            $supplier_id = $suppliers->addSupplier($info);
            $transactions = $this->model("transactions");
            $info_trx = array();
            $info_trx["amount_usd"] = $item_array["cost"] * $item_array["qty"];
            $info_trx["amount_lbp"] = 0;
            $info_trx["transaction_type"] = 2;
            $info_trx["transaction_to_cashbox_id"] = 0;
            $info_trx["transaction_note"] = 'Buy Item from POS';
            $info_trx["created_by"] = $_SESSION["id"];
            $info_trx["current_cashbox_id"] = $_SESSION["cashbox_id"];
            $transactions->add_new_transaction($info_trx);
            if (0 < strlen($item_array["item_code1"]) || 0 < strlen($item_array["item_code2"])) {
                $uniqueItems = $this->model("uniqueItems");
                $unique_id = $uniqueItems->createNew($item_id, 1);
                $uniqueItems->update_imei($unique_id, $item_array["item_code1"], $item_array["item_code2"], $supplier_id);
            }
        }
        echo json_encode(array());
    }
    public function update_payment_of_invoice($invoice_id, $payment)
    {
        $invoice = $this->model("invoice");
        $invoice_info = $invoice->getInvoiceById($invoice_id);
        if ($invoice_info[0]["customer_id"] == 0 && $payment == 2) {
            echo json_encode(array(1));
        } else {
            $result = $invoice->update_payment_of_invoice($invoice_id, $payment);
            if ($this->settings_info["telegram_enable"] == 1 && 0 < $result) {
                $users = $this->model("user");
                $employees_info = $users->getAllUsersEvenDeleted();
                $employees_info_array = array();
                for ($i = 0; $i < count($employees_info); $i++) {
                    $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                }
                $store = $this->model("store");
                $store_info = $store->getStoresById($_SESSION["store_id"]);
                $payment_text = "";
                if ($payment == 1) {
                    $payment_text = "Cash";
                }
                if ($payment == 2) {
                    $payment_text = "Debt";
                }
                $info_tel = array();
                $info_tel["message"] .= "<strong>Change Invoice Payment</strong> \n";
                $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                $info_tel["message"] .= "<strong>Operator:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                $info_tel["message"] .= "<strong>Invoice ID: " . $invoice_id . "</strong> \n";
                $info_tel["message"] .= "<strong>Payment Changed to:</strong> " . $payment_text . " \n";
                self::send_to_telegram($info_tel, 1);
            }
            echo json_encode(array(0));
        }
    }
    public function change_client_invoice($_invoice_id, $_new_client_id)
    {
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $new_client_id = filter_var($_new_client_id, FILTER_SANITIZE_NUMBER_INT);
        $old_invoice_info = $invoice->getInvoiceById($invoice_id);
        $old_customer_info = array();
        if (0 < $old_invoice_info[0]["customer_id"]) {
            $old_customer_info = $customers->getCustomersById($old_invoice_info[0]["customer_id"]);
        }
        $new_customer_info = array();
        if (0 < $new_client_id) {
            $new_customer_info = $customers->getCustomersById($new_client_id);
        }
        $result = $invoice->update_invoice_client($invoice_id, $new_client_id);
        if (0 < $result && $this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $info_tel = array();
            $info_tel["message"] .= "<strong>Change Client of Invoice</strong> \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Operator:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Invoice ID: " . $invoice_id . "</strong> \n";
            if (0 < count($old_customer_info)) {
                $info_tel["message"] .= "<strong>Old Client:</strong> " . $old_customer_info[0]["name"] . " " . $old_customer_info[0]["middle_name"] . " " . $old_customer_info[0]["last_name"] . "(" . $old_customer_info[0]["id"] . ")" . "\n";
            } else {
                $info_tel["message"] .= "<strong>Old Client:</strong> No client \n";
            }
            if (0 < count($new_customer_info)) {
                $info_tel["message"] .= "<strong>New Client:</strong> " . $new_customer_info[0]["name"] . " " . $new_customer_info[0]["middle_name"] . " " . $new_customer_info[0]["last_name"] . "(" . $new_customer_info[0]["id"] . ")" . " \n";
            }
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array());
    }
    public function search_client($invoice_id)
    {
        $user = $this->model("user");
        $search_client = filter_input(INPUT_POST, "query", self::conversion_php_version_filter());
        $result = $user->search_client($search_client);
        $output = "";
        for ($i = 0; $i < count($result); $i++) {
            $output .= "<li onclick=\"update_inv_cl(" . $result[$i]["id"] . "," . $invoice_id . ")\"  data-id=\"" . $result[$i]["id"] . "\">" . $result[$i]["id"] . " - " . $result[$i]["name"] . " - " . $result[$i]["phone"] . "</li>";
        }
        echo $output;
    }
    public function monitor_pos_items($_item_id)
    {
        $pos = $this->model("pos");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $pos->monitor_pos_items($item_id);
        echo json_encode(array());
    }
    public function monitor_pos_items_adv($_item_id, $qty)
    {
        $pos = $this->model("pos");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $pos->monitor_pos_items_adv($item_id, $qty);
        echo json_encode(array());
    }
    public function get_all_customers_acc($_p0, $_p1)
    {
        $customers = $this->model("customers");
        $items = $this->model("items");
        $info = array();
        $info["customers"] = $customers->get_all_customers_acc();
        $info["invoice_info"] = $items->get_item_invoice_info($_p1);
        echo json_encode($info);
    }
    public function update_invoice_date($_invoice_id, $_date)
    {
        $invoice = $this->model("invoice");
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($_date, FILTER_SANITIZE_NUMBER_INT);
        $invoice->update_invoice_date($invoice_id, $date);
        echo json_encode(array());
    }
    public function generate_empty_invoice()
    {
        $invoice = $this->model("invoice");
        $id = $invoice->generate_empty_invoice($_SESSION["store_id"], $_SESSION["id"], $this->settings_info["vat"]);
        echo json_encode($id);
    }
    public function customer_latest_price($_customer_id, $_item_id)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $info = $invoice->customer_latest_price($customer_id, $item_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"] / $info[$i]["qty"], $this->settings_info));
            array_push($tmp, "");
            array_push($tmp, round($info[$i]["final_price_disc_qty"] / $info[$i]["qty"], $this->settings_info["round_val"]));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getHistoryDiscounts($_id)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $discounts = $this->model("discounts");
        $items = $this->model("items");
        $items_info = $items->get_item($id);
        $discounts_data = $discounts->getDiscountsByItemId($id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($discounts_data); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($id));
            array_push($tmp, $discounts_data[$i]["discount_name"]);
            array_push($tmp, self::date_time_format_custom($discounts_data[$i]["start_date"]));
            array_push($tmp, self::date_time_format_custom($discounts_data[$i]["end_date"]));
            array_push($tmp, self::value_format_custom($items_info[0]["selling_price"], $this->settings_info));
            array_push($tmp, self::value_format_custom($discounts_data[$i]["discount_value"], $this->settings_info) . " %");
            if ($items_info[0]["vat"] == 0) {
                array_push($tmp, "No");
            } else {
                array_push($tmp, ($this->settings_info["vat"] - 1) * 100 . " %");
            }
            $price_after_discount = $items_info[0]["selling_price"] - $items_info[0]["selling_price"] * $discounts_data[$i]["discount_value"] / 100;
            if ($items_info[0]["vat"] == 1) {
                $price_after_discount = $price_after_discount * $this->settings_info["vat"];
            }
            array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function check_internationnal_call($_call)
    {
        self::giveAccessTo(array(2, 4));
        $call = filter_var($_call, self::conversion_php_version_filter());
        $invoice = $this->model("invoice");
        $info = $invoice->check_internationnal_call($call);
        echo json_encode($info[0]["num"]);
    }
    public function quick_report_info()
    {
        self::giveAccessTo(array(2, 4));
        $mobile = $this->model("mobileStore");
        $invoice = $this->model("invoice");
        $info = array();
        $int_call_info = $invoice->get_internationnal_calls_invoices();
        $info["mobile_stock_value_alfa"] = number_format($mobile->get_mobile_stock_value_alfa(), 2);
        $info["mobile_stock_value_mtc"] = number_format($mobile->get_mobile_stock_value_mtc(), 2);
        $info["interna_call_balance"] = number_format($this->settings_info["international_calls_balance"], 2);
        echo json_encode($info);
    }
    public function getStockReport()
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $tables_info = $items->getAllItems_instant_report();
        $all_item_qty_in_store = $items->get_all_item_qty_in_store($_SESSION["store_id"]);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($tables_info[$i]["id"]));
            array_push($tmp, $tables_info[$i]["description"] . " #" . $tables_info[$i]["sku_code"]);
            array_push($tmp, $tables_info[$i]["barcode"]);
            array_push($tmp, self::formar_nb_if_float((double) $qty[$tables_info[$i]["id"]]));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_invoice_with_all_details($_invoice_id)
    {
        self::giveAccessTo(array(2));
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $info = array();
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $employees = $this->model("employees");
        $settings = $this->model("settings");
        $user = $this->model("user");
        $info["invoice"] = $invoice->getInvoiceById($invoice_id);
        if (count($info["invoice"]) == 0 || floatval($this->settings_info["disable_edit_and_delete_invoice_older_than"]) < floatval(time() - strtotime($info["invoice"][0]["creation_date"]))) {
            $user_info = array();
            $user_info["notfound"] = 1;
            echo json_encode($user_info);
            exit;
        }
        $info["invoice_details"] = $invoice->getItemsOfInvoice($invoice_id);
        $info["invoice"][0]["total_value_formated"] = self::value_format_custom($info["invoice"][0]["total_value"], $this->settings_info);
        $info["invoice"][0]["discount_formated"] = self::value_format_custom($info["invoice"][0]["invoice_discount"], $this->settings_info);
        $info["invoice"][0]["manual_discount_value"] = $info["invoice"][0]["invoice_discount"];
        $info["invoice"][0]["total_formated"] = self::value_format_custom($info["invoice"][0]["total_value"] + $info["invoice"][0]["invoice_discount"], $this->settings_info);
        if ($info["invoice"][0]["customer_id"] != NULL && $info["invoice"][0]["customer_id"] != 0) {
            $cs = $customers->getCustomersById($info["invoice"][0]["customer_id"]);
            $info["customer"][0]["name"] = ucwords($cs[0]["name"]);
        } else {
            $info["customer"][0]["name"] = "Unknown";
        }
        if ($info["invoice"][0]["sales_person"] != 0) {
            $sp = $employees->get_employee_even_delete($info["invoice"][0]["sales_person"]);
            $info["sales_person"][0]["name"] = ucwords($sp[0]["first_name"] . " " . $sp[0]["last_name"]);
        } else {
            $info["sales_person"][0]["name"] = "Unknown";
        }
        if ($info["invoice"][0]["payment_method"] == NULL) {
            $info["payment_method"][0]["name"] = "";
        } else {
            $payment_name = $settings->get_all_payment_method_by_id($info["invoice"][0]["payment_method"]);
            $info["payment_method"][0]["name"] = ucwords($payment_name[0]["method_name"]);
        }
        $user_info = array();
        $user_info["id"] = $info["invoice"][0]["employee_id"];
        $cashier = $user->get_user($user_info);
        $info["cashier"][0]["name"] = ucwords($cashier[0]["name"]);
        $info["notfound"] = 0;
        echo json_encode($info);
    }
    public function check_session_or_cashbox()
    {
        $cashbox = $this->model("cashbox");
        $info = array();
        if (isset($_SESSION["id"])) {
            $info["session"] = 1;
        } else {
            $info["session"] = 0;
        }
        $info["cashbox"] = $cashbox->check_if_cashbox_is_open($_SESSION["id"]);
        echo json_encode($info);
    }
    public function show_invoice_to_change_other_location($_invoice_id, $_location_id, $_local_invoice)
    {
        self::giveAccessTo(array(2, 4));
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $location_id = filter_var($_location_id, FILTER_SANITIZE_NUMBER_INT);
        $local_invoice = filter_var($_local_invoice, FILTER_SANITIZE_NUMBER_INT);
        self::show_invoice_to_change($invoice_id, $location_id, $local_invoice);
    }
    public function show_invoice_to_change($invoice_id_, $store_id_, $virtual_invoice_id = 0)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $store = $this->model("store");
        $outside_connection = $this->model("outside_connection_");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $store_id = 0;
        if (0 < $store_id_) {
            $store_id = $store_id_;
        }
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        if ($store_id == 0) {
            $info = $invoice->getAllItemsWasSoldByInvoice_id_switch($invoice_id);
        } else {
            $warehouse_info = 0;
            $stores = $store->getAllStores();
            for ($i = 0; $i < count($stores); $i++) {
                if ($stores[$i]["id"] == $store_id) {
                    $warehouse_info = $stores[$i];
                    break;
                }
            }
            $already_imported = $invoice->getInvoiceByIdOtherBranche($invoice_id, $store_id_);
            if (0 < count($already_imported)) {
                $data_array["data"] = array();
                $data_array["er"] = array(1);
                $data_array["local_id"] = array($already_imported[0]["id"]);
                echo json_encode($data_array);
                return NULL;
            }
            $custom_connection = my_sql::custom_connection($warehouse_info["ip_address"], $warehouse_info["username"], $warehouse_info["password"], $warehouse_info["db"]);
            if ($custom_connection) {
                $remote_invoice = $outside_connection->get_remote_invoice($custom_connection, $invoice_id);
                $info = $outside_connection->getAllItemsWasSoldByInvoice_id_switch($custom_connection, $invoice_id);
                if (0 < $virtual_invoice_id) {
                    $remote_invoice_items = $outside_connection->get_remote_invoice_items($custom_connection, $invoice_id);
                    $invoice->clone_invoice($remote_invoice, $remote_invoice_items, $virtual_invoice_id, $store_id);
                }
            } else {
                $data_array["data"] = array();
                $data_array["er"] = array(2);
                echo json_encode($data_array);
                return NULL;
            }
        }
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            if ($info[$i]["item_id"] == NULL) {
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($tmp, $info[$i]["description"]);
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($tmp, "<span id='old_qty_" . $info[$i]["id"] . "'>" . (double) $info[$i]["qty"] . "</span>");
                array_push($tmp, "<input readonly class='qty_input only_numeric' type='text' id='qty_" . $info[$i]["id"] . "' name='originial_edit_qty_[" . $info[$i]["id"] . "]' value='0' />");
            } else {
                array_push($tmp, self::idFormat_INVIT($info[$i]["id"]));
                $item_info = $items->get_item($info[$i]["item_id"]);
                array_push($tmp, $item_info[0]["barcode"]);
                array_push($tmp, $item_info[0]["description"]);
                array_push($tmp, $sizes_info_label[$item_info[0]["size_id"]]);
                array_push($tmp, $colors_info_label[$item_info[0]["color_text_id"]]);
                array_push($tmp, "<span id='old_qty_" . $info[$i]["id"] . "'>" . (double) $info[$i]["qty"] . "</span>");
                array_push($tmp, "<input autocomplete='off' onkeyup='get_difference()' class='qty_input qty_input__ only_numeric' type='text' id='qty_" . $info[$i]["id"] . "' name='originial_edit_qty_[" . $info[$i]["id"] . "]' value='0' />");
            }
            if ($info[$i]["discount"] < 0) {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"] / (1 - $info[$i]["discount"] / 100), $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            }
            array_push($tmp, (double) $info[$i]["discount"]);
            if ($info[$i]["vat"] == 1) {
                array_push($tmp, ((double) $info[$i]["vat_value"] - 1) * 100 . " %");
            } else {
                array_push($tmp, 0 . " %");
            }
            if ($info[$i]["vat"] == 1) {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100) * $info[$i]["vat_value"] * (double) $info[$i]["qty"], $this->settings_info));
            } else {
                if ($info[$i]["discount"] < 0) {
                    array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (double) $info[$i]["qty"], $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100) * (double) $info[$i]["qty"], $this->settings_info));
                }
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function return_remain_balance($customer_id_)
    {
        self::giveAccessTo(array(2, 4));
        $customer_id = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $payments = $this->model("payments");
        $creditnote = $this->model("creditnote");
        $customer_info = $customers->getCustomersById($customer_id);
        $invoies_info = $invoice->getTotalUnpaid($customer_id);
        $total_payments = $payments->getTotalPaymentForCustomer($customer_id);
        $creditnote_info = $creditnote->total_credit_notes($customer_id);
        $info = array();
        $info["customer_balance"] = $total_payments[0]["sum"];
        $info["total_unPaid"] = $invoies_info[0]["sum"];
        $info["total_remain"] = $info["total_unPaid"] - $info["customer_balance"];
        $info["total_remain"] += $customer_info[0]["starting_balance"];
        $info["total_remain"] -= $creditnote_info[0]["sum"];
        $info["total_remain"] = self::value_format_custom($info["total_remain"], $this->settings_info);
        return $info["total_remain"];
    }
    public function get_customer_by_id($_customer_id)
    {
        self::giveAccessTo(array(2));
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        $customers_data = $customers->getCustomersById($customer_id);
        $customers_data[0]["customer_balance"] = self::return_remain_balance($customer_id);
        echo json_encode($customers_data);
    }
    public function show_debts_payment_details_daily()
    {
        self::giveAccessTo(array(2));
        echo json_encode(array());
    }
    public function debts_payment_bydate_table()
    {
        self::giveAccessTo(array(2));
        $data_array["data"] = array();
        $payments = $this->model("payments");
        $customers = $this->model("customers");
        $customers_info = $customers->getCustomers();
        $cus = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $cus[$customers_info[$i]["id"]] = $customers_info[$i]["name"];
        }
        $payments_info = $payments->getAllDebtsPayment($_SESSION["cashbox_id"]);
        for ($i = 0; $i < count($payments_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_customer_payment($payments_info[$i]["id"]));
            array_push($tmp, $cus[$payments_info[$i]["customer_id"]]);
            array_push($tmp, self::value_format_custom($payments_info[$i]["balance"], $this->settings_info));
            array_push($tmp, $payments_info[$i]["balance_date"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_customers($type)
    {
        self::giveAccessTo(array(2));
        $data_array["data"] = array();
        $customers = $this->model("customers");
        if ($this->settings_info["clients_show_only_by_users_created"] == 1) {
            if ($type == 0) {
                $customers_data = $customers->getCustomersRetail_l();
            } else {
                $customers_data = $customers->getCustomers_l();
            }
        } else {
            if ($type == 0) {
                $customers_data = $customers->getCustomersRetail();
            } else {
                $customers_data = $customers->getCustomers();
            }
        }
        $balances = self::get_all_balances();
        $customers_types = $customers->getCustomersTypes();
        $customers_types_info = array();
        for ($i = 0; $i < count($customers_types); $i++) {
            $customers_types_info[$customers_types[$i]["id"]] = $customers_types[$i]["name"];
        }
        for ($i = 0; $i < count($customers_data); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_customer($customers_data[$i]["id"]));
            $nt = "";
            if (0 < strlen($customers_data[$i]["note"])) {
                $nt = "<i class='glyphicon glyphicon-info-sign' title='" . $customers_data[$i]["note"] . "'></i>";
            }
            $company = "";
            if (0 < strlen($customers_data[$i]["company"])) {
                $company = " (" . $customers_data[$i]["company"] . ") ";
            }
            array_push($tmp, $customers_data[$i]["name"] . " " . $customers_data[$i]["middle_name"] . " " . $customers_data[$i]["last_name"] . " " . $company . " " . $nt);
            if (isset($customers_data[$i]["id"])) {
                array_push($tmp, number_format($balances[$customers_data[$i]["id"]], 2));
            } else {
                array_push($tmp, number_format(0, 2));
            }
            array_push($tmp, $customers_data[$i]["address"]);
            array_push($tmp, "<span id='ph_" . $customers_data[$i]["id"] . "'>" . $customers_data[$i]["phone"] . "</span>");
            array_push($tmp, "<span id='idnbb_" . $customers_data[$i]["id"] . "'>" . $customers_data[$i]["id_nb"] . "</span>");
            array_push($tmp, $customers_types_info[$customers_data[$i]["customer_type"]]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_cashboxes($date_)
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $date = filter_var($date_, self::conversion_php_version_filter());
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
        $cashboxes = $cashbox->getHistoryOfCashboxes($_SESSION["id"], $_SESSION["store_id"], $date_range);
        $data_array["data"] = array();
        for ($i = 0; $i < count($cashboxes); $i++) {
            $tmp = array();
            array_push($tmp, $cashboxes[$i]["id"]);
            array_push($tmp, $cashboxes[$i]["starting_cashbox_date"]);
            array_push($tmp, $cashboxes[$i]["ending_cashbox_date"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllCustomersDetails()
    {
        self::giveAccessTo(array(2, 4));
        $customers = $this->model("customers");
        $info = $customers->getCustomersMinified();
        echo json_encode($info);
    }
    public function backupNow()
    {
        if ($_GET["f"] != "backupNow") {
            self::giveAccessTo(array(2, 4));
        }
        self::bkp($this->settings_info);
    }
    public function tracking_pos($_action, $_item_id)
    {
        self::giveAccessTo(array(2, 4));
        $action = filter_var($_action, self::conversion_php_version_filter());
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        if (QUERY_LOGS_ENABLE && !file_exists(QUERY_LOGS_PATH)) {
            mkdir(QUERY_LOGS_PATH, 511, true);
        }
        $file = QUERY_LOGS_PATH . "/pos-" . date("Y-m-d") . ".txt";
        file_put_contents($file, $action . " item " . $item_id . " " . date("h:i:sa") . " \n", FILE_APPEND | LOCK_EX);
    }
    public function add_wasting_item_by_barcode($barcode_)
    {
    }
    public function get_item_by_barcode($barcode_)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $store = $this->model("store");
        $barcode = filter_var($barcode_, self::conversion_php_version_filter());
        $barcode = preg_replace("/[^a-zA-Z0-9.*]/", "", $barcode);
        $real_barcode = $barcode;
        $plu = 0;
        $plu_price = 0;
        if (substr($barcode, 0, 2) === $this->settings_info["plu_prefix"]) {
            $plu = 1;
            $real_barcode = substr($barcode, $this->settings_info["plu_first_part_start"], $this->settings_info["plu_first_part_end"]);
            $plu_price = substr($barcode, $this->settings_info["plu_second_part_start"], $this->settings_info["plu_second_part_end"]);
        }
        if (strlen($real_barcode) < 5) {
        }
        $item_info = $items->get_item_by_barcode($real_barcode);
        $colors = $this->model("colors");
        $all_colors = $colors->getColorsText();
        $colors_info = array();
        for ($i = 0; $i < count($all_colors); $i++) {
            $colors_info[$all_colors[$i]["id"]] = $all_colors[$i]["name"];
        }
        $sizes = $this->model("sizes");
        $all_sizes = $sizes->getSizes();
        $sizes_info = array();
        for ($i = 0; $i < count($all_sizes); $i++) {
            $sizes_info[$all_sizes[$i]["id"]] = $all_sizes[$i]["name"];
        }
        if (0 < count($item_info)) {
            $colors = $this->model("colors");
            $all_colors = $colors->getColorsText();
            $colors_info = array();
            for ($i = 0; $i < count($all_colors); $i++) {
                $colors_info[$all_colors[$i]["id"]] = $all_colors[$i]["name"];
            }
            self::tracking_pos("add", $item_info[0]["id"]);
            $item_info[0]["plu"] = $plu;
            if ($plu == 1) {
                $item_info[0]["qty"] = $plu_price * 1 / $item_info[0]["selling_price"];
                if ($this->settings_info["plu_weight"] == 1) {
                    $item_info[0]["qty"] = $plu_price / 1000;
                    $item_info[0]["selling_price"] = $item_info[0]["selling_price"];
                }
            } else {
                $item_info[0]["qty"] = 1;
            }
            $measure = $this->model("measures");
            $measures = $measure->getMeasures();
            $measures_info = array();
            for ($i = 0; $i < count($measures); $i++) {
                $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
            }
            $item_info[0]["measure_label"] = "";
            if ($item_info[0]["unit_measure_id"] != NULL) {
                $item_info[0]["measure_label"] = $measures_info[$item_info[0]["unit_measure_id"]];
            }
            $discounts = $this->model("discounts");
            $discounts_items = $discounts->get_all_items_under_discounts();
            $discounts_items_ids = array();
            $discounts_items_discount = array();
            for ($i = 0; $i < count($discounts_items); $i++) {
                $discounts_items_ids[$i] = $discounts_items[$i]["item_id"];
            }
            for ($i = 0; $i < count($discounts_items); $i++) {
                $discounts_items_discount[$discounts_items[$i]["item_id"]] = $discounts_items[$i]["discount_value"];
            }
            $item_info[0]["discount"] = $item_info[0]["discount"];
            if (in_array($item_info[0]["id"], $discounts_items_ids)) {
                $item_info[0]["discount"] = $discounts_items_discount[$item_info[0]["id"]];
                if ($this->settings_info["discount_by_group_force_round"] == 1) {
                    $initial_price_is = $item_info[0]["selling_price"];
                    $final_price_will_be = (int) ($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100));
                    $discountValue = $initial_price_is - $final_price_will_be;
                    $item_info[0]["discount"] = $discountValue / $initial_price_is * 100;
                }
            }
            $item_info[0]["plu_price"] = $plu_price;
            $item_info[0]["final_cost"] = $item_info[0]["buying_cost"];
            $item_info[0]["weight"] = $item_info[0]["weight"];
            $item_info[0]["composite_items"] = array();
            if ($item_info[0]["is_composite"] == 1) {
                $item_info[0]["composite_items"] = $items->get_all_composite_of_item($item_info[0]["id"]);
                $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[0]["composite_items"][0]["item_id"]);
                $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
                $item_info[0]["description"] .= " | <b>" . floatval($item_info[0]["composite_items"][0]["qty"]) . " pieces</b>";
            } else {
                $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[0]["id"]);
                $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
            }
            if ($this->settings_info["pos_hide_stock"] == 1 && $_SESSION["role"] == 2) {
                $item_info[0]["quantity"] = "";
            }
            if ($this->settings_info["show_size_and_color_on_pos"] == "1") {
                if (isset($sizes_info[$item_info[0]["size_id"]])) {
                    $item_info[0]["description"] .= " - <b>Size:</b>" . $sizes_info[$item_info[0]["size_id"]];
                }
                if (isset($colors_info[$item_info[0]["color_text_id"]])) {
                    $item_info[0]["description"] .= " - <b>Color:</b>" . $colors_info[$item_info[0]["color_text_id"]];
                }
            }
            $item_info[0]["buying_cost"] = 0;
            $item_info[0]["enable_price_var"] = $this->settings_info["enable_price_var"];
            $item_info[0]["price_var_round"] = $this->settings_info["price_var_round"];
            $item_info[0]["new_price_rate_to_lbp"] = $this->settings_info["new_price_rate_to_lbp"];
            $item_info[0]["base_price_rate_to_usd"] = $this->settings_info["base_price_rate_to_usd"];
        } else {
            $item_info = array();
            $customers = $this->model("customers");
            $item_info["customer"] = $customers->get_client_by_code($real_barcode);
        }
        echo json_encode($item_info);
    }
    public function get_item_by_barcode_for_change($barcode_, $_item_id)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $store = $this->model("store");
        $colors = $this->model("colors");
        $all_colors = $colors->getColorsText();
        $colors_info = array();
        for ($i = 0; $i < count($all_colors); $i++) {
            $colors_info[$all_colors[$i]["id"]] = $all_colors[$i]["name"];
        }
        $sizes = $this->model("sizes");
        $all_sizes = $sizes->getSizes();
        $sizes_info = array();
        for ($i = 0; $i < count($all_sizes); $i++) {
            $sizes_info[$all_sizes[$i]["id"]] = $all_sizes[$i]["name"];
        }
        $measure = $this->model("measures");
        $measures = $measure->getMeasures();
        $measures_info = array();
        for ($i = 0; $i < count($measures); $i++) {
            $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
        }
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $barcode = filter_var($barcode_, self::conversion_php_version_filter());
        $real_barcode = $barcode;
        $plu = 0;
        $plu_price = 0;
        if (substr($barcode, 0, 2) === $this->settings_info["plu_prefix"]) {
            $plu = 1;
            $real_barcode = substr($barcode, 0, 6);
            $plu_price = substr($barcode, 7, 5);
        }
        if (0 < $item_id) {
            $item_info = $items->get_item($item_id);
        } else {
            $item_info = $items->get_item_by_barcode($real_barcode);
        }
        for ($k = 0; $k < count($item_info); $k++) {
            $item_info[$k]["plu"] = $plu;
            if (isset($colors_info[$item_info[$k]["color_text_id"]])) {
                $item_info[$k]["color_text_id"] = $colors_info[$item_info[$k]["color_text_id"]];
            } else {
                $item_info[$k]["color_text_id"] = "Unknown";
            }
            if (isset($sizes_info[$item_info[$k]["size_id"]])) {
                $item_info[$k]["size_id"] = $sizes_info[$item_info[$k]["size_id"]];
            } else {
                $item_info[$k]["size_id"] = "Unknown";
            }
            if ($plu == 1) {
                $item_info[$k]["qty"] = $plu_price * 1 / $item_info[$k]["selling_price"];
            } else {
                $item_info[$k]["qty"] = 1;
            }
            $item_info[$k]["measure_label"] = "";
            if ($item_info[$k]["unit_measure_id"] != NULL) {
                $item_info[$k]["measure_label"] = $measures_info[$item_info[$k]["unit_measure_id"]];
            }
            $discounts = $this->model("discounts");
            $discounts_items = $discounts->get_all_items_under_discounts();
            $discounts_items_ids = array();
            $discounts_items_discount = array();
            for ($i = 0; $i < count($discounts_items); $i++) {
                $discounts_items_ids[$i] = $discounts_items[$i]["item_id"];
            }
            for ($i = 0; $i < count($discounts_items); $i++) {
                $discounts_items_discount[$discounts_items[$i]["item_id"]] = $discounts_items[$i]["discount_value"];
            }
            $item_info[$k]["discount"] = $item_info[$k]["discount"];
            if (in_array($item_info[$k]["id"], $discounts_items_ids)) {
                $item_info[$k]["discount"] = $discounts_items_discount[$item_info[$k]["id"]];
                if ($this->settings_info["discount_by_group_force_round"] == 1) {
                    $initial_price_is = $item_info[0]["selling_price"];
                    $final_price_will_be = (int) ($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100));
                    $discountValue = $initial_price_is - $final_price_will_be;
                    $item_info[0]["discount"] = $discountValue / $initial_price_is * 100;
                }
            }
            $item_info[$k]["plu_price"] = $plu_price;
            $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[$k]["id"]);
            $item_info[$k]["quantity"] = (double) $qty_store[0]["quantity"];
            $item_info[$k]["selling_price"] = $item_info[$k]["selling_price"];
            if ($item_info[$k]["vat"] == 0) {
                $item_info[$k]["vat_value"] = 0;
            } else {
                $item_info[$k]["vat_value"] = ($this->settings_info["vat"] - 1) * 100;
            }
            $item_info[$k]["enable_price_var"] = $this->settings_info["enable_price_var"];
            $item_info[$k]["price_var_round"] = $this->settings_info["price_var_round"];
            $item_info[$k]["new_price_rate_to_lbp"] = $this->settings_info["new_price_rate_to_lbp"];
            $item_info[$k]["base_price_rate_to_usd"] = $this->settings_info["base_price_rate_to_usd"];
            $item_info[$k]["buying_cost"] = 0;
        }
        echo json_encode($item_info);
    }
    public function get_item($id_)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $store = $this->model("store");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($id);
        $colors = $this->model("colors");
        $all_colors = $colors->getColorsText();
        $colors_info = array();
        for ($i = 0; $i < count($all_colors); $i++) {
            $colors_info[$all_colors[$i]["id"]] = $all_colors[$i]["name"];
        }
        $sizes = $this->model("sizes");
        $all_sizes = $sizes->getSizes();
        $sizes_info = array();
        for ($i = 0; $i < count($all_sizes); $i++) {
            $sizes_info[$all_sizes[$i]["id"]] = $all_sizes[$i]["name"];
        }
        self::tracking_pos("add", $id);
        $measure = $this->model("measures");
        $measures = $measure->getMeasures();
        $measures_info = array();
        for ($i = 0; $i < count($measures); $i++) {
            $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
        }
        $discounts = $this->model("discounts");
        $discounts_items = $discounts->get_all_items_under_discounts();
        $discounts_items_ids = array();
        $discounts_items_discount = array();
        for ($i = 0; $i < count($discounts_items); $i++) {
            $discounts_items_ids[$i] = $discounts_items[$i]["item_id"];
        }
        for ($i = 0; $i < count($discounts_items); $i++) {
            $discounts_items_discount[$discounts_items[$i]["item_id"]] = $discounts_items[$i]["discount_value"];
        }
        $item_info[0]["discount"] = $item_info[0]["discount"];
        if (in_array($item_info[0]["id"], $discounts_items_ids)) {
            $item_info[0]["discount"] = $discounts_items_discount[$item_info[0]["id"]];
            if ($this->settings_info["discount_by_group_force_round"] == 1) {
                $initial_price_is = $item_info[0]["selling_price"];
                $final_price_will_be = (int) ($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100));
                $discountValue = $initial_price_is - $final_price_will_be;
                $item_info[0]["discount"] = $discountValue / $initial_price_is * 100;
            }
        }
        if (is_null($item_info[0]["barcode"])) {
            $item_info[0]["barcode"] = "";
        }
        $item_info[0]["plu"] = 0;
        if (substr($item_info[0]["barcode"], 0, 2) === $this->settings_info["plu_prefix"]) {
            $item_info[0]["plu"] = 1;
        }
        $item_info[0]["measure_label"] = "";
        if ($item_info[0]["unit_measure_id"] != NULL) {
            $item_info[0]["measure_label"] = $measures_info[$item_info[0]["unit_measure_id"]];
        }
        $item_info[0]["discount"] = $item_info[0]["discount"];
        $item_info[0]["qty"] = 1;
        $item_info[0]["final_cost"] = $item_info[0]["buying_cost"];
        $item_info[0]["weight"] = $item_info[0]["weight"];
        if ($this->settings_info["show_size_and_color_on_pos"] == "1") {
            if (isset($sizes_info[$item_info[0]["size_id"]])) {
                $item_info[0]["description"] .= " - <b>Size: </b>" . $sizes_info[$item_info[0]["size_id"]];
            }
            if (isset($colors_info[$item_info[0]["color_text_id"]])) {
                $item_info[0]["description"] .= " - <b>Color: </b>" . $colors_info[$item_info[0]["color_text_id"]];
            }
        }
        $item_info[0]["plu_price"] = 0;
        $item_info[0]["composite_items"] = array();
        if ($item_info[0]["is_composite"] == 1) {
            $item_info[0]["composite_items"] = $items->get_all_composite_of_item($id);
            $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[0]["composite_items"][0]["item_id"]);
            $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
            $item_info[0]["description"] .= " | <b>" . floatval($item_info[0]["composite_items"][0]["qty"]) . " pieces</b>";
        } else {
            $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $id);
            $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
        }
        if ($this->settings_info["pos_hide_stock"] == 1 && $_SESSION["role"] == 2) {
            $item_info[0]["quantity"] = "";
        }
        $item_info[0]["enable_price_var"] = $this->settings_info["enable_price_var"];
        $item_info[0]["price_var_round"] = $this->settings_info["price_var_round"];
        $item_info[0]["new_price_rate_to_lbp"] = $this->settings_info["new_price_rate_to_lbp"];
        $item_info[0]["base_price_rate_to_usd"] = $this->settings_info["base_price_rate_to_usd"];
        $item_info[0]["enable_round"] = 1;
        if ($item_info[0]["sku_code"] == "undefined") {
            $item_info[0]["sku_code"] = "";
        }
        $item_info[0]["buying_cost"] = 0;
        echo json_encode($item_info);
    }
    public function testd()
    {
        $data = array();
        $data["settings"] = $this->settings_info;
        $this->view("pos/" . $this->settings_info["pos_path"] . "/testd", $data);
    }
    public function _default()
    {
        $data = array();
        $data["settings"] = $this->settings_info;
        include "application/lang/" . $this->settings_info["language"] . "/" . $this->settings_info["language"] . ".php";
        $data["mobile_packages"] = array();
        $data["mobile_days_packages"] = array();
        $data["devices_array"] = array();
        $mobile_store = $this->model("mobileStore");
        $packages = $mobile_store->getPackages();
        $devices = $mobile_store->getDevices($_SESSION["store_id"]);
        $days_packages = $mobile_store->getDaysPackages();
        for ($i = 0; $i < count($packages); $i++) {
            $data["mobile_packages"][$i] = $packages[$i];
        }
        for ($i = 0; $i < count($days_packages); $i++) {
            $data["mobile_days_packages"][$i] = $days_packages[$i];
        }
        for ($i = 0; $i < count($devices); $i++) {
            $data["devices_array"][$devices[$i]["operator_id"]] = $devices[$i]["id"];
        }
        $store = $this->model("store");
        $data["stores_from"] = $store->getStores_c();
        $data["stores_to"] = $store->getStores_c();
        $this->view("pos/" . $this->settings_info["pos_path"] . "/pos", $data);
    }
    public function cancelDiscount($_inv_id)
    {
        self::giveAccessTo(array(2, 4));
        $inv_id = filter_var($_inv_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $cashbox = $this->model("cashbox");
        $invoice_info = $invoice->getInvoiceById($inv_id);
        $invoice->cancelDiscount($inv_id);
        $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        echo json_encode(array());
    }
    public function posItems()
    {
        self::giveAccessTo(array(2, 4));
        $user = $this->model("user");
        $data = array();
        $data["users"] = $user->getAllUsersPOS();
        $this->view("pos_items", $data);
    }
    public function update_user_pos_col($_item_id, $_users_id)
    {
        self::giveAccessTo(array(2, 4));
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $users_id = filter_var($_users_id, self::conversion_php_version_filter());
        $items = $this->model("items");
        $items->update_user_pos_col($item_id, $users_id);
        echo json_encode(array());
    }
    public function get_users_assigned($_item_id)
    {
        self::giveAccessTo(array(2, 4));
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $data = $items->get_item_in_store($item_id);
        echo json_encode($data);
    }
    public function showInstantReport($store_id_)
    {
        self::giveAccessTo(array(2, 4));
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $pos = $this->model("pos");
        $report_info = $pos->getItemsForInstantReport($store_id);
        echo json_encode($report_info);
    }
    public function getPurchasedListOfCustomer($customer_id_)
    {
        self::giveAccessTo(array(2, 4));
        $customer_id = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
        $pos = $this->model("pos");
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $expenses = $this->model("expenses");
        $items_m = $this->model("items");
        $info = array();
        $info["vendor_id"] = $_SESSION["id"];
        $info["store_id"] = $_SESSION["store_id"];
        $items = array();
        $items["purchases"] = $pos->getPurchasedListOfCustomer($info, intval($customer_id));
        for ($i = 0; $i < count($items["purchases"]); $i++) {
            if (is_null($items["purchases"][$i]["item_id"])) {
                $items["purchases"][$i]["desc"] = $items["purchases"][$i]["descr"];
            } else {
                $item_info = $items_m->get_item($items["purchases"][$i]["item_id"]);
                $items["purchases"][$i]["desc"] = $item_info[0]["description"];
            }
            $items["purchases"][$i]["customer_id"] = $items["purchases"][$i]["customer_id"];
            $items["purchases"][$i]["customer"] = "";
            if (!is_null($items["purchases"][$i]["customer_id"])) {
                $customers_info = $customers->getCustomersById($items["purchases"][$i]["customer_id"]);
                $items["purchases"][$i]["customer"] = $customers_info[0]["name"];
            }
            $items["purchases"][$i]["invoice_date"] = $items["purchases"][$i]["creation_date"];
            $items["purchases"][$i]["closed"] = $items["purchases"][$i]["closed"];
            $items["purchases"][$i]["final_price_disc_qty"] = floor($items["purchases"][$i]["final_price_disc_qty"]);
            $items["purchases"][$i]["qty"] = floor($items["purchases"][$i]["qty"]);
        }
        echo json_encode($items);
    }
    public function getPurchasedList($date_, $customer_id_)
    {
        self::giveAccessTo(array(2, 4));
        $date = filter_var($date_, self::conversion_php_version_filter());
        $customer_id = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
        $pos = $this->model("pos");
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $expenses = $this->model("expenses");
        $items_m = $this->model("items");
        $payments = $this->model("payments");
        $info = array();
        $info["vendor_id"] = $_SESSION["id"];
        $info["store_id"] = $_SESSION["store_id"];
        $items = array();
        $items["purchases"] = $pos->getItemsPurchasedList($info, $date, intval($customer_id));
        $items["expenses"] = $expenses->getExpensesByDate($_SESSION["store_id"], $date);
        $items["payments"] = $payments->getPaymentsByDate($_SESSION["store_id"], $date);
        for ($i = 0; $i < count($items["payments"]); $i++) {
            $customers_info = $customers->getCustomersById($items["payments"][$i]["customer_id"]);
            $items["payments"][$i]["name"] = $customers_info[0]["name"];
        }
        for ($i = 0; $i < count($items["purchases"]); $i++) {
            if (is_null($items["purchases"][$i]["item_id"])) {
                $items["purchases"][$i]["desc"] = $items["purchases"][$i]["descr"];
            } else {
                $item_info = $items_m->get_item($items["purchases"][$i]["item_id"]);
                $items["purchases"][$i]["desc"] = $item_info[0]["description"];
            }
            $items["purchases"][$i]["customer_id"] = $items["purchases"][$i]["customer_id"];
            $items["purchases"][$i]["customer"] = "";
            if (!is_null($items["purchases"][$i]["customer_id"])) {
                $customers_info = $customers->getCustomersById($items["purchases"][$i]["customer_id"]);
                $items["purchases"][$i]["customer"] = $customers_info[0]["name"];
            }
            $items["purchases"][$i]["invoice_date"] = $items["purchases"][$i]["creation_date"];
            $items["purchases"][$i]["closed"] = $items["purchases"][$i]["closed"];
            $items["purchases"][$i]["auto_closed"] = $items["purchases"][$i]["auto_closed"];
            $items["purchases"][$i]["final_price_disc_qty"] = $items["purchases"][$i]["final_price_disc_qty"];
            $items["purchases"][$i]["qty"] = (double) $items["purchases"][$i]["qty"];
        }
        echo json_encode($items);
    }
    public function get_all_invoices_list($date_, $invoice_id_, $operation_type)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $settings = $this->model("settings");
        $cashbox = $this->model("cashbox");
        $customers = $this->model("customers");
        $store_id = $_SESSION["store_id"];
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $date = filter_var($date_, self::conversion_php_version_filter());
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
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
        $cashboxes_array = array();
        $cashbox_opened = $cashbox->get_all_opened_cashbox();
        for ($i = 0; $i < count($cashbox_opened); $i++) {
            $cashboxes_array[$cashbox_opened[$i]["id"]] = $cashbox_opened[$i];
        }
        $info = $invoice->getAllInvoices_list_filtered($store_id, $date_range, $this->settings_info, $operation_type);
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            $total_invoice_details = self::get_total_invoice_details($info[$i]);
            array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, $users_array[$info[$i]["employee_id"]]["username"]);
            $note = "";
            if (0 < strlen($info[$i]["payment_note"])) {
                $note = "&nbsp;<b>NOTE:</b>" . $info[$i]["payment_note"];
            }
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                if ($info[$i]["closed"] == 0) {
                    array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . " " . $customer[0]["middle_name"] . " " . $customer[0]["last_name"] . "</span>" . $note);
                } else {
                    array_push($tmp, $customer[0]["name"] . " " . $customer[0]["middle_name"] . " " . $customer[0]["last_name"] . "" . $note);
                }
            } else {
                array_push($tmp, $note);
            }
            $total_invoice_amount_lbp = "";
            if ($this->settings_info["usd_but_show_lbp_priority"] == 1) {
                $total_invoice_amount_lbp = "<span class='lbpsm'>(" . number_format(self::only_round_lbp($info[$i]["total_value"] * $info[$i]["rate"]), 0) . " LBP)</span> ";
            }
            array_push($tmp, self::value_format_custom($info[$i]["total_value"], $this->settings_info) . " " . $total_invoice_amount_lbp);
            $discount_lbp = "";
            if ($this->settings_info["usd_but_show_lbp_priority"] == 1) {
                $discount_lbp = "<span class='lbpsm'>(" . number_format(self::only_round_lbp(($info[$i]["total_value"] + $info[$i]["invoice_discount"]) * $info[$i]["rate"]) - self::only_round_lbp($info[$i]["total_value"] * $info[$i]["rate"])) . " LBP)</span> ";
            }
            array_push($tmp, self::value_format_custom($info[$i]["invoice_discount"], $this->settings_info) . " " . $discount_lbp);
            $total_invoice_after_discount_lbp = "";
            if ($this->settings_info["usd_but_show_lbp_priority"] == 1) {
                $total_invoice_after_discount_lbp = "<span class='lbpsm'>(" . number_format(self::only_round_lbp(($info[$i]["total_value"] + $info[$i]["invoice_discount"]) * $info[$i]["rate"]), 0) . " LBP)</span> ";
            }
            array_push($tmp, self::value_format_custom($info[$i]["total_value"] + $info[$i]["invoice_discount"], $this->settings_info) . " " . $total_invoice_after_discount_lbp);
            array_push($tmp, $total_invoice_details["tax"]);
            array_push($tmp, $total_invoice_details["freight"]);
            array_push($tmp, self::value_format_custom($total_invoice_details["total"], $this->settings_info));
            if ($info[$i]["closed"] == 1 && $info[$i]["auto_closed"] == 0) {
                array_push($tmp, $payment_method_info[$info[$i]["payment_method"]]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "");
            if ($this->settings_info["enable_delete_or_return_if_base_operator_is_closed"] == 1) {
                if ($info[$i]["cashbox_id"] != $_SESSION["cashbox_id"] && isset($cashboxes_array[$info[$i]["cashbox_id"]])) {
                    array_push($tmp, "1");
                } else {
                    array_push($tmp, "0");
                }
            } else {
                array_push($tmp, "0");
            }
            if ($this->settings_info["disable_edit_invoice_in_pos"] == 1) {
                array_push($tmp, "1");
            } else {
                array_push($tmp, "0");
            }
            if ($this->settings_info["disable_edit_and_delete_invoice_older_than"] < time() - strtotime($info[$i]["creation_date"])) {
                array_push($tmp, "1");
            } else {
                array_push($tmp, "0");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_other_branches_invoices_list($date_, $invoice_id_)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $settings = $this->model("settings");
        $customers = $this->model("customers");
        $store_id = $_SESSION["store_id"];
        $date = filter_var($date_, self::conversion_php_version_filter());
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
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
        $info = $invoice->getAllInvoices_other_branches_list($store_id, $date_range, $this->settings_info);
        $items_info_db = array();
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
                array_push($tmp, "");
            }
            array_push($tmp, self::value_format_custom($info[$i]["total_value"], $this->settings_info));
            array_push($tmp, self::value_format_custom($info[$i]["invoice_discount"], $this->settings_info));
            array_push($tmp, self::value_format_custom($info[$i]["total_value"] + $info[$i]["invoice_discount"], $this->settings_info));
            if ($info[$i]["closed"] == 1 && $info[$i]["auto_closed"] == 0) {
                array_push($tmp, $payment_method_info[$info[$i]["payment_method"]]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_sold_items($date_, $invoice_id_)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $customers = $this->model("customers");
        $store_id = 1;
        $date = filter_var($date_, self::conversion_php_version_filter());
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
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
        $info = array(0);
        if ($invoice_id == 0) {
            $info = $invoice->getAllItemsWasSold_switch($store_id, $date_range);
        } else {
            $info = $invoice->getAllItemsWasSoldByInvoice_id_switch($invoice_id);
        }
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($info[$i]["invoice_id"]));
            if ($info[$i]["item_id"] == NULL) {
                array_push($tmp, "");
                array_push($tmp, $info[$i]["description"]);
                array_push($tmp, "");
            } else {
                array_push($tmp, self::idFormat_INVIT($info[$i]["id"]));
                $item_info = $items->get_item($info[$i]["item_id"]);
                array_push($tmp, $item_info[0]["description"]);
                array_push($tmp, $item_info[0]["barcode"]);
            }
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . "</span>");
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, (double) $info[$i]["qty"]);
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100), $this->settings_info));
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_sold_items_with_vat_by_barcode($_barcode)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $customers = $this->model("customers");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $barcode = filter_var($_barcode, self::conversion_php_version_filter());
        $info = $invoice->getAllItemsWasSoldByBarcode_switch($_SESSION["store_id"], $barcode);
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            if ($this->settings_info["disable_edit_invoice_in_pos"] == 1) {
                array_push($tmp, "" . self::idFormat_invoice($info[$i]["invoice_id"]));
            } else {
                array_push($tmp, "<a onclick='show_invoice_to_change(" . $info[$i]["invoice_id"] . ")' >" . self::idFormat_invoice($info[$i]["invoice_id"])) . "</a>";
            }
            array_push($tmp, self::idFormat_INVIT($info[$i]["id"]));
            $item_info = $items->get_item($info[$i]["item_id"]);
            array_push($tmp, $item_info[0]["description"]);
            $ssize = "";
            if (isset($sizes_info_label[$item_info[0]["size_id"]])) {
                $ssize = $sizes_info_label[$item_info[0]["size_id"]];
            }
            $ccolor = "";
            if (isset($colors_info_label[$item_info[0]["color_text_id"]])) {
                $ccolor = $colors_info_label[$item_info[0]["color_text_id"]];
            }
            array_push($tmp, "S " . $ssize . " - C " . $ccolor);
            array_push($tmp, $item_info[0]["barcode"]);
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . "</span>");
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, (double) $info[$i]["qty"]);
            if ($info[$i]["vat"] == 1) {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100) * $info[$i]["vat_value"], $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100), $this->settings_info));
            }
            array_push($tmp, self::value_format_custom((double) $info[$i]["discount"], $this->settings_info) . " %");
            if ($info[$i]["vat"] == 1) {
                array_push($tmp, ((double) $info[$i]["vat_value"] - 1) * 100 . " %");
            } else {
                array_push($tmp, "0 %");
            }
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_sold_items_with_vat($date_, $invoice_id_, $operations_type)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $customers = $this->model("customers");
        $mobileStore = $this->model("mobileStore");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $cashbox = $this->model("cashbox");
        $store_id = $_SESSION["store_id"];
        $date = filter_var($date_, self::conversion_php_version_filter());
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $cashboxes_array = array();
        $cashbox_opened = $cashbox->get_all_opened_cashbox();
        for ($i = 0; $i < count($cashbox_opened); $i++) {
            $cashboxes_array[$cashbox_opened[$i]["id"]] = $cashbox_opened[$i];
        }
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
        $info = array(0);
        if ($invoice_id == 0) {
            $info = $invoice->getAllItemsWasSold_switch__($date_range, $this->settings_info, $operations_type);
        } else {
            $info = $invoice->getAllItemsWasSoldByInvoice_id_switch__($invoice_id, $this->settings_info);
        }
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $devices_array = array();
        $d = $mobileStore->get_devices_even_deleted();
        for ($i = 0; $i < count($d); $i++) {
            $devices_array[$d[$i]["id"]] = $d[$i];
        }
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $invoices_ids = array();
        for ($i = 0; $i < count($info); $i++) {
            array_push($invoices_ids, $info[$i]["invoice_id"]);
        }
        $imeis_items = array();
        if (0 < count($invoices_ids)) {
            $uniqueItems = $this->model("uniqueItems");
            $imeis_items_info = $uniqueItems->get_invoices_items($invoices_ids);
            for ($k = 0; $k < count($imeis_items_info); $k++) {
                if (!isset($imeis_items[$imeis_items_info[$k]["invoice_id"]]["item_id"])) {
                    $imeis_items[$imeis_items_info[$k]["invoice_id"]][$imeis_items_info[$k]["item_id"]] = "IMEI1: " . $imeis_items_info[$k]["code1"] . "<br/>IMEI2: " . $imeis_items_info[$k]["code2"];
                }
            }
        }
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, "<a href='#' onclick='show_invoice_to_change(" . $info[$i]["invoice_id"] . ")'>" . self::idFormat_invoice($info[$i]["invoice_id"]) . "</a>");
            if ($info[$i]["item_id"] == NULL) {
                array_push($tmp, "");
                array_push($tmp, $info[$i]["creation_date"]);
                array_push($tmp, $users_array[$info[$i]["employee_id"]]["username"]);
                $from_device = "";
                if (0 < $info[$i]["mobile_transfer_credits"]) {
                    $transfer_info = $invoice->get_transfer_info($info[$i]["id"]);
                    if (0 < count($transfer_info)) {
                        $from_device = $devices_array[$transfer_info[0]["device_id"]]["description"];
                    }
                }
                array_push($tmp, $info[$i]["description"] . " " . $from_device);
                array_push($tmp, "");
                array_push($tmp, "");
            } else {
                array_push($tmp, self::idFormat_INVIT($info[$i]["id"]));
                array_push($tmp, $info[$i]["creation_date"]);
                array_push($tmp, $users_array[$info[$i]["employee_id"]]["username"]);
                $item_info = $items->get_item($info[$i]["item_id"]);
                array_push($tmp, $item_info[0]["description"]);
                if ($item_info[0]["is_composite"] == 0) {
                    array_push($tmp, "S " . $sizes_info_label[$item_info[0]["size_id"]] . " - C " . $colors_info_label[$item_info[0]["color_text_id"]]);
                } else {
                    array_push($tmp, "");
                }
                $codes = "";
                if (isset($imeis_items[$info[$i]["invoice_id"]][$info[$i]["item_id"]])) {
                    $codes .= "<br/>" . $imeis_items[$info[$i]["invoice_id"]][$info[$i]["item_id"]];
                }
                array_push($tmp, $item_info[0]["barcode"] . $codes);
            }
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . " " . $customer[0]["middle_name"] . " " . $customer[0]["last_name"] . "</span>");
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, (double) $info[$i]["qty"]);
            if ($info[$i]["discount"] < 0) {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"] / (1 - $info[$i]["discount"] / 100), $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            }
            array_push($tmp, number_format($info[$i]["discount"], 2) . " %");
            if ($info[$i]["vat"] == 1) {
                array_push($tmp, ((double) $info[$i]["vat_value"] - 1) * 100 . " %");
            } else {
                array_push($tmp, 0 . " %");
            }
            if ($info[$i]["vat"] == 1) {
                if (0 <= $info[$i]["discount"]) {
                    array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100) * $info[$i]["vat_value"] * $info[$i]["qty"], $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * $info[$i]["vat_value"] * $info[$i]["qty"], $this->settings_info));
                }
            } else {
                if (0 <= $info[$i]["discount"]) {
                    array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100) * $info[$i]["qty"], $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * $info[$i]["qty"], $this->settings_info));
                }
            }
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, "");
            array_push($tmp, $info[$i]["pos_discounted"]);
            if ($this->settings_info["enable_delete_or_return_if_base_operator_is_closed"] == 1) {
                if ($info[$i]["cashbox_id"] != $_SESSION["cashbox_id"] && isset($cashboxes_array[$info[$i]["cashbox_id"]])) {
                    array_push($tmp, "1");
                } else {
                    array_push($tmp, "0");
                }
            } else {
                array_push($tmp, "0");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_items()
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $setting = self::getSettings();
        $tables_info = $items->getAllItems();
        $all_item_qty_in_store = $items->get_all_item_qty_in_store(1);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $data_array["data"] = array();
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $discounts = $this->model("discounts");
        $discounts_items = $discounts->get_all_items_under_discounts();
        $discounts_items_ids = array();
        $discounts_items_discount = array();
        for ($i = 0; $i < count($discounts_items); $i++) {
            $discounts_items_ids[$i] = $discounts_items[$i]["item_id"];
        }
        for ($i = 0; $i < count($discounts_items); $i++) {
            $discounts_items_discount[$discounts_items[$i]["item_id"]] = $discounts_items[$i]["discount_value"];
        }
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($tables_info[$i]["id"]));
            array_push($tmp, $tables_info[$i]["description"]);
            array_push($tmp, $tables_info[$i]["barcode"]);
            array_push($tmp, self::value_format_custom($tables_info[$i]["selling_price"], $this->settings_info));
            $tables_info[$i]["discount"] = self::value_format_custom($tables_info[$i]["discount"], $this->settings_info);
            if (in_array($tables_info[$i]["id"], $discounts_items_ids)) {
                $tables_info[$i]["discount"] = self::value_format_custom($discounts_items_discount[$tables_info[$i]["id"]], $this->settings_info);
            }
            array_push($tmp, $tables_info[$i]["discount"] . " %");
            array_push($tmp, (double) $qty[$tables_info[$i]["id"]]);
            if ($tables_info[$i]["color_text_id"] == NULL || $tables_info[$i]["color_text_id"] == "" || !isset($colors_info_label[$tables_info[$i]["color_text_id"]])) {
                array_push($tmp, "");
            } else {
                array_push($tmp, $colors_info_label[$tables_info[$i]["color_text_id"]]);
            }
            if ($tables_info[$i]["size_id"] == NULL || $tables_info[$i]["size_id"] == "" || !isset($sizes_info_label[$tables_info[$i]["size_id"]])) {
                array_push($tmp, "");
            } else {
                array_push($tmp, $sizes_info_label[$tables_info[$i]["size_id"]]);
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_items_new($_by_barcode, $_category, $_subcategory)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $by_barcode = filter_var($_by_barcode, self::conversion_php_version_filter());
        $category = filter_var($_category, self::conversion_php_version_filter());
        $subcategory = filter_var($_subcategory, self::conversion_php_version_filter());

        if ($by_barcode == "0") {
            $tables_info = $items->getAllItemsPOS($category, $subcategory);
        } else {
            $tables_info = $items->getAllItemsByBarcode($by_barcode);
        }

        $all_item_qty_in_store = $items->get_all_item_qty_in_store($_SESSION["store_id"]);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $data_array["data"] = array();
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $discounts = $this->model("discounts");
        $discounts_items = $discounts->get_all_items_under_discounts();
        $discounts_items_discount = array();
        for ($i = 0; $i < count($discounts_items); $i++) {
            $discounts_items_discount[$discounts_items[$i]["item_id"]] = $discounts_items[$i]["discount_value"];
        }
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($tables_info[$i]["id"]));
            array_push($tmp, $tables_info[$i]["sku_code"]);
            if (0 < strlen($tables_info[$i]["sku_code"])) {
                array_push($tmp, $tables_info[$i]["description"] . " #" . $tables_info[$i]["sku_code"]);
            } else {
                array_push($tmp, $tables_info[$i]["description"]);
            }
            if (strlen($tables_info[$i]["barcode"]) < 5) {
                array_push($tmp, sprintf("%05s", $tables_info[$i]["barcode"]));
            } else {
                array_push($tmp, $tables_info[$i]["barcode"]);
            }
            if ($this->settings_info["enable_wholasale"] == 0) {
                array_push($tmp, self::value_format_custom($tables_info[$i]["selling_price"], $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($tables_info[$i]["selling_price"], $this->settings_info) . " / " . self::value_format_custom($tables_info[$i]["wholesale_price"], $this->settings_info));
            }
            $tables_info[$i]["discount"] = number_format($tables_info[$i]["discount"], 2);
            if (isset($discounts_items_discount[$tables_info[$i]["id"]])) {
                $tables_info[$i]["discount"] = number_format($discounts_items_discount[$tables_info[$i]["id"]], 2);
            }
            if ($this->settings_info["discount_by_group_force_round"] == 1 && isset($discounts_items_discount[$tables_info[$i]["id"]])) {
                $tables_info[$i]["discount"] = number_format($discounts_items_discount[$tables_info[$i]["id"]], 2);
                $initial_price_is = $tables_info[$i]["selling_price"];
                $final_price_will_be = (int) ($tables_info[$i]["selling_price"] * (1 - $tables_info[$i]["discount"] / 100));
                $discountValue = $initial_price_is - $final_price_will_be;
                $tables_info[$i]["discount"] = $discountValue / $initial_price_is * 100;
            }
            if (0 < $tables_info[$i]["discount"]) {
                array_push($tmp, "<span class='discount'>" . number_format($tables_info[$i]["discount"], 2) . " %</span>");
            } else {
                array_push($tmp, "0 %");
            }
            if ($tables_info[$i]["vat"] == 1) {
                array_push($tmp, "<span class='vat'>" . ($this->settings_info["vat"] - 1) * 100 . " %</span>");
            } else {
                array_push($tmp, "0 %");
            }
            $price_after_discount = $tables_info[$i]["selling_price"] - $tables_info[$i]["selling_price"] * $tables_info[$i]["discount"] / 100;
            if ($tables_info[$i]["vat"] == 1) {
                $price_after_discount = $price_after_discount * $this->settings_info["vat"];
            }
            array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
            if ($this->settings_info["pos_hide_stock"] == 1 && $_SESSION["role"] == 2) {
                array_push($tmp, "");
            } else {
                array_push($tmp, (double) $qty[$tables_info[$i]["id"]]);
            }
            if ($tables_info[$i]["color_text_id"] == NULL || $tables_info[$i]["color_text_id"] == "" || !isset($colors_info_label[$tables_info[$i]["color_text_id"]])) {
                array_push($tmp, "");
            } else {
                array_push($tmp, $colors_info_label[$tables_info[$i]["color_text_id"]]);
            }
            if ($tables_info[$i]["size_id"] == NULL || $tables_info[$i]["size_id"] == "" || !isset($sizes_info_label[$tables_info[$i]["size_id"]])) {
                array_push($tmp, "");
            } else {
                array_push($tmp, $sizes_info_label[$tables_info[$i]["size_id"]]);
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }

       echo json_encode($data_array);
    }
    public function getItemsForPos()
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $info = array();
        $itemsData = $items->getItemsForPos($info);
        echo json_encode($itemsData);
    }
    public function getItemsForPosQty($item_id_)
    {
        self::giveAccessTo(array(2, 4));
        $item_id = filter_var($item_id_, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $info = $items->get_item_qty_in_store($item_id, $_SESSION["store_id"]);
        echo json_encode($info);
    }
    public function getPurchasesItemsOfInvoice($invoice_id_)
    {
        self::giveAccessTo(array(2, 4));
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoices = $this->model("invoice");
        $items = $this->model("items");
        $info = $invoices->getItemsOfInvoice($invoice_id);
        $info_return = array();
        for ($i = 0; $i < count($info); $i++) {
            $info_return[$i]["id"] = $info[$i]["id"];
            $info_return[$i]["item_id"] = $info[$i]["item_id"];
            $info_return[$i]["qty"] = (double) $info[$i]["qty"];
            $info_return[$i]["final_price_disc_qty"] = self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info);
            $info_return[$i]["selling_price"] = self::value_format_custom($info[$i]["selling_price"], $this->settings_info);
            if (is_null($info[$i]["item_id"])) {
                $info_return[$i]["description"] = $info[$i]["description"];
            } else {
                $item_info = $items->get_item($info[$i]["item_id"]);
                $info_return[$i]["description"] = $item_info[0]["description"];
            }
        }
        echo json_encode($info_return);
    }
    public function getPurchases($store_id_, $date_)
    {
        self::giveAccessTo(array(2, 4));
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $invoices = $this->model("invoice");
        $info = $invoices->getPurchases($store_id, $date);
        for ($i = 0; $i < count($info); $i++) {
            $info[$i]["total_value"] = self::value_format_custom($info[$i]["total_value"], $this->settings_info);
            $info[$i]["invoice_discount"] = self::value_format_custom($info[$i]["invoice_discount"], $this->settings_info);
        }
        echo json_encode($info);
    }
    public function closeCashbox()
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $cashbox->closeCashbox($_SESSION["store_id"], $_SESSION["id"]);
        if ($this->settings_info["telegram_enable"] == 1) {
            $total_cashbox = self::_get_full_report_table(1, $_SESSION["cashbox_id"]);
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $info = array();
            $info["message"] = "<strong>Cashbox closed:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            if (0 < $total_cashbox["net_lbp"]) {
                $info["message"] .= "<strong>LBP:</strong> " . number_format($total_cashbox["net_lbp"], 0) . " \n";
            }
            if (0 < $total_cashbox["net_usd"]) {
                $info["message"] .= "<strong>USD:</strong> " . number_format($total_cashbox["net_usd"], 0) . " \n";
            }
            self::send_to_telegram($info, 1);
        }
        echo json_encode(array());
    }
    public function getCashBox()
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $info = array();
        $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        $cashbox_info = $cashbox->geCashboxById($_SESSION["cashbox_id"]);
        $info["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"], 2), $this->settings_info);
        $info["cashBox_lbp"] = 0;
        $info["cashBox_usd"] = 0;
        if ($this->settings_info["usd_but_show_lbp_priority"] == 1) {
            $info["cashBox_lbp"] = number_format($cashbox->getCashboxDetails($_SESSION["cashbox_id"], 2) + $cashbox_info[0]["cashbox_lbp"], 0);
            $info["cashBox_usd"] = number_format($cashbox->getCashboxDetails($_SESSION["cashbox_id"], 1) + $cashbox_info[0]["cash"], 2);
        }
        echo json_encode($info);
    }
    public function delete_invoice($_id)
    {
        self::giveAccessTo(array(2));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $cashbox = $this->model("cashbox");
        $items = $this->model("items");
        $payments = $this->model("payments");
        $store = $this->model("store");
        $invoice_details = $invoice->getInvoiceById($id);
        $puchased_item = $invoice->getItemsOfInvoice($id);
        $cashb = $cashbox->getTodayCashbox($_SESSION["store_id"], $_SESSION["id"]);
        $composer_items = array();
        for ($i = 0; $i < count($puchased_item); $i++) {
            $items_info = array();
            if ($puchased_item[$i]["item_id"] != NULL) {
                $items_info = $items->get_item($puchased_item[$i]["item_id"]);
                if (0 < $items_info[0]["complex_item_id"]) {
                    $tmp__ = array();
                    $tmp__["item_id"] = $puchased_item[$i]["item_id"];
                    $tmp__["item_qty"] = $puchased_item[$i]["qty"];
                    array_push($composer_items, $tmp__);
                }
                $info = array();
                $info["id"] = $puchased_item[$i]["id"];
                if ($puchased_item[$i]["item_id"] != NULL) {
                    $info["item_id"] = $puchased_item[$i]["item_id"];
                } else {
                    $info["item_id"] = "null";
                }
                $info["mobile_transfer_credits"] = "NULL";
                $info["description"] = $puchased_item[$i]["description"];
                $info["invoice_id"] = $puchased_item[$i]["invoice_id"];
                $info["custom_item"] = $puchased_item[$i]["custom_item"];
                $info["qty"] = $puchased_item[$i]["qty"];
                $info["buying_cost"] = $puchased_item[$i]["buying_cost"] * $info["qty"];
                $info["vat"] = $puchased_item[$i]["vat"];
                $info["vat_value"] = $puchased_item[$i]["vat_value"];
                $info["selling_price"] = $puchased_item[$i]["selling_price"];
                $info["discount"] = $puchased_item[$i]["discount"];
                $info["final_price_disc_qty"] = $puchased_item[$i]["final_price_disc_qty"];
                $info["returned_by_vendor_id"] = $_SESSION["id"];
                $info["returned_to_store_id"] = $_SESSION["store_id"];
                if (0 < count($cashb)) {
                    $info["cashbox_id"] = $cashb[0]["id"];
                } else {
                    $info["cashbox_id"] = 0;
                }
                if ($puchased_item[$i]["item_change_cashbox"] == 0) {
                    $info["old_cashbox_id"] = $invoice_details[0]["cashbox_id"];
                } else {
                    $info["old_cashbox_id"] = $puchased_item[$i]["item_change_cashbox"];
                }
                $invoice->returnPurchasedItem($info);
                $invoice->reduceQtyOfPurchasedItem($info["id"], $info["invoice_id"], $info["qty"]);
                if (!is_null($puchased_item[$i]["item_id"])) {
                    $store_info = array();
                    $store_info["store_id"] = $_SESSION["store_id"];
                    $store_info["user_id"] = $_SESSION["id"];
                    if ($_SESSION["role"] == 1) {
                        $store_info["source"] = "soldbyadmin-" . $id;
                    } else {
                        $store_info["source"] = "pos";
                    }
                    if (0 < count($items_info)) {
                        if ($items_info[0]["is_composite"] == 1) {
                            $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                            $store_info["qty"] = $all_composite_of_item[0]["qty"] * $info["qty"];
                            $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                            if ($all_composite_of_item[0]["is_pack"] == 0) {
                                $store_info["qty"] = $all_composite_of_item[0]["qty"] * $info["qty"];
                                $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                                $store_info["source"] = "pos";
                                $store->add_qty($store_info);
                            } else {
                                $store_info["qty"] = $info["qty"];
                                $store_info["item_id"] = $all_composite_of_item[0]["composite_item_id"];
                                $store_info["source"] = "pos";
                                $store->add_pack_qty($store_info);
                            }
                        } else {
                            $store_info["qty"] = $info["qty"];
                            $store_info["item_id"] = $info["item_id"];
                            $store->add_qty($store_info);
                        }
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $info["item_id"];
                        $store->add_qty($store_info);
                    }
                }
                if (is_null($puchased_item[$i]["item_id"]) && 0 < $puchased_item[$i]["mobile_transfer_credits"]) {
                    $mobileStore = $this->model("mobileStore");
                    $return_pkg_info = $mobileStore->getPackage($puchased_item[$i]["mobile_transfer_credits"]);
                    if ($return_pkg_info[0]["days"] == 0) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[$i]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"] + (double) $transInfo[0]["sms_fees"];
                        $mobileStore->updateCredits($retur_cr);
                        if (0 < (double) $transInfo[0]["sms_fees"]) {
                            $mobileStore->set_fees_as_returned($transInfo[0]["id"], 0, 0, 0, 0);
                        }
                    }
                    if (0 < $return_pkg_info[0]["days"]) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[$i]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"];
                        $mobileStore->updateCredits($retur_cr);
                        if (0 < (double) $transInfo[0]["sms_fees"]) {
                            $mobileStore->set_fees_as_returned($transInfo[0]["id"], 0, 0, 0, 0);
                        }
                    }
                }
                $payment_info = array();
                $payment_info["invoice_id"] = $info["invoice_id"];
                $payment_info["value"] = 0 - $info["final_price_disc_qty"];
                $payment_info["vendor_id"] = $_SESSION["id"];
                $payment_info["store_id"] = $_SESSION["store_id"];
                $payments->add_payment($payment_info);
            }
            if ($puchased_item[$i]["item_id"] !== NULL) {
                $uniqueItems = $this->model("uniqueItems");
                $uniqueItems->clean_unique_item($id, $puchased_item[$i]["item_id"]);
            }
        }
        $last = $invoice->getItemsOfInvoice($id);
        $invoice->update_return_and_set_deleted($id);
        if ($this->settings_info["usd_but_show_lbp_priority"] == 1 && $invoice_details[0]["closed"] == 1) {
            $info_changes = array();
            $info_changes["invoice_id"] = $id;
            $info_changes["return_value"] = $invoice_details[0]["total_value"] + $invoice_details[0]["invoice_discount"];
            $info_changes["added_value"] = 0;
            $info_changes["cashbox_id"] = $_SESSION["cashbox_id"];
            $info_changes["old_cashbox_id"] = $invoice_details[0]["cashbox_id"];
            $info_changes["cash_usd_to_return"] = 0;
            $info_changes["cash_lbp_to_return"] = 0;
            $info_changes["returned_cash_lbp"] = 0;
            $info_changes["returned_cash_usd"] = $invoice_details[0]["total_value"] + $invoice_details[0]["invoice_discount"];
            $info_changes["cash_lbp_in"] = 0;
            $info_changes["cash_usd_in"] = 0;
            $info_changes["rate"] = $invoice_details[0]["rate"];
            $info_changes["invoice_item_id"] = 0;
            $info_changes["invoice_item_return_id"] = 0;
            $info_changes["only_return"] = 1;
            $cashbox->add_change($info_changes);
        }
        if (count($last) == 0) {
            $invoice->delete_invoice($id);
            if ($this->settings_info["telegram_enable"] == 1) {
                $users = $this->model("user");
                $employees_info = $users->getAllUsersEvenDeleted();
                $employees_info_array = array();
                for ($i = 0; $i < count($employees_info); $i++) {
                    $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                }
                $store = $this->model("store");
                $store_info = $store->getStoresById($_SESSION["store_id"]);
                $info = array();
                $info["message"] = "<strong>DELETE -  INVOICE ID:</strong> " . $id . " \n";
                $info["message"] .= "<strong>DELETED BY:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                $info["message"] .= "<strong>DATE:</strong> " . date("Y-m-d H:i:s") . " \n";
                $info["message"] .= "<strong>BRANCH:</strong> " . $store_info[0]["name"] . " \n";
                $info["message"] .= "\n";
                self::send_to_telegram($info, 1);
            }
        }
        $invoice->calculate_total_profit_for_invoice($id);
        if (isset($_SESSION["cashbox_id"])) {
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        }
        if (0 < $invoice_details[0]["customer_id"]) {
            $customers_class = $this->model("customers");
            $customers_class->bal_need_update($invoice_details[0]["customer_id"]);
        }
        for ($i = 0; $i < count($composer_items); $i++) {
            $store->return_qty_of_composite($composer_items[$i]);
        }
        echo json_encode(array());
    }
    public function edit_invoice_change()
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $cashbox = $this->model("cashbox");
        $store = $this->model("store");
        $invoice_id = filter_input(INPUT_POST, "invoice_id", FILTER_SANITIZE_NUMBER_INT);
        $cash_details = array();
        $cash_details["cash_usd"] = filter_input(INPUT_POST, "cash_usd", FILTER_SANITIZE_NUMBER_INT);
        $cash_details["cash_lbp"] = filter_input(INPUT_POST, "cash_lbp", FILTER_SANITIZE_NUMBER_INT);
        $cash_details["r_cash_usd_action"] = filter_input(INPUT_POST, "r_cash_usd_action", FILTER_SANITIZE_NUMBER_INT);
        $cash_details["r_cash_lbp_action"] = filter_input(INPUT_POST, "r_cash_lbp_action", FILTER_SANITIZE_NUMBER_INT);
        $cash_details["cash_lbp_to_return"] = filter_input(INPUT_POST, "r_cash_lbp", FILTER_SANITIZE_NUMBER_INT);
        $cash_details["cash_usd_to_return"] = filter_input(INPUT_POST, "r_cash_usd", FILTER_SANITIZE_NUMBER_INT);
        $change_msg = "";
        if (!isset($cash_usd_r)) {
            $cash_usd_r = 0;
        }
        if (!isset($cash_lbp_r)) {
            $cash_lbp_r = 0;
        }
        $invoice_info = $invoice->getInvoiceById($invoice_id);
        $return_value = 0;
        $added_value = 0;
        $change_msg .= "<strong>RETURNED ITEMS:</strong>\n";
        if (0 < count($invoice_info)) {
            if (isset($_POST["originial_edit_qty_"])) {
                foreach ($_POST["originial_edit_qty_"] as $key => $value) {
                    $key = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                    $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if (0 < $value) {
                        $puchased_item = $invoice->get_item_from_invoice($key);
                        $invoice_details = $invoice->getInvoiceById($puchased_item[0]["invoice_id"]);
                        if (!is_null($puchased_item[0]["item_id"])) {
                            $store_info = array();
                            $store_info["store_id"] = $_SESSION["store_id"];
                            $store_info["user_id"] = $_SESSION["id"];
                            $items_info = array();
                            if ($puchased_item[0]["item_id"] != NULL) {
                                $items_info = $items->get_item($puchased_item[0]["item_id"]);
                            }
                            $info = array();
                            $info["id"] = $key;
                            if ($puchased_item[0]["item_id"] != NULL) {
                                $info["item_id"] = $puchased_item[0]["item_id"];
                            } else {
                                $info["item_id"] = "null";
                            }
                            $info["mobile_transfer_credits"] = "NULL";
                            $info["description"] = $puchased_item[0]["description"];
                            $info["invoice_id"] = $puchased_item[0]["invoice_id"];
                            $info["custom_item"] = $puchased_item[0]["custom_item"];
                            $remain = 0;
                            if ($puchased_item[0]["qty"] <= $value) {
                                $info["qty"] = $puchased_item[0]["qty"];
                                $remain = 0;
                            } else {
                                $info["qty"] = $value;
                                $remain = $puchased_item[0]["qty"] - $value;
                            }
                            $change_msg .= "<strong>ITEM:</strong>" . $items_info[0]["description"] . "(" . $info["item_id"] . ") \n";
                            $change_msg .= "<strong>QTY:</strong> -" . $value . "\n";
                            $info["buying_cost"] = $puchased_item[0]["buying_cost"] * $info["qty"];
                            $info["vat"] = $puchased_item[0]["vat"];
                            $info["vat_value"] = $puchased_item[0]["vat_value"];
                            $info["selling_price"] = $puchased_item[0]["selling_price"] * $info["qty"];
                            $info["discount"] = (double) $puchased_item[0]["discount"];
                            $info["final_price_disc_qty"] = $puchased_item[0]["final_price_disc_qty"];
                            $return_value_tmp = $info["selling_price"];
                            if (0 < $info["discount"]) {
                                $return_value_tmp = $return_value_tmp * (1 - $info["discount"] / 100);
                            }
                            if ($info["vat"] == 1) {
                                $return_value_tmp = $return_value_tmp * $info["vat_value"];
                            }
                            $return_value += $return_value_tmp;
                            $info["returned_by_vendor_id"] = $_SESSION["id"];
                            $info["returned_to_store_id"] = $_SESSION["store_id"];
                            $cashb = $cashbox->getTodayCashbox($_SESSION["store_id"], $_SESSION["id"]);
                            $info["cashbox_id"] = $cashb[0]["id"];
                            $info["old_cashbox_id"] = $invoice_details[0]["cashbox_id"];
                            $return_id = $invoice->returnPurchasedItem($info);
                            $invoice->set_returned_as_not_only_return($return_id);
                            $invoice->set_returned_invoice_item_id($return_id, $puchased_item[0]["id"]);
                            $invoice->reduceQtyOfPurchasedItem($info["id"], $info["invoice_id"], $info["qty"]);
                            if (!is_null($puchased_item[0]["item_id"])) {
                                $store_info = array();
                                $store_info["store_id"] = $_SESSION["store_id"];
                                $store_info["user_id"] = $_SESSION["id"];
                                if (0 < count($items_info)) {
                                    if ($items_info[0]["is_composite"] == 1) {
                                        $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                                        $store_info["qty"] = $all_composite_of_item[0]["qty"] * $info["qty"];
                                        $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                                    } else {
                                        $store_info["qty"] = $info["qty"];
                                        $store_info["item_id"] = $info["item_id"];
                                    }
                                } else {
                                    $store_info["qty"] = $info["qty"];
                                    $store_info["item_id"] = $info["item_id"];
                                }
                                $store_info["source"] = "pos";
                                $store->add_qty($store_info);
                            }
                        }
                    }
                }
            }
            $change_msg .= "\n";
            $change_msg .= "<strong>ADDED ITEMS:</strong>\n";
            if (isset($_POST["qty_"])) {
                foreach ($_POST["qty_"] as $key => $value) {
                    $item_to_add = array();
                    $items_info = $items->get_item($key);
                    $item_to_add["invoice_id"] = $invoice_id;
                    $item_to_add["item_id"] = $key;
                    $item_to_add["qty"] = $value;
                    $item_to_add["custom_item"] = 0;
                    $item_to_add["mobile_transfer_item"] = 0;
                    $item_to_add["manual_discounted"] = 0;
                    $item_to_add["mobile_transfer_device_id"] = 0;
                    $item_to_add["buying_cost"] = $items_info[0]["buying_cost"];
                    $item_to_add["is_official"] = $items_info[0]["is_official"];
                    $item_to_add["mobile_transfer_item"] = 0;
                    $item_to_add["vat"] = $items_info[0]["vat"];
                    $item_to_add["selling_price"] = $_POST["price_inv_ch_"][$key];
                    $item_to_add["discount"] = (double) $_POST["disc_inv_ch_"][$key];
                    $item_to_add["is_composite"] = $items_info[0]["is_composite"];
                    $item_to_add["vat_value"] = $this->settings_info["vat"];
                    if ($item_to_add["vat"] == 0) {
                        $item_to_add["final_cost"] = $item_to_add["buying_cost"] * $item_to_add["qty"];
                    } else {
                        $item_to_add["final_cost"] = $item_to_add["buying_cost"] * floatval($this->settings_info["vat"]) * $item_to_add["qty"];
                    }
                    if ($item_to_add["vat"] == 0) {
                        $item_to_add["final_price"] = ($item_to_add["selling_price"] - $item_to_add["selling_price"] * $item_to_add["discount"] / 100) * $item_to_add["qty"];
                    } else {
                        $item_to_add["final_price"] = ($item_to_add["selling_price"] - $item_to_add["selling_price"] * $item_to_add["discount"] / 100) * $item_to_add["qty"] * floatval($item_to_add["vat_value"]);
                    }
                    $added_value += $item_to_add["final_price"];
                    $item_to_add["profit"] = $item_to_add["final_price"] - $item_to_add["final_cost"];
                    $inv_it_id = $invoice->addItemsToInvoice($item_to_add);
                    $invoice->set_as_new_item($inv_it_id);
                    if ($invoice_info[0]["cashbox_id"] != $_SESSION["cashbox_id"]) {
                        $cashbox->updateItemChangeInfo($inv_it_id, $_SESSION["cashbox_id"]);
                    }
                    if ($items_info[0]["is_composite"] == 0) {
                        $store->reduce_qty($_SESSION["store_id"], $key, $value, $_SESSION["id"]);
                    }
                    $change_msg .= "<strong>ITEM:</strong>" . $items_info[0]["description"] . "(" . $key . ") \n";
                    $change_msg .= "<strong>QTY:</strong> +" . $value . "\n";
                }
            }
            $info_changes = array();
            $info_changes["invoice_id"] = $invoice_id;
            $info_changes["return_value"] = $return_value;
            $info_changes["added_value"] = $added_value;
            $info_changes["cashbox_id"] = $_SESSION["cashbox_id"];
            $info_changes["old_cashbox_id"] = $invoice_info[0]["cashbox_id"];
            $info_changes["cash_usd_to_return"] = $cash_details["r_cash_usd"];
            $info_changes["cash_lbp_to_return"] = $cash_details["r_cash_lbp"];
            $info_changes["returned_cash_lbp"] = $cash_details["r_cash_lbp_action"];
            $info_changes["returned_cash_usd"] = $cash_details["r_cash_usd_action"];
            $info_changes["cash_lbp_in"] = $cash_details["cash_lbp"];
            $info_changes["cash_usd_in"] = $cash_details["cash_usd"];
            $info_changes["rate"] = $this->settings_info["usdlbp_rate"];
            $info_changes["invoice_item_id"] = 0;
            $info_changes["invoice_item_return_id"] = 0;
            if (isset($_POST["qty_"])) {
                $info_changes["only_return"] = 0;
            } else {
                $info_changes["only_return"] = 1;
            }
            if (!isset($info_changes["cash_usd_to_return"]) || $info_changes["cash_usd_to_return"] == "") {
                $info_changes["cash_usd_to_return"] = 0;
            }
            if (!isset($info_changes["cash_lbp_to_return"]) || $info_changes["cash_lbp_to_return"] == "") {
                $info_changes["cash_lbp_to_return"] = 0;
            }
            if (!isset($info_changes["returned_cash_lbp"]) || $info_changes["returned_cash_lbp"] == "") {
                $info_changes["returned_cash_lbp"] = 0;
            }
            if (!isset($info_changes["returned_cash_usd"]) || $info_changes["returned_cash_usd"] == "") {
                $info_changes["returned_cash_usd"] = 0;
            }
            if (!isset($info_changes["cash_lbp_in"]) || $info_changes["cash_lbp_in"] == "") {
                $info_changes["cash_lbp_in"] = 0;
            }
            if (!isset($info_changes["cash_usd_in"]) || $info_changes["cash_usd_in"] == "") {
                $info_changes["cash_usd_in"] = 0;
            }
            if ($invoice_info[0]["closed"] == 0 && $invoice_info[0]["other_branche"] == 0) {
                $info_changes["cash_lbp_in"] = 0;
                $info_changes["cash_usd_in"] = 0;
                $info_changes["returned_cash_usd"] = 0;
                $info_changes["returned_cash_lbp"] = 0;
                $info_changes["cash_usd_to_return"] = 0;
                $info_changes["cash_lbp_to_return"] = 0;
            }
            $cashbox->add_change($info_changes);
            $invoice->calculate_total_value($invoice_id);
            $invoice->calculate_total_profit_for_invoice($invoice_id);
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
            $invoice->resend_email($invoice_id);
            if ($this->settings_info["telegram_enable"] == 1) {
                $users = $this->model("user");
                $employees_info = $users->getAllUsersEvenDeleted();
                $employees_info_array = array();
                for ($i = 0; $i < count($employees_info); $i++) {
                    $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                }
                $store = $this->model("store");
                $store_info = $store->getStoresById($_SESSION["store_id"]);
                $info = array();
                $info["message"] = "<strong>EDIT INVOICE ID:</strong> " . $invoice_id . " \n";
                $info["message"] .= "<strong>EDITED BY:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                $info["message"] .= "<strong>DATE:</strong> " . date("Y-m-d H:i:s") . " \n";
                $info["message"] .= "<strong>BRANCH:</strong> " . $store_info[0]["name"] . " \n";
                $info["message"] .= "\n";
                $info["message"] .= $change_msg;
                self::send_to_telegram($info, 1);
            }
        }
        echo json_encode(array());
    }
    public function get_item_invoice_info($id_)
    {
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $return = $invoice->get_item_from_invoice_with_details($id);
        $return[0]["unit_selling"] = $return[0]["final_price_disc_qty"] / $return[0]["qty"];
        $return[0]["rate_formated"] = number_format($return[0]["rate"], 0);
        $return[0]["current_rate"] = $this->settings_info["usdlbp_rate"];
        $return[0]["current_rate_formated"] = number_format($this->settings_info["usdlbp_rate"], 0);
        $return[0]["return_lbp_current_rate"] = $return[0]["unit_selling"] * $this->settings_info["usdlbp_rate"];
        $return[0]["return_lbp_invoice_rate"] = $return[0]["unit_selling"] * $return[0]["rate"];
        $return[0]["return_lbp_current_rate_f"] = number_format($return[0]["unit_selling"] * $this->settings_info["usdlbp_rate"], 0);
        $return[0]["return_lbp_invoice_rate_f"] = number_format($return[0]["unit_selling"] * $return[0]["rate"], 0);
        echo json_encode($return);
    }
    public function check_if_credit_transfer($id_)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $info = $invoice->check_if_fees($id);
        if (0 < count($info)) {
            echo json_encode($info);
        } else {
            echo json_encode(array());
        }
    }
    public function returnBackItems($id_, $qty_, $return_sms_fees, $_extra_sms_fees, $on_customer_acc_id, $return_lbp, $return_usd)
    {
        self::giveAccessTo(array(2, 4));
        if ($_SESSION["demo"] == 1 || !isset($_SESSION["cashbox_id"])) {
            $return_info = array();
            $return_info["remain"] = 0;
            $return_info["total_price"] = 0;
            $return_info["returned_id"] = 0;
            echo json_encode($return_info);
        } else {
            $invoice = $this->model("invoice");
            $items = $this->model("items");
            $payments = $this->model("payments");
            $cashbox = $this->model("cashbox");
            $store = $this->model("store");
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $qty = filter_var($qty_, FILTER_SANITIZE_NUMBER_INT);
            $extra_sms_fees = filter_var($_extra_sms_fees, FILTER_SANITIZE_NUMBER_INT);
            $puchased_item = $invoice->get_item_from_invoice($id);
            $invoice_details = $invoice->getInvoiceById($puchased_item[0]["invoice_id"]);
            $items_info = array();
            if ($puchased_item[0]["item_id"] != NULL) {
                $items_info = $items->get_item($puchased_item[0]["item_id"]);
            }
            $info = array();
            $info["id"] = $id;
            if ($puchased_item[0]["item_id"] != NULL) {
                $info["item_id"] = $puchased_item[0]["item_id"];
            } else {
                $info["item_id"] = "null";
            }
            $info["mobile_transfer_credits"] = "NULL";
            $info["description"] = $puchased_item[0]["description"];
            $info["invoice_id"] = $puchased_item[0]["invoice_id"];
            $info["custom_item"] = $puchased_item[0]["custom_item"];
            $remain = 0;
            if ($puchased_item[0]["qty"] <= $qty) {
                $info["qty"] = $puchased_item[0]["qty"];
                $remain = 0;
            } else {
                $info["qty"] = $qty;
                $remain = $puchased_item[0]["qty"] - $qty;
            }
            $info["buying_cost"] = $puchased_item[0]["buying_cost"] * $info["qty"];
            $info["vat"] = $puchased_item[0]["vat"];
            $info["vat_value"] = $puchased_item[0]["vat_value"];
            $info["selling_price"] = $puchased_item[0]["selling_price"] * $info["qty"];
            $info["discount"] = $puchased_item[0]["discount"];
            $info["final_price_disc_qty"] = $puchased_item[0]["final_price_disc_qty"];
            $info["returned_by_vendor_id"] = $_SESSION["id"];
            $info["returned_to_store_id"] = $_SESSION["store_id"];
            $cashb = $cashbox->getTodayCashbox($_SESSION["store_id"], $_SESSION["id"]);
            $info["cashbox_id"] = $cashb[0]["id"];
            if ($puchased_item[0]["item_change_cashbox"] == 0) {
                $info["old_cashbox_id"] = $invoice_details[0]["cashbox_id"];
            } else {
                $info["old_cashbox_id"] = $puchased_item[0]["item_change_cashbox"];
            }
            $returned_id = $invoice->returnPurchasedItem($info);
            if (0 < $on_customer_acc_id) {
                $invoice->assign_to_acc($returned_id, $on_customer_acc_id);
            }
            $invoice->reduceQtyOfPurchasedItem($info["id"], $info["invoice_id"], $info["qty"]);
            $invoice->calculate_total_profit_for_invoice($info["invoice_id"]);
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
            if ($puchased_item[0]["international_calls"] == 1) {
                $query___ = "update settings set value=value+" . $puchased_item[0]["base_usd_price"] . " where name='international_calls_balance'";
                my_sql::query($query___);
            }
            $return_value = $info["selling_price"];
            if (0 < $info["discount"]) {
                $return_value = $return_value * (1 - $info["discount"] / 100);
            }
            if ($info["vat"] == 1) {
                $return_value = $return_value * $info["vat_value"];
            }
            $info_changes = array();
            $info_changes["invoice_id"] = $info["invoice_id"];
            $info_changes["return_value"] = $return_value * $info["qty"];
            $info_changes["added_value"] = 0;
            $info_changes["cashbox_id"] = $_SESSION["cashbox_id"];
            $info_changes["old_cashbox_id"] = $invoice_details[0]["cashbox_id"];
            $info_changes["cash_usd_r"] = $return_usd;
            $info_changes["cash_lbp_r"] = $return_lbp;
            $info_changes["only_return"] = 0;
            $info_changes["invoice_item_id"] = 0;
            $info_changes["invoice_item_return_id"] = 0;
            $cashbox->add_change($info_changes);
            if (!is_null($puchased_item[0]["item_id"])) {
                $store_info = array();
                $store_info["store_id"] = $_SESSION["store_id"];
                $store_info["user_id"] = $_SESSION["id"];
                if (0 < count($items_info)) {
                    if ($items_info[0]["is_composite"] == 1) {
                        $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                        $store_info["qty"] = $all_composite_of_item[0]["qty"] * $info["qty"];
                        $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $info["item_id"];
                    }
                } else {
                    $store_info["qty"] = $info["qty"];
                    $store_info["item_id"] = $info["item_id"];
                }
                $store_info["source"] = "pos";
                $store->add_qty($store_info);
            }
            if (is_null($puchased_item[0]["item_id"]) && 0 < $puchased_item[0]["mobile_transfer_credits"]) {
                $mobileStore = $this->model("mobileStore");
                $return_pkg_info = $mobileStore->getPackage($puchased_item[0]["mobile_transfer_credits"]);
                if ($return_pkg_info[0]["days"] == 0) {
                    $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                    $retur_cr = array();
                    $retur_cr["id"] = $transInfo[0]["device_id"];
                    if ($return_sms_fees == 1) {
                        $retur_cr["balance"] = $transInfo[0]["qty"] + (double) $transInfo[0]["sms_fees"];
                    } else {
                        $retur_cr["balance"] = $transInfo[0]["qty"];
                    }
                    if ($extra_sms_fees == 1) {
                        $retur_cr["extra_sms_fees"] = $this->settings_info["additional_credit_transfer_sms_cost"];
                        $mobileStore->updateCreditsFees($retur_cr);
                    }
                    $mobileStore->updateCredits($retur_cr);
                    if (0 < (double) $transInfo[0]["sms_fees"]) {
                        $mobileStore->set_fees_as_returned($transInfo[0]["id"], $extra_sms_fees, $retur_cr["extra_sms_fees"], $return_sms_fees, (double) $transInfo[0]["sms_fees"]);
                    }
                }
                if (0 < $return_pkg_info[0]["days"]) {
                    $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                    $retur_cr = array();
                    $retur_cr["id"] = $transInfo[0]["device_id"];
                    $retur_cr["balance"] = $transInfo[0]["qty"];
                    $mobileStore->updateCredits($retur_cr);
                    if (0 < (double) $transInfo[0]["sms_fees"]) {
                        $mobileStore->set_fees_as_returned($transInfo[0]["id"], 0, 0, 0, 0);
                    }
                    $store_info = array();
                    $store_info["store_id"] = $_SESSION["store_id"];
                    $store_info["user_id"] = $_SESSION["id"];
                    $store_info["qty"] = 1;
                    $items_info_transfer = $items->get_item($return_pkg_info[0]["item_related"]);
                    if ($items_info_transfer[0]["is_composite"] == 1) {
                        $all_composite_of_item = $items->get_all_composite_of_item($items_info_transfer[0]["id"]);
                        $store_info["qty"] = $all_composite_of_item[0]["qty"];
                        $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $return_pkg_info[0]["item_related"];
                    }
                    $store_info["source"] = "pos";
                    $store->add_qty($store_info);
                }
            }
            $payment_info = array();
            $payment_info["invoice_id"] = $info["invoice_id"];
            $payment_info["value"] = 0 - $info["final_price_disc_qty"];
            $payment_info["vendor_id"] = $_SESSION["id"];
            $payment_info["store_id"] = $_SESSION["store_id"];
            $payments->add_payment($payment_info);
            $return_info = array();
            $return_info["remain"] = $remain;
            $return_info["total_price"] = $remain * $puchased_item[0]["selling_price"];
            $return_info["returned_id"] = $returned_id;
            if (0 < $invoice_details[0]["customer_id"]) {
                $customers_class = $this->model("customers");
                $customers_class->bal_need_update($invoice_details[0]["customer_id"]);
            }
            echo json_encode($return_info);
        }
    }
    public function returnBackItems_new($id_, $qty_, $return_sms_fees, $_extra_sms_fees, $on_customer_acc_id, $cash_usd, $cash_lbp, $returned_cash_usd, $returned_cash_lbp, $r_cash_usd, $r_cash_lbp)
    {
        self::giveAccessTo(array(2, 4));
        if ($_SESSION["demo"] == 1 || !isset($_SESSION["cashbox_id"])) {
            $return_info = array();
            $return_info["remain"] = 0;
            $return_info["total_price"] = 0;
            $return_info["returned_id"] = 0;
            echo json_encode($return_info);
        } else {
            $invoice = $this->model("invoice");
            $items = $this->model("items");
            $payments = $this->model("payments");
            $cashbox = $this->model("cashbox");
            $store = $this->model("store");
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $qty = filter_var($qty_, FILTER_SANITIZE_NUMBER_INT);
            $extra_sms_fees = filter_var($_extra_sms_fees, FILTER_SANITIZE_NUMBER_INT);
            $puchased_item = $invoice->get_item_from_invoice($id);
            $invoice_details = $invoice->getInvoiceById($puchased_item[0]["invoice_id"]);
            $items_info = array();
            if ($puchased_item[0]["item_id"] != NULL) {
                $items_info = $items->get_item($puchased_item[0]["item_id"]);
            }
            $info = array();
            $info["id"] = $id;
            if ($puchased_item[0]["item_id"] != NULL) {
                $info["item_id"] = $puchased_item[0]["item_id"];
            } else {
                $info["item_id"] = "null";
            }
            $info["mobile_transfer_credits"] = "NULL";
            $info["description"] = $puchased_item[0]["description"];
            $info["invoice_id"] = $puchased_item[0]["invoice_id"];
            $info["custom_item"] = $puchased_item[0]["custom_item"];
            $remain = 0;
            if ($puchased_item[0]["qty"] <= $qty) {
                $info["qty"] = $puchased_item[0]["qty"];
                $remain = 0;
            } else {
                $info["qty"] = $qty;
                $remain = $puchased_item[0]["qty"] - $qty;
            }
            $info["buying_cost"] = $puchased_item[0]["buying_cost"] * $info["qty"];
            $info["vat"] = $puchased_item[0]["vat"];
            $info["vat_value"] = $puchased_item[0]["vat_value"];
            $info["selling_price"] = $puchased_item[0]["selling_price"] * $info["qty"];
            $info["discount"] = $puchased_item[0]["discount"];
            $info["final_price_disc_qty"] = $puchased_item[0]["final_price_disc_qty"];
            $info["returned_by_vendor_id"] = $_SESSION["id"];
            $info["returned_to_store_id"] = $_SESSION["store_id"];
            $cashb = $cashbox->getTodayCashbox($_SESSION["store_id"], $_SESSION["id"]);
            $info["cashbox_id"] = $cashb[0]["id"];
            if ($puchased_item[0]["item_change_cashbox"] == 0) {
                $info["old_cashbox_id"] = $invoice_details[0]["cashbox_id"];
            } else {
                $info["old_cashbox_id"] = $puchased_item[0]["item_change_cashbox"];
            }
            $returned_id = $invoice->returnPurchasedItem($info);
            if (0 < $on_customer_acc_id) {
                $invoice->assign_to_acc($returned_id, $on_customer_acc_id);
            }
            $invoice->reduceQtyOfPurchasedItem($info["id"], $info["invoice_id"], $info["qty"]);
            $invoice->calculate_total_profit_for_invoice($info["invoice_id"]);
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
            if ($puchased_item[0]["international_calls"] == 1) {
                $query___ = "update settings set value=value+" . $puchased_item[0]["base_usd_price"] . " where name='international_calls_balance'";
                my_sql::query($query___);
            }
            $return_value = $info["selling_price"];
            if (0 < $info["discount"]) {
                $return_value = $return_value * (1 - $info["discount"] / 100);
            }
            if ($info["vat"] == 1) {
                $return_value = $return_value * $info["vat_value"];
            }
            $info_changes = array();
            $info_changes["invoice_id"] = $info["invoice_id"];
            $info_changes["return_value"] = $return_value * $info["qty"];
            $info_changes["added_value"] = 0;
            $info_changes["cashbox_id"] = $_SESSION["cashbox_id"];
            $info_changes["old_cashbox_id"] = $invoice_details[0]["cashbox_id"];
            $info_changes["cash_usd_to_return"] = $r_cash_usd;
            $info_changes["cash_lbp_to_return"] = $r_cash_lbp;
            $info_changes["returned_cash_lbp"] = $returned_cash_lbp;
            $info_changes["returned_cash_usd"] = $returned_cash_usd;
            $info_changes["cash_lbp_in"] = $cash_lbp;
            $info_changes["cash_usd_in"] = $cash_usd;
            $info_changes["rate"] = $this->settings_info["usdlbp_rate"];
            $info_changes["only_return"] = 0;
            $info_changes["invoice_item_id"] = $info["id"];
            $info_changes["invoice_item_return_id"] = $returned_id;
            $cashbox->add_change($info_changes);
            if (!is_null($puchased_item[0]["item_id"])) {
                $store_info = array();
                $store_info["store_id"] = $_SESSION["store_id"];
                $store_info["user_id"] = $_SESSION["id"];
                if (0 < count($items_info)) {
                    if ($items_info[0]["is_composite"] == 1) {
                        $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                        if ($all_composite_of_item[0]["is_pack"] == 0) {
                            $store_info["qty"] = $all_composite_of_item[0]["qty"] * $info["qty"];
                            $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                            $store_info["source"] = "pos";
                            $store->add_qty($store_info);
                        } else {
                            $store_info["qty"] = $info["qty"];
                            $store_info["item_id"] = $all_composite_of_item[0]["composite_item_id"];
                            $store_info["source"] = "pos";
                            $store->add_pack_qty($store_info);
                        }
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $info["item_id"];
                        $store_info["source"] = "pos";
                        $store->add_qty($store_info);
                    }
                } else {
                    $store_info["qty"] = $info["qty"];
                    $store_info["item_id"] = $info["item_id"];
                    $store_info["source"] = "pos";
                    $store->add_qty($store_info);
                }
            }
            if (is_null($puchased_item[0]["item_id"]) && 0 < $puchased_item[0]["mobile_transfer_credits"]) {
                $mobileStore = $this->model("mobileStore");
                $return_pkg_info = $mobileStore->getPackage($puchased_item[0]["mobile_transfer_credits"]);
                if ($return_pkg_info[0]["days"] == 0) {
                    $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                    $retur_cr = array();
                    $retur_cr["id"] = $transInfo[0]["device_id"];
                    if ($return_sms_fees == 1) {
                        $retur_cr["balance"] = $transInfo[0]["qty"] + (double) $transInfo[0]["sms_fees"];
                    } else {
                        $retur_cr["balance"] = $transInfo[0]["qty"];
                    }
                    if ($extra_sms_fees == 1) {
                        $retur_cr["extra_sms_fees"] = $this->settings_info["additional_credit_transfer_sms_cost"];
                        $mobileStore->updateCreditsFees($retur_cr);
                    }
                    $mobileStore->updateCredits($retur_cr);
                    if (0 < (double) $transInfo[0]["sms_fees"]) {
                        $mobileStore->set_fees_as_returned($transInfo[0]["id"], $extra_sms_fees, $retur_cr["extra_sms_fees"], $return_sms_fees, (double) $transInfo[0]["sms_fees"]);
                    }
                }
                if (0 < $return_pkg_info[0]["days"]) {
                    $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                    $retur_cr = array();
                    $retur_cr["id"] = $transInfo[0]["device_id"];
                    $retur_cr["balance"] = $transInfo[0]["qty"];
                    $mobileStore->updateCredits($retur_cr);
                    if (0 < (double) $transInfo[0]["sms_fees"]) {
                        $mobileStore->set_fees_as_returned($transInfo[0]["id"], 0, 0, 0, 0);
                    }
                    $store_info = array();
                    $store_info["store_id"] = $_SESSION["store_id"];
                    $store_info["user_id"] = $_SESSION["id"];
                    $store_info["qty"] = 1;
                    $items_info_transfer = $items->get_item($return_pkg_info[0]["item_related"]);
                    if ($items_info_transfer[0]["is_composite"] == 1) {
                        $all_composite_of_item = $items->get_all_composite_of_item($items_info_transfer[0]["id"]);
                        $store_info["qty"] = $all_composite_of_item[0]["qty"];
                        $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $return_pkg_info[0]["item_related"];
                    }
                    $store_info["source"] = "pos";
                    $store->add_qty($store_info);
                }
            }
            $payment_info = array();
            $payment_info["invoice_id"] = $info["invoice_id"];
            $payment_info["value"] = 0 - $info["final_price_disc_qty"];
            $payment_info["vendor_id"] = $_SESSION["id"];
            $payment_info["store_id"] = $_SESSION["store_id"];
            $payments->add_payment($payment_info);
            $return_info = array();
            $return_info["remain"] = $remain;
            $return_info["total_price"] = $remain * $puchased_item[0]["selling_price"];
            $return_info["returned_id"] = $returned_id;
            if (0 < $invoice_details[0]["customer_id"]) {
                $customers_class = $this->model("customers");
                $customers_class->bal_need_update($invoice_details[0]["customer_id"]);
            }
            if ($puchased_item[0]["item_id"] !== NULL) {
                $uniqueItems = $this->model("uniqueItems");
                $return_info["unique_items"] = $uniqueItems->clean_unique_item($invoice_details[0]["id"], $puchased_item[0]["item_id"]);
            }
            echo json_encode($return_info);
        }
    }
    public function returnAllPurchasedItem($id_)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $store = $this->model("store");
        $mobileStore = $this->model("mobileStore");
        $puchased_item = $invoice->get_item_from_invoice($id);
        $items_info = array();
        $invoice_info = $invoice->getInvoiceById($puchased_item[0]["invoice_id"]);
        $info = array();
        $info["id"] = $id;
        if (is_null($puchased_item[0]["item_id"])) {
            $info["item_id"] = "NULL";
        } else {
            $info["item_id"] = $puchased_item[0]["item_id"];
            $items_info = $items->get_item($puchased_item[0]["item_id"]);
        }
        if (is_null($puchased_item[0]["mobile_transfer_credits"])) {
            $info["mobile_transfer_credits"] = "NULL";
        } else {
            $info["mobile_transfer_credits"] = $puchased_item[0]["mobile_transfer_credits"];
        }
        $info["description"] = $puchased_item[0]["description"];
        $info["invoice_id"] = $puchased_item[0]["invoice_id"];
        $info["custom_item"] = $puchased_item[0]["custom_item"];
        $info["qty"] = $puchased_item[0]["qty"];
        $info["buying_cost"] = $puchased_item[0]["buying_cost"];
        $info["vat"] = $puchased_item[0]["vat"];
        $info["selling_price"] = $puchased_item[0]["selling_price"];
        $info["discount"] = $puchased_item[0]["discount"];
        $info["final_price_disc_qty"] = $puchased_item[0]["final_price_disc_qty"];
        $info["returned_by_vendor_id"] = $_SESSION["id"];
        $info["returned_to_store_id"] = $_SESSION["store_id"];
        $cashb = $cashbox->getTodayCashbox($_SESSION["store_id"], $_SESSION["id"]);
        $info["cashbox_id"] = $cashb[0]["id"];
        $info["old_cashbox_id"] = $invoice_info[0]["cashbox_id"];
        if ($invoice_info[0]["closed"] == 1) {
            if ($puchased_item[0]["qty"] == 1) {
                $invoice->returnPurchasedItem($info);
                $invoice->deletePurchasedItem($info["id"], $info["invoice_id"]);
                if (!is_null($puchased_item[0]["item_id"])) {
                    $store_info = array();
                    $store_info["store_id"] = $_SESSION["store_id"];
                    $store_info["user_id"] = $_SESSION["id"];
                    if (0 < count($items_info)) {
                        if ($items_info[0]["is_composite"] == 1) {
                            $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                            $store_info["qty"] = $all_composite_of_item[0]["qty"];
                            $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                        } else {
                            $store_info["qty"] = $info["qty"];
                            $store_info["item_id"] = $info["item_id"];
                        }
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $info["item_id"];
                    }
                    $store_info["source"] = "pos";
                    $store->add_qty($store_info);
                }
                if (is_null($puchased_item[0]["item_id"]) && 0 < $puchased_item[0]["mobile_transfer_credits"]) {
                    $return_pkg_info = $mobileStore->getPackage($puchased_item[0]["mobile_transfer_credits"]);
                    if ($return_pkg_info[0]["days"] == 0) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"] + (double) $transInfo[0]["sms_fees"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                    if (0 < $return_pkg_info[0]["days"]) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                }
                if ($invoice_info[0]["auto_closed"] == 1) {
                    $balance_info["customer_id"] = $invoice_info[0]["customer_id"];
                    $balance_info["vendor_id"] = $_SESSION["id"];
                    $balance_info["store_id"] = $_SESSION["store_id"];
                    $balance_info["value"] = 0 - $info["final_price_disc_qty"];
                    $payments->add_balance($balance_info);
                }
                $payment_info = array();
                $payment_info["invoice_id"] = $info["invoice_id"];
                $payment_info["value"] = 0 - $info["final_price_disc_qty"];
                $payment_info["vendor_id"] = $_SESSION["id"];
                $payment_info["store_id"] = $_SESSION["store_id"];
                $payments->add_payment($payment_info);
                $info_to_return["done"] = 1;
            } else {
                $info["qty"] = 1;
                $invoice->returnPurchasedItem($info);
                $invoice->deleteOnePurchasedItem($info["id"], $info["invoice_id"]);
                if (!is_null($puchased_item[0]["item_id"])) {
                    $store_info = array();
                    $store_info["store_id"] = $_SESSION["store_id"];
                    $store_info["user_id"] = $_SESSION["id"];
                    if (0 < count($items_info)) {
                        if ($items_info[0]["is_composite"] == 1) {
                            $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                            $store_info["qty"] = $all_composite_of_item[0]["qty"];
                            $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                        } else {
                            $store_info["qty"] = $info["qty"];
                            $store_info["item_id"] = $info["item_id"];
                        }
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $info["item_id"];
                    }
                    $store_info["source"] = "pos";
                    $store->add_qty($store_info);
                }
                if (is_null($puchased_item[0]["item_id"]) && 0 < $puchased_item[0]["mobile_transfer_credits"]) {
                    $return_pkg_info = $mobileStore->getPackage($puchased_item[0]["mobile_transfer_credits"]);
                    if ($return_pkg_info[0]["days"] == 0) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"] + (double) $transInfo[0]["sms_fees"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                    if (0 < $return_pkg_info[0]["days"]) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                }
                $payment_info = array();
                $payment_info["invoice_id"] = $info["invoice_id"];
                $payment_info["value"] = 0 - $info["selling_price"] * (1 - $info["discount"] / 100);
                $payment_info["vendor_id"] = $_SESSION["id"];
                $payment_info["store_id"] = $_SESSION["store_id"];
                $payments->add_payment($payment_info);
                $info_to_return["done"] = 1;
            }
        } else {
            if ($puchased_item[0]["qty"] == 1) {
                $invoice->returnPurchasedItem($info);
                $invoice->deletePurchasedItem($info["id"], $info["invoice_id"]);
                if (!is_null($puchased_item[0]["item_id"])) {
                    $store_info = array();
                    $store_info["store_id"] = $_SESSION["store_id"];
                    $store_info["user_id"] = $_SESSION["id"];
                    if (0 < count($items_info)) {
                        if ($items_info[0]["is_composite"] == 1) {
                            $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                            $store_info["qty"] = $all_composite_of_item[0]["qty"];
                            $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                        } else {
                            $store_info["qty"] = $info["qty"];
                            $store_info["item_id"] = $info["item_id"];
                        }
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $info["item_id"];
                    }
                    $store_info["source"] = "pos";
                    $store->add_qty($store_info);
                }
                if (is_null($puchased_item[0]["item_id"]) && 0 < $puchased_item[0]["mobile_transfer_credits"]) {
                    $return_pkg_info = $mobileStore->getPackage($puchased_item[0]["mobile_transfer_credits"]);
                    if ($return_pkg_info[0]["days"] == 0) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"] + (double) $transInfo[0]["sms_fees"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                    if (0 < $return_pkg_info[0]["days"]) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                }
                $info_to_return["done"] = 1;
            } else {
                $info["qty"] = 1;
                $invoice->returnPurchasedItem($info);
                $invoice->deleteOnePurchasedItem($info["id"], $info["invoice_id"]);
                if (is_null($puchased_item[0]["item_id"]) && 0 < $puchased_item[0]["mobile_transfer_credits"]) {
                    $return_pkg_info = $mobileStore->getPackage($puchased_item[0]["mobile_transfer_credits"]);
                    if ($return_pkg_info[0]["days"] == 0) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"] + (double) $transInfo[0]["sms_fees"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                    if (0 < $return_pkg_info[0]["days"]) {
                        $transInfo = $mobileStore->getCreditHistoryByItemId($puchased_item[0]["id"]);
                        $retur_cr = array();
                        $retur_cr["id"] = $transInfo[0]["device_id"];
                        $retur_cr["balance"] = $transInfo[0]["qty"];
                        $mobileStore->updateCredits($retur_cr);
                    }
                }
                if (!is_null($puchased_item[0]["item_id"])) {
                    $store_info = array();
                    $store_info["store_id"] = $_SESSION["store_id"];
                    $store_info["user_id"] = $_SESSION["id"];
                    if (0 < count($items_info)) {
                        if ($items_info[0]["is_composite"] == 1) {
                            $all_composite_of_item = $items->get_all_composite_of_item($items_info[0]["id"]);
                            $store_info["qty"] = $all_composite_of_item[0]["qty"];
                            $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                        } else {
                            $store_info["qty"] = $info["qty"];
                            $store_info["item_id"] = $info["item_id"];
                        }
                    } else {
                        $store_info["qty"] = $info["qty"];
                        $store_info["item_id"] = $info["item_id"];
                    }
                    $store_info["source"] = "pos";
                    $store->add_qty($store_info);
                }
                $info_to_return["done"] = 1;
            }
        }
        $invoice->calculate_total_profit_for_invoice($puchased_item[0]["invoice_id"]);
        $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        $info_to_return["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info);
        echo json_encode($info_to_return);
    }
    public function setCashbox($value_, $_value_lbp)
    {
        self::giveAccessTo(array(2, 4));
        if (!isset($value_) || $value_ == "") {
            $value_ = 0;
        }
        if (!isset($_value_lbp) || $_value_lbp == "") {
            $_value_lbp = 0;
        }
        $value = filter_var($value_, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $value_lbp = filter_var($_value_lbp, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $cashbox = $this->model("cashbox");
        $cashbox_id = $cashbox->setCashbox($_SESSION["store_id"], $_SESSION["id"], $value, $value_lbp);
        if (0 < $cashbox_id) {
            $_SESSION["cashbox_id"] = $cashbox_id;
            if ($this->settings_info["telegram_enable"] == 1) {
                $users = $this->model("user");
                $employees_info = $users->getAllUsersEvenDeleted();
                $employees_info_array = array();
                for ($i = 0; $i < count($employees_info); $i++) {
                    $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                }
                $store = $this->model("store");
                $store_info = $store->getStoresById($_SESSION["store_id"]);
                $info = array();
                $info["message"] = "<strong>Cashbox is opened by:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                $info["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                $info["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                if (0 < $value_lbp) {
                    $info["message"] .= "<strong>LBP:</strong> " . number_format($value_lbp, 0) . " \n";
                }
                if (0 < $value) {
                    $info["message"] .= "<strong>USD:</strong> " . number_format($value, 0) . " \n";
                }
                self::send_to_telegram($info, 1);
            }
            echo json_encode(array(1));
        } else {
            echo json_encode(array(0));
        }
    }
    public function getTodayCashbox()
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $info = $cashbox->getTodayCashbox($_SESSION["store_id"], $_SESSION["id"]);
        if (0 < count($info)) {
            return $info;
        }
        return 0;
    }
    public function getStoreId()
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $info = array();
        $info["store_id"] = $_SESSION["store_id"];
        $todayCashbox = self::getTodayCashbox();
        if ($todayCashbox == 0) {
            $info["cashbox"] = $todayCashbox;
        } else {
            $info["cashbox"] = 1;
            $_SESSION["cashbox_id"] = $todayCashbox[0]["id"];
        }
        $info["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info);
        echo json_encode($info);
    }
    public function getPayments($id_, $invoice_id_)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $payments = $this->model("payments");
        $info = $payments->getPaymentOfCustomer($id, $invoice_id);
        echo json_encode($info);
    }
    public function getCustomersPaymentInfo($customer_id_)
    {
        self::giveAccessTo(array(2, 4));
        $customer_id = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $payments = $this->model("payments");
        $creditnote = $this->model("creditnote");
        $customer_info = $customers->getCustomersById($customer_id);
        $invoies_info = $invoice->getTotalUnpaid($customer_id);
        $total_payments = $payments->getTotalPaymentForCustomer($customer_id);
        $creditnote_info = $creditnote->total_credit_notes($customer_id);
        $info = array();
        $info["customer_balance"] = $total_payments[0]["sum"];
        $info["total_unPaid"] = $invoies_info[0]["sum"];
        $info["total_remain"] = $info["total_unPaid"] - $info["customer_balance"];
        $info["total_remain"] += $customer_info[0]["starting_balance"];
        $info["total_remain"] -= $creditnote_info[0]["sum"];
        $info["total_remain"] = self::value_format_custom($info["total_remain"], $this->settings_info);
        echo json_encode($info);
    }
    public function get_all_international_calls($date)
    {
        self::giveAccessTo(array(2, 4));
        $data_array["data"] = array();
        $invoice = $this->model("invoice");
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
        $info = $invoice->get_all_international_calls($date_range);
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["description"]);
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getSettingsForPos()
    {
        $settings = self::getSettings();
        $get_settings_local = self::get_settings_local();
        $employees = $this->model("employees");
        $currency = $this->model("currency");
        $store = $this->model("store");
        $info = array();
        $info["payment_full"] = $settings["payment_full"];
        $info["payment_later"] = $settings["payment_later"];
        $info["payment_credit_card"] = $settings["payment_credit_card"];
        $info["default_currency_symbol"] = $settings["default_currency_symbol"];
        $info["auto_print"] = $settings["auto_print"];
        $info["payment_later"] = $settings["payment_later"];
        $info["enable_wholasale"] = $settings["enable_wholasale"];
        $info["enable_customer_display"] = $settings["enable_customer_display"];
        $info["ask_print_for_gift"] = $settings["ask_print_for_gift"];
        $info["a4_printer"] = $settings["a4_printer"];
        $info["round_val"] = $settings["round_val"];
        $info["pos_normal_round"] = $settings["pos_normal_round"];
        $info["default_print_paper"] = $settings["default_print_paper"];
        $info["additional_credit_transfer_sms_cost"] = $settings["additional_credit_transfer_sms_cost"];
        $info["auto_sync"] = $settings["auto_sync"];
        $info["international_calls_source_rate"] = $settings["international_calls_source_rate"];
        $info["enable_sales_person"] = $settings["enable_sales_person"];
        $info["enable_omt"] = $settings["enable_omt"];
        $info["enable_omt_url"] = $settings["enable_omt_url"];
        $info["enable_customers_cashback"] = $settings["enable_customers_referrer"];
        $info["pos_manual_print"] = $settings["pos_manual_print"];
        $info["enable_qz_print"] = $settings["enable_qz_print"];
        $info["pos_hide_cash_payment_if_customer_is_selected"] = $settings["pos_hide_cash_payment_if_customer_is_selected"];
        $info["phone_number_format"] = $settings["phone_number_format"];
        $info["identity_number_format"] = $settings["identity_number_format"];
        $info["enable_advanced_customer_info"] = $settings["enable_advanced_customer_info"];
        $info["enable_edit_invoice_password"] = $settings["enable_edit_invoice_password"];
        $info["edit_invoice_password"] = $settings["edit_invoice_password"];
        $info["garage_car_plugin"] = $settings["garage_car_plugin"];
        $info["enable_delete_customer_on_pos"] = $settings["enable_delete_customer_on_pos"];
        $info["sound_play"] = $settings["sound_play"];
        $info["international_call_rate"] = $settings["international_call_rate"];
        $info["payment_cheque"] = $settings["payment_cheque"];
        $info["pos_all_items_hide_on_add_to_invoice"] = $settings["pos_all_items_hide_on_add_to_invoice"];
        $info["vat"] = $settings["vat"];
        $info["advanced_customer_info_img_width"] = $settings["advanced_customer_info_img_width"];
        $info["enable_discount_password"] = $settings["enable_discount_password"];
        $info["discount_password"] = $settings["discount_password"];
        $info["enable_only_return_password"] = $settings["enable_only_return_password"];
        $info["only_return_password"] = $settings["only_return_password"];
        $info["disable_international_calls"] = $settings["disable_international_calls"];
        $info["pos_sales_person_boxes"] = $settings["pos_sales_person_boxes"];
        $info["enable_change_invoice_date"] = $settings["enable_change_invoice_date"];
        $info["enable_invoice_discount"] = $settings["enable_invoice_discount"];
        $info["enable_invoice_tax"] = $settings["enable_invoice_tax"];
        $info["enable_invoice_freight"] = $settings["enable_invoice_freight"];
        $info["enable_edit_invoice_even_new_item_is_added"] = $settings["enable_edit_invoice_even_new_item_is_added"];
        $info["set_password_for_cashbox_and_report_pos"] = $settings["set_password_for_cashbox_and_report_pos"];
        $info["salesperson"] = $employees->getAllEmployees();
        $info["force_select_sales_persion_on_pos"] = $settings["force_select_sales_persion_on_pos"];
        $all_currencies = $currency->getAllEnabledCurrencies();
        $info["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $info["currencies"][$i]["id"] = $all_currencies[$i]["id"];
            $info["currencies"][$i]["name"] = $all_currencies[$i]["name"];
            $info["currencies"][$i]["symbole"] = $all_currencies[$i]["symbole"];
            $info["currencies"][$i]["system_default"] = $all_currencies[$i]["system_default"];
            $info["currencies"][$i]["rate_to_system_default"] = $all_currencies[$i]["rate_to_system_default"];
        }
        $info["stores"] = $store->getStoresNotGlobal();
        echo json_encode($info);
    }
    public function getNonBarcodeItems($_store_id_)
    {
        self::giveAccessTo(array(2, 4));
        $store_id = filter_var($_store_id_, FILTER_SANITIZE_NUMBER_INT);
        $pos = $this->model("pos");
        $data = $pos->getNonBarcodeItems($store_id);
        $measure = $this->model("measures");
        $measures = $measure->getMeasures();
        $measures_info = array();
        for ($i = 0; $i < count($measures); $i++) {
            $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
        }
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]["selling_price"] = self::value_format_custom($data[$i]["selling_price"] - $data[$i]["selling_price"] * $data[$i]["discount"] / 100, $this->settings_info);
            $measure_symb = "";
            if ($data[$i]["unit_measure_id"] != NULL) {
                $measure_symb = $measures_info[$data[$i]["unit_measure_id"]];
            }
            $data[$i]["quantity"] = (double) $data[$i]["quantity"] . " " . $measure_symb;
        }
        echo json_encode($data);
    }
    public function getAllItemsBarcoded($_store_id_)
    {
        self::giveAccessTo(array(2, 4));
        $store_id = filter_var($_store_id_, FILTER_SANITIZE_NUMBER_INT);
        $pos = $this->model("pos");
        $data = $pos->getAllItemsBarcoded($store_id, $this->settings_info);
        $measure = $this->model("measures");
        $measures = $measure->getMeasures();
        $measures_info = array();
        for ($i = 0; $i < count($measures); $i++) {
            $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
        }
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]["selling_price"] = self::value_format_custom($data[$i]["selling_price"] - $data[$i]["selling_price"] * $data[$i]["discount"] / 100, $this->settings_info);
            $measure_symb = "";
            if ($data[$i]["unit_measure_id"] != NULL) {
                $measure_symb = $measures_info[$data[$i]["unit_measure_id"]];
            }
            $data[$i]["quantity"] = (double) $data[$i]["quantity"];
            if (27 < strlen($data[$i]["description"])) {
                $data[$i]["description"] = substr($data[$i]["description"], 0, 27) . " ...";
            }
        }
        echo json_encode($data);
    }
    public function get_custom_items($_store_id_)
    {
        self::giveAccessTo(array(2, 4));
        $store_id = filter_var($_store_id_, FILTER_SANITIZE_NUMBER_INT);
        $pos = $this->model("pos");
        $data = $pos->get_custom_items($store_id, $this->settings_info);
        $data_to_return = array();
        $data_to_return["items"] = array();
        $data_to_return["subcat"] = array();
        $data_to_return_index = 0;
        $subcats = array();
        for ($i = 0; $i < count($data); $i++) {
            if (!in_array($data[$i]["item_category"], $subcats)) {
                array_push($subcats, $data[$i]["item_category"]);
            }
        }
        if (0 < count($subcats) && $this->settings_info["pos_split_screen_show_subcategories"] == 1) {
            $data_to_return["subcat"] = $pos->get_all_sub_categories_in_array($subcats);
        }
        for ($i = 0; $i < count($data); $i++) {
            $tmp_array = explode(",", $data[$i]["pos_col_users"]);
            if (in_array($_SESSION["id"], $tmp_array) || in_array(0, $tmp_array)) {
                $data_to_return["items"][$data_to_return_index] = $data[$i];
                $data_to_return["items"][$data_to_return_index]["selling_price"] = self::value_format_custom($data[$i]["selling_price"] - $data[$i]["selling_price"] * $data[$i]["discount"] / 100, $this->settings_info);
                $data_to_return_index++;
            }
        }
        echo json_encode($data_to_return);
    }
    public function add_item_to_interface($_item_id, $_store_id_)
    {
        self::giveAccessTo();
        $data = array();
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $store_id = filter_var($_store_id_, FILTER_SANITIZE_NUMBER_INT);
        $info = array();
        $info["store_id"] = $_SESSION["store_id"];
        $info["item_id"] = $item_id;
        $pos = $this->model("pos");
        $pos->add_item_to_interface($info);
        $data["id"] = $item_id;
        echo json_encode($data);
    }
    public function remove_item_to_interface($_item_id, $_store_id_)
    {
        self::giveAccessTo();
        $data = array();
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $store_id = filter_var($_store_id_, FILTER_SANITIZE_NUMBER_INT);
        $info = array();
        $info["store_id"] = $store_id;
        $info["item_id"] = $item_id;
        $pos = $this->model("pos");
        $pos->remove_item_to_interface($info);
        $data["id"] = $item_id;
        echo json_encode($data);
    }
    public function getPackageById($id_)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $data = $mobileStore->getPackage($id);
        $operator_label = array();
        $operators = $mobileStore->getOperators();
        for ($i = 0; $i < count($operators); $i++) {
            $operator_label[$operators[$i]["id"]]["id"] = $operators[$i]["name"];
        }
        $info = array();
        for ($i = 0; $i < count($data); $i++) {
            $info[$i]["id"] = $data[$i]["id"];
            $info[$i]["price"] = $data[$i]["price"];
            $info[$i]["qty"] = $data[$i]["qty"];
            $info[$i]["days"] = $data[$i]["days"];
            $info[$i]["operator_name"] = $operator_label[$data[$i]["operator_id"]]["id"];
            $info[$i]["description"] = $data[$i]["description"];
        }
        echo json_encode($info);
    }
    public function getTransferPackages($_store_id_)
    {
        self::giveAccessTo(array(2, 4));
        $_store_id = filter_var($_store_id_, FILTER_SANITIZE_NUMBER_INT);
        $mobileStore = $this->model("mobileStore");
        $operators = $mobileStore->getOperators();
        $packages = $mobileStore->getAllPackages();
        $devices = $mobileStore->getDevices($_store_id);
        $base_color = array();
        $base_name = array();
        $info["operators"] = array();
        for ($i = 0; $i < count($operators); $i++) {
            $info["operators"][$i]["id"] = $operators[$i]["id"];
            $base_color[$info["operators"][$i]["id"]] = $operators[$i]["base_color"];
            $base_name[$info["operators"][$i]["id"]] = $operators[$i]["name"];
        }
        $info["packages"] = array();
        for ($i = 0; $i < count($packages); $i++) {
            $info["packages"][$i]["id"] = $packages[$i]["id"];
            $info["packages"][$i]["qty"] = $packages[$i]["qty"];
            $info["packages"][$i]["operator_id"] = $packages[$i]["operator_id"];
            $info["packages"][$i]["price"] = self::value_format_custom($packages[$i]["price"], $this->settings_info);
            $info["packages"][$i]["base_color"] = $base_color[$packages[$i]["operator_id"]];
            $info["packages"][$i]["operator_name"] = $base_name[$packages[$i]["operator_id"]];
            $info["packages"][$i]["days"] = $packages[$i]["days"];
            $info["packages"][$i]["type"] = $packages[$i]["type"];
            $info["packages"][$i]["description"] = $packages[$i]["description"];
            $info["packages"][$i]["no_sms_fees"] = $packages[$i]["no_sms_fees"];
        }
        $info["devices"] = array();
        for ($i = 0; $i < count($devices); $i++) {
            $info["devices"][$i]["id"] = $devices[$i]["id"];
            $info["devices"][$i]["operator_id"] = $devices[$i]["operator_id"];
            $info["devices"][$i]["balance"] = number_format($devices[$i]["balance"], 2);
            $info["devices"][$i]["operator_name"] = $base_name[$devices[$i]["operator_id"]];
            $info["devices"][$i]["description"] = $devices[$i]["description"];
            $info["devices"][$i]["color"] = $base_color[$devices[$i]["operator_id"]];
        }
        echo json_encode($info);
    }
    public function add_item_qty()
    {
        self::giveAccessTo(array(2, 4));
        $store = $this->model("store");
        $info = array();
        $info["qty"] = filter_input(INPUT_POST, "item_add_qty", FILTER_SANITIZE_NUMBER_INT);
        if ($info["qty"] < 0) {
            $info["qty"] = 0;
        }
        $info["item_id"] = filter_input(INPUT_POST, "item_id", FILTER_SANITIZE_NUMBER_INT);
        $info["store_id"] = $_SESSION["store_id"];
        $info["user_id"] = $_SESSION["id"];
        $info["source"] = "pos";
        $store->add_qty($info);
        echo json_encode(array());
    }
    public function getItemInStore($store_id_)
    {
        self::giveAccessTo(array(2, 4));
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $items->sync_item_with_store($_SESSION["store_id"]);
        $items_in_store = $items->get_items_in_store($store_id, 0, 0);
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_in_store); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($items_in_store[$i]["item_id"]));
            array_push($tmp, self::idFormat_supplier($items_in_store[$i]["supplier_reference"]));
            array_push($tmp, $items_in_store[$i]["barcode"]);
            array_push($tmp, $items_in_store[$i]["description"]);
            if ($items_in_store[$i]["on_pos_interface"] == 0) {
                array_push($tmp, "NO");
            } else {
                array_push($tmp, "YES");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function posit_changed($_item_id, $_order)
    {
        self::giveAccessTo(array(2, 4));
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $order = filter_var($_order, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $items->posit_changed($item_id, $order);
        echo json_encode(array());
    }
    public function getItemInStore_for_pos_col($store_id_)
    {
        self::giveAccessTo(array(2, 4));
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $items->sync_item_with_store($_SESSION["store_id"]);
        $items_in_store = $items->get_items_in_store($store_id, 0, 0);
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_in_store); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($items_in_store[$i]["item_id"]));
            array_push($tmp, self::idFormat_supplier($items_in_store[$i]["supplier_reference"]));
            array_push($tmp, $items_in_store[$i]["barcode"]);
            array_push($tmp, $items_in_store[$i]["description"]);
            if ($items_in_store[$i]["on_pos_interface"] == 0) {
                array_push($tmp, "no");
            } else {
                array_push($tmp, "<b>YES</b>");
            }
            array_push($tmp, "");
            array_push($tmp, $items_in_store[$i]["pos_order"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getUnpaidInvoicesOfCustomers($_customer_id_)
    {
        self::giveAccessTo(array(2, 4));
        $_customer_id = filter_var($_customer_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $store = $this->model("store");
        $stores_label = array();
        $stores = $store->getStores();
        for ($i = 0; $i < count($stores); $i++) {
            $stores_label[$stores[$i]["id"]] = $stores[$i]["name"];
        }
        $invoices = $invoice->getUnpaidInvoicesOfCustomers($_customer_id);
        for ($i = 0; $i < count($invoices); $i++) {
            $amount = $invoice->getAmount($invoices[$i]["id"]);
            $totalPayments = $payments->getTotalPayments($invoices[$i]["id"]);
            $invoices[$i]["invoice_value"] = self::value_format_custom(floatval($amount[0]["sum"]), $this->settings_info);
            $invoices[$i]["total_paid"] = $totalPayments[0]["sum"] . " " . $this->settings_info["default_currency_symbol"];
            $invoices[$i]["store_name"] = $stores_label[$invoices[$i]["store_id"]];
        }
        echo json_encode($invoices);
    }
    public function getCustomersPayments($customer_id_)
    {
        self::giveAccessTo(array(2, 4));
        $customer_id = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
        $payments = $this->model("payments");
        $balance_info = $payments->getAllBalancePaymentOfCustomer($customer_id);
        $data_array["data"] = array();
        if ($customer_id != 0) {
            for ($i = 0; $i < count($balance_info); $i++) {
                $tmp = array();
                array_push($tmp, $balance_info[$i]["id"]);
                array_push($tmp, date_format(date_create($balance_info[$i]["balance_date"]), "l jS \\of F Y h:i:s A"));
                array_push($tmp, $balance_info[$i]["balance"] . " " . $this->settings_info["default_currency_symbol"]);
                array_push($data_array["data"], $tmp);
            }
        }
        echo json_encode($data_array);
    }
    public function get_packages($_p0, $_p1, $_p2, $_p3)
    {
        self::giveAccessTo(array(2, 4));
        $mobileStore = $this->model("mobileStore");
        $operators = $mobileStore->getOperators();
        $packages = $mobileStore->getAllPackages();
        $devices = $mobileStore->getDevices($_SESSION["store_id"]);
        $base_color = array();
        $base_name = array();
        $info["operators"] = array();
        for ($i = 0; $i < count($operators); $i++) {
            $info["operators"][$i]["id"] = $operators[$i]["id"];
            $base_color[$info["operators"][$i]["id"]] = $operators[$i]["base_color"];
            $base_name[$info["operators"][$i]["id"]] = $operators[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($packages); $i++) {
            $tmp = array();
            $cd = "";
            if (0 < $packages[$i]["days"]) {
                $cd = $packages[$i]["days"] . " Days and ";
            }
            array_push($tmp, $packages[$i]["id"]);
            array_push($tmp, $packages[$i]["operator_id"]);
            array_push($tmp, "<b>" . $packages[$i]["description"] . "</b>");
            array_push($tmp, "<b>" . $packages[$i]["days"] . "</b>");
            array_push($tmp, "<b>" . $packages[$i]["qty"] . "</b>");
            array_push($tmp, "<b>" . self::value_format_custom($packages[$i]["price"], $this->settings_info) . "</b>");
            array_push($tmp, "<b>" . $packages[$i]["alias"] . "</b>");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllClientsCards($type)
    {
        self::giveAccessTo(array(2));
        $garage = $this->model("garage");
        $customers = $this->model("customers");
        $colors = $this->model("colors");
        $customers_info = $customers->getCustomersEvenDeleted();
        $customers_array = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $customers_array[$customers_info[$i]["id"]] = $customers_info[$i];
        }
        $colors_array = array();
        $colors_info = $colors->getColorsText();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i];
        }
        if ($type == 0) {
            $info = $garage->getAllClientsCards();
        } else {
            $info = $garage->getAllClientsPendingsCards();
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_gcc($info[$i]["id"]));
            if ($info[$i]["client_id"] != 0) {
                array_push($tmp, $customers_array[$info[$i]["client_id"]]["name"] . " " . $customers_array[$info[$i]["client_id"]]["middle_name"] . " " . $customers_array[$info[$i]["client_id"]]["last_name"]);
                array_push($tmp, $customers_array[$info[$i]["client_id"]]["phone"]);
            } else {
                array_push($tmp, "");
                array_push($tmp, "");
            }
            array_push($tmp, $info[$i]["problem_description"]);
            array_push($tmp, $info[$i]["code"]);
            array_push($tmp, $info[$i]["company"]);
            array_push($tmp, $info[$i]["car_type"]);
            array_push($tmp, $info[$i]["model"]);
            array_push($tmp, $colors_array[$info[$i]["color"]]["name"]);
            array_push($tmp, $info[$i]["odometer"]);
            array_push($tmp, $info[$i]["car"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function logout()
    {
        session_start();
        session_destroy();
        header("location: ./");
    }
    public function get_all_items_new_AJAX($_by_barcode, $_category, $_subcategory)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $data_array["data"] = array();
        $predefined_columns = array();
        $predefined_columns[0] = "item_id";
        $predefined_columns[1] = "desc_sku";
        $predefined_columns[2] = "sku_code";
        $predefined_columns[3] = "barcode";
        $predefined_columns[4] = "selling_price";
        $predefined_columns[5] = "discount";
        $predefined_columns[6] = "item_vat";
        $predefined_columns[7] = "price_after_discount";
        $predefined_columns[8] = "qty";
        $predefined_columns[9] = "color_text_label";
        $predefined_columns[10] = "size_label";
        $filter = array();
        $filter["start"] = $_POST["start"];
        $filter["row_per_page"] = $_POST["length"];
        $filter["columns"] = $predefined_columns;
        $columnIndex = $_POST["order"][0]["column"];
        $filter["col_sort_index"] = $columnIndex;
        $filter["col_sort"] = $predefined_columns[$columnIndex];
        $filter["order_by"] = $_POST["order"][0]["dir"];
        if (isset($_POST["search"]["value"])) {
            $filter["search_filters"] = filter_var($_POST["search"]["value"], self::conversion_php_version_filter());
        } else {
            $filter["search_filters"] = "";
        }
        $filter["search_col_filters"] = array();
        if (isset($_POST["search_col_filters"])) {
            $filter["search_col_filters"] = $_POST["search_col_filters"];
        }
        $filter["enable_wholasale"] = $this->settings_info["enable_wholasale"];
        $filter["items_vat"] = $this->settings_info["vat"];
        $filter["store_id"] = $_SESSION["store_id"];
        $by_barcode = filter_var($_by_barcode, self::conversion_php_version_filter());
        $category = filter_var($_category, self::conversion_php_version_filter());
        $subcategory = filter_var($_subcategory, self::conversion_php_version_filter());
        if ($by_barcode == "0") {
            $tables_info = $items->getAllItemsPOS_AJAX($category, $subcategory, $filter, 0);
        } else {
            $tables_info = $items->getAllItemsByBarcode_AJAX($by_barcode, $filter, 0);
        }
        $data_array["data"] = array();
        $discounts = $this->model("discounts");
        $discounts_items = $discounts->get_all_items_under_discounts();
        $discounts_items_discount = array();
        for ($i = 0; $i < count($discounts_items); $i++) {
            $discounts_items_discount[$discounts_items[$i]["item_id"]] = $discounts_items[$i]["discount_value"];
        }
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            for ($k = 0; $k < count($filter["columns"]); $k++) {
                if ($filter["columns"][$k] == "item_id") {
                    array_push($tmp, self::idFormat_item($tables_info[$i]["item_id"]));
                } else {
                    if ($filter["columns"][$k] == "barcode") {
                        if (strlen($tables_info[$i]["barcode"]) < 5) {
                            array_push($tmp, sprintf("%05s", $tables_info[$i][$filter["columns"][$k]]));
                        } else {
                            array_push($tmp, $tables_info[$i][$filter["columns"][$k]]);
                        }
                    } else {
                        if ($filter["columns"][$k] == "discount") {
                            if (0 < $tables_info[$i]["discount"]) {
                                array_push($tmp, "<span class='discount'>" . number_format($tables_info[$i]["discount"], 2) . " %</span>");
                            } else {
                                array_push($tmp, "0 %");
                            }
                        } else {
                            if ($filter["columns"][$k] == "vat") {
                                array_push($tmp, "<span class='vat'>" . $tables_info[$i]["vat"] . " %</span>");
                            } else {
                                if ($filter["columns"][$k] == "price_after_discount") {
                                    array_push($tmp, self::value_format_custom($tables_info[$i][$filter["columns"][$k]], $this->settings_info));
                                } else {
                                    if ($filter["columns"][$k] == "qty") {
                                        array_push($tmp, (double) $tables_info[$i][$filter["columns"][$k]]);
                                    } else {
                                        array_push($tmp, $tables_info[$i][$filter["columns"][$k]]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        if ($by_barcode == "0") {
            $tables_info_cnt = $items->getAllItemsPOS_AJAX($category, $subcategory, $filter, 1);
        } else {
            $tables_info_cnt = $items->getAllItemsByBarcode_AJAX($by_barcode, $filter, 1);
        }
        $draw = $_POST["draw"];
        $response = array("draw" => $draw, "recordsTotal" => count($tables_info_cnt), "recordsFiltered" => count($tables_info_cnt), "data" => $data_array["data"]);
        echo json_encode($response);
    }
    public function submit_stock_transfer()
    {
        $transfer_class = $this->model("transfer");
        $info = array();
        $info["by"] = $_SESSION["id"];
        $info["to_store_id"] = filter_input(INPUT_POST, "to_branch", FILTER_SANITIZE_NUMBER_INT);
        $info["from_store_id"] = $_SESSION["store_id"];
        $info["qty"] = filter_input(INPUT_POST, "trs_qty", FILTER_SANITIZE_NUMBER_INT);
        $info["trs_item_id"] = filter_input(INPUT_POST, "trs_item_id", FILTER_SANITIZE_NUMBER_INT);
        $id = $transfer_class->pos_stock_transfer($info);
        if (0 < $id) {
            $updated_rows = $transfer_class->excute_stock_transfer($id);
            if ($updated_rows) {
            }
        }
        echo json_encode(array());
    }
}
?>