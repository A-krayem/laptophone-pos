<?php

class quotations extends Controller
{

    public $settings_info = null;

    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function all_quotations()
    {

        //self::giveAccessTo();
        $data = array();
        $data["print_a4_pdf_version"] = $this->settings_info["print_a4_pdf_version"];
        $this->view("all_quotations", $data);
    }

    public function get_needed_data()
    {
        //self::giveAccessTo();
        $settings_info = self::getSettings();
        $info["default_currency_symbol"] = $settings_info["default_currency_symbol"];
        $info["apply_vat_sales_item"] = $settings_info["apply_vat_sales_item"];
        $info["print_a4_pdf_version"] = $settings_info["print_a4_pdf_version"];
        $info["all_invoices_hide_col"] = $settings_info["all_invoices_hide_col"];
        $info["all_quotations_hide_col"] = $settings_info["all_quotations_hide_col"];

        $info["vat"] = $settings_info["vat"];

        $suppliers = $this->model("suppliers");
        $suppliers_data = $suppliers->getSuppliers();
        $info["suppliers"] = array();
        for ($i = 0; $i < count($suppliers_data); $i++) {
            $info["suppliers"][$i]["id"] = $suppliers_data[$i]["id"];
            $info["suppliers"][$i]["name"] = $suppliers_data[$i]["name"];
            $info["suppliers"][$i]["c_name"] = $suppliers_data[$i]["contact_name"];
            $info["suppliers"][$i]["address"] = $suppliers_data[$i]["address"];
        }

        $info["payment_status"] = array();
        $settings = $this->model("settings");
        $info["payment_status"] = $settings->get_payment_status();

        $warehouses = $this->model("warehouses");
        $all_warehouses = $warehouses->getAllWarehouses();
        $info["warehouses"] = array();
        for ($i = 0; $i < count($all_warehouses); $i++) {
            $info["warehouses"][$i]["id"] = self::idFormat_warehouse($all_warehouses[$i]["id"]);
            $info["warehouses"][$i]["location"] = $all_warehouses[$i]["location"];
        }

        $store = $this->model("store");
        $all_stores = $store->getStores();
        $info["stores"] = array();
        for ($i = 0; $i < count($all_stores); $i++) {
            $info["stores"][$i]["id"] = $all_stores[$i]["id"];
            $info["stores"][$i]["name"] = $all_stores[$i]["name"];
        }


        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        $info["currencies"] = array();
        for ($i = 0; $i < count($all_currencies); $i++) {
            $info["currencies"][$i]["id"] = $all_currencies[$i]["id"];
            $info["currencies"][$i]["name"] = $all_currencies[$i]["name"];
            $info["currencies"][$i]["symbole"] = $all_currencies[$i]["symbole"];
            $info["currencies"][$i]["system_default"] = $all_currencies[$i]["system_default"];
            $info["currencies"][$i]["rate_to_system_default"] = $all_currencies[$i]["rate_to_system_default"];
            $info["currencies"][$i]["pi_decimal"] = $all_currencies[$i]["pi_decimal"];
        }


        $sizes = $this->model("sizes");
        $colors = $this->model("colors");

        $sizes_info = $sizes->getSizes();
        $colors_info = $colors->getColorsText();

        $sizes_info_label = array();
        for ($i = 0; $i < count($sizes_info); $i++) {
            $sizes_info_label[$sizes_info[$i]["id"]] = $sizes_info[$i]["name"];
        }

        $colors_info_label = array();
        for ($i = 0; $i < count($colors_info); $i++) {
            $colors_info_label[$colors_info[$i]["id"]] = $colors_info[$i]["name"];
        }

        $info["all_items"] = array();


        $users = $this->model("user");
        
        if($_SESSION["role"]==2){
            $info["employees"] = $users->get_user_by_id($_SESSION["id"]);
        }else{
            $info["employees"] = $users->getAllUsers();
        }
        

        echo json_encode($info);
    }
    public function convertToInvoice($quotation_id)
    {
    }
    public function getAllQuotationsDateRange($store_id_, $_date, $_filter_deleted, $_filter_user, $_filter_customer_id, $_items)
    {
        //self::giveAccessTo();
        $date = filter_var($_date, self::conversion_php_version_filter());
        $filter_user = filter_var($_filter_user, FILTER_SANITIZE_NUMBER_INT);
        $quotations = $this->model("quotations");
        $filter_customer_id = filter_var($_filter_customer_id, FILTER_SANITIZE_NUMBER_INT);
        $filter_status = filter_var($_filter_deleted, FILTER_SANITIZE_NUMBER_INT);
        $items = filter_var($_items, self::conversion_php_version_filter());
        if ($items)
            $items = explode(",", $items);
        $date_range_tmp = explode(" ", $date ? $date : "");
        $date_range[0] = $date == "today" ? date('Y-m-1') : date('Y-m-d', strtotime(trim($date_range_tmp[0])));
        $date_range[1] = $date == "today" ? date('Y-m-d') : date('Y-m-d', strtotime(trim($date_range_tmp[2])));

        
        $customers = $this->model("customers");
        $customers_types = $customers->getCustomersTypes();
        $customers_types_info = array();
        for ($i = 0; $i < count($customers_types); $i++) {
            $customers_types_info[$customers_types[$i]["id"]] = $customers_types[$i]["name"];
        }
        

        //$filter_user=0;
        $quotations = $quotations->getAllQuotationsSwitch($date_range,  $filter_user, $filter_status, $filter_customer_id, $items);

        $data_array["data"] = array();

        if ($this->settings_info["show_currency_in_report"] == 0) {
            $this->settings_info["default_currency_symbol"] = "";
        }
        foreach ($quotations as $quotation) {
            $tmp = array();

            array_push($tmp, self::idFormat_quotation($quotation["id"]));

            array_push($tmp,  $quotation["customer_id"] ? self::idFormat_customer($quotation["customer_id"]) : "");
            array_push($tmp, $quotation["customer_id"] ? $quotation["customer_name"] . " " . $quotation["customer_middle_name"] . " " . $quotation["customer_last_name"] : "");
            array_push($tmp, $quotation["sales_first_name"] . " " . $quotation["sales_last_name"]);

            //$tmp[] = $quotation["customer_id"] ? self::idFormat_customer($quotation["customer_id"]) : "";

            //$tmp[] = $quotation["customer_id"] ? $quotation["customer_name"] . " " . $quotation["customer_middle_name"] . " " . $quotation["customer_last_name"] : "";

            //$tmp[] =  $quotation["sales_first_name"] . " " . $quotation["sales_last_name"];

            array_push($tmp, $quotation["creation_date"]);
            array_push($tmp, self::value_format_custom_no_currency($quotation["sub_total"], $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($quotation["discount"], $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($quotation["vat"], $this->settings_info));
            array_push($tmp, self::value_format_custom_no_currency($quotation["total"], $this->settings_info));

            $tmp_ = explode(" ", $quotation["expiery_date"]);
            array_push($tmp,  $quotation["expiery_date"] && $tmp_[0] != "0000-00-00" ? self::date_format_custom($quotation["expiery_date"]) : "");
            
            
            array_push($tmp, $customers_types_info[$quotation["quotation_type"]]);
            
            array_push($tmp, "");

            array_push($tmp, $quotation["deleted"]);

            $tmp_ = explode(" ", $quotation["expiery_date"]);
            array_push($tmp, $quotation["expiery_date"] && $tmp_[0] != "0000-00-00" ? (new DateTime($quotation["expiery_date"]) > (new DateTime()) ? 0 : 1) : "0");
            array_push($tmp, $quotation["invoice_id"]);
            
            array_push($tmp, $_SESSION["role"]);
            
            array_push($data_array["data"], $tmp);
        }

        echo json_encode($data_array);
    }
    public function generate_empty_quotation()
    {
        $quotations = $this->model("quotations");
        echo json_encode($quotations->generate_empty_quotation($_SESSION['store_id'], $_SESSION['id'], $this->settings_info["vat"]));
    }
    
    public function update_quotation_type($_quotation_id,$_type_id){
        $quotation_id = filter_var($_quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $type_id = filter_var($_type_id, FILTER_SANITIZE_NUMBER_INT);
        
        $quotations = $this->model("quotations");
        $quotations->update_quotation_type($quotation_id,$type_id);
        echo json_encode(array());
    }
    
    

    function save_manual_quotation_items($quotation_item_id, $price, $discount, $vat, $qty, $description, $rate)
    {
        $quotations = $this->model("quotations");
        $items = $this->model("items");
        $quotation_item_id = filter_var($quotation_item_id, FILTER_SANITIZE_NUMBER_INT);
        $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $discount = filter_var($discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $vat = filter_var($vat, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $old_info = $quotations->get_item_from_quotation($quotation_item_id);
        $rate = filter_var($rate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $description = filter_var($description,  self::conversion_php_version_filter());

        $item_info = $items->get_item($old_info[0]["item_id"]);
        $info["buying_cost"] = $item_info[0]["buying_cost"];
        $info["quotation_item_id"] = $quotation_item_id;
        $info["selling_price"] = $price;
        $info["discount"] = $discount;
        $info["vat"] = $vat;
        $info["qty"] = $qty;
        $info["vat_value"] = 1 + (floatval($this->settings_info["vat"]) / 100);
        $info["final_cost"] = $info["buying_cost"] * $info["qty"];
        $info["description"] = $description;

        if ($info["vat"] == 0) {
            $info["final_price"] = ($info["selling_price"] - ($info["selling_price"] * $info["discount"] / 100)) * $info["qty"];
        } else {
            $info["final_price"] = ($info["selling_price"] - ($info["selling_price"] * $info["discount"] / 100)) * $info["qty"] *  $info["vat_value"];
        }

        $info["profit"] =  ($info["selling_price"] - ($info["selling_price"] * $info["discount"] / 100)) * $info["qty"] - $info["final_cost"];
        $quotations = $this->model("quotations");
        $quotations->save_manual_quotation_items($info);
        $store = $this->model("store");
        $qtty = $store->getQtyOfItem($_SESSION["store_id"], $old_info[0]["item_id"]);

        $quotations->calculate_total_profit_for_quotation($old_info[0]["quotation_id"]);
        $quotations->calculate_total_value($old_info[0]["quotation_id"]);
        // $quotations->calculate_total_value_with_vat($invoice_id);
        // $quotations->calculate_total_cost_price_for_invoice($invoice_id);
        // $quotations->calculate_total_value($invoice_id);

        $get_item_from_quotation = $quotations->get_item_from_quotation($quotation_item_id);
        $temp = $get_item_from_quotation[0];

        $rowTmp["id"] = $temp["id"];
        $rowTmp["quotation_id"] = $temp["quotation_id"];
        $rowTmp["item_id"] = $temp["item_id"];
        $rowTmp["additional_description"] = $temp["additional_description"];
        $rowTmp["buying_cost"] = $temp["buying_cost"];
        $rowTmp["qty"] = $temp["qty"];
        
        if($item_info[0]["is_composite"]==1){
            $composite_item_details = $items->get_composite_item_id($item_info[0]["id"]);
            //var_dump($composite_item_details);exit;
            $qtty_c = $store->getQtyOfItem($_SESSION["store_id"], $composite_item_details[0]["item_id"]);
            if($qtty_c[0]["quantity"]>0){
                $rowTmp["qty_in_store"] = $qtty_c[0]["quantity"]/$composite_item_details[0]["qty"] - $temp["qty"];
            }else{
                $rowTmp["qty_in_store"] = 0;
            }
            
        }else{
            $rowTmp["qty_in_store"] = $qtty[0]['quantity'] - $temp["qty"];
        }
        
        
        $rowTmp["selling_price"] = $temp["selling_price"];
        $rowTmp["discount"] = $temp["discount"];
        $rowTmp["vat"] = $temp["vat"];
        $rowTmp["vat_value"] = $temp["vat_value"];
        $rowTmp["final_price"] = $temp["final_price"];
        $rowTmp["final_cost"] = $temp["final_cost"];
        
        
        
        if($_SESSION["role"]==2){
            $rowTmp["profit"]=0;
        }else{
            $rowTmp["profit"] = $_SESSION["hide_critical_data"] ? 0 : $temp["profit"];
        }
            
        $rowTmp["deleted"] = $temp["deleted"];
        $return[] = $rowTmp;
        echo json_encode($return);
    }

    public function addItemsToQuotation_manual($_id_quotation, $_id_item, $_customer_id)
    {
        //self::giveAccessTo(array(2, 3, 4));
        $id_quotation = filter_var($_id_quotation, FILTER_SANITIZE_NUMBER_INT);
        $id_item = filter_var($_id_item, FILTER_SANITIZE_NUMBER_INT);

        $quotations = $this->model("quotations");
        $items = $this->model("items");
        $customers = $this->model("customers");

        //$customer_info = $customers->getCustomersById($_customer_id);
        $quotation_details = $quotations->getQuotationById($id_quotation);

        $info["quotation_id"] = $id_quotation;
        $info["item_id"] = $id_item;
        $info["qty"] = 1;
        $info["custom_item"] = 0;
        $info["mobile_transfer_item"] = 0;
        $info["manual_discounted"] = 0;
        $info["mobile_transfer_device_id"] = 0;

        $items_info = $items->get_item($id_item);

        $info["buying_cost"] = $items_info[0]["buying_cost"];
        $info["vat"] = $items_info[0]["vat"];

        if ($quotation_details[0]["quotation_type"]==2) {
            $info["selling_price"] = $items_info[0]["wholesale_price"];
        }else if ($quotation_details[0]["quotation_type"]==3) {
            $info["selling_price"] = $items_info[0]["second_wholesale_price"];
        } else {
            $info["selling_price"] = $items_info[0]["selling_price"];
        }



        $info["discount"] = 0;
        $info["is_composite"] = $items_info[0]["is_composite"];

        $info["vat_value"] = 1 + ($this->settings_info["vat"] / 100);



        if ($info["vat"] == 0) {
            $info["final_price"] = ($info["selling_price"] - ($info["selling_price"] * $info["discount"] / 100)) * $info["qty"];
        } else {
            $info["final_price"] = ($info["selling_price"] - ($info["selling_price"] * $info["discount"] / 100)) * $info["qty"] * floatval($items_info[0]["vat_value"]);
        }
        $info["final_cost"] = 0;
        $info["profit"] = 0;

        $quotations->addItemsToQuotation($info);
        $quotations->update_quotation_buying_cost($id_quotation);
        $quotations->update_quotation_items_profit($id_quotation);
        echo json_encode(array());
    }

    public function get_all_item_in_quotation($_quotation_id)
    {
        $quotation_id = filter_var($_quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $quotations = $this->model("quotations");
        $items = $this->model("items");
        $quotations->update_quotation_buying_cost($quotation_id);
        $quotations->update_quotation_items_profit($quotation_id);
        $quotations->calculate_total_value($quotation_id);
        $quotations->calculate_total_profit_for_quotation($quotation_id);

        $quotationItems = $quotations->getItemsOfQuotation($quotation_id);
        $data_array["data"] = array();
        $store = $this->model("store");
        foreach ($quotationItems as $item) {
            $item_info = $items->get_item($item["item_id"]);
            if ($item == 1) {
                $no_vat_selected = "";
                $vat_selected = "selected";
            } else {
                $no_vat_selected = "selected";
                $vat_selected = "";
            }
            $tmp = array();
            $tmp[] = $item["id"];
            $tmp[] = self::idFormat_item($item["item_id"]);
            $tmp[] = $item_info[0]["sku_code"];
            $tmp[] = $item_info[0]["barcode"];
            
            
            if($item_info[0]["is_composite"]==0){
                $tmp[] = $item_info[0]["description"]."";
            }else{
                $composite_info = $items->get_composite_item_id($item["item_id"]);
                $tmp[] = $item_info[0]["description"]." <b>(". floatval($composite_info[0]["qty"])."U/Box)</b>";
            }
            
            
            $tmp[] = "<input onchange='update_ad_item_description(" . $item["id"] . ")' class='minv_des' type='text' id='addesc_" . $item["id"] . "' value='" . $item["additional_description"] . "' />";


            array_push($tmp, "<input onchange='update_total_quotation(" . $item["id"] . "," . $quotation_id . ")' class='minv_ cleavesf3 spr_".$item["item_id"]."' type='text' id='inv_it_price_" . $item["id"] . "' value='" . floatval($item["selling_price"]) . "' />");
            array_push($tmp, "<input onchange='update_total_quotation(" . $item["id"] . "," . $quotation_id . ")' class='minv cleavesf2' type='text' id='inv_it_dis_" . $item["id"] . "' value='" . floatval($item["discount"]) . "' />");


            array_push($tmp, "<select onchange='update_total_quotation(" . $item["id"] . "," . $quotation_id . ")' id='mivat_" . $item["id"] . "' class='minv_s'><option value='1' " . $vat_selected . ">" . $this->settings_info["vat"] . "%</option><option value='0'  " . $no_vat_selected . ">No</option></select>"); //$item["vat"]

            if ($vt == 0) {
                array_push($tmp, "<input readonly class='minv_ cleavesf3 form-control itfp_".$item["item_id"]."' style='width:100%!important' type='text' id='fp_" . $item["id"] . "' value='" . ((floatval($item["selling_price"]) * (1 - ($item["discount"] / 100)))) . "' />");
            } else {
                array_push($tmp, "<input readonly class='minv_ cleavesf3 form-control itfp_".$item["item_id"]."' style='width:100%!important' type='text' id='fp_" . $item["id"] . "' value='" . ((floatval($item["selling_price"]) * (1 - ($item["discount"] / 100))) * $vt) . "' />");
            }


            array_push($tmp, "<input onchange='update_total_quotation(" . $item["id"] . "," . $quotation_id . ")' class='minv itq_".$item["item_id"]."' type='number' id='inv_it_qty_" . $item["id"] . "' value='" . floatval($item["qty"]) . "' />");

            if($item_info[0]["is_composite"]==0){
                $qtty = $store->getQtyOfItem($_SESSION["store_id"], $item["item_id"])[0]["quantity"];
            }else{
                
                $composite_items = $items->get_all_composite_of_item($item_info[0]["id"]);
                $qtty = $store->getQtyOfItem($_SESSION["store_id"], $composite_items[0]["item_id"])[0]["quantity"];
            }
            
            array_push($tmp, "<input readonly class='minvread cleavesf3 form-control' style='width:100%!important' type='text' id='qty_in_store_" . $item["id"] . "' value='" .  (number_format($qtty - floatval($item["qty"]))) . "' />");

            $vt = 1;
            if ($item["vat"] == 1) {
                $vt = $item["vat_value"];
            }

            if ($vt == 0) {
                array_push($tmp, "<input readonly class='minvread cleavesf3 total_per_item form-control' style='width:100%!important' type='text' id='inv_it_tp_" . $item["id"] . "' value='" . ((floatval($item["selling_price"]) * (1 - ($item["discount"] / 100))) * floatval($item["qty"])) . "' />");
            } else {
                array_push($tmp, "<input readonly class='minvread cleavesf3 total_per_item form-control' style='width:100%!important' type='text' id='inv_it_tp_" . $item["id"] . "' value='" . ((floatval($item["selling_price"]) * (1 - ($item["discount"] / 100))) * $vt * floatval($item["qty"])) . "' />");
            }
            
            if($_SESSION["role"]==2){
                array_push($tmp, "<input readonly id='quo_it_profit_" . $item["id"] . "' class='minvread cleavesf3 single_item_profit form-control'  style='width:100%!important' value='0' />");
            }else{
                array_push($tmp, "<input readonly id='quo_it_profit_" . $item["id"] . "' class='minvread cleavesf3 single_item_profit form-control'  style='width:100%!important' value='" . ($_SESSION["hide_critical_data"] ? 0 : floatval($item["profit"])) . "' />");
            }
        
        

            array_push($tmp, "");
            array_push($tmp, $item["item_id"]);
            array_push($data_array["data"], $tmp);
        }


        echo json_encode($data_array);
    }

    public function delete_quotation($_quotation_id)
    {
        $quotation_id = filter_var($_quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $quotations = $this->model("quotations");
        $quotations->delete_quotation($quotation_id);
        echo json_encode(array());
    }

    function delete_item_from_manual_quotation($quotation_id)
    {
        $quotations = $this->model("quotations");
        $quotation_id = filter_var($quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $old_info = $quotations->get_item_from_quotation($quotation_id);


        $quotations->delete_item_from_manual_quotation($quotation_id);

        $total_value = $quotations->calculate_total_value_with_vat($old_info[0]["quotation_id"]);


        $quotations->calculate_total_profit_for_quotation($old_info[0]["quotation_id"]);
        $quotations->calculate_total_value($old_info[0]["quotation_id"]);

        echo json_encode(array());
    }

    function save_manual_quotation($quotation_id, $customer_id, $quotation_discount, $_quotation_note, $rate, $_expiery_date)
    {
        $quotations = $this->model("quotations");
        $quotation_note = filter_var($_quotation_note,  self::conversion_php_version_filter());
        $expiery_date = filter_var($_expiery_date,  self::conversion_php_version_filter());
        
        if(strlen($quotation_discount)==0){
            $quotation_discount= 0;
        }
        
        if(strlen($rate)==0){
            $rate= 0;
        }
        
        if(strlen($expiery_date)==0){
            $expiery_date= 'NULL';
        }
        

        $log["before"] = $quotations->getQuotationItemsDetails($quotation_id);
        $quotations->update_quotation_info_manual($quotation_id, $quotation_discount, $quotation_note, $rate, $expiery_date, $customer_id);
        $quotations->calculate_total_value_with_vat($quotation_id);

        $quotations->calculate_total_profit_for_quotation($quotation_id);
        $quotations->calculate_total_value($quotation_id);
        $log["after"] = $quotations->getQuotationItemsDetails($quotation_id);
        $log["post"]["quotation_id"] = $quotation_id;
        $log["post"]["customer_id"] = $customer_id;
        $log["post"]["quotation_discount"] = $quotation_discount;
        $log["post"]["quotation_note"] = $quotation_note;
        $log["data"]["rate"] = $rate;
        $log["post"]["_expiery_date"] = $expiery_date;
        $log = json_encode($log);
        $quotations->saveLog($log, $quotation_id);


        echo json_encode(array());
    }

    public function get_needed_data_for_manual_creation()
    {
        $info = array();
        $employees = $this->model("employees");

        $info["salesman"] = $employees->getAllEmployees();






        echo json_encode($info);
    }


    public function update_add_item_description($quotation_item_id, $_description)
    {
        $quotations = $this->model("quotations");

        $description = filter_var($_description, self::conversion_php_version_filter());

        $quotations->update_add_item_description($quotation_item_id, $description);
        echo json_encode(array());
    }

    public function getQuotationItemsDetails($quotation_id)
    {
        $quotation_id = filter_var($quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $quotations = $this->model("quotations");
        $customers = $this->model("customers");

        $info = $quotations->getQuotationItemsDetails($quotation_id);

        $info["currency_counnt"] = $_SESSION['currency_counnt'];

        $info["customer"] = array();
        if ($info["quotation"][0]["customer_id"] > 0) {
            $info["customer"] = $customers->getCustomersById($info["quotation"][0]["customer_id"]);
        }
        $info["quotation"][0]["profit"] = $_SESSION["hide_critical_data"] ? 0 : $info["quotation"][0]["profit"];
        echo json_encode($info);
    }

    public function getInvoiceItemsDetails($_invoice_id)
    {
    }
    public function generateInvoiceFromQuotation($_quotation_id)
    {
        $quotation_id = filter_var($_quotation_id, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $quotations = $this->model("quotations");
        $items_class = $this->model("items");
        $store = $this->model("store");
        $store_id = $_SESSION["store_id"];
        $payment_method = 1;
        $payment_note = "Converted from Quotations";
        $created_by = $_SESSION["id"];
        $quoDetails = $quotations->getQuotationItemsDetails($quotation_id);
        $quotation = $quoDetails["quotation"][0];
        $quotation_items = $quoDetails["quotation_items"];
        $invoice_id = $invoice->generateInvoiceId($store_id, $_SESSION['id'], $payment_method, $payment_note, $created_by, $this->settings_info["vat"], 0);
        $quotations->setInvoiceId($invoice_id, $quotation_id);
        if ($quotation["discount"]) {
            $invoice->addDiscount($invoice_id, $quotation["discount"], "");
        }
        if ($quotation["customer_id"]) {
            $invoice->updateCustomerInvoice($invoice_id, $quotation["customer_id"]);
        }
        foreach ($quotation_items as $quotationItem) {
            $quotationItem["invoice_id"] = $invoice_id;
            $quotationItem["is_official"] = 0;
            $invoice->addItemsToInvoice($quotationItem);
            
            /* */
            $item_info = $items_class->get_item((int)$quotationItem["item_id"]);
            
            
            
            /* check if complex item */
            if($item_info[0]["complex_item_id"]>0){
                $item_composed=array();
                $item_composed["item_id"]=(int)$item_info[0]["id"];
                $item_composed["item_qty"]=$quotationItem["qty"];
                $store->reduce_qty_of_composite($item_composed);
            }else{
                if($item_info[0]["is_composite"]==0){
                    $qty_to_reduce = $quotationItem["qty"];
                    $store->reduce_qty_by_admin($_SESSION["store_id"], $quotationItem["item_id"], $qty_to_reduce, $_SESSION['id'],$invoice_id);
                }else{
                    $composite_items = $items_class->get_all_composite_of_item($quotationItem["item_id"]);
                    $qty_to_reduce=$quotationItem["qty"]*$composite_items[0]["qty"];
                    $store->reduce_qty_by_admin($_SESSION["store_id"], $composite_items[0]["item_id"], $qty_to_reduce, $_SESSION['id'],$invoice_id);
                }
            }
            
            
            
        }
        $invoice->calculate_total_value($invoice_id);
        $invoice->calculate_total_profit_for_invoice($invoice_id);
        echo json_encode(["invoice_id" => $invoice_id]);
    }
}
