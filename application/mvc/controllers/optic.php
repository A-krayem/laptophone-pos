<?php class optic extends Controller
{

    public $licenseExpired = false;
    public $settings_info = null;

    public function __construct()
    {
        $this->checkAuth();

    }

    public function get_optic_details($_client_id, $_optic_type)
    {

        $optic_model = $this->model("optic");

        $optic_type = filter_var($_optic_type, self::conversion_php_version_filter());
        $client_id = filter_var($_client_id, self::conversion_php_version_filter());


        $filters = array();
        $filters["optic_type"] = $optic_type;
        $filters["client_id"] = $client_id;
        if ($client_id == "") {
            $filters["client_id"] = -1;
        }
        $optic_details = $optic_model->get_client_optic_details_by_type($filters);

        $data_array["data"] = array();
        for ($i = 0; $i < count($optic_details); $i++) {
            $tmp = array();

            $dt = explode(" ", $optic_details[$i]["date"]);

            array_push($tmp, $optic_details[$i]["id"]);
            array_push($tmp, $dt[0]);
            array_push($tmp, $optic_details[$i]["r_eye_sph"]);
            array_push($tmp, $optic_details[$i]["r_eye_cyl"]);
            array_push($tmp, $optic_details[$i]["r_eye_axis"]);
            array_push($tmp, $optic_details[$i]["r_eye_prism"]);
            array_push($tmp, $optic_details[$i]["l_eye_sph"]);
            array_push($tmp, $optic_details[$i]["l_eye_cyl"]);
            array_push($tmp, $optic_details[$i]["l_eye_axis"]);
            array_push($tmp, $optic_details[$i]["l_eye_prism"]);

            array_push($tmp, $optic_details[$i]["doctor"]);
            array_push($tmp, $optic_details[$i]["creation_date"]);
            array_push($tmp, $optic_details[$i]["deleted"]);

            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }

        echo json_encode($data_array);
    }




    public function search_for_client($_search, $_page, $_client_id)
    {
        $search = filter_var($_search, self::conversion_php_version_filter());
        $page = filter_var($_page, FILTER_SANITIZE_NUMBER_INT);
        $client_id = filter_var($_client_id, FILTER_SANITIZE_NUMBER_INT);

        $optic = $this->model("optic");
        $results = $optic->search($search, $page, $client_id, 20);
        $return = array();

        $return["results"] = array();
        $index = 0;
        foreach ($results as $result) {

            $return["results"][$index] = array("id" => $result["id"], "text" => $result["id"] . " | " . $result["name"] . " " . $result["middle_name"] . " " . $result["last_name"] . " - Phone # " . $result["phone"]);
            $index++;
        }


        if (count($results) == 20)
            $return["pagination"]["more"] = $optic->search($search, $page, $client_id, 20, true);
        else
            $return["pagination"]["more"] = false;
        echo json_encode($return);
    }

    public function add_optic_details()
    {
        $optic = $this->model("optic");

        if (isset($_POST["optic_id"])) { // update 
            $mysql_array = array();
            foreach ($_POST as $key => $val) {
                if (explode("optic_", $key)[1] == "date") {
                    $val = date('Y-m-d', strtotime($val));
                }
                $mysql_array[explode("optic_", $key)[1]] = $val;

            }
            $result = $optic->update_optic_detail_info($mysql_array);
            if ($result > 0) {
                $optic_data = array();
                $optic_data["old"] = $optic->get_optic_details_by_id($_POST["optic_id"]);
                $optic_data["new"] = json_encode($mysql_array);
                $logs_info = array();
                $logs_info["created_by"] = $_SESSION['id'];
                $logs_info["related_to_optic_id"] = $_POST["optic_id"];
                $logs_info["other_info"] = json_encode($optic_data);
                $logs_info["description"] = "Update Optic " . ucfirst($_POST["optic_type"]) . " with ID (" . $_POST["optic_id"] . ").";
                $optic->add_optic_logs($logs_info);
            }
            echo json_encode($result);

        } else {

            $mysql_array = array();
            $mysql_array["col"] = array();
            $mysql_array["val"] = array();

            $optic_data = array();
            foreach ($_POST as $key => $val) {
                $val = filter_var($val, self::conversion_php_version_filter());

                if (explode("optic_", $key)[1] == "date") {
                    $val = date('Y-m-d', strtotime($val));
                    array_push($mysql_array["col"], explode("optic_", $key)[1]);
                    array_push($mysql_array["val"], $val);
                } else {
                    array_push($mysql_array["col"], explode("optic_", $key)[1]);
                    array_push($mysql_array["val"], $val);
                }

                $optic_data[explode("optic_", $key)[1]] = $val;

            }
            $result = $optic->save_optic_detail_info($mysql_array);
            if ($result > 0) {


                $logs_info = array();
                $logs_info["created_by"] = $_SESSION['id'];
                $logs_info["related_to_optic_id"] = $result;
                $logs_info["other_info"] = json_encode($optic_data);
                $logs_info["description"] = "Add New Optic " . ucfirst($_POST["optic_type"]) . " with ID (" . $result . ").";
                $optic->add_optic_logs($logs_info);
            }
            echo json_encode($result);

        }

    }

    public function delete_optic_detail($optic_id)
    {
        $optic = $this->model("optic");
        $result = $optic->delete_optic_detail_by_id($optic_id);
        if ($result) {
            $optic_info = $optic->get_optic_details_by_id($optic_id);
            $logs_info = array();
            $logs_info["created_by"] = $_SESSION['id'];
            $logs_info["related_to_optic_id"] = $optic_id;
            $logs_info["description"] = "Delete Optic " . ucfirst($optic_info[0]["type"]) . " with ID (" . $optic_id . ").";
            $optic->add_optic_logs($logs_info);
        }
        echo json_encode($result);
    }

    public function get_optic_details_by_id($optic_id)
    {
        $optic = $this->model("optic");
        $result = $optic->get_optic_details_by_id($optic_id);

        $result[0]["date"] = date('m/d/Y', strtotime($result[0]["date"]));

        echo json_encode($result);

    }
    public function get_optic_clients_info_by_filters($_first_name, $_middle_name, $_last_name, $_phone_nb, $_doctor)
    {
        $optic_model = $this->model("optic");

        $first_name = filter_var($_first_name, self::conversion_php_version_filter());
        $middle_name = filter_var($_middle_name, self::conversion_php_version_filter());
        $last_name = filter_var($_last_name, self::conversion_php_version_filter());
        $phone_nb = filter_var($_phone_nb, self::conversion_php_version_filter());
        $doctor = filter_var($_doctor, self::conversion_php_version_filter());

        $filters = array();
        $filters["name"] = $first_name;
        $filters["middle_name"] = $middle_name;
        $filters["last_name"] = $last_name;
        $filters["phone"] = $phone_nb;
        $filters["doctor"] = $doctor;
        $op_client_info = $optic_model->search_for_clients_by_filters($filters);
        $data_array["data"] = array();
        for ($i = 0; $i < count($op_client_info); $i++) {
            $tmp = array();

            array_push($tmp, $op_client_info[$i]["id"]);
            array_push($tmp, $op_client_info[$i]["name"]);
            array_push($tmp, $op_client_info[$i]["middle_name"]);
            array_push($tmp, $op_client_info[$i]["last_name"]);
            array_push($tmp, $op_client_info[$i]["phone"]);
            array_push($tmp, $op_client_info[$i]["pd"]);
            array_push($tmp, $op_client_info[$i]["doctor"]);
            array_push($tmp, $op_client_info[$i]["Note"]);
            array_push($tmp, $op_client_info[$i]["Address"]);


            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }

        echo json_encode($data_array);
    }

}