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
class garage extends Controller
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
    public function cards()
    {
        self::giveAccessTo(array(2));
        $this->view("garage/cards");
    }
    public function oil_report()
    {
        self::giveAccessTo(array(2));
        $this->view("garage/oil_report");
    }
    public function get_unassigned_card($_customer_id)
    {
        self::giveAccessTo(array(2));
        $garage = $this->model("garage");
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $card_info = $garage->getAllClientsPendingsCards($customer_id);
        echo json_encode($card_info);
    }
    public function getInvoicesOfCustomers($_customer_id)
    {
        self::giveAccessTo(array(2));
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $invoices = $invoice->getInvoicesOfCustomers($customer_id);
        $info = array();
        for ($i = 0; $i < count($invoices); $i++) {
            $info[$i]["id"] = $invoices[$i]["id"];
            $info[$i]["id_label"] = self::idFormat_invoice($invoices[$i]["id"]);
            $info[$i]["total_value"] = number_format($invoices[$i]["total_value"] + $invoices[$i]["invoice_discount"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        }
        echo json_encode($info);
    }
    public function get_garage_card_id($_id)
    {
        self::giveAccessTo(array(2));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $garage = $this->model("garage");
        $car_info = $garage->get_garage_card_id($id);
        echo json_encode($car_info);
    }
    public function delete_card($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $garage = $this->model("garage");
        $return = array();
        $return["status"] = $garage->delete_card($id);
        echo json_encode($return);
    }
    public function add_new_garage_card()
    {
        self::giveAccessTo(array(2));
        $garage = $this->model("garage");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["customers_list"] = filter_input(INPUT_POST, "customers_list", FILTER_SANITIZE_NUMBER_INT);
        $info["card_invoice"] = filter_input(INPUT_POST, "card_invoice", FILTER_SANITIZE_NUMBER_INT);
        $info["company"] = filter_input(INPUT_POST, "company", self::conversion_php_version_filter());
        $info["date_in"] = filter_input(INPUT_POST, "date_in", self::conversion_php_version_filter());
        $info["date_out"] = filter_input(INPUT_POST, "date_out", self::conversion_php_version_filter());
        $info["code"] = filter_input(INPUT_POST, "code", self::conversion_php_version_filter());
        $info["car_type"] = filter_input(INPUT_POST, "car_type", self::conversion_php_version_filter());
        $info["car_model"] = filter_input(INPUT_POST, "car_model", self::conversion_php_version_filter());
        $info["car_color"] = filter_input(INPUT_POST, "item_text_color", FILTER_SANITIZE_NUMBER_INT);
        $info["car_odometer"] = filter_input(INPUT_POST, "car_odometer", self::conversion_php_version_filter());
        $info["car_c"] = filter_input(INPUT_POST, "car_c", self::conversion_php_version_filter());
        $info["problem_description"] = filter_input(INPUT_POST, "problem_description", self::conversion_php_version_filter());
        $info["oil_changed_date"] = filter_input(INPUT_POST, "oil_changed_date", self::conversion_php_version_filter());
        $info["oil_next_change_date"] = filter_input(INPUT_POST, "oil_next_change_date", self::conversion_php_version_filter());
        $info["oil_note"] = filter_input(INPUT_POST, "oil_note", self::conversion_php_version_filter());
        if ($info["card_invoice"] == NULL || $info["card_invoice"] == "") {
            $info["card_invoice"] = 0;
        }
        if ($info["oil_changed_date"] == "" || $info["oil_changed_date"] == NULL) {
            $info["oil_changed_date"] = "NULL";
        } else {
            $info["oil_changed_date"] = "'" . $info["oil_changed_date"] . "'";
        }
        if ($info["oil_next_change_date"] == "" || $info["oil_next_change_date"] == NULL) {
            $info["oil_next_change_date"] = "NULL";
        } else {
            $info["oil_next_change_date"] = "'" . $info["oil_next_change_date"] . "'";
        }
        if ($info["id_to_edit"] == 0) {
            $last_insert_item_id = $garage->add_new_card($info);
        } else {
            $garage->update_card($info);
        }
        $data["id"] = $info["id_to_edit"];
        if ($data["id"] == 0) {
            $data["id"] = $last_insert_item_id;
        }
        echo json_encode($data);
    }
    public function get_garage_needed_info()
    {
        self::giveAccessTo(array(2));
        $info = array();
        $info["colors"] = array();
        $info["customers"] = array();
        $customers = $this->model("customers");
        $colors = $this->model("colors");
        $customers_info = $customers->getCustomersEvenDeleted();
        $colors_info = $colors->getColorsText();
        for ($i = 0; $i < count($colors_info); $i++) {
            $info["colors"][$i]["name"] = $colors_info[$i]["name"];
            $info["colors"][$i]["id"] = $colors_info[$i]["id"];
        }
        for ($i = 0; $i < count($customers_info); $i++) {
            $info["customers"][$i]["id"] = $customers_info[$i]["id"];
            $info["customers"][$i]["name"] = $customers_info[$i]["name"] . " " . $customers_info[$i]["middle_name"] . " " . $customers_info[$i]["last_name"];
            $info["customers"][$i]["phone"] = $customers_info[$i]["phone"];
        }
        $info["oil_change_date_interval_by_day"] = $this->settings_info["oil_change_date_interval_by_day"];
        echo json_encode($info);
    }
    public function getAllClientsCards($oil_filter)
    {
        self::giveAccessTo(array(2));
        $garage = $this->model("garage");
        $customers = $this->model("customers");
        $colors = $this->model("colors");
        $employees = $this->model("employees");
        $employees_info = $employees->getAllEmployeesEvenDeleted();
        $employees_info_array = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i];
        }
        $sales_person = $garage->getAllClientsCards_sales_person();
        $sales_person_garage_array = array();
        for ($i = 0; $i < count($sales_person); $i++) {
            $sales_person_garage_array[$sales_person[$i]["id"]] = $sales_person[$i];
        }
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
        if ($oil_filter == 0) {
            $info = $garage->getAllClientsCards();
        } else {
            $info = $garage->getAllClientsCards_DUEOIL();
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
            array_push($tmp, $info[$i]["date_time_in"]);
            array_push($tmp, $info[$i]["date_time_out"]);
            array_push($tmp, $info[$i]["oil_changed_date"]);
            array_push($tmp, $info[$i]["oil_next_change_date"]);
            array_push($tmp, $info[$i]["oil_note"]);
            if ($info[$i]["invoice_id"] != NULL && 0 < $info[$i]["invoice_id"]) {
                array_push($tmp, $employees_info_array[$sales_person_garage_array[$info[$i]["id"]]["sales_person"]]["first_name"]);
            } else {
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>