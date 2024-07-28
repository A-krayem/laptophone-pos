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
class notificationModel
{
    public function get_notifications($user_id)
    {
        $query = "select * from notifications where notified=0 and to_user=" . $user_id . " order by creation_date asc limit 5";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_as_notified($ids)
    {
        if (0 < count($ids)) {
            $query = "update notifications set notified=1 where id in (" . implode(",", $ids) . ")";
            my_sql::query($query);
        }
    }
    public function add_notification($info)
    {
        my_sql::query("insert into notifications (to_user,creation_date,title,description,icon,bg_color,hide_after,type) values(" . "" . $info["to_user"] . "," . "now()," . "'" . $info["title"] . "'," . "'" . $info["description"] . "'," . "'" . $info["icon"] . "'," . "'" . $info["bg_color"] . "'," . "'" . $info["hide_after"] . "'," . "" . $info["type"] . ")");
    }
}

?>