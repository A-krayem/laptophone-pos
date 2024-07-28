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
class authorize extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function delete_auth($_id)
    {
        $authorization = $this->model("authorization");
        $users = $this->model("user");
        $global_logs = $this->model("global_logs");
        $res = $users->get_user_by_id($_SESSION["id"]);
        if ($this->settings_info["enable_authorization_code"] == 0 || $res[0]["authorization_required"] == 1) {
            exit;
        }
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $authorization->delete_auth($id);
        $logs_info = array();
        $logs_info["operator_id"] = $_SESSION["id"];
        $logs_info["related_to_item_id"] = 0;
        $logs_info["description"] = "Autorized user/Device (" . $id . ") has been deleted";
        $logs_info["log_type"] = 2;
        $logs_info["other_info"] = "";
        $global_logs->add_global_log($logs_info);
        echo json_encode(array());
    }
    public function get_authorized_devices()
    {
        $authorization = $this->model("authorization");
        $users = $this->model("user");
        $res = $users->get_user_by_id($_SESSION["id"]);
        $cookie_name = "skeyUp";
        $athorized = false;
        if ($authorization->user_authorized($_SESSION["id"], $_COOKIE[$cookie_name]) && $_SESSION["hide_critical_data"] == 0) {
            $athorized = true;
        }
        if ($this->settings_info["enable_authorization_code"] == 0 || $athorized == false || $_SESSION["hide_critical_data"] == 1) {
            exit;
        }
        $employees_info = $users->getAllUsersEvenDeleted();
        $employees_info_array = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
        }
        $all_authorization = $authorization->all_authorization();
        $data_array["data"] = array();
        for ($i = 0; $i < count($all_authorization); $i++) {
            $tmp = array();
            array_push($tmp, $all_authorization[$i]["creation_date"]);
            array_push($tmp, $all_authorization[$i]["operator_id"]);
            array_push($tmp, $employees_info_array[$all_authorization[$i]["operator_id"]]);
            array_push($tmp, "<b>" . $all_authorization[$i]["authorized_key"] . "</b>");
            if ($all_authorization[$i]["accepted"] == 1) {
                array_push($tmp, "<b class='text-success'>Active</b>");
            } else {
                array_push($tmp, "<b class='text-danger'>Disactive</b>");
            }
            array_push($tmp, "<button style=\"width: 100%;\" type=\"button\" class=\"btn btn-danger btn-xs\" onclick=\"delete_auth(" . $all_authorization[$i]["id"] . ")\">Delete</button>");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>