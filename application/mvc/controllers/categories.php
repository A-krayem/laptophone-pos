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
class categories extends Controller
{
    public $licenseExpired = false;
    public $settings_info = array();
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function _default()
    {
        self::giveAccessTo();
        $data["enable_wholasale"] = $this->settings_info["enable_wholasale"];
        $data["vat"] = $this->settings_info["vat"];
        $data["print_barcode_in_browser"] = $this->settings_info["print_barcode_in_browser"];
        $data["mobile_shop"] = $this->settings_info["mobile_shop"];
        $data["enable_new_multibranches"] = 0;
        if (isset($this->settings_info["enable_new_multibranches"])) {
            $data["enable_new_multibranches"] = $this->settings_info["enable_new_multibranches"];
        }
        $this->view("categories", $data);
    }
    public function move_subcategory_action($id_from_, $id_to_)
    {
        $categories = $this->model("categories");
        $id_from = filter_var($id_from_, FILTER_SANITIZE_NUMBER_INT);
        $id_to = filter_var($id_to_, FILTER_SANITIZE_NUMBER_INT);
        $categories->move_subcategory_action($id_from, $id_to);
        echo json_encode(array());
    }
    public function delete_category($id_)
    {
        self::giveAccessTo();
        $categories = $this->model("categories");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $return["status"] = $categories->delete_category($id);
        echo json_encode($return);
    }
    public function delete_parent_category($id_)
    {
        self::giveAccessTo();
        $categories = $this->model("categories");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $return["status"] = $categories->delete_parent_category($id);
        echo json_encode($return);
    }
    public function add_new_parent_category()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["cat_desc"] = filter_input(INPUT_POST, "subcat_desc", self::conversion_php_version_filter());
        $categories = $this->model("categories");
        $return = array();
        $return["exist"] = $categories->parent_category_is_exist($info["cat_desc"]);
        if (0 < $return["exist"]) {
            $return["id"] = 0;
            $return["cat_desc"] = "";
            echo json_encode($return);
            exit;
        }
        $info_return = NULL;
        if ($info["id_to_edit"] == 0) {
            $info_return = $categories->add_new_parent_category($info);
        } else {
            $categories->update_parent_category($info);
        }
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        $return["cat_desc"] = $info["cat_desc"];
        echo json_encode($return);
    }
    public function add_new_category()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["cat_desc"] = filter_input(INPUT_POST, "cat_desc", self::conversion_php_version_filter());
        $info["parent_cat_id"] = filter_input(INPUT_POST, "parent_cat_id", FILTER_SANITIZE_NUMBER_INT);
        $categories = $this->model("categories");
        $return = array();
        $return["exist"] = $categories->subcategory_is_exist($info["cat_desc"], $info["parent_cat_id"]);
        if (0 < $return["exist"]) {
            $return["id"] = 0;
            $return["cat_desc"] = "";
            echo json_encode($return);
            exit;
        }
        $info_return = NULL;
        if ($info["id_to_edit"] == 0) {
            $info_return = $categories->add_new_category($info);
        } else {
            $categories->update_category($info);
        }
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        $return["cat_desc"] = $info["cat_desc"];
        echo json_encode($return);
    }
    public function get_category($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $categories = $this->model("categories");
        $info = $categories->get_category($id);
        echo json_encode($info);
    }
    public function get_parent_category($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $categories = $this->model("categories");
        $info = $categories->get_parent_category($id);
        echo json_encode($info);
    }
    public function getCategories()
    {
        self::giveAccessTo();
        $info = array();
        $categories = $this->model("categories");
        $info["cat"] = $categories->getAllCategories();
        $info["catp"] = $categories->getAllParentCategories();
        echo json_encode($info);
    }
    public function getAllCategoriesByParent($id_, $_store_id)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        $categories = $this->model("categories");
        $info = $categories->getAllCategoriesByParent($id);
        $info_categories_items = $categories->getTotalItems($id);
        $categories_info = array();
        for ($i = 0; $i < count($info_categories_items); $i++) {
            $categories_info[$info_categories_items[$i]["item_category"]] = $info_categories_items[$i]["num"];
        }
        $info_categories_items_sum = $categories->getAllSubCategoriesQty($store_id);
        $categories_info_qty = array();
        for ($i = 0; $i < count($info_categories_items_sum); $i++) {
            $categories_info_qty[$info_categories_items_sum[$i]["item_category"]] = $info_categories_items_sum[$i]["sum_qty"];
        }
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_category($info[$i]["id"]));
            array_push($tmp, $info[$i]["description"]);
            if (isset($categories_info[$info[$i]["id"]])) {
                array_push($tmp, $categories_info[$info[$i]["id"]]);
            } else {
                array_push($tmp, 0);
            }
            if (isset($categories_info_qty[$info[$i]["id"]])) {
                array_push($tmp, floor($categories_info_qty[$info[$i]["id"]]));
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
    public function move_subcategory_to($_subcategory_id)
    {
        $subcategory_id = filter_var($_subcategory_id, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $categories = $this->model("categories");
        $return["all_sub_categories"] = $categories->getAllCategories();
        $return["available_count"] = $categories->items_count_in_subcategory($subcategory_id);
        echo json_encode($return);
    }
    public function getAllParentCategories()
    {
        self::giveAccessTo();
        $categories = $this->model("categories");
        $info = $categories->getAllParentCategories();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_parentcategory($info[$i]["id"]));
            array_push($tmp, $info[$i]["name"]);
            $totalSub = $categories->getTotalSubCategories($info[$i]["id"]);
            array_push($tmp, $totalSub[0]["num"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllPCat()
    {
        self::giveAccessTo();
        $categories = $this->model("categories");
        $info = $categories->getAllParentCategories();
        echo json_encode($info);
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>