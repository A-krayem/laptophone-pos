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
class settings_info extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        if (self::licenseExpired() == true) {
        }
    }
    public function getSettingsInfo()
    {
        $settings_info = self::getSettings();
        $backup = $this->model("backup");
        $settings_info["backupnow"] = 0;
        $last_backup = strtotime($backup->get_last_backup());
        if ($settings_info["backup_diffrence"] < time() - $last_backup) {
            $settings_info["backupnow"] = 1;
            $backup->update_last_backup();
        }
        echo json_encode($settings_info);
    }
    public function enable_disable($setting_name, $value)
    {
        self::giveAccessTo();
        $settings = $this->model("settings");
        $settings->enable_disable($setting_name, $value);
        echo json_encode(array());
    }
    public function update_barcode_para()
    {
        self::giveAccessTo();
        $settings = $this->model("settings");

        $store_name_ed = trim(filter_input(INPUT_POST, "store_name_ed", self::conversion_php_version_filter()));
        if ($store_name_ed == "on") {
            $store_name_ed = 1;
        } else {
            $store_name_ed = 0;
        }
        $settings->update_local_barcode_value($store_name_ed, "store_name_enable");
        $store_name_spinner_left = trim(filter_input(INPUT_POST, "store_name_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($store_name_spinner_left, "store_name_x");
        $store_name_spinner_top = trim(filter_input(INPUT_POST, "store_name_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($store_name_spinner_top, "store_name_y");
        $store_name_font_size = trim(filter_input(INPUT_POST, "store_name_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($store_name_font_size, "store_name_font_size");


        $sku_ed = trim(filter_input(INPUT_POST, "sku_ed", self::conversion_php_version_filter()));
        if ($sku_ed == "on") {
            $sku_ed = 1;
        } else {
            $sku_ed = 0;
        }
        $settings->update_local_barcode_value($sku_ed, "enable_sku");
        $sku_spinner_left = trim(filter_input(INPUT_POST, "sku_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($sku_spinner_left, "sku_x");
        $sku_spinner_top = trim(filter_input(INPUT_POST, "sku_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($sku_spinner_top, "sku_y");
        $sku_font_size = trim(filter_input(INPUT_POST, "sku_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($sku_font_size, "sku_font_size");







        $description_ed = trim(filter_input(INPUT_POST, "description_ed", self::conversion_php_version_filter()));
        if ($description_ed == "on") {
            $description_ed = 1;
        } else {
            $description_ed = 0;
        }
        $settings->update_local_barcode_value($description_ed, "description_enable");
        $description_spinner_left = trim(filter_input(INPUT_POST, "description_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($description_spinner_left, "description_x");
        $description_spinner_top = trim(filter_input(INPUT_POST, "description_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($description_spinner_top, "description_y");
        $description_font_size = trim(filter_input(INPUT_POST, "description_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($description_font_size, "description_size");
        $description_max = trim(filter_input(INPUT_POST, "description_max", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($description_max, "description_max_size");

        $price_ed = trim(filter_input(INPUT_POST, "price_ed", self::conversion_php_version_filter()));
        if ($price_ed == "on") {
            $price_ed = 1;
        } else {
            $price_ed = 0;
        }
        $settings->update_local_barcode_value($price_ed, "price_enable");
        $price_spinner_left = trim(filter_input(INPUT_POST, "price_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($price_spinner_left, "price_x");
        $price_spinner_top = trim(filter_input(INPUT_POST, "price_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($price_spinner_top, "price_y");
        $price_font_size = trim(filter_input(INPUT_POST, "price_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($price_font_size, "price_font_size");

        $discount_ed = trim(filter_input(INPUT_POST, "discount_ed", self::conversion_php_version_filter()));
        if ($discount_ed == "on") {
            $discount_ed = 1;
        } else {
            $discount_ed = 0;
        }
        $settings->update_local_barcode_value($discount_ed, "discount_enable");
        $discount_spinner_left = trim(filter_input(INPUT_POST, "discount_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($discount_spinner_left, "discount_x");
        $discount_spinner_top = trim(filter_input(INPUT_POST, "discount_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($discount_spinner_top, "discount_y");
        $discount_font_size = trim(filter_input(INPUT_POST, "discount_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($discount_font_size, "discount_font_size");

        $after_discount_ed = trim(filter_input(INPUT_POST, "after_discount_ed", self::conversion_php_version_filter()));
        if ($after_discount_ed == "on") {
            $after_discount_ed = 1;
        } else {
            $after_discount_ed = 0;
        }
        $settings->update_local_barcode_value($after_discount_ed, "price_after_discount_enable");
        $after_discount_spinner_left = trim(filter_input(INPUT_POST, "after_discount_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($after_discount_spinner_left, "price_after_discount_x");
        $after_discount_spinner_top = trim(filter_input(INPUT_POST, "after_discount_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($after_discount_spinner_top, "price_after_discount_y");
        $after_discount_font_size = trim(filter_input(INPUT_POST, "after_discount_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($after_discount_font_size, "price_after_discount_size");
        $barcode_ed = trim(filter_input(INPUT_POST, "barcode_ed", self::conversion_php_version_filter()));
        if ($barcode_ed == "on") {
            $barcode_ed = 1;
        } else {
            $barcode_ed = 0;
        }
        $settings->update_local_barcode_value($barcode_ed, "barcode_enable");
        $barcode_spinner_left = trim(filter_input(INPUT_POST, "barcode_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($barcode_spinner_left, "barcode_position_x");
        $barcode_spinner_top = trim(filter_input(INPUT_POST, "barcode_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($barcode_spinner_top, "barcode_position_y");

        $size_ed = trim(filter_input(INPUT_POST, "size_ed", self::conversion_php_version_filter()));
        if ($size_ed == "on") {
            $size_ed = 1;
        } else {
            $size_ed = 0;
        }
        $settings->update_local_barcode_value($size_ed, "size_enable");
        $size_spinner_left = trim(filter_input(INPUT_POST, "size_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($size_spinner_left, "size_x");
        $size_spinner_top = trim(filter_input(INPUT_POST, "size_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($size_spinner_top, "size_y");
        $size_font_size = trim(filter_input(INPUT_POST, "size_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($size_font_size, "size_font_size");

        $color_ed = trim(filter_input(INPUT_POST, "color_ed", self::conversion_php_version_filter()));
        if ($color_ed == "on") {
            $color_ed = 1;
        } else {
            $color_ed = 0;
        }
        $settings->update_local_barcode_value($color_ed, "color_enable");
        $color_spinner_left = trim(filter_input(INPUT_POST, "color_spinner_left", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($color_spinner_left, "color_x");
        $color_spinner_top = trim(filter_input(INPUT_POST, "color_spinner_top", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($color_spinner_top, "color_y");
        $color_font_color = trim(filter_input(INPUT_POST, "color_font_size", FILTER_SANITIZE_NUMBER_INT));
        $settings->update_local_barcode_value($color_font_color, "color_font_size");

        echo json_encode(array());
    }
    public function getIfToUpdate()
    {
        self::giveAccessTo();
        $settings_info = self::getSettings();
        $settings = $this->model("settings");
        $fl = (int) $settings_info["auto_update_items_qty_in_admin"];
        $old_fl = $fl;
        if (0 < $fl) {
            $fl -= 1;
            $settings->update_value($fl, "auto_update_items_qty_in_admin");
        }
        echo json_encode(array($old_fl));
    }
    public function save_rate($_id, $_value_rate)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $value_rate = filter_var($_value_rate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $currency = $this->model("currency");
        $currency->save_rate($id, $value_rate);
        echo json_encode(array());
    }
    public function get_cashin_out_needed_data()
    {
        self::giveAccessTo(array(2, 4));
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllCurrenciesEvenDeleted();
        $info["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $info["currencies"][$i]["id"] = $all_currencies[$i]["id"];
            $info["currencies"][$i]["name"] = $all_currencies[$i]["name"];
            $info["currencies"][$i]["symbole"] = $all_currencies[$i]["symbole"];
            $info["currencies"][$i]["system_default"] = $all_currencies[$i]["system_default"];
            $info["currencies"][$i]["rate_to_system_default"] = $all_currencies[$i]["rate_to_system_default"];
            $info["currencies"][$i]["pi_decimal"] = $all_currencies[$i]["pi_decimal"];
        }
        $cashinout = $this->model("cashinout");
        $info["cashinout_types"] = $cashinout->getAllTypes();
        $info["cashinout_types_even_deleted"] = $cashinout->getAllTypesEvenDeleted();
        echo json_encode($info);
    }
    public function get_get_cashin_out_shifts($date_range)
    {
        $cashbox = $this->model("cashbox");
        $date_range_tmp = explode(" - ", $date_range);
        list($date_range_info[0], $date_range_info[1]) = $date_range_tmp;
        $cashboxes = $cashbox->get_cashboxes($date_range_info);
        $cashboxes_return = array();
        for ($i = 0; $i < count($cashboxes); $i++) {
            $cashboxes_return[$i]["id"] = $cashboxes[$i]["id"];
            $cashboxes_return[$i]["date"] = $cashboxes[$i]["starting_cashbox_date"];
        }
        echo json_encode($cashboxes_return);
    }
    public function get_needed_data()
    {
        self::giveAccessTo();
        $settings_info = self::getSettings();
        $info["default_currency_symbol"] = $settings_info["default_currency_symbol"];
        $info["apply_vat_sales_item"] = $settings_info["apply_vat_sales_item"];
        $info["print_a4_pdf_version"] = $settings_info["print_a4_pdf_version"];
        $info["all_invoices_hide_col"] = $settings_info["all_invoices_hide_col"];
        $info["all_quotations_hide_col"] = $settings_info["all_quotations_hide_col"];
        $info["vat"] = $settings_info["vat"];
        $suppliers = $this->model("suppliers");
        $suppliers_data = $suppliers->getSuppliers();
        $info["suppliers"] = array();
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $info["suppliers"][$i]["id"] = $suppliers_data[$i]["id"];
            $info["suppliers"][$i]["name"] = $suppliers_data[$i]["name"];
            $info["suppliers"][$i]["c_name"] = $suppliers_data[$i]["contact_name"];
            $info["suppliers"][$i]["address"] = $suppliers_data[$i]["address"];
        }
        $info["payment_status"] = array();
        $settings = $this->model("settings");
        $info["payment_status"] = $settings->get_payment_status();
        $warehouses = $this->model("warehouses");
        $all_warehouses = $warehouses->getAllWarehouses();
        $info["warehouses"] = array();
        for ($i = 0; $i < count($all_warehouses); $i++) {
            $info["warehouses"][$i]["id"] = self::idFormat_warehouse($all_warehouses[$i]["id"]);
            $info["warehouses"][$i]["location"] = $all_warehouses[$i]["location"];
        }
        $store = $this->model("store");
        $all_stores = $store->getStores();
        $info["stores"] = array();
        for ($i = 0; $i < count($all_stores); $i++) {
            $info["stores"][$i]["id"] = $all_stores[$i]["id"];
            $info["stores"][$i]["name"] = $all_stores[$i]["name"];
        }
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $info["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $info["currencies"][$i]["id"] = $all_currencies[$i]["id"];
            $info["currencies"][$i]["name"] = $all_currencies[$i]["name"];
            $info["currencies"][$i]["symbole"] = $all_currencies[$i]["symbole"];
            $info["currencies"][$i]["system_default"] = $all_currencies[$i]["system_default"];
            $info["currencies"][$i]["rate_to_system_default"] = $all_currencies[$i]["rate_to_system_default"];
            $info["currencies"][$i]["pi_decimal"] = $all_currencies[$i]["pi_decimal"];
        }
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
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
        $info["all_items"] = array();
        $tables_info = $items->getAllItems();
        for ($i = 0; $i < count($tables_info); $i++) {
            $info["all_items"][$i]["id"] = $tables_info[$i]["id"];
            $info["all_items"][$i]["description"] = $tables_info[$i]["description"];
            $info["all_items"][$i]["buying_cost"] = (double) $tables_info[$i]["buying_cost"];
            $info["all_items"][$i]["vat"] = $tables_info[$i]["vat"];
            $info["all_items"][$i]["barcode"] = $tables_info[$i]["barcode"];
        }
        $employees = $this->model("employees");
        $info["employees"] = $employees->getAllEmployees();
        $users = $this->model("user");
        $info["vendors"] = $users->getAllUsers();
        $info["vendors_created_invoices"] = $users->getAllUsersCreateInvoices();
        echo json_encode($info);
    }
    public function get_payment_status()
    {
        self::giveAccessTo();
        $settings = $this->model("settings");
        $payment_status = $settings->get_payment_status();
        echo json_encode($payment_status);
    }
    public function get_payment_method()
    {
        self::giveAccessTo(array(2));
        $settings = $this->model("settings");
        $payment_status = $settings->get_payment_method();
        echo json_encode($payment_status);
    }
    public function add_new_bank()
    {
        self::giveAccessTo(array(2));
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["bank_name"] = filter_input(INPUT_POST, "bank_name", self::conversion_php_version_filter());
        $settings = $this->model("settings");
        $info_return = $settings->add_new_bank($info);
        $return = array();
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        $return["name"] = $info["bank_name"];
        echo json_encode($return);
    }
    public function get_info()
    {
        self::giveAccessTo(array(2));
        $info = array();
        $settings = $this->model("settings");
        $settings_info = self::getSettings();
        $info["payments_method"] = $settings->get_payment_method();
        $info["banks"] = $settings->get_banks();
        $info["a4_print_style"] = $settings_info["a4_print_style"];
        $info["settings"][0]["number_of_decimal_points"] = $settings_info["number_of_decimal_points"];
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $info["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $info["currencies"][$i]["id"] = $all_currencies[$i]["id"];
            $info["currencies"][$i]["name"] = $all_currencies[$i]["name"];
            $info["currencies"][$i]["symbole"] = $all_currencies[$i]["symbole"];
            $info["currencies"][$i]["system_default"] = $all_currencies[$i]["system_default"];
            $info["currencies"][$i]["rate_to_system_default"] = $all_currencies[$i]["rate_to_system_default"];
        }
        echo json_encode($info);
    }
    public function update_settings()
    {
        self::giveAccessTo();
        $settings = $this->model("settings");
        if (isset($_POST["printer_name"])) {
            $printer_name = trim(filter_input(INPUT_POST, "printer_name", self::conversion_php_version_filter()));
            $settings->update_local_value($printer_name, "printer_name");
        } else {
            if (isset($_POST["shop_name"])) {
                $shop_name = trim(filter_input(INPUT_POST, "shop_name", self::conversion_php_version_filter()));
                $settings->update_value($shop_name, "shop_name");
            } else {
                if (isset($_POST["printer_status"])) {
                    $printer_status = trim(filter_input(INPUT_POST, "printer_status", FILTER_SANITIZE_NUMBER_INT));
                    $settings->update_value($printer_status, "auto_print");
                } else {
                    if (isset($_POST["language"])) {
                        $language = trim(filter_input(INPUT_POST, "language", self::conversion_php_version_filter()));
                        $settings->update_value($language, "language");
                    } else {
                        if (isset($_POST["vat"])) {
                            $vat = trim(filter_input(INPUT_POST, "vat", self::conversion_php_version_filter()));
                            $v = 1 + $vat / 100;
                            $settings->update_value($v, "vat");
                        } else {
                            if (isset($_POST["phone_nb"])) {
                                $phone_nb = trim(filter_input(INPUT_POST, "phone_nb", self::conversion_php_version_filter()));
                                $settings->update_value($phone_nb, "phone_nb");
                            } else {
                                if (isset($_POST["address"])) {
                                    $phone_nb = trim(filter_input(INPUT_POST, "address", self::conversion_php_version_filter()));
                                    $settings->update_value($phone_nb, "address");
                                } else {
                                    if (isset($_POST["printer_barcode_name"])) {
                                        $phone_nb = trim(filter_input(INPUT_POST, "printer_barcode_name", self::conversion_php_version_filter()));
                                        $settings->update_local_value($phone_nb, "printer_barcode_name");
                                    } else {
                                        if (isset($_POST["barcode_page_size_name"])) {
                                            $phone_nb = trim(filter_input(INPUT_POST, "barcode_page_size_name", self::conversion_php_version_filter()));
                                            $settings->update_local_value($phone_nb, "barcode_page_size_name");
                                        } else {
                                            if (isset($_POST["invoice_pdf_MOF"])) {
                                                $mof = trim(filter_input(INPUT_POST, "invoice_pdf_MOF", self::conversion_php_version_filter()));
                                                $settings->update_value($mof, "invoice_pdf_MOF");
                                            } else {
                                                if (isset($_POST["invoice_footer"])) {
                                                    $invoice_footer = trim(filter_input(INPUT_POST, "invoice_footer", self::conversion_php_version_filter()));
                                                    $settings->update_value($invoice_footer, "invoice_footer");
                                                } else {
                                                    if (isset($_POST["expiry_interval_days"])) {
                                                        $expiry_interval = trim(filter_input(INPUT_POST, "expiry_interval_days", FILTER_SANITIZE_NUMBER_INT));
                                                        $settings->update_value($expiry_interval, "expiry_interval_days");
                                                    } else {
                                                        if (isset($_POST["logo_"])) {
                                                            $target_dir = "resources/";
                                                            $target_file = $target_dir . basename($_FILES["logo_to_print_name"]["name"]);
                                                            $uploadOk = 1;
                                                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                                                            $check = getimagesize($_FILES["logo_to_print_name"]["tmp_name"]);
                                                            if ($check !== false) {
                                                                $uploadOk = 1;
                                                                if (!in_array($imageFileType, array("png"))) {
                                                                    $uploadOk = 12;
                                                                    echo json_encode(array($uploadOk));
                                                                    return NULL;
                                                                }
                                                                if (file_exists($target_file)) {
                                                                    unlink($target_file);
                                                                }
                                                                if ($uploadOk == 1) {
                                                                    if (move_uploaded_file($_FILES["logo_to_print_name"]["tmp_name"], $target_file)) {
                                                                        $settings->update_value($_FILES["logo_to_print_name"]["name"], "logo_to_print_name");
                                                                        echo json_encode(array($uploadOk));
                                                                        return NULL;
                                                                    }
                                                                    echo json_encode(array(13));
                                                                    return NULL;
                                                                }
                                                            } else {
                                                                $uploadOk = 10;
                                                                echo json_encode(array($uploadOk));
                                                                return NULL;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        echo json_encode(array());
    }
    public function parameters($p)
    {
        $settings = $this->model("settings");
        $settings_info = self::getSettings();
        $settings_local_info = self::get_settings_local();
        $currencies = $settings->getCurrencies();
        $settings_barcode_info = $settings->get_barcode_local_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($settings_barcode_info); $i++) {
            $barcode_settings_info[$settings_barcode_info[$i]["name"]] = $settings_barcode_info[$i]["value"];
        }
        $data["settings"] = $settings_info;
        $data["settings_local"] = $settings_local_info;
        $data["barcode"] = $barcode_settings_info;
        $data["currencies"] = $currencies;
        $data["p"] = $p;
        $this->view("parameters", $data);
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>