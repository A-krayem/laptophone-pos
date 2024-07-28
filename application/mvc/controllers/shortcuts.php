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
class shortcuts extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
    }
    public function import_shortcut_to_stock($_shortcut_id, $_stock_qty, $_p2)
    {
        $shortcuts = $this->model("shortcuts");
        $store = $this->model("store");
        $shortcut_id = filter_var($_shortcut_id, FILTER_SANITIZE_NUMBER_INT);
        $stock_qty = filter_var($_stock_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $all_items = $shortcuts->get_all_items_in_shortcut($shortcut_id);
        for ($i = 0; $i < count($all_items); $i++) {
            $info_add_qty = array();
            $info_add_qty["qty"] = $all_items[$i]["qty"] * $stock_qty;
            $info_add_qty["item_id"] = $all_items[$i]["item_id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "manual";
            $store->add_qty($info_add_qty);
        }
        echo json_encode(array());
    }
    public function add_new_shortcut()
    {
        $shortcuts = $this->model("shortcuts");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["to_add_to_shortut"] = filter_input(INPUT_POST, "to_add_to_shortut", self::conversion_php_version_filter());
        $info["shortcut_name"] = filter_input(INPUT_POST, "shortcut_name", self::conversion_php_version_filter());
        $info["derived_from_group"] = 0;
        $return = array();
        if ($info["id_to_edit"] == 0) {
            $return["id"] = $shortcuts->add_new_shortcut($info);
            if (0 < strlen($info["to_add_to_shortut"])) {
                $items_array = explode(",", $info["to_add_to_shortut"]);
                for ($i = 0; $i < count($items_array); $i++) {
                    $shortcuts->add_item_to_shortcut($return["id"], $items_array[$i]);
                }
            }
        } else {
            $return["id"] = $info["id_to_edit"];
        }
        echo json_encode($return);
    }
    public function add_item_to_shortcut($_item_id, $_shortcut_id, $_qty)
    {
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $shortcut_id = filter_var($_shortcut_id, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $shortcuts = $this->model("shortcuts");
        $shortcuts->add_new_item_qty_to_shortcut($shortcut_id, $item_id, $qty);
        echo json_encode(array());
    }
    public function delete_shortcut($id)
    {
        self::giveAccessTo(array(2, 4));
        $shortcuts = $this->model("shortcuts");
        $shortcuts->delete_shortcut($id);
        echo json_encode(array());
    }
    public function set_group_as_shortcut($_item_id)
    {
        self::giveAccessTo(array(2, 4));
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $shortcuts = $this->model("shortcuts");
        $return_shortcut_id = $shortcuts->set_group_as_shortcut($item_id);
        echo json_encode(array($return_shortcut_id));
    }
    public function delete_item_from_shortcut($_id)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $shortcuts = $this->model("shortcuts");
        $shortcuts->delete_item_from_shortcut($id);
        echo json_encode(array());
    }
    public function update_item_qty_shortcut($_id, $_qty)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $shortcuts = $this->model("shortcuts");
        $shortcuts->update_item_qty_shortcut($id, $qty);
        echo json_encode(array());
    }
    public function get_all_shortcuts_by_group($_id)
    {
        $data_array["data"] = array();
        $shortcuts = $this->model("shortcuts");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $shortcuts_data = $shortcuts->get_all_shortcuts_by_group($id);
        for ($i = 0; $i < count($shortcuts_data); $i++) {
            $tmp = array();
            array_push($tmp, $shortcuts_data[$i]["id"]);
            array_push($tmp, $shortcuts_data[$i]["description"]);
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_shortcuts()
    {
        $data_array["data"] = array();
        $shortcuts = $this->model("shortcuts");
        $shortcuts_data = $shortcuts->get_all_shortcuts();
        $shortcuts_total_qty = $shortcuts->get_total_qty();
        $shortcuts_total_qty_array = array();
        for ($i = 0; $i < count($shortcuts_total_qty); $i++) {
            $shortcuts_total_qty_array[$shortcuts_total_qty[$i]["shortcut_id"]] = $shortcuts_total_qty[$i]["total_stock"];
        }
        for ($i = 0; $i < count($shortcuts_data); $i++) {
            $tmp = array();
            array_push($tmp, $shortcuts_data[$i]["id"]);
            array_push($tmp, $shortcuts_data[$i]["description"]);
            if (isset($shortcuts_total_qty_array[$shortcuts_data[$i]["id"]])) {
                array_push($tmp, floatval($shortcuts_total_qty_array[$shortcuts_data[$i]["id"]]));
            } else {
                array_push($tmp, 0);
            }
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_items_to_add_to_shortcut($_shortcut_id)
    {
        self::giveAccessTo(array(2, 4));
        $shortcut_id = filter_var($_shortcut_id, FILTER_SANITIZE_NUMBER_INT);
        $data_array["data"] = array();
        $shortcuts = $this->model("shortcuts");
        $items = $shortcuts->get_all_items_to_add_to_shortcut($shortcut_id);
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
        for ($i = 0; $i < count($items); $i++) {
            $tmp = array();
            $color_size = "";
            if ($items[$i]["size_id"] == NULL) {
                $color_size .= "";
            } else {
                $color_size .= $sizes_info_label[$items[$i]["size_id"]] . " ";
            }
            if (!is_null($items[$i]["color_text_id"])) {
                $color_size .= $colors_info_label[$items[$i]["color_text_id"]];
            } else {
                $color_size .= "";
            }
            array_push($tmp, $items[$i]["id"]);
            array_push($tmp, $items[$i]["barcode"]);
            array_push($tmp, $items[$i]["description"] . " " . $color_size);
            array_push($tmp, "<input class='sitem_class' type='number' id='shit_" . $items[$i]["id"] . "' value='0' />");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_all_items_in_shortcut($_id)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $data_array["data"] = array();
        $shortcuts = $this->model("shortcuts");
        $colors = $this->model("colors");
        $colors_info = $colors->getColorsText();
        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }
        $sizes = $this->model("sizes");
        $sizes_info = $sizes->getSizes();
        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }
        $shortcuts_items = $shortcuts->get_all_items_in_shortcut($id);
        for ($i = 0; $i < count($shortcuts_items); $i++) {
            $tmp = array();
            $color_size = "";
            if ($shortcuts_items[$i]["size_id"] == NULL) {
                $color_size .= "";
            } else {
                $color_size .= $sizes_info_label[$shortcuts_items[$i]["size_id"]] . " ";
            }
            if (!is_null($shortcuts_items[$i]["color_text_id"])) {
                $color_size .= $colors_info_label[$shortcuts_items[$i]["color_text_id"]];
            } else {
                $color_size .= "";
            }
            array_push($tmp, $shortcuts_items[$i]["id"]);
            array_push($tmp, $shortcuts_items[$i]["barcode"]);
            array_push($tmp, $shortcuts_items[$i]["description"] . " " . $color_size);
            array_push($tmp, "<input class='sitem_class' type='text' onchange='sitem_changed(" . $shortcuts_items[$i]["id"] . ")' id='sitem_" . $shortcuts_items[$i]["id"] . "' value='" . floor($shortcuts_items[$i]["qty"]) . "' />");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>