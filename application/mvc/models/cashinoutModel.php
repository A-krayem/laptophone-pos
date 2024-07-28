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
class cashinoutModel
{
    public function getAllTypes()
    {
        $query = "select * from cash_in_out_types where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReturnedItemsCashDetails($cashbox_id)
    {
        $query = "select * from cashbox_changes_info where cashbox_id=" . $cashbox_id . " and invoice_item_return_id>0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_in_out_of_invoice($invoice_id)
    {
        $query = "select * from cashbox_changes_info where invoice_id=" . $invoice_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_cash_details_by_id($id, $payment_type)
    {
        if ($payment_type == 1) {
            $query = "select * from cash_details where id=" . $id;
        }
        if ($payment_type == 2) {
            $query = "select * from cashbox_changes_info where id=" . $id;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_cash_details($update)
    {
        if ($update["payment_type"] == 1) {
            my_sql::query("update cash_details set cash_usd='" . $update["cash_usd"] . "',cash_lbp='" . $update["cash_lbp"] . "',returned_cash_usd='" . $update["returned_cash_usd"] . "',returned_cash_lbp='" . $update["returned_cash_lbp"] . "',must_return_cash_usd='" . $update["must_return_cash_usd"] . "',must_return_cash_lbp='" . $update["must_return_cash_lbp"] . "' where id=" . $update["id"]);
        }
        if ($update["payment_type"] == 2) {
            my_sql::query("update cashbox_changes_info set cash_usd_to_return='" . $update["must_return_cash_usd"] . "',cash_lbp_to_return='" . $update["must_return_cash_lbp"] . "',returned_cash_lbp='" . $update["returned_cash_lbp"] . "',returned_cash_usd='" . $update["returned_cash_usd"] . "',cash_lbp_in='" . $update["cash_lbp"] . "',cash_usd_in='" . $update["cash_usd"] . "' where id=" . $update["id"]);
        }
    }
    public function cash_details_log($log)
    {
        $query = "insert into cash_details_logs(creation_date,cash_usd,cash_lbp,returned_cash_usd,returned_cash_lbp,must_return_cash_usd,must_return_cash_lbp,cashbox_id,rate,transaction_id,transaction_type,cash_details_id) values" . "('" . my_sql::datetime_now() . "','" . $log["cash_usd"] . "','" . $log["cash_lbp"] . "','" . $log["returned_cash_usd"] . "','" . $log["returned_cash_lbp"] . "','" . $log["must_return_cash_usd"] . "','" . $log["must_return_cash_lbp"] . "','" . $log["cashbox_id"] . "','" . $log["rate"] . "','" . $log["transaction_id"] . "','" . $log["transaction_type"] . "','" . $log["cash_details_id"] . "')";
        my_sql::query($query);
    }
    public function get_cashinout_by_id($payment_type, $transaction_id)
    {
        if ($payment_type == 1) {
            $query = "select cd.id,cd.cash_usd,cd.cash_lbp,cd.invoice_id,cd.base_usd_amount,cd.rate,cd.must_return_cash_usd,cd.must_return_cash_lbp,cd.returned_cash_usd,cd.returned_cash_lbp  from cash_details cd left join invoices inv on inv.id=cd.invoice_id where cd.id=" . $transaction_id;
        }
        if ($payment_type == 2) {
            $query = "select cd.return_value,cd.added_value, cd.id,cd.cash_lbp_in as cash_lbp,cd.cash_usd_in as cash_usd,cd.invoice_id,cd.return_value as base_usd_amount,cd.rate,cd.cash_usd_to_return as must_return_cash_usd,cd.cash_lbp_to_return as must_return_cash_lbp,cd.returned_cash_usd,cd.returned_cash_lbp  from cashbox_changes_info cd left join invoices inv on inv.id=cd.invoice_id where cd.id=" . $transaction_id;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_info_services($cash_type, $currency, $operations_type)
    {
        $operationtype_query = "";
        if (0 < $operations_type) {
            $operationtype_query = " and type_id=" . $operations_type . " ";
        }
        $query = "select COALESCE(sum(cash_value), 0) as sum from cash_in_out where deleted=0 and type_id not in (1,2) and cashbox_id=" . $_SESSION["cashbox_id"] . " and currency_id=" . $currency . " and cash_in_out=" . $cash_type . " " . $operationtype_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_total_cash_USD($cash_type, $operations_type)
    {
        $operationtype_query = "";
        if (0 < $operations_type) {
            $operationtype_query = " and type_id=" . $operations_type . " ";
        }
        $query = "select COALESCE(sum(amount_usd), 0) as sum from cash_in_out where deleted=0 and cashbox_id=" . $_SESSION["cashbox_id"] . " and cash_in_out=" . $cash_type . " " . $operationtype_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_total_cash_LBP($cash_type, $operations_type)
    {
        $operationtype_query = "";
        if (0 < $operations_type) {
            $operationtype_query = " and type_id=" . $operations_type . " ";
        }
        $query = "select COALESCE(sum(amount_lbp), 0) as sum from cash_in_out where deleted=0 and cashbox_id=" . $_SESSION["cashbox_id"] . " and cash_in_out=" . $cash_type . " " . $operationtype_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_info_transfer($cash_type, $currency, $operations_type)
    {
        $operationtype_query = "";
        if (0 < $operations_type) {
            $operationtype_query = " and type_id=" . $operations_type . " ";
        }
        $query = "select COALESCE(sum(cash_value), 0) as sum from cash_in_out where deleted=0 and type_id in (1,2) and cashbox_id=" . $_SESSION["cashbox_id"] . " and currency_id=" . $currency . " and cash_in_out=" . $cash_type . " " . $operationtype_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_starting($shift_id)
    {
        $query = "select * from cash_in_out_starting where shift_id=" . $shift_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllTypesEvenDeleted()
    {
        $query = "select * from cash_in_out_types";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getOperationTypeById($id)
    {
        $query = "select * from cash_in_out_types where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashinout($date_range)
    {
        $query = "select * from cash_in_out where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cashbox_id=" . $_SESSION["cashbox_id"];
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashinout_st($date_range)
    {
        $query = "select * from cash_in_out where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashinout_report($date_range, $operationtype, $cashtype)
    {
        $cash_in_out_query = "";
        if (0 < $cashtype) {
            $cash_in_out_query = " and cash_in_out=" . $cashtype . " ";
        }
        $operationtype_query = "";
        if (0 < $operationtype) {
            $operationtype_query = " and type_id=" . $operationtype . " ";
        }
        $query = "select * from cash_in_out where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' " . $cash_in_out_query . " " . $operationtype_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_cashinout_report_shift($date_range, $operationtype, $cashtype, $shift_id)
    {
        $cash_in_out_query = "";
        if (0 < $cashtype) {
            $cash_in_out_query = " and cash_in_out=" . $cashtype . " ";
        }
        $operationtype_query = "";
        if (0 < $operationtype) {
            $operationtype_query = " and type_id=" . $operationtype . " ";
        }
        if (0 < $shift_id) {
            $query = "select * from cash_in_out where deleted=0 and cashbox_id=" . $shift_id . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' " . $cash_in_out_query . " " . $operationtype_query;
        } else {
            $query = "select * from cash_in_out where deleted=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' " . $cash_in_out_query . " " . $operationtype_query;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function starting($currency, $shift_id)
    {
        $query = "select * from cash_in_out_starting where shift_id=" . $shift_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if ($currency == 1 && 0 < count($result)) {
            return $result[0]["usd_amount"];
        }
        if ($currency == 2 && 0 < count($result)) {
            return $result[0]["lbp_amount"];
        }
        return 0;
    }
    public function get_all_cashinout_report_sum($date_range, $operationtype, $cashtype, $currency)
    {
        $cash_in_out_query = "";
        if (0 < $cashtype) {
            $cash_in_out_query = " and cash_in_out=" . $cashtype . " ";
        }
        $operationtype_query = "";
        if (0 < $operationtype) {
            $operationtype_query = " and type_id=" . $operationtype . " ";
        }
        $query = "select COALESCE(sum(cash_value), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' " . $cash_in_out_query . " " . $operationtype_query;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_all_cash_in_out_report_sum($date_range, $currency, $operationtype, $shift_id)
    {
        $column = "amount_usd";
        if ($currency == 2) {
            $column = "amount_lbp";
        }
        $operationtype_query = "";
        if (0 < $operationtype) {
            $operationtype_query = " and type_id=" . $operationtype . " ";
        }
        if (0 < $shift_id) {
            $query_in = "select COALESCE(sum(" . $column . "), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cashbox_id=" . $shift_id . " and cash_in_out=1 " . $operationtype_query;
        } else {
            $query_in = "select COALESCE(sum(" . $column . "), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cash_in_out=1 " . $operationtype_query;
        }
        $result_in = my_sql::fetch_assoc(my_sql::query($query_in));
        if (0 < $shift_id) {
            $query_out = "select COALESCE(sum(" . $column . "), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cashbox_id=" . $shift_id . " and cash_in_out=2" . $operationtype_query;
        } else {
            $query_out = "select COALESCE(sum(" . $column . "), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cash_in_out=2" . $operationtype_query;
        }
        $result_out = my_sql::fetch_assoc(my_sql::query($query_out));
        $return = array();
        $return["total_in"] = $result_in[0]["sum"];
        $return["total_out"] = $result_out[0]["sum"];
        return $return;
    }
    public function get_all_cash_in_out_report_sum_amount($date_range, $currency, $operationtype, $shift_id)
    {
        $operationtype_query = "";
        if (0 < $operationtype) {
            $operationtype_query = " and type_id=" . $operationtype . " ";
        }
        if (0 < $shift_id) {
            $query_in = "select COALESCE(sum(cash_value), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cashbox_id=" . $shift_id . " and cash_in_out=1 " . $operationtype_query;
        } else {
            $query_in = "select COALESCE(sum(cash_value), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'  and cash_in_out=1 " . $operationtype_query;
        }
        $result_in = my_sql::fetch_assoc(my_sql::query($query_in));
        if (0 < $shift_id) {
            $query_out = "select COALESCE(sum(cash_value), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cashbox_id=" . $shift_id . " and cash_in_out=2" . $operationtype_query;
        } else {
            $query_out = "select COALESCE(sum(cash_value), 0) as sum from cash_in_out where deleted=0 and currency_id=" . $currency . " and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and cash_in_out=2" . $operationtype_query;
        }
        $result_out = my_sql::fetch_assoc(my_sql::query($query_out));
        $return = array();
        $return["total_in_amount"] = $result_in[0]["sum"];
        $return["total_out_amount"] = $result_out[0]["sum"];
        return $return;
    }
    public function get_cash_in_out_id($id)
    {
        $query = "select * from cash_in_out where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add($info)
    {
        $query = "insert into cash_in_out(creation_date,type_id,cash_value,cashbox_id,user_id,note,deleted,currency_id,currency_rate,cash_in_out,amount_lbp,amount_usd,operation_reference) values('" . my_sql::datetime_now() . "'," . $info["type_id"] . "," . $info["cash_value"] . "," . $info["cashbox_id"] . "," . $info["user_id"] . ",'" . $info["note"] . "',0," . $info["currency_id"] . "," . $info["currency_rate"] . "," . $info["cash_in_out"] . "," . $info["amount_lbp"] . "," . $info["amount_usd"] . ",'" . $info["op_ref"] . "')";
        my_sql::query($query);
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function delete($id)
    {
        my_sql::query("update cash_in_out set deleted=1 where id=" . $id);
    }
    public function update_starting_usd($val, $shift_id)
    {
        my_sql::query("update cash_in_out_starting set usd_amount=" . $val . " where shift_id=" . $shift_id);
    }
    public function update_starting_lbp($val, $shift_id)
    {
        my_sql::query("update cash_in_out_starting set lbp_amount=" . $val . "  where shift_id=" . $shift_id);
    }
}

?>