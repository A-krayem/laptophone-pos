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
class discounts extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function get_discounted_items()
    {
        self::giveAccessTo();
        $discounts = $this->model("discounts");
        $dicounted_items = $discounts->get_all_items_under_discounts();
        print_r($dicounted_items);
    }
    public function delete_discount($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $discounts = $this->model("discounts");
        $discounts->delete_discount($id);
        echo json_encode(array());
    }
    public function add_new_discount()
    {
        self::giveAccessTo();
        $discounts = $this->model("discounts");
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["start_date"] = filter_input(INPUT_POST, "start_date", self::conversion_php_version_filter());
        $info["end_date"] = filter_input(INPUT_POST, "end_date", self::conversion_php_version_filter());
        $info["discount_name"] = filter_input(INPUT_POST, "discount_name", self::conversion_php_version_filter());
        $info["discount_value"] = filter_input(INPUT_POST, "discount_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["parent_category_id"] = filter_input(INPUT_POST, "category_id", FILTER_SANITIZE_NUMBER_INT);
        $info["category_id"] = filter_input(INPUT_POST, "sub_category_id", FILTER_SANITIZE_NUMBER_INT);
        if ($info["id_to_edit"] == 0) {
            $last_insert_item_id = $discounts->add_new_discount($info);
        } else {
            $discounts->update_discount($info);
        }
        echo json_encode(array($info["id_to_edit"]));
    }
    public function add_new_discount_group()
    {
        self::giveAccessTo();
        $discounts = $this->model("discounts");
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["start_date"] = filter_input(INPUT_POST, "start_date", self::conversion_php_version_filter());
        $info["end_date"] = filter_input(INPUT_POST, "end_date", self::conversion_php_version_filter());
        $info["discount_name"] = filter_input(INPUT_POST, "discount_name", self::conversion_php_version_filter());
        $info["discount_value"] = filter_input(INPUT_POST, "discount_value", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["group_id"] = filter_input(INPUT_POST, "group_id", FILTER_SANITIZE_NUMBER_INT);
        $info["never_end"] = 0;
        if (isset($_POST["never_end"])) {
            $info["never_end"] = 1;
        }
        if ($info["id_to_edit"] == 0) {
            $last_insert_item_id = $discounts->add_new_discount_group($info);
        } else {
            $discounts->update_discount_group($info);
        }
        echo json_encode(array($info["id_to_edit"]));
    }
    public function get_items_under_discount($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $discounts = $this->model("discounts");
        $items = $this->model("items");
        $all_item_qty_in_store = $items->get_all_item_qty_in_store(1);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $discount_details = $discounts->get_discount($id);
        $items_under_discount = $discounts->get_items_under_discount($id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_under_discount); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($items_under_discount[$i]["id"]));
            array_push($tmp, $items_under_discount[$i]["description"]);
            array_push($tmp, (double) $qty[$items_under_discount[$i]["id"]]);
            $buying_cost = $items_under_discount[$i]["buying_cost"];
            if ($items_under_discount[$i]["vat"] == 1) {
                $buying_cost = floatval($buying_cost) * floatval($this->settings_info["vat"]);
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                array_push($tmp, number_format($buying_cost, $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            }
            array_push($tmp, number_format($items_under_discount[$i]["selling_price"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($discount_details[0]["discount_value"], $this->settings_info["number_of_decimal_points"]));
            array_push($tmp, number_format($items_under_discount[$i]["selling_price"] * (1 - $discount_details[0]["discount_value"] / 100), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                array_push($tmp, number_format($items_under_discount[$i]["selling_price"] * (1 - $discount_details[0]["discount_value"] / 100) - $buying_cost, $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_items_under_discount_group($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $discounts = $this->model("discounts");
        $items = $this->model("items");
        $all_item_qty_in_store = $items->get_all_item_qty_in_store(1);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $discount_details = $discounts->get_discount($id);
        $items_under_discount = $discounts->get_items_under_discount_group($id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_under_discount); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($items_under_discount[$i]["id"]));
            array_push($tmp, $items_under_discount[$i]["description"]);
            array_push($tmp, (double) $qty[$items_under_discount[$i]["id"]]);
            if ($items_under_discount[$i]["vat"] == 1) {
                $buying_cost = floatval($buying_cost) * floatval($this->settings_info["vat"]);
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                array_push($tmp, number_format($buying_cost, $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            }
            array_push($tmp, number_format($items_under_discount[$i]["selling_price"], $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($discount_details[0]["discount_value"], $this->settings_info["number_of_decimal_points"]));
            array_push($tmp, number_format($items_under_discount[$i]["selling_price"] * (1 - $discount_details[0]["discount_value"] / 100), $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                array_push($tmp, number_format($items_under_discount[$i]["selling_price"] * (1 - $discount_details[0]["discount_value"] / 100) - $buying_cost, $this->settings_info["number_of_decimal_points"]) . " " . $this->settings_info["default_currency_symbol"]);
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_discount($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $discounts = $this->model("discounts");
        $discount = $discounts->get_discount($id);
        $discount[0]["discount_value"] = $discount[0]["discount_value"];
        echo json_encode($discount);
    }
    public function getAllDiscounts_bygroups($store_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $discounts = $this->model("discounts");
        $items = $this->model("items");
        $all_items = $items->getAllItemsEvenDeleted();
        $all_items_array = array();
        for ($i = 0; $i < count($all_items); $i++) {
            $all_items_array[(int) $all_items[$i]["item_group"]] = $all_items[$i]["description"];
        }
        $all_discounts = $discounts->getDiscounts_bygroups();
        $data_array["data"] = array();
        for ($i = 0; $i < count($all_discounts); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_discount($all_discounts[$i]["id"]));
            array_push($tmp, $all_discounts[$i]["discount_name"]);
            array_push($tmp, $all_discounts[$i]["start_date"]);
            array_push($tmp, $all_discounts[$i]["end_date"]);
            array_push($tmp, $all_discounts[$i]["creation_date"]);
            array_push($tmp, $all_discounts[$i]["discount_value"] . " %");
            array_push($tmp, $all_items_array[$all_discounts[$i]["group_id"]]);
            $now = date("Y-m-d H:i:s", strtotime("now"));
            if ($all_discounts[$i]["start_date"] <= $now && $now <= $all_discounts[$i]["end_date"] || $all_discounts[$i]["never_end"] == 1) {
                array_push($tmp, "1");
            } else {
                array_push($tmp, "0");
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllDiscounts($store_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $discounts = $this->model("discounts");
        $categories_class = $this->model("categories");
        $parent_categories = $categories_class->getAllParentCategories();
        $categories = $categories_class->getAllCategories();
        $pc = array();
        for ($i = 0; $i < count($parent_categories); $i++) {
            $pc[$parent_categories[$i]["id"]] = $parent_categories[$i]["name"];
        }
        $sc = array();
        $sc[0] = "All";
        for ($i = 0; $i < count($categories); $i++) {
            $sc[$categories[$i]["id"]] = $categories[$i]["description"];
        }
        $all_discounts = $discounts->getDiscounts();
        $data_array["data"] = array();
        for ($i = 0; $i < count($all_discounts); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_discount($all_discounts[$i]["id"]));
            array_push($tmp, $all_discounts[$i]["discount_name"]);
            array_push($tmp, $all_discounts[$i]["start_date"]);
            array_push($tmp, $all_discounts[$i]["end_date"]);
            array_push($tmp, $all_discounts[$i]["creation_date"]);
            array_push($tmp, $all_discounts[$i]["discount_value"] . " %");
            if ($all_discounts[$i]["category_parent_id"] != NULL) {
                array_push($tmp, $pc[$all_discounts[$i]["category_parent_id"]]);
                array_push($tmp, $sc[$all_discounts[$i]["category_id"]]);
            } else {
                array_push($tmp, "");
                array_push($tmp, "");
            }
            $now = date("Y-m-d H:i:s", strtotime("now"));
            if ($all_discounts[$i]["start_date"] <= $now && $now <= $all_discounts[$i]["end_date"]) {
                array_push($tmp, "1");
            } else {
                array_push($tmp, "0");
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function _default()
    {
        self::giveAccessTo();
        $this->view("discount_by_categories");
    }
    public function discount_by_groups()
    {
        self::giveAccessTo();
        $this->view("discount_by_group");
    }
}

?>