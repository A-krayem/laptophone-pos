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
class transactionsModel
{
    public function add_new_transaction($info)
    {
        $query = "insert into cashbox_transactions(transaction_type,amount_usd,from_cashbox_id,to_cashbox_id,creation_date,created_by,note,amount_lbp) " . "values('" . $info["transaction_type"] . "','" . $info["amount_usd"] . "','" . $info["current_cashbox_id"] . "','" . $info["transaction_to_cashbox_id"] . "','" . my_sql::datetime_now() . "','" . $info["created_by"] . "','" . $info["transaction_note"] . "','" . $info["amount_lbp"] . "')";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function get_transaction_for_cashbox_id($cashbox_id)
    {
        $query = "select * from  cashbox_transactions where deleted=0 and (from_cashbox_id=" . $cashbox_id . " or to_cashbox_id=" . $cashbox_id . ") ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_transaction_for_cashbox_id_remote($cashbox_id, $cnx)
    {
        $query = "select * from  cashbox_transactions where deleted=0 and (from_cashbox_id=" . $cashbox_id . " or to_cashbox_id=" . $cashbox_id . ") ";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    public function get_all_transactions_filters($filter)
    {
        $deleted_filter = "";
        if ($filter["deleted"] == 1) {
            $deleted_filter = " and deleted=0 ";
        }
        if ($filter["deleted"] == 2) {
            $deleted_filter = " and deleted=1";
        }
        $query = "select * from  cashbox_transactions where  1 " . $deleted_filter . " and date(creation_date)>='" . $filter["start_date"] . "' and date(creation_date)<='" . $filter["end_date"] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_transaction($transaction_id)
    {
        my_sql::query("update cashbox_transactions set deleted=1 where id=" . $transaction_id);
        return my_sql::get_mysqli_rows_num();
    }
    public function get_transaction_by_id($transaction_id)
    {
        $result = my_sql::fetch_assoc(my_sql::query("select * from cashbox_transactions where id=" . $transaction_id));
        return $result;
    }
}

?>