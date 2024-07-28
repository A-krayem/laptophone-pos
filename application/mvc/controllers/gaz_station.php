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
class gaz_station extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function prepare_to_render()
    {
        $stock = $this->model("stock");
        $return = array();
        $gaz_station = $this->model("gaz_station");
        $station_details = $gaz_station->get_all();
        for ($i = 0; $i < count($station_details); $i++) {
            $stock_qty = $stock->get_stock_qty($station_details[$i]["item_id"]);
            $return[$i]["id"] = $station_details[$i]["id"];
            $return[$i]["name"] = $station_details[$i]["name"];
            $return[$i]["min"] = 0;
            $return[$i]["max"] = $station_details[$i]["max_stock"];
            $return[$i]["stock"] = $stock_qty[0]["quantity"];
        }
        echo json_encode($return);
    }
    public function submit_dispensing_gun_station()
    {
        $info = array();
        $info["gun_name"] = filter_input(INPUT_POST, "gun_name", self::conversion_php_version_filter());
        $info["gun_st_counter"] = filter_input(INPUT_POST, "gun_st_counter", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["search_item"] = filter_input(INPUT_POST, "search_item", FILTER_SANITIZE_NUMBER_INT);
        $info["created_by"] = $_SESSION["id"];
        $info["max_stock"] = filter_input(INPUT_POST, "max_stock", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $gaz_station = $this->model("gaz_station");
        $last_id = $gaz_station->add_gun($info);
        echo json_encode(array($last_id));
    }
    public function get_all_dispensing_guns()
    {
        $gaz_station = $this->model("gaz_station");
        $items = $this->model("items");
        $stock = $this->model("stock");
        $result = $gaz_station->get_all();
        $data_array["data"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $item_details = $items->get_item($result[$i]["item_id"]);
            $stock_qty = $stock->get_stock_qty($result[$i]["item_id"]);
            $tmp = array();
            array_push($tmp, $result[$i]["id"]);
            array_push($tmp, $result[$i]["name"]);
            array_push($tmp, $result[$i]["creation_date"]);
            array_push($tmp, floatval($result[$i]["starting_counter"]));
            array_push($tmp, $item_details[0]["description"]);
            array_push($tmp, number_format(floatval($stock_qty[0]["quantity"])));
            array_push($tmp, number_format(floatval($result[$i]["max_stock"])));
            array_push($tmp, "<button onclick=\"set_new_counter(" . $result[$i]["id"] . ")\" style=\"width:100%; padding:1px;\" type=\"button\" class=\"btn btn-info btn-sm\">Set New Counter</button>");
            if ($_SESSION["role"] == 1) {
                array_push($tmp, "<button onclick=\"delete_disp_gun(" . $result[$i]["id"] . ")\" style=\"width:100%; padding:1px;\" type=\"button\" class=\"btn btn-danger btn-sm\">Delete</button>");
            } else {
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function delete_disp_gun($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $gaz_station = $this->model("gaz_station");
        $gaz_station->delete_disp_gun($id);
        echo json_encode(array());
    }
    public function delete_counter($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $gaz_station = $this->model("gaz_station");
        $invoice_model = $this->model("invoice");
        $store = $this->model("store");
        $items = $this->model("items");
        $gaz_station_set_counter_details = $gaz_station->get_set_counter_details_details($id);
        $gaz_station_details = $gaz_station->get_gaz_station_details($gaz_station_set_counter_details[0]["gaz_station_id"]);
        $gaz_station->delete_counter($id);
        $invoice_model->delete_invoice($gaz_station_set_counter_details[0]["invoice_id"]);
        $query = "select * from invoice_items where invoice_id=" . $gaz_station_set_counter_details[0]["invoice_id"] . " and item_id=" . $gaz_station_details[0]["item_id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $qty = $result[0]["qty"];
        if ($_SESSION["role"] == 1) {
            $info_add_qty = array();
            $info_add_qty["qty"] = $qty;
            $info_add_qty["item_id"] = $gaz_station_details[0]["item_id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "soldbyadmin-" . ${$gaz_station_set_counter_details[0]["invoice_id"]};
            $store->add_qty($info_add_qty);
        } else {
            $store_info = array();
            $store_info["store_id"] = $_SESSION["store_id"];
            $store_info["user_id"] = $_SESSION["id"];
            $store_info["source"] = "pos";
            $store_info["qty"] = $qty;
            $store_info["item_id"] = $gaz_station_details[0]["item_id"];
            $store->add_qty($store_info);
        }
        echo json_encode(array());
    }
    public function get_all_counters($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $gaz_station = $this->model("gaz_station");
        $users = $this->model("user");
        $employees_info = $users->getAllUsersEvenDeleted();
        $employees_info_array = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
        }
        $result = $gaz_station->get_all_counters($id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp = array();
            array_push($tmp, $result[$i]["id"]);
            array_push($tmp, $employees_info_array[$result[$i]["created_by"]]);
            array_push($tmp, $result[$i]["creation_date"]);
            array_push($tmp, floatval($result[$i]["old_counter"]));
            array_push($tmp, floatval($result[$i]["new_counter"]));
            array_push($tmp, floatval($result[$i]["new_counter"]) - floatval($result[$i]["old_counter"]));
            array_push($tmp, floatval($result[$i]["debt"]));
            array_push($tmp, floatval($result[$i]["cash"]));
            array_push($tmp, $result[$i]["invoice_id"]);
            array_push($tmp, number_format(floatval($result[$i]["available_stock"])));
            if ($i == count($result) - 1) {
                array_push($tmp, "<button onclick=\"delete_counter(" . $result[$i]["id"] . "," . $result[$i]["gaz_station_id"] . ")\" style=\"width:100%; padding:1px;\" type=\"button\" class=\"btn btn-danger btn-sm\">Delete</button>");
            } else {
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_gun_details($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $gaz_station = $this->model("gaz_station");
        $gaz_station_details = $gaz_station->get_gaz_station_details($id);
        echo json_encode($gaz_station_details);
    }
    public function simulation($info)
    {
        $gaz_station = $this->model("gaz_station");
        $gaz_station_details = $gaz_station->get_gaz_station_details($info["gaz_station_id"]);
        $gaz_station_info = $gaz_station->get_last_counter_info($info["gaz_station_id"]);
        $return = array();
        $return["old_counter"] = (double) $gaz_station_info["new_counter"];
        $return["new_counter"] = (double) $info["new_counter"];
        $return["item_id"] = $gaz_station_details[0]["item_id"];
        $return["gaz_station_id"] = $info["gaz_station_id"];
        $return["old_counter_date"] = $gaz_station_info["creation_date"];
        $return["new_counter_date"] = date("Y-m-d H:i:s");
        $return["difference"] = $return["new_counter"] - $return["old_counter"];
        $return["total_debt"] = floatval($gaz_station->get_total_debt_qty($gaz_station_details[0]["item_id"], $return["old_counter_date"], $return["new_counter_date"]));
        $return["total_cash"] = $return["difference"] - $return["total_debt"];
        $return["error"] = 0;
        if ($return["difference"] < 0) {
            $return["error"] = 1;
        }
        return $return;
    }
    public function submit_new_counter()
    {
        $info = array();
        $info["new_counter"] = filter_input(INPUT_POST, "new_counter_n", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["gaz_station_id"] = filter_input(INPUT_POST, "gaz_station_id", FILTER_SANITIZE_NUMBER_INT);
        $info["save"] = filter_input(INPUT_POST, "save", FILTER_SANITIZE_NUMBER_INT);
        $return = self::simulation($info);
        if ($info["save"] == 1 && $return["error"] == 0) {
            $gaz_station = $this->model("gaz_station");
            $invoice_model = $this->model("invoice");
            $items = $this->model("items");
            $store = $this->model("store");
            $invoice_id = $invoice_model->generateInvoiceId_station($_SESSION["store_id"], $_SESSION["id"], 1, "", 0, $this->settings_info["vat"], 0);
            $info["invoice_id"] = $invoice_id;
            $info["item_id"] = $return["item_id"];
            $info["qty"] = $return["total_cash"];
            $info["custom_item"] = 0;
            $info["mobile_transfer_item"] = 0;
            $info["manual_discounted"] = 0;
            $info["mobile_transfer_device_id"] = 0;
            $items_info = $items->get_item($return["item_id"]);
            $info["buying_cost"] = $items_info[0]["buying_cost"];
            $info["is_official"] = $items_info[0]["is_official"];
            $info["mobile_transfer_item"] = 0;
            $info["vat"] = $items_info[0]["vat"];
            $info["selling_price"] = $items_info[0]["selling_price"];
            $info["discount"] = $items_info[0]["discount"];
            $info["is_composite"] = $items_info[0]["is_composite"];
            $info["vat_value"] = $this->settings_info["vat"];
            if ($info["vat"] == 0) {
                $info["final_cost"] = $info["buying_cost"] * $info["qty"];
            } else {
                $info["final_cost"] = $info["buying_cost"] * floatval($this->settings_info["vat"]) * $info["qty"];
            }
            if ($info["vat"] == 0) {
                $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"];
            } else {
                $info["final_price"] = ($info["selling_price"] - $info["selling_price"] * $info["discount"] / 100) * $info["qty"] * floatval($items_info[0]["vat_value"]);
            }
            $info["profit"] = $info["final_price"] - $info["final_cost"];
            $inv_item_id = $invoice_model->addItemsToInvoice($info);
            $invoice_model->calculate_total_cost_price($inv_item_id);
            $invoice_model->calculate_total_value($invoice_id);
            $invoice_model->calculate_total_profit_for_invoice($invoice_id);
            if ($_SESSION["role"] == 1) {
                $store->reduce_qty_by_admin($_SESSION["store_id"], $info["item_id"], $info["qty"], $_SESSION["id"], $invoice_id);
            } else {
                $store->reduce_qty_by_pos($_SESSION["store_id"], $info["item_id"], $info["qty"], $_SESSION["id"]);
            }
            $stock_model = $this->model("stock");
            $stock_qty = $stock_model->get_stock_qty($return["item_id"]);
            $return["stock"] = $stock_qty[0]["quantity"];
            $last_id = $gaz_station->set_gun_new_counter($return);
            $gaz_station->update_invoice_id($last_id, $invoice_id);
            echo json_encode(array());
        } else {
            echo json_encode($return);
        }
    }
}

?>