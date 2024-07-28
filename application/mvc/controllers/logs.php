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
class logs extends Controller
{
    public $licenseExpired = false;
    public function color()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
    }
    public function user_customers_logs()
    {
        $data = array();
        $this->view("logs/user_customers", $data);
    }
    public function get_needed_data()
    {
        $data = array();
        $user = $this->model("user");
        $data["users"] = $user->getAllUsersEvenDeleted();
        echo json_encode($data);
    }
    public function prepare_customer_user_log_text($text, $all_users_info)
    {
        $user = $this->model("user");
        $description = "";
        $text_split = explode("##", $text["description"]);
        for ($i = 0; $i < count($text_split); $i++) {
            if ($text["action_type"] == "created") {
                if (count($all_users_info) == 0) {
                    $info_user = array();
                    $info_user["id"] = $text["user_id"];
                    $user_info = $user->get_user($info_user);
                    $usn = $user_info[0]["username"];
                } else {
                    $usn = $all_users_info[$text["user_id"]]["username"];
                }
                $date = date_create($text["action_date"]);
                $description .= "Created by <b>" . strtoupper($usn) . "</b> on <b>" . date_format($date, "l jS \\of F Y h:i:s A") . "</b> <br/>";
            } else {
                if (count($all_users_info) == 0) {
                    $info_user = array();
                    $info_user["id"] = $text["user_id"];
                    $user_info = $user->get_user($info_user);
                    $usn = $user_info[0]["username"];
                } else {
                    $usn = $all_users_info[$text["user_id"]]["username"];
                }
                $date = date_create($text["action_date"]);
                $text_split_d = explode("#", $text_split[$i]);
                if (count($text_split_d) == 3) {
                    if (trim($text_split_d[1]) == "0") {
                        $text_split_d[1] = "Empty";
                    }
                    if ($text_split_d[1] != " " && $text_split_d[2] != " NULL ") {
                        $description .= "<b>" . $text_split_d[0] . "</b> updated from <b>" . $text_split_d[1] . "</b> to <b>" . $text_split_d[2] . "</b> by <b>" . strtoupper($usn) . "</b> on <b>" . date_format($date, "l jS \\of F Y h:i:s A") . "</b><br/>";
                    }
                }
            }
        }
        return $description;
    }
    public function get_user_customers_logs_by_id($_id)
    {
        $logs = $this->model("logs");
        $customers = $this->model("customers");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $info["logs"] = $logs->get_user_customers_logs_by_id($id);
        $customer_info = $customers->getCustomersById($id);
        $description_cs = "<b>" . strtoupper($customer_info[0]["name"]) . " " . strtoupper($customer_info[0]["middle_name"]) . " " . strtoupper($customer_info[0]["last_name"]) . "</b><br/>";
        $info["customer_info"] = $description_cs;
        for ($i = 0; $i < count($info["logs"]); $i++) {
            $info["logs"][$i]["description"] = self::prepare_customer_user_log_text($info["logs"][$i], array());
        }
        echo json_encode($info);
    }
    public function get_user_customers_logs($_date_filter, $_user_id, $p2, $p3)
    {
        $user = $this->model("user");
        $logs = $this->model("logs");
        $user_id = filter_var($_user_id, FILTER_SANITIZE_NUMBER_INT);
        $date_filter = filter_var($_date_filter, self::conversion_php_version_filter());
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($date_filter == "today") {
            $date_range[0] = date("Y-m-d");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date_filter);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $customers = $this->model("customers");
        $all_customer_info = array();
        $customer_info = $customers->getCustomersEvenDeleted();
        for ($i = 0; $i < count($customer_info); $i++) {
            $all_customer_info[$customer_info[$i]["id"]] = $customer_info[$i];
        }
        $all_users_info = array();
        $users_info = $user->getAllUsersEvenDeleted();
        for ($i = 0; $i < count($users_info); $i++) {
            $all_users_info[$users_info[$i]["id"]] = $users_info[$i];
        }
        $info = $logs->get_user_customers_logs($date_range, $user_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($info); $i++) {
            $description_cs = "<b>" . strtoupper($all_customer_info[$info[$i]["customer_id"]]["name"]) . " " . strtoupper($all_customer_info[$info[$i]["customer_id"]]["middle_name"]) . " " . strtoupper($all_customer_info[$info[$i]["customer_id"]]["last_name"]) . "</b><br/>";
            $tmp = array();
            if ($info[$i]["action_type"] == "created") {
                array_push($tmp, "<span class='cr'>Create</span>");
            } else {
                array_push($tmp, "<span class='up'>Update</span>");
            }
            array_push($tmp, $info[$i]["action_date"]);
            array_push($tmp, self::prepare_customer_user_log_text($info[$i], $all_users_info));
            array_push($tmp, $description_cs);
            array_push($tmp, "");
            array_push($tmp, $info[$i]["customer_id"]);
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>