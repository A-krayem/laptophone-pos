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
class reportsModel
{
    public function get_stock_movement($info)
    {
        $query = "select * from stock_movement_details where stock_movement_id in (select id from stock_movement where MONTH('" . $info["date"] . "')=MONTH(stock_date) AND YEAR('" . $info["date"] . "') = YEAR(stock_date) and store_id=" . $info["store_id"] . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_total_commission($store_id, $date_range, $vendor_id)
    {
        $query = "select COALESCE(sum(total_value*vendor_commission_percentage/100), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and (employee_id=" . $vendor_id . " or id in (select invoice_id from quotations where created_by=" . $vendor_id . " and invoice_id>0 ) ) ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_commission_details($date_range, $vendor_id)
    {
        $query = "select id,total_value,vendor_commission_percentage from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and employee_id=" . $vendor_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getLacksItems($store_id)
    {
        $query = "select st.item_id,it.description,it.barcode,st.quantity,it.supplier_reference,sp.name,it.unit_measure_id as unit_measure_id,it.vat,it.buying_cost,it.selling_price,it.discount,it.wholesale_price from store_items st, items it,suppliers sp where st.item_id=it.id and it.supplier_reference=sp.id and st.quantity<=it.lack_warning and it.lack_warning!=-1 and it.deleted=0 and it.is_composite=0 and st.store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function print_dashboard_report($daterange)
    {
        $query = "select MONTH(creation_date) AS month,YEAR(creation_date) AS year,DATE_FORMAT(DATE(creation_date),'%M %Y') as creation_date,count(id) as invoices_nb,COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "'  GROUP BY YEAR(creation_date), MONTH(creation_date) ASC";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_sold_qty_by_month_year($year, $month)
    {
        $query = "select COALESCE(sum(qty), 0) as sum from invoice_items where invoice_id in (select id from invoices where deleted=0 and other_branche=0 and YEAR(creation_date)='" . $year . "' and MONTH(creation_date)='" . $month . "')";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getCashboxReport($store_id, $rdate, $vendor_id)
    {
        $employee_filter = "";
        if (0 < $vendor_id) {
            $employee_filter = " and vendor_id=" . $vendor_id . " ";
        }
        $query = "select * from cashbox where date(starting_cashbox_date)='" . $rdate . "' " . $employee_filter . " order by starting_cashbox_date desc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_debts_payment($daterange)
    {
        $query = "select * from customer_balance where date(balance_date)>='" . $daterange[0] . "' and date(balance_date)<='" . $daterange[1] . "'  and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalTransfersSMSCost($store_id, $daterange, $mobile_operator)
    {
        if ($mobile_operator == 0) {
            $query = "select COALESCE(abs(sum(sms_fees)), 0) as sum from mobile_credits_history where invoice_item_id in ( select id from invoice_items where mobile_transfer_credits in (select id from mobile_dollars where days=0) and invoice_id in (select id from invoices where date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id . " and other_branche=0) )";
        } else {
            $query = "select COALESCE(abs(sum(sms_fees)), 0) as sum from mobile_credits_history where invoice_item_id in ( select id from invoice_items where mobile_transfer_credits in (select id from mobile_dollars where days=0) and invoice_id in (select id from invoices where date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id . " and other_branche=0) )  and device_id in (select id from mobile_devices where operator_id=" . $mobile_operator . ")";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalTransfers($store_id, $daterange, $mobile_operator)
    {
        if ($mobile_operator == 0) {
            $query = "select COALESCE(abs(sum(qty)), 0) as sum from mobile_credits_history where invoice_item_id in ( select id from invoice_items where mobile_transfer_credits in (select id from mobile_dollars where days=0) and invoice_id in (select id from invoices where date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id . " and other_branche=0) )";
        } else {
            $query = "select COALESCE(abs(sum(qty)), 0) as sum from mobile_credits_history where invoice_item_id in ( select id from invoice_items where mobile_transfer_credits in (select id from mobile_dollars where days=0) and invoice_id in (select id from invoices where date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id . " and other_branche=0) )  and device_id in (select id from mobile_devices where operator_id=" . $mobile_operator . ")";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportByDay_days_transfers($store_id, $daterange, $mobile_operator)
    {
        if ($mobile_operator == 0) {
            $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.buying_cost,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.final_cost_vat_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount,inv.payment_note from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id is NULL and inv_it.mobile_transfer_credits in (select id from mobile_dollars where days>0)";
        } else {
            $query_ = "select id from mobile_devices where operator_id=" . $mobile_operator;
            $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.buying_cost,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.final_cost_vat_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount,inv.payment_note from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id is NULL and inv_it.id in (select invoice_item_id from mobile_credits_history where device_id in ( " . $query_ . " ) ) and inv_it.mobile_transfer_credits in (select id from mobile_dollars where days>0)";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportByDay_credits_transfers($store_id, $daterange, $mobile_operator)
    {
        if ($mobile_operator == 0) {
            $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.buying_cost,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.final_cost_vat_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount,inv.payment_note from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id is NULL and inv_it.mobile_transfer_credits in (select id from mobile_dollars where days=0)";
        } else {
            $query_ = "select id from mobile_devices where operator_id=" . $mobile_operator;
            $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.buying_cost,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.final_cost_vat_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount,inv.payment_note from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id is NULL and inv_it.id in (select invoice_item_id from mobile_credits_history where device_id in ( " . $query_ . " ) ) and inv_it.mobile_transfer_credits in (select id from mobile_dollars where days=0)";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportByDay($store_id, $daterange, $category_id, $sales_type, $category_parent, $supplier_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        if ($category_id == 0 && $category_parent == 0) {
            $query = "select inv.id,inv_it.id as invit, inv.creation_date,inv_it.item_id,inv_it.buying_cost,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.final_cost_vat_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " " . $whole_sale;
        } else {
            if ($category_id == 0 && 0 < $category_parent) {
                $query = "select inv.id,inv_it.id as invit, inv.creation_date,inv_it.item_id,inv_it.buying_cost,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.final_cost_vat_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category in (select id from items_categories where parent=" . $category_parent . ") ) " . $whole_sale . " ";
            } else {
                $query = "select inv.id,inv_it.id as invit, inv.creation_date,inv_it.item_id,inv_it.buying_cost,inv_it.selling_price,inv_it.final_price_disc_qty,inv_it.final_cost_vat_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ") " . $whole_sale;
            }
        }
        $query_supplier = "";
        if (0 < $supplier_id) {
            $query_supplier = " and inv_it.item_id in (select distinct(item_id) from receive_stock where receive_stock_invoice_id in (select id from receive_stock_invoices where supplier_id=" . $supplier_id . "))";
            $query .= $query_supplier;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportByDay_by_item($store_id, $daterange, $category_id, $sales_type, $category_parent_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        $categories_filter = "";
        if ($category_id == 0 && $category_parent_id == 0) {
            $categories_filter = "";
        }
        if ($category_id == 0 && 0 < $category_parent_id) {
            $categories_filter = " and inv_it.item_id in (select id from items where item_category in (select id from items_categories where parent=" . $category_parent_id . ")) ";
        }
        if (0 < $category_id && $category_parent_id == 0) {
            $categories_filter = " and inv_it.item_id in (select id from items where item_category=" . $category_id . ") ";
        }
        if (0 < $category_id && 0 < $category_parent_id) {
            $categories_filter = " and inv_it.item_id in (select id from items where item_category=" . $category_id . ") ";
        }
        $query = "select inv_it.id,inv_it.item_id,sum(inv_it.qty*inv_it.buying_cost) as buying_cost,inv_it.selling_price,sum(inv_it.final_price_disc_qty) as final_price_disc_qty,sum(inv_it.final_cost_vat_qty) as final_cost_vat_qty,sum(inv_it.qty) as qty,inv_it.description,sum(inv_it.profit) as profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv_it.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' " . $categories_filter . " " . $whole_sale . " and item_id IS NOT NULL group by item_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        $query_mobile = "select inv_it.item_id,sum(inv_it.qty*inv_it.buying_cost) as buying_cost,inv_it.selling_price,sum(inv_it.final_price_disc_qty) as final_price_disc_qty,sum(inv_it.final_cost_vat_qty) as final_cost_vat_qty,sum(inv_it.qty) as qty,inv_it.description,sum(inv_it.profit) as profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv_it.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and item_id IS NULL " . $whole_sale . " group by mobile_transfer_credits";
        $result_mobile = my_sql::fetch_assoc(my_sql::query($query_mobile));
        return array_merge($result, $result_mobile);
    }
    public function getSumOfSoldItems_by_group($item_group, $category_id, $daterange)
    {
        if ($category_id == 0) {
            $query = "select COALESCE(sum(inv_it.qty), 0) as qty from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv_it.item_id in (select id from items where item_group=" . $item_group . ")";
        } else {
            $query = "select COALESCE(sum(inv_it.qty), 0) as qty from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv_it.item_id in (select id from items where item_group=" . $item_group . " and item_category=" . $category_id . ")";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSumOfSoldItems_by_group__bygroup($category_id, $daterange)
    {
        if ($category_id == 0) {
            $query = "select it.item_group,COALESCE(sum(inv_it.qty), 0) as qty from invoices inv left join invoice_items inv_it on inv.id=inv_it.invoice_id left join items it on it.id=inv_it.item_id where inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' group by it.item_group";
        } else {
            $query = "select it.item_group,COALESCE(sum(inv_it.qty), 0) as qty from invoices inv left join invoice_items inv_it on inv.id=inv_it.invoice_id left join items it on it.id=inv_it.item_id where inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and it.item_category=" . $category_id . " group by it.item_group ";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSumOfSoldItems_all_time($store_id, $category_id)
    {
        if ($category_id == 0) {
            $query = "select inv_it.item_id,sum(inv_it.qty) as qty from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and inv.store_id=" . $store_id . " group by item_id";
        } else {
            $query = "select inv_it.item_id,sum(inv_it.qty) as qty from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ") group by item_id";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSumOfSoldItems_all_time_by_group__groupby($category_id)
    {
        if ($category_id == 0) {
            $query = "select it.item_group,COALESCE(sum(inv_it.qty), 0) as qty from invoices inv left join invoice_items inv_it on inv.id=inv_it.invoice_id left join items it on it.id=inv_it.item_id where inv.deleted=0 and inv.other_branche=0 and it.item_group IS NOT NULL group by it.item_group";
        } else {
            $query = "select it.item_group,COALESCE(sum(inv_it.qty), 0) as qty from invoices inv left join invoice_items inv_it on inv.id=inv_it.invoice_id left join items it on it.id=inv_it.item_id where inv.deleted=0 and inv.other_branche=0 and it.item_group IS NOT NULL and it.item_category=" . $category_id . " group by it.item_group";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getSumOfSoldItems_all_time_by_group($item_group, $category_id)
    {
        if ($category_id == 0) {
            $query = "select COALESCE(sum(inv_it.qty), 0) as qty from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and inv_it.item_id in (select id from items where item_group=" . $item_group . ")";
        } else {
            $query = "select COALESCE(sum(inv_it.qty), 0) as qty from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and inv_it.item_id in (select id from items where item_group=" . $item_group . " and item_category=" . $category_id . ")";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportByDay_switch($store_id, $daterange, $category_id, $sales_type)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        if ($category_id == 0) {
            $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.final_price_disc_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and user_role=4 " . $whole_sale;
        } else {
            $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.final_price_disc_qty,inv_it.qty,inv_it.description,inv_it.profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and user_role=4 and inv_it.item_id in (select id from items where item_category=" . $category_id . ") " . $whole_sale;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function prepare_qry_sales_type($sales_type)
    {
        $whole_sale = "";
        switch ($sales_type) {
            case "0":
                $whole_sale = "";
                break;
            case "1":
                $whole_sale = " and (inv.customer_id not in (select id from customers where customer_type=2) or inv.customer_id is NULL)";
                break;
            case "2":
                $whole_sale = " and inv.customer_id in (select id from customers where customer_type=2)";
                break;
            default:
                $whole_sale = "";
        }
        return $whole_sale;
    }
    public function getReportByDay_by_item_switch($store_id, $daterange, $category_id, $sales_type, $category_parent_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        if ($category_id == 0) {
            $query = "select inv_it.item_id,sum(inv_it.buying_cost) as buying_cost,inv_it.selling_price,sum(inv_it.final_price_disc_qty) as final_price_disc_qty,sum(inv_it.final_cost_vat_qty) as final_cost_vat_qty,sum(inv_it.qty) as qty,inv_it.description,sum(inv_it.profit) as profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and user_role=4 " . $whole_sale . " group by item_id ";
        } else {
            $query = "select inv_it.item_id,sum(inv_it.buying_cost) as buying_cost,inv_it.selling_price,sum(inv_it.final_price_disc_qty) as final_price_disc_qty,sum(inv_it.final_cost_vat_qty) as final_cost_vat_qty,sum(inv_it.qty) as qty,inv_it.description,sum(inv_it.profit) as profit,inv_it.vat,inv_it.vat_value,inv_it.discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and user_role=4 and inv_it.item_id in (select id from items where item_category=" . $category_id . ") " . $whole_sale . " group by item_id";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportByEmployee($store_id, $date_range, $vendor_id)
    {
        $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.final_price_disc_qty,inv_it.qty,inv_it.description,inv_it.vat,inv_it.vat_value,inv_it.selling_price,inv_it.discount,inv.tax,inv.customer_id,inv.total_value,inv.invoice_discount from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and (inv.employee_id=" . $vendor_id . " or inv.id in (select invoice_id from quotations where created_by=" . $vendor_id . " and deleted=0 )) and date(inv.creation_date)>=date('" . $date_range[0] . "') and date(inv.creation_date)<=date('" . $date_range[1] . "') ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getVATReport($store_id, $date_range)
    {
        $query = "select * from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and official=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportBySalesperson($store_id, $date_range, $sales_person_id)
    {
        if ($sales_person_id == NULL || $sales_person_id == "null") {
            return array();
        }
        $query = "select inv.id,inv.creation_date,inv_it.item_id,inv_it.final_price_disc_qty,inv_it.qty,inv_it.description,inv_it.vat,inv_it.vat_value,inv_it.selling_price,inv_it.discount,inv.closed from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $date_range[0] . "' and date(inv.creation_date)<='" . $date_range[1] . "' and sales_person=" . $sales_person_id . " and inv.store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportSumBySalesperson($store_id, $sales_person_id, $date_range)
    {
        $query = "select COALESCE(sum(total_value), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and sales_person=" . $sales_person_id . " and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportSumBySalesperson_debt($store_id, $sales_person_id, $date_range)
    {
        $query = "select COALESCE(sum(total_value), 0) as sum from invoices where deleted=0 and closed=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and sales_person=" . $sales_person_id . " and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportSumBySalespersonDiscountsInvoice($store_id, $sales_person_id, $date_range)
    {
        $query = "select COALESCE(abs(sum(invoice_discount)), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and sales_person=" . $sales_person_id . " and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function profit_limited($store_id, $daterange, $category_id)
    {
        if ($category_id == 0) {
            $query = "select COALESCE(sum(total_profit_limited), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id . " and employee_id in (select id from users where role_id=4) ";
        } else {
            $query = "select COALESCE(sum(profit), 0) as sum from invoices inv,invoice_items inv_it where inv.deleted=0 and inv.other_branche=0 and inv.id=inv_it.invoice_id and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and user_role=4 and inv_it.item_id in (select id from items where item_category=" . $category_id . ")";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function total_mobile_transfers($store_id, $daterange, $mobile_operator)
    {
        $query_ = "select id from mobile_devices where operator_id=" . $mobile_operator;
        $query = "select COALESCE(sum(inv_it.profit), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id IS NULL and inv_it.id in (select invoice_item_id from mobile_credits_history where device_id in ( " . $query_ . " ) )";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function profit_mobile_transfers($store_id, $daterange, $mobile_operator)
    {
        if ($mobile_operator == 0) {
            $query = "select COALESCE(sum(inv_it.profit), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id IS NULL and inv_it.mobile_transfer_credits in (select id from mobile_dollars where days=0)";
        } else {
            $query_ = "select id from mobile_devices where operator_id=" . $mobile_operator;
            $query = "select COALESCE(sum(inv_it.profit), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id IS NULL and inv_it.id in (select invoice_item_id from mobile_credits_history where device_id in ( " . $query_ . " ) ) and inv_it.mobile_transfer_credits in (select id from mobile_dollars where days=0)";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function profit($store_id, $daterange, $category_id, $sales_type, $category_parent_id, $supplier_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        $query_supplier = "";
        if (0 < $supplier_id) {
            $query_supplier = " and inv_it.item_id in (select distinct(item_id) from receive_stock where receive_stock_invoice_id in (select id from receive_stock_invoices where supplier_id=" . $supplier_id . "))";
            $query .= $query_supplier;
        }
        if ($category_id == 0 && $category_parent_id == 0) {
            $query = "select COALESCE(sum(inv_it.profit), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' " . $whole_sale . " " . $query_supplier;
        } else {
            if ($category_id == 0 && 0 < $category_parent_id) {
                $query = "select COALESCE(sum(inv_it.profit), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv_it.item_id in ( select id from items where item_category in (select id from items_categories where parent=" . $category_parent_id . ")) " . $whole_sale . " " . $query_supplier;
            } else {
                $query = "select COALESCE(sum(inv_it.profit), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv_it.item_id in (select id from items where item_category=" . $category_id . ") " . $whole_sale . " " . $query_supplier;
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function profit__($store_id, $daterange, $category_id, $sales_type, $category_parent_id, $supplier_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        $query_supplier = "";
        if (0 < $supplier_id) {
            $query_supplier = " and inv_it.item_id in (select distinct(item_id) from receive_stock where receive_stock_invoice_id in (select id from receive_stock_invoices where supplier_id=" . $supplier_id . "))";
        }
        $query = "select COALESCE(sum(inv.profit_after_discount), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' " . $whole_sale . " " . $query_supplier;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalDebtsSales($store_id, $daterange, $category_id)
    {
        if ($category_id == 0) {
            if ($_SESSION["role"] == 3) {
                $query = "select COALESCE(sum(total_value), 0) as sum from invoices where deleted=0 and other_branche=0 and (auto_closed=1 || closed=0) and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and official=1 and store_id=" . $store_id;
            } else {
                $query = "select COALESCE(sum(total_value), 0) as sum from invoices where deleted=0 and other_branche=0 and (auto_closed=1 || closed=0) and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id;
            }
        } else {
            if ($_SESSION["role"] == 3) {
                $query = "select COALESCE(sum(final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and inv.official=1 and (inv.auto_closed=1 || inv.closed=0) and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ")";
            } else {
                $query = "select COALESCE(sum(final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and (inv.auto_closed=1 || inv.closed=0) and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ")";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalDebtsSalesInfo($store_id, $daterange, $category_id, $sales_type, $category_parent_id, $supplier_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        $supplier__query = "";
        if (0 < $supplier_id) {
            $supplier__query = "and inv_it.item_id in( select DISTINCT(item_id) from receive_stock where receive_stock_invoice_id in (select id from  receive_stock_invoices where supplier_id=" . $supplier_id . "))";
        }
        if ($category_id == 0 && $category_parent_id == 0) {
            $query = "select COALESCE(sum(inv_it.final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and (inv.auto_closed=1 || inv.closed=0) and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " " . $whole_sale . " " . $supplier__query;
        } else {
            if ($category_id == 0 && 0 < $category_parent_id) {
                $query = "select COALESCE(sum(inv_it.final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and (inv.auto_closed=1 || inv.closed=0) and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in ( select id from items where item_category in (select id from items_categories where parent=" . $category_parent_id . ")) " . $whole_sale . " " . $supplier__query;
            } else {
                $query = "select COALESCE(sum(inv_it.final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and (inv.auto_closed=1 || inv.closed=0) and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ") " . $whole_sale . " " . $supplier__query;
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInfoTotalInvoices($store_id, $date_range)
    {
        $query = "select COALESCE(sum(total_value), 0) as sum from invoices where official=1 and deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInfoTotalInvoices_limited($store_id, $date_range)
    {
        $query = "select COALESCE(sum(total_value), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and store_id=" . $store_id . " and employee_id in (select id from users where role_id=4)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInfoTotalInvoicesVat($store_id, $date_range)
    {
        $query = "select COALESCE(sum( (total_value+invoice_discount)*vat_value-(total_value+invoice_discount) ), 0) as sum from invoices where official=1 and deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInfoTotalInvoicesVat_limited($store_id, $date_range)
    {
        $query = "select COALESCE(sum(total_vat_value), 0) as sum from invoices where deleted=0 and other_branche=0 and date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and store_id=" . $store_id . " and employee_id in (select id from users where role_id=4)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalSalesInfo($store_id, $daterange, $category_id, $payment_method, $sales_type, $category_parent_id, $supplier_id)
    {
        $supplier__query = "";
        if (0 < $supplier_id) {
            $supplier__query = "and inv_it.item_id in( select DISTINCT(item_id) from receive_stock where receive_stock_invoice_id in (select id from  receive_stock_invoices where supplier_id=" . $supplier_id . "))";
        }
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        if ($category_id == 0 && $category_parent_id == 0) {
            $query = "select COALESCE(sum(inv_it.final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.id=inv_it.invoice_id and inv.payment_method=" . $payment_method . " and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " " . $whole_sale . " " . $supplier__query;
        } else {
            if ($category_id == 0 && 0 < $category_parent_id) {
                $query = "select COALESCE(sum(inv_it.final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.payment_method=" . $payment_method . " and inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category in (select id from items_categories where parent=" . $category_parent_id . ")) " . $whole_sale . " " . $supplier__query;
            } else {
                $query = "select COALESCE(sum(inv_it.final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.payment_method=" . $payment_method . " and inv.id=inv_it.invoice_id and inv_it.deleted=0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ") " . $whole_sale . " " . $supplier__query;
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalSales($store_id, $daterange, $category_id, $payment_method)
    {
        if ($category_id == 0) {
            if ($_SESSION["role"] == 3) {
                $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where payment_method=" . $payment_method . " and  deleted=0 and other_branche=0 and official=1 and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id;
            } else {
                $query = "select COALESCE(sum((total_value+invoice_discount)+((total_value+invoice_discount)*(tax/100))+freight), 0) as sum from invoices where payment_method=" . $payment_method . " and  deleted=0 and other_branche=0 and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id;
            }
        } else {
            if ($_SESSION["role"] == 3) {
                $query = "select COALESCE(sum(final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.payment_method=" . $payment_method . " and inv.id=inv_it.invoice_id and inv.official=1 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ")";
            } else {
                $query = "select COALESCE(sum(final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where inv.payment_method=" . $payment_method . " and inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and inv_it.item_id in (select id from items where item_category=" . $category_id . ")";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalSales_limited($store_id, $daterange, $category_id, $payment_method)
    {
        if ($category_id == 0) {
            $query = "select COALESCE(sum(total_value_limited), 0) as sum from invoices where payment_method=" . $payment_method . " and deleted=0 and other_branche=0 and date(creation_date)>='" . $daterange[0] . "' and date(creation_date)<='" . $daterange[1] . "' and store_id=" . $store_id . " and employee_id in (select id from users where role_id=4)";
        } else {
            $query = "select COALESCE(sum(final_price_disc_qty), 0) as sum from invoices inv,invoice_items inv_it where where inv.payment_method=" . $payment_method . " inv.id=inv_it.invoice_id and inv.deleted=0 and inv.other_branche=0 and other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " and user_role=4 and inv_it.item_id in (select id from items where item_category=" . $category_id . ")";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalManualDiscount($store_id, $daterange, $sales_type, $supplier_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        if ($_SESSION["role"] == 3) {
            $query = "select COALESCE(sum(inv.invoice_discount), 0) as sum from invoices inv where inv.deleted=0 and inv.other_branche=0 and inv.official=1 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " " . $whole_sale;
        } else {
            $query = "select COALESCE(sum(inv.invoice_discount), 0) as sum from invoices inv where inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " " . $whole_sale;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function totalManualDiscount_PL($store_id, $daterange, $sales_type, $supplier_id)
    {
        $whole_sale = self::prepare_qry_sales_type($sales_type);
        if ($_SESSION["role"] == 3) {
            $query = "select COALESCE(sum(inv.profit_after_discount), 0) as sum from invoices inv where inv.profit_after_discount<0 and inv.deleted=0 and inv.other_branche=0 and inv.official=1 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " " . $whole_sale;
        } else {
            $query = "select COALESCE(sum(inv.profit_after_discount), 0) as sum from invoices inv where inv.profit_after_discount<0 and inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)>='" . $daterange[0] . "' and date(inv.creation_date)<='" . $daterange[1] . "' and inv.store_id=" . $store_id . " " . $whole_sale;
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function invoice_total_value($store_id, $rdate)
    {
        $query = "select COALESCE(sum(total_value), 0) as sum from invoices inv where inv.deleted=0 and inv.other_branche=0 and date(inv.creation_date)='" . $rdate . "' and inv.store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportOfReturningItems($store_id, $date_range, $vendor_id)
    {
        $query = "select * from returned_purchases rp where rp.deleted=0 and date(rp.return_date)>='" . $date_range[0] . "' and date(rp.return_date)<='" . $date_range[1] . "' and rp.returned_by_vendor_id=" . $vendor_id . " and rp.returned_to_store_id=" . $store_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTopCustomers($store_id)
    {
        $query = "SELECT customer_id,sum(total_value) as total_v,sum(invoice_discount) as total_disc,sum(total_profit) as total_p,sum(profit_after_discount) as total_p_after_discount FROM invoices where deleted=0 and other_branche=0 and `customer_id` is not NULL group by customer_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportBestSeller($store_id, $date_range, $category_id)
    {
        if ($category_id == 0) {
            $query = "SELECT item_id,sum(qty) as qty,sum(profit) as profit,selling_price,buying_cost,vat,discount FROM invoice_items where invoice_id in (select id from invoices where date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and store_id=" . $store_id . " and other_branche=0) and item_id in (select id from items where is_composite=0) group by item_id order by qty desc";
        } else {
            $query = "SELECT item_id,sum(qty) as qty,sum(profit) as profit,selling_price,buying_cost,vat,discount FROM invoice_items where invoice_id in (select id from invoices where date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and store_id=" . $store_id . " and other_branche=0) and item_id in (select id from items where item_category=" . $category_id . " and is_composite=0) group by item_id order by qty desc";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getReportBestSeller_by_box($item_id, $date_range, $store_id, $category_id)
    {
        $return = array();
        $return["total_qty"] = 0;
        $return["total_profit"] = 0;
        $t = 0;
        $query = "select composite_item_id,qty from items_composite where item_id=" . $item_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        for ($i = 0; $i < count($result); $i++) {
            if ($category_id == 0) {
                $query__ = "SELECT item_id,COALESCE(sum(qty),0) as qty,sum(profit) as profit,selling_price,buying_cost,vat,discount FROM invoice_items where invoice_id in (select id from invoices where date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and store_id=" . $store_id . " and other_branche=0) and item_id=" . $result[$i]["composite_item_id"];
            } else {
                $query__ = "SELECT item_id,COALESCE(sum(qty),0) as qty,sum(profit) as profit,selling_price,buying_cost,vat,discount FROM invoice_items where invoice_id in (select id from invoices where date(creation_date)>='" . $date_range[0] . "' and date(creation_date)<='" . $date_range[1] . "' and store_id=" . $store_id . " and other_branche=0) and item_id=" . $result[$i]["composite_item_id"];
            }
            $result__ = my_sql::fetch_assoc(my_sql::query($query__));
            if (0 < count($result__)) {
                $return["total_qty"] += $result__[0]["qty"] * $result[$i]["qty"];
                $return["total_profit"] += $result__[0]["profit"];
            }
        }
        return $return;
    }
}

?>