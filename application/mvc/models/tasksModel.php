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
class tasksModel
{
    public function add_new_task($info)
    {
        if ($_SESSION["role"] == 1) {
            $cbox = 0;
        } else {
            $cbox = $_SESSION["cashbox_id"];
        }
        $query = "insert into tasks_daily(description,due_date,creation_date,status,customer_id,created_by,remind_before,note_to,shift_id,fav) values('" . $info["task_description"] . "','" . $info["task_due_date"] . "','" . my_sql::datetime_now() . "',1,0," . $_SESSION["id"] . "," . $info["task_bd"] . "," . $info["note_to"] . "," . $cbox . "," . $info["fav"] . ")";
        my_sql::query($query);
        $id = my_sql::get_mysqli_insert_id();
        return $id;
    }
    public function get_favnotes($id)
    {
        $query = "select id,description from tasks_daily where deleted=0 and created_by=" . $id . " and fav=1 group by description";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_tasks($action, $daterange, $from, $to, $status)
    {
        $query = "";
        if ($action == 1) {
            $query = "select * from tasks_daily where deleted=0 and status=1 and '" . my_sql::datetime_now() . "'>=DATE_SUB(due_date, INTERVAL remind_before DAY) and (note_to=" . $_SESSION["id"] . " or created_by=" . $_SESSION["id"] . ") order by status asc,due_date asc";
        } else {
            $status_qry = "";
            if (0 < $status) {
                $status_qry = " and status=" . $status . " ";
            }
            if (0 < $from && 0 < $to) {
                if ($from == $_SESSION["id"]) {
                    $from_to_qry = " and (note_to=" . $to . " and created_by=" . $from . ") ";
                } else {
                    $from_to_qry = " and (note_to=" . $_SESSION["id"] . " and created_by=" . $from . ") ";
                }
            }
            if ($from == 0 && 0 < $to) {
                $from_to_qry = " and (note_to=" . $to . " and created_by=" . $_SESSION["id"] . ") ";
            }
            if ($from == 0 && $to == 0) {
                $from_to_qry = " and (note_to=" . $_SESSION["id"] . " or created_by=" . $_SESSION["id"] . ") ";
            }
            if (0 < $from && $to == 0) {
                if ($from == $_SESSION["id"]) {
                    $from_to_qry = " and (note_to=" . $to . " or created_by=" . $_SESSION["id"] . ") ";
                } else {
                    $from_to_qry = " and (note_to=" . $_SESSION["id"] . " and created_by=" . $from . ") ";
                }
            }
            if ($_SESSION["role"] == 1) {
                $fr_to = "";
                if (0 < $from && 0 < $to) {
                    $fr_to = "and created_by=" . $from . " and note_to=" . $to;
                }
                if (0 < $from && $to == 0) {
                    $fr_to = "and created_by=" . $from;
                }
                if ($from == 0 && 0 < $to) {
                    $fr_to = " and note_to=" . $to;
                }
                $query = "select * from tasks_daily where deleted=0 and date(due_date)>='" . $daterange[0] . "' and date(due_date)<='" . $daterange[1] . "' " . $fr_to . " " . $status_qry . " order by status asc,due_date asc";
            } else {
                $query = "select * from tasks_daily where deleted=0 " . $from_to_qry . "  and date(due_date)>='" . $daterange[0] . "' and date(due_date)<='" . $daterange[1] . "' " . $status_qry . "   order by status asc,due_date asc";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_task_by_id($id)
    {
        $query = "select * from tasks_daily where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_task($info)
    {
        my_sql::query("update tasks_daily set description='" . $info["task_description"] . "',due_date='" . $info["task_due_date"] . "',remind_before='" . $info["task_bd"] . "',note_to=" . $info["note_to"] . ",fav=" . $info["fav"] . " where id=" . $info["id_to_edit"]);
    }
    public function undo_status($id)
    {
        my_sql::query("update tasks_daily set status=1,leaved_note='' where id=" . $id);
    }
    public function delete_task($id)
    {
        my_sql::query("update tasks_daily set deleted=1 where id=" . $id);
    }
    public function delete_fav($id)
    {
        my_sql::query("update tasks_daily set fav=0 where id=" . $id);
    }
    public function set_task_status($id, $status, $leaved_note)
    {
        if ($_SESSION["role"] == 1) {
            $cbox = 0;
        } else {
            $cbox = $_SESSION["cashbox_id"];
        }
        my_sql::query("update tasks_daily set status=" . $status . ",leaved_note='" . $leaved_note . "',set_done_shift_id=" . $cbox . " where id=" . $id);
    }
    public function get_pending_task_nb()
    {
        $query = "select count(id) as nb from tasks_daily where deleted=0 and status=1 and '" . my_sql::datetime_now() . "'>=DATE_SUB(due_date, INTERVAL remind_before DAY)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>