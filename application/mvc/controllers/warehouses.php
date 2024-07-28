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
class warehouses extends Controller
{
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
    }
    public function _default()
    {
        self::giveAccessTo();
        $this->view("warehouses");
    }
    public function getAllWarehouses()
    {
        self::giveAccessTo();
        $warehouses = $this->model("warehouses");
        $info = $warehouses->getAllWarehouses();
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_warehouse($info[$i]["id"]));
            array_push($tmp, $info[$i]["location"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllWarehouses_()
    {
        self::giveAccessTo();
        $warehouses = $this->model("warehouses");
        $info = $warehouses->getAllWarehouses();
        $info_return = array();
        for ($i = 0; $i < count($info); $i++) {
            $info_return[$i]["id"] = self::idFormat_warehouse($info[$i]["id"]);
            $info_return[$i]["location"] = $info[$i]["location"];
        }
        echo json_encode($info_return);
    }
    public function add_new_warehouse()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["warehouse_desc"] = filter_input(INPUT_POST, "warehouse_desc", self::conversion_php_version_filter());
        $warehouses = $this->model("warehouses");
        $info_return = NULL;
        if ($info["id_to_edit"] == 0) {
            $info_return = $warehouses->add_new_warehouse($info);
        } else {
            $warehouses->update_warehouse($info);
        }
        $return = array();
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        $return["warehouse_desc"] = $info["warehouse_desc"];
        echo json_encode($return);
    }
    public function get_warehouse($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $warehouses = $this->model("warehouses");
        $info = $warehouses->get_warehouse($id);
        echo json_encode($info);
    }
    public function delete_warehouse($id_)
    {
        self::giveAccessTo();
        $warehouses = $this->model("warehouses");
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $return = array();
        $return["status"] = $warehouses->delete_warehouse($id);
        echo json_encode($return);
    }
    public function logout()
    {
        session_destroy();
        header("location: ./");
    }
}

?>