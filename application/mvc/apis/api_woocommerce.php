<?php

class api_woocommerce extends Controller
{



  public function sync_all_items($products)
  {
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products';



    $data = array();


    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;

    foreach ($products as $product_data) {

      $product = array(
        'name' => $product_data["item_alias"],
        'type' => 'simple',
        'regular_price' => $product_data["buying_cost"],
        'description' => $product_data["description"],
        'sku' => $product_data["sku_code"],

      );

      $data["data"] = $product;
      $result = self::post_web_page($api_url, $data);
      return ($result);
    }
  }



  public function sync_by_item_id($item_id, $is_variable_item)
  {
    $woocommerce_class = $this->model("woocommerce");
    $items_class = $this->model("items");
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];

    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products';




    require_once 'application/mvc/models/itemsModel.php';
    $items_class = new itemsModel();
    $item_info = $items_class->get_item($item_id);

    $item_category_id = 0;
    if (count($item_info) > 0) {
      if ($item_info[0]["item_category"] != null || $item_info[0]["item_category"] > 0) {
        // get woocommerce category id from pos category id 
        $wocommerce_pos_cat_info = $woocommerce_class->get_woocommerce_category_by_pos_cat_id($item_info[0]["item_category"]);
        if (count($wocommerce_pos_cat_info) > 0) {
          $item_category_id = $wocommerce_pos_cat_info[0]["woocommerce_category_id"];
        }
      }
    }


    $image_path = "";
    $image_info = $items_class->getImageItem_by_id($item_id);

    if (count($image_info) > 0) {


      // Image file path
      $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
      $image_path = array(
        'src' => $current_url . "/data/images_items/" . $image_info[0]["name"] // Replace with your image URL
      );
    }

    $result = array("response" => "Item is not found.", "http_code" => "404"); // 404 Not found code 

    if ($is_variable_item == 0) {
      $product_type = "simple";
      $attributes = "";
    } else {
      $product_type = "variable";
      require_once 'application/mvc/controllers/variation.php';
      $variation_class = new variation();
      $attributes = ($variation_class->list_all_item_attributes());
    }
    if (count($item_info) > 0) {

      // Product data
      $product_data = [
        'name' => $item_info[0]["item_alias"],
        'type' => $product_type,
        'regular_price' => $item_info[0]["selling_price"],
        'description' => $item_info[0]["description"], // will change
        'sku' => $item_info[0]["sku_code"],
        'weight' => $item_info[0]["weight"],
        'short_description' => $item_info[0]["description"],
        'categories' => array(array('id' => $item_category_id)),
        // Replace 1 with the category ID you want to assign
        'images' => array(
          $image_path
        )
      ];
      if (!empty($attributes)) {
        $product_data['attributes'] = $attributes;
      }
      $data = array();

      $data["data"] = $product_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::post_web_page($api_url, $data);
    }
    // Print the response
    return $result;
  }


  public function update_item_by_id($pos_item_id, $woc_product_id, $is_variable_item)
  {

    $woocommerce_class = $this->model("woocommerce");

    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';

    if ($is_variable_item == 0) {
      $product_type = "simple";
      $attributes = "";
    } else {
      $product_type = "variable";
      require_once 'application/mvc/controllers/variation.php';
      $variation_class = new variation();
      $attributes = ($variation_class->list_all_item_attributes());
    }

    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/' . $woc_product_id;
    // Image file path
    $image_path = "http://localhost/stock-and-pos/woocommerce_images/test.jpg";

    require_once 'application/mvc/models/itemsModel.php';
    $items_class = new itemsModel();
    $item_info = $items_class->get_item($pos_item_id);

    $item_category_id = 0;
    if (count($item_info) > 0) {

      if ($item_info[0]["item_category"] != null || $item_info[0]["item_category"] > 0) {
        // get woocommerce category id from pos category id 
        $wocommerce_pos_cat_info = $woocommerce_class->get_woocommerce_category_by_pos_cat_id($item_info[0]["item_category"]);
        if (count($wocommerce_pos_cat_info) > 0) {
          $item_category_id = $wocommerce_pos_cat_info[0]["woocommerce_category_id"];
        }
      }
    }



    $image_path = "";
    $image_info = $items_class->getImageItem_by_id($pos_item_id);

    if (count($image_info) > 0) {

      // Image file path

      $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";



      $image_path = array(
        'src' => $current_url . "/data/images_items/" . $image_info[0]["name"] // Replace with your image URL
      );
    }


    $result = array("response" => "Item is not found.", "http_code" => "404"); // 404 Not found code 

    if (count($item_info) > 0) {

      // Product data
      $product_data = [
        'name' => $item_info[0]["item_alias"],
        'type' => $product_type,
        'regular_price' => $item_info[0]["selling_price"],
        'description' => $item_info[0]["description"],
        'sku' => $item_info[0]["sku_code"],
        'weight' => $item_info[0]["weight"],
        'short_description' => $item_info[0]["description"],
        'categories' => array(array('id' => $item_category_id)),
        // Replace 1 with the category ID you want to assign
        'images' => array(
          $image_path
        )
      ];

      if (!empty($attributes)) {
        $product_data['attributes'] = $attributes;
      }
      $data = array();

      $data["data"] = $product_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::put_web_page($api_url, $data);
    }
    // Print the response
    return $result;
  }


  public function sync_parent_category_by_id($parent_category_id)
  {


    $categories_class = $this->model("categories");

    $parent_category_info = $categories_class->get_parent_category($parent_category_id);
    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/categories';


    $result = array("response" => "Parent Category  is not found.", "http_code" => "404"); // 404 Not found code 

    if (count($parent_category_info) > 0) {
      $parent_category_data = [
        'name' => $parent_category_info[0]["name"],
        'description' => '',
        'parent' => 0, // 0 indicates a top-level category
      ];

      $data = array();

      $data["data"] = $parent_category_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::post_web_page($api_url, $data);
    }


    return $result; // The response will contain the created category's details

  }



  public function update_parent_category_by_id($pos_p_cat_id, $woc_p_cat_id)
  {


    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/categories/' . $woc_p_cat_id;


    require_once 'application/mvc/models/itemsModel.php';
    $categories_class = $this->model("categories");
    $parent_category_info = $categories_class->get_parent_category($pos_p_cat_id);



    $result = array("response" => "Parent Category  is not found.", "http_code" => "404"); // 404 Not found code 
    if (count($parent_category_info) > 0) {
      $parent_category_data = [
        'name' => $parent_category_info[0]["name"],
        'description' => '',
        'parent' => 0, // 0 indicates a top-level category
      ];
      $data = array();


      $data["data"] = $parent_category_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::put_web_page($api_url, $data);
    }
    // Print the response
    return $result;
  }




  public function sync_category_by_id($category_id)
  {


    $categories_class = $this->model("categories");
    $woocommerce_class = $this->model("woocommerce");

    $category_info = $categories_class->get_category($category_id);


    // get woocommerce parent category id from pos parent category id 
    $woc_parent_category_info = $woocommerce_class->get_woocommerce_parent_category_by_pos_cat_id($category_info[0]["parent"]);
    $woc_parent_category_id = 0;
    if (count($woc_parent_category_info) > 0) {
      $woc_parent_category_id = $woc_parent_category_info[0]["woocommerce_parent_category_id"];
    }
    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/categories';


    $result = array("response" => "Sub-Category is not found.", "http_code" => "404"); // 404 Not found code 

    if (count($category_info) > 0) {
      $category_info_data = [
        'name' => $category_info[0]["description"],
        'description' => $category_info[0]["description"],
        'parent' => $woc_parent_category_id, // 0 indicates a top-level category
      ];


      $data = array();
      $data["data"] = $category_info_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::post_web_page($api_url, $data);
    }


    return $result; // The response will contain the created category's details

  }



  public function update_category_by_id($pos_cat_id, $woc_cat_id)
  {


    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/categories/' . $woc_cat_id;


    require_once 'application/mvc/models/itemsModel.php';
    $categories_class = $this->model("categories");

    $woocommerce_class = $this->model("woocommerce");


    $category_info = $categories_class->get_category($pos_cat_id);

    // get woocommerce parent category id from pos parent category id 
    $woc_parent_category_info = $woocommerce_class->get_woocommerce_parent_category_by_pos_cat_id($category_info[0]["parent"]);
    $woc_parent_category_id = 0;
    if (count($woc_parent_category_info) > 0) {
      $woc_parent_category_id = $woc_parent_category_info[0]["woocommerce_parent_category_id"];
    }



    $result = array("response" => "Category is not found.", "http_code" => "404"); // 404 Not found code 

    if (count($category_info) > 0) {
      $category_info_data = [
        'name' => $category_info[0]["description"],
        'description' => $category_info[0]["description"],
        'parent' => $woc_cat_id, // 0 indicates a top-level category
      ];

      $data = array();
      $data["data"] = $category_info_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::put_web_page($api_url, $data);
    }
    // Print the response
    return $result;
  }


  public function delete_item_by_id($item_id, $woc_product_id)
  {
    $items_class = $this->model("items");

    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/' . $woc_product_id;

    $item_info = $items_class->get_item($item_id);
    $result = array("response" => "Item is not found to delete.", "http_code" => "404"); // 404 Not found code 
    if (count($item_info) > 0) {
      $data = array();
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::delete_web_page($api_url, $data);
    }
    // Check the response
    return $result;
  }

  public function delete_parent_category_by_id($pos_p_cat_id, $woc_p_cat_id)
  {
    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/categories/' . $woc_p_cat_id;

    require_once 'application/mvc/models/itemsModel.php';
    $categories_class = $this->model("categories");
    $parent_category_info = $categories_class->get_parent_category($pos_p_cat_id);

    // WooCommerce API URL and credentials
    $result = array("response" => "Item is not found to delete.", "http_code" => "404"); // 404 Not found code 
    if (count($parent_category_info) > 0) {
      $data = array();
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::delete_web_page($api_url, $data);
    }
    // Check the response
    return $result;
  }


  public function delete_category_by_id($pos_cat_id, $woc_cat_id)
  {
    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/categories/' . $woc_cat_id;

    $categories_class = $this->model("categories");
    $category_info = $categories_class->get_category($pos_cat_id);

    $result = array("response" => "Category is not found.", "http_code" => "404"); // 404 Not found code 
    if (count($category_info) > 0) {
      $data = array();
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::delete_web_page($api_url, $data);
    }
    // Check the response
    return $result;
  }

  public function delete_woocommerce_category_by_id($woc_cat_id)
  {
    // WooCommerce API URL and credentials

    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];

    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/categories/' . $woc_cat_id;

    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = self::delete_web_page($api_url, $data);
    return $result;
  }


  public function retrieve_all_woocategories_by_parent_id($parent_id, $page, $per_page)
  {

    // WooCommerce API URL and credentials
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';

    $api_url = $website_url . '/wp-json/wc/v3/products/categories' . ($parent_id > 0 ? '?parent=' . $parent_id : '') . ($parent_id > 0 ? '&page=' . $page : '?page=' . $page) . "&per_page=" . $per_page;
    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = self::get_web_page($api_url, $data);
    return ($result);
  }


  public function retrieve_all_woo_parentcategories($page, $per_page)
  {
    // WooCommerce API URL and credentials
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/categories?parent=0&page=' . $page . "&per_page=" . $per_page;

    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = self::get_web_page($api_url, $data);
    return ($result);
  }


  public function add_update_new_category($info)
  {

    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];

    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/categories' . ($info["category_id"] > 0 ? '/' . $info["category_id"] : '');


    $category_info_data = [
      'name' => $info["name"],
      'description' => $info["description"],
      'parent' => $info["parent_category_id"],
    ];


    $data = array();
    $data["data"] = $category_info_data;
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    if ($info["category_id"] > 0) {
      $result = self::put_web_page($api_url, $data);
    } else {
      $result = self::post_web_page($api_url, $data);
    }

    return $result; // The response will contain the created category's details



  }

  public function add_update_new_parent_category($info)
  {

    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];

    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/categories' . ($info["parent_category_id"] > 0 ? '/' . $info["parent_category_id"] : '');


    $pcategory_info_data = [
      'name' => $info["name"],
      'description' => $info["description"],
      'parent' => 0,
    ];


    $data = array();
    $data["data"] = $pcategory_info_data;
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    if ($info["category_id"] > 0) {
      $result = self::put_web_page($api_url, $data);
    } else {
      $result = self::post_web_page($api_url, $data);
    }

    return $result; // The response will contain the created category's details



  }


  public function delete_parent_category_id($woc_p_cat_id)
  {
    // WooCommerce API URL and credentials
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/categories' . ($woc_p_cat_id > 0 ? '/' . $woc_p_cat_id : '?parent=0');
    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = self::delete_web_page($api_url, $data);
    return $result;
  }


  public function delete_batch_parent_categories($category_ids)
  {
    // WooCommerce API URL and credentials
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/categories/batch';
    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $data = array(
      'delete' => $category_ids,
    );
    $result = self::post_web_page($api_url, $data);
    return $result;
  }

  public function create_batch_parent_categories($category_ids)
  {
    // WooCommerce API URL and credentials
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/batch';
    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $data = array(
      'create' => $category_ids,
    );
    $result = self::post_web_page($api_url, $data);
    return $result;
  }

  public function sync_product_variation_($pos_item_id, $item_id)
  {
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/' . $pos_item_id . '/variations';

    require_once 'application/mvc/controllers/variation.php';
    $variation_class = new variation();
    $variation_data = $variation_class->items_variation_details($item_id);

    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;

    $result = array();
    $result = array("response" => "No Variation is found.", "http_code" => "404"); // 404 Not found code 
    $result["error"] = 0;
    // if (!empty($variation_data)) {


    //   foreach ($variation_data as $variation_data) {
    //     $data["data"] = ($variation_data);
    //     $result = self::post_web_page($api_url, $data);
    //     // return $result;

    //     if (!in_array($result["http_code"], array("200", "201"))) {
    //       $result["error"]++;
    //     }
    //   }

    //   return $result;
    // }
  }


  public function sync_product_variation($woo_item_id, $variation_data)
  {
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/' . $woo_item_id . '/variations';


    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = array();
    $result = array("response" => "No Variation is found.", "http_code" => "404"); // 404 Not found code 
    $result["error"] = 0;
    if (!empty($variation_data)) {

      $data["data"] = ($variation_data);
      $result = self::post_web_page($api_url, $data);
    }
    return $result;
  }


  public function update_product_variation($woo_item_id, $variation_data, $product_variation_id)
  {
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/' . $woo_item_id . '/variations' . "/" . $product_variation_id;


    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;

    $result = array();
    $result = array("response" => "No Variation is found.", "http_code" => "404"); // 404 Not found code 
    $result["error"] = 0;
    if (!empty($variation_data)) {

      $data["data"] = ($variation_data);
      $result = self::put_web_page($api_url, $data);
    }
    return $result;
  }


  public function get_product_by_category_id($category_id, $page, $per_page)
  {


    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products?category=' . $category_id . '&page=' . $page . '&per_page=' . $per_page;
    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = self::get_web_page($api_url, $data);
    return ($result);
  }


  public function update_product_category_id($product_id, $new_category_id)
  {
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/' . $product_id;

    $product_data = [
      'categories' => array(array('id' => $new_category_id))

    ];

    $data = array();

    $data["data"] = $product_data;
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = self::put_web_page($api_url, $data);
    return ($result);
  }

  public function get_woocommerce_product_id($product_id)
  {


    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products/' . $product_id;
    $data = array();
    $data["consumer_key"] = $consumer_key;
    $data["consumer_secret"] = $consumer_secret;
    $result = self::get_web_page($api_url, $data);
    return ($result);
  }

  public function sync_by_item_id_with_quantity($item_id, $is_variable_item)
  {
    $woocommerce_class = $this->model("woocommerce");
    $items_class = $this->model("items");
    $settings_info = self::getSettings();
    $website_url = $settings_info["woocommerce_url"];

    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';
    $api_url = $website_url . '/wp-json/wc/v3/products';




    require_once 'application/mvc/models/itemsModel.php';
    $items_class = new itemsModel();
    require_once 'application/mvc/models/storeModel.php';
    $store = new storeModel();
    $item_info = $items_class->get_item($item_id);

    $item_category_id = 0;
    $item_quantity = 0;

    if (count($item_info) > 0) {
      if ($item_info[0]["item_category"] != null || $item_info[0]["item_category"] > 0) {
        // get woocommerce category id from pos category id 
        $wocommerce_pos_cat_info = $woocommerce_class->get_woocommerce_category_by_pos_cat_id($item_info[0]["item_category"]);
        if (count($wocommerce_pos_cat_info) > 0) {
          $item_category_id = $wocommerce_pos_cat_info[0]["woocommerce_category_id"];
        }
      }
      $item_quantity_result =  $store->getQtyOfItemInAllStore($item_id);
      if ($item_quantity_result) {
        $item_quantity = $item_quantity_result[0]["quantity"];
      }
    }


    $image_path = "";
    $image_info = $items_class->getImageItem_by_id($item_id);

    if (count($image_info) > 0) {


      // Image file path
      $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
      $image_path = array(
        'src' => $current_url . "/data/images_items/" . $image_info[0]["name"] // Replace with your image URL
      );
    }

    $result = array("response" => "Item is not found.", "http_code" => "404"); // 404 Not found code 

    if ($is_variable_item == 0) {
      $product_type = "simple";
      $attributes = "";
    } else {
      $product_type = "variable";
      require_once 'application/mvc/controllers/variation.php';
      $variation_class = new variation();
      $attributes = ($variation_class->list_all_item_attributes());
    }
    if (count($item_info) > 0) {

      // Product data
      $product_data = [
        'name' => $item_info[0]["item_alias"],
        'type' => $product_type,
        'regular_price' => $item_info[0]["selling_price"],
        'description' => $item_info[0]["description"], // will change
        'sku' => $item_info[0]["sku_code"],
        'weight' => $item_info[0]["weight"],
        'short_description' => $item_info[0]["description"],
        'categories' => array(array('id' => $item_category_id)),
        // Replace 1 with the category ID you want to assign
        'images' => array(
          $image_path
        ),
        'manage_stock' => true,
        'stock_quantity' => $item_quantity

      ];
      if (!empty($attributes)) {
        $product_data['attributes'] = $attributes;
      }
      $data = array();

      $data["data"] = $product_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::post_web_page($api_url, $data);
    }
    // Print the response
    return $result;
  }


  public function update_item_by_id_with_quantity($pos_item_id, $woc_product_id, $is_variable_item)
  {

    $woocommerce_class = $this->model("woocommerce");

    // WooCommerce API URL and credentials
    $consumer_key = 'ck_fb6f6a121c06a3aba3f322919ee3f8e198ca4583';
    $consumer_secret = 'cs_0124193bf1285f252aaa960cdcb4e26d4241c20d';

    if ($is_variable_item == 0) {
      $product_type = "simple";
      $attributes = "";
    } else {
      $product_type = "variable";
      require_once 'application/mvc/controllers/variation.php';
      $variation_class = new variation();
      $attributes = ($variation_class->list_all_item_attributes());
    }

    $api_url = 'https://tekpluslb.com/wp-json/wc/v3/products/' . $woc_product_id;
    // Image file path
    // $image_path = "http://localhost/stock-and-pos/woocommerce_images/test.jpg";

    require_once 'application/mvc/models/itemsModel.php';
    $items_class = new itemsModel();
    require_once 'application/mvc/models/storeModel.php';
    $store = new storeModel();


    $item_info = $items_class->get_item($pos_item_id);

    $item_category_id = 0;
    $item_quantity = 0;
    if (count($item_info) > 0) {

      if ($item_info[0]["item_category"] != null || $item_info[0]["item_category"] > 0) {
        // get woocommerce category id from pos category id 
        $wocommerce_pos_cat_info = $woocommerce_class->get_woocommerce_category_by_pos_cat_id($item_info[0]["item_category"]);
        if (count($wocommerce_pos_cat_info) > 0) {
          $item_category_id = $wocommerce_pos_cat_info[0]["woocommerce_category_id"];
        }
      }

      $item_quantity_result =  $store->getQtyOfItemInAllStore($pos_item_id);
      if ($item_quantity_result) {
        $item_quantity = $item_quantity_result[0]["quantity"];
      }
    }



    $image_path = "";
    $image_info = $items_class->getImageItem_by_id($pos_item_id);

    if (count($image_info) > 0) {

      // Image file path

      $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";



      $image_path = array(
        'src' => $current_url . "/data/images_items/" . $image_info[0]["name"] // Replace with your image URL
      );
    }


    $result = array("response" => "Item is not found.", "http_code" => "404"); // 404 Not found code 

    if (count($item_info) > 0) {

      // Product data
      $product_data = [
        'name' => $item_info[0]["item_alias"],
        'type' => $product_type,
        'regular_price' => $item_info[0]["selling_price"],
        'description' => $item_info[0]["description"],
        'sku' => $item_info[0]["sku_code"],
        'weight' => $item_info[0]["weight"],
        'short_description' => $item_info[0]["description"],
        'categories' => array(array('id' => $item_category_id)),
        // Replace 1 with the category ID you want to assign
        'images' => array(
          $image_path
        ),
        'manage_stock' => true,
        'stock_quantity' => $item_quantity
      ];

      if (!empty($attributes)) {
        $product_data['attributes'] = $attributes;
      }
      $data = array();

      $data["data"] = $product_data;
      $data["consumer_key"] = $consumer_key;
      $data["consumer_secret"] = $consumer_secret;
      $result = self::put_web_page($api_url, $data);
    }
    // Print the response
    return $result;
  }
}
