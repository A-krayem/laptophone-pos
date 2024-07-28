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
class employees extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function _default()
    {
        self::giveAccessTo();
        $this->view("employees");
    }
    public function employees_attendance()
    {
        self::giveAccessTo();
        $this->view("employees_attendance");
    }
    public function add_new_employee_attendance()
    {
        self::giveAccessTo();
        $employees = $this->model("employees");
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["employee_id"] = filter_input(INPUT_POST, "employee_id", FILTER_SANITIZE_NUMBER_INT);
        $info["start_date"] = filter_input(INPUT_POST, "start_date", self::conversion_php_version_filter());
        $info["end_date"] = filter_input(INPUT_POST, "end_date", self::conversion_php_version_filter());
        if ($info["id_to_edit"] == 0) {
            $info_return = $employees->add_new_employee_attendance($info);
        } else {
            $employees->update_employee_attendance($info);
        }
        $return = array();
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        echo json_encode($return);
    }
    public function delete_employee_attendance($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $employees = $this->model("employees");
        $employees->delete_employee_attendance($id);
        echo json_encode(array());
    }
    public function getAllEmployeesAttendance()
    {
        self::giveAccessTo();
        $employees = $this->model("employees");
        $employees_attendance_info = $employees->getAllEmployeesAttendance();
        $data_array["data"] = array();
        for ($i = 0; $i < count($employees_attendance_info); $i++) {
            $tmp = array();
            array_push($tmp, $employees_attendance_info[$i]["ea_id"]);
            array_push($tmp, $employees_attendance_info[$i]["first_name"] . " " . $employees_attendance_info[$i]["last_name"]);
            array_push($tmp, $employees_attendance_info[$i]["start_date_time"]);
            array_push($tmp, $employees_attendance_info[$i]["end_date_time"]);
            $starttimestamp = strtotime($employees_attendance_info[$i]["start_date_time"]);
            $endtimestamp = strtotime($employees_attendance_info[$i]["end_date_time"]);
            $hours = (int) (abs($endtimestamp - $starttimestamp) / 3600);
            $seconds = number_format((int) (abs($endtimestamp - $starttimestamp) % 3600) / 60, 0);
            array_push($tmp, $hours . " Hour(s) and " . $seconds . " Minute(s)");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function system_users()
    {
        self::giveAccessTo();
        $user = $this->model("user");
        $data = array();
        $data["max_users"] = $this->settings_info["max_users"];
        $data["enable_new_multibranches"] = 0;
        if (isset($this->settings_info["enable_new_multibranches"])) {
            $data["enable_new_multibranches"] = $this->settings_info["enable_new_multibranches"];
        }
        $data["current_users"] = $user->get_current_num_users();
        $this->view("system_users", $data);
    }
    public function delete_user($id_)
    {
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $user = $this->model("user");
        $return = array();
        $return["status"] = $user->delete_user($id);
        echo json_encode($return);
    }
    public function delete_employee($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $employees = $this->model("employees");
        $return = array();
        $return["status"] = $employees->delete_employee($id);
        echo json_encode($return);
    }
    public function get_user($id_)
    {
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $info["id"] = $id;
        $user = $this->model("user");
        $info = $user->get_user($info);
        $info[0]["password"] = "";
        $info[0]["branches"] = array();
        if (isset($this->settings_info["enable_new_multibranches"])) {
            $branchModel = $this->model("branch");
            $branches = $branchModel->get_branches();
            for ($i = 0; $i < count($branches); $i++) {
                $info[0]["branches"][$i]["id"] = $branches[$i]["id"];
                $info[0]["branches"][$i]["branch_name"] = $branches[$i]["branch_name"];
                $info[0]["branches"][$i]["location_name"] = $branches[$i]["location_name"];
            }
        }
        echo json_encode($info);
    }
    public function add_new_user()
    {
        $user = $this->model("user");
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        if ($this->settings_info["max_users"] <= $user->get_current_num_users() && $this->settings_info["max_users"] != -1 && $info["id_to_edit"] == 0) {
            exit;
        }
        $info["username"] = filter_input(INPUT_POST, "username", self::conversion_php_version_filter());
        $info["password"] = filter_input(INPUT_POST, "password", self::conversion_php_version_filter());
        $info["user_type"] = filter_input(INPUT_POST, "user_type", FILTER_SANITIZE_NUMBER_INT);
        $info["user_id"] = filter_input(INPUT_POST, "user_id", self::conversion_php_version_filter());
        $info["branches_ids"] = array();
        if (isset($_POST["branches_ids"])) {
            $branches_ids = $_POST["branches_ids"];
            for ($i = 0; $i < count($branches_ids); $i++) {
                array_push($info["branches_ids"], (int) $branches_ids[$i]);
            }
        }
        $info["commission"] = filter_input(INPUT_POST, "commission", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!isset($info["commission"]) || strlen($info["commission"]) == 0 || $info["commission"] == "") {
            $info["commission"] = 0;
        }
        $info["user_critical"] = filter_input(INPUT_POST, "user_critical", FILTER_SANITIZE_NUMBER_INT);
        $info["operator_is_admin"] = filter_input(INPUT_POST, "operator_is_admin", FILTER_SANITIZE_NUMBER_INT);
        if (isset($info["operator_is_admin"])) {
            $info["operator_is_admin"] = 1;
        } else {
            $info["operator_is_admin"] = 0;
        }
        $global_logs = $this->model("global_logs");
        $info_return = NULL;
        if ($info["id_to_edit"] == 0) {
            $info_return = $user->add_new_user($info);
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["related_to_item_id"] = 0;
            $logs_info["description"] = "New user (" . $info["username"] . "-" . $info_return . ") has been created";
            $logs_info["log_type"] = 3;
            $logs_info["other_info"] = json_encode($info);
            $global_logs->add_global_log($logs_info);
        } else {
            $logs_info = array();
            $logs_info["operator_id"] = $_SESSION["id"];
            $logs_info["related_to_item_id"] = 0;
            $logs_info["description"] = "User (" . $info["id_to_edit"] . ") has been updated";
            $logs_info["log_type"] = 3;
            $logs_info["other_info"] = json_encode($info);
            $global_logs->add_global_log($logs_info);
            $info_return = $user->update_user($info);
        }
        $return = array();
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        echo json_encode($return);
    }
    public function get_employee($id_)
    {
        self::giveAccessTo();
        $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
        $employees = $this->model("employees");
        $info = $employees->get_employee($id);
        $info[0]["typeahead_cname"] = "";
        if (0 < $info[0]["also_customer_id"]) {
            $customers = $this->model("customers");
            $customers_info = $customers->get_customers_typeahead_for_edit($info[0]["also_customer_id"]);
            $info[0]["typeahead_cname"] = $customers_info[0]["name"];
        }
        echo json_encode($info);
    }
    public function add_new_employee()
    {
        self::giveAccessTo();
        $info["id_to_edit"] = filter_input(INPUT_POST, "id_to_edit", FILTER_SANITIZE_NUMBER_INT);
        $info["first_name"] = filter_input(INPUT_POST, "first_name", self::conversion_php_version_filter());
        $info["last_name"] = filter_input(INPUT_POST, "last_name", self::conversion_php_version_filter());
        $info["address"] = filter_input(INPUT_POST, "address", self::conversion_php_version_filter());
        $info["phone"] = filter_input(INPUT_POST, "phone", self::conversion_php_version_filter());
        $info["start_date"] = filter_input(INPUT_POST, "start_date", self::conversion_php_version_filter());
        $info["middle_name"] = filter_input(INPUT_POST, "middle_name", self::conversion_php_version_filter());
        $info["email"] = filter_input(INPUT_POST, "email", self::conversion_php_version_filter());
        $info["note"] = filter_input(INPUT_POST, "note", self::conversion_php_version_filter());
        $info["customer_emp_id"] = 0;
        if (isset($_POST["customer_emp_id"])) {
            $info["customer_emp_id"] = filter_input(INPUT_POST, "customer_emp_id", FILTER_SANITIZE_NUMBER_INT);
        }
        $info["hours_per_day"] = filter_input(INPUT_POST, "hours_per_day", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["basic_salary"] = filter_input(INPUT_POST, "basic_salary", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["paycut"] = filter_input(INPUT_POST, "paycut", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["overtime"] = filter_input(INPUT_POST, "overtime", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $info["start_date"] = date("Y-m-d", strtotime($info["start_date"]));
        $employees = $this->model("employees");
        $info_return = NULL;
        if ($info["id_to_edit"] == 0) {
            $info_return = $employees->add_new_employee($info);
        } else {
            $employees->update_employee($info);
        }
        $return = array();
        if (0 < $info["id_to_edit"]) {
            $return["id"] = $info["id_to_edit"];
        } else {
            $return["id"] = $info_return;
        }
        echo json_encode($return);
    }
    public function getAllUsers()
    {
        self::giveAccessTo();
        $user = $this->model("user");
        $employees = $this->model("employees");
        $employee_info = array();
        $employee_data = $employees->getAllEmployees();
        for ($i = 0; $i < count($employee_data); $i++) {
            $employee_info[$employee_data[$i]["id"]] = $employee_data[$i];
        }
        $users_info = $user->getAllUsers();
        $data_array["data"] = array();
        for ($i = 0; $i < count($users_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_sysuser($users_info[$i]["id"]));
            array_push($tmp, $users_info[$i]["username"]);
            if (isset($employee_info[$users_info[$i]["name"]])) {
                array_push($tmp, $employee_info[$users_info[$i]["name"]]["first_name"] . " " . $employee_info[$users_info[$i]["name"]]["middle_name"] . " " . $employee_info[$users_info[$i]["name"]]["last_name"]);
            } else {
                array_push($tmp, $users_info[$i]["name"]);
            }
            array_push($tmp, "******");
            array_push($tmp, number_format($users_info[$i]["commission"], 2));
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllEmployees()
    {
        self::giveAccessTo();
        $employees = $this->model("employees");
        $employees_info = $employees->getAllEmployees();
        $data_array["data"] = array();
        for ($i = 0; $i < count($employees_info); $i++) {
            $tmp = array();
            array_push($tmp, self::idFormat_employee($employees_info[$i]["id"]));
            array_push($tmp, $employees_info[$i]["first_name"]);
            array_push($tmp, $employees_info[$i]["middle_name"]);
            array_push($tmp, $employees_info[$i]["last_name"]);
            array_push($tmp, $employees_info[$i]["email"]);
            array_push($tmp, $employees_info[$i]["address"]);
            array_push($tmp, $employees_info[$i]["phone_number"]);
            array_push($tmp, self::date_format_custom($employees_info[$i]["start_date"]));
            array_push($tmp, $employees_info[$i]["note"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function getAllEmployeesDetails()
    {
        $employees = $this->model("employees");
        $employees_info["emp"] = $employees->getAllEmployees();
        $employees_info["branches"] = array();
        if (isset($this->settings_info["enable_new_multibranches"])) {
            $branchModel = $this->model("branch");
            $branches = $branchModel->get_branches();
            for ($i = 0; $i < count($branches); $i++) {
                $employees_info["branches"][$i]["id"] = $branches[$i]["id"];
                $employees_info["branches"][$i]["branch_name"] = $branches[$i]["branch_name"];
                $employees_info["branches"][$i]["location_name"] = $branches[$i]["location_name"];
            }
        }
        echo json_encode($employees_info);
    }
    public function getAllEmployeesDeliveryRole()
    {
        $user = $this->model("user");
        $user_info = $user->getAllEmployeesDeliveryRole();
        echo json_encode($user_info);
    }
    public function getAllEmployeesCashbox($username, $_date)
    {
        $employees = $this->model("employees");
        $date = filter_var($_date, self::conversion_php_version_filter());
        if ($date == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $filters = array();
        $filters["date_range"] = $date_range;
        $filters["username"] = $username;
        $all_cashbox = $employees->getAllCashboxEmployees($filters);
        $data_array["data"] = array();
        for ($i = 0; $i < count($all_cashbox); $i++) {
            $tmp = array();
            array_push($tmp, $all_cashbox[$i]["cashbox_id"]);
            array_push($tmp, $all_cashbox[$i]["vendor_id"]);
            array_push($tmp, $all_cashbox[$i]["username"]);
            array_push($tmp, self::date_time_format_custom($all_cashbox[$i]["starting_cashbox_date"]));
            array_push($tmp, self::date_time_format_custom($all_cashbox[$i]["ending_cashbox_date"]));
            array_push($tmp, $all_cashbox[$i]["working_hrs"]);
            array_push($tmp, $all_cashbox[$i]["paid"] == 0 ? "No" : "Yes");
            array_push($tmp, $all_cashbox[$i]["paid"]);
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function search_employee($_search, $_page)
    {
        $search = filter_var($_search, self::conversion_php_version_filter());
        $page = filter_var($_page, FILTER_SANITIZE_NUMBER_INT);
        $employees = $this->model("employees");
        $results = $employees->search($search, $page, 20);
        $return = array();
        $return["results"] = array();
        $index = 0;
        foreach ($results as $result) {
            $return["results"][$index] = array("id" => $result["id"], "text" => $result["username"] . " - " . $result["id"]);
            $index++;
        }
        if (count($results) == 20) {
            $return["pagination"]["more"] = $employees->search($search, $page, 20, true);
        } else {
            $return["pagination"]["more"] = false;
        }
        echo json_encode($return);
    }
    public function getEmployeesCashbox_overview($username, $_date)
    {
        $employees = $this->model("employees");
        $date = filter_var($_date, self::conversion_php_version_filter());
        if ($date == "today") {
            $date_range[0] = date("Y-m-1");
            $date_range[1] = date("Y-m-d");
        } else {
            $date_range_tmp = explode(" ", $date);
            $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[2])));
        }
        $filters = array();
        $filters["date_range"] = $date_range;
        $filters["username"] = $username;
        $filters["username"] = $username;
        $return = array();
        $return["total_working_hrs"] = $employees->get_total_working_hrs_paid_not_paid($filters, -1);
        $return["total_paid_hrs"] = $employees->get_total_working_hrs_paid_not_paid($filters, 1);
        $return["total_unpaid_hrs"] = $employees->get_total_working_hrs_paid_not_paid($filters, 0);
        echo json_encode($return);
    }
    public function update_cashbox_paid($cashbox_id, $is_paid)
    {
        $employees = $this->model("employees");
        $info = array();
        $info["is_paid"] = $is_paid;
        $info["id"] = $cashbox_id;
        $result = $employees->update_employee_cashbox_paid($info);
        if ($result) {
            $logs_info = array();
            $logs_info["created_by"] = $_SESSION["id"];
            $logs_info["related_to_cashbox_id"] = $cashbox_id;
            $logs_info["description"] = "Cashbox changes from " . ($is_paid == 1 ? "(Unpaid to Paid)." : "(Paid to Unpaid).");
            $employees->add_cashbox_logs($logs_info);
        }
        echo json_encode($result);
    }
}

?>