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
class complex_items extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function get_needed_data()
    {
        self::giveAccessTo();
        $users = $this->model("user");
        $info["employees"] = $users->getAllUsers();
        echo json_encode($info);
    }
    public function delete_ci_item($_ci_item_id)
    {
        $ci_item_id = filter_var($_ci_item_id, self::conversion_php_version_filter());
        $complexItems = $this->model("complexItems");
        $complexItems->deleteCIItem($ci_item_id);
        echo json_encode(array("success" => true));
    }
    public function duplicateCI($_complex_item_id)
    {
        $complex_item_id = filter_var($_complex_item_id, FILTER_SANITIZE_NUMBER_INT);
        $complexItems = $this->model("complexItems");
        $newId = $complexItems->duplicateCI($complex_item_id);
        echo json_encode(array("complex_item_id" => $newId));
    }
    public function get_all_items_in_complex_item($_complex_item_id)
    {
        $complex_item_id = filter_var($_complex_item_id, FILTER_SANITIZE_NUMBER_INT);
        $complexItems = $this->model("complexItems");
        $items = $complexItems->getItems($complex_item_id);
        $return = array();
        $return["data"] = array();
        foreach ($items as $item) {
            $tmp = array();
            array_push($tmp, $item["id"]);
            array_push($tmp, $item["item_id"]);
            array_push($tmp, $item["barcode"]);
            array_push($tmp, $item["description"]);
            array_push($tmp, "<input  class='minv_des' type='text' id='ci_item_desc_" . $item["id"] . "' value='" . $item["additional_description"] . "' />");
            array_push($tmp, "<input readonly  class='minvread form-control cleavesf5 item_cost' type='text' style='width:100%!important' id='ci_item_cist" . $item["id"] . "' value='" . ($_SESSION["hide_critical_data"] ? 0 : $item["buying_cost"]) . "' />");
            array_push($tmp, "<input readonly  class='minvread form-control cleavesf5' style='width:100%!important' type='text' id='ci_item_price_" . $item["id"] . "' value='" . floatval($item["selling_price"]) . "' />");
            array_push($tmp, "<input  class='minv' onchange='update_ci_item_data(" . $item["id"] . ")' type='number' id='ci_qty_" . $item["id"] . "' value='" . floatval($item["qty"]) . "' />");
            array_push($tmp, "<input  readonly class='minvread cleavesf5 cost_per_item form-control' style='width:100%!important'  type='text' id='ci_item_tc_" . $item["id"] . "' value='" . ($_SESSION["hide_critical_data"] ? 0 : $item["final_cost"]) . "' />");
            array_push($tmp, "<input readonly class='minvread cleavesf5 total_per_item form-control' style='width:100%!important'  type='text' id='ci_item_tp_" . $item["id"] . "' value='" . $item["final_price"] . "' />");
            array_push($tmp, "<input readonly id='ci_item_profit_" . $item["id"] . "' class='minvread cleavesf3 single_item_profit form-control'  style='width:100%!important' value='" . ($_SESSION["hide_critical_data"] ? 0 : floatval($item["profit"])) . "' />");
            array_push($tmp, "");
            array_push($return["data"], $tmp);
        }
        echo json_encode($return);
    }
    public function generate_empty_complex_item()
    {
        $return = array();
        $complexItems = $this->model("complexItems");
        $categoriesModel = $this->model("categories");
        $return["categories"] = $categoriesModel->getAllParentCategories();
        $return["subcategories"] = $categoriesModel->getAllCategories();
        $newId = $complexItems->generateEmpty(1);
        $return["new_id"] = $newId;
        echo json_encode($return);
    }
    public function updateSingleItem($_ci_item_id)
    {
        $data["description"] = filter_input(INPUT_POST, "description", self::conversion_php_version_filter());
        $data["qty"] = filter_input(INPUT_POST, "qty", FILTER_SANITIZE_NUMBER_INT);
        $data["price"] = filter_input(INPUT_POST, "price", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $ci_item_id = filter_var($_ci_item_id, FILTER_SANITIZE_NUMBER_INT);
        $complexItems = $this->model("complexItems");
        $result = $complexItems->updateCIItemData($ci_item_id, $data);
        $result["profit"] = $_SESSION["hide_critical_data"] ? 0 : $result["profit"];
        if ($_SESSION["hide_critical_data"]) {
            $result["buying_cost"] = 0;
            $result["final_cost"] = 0;
        }
        echo json_encode(array("ci_item" => $result));
    }
    public function getAllComplexItemsDateRange($_date, $_status, $_filter_user, $_items)
    {
        $date = filter_var($_date, self::conversion_php_version_filter());
        $filter_user = filter_var($_filter_user, FILTER_SANITIZE_NUMBER_INT);
        $complexItems = $this->model("complexItems");
        $filter_status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
        $items = filter_var($_items, self::conversion_php_version_filter());
        if ($items) {
            $items = explode(",", $items);
        }
        $date_range_tmp = explode(" ", $date ? $date : "");
        $date_range[0] = $date == "today" ? date("Y-m-1") : date("Y-m-d", strtotime(trim($date_range_tmp[0])));
        $date_range[1] = $date == "today" ? date("Y-m-d") : date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        $filters = array();
        $filters["date_range"] = $date_range;
        $filters["user"] = $filter_user;
        $filters["status"] = $filter_status;
        $filters["items"] = $items;
        $filters["type"] = 1;
        $complexItemsArray = $complexItems->getAllComplexItemsFiltered($filters);
        $data_array["data"] = array();
        if ($this->settings_info["show_currency_in_report"] == 0) {
            $this->settings_info["default_currency_symbol"] = "";
        }
        foreach ($complexItemsArray as $complexItem) {
            $tmp = array();
            array_push($tmp, self::idFormat_offer_package($complexItem["id"]));
            array_push($tmp, $complexItem["name"]);
            array_push($tmp, $complexItem["barcode"]);
            array_push($tmp, $complexItem["username"]);
            array_push($tmp, self::date_format($complexItem["creation_date"]));
            array_push($tmp, $complexItem["items_count"]);
            array_push($tmp, self::value_format_custom_no_currency($complexItem["sub_total"], $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($complexItem["discount"], $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($complexItem["total"], $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($complexItem["profit"], $this->settings_info));
            array_push($tmp, "");
            array_push($tmp, $complexItem["deleted"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getComplexItem_ItemsDetails($_complex_item_id)
    {
        $complex_item_id = filter_var($_complex_item_id, FILTER_SANITIZE_NUMBER_INT);
        $complexItems = $this->model("complexItems");
        $complexItem = $complexItems->get($complex_item_id);
        if ($_SESSION["hide_critical_data"]) {
            $complexItem["profit"] = 0;
            $complexItem["cost"] = 0;
        }
        $categoriesModel = $this->model("categories");
        $categories = $categoriesModel->getAllParentCategories();
        $subcategories = $categoriesModel->getAllCategories();
        echo json_encode(array("complex_item" => $complexItem, "categories" => $categories, "subcategories" => $subcategories));
    }
    public function save_manual_complex_item_items()
    {
    }
    public function save_manual_complex_item()
    {
    }
    public function addItemsTocomplexitem_manual($_complex_item_id, $_item_id)
    {
        $complex_item_id = filter_var($_complex_item_id, FILTER_SANITIZE_NUMBER_INT);
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $complexItems = $this->model("complexItems");
        $itemsModel = $this->model("items");
        $item_details = $itemsModel->get_item($item_id);
        $newItem_id = 0;
        if (0 < $item_details[0]["complex_item_id"]) {
            $all_items_in_complex_item = $complexItems->get_item_in_complex_items($item_details[0]["complex_item_id"]);
            for ($i = 0; $i < count($all_items_in_complex_item); $i++) {
                $newItem_id = $complexItems->addItems($complex_item_id, $all_items_in_complex_item[$i]["item_id"]);
            }
        } else {
            $newItem_id = $complexItems->addItems($complex_item_id, $item_id);
        }
        echo $newItem_id;
    }
    public function delete_complex_item($_complex_item_id)
    {
    }
    public function updateCI($_complex_item_id)
    {
        $complex_item_id = filter_var($_complex_item_id, FILTER_SANITIZE_NUMBER_INT);
        $data = filter_input_array(INPUT_POST, array("note" => self::conversion_php_version_filter(), "discount" => array("filter" => FILTER_SANITIZE_NUMBER_FLOAT, "flags" => FILTER_FLAG_ALLOW_FRACTION), "barcode" => self::conversion_php_version_filter(), "category_id" => FILTER_SANITIZE_NUMBER_INT, "subcategory_id" => FILTER_SANITIZE_NUMBER_INT, "name" => self::conversion_php_version_filter()));
        $complexItems = $this->model("complexItems");
        $complexItems->updateCI($complex_item_id, $data);
        echo json_encode(array("success" => true));
    }
    public function deleteCI($_complex_item_id)
    {
        $complex_item_id = filter_var($_complex_item_id, FILTER_SANITIZE_NUMBER_INT);
        $complexItems = $this->model("complexItems");
        $result = $complexItems->deleteCI($complex_item_id);
        echo json_encode(array("success" => $result));
    }
}

?>