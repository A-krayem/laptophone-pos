<?php

class woocommerce extends Controller
{

    public $licenseExpired = false;
    public $settings_info = null;

    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }

    //

    // function to add or update an item from pos to woommerce
    public function sync_item($item_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        // check if pos item is already added to woocommerce => if yes insert else update
        $wocoommerce_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id);
        if (count($wocoommerce_item_info) == 0) { // add product

            $result_api = $api_woocommerce_class->sync_by_item_id($item_id, 0);
            if ($result_api["http_code"] === 201) {
                $response = json_decode($result_api["response"], true);

                $info = array();
                $info["item_id"] = $item_id;
                $info["product_id"] = $response["id"];
                $info["response"] = $result_api["response"];

                $woocommerce_class->add_update_woocommerce_item($info);
                // echo ($response["id"]);
                echo $result_api["response"];
            } else {

                echo 'Error adding product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
            }
        } else { // update product

            $result_api = $api_woocommerce_class->update_item_by_id($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"], 0);
            if ($result_api["http_code"] === 200) {
                $response = json_decode($result_api["response"], true);

                $info = array();
                $info["item_id"] = $item_id;
                $info["product_id"] = $response["id"];
                $info["response"] = $result_api["response"];
                $woocommerce_class->add_update_woocommerce_item($info);
                // echo ($response["id"]);
                echo $result_api["response"];
            } else {
                echo 'Erro updating product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
            }
        }
    }




    // function to delete an item from pos to woommerce
    public function delete_item($item_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        $wocoommerce_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id);
        if (count($wocoommerce_item_info) > 0) { // delete product
            // delete from woocommerce 
            $result_api = $api_woocommerce_class->delete_item_by_id($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"]);
            if ($result_api["http_code"] === 200) {
                // delete from pos woccommerce table 
                $woocommerce_class->delete_woocommerce_item($item_id);
                $response = json_decode($result_api["response"], true);
                echo $result_api["response"];
            } else {
                echo 'Error deleting product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
            }
        } else {
            echo 'Error deleting product. Product is not found.';
        }
    }


    // function to delete an item sub category from pos to woommerce
    public function delete_parent_category_id($parent_category_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $wocommerce_pos_p_cat_info = $woocommerce_class->get_woocommerce_parent_category_by_pos_cat_id($parent_category_id);
        if (count($wocommerce_pos_p_cat_info) > 0) { // delete parent category
            // delete from woocommerce 
            $result_api = $api_woocommerce_class->delete_parent_category_by_id($parent_category_id, $wocommerce_pos_p_cat_info[0]["woocommerce_parent_category_id"]);
            if ($result_api["http_code"] === 200) {
                // delete from pos woccommerce table 
                $woocommerce_class->delete_woocommerce_parent_category($parent_category_id);
                $response = json_decode($result_api["response"], true);
                echo $result_api["response"];
            } else {
                echo 'Error deleting Parent Category. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
            }
        } else {
            echo 'Error deleting Parent Category. Parent Category is not found.';
        }
    }

    // function to delete an item sub category from pos to woommerce
    public function delete_category($category_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        $woocommerce_pos_cat_info = $woocommerce_class->get_woocommerce_category_by_pos_cat_id($category_id);

        if (count($woocommerce_pos_cat_info) > 0) { // delete sub-category
            // delete from woocommerce 
            $result_api = $api_woocommerce_class->delete_category_by_id($category_id, $woocommerce_pos_cat_info[0]["woocommerce_category_id"]);

            if ($result_api["http_code"] === 200) {
                // delete from pos woccommerce table 
                $woocommerce_class->delete_woocommerce_pos_category($category_id);

                $response = json_decode($result_api["response"], true);

                echo $result_api["response"];
            } else {
                echo 'Error deleting Category. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
            }
        } else {
            echo 'Error deleting Sub-Category. Category is not found.';
        }
    }


    public function delete_all_items()
    {

        $items_class = $this->model("items");
        $all_items = $items_class->getAllItems();
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        for ($i = 0; $i < count($all_items); $i++) {
            self::delete_item($all_items[$i]["id"]);
        }
    }


    // Function is done once
    public function delete_all_parent_categories()
    {
        $categories_class = $this->model("categories");
        $all_categories = $categories_class->getAllParentCategories();
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        for ($i = 0; $i < count($all_categories); $i++) {
            self::delete_parent_category_id($all_categories[$i]["id"]);
        }
    }

    // Function is done once
    public function delete_all_sub_categories()
    {
        $categories_class = $this->model("categories");
        $all_categories = $categories_class->getAllCategories();
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        for ($i = 0; $i < count($all_categories); $i++) {
            self::delete_category($all_categories[$i]["id"]);
        }
    }



    public function get_all_items_info($_item_search, $p_cat_id, $cat_id, $is_variable_item)
    {

        $parent_category_id = filter_var($p_cat_id, FILTER_SANITIZE_NUMBER_INT);
        $category_id = filter_var($cat_id, FILTER_SANITIZE_NUMBER_INT);
        $item_search = filter_var($_item_search, self::conversion_php_version_filter());


        $filters = array();
        $filters["parent_category_id"] = $parent_category_id;
        $filters["category_id"] = $category_id;
        if ($item_search == "") {
            $item_search = -1;
        }
        $filters["item_search"] = $item_search;
        $filters["is_variable_item"] = $is_variable_item;

        $items_class = $this->model("items");
        $all_items = $items_class->getAllItems_by_filters($filters);

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

        $is_variation_item = 0;
        $woocommerce_class = $this->model("woocommerce");
        $woocommerce_items_info = $woocommerce_class->get_all_woocommerce_items($is_variation_item);
        $all_woocommerce_items = array();
        for ($i = 0; $i < count($woocommerce_items_info); $i++) {
            $all_woocommerce_items[$woocommerce_items_info[$i]["pos_item_id"]] = $woocommerce_items_info[$i]["pos_item_id"];
        }

        $data_array["data"] = array();
        for ($i = 0; $i < count($all_items); $i++) {
            $tmp = array();
            array_push($tmp, $all_items[$i]["id"]);
            array_push($tmp, $all_items[$i]["sku_code"]);
            array_push($tmp, $all_items[$i]["description"]);
            array_push($tmp, $all_items[$i]["barcode"]);
            if (isset($colors_info_label[$all_items[$i]["color_text_id"]])) {
                array_push($tmp, $colors_info_label[$all_items[$i]["color_text_id"]]);
            } else {
                array_push($tmp, "No Color");
            }

            if (isset($sizes_info_label[$all_items[$i]["size_id"]])) {
                array_push($tmp, $sizes_info_label[$all_items[$i]["size_id"]]);
            } else {
                array_push($tmp, "NO SIZE");
            }

            if (isset($all_woocommerce_items[$all_items[$i]["id"]])) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }

            array_push($tmp, "");

            array_push($data_array["data"], $tmp);
        }

        echo json_encode($data_array);
    }




    /* Applied on POS */

    public function sync_all_items($is_variable_item)
    {

        $items_class = $this->model("items");
        $filters = array();
        $filters["is_variable_item"] = $is_variable_item;

        $all_items = $items_class->getAllItems_by_filters($filters);


        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        // $result_api = $api_woocommerce_class->sync_all_items($all_items);
        // echo ($result_api);
        $return = array();
        $return["error"] = 0;
        $return["msg"] = "";
        $sync_with_quantity = 0;
        for ($i = 0; $i < count($all_items); $i++) {
            $result_api = self::sync_itemid($all_items[$i]["id"], false, $is_variable_item, $sync_with_quantity);
            if ($result_api["error"] == 1) {
                $return["error"] = $result_api["error"];
                $return["msg"] = $result_api["msg"];
                break;
            }
        }

        echo json_encode($return);
    }

    public function sync_itemid($item_id, $echoResult, $is_variable_item, $sync_with_quantity)
    {

        if ($is_variable_item == 0) {
            $result =   self::sync_simple_item($item_id, $echoResult, $is_variable_item, $sync_with_quantity);
        } else {

            $result =  self::sync_variable_item($item_id, $echoResult, $is_variable_item, $sync_with_quantity);
        }
    }
    public function sync_simple_item_old($item_id, $echoResult = true, $is_variable_item)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        // check if pos item is already added to woocommerce => if yes insert else update
        $wocoommerce_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id);
        if (count($wocoommerce_item_info) == 0) { // add product

            $result_api = $api_woocommerce_class->sync_by_item_id($item_id, $is_variable_item);
            if ($result_api["http_code"] === 201) {
                $response = json_decode($result_api["response"], true);

                $info = array();
                $info["item_id"] = $item_id;
                $info["product_id"] = $response["id"];
                $info["response"] = $result_api["response"];

                $woocommerce_class->add_update_woocommerce_item($info);
                $result_api["error"] = 0;
                // $result_api["msg"] = $result_api["response"];
                $result_api["msg"] = "Product ID " . $item_id . " has been added";


                /*  Logs */
                $wocoommerce_pos_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id);
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_item_id"] = $item_id;
                $logs_info["description"] = "Pos Item ID (" . $item_id . ") was synced into Woocommerce with  Item ID (" . $wocoommerce_pos_item_info[0]["woocommerce_item_id"] . ")";
                $logs_info["woo_item_id"] = $wocoommerce_pos_item_info[0]["woocommerce_item_id"];
                $logs_info["woo_id"] = $wocoommerce_pos_item_info[0]["id"];
                $woocommerce_class->add_woocommerce_items_logs($logs_info);
                /* END Logs */
                if ($echoResult) {
                    echo json_encode($result_api);
                } else {
                    return $result_api;
                }
            } else {
                $result_api["error"] = 1;
                // $result_api["msg"] = 'Error adding product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                $result_api["msg"] = 'Error adding product ID .' . $item_id;

                if ($echoResult) {
                    echo json_encode($result_api);
                } else {
                    return $result_api;
                }
            }
        } else { // update product

            $result_api = $api_woocommerce_class->update_item_by_id($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"], 0);
            if ($result_api["http_code"] === 200) {
                $response = json_decode($result_api["response"], true);

                $info = array();
                $info["item_id"] = $item_id;
                $info["product_id"] = $response["id"];
                $info["response"] = $result_api["response"];
                $woocommerce_class->add_update_woocommerce_item($info);




                /*Logs */
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_item_id"] = $item_id;
                $logs_info["description"] = "Pos Item ID (" . $item_id . ") was updated with Woocommerce Item ID (" . $wocoommerce_item_info[0]["woocommerce_item_id"] . ")";
                $logs_info["woo_item_id"] = $wocoommerce_item_info[0]["woocommerce_item_id"];
                $logs_info["woo_id"] = $wocoommerce_item_info[0]["id"];
                $woocommerce_class->add_woocommerce_items_logs($logs_info);
                /* END Logs */

                $result_api["error"] = 0;
                // $result_api["msg"] = $result_api["response"];
                $result_api["msg"] = "Product ID " . $item_id . " has been updated";
                if ($echoResult) {
                    echo json_encode($result_api);
                } else {
                    return $result_api;
                }
            } else {
                $result_api["error"] = 1;
                // $result_api["msg"] = 'Error updating product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                $result_api["msg"] = 'Error updating product ID .' . $item_id;

                if ($echoResult) {
                    echo json_encode($result_api);
                } else {
                    return $result_api;
                }
            }
        }
    }

    public function sync_simple_item($item_id, $echoResult, $is_variable_item, $sync_with_quantity)
    {
        $return_result = array();
        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        // check if pos item is already added to woocommerce => if yes insert else update
        $wocoommerce_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id, 0);
        if (count($wocoommerce_item_info) == 0) { // add product
            if ($sync_with_quantity == 0) {
                $result_api = $api_woocommerce_class->sync_by_item_id($item_id, $is_variable_item);
            } else {
                $result_api = $api_woocommerce_class->sync_by_item_id_with_quantity($item_id, $is_variable_item);
            }
            $response = json_decode($result_api["response"], true);

            if ($result_api["http_code"] === 201) {
                $description = "Product ID " . $item_id . " has been added.";
                $return_result["error"] = 0;
                $return_result["msg"] = "Product ID " . $item_id . " has been added successfully.";
            } else {
                $return_result["error"] = 1;
                $description = 'Error adding product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                $return_result["msg"] = 'Error adding product ID .' . $item_id;
            }
        } else { // update product

            if ($sync_with_quantity == 0) {

                $result_api = $api_woocommerce_class->update_item_by_id($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"], $is_variable_item);
            } else {
                $result_api = $api_woocommerce_class->update_item_by_id_with_quantity($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"], $is_variable_item);
            }
            $response = json_decode($result_api["response"], true);
            if ($result_api["http_code"] === 200) {
                $description = "Product ID " . $item_id . " has been updated.";
                $return_result["error"] = 0;
                $return_result["msg"] = "Product ID " . $item_id . " has been updated successfully.";
            } else {
                $description = 'Error updating product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                $return_result["error"] = 1;
                $return_result["msg"] = 'Error updating product ID .' . $item_id;
            }
        }

        $woo_item_id = 0;
        $woo_id = 0;
        if ($return_result["error"] == 0) {

            $info = array();
            $info["item_id"] = $item_id;
            $info["product_id"] = $response["id"];
            $info["response"] = $result_api["response"];
            $woocommerce_class->add_update_woocommerce_item($info);

            $wocoommerce_pos_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id, 0);
            if (count($wocoommerce_pos_item_info) > 0) {
                $woo_item_id = $wocoommerce_pos_item_info[0]["woocommerce_item_id"];
                $woo_id = $wocoommerce_pos_item_info[0]["id"];
            }
        }


        /*  Logs */
        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION['id'];
        $logs_info["pos_item_id"] = $item_id;
        $logs_info["description"] = $description; //"Pos Item ID (" . $item_id . ") was synced into Woocommerce with  Item ID (" . $wocoommerce_pos_item_info[0]["woocommerce_item_id"] . ")";
        $logs_info["woo_item_id"] = $woo_item_id;
        $logs_info["woo_id"] = $woo_id;
        $woocommerce_class->add_woocommerce_items_logs($logs_info);
        if ($echoResult) {
            echo json_encode($return_result);
        } else {
            return $return_result;
        }
    }

    public function  sync_item_variations($woo_item_id, $item_group)
    {
        require_once 'application/mvc/controllers/variation.php';
        $variation_class = new variation();
        $variation_data = $variation_class->items_variation_details($item_group);

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woo_id = 0;
        $variation_woo_item_id = 0;
        $woocommerce_class = $this->model("woocommerce");
        $woocommerce_items_info = $woocommerce_class->get_all_woocommerce_items(1);
        $all_woocommerce_items = array();
        for ($i = 0; $i < count($woocommerce_items_info); $i++) {
            $all_woocommerce_items[$woocommerce_items_info[$i]["pos_item_id"]] = $woocommerce_items_info[$i];
        }
        $return_result = array();
        if (!empty($variation_data["variation_details"])) {
            foreach ($variation_data["variation_details"] as $variation_data) {
                $item_id =    $variation_data["pos_item_id"];
                unset($variation_data['pos_item_id']);
                if (!isset($all_woocommerce_items[$item_id])) {
                    $return_result["update"] = 0;
                    $result_api = $api_woocommerce_class->sync_product_variation($woo_item_id, ($variation_data));
                    $response = json_decode($result_api["response"], true);

                    if ($result_api["http_code"] === 201) {
                        $description = "Item Variation Product ID " . $response["id"] . " has been added.";
                        $return_result["error"] = 0;
                        $return_result["msg"] = "Product Variations of Item ID (" . $item_group . ") has been synced successfully.";
                    } else {
                        $description = "Item Variation Product wasnot added. HTTP Code: " . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                        $return_result["error"] = 1;
                        $return_result["msg"] = "Error While Syncing Product Variations of Item ID (" . $item_group . ")";
                    }
                } else {
                    $return_result["update"] = 1;

                    $result_api = $api_woocommerce_class->update_product_variation($woo_item_id, $variation_data, $all_woocommerce_items[$item_id]["woocommerce_item_id"]);
                    $response = json_decode($result_api["response"], true);
                    if ($result_api["http_code"] === 200) {
                        $description = "Item Variation Product ID " . $response["id"] . " has been updated.";
                        $return_result["error"] = 0;
                        $return_result["msg"] = "Product Variations of Item ID (" . $item_group . ") has been synced successfully.";
                        $variation_woo_item_id = $all_woocommerce_items[$item_id]["woocommerce_item_id"];
                        $woo_id = $all_woocommerce_items[$item_id]["id"];
                    } else {
                        $description = "Item Variation Product wasnot updated.  HTTP Code: " . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                        $return_result["error"] = 1;
                        $return_result["msg"] = "Error While Updating Product Variations of Item ID (" . $item_group . ")";
                    }
                }

                if ($return_result["error"] == 0) {
                    $info = array();
                    $info["item_id"] = $item_id;
                    $info["product_id"] = $response["id"];
                    $info["response"] = $result_api["response"];
                    $info["item_group"] = $item_group;
                    $info["is_item_variation"] = 1;
                    $woocommerce_class->add_update_woocommerce_item_variation($info);


                    if ($return_result["update"] == 0) {
                        $woocommerce_items_variation_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id, 1);
                        if (count($woocommerce_items_variation_info) > 0) {
                            $variation_woo_item_id = $woocommerce_items_variation_info[0]["woocommerce_item_id"];
                            $woo_id = $woocommerce_items_variation_info[0]["id"];
                        }
                    }
                }

                // /*  Logs */
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_item_id"] = $item_id;
                $logs_info["description"] = $description; //"Pos Item ID (" . $item_id . ") was synced into Woocommerce with  Item ID (" . $wocoommerce_pos_item_info[0]["woocommerce_item_id"] . ")";
                $logs_info["woo_item_id"] = $variation_woo_item_id;
                $logs_info["woo_id"] = $woo_id;
                $woocommerce_class->add_woocommerce_items_logs($logs_info);
            }
        }
        return ($return_result);
    }

    public function sync_variable_item($item_id, $echoResult, $is_variable_item, $sync_with_quantity)
    {
        $return_result = array();
        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        // check if pos item is already added to woocommerce => if yes insert else update
        $wocoommerce_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id, 0);
        if (count($wocoommerce_item_info) == 0) { // add product
            if ($sync_with_quantity == 0) {
                $result_api = $api_woocommerce_class->sync_by_item_id($item_id, $is_variable_item);
            } else {
                $result_api = $api_woocommerce_class->sync_by_item_id_with_quantity($item_id, $is_variable_item);
            }
            $response = json_decode($result_api["response"], true);

            if ($result_api["http_code"] === 201) {



                // $result_api = $api_woocommerce_class->sync_by_item_id($item_id,$is_variable_item);
                $description = "Product ID " . $item_id . " has been added.";
                $return_result["error"] = 0;
                $return_result["msg"] = "Product ID " . $item_id . " has been added successfully.";
            } else {
                $return_result["error"] = 1;
                $description = 'Error adding product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                $return_result["msg"] = 'Error adding product ID .' . $item_id;
            }
        } else { // update product

            if ($sync_with_quantity == 0) {

                $result_api = $api_woocommerce_class->update_item_by_id($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"], $is_variable_item);
            } else {
                $result_api = $api_woocommerce_class->update_item_by_id_with_quantity($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"], $is_variable_item);
            }
            $response = json_decode($result_api["response"], true);
            if ($result_api["http_code"] === 200) {

                $description = "Product ID " . $item_id . " has been updated.";
                $return_result["error"] = 0;
                $return_result["msg"] = "Product ID " . $item_id . " has been updated successfully.";
            } else {
                $description = 'Error updating product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                $return_result["error"] = 1;
                $return_result["msg"] = 'Error updating product ID .' . $item_id;
            }
        }

        $woo_item_id = 0;
        $woo_id = 0;
        if ($return_result["error"] == 0) {

            $info = array();
            $info["item_id"] = $item_id;
            $info["product_id"] = $response["id"];
            $info["response"] = $result_api["response"];
            $woocommerce_class->add_update_woocommerce_item($info);

            $wocoommerce_pos_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id, 0);
            if (count($wocoommerce_pos_item_info) > 0) {
                $woo_item_id = $wocoommerce_pos_item_info[0]["woocommerce_item_id"];
                $woo_id = $wocoommerce_pos_item_info[0]["id"];
            }
        }


        /*  Logs */
        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION['id'];
        $logs_info["pos_item_id"] = $item_id;
        $logs_info["description"] = $description; //"Pos Item ID (" . $item_id . ") was synced into Woocommerce with  Item ID (" . $wocoommerce_pos_item_info[0]["woocommerce_item_id"] . ")";
        $logs_info["woo_item_id"] = $woo_item_id;
        $logs_info["woo_id"] = $woo_id;
        $woocommerce_class->add_woocommerce_items_logs($logs_info);


        if ($return_result["error"] == 0) {
            $variation_result_api = self::sync_item_variations($response["id"], $item_id);
            if ($variation_result_api["error"] == 1) {
                $return_result["error"] = 1;
                $return_result["msg"] = $variation_result_api["msg"];
            }
        }
        if ($echoResult) {
            echo json_encode($return_result);
        } else {
            return $return_result;
        }
    }



    public function delete_itemid($item_id, $is_variable_item)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        $wocoommerce_item_info = $woocommerce_class->get_woocommerce_by_pos_item_id($item_id, 0);
        if (count($wocoommerce_item_info) > 0) { // delete product
            // delete from woocommerce 
            $result_api = $api_woocommerce_class->delete_item_by_id($item_id, $wocoommerce_item_info[0]["woocommerce_item_id"]);
            if ($result_api["http_code"] === 200) {
                // delete from pos woccommerce table 
                $woocommerce_class->delete_woocommerce_item($item_id);
                $response = json_decode($result_api["response"], true);
                $result_api["error"] = 0;
                // $result_api["msg"] = $result_api["response"];
                $result_api["msg"] = "";


                /*  Logs */
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_item_id"] = $item_id;
                $logs_info["description"] = "Delete Pos Item ID (" . $item_id . ") with Woocommerce Item ID (" . $wocoommerce_item_info[0]["woocommerce_item_id"] . ")";
                $logs_info["woo_item_id"] = $wocoommerce_item_info[0]["woocommerce_item_id"];
                $logs_info["woo_id"] = $wocoommerce_item_info[0]["id"];
                $woocommerce_class->add_woocommerce_items_logs($logs_info);
                /* END Logs */

                if ($is_variable_item == 1) {
                    $woocommerce_class->delete_item_group_variations($item_id);
                }

                echo json_encode($result_api);
            } else {
                $result_api["error"] = 1;
                // $result_api["msg"] = 'Error deleting product. HTTP Code: ' . $result_api["http_code"] . ', Response: ' . $result_api["response"];
                $result_api["msg"] = 'Error deleting product ID ' . $item_id;

                echo json_encode($result_api);
            }
        } else {
            $result_api = array();
            $result_api["error"] = 1;
            $result_api["msg"] = 'Error deleting product. Product is not found.';
            echo json_encode($result_api);
        }
    }



    public function get_all_pos_categories($p_cat_id)
    {
        $parent_category_id = filter_var($p_cat_id, FILTER_SANITIZE_NUMBER_INT);
        $woocommerce_class = $this->model("woocommerce");
        $categories = $this->model("categories");

        $filters = array();
        $filters["parent_category_id"] = $parent_category_id;

        $all_categories = $woocommerce_class->getAll_POS_CategoriesByParent($filters); // sub categories 
        $all_parent_categories = $categories->getAllParentCategories();
        $all_parent_categories_array = array();
        for ($i = 0; $i < count($all_parent_categories); $i++) {
            $all_parent_categories_array[$all_parent_categories[$i]["id"]] = $all_parent_categories[$i]["name"];
        }

        $all_woo_categories = $woocommerce_class->get_all_woocommerce_categories();
        $all_woo_categories_array = array();
        for ($i = 0; $i < count($all_woo_categories); $i++) {
            $all_woo_categories_array[$all_woo_categories[$i]["pos_category_id"]] = $all_woo_categories[$i];
        }

        $data_array["data"] = array();
        for ($i = 0; $i < count($all_categories); $i++) {
            $tmp = array();
            $isupdated_text = ($all_categories[$i]["is_synced"] == 1) ? "<small class='text-success'> - Updated</small>" : "<small class='text-danger'> - Not Updated</small>";

            array_push($tmp, $all_categories[$i]["id"]);
            array_push($tmp, $all_categories[$i]["description"]);
            array_push($tmp, ($all_categories[$i]["parent"] > 0) ? $all_parent_categories_array[$all_categories[$i]["parent"]] : "");

            array_push($tmp, (isset($all_woo_categories_array[$all_categories[$i]["id"]]) ? "<span class='text-success'>Synced</span>" . $isupdated_text : "<span class='text-danger'>Not Synced</span>" . $isupdated_text));
            array_push($tmp, "");

            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }


    public function sync_parent_categoryid($pos_parent_category_id) // add
    {

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woocommerce_class = $this->model("woocommerce");
        $wocommerce_pos_p_cat_info = $woocommerce_class->get_woocommerce_parent_category_by_pos_cat_id($pos_parent_category_id);

        $return_result = array();
        $return_result["pos_p_cat_id"] = $pos_parent_category_id;
        $return_result["woo_p_cat_id"] = 0;
        $return_result["woo_id"] = 0;
        $description = "";
        if (count($wocommerce_pos_p_cat_info) == 0) { // post parent category add p-cat 
            $result_api = $api_woocommerce_class->sync_parent_category_by_id($pos_parent_category_id);

            if ($result_api["http_code"] === 201) {
                $response = json_decode($result_api["response"], true);
                $return_result["error"] = 0;
                $return_result["update"] = 0;
                $description = "Add Pos Parent Category ID (" . $pos_parent_category_id . ") with Woocommerce Parent Category ID  " . $response["id"];
            } else {
                $return_result["error"] = 1;
                $return_result["update"] = 0;
                $description = "Error adding Pos Parent Category ID (" . $pos_parent_category_id . ") into Woocommerce.";
            }
        } else {
            $result_api = $api_woocommerce_class->update_parent_category_by_id($pos_parent_category_id, $wocommerce_pos_p_cat_info[0]["woocommerce_parent_category_id"]);
            if ($result_api["http_code"] === 200) {
                $response = json_decode($result_api["response"], true);
                $return_result["error"] = 0;
                $return_result["update"] = 1;
                $description = "Updating Pos Parent Category ID (" . $pos_parent_category_id . ") with Woocommerce Parent Category ID  " . $response["id"];
            } else {
                $return_result["error"] = 1;
                $return_result["update"] = 1;
                $description = "Error updating Pos Parent Category ID (" . $pos_parent_category_id . ")  into Woocommerce.";
            }
        }


        if ($return_result["error"] == 0) {
            $response = json_decode($result_api["response"], true);
            $info = array();
            $info["pos_p_category_id"] = $pos_parent_category_id;
            $info["woc_p_category_id"] = $response["id"];
            $info["response"] = $result_api["response"];
            $woocommerce_class->add_update_POS_parent_categories($info); // add into woo_pcat table
            if ($return_result["update"] == 0) {
                $woo_id = my_sql::get_mysqli_insert_id();
            } else {
                $woo_id = $wocommerce_pos_p_cat_info[0]["id"];
            }
            $woocommerce_class->update_is_sync_parentcategory_id($pos_parent_category_id, 1); // sync value
            $return_result["woo_p_cat_id"] = $response["id"];
            $return_result["woo_id"] = $woo_id;
        }

        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION['id'];
        $logs_info["pos_parent_category_id"] = $return_result["pos_p_cat_id"];
        $logs_info["description"] = $description;
        $logs_info["woo_parent_category_id"] = $return_result["woo_p_cat_id"];
        $logs_info["woo_id"] = $return_result["woo_id"];
        $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
        /* END Logs */
        return ($return_result);
    }

    public function sync_categoryid($pos_category_id, $echoResult = true)
    {

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woocommerce_class = $this->model("woocommerce");
        $woocommerce_pos_cat_info = $woocommerce_class->get_woocommerce_category_by_pos_cat_id($pos_category_id);

        $categories_class = $this->model("categories");
        $pos_parent_category_id = $categories_class->get_category($pos_category_id)[0]["parent"];
        $woo_parent_cat_id = 0;

        $$return_result = array();

        $return_result["pos_cat_id"] = $pos_category_id;
        $return_result["woo_cat_id"] = 0;
        $return_result["woo_id"] = 0;
        $description = "";

        if ($pos_parent_category_id > 0) {
            $result_woo_p_cat_api = self::sync_parent_categoryid($pos_parent_category_id);
            if ($result_woo_p_cat_api["error"] == 0) {
                $woo_parent_cat_id = $result_woo_p_cat_api["woo_p_cat_id"];
            }
        }

        if (count($woocommerce_pos_cat_info) == 0) { // post  category
            $result_api = $api_woocommerce_class->sync_category_by_id($pos_category_id, $woo_parent_cat_id);
            $response = json_decode($result_api["response"], true);

            if ($result_api["http_code"] === 201) {
                $return_result["error"] = 0;
                $return_result["update"] = 0;
                $return_result["msg"] = "Sub-Category ID " . $pos_category_id . " has been added.";
                $description = "Add Category ID (" . $pos_category_id . ") with Woocommerce Category ID  " . $response["id"];
            } else {
                $return_result["error"] = 1;
                $return_result["update"] = 0;
                $return_result["msg"] = 'Error adding Sub-category .' . $pos_category_id;
                $description = "Error adding Pos Category ID (" . $pos_category_id . ") into Woocommerce.";
            }
        } else { // update category
            $result_api = $api_woocommerce_class->update_category_by_id($pos_category_id, $woocommerce_pos_cat_info[0]["woocommerce_category_id"], $woo_parent_cat_id);
            $response = json_decode($result_api["response"], true);

            if ($result_api["http_code"] === 200) {
                $return_result["update"] = 1;
                $return_result["error"] = 0;
                $return_result["msg"] = "Sub-Category ID " . $pos_category_id . " has been updated.";
                $description = "Updating Category ID (" . $pos_category_id . ") with Woocommerce Category ID  " . $response["id"];
            } else {
                $return_result["update"] = 1;
                $return_result["error"] = 1;
                $return_result["msg"] = 'Error updating Sub-category.' . $pos_category_id;
                $description = "Error updating Pos Category ID (" . $pos_category_id . ")  into Woocommerce.";
            }
        }

        if ($return_result["error"] == 0) {
            $info = array();
            $info["pos_category_id"] = $pos_category_id;
            $info["woc_category_id"] = $response["id"];
            $info["response"] = $result_api["response"];
            $woocommerce_class->add_update_POS_categories($info);
            if ($return_result["update"] == 0) {
                $woo_id = my_sql::get_mysqli_insert_id();
            } else {
                $woo_id = $woocommerce_pos_cat_info[0]["id"];
            }
            $woocommerce_class->update_is_sync_category_id($pos_category_id, 1);

            $return_result["woo_id"] = $woo_id;
            $return_result["woo_cat_id"] = $response["id"];
        }
        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION['id'];
        $logs_info["pos_category_id"] = $pos_category_id;
        $logs_info["description"] = $description . ($return_result["error"] == 1 ? $response["message"] : "");
        $logs_info["woo_category_id"] = $return_result["woo_cat_id"];
        $logs_info["woo_id"] = $return_result["woo_id"];
        $woocommerce_class->add_woocommerce_categories_logs($logs_info);
        /* END Logs */


        if ($echoResult) {
            echo json_encode($return_result);
        } else {
            return $return_result;
        }
    }



    public function sync_all_categories($parent_category_id) // sub categories 
    {
        $filters = array();
        $filters["parent_category_id"] = $parent_category_id;
        $woocommerce_class = $this->model("woocommerce");

        $all_categories = $woocommerce_class->getAll_POS_CategoriesByParent($filters);

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        $return = array();
        $return["error"] = 0;
        $return["msg"] = "";
        for ($i = 0; $i < count($all_categories); $i++) {
            $result_api = self::sync_categoryid($all_categories[$i]["id"], false);
            if ($result_api["error"] == 1) {
                $return["error"] = $result_api["error"];
                $return["msg"] = $result_api["msg"];
                break;
            }
        }
        echo json_encode($return);
    }

    function get_all_woocommerce_categories_old($parent_category_id)
    {
        $all_parent_categories = self::list_all_woocommerce_parent_categories(false);
        $all_parent_categories_array = array();
        for ($i = 0; $i < count($all_parent_categories); $i++) {
            $all_parent_categories_array[$all_parent_categories[$i]["id"]] = $all_parent_categories[$i]["name"];
        }

        $page = 1;
        $per_page = 15;
        $all_categories = array();

        do {
            require_once 'application/mvc/apis/api_woocommerce.php';
            $api_woocommerce_class = new api_woocommerce();
            $result_api = $api_woocommerce_class->retrieve_all_woocategories_by_parent_id($parent_category_id, $page, $per_page);
            $http_status = $result_api["http_code"];
            $categories = json_decode($result_api["response"], true);
            if (!empty($categories) && $http_status == 200) {

                $filtered_categories = array_filter($categories, function ($category) {
                    return isset($category['parent']) && $category['parent'] !== 0;
                });
                if (!empty($filtered_categories)) {
                    $all_categories = array_merge($all_categories, $filtered_categories);
                    $page++;
                } else {
                    break;
                }
            } else {
                break;
            }
        } while (!empty($categories));
        $data_array["data"] = array();
        if ($http_status == 200) {
            for ($i = 0; $i < count($all_categories); $i++) {
                $tmp = array();
                array_push($tmp, $all_categories[$i]["id"]);
                array_push($tmp, $all_categories[$i]["parent"]);
                array_push($tmp, $all_categories[$i]["name"]);
                array_push($tmp, $all_categories[$i]["description"]);
                if (isset($all_parent_categories_array[$all_categories[$i]["parent"]])) {
                    array_push($tmp, $all_parent_categories_array[$all_categories[$i]["parent"]]);
                } else {
                    array_push($tmp, "");
                }
                array_push($tmp, "");
                array_push($data_array["data"], $tmp);
            }
        }

        echo json_encode(($data_array));
    }



    function list_all_woocommerce_parent_categories($echoResult = true)
    {
        $page = 1;
        $per_page = 15;
        $all_categories = array();
        do {
            require_once 'application/mvc/apis/api_woocommerce.php';
            $api_woocommerce_class = new api_woocommerce();
            $result_api = $api_woocommerce_class->retrieve_all_woo_parentcategories($page, $per_page);
            $categories = json_decode($result_api["response"], true);
            $http_status = $result_api["http_code"];
            if (!empty($categories) && $http_status == 200) {
                $all_categories = array_merge($all_categories, $categories);
                $page++;
            } else {
                break;
            }
        } while (!empty($categories));
        if ($echoResult) {
            echo json_encode(($all_categories));
        } else {
            return ($all_categories);
        }
    }


    function list_all_woocommerce_categories($parent_category_id, $echoResult = true)
    {
        $page = 1;
        $per_page = 15;

        $all_categories = array();

        do {
            require_once 'application/mvc/apis/api_woocommerce.php';
            $api_woocommerce_class = new api_woocommerce();
            $result_api = $api_woocommerce_class->retrieve_all_woocategories_by_parent_id($parent_category_id, $page, $per_page);
            $http_status = $result_api["http_code"];
            $categories = json_decode($result_api["response"], true);
            if (!empty($categories) && $http_status == 200) {

                $filtered_categories = array_filter($categories, function ($category) {
                    return isset($category['parent']) && $category['parent'] !== 0;
                });
                if (!empty($filtered_categories)) {
                    $all_categories = array_merge($all_categories, $filtered_categories);
                    $page++;
                } else {
                    break;
                }
            } else {
                break;
            }
        } while (!empty($categories));
        if ($echoResult) {
            echo json_encode(($all_categories));
        } else {
            return ($all_categories);
        }
    }


    public function delete_woo_categoryid($woo_category_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $return_result = array();
        if ($this->settings_info["woocommerce_update_merge_category_items"] == 1) {
            $products_category = self::get_all_items_by_category_id($woo_category_id);
        }
        $result_api = $api_woocommerce_class->delete_woocommerce_category_by_id($woo_category_id);
        $response = json_decode($result_api["response"], true);

        if ($result_api["http_code"] === 200) {
            $return_result["error"] = 0;
            $return_result["deleted"] = 1;
            $description = "Delete from Woocommerce Category ID (" . $woo_category_id . ")";
            $return_result["msg"] = "Category ID  has been deleted successfully.";
        } else {
            $return_result["error"] = 1;
            $return_result["deleted"] = 0;
            $return_result["msg"] = "Error deleting Woocommerce Category ID.";
            $description = "Error deleting from Woocommerce Category ID (" . $woo_category_id . ")." . (isset($response["message"]) ? ($response["message"]) : "");
        }





        $woo_id = 0;
        $pos_category_id = 0;

        /*  Logs for woocommmerce */
        $logs_info_ = array();
        $logs_info_["operator_id"] = $_SESSION['id'];
        $logs_info_["description"] = $description;
        $logs_info_["woo_category_id"] = $woo_category_id;

        if ($return_result["error"] == 0) {
            /*DELETE FROM DB */
            $woocommerce_pos_cat_info = $woocommerce_class->get_woo_categories_by_woo_category_id($woo_category_id);
            if (count($woocommerce_pos_cat_info) > 0) { // delete sub-category 
                $woo_id = $woocommerce_pos_cat_info[0]["id"];
                $pos_category_id = $woocommerce_pos_cat_info[0]["pos_category_id"];
                $woocommerce_class->delete_woo_category_by_woo_category_id($woo_category_id);
                if ($pos_category_id > 0) {
                    $woocommerce_class->update_is_sync_category_id($pos_category_id, 0);
                    $description = "UnSync POS Category ID (" . $woocommerce_pos_cat_info[0]["pos_category_id"] . ") with  Woocommerce Category ID (" . $woo_category_id . ") ";
                    $logs_info = array();
                    $logs_info["operator_id"] = $_SESSION['id'];
                    $logs_info["pos_category_id"] = $pos_category_id;
                    $logs_info["description"] = $description;
                    $logs_info["woo_category_id"] = $woo_category_id;
                    $logs_info["woo_id"] = $woo_id;
                    $woocommerce_class->add_woocommerce_categories_logs($logs_info);
                }
            }
            if ($this->settings_info["woocommerce_update_merge_category_items"] == 1) {

                self::resync_woo_items_by_cat_id($woo_category_id, $products_category);
            }
        }


        $logs_info_["pos_category_id"] = $pos_category_id;
        $logs_info_["woo_id"] = $woo_id;
        $woocommerce_class->add_woocommerce_categories_logs($logs_info_);
        /* END Logs */
        echo json_encode($return_result);
    }

    public function test()
    {


        // require_once 'application/mvc/apis/api_woocommerce.php';
        // $api_woocommerce_class = new api_woocommerce();



        // $result_api = $api_woocommerce_class->create_batch_parent_categories($cat);
        // $categories = json_decode($result_api["response"], true);


        // // Output category information
        // foreach ($categories as $category) {
        //     echo 'Category ID: ' . $category['id'] . '<br>';
        //     echo 'Category Name: ' . $category['name'] . '<br>';
        //     echo 'Category Slug: ' . $category['slug'] . '<br>';
        //     echo 'Category Description: ' . $category['description'] . '<br><br>';
        // }





        // Initialize variables
        $page = 1;
        $per_page = 15;

        $all_categories = array();

        do {

            require_once 'application/mvc/apis/api_woocommerce.php';
            $api_woocommerce_class = new api_woocommerce();
            $result_api = $api_woocommerce_class->retrieve_all_woocategories_by_parent_id(-1, $page, $per_page);

            // Decode the JSON response
            $categories = json_decode($result_api["response"], true);

            // Check if categories are present
            if (!empty($categories)) {
                // Add current page categories to the array
                $all_categories = array_merge($all_categories, $categories);

                // Move to the next page
                $page++;
            } else {
                // No more categories, exit the loop
                break;
            }
        } while (!empty($categories));

        // Output category information
        foreach ($all_categories as $category) {
            echo 'Category ID: ' . $category['id'] . '<br>';
            echo 'Category Name: ' . $category['name'] . '<br>';
            echo 'Category Slug: ' . $category['slug'] . '<br>';
            echo 'Category Description: ' . $category['description'] . '<br><br>';
        }
    }


    public function add_new_woo_category()
    {
        $info = array();
        $info["parent_category_id"] = filter_input(INPUT_POST, 'p_cat_id', FILTER_SANITIZE_NUMBER_INT);
        $info["description"] = filter_input(INPUT_POST, 'desc', self::conversion_php_version_filter());
        $info["name"] = filter_input(INPUT_POST, 'name', self::conversion_php_version_filter());
        $info["category_id"] = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woocommerce_class = $this->model("woocommerce");

        $result_api = $api_woocommerce_class->add_update_new_category($info);
        $return_result = array();
        $return_result["result"] = $result_api;
        $status_code = 201;
        $woo_cat_id = 0;
        if ($info["category_id"] > 0) {
            $status_code = 200;
        }

        $response = json_decode($result_api["response"], true);

        if ($result_api["http_code"] === $status_code) {
            $return_result["error"] = 0;
            if ($info["category_id"] > 0) {
                $return_result["msg"] = "Sub-Category has been updated successfully.";
                $description = "Update Woocommerce Category ID  (" . $response["id"] . ")";
            } else {
                $return_result["msg"] = "Sub-Category has been added successfully.";
                $description = "Add New Woocommerce Category ID  (" . $response["id"] . ")";
            }
            $woo_cat_id = $response["id"];
        } else {
            $return_result["error"] = 1;
            if ($info["category_id"] > 0) {
                $return_result["msg"] = "Error updating Sub-category";
                $description = "Error updating  Woocommerce Category ID. " . (isset($response["message"]) ? ($response["message"]) : "");
            } else {
                $return_result["msg"] = "Error adding Sub-category" .
                    $description = "Error adding  Woocommerce Category ID. " . (isset($response["message"]) ? ($response["message"]) : "");
            }
        }




        $sync_info = array();
        $sync_info["response"] = $result_api["response"];
        $sync_info["pos_category_id"] = 0;
        $sync_info["woc_category_id"] = $woo_cat_id;
        $sync_info["from_woocommerce"] = 1;
        $sync_info["woo_id"] = 0;
        $sync_info["parent"] = $info["parent_category_id"];

        /* Sync From Woocommerce into db */
        if ($return_result["error"] == 0) {
            $woocommerce_cat_info = $woocommerce_class->get_woo_categories_by_woo_category_id($woo_cat_id);
            if (count($woocommerce_cat_info) > 0) {
                $sync_info["woo_id"] = $woocommerce_cat_info[0]["id"];
                $sync_info["pos_category_id"] = $woocommerce_cat_info[0]["pos_category_id"];
            }
            $result = $woocommerce_class->add_update_woocommerce_categories($sync_info);
            if (!$result) {
                $return_result["error"] == 1;
                $return_result["msg"] = "Error Syncing Sub-Category " . $woo_cat_id;
                $description = "Error Syncing Woocommerce Category " . $woo_cat_id;
            }
        }

        /* END Sync From Woocommerce into db */

        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION['id'];
        $logs_info["pos_category_id"] = $sync_info["pos_category_id"];
        $logs_info["description"] = $description;
        $logs_info["woo_category_id"] = $woo_cat_id;
        $logs_info["woo_id"] = $sync_info["woo_id"];
        $woocommerce_class->add_woocommerce_categories_logs($logs_info);
        /* END Logs */

        echo json_encode($return_result);
    }


    public function add_new_woo_parent_category()
    {
        $info = array();
        $info["parent_category_id"] = filter_input(INPUT_POST, 'p_cat_id', FILTER_SANITIZE_NUMBER_INT);
        $info["description"] = filter_input(INPUT_POST, 'desc', self::conversion_php_version_filter());
        $info["name"] = filter_input(INPUT_POST, 'name', self::conversion_php_version_filter());

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woocommerce_class = $this->model("woocommerce");

        $result_api = $api_woocommerce_class->add_update_new_parent_category($info);
        $return_result = array();
        $return_result["result"] = $result_api;
        $status_code = 201;
        $woo_parent_cat_id = 0;
        if ($info["parent_category_id"] > 0) {
            $status_code = 200;
        }

        $response = json_decode($result_api["response"], true);

        if ($result_api["http_code"] === $status_code) {
            $return_result["error"] = 0;
            if ($info["parent_category_id"] > 0) {
                $return_result["msg"] = "Category has been updated successfully.";
                $description = "Update Woocommerce Parent Category ID  (" . $response["id"] . ")";
            } else {
                $return_result["msg"] = "Category has been added successfully.";
                $description = "Add New Woocommerce Parent Category ID  (" . $response["id"] . ")";
            }
            $woo_parent_cat_id = $response["id"];
        } else {
            $return_result["error"] = 1;
            if ($info["parent_category_id"] > 0) {
                $return_result["msg"] = "Error updating category";
                $description = "Error updating  Woocommerce Parent Category ID. " . (isset($response["message"]) ? ($response["message"]) : "");
            } else {
                $return_result["msg"] = "Error adding category";
                $description = "Error adding  Woocommerce Parent Category ID. " . (isset($response["message"]) ? ($response["message"]) : "");
            }
        }


        $sync_info = array();
        $sync_info["response"] = $result_api["response"];
        $sync_info["pos_p_category_id"] = 0;
        $sync_info["woc_p_category_id"] = $woo_parent_cat_id;
        $sync_info["from_woocommerce"] = 1;
        $sync_info["woo_id"] = 0;


        /* Sync From Woocommerce into db */
        if ($return_result["error"] == 0) {
            $woocommerce_pcat_info = $woocommerce_class->get_woo_pcategories_by_woo_pcategory_id($woo_parent_cat_id);
            if (count($woocommerce_pcat_info) > 0) {
                $sync_info["woo_id"] = $woocommerce_pcat_info[0]["id"];
                $sync_info["pos_p_category_id"] = $woocommerce_pcat_info[0]["pos_category_id"];
            }
            $result = $woocommerce_class->add_update_woocommerce_parent_categories($sync_info);



            if (!$result) {
                $return_result["error"] == 1;
                $return_result["msg"] = "Error Syncing Category " . $woo_parent_cat_id;
                $description = "Error Syncing Woocommerce Parent Category " . $woo_parent_cat_id;
            } else {
                $return_result["parent_categories"] = self::list_all_woocommerce_parent_categories(false);
            }
        }


        /* END Sync From Woocommerce into db */

        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION['id'];
        $logs_info["pos_parent_category_id"] = $sync_info["pos_p_category_id"];
        $logs_info["description"] = $description;
        $logs_info["woo_parent_category_id"] = $woo_parent_cat_id;
        $logs_info["woo_id"] = $sync_info["woo_id"];
        $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);

        /* END Logs */

        echo json_encode($return_result);
    }

    public function delete_batch_parent_categories()
    {



        // Set the initial page and per_page values
        $page = 1;
        $per_page = 100;
        $all_deleted_parent_categories = array();
        $all_deleted_parent_categories_ids = array();
        $all_categories = array();
        // Loop through pages and delete categories
        do {
            require_once 'application/mvc/apis/api_woocommerce.php';
            $api_woocommerce_class = new api_woocommerce();
            $result_api = $api_woocommerce_class->retrieve_all_woo_parentcategories($page, $per_page);
            $parent_categories = json_decode($result_api["response"], true);
            $http_status = $result_api["http_code"];
            if (!empty($parent_categories) && $http_status == 200) {
                $parent_category_ids = array();
                foreach ($parent_categories as $parent_category) {
                    $parent_category_ids[] = $parent_category['id'];

                    $categories = self::list_all_woocommerce_categories($parent_category['id'], false); // retrieve categories below parent category
                    $all_categories = array_merge($all_categories, $categories);
                }

                if (!empty($parent_category_ids)) {   // delete  from woocommerce 
                    //  echo json_encode($parent_category_ids); 
                    $result_api = $api_woocommerce_class->delete_batch_parent_categories($parent_category_ids);
                    if (isset($result_api['deleted']) && $result_api["status"] == 200) {
                        $description = "Parent Categories deleted in batch: ()" . implode(', ', $result_api['deleted']) . ")";

                        $all_deleted_parent_categories_ids = array_merge($all_deleted_parent_categories_ids, $parent_category_ids);
                    } else {
                        $description = "Failed to delete categories in batch: (" . implode(', ', $result_api['deleted']) . ")";
                    }
                    /* Logs */
                    $woocommerce_class = $this->model("woocommerce");
                    $logs_info = array();
                    $logs_info["operator_id"] = $_SESSION['id'];
                    $logs_info["pos_parent_category_id"] = 0;
                    $logs_info["description"] = "Batch Delete Woocommerce Parent Category IDs (" . implode(', ', $parent_category_ids) . ")";
                    $logs_info["woo_parent_category_id"] = 0;
                    $logs_info["woo_id"] = 0;
                    $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
                    /* END Logs */
                }
                break;
            }

            $page++;
        } while (!empty($parent_categories));


        $return_result = array();
        $return_result["parent_category_ids"] = $all_deleted_parent_categories_ids;
        $return_result["all_categories"] = $all_categories;
        echo json_encode($return_result);
    }


    public function delete_all_parentCategories_old()
    {

        $woocommerce_class = $this->model("woocommerce");


        // Set the initial page and per_page values
        $page = 1;
        $per_page = 100;
        $all_deleted_parent_categories_ids = array();
        $all_categories = array();
        $all_categories_ids = array();

        $all_woo_pcategories = $woocommerce_class->get_all_woocommerce_parent_categories(-1);
        $all_parent_categories_array = array();
        for ($i = 0; $i < count($all_woo_pcategories); $i++) {
            $all_parent_categories_array[$all_woo_pcategories[$i]["woocommerce_parent_category_id"]] = $all_woo_pcategories[$i];
        }

        $return_result = array();
        $return_result["error"] = 0;
        $return_result["msg"] = "Deleting Catgories";

        // Loop through pages and delete categories
        do {
            require_once 'application/mvc/apis/api_woocommerce.php';
            $api_woocommerce_class = new api_woocommerce();
            $result_api = $api_woocommerce_class->retrieve_all_woo_parentcategories($page, $per_page);
            $parent_categories = json_decode($result_api["response"], true);
            $http_status = $result_api["http_code"];
            if (!empty($parent_categories) && $http_status == 200) {
                $parent_category_ids = array();
                foreach ($parent_categories as $parent_category) {

                    $parent_category_ids[] = $parent_category['id'];

                    $categories = self::list_all_woocommerce_categories($parent_category['id'], false); // retrieve categories below parent category
                    $all_categories = array_merge($all_categories, $categories);
                    if ($parent_category['id'] != 15) {
                        $result_api = $api_woocommerce_class->delete_parent_category_id($parent_category['id']);
                        if (($result_api["http_code"] === 200)) {
                            array_push($all_deleted_parent_categories_ids, $parent_category['id']);
                            $description = " Delete from Woocommerce Parent Category ID (" . $parent_category['id'] . ")";
                        } else {
                            $description = "Failed to delete from Woocommerce Parent Category ID (" . $parent_category['id'] . ")";
                            $return_result["error"] = 1;
                            $return_result["msg"] = "Failed to delete from Woocommerce Category IDs";
                        }
                    } else {
                        $return_result["msg"] = "Note Uncategorized Category cannot be deleted";
                        $description = "Note Uncategorized Category cannot be deleted";
                    }

                    $pos_p_cat_id = 0;
                    $woo_id = 0;
                    if (isset($all_parent_categories_array[$parent_category['id']])) {
                        $pos_p_cat_id = $all_parent_categories_array[$parent_category['id']]["pos_parent_category_id"];
                        $woo_id = $all_parent_categories_array[$parent_category['id']]["id"];
                    }
                    /* Logs */
                    $logs_info = array();
                    $logs_info["operator_id"] = $_SESSION['id'];
                    $logs_info["pos_parent_category_id"] = $pos_p_cat_id;
                    $logs_info["description"] = $description;
                    $logs_info["woo_parent_category_id"] = $parent_category['id'];
                    $logs_info["woo_id"] = $woo_id;
                    $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
                    /* END Logs */
                }
                $page++;
            } else {
                $return_result["error"] = 0;
                $return_result["msg"] = "Finished Deleting";
                break;
            }
        } while (!empty($parent_categories));


        /*DB DELETE WOO PCAT and UNSYNC POS  pcat */
        if (count($all_deleted_parent_categories_ids) > 0) {
            $pos_p_cat_ids = array();
            $pos_p_cats = $woocommerce_class->get_pos_pcategories_by_woo_pcategories_by_ids($all_deleted_parent_categories_ids);
            for ($i = 0; $i < count($pos_p_cats); $i++) {
                array_push($pos_p_cat_ids, $pos_p_cats[$i]["pos_parent_category_id"]);
            }
            $woocommerce_class->delete_woocommerce_parent_categories($all_deleted_parent_categories_ids);
            $affected_rows = my_sql::get_mysqli_rows_num();
            if ($affected_rows > 0) {

                /* Logs */
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_parent_category_id"] = 0;
                $logs_info["description"] = " Delete Parent Category IDs  (" . implode(',', $all_deleted_parent_categories_ids) . ")";
                $logs_info["woo_parent_category_id"] = 0;
                $logs_info["woo_id"] = 0;
                $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
                /* END Logs */


                if (count($pos_p_cat_ids) > 0) {
                    $woocommerce_class->unsync_pos_woo_parent_categories($pos_p_cat_ids);
                    $affected_rows = my_sql::get_mysqli_rows_num();
                    if ($affected_rows > 0) {
                        /* Logs */
                        $logs_info = array();
                        $logs_info["operator_id"] = $_SESSION['id'];
                        $logs_info["pos_parent_category_id"] = 0;
                        $logs_info["description"] = " Unsyc Pos Parent Category IDs: (" . implode(',', $pos_p_cat_ids) . ")";
                        $logs_info["woo_parent_category_id"] = 0;
                        $logs_info["woo_id"] = 0;
                        $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
                        /* END Logs */
                    }
                }
            }
        }
        $return_result["all_categories"] = $all_categories;

        /*Update Move Categories from deleted Pcat */
        if (count($all_categories) > 0) {
            self::merge_woo_categories_to_new_ParentCategory($all_categories, 0);
        }

        return ($return_result);
    }
    public function delete_all_parentCategories()
    {

        $woocommerce_class = $this->model("woocommerce");


        // Set the initial page and per_page values
        $page = 1;
        $per_page = 100;
        $all_deleted_parent_categories_ids = array();
        $all_categories = array();
        $all_categories_ids = array();


        $return_result = array();
        $return_result["error"] = 0;
        $return_result["msg"] = "Deleting Catgories";


        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $parent_categories = self::get_woocommerce_parent_categories(false);

        foreach ($parent_categories as $parent_category) {
            $categories = self::list_all_woocommerce_categories($parent_category['id'], false); // retrieve categories below parent category
            $all_categories = array_merge($all_categories, $categories);
            if ($parent_category['id'] != 15) {
                if ($this->settings_info["woocommerce_update_merge_category_items"] == 1) {
                    $products_category = self::get_all_items_by_category_id($parent_category['id']);
                }

                $result_api = $api_woocommerce_class->delete_parent_category_id($parent_category['id']);
                if (($result_api["http_code"] === 200)) {
                    array_push($all_deleted_parent_categories_ids, $parent_category['id']);
                    $description = " Delete from Woocommerce Parent Category ID (" . $parent_category['id'] . ")";
                    if ($this->settings_info["woocommerce_update_merge_category_items"] == 1) {
                        self::resync_woo_items_by_cat_id($parent_category['id'], $products_category);
                    }
                } else {
                    $description = "Failed to delete from Woocommerce Parent Category ID (" . $parent_category['id'] . ") . HTTP Code: " . $result_api["http_code"] . ", Response: " . $result_api["response"];;
                    $return_result["error"] = 1;
                    $return_result["msg"] = "Failed to delete from Woocommerce Category IDs";
                }
            } else {
                $return_result["msg"] = "Note Uncategorized Category cannot be deleted";
                $description = "Note Uncategorized Category cannot be deleted";
            }


            $pos_p_cat_id = $parent_category["pos_parent_category_id"];
            $woo_id = $parent_category["woo_id"];

            /* Logs */
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION['id'];
            $logs_info["pos_parent_category_id"] = $pos_p_cat_id;
            $logs_info["description"] = $description;
            $logs_info["woo_parent_category_id"] = $parent_category['id'];
            $logs_info["woo_id"] = $woo_id;
            $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
            /* END Logs */
        }

        /*DB DELETE WOO PCAT and UNSYNC POS  pcat */
        if (count($all_deleted_parent_categories_ids) > 0) {
            $pos_p_cat_ids = array();
            $pos_p_cats = $woocommerce_class->get_pos_pcategories_by_woo_pcategories_by_ids($all_deleted_parent_categories_ids);
            for ($i = 0; $i < count($pos_p_cats); $i++) {
                array_push($pos_p_cat_ids, $pos_p_cats[$i]["pos_parent_category_id"]);
            }
            $woocommerce_class->delete_woocommerce_parent_categories($all_deleted_parent_categories_ids);
            $affected_rows = my_sql::get_mysqli_rows_num();
            if ($affected_rows > 0) {

                /* Logs */
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_parent_category_id"] = 0;
                $logs_info["description"] = " Delete Parent Category IDs  (" . implode(',', $all_deleted_parent_categories_ids) . ")";
                $logs_info["woo_parent_category_id"] = 0;
                $logs_info["woo_id"] = 0;
                $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
                /* END Logs */


                if (count($pos_p_cat_ids) > 0) {
                    $woocommerce_class->unsync_pos_woo_parent_categories($pos_p_cat_ids);
                    $affected_rows = my_sql::get_mysqli_rows_num();
                    if ($affected_rows > 0) {
                        /* Logs */
                        $logs_info = array();
                        $logs_info["operator_id"] = $_SESSION['id'];
                        $logs_info["pos_parent_category_id"] = 0;
                        $logs_info["description"] = " Unsyc Pos Parent Category IDs: (" . implode(',', $pos_p_cat_ids) . ")";
                        $logs_info["woo_parent_category_id"] = 0;
                        $logs_info["woo_id"] = 0;
                        $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
                        /* END Logs */
                    }
                }
            }
        }
        $return_result["all_categories"] = $all_categories;

        /*Update Move Categories from deleted Pcat */
        if (count($all_categories) > 0) {
            self::merge_woo_categories_to_new_ParentCategory($all_categories, 0);
        }

        return ($return_result);
    }

    public function merge_woo_categories_to_new_ParentCategory($all_categories, $to_Pcat_id)
    {

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woocommerce_class = $this->model("woocommerce");
        $all_category_ids = array();
        $all_pos_category_ids = array();
        $all_woo_categories = $woocommerce_class->get_all_woocommerce_categories();
        $all_categories_array = array();
        for ($i = 0; $i < count($all_woo_categories); $i++) {
            $all_categories_array[$all_woo_categories[$i]["woocommerce_category_id"]] = $all_woo_categories[$i];
        }
        $return_result = array();
        // update all categories
        for ($i = 0; $i < count($all_categories); $i++) {
            if ($to_Pcat_id > 0) {
                $info = array();
                $info["parent_category_id"] = $to_Pcat_id;
                $info["description"] = $all_categories[$i]["description"];
                $info["name"] = $all_categories[$i]["name"];
                $info["category_id"] = $all_categories[$i]["id"];
                $result_api_cat = $api_woocommerce_class->add_update_new_category($info);
                if (($result_api_cat["http_code"] === 200)) {
                    $return_result["error"] = 0;
                    $return_result["msg"] = "Sub-Category merged to Category.";
                    $description = "Category ID " . $all_categories[$i]["id"] . " has been merged successfully to new Parent Category.";
                } else {
                    $return_result["error"] = 1;
                    $return_result["msg"] = "Error merging Sub-Category.";
                    $description = "Error merging Category ID " . $all_categories[$i]["id"];
                    break;
                }

                /*Logs */
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_category_id"] = isset($all_categories_array[$all_categories[$i]["id"]]["pos_category_id"]) ? $all_categories_array[$all_categories[$i]["id"]]["pos_category_id"] : 0;
                $logs_info["description"] = $description;
                $logs_info["woo_category_id"] = $all_categories[$i]["id"];
                $logs_info["woo_id"] = isset($all_categories_array[$all_categories[$i]["id"]]["id"]) ? $all_categories_array[$all_categories[$i]["id"]]["id"] : 0;
                $woocommerce_class->add_woocommerce_categories_logs($logs_info);
                /* END Logs */
            }



            if ($return_result["error"] == 0) {
                // push woo categories to delete them 
                if (isset($all_categories_array[$all_categories[$i]["id"]])) {
                    array_push($all_category_ids, $all_categories[$i]["id"]);
                    // push pos categories to unsycn them 
                    if ($all_categories_array[$all_categories[$i]["id"]]["pos_category_id"] > 0) {
                        array_push($all_pos_category_ids, $all_categories_array[$all_categories[$i]["id"]]["pos_category_id"]);
                    }
                }
            }
        }
        // delete from table + sync again all parent and categories 
        if (count($all_category_ids) > 0) {
            $woocommerce_class->delete_woocommerce_categories($all_category_ids);
        }

        if (count($all_pos_category_ids) > 0) {
            $woocommerce_class->unsync_pos_woo_categories($all_pos_category_ids);
        }
        return $return_result;
    }


    public function delete_parent_category($parent_category_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woo_id = 0;
        $pos_p_cat_id = 0;

        if ($parent_category_id > 0) {
            // from woocommerce
            $return_result = self::delete_parent_categoryid($parent_category_id);
        } else {
            $return_result = self::delete_all_parentCategories();
        }
        $response = json_decode($result_api["response"], true);


        if (isset($return_result["error"]) && ($return_result["error"] == 0)) {
            if ($parent_category_id > 0) {
                $return_result["msg"] = "Category has been deleted";
                $description = "Parent Category ID " . $parent_category_id . " has been deleted successfully.";
            } else {
                $return_result["msg"] = "Categories has been deleted";
            }
        } else {
            if ($parent_category_id > 0) {
                $return_result["msg"] = "Error Deleting Category";
            } else {
                $return_result["msg"] = "Error Deleting all Categories";
            }
        }
        /*Resync Parent Cat and Cat */
        $result_p_cat = self::sync_from_woocommmerce_all_parent_categories();
        $result_cat = self::sync_from_woocommmerce_all_categories();
        if ($result_p_cat["errors"] > 0 || $$result_cat["errors"] > 0) {
            $return_result["error"] = 1;
            $return_result["msg"] = "Error Occured while syncing info from woocommerce.";
        }

        $return_result["parent_categories"] = self::get_woocommerce_parent_categories(false);
        /*END Resync Parent Cat and Cat */


        echo json_encode($return_result);
    }


    public function delete_parent_categoryid($parent_category_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woo_id = 0;
        $pos_p_cat_id = 0;
        // from woocommerce
        $all_categories = array();
        $categories = self::list_all_woocommerce_categories($parent_category_id, false); // retrieve categories below parent category
        $all_categories = array_merge($all_categories, $categories);
        if ($parent_category_id != 15) {
            if ($this->settings_info["woocommerce_update_merge_category_items"] == 1) {
                $products_category = self::get_all_items_by_category_id($parent_category_id);
            }

            $result_api = $api_woocommerce_class->delete_parent_category_id($parent_category_id);
        }

        $wocommerce_pos_p_cat_info = $woocommerce_class->get_woo_pcategories_by_woo_pcategory_id($parent_category_id);
        if ((count($wocommerce_pos_p_cat_info) > 0)) {
            $pos_p_cat_id = $wocommerce_pos_p_cat_info[0]["pos_parent_category_id"];
            $woo_id = $wocommerce_pos_p_cat_info[0]["id"];
        }

        $return_result = array();
        if ($parent_category_id != 15) {

            $response = json_decode($result_api["response"], true);

            if (($result_api["http_code"] === 200)) {
                $return_result["error"] = 0;
                $return_result["msg"] = "Category has been deleted";
                $description = "Parent Category ID " . $parent_category_id . " has been deleted successfully.";
                if ($this->settings_info["woocommerce_update_merge_category_items"] == 1) {
                    self::resync_woo_items_by_cat_id($parent_category_id, $products_category);
                }
            } else {
                $return_result["error"] = 1;
                $return_result["msg"] = "Error Deleting Category";
                $description = "Error deleting Parent Category ID (" . $parent_category_id . "). " . (isset($response["message"]) ? ($response["message"]) : "");
            }
        } else {
            $return_result["error"] = 0;
            $return_result["msg"] = "Note that Category Uncategorized cannot be deleted.";
            $description = "Parent Category ID " . $parent_category_id . " cannot be deleted.";
        }


        /*Logs */
        $logs_info_ = array();
        $logs_info_["operator_id"] = $_SESSION['id'];
        $logs_info_["pos_parent_category_id"] = $pos_p_cat_id;
        $logs_info_["description"] = $description;
        $logs_info_["woo_parent_category_id"] = $parent_category_id;
        $logs_info_["woo_id"] = $woo_id;
        $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info_);
        /* END Logs */


        if ($return_result["error"] == 0) {
            /*DELETE FROM DB */
            $woocommerce_class->delete_woo_category_by_woo_pcategory_id($parent_category_id);
            $affected_rows = my_sql::get_mysqli_rows_num();
            if ($affected_rows > 0) {
                if ($pos_category_id > 0) {
                    $woocommerce_class->update_is_sync_parentcategory_id($pos_p_cat_id, 0);
                    $description = "UnSync POS Parent Category ID (" . $pos_p_cat_id . ") with  Woocommerce Category ID (" . $parent_category_id . ") ";
                    $logs_info = array();
                    $logs_info["operator_id"] = $_SESSION['id'];
                    $logs_info["pos_parent_category_id"] = $pos_p_cat_id;
                    $logs_info["description"] = $description;
                    $logs_info["woo_parent_category_id"] = $parent_category_id;
                    $logs_info["woo_id"] = $woo_id;
                    $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info);
                }
            }
        }

        if (count($all_categories) > 0) {
            self::merge_woo_categories_to_new_ParentCategory($all_categories, 0);
        }

        $return_result["all_categories"] = $all_categories;


        // update all categories

        return ($return_result);
    }

    public function delete_parent_category_($parent_category_id)
    {

        $woocommerce_class = $this->model("woocommerce");
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        if ($parent_category_id > 0) {
            $result_api = $api_woocommerce_class->delete_parent_category_id($parent_category_id);
        } else {
            $all_categories = self::list_all_woocommerce_categories(-1, false);
            $all_woo_categories = $woocommerce_class->get_all_woocommerce_categories();
            $all_categories_array = array();
            for ($i = 0; $i < count($all_woo_categories); $i++) {
                $all_categories_array[$all_woo_categories[$i]["woocommerce_category_id"]] = $all_woo_categories[$i];
            }
        }
        $response = json_decode($result_api["response"], true);

        $return_result = array();

        if (($result_api["http_code"] === 200)) {
            $return_result["error"] = 0;
            if ($parent_category_id > 0) {
                $return_result["msg"] = "Category has been deleted";
                $description = "Parent Category ID " . $parent_category_id . " has been deleted successfully.";
            } else {
                $return_result["msg"] = "Categories has been deleted";
                $description = "Parent Categories has been deleted successfully.";
            }
        } else {
            $return_result["error"] = 1;
            if ($parent_category_id > 0) {
                $return_result["msg"] = "Error Deleting Category";
                $description = "Error deleting Parent Category ID (" . $parent_category_id . "). " . (isset($response["message"]) ? ($response["message"]) : "");
            } else {
                $return_result["msg"] = "Error Deleting all Categories";
                $description = "Error deleting all Parent Categories ";
            }
        }

        $woo_id = 0;
        $pos_p_cat_id = 0;

        /*Logs */
        $logs_info_ = array();
        $logs_info_["operator_id"] = $_SESSION['id'];
        $logs_info_["pos_parent_category_id"] = $pos_p_cat_id;
        $logs_info_["description"] = $description;
        $logs_info_["woo_parent_category_id"] = $parent_category_id;
        $logs_info_["woo_id"] = $woo_id;
        $woocommerce_class->add_woocommerce_parent_categories_logs($logs_info_);
        /* END Logs */




        if ($return_result["error"] == 0) {
            /*DELETE FROM DB */
            $wocommerce_pos_p_cat_info = $woocommerce_class->get_woo_pcategories_by_woo_pcategory_id($parent_category_id);
            if ((count($wocommerce_pos_p_cat_info) > 0)) {
                $pos_p_cat_id = $wocommerce_pos_p_cat_info[0]["pos_parent_category_id"];
                $woo_id = $wocommerce_pos_p_cat_info[0]["id"];
            }
            $woocommerce_class->delete_woo_category_by_woo_category_id($woo_category_id);
            if ($pos_category_id > 0) {
                $woocommerce_class->update_is_sync_category_id($pos_category_id, 0);
                $description = "UnSync POS Category ID (" . $woocommerce_pos_cat_info[0]["pos_category_id"] . ") with  Woocommerce Category ID (" . $woo_category_id . ") ";
                $logs_info = array();
                $logs_info["operator_id"] = $_SESSION['id'];
                $logs_info["pos_category_id"] = $pos_category_id;
                $logs_info["description"] = $description;
                $logs_info["woo_category_id"] = $woo_category_id;
                $logs_info["woo_id"] = $woo_id;
                $woocommerce_class->add_woocommerce_categories_logs($logs_info);
            }
        }




        // update all categories
        for ($i = 0; $i < count($all_categories); $i++) {

            $info = array();
            $info["parent_category_id"] = 0;
            $info["description"] = $all_categories[$i]["description"];
            $info["name"] = $all_categories[$i]["name"];
            $info["category_id"] = $all_categories[$i]["id"];
            $result_api_cat = $api_woocommerce_class->add_update_new_category($info);
            if (($result_api_cat["http_code"] === 200)) {
                $return_result["error"] = 0;
                $return_result["msg"] = "Sub-Category has been updated.";
                $description = "Category ID " . $all_categories[$i]["id"] . " has been updated successfully.";
            } else {
                $return_result["error"] = 1;
                $return_result["msg"] = "Error Updating Sub-Category.";
                $description = "Error updating Category ID " . $all_categories[$i]["id"];
                break;
            }

            /*Logs */

            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION['id'];
            $logs_info["pos_category_id"] = 0;
            $logs_info["description"] = $description;
            $logs_info["woo_category_id"] = $all_categories[$i]["id"];
            $logs_info["woo_id"] = 0;
            $woocommerce_class->add_woocommerce_categories_logs($logs_info);
            /* END Logs */
        }
        echo json_encode($return_result);
    }


    public function sync_from_woocommmerce_all_parent_categories()
    {
        $woocommerce_class = $this->model("woocommerce");
        $all_parent_categories = self::list_all_woocommerce_parent_categories(false);
        $all_woo_pcategories = $woocommerce_class->get_all_woocommerce_parent_categories(-1);
        $all_parent_categories_array = array();
        for ($i = 0; $i < count($all_woo_pcategories); $i++) {
            $all_parent_categories_array[$all_woo_pcategories[$i]["woocommerce_parent_category_id"]] = $all_woo_pcategories[$i];
        }
        $return_result = array();
        $return_result["errors"] = 0;
        foreach ($all_parent_categories as $category) {
            $info = array();
            $info["response"] = json_encode($category);
            $info["pos_p_category_id"] = 0;
            $info["woc_p_category_id"] = $category['id'];
            $info["from_woocommerce"] = 1;
            $info["woo_id"] = 0;
            if (isset($all_parent_categories_array[$category["id"]])) {
                $info["woo_id"] = $all_parent_categories_array[$category["id"]]["id"];
                $info["pos_p_category_id"] = $all_parent_categories_array[$category["id"]]["pos_parent_category_id"];
            }
            $result = $woocommerce_class->add_update_woocommerce_parent_categories($info);
            if (!$result) {
                $return_result["errors"]++;
            }
        }
        return ($return_result);
    }


    public function sync_from_woocommmerce_all_categories()
    {
        $woocommerce_class = $this->model("woocommerce");
        $all_categories = self::list_all_woocommerce_categories(-1, false);
        $all_woo_categories = $woocommerce_class->get_all_woocommerce_categories();
        $all_categories_array = array();
        for ($i = 0; $i < count($all_woo_categories); $i++) {
            $all_categories_array[$all_woo_categories[$i]["woocommerce_category_id"]] = $all_woo_categories[$i];
        }
        $return_result = array();
        $return_result["errors"] = 0;
        foreach ($all_categories as $category) {
            $info = array();
            $info["response"] = json_encode($category);
            $info["pos_category_id"] = 0;
            $info["woc_category_id"] = $category['id'];
            $info["from_woocommerce"] = 1;
            $info["woo_id"] = 0;
            $info["parent"] = $category["parent"];
            if (isset($all_categories_array[$category["id"]])) {
                $info["woo_id"] = $all_categories_array[$category["id"]]["id"];
                $info["pos_category_id"] = $all_categories_array[$category["id"]]["pos_category_id"];
            }
            $result = $woocommerce_class->add_update_woocommerce_categories($info);
            if (!$result) {
                $return_result["errors"]++;
            }
        }
        return ($return_result);
    }
    public function get_woocommerce_category_and_parents()
    {
        $result_p_cat = self::sync_from_woocommmerce_all_parent_categories();
        $result_cat = self::sync_from_woocommmerce_all_categories();
        $return = array();
        $return["error"] = 0;
        $return["msg"] = "";
        if ($result_p_cat["errors"] > 0 || $$result_cat["errors"] > 0) {
            $return["error"] = 1;
            $return["msg"] = "Error Occured while syncing info from woocommerce.";
        } else {
            $return["parent_categories"] = self::get_woocommerce_parent_categories(false);
        }
        echo json_encode($return);
    }




    function get_woocommerce_parent_categories($echoResult = true)
    {
        $woocommerce_class = $this->model("woocommerce");
        $parent_categories = $woocommerce_class->get_all_woocommerce_parent_categories(1);
        $all_parent_categories_array = array();
        for ($i = 0; $i < count($parent_categories); $i++) {
            $response = json_decode($parent_categories[$i]["response"], true);
            array_push($all_parent_categories_array, array("id" => $parent_categories[$i]["woocommerce_parent_category_id"], "name" => (isset($response["name"])) ? $response["name"] : "", "description" => (isset($response["description"])) ? $response["description"] : "", "pos_parent_category_id" => $parent_categories[$i]["pos_parent_category_id"], "woo_id" => $parent_categories[$i]["id"]));
        }
        if ($echoResult) {
            echo json_encode($all_parent_categories_array);
        } else {
            return $all_parent_categories_array;
        }
    }



    function get_all_woocommerce_categories($parent_category_id)
    {
        $woocommerce_class = $this->model("woocommerce");
        $parent_categories = $woocommerce_class->get_all_woocommerce_parent_categories(1);
        $all_parent_categories_array = array();

        for ($i = 0; $i < count($parent_categories); $i++) {
            for ($i = 0; $i < count($parent_categories); $i++) {
                $response = json_decode($parent_categories[$i]["response"], true);
                $all_parent_categories_array[$parent_categories[$i]["woocommerce_parent_category_id"]] = (isset($response["name"])) ? $response["name"] : "";
            }
        }
        $info = array();
        $info["parent_category_id"] = $parent_category_id;
        $info["from_woocommerce"] = 1;
        $all_categories = $woocommerce_class->get_all_woocommerce_categories_with_filters($info);

        $data_array["data"] = array();
        for ($i = 0; $i < count($all_categories); $i++) {
            $tmp = array();
            $response = json_decode($all_categories[$i]["response"], true);
            array_push($tmp, $all_categories[$i]["woocommerce_category_id"]);
            array_push($tmp, (isset($response["parent"])) ? $response["parent"] : "");
            array_push($tmp, (isset($response["name"])) ? $response["name"] : "");
            array_push($tmp, (isset($response["description"])) ? $response["description"] : "");
            if (isset($all_parent_categories_array[$response["parent"]])) {
                array_push($tmp, $all_parent_categories_array[$response["parent"]]);
            } else {
                array_push($tmp, "");
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode(($data_array));
    }



    function batchCreateProducts($products_data)
    {

        $url = 'https://tekpluslb.com';
        $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
        $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
        $batch_url = $url . '/wp-json/wc/v3/products/batch';
        $ch = curl_init($batch_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($products_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $batch_response = curl_exec($ch);
        curl_close($ch);
        $response_data = json_decode($batch_response, true);

        if (isset($response_data['create'])) {
            foreach ($response_data['create'] as $product_id => $status) {
                if ($status === true) {
                    echo "Product with ID $product_id created successfully.\n";
                } else {
                    echo "Failed to create product with ID $product_id. Error: " . $status['message'] . "\n";
                }
            }
        } else {
            echo "Failed to create products in batch\n";
            print_r($response_data); // Output the response for debugging
        }
    }



    public function get_all_items_by_category_id($category_id)
    {

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        $page = 1;
        $per_page = 100;
        $all_products = array();

        do {
            $result_api = $api_woocommerce_class->get_product_by_category_id($category_id, $page, $per_page);
            $http_status = $result_api["http_code"];
            $products = json_decode($result_api["response"], true);
            if (!empty($products) && $http_status == 200) {

                foreach ($products as $product) {
                    foreach ($product['categories'] as $category) {
                        if ($category['id'] == $category_id) {
                            $all_products[] = $product;
                            break;
                        }
                    }
                }

                $page++;
            } else {
                break;
            }
        } while (!empty($products));

        return ($all_products);
    }

    public function get_woo_items_by_category_id($category_id)
    {
        $items = array();
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $page = 1;
        $per_page = 100;

        do {
            $result_api = $api_woocommerce_class->get_product_by_category_id($category_id, $page, $per_page);
            $http_status = $result_api["http_code"];
            $products = json_decode($result_api["response"], true);

            if (!empty($products) && $http_status == 200) {
                foreach ($products as $product) {
                    // Push product IDs directly to the $items array
                    $items[] = $product['id'];
                }
                $page++;
            } else {
                break;
            }
        } while (!empty($products));

        echo json_encode($items);
        // self::resync_woo_items_by_cat_id($category_id, $items);
    }


    public function merge_all_items_to_new_category($current_category_id, $targeted_category_id)
    {
        $products = self::get_all_items_by_category_id($current_category_id);

        foreach ($products as $product) {
            self::merge_item_to_new_category($product['id'], $targeted_category_id);
        }
    }


    public function merge_item_to_new_category($product_id, $targeted_category_id)
    {

        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $woocommerce_class = $this->model("woocommerce");
        $return_result = array();



        $result_api = $api_woocommerce_class->update_product_category_id($product_id, $targeted_category_id);
        if ($result_api["http_code"] === 200) {
            $response = json_decode($result_api["response"], true);
            $return_result["error"] = 0;
            $return_result["msg"] = " ";
            $description = "Product ID (" . $product_id . ") was merged to new Category ID (" . $targeted_category_id . ").";
        } else {
            $return_result["error"] = 1;
            $return_result["msg"] = "Error occured while merging item into new category.";
            $description = "Error occured while merging Product ID (" . $product_id . ") into new Category ID (" . $targeted_category_id . ").";
        }
        $woocommerce_items_info =  $woocommerce_class->get_woocommerce_by_woo_item_id($product_id);
        if (count($woocommerce_items_info) > 0) {

            $info = array();
            $info["item_id"] = $woocommerce_items_info[0]["pos_item_id"];
            $info["product_id"] = $response["id"];
            $info["response"] = $result_api["response"];
            $woocommerce_class->add_update_woocommerce_item($info);

            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION['id'];
            $logs_info["pos_item_id"] = $woocommerce_items_info[0]["pos_item_id"];
            $logs_info["description"] = $description;
            $logs_info["woo_item_id"] = $woocommerce_items_info[0]["woocommerce_item_id"];
            $logs_info["woo_id"] = $woocommerce_items_info[0]["id"];
            $woocommerce_class->add_woocommerce_items_logs($logs_info);
        }
    }




    public function  get_all_filtered_products_categories($category_id, $category_items)
    {
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        $page = 1;
        $per_page = 100;
        $all_products = array();
        do {
            $result_api = $api_woocommerce_class->get_product_by_category_id($category_id, $page, $per_page);
            $http_status = $result_api["http_code"];
            $products = json_decode($result_api["response"], true);
            if (!empty($products) && $http_status == 200) {
                // Filter products based on whether their IDs are in $category_items
                $filtered_products = array_filter($products, function ($product) use ($category_items) {
                    return in_array($product['id'], $category_items);
                });
                // Merge filtered products into $all_products
                $all_products = array_merge($all_products, $filtered_products);
                $page++;
            } else {
                break;
            }
        } while (!empty($products));

        return ($all_products);
    }


    public function resync_woo_items_by_cat_id($current_category_id, $products)
    {


        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();

        $woocommerce_class = $this->model("woocommerce");
        $all_items =  $woocommerce_class->get_woocommerce_items();
        $all_items_array = array();
        for ($i = 0; $i < count($all_items); $i++) {
            $all_items_array[$all_items[$i]["woocommerce_item_id"]] = $all_items[$i];
        }

        // $products = self::get_all_filtered_products_categories($current_category_id, $products);
        foreach ($products as $product) {
            if (isset($all_items_array[$product['id']])) {
                $result_api =  $api_woocommerce_class->get_woocommerce_product_id($product['id']);

                if ($result_api["http_code"] === 200) {
                    $response = json_decode($result_api["response"], true);

                    $info = array();
                    $info["item_id"] = $all_items_array[$product['id']]["pos_item_id"];
                    $info["product_id"] = $product['id'];
                    $info["response"] = json_encode($response);
                    $woocommerce_class->add_update_woocommerce_item($info);

                    $logs_info = array();
                    $logs_info["operator_id"] = $_SESSION['id'];
                    $logs_info["pos_item_id"] = $all_items_array[$product['id']]["pos_item_id"];
                    $logs_info["description"] = "Merge  Product ID (" . $all_items_array[$product['id']]["pos_item_id"] . ") from Category ID (" . $current_category_id . ")";
                    $logs_info["woo_item_id"] = $all_items_array[$product['id']]["woocommerce_item_id"];
                    $logs_info["woo_id"] = $all_items_array[$product['id']]["id"];
                    $woocommerce_class->add_woocommerce_items_logs($logs_info);
                }
            }
        }
    }
    public function test1()
    {
        require_once 'application/mvc/apis/api_woocommerce.php';
        $api_woocommerce_class = new api_woocommerce();
        $page = 1;
        $per_page = 15;

        $result_api = $api_woocommerce_class->get_product_by_category_id(508, $page, $per_page);
        echo json_encode($result_api);
    }
    public function delete_all_products()
    {

        $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
        $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
        $endpoint = 'https://tekpluslb.com/wp-json/wc/v3/products';


        // Function to make API request
        function make_api_request($url, $consumer_key, $consumer_secret, $method = 'GET', $data = null)
        {
            $curl = curl_init();

            $headers = array(
                'Content-Type: application/json',
            );

            $params = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
            );

            if ($method !== 'GET' && $data !== null) {
                $params[CURLOPT_POSTFIELDS] = json_encode($data);
            }

            curl_setopt_array($curl, $params);

            curl_setopt($curl, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");

            $response = curl_exec($curl);

            curl_close($curl);

            return $response;
        }

        // Delete products with pagination
        $page = 1;
        $per_page = 100; // Number of products to delete per page

        do {
            // Retrieve products for the current page
            $response = make_api_request($endpoint . '?page=' . $page . '&per_page=' . $per_page, $consumer_key, $consumer_secret);
            $products = json_decode($response, true);

            // Delete each product
            foreach ($products as $product) {
                $product_id = $product['id'];
                $delete_endpoint = $endpoint . "/$product_id";
                make_api_request($delete_endpoint, $consumer_key, $consumer_secret, 'DELETE');
            }

            $page++;

            // Continue looping until no more products are returned
        } while (!empty($products));

        echo "All products have been deleted.";
    }



    public function enable_stock()
    {
        $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
        $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
        $url = 'https://tekpluslb.com/wp-json/wc/v3/products';


        // New stock status (true to enable stock management, false to disable)
        $new_stock_status = true;

        // Prepare data to update
        $data = [
            'manage_stock' => $new_stock_status
        ];

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret)
        ]);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Decode response
            $products = json_decode($response);

            // Loop through each product
            foreach ($products as $product) {
                // Update product data
                $update_url = $url . '/' . $product->id;
                $update_ch = curl_init($update_url);
                curl_setopt($update_ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($update_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($update_ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($update_ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret)
                ]);
                $update_response = curl_exec($update_ch);

                // Check for errors
                if (curl_errno($update_ch)) {
                    echo 'Error updating product ' . $product->id . ': ' . curl_error($update_ch) . "\n";
                } else {
                    echo 'Stock management updated for product ' . $product->id . "\n";
                }

                // Close cURL
                curl_close($update_ch);
            }
        }

        // Close cURL
        curl_close($ch);
    }
}
