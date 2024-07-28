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
class notifications extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function get_new_not()
    {
        $notification = $this->model("notification");
        $notification_details = $notification->get_notifications($_SESSION["id"]);
        $notifications = array();
        $notifications_to_set_as_notified = array();
        for ($i = 0; $i < count($notification_details); $i++) {
            array_push($notifications_to_set_as_notified, $notification_details[$i]["id"]);
            if ($notification_details[$i]["hide_after"] == 0) {
                $notification_details[$i]["hide_after"] = false;
            }
            array_push($notifications, array("heading" => $notification_details[$i]["title"], "text" => $notification_details[$i]["description"], "icon" => $notification_details[$i]["icon"], "hideAfter" => $notification_details[$i]["hide_after"], "bgColor" => $notification_details[$i]["bg_color"]));
        }
        $notification->set_as_notified($notifications_to_set_as_notified);
        echo json_encode($notifications);
    }
}

?>