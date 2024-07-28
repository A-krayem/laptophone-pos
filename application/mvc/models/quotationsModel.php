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
class quotationsModel
{
    public function getAllQuotationsSwitch($daterange, $filter_user, $filter_status, $customer_id, $items)
    {
        $itemsCondition = "";
        if ($items) {
            foreach ($items as $item) {
                if ($item) {
                    $itemsCondition .= "and quotations.id in (SELECT quotation_id from quotation_details where item_id=" . $item . " and deleted=0 )";
                }
            }
        }
        $filter_user_condition = "";
        if (0 < $filter_user) {
            $filter_user_condition = " and quotations.created_by=" . $filter_user . " ";
        }
        $customer_filter = "";
        if ($customer_id) {
            $customer_filter = " and customer_id=" . $customer_id;
        }
        if ($filter_status == 1) {
            $deletedFilter = "and  quotations.deleted=1 ";
        } else {
            if ($filter_status == 2) {
                $deletedFilter = "and  quotations.expiery_date<NOW() and quotations.expiery_date>0 and quotations.expiery_date is not null";
            } else {
                if ($filter_status == 3) {
                    $deletedFilter = "and quotations.invoice_id IS NULL and quotations.deleted=0";
                } else {
                    if ($filter_status == 4) {
                        $deletedFilter = "and quotations.invoice_id IS NOT NULL and quotations.deleted=0";
                    } else {
                        $deletedFilter = " and quotations.deleted=0 and (quotations.expiery_date>NOW() or quotations.expiery_date=0 or quotations.expiery_date is null)";
                    }
                }
            }
        }
        if ($_SESSION["role"] == 2) {
            $query = "select quotations.*,u.username as sales_first_name,customers.name customer_name,customers.middle_name customer_middle_name,customers.last_name customer_last_name from quotations left join users u on u.id=quotations.created_by left join customers on customers.id=quotations.customer_id where 1 and quotations.created_by=" . $_SESSION["id"] . " and  date(quotations.creation_date)>='" . $daterange[0] . "' and date(quotations.creation_date)<='" . $daterange[1] . "' " . $customer_filter . " " . $filter_user_condition . (string) $deletedFilter . " " . $itemsCondition . " order by id asc";
        } else {
            $query = "select quotations.*,u.username as sales_first_name,customers.name customer_name,customers.middle_name customer_middle_name,customers.last_name customer_last_name from quotations left join users u on u.id=quotations.created_by left join customers on customers.id=quotations.customer_id where 1 and (submitted=1 or quotations.created_by=" . $_SESSION["id"] . ") and  date(quotations.creation_date)>='" . $daterange[0] . "' and date(quotations.creation_date)<='" . $daterange[1] . "' " . $customer_filter . " " . $filter_user_condition . (string) $deletedFilter . " " . $itemsCondition . " order by id asc";
        }
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function generate_empty_quotation($store_id, $created_by, $vat)
    {
        $vendor_commission_percentage = 0;
        $query = "select * from users where id=" . $created_by;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (0 < count($result)) {
            $vendor_commission_percentage = $result[0]["commission"];
        }
        my_sql::query("insert into quotations(creation_date,customer_id,store_id,created_by,sub_total,discount,vat,total,profit,vendor_commission_percentage) values (now(),null," . $store_id . "," . $created_by . ",0,0," . $vat . ",0,0," . $vendor_commission_percentage . ")");
        return my_sql::get_mysqli_insert_id();
    }
    public function get_quotation_created_by($invoice_id)
    {
        $query = "select * from quotations where invoice_id=" . $invoice_id . " and deleted=0 ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function addItemsToQuotation($info)
    {
        $query = "insert into quotation_details(quotation_id,item_id,buying_cost,qty,selling_price,discount,vat,vat_value,final_price,final_cost,profit) values('" . $info["quotation_id"] . "','" . $info["item_id"] . "','" . $info["buying_cost"] . "','" . $info["qty"] . "','" . $info["selling_price"] . "','" . $info["discount"] . "','" . $info["vat"] . "','" . $info["vat_value"] . "','" . $info["final_price"] . "','" . $info["final_cost"] . "','" . $info["profit"] . "');";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function getItemsOfQuotation($quotation_id)
    {
        $query = "select * from quotation_details where quotation_id=" . $quotation_id . " and deleted=0 order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsOfQuotationDetailed($quotation_id)
    {
        $query = "SELECT quotation_details.*,items.description,items.sku_code,items.is_composite from quotation_details left join items on items.id=quotation_details.item_id where quotation_details.quotation_id=" . $quotation_id . " and quotation_details.deleted=0 order by quotation_details.id asc";
        return my_sql::fetch_assoc(my_sql::query($query));
    }
    public function save_manual_quotation_items($info)
    {
        $query = "update quotation_details set additional_description='" . $info["description"] . "',profit='" . $info["profit"] . "',vat_value='" . $info["vat_value"] . "',final_price='" . $info["final_price"] . "', final_cost='" . $info["final_cost"] . "', selling_price='" . $info["selling_price"] . "' ,discount='" . $info["discount"] . "' ,vat='" . $info["vat"] . "' ,qty='" . $info["qty"] . "' where id='" . $info["quotation_item_id"] . "'";
        my_sql::query($query);
    }
    public function get_item_from_quotation($id)
    {
        $query = "select * from quotation_details where id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_payment_by_quotation_id($id)
    {
        $query = "select * from customer_balance where quotation_id=" . $id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function delete_item_from_manual_quotation($invoice_item_id)
    {
        $query = "update quotation_details set deleted=1 where id=" . $invoice_item_id;
        my_sql::query($query);
    }
    public function update_quotation_type($quotation_id, $type_id)
    {
        $query = "update quotations set quotation_type=" . $type_id . " where id=" . $quotation_id;
        my_sql::query($query);
    }
    public function getQuotationById($id)
    {
        $query = "select * from quotations where id= " . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function calculate_total_value_with_vat($quotation_id)
    {
        $amount = self::getAmount($quotation_id);
        $amount_vat_diff = self::getAmountVatDiff($quotation_id);
        $query = "update quotations set sub_total=" . ($amount[0]["sum"] + $amount_vat_diff[0]["sum"]) . ", total=" . ($amount[0]["sum"] + $amount_vat_diff[0]["sum"]) . "-discount where id= " . $quotation_id;
        my_sql::query($query);
        return $amount[0]["sum"] + $amount_vat_diff[0]["sum"];
    }
    public function getAmount($quotation_id)
    {
        $query = "select COALESCE(sum(final_price), 0) as sum from quotation_details where quotation_id= " . $quotation_id . " and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getItemsOfInvoice($quotation_id)
    {
        $query = "select * from quotation_details where quotation_id=" . $quotation_id . " and deleted=0 order by id asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_quotation_items_profit($quotation_id)
    {
        my_sql::query("UPDATE quotation_details set final_cost=buying_cost*qty, profit=(final_price/(case vat when 1 then vat_value else 1 end))-final_cost where quotation_id=" . $quotation_id);
    }
    public function getAmountVatDiff($quotation_id)
    {
        $query = "select COALESCE(sum(final_price*(vat_value-1)), 0) as sum from quotation_details where quotation_id= " . $quotation_id . " and deleted=0 and vat=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function calculate_total_profit_for_quotation($quotation_id)
    {
        my_sql::query("update quotations set profit =(SELECT COALESCE(SUM(Profit),0) from quotation_details where quotation_id=quotations.id  and quotation_details.deleted=0)-discount where quotations.id=" . $quotation_id);
    }
    public function delete_quotation($quotation_id)
    {
        my_sql::query("update quotations set deleted=1 where id=" . $quotation_id);
        my_sql::query("INSERT INTO quotations_log(creation_date,log,quotation_id,created_by) values(now(),'[\\'deleted\\']','" . $quotation_id . "'," . $_SESSION["id"] . ")");
    }
    public function update_quotation_buying_cost($quotation_id)
    {
        my_sql::query("UPDATE quotation_details set buying_cost= (SELECT buying_cost from items where id=quotation_details.item_id) where quotation_id=" . $quotation_id . " ");
    }
    public function calculate_total_value($quotation_id)
    {
        $amount = self::getAmount($quotation_id);
        my_sql::query("update quotations set sub_total =(SELECT COALESCE(SUM(final_price),0) from quotation_details where quotation_id=quotations.id  and quotation_details.deleted=0),total =((SELECT COALESCE(SUM(final_price),0) from quotation_details where quotation_id=quotations.id  and quotation_details.deleted=0)-discount) where quotations.id=" . $quotation_id);
        my_sql::query("update quotations set discount=0 where total=0 and id=" . $quotation_id);
        return $amount[0]["sum"];
    }
    public function update_quotation_info_manual($quotation_id, $quotation_discount, $note, $rate, $expiery_date, $customer_id)
    {
        $query = "update quotations set  discount='" . $quotation_discount . "',note='" . $note . "',rate= '" . $rate . "',customer_id=" . ($customer_id ? $customer_id : "NULL") . ",submitted=1 where id= " . $quotation_id;
        my_sql::query($query);
        if (0 < strlen($expiery_date)) {
            $query = "update quotations set expiery_date=NULL where id= " . $quotation_id;
        } else {
            $query = "update quotations set expiery_date='" . $expiery_date . "' where id= " . $quotation_id;
        }
        my_sql::query($query);
    }
    public function update_add_item_description($quotation_id, $description)
    {
        $query = "update quotation_details set additional_description='" . $description . "' where id=" . $quotation_id;
        my_sql::query($query);
    }
    public function getQuotationItemsDetails($quotation_id)
    {
        $info = array();
        $info["quotation"] = self::getQuotationById($quotation_id);
        $info["quotation_items"] = self::getItemsOfQuotation($quotation_id);
        return $info;
    }
    public function setInvoiceId($invoice_id, $quotation_id)
    {
        my_sql::query("UPDATE quotations set invoice_id =" . $invoice_id . " where id =" . $quotation_id);
    }
    public function saveLog($log, $quotation_id)
    {
        my_sql::query("INSERT INTO quotations_log(creation_date,log,quotation_id,created_by) values(now(),'" . $log . "','" . $quotation_id . "'," . $_SESSION["id"] . ")");
    }
    public function get_pending_nb()
    {
        $query = "select count(id) as num from quotations where deleted=0  and submitted=1 and invoice_id IS NULL";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }
}

?>