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
class shareholderModel
{
    public function update_log($info)
    {
        $query = "insert into shareholders_logs(shareholder_id,percentage,start_date) values(" . $info["shareholder_id"] . "," . $info["percentage"] . ",'" . $info["start_date"] . "')";
        my_sql::query($query);
    }
    public function get_all_share_holders($filters)
    {
        $query = "select * from shareholders where status=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_error_details($date)
    {
        $query = "select * from (SELECT COALESCE(sum(cb.balance), 0) as sum,cb.invoice_id,(inv.total_value+inv.invoice_discount) as total_inv_amt FROM `customer_balance` cb left join invoices inv on inv.id=cb.invoice_id where cb.invoice_id>0 and date(cb.value_date)='" . $date . "' group by cb.invoice_id) as res where sum!=total_inv_amt";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_share_holders_even_deleted()
    {
        $query = "select * from shareholders";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function profit_distribution($startdate)
    {
        $query = "select COALESCE(sum(net_profit), 0) as sum from shareholders_details where date(for_date)='" . $startdate . "' and net_profit>0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_shareholders_details_log($shareholder_id)
    {
        $query = "select * from shareholders_logs where shareholder_id=" . $shareholder_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_share_holders_active($startdate)
    {
        $query = "select * from shareholders_logs where (date(start_date)<='" . $startdate . "' and end_date IS NULL) or (date(start_date)<='" . $startdate . "' and date(end_date)>'" . $startdate . "') and date(start_date)!=date(end_date) and shareholder_id in (select id from shareholders where removed_from_system=0) ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_statistics($start_date, $end_date)
    {
        $query = "select * from shareholders_details where date(for_date)>='" . $start_date . "' and date(for_date)<='" . $end_date . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_shareholder($id)
    {
        my_sql::query("update shareholders set status=0,deleted_date=now() where id=" . $id);
        my_sql::query("update shareholders_logs set end_date=now()  where shareholder_id=" . $id . " and end_date IS NULL");
    }
    public function delete_completely($id)
    {
        my_sql::query("update shareholders set status=0,deleted_date=now(),removed_from_system=1 where id=" . $id);
        my_sql::query("update shareholders_logs set end_date=now()  where shareholder_id=" . $id . " and end_date IS NULL");
    }
    public function update_shareholder($info)
    {
        $old_info = self::get_shareholder_by_id($info["id_to_edit"]);
        my_sql::query("update shareholders set name='" . $info["name"] . "' where id=" . $info["id_to_edit"]);
        if (floatval($old_info[0]["percentage"]) != floatval($info["percentage"])) {
            my_sql::query("update shareholders set percentage='" . $info["percentage"] . "' where id=" . $info["id_to_edit"]);
            $query_log_date = "select * from shareholders_logs where shareholder_id=" . $info["id_to_edit"] . " and date(start_date)=date('" . $old_info[0]["active_date"] . "') and date(start_date)=date(now()) ";
            $result_log_date = my_sql::fetch_assoc(my_sql::query($query_log_date));
            if (0 < count($result_log_date)) {
                my_sql::query("update shareholders_logs set percentage='" . $info["percentage"] . "' where id=" . $result_log_date[0]["id"]);
                return NULL;
            }
            $query_last = "select * from shareholders_logs where shareholder_id=" . $info["id_to_edit"] . " and end_date IS NULL";
            $result_last = my_sql::fetch_assoc(my_sql::query($query_last));
            if (0 < count($result_last)) {
                my_sql::query("update shareholders_logs set end_date=now() where id=" . $result_last[0]["id"]);
                $query = "insert into shareholders_logs(shareholder_id,percentage,start_date) values(" . $info["id_to_edit"] . "," . $info["percentage"] . ",now())";
                my_sql::query($query);
            }
        }
    }
    public function get_shareholder_by_id($id)
    {
        $query = "select * from shareholders where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_shareholder($info)
    {
        $query = "insert into shareholders (name,percentage,created_by,creation_date,status,active_date) values(" . "'" . $info["name"] . "','" . $info["percentage"] . "','" . $info["created_by"] . "',now(),1,'" . $info["active_date"] . "')";
        my_sql::query($query);
        $last_id = my_sql::get_mysqli_insert_id();
        $info_log = array();
        $info_log["shareholder_id"] = $last_id;
        $info_log["percentage"] = $info["percentage"];
        $info_log["start_date"] = $info["active_date"];
        self::update_log($info_log);
    }
    public function update_expenses_record_by_date($date, $total_expense)
    {
        $query = "select count(id) as num from shareholders_details where for_date='" . $date . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            my_sql::query("update shareholders_details set total_expenses=" . $total_expense . " where for_date='" . $date . "'");
        } else {
            my_sql::query("insert into shareholders_details(for_date,total_expenses) values('" . $date . "'," . $total_expense . ")");
        }
    }
    public function update_payments_record_by_date($date, $total_payments)
    {
        $query = "select count(id) as num from shareholders_details where for_date='" . $date . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            my_sql::query("update shareholders_details set total_invoices_income=" . $total_payments . " where for_date='" . $date . "'");
        } else {
            my_sql::query("insert into shareholders_details(for_date,total_invoices_income) values('" . $date . "'," . $total_payments . ")");
        }
    }
    public function prepare_all_expenses($from_date, $end_date)
    {
        $query = "SELECT date(`date`) as dt,sum(`value`) as total_expense FROM `expenses` where deleted=0 and date(`date`)>=date('" . $from_date . "') and date(`date`)<=date('" . $end_date . "') group by date(`date`) ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            self::update_expenses_record_by_date($result[$i]["dt"], $result[$i]["total_expense"]);
        }
        return $result;
    }
    public function prepare_all_invoices_payments($from_date, $end_date)
    {
        $query = "SELECT date(`value_date`) as dt,sum(`balance`) as total_payment FROM `customer_balance` where deleted=0 and date(`value_date`)>=date('" . $from_date . "') and date(`value_date`)<=date('" . $end_date . "') and invoice_id>0 group by date(`value_date`) ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            self::update_payments_record_by_date($result[$i]["dt"], $result[$i]["total_payment"]);
        }
        return $result;
    }
    public function update_invoice_profit_by_date($date, $total_profit, $total_cost)
    {
        $query = "select count(id) as num from shareholders_details where for_date='" . $date . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            my_sql::query("update shareholders_details set total_invoices_profit=" . $total_profit . ",total_invoices_cost=" . $total_cost . " where for_date='" . $date . "'");
        } else {
            my_sql::query("insert into shareholders_details(for_date,total_invoices_profit,total_invoices_cost) values('" . $date . "'," . $total_profit . "," . $total_cost . ")");
        }
    }
    public function prepare_all_invoices_profits($from_date, $end_date)
    {
        $query = "SELECT date(cb.value_date) as dt,sum(inv.profit_after_discount) as total_profit,sum(inv.total_value+inv.invoice_discount-inv.profit_after_discount) as total_cost FROM `customer_balance` cb left join invoices inv on inv.id=cb.invoice_id where  inv.deleted=0 and cb.deleted=0 and cb.invoice_id>0 and date(cb.`value_date`)>=date('" . $from_date . "') and date(cb.`value_date`)<=date('" . $end_date . "') group by date(cb.value_date)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            self::update_invoice_profit_by_date($result[$i]["dt"], $result[$i]["total_profit"], $result[$i]["total_cost"]);
        }
        return $result;
    }
    public function update_net_profit($from_date, $end_date)
    {
        my_sql::query("update shareholders_details set net_profit=total_invoices_profit-total_expenses  where date(for_date)>='" . $from_date . "' and date(for_date)<='" . $end_date . "' ");
    }
    public function reset_shareholders_details($from_date, $end_date)
    {
        my_sql::query("update shareholders_details set total_expenses=0,total_invoices_income=0,total_invoices_cost=0,total_invoices_profit=0,net_profit=0  where date(for_date)>='" . $from_date . "' and date(for_date)<='" . $end_date . "' ");
    }
}

?>