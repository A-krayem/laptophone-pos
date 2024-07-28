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
class login extends Controller
{
    public $enable_first_security_code = 0;
    public $cookie_name = "skeyUp";
    public function __construct()
    {
    }
    public function getBrowserInfo()
    {
        $user_agent = $_SERVER["HTTP_USER_AGENT"];
        $browser_info = array("user_agent" => $user_agent, "browser" => "", "version" => "", "platform" => "", "pattern" => "");
        if (preg_match("/MSIE/i", $user_agent) && !preg_match("/Opera/i", $user_agent)) {
            $browser_info["browser"] = "Internet Explorer";
            $browser_info["pattern"] = "MSIE";
        } else {
            if (preg_match("/Firefox/i", $user_agent)) {
                $browser_info["browser"] = "Mozilla Firefox";
                $browser_info["pattern"] = "Firefox";
            } else {
                if (preg_match("/Chrome/i", $user_agent)) {
                    $browser_info["browser"] = "Google Chrome";
                    $browser_info["pattern"] = "Chrome";
                } else {
                    if (preg_match("/Safari/i", $user_agent)) {
                        $browser_info["browser"] = "Safari";
                        $browser_info["pattern"] = "Safari";
                    } else {
                        if (preg_match("/Opera/i", $user_agent)) {
                            $browser_info["browser"] = "Opera";
                            $browser_info["pattern"] = "Opera";
                        } else {
                            if (preg_match("/Netscape/i", $user_agent)) {
                                $browser_info["browser"] = "Netscape";
                                $browser_info["pattern"] = "Netscape";
                            }
                        }
                    }
                }
            }
        }
        $known = array("Version", $browser_info["pattern"], "other");
        $pattern = "#(?<browser>" . join("|", $known) . ")[/ ]+(?<version>[0-9.|a-zA-Z.]*)#";
        if (!preg_match_all($pattern, $user_agent, $matches)) {
        }
        $i = count($matches["browser"]);
        if ($i != 1) {
            if (strripos($user_agent, "Version") < strripos($user_agent, $browser_info["pattern"])) {
                $browser_info["version"] = $matches["version"][0];
            } else {
                $browser_info["version"] = $matches["version"][1];
            }
        } else {
            $browser_info["version"] = $matches["version"][0];
        }
        if (preg_match("/linux/i", $user_agent)) {
            $browser_info["platform"] = "linux";
        } else {
            if (preg_match("/macintosh|mac os x/i", $user_agent)) {
                $browser_info["platform"] = "mac";
            } else {
                if (preg_match("/windows|win32/i", $user_agent)) {
                    $browser_info["platform"] = "windows";
                }
            }
        }
        return $browser_info;
    }
    public function authorize()
    {
        $data = array();
        $data["sc"] = 0;
        $data["url"] = "";
        $authorization = $this->model("authorization");
        $settings_data["settings"] = self::getSettings();
        if (isset($settings_data["settings"]["cloudflare_enable_turnstile"]) && $settings_data["settings"]["cloudflare_enable_turnstile"] == 1) {
            $cf_turnstile_response = filter_input(INPUT_POST, "cf-turnstile-response", self::conversion_php_version_filter());
            $turnstile_verified = self::turnstile_verified($cf_turnstile_response);
            if (!$turnstile_verified) {
                $data["sc"] = 2;
                $data["url"] = "";
                echo json_encode($data);
                exit;
            }
        }
        $scode = filter_input(INPUT_POST, "scode", self::conversion_php_version_filter());
        if (isset($_COOKIE[$this->cookie_name])) {
            if ($scode == $_COOKIE[$this->cookie_name]) {
                $affected = $authorization->set_as_authorized($_SESSION["id"], $scode);
                if (0 < $affected) {
                    $data["sc"] = 1;
                    $_SESSION["locked"] = 0;
                    if ($_SESSION["role"] == 1) {
                        $data["url"] = "index.php?r=dashboard";
                    }
                    if ($_SESSION["role"] == 2) {
                        $data["url"] = "index.php?r=pos";
                    }
                }
            } else {
                $log = array();
                $log["browser_info"] = json_encode(self::getBrowserInfo());
                $log["ip"] = self::getClientIp();
                $authorization->authorization_log($log);
                $store = $this->model("store");
                $user = $this->model("user");
                $cookie_value = self::generateSmallKey();
                $cookie_name = $this->cookie_name;
                setcookie($cookie_name, $cookie_value, time() + 86400 * 720, "/");
                $authorization_info = array();
                $authorization_info["operator_id"] = $_SESSION["id"];
                $authorization_info["authorized_key"] = $cookie_value;
                $authorization->update_authorization_code($authorization_info);
                if ($settings_data["settings"]["telegram_enable"] == 1) {
                    $employees_info = $user->getAllUsersEvenDeleted();
                    $employees_info_array = array();
                    for ($i = 0; $i < count($employees_info); $i++) {
                        $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                    }
                    $store_info = $store->getStoresById($_SESSION["store_id"]);
                    $info_tel = array();
                    $info_tel["message"] = "<strong>Authorization Code:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                    $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                    $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                    $info_tel["message"] .= "<strong>Authorization Code:</strong> " . $cookie_value . " \n";
                    self::send_to_telegram($info_tel, 1);
                }
            }
        }
        echo json_encode($data);
    }
    public function _default()
    {
        $data = array();
        $data["settings"] = self::getSettings();
        $settings = $this->model("settings");
        $settings->update_value(VERSION, "version");
        $data["settings"] = self::getSettings();
        if (isset($_SESSION)) {
        }
        $data["cloudflare_enable_turnstile"] = 0;
        if (isset($data["settings"]["cloudflare_enable_turnstile"])) {
            $data["cloudflare_enable_turnstile"] = $data["settings"]["cloudflare_enable_turnstile"];
        }
        $this->view("login_1", $data);
    }
    public function update_online_info($settings)
    {
        $url = $settings["url_request"];
        $myvars = "update_info=1&customer_id=" . $settings["customer_id"];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $response = curl_exec($ch);
    }
    public function loginUser()
    {
        $data = array();
        $username = filter_input(INPUT_POST, "username", self::conversion_php_version_filter());
        $password = filter_input(INPUT_POST, "password", self::conversion_php_version_filter());
        $settings_data["settings"] = self::getSettings();
        if (isset($settings_data["settings"]["cloudflare_enable_turnstile"]) && $settings_data["settings"]["cloudflare_enable_turnstile"] == 1) {
            $cf_turnstile_response = filter_input(INPUT_POST, "cf-turnstile-response", self::conversion_php_version_filter());
            $turnstile_verified = self::turnstile_verified($cf_turnstile_response);
            if (!$turnstile_verified) {
                echo json_encode(array(2));
                exit;
            }
        }
        $user = $this->model("user");
        $settings = $this->model("settings");
        $stock = $this->model("stock");
        $store = $this->model("store");
        $cashbox = $this->model("cashbox");
        $info = array();
        $info["username"] = $username;
        $info["password"] = $password;
        $res = $user->getUserInfo($info);
        if (0 < count($res)) {
            if (isset($_SESSION["locked"]) && $_SESSION["locked"] == 1) {
                session_destroy();
                session_start();
            }
            $_SESSION["upsilon_version"] = $settings_data["settings"]["version"];
            if ($settings_data["settings"]["encode_and_online_backup"] == "1") {
                $_SESSION["online_log_query"] = 1;
                $_SESSION["path_log_query"] = $settings_data["settings"]["backup_path"];
            }
            self::update_online_info($settings_data["settings"]);
            if (isset($_SESSION["id"]) && $_SESSION["role"] == 2 && $res[0]["role_id"] == 2 && $res[0]["id"] == $_SESSION["id"]) {
                echo json_encode(array("pos"));
                exit;
            }
            if (isset($_SESSION["id"]) && $_SESSION["role"] == 2 && $res[0]["role_id"] == 1) {
                echo json_encode(array("posloggedin"));
                exit;
            }
            if (isset($_SESSION["id"]) && $_SESSION["role"] == 1 && $res[0]["role_id"] == 1 && $res[0]["id"] == $_SESSION["id"]) {
                echo json_encode(array("admin"));
                exit;
            }
            if (isset($_SESSION["id"]) && $_SESSION["role"] == 1 && $res[0]["role_id"] == 2) {
                echo json_encode(array("adminloggedin"));
                exit;
            }
            if (defined("OMT_CLIENT") && OMT_CLIENT == true && $res[0]["role_id"] == 2 && $cashbox->main_cashbox_is_open($res[0]["id"]) == 0) {
                echo json_encode(array("main_cashbox_is_not_open"));
                exit;
            }
            $_SESSION["id"] = $res[0]["id"];
            $_SESSION["role"] = $res[0]["role_id"];
            $_SESSION["demo"] = $res[0]["demo"];
            $_SESSION["store_id"] = $res[0]["store_id"];
            $_SESSION["hide_critical_data"] = $res[0]["hide_critical_data"];
            $_SESSION["2fa_enabled"] = $res[0]["ga_2fa_enabled"];
            if ($res[0]["ga_2fa_enabled"] == 1) {
                $_SESSION["2fa_locked"] = 1;
            } else {
                $_SESSION["2fa_locked"] = 0;
            }
            if ($_SESSION["hide_critical_data"] == 0 && $settings_data["settings"]["notification_for_password_security_check"] == 1 && $settings_data["settings"]["is_demo_version"] == 0) {
                self::check_passwords_if_strong($_SESSION["id"]);
            }
            if ($settings_data["settings"]["telegram_enable"] == 1 && $_SESSION["role"] == 1) {
                $employees_info = $user->getAllUsersEvenDeleted();
                $employees_info_array = array();
                for ($i = 0; $i < count($employees_info); $i++) {
                    $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                }
                $store_info = $store->getStoresById($_SESSION["store_id"]);
                $info_tel = array();
                $info_tel["message"] = "<strong>Admin Logged in:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                self::send_to_telegram($info_tel, 1);
            }
            $_SESSION["operator_is_admin"] = $res[0]["operator_is_admin"];
            $_SESSION["currency_symbol"] = $settings_data["settings"]["default_currency_symbol"];
            $todayCashbox = $cashbox->getTodayCashbox($_SESSION["store_id"], $_SESSION["id"]);
            if ($res[0]["role_id"] == 2 && 0 < count($info)) {
                $_SESSION["cashbox_id"] = $todayCashbox[0]["id"];
            }
            $_SESSION["username"] = $res[0]["username"];
            $_SESSION["check_key"] = self::generateRandomStringComplex_key(20);
            $user->updateCustomerID(self::GetProcessor());
            $user->update_check_key($_SESSION["id"], $_SESSION["check_key"]);
            $settings->setCurrencySymbole();
            $_SESSION["currency_counnt"] = $store->get_currency_num();
            $stock->check_stock_movement($_SESSION["store_id"]);
            $store_info = $store->getStoresById($res[0]["store_id"]);
            $store_global = $store->getStoresGlobal();
            $_SESSION["centralize"] = 0;
            if ($store_info[0]["primary_db"] == 1) {
                $_SESSION["centralize"] = 1;
            }
            $_SESSION["page_title"] = "";
            $_SESSION["global_admin_exist"] = 0;
            if (0 < $store_global[0]["num"]) {
                $_SESSION["global_admin_exist"] = 1;
                $_SESSION["page_title"] = $store_info[0]["name"] . " - ";
            }
            if ($_SESSION["global_admin_exist"] == 1) {
                $_SESSION["to_sync"] = 1;
            }
            $_SESSION["store_name"] = $store_info[0]["name"];
            $data[0] = $res[0]["role_id"];
            $code = self::mc_decrypt($settings_data["settings"]["activation_code"], ENCRYPTION_KEY_1);
            $code_exploded = explode("_", $code);
            if ($code_exploded[1] - $settings_data["settings"]["show_expiry_date_alert_before_days"] < time()) {
                $_SESSION["warning_expire"] = $code_exploded[1];
            } else {
                $_SESSION["warning_expire"] = 0;
            }
            $_SESSION["ptype"] = $settings_data["settings"]["ptype"];
            $login_info = array();
            $login_info["user_id"] = $_SESSION["id"];
            $login_info["login_out"] = 1;
            $_SESSION["login_id_history"] = $user->login_history($login_info);
            $_SESSION["show_var_price"] = $settings_data["settings"]["show_var_price"];
            if ($settings_data["settings"]["enable_authorization_code"] == 1 && $res[0]["authorization_required"] == 1) {
                $cookie_name = $this->cookie_name;
                $authorization = $this->model("authorization");
                $authorization_exist = $authorization->authorization_exist($_SESSION["id"], $_COOKIE[$cookie_name]);
                if (!isset($_COOKIE[$cookie_name]) || $authorization_exist == 0) {
                    $_SESSION["locked"] = 1;
                    $cookie_value = self::generateSmallKey();
                    setcookie($cookie_name, $cookie_value, time() + 86400 * 720, "/");
                    $authorization_info = array();
                    $authorization_info["operator_id"] = $_SESSION["id"];
                    $authorization_info["authorized_key"] = $cookie_value;
                    $authorization_info["cookies"] = $_COOKIE[$this->cookie_name];
                    $authorization_info["browser_info"] = json_encode(self::getBrowserInfo());
                    $added = $authorization->add_authorization_code($authorization_info);
                    if ($settings_data["settings"]["telegram_enable"] == 1 && 0 < $added) {
                        $employees_info = $user->getAllUsersEvenDeleted();
                        $employees_info_array = array();
                        for ($i = 0; $i < count($employees_info); $i++) {
                            $employees_info_array[$employees_info[$i]["id"]] = $employees_info[$i]["username"];
                        }
                        $store_info = $store->getStoresById($_SESSION["store_id"]);
                        $info_tel = array();
                        $info_tel["message"] = "<strong>Authorization Code:</strong> " . $employees_info_array[$_SESSION["id"]] . " \n";
                        $info_tel["message"] .= "<strong>Date:</strong> " . date("Y-m-d H:i:s") . " \n";
                        $info_tel["message"] .= "<strong>Branch:</strong> " . $store_info[0]["name"] . " \n";
                        $info_tel["message"] .= "<strong>Authorization Code:</strong> " . $cookie_value . " \n";
                        self::send_to_telegram($info_tel, 1);
                    }
                } else {
                    $authorized = $authorization->check_authorization_code($_SESSION["id"], $_COOKIE[$cookie_name]);
                    if (!$authorized) {
                        $_SESSION["locked"] = 1;
                    } else {
                        $_SESSION["locked"] = 0;
                    }
                }
            }
        } else {
            $data[0] = 0;
        }
        echo json_encode($data);
    }
}

?>