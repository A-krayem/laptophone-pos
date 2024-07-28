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
class expensesModel
{
    public function getTypes()
    {
        $query = "select * from  expenses_types where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTypesEvenDeleted()
    {
        $query = "select * from  expenses_types";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalByTypes($id, $date)
    {
        $query = "select COALESCE(sum(value), 0) as sum from expenses where deleted=0 and type_id=" . $id . " and date(date)>='" . $date[0] . "' and date(date)<='" . $date[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function add_expense($info)
    {
        if (!isset($_SESSION["cashbox_id"])) {
            $cashbox_id = 0;
        } else {
            $cashbox_id = $_SESSION["cashbox_id"];
        }
        $query = "insert into expenses(type_id,description,date,value,creation_date,store_id,vendor_id,cashbox_id,cash_usd_to_return,cash_lbp_to_return,returned_cash_lbp,returned_cash_usd,cash_lbp_in,cash_usd_in,rate,reflected_to_profit) values(" . $info["type_id"] . ",'" . $info["description"] . "','" . $info["date"] . "'," . $info["value"] . ",'" . my_sql::datetime_now() . "'," . $info["store_id"] . "," . $info["vendor_id"] . ",'" . $cashbox_id . "','" . $info["cash_usd_to_return"] . "','" . $info["cash_lbp_to_return"] . "'," . "'" . $info["returned_cash_lbp"] . "','" . $info["returned_cash_usd"] . "'," . "'" . $info["cash_lbp_in"] . "','" . $info["cash_usd_in"] . "','" . $info["rate"] . "'," . $info["reflected_to_profit"] . ")";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function add_new_category($info)
    {
        $query = "insert into expenses_types(name) values('" . $info["name"] . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function update_expense($info)
    {
        $query = "update expenses set type_id=" . $info["type_id"] . ",description='" . $info["description"] . "',date='" . $info["date"] . "',value='" . $info["value"] . "',reflected_to_profit=" . $info["reflected_to_profit"] . " where id=" . $info["id_to_edit"];
        my_sql::query($query);
    }
    public function update_expense_type_name($id, $name)
    {
        $query = "update expenses_types set name='" . $name . "' where id=" . $id;
        my_sql::query($query);
    }
    public function get_expense($id)
    {
        $query = "select * from  expenses where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_expense($id)
    {
        $query = "update expenses set deleted = 1 where id=" . $id;
        my_sql::query($query);
    }
    public function delete_expense_type($id)
    {
        $query = "update expenses_types set deleted = 1 where id=" . $id;
        my_sql::query($query);
    }
    public function getExpenses($store_id)
    {
        $query = "select * from  expenses where deleted=0 and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getExpensesByDate($store_id, $date)
    {
        $query = "select * from  expenses where deleted=0 and store_id=" . $store_id . " and date(creation_date)='" . $date . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getExpensesByDateRange($store_id, $date_range)
    {
        if ($_SESSION["role"] == 1) {
            if ($date_range == "today") {
                $query = "select * from  expenses where deleted=0 date(date)>=date('" . my_sql::datetime_now() . "')";
            } else {
                $query = "select * from  expenses where deleted=0 and date(date)>='" . $date_range[0] . "' and date(date)<='" . $date_range[1] . "'";
            }
        } else {
            if ($date_range == "today") {
                $query = "select * from  expenses where vendor_id=" . $_SESSION["id"] . " and deleted=0 date(date)>=date('" . my_sql::datetime_now() . "')";
            } else {
                $query = "select * from  expenses where vendor_id=" . $_SESSION["id"] . " and deleted=0 and date(date)>='" . $date_range[0] . "' and date(date)<='" . $date_range[1] . "'";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getExpensesByDateRangeAndType($store_id, $date_range, $type_id)
    {
        $query_type_id = "";
        if (0 < $type_id) {
            $query_type_id = " and type_id=" . $type_id;
        }
        if ($date_range == "today") {
            $query = "select * from  expenses where deleted=0 date(date)>=date('" . my_sql::datetime_now() . "') " . $query_type_id;
        } else {
            $query = "select * from  expenses where deleted=0 and date(date)>='" . $date_range[0] . "' and date(date)<='" . $date_range[1] . "' " . $query_type_id;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getExpensesByIntervalOfDate($store_id, $daterange)
    {
        $query = "select COALESCE(sum(value), 0) as sum from  expenses where deleted=0 and date(date)>='" . $daterange[0] . "' and date(date)<='" . $daterange[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getExpensesByIntervalOfDate_remote($store_id, $daterange, $cnx)
    {
        $query = "select COALESCE(sum(value), 0) as sum from  expenses where deleted=0 and date(date)>='" . $daterange[0] . "' and date(date)<='" . $daterange[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result[0]["sum"];
    }
    public function getSumOfExpensesByCashboxID($cashbox_id)
    {
        $query = "select COALESCE(sum(value), 0) as sum from expenses where deleted=0 and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getExpensesByCashboxID($cashbox_id)
    {
        $query = "select * from expenses where deleted=0 and cashbox_id=" . $cashbox_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>