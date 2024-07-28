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
class debit_note extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function debit_notes()
    {
        self::giveAccessTo(array(2, 4));
        $this->view("debit_note");
    }
    public function generate_debit_note()
    {
        $debitnote = $this->model("debitnote");
        $id = $debitnote->generate_debit_note($_SESSION["store_id"], $_SESSION["id"]);
        echo json_encode($id);
    }
    public function get_debit_note_details($debit_note_id)
    {
        $debitnote = $this->model("debitnote");
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
        $info = $debitnote->get_debit_note_details($debit_note_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            $item_info = $items->get_item($info[$i]["item_id"]);
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $info[$i]["item_id"]);
            array_push($tmp, $item_info[0]["barcode"]);
            array_push($tmp, $item_info[0]["description"]);
            if (isset($colors_info_label[$item_info[0]["color_text_id"]])) {
                array_push($tmp, $colors_info_label[$item_info[0]["color_text_id"]]);
            } else {
                array_push($tmp, "");
            }
            if (isset($sizes_info_label[$item_info[0]["size_id"]])) {
                array_push($tmp, $sizes_info_label[$item_info[0]["size_id"]]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "<input onchange='dn_qty_changed(" . $info[$i]["id"] . ")' class='cn_qty clv' type='text' id='dn_qty_" . $info[$i]["id"] . "' value='" . $info[$i]["qty"] . "' />");
            array_push($tmp, "<input onchange='dn_price_changed(" . $info[$i]["id"] . ")' class='cn_price clv' type='text' id='dn_price_" . $info[$i]["id"] . "' value='" . floatval($info[$i]["price"]) . "' />");
            array_push($tmp, "<input readonly class='cn_total clv cntpi' type='text' id='dn_total_" . $info[$i]["id"] . "' value='" . floatval($info[$i]["price"] * $info[$i]["qty"]) . "' />");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function print_debitnote($_id)
    {
        self::giveAccessTo(array(2, 4));
        $debitnote = $this->model("debitnote");
        $suppliers = $this->model("suppliers");
        $settings = $this->model("settings");
        $phones = $this->model("phones");
        $stock = $this->model("stock");
        $items = $this->model("items");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $data["cn"] = $debitnote->get_debit_note($id);
        $data["supplier"] = NULL;
        if (!is_null($data["cn"][0]["supplier_id"])) {
            $data["supplier"] = $suppliers->getSupplier($data["cn"][0]["supplier_id"]);
            $contacts = $phones->getSupplierContacts($data["cn"][0]["supplier_id"]);
            $data["supplier"][0]["phone"] = $contacts[0]["phone_number"];
        }
        $payment_types = $settings->get_payment_method();
        $data["pm"] = array();
        for ($i = 0; $i < count($payment_types); $i++) {
            $data["pm"][$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
        }
        $data["returned"] = array();
        if (0 < $data["cn"][0]["p_invoice"]) {
            $data["returned"] = $stock->get_returned_items_from_pi($data["cn"][0]["p_invoice"]);
            for ($i = 0; $i < count($data["returned"]); $i++) {
                $data["returned"][$i]["price"] = $data["returned"][$i]["cost"];
            }
        } else {
            $data["returned"] = $stock->get_returned_items_fly($data["cn"][0]["id"]);
            for ($i = 0; $i < count($data["returned"]); $i++) {
                $data["returned"][$i]["returned_debit"] = $data["returned"][$i]["qty"];
            }
        }
        if (0 < $data["cn"][0]["p_invoice"]) {
            $items_info = $items->get_items_in_pi($data["cn"][0]["p_invoice"]);
        } else {
            $items_info = $items->get_items_in_dnote($data["cn"][0]["id"]);
        }
        $data["items"] = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $data["items"][$items_info[$i]["id"]] = $items_info[$i];
        }
        $this->view("printing/debitnote", $data);
    }
    public function get_debit_note($_id)
    {
        self::giveAccessTo(array(2, 4));
        $debitnote = $this->model("debitnote");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $debitnote_info = $debitnote->get_debit_note($id);
        $debitnote_info[0]["debit_value"] = $debitnote_info[0]["debit_value"];
        echo json_encode($debitnote_info);
    }
    public function delete_debit_note($_id)
    {
        self::giveAccessTo(array(2, 4));
        $debitnote = $this->model("debitnote");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $debit_info = $debitnote->get_debit_note($id);
        $debitnote->delete_debit_note($id);
        $debitnote->reset_returns($debit_info[0]["p_invoice"]);
        echo json_encode(array("status" => 1));
    }
    public function add_debit_note()
    {
        self::giveAccessTo(array(2, 4));
        $debitnote = $this->model("debitnote");
        $currency = $this->model("currency");
        $currencies = $currency->getAllCurrencies();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["supplier_id"] = filter_input(INPUT_POST, "supplier_id", FILTER_SANITIZE_NUMBER_INT);
        $info["payment_method"] = filter_input(INPUT_POST, "payment_method", FILTER_SANITIZE_NUMBER_INT);
        $info["debit_value"] = filter_input(INPUT_POST, "debit_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["note"] = filter_input(INPUT_POST, "debit_note_note", self::conversion_php_version_filter());
        $info["currency_rate"] = filter_input(INPUT_POST, "currency_rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["payment_currency"] = filter_input(INPUT_POST, "payment_currency", FILTER_SANITIZE_NUMBER_INT);
        if ($info["payment_currency"] == $currency_default_id) {
            $info["currency_rate"] = 1;
        } else {
            if ($currency_default_id == 1 && $info["payment_currency"] == 2) {
                $info["currency_rate"] = 1 / $info["currency_rate"];
            } else {
                if ($currency_default_id == 2 && $info["payment_currency"] == 1) {
                    $info["currency_rate"] = $info["currency_rate"];
                }
            }
        }
        $info["reference"] = filter_input(INPUT_POST, "reference", self::conversion_php_version_filter());
        $info["payment_owner"] = filter_input(INPUT_POST, "payment_owner", self::conversion_php_version_filter());
        if (!isset($info["currency_rate"]) || $info["currency_rate"] == 0 || strlen($info["currency_rate"]) == 0) {
            $info["currency_rate"] = 1;
        }
        $info["payment_value"] = filter_input(INPUT_POST, "payment_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["bank_id"] = filter_input(INPUT_POST, "bank_source", FILTER_SANITIZE_NUMBER_INT);
        if ($info["payment_method"] == 1) {
            $info["bank_id"] = 0;
        }
        $info["pi_id"] = filter_input(INPUT_POST, "pi_id", FILTER_SANITIZE_NUMBER_INT);
        if (!isset($info["pi_id"])) {
            $info["pi_id"] = 0;
        }
        if ($info["id_to_edit"] == 0) {
            $id = $debitnote->add_debit_note($info);
            echo json_encode(array($id));
        } else {
            $debitnote->update_debit_note($info);
            echo json_encode(array());
        }
    }
    public function add_item_to_debit_note($debit_note_id, $item_id)
    {
        $debitnote = $this->model("debitnote");
        $debitnote->add_item_to_debit_note($debit_note_id, $item_id);
        echo json_encode(array());
    }
    public function getDebitNoteInfoNeeds($_supplier_id)
    {
        self::giveAccessTo(array(2, 4));
        $suppliers = $this->model("suppliers");
        $settings = $this->model("settings");
        $stock = $this->model("stock");
        $currency = $this->model("currency");
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $suppliersInfo = $suppliers->getSuppliers();
        $payment_types = $settings->get_payment_method();
        $banks = $settings->get_banks();
        if ($supplier_id == 0) {
            $stock_info = $stock->get_pi_closed();
        } else {
            $stock_info = $stock->get_pi_closed_by_supplier_id($supplier_id);
        }
        $data = array();
        $data["suppliers"] = array();
        for ($i = 0; $i < count($suppliersInfo); $i++) {
            $data["suppliers"][$i]["id"] = $suppliersInfo[$i]["id"];
            $data["suppliers"][$i]["name"] = $suppliersInfo[$i]["name"];
        }
        $data["pm"] = array();
        for ($i = 0; $i < count($payment_types); $i++) {
            $data["pm"][$i]["id"] = $payment_types[$i]["id"];
            $data["pm"][$i]["method_name"] = $payment_types[$i]["method_name"];
        }
        $data["pi"] = array();
        for ($i = 0; $i < count($stock_info); $i++) {
            $data["pi"][$i]["id"] = $stock_info[$i]["id"];
            $data["pi"][$i]["pi_name"] = self::idFormat_stockInv($stock_info[$i]["id"]) . " - " . $stock_info[$i]["invoice_reference"];
        }
        $data["banks"] = array();
        for ($i = 0; $i < count($banks); $i++) {
            $data["banks"][$i]["id"] = $banks[$i]["id"];
            $data["banks"][$i]["name"] = $banks[$i]["name"];
        }
        $data["currencies_count"] = $_SESSION["currency_counnt"];
        $all_currencies = $currency->getAllEnabledCurrencies();
        $data["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $data["currencies"][$i]["id"] = $all_currencies[$i]["id"];
            $data["currencies"][$i]["name"] = $all_currencies[$i]["name"];
            $data["currencies"][$i]["symbole"] = $all_currencies[$i]["symbole"];
            $data["currencies"][$i]["system_default"] = $all_currencies[$i]["system_default"];
            $data["currencies"][$i]["rate_to_system_default"] = $all_currencies[$i]["rate_to_system_default"];
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
        $result = $items->get_items_names_with_boxes();
        $data["items"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $data["items"][$i]["id"] = $result[$i]["id"];
            if ($result[$i]["barcode"] == NULL) {
                $result[$i]["barcode"] = "";
            }
            if ($result[$i]["second_barcode"] == NULL) {
                $result[$i]["second_barcode"] = "";
            }
            $color_size = "";
            if (isset($colors_info_label[$result[$i]["color_text_id"]]) && $result[$i]["color_text_id"] != 1) {
                $color_size .= $colors_info_label[$result[$i]["color_text_id"]];
            }
            if (isset($sizes_info_label[$result[$i]["size_id"]]) && $result[$i]["size_id"] != 1) {
                $color_size .= "-" . $sizes_info_label[$result[$i]["size_id"]];
            }
            if (strlen($result[$i]["barcode"]) < 5) {
                $result[$i]["barcode"] = sprintf("%05s", $result[$i]["barcode"]);
            }
            $data["items"][$i]["name"] = $result[$i]["description"] . "-" . $result[$i]["barcode"] . "-" . $color_size;
        }
        echo json_encode($data);
    }
    public function pi_return($_id, $_qty)
    {
        self::giveAccessTo(array(2, 4));
        $debitnote = $this->model("debitnote");
        $stock = $this->model("stock");
        $store = $this->model("store");
        $items = $this->model("items");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info_receive_stock = $stock->get_item_from_invoice_order($id);
        if ($info_receive_stock[0]["returned_debit"] == 0) {
            $info_add_qty["qty"] = 0 - $_qty;
            $info_add_qty["item_id"] = $info_receive_stock[0]["item_id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "DN-" . $info_receive_stock[0]["id"];
            $store->add_qty($info_add_qty);
            $info["new_qty"] = $info_add_qty["qty"];
            $info["item_id"] = $info_receive_stock[0]["item_id"];
            $info["po_id"] = $info_receive_stock[0]["receive_stock_invoice_id"];
            $info["receive_stock_id"] = $info_receive_stock[0]["id"];
            $items->update_history_prices_per_it($info);
            $items->set_global_average_cost($info_receive_stock[0]["item_id"]);
        } else {
            if ($qty < $info_receive_stock[0]["returned_debit"]) {
                $info_add_qty["qty"] = $info_receive_stock[0]["returned_debit"] - $qty;
                $info_add_qty["item_id"] = $info_receive_stock[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "DN-" . $info_receive_stock[0]["id"];
                $store->add_qty($info_add_qty);
                $info["new_qty"] = $info_add_qty["qty"];
                $info["item_id"] = $info_receive_stock[0]["item_id"];
                $info["po_id"] = $info_receive_stock[0]["receive_stock_invoice_id"];
                $info["receive_stock_id"] = $info_receive_stock[0]["id"];
                $items->update_history_prices_per_it($info);
                $items->set_global_average_cost($info_receive_stock[0]["item_id"]);
            }
            if ($info_receive_stock[0]["returned_debit"] < $qty) {
                $info_add_qty["qty"] = 0 - abs($info_receive_stock[0]["returned_debit"] - $qty);
                $info_add_qty["item_id"] = $info_receive_stock[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "DN-" . $info_receive_stock[0]["id"];
                $store->add_qty($info_add_qty);
                $info["new_qty"] = $info_add_qty["qty"];
                $info["item_id"] = $info_receive_stock[0]["item_id"];
                $info["po_id"] = $info_receive_stock[0]["receive_stock_invoice_id"];
                $info["receive_stock_id"] = $info_receive_stock[0]["id"];
                $items->update_history_prices_per_it($info);
                $items->set_global_average_cost($info_receive_stock[0]["item_id"]);
            }
        }
        $debitnote->pi_return($id, $qty);
        echo json_encode(array());
    }
    public function get_debit_notes($date_)
    {
        self::giveAccessTo(array(2, 4));
        $suppliers = $this->model("suppliers");
        $debitnote = $this->model("debitnote");
        $settings = $this->model("settings");
        $currency = $this->model("currency");
        $date = filter_var($date_, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date == "today") {
            $date_range[0] = date("Y-m-01");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $debitnotes = $debitnote->debit_notes($date_range);
        $suppliers_info = $suppliers->getSuppliers();
        $cus = array();
        for ($i = 0; $i < count($suppliers_info); $i++) {
            $cus[$suppliers_info[$i]["id"]] = $suppliers_info[$i]["name"];
        }
        $payment_types = $settings->get_payment_method();
        $payment_types_name = array();
        for ($i = 0; $i < count($payment_types); $i++) {
            $payment_types_name[$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
        }
        $currencies = $currency->getAllCurrencies();
        $currencies_info = array();
        $currency_default_id = 0;
        for ($i = 0; $i < count($currencies); $i++) {
            $currencies_info[$currencies[$i]["id"]] = $currencies[$i];
            if ($currencies[$i]["system_default"] == 1) {
                $currency_default_id = $currencies[$i]["id"];
            }
        }
        $data_array["data"] = array();
        $sum = $debitnote->sum_debit_notes($date_range);
        $data_array["total"] = number_format($sum[0]["sum"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        for ($i = 0; $i < count($debitnotes); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_debitnote($debitnotes[$i]["id"]));
            array_push($tmp, $debitnotes[$i]["creation_date"]);
            array_push($tmp, $cus[$debitnotes[$i]["supplier_id"]]);
            array_push($tmp, self::idFormat_stockInv($debitnotes[$i]["p_invoice"]));
            array_push($tmp, $payment_types_name[$debitnotes[$i]["debit_payment_method"]]);
            if ($debitnotes[$i]["payment_currency"] != $currency_default_id) {
                $debitnotes[$i]["debit_value"] = $debitnotes[$i]["debit_value"] * $debitnotes[$i]["currency_rate"];
            }
            array_push($tmp, self::global_number_formatter($debitnotes[$i]["debit_value"], $this->settings_info));
            array_push($tmp, $currencies_info[$debitnotes[$i]["payment_currency"]]["symbole"]);
            array_push($tmp, self::global_number_formatter((double) $debitnotes[$i]["currency_rate"], $this->settings_info));
            array_push($tmp, $debitnotes[$i]["note"]);
            array_push($tmp, "");
            array_push($tmp, $debitnotes[$i]["on_the_fly"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function dn_qty_changed($dn_details_id, $qty)
    {
        $debitnote = $this->model("debitnote");
        $items = $this->model("items");
        $store = $this->model("store");
        $old_info = $debitnote->get_item_details_from_dn($dn_details_id);
        $old_qty = $old_info[0]["qty"];
        $new_qty = $qty;
        $item_info = $items->get_item($old_info[0]["item_id"]);
        if ($old_qty < $new_qty) {
            if ($item_info[0]["is_composite"] == 0) {
                $info_add_qty = array();
                $info_add_qty["qty"] = 0 - ($new_qty - $old_qty);
                $info_add_qty["item_id"] = $old_info[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "DN-" . $old_info[0]["debit_note_id"];
                $store->add_qty($info_add_qty);
            } else {
                $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
                for ($kk = 0; $kk < count($composite_items); $kk++) {
                    $info_add_qty = array();
                    $info_add_qty["qty"] = 0 - ($new_qty - $old_qty) * $composite_items[$kk]["qty"];
                    $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                    $info_add_qty["store_id"] = $_SESSION["store_id"];
                    $info_add_qty["source"] = "DN-" . $old_info[0]["debit_note_id"];
                    $store->add_qty($info_add_qty);
                }
            }
        }
        if ($new_qty < $old_qty) {
            if ($item_info[0]["is_composite"] == 0) {
                $info_add_qty = array();
                $info_add_qty["qty"] = $old_qty - $new_qty;
                $info_add_qty["item_id"] = $old_info[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "DN-" . $old_info[0]["debit_note_id"];
                $store->add_qty($info_add_qty);
            } else {
                $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
                for ($kk = 0; $kk < count($composite_items); $kk++) {
                    $info_add_qty = array();
                    $info_add_qty["qty"] = ($old_qty - $new_qty) * $composite_items[$kk]["qty"];
                    $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                    $info_add_qty["store_id"] = $_SESSION["store_id"];
                    $info_add_qty["source"] = "DN-" . $old_info[0]["debit_note_id"];
                    $store->add_qty($info_add_qty);
                }
            }
        }
        $debitnote->dn_qty_changed($dn_details_id, $qty);
        echo json_encode(array());
    }
    public function dn_price_changed($dn_details_id, $price)
    {
        $debitnote = $this->model("debitnote");
        $debitnote->dn_price_changed($dn_details_id, $price);
        echo json_encode(array());
    }
}

?>