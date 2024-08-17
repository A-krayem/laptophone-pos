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
class items extends Controller
{
    public $settings_info = NULL;
    public $settings_info_local = NULL;
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->settings_info_local = self::get_settings_local();
        $this->licenseExpired = self::licenseExpired();
    }
    public function print_item_statement__($_item_id, $_daterange)
    {
        self::__print_item_statement__($_item_id, $_daterange);
    }
    public function __print_item_statement__($_item_id, $_daterange)
    {
        $items = $this->model("items");
        $stockModel = $this->model("stock");
        $wastingModel = $this->model("wasting");
        $debitnoteModel = $this->model("debitnote");
        $branchModel = $this->model("branch");
        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $date_range_tmp = explode(" ", $daterange);
        $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        $branches__ = $branchModel->get_branches_even_deleted();
        $branches___array = array();
        for ($i = 0; $i < count($branches__); $i++) {
            $branches___array[$branches__[$i]["id"]] = $branches__[$i];
        }
        $data = array();
        $data["info"] = $items->get_item($item_id);
        $data["table"] = array();
        $item_from_pis = $stockModel->get_item_from_pis($item_id);
        $item_from_pis_debit_note = $stockModel->get_item_from_pis_debit_note($item_id);
        $item_from_pis_debit_note_on_the_fly = $stockModel->get_item_from_pis_debit_note_on_the_fly($item_id);
        $item_from_credit_note = $stockModel->get_item_credit_note($item_id);
        $item_from_pis_free = $stockModel->get_item_from_pis_free($item_id);
        $item_wasting = $wastingModel->get_wasting_of_item_id($item_id);
        $item_manual_qty_edit = $items->item_manual_qty_edit($item_id);
        $item_pack_qty_edit = $items->item_pack_qty_edit($item_id);
        $item_as_boxes = $items->item_as_boxes($item_id);
        $item_as_composer = $items->item_as_composer($item_id);
        $item_invoices = $stockModel->invoices($item_id);
        if ($_SESSION["centralize"] == 1 && WAREHOUSE_CONNECTED == 1) {
            $item_transfer = $stockModel->transfers($item_id);
        } else {
            if ($_SESSION["centralize"] == 1 && WAREHOUSE_CONNECTED == 0) {
                $item_transfer = $stockModel->transfers($item_id);
            } else {
                $item_transfer = $stockModel->transfers($item_id);
            }
        }
        $data_array = array();
        for ($i = 0; $i < count($item_from_pis); $i++) {
            $qty = floatval($item_from_pis[$i]["qty"]);
            array_push($data_array, array("type" => "pi", "date" => $item_from_pis[$i]["creation_date"], "description" => "Purchase Invoice - Ref " . $item_from_pis[$i]["id"], "qty_in" => $qty, "qty_out" => 0, "timestamp" => strtotime($item_from_pis[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_from_credit_note); $i++) {
            $qty = floatval($item_from_credit_note[$i]["qty"]);
            array_push($data_array, array("type" => "pi", "date" => $item_from_credit_note[$i]["creation_date"], "description" => "Credit note - Ref " . $item_from_pis_debit_note[$i]["id"], "qty_in" => $qty, "qty_out" => 0, "timestamp" => strtotime($item_from_credit_note[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_from_pis_debit_note); $i++) {
            $qty = floatval($item_from_pis_debit_note[$i]["returned_debit"]);
            array_push($data_array, array("type" => "pi", "date" => $item_from_pis_debit_note[$i]["creation_date"], "description" => "Debit note on purchase invoice - Ref " . $item_from_pis_debit_note[$i]["id"], "qty_in" => 0, "qty_out" => $qty, "timestamp" => strtotime($item_from_pis_debit_note[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_from_pis_debit_note_on_the_fly); $i++) {
            $qty = floatval($item_from_pis_debit_note_on_the_fly[$i]["qty"]);
            array_push($data_array, array("type" => "dn", "date" => $item_from_pis_debit_note_on_the_fly[$i]["creation_date"], "description" => "Debit note on the fly - Ref " . $item_from_pis_debit_note_on_the_fly[$i]["id"], "qty_in" => 0, "qty_out" => $qty, "timestamp" => strtotime($item_from_pis_debit_note_on_the_fly[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_from_pis_free); $i++) {
            $qty = floatval($item_from_pis_free[$i]["fqty"]);
            array_push($data_array, array("type" => "pi", "date" => $item_from_pis_free[$i]["creation_date"], "description" => "Free on purchase invoice - Ref " . $item_from_pis_free[$i]["id"], "qty_in" => $qty, "qty_out" => 0, "timestamp" => strtotime($item_from_pis_free[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_manual_qty_edit); $i++) {
            $qtin = 0;
            $qtout = 0;
            if (0 < $item_manual_qty_edit[$i]["qty"]) {
                $qtin = $item_manual_qty_edit[$i]["qty"];
            } else {
                $qtout = abs($item_manual_qty_edit[$i]["qty"]);
            }
            $desc = "Manual edit";
            if (strpos($item_manual_qty_edit[$i]["source"], "NBTRANS") !== false) {
                $bid = explode("-", $item_manual_qty_edit[$i]["source"]);
                if (0 < $item_manual_qty_edit[$i]["qty"]) {
                    $desc = "Transfer from branch " . $branches___array[$bid[1]]["branch_name"];
                } else {
                    $desc = "Transfer to branch " . $branches___array[$bid[1]]["branch_name"];
                }
            }
            array_push($data_array, array("type" => "manual", "date" => $item_manual_qty_edit[$i]["creation_date"], "description" => $desc, "qty_in" => $qtin, "qty_out" => $qtout, "timestamp" => strtotime($item_manual_qty_edit[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_pack_qty_edit); $i++) {
            $qtin = 0;
            $qtout = 0;
            if (0 < $item_pack_qty_edit[$i]["qty"]) {
                $qtin = $item_pack_qty_edit[$i]["qty"];
            } else {
                $qtout = abs($item_pack_qty_edit[$i]["qty"]);
            }
            array_push($data_array, array("type" => "manual", "date" => $item_pack_qty_edit[$i]["creation_date"], "description" => "Pack edit", "qty_in" => $qtin, "qty_out" => $qtout, "timestamp" => strtotime($item_pack_qty_edit[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_wasting); $i++) {
            $qty = floatval($item_wasting[$i]["qty"]);
            array_push($data_array, array("type" => "pi", "date" => $item_wasting[$i]["creation_date"], "description" => "Wasting - Ref " . $item_wasting[$i]["id"], "qty_in" => 0, "qty_out" => $qty, "timestamp" => strtotime($item_wasting[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_as_boxes); $i++) {
            $qty = floatval($item_as_boxes[$i]["qty"]);
            $cname = "";
            if (0 < strlen($item_as_boxes[$i]["name"])) {
                $cname .= " - " . $item_as_boxes[$i]["name"];
            }
            if (0 < strlen($item_as_boxes[$i]["middle_name"])) {
                $cname .= " - " . $item_as_boxes[$i]["middle_name"];
            }
            if (0 < strlen($item_as_boxes[$i]["last_name"])) {
                $cname .= " - " . $item_as_boxes[$i]["last_name"];
            }
            array_push($data_array, array("type" => "inv", "date" => $item_as_boxes[$i]["creation_date"], "description" => "Invoice - Ref " . $item_as_boxes[$i]["id"] . $cname, "qty_in" => 0, "qty_out" => $qty, "timestamp" => strtotime($item_as_boxes[$i]["creation_date"]), "comment" => " (As boxes)"));
        }
        for ($i = 0; $i < count($item_as_composer); $i++) {
            $qty = floatval($item_as_composer[$i]["qty"]);
            $cname = "";
            if (0 < strlen($item_as_composer[$i]["name"])) {
                $cname .= " - " . $item_as_composer[$i]["name"];
            }
            if (0 < strlen($item_as_composer[$i]["middle_name"])) {
                $cname .= " - " . $item_as_composer[$i]["middle_name"];
            }
            if (0 < strlen($item_as_composer[$i]["last_name"])) {
                $cname .= " - " . $item_as_composer[$i]["last_name"];
            }
            array_push($data_array, array("type" => "inv", "date" => $item_as_composer[$i]["creation_date"], "description" => "Invoice - Ref " . $item_as_composer[$i]["id"] . $cname, "qty_in" => 0, "qty_out" => $qty, "timestamp" => strtotime($item_as_composer[$i]["creation_date"]), "comment" => " (As Composer)"));
        }
        for ($i = 0; $i < count($item_invoices); $i++) {
            $qty = floatval($item_invoices[$i]["qty"]);
            $cname = "";
            if (0 < strlen($item_invoices[$i]["name"])) {
                $cname .= " - " . $item_invoices[$i]["name"];
            }
            if (0 < strlen($item_invoices[$i]["middle_name"])) {
                $cname .= " - " . $item_invoices[$i]["middle_name"];
            }
            if (0 < strlen($item_invoices[$i]["last_name"])) {
                $cname .= " - " . $item_invoices[$i]["last_name"];
            }
            array_push($data_array, array("type" => "inv", "date" => $item_invoices[$i]["creation_date"], "description" => "Invoice - Ref " . $item_invoices[$i]["id"] . $cname, "qty_in" => 0, "qty_out" => $qty, "timestamp" => strtotime($item_invoices[$i]["creation_date"]), "comment" => ""));
        }
        for ($i = 0; $i < count($item_transfer); $i++) {
            $qty = floatval($item_transfer[$i]["qty"]);
            $qtin = 0;
            $qtout = 0;
            if (0 < $item_transfer[$i]["qty"]) {
                $qtin = $item_transfer[$i]["qty"];
            } else {
                $qtout = abs($item_transfer[$i]["qty"]);
            }
            array_push($data_array, array("type" => "trans", "date" => $item_transfer[$i]["creation_date"], "description" => "Transfer", "qty_in" => $qtin, "qty_out" => $qtout, "timestamp" => strtotime($item_transfer[$i]["creation_date"]), "comment" => ""));
        }
        usort($data_array, function ($a, $b) {
            return $a["timestamp"] - $b["timestamp"];
        });
        for ($i = 0; $i < count($data_array); $i++) {
            array_push($data["table"], $data_array[$i]);
        }
        $this->view("print_templates/a4/item_statement_new", $data);
    }
    public function get_items_gallery($_category, $_subcategory)
    {
        $items = $this->model("items");
        $categories = $this->model("categories");
        $gallery_return = array();
        $gallery_return["gal"] = array();
        $gallery_return["categories"] = $categories->getAllParentCategoriesEvenDeleted();
        $gallery_return["sub_categories"] = $categories->getAllCategoriesEvenDeleted();
        $category = filter_var($_category, FILTER_SANITIZE_NUMBER_INT);
        $subcategory = filter_var($_subcategory, FILTER_SANITIZE_NUMBER_INT);
        $filter = array();
        $filter["category"] = $category;
        $filter["subcategory"] = $subcategory;
        $all_items_images = $items->get_all_items_images($filter);
        if ($this->settings_info["gallery_show_by_boxes"] == "0") {
            $all_available_qty = $items->get_all_qty_of_boxitems_and_items();
        } else {
            $all_available_qty = $items->get_all_available_boxes();
        }
        $boxes_qunatities = $items->prepare_all_boxes_qunatities();
        $qty_in_bx = $items->prepare_qties_in_box();
        for ($i = 0; $i < count($all_items_images); $i++) {
            if (in_array($all_items_images[$i]["item_id"], $all_available_qty)) {
                if ($all_items_images[$i]["is_composite"] == 1) {
                    $qty_b = "/" . floatval($qty_in_bx[$all_items_images[$i]["item_id"]]) . " units";
                    $qty = number_format(floatval($boxes_qunatities[$all_items_images[$i]["item_id"]]), 2) . " BOX";
                } else {
                    $qty_b = "";
                    $qty = floatval($all_items_images[$i]["quantity"]) . " ITEMS";
                }
                $selling_price = $all_items_images[$i]["selling_price"];
                if (isset($this->settings_info["gallery_default_price"]) && $this->settings_info["gallery_default_price"] == 2) {
                    $selling_price = $all_items_images[$i]["wholesale_price"];
                }
                if (isset($this->settings_info["gallery_default_price"]) && $this->settings_info["gallery_default_price"] == 3) {
                    $selling_price = $all_items_images[$i]["second_wholesale_price"];
                }
                $cost_price = "";
                if (isset($this->settings_info["show_cost_in_gallery"]) && $this->settings_info["show_cost_in_gallery"] == 1 && isset($this->settings_info["gallery_default_price"]) && $this->settings_info["gallery_default_price"] == 3) {
                    $cost_price = "_<small>" . date("Y") . floatval($all_items_images[$i]["buying_cost"]) . "</small>_";
                }
                array_push($gallery_return["gal"], array("mainpath" => $this->settings_info["main_pictures_path"], "path" => $all_items_images[$i]["name"], "item_id" => $all_items_images[$i]["item_id"], "description" => $all_items_images[$i]["description"], "cur" => $_SESSION["currency_symbol"], "price" => $cost_price . "" . number_format($selling_price, 2), "qty" => $qty, "qty_b" => $qty_b));
            }
        }
        echo json_encode($gallery_return);
    }
    public function test()
    {
        $items = $this->model("items");
    }
    public function add_value_to_price()
    {
        $items = $this->model("items");
        $value = $_POST["amount"];
        $items_ids = $_POST["items"];
        $cost_also = $_POST["cost_also"];
        $items->add_value_to_price($items_ids, $value, $cost_also, $this->settings_info);
        echo json_encode(array());
    }
    public function add_qty_to_all()
    {
        $items = $this->model("items");
        $value = $_POST["amount"];
        $items_ids = $_POST["items"];
        $items->add_qty_to_all($items_ids, $value);
        echo json_encode(array());
    }
    public function set_price_to_all()
    {
        $items = $this->model("items");
        $value = $_POST["amount"];
        $items_ids = $_POST["items"];
        $items->set_price_to_all($items_ids, $value);
        echo json_encode(array());
    }
    public function import_items_of_supplier($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $items_of_supplier = $items->import_items_of_supplier($id);
        echo json_encode($items_of_supplier);
    }
    public function addItemToPI($_item_id, $_pi_id)
    {
        self::giveAccessTo();
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $pi_id = filter_var($_pi_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $id = $items->addItemToPI($item_id, $pi_id);
        echo json_encode(array("id" => $id));
    }
    public function update_upsilon_id()
    {
        $items = $this->model("items");
        $categories = $this->model("categories");
        $items_info = $items->getAllItemsEvenDeleted();
        $categories_info = $categories->getAllParentCategoriesEvenDeleted();
        for ($i = 0; $i < count($items_info); $i++) {
        }
        for ($i = 0; $i < count($categories_info); $i++) {
            $up_id = self::generate_upsilon_id();
        }
    }
    public function get_latest_cost_of_item($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, self::conversion_php_version_filter());
        $stock = $this->model("stock");
        $items = $this->model("items");
        $info = $stock->get_latest_cost_of_item($id);
        $pi_info = array();
        if (0 < count($info)) {
            $pi_info = $stock->getStockInvoicesById($info[0]["receive_stock_invoice_id"]);
        }
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $currencies = array();
        $default = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $currencies[$all_currencies[$i]["id"]] = $all_currencies[$i];
            if ($all_currencies[$i]["system_default"] == 1) {
                $default = $all_currencies[$i];
            }
        }
        $info[0]["cost"] = $info[0]["cost"];
        if (count($pi_info) == 0) {
            $item_info = $items->get_item($id);
            $info[0]["cost"] = $item_info[0]["buying_cost"];
            echo json_encode($info);
        } else {
            echo json_encode($info);
        }
    }
    public function _default()
    {
        self::giveAccessTo();
        $data = array();
        $data["enable_wholasale"] = $this->settings_info["enable_wholasale"];
        $data["usdlbp_rate"] = $this->settings_info["usdlbp_rate"];
        $data["mobile_shop"] = $this->settings_info["mobile_shop"];
        $data["enable_new_multibranches"] = 0;
        if (isset($this->settings_info["enable_new_multibranches"])) {
            $data["enable_new_multibranches"] = $this->settings_info["enable_new_multibranches"];
        }
        $this->view("items", $data);
    }
    public function manual_cost()
    {
        self::giveAccessTo();
        $this->view("manual_cost");
    }
    public function getItemsCategories()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $info = $items->getCategories();
        echo json_encode($info);
    }
    public function checkBarcodeIfExist($_barcode)
    {
        self::giveAccessTo();
        $barcode = filter_var($_barcode, self::conversion_php_version_filter());
        $items = $this->model("items");
        $result = $items->get_item_by_barcode($barcode);
        echo json_encode($result);
    }
    public function checkBarcodeIfExist__($_barcode)
    {
        self::giveAccessTo();
        $barcode = filter_var($_barcode, self::conversion_php_version_filter());
        $items = $this->model("items");
        $result = $items->get_item_by_barcode($barcode);
        if (0 < count($result)) {
            return true;
        }
        return false;
    }
    public function get_item_by_id__($_id)
    {
        self::giveAccessTo();
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $result = $items->get_item($id);
        if (isset($colors_array[$result[0]["color_text_id"]]) && 0 < strlen($colors_array[$result[0]["color_text_id"]])) {
            $result[0]["description"] .= "|" . $colors_array[$result[0]["color_text_id"]];
        }
        if (isset($sizes_array[$result[0]["size_id"]]) && 0 < strlen($sizes_array[$result[0]["size_id"]])) {
            $result[0]["description"] .= "|" . $sizes_array[$result[0]["size_id"]];
        }
        echo json_encode($result);
    }
    public function get_item_by_id_($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $user = $this->model("user");
        $result = $items->get_item($id);
        if ($result[0]["user_id"] != NULL) {
            $info = array();
            $info["id"] = $result[0]["user_id"];
            $user_info = $user->get_user($info);
            $result[0]["user_name"] = $user_info[0]["username"];
        } else {
            $result[0]["user_name"] = -1;
        }
        echo json_encode($result);
    }
    public function get_item_by_id($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $user = $this->model("user");
        $result = $items->get_item_in_group($id);
        if ($result[0]["user_id"] != NULL) {
            $info = array();
            $info["id"] = $result[0]["user_id"];
            $user_info = $user->get_user($info);
            $result[0]["user_name"] = $user_info[0]["username"];
        } else {
            $result[0]["user_name"] = -1;
        }
        echo json_encode($result);
    }
    public function get_items_names_with_boxes()
    {
        self::giveAccessTo(array(2));
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
        $info = array();
        for ($i = 0; $i < count($result); $i++) {
            $info[$i]["id"] = $result[$i]["id"];
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
            $info[$i]["name"] = $result[$i]["description"] . "-" . $result[$i]["barcode"] . "-" . $color_size;
        }
        echo json_encode($info);
    }
    public function get_items_names_without_boxes()
    {
        self::giveAccessTo(array(2));
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $items = $this->model("items");
        $result = $items->get_items_names_without_boxes();
        $info = array();
        for ($i = 0; $i < count($result); $i++) {
            $info[$i]["id"] = $result[$i]["id"];
            if ($result[$i]["barcode"] == NULL) {
                $result[$i]["barcode"] = "";
            }
            if ($result[$i]["second_barcode"] == NULL) {
                $result[$i]["second_barcode"] = "";
            }
            $info[$i]["name"] = $result[$i]["description"];
            if (0 < strlen($result[$i]["id"])) {
                $info[$i]["name"] .= "|" . $result[$i]["id"];
            }
            if (0 < strlen($result[$i]["barcode"])) {
                $info[$i]["name"] .= "|" . $result[$i]["barcode"];
            }
            if (0 < strlen($result[$i]["second_barcode"])) {
                $info[$i]["name"] .= "|" . $result[$i]["second_barcode"];
            }
            if (isset($colors_array[$result[$i]["color_text_id"]]) && 0 < strlen($colors_array[$result[$i]["color_text_id"]])) {
                $info[$i]["name"] .= "|" . $colors_array[$result[$i]["color_text_id"]];
            }
            if (isset($sizes_array[$result[$i]["size_id"]]) && 0 < strlen($sizes_array[$result[$i]["size_id"]])) {
                $info[$i]["name"] .= "|" . $sizes_array[$result[$i]["size_id"]];
            }
        }
        echo json_encode($info);
    }
    public function get_items_names()
    {
        self::giveAccessTo(array(2));
        $items = $this->model("items");
        $result = $items->get_items_names();
        $info = array();
        for ($i = 0; $i < count($result); $i++) {
            $info[$i]["id"] = $result[$i]["id"];
            if ($result[$i]["barcode"] == NULL) {
                $result[$i]["barcode"] = "";
            }
            if ($result[$i]["second_barcode"] == NULL) {
                $result[$i]["second_barcode"] = "";
            }
            $info[$i]["name"] = $result[$i]["description"] . "-" . $result[$i]["id"] . "-" . $result[$i]["barcode"] . "-" . $result[$i]["second_barcode"];
        }
        echo json_encode($info);
    }
    public function re_tr($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer->re_tr($id);
        echo json_encode(array());
    }
    public function get_all_logs($_id, $_store_id)
    {
        self::giveAccessTo();
        $data_array["data"] = array();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $store_id = $_SESSION["store_id"];
        $items = $this->model("items");
        $user = $this->model("user");
        $transfer = $this->model("transfer");
        $store = $this->model("store");
        $stock = $this->model("stock");
        $allrates = $stock->get_all_rate_of_pi_related_to_item_id($_id);
        $allrates_array = array();
        for ($i = 0; $i < count($allrates); $i++) {
            if (floor($allrates[$i]["cur_rate"]) == 1) {
                $allrates_array[$allrates[$i]["id"]] = "";
            } else {
                $allrates_array[$allrates[$i]["id"]] = " Rate: " . self::value_format_custom(floor($allrates[$i]["cur_rate"]), $this->settings_info);
            }
        }
        $stores = $store->getAllStores();
        $stores_array = array();
        for ($i = 0; $i < count($stores); $i++) {
            $stores_array[$stores[$i]["id"]] = $stores[$i]["location"];
        }
        $users_pos = $user->getAllUsersPOSEvenDeleted();
        $users_pos_array = array();
        for ($i = 0; $i < count($users_pos); $i++) {
            array_push($users_pos_array, $users_pos[$i]["id"]);
        }
        $users_info = $user->getAllUsersEvenDeleted();
        $users_info_array = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_info_array[$users_info[$i]["id"]] = $users_info[$i]["username"] . " - " . $users_info[$i]["name"];
        }
        $items_logs = $items->get_all_logs($id, $store_id);
        $transfers = $transfer->get_item_transferred($id);
        if ($transfers == "-1") {
            $data_array["data"] = -1;
            echo json_encode($data_array);
        } else {
            $transfers_info = array();
            for ($i = 0; $i < count($transfers); $i++) {
                $transfers_info[$transfers[$i]["id"]]["from"] = $stores_array[$transfers[$i]["from_store_id"]];
                $transfers_info[$transfers[$i]["id"]]["to"] = $stores_array[$transfers[$i]["to_store_id"]];
            }
            for ($i = 0; $i < count($items_logs); $i++) {
                $tmp = array();
                array_push($tmp, self::log_format($items_logs[$i]["id"]));
                if (strpos($items_logs[$i]["source"], "AGE") !== false) {
                    array_push($tmp, "<b>Shrinkage Set to " . (double) $items_logs[$i]["qty_afer_action"] . " </b>");
                } else {
                    if (strpos($items_logs[$i]["source"], "recharge") !== false) {
                        if (0 < $items_logs[$i]["qty"]) {
                            array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Cancel Recharge Line</span> ");
                        } else {
                            array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Recharge Line</span> ");
                        }
                    } else {
                        if (strpos($items_logs[$i]["source"], "TRANS") !== false) {
                            $transfer_id_tmp = explode("-", $items_logs[$i]["source"]);
                            $transfer_id = (int) $transfer_id_tmp[1];
                            $sr_id = explode("-", $items_logs[$i]["source"]);
                            if (0 < $items_logs[$i]["qty"]) {
                                array_push($tmp, "<b>+" . (double) $items_logs[$i]["qty"] . "</b> <span class='transfer_class'>Transferred From " . $transfers_info[$transfer_id]["from"] . " - (" . $items_logs[$i]["source"] . ")</span>");
                            } else {
                                array_push($tmp, "<b>" . (double) $items_logs[$i]["qty"] . "</b> <span class='transfer_class'>Transferred To " . $transfers_info[$transfer_id]["to"] . " - (" . $items_logs[$i]["source"] . ")</span>");
                            }
                        } else {
                            if (strpos($items_logs[$i]["source"], "DN") !== false) {
                                if (0 < $items_logs[$i]["qty"]) {
                                    if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                        array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Returned By POS - Debit Note " . $items_logs[$i]["source"] . "</span> ");
                                    } else {
                                        array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Added By Admin - Debit Note " . $items_logs[$i]["source"] . "</span> ");
                                    }
                                } else {
                                    if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                        array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Sold</span> ");
                                    } else {
                                        array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Reduced By Admin - Debit Note " . $items_logs[$i]["source"] . "</span>");
                                    }
                                }
                            } else {
                                if (strpos($items_logs[$i]["source"], "CN") !== false) {
                                    if (0 < $items_logs[$i]["qty"]) {
                                        if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                            array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Returned By POS - Credit Note</span> ");
                                        } else {
                                            array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Added By Admin - Credit Note</span> ");
                                        }
                                    } else {
                                        if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                            array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Sold</span> ");
                                        } else {
                                            array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Reduced By Admin - Credit Note</span>");
                                        }
                                    }
                                } else {
                                    if (strpos($items_logs[$i]["source"], "WA") !== false) {
                                        if (0 < $items_logs[$i]["qty"]) {
                                            if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                                array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Wasting canceled By POS</span> ");
                                            } else {
                                                array_push($tmp, "#");
                                            }
                                        } else {
                                            if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                                array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Wasting</span> ");
                                            } else {
                                                array_push($tmp, "#");
                                            }
                                        }
                                    } else {
                                        if (is_numeric($items_logs[$i]["source"])) {
                                            if (0 < $items_logs[$i]["qty"]) {
                                                array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>PI (" . self::idFormat_stockInv($items_logs[$i]["source"]) . ")" . $allrates_array[$items_logs[$i]["source"]] . "</span> ");
                                            } else {
                                                array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>PI (" . self::idFormat_stockInv($items_logs[$i]["source"]) . ")" . $allrates_array[$items_logs[$i]["source"]] . "</span> ");
                                            }
                                        } else {
                                            if (strpos($items_logs[$i]["source"], "POSTRS_C") !== false) {
                                                if (0 < $items_logs[$i]["qty"]) {
                                                    array_push($tmp, "<b>+" . (double) $items_logs[$i]["qty"] . "</b> <span class='added_class'>CANCEL BRANCH TRANSFER</span>");
                                                }
                                            } else {
                                                if (strpos($items_logs[$i]["source"], "POSTRS") !== false) {
                                                    if (0 < $items_logs[$i]["qty"]) {
                                                        array_push($tmp, "<b>+" . (double) $items_logs[$i]["qty"] . "</b> <span class='added_class'>BRANCH TRANSFER</span>");
                                                    } else {
                                                        array_push($tmp, "<b>" . (double) $items_logs[$i]["qty"] . "</b> <span class='reduce_class'>BRANCH TRANSFER</span>");
                                                    }
                                                } else {
                                                    if (strpos($items_logs[$i]["source"], "pack") !== false) {
                                                        if (0 < $items_logs[$i]["qty"]) {
                                                            array_push($tmp, "<b>+" . (double) $items_logs[$i]["qty"] . "</b> <span class='added_class'>PACKING</span>");
                                                        } else {
                                                            array_push($tmp, "<b>" . (double) $items_logs[$i]["qty"] . "</b> <span class='reduce_class'>PACKING</span>");
                                                        }
                                                    } else {
                                                        if (strpos($items_logs[$i]["source"], "soldbyadmin") !== false) {
                                                            if (0 < $items_logs[$i]["qty"]) {
                                                                array_push($tmp, "<b>+" . (double) $items_logs[$i]["qty"] . "</b> <span class='added_class'>Returned By Admin</span>");
                                                            } else {
                                                                array_push($tmp, "<b>" . (double) $items_logs[$i]["qty"] . "</b> <span class='reduce_class'>Sold By Admin</span>");
                                                            }
                                                        } else {
                                                            if (0 < $items_logs[$i]["qty"]) {
                                                                if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                                                    array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Added By Vendor</span> ");
                                                                } else {
                                                                    array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Added By Admin</span> ");
                                                                }
                                                            } else {
                                                                if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                                                    array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Sold</span> ");
                                                                } else {
                                                                    array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Reduced By Admin</span>");
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                array_push($tmp, "<b>" . (double) $items_logs[$i]["qty_afer_action"] . "</b>");
                array_push($tmp, $users_info_array[$items_logs[$i]["user_id"]]);
                array_push($tmp, self::date_time_format_custom($items_logs[$i]["creation_date"]));
                array_push($data_array["data"], $tmp);
            }
            echo json_encode($data_array);
        }
    }
    public function get_variation($_id, $_store_id)
    {
        self::giveAccessTo();
        $stock = $this->model("stock");
        $items = $this->model("items");
        $invoice = $this->model("invoice");
        $data_array["data"] = array();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $all_in_out = array();
        $all_in = $stock->get_all_pi_of_item($id);
        $all_out = $invoice->get_all_invoices_of_item($id);
        $manual_qty = $items->get_manual_qty($id);
        for ($i = 0; $i < count($manual_qty); $i++) {
            if ($manual_qty[$i]["qty"] != 0) {
                array_push($all_in_out, array("timestamp" => strtotime($manual_qty[$i]["creation_date"]), "creation_date" => $manual_qty[$i]["creation_date"], "sp_name" => "<b class='text-success'>MANUAL QTY ADJUSTMENT</b>", "qty" => floor($manual_qty[$i]["qty"]), "pi_id" => "", "cost" => 0, "profit" => 0, "invoice_reference" => "", "discount" => 0, "in" => 0));
            }
        }
        for ($i = 0; $i < count($all_out); $i++) {
            if (0 < $all_out[$i]["qty"]) {
                array_push($all_in_out, array("timestamp" => strtotime($all_out[$i]["creation_date"]), "creation_date" => $all_out[$i]["creation_date"], "sp_name" => "<b class='text-success'>SOLD</b> to <b>" . $all_out[$i]["cname"] . "</b> <b>INV ID</b> (" . $all_out[$i]["invoice_id"] . ")", "qty" => floor($all_out[$i]["qty"]), "pi_id" => "", "cost" => number_format($all_out[$i]["final_price_disc_qty"] / $all_out[$i]["qty"], 2), "profit" => $all_out[$i]["profit"], "invoice_reference" => "", "discount" => number_format(0, 2), "in" => 2));
            }
        }
        for ($i = 0; $i < count($all_in); $i++) {
            if (0 < $all_in[$i]["qty"]) {
                array_push($all_in_out, array("timestamp" => strtotime($all_in[$i]["creation_date"]), "creation_date" => $all_in[$i]["creation_date"], "sp_name" => "<b class='text-danger'>PURSHASED</b> FROM <b>" . $all_in[$i]["sup_name"] . "</b> <b>PI ID</b> (" . $all_in[$i]["pi_id"] . ") <b>PI REF</b> (" . $all_in[$i]["invoice_reference"] . ")", "qty" => floor($all_in[$i]["qty"]), "pi_id" => $all_in[$i]["pi_id"], "cost" => number_format($all_in[$i]["cost"], 2), "profit" => 0, "invoice_reference" => $all_in[$i]["invoice_reference"], 2, "discount" => number_format($all_in[$i]["discount_percentage"], 2), "in" => 1));
            }
        }
        self::__USORT_TIMESTAMP($all_in_out);
        $vqty = 0;
        for ($i = 0; $i < count($all_in_out); $i++) {
            $tmp = array();
            if ($all_in_out[$i]["in"] == 1) {
                $vqty += $all_in_out[$i]["qty"];
            }
            if ($all_in_out[$i]["in"] == 2) {
                $vqty -= $all_in_out[$i]["qty"];
            }
            if ($all_in_out[$i]["in"] == 0) {
                $vqty += $all_in_out[$i]["qty"];
            }
            array_push($tmp, $all_in_out[$i]["creation_date"]);
            array_push($tmp, $all_in_out[$i]["sp_name"]);
            array_push($tmp, $all_in_out[$i]["qty"]);
            array_push($tmp, $vqty);
            array_push($tmp, $all_in_out[$i]["cost"]);
            if (0 < $all_in_out[$i]["profit"]) {
                array_push($tmp, number_format($all_in_out[$i]["profit"], 2));
                array_push($tmp, number_format($all_in_out[$i]["profit"] / $all_in_out[$i]["qty"], 2));
            } else {
                array_push($tmp, "");
                array_push($tmp, "");
            }
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_logs_by_daterange($_daterane)
    {
        self::giveAccessTo();
        $data_array["data"] = array();
        $date = filter_var($_daterane, self::conversion_php_version_filter());
        $store_id = $_SESSION["store_id"];
        $items = $this->model("items");
        $user = $this->model("user");
        $transfer = $this->model("transfer");
        $store = $this->model("store");
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date == "today") {
            $date_range[0] = date("Y-m-d");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $stores = $store->getAllStores();
        $stores_array = array();
        for ($i = 0; $i < count($stores); $i++) {
            $stores_array[$stores[$i]["id"]] = $stores[$i]["location"];
        }
        $users_pos = $user->getAllUsersPOSEvenDeleted();
        $users_pos_array = array();
        for ($i = 0; $i < count($users_pos); $i++) {
            array_push($users_pos_array, $users_pos[$i]["id"]);
        }
        $users_info = $user->getAllUsersEvenDeleted();
        $users_info_array = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_info_array[$users_info[$i]["id"]] = $users_info[$i]["username"] . " - " . $users_info[$i]["name"];
        }
        $items_logs = $items->get_all_logs_by_date_range($date_range);
        for ($i = 0; $i < count($items_logs); $i++) {
            $tmp = array();
            array_push($tmp, self::log_format($items_logs[$i]["id"]));
            array_push($tmp, "#" . $items_logs[$i]["item_id"] . "__" . $items_logs[$i]["description"]);
            if (strpos($items_logs[$i]["source"], "AGE") !== false) {
                array_push($tmp, "<b>Shrinkage Set to " . (double) $items_logs[$i]["qty_afer_action"] . " </b>");
            } else {
                if (strpos($items_logs[$i]["source"], "recharge") !== false) {
                    if (0 < $items_logs[$i]["qty"]) {
                        array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Cancel Recharge Line</span> ");
                    } else {
                        array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Recharge Line</span> ");
                    }
                } else {
                    if (strpos($items_logs[$i]["source"], "TRANS") !== false) {
                        array_push($tmp, "");
                    } else {
                        if (strpos($items_logs[$i]["source"], "DN") !== false) {
                            if (0 < $items_logs[$i]["qty"]) {
                                if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                    array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Returned By POS - Debit Note " . $items_logs[$i]["source"] . "</span> ");
                                } else {
                                    array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Added By Admin - Debit Note " . $items_logs[$i]["source"] . "</span> ");
                                }
                            } else {
                                if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                    array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Sold</span> ");
                                } else {
                                    array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Reduced By Admin - Debit Note " . $items_logs[$i]["source"] . "</span>");
                                }
                            }
                        } else {
                            if (strpos($items_logs[$i]["source"], "CN") !== false) {
                                if (0 < $items_logs[$i]["qty"]) {
                                    if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                        array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Returned By POS - Credit Note</span> ");
                                    } else {
                                        array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Added By Admin - Credit Note</span> ");
                                    }
                                } else {
                                    if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                        array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Sold</span> ");
                                    } else {
                                        array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Reduced By Admin - Credit Note</span>");
                                    }
                                }
                            } else {
                                if (strpos($items_logs[$i]["source"], "WA") !== false) {
                                    if (0 < $items_logs[$i]["qty"]) {
                                        if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                            array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Wasting canceled By POS</span> ");
                                        } else {
                                            array_push($tmp, "#");
                                        }
                                    } else {
                                        if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                            array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Wasting</span> ");
                                        } else {
                                            array_push($tmp, "#");
                                        }
                                    }
                                } else {
                                    if (is_numeric($items_logs[$i]["source"])) {
                                        if (0 < $items_logs[$i]["qty"]) {
                                            array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>PI (" . self::idFormat_stockInv($items_logs[$i]["source"]) . ")</span> ");
                                        } else {
                                            array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>PI (" . self::idFormat_stockInv($items_logs[$i]["source"]) . ")</span> ");
                                        }
                                    } else {
                                        if (0 < $items_logs[$i]["qty"]) {
                                            if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                                array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Returned By POS</span> ");
                                            } else {
                                                array_push($tmp, "<b>+" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Added By Admin</span> ");
                                            }
                                        } else {
                                            if (in_array($items_logs[$i]["user_id"], $users_pos_array)) {
                                                array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='reduce_class'>Sold</span> ");
                                            } else {
                                                array_push($tmp, "<b>-" . abs((double) $items_logs[$i]["qty"]) . "</b> <span class='added_class'>Reduced By Admin</span>");
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            array_push($tmp, "<b>" . (double) $items_logs[$i]["qty_afer_action"] . "</b>");
            array_push($tmp, $users_info_array[$items_logs[$i]["user_id"]]);
            array_push($tmp, self::date_time_format_custom($items_logs[$i]["creation_date"]));
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function _rvdelete($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $result = $items->_rvdelete($id);
        echo json_encode(array());
    }
    public function _vdelete($_id)
    {
        self::giveAccessTo();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $result = $items->_vdelete($id);
        echo json_encode(array());
    }
    public function get_items_barcodes()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $result = $items->get_items_barcodes();
        echo json_encode($result);
    }
    public function generate_upc_checkdigit($upc_code)
    {
        $odd_total = 0;
        $even_total = 0;
        for ($i = 0; $i < 11; $i++) {
            if (($i + 1) % 2 == 0) {
                $even_total += $upc_code[$i];
            } else {
                $odd_total += $upc_code[$i];
            }
        }
        $sum = 3 * $odd_total + $even_total;
        $check_digit = $sum % 10;
        return 0 < $check_digit ? 10 - $check_digit : $check_digit;
    }
    public function _generateBarcode()
    {
        self::giveAccessTo();
        $barcode = $this->model("barcode");
        $filepath = isset($_GET["filepath"]) ? $_GET["filepath"] : "barcodes/";
        $text = isset($_GET["text"]) ? $_GET["text"] : "abc";
        $size = isset($_GET["size"]) ? $_GET["size"] : "20";
        $orientation = isset($_GET["orientation"]) ? $_GET["orientation"] : "horizontal";
        $code_type = isset($_GET["codetype"]) ? $_GET["codetype"] : "code128";
        $print = true;
        $sizefactor = isset($_GET["sizefactor"]) ? $_GET["sizefactor"] : "1";
        $barcode->generate_barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor);
    }
    public function ____print_barcode($_item_id, $_number_to_print)
    {
        $items = $this->model("items");
        $settings = $this->model("settings");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $number_to_print = filter_var($_number_to_print, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($item_id);
        $barcode_settings = $settings->get_barcode_settings();
        $main_root = self::get_main_root();
        if (file_exists($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg")) {
            unlink($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg");
        }
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
        $barcode_key[0]["mid"] = $item_info[0]["barcode"];
        if (strlen($barcode_key[0]["mid"]) < 4) {
            $barcode_key[0]["mid"] = sprintf("%04d", $barcode_key[0]["mid"]);
        }
        include "application/mvc/models/BarcodeGenerator.php";
        $generator = new barcodeGenerator();
        $options = array();
        $image = $generator->output_image("jpg", $barcode_settings_info["type"], $barcode_key[0]["mid"], $options);
        $cmd = $main_root . "/tools/barcodes \"" . $this->settings_info_local["printer_barcode_name"] . "\" \"" . $main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg\" " . $number_to_print . " \"" . $this->settings_info_local["barcode_page_size_name"] . "\" ";
    }
    public function create_and_print_barcode()
    {
        include "application/mvc/models/BarcodeGenerator.php";
        $generator = new barcodeGenerator();
        $options = array();
        $options["w"] = 500;
        $options["h"] = 300;
        $options["wm"] = 3;
        $options["ww"] = 3;
        header("Content-Type: image/jgeg");
        $im = @imagecreate(600, 309) or exit("Cannot Initialize new GD image stream");
        $background_color = imagecolorallocate($im, 255, 255, 255);
        $text_color = imagecolorallocate($im, 0, 0, 0);
        imagettftext($im, 40, 0, 20, 50, $text_color, "Oswald-Light.ttf", "Hello");
        $main_root = self::get_main_root();
        imagejpeg($im, $main_root . "/barcodes/text.jpg");
        imagedestroy($im);
        $dest = imagecreatefromjpeg($main_root . "/barcodes/text.jpg");
        $src = imagecreatefromjpeg($main_root . "/barcodes/123456789.jpg");
        imagecopymerge($dest, $src, 10, 50, 0, 0, 500, 300, 75);
        header("Content-Type: image/jpeg");
        imagejpeg($dest, $main_root . "/barcodes/merged.jpg");
        imagedestroy($dest);
        imagedestroy($src);
    }
    public function print_barcode_of_transfer_id($_transfer_id)
    {
        self::giveAccessTo();
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer_info = $transfer->getAllItemsInTransferDetails($transfer_id);
        for ($i = 0; $i < count($transfer_info); $i++) {
        }
        echo json_encode(array());
    }
    public function print_barcode_of_transfer_id_manual($_transfer_id)
    {
        self::giveAccessTo();
        $transfer_id = filter_var($_transfer_id, FILTER_SANITIZE_NUMBER_INT);
        $transfer = $this->model("transfer");
        $transfer_info = $transfer->getAllItemsInTransferDetails($transfer_id);
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $items = $this->model("items");
        $settings = $this->model("settings");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $item_id = 102568;
        $barcode_settings = $settings->get_barcode_local_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
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
        $data = array();
        $items_array = array();
        for ($i = 0; $i < count($transfer_info); $i++) {
            for ($k = 0; $k < $transfer_info[$i]["qty"]; $k++) {
                array_push($items_array, $transfer_info[$i]["item_id"]);
            }
        }
        for ($i = 0; $i < count($items_array); $i++) {
            $item_info = $items->get_item($items_array[$i]);
            $barcode_key[0]["mid"] = $item_info[0]["barcode"];
            if (strlen($barcode_key[0]["mid"]) < 5) {
                $barcode_key[0]["mid"] = sprintf("%05s", $barcode_key[0]["mid"]);
            }
            $it_desc = $item_info[0]["description"];
            if ($item_info[0]["item_alias"] != NULL && $item_info[0]["item_alias"] != "" && $item_info[0]["item_alias"] != "null") {
                $it_desc = $item_info[0]["item_alias"];
                if ($barcode_settings_info["description_max_size"] < strlen($item_info[0]["item_alias"])) {
                    $it_desc = substr($item_info[0]["item_alias"], 0, $barcode_settings_info["description_max_size"]) . " ...";
                }
            } else {
                if ($barcode_settings_info["description_max_size"] < strlen($item_info[0]["description"])) {
                    $it_desc = substr($item_info[0]["description"], 0, $barcode_settings_info["description_max_size"]) . " ...";
                }
            }
            if (in_array($item_info[0]["id"], $discounts_items_ids)) {
                $item_info[0]["discount"] = $discounts_items_discount[$item_info[0]["id"]];
            }
            $new_price = self::value_format_custom($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), $this->settings_info);
            $data[$i]["item_barcode"] = $barcode_key[0]["mid"];
            $data[$i]["item_enable"] = $barcode_settings_info["description_enable"];
            $data[$i]["item_left"] = $barcode_settings_info["description_x"];
            $data[$i]["item_top"] = $barcode_settings_info["description_y"];
            $data[$i]["item_size"] = $barcode_settings_info["description_size"];
            $data[$i]["item_description"] = $it_desc;
            $data[$i]["barcode_type"] = $this->settings_info["barcode_type"];
            $data[$i]["barcode_left"] = $barcode_settings_info["barcode_position_x"];
            $data[$i]["barcode_top"] = $barcode_settings_info["barcode_position_y"];
            $data[$i]["store_name_enable"] = $barcode_settings_info["store_name_enable"];
            $data[$i]["store_name_left"] = $barcode_settings_info["store_name_x"];
            $data[$i]["store_name_right"] = $barcode_settings_info["store_name_y"];
            $data[$i]["store_name_font_size"] = $barcode_settings_info["store_name_font_size"];
            $data[$i]["store_name"] = $this->settings_info_local["shop_name"];
            $data[$i]["price_enable"] = $barcode_settings_info["price_enable"];
            $data[$i]["price_left"] = $barcode_settings_info["price_x"];
            $data[$i]["price_top"] = $barcode_settings_info["price_y"];
            $data[$i]["price_font_size"] = $barcode_settings_info["price_font_size"];
            $data[$i]["price"] = self::value_format_custom($item_info[0]["selling_price"], $this->settings_info);
            $data[$i]["size_enable"] = $barcode_settings_info["size_enable"];
            $data[$i]["size_x"] = $barcode_settings_info["size_x"];
            $data[$i]["size_y"] = $barcode_settings_info["size_y"];
            $data[$i]["size_font_size"] = $barcode_settings_info["size_font_size"];
            $data[$i]["size_id"] = $sizes_array[$item_info[0]["size_id"]];
            $data[$i]["color_enable"] = $barcode_settings_info["color_enable"];
            $data[$i]["color_x"] = $barcode_settings_info["color_x"];
            $data[$i]["color_y"] = $barcode_settings_info["color_y"];
            $data[$i]["color_font_size"] = $barcode_settings_info["color_font_size"];
            $data[$i]["color_text_id"] = $colors_array[$item_info[0]["color_text_id"]];
            $data[$i]["discount_enable"] = $barcode_settings_info["discount_enable"];
            $data[$i]["discount_x"] = $barcode_settings_info["discount_x"];
            $data[$i]["discount_y"] = $barcode_settings_info["discount_y"];
            $data[$i]["discount_font_size"] = $barcode_settings_info["discount_font_size"];
            $data[$i]["discount"] = round($item_info[0]["discount"], 0) . "%";
            $data[$i]["price_after_discount_x"] = $barcode_settings_info["price_after_discount_x"];
            $data[$i]["price_after_discount_y"] = $barcode_settings_info["price_after_discount_y"];
            $data[$i]["price_after_discount_size"] = $barcode_settings_info["price_after_discount_size"];
            $data[$i]["final_price"] = $new_price;
            $data[$i]["default_currency_symbol"] = $this->settings_info["default_currency_symbol"];
            $data[$i]["print_barcode_in_browser_paper_width"] = $this->settings_info["print_barcode_in_browser_paper_width"];
            $data[$i]["print_barcode_in_browser_paper_height"] = $this->settings_info["print_barcode_in_browser_paper_height"];
            $data[$i]["sku_code"] = $item_info[0]["sku_code"];
            $data[$i]["enable_sku"] = $barcode_settings_info["enable_sku"];
            $data[$i]["sku_x"] = $barcode_settings_info["sku_x"];
            $data[$i]["sku_y"] = $barcode_settings_info["sku_y"];
            $data[$i]["sku_font_size"] = $barcode_settings_info["sku_font_size"];
        }
        $this->view("print_templates/barcodes/print_bulk_barcode", $data);
    }
    public function print_barcode_by_barcode($_barcode)
    {
        $items = $this->model("items");
        $info = $items->get_item_by_barcode($_barcode);
        self::print_barcode_using_exe($info[0]["id"], 1, 0);
    }
    public function print_bcode()
    {
        for ($i = 550; $i < 600; $i++) {
            $barcode_key = 100 . "MY" . (1000 + $i);
            if (strlen($barcode_key) < 5) {
                $barcode_key = sprintf("%05s", $barcode_key);
            }
            self::print_barcode($barcode_key, $nb = 1);
        }
    }
    public function print_barcode($_item_id, $nb = 1, $new_price = 0)
    {
        if ($new_price == NULL || $new_price == "") {
            $new_price = 0;
        }
        self::print_barcode_using_exe($_item_id, $nb, $new_price, $new_price);
    }
    public function print_barcode_group($_group_id, $nb = 1)
    {
        $items = $this->model("items");
        $item_info = $items->get_items_by_group($_group_id);
        self::print_barcode_using_exe($item_info[0]["id"], $nb, 0);
    }
    public function print_barcode_using_windows_print($_item_id, $with_stock, $with_grp_and_qties)
    {
        $data = array();
        $main_root = self::get_main_root();
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $items = $this->model("items");
        $settings = $this->model("settings");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $item_id = filter_var($_item_id, self::conversion_php_version_filter());
        $number_to_print = filter_var($_number_to_print, FILTER_SANITIZE_NUMBER_INT);
        $items_to_print = explode(",", $item_id);

        $data["items_to_print"] = array();
        for ($i = 0; $i < count($items_to_print); $i++) {
            if ($with_grp_and_qties == 1) {
                $item_info_tmp = $items->get_item($items_to_print[$i]);
                $grps = $items->get_items_by_group($item_info_tmp[0]["item_group"]);
                for ($g = 0; $g < count($grps); $g++) {
                    array_push($data["items_to_print"], $grps[$g]["id"]);
                }
            } else {
                array_push($data["items_to_print"], $items_to_print[$i]);
            }
        }

        $barcode_settings = $settings->get_barcode_local_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
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
        $item_info = $items->get_item_array($data["items_to_print"]);
        $item_info_stock = $items->get_stock_array($data["items_to_print"]);
        $item_info_stock_array = array();
        for ($i = 0; $i < count($item_info_stock); $i++) {
            if (0 < $item_info_stock[$i]["quantity"]) {
                $item_info_stock_array[$item_info_stock[$i]["item_id"]] = $item_info_stock[$i]["quantity"];
            } else {
                $item_info_stock_array[$item_info_stock[$i]["item_id"]] = 0;
            }
        }
        $data["items_to_print_details"] = array();

        for ($i = 0; $i < count($item_info); $i++) {
            $it_desc = $item_info[$i]["description"];
            if ($item_info[$i]["item_alias"] != NULL && $item_info[$i]["item_alias"] != "" && $item_info[$i]["item_alias"] != "null") {
                $it_desc = $item_info[$i]["item_alias"];
                if ($barcode_settings_info["description_max_size"] < strlen($item_info[$i]["item_alias"])) {
                    $it_desc = substr($item_info[$i]["item_alias"], 0, $barcode_settings_info["description_max_size"]) . " ...";
                }
            } else {
                if ($barcode_settings_info["description_max_size"] < strlen($item_info[$i]["description"])) {
                    $it_desc = substr($item_info[$i]["description"], 0, $barcode_settings_info["description_max_size"]) . " ...";
                }
            }
            if (in_array($item_info[$i]["id"], $discounts_items_ids)) {
                $item_info[$i]["discount"] = $discounts_items_discount[$item_info[$i]["id"]];
                if ($this->settings_info["discount_by_group_force_round"] == 1) {
                    $item_info[$i]["discount"] = $discounts_items_discount[$item_info[$i]["id"]];
                    $initial_price_is = $item_info[$i]["selling_price"];
                    $final_price_will_be = (int) ($item_info[$i]["selling_price"] * (1 - $item_info[$i]["discount"] / 100));
                    $discountValue = $initial_price_is - $final_price_will_be;
                    $item_info[$i]["discount"] = $discountValue / $initial_price_is * 100;
                }
            }
            $new_price = self::value_format_custom($item_info[$i]["selling_price"] * (1 - $item_info[$i]["discount"] / 100), $this->settings_info);
            $data["final_price"] = $new_price;
            $barcode_key[$i]["mid"] = $item_info[$i]["barcode"];
            if (strlen($barcode_key[$i]["mid"]) < 5) {
                $barcode_key[$i]["mid"] = sprintf("%05s", $barcode_key[$i]["mid"]);
            }
            $price = self::value_format_custom($item_info[$i]["selling_price"], $this->settings_info);
            $size_id = $sizes_array[$item_info[$i]["size_id"]];
            $color = $colors_array[$item_info[$i]["color_text_id"]];
            $discount_enable = $barcode_settings_info["discount_enable"];
            $discount = round($item_info[$i]["discount"], 0) . "%";
            if ($item_info[$i]["discount"] == 0) {
                $discount_enable = 0;
            }
            if ($discount_enable == 1 && $discount == 0) {
                $discount_enable = 0;
            }
            $sku_code = $item_info[0]["sku_code"];

            if (isset($item_info_stock_array[$item_info[$i]["id"]]) && $with_stock == 1) {
                for ($k = 0; $k < $item_info_stock_array[$item_info[$i]["id"]]; $k++) {
                    array_push($data["items_to_print_details"], array("description" => $it_desc, "final_price" => $new_price, "barcode" => $barcode_key[$i]["mid"], "price" => $price, "size" => $size_id, "color" => $color, "discount" => $discount, "enable_discount" => $discount_enable, "sku" => $sku_code));
                }
            } else {
                array_push($data["items_to_print_details"], array("description" => $it_desc, "final_price" => $new_price, "barcode" => $barcode_key[$i]["mid"], "price" => $price, "size" => $size_id, "color" => $color, "discount" => $discount, "enable_discount" => $discount_enable, "sku" => $sku_code));
            }
        }
        $data["item_enable"] = $barcode_settings_info["description_enable"];
        $data["item_left"] = $barcode_settings_info["description_x"];
        $data["item_top"] = $barcode_settings_info["description_y"];
        $data["item_size"] = $barcode_settings_info["description_size"];
        $data["item_description"] = $it_desc;
        $data["barcode_enable"] = $barcode_settings_info["barcode_enable"];
        $data["barcode_type"] = $this->settings_info["barcode_type"];
        $data["barcode_left"] = $barcode_settings_info["barcode_position_x"];
        $data["barcode_top"] = $barcode_settings_info["barcode_position_y"];
        $data["store_name_enable"] = $barcode_settings_info["store_name_enable"];
        $data["store_name_left"] = $barcode_settings_info["store_name_x"];
        $data["store_name_right"] = $barcode_settings_info["store_name_y"];
        $data["store_name_font_size"] = $barcode_settings_info["store_name_font_size"];
        $data["store_name"] = $this->settings_info_local["shop_name"];
        $data["price_enable"] = $barcode_settings_info["price_enable"];
        $data["price_left"] = $barcode_settings_info["price_x"];
        $data["price_top"] = $barcode_settings_info["price_y"];
        $data["price_font_size"] = $barcode_settings_info["price_font_size"];
        $data["size_enable"] = $barcode_settings_info["size_enable"];
        $data["size_x"] = $barcode_settings_info["size_x"];
        $data["size_y"] = $barcode_settings_info["size_y"];
        $data["size_font_size"] = $barcode_settings_info["size_font_size"];
        $data["color_enable"] = $barcode_settings_info["color_enable"];
        $data["color_x"] = $barcode_settings_info["color_x"];
        $data["color_y"] = $barcode_settings_info["color_y"];
        $data["color_font_size"] = $barcode_settings_info["color_font_size"];
        $data["discount_x"] = $barcode_settings_info["discount_x"];
        $data["discount_y"] = $barcode_settings_info["discount_y"];
        $data["discount_font_size"] = $barcode_settings_info["discount_font_size"];
        $data["price_after_discount_x"] = $barcode_settings_info["price_after_discount_x"];
        $data["price_after_discount_y"] = $barcode_settings_info["price_after_discount_y"];
        $data["price_after_discount_size"] = $barcode_settings_info["price_after_discount_size"];
        $data["default_currency_symbol"] = $this->settings_info["default_currency_symbol"];
        $data["print_barcode_in_browser_paper_width"] = $this->settings_info["print_barcode_in_browser_paper_width"];
        $data["print_barcode_in_browser_paper_height"] = $this->settings_info["print_barcode_in_browser_paper_height"];
        $data["enable_sku"] = $barcode_settings_info["enable_sku"];
        $data["sku_x"] = $barcode_settings_info["sku_x"];
        $data["sku_y"] = $barcode_settings_info["sku_y"];
        $data["sku"] = $sku_code;
        $data["sku_font_size"] = $barcode_settings_info["sku_font_size"];
        $this->view("print_templates/barcodes/bc", $data);
    }
    public function print_barcode_using_exe($_item_id, $_number_to_print, $new_price_manual)
    {
        $main_root = self::get_main_root();
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $items = $this->model("items");
        $settings = $this->model("settings");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $number_to_print = filter_var($_number_to_print, FILTER_SANITIZE_NUMBER_INT);
        $barcode_settings = $settings->get_barcode_local_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
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
        $full_path = $main_root . "/tools/barcode_label/";
        $barcode_page_size_name = $this->settings_info_local["barcode_page_size_name"];
        $printer_name = $this->settings_info_local["printer_barcode_name"];
        $store_name_chaine = $barcode_settings_info["store_name_enable"] . "#" . $barcode_settings_info["store_name_x"] . "#" . $barcode_settings_info["store_name_y"] . "#" . $barcode_settings_info["store_name_font_size"] . "#" . $this->settings_info_local["shop_name"];
        $item_info = $items->get_item($item_id);
        $it_desc = $item_info[0]["description"];
        if ($item_info[0]["item_alias"] != NULL && $item_info[0]["item_alias"] != "" && $item_info[0]["item_alias"] != "null") {
            $it_desc = $item_info[0]["item_alias"];
            if ($barcode_settings_info["description_max_size"] < strlen($item_info[0]["item_alias"])) {
                $it_desc = substr($item_info[0]["item_alias"], 0, $barcode_settings_info["description_max_size"]) . " ...";
            }
        } else {
            if ($barcode_settings_info["description_max_size"] < strlen($item_info[0]["description"])) {
                $it_desc = substr($item_info[0]["description"], 0, $barcode_settings_info["description_max_size"]) . " ...";
            }
        }
        if (in_array($item_info[0]["id"], $discounts_items_ids)) {
            $item_info[0]["discount"] = $discounts_items_discount[$item_info[0]["id"]];
        }
        $new_price = self::value_format_custom($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), $this->settings_info);
        if (0 < $new_price_manual) {
            $item_info[0]["selling_price"] = self::value_format_custom_no_currency($new_price_manual, $this->settings_info);
        } else {
            $item_info[0]["selling_price"] = self::value_format_custom_no_currency($item_info[0]["selling_price"], $this->settings_info) . " " . $this->settings_info["default_currency_symbol"];
        }
        $description_chaine = $barcode_settings_info["description_enable"] . "#" . $barcode_settings_info["description_x"] . "#" . $barcode_settings_info["description_y"] . "#" . $barcode_settings_info["description_size"] . "#" . $it_desc;
        $original_price_chaine = $barcode_settings_info["price_enable"] . "#" . $barcode_settings_info["price_x"] . "#" . $barcode_settings_info["price_y"] . "#" . $barcode_settings_info["price_font_size"] . "#Price: " . $item_info[0]["selling_price"];
        $discount_chaine = $barcode_settings_info["discount_enable"] . "#" . $barcode_settings_info["discount_x"] . "#" . $barcode_settings_info["discount_y"] . "#" . $barcode_settings_info["discount_font_size"] . "#" . round($item_info[0]["discount"], 0);
        $discounted_price_chaine = $barcode_settings_info["discount_enable"] . "#" . $barcode_settings_info["price_after_discount_x"] . "#" . $barcode_settings_info["price_after_discount_y"] . "#" . $barcode_settings_info["price_after_discount_size"] . "#New Price: " . $new_price;
        $size_chaine = $barcode_settings_info["size_enable"] . "#" . $barcode_settings_info["size_x"] . "#" . $barcode_settings_info["size_y"] . "#" . $barcode_settings_info["size_font_size"] . "#SIZE: " . $sizes_array[$item_info[0]["size_id"]];
        $color_chaine = $barcode_settings_info["color_enable"] . "#" . $barcode_settings_info["color_x"] . "#" . $barcode_settings_info["color_y"] . "#" . $barcode_settings_info["color_font_size"] . "#COLOR: " . $colors_array[$item_info[0]["color_text_id"]];
        if (file_exists($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg")) {
            unlink($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg");
        }
        $barcode_key[0]["mid"] = $item_info[0]["barcode"];
        if (strlen($barcode_key[0]["mid"]) < 5) {
            $barcode_key[0]["mid"] = sprintf("%05s", $barcode_key[0]["mid"]);
        }
        require_once "application/mvc/models/BarcodeGenerator.php";
        $generator = new barcodeGenerator();
        $options = array();
        if ($barcode_settings_info["h"] != -1) {
            $options["h"] = $barcode_settings_info["h"];
        }
        if ($barcode_settings_info["w"] != -1) {
            $options["w"] = $barcode_settings_info["w"];
        }
        if ($barcode_settings_info["wm"] != -1) {
            $options["wm"] = $barcode_settings_info["wm"];
        }
        if ($barcode_settings_info["ww"] != -1) {
            $options["ww"] = $barcode_settings_info["ww"];
        }
        if ($barcode_settings_info["wq"] != -1) {
            $options["wq"] = $barcode_settings_info["wq"];
        }
        if ($barcode_settings_info["wn"] != -1) {
            $options["wn"] = $barcode_settings_info["wn"];
        }
        if ($barcode_settings_info["th"] != -1) {
            $options["th"] = $barcode_settings_info["th"];
        }
        if ($barcode_settings_info["ts"] != -1) {
            $options["ts"] = $barcode_settings_info["ts"];
        }
        if ($barcode_settings_info["pt"] != -1) {
            $options["pt"] = $barcode_settings_info["pt"];
        }
        if ($barcode_settings_info["pb"] != -1) {
            $options["pb"] = $barcode_settings_info["pb"];
        }
        if ($barcode_settings_info["pl"] != -1) {
            $options["pl"] = $barcode_settings_info["pl"];
        }
        if ($barcode_settings_info["pr"] != -1) {
            $options["pr"] = $barcode_settings_info["pr"];
        }
        if ($barcode_settings_info["p"] != -1) {
            $options["p"] = $barcode_settings_info["p"];
        }
        $image = $generator->output_image("jpg", $barcode_settings_info["type"], $barcode_key[0]["mid"], $options);
        $cmd = $main_root . "/tools/barcode_label/LabelPrinter \"" . $full_path . "\" \"" . $barcode_page_size_name . "\" \"" . $printer_name . "\" \"" . $store_name_chaine . "\" \"" . $description_chaine . "\" \"" . $original_price_chaine . "\" \"" . $discount_chaine . "\" \"" . $discounted_price_chaine . "\" \"" . $number_to_print . "\" \"" . $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg\" \"" . $barcode_settings_info["barcode_position_x"] . "\" \"" . $barcode_settings_info["barcode_position_y"] . "\" \"" . $size_chaine . "\" \"" . $color_chaine . "\"";
        exec($cmd, $output, $result);
    }
    public function print_barcodeworking($_item_id, $_number_to_print)
    {
        $items = $this->model("items");
        $settings = $this->model("settings");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $number_to_print = filter_var($_number_to_print, FILTER_SANITIZE_NUMBER_INT);
        $error = 0;
        $item_info = $items->get_item($item_id);
        $barcode_settings = $settings->get_barcode_local_settings();
        $main_root = self::get_main_root();
        if (file_exists($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg")) {
            unlink($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg");
        }
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
        $barcode_key[0]["mid"] = $item_info[0]["barcode"];
        if (strlen($barcode_key[0]["mid"]) < 4) {
            $barcode_key[0]["mid"] = sprintf("%04s", $barcode_key[0]["mid"]);
        }
        include "application/mvc/models/BarcodeGenerator.php";
        $generator = new barcodeGenerator();
        $options = array();
        if ($barcode_settings_info["h"] != -1) {
            $options["h"] = $barcode_settings_info["h"];
        }
        if ($barcode_settings_info["w"] != -1) {
            $options["w"] = $barcode_settings_info["w"];
        }
        if ($barcode_settings_info["wm"] != -1) {
            $options["wm"] = $barcode_settings_info["wm"];
        }
        if ($barcode_settings_info["ww"] != -1) {
            $options["ww"] = $barcode_settings_info["ww"];
        }
        if ($barcode_settings_info["wq"] != -1) {
            $options["wq"] = $barcode_settings_info["wq"];
        }
        if ($barcode_settings_info["wn"] != -1) {
            $options["wn"] = $barcode_settings_info["wn"];
        }
        if ($barcode_settings_info["th"] != -1) {
            $options["th"] = $barcode_settings_info["th"];
        }
        if ($barcode_settings_info["ts"] != -1) {
            $options["ts"] = $barcode_settings_info["ts"];
        }
        if ($barcode_settings_info["pt"] != -1) {
            $options["pt"] = $barcode_settings_info["pt"];
        }
        if ($barcode_settings_info["pb"] != -1) {
            $options["pb"] = $barcode_settings_info["pb"];
        }
        if ($barcode_settings_info["pl"] != -1) {
            $options["pl"] = $barcode_settings_info["pl"];
        }
        if ($barcode_settings_info["pr"] != -1) {
            $options["pr"] = $barcode_settings_info["pr"];
        }
        if ($barcode_settings_info["p"] != -1) {
            $options["p"] = $barcode_settings_info["p"];
        }
        $image = $generator->output_image("jpg", $barcode_settings_info["type"], $barcode_key[0]["mid"], $options);
        header("Content-type: image/jpeg");
        $jpg_image = imagecreatefromjpeg($main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg");
        $black = imagecolorallocate($jpg_image, 0, 0, 0);
        $font_path = "fonts/Oswald-Light.ttf";
        if ($barcode_settings_info["store_name_enable"]) {
            imagestring($jpg_image, $barcode_settings_info["store_name_font_size"], $barcode_settings_info["store_name_x"], $barcode_settings_info["store_name_y"], $this->settings_info["shop_name"], $black);
        }
        if ($barcode_settings_info["price_enable"]) {
            $before = "";
            if (0 < $item_info[0]["discount"]) {
                $before = "Before ";
            }
            imagestring($jpg_image, $barcode_settings_info["price_font_size"], $barcode_settings_info["price_x"], $barcode_settings_info["price_y"], $before . "Price " . number_format(floor($item_info[0]["selling_price"])) . " " . $this->settings_info["default_currency_symbol"], $black);
            if (0 < $item_info[0]["discount"]) {
                imagestring($jpg_image, $barcode_settings_info["price_font_size"], $barcode_settings_info["price_x"], $barcode_settings_info["price_y"] + 15, "Discount: " . floor($item_info[0]["discount"]) . " %", $black);
            }
            if (0 < $item_info[0]["discount"]) {
                imagestring($jpg_image, $barcode_settings_info["price_font_size"], $barcode_settings_info["price_x"], $barcode_settings_info["price_y"] + 30, "After Price " . number_format(round($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), 0), 2) . " " . $this->settings_info["default_currency_symbol"], $black);
                imageline($jpg_image, $barcode_settings_info["price_x"], $barcode_settings_info["price_y"] + 8, $barcode_settings_info["price_x"] + 150, $barcode_settings_info["price_y"] + 8, $black);
            }
        }
        if ($barcode_settings_info["id_enable"]) {
            $it_id = $item_info[0]["id"];
            if (strlen($item_info[0]["id"]) < 4) {
                $it_id = sprintf("%04d", $item_info[0]["id"]);
            }
            imagestring($jpg_image, $barcode_settings_info["id_font_size"], $barcode_settings_info["id_x"], $barcode_settings_info["id_y"], "ID: " . $it_id, $black);
        }
        $it_desc = $item_info[0]["description"];
        if ($item_info[0]["item_alias"] != NULL) {
            $it_desc = $item_info[0]["item_alias"];
        } else {
            if (30 < strlen($item_info[0]["description"])) {
                $it_desc = substr($item_info[0]["description"], 0, 30) . " ...";
            }
        }
        if ($barcode_settings_info["description_enable"]) {
            imagestring($jpg_image, $barcode_settings_info["description_size"], $barcode_settings_info["description_x"], $barcode_settings_info["description_y"], $it_desc, $black);
        }
        imagejpeg($jpg_image, $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg");
        imagedestroy($jpg_image);
        $cmd = $main_root . "/tools/barcodes \"" . $this->settings_info_local["printer_barcode_name"] . "\" \"" . $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg\" " . $number_to_print . " \"" . $this->settings_info_local["barcode_page_size_name"] . "\" ";
        exec($cmd, $output, $result);
        echo json_encode(array($error));
    }
    public function print_barcode_using_php($img_path)
    {
        $handle = printer_open("" . $this->settings_info_local["printer_barcode_name"]);
        printer_start_doc($handle, "Barcode");
        printer_start_page($handle);
        printer_draw_bmp($handle, $img_path, 1, 1);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
    }
    public function generateBarcode($exist)
    {
        $items = $this->model("items");
        $settings = $this->model("settings");
        $barcode_key = $items->getMaxItemId();
        $barcode_settings = $settings->get_barcode_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
        if ($this->settings_info["barcode_type"] == "ean13" && $this->settings_info["print_barcode_in_browser"] == "1") {
            $barcode_key[0]["mid"] = self::generateEAN13(time());
        } else {
            if ($this->settings_info["barcode_type"] == "upc" && $this->settings_info["print_barcode_in_browser"] == "1") {
                if ($exist == 0) {
                    $upc = str_pad((int) $barcode_key[0]["mid"] + 1, 11, "1", STR_PAD_LEFT);
                    $barcode_key[0]["mid"] = self::UPCAbarcode($upc);
                } else {
                    $upc = str_pad((int) $exist, 11, "1", STR_PAD_LEFT);
                    $barcode_key[0]["mid"] = self::UPCAbarcode($upc);
                }
            } else {
                if ($this->settings_info["barcode_type"] == "CODE128C" && $this->settings_info["print_barcode_in_browser"] == "1") {
                    if ($exist == 0) {
                        $barcode_key[0]["mid"] = $this->settings_info["starting_barcode"] . "" . str_pad((int) $barcode_key[0]["mid"] + 1, 7, "0", STR_PAD_LEFT);
                    } else {
                        $barcode_key[0]["mid"] = $this->settings_info["starting_barcode"] . "" . str_pad((int) $exist, 7, "0", STR_PAD_LEFT);
                    }
                    while (self::checkBarcodeIfExist__($barcode_key[0]["mid"])) {
                        $barcode_key[0]["mid"] += 1;
                    }
                } else {
                    if ($exist == 0) {
                        $barcode_key[0]["mid"] = (int) $barcode_key[0]["mid"] + 1;
                    } else {
                        $barcode_key[0]["mid"] = (int) $exist;
                    }
                }
            }
        }
        if (strlen($barcode_key[0]["mid"]) < 4) {
            $barcode_key[0]["mid"] = sprintf("%04s", $barcode_key[0]["mid"]);
        }
        echo json_encode(array($barcode_key[0]["mid"]));
    }
    public function generateBarcode_and_return($exist)
    {
        $items = $this->model("items");
        $settings = $this->model("settings");
        $barcode_key = $items->getMaxItemId();
        $barcode_settings = $settings->get_barcode_settings();
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
        if ($this->settings_info["barcode_type"] == "ean13" && $this->settings_info["print_barcode_in_browser"] == "1") {
            $barcode_key[0]["mid"] = self::generateEAN13(time());
        } else {
            if ($this->settings_info["barcode_type"] == "upc" && $this->settings_info["print_barcode_in_browser"] == "1") {
                if ($exist == 0) {
                    $upc = str_pad((int) $barcode_key[0]["mid"] + 1, 11, "1", STR_PAD_LEFT);
                    $barcode_key[0]["mid"] = self::UPCAbarcode($upc);
                } else {
                    $upc = str_pad((int) $exist, 11, "1", STR_PAD_LEFT);
                    $barcode_key[0]["mid"] = self::UPCAbarcode($upc);
                }
            } else {
                if ($this->settings_info["barcode_type"] == "CODE128C" && $this->settings_info["print_barcode_in_browser"] == "1") {
                    if ($exist == 0) {
                        $barcode_key[0]["mid"] = $this->settings_info["starting_barcode"] . "" . str_pad((int) $barcode_key[0]["mid"] + 1, 7, "0", STR_PAD_LEFT);
                    } else {
                        $barcode_key[0]["mid"] = $this->settings_info["starting_barcode"] . "" . str_pad((int) $exist, 7, "0", STR_PAD_LEFT);
                    }
                    while (self::checkBarcodeIfExist__($barcode_key[0]["mid"])) {
                        $barcode_key[0]["mid"] += 1;
                    }
                } else {
                    if ($exist == 0) {
                        $barcode_key[0]["mid"] = (int) $barcode_key[0]["mid"] + 1;
                    } else {
                        $barcode_key[0]["mid"] = (int) $exist;
                    }
                }
            }
        }
        if (strlen($barcode_key[0]["mid"]) < 4) {
            $barcode_key[0]["mid"] = sprintf("%04s", $barcode_key[0]["mid"]);
        }
        return $barcode_key[0]["mid"];
    }
    public function get_item($id_)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $store = $this->model("store");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($id);
        $measure = $this->model("measures");
        $measures = $measure->getMeasures();
        $measures_info = array();
        for ($i = 0; $i < count($measures); $i++) {
            $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
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
        $item_info[0]["discount"] = number_format($item_info[0]["discount"], 5);
        if (in_array($item_info[0]["id"], $discounts_items_ids)) {
            $item_info[0]["discount"] = number_format($discounts_items_discount[$item_info[0]["id"]], 2);
        }
        if (is_null($item_info[0]["barcode"])) {
            $item_info[0]["barcode"] = "";
        }
        $item_info[0]["plu"] = 0;
        if (substr($item_info[0]["barcode"], 0, 2) === $this->settings_info["plu_prefix"]) {
            $item_info[0]["plu"] = 1;
        }
        $item_info[0]["measure_label"] = "";
        if ($item_info[0]["unit_measure_id"] != NULL) {
            $item_info[0]["measure_label"] = $measures_info[$item_info[0]["unit_measure_id"]];
        }
        $item_info[0]["discount"] = number_format((double) $item_info[0]["discount"], 2);
        $item_info[0]["qty"] = 1;
        $item_info[0]["plu_price"] = 0;
        $item_info[0]["composite_items"] = array();
        if ($item_info[0]["is_composite"] == 1) {
            $item_info[0]["composite_items"] = $items->get_all_composite_of_item($id);
        }
        $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $id);
        $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
        $item_info[0]["round"] = $this->settings_info["round_val"];
        $item_info[0]["item_final_price"] = $item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100);
        echo json_encode($item_info);
    }
    public function get_global_items()
    {
        self::giveAccessTo(array(2, 4));
        $data_array["data"] = array();
        for ($i = 0; $i < 15000; $i++) {
            $tmp = array();
            array_push($tmp, 0);
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_item_id_by_barcode($barcode_)
    {
        $items = $this->model("items");
        $barcode = filter_var($barcode_, self::conversion_php_version_filter());
        $item_info = $items->get_item_id_by_barcode($barcode);
        if (0 < count($item_info)) {
            echo json_encode($item_info[0]["id"]);
        } else {
            echo json_encode(0);
        }
    }
    public function get_item_by_barcode($barcode_)
    {
        self::giveAccessTo(array(2, 4));
        $items = $this->model("items");
        $store = $this->model("store");
        $barcode = filter_var($barcode_, self::conversion_php_version_filter());
        $real_barcode = $barcode;
        $plu = 0;
        $plu_price = 0;
        if (substr($barcode, 0, 2) === $this->settings_info["plu_prefix"]) {
            $plu = 1;
            $real_barcode = substr($barcode, 0, 6);
            $plu_price = substr($barcode, 7, 5);
        }
        $item_info = $items->get_item_by_barcode($real_barcode);
        if (0 < count($item_info)) {
            $item_info[0]["plu"] = $plu;
            for ($i = 0; $i < count($item_info); $i++) {
                if (50 < strlen($item_info[$i]["description"])) {
                    $item_info[$i]["description"] = substr($item_info[$i]["description"], 0, 50) . " ...";
                }
            }
            if ($plu == 1) {
                $item_info[0]["qty"] = $plu_price * 1 / $item_info[0]["selling_price"];
            } else {
                $item_info[0]["qty"] = 1;
            }
            $measure = $this->model("measures");
            $measures = $measure->getMeasures();
            $measures_info = array();
            for ($i = 0; $i < count($measures); $i++) {
                $measures_info[$measures[$i]["id"]] = $measures[$i]["name"];
            }
            $item_info[0]["measure_label"] = "";
            if ($item_info[0]["unit_measure_id"] != NULL) {
                $item_info[0]["measure_label"] = $measures_info[$item_info[0]["unit_measure_id"]];
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
            $item_info[0]["discount"] = number_format($item_info[0]["discount"], 5);
            if (in_array($item_info[0]["id"], $discounts_items_ids)) {
                $item_info[0]["discount"] = number_format($discounts_items_discount[$item_info[0]["id"]], 2);
            }
            $item_info[0]["plu_price"] = $plu_price;
            $qty_store = $store->getQtyOfItem($_SESSION["store_id"], $item_info[0]["id"]);
            $item_info[0]["quantity"] = (double) $qty_store[0]["quantity"];
            $all_item_group = $items->getAllItemsByGroup_onlyGroup();
            $group_ids = array();
            for ($i = 0; $i < count($all_item_group); $i++) {
                array_push($group_ids, $all_item_group[$i]["item_group"]);
            }
            if (in_array($item_info[0]["item_group"], $group_ids)) {
                $item_info[0]["isgroup"] = 1;
            } else {
                $item_info[0]["isgroup"] = 0;
            }
        }
        echo json_encode($item_info);
    }
    public function delete_item($id_)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $return["status"] = $items->delete_item($id);
        echo json_encode($return);
    }
    public function delete_all_items($items_ids)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $global_logs = $this->model("global_logs");
        $ids = filter_var($items_ids, self::conversion_php_version_filter());
        $return = array();
        $return["status"] = $items->delete_all_items($ids);
        if ($return["status"]) {
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["related_to_item_id"] = $ids;
            $logs_info["description"] = "Item (IT-" . $ids . ") and all related group has been deleted";
            $logs_info["log_type"] = 1;
            $logs_info["other_info"] = "";
            $global_logs->add_global_log($logs_info);
        }
        echo json_encode($return);
    }
    public function import_items()
    {
        $info["user_id"] = $_SESSION["id"];
    }
    public function add_new_item()
    {
        self::giveAccessTo();
        if ($this->licenseExpired) {
            exit;
        }
        $items = $this->model("items");
        $store = $this->model("store");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $categories = $this->model("categories");
        $global_logs = $this->model("global_logs");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["item_desc"] = filter_input(INPUT_POST, "item_desc", self::conversion_php_version_filter());
        $info["item_alias"] = filter_input(INPUT_POST, "item_alias", self::conversion_php_version_filter());
        $info["item_alias"] = str_replace("'", "", $info["item_alias"]);
        $info["another_description"] = filter_input(INPUT_POST, "another_description", self::conversion_php_version_filter());
        $info["item_barcode"] = filter_input(INPUT_POST, "item_barcode", self::conversion_php_version_filter());
        $info["item_barcode_second"] = filter_input(INPUT_POST, "item_barcode_second", self::conversion_php_version_filter());
        $info["item_cat"] = filter_input(INPUT_POST, "item_cat", FILTER_SANITIZE_NUMBER_INT);
        $info["supplier_id"] = filter_input(INPUT_POST, "supplier_id", FILTER_SANITIZE_NUMBER_INT);
        $info["item_cost"] = filter_input(INPUT_POST, "item_cost", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if ($info["item_barcode"] == -1) {
            $info["item_barcode"] = self::generateBarcode_and_return($info["id_to_edit"]);
        }
        if ($info["item_cost"] == "" || $info["item_cost"] == NULL) {
            $info["item_cost"] = 0;
        }
        $info["item_vat"] = filter_input(INPUT_POST, "item_vat", FILTER_SANITIZE_NUMBER_INT);
        $info["vat_on_sale"] = filter_input(INPUT_POST, "item_vat_on_sale", FILTER_SANITIZE_NUMBER_INT);
        $info["item_disc"] = filter_input(INPUT_POST, "item_disc", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["selling_price"] = filter_input(INPUT_POST, "selling_price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if ($info["selling_price"] == 0) {
            $info["selling_price"] = filter_input(INPUT_POST, "item_final_price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        $info["weight"] = filter_input(INPUT_POST, "item_weight", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!isset($info["weight"]) || $info["weight"] == "") {
            $info["weight"] = 0;
        }
        $info["user_id"] = $_SESSION["id"];
        $info["lack_warning"] = filter_input(INPUT_POST, "lack_warning", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["item_unit_measure"] = filter_input(INPUT_POST, "item_unit_measure", FILTER_SANITIZE_NUMBER_INT);
        $info["item_size"] = filter_input(INPUT_POST, "item_size", FILTER_SANITIZE_NUMBER_INT);
        $info["item_color"] = filter_input(INPUT_POST, "item_color", self::conversion_php_version_filter());
        $info["material_id"] = filter_input(INPUT_POST, "material_id", FILTER_SANITIZE_NUMBER_INT);
        $info["expiry_date"] = filter_input(INPUT_POST, "expiry_date", self::conversion_php_version_filter());
        $info["composite_item_id"] = filter_input(INPUT_POST, "composite_item_id", FILTER_SANITIZE_NUMBER_INT);
        $info["composite_item_qty"] = filter_input(INPUT_POST, "composite_item_qty", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["item_text_color"] = filter_input(INPUT_POST, "item_text_color", FILTER_SANITIZE_NUMBER_INT);
        $info["text_color_pi"] = filter_input(INPUT_POST, "text_color_pi", self::conversion_php_version_filter());
        $info["item_size_pi"] = filter_input(INPUT_POST, "item_size_pi", self::conversion_php_version_filter());
        $info["item_cat_text"] = filter_input(INPUT_POST, "item_cat_text", self::conversion_php_version_filter());
        $info["item_pcat_text"] = filter_input(INPUT_POST, "item_pcat_text", self::conversion_php_version_filter());
        $info["wholesale_price"] = filter_input(INPUT_POST, "item_final_wholesale_price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["second_wholesale_price"] = filter_input(INPUT_POST, "item_final_sec_wholesale_price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!isset($info["second_wholesale_price"])) {
            $info["second_wholesale_price"] = 0;
        }
        $info["image_link"] = filter_input(INPUT_POST, "image_link", self::conversion_php_version_filter());
        if (!isset($info["image_link"])) {
            $info["image_link"] = "";
        }
        $info["supplier_ref"] = filter_input(INPUT_POST, "supplier_ref", self::conversion_php_version_filter());
        $info["official_or_not"] = filter_input(INPUT_POST, "official_or_not", FILTER_SANITIZE_NUMBER_INT);
        $info["item_sku"] = filter_input(INPUT_POST, "item_sku", self::conversion_php_version_filter());
        $info["up_all_gp"] = 0;
        if (isset($_POST["up_all_gp"])) {
            $info["up_all_gp"] = 1;
        }
        $info["show_on_pos"] = 0;
        if (isset($_POST["show_on_pos"])) {
            $info["show_on_pos"] = 1;
        }
        $info["is_pack"] = 0;
        if (isset($_POST["is_pack"])) {
            $info["is_pack"] = 1;
        }
        $info["depend_on_var_price"] = 0;
        if (isset($_POST["dvar"])) {
            $info["depend_on_var_price"] = 1;
        }
        $info["fixed_price"] = filter_input(INPUT_POST, "fixed_price", FILTER_SANITIZE_NUMBER_INT);
        $info["fixed_price_val"] = filter_input(INPUT_POST, "fixed_price_val", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!isset($info["fixed_price"])) {
            $info["fixed_price"] = 0;
        }
        if (!isset($info["fixed_price_val"]) || $info["fixed_price_val"] == "") {
            $info["fixed_price_val"] = 0;
        }
        $info["based_on_sku"] = filter_input(INPUT_POST, "based_on_sku", self::conversion_php_version_filter());
        $info["based_on_barcode"] = filter_input(INPUT_POST, "based_on_barcode", self::conversion_php_version_filter());
        if (isset($info["based_on_barcode"]) && isset($info["item_barcode"]) && 0 < strlen($info["item_barcode"])) {
            $item_sku_count = $items->check_if_sku_exist($info["item_sku"]);
            if ($this->settings_info["pi_unique_barcode"] == 0) {
                $item_barcode_count = array();
            } else {
                $item_barcode_count = $items->check_if_barcode_exist($info["item_barcode"]);
            }
            if (0 < count($item_barcode_count)) {
                $data["id"] = $item_barcode_count[0]["id"];
                echo json_encode($data);
                return NULL;
            }
            if (isset($info["item_size_pi"]) && 0 < strlen($info["item_size_pi"]) && $info["item_size_pi"] != "undefined") {
                $result_size = $sizes->checkSize($info["item_size_pi"]);
                if (0 < count($result_size)) {
                    $info["item_size"] = $result_size[0]["id"];
                } else {
                    $size_add_info = array();
                    $size_add_info["size_name"] = $info["item_size_pi"];
                    $info["item_size"] = $sizes->add_new_size($size_add_info);
                }
            } else {
                $info["item_size"] = 1;
            }
            if (isset($info["text_color_pi"]) && 0 < strlen($info["text_color_pi"]) && $info["text_color_pi"] != "undefined") {
                $result_color = $colors->checkColor($info["text_color_pi"]);
                if (0 < count($result_color)) {
                    $info["item_text_color"] = $result_color[0]["id"];
                } else {
                    $color_add_info = array();
                    $color_add_info["color_name"] = $info["text_color_pi"];
                    $info["item_text_color"] = $colors->add_new_color($color_add_info);
                }
            } else {
                $info["item_text_color"] = 1;
            }
            if (isset($info["item_pcat_text"]) && 0 < strlen($info["item_pcat_text"]) && $info["item_pcat_text"] != "undefined") {
                $result_pcategory = $categories->checkParentCategory($info["item_pcat_text"]);
                if (0 < count($result_pcategory)) {
                    $info["item_pcat_id"] = $result_pcategory[0]["id"];
                } else {
                    $pcategory_add_info = array();
                    $pcategory_add_info["cat_desc"] = $info["item_pcat_text"];
                    $info["item_pcat_id"] = $categories->add_new_parent_category($pcategory_add_info);
                }
            } else {
                $info["item_pcat_id"] = 1;
            }
            if (isset($info["item_cat_text"]) && 0 < strlen($info["item_cat_text"]) && $info["item_cat_text"] != "undefined") {
                $result_category = $categories->checkCategory($info["item_cat_text"], $info["item_pcat_id"]);
                if (0 < count($result_category)) {
                    $info["item_cat"] = $result_category[0]["id"];
                } else {
                    $category_add_info = array();
                    $category_add_info["cat_desc"] = $info["item_cat_text"];
                    $category_add_info["parent_cat_id"] = $info["item_pcat_id"];
                    $info["item_cat"] = $categories->add_new_category($category_add_info);
                }
            } else {
                $info["item_cat"] = 1;
            }
            if ($info["item_barcode"] == "undefined") {
                $info["item_barcode"] = 0;
            }
            if ($info["item_desc"] == "undefined") {
                $info["item_desc"] = "";
            }
        }
        if ($info["item_sku"] == "" || $info["item_sku"] == NULL) {
            $info["item_sku"] = "NULL";
        } else {
            $info["item_sku"] = "'" . trim($info["item_sku"]) . "'";
        }
        $info["is_official"] = 0;
        if (isset($info["official_or_not"])) {
            $info["is_official"] = 1;
        }
        if (!isset($info["wholesale_price"])) {
            $info["wholesale_price"] = 0;
        }
        $info["is_composite"] = 0;
        if (0 < $info["composite_item_id"]) {
            $info["is_composite"] = 1;
        }
        if (strlen($info["item_cat"]) == 0 || !isset($info["item_cat"])) {
            $info["item_cat"] = "NULL";
        } else {
            $info["item_cat"] = "'" . $info["item_cat"] . "'";
        }
        if (strlen($info["item_alias"]) == 0 || !isset($info["item_alias"])) {
            $info["item_alias"] = "NULL";
        } else {
            $info["item_alias"] = "'" . $info["item_alias"] . "'";
        }
        if (isset($info["another_description"])) {
            $info["another_description"] = "'" . $info["another_description"] . "'";
        } else {
            $info["another_description"] = "''";
        }
        if (isset($info["item_unit_measure"])) {
            if ($info["item_unit_measure"] == 0) {
                $info["item_unit_measure"] = 1;
            } else {
                $info["item_unit_measure"] = 0;
            }
        } else {
            $info["item_unit_measure"] = 0;
        }
        if (isset($info["item_size"])) {
            if ($info["item_size"] == 0) {
                $info["item_size"] = 1;
            }
        } else {
            $info["item_size"] = 1;
        }
        if (isset($info["item_text_color"])) {
            if ($info["item_text_color"] == 0) {
                $info["item_text_color"] = 1;
            }
        } else {
            $info["item_text_color"] = 1;
        }
        $info["v_access"] = filter_input(INPUT_POST, "v_access", self::conversion_php_version_filter());
        $info["vendor_access"] = 0;
        if (isset($info["v_access"])) {
            $info["vendor_access"] = 1;
        }
        $info["i_report"] = filter_input(INPUT_POST, "i_report", self::conversion_php_version_filter());
        $info["instant_report"] = 0;
        if (isset($info["i_report"])) {
            $info["instant_report"] = 1;
        }
        if ($info["item_barcode"] == "" || $info["item_barcode"] == NULL) {
            $info["item_barcode"] = "NULL";
        } else {
            $info["item_barcode"] = "'" . trim($info["item_barcode"]) . "'";
        }
        if ($info["item_barcode_second"] == "" || $info["item_barcode_second"] == NULL) {
            $info["item_barcode_second"] = "NULL";
        } else {
            $info["item_barcode_second"] = "'" . $info["item_barcode_second"] . "'";
        }
        $info["sync_only_cost"] = $this->settings_info["sync_only_cost"];
        if ($info["id_to_edit"] == 0) {
            $last_insert_item_id = $items->add_new_item($info);
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["related_to_item_id"] = $info["id_to_edit"];
            $logs_info["description"] = "New Item (IT-" . $last_insert_item_id . ") has been added";
            $logs_info["log_type"] = 1;
            $logs_info["other_info"] = "";
            $global_logs->add_global_log($logs_info);
            if ($info["is_composite"] == 1) {
                $info_composite = array();
                $info_composite["item_id"] = $info["composite_item_id"];
                $info_composite["composite_item_reference"] = $last_insert_item_id;
                $info_composite["item_composite_qty"] = $info["composite_item_qty"];
                $info_composite["is_pack"] = $info["is_pack"];
                $items->add_composite_items($info_composite);
            }
            if ($info["is_composite"] == 1 && $this->settings_info["composite_auto_set_cost_and_price"] == 1) {
                $items->composite_set_cost_and_price($last_insert_item_id);
            }
            $info_hist["user_id"] = $_SESSION["id"];
            $info_hist["item_id"] = $last_insert_item_id;
            $info_hist["old_cost"] = 0;
            $info_hist["new_cost"] = $info["item_cost"];
            $info_hist["old_qty"] = 0;
            $info_hist["new_qty"] = 0;
            $info_hist["source"] = "manual";
            $info_hist["receive_stock_id"] = "-";
            $info_hist["free"] = 0;
            $items->add_history_prices($info_hist);
        } else {
            $items_info_old = $items->get_item($info["id_to_edit"]);
            $items->update_item($info);
            $bc = $info["id_to_edit"];
            if (strlen($bc) < 6) {
                $bc = sprintf("%05s", $bc);
            }
            if ($info["item_barcode"] != "'" . $bc . "'") {
                $main_root = self::get_main_root();
                if (file_exists($main_root . "/barcodes/" . $bc . ".jpg")) {
                    unlink($main_root . "/barcodes/" . $bc . ".jpg");
                }
            }
            $info_item_store = $store->getQtyOfItem($_SESSION["store_id"], $info["id_to_edit"]);
            if (floatval($items_info_old[0]["buying_cost"]) != floatval($info["item_cost"])) {
                $info_hist = array();
                $info_hist["user_id"] = $_SESSION["id"];
                $info_hist["item_id"] = $info["id_to_edit"];
                $info_hist["old_cost"] = $items_info_old[0]["buying_cost"];
                $info_hist["new_cost"] = $info["item_cost"];
                $info_hist["new_qty"] = 0;
                $info_hist["source"] = "force";
                $info_hist["receive_stock_id"] = "-";
                $info_hist["free"] = 0;
                if ($_SESSION["centralize"] == 0) {
                    $info_hist["old_qty"] = $info_item_store[0]["quantity"];
                } else {
                    $info_hist["old_qty"] = self::get_sum_qty_in_all_stores($info["id_to_edit"]);
                }
                $items->add_history_prices($info_hist);
                $items->set_global_average_cost($info["id_to_edit"]);
            }
            if (floatval($items_info_old[0]["buying_cost"]) != floatval($info["item_cost"])) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item cost has been changed from " . floatval($items_info_old[0]["buying_cost"]) . " to " . floatval($info["item_cost"]);
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if (floatval($items_info_old[0]["selling_price"]) != floatval($info["selling_price"])) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item selling price has been changed from " . floatval($items_info_old[0]["selling_price"]) . " to " . floatval($info["selling_price"]);
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if (floatval($items_info_old[0]["wholesale_price"]) != floatval($info["wholesale_price"])) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item wholesale has been changed from " . floatval($items_info_old[0]["wholesale_price"]) . " to " . floatval($info["wholesale_price"]);
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if (floatval($items_info_old[0]["second_wholesale_price"]) != floatval($info["second_wholesale_price"])) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item second wholesale has been changed from " . floatval($items_info_old[0]["second_wholesale_price"]) . " to " . floatval($info["second_wholesale_price"]);
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if ($items_info_old[0]["description"] != $info["item_desc"]) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item description price has been changed from " . filter_var($items_info_old[0]["description"], self::conversion_php_version_filter()) . " to " . filter_var($info["item_desc"], self::conversion_php_version_filter());
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if ($items_info_old[0]["item_barcode"] == NULL) {
                $items_info_old[0]["item_barcode"] = "";
            }
            if ($info["item_barcode"] == NULL) {
                $info["item_barcode"] = "";
            }
            if ($info["item_barcode"] != NULL && $items_info_old[0]["barcode"] != trim($info["item_barcode"], "'")) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item barcode has been changed from " . filter_var($items_info_old[0]["barcode"], self::conversion_php_version_filter()) . " to " . filter_var(trim($info["item_barcode"], "'"), self::conversion_php_version_filter());
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if ($items_info_old[0]["sku_code"] == NULL) {
                $items_info_old[0]["sku_code"] = "";
            }
            if ($info["item_sku"] == NULL) {
                $info["item_sku"] = "";
            }
            if ($items_info_old[0]["sku_code"] != trim($info["item_sku"], "'")) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item SKU has been changed from " . filter_var($items_info_old[0]["sku_code"], self::conversion_php_version_filter()) . " to " . filter_var(trim($info["item_sku"], "'"), self::conversion_php_version_filter());
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if ($items_info_old[0]["second_barcode"] == NULL) {
                $items_info_old[0]["second_barcode"] = "";
            }
            if ($info["second_barcode"] == NULL) {
                $info["item_barcode"] = "";
            }
            if ($items_info_old[0]["second_barcode"] != NULL && $items_info_old[0]["second_barcode"] != trim($info["item_barcode_second"], "'")) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item secondary barcode has been changed from " . filter_var($items_info_old[0]["second_barcode"], self::conversion_php_version_filter()) . " to " . filter_var(trim($info["item_barcode_second"], "'"), self::conversion_php_version_filter());
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if ($items_info_old[0]["discount"] != $info["item_disc"]) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Item discount has been changed from " . $items_info_old[0]["discount"] . " to " . $info["item_disc"];
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if ($items_info_old[0]["fixed_price"] != $info["fixed_price"]) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Fixed price has been changed from " . $items_info_old[0]["fixed_price"] . " to " . $info["fixed_price"];
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
            if ($items_info_old[0]["fixed_price_value"] != $info["fixed_price_val"]) {
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION["id"];
                $logs_info["related_to_item_id"] = $info["id_to_edit"];
                $logs_info["description"] = "Fixed price has been changed from " . number_format($items_info_old[0]["fixed_price_value"], 2) . " to " . number_format($info["fixed_price_val"], 2);
                $logs_info["log_type"] = 1;
                $logs_info["other_info"] = "";
                $global_logs->add_global_log($logs_info);
            }
        }
        if (0 < $info["id_to_edit"] && $info["up_all_gp"] == 1) {
            $items->update_prices_of_all_group($info);
        }
        $items->sync_item_with_store($_SESSION["store_id"]);
        $data["id"] = $info["id_to_edit"];
        if ($data["id"] == 0) {
            $data["id"] = $last_insert_item_id;
        }
        if ($info["id_to_edit"] == 0 && (isset($_POST["item_text_color_g"]) || isset($_POST["item_size_g"]))) {
            $grp_colors_array = array();
            if (isset($_POST["item_text_color_g"])) {
                $grp_colors_array = $_POST["item_text_color_g"];
            }
            $grp_sizes_array = array();
            if (isset($_POST["item_size_g"])) {
                $grp_sizes_array = $_POST["item_size_g"];
            }
            $group_found = false;
            $itinfo = $items->get_item($last_insert_item_id);
            for ($c = 0; $c < count($grp_colors_array); $c++) {
                for ($s = 0; $s < count($grp_sizes_array); $s++) {
                    $bcode = "";
                    $sku = "";
                    $qty = 0;
                    $info["item_barcode"] = "''";
                    $info["item_sku"] = "''";
                    if (0 < $grp_colors_array[$c] && 0 < $grp_sizes_array[$s]) {
                        $bcode = filter_input(INPUT_POST, "grp_bc_" . $grp_colors_array[$c] . "_" . $grp_sizes_array[$s], self::conversion_php_version_filter());
                        if (isset($bcode) && 0 < strlen($bcode)) {
                            $info["item_barcode"] = "'" . $bcode . "'";
                        } else {
                            $info["item_barcode"] = "'" . self::generateBarcode_and_return(0) . "'";
                        }
                        $sku = filter_input(INPUT_POST, "sku_bc_" . $grp_colors_array[$c] . "_" . $grp_sizes_array[$s], self::conversion_php_version_filter());
                        if (isset($sku) && 0 < strlen($sku)) {
                            $info["item_sku"] = "'" . $sku . "'";
                        }
                        $info["item_text_color"] = $grp_colors_array[$c];
                        $info["item_size"] = $grp_sizes_array[$s];
                        $_id_ = $items->add_new_item($info);
                        $items->set_item_group($_id_, $itinfo[0]["item_group"]);
                        $items->sync_item_with_store($_SESSION["store_id"]);
                        $qty = filter_input(INPUT_POST, "qty_bc_" . $grp_colors_array[$c] . "_" . $grp_sizes_array[$s], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        if (isset($qty) && 0 < $qty) {
                            $qty_info = array();
                            $qty_info["qty"] = $qty;
                            $qty_info["store_id"] = $_SESSION["store_id"];
                            $qty_info["item_id"] = $_id_;
                            $qty_info["source"] = "manual";
                            $store->add_qty($qty_info);
                        }
                        $group_found = true;
                    }
                }
            }
            if ($group_found) {
                $items->delete_item($last_insert_item_id);
            }
        }
        echo json_encode($data);
    }
    public function getitems_for_type_head()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $info = $items->getitems_for_type_head();
        echo json_encode($info);
    }
    public function getAllItems_()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $tables_info = $items->getAllItems();
        $setting = self::getSettings();
        $data_array["data"] = array();
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
        $info = array();
        for ($i = 0; $i < count($tables_info); $i++) {
            $info[$i]["id"] = $tables_info[$i]["id"];
            $info[$i]["description"] = $tables_info[$i]["description"];
            $info[$i]["buying_cost"] = (double) $tables_info[$i]["buying_cost"];
            $info[$i]["vat"] = $tables_info[$i]["vat"];
            $info[$i]["barcode"] = $tables_info[$i]["barcode"];
        }
        echo json_encode($info);
    }
    public function get_all_items_of_sub_category($_id, $_store_id)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $size = $this->model("sizes");
        $colors = $this->model("colors");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
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
        $tables_info = $items->getAllItemsBySub($id, $store_id);
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
    public function _getAllBoxesItemsQty()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $boxes_qty = $items->getAllBoxesItemsQty();
        $info = array();
        for ($i = 0; $i < count($boxes_qty); $i++) {
            if (isset($info["items_ids"][$boxes_qty[$i]["composite_item_id"]])) {
                array_push($info["items_ids"][$boxes_qty[$i]["composite_item_id"]], $boxes_qty[$i]["item_id"]);
                array_push($info["composite_nb"][$boxes_qty[$i]["composite_item_id"]], round($boxes_qty[$i]["qty"], 0));
            } else {
                $info["items_ids"][$boxes_qty[$i]["composite_item_id"]] = array();
                $info["composite_nb"][$boxes_qty[$i]["composite_item_id"]] = array();
                array_push($info["items_ids"][$boxes_qty[$i]["composite_item_id"]], $boxes_qty[$i]["item_id"]);
                array_push($info["composite_nb"][$boxes_qty[$i]["composite_item_id"]], round($boxes_qty[$i]["qty"], 0));
            }
        }
        return $info;
    }
    public function getAllBoxesItemsQty()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $boxes_qty = $items->getAllBoxesItemsQty();
        $info = array();
        for ($i = 0; $i < count($boxes_qty); $i++) {
            $info["items_ids"][$boxes_qty[$i]["composite_item_id"]] = $boxes_qty[$i]["item_id"];
            $info["composite_nb"][$boxes_qty[$i]["composite_item_id"]] = floor($boxes_qty[$i]["qty"]);
        }
        return $info;
    }
    public function chek_if_item_of_group($_item_id)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $result = $items->get_item($item_id);
        $all_item_group = $items->getAllItemsByGroup_onlyGroup();
        $group_ids = array();
        for ($i = 0; $i < count($all_item_group); $i++) {
            array_push($group_ids, $all_item_group[$i]["item_group"]);
        }
        $info = array();
        if (in_array($result[0]["item_group"], $group_ids)) {
            $info["group"] = "1";
        } else {
            $info["group"] = "0";
        }
        echo json_encode($info);
    }
    public function get_trash_items()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $material = $this->model("material");
        $trash_info = $items->getAllTrashItems();
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $material_info = $material->getMaterials();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $material_info_label = array();
        for ($i = 0; $i < count($material_info); $i++) {
            $material_info_label[$material_info[$i]["id"]] = $material_info[$i]["name"];
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
        $setting = self::getSettings();
        $data_array["data"] = array();
        for ($i = 0; $i < count($trash_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($trash_info[$i]["id"]));
            if (strlen($trash_info[$i]["barcode"]) < 5) {
                array_push($tmp, sprintf("%05s", $trash_info[$i]["barcode"]));
            } else {
                array_push($tmp, $trash_info[$i]["barcode"]);
            }
            if ($trash_info[$i]["sku_code"] != NULL) {
                array_push($tmp, $trash_info[$i]["description"] . " #" . $trash_info[$i]["sku_code"]);
            } else {
                array_push($tmp, $trash_info[$i]["description"]);
            }
            $final_cost = 0;
            if ($trash_info[$i]["vat"]) {
                $final_cost = floatval($trash_info[$i]["buying_cost"] * $this->settings_info["vat"]);
            } else {
                $final_cost = floatval($trash_info[$i]["buying_cost"]);
            }
            array_push($tmp, self::value_format_custom($final_cost, $setting));
            if (in_array($trash_info[$i]["id"], $discounts_items_ids)) {
                $trash_info[$i]["discount"] = $discounts_items_discount[$trash_info[$i]["id"]];
            }
            $price_after_discount = $trash_info[$i]["selling_price"] - $trash_info[$i]["selling_price"] * $trash_info[$i]["discount"] / 100;
            if ($trash_info[$i]["vat"] == 1) {
                $price_after_discount = $price_after_discount * $this->settings_info["vat"];
            }
            if ($setting["enable_wholasale"] == 0) {
                array_push($tmp, self::value_format_custom($price_after_discount, $setting));
            } else {
                if ($trash_info[$i]["vat"] == 1) {
                    $tables_info[$i]["wholesale_price"] = $trash_info[$i]["wholesale_price"] * $this->settings_info["vat"];
                }
                array_push($tmp, self::value_format_custom($price_after_discount, $setting) . " <b>/</b> " . self::value_format_custom($trash_info[$i]["wholesale_price"], $setting));
            }
            if ($trash_info[$i]["size_id"] == NULL) {
                array_push($tmp, "None");
            } else {
                array_push($tmp, $sizes_info_label[$trash_info[$i]["size_id"]]);
            }
            if (!is_null($trash_info[$i]["color_text_id"])) {
                array_push($tmp, $colors_info_label[$trash_info[$i]["color_text_id"]]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function restore_item($_item_id)
    {
        self::giveAccessTo();
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $items->restore_item($item_id);
        echo json_encode(array());
    }
    public function oilnk($_item_id)
    {
        self::giveAccessTo();
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $items_model = $this->model("items");
        $item_info = $items_model->get_item($item_id);
        $return = array();
        $return["link"] = $item_info[0]["image_link"];
        echo json_encode($return);
    }
    public function validateUrl($url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $urlParts = parse_url($url);
        if (!in_array($urlParts["scheme"], array("http", "https"))) {
            return false;
        }
        return true;
    }
    public function getAllItems($store_id = 0, $_category_id, $_sub_category_id, $_itemboxes, $_supplier_id, $stock_status)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $material = $this->model("material");
        $items->sync_item_with_store($_SESSION["store_id"]);
        $complexItems = $this->model("complexItems");
        $result_complex_availabily = $complexItems->complex_items_production_availability();
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
        $sub_category_id = filter_var($_sub_category_id, FILTER_SANITIZE_NUMBER_INT);
        $itemboxes = filter_var($_itemboxes, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $tables_info = $items->getAllItems_withfilter($category_id, $sub_category_id, $itemboxes, $supplier_id, $stock_status);
        $all_item_qty_in_store = $items->get_all_item_qty_in_store($_SESSION["store_id"]);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $all_item_qty_in_store_by_group = $items->get_all_item_qty_in_store_by_group($store_id);
        $qty_group = array();
        for ($i = 0; $i < count($all_item_qty_in_store_by_group); $i++) {
            $qty_group[$all_item_qty_in_store_by_group[$i]["item_id"]] = $all_item_qty_in_store_by_group[$i]["quantity"];
        }
        $setting = self::getSettings();
        $data_array["data"] = array();
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $material_info = $material->getMaterials();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $material_info_label = array();
        for ($i = 0; $i < count($material_info); $i++) {
            $material_info_label[$material_info[$i]["id"]] = $material_info[$i]["name"];
        }
        $discounts = $this->model("discounts");
        $discounts_items = $discounts->get_all_items_under_discounts();
        $discounts_items_discount = array();
        for ($i = 0; $i < count($discounts_items); $i++) {
            $discounts_items_discount[$discounts_items[$i]["item_id"]] = $discounts_items[$i]["discount_value"];
        }
        $all_boxes_qty = self::_getAllBoxesItemsQty();
        $all_item_group = $items->getAllItemsByGroup_onlyGroup();
        $group_ids = array();
        for ($i = 0; $i < count($all_item_group); $i++) {
            $group_ids[$all_item_group[$i]["item_group"]] = $all_item_group[$i]["item_group"];
        }
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            if (isset($group_ids[$tables_info[$i]["item_group"]])) {
                array_push($tmp, self::idFormat_item($tables_info[$i]["id"]) . " <i class='glyphicon glyphicon-folder-open' style='cursor:pointer' onclick='BulkItem(" . $tables_info[$i]["id"] . ")'></i>");
            } else {
                array_push($tmp, self::idFormat_item($tables_info[$i]["id"]));
            }
            array_push($tmp, self::idFormat_supplier($tables_info[$i]["supplier_reference"]));
            if (strlen($tables_info[$i]["barcode"]) < 5) {
                array_push($tmp, sprintf("%05s", $tables_info[$i]["barcode"]));
            } else {
                array_push($tmp, $tables_info[$i]["barcode"]);
            }
            $link_image = "";
            if (0 < strlen($tables_info[$i]["image_link"]) && self::validateUrl($tables_info[$i]["image_link"])) {
                $link_image = "<i onclick=\"oilnk(" . $tables_info[$i]["id"] . ")\" title=\"Open Image\" class=\"glyphicon glyphicon-link\"></i>";
            }
            $prod = "";
            if (0 < $tables_info[$i]["complex_item_id"]) {
                $prod = "&nbsp;<b class='text-primary prod' onclick='edit_manual_complex_item(" . $tables_info[$i]["complex_item_id"] . ")'>Composed</b>";
            }
            if ($tables_info[$i]["sku_code"] != "undefined" && 0 < strlen($tables_info[$i]["sku_code"])) {
                array_push($tmp, $tables_info[$i]["description"] . " #" . $tables_info[$i]["sku_code"] . $link_image . $prod);
            } else {
                array_push($tmp, $tables_info[$i]["description"] . $link_image . $prod);
            }
            if ($tables_info[$i]["size_id"] == NULL) {
                array_push($tmp, "None");
            } else {
                array_push($tmp, $sizes_info_label[$tables_info[$i]["size_id"]]);
            }
            array_push($tmp, $tables_info[$i]["color_id"]);
            if (!is_null($tables_info[$i]["color_text_id"])) {
                array_push($tmp, $colors_info_label[$tables_info[$i]["color_text_id"]]);
            } else {
                array_push($tmp, "");
            }
            $final_cost = 0;
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                if ($tables_info[$i]["vat"]) {
                    $final_cost = floatval($tables_info[$i]["buying_cost"] * $this->settings_info["vat"]);
                } else {
                    $final_cost = floatval($tables_info[$i]["buying_cost"]);
                }
                array_push($tmp, self::global_number_formatter($final_cost, $setting));
            }
            if (isset($discounts_items_discount[$tables_info[$i]["id"]])) {
                $tables_info[$i]["discount"] = $discounts_items_discount[$tables_info[$i]["id"]];
            }
            $price_after_discount = $tables_info[$i]["selling_price"] - $tables_info[$i]["selling_price"] * $tables_info[$i]["discount"] / 100;
            if ($tables_info[$i]["vat"] == 1) {
                $price_after_discount = $price_after_discount * $this->settings_info["vat"];
            }
            if ($setting["enable_wholasale"] == 0) {
                array_push($tmp, self::global_number_formatter($price_after_discount, $setting));
            } else {
                if ($tables_info[$i]["vat"] == 1) {
                    $tables_info[$i]["wholesale_price"] = $tables_info[$i]["wholesale_price"] * $this->settings_info["vat"];
                }
                array_push($tmp, self::global_number_formatter($price_after_discount, $setting) . " <b>/</b> " . self::global_number_formatter($tables_info[$i]["wholesale_price"], $setting) . " <b>/</b> " . self::global_number_formatter($tables_info[$i]["second_wholesale_price"], $setting));
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                if ($setting["enable_wholasale"] == 0) {
                    array_push($tmp, "***");
                } else {
                    array_push($tmp, "*** <b>/</b> ***");
                }
            } else {
                if ($setting["enable_wholasale"] == 0) {
                    array_push($tmp, self::global_number_formatter($price_after_discount - $final_cost, $setting));
                } else {
                    array_push($tmp, self::global_number_formatter($price_after_discount - $final_cost, $setting) . " <b>/</b> " . self::value_format_custom($tables_info[$i]["wholesale_price"] - $final_cost, $setting));
                }
            }
            if (0 < $price_after_discount) {
                $margin_profit = ($price_after_discount - $final_cost) / $price_after_discount * 100;
            } else {
                $margin_profit = 0;
            }
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "** %");
            } else {
                array_push($tmp, self::global_number_formatter($margin_profit, $this->settings_info) . " %");
            }
            array_push($tmp, $material_info_label[$tables_info[$i]["material_id"]]);
            if ($_SESSION["role"] == 1) {
                if ($tables_info[$i]["is_composite"] == 0) {
                    if (0 < $tables_info[$i]["complex_item_id"]) {
                        array_push($tmp, "PROD: <b>" . floor($result_complex_availabily[$tables_info[$i]["complex_item_id"]]) . "<br/>");
                    } else {
                        if (isset($group_ids[$all_item_group["item_group"]])) {
                            array_push($tmp, (double) $qty_group[$tables_info[$i]["id"]]);
                        } else {
                            array_push($tmp, "<button type='button' class='btn btn-info btn-xs btn-full-wd' onclick='addItemToStoreByID(" . $tables_info[$i]["id"] . ")'>" . (double) $qty[$tables_info[$i]["id"]] . "</button>");
                        }
                    }
                } else {
                    if (0 < $tables_info[$i]["complex_item_id"]) {
                        array_push($tmp, "");
                    } else {
                        if (1 < count($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]])) {
                            array_push($tmp, "<span class='show_complex' onclick='show_complex(" . $tables_info[$i]["id"] . ")' >complex</span>");
                        } else {
                            if ($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0] == 0) {
                                $bx_nb = 0;
                                array_push($tmp, $bx_nb);
                            } else {
                                $bx_nb = $qty[$all_boxes_qty["items_ids"][$tables_info[$i]["id"]][0]] / $all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0];
                                $bx_remain = $qty[$all_boxes_qty["items_ids"][$tables_info[$i]["id"]][0]] % $all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0];
                                array_push($tmp, "<b title='WSP: " . $tables_info[$i]["wholesale_price"] / $all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0] . "/WWSP: " . $tables_info[$i]["second_wholesale_price"] / $all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0] . " /UNIT'>" . $all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0] . "U</b>/ " . (int) $bx_nb . "B & " . self::value_format_custom($bx_remain, $this->settings_info) . "It");
                            }
                        }
                    }
                }
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllItemsForTransfer($store_id = 0, $_category_id = 0, $_sub_category_id = 0, $_itemboxes = 0, $_supplier_id = 0)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $material = $this->model("material");
        $items->sync_item_with_store($_SESSION["store_id"]);
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
        $sub_category_id = filter_var($_sub_category_id, FILTER_SANITIZE_NUMBER_INT);
        $itemboxes = filter_var($_itemboxes, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $tables_info = $items->getAllItems_withfilter_without_grouping($category_id, $sub_category_id, $itemboxes, $supplier_id);
        $all_item_qty_in_store = $items->get_all_item_qty_in_store($store_id);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $setting = self::getSettings();
        $data_array["data"] = array();
        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();
        $material_info = $material->getMaterials();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $material_info_label = array();
        for ($i = 0; $i < count($material_info); $i++) {
            $material_info_label[$material_info[$i]["id"]] = $material_info[$i]["name"];
        }
        $all_boxes_qty = self::_getAllBoxesItemsQty();
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($tables_info[$i]["id"]));
            if (strlen($tables_info[$i]["barcode"]) < 5) {
                array_push($tmp, sprintf("%05s", $tables_info[$i]["barcode"]));
            } else {
                array_push($tmp, $tables_info[$i]["barcode"]);
            }
            $color_label = "";
            if ($tables_info[$i]["color_text_id"] != NULL && $tables_info[$i]["color_text_id"] != "") {
                if (isset($colors_info_label[$tables_info[$i]["color_text_id"]])) {
                    $color_label = $colors_info_label[$tables_info[$i]["color_text_id"]];
                } else {
                    $color_label = "Unknown";
                }
            } else {
                $color_label = "Unknown";
            }
            $size_label = "";
            if ($tables_info[$i]["size_id"] != NULL && $tables_info[$i]["size_id"] != "") {
                if (isset($sizes_info_label[$tables_info[$i]["size_id"]])) {
                    $size_label = $sizes_info_label[$tables_info[$i]["size_id"]];
                } else {
                    $size_label = "Unknown";
                }
            } else {
                $size_label = "Unknown";
            }
            if ($tables_info[$i]["sku_code"] != NULL) {
                array_push($tmp, $tables_info[$i]["description"] . " #" . $tables_info[$i]["sku_code"] . " <b>Size:</b> " . $color_label . " <b>Color:</b> " . $size_label);
            } else {
                array_push($tmp, $tables_info[$i]["description"] . " <b>Color:</b> " . $color_label . " <b>Size:</b> " . $size_label);
            }
            if ($store_id == $_SESSION["store_id"]) {
                if ($tables_info[$i]["is_composite"] == 0) {
                    array_push($tmp, (double) $qty[$tables_info[$i]["id"]]);
                } else {
                    if (1 < count($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]])) {
                        array_push($tmp, "<span class='show_complex' onclick='show_complex(" . $tables_info[$i]["id"] . ")' >complex</span>");
                    } else {
                        if ($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0] == 0) {
                            $bx_nb = 0;
                            array_push($tmp, $bx_nb);
                        } else {
                            $bx_nb = $qty[$all_boxes_qty["items_ids"][$tables_info[$i]["id"]][0]] / floor($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0]);
                            $bx_remain = floor($qty[$all_boxes_qty["items_ids"][$tables_info[$i]["id"]][0]]) % floor($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0]);
                            array_push($tmp, (int) $bx_nb . " B & " . $bx_remain . " It");
                        }
                    }
                }
            } else {
                array_push($tmp, "-");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_needed_data_categories_subcategories()
    {
        self::giveAccessTo(array(2));
        $items = $this->model("items");
        $categories = $this->model("categories");
        $info["categories"] = $items->getCategories();
        $info["parents_categories"] = $categories->getAllParentCategories();
        echo json_encode($info);
    }
    public function get_optimized_needed_data()
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $items = $this->model("items");
        $categories = $this->model("categories");
        $store = $this->model("store");
        $suppliers_data = $suppliers->getSuppliers();
        $info["suppliers"] = array();
        $info["categories"] = array();
        $info["measures"] = array();
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $info["suppliers"][$i]["id"] = $suppliers_data[$i]["id"];
            $info["suppliers"][$i]["name"] = $suppliers_data[$i]["name"];
            $info["suppliers"][$i]["c_name"] = $suppliers_data[$i]["contact_name"];
            $info["suppliers"][$i]["address"] = $suppliers_data[$i]["address"];
        }
        $info["stores"] = $store->getStores();
        $info["categories"] = $items->getCategories();
        $info["parents_categories"] = $categories->getAllParentCategories();
        $info["default_currency_symbol"] = $this->settings_info["default_currency_symbol"];
        $info["item_another_description_lang"] = $this->settings_info["item_another_description_lang"];
        $info["vat"] = $this->settings_info["vat"];
        $info["inventory_items_hide_col"] = $this->settings_info["inventory_items_hide_col"];
        $info["report_sales_hide_colums"] = $this->settings_info["report_sales_hide_colums"];
        $info["print_barcode_in_browser"] = $this->settings_info["print_barcode_in_browser"];
        echo json_encode($info);
    }
    public function get_needed_data()
    {
        self::giveAccessTo();
        $suppliers = $this->model("suppliers");
        $items = $this->model("items");
        $measures = $this->model("measures");
        $sizes = $this->model("sizes");
        $colors = $this->model("colors");
        $categories = $this->model("categories");
        $material = $this->model("material");
        $store = $this->model("store");
        $mobile_store = $this->model("mobileStore");
        $suppliers_data = $suppliers->getSuppliers();
        $info["suppliers"] = array();
        $info["categories"] = array();
        $info["measures"] = array();
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $info["suppliers"][$i]["id"] = $suppliers_data[$i]["id"];
            $info["suppliers"][$i]["name"] = $suppliers_data[$i]["name"];
            $info["suppliers"][$i]["c_name"] = $suppliers_data[$i]["contact_name"];
            $info["suppliers"][$i]["address"] = $suppliers_data[$i]["address"];
        }
        $info["stores"] = $store->getStores();
        $info["measures"] = $measures->getMeasures();
        $info["sizes"] = $sizes->getSizesOnlyAvailable();
        $info["colors_text"] = $colors->getColorsTextOnlyAvailable();
        $info["categories"] = $items->getCategories();
        $info["materials"] = $material->getMaterials();
        $info["parents_categories"] = $categories->getAllParentCategories();
        $info["stores_secondary"] = $store->getStoresNotGlobal();
        $info["all_stores_primary"] = $store->getStoresNotGlobalInDetails();
        $info["all_stores_totally"] = $store->getAllStores();
        $info["all_groups"] = $items->getAllItemsByGroup();
        $info["mobile_operators"] = $mobile_store->getOperators();
        $info["rate"] = $this->settings_info["usdlbp_rate"];
        $info["usd_but_show_lbp_priority"] = $this->settings_info["usd_but_show_lbp_priority"];
        $info["default_currency_symbol"] = $this->settings_info["default_currency_symbol"];
        $info["print_barcode_in_browser"] = $this->settings_info["print_barcode_in_browser"];
        $info["item_another_description_lang"] = $this->settings_info["item_another_description_lang"];
        $info["vat"] = $this->settings_info["vat"];
        echo json_encode($info);
    }
    public function get_group($_item_id)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($item_id);
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
        if ($item_info[0]["item_group"] == 0) {
            $items_gr = array();
        } else {
            $items_gr = $items->get_items_by_group($item_info[0]["item_group"]);
        }
        $all_item_qty_in_store = $items->get_all_item_qty_in_store($_SESSION["store_id"]);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($items_gr); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_item($items_gr[$i]["id"]));
            if (strlen($items_gr[$i]["barcode"]) < 5) {
                array_push($tmp, sprintf("%05s", $items_gr[$i]["barcode"]));
            } else {
                array_push($tmp, $items_gr[$i]["barcode"]);
            }
            array_push($tmp, $items_gr[$i]["description"]);
            array_push($tmp, $colors_info_label[$items_gr[$i]["color_text_id"]]);
            array_push($tmp, $sizes_info_label[$items_gr[$i]["size_id"]]);
            array_push($tmp, floor($qty[$items_gr[$i]["id"]]));
            $final_cost = 0;
            if ($_SESSION["hide_critical_data"] == 1) {
                array_push($tmp, "***");
            } else {
                if ($items_gr[$i]["vat"]) {
                    $final_cost = floatval($items_gr[$i]["buying_cost"] * $this->settings_info["vat"]);
                } else {
                    $final_cost = floatval($items_gr[$i]["buying_cost"]);
                }
                array_push($tmp, self::value_format_custom($final_cost, $this->settings_info));
            }
            $price_after_discount = $items_gr[$i]["selling_price"] - $items_gr[$i]["selling_price"] * $items_gr[$i]["discount"] / 100;
            if ($items_gr[$i]["vat"] == 1) {
                $price_after_discount = $price_after_discount * $this->settings_info["vat"];
            }
            if ($this->settings_info["enable_wholasale"] == 0) {
                array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info));
            } else {
                if ($items_gr[$i]["vat"] == 1) {
                    $items_gr[$i]["wholesale_price"] = $items_gr[$i]["wholesale_price"] * $this->settings_info["vat"];
                }
                array_push($tmp, self::value_format_custom($price_after_discount, $this->settings_info) . " <b>/</b> " . self::value_format_custom($items_gr[$i]["wholesale_price"], $this->settings_info) . " <b>/</b> " . self::value_format_custom($items_gr[$i]["second_wholesale_price"], $this->settings_info));
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function add_bulk()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $store = $this->model("store");
        $shortcuts = $this->model("shortcuts");
        $global_logs = $this->model("global_logs");
        $info["item_id"] = filter_input(INPUT_POST, "item_id", FILTER_SANITIZE_NUMBER_INT);
        $default_qty = filter_input(INPUT_POST, "bulk_items_qty", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $item_info = $items->get_item($info["item_id"]);
        $prepare_for_shortcuts = array();
        if ($item_info[0]["is_composite"] == 0) {
            if (isset($_POST["bulk_items_select_colors"]) && isset($_POST["bulk_items_select_sizes"])) {
                for ($i = 0; $i < count($_POST["bulk_items_select_colors"]); $i++) {
                    $prepare_for_shortcuts[$_POST["bulk_items_select_colors"][$i]] = array();
                    for ($j = 0; $j < count($_POST["bulk_items_select_sizes"]); $j++) {
                        $info["color_text_id"] = $_POST["bulk_items_select_colors"][$i];
                        $info["size_id"] = $_POST["bulk_items_select_sizes"][$j];
                        $info["item_group"] = $item_info[0]["item_group"];
                        if ($items->if_bulk_accepted($info)) {
                            $barcode_key = $items->getMaxItemId();
                            if (self::is_on_server() || $this->settings_info["barcode_type"] == "CODE128C") {
                                $info["barcode"] = $this->settings_info["starting_barcode"] . "" . str_pad((int) $barcode_key[0]["mid"] + 1, 7, "0", STR_PAD_LEFT);
                            } else {
                                $info["barcode"] = (int) $barcode_key[0]["mid"] + 1;
                            }
                            $last_insert_id = $items->bulk_item($info);
                            $logs_info = array();
                            $logs_info["operator_id"] = $_SESSION["id"];
                            $logs_info["related_to_item_id"] = $last_insert_id;
                            $logs_info["description"] = "New Item (IT-" . $last_insert_id . ") has been added";
                            $logs_info["log_type"] = 1;
                            $logs_info["other_info"] = "";
                            $global_logs->add_global_log($logs_info);
                            array_push($prepare_for_shortcuts[$_POST["bulk_items_select_colors"][$i]], array("size_id" => $info["size_id"], "item_id" => $last_insert_id));
                            $items->sync_item_with_store($_SESSION["store_id"]);
                            $qty_info = array();
                            $qty_info["qty"] = $default_qty;
                            $qty_info["store_id"] = $_SESSION["store_id"];
                            $qty_info["item_id"] = $last_insert_id;
                            $qty_info["source"] = "manual";
                            $store->add_qty($qty_info);
                        } else {
                            $item_info_result = $items->get_item_info_by_color_size_group($info);
                            array_push($prepare_for_shortcuts[$_POST["bulk_items_select_colors"][$i]], array("size_id" => $info["size_id"], "item_id" => $item_info_result[0]["id"]));
                        }
                    }
                }
            } else {
                if (isset($_POST["bulk_items_select_colors"]) && !isset($_POST["bulk_items_select_sizes"])) {
                    for ($i = 0; $i < count($_POST["bulk_items_select_colors"]); $i++) {
                        $info["color_text_id"] = $_POST["bulk_items_select_colors"][$i];
                        $info["size_id"] = 0;
                        $info["item_group"] = $item_info[0]["item_group"];
                        if ($items->if_bulk_accepted($info)) {
                            $barcode_key = $items->getMaxItemId();
                            if (self::is_on_server() || $this->settings_info["barcode_type"] == "CODE128C") {
                                $info["barcode"] = $this->settings_info["starting_barcode"] . "" . str_pad((int) $barcode_key[0]["mid"] + 1, 7, "0", STR_PAD_LEFT);
                            } else {
                                $info["barcode"] = (int) $barcode_key[0]["mid"] + 1;
                            }
                            $last_insert_id = $items->bulk_item($info);
                            $logs_info = array();
                            $logs_info["operator_id"] = $_SESSION["id"];
                            $logs_info["related_to_item_id"] = $last_insert_id;
                            $logs_info["description"] = "New Item (IT-" . $last_insert_id . ") has been added";
                            $logs_info["log_type"] = 1;
                            $logs_info["other_info"] = "";
                            $global_logs->add_global_log($logs_info);
                            $items->sync_item_with_store($_SESSION["store_id"]);
                            $qty_info = array();
                            $qty_info["qty"] = $default_qty;
                            $qty_info["store_id"] = $_SESSION["store_id"];
                            $qty_info["item_id"] = $last_insert_id;
                            $qty_info["source"] = "manual";
                            $store->add_qty($qty_info);
                        }
                    }
                } else {
                    if (!isset($_POST["bulk_items_select_colors"]) && isset($_POST["bulk_items_select_sizes"])) {
                        for ($j = 0; $j < count($_POST["bulk_items_select_sizes"]); $j++) {
                            $info["color_text_id"] = 1;
                            $info["size_id"] = $_POST["bulk_items_select_sizes"][$j];
                            $info["item_group"] = $item_info[0]["item_group"];
                            if ($items->if_bulk_accepted($info)) {
                                $barcode_key = $items->getMaxItemId();
                                if (self::is_on_server() || $this->settings_info["barcode_type"] == "CODE128C") {
                                    $info["barcode"] = $this->settings_info["starting_barcode"] . "" . str_pad((int) $barcode_key[0]["mid"] + 1, 7, "0", STR_PAD_LEFT);
                                } else {
                                    $info["barcode"] = (int) $barcode_key[0]["mid"] + 1;
                                }
                                $last_insert_id = $items->bulk_item($info);
                                $logs_info = array();
                                $logs_info["operator_id"] = $_SESSION["id"];
                                $logs_info["related_to_item_id"] = $last_insert_id;
                                $logs_info["description"] = "New Item (IT-" . $last_insert_id . ") has been added";
                                $logs_info["log_type"] = 1;
                                $logs_info["other_info"] = "";
                                $global_logs->add_global_log($logs_info);
                                $items->sync_item_with_store($_SESSION["store_id"]);
                                $qty_info = array();
                                $qty_info["qty"] = $default_qty;
                                $qty_info["store_id"] = $_SESSION["store_id"];
                                $qty_info["item_id"] = $last_insert_id;
                                $qty_info["source"] = "manual";
                                $store->add_qty($qty_info);
                            }
                        }
                    }
                }
            }
        }
        $colors = $this->model("colors");
        $colors_info = $colors->getColorsText();
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $info["shortcut_type"] = filter_input(INPUT_POST, "shortcut_type", FILTER_SANITIZE_NUMBER_INT);
        $info["shortcut_items_qty"] = filter_input(INPUT_POST, "shortcut_items_qty", self::conversion_php_version_filter());
        if (0 < $info["shortcut_type"]) {
            $shortcut_items_qty = explode(",", $info["shortcut_items_qty"]);
            if (isset($_POST["bulk_items_select_colors"]) && isset($_POST["bulk_items_select_sizes"])) {
                for ($i = 0; $i < count($_POST["bulk_items_select_colors"]); $i++) {
                    $info_sh = array();
                    $info_sh["shortcut_name"] = $item_info[0]["description"] . " - " . $colors_info_label[$_POST["bulk_items_select_colors"][$i]];
                    $info_sh["derived_from_group"] = $item_info[0]["item_group"];
                    $return_id = $shortcuts->add_new_shortcut($info_sh);
                    for ($k = 0; $k < count($_POST["bulk_items_select_sizes"]); $k++) {
                        $shortcuts->add_new_item_qty_to_shortcut($return_id, $prepare_for_shortcuts[$_POST["bulk_items_select_colors"][$i]][$k]["item_id"], $shortcut_items_qty[$k]);
                    }
                }
            }
        }
        echo json_encode(array());
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
    public function items_view()
    {
        self::giveAccessTo();
        $data = array();
        $data["enable_wholasale"] = $this->settings_info["enable_wholasale"];
        $data["usdlbp_rate"] = $this->settings_info["usdlbp_rate"];
        $this->view("items_ajax", $data);
    }
    public function getAllItems_Ajax($store_id = 0, $_category_id, $_sub_category_id, $_itemboxes, $_supplier_id)
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $data_array["data"] = array();
        $predefined_columns = array();
        $predefined_columns[0] = "item_id";
        $predefined_columns[1] = "supplier_reference";
        $predefined_columns[2] = "barcode";
        $predefined_columns[3] = "desc_sku";
        $predefined_columns[4] = "size_label";
        $predefined_columns[5] = "color_id";
        $predefined_columns[6] = "color_text_label";
        $predefined_columns[7] = "final_cost";
        $predefined_columns[8] = "price_after_discount";
        $predefined_columns[9] = "last_price_whole_price";
        $predefined_columns[10] = "margin_profit";
        $predefined_columns[11] = "material_label";
        $filter = array();
        $filter["start"] = $_POST["start"];
        $filter["row_per_page"] = $_POST["length"];
        $filter["columns"] = $predefined_columns;
        $columnIndex = $_POST["order"][0]["column"];
        $filter["col_sort_index"] = $columnIndex;
        $filter["col_sort"] = $predefined_columns[$columnIndex];
        $filter["order_by"] = $_POST["order"][0]["dir"];
        $filter["items_vat"] = $this->settings_info["vat"];
        $setting = self::getSettings();
        $filter["enable_wholasale"] = $setting["enable_wholasale"];
        if (isset($_POST["search"]["value"])) {
            $filter["search_filters"] = filter_var($_POST["search"]["value"], self::conversion_php_version_filter());
        } else {
            $filter["search_filters"] = "";
        }
        $filter["search_col_filters"] = array();
        if (isset($_POST["search_col_filters"])) {
            $filter["search_col_filters"] = $_POST["search_col_filters"];
        }
        $items->sync_item_with_store($_SESSION["store_id"]);
        $category_id = filter_var($_category_id, FILTER_SANITIZE_NUMBER_INT);
        $sub_category_id = filter_var($_sub_category_id, FILTER_SANITIZE_NUMBER_INT);
        $itemboxes = filter_var($_itemboxes, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $tables_info = $items->getAllItems_withfilter_Ajax($category_id, $sub_category_id, $itemboxes, $supplier_id, $filter, 0);
        $all_item_qty_in_store = $items->get_all_item_qty_in_store($_SESSION["store_id"]);
        $qty = array();
        for ($i = 0; $i < count($all_item_qty_in_store); $i++) {
            $qty[$all_item_qty_in_store[$i]["item_id"]] = $all_item_qty_in_store[$i]["quantity"];
        }
        $all_item_qty_in_store_by_group = $items->get_all_item_qty_in_store_by_group($store_id);
        $qty_group = array();
        for ($i = 0; $i < count($all_item_qty_in_store_by_group); $i++) {
            $qty_group[$all_item_qty_in_store_by_group[$i]["item_id"]] = $all_item_qty_in_store_by_group[$i]["quantity"];
        }
        $data_array["data"] = array();
        $all_boxes_qty = self::_getAllBoxesItemsQty();
        $all_item_group = $items->getAllItemsByGroup_onlyGroup();
        $group_ids = array();
        for ($i = 0; $i < count($all_item_group); $i++) {
            $group_ids[$all_item_group[$i]["item_group"]] = $all_item_group[$i]["item_group"];
        }
        for ($i = 0; $i < count($tables_info); $i++) {
            $tmp = array();
            for ($k = 0; $k < count($filter["columns"]); $k++) {
                if ($filter["columns"][$k] == "item_id") {
                    if (isset($group_ids[$tables_info[$i]["item_group"]])) {
                        array_push($tmp, self::idFormat_item($tables_info[$i]["item_id"]) . " <i class='glyphicon glyphicon-folder-open' style='cursor:pointer' onclick='BulkItem(" . $tables_info[$i]["id"] . ")'></i>");
                    } else {
                        array_push($tmp, self::idFormat_item($tables_info[$i]["item_id"]));
                    }
                } else {
                    if ($filter["columns"][$k] == "supplier_reference") {
                        array_push($tmp, self::idFormat_supplier($tables_info[$i][$filter["columns"][$k]]));
                    } else {
                        if ($filter["columns"][$k] == "barcode") {
                            if (strlen($tables_info[$i]["barcode"]) < 5) {
                                array_push($tmp, sprintf("%05s", $tables_info[$i][$filter["columns"][$k]]));
                            } else {
                                array_push($tmp, $tables_info[$i][$filter["columns"][$k]]);
                            }
                        } else {
                            if ($filter["columns"][$k] == "final_cost") {
                                array_push($tmp, self::value_format_custom(floatval($tables_info[$i][$filter["columns"][$k]]), $setting));
                            } else {
                                if ($filter["columns"][$k] == "margin_profit") {
                                    array_push($tmp, number_format($tables_info[$i][$filter["columns"][$k]], 2) . " %");
                                } else {
                                    array_push($tmp, $tables_info[$i][$filter["columns"][$k]]);
                                }
                            }
                        }
                    }
                }
            }
            if ($_SESSION["role"] == 1) {
                if ($tables_info[$i]["is_composite"] == 0) {
                    if (isset($group_ids[$all_item_group["item_group"]])) {
                        array_push($tmp, (double) $qty_group[$tables_info[$i]["id"]]);
                    } else {
                        array_push($tmp, "<button type='button' class='btn btn-info btn-xs btn-full-wd' onclick='addItemToStoreByID(" . $tables_info[$i]["id"] . ")'>" . (double) $qty[$tables_info[$i]["id"]] . "</button>");
                    }
                } else {
                    if (1 < count($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]])) {
                        array_push($tmp, "<span class='show_complex' onclick='show_complex(" . $tables_info[$i]["id"] . ")' >complex</span>");
                    } else {
                        if ($all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0] == 0) {
                            $bx_nb = 0;
                            array_push($tmp, $bx_nb);
                        } else {
                            $bx_nb = $qty[$all_boxes_qty["items_ids"][$tables_info[$i]["id"]][0]] / $all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0];
                            $bx_remain = $qty[$all_boxes_qty["items_ids"][$tables_info[$i]["id"]][0]] % $all_boxes_qty["composite_nb"][$tables_info[$i]["id"]][0];
                            array_push($tmp, (int) $bx_nb . " B & " . self::value_format_custom($bx_remain, $this->settings_info) . " It");
                        }
                    }
                }
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        $tables_info_cnt = $items->getAllItems_withfilter_Ajax($category_id, $sub_category_id, $itemboxes, $supplier_id, $filter, 1);
        $draw = $_POST["draw"];
        $response = array("draw" => $draw, "recordsTotal" => count($tables_info_cnt), "recordsFiltered" => count($tables_info_cnt), "data" => $data_array["data"]);
        echo json_encode($response);
    }
    public function search($_search, $_page)
    {
        $search = filter_var($_search, self::conversion_php_version_filter());
        $page = filter_var($_page, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $colors = $this->model("colors");
        $sizes = $this->model("sizes");
        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
        $colors_array = array();
        $sizes_array = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_array[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_array[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        if (isset($this->settings_info["do_not_sell_boxes"])) {
            if ($this->settings_info["do_not_sell_boxes"] == 0) {
                $results = $items->search($search, $page, 20);
            } else {
                $results = $items->search_but_no_boxes($search, $page, 20);
            }
        } else {
            $results = $items->search($search, $page, 20);
        }
        $return = array();
        $return["results"] = array();
        $index = 0;
        foreach ($results as $result) {
            $text = $result["description"];
            if (0 < strlen($result["barcode"])) {
                $text .= " - " . $result["barcode"];
            }
            if (0 < strlen($result["barcode"])) {
                $text .= " - " . $result["barcode"];
            }
            if (0 < strlen($result["sku_code"])) {
                $text .= " - " . $result["sku_code"];
            }
            if (isset($sizes_array[$result["size_id"]]) && 0 < strlen($sizes_array[$result["size_id"]])) {
                $text .= " - " . $sizes_array[$result["size_id"]];
            }
            if (isset($colors_array[$result["color_text_id"]]) && 0 < strlen($colors_array[$result["color_text_id"]])) {
                $text .= " - " . $colors_array[$result["color_text_id"]];
            }
            if (0 < $result["complex_item_id"]) {
                $text .= " <b>(Composed)</b>";
            }
            $return["results"][$index] = array("id" => $result["id"], "text" => $text);
            $index++;
        }
        if (count($results) == 20) {
            $return["pagination"]["more"] = $items->search($search, $page, 20, true);
        } else {
            $return["pagination"]["more"] = false;
        }
        echo json_encode($return);
    }
}

?>