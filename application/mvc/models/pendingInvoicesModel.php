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
class pendingInvoicesModel
{
    public function save($data, $note, $location)
    {
        $createdBy = $_SESSION["id"];
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        my_sql::query("INSERT INTO pending_invoices(created_by,creation_date,deleted,data,note,location) values (" . $createdBy . ",NOW(),0,'" . $data . "','" . $note . "','" . $location . "')");
        return my_sql::get_mysqli_insert_id();
    }
    public function save_auto_hold($data, $note, $location)
    {
        $createdBy = $_SESSION["id"];
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $result_temp = my_sql::fetch_assoc(my_sql::query("select count(id) as num from pending_invoices where created_by=" . $createdBy . " and is_current_temp=1 and deleted=0"));
        if ($result_temp[0]["num"] == 0) {
            my_sql::query("INSERT INTO pending_invoices(created_by,creation_date,deleted,data,note,location,is_current_temp) values (" . $createdBy . ",NOW(),0,'" . $data . "','" . $note . "','" . $location . "',1)");
            return my_sql::get_mysqli_insert_id();
        }
        my_sql::query("update pending_invoices set data='" . $data . "' where created_by=" . $createdBy . " and is_current_temp=1 ");
        return my_sql::get_mysqli_insert_id();
    }
    public function getAll()
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT pending_invoices.*,users.name as user_name FROM pending_invoices left join users on users.id=pending_invoices.created_by  where pending_invoices.deleted=0"));
    }
    public function delete($pending_id)
    {
        my_sql::query("UPDATE pending_invoices set deleted=1 where id =" . $pending_id); // where is_current_temp=0
        return 0 < my_sql::get_mysqli_rows_num();
    }
    public function get($pending_id)
    {
        $result = my_sql::fetch_assoc(my_sql::query("SELECT * FROM pending_invoices where id=" . $pending_id));
        return $result[0];
    }
}

?>