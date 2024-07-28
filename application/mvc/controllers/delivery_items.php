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
class delivery_items extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo(array(2));
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function delivery()
    {
        self::giveAccessTo();
        $this->view("delivery_items/delivery");
    }
    public function report()
    {
        self::giveAccessTo();
        $this->view("delivery_items/report");
    }
    public function supplier_statement()
    {
        self::giveAccessTo();
        $this->view("delivery_items/supplier_statement");
    }
    public function all_packages()
    {
        self::giveAccessTo();
        $this->view("delivery_items/all_packages");
    }
    public function getReport($_date_filter)
    {
        self::giveAccessTo();
        $date_filter = filter_var($_date_filter, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $expenses = $this->model("expenses");
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date_filter == "today") {
            $date_range[0] = date("Y-m-d");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $total_expenses = $expenses->getExpensesByIntervalOfDate(0, $date_range);
        $total_delivery_profit = $delivery->getTotalDeliveryProfit($date_range);
        $info = array();
        $info["cash_in"] = number_format(0, 2);
        $info["cash_out"] = number_format(0, 2);
        $info["cash_current"] = number_format(0, 2);
        $info["delivery_profit"] = number_format($total_delivery_profit, $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        $info["total_expense"] = number_format($total_expenses[0]["sum"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        $info["total_profit"] = number_format($total_delivery_profit - $total_expenses[0]["sum"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info);
    }
    public function update_pi_delivery_sheet($_sheet_id)
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $sheet_id = filter_var($_sheet_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery->update_pi_delivery_sheet($sheet_id);
        echo json_encode(array());
    }
    public function check_deliver_pi()
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $stock = $this->model("stock");
        $query = "select * from plugin_deliveries where id not in (select invoice_reference from receive_stock_invoices where deleted=0) and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            $currency = $this->model("currency");
            $currencies = $currency->getAllCurrencies();
            $currencies_info = array();
            $currency_default_id = 0;
            for ($k = 0; $k < count($currencies); $k++) {
                $currencies_info[$currencies[$k]["id"]] = $currencies[$k];
                if ($currencies[$k]["system_default"] == 1) {
                    $currency_default_id = $currencies[$k]["id"];
                }
            }
            $info_invoice = array();
            $info_invoice["supplier_id"] = $result[$i]["supplier_id"];
            $info_invoice["currency_id"] = $currency_default_id;
            $info_invoice["receive_invoice_date"] = date("Y-m-d H:i:s");
            $info_invoice["delivery_date"] = date("Y-m-d H:i:s");
            $info_invoice["invoice_subtotal"] = 0;
            $info_invoice["invoice_discount"] = 0;
            $info_invoice["invoice_total"] = 0;
            $info_invoice["invoice_tax"] = 0;
            $info_invoice["action_type"] = 0;
            $info_invoice["autofill_id"] = 1;
            $info_invoice["invoice_reference"] = $result[$i]["id"];
            $stock->addStockInvoice($info_invoice);
            $delivery->update_pi_delivery_sheet($result[$i]["id"]);
        }
    }
    public function create_new_delivery()
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $stock = $this->model("stock");
        $latest_id = $delivery->create_new_delivery();
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
        $info_invoice["supplier_id"] = "NULL";
        $info_invoice["currency_id"] = $currency_default_id;
        $info_invoice["receive_invoice_date"] = date("Y-m-d H:i:s");
        $info_invoice["delivery_date"] = date("Y-m-d H:i:s");
        $info_invoice["invoice_subtotal"] = 0;
        $info_invoice["invoice_discount"] = 0;
        $info_invoice["invoice_total"] = 0;
        $info_invoice["invoice_tax"] = 0;
        $info_invoice["action_type"] = 0;
        $info_invoice["autofill_id"] = 1;
        $info_invoice["invoice_reference"] = $latest_id;
        $stock->addStockInvoice($info_invoice);
        $info = array();
        $info["id"] = $latest_id;
        echo json_encode($info);
    }
    public function auto_correct_pi()
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $deliveries = $delivery->get_all_deliveries_sheets();
        for ($i = 0; $i < count($deliveries); $i++) {
            $delivery->update_pi_delivery_sheet($deliveries[$i]["id"]);
        }
    }
    public function delete_delivery_item($_delivery_item_id)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $delivery->delete_delivery_item($delivery_item_id);
        $info = $delivery->get_deliveryid_of_itemdelivery_id($delivery_item_id);
        $delivery->update_pi_delivery_sheet($info[0]["delivery_id"]);
        echo json_encode(array());
    }
    public function customer_changed_delivery($_delivery_item_id, $_customer_id)
    {
        self::giveAccessTo();
        $customers = $this->model("customers");
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $delivery->customer_changed_delivery($delivery_item_id, $customer_id);
        echo json_encode($customers->getCustomersById($customer_id));
    }
    public function package_supplier_paid($_package_id, $_status)
    {
        self::giveAccessTo();
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
        $package_id = filter_var($_package_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $delivery->package_supplier_paid($package_id, $status);
        echo json_encode(array());
    }
    public function search_wb_number($_wb_number)
    {
        self::giveAccessTo();
        $wb_number = filter_var($_wb_number, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $info = $delivery->search_wb_number($wb_number);
        echo json_encode($info);
    }
    public function getAllPackagesForSupplier($_supplier_id, $_paid_filter, $_delivery_filter)
    {
        self::giveAccessTo();
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $paid_filter = filter_var($_paid_filter, FILTER_SANITIZE_NUMBER_INT);
        $delivery_filter = filter_var($_delivery_filter, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $all_delivery_packages = $delivery->getAllPackagesForSupplier($supplier_id, $paid_filter, $delivery_filter);
        $customers = $this->model("customers");
        $customers_info = $customers->getCustomersEvenDeleted();
        $customers_array = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $customers_array[$customers_info[$i]["id"]] = $customers_info[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($all_delivery_packages); $i++) {
            $tmp = array();
            array_push($tmp, $all_delivery_packages[$i]["wb_number"]);
            array_push($tmp, $all_delivery_packages[$i]["sending_date"]);
            array_push($tmp, $all_delivery_packages[$i]["customer_name"]);
            array_push($tmp, number_format($all_delivery_packages[$i]["collection_value"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($all_delivery_packages[$i]["delivery_charge"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($all_delivery_packages[$i]["pickapp_share"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($all_delivery_packages[$i]["our_share"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($all_delivery_packages[$i]["net_amout"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            if ($all_delivery_packages[$i]["paid_supplier"] == 1) {
                array_push($tmp, "<b>Paid</b>");
            } else {
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getWellInfo($supplier_id, $paid_filter, $delivery_filter)
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $info["total_collection"] = number_format($delivery->getTotalCollection($supplier_id, $paid_filter, $delivery_filter), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        $info["total_delivery_charges"] = number_format($delivery->getTotalDelivery_charge($supplier_id, $paid_filter, $delivery_filter), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        $info["total_net_amount"] = number_format($delivery->getTotalNetAmount($supplier_id, $paid_filter, $delivery_filter), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        $info["total_pickapp_share"] = number_format($delivery->getTotalPickapp_share($supplier_id, $paid_filter, $delivery_filter), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        $info["total_our_share"] = number_format($delivery->getTotalOur_share($supplier_id, $paid_filter, $delivery_filter), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info);
    }
    public function hide($_delivery_sheet_id)
    {
        self::giveAccessTo();
        $delivery_sheet_id = filter_var($_delivery_sheet_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $delivery->hide($delivery_sheet_id);
        echo json_encode(array());
    }
    public function get_all_delivery_items($_delivery_sheet_id)
    {
        self::giveAccessTo();
        $delivery_sheet_id = filter_var($_delivery_sheet_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $customers = $this->model("customers");
        $all_delivery_packages = $delivery->get_all_delivery_items($delivery_sheet_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($all_delivery_packages); $i++) {
            $tmp = array();
            array_push($tmp, $all_delivery_packages[$i]["id"]);
            array_push($tmp, "<input onchange='cusname_changed(" . $all_delivery_packages[$i]["id"] . ")' type='text' value='" . $all_delivery_packages[$i]["customer_name"] . "' class='input_sm' id='cusname_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input onchange='cusaddr_changed(" . $all_delivery_packages[$i]["id"] . ")' type='text' value='" . $all_delivery_packages[$i]["customer_address"] . "' class='input_sm' id='cusaddr_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input onchange='cusphone_changed(" . $all_delivery_packages[$i]["id"] . ")' type='text' value='" . $all_delivery_packages[$i]["customer_phone"] . "' class='input_sm' id='cusphone_" . $all_delivery_packages[$i]["id"] . "'>");
            $sd = explode(" ", $all_delivery_packages[$i]["sending_date"]);
            array_push($tmp, "<input type='text' value='" . $sd[0] . "' class='input_sm datepicker' id='date_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input onchange='wb_changed(" . $all_delivery_packages[$i]["id"] . ")' type='text' value='" . $all_delivery_packages[$i]["wb_number"] . "' class='input_sm' id='wb_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input onchange='collection_changed(" . $all_delivery_packages[$i]["id"] . ");update_auto_netamount_value(" . $all_delivery_packages[$i]["id"] . ");' type='text' value='" . number_format($all_delivery_packages[$i]["collection_value"], $this->settings_info["number_of_decimal_points"]) . "' type='text' value='0' class='input_sm only_numeric' id='cl_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input onchange='deliverycharge_changed(" . $all_delivery_packages[$i]["id"] . ");update_auto_netamount_value(" . $all_delivery_packages[$i]["id"] . ");' type='text' value='" . number_format($all_delivery_packages[$i]["delivery_charge"], $this->settings_info["number_of_decimal_points"]) . "' type='text' value='0' class='input_sm only_numeric' id='dc_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input onchange='pickappshare_changed(" . $all_delivery_packages[$i]["id"] . ");update_auto_ourshare_value(" . $all_delivery_packages[$i]["id"] . ")' type='text' value='" . number_format($all_delivery_packages[$i]["pickapp_share"], $this->settings_info["number_of_decimal_points"]) . "' type='text' value='0' class='input_sm only_numeric' id='pas_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input readonly type='text' value='" . number_format($all_delivery_packages[$i]["our_share"], $this->settings_info["number_of_decimal_points"]) . "' type='text' value='0' class='input_sm only_numeric' id='ours_" . $all_delivery_packages[$i]["id"] . "'>");
            array_push($tmp, "<input readonly onchange='netamount_changed(" . $all_delivery_packages[$i]["id"] . ")' type='text' value='" . number_format($all_delivery_packages[$i]["net_amout"], $this->settings_info["number_of_decimal_points"]) . "' type='text' value='0' class='input_sm only_numeric' id='na_" . $all_delivery_packages[$i]["id"] . "'>");
            if ($all_delivery_packages[$i]["status"] == 1) {
                array_push($tmp, "<input onchange='delivered_changed(" . $all_delivery_packages[$i]["id"] . ")' checked class='delst' type='checkbox' id='de_" . $all_delivery_packages[$i]["id"] . "'>");
            } else {
                array_push($tmp, "<input onchange='delivered_changed(" . $all_delivery_packages[$i]["id"] . ")' class='delst' type='checkbox' id='de_" . $all_delivery_packages[$i]["id"] . "'>");
            }
            if ($all_delivery_packages[$i]["paid_date"] != NULL) {
                $p_date = explode(" ", $all_delivery_packages[$i]["paid_date"]);
            } else {
                $p_date = explode(" ", " ");
            }
            if ($all_delivery_packages[$i]["paid_supplier"] == 1) {
                array_push($tmp, "<input onchange='paid_changed(" . $all_delivery_packages[$i]["id"] . "," . $_delivery_sheet_id . ");' checked class='delst' type='checkbox' id='paidsup_" . $all_delivery_packages[$i]["id"] . "'><span class='paid_date'>" . $p_date[0] . "</span>");
            } else {
                array_push($tmp, "<input onchange='paid_changed(" . $all_delivery_packages[$i]["id"] . "," . $_delivery_sheet_id . ");' class='delst' type='checkbox' id='paidsup_" . $all_delivery_packages[$i]["id"] . "'><span class='paid_date'>" . $p_date[0] . "</span>");
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function package_delivered($_package_id, $_status)
    {
        self::giveAccessTo();
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
        $package_id = filter_var($_package_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $delivery->package_delivered($package_id, $status);
        echo json_encode(array());
    }
    public function delete_sheet($_sheet_id)
    {
        self::giveAccessTo();
        $sheet_id = filter_var($_sheet_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $delivery->delete_sheet($sheet_id);
        echo json_encode(array());
    }
    public function collection_changed($_delivery_item_id, $_col)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $col = filter_var($_col, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $delivery = $this->model("delivery");
        $delivery->collection_changed($delivery_item_id, $col);
        echo json_encode(array());
    }
    public function deliverycharge_changed($_delivery_item_id, $_dc)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $dc = filter_var($_dc, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $delivery = $this->model("delivery");
        $delivery->deliverycharge_changed($delivery_item_id, $dc);
        echo json_encode(array());
    }
    public function netamount_changed($_delivery_item_id, $_na)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $na = filter_var($_na, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $delivery->netamount_changed($delivery_item_id, $na);
        $info = $delivery->get_deliveryid_of_itemdelivery_id($delivery_item_id);
        $delivery->update_pi_delivery_sheet($info[0]["delivery_id"]);
        echo json_encode(array());
    }
    public function get_deliveryid_of_itemdelivery_id($_item_d_id)
    {
        self::giveAccessTo();
        $item_d_id = filter_var($_item_d_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $info = $delivery->get_deliveryid_of_itemdelivery_id($item_d_id);
        return $info[0]["delivery_id"];
    }
    public function wb_changed($_delivery_item_id, $_wb)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $wb = filter_var($_wb, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $delivery->wb_changed($delivery_item_id, $wb);
        $info = array();
        $info["count"] = $delivery->wb_count($wb);
        echo json_encode($info);
    }
    public function wb_changed_count($_wb)
    {
        self::giveAccessTo();
        $wb = filter_var($_wb, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $info = array();
        $info["count"] = $delivery->wb_count($wb);
        echo json_encode($info);
    }
    public function update_sending_date($_delivery_item_id, $_sending_date)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $sending_date = filter_var($_sending_date, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $delivery->update_sending_date($delivery_item_id, $sending_date);
        echo json_encode(array());
    }
    public function add_new_delivery_package($_delivery_sheet_id)
    {
        self::giveAccessTo();
        $delivery_sheet_id = filter_var($_delivery_sheet_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $id = $delivery->add_new_delivery_package($delivery_sheet_id);
        $info = array();
        $info["id"] = $id;
        echo json_encode($info);
    }
    public function get_delivery_needed_info($_delivery_sheet_id)
    {
        self::giveAccessTo(array(2));
        $info = array();
        $info["suppliers"] = array();
        $info["delivery_sheet_info"] = array();
        $delivery_sheet_id = filter_var($_delivery_sheet_id, FILTER_SANITIZE_NUMBER_INT);
        $suppliers = $this->model("suppliers");
        $delivery = $this->model("delivery");
        $suppliers_info = $suppliers->getSuppliers();
        $info["delivery_sheet_info"] = $delivery->get_delivery_sheet_id($delivery_sheet_id);
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $info["suppliers"][$i]["id"] = $suppliers_info[$i]["id"];
            $info["suppliers"][$i]["name"] = $suppliers_info[$i]["name"];
        }
        echo json_encode($info);
    }
    public function getAllPackages($date_filter)
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $suppliers = $this->model("suppliers");
        $customers = $this->model("customers");
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date_filter == "latest") {
            $dt = date("Y-m-d");
            $date_range[0] = date("Y-m-d", strtotime((string) $dt . " -30 day"));
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $suppliers_info = $suppliers->getSuppliers();
        $suppliers_array = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_array[$suppliers_info[$i]["id"]] = $suppliers_info[$i];
        }
        $customers_info = $customers->getCustomersEvenDeleted();
        $customers_array = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $customers_array[$customers_info[$i]["id"]] = $customers_info[$i];
        }
        $info = $delivery->getAllPackages($date_range);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_delivery($info[$i]["delivery_id"]));
            array_push($tmp, self::idFormat_delivery($info[$i]["id"]));
            array_push($tmp, $info[$i]["wb_number"]);
            array_push($tmp, $info[$i]["sending_date"]);
            array_push($tmp, $info[$i]["customer_name"]);
            array_push($tmp, $info[$i]["customer_address"]);
            array_push($tmp, $info[$i]["customer_phone"]);
            array_push($tmp, number_format($info[$i]["collection_value"], $this->settings_info["number_of_decimal_points"]));
            array_push($tmp, number_format($info[$i]["delivery_charge"], $this->settings_info["number_of_decimal_points"]));
            array_push($tmp, number_format($info[$i]["pickapp_share"], $this->settings_info["number_of_decimal_points"]));
            array_push($tmp, number_format($info[$i]["our_share"], $this->settings_info["number_of_decimal_points"]));
            array_push($tmp, number_format($info[$i]["net_amout"], $this->settings_info["number_of_decimal_points"]));
            if ($info[$i]["status"] == 1) {
                array_push($tmp, "<span class='delivered'>Delivered</span>");
            } else {
                array_push($tmp, "<span class='not_yet'>Not Yet</span>");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllDeliveries($date_filter)
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $suppliers = $this->model("suppliers");
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date_filter == "latest") {
            $dt = date("Y-m-d");
            $date_range[0] = date("Y-m-d", strtotime((string) $dt . " -30 day"));
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $suppliers_info = $suppliers->getSuppliers();
        $suppliers_array = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_array[$suppliers_info[$i]["id"]] = $suppliers_info[$i];
        }
        $info = $delivery->getAllDeliveries($date_range);
        $info_sum = $delivery->get_sum($date_range);
        $sum_array = array();
        for ($i = 0; $i < count($info_sum); $i++) {
            $sum_array[$info_sum[$i]["delivery_id"]] = $info_sum[$i];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_delivery($info[$i]["id"]));
            if (isset($suppliers_array[$info[$i]["supplier_id"]]["name"])) {
                array_push($tmp, $suppliers_array[$info[$i]["supplier_id"]]["name"]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, $info[$i]["creation_date"]);
            if (isset($sum_array[$info[$i]["id"]]["del_num"])) {
                array_push($tmp, $sum_array[$info[$i]["id"]]["del_num"]);
            } else {
                array_push($tmp, 0);
            }
            if (isset($sum_array[$info[$i]["id"]]["delivered"])) {
                array_push($tmp, $sum_array[$info[$i]["id"]]["delivered"]);
            } else {
                array_push($tmp, 0);
            }
            if (isset($sum_array[$info[$i]["id"]]["collection_value"])) {
                array_push($tmp, number_format($sum_array[$info[$i]["id"]]["collection_value"], $this->settings_info["number_of_decimal_points"]));
            } else {
                array_push($tmp, number_format(0, $this->settings_info["number_of_decimal_points"]));
            }
            if (isset($sum_array[$info[$i]["id"]]["delivery_charge"])) {
                array_push($tmp, number_format($sum_array[$info[$i]["id"]]["delivery_charge"], $this->settings_info["number_of_decimal_points"]));
            } else {
                array_push($tmp, number_format(0, $this->settings_info["number_of_decimal_points"]));
            }
            if (isset($sum_array[$info[$i]["id"]]["pickapp_share"])) {
                array_push($tmp, number_format($sum_array[$info[$i]["id"]]["pickapp_share"], $this->settings_info["number_of_decimal_points"]));
            } else {
                array_push($tmp, number_format(0, $this->settings_info["number_of_decimal_points"]));
            }
            if (isset($sum_array[$info[$i]["id"]]["our_share"])) {
                array_push($tmp, number_format($sum_array[$info[$i]["id"]]["our_share"], $this->settings_info["number_of_decimal_points"]));
            } else {
                array_push($tmp, number_format(0, $this->settings_info["number_of_decimal_points"]));
            }
            if (isset($sum_array[$info[$i]["id"]]["net_amout"])) {
                array_push($tmp, number_format($sum_array[$info[$i]["id"]]["net_amout"], $this->settings_info["number_of_decimal_points"]));
            } else {
                array_push($tmp, number_format(0, $this->settings_info["number_of_decimal_points"]));
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function update_print($_sheet_id)
    {
        self::giveAccessTo();
        $sheet_id = filter_var($_sheet_id, FILTER_SANITIZE_NUMBER_INT);
        $delivery = $this->model("delivery");
        $delivery->update_print($sheet_id);
        echo json_encode(array());
    }
    public function get_sum_of_print_sheet($sheet_id, $_all)
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $data = $delivery->get_sum_of_all_delivery_items_to_partial_print($sheet_id);
        $info = array();
        $info["sum_net_amount"] = number_format($data, 2);
        echo json_encode($info);
    }
    public function print_sheet($sheet_id, $_all)
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $suppliers = $this->model("suppliers");
        $phones = $this->model("phones");
        $customers = $this->model("customers");
        $suppliers_info = $suppliers->getSuppliers();
        $suppliers_array = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $suppliers_array[$suppliers_info[$i]["id"]] = $suppliers_info[$i];
        }
        $customers_info = $customers->getCustomersEvenDeleted();
        $customers_array = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $customers_array[$customers_info[$i]["id"]] = $customers_info[$i];
        }
        $data["delivery_info"] = $delivery->get_delivery_sheet_id($sheet_id);
        $data["supplier"] = $suppliers_array;
        $data["customers"] = $customers_array;
        $data["supplier_phone"] = $phones->getSupplierContacts($data["delivery_info"][0]["supplier_id"]);
        if ($_all == 0) {
            $data["delivery_packages"] = $delivery->get_all_delivery_items_to_partial_print($sheet_id);
        } else {
            $data["delivery_packages"] = $delivery->get_all_delivery_items_all_print($sheet_id);
        }
        $delivery->update_print($sheet_id);
        $data["currency"] = $this->settings_info["default_currency_symbol"];
        $data["number_of_decimal_points"] = $this->settings_info["number_of_decimal_points"];
        $this->view("delivery_items/print_sheet", $data);
    }
    public function update_supplier_delivery($delivery_id, $supplier_id)
    {
        self::giveAccessTo();
        $delivery = $this->model("delivery");
        $delivery->update_supplier_delivery($delivery_id, $supplier_id);
        $delivery->update_supplier_in_purshace_invoice($delivery_id, $supplier_id);
        echo json_encode(array());
    }
    public function pickappshare_changed($_delivery_item_id, $_dc)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $dc = filter_var($_dc, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $delivery = $this->model("delivery");
        $delivery->pickappshare_changed($delivery_item_id, $dc);
        echo json_encode(array());
    }
    public function ourshare_changed($_delivery_item_id, $_dc)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $dc = filter_var($_dc, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $delivery = $this->model("delivery");
        $delivery->ourshare_changed($delivery_item_id, $dc);
        echo json_encode(array());
    }
    public function cusname_changed($_delivery_item_id, $_wb)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $wb = filter_var($_wb, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $delivery->cusname_changed($delivery_item_id, $wb);
        echo json_encode(array());
    }
    public function cusaddr_changed($_delivery_item_id, $_wb)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $wb = filter_var($_wb, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $delivery->cusaddr_changed($delivery_item_id, $wb);
        echo json_encode(array());
    }
    public function cusphone_changed($_delivery_item_id, $_wb)
    {
        self::giveAccessTo();
        $delivery_item_id = filter_var($_delivery_item_id, FILTER_SANITIZE_NUMBER_INT);
        $wb = filter_var($_wb, self::conversion_php_version_filter());
        $delivery = $this->model("delivery");
        $delivery->cusphone_changed($delivery_item_id, $wb);
        echo json_encode(array());
    }
}

?>