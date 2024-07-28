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
class employeesModel
{
    public function getAllEmployees()
    {
        $query = "select * from employees where deleted_emp=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllEmployeesEvenDeleted()
    {
        $query = "select * from employees";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_employee_attendance($info)
    {
        $query = "insert into employees_attendance(employee_id,start_date_time,end_date_time,creation_date) values('" . $info["employee_id"] . "','" . $info["start_date"] . "','" . $info["end_date"] . "','" . my_sql::datetime_now() . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function update_employee_attendance($info)
    {
        $query = "update employees_attendance set start_date_time='" . $info["start_date"] . "',end_date_time='" . $info["end_date"] . "' where id=" . $info["id_to_edit"];
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function getAllEmployeesAttendance()
    {
        $query = "select ea.id as ea_id,e.first_name,e.last_name,ea.start_date_time,ea.end_date_time,ea.creation_date from employees_attendance ea,employees e where ea.employee_id=e.id and deleted=0 order by ea.creation_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_employee_attendance($id)
    {
        my_sql::query("update employees_attendance set deleted=1 where id=" . $id);
    }
    public function delete_employee($id)
    {
        $query = "update employees set deleted_emp=1 where id=" . $id;
        $result = my_sql::query($query);
        return $result;
    }
    public function get_employee($id)
    {
        $query = "select * from employees where id=" . $id . " and deleted_emp=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_employee_even_delete($id)
    {
        $query = "select * from employees where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_employee($info)
    {
        $query = "insert into employees(first_name,last_name,address,phone_number,start_date,middle_name,email,note,basic_salary,paycut_per_hour,overtime_per_hour,hours_per_day,also_customer_id) values('" . $info["first_name"] . "','" . $info["last_name"] . "','" . $info["address"] . "','" . $info["phone"] . "','" . $info["start_date"] . "','" . $info["middle_name"] . "','" . $info["email"] . "','" . $info["note"] . "'," . $info["basic_salary"] . "," . $info["paycut"] . "," . $info["overtime"] . "," . $info["hours_per_day"] . "," . $info["customer_emp_id"] . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function update_employee($info)
    {
        $query = "update employees set first_name='" . $info["first_name"] . "',last_name='" . $info["last_name"] . "',address='" . $info["address"] . "',phone_number='" . $info["phone"] . "',start_date='" . $info["start_date"] . "',middle_name='" . $info["middle_name"] . "',email='" . $info["email"] . "',note='" . $info["note"] . "',basic_salary=" . $info["basic_salary"] . ",paycut_per_hour=" . $info["paycut"] . ",overtime_per_hour=" . $info["overtime"] . ",hours_per_day=" . $info["hours_per_day"] . ",also_customer_id=" . $info["customer_emp_id"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function getAllCashboxEmployees($filters)
    {
        $datefilter = "";
        if (isset($filters["date_range"])) {
            $datefilter = " and date(cb.starting_cashbox_date)>='" . $filters["date_range"][0] . "' and date(cb.starting_cashbox_date)<='" . $filters["date_range"][1] . "' ";
        }
        $user_filter = "";
        if (isset($filters["username"]) && $filters["username"] != "") {
            $user_filter = " and u.id in (" . $filters["username"] . ")";
        }
        $query = "select *,TIMESTAMPDIFF(HOUR, cb.starting_cashbox_date,cb.ending_cashbox_date)  as working_hrs,cb.id as cashbox_id from cashbox as cb left join  users as u on cb.vendor_id=u.id where 1 " . $datefilter . " " . $user_filter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function search($search, $page, $perPage, $checkHasMore = false)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", " . $perPage;
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        $query = "SELECT " . $select . " FROM users where deleted=0 and username like \"%" . $search . "%\"  or id =\"" . $search . "\" " . $limiter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($checkHasMore) {
            return $page + 1 * $perPage < $result[0]["total_results"];
        }
        return $result;
    }
    public function get_total_working_hrs_paid_not_paid($filters, $is_paid)
    {
        $datefilter = "";
        if (isset($filters["date_range"])) {
            $datefilter = " and date(cb.starting_cashbox_date)>='" . $filters["date_range"][0] . "' and date(cb.starting_cashbox_date)<='" . $filters["date_range"][1] . "' ";
        }
        $user_filter = "";
        if (isset($filters["username"]) && $filters["username"] != "") {
            $user_filter = " and u.id in (" . $filters["username"] . ")";
        }
        $is_paid_filter = "";
        if ($is_paid != -1) {
            $is_paid_filter = " and cb.paid =" . $is_paid;
        }
        $query = "select COALESCE(SUM(TIMESTAMPDIFF(HOUR, cb.starting_cashbox_date,cb.ending_cashbox_date)),0)  as working_hrs  from cashbox as cb left join  users as u on cb.vendor_id=u.id where 1 " . $datefilter . " " . $user_filter . " " . $is_paid_filter;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["working_hrs"];
    }
    public function update_employee_cashbox_paid($info)
    {
        $query = "update cashbox set paid=" . $info["is_paid"] . ",paid_last_update='" . my_sql::datetime_now() . "'  where id=" . $info["id"];
        $result = my_sql::query($query);
        return $result;
    }
    public function add_cashbox_logs($info)
    {
        $query = "insert into cashbox_logs(created_by,related_to_cashbox_id,creation_date,description) " . "values('" . $info["created_by"] . "','" . $info["related_to_cashbox_id"] . "','" . my_sql::datetime_now() . "','" . $info["description"] . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }
}

?>