<?php

class variation extends Controller
{

    public $settings_info = null;

    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }


    public function get_all_items_variation()
    {
        $return_info = array();
        $items = $this->model("items");

        $all_items = $items->get_all_items_variation();

        echo json_encode($all_items);
    }

    public function items_variation_details($group_id)
    {
        $return_info = array();
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

        $variation_info = array();
        $variation_info_details = array();
        $all_variations=array();
        $all_variations["variations"]=array();
        $all_variations["variation_details"]=array();
        $all_items = $items->items_variation_details($group_id);
        for ($i = 0; $i < count($all_items); $i++) {
            $c_colors = array('name' => 'Color', 'option' => $colors_array[$all_items[$i]["color_text_id"]]);
            $c_sizes = array('name' => 'Size', 'option' => $sizes_array[$all_items[$i]["size_id"]]);
            array_push(
                $all_variations["variations"],
                array(
                    "description" => $all_items[$i]["description"],
                    "regular_price" => $all_items[$i]["selling_price"],
                    'attributes' => array(
                        $c_sizes,
                        $c_colors,
                    ),
                )
            );

            array_push(
                $all_variations["variation_details"],
                array(
                    "pos_item_id" => $all_items[$i]["id"],
                    "description" => $all_items[$i]["description"],
                    "regular_price" => $all_items[$i]["selling_price"],
                    'attributes' => array(
                        $c_sizes,
                        $c_colors,
                    ),
                )
            );
        }

   

        return($all_variations);
    }


    public function list_all_item_attributes()
    {

        $attributes =[];// array("Color" => array(), "Size" => array());

        $colors = $this->model("colors");
        $sizes = $this->model("sizes");

        $colors_info = $colors->getColorsText();
        $sizes_info = $sizes->getSizes();
      
        $attributes[] = [
            'name' => 'Color',
            'options' => array_column($colors_info, 'name'),
            'visible' => true,
            'variation' => true
        ];
        
        $attributes[] = [
            'name' => 'Size',
            'options' => array_column($sizes_info, 'name'),
            'visible' => true,
            'variation' => true
        ];
        return($attributes);

    }
  
}
