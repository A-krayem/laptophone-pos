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
class syncModel
{
    public function pingServer($server)
    {
        return 1;
    }
    public function transfers_logs($msg)
    {
        $main_root = $_SERVER["DOCUMENT_ROOT"] . dirname($_SERVER["PHP_SELF"]);
        if (!file_exists($main_root . "/transfers_logs")) {
            mkdir($main_root . "/transfers_logs", 511, true);
        }
        $file = $main_root . "/transfers_logs" . "/log-" . date("Y-m-d") . ".txt";
        file_put_contents($file, "\n" . date("h:i:s A") . ":\n" . $msg, FILE_APPEND | LOCK_EX);
    }
    public function check_id_sync_pending($store_id)
    {
        $q = "select * from store where id=" . $store_id;
        $r = my_sql::fetch_assoc(my_sql::query($q));
        if ($r[0]["primary_db"] == 0) {
            $query_data = "select count(id) as num from queries where (target_store=" . $store_id . " or target_store=0) and id not in (select qry_id from queries_synced where store_id=" . $store_id . ") order by id asc";
            $result_data = my_sql::fetch_assoc(my_sql::query($query_data));
            if (0 < $result_data[0]["num"]) {
                return $result_data[0]["num"];
            }
        }
        $query_transfer = "select count(id) as num from transfers where to_store_id=" . $store_id . " and synced_destination=0 and submit_transfer=1 and deleted=0 and id in(select transfer_id from transfers_details where deleted=0)";
        $result_transfer = my_sql::fetch_assoc(my_sql::query($query_transfer));
        if (0 < $result_transfer[0]["num"]) {
            return $result_transfer[0]["num"];
        }
        $query_transfer = "select count(id) as num from transfers where from_store_id=" . $store_id . " and synced_source=0 and submit_transfer=1 and deleted=0 and id in(select transfer_id from transfers_details where deleted=0)";
        $result_transfer = my_sql::fetch_assoc(my_sql::query($query_transfer));
        if (0 < $result_transfer[0]["num"]) {
            return $result_transfer[0]["num"];
        }
        return 0;
    }
    public function sync_item_with_store($connection_db, $store_id)
    {
        $query = "select id from items where id not in (select item_id from store_items)";
        $result = my_sql::fetch_assoc(mysqli_query($connection_db, $query));
        if (0 < count($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $qry = "insert into store_items(store_id,item_id,quantity) values(" . $store_id . "," . $result[$i]["id"] . ",0)";
                mysqli_query($connection_db, $qry);
            }
        }
    }
    public function getQtyOfItem($item_id, $connection_db)
    {
        $query = "select quantity from store_items where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(mysqli_query($connection_db, $query));
        return $result;
    }
    public function idFormat_transfers($id)
    {
        return "TRANS-" . sprintf("%07s", $id);
    }
    public function sync_Transfers($store_id)
    {
        $query_primary = "select * from store where primary_db=1";
        $result_primary = my_sql::fetch_assoc(my_sql::query($query_primary));
        $primary_host = $result_primary[0]["ip_address"];
        $primary_username = $result_primary[0]["username"];
        $primary_password = $result_primary[0]["password"];
        $primary_db = $result_primary[0]["db"];
        if (self::pingServer($primary_host) == -1) {
            self::transfers_logs("Error Connection 0003");
            return "0003";
        }
        $primary_db_connection = mysqli_connect($primary_host, $primary_username, $primary_password, $primary_db);
        if ($primary_db_connection) {
            mysqli_query($primary_db_connection, "SET NAMES utf8");
            $query_slave = "select * from store where id=" . $store_id;
            $result_stores = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $query_slave));
            $i = 0;
            while ($i < count($result_stores)) {
                $store_db_connection = mysqli_connect($result_stores[$i]["ip_address"], $result_stores[$i]["username"], $result_stores[$i]["password"], $result_stores[$i]["db"]);
                if ($store_db_connection) {
                    mysqli_query($store_db_connection, "SET NAMES utf8");
                    self::sync_item_with_store($store_db_connection, $result_stores[$i]["id"]);
                    $queries_transfers = "select * from transfers where submit_transfer=1 and synced_destination=0 and to_store_id=" . $result_stores[$i]["id"] . " and deleted=0 order by id asc";
                    $result_transfers = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $queries_transfers));
                    for ($j = 0; $j < count($result_transfers); $j++) {
                        $query_transfers_details = "select * from transfers_details where transfer_id=" . $result_transfers[$j]["id"] . " and deleted=0";
                        $result_transfers_details = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $query_transfers_details));
                        $query_qty_multiple_slave = "";
                        $query_qty_history = "insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) VALUES";
                        $log_msg = "";
                        for ($k = 0; $k < count($result_transfers_details); $k++) {
                            $log_msg .= "Item id:" . $result_transfers_details[$k]["item_id"] . " quantity: " . $result_transfers_details[$k]["qty"] . " added to store id: " . $result_transfers[$j]["to_store_id"] . " \n";
                            $query_qty_multiple_slave .= " when item_id = " . $result_transfers_details[$k]["item_id"] . " and store_id=" . $result_transfers[$j]["to_store_id"] . " then quantity+" . $result_transfers_details[$k]["qty"] . " ";
                            $after_qty = self::getQtyOfItem($result_transfers_details[$k]["item_id"], $store_db_connection);
                            if ($k == count($result_transfers_details) - 1) {
                                $query_qty_history .= "(1," . $result_transfers_details[$k]["item_id"] . ",'" . my_sql::datetime_now() . "'," . $result_transfers_details[$k]["qty"] . "," . $result_transfers[$j]["to_store_id"] . "," . ($after_qty[0]["quantity"] + $result_transfers_details[$k]["qty"]) . ",'" . self::idFormat_transfers($result_transfers[$j]["id"]) . "');";
                            } else {
                                $query_qty_history .= "(1," . $result_transfers_details[$k]["item_id"] . ",'" . my_sql::datetime_now() . "'," . $result_transfers_details[$k]["qty"] . "," . $result_transfers[$j]["to_store_id"] . "," . ($after_qty[0]["quantity"] + $result_transfers_details[$k]["qty"]) . ",'" . self::idFormat_transfers($result_transfers[$j]["id"]) . "'),";
                            }
                        }
                        $query_qty_multiple_slave = "UPDATE store_items SET quantity = CASE " . $query_qty_multiple_slave . " ELSE quantity END;";
                        $result_update = mysqli_query($store_db_connection, $query_qty_multiple_slave);
                        mysqli_query($store_db_connection, $query_qty_history);
                        if ($result_update) {
                            self::transfers_logs($log_msg);
                            mysqli_query($primary_db_connection, "update transfers set synced_destination=1 where id=" . $result_transfers[$j]["id"]);
                        } else {
                            self::transfers_logs("Error query execution " . $result_transfers[$j]["id"] . " on store id " . $result_stores[$i]["id"]);
                            self::transfers_logs("## Query: " . $query_qty_multiple_slave);
                            self::transfers_logs("## Error description: " . mysqli_error($store_db_connection));
                        }
                    }
                    $queries_transfers = "select * from transfers where submit_transfer=1 and synced_source=0 and from_store_id=" . $result_stores[$i]["id"] . " and deleted=0 order by id asc";
                    $result_transfers = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $queries_transfers));
                    for ($j = 0; $j < count($result_transfers); $j++) {
                        $query_transfers_details = "select * from transfers_details where transfer_id=" . $result_transfers[$j]["id"] . " and deleted=0";
                        $result_transfers_details = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $query_transfers_details));
                        $query_qty_multiple_slave = "";
                        $query_qty_history = "insert into history_quantities(user_id,item_id,creation_date,qty,store_id,qty_afer_action,source) VALUES";
                        $log_msg = "";
                        for ($k = 0; $k < count($result_transfers_details); $k++) {
                            $log_msg .= "Item id:" . $result_transfers_details[$k]["item_id"] . " quantity: " . $result_transfers_details[$k]["qty"] . " reduced from store id: " . $result_transfers[$j]["from_store_id"] . " \n";
                            $query_qty_multiple_slave .= " when item_id = " . $result_transfers_details[$k]["item_id"] . " and store_id=" . $result_transfers[$j]["from_store_id"] . " then quantity-" . $result_transfers_details[$k]["qty"] . " ";
                            $after_qty = self::getQtyOfItem($result_transfers_details[$k]["item_id"], $store_db_connection);
                            if ($k == count($result_transfers_details) - 1) {
                                $query_qty_history .= "(1," . $result_transfers_details[$k]["item_id"] . ",'" . my_sql::datetime_now() . "',-" . $result_transfers_details[$k]["qty"] . "," . $result_transfers[$j]["from_store_id"] . "," . ($after_qty[0]["quantity"] - $result_transfers_details[$k]["qty"]) . ",'" . self::idFormat_transfers($result_transfers[$j]["id"]) . "');";
                            } else {
                                $query_qty_history .= "(1," . $result_transfers_details[$k]["item_id"] . ",'" . my_sql::datetime_now() . "',-" . $result_transfers_details[$k]["qty"] . "," . $result_transfers[$j]["from_store_id"] . "," . ($after_qty[0]["quantity"] - $result_transfers_details[$k]["qty"]) . ",'" . self::idFormat_transfers($result_transfers[$j]["id"]) . "'),";
                            }
                        }
                        $query_qty_multiple_slave = "UPDATE store_items SET quantity = CASE " . $query_qty_multiple_slave . " ELSE quantity END;";
                        $result_update = mysqli_query($store_db_connection, $query_qty_multiple_slave);
                        mysqli_query($store_db_connection, $query_qty_history);
                        if ($result_update) {
                            self::transfers_logs($log_msg);
                            mysqli_query($primary_db_connection, "update transfers set synced_source=1 where id=" . $result_transfers[$j]["id"]);
                        }
                    }
                    $i++;
                } else {
                    self::transfers_logs("Error Connection 0004");
                    return "0004";
                }
            }
            return "0000";
        }
        self::transfers_logs("Error Connection 0003");
        return "0003";
    }
    public function sync_Data($store_id)
    {
        $query_primary = "select * from store where primary_db=1";
        $result_primary = my_sql::fetch_assoc(my_sql::query($query_primary));
        $primary_host = $result_primary[0]["ip_address"];
        $primary_username = $result_primary[0]["username"];
        $primary_password = $result_primary[0]["password"];
        $primary_db = $result_primary[0]["db"];
        if (self::pingServer($primary_host) == -1) {
            self::transfers_logs("Error Connection 0003");
            return "0003";
        }
        $primary_db_connection = mysqli_connect($primary_host, $primary_username, $primary_password, $primary_db);
        if ($primary_db_connection) {
            mysqli_query($primary_db_connection, "SET NAMES utf8");
            $query_slave = "select * from store where id=" . $store_id;
            $result_slave = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $query_slave));
            $i = 0;
            while ($i < count($result_slave)) {
                $slave_db_connection = mysqli_connect($result_slave[$i]["ip_address"], $result_slave[$i]["username"], $result_slave[$i]["password"], $result_slave[$i]["db"]);
                if ($slave_db_connection) {
                    mysqli_query($slave_db_connection, "SET NAMES utf8");
                    $queries_to_execute = "select * from queries where (target_store=" . $result_slave[$i]["id"] . " or target_store=0) and id not in (select qry_id from queries_synced where store_id=" . $result_slave[$i]["id"] . ") order by id asc";
                    $result_to_execute = my_sql::fetch_assoc(mysqli_query($primary_db_connection, $queries_to_execute));
                    $j = 0;
                    while ($j < count($result_to_execute)) {
                        $reqry = mysqli_query($slave_db_connection, $result_to_execute[$j]["qry"]);
                        if ($reqry && 0 <= mysqli_affected_rows($slave_db_connection)) {
                            self::transfers_logs("query id: " . $result_to_execute[$j]["id"] . " has been executed");
                            $result_inserted = mysqli_query($primary_db_connection, "insert into queries_synced(qry_id,store_id) values(" . $result_to_execute[$j]["id"] . "," . $result_slave[$i]["id"] . ")");
                            if ($result_inserted) {
                                self::transfers_logs("query id: " . $result_to_execute[$j]["id"] . " marked as synced");
                            }
                            $j++;
                        } else {
                            break;
                        }
                    }
                    $i++;
                } else {
                    self::transfers_logs("Error Connection 0002");
                    return "0002";
                }
            }
            return "0000";
        }
        self::transfers_logs("Error Connection 0001");
        return "0001";
    }
}

?>