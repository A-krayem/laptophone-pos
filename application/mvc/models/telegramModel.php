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
class telegramModel
{
    public function send_to_telegram($info, $telid)
    {
        $query = "insert into telegram(message,creation_date,status,telegram_id) values('" . $info["message"] . "',now(),0," . $telid . ")";
        my_sql::query($query);
    }
    public function get_all_pending_messages()
    {
        $query = "select * from telegram where status=0 order by id asc limit 10";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function set_as_sent($id)
    {
        my_sql::query("update telegram set status=1 where id=" . $id);
    }
}

?>