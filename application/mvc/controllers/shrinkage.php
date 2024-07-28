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
class shrinkage extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function upload_excel_file()
    {
        $return = array();
        $return["error"] = 0;
        $return["msg"] = "";
        $id = $_POST["id"];
        $upload_dir = "shrinkage_excels/";
        $file_name = $_FILES["excelfile"]["name"];
        $file_size = $_FILES["excelfile"]["size"];
        $file_tmp = $_FILES["excelfile"]["tmp_name"];
        $file_type = $_FILES["excelfile"]["type"];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array("xlsx");
        if (in_array($file_ext, $allowed_ext) === false) {
            $return["error"] = 1;
            $return["msg"] = "Only Excel files are allowed";
            echo json_encode($return);
            exit;
        }
        $new_file_name = uniqid() . "_" . $file_name;
        $file_path = $upload_dir . $new_file_name;
        move_uploaded_file($file_tmp, $file_path);
        $result_info = self::read_data($file_path, $id, $new_file_name);
        echo json_encode($result_info);
    }
    public function read_data($file, $id, $new_file_name)
    {
        $shrinkage = $this->model("shrinkage");
        $items = $this->model("items");
        $shrinkage->update_file_name($id, $new_file_name);
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        $data = $sheet->toArray();
        $result_info = array();
        $result_info["nb"] = 0;
        $result_info["error"] = 0;
        $result_info["error_details"] = array();
        $barcodes = array();
        for ($i = 0; $i < count($data); $i++) {
            if (1 <= $i) {
                array_push($barcodes, $data[$i][0]);
                $result_info["nb"]++;
            }
        }
        $shrinkage->update_total_rows_in_excel($id, $result_info["nb"]);
        $shrinkage->reset_scanner_qty($id);
        for ($i = 0; $i < count($barcodes); $i++) {
            if (strlen($barcodes[$i]) < 5) {
                $barcodes[$i] = str_pad($barcodes[$i], 5, "0", STR_PAD_LEFT);
            }
        }
        $barcodes_array_count = array_count_values($barcodes);
        $search_barcodes = array();
        foreach ($barcodes_array_count as $key => $value) {
            array_push($search_barcodes, $key);
        }
        if (0 < count($search_barcodes)) {
            $items_info = $items->getAllItemsEvenDeleted_limited_by_barcodes_array($search_barcodes);
        } else {
            $items_info = array();
        }
        $items_info_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            if (!isset($items_info_array[ltrim($items_info[$i]["barcode"], "0")])) {
                $items_info_array[ltrim($items_info[$i]["barcode"], "0")] = $items_info[$i]["id"];
            }
        }
        $chunks = array_chunk($barcodes_array_count, 50, true);
        $shrinkage->reset_failed_scanner($id);
        for ($i = 0; $i < count($chunks); $i++) {
            $barcodes_array_count__ = $chunks[$i];
            $query_bulk_execute = false;
            $query_bulk = "";
            $query_bulk .= " UPDATE shrinkages_details SET scanner_qty = CASE ";
            foreach ($barcodes_array_count__ as $key => $value) {
                if (isset($items_info_array[$key])) {
                    $query_bulk_execute = true;
                    $query_bulk .= " WHEN shrinkages_id=" . $id . " and item_id =" . $items_info_array[$key] . " THEN " . $value . " ";
                } else {
                    for ($r = 0; $r < $value; $r++) {
                        $result_info["error"]++;
                        $shrinkage->add_failed_scanner($key, $id);
                        array_push($result_info["error_details"], $key);
                    }
                }
            }
            $query_bulk .= " ELSE scanner_qty ";
            $query_bulk .= "END;";
            if ($query_bulk_execute) {
                $shrinkage->update_scanner_qty_bulk($query_bulk);
            }
        }
        $shrinkage->update_total_scanner_qty_sucsess($id);
        return $result_info;
    }
    public function shrinkage_mng()
    {
        self::giveAccessTo();
        $this->view("shrinkage_mng");
    }
    public function delete_shrinkage($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $shrinkage = $this->model("shrinkage");
        $return = array();
        $return["status"] = $shrinkage->delete_shrinkage($id);
        echo json_encode($return);
    }
    public function get_shrinkage_by_id($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $shrinkage = $this->model("shrinkage");
        $return = $shrinkage->get_shrinkage_by_id($id);
        echo json_encode($return);
    }
    public function check_current_stock_and_shrinkingage($_shrinking_id)
    {
        self::giveAccessTo();
        $shrinking_id = filter_var($_shrinking_id, FILTER_SANITIZE_NUMBER_INT);
        $shrinkage = $this->model("shrinkage");
        $shrinkage->sync_shrinkage_with_stock($shrinking_id);
    }
    public function add_new_shrinkage()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["stores_id"] = filter_input(INPUT_POST, "stores_list", FILTER_SANITIZE_NUMBER_INT);
        $info["shrinkage_description"] = filter_input(INPUT_POST, "shrinkage_description", self::conversion_php_version_filter());
        $shrinkage = $this->model("shrinkage");
        if ($info["id_to_edit"] == 0) {
            $shrinkage->add_new_shrinkage($info);
        } else {
            $shrinkage->update_shrinkage($info);
        }
        echo json_encode(array());
    }
    public function getAllShrinkagesDetails($_id, $_group_id, $_supplier_id, $_subcategory_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $group_id = filter_var($_group_id, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $subcategory_id = filter_var($_subcategory_id, FILTER_SANITIZE_NUMBER_INT);
        $shrinkage = $this->model("shrinkage");
        $items = $this->model("items");
        self::check_current_stock_and_shrinkingage($id);
        $items_info = $items->getAllItemsEvenDeleted_limited();
        $items_info_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $items_info_array[$items_info[$i]["id"]] = $items_info[$i];
        }
        $info = array();
        $info["id"] = $id;
        $info["group_id"] = $group_id;
        $info["supplier_id"] = $supplier_id;
        $info["subcategory_id"] = $subcategory_id;
        $shrinkage_info = $shrinkage->getShrinkageDetailsById($info);
        $data_array["data"] = array();
        for ($i = 0; $i < count($shrinkage_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_shrinkage($shrinkage_info[$i]["id"]));
            array_push($tmp, self::idFormat_item($shrinkage_info[$i]["item_id"]));
            if (isset($items_info_array[$shrinkage_info[$i]["item_id"]])) {
                if (strlen($tables_info[$i]["barcode"]) < 5) {
                    array_push($tmp, sprintf("%05s", $items_info_array[$shrinkage_info[$i]["item_id"]]["barcode"]));
                } else {
                    array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["barcode"]);
                }
                array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["description"]);
            } else {
                array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["barcode"]);
                array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["description"]);
            }
            array_push($tmp, round($shrinkage_info[$i]["old_stock_qty"], 3));
            array_push($tmp, round($shrinkage_info[$i]["new_stock_qty"], 3));
            array_push($tmp, $shrinkage_info[$i]["checked_date"]);
            array_push($tmp, number_format(floor($shrinkage_info[$i]["avg_cost"]), 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, number_format($shrinkage_info[$i]["avg_cost"] * ($shrinkage_info[$i]["old_stock_qty"] - $shrinkage_info[$i]["new_stock_qty"]), 2) . " " . $this->settings_info["default_currency_symbol"]);
            array_push($tmp, floor($shrinkage_info[$i]["scanner_qty"]));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function failed_shrinkage($_shrinkage_id, $_p1, $_p2, $_p3, $_p4)
    {
        $shrinkage_id = filter_var($_shrinkage_id, FILTER_SANITIZE_NUMBER_INT);
        $shrinkage = $this->model("shrinkage");
        $failed = $shrinkage->failed_shrinkage($shrinkage_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($failed); $i++) {
            $tmp = array();
            array_push($tmp, $shrinkage_id);
            array_push($tmp, $failed[$i]["item_barcode"]);
            array_push($tmp, $failed[$i]["num"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function change_qty_sh($_id, $_new_qty)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $new_qty = filter_var($_new_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $shrinkage = $this->model("shrinkage");
        $items = $this->model("items");
        $store = $this->model("store");
        $info_shrink = $shrinkage->getShrinkageDetailsByDetailsId($id);
        $item_info = $items->get_item($info_shrink[0]["item_id"]);
        $info_shrinkage_info = $shrinkage->getShrinkageById($info_shrink[0]["shrinkages_id"]);
        $shrinkage->change_qty_sh($id, $new_qty, $item_info[0]["buying_cost"]);
        $info = array();
        $info["old_qty"] = $info_shrink[0]["old_stock_qty"];
        $info["qty"] = $new_qty;
        $info["item_id"] = $info_shrink[0]["item_id"];
        $info["store_id"] = $info_shrinkage_info[0]["store_id"];
        $info["user_id"] = $_SESSION["id"];
        $info["samecost"] = 0;
        $info["cost"] = 0;
        $info["source"] = self::idFormat_shrinkage($id);
        $store->set_qty($info);
        $return = $shrinkage->getShrinkageDetailsByDetailsId($id);
        $return[0]["total_cost"] = self::global_number_formatter($item_info[0]["buying_cost"] * ($return[0]["old_stock_qty"] - $return[0]["new_stock_qty"]), $this->settings_info);
        echo json_encode($return);
    }
    public function get_shrinkage_info($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $shrinkage = $this->model("shrinkage");
        $shrinkage_info_total_lost = $shrinkage->get_total_lost($id);
        $info = array();
        $info["total_lost"] = number_format($shrinkage_info_total_lost[0]["total_lost"], 2) . " " . $this->settings_info["default_currency_symbol"];
        echo json_encode($info);
    }
    public function exclude_items_in_shrinkage()
    {
        self::giveAccessTo();
        $shrinkage = $this->model("shrinkage");
        $items = $this->model("items");
        $barcodes = filter_input(INPUT_POST, "barcodes_input", self::conversion_php_version_filter());
        $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
        $barcodes_array = explode(",", $barcodes);
        $barcodes_query = "";
        $shrinkage->reset_scanner_qty($id);
        for ($i = 0; $i < count($barcodes_array); $i++) {
            if (strlen($barcodes_array[$i]) == 5) {
                $barcodes_array[$i] = ltrim($barcodes_array[$i], "0");
            }
        }
        $barcodes_array_count = array_count_values($barcodes_array);
        $items_info = $items->getAllItemsEvenDeleted_limited();
        $items_info_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            if (!isset($items_info_array[ltrim($items_info[$i]["barcode"], "0")])) {
                $items_info_array[ltrim($items_info[$i]["barcode"], "0")] = $items_info[$i]["id"];
            }
        }
        $query_bulk = "";
        $query_bulk .= " UPDATE shrinkages_details SET scanner_qty = CASE ";
        foreach ($barcodes_array_count as $key => $value) {
            if (isset($items_info_array[$key])) {
                $query_bulk .= " WHEN shrinkages_id=" . $id . " and item_id =" . $items_info_array[$key] . " THEN " . $value . " ";
            }
        }
        $query_bulk .= " ELSE scanner_qty ";
        $query_bulk .= "END;";
        $shrinkage->update_scanner_qty_bulk($query_bulk);
        echo json_encode(array());
    }
    public function getAllShrinkages()
    {
        self::giveAccessTo();
        $shrinkage = $this->model("shrinkage");
        $shrinkages = $shrinkage->getAllShrinkages();
        $store = $this->model("store");
        $stores = $store->getStores();
        $store_name = array();
        for ($i = 0; $i < count($stores); $i++) {
            $store_name[$stores[$i]["id"]] = $stores[$i]["name"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($shrinkages); $i++) {
            $shrinkage_info_total_lost = $shrinkage->get_total_lost($shrinkages[$i]["id"]);
            $shrinkages_details = $shrinkage->getAllShrinkagesDetails($shrinkages[$i]["id"]);
            $shrinkages_details_compared = $shrinkage->getAllShrinkagesDetailsCompared($shrinkages[$i]["id"]);
            $tmp = array();
            array_push($tmp, self::idFormat_shrinkage($shrinkages[$i]["id"]));
            array_push($tmp, $shrinkages[$i]["creation_date"]);
            array_push($tmp, $store_name[$shrinkages[$i]["store_id"]]);
            array_push($tmp, $shrinkages[$i]["description"]);
            array_push($tmp, $shrinkages_details[0]["num"]);
            array_push($tmp, $shrinkages_details_compared[0]["num"]);
            array_push($tmp, $shrinkages_details[0]["num"] - $shrinkages_details_compared[0]["num"]);
            array_push($tmp, self::global_number_formatter($shrinkage_info_total_lost[0]["total_lost"], $this->settings_info));
            array_push($tmp, $shrinkages[$i]["excel_total_rows_nb"]);
            array_push($tmp, $shrinkages[$i]["scanner_qty_success"]);
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllShrinkagesDetails_New($_id, $_group_id, $_supplier_id, $_subcategory_id, $_qty_info)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $group_id = filter_var($_group_id, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $subcategory_id = filter_var($_subcategory_id, self::conversion_php_version_filter());
        $qty_info = filter_var($_qty_info, FILTER_SANITIZE_NUMBER_INT);
        $shrinkage = $this->model("shrinkage");
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
        self::check_current_stock_and_shrinkingage($id);
        $items_info = $items->getAllItemsEvenDeleted_limited();
        $items_info_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            $items_info_array[$items_info[$i]["id"]] = $items_info[$i];
        }
        $info = array();
        $info["id"] = $id;
        $info["group_id"] = $group_id;
        $info["supplier_id"] = $supplier_id;
        $info["subcategory_id"] = explode(",", $subcategory_id);
        $info["qty_info"] = $qty_info;
        $shrinkage_info = $shrinkage->getShrinkageDetailsById_New($info);
        $data_array["data"] = array();
        for ($i = 0; $i < count($shrinkage_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_shrinkage($shrinkage_info[$i]["id"]));
            if ($items_info_array[$shrinkage_info[$i]["item_id"]]["deleted"] == 1) {
                array_push($tmp, "<span class='red'>" . self::idFormat_item($shrinkage_info[$i]["item_id"]) . "</span>");
            } else {
                array_push($tmp, self::idFormat_item($shrinkage_info[$i]["item_id"]));
            }
            if (isset($items_info_array[$shrinkage_info[$i]["item_id"]])) {
                if (strlen($shrinkage_info[$i]["barcode"]) < 5) {
                    array_push($tmp, sprintf("%05s", $items_info_array[$shrinkage_info[$i]["item_id"]]["barcode"]));
                } else {
                    array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["barcode"]);
                }
                array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["description"]);
            } else {
                array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["barcode"]);
                array_push($tmp, $items_info_array[$shrinkage_info[$i]["item_id"]]["description"]);
            }
            array_push($tmp, $colors_info_label[$items_info_array[$shrinkage_info[$i]["item_id"]]["color_text_id"]]);
            array_push($tmp, $sizes_info_label[$items_info_array[$shrinkage_info[$i]["item_id"]]["size_id"]]);
            array_push($tmp, round($shrinkage_info[$i]["old_stock_qty"], 3));
            array_push($tmp, round($shrinkage_info[$i]["new_stock_qty"], 3));
            array_push($tmp, $shrinkage_info[$i]["checked_date"]);
            array_push($tmp, self::global_number_formatter($shrinkage_info[$i]["avg_cost"], $this->settings_info));
            array_push($tmp, self::global_number_formatter($shrinkage_info[$i]["avg_cost"] * ($shrinkage_info[$i]["old_stock_qty"] - $shrinkage_info[$i]["new_stock_qty"]), $this->settings_info));
            array_push($tmp, floor($shrinkage_info[$i]["scanner_qty"]));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>