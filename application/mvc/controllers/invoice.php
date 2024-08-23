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
class invoice extends Controller
{
    public $settings_info = NULL;
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function add_item_prepare_invoice_by_barcode($_barcode, $_preinvoice_id)
    {
        $invoices_model = $this->model("invoice");
        $customers_model = $this->model("customers");
        $barcode = filter_var($_barcode, self::conversion_php_version_filter());
        $preinvoice_id = filter_var($_preinvoice_id, FILTER_SANITIZE_NUMBER_INT);
        $customer_type = 1;
        $pre_invoice_details = $invoices_model->get_pre_invoice_by_id($preinvoice_id);
        if (0 < $pre_invoice_details[0]["client_id"]) {
            $customer_info = $customers_model->getCustomersById($pre_invoice_details[0]["client_id"]);
            $customer_type = $customer_info[0]["customer_type"];
        }
        $result = $invoices_model->add_item_prepare_invoice_by_barcode($barcode, $preinvoice_id, $customer_type);
        echo json_encode($result);
    }
    public function change_client_preinvoice($_client_id, $_preinvoice_id)
    {
        $invoices_model = $this->model("invoice");
        $client_id = filter_var($_client_id, FILTER_SANITIZE_NUMBER_INT);
        $preinvoice_id = filter_var($_preinvoice_id, FILTER_SANITIZE_NUMBER_INT);
        $invoices_model->change_client_preinvoice($client_id, $preinvoice_id);
        echo json_encode(array());
    }
    public function prepare_new_invoice($_client_id)
    {
        $invoices_model = $this->model("invoice");
        $client_id = filter_var($_client_id, FILTER_SANITIZE_NUMBER_INT);
        $id = $invoices_model->prepare_new_invoice($client_id);
        echo json_encode(array($id));
    }
    public function get_preinvoices()
    {
        $invoices_model = $this->model("invoice");
        $preinvoices = $invoices_model->get_preinvoices();
        $user = $this->model("user");
        $users_info = $user->getAllUsersEvenDeleted();
        $users_info_array = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $nm = "";
            if (0 < strlen($users_info[$i]["name"])) {
                $nm = " - " . $users_info[$i]["name"];
            }
            $users_info_array[$users_info[$i]["id"]] = $users_info[$i]["username"] . $nm;
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($preinvoices); $i++) {
            $tmp = array();
            array_push($tmp, $preinvoices[$i]["id"]);
            array_push($tmp, $preinvoices[$i]["creation_date"]);
            array_push($tmp, $users_info_array[$preinvoices[$i]["created_by"]]);
            array_push($tmp, $preinvoices[$i]["client_name"]);
            array_push($tmp, $preinvoices[$i]["total_amount"]);
            array_push($tmp, "<button class=\"btn btn-primary btn-sm\" onclick=\"load_pending_invoice_id(" . $preinvoices[$i]["id"] . ")\" style=\"width:100%;padding:0px !important;font-size:14px !important;\">Load</button>");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function load_pending_invoice_id($_invoice_id)
    {
        $info = array();
        $invoices_model = $this->model("invoice");
        $preinvoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $pre_invoice_info = $invoices_model->get_pre_invoice_by_id($preinvoice_id);
        $info["client_id"] = $pre_invoice_info[0]["client_id"];
        $info["items"] = $invoices_model->get_pre_invoice_details_by_id($preinvoice_id);
        echo json_encode($info);
    }
    public function delete_preinvoice($_invoice_id)
    {
        $invoices_model = $this->model("invoice");
        $preinvoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $invoices_model->delete_preinvoice($preinvoice_id);
        echo json_encode(array());
    }
    public function delete_item_from_preinvoice($_item_id, $_preinvoice_id)
    {
        $invoices_model = $this->model("invoice");
        $preinvoice_id = filter_var($_preinvoice_id, FILTER_SANITIZE_NUMBER_INT);
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $invoices_model->delete_item_from_preinvoice($preinvoice_id, $item_id);
        echo json_encode(array());
    }
    public function get_pre_invoice_items($_preinvoice_id)
    {
        $invoices_model = $this->model("invoice");
        $items_model = $this->model("items");
        $customers_model = $this->model("customers");
        $preinvoice_id = filter_var($_preinvoice_id, FILTER_SANITIZE_NUMBER_INT);
        $customer_type = 1;
        $pre_invoice_details = $invoices_model->get_pre_invoice_by_id($preinvoice_id);
        if (0 < $pre_invoice_details[0]["client_id"]) {
            $customer_info = $customers_model->getCustomersById($pre_invoice_details[0]["client_id"]);
            $customer_type = $customer_info[0]["customer_type"];
        }
        $data_array["data"] = array();
        $result = $invoices_model->get_pre_invoice_items($preinvoice_id);
        for ($i = 0; $i < count($result); $i++) {
            $tmp = array();
            array_push($tmp, "");
            array_push($tmp, "");
            $sku = "";
            if ($result[$i]["sku_code"] != "undefined" && 0 < strlen($result[$i]["sku_code"])) {
                $sku = "<b>Code: </b> " . $result[$i]["sku_code"] . "<br/>";
            } else {
                if ($result[$i]["is_composite"] == 0) {
                    $c = $items_model->get_sku_of_composite_of_item_id($result[$i]["item_id"]);
                    $sku = "<b>Code: </b> " . $c . "<br/>";
                }
            }
            $barcode = "";
            if (0 < strlen($result[$i]["barcode"])) {
                $barcode = "<b>Bcode: </b> " . $result[$i]["barcode"] . "<br/>";
            }
            $composite = "";
            if ($result[$i]["is_composite"] == 1) {
                $composite_details = $items_model->get_composite_item_id($result[$i]["item_id"]);
                $composite = "<b>Units: </b> " . floatval($composite_details[0]["qty"]) . "<br/>";
            }
            array_push($tmp, $sku . $barcode . $composite . $result[$i]["description"] . "<br/><i class='bi bi-trash' onclick='delete_item(" . $result[$i]["item_id"] . ")'></i>");
            array_push($tmp, number_format($result[$i]["price"], 2));
            $qqty = floatval($result[$i]["qty"]);
            if ($customer_type == 3) {
                if (0 < $result[$i]["composite_qty"]) {
                    $qqty = floatval($result[$i]["composite_qty"]) . "x" . floatval($result[$i]["qty"]) / floatval($result[$i]["composite_qty"]) . "B";
                } else {
                    $qqty = floatval($result[$i]["qty"]);
                }
            } else {
                $qqty = floatval($result[$i]["qty"]);
            }
            array_push($tmp, "<span class='qty_css' onclick='qty_change(" . $result[$i]["item_id"] . ")'>" . $qqty . "</span>");
            array_push($tmp, number_format($result[$i]["total"], 2));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function set_qty($_item_id, $_preinvoice_id, $_qty)
    {
        $invoices_model = $this->model("invoice");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $preinvoice_id = filter_var($_preinvoice_id, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_qty, FILTER_SANITIZE_NUMBER_INT);
        $invoices_model->set_preinvoice_qty($item_id, $preinvoice_id, $qty);
        echo json_encode(array());
    }
    public function get_pre_invoices()
    {
        $return = array();
        $invoices_model = $this->model("invoice");
        $result = $invoices_model->get_pre_invoices();
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]["total_amount"] = number_format($result[$i]["total_amount"], 2);
            array_push($return, $result[$i]);
        }
        echo json_encode($return);
    }
    public function refresh_commissions($_vendor_id)
    {
        $invoices_model = $this->model("invoice");
        $vendor_id = filter_var($_vendor_id, FILTER_SANITIZE_NUMBER_INT);
        $invoices_model->refresh_commissions($vendor_id);
        echo json_encode(array());
    }
    public function get_invoice_price($_inv_id)
    {
        $invoices_model = $this->model("invoice");
        $inv_id = filter_var($_inv_id, FILTER_SANITIZE_NUMBER_INT);
        $details = $invoices_model->getInvoiceById($inv_id);
        echo json_encode(array("price" => floatval($details[0]["total_value"])));
    }
    public function get_needed_data_for_manual_creation()
    {
        $info = array();
        $customers = $this->model("customers");
        $employees = $this->model("employees");
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $info["customers"] = $customers->getCustomersMinified();
        $info["salesman"] = $employees->getAllEmployees();
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
        $info["is_taxable"] = $this->settings_info["invoice_taxable_enabled"];
        $result = $items->get_items_names_with_boxes();
        $info["items"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $info["items"][$i]["id"] = $result[$i]["id"];
            if ($result[$i]["barcode"] == NULL) {
                $result[$i]["barcode"] = "";
            }
            if ($result[$i]["second_barcode"] == NULL) {
                $result[$i]["second_barcode"] = "";
            }
            $color_size = "";
            if (isset($colors_info_label[$result[$i]["color_text_id"]]) && $result[$i]["color_text_id"] != 1) {
                $color_size .= $colors_info_label[$result[$i]["color_text_id"]];
            }
            if (isset($sizes_info_label[$result[$i]["size_id"]]) && $result[$i]["size_id"] != 1) {
                $color_size .= "-" . $sizes_info_label[$result[$i]["size_id"]];
            }
            if (strlen($result[$i]["barcode"]) < 5) {
                $result[$i]["barcode"] = sprintf("%05s", $result[$i]["barcode"]);
            }
            $info["items"][$i]["name"] = $result[$i]["description"] . "-" . $result[$i]["barcode"] . "-" . $color_size;
        }
        echo json_encode($info);
    }
    public function update_cost($inv_item_id, $cost)
    {
        $invoice = $this->model("invoice");
        $invoice->update_cost($inv_item_id, $cost);
        $result = $invoice->get_item_from_inv($inv_item_id);
        $invoice->calculate_total_cost_price($inv_item_id);
        $invoice->calculate_total_value($result[0]["invoice_id"]);
        $invoice->calculate_total_profit_for_invoice($result[0]["invoice_id"]);
        echo json_encode(array());
    }
    public function get_deliveries($_status)
    {
        self::giveAccessTo(array(2, 4));
        $status_id = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
        $filter = array();
        $filter["status_id"] = $status_id;
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $invoice_info = $invoice->get_invoices_pending_deliveries($filter);
        $data_array["data"] = array();
        for ($i = 0; $i < count($invoice_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($invoice_info[$i]["id"]));
            if (0 < strlen($invoice_info[$i]["delivery_ref"])) {
                array_push($tmp, $invoice_info[$i]["delivery_ref"] . "<i class='glyphicon glyphicon-edit edref' onclick='edit_del_ref(" . $invoice_info[$i]["id"] . ",this)' ></i>");
            } else {
                array_push($tmp, "<input id='delref_" . $invoice_info[$i]["id"] . "' onchange='changeref(" . $invoice_info[$i]["id"] . ")' style='color:#000;width:100%;height:23px;' type='text' value='" . $invoice_info[$i]["delivery_ref"] . "' />");
            }
            if ($invoice_info[$i]["customer_id"] != NULL && $invoice_info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($invoice_info[$i]["customer_id"]);
                array_push($tmp, $customer[0]["name"] . " " . $customer[0]["middle_name"] . " " . $customer[0]["last_name"] . " " . $customer[0]["address"] . " " . $customer[0]["phone"]);
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, self::value_format_custom($invoice_info[$i]["total_value"] + $invoice_info[$i]["invoice_discount"], $this->settings_info));
            array_push($tmp, self::value_format_custom($invoice_info[$i]["delivery_cost"], $this->settings_info));
            array_push($tmp, self::value_format_custom($invoice_info[$i]["total_value"] + $invoice_info[$i]["invoice_discount"] + $invoice_info[$i]["delivery_cost"], $this->settings_info));
            array_push($tmp, "");
            array_push($tmp, $invoice_info[$i]["delivery"]);
            array_push($tmp, "");
            if ($invoice_info[$i]["customer_id"] != NULL && $invoice_info[$i]["customer_id"] != 0) {
                array_push($tmp, $invoice_info[$i]["customer_id"]);
            } else {
                array_push($tmp, "0");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function set_delivery_as_done($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $invoice->set_delivery_as_done($id);
        echo json_encode(array());
    }
    public function change_delivery_reference($_id, $_ref)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $ref = filter_var($_ref, self::conversion_php_version_filter());
        $invoice = $this->model("invoice");
        $invoice->change_delivery_reference($id, $ref);
        echo json_encode(array());
    }
    public function create_invoice_manual($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $data = array();
        $this->view("create_invoice_manual", $data);
    }
    public function generateInvoiceId()
    {
        $this->checkAuth();
        $invoice = $this->model("invoice");
        $info = array();
        $invoice_id = $invoice->generateInvoiceId_manual($_SESSION["store_id"], $_SESSION["id"], $this->settings_info["vat"]);
        $info["invoice_id"] = $invoice_id;
        echo json_encode($info);
    }
    public function status_changed($_invoice_id, $_status)
    {
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $invoice->status_changed($invoice_id, $status);
        echo json_encode(array());
    }
    public function print_invoice($_invoice_id, $_currency)
    {
        $currency_id = filter_var($_currency, FILTER_SANITIZE_NUMBER_INT);
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $data = array();
        $invoices = $this->model("invoice");
        $items = $this->model("items");
        $customers = $this->model("customers");
        $currency = $this->model("currency");
        $payments = $this->model("payments");
        $employees = $this->model("employees");
        $user = $this->model("user");
        $data["invoice"] = $invoices->getInvoiceById($invoice_id);
        $data["currency_request_id"] = $currency_id;
        $data["invoice_model"] = $invoices;
        $data["customer"] = NULL;
        $invoice_type = 1;
        if (!is_null($data["invoice"][0]["customer_id"])) {
            $data["customer"] = $customers->getCustomersById($data["invoice"][0]["customer_id"]);
            $invoice_type = $data["customer"][0]["customer_type"];
        }
        $data["invoice_items"] = $invoices->getItemsOfInvoice($invoice_id);
        $data["items_instance"] = $items;
        if (0 < $data["invoice"][0]["customer_id"]) {
            $data["total_balance"] = $payments->get_total_balance($data["invoice"][0]["customer_id"]) + $data["customer"][0]["starting_balance"];
        } else {
            $data["total_balance"] = 0;
        }
        $limit_date = $data["invoice"][0]["creation_date"];
        if ($data["invoice"][0]["customer_id"] != NULL && $data["invoice"][0]["customer_id"] != 0) {
            $data["previews_balance"] = $payments->get_previews_balance($data["invoice"][0]["customer_id"], $limit_date) + $data["customer"][0]["starting_balance"];
        } else {
            $data["previews_balance"] = 0;
        }
        $all_currencies = $currency->getAllEnabledCurrencies();
        $data["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            if ($all_currencies[$i]["system_default"] == 1) {
                $data["currency_system_default"] = $all_currencies[$i]["id"];
            }
            if ($all_currencies[$i]["default_vat"] == true) {
                $data["default_vat_rate"] = $all_currencies[$i]["rate_to_system_default"];
                $data["default_vat_symbole"] = $all_currencies[$i]["symbole"];
            }
            $data["currencies"][$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $quotations = $this->model("quotations");
        $quotations_details = $quotations->get_quotation_created_by($invoice_id);
        if (0 < count($quotations_details)) {
            $user_info = $user->get_user_by_id($quotations_details[0]["created_by"]);
            $data["salesperson_name"] = $user_info[0]["username"];
        } else {
            if ($data["invoice"][0]["sales_person"] == 0) {
                $data["salesperson_name"] = "";
            } else {
                $employee_info = $employees->get_employee_even_delete($data["invoice"][0]["sales_person"]);
                $data["salesperson_name"] = $employee_info[0]["first_name"] . " " . $employee_info[0]["last_name"];
            }
        }
        if ($this->settings_info["a4_print_style"] == 1) {
            $this->view("printing/a4/invoice_1", $data);
        }
        if ($this->settings_info["a4_print_style"] == 2) {
            $this->view("printing/a4/invoice_2", $data);
        }
        if ($this->settings_info["a4_print_style"] == 3) {
            $this->view("printing/a4/invoice_pro", $data);
        }
        if ($this->settings_info["a4_print_style"] == 4) {
            $this->view("printing/a4/invoice_pro_1", $data);
        }
        if ($this->settings_info["a4_print_style"] == 6) {
            $this->view("printing/a4/invoice_pro_2", $data);
        }
        if ($this->settings_info["a4_print_style"] == 5) {
            $this->view("printing/a4/invoice_pro_1_a5", $data);
        }
        if ($this->settings_info["a4_print_style"] == 100) {
            $this->view("printing/a4/invoice_pro_template", $data);
        }
        if ($this->settings_info["a4_print_style"] == 7) {
            $data["arabic_stmt_and_invoice"] = $this->settings_info["arabic_stmt_and_invoice"];
            if ($invoice_type == 1 || $invoice_type == 2) {
                $this->view("printing/a4/invoice_pro_3", $data);
            } else {
                $this->view("printing/a4/invoice_pro_3_wholesale", $data);
            }
        }
        if ($this->settings_info["a4_print_style"] == 4) {
        }
        if ($this->settings_info["a4_print_style"] == 5) {
            $this->view("printing/a4/invoice_pro_kassem", $data);
        }
    }
    public function invoice_must_pay()
    {
        self::giveAccessTo();
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $this->view("invoices_must_pay", $data);
    }
    public function all_invoice()
    {
        self::giveAccessTo();
        $data = array();
        $data["print_a4_pdf_version"] = $this->settings_info["print_a4_pdf_version"];
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $data["enable_new_multibranches"] = $this->settings_info["enable_new_multibranches"];
        $data["branches"] = self::get_accessible_branches();
        $this->view("all_invoices", $data);
    }
    public function update_invoice()
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $store = $this->model("store");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["invoice_discount"] = filter_input(INPUT_POST, "invoice_discount", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["item_invoice_id"] = array();
        $info["qty"] = array();
        $info["discount"] = array();
        if (isset($_POST["qty"])) {
            for ($i = 0; $i < count($_POST["qty"]["item_invoice_id"]); $i++) {
                $info["item_invoice_id"][$i] = $_POST["qty"]["item_invoice_id"][$i];
            }
            for ($i = 0; $i < count($_POST["qty"]["qty"]); $i++) {
                $info["qty"][$i] = $_POST["qty"]["qty"][$i];
            }
        }
        if (isset($_POST["disc"])) {
            for ($i = 0; $i < count($_POST["disc"]["discount"]); $i++) {
                $info["discount"][$i] = $_POST["disc"]["discount"][$i];
            }
        }
        $invoice_info = $invoice->getInvoiceById($info["id_to_edit"]);
        for ($i = 0; $i < count($info["item_invoice_id"]); $i++) {
            $item_invoice_details = $invoice->get_item_from_invoice($info["item_invoice_id"][$i]);
            $info_add_qty = array();
            if ($info["qty"][$i] < $item_invoice_details[0]["qty"]) {
                $info_add_qty["qty"] = abs($item_invoice_details[0]["qty"] - $info["qty"][$i]);
            } else {
                $info_add_qty["qty"] = 0 - abs($item_invoice_details[0]["qty"] - $info["qty"][$i]);
            }
            $info_add_qty["item_id"] = $item_invoice_details[0]["item_id"];
            $info_add_qty["store_id"] = $invoice_info[0]["store_id"];
            $info_add_qty["source"] = "invoice_edit";
            $store->add_qty($info_add_qty);
            $invoice->update_invoice_item($info["item_invoice_id"][$i], $info["qty"][$i], $info["discount"][$i]);
            $invoice->calculate_total_cost_price($info["item_invoice_id"][$i]);
        }
        $invoice->update_invoice($info);
        $invoice->calculate_total_value($info["id_to_edit"]);
        $invoice->calculate_total_profit_for_invoice($info["id_to_edit"]);
        echo json_encode(array());
    }
    public function getInvoiceItemsDetails($_invoice_id)
    {
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $info = $invoice->getInvoiceItemsDetails($invoice_id);
        $info["currency_counnt"] = $_SESSION["currency_counnt"];
        $info["customer"] = array();
        if (0 < $info["invoice"][0]["customer_id"]) {
            $info["customer"] = $customers->getCustomersById($info["invoice"][0]["customer_id"]);
        }
        echo json_encode($info);
    }
    public function get_all_item_in_invoice($_invoice_id)
    {
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $return_invoice_items = $invoice->get_returned_by_invoice($invoice_id);
        $return_invoice_item_id = NULL;
        for ($i = 0; $i < count($return_invoice_items); $i++) {
            $return_invoice_item_id = $return_invoice_items[$i]["invoice_item_id"];
        }
        $invoice_items = $invoice->getItemsOfInvoice($invoice_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($invoice_items); $i++) {
            $invoice_imeis = $invoice->getInvoiceImeis($invoice_id, $invoice_items[$i]["item_id"]);
            $imei = "";
            for ($k = 0; $k < count($invoice_imeis); $k++) {
                $imei .= "<br/><b>" . $invoice_imeis[$k]["code1"] . "</b>";
                if (0 < strlen($invoice_imeis[$k]["code2"])) {
                    $imei .= " / <b>" . $invoice_imeis[$k]["code2"] . "</b>";
                }
            }
            $item_info = $items->get_item($invoice_items[$i]["item_id"]);
            if ($invoice_items[$i]["vat"] == 1) {
                $no_vat_selected = "";
                $vat_selected = "selected";
            } else {
                $no_vat_selected = "selected";
                $vat_selected = "";
            }
            $tmp = array();
            array_push($tmp, $invoice_items[$i]["id"]);
            if ($invoice_items[$i]["added_new"] == 1) {
                array_push($tmp, "<span style='color:red'>" . self::idFormat_item($invoice_items[$i]["item_id"]) . "</span>");
            } else {
                array_push($tmp, self::idFormat_item($invoice_items[$i]["item_id"]));
            }
            array_push($tmp, $item_info[0]["sku_code"]);
            array_push($tmp, $item_info[0]["barcode"]);
            if ($item_info[0]["is_composite"] == 0) {
                array_push($tmp, $item_info[0]["description"] . $imei);
            } else {
                $composite_info = $items->get_composite_item_id($invoice_items[$i]["item_id"]);
                array_push($tmp, $item_info[0]["description"] . $imei . "<b>(" . floatval($composite_info[0]["qty"]) . "U/Box)</b>");
            }
            array_push($tmp, "<input onchange='update_ad_item_description(" . $invoice_items[$i]["id"] . ")' class='minv_des' type='text' id='addesc_" . $invoice_items[$i]["id"] . "' value='" . $invoice_items[$i]["additional_description"] . "' />");
            array_push($tmp, "<input onchange='update_total(" . $invoice_items[$i]["id"] . "," . $invoice_items[$i]["invoice_id"] . ")' class='minv_ cleavesf3 minv_" . $invoice_items[$i]["item_id"] . "' type='text' id='inv_it_price_" . $invoice_items[$i]["id"] . "' value='" . floatval($invoice_items[$i]["selling_price"]) . "' />");
            array_push($tmp, "<input onchange='update_total(" . $invoice_items[$i]["id"] . "," . $invoice_items[$i]["invoice_id"] . ")' class='minv cleavesf2' type='text' id='inv_it_dis_" . $invoice_items[$i]["id"] . "' value='" . floatval($invoice_items[$i]["discount"]) . "' />");
            array_push($tmp, "<select onchange='update_total(" . $invoice_items[$i]["id"] . "," . $invoice_items[$i]["invoice_id"] . ")' id='mivat_" . $invoice_items[$i]["id"] . "' class='minv_s'><option value='1' " . $vat_selected . ">" . $this->settings_info["vat"] . "%</option><option value='0'  " . $no_vat_selected . ">No</option></select>");
            if ($vt == 0) {
                array_push($tmp, "<input readonly class='minv_ cleavesf3' type='text' id='fp_" . $invoice_items[$i]["id"] . "' value='" . floatval($invoice_items[$i]["selling_price"]) * (1 - $invoice_items[$i]["discount"] / 100) . "' />");
            } else {
                array_push($tmp, "<input readonly class='minv_ cleavesf3' type='text' id='fp_" . $invoice_items[$i]["id"] . "' value='" . floatval($invoice_items[$i]["selling_price"]) * (1 - $invoice_items[$i]["discount"] / 100) * $vt . "' />");
            }
            array_push($tmp, "<input onchange='update_total(" . $invoice_items[$i]["id"] . "," . $invoice_items[$i]["invoice_id"] . ")' class='minv inv_it_qty_" . $invoice_items[$i]["item_id"] . "' type='number' id='inv_it_qty_" . $invoice_items[$i]["id"] . "' value='" . floatval($invoice_items[$i]["qty"]) . "' />");
            $vt = 1;
            if ($invoice_items[$i]["vat"] == 1) {
                $vt = $invoice_items[$i]["vat_value"];
            }
            if ($vt == 0) {
                array_push($tmp, "<input readonly class='minvread cleavesf3 total_per_item itid_" . $invoice_items[$i]["item_id"] . "' type='text' id='inv_it_tp_" . $invoice_items[$i]["id"] . "' value='" . floatval($invoice_items[$i]["selling_price"]) * (1 - $invoice_items[$i]["discount"] / 100) * floatval($invoice_items[$i]["qty"]) . "' />");
            } else {
                array_push($tmp, "<input readonly class='minvread cleavesf3 total_per_item itid_" . $invoice_items[$i]["item_id"] . "' type='text' id='inv_it_tp_" . $invoice_items[$i]["id"] . "' value='" . floatval($invoice_items[$i]["selling_price"]) * (1 - $invoice_items[$i]["discount"] / 100) * $vt * floatval($invoice_items[$i]["qty"]) . "' />");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function update_add_item_description($invoice_item_id, $_description)
    {
        $invoice = $this->model("invoice");
        $description = filter_var($_description, self::conversion_php_version_filter());
        $invoice->update_add_item_description($invoice_item_id, $description);
        echo json_encode(array());
    }
    public function generate_empty_invoice()
    {
        $invoice = $this->model("invoice");
        $id = $invoice->generate_empty_invoice($_SESSION["store_id"], $_SESSION["id"], $this->settings_info["vat"]);
        echo json_encode($id);
    }
    public function generate_empty_invoice_for_branch($_branch_id)
    {
        $invoice = $this->model("invoice");
        $branch_id = filter_var($_branch_id, FILTER_SANITIZE_NUMBER_INT);
        $id = $invoice->generate_empty_invoice_for_branch($_SESSION["store_id"], $_SESSION["id"], $this->settings_info["vat"], $branch_id);
        echo json_encode($id);
    }
    public function addItemsToInvoice_manual($_id_invoice, $_id_item, $_customer_id)
    {
        self::giveAccessTo(array(2, 3, 4));
        $id_invoice = filter_var($_id_invoice, FILTER_SANITIZE_NUMBER_INT);
        $id_item = filter_var($_id_item, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $store = $this->model("store");
        $customers = $this->model("customers");
        $customer_info = $customers->getCustomersById($_customer_id);
        $info["invoice_id"] = $id_invoice;
        $info["item_id"] = $id_item;
        $info["qty"] = 1;
        $info["custom_item"] = 0;
        $info["mobile_transfer_item"] = 0;
        $info["manual_discounted"] = 0;
        $info["mobile_transfer_device_id"] = 0;
        $items_info = $items->get_item($id_item);
        $info["buying_cost"] = $items_info[0]["buying_cost"];
        $info["is_official"] = $items_info[0]["is_official"];
        $info["mobile_transfer_item"] = 0;
        $info["vat"] = $items_info[0]["vat"];
        if ($items_info[0]["complex_item_id"] == 0) {
            if (0 < count($customer_info) && $customer_info[0]["customer_type"] == 2) {
                $info["selling_price"] = $items_info[0]["wholesale_price"];
            } else {
                if (0 < count($customer_info) && $customer_info[0]["customer_type"] == 3) {
                    $info["selling_price"] = $items_info[0]["second_wholesale_price"];
                } else {
                    $info["selling_price"] = $items_info[0]["selling_price"];
                }
            }
        } else {
            $info["selling_price"] = $items_info[0]["selling_price"];
        }
        $info["discount"] = $items_info[0]["discount"];
        $info["is_composite"] = $items_info[0]["is_composite"];
        $info["vat_value"] = $this->settings_info["vat"];
        if ($info["vat"] == 0) {
            $info["final_cost"] = $info["buying_cost"] * $info["qty"];
        } else {
            $info["final_cost"] = $info["buying_cost"] * floatval($this->settings_info["vat"]) * $info["qty"];
        }
        if ($info["vat"] == 0) {
            $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"];
        } else {
            $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"] * floatval($items_info[0]["vat_value"]);
        }
        $info["profit"] = $info["final_price"] - $info["final_cost"];
        $invoice->addItemsToInvoice($info);
        if ($info["is_composite"] == 0) {
            $store->reduce_qty_by_admin($_SESSION["store_id"], $id_item, $info["qty"], $_SESSION["id"], $id_invoice);
        } else {
            $composite_items = $items->get_all_composite_of_item($info["item_id"]);
            for ($kk = 0; $kk < count($composite_items); $kk++) {
                $store->reduce_qty_by_admin($_SESSION["store_id"], $composite_items[$kk]["item_id"], $composite_items[$kk]["qty"] * $info["qty"], $_SESSION["id"], $id_invoice);
            }
        }
        if (0 < $items_info[0]["complex_item_id"]) {
            $tmp = array();
            $tmp["item_id"] = $items_info[0]["id"];
            $tmp["item_qty"] = $info["qty"];
            $store->reduce_qty_of_composite($tmp);
        }
        echo json_encode(array());
    }
    public function addItemsToInvoice($_id_invoice, $_id_item)
    {
        self::giveAccessTo(array(2, 3, 4));
        $id_invoice = filter_var($_id_invoice, FILTER_SANITIZE_NUMBER_INT);
        $id_item = filter_var($_id_item, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $store = $this->model("store");
        $info["invoice_id"] = $id_invoice;
        $info["item_id"] = $id_item;
        $info["qty"] = 1;
        $info["custom_item"] = 0;
        $info["mobile_transfer_item"] = 0;
        $info["manual_discounted"] = 0;
        $info["mobile_transfer_device_id"] = 0;
        $items_info = $items->get_item($id_item);
        $info["buying_cost"] = $items_info[0]["buying_cost"];
        $info["is_official"] = $items_info[0]["is_official"];
        $info["mobile_transfer_item"] = 0;
        $info["vat"] = $items_info[0]["vat"];
        $info["selling_price"] = $items_info[0]["selling_price"];
        $info["discount"] = 0;
        $info["is_composite"] = $items_info[0]["is_composite"];
        $info["vat_value"] = $this->settings_info["vat"];
        if ($info["vat"] == 0) {
            $info["final_cost"] = $info["buying_cost"] * $info["qty"];
        } else {
            $info["final_cost"] = $info["buying_cost"] * floatval($this->settings_info["vat"]) * $info["qty"];
        }
        if ($info["vat"] == 0) {
            $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"];
        } else {
            $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"] * floatval($items_info[0]["vat_value"]);
        }
        $info["profit"] = $info["final_price"] - $info["final_cost"];
        $invoice->addItemsToInvoice($info);
        if ($info["is_composite"] == 0) {
            $store->reduce_qty_by_pos($_SESSION["store_id"], $id_item, $info["qty"], $_SESSION["id"]);
        } else {
            $composite_items = $items->get_all_composite_of_item($info["item_id"]);
            for ($kk = 0; $kk < count($composite_items); $kk++) {
                $store->reduce_qty_by_pos($_SESSION["store_id"], $composite_items[$kk]["item_id"], $composite_items[$kk]["qty"] * $info["qty"], $_SESSION["id"]);
            }
        }
        echo json_encode(array());
    }
    public function get_invoice_by_id($invoice_id_)
    {
        self::giveAccessTo(array(2, 4));
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $user = $this->model("user");
        $employees = $this->model("employees");
        $invoice_info = $invoice->getInvoiceById($invoice_id);
        $invoice_info[0]["id"] = self::idFormat_invoice($invoice_info[0]["id"]);
        $invoice_info[0]["total_value_"] = self::value_format_custom($invoice_info[0]["total_value"], $this->settings_info);
        $invoice_info[0]["invoice_discount_"] = self::value_format_custom(abs($invoice_info[0]["invoice_discount"]), $this->settings_info);
        $invoice_info[0]["total_after_discount"] = self::value_format_custom($invoice_info[0]["total_value"] + $invoice_info[0]["invoice_discount"], $this->settings_info);
        $invoice_info[0]["default_currency_symbol"] = $this->settings_info["default_currency_symbol"];
        $info = array();
        $info["id"] = $invoice_info[0]["employee_id"];
        $user_info = $user->get_user($info);
        $invoice_info[0]["vendor_name"] = $user_info[0]["name"];
        if ($invoice_info[0]["sales_person"] == 0) {
            $invoice_info[0]["salesperson_name"] = "";
        } else {
            $employee_info = $employees->get_employee_even_delete($invoice_info[0]["sales_person"]);
            $invoice_info[0]["salesperson_name"] = $employee_info[0]["first_name"] . " " . $employee_info[0]["last_name"];
        }
        echo json_encode($invoice_info);
    }
    public function get_invoice_details_by_id($id_)
    {
        self::giveAccessTo(array(1, 2));
        $invoice = $this->model("invoice");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $invoice->getItemsOfInvoice($id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($item_info); $i++) {
            $tmp = array();
            $items = $this->model("items");
            array_push($tmp, $item_info[$i]["id"]);
            if ($item_info[$i]["item_id"] != NULL) {
                $item_info_details = $items->get_item($item_info[$i]["item_id"]);
                array_push($tmp, self::idFormat_item($item_info[$i]["item_id"]));
                array_push($tmp, $item_info_details[0]["description"]);
            } else {
                array_push($tmp, "");
                array_push($tmp, $item_info[$i]["description"]);
            }
            array_push($tmp, "<input type='hidden' name='qty[item_invoice_id][]' value=" . (double) $item_info[$i]["id"] . " /><input class='input-xs only_numeric' type='text' name='qty[qty][]' value='" . (double) $item_info[$i]["qty"] . "'>");
            array_push($tmp, self::value_format_custom($item_info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, "<input type='hidden' name='disc[item_invoice_id][]' value=" . (double) $item_info[$i]["id"] . " /><input class='input-xs only_numeric' type='text' name='disc[discount][]' value='" . (double) $item_info[$i]["discount"] . "'>");
            if ($item_info[$i]["vat"] == 1) {
                array_push($tmp, ($item_info[$i]["vat_value"] - 1) * 100 . " %");
            } else {
                array_push($tmp, $item_info[$i]["vat"]);
            }
            if ($item_info[$i]["vat"] == 1) {
                array_push($tmp, self::value_format_custom($item_info[$i]["selling_price"] * (1 - $item_info[$i]["discount"] / 100) * $item_info[$i]["vat_value"], $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($item_info[$i]["selling_price"] * (1 - $item_info[$i]["discount"] / 100), $this->settings_info));
            }
            if ($item_info[$i]["vat"] == 1) {
                array_push($tmp, self::value_format_custom($item_info[$i]["selling_price"] * (1 - $item_info[$i]["discount"] / 100) * $item_info[$i]["vat_value"] * $item_info[$i]["qty"], $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($item_info[$i]["selling_price"] * (1 - $item_info[$i]["discount"] / 100) * $item_info[$i]["qty"], $this->settings_info));
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function close_invoice($invoice_id_)
    {
        self::giveAccessTo(array(2, 4));
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $paymentsInfo = $payments->getTotalPayments($invoice_id);
        $invoice_info = $invoice->getInvoiceById($invoice_id);
        $info["invoice_id"] = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $info["value"] = $invoice_info[0]["total_value"] - $paymentsInfo[0]["sum"];
        $info["vendor_id"] = $_SESSION["id"];
        if ($_SESSION["role"] == 2) {
            $info["store_id"] = $_SESSION["store_id"];
        } else {
            $info["store_id"] = "NULL";
        }
        $payments->add_payment($info);
        $invoice->closeInvoice($invoice_id);
        $info_to_return["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $info["store_id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info_to_return);
    }
    public function set_status($_invoice_id)
    {
        self::giveAccessTo();
        $invoice_id = filter_var($_invoice_id, self::conversion_php_version_filter());
        $invoice = $this->model("invoice");
        $invoice->set_status($invoice_id);
        $invoice_info = $invoice->getInvoiceById($invoice_id);
        self::autoCloseInvoices($invoice_info[0]["customer_id"]);
        echo json_encode(array());
    }
    public function getAllInvoicesDateRange($store_id_, $_date, $_filter_invoices, $_filter_salesperson, $_vendord_id, $_is_taxable)
    {
        self::giveAccessTo();
        $date = filter_var($_date, self::conversion_php_version_filter());
        $filter_invoices = filter_var($_filter_invoices, self::conversion_php_version_filter());
        $filter_salesperson = filter_var($_filter_salesperson, FILTER_SANITIZE_NUMBER_INT);
        $vendord_id = filter_var($_vendord_id, FILTER_SANITIZE_NUMBER_INT);
        $is_taxable = filter_var($_is_taxable, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $settings = $this->model("settings");
        $employees = $this->model("employees");
        $creditnote = $this->model("creditnote");
        $balances = self::get_all_balances();
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
        $employees_info = $employees->getAllEmployeesEvenDeleted();
        $employees_info_array = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["first_name"] . " " . $employees_info[$i]["last_name"];
        }
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
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoices = $invoice->getAllInvoicesSwitch($store_id, $date_range, $filter_invoices, $this->settings_info, $filter_salesperson, $vendord_id, $is_taxable);
        $data_array["data"] = array();
        $total_clients_balance_counted = array();
        $total_clients_balance = 0;
        $_total_amounts = 0;
        $_total_profits = 0;
        $data_array["total_clients_balance"] = 0;
        $data_array["_total_amounts"] = 0;
        $settings_payments_methos = $settings->get_all_payment_method();
        $p_method = array();
        for ($i = 0; $i < count($settings_payments_methos); $i++) {
            $p_method[$settings_payments_methos[$i]["id"]] = $settings_payments_methos[$i]["method_name"];
        }
        $customers_remain = array();
        $customers = $this->model("customers");
        if ($this->settings_info["show_currency_in_report"] == 0) {
            $this->settings_info["default_currency_symbol"] = "";
        }
        $customers_ids = array();
        $customers_details_array = array();
        for ($i = 0; $i < count($invoices); $i++) {
            if ($invoices[$i]["customer_id"] != NULL && $invoices[$i]["customer_id"] != 0) {
                array_push($customers_ids, $invoices[$i]["customer_id"]);
            }
        }
        if (0 < count($customers_ids)) {
            $customers_details = $customers->getCustomersByIDSArray($customers_ids);
            for ($i = 0; $i < count($customers_details); $i++) {
                $customers_details_array[$customers_details[$i]["id"]] = $customers_details[$i];
            }
        }
        for ($i = 0; $i < count($invoices); $i++) {
            $tmp = array();
            if ($invoices[$i]["customer_id"] != NULL && $invoices[$i]["customer_id"] != 0 && !isset($customers_remain[$invoices[$i]["customer_id"]])) {
                if (!in_array($invoices[$i]["customer_id"], $total_clients_balance_counted)) {
                    array_push($total_clients_balance_counted, $invoices[$i]["customer_id"]);
                    $total_clients_balance += $balances[$invoices[$i]["customer_id"]];
                }
                self::autoCloseInvoices($invoices[$i]["customer_id"]);
                $customers_remain[$invoices[$i]["customer_id"]] = 0;
            }
            $customersInfo = NULL;
            if ($invoices[$i]["customer_id"] != NULL && $invoices[$i]["customer_id"] != 0) {
                $customersInfo[0] = $customers_details_array[$invoices[$i]["customer_id"]];
            } else {
                $customersInfo = array();
            }
            $invid = $invoices[$i]["id"];
            $inv_off_id = "";
            if (0 < $invoices[$i]["invoice_nb_official"]) {
                $inv_off_id = "/" . $invoices[$i]["invoice_nb_official"];
            }
            array_push($tmp, self::idFormat_invoice($invid) . $inv_off_id);
            if (0 < count($customersInfo)) {
                array_push($tmp, self::idFormat_customer($invoices[$i]["customer_id"]));
            } else {
                array_push($tmp, "");
            }
            if (0 < count($customersInfo)) {
                array_push($tmp, $customersInfo[0]["name"] . " " . $customersInfo[0]["middle_name"] . " " . $customersInfo[0]["last_name"]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, self::global_number_formatter($customersInfo[0]["starting_balance"], $this->settings_info));
            if (isset($employees_info_array[$invoices[$i]["sales_person"]])) {
                array_push($tmp, $employees_info_array[$invoices[$i]["sales_person"]]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, $invoices[$i]["creation_date"]);
            $vt_val = 0;
            if (0 < $invoices[$i]["vat_value"]) {
                $vt_val = ($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"]) * ($invoices[$i]["vat_value"] - 1);
            }
            if ($invoices[$i]["closed"] == 1) {
                $_total_amounts += $invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"] + $vt_val;
                array_push($tmp, "<b>" . self::global_number_formatter(floatval($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"] + $vt_val), $this->settings_info)) . "<b/>";
                array_push($tmp, self::global_number_formatter(floatval($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"]), $this->settings_info));
            } else {
                $_total_amounts += $invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"] + $vt_val;
                array_push($tmp, "<b class='unpaid'>" . self::global_number_formatter(floatval($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"] + $vt_val), $this->settings_info)) . "<b/>";
                array_push($tmp, self::global_number_formatter(floatval($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"]), $this->settings_info));
            }
            if ($invoices[$i]["closed"] == 1) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }
            if ($invoices[$i]["closed"] == 1 && $invoices[$i]["auto_closed"] == 0) {
                array_push($tmp, "");
                array_push($tmp, "");
            } else {
                if ($invoices[$i]["closed"] == 0 && $invoices[$i]["auto_closed"] == 0) {
                    if ($customers_remain[$invoices[$i]["customer_id"]] == 0) {
                        array_push($tmp, "<b class='unpaid'>" . self::global_number_formatter($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"] - $customers_remain[$invoices[$i]["customer_id"]], $this->settings_info) . "<b/>");
                        array_push($tmp, self::global_number_formatter($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"] - $customers_remain[$invoices[$i]["customer_id"]], $this->settings_info));
                    } else {
                        array_push($tmp, "<b class='unpaid'>" . self::global_number_formatter($customers_remain[$invoices[$i]["customer_id"]], $this->settings_info) . "<b/>");
                        array_push($tmp, self::global_number_formatter($customers_remain[$invoices[$i]["customer_id"]], $this->settings_info));
                        $customers_remain[$invoices[$i]["customer_id"]] = 0;
                    }
                } else {
                    if ($invoices[$i]["closed"] == 1 && $invoices[$i]["auto_closed"] == 1) {
                        array_push($tmp, "");
                        array_push($tmp, "");
                    } else {
                        array_push($tmp, "");
                        array_push($tmp, "");
                    }
                }
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                $_total_profits += $invoices[$i]["profit_after_discount"];
                array_push($tmp, self::global_number_formatter($invoices[$i]["profit_after_discount"], $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            if ($invoices[$i]["customer_id"] == 0) {
                array_push($tmp, "");
            } else {
                array_push($tmp, self::global_number_formatter($balances[$invoices[$i]["customer_id"]], $this->settings_info));
            }
            if ($invoices[$i]["closed"] == 1) {
                array_push($tmp, "PAID ");
            } else {
                array_push($tmp, "NOT PAID");
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, $invoices[$i]["deleted"]);
            array_push($data_array["data"], $tmp);
        }
        $data_array["total_clients_balance"] = self::global_number_formatter(floatval($total_clients_balance), $this->settings_info);
        $data_array["_total_amounts"] = self::global_number_formatter(floatval($_total_amounts), $this->settings_info);
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $data_array["_total_profits"] = self::global_number_formatter(floatval($_total_profits), $this->settings_info);
        } else {
            $data_array["_total_profits"] = self::critical_data();
        }
        echo json_encode($data_array);
    }
    public function set_official_nb_manual($_invoice_id, $_nb)
    {
        self::giveAccessTo();
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $nb = filter_var($_nb, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $invoice->set_official_nb_manual($invoice_id, $nb);
        echo json_encode(array());
    }
    public function update_salesperson_of_invoice($_invoice_id, $_salesperson_id)
    {
        self::giveAccessTo();
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $salesperson_id = filter_var($_salesperson_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $invoice->update_salesperson_of_invoice($invoice_id, $salesperson_id);
        echo json_encode(array());
    }
    public function get_invoice_info($_invoice_id)
    {
        self::giveAccessTo();
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $employees = $this->model("employees");
        $invoice = $this->model("invoice");
        $info = array();
        $info["employees"] = $employees->getAllEmployees();
        $info["invoice"] = $invoice->getInvoiceById($invoice_id);
        echo json_encode($info);
    }
    public function getAllInvoices($store_id_)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $settings = $this->model("settings");
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoices = $invoice->getAllInvoicesSwitch($store_id);
        $data_array["data"] = array();
        $settings_payments_methos = $settings->get_all_payment_method();
        $p_method = array();
        for ($i = 0; $i < count($settings_payments_methos); $i++) {
            $p_method[$settings_payments_methos[$i]["id"]] = $settings_payments_methos[$i]["method_name"];
        }
        $customers = $this->model("customers");
        for ($i = 0; $i < count($invoices); $i++) {
            $tmp = array();
            $customersInfo = NULL;
            if ($invoices[$i]["customer_id"] != NULL && $invoices[$i]["customer_id"] != 0) {
                $customersInfo = $customers->getCustomersById($invoices[$i]["customer_id"]);
            } else {
                $customersInfo = array();
            }
            array_push($tmp, self::idFormat_invoice($invoices[$i]["id"]));
            if (0 < count($customersInfo)) {
                array_push($tmp, self::idFormat_customer($invoices[$i]["customer_id"]));
            } else {
                array_push($tmp, "-");
            }
            if (0 < count($customersInfo)) {
                array_push($tmp, $customersInfo[0]["name"]);
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, $invoices[$i]["creation_date"]);
            array_push($tmp, self::value_format_custom(floatval($invoices[$i]["total_value"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, "<b>" . $p_method[$invoices[$i]["payment_method"]] . "</b>");
            array_push($tmp, self::value_format_custom($invoices[$i]["profit_after_discount"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function addPayment($invoice_id_, $value_)
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $info["invoice_id"] = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $info["value"] = filter_var($value_, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["vendor_id"] = $_SESSION["id"];
        $info["store_id"] = $_SESSION["store_id"];
        $payments = $this->model("payments");
        $payments->add_payment($info);
        $info_to_return["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info_to_return);
    }
    public function addCustomerPaymentDetails($customer_id_, $value_, $payment_method_)
    {
        self::giveAccessTo(array(2, 4));
        if ($_SESSION["demo"] == 1) {
            $info_to_return = array();
            $info_to_return["cashBoxTotal"] = 0;
            echo json_encode($info_to_return);
        } else {
            $cashbox = $this->model("cashbox");
            $info = array();
            $info["customer_id"] = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
            $info["payment_method"] = filter_var($payment_method_, FILTER_SANITIZE_NUMBER_INT);
            $info["value"] = filter_var($value_, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $info["vendor_id"] = $_SESSION["id"];
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
            $payments = $this->model("payments");
            if (isset($info["customer_id"]) && 0 < $info["customer_id"] && $info["customer_id"] != "") {
                $payments->add_payment_to_customer($info);
                self::autoCloseInvoices($info["customer_id"]);
            }
            $info_to_return = array();
            if ($_SESSION["role"] == 2) {
                $cashbox->updateCashBox($_SESSION["cashbox_id"]);
                $info_to_return["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
            }
            echo json_encode($info_to_return);
        }
    }
    public function addCustomerPayment($customer_id_, $value_)
    {
        self::giveAccessTo(array(2, 4));
        if ($_SESSION["demo"] == 1) {
            $info_to_return = array();
            $info_to_return["cashBoxTotal"] = 0;
            echo json_encode($info_to_return);
        } else {
            $cashbox = $this->model("cashbox");
            $info = array();
            $info["customer_id"] = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
            $info["value"] = filter_var($value_, FILTER_SANITIZE_NUMBER_FLOAT);
            $info["payment_method"] = filter_var(1, FILTER_SANITIZE_NUMBER_FLOAT);
            $info["vendor_id"] = $_SESSION["id"];
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
            $payments = $this->model("payments");
            $payments->add_payment_to_customer($info);
            self::autoCloseInvoices($info["customer_id"]);
            $info_to_return = array();
            if ($_SESSION["role"] == 2) {
                $cashbox->updateCashBox($_SESSION["cashbox_id"]);
                $info_to_return["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
            }
            echo json_encode($info_to_return);
        }
    }
    public function all_sold_items()
    {
        self::giveAccessTo();
        $this->view("all_sold_items");
    }
    public function updateDiscount($_item_id, $_final_price)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $final_price = filter_var($_final_price, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $invoice->get_item_from_invoice($item_id);
        $final_discount = 100 - $final_price / $item_info[0]["selling_price"] * 100;
        if ($item_info[0]["item_id"] == NULL) {
            $item_name = $item_info[0]["description"];
        } else {
            $item_info_from_db = $items->get_item($item_info[0]["item_id"]);
            $item_name = $item_info_from_db[0]["description"];
        }
        self::createDiscountLogs("Set manual discount by user " . $_SESSION["username"] . " for " . $item_name . "(" . self::value_format_custom($item_info[0]["selling_price"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"] . ") " . self::value_format_custom($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), $this->settings_info) . " --->>> " . self::value_format_custom($final_price, $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
        $invoice->updateDiscount($item_id, $final_discount, $item_info[0]["invoice_id"]);
        $invoice->calculate_total_profit_for_invoice($item_info[0]["invoice_id"]);
        echo json_encode(array());
    }
    public function setofficial($_id, $_action)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $action = filter_var($_action, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $invoice->setofficial($id, $action);
        echo json_encode(array());
    }
    public function set_invoice_official($_id, $_action)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $action = filter_var($_action, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $invoice->set_invoice_official($id, $action);
        echo json_encode(array());
    }
    public function getAllItemsWasSold($_store_id, $date_)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $customers = $this->model("customers");
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
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
        $info = $invoice->getAllItemsWasSold_switch($store_id, $date_range);
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            if ($info[$i]["item_id"] == NULL) {
                if ($info[$i]["custom_item"] == 1) {
                    array_push($tmp, $info[$i]["description"]);
                } else {
                    array_push($tmp, $info[$i]["description"]);
                }
            } else {
                if (array_key_exists($info[$i]["item_id"], $items_info_db)) {
                    $item_info = $items_info_db[$info[$i]["item_id"]];
                } else {
                    $item_info = $items->get_item($info[$i]["item_id"]);
                    $items_info_db[$info[$i]["item_id"]] = $item_info;
                }
                array_push($tmp, $item_info[0]["description"]);
            }
            if ($info[$i]["closed"] == 0 && $info[$i]["customer_id"] != NULL) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . " " . $customer[0]["middle_name"] . " " . $customer[0]["last_name"] . "</span>");
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, (double) $info[$i]["qty"]);
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100), $this->settings_info));
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($info[$i]["final_cost_vat_qty"], $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info));
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"] - $info[$i]["final_cost_vat_qty"], $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, "");
            if ($this->settings_info["set_official"] == 1) {
                array_push($tmp, $info[$i]["official"]);
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function addPaymentForCustomerAdminSide($invoice_id_, $value_)
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $info["invoice_id"] = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
        $info["value"] = filter_var($value_, FILTER_SANITIZE_NUMBER_FLOAT);
        $info["store_id"] = "NULL";
        $info["vendor_id"] = $_SESSION["id"];
        $payments = $this->model("payments");
        $payments->add_payment($info);
        $info_to_return["cashBoxTotal"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $info["store_id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info_to_return);
    }
    public function set_due_date($_invoice_id, $_date)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($_date, self::conversion_php_version_filter());
        $invoice->set_due_date($invoice_id, $date);
        echo json_encode(array());
    }
    public function dismiss_due_date($_invoice_id)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice->dismiss_due_date($invoice_id);
        echo json_encode(array());
    }
    public function getInvoicesMustPay($store_id_)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoices = $invoice->getInvoicesMustPay($store_id);
        $data_array["data"] = array();
        $total_amount = 0;
        $customers = $this->model("customers");
        for ($i = 0; $i < count($invoices); $i++) {
            $tmp = array();
            $customersInfo = NULL;
            if ($invoices[$i]["customer_id"] != NULL && $invoices[$i]["customer_id"] != 0) {
                $customersInfo = $customers->getCustomersById($invoices[$i]["customer_id"]);
            } else {
                $customersInfo = array();
            }
            array_push($tmp, self::idFormat_invoice($invoices[$i]["id"]));
            array_push($tmp, self::idFormat_customer($invoices[$i]["customer_id"]));
            if (0 < count($customersInfo)) {
                array_push($tmp, $customersInfo[0]["name"] . " " . $customersInfo[0]["middle_name"] . " " . $customersInfo[0]["last_name"]);
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, self::date_format_custom($invoices[$i]["creation_date"]));
            $total_amount += $invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"];
            array_push($tmp, self::global_number_formatter($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"], $this->settings_info));
            array_push($tmp, self::date_format_custom($invoices[$i]["due_date"]));
            if ($invoices[$i]["due_date"] < date("Y-m-d H:i:s") && 0 < $invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"]) {
                array_push($tmp, "1");
            } else {
                array_push($tmp, "0");
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        $data_array["total_amount"] = self::global_number_formatter(floatval($total_amount), $this->settings_info);
        echo json_encode($data_array);
    }
    public function getInvoicesOfCustomers($customer_id_)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $store = $this->model("store");
        $payments = $this->model("payments");
        $customer_id = filter_var($customer_id_, FILTER_SANITIZE_NUMBER_INT);
        $invoices = $invoice->getInvoicesOfCustomers($customer_id);
        $data_array["data"] = array();
        $stores_label = array();
        $stores = $store->getStores();
        for ($i = 0; $i < count($stores); $i++) {
            $stores_label[$stores[$i]["id"]] = $stores[$i]["name"];
        }
        for ($i = 0; $i < count($invoices); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_customer($invoices[$i]["customer_id"]));
            array_push($tmp, self::idFormat_invoice($invoices[$i]["id"]));
            array_push($tmp, $invoices[$i]["closed"]);
            array_push($tmp, "-");
            array_push($tmp, $invoices[$i]["creation_date"]);
            array_push($tmp, self::value_format_custom(floatval($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"]), $this->settings_info));
            array_push($tmp, $stores_label[$invoices[$i]["store_id"]]);
            if ($invoices[$i]["closed"] == 1) {
                array_push($tmp, self::value_format_custom(floatval($amount[0]["sum"]), $this->settings_info));
            } else {
                $totalPayments = $payments->getTotalPayments($invoices[$i]["id"]);
                array_push($tmp, self::value_format_custom(floatval($totalPayments[0]["sum"]), $this->settings_info));
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function update_cost_of_custom_item()
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["items_cost"] = filter_input(INPUT_POST, "items_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $invoice->update_cost_of_custom_item($info);
        $item_info = $invoice->get_item_from_invoice($info["id_to_edit"]);
        $invoice->calculate_total_profit_for_invoice($item_info[0]["invoice_id"]);
        $return = array();
        $return["id"] = $info["id_to_edit"];
        echo json_encode($return);
    }
    public function get_item_from_invoice($id_)
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $invoice->get_item_from_invoice($id);
        $info = array();
        $info["buying_cost"] = self::value_format_custom($item_info[0]["buying_cost"], $this->settings_info);
        echo json_encode($info);
    }
    public function getAllPaymetsCustomers($store_id_)
    {
        self::giveAccessTo();
        $customers = $this->model("customers");
        $payments = $this->model("payments");
        $invoice = $this->model("invoice");
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $customersInfo = $customers->getCustomersToPay();
        $data_array["data"] = array();
        for ($i = 0; $i < count($customersInfo); $i++) {
            $balance_info = $payments->getAllBalancePaymentOfCustomer($customersInfo[$i]["id"]);
            $invoices_info = $invoice->getTotalUnpaid($customersInfo[$i]["id"]);
            $tmp = array();
            array_push($tmp, self::idFormat_customer($customersInfo[$i]["id"]));
            array_push($tmp, $customersInfo[$i]["name"] . " - " . $customersInfo[$i]["phone"]);
            array_push($tmp, $customersInfo[$i]["address"]);
            $rem = $invoices_info[0]["sum"] - $customersInfo[$i]["balance"];
            array_push($tmp, self::value_format_custom(round($rem), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getCustomItemsWasSold()
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $info = $invoice->getCustomItemsWasSold();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_INVIT($info[$i]["id"]));
            array_push($tmp, $info[$i]["description"]);
            array_push($tmp, self::value_format_custom($info[$i]["buying_cost"], $this->settings_info));
            array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info));
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function pay_custom_new()
    {
        self::giveAccessTo(array(2, 4));
        if ($_SESSION["demo"] == 1) {
            $return = array();
            $return["inv_id"] = -1;
            $return["cashbox_value"] = 0;
            echo json_encode($return);
        } else {
            $invoice = $this->model("invoice");
            $items = $this->model("items");
            $store = $this->model("store");
            $cashbox = $this->model("cashbox");
            $discounts = $this->model("discounts");
            $settings = $this->model("settings");
            $itemsForInvoice = filter_input(INPUT_POST, "items", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $more_info = filter_input(INPUT_POST, "more_info", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $pay_type = filter_input(INPUT_POST, "pay", FILTER_SANITIZE_STRING);
            $store_id = filter_input(INPUT_POST, "store_id", FILTER_SANITIZE_NUMBER_INT);
            $invoice_discount = filter_input(INPUT_POST, "after_discount", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $discount_note = filter_input(INPUT_POST, "discount_note", FILTER_SANITIZE_STRING);
            if (!isset($more_info[0]["sales_person_id"])) {
                $more_info[0]["sales_person_id"] = 0;
            }
            if (!isset($discount_note)) {
                $discount_note = "";
            }
            $info_payment = array();
            if (isset($more_info[0]["customer_name"]) && $more_info[0]["customer_name"] != "") {
                $info_payment["name"] = $more_info[0]["customer_name"];
                $info_payment["middle_name"] = $more_info[0]["middle_name"];
                $info_payment["last_name"] = $more_info[0]["last_name"];
                $info_payment["customer_id"] = $more_info[0]["customer_id"];
                if (isset($more_info[0]["address"])) {
                    $info_payment["address"] = $more_info[0]["address"];
                } else {
                    $info_payment["address"] = NULL;
                }
                $info_payment["phone"] = NULL;
            }
            if (isset($more_info[0]["phone"]) && $more_info[0]["phone"] != "") {
                $info_payment["phone"] = $more_info[0]["phone"];
            }
            if (!isset($more_info[0]["payment_note"])) {
                $more_info[0]["payment_note"] = "";
            }
            $payment_method = 1;
            switch ($pay_type) {
                case "cc":
                    $payment_method = 3;
                    break;
                case "lp":
                    $payment_method = 1;
                    break;
                case "pc":
                    $payment_method = 2;
                    break;
                case "full":
                    $payment_method = 1;
                    break;
            }
            $invoice_id = $invoice->generateInvoiceId($store_id, $_SESSION["id"], $payment_method, $more_info[0]["payment_note"], $more_info[0]["sales_person_id"], $this->settings_info["vat"]);
            $total_invoice_price = 0;
            $items_ids = "(";
            $items_ids_count = count($itemsForInvoice);
            for ($i = 0; $i < $items_ids_count; $i++) {
                if ($i < $items_ids_count - 1) {
                    if ($itemsForInvoice[$i]["custom_item"] == 0) {
                        $items_ids .= $itemsForInvoice[$i]["id"] . ",";
                    }
                } else {
                    if ($itemsForInvoice[$i]["custom_item"] == 0) {
                        $items_ids .= $itemsForInvoice[$i]["id"] . ")";
                    } else {
                        $items_ids .= "-1)";
                    }
                }
            }
            $items_info = $items->get_items_in($items_ids, $store_id);
            $items_info_d = array();
            for ($i = 0; $i < count($items_info); $i++) {
                $items_info_d[$items_info[$i]["id"]] = $items_info[$i];
            }
            $query = "insert into invoice_items(invoice_id,item_id,qty,buying_cost,vat,selling_price,discount,final_cost_vat_qty,final_price_disc_qty,profit,vat_value,description,mobile_transfer_credits,custom_item,user_role,official)VALUES";
            $query_history_qty = "insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source)VALUES";
            $query_qty_multiple = "";
            $query_qty_multiple_composite = "";
            $num_item_not_custom_and_mobile_transfer_device_id_0 = 0;
            $num_item_not_custom_and_mobile_transfer_device_id_counter = 0;
            for ($i = 0; $i < count($itemsForInvoice); $i++) {
                if ($itemsForInvoice[$i]["custom_item"] == 0 || $itemsForInvoice[$i]["mobile_transfer_device_id"] == "0" && ($itemsForInvoice[$i]["custom_item"] == 1 || $itemsForInvoice[$i]["custom_item"] == 2 || $itemsForInvoice[$i]["custom_item"] == 3)) {
                    $num_item_not_custom_and_mobile_transfer_device_id_0++;
                }
            }
            for ($i = 0; $i < count($itemsForInvoice); $i++) {
                $info = array();
                $info["invoice_id"] = $invoice_id;
                $info["item_id"] = $itemsForInvoice[$i]["id"];
                $info["qty"] = $itemsForInvoice[$i]["qty"];
                $info["custom_item"] = $itemsForInvoice[$i]["custom_item"];
                $info["manual_discounted"] = $itemsForInvoice[$i]["m_d"];
                $info["mobile_transfer_device_id"] = $itemsForInvoice[$i]["mobile_transfer_device_id"];
                if ($info["custom_item"] == 1) {
                    $info["buying_cost"] = 0;
                    $info["final_cost"] = 0;
                    $info["mobile_transfer_item"] = $itemsForInvoice[$i]["mobile_transfer_item"];
                    $info["vat"] = 0;
                    $info["selling_price"] = $itemsForInvoice[$i]["price"];
                    $info["is_composite"] = 0;
                    $info["is_official"] = 0;
                    $info["description"] = "'" . $itemsForInvoice[$i]["description"] . "'";
                    $info["discount"] = 0;
                    $info["item_id"] = "null";
                } else {
                    $info["buying_cost"] = $items_info_d[$info["item_id"]]["buying_cost"];
                    $info["mobile_transfer_item"] = 0;
                    $info["vat"] = $items_info_d[$info["item_id"]]["vat"];
                    $info["selling_price"] = $items_info_d[$info["item_id"]]["selling_price"];
                    $info["is_composite"] = $items_info_d[$info["item_id"]]["is_composite"];
                    if ($info["vat"] == 0) {
                        $info["final_cost"] = $info["buying_cost"] * $info["qty"];
                    } else {
                        $info["final_cost"] = $info["buying_cost"] * $info["qty"];
                    }
                    $info["is_official"] = $items_info_d[$info["item_id"]]["is_official"];
                    $info["description"] = "null";
                    $info["discount"] = $itemsForInvoice[$i]["m_d"];
                }
                $discounts_items = $discounts->get_all_items_under_discounts();
                $discounts_items_ids = array();
                $discounts_items_discount = array();
                for ($k = 0; $k < count($discounts_items); $k++) {
                    $discounts_items_ids[$k] = $discounts_items[$k]["item_id"];
                }
                for ($k = 0; $k < count($discounts_items); $k++) {
                    $discounts_items_discount[$discounts_items[$k]["item_id"]] = $discounts_items[$k]["discount_value"];
                }
                if ($info["manual_discounted"] == 1) {
                    $info["discount"] = $itemsForInvoice[$i]["ds"];
                } else {
                    if ($info["custom_item"] == 0) {
                        $info["discount"] = $items_info_d[$info["item_id"]]["discount"];
                        if (in_array($info["item_id"], $discounts_items_ids)) {
                            $info["discount"] = self::value_format_custom($discounts_items_discount[$info["item_id"]], $this->settings_info);
                        }
                    } else {
                        $info["discount"] = $itemsForInvoice[$i]["ds"];
                    }
                }
                $info["vat_value"] = $this->settings_info["vat"];
                $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"];
                $info["profit"] = $info["final_price"] - $info["final_cost"];
                if ($info["custom_item"] == 0 || $info["mobile_transfer_device_id"] == "0" && ($info["custom_item"] == 1 || $info["custom_item"] == 2 || $info["custom_item"] == 3)) {
                    if ($num_item_not_custom_and_mobile_transfer_device_id_counter == $num_item_not_custom_and_mobile_transfer_device_id_0 - 1) {
                        $query .= "(" . $info["invoice_id"] . "," . $info["item_id"] . "," . $info["qty"] . "," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["final_cost"] . "," . $info["final_price"] . "," . $info["profit"] . "," . $info["vat_value"] . "," . $info["description"] . "," . $info["mobile_transfer_item"] . "," . $info["custom_item"] . "," . $_SESSION["role"] . "," . $info["is_official"] . ");";
                    } else {
                        $query .= "(" . $info["invoice_id"] . "," . $info["item_id"] . "," . $info["qty"] . "," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["final_cost"] . "," . $info["final_price"] . "," . $info["profit"] . "," . $info["vat_value"] . "," . $info["description"] . "," . $info["mobile_transfer_item"] . "," . $info["custom_item"] . "," . $_SESSION["role"] . "," . $info["is_official"] . "),";
                    }
                    $num_item_not_custom_and_mobile_transfer_device_id_counter++;
                }
                if ($info["custom_item"] == 0) {
                    if ($info["is_composite"] == 0) {
                        $query_qty_multiple .= " when item_id = " . $info["item_id"] . " and store_id=" . $store_id . " then quantity-" . $info["qty"] . " ";
                        if ($i == $items_ids_count - 1) {
                            $query_history_qty .= "(" . $_SESSION["id"] . "," . $info["item_id"] . ",now(),-" . $info["qty"] . "," . $store_id . "," . ($items_info_d[$info["item_id"]]["quantity"] - $info["qty"]) . ",'pos');";
                        } else {
                            $query_history_qty .= "(" . $_SESSION["id"] . "," . $info["item_id"] . ",now(),-" . $info["qty"] . "," . $store_id . "," . ($items_info_d[$info["item_id"]]["quantity"] - $info["qty"]) . ",'pos'),";
                        }
                    } else {
                        $composite_items = $items->get_all_composite_of_item($info["item_id"]);
                        for ($kk = 0; $kk < count($composite_items); $kk++) {
                            $query_qty_multiple_composite .= " when item_id = " . $composite_items[$kk]["item_id"] . " and store_id=" . $store_id . " then quantity-" . $composite_items[$kk]["qty"] * $info["qty"] . " ";
                            $qty_ci = $store->getQtyOfItem($store_id, $composite_items[$kk]["item_id"]);
                            if ($i == $items_ids_count - 1) {
                                $query_history_qty .= "(" . $_SESSION["id"] . "," . $composite_items[$kk]["item_id"] . ",now(),-" . $composite_items[$kk]["qty"] * $info["qty"] . "," . $store_id . "," . ($qty_ci[0]["quantity"] - $composite_items[$kk]["qty"] * $info["qty"]) . ",'pos');";
                            } else {
                                $query_history_qty .= "(" . $_SESSION["id"] . "," . $composite_items[$kk]["item_id"] . ",now(),-" . $composite_items[$kk]["qty"] * $info["qty"] . "," . $store_id . "," . ($qty_ci[0]["quantity"] - $composite_items[$kk]["qty"] * $info["qty"]) . ",'pos'),";
                            }
                        }
                    }
                } else {
                    if ($info["mobile_transfer_device_id"] != "0" && ($info["custom_item"] == 1 || $info["custom_item"] == 2 || $info["custom_item"] == 3)) {
                        $mobileStore = $this->model("mobileStore");
                        $info_pkg = $mobileStore->getPackage($info["mobile_transfer_item"]);
                        $info["buying_cost"] = $info_pkg[0]["credit_cost"];
                        $info["final_cost"] = $info_pkg[0]["credit_cost"];
                        $info["profit"] = $info["final_price"] - $info["final_cost"];
                        $info["vat"] = 0;
                        $returned_invoice_item_id = $invoice->addTransferCreditsToInvoice($info);
                        $mobileStore->reduceCredits($info["mobile_transfer_device_id"], $info["mobile_transfer_item"]);
                        $store->reduce_qty_by_pos($store_id, $info_pkg[0]["item_related"], $info["qty"], $_SESSION["id"]);
                        $history_credits = array();
                        $history_credits["invoice_item_id"] = $returned_invoice_item_id;
                        if ($info_pkg[0]["days"] == 0) {
                            $history_credits["qty"] = $info_pkg[0]["qty"];
                        } else {
                            $history_credits["qty"] = 0 - $info_pkg[0]["return_credits"];
                        }
                        if ($info_pkg[0]["no_sms_fees"] == 1 || 0 < $info_pkg[0]["days"]) {
                            $history_credits["sms_fees"] = 0;
                        } else {
                            $history_credits["sms_fees"] = $mobileStore->credits_transfer_sms_cost($info_pkg[0]["operator_id"]);
                        }
                        $history_credits["device_id"] = $info["mobile_transfer_device_id"];
                        $mobileStore->creditsHistory($history_credits);
                    }
                }
            }
            $query_qty = "UPDATE store_items SET quantity = CASE " . $query_qty_multiple . " ELSE quantity END;";
            $query_qty_composite = "UPDATE store_items SET quantity = CASE " . $query_qty_multiple_composite . " ELSE quantity END;";
            $store->one_query($query_qty);
            if (0 < strlen($query_qty_multiple_composite)) {
                $store->one_query($query_qty_composite);
            }
            $store->one_query($query_history_qty);
            $invoice->addAllItemsToInvoice($query);
            $customer_id = NULL;
            if (0 < count($info_payment)) {
                $info_payment["starting_balance"] = 0;
                $info_payment["customer_discount"] = 0;
                $info_payment["customer_mof"] = "";
                $customer = $this->model("customers");
                if (0 < $info_payment["customer_id"]) {
                    $customer_id = $info_payment["customer_id"];
                } else {
                    $info_payment["customer_type"] = 1;
                    $customer_id = $customer->addCustomer($info_payment);
                }
                if (0 < $customer_id) {
                    $invoice->updateCustomerInvoice($invoice_id, $customer_id);
                }
            }
            $total_value = $invoice->calculate_total_value_with_vat($invoice_id);
            $payment_method = 1;
            switch ($pay_type) {
                case "lp":
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    break;
                case "cc":
                    $invoice->closeInvoice($invoice_id);
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    break;
                case "pc":
                    $invoice->closeInvoice($invoice_id);
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    break;
                case "full":
                    $invoice->closeInvoice($invoice_id);
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    $cashbox->updateCashBox($_SESSION["cashbox_id"]);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    break;
            }
            $invoice->calculate_total_cost_price_for_invoice($invoice_id);
            $invoice->calculate_total_profit_for_invoice($invoice_id);
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
            $settings->update_value("1", "auto_update_items_qty_in_admin");
            $return = array();
            $return["inv_id"] = $invoice_id;
            $return["cashbox_value"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
            if ($this->settings_info["garage_car_plugin"] == "1" && $customer_id != NULL) {
                $garage = $this->model("garage");
                $info_garage = array();
                if ($customer_id == NULL) {
                    $info_garage["customers_list"] = 0;
                } else {
                    $info_garage["customers_list"] = $customer_id;
                }
                $info_garage["date_in"] = date("Y-m-d H:i:s");
                $info_garage["code"] = "";
                $info_garage["company"] = "";
                $info_garage["car_type"] = "";
                $info_garage["car_model"] = "";
                $info_garage["car_color"] = "1";
                $info_garage["car_odometer"] = "";
                $info_garage["car_c"] = "";
                $info_garage["date_out"] = date("Y-m-d H:i:s");
                $info_garage["problem_description"] = "";
                $info_garage["card_invoice"] = $invoice_id;
                $info_garage["oil_changed_date"] = "NULL";
                $info_garage["oil_next_change_date"] = "NULL";
                $info_garage["oil_note"] = "";
                $last_id = $garage->add_new_card($info_garage);
                $return["card_id"] = $last_id;
            }
            echo json_encode($return);
        }
    }
    public function pay_pos_6()
    {
        self::giveAccessTo(array(2, 4));
        if ($_SESSION["demo"] == 1) {
            $return = array();
            $return["inv_id"] = -1;
            $return["cashbox_value"] = 0;
            echo json_encode($return);
        } else {
            $invoice = $this->model("invoice");
            $items = $this->model("items");
            $store = $this->model("store");
            $cashbox = $this->model("cashbox");
            $discounts = $this->model("discounts");
            $settings = $this->model("settings");
            $customers_class = $this->model("customers");
            $itemsForInvoice = filter_input(INPUT_POST, "items", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $more_info = filter_input(INPUT_POST, "more_info", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $pay_type = filter_input(INPUT_POST, "pay", FILTER_SANITIZE_STRING);
            $current_recalled_invoice = filter_input(INPUT_POST, "current_recalled_invoice", FILTER_SANITIZE_STRING);
            $store_id = filter_input(INPUT_POST, "store_id", FILTER_SANITIZE_NUMBER_INT);
            $cash = filter_input(INPUT_POST, "cash", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $invoice_discount = filter_input(INPUT_POST, "after_discount", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $cash_from_client = 0;
            $discount_note = filter_input(INPUT_POST, "discount_note", FILTER_SANITIZE_STRING);
            $delivery = filter_input(INPUT_POST, "delivery", FILTER_SANITIZE_NUMBER_INT);
            $delivery_cost = filter_input(INPUT_POST, "delivery_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $delivery_ref = filter_input(INPUT_POST, "delivery_ref", FILTER_SANITIZE_STRING);
            $on_account = filter_input(INPUT_POST, "on_account", FILTER_SANITIZE_NUMBER_INT);
            $invoice_freight = filter_input(INPUT_POST, "freight", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $invoice_taxes = filter_input(INPUT_POST, "tax", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if (!isset($invoice_freight)) {
                $invoice_freight = 0;
            }
            if (!isset($invoice_taxes)) {
                $invoice_taxes = 0;
            }
            if (!isset($more_info[0]["cus_referrer"])) {
                $more_info[0]["cus_referrer"] = 0;
            }
            if (!isset($more_info[0]["to_second_currency_rate"])) {
                $more_info[0]["to_second_currency_rate"] = 0;
            }
            if (!isset($more_info[0]["sales_person_id"])) {
                $more_info[0]["sales_person_id"] = 0;
            }
            if (!isset($discount_note)) {
                $discount_note = "";
            }
            $info_payment = array();
            if (isset($more_info[0]["customer_name"]) && $more_info[0]["customer_name"] != "") {
                $info_payment["name"] = $more_info[0]["customer_name"];
                $info_payment["middle_name"] = $more_info[0]["middle_name"];
                $info_payment["last_name"] = $more_info[0]["last_name"];
                $info_payment["customer_id"] = $more_info[0]["customer_id"];
                if (isset($more_info[0]["address"])) {
                    $info_payment["address"] = $more_info[0]["address"];
                } else {
                    $info_payment["address"] = NULL;
                }
                $info_payment["phone"] = NULL;
            }
            if (isset($more_info[0]["phone"]) && $more_info[0]["phone"] != "") {
                $info_payment["phone"] = $more_info[0]["phone"];
            }
            if (!isset($more_info[0]["payment_note"])) {
                $more_info[0]["payment_note"] = "";
            }
            $payment_method = 1;
            switch ($pay_type) {
                case "cc":
                    $payment_method = 3;
                    break;
                case "lp":
                    $payment_method = 1;
                    break;
                case "pc":
                    $payment_method = 2;
                    break;
                case "full":
                    $payment_method = 1;
                    break;
            }
            $invoice_id = $invoice->generateInvoiceId($store_id, $_SESSION["id"], $payment_method, $more_info[0]["payment_note"], $more_info[0]["sales_person_id"], $this->settings_info["vat"], $more_info[0]["cus_referrer"]);
            //echo($invoice_id);
            if (0 < $invoice_taxes) {
                $invoice->update_official_nb($invoice_id);
            }
            if ($delivery == 1) {
                $invoice->update_delivery_pos($invoice_id, $delivery_cost, $delivery_ref);
            }
            if (0 < $invoice_freight || 0 < $invoice_taxes) {
                $invoice->update_invoice_more_invoice($invoice_id, $invoice_freight, $invoice_taxes);
            }
            $total_invoice_price = 0;
            $items_ids = "(";
            $items_ids_count = count($itemsForInvoice);
            for ($i = 0; $i < $items_ids_count; $i++) {
                if ($i < $items_ids_count - 1) {
                    if ($itemsForInvoice[$i]["custom_item"] == 0) {
                        $items_ids .= $itemsForInvoice[$i]["id"] . ",";
                    }
                } else {
                    if ($itemsForInvoice[$i]["custom_item"] == 0) {
                        $items_ids .= $itemsForInvoice[$i]["id"] . ")";
                    } else {
                        $items_ids .= "-1)";
                    }
                }
            }
            $items_info = $items->get_items_in($items_ids, $store_id);
            $items_info_d = array();
            $items_by_composer = array();
            for ($i = 0; $i < count($items_info); $i++) {
                $items_info_d[$items_info[$i]["id"]] = $items_info[$i];
            }
            $query = "insert into invoice_items(invoice_id,item_id,qty,buying_cost,vat,selling_price,discount,final_cost_vat_qty,final_price_disc_qty,profit,vat_value,description,mobile_transfer_credits,custom_item,user_role,official,pos_discounted,international_calls,base_usd_price,average_rate)VALUES";
            $query_history_qty = "insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source)VALUES";
            $query_qty_multiple = "";
            $query_qty_multiple_composite = "";
            $num_item_not_custom_and_mobile_transfer_device_id_0 = 0;
            $num_item_not_custom_and_mobile_transfer_device_id_counter = 0;
            for ($i = 0; $i < count($itemsForInvoice); $i++) {
                if ($itemsForInvoice[$i]["custom_item"] == 0 || $itemsForInvoice[$i]["mobile_transfer_device_id"] == "0" && ($itemsForInvoice[$i]["custom_item"] == 1 || $itemsForInvoice[$i]["custom_item"] == 2 || $itemsForInvoice[$i]["custom_item"] == 3)) {
                    $num_item_not_custom_and_mobile_transfer_device_id_0++;
                }
            }
            $check_if_codes_exist_for_items = array();
            for ($i = 0; $i < count($itemsForInvoice); $i++) {
                if (0 < $items_info_d[$itemsForInvoice[$i]["id"]]["complex_item_id"]) {
                    $tmp = array();
                    $tmp["item_id"] = $itemsForInvoice[$i]["id"];
                    $tmp["item_qty"] = $itemsForInvoice[$i]["qty"];
                    array_push($items_by_composer, $tmp);
                }
                $info = array();
                $info["invoice_id"] = $invoice_id;
                $info["item_id"] = $itemsForInvoice[$i]["id"];
                $info["qty"] = $itemsForInvoice[$i]["qty"];
                $info["custom_item"] = $itemsForInvoice[$i]["custom_item"];
                $info["international_calls"] = $itemsForInvoice[$i]["international_calls"];
                $info["base_usd_price"] = $itemsForInvoice[$i]["base_usd_price"];
                if (0 < $info["international_calls"]) {
                    $query___ = "update settings set value=value-" . $info["base_usd_price"] . " where name='international_calls_balance'";
                    my_sql::query($query___);
                }
                $info["manual_discounted"] = $itemsForInvoice[$i]["m_d"];
                $info["mobile_transfer_device_id"] = $itemsForInvoice[$i]["mobile_transfer_device_id"];
                if ($info["custom_item"] == 1) {
                    $info["buying_cost"] = 0;
                    $info["final_cost"] = 0;
                    if (isset($itemsForInvoice[$i]["cost"])) {
                        $info["buying_cost"] = $itemsForInvoice[$i]["cost"];
                        $info["final_cost"] = $info["buying_cost"];
                    }
                    $info["mobile_transfer_item"] = $itemsForInvoice[$i]["mobile_transfer_item"];
                    $info["vat"] = 0;
                    $info["selling_price"] = $itemsForInvoice[$i]["price"];
                    $info["is_composite"] = 0;
                    $info["is_official"] = 0;
                    $info["description"] = "'" . $itemsForInvoice[$i]["description"] . "'";
                    $info["discount"] = 0;
                    $info["item_id"] = "null";
                } else {
                    $info["buying_cost"] = $items_info_d[$info["item_id"]]["buying_cost"];
                    $info["mobile_transfer_item"] = 0;
                    $info["vat"] = $items_info_d[$info["item_id"]]["vat"];
                    $info["selling_price"] = $itemsForInvoice[$i]["price"];
                    $info["is_composite"] = $items_info_d[$info["item_id"]]["is_composite"];
                    if ($info["vat"] == 0) {
                        $info["final_cost"] = $info["buying_cost"] * $info["qty"];
                    } else {
                        $info["final_cost"] = $info["buying_cost"] * $info["qty"];
                    }
                    $info["is_official"] = $items_info_d[$info["item_id"]]["is_official"];
                    $info["description"] = "null";
                    $info["discount"] = $itemsForInvoice[$i]["m_d"];
                }
                $discounts_items = $discounts->get_all_items_under_discounts();
                $discounts_items_ids = array();
                $discounts_items_discount = array();
                for ($k = 0; $k < count($discounts_items); $k++) {
                    $discounts_items_ids[$k] = $discounts_items[$k]["item_id"];
                }
                for ($k = 0; $k < count($discounts_items); $k++) {
                    $discounts_items_discount[$discounts_items[$k]["item_id"]] = $discounts_items[$k]["discount_value"];
                }
                if ($info["manual_discounted"] == 1) {
                    $info["discount"] = $itemsForInvoice[$i]["ds"];
                } else {
                    if ($info["custom_item"] == 0) {
                        $info["discount"] = $items_info_d[$info["item_id"]]["discount"];
                        if (in_array($info["item_id"], $discounts_items_ids)) {
                            $info["discount"] = $discounts_items_discount[$info["item_id"]];
                        }
                    } else {
                        $info["discount"] = $itemsForInvoice[$i]["ds"];
                    }
                }
                $info["vat_value"] = $this->settings_info["vat"];
                if (0 <= $info["discount"]) {
                    $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"];
                } else {
                    $info["final_price"] = $info["selling_price"] * $info["qty"];
                }
                $info["profit"] = $info["final_price"] - $info["final_cost"];
                if ($info["custom_item"] == 0 || $info["mobile_transfer_device_id"] == "0" && ($info["custom_item"] == 1 || $info["custom_item"] == 2 || $info["custom_item"] == 3)) {
                    if ($num_item_not_custom_and_mobile_transfer_device_id_counter == $num_item_not_custom_and_mobile_transfer_device_id_0 - 1) {
                        $query .= "(" . $info["invoice_id"] . "," . $info["item_id"] . "," . $info["qty"] . "," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["final_cost"] . "," . $info["final_price"] . "," . $info["profit"] . "," . $info["vat_value"] . "," . $info["description"] . "," . $info["mobile_transfer_item"] . "," . $info["custom_item"] . "," . $_SESSION["role"] . "," . $info["is_official"] . "," . $info["manual_discounted"] . "," . $info["international_calls"] . "," . $info["base_usd_price"] . "," . $this->settings_info["international_calls_source_rate"] . ");";
                    } else {
                        $query .= "(" . $info["invoice_id"] . "," . $info["item_id"] . "," . $info["qty"] . "," . $info["buying_cost"] . "," . $info["vat"] . "," . $info["selling_price"] . "," . $info["discount"] . "," . $info["final_cost"] . "," . $info["final_price"] . "," . $info["profit"] . "," . $info["vat_value"] . "," . $info["description"] . "," . $info["mobile_transfer_item"] . "," . $info["custom_item"] . "," . $_SESSION["role"] . "," . $info["is_official"] . "," . $info["manual_discounted"] . "," . $info["international_calls"] . "," . $info["base_usd_price"] . "," . $this->settings_info["international_calls_source_rate"] . "),";
                    }
                    $num_item_not_custom_and_mobile_transfer_device_id_counter++;
                }
                if ($info["custom_item"] == 0) {
                    array_push($check_if_codes_exist_for_items, array("item_id" => $info["item_id"], "qty" => $info["qty"]));
                    if ($info["is_composite"] == 0) {
                        $query_qty_multiple .= " when item_id = " . $info["item_id"] . " and store_id=" . $store_id . " then quantity-" . $info["qty"] . " ";
                        if ($i == $items_ids_count - 1) {
                            $query_history_qty .= "(" . $_SESSION["id"] . "," . $info["item_id"] . ",'" . my_sql::datetime_now() . "',-" . $info["qty"] . "," . $store_id . "," . ($items_info_d[$info["item_id"]]["quantity"] - $info["qty"]) . ",'pos');";
                        } else {
                            $query_history_qty .= "(" . $_SESSION["id"] . "," . $info["item_id"] . ",'" . my_sql::datetime_now() . "',-" . $info["qty"] . "," . $store_id . "," . ($items_info_d[$info["item_id"]]["quantity"] - $info["qty"]) . ",'pos'),";
                        }
                    } else {
                        $composite_items = $items->get_all_composite_of_item($info["item_id"]);
                        for ($kk = 0; $kk < count($composite_items); $kk++) {
                            if ($composite_items[$kk]["is_pack"] == 1) {
                                $query_qty_multiple_composite = " when item_id = " . $composite_items[$kk]["composite_item_id"] . " and store_id=" . $store_id . " then packs_nb-" . $info["qty"] . " ";
                                $query_qty_composite = "UPDATE store_items SET packs_nb = CASE " . $query_qty_multiple_composite . " ELSE packs_nb END;";
                                $store->one_query($query_qty_composite);
                            } else {
                                $query_qty_multiple_composite = " when item_id = " . $composite_items[$kk]["item_id"] . " and store_id=" . $store_id . " then quantity-" . $composite_items[$kk]["qty"] * $info["qty"] . " ";
                                $query_qty_composite = "UPDATE store_items SET quantity = CASE " . $query_qty_multiple_composite . " ELSE quantity END;";
                                $store->one_query($query_qty_composite);
                            }
                            if ($composite_items[$kk]["is_pack"] == 1) {
                                $qty_ci = $store->getQtyOfItem($store_id, $composite_items[$kk]["composite_item_id"]);
                                if ($i == $items_ids_count - 1) {
                                    $query_history_qty .= "(" . $_SESSION["id"] . "," . $composite_items[$kk]["composite_item_id"] . ",'" . my_sql::datetime_now() . "',-" . $info["qty"] . "," . $store_id . "," . $qty_ci[0]["quantity"] . ",'pos');";
                                } else {
                                    $query_history_qty .= "(" . $_SESSION["id"] . "," . $composite_items[$kk]["composite_item_id"] . ",'" . my_sql::datetime_now() . "',-" . $info["qty"] . "," . $store_id . "," . $qty_ci[0]["quantity"] . ",'pos'),";
                                }
                            } else {
                                $qty_ci = $store->getQtyOfItem($store_id, $composite_items[$kk]["item_id"]);
                                if ($i == $items_ids_count - 1) {
                                    $query_history_qty .= "(" . $_SESSION["id"] . "," . $composite_items[$kk]["item_id"] . ",'" . my_sql::datetime_now() . "',-" . $composite_items[$kk]["qty"] * $info["qty"] . "," . $store_id . "," . $qty_ci[0]["quantity"] . ",'pos');";
                                } else {
                                    $query_history_qty .= "(" . $_SESSION["id"] . "," . $composite_items[$kk]["item_id"] . ",'" . my_sql::datetime_now() . "',-" . $composite_items[$kk]["qty"] * $info["qty"] . "," . $store_id . "," . $qty_ci[0]["quantity"] . ",'pos'),";
                                }
                            }
                        }
                    }
                } else {
                    if ($info["mobile_transfer_device_id"] != "0" && ($info["custom_item"] == 1 || $info["custom_item"] == 2 || $info["custom_item"] == 3)) {
                        $mobileStore = $this->model("mobileStore");
                        $info_pkg = $mobileStore->getPackage($info["mobile_transfer_item"]);
                        $info["buying_cost"] = $info_pkg[0]["credit_cost"];
                        $info["final_cost"] = $info_pkg[0]["credit_cost"];
                        $info["profit"] = $info["final_price"] - $info["final_cost"];
                        $info["vat"] = 0;
                        $returned_invoice_item_id = $invoice->addTransferCreditsToInvoice($info);
                        $mobileStore->reduceCredits($info["mobile_transfer_device_id"], $info["mobile_transfer_item"]);
                        $item__ = $items->get_item($info_pkg[0]["item_related"]);
                        if (0 < count($item__)) {
                            if ($item__[0]["is_composite"]) {
                                $all_composite_of_item = $items->get_all_composite_of_item($info_pkg[0]["item_related"]);
                                $store_info["qty"] = $all_composite_of_item[0]["qty"];
                                $store_info["item_id"] = $all_composite_of_item[0]["item_id"];
                                $store->reduce_qty_by_pos($store_id, $store_info["item_id"], $store_info["qty"], $_SESSION["id"]);
                            } else {
                                $store->reduce_qty_by_pos($store_id, $info_pkg[0]["item_related"], $info["qty"], $_SESSION["id"]);
                            }
                        }
                        $history_credits = array();
                        $history_credits["invoice_item_id"] = $returned_invoice_item_id;
                        if ($info_pkg[0]["days"] == 0) {
                            $history_credits["qty"] = $info_pkg[0]["qty"];
                        } else {
                            $history_credits["qty"] = 0 - $info_pkg[0]["return_credits"];
                        }
                        if ($info_pkg[0]["no_sms_fees"] == 1 || 0 < $info_pkg[0]["days"]) {
                            $history_credits["sms_fees"] = 0;
                        } else {
                            $history_credits["sms_fees"] = $mobileStore->credits_transfer_sms_cost($info_pkg[0]["operator_id"]);
                        }
                        $history_credits["device_id"] = $info["mobile_transfer_device_id"];
                        $mobileStore->creditsHistory($history_credits);
                    }
                }
            }
            $query_qty = "UPDATE store_items SET quantity = CASE " . $query_qty_multiple . " ELSE quantity END;";
            if (0 < strlen($query_qty_multiple)) {
                $store->one_query($query_qty);
            }
            $store->one_query($query_history_qty);
            $invoice->addAllItemsToInvoice($query);
            for ($i = 0; $i < count($items_by_composer); $i++) {
                $store->reduce_qty_of_composite($items_by_composer[$i]);
            }
            $customer_id = NULL;
            if (0 < count($info_payment)) {
                $info_payment["starting_balance"] = 0;
                $info_payment["customer_discount"] = 0;
                $info_payment["customer_mof"] = "";
                $info_payment["city_id"] = 0;
                $info_payment["dob"] = "";
                $info_payment["id_type"] = 0;
                $info_payment["id_expiry"] = "";
                $info_payment["id_nb"] = "";
                $info_payment["cob"] = 0;
                $info_payment["coi"] = 0;
                $info_payment["account_nb"] = 0;
                $info_payment["note"] = "";
                $info_payment["reference_id"] = 0;
                $customer = $this->model("customers");
                if (0 < $info_payment["customer_id"]) {
                    $customer_id = $info_payment["customer_id"];
                } else {
                    $info_payment["customer_type"] = 1;
                }
                if (0 < $customer_id) {
                    $invoice->updateCustomerInvoice($invoice_id, $customer_id);
                }
            }
            $total_value = $invoice->calculate_total_value_with_vat($invoice_id);
            $payment_method = 1;
            switch ($pay_type) {
                case "lp":
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    if ($this->settings_info["cash_from_client_as_first_payment"] == 1 && isset($cash_from_client)) {
                        $info = array();
                        $info["customer_id"] = $customer_id;
                        $info["payment_method"] = 1;
                        $info["value"] = $cash_from_client;
                        $info["note"] = "";
                        $info["bank_id"] = 0;
                        $info["reference_nb"] = "";
                        $info["owner"] = "";
                        $info["voucher"] = "";
                        $info["picture"] = "";
                        $info["vendor_id"] = $_SESSION["id"];
                        $info["value_date"] = "";
                        $info["currency_id"] = 2;
                        $info["rate"] = 1;
                        $info["store_id"] = $_SESSION["store_id"];
                        $info["cashbox_id"] = $_SESSION["cashbox_id"];
                        sleep(1);
                        $payments->add_payment_to_customer($info);
                    }
                    break;
                case "cc":
                    $invoice->closeInvoice($invoice_id);
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    break;
                case "pc":
                    $invoice->closeInvoice($invoice_id);
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    break;
                case "full":
                    $invoice->closeInvoice($invoice_id);
                    $payments = $this->model("payments");
                    $total_amount = $invoice->getAmount($invoice_id);
                    $total_amount_vat_diff = $invoice->getAmountVatDiff($invoice_id);
                    $info_payment["invoice_id"] = $invoice_id;
                    $info_payment["value"] = $total_amount[0]["sum"] + $total_amount_vat_diff[0]["sum"];
                    $info_payment["store_id"] = $store_id;
                    $info_payment["vendor_id"] = $_SESSION["id"];
                    $payments->add_payment($info_payment);
                    $cashbox->updateCashBox($_SESSION["cashbox_id"]);
                    if ($invoice_discount != -1) {
                        $invoice->addDiscount($invoice_id, $invoice_discount - $total_value, $discount_note);
                    }
                    break;
            }
            $invoice->calculate_total_cost_price_for_invoice($invoice_id);
            $invoice->calculate_total_profit_for_invoice($invoice_id);
            if ($this->settings_info["enable_customers_referrer"] == "1") {
                $invoice->calculate_cashback_value($invoice_id, $this->settings_info);
            }
            $cashbox->updateCashBox($_SESSION["cashbox_id"]);
            $settings->update_value("1", "auto_update_items_qty_in_admin");
            $return = array();
            $return["inv_id"] = $invoice_id;
            $return["cashbox_value"] = self::value_format_custom($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
            $gara_card_id = filter_input(INPUT_POST, "gara_card_id", FILTER_SANITIZE_NUMBER_INT);
            if ($this->settings_info["garage_car_plugin"] == "1" && isset($gara_card_id) && 0 < $gara_card_id) {
                $garage = $this->model("garage");
                $garage->assign_card_to_invoice($invoice_id, $gara_card_id);
                $return["card_id"] = $gara_card_id;
            }
            if (0 < $more_info[0]["to_second_currency_rate"]) {
                $invoice->update_second_currency_rate($invoice_id, $more_info[0]["to_second_currency_rate"]);
            }
            if (0 < $info_payment["customer_id"]) {
                $customers_class->bal_need_update($info_payment["customer_id"]);
            }
            $cash_info = array();
            $cash_info["cash_lbp"] = $cash[0]["cash_lbp"];
            $cash_info["cash_usd"] = $cash[0]["cash_usd"];
            if ($pay_type == "lp") {
                $cash_info["cash_lbp"] = 0;
                $cash_info["cash_usd"] = 0;
            }
            $cash_info["rate"] = $more_info[0]["to_second_currency_rate"];
            $cash_info["invoice_id"] = $invoice_id;
            $cash_info["base_amount"] = $invoice_discount;
            $cash_info["cashbox_id"] = $_SESSION["cashbox_id"];
            $cash_info["returned_cash_lbp"] = $cash[0]["returned_cash_lbp"];
            $cash_info["returned_cash_usd"] = $cash[0]["returned_cash_usd"];
            $cash_info["must_return_cash_lbp"] = $cash[0]["must_return_cash_lbp"];
            $cash_info["must_return_cash_usd"] = $cash[0]["must_return_cash_usd"];
            if ($cash[0]["cash_lbp"] + $cash[0]["cash_usd"] == 0) {
            }
            $invoice->add_cash_details($cash_info);
            $cashbox_info = self::_get_full_report_table(2, $_SESSION["cashbox_id"]);
            $invoice->update_cashinfo_for_invoice($invoice_id, $cashbox_info);
            $return["assign_codes"] = array();
            $_SESSION["force_assign_code"] = 0;
            if (0 < count($check_if_codes_exist_for_items)) {
                $uniqueItems = $this->model("uniqueItems");
                $unique_result = $uniqueItems->check_if_have_codes($check_if_codes_exist_for_items);
                if (0 < count($unique_result)) {
                    $return["assign_codes"] = $unique_result;
                    $return["description"] .= "<b>Add phone code for the following phone(s)</b><br/>";
                    for ($i = 0; $i < count($return["assign_codes"]); $i++) {
                        $return["description"] .= "<span style='font-size:20px;'>" . $return["assign_codes"][$i]["item_id_qty"] . " of " . $items_info_d[$return["assign_codes"][$i]["item_id"]]["description"] . "'</span><br/>";
                    }
                    $_SESSION["force_assign_code"] = 1;
                    $_SESSION["assign_codes"] = $unique_result;
                    $_SESSION["description"] = $return["description"];
                    $_SESSION["customer_id"] = $customer_id;
                    $_SESSION["inv_id"] = $invoice_id;
                }
            }
            if ($on_account == 1) {
                $on_account_info = array();
                $on_account_info["id_to_edit"] = 0;
                $on_account_info["customer_id"] = $customer_id;
                $on_account_info["invoice_id"] = $invoice_id;
                $on_account_info["quotation_id"] = 0;
                if (0 < $more_info[0]["to_second_currency_rate"]) {
                    $on_account_info["value"] = $cash[0]["cash_usd"] + $cash[0]["cash_lbp"] / $more_info[0]["to_second_currency_rate"];
                } else {
                    $on_account_info["value"] = $cash[0]["cash_usd"];
                }
                $on_account_info["note"] = "On Account";
                $on_account_info["payment_method"] = 4;
                $on_account_info["creation_date"] = date("Y-m-d");
                $on_account_info["bank_id"] = 0;
                $on_account_info["reference_nb"] = "";
                $on_account_info["owner"] = "";
                $on_account_info["voucher"] = "";
                $on_account_info["picture"] = "";
                $on_account_info["vendor_id"] = $_SESSION["id"];
                $on_account_info["currency_id"] = 1;
                $on_account_info["rate_value"] = $more_info[0]["to_second_currency_rate"];
                $on_account_info["rate"] = 1;
                $on_account_info["p_rate"] = $more_info[0]["to_second_currency_rate"];
                $on_account_info["store_id"] = $_SESSION["store_id"];
                $on_account_info["cashbox_id"] = $_SESSION["cashbox_id"];
                $on_account_info["cash_in_usd"] = $cash[0]["cash_usd"];
                $on_account_info["cash_in_lbp"] = $cash[0]["cash_lbp"];
                $on_account_info["returned_usd"] = 0;
                $on_account_info["returned_lbp"] = 0;
                $on_account_info["to_returned_usd"] = 0;
                $on_account_info["to_returned_lbp"] = 0;
                $payments->add_payment_to_customer_new($on_account_info);
            }
            if (isset($current_recalled_invoice)) {
                $pendingInvoices = $this->model("pendingInvoices");
                $pendingInvoices->delete($current_recalled_invoice);
            }
            echo json_encode($return);
        }
    }
    public function delete_item_from_manual_invoice($invoice_item_id)
    {
        $invoice = $this->model("invoice");
        $store = $this->model("store");
        $items = $this->model("items");
        $old_info = $invoice->get_item_from_invoice($invoice_item_id);
        $old_qty = $old_info[0]["qty"];
        $invoice->delete_item_from_manual_invoice($invoice_item_id);
        $item_info = $items->get_item($old_info[0]["item_id"]);
        if ($item_info[0]["is_composite"] == 0) {
            $info_add_qty["qty"] = $old_qty;
            $info_add_qty["item_id"] = $old_info[0]["item_id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "soldbyadmin-" . $old_info[0]["invoice_id"];
            $store->add_qty($info_add_qty);
        } else {
            $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
            for ($kk = 0; $kk < count($composite_items); $kk++) {
                $info_add_qty = array();
                $info_add_qty["qty"] = $old_qty * $composite_items[$kk]["qty"];
                $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "soldbyadmin-" . $old_info[0]["invoice_id"];
                $store->add_qty($info_add_qty);
            }
        }
        if (0 < $item_info[0]["complex_item_id"]) {
            $tmp = array();
            $tmp["item_id"] = $item_info[0]["id"];
            $tmp["item_qty"] = 0 - abs($old_qty);
            $store->reduce_qty_of_composite($tmp);
        }
        $total_value = $invoice->calculate_total_value_with_vat($old_info[0]["invoice_id"]);
        $invoice->calculate_total_cost_price_for_invoice($old_info[0]["invoice_id"]);
        $invoice->calculate_total_profit_for_invoice($old_info[0]["invoice_id"]);
        $invoice->calculate_total_value($old_info[0]["invoice_id"]);
        echo json_encode(array());
    }
    public function set_as_taxable_invoice($invoice_id)
    {
        $invoice = $this->model("invoice");
        $invoice->update_official_nb($invoice_id);
        $invoice->update_official_tax_value($invoice_id);
        echo json_encode(array($this->settings_info["vat"]));
    }
    public function save_manual_invoice_items($invoice_item_id, $price, $discount, $vat, $qty, $invoice_id, $rate)
    {
        $invoice = $this->model("invoice");
        $store = $this->model("store");
        $items = $this->model("items");
        $old_info = $invoice->get_item_from_invoice($invoice_item_id);
        $old_qty = $old_info[0]["qty"];
        $new_qty = $qty;
        $item_info = $items->get_item($old_info[0]["item_id"]);
        if ($old_qty < $new_qty) {
            if ($item_info[0]["is_composite"] == 0) {
                $info_add_qty = array();
                $info_add_qty["qty"] = 0 - ($new_qty - $old_qty);
                $info_add_qty["item_id"] = $old_info[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "soldbyadmin-" . $old_info[0]["invoice_id"];
                $store->add_qty($info_add_qty);
            } else {
                $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
                for ($kk = 0; $kk < count($composite_items); $kk++) {
                    $info_add_qty = array();
                    $info_add_qty["qty"] = 0 - ($new_qty - $old_qty) * $composite_items[$kk]["qty"];
                    $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                    $info_add_qty["store_id"] = $_SESSION["store_id"];
                    $info_add_qty["source"] = "soldbyadmin-" . $old_info[0]["invoice_id"];
                    $store->add_qty($info_add_qty);
                }
            }
            if (0 < $item_info[0]["complex_item_id"]) {
                $tmp = array();
                $tmp["item_id"] = $item_info[0]["id"];
                $tmp["item_qty"] = $new_qty - $old_qty;
                $store->reduce_qty_of_composite($tmp);
            }
        }
        if ($new_qty < $old_qty) {
            if ($item_info[0]["is_composite"] == 0) {
                $info_add_qty = array();
                $info_add_qty["qty"] = $old_qty - $new_qty;
                $info_add_qty["item_id"] = $old_info[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "soldbyadmin-" . $old_info[0]["invoice_id"];
                $store->add_qty($info_add_qty);
            } else {
                $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
                for ($kk = 0; $kk < count($composite_items); $kk++) {
                    $info_add_qty = array();
                    $info_add_qty["qty"] = ($old_qty - $new_qty) * $composite_items[$kk]["qty"];
                    $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                    $info_add_qty["store_id"] = $_SESSION["store_id"];
                    $info_add_qty["source"] = "soldbyadmin-" . $old_info[0]["invoice_id"];
                    $store->add_qty($info_add_qty);
                }
            }
            if (0 < $item_info[0]["complex_item_id"]) {
                $tmp = array();
                $tmp["item_id"] = $item_info[0]["id"];
                $tmp["item_qty"] = 0 - abs($new_qty - $old_qty);
                $store->reduce_qty_of_composite($tmp);
            }
        }
        $invoice->save_manual_invoice_items($invoice_item_id, $price, $discount, $vat, $qty, $rate);
        $invoice->calculate_total_value_with_vat($invoice_id);
        $invoice->calculate_total_cost_price_for_invoice($invoice_id);
        $invoice->calculate_total_profit_for_invoice($invoice_id);
        $invoice->calculate_total_value($invoice_id);
        echo json_encode($invoice->get_item_from_invoice($invoice_item_id));
    }
    public function recurring_update($invoice_id, $recurring_id)
    {
        $invoice = $this->model("invoice");
        $invoice->recurring_update($invoice_id, $recurring_id);
        echo json_encode(array());
    }
    public function save_manual_invoice($invoice_id, $customer_id, $paid, $invoice_discount, $_salesman, $_invoice_note, $rate, $invoice_date, $change_date)
    {
        $invoice = $this->model("invoice");
        $invoice_note = filter_var($_invoice_note, self::conversion_php_version_filter());
        $salesman = 0;
        if (0 < strlen($_salesman) && $_salesman != "null") {
            $salesman = filter_var($_salesman, FILTER_SANITIZE_NUMBER_INT);
        }
        $invoice->update_invoice_info_manual($invoice_id, $invoice_discount, $invoice_note, $salesman, $rate, $invoice_date, $paid, $change_date);
        $invoice->calculate_total_value_with_vat($invoice_id);
        $invoice->calculate_total_cost_price_for_invoice($invoice_id);
        $invoice->calculate_total_profit_for_invoice($invoice_id);
        $invoice->calculate_total_value($invoice_id);
        if (0 < $customer_id) {
            $invoice->updateCustomerInvoice($invoice_id, $customer_id);
        } else {
            $invoice->resetCustomerInvoice($invoice_id);
        }
        echo json_encode(array());
    }
}

?>