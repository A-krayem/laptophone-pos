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
class nosession extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->settings_info = self::getSettings();
    }
    public function get_client_invoice($invoice_id)
    {
        $invoices = $this->model("invoice");
        $customers = $this->model("customers");
        $store = $this->model("store");
        $items = $this->model("items");
        $invoice_info = $invoices->getInvoiceById($invoice_id);
        $invoice_items_info = $invoices->getItemsOfInvoice($invoice_id);
        $store_info_data = $store->getStoresById($invoice_info[0]["store_id"]);
        if ($invoice_info[0]["customer_id"] != NULL) {
            $customer_info = $customers->getCustomersById($invoice_info[0]["customer_id"]);
        } else {
            $customer_info = array();
        }
        $data = array();
        $store_info = array();
        $store_info["name"] = $store_info_data[0]["name"];
        $store_info["phone"] = $this->settings_info["phone_nb"];
        $store_info["location"] = $store_info_data[0]["location"];
        $store_info["address"] = "";
        $store_info["fax"] = "";
        $store_info["employee_name"] = "";
        $client_info = array();
        if (0 < count($customer_info)) {
            $client_info["location"] = $customer_info[0]["address_city"];
            $client_info["phone"] = $customer_info[0]["phone"];
            $client_info["company"] = $customer_info[0]["company"];
            $client_info["email"] = $customer_info[0]["email"];
            $client_info["address"] = $customer_info[0]["address"];
            $client_info["client_store"] = "";
            $client_info["name"] = $customer_info[0]["name"] . " " . $customer_info[0]["middle_name"] . " " . $customer_info[0]["last_name"];
        }
        $invoice_items = array();
        for ($i = 0; $i < count($invoice_items_info); $i++) {
            $item_info = $items->get_item($invoice_items_info[$i]["item_id"]);
            $invoice_items[$i]["id"] = $invoice_items_info[$i]["item_id"];
            $invoice_items[$i]["name"] = $item_info[0]["description"];
            $invoice_items[$i]["price"] = floatval($invoice_items_info[$i]["final_price_disc_qty"] / $invoice_items_info[$i]["qty"]);
            $invoice_items[$i]["quantity"] = floatval($invoice_items_info[$i]["qty"]);
        }
        for ($i = 0; $i < count($invoice_items); $i++) {
            $invoice_items[$i]["subtotal"] = $invoice_items[$i]["price"] * $invoice_items[$i]["quantity"];
        }
        $data["store_info"] = $store_info;
        $data["client_info"] = $client_info;
        $data["invoice_items"] = $invoice_items;
        $invoice_data = array();
        $invoice_data["invoice_nb"] = $invoice_info[0]["id"];
        $invoice_data["date"] = date("d F, Y", strtotime($invoice_info[0]["creation_date"]));
        $invoice_data["freight"] = floatval($invoice_info[0]["freight"]);
        $invoice_data["discount"] = floatval($invoice_info[0]["invoice_discount"]);
        $invoice_data["tax"] = floatval($invoice_info[0]["tax"]);
        $data["vat_nb"] = $this->settings_info["vat_nb"];
        $invoice_data["subtotal"] = floatval($invoice_info[0]["total_value"]);
        $invoice_data["total"] = floatval($invoice_info[0]["total_value"] + $invoice_info[0]["invoice_discount"]);
        $invoice_data["total_without_tax"] = $invoice_data["total"];
        $invoice_data["total"] = $invoice_data["total"] * (1 + $invoice_data["tax"] / 100);
        $invoice_data["total"] += $invoice_data["freight"];
        $data["invoice_data"] = $invoice_data;
        $this->view("print_templates/client_invoice_fake", $data);
    }
}

?>