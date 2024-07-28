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
class reportsacc extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        if ($_SESSION["hide_critical_data"] == 1) {
            exit;
        }
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function get_all_sales($_p0, $_p1, $_p2)
    {
        $data_array["data"] = array();
        for ($i = 0; $i < 20; $i++) {
            $tmp = array();
            array_push($tmp, "A" . $i);
            array_push($tmp, "B");
            array_push($tmp, "C");
            array_push($tmp, "A");
            array_push($tmp, "B");
            array_push($tmp, "C");
            array_push($tmp, "A");
            array_push($tmp, "B");
            array_push($tmp, "C");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function dashboard()
    {
        $data = array();
        $this->view("reports/dashboard", $data);
    }
    public function clients()
    {
        $data = array();
        $this->view("reports/clients", $data);
    }
    public function reportsacc_by_clients_created_date($_p0, $_p1, $_p2)
    {
        $return_info = array();
        $customers_limit = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $s_date = filter_var($_p1, self::conversion_php_version_filter());
        $e_date = filter_var($_p2, self::conversion_php_version_filter());
        if ($s_date == "-1" && $e_date == "-1") {
            $s_date = date("Y-m-d", strtotime(date("Y") . "-" . date("M") . "-01"));
            $e_date = date("Y-m-d");
        }
        if (60 < $customers_limit) {
            $customers_limit = 60;
        }
        if ($customers_limit < 0) {
            $customers_limit = 0;
        }
        $reportsacc = $this->model("reportsacc");
        $customers_model = $this->model("customers");
        $return_info["items"] = array();
        $customers = $reportsacc->clients_creation_date($customers_limit, $s_date, $e_date);
        $customers_ids = array();
        for ($i = 0; $i < count($customers); $i++) {
            array_push($customers_ids, $customers[$i]["customer_id"]);
        }
        $customers_details = array();
        if (0 < count($customers_ids)) {
            $customers_details = $customers_model->getCustomersByIDSArray($customers_ids);
        }
        $customers_details_array = array();
        for ($i = 0; $i < count($customers_details); $i++) {
            $customers_details_array[$customers_details[$i]["id"]] = $customers_details[$i];
        }
        for ($i = 0; $i < count($customers); $i++) {
            array_push($return_info["items"], array($customers_details_array[$customers[$i]["customer_id"]]["name"], $customers[$i]["sum"]));
        }
        echo json_encode($return_info);
    }
    public function reportsacc_top_customers($_p0, $_p1)
    {
        $return_info = array();
        $customers_limit = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $customers_date_range = filter_var($_p1, self::conversion_php_version_filter());
        if (60 < $customers_limit) {
            $customers_limit = 60;
        }
        if ($customers_limit < 0) {
            $customers_limit = 0;
        }
        $reportsacc = $this->model("reportsacc");
        $customers_model = $this->model("customers");
        $return_info["items"] = array();
        $customers = $reportsacc->top_sales_customers($customers_date_range, $customers_limit);
        $customers_ids = array();
        for ($i = 0; $i < count($customers); $i++) {
            array_push($customers_ids, $customers[$i]["customer_id"]);
        }
        $customers_details = array();
        if (0 < count($customers_ids)) {
            $customers_details = $customers_model->getCustomersByIDSArray($customers_ids);
        }
        $customers_details_array = array();
        for ($i = 0; $i < count($customers_details); $i++) {
            $customers_details_array[$customers_details[$i]["id"]] = $customers_details[$i];
        }
        for ($i = 0; $i < count($customers); $i++) {
            array_push($return_info["items"], array($customers_details_array[$customers[$i]["customer_id"]]["name"], $customers[$i]["sum"]));
        }
        echo json_encode($return_info);
    }
    public function reportsacc_top_products($_p0, $_p1)
    {
        $return_info = array();
        $products_limit = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $products_date_range = filter_var($_p1, self::conversion_php_version_filter());
        if (60 < $products_limit) {
            $products_limit = 60;
        }
        if ($products_limit < 0) {
            $products_limit = 0;
        }
        $reportsacc = $this->model("reportsacc");
        $items_model = $this->model("items");
        $return_info["items"] = array();
        $products = $reportsacc->top_sales_products($products_date_range, $products_limit);
        $items_ids = array();
        for ($i = 0; $i < count($products); $i++) {
            array_push($items_ids, $products[$i]["item_id"]);
        }
        $item_details = array();
        if (0 < count($items_ids)) {
            $item_details = $items_model->get_item_array($items_ids);
        }
        $item_details_array = array();
        for ($i = 0; $i < count($item_details); $i++) {
            $item_details_array[$item_details[$i]["id"]] = $item_details[$i];
        }
        for ($i = 0; $i < count($products); $i++) {
            array_push($return_info["items"], array($item_details_array[$products[$i]["item_id"]]["description"], $products[$i]["num"]));
        }
        echo json_encode($return_info);
    }
    public function reportsacc_last_days($_p0, $_start_date, $_end_date)
    {
        $reportsacc = $this->model("reportsacc");
        $days_nb = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $start_date = filter_var($_start_date, self::conversion_php_version_filter());
        $end_date = filter_var($_end_date, self::conversion_php_version_filter());
        $lastDays = array();
        $lastMonths_mysql = array();
        if ($start_date != "-1" && $end_date != "-1") {
            $start_date = new DateTime($start_date);
            $end_date = new DateTime($end_date);
            $interval = $start_date->diff($end_date);
            if (31 < $interval->days) {
                exit;
            }
            $current_date = clone $start_date;
            while ($current_date <= $end_date) {
                $lastDays[] = $current_date->format("Y-m-d");
                $lastMonths_mysql[] = $current_date->format("Y-m-d H:i:s");
                $current_date->modify("+1 day");
            }
        } else {
            if (15 < $days_nb) {
                $days_nb = 15;
            }
            if ($days_nb < 0) {
                $days_nb = 0;
            }
            $currentDate = new DateTime();
            for ($i = 1; $i <= $days_nb; $i++) {
                $lastDays[] = $currentDate->format("Y-m-d");
                $lastMonths_mysql[] = $currentDate->format("Y-m-d H:i:s");
                $currentDate->modify("-1 day");
            }
        }
        $data_return = array();
        $data_return["days"] = $lastDays;
        $data_return["total_sales"] = $reportsacc->get_total_sales_of_days($lastMonths_mysql);
        $data_return["total_profits"] = $reportsacc->get_total_profits_of_days($lastMonths_mysql);
        $data_return["total_expenses"] = $reportsacc->get_total_expenses_of_days($lastMonths_mysql);
        echo json_encode($data_return);
    }
    public function reportsacc_last_months($_p0)
    {
        $days_nb = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        if (30 < $days_nb) {
            $days_nb = 30;
        }
        if ($days_nb < 0) {
            $days_nb = 0;
        }
        $reportsacc = $this->model("reportsacc");
        $currentDate = new DateTime();
        $lastMonths = array();
        $lastMonths_mysql = array();
        for ($i = 1; $i <= $days_nb; $i++) {
            $lastMonths[] = $currentDate->format("Y-m");
            $lastMonths_mysql[] = $currentDate->format("Y-m-d H:i:s");
            $currentDate->modify("first day of previous month");
        }
        $data_return = array();
        $data_return["days"] = $lastMonths;
        $data_return["total_sales"] = $reportsacc->get_total_sales_of_months($lastMonths_mysql);
        $data_return["total_profits"] = $reportsacc->get_total_profits_of_months($lastMonths_mysql);
        $data_return["total_expenses"] = $reportsacc->get_total_expenses_of_months($lastMonths_mysql);
        echo json_encode($data_return);
    }
}

?>