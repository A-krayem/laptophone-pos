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
class reports_generator extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function testpng()
    {
        self::checkAuth();
        $main_root = self::get_main_root();
        $actual_link = (isset($_SERVER["HTTPS"]) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $actual_link_array = explode("?", $actual_link);
        $main_url = $actual_link_array[0];
        exec($main_root . "/tools/wkhtml/wkhtmltoimage.exe \"" . $main_url . "?r=reports_generator&f=barex\" " . $main_root . "/generated_reports/document.jpg");
        exit;
    }
    public function barex()
    {
        self::checkAuth();
        $this->view("reports_templates/barcode");
    }
    public function generate()
    {
        self::checkAuth();
        $main_root = self::get_main_root();
        $actual_link = (isset($_SERVER["HTTPS"]) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $actual_link_array = explode("?", $actual_link);
        $main_url = $actual_link_array[0];
        exec($main_root . "/tools/wkhtml/wkhtmltopdf.exe --margin-bottom 20 --margin-top 20 --header-line --header-spacing 5 --header-center \"Premium Outlet\" --footer-font-size 8 --footer-center \"Powered by Upsilon\" --page-size A4 \"" . $main_url . "?r=reports_generator&f=example_report&p0=2017-11-20%20-%202017-11-29\" " . $main_root . "/generated_reports/document.pdf");
    }
    public function generate_creditnote($creditnote_id)
    {
        self::checkAuth();
        $main_root = self::get_main_root();
        $actual_link = (isset($_SERVER["HTTPS"]) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $actual_link_array = explode("?", $actual_link);
        $main_url = $actual_link_array[0];
        exec($main_root . "/tools/wkhtml/wkhtmltopdf.exe --page-size A4 \"" . $main_url . "?r=reports_generator&f=creditnote_template&p0=" . $creditnote_id . "\" \"" . $main_root . "/UPSILON Docs/credit notes/Credit Note_" . $creditnote_id . ".pdf\"");
        echo json_encode(array());
    }
    public function generate_invoice($invoice_id)
    {
        self::checkAuth();
        $main_root = self::get_main_root();
        $actual_link = (isset($_SERVER["HTTPS"]) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $actual_link_array = explode("?", $actual_link);
        $main_url = $actual_link_array[0];
        exec($main_root . "/tools/wkhtml/wkhtmltopdf.exe --page-size A4 \"" . $main_url . "?r=reports_generator&f=invoice_template&p0=" . $invoice_id . "\" \"" . $main_root . "/UPSILON Docs/invoices/Invoice_" . $invoice_id . ".pdf\"");
        echo json_encode(array());
    }
    public function creditnote_template($creditnote_id)
    {
        $data = array();
        $creditnote = $this->model("creditnote");
        $settings = $this->model("settings");
        $customers = $this->model("customers");
        $data["cn"] = $creditnote->get_credit_note($creditnote_id);
        $payment_types = $settings->get_payment_method();
        $data["pm"] = array();
        for ($i = 0; $i < count($payment_types); $i++) {
            $data["pm"][$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
        }
        $data["customer"] = NULL;
        if (!is_null($data["cn"][0]["customer_id"])) {
            $data["customer"] = $customers->getCustomersById($data["cn"][0]["customer_id"]);
        }
        $this->view("reports_templates/creditnote", $data);
    }
    public function invoice_template($invoice_id)
    {
        $data = array();
        $invoices = $this->model("invoice");
        $items = $this->model("items");
        $customers = $this->model("customers");
        $data["invoice"] = $invoices->getInvoiceById($invoice_id);
        $data["customer"] = NULL;
        if (!is_null($data["invoice"][0]["customer_id"])) {
            $data["customer"] = $customers->getCustomersById($data["invoice"][0]["customer_id"]);
        }
        $data["invoice_items"] = $invoices->getItemsOfInvoice($invoice_id);
        $data["items_instance"] = $items;
        $this->view("reports_templates/invoice", $data);
    }
    public function example_report($_date)
    {
        self::checkAuth();
        $reports = $this->model("reports");
        $items = $this->model("items");
        $date = filter_var($_date, self::conversion_php_version_filter());
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
        $info = $reports->getReportByDay(1, $date_range, 0);
        $all_items = $items->getAllItemsEvenDeleted();
        $all_items_info = array();
        for ($i = 0; $i < count($all_items); $i++) {
            $all_items_info[$all_items[$i]["id"]] = $all_items[$i];
        }
        $data["sales_items"] = $info;
        $data["all_items"] = $all_items_info;
        $data["settings_info"] = $this->settings_info;
        $this->view("reports_templates/index", $data);
    }
}

?>