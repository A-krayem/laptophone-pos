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
class multiple_branches extends Controller
{
    public $licenseExpired = false;
    public $settings_info = array();
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function get_available($_item_id, $_branch_id)
    {
        $this->checkAuth();
        $itemsModel = $this->model("items");
        $branch = $this->model("branch");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $branch_id = filter_var($_branch_id, FILTER_SANITIZE_NUMBER_INT);
        $qty = 0;
        if ($branch_id == 0) {
            $qty_r = $itemsModel->getQtyOfItem($_SESSION["store_id"], $item_id);
            $qty = $qty_r[0]["quantity"];
        } else {
            $qty = $branch->get_branches_stock_item_id($branch_id, $item_id);
        }
        $return = array();
        $return["avqty"] = floatval($qty);
        echo json_encode($return);
    }
    public function submit_transfer_branch()
    {
        $this->checkAuth();
        $itemsModel = $this->model("items");
        $branch = $this->model("branch");
        $store = $this->model("store");
        $info = array();
        $info["qty_to_transfer"] = filter_input(INPUT_POST, "qty_to_transfer", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["from_branch"] = filter_input(INPUT_POST, "from_branch", FILTER_SANITIZE_NUMBER_INT);
        $info["to_branch"] = filter_input(INPUT_POST, "to_branch", FILTER_SANITIZE_NUMBER_INT);
        $info["item_id"] = filter_input(INPUT_POST, "item_id", FILTER_SANITIZE_NUMBER_INT);
        $qty = 0;
        if ($info["from_branch"] == 0) {
            $qty_r = $itemsModel->getQtyOfItem($_SESSION["store_id"], $info["item_id"]);
            $qty = $qty_r[0]["quantity"];
        } else {
            $qty = $branch->get_branches_stock_item_id($info["from_branch"], $info["item_id"]);
        }
        if ($qty < $info["qty_to_transfer"]) {
            echo json_encode(array(-1));
            exit;
        }
        $item_info = $itemsModel->get_item($info["item_id"]);
        if (count($item_info) == 0) {
            echo json_encode(array(-2));
            exit;
        }
        if ($info["from_branch"] == 0) {
            $info_add_qty = array();
            $info_add_qty["qty"] = 0 - $info["qty_to_transfer"];
            $info_add_qty["item_id"] = $info["item_id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "NBTRANSIN-" . $info["to_branch"];
            $store->add_qty($info_add_qty);
        }
        if ($info["to_branch"] == 0) {
            $info_add_qty = array();
            $info_add_qty["qty"] = $info["qty_to_transfer"];
            $info_add_qty["item_id"] = $info["item_id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "NBTRANSOUT-" . $info["from_branch"];
            $store->add_qty($info_add_qty);
        }
        $branch->submit_transfer_branch($info);
        echo json_encode(array());
    }
    public function deleted_branch($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $branch = $this->model("branch");
        if (0 < $branch->tot_qty($id)) {
            echo json_encode(array(-1));
            exit;
        }
        $status = $branch->deleted_branch($id);
        echo json_encode(array($status));
    }
    public function get_all_items_in_branch($_branch_id)
    {
        self::giveAccessTo();
        $branch_id = filter_var($_branch_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $branch = $this->model("branch");
        $size = $this->model("sizes");
        $colors = $this->model("colors");
        $store_id = $_SESSION["store_id"];
        $data_array["data"] = array();
        $size_info = $size->getSizes();
        $size_info_array = array();
        for ($i = 0; $i < count($size_info); $i++) {
            $size_info_array[$size_info[$i]["id"]] = $size_info[$i]["name"];
        }
        $colors_info = $colors->getColorsText();
        $colors_info_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $tables_info = $branch->getAllItems($store_id, $branch_id);
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($tables_info[$i]["id"]));
            array_push($tmp, $tables_info[$i]["description"]);
            array_push($tmp, $tables_info[$i]["barcode"]);
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                array_push($tmp, self::global_number_formatter($tables_info[$i]["buying_cost"], $this->settings_info));
            }
            array_push($tmp, self::global_number_formatter($tables_info[$i]["selling_price"], $this->settings_info));
            array_push($tmp, self::global_number_formatter($tables_info[$i]["discount"], $this->settings_info));
            array_push($tmp, floor($tables_info[$i]["quantity"]));
            if ($tables_info[$i]["size_id"] != NULL && $tables_info[$i]["size_id"] != "") {
                array_push($tmp, $size_info_array[$tables_info[$i]["size_id"]]);
            } else {
                array_push($tmp, "");
            }
            if (!is_null($tables_info[$i]["color_text_id"]) && $tables_info[$i]["color_text_id"] != "") {
                array_push($tmp, $colors_info_array[$tables_info[$i]["color_text_id"]]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function submit_new_branch()
    {
        $branchModel = $this->model("branch");
        if ($this->settings_info["enable_new_multibranches"] == 0) {
            echo json_encode(array(-2));
            exit;
        }
        $branches = $branchModel->get_branches();
        if ($this->settings_info["new_multibranches_limit"] <= count($branches)) {
            echo json_encode(array(-1));
            exit;
        }
        $info = array();
        $info["branch_name"] = filter_input(INPUT_POST, "branch_name", self::conversion_php_version_filter());
        $info["location_name"] = filter_input(INPUT_POST, "branch_location", self::conversion_php_version_filter());
        $info["created_by"] = $_SESSION["id"];
        $branchModel->add_branch($info);
        echo json_encode(array(1));
    }
    public function get_branches($_p0, $_from_branch_id)
    {
        self::giveAccessTo();
        $itemsModel = $this->model("items");
        $branch = $this->model("branch");
        $item_id = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $from_branch_id = filter_var($_from_branch_id, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $itemsModel->get_item($item_id);
        $qty = 0;
        if ($from_branch_id == 0) {
            $qty_r = $itemsModel->getQtyOfItem($_SESSION["store_id"], $item_id);
            $qty = $qty_r[0]["quantity"];
        } else {
            $qty = $branch->get_branches_stock_item_id($from_branch_id, $item_id);
        }
        $branches = $branch->get_branches();
        $return = array();
        $return["b"] = array();
        $return["item"] = array();
        $return["item_av_qty"] = floatval($qty);
        $return["item_desc"] = $item_info[0]["description"];
        for ($i = 0; $i < count($branches); $i++) {
            $return["b"][$i]["id"] = $branches[$i]["id"];
            $return["b"][$i]["bn"] = $branches[$i]["branch_name"];
            $return["b"][$i]["lc"] = $branches[$i]["location_name"];
        }
        echo json_encode($return);
    }
    public function get_all_multiple_branches()
    {
        $branch = $this->model("branch");
        $branches = $branch->get_branches();
        $user = $this->model("user");
        $users = $user->getAllUsersEvenDeleted();
        $users_array = array();
        for ($i = 0; $i < count($users); $i++) {
            $users_array[$users[$i]["id"]] = $users[$i];
        }
        $b_total_stock_value = 0;
        $data_array["data"] = array();
        for ($i = 0; $i < count($branches); $i++) {
            $tmp = array();
            $stock_v = $branch->get_stock_value_branch($branches[$i]["id"]);
            array_push($tmp, $branches[$i]["id"]);
            array_push($tmp, $users_array[$branches[$i]["created_by"]]["username"]);
            array_push($tmp, $branches[$i]["creation_date"]);
            array_push($tmp, $branches[$i]["branch_name"]);
            array_push($tmp, $branches[$i]["location_name"]);
            $b_total_stock_value += $stock_v;
            array_push($tmp, self::global_number_formatter($stock_v, $this->settings_info));
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        $data_array["b_available_brances"] = count($branches);
        $data_array["b_branches_limit"] = $this->settings_info["new_multibranches_limit"];
        $data_array["b_total_stock_value"] = $b_total_stock_value;
        $data_array["b_total_stock_profit"] = 0;
        echo json_encode($data_array);
    }
}

?>