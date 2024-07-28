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
class ga_2fa extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function authorize()
    {
        $return = array();
        $return["valid"] = 0;
        $g = new PHPGangsta_GoogleAuthenticator();
        $scode = filter_input(INPUT_POST, "scode", self::conversion_php_version_filter());
        $userModel = $this->model("user");
        $user_info = $userModel->get_user_by_id($_SESSION["id"]);
        $secret = $user_info[0]["ga_2fa_secret"];
        $result = $g->verifyCode($secret, $scode, 2);
        if ($_SESSION["role"] == 1) {
            $return["url"] = "index.php?r=dashboard";
        }
        if ($_SESSION["role"] == 2) {
            $return["url"] = "index.php?r=pos";
        }
        if ($result) {
            $return["valid"] = 1;
            $_SESSION["2fa_locked"] = 0;
        }
        echo json_encode($return);
    }
    public function enable_2fa()
    {
        $g = new PHPGangsta_GoogleAuthenticator();
        $secret = $g->createSecret();
        $userModel = $this->model("user");
        $user_info = $userModel->get_user_by_id($_SESSION["id"]);
        if ($user_info[0]["ga_2fa_enabled"] == 1) {
            echo json_encode(array());
            exit;
        }
        if ($user_info[0]["ga_2fa_secret"] == NULL) {
            $qrCodeUrl = $g->getQRCodeGoogleUrl("UPSILON 2FA " . $_SESSION["username"], $secret);
            $userModel->update_2fa_code($_SESSION["id"], $secret);
        } else {
            $qrCodeUrl = $g->getQRCodeGoogleUrl("UPSILON 2FA " . $_SESSION["username"], $user_info[0]["ga_2fa_secret"]);
        }
        $return = array();
        $return["qr"] = $qrCodeUrl;
        echo json_encode($return);
    }
    public function disable_2fa()
    {
        echo json_encode(array());
    }
    public function enable_save($userCode)
    {
        $return = array();
        $return["valid_code"] = 0;
        $g = new PHPGangsta_GoogleAuthenticator();
        $userModel = $this->model("user");
        $user_info = $userModel->get_user_by_id($_SESSION["id"]);
        $secret = $user_info[0]["ga_2fa_secret"];
        $result = $g->verifyCode($secret, $userCode, 2);
        if ($result) {
            $return["valid_code"] = 1;
            $userModel->set_enabled_2fa($_SESSION["id"], 1);
        }
        echo json_encode($return);
    }
    public function disable_save($userCode)
    {
        $return = array();
        $return["valid"] = 0;
        $g = new PHPGangsta_GoogleAuthenticator();
        $userModel = $this->model("user");
        $user_info = $userModel->get_user_by_id($_SESSION["id"]);
        $secret = $user_info[0]["ga_2fa_secret"];
        $result = $g->verifyCode($secret, $userCode, 2);
        if ($result) {
            $userModel->set_enabled_2fa($_SESSION["id"], 0);
            $return["valid"] = 1;
        }
        echo json_encode($return);
    }
}

?>