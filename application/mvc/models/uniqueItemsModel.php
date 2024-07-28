<?php

class uniqueItemsModel
{
    public function getAll($itemID)
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT unique_items.*,suppliers.name as supplier_name,customers.name as customer_name FROM unique_items left join suppliers on suppliers.id=unique_items.supplier_id left join customers on customers.id=unique_items.customer_id where unique_items.deleted=0 and item_id=$itemID order by unique_items.id asc"));
    }

    public function getAll__($itemID, $only_not_paid)
    {
        $customer_filter = "";
        if ($only_not_paid == 1) {
            $customer_filter = " and customer_id=0 and invoice_id=0 ";
        }
        return my_sql::fetch_assoc(my_sql::query("SELECT unique_items.*,suppliers.name as supplier_name,customers.name as customer_name FROM unique_items left join suppliers on suppliers.id=unique_items.supplier_id left join customers on customers.id=unique_items.customer_id where unique_items.deleted=0 and item_id=$itemID $customer_filter order by unique_items.id asc"));
    }
    
    public function update_registered_store($store_id){
        my_sql::query("update unique_items set registered_in_store_id=".$store_id." where customer_id>0");
        my_sql::query("update unique_items set registered_in_store_id=0 where customer_id=0 and invoice_id=0 and registered_in_store_id>0");
    }
    
    public function getAll__centralize($itemID, $only_not_paid)
    {
        $customer_filter = "";
        if ($only_not_paid == 1) {
            $customer_filter = " and customer_id=0 and invoice_id=0 ";
        }
        return my_sql::fetch_assoc(my_sql::query("SELECT unique_items.*,suppliers.name as supplier_name,'CN' as customer_name FROM unique_items left join suppliers on suppliers.id=unique_items.supplier_id  where unique_items.deleted=0 and item_id=$itemID $customer_filter order by unique_items.id asc"));
    }
    
    public function getAll__V2($itemID, $only_not_paid)
    {
        $customer_filter = "";
        if ($only_not_paid == 1) {
            $customer_filter = " and customer_id=0 and invoice_id=0 ";
        }
        return my_sql::fetch_assoc(my_sql::query("SELECT unique_items.*,suppliers.name as supplier_name,global_clients.id as customer_name FROM unique_items left join suppliers on suppliers.id=unique_items.supplier_id left join global_clients on global_clients.client_id=unique_items.customer_id and global_clients.store_id=unique_items.registered_in_store_id where unique_items.deleted=0 and item_id=$itemID $customer_filter order by unique_items.id asc"));
    }
    
    public function remote_customer_of_imei($cnx,$unique_id){
        $query="select * from unique_items where id=".$unique_id;
        
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        if(count($result)>0){
            if($result[0]["registered_in_store_id"]>0){
                return $result;
            }
        }
        return array();
    }
    
    public function get_customer_info_remote($cnx_to_store,$customer_id){
        $query="select * from customers where id=".$customer_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx_to_store));
        return $result;
    }
    
    public function get_remote_customer($cnx,$customer_id){
        $query="select name,middle_name,last_name from customers where id=".$customer_id;
        $result = my_sql::fetch_assoc(my_sql::custom_connection_query($query, $cnx));
        return $result;
    }
    
    public function ignore_imei_registration(){
         my_sql::query("UPDATE invoices set log_note='". json_encode($_SESSION["assign_codes"])."' where id=".$_SESSION["inv_id"]);

    }

    public function get_items_imei_by_array($item_ids)
    {
        if (count($item_ids) == 0) {
            return array();
        }

        $query = "select ui.item_id,ui.supplier_id,ui.customer_id,ui.code1,ui.code2,ui.creation_date,ui.invoice_id,cs.name as c_first_name,cs.middle_name as c_middle_name,cs.last_name as c_last_name,it.description from unique_items ui left join customers cs on cs.id=ui.customer_id left join items it on it.id=ui.item_id where ui.deleted=0 and ui.invoice_id=0 and customer_id=0 and ui.item_id in (" . implode(",", $item_ids) . ") ";
        //echo $query;exit;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function check_if_have_codes($items_info)
    {
        $items_ids = array();
        $items_qty_array = array();
        for ($i = 0; $i < count($items_info); $i++) {
            array_push($items_ids, $items_info[$i]["item_id"]);
            $items_qty_array[$items_info[$i]["item_id"]] = $items_info[$i]["qty"];
        }
        $query = "select DISTINCT(item_id) from unique_items where deleted=0 and item_id in (" . implode(",", $items_ids) . ")";
        $result = my_sql::fetch_assoc(my_sql::query($query));

        $return = array();
        for ($i = 0; $i < count($result); $i++) {
            array_push($return, array("item_id" => $result[$i]["item_id"], "item_id_qty" => $items_qty_array[$result[$i]["item_id"]]));
        }
        return $return;
    }
    
    public function check_availibility($imei,$imeip){
        if($imeip==1){
            $query = "select count(id) as num from unique_items where (code1='".$imei."' or code2='".$imei."') and deleted=0";
        }else{
            $query = "select count(id) as num from unique_items where (code1='".$imei."' or code2='".$imei."') and deleted=0";
        }
 
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["num"];
    }

    public function get_details($date_range, $item_id)
    {
        if($_SESSION['global_admin_exist']==0){
            $query = "select ui.item_id,ui.supplier_id,ui.customer_id,ui.code1,ui.code2,ui.creation_date,ui.invoice_id,cs.name as c_first_name,cs.middle_name as c_middle_name,cs.last_name as c_last_name,it.description,ui.pi_id from unique_items ui left join customers cs on cs.id=ui.customer_id left join items it on it.id=ui.item_id left join invoices inv on inv.id=ui.invoice_id where ui.deleted=0 and date(inv.creation_date)>='" . $date_range[0] . "' and date(inv.creation_date)<='" . $date_range[1] . "' and ui.invoice_id>0";
        }else{
            $query = "select ui.item_id,ui.supplier_id,ui.customer_id,ui.code1,ui.code2,ui.creation_date,ui.invoice_id,cs.name as c_first_name,cs.middle_name as c_middle_name,cs.last_name as c_last_name,it.description,ui.pi_id from unique_items ui left join customers cs on cs.id=ui.customer_id left join items it on it.id=ui.item_id left join invoices inv on inv.id=ui.invoice_id where ui.deleted=0 and date(inv.creation_date)>='" . $date_range[0] . "' and date(inv.creation_date)<='" . $date_range[1] . "' and ui.invoice_id>0 and registered_in_store_id=".$_SESSION["store_id"];
        }
        
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }


    public function get_invoices_items($invoices_ids)
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT invoice_id,item_id,code1,code2 FROM unique_items where invoice_id in (" . implode(",", $invoices_ids) . ")"));
    }

    public function get_oldest()
    {
        $query = "select DATEDIFF('" . my_sql::datetime_now() . "', creation_date) as days from unique_items_history where invoice_id>0 order by creation_date asc limit 1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if(count($result)>0){
            return $result[0]["days"];
        }else{
            return 0;
        }
        
    }

    public function createNew($itemID, $qty)
    {
        $createdBy = $_SESSION["id"];
        //$values = array();
        $id=0;
        for ($i = 0; $i < $qty; $i++) {
            //array_pusH($values, "($itemID,'','',0,0,now(),$createdBy,0,0)");
            
            $query="INSERT into unique_items (item_id,code1,code2,supplier_id,customer_id,creation_date,created_by,is_defined,deleted) values ($itemID,'','',0,0,now(),$createdBy,0,0)";
            my_sql::query($query);

            $id = my_sql::get_mysqli_insert_id();
            if($id>0){
                my_sql::global_query_sync($query);
            }
            
        }
        return $id;
    }
    
    public function createNew_withPI($itemID, $qty,$pi_id,$supplier_id)
    {
        $createdBy = $_SESSION["id"];
        //$values = array();
        $id=0;
        
          
        
        for ($i = 0; $i < $qty; $i++) {
            //array_pusH($values, "($itemID,'','',0,0,now(),$createdBy,0,0)");
            
            $query="INSERT into unique_items (item_id,code1,code2,supplier_id,customer_id,creation_date,created_by,is_defined,deleted,pi_id) values ($itemID,'','',".$supplier_id.",0,now(),$createdBy,0,0,".$pi_id.")";
            my_sql::query($query);
      
        

            $id = my_sql::get_mysqli_insert_id();
            if($id>0){
                my_sql::global_query_sync($query);
            }
            
        }
        return $id;
    }
    
    public function delete($uniqueItemId)
    {
        self::storeLogInHisotry($uniqueItemId, "Unique Item Deleted");
        $query="UPDATE unique_items set deleted=1 where id=$uniqueItemId";
        my_sql::query($query);
        if (my_sql::get_mysqli_rows_num() > 0) {
            my_sql::global_query_sync($query);
        }
    }

    public function update_imei($unique_id, $imei_1, $imei_2, $supplier_id)
    {
        $query="UPDATE unique_items set code1='" . $imei_1 . "',code2='" . $imei_2 . "',is_defined=1,supplier_id=" . $supplier_id . " where id=$unique_id";
        my_sql::query($query);
        if (my_sql::get_mysqli_rows_num() > 0) {
            my_sql::global_query_sync($query);
        }
    }

    public function update($data)
    {
        $uniqueItemsToUpdate = my_sql::fetch_assoc(my_sql::query("SELECT * FROM unique_items where id in (" . implode(",", array_keys($data)) . ")"));

        $valuesToCheck = array("code1", "code2", "note", "supplier_id", "customer_id");
        foreach ($uniqueItemsToUpdate as $uniqueItemToUpdate) {
            $update = false;
            foreach ($valuesToCheck as $valueToCheck) {
                if ($uniqueItemToUpdate[$valueToCheck] != $data[$uniqueItemToUpdate["id"]][$valueToCheck]) {
                    $update[] = $valueToCheck;
                }
            }
            if ($update) {
                $newData = $data[$uniqueItemToUpdate["id"]];
                
                $supplier_id = $data[$uniqueItemToUpdate["id"]]["supplier_id"] ? $data[$uniqueItemToUpdate["id"]]["supplier_id"] : 0;
                $customer_id = $data[$uniqueItemToUpdate["id"]]["customer_id"] ? $data[$uniqueItemToUpdate["id"]]["customer_id"] : 0;
                $code1 = $data[$uniqueItemToUpdate["id"]]["code1"] ? $data[$uniqueItemToUpdate["id"]]["code1"] :  "";
                $code2 = $data[$uniqueItemToUpdate["id"]]["code2"] ? $data[$uniqueItemToUpdate["id"]]["code2"] :  "";
                $note = $data[$uniqueItemToUpdate["id"]]["note"] ? $data[$uniqueItemToUpdate["id"]]["note"] : "";
                $uniqueItemId = $uniqueItemToUpdate["id"];
                
                
                $query="UPDATE unique_items set supplier_id=$supplier_id,customer_id=$customer_id,code1='$code1',code2='$code2',note='$note',is_defined=1,warehouse_synced=0 where id=$uniqueItemId";
                my_sql::query($query);
                
                if (my_sql::get_mysqli_rows_num() > 0) {
                    my_sql::global_query_sync($query);
                }
                
                
                self::storeLogInHisotry($uniqueItemToUpdate["id"], implode(", ", array_map(function ($elm) use ($newData) {
                    return $elm . " updated to " . $newData[$elm];
                }, $update)));
            }
        }
    }
    public function storeLogInHisotry($uniqueItemId, $description = "")
    {
        $oldUniqueItem = my_sql::fetch_assoc(my_sql::query("SELECT * FROM unique_items where id=$uniqueItemId"));
        $updatedBy = $_SESSION['id'];
        //echo "INSERT into unique_items_history(" . implode(",", array_keys($oldUniqueItem[0])) . ",updated_by,updated_at,description) values('" . implode("','", $oldUniqueItem[0]) . "',$updatedBy,now(),'$description')";exit;
        
        $query="INSERT into unique_items_history(" . implode(",", array_keys($oldUniqueItem[0])) . ",updated_by,updated_at,description) values('" . implode("','", $oldUniqueItem[0]) . "',$updatedBy,now(),'$description')";
        my_sql::query($query);

        $last_id = my_sql::get_mysqli_insert_id();
        
        if ($last_id > 0) {
            my_sql::global_query_sync($query);
        }
                
        return $last_id;
    }
    public function clearUndefined($itemID)
    {
        $query="DELETE FROM unique_items where item_id=$itemID and is_defined=0";
        my_sql::query($query);
        if (my_sql::get_mysqli_rows_num() > 0) {
            my_sql::global_query_sync($query);
        }
    }
    public function search($term)
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT ui.*,items.description as item_description,items.barcode as item_barcode, items.second_barcode as item_barcode2,suppliers.name as supplier_name,customers.name as customer_name FROM unique_items ui left join items on items.id=ui.item_id left join customers on customers.id=ui.customer_id left join suppliers on suppliers.id=ui.supplier_id where ui.code1 like '%$term%' or  ui.code2 like '%$term%' or items.description like '%$term%' or items.barcode like '%$term%' or items.second_barcode like '%$term%'   or  ui.note like '%$term%' or ui.supplier_id in (select id from suppliers where name like '%$term%') or ui.customer_id in (SELECT id from customers where name like '%$term%' or phone like '%$term%')"));
    }
    public function serachByCode($code)
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT unique_items.*,items.description as item_description,items.barcode as item_barcode, suppliers.name as supplier_name FROM unique_items left join items on items.id=unique_items.item_id left join suppliers on suppliers.id=unique_items.supplier_id where items.deleted=0 and unique_items.deleted=0 and (unique_items.code1='$code' or unique_items.code2='$code')"));
    }
    public function updateCustomerIDOfUniqueItems($updates, $invoice_id)
    {
        $items = my_sql::fetch_assoc(my_sql::query("SELECT * FROM unique_items where id in (" . implode(",", array_keys($updates)) . ")"));
        $totalUpdated = 0;
        foreach ($items as $item) {
            //if ($item["customer_id"] != $updates[$item["id"]]) {
            $last_id = self::storeLogInHisotry($item["id"], "customer id changed to " . ($updates[$item["id"]] ? $updates[$item["id"]] : 0));
            
            
            my_sql::query("UPDATE unique_items set invoice_id='" . $invoice_id . "',customer_id='" . ($updates[$item["id"]] ? $updates[$item["id"]] : 0) . "' where id =" . $item["id"]);
            
            my_sql::query("UPDATE unique_items_history set invoice_id='" . $invoice_id . "' where unique_id =" . $last_id);
            $totalUpdated++;
            //}
        }
        return $totalUpdated > 0;
    }
    public function getAllAdvanced($filterData, $onlyCount = 0)
    {
        $conditions = "unique_items.deleted=0 and items.deleted=0";
        if ($filterData["itemIDs"]) {
            $conditions .= " and unique_items.item_id in (" . implode(",", $filterData["itemIDs"]) . ")";
        }
        if ($filterData["customer_id"]) {
            $conditions .= " and unique_items.customer_id= " . $filterData["customer_id"] .  " ";
        }

        if ($filterData["supplier_id"]) {
            $conditions .= " and unique_items.supplier_id= " . $filterData["supplier_id"] .  " ";
        }

        if ($filterData["sold"]) {
            if ($filterData["sold"] == "sold") {
                $conditions .= " and (unique_items.customer_id!=0 or unique_items.invoice_id!=0) ";
            } else if ($filterData["sold"] == "instock") {
                $conditions .= " and (unique_items.customer_id=0 and unique_items.invoice_id=0) ";
            }
        }

        if ($filterData["hasIssue"]) {
            $storeId = $_SESSION["store_id"];
            if ($filterData["hasIssue"] == "hasIssue") {
                $conditions .= "";
                $x = my_sql::fetch_assoc(my_sql::query("SELECT items.id as item_id from items left join store_items on items.id=store_items.item_id left join (SELECT count(*) as available_qty,item_id from unique_items where deleted =0 and customer_id=0 and invoice_id=0 group by item_id) as ui2 on ui2.item_id=items.id where  deleted=0 and store_items.store_id=$storeId and COALESCE(ui2.available_qty,0)!=COALESCE(store_items.quantity,0) "));
                if (!$x)
                    $conditions .= " and 0";
                else {
                    $conditions .= " and unique_items.item_id in (" . implode(",", array_map(function ($singleItem) {
                        return $singleItem["item_id"];
                    }, $x)) . ")";
                }
            } else if ($filterData["hasIssue"] == "noissue") {

                $y = my_sql::fetch_assoc(my_sql::query("SELECT items.id  as item_id from items left join store_items on items.id=store_items.item_id left join (SELECT count(*) as available_qty,item_id from unique_items where deleted =0 and customer_id=0 and invoice_id=0 group by item_id) as ui2 on ui2.item_id=items.id where  deleted=0 and store_items.store_id=$storeId and ui2.available_qty=store_items.quantity"));
                if (!$y)
                    $conditions .= " and 0";
                else {
                    $conditions .= " and unique_items.item_id in (" . implode(",", array_map(function ($singleItem) {
                        return $singleItem["item_id"];
                    }, $y)) . ")";
                }
            }
        }
        $uniqueItemsCount =  my_sql::fetch_assoc(my_sql::query("SELECT distinct(unique_items.item_id) as item_id FROM unique_items left join suppliers on suppliers.id=unique_items.supplier_id left join customers on customers.id=unique_items.customer_id left join items on items.id=unique_items.item_id  where $conditions "));
        if ($onlyCount) {
            return count($uniqueItemsCount);
        }
        $itemIdsAllowed = array();
        foreach ($uniqueItemsCount as $uniqueItemx) {
            $itemIdsAllowed[] = $uniqueItemx["item_id"];
        }
        $itemIdsAllowed = array_slice($itemIdsAllowed, $filterData["start"], $filterData["length"]);
        $UniqueItems = array();

        if ($itemIdsAllowed)
            $UniqueItems =  my_sql::fetch_assoc(my_sql::query("SELECT unique_items.*,customers.name as customer_name,suppliers.name as supplier_name,items.description,items.barcode FROM unique_items left join suppliers on suppliers.id=unique_items.supplier_id left join customers on customers.id=unique_items.customer_id left join items on items.id=unique_items.item_id  where $conditions and unique_items.item_id in (" . implode(",", $itemIdsAllowed) . ") "));
        $sortedUniqueItems = array();
        foreach ($UniqueItems as $uniqueItem) {
            $sortedUniqueItems[$uniqueItem["item_id"]][] = $uniqueItem;
        }
        $items = [];
        if ($sortedUniqueItems)
            $items = my_sql::fetch_assoc(my_sql::query("SELECT items.*,ui2.available_qty ,ui.sold as sold_qty,store_items.quantity from items left join store_items on items.id=store_items.item_id left join (SELECT count(*) as sold,item_id from unique_items where deleted =0 and (customer_id!=0 or invoice_id!=0) group by item_id) as ui on ui.item_id=items.id left join (SELECT count(*) as available_qty,item_id from unique_items where deleted =0 and customer_id=0 and invoice_id=0 group by item_id) as ui2 on ui2.item_id=items.id where  items.id in (" . implode(",", array_keys($sortedUniqueItems)) . ") and deleted=0 and store_items.store_id=" . $_SESSION["store_id"]));
        return ["items" => $items, "uniqueItems" => $sortedUniqueItems];
    }
    public function clean_unique_item($invoice_id, $item_id)
    {
        $query = "select * from unique_items where item_id=$item_id and invoice_id=$invoice_id and deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        if (count($result) == 1) {
            
            $qry_1="update unique_items set customer_id=0,invoice_id=0,warehouse_synced=0 where id=" . $result[0]["id"];
            my_sql::query($qry_1);
            if (my_sql::get_mysqli_rows_num() > 0) {
                my_sql::global_query_sync($qry_1);
            }
            
            $qry_2="INSERT into unique_items_history(id,item_id,invoice_id,updated_by,updated_at,description,creation_date) "
                . "values(" . $result[0]["id"] . "," . $item_id . "," . $invoice_id . "," . $_SESSION["id"] . ",now(),'Item returned',now())";
            my_sql::query($qry_2);
            if (my_sql::get_mysqli_rows_num() > 0) {
                my_sql::global_query_sync($qry_2);
            }
            
            /* clear from warehouse */
            $query_w = "select * from store where primary_db=1";
            $result_w = my_sql::fetch_assoc(my_sql::query($query_w));
            if(count($result_w)>0 && WAREHOUSE_CONNECTED==0){
                $host = $result_w[0]["ip_address"];
                $username = $result_w[0]["username"];
                $password = $result_w[0]["password"];
                $db = $result_w[0]["db"];
                $cnx = mysqli_connect($host, $username, $password,$db);
                my_sql::custom_connection_query("update unique_items set customer_id=0,invoice_id=0,registered_in_store_id=0 where id=" . $result[0]["id"], $cnx);
            }
            /* end of clear from warehouse */
            
            return array();
        } elseif (count($result) > 1) {
            return $result;
        }
    }
}
