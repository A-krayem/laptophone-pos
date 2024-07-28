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
class dashboard extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public $settings_info_local = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
        $this->settings_info_local = self::get_settings_local();
        $invoice = $this->model("invoice");
        $invoice->recurring_invoices();
    }
    public function test_()
    {
    }
    public function get_all_vendor()
    {
        $return = array();
        $user = $this->model("user");
        $return["vendors"] = $user->getAllUsersPOSEvenDeleted();
        echo json_encode($return);
    }
    public function posmonitor($_p0, $_p1, $_p2, $_p3, $_p4)
    {
        $filter = array();
        $filter["date_range"] = filter_var($_p0, self::conversion_php_version_filter());
        $filter["vendor_id"] = $_p1;
        $date_range_tmp = NULL;
        $filter["start_date"] = NULL;
        $filter["end_date"] = NULL;
        if ($filter["date_range"] == "today") {
            $filter["start_date"] = date("Y-m-d");
            $filter["end_date"] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $filter["date_range"]);
            $filter["start_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $filter["end_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $cashbox = $this->model("cashbox");
        $posm_data = $cashbox->posmonitor($filter);
        $data_array["data"] = array();
        for ($i = 0; $i < count($posm_data); $i++) {
            $tmp = array();
            array_push($tmp, $posm_data[$i]["creation_date"]);
            array_push($tmp, $posm_data[$i]["username"]);
            array_push($tmp, $posm_data[$i]["item_id"]);
            array_push($tmp, $posm_data[$i]["description"]);
            array_push($tmp, $posm_data[$i]["barcode"]);
            array_push($tmp, $posm_data[$i]["qty"]);
            array_push($tmp, floor($posm_data[$i]["quantity"]));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function save_rate($_rate)
    {
        $settings = $this->model("settings");
        $rate = filter_var($_rate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $settings->update_value_with_sync($rate, "usdlbp_rate");
        echo json_encode(array());
    }
    public function var_price_values()
    {
        $info = array();
        $info["base_price_rate_to_usd"] = $this->settings_info["base_price_rate_to_usd"];
        $info["new_price_rate_to_lbp"] = $this->settings_info["new_price_rate_to_lbp"];
        $info["enable_price_var"] = $this->settings_info["enable_price_var"];
        echo json_encode($info);
    }
    public function update_var_prices($enable, $base_price_var_rate, $new_var_price_rate, $round)
    {
        $settings = $this->model("settings");
        $info["enable_price_var"] = filter_var($enable, FILTER_SANITIZE_NUMBER_INT);
        $info["base_price_rate_to_usd"] = filter_var($base_price_var_rate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["new_price_rate_to_lbp"] = filter_var($new_var_price_rate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["price_var_round"] = 1;
        $settings->update_value_with_sync($info["base_price_rate_to_usd"], "base_price_rate_to_usd");
        $settings->update_value_with_sync($info["new_price_rate_to_lbp"], "new_price_rate_to_lbp");
        $settings->update_value_with_sync($info["enable_price_var"], "enable_price_var");
        $settings->update_value_with_sync($info["price_var_round"], "price_var_round");
        echo json_encode(array());
    }
    public function recovery_items_table()
    {
        $outside_connection = $this->model("outside_connection_");
        $store = $this->model("store");
        $db_to_recovery = 2;
        $stores = $store->getAllStores();
        $warehouse_connection_data = array();
        for ($i = 0; $i < count($stores); $i++) {
            if ($stores[$i]["warehouse"] == 1) {
                $warehouse_connection_data = $stores[$i];
            }
        }
        $warehouse_connection = my_sql::custom_connection($warehouse_connection_data["ip_address"], $warehouse_connection_data["username"], $warehouse_connection_data["password"], $warehouse_connection_data["db"]);
        $store_info = array();
        for ($i = 0; $i < count($stores); $i++) {
            if ($stores[$i]["id"] == $db_to_recovery) {
                $store_info = $stores[$i];
            }
        }
        $store_connection = my_sql::custom_connection($store_info["ip_address"], $store_info["username"], $store_info["password"], $store_info["db"]);
        if ($warehouse_connection && $store_connection) {
            $outside_connection->recovery_items_table($warehouse_connection, $store_connection);
        }
    }
    public function search_invoices_($_note)
    {
        self::giveAccessTo(array(2, 4));
        $invoice = $this->model("invoice");
        $settings = $this->model("settings");
        $customers = $this->model("customers");
        $note = filter_var($_note, self::conversion_php_version_filter());
        $payment_method = $settings->get_all_payment_method();
        $payment_method_info = array();
        for ($i = 0; $i < count($payment_method); $i++) {
            $payment_method_info[$payment_method[$i]["id"]] = $payment_method[$i]["method_name"];
        }
        $info = $invoice->getAllInvoices_search_by_note($note, $this->settings_info);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, "<span class=\"inv_click\" onclick=\"show_details_invoice(" . $info[$i]["id"] . ")\">" . self::idFormat_invoice($info[$i]["id"]) . "</span>");
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
            array_push($tmp, self::global_number_formatter($info[$i]["total_value"] + $info[$i]["invoice_discount"], $this->settings_info));
            array_push($tmp, $info[$i]["payment_note"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function to_delete()
    {
        $query = "select * from items where deleted=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $_ids = "(";
        for ($i = 0; $i < count($result); $i++) {
            if ($i < count($result) - 1) {
                $_ids .= $result[$i]["id"] . ",";
            } else {
                $_ids .= $result[$i]["id"];
            }
        }
        echo $_ids . ")";
    }
    public function get_mytec_counters($from, $to)
    {
        $query = "select ct.barcode as barcode,sb.id as reference,sb.nop as name from tonyfrangieh.counters ct,tonyfrangieh.subscribers sb where sb.id=ct.subscriber_id and ct.deleted=0 and ct.deleted=0 and sb.id>" . $from . " and sb.id<=" . $to . " order by ct.id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        echo json_encode($result);
    }
    public function generate_barcode()
    {
        $query = "select * from aytou.counters";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            $bcode = "103YM" . (100 + $i);
            $query = "update aytou.counters set barcode='" . $bcode . "' where id=" . $result[$i]["id"];
        }
    }
    public function print_barcodes_mytec_one_by_one()
    {
        $this->view("printing/barcode_one_by_one");
    }
    public function print_barcodes_mytec($ref, $barcode, $name)
    {
        $settings = $this->model("settings");
        require_once "application/mvc/models/BarcodeGenerator.php";
        $main_root = self::get_main_root();
        $full_path = $main_root . "/tools/barcode_label/";
        $barcode_settings = $settings->get_barcode_local_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
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
        $generator = new barcodeGenerator();
        $barcode_page_size_name = "mytec";
        $printer_name = "Datamax";
        $store_name_chaine = "0#20#95#8#Powered  by  MYTEC";
        $original_price_chaine = "1#20#60#10#Name: " . $name;
        $discount_chaine = "0#1#1#1#1";
        $discounted_price_chaine = "0#1#1#1#1";
        $number_to_print = 1;
        $size_chaine = "0#1#1#1#1";
        $color_chaine = "0#1#1#1#1";
        $barcode_settings_info["barcode_position_x"] = 5;
        $barcode_settings_info["barcode_position_y"] = 20;
        $description_chaine = "1#20#75#10#Reference: " . $ref;
        $barcode_key[0]["mid"] = $barcode;
        if (!file_exists($main_root . "/barcodes/" . $barcode . ".jpg")) {
            $image = $generator->output_image("jpg", $barcode_settings_info["type"], $barcode, $options);
        }
        $cmd = $main_root . "/tools/barcode_label/LabelPrinter \"" . $full_path . "\" \"" . $barcode_page_size_name . "\" \"" . $printer_name . "\" \"" . $store_name_chaine . "\" \"" . $description_chaine . "\" \"" . $original_price_chaine . "\" \"" . $discount_chaine . "\" \"" . $discounted_price_chaine . "\" \"" . $number_to_print . "\" \"" . $main_root . "/barcodes/" . $barcode . ".jpg\" \"" . $barcode_settings_info["barcode_position_x"] . "\" \"" . $barcode_settings_info["barcode_position_y"] . "\" \"" . $size_chaine . "\" \"" . $color_chaine . "\"";
        exec($cmd, $output, $result);
        echo json_encode(array());
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
        $new_price = self::global_number_formatter($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), $this->settings_info);
        $description_chaine = $barcode_settings_info["description_enable"] . "#" . $barcode_settings_info["description_x"] . "#" . $barcode_settings_info["description_y"] . "#" . $barcode_settings_info["description_size"] . "#" . $it_desc;
        $original_price_chaine = $barcode_settings_info["price_enable"] . "#" . $barcode_settings_info["price_x"] . "#" . $barcode_settings_info["price_y"] . "#" . $barcode_settings_info["price_font_size"] . "#Price: " . self::global_number_formatter($item_info[0]["selling_price"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
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
    public function get_due_invoices()
    {
        self::giveAccessTo();
        $invoice = $this->model("invoice");
        $info = $invoice->get_due_invoices();
        echo json_encode($info);
    }
    public function update_phones()
    {
    }
    public function fix_vat()
    {
        $invoice = $this->model("invoice");
        $query = "select * from invoices where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
        }
    }
    public function init_countries_and_cities()
    {
        self::giveAccessTo();
    }
    public function search_invoice($_invoice_id)
    {
        self::giveAccessTo();
        $invoice_id = filter_var($_invoice_id, self::conversion_php_version_filter());
        $invoice = $this->model("invoice");
        $info = array();
        if ($invoice_id[0] == "s") {
            $invoice_id = substr($invoice_id, 1);
            $invoice_info = $invoice->search_invoice_by_ref((int) $invoice_id);
            $info["id"] = $invoice_info[0]["id"];
            $info["display_base_id"] = 1;
            echo json_encode($info);
        } else {
            $info["id"] = $invoice_id;
            $info["display_base_id"] = 0;
            echo json_encode($info);
        }
    }
    public function get_due_cheques($with_data)
    {
        self::giveAccessTo();
        $payments = $this->model("payments");
        $settings = $this->model("settings");
        $info_suppliers = array();
        $info_customers = array();
        if ($with_data == 0) {
            $info_suppliers = $payments->get_due_cheques_suppliers();
            $info_customers = $payments->get_due_cheques_customers();
        } else {
            $info_suppliers = $payments->get_pending_cheques_suppliers();
            $info_customers = $payments->get_pending_cheques_customers();
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
        $banks = $settings->get_banks();
        $banks_info = array();
        for ($i = 0; $i < count($banks); $i++) {
            $banks_info[$banks[$i]["id"]] = $banks[$i]["name"];
        }
        if ($with_data == 0) {
            echo json_encode(array(count($info_suppliers) + count($info_customers)));
        } else {
            $cheques = array();
            $cheques_index = 0;
            for ($i = 0; $i < count($info_suppliers); $i++) {
                $cheques[$cheques_index] = array("timestamp" => strtotime($info_suppliers[$i]["creation_date"]), "id" => "s_" . $info_suppliers[$i]["id"], "value_date" => $info_suppliers[$i]["payment_date"], "value" => $info_suppliers[$i]["payment_value"], "currency" => $info_suppliers[$i]["payment_currency"], "bank_id" => $info_suppliers[$i]["bank_id"], "ref" => $info_suppliers[$i]["reference"], "owner" => $info_suppliers[$i]["payment_owner"], "owner_type" => "Supplier");
                $cheques_index++;
            }
            for ($i = 0; $i < count($info_customers); $i++) {
                $cheques[$cheques_index] = array("timestamp" => strtotime($info_customers[$i]["balance_date"]), "id" => "c_" . $info_customers[$i]["id"], "value_date" => $info_customers[$i]["value_date"], "value" => $info_customers[$i]["balance"], "currency" => $info_customers[$i]["currency_id"], "bank_id" => $info_customers[$i]["bank_id"], "ref" => $info_customers[$i]["reference_nb"], "owner" => $info_customers[$i]["owner"], "owner_type" => "Customer");
                $cheques_index++;
            }
            self::__USORT_TIMESTAMP($cheques);
            $data_array["data"] = array();
            foreach ($cheques as $key => $value) {
                $tmp = array();
                array_push($tmp, $value["id"]);
                array_push($tmp, $value["ref"]);
                array_push($tmp, $value["owner"]);
                array_push($tmp, $value["owner_type"]);
                array_push($tmp, $banks_info[$value["bank_id"]]);
                array_push($tmp, self::date_format_custom($value["value_date"]));
                $this->settings_info["default_currency_symbol"] = "";
                array_push($tmp, self::global_number_formatter($value["value"], $this->settings_info));
                array_push($tmp, $currencies_info[$value["currency"]]["symbole"]);
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($data_array["data"], $tmp);
            }
            echo json_encode($data_array);
        }
    }
    public function resfresh_sync_status()
    {
        $this->checkAuth();
        $store = $this->model("store");
        $sync = $this->model("sync");
        $info = array();
        $stores = $store->getAllStores();
        for ($i = 0; $i < count($stores); $i++) {
            $sync_status = $sync->check_id_sync_pending($stores[$i]["id"]);
            $info[$i]["sync_pending"] = $sync_status;
            $info[$i]["store_id"] = $stores[$i]["id"];
        }
        echo json_encode($info);
    }
    public function reorder_official_invoices_ids()
    {
        $query = "select * from invoices where deleted=0 and total_vat_value>0 and year(creation_date)='2019' order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $start_id = 1;
        for ($i = 0; $i < count($result); $i++) {
            my_sql::query("update invoices set invoice_nb_official=" . $start_id . " where id=" . $result[$i]["id"]);
            $start_id++;
        }
    }
    public function reset_history_prices()
    {
        $query = "select * from items";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
        }
    }
    public function regroup_items()
    {
        $query = "SELECT id,description,count(id) as num FROM `items` group by (`description`) having count(id)>1 ORDER BY items.id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            my_sql::query("update items set item_group=" . $result[$i]["id"] . " where description='" . $result[$i]["description"] . "'");
            my_sql::global_query_sync("update items set item_group=" . $result[$i]["id"] . " where description='" . $result[$i]["description"] . "'");
        }
    }
    public function nd()
    {
        $this->view("dashboard_new");
    }
    public function _default()
    {
        $user = $this->model("user");
        $store = $this->model("store");
        $authorization = $this->model("authorization");
        $data["rate"] = $this->settings_info["usdlbp_rate"];
        $user_info = $user->get_user_by_id($_SESSION["id"]);
        $data["enable_auth"] = 0;
        $data["stores_from"] = $store->getStores_c();
        $data["stores_to"] = $store->getStores_c();
        $data["ga_2fa_enabled"] = $user_info[0]["ga_2fa_enabled"];
        $cookie_name = "skeyUp";
        $athorized = false;
        if ($authorization->user_authorized($_SESSION["id"], $_COOKIE[$cookie_name]) && $_SESSION["hide_critical_data"] == 0) {
            $athorized = true;
        }
        if ($this->settings_info["enable_authorization_code"] == 1 && ($user_info[0]["authorization_required"] == 0 || $athorized == true)) {
            $data["enable_auth"] = 1;
        }
        $data["telegram_enabled"] = 0;
        if ($this->settings_info["telegram_enable"] == 1) {
            $data["telegram_enabled"] = 1;
        }
        $data["stores"] = $store->getAllStores();
        $data["vendor_exist"] = $store->vendor_is_exist();
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        if ($_SESSION["centralize"] == 0) {
            if (ENABLE_NEW_TEMPLATE == 1) {
                $this->view("newtemplate/dashboard", $data);
            } else {
                $this->view("dashboard", $data);
            }
        } else {
            if (WAREHOUSE_CONNECTED == 1) {
                $this->view("dashboard", $data);
            } else {
                $warehouse = $this->model("warehouse");
                $stores = $warehouse->get_stores();
                for ($i = 0; $i < count($stores); $i++) {
                    $cnx = self::get_store_connection($stores[$i]["id"]);
                    $warehouse->sync_clients($cnx, $stores[$i]["id"]);
                }
                for ($i = 0; $i < count($data["stores"]); $i++) {
                    if ($data["stores"][$i]["warehouse"] == 0) {
                        $cnx = self::get_store_connection($data["stores"][$i]["id"]);
                        $warehouse->sync_clients_imei($cnx, $data["stores"][$i]["id"]);
                    }
                }
                for ($i = 0; $i < count($data["stores"]); $i++) {
                    if ($data["stores"][$i]["warehouse"] == 1) {
                        $cnx = self::get_store_connection($data["stores"][$i]["id"]);
                        $warehouse->sync_clients_imei_warehouse_connected($cnx, $data["stores"][$i]["id"]);
                    }
                }
                $this->view("dashboard_global", $data);
            }
        }
    }
    public function backup()
    {
        $this->view("backup");
    }
    public function backupNow()
    {
        $this->checkAuth();
        self::bkp($this->settings_info);
    }
    public function getMostProfitable($_store_id)
    {
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $dashboard = $this->model("dashboard");
        $items = $this->model("items");
        $info = $dashboard->getMostProfitable($store_id);
        for ($i = 0; $i < count($info); $i++) {
            $item_info = $items->get_item($info[$i]["item_id"]);
            $info[$i]["item_name"] = $item_info[0]["description"];
            $info[$i]["item_name"] = $item_info[0]["description"];
            $info[$i]["sum_of_profit"] = self::global_number_formatter($info[$i]["sum_of_profit"], $this->settings_info);
        }
        echo json_encode($info);
    }
    public function getBestSellerLifeTime($_store_id)
    {
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $dashboard = $this->model("dashboard");
        $items = $this->model("items");
        $info = $dashboard->getBestSellerLifeTime($store_id);
        for ($i = 0; $i < count($info); $i++) {
            $item_info = $items->get_item($info[$i]["item_id"]);
            $info[$i]["item_name"] = $item_info[0]["description"];
        }
        echo json_encode($info);
    }
    public function getDailyProfit($_store_id, $_date_range)
    {
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $info["date_range"] = filter_var($_date_range, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $info["start_date"] = NULL;
        $info["end_date"] = NULL;
        if ($info["date_range"] == "today") {
            $info["start_date"] = date("Y-m-d");
            $info["end_date"] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $info["date_range"]);
            $info["start_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $info["end_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $dashboard = $this->model("dashboard");
        $info = $dashboard->getProfitOfDays($store_id, $info);
        echo json_encode($info);
    }
    public function getMonthlyProfit($_store_id)
    {
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $dashboard = $this->model("dashboard");
        $info = $dashboard->getProfitOfMonths($store_id);
        echo json_encode($info);
    }
    public function getTotalSalesByTypes($_store_id, $_date_range)
    {
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $info["date_range"] = filter_var($_date_range, self::conversion_php_version_filter());
        $dashboard = $this->model("dashboard");
        $date_range_tmp = NULL;
        $info["start_date"] = NULL;
        $info["end_date"] = NULL;
        if ($info["date_range"] == "today") {
            $info["start_date"] = date("Y-m-d");
            $info["end_date"] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $info["date_range"]);
            $info["start_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $info["end_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $info_data = $dashboard->getTotalSalesByTypes($store_id, $info);
        $info = array();
        $info["cash"] = 0;
        $info["cc"] = 0;
        $info["cheques"] = 0;
        for ($i = 0; $i < count($info_data); $i++) {
            if ($info_data[$i]["payment_method"] == 1) {
                $info["cash"] = $info_data[$i]["sum_value"];
            }
            if ($info_data[$i]["payment_method"] == 2) {
                $info["cheques"] = $info_data[$i]["sum_value"];
            }
            if ($info_data[$i]["payment_method"] == 3) {
                $info["cc"] = $info_data[$i]["sum_value"];
            }
        }
        echo json_encode($info);
    }
    public function get_global_info_new($_store_id, $_date_range)
    {
        $info = array();
        self::update_online_customers($this->settings_info);
        $invoice = $this->model("invoice");
        $quotations = $this->model("quotations");
        $expenses = $this->model("expenses");
        $items = $this->model("items");
        $mobile = $this->model("mobileStore");
        $suppliers = $this->model("suppliers");
        $creditnote = $this->model("creditnote");
        $payments = $this->model("payments");
        $customers = $this->model("customers");
        $wasting = $this->model("wasting");
        $tasks = $this->model("tasks");
        $backup = $this->model("backup");
        $items_expired_soon = $items->get_expired_items_nb($_SESSION["store_id"], (double) $this->settings_info["expiry_interval_days"]);
        $info_suppliers = $payments->get_pending_cheques_suppliers();
        $info_customers = $payments->get_pending_cheques_customers();
        $info_suppliers_due_date = $payments->get_pending_cheques_suppliers_due_date();
        $info_customers_due_date = $payments->get_pending_cheques_customers_due_date();
        $total_starting_balance = $suppliers->get_all_starting_balance();
        $totalSuppliersInvoicesValue = $suppliers->getAllSuppliersInvoicesValue();
        $totalSuppliersPaid = $suppliers->getAllSuppliersPaid();
        $totalSuppliersDebitNote = $suppliers->getAllSuppliersDebitNote();
        $total_customers_starting_balance = $customers->get_all_starting_balance();
        $all_unpaid_customers_invoices = $invoice->getAllUnpaid();
        $creditnote_sum = $creditnote->get_all_sum_creditnote();
        $total_payments = $payments->getAllPaymentForCustomer();
        $tasks_pending = $tasks->get_pending_task_nb();
        $info["store_id"] = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $info["date_range"] = filter_var($_date_range, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $info["start_date"] = NULL;
        $info["end_date"] = NULL;
        if ($info["date_range"] == "today") {
            $info["start_date"] = date("Y-m-d");
            $info["end_date"] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $info["date_range"]);
            $info["start_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $info["end_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $info_data = array();
        $_total_cash_sales = $invoice->getCashSalesInvoicesByDateAndStore($info);
        $_total_debts_sales = $invoice->getSalesNotPaidByStoreAndDate($info);
        $_total_creditcard_sales = $invoice->getSalesByCreditCardByStoreAndDate($info);
        $_total_cheque_sales = $invoice->getSalesByChequeByStoreAndCheque($info);
        $_total_sales = $_total_cash_sales + $_total_debts_sales + $_total_creditcard_sales + $_total_cheque_sales;
        $_total_profit = $invoice->total_profit($info);
        $_total_returns = $invoice->getTotalReturns($info);
        $_total_sold_items = $invoice->getTotalSoldItems($info);
        $_total_suppliers_payment = $suppliers->getTotalSuppliersPaymentsByDateRange($info);
        $_total_customers_payments = $customers->getTotalCustomersPaymentsByDateRange($info);
        $_total_wasting = $wasting->get_total_wasting($info);
        $info_data["total_cash_sales"] = self::global_number_formatter($_total_cash_sales, $this->settings_info);
        $info_data["total_debts_sales"] = self::global_number_formatter($_total_debts_sales, $this->settings_info);
        $info_data["total_creditcard_sales"] = self::global_number_formatter($_total_creditcard_sales, $this->settings_info);
        $info_data["total_cheque_sales"] = self::global_number_formatter($_total_cheque_sales, $this->settings_info);
        $info_data["default_currency_symbol"] = $_SESSION["currency_symbol"];
        $info_data["total_profit"] = 0;
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["total_profit"] = self::global_number_formatter($_total_profit, $this->settings_info);
        } else {
            $info_data["total_profit"] = self::critical_data();
            $info_data["total_debts_sales"] = self::critical_data();
            $info_data["total_creditcard_sales"] = self::critical_data();
            $info_data["total_cheque_sales"] = self::critical_data();
        }
        $info_data["total_sales"] = self::global_number_formatter($_total_sales, $this->settings_info);
        if ($_SESSION["hide_critical_data"] == 1) {
            $info_data["total_sales"] = self::critical_data();
            $info_data["total_cash_sales"] = self::critical_data();
        }
        $info_data["total_returns"] = self::global_number_formatter($_total_returns, $this->settings_info);
        $info_data["mobile_stock_value_alfa"] = self::global_number_formatter($mobile->get_mobile_stock_value_alfa(), $this->settings_info);
        $info_data["mobile_stock_value_mtc"] = self::global_number_formatter($mobile->get_mobile_stock_value_mtc(), $this->settings_info);
        $info_data["pending_cheques_nb"] = count($info_suppliers) + count($info_customers);
        $info_data["pending_cheques_nb_due"] = $info_suppliers_due_date + $info_customers_due_date;
        $info_data["pending_quotations_nb"] = $quotations->get_pending_nb();
        $info_data["pending_tasks"] = $tasks_pending[0]["nb"];
        $info_data["expired_nb"] = count($items_expired_soon);
        $int_call_info = $invoice->get_internationnal_calls_invoices();
        $info_data["interna_call_balance"] = self::global_number_formatter($this->settings_info["international_calls_balance"], $this->settings_info);
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            if (0 < $info_data["total_sales"]) {
                $info_data["profit_margin"] = self::global_number_formatter($_total_profit / $_total_sales * 100, $this->settings_info);
            } else {
                $info_data["profit_margin"] = 0;
            }
        } else {
            $info_data["profit_margin"] = self::critical_data();
        }
        $items_info = $items->get_items_in_store($_SESSION["store_id"], 0, 0);
        $total_stock_cost = 0;
        for ($i = 0; $i < count($items_info); $i++) {
            if (0 < $items_info[$i]["quantity"] && $items_info[$i]["packs_nb"] == 0) {
                $tmp = 0;
                if ($items_info[$i]["vat"]) {
                    $tmp = floatval($items_info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                } else {
                    $tmp = floatval($items_info[$i]["buying_cost"]);
                }
                $total_stock_cost += $tmp * $items_info[$i]["quantity"];
            }
            if ($items_info[$i]["quantity"] == 0 && 0 < $items_info[$i]["packs_nb"]) {
                $tmp = 0;
                if ($items_info[$i]["vat"]) {
                    $tmp = floatval($items_info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                } else {
                    $tmp = floatval($items_info[$i]["buying_cost"]);
                }
                $total_stock_cost += $tmp * $items_info[$i]["packs_nb"];
            }
        }
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["total_stock_cost"] = self::global_number_formatter($total_stock_cost, $this->settings_info);
            $info_data["total_stock_cost_clear"] = $total_stock_cost;
        } else {
            $info_data["total_stock_cost"] = self::critical_data();
        }
        $daterange[0] = $info["start_date"];
        $daterange[1] = $info["end_date"];
        $exprense_result = $expenses->getExpensesByIntervalOfDate($info["store_id"], $daterange);
        $info_data["total_expenses"] = self::global_number_formatter($exprense_result[0]["sum"], $this->settings_info);
        $info_data["total_supplier_remain"] = self::global_number_formatter($totalSuppliersInvoicesValue[0]["sum"] + $total_starting_balance[0]["sum"] - $totalSuppliersPaid[0]["sum"] - $totalSuppliersDebitNote[0]["sum"], $this->settings_info);
        $infodate = array();
        $infodate[0] = "all";
        $infodate[1] = "all";
        $info_data["total_supplier_remain_usd"] = self::global_number_formatter($suppliers->get_balances_suppliers(1, 0, $infodate, 0, 0), $this->settings_info);
        $info_data["total_supplier_remain_lbp"] = self::global_number_formatter($suppliers->get_balances_suppliers(2, 0, $infodate, 0, 0), $this->settings_info);
        $info_data["total_customers_remain"] = self::global_number_formatter($all_unpaid_customers_invoices[0]["sum"] + $total_customers_starting_balance[0]["sum"] - $creditnote_sum[0]["sum"] - $total_payments[0]["sum"], $this->settings_info);
        $info_data["total_customers_remain_p"] = self::global_number_formatter(abs($all_unpaid_customers_invoices[0]["sum"] + $total_customers_starting_balance[0]["sum"] - $creditnote_sum[0]["sum"] - $total_payments[0]["sum"]), $this->settings_info);
        $due_inv = $invoice->get_due_invoices_nb();
        $info_data["total_customers_due_invoices"] = $due_inv[0]["num"];
        $info_data["warn"] = $_SESSION["warning_expire"];
        $info_data["warn_date"] = date("l jS \\of F Y", $info_data["warn"]);
        $info_data["total_suppliers_payment"] = self::global_number_formatter($_total_suppliers_payment, $this->settings_info);
        $info_data["total_customers_payments"] = self::global_number_formatter($_total_customers_payments, $this->settings_info);
        $info_data["total_wasting"] = self::global_number_formatter($_total_wasting[0]["sum"], $this->settings_info);
        $info_data["total_items"] = floatval($_total_sold_items);
        $info_data["backupnow"] = 0;
        $last_backup = strtotime($backup->get_last_backup());
        if (!self::is_on_server() && $this->settings_info["backup_diffrence"] < time() - $last_backup) {
            $info_data["backupnow"] = 1;
            $backup->update_last_backup();
        }
        $totalcashbox = 0;
        $totalcashbox += $_total_cash_sales + $_total_customers_payments - $exprense_result[0]["sum"] - $_total_suppliers_payment;
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["cashbox"] = self::global_number_formatter($totalcashbox, $this->settings_info);
        } else {
            $info_data["cashbox"] = self::critical_data();
        }
        $total_taxes_freight = $invoice->get_total_taxes_freight($info);
        $total_taxes = $total_taxes_freight[0]["sum_taxes"];
        $total_freight = $total_taxes_freight[0]["sum_freight"];
        $info_data["total_invoices_taxes"] = self::global_number_formatter($total_taxes, $this->settings_info);
        $info_data["total_invoices_freight"] = self::global_number_formatter($total_freight, $this->settings_info);
        echo json_encode($info_data);
    }
    public function get_global_info_new_warehouse($_store_id, $_date_range)
    {
        $invoice = $this->model("invoice");
        $store_model = $this->model("store");
        $expenses = $this->model("expenses");
        $wasting = $this->model("wasting");
        $items = $this->model("items");
        $suppliers = $this->model("suppliers");
        $customers = $this->model("customers");
        $info = array();
        $info["store_id"] = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $info["date_range"] = filter_var($_date_range, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $info["start_date"] = NULL;
        $info["end_date"] = NULL;
        if ($info["date_range"] == "today") {
            $info["start_date"] = date("Y-m-d");
            $info["end_date"] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $info["date_range"]);
            $info["start_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $info["end_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $stores = $store_model->getAllStores();
        $info_data = array();
        $_total_cash_sales = 0;
        $_total_debts_sales = 0;
        $_total_creditcard_sales = 0;
        $_total_cheque_sales = 0;
        $total_expenses = 0;
        $_total_returns = 0;
        $_total_wasting = 0;
        $_total_sold_items = 0;
        $total_taxes = 0;
        $total_freight = 0;
        $_total_profit = 0;
        $_total_customers_payments = 0;
        $daterange = array();
        $daterange[0] = $info["start_date"];
        $daterange[1] = $info["end_date"];
        if (0 < $info["store_id"]) {
            $cnx = self::get_store_connection($info["store_id"]);
            $_total_cash_sales = $invoice->getCashSalesInvoicesByDateAndStore_remote($info, $cnx, $info["store_id"]);
            $_total_debts_sales = $invoice->getSalesNotPaidByStoreAndDate_remote($info, $cnx, $info["store_id"]);
            $_total_creditcard_sales = $invoice->getSalesByCreditCardByStoreAndDate_remote($info, $cnx, $info["store_id"]);
            $_total_cheque_sales = $invoice->getSalesByChequeByStoreAndCheque_remote($info, $cnx, $info["store_id"]);
            $total_expenses = $expenses->getExpensesByIntervalOfDate_remote($info["store_id"], $daterange, $cnx);
            $_total_returns = $invoice->getTotalReturns_remote($info, $cnx);
            $_total_wasting = $wasting->get_total_wasting_remote($info, $cnx);
            $_total_customers_payments = $customers->getTotalCustomersPaymentsByDateRange_remote($info, $cnx);
            $_total_sold_items = $invoice->getTotalSoldItems_remote($info, $cnx);
            $total_taxes_freight = $invoice->get_total_taxes_freight_remote($info, $cnx);
            $total_taxes = $total_taxes_freight[0]["sum_taxes"];
            $total_freight = $total_taxes_freight[0]["sum_freight"];
            $_total_profit = $invoice->total_profit_remote($info, $cnx);
        } else {
            for ($i = 0; $i < count($stores); $i++) {
                $cnx = self::get_store_connection($stores[$i]["id"]);
                $_total_cash_sales += $invoice->getCashSalesInvoicesByDateAndStore_remote($info, $cnx, $stores[$i]["id"]);
                $_total_debts_sales += $invoice->getSalesNotPaidByStoreAndDate_remote($info, $cnx, $stores[$i]["id"]);
                $_total_creditcard_sales += $invoice->getSalesByCreditCardByStoreAndDate_remote($info, $cnx, $stores[$i]["id"]);
                $_total_cheque_sales = $invoice->getSalesByChequeByStoreAndCheque_remote($info, $cnx, $stores[$i]["id"]);
                $total_expenses += $expenses->getExpensesByIntervalOfDate_remote($stores[$i]["id"], $daterange, $cnx);
                $_total_returns += $invoice->getTotalReturns_remote($info, $cnx);
                $_total_wasting += $wasting->get_total_wasting_remote($info, $cnx);
                $_total_customers_payments += $customers->getTotalCustomersPaymentsByDateRange_remote($info, $cnx);
                $_total_sold_items += $invoice->getTotalSoldItems_remote($info, $cnx);
                $total_taxes_freight = $invoice->get_total_taxes_freight_remote($info, $cnx);
                $total_taxes += $total_taxes_freight[0]["sum_taxes"];
                $total_freight += $total_taxes_freight[0]["sum_freight"];
                $_total_profit += $invoice->total_profit_remote($info, $cnx);
            }
        }
        $_total_suppliers_payment = $suppliers->getTotalSuppliersPaymentsByDateRange($info);
        $_total_sales = $_total_cash_sales + $_total_debts_sales + $_total_creditcard_sales + $_total_cheque_sales;
        $info_data["total_invoices_taxes"] = self::global_number_formatter($total_taxes, $this->settings_info);
        $info_data["total_invoices_freight"] = self::global_number_formatter($total_freight, $this->settings_info);
        $info_data["total_cash_sales"] = self::global_number_formatter($_total_cash_sales, $this->settings_info);
        $info_data["total_debts_sales"] = self::global_number_formatter($_total_debts_sales, $this->settings_info);
        $info_data["total_creditcard_sales"] = self::global_number_formatter($_total_creditcard_sales, $this->settings_info);
        $info_data["total_cheque_sales"] = self::global_number_formatter($_total_cheque_sales, $this->settings_info);
        $info_data["total_expenses"] = self::global_number_formatter($total_expenses, $this->settings_info);
        $info_data["total_returns"] = self::global_number_formatter($_total_returns, $this->settings_info);
        $info_data["total_profit"] = self::global_number_formatter($_total_profit, $this->settings_info);
        $info_data["total_suppliers_payment"] = self::global_number_formatter($_total_suppliers_payment, $this->settings_info);
        if (0 < $_total_sales) {
            $info_data["profit_margin"] = self::global_number_formatter($_total_profit / $_total_sales * 100, $this->settings_info);
        } else {
            $info_data["profit_margin"] = self::global_number_formatter(0, $this->settings_info);
        }
        $info_data["total_wasting"] = self::global_number_formatter($_total_wasting, $this->settings_info);
        $info_data["total_customers_payments"] = self::global_number_formatter($_total_customers_payments, $this->settings_info);
        $info_data["total_items"] = self::global_number_formatter($_total_sold_items, $this->settings_info);
        $info_data["total_sales"] = self::global_number_formatter($_total_sales, $this->settings_info);
        $info_data["cashbox"] = self::global_number_formatter($_total_cash_sales + $_total_customers_payments - $total_expenses - $_total_suppliers_payment, $this->settings_info);
        $total_stock_cost = 0;
        $items_info = $items->get_items_in_store($_SESSION["store_id"], 0, 0);
        for ($i = 0; $i < count($items_info); $i++) {
            if (0 < $items_info[$i]["quantity"] && $items_info[$i]["packs_nb"] == 0) {
                $tmp = 0;
                if ($items_info[$i]["vat"]) {
                    $tmp = floatval($items_info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                } else {
                    $tmp = floatval($items_info[$i]["buying_cost"]);
                }
                $total_stock_cost += $tmp * $items_info[$i]["quantity"];
            }
            if ($items_info[$i]["quantity"] == 0 && 0 < $items_info[$i]["packs_nb"]) {
                $tmp = 0;
                if ($items_info[$i]["vat"]) {
                    $tmp = floatval($items_info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                } else {
                    $tmp = floatval($items_info[$i]["buying_cost"]);
                }
                $total_stock_cost += $tmp * $items_info[$i]["packs_nb"];
            }
        }
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["total_stock_cost"] = self::global_number_formatter($total_stock_cost, $this->settings_info);
            $info_data["total_stock_cost_clear"] = $total_stock_cost;
        } else {
            $info_data["total_stock_cost"] = self::critical_data();
        }
        $infodate = array();
        $infodate[0] = "all";
        $infodate[1] = "all";
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["total_supplier_remain_usd"] = self::global_number_formatter($suppliers->get_balances_suppliers(1, 0, $infodate, 0, 0), $this->settings_info);
        } else {
            $info_data["total_supplier_remain_usd"] = self::critical_data();
        }
        echo json_encode($info_data);
    }
    public function get_global_info_new_warehouse_($_store_id, $_date_range)
    {
        $info = array();
        self::update_online_customers($this->settings_info);
        $invoice = $this->model("invoice");
        $quotations = $this->model("quotations");
        $expenses = $this->model("expenses");
        $items = $this->model("items");
        $mobile = $this->model("mobileStore");
        $suppliers = $this->model("suppliers");
        $creditnote = $this->model("creditnote");
        $payments = $this->model("payments");
        $customers = $this->model("customers");
        $wasting = $this->model("wasting");
        $tasks = $this->model("tasks");
        $backup = $this->model("backup");
        $items_expired_soon = $items->get_expired_items_nb($_SESSION["store_id"], (double) $this->settings_info["expiry_interval_days"]);
        $info_suppliers = $payments->get_pending_cheques_suppliers();
        $info_customers = $payments->get_pending_cheques_customers();
        $info_suppliers_due_date = $payments->get_pending_cheques_suppliers_due_date();
        $info_customers_due_date = $payments->get_pending_cheques_customers_due_date();
        $total_starting_balance = $suppliers->get_all_starting_balance();
        $totalSuppliersInvoicesValue = $suppliers->getAllSuppliersInvoicesValue();
        $totalSuppliersPaid = $suppliers->getAllSuppliersPaid();
        $totalSuppliersDebitNote = $suppliers->getAllSuppliersDebitNote();
        $total_customers_starting_balance = $customers->get_all_starting_balance();
        $all_unpaid_customers_invoices = $invoice->getAllUnpaid();
        $creditnote_sum = $creditnote->get_all_sum_creditnote();
        $total_payments = $payments->getAllPaymentForCustomer();
        $tasks_pending = $tasks->get_pending_task_nb();
        $info["store_id"] = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $info["date_range"] = filter_var($_date_range, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $info["start_date"] = NULL;
        $info["end_date"] = NULL;
        if ($info["date_range"] == "today") {
            $info["start_date"] = date("Y-m-d");
            $info["end_date"] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $info["date_range"]);
            $info["start_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $info["end_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $info_data = array();
        $_total_cash_sales = $invoice->getCashSalesInvoicesByDateAndStore($info);
        $_total_debts_sales = $invoice->getSalesNotPaidByStoreAndDate($info);
        $_total_creditcard_sales = $invoice->getSalesByCreditCardByStoreAndDate($info);
        $_total_cheque_sales = $invoice->getSalesByChequeByStoreAndCheque($info);
        $_total_sales = $_total_cash_sales + $_total_debts_sales + $_total_creditcard_sales + $_total_cheque_sales;
        $_total_profit = $invoice->total_profit($info);
        $_total_returns = $invoice->getTotalReturns($info);
        $_total_sold_items = $invoice->getTotalSoldItems($info);
        $_total_suppliers_payment = $suppliers->getTotalSuppliersPaymentsByDateRange($info);
        $_total_customers_payments = $customers->getTotalCustomersPaymentsByDateRange($info);
        $_total_wasting = $wasting->get_total_wasting($info);
        $info_data["total_cash_sales"] = self::global_number_formatter($_total_cash_sales, $this->settings_info);
        $info_data["total_debts_sales"] = self::global_number_formatter($_total_debts_sales, $this->settings_info);
        $info_data["total_creditcard_sales"] = self::global_number_formatter($_total_creditcard_sales, $this->settings_info);
        $info_data["total_cheque_sales"] = self::global_number_formatter($_total_cheque_sales, $this->settings_info);
        $info_data["default_currency_symbol"] = $_SESSION["currency_symbol"];
        $info_data["total_profit"] = 0;
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["total_profit"] = self::global_number_formatter($_total_profit, $this->settings_info);
        } else {
            $info_data["total_profit"] = self::critical_data();
            $info_data["total_debts_sales"] = self::critical_data();
            $info_data["total_creditcard_sales"] = self::critical_data();
            $info_data["total_cheque_sales"] = self::critical_data();
        }
        $info_data["total_sales"] = self::global_number_formatter($_total_sales, $this->settings_info);
        if ($_SESSION["hide_critical_data"] == 1) {
            $info_data["total_sales"] = self::critical_data();
            $info_data["total_cash_sales"] = self::critical_data();
        }
        $info_data["total_returns"] = self::global_number_formatter($_total_returns, $this->settings_info);
        $info_data["mobile_stock_value_alfa"] = self::global_number_formatter($mobile->get_mobile_stock_value_alfa(), $this->settings_info);
        $info_data["mobile_stock_value_mtc"] = self::global_number_formatter($mobile->get_mobile_stock_value_mtc(), $this->settings_info);
        $info_data["pending_cheques_nb"] = count($info_suppliers) + count($info_customers);
        $info_data["pending_cheques_nb_due"] = $info_suppliers_due_date + $info_customers_due_date;
        $info_data["pending_quotations_nb"] = $quotations->get_pending_nb();
        $info_data["pending_tasks"] = $tasks_pending[0]["nb"];
        $info_data["expired_nb"] = count($items_expired_soon);
        $info_data["interna_call_balance"] = self::global_number_formatter($this->settings_info["international_calls_balance"], $this->settings_info);
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            if (0 < $info_data["total_sales"]) {
                $info_data["profit_margin"] = self::global_number_formatter($_total_profit / $_total_sales * 100, $this->settings_info);
            } else {
                $info_data["profit_margin"] = 0;
            }
        } else {
            $info_data["profit_margin"] = self::critical_data();
        }
        $items_info = $items->get_items_in_store($_SESSION["store_id"], 0, 0);
        $total_stock_cost = 0;
        for ($i = 0; $i < count($items_info); $i++) {
            if (0 < $items_info[$i]["quantity"] && $items_info[$i]["packs_nb"] == 0) {
                $tmp = 0;
                if ($items_info[$i]["vat"]) {
                    $tmp = floatval($items_info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                } else {
                    $tmp = floatval($items_info[$i]["buying_cost"]);
                }
                $total_stock_cost += $tmp * $items_info[$i]["quantity"];
            }
            if ($items_info[$i]["quantity"] == 0 && 0 < $items_info[$i]["packs_nb"]) {
                $tmp = 0;
                if ($items_info[$i]["vat"]) {
                    $tmp = floatval($items_info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                } else {
                    $tmp = floatval($items_info[$i]["buying_cost"]);
                }
                $total_stock_cost += $tmp * $items_info[$i]["packs_nb"];
            }
        }
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["total_stock_cost"] = self::global_number_formatter($total_stock_cost, $this->settings_info);
            $info_data["total_stock_cost_clear"] = $total_stock_cost;
        } else {
            $info_data["total_stock_cost"] = self::critical_data();
        }
        $daterange[0] = $info["start_date"];
        $daterange[1] = $info["end_date"];
        $exprense_result = $expenses->getExpensesByIntervalOfDate($info["store_id"], $daterange);
        $info_data["total_expenses"] = self::global_number_formatter($exprense_result[0]["sum"], $this->settings_info);
        $info_data["total_supplier_remain"] = self::global_number_formatter($totalSuppliersInvoicesValue[0]["sum"] + $total_starting_balance[0]["sum"] - $totalSuppliersPaid[0]["sum"] - $totalSuppliersDebitNote[0]["sum"], $this->settings_info);
        $infodate = array();
        $infodate[0] = "all";
        $infodate[1] = "all";
        $info_data["total_supplier_remain_usd"] = self::global_number_formatter($suppliers->get_balances_suppliers(1, 0, $infodate, 0, 0), $this->settings_info);
        $info_data["total_supplier_remain_lbp"] = self::global_number_formatter($suppliers->get_balances_suppliers(2, 0, $infodate, 0, 0), $this->settings_info);
        $info_data["total_customers_remain"] = self::global_number_formatter($all_unpaid_customers_invoices[0]["sum"] + $total_customers_starting_balance[0]["sum"] - $creditnote_sum[0]["sum"] - $total_payments[0]["sum"], $this->settings_info);
        $info_data["total_customers_remain_p"] = self::global_number_formatter(abs($all_unpaid_customers_invoices[0]["sum"] + $total_customers_starting_balance[0]["sum"] - $creditnote_sum[0]["sum"] - $total_payments[0]["sum"]), $this->settings_info);
        $due_inv = $invoice->get_due_invoices_nb();
        $info_data["total_customers_due_invoices"] = $due_inv[0]["num"];
        $info_data["warn"] = $_SESSION["warning_expire"];
        $info_data["warn_date"] = date("l jS \\of F Y", $info_data["warn"]);
        $info_data["total_suppliers_payment"] = self::global_number_formatter($_total_suppliers_payment, $this->settings_info);
        $info_data["total_customers_payments"] = self::global_number_formatter($_total_customers_payments, $this->settings_info);
        $info_data["total_wasting"] = self::global_number_formatter($_total_wasting[0]["sum"], $this->settings_info);
        $info_data["total_items"] = floatval($_total_sold_items);
        $info_data["backupnow"] = 0;
        $last_backup = strtotime($backup->get_last_backup());
        if (!self::is_on_server() && $this->settings_info["backup_diffrence"] < time() - $last_backup) {
            $info_data["backupnow"] = 1;
            $backup->update_last_backup();
        }
        $totalcashbox = 0;
        $totalcashbox += $_total_cash_sales + $_total_customers_payments - $exprense_result[0]["sum"] - $_total_suppliers_payment;
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info_data["cashbox"] = self::global_number_formatter($totalcashbox, $this->settings_info);
        } else {
            $info_data["cashbox"] = self::critical_data();
        }
        $total_taxes_freight = $invoice->get_total_taxes_freight($info);
        $total_taxes = $total_taxes_freight[0]["sum_taxes"];
        $total_freight = $total_taxes_freight[0]["sum_freight"];
        $info_data["total_invoices_taxes"] = self::global_number_formatter($total_taxes, $this->settings_info);
        $info_data["total_invoices_freight"] = self::global_number_formatter($total_freight, $this->settings_info);
        echo json_encode($info_data);
    }
    public function temp_disable_activation()
    {
        $_SESSION["warning_expire"] = 0;
        echo json_encode(array());
    }
    public function get_global_info($_store_id)
    {
        $dashboard = $this->model("dashboard");
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $info = array();
        $low_items_num = $dashboard->get_low_items_num($store_id);
        $suppliers_num = $dashboard->get_suppliers_num();
        $users_num = $dashboard->get_users_num();
        $items_num = $dashboard->get_items_num($store_id);
        $total_profit = $dashboard->get_total_profit($store_id);
        $total_debts = $dashboard->get_total_debts($store_id);
        $mtc_balance = $dashboard->total_mtc_balance($store_id);
        $alfa_balance = $dashboard->total_alfa_balance($store_id);
        $info["items_low"] = $low_items_num[0]["num"];
        $info["suppliers_num"] = $suppliers_num[0]["num"];
        $info["users_num"] = $users_num[0]["num"];
        $info["items_num"] = (double) $items_num[0]["num"];
        $info["total_profit"] = $total_profit[0]["profit"];
        $info["total_debts"] = $total_debts[0]["sum_of_debts"];
        echo json_encode($info);
    }
    public function logout()
    {
        session_unset();
        session_destroy();
        header("location: ./");
    }
}

?>