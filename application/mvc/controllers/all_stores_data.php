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
class all_stores_data extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function prepare_stock_transfer($_item_id)
    {
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $store = $this->model("store");
        $item_info = $items->get_item($item_id);
        $return_info = array();
        $return_info["item_id"] = $item_info[0]["id"];
        $return_info["stores"] = $store->get_other_stores($_SESSION["store_id"]);
        echo json_encode($return_info);
    }
    public function collect_customers()
    {
        $sms = $this->model("sms");
        if ($_SESSION["global_admin_exist"] == 1) {
            $store = $this->model("store");
            $stores_data = array();
            $stores = $store->getAllStores();
            for ($i = 0; $i < count($stores); $i++) {
                $stores_data[$i] = $stores[$i];
            }
            for ($i = 0; $i < count($stores_data); $i++) {
                $custom_connection = my_sql::custom_connection($stores_data[$i]["ip_address"], $stores_data[$i]["username"], $stores_data[$i]["password"], $stores_data[$i]["db"]);
                if ($custom_connection) {
                    $sms->collect_customers($custom_connection);
                }
            }
        } else {
            $sms->collect_customers(0);
        }
        echo json_encode(array());
    }
    public function get_all_quantities($_law_qty, $_availibility, $p2, $p3, $p4, $p5)
    {
        $law_qty = filter_var($_law_qty, FILTER_SANITIZE_NUMBER_INT);
        $availibility = filter_var($_availibility, FILTER_SANITIZE_NUMBER_INT);
        $in_stores = filter_var($p2, self::conversion_php_version_filter());
        $in_stores_array_tmp = explode(",", $in_stores);
        $in_stores_array = array();
        for ($i = 0; $i < count($in_stores_array_tmp); $i++) {
            $in_stores_array[$i] = $in_stores_array_tmp[$i];
        }
        $outside_connection_ = $this->model("outside_connection_");
        $items = $this->model("items");
        $store = $this->model("store");
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
        $stores_data = array();
        $items_details_per_store = array();
        $warehouse_id = 0;
        $stores = $store->getAllStores();
        for ($i = 0; $i < count($stores); $i++) {
            if ($stores[$i]["warehouse"] == 1) {
                $warehouse_id = $stores[$i]["id"];
            }
            $stores_data[$i] = $stores[$i];
            $custom_connection = my_sql::custom_connection($stores_data[$i]["ip_address"], $stores_data[$i]["username"], $stores_data[$i]["password"], $stores_data[$i]["db"]);
            if ($custom_connection) {
                if (in_array($stores[$i]["id"], $in_stores_array)) {
                    $items_details_per_store[$stores_data[$i]["id"]] = $items->getLowItemsInAllStores($custom_connection);
                } else {
                    $items_details_per_store[$stores_data[$i]["id"]] = array();
                }
            } else {
                $items_details_per_store[$stores_data[$i]["id"]] = array();
            }
        }
        $items_details = $items_details_per_store[$warehouse_id];
        $qty_stores = array();
        for ($i = 0; $i < count($stores); $i++) {
            if (in_array($stores[$i]["id"], $in_stores_array)) {
                for ($j = 0; $j < count($items_details_per_store[$stores[$i]["id"]]); $j++) {
                    $qty_stores[$stores[$i]["id"]][$items_details_per_store[$stores[$i]["id"]][$j]["id"]] = $items_details_per_store[$stores[$i]["id"]][$j]["quantity"];
                }
            }
        }
        $tmp_items_details = array();
        $idx = 0;
        for ($i = 0; $i < count($items_details); $i++) {
            for ($s = 0; $s < count($stores); $s++) {
                if (in_array($stores[$s]["id"], $in_stores_array) && floor($qty_stores[$stores[$s]["id"]][$items_details[$i]["id"]]) <= $law_qty) {
                    $tmp_items_details[$idx] = $items_details[$i];
                    $idx++;
                    break;
                }
            }
        }
        $items_details = $tmp_items_details;
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_details); $i++) {
            if ($availibility == 1 && 0 < floor($qty_stores[$warehouse_id][$items_details[$i]["id"]]) || $availibility == 0) {
                $tmp = array();
                array_push($tmp, self::idFormat_item($items_details[$i]["id"]));
                array_push($tmp, $items_details[$i]["barcode"]);
                array_push($tmp, $items_details[$i]["description"]);
                if (isset($colors_info_label[$items_details[$i]["color_text_id"]])) {
                    array_push($tmp, $colors_info_label[$items_details[$i]["color_text_id"]]);
                } else {
                    array_push($tmp, "");
                }
                $final_cost = 0;
                if ($items_details[$i]["vat"]) {
                    $final_cost = floatval($items_details[$i]["buying_cost"] * $this->settings_info["vat"]);
                } else {
                    $final_cost = floatval($items_details[$i]["buying_cost"]);
                }
                if ($_SESSION["hide_critical_data"] == 1) {
                    array_push($tmp, "***");
                } else {
                    array_push($tmp, self::value_format_custom($final_cost, $this->settings_info));
                }
                if (in_array($items_details[$i]["id"], $discounts_items_ids)) {
                    $items_details[$i]["discount"] = $discounts_items_discount[$items_details[$i]["id"]];
                }
                $price_after_discount = $items_details[$i]["selling_price"] - $items_details[$i]["selling_price"] * $items_details[$i]["discount"] / 100;
                if ($items_details[$i]["vat"] == 1) {
                    $price_after_discount = $price_after_discount * $this->settings_info["vat"];
                }
                if ($_SESSION["hide_critical_data"] == 1) {
                    array_push($tmp, "***");
                } else {
                    if ($this->settings_info["enable_wholasale"] == 0) {
                        array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
                    } else {
                        array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
                    }
                }
                for ($s = 0; $s < count($stores); $s++) {
                    if (in_array($stores[$s]["id"], $in_stores_array)) {
                        array_push($tmp, floor($qty_stores[$stores[$s]["id"]][$items_details[$i]["id"]]));
                    } else {
                        array_push($tmp, "");
                    }
                }
                array_push($data_array["data"], $tmp);
            }
        }
        echo json_encode($data_array);
    }
    public function get_all_items_qty_in_all_stores($item_id)
    {
        $outside_connection_ = $this->model("outside_connection_");
        $store = $this->model("store");
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
        $item_details = $items->get_item($item_id);
        $data_array["data"] = array();
        if (0 < count($item_details)) {
            $stores_data = array();
            $stores = $store->getAllStores();
            for ($i = 0; $i < count($stores); $i++) {
                $stores_data[$i] = $stores[$i];
            }
            $items_stores_data = array();
            for ($i = 0; $i < count($stores_data); $i++) {
                $custom_connection = my_sql::custom_connection($stores_data[$i]["ip_address"], $stores_data[$i]["username"], $stores_data[$i]["password"], $stores_data[$i]["db"]);
                $items_stores_data[$i]["id"] = $stores_data[$i]["id"];
                $items_stores_data[$i]["store_name"] = $stores_data[$i]["name"];
                if ($custom_connection) {
                    $item_info = $outside_connection_->getQtyOfItem($item_details[0]["id"], $custom_connection);
                    $items_stores_data[$i]["quantity"] = $item_info[0]["quantity"];
                } else {
                    $items_stores_data[$i]["quantity"] = "-";
                }
                $tmp = array();
                array_push($tmp, self::idFormat_item($item_details[0]["id"]));
                array_push($tmp, $item_details[0]["barcode"]);
                array_push($tmp, $item_details[0]["description"]);
                array_push($tmp, $sizes_info_label[$item_details[0]["size_id"]]);
                array_push($tmp, $colors_info_label[$item_details[0]["color_text_id"]]);
                array_push($tmp, floatval($items_stores_data[$i]["quantity"]));
                array_push($tmp, "<b>" . $items_stores_data[$i]["store_name"] . "</b>");
                array_push($tmp, "");
                if ($stores_data[$i]["primary_db"] == 1) {
                    array_push($tmp, 0);
                } else {
                    array_push($tmp, $stores_data[$i]["id"]);
                }
                array_push($data_array["data"], $tmp);
            }
        }
        echo json_encode($data_array);
    }
    public function get_all_items_qty_in_all_stores___($item_id)
    {
        $outside_connection_ = $this->model("outside_connection_");
        $store = $this->model("store");
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
        $item_details = $items->get_item($item_id);
        $data_array["data"] = array();
        if (0 < count($item_details)) {
            $stores_data = array();
            $stores = $store->getAllStores();
            for ($i = 0; $i < count($stores); $i++) {
                $stores_data[$i] = $stores[$i];
            }
            $items_stores_data = array();
            for ($i = 0; $i < count($stores_data); $i++) {
                $custom_connection = my_sql::custom_connection($stores_data[$i]["ip_address"], $stores_data[$i]["username"], $stores_data[$i]["password"], $stores_data[$i]["db"]);
                $items_stores_data[$i]["id"] = $stores_data[$i]["id"];
                $items_stores_data[$i]["store_name"] = $stores_data[$i]["name"];
                if ($custom_connection) {
                    $item_info = $outside_connection_->getQtyOfItem($item_details[0]["id"], $custom_connection);
                    $items_stores_data[$i]["quantity"] = $item_info[0]["quantity"];
                } else {
                    $items_stores_data[$i]["quantity"] = "-";
                }
                $tmp = array();
                array_push($tmp, "<b>" . $items_stores_data[$i]["store_name"] . "</b>");
                array_push($tmp, floatval($items_stores_data[$i]["quantity"]));
                array_push($data_array["data"], $tmp);
            }
        }
        echo json_encode($data_array);
    }
}

?>