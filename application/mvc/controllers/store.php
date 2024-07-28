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
class store extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function _default()
    {
        $this->view("store");
    }
    public function getStores()
    {
        self::giveAccessTo();
        $store = $this->model("store");
        echo json_encode($store->getAllStores());
    }
    public function update_expiry_date()
    {
        self::giveAccessTo();
        $store = $this->model("store");
        $info = array();
        $info["expiry_date"] = filter_input(INPUT_POST, "expiry_date", self::conversion_php_version_filter());
        $info["item_id"] = filter_input(INPUT_POST, "item_id", FILTER_SANITIZE_NUMBER_INT);
        $info["store_id"] = $_SESSION["store_id"];
        $info["user_id"] = $_SESSION["id"];
        $info["expiry_date"] = date("Y-m-d", strtotime($info["expiry_date"]));
        $store->update_expiry_date($info);
        echo json_encode(array());
    }
    public function add_qty()
    {
        self::giveAccessTo();
        $store = $this->model("store");
        $items = $this->model("items");
        $global_logs = $this->model("global_logs");
        $info = array();
        $info["qty"] = filter_input(INPUT_POST, "qty", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["item_id"] = filter_input(INPUT_POST, "item_id", FILTER_SANITIZE_NUMBER_INT);
        $info["store_id"] = $_SESSION["store_id"];
        $info["user_id"] = $_SESSION["id"];
        $info["samecost"] = filter_input(INPUT_POST, "samecost", FILTER_SANITIZE_NUMBER_INT);
        $info["cost"] = filter_input(INPUT_POST, "cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info_item = $items->get_item($info["item_id"]);
        $info_item_store = $store->getQtyOfItem($info["store_id"], $info["item_id"]);
        if (!isset($info["samecost"]) && $info["cost"] != "") {
            $info_hist["user_id"] = $_SESSION["id"];
            $info_hist["item_id"] = $info["item_id"];
            $info_hist["old_cost"] = $info_item[0]["buying_cost"];
            if (!isset($info["samecost"]) && $info["cost"] != "") {
                $info_hist["new_cost"] = $info["cost"];
            } else {
                $info_hist["new_cost"] = $info_item[0]["buying_cost"];
            }
            $info_item_store = $store->getQtyOfItemInAllStore($info["item_id"]);
            if ($_SESSION["centralize"] == 0) {
                $info_hist["old_qty"] = $info_item_store[0]["quantity"];
            } else {
                $info_hist["old_qty"] = self::get_sum_qty_in_all_stores($info["item_id"]);
            }
            $info_hist["new_qty"] = $info["qty"];
            $info_hist["source"] = "manual";
            $info_hist["receive_stock_id"] = "-";
            $info_hist["free"] = 0;
            $items->add_history_prices($info_hist);
            $items->set_global_average_cost($info["item_id"]);
        } else {
            if ($info["cost"] == "") {
                $info_hist["user_id"] = $_SESSION["id"];
                $info_hist["item_id"] = $info["item_id"];
                $info_hist["old_cost"] = $info_item[0]["buying_cost"];
                $info_hist["new_cost"] = $info_item[0]["buying_cost"];
                $info_item_store = $store->getQtyOfItemInAllStore($info["item_id"]);
                if ($_SESSION["centralize"] == 0) {
                    $info_hist["old_qty"] = $info_item_store[0]["quantity"];
                } else {
                    $info_hist["old_qty"] = self::get_sum_qty_in_all_stores($info["item_id"]);
                }
                $info_hist["new_qty"] = $info["qty"];
                $info_hist["source"] = "manual";
                $info_hist["receive_stock_id"] = "-";
                $info_hist["free"] = 0;
                $items->add_history_prices($info_hist);
                $items->set_global_average_cost($info["item_id"]);
            }
        }
        $info["source"] = "manual";
        $store->add_qty($info);
        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION["id"];
        $logs_info["related_to_item_id"] = $info["item_id"];
        if (0 < $info["qty"]) {
            $logs_info["description"] = "Added Qty " . $info["qty"] . " of Item (IT-" . $info["item_id"] . ")";
        } else {
            $logs_info["description"] = "Subtracted Qty " . $info["qty"] . " of Item (IT-" . $info["item_id"] . ")";
        }
        $logs_info["log_type"] = 1;
        $logs_info["other_info"] = "";
        $global_logs->add_global_log($logs_info);
        if ($this->settings_info["telegram_enable"] == 1) {
            $users = $this->model("user");
            $employees_info = $users->getAllUsersEvenDeleted();
            $employees_info_array = array();
            for ($i = 0; $i < count($employees_info); $i++) {
                $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
            }
            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION["store_id"]);
            $item_info = $items->get_item($info["item_id"]);
            $info_tel = array();
            $info_tel["message"] = "<strong>Qty Changed:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
            $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
            $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
            $info_tel["message"] .= "<strong>Item ID:</strong> " . $info["item_id"] . " \n";
            $info_tel["message"] .= "<strong>Description:</strong> " . $item_info[0]["description"] . " \n";
            $info_tel["message"] .= "<strong>Qty:</strong> " . $info["qty"] . " \n";
            self::send_to_telegram($info_tel, 1);
        }
        echo json_encode(array());
    }
    public function get_item_to_packing($id_)
    {
        $items = $this->model("items");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($id);
        $item_qty_in_store = $items->get_item_qty_in_store($id, $_SESSION["store_id"]);
        $data = array();
        $data["item_id"] = $item_info[0]["id"];
        $data["description"] = $item_info[0]["description"];
        $data["is_composite"] = $item_info[0]["is_composite"];
        $data["packs"] = $item_qty_in_store[0]["packs_nb"];
        echo json_encode($data);
    }
    public function get_item($id_, $store_id_)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $measure = $this->model("measures");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($id);
        $measures = $measure->getMeasures();
        $measures_info = array();
        for ($i = 0; $i < count($measures); $i++) {
            $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
        }
        $item_qty_in_store = $items->get_item_qty_in_store($id, $store_id);
        $item_info[0]["qty"] = self::value_format_custom_no_currency((double) $item_qty_in_store[0]["quantity"], $this->settings_info);
        $item_info[0]["buying_cost"] = number_format($item_info[0]["buying_cost"], 2);
        $item_info[0]["expiry_date"] = $item_qty_in_store[0]["expiry_date"];
        if ($item_info[0]["unit_measure_id"] != NULL) {
            $item_info[0]["measure"] = $measures_info[$item_info[0]["unit_measure_id"]];
        } else {
            $item_info[0]["measure"] = "";
        }
        $item_info[0]["global_admin_exist"] = $_SESSION["global_admin_exist"];
        echo json_encode($item_info);
    }
    public function getItemInStore($store_id_)
    {
        self::giveAccessTo();
        $store_id = filter_var($store_id_, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $items->sync_item_with_store($_SESSION["store_id"]);
        $items_in_store = $items->get_items_in_store($store_id, 0, 0);
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_in_store); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($items_in_store[$i]["item_id"]));
            array_push($tmp, self::idFormat_supplier($items_in_store[$i]["supplier_reference"]));
            array_push($tmp, $items_in_store[$i]["barcode"]);
            array_push($tmp, $items_in_store[$i]["description"]);
            if ($_SESSION["role"] == 1) {
                array_push($tmp, (double) $items_in_store[$i]["quantity"]);
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>