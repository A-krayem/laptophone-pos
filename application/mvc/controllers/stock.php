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
class stock extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public $settings_info_local = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
        $this->settings_info_local = self::get_settings_local();
    }
    public function print_pi($_id)
    {
        $stock = $this->model("stock");
        $items = $this->model("items");
        $suppliers = $this->model("suppliers");
        $currency = $this->model("currency");
        $data = array();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $data["pi"] = $stock->getStockInvoicesById($id);
        $data["pi_items"] = $stock->getStockInvoiceItems($id);
        $data["currency"] = $currency->getCurrency_by_id($data["pi"][0]["currency_id"]);
        $data["supplier"] = array();
        if (0 < $data["pi"][0]["supplier_id"]) {
            $data["supplier"] = $suppliers->get_supplier_by_id($data["pi"][0]["supplier_id"]);
        }
        $all_pi_more_types = $stock->get_all_pi_more_types();
        $data["all_pi_more_types_array"] = array();
        for ($i = 0; $i < count($all_pi_more_types); $i++) {
            $data["all_pi_more_types_array"][$all_pi_more_types[$i]["id"]] = $all_pi_more_types[$i];
        }
        $data["more_pi"] = $stock->get_pi_more($id);
        $data["settings"] = $this->settings_info;
        $items_info = $items->getItemsInPurchaseInvoice($id);
        for ($i = 0; $i < count($items_info); $i++) {
            $data["items"][$items_info[$i]["id"]] = $items_info[$i];
        }
        $this->view("printing/pi", $data);
    }
    public function delete_pi_more($_id)
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock->delete_pi_more($id);
        echo json_encode(array());
    }
    public function update_currency_pi($_pi_id, $_currency_id)
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $pi_id = filter_var($_pi_id, FILTER_SANITIZE_NUMBER_INT);
        $currency_id = filter_var($_currency_id, FILTER_SANITIZE_NUMBER_INT);
        $stock->update_currency_pi($pi_id, $currency_id);
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $currencies = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $currencies[$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        echo json_encode(array($currencies[$currency_id]["rate_to_system_default"]));
    }
    public function add_more_pi()
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $info["description"] = filter_input(INPUT_POST, "pi_more_description", self::conversion_php_version_filter());
        $info["value"] = filter_input(INPUT_POST, "pi_more_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["type"] = filter_input(INPUT_POST, "pimore_type", FILTER_SANITIZE_NUMBER_INT);
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["id_to_edit_more"] = filter_input(INPUT_POST, "id_to_edit_more", FILTER_SANITIZE_NUMBER_INT);
        $info["apply_to_items"] = filter_input(INPUT_POST, "apply_to_items", FILTER_SANITIZE_NUMBER_INT);
        if ($info["id_to_edit_more"] == 0) {
            $stock->add_more_pi($info);
        } else {
            $stock->update_more_pi($info);
        }
        $stock->apply_more_fees_discount_to_pi($info["id_to_edit"]);
        echo json_encode(array());
    }
    public function get_pi_more_by_id($_id, $_pi_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $pi_id = filter_var($_pi_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $currencies = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $currencies[$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $invoice_info = $stock->getStockInvoicesById($pi_id);
        $info = $stock->get_pi_more_by_id($id);
        $info[0]["value"] = number_format($info[0]["value"], $currencies[$invoice_info[0]["currency_id"]]["pi_decimal"], ".", "");
        echo json_encode($info);
    }
    public function get_pi_more_data($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $info = $stock->get_pi_more($id);
        echo json_encode($info);
    }
    public function get_pi_more($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $info = $stock->get_pi_more($id);
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $currencies = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $currencies[$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $invoice_info = $stock->getStockInvoicesById($id);
        $info_types = $stock->get_all_pi_more_types();
        $info_types_array = array();
        for ($i = 0; $i < count($info_types); $i++) {
            $info_types_array[$info_types[$i]["id"]] = $info_types[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $info_types_array[$info[$i]["type_id"]]["description"]);
            array_push($tmp, number_format($info[$i]["value"], $currencies[$invoice_info[0]["currency_id"]]["pi_decimal"]));
            if ($info[$i]["apply_to_pi"] == 1) {
                array_push($tmp, "Yes");
            } else {
                array_push($tmp, "No");
            }
            array_push($tmp, $info[$i]["note"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_pi_para()
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $info = $stock->get_all_pi_more_types();
        echo json_encode($info);
    }
    public function receive_stock()
    {
        self::giveAccessTo();
        $data = array();
        $data["enable_wholasale"] = $this->settings_info["enable_wholasale"];
        $data["mobile_shop"] = $this->settings_info["mobile_shop"];
        $this->view("receive_stock", $data);
    }
    public function get_pi_info($_supplier_id, $_p1, $_daterange)
    {
        self::giveAccessTo();
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($daterange == "current") {
            $date_range[0] = date("Y-m-01");
            $date_range[1] = date("Y-m-t");
        } else {
            $date_range_tmp = explode(" ", $daterange);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $stock = $this->model("stock");
        $payments = $this->model("payments");
        $data = array();
        $pival = $stock->get_all_pi_value($supplier_id, $date_range);
        $pivatval = $stock->get_all_pi_vat_value($supplier_id, $date_range);
        $tpayments = $payments->get_total_payments($supplier_id, $date_range);
        $pivatval_oninvoice = $stock->get_all_pi_vat_value_on_invoice($supplier_id, $date_range);
        $data["all_pi_value"] = self::global_number_formatter($pival, $this->settings_info);
        $data["all_pi_vat_value"] = self::global_number_formatter($pivatval + $pivatval_oninvoice, $this->settings_info);
        $data["total_payments"] = self::global_number_formatter($tpayments, $this->settings_info);
        echo json_encode($data);
    }
    public function update_pi_picture()
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $pi_id_for_pic = filter_input(INPUT_POST, "pi_id_for_pic", FILTER_SANITIZE_NUMBER_INT);
        $pi_name = self::uploade_picture($_FILES["pi_picture"]["name"], $_FILES["pi_picture"]["tmp_name"], self::idFormat_stockInv($pi_id_for_pic), "pi/");
        if ($pi_name != NULL && $pi_name != "" && 0 < strlen($pi_name)) {
            $stock->update_pi_picture_name($pi_id_for_pic, $pi_name);
        }
        echo json_encode(array());
    }
    public function get_supplier_of_invoice($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $suppliers = $this->model("suppliers");
        $result = $stock->get_supplier_of_invoice($id);
        if ($result[0]["supplier_id"] != NULL) {
            $suppliers_data = $suppliers->getSupplier($result[0]["supplier_id"]);
            if (0 < count($suppliers_data)) {
                $result[0]["supplier_name"] = $suppliers_data[0]["name"];
            }
        }
        echo json_encode($result);
    }
    public function getStockInvoiceItems($_id, $_with_data)
    {
        self::giveAccessTo(array(2));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $currency = $this->model("currency");
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $all_currencies = $currency->getAllEnabledCurrencies();
        $all_currencies_data = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $all_currencies_data[$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $pi_info = $stock->getStockInvoicesById($id);
        $this->settings_info["default_currency_symbol"] = $all_currencies_data[$pi_info[0]["currency_id"]]["symbole"];
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $result = $stock->getStockInvoiceItems($id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp = array();
            $items_info = $items->get_item($result[$i]["item_id"]);
            array_push($tmp, $result[$i]["id"]);
            array_push($tmp, self::idFormat_item($result[$i]["item_id"]));
            array_push($tmp, $items_info[0]["barcode"]);
            array_push($tmp, $items_info[0]["description"]);
            if (!is_null($items_info[0]["color_text_id"])) {
                array_push($tmp, $colors_info_label[$items_info[0]["color_text_id"]]);
            } else {
                array_push($tmp, "");
            }
            if ($items_info[0]["size_id"] == NULL) {
                array_push($tmp, "");
            } else {
                array_push($tmp, $sizes_info_label[$items_info[0]["size_id"]]);
            }
            array_push($tmp, (double) $result[$i]["qty"] + (double) $result[$i]["fqty"]);
            if ($_with_data) {
                array_push($tmp, "<input onchange='total_debit_value()' value='" . (double) $result[$i]["returned_debit"] . "' class='qty_input debit_return_qty only_numeric' id='piqty_" . $result[$i]["id"] . "' name='piqty_" . $result[$i]["id"] . "' type='number' min='0' max='" . ((double) $result[$i]["qty"] + (double) $result[$i]["fqty"]) . "'>");
            } else {
                array_push($tmp, "<input onchange='total_debit_value()' autocomplete='off' value='0' class='qty_input debit_return_qty only_numeric' id='piqty_" . $result[$i]["id"] . "' name='piqty_" . $result[$i]["id"] . "' type='number' min='0' max='" . ((double) $result[$i]["qty"] + (double) $result[$i]["fqty"]) . "'>");
            }
            if (0 < (double) $result[$i]["qty"] + (double) $result[$i]["fqty"]) {
                array_push($tmp, self::value_format_custom($result[$i]["cost"] * (double) $result[$i]["qty"] / ((double) $result[$i]["qty"] + (double) $result[$i]["fqty"]), $this->settings_info));
            } else {
                array_push($tmp, self::value_format_custom($result[$i]["cost"] * (double) $result[$i]["qty"], $this->settings_info));
            }
            array_push($tmp, $result[$i]["discount_percentage"] . "%");
            if ($result[$i]["vat"]) {
                array_push($tmp, $this->settings_info["vat"]);
            } else {
                array_push($tmp, 0);
            }
            array_push($tmp, number_format($result[$i]["discount_after_vat"], 2) . "%");
            $total_cost = $result[$i]["cost"];
            if (0 < $result[$i]["discount_percentage"]) {
                $total_cost = $total_cost * (1 - $result[$i]["discount_percentage"] / 100);
            }
            if (0 < $result[$i]["vat"]) {
                $total_cost = $total_cost * $this->settings_info["vat"];
            }
            if (0 < $result[$i]["discount_after_vat"]) {
                $total_cost = $total_cost * (1 - $result[$i]["discount_after_vat"] / 100);
            }
            array_push($tmp, self::value_format_custom($total_cost, $this->settings_info) . "<input type='hidden' id='tc_" . $result[$i]["id"] . "' value='" . $total_cost . "' />");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_purchase_invoice_by_id($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $items = $this->model("items");
        $result = $stock->getStockInvoiceItems($id);
        $result_invoice_p = $stock->getStockInvoicesById($id);
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currencies_info = array();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            $currencies_info[$currencies[$i]["id"]] = $currencies[$i];
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $this->settings_info["default_currency_symbol"] = $currencies_info[$result_invoice_p[0]["currency_id"]]["symbole"];
        $data_array["data"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp = array();
            $items_info = $items->get_item($result[$i]["item_id"]);
            array_push($tmp, self::idFormat_item($result[$i]["item_id"]));
            array_push($tmp, $items_info[0]["description"]);
            array_push($tmp, self::value_format_custom_no_currency((double) $result[$i]["qty"], $this->settings_info));
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom((double) $result[$i]["cost"], $this->settings_info));
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, (double) $result[$i]["discount_percentage"] . " %");
                array_push($tmp, (double) $result[$i]["discount_percentage_2"] . " %");
            }
            if ($result[$i]["vat"] == 1) {
                array_push($tmp, ((double) $this->settings_info["vat"] - 1) * 100 . "%");
            } else {
                array_push($tmp, "0%");
            }
            array_push($tmp, (double) $result[$i]["discount_after_vat"] . " %");
            $after_disc1 = (double) $result[$i]["cost"] - (double) $result[$i]["cost"] * $result[$i]["discount_percentage"] / 100;
            $after_disc = $after_disc1 - $after_disc1 * $result[$i]["discount_percentage_2"] / 100;
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                if ($result[$i]["vat"] == 0) {
                    array_push($tmp, self::value_format_custom($after_disc, $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom(($after_disc + $after_disc * ($this->settings_info["vat"] - 1)) * (1 - $result[$i]["discount_after_vat"] / 100), $this->settings_info));
                }
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function check_if_moved_to_store($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $result = $stock->check_if_moved_to_store($id);
        echo json_encode(array($result[0]["moved_to_stock"]));
    }
    public function lock_pi_set($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $stock->lock_pi_set($id);
        echo json_encode(array());
    }
    public function move_to_store($_id)
    {
    }
    public function getStockInvoiceDetailById($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currencies_info = array();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            $currencies_info[$currencies[$i]["id"]] = $currencies[$i];
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $result = $stock->getStockInvoicesById($id);
        $this->settings_info["default_currency_symbol"] = $currencies_info[$result[0]["currency_id"]]["symbole"];
        $result[0]["id"] = self::idFormat_stockInv($result[0]["id"]);
        $result[0]["receive_invoice_date"] = date("Y-m-d", strtotime($result[0]["receive_invoice_date"]));
        $result[0]["delivery_date"] = date("Y-m-d", strtotime($result[0]["delivery_date"]));
        $result[0]["subtotal"] = self::value_format_custom($result[0]["subtotal"], $this->settings_info);
        $result[0]["discount"] = self::value_format_custom($result[0]["discount"], $this->settings_info);
        $result[0]["invoice_tax"] = self::value_format_custom($result[0]["invoice_tax"], $this->settings_info);
        $result[0]["total"] = self::value_format_custom($result[0]["total"], $this->settings_info);
        echo json_encode($result);
    }
    public function delete_picture_pi($_id)
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $info = $stock->getStockInvoicesById($id);
        if (file_exists($info[0]["pi_picture_name"])) {
            $tmp = explode("/", $info[0]["pi_picture_name"]);
            rename($info[0]["pi_picture_name"], $tmp[0] . "/" . $tmp[1] . "/trash/" . time() . "_" . $tmp[2]);
            $stock->reset_pi_picture_name($id);
        }
        echo json_encode(array());
    }
    public function getStockInvoiceById($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $info = $stock->getStockInvoicesById($id);
        echo json_encode($info);
    }
    public function getStockInvoices_for_transfer()
    {
        $stock = $this->model("stock");
        $info = $stock->getStockInvoices_for_transfer();
        $suppliers = $this->model("suppliers");
        $suppliers_info = $suppliers->getAllSuppliersEvenDeleted();
        $suppliers_array = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_array[$suppliers_info[$i]["id"]] = $suppliers_info[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $suppliers_array[$info[$i]["supplier_id"]]["name"]);
            array_push($tmp, $info[$i]["invoice_reference"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getStockInvoicesById($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $items = $this->model("items");
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $currencies = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $currencies[$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $data["invoice_info"] = $stock->getStockInvoicesById($id);
        $data["pi_more_data"] = $stock->get_pi_more($id);
        $receive_invoice_date = strtotime($data["invoice_info"][0]["receive_invoice_date"]);
        $data["invoice_info"][0]["receive_invoice_date"] = date("Y-m-d", $receive_invoice_date);
        $delivery_date = strtotime($data["invoice_info"][0]["delivery_date"]);
        $data["invoice_info"][0]["delivery_date"] = date("Y-m-d", $delivery_date);
        $data["invoice_info"][0]["subtotal"] = number_format($data["invoice_info"][0]["subtotal"], 2, ".", "");
        $data["invoice_info"][0]["discount"] = number_format($data["invoice_info"][0]["discount"], 2, ".", "");
        $data["invoice_info"][0]["total"] = number_format($data["invoice_info"][0]["total"], $currencies[$data["invoice_info"][0]["currency_id"]]["pi_decimal"], ".", "");
        $data["invoice_info"][0]["paid_status"] = number_format($data["invoice_info"][0]["paid_status"]);
        $data["invoice_info"][0]["invoice_tax"] = number_format($data["invoice_info"][0]["invoice_tax"], 2, ".", "");
        $data["invoice_info"][0]["cur_rate"] = $data["invoice_info"][0]["cur_rate"];
        $data["invoice_info"][0]["invoice_reference"] = $data["invoice_info"][0]["invoice_reference"];
        $data["invoice_info"][0]["pi_pic_exist"] = 0;
        $data["invoice_info"][0]["pi_pic_path"] = "";
        if (!is_null($data["invoice_info"][0]["pi_picture_name"])) {
            $data["invoice_info"][0]["pi_pic_exist"] = 1;
            $data["invoice_info"][0]["pi_pic_path"] = $data["invoice_info"][0]["pi_picture_name"];
        }
        $items_info = $items->getAllItemsEvenDeleted();
        $items_info_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $items_info_array[$items_info[$i]["id"]] = $items_info[$i];
        }
        $data["invoice_items"] = $stock->getStockInvoiceItems($id);
        for ($i = 0; $i < count($data["invoice_items"]); $i++) {
            $data["invoice_items"][$i]["cost"] = $data["invoice_items"][$i]["cost"];
            $data["invoice_items"][$i]["description"] = $items_info_array[$data["invoice_items"][$i]["item_id"]]["description"];
            if (isset($colors_array[$items_info_array[$data["invoice_items"][$i]["item_id"]]["color_text_id"]]) && 0 < strlen($colors_array[$items_info_array[$data["invoice_items"][$i]["item_id"]]["color_text_id"]])) {
                $data["invoice_items"][$i]["description"] .= "|" . $colors_array[$items_info_array[$data["invoice_items"][$i]["item_id"]]["color_text_id"]];
            }
            if (isset($sizes_array[$items_info_array[$data["invoice_items"][$i]["item_id"]]["size_id"]]) && 0 < strlen($sizes_array[$items_info_array[$data["invoice_items"][$i]["item_id"]]["size_id"]])) {
                $data["invoice_items"][$i]["description"] .= "|" . $sizes_array[$items_info_array[$data["invoice_items"][$i]["item_id"]]["size_id"]];
            }
        }
        if ($data["invoice_info"][0]["supplier_id"] != NULL) {
            $all_debit_note = $stock->get_all_debit_notes($data["invoice_info"][0]["supplier_id"]);
        } else {
            $all_debit_note = array();
        }
        if (in_array($data["invoice_info"][0]["id"], $all_debit_note)) {
            $data["invoice_info"][0]["moved_to_stock"] = 1;
        } else {
            $data["invoice_info"][0]["moved_to_stock"] = 0;
        }
        echo json_encode($data);
    }
    public function delete_purchase_invoice($_po_id)
    {
        self::giveAccessTo();
        $po_id = filter_var($_po_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $store = $this->model("store");
        $items = $this->model("items");
        $global_logs = $this->model("global_logs");
        $stockInvoices = $stock->getStockInvoiceItems($po_id);
        $deleted = 0;
        for ($i = 0; $i < count($stockInvoices); $i++) {
            $deleted = 1;
            $info_add_qty = array();
            $info_add_qty["qty"] = 0 - $stockInvoices[$i]["qty"];
            $info_add_qty["item_id"] = $stockInvoices[$i]["item_id"];
            $info_add_qty["store_id"] = $stockInvoices[$i]["location_id"];
            $info_add_qty["source"] = $_po_id;
            $store->add_qty($info_add_qty);
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["related_to_item_id"] = $info_add_qty["item_id"];
            $logs_info["description"] = "Subtracted Qty " . $info_add_qty["qty"] . " (By Delete ALL PI " . $po_id . ") of Item (IT-" . $info_add_qty["item_id"] . ")";
            $logs_info["log_type"] = 1;
            $logs_info["other_info"] = "";
            $global_logs->add_global_log($logs_info);
            if ($this->settings_info["telegram_enable"] == 1) {
                $users = $this->model("user");
                $employees_info = $users->getAllUsersEvenDeleted();
                $employees_info_array = array();
                for ($i = 0; $i < count($employees_info); $i++) {
                    $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                }
                $store = $this->model("store");
                $store_info = $store->getStoresById($_SESSION["store_id"]);
                $item_info = $items->get_item($stockInvoices[$i]["item_id"]);
                $info_tel = array();
                $info_tel["message"] = "<strong>Qty Changed:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                $info_tel["message"] .= "<strong>Item ID:</strong> " . $info_add_qty["item_id"] . " \n";
                $info_tel["message"] .= "<strong>Description:</strong> " . $item_info[0]["description"] . " \n";
                $info_tel["message"] .= "<strong>Qty:</strong> " . $info_add_qty["qty"] . " \n";
                self::send_to_telegram($info_tel, 1);
            }
            $stock->delete_history_price($_po_id, $stockInvoices[$i]["item_id"]);
            $items->set_global_average_cost($stockInvoices[$i]["item_id"]);
        }
        if ($deleted == 1 || count($stockInvoices) == 0) {
            $stock->delete_purchase_invoice($po_id);
        }
        echo json_encode(array());
    }
    public function getStockInvoices($_supplier_id, $_payment_status_id, $_daterange)
    {
        self::giveAccessTo(array(2, 4));
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $payment_status_id = filter_var($_payment_status_id, FILTER_SANITIZE_NUMBER_INT);
        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($daterange == "current") {
            $date_range[0] = date("Y-m-01");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $daterange);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $stock = $this->model("stock");
        $suppliers = $this->model("suppliers");
        $suppliers_info = $suppliers->getAllSuppliersEvenDeleted();
        $suppliers_array = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_array[$suppliers_info[$i]["id"]] = $suppliers_info[$i];
        }
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currencies_info = array();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            $currencies_info[$currencies[$i]["id"]] = $currencies[$i];
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $settings = $this->model("settings");
        $payment_status = $settings->get_payment_status();
        $payment_status_info = array();
        for ($i = 0; $i < count($payment_status); $i++) {
            $payment_status_info[$payment_status[$i]["id"]] = $payment_status[$i]["status_name"];
        }
        $getStockInvoices = $stock->getStockInvoices($supplier_id, $payment_status_id, $date_range);
        $all_debit_note = $stock->get_all_debit_notes($supplier_id);
        $bas_cur = $this->settings_info["default_currency_symbol"];
        $data_array["data"] = array();
        for ($i = 0; $i < count($getStockInvoices); $i++) {
            $tmp = array();
            $this->settings_info["default_currency_symbol"] = $currencies_info[$getStockInvoices[$i]["currency_id"]]["symbole"];
            $this->settings_info["number_of_decimal_points"] = $currencies_info[$getStockInvoices[$i]["currency_id"]]["pi_decimal"];
            array_push($tmp, self::idFormat_stockInv($getStockInvoices[$i]["id"]));
            array_push($tmp, self::date_format_custom($getStockInvoices[$i]["receive_invoice_date"]));
            if (isset($suppliers_array[$getStockInvoices[$i]["supplier_id"]])) {
                array_push($tmp, $suppliers_array[$getStockInvoices[$i]["supplier_id"]]["name"]);
            } else {
                array_push($tmp, "");
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
                array_push($tmp, self::critical_data());
                array_push($tmp, self::critical_data());
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::global_number_formatter($getStockInvoices[$i]["subtotal"], $this->settings_info));
                array_push($tmp, self::global_number_formatter($getStockInvoices[$i]["discount"], $this->settings_info));
                array_push($tmp, self::global_number_formatter($getStockInvoices[$i]["invoice_tax"], $this->settings_info));
                array_push($tmp, self::global_number_formatter($getStockInvoices[$i]["total"], $this->settings_info));
            }
            array_push($tmp, floatval($getStockInvoices[$i]["total_qty"]));
            array_push($tmp, $getStockInvoices[$i]["invoice_reference"]);
            array_push($tmp, number_format($getStockInvoices[$i]["cur_rate"], $this->settings_info["number_of_decimal_points"]) . " " . $bas_cur);
            array_push($tmp, "");
            if (in_array($getStockInvoices[$i]["id"], $all_debit_note)) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }
            array_push($tmp, $getStockInvoices[$i]["deleted"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function generate_purshace_invoice()
    {
        self::giveAccessTo();
        $info = array();
        $stock = $this->model("stock");
        $para = array();
        $para["vat"] = $this->settings_info["vat"];
        $para["charge_type"] = $this->settings_info["default_charge_type"];
        $info["id"] = $stock->generate_purshace_invoice($para);
        echo json_encode($info);
    }
    public function print_barcode_using_exe($_item_id, $_number_to_print)
    {
        $main_root = self::get_main_root();
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $items = $this->model("items");
        $settings = $this->model("settings");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $number_to_print = filter_var($_number_to_print, FILTER_SANITIZE_NUMBER_INT);
        $barcode_settings = $settings->get_barcode_local_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
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
        $full_path = $main_root . "/tools/barcode_label/";
        $barcode_page_size_name = $this->settings_info_local["barcode_page_size_name"];
        $printer_name = $this->settings_info_local["printer_barcode_name"];
        $store_name_chaine = $barcode_settings_info["store_name_enable"] . "#" . $barcode_settings_info["store_name_x"] . "#" . $barcode_settings_info["store_name_y"] . "#" . $barcode_settings_info["store_name_font_size"] . "#" . $this->settings_info_local["shop_name"];
        $item_info = $items->get_item($item_id);
        $it_desc = $item_info[0]["description"];
        if ($item_info[0]["item_alias"] != NULL && $item_info[0]["item_alias"] != "" && $item_info[0]["item_alias"] != "null") {
            $it_desc = $item_info[0]["item_alias"];
            if ($barcode_settings_info["description_max_size"] < strlen($item_info[0]["item_alias"])) {
                $it_desc = substr($item_info[0]["item_alias"], 0, $barcode_settings_info["description_max_size"]) . " ...";
            }
        } else {
            if ($barcode_settings_info["description_max_size"] < strlen($item_info[0]["description"])) {
                $it_desc = substr($item_info[0]["description"], 0, $barcode_settings_info["description_max_size"]) . " ...";
            }
        }
        if (in_array($item_info[0]["id"], $discounts_items_ids)) {
            $item_info[0]["discount"] = $discounts_items_discount[$item_info[0]["id"]];
        }
        $new_price = self::value_format_custom($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), $this->settings_info);
        $description_chaine = $barcode_settings_info["description_enable"] . "#" . $barcode_settings_info["description_x"] . "#" . $barcode_settings_info["description_y"] . "#" . $barcode_settings_info["description_size"] . "#" . $it_desc;
        $original_price_chaine = $barcode_settings_info["price_enable"] . "#" . $barcode_settings_info["price_x"] . "#" . $barcode_settings_info["price_y"] . "#" . $barcode_settings_info["price_font_size"] . "#Price: " . number_format($item_info[0]["selling_price"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        $discount_chaine = $barcode_settings_info["discount_enable"] . "#" . $barcode_settings_info["discount_x"] . "#" . $barcode_settings_info["discount_y"] . "#" . $barcode_settings_info["discount_font_size"] . "#" . round($item_info[0]["discount"], $this->settings_info["number_of_decimal_points"]);
        $discounted_price_chaine = $barcode_settings_info["discount_enable"] . "#" . $barcode_settings_info["price_after_discount_x"] . "#" . $barcode_settings_info["price_after_discount_y"] . "#" . $barcode_settings_info["price_after_discount_size"] . "#New Price: " . $new_price;
        $size_chaine = $barcode_settings_info["size_enable"] . "#" . $barcode_settings_info["size_x"] . "#" . $barcode_settings_info["size_y"] . "#" . $barcode_settings_info["size_font_size"] . "#SIZE: " . $sizes_array[$item_info[0]["size_id"]];
        $color_chaine = $barcode_settings_info["color_enable"] . "#" . $barcode_settings_info["color_x"] . "#" . $barcode_settings_info["color_y"] . "#" . $barcode_settings_info["color_font_size"] . "#COLOR: " . $colors_array[$item_info[0]["color_text_id"]];
        if (file_exists($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg")) {
            unlink($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg");
        }
        $barcode_key[0]["mid"] = $item_info[0]["barcode"];
        if (strlen($barcode_key[0]["mid"]) < 5) {
            $barcode_key[0]["mid"] = sprintf("%05s", $barcode_key[0]["mid"]);
        }
        require_once "application/mvc/models/BarcodeGenerator.php";
        $generator = new barcodeGenerator();
        $options = array();
        if ($barcode_settings_info["h"] != -1) {
            $options["h"] = $barcode_settings_info["h"];
        }
        if ($barcode_settings_info["w"] != -1) {
            $options["w"] = $barcode_settings_info["w"];
        }
        if ($barcode_settings_info["wm"] != -1) {
            $options["wm"] = $barcode_settings_info["wm"];
        }
        if ($barcode_settings_info["ww"] != -1) {
            $options["ww"] = $barcode_settings_info["ww"];
        }
        if ($barcode_settings_info["wq"] != -1) {
            $options["wq"] = $barcode_settings_info["wq"];
        }
        if ($barcode_settings_info["wn"] != -1) {
            $options["wn"] = $barcode_settings_info["wn"];
        }
        if ($barcode_settings_info["th"] != -1) {
            $options["th"] = $barcode_settings_info["th"];
        }
        if ($barcode_settings_info["ts"] != -1) {
            $options["ts"] = $barcode_settings_info["ts"];
        }
        if ($barcode_settings_info["pt"] != -1) {
            $options["pt"] = $barcode_settings_info["pt"];
        }
        if ($barcode_settings_info["pb"] != -1) {
            $options["pb"] = $barcode_settings_info["pb"];
        }
        if ($barcode_settings_info["pl"] != -1) {
            $options["pl"] = $barcode_settings_info["pl"];
        }
        if ($barcode_settings_info["pr"] != -1) {
            $options["pr"] = $barcode_settings_info["pr"];
        }
        if ($barcode_settings_info["p"] != -1) {
            $options["p"] = $barcode_settings_info["p"];
        }
        $image = $generator->output_image("jpg", $barcode_settings_info["type"], $barcode_key[0]["mid"], $options);
        $cmd = $main_root . "/tools/barcode_label/LabelPrinter \"" . $full_path . "\" \"" . $barcode_page_size_name . "\" \"" . $printer_name . "\" \"" . $store_name_chaine . "\" \"" . $description_chaine . "\" \"" . $original_price_chaine . "\" \"" . $discount_chaine . "\" \"" . $discounted_price_chaine . "\" \"" . $number_to_print . "\" \"" . $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg\" \"" . $barcode_settings_info["barcode_position_x"] . "\" \"" . $barcode_settings_info["barcode_position_y"] . "\" \"" . $size_chaine . "\" \"" . $color_chaine . "\"";
        exec($cmd, $output, $result);
    }
    public function receive_stock_data()
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $store = $this->model("store");
        $items = $this->model("items");
        $global_logs = $this->model("global_logs");
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currencies_info = array();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            $currencies_info[$currencies[$i]["id"]] = $currencies[$i];
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $info_invoice = array();
        $result = $_POST["items"];
        $info_invoice["supplier_id"] = filter_input(INPUT_POST, "supplier_id", FILTER_SANITIZE_NUMBER_INT);
        $info_invoice["currency_id"] = filter_input(INPUT_POST, "currency_id", FILTER_SANITIZE_NUMBER_INT);
        $info_invoice["cur_rate"] = filter_input(INPUT_POST, "cur_rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if ($info_invoice["currency_id"] == $currency_default_id) {
            $info_invoice["cur_rate"] = 1;
        } else {
            if ($currency_default_id == 1 && $info_invoice["currency_id"] == 2) {
                $info_invoice["cur_rate"] = 1 / $info_invoice["cur_rate"];
            } else {
                if ($currency_default_id == 2 && $info_invoice["currency_id"] == 1) {
                    $info_invoice["cur_rate"] = $info_invoice["cur_rate"];
                } else {
                    if ($currency_default_id == 1 && 2 < $info_invoice["currency_id"]) {
                        $info_invoice["cur_rate"] = 1 / $info_invoice["cur_rate"];
                    }
                    if ($currency_default_id == 2 && 2 < $info_invoice["currency_id"]) {
                    }
                }
            }
        }
        $info_invoice["receive_invoice_date"] = filter_input(INPUT_POST, "invoice_date", self::conversion_php_version_filter());
        $info_invoice["delivery_date"] = filter_input(INPUT_POST, "delivery_date", self::conversion_php_version_filter());
        $info_invoice["invoice_subtotal"] = filter_input(INPUT_POST, "invoice_subtotal", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info_invoice["invoice_discount"] = filter_input(INPUT_POST, "invoice_discount", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info_invoice["invoice_total"] = filter_input(INPUT_POST, "invoice_total", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info_invoice["invoice_tax"] = filter_input(INPUT_POST, "invoice_tax", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info_invoice["action_type"] = filter_input(INPUT_POST, "action_type", FILTER_SANITIZE_NUMBER_INT);
        $info_invoice["autofill_id"] = filter_input(INPUT_POST, "autofill_id", FILTER_SANITIZE_NUMBER_INT);
        $info_invoice["invoice_reference"] = filter_input(INPUT_POST, "invoice_reference", self::conversion_php_version_filter());
        $info_invoice["charge_type_id"] = filter_input(INPUT_POST, "charge_type_id", FILTER_SANITIZE_NUMBER_INT);
        $allStockInvoiceItems = array();
        if (0 < $info_invoice["action_type"]) {
            $allStockInvoiceItems_tmp = $stock->getStockInvoiceItems($info_invoice["action_type"]);
            for ($i = 0; $i < count($allStockInvoiceItems_tmp); $i++) {
                $allStockInvoiceItems[$allStockInvoiceItems_tmp[$i]["item_id"]] = $allStockInvoiceItems_tmp[$i];
            }
        }
        $allItemsEvenDeleted = array();
        $allItemsEvenDeleted_tmp = $items->getAllItemsEvenDeleted();
        for ($i = 0; $i < count($allItemsEvenDeleted_tmp); $i++) {
            $allItemsEvenDeleted[$allItemsEvenDeleted_tmp[$i]["id"]] = $allItemsEvenDeleted_tmp[$i];
        }
        $qtyOfAllItem = array();
        $qtyOfAllItem_tmp = $store->getQtyOfAllItemInAllStore();
        for ($i = 0; $i < count($qtyOfAllItem_tmp); $i++) {
            $qtyOfAllItem[$qtyOfAllItem_tmp[$i]["item_id"]] = $qtyOfAllItem_tmp[$i];
        }
        $update_history_qty_query = NULL;
        $update_history_fqty_query = NULL;
        $update_history_prices_query = NULL;
        $updateStockInvoiceItems_query = NULL;
        $to_update_average = array();
        $update_stock_invoice_item_id = NULL;
        $update_stock_invoice_qty = NULL;
        $update_stock_invoice_charge = NULL;
        $update_stock_invoice_cost = NULL;
        $update_stock_invoice_vat = NULL;
        $update_stock_invoice_disc = NULL;
        $update_stock_invoice_disc_2 = NULL;
        $update_stock_invoice_disc_after_vat = NULL;
        $update_stock_invoice_supplier_ref = NULL;
        if ($info_invoice["action_type"] == 0) {
            $invoice_stock_id = $stock->addStockInvoice($info_invoice);
        } else {
            $stock->updateStockInvoice($info_invoice);
            $totl_item = count($result);
            for ($i = 0; $i < $totl_item; $i++) {
                $tmp = array();
                $tmp["index"] = $result[$i]["index"];
                $tmp["item_id"] = $result[$i]["item_id"];
                $tmp["location_id"] = $_SESSION["store_id"];
                $tmp["qty"] = $result[$i]["qty"];
                $tmp["fqty"] = $result[$i]["fqty"];
                $tmp["cost"] = $result[$i]["unit_cost"];
                $tmp["vat"] = $result[$i]["vat"];
                $tmp["supplier_item_ref"] = $result[$i]["supplier_item_ref"];
                $tmp["unit_discount"] = $result[$i]["unit_discount"];
                $tmp["unit_discount_2"] = $result[$i]["unit_discount_2"];
                $tmp["charge"] = $result[$i]["charge"];
                $tmp["unit_discount_after_vat"] = $result[$i]["discount_after_vat"];
                $tmp["expiry_date"] = $result[$i]["expiry_date"];
                if ($tmp["expiry_date"] == 0) {
                    $tmp["expiry_date"] = "NULL";
                }
                if (0 < $result[$i]["print_barcode"]) {
                    self::print_barcode_using_exe($result[$i]["item_id"], $result[$i]["print_barcode"]);
                }
                if ($result[$i]["new_item"] == 1) {
                    $returned_id = $stock->addStockInvoiceItems($tmp, $info_invoice["action_type"]);
                    $info_hist = array();
                    $info_hist["user_id"] = $_SESSION["id"];
                    $info_hist["item_id"] = $result[$i]["item_id"];
                    $info_hist["old_cost"] = $allItemsEvenDeleted[$result[$i]["item_id"]]["buying_cost"];
                    if ($info_invoice["currency_id"] != $currency_default_id) {
                        $result[$i]["unit_cost"] = $result[$i]["unit_cost"] * $info_invoice["cur_rate"];
                    }
                    if ($this->settings_info["apply_vat_sales_item"] == 1) {
                        $info_hist["new_cost"] = $result[$i]["unit_cost"] * (1 - $result[$i]["unit_discount"] / 100);
                        if (0 < $result[$i]["vat"]) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] * $this->settings_info["vat"];
                        }
                        if (0 < $result[$i]["discount_after_vat"]) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] * (1 - $result[$i]["discount_after_vat"] / 100);
                        }
                        if (0 < $result[$i]["vat"]) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] / $this->settings_info["vat"];
                        }
                    } else {
                        $info_hist["new_cost"] = $result[$i]["final_unit_cost"];
                        if ($info_invoice["currency_id"] != $currency_default_id) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] * $info_invoice["cur_rate"];
                        }
                    }
                    if ($_SESSION["centralize"] == 0) {
                        $info_hist["old_qty"] = $qtyOfAllItem[$result[$i]["item_id"]]["quantity"];
                    } else {
                        $info_hist["old_qty"] = self::get_sum_qty_in_all_stores($result[$i]["item_id"]);
                    }
                    $info_hist["new_qty"] = $result[$i]["qty"];
                    $info_hist["source"] = $info_invoice["action_type"];
                    $info_hist["receive_stock_id"] = $returned_id;
                    $info_hist["free"] = $result[$i]["fqty"];
                    $items->add_history_prices($info_hist);
                    $items->set_global_average_cost($result[$i]["item_id"]);
                    $info_add_qty["qty"] = $result[$i]["qty"] + $result[$i]["fqty"];
                    $info_add_qty["item_id"] = $result[$i]["item_id"];
                    $info_add_qty["store_id"] = $_SESSION["store_id"];
                    $info_add_qty["source"] = $info_invoice["action_type"];
                    $store->add_qty($info_add_qty);
                    $logs_info = array();
                    $logs_info["operator_id"] = $_SESSION["id"];
                    $logs_info["related_to_item_id"] = $result[$i]["item_id"];
                    if (0 < $info_add_qty["qty"]) {
                        $logs_info["description"] = "Added Qty " . $info_add_qty["qty"] . " of Item (IT-" . $result[$i]["item_id"] . ") PI " . $returned_id;
                    } else {
                        $logs_info["description"] = "Subtracted Qty " . $info_add_qty["qty"] . " of Item (IT-" . $result[$i]["item_id"] . ") PI " . $returned_id;
                    }
                    $logs_info["log_type"] = 1;
                    $logs_info["other_info"] = "";
                    $global_logs->add_global_log($logs_info);
                    if ($this->settings_info["telegram_enable"] == 1) {
                        $users = $this->model("user");
                        $employees_info = $users->getAllUsersEvenDeleted();
                        $employees_info_array = array();
                        for ($e = 0; $e < count($employees_info); $e++) {
                            $employees_info_array[$employees_info[$e]["id"]] = $employees_info[$e]["username"];
                        }
                        $store = $this->model("store");
                        $store_info = $store->getStoresById($_SESSION["store_id"]);
                        $item_info = $items->get_item($logs_info["related_to_item_id"]);
                        $info_tel = array();
                        $info_tel["message"] = "<strong>Qty Changed:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                        $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                        $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                        $info_tel["message"] .= "<strong>Item ID:</strong> " . $info_add_qty["item_id"] . " \n";
                        $info_tel["message"] .= "<strong>Description:</strong> " . $item_info[0]["description"] . " \n";
                        $info_tel["message"] .= "<strong>Qty:</strong> " . $info_add_qty["qty"] . " \n";
                        self::send_to_telegram($info_tel, 1);
                    }
                    $info["item_id"] = $info_add_qty["item_id"];
                    $info["vat"] = $result[$i]["vat"];
                    if ($this->settings_info["apply_vat_sales_item"] == 1) {
                        $items->set_vat($info);
                    }
                    if ($this->settings_info["force_price_equal_cost"] == 1) {
                        self::force_item_price_equal_cost($result[$i]["item_id"], $info_hist["new_cost"], $result[$i]["qty"], $info_invoice["action_type"]);
                    }
                    $log_st = array();
                    $log_st["created_by"] = $_SESSION["id"];
                    $log_st["related_to_item_id"] = $result[$i]["item_id"];
                    $log_st["pi_id"] = $info_invoice["action_type"];
                    $log_st["description"] = "Item Added - Qty:" . floatval($info_add_qty["qty"]) . " Free Qty:" . floatval($info_hist["free"]) . " Cost:" . floatval($info_hist["new_cost"]) . " Charge:" . floatval($tmp["charge"]);
                    $stock->receive_stock_logs($log_st);
                } else {
                    $update_stock_invoice_item_id .= " when id=" . $tmp["index"] . " then " . $tmp["item_id"] . " ";
                    $update_stock_invoice_qty .= " when id=" . $tmp["index"] . " then " . $tmp["qty"] . " ";
                    $update_stock_invoice_fqty .= " when id=" . $tmp["index"] . " then " . $tmp["fqty"] . " ";
                    $update_stock_invoice_charge .= " when id=" . $tmp["index"] . " then " . $tmp["charge"] . " ";
                    if ($tmp["expiry_date"] == "null") {
                        $update_stock_invoice_expirydate .= " when id=" . $tmp["index"] . " then NULL ";
                    } else {
                        $update_stock_invoice_expirydate .= " when id=" . $tmp["index"] . " then '" . $tmp["expiry_date"] . "' ";
                    }
                    $update_stock_invoice_cost .= " when id=" . $tmp["index"] . " then " . $tmp["cost"] . " ";
                    $update_stock_invoice_vat .= " when id=" . $tmp["index"] . " then " . $tmp["vat"] . " ";
                    $update_stock_invoice_disc .= " when id=" . $tmp["index"] . " then " . $tmp["unit_discount"] . " ";
                    $update_stock_invoice_disc_2 .= " when id=" . $tmp["index"] . " then " . $tmp["unit_discount_2"] . " ";
                    $update_stock_invoice_disc_after_vat .= " when id=" . $tmp["index"] . " then " . $tmp["unit_discount_after_vat"] . " ";
                    $update_stock_invoice_supplier_ref .= " when id=" . $tmp["index"] . " then '" . $tmp["supplier_item_ref"] . "' ";
                    if ($_SESSION["store_id"] == $allStockInvoiceItems[$result[$i]["item_id"]]["location_id"]) {
                        $info_add_qty["qty"] = $result[$i]["qty"] + $result[$i]["fqty"] - ($allStockInvoiceItems[$result[$i]["item_id"]]["qty"] + $allStockInvoiceItems[$result[$i]["item_id"]]["fqty"]);
                        $info_add_qty["item_id"] = $result[$i]["item_id"];
                        $info_add_qty["store_id"] = $_SESSION["store_id"];
                        $info_add_qty["source"] = $info_invoice["action_type"];
                        $store->add_qty($info_add_qty);
                        if ($info_add_qty["qty"] != 0) {
                            $logs_info = array();
                            $logs_info["operator_id"] = $_SESSION["id"];
                            $logs_info["related_to_item_id"] = $result[$i]["item_id"];
                            if (0 < $info_add_qty["qty"]) {
                                $logs_info["description"] = "Added Qty " . $info_add_qty["qty"] . " of Item (IT-" . $result[$i]["item_id"] . ") PI " . $info_invoice["action_type"];
                            } else {
                                $logs_info["description"] = "Subtracted Qty " . $info_add_qty["qty"] . " of Item (IT-" . $result[$i]["item_id"] . ") PI " . $info_invoice["action_type"];
                            }
                            $logs_info["log_type"] = 1;
                            $logs_info["other_info"] = "";
                            $global_logs->add_global_log($logs_info);
                            if ($this->settings_info["telegram_enable"] == 1) {
                                $users = $this->model("user");
                                $employees_info = $users->getAllUsersEvenDeleted();
                                $employees_info_array = array();
                                for ($e = 0; $e < count($employees_info); $e++) {
                                    $employees_info_array[$employees_info[$e]["id"]] = $employees_info[$e]["username"];
                                }
                                $store = $this->model("store");
                                $store_info = $store->getStoresById($_SESSION["store_id"]);
                                $item_info = $items->get_item($tmp["item_id"]);
                                $info_tel = array();
                                $info_tel["message"] = "<strong>Qty Changed:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                                $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                                $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                                $info_tel["message"] .= "<strong>Item ID:</strong> " . $info_add_qty["item_id"] . " \n";
                                $info_tel["message"] .= "<strong>Description:</strong> " . $item_info[0]["description"] . " \n";
                                $info_tel["message"] .= "<strong>Qty:</strong> " . $info_add_qty["qty"] . " \n";
                                self::send_to_telegram($info_tel, 1);
                            }
                        }
                    }
                    $info_hist = array();
                    $info_hist["user_id"] = $_SESSION["id"];
                    $info_hist["item_id"] = $result[$i]["item_id"];
                    $info_hist["old_cost"] = $allItemsEvenDeleted[$result[$i]["item_id"]]["buying_cost"];
                    if ($info_invoice["currency_id"] != $currency_default_id) {
                        $result[$i]["unit_cost"] = $result[$i]["unit_cost"] * $info_invoice["cur_rate"];
                    }
                    if ($this->settings_info["apply_vat_sales_item"] == 1) {
                        $info_hist["new_cost"] = $result[$i]["unit_cost"] * (1 - $result[$i]["unit_discount"] / 100);
                        if (0 < $result[$i]["vat"]) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] * $this->settings_info["vat"];
                        }
                        if (0 < $result[$i]["discount_after_vat"]) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] * (1 - $result[$i]["discount_after_vat"] / 100);
                        }
                        if (0 < $result[$i]["vat"]) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] / $this->settings_info["vat"];
                        }
                    } else {
                        $info_hist["new_cost"] = $result[$i]["final_unit_cost"];
                        if ($info_invoice["currency_id"] != $currency_default_id) {
                            $info_hist["new_cost"] = $info_hist["new_cost"] * $info_invoice["cur_rate"];
                        }
                    }
                    $info_hist["old_qty"] = $qtyOfAllItem[$result[$i]["item_id"]]["quantity"];
                    $info_hist["new_qty"] = $result[$i]["qty"] - $allStockInvoiceItems[$result[$i]["item_id"]]["qty"];
                    $info_hist["source"] = $info_invoice["action_type"];
                    $info_hist["po_id"] = $info_invoice["action_type"];
                    $info_hist["receive_stock_id"] = $allStockInvoiceItems[$result[$i]["item_id"]]["id"];
                    $update_history_qty_query .= " when item_id=" . $info_hist["item_id"] . " and source='" . $info_hist["po_id"] . "' and receive_stock_id='" . $info_hist["receive_stock_id"] . "' then added_qty+" . $info_hist["new_qty"] . " ";
                    $update_history_fqty_query .= " when item_id=" . $info_hist["item_id"] . " and source='" . $info_hist["po_id"] . "' and receive_stock_id='" . $info_hist["receive_stock_id"] . "' then " . $result[$i]["fqty"] . " ";
                    $update_history_prices_query .= " when item_id=" . $info_hist["item_id"] . " and source='" . $info_hist["po_id"] . "' and receive_stock_id='" . $info_hist["receive_stock_id"] . "' then " . $info_hist["new_cost"] . " ";
                    array_push($to_update_average, $tmp["item_id"]);
                    $info["item_id"] = $info_add_qty["item_id"];
                    $info["vat"] = $result[$i]["vat"];
                    if ($this->settings_info["apply_vat_sales_item"] == 1) {
                        $items->set_vat($info);
                    }
                    if ($this->settings_info["force_price_equal_cost"] == 1) {
                        self::force_item_price_equal_cost($result[$i]["item_id"], $info_hist["new_cost"], $result[$i]["qty"], $info_invoice["action_type"]);
                    }
                }
            }
            $stock->apply_more_fees_discount_to_pi($info_invoice["action_type"]);
            $old_pi_details = $stock->get_pi_details($info_invoice["action_type"]);
            if ($update_stock_invoice_item_id != NULL) {
                $query_qty = "UPDATE receive_stock SET item_id = (CASE " . $update_stock_invoice_item_id . " ELSE item_id END),qty = (CASE " . $update_stock_invoice_qty . " ELSE qty END),cost = (CASE " . $update_stock_invoice_cost . " ELSE cost END),vat = (CASE " . $update_stock_invoice_vat . " ELSE vat END),discount_percentage = (CASE " . $update_stock_invoice_disc . " ELSE discount_percentage END),discount_after_vat = (CASE " . $update_stock_invoice_disc_after_vat . " ELSE discount_after_vat END),supplier_ref = (CASE " . $update_stock_invoice_supplier_ref . " ELSE supplier_ref END),discount_percentage_2 = (CASE " . $update_stock_invoice_disc_2 . " ELSE discount_percentage_2 END),fqty = (CASE " . $update_stock_invoice_fqty . " ELSE fqty END),charge = (CASE " . $update_stock_invoice_charge . " ELSE charge END),expiry_date = (CASE " . $update_stock_invoice_expirydate . " ELSE expiry_date END);";
                $items->update_bulk_queries($query_qty);
            }
            if ($update_history_qty_query != NULL) {
                $query_qty = "UPDATE history_prices SET added_qty = (CASE " . $update_history_qty_query . " ELSE added_qty END),new_cost = (CASE " . $update_history_prices_query . " ELSE new_cost END),free_qty = (CASE " . $update_history_fqty_query . " ELSE free_qty END);";
                $items->update_bulk_queries($query_qty);
            }
            $new_pi_details = $stock->get_pi_details($info_invoice["action_type"]);
            if ($result[$i]["new_item"] == 0) {
                for ($o = 0; $o < count($old_pi_details); $o++) {
                    for ($n = 0; $n < count($new_pi_details); $n++) {
                        if ($old_pi_details[$o]["item_id"] == $new_pi_details[$n]["item_id"]) {
                            $log_st = array();
                            $log_st["created_by"] = $_SESSION["id"];
                            $log_st["related_to_item_id"] = $new_pi_details[$n]["item_id"];
                            $log_st["pi_id"] = $info_invoice["action_type"];
                            if (floatval($old_pi_details[$o]["qty"]) != floatval($new_pi_details[$n]["qty"])) {
                                $log_st["description"] = "Quantity changed from " . floatval($old_pi_details[$o]["qty"]) . " to " . floatval($new_pi_details[$n]["qty"]);
                                $stock->receive_stock_logs($log_st);
                            }
                            if (floatval($old_pi_details[$o]["fqty"]) != floatval($new_pi_details[$n]["fqty"])) {
                                $log_st["description"] = "Free Quantity changed from " . floatval($old_pi_details[$o]["fqty"]) . " to " . floatval($new_pi_details[$n]["fqty"]);
                                $stock->receive_stock_logs($log_st);
                            }
                            if (floatval($old_pi_details[$o]["cost"]) != floatval($new_pi_details[$n]["cost"])) {
                                $log_st["description"] = "Unit cost changed from " . floatval($old_pi_details[$o]["cost"]) . " to " . floatval($new_pi_details[$n]["cost"]);
                                $stock->receive_stock_logs($log_st);
                            }
                            if (floatval($old_pi_details[$o]["discount_percentage"]) != floatval($new_pi_details[$n]["discount_percentage"])) {
                                $log_st["description"] = "Discount 1 changed from " . floatval($old_pi_details[$o]["discount_percentage"]) . " to " . floatval($new_pi_details[$n]["discount_percentage"]);
                                $stock->receive_stock_logs($log_st);
                            }
                            if (floatval($old_pi_details[$o]["discount_after_vat"]) != floatval($new_pi_details[$n]["discount_after_vat"])) {
                                $log_st["description"] = "Discount after TAX changed from " . floatval($old_pi_details[$o]["discount_after_vat"]) . " to " . floatval($new_pi_details[$n]["discount_after_vat"]);
                                $stock->receive_stock_logs($log_st);
                            }
                            if (floatval($old_pi_details[$o]["vat"]) != floatval($new_pi_details[$n]["vat"])) {
                                $action = "Checked";
                                if ($new_pi_details[$n]["vat"] == 0) {
                                    $action = "Removed";
                                }
                                $log_st["description"] = "TAX " . $action;
                                $stock->receive_stock_logs($log_st);
                            }
                            if (floatval($old_pi_details[$o]["charge"]) != floatval($new_pi_details[$n]["charge"])) {
                                $log_st["description"] = "Charge changed from " . floatval($old_pi_details[$o]["charge"]) . " to " . floatval($new_pi_details[$n]["charge"]);
                                $stock->receive_stock_logs($log_st);
                            }
                        }
                    }
                }
            }
            $query_avrgs = "";
            if (0 < count($to_update_average)) {
                for ($ik = 0; $ik < count($to_update_average); $ik++) {
                    $history_costs = $items->get_history_cost($to_update_average[$ik]);
                    $return_cost = $items->calculate_global_average_cost($history_costs);
                    if ($return_cost != 0) {
                        $query_avrgs .= " when id=" . $to_update_average[$ik] . "  then " . $return_cost . " ";
                    }
                }
                $query_avr = "UPDATE items SET buying_cost = (CASE " . $query_avrgs . " ELSE buying_cost END);";
                $items->update_bulk_queries($query_avr);
                if (0 < my_sql::get_mysqli_rows_num()) {
                    my_sql::global_query_sync($query_avr);
                }
            }
        }
        $stock->update_total_qty($info_invoice["action_type"]);
        $settings = $this->model("settings");
        $settings->update_value("1", "auto_update_items_qty_in_admin");
        echo json_encode($info_invoice["action_type"]);
    }
    public function force_item_price_equal_cost($item_id, $cost, $qty, $receive_stock_id)
    {
        $items = $this->model("items");
        $info_hist = array();
        $info_hist["user_id"] = $_SESSION["id"];
        $info_hist["item_id"] = $item_id;
        $info_hist["old_cost"] = $cost;
        $info_hist["new_cost"] = $cost;
        if ($_SESSION["centralize"] == 0) {
            $info_hist["old_qty"] = $qty;
        } else {
            $info_hist["old_qty"] = self::get_sum_qty_in_all_stores($item_id);
        }
        $info_hist["new_qty"] = 0;
        $info_hist["source"] = "force";
        $info_hist["free"] = 0;
        $info_hist["receive_stock_id"] = $receive_stock_id;
        $items->add_history_prices($info_hist);
        $items->set_global_average_cost($item_id);
        $items->force_item_price_equal_cost($cost, $item_id);
    }
    public function delete_item_from_invoice_order($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $stock = $this->model("stock");
        $store = $this->model("store");
        $items = $this->model("items");
        $global_logs = $this->model("global_logs");
        $item_from_invoice_order = $stock->get_item_from_invoice_order($id);
        $stock->delete_history_price($item_from_invoice_order[0]["receive_stock_invoice_id"], $item_from_invoice_order[0]["item_id"]);
        $info_add_qty["qty"] = 0 - ($item_from_invoice_order[0]["qty"] + $item_from_invoice_order[0]["fqty"]);
        $info_add_qty["item_id"] = $item_from_invoice_order[0]["item_id"];
        $info_add_qty["store_id"] = $_SESSION["store_id"];
        $info_add_qty["source"] = $item_from_invoice_order[0]["receive_stock_invoice_id"];
        $store->add_qty($info_add_qty);
        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION["id"];
        $logs_info["related_to_item_id"] = $info_add_qty["item_id"];
        $logs_info["description"] = "Subtracted Qty " . ($item_from_invoice_order[0]["qty"] + $item_from_invoice_order[0]["fqty"]) . " (By Delete From PI " . $info_add_qty["source"] . ") of Item (IT-" . $info_add_qty["item_id"] . ")";
        $logs_info["log_type"] = 1;
        $logs_info["other_info"] = "";
        $global_logs->add_global_log($logs_info);
        if ($this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $item_info = $items->get_item($info_add_qty["item_id"]);
            $info_tel = array();
            $info_tel["message"] = "<strong>Qty Changed:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Item ID:</strong> " . $info_add_qty["item_id"] . " \n";
            $info_tel["message"] .= "<strong>Description:</strong> " . $item_info[0]["description"] . " \n";
            $info_tel["message"] .= "<strong>Qty:</strong> " . $info_add_qty["qty"] . " \n";
            self::send_to_telegram($info_tel, 1);
        }
        $stock->delete_item_from_invoice_order($id);
        $items->set_global_average_cost($item_from_invoice_order[0]["item_id"]);
        $log_st = array();
        $log_st["created_by"] = $_SESSION["id"];
        $log_st["related_to_item_id"] = $item_from_invoice_order[0]["item_id"];
        $log_st["pi_id"] = $item_from_invoice_order[0]["receive_stock_invoice_id"];
        $log_st["description"] = "Delete Item - Cost:" . floatval($item_from_invoice_order[0]["cost"]) . " Qty:" . floatval($item_from_invoice_order[0]["qty"]) . " Free Qty:" . floatval($item_from_invoice_order[0]["fqty"]) . " Charge:" . floatval($item_from_invoice_order[0]["charge"]);
        $stock->receive_stock_logs($log_st);
        echo json_encode(array());
    }
    public function get_log_pi($_pi_id)
    {
        $stock = $this->model("stock");
        $pi_id = filter_var($_pi_id, FILTER_SANITIZE_NUMBER_INT);
        $logs = $stock->get_log_pi($pi_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($logs); $i++) {
            $tmp = array();
            array_push($tmp, $logs[$i]["creation_date"]);
            array_push($tmp, $logs[$i]["username"]);
            array_push($tmp, "<b>#" . $logs[$i]["related_to_item_id"] . "</b> " . $logs[$i]["item_description"]);
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, $logs[$i]["description"]);
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function _default()
    {
        self::giveAccessTo();
        $this->view("stock");
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>