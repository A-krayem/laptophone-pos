<?php

class opticModel
{

    public function get_client_optic_details_by_type($filters)
    {
        $client_filter = "";
        if ((isset($filters["client_id"])) && ($filters["client_id"] != "")) {
            $client_filter = " and client_id = " . $filters["client_id"];
        }

        $optic_type_filter = "";
        if ((isset($filters["optic_type"])) && ($filters["optic_type"] != "")) {
            $optic_type_filter = " and type = '" . $filters["optic_type"] . "'";
        }


        $query = "select * from optic_details where deleted =0  " . $client_filter . " " . $optic_type_filter . " order by id desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }


    public function search($search, $page, $client_id, $perPage, $checkHasMore = false)
    {
        $page = $page == 0 ? 1 : $page;
        $page = $checkHasMore ? $page : $page - 1;
        $limiter = $checkHasMore ? "" : "limit " . $page * $perPage . ", $perPage";
        $select = $checkHasMore ? "count(*) as total_results" : "*";
        if ($client_id == 0) {
            $query = "SELECT $select FROM customers where concat(name,\" \",middle_name,\" \",last_name) like \"%$search%\" or concat(name,\" \",last_name) like \"%$search%\" or name like \"%$search%\"  or middle_name like \"%$search%\"  or last_name like \"%$search%\"  or phone like \"%$search%\"  or id =\"$search\"   $limiter";
        } else {
            $query = "SELECT $select FROM customers where id=$client_id    $limiter";
        }

        $result = my_sql::fetch_assoc(my_sql::query($query));

        if ($checkHasMore)
            return $result[0]["total_results"] > $page + 1 * $perPage;
        return $result;
    }

    public function save_optic_detail_info($info)
    {
        $query = "insert into optic_details(" . implode(",", $info["col"]) . ",creation_date) values('" . implode("','", $info["val"]) . "','" . my_sql::datetime_now() . "')";

        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        return $last_id;
    }

    public function get_optic_details_by_id($id)
    {
        $query = "select * from optic_details where id= " . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_optic_detail_by_id($optic_id)
    {
        $query = "update  optic_details set deleted=1 where id =" . $optic_id;
        $result = my_sql::query($query);
        return $result;
    }


    public function add_optic_logs($info)
    {
        $query = "insert into optic_logs(created_by,related_to_optic_id,description,creation_date)  values('" . $info["created_by"] . "','" . $info["related_to_optic_id"] . "','" . $info["description"] . "','" . my_sql::datetime_now() . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        if (isset($info["other_info"])) {
            my_sql::query("update optic_logs set other_info='" . $info["other_info"] . "' where id=" . $last_id);
        }
        return $last_id;
    }


    public function update_optic_detail_info($info)
    {
        $query = "update  optic_details set client_id='" . $info["client_id"] . "',date='" . $info["date"] . "',doctor='" . $info["doctor"] . "',l_eye_axis='" . $info["l_eye_axis"] . "' ,l_eye_cyl='" . $info["l_eye_cyl"] . "', l_eye_prism='" . $info["l_eye_prism"] . "', l_eye_sph='" . $info["l_eye_sph"] . "',r_eye_axis='" . $info["r_eye_axis"] . "' ,r_eye_cyl='" . $info["r_eye_cyl"] . "',r_eye_prism='" . $info["r_eye_prism"] . "',r_eye_sph='" . $info["r_eye_sph"] . "',type='" . $info["type"] . "' where id=" . $info["id"];
        $result = my_sql::query($query);
        if ($result) {
            return 1;
        } else {
            return 0;

        }
    }


    public function search_for_clients_by_filters($available_filters)
    {

        $limiter = "limit 250";

        $search_filters = [];

        foreach ($available_filters as $filter_name => $filter_val) {
            
            if (!empty($available_filters[$filter_name])) {
                $search_filters[] = "$filter_name LIKE  \"%". $available_filters[$filter_name] ."%\"";
            }
        }
        if (!empty($search_filters)) {
            $where_clause = " " . implode(" AND ", $search_filters);
            $query = "SELECT * FROM customers where deleted=0 and " . $where_clause . " " . $limiter;
           $result = my_sql::fetch_assoc(my_sql::query($query));
            return $result;
        } else {
            return [];
        }

    }

}