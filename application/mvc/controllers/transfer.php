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
class transfer extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function print_pos_transfer($id, $from_store_id)
    {
        $transfer = $this->model("transfer");
        $store = $this->model("store");
        $items = $this->model("items");
        $users_remote = $this->model("users_remote");
        $connection = self::get_store_connection($from_store_id);
        $transfer_info = $transfer->get_remote_transfer_branch_by_id($id, $connection);
        $from_details = $store->getStoresById($transfer_info[0]["from_store_id"]);
        $to_details = $store->getStoresById($transfer_info[0]["to_store_id"]);
        $user_info = $users_remote->get_user_info_by_connection($connection, $transfer_info[0]["created_by"]);
        $item_info = $items->get_item($transfer_info[0]["item_id"]);
        $data = array();
        $data["transfer_id"] = $transfer_info[0]["id"];
        $data["from_branch"] = $from_details[0]["name"];
        $data["to_branch"] = $to_details[0]["name"];
        $data["vendor"] = $user_info[0]["username"];
        $data["transfer_date"] = $transfer_info[0]["creation_date"];
        $data["transfer_qty"] = $transfer_info[0]["transfer_qty"];
        $data["item"] = $item_info[0];
        $this->view("print_templates/pos8/print_pos_transfer", $data);
    }
    public function confirm_transfer($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $affected = $transfer->confirm_transfer($id, $_SESSION["store_id"], $_SESSION["id"]);
        echo json_encode(array());
    }
    public function show_to_confirm_transfer($_id)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $users = $this->model("user");
        $employees_info = $users->getAllUsersEvenDeleted();
        $employees_info_array = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
        }
        $result = $transfer->show_to_confirm_transfer($id, $_SESSION["store_id"]);
        $return = array();
        if (0 < count($result["transfer_details"])) {
            $return["found"] = 1;
            $return["transfer_details"] = $result["transfer_details"];
            $return["by"] = $employees_info_array[$return["transfer_details"][0]["confirmed_by_receiver_id"]];
        } else {
            $return["found"] = 0;
        }
        echo json_encode($return);
    }
    public function _default()
    {
        $customers = $this->model("customers");
        $data = array();
        $data["clients_types"] = $customers->getEnabledCustomersTypes();
        $this->view("transfers", $data);
    }
    public function add_shortcut_to_transfer($_transfer_id, $_shortcut_id)
    {
        self::giveAccessTo();
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $shortcut_id = filter_var($_shortcut_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer->add_shortcut_to_transfer($transfer_id, $shortcut_id);
        echo json_encode(array());
    }
    public function transfer_pi($_transfer_id, $_transfer_pi_id)
    {
        self::giveAccessTo();
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer_pi_id = filter_var($_transfer_pi_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer->transfer_pi($transfer_id, $transfer_pi_id);
        echo json_encode(array());
    }
    public function delete_all_items_in_transfer_list($_transfer_id)
    {
        self::giveAccessTo();
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer->delete_all_items_in_transfer_list($transfer_id);
        echo json_encode(array());
    }
    public function add_all_to_transfer($_supplier_id, $_category_id, $_subcategory_id, $_transfer_id)
    {
        self::giveAccessTo();
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
        $subcategory_id = filter_var($_subcategory_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $itemboxes = 0;
        $transfer = $this->model("transfer");
        $items = $this->model("items");
        $all_transfer_list_items = array();
        $all_transfer_list = $transfer->get_all_transfer_list($transfer_id);
        for ($i = 0; $i < count($all_transfer_list); $i++) {
            array_push($all_transfer_list_items, $all_transfer_list[$i]["item_id"]);
        }
        $all_it_qty = $items->get_all_item_qty_in_store($_SESSION["store_id"]);
        $qty = array();
        for ($i = 0; $i < count($all_it_qty); $i++) {
            $qty[$all_it_qty[$i]["item_id"]] = $all_it_qty[$i]["quantity"];
        }
        $tables_info = $items->getAllItems_withfilter_to_transfer($category_id, $subcategory_id, $itemboxes, $supplier_id);
        for ($i = 0; $i < count($tables_info); $i++) {
            if (!in_array($tables_info[$i]["id"], $all_transfer_list_items)) {
                $info = array();
                $info["item_id"] = $tables_info[$i]["id"];
                $info["transfer_id"] = $transfer_id;
                $info["qty"] = $qty[$info["item_id"]];
                $it_info = $items->get_item($tables_info[$i]["id"]);
                $info["selling_price"] = $it_info[0]["selling_price"];
                $info["buying_cost"] = $it_info[0]["buying_cost"];
                $transfer->add_to_transfer_list($info);
            }
        }
        echo json_encode(array());
    }
    public function add_to_transfer_list($_item_id, $_transfer_id)
    {
        self::giveAccessTo();
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer_info = $transfer->get_transfer_by_id($transfer_id);
        $items = $this->model("items");
        $it_info = $items->get_item($item_id);
        $qty = 1;
        if ($it_info[0]["is_composite"] == 1) {
            $composite_details = $items->get_composite_item_id($item_id);
            $item_id = $composite_details[0]["item_id"];
            $it_info = $items->get_item($item_id);
            $qty = $composite_details[0]["qty"];
        }
        $all_transfer_list_items = array();
        $all_transfer_list = $transfer->get_all_transfer_list($transfer_id);
        for ($i = 0; $i < count($all_transfer_list); $i++) {
            array_push($all_transfer_list_items, $all_transfer_list[$i]["item_id"]);
        }
        $info = array();
        $info["item_id"] = $item_id;
        $info["transfer_id"] = $transfer_id;
        $info["qty"] = $qty;
        $info["buying_cost"] = $it_info[0]["buying_cost"];
        if ($transfer_info[0]["pricing_type"] == 1) {
            $info["selling_price"] = $it_info[0]["selling_price"];
        }
        if ($transfer_info[0]["pricing_type"] == 2) {
            $info["selling_price"] = $it_info[0]["wholesale_price"];
        }
        if ($transfer_info[0]["pricing_type"] == 3) {
            $info["selling_price"] = $it_info[0]["second_wholesale_price"];
        }
        $return = array();
        if (!in_array($item_id, $all_transfer_list_items)) {
            $return["id"] = $transfer->add_to_transfer_list($info);
        } else {
            $transfer->update_transfer_list_qty($info);
        }
        echo json_encode($return);
    }
    public function delete_transfer($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $return = array();
        $return["status"] = $transfer->delete_transfer($id);
        echo json_encode($return);
    }
    public function get_transfer_by_id($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $store = $this->model("store");
        $return = $transfer->get_transfer_by_id($id);
        $store_info = $store->getStoresById($return[0]["to_store_id"]);
        $store_info_source = $store->getStoresById($return[0]["from_store_id"]);
        $return[0]["store_name"] = $store_info[0]["name"];
        $return[0]["store_name_source"] = $store_info_source[0]["name"];
        echo json_encode($return);
    }
    public function duplicate_transfer()
    {
        $info = array();
        $info["id_to_duplicate"] = filter_input(INPUT_POST, "id_to_duplicate", FILTER_SANITIZE_NUMBER_INT);
        $info["transfer_description"] = filter_input(INPUT_POST, "transfer_description", self::conversion_php_version_filter());
        $info["to_store_id"] = filter_input(INPUT_POST, "stores_list", FILTER_SANITIZE_NUMBER_INT);
        $info["from_store_id"] = filter_input(INPUT_POST, "stores_list_source", FILTER_SANITIZE_NUMBER_INT);
        $info["created_by"] = $_SESSION["id"];
        $transfer = $this->model("transfer");
        $transfer->duplicate_transfer($info);
        echo json_encode(array());
    }
    public function add_new_transfer()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["transfer_description"] = filter_input(INPUT_POST, "transfer_description", self::conversion_php_version_filter());
        $info["to_store_id"] = filter_input(INPUT_POST, "stores_list", FILTER_SANITIZE_NUMBER_INT);
        $info["from_store_id"] = filter_input(INPUT_POST, "stores_list_source", FILTER_SANITIZE_NUMBER_INT);
        $info["pricing_type"] = filter_input(INPUT_POST, "pricing_type", FILTER_SANITIZE_NUMBER_INT);
        $info["created_by"] = $_SESSION["id"];
        $return = array();
        $return["id"] = 0;
        $transfer = $this->model("transfer");
        if ($info["id_to_edit"] == 0) {
            $return["id"] = $transfer->add_new_transfer($info);
        } else {
            $transfer->update_transfer($info);
        }
        echo json_encode($return);
    }
    public function getAllItemsInTransferDetails($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
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
        $transfer_info = $transfer->get_transfer_by_id($id);
        $all_items = $items->getAllItemsEvenDeleted();
        $all_items_array = array();
        for ($i = 0; $i < count($all_items); $i++) {
            $all_items_array[$all_items[$i]["id"]] = $all_items[$i];
        }
        $transfer_details = $transfer->getAllItemsInTransferDetails($id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($transfer_details); $i++) {
            $tmp = array();
            array_push($tmp, $transfer_details[$i]["id"]);
            array_push($tmp, self::idFormat_item($transfer_details[$i]["item_id"]));
            array_push($tmp, $all_items_array[$transfer_details[$i]["item_id"]]["barcode"]);
            $color_label = "";
            if (isset($colors_info_label[$all_items_array[$transfer_details[$i]["item_id"]]["color_text_id"]])) {
                $color_label = $colors_info_label[$all_items_array[$transfer_details[$i]["item_id"]]["color_text_id"]];
            }
            $size_label = "";
            if (isset($sizes_info_label[$all_items_array[$transfer_details[$i]["item_id"]]["size_id"]])) {
                $size_label = $sizes_info_label[$all_items_array[$transfer_details[$i]["item_id"]]["size_id"]];
            }
            array_push($tmp, $all_items_array[$transfer_details[$i]["item_id"]]["description"] . " " . $color_label . " " . $size_label);
            if ($transfer_info[0]["submit_transfer"] == 0) {
                array_push($tmp, "<button type='button' class='btn btn-primary btn-xs btn-xss' onclick='qty_tr_minus(" . $transfer_details[$i]["id"] . ")'>-</button><input onchange='update_tr_qty(" . $transfer_details[$i]["id"] . ")' class='form-control input-xxs only_numeric' value='" . floor($transfer_details[$i]["qty"]) . "' id='transfer_deial_" . $transfer_details[$i]["id"] . "' type='text'><button onclick='qty_tr_plus(" . $transfer_details[$i]["id"] . ")' type='button' class='btn btn-primary btn-xs btn-xss'>+</button>");
            } else {
                array_push($tmp, "<button type='button' class='btn btn-primary btn-xs btn-xss disabled'>-</button><input readonly class='form-control input-xxs only_numeric disabled' value='" . floor($transfer_details[$i]["qty"]) . "' id='transfer_deial_" . $transfer_details[$i]["id"] . "' type='text'><button type='button' class='btn btn-primary btn-xs btn-xss disabled'>+</button>");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function qty_tr_minus($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer->qty_tr_minus($id);
        echo json_encode(array());
    }
    public function qty_tr_plus($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer->qty_tr_plus($id);
        echo json_encode(array());
    }
    public function update_tr_qty($_id, $_val)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $val = filter_var($_val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $transfer = $this->model("transfer");
        $transfer->update_tr_qty($id, $val);
        echo json_encode(array());
    }
    public function delete_transfer_details_item($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer->delete_transfer_details_item($id);
        echo json_encode(array());
    }
    public function submit_transfer($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $store = $this->model("store");
        $transfer_info = $transfer->get_transfer_by_id($id);
        if ($transfer_info[0]["submit_transfer"] == 0 && $transfer_info[0]["from_store_id"] == $_SESSION["store_id"]) {
            $transfer_details = $transfer->getAllItemsInTransferDetails($id);
            for ($i = 0; $i < count($transfer_details); $i++) {
                $store->reduce_qty_transfer($transfer_info[0]["from_store_id"], $transfer_details[$i]["item_id"], $transfer_details[$i]["qty"], $_SESSION["id"], self::idFormat_transfers($id));
            }
            $transfer->set_source_as_synced($id);
        }
        $transfer->submit_transfer($id);
        echo json_encode(array());
    }
    public function getAllTransfers($_date_filter)
    {
        self::giveAccessTo();
        $transfer = $this->model("transfer");
        $info = array();
        $info["date_range"] = filter_var($_date_filter, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $info["start_date"] = NULL;
        $info["end_date"] = NULL;
        if ($info["date_range"] == "latest") {
            $info["start_date"] = date("Y-m-d");
            $info["end_date"] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $info["date_range"]);
            $info["start_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $info["end_date"] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $transfers = $transfer->getAllTransfers($info);
        $store = $this->model("store");
        $stores = $store->getAllStores();
        $store_name = array();
        for ($i = 0; $i < count($stores); $i++) {
            $store_name[$stores[$i]["id"]] = $stores[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($transfers); $i++) {
            $tmp = array();
            array_push($tmp, $transfers[$i]["id"]);
            array_push($tmp, $transfers[$i]["creation_date"]);
            array_push($tmp, $transfers[$i]["description"]);
            array_push($tmp, $store_name[$transfers[$i]["from_store_id"]]);
            array_push($tmp, $store_name[$transfers[$i]["to_store_id"]]);
            if ($transfers[$i]["submit_transfer"] == 1 && $transfers[$i]["synced_source"] == 1) {
                array_push($tmp, "<b>Done</b>");
            } else {
                if ($transfers[$i]["submit_transfer"] == 1 && $transfers[$i]["synced_source"] == 0) {
                    array_push($tmp, "<span class='pending'>Pending</span>");
                } else {
                    if ($transfers[$i]["submit_transfer"] == 0) {
                        array_push($tmp, "<span class='edit_mode'>Edit mode</span>");
                    } else {
                        array_push($tmp, "Unknown");
                    }
                }
            }
            if ($transfers[$i]["submit_transfer"] == 1 && $transfers[$i]["synced_destination"] == 1) {
                array_push($tmp, "<b>Done</b>");
            } else {
                if ($transfers[$i]["submit_transfer"] == 1 && $transfers[$i]["synced_destination"] == 0) {
                    array_push($tmp, "<span class='pending'>Pending</span>");
                } else {
                    if ($transfers[$i]["submit_transfer"] == 0) {
                        array_push($tmp, "<span class='edit_mode'>Edit mode</span>");
                    } else {
                        array_push($tmp, "Unknown");
                    }
                }
            }
            if (0 < $transfers[$i]["confirmed_by_receiver_id"]) {
                array_push($tmp, $transfers[$i]["confirmed_by_receiver_id"]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, number_format($transfer->get_total_amount_of_transfer_id($transfers[$i]["id"]), 2));
            array_push($tmp, $transfers[$i]["submit_transfer"]);
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function transfer_details($_transfer_id, $_print_group)
    {
        $transfer = $this->model("transfer");
        $user = $this->model("user");
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer_details = $transfer->get_transfer_details($transfer_id);
        $data = array();
        $data["available"] = 0;
        $data["print_group"] = $_print_group;
        $data["items"] = array();
        if (0 < count($transfer_details)) {
            $data["available"] = 1;
            $data["transfer_id"] = $transfer_id;
            $data["from_store"] = $transfer_details[0]["from_store"];
            $data["to_store"] = $transfer_details[0]["to_store"];
            $data["creation_date"] = date("d-m-Y", strtotime($transfer_details[0]["creation_date"]));
            if (0 < $transfer_details[0]["created_by"]) {
                $user_info = $user->get_user_by_id($transfer_details[0]["created_by"]);
                $data["created_by"] = $user_info[0]["username"];
            } else {
                $data["created_by"] = "";
            }
            $data["shop_name"] = $this->settings_info["shop_name"];
            $data["transfer_details"] = $transfer_details;
        }
        $this->view("print_templates/a4/transfer", $data);
    }
    public function getAllBranchTransfers($date_range_, $_transfer_from, $_transfer_to)
    {
        $store = $this->model("store");
        $transfer = $this->model("transfer");
        $stock = $this->model("stock");
        $stores = $store->getAllStores();
        $date_filter = filter_var($date_range_, self::conversion_php_version_filter());
        $transfer_from = filter_var($_transfer_from, FILTER_SANITIZE_NUMBER_INT);
        $transfer_to = filter_var($_transfer_to, FILTER_SANITIZE_NUMBER_INT);
        $date_range = array();
        if ($date_filter == "thismonth") {
            $date_range[0] = date("Y-m-d", strtotime(date("Y") . "-" . date("M") . "-" . date("d")));
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" - ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
        }
        $transfer_info = array();
        for ($i = 0; $i < count($stores); $i++) {
            $connecttion = self::get_store_connection($stores[$i]["id"]);
            $branch_transfer = $transfer->get_branch_transfer_by_cnx($connecttion, $_SESSION["store_id"], $date_range, $transfer_from, $transfer_to);
            for ($k = 0; $k < count($branch_transfer); $k++) {
                array_push($transfer_info, $branch_transfer[$k]);
            }
        }
        $data_array["data"] = array();
        $total_price = 0;
        $total_cost = 0;
        $total_qty = 0;
        for ($i = 0; $i < count($transfer_info); $i++) {
            $current_item_info = $stock->get_stock_qty($transfer_info[$i]["item_id"]);
            $tmp = array();
            array_push($tmp, $transfer_info[$i]["creation_date"]);
            array_push($tmp, $transfer_info[$i]["store_name"]);
            array_push($tmp, $transfer_info[$i]["store_name_to"]);
            array_push($tmp, $transfer_info[$i]["item_id"]);
            array_push($tmp, $transfer_info[$i]["description"]);
            array_push($tmp, $transfer_info[$i]["color_name"]);
            array_push($tmp, $transfer_info[$i]["size_name"]);
            array_push($tmp, $transfer_info[$i]["transfer_qty"]);
            $total_qty += $transfer_info[$i]["transfer_qty"];
            array_push($tmp, floatval($current_item_info[0]["quantity"]));
            array_push($tmp, number_format(floatval($transfer_info[$i]["unit_price"]), 2));
            array_push($tmp, number_format(floatval($transfer_info[$i]["unit_price"]) * $transfer_info[$i]["transfer_qty"], 2));
            if ($transfer_info[$i]["cancelled_by"] == 0) {
                $total_price += floatval($transfer_info[$i]["unit_price"]) * $transfer_info[$i]["transfer_qty"];
            }
            if ($_SESSION["role"] == 1 && $_SESSION["hide_critical_data"] == 0) {
                if ($transfer_info[$i]["cancelled_by"] == 0) {
                    $total_cost += floatval($transfer_info[$i]["unit_cost"]) * $transfer_info[$i]["transfer_qty"];
                }
                array_push($tmp, number_format(floatval($transfer_info[$i]["unit_cost"]) * $transfer_info[$i]["transfer_qty"], 2));
            } else {
                array_push($tmp, self::critical_data());
            }
            if ($transfer_info[$i]["status"] == 1) {
                if ($_SESSION["store_id"] != $transfer_info[$i]["from_store_id"]) {
                    array_push($tmp, "<button style=\"width: 100%;\" type=\"button\" class=\"btn btn-success btn-xs\" onclick=\"received_branch_transfer(" . $transfer_info[$i]["transfer_id"] . "," . $transfer_info[$i]["from_store_id"] . ")\">Received</button>");
                } else {
                    array_push($tmp, "<button style=\"width: 100%;\" type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"cancel_branch_transfer(" . $transfer_info[$i]["transfer_id"] . "," . $transfer_info[$i]["from_store_id"] . ")\">Cancel</button>");
                }
            } else {
                if (0 < $transfer_info[$i]["confirmed_by"]) {
                    array_push($tmp, "<b class=\"text-success\">Confirmed</b>");
                } else {
                    array_push($tmp, "<b class=\"text-danger\">Cancelled</b>");
                }
            }
            array_push($tmp, "<button style=\"width: 100%;\" type=\"button\" class=\"btn btn-info btn-xs\" onclick=\"received_branch_transfer_print(" . $transfer_info[$i]["transfer_id"] . "," . $transfer_info[$i]["from_store_id"] . ")\">Print</button>");
            array_push($data_array["data"], $tmp);
        }
        $tmp = array();
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "<b>T:</b> " . number_format(floatval($total_qty), 0));
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "<b>T:</b> " . number_format($total_price, 2));
        if ($_SESSION["role"] == 1 && $_SESSION["hide_critical_data"] == 0) {
            array_push($tmp, "<b>T:</b> " . number_format($total_cost, 2));
        } else {
            array_push($tmp, "<b>T:</b> " . self::critical_data());
        }
        array_push($tmp, "");
        array_push($tmp, "");
        array_unshift($data_array["data"], $tmp);
        echo json_encode($data_array);
    }
    public function confirm_branch_transfer($transfer_id, $store_id)
    {
        $transfer = $this->model("transfer");
        $connection = self::get_store_connection($store_id);
        $transfer->confirm_branch_transfer($transfer_id, $connection);
        echo json_encode(array());
    }
    public function cancel_branch_transfer($transfer_id, $store_id)
    {
        $transfer = $this->model("transfer");
        $transfer->cancel_branch_transfer($transfer_id);
        echo json_encode(array());
    }
}

?>