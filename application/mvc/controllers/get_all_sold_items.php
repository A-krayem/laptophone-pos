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
    public function getAllCustomersDetails()
    {
        self::giveAccessTo(array(2, 4));
        $customers = $this->model("customers");
        $info = $customers->getCustomers();
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
    public function get_item_by_barcode($barcode_)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $store = $this->model("store");
        $barcode = filter_var($barcode_, self::conversion_php_version_filter());
        $real_barcode = $barcode;
        $plu = 0;
        $plu_price = 0;
        if (substr($barcode, 0, 2) === $this->settings_info["plu_prefix"]) {
            $plu = 1;
            $real_barcode = substr($barcode, 0, 6);
            $plu_price = substr($barcode, 7, 5);
        }
        $item_info = $items->get_item_by_barcode($real_barcode);
        if (0 < count($item_info)) {
            $colors = $this->model("colors");
            $all_colors = $colors->getColorsText();
            $colors_info = array();
            for ($i = 0; $i < count($all_colors); $i++) {
                $colors_info[$all_colors[$i]["id"]] = $all_colors[$i]["name"];
            }
            self::tracking_pos("add", $item_info[0]["id"]);
            $item_info[0]["plu"] = $plu;
            for ($i = 0; $i < count($item_info); $i++) {
                if (50 < strlen($item_info[$i]["description"])) {
                    $item_info[$i]["description"] = substr($item_info[$i]["description"], 0, 50) . " ...";
                }
                if ($item_info[$i]["color_text_id"] != NULL && $item_info[$i]["color_text_id"] != "") {
                    if (isset($colors_info[$item_info[$i]["color_text_id"]])) {
                        $item_info[$i]["color_text_id"] = $colors_info[$item_info[$i]["color_text_id"]];
                    } else {
                        $item_info[$i]["color_text_id"] = "Unknown";
                    }
                } else {
                    $item_info[$i]["color_text_id"] = "Unknown";
                }
            }
            if ($plu == 1) {
                $item_info[0]["qty"] = $plu_price * 1 / $item_info[0]["selling_price"];
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
            $item_info[0]["discount"] = number_format($item_info[0]["discount"], 5);
            if (in_array($item_info[0]["id"], $discounts_items_ids)) {
                $item_info[0]["discount"] = number_format($discounts_items_discount[$item_info[0]["id"]], 2);
            }
            $item_info[0]["plu_price"] = $plu_price;
            $item_info[0]["composite_items"] = array();
            if ($item_info[0]["is_composite"] == 1) {
                $item_info[0]["composite_items"] = $items->get_all_composite_of_item($item_info[0]["id"]);
                $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[0]["composite_items"][0]["item_id"]);
                $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
            } else {
                $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[0]["id"]);
                $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
            }
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
        $item_info[0]["discount"] = number_format($item_info[0]["discount"], 5);
        if (in_array($item_info[0]["id"], $discounts_items_ids)) {
            $item_info[0]["discount"] = number_format($discounts_items_discount[$item_info[0]["id"]], 2);
        }
        if (50 < strlen($item_info[0]["description"])) {
            $item_info[0]["description"] = substr($item_info[0]["description"], 0, 50) . " ...";
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
        $item_info[0]["discount"] = number_format($item_info[0]["discount"], 2);
        $item_info[0]["qty"] = 1;
        $item_info[0]["plu_price"] = 0;
        $item_info[0]["composite_items"] = array();
        if ($item_info[0]["is_composite"] == 1) {
            $item_info[0]["composite_items"] = $items->get_all_composite_of_item($id);
            $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[0]["composite_items"][0]["item_id"]);
            $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
        } else {
            $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $id);
            $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
        }
        echo json_encode($item_info);
    }
    public function _default()
    {
        $data = array();
        $data["settings"] = $this->settings_info;
        include "application/lang/" . $this->settings_info["language"] . "/" . $this->settings_info["language"] . ".php";
        $this->view("pos/" . $this->settings_info["pos_path"] . "/pos", $data);
    }
    public function cancelDiscount($_inv_id)
    {
        self::giveAccessTo(array(2, 4));
        $inv_id = filter_var($_inv_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $cashbox = $this->model("cashbox");
        $invoice->cancelDiscount($inv_id);
        $cashbox->updateCashBox($_SESSION["cashbox_id"]);
        echo json_encode(array());
    }
    public function posItems()
    {
        $this->view("pos_items");
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
    public function get_all_invoices_list($date_, $invoice_id_)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $settings = $this->model("settings");
        $customers = $this->model("customers");
        $store_id = 1;
        $date = filter_var($date_, self::conversion_php_version_filter());
        $invoice_id = filter_var($invoice_id_, FILTER_SANITIZE_NUMBER_INT);
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
        $payment_method = $settings->get_all_payment_method();
        $payment_method_info = array();
        for ($i = 0; $i < count($payment_method); $i++) {
            $payment_method_info[$payment_method[$i]["id"]] = $payment_method[$i]["method_name"];
        }
        $info = $invoice->getAllInvoices_list($store_id, $date_range, $this->settings_info);
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
            array_push($tmp, $info[$i]["creation_date"]);
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . "</span>");
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, number_format($info[$i]["total_value"], 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($info[$i]["invoice_discount"], 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($info[$i]["total_value"] + $info[$i]["invoice_discount"], 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, $payment_method_info[$info[$i]["payment_method"]]);
            array_push($tmp, "");
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
            $date_range[0] = date("Y-m-1");
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
            array_push($tmp, self::idFormat_item($info[$i]["id"]));
            $item_info = $items->get_item($info[$i]["item_id"]);
            array_push($tmp, $item_info[0]["description"]);
            array_push($tmp, $item_info[0]["barcode"]);
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0) {
                $customer = $customers->getCustomersById($info[$i]["customer_id"]);
                array_push($tmp, "<span class='debtsColor'>" . $customer[0]["name"] . "</span>");
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, (double) $info[$i]["qty"]);
            array_push($tmp, number_format($info[$i]["selling_price"] * (1 - $info[$i]["discount"] / 100), 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($info[$i]["final_price_disc_qty"], 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, "");
            array_push($tmp, "");
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
            array_push($tmp, self::value_format_custom($tables_info[$i]["selling_price"], $setting));
            $tables_info[$i]["discount"] = number_format($tables_info[$i]["discount"], 2);
            if (in_array($tables_info[$i]["id"], $discounts_items_ids)) {
                $tables_info[$i]["discount"] = number_format($discounts_items_discount[$tables_info[$i]["id"]], 2);
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
            $info_return[$i]["final_price_disc_qty"] = number_format($info[$i]["final_price_disc_qty"], 2);
            $info_return[$i]["selling_price"] = number_format($info[$i]["selling_price"], 2);
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
            $info[$i]["total_value"] = number_format($info[$i]["total_value"], 2);
            $info[$i]["invoice_discount"] = number_format($info[$i]["invoice_discount"], 2);
        }
        echo json_encode($info);
    }
    public function closeCashbox()
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $cashbox->closeCashbox($_SESSION["store_id"], $_SESSION["id"]);
        echo json_encode(array());
    }
    public function getCashBox()
    {
        self::giveAccessTo(array(2, 4));
        $cashbox = $this->model("cashbox");
        $info = array();
        $info["cashBoxTotal"] = number_format($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), 2) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info);
    }
    public function returnBackItems($id_, $qty_)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $payments = $this->model("payments");
        $cashbox = $this->model("cashbox");
        $store = $this->model("store");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($qty_, FILTER_SANITIZE_NUMBER_INT);
        $puchased_item = $invoice->get_item_from_invoice($id);
        $items_info = $items->get_item($puchased_item[0]["item_id"]);
        $info = array();
        $info["id"] = $id;
        $info["item_id"] = $puchased_item[0]["item_id"];
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
        $info["selling_price"] = $puchased_item[0]["selling_price"] * $info["qty"];
        $info["discount"] = $puchased_item[0]["discount"];
        $info["final_price_disc_qty"] = $puchased_item[0]["final_price_disc_qty"];
        $info["returned_by_vendor_id"] = $_SESSION["id"];
        $info["returned_to_store_id"] = $_SESSION["store_id"];
        $invoice->returnPurchasedItem($info);
        $invoice->reduceQtyOfPurchasedItem($info["id"], $info["invoice_id"], $info["qty"]);
        $invoice->calculate_total_profit_for_invoice($info["invoice_id"]);
        $cashbox->updateCashBox($_SESSION["cashbox_id"]);
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
        $payment_info = array();
        $payment_info["invoice_id"] = $info["invoice_id"];
        $payment_info["value"] = 0 - $info["final_price_disc_qty"];
        $payment_info["vendor_id"] = $_SESSION["id"];
        $payment_info["store_id"] = $_SESSION["store_id"];
        $payments->add_payment($payment_info);
        $return_info = array();
        $return_info["remain"] = $remain;
        $return_info["total_price"] = $remain * $puchased_item[0]["selling_price"];
        echo json_encode($return_info);
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
        $info_to_return["cashBoxTotal"] = number_format($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), 2) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info_to_return);
    }
    public function setCashbox($value_)
    {
        self::giveAccessTo(array(2, 4));
        $value = filter_var($value_, FILTER_SANITIZE_NUMBER_INT);
        $cashbox = $this->model("cashbox");
        $_SESSION["cashbox_id"] = $cashbox->setCashbox($_SESSION["store_id"], $_SESSION["id"], $value);
        echo json_encode(array());
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
        $info["cashBoxTotal"] = number_format($cashbox->getTotalCashbox($_SESSION["id"], $_SESSION["store_id"]), 2) . " " . $this->settings_info["default_currency_symbol"];
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
        $customer_info = $customers->getCustomersById($customer_id);
        $invoies_info = $invoice->getTotalUnpaid($customer_id);
        $info = array();
        $info["customer_balance"] = $customer_info[0]["balance"];
        $info["total_unPaid"] = $invoies_info[0]["sum"];
        $info["total_remain"] = $info["total_unPaid"] - $info["customer_balance"];
        echo json_encode($info);
    }
    public function getSettingsForPos()
    {
        $settings = self::getSettings();
        $info = array();
        $info["payment_full"] = $settings["payment_full"];
        $info["payment_later"] = $settings["payment_later"];
        $info["payment_credit_card"] = $settings["payment_credit_card"];
        $info["default_currency_symbol"] = $settings["default_currency_symbol"];
        $info["auto_print"] = $settings["auto_print"];
        $info["payment_later"] = $settings["payment_later"];
        $info["enable_wholasale"] = $settings["enable_wholasale"];
        $info["enable_customer_display"] = $settings["enable_customer_display"];
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
            $data[$i]["selling_price"] = number_format($data[$i]["selling_price"] - $data[$i]["selling_price"] * $data[$i]["discount"] / 100, 2) . " " . $this->settings_info["default_currency_symbol"];
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
            $data[$i]["selling_price"] = number_format($data[$i]["selling_price"] - $data[$i]["selling_price"] * $data[$i]["discount"] / 100, 2) . " " . $this->settings_info["default_currency_symbol"];
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
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]["selling_price"] = number_format($data[$i]["selling_price"] - $data[$i]["selling_price"] * $data[$i]["discount"] / 100, 2) . " " . $this->settings_info["default_currency_symbol"];
        }
        echo json_encode($data);
    }
    public function add_item_to_interface($_item_id, $_store_id_)
    {
        self::giveAccessTo();
        $data = array();
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $store_id = filter_var($_store_id_, FILTER_SANITIZE_NUMBER_INT);
        $info = array();
        $info["store_id"] = $store_id;
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
            $info["packages"][$i]["price"] = number_format($packages[$i]["price"]);
            $info["packages"][$i]["base_color"] = $base_color[$packages[$i]["operator_id"]];
            $info["packages"][$i]["operator_name"] = $base_name[$packages[$i]["operator_id"]];
            $info["packages"][$i]["days"] = $packages[$i]["days"];
            $info["packages"][$i]["type"] = $packages[$i]["type"];
            $info["packages"][$i]["description"] = $packages[$i]["description"];
        }
        $info["devices"] = array();
        for ($i = 0; $i < count($devices); $i++) {
            $info["devices"][$i]["id"] = $devices[$i]["id"];
            $info["devices"][$i]["operator_id"] = $devices[$i]["operator_id"];
            $info["devices"][$i]["balance"] = $devices[$i]["balance"];
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
            $invoices[$i]["invoice_value"] = number_format(floatval($amount[0]["sum"]), 2) . " " . $this->settings_info["default_currency_symbol"];
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
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>