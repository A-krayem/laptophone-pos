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
class tasks extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function get_task_by_id($_task_id)
    {
        $tasks = $this->model("tasks");
        $id = filter_var($_task_id, FILTER_SANITIZE_NUMBER_INT);
        $info = $tasks->get_task_by_id($id);
        $tmp = explode(" ", $info[0]["due_date"]);
        $info[0]["due_date"] = $tmp[0];
        echo json_encode($info);
    }
    public function get_task_needed()
    {
        $user = $this->model("user");
        $info = array();
        $info["users"] = array();
        $user_info = $user->getAllUsers();
        for ($i = 0; $i < count($user_info); $i++) {
            $info["users"][$i]["id"] = $user_info[$i]["id"];
            $info["users"][$i]["username"] = $user_info[$i]["username"];
            $info["users"][$i]["me"] = 0;
            if ($_SESSION["id"] == $info["users"][$i]["id"]) {
                $info["users"][$i]["me"] = 1;
            }
        }
        echo json_encode($info);
    }
    public function get_tasks_due_nb()
    {
        $tasks = $this->model("tasks");
        $info = $tasks->get_tasks(1, 0, 0, 0, 0);
        if (1 <= count($info)) {
            echo json_encode(array(1));
            exit;
        }
        echo json_encode(array(0));
    }
    public function get_favnotes()
    {
        $tasks = $this->model("tasks");
        $fav = $tasks->get_favnotes($_SESSION["id"]);
        $data_array["data"] = array();
        for ($i = 0; $i < count($fav); $i++) {
            $tmp = array();
            array_push($tmp, $fav[$i]["id"]);
            array_push($tmp, "<span style='text-decoration:underline' id='idfav_" . $fav[$i]["id"] . "' onclick='use_fav(" . $fav[$i]["id"] . ")'>" . $fav[$i]["description"] . "</span>");
            array_push($tmp, "<i style=\"cursor:pointer;font-size:17px;\"  class=\"glyphicon glyphicon-trash red\" title=\"Delete\" onclick=\"delete_fav(" . $fav[$i]["id"] . ")\"></i>");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_tasks($action, $_daterange, $_from, $_to, $_status)
    {
        $tasks = $this->model("tasks");
        $user = $this->model("user");
        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        $from = filter_var($_from, FILTER_SANITIZE_NUMBER_INT);
        $to = filter_var($_to, FILTER_SANITIZE_NUMBER_INT);
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
        $date_range_tmp = NULL;
        $date_range[0] = NULL;
        $date_range[1] = NULL;
        if ($daterange == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" - ", $daterange);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
        $user_info = $user->getAllUsersEvenDeleted();
        $user_array = array();
        for ($i = 0; $i < count($user_info); $i++) {
            $user_array[$user_info[$i]["id"]] = $user_info[$i];
        }
        $data_array["data"] = array();
        $info = $tasks->get_tasks($action, $date_range, $from, $to, $status);
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $user_array[$info[$i]["created_by"]]["username"]);
            array_push($tmp, $user_array[$info[$i]["note_to"]]["username"]);
            $ddate = $info[$i]["due_date"];
            $ddate_ = explode(" ", $ddate);
            array_push($tmp, $ddate_[0]);
            array_push($tmp, $info[$i]["remind_before"] . " day(s)");
            $response_note = "";
            if (strlen($info[$i]["leaved_note"])) {
                $response_note = $info[$i]["leaved_note"];
            }
            array_push($tmp, $info[$i]["description"] . "<br/><span class='resp_n'>" . $response_note . "</span>");
            if ($info[$i]["status"] == 1) {
                array_push($tmp, "<b class='note_p'>Pending</b>");
            } else {
                array_push($tmp, "<b class='note_d'>Done</b>");
            }
            array_push($tmp, "");
            array_push($tmp, $info[$i]["status"]);
            if ($_SESSION["id"] == $info[$i]["created_by"]) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }
            if ($_SESSION["role"] == 1 && $info[$i]["note_to"] == $_SESSION["id"] || $_SESSION["cashbox_id"] == $info[$i]["set_done_shift_id"]) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }
            if ($_SESSION["id"] == $info[$i]["note_to"]) {
                array_push($tmp, 1);
            } else {
                array_push($tmp, 0);
            }
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function delete_task($_task_id)
    {
        $id = filter_var($_task_id, FILTER_SANITIZE_NUMBER_INT);
        $tasks = $this->model("tasks");
        $tasks->delete_task($id);
        $tasks_pending = $tasks->get_pending_task_nb();
        echo json_encode(array($tasks_pending[0]["nb"]));
    }
    public function delete_fav($_task_id)
    {
        $id = filter_var($_task_id, FILTER_SANITIZE_NUMBER_INT);
        $tasks = $this->model("tasks");
        $tasks->delete_fav($id);
        echo json_encode(array());
    }
    public function undo_status($_task_id)
    {
        $id = filter_var($_task_id, FILTER_SANITIZE_NUMBER_INT);
        $tasks = $this->model("tasks");
        $tasks->undo_status($id);
        echo json_encode(array());
    }
    public function add_new_ctask()
    {
        $tasks = $this->model("tasks");
        $info["task_due_date"] = filter_input(INPUT_POST, "task_due_date", self::conversion_php_version_filter());
        $info["task_description"] = filter_input(INPUT_POST, "task_description", self::conversion_php_version_filter());
        $info["task_bd"] = filter_input(INPUT_POST, "task_bd", FILTER_SANITIZE_NUMBER_INT);
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["fav"] = 0;
        if (isset($_POST["fav"])) {
            $info["fav"] = 1;
        }
        if ($info["id_to_edit"] == 0) {
            for ($i = 0; $i < count($_POST["select_to"]); $i++) {
                $info["note_to"] = $_POST["select_to"][$i];
                if (0 < $i) {
                    $info["fav"] = 0;
                }
                $last_insert_id = $tasks->add_new_task($info);
            }
        } else {
            $info["note_to"] = $_POST["select_to"][0];
            $tasks->update_task($info);
            $last_insert_id = $info["id_to_edit"];
        }
        echo json_encode(array($last_insert_id));
    }
    public function set_task_status($_id, $_status, $_note)
    {
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $status = filter_var($_status, FILTER_SANITIZE_NUMBER_INT);
        $note = filter_var($_note, self::conversion_php_version_filter());
        $tasks = $this->model("tasks");
        $tasks->set_task_status($id, $status, $note);
        $tasks_pending = $tasks->get_pending_task_nb();
        echo json_encode(array($tasks_pending[0]["nb"]));
    }
}

?>