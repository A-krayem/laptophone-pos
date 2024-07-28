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
class deliveryModel
{
    public function search_wb_number($wb_number)
    {
        $query = "select * from plugin_delivery_details where wb_number='" . $wb_number . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function wb_count($wb_number)
    {
        $query = "select count(id) as sum from plugin_delivery_details where wb_number='" . $wb_number . "' and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getAllDeliveries($date_filter)
    {
        $query = "select * from plugin_deliveries where deleted=0  ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllPackages($date_filter)
    {
        $query = "select * from plugin_delivery_details where deleted=0 and delivery_id in ( select id from plugin_deliveries where deleted=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_delivery_sheet_id($id)
    {
        $query = "select * from plugin_deliveries where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_all_deliveries_sheets()
    {
        $query = "select * from plugin_deliveries";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_supplier_in_purshace_invoice($delivery_id, $supplier_id)
    {
        $query = "update receive_stock_invoices set supplier_id=" . $supplier_id . " where invoice_reference=" . $delivery_id;
        my_sql::query($query);
    }
    public function get_deliveryid_of_itemdelivery_id($_item_d_id)
    {
        $query = "select * from plugin_delivery_details where id=" . $_item_d_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_sum($date_filter)
    {
        $query = "SELECT delivery_id,COALESCE(sum(collection_value), 0) as collection_value, COALESCE(sum(delivery_charge), 0) as delivery_charge,COALESCE(sum(pickapp_share), 0) as pickapp_share,COALESCE(sum(our_share), 0) as our_share, COALESCE(sum(net_amout), 0) as net_amout, COALESCE(sum(status), 0) as delivered, count(delivery_id) as del_num FROM plugin_delivery_details where deleted=0 and delivery_id in (select id from plugin_deliveries where deleted=0) group by delivery_id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function hide($delivery_sheet_id)
    {
        $query = "update plugin_delivery_details set hide=1 where delivery_id=" . $delivery_sheet_id . " and paid_supplier=1";
        my_sql::query($query);
    }
    public function get_all_delivery_items($delivery_sheet_id)
    {
        $query = "select * from plugin_delivery_details where deleted=0 and hide=0 and delivery_id=" . $delivery_sheet_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_sum_of_all_delivery_items_to_partial_print($delivery_sheet_id)
    {
        $query = "select COALESCE(sum(net_amout), 0) as sum_net_amount from plugin_delivery_details where deleted=0 and hide=0 and delivery_id=" . $delivery_sheet_id . " and print_group=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum_net_amount"];
    }
    public function get_all_delivery_items_to_partial_print($delivery_sheet_id)
    {
        $query = "select * from plugin_delivery_details where deleted=0 and delivery_id=" . $delivery_sheet_id . " and print_group=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            return $result;
        }
        $query_ = "SELECT max(print_group) as max_p FROM plugin_delivery_details where delivery_id=" . $delivery_sheet_id;
        $result_ = my_sql::fetch_assoc(my_sql::query($query_));
        if (0 < count($result_)) {
            $query__ = "select * from plugin_delivery_details where deleted=0 and delivery_id=" . $delivery_sheet_id . " and print_group=" . $result_[0]["max_p"];
            $result__ = my_sql::fetch_assoc(my_sql::query($query__));
            return $result__;
        }
        return array();
    }
    public function get_all_delivery_items_all_print($delivery_sheet_id)
    {
        $query = "select * from plugin_delivery_details where deleted=0 and delivery_id=" . $delivery_sheet_id . "";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getTotalDeliveryProfit($daterange)
    {
        $query = "select COALESCE(abs(sum(delivery_charge-pickapp_share)), 0) as sum from plugin_delivery_details where deleted=0 and date(sending_date)>='" . $daterange[0] . "' and date(sending_date)<='" . $daterange[1] . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalCollection($supplier_id, $paid_filter, $delivery_filter)
    {
        $supplier_id_query = "";
        if (0 < $supplier_id) {
            $supplier_id_query = " and supplier_id=" . $supplier_id;
        }
        $delivery_query = "";
        if (0 < $delivery_filter) {
            if ($delivery_filter == 1) {
                $delivery_query = " and pickapp_share=0 ";
            }
            if ($delivery_filter == 2) {
                $delivery_query = " and pickapp_share>0 ";
            }
        }
        if ($paid_filter == 1) {
            $query = "select COALESCE(abs(sum(collection_value)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=1 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
        } else {
            if ($paid_filter == 2) {
                $query = "select COALESCE(abs(sum(collection_value)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            } else {
                $query = "select COALESCE(abs(sum(collection_value)), 0) as sum from plugin_delivery_details where deleted=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalDelivery_charge($supplier_id, $paid_filter, $delivery_filter)
    {
        $supplier_id_query = "";
        if (0 < $supplier_id) {
            $supplier_id_query = " and supplier_id=" . $supplier_id;
        }
        $delivery_query = "";
        if (0 < $delivery_filter) {
            if ($delivery_filter == 1) {
                $delivery_query = " and pickapp_share=0 ";
            }
            if ($delivery_filter == 2) {
                $delivery_query = " and pickapp_share>0 ";
            }
        }
        if ($paid_filter == 1) {
            $query = "select COALESCE(abs(sum(delivery_charge)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=1 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
        } else {
            if ($paid_filter == 2) {
                $query = "select COALESCE(abs(sum(delivery_charge)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            } else {
                $query = "select COALESCE(abs(sum(delivery_charge)), 0) as sum from plugin_delivery_details where deleted=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalNetAmount($supplier_id, $paid_filter, $delivery_filter)
    {
        $supplier_id_query = "";
        if (0 < $supplier_id) {
            $supplier_id_query = " and supplier_id=" . $supplier_id;
        }
        $delivery_query = "";
        if (0 < $delivery_filter) {
            if ($delivery_filter == 1) {
                $delivery_query = " and pickapp_share=0 ";
            }
            if ($delivery_filter == 2) {
                $delivery_query = " and pickapp_share>0 ";
            }
        }
        if ($paid_filter == 1) {
            $query = "select COALESCE(abs(sum(net_amout)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=1 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
        } else {
            if ($paid_filter == 2) {
                $query = "select COALESCE(abs(sum(net_amout)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            } else {
                $query = "select COALESCE(abs(sum(net_amout)), 0) as sum from plugin_delivery_details where deleted=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getAllPackagesForSupplier($supplier_id, $paid_filter, $delivery_filter)
    {
        $supplier_id_query = "";
        if (0 < $supplier_id) {
            $supplier_id_query = " and supplier_id=" . $supplier_id;
        }
        $delivery_query = "";
        if (0 < $delivery_filter) {
            if ($delivery_filter == 1) {
                $delivery_query = " and pickapp_share=0 ";
            }
            if ($delivery_filter == 2) {
                $delivery_query = " and pickapp_share>0 ";
            }
        }
        if ($paid_filter == 0) {
            $query = "select * from plugin_delivery_details where deleted=0 and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . " " . $delivery_query . ") order by sending_date desc";
        }
        if ($paid_filter == 1) {
            $query = "select * from plugin_delivery_details where deleted=0 and paid_supplier=1 and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . " " . $delivery_query . ") order by sending_date desc";
        }
        if ($paid_filter == 2) {
            $query = "select * from plugin_delivery_details where deleted=0 and paid_supplier=0 and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . " " . $delivery_query . ") order by sending_date desc";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function create_new_delivery()
    {
        $query = "insert into plugin_deliveries (creation_date,supplier_id) values('" . my_sql::datetime_now() . "',0)";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function getTotalPickapp_share($supplier_id, $paid_filter, $delivery_filter)
    {
        $supplier_id_query = "";
        if (0 < $supplier_id) {
            $supplier_id_query = " and supplier_id=" . $supplier_id;
        }
        $delivery_query = "";
        if (0 < $delivery_filter) {
            if ($delivery_filter == 1) {
                $delivery_query = " and pickapp_share=0 ";
            }
            if ($delivery_filter == 2) {
                $delivery_query = " and pickapp_share>0 ";
            }
        }
        if ($paid_filter == 1) {
            $query = "select COALESCE(abs(sum(pickapp_share)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=1 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
        } else {
            if ($paid_filter == 2) {
                $query = "select COALESCE(abs(sum(pickapp_share)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            } else {
                $query = "select COALESCE(abs(sum(pickapp_share)), 0) as sum from plugin_delivery_details where deleted=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function getTotalOur_share($supplier_id, $paid_filter, $delivery_filter)
    {
        $supplier_id_query = "";
        if (0 < $supplier_id) {
            $supplier_id_query = " and supplier_id=" . $supplier_id;
        }
        $delivery_query = "";
        if (0 < $delivery_filter) {
            if ($delivery_filter == 1) {
                $delivery_query = " and pickapp_share=0 ";
            }
            if ($delivery_filter == 2) {
                $delivery_query = " and pickapp_share>0 ";
            }
        }
        if ($paid_filter == 1) {
            $query = "select COALESCE(abs(sum(our_share)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=1 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
        } else {
            if ($paid_filter == 2) {
                $query = "select COALESCE(abs(sum(our_share)), 0) as sum from plugin_delivery_details where deleted=0 and paid_supplier=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            } else {
                $query = "select COALESCE(abs(sum(our_share)), 0) as sum from plugin_delivery_details where deleted=0 " . $delivery_query . " and delivery_id in (select id from plugin_deliveries where deleted=0 " . $supplier_id_query . ")";
            }
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_total_cod_for_supplier_id($supplier_id)
    {
        $query = "select COALESCE(abs(sum(net_amout)), 0) as sum from plugin_delivery_details where deleted=0 and status=1 and delivery_id in (select id from plugin_deliveries where deleted=0 and supplier_id=" . $supplier_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function get_total_not_cod_for_supplier_id($supplier_id)
    {
        $query = "select COALESCE(abs(sum(net_amout)), 0) as sum from plugin_delivery_details where deleted=0 and status=0 and delivery_id in (select id from plugin_deliveries where deleted=0 and supplier_id=" . $supplier_id . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["sum"];
    }
    public function add_new_delivery_package($delivery_sheet_id)
    {
        $query = "insert into plugin_delivery_details(customer_id,sending_date,wb_number,collection_value,delivery_charge,net_amout,status,delivery_id) values(0,'" . my_sql::datetime_now() . "',0,0,0,0,0," . $delivery_sheet_id . ")";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function package_delivered($package_id, $status)
    {
        $query = "update plugin_delivery_details set status=" . $status . " where id=" . $package_id;
        my_sql::query($query);
        $query = "update plugin_delivery_details set cod_date='" . my_sql::datetime_now() . "' where id=" . $package_id . " and cod_date is null";
        my_sql::query($query);
    }
    public function package_supplier_paid($package_id, $status)
    {
        $print_group = -1;
        if ($status == 1) {
            $print_group = 0;
        }
        $query = "update plugin_delivery_details set paid_supplier=" . $status . ",paid_date='" . my_sql::datetime_now() . "',user_id=" . $_SESSION["id"] . ",print_group=" . $print_group . " where id=" . $package_id;
        my_sql::query($query);
    }
    public function update_supplier_delivery($delivery_id, $supplier_id)
    {
        $query = "update plugin_deliveries set supplier_id=" . $supplier_id . " where id=" . $delivery_id;
        my_sql::query($query);
    }
    public function customer_changed_delivery($delivery_item_id, $customer_id)
    {
        $query = "update plugin_delivery_details set customer_id=" . $customer_id . " where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function update_sending_date($delivery_item_id, $sending_date)
    {
        $query = "update plugin_delivery_details set sending_date='" . $sending_date . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function wb_changed($delivery_item_id, $wb)
    {
        $query = "update plugin_delivery_details set wb_number='" . $wb . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function delete_delivery_item($delivery_item_id)
    {
        $query = "update plugin_delivery_details set deleted=1 where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function delete_sheet($sheet_id)
    {
        $query = "update plugin_deliveries set deleted=1 where id=" . $sheet_id;
        my_sql::query($query);
        $query = "update plugin_delivery_details set deleted=1 where delivery_id=" . $sheet_id;
        my_sql::query($query);
    }
    public function collection_changed($delivery_item_id, $col)
    {
        $query = "update plugin_delivery_details set collection_value='" . $col . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function deliverycharge_changed($delivery_item_id, $dc)
    {
        $query = "update plugin_delivery_details set delivery_charge='" . $dc . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function netamount_changed($delivery_item_id, $na)
    {
        $query = "update plugin_delivery_details set net_amout\t='" . $na . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function pickappshare_changed($delivery_item_id, $dc)
    {
        $query = "update plugin_delivery_details set pickapp_share='" . $dc . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function ourshare_changed($delivery_item_id, $dc)
    {
        $query = "update plugin_delivery_details set our_share='" . $dc . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function cusname_changed($delivery_item_id, $txt)
    {
        $query = "update plugin_delivery_details set customer_name='" . $txt . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function cusaddr_changed($delivery_item_id, $txt)
    {
        $query = "update plugin_delivery_details set customer_address='" . $txt . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function cusphone_changed($delivery_item_id, $txt)
    {
        $query = "update plugin_delivery_details set customer_phone='" . $txt . "' where id=" . $delivery_item_id;
        my_sql::query($query);
    }
    public function update_print($sheet_id)
    {
        $query = "SELECT count(id) as num FROM plugin_delivery_details where print_group=0 and delivery_id=" . $sheet_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < $result[0]["num"]) {
            $query_ = "SELECT max(print_group) as max_p FROM plugin_delivery_details where delivery_id=" . $sheet_id;
            $result_ = my_sql::fetch_assoc(my_sql::query($query_));
            my_sql::query("update plugin_delivery_details set print_group=" . ($result_[0]["max_p"] + 1) . " where print_group=0 and delivery_id=" . $sheet_id);
        }
    }
    public function update_pi_delivery_sheet($sheet_id)
    {
        $query_ = "select COALESCE(abs(sum(net_amout)), 0) as net_amount_sum from plugin_delivery_details where deleted=0 and delivery_id=" . $sheet_id;
        $result_ = my_sql::fetch_assoc(my_sql::query($query_));
        $query = "update receive_stock_invoices set total=" . $result_[0]["net_amount_sum"] . " where invoice_reference='" . $sheet_id . "'";
        my_sql::query($query);
    }
}

?>