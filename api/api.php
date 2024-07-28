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
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
error_reporting(0);
require_once "../config/my_sql.php";
require_once "../config/define.php";
require_once "../application/core/lib/my_sql.php";
require_once "../application/mvc/models/quotationsModel.php";
$getParams = $_GET;
$getLog = "GET request at " . $dateTime . ": " . json_encode($getParams) . PHP_EOL;
$postParams = $_POST;
$postLog = "POST request at " . $dateTime . ": " . json_encode($postParams) . PHP_EOL;
$logEntry = $getLog . $postLog;
$log_id = write_logs($logEntry);
$settings = get_settings();
$hashkey = "";
if (isset($_GET["k"])) {
    $hashkey = $_GET["k"];
}
if (!hash_key_exist($hashkey) || !isset($_GET["k"])) {
    $response["message"] = "Unauthorized Key";
    set_failed($log_id);
    echo json_encode($response);
    exit;
}
$rinfo_ = get_wl_ips($hashkey);
$allowedIPs = $rinfo_["ips"];
$user_id = $rinfo_["user_id"];
$store_id = $rinfo_["store_id"];
$clientIP = $_SERVER["REMOTE_ADDR"];
if (!in_array($clientIP, $allowedIPs) && !in_array("all", $allowedIPs)) {
    $response["message"] = "Unauthorized IP address";
    echo json_encode($response);
    set_failed($log_id);
    exit;
}
$endpoint = "";
if (isset($_GET["endpoint"])) {
    $endpoint = $_GET["endpoint"];
}
$response = array("status" => "error", "message" => "Unauthorized", "data" => NULL);
switch ($endpoint) {
    case "items":
        $response["status"] = "success";
        $response["message"] = "Endpoint processed successfully";
        $response["data"] = json_encode(get_all_items());
        echo json_encode($response);
        break;
    case "add_quotation":
        $id = add_quotation();
        if (0 < $id) {
            $response["status"] = "success";
            $response["message"] = "Endpoint processed successfully";
            $response["data"] = array("qid" => $id);
        } else {
            $response["status"] = "failed";
            $response["message"] = "Please contact support";
            $response["data"] = array("qid" => 0);
        }
        echo json_encode($response);
        break;
    default:
        $response["message"] = "Invalid endpoint";
        $response["data"] = array();
        echo json_encode($response);
        return 1;
}
function add_quotation()
{
    global $settings;
    global $user_id;
    global $store_id;
    global $log_id;
    $foreign_client_id = 0;
    $client_id = 0;
    $data_items = json_decode($_POST["items"], true);
    if (count($data_items) == 0) {
        $response["status"] = "failed";
        $response["message"] = "Cannot create invoice without items";
        echo json_encode($response);
        set_failed($log_id);
        exit;
    }
    for ($i = 0; $i < count($data_items); $i++) {
        $data_items[$i]["pos_item_id"] = (int) filter_var($data_items[$i]["pos_item_id"], FILTER_SANITIZE_NUMBER_INT);
        $data_items[$i]["qty"] = (int) filter_var($data_items[$i]["qty"], FILTER_SANITIZE_NUMBER_INT);
        $data_items[$i]["selling_price"] = filter_var($data_items[$i]["selling_price"], FILTER_VALIDATE_FLOAT);
        $data_items[$i]["foreign_item_id"] = (int) filter_var($data_items[$i]["foreign_item_id"], FILTER_SANITIZE_NUMBER_INT);
    }
    for ($i = 0; $i < count($data_items); $i++) {
        $result = get_item_by_id($data_items[$i]["pos_item_id"]);
        if (count($result) == 0) {
            $response["status"] = "failed";
            $response["message"] = "POS Item ID:" . $data_items[$i]["pos_item_id"] . ", is not available in the inventory";
            echo json_encode($response);
            set_failed($log_id);
            exit;
        }
        $data_items[$i]["buying_cost"] = $result[0]["buying_cost"];
        $data_items[$i]["vat"] = 0;
        $data_items[$i]["vat_value"] = 0;
        $data_items[$i]["discount"] = 0;
        $data_items[$i]["final_price"] = $data_items[$i]["selling_price"] * $data_items[$i]["qty"];
        $data_items[$i]["final_cost"] = $result[0]["buying_cost"] * $data_items[$i]["qty"];
        $data_items[$i]["profit"] = $data_items[$i]["final_price"] - $data_items[$i]["final_cost"];
        if ($data_items[$i]["qty"] <= 0) {
            $response["status"] = "failed";
            $response["message"] = "POS Item ID:" . $data_items[$i]["pos_item_id"] . ", stock quantity cannot be zero or negative";
            echo json_encode($response);
            set_failed($log_id);
            exit;
        }
        if ($data_items[$i]["foreign_item_id"] <= 0) {
            $response["status"] = "failed";
            $response["message"] = "Foreign item ID cannot be zero or negative";
            echo json_encode($response);
            exit;
        }
    }
    $foreign_invoice_id = filter_var($_POST["invoice_id"], FILTER_SANITIZE_NUMBER_INT);
    if (!isset($foreign_invoice_id) || $foreign_invoice_id <= 0) {
        $response["status"] = "failed";
        $response["message"] = "Unknown invoice ID";
        echo json_encode($response);
        set_failed($log_id);
        exit;
    }
    $client_name = "";
    if (!isset($_POST["client_name"])) {
        $response["status"] = "failed";
        $response["message"] = "Unknown client name";
        echo json_encode($response);
        set_failed($log_id);
        exit;
    }
    if (0 < strlen($_POST["client_name"])) {
        $client_name = $_POST["client_name"];
        if (!isset($_POST["client_id"])) {
            $response["status"] = "failed";
            $response["message"] = "Unknown client ID";
            echo json_encode($response);
            set_failed($log_id);
            exit;
        }
        $foreign_client_id = filter_var($_POST["client_id"], FILTER_SANITIZE_NUMBER_INT);
        if ($foreign_client_id <= 0) {
            $response["status"] = "failed";
            $response["message"] = "Unknown client ID " . $client_id;
            echo json_encode($response);
            set_failed($log_id);
            exit;
        }
        $client_id = get_client_by_id($foreign_client_id, $client_name);
        $info = array();
        $info["created_by"] = $user_id;
        $info["store_id"] = 0;
        $info["vat"] = $settings["vat"];
        $info["client_id"] = $client_id;
        $info["client_name"] = $client_name;
        $info["foreign_id"] = $foreign_client_id;
        $info["store_id"] = $store_id;
        $info["submitted"] = 1;
        $info["quotation_type"] = 1;
        $info["foreign_invoice_id"] = $foreign_invoice_id;
        $info["data_items"] = $data_items;
        $quotation_id = create_quotation($info);
        $quotationsModel = new quotationsModel();
        $quotationsModel->calculate_total_profit_for_quotation($quotation_id);
        $quotationsModel->calculate_total_value($quotation_id);
        return $quotation_id;
    }
    $response["status"] = "failed";
    $response["message"] = "Client name is empty";
    echo json_encode($response);
    set_failed($log_id);
    exit;
}
function get_client_by_id($foreign_client_id, $client_name)
{
    $query = "select * from customers where foreign_id='" . $foreign_client_id . "' and deleted=0";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    if (0 < count($result)) {
        return $result[0]["id"];
    }
    $name = $client_name;
    $query_c = "insert into customers (name,foreign_id) values('" . $name . "'," . $foreign_client_id . ")";
    my_sql::query($query_c);
    $last_insert_id = my_sql::get_mysqli_insert_id();
    return $last_insert_id;
}
function create_quotation($info)
{
    my_sql::query("insert into quotations(creation_date,customer_id,store_id,created_by,sub_total,discount,vat,total,profit,submitted,quotation_type,foreign_invoice_id) values (now()," . $info["client_id"] . "," . $info["store_id"] . "," . $info["created_by"] . ",0,0," . $info["vat"] . ",0,0," . $info["submitted"] . "," . $info["quotation_type"] . "," . $info["foreign_invoice_id"] . ")");
    $id = my_sql::get_mysqli_insert_id();
    for ($i = 0; $i < count($info["data_items"]); $i++) {
        my_sql::query("insert into quotation_details(quotation_id,item_id,buying_cost,qty,selling_price,final_price,final_cost,profit,foreign_item_id) " . "values (" . $id . "," . $info["data_items"][$i]["pos_item_id"] . "," . $info["data_items"][$i]["buying_cost"] . "," . $info["data_items"][$i]["qty"] . "," . $info["data_items"][$i]["selling_price"] . "," . $info["data_items"][$i]["final_price"] . "," . $info["data_items"][$i]["final_cost"] . "," . $info["data_items"][$i]["profit"] . "," . $info["data_items"][$i]["foreign_item_id"] . ")");
    }
    return $id;
}
function get_wl_ips($hash)
{
    $return = array();
    $return["ips"] = array();
    $return["user_id"] = 0;
    $query = "select * from api_config where hashkey='" . $hash . "' and active=1";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    if (0 < count($result)) {
        $whitelist_array = explode(",", $result[0]["whitelist_ips"]);
        for ($i = 0; $i < count($whitelist_array); $i++) {
            array_push($return["ips"], $whitelist_array[$i]);
        }
        $return["user_id"] = $result[0]["user_id"];
        $return["store_id"] = 0;
        $query_user = "select * from users where id='" . $result[0]["user_id"] . "'";
        $result_user = my_sql::fetch_assoc(my_sql::query($query_user));
        if (0 < count($result_user)) {
            $return["store_id"] = $result_user[0]["store_id"];
        }
    }
    return $return;
}
function hash_key_exist($hash)
{
    $query = "select count(id) as num from api_config where hashkey='" . $hash . "' and active=1";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    if (0 < floatval($result[0]["num"])) {
        return true;
    }
    return false;
}
function get_all_items()
{
    $query = "select it.id,it.description,it.sku_code,CAST(si.quantity AS DECIMAL(10, 0))  as avqty  from items it left join store_items si on si.item_id=it.id where it.deleted=0";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    return $result;
}
function get_item_by_id($id)
{
    $query = "select id,buying_cost from items where id=" . $id . " and deleted=0";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    return $result;
}
function write_logs($data)
{
    my_sql::query("insert into api_logs(creation_date,all_data) values(now(),'" . $data . "')");
    $id = my_sql::get_mysqli_insert_id();
    return $id;
}
function set_failed($log_id)
{
    my_sql::query("update api_logs set failed=1 where id=" . $log_id);
    $id = my_sql::get_mysqli_insert_id();
    return $id;
}
function get_settings()
{
    $config = array();
    $query = "select * from settings";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    for ($i = 0; $i < count($result); $i++) {
        $config["" . $result[$i]["name"]] = $result[$i]["value"];
    }
    return $config;
}

?>