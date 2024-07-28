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
class my_sql
{
    private static $instance = NULL;
    private static $instance_local = NULL;
    private function __construct()
    {
    }
    public static function datetime_now()
    {
        return date("Y-m-d H:i:s");
    }
    public static function time_now()
    {
        return date("H:i:s");
    }
    private function __clone()
    {
    }
    public static function connection()
    {
        if (!isset($instance)) {
            self::$instance = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
            if (!self::$instance) {
                exit("Could not connect: " . mysqli_error());
            }
        }
        return self::$instance;
    }
    public static function pingServer($server)
    {
        $starttime = microtime(true);
        $file = fsockopen($server, 80, $errno, $errstr, 1);
        $stoptime = microtime(true);
        $status = 0;
        if (!$file) {
            $status = -1;
        } else {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        return $status;
    }
    public static function custom_connection($server, $username, $password, $database)
    {
        if (self::pingServer($server) == -1) {
            return false;
        }
        $result_connection = mysqli_connect($server, $username, $password, $database);
        if (!$result_connection) {
            return false;
        }
        return $result_connection;
    }
    public static function custom_connection_query($query, $connection)
    {
        mysqli_query($connection, "SET NAMES utf8");
        mysqli_query($connection, "SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $result = mysqli_query($connection, $query);
        return $result;
    }
    public static function connection_local()
    {
        if (!isset($instance_local)) {
            self::$instance_local = mysqli_connect(SERVER_LOCAL, USERNAME_LOCAL, PASSWORD_LOCAL, DATABASE_LOCAL);
            if (!self::$instance_local) {
                exit("Could not connect: " . mysqli_error());
            }
        }
        return self::$instance_local;
    }
    public static function global_query_sync($query, $target_store = 0)
    {
        if ($_SESSION["centralize"] == 1 && WAREHOUSE_CONNECTED == 0) {
            return mysqli_query(self::connection(), "insert into queries(qry,transaction_date,target_store) values(\"" . $query . "\",now()," . $target_store . ")");
        }
    }
    public static function query_local($query)
    {
        mysqli_query(self::connection_local(), "SET NAMES utf8");
        mysqli_query(self::connection_local(), "SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $result = mysqli_query(self::connection_local(), $query);
        return $result;
    }
    public static function query($query)
    {
        if (QUERY_LOGS_ENABLE && !file_exists(QUERY_LOGS_PATH)) {
            mkdir(QUERY_LOGS_PATH, 511, true);
        }
        $msc = microtime(true);
        mysqli_query(self::connection(), "SET NAMES utf8");
        mysqli_query(self::connection(), "SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $msc = microtime(true) - $msc;
        if (QUERY_LOGS_ENABLE) {
        }
        $result = mysqli_query(self::connection(), $query);
        if (!$result) {
            $file = QUERY_LOGS_PATH . "/failed_queries-" . date("Y-m-d") . ".txt";
            file_put_contents($file, "failed_queries: " . $query . " ## " . date("h:i:sa") . " ## execution time:" . $msc . " \n", FILE_APPEND | LOCK_EX);
        }
        return $result;
    }
    public static function fetch_assoc($result)
    {
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
        return $array;
    }
    public static function get_mysqli_insert_id()
    {
        return mysqli_insert_id(self::connection());
    }
    public static function get_mysqli_rows_num()
    {
        return mysqli_affected_rows(self::connection());
    }
    public static function get_mysqli_rows_num_remote($cnx)
    {
        return mysqli_affected_rows($cnx);
    }
}

?>