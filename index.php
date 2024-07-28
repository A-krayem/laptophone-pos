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
ini_set("session.gc_maxlifetime", 29030400);
ini_set("max_execution_time", 3200);
ini_set("memory_limit", -1);
error_reporting(0);
define("VERSION", "Live.1.8.2");
define("ENABLE_NEW_TEMPLATE", 0);
define("NEW_TEMPLATE_PATH", "application/mvc/views/newtemplate/");
if (defined("PHP_MAJOR_VERSION") && 6 <= PHP_MAJOR_VERSION) {
    require_once __DIR__ . "/vendor/autoload.php";
}
require_once "application/core/session/database.class.php";
require_once "application/core/session/mysql.sessions.php";
require_once "advanced_config.php";
require_once "config/my_sql.php";
require_once "config/define.php";
require_once "application/mvc/init.php";
$query = "select * from settings";
$result = my_sql::fetch_assoc(my_sql::query($query));
$settings = array();
for ($i = 0; $i < count($result); $i++) {
    $settings["" . $result[$i]["name"]] = $result[$i]["value"];
}
if (isset($settings["time_zone"])) {
    date_default_timezone_set($settings["time_zone"]);
} else {
    date_default_timezone_set("Asia/Beirut");
}
$whitelist = array("127.0.0.1", "::1");
if (in_array(getUserIP(), $whitelist)) {
}
session_start();
$app = new App();
function write_logs($logEntry)
{
    $data = array();
    $data["GET"] = $_GET;
    $data["POST"] = $_POST;
    $data["COOKIES"] = $_COOKIE;
    $data["SESSION"] = $_SESSION;
    $data["SERVER"] = $_SERVER;
    $query = "insert into log_requests(creation_date,data_request) values(now(),'" . json_encode($data) . "')";
    my_sql::query($query);
    my_sql::query("DELETE FROM log_requests WHERE creation_date < DATE_SUB(NOW(), INTERVAL 3 MONTH)");
}
function getUserIP()
{
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER["REMOTE_ADDR"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER["HTTP_CLIENT_IP"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client = $_SERVER["HTTP_CLIENT_IP"];
    $forward = $_SERVER["HTTP_X_FORWARDED_FOR"];
    $remote = $_SERVER["REMOTE_ADDR"];
    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } else {
        if (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
    }
    return $ip;
}

?>