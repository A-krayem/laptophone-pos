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
class sync extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo(array(2));
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function sync_($_store_id)
    {
        self::giveAccessTo(array(2));
        $sync = $this->model("sync");
        $store_id = filter_var($_store_id, FILTER_SANITIZE_NUMBER_INT);
        if ($this->settings_info["global_admin_is_local"] == 0) {
            $store = $this->model("store");
            $store_global = $store->getStoresNotGlobalInDetails();
            if (0 < count($store_global)) {
                $result = $sync->sync_Transfers($store_global[0]["id"]);
            }
        }
        $result = $sync->sync_Data($store_id);
        if ($result == "0000") {
            $result_1 = $sync->sync_Transfers($store_id);
            if ($result_1 == "0000") {
                echo json_encode(array(0));
            } else {
                echo json_encode(array($result_1));
            }
        } else {
            echo json_encode(array($result));
        }
    }
}

?>