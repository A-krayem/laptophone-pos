<?php
class unique_items extends controller
{
    public function getAll($_itemID, $only_not_paid)
    {
        $read_only="";
        $disabled="";
        $del="";
        if( $_SESSION['global_admin_exist']==1 && ($_SESSION['centralize']==0 || WAREHOUSE_CONNECTED==1) ){
            $read_only="readonly";
            $disabled="disabled";
            $del="-1";
        }
        
        $read_only_note="";
        if( $_SESSION['centralize']==1){
            //$read_only_note="readonly";
        }
        
        $customer_disabled="";
        if($_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==0){
            $customer_disabled="disabled";
        }
        
        
        $itemID = filter_var($_itemID, FILTER_SANITIZE_NUMBER_INT);
        $uniqueItems = $this->model("uniqueItems");
        
        $remote_customers=array();
        
        if( $_SESSION['centralize']==0 || ($_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==1)){
            $allUniquesOfItem = $uniqueItems->getAll__($itemID, $only_not_paid);
        }else{
            //$allUniquesOfItem = $uniqueItems->getAll__centralize($itemID, $only_not_paid);
            $allUniquesOfItem = $uniqueItems->getAll__V2($itemID, $only_not_paid);
            foreach ($allUniquesOfItem as $index => $uniqueOfItem) {
                if($uniqueOfItem["customer_id"]>0){
                    array_push($remote_customers,array("customer_id"=>$uniqueOfItem["customer_id"],"store_id"=>$uniqueOfItem["registered_in_store_id"]));
                }
            }
        }
        
        
        $store = $this->model("store");
            
        $stores_data = array();
        $stores = $store->getAllStores();
        for($i=0;$i<count($stores);$i++){
            $stores_data[$stores[$i]["id"]] = $stores[$i]["name"];
        }
        
        
        $return = array();
        $return["data"] = array();
        
        $enable_push=true;
        
        foreach ($allUniquesOfItem as $index => $uniqueOfItem) {
            
            $enable_push=true;
            $tmp = array();
            array_push($tmp, $uniqueOfItem["id"]);
            array_push($tmp, "<input ".$read_only."  class='form-control moveToNextOnEnter' data-diagonal-ordering='" . ($index + 1 + $index + 1 - 1) . "' data-ordering=\"" . (100 + $index) . "\" style='width:100%' onchange='update_unique_item(" . $uniqueOfItem["id"] . ")' type='text' id='unique_item_code1_" . $uniqueOfItem["id"] . "' value='" . $uniqueOfItem["code1"] . "' />");
            array_push($tmp, "<input  ".$read_only." class='form-control moveToNextOnEnter'  data-diagonal-ordering='" . ($index + 1 + $index + 1) . "' data-ordering=\"" . (200 + $index) . "\" style='width:100%' onchange='update_unique_item(" . $uniqueOfItem["id"] . ")' type='text' id='unique_item_code2_" . $uniqueOfItem["id"] . "' value='" . $uniqueOfItem["code2"] . "' />");
            $supplierOption = $uniqueOfItem["supplier_id"] ? "<option selected  value='" . $uniqueOfItem["supplier_id"] . "'>" . $uniqueOfItem["supplier_name"] . "</option>" : "<option value=''>Select a supplier</option>";
            
            
            if( $_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==0){
                if($uniqueOfItem["registered_in_store_id"]>0){
                    $cnx = self::get_store_connection($uniqueOfItem["registered_in_store_id"]);
                    $remote_customer = $uniqueItems->get_remote_customer($cnx,$uniqueOfItem["customer_id"]);
                    if(count($remote_customer)>0){

                        $full_name="";
                        if(strlen($remote_customer[0]["name"])>0){
                            $full_name.=$remote_customer[0]["name"];
                        }
                        if(strlen($remote_customer[0]["middle_name"])>0){
                            $full_name.=" ".$remote_customer[0]["middle_name"];
                        }
                        if(strlen($remote_customer[0]["last_name"])>0){
                            $full_name.=" ".$remote_customer[0]["last_name"];
                        }


                        $customerOption =  "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $remote_customer[0]["name"]. " Branch: " .$stores_data[$uniqueOfItem["registered_in_store_id"]] . "</option>";
                    }else{
                        $customerOption = $uniqueOfItem["customer_id"] ? "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $uniqueOfItem["customer_name"] . "</option>" : "<option value=''>Select a customer</option>";
                    }
                }else{
                        $customerOption = $uniqueOfItem["customer_id"] ? "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $uniqueOfItem["customer_name"] . "</option>" : "<option value=''>Select a customer</option>";
                }
                

                    
            }elseif( $_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==1){
                
                if(floatval($uniqueOfItem["registered_in_store_id"])>0){
                    
                    $customer_disabled="disabled";
                    if(floatval($uniqueOfItem["registered_in_store_id"])==$_SESSION["store_id"]){
                        $customer_disabled="";
                    }
                   
                    
                    
                    $cnx = self::get_store_connection($uniqueOfItem["registered_in_store_id"]);
                    $remote_customer = $uniqueItems->get_remote_customer($cnx,$uniqueOfItem["customer_id"]);
                    if(count($remote_customer)>0){

                        $full_name="";
                        if(strlen($remote_customer[0]["name"])>0){
                            $full_name.=$remote_customer[0]["name"];
                        }
                        if(strlen($remote_customer[0]["middle_name"])>0){
                            $full_name.=" ".$remote_customer[0]["middle_name"];
                        }
                        if(strlen($remote_customer[0]["last_name"])>0){
                            $full_name.=" ".$remote_customer[0]["last_name"];
                        }


                        $customerOption =  "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $remote_customer[0]["name"]. " Branch: " .$stores_data[$uniqueOfItem["registered_in_store_id"]] . "</option>";
                    } else{
                        $customerOption="";
                    }
                }else{
                    $customer_disabled="";
                    $read_only_note="";
                    $customerOption = $uniqueOfItem["customer_id"] ? "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $uniqueOfItem["customer_name"] . "</option>" : "<option value=''>Select a customer</option>";
                }
                /*
                if(floatval($uniqueOfItem["registered_in_store_id"])== floatval($_SESSION["store_id"])){
                    if($uniqueOfItem["customer_id"]==0){
                        $customer_disabled="";
                        $read_only_note="";
                        $customerOption = $uniqueOfItem["customer_id"] ? "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $uniqueOfItem["customer_name"] . "</option>" : "<option value=''>Select a customer</option>";
                    }else if($uniqueOfItem["registered_in_store_id"]>0 && $uniqueOfItem["customer_id"]>0){
                        
                        
                        
                        $customer_disabled="disabled";
                        $cnx = self::get_store_connection($uniqueOfItem["registered_in_store_id"]);
                        $remote_customer = $uniqueItems->get_remote_customer($cnx,$uniqueOfItem["customer_id"]);
                        if(count($remote_customer)>0){

                            $full_name="";
                            if(strlen($remote_customer[0]["name"])>0){
                                $full_name.=$remote_customer[0]["name"];
                            }
                            if(strlen($remote_customer[0]["middle_name"])>0){
                                $full_name.=" ".$remote_customer[0]["middle_name"];
                            }
                            if(strlen($remote_customer[0]["last_name"])>0){
                                $full_name.=" ".$remote_customer[0]["last_name"];
                            }


                            $customerOption =  "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $remote_customer[0]["name"]. " Branch: " .$stores_data[$uniqueOfItem["registered_in_store_id"]] . "</option>";
                        } else{
                            $customerOption="";
                        }
                    }else{
                        $read_only_note="";
                        $customerOption="";
                    } 
                }else{
                    $customer_disabled="";
                    $read_only_note="";
                    $customerOption = $uniqueOfItem["customer_id"] ? "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $uniqueOfItem["customer_name"] . "</option>" : "<option value=''>Select a customer</option>";
                }*/
                
                
            }else{
                
                
                $customer_disabled="";
                if($uniqueOfItem["customer_id"]>0){
                    $customerOption = $uniqueOfItem["customer_id"] ? "<option selected value='" . $uniqueOfItem["customer_id"] . "'>" . $uniqueOfItem["customer_name"] . "</option>" : "<option value=''>Select a customer</option>";
                }else{
                    
                
                    
                    $customerOption = "<option value=''>Select a customer</option>";
                    
                    
                  
                    if($_SESSION['global_admin_exist']==1){
                        $warehouses = $store->getWarehouses();
                        $cnx = self::get_store_connection($warehouses[0]["id"]);
                        $remote_customer_of_imei = $uniqueItems->remote_customer_of_imei($cnx,$uniqueOfItem["id"]);
                    }else{
                        $remote_customer_of_imei = array();//$uniqueItems->remote_customer_of_imei($cnx,$uniqueOfItem["id"]);
                    }
                    
                    
                    
                    if(count($remote_customer_of_imei)>0){
                        $customer_disabled="disabled";
                        
                        if($only_not_paid==1){
                             $enable_push=false;
                        }
                       
                        $cnx_to_store = self::get_store_connection($remote_customer_of_imei[0]["registered_in_store_id"]);
                        $customer_info_remote = $uniqueItems->get_customer_info_remote($cnx_to_store,$remote_customer_of_imei[0]["customer_id"],$remote_customer_of_imei[0]["registered_in_store_id"]);
                        
                        
                        if(count($customer_info_remote)>0){
                            $customerOption = "<option value='".$customer_info_remote[0]["name"]."'>".$customer_info_remote[0]["name"]." Branch: ".$stores_data[$remote_customer_of_imei[0]["registered_in_store_id"]]."</option>";
                        }else{
                            $customerOption = "<option value='".$remote_customer_of_imei[0]["customer_id"]."'>".$remote_customer_of_imei[0]["customer_id"]."</option>";
                        }
                    }else{
                        $customerOption = "<option value=''>Select a customer</option>";
                        $customer_disabled="";
                    }   
                }
                
            }
            
            
            array_push($tmp, "<select ".$disabled." class='form-control  suppliers_select new_unique_item_supplier_select'  style='width:100%'  id='unique_item_supplier_" . $uniqueOfItem["id"] . "' onchange='update_unique_item(" . $uniqueOfItem["id"] . ")' >$supplierOption </select>");
            array_push($tmp, "<select  ".$customer_disabled." class='form-control customers_select' style='width:100%'  id='unique_item_customer_" . $uniqueOfItem["id"] . "' onchange='update_unique_item(" . $uniqueOfItem["id"] . ")'  >$customerOption </select>");
           
            
            array_push($tmp,  "<input  ".$read_only_note."  class='form-control moveToNextOnEnter' data-ordering=\"" . (500 + $index) . "\" onchange='update_unique_item(" . $uniqueOfItem["id"] . ")'  style='width:100%' type='text' id='unique_item_note_" . $uniqueOfItem["id"] . "' value='" . $uniqueOfItem["note"] . "' />");
            
            array_push($tmp, $del);
            
            if($enable_push){
               array_push($return["data"], $tmp); 
            }
                
        }
        echo json_encode($return);
    }

    function show_available_imei($json_items, $cid)
    {
        $unique_items = $this->model("uniqueItems");
        $jsonData = json_decode($json_items, true);

        $items_ids = array();
        foreach ($jsonData as $key => $value) {
            array_push($items_ids, $key);
        }

        $result = $unique_items->get_items_imei_by_array($items_ids);



        $data_array["data"] = array();
        for ($i = 0; $i < count($result); $i++) {
            $tmp = array();
            array_push($tmp, $result[$i]["item_id"]);
            array_push($tmp, $result[$i]["description"]);
            array_push($tmp, "<a onclick='addUniqueItemToCustomer_custom(\"" . $result[$i]["code1"] . "\"," . $cid . ")' href='#'>" . $result[$i]["code1"] . "</a>");
            array_push($tmp, "<a onclick='addUniqueItemToCustomer_custom(\"" . $result[$i]["code2"] . "\"," . $cid . ")' href='#'>" . $result[$i]["code2"] . "</a>");
            array_push($tmp, "");

            array_push($data_array["data"], $tmp);
        }

        echo json_encode($data_array);
    }

    public function deleteItem($_uniqueItemId)
    {
        $uniqueItemId = filter_var($_uniqueItemId, FILTER_SANITIZE_NUMBER_INT);
        $uniqueItems = $this->model("uniqueItems");
        $uniqueItems->delete($uniqueItemId);
        echo json_encode(array("success" => true));
    }
    
    public function check_availibility($_imei,$_imeip){
        $imei = filter_Var($_imei,  self::conversion_php_version_filter());
        $imeip = filter_var($_imeip, FILTER_SANITIZE_NUMBER_INT);
         $uniqueItems = $this->model("uniqueItems");
         
        $exist = $uniqueItems->check_availibility($imei,$imeip);
         
        if($imeip==1){
            echo json_encode(array($exist));
        }else{
            echo json_encode(array($exist));
        }
        
    }
    
    public function createNew_withPI($_itemID, $_qty, $_clear,$_pi_id,$_supplier_id)
    {
        if(WAREHOUSE_CONNECTED==1){
            echo json_encode(array('success' => false,'is_warehouse' => 0,'show_imei_options' => 0));
            exit;
        }
        
        $itemID = filter_var($_itemID, FILTER_SANITIZE_NUMBER_INT);
        $pi_id = filter_var($_pi_id, FILTER_SANITIZE_NUMBER_INT);
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_qty, FILTER_SANITIZE_NUMBER_INT);
        $clear = filter_Var($_clear,  self::conversion_php_version_filter());
        $uniqueItems = $this->model("uniqueItems");
        if ($clear == 1) {
            $uniqueItems->clearUndefined($itemID);
        }
        
     
        if(!isset($pi_id) || is_null($pi_id) || $pi_id==""){
            $pi_id=0;
        }
        $uniqueItems->createNew_withPI($itemID, $qty,$pi_id,$supplier_id);
        
        
        $warehouse_exist=0;
        
        
        if($_SESSION['centralize']==0){
            $warehouse_exist=1;
        }
        
    
        $is_warehouse=0;
        if($_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==1){
             $is_warehouse=1;
        }
        
        
        $show_imei_options=0;
        if( ($_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==0) || $_SESSION['global_admin_exist']==0){
            $show_imei_options=1;
        }
        
        
        echo json_encode(array('success' => true,'is_warehouse' => $is_warehouse,'show_imei_options' => $show_imei_options));
    }
    
    public function createNew($_itemID, $_qty, $_clear)
    {
        if(WAREHOUSE_CONNECTED==1){
            echo json_encode(array('success' => false,'is_warehouse' => 0,'show_imei_options' => 0));
            exit;
        }
        
        $itemID = filter_var($_itemID, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_qty, FILTER_SANITIZE_NUMBER_INT);
        $clear = filter_Var($_clear,  self::conversion_php_version_filter());
        $uniqueItems = $this->model("uniqueItems");
        if ($clear == 1) {
            $uniqueItems->clearUndefined($itemID);
        }
        $uniqueItems->createNew($itemID, $qty);
        
        
        $warehouse_exist=0;
        
        
        if($_SESSION['centralize']==0){
            $warehouse_exist=1;
        }
        
    
        $is_warehouse=0;
        if($_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==1){
             $is_warehouse=1;
        }
        
        
        $show_imei_options=0;
        if( ($_SESSION['centralize']==1 && WAREHOUSE_CONNECTED==0) || $_SESSION['global_admin_exist']==0){
            $show_imei_options=1;
        }
        
        
        echo json_encode(array('success' => true,'is_warehouse' => $is_warehouse,'show_imei_options' => $show_imei_options));
    }
    public function update()
    {
        $data = filter_input_array(INPUT_POST, array("data" => array("filter" => self::conversion_php_version_filter(), "flags" => FILTER_REQUIRE_ARRAY)));
        $uniqueItems = $this->model("uniqueItems");
        $uniqueItems->update($data["data"]);
        echo json_encode(array("success" => true));
    }
    public function search()
    {
        $term = filter_input(INPUT_POST, "term", self::conversion_php_version_filter());
        $uniqueItems = $this->model("uniqueItems");
        $uniqueItems = $uniqueItems->search($term);
        //For now i'm going to return all the data, and we can use it in the front end
        echo json_encode(array("data" => $uniqueItems));
    }
    public function searchByCode()
    {
        $code = filter_input(INPUT_POST, "code", self::conversion_php_version_filter());
        $customer_id = filter_input(INPUT_POST, "customer_id", FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        $response["customer"] = $customers->getCustomersById($customer_id);
        $uniqueItems = $this->model("uniqueItems");
        $result = $uniqueItems->serachByCode($code);
        $response["success"] = count($result) > 0;
        $response["unique_items"] = $result;
        echo json_encode($response);
    }
    
    public function ignore_imei_registration(){
        $uniqueItems = $this->model("uniqueItems");
        if(isset($_SESSION["force_assign_code"]) && $_SESSION["force_assign_code"]==1){
            $uniqueItems->ignore_imei_registration();
            unset($_SESSION["assign_codes"]);
            unset($_SESSION["description"]);
            unset($_SESSION["customer_id"]);
            unset($_SESSION["inv_id"]);
        }
        echo json_encode(array());
    }
    
    public function updateUniqueItemsCustomerId()
    {
        $invoice_id = filter_input(INPUT_POST, "invoice_id", FILTER_SANITIZE_NUMBER_INT);
        $updates = filter_input_array(INPUT_POST, array("itemsToUpdate" => array("filter" => FILTER_SANITIZE_NUMBER_INT, "flags" => FILTER_REQUIRE_ARRAY)));
        $uniqueItems = $this->model("uniqueItems");
        $uniqueItems->updateCustomerIDOfUniqueItems($updates["itemsToUpdate"], $invoice_id);
        
        
        if(isset($_SESSION["force_assign_code"]) && $_SESSION["force_assign_code"]==1){
            $_SESSION["force_assign_code"]=0;
            unset($_SESSION["assign_codes"]);
            unset($_SESSION["description"]);
            unset($_SESSION["customer_id"]);
            unset($_SESSION["inv_id"]);
        }
        
        echo json_encode(array("success" => true));
    }
    public function getAllAdvanced()
    {
        $filterData = filter_input_array(INPUT_POST, array("customer_id" => FILTER_SANITIZE_NUMBER_INT, "hasIssue" => self::conversion_php_version_filter(), "supplier_id" => FILTER_SANITIZE_NUMBER_INT, "sold" => self::conversion_php_version_filter(), "itemIDs" => array("filter" => FILTER_SANITIZE_NUMBER_INT, "flags" => FILTER_REQUIRE_ARRAY)));
        $uniqueItems = $this->model("uniqueItems");
        $results = $uniqueItems->getAllAdvanced($filterData);
        $total = $uniqueItems->getAllAdvanced($filterData, true);
        $items = $results["items"];
        $return = array();
        function appendWithSeperator($mapper, $items)
        {
            $i = 0;
            $res = [];
            foreach ($items as $item) {
                $i++;
                $res[] = $i . "- " . $mapper($item);
            }
            return implode("\n<br/>", $res);
        };
        foreach ($items as $item) {
            $tmp = array();
            $tmp[] = self::idFormat_item($item["id"]);
            $tmp[] = $item["description"];
            $tmp[] =  $item["barcode"];
            $tmp[] =  appendWithSeperator(function ($singleItem) {
                return $singleItem["code1"] ? $singleItem["code1"] : "N/A";
            }, $results["uniqueItems"][$item["id"]]);
            $tmp[] =  appendWithSeperator(function ($singleItem) {
                return $singleItem["code2"] ? $singleItem["code2"] : "N/A";
            }, $results["uniqueItems"][$item["id"]]);

            $tmp[] =  appendWithSeperator(function ($singleItem) {
                return $singleItem["supplier_name"] ? $singleItem["supplier_name"] : "N/A";
            }, $results["uniqueItems"][$item["id"]]);

            $tmp[] = appendWithSeperator(function ($singleItem) {
                return $singleItem["customer_name"] ? $singleItem["customer_name"] : "N/A";
            }, $results["uniqueItems"][$item["id"]]);

            $tmp[] = $item["sold_qty"] ? $item["sold_qty"] : 0;
            $tmp[] = $item["available_qty"] ? $item["available_qty"] : 0;

            $tmp[] = number_format($item["quantity"], 0);

            $tmp[] = "";
            // $tmp[] = "";
            // $tmp[] = "";
            // $tmp[] = "";
            $return[] = $tmp;
        }
        echo json_encode([
            'data' => $return,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
        ]);
    }
}
