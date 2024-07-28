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
class license extends Controller
{
    public $settings_info = NULL;
    public $licenseExpired = false;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    public function getGeneralInfo()
    {
        $path = __FILE__;
        return md5(self::GetVolumeLabel($path[0])) . "#" . $path[0] . "#" . php_uname() . "#" . PHP_OS;
    }
    public function _default()
    {
        self::giveAccessTo();
        $data = array();
        $data["encrypted"] = self::mc_encrypt(self::generate_key(), ENCRYPTION_KEY_1);
        $this->view("license", $data);
    }
    public function requestLicense()
    {
        self::giveAccessTo();
        $url = $this->settings_info["url_request"];
        $general_info = self::mc_encrypt(self::generate_key(), ENCRYPTION_KEY_1);
        $myvars = "general_info=" . urlencode($general_info) . "&customer_id=" . $this->settings_info["customer_id"];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $json = json_decode($response, true);
        $info = array();
        $info["status"] = 0;
        if (0 < strlen($json[0]["activation_code"])) {
            $license = $this->model("license");
            $info["activation_code"] = $json[0]["activation_code"];
            $info["serial_number"] = "";
            $license->updateLicense($info);
            $info["status"] = 1;
        }
        echo json_encode($info);
    }
    public function _requestLicense()
    {
        self::giveAccessTo();
        $url = $this->settings_info["url_request"];
        $general_info = self::mc_encrypt(self::generate_key(), ENCRYPTION_KEY_1);
        $myvars = "general_info=" . urlencode($general_info) . "&customer_id=" . $this->settings_info["customer_id"];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $json = json_decode($response, true);
        $info = array();
        $info["status"] = 0;
        if (0 < strlen($json[0]["activation_code"]) && 0 < strlen($json[0]["serial_number"])) {
            $license = $this->model("license");
            $info["activation_code"] = $json[0]["activation_code"];
            $info["serial_number"] = $json[0]["serial_number"];
            $license->updateLicense($info);
            $info["status"] = 1;
        }
        echo json_encode($info);
    }
}

?>