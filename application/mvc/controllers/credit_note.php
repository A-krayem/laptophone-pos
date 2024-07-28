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
class credit_note extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function credit_notes()
    {
        self::giveAccessTo();
        $this->view("credit_note");
    }
    public function generate_credit_note()
    {
        $creditnote = $this->model("creditnote");
        $id = $creditnote->generate_credit_note($_SESSION["store_id"], $_SESSION["id"]);
        echo json_encode($id);
    }
    public function print_creditnote($_id)
    {
        self::giveAccessTo();
        $creditnote = $this->model("creditnote");
        $customers = $this->model("customers");
        $settings = $this->model("settings");
        $currency = $this->model("currency");
        $items = $this->model("items");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $data["cn"] = $creditnote->get_credit_note($id);
        $data["cn_details"] = $creditnote->get_all_items_details_from_cn($id);
        $data["itemsclass"] = $items;
        $data["settings"] = $this->settings_info;
        $all_currencies = $currency->getAllEnabledCurrencies();
        $data["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            if ($all_currencies[$i]["system_default"] == 1) {
                $data["currency_system_default"] = $all_currencies[$i]["id"];
            }
            $data["currencies"][$all_currencies[$i]["id"]] = $all_currencies[$i];
        }
        $data["customer"] = NULL;
        if (!is_null($data["cn"][0]["customer_id"])) {
            $data["customer"] = $customers->getCustomersById($data["cn"][0]["customer_id"]);
        }
        $payment_types = $settings->get_payment_method();
        $data["pm"] = array();
        for ($i = 0; $i < count($payment_types); $i++) {
            $data["pm"][$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
        }
        $this->view("printing/creditnote", $data);
    }
    public function get_credit_note($_id)
    {
        self::giveAccessTo();
        $creditnote = $this->model("creditnote");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $creditnote_info = $creditnote->get_credit_note($id);
        echo json_encode($creditnote_info);
    }
    public function delete_credit_note($_id)
    {
        self::giveAccessTo();
        $creditnote = $this->model("creditnote");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        self::delete_all_item_from_cr($id);
        $creditnote->delete_credit_note($id);
        echo json_encode(array("status" => 1));
    }
    public function add_credit_note()
    {
        self::giveAccessTo();
        $creditnote = $this->model("creditnote");
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["customer_id"] = filter_input(INPUT_POST, "customer_id", FILTER_SANITIZE_NUMBER_INT);
        $info["payment_method_id"] = filter_input(INPUT_POST, "payment_method_id", FILTER_SANITIZE_NUMBER_INT);
        $info["credit_value"] = filter_input(INPUT_POST, "credit_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["note"] = filter_input(INPUT_POST, "credit_note_note", self::conversion_php_version_filter());
        $info["cr_rate_to_lbp"] = filter_input(INPUT_POST, "cr_rate_to_lbp", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["currency_rate"] = filter_input(INPUT_POST, "currency_rate", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!isset($info["currency_rate"]) || $info["currency_rate"] == 0 || strlen($info["currency_rate"]) == 0) {
            $info["currency_rate"] = 1;
        }
        $info["payment_currency"] = filter_input(INPUT_POST, "payment_currency", FILTER_SANITIZE_NUMBER_INT);
        $info["auto_sum"] = filter_input(INPUT_POST, "auto_sum", FILTER_SANITIZE_NUMBER_INT);
        $info["bank_id"] = filter_input(INPUT_POST, "bank_source", FILTER_SANITIZE_NUMBER_INT);
        if ($info["payment_method_id"] == 1) {
            $info["bank_id"] = 0;
        }
        $info["reference"] = filter_input(INPUT_POST, "reference", self::conversion_php_version_filter());
        $info["payment_owner"] = filter_input(INPUT_POST, "payment_owner", self::conversion_php_version_filter());
        if ($info["id_to_edit"] == 0) {
            $id = $creditnote->add_credit_note($info);
            echo json_encode(array($id));
        } else {
            $creditnote->update_credit_note($info);
            echo json_encode(array());
        }
    }
    public function getCreditNoteInfoNeeds()
    {
        self::giveAccessTo(array(2, 4));
        $customers = $this->model("customers");
        $settings = $this->model("settings");
        $currency = $this->model("currency");
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
        $customersInfo = $customers->getCustomers();
        $payment_types = $settings->get_payment_method();
        $banks = $settings->get_banks();
        $data = array();
        $data["customers"] = array();
        for ($i = 0; $i < count($customersInfo); $i++) {
            $data["customers"][$i]["id"] = $customersInfo[$i]["id"];
            $data["customers"][$i]["name"] = $customersInfo[$i]["name"] . " " . $customersInfo[$i]["middle_name"] . " " . $customersInfo[$i]["last_name"];
        }
        $data["pm"] = array();
        for ($i = 0; $i < count($payment_types); $i++) {
            $data["pm"][$i]["id"] = $payment_types[$i]["id"];
            $data["pm"][$i]["method_name"] = $payment_types[$i]["method_name"];
        }
        $data["banks"] = array();
        for ($i = 0; $i < count($banks); $i++) {
            $data["banks"][$i]["id"] = $banks[$i]["id"];
            $data["banks"][$i]["name"] = $banks[$i]["name"];
        }
        $all_currencies = $currency->getAllEnabledCurrencies();
        $data["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $data["currencies"][$i]["id"] = $all_currencies[$i]["id"];
            $data["currencies"][$i]["name"] = $all_currencies[$i]["name"];
            $data["currencies"][$i]["symbole"] = $all_currencies[$i]["symbole"];
            $data["currencies"][$i]["system_default"] = $all_currencies[$i]["system_default"];
            $data["currencies"][$i]["rate_to_system_default"] = $all_currencies[$i]["rate_to_system_default"];
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
    public function add_item_to_credit_note($credit_note_id, $item_id)
    {
        $creditnote = $this->model("creditnote");
        $creditnote->add_item_to_credit_note($credit_note_id, $item_id);
        echo json_encode(array());
    }
    public function get_credit_notes($date_)
    {
        self::giveAccessTo();
        $customers = $this->model("customers");
        $creditnote = $this->model("creditnote");
        $settings = $this->model("settings");
        $currency = $this->model("currency");
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
        $creditnotes = $creditnote->credit_notes($date_range);
        $customers_info = $customers->getCustomers();
        $cus = array();
        for ($i = 0; $i < count($customers_info); $i++) {
            $cus[$customers_info[$i]["id"]] = $customers_info[$i]["name"];
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
        $sum = $creditnote->sum_credit_notes($date_range);
        $data_array["total"] = number_format($sum[0]["sum"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"];
        for ($i = 0; $i < count($creditnotes); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_creditnote($creditnotes[$i]["id"]));
            array_push($tmp, $creditnotes[$i]["creation_date"]);
            array_push($tmp, $cus[$creditnotes[$i]["customer_id"]]);
            array_push($tmp, $payment_types_name[$creditnotes[$i]["credit_payment_method"]]);
            if ($creditnotes[$i]["payment_currency"] != $currency_default_id) {
                $creditnotes[$i]["credit_value"] = $creditnotes[$i]["credit_value"] * $creditnotes[$i]["currency_rate"];
            }
            $creditnotes[$i]["credit_value"] = self::global_number_formatter($creditnotes[$i]["credit_value"], $this->settings_info);
            array_push($tmp, $creditnotes[$i]["credit_value"]);
            array_push($tmp, $creditnotes[$i]["note"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_credit_note_details($credit_note_id)
    {
        $creditnote = $this->model("creditnote");
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
        $info = $creditnote->get_credit_note_details($credit_note_id);
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
            array_push($tmp, "<input onchange='cn_qty_changed(" . $info[$i]["id"] . ")' class='cn_qty clv' type='text' id='cn_qty_" . $info[$i]["id"] . "' value='" . $info[$i]["qty"] . "' />");
            array_push($tmp, "<input onchange='cn_price_changed(" . $info[$i]["id"] . ")' class='cn_price clv' type='text' id='cn_price_" . $info[$i]["id"] . "' value='" . floatval($info[$i]["price"]) . "' />");
            array_push($tmp, "<input readonly class='cn_total clv cntpi' type='text' id='cn_total_" . $info[$i]["id"] . "' value='" . floatval($info[$i]["price"] * $info[$i]["qty"]) . "' />");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function delete_all_item_from_cr($cn_id)
    {
        $creditnote = $this->model("creditnote");
        $all_items_details_from_cn = $creditnote->get_all_items_details_from_cn($cn_id);
        for ($i = 0; $i < count($all_items_details_from_cn); $i++) {
            self::delete_row_from_cr($all_items_details_from_cn[$i]["id"], 0);
        }
    }
    public function delete_row_from_cr($cn_details_id, $return)
    {
        $creditnote = $this->model("creditnote");
        $items = $this->model("items");
        $store = $this->model("store");
        $old_info = $creditnote->get_item_details_from_cn($cn_details_id);
        $old_qty = $old_info[0]["qty"];
        $new_qty = 0;
        $item_info = $items->get_item($old_info[0]["item_id"]);
        if ($item_info[0]["is_composite"] == 0) {
            $info_add_qty = array();
            $info_add_qty["qty"] = 0 - $old_qty;
            $info_add_qty["item_id"] = $old_info[0]["item_id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "CN-" . $old_info[0]["credit_note_id"];
            $store->add_qty($info_add_qty);
        } else {
            $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
            for ($kk = 0; $kk < count($composite_items); $kk++) {
                $info_add_qty = array();
                $info_add_qty["qty"] = (0 - $old_qty) * $composite_items[$kk]["qty"];
                $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "CN-" . $old_info[0]["credit_note_id"];
                $store->add_qty($info_add_qty);
            }
        }
        $creditnote->delete_row_from_cr($cn_details_id);
        if ($return == 1) {
            echo json_encode(array());
        }
    }
    public function cn_qty_changed($cn_details_id, $qty)
    {
        $creditnote = $this->model("creditnote");
        $items = $this->model("items");
        $store = $this->model("store");
        $old_info = $creditnote->get_item_details_from_cn($cn_details_id);
        $old_qty = $old_info[0]["qty"];
        $new_qty = $qty;
        $item_info = $items->get_item($old_info[0]["item_id"]);
        if ($old_qty < $new_qty) {
            if ($item_info[0]["is_composite"] == 0) {
                $info_add_qty = array();
                $info_add_qty["qty"] = $new_qty - $old_qty;
                $info_add_qty["item_id"] = $old_info[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "CN-" . $old_info[0]["credit_note_id"];
                $store->add_qty($info_add_qty);
            } else {
                $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
                for ($kk = 0; $kk < count($composite_items); $kk++) {
                    $info_add_qty = array();
                    $info_add_qty["qty"] = ($new_qty - $old_qty) * $composite_items[$kk]["qty"];
                    $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                    $info_add_qty["store_id"] = $_SESSION["store_id"];
                    $info_add_qty["source"] = "CN-" . $old_info[0]["credit_note_id"];
                    $store->add_qty($info_add_qty);
                }
            }
        }
        if ($new_qty < $old_qty) {
            if ($item_info[0]["is_composite"] == 0) {
                $info_add_qty = array();
                $info_add_qty["qty"] = 0 - ($old_qty - $new_qty);
                $info_add_qty["item_id"] = $old_info[0]["item_id"];
                $info_add_qty["store_id"] = $_SESSION["store_id"];
                $info_add_qty["source"] = "CN-" . $old_info[0]["credit_note_id"];
                $store->add_qty($info_add_qty);
            } else {
                $composite_items = $items->get_all_composite_of_item($old_info[0]["item_id"]);
                for ($kk = 0; $kk < count($composite_items); $kk++) {
                    $info_add_qty = array();
                    $info_add_qty["qty"] = (0 - ($old_qty - $new_qty)) * $composite_items[$kk]["qty"];
                    $info_add_qty["item_id"] = $composite_items[$kk]["item_id"];
                    $info_add_qty["store_id"] = $_SESSION["store_id"];
                    $info_add_qty["source"] = "CN-" . $old_info[0]["credit_note_id"];
                    $store->add_qty($info_add_qty);
                }
            }
        }
        $creditnote->cn_qty_changed($cn_details_id, $qty);
        echo json_encode(array());
    }
    public function cn_price_changed($cn_details_id, $price)
    {
        $creditnote = $this->model("creditnote");
        $creditnote->cn_price_changed($cn_details_id, $price);
        echo json_encode(array());
    }
}

?>