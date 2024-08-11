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
class Controller extends general_function
{
    protected function checkAuth()
    {
        if (isset($_GET["r"]) && $_GET["r"] == "reports_generator") {
            return NULL;
        }
        if (isset($_GET["f"]) && $_GET["f"] == "backupNow") {
            return NULL;
        }
        if (isset($_GET["r"]) && $_GET["r"] == "price_checker") {
            return NULL;
        }
        if (isset($_GET["r"]) && $_GET["r"] == "nosession") {
            return NULL;
        }
        if (isset($_GET["f"]) && $_GET["f"] == "print_invoice") {
            return NULL;
        }
        $user = self::model("user");
        if (isset($_SESSION["id"])) {
            $user_info = $user->get_user_by_id($_SESSION["id"]);
            $global_settings = self::getSettings();
            if ($user_info[0]["deleted"] == 1) {
                header("Location: index.php");
                exit;
            }
            if ($user_info[0]["authorization_required"] == 1 && $global_settings["enable_authorization_code"] == 1) {
                $cookie_name = "skeyUp";
                $authorization_mpdel = self::model("authorization");
                $authorization_exist = $authorization_mpdel->authorization_requested($_SESSION["id"], $_COOKIE[$cookie_name]);
                if (count($authorization_exist) == 0) {
                    session_destroy();
                    header("Location: index.php");
                    exit;
                }
                if ($authorization_exist[0]["accepted"] == 0) {
                    $cloudflare_enable_turnstile = $global_settings["cloudflare_enable_turnstile"];
                    require_once "application/mvc/views/authorization_1.php";
                    exit;
                }
            }
        }
        if (isset($_SESSION["locked"]) && $_SESSION["locked"] == 1) {
            $cloudflare_enable_turnstile = $global_settings["cloudflare_enable_turnstile"];
            require_once "application/mvc/views/authorization_1.php";
            exit;
        }
        if (isset($_SESSION["id"]) && $user_info[0]["ga_2fa_enabled"] == 1) {
            if (!($_GET["f"] == "authorize" && $_GET["r"] == "ga_2fa")) {
                if (!isset($_SESSION["2fa_locked"])) {
                    require_once "application/mvc/views/authorization_2fa.php";
                    exit;
                }
                if ($_SESSION["2fa_locked"] == 1) {
                    require_once "application/mvc/views/authorization_2fa.php";
                    exit;
                }
            }
        }
        if (!isset($_SESSION["id"])) {
            header("Location: index.php");
            exit;
        }
        $user_check_key = $user->get_user_check_key($_SESSION["id"]);
    }
    protected function model($model)
    {
        $model .= "Model";
        require_once "application/mvc/models/" . $model . ".php";
        return new $model();
    }
    public function view($view, $data = array())
    {
        $global_settings = self::getSettings();
        require_once "application/mvc/" . MAIN_VIEW . "/" . $view . ".php";
    }
    public function idFormat_sysuser($id)
    {
        return "SYSUSER-" . sprintf("%05s", $id);
    }
    public function idFormat_gcc($id)
    {
        return "GCC-" . sprintf("%07s", $id);
    }
    public function idFormat_delivery($id)
    {
        return "DLVRY-" . sprintf("%07s", $id);
    }
    public function idFormat_supplier_payment($id)
    {
        return "SUPPAY-" . sprintf("%07s", $id);
    }
    public function idFormat_customer_payment($id)
    {
        return "CLPAY-" . sprintf("%07s", $id);
    }
    public function idFormat_debitnote($id)
    {
        return "DN-" . sprintf("%07s", $id);
    }
    public function idFormat_creditnote($id)
    {
        return "CN-" . sprintf("%07s", $id);
    }
    public function idFormat_intercall_balance($id)
    {
        return "ICBAL-" . sprintf("%07s", $id);
    }
    public function idFormat_supplier($id)
    {
        return "SUP-" . sprintf("%05s", $id);
    }
    public function idFormat_discount($id)
    {
        return "DIS-" . sprintf("%05s", $id);
    }
    public function idFormat_customer($id)
    {
        return "CL-" . sprintf("%07s", $id);
    }
    public function log_format($id)
    {
        return "LOG-" . sprintf("%08s", $id);
    }
    public function idFormat_invoice($id)
    {
        return "INV-" . sprintf("%07s", $id);
    }
    public function idFormat_invoice_print($id)
    {
        return "" . sprintf("%07s", $id);
    }
    public function idFormat_item($id)
    {
        return "IT-" . sprintf("%06s", $id);
    }
    public function idFormat_category($id)
    {
        return "SUBCAT-" . sprintf("%05s", $id);
    }
    public function idFormat_parentcategory($id)
    {
        return "CAT-" . sprintf("%05s", $id);
    }
    public function idFormat_quotation($id)
    {
        return "Q-" . sprintf("%07s", $id);
    }
    public function idFormat_offer_package($id)
    {
        return "C-" . sprintf("%07s", $id);
    }
    public function idFormat_warehouse($id)
    {
        return "WH-" . sprintf("%05s", $id);
    }
    public function idFormat_expenses($id)
    {
        return "EXP-" . sprintf("%05s", $id);
    }
    public function idFormat_stockInv($id)
    {
        return "PINV-" . sprintf("%07s", $id);
    }
    public function idFormat_employee($id)
    {
        return "EMP-" . sprintf("%04s", $id);
    }
    public function idFormat_INVIT($id)
    {
        return "INVIT-" . sprintf("%07s", $id);
    }
    public function idFormat_mobilePkg($id)
    {
        return "MBPKG-" . sprintf("%04s", $id);
    }
    public function idFormat_mobileDEV($id)
    {
        return "MBDEV-" . sprintf("%04s", $id);
    }
    public function idFormat_shrinkage($id)
    {
        return "SHAGE-" . sprintf("%07s", $id);
    }
    public function idFormat_restaurantTables($id)
    {
        return "RSTB-" . sprintf("%03s", $id);
    }
    public function idFormat_transfers($id)
    {
        return "TRANS-" . sprintf("%07s", $id);
    }
    public function turnstile_verified($cf_turnstile_response)
    {
        $data = array("secret" => "0x4AAAAAAAaEDy4gVsrejlJWi_kCDeEag6k", "response" => $cf_turnstile_response);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://challenges.cloudflare.com/turnstile/v0/siteverify");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $response_json = json_decode($response, true);
        return $response_json["success"];
    }
    public function get_total_invoice_details($invoice_info)
    {
        $return_info = array();
        $return_info["tax"] = floatval($invoice_info["tax"]) . " %";
        $return_info["freight"] = floatval($invoice_info["freight"]);
        $base_v = $invoice_info["total_value"] + $invoice_info["invoice_discount"];
        $return_info["total"] = $base_v + $base_v * $return_info["tax"] / 100 + $return_info["freight"];
        return $return_info;
    }
    public function mysql_invoice_calc_string()
    {
    }
    public function is_connected()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            $is_conn = true;
            fclose($connected);
        } else {
            $is_conn = false;
        }
        return $is_conn;
    }
    public function only_round_lbp($value)
    {
        $r = round($value, 0) % 1000;
        $t = round($value, 0);
        if (0 < $r) {
            if (500 <= $r) {
                $t = floor($value / 1000) * 1000 + 1000;
            } else {
                $t = floor($value / 1000) * 1000;
            }
        }
        return $t;
    }
    public function encrypt($data)
    {
        $key = "Upsilon1983#@123";
        $iv = "e4b51f0c938e869d9aa92d02e293aee1";
        $encrypted = openssl_encrypt($data, "AES-256-CBC", $key, 0, $iv);
        return base64_encode($encrypted);
    }
    public function decrypt($data)
    {
        $key = "Upsilon1983#@123";
        $iv = "e4b51f0c938e869d9aa92d02e293aee1";
        $decoded = base64_decode($data);
        return openssl_decrypt($decoded, "AES-256-CBC", $key, 0, $iv);
    }
    public function update_online_customers($settings)
    {
        $customers = $this->model("customers");
        $info = $customers->getNotSynced();
        if (0 < count($info) && self::is_connected()) {
            for ($i = 0; $i < count($info); $i++) {
                $url = $settings["archive"];
                $tmp = array();
                array_push($tmp, array("nm" => $info[$i]["name"] . " " . $info[$i]["middle_name"] . " " . $info[$i]["last_name"]));
                array_push($tmp, array("ph" => $info[$i]["phone"]));
                array_push($tmp, array("ct" => $info[$i]["customer_type"]));
                array_push($tmp, array("sn" => $settings["shop_name"]));
                $param = self::encrypt(json_encode($tmp));
                $myvars = "txt=" . urlencode($param);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                $response = curl_exec($ch);
                if ($response == "") {
                    $customers->setSynced($info[$i]["id"]);
                }
            }
        }
    }
    public function get_customer_statement_data($customer_id)
    {
        self::giveAccessTo(array(2));
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
        $payments = $this->model("payments");
        $credit_note = $this->model("creditnote");
        $credit_notes = $credit_note->get_credit_note_for_customers($customer_id);
        $customers_all_invoices = $invoice->getInvoicesOfCustomers($customer_id);
        $customers_all_payments = $payments->getAllDebtsPaymentOfCustomer($customer_id);
        $customer_info = $customers->getCustomersById($customer_id);
        $stm = array();
        $array_index = 0;
        $stm[$array_index] = array("timestamp" => 0, "creation_date" => "Starting balance", "invoice_id" => "", "payment_note" => "", "total_invoice_value" => $customer_info[0]["starting_balance"], "total_payment_value" => 0, "credit" => 1, "deleted" => 0, "ref_payment" => "", "store_id" => NULL, "closed" => 1, "auto_closed" => 0, "payment_method" => 0, "paid_directly" => -1, "credit_note" => 0, "invoice_nb_official" => 1);
        $array_index++;
        for ($i = 0; $i < count($customers_all_invoices); $i++) {
            if (is_null($customers_all_invoices[$i]["store_id"])) {
                $customers_all_invoices_store_id = NULL;
            } else {
                $customers_all_invoices_store_id = $customers_all_invoices[$i]["store_id"];
            }
            $credit = 1;
            $invoice_paid_directly = 0;
            if ($customers_all_invoices[$i]["closed"] == 1 && $customers_all_invoices[$i]["auto_closed"] == 0) {
                $credit = 0;
                $invoice_paid_directly = 1;
            }
            $tt = self::get_total_invoice_details($customers_all_invoices[$i]);
            $stm[$array_index] = array("timestamp" => strtotime($customers_all_invoices[$i]["creation_date"]), "creation_date" => $customers_all_invoices[$i]["creation_date"], "invoice_id" => $customers_all_invoices[$i]["id"], "payment_note" => $customers_all_invoices[$i]["payment_note"], "total_invoice_value" => $tt["total"], "total_payment_value" => "", "credit" => $credit, "deleted" => 0, "ref_payment" => "", "store_id" => $customers_all_invoices_store_id, "closed" => $customers_all_invoices[$i]["closed"], "auto_closed" => $customers_all_invoices[$i]["auto_closed"], "payment_method" => 0, "paid_directly" => $invoice_paid_directly, "credit_note" => 0, "invoice_nb_official" => $customers_all_invoices[$i]["invoice_nb_official"]);
            $array_index++;
        }
        for ($i = 0; $i < count($customers_all_payments); $i++) {
            if (is_null($customers_all_payments[$i]["store_id"])) {
                $customers_all_payments_store_id = NULL;
            } else {
                $customers_all_payments_store_id = $customers_all_payments[$i]["store_id"];
            }
            $deleted = $customers_all_payments[$i]["deleted"];
            $stm[$array_index] = array("timestamp" => strtotime($customers_all_payments[$i]["balance_date"]), "creation_date" => $customers_all_payments[$i]["balance_date"], "invoice_id" => "", "payment_note" => $customers_all_payments[$i]["note"], "total_invoice_value" => "", "total_payment_value" => $customers_all_payments[$i]["balance"] * $customers_all_payments[$i]["rate"], "credit" => 0, "deleted" => $deleted, "ref_payment" => $customers_all_payments[$i]["id"], "store_id" => $customers_all_payments_store_id, "auto_closed" => "", "auto_closed" => "", "payment_method" => $customers_all_payments[$i]["payment_method"], "paid_directly" => -1, "credit_note" => 0, "invoice_nb_official" => 1);
            $array_index++;
        }
        for ($i = 0; $i < count($credit_notes); $i++) {
            $stm[$array_index] = array("timestamp" => strtotime($credit_notes[$i]["creation_date"]), "creation_date" => $credit_notes[$i]["creation_date"], "invoice_id" => "", "payment_note" => "-", "total_invoice_value" => "", "total_payment_value" => $credit_notes[$i]["credit_value"] * $credit_notes[$i]["currency_rate"], "credit" => 0, "deleted" => $credit_notes[$i]["deleted"], "ref_payment" => $credit_notes[$i]["id"], "store_id" => $credit_notes[$i]["store_id"], "auto_closed" => "", "payment_method" => $credit_notes[$i]["credit_payment_method"], "paid_directly" => -1, "credit_note" => 1);
            $array_index++;
        }
        self::__USORT_TIMESTAMP($stm);
        return $stm;
    }
    public function is_localhost()
    {
        if ($_SERVER["HTTP_HOST"] == "localhost" || $_SERVER["SERVER_NAME"] == "localhost") {
            return true;
        }
        return false;
    }
    public function mask($str, $start = 0, $length = NULL)
    {
        $mask = preg_replace("/\\S/", "*", $str);
        if (is_null($length)) {
            $mask = substr($mask, $start);
            $str = substr_replace($str, $mask, $start);
        } else {
            $mask = substr($mask, $start, $length);
            $str = substr_replace($str, $mask, $start, $length);
        }
        return $str;
    }
    public function uploade_picture($file_name, $tmp_name, $new_file_name, $dir)
    {
        $target_dir = "data/";
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extension = explode(".", $file_name);
        $check = getimagesize($tmp_name);
        if ($check !== false) {
            $uploadOk = 1;
            if (!in_array($imageFileType, array("png", "jpg", "jpeg"))) {
                $uploadOk = 12;
            } else {
                if (file_exists($target_dir . $dir . $new_file_name . "." . $extension[1])) {
                    rename($target_dir . $dir . $new_file_name . "." . $extension[1], $target_dir . $dir . "trash/" . $new_file_name . "_" . time() . "." . $extension[1]);
                }
                if ($uploadOk == 1) {
                    if (move_uploaded_file($tmp_name, $target_dir . $dir . $new_file_name . "." . $extension[1])) {
                        return $target_dir . $dir . $new_file_name . "." . $extension[1];
                    }
                    return NULL;
                }
            }
        } else {
            $uploadOk = 10;
        }
    }
    public function getCurrencies()
    {
        $currency = self::model("currency");
        $currency_data = $currency->getAllCurrencies();
        return $currency_data;
    }
    public function getSettings()
    {
        $settings = self::model("settings");
        $settings_data = $settings->get_settings();
        $settings_info = array();
        for ($i = 0; $i < count($settings_data); $i++) {
            $settings_info["" . $settings_data[$i]["name"]] = $settings_data[$i]["value"];
        }
        if (isset($_SESSION["ptype"]) && $_SESSION["ptype"] == 1) {
            $settings_info["enable_wholasale"] = 0;
            $settings_info["payment_credit_card"] = 0;
            $settings_info["payment_cheque"] = 0;
        }
        return $settings_info;
    }
    public function get_settings_local()
    {
        $settings = self::model("settings");
        $settings_data = $settings->get_settings_local();
        $settings_info = array();
        for ($i = 0; $i < count($settings_data); $i++) {
            $settings_info["" . $settings_data[$i]["name"]] = $settings_data[$i]["value"];
        }
        return $settings_info;
    }
    public function check_older($settings_info)
    {
        $days = 1;
        $dir = $settings_info["backup_path"] . "/enc/";
        $files = glob($dir . "*.zip");
        $files_backups = array();
        for ($i = 0; $i < count($files); $i++) {
            $files_backups[filemtime($files[$i])] = $files[$i];
        }
        krsort($files_backups);
        $index = 0;
        foreach ($files_backups as $key => $value) {
            $index++;
            if ($settings_info["leave_encoded_backups_nb"] < $index) {
                unlink($value);
            }
        }
    }
    public function check_older_txt($settings_info)
    {
        $days = 1;
        $dir = $settings_info["backup_path"] . "/enc/";
        $files = glob($dir . "*.txt");
        $files_backups = array();
        for ($i = 0; $i < count($files); $i++) {
            $files_backups[filemtime($files[$i])] = $files[$i];
        }
        krsort($files_backups);
        $index = 0;
        foreach ($files_backups as $key => $value) {
            $index++;
            if ($settings_info["leave_encoded_backups_nb"] < $index) {
                unlink($value);
            }
        }
    }
    public function bkp($settings_info)
    {
        if (self::is_on_server()) {
            echo json_encode(array("status" => 1));
            exit;
        }
        $tt = time();
        $filename = $settings_info["shop_name"] . "_" . $settings_info["customer_id"] . "_" . $tt . ".sql";
        $filename_enc = $settings_info["shop_name"] . "_" . $settings_info["customer_id"] . "_" . $tt . "_enc.sql.zip";
        $filename_zip = $filename . ".zip";
        $cmd = $settings_info["dump_path"] . " --databases " . $settings_info["database_name"] . " --result-file=" . $settings_info["backup_path"] . "\\db_\"" . $filename . "\" --user=" . USERNAME . " --password=" . PASSWORD;
        if (!file_exists($settings_info["backup_path"])) {
            mkdir($settings_info["backup_path"], 511, true);
        }
        if (!file_exists($settings_info["backup_path"] . "/enc")) {
            mkdir($settings_info["backup_path"] . "/enc", 511, true);
        }
        $result = exec($cmd, $output);
        $main_root = self::get_main_root();
        if ($settings_info["encode_and_online_backup"] == 1) {
            $result_zip = exec($main_root . "/OpenSSL-Win32/bin/zip.exe " . $settings_info["backup_path"] . "\\db_\"" . $filename_zip . "\" " . $settings_info["backup_path"] . "\\db_\"" . $filename . "\"", $output_zip);
            $result_enc = exec($main_root . "/OpenSSL-Win32/bin/openssl.exe smime -encrypt -binary -text -aes256 -in " . $settings_info["backup_path"] . "\\db_\"" . $filename_zip . "\" -out " . $settings_info["backup_path"] . "/enc\\db_\"" . $filename_enc . "\" -outform DER " . $main_root . "/OpenSSL-Win32/bin/mysqldump-secure.pub.pem", $output_enc);
            self::check_older($settings_info);
        }
        if ($output == "") {
            echo json_encode(array("status" => 0));
        } else {
            echo json_encode(array("status" => 1));
        }
    }
    public function get_main_root()
    {
        return $_SERVER["DOCUMENT_ROOT"] . dirname($_SERVER["PHP_SELF"]);
    }
    public function giveAccessTo($employe_level = array())
    {
        if ($_SESSION["role"] == 1 || $_SESSION["role"] == 3) {
            return NULL;
        }
        if (!in_array($_SESSION["role"], $employe_level)) {
            exit;
        }
    }
    public function genRandomString($length = 5)
    {
        $characters = "0123456789";
        $string = "";
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }
    public function generate_upsilon_id()
    {
        return self::genRandomString() . uniqid();
    }
    public function is_on_server()
    {
        $whitelist = array("127.0.0.1", "::1");
        if (defined("PHP_MAJOR_VERSION") && 6 <= PHP_MAJOR_VERSION) {
            return true;
        }
        if (in_array($_SERVER["REMOTE_ADDR"], $whitelist)) {
            return false;
        }
        return true;
    }
    public function findmacaddress($ifaces = array("eth0", "en0", "eth1", "en1"))
    {
        if (stripos(PHP_OS, "WIN") === 0) {
            $output = shell_exec("ipconfig /all");
            if (preg_match("/Physical[^:]+:(.*)/i", $output, $m) && isset($m[1])) {
                return str_replace("-", ":", trim($m[1]));
            }
        } else {
            foreach ($ifaces as $iface) {
                if (self::is_on_server()) {
                    return NULL;
                }
                $output = shell_exec("/sbin/ifconfig " . $iface . " 2>&1");
                if (preg_match("/([0-9A-F]{2}[:-]){5}([0-9A-F]{2})/", strtoupper($output), $m) && isset($m[0])) {
                    return trim($m[0]);
                }
            }
        }
    }
    public function getClientIp()
    {
        $ipAddress = "";
        if (isset($_SERVER["HTTP_CLIENT_IP"]) && !empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ipAddress = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                if (isset($_SERVER["HTTP_X_FORWARDED"]) && !empty($_SERVER["HTTP_X_FORWARDED"])) {
                    $ipAddress = $_SERVER["HTTP_X_FORWARDED"];
                } else {
                    if (isset($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"]) && !empty($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) {
                        $ipAddress = $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
                    } else {
                        if (isset($_SERVER["HTTP_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_FORWARDED_FOR"])) {
                            $ipAddress = $_SERVER["HTTP_FORWARDED_FOR"];
                        } else {
                            if (isset($_SERVER["HTTP_FORWARDED"]) && !empty($_SERVER["HTTP_FORWARDED"])) {
                                $ipAddress = $_SERVER["HTTP_FORWARDED"];
                            } else {
                                if (isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"])) {
                                    $ipAddress = $_SERVER["REMOTE_ADDR"];
                                }
                            }
                        }
                    }
                }
            }
        }
        if (strpos($ipAddress, ",") !== false) {
            $ipAddress_ = explode(",", $ipAddress);
            $ipAddress = $ipAddress_[0];
        }
        return $ipAddress;
    }
    public function GetProcessor()
    {
        if (self::is_on_server()) {
            return NULL;
        }
        $global_settings = self::getSettings();
        $pID = shell_exec("wmic cpu get ProcessorId");
        return preg_replace("/\\s+/", "", str_replace("ProcessorId", "", $pID)) . "-" . self::GetVolumeLabel($global_settings["main_drive"]);
    }
    public function GetVolumeLabel($drive)
    {
        if (self::is_on_server()) {
            return NULL;
        }
        if (preg_match("#Volume Serial Number is (.*)\\n#i", shell_exec("dir " . $drive . ":"), $m)) {
            $volname = "(" . $m[1] . ")";
        } else {
            $volname = "";
        }
        return str_replace("(", "", str_replace(")", "", $volname));
    }
    public function createDiscountLogs($msg)
    {
        if (QUERY_GENERAL_LOGS_DISCOUNT && !file_exists(QUERY_GENERAL_LOGS_DISCOUNT_PATH)) {
            mkdir(QUERY_GENERAL_LOGS_DISCOUNT_PATH, 511, true);
        }
        if (QUERY_GENERAL_LOGS_DISCOUNT == true) {
            $file = QUERY_GENERAL_LOGS_DISCOUNT_PATH . "/log-" . date("Y-m-d") . ".txt";
            file_put_contents($file, date("h:i:s A") . ": " . $msg . " \n", FILE_APPEND | LOCK_EX);
        }
    }
    public function get_sum($_value)
    {
        $sum = 0;
        $value = str_split($_value);
        for ($i = 0; $i < count($value); $i++) {
            $sum += $value[$i];
        }
        return $sum;
    }
    public function generateSmallKey($length = 8)
    {
        $characters = "upsilon123456789";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function generateRandomStringComplex_alway_dynamic($length = 20)
    {
        $characters = "abcdefghijklKLMNOPQRSTUVWXYZ0123456789";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function generateRandomStringComplex($length = 20)
    {
        return VERSION;
    }
    public function generateRandomStringComplex_key($length = 20)
    {
        $characters = "abcdefghijklKLMNOPQRSTUVWXYZ0123456789";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function generateRandomString($length = 10)
    {
        $characters = "abcdefghijklKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function encrypt_serialnumber()
    {
        $time_stamp = "1514811600";
        $reversed_time_stamp = strrev($time_stamp);
        $reversed_time_stamp_array = str_split($reversed_time_stamp);
        $final = "";
        for ($i = 0; $i < count($reversed_time_stamp_array); $i++) {
            $final .= $reversed_time_stamp_array[$i] . self::generateRandomString(rand(2, 3));
        }
        $final .= "_" . array_sum($reversed_time_stamp_array);
        echo $final;
    }
    public function decrypt_serialnumber($serial)
    {
        $time_stamp_decrypted_split = explode("_", $serial);
        $numerics_tmp = NULL;
        for ($i = 0; $i < strlen($time_stamp_decrypted_split[0]); $i++) {
            if (is_numeric($time_stamp_decrypted_split[0][$i])) {
                $numerics_tmp .= $time_stamp_decrypted_split[0][$i];
            }
        }
        $numerics = strrev($numerics_tmp);
        return $numerics;
    }
    public function mc_encrypt($encrypt_, $key_)
    {
        if (self::is_on_server()) {
            return NULL;
        }
        $encrypt = serialize($encrypt_);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack("H*", $key_);
        $mac = hash_hmac("sha256", $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt . $mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt) . "|" . base64_encode($iv);
        return $encoded;
    }
    public function mc_decrypt($decrypt_, $key_)
    {
        if (self::is_on_server()) {
            return NULL;
        }
        $decrypt = explode("|", $decrypt_ . "|");
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if (strlen($iv) !== mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
            return false;
        }
        $key = pack("H*", $key_);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac("sha256", $decrypted, substr(bin2hex($key), -32));
        if ($calcmac !== $mac) {
            return false;
        }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }
    public function autoCloseInvoices($customer_id)
    {
        $invoices = $this->model("invoice");
        $customers = $this->model("customers");
        $payments = $this->model("payments");
        $UnpaidInvoicesOfCustomers = $invoices->getUnpaidInvoicesOfCustomers($customer_id);
        $g = 0;
        if ($g < count($UnpaidInvoicesOfCustomers)) {
            $total_balance = $customers->get_total_balance($customer_id);
            if ($customer_id == 67) {
            }
            if ($UnpaidInvoicesOfCustomers[$g]["total_value"] + $UnpaidInvoicesOfCustomers[$g]["invoice_discount"] <= $total_balance) {
                $info_pay = array();
                $info_pay["invoice_id"] = $UnpaidInvoicesOfCustomers[$g]["id"];
                $info_pay["value"] = $UnpaidInvoicesOfCustomers[$g]["total_value"] + $UnpaidInvoicesOfCustomers[$g]["invoice_discount"];
                $info_pay["store_id"] = $_SESSION["store_id"];
                $info_pay["vendor_id"] = $_SESSION["id"];
                $payments->add_payment($info_pay);
                $info_reduce = array();
                $info_reduce["customer_id"] = $customer_id;
                $info_reduce["value"] = $UnpaidInvoicesOfCustomers[$g]["total_value"] + $UnpaidInvoicesOfCustomers[$g]["invoice_discount"];
                $payments->reduce_payment_to_customer($info_reduce);
                $invoices->closeInvoice($UnpaidInvoicesOfCustomers[$g]["id"]);
                $invoices->setAutoClosedInvoice($UnpaidInvoicesOfCustomers[$g]["id"]);
            }
            //break;
        }
    }
    public function getTimeStamp($ts)
    {
        return intval(self::decrypt_serialnumber($ts));
    }
    public function date_format_custom($date)
    {
        return date("D j M Y", strtotime($date));
    }
    public function date_format($date)
    {
        return date("Y-m-d", strtotime($date));
    }
    public function date_time_format_custom($date)
    {
        return date("l jS \\of F Y H:i:s", strtotime($date));
    }
    public function formar_nb_if_float($amount, $decimal = 2)
    {
        $dcnb = strlen(substr(strrchr($amount, "."), 1));
        return number_format($amount, $dcnb);
    }
    public function highlightDecimal($number, $color, $size, $is_bold)
    {
        $numberStr = (string) $number;
        if (strpos($numberStr, ".") !== false) {
            list($integerPart, $decimalPart) = explode(".", $numberStr);
            if ($is_bold == 1) {
                $highlightedDecimal = "<b style=\"font-size:" . $size . "px;\">." . $decimalPart . "</b>";
            } else {
                $highlightedDecimal = "<span style=\"font-size:" . $size . "px;\">." . $decimalPart . "</span>";
            }
            $result = $integerPart . $highlightedDecimal;
        } else {
            $result = $numberStr;
        }
        return $result;
    }
    public function global_number_formatter($amount, $settings_info)
    {
        $v = rtrim(rtrim(number_format($amount, $settings_info["number_of_decimal_points"]), "0"), ".");
        $color = "#f36c54";
        $size = 12;
        if (isset($settings_info["decimal_part_size"])) {
            $size = $settings_info["decimal_part_size"];
        }
        $is_bold = 0;
        if (isset($settings_info["decimal_part_is_bold"])) {
            $is_bold = $settings_info["decimal_part_is_bold"];
        }
        return self::highlightDecimal($v, $color, $size, $is_bold);
    }
    public function value_format_custom_no_currency($value, $settings_info)
    {
        return self::formar_nb_if_float(round($value, $settings_info["round_val"]), $settings_info["number_of_decimal_points"]);
    }
    public function value_format_custom_no_currency_no_round($value, $settings_info)
    {
        return self::formar_nb_if_float($value, 0);
    }
    public function value_format_custom_round_decimal($value, $settings_info, $currencies, $currency_id)
    {
        if ($settings_info["show_currency_in_report"] == 0) {
            return self::formar_nb_if_float(round($value, $currencies[$currency_id]["sales_invoice_round"]), $currencies[$currency_id]["sales_invoice_decimal"]);
        }
        return self::formar_nb_if_float(round($value, $currencies[$currency_id]["sales_invoice_round"]), $currencies[$currency_id]["sales_invoice_decimal"]) . " " . $settings_info["default_currency_symbol"];
    }
    public function value_format_custom($value, $settings_info)
    {
        if ($value == "" || $value == NULL) {
            $value = 0;
        }
        if ($settings_info["show_currency_in_report"] == "0") {
            return self::formar_nb_if_float(round($value, $settings_info["round_val"]), $settings_info["number_of_decimal_points"]);
        }
        return self::formar_nb_if_float(round($value, $settings_info["round_val"]), $settings_info["number_of_decimal_points"]) . " " . $settings_info["default_currency_symbol"];
    }
    public function generate_key()
    {
        return self::GetProcessor();
    }
    public function conversion_php_version_filter()
    {
        if (defined("PHP_MAJOR_VERSION") && 6 <= PHP_MAJOR_VERSION) {
            return FILTER_SANITIZE_ADD_SLASHES;
        }
        return FILTER_SANITIZE_MAGIC_QUOTES;
    }
    public function conversion_php_version_encrypt()
    {
        if (defined("PHP_MAJOR_VERSION") && 6 <= PHP_MAJOR_VERSION) {
            return FILTER_SANITIZE_ADD_SLASHES;
        }
        return FILTER_SANITIZE_MAGIC_QUOTES;
    }
    public function licenseExpired()
    {
        if (self::is_on_server()) {
            return false;
        }
        $global_settings = self::getSettings();
        $code = self::mc_decrypt($global_settings["activation_code"], ENCRYPTION_KEY_1);
        $code_exploded = explode("_", $code);
        if (count($code_exploded) <= 1) {
            $code_exploded = array();
            $code_exploded[0] = "123456";
            $code_exploded[1] = "123456";
        }
        if ($code_exploded[1] < time()) {
            return "#001";
        }
        if (!COMPUTER_IS_CLIENT && $global_settings["enable_phd"] != "C4CA4238A0B923820DCC509A6F75849B" && $code_exploded[0] != self::GetProcessor()) {
            return "#002";
        }
        if (date("Y") < 2019) {
            return "#003";
        }
        return false;
    }
    public function critical_data()
    {
        return "******";
    }
    public function print_barcode_($_item_id, $nb = 1)
    {
        $main_root = self::get_main_root();
        $items = $this->model("items");
        $settings = $this->model("settings");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($item_id);
        $barcode_settings = $settings->get_barcode_settings($this->settings_info["barcode_paper_id"]);
        $settings_info_local = self::get_settings_local();
        $barcode_key[0]["mid"] = $item_info[0]["barcode"];
        if (file_exists($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg")) {
            unlink($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg");
        }
        if (strlen($barcode_key[0]["mid"]) < 4) {
            $barcode_key[0]["mid"] = sprintf("%04d", $barcode_key[0]["mid"]);
        }
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
        include_once "application/mvc/models/BarcodeGenerator.php";
        $generator = new barcodeGenerator();
        $options = array();
        if ($barcode_settings_info["h"] != -1) {
            $options["h"] = $barcode_settings_info["h"];
        }
        if ($barcode_settings_info["w"] != -1) {
            $options["w"] = $barcode_settings_info["w"];
        }
        if ($barcode_settings_info["wm"] != -1) {
            $options["wm"] = $barcode_settings_info["wm"];
        }
        if ($barcode_settings_info["ww"] != -1) {
            $options["ww"] = $barcode_settings_info["ww"];
        }
        if ($barcode_settings_info["wq"] != -1) {
            $options["wq"] = $barcode_settings_info["wq"];
        }
        if ($barcode_settings_info["wn"] != -1) {
            $options["wn"] = $barcode_settings_info["wn"];
        }
        if ($barcode_settings_info["th"] != -1) {
            $options["th"] = $barcode_settings_info["th"];
        }
        if ($barcode_settings_info["ts"] != -1) {
            $options["ts"] = $barcode_settings_info["ts"];
        }
        if ($barcode_settings_info["pt"] != -1) {
            $options["pt"] = $barcode_settings_info["pt"];
        }
        if ($barcode_settings_info["pb"] != -1) {
            $options["pb"] = $barcode_settings_info["pb"];
        }
        if ($barcode_settings_info["pl"] != -1) {
            $options["pl"] = $barcode_settings_info["pl"];
        }
        if ($barcode_settings_info["pr"] != -1) {
            $options["pr"] = $barcode_settings_info["pr"];
        }
        if ($barcode_settings_info["p"] != -1) {
            $options["p"] = $barcode_settings_info["p"];
        }
        $image_ = $generator->output_image("jpg", $barcode_settings_info["type"], $barcode_key[0]["mid"], $options);
        $im = imagecreatefromjpeg($main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg");
        Bmp::imagebmp($im, $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".bmp");
        if (strlen($barcode_key[0]["mid"]) < 4) {
            $barcode_key[0]["mid"] = sprintf("%04d", $barcode_key[0]["mid"]);
        }
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
        $handle = printer_open($settings_info_local["printer_barcode_name"]);
        printer_set_option($handle, PRINTER_MODE, "raw");
        printer_set_option($handle, PRINTER_COPIES, 2);
        printer_start_doc($handle, "My Document");
        printer_start_page($handle);
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 30, "000000");
        printer_select_pen($handle, $pen);
        $font_store_name = printer_create_font("Bebas", $barcode_settings_info["store_name_font_size"], 0, PRINTER_FW_NORMAL, false, false, false, 0);
        printer_select_font($handle, $font_store_name);
        printer_set_option($handle, PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM);
        printer_set_option($handle, PRINTER_PAPER_LENGTH, 30);
        printer_set_option($handle, PRINTER_PAPER_WIDTH, 58);
        printer_draw_text($handle, $this->settings_info["shop_name"], $barcode_settings_info["store_name_x"], $barcode_settings_info["store_name_y"]);
        printer_delete_font($font_store_name);
        printer_delete_pen($pen);
        if ($barcode_settings_info["description_enable"]) {
            $pen = printer_create_pen(PRINTER_PEN_SOLID, 30, "000000");
            printer_select_pen($handle, $pen);
            $font_description = printer_create_font("Oswald", $barcode_settings_info["description_size"], 0, PRINTER_FW_NORMAL, false, false, false, 0);
            printer_select_font($handle, $font_description);
            $it_desc = $item_info[0]["description"];
            if (0 < strlen($item_info[0]["item_alias"]) && $item_info[0]["item_alias"] != "null") {
                $it_desc = $item_info[0]["item_alias"];
            }
            if ($barcode_settings_info["description_max_size"] < strlen($it_desc)) {
                $it_desc = substr($it_desc, 0, $barcode_settings_info["description_max_size"]) . " ...";
            }
            printer_draw_text($handle, $it_desc, $barcode_settings_info["description_x"], $barcode_settings_info["description_y"]);
            printer_delete_font($font_description);
            printer_delete_pen($pen);
        }
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 30, "000000");
        printer_select_pen($handle, $pen);
        $before = "";
        $strikeout = false;
        if (0 < $item_info[0]["discount"]) {
            $before = "Original ";
            $strikeout = true;
        }
        $font_original_price = printer_create_font("Oswald", $barcode_settings_info["price_font_size"], 0, PRINTER_FW_NORMAL, false, false, $strikeout, 0);
        printer_select_font($handle, $font_original_price);
        if ($barcode_settings_info["price_enable"]) {
            printer_draw_text($handle, $before . "Price: " . number_format($item_info[0]["selling_price"], 0) . " " . $this->settings_info["default_currency_symbol"], $barcode_settings_info["price_x"], $barcode_settings_info["price_y"]);
        }
        printer_delete_font($font_original_price);
        printer_delete_pen($pen);
        if (0 < $item_info[0]["discount"]) {
            $pen = printer_create_pen(PRINTER_PEN_SOLID, 30, "000000");
            printer_select_pen($handle, $pen);
            $font_discount = printer_create_font("Oswald", $barcode_settings_info["price_font_size"], 0, PRINTER_FW_NORMAL, false, false, false, 0);
            printer_select_font($handle, $font_discount);
            if ($barcode_settings_info["discount_enable"] == 1) {
                printer_draw_text($handle, "Discount: " . round((double) $item_info[0]["discount"], 0) . " %", $barcode_settings_info["discount_x"], $barcode_settings_info["discount_y"]);
            }
            printer_delete_font($font_discount);
            printer_delete_pen($pen);
        }
        $after = "";
        if (0 < $item_info[0]["discount"]) {
            $pen = printer_create_pen(PRINTER_PEN_SOLID, 30, "000000");
            printer_select_pen($handle, $pen);
            $font = printer_create_font("Oswald", $barcode_settings_info["price_font_size"], 0, PRINTER_FW_NORMAL, false, false, false, 0);
            printer_select_font($handle, $font);
            $after = "Total ";
            printer_draw_text($handle, $after . "Price: " . number_format(round($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), 0), 0) . " " . $this->settings_info["default_currency_symbol"], $barcode_settings_info["price_after_discount_x"], $barcode_settings_info["price_after_discount_y"]);
            printer_delete_font($font);
            printer_delete_pen($pen);
        }
        printer_draw_bmp($handle, $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".bmp", $barcode_settings_info["barcode_position_x"], $barcode_settings_info["barcode_position_y"]);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
    }
    public function relative_date($time)
    {
        $today = strtotime(date("M j, Y"));
        $reldays = ($time - $today) / 86400;
        if (0 <= $reldays && $reldays < 1) {
            return "Today";
        }
        if (1 <= $reldays && $reldays < 2) {
            return "Tomorrow";
        }
        if (-1 <= $reldays && $reldays < 0) {
            return "Yesterday";
        }
        if (abs($reldays) < 7) {
            if (0 < $reldays) {
                $reldays = floor($reldays);
                return "In " . $reldays . " day" . ($reldays != 1 ? "s" : "");
            }
            $reldays = abs(floor($reldays));
            return $reldays . " day" . ($reldays != 1 ? "s" : "") . " ago";
        }
        if (abs($reldays) < 182) {
            return date("l, j F", $time ? $time : time());
        }
        return date("l, j F, Y", $time ? $time : time());
    }
    public function calculate_upc_check_digit($upc_code)
    {
        $checkDigit = -1;
        $upc = substr($upc_code, 0, 11);
        if (strlen($upc) == 11 && strlen($upc_code) <= 12) {
            $oddPositions = $upc[0] + $upc[2] + $upc[4] + $upc[6] + $upc[8] + $upc[10];
            $oddPositions *= 3;
            $evenPositions = $upc[1] + $upc[3] + $upc[5] + $upc[7] + $upc[9];
            $sumEvenOdd = $oddPositions + $evenPositions;
            $checkDigit = (10 - $sumEvenOdd % 10) % 10;
        }
        return $checkDigit;
    }
    public function UPCAbarcode($code)
    {
        $lw = 2;
        $hi = 100;
        $Lencode = array("0001101", "0011001", "0010011", "0111101", "0100011", "0110001", "0101111", "0111011", "0110111", "0001011");
        $Rencode = array("1110010", "1100110", "1101100", "1000010", "1011100", "1001110", "1010000", "1000100", "1001000", "1110100");
        $ends = "101";
        $center = "01010";
        if (strlen($code) != 11) {
            exit("UPC-A Must be 11 digits.");
        }
        $ncode = "0" . $code;
        $even = 0;
        $odd = 0;
        for ($x = 0; $x < 12; $x++) {
            if ($x % 2) {
                $odd += $ncode[$x];
            } else {
                $even += $ncode[$x];
            }
        }
        $code .= (10 - ($odd * 3 + $even) % 10) % 10;
        return $code;
    }
    public function generateEAN13($number)
    {
        $code = str_pad($number, 12, "0");
        $weightflag = true;
        $sum = 0;
        for ($i = strlen($code) - 1; 0 <= $i; $i--) {
            $sum += (int) $code[$i] * ($weightflag ? 3 : 1);
            $weightflag = !$weightflag;
        }
        $code .= (10 - $sum % 10) % 10;
        return $code;
    }
    public function print_barcode__($_item_id, $nb = 1)
    {
        $main_root = self::get_main_root();
        $items = $this->model("items");
        $settings = $this->model("settings");
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $item_info = $items->get_item($item_id);
        $barcode_settings = $settings->get_barcode_settings($this->settings_info["barcode_paper_id"]);
        $settings_info_local = self::get_settings_local();
        if (file_exists($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".bmp")) {
            unlink($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".bmp");
        }
        if (file_exists($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg")) {
            unlink($main_root . "/barcodes/" . $item_info[0]["barcode"] . ".jpg");
        }
        $barcode_key[0]["mid"] = $item_info[0]["barcode"];
        if (strlen($barcode_key[0]["mid"]) < 4) {
            $barcode_key[0]["mid"] = sprintf("%04d", $barcode_key[0]["mid"]);
        }
        $barcode_settings_info = array();
        for ($i = 0; $i < count($barcode_settings); $i++) {
            $barcode_settings_info[$barcode_settings[$i]["name"]] = $barcode_settings[$i]["value"];
        }
        include_once "application/mvc/models/BarcodeGenerator.php";
        $generator = new barcodeGenerator();
        $options = array();
        if ($barcode_settings_info["h"] != -1) {
            $options["h"] = $barcode_settings_info["h"];
        }
        if ($barcode_settings_info["w"] != -1) {
            $options["w"] = $barcode_settings_info["w"];
        }
        if ($barcode_settings_info["wm"] != -1) {
            $options["wm"] = $barcode_settings_info["wm"];
        }
        if ($barcode_settings_info["ww"] != -1) {
            $options["ww"] = $barcode_settings_info["ww"];
        }
        if ($barcode_settings_info["wq"] != -1) {
            $options["wq"] = $barcode_settings_info["wq"];
        }
        if ($barcode_settings_info["wn"] != -1) {
            $options["wn"] = $barcode_settings_info["wn"];
        }
        if ($barcode_settings_info["th"] != -1) {
            $options["th"] = $barcode_settings_info["th"];
        }
        if ($barcode_settings_info["ts"] != -1) {
            $options["ts"] = $barcode_settings_info["ts"];
        }
        if ($barcode_settings_info["pt"] != -1) {
            $options["pt"] = $barcode_settings_info["pt"];
        }
        if ($barcode_settings_info["pb"] != -1) {
            $options["pb"] = $barcode_settings_info["pb"];
        }
        if ($barcode_settings_info["pl"] != -1) {
            $options["pl"] = $barcode_settings_info["pl"];
        }
        if ($barcode_settings_info["pr"] != -1) {
            $options["pr"] = $barcode_settings_info["pr"];
        }
        if ($barcode_settings_info["p"] != -1) {
            $options["p"] = $barcode_settings_info["p"];
        }
        $image_ = $generator->output_image("jpg", $barcode_settings_info["type"], $barcode_key[0]["mid"], $options);
        $image = new Imagick();
        $image->setResolution($barcode_settings_info["barcode_dpi"], $barcode_settings_info["barcode_dpi"]);
        $image->newImage($barcode_settings_info["barcode_paper_width_in_pixels"], $barcode_settings_info["barcode_paper_height_in_pixels"], new ImagickPixel("white"));
        $image->setImageFormat("jpg");
        $image->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
        $draw = new ImagickDraw();
        $draw->setFillColor("black");
        $draw->setFontSize($barcode_settings_info["store_name_font_size"]);
        $draw->setFont("fonts/BEBAS___.ttf");
        $image->annotateImage($draw, $barcode_settings_info["store_name_x"], $barcode_settings_info["store_name_y"], 0, $this->settings_info["shop_name"]);
        if ($barcode_settings_info["description_enable"]) {
            $it_desc = $item_info[0]["description"];
            if (0 < strlen($item_info[0]["item_alias"]) && $item_info[0]["item_alias"] != "null") {
                $it_desc = $item_info[0]["item_alias"];
            }
            if ($barcode_settings_info["description_max_size"] < strlen($it_desc)) {
                $it_desc = substr($it_desc, 0, $barcode_settings_info["description_max_size"]) . " ...";
            }
            $draw->setFontSize($barcode_settings_info["description_size"]);
            $draw->setFont("fonts/Oswald-Light.ttf");
            list($lines, $lineHeight) = BMP::wordWrapAnnotation($image, $draw, $it_desc, $barcode_settings_info["description_max_width_on_paper"]);
            $i = 0;
            $image->annotateImage($draw, $barcode_settings_info["description_x"], $barcode_settings_info["description_y"] + $i * $lineHeight, 0, $lines[$i]);
            for ($i = 1; $i < count($lines); $i++) {
                $image->annotateImage($draw, $barcode_settings_info["description_x"], $barcode_settings_info["description_multiline_space"] + $i * $lineHeight, 0, $lines[$i]);
            }
        }
        $draw->setFont("fonts/Oswald-Light.ttf");
        $draw->setFontSize($barcode_settings_info["price_font_size"]);
        $before = "";
        if (0 < $item_info[0]["discount"]) {
            $draw->setTextDecoration(4);
            $before = "Original ";
        }
        $image->annotateImage($draw, $barcode_settings_info["price_x"], $barcode_settings_info["price_y"], 0, $before . "Price: " . number_format($item_info[0]["selling_price"], 0) . " " . $this->settings_info["default_currency_symbol"]);
        if (0 < $item_info[0]["discount"] && $barcode_settings_info["discount_enable"] == 1) {
            $draw->setTextDecoration(0);
            $draw->setFontSize($barcode_settings_info["discount_font_size"]);
            $image->annotateImage($draw, $barcode_settings_info["discount_x"], $barcode_settings_info["discount_y"], 0, "Discount: " . round((double) $item_info[0]["discount"], 0) . " %");
        }
        $after = "";
        if (0 < $item_info[0]["discount"]) {
            $after = "Total ";
            $draw->setTextDecoration(0);
            $image->annotateImage($draw, $barcode_settings_info["price_after_discount_x"], $barcode_settings_info["price_after_discount_y"], 0, $after . "Price: " . number_format(round($item_info[0]["selling_price"] * (1 - $item_info[0]["discount"] / 100), 0), 2) . " " . $this->settings_info["default_currency_symbol"]);
        }
        $image->writeImage($main_root . "/barcodes/tmp.jpg");
        $barc = new Imagick();
        $barc->readImage($main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg");
        $text = new Imagick();
        $text->readImage($main_root . "/barcodes/tmp.jpg");
        $x = $barcode_settings_info["barcode_position_x"];
        $y = $barcode_settings_info["barcode_position_y"];
        $text->compositeImage($barc, Imagick::COMPOSITE_OVER, $x, $y);
        $text->writeImage($main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg");
        $im = imagecreatefromjpeg($main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".jpg");
        Bmp::imagebmp($im, $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".bmp");
        for ($i = 0; $i < $nb; $i++) {
            $handle = printer_open($settings_info_local["printer_barcode_name"]);
            printer_set_option($handle, PRINTER_PAPER_FORMAT, $nb);
            printer_start_doc($handle, "Barcode");
            printer_start_page($handle);
            printer_draw_bmp($handle, $main_root . "/barcodes/" . $barcode_key[0]["mid"] . ".bmp", 1, 1);
            printer_end_page($handle);
            printer_end_doc($handle);
            printer_close($handle);
        }
        echo json_encode(array());
    }
    public function send_to_telegram($info, $tel_id)
    {
        $telegram = $this->model("telegram");
        $telegram->send_to_telegram($info, $tel_id);
    }
    public function get_all_balances()
    {
        $payments = $this->model("payments");
        $invoice = $this->model("invoice");
        $creditnote = $this->model("creditnote");
        $customers = $this->model("customers");
        $info = $customers->getCustomers();
        $invoices_info_group = $invoice->getTotalUnpaidGroup();
        $invoices_info_group_ = array();
        for ($i = 0; $i < count($invoices_info_group); $i++) {
            $invoices_info_group_[$invoices_info_group[$i]["customer_id"]] = $invoices_info_group[$i]["sum"];
        }
        $creditnote_sum_group = $creditnote->get_total_sum_creditnote_group();
        $creditnote_sum_group_ = array();
        for ($i = 0; $i < count($creditnote_sum_group); $i++) {
            $creditnote_sum_group_[$creditnote_sum_group[$i]["customer_id"]] = $creditnote_sum_group[$i]["sum"];
        }
        $total_payments_group = $payments->getTotalPaymentForCustomer_group();
        $total_payments_group_ = array();
        for ($i = 0; $i < count($total_payments_group); $i++) {
            $total_payments_group_[$total_payments_group[$i]["customer_id"]] = $total_payments_group[$i]["sum"];
        }
        $balance = array();
        for ($i = 0; $i < count($info); $i++) {
            $rm = $invoices_info_group_[$info[$i]["id"]] + $info[$i]["starting_balance"] - $total_payments_group_[$info[$i]["id"]] - $creditnote_sum_group_[$info[$i]["id"]];
            $balance[$info[$i]["id"]] = $rm;
        }
        return $balance;
    }
    public function _get_full_report_table($return_type, $cashbox_id)
    {
        $cashbox = $this->model("cashbox");
        $customers = $this->model("customers");
        $suppliers = $this->model("suppliers");
        $transactions_class = $this->model("transactions");
        if ($cashbox_id == 0) {
            $cashbox_id = $_SESSION["cashbox_id"];
        }
        $cashbox_info = $cashbox->geCashboxById($cashbox_id);
        $data_array = array();
        $data_array["data"] = array();
        $return_info_limited = array();
        $return_info_limited["net_usd"] = 0;
        $return_info_limited["net_lbp"] = 0;
        $transactions_sales_invoices = $cashbox->get_sales_invoice_transacions_details($cashbox_id);
        $transactions_changes_invoices = $cashbox->get_changes_invoice_transacions_details($cashbox_id);
        $transactions_changes_invoices_other_cashbox = $cashbox->get_changes_invoice_transacions_details_other_cashbox($cashbox_id);
        $transactions_payments_clients = $cashbox->get_clients_payment_transacions_details($cashbox_id);
        $transactions_expenses = $cashbox->get_expenses_transacions_details($cashbox_id);
        $transactions_suppliers = $cashbox->get_suppliers_transacions_details($cashbox_id);
        $net_lbp = $cashbox_info[0]["cashbox_lbp"];
        $net_usd = $cashbox_info[0]["cash"];
        $return_info_limited["net_usd"] = $net_usd;
        $return_info_limited["net_lbp"] = $net_lbp;
        $tmp = array();
        array_push($tmp, $cashbox_info[0]["starting_cashbox_date"]);
        array_push($tmp, "<b>Cashbox is started</b>");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "<span class='cin'>" . number_format($cashbox_info[0]["cash"], 2) . "</span>");
        array_push($tmp, "<span class='cin'>" . number_format($cashbox_info[0]["cashbox_lbp"], 0) . "</span>");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "<b>" . number_format($net_usd, 2) . "</b>");
        array_push($tmp, "<b>" . number_format($net_lbp, 0) . "</b>");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($data_array["data"], $tmp);
        $customers_to_get = array();
        for ($i = 0; $i < count($transactions_sales_invoices); $i++) {
            if (0 < $transactions_sales_invoices[$i]["customer_id"] && !in_array($transactions_sales_invoices[$i]["customer_id"], $customers_to_get)) {
                array_push($customers_to_get, $transactions_sales_invoices[$i]["customer_id"]);
            }
        }
        $customers_array = array();
        if (0 < count($customers_to_get)) {
            $customers_result = $customers->getCustomersByIDSArray($customers_to_get);
            for ($i = 0; $i < count($customers_result); $i++) {
                $customers_array[$customers_result[$i]["id"]] = $customers_result[$i];
            }
        }
        $transactions = array();
        $indx = 0;
        for ($i = 0; $i < count($transactions_sales_invoices); $i++) {
            $cus_name = "";
            if (0 < $transactions_sales_invoices[$i]["customer_id"]) {
                $cus_name = "<br/><small class='cus_name'>" . $customers_array[$transactions_sales_invoices[$i]["customer_id"]]["name"] . "</small>";
            }
            $transactions[$indx] = array("creation_date" => $transactions_sales_invoices[$i]["creation_date"], "timestamp" => strtotime($transactions_sales_invoices[$i]["creation_date"]), "transaction_id" => $transactions_sales_invoices[$i]["id"], "employee_id" => $transactions_sales_invoices[$i]["employee_id"], "invoice_id" => $transactions_sales_invoices[$i]["invoice_id"] . $cus_name, "base_usd_amount" => $transactions_sales_invoices[$i]["base_usd_amount"], "rate" => $transactions_sales_invoices[$i]["rate"], "cash_usd" => $transactions_sales_invoices[$i]["cash_usd"], "cash_lbp" => $transactions_sales_invoices[$i]["cash_lbp"], "returned_cash_usd" => $transactions_sales_invoices[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_sales_invoices[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_sales_invoices[$i]["must_return_cash_usd"], "must_return_cash_lbp" => $transactions_sales_invoices[$i]["must_return_cash_lbp"], "closed" => $transactions_sales_invoices[$i]["closed"], "auto_closed" => $transactions_sales_invoices[$i]["auto_closed"], "changes" => 0, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 1, "payment_type" => 1, "is_external_transaction" => 0);
            $indx++;
        }
        $suppliers_to_get = array();
        for ($i = 0; $i < count($transactions_suppliers); $i++) {
            if (!in_array($transactions_suppliers[$i]["supplier_id"], $suppliers_to_get)) {
                array_push($suppliers_to_get, $transactions_suppliers[$i]["supplier_id"]);
            }
        }
        $suppliers_array = array();
        if (0 < count($suppliers_to_get)) {
            $suppliers_result = $suppliers->getSuppliersByArray($suppliers_to_get);
            for ($i = 0; $i < count($suppliers_result); $i++) {
                $suppliers_array[$suppliers_result[$i]["id"]] = $suppliers_result[$i];
            }
        }
        for ($i = 0; $i < count($transactions_suppliers); $i++) {
            $sup_name = "";
            if (0 < $transactions_suppliers[$i]["supplier_id"]) {
                $sup_name = "<br/><small class='cus_name'>" . $suppliers_array[$transactions_suppliers[$i]["supplier_id"]]["name"] . "</small>";
            }
            $transactions[$indx] = array("creation_date" => $transactions_suppliers[$i]["creation_date"], "timestamp" => strtotime($transactions_suppliers[$i]["creation_date"]), "employee_id" => 0, "transaction_id" => 0, "invoice_id" => $transactions_suppliers[$i]["id"] . $sup_name, "base_usd_amount" => $transactions_suppliers[$i]["payment_value"], "rate" => $transactions_suppliers[$i]["rate"], "cash_usd" => $transactions_suppliers[$i]["cash_in_usd"], "cash_lbp" => $transactions_suppliers[$i]["cash_in_lbp"], "returned_cash_usd" => $transactions_suppliers[$i]["returned_usd"], "returned_cash_lbp" => $transactions_suppliers[$i]["returned_lbp"], "must_return_cash_usd" => $transactions_suppliers[$i]["to_returned_usd"], "must_return_cash_lbp" => $transactions_suppliers[$i]["to_returned_lbp"], "closed" => 1, "auto_closed" => 0, "changes" => 0, "client_payment" => 0, "expenses" => 0, "supplier_p" => 1, "is_invoice" => 0, "payment_type" => 0, "is_external_transaction" => 0);
            $indx++;
        }
        for ($i = 0; $i < count($transactions_expenses); $i++) {
            $transactions[$indx] = array("creation_date" => $transactions_expenses[$i]["creation_date"], "timestamp" => strtotime($transactions_expenses[$i]["creation_date"]), "employee_id" => 0, "transaction_id" => 0, "invoice_id" => $transactions_expenses[$i]["id"], "base_usd_amount" => $transactions_expenses[$i]["value"], "rate" => $transactions_expenses[$i]["rate"], "cash_usd" => $transactions_expenses[$i]["cash_usd_in"], "cash_lbp" => $transactions_expenses[$i]["cash_lbp_in"], "returned_cash_usd" => $transactions_expenses[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_expenses[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_expenses[$i]["cash_usd_to_return"], "must_return_cash_lbp" => $transactions_expenses[$i]["cash_lbp_to_return"], "closed" => 1, "auto_closed" => 0, "changes" => 0, "client_payment" => 0, "expenses" => 1, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 0, "is_external_transaction" => 0);
            $indx++;
        }
        $customers_p_to_get = array();
        for ($i = 0; $i < count($transactions_payments_clients); $i++) {
            if (!in_array($transactions_payments_clients[$i]["customer_id"], $customers_p_to_get)) {
                array_push($customers_p_to_get, $transactions_payments_clients[$i]["customer_id"]);
            }
        }
        $customers_p_array = array();
        if (0 < count($customers_p_to_get)) {
            $cp_result = $customers->getCustomersByIDSArray($customers_p_to_get);
            for ($i = 0; $i < count($cp_result); $i++) {
                $customers_p_array[$cp_result[$i]["id"]] = $cp_result[$i];
            }
        }
        for ($i = 0; $i < count($transactions_payments_clients); $i++) {
            $cu_name = "";
            if (0 < $transactions_payments_clients[$i]["customer_id"]) {
                $cu_name = "<br/><small class='cus_name'>" . $customers_p_array[$transactions_payments_clients[$i]["customer_id"]]["name"] . "</small>";
            }
            $transactions[$indx] = array("creation_date" => $transactions_payments_clients[$i]["value_date"], "timestamp" => strtotime($transactions_payments_clients[$i]["value_date"]), "employee_id" => $transactions_payments_clients[$i]["vendor_id"], "transaction_id" => 0, "invoice_id" => $transactions_payments_clients[$i]["id"] . "" . $cu_name, "base_usd_amount" => $transactions_payments_clients[$i]["balance"], "rate" => $transactions_payments_clients[$i]["p_rate"], "cash_usd" => $transactions_payments_clients[$i]["cash_in_usd"], "cash_lbp" => $transactions_payments_clients[$i]["cash_in_lbp"], "returned_cash_usd" => $transactions_payments_clients[$i]["returned_usd"], "returned_cash_lbp" => $transactions_payments_clients[$i]["returned_lbp"], "must_return_cash_usd" => $transactions_payments_clients[$i]["to_returned_usd"], "must_return_cash_lbp" => $transactions_payments_clients[$i]["to_returned_lbp"], "closed" => 0, "auto_closed" => 0, "changes" => 0, "client_payment" => 1, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 0, "is_external_transaction" => 0);
            $indx++;
        }
        for ($i = 0; $i < count($transactions_changes_invoices); $i++) {
            $total_return = 0;
            if ($transactions_changes_invoices[$i]["added_value"] <= $transactions_changes_invoices[$i]["return_value"]) {
                $total_return = $transactions_changes_invoices[$i]["return_value"] - $transactions_changes_invoices[$i]["added_value"];
            } else {
                $total_return = $transactions_changes_invoices[$i]["added_value"] - $transactions_changes_invoices[$i]["return_value"];
            }
            $transactions[$indx] = array("creation_date" => $transactions_changes_invoices[$i]["change_date"], "timestamp" => strtotime($transactions_changes_invoices[$i]["change_date"]), "employee_id" => 0, "transaction_id" => $transactions_changes_invoices[$i]["id"], "invoice_id" => $transactions_changes_invoices[$i]["invoice_id"], "base_usd_amount" => $total_return, "rate" => $transactions_changes_invoices[$i]["rate"], "cash_usd" => $transactions_changes_invoices[$i]["cash_usd_in"], "cash_lbp" => $transactions_changes_invoices[$i]["cash_lbp_in"], "returned_cash_usd" => $transactions_changes_invoices[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_changes_invoices[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_changes_invoices[$i]["cash_usd_to_return"], "must_return_cash_lbp" => $transactions_changes_invoices[$i]["cash_lbp_to_return"], "closed" => -1, "auto_closed" => -1, "changes" => 1, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 2, "is_external_transaction" => 0);
            $indx++;
        }
        for ($i = 0; $i < count($transactions_changes_invoices_other_cashbox); $i++) {
            $total_return = 0;
            if ($transactions_changes_invoices_other_cashbox[$i]["added_value"] <= $transactions_changes_invoices_other_cashbox[$i]["return_value"]) {
                $total_return = $transactions_changes_invoices_other_cashbox[$i]["return_value"] - $transactions_changes_invoices_other_cashbox[$i]["added_value"];
            } else {
                $total_return = $transactions_changes_invoices_other_cashbox[$i]["added_value"] - $transactions_changes_invoices_other_cashbox[$i]["return_value"];
            }
            $transactions[$indx] = array("creation_date" => $transactions_changes_invoices_other_cashbox[$i]["change_date"], "timestamp" => strtotime($transactions_changes_invoices_other_cashbox[$i]["change_date"]), "employee_id" => 0, "transaction_id" => $transactions_changes_invoices_other_cashbox[$i]["id"], "invoice_id" => $transactions_changes_invoices_other_cashbox[$i]["invoice_id"], "base_usd_amount" => $total_return, "rate" => $transactions_changes_invoices_other_cashbox[$i]["rate"], "cash_usd" => $transactions_changes_invoices_other_cashbox[$i]["cash_usd_in"], "cash_lbp" => $transactions_changes_invoices_other_cashbox[$i]["cash_lbp_in"], "returned_cash_usd" => $transactions_changes_invoices_other_cashbox[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_changes_invoices_other_cashbox[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_changes_invoices_other_cashbox[$i]["cash_usd_to_return"], "must_return_cash_lbp" => $transactions_changes_invoices_other_cashbox[$i]["cash_lbp_to_return"], "closed" => -1, "auto_closed" => -1, "changes" => 1, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 2, "is_external_transaction" => 0);
            $indx++;
        }
        $transactions_info = $transactions_class->get_transaction_for_cashbox_id($cashbox_id);
        for ($i = 0; $i < count($transactions_info); $i++) {
            $cash_usd_in = 0;
            $cash_lbp_in = 0;
            $cash_usd_out = 0;
            $cash_lbp_out = 0;
            if ($transactions_info[$i]["transaction_type"] == 1) {
                $cash_usd_in = $transactions_info[$i]["amount_usd"];
                $cash_lbp_in = $transactions_info[$i]["amount_lbp"];
            }
            if ($transactions_info[$i]["transaction_type"] == 2) {
                $cash_usd_out = $transactions_info[$i]["amount_usd"];
                $cash_lbp_out = $transactions_info[$i]["amount_lbp"];
            }
            $transactions[$indx] = array("creation_date" => $transactions_info[$i]["creation_date"], "timestamp" => strtotime($transactions_info[$i]["creation_date"]), "employee_id" => 0, "transaction_id" => $transactions_info[$i]["id"], "invoice_id" => "", "base_usd_amount" => "", "rate" => 0, "cash_usd" => $cash_usd_in, "cash_lbp" => $cash_lbp_in, "returned_cash_usd" => $cash_usd_out, "returned_cash_lbp" => $cash_lbp_out, "must_return_cash_usd" => 0, "must_return_cash_lbp" => 0, "closed" => -1, "auto_closed" => -1, "changes" => 0, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 3, "is_external_transaction" => $transactions_info[$i]["transaction_type"]);
            $indx++;
        }
        self::__USORT_TIMESTAMP($transactions);
        for ($i = 0; $i < $indx; $i++) {
            $tmp = array();
            array_push($tmp, $transactions[$i]["creation_date"]);
            if ($transactions[$i]["changes"] == 1) {
                array_push($tmp, "<span class='cret'>R/C-" . $transactions[$i]["invoice_id"] . "</span>");
            } else {
                if (0 < $transactions[$i]["is_external_transaction"]) {
                    if ($transactions[$i]["is_external_transaction"] == 1) {
                        array_push($tmp, "<span class='cin'>CASH IN-" . $transactions[$i]["transaction_id"] . "</span>");
                    }
                    if ($transactions[$i]["is_external_transaction"] == 2) {
                        array_push($tmp, "<span class='cout'>CASH OUT-" . $transactions[$i]["transaction_id"] . "</span>");
                    }
                    if ($transactions[$i]["is_external_transaction"] == 3) {
                        array_push($tmp, "<span class='cout'>CASH TRANS-" . $transactions[$i]["transaction_id"] . "</span>");
                    }
                } else {
                    if ($transactions[$i]["expenses"] == 1) {
                        array_push($tmp, "<span class='cout'>EXP-" . $transactions[$i]["invoice_id"] . "</span>");
                    } else {
                        if ($transactions[$i]["supplier_p"] == 1) {
                            array_push($tmp, "<span class='cout'>SUP-PAY-" . $transactions[$i]["invoice_id"] . "</span>");
                        } else {
                            if ($transactions[$i]["client_payment"] == 1) {
                                array_push($tmp, "<span class='cin'>CP-" . $transactions[$i]["invoice_id"] . "</span>");
                            } else {
                                if ($transactions[$i]["closed"] == 0) {
                                    array_push($tmp, "<span class='cout'>INV-" . $transactions[$i]["invoice_id"] . "</span>");
                                } else {
                                    array_push($tmp, "<span class='cin'>INV-" . $transactions[$i]["invoice_id"] . "</span>");
                                }
                            }
                        }
                    }
                }
            }
            $checker = "";
            if ($transactions[$i]["rate"] == 0) {
                $checker = "<i class='glyphicon glyphicon-exclamation-sign error_cash'></i>";
            } else {
                $tousd = $transactions[$i]["cash_lbp"] / $transactions[$i]["rate"] + $transactions[$i]["cash_usd"] - $transactions[$i]["returned_cash_usd"] - $transactions[$i]["returned_cash_lbp"] / $transactions[$i]["rate"];
                if (abs($transactions[$i]["base_usd_amount"] - $tousd) <= 0.1) {
                    $tousd = floatval(round($tousd, 2));
                }
                if (floatval($tousd) != floatval($transactions[$i]["base_usd_amount"]) && 0 < $tousd || 0 < $transactions[$i]["must_return_cash_usd"] || 0 < $transactions[$i]["must_return_cash_lbp"]) {
                    $checker = "<i class='glyphicon glyphicon-exclamation-sign error_cash'></i>";
                }
                if ($transactions[$i]["cash_lbp"] == 0 && $transactions[$i]["cash_usd"] == 0 && $transactions[$i]["returned_cash_usd"] == 0 && $transactions[$i]["returned_cash_lbp"] == 0) {
                    $checker = "<i class='glyphicon glyphicon-exclamation-sign error_cash'></i>";
                }
            }
            if (0 < $transactions[$i]["is_external_transaction"]) {
                $checker = "";
            }
            if ($transactions[$i]["base_usd_amount"] == "") {
                array_push($tmp, "");
            } else {
                array_push($tmp, number_format($transactions[$i]["base_usd_amount"], 2) . " " . $checker);
            }
            if (0 < $transactions[$i]["is_external_transaction"]) {
                array_push($tmp, "");
            } else {
                array_push($tmp, number_format($transactions[$i]["rate"], 0));
            }
            if (0 < $transactions[$i]["cash_usd"]) {
                array_push($tmp, "<span class='cin'>" . number_format($transactions[$i]["cash_usd"], 2) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["cash_lbp"]) {
                array_push($tmp, "<span class='cin'>" . number_format($transactions[$i]["cash_lbp"], 0) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["returned_cash_usd"]) {
                array_push($tmp, "<span class='cout'>" . number_format($transactions[$i]["returned_cash_usd"], 2) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["returned_cash_lbp"]) {
                array_push($tmp, "<span class='cout'>" . number_format($transactions[$i]["returned_cash_lbp"], 0) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["must_return_cash_usd"]) {
                array_push($tmp, "<span class='cwar'>" . number_format($transactions[$i]["must_return_cash_usd"], 2) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["must_return_cash_lbp"]) {
                array_push($tmp, "<span class='cwar'>" . number_format($transactions[$i]["must_return_cash_lbp"], 0) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if ($transactions[$i]["is_invoice"] == 1 && $transactions[$i]["closed"] == 1 && $transactions[$i]["auto_closed"] == 0) {
                $net_usd += $transactions[$i]["cash_usd"] - $transactions[$i]["returned_cash_usd"];
                $net_lbp += $transactions[$i]["cash_lbp"] - $transactions[$i]["returned_cash_lbp"];
            }
            if ($transactions[$i]["is_invoice"] == 0) {
                $net_usd += $transactions[$i]["cash_usd"] - $transactions[$i]["returned_cash_usd"];
                $net_lbp += $transactions[$i]["cash_lbp"] - $transactions[$i]["returned_cash_lbp"];
            }
            array_push($tmp, "<b>" . number_format($net_usd, 2) . "</b>");
            array_push($tmp, "<b>" . number_format($net_lbp, 0) . "</b>");
            array_push($tmp, "");
            array_push($tmp, $transactions[$i]["payment_type"]);
            array_push($tmp, $transactions[$i]["transaction_id"]);
            $return_info_limited["net_usd"] = $net_usd;
            $return_info_limited["net_lbp"] = $net_lbp;
            array_push($data_array["data"], $tmp);
        }
        if ($return_type == 0) {
            $invoice = $this->model("invoice");
            $delinv = $invoice->getDeleted_invoices($cashbox_id);
            $data_array["totdel"] = count($delinv);
            echo json_encode($data_array);
        } else {
            return $return_info_limited;
        }
    }
    public function get_store_connection($id)
    {
        $query = "select * from store where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $host = $result[0]["ip_address"];
        $username = $result[0]["username"];
        $password = $result[0]["password"];
        $db = $result[0]["db"];
        $cnx = mysqli_connect($host, $username, $password, $db);
        return $cnx;
    }
    public function get_accessible_branches()
    {
        $branchModel = $this->model("branch");
        $return["ids"] = array();
        $return["details"] = array();
        $branches = $branchModel->get_branches_limited();
        $branches_user = $branchModel->get_my_access_branches();
        if (in_array(0, $branches_user)) {
            array_push($return["ids"], 0);
            array_push($return["details"], array("id" => 0, "branch_name" => "Main Branch", "location_name" => ""));
        }
        for ($i = 0; $i < count($branches); $i++) {
            if (in_array($branches[$i]["id"], $branches_user)) {
                array_push($return["ids"], $branches[$i]["id"]);
                array_push($return["details"], $branches[$i]);
            }
        }
        return $return;
    }
    public function get_sum_qty_in_all_stores($item_id)
    {
        $store_class = $this->model("store");
        $stock_class = $this->model("stock");
        $stores = $store_class->getAllStores();
        $total = 0;
        for ($i = 0; $i < count($stores); $i++) {
            $cnx = self::get_store_connection($stores[$i]["id"]);
            $qty_in_store = $stock_class->get_qty_in_store($item_id, $cnx);
            if (0 < count($qty_in_store)) {
                $total += $qty_in_store[0]["quantity"];
            }
        }
        return $total;
    }
    public function _get_full_remote_report_table($return_type, $cashbox_id, $store_id)
    {
        $cashbox = $this->model("cashbox");
        $customers = $this->model("customers");
        $suppliers = $this->model("suppliers");
        $transactions_class = $this->model("transactions");
        $cnx = self::get_store_connection($store_id);
        $cashbox_info = $cashbox->geCashboxById_remote($cashbox_id, $cnx);
        $data_array = array();
        $data_array["data"] = array();
        $return_info_limited = array();
        $return_info_limited["net_usd"] = 0;
        $return_info_limited["net_lbp"] = 0;
        $transactions_sales_invoices = $cashbox->get_sales_invoice_transacions_details_remote($cashbox_id, $cnx);
        $transactions_changes_invoices = $cashbox->get_changes_invoice_transacions_details_remote($cashbox_id, $cnx);
        $transactions_changes_invoices_other_cashbox = $cashbox->get_changes_invoice_transacions_details_other_cashbox_remote($cashbox_id, $cnx);
        $transactions_payments_clients = $cashbox->get_clients_payment_transacions_details_remote($cashbox_id, $cnx);
        $transactions_expenses = $cashbox->get_expenses_transacions_details_remote($cashbox_id, $cnx);
        $transactions_suppliers = $cashbox->get_suppliers_transacions_details_remote($cashbox_id, $cnx);
        $net_lbp = $cashbox_info[0]["cashbox_lbp"];
        $net_usd = $cashbox_info[0]["cash"];
        $return_info_limited["net_usd"] = $net_usd;
        $return_info_limited["net_lbp"] = $net_lbp;
        $tmp = array();
        array_push($tmp, $cashbox_info[0]["starting_cashbox_date"]);
        array_push($tmp, "<b>Cashbox is started</b>");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "<span class='cin'>" . number_format($cashbox_info[0]["cash"], 2) . "</span>");
        array_push($tmp, "<span class='cin'>" . number_format($cashbox_info[0]["cashbox_lbp"], 0) . "</span>");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "<b>" . number_format($net_usd, 2) . "</b>");
        array_push($tmp, "<b>" . number_format($net_lbp, 0) . "</b>");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($tmp, "");
        array_push($data_array["data"], $tmp);
        $customers_to_get = array();
        for ($i = 0; $i < count($transactions_sales_invoices); $i++) {
            if (0 < $transactions_sales_invoices[$i]["customer_id"] && !in_array($transactions_sales_invoices[$i]["customer_id"], $customers_to_get)) {
                array_push($customers_to_get, $transactions_sales_invoices[$i]["customer_id"]);
            }
        }
        $customers_array = array();
        if (0 < count($customers_to_get)) {
            $customers_result = $customers->getCustomersByIDSArray_remote($customers_to_get, $cnx);
            for ($i = 0; $i < count($customers_result); $i++) {
                $customers_array[$customers_result[$i]["id"]] = $customers_result[$i];
            }
        }
        $transactions = array();
        $indx = 0;
        for ($i = 0; $i < count($transactions_sales_invoices); $i++) {
            $cus_name = "";
            if (0 < $transactions_sales_invoices[$i]["customer_id"]) {
                $cus_name = "<br/><small class='cus_name'>" . $customers_array[$transactions_sales_invoices[$i]["customer_id"]]["name"] . "</small>";
            }
            $transactions[$indx] = array("creation_date" => $transactions_sales_invoices[$i]["creation_date"], "timestamp" => strtotime($transactions_sales_invoices[$i]["creation_date"]), "transaction_id" => $transactions_sales_invoices[$i]["id"], "employee_id" => $transactions_sales_invoices[$i]["employee_id"], "invoice_id" => $transactions_sales_invoices[$i]["invoice_id"] . $cus_name, "base_usd_amount" => $transactions_sales_invoices[$i]["base_usd_amount"], "rate" => $transactions_sales_invoices[$i]["rate"], "cash_usd" => $transactions_sales_invoices[$i]["cash_usd"], "cash_lbp" => $transactions_sales_invoices[$i]["cash_lbp"], "returned_cash_usd" => $transactions_sales_invoices[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_sales_invoices[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_sales_invoices[$i]["must_return_cash_usd"], "must_return_cash_lbp" => $transactions_sales_invoices[$i]["must_return_cash_lbp"], "closed" => $transactions_sales_invoices[$i]["closed"], "auto_closed" => $transactions_sales_invoices[$i]["auto_closed"], "changes" => 0, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 1, "payment_type" => 1, "is_external_transaction" => 0);
            $indx++;
        }
        $suppliers_to_get = array();
        for ($i = 0; $i < count($transactions_suppliers); $i++) {
            if (!in_array($transactions_suppliers[$i]["supplier_id"], $suppliers_to_get)) {
                array_push($suppliers_to_get, $transactions_suppliers[$i]["supplier_id"]);
            }
        }
        $suppliers_array = array();
        if (0 < count($suppliers_to_get)) {
            $suppliers_result = $suppliers->getSuppliersByArray_remote($suppliers_to_get, $cnx);
            for ($i = 0; $i < count($suppliers_result); $i++) {
                $suppliers_array[$suppliers_result[$i]["id"]] = $suppliers_result[$i];
            }
        }
        for ($i = 0; $i < count($transactions_suppliers); $i++) {
            $sup_name = "";
            if (0 < $transactions_suppliers[$i]["supplier_id"]) {
                $sup_name = "<br/><small class='cus_name'>" . $suppliers_array[$transactions_suppliers[$i]["supplier_id"]]["name"] . "</small>";
            }
            $transactions[$indx] = array("creation_date" => $transactions_suppliers[$i]["creation_date"], "timestamp" => strtotime($transactions_suppliers[$i]["creation_date"]), "employee_id" => 0, "transaction_id" => 0, "invoice_id" => $transactions_suppliers[$i]["id"] . $sup_name, "base_usd_amount" => $transactions_suppliers[$i]["payment_value"], "rate" => $transactions_suppliers[$i]["rate"], "cash_usd" => $transactions_suppliers[$i]["cash_in_usd"], "cash_lbp" => $transactions_suppliers[$i]["cash_in_lbp"], "returned_cash_usd" => $transactions_suppliers[$i]["returned_usd"], "returned_cash_lbp" => $transactions_suppliers[$i]["returned_lbp"], "must_return_cash_usd" => $transactions_suppliers[$i]["to_returned_usd"], "must_return_cash_lbp" => $transactions_suppliers[$i]["to_returned_lbp"], "closed" => 1, "auto_closed" => 0, "changes" => 0, "client_payment" => 0, "expenses" => 0, "supplier_p" => 1, "is_invoice" => 0, "payment_type" => 0, "is_external_transaction" => 0);
            $indx++;
        }
        for ($i = 0; $i < count($transactions_expenses); $i++) {
            $transactions[$indx] = array("creation_date" => $transactions_expenses[$i]["creation_date"], "timestamp" => strtotime($transactions_expenses[$i]["creation_date"]), "employee_id" => 0, "transaction_id" => 0, "invoice_id" => $transactions_expenses[$i]["id"], "base_usd_amount" => $transactions_expenses[$i]["value"], "rate" => $transactions_expenses[$i]["rate"], "cash_usd" => $transactions_expenses[$i]["cash_usd_in"], "cash_lbp" => $transactions_expenses[$i]["cash_lbp_in"], "returned_cash_usd" => $transactions_expenses[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_expenses[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_expenses[$i]["cash_usd_to_return"], "must_return_cash_lbp" => $transactions_expenses[$i]["cash_lbp_to_return"], "closed" => 1, "auto_closed" => 0, "changes" => 0, "client_payment" => 0, "expenses" => 1, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 0, "is_external_transaction" => 0);
            $indx++;
        }
        $customers_p_to_get = array();
        for ($i = 0; $i < count($transactions_payments_clients); $i++) {
            if (!in_array($transactions_payments_clients[$i]["customer_id"], $customers_p_to_get)) {
                array_push($customers_p_to_get, $transactions_payments_clients[$i]["customer_id"]);
            }
        }
        $customers_p_array = array();
        if (0 < count($customers_p_to_get)) {
            $cp_result = $customers->getCustomersByIDSArray_remote($customers_p_to_get, $cnx);
            for ($i = 0; $i < count($cp_result); $i++) {
                $customers_p_array[$cp_result[$i]["id"]] = $cp_result[$i];
            }
        }
        for ($i = 0; $i < count($transactions_payments_clients); $i++) {
            $cu_name = "";
            if (0 < $transactions_payments_clients[$i]["customer_id"]) {
                $cu_name = "<br/><small class='cus_name'>" . $customers_p_array[$transactions_payments_clients[$i]["customer_id"]]["name"] . "</small>";
            }
            $transactions[$indx] = array("creation_date" => $transactions_payments_clients[$i]["value_date"], "timestamp" => strtotime($transactions_payments_clients[$i]["value_date"]), "employee_id" => $transactions_payments_clients[$i]["vendor_id"], "transaction_id" => 0, "invoice_id" => $transactions_payments_clients[$i]["id"] . "" . $cu_name, "base_usd_amount" => $transactions_payments_clients[$i]["balance"], "rate" => $transactions_payments_clients[$i]["p_rate"], "cash_usd" => $transactions_payments_clients[$i]["cash_in_usd"], "cash_lbp" => $transactions_payments_clients[$i]["cash_in_lbp"], "returned_cash_usd" => $transactions_payments_clients[$i]["returned_usd"], "returned_cash_lbp" => $transactions_payments_clients[$i]["returned_lbp"], "must_return_cash_usd" => $transactions_payments_clients[$i]["to_returned_usd"], "must_return_cash_lbp" => $transactions_payments_clients[$i]["to_returned_lbp"], "closed" => 0, "auto_closed" => 0, "changes" => 0, "client_payment" => 1, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 0, "is_external_transaction" => 0);
            $indx++;
        }
        for ($i = 0; $i < count($transactions_changes_invoices); $i++) {
            $total_return = 0;
            if ($transactions_changes_invoices[$i]["added_value"] <= $transactions_changes_invoices[$i]["return_value"]) {
                $total_return = $transactions_changes_invoices[$i]["return_value"] - $transactions_changes_invoices[$i]["added_value"];
            } else {
                $total_return = $transactions_changes_invoices[$i]["added_value"] - $transactions_changes_invoices[$i]["return_value"];
            }
            $transactions[$indx] = array("creation_date" => $transactions_changes_invoices[$i]["change_date"], "timestamp" => strtotime($transactions_changes_invoices[$i]["change_date"]), "employee_id" => 0, "transaction_id" => $transactions_changes_invoices[$i]["id"], "invoice_id" => $transactions_changes_invoices[$i]["invoice_id"], "base_usd_amount" => $total_return, "rate" => $transactions_changes_invoices[$i]["rate"], "cash_usd" => $transactions_changes_invoices[$i]["cash_usd_in"], "cash_lbp" => $transactions_changes_invoices[$i]["cash_lbp_in"], "returned_cash_usd" => $transactions_changes_invoices[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_changes_invoices[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_changes_invoices[$i]["cash_usd_to_return"], "must_return_cash_lbp" => $transactions_changes_invoices[$i]["cash_lbp_to_return"], "closed" => -1, "auto_closed" => -1, "changes" => 1, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 2, "is_external_transaction" => 0);
            $indx++;
        }
        for ($i = 0; $i < count($transactions_changes_invoices_other_cashbox); $i++) {
            $total_return = 0;
            if ($transactions_changes_invoices_other_cashbox[$i]["added_value"] <= $transactions_changes_invoices_other_cashbox[$i]["return_value"]) {
                $total_return = $transactions_changes_invoices_other_cashbox[$i]["return_value"] - $transactions_changes_invoices_other_cashbox[$i]["added_value"];
            } else {
                $total_return = $transactions_changes_invoices_other_cashbox[$i]["added_value"] - $transactions_changes_invoices_other_cashbox[$i]["return_value"];
            }
            $transactions[$indx] = array("creation_date" => $transactions_changes_invoices_other_cashbox[$i]["change_date"], "timestamp" => strtotime($transactions_changes_invoices_other_cashbox[$i]["change_date"]), "employee_id" => 0, "transaction_id" => $transactions_changes_invoices_other_cashbox[$i]["id"], "invoice_id" => $transactions_changes_invoices_other_cashbox[$i]["invoice_id"], "base_usd_amount" => $total_return, "rate" => $transactions_changes_invoices_other_cashbox[$i]["rate"], "cash_usd" => $transactions_changes_invoices_other_cashbox[$i]["cash_usd_in"], "cash_lbp" => $transactions_changes_invoices_other_cashbox[$i]["cash_lbp_in"], "returned_cash_usd" => $transactions_changes_invoices_other_cashbox[$i]["returned_cash_usd"], "returned_cash_lbp" => $transactions_changes_invoices_other_cashbox[$i]["returned_cash_lbp"], "must_return_cash_usd" => $transactions_changes_invoices_other_cashbox[$i]["cash_usd_to_return"], "must_return_cash_lbp" => $transactions_changes_invoices_other_cashbox[$i]["cash_lbp_to_return"], "closed" => -1, "auto_closed" => -1, "changes" => 1, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 2, "is_external_transaction" => 0);
            $indx++;
        }
        $transactions_info = $transactions_class->get_transaction_for_cashbox_id_remote($cashbox_id, $cnx);
        for ($i = 0; $i < count($transactions_info); $i++) {
            $cash_usd_in = 0;
            $cash_lbp_in = 0;
            $cash_usd_out = 0;
            $cash_lbp_out = 0;
            if ($transactions_info[$i]["transaction_type"] == 1) {
                $cash_usd_in = $transactions_info[$i]["amount_usd"];
                $cash_lbp_in = $transactions_info[$i]["amount_lbp"];
            }
            if ($transactions_info[$i]["transaction_type"] == 2) {
                $cash_usd_out = $transactions_info[$i]["amount_usd"];
                $cash_lbp_out = $transactions_info[$i]["amount_lbp"];
            }
            $transactions[$indx] = array("creation_date" => $transactions_info[$i]["creation_date"], "timestamp" => strtotime($transactions_info[$i]["creation_date"]), "employee_id" => 0, "transaction_id" => $transactions_info[$i]["id"], "invoice_id" => "", "base_usd_amount" => "", "rate" => 0, "cash_usd" => $cash_usd_in, "cash_lbp" => $cash_lbp_in, "returned_cash_usd" => $cash_usd_out, "returned_cash_lbp" => $cash_lbp_out, "must_return_cash_usd" => 0, "must_return_cash_lbp" => 0, "closed" => -1, "auto_closed" => -1, "changes" => 0, "client_payment" => 0, "expenses" => 0, "supplier_p" => 0, "is_invoice" => 0, "payment_type" => 3, "is_external_transaction" => $transactions_info[$i]["transaction_type"]);
            $indx++;
        }
        self::__USORT_TIMESTAMP($transactions);
        for ($i = 0; $i < $indx; $i++) {
            $tmp = array();
            array_push($tmp, $transactions[$i]["creation_date"]);
            if ($transactions[$i]["changes"] == 1) {
                array_push($tmp, "<span class='cret'>R/C-" . $transactions[$i]["invoice_id"] . "</span>");
            } else {
                if (0 < $transactions[$i]["is_external_transaction"]) {
                    if ($transactions[$i]["is_external_transaction"] == 1) {
                        array_push($tmp, "<span class='cin'>CASH IN-" . $transactions[$i]["transaction_id"] . "</span>");
                    }
                    if ($transactions[$i]["is_external_transaction"] == 2) {
                        array_push($tmp, "<span class='cout'>CASH OUT-" . $transactions[$i]["transaction_id"] . "</span>");
                    }
                    if ($transactions[$i]["is_external_transaction"] == 3) {
                        array_push($tmp, "<span class='cout'>CASH TRANS-" . $transactions[$i]["transaction_id"] . "</span>");
                    }
                } else {
                    if ($transactions[$i]["expenses"] == 1) {
                        array_push($tmp, "<span class='cout'>EXP-" . $transactions[$i]["invoice_id"] . "</span>");
                    } else {
                        if ($transactions[$i]["supplier_p"] == 1) {
                            array_push($tmp, "<span class='cout'>SUP-PAY-" . $transactions[$i]["invoice_id"] . "</span>");
                        } else {
                            if ($transactions[$i]["client_payment"] == 1) {
                                array_push($tmp, "<span class='cin'>CP-" . $transactions[$i]["invoice_id"] . "</span>");
                            } else {
                                if ($transactions[$i]["closed"] == 0) {
                                    array_push($tmp, "<span class='cout'>INV-" . $transactions[$i]["invoice_id"] . "</span>");
                                } else {
                                    array_push($tmp, "<span class='cin'>INV-" . $transactions[$i]["invoice_id"] . "</span>");
                                }
                            }
                        }
                    }
                }
            }
            $checker = "";
            if ($transactions[$i]["rate"] == 0) {
                $checker = "<i class='glyphicon glyphicon-exclamation-sign error_cash'></i>";
            } else {
                $tousd = $transactions[$i]["cash_lbp"] / $transactions[$i]["rate"] + $transactions[$i]["cash_usd"] - $transactions[$i]["returned_cash_usd"] - $transactions[$i]["returned_cash_lbp"] / $transactions[$i]["rate"];
                if (abs($transactions[$i]["base_usd_amount"] - $tousd) <= 0.1) {
                    $tousd = floatval(round($tousd, 2));
                }
                if (floatval($tousd) != floatval($transactions[$i]["base_usd_amount"]) && 0 < $tousd || 0 < $transactions[$i]["must_return_cash_usd"] || 0 < $transactions[$i]["must_return_cash_lbp"]) {
                    $checker = "<i class='glyphicon glyphicon-exclamation-sign error_cash'></i>";
                }
            }
            if (0 < $transactions[$i]["is_external_transaction"]) {
                $checker = "";
            }
            if ($transactions[$i]["base_usd_amount"] == "") {
                array_push($tmp, "");
            } else {
                array_push($tmp, number_format($transactions[$i]["base_usd_amount"], 2) . " " . $checker);
            }
            if (0 < $transactions[$i]["is_external_transaction"]) {
                array_push($tmp, "");
            } else {
                array_push($tmp, number_format($transactions[$i]["rate"], 0));
            }
            if (0 < $transactions[$i]["cash_usd"]) {
                array_push($tmp, "<span class='cin'>" . number_format($transactions[$i]["cash_usd"], 2) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["cash_lbp"]) {
                array_push($tmp, "<span class='cin'>" . number_format($transactions[$i]["cash_lbp"], 0) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["returned_cash_usd"]) {
                array_push($tmp, "<span class='cout'>" . number_format($transactions[$i]["returned_cash_usd"], 2) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["returned_cash_lbp"]) {
                array_push($tmp, "<span class='cout'>" . number_format($transactions[$i]["returned_cash_lbp"], 0) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["must_return_cash_usd"]) {
                array_push($tmp, "<span class='cwar'>" . number_format($transactions[$i]["must_return_cash_usd"], 2) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if (0 < $transactions[$i]["must_return_cash_lbp"]) {
                array_push($tmp, "<span class='cwar'>" . number_format($transactions[$i]["must_return_cash_lbp"], 0) . "</span>");
            } else {
                array_push($tmp, "");
            }
            if ($transactions[$i]["is_invoice"] == 1 && $transactions[$i]["closed"] == 1 && $transactions[$i]["auto_closed"] == 0) {
                $net_usd += $transactions[$i]["cash_usd"] - $transactions[$i]["returned_cash_usd"];
                $net_lbp += $transactions[$i]["cash_lbp"] - $transactions[$i]["returned_cash_lbp"];
            }
            if ($transactions[$i]["is_invoice"] == 0) {
                $net_usd += $transactions[$i]["cash_usd"] - $transactions[$i]["returned_cash_usd"];
                $net_lbp += $transactions[$i]["cash_lbp"] - $transactions[$i]["returned_cash_lbp"];
            }
            array_push($tmp, "<b>" . number_format($net_usd, 2) . "</b>");
            array_push($tmp, "<b>" . number_format($net_lbp, 0) . "</b>");
            array_push($tmp, "");
            array_push($tmp, $transactions[$i]["payment_type"]);
            array_push($tmp, $transactions[$i]["transaction_id"]);
            $return_info_limited["net_usd"] = $net_usd;
            $return_info_limited["net_lbp"] = $net_lbp;
            array_push($data_array["data"], $tmp);
        }
        if ($return_type == 0) {
            $invoice = $this->model("invoice");
            $delinv = $invoice->getDeleted_invoices_remote($cashbox_id);
            $data_array["totdel"] = count($delinv);
            echo json_encode($data_array);
        } else {
            return $return_info_limited;
        }
    }
    public function post_web_page($api_url, $data_info)
    {
        $curl = curl_init($api_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($curl, CURLOPT_USERPWD, $data_info["consumer_key"] . ":" . $data_info["consumer_secret"]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_info["data"]));
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $return = array("response" => $response, "http_code" => $http_code);
        curl_close($curl);
        return $return;
    }
    public function put_web_page($url, $data_info)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_info["data"]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_USERPWD, $data_info["consumer_key"] . ":" . $data_info["consumer_secret"]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "Error: " . curl_error($ch);
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $return = array("response" => $response, "http_code" => $http_code);
        curl_close($ch);
        return $return;
    }
    public function post_web_page_new($url, $data_info)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "identity");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, $data_info["consumer_key"] . ":" . $data_info["consumer_secret"]);
        if (!empty($data_info)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_info["data"]));
        }
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            trigger_error("Curl Error:" . curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }
    public function delete_web_page($url, $data_info)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "?force=true");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_USERPWD, $data_info["consumer_key"] . ":" . $data_info["consumer_secret"]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "Error: " . curl_error($ch);
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $return = array("response" => $response, "http_code" => $http_code);
        curl_close($ch);
        return $return;
    }
    public function isStrongPassword($password)
    {
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match("/[A-Z]/", $password)) {
            return false;
        }
        if (!preg_match("/[a-z]/", $password)) {
            return false;
        }
        if (!preg_match("/\\d/", $password)) {
            return false;
        }
        if (!preg_match("/[^A-Za-z0-9]/", $password)) {
            return false;
        }
        return true;
    }
    public function check_passwords_if_strong($to_user_id)
    {
        $user = self::model("user");
        $notification = self::model("notification");
        $users = $user->getAllUsers_Passwords();
        for ($i = 0; $i < count($users); $i++) {
            if (!self::isStrongPassword($users[$i]["password"])) {
                $info = array();
                $info["to_user"] = $to_user_id;
                $info["title"] = "Weak Password";
                $info["description"] = "Create a robust password to enhance the security of <b>" . $users[$i]["username"] . "<b/>";
                $info["icon"] = "info";
                $info["bg_color"] = "d43f3a";
                $info["hide_after"] = 0;
                $info["type"] = 1;
                $notification->add_notification($info);
            }
        }
    }
}

?>