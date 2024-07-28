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
class sms extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
        $sms = $this->model("sms");
        $sms->update_balance(self::get_account_balance());
        $this->settings_info = self::getSettings();
    }
    public function list_of_sms()
    {
        self::giveAccessTo();
        $data = array();
        $data["sms_balance"] = number_format(0, 5) . " \$";
        $this->view("list_of_sms", $data);
    }
    public function get_all_txt()
    {
        self::giveAccessTo();
        $data = array();
        $sms = $this->model("sms");
        $info = $sms->get_all_phone_as_txt();
        for ($i = 0; $i < count($info); $i++) {
            $data["txt"] .= $info[$i]["phone"] . "\n";
        }
        echo json_encode($data);
    }
    public function list_of_customer($sms_id, $_p1, $_p2)
    {
        $sms = $this->model("sms");
        $sms_status = $sms->getAllSmsStatus();
        $sms_status_array = array();
        for ($i = 0; $i < count($sms_status); $i++) {
            $sms_status_array[$sms_status[$i]["id"]] = $sms_status[$i];
        }
        $customers = $sms->list_of_customer($sms_id);
        $data_array["data"] = array();
        for ($i = 0; $i < count($customers); $i++) {
            $tmp = array();
            array_push($tmp, $customers[$i]["id"]);
            array_push($tmp, "<b>" . $customers[$i]["name"] . "</b>");
            array_push($tmp, $customers[$i]["phone"]);
            if ($customers[$i]["status_id"] == 2) {
                array_push($tmp, "<b class='sent'>" . $sms_status_array[$customers[$i]["status_id"]]["name"] . "</b>");
            } else {
                if ($customers[$i]["status_id"] == 3) {
                    array_push($tmp, "<b class='failed'>" . $sms_status_array[$customers[$i]["status_id"]]["name"] . "</b>");
                } else {
                    array_push($tmp, $sms_status_array[$customers[$i]["status_id"]]["name"]);
                }
            }
            array_push($tmp, $customers[$i]["sent_date"]);
            if ($customers[$i]["status_id"] == 1) {
                array_push($tmp, $customers[$i]["excluded"]);
            } else {
                array_push($tmp, -1);
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        $data_array["_total_sent"] = $sms->get_total_sent($sms_id);
        $data_array["_total_failed"] = $sms->get_total_failed($sms_id);
        $data_array["_total_pending"] = $sms->get_total_pending($sms_id);
        echo json_encode($data_array);
    }
    public function exclude($_id, $_exclude)
    {
        $sms = $this->model("sms");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $exclude = filter_var($_exclude, FILTER_SANITIZE_NUMBER_INT);
        $sms->exclude($id, $exclude);
        echo json_encode(array($id));
    }
    public function collected_nb()
    {
        $sms = $this->model("sms");
        $nb = $sms->collected_nb();
        echo json_encode($nb);
    }
    public function get_sms_by_id($_id)
    {
        $sms = $this->model("sms");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $info = $sms->get_sms_by_id($id);
        echo json_encode($info);
    }
    public function getAllSms($_p0, $_p1, $_p2, $_p3, $_p4)
    {
        $sms = $this->model("sms");
        $customers = $this->model("customers");
        $all_sms = $sms->getAllSms();
        $data_array["data"] = array();
        for ($i = 0; $i < count($all_sms); $i++) {
            $tmp = array();
            $customers_info = $sms->getSmsCustomers($all_sms[$i]["id"]);
            $prices_info = $sms->getTotalSpent($all_sms[$i]["id"]);
            array_push($tmp, $all_sms[$i]["id"]);
            array_push($tmp, $all_sms[$i]["title"]);
            array_push($tmp, $all_sms[$i]["body"]);
            array_push($tmp, $all_sms[$i]["creation_date"]);
            array_push($tmp, $all_sms[$i]["start_date"]);
            array_push($tmp, number_format(count($customers_info), 0));
            array_push($tmp, "");
            array_push($tmp, number_format($prices_info[0]["sum"], 5));
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function send_test($_sms_id, $_nb)
    {
        $return["status"] = 0;
        $return["balance"] = $this->settings_info["sms_balance"];
        if (!self::is_connected()) {
            $return["status"] = -1;
            echo json_encode($return);
        } else {
            if (strlen($this->settings_info["sms_username"]) == 0 || strlen($this->settings_info["sms_password"]) == 0) {
                $return["status"] = -2;
                echo json_encode($return);
            } else {
                $sms = $this->model("sms");
                $sms_id = filter_var($_sms_id, FILTER_SANITIZE_NUMBER_INT);
                $sms_info = $sms->get_sms_by_id($sms_id);
                $nb = $sms->validate_phone_number($_nb);
                $url = $this->settings_info["sms_provider"] . "?usr=" . $this->settings_info["sms_username"] . "&pwd=" . $this->settings_info["sms_password"] . "&to=" . $nb . "&msg=" . urlencode($sms_info[0]["body"]) . "&from=" . urlencode($this->settings_info["sender_id"]);
                $action_response = self::send_sms($url);
                $data_json = json_decode($action_response, true);
                if ($data_json["response"] == "success") {
                    $return["balance"] = $data_json["balance"];
                    $sms->add_test($sms_id, $data_json["price"]);
                }
                $sms->update_balance($return["balance"]);
                $return["balance"] = number_format($return["balance"], 5) . " \$";
                echo json_encode(array($return));
            }
        }
    }
    public function get_account_balance()
    {
        $url = "http://www.upsilonsms.com/balance.php?usr=" . $this->settings_info["sms_username"] . "&pwd=" . $this->settings_info["sms_password"];
        $action_response = self::send_sms($url);
        $data_json = json_decode($action_response, true);
        return $data_json["balance"];
    }
    public function start_sending($_sms_id)
    {
        $return["status"] = 0;
        $return["balance"] = $this->settings_info["sms_balance"];
        if (!self::is_connected()) {
            $return["status"] = -1;
            echo json_encode($return);
        } else {
            if (strlen($this->settings_info["sms_username"]) == 0 || strlen($this->settings_info["sms_password"]) == 0) {
                $return["status"] = -2;
                echo json_encode($return);
            } else {
                $sms = $this->model("sms");
                $sms_id = filter_var($_sms_id, FILTER_SANITIZE_NUMBER_INT);
                $sms_info = $sms->get_sms_by_id($sms_id);
                $customers = $sms->getSmsCustomers_to_send($sms_id, 5);
                for ($i = 0; $i < count($customers); $i++) {
                    $url = $this->settings_info["sms_provider"] . "?usr=" . $this->settings_info["sms_username"] . "&pwd=" . $this->settings_info["sms_password"] . "&to=" . ltrim($customers[$i]["phone"], "0") . "&msg=" . urlencode($sms_info[0]["body"]) . "&from=" . urlencode($this->settings_info["sender_id"]);
                    $action_response = self::send_sms($url);
                    $data_json = json_decode($action_response, true);
                    if ($data_json["response"] == "success") {
                        $return["balance"] = $data_json["balance"];
                        $sms->set_sent($customers[$i]["id"], $data_json["price"]);
                    } else {
                        $sms->set_failed($customers[$i]["id"]);
                    }
                }
                $sms->update_balance($return["balance"]);
                $return["balance"] = number_format($return["balance"], 5) . " \$";
                if (count($customers) == 0) {
                    $return["status"] = 1;
                }
                echo json_encode(array($return));
            }
        }
    }
    public function send_sms($url)
    {
        $options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => false, CURLOPT_FOLLOWLOCATION => true, CURLOPT_ENCODING => "", CURLOPT_USERAGENT => "spider", CURLOPT_AUTOREFERER => true, CURLOPT_CONNECTTIMEOUT => 120, CURLOPT_TIMEOUT => 120, CURLOPT_MAXREDIRS => 10, CURLOPT_SSL_VERIFYPEER => false);
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
        return $content;
    }
    public function add_new_sms()
    {
        $sms = $this->model("sms");
        $info = array();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["title"] = filter_input(INPUT_POST, "sms_title", self::conversion_php_version_filter());
        $info["body"] = filter_input(INPUT_POST, "sms_body", self::conversion_php_version_filter());
        $info["start_date"] = filter_input(INPUT_POST, "sms_start", self::conversion_php_version_filter());
        if (0 < $info["id_to_edit"]) {
            $returned_id = $info["id_to_edit"];
            $sms->update_sms($info);
        } else {
            $returned_id = $sms->add_new_sms($info);
            $customers = $this->model("customers");
            $customers_info = $customers->getCollectedCustomers();
            for ($i = 0; $i < count($customers_info); $i++) {
                if (strlen($customers_info[$i]["phone"]) == 11 || strlen($customers_info[$i]["phone"]) == 10) {
                    $sms->add_sms_details($customers_info[$i], $returned_id);
                }
            }
        }
        echo json_encode($returned_id);
    }
    public function delete_sms($_id)
    {
        $sms = $this->model("sms");
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $info = array();
        $info["id"] = $id;
        $sms->delete_sms($info);
        echo json_encode(array());
    }
}

?>