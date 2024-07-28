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
class reports extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function report_lack_of_items()
    {
        self::giveAccessTo();
        $this->view("report_lack_of_items");
    }
    public function report_lost_mobiledays()
    {
        self::giveAccessTo();
        $this->view("report_lost_mobiledays");
    }
    public function report_stock_movement()
    {
        self::giveAccessTo();
        $this->view("report_stock_movement");
    }
    public function report_stock_movement_all_time()
    {
        self::giveAccessTo();
        $this->view("report_stock_movement_all_time");
    }
    public function report_stock_movement_all_time_by_group()
    {
        self::giveAccessTo();
        $this->view("report_stock_movement_all_time_by_group");
    }
    public function top_customers_payments()
    {
        self::giveAccessTo();
        $this->view("top_customers_payments");
    }
    public function report_sales_by_salesperson()
    {
        self::giveAccessTo();
        $data = array();
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $this->view("report_sales_by_salesperson", $data);
    }
    public function var_report()
    {
        self::giveAccessTo();
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $this->view("report_vat", $data);
    }
    public function best_seller()
    {
        self::giveAccessTo();
        $data = array();
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $this->view("report_best_seller", $data);
    }
    public function report_stock_expired()
    {
        self::giveAccessTo();
        $this->view("report_stock_expired");
    }
    public function report_stock_wasting()
    {
        self::giveAccessTo();
        $users = $this->model("user");
        $data["users"] = $users->getAllVendorsEvenDeleted();
        $this->view("report_stock_wasting", $data);
    }
    public function report_returning_items()
    {
        self::giveAccessTo();
        $this->view("report_returning_items");
    }
    public function report_sales_by_day_credit_transfers()
    {
        self::giveAccessTo();
        $this->view("report_sales_mobile_credit_transfers");
    }
    public function report_sales_by_day_mobiledays()
    {
        self::giveAccessTo();
        $this->view("report_sales_by_day_mobiledays");
    }
    public function report_sales_by_day()
    {
        self::giveAccessTo();
        $data = array();
        $data["row_discounted_color_in_report"] = $this->settings_info["row_discounted_color_in_report"];
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $this->view("report_sales_by_day", $data);
    }
    public function report_sales_by_day_items()
    {
        self::giveAccessTo();
        $data = array();
        $data["row_discounted_color_in_report"] = $this->settings_info["row_discounted_color_in_report"];
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $this->view("report_sales_by_day_items", $data);
    }
    public function report_stock()
    {
        self::giveAccessTo();
        $data = array();
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $this->view("report_stock", $data);
    }
    public function report_cashbox()
    {
        self::giveAccessTo();
        $this->view("report_cashbox_new");
    }
    public function report_sales_by_employee()
    {
        self::giveAccessTo();
        $this->view("report_sales_by_employee");
    }
    public function report_expenses()
    {
        self::giveAccessTo();
        $this->view("report_expenses");
    }
    public function getVendors()
    {
        self::giveAccessTo();
        $users = $this->model("user");
        $info = $users->getVendors();
        echo json_encode($info);
    }
    public function getSalesperson()
    {
        self::giveAccessTo();
        $employee = $this->model("employees");
        $info = $employee->getAllEmployees();
        echo json_encode($info);
    }
    public function debts_payments()
    {
        self::giveAccessTo();
        $this->view("report_debts_payments");
    }
    public function get_debts_payment($_date)
    {
        self::giveAccessTo();
        $date = filter_var($_date, self::conversion_php_version_filter());
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
        $reports = $this->model("reports");
        $reports_info = $reports->get_debts_payment($date_range);
        $customers = $this->model("customers");
        $customers_info = $customers->getCustomers();
        $cus = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $cus[$customers_info[$i]["id"]] = ucfirst($customers_info[$i]["name"]) . " " . ucfirst($customers_info[$i]["middle_name"]) . " " . ucfirst($customers_info[$i]["last_name"]);
        }
        $user = $this->model("user");
        $employees_info = $user->getAllUsersEvenDeleted();
        $emp = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $emp[$employees_info[$i]["id"]] = $employees_info[$i];
        }
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $data["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            if ($all_currencies[$i]["system_default"] == 1) {
                $data["currency_system_default"] = $all_currencies[$i]["id"];
            }
            $data["currencies"][$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $settings = $this->model("settings");
        $settings_payments_methos = $settings->get_all_payment_method();
        $p_method = array();
        for ($i = 0; $i < count($settings_payments_methos); $i++) {
            $p_method[$settings_payments_methos[$i]["id"]] = $settings_payments_methos[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($reports_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_customer_payment($reports_info[$i]["id"]));
            array_push($tmp, $cus[$reports_info[$i]["customer_id"]]);
            array_push($tmp, $emp[$reports_info[$i]["vendor_id"]]["username"]);
            array_push($tmp, self::global_number_formatter($reports_info[$i]["balance"], $this->settings_info));
            array_push($tmp, $p_method[$reports_info[$i]["payment_method"]]["method_name"]);
            array_push($tmp, $reports_info[$i]["note"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getCashboxReport_new($date)
    {
        $cashbox = $this->model("cashbox");
        $user = $this->model("user");
        $users_info = $user->getAllUsersEvenDeleted();
        $users_detail = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_detail[$users_info[$i]["id"]] = $users_info[$i];
        }
        $date_filter = filter_var($date, self::conversion_php_version_filter());
        $date_range = array();
        if ($date_filter == "thismonth") {
            $date_range[0] = date("Y-m-d", strtotime(date("Y") . "-" . date("M") . "-01"));
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" - ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
        }
        $filter = array();
        list($filter["start_date"], $filter["end_date"]) = $date_range;
        $cashbox_info = $cashbox->get_cashboxes_by_filter($filter);
        $data_array["data"] = array();
        for ($i = 0; $i < count($cashbox_info); $i++) {
            $tmp = array();
            array_push($tmp, $cashbox_info[$i]["id"]);
            array_push($tmp, $users_detail[$cashbox_info[$i]["vendor_id"]]["username"]);
            array_push($tmp, $cashbox_info[$i]["starting_cashbox_date"]);
            array_push($tmp, $cashbox_info[$i]["ending_cashbox_date"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_stock_movement_all_time_by_group($_store_id, $_date, $_subcategory_id, $_creation_date)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $reports = $this->model("reports");
        $store = $this->model("store");
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $subcategory_id = filter_var($_subcategory_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($_date, self::conversion_php_version_filter());
        $items_starting_date = filter_var($_creation_date, self::conversion_php_version_filter());
        $date_range_starting = array();
        $date_range_starting[0] = NULL;
        $date_range_starting[1] = NULL;
        if ($items_starting_date == "all") {
            $date_range_starting[0] = date("Y-m-1");
            $date_range_starting[1] = date("Y-m-d");
        } else {
            $date_range_starting = explode(" - ", $items_starting_date);
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
        if ($subcategory_id == 0) {
            $items_info = $items->getAllItemsByGroup_Creationdate($date_range_starting);
        } else {
            $items_info = $items->getAllItemsByGroupBySubcategory_Creationdate($subcategory_id, $date_range_starting);
        }
        $items_qty = $items->getAllItemsWithQTY_by_group();
        $items_qty_info = array();
        for ($i = 0; $i < count($items_qty); $i++) {
            $items_qty_info[$items_qty[$i]["item_group"]] = $items_qty[$i]["qty"];
        }
        $failed_cnx = true;
        if ($_SESSION["global_admin_exist"] == 1) {
            $warehouse_info = array();
            $stores = $store->getAllStores();
            for ($i = 0; $i < count($stores); $i++) {
                if ($stores[$i]["warehouse"] == 1) {
                    $warehouse_info[$i] = $stores[$i];
                }
            }
            $outside_connection_ = $this->model("outside_connection_");
            $custom_connection = my_sql::custom_connection($warehouse_info[0]["ip_address"], $warehouse_info[0]["username"], $warehouse_info[0]["password"], $warehouse_info[0]["db"]);
            if ($custom_connection) {
                $failed_cnx = false;
                $items_qty_in_warehouse = $outside_connection_->getAllItemsWithQTY_by_group_in_location($custom_connection);
                $items_qty_in_warehouse_info = array();
                for ($i = 0; $i < count($items_qty_in_warehouse); $i++) {
                    $items_qty_in_warehouse_info[$items_qty_in_warehouse[$i]["item_group"]] = $items_qty_in_warehouse[$i]["qty"];
                }
            }
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
        $sold_items_all_time_by_group_info = $reports->getSumOfSoldItems_all_time_by_group__groupby($subcategory_id);
        $sold_items_all_time_by_group_array = array();
        for ($i = 0; $i < count($sold_items_all_time_by_group_info); $i++) {
            $sold_items_all_time_by_group_array[$sold_items_all_time_by_group_info[$i]["item_group"]] = $sold_items_all_time_by_group_info[$i]["qty"];
        }
        $sold_items_by_group = $reports->getSumOfSoldItems_by_group__bygroup($subcategory_id, $date_range);
        $sold_items_by_group_array = array();
        for ($i = 0; $i < count($sold_items_by_group); $i++) {
            $sold_items_by_group_array[$sold_items_by_group[$i]["item_group"]] = $sold_items_by_group[$i]["qty"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $tmp = array();
            array_push($tmp, $items_info[$i]["description"]);
            $final_cost = 0;
            if ($items_info[$i]["vat"] == 1) {
                $final_cost = $items_info[$i]["buying_cost"] * $this->settings_info["vat"];
            } else {
                $final_cost = $items_info[$i]["buying_cost"];
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($final_cost, $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            array_push($tmp, self::value_format_custom($items_info[$i]["selling_price"], $this->settings_info));
            if (in_array($items_info[$i]["id"], $discounts_items_ids)) {
                $items_info[$i]["discount"] = $discounts_items_discount[$items_info[$i]["id"]];
            }
            $price_after_discount = $items_info[$i]["selling_price"] - $items_info[$i]["selling_price"] * $items_info[$i]["discount"] / 100;
            if ($items_info[$i]["vat"] == 1) {
                $price_after_discount = $items_info[$i]["selling_price"] * $this->settings_info["vat"];
            }
            array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
            array_push($tmp, $items_info[$i]["num"]);
            array_push($tmp, (double) $items_qty_info[$items_info[$i]["item_group"]] + (double) $sold_items_all_time_by_group_array[$items_info[$i]["item_group"]]);
            array_push($tmp, (double) $items_qty_info[$items_info[$i]["item_group"]]);
            array_push($tmp, (double) $sold_items_by_group_array[$items_info[$i]["item_group"]]);
            if ($_SESSION["global_admin_exist"] == 1 && !$failed_cnx) {
                array_push($tmp, (double) $items_qty_in_warehouse_info[$items_info[$i]["item_group"]]);
            } else {
                array_push($tmp, "-");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_stock_movement_all_time($_store_id, $_date, $_subcategory_id, $_items_starting_date)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $reports = $this->model("reports");
        $store = $this->model("store");
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $subcategory_id = filter_var($_subcategory_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($_date, self::conversion_php_version_filter());
        $items_starting_date = filter_var($_items_starting_date, self::conversion_php_version_filter());
        $creation_date = 0;
        if ($items_starting_date == "all" || strlen($items_starting_date) == 0 || $items_starting_date == NULL) {
            $creation_date = 0;
        } else {
            $creation_date = $items_starting_date;
        }
        $date_range_starting = array();
        $date_range_starting[0] = NULL;
        $date_range_starting[1] = NULL;
        if ($items_starting_date == "all") {
            $date_range_starting[0] = date("Y-m-1");
            $date_range_starting[1] = date("Y-m-d");
        } else {
            $date_range_starting = explode(" - ", $items_starting_date);
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
        if ($subcategory_id == 0) {
            $items_info = $items->getAllItemsWithQTY($store_id, $date_range_starting);
        } else {
            $items_info = $items->getAllItemsBySub_andcreationdate($subcategory_id, $store_id, $date_range_starting);
        }
        $sold_items = $reports->getReportByDay_by_item($store_id, $date_range, $subcategory_id, 0, 0);
        $sold_items_info = array();
        for ($i = 0; $i < count($sold_items); $i++) {
            $sold_items_info[$sold_items[$i]["item_id"]] = $sold_items[$i];
        }
        $sold_items_all_time = $reports->getSumOfSoldItems_all_time($store_id, $subcategory_id);
        $sold_items_all_time_info = array();
        for ($i = 0; $i < count($sold_items_all_time); $i++) {
            $sold_items_all_time_info[$sold_items_all_time[$i]["item_id"]] = $sold_items_all_time[$i]["qty"];
        }
        $failed_cnx = true;
        if ($_SESSION["global_admin_exist"] == 1) {
            $warehouse_info = array();
            $stores = $store->getAllStores();
            for ($i = 0; $i < count($stores); $i++) {
                if ($stores[$i]["warehouse"] == 1) {
                    $warehouse_info[$i] = $stores[$i];
                }
            }
            $outside_connection_ = $this->model("outside_connection_");
            $custom_connection = my_sql::custom_connection($warehouse_info[0]["ip_address"], $warehouse_info[0]["username"], $warehouse_info[0]["password"], $warehouse_info[0]["db"]);
            if ($custom_connection) {
                $failed_cnx = false;
                $items_qty_in_warehouse = $outside_connection_->getAllItemsWithQTY_inlocation($custom_connection);
                $items_qty_in_warehouse_info = array();
                for ($i = 0; $i < count($items_qty_in_warehouse); $i++) {
                    $items_qty_in_warehouse_info[$items_qty_in_warehouse[$i]["id"]] = $items_qty_in_warehouse[$i]["quantity"];
                }
            }
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
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($items_info[$i]["id"]));
            array_push($tmp, $items_info[$i]["barcode"]);
            array_push($tmp, $items_info[$i]["description"]);
            $final_cost = 0;
            if ($items_info[$i]["vat"] == 1) {
                $final_cost = $items_info[$i]["buying_cost"] * $this->settings_info["vat"];
            } else {
                $final_cost = $items_info[$i]["buying_cost"];
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($final_cost, $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            array_push($tmp, self::value_format_custom($items_info[$i]["selling_price"], $this->settings_info));
            if (in_array($items_info[$i]["id"], $discounts_items_ids)) {
                $items_info[$i]["discount"] = $discounts_items_discount[$items_info[$i]["id"]];
            }
            $price_after_discount = $items_info[$i]["selling_price"] - $items_info[$i]["selling_price"] * $items_info[$i]["discount"] / 100;
            if ($items_info[$i]["vat"] == 1) {
                $price_after_discount = $items_info[$i]["selling_price"] * $this->settings_info["vat"];
            }
            array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
            if (0 < $price_after_discount) {
                $margin_profit = ($price_after_discount - $final_cost) / $price_after_discount * 100;
            } else {
                $margin_profit = 0;
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, number_format($margin_profit, 2) . " %");
            } else {
                array_push($tmp, self::critical_data());
            }
            if (isset($sold_items_all_time_info[$items_info[$i]["id"]])) {
                array_push($tmp, (double) $sold_items_all_time_info[$items_info[$i]["id"]] + $items_info[$i]["quantity"]);
            } else {
                array_push($tmp, (double) $items_info[$i]["quantity"]);
            }
            array_push($tmp, (double) $items_info[$i]["quantity"]);
            if (isset($sold_items_info[$items_info[$i]["id"]])) {
                array_push($tmp, (double) $sold_items_info[$items_info[$i]["id"]]["qty"]);
            } else {
                array_push($tmp, 0);
            }
            array_push($tmp, self::value_format_custom($sold_items_info[$items_info[$i]["id"]]["final_price_disc_qty"], $this->settings_info));
            if ($_SESSION["global_admin_exist"] == 1 && !$failed_cnx) {
                array_push($tmp, (double) $items_qty_in_warehouse_info[$items_info[$i]["id"]]);
            } else {
                array_push($tmp, "-");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_stock_movement($_store_id, $_date, $_subcategory_id)
    {
        self::giveAccessTo();
        $reports = $this->model("reports");
        $items = $this->model("items");
        $invoice = $this->model("invoice");
        $info = array();
        $info["store_id"] = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $info["subcategory_id"] = filter_var($_subcategory_id, FILTER_SANITIZE_NUMBER_INT);
        $dd = filter_var($_date, self::conversion_php_version_filter());
        if ($dd == "today") {
            $info["date"] = date("Y-m-1");
        } else {
            $info["date"] = date("Y-m-d", strtotime(filter_var($dd, self::conversion_php_version_filter())));
        }
        $it_mv_info = $reports->get_stock_movement($info);
        $invoices_items = $invoice->get_sold_items_for_item_movement($info["store_id"], $info["date"]);
        $invoices_items_array = array();
        for ($i = 0; $i < count($invoices_items); $i++) {
            $invoices_items_array[$invoices_items[$i]["item_id"]] = $invoices_items[$i];
        }
        $items_info = $items->getAllItemsEvenDeleted();
        $items_info_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $items_info_array[$items_info[$i]["id"]] = $items_info[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($it_mv_info); $i++) {
            $tmp = array();
            if ($items_info_array[$it_mv_info[$i]["item_id"]]["item_category"] == $info["subcategory_id"] || $info["subcategory_id"] == 0) {
                array_push($tmp, self::idFormat_item($it_mv_info[$i]["item_id"]));
                array_push($tmp, $items_info_array[$it_mv_info[$i]["item_id"]]["barcode"]);
                array_push($tmp, $items_info_array[$it_mv_info[$i]["item_id"]]["description"]);
                array_push($tmp, round($it_mv_info[$i]["qty"], 3));
                if (isset($invoices_items_array[$it_mv_info[$i]["item_id"]])) {
                    array_push($tmp, self::value_format_custom($invoices_items_array[$it_mv_info[$i]["item_id"]]["item_qty"], $this->settings_info));
                    array_push($tmp, self::value_format_custom($invoices_items_array[$it_mv_info[$i]["item_id"]]["item_price_sum"], $this->settings_info));
                    array_push($tmp, self::value_format_custom($invoices_items_array[$it_mv_info[$i]["item_id"]]["item_total_profit"], $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom(0, $this->settings_info));
                    array_push($tmp, self::value_format_custom(0, $this->settings_info));
                    array_push($tmp, self::value_format_custom(0, $this->settings_info));
                }
                if ($items_info_array[$it_mv_info[$i]["item_id"]]["vat"] == 1) {
                    array_push($tmp, self::value_format_custom($it_mv_info[$i]["qty"] * $items_info_array[$it_mv_info[$i]["item_id"]]["buying_cost"] * floatval($this->settings_info["vat"]), $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom($it_mv_info[$i]["qty"] * $items_info_array[$it_mv_info[$i]["item_id"]]["buying_cost"], $this->settings_info));
                }
                array_push($data_array["data"], $tmp);
            }
        }
        echo json_encode($data_array);
    }
    public function getInfoForSalesperson($_store_id, $_sales_person_id, $_date_range)
    {
        self::giveAccessTo();
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $sales_person_id = filter_var($_sales_person_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($_date_range, self::conversion_php_version_filter());
        $reports = $this->model("reports");
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
        $report_info = $reports->getReportSumBySalesperson($store_id, $sales_person_id, $date_range);
        $report_info_debt = $reports->getReportSumBySalesperson_debt($store_id, $sales_person_id, $date_range);
        $report_info_dis = $reports->getReportSumBySalespersonDiscountsInvoice($store_id, $sales_person_id, $date_range);
        if ($_SESSION["hide_critical_data"] == 1) {
            echo json_encode(array(self::critical_data(), self::critical_data(), self::critical_data()));
        } else {
            echo json_encode(array(self::value_format_custom($report_info[0]["sum"], $this->settings_info), self::value_format_custom($report_info_dis[0]["sum"], $this->settings_info), self::value_format_custom($report_info_debt[0]["sum"], $this->settings_info)));
        }
    }
    public function expenses($store_id_, $date_, $type_id_)
    {
        self::giveAccessTo();
        $expenses = $this->model("expenses");
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $type_id = filter_var($type_id_, FILTER_SANITIZE_NUMBER_INT);
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
        $expenses_data = $expenses->getExpensesByDateRangeAndType($store_id, $date_range, $type_id);
        $expenses_types = $expenses->getTypes();
        $types = array();
        for ($i = 0; $i < count($expenses_types); $i++) {
            $types[$expenses_types[$i]["id"]] = $expenses_types[$i]["name"];
        }
        $data_array["data"] = array();
        $data_array["total_amount"] = 0;
        $total_exp = 0;
        for ($i = 0; $i < count($expenses_data); $i++) {
            $tmp = array();
            $expenses_data_exploaded = explode(" ", $expenses_data[$i]["date"]);
            array_push($tmp, self::idFormat_expenses($expenses_data[$i]["id"]));
            array_push($tmp, $types[$expenses_data[$i]["type_id"]]);
            array_push($tmp, $expenses_data[$i]["description"]);
            array_push($tmp, $expenses_data_exploaded[0]);
            array_push($tmp, self::value_format_custom($expenses_data[$i]["value"], $this->settings_info));
            $total_exp += $expenses_data[$i]["value"];
            $data_array["total_amount"] += $expenses_data[$i]["value"];
            array_push($data_array["data"], $tmp);
        }
        $data_array["total_amount_f"] = number_format($data_array["total_amount"], 2) . " " . $_SESSION["currency_symbol"];
        echo json_encode($data_array);
    }
    public function getReportBestSeller($store_id_, $date_, $_category_id)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
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
        $reports = $this->model("reports");
        $items = $this->model("items");
        $all_items = $items->getAllItemsEvenDeleted();
        $items_info = array();
        for ($i = 0; $i < count($all_items); $i++) {
            $items_info[$all_items[$i]["id"]] = $all_items[$i];
        }
        $all_items_that_have_boxes = $items->get_all_items_that_have_boxes();
        $info = $reports->getReportBestSeller($store_id, $date_range, $category_id);
        $data_array["data"] = array();
        $_total_sales = 0;
        $_total_profits = 0;
        $_total_qty = 0;
        $data_array["_total_sales"] = 0;
        $data_array["_total_profits"] = 0;
        $data_array["_total_qty"] = 0;
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            $comp_qty = 0;
            $tot_box_profit = 0;
            if ($info[$i]["item_id"] != NULL && in_array($info[$i]["item_id"], $all_items_that_have_boxes)) {
                $rs = $reports->getReportBestSeller_by_box($info[$i]["item_id"], $date_range, $store_id, $category_id);
                $comp_qty = $rs["total_qty"];
                $tot_box_profit = $rs["total_profit"];
            }
            if ($info[$i]["item_id"] != NULL) {
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                array_push($tmp, $items_info[$info[$i]["item_id"]]["barcode"]);
                array_push($tmp, $items_info[$info[$i]["item_id"]]["description"]);
            } else {
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($tmp, $info[$i]["description"]);
            }
            $_total_qty += floor($info[$i]["qty"] + $comp_qty);
            array_push($tmp, floor($info[$i]["qty"] + $comp_qty));
            $final_cost = 0;
            if ($info[$i]["vat"]) {
                $final_cost = floatval($info[$i]["buying_cost"] * $this->settings_info["vat"]);
            } else {
                $final_cost = floatval($info[$i]["buying_cost"]);
            }
            $_total_sales += $info[$i]["selling_price"];
            $price_after_discount = $info[$i]["selling_price"] - $info[$i]["selling_price"] * $info[$i]["discount"] / 100;
            if ($info[$i]["vat"] == 1) {
                $price_after_discount = $price_after_discount * $this->settings_info["vat"];
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($price_after_discount - $final_cost, $this->settings_info));
                array_push($tmp, self::value_format_custom($info[$i]["profit"] + $tot_box_profit, $this->settings_info));
                $_total_profits += $info[$i]["profit"] + $tot_box_profit;
            }
            array_push($data_array["data"], $tmp);
        }
        $data_array["_total_sales"] = number_format($_total_sales, 0);
        $data_array["_total_profits"] = number_format($_total_profits, 0);
        $data_array["_total_qty"] = number_format($_total_qty, 0);
        echo json_encode($data_array);
    }
    public function getExpiredStockReport($store_id_, $supplier_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($supplier_id_, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $info = $items->get_expired_items_in_store($store_id, (double) $this->settings_info["expiry_interval_days"]);
        $total_qty = 0;
        $total_cost = 0;
        $total_price = 0;
        $total_profit = 0;
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            if ($info[$i]["supplier_reference"] == $supplier_id || $supplier_id == 0) {
                $tmp = array();
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                array_push($tmp, $info[$i]["description"]);
                array_push($tmp, self::date_format_custom($info[$i]["expiry_date"]));
                array_push($tmp, $info[$i]["remain"]);
                $total_qty += (double) $info[$i]["quantity"];
                array_push($tmp, (double) $info[$i]["quantity"]);
                array_push($tmp, self::global_number_formatter($info[$i]["buying_cost"], $this->settings_info));
                $total_price += $info[$i]["quantity"] * $info[$i]["selling_price"];
                array_push($tmp, self::global_number_formatter($info[$i]["selling_price"], $this->settings_info));
                $total_profit += $info[$i]["quantity"] * $info[$i]["selling_price"] - $info[$i]["quantity"] * $info[$i]["buying_cost"];
                $total_cost += $info[$i]["quantity"] * $info[$i]["buying_cost"];
                array_push($tmp, self::global_number_formatter($info[$i]["quantity"] * $info[$i]["buying_cost"], $this->settings_info));
                array_push($data_array["data"], $tmp);
            }
        }
        $data_array["total_qty"] = self::global_number_formatter(floatval($total_qty), $this->settings_info);
        $data_array["total_cost"] = self::global_number_formatter(floatval($total_cost), $this->settings_info);
        $data_array["total_price"] = self::global_number_formatter(floatval($total_price), $this->settings_info);
        $data_array["total_profit"] = self::global_number_formatter(floatval($total_profit), $this->settings_info);
        echo json_encode($data_array);
    }
    public function getExpiredStockReportDetails()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $info = $items->get_expired_items_in_store($_SESSION["store_id"], (double) $this->settings_info["expiry_interval_days"]);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
            array_push($tmp, $info[$i]["description"]);
            array_push($tmp, self::date_format_custom($info[$i]["expiry_date"]));
            array_push($tmp, $info[$i]["remain"]);
            array_push($tmp, (double) $info[$i]["quantity"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getStockReport($store_id_, $supplier_id_, $_cat, $_subcat, $_price_type_id)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($supplier_id_, FILTER_SANITIZE_NUMBER_INT);
        $price_type_id = filter_var($_price_type_id, FILTER_SANITIZE_NUMBER_INT);
        $cat = filter_var($_cat, FILTER_SANITIZE_NUMBER_INT);
        $subcat = filter_var($_subcat, FILTER_SANITIZE_NUMBER_INT);
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
        $items = $this->model("items");
        $info = $items->get_items_in_store($store_id, $cat, $subcat);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            if (($info[$i]["supplier_reference"] == $supplier_id || $supplier_id == 0) && $info[$i]["is_composite"] == 0) {
                $tmp = array();
                $selling_price = $info[$i]["selling_price"];
                if ($price_type_id == 2) {
                    $selling_price = $info[$i]["wholesale_price"];
                }
                if ($price_type_id == 3) {
                    $selling_price = $info[$i]["second_wholesale_price"];
                }
                $final_cost = 0;
                if ($info[$i]["vat"]) {
                    $final_cost = floatval($info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                    $price_after_discount = ($selling_price - $selling_price * $info[$i]["discount"] / 100) * floatval($this->settings_info["vat"]);
                } else {
                    $final_cost = floatval($info[$i]["buying_cost"]);
                    $price_after_discount = $selling_price - $selling_price * $info[$i]["discount"] / 100;
                }
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                array_push($tmp, $info[$i]["barcode"]);
                array_push($tmp, $info[$i]["description"]);
                array_push($tmp, $colors_info_label[$info[$i]["color_text_id"]]);
                array_push($tmp, $sizes_info_label[$info[$i]["size_id"]]);
                array_push($tmp, (double) $info[$i]["quantity"]);
                if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                    array_push($tmp, self::global_number_formatter($final_cost, $this->settings_info));
                    array_push($tmp, self::global_number_formatter($final_cost * $info[$i]["quantity"], $this->settings_info));
                    array_push($tmp, self::global_number_formatter($price_after_discount * $info[$i]["quantity"], $this->settings_info));
                    array_push($tmp, self::global_number_formatter(($price_after_discount - $final_cost) * $info[$i]["quantity"], $this->settings_info));
                } else {
                    array_push($tmp, self::critical_data());
                    array_push($tmp, self::critical_data());
                    array_push($tmp, self::global_number_formatter($price_after_discount * $info[$i]["quantity"], $this->settings_info));
                    array_push($tmp, self::critical_data());
                }
                array_push($data_array["data"], $tmp);
            }
        }
        echo json_encode($data_array);
    }
    public function getTopCustomers($store_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $reports = $this->model("reports");
        $customers = $this->model("customers");
        $customers_info = $customers->getCustomers();
        $cus = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $cus[$customers_info[$i]["id"]] = $customers_info[$i]["name"];
        }
        $info = $reports->getTopCustomers($store_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $cus[$info[$i]["customer_id"]]);
            array_push($tmp, self::global_number_formatter((double) $info[$i]["total_v"], $this->settings_info));
            array_push($tmp, self::global_number_formatter((double) $info[$i]["total_disc"], $this->settings_info));
            array_push($tmp, self::global_number_formatter((double) $info[$i]["total_p"], $this->settings_info));
            array_push($tmp, self::global_number_formatter((double) $info[$i]["total_p_after_discount"], $this->settings_info));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getCashboxReport($store_id_, $date_, $vendor_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $vendor_id = filter_var($vendor_id_, FILTER_SANITIZE_NUMBER_INT);
        $reports = $this->model("reports");
        $cashbox = $this->model("cashbox");
        $expenses = $this->model("expenses");
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $suppliers = $this->model("suppliers");
        $user = $this->model("user");
        $users_info = $user->getAllUsersEvenDeleted();
        $users_detail = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_detail[$users_info[$i]["id"]] = $users_info[$i];
        }
        $rdate = date("Y-m-d", strtotime($date));
        $info__ = $reports->getCashboxReport($store_id, $rdate, $vendor_id);
        for ($i = 0; $i < count($info__); $i++) {
            if ($info__[$i]["closed"] == 0) {
                $cashbox->updateCashBox($info__[$i]["id"]);
            }
        }
        $info = $reports->getCashboxReport($store_id, $rdate, $vendor_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            if ($info[$i]["fixed_info"] != NULL) {
                $fixed_info = json_decode($info[$i]["fixed_info"], true);
            }
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $users_detail[$info[$i]["vendor_id"]]["username"]);
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($info[$i]["cash"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            }
            if ($info[$i]["closed"] == 0) {
                array_push($tmp, "Open");
            } else {
                array_push($tmp, "Closed");
            }
            $totalInvoiceDiscountByCashboxID = $invoice->getTotalInvoiceDiscountByCashboxID($info[$i]["id"]);
            $salesReturnedByCashboxID = $invoice->getSalesReturnedByCashboxID($info[$i]["id"]);
            $salesReturnedByCashboxID_vat_diff = $invoice->getSalesReturnedByCashboxID_vat_diff($info[$i]["id"]);
            $customers_payment_debts = $customers->getTotalPaymentBalanceByCashboxID($info[$i]["id"]);
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                if ($info[$i]["fixed_info"] != NULL) {
                    array_push($tmp, self::value_format_custom($fixed_info["invoices"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                } else {
                    array_push($tmp, self::value_format_custom($invoice->getCashSalesInvoicesByCashboxID($info[$i]["id"]) + $invoice->getCashSalesInvoicesByCashboxID_vat_diff($info[$i]["id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                }
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($invoice->getSalesByCreditCard($info[$i]["id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($invoice->getSalesByCheque($info[$i]["id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($invoice->getSalesNotPaidByCashboxID($info[$i]["id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                if ($info[$i]["fixed_info"] != NULL) {
                    array_push($tmp, self::value_format_custom($fixed_info["customer_payments"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                } else {
                    array_push($tmp, self::value_format_custom($customers_payment_debts, $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                }
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                if ($info[$i]["fixed_info"] != NULL) {
                    array_push($tmp, self::value_format_custom($fixed_info["expenses"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                } else {
                    array_push($tmp, self::value_format_custom($expenses->getSumOfExpensesByCashboxID($info[$i]["id"]), $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                }
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($totalInvoiceDiscountByCashboxID, $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                if ($info[$i]["fixed_info"] != NULL) {
                    array_push($tmp, self::value_format_custom($fixed_info["returned_purchases"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                } else {
                    array_push($tmp, self::value_format_custom($salesReturnedByCashboxID + $salesReturnedByCashboxID_vat_diff, $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
                }
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($fixed_info["suppliers_payments"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, self::critical_data());
            } else {
                array_push($tmp, self::value_format_custom($info[$i]["cash_on_close"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"]);
            }
            $start = date("Y-m-d h:i:s a", strtotime($info[$i]["starting_cashbox_date"]));
            if ($info[$i]["ending_cashbox_date"] != NULL) {
                $end = date("Y-m-d h:i:s a", strtotime($info[$i]["ending_cashbox_date"]));
            } else {
                $end = "";
            }
            array_push($tmp, $start . "<br/><br/>" . $end);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getReportOfReturningItems($store_id_, $date_, $vendor_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $vendor_id = filter_var($vendor_id_, FILTER_SANITIZE_NUMBER_INT);
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
        $reports = $this->model("reports");
        $items = $this->model("items");
        $user = $this->model("user");
        $invoice = $this->model("invoice");
        $users_info = $user->getAllUsersEvenDeleted();
        $users_detail = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_detail[$users_info[$i]["id"]] = $users_info[$i];
        }
        $info = $reports->getReportOfReturningItems($store_id, $date_range, $vendor_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($info[$i]["invoice_id"]));
            array_push($tmp, $info[$i]["return_date"]);
            array_push($tmp, "");
            if ($info[$i]["item_id"] == NULL) {
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($tmp, $info[$i]["description"]);
            } else {
                $item_info = $items->get_item($info[$i]["item_id"]);
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                array_push($tmp, $item_info[0]["barcode"]);
                array_push($tmp, $item_info[0]["description"]);
            }
            array_push($tmp, (double) $info[$i]["qty"]);
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, (double) $info[$i]["discount"] . "%");
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"] - $info[$i]["selling_price"] * $info[$i]["discount"] / 100, $this->settings_info));
            array_push($tmp, $users_detail[$info[$i]["returned_by_vendor_id"]]["username"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getReportBySalesperson($store_id_, $date_, $vendor_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $sales_person_id = filter_var($vendor_id_, FILTER_SANITIZE_NUMBER_INT);
        $reports = $this->model("reports");
        $items = $this->model("items");
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
        $info = $reports->getReportBySalesperson($store_id, $date_range, $sales_person_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            if (is_null($info[$i]["item_id"])) {
                array_push($tmp, "no ref.");
                array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
                array_push($tmp, $info[$i]["description"]);
            } else {
                $item_info = $items->get_item($info[$i]["item_id"]);
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                if ($info[$i]["closed"] == 1) {
                    array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
                } else {
                    array_push($tmp, "<b onclick='edit_manual_invoice(" . $info[$i]["id"] . ")' class='invc'>" . self::idFormat_invoice($info[$i]["id"]) . "</b>");
                }
                array_push($tmp, $item_info[0]["description"]);
            }
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, (double) $info[$i]["qty"]);
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            }
            array_push($tmp, (double) $info[$i]["discount"] . " %");
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                if ($info[$i]["vat"] == 0) {
                    array_push($tmp, $info[$i]["vat"]);
                } else {
                    array_push($tmp, ($info[$i]["vat_value"] - 1) * 100 . " %");
                }
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                if ($info[$i]["vat"] == 0) {
                    array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"] * $info[$i]["vat_value"], $this->settings_info));
                }
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getVATReport($store_id_, $date_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $reports = $this->model("reports");
        $customers = $this->model("customers");
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
        $customers_info = $customers->getCustomersEvenDeleted();
        $customers_info_array = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $customers_info_array[$customers_info[$i]["id"]] = $customers_info[$i]["name"] . " " . $customers_info[$i]["middle_name"] . " " . $customers_info[$i]["last_name"];
        }
        $info = $reports->getVATReport($store_id, $date_range);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
            array_push($tmp, $info[$i]["creation_date"]);
            if ($info[$i]["customer_id"] != NULL && $info[$i]["customer_id"] != 0 && isset($customers_info_array[$info[$i]["customer_id"]])) {
                array_push($tmp, ucwords($customers_info_array[$info[$i]["customer_id"]]));
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, self::global_number_formatter($info[$i]["total_value"], $this->settings_info));
            array_push($tmp, self::global_number_formatter($info[$i]["total_value"] * ($info[$i]["vat_value"] - 1), $this->settings_info));
            array_push($tmp, self::global_number_formatter(($info[$i]["total_value"] + $info[$i]["invoice_discount"]) * $info[$i]["vat_value"], $this->settings_info));
            array_push($tmp, self::global_number_formatter(0, $this->settings_info));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getReportByEmployee($store_id_, $date_, $vendor_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $vendor_id = filter_var($vendor_id_, FILTER_SANITIZE_NUMBER_INT);
        $reports = $this->model("reports");
        $items = $this->model("items");
        $date_range = array();
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date == "thismonth") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $info = $reports->getReportByEmployee($store_id, $date_range, $vendor_id);
        $customers = $this->model("customers");
        $customers_info = $customers->getCustomers();
        $cus = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $cus[$customers_info[$i]["id"]] = ucfirst($customers_info[$i]["name"]) . " " . ucfirst($customers_info[$i]["middle_name"]) . " " . ucfirst($customers_info[$i]["last_name"]);
        }
        $total_amount = 0;
        $total_amount_counted = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            $customer = "";
            if (0 < $info[$i]["customer_id"]) {
                $customer = $cus[$info[$i]["customer_id"]];
            }
            if (is_null($info[$i]["item_id"])) {
                array_push($tmp, "no ref.");
                array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
                if (!in_array($info[$i]["id"], $total_amount_counted)) {
                    $total_amount += $info[$i]["total_value"] + $info[$i]["invoice_discount"];
                    array_push($total_amount_counted, $info[$i]["id"]);
                }
                array_push($tmp, self::value_format_custom($info[$i]["total_value"], $this->settings_info));
                array_push($tmp, self::value_format_custom($info[$i]["invoice_discount"], $this->settings_info));
                array_push($tmp, $customer);
                array_push($tmp, $info[$i]["description"]);
            } else {
                $item_info = $items->get_item($info[$i]["item_id"]);
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
                if (!in_array($info[$i]["id"], $total_amount_counted)) {
                    $total_amount += $info[$i]["total_value"] + $info[$i]["invoice_discount"];
                    array_push($total_amount_counted, $info[$i]["id"]);
                }
                array_push($tmp, self::value_format_custom($info[$i]["total_value"], $this->settings_info));
                array_push($tmp, self::value_format_custom($info[$i]["invoice_discount"], $this->settings_info));
                array_push($tmp, $customer);
                array_push($tmp, $item_info[0]["description"]);
            }
            $date_ = explode(" ", $info[$i]["creation_date"]);
            array_push($tmp, $date_[0]);
            array_push($tmp, (double) $info[$i]["qty"]);
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, (double) $info[$i]["discount"] . " %");
            if (0 < $info[$i]["tax"]) {
                array_push($tmp, number_format($info[$i]["tax"] / 100, 2));
            } else {
                if ($info[$i]["vat"] == 0) {
                    array_push($tmp, $info[$i]["vat"]);
                } else {
                    array_push($tmp, ($info[$i]["vat_value"] - 1) * 100 . " %");
                }
            }
            if (0 < $info[$i]["tax"]) {
                array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"] * (1 + $info[$i]["tax"] / 100), $this->settings_info));
            } else {
                if ($info[$i]["vat"] == 0) {
                    array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info));
                } else {
                    array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"] * $info[$i]["vat_value"], $this->settings_info));
                }
            }
            array_push($data_array["data"], $tmp);
        }
        $commission = $reports->get_total_commission($store_id, $date_range, $vendor_id);
        $data_array["total_amount"] = number_format($total_amount, 2) . " " . $_SESSION["currency_symbol"];
        $data_array["total_commission"] = number_format($commission, 2) . " " . $_SESSION["currency_symbol"];
        echo json_encode($data_array);
    }
    public function get_all_commission_details($vendor_id_, $date_)
    {
        self::giveAccessTo();
        $date = filter_var($date_, self::conversion_php_version_filter());
        $vendor_id = filter_var($vendor_id_, FILTER_SANITIZE_NUMBER_INT);
        $reports = $this->model("reports");
        $date_range = array();
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date == "thismonth") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $commissions = $reports->get_commission_details($date_range, $vendor_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($commissions); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($commissions[$i]["id"]));
            array_push($tmp, number_format($commissions[$i]["total_value"], 2) . " <b>" . $_SESSION["currency_symbol"] . "</b>");
            array_push($tmp, number_format($commissions[$i]["vendor_commission_percentage"], 1) . " %");
            array_push($tmp, number_format($commissions[$i]["total_value"] * $commissions[$i]["vendor_commission_percentage"] / 100, 3) . " <b>" . $_SESSION["currency_symbol"] . "</b>");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getReportByDay_items($store_id_, $date_, $_category_id, $_sales_type, $_category_parent)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
        $category_parent_id = filter_var($_category_parent, FILTER_SANITIZE_NUMBER_INT);
        $sales_type = filter_var($_sales_type, FILTER_SANITIZE_NUMBER_INT);
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
        $reports = $this->model("reports");
        $items = $this->model("items");
        if ($_SESSION["role"] == 1) {
            $info = $reports->getReportByDay_by_item($store_id, $date_range, $category_id, $sales_type, $category_parent_id);
        } else {
            $info = $reports->getReportByDay_by_item_switch($store_id, $date_range, $category_id, $sales_type, $category_parent_id);
        }
        $info_stock = $items->get_items_in_store($store_id, 0, 0);
        $info_stock_stk = array();
        for ($i = 0; $i < count($info_stock); $i++) {
            $info_stock_stk[$info_stock[$i]["item_id"]] = $info_stock[$i]["quantity"];
        }
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            if (is_null($info[$i]["item_id"])) {
                array_push($tmp, "no ref.");
                array_push($tmp, "");
                array_push($tmp, $info[$i]["description"]);
            } else {
                if (array_key_exists($info[$i]["item_id"], $items_info_db)) {
                    $item_info = $items_info_db[$info[$i]["item_id"]];
                } else {
                    $item_info = $items->get_item($info[$i]["item_id"]);
                    $items_info_db[$info[$i]["item_id"]] = $item_info;
                }
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                if (strlen($item_info[0]["barcode"]) < 5) {
                    array_push($tmp, sprintf("%05s", $item_info[0]["barcode"]));
                } else {
                    array_push($tmp, $item_info[0]["barcode"]);
                }
                array_push($tmp, $item_info[0]["description"]);
            }
            array_push($tmp, (double) $info[$i]["qty"]);
            if (isset($info_stock_stk[$info[$i]["item_id"]])) {
                $stk_av = "<span class='stk'>" . floor($info_stock_stk[$info[$i]["item_id"]]) . "</span>";
            } else {
                $stk_av = "<span class='stk_na'>(NA)</span>";
            }
            array_push($tmp, $stk_av);
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($info[$i]["buying_cost"], $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, number_format((double) $info[$i]["discount"], 2) . " %");
            if ($info[$i]["vat"] == 0) {
                array_push($tmp, $info[$i]["vat"]);
            } else {
                array_push($tmp, ($info[$i]["vat_value"] - 1) * 100 . " %");
            }
            $price_after_discount = $info[$i]["selling_price"] - $info[$i]["selling_price"] * $info[$i]["discount"] / 100;
            if (0 < $info[$i]["vat"]) {
                $price_after_discount = $price_after_discount * $info[$i]["vat_value"];
            }
            array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
            array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info));
            $cost_after_vat = 0;
            if ($info[$i]["vat"] == 0) {
                $cost_after_vat = $info[$i]["final_cost_vat_qty"];
            } else {
                $cost_after_vat = $info[$i]["final_cost_vat_qty"];
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($info[$i]["profit"], $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            $price_after_vat = 0;
            if ($info[$i]["vat"] == 0) {
                $price_after_vat = $info[$i]["final_price_disc_qty"];
            } else {
                $price_after_vat = $info[$i]["final_price_disc_qty"] * $info[$i]["vat_value"];
            }
            if (0 < $price_after_vat) {
                $margin_profit = ($price_after_vat - $cost_after_vat) / $price_after_vat * 100;
            } else {
                $margin_profit = 0;
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, number_format($margin_profit, 2) . " %");
            } else {
                array_push($tmp, self::critical_data());
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getReportByDay_credit_transfers($store_id_, $date_, $_mobile_operator, $_credit_or_days)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $mobile_operator = filter_var($_mobile_operator, FILTER_SANITIZE_NUMBER_INT);
        $credit_or_days = filter_var($_credit_or_days, FILTER_SANITIZE_NUMBER_INT);
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
        $reports = $this->model("reports");
        $items = $this->model("items");
        if ($credit_or_days == 0) {
            $info = $reports->getReportByDay_credits_transfers($store_id, $date_range, $mobile_operator);
        } else {
            $info = $reports->getReportByDay_days_transfers($store_id, $date_range, $mobile_operator);
        }
        $info_stock = $items->get_items_in_store($store_id, 0, 0);
        $info_stock_stk = array();
        for ($i = 0; $i < count($info_stock); $i++) {
            $info_stock_stk[$info_stock[$i]["item_id"]] = $info_stock[$i]["quantity"];
        }
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
            if (is_null($info[$i]["item_id"])) {
                $note = "";
                if (0 < strlen($info[$i]["payment_note"])) {
                    $note = " ( " . $info[$i]["payment_note"] . " )";
                }
                array_push($tmp, $info[$i]["description"] . $note);
            } else {
                if (array_key_exists($info[$i]["item_id"], $items_info_db)) {
                    $item_info = $items_info_db[$info[$i]["item_id"]];
                } else {
                    $item_info = $items->get_item($info[$i]["item_id"]);
                    $items_info_db[$info[$i]["item_id"]] = $item_info;
                }
                array_push($tmp, $item_info[0]["description"]);
            }
            array_push($tmp, $info[$i]["creation_date"]);
            if (isset($info_stock_stk[$info[$i]["item_id"]])) {
                $stk_av = "<span class='stk'>" . floor($info_stock_stk[$info[$i]["item_id"]]) . "</span>";
            } else {
                $stk_av = "<span class='stk_na'>(NA)</span>";
            }
            array_push($tmp, (double) $info[$i]["qty"]);
            array_push($tmp, $stk_av);
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, self::value_format_custom((double) $info[$i]["discount"], $this->settings_info) . " %");
            if ($info[$i]["vat"] == 0) {
                array_push($tmp, $info[$i]["vat"]);
            } else {
                array_push($tmp, ($info[$i]["vat_value"] - 1) * 100 . " %");
            }
            $price_after_vat = 0;
            if ($info[$i]["vat"] == 0) {
                $price_after_vat = $info[$i]["final_price_disc_qty"];
                array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info));
            } else {
                $price_after_vat = $info[$i]["final_price_disc_qty"] * $info[$i]["vat_value"];
                array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"] * $info[$i]["vat_value"], $this->settings_info));
            }
            $cost_after_vat = 0;
            if ($info[$i]["vat"] == 0) {
                $cost_after_vat = $info[$i]["final_cost_vat_qty"];
            } else {
                $cost_after_vat = $info[$i]["final_cost_vat_qty"];
            }
            array_push($tmp, self::value_format_custom($info[$i]["profit"], $this->settings_info));
            if (0 < $price_after_vat) {
                $margin_profit = ($price_after_vat - $cost_after_vat) / $price_after_vat * 100;
            } else {
                $margin_profit = 0;
            }
            array_push($tmp, self::value_format_custom($margin_profit, $this->settings_info) . " %");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getReportByDay($store_id_, $date_, $_category_id, $sales_type, $_parent_category, $_supplier_id)
    {
        self::giveAccessTo();
        $store_id = $_SESSION["store_id"];
        $categories = $this->model("categories");
        $date = filter_var($date_, self::conversion_php_version_filter());
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
        $parent_category = filter_var($_parent_category, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
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
        $reports = $this->model("reports");
        $items = $this->model("items");
        $info = $reports->getReportByDay($store_id, $date_range, $category_id, $sales_type, $parent_category, $supplier_id);
        $info_stock = $items->get_items_in_store($store_id, 0, 0);
        $info_stock_stk = array();
        for ($i = 0; $i < count($info_stock); $i++) {
            $info_stock_stk[$info_stock[$i]["item_id"]] = $info_stock[$i]["quantity"];
        }
        $categories_info = $categories->getAllCategoriesEvenDeleted();
        $categories_array = array();
        for ($i = 0; $i < count($categories_info); $i++) {
            $categories_array[$categories_info[$i]["id"]] = $categories_info[$i];
        }
        $categories_parent_info = $categories->getAllParentCategoriesEvenDeleted();
        $categories_parent_array = array();
        for ($i = 0; $i < count($categories_parent_info); $i++) {
            $categories_parent_array[$categories_parent_info[$i]["id"]] = $categories_parent_info[$i];
        }
        $items_info_db = array();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_invoice($info[$i]["id"]));
            if (is_null($info[$i]["item_id"])) {
                array_push($tmp, "no ref.");
                array_push($tmp, "");
                array_push($tmp, "");
                array_push($tmp, $info[$i]["description"]);
            } else {
                if (array_key_exists($info[$i]["item_id"], $items_info_db)) {
                    $item_info = $items_info_db[$info[$i]["item_id"]];
                } else {
                    $item_info = $items->get_item($info[$i]["item_id"]);
                    $items_info_db[$info[$i]["item_id"]] = $item_info;
                }
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                if (strlen($item_info[0]["barcode"]) < 5) {
                    array_push($tmp, sprintf("%05s", $item_info[0]["barcode"]));
                } else {
                    array_push($tmp, $item_info[0]["barcode"]);
                }
                if (is_null($info[$i]["item_id"])) {
                    array_push($tmp, "");
                } else {
                    array_push($tmp, $item_info[0]["sku_code"]);
                }
                if ($item_info[0]["sku_code"] != NULL && $item_info[0]["sku_code"] != "") {
                    array_push($tmp, $item_info[0]["description"] . " (" . $item_info[0]["sku_code"] . ")");
                } else {
                    array_push($tmp, $item_info[0]["description"]);
                }
            }
            array_push($tmp, $info[$i]["creation_date"]);
            if (isset($info_stock_stk[$info[$i]["item_id"]])) {
                $stk_av = "<span class='stk'>" . floor($info_stock_stk[$info[$i]["item_id"]]) . "</span>";
            } else {
                $stk_av = "<span class='stk_na'>(NA)</span>";
            }
            array_push($tmp, (double) $info[$i]["qty"]);
            array_push($tmp, $stk_av);
            $final_cost = 0;
            if ($info[$i]["vat"]) {
                $final_cost = floatval($info[$i]["buying_cost"] * $info[$i]["vat_value"]);
            } else {
                $final_cost = floatval($info[$i]["buying_cost"]);
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, "<input onchange='update_cost(this," . $info[$i]["invit"] . ")' class='costinp' type='text' value='" . self::value_format_custom($final_cost, $this->settings_info) . "'>");
            } else {
                array_push($tmp, self::critical_data());
            }
            array_push($tmp, self::value_format_custom($info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, number_format((double) $info[$i]["discount"], 2) . " %");
            if ($info[$i]["vat"] == 0) {
                array_push($tmp, $info[$i]["vat"]);
            } else {
                array_push($tmp, ($info[$i]["vat_value"] - 1) * 100 . " %");
            }
            array_push($tmp, self::value_format_custom($info[$i]["final_price_disc_qty"], $this->settings_info));
            $cost_after_vat = 0;
            if ($info[$i]["vat"] == 0) {
                $cost_after_vat = $info[$i]["final_cost_vat_qty"];
            } else {
                $cost_after_vat = $info[$i]["final_cost_vat_qty"];
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                array_push($tmp, self::value_format_custom($info[$i]["profit"], $this->settings_info));
            } else {
                array_push($tmp, self::critical_data());
            }
            $price_after_vat = 0;
            if ($info[$i]["vat"] == 0) {
                $price_after_vat = $info[$i]["final_price_disc_qty"];
            } else {
                $price_after_vat = $info[$i]["final_price_disc_qty"] * $info[$i]["vat_value"];
            }
            if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
                if (0 < $price_after_vat) {
                    $margin_profit = number_format(($price_after_vat - $cost_after_vat) / $price_after_vat * 100, 2) . " %";
                } else {
                    $margin_profit = 0 . " %";
                }
            } else {
                $margin_profit = self::critical_data();
            }
            array_push($tmp, $margin_profit);
            if (is_null($info[$i]["item_id"])) {
                array_push($tmp, "");
                array_push($tmp, "");
            } else {
                if (isset($categories_parent_array[$categories_array[$item_info[0]["item_category"]]["parent"]])) {
                    array_push($tmp, $categories_parent_array[$categories_array[$item_info[0]["item_category"]]["parent"]]["name"]);
                } else {
                    array_push($tmp, "");
                }
                if (isset($categories_array[$item_info[0]["item_category"]])) {
                    array_push($tmp, $categories_array[$item_info[0]["item_category"]]["description"]);
                } else {
                    array_push($tmp, "");
                }
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getStockInfo($store_id_, $supplier_id_, $_cat, $_subcat, $_price_type_id)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($supplier_id_, FILTER_SANITIZE_NUMBER_INT);
        $price_type_id = filter_var($_price_type_id, FILTER_SANITIZE_NUMBER_INT);
        $cat = filter_var($_cat, FILTER_SANITIZE_NUMBER_INT);
        $subcat = filter_var($_subcat, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $info["totalItems"] = 0;
        $info["total_stock_cost"] = 0;
        $info["total_stock_price"] = 0;
        $info["total_stock_profit"] = 0;
        $items_info = $items->get_items_in_store($store_id, $cat, $subcat);
        for ($i = 0; $i < count($items_info); $i++) {
            if (($items_info[$i]["supplier_reference"] == $supplier_id || $supplier_id == 0) && (substr($items_info[$i]["barcode"], 0, $this->settings_info["number_of_decimal_points"]) === $this->settings_info["plu_prefix"] && $this->settings_info["available_in_stock_include_plu"] == 1 || substr($items_info[$i]["barcode"], 0, $this->settings_info["number_of_decimal_points"]) !== $this->settings_info["plu_prefix"]) && 0 < $items_info[$i]["quantity"]) {
                $info["totalItems"] += $items_info[$i]["quantity"];
                $selling_price = $items_info[$i]["selling_price"];
                if ($price_type_id == 2) {
                    $selling_price = $items_info[$i]["wholesale_price"];
                }
                if ($price_type_id == 3) {
                    $selling_price = $items_info[$i]["second_wholesale_price"];
                }
                $final_cost = 0;
                if ($items_info[$i]["vat"]) {
                    $final_cost = floatval($items_info[$i]["buying_cost"]) * floatval($this->settings_info["vat"]);
                    $price_after_discount = ($selling_price - $selling_price * $items_info[$i]["discount"] / 100) * floatval($this->settings_info["vat"]);
                } else {
                    $final_cost = floatval($items_info[$i]["buying_cost"]);
                    $price_after_discount = $selling_price - $selling_price * $items_info[$i]["discount"] / 100;
                }
                $info["total_stock_cost"] += $final_cost * $items_info[$i]["quantity"];
                $info["total_stock_price"] += $price_after_discount * $items_info[$i]["quantity"];
                $info["total_stock_profit"] += $price_after_discount * $items_info[$i]["quantity"] - $final_cost * $items_info[$i]["quantity"];
            }
        }
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info["totalItems"] = self::global_number_formatter($info["totalItems"], 0);
            $info["total_stock_cost"] = self::global_number_formatter($info["total_stock_cost"], $this->settings_info);
            $info["total_stock_price"] = self::global_number_formatter($info["total_stock_price"], $this->settings_info);
            $info["total_stock_profit"] = self::global_number_formatter($info["total_stock_profit"], $this->settings_info);
        } else {
            $info["totalItems"] = self::critical_data();
            $info["total_stock_cost"] = self::critical_data();
            $info["total_stock_price"] = self::critical_data();
            $info["total_stock_profit"] = self::critical_data();
        }
        echo json_encode($info);
    }
    public function getInfoVat($store_id_, $date_)
    {
        self::giveAccessTo();
        $reports = $this->model("reports");
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
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
        $totalSales_invoices = $reports->getInfoTotalInvoices($store_id, $date_range);
        $totalSales_invoices_vat = $reports->getInfoTotalInvoicesVat($store_id, $date_range);
        $info["total_sales_invoices_after_vat"] = self::global_number_formatter($totalSales_invoices[0]["sum"] + $totalSales_invoices_vat[0]["sum"], $this->settings_info);
        $info["total_sales_invoices"] = self::global_number_formatter($totalSales_invoices[0]["sum"], $this->settings_info);
        $info["total_sales_invoices_vat"] = self::global_number_formatter($totalSales_invoices_vat[0]["sum"], $this->settings_info);
        echo json_encode($info);
    }
    public function getInfo_mobile_transfer($store_id_, $date_, $_operator_id)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $operator_id = filter_var($_operator_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $reports = $this->model("reports");
        $expenses = $this->model("expenses");
        $creditnote = $this->model("creditnote");
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
        $profit = $reports->profit_mobile_transfers($store_id, $date_range, $operator_id);
        $totalTransfers = $reports->totalTransfers($store_id, $date_range, $operator_id);
        $totalTransfers_sms_cost = $reports->totalTransfersSMSCost($store_id, $date_range, $operator_id);
        $info = array();
        $info["total_profit"] = self::value_format_custom($profit[0]["sum"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
        $info["total_transfers"] = self::value_format_custom($totalTransfers[0]["sum"], $this->settings_info);
        $info["total_sms_cost"] = self::value_format_custom($totalTransfers_sms_cost[0]["sum"], $this->settings_info);
        echo json_encode($info);
    }
    public function getInfo($supplier_id_, $date_, $_category_id, $_sales_type, $_category_parent_id)
    {
        self::giveAccessTo();
        $supplier_id = filter_var($supplier_id_, FILTER_SANITIZE_NUMBER_INT);
        $store_id = $_SESSION["store_id"];
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
        $sales_type = filter_var($_sales_type, FILTER_SANITIZE_NUMBER_INT);
        $category_parent_id = filter_var($_category_parent_id, FILTER_SANITIZE_NUMBER_INT);
        $date = filter_var($date_, self::conversion_php_version_filter());
        $reports = $this->model("reports");
        $expenses = $this->model("expenses");
        $creditnote = $this->model("creditnote");
        $wasting = $this->model("wasting");
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
        $profit = $reports->profit($store_id, $date_range, $category_id, $sales_type, $category_parent_id, $supplier_id);
        $totalSales = $reports->totalSalesInfo($store_id, $date_range, $category_id, 1, $sales_type, $category_parent_id, $supplier_id);
        $totalSalesCreditCard = $reports->totalSalesInfo($store_id, $date_range, $category_id, 3, $sales_type, $category_parent_id, $supplier_id);
        $total_sales_cheques = $reports->totalSalesInfo($store_id, $date_range, $category_id, 2, $sales_type, $category_parent_id, $supplier_id);
        $totalDebtsSales = $reports->totalDebtsSalesInfo($store_id, $date_range, $category_id, $sales_type, $category_parent_id, $supplier_id);
        $manual_discount = $reports->totalManualDiscount($store_id, $date_range, $sales_type, $supplier_id, $category_parent_id);
        $total_expenses = $expenses->getExpensesByIntervalOfDate($store_id, $date_range);
        $total_credit_notes = $creditnote->get_total_creditnote($store_id, $date_range);
        $info_wasting = array();
        list($info_wasting["start_date"], $info_wasting["end_date"]) = $date_range;
        $_total_wasting = $wasting->get_total_wasting($info_wasting);
        $info = array();
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info["total_profit"] = self::value_format_custom($profit[0]["sum"], $this->settings_info);
        } else {
            $info["total_profit"] = self::critical_data();
        }
        $manual_discount_pl = $reports->totalManualDiscount_PL($store_id, $date_range, $sales_type, $supplier_id, $category_parent_id);
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info["tm_discount_pl"] = self::value_format_custom($manual_discount_pl[0]["sum"], $this->settings_info);
        } else {
            $info["tm_discount_pl"] = self::critical_data();
        }
        $profit__ = $reports->profit__($store_id, $date_range, $category_id, $sales_type, $category_parent_id, $supplier_id);
        $net_profit = $profit__[0]["sum"] - $total_expenses[0]["sum"];
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 0) {
            $info["total_net_profit"] = self::value_format_custom($net_profit, $this->settings_info);
        } else {
            $info["total_net_profit"] = self::critical_data();
        }
        $info["total_sales"] = self::value_format_custom($totalSales[0]["sum"] - $totalDebtsSales[0]["sum"], $this->settings_info);
        $info["total_sales_creditcards"] = self::value_format_custom($totalSalesCreditCard[0]["sum"], $this->settings_info);
        $info["total_sales_cheques"] = self::value_format_custom($total_sales_cheques[0]["sum"], $this->settings_info);
        $info["manual_discount"] = self::value_format_custom(abs($manual_discount[0]["sum"]), $this->settings_info);
        $info["total_expenses"] = self::value_format_custom($total_expenses[0]["sum"], $this->settings_info);
        $info["total_sales_debts"] = self::value_format_custom($totalDebtsSales[0]["sum"], $this->settings_info);
        $info["total_sales_total"] = self::value_format_custom($totalSalesCreditCard[0]["sum"] + $total_sales_cheques[0]["sum"] + $totalDebtsSales[0]["sum"] + $totalSales[0]["sum"] - $totalDebtsSales[0]["sum"], $this->settings_info);
        $info["total_credit_notes"] = self::value_format_custom($total_credit_notes[0]["sum"], $this->settings_info);
        $info["total_wasting"] = self::value_format_custom($_total_wasting[0]["sum"], $this->settings_info);
        if (isset($_SESSION["hide_critical_data"]) && $_SESSION["hide_critical_data"] == 1) {
            $info["total_sales"] = self::critical_data();
            $info["total_sales_creditcards"] = self::critical_data();
            $info["total_sales_cheques"] = self::critical_data();
            $info["total_sales_debts"] = self::critical_data();
            $info["total_sales_total"] = self::critical_data();
        }
        echo json_encode($info);
    }
    public function getWastingItems($date_range_, $vendor_id)
    {
        $wasting = $this->model("wasting");
        $items = $this->model("items");
        $date_range_para = filter_var($date_range_, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date_range_para == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date_range_para);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $user = $this->model("user");
        $employees_info = $user->getAllUsersEvenDeleted();
        $emp = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $emp[$employees_info[$i]["id"]] = $employees_info[$i];
        }
        $items_info = $items->getAllItemsEvenDeleted();
        $items_info_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $items_info_array[$items_info[$i]["id"]] = $items_info[$i];
        }
        $filter = array();
        $filter["vendor"] = $vendor_id;
        $filter["date"] = $date_range;
        $wastings = $wasting->get_all_wasting_items_filtration($filter);
        $data_array["data"] = array();
        for ($i = 0; $i < count($wastings); $i++) {
            $tmp = array();
            array_push($tmp, $wastings[$i]["id"]);
            array_push($tmp, $items_info_array[$wastings[$i]["item_id"]]["description"]);
            array_push($tmp, $wastings[$i]["creation_date"]);
            array_push($tmp, $emp[$wastings[$i]["user_id"]]["username"]);
            array_push($tmp, self::value_format_custom($wastings[$i]["cost"], $this->settings_info));
            array_push($tmp, self::value_format_custom($wastings[$i]["price"], $this->settings_info));
            array_push($tmp, $wastings[$i]["qty"]);
            array_push($tmp, self::value_format_custom($wastings[$i]["cost"] * $wastings[$i]["qty"], $this->settings_info));
            array_push($tmp, self::value_format_custom($wastings[$i]["price"] * $wastings[$i]["qty"], $this->settings_info));
            array_push($tmp, "");
            array_push($tmp, $wastings[$i]["note"]);
            array_push($tmp, "");
            $data_array["total_price"] += $wastings[$i]["price"] * $wastings[$i]["qty"];
            array_push($data_array["data"], $tmp);
        }
        $data_array["total_price"] = self::value_format_custom($data_array["total_price"], $this->settings_info);
        echo json_encode($data_array);
    }
    public function getLacksItems($store_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $reports = $this->model("reports");
        $outside_connection_ = $this->model("outside_connection_");
        $info = $reports->getLacksItems($store_id);
        $measure = $this->model("measures");
        $measures = $measure->getMeasures();
        $measures_info = array();
        for ($i = 0; $i < count($measures); $i++) {
            $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
        }
        $all_stock_items = array();
        $connection_problem = false;
        if ($_SESSION["global_admin_exist"] == 1) {
            $store = $this->model("store");
            $warehouses = $store->getWarehouses();
            for ($i = 0; $i < count($warehouses); $i++) {
                $custom_connection = my_sql::custom_connection($warehouses[$i]["ip_address"], $warehouses[$i]["username"], $warehouses[$i]["password"], $warehouses[$i]["db"]);
                if ($custom_connection) {
                    $allStockOfLocation = $outside_connection_->getAllStockOfLocation($custom_connection);
                    for ($j = 0; $j < count($allStockOfLocation); $j++) {
                        $all_stock_items[$allStockOfLocation[$j]["item_id"]] = (double) $allStockOfLocation[$j]["quantity"];
                    }
                } else {
                    $connection_problem = true;
                }
            }
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            if (0 < $all_stock_items[$info[$i]["item_id"]] || count($all_stock_items) == 0) {
                $tmp = array();
                array_push($tmp, self::idFormat_item($info[$i]["item_id"]));
                if (strlen($info[$i]["barcode"]) < 5) {
                    array_push($tmp, sprintf("%05s", $info[$i]["barcode"]));
                } else {
                    array_push($tmp, $info[$i]["barcode"]);
                }
                array_push($tmp, $info[$i]["description"]);
                $final_cost = 0;
                if ($info[$i]["vat"]) {
                    $final_cost = floatval($info[$i]["buying_cost"] * $this->settings_info["vat"]);
                } else {
                    $final_cost = floatval($info[$i]["buying_cost"]);
                }
                array_push($tmp, self::global_number_formatter($final_cost, $this->settings_info));
                $price_after_discount = $info[$i]["selling_price"] - $info[$i]["selling_price"] * $info[$i]["discount"] / 100;
                if ($info[$i]["vat"] == 1) {
                    $price_after_discount = $price_after_discount * $this->settings_info["vat"];
                }
                if ($this->settings_info["enable_wholasale"] == 0) {
                    array_push($tmp, self::global_number_formatter($price_after_discount, $this->settings_info));
                } else {
                    array_push($tmp, self::global_number_formatter($price_after_discount, $this->settings_info) . " <b>/</b> " . self::global_number_formatter($info[$i]["wholesale_price"], $this->settings_info));
                }
                $measure_symb = "";
                if ($info[$i]["unit_measure_id"] != NULL) {
                    $measure_symb = $measures_info[$info[$i]["unit_measure_id"]];
                }
                if ($connection_problem) {
                    array_push($tmp, (double) $info[$i]["quantity"] . " cnx stock error");
                } else {
                    array_push($tmp, (double) $info[$i]["quantity"]);
                }
                array_push($tmp, $info[$i]["name"]);
                array_push($tmp, "");
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