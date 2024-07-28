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
class packing extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function add_pack_qty($_p0, $_p1)
    {
        $store = $this->model("store");
        $items = $this->model("items");
        $item_id = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_p1, FILTER_SANITIZE_NUMBER_INT);
        $composite_info = $items->get_composite_item_id($item_id);
        $qty_info_c = array();
        if (0 < $qty) {
            $qty_info_c["qty"] = (0 - $composite_info[0]["qty"]) * $qty;
        } else {
            $qty_info_c["qty"] = $composite_info[0]["qty"] * abs($qty);
        }
        $qty_info_c["store_id"] = $_SESSION["store_id"];
        $qty_info_c["item_id"] = $composite_info[0]["item_id"];
        $qty_info_c["source"] = "pack";
        $store->add_qty($qty_info_c);
        $qty_info = array();
        $qty_info["qty"] = $qty;
        $qty_info["store_id"] = $_SESSION["store_id"];
        $qty_info["item_id"] = $item_id;
        $qty_info["source"] = "manual";
        $store->add_pack_qty($qty_info);
        echo json_encode(array());
    }
    public function get_packing($_p0, $_p1, $_p2, $_p3, $_p4)
    {
        $items = $this->model("items");
        $info_composite = $items->get_all_packs();
        $data_array["data"] = array();
        $data_array["total"] = 0;
        for ($i = 0; $i < count($info_composite); $i++) {
            $tmp = array();
            array_push($tmp, $info_composite[$i]["id"]);
            array_push($tmp, $info_composite[$i]["description"]);
            array_push($tmp, "<button type=\"button\" class=\"btn btn-info btn-xs\" style=\"width:100%\" onclick=\"addPacks(" . $info_composite[$i]["id"] . ")\">" . rtrim(rtrim($info_composite[$i]["packs_nb"], "0"), ".") . "</button>");
            array_push($tmp, $info_composite[$i]["c_item_id"]);
            array_push($tmp, $info_composite[$i]["it2description"]);
            array_push($tmp, rtrim(rtrim($info_composite[$i]["qty"], "0"), "."));
            $t = $info_composite[$i]["packs_nb"] * $info_composite[$i]["buying_cost"];
            array_push($tmp, number_format($t, 2));
            $data_array["total"] += $t;
            array_push($data_array["data"], $tmp);
        }
        $data_array["total"] = number_format($data_array["total"], 2);
        echo json_encode($data_array);
    }
}

?>