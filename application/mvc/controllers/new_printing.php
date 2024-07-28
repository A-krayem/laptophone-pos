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
class new_printing extends Controller
{
    public $licenseExpired = false;
    public function print_quotation($_quotation_id)
    {
        $items = $this->model("items");
        $customers = $this->model("customers");
        $quotations = $this->model("quotations");
        $quotation_id = filter_var($_quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $data["items"] = $quotations->getItemsOfQuotationDetailed($quotation_id);
        $quotation = $quotations->getQuotationById($quotation_id);
        $data["quotation"] = $quotation[0];
        $data["customer"] = $data["quotation"]["customer_id"] ? $customers->getCustomersById($data["quotation"]["customer_id"]) : array();
        $data["settings"] = self::getSettings();
        for ($i = 0; $i < count($data["items"]); $i++) {
            if ($data["items"][$i]["is_composite"] == 0) {
                $data["items"][$i]["description"] = $data["items"][$i]["description"] . "";
                $data["items"][$i]["box_qty"] = 0;
            } else {
                $composite_info = $items->get_composite_item_id($data["items"][$i]["item_id"]);
                $data["items"][$i]["description"] = $data["items"][$i]["description"] . " <b>(" . floatval($composite_info[0]["qty"]) . "U/Box)</b>";
                $data["items"][$i]["box_qty"] = $composite_info[0]["qty"];
            }
        }
        $this->view("print_templates/a4/quotation_items", $data);
    }
    public function print_receipt($_payment_id)
    {
        $customers = $this->model("customers");
        $quotations = $this->model("quotations");
        $customers_class = $this->model("customers");
        $payment_id = filter_var($_payment_id, FILTER_SANITIZE_NUMBER_INT);
        $data = array();
        $payment = $customers_class->get_customer_payment($payment_id);
        $data["payment"] = $payment[0];
        $data["customer"] = $data["payment"]["customer_id"] ? $customers->getCustomersById($data["payment"]["customer_id"]) : array();
        $data["settings"] = self::getSettings();
        $this->view("print_templates/pos58/print_last_payment_for_quotation", $data);
    }
    public function print_last_payment_for_quotation($_quotation_id)
    {
        $customers = $this->model("customers");
        $quotations = $this->model("quotations");
        $quotation_id = filter_var($_quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $data = array();
        $quotation_info = $quotations->getQuotationById($quotation_id);
        $payment = $quotations->get_payment_by_quotation_id($quotation_id);
        $data["quotation"] = $quotation_info[0];
        $data["payment"] = $payment[0];
        $data["customer"] = $data["payment"]["customer_id"] ? $customers->getCustomersById($data["payment"]["customer_id"]) : array();
        $data["settings"] = self::getSettings();
        $this->view("print_templates/pos58/print_last_payment_for_quotation", $data);
    }
}

?>