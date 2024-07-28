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
class price_checker extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->settings_info = self::getSettings();
    }
    public function check()
    {
        $data = array();
        $data["qty"] = false;
        $data["description"] = true;
        $data["barcode"] = true;
        $data["price"] = true;
        $data["rate"] = false;
        $data["price_lbp"] = true;
        $this->view("reports/price_checker", $data);
    }
    public function price_checker($_barcode)
    {
        $barcode = filter_var($_barcode, self::conversion_php_version_filter());
        $barcode = preg_replace("/[^a-zA-Z0-9.]/", "", $barcode);
        $items = $this->model("items");
        $result = $items->get_item_by_barcode_to_check($barcode);
        if (0 < count($result)) {
            $result[0]["selling_price"] = number_format($result[0]["selling_price"], 2);
            $result[0]["quantity"] = number_format(floatval($result[0]["quantity"]), 2);
            $result[0]["usd_lbp_rate"] = number_format(floatval($this->settings_info["usdlbp_rate"]), 2);
            $result[0]["selling_price_lbp"] = number_format(floatval($result[0]["selling_price"]) * $this->settings_info["usdlbp_rate"], 0);
        }
        echo json_encode($result);
    }
}

?>