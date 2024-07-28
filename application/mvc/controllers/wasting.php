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
class wasting extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
    }
    public function delete($_id)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, self::conversion_php_version_filter());
        $wasting = $this->model("wasting");
        $wasting->delete($id);
        echo json_encode(array());
    }
    public function wasting_clear()
    {
        $wasting = $this->model("wasting");
        $wasting->wasting_clear();
        echo json_encode(array());
    }
    public function get_all_wasting_items($_p0, $_p1, $_p2)
    {
        self::giveAccessTo(array(2, 4));
        $setting = self::getSettings();
        $wasting = $this->model("wasting");
        $wasting_info = $wasting->get_all_wasting_items_not_cleared();
        $user = $this->model("user");
        $users_info = $user->getAllUsersEvenDeleted();
        $users_info_array = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $users_info_array[$users_info[$i]["id"]] = $users_info[$i]["username"];
        }
        $items = $this->model("items");
        $all_items = $items->getAllItemsEvenDeleted();
        $all_items_array = array();
        for ($i = 0; $i < count($all_items); $i++) {
            $all_items_array[$all_items[$i]["id"]] = $all_items[$i];
        }
        $data_array["data"] = array();
        $total = 0;
        for ($i = 0; $i < count($wasting_info); $i++) {
            $tmp = array();
            array_push($tmp, $wasting_info[$i]["id"]);
            array_push($tmp, $all_items_array[$wasting_info[$i]["item_id"]]["description"]);
            array_push($tmp, $wasting_info[$i]["note"]);
            $total += $wasting_info[$i]["price"] * $wasting_info[$i]["qty"];
            array_push($tmp, self::value_format_custom($wasting_info[$i]["price"], $setting));
            array_push($tmp, self::value_format_custom($wasting_info[$i]["qty"], $setting));
            array_push($tmp, self::value_format_custom($wasting_info[$i]["price"] * $wasting_info[$i]["qty"], $setting));
            array_push($tmp, $users_info_array[$wasting_info[$i]["user_id"]]);
            array_push($tmp, $wasting_info[$i]["type"]);
            array_push($tmp, $wasting_info[$i]["creation_date"]);
            array_push($tmp, "");
            if ($_SESSION["cashbox_id"] == $wasting_info[$i]["cashbox_id"] || $_SESSION["role"] == 1) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }
            array_push($data_array["data"], $tmp);
        }
        $data_array["total"] = number_format($total, 2);
        echo json_encode($data_array);
    }
    public function delete_wasting_id($_id)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, self::conversion_php_version_filter());
        $wasting = $this->model("wasting");
        $store = $this->model("store");
        $deleted_info = $wasting->get_wasting_by_id($id);
        $wasting->delete($id);
        $info_add_qty["qty"] = 1;
        $info_add_qty["item_id"] = $deleted_info[0]["item_id"];
        $info_add_qty["store_id"] = $_SESSION["store_id"];
        $info_add_qty["source"] = "WA-" . $id;
        $store->add_qty($info_add_qty);
        echo json_encode(array());
    }
    public function add_wasting_item_by_id($_id, $_note, $_qty)
    {
        self::giveAccessTo(array(2, 4));
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_qty, FILTER_SANITIZE_NUMBER_FLOAT);
        $note = filter_var($_note, self::conversion_php_version_filter());
        $items = $this->model("items");
        $store = $this->model("store");
        if (!$qty) {
            $qty = 0;
        }
        $item_info = $items->get_item($id);
        if (0 < count($item_info)) {
            $info["id_to_edit"] = 0;
            $info["item_id"] = $item_info[0]["id"];
            $info["user_id"] = $_SESSION["id"];
            $info["cost"] = $item_info[0]["buying_cost"];
            $info["type"] = 1;
            $info["note"] = $note;
            $info["qty"] = $qty;
            $info["price"] = $item_info[0]["selling_price"];
            $return_id = self::add($info);
            $info_add_qty["qty"] = 0 - $qty;
            $info_add_qty["item_id"] = $item_info[0]["id"];
            $info_add_qty["store_id"] = $_SESSION["store_id"];
            $info_add_qty["source"] = "WA-" . $return_id["id"];
            $store->add_qty($info_add_qty);
        }
        echo json_encode(array());
    }
    public function add($info)
    {
        self::giveAccessTo(array(2, 4));
        $wasting = $this->model("wasting");
        if ($info["id_to_edit"] == 0) {
            $last_id = $wasting->add($info);
        } else {
            $last_id = $info["id_to_edit"];
            $wasting->update($info);
        }
        $info_return = array();
        $info_return["id"] = $last_id;
        return $info_return;
    }
}

?>