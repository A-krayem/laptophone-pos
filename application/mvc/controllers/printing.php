<?php

class printing extends Controller {

    public $licenseExpired = false;
    public $settings_info = null;
    
    public function __construct() {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->licenseExpired = self::licenseExpired();
    }
    
    
    public function print_quick_financial_report($_daterange){
        $suppliers_class = $this->model("suppliers");
        
        $data = array();
        $daterange = filter_var($_daterange, self::conversion_php_version_filter()); 
        $date_range_tmp = explode(" - ", $daterange);
        $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[1])));
        
        $data["settings"] = $this->settings_info;
        
        $data["date_from"] = $date_range[0];
        $data["date_to"] = $date_range[1];
        
        
        $data["details"]=$suppliers_class->get_suppliers_pi($date_range);
        
        self::__USORT_TIMESTAMP($data["details"]);
    
           
        $this->view("print_templates/a4/print_quick_financial_report",$data);
    }
    
    public function print_dashboard_report($_daterange){
        $data = array();
        $daterange = filter_var($_daterange, self::conversion_php_version_filter()); 
        $date_range_tmp = explode(" - ", $daterange);
        $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[1])));
        
        $data["settings"] = $this->settings_info;
        
        $data["date_from"] = $date_range[0];
        $data["date_to"] = $date_range[1];
        
        $reports = $this->model("reports");
        $data["report_info"] = $reports->print_dashboard_report($date_range);
        
   
        for ($i = 0; $i < count($data["report_info"]); $i++) {
            $data["report_info"][$i]["soldqty"] = $reports->get_sold_qty_by_month_year($data["report_info"][$i]["year"],$data["report_info"][$i]["month"]);
        }
        
        $this->view("print_templates/a4/print_dashboard_report",$data);
    }
    
    public function print_expense($_id){
        $expensesModel = $this->model("expenses");
        $data = array();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        
        $data["expense_info"] = $expensesModel->get_expense($id);
        
        
        $employees = $this->model("user");
        $employee_info = array();
        $employee_data = $employees->getAllVendorsEvenDeleted();
        for ($i = 0; $i < count($employee_data); $i++) {
            $employee_info[$employee_data[$i]["id"]] = $employee_data[$i];
        }
        $data["employee_info"]=$employee_info;
        
        $this->view("print_templates/pos8/print_expense",$data);
    }
    
    public function print_invoice($_id,$_forgift){
        $data = array();
        $id = filter_var($_id, FILTER_SANITIZE_NUMBER_INT);
        $data["gift_print"] = filter_var($_forgift, FILTER_SANITIZE_NUMBER_INT);
        $invoice = $this->model("invoice");
        $customers = $this->model("customers");
         $sales_man = $this->model("employees");
         $payments_class = $this->model("payments");
        
        $employees = $this->model("user");
        $employee_info = array();
        $employee_data = $employees->getAllVendorsEvenDeleted();
        for ($i = 0; $i < count($employee_data); $i++) {
            $employee_info[$employee_data[$i]["id"]] = $employee_data[$i];
        }
        
        
        $sales_man_info=$sales_man->getAllEmployeesEvenDeleted();
        $sales_man_array=array();
        for ($i = 0; $i < count($sales_man_info); $i++) {
            $sales_man_array[$sales_man_info[$i]["id"]] = $sales_man_info[$i];
        }
        
        $data["employee_info"]=$employee_info;
        $data["sales_man"]=$sales_man_array;
        

        
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        for ($i = 0; $i < count($all_currencies); $i++) {
            if($all_currencies[$i]["system_default"] == 1){
                $data["currency_system_default"] = $all_currencies[$i]["id"];
            }
        }

        $data["invoice"] = $invoice->getInvoiceById($id);
        $data["items"] = $invoice->getItemsOfInvoice($id);
        
        $data["customer_info"]="";
        $data["total_balance"]=0;
        if($data["invoice"][0]['customer_id']>0){
            $customers_info = $customers->getCustomersById($data["invoice"][0]['customer_id']);
            $data["customer_info"] = $customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"];
            $data["customer_address"] = $customers_info[0]["address"];
            $data["phone"] = $customers_info[0]["phone"];
            $data["address_area"] = $customers_info[0]["address_area"];
            $data["address_city"] = $customers_info[0]["address_city"];
            $data["address_street"] = $customers_info[0]["address_street"];
            $data["address_floor"] = $customers_info[0]["address_floor"];
            $data["address_note"] = $customers_info[0]["address_note"];
            $data["address_building"] = $customers_info[0]["address_building"];
            
            $data["total_balance"] = $payments_class->get_total_balance($data["invoice"][0]['customer_id'])+$customers_info[0]["starting_balance"];
        }
        
        $data["items_info_class"] = $this->model("items");
        
        $cashbox = $this->model("cashbox");
        $data["in_out"] = $cashbox->get_cash_details_of_invoice($id);
        
        $data["settings"] = $this->settings_info;
        
        
        
        
        $data["imei"] = $invoice->getIMEI($id);
        
        if($this->settings_info["usd_but_show_lbp_priority"]==1){
            $this->view("print_templates/pos8/print_usd_but_show_lbp_priority",$data);
        }else{
            if($this->settings_info["print_invoice_lbp_format"]=="print_invoice_lbp_8cm_1"){
                $this->view("print_templates/pos8/print_invoice_lbp_8cm_1",$data);
            }elseif($this->settings_info["print_invoice_lbp_format"]=="print_invoice_usd_8cm"){
                $this->view("print_templates/pos8/print_invoice_usd_8cm",$data);
            }elseif($this->settings_info["print_invoice_lbp_format"]=="print_invoice_cfa"){
                $this->view("print_templates/pos8/print_invoice_cfa",$data);
            }else{
                $this->view("print_templates/pos8/print_invoice_8cm",$data);
            }
        }    
        
    }
    
    public function print_hold_invoice($_pending_id){
        $data = array();
        $pending_id = filter_var($_pending_id, FILTER_SANITIZE_NUMBER_INT);
        $customers = $this->model("customers");
        $sales_man = $this->model("employees");
        $pending_invoices = $this->model("pendingInvoices");
        

        $pending_invoice_info =$pending_invoices->get($pending_id);
        
        $pinv= json_decode($pending_invoice_info["data"],true);
    
        $data["employee_id"]=$pending_invoice_info["created_by"];
        
        $employees = $this->model("user");
        $employee_info = array();
        $employee_data = $employees->getAllVendorsEvenDeleted();
        for ($i = 0; $i < count($employee_data); $i++) {
            $employee_info[$employee_data[$i]["id"]] = $employee_data[$i];
        }
        
        $sales_man_info=$sales_man->getAllEmployeesEvenDeleted();
        $sales_man_array=array();
        for ($i = 0; $i < count($sales_man_info); $i++) {
            $sales_man_array[$sales_man_info[$i]["id"]] = $sales_man_info[$i];
        }
        
        $data["employee_info"]=$employee_info;
        

        
        $currency = $this->model("currency");
        $all_currencies = $currency->getAllEnabledCurrencies();
        for ($i = 0; $i < count($all_currencies); $i++) {
            if($all_currencies[$i]["system_default"] == 1){
                $data["currency_system_default"] = $all_currencies[$i]["id"];
            }
        }

        $data["items"] = array();
        //var_dump($pinv["ALlitems"]);exit;
        for($i=0;$i<count($pinv["ALlitems"]);$i++){
            array_push($data["items"],$pinv["ALlitems"][$i]);
        }
        
        $data["customer_info"]=$pinv["customer"][0]["name"];
        
        $data["items_info_class"] = $this->model("items");
                
        $data["settings"] = $this->settings_info;
        
        
        //var_dump($pending_invoice_info);exit;
                
         $this->view("print_templates/pos8/print_usd_but_show_lbp_priority_hold",$data); 
        
    }
    
    public function print_suppliers_all_pi($_supplier_id,$_daterange){
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        
        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        
        $date_range_tmp = explode(" ", $daterange);
        $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[2])));
        
        
        $data = array();
        
        $data["start_date"] = $date_range[0];
        $data["end_date"] = $date_range[1];
        
        $suppliers = $this->model("suppliers");
        $currency = $this->model("currency");
        $data["settings"] = $this->settings_info;
        
        $currencies = $currency->getAllCurrenciesEvenDeleted();
        $currencies_array = array();
        for($i=0;$i<count($currencies);$i++){
            $currencies_array[$currencies[$i]["id"]]=$currencies[$i];
        }
        
        $data["supplier"] = $suppliers->getSupplier($supplier_id);
        
        $data["payments"] = $suppliers->getAllSuppliersPaymentsDateRange($supplier_id,$date_range);
        $data["invoices"] = $suppliers->getInvoicesOfSupplierDateRange($supplier_id,$date_range);
        $data["debitnotes"] = $suppliers->getAllSuppliersDebitNote_byid($supplier_id,$date_range);
           
        $data["previews_balance"]=$suppliers->get_previews_balance($supplier_id,$data["start_date"]);
        
        $data["allpi"] = array();
        $data["allpayments"] = array();

        for($i=0;$i<count($data["invoices"]);$i++){
            $dt= explode(" ", $data["invoices"][$i]["receive_invoice_date"]);
            $debit = 0;
            $credit = $data["invoices"][$i]["total"]*$data["invoices"][$i]["cur_rate"];
            $balance = $credit;
            array_push($data["allpi"], array("date"=>$dt[0],"description"=>"PI number - ".$data["invoices"][$i]["invoice_reference"],"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"timestamp"=>strtotime($data["invoices"][$i]["receive_invoice_date"]),"currency"=>$currencies_array[$data["invoices"][$i]["currency_id"]]["symbole"],"vat"=>$data["invoices"][$i]["invoice_tax"]*$data["invoices"][$i]["cur_rate"]));
        }
        
        for($i=0;$i<count($data["debitnotes"]);$i++){
            $dt= explode(" ", $data["debitnotes"][$i]["creation_date"]);
            $debit = 0;
            $credit = -$data["debitnotes"][$i]["debit_value"]*$data["debitnotes"][$i]["currency_rate"];
            array_push($data["allpi"], array("date"=>$dt[0],"description"=>"Debit note - ".$data["debitnotes"][$i]["id"]." for PI ".$data["debitnotes"][$i]["p_invoice"],"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["debitnotes"][$i]["creation_date"]),"currency"=>$currencies_array[$data["debitnotes"][$i]["payment_currency"]]["symbole"]));
        }
        
        usort($data["allpi"], function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        });
        
        for($i=0;$i<count($data["payments"]);$i++){
            $dt= explode(" ", $data["payments"][$i]["payment_date"]);
            $debit = $data["payments"][$i]["payment_value"]*$data["payments"][$i]["currency_rate"];
            $credit = 0;
            array_push($data["allpayments"], array("type"=>"payment","date"=>$dt[0],"description"=>"Receipt - ".$data["payments"][$i]["id"]." - ".$data["payments"][$i]["payment_note"],"qty"=>"","debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"timestamp"=>strtotime($data["payments"][$i]["payment_date"]+1),"currency"=>$currencies_array[$data["payments"][$i]["payment_currency"]]["symbole"]));
        }

        
        usort($data["allpayments"], function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        });

      
        
        $this->view("print_templates/a4/supplier_all_pi",$data);
    }
    
    public function print_suppliers_statement($_supplier_id){
        $customers = $this->model("customers");
        $suppliers = $this->model("suppliers");
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $debitnote = $this->model("debitnote");
        
        $supplier_id = filter_var($_supplier_id, FILTER_SANITIZE_NUMBER_INT);
        
        $data["payments"] = $suppliers->getAllSuppliersPayments($supplier_id);
        $data["supplier"] = $suppliers->getSupplier($supplier_id);
        
        $debitnotes = $suppliers->getAllSuppliersDebitNote_byid($supplier_id);
        
        $data["settings"] = $this->settings_info;
        $data["invoices"] = $suppliers->getPIItemsOfSupplier($supplier_id);
        $data["table"]=array();
        
        array_push($data["table"], array("date"=>"","description"=>"Starting Balance","qty"=>"","debit"=>0,"credit"=>$data["supplier"][0]["starting_balance"],"balance"=>0));
        
        //var_dump($debitnotes);
        for($i=0;$i<count($debitnotes);$i++){
            $dt= explode(" ", $debitnotes[$i]["creation_date"]);
            //????????????
            $debit = 0;
            $credit = $debitnotes[$i]["debit_value"]*$debitnotes[$i]["currency_rate"];
            array_push($data["table"], array("date"=>$dt[0],"description"=>"Debit note - ".$debitnotes[$i]["id"],"qty"=>"","debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($debitnotes[$i]["creation_date"])));
        }

        for($i=0;$i<count($data["invoices"]);$i++){
            $dt= explode(" ", $data["invoices"][$i]["receive_invoice_date"]);
            $debit = 0;
            
            $after_disc1 = floor($data["invoices"][$i]["qty"])*((float)$data["invoices"][$i]["cost"]-(float)$data["invoices"][$i]["cost"]*$data["invoices"][$i]["discount_percentage"]/100);
            $after_disc2 = $after_disc1-($after_disc1*$data["invoices"][$i]["discount_percentage_2"]/100);
            
            if($data["invoices"][$i]["vat"]==0){
                $credit = $after_disc2;
            }else{
                $credit = ($after_disc2+$after_disc2*($this->settings_info["vat"]-1))*(1-$data["invoices"][$i]["discount_after_vat"]/100);
            }
            
            $credit = $credit;
            array_push($data["table"], array("date"=>$dt[0],"description"=>$data["invoices"][$i]["description"],"qty"=>floor($data["invoices"][$i]["qty"]),"debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["invoices"][$i]["receive_invoice_date"])));
        }
        
        for($i=0;$i<count($data["payments"]);$i++){
            $dt= explode(" ", $data["payments"][$i]["payment_date"]);
            $debit = $data["payments"][$i]["payment_value"]*$data["payments"][$i]["currency_rate"];
            $credit = 0;
            array_push($data["table"], array("type"=>"payment","date"=>$dt[0],"description"=>"Payment - ".$data["payments"][$i]["id"],"qty"=>"","debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"timestamp"=>strtotime($data["payments"][$i]["payment_date"]+1)));
        }
        
        usort($data["table"], function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        });
        
        
        $data["st_of_acc"] = array();
        $balance = 0;
        $total_debit = 0;
        $total_credit = 0;
        for($i=0;$i<count($data["table"]);$i++){
            $debit = $data["table"][$i]["debit"];
            $total_debit+=$debit;

            $credit = $data["table"][$i]["credit"];
            $total_credit+=$credit;

            $balance += ($debit-$credit);
            
            if($debit==0){
                $debit="";
            }
            
            if($credit==0){
                $credit="";
            }
            
            array_push($data["st_of_acc"], array("date"=>$data["table"][$i]["date"],"description"=>$data["table"][$i]["description"],"qty"=>$data["table"][$i]["qty"],"debit"=>$debit,"credit"=>$credit,"balance"=>$balance));
        }
        
        $data["total_debit"]=$total_debit;
        $data["total_credit"]=$total_credit;
        $this->view("print_templates/a4/supplier_statement",$data);
    }
    
    public function print_delivery_statement__($_customer_id,$_daterange) {
        $data=array();
        $this->view("print_templates/a4/customer_statement_new",$data);
    }
    
    public function print_customer_statement__($_customer_id,$_daterange) {
        
        $customers = $this->model("customers");
        $supplier_id = $customers->is_connected_to_supplier(filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT));
        if($supplier_id>0){
            self::__print_customer_statement__supplier_connected($_customer_id,$_daterange,$supplier_id);
        }else{
            self::__print_customer_statement__($_customer_id,$_daterange);
        }
        
        
    }
    
    public function imei_report($_daterange) {
        $unique_items = $this->model("uniqueItems");
        $suppliers= $this->model("suppliers");
        
        $date_filter = filter_var($_daterange, self::conversion_php_version_filter());
        
        
        $all_suppliers = $suppliers->getAllSuppliersEvenDeleted();
        $data["all_suppliers"] = array();
        for($i=0;$i<count($all_suppliers);$i++){
            $data["all_suppliers"][$all_suppliers[$i]["id"]]=$all_suppliers[$i];
        }
        
        $date_range = array();
        if($date_filter=="thismonth"){
            $date_range[0] = date('Y-m-d', strtotime(date("Y")."-".date("M")."-01"));
            $date_range[1] = date('Y-m-d');
        }else{
            $date_range_tmp = explode(" - ", $date_filter);
            $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
            $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[1])));
        }
        
        
        if($_SESSION['global_admin_exist']==1 && $_SESSION['centralize']==0){
            $unique_items->update_registered_store($_SESSION['store_id']);
        }
        
        $data["first_imei"] = $unique_items->get_oldest();
        
        
        $data["imei_details"] = $unique_items->get_details($date_range,0);

        
        $data["start_date"] = $date_range[0];
        $data["end_date"] = $date_range[1];
        
        $data["settings"] = $this->settings_info;
        
        
        
        //$data["imei_details"][$i]["customer_id"]
        
        $this->view("print_templates/a4/imei",$data);
    }
    
    
    public function __print_customer_statement__supplier_connected($_customer_id,$_daterange,$supplier_id) {
        $customers = $this->model("customers");
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $creditnote = $this->model("creditnote");

        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        
        $date_range_tmp = explode(" ", $daterange);
        $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[2])));
        
        $items = $this->model("items");
        $all_items = $items->getAllItemsEvenDeleted();
        $items_array = array();
        for($i=0;$i<count($all_items);$i++){
            $items_array[$all_items[$i]["id"]] = $all_items[$i]["description"];
        }
        
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        
        $data["payments"] = $payments->getAllDebtsPaymentOfCustomerDateRange($customer_id,$date_range);
        $data["customer"] = $customers->getCustomersById($customer_id);
        $creditnotes = $creditnote->get_credit_note_for_customersDateRange($customer_id,$date_range);
        
        $data["previews_balance"] = $payments->get_previews_balance($customer_id,$date_range[0]);
        $data["start_date_client_days"] = $customers->start_date_customer($customer_id);        
        $data["start_date"] = $date_range[0];
        $data["end_date"] = $date_range[1];
        
        $data["settings"] = $this->settings_info;
        $data["invoices"] = $customers->get_all_invoices_of_customerDateRange($customer_id,$date_range);
        for($i=0;$i<count($data["invoices"]);$i++){
            $data["inv_items"][$data["invoices"][$i]["id"]] = $invoice->getItemsOfInvoiceDetails($data["invoices"][$i]["id"]);
        }
        
        $data["table"]=array();
        
        
        $data["total_debit"]=0;
        $data["total_credit"]=0;
        
        /* customers */
        for($i=0;$i<count($creditnotes);$i++){
            $dt= explode(" ", $creditnotes[$i]["creation_date"]);
            $debit = 0;
            $credit = $creditnotes[$i]["credit_value"]*$creditnotes[$i]["currency_rate"];
            $data["total_credit"]+=$credit;
            array_push($data["table"], array("type"=>"crnote","date"=>$dt[0],"description"=>"Credit note - ".$creditnotes[$i]["id"],"qty"=>"","debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($creditnotes[$i]["creation_date"]),"independant"=>0));
        }
        
        
        for($i=0;$i<count($data["invoices"]);$i++){
            if($data["invoices"][$i]["closed"]==0 || $data["invoices"][$i]["auto_closed"]==1){
                $dt= explode(" ", $data["invoices"][$i]["creation_date"]);
                $tt = self::get_total_invoice_details($data["invoices"][$i]);
                $debit = $tt["total"];// $data["invoices"][$i]["total_value"]+$data["invoices"][$i]["invoice_discount"];
                $data["total_debit"]+=$debit;
                $credit = 0;
                $descri = "Invoice ".$data["invoices"][$i]["id"];
                array_push($data["table"], array("type"=>"inv","date"=>$dt[0],"description"=>$descri." ".$data["invoices"][$i]["creation_date"],"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["invoices"][$i]["creation_date"]),"independant"=>0));   
            }  
            if($data["invoices"][$i]["closed"]==1 && $data["invoices"][$i]["auto_closed"]==0){
                $dt= explode(" ", $data["invoices"][$i]["creation_date"]);
                $tt = self::get_total_invoice_details($data["invoices"][$i]);
                $debit = $tt["total"];// $data["invoices"][$i]["total_value"]+$data["invoices"][$i]["invoice_discount"];
                $data["total_debit"]+=$debit;
                
                
                $credit = $data["invoices"][$i]["total_value"]+$data["invoices"][$i]["invoice_discount"];
                $data["total_credit"]+=$credit;
                $descri = "Invoice ".$data["invoices"][$i]["id"];
                array_push($data["table"], array("type"=>"inv","date"=>$dt[0],"description"=>$descri,"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["invoices"][$i]["creation_date"]),"independant"=>0));   
            }  
        } 
      
        for($i=0;$i<count($data["payments"]);$i++){
            $dt= explode(" ", $data["payments"][$i]["value_date"]);
            $debit = 0;
            $credit = $data["payments"][$i]["balance"]*$data["payments"][$i]["rate"];
            $data["total_credit"]+=$credit;
            $note="";
            if(strlen($data["payments"][$i]["note"])){
                $note="<br/><small>".$data["payments"][$i]["note"]."</small>";
            }
            
            array_push($data["table"], array("type"=>"payment ","date"=>$dt[0],"description"=>"Debt Payment - ".$data["payments"][$i]["id"]." ".$data["payments"][$i]["value_date"].$note,"qty"=>"","debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["payments"][$i]["value_date"]),"independant"=>0));
        }
        
        
        /* suppliers */
        
        
        $suppliers = $this->model("suppliers");
        
        
        $supplier_info = $suppliers->get_supplier_by_id($supplier_id);
        $currency=1;
        if($currency==1){
            if($supplier_info[0]["usd_starting_balance"]<0){
                array_push($data["table"], array("date"=>"","description"=>"<b>Supplier</b>:<b>Starting Balance</b>","qty"=>"","debit"=>$supplier_info[0]["usd_starting_balance"],"credit"=>0,"balance"=>0));
            }else{
                array_push($data["table"], array("date"=>"","description"=>"<b>Supplier</b>:<b>Starting Balance</b>","qty"=>"","debit"=>0,"credit"=>$supplier_info[0]["usd_starting_balance"],"balance"=>0));
            }
        }
        
        
        
        $brought_balance = $suppliers->get_balances_suppliers($currency,$supplier_id,$daterange,0,1);
        
        $sup_deb=0;
        $sup_cre=0;
        if($brought_balance<0){
            $sup_cre=abs($brought_balance);
        }else{
            $sup_deb=abs($brought_balance);
        }
        
        array_push($data["table"], array("date"=>"","description"=>"<b>Supplier</b>:<b>Preview Balance Till ".date('Y-m-d', strtotime('-1 day', strtotime($data["start_date"])))."</b>","qty"=>"","debit"=>$sup_deb,"credit"=>$sup_cre,"balance"=>0));
        
        
        $suppliers_details = $suppliers->get_balances_suppliers_details($supplier_id,$currency,$date_range);
    
        for($i=0;$i<count($suppliers_details);$i++){
            if($suppliers_details[$i]["st_balance"]==0){
                $dte= explode(" ", $suppliers_details[$i]["creation_date"]);
                array_push($data["table"], array("type"=>"","date"=>$dte[0],"description"=>"<b>Supplier</b>: ".$suppliers_details[$i]["desc"],"qty"=>0,"debit"=>$suppliers_details[$i]["debit"],"credit"=>$suppliers_details[$i]["credit"],"balance"=>0,"timestamp"=>$suppliers_details[$i]["timestamp"]+1,"independant"=>0));
            }
            
        }
        
        
        
        usort($data["table"], function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        });
        
        
        $data["st_of_acc"] = array();

       $start_debit=0;
       $start_credit=0;
       if($data["customer"][0]["starting_balance"]>0){
            $start_debit=0;//$data["customer"][0]["starting_balance"];
            $start_credit=0;
       }else{
            $start_debit=0;
            $start_credit=0;//$data["customer"][0]["starting_balance"];
       } 
       array_push($data["st_of_acc"], array("date"=>"","description"=>"<b>Starting Balance</b>","qty"=>"","debit"=>$start_debit,"credit"=>$start_credit,"balance"=>$data["customer"][0]["starting_balance"]));  
       
       $previews_debit=0;
       $previews_credit=0;
       if($data["previews_balance"]>0){
            $previews_debit=$data["previews_balance"];
            $previews_credit=0;
       }else{
            $previews_debit=0;
            $previews_credit=$data["previews_balance"];
       } 

       array_push($data["st_of_acc"], array("date"=>"","description"=>"<b>Preview Balance Till ".date('Y-m-d', strtotime('-1 day', strtotime($data["start_date"])))."</b>","qty"=>"","debit"=>$previews_debit,"credit"=>$previews_credit,"balance"=>$data["previews_balance"]+$data["customer"][0]["starting_balance"]));
        
        
        $total_debit = 0;//$start_debit+$previews_debit;
        $total_credit = 0;//$start_credit+$previews_credit;
        $balance = $data["previews_balance"]+$data["customer"][0]["starting_balance"];
        

        for($i=0;$i<count($data["table"]);$i++){
            if($data["table"][$i]["independant"]==0){
                $debit = $data["table"][$i]["debit"];
                $total_debit+=$debit;

                $credit = $data["table"][$i]["credit"];
                $total_credit+=$credit;

                $balance += ($debit-$credit);

                if($debit==0){
                    $debit=0;
                }

                if($credit==0){
                    $credit=0;
                }

                array_push($data["st_of_acc"], array("date"=>$data["table"][$i]["date"],"description"=>$data["table"][$i]["description"],"qty"=>$data["table"][$i]["qty"],"debit"=>$debit,"credit"=>$credit,"balance"=>$balance));
            }
        }
        
        $data["total_debit"]=$total_debit;
        $data["total_credit"]=$total_credit;
        
        
        
        
        
        $this->view("print_templates/a4/customer_statement_new_supplier_connected",$data);
    }
    
    
    public function __print_customer_statement__($_customer_id,$_daterange) {
        $customers = $this->model("customers");
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $creditnote = $this->model("creditnote");
        
        
        
        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        
        $date_range_tmp = explode(" ", $daterange);
        $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[2])));
        
        $items = $this->model("items");
        $all_items = $items->getAllItemsEvenDeleted();
        $items_array = array();
        for($i=0;$i<count($all_items);$i++){
            $items_array[$all_items[$i]["id"]] = $all_items[$i]["description"];
        }
        
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        
        $data["payments"] = $payments->getAllDebtsPaymentOfCustomerDateRange($customer_id,$date_range);
        $data["customer"] = $customers->getCustomersById($customer_id);
        $creditnotes = $creditnote->get_credit_note_for_customersDateRange($customer_id,$date_range);
        
        $data["previews_balance"] = $payments->get_previews_balance($customer_id,$date_range[0]);
        $data["start_date_client_days"] = $customers->start_date_customer($customer_id);        $data["start_date"] = $date_range[0];
        $data["end_date"] = $date_range[1];
        
        $data["settings"] = $this->settings_info;
        $data["invoices"] = $customers->get_all_invoices_of_customerDateRange($customer_id,$date_range);
        for($i=0;$i<count($data["invoices"]);$i++){
            $data["inv_items"][$data["invoices"][$i]["id"]] = $invoice->getItemsOfInvoiceDetails($data["invoices"][$i]["id"]);
        }
        
        $data["table"]=array();
        
        $data["total_debit"]=0;
        $data["total_credit"]=0;
        
        for($i=0;$i<count($creditnotes);$i++){
            $dt= explode(" ", $creditnotes[$i]["creation_date"]);
            $debit = 0;
            $credit = $creditnotes[$i]["credit_value"]*$creditnotes[$i]["currency_rate"];
            $data["total_credit"]+=$credit;
            array_push($data["table"], array("type"=>"crnote","date"=>$dt[0],"description"=>"Credit note - ".$creditnotes[$i]["id"],"qty"=>"","debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($creditnotes[$i]["creation_date"]),"independant"=>0));
        }
        
        
        for($i=0;$i<count($data["invoices"]);$i++){
            if($data["invoices"][$i]["closed"]==0 || $data["invoices"][$i]["auto_closed"]==1){
                $dt= explode(" ", $data["invoices"][$i]["creation_date"]);
                $tt = self::get_total_invoice_details($data["invoices"][$i]);
                $debit = $tt["total"];// $data["invoices"][$i]["total_value"]+$data["invoices"][$i]["invoice_discount"];
                $data["total_debit"]+=$debit;
                $credit = 0;
                $descri = "Invoice ".$data["invoices"][$i]["id"];
                array_push($data["table"], array("type"=>"inv","date"=>$dt[0],"description"=>$descri,"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["invoices"][$i]["creation_date"]),"independant"=>0));   
            } 
            if($data["invoices"][$i]["closed"]==1 && $data["invoices"][$i]["auto_closed"]==0){
                $dt= explode(" ", $data["invoices"][$i]["creation_date"]);
                $tt = self::get_total_invoice_details($data["invoices"][$i]);
                $debit = $tt["total"];// $data["invoices"][$i]["total_value"]+$data["invoices"][$i]["invoice_discount"];
                $data["total_debit"]+=$debit;
                $credit = $data["invoices"][$i]["total_value"]+$data["invoices"][$i]["invoice_discount"];
                $data["total_credit"]+=$credit;
                $descri = "Invoice ".$data["invoices"][$i]["id"];
                array_push($data["table"], array("type"=>"inv","date"=>$dt[0],"description"=>$descri,"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["invoices"][$i]["creation_date"]),"independant"=>0));   
            }  
        } 
      
        for($i=0;$i<count($data["payments"]);$i++){
            $dt= explode(" ", $data["payments"][$i]["value_date"]);
            $debit = 0;
            $credit = $data["payments"][$i]["balance"]*$data["payments"][$i]["rate"];
            $data["total_credit"]+=$credit;
            
            $note="";
            if(strlen($data["payments"][$i]["note"])){
                $note="<br/><small>".$data["payments"][$i]["note"]."</small>";
            }
            
            array_push($data["table"], array("type"=>"payment","date"=>$dt[0],"description"=>"Debt Payment - ".$data["payments"][$i]["id"]." ".$note,"qty"=>"","debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($data["payments"][$i]["value_date"]),"independant"=>0));
        }
        
        
        usort($data["table"], function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        });
        
        
        $data["st_of_acc"] = array();

       $start_debit=0;
       $start_credit=0;
       if($data["customer"][0]["starting_balance"]>0){
            $start_debit=0;//$data["customer"][0]["starting_balance"];
            $start_credit=0;
       }else{
            $start_debit=0;
            $start_credit=0;//$data["customer"][0]["starting_balance"];
       } 
       
       $start_bal="Starting Balance";
       if($this->settings_info["arabic_stmt_and_invoice"]==1){
            $start_bal="بداية الرصيد";
        }
       
       
       
       array_push($data["st_of_acc"], array("date"=>"","description"=>"<b>".$start_bal."</b>","qty"=>"","debit"=>$start_debit,"credit"=>$start_credit,"balance"=>$data["customer"][0]["starting_balance"]));  
       
       $previews_debit=0;
       $previews_credit=0;
       if($data["previews_balance"]>0){
            $previews_debit=$data["previews_balance"];
            $previews_credit=0;
       }else{
            $previews_debit=0;
            $previews_credit=$data["previews_balance"];
       } 

       $prev_bal="Preview Balance Till";
       if($this->settings_info["arabic_stmt_and_invoice"]==1){
            $prev_bal="رصيد ما قيل تاريخ";
        }
        
       array_push($data["st_of_acc"], array("date"=>"","description"=>"<b>".$prev_bal." ".date('Y-m-d', strtotime('-1 day', strtotime($data["start_date"])))."</b>","qty"=>"","debit"=>$previews_debit,"credit"=>$previews_credit,"balance"=>$data["previews_balance"]+$data["customer"][0]["starting_balance"]));
        
        
        $total_debit = 0;//$start_debit+$previews_debit;
        $total_credit = 0;//$start_credit+$previews_credit;
        $balance = $data["previews_balance"]+$data["customer"][0]["starting_balance"];
        
        
        
        

        for($i=0;$i<count($data["table"]);$i++){
            if($data["table"][$i]["independant"]==0){
                $debit = $data["table"][$i]["debit"];
                $total_debit+=$debit;

                $credit = $data["table"][$i]["credit"];
                $total_credit+=$credit;

                $balance += ($debit-$credit);

                if($debit==0){
                    $debit="";
                }

                if($credit==0){
                    $credit="";
                }

                array_push($data["st_of_acc"], array("date"=>$data["table"][$i]["date"],"description"=>$data["table"][$i]["description"],"qty"=>$data["table"][$i]["qty"],"debit"=>$debit,"credit"=>$credit,"balance"=>$balance));
            }
        }
        
        $data["total_debit"]=$total_debit;
        $data["total_credit"]=$total_credit;
        
        //$data["arabic_stmt_and_invoice"]=$this->settings_info["arabic_stmt_and_invoice"];
        //$this->view("print_templates/a4/customer_statement_new",$data);
        if($this->settings_info["arabic_stmt_and_invoice"]==1){
            $this->view("print_templates/a4/customer_statement_new_ar",$data);
        }else{
            $this->view("print_templates/a4/customer_statement_new",$data);
        }
        
        
    }
    
    public function print_customer_statement($_customer_id,$_daterange) {
        $customers = $this->model("customers");
        $invoice = $this->model("invoice");
        $payments = $this->model("payments");
        $creditnote = $this->model("creditnote");
        
        $daterange = filter_var($_daterange, self::conversion_php_version_filter());
        
        $date_range_tmp = explode(" ", $daterange);
        $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
        $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[2])));
        
        $items = $this->model("items");
        $all_items = $items->getAllItemsEvenDeleted();
        $items_array = array();
        for($i=0;$i<count($all_items);$i++){
            $items_array[$all_items[$i]["id"]] = $all_items[$i]["description"];
        }
        
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        
        $data["payments"] = $payments->getAllDebtsPaymentOfCustomerDateRange($customer_id,$date_range);
        $data["customer"] = $customers->getCustomersById($customer_id);
        $creditnotes = $creditnote->get_credit_note_for_customersDateRange($customer_id,$date_range);
        
        $data["previews_balance"] = $payments->get_previews_balance($customer_id,$date_range[0]);
        
        $data["start_date"] = $date_range[0];
        $data["end_date"] = $date_range[1];
        
        $data["settings"] = $this->settings_info;
        $data["invoices"] = $customers->get_all_invoices_of_customerDateRange($customer_id,$date_range);
        for($i=0;$i<count($data["invoices"]);$i++){
            $data["inv_items"][$data["invoices"][$i]["id"]] = $invoice->getItemsOfInvoiceDetails($data["invoices"][$i]["id"]);
        }
        
        $data["table"]=array();
        
        
        for($i=0;$i<count($creditnotes);$i++){
            $dt= explode(" ", $creditnotes[$i]["creation_date"]);
            $debit = 0;
            $credit = $creditnotes[$i]["credit_value"]*$creditnotes[$i]["currency_rate"];
            if($credit==""){
                $credit=0;
            }
            
            array_push($data["table"], array("type"=>"crnote","date"=>$dt[0],"description"=>"Credit note - ".$creditnotes[$i]["id"],"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>0,"timestamp"=>strtotime($creditnotes[$i]["creation_date"]),"independant"=>0,"unit_price"=>0));
        }
        
        
        for($i=0;$i<count($data["invoices"]);$i++){
            $dt= explode(" ", $data["invoices"][$i]["creation_date"]);
            for($k=0;$k<count($data["inv_items"][$data["invoices"][$i]["id"]]);$k++){
                $debit = $data["inv_items"][$data["invoices"][$i]["id"]][$k]["final_price_disc_qty"];//$data["inv_items"][$data["invoices"][$i]["id"]][$k]["description"]
                $credit = 0;
                $descri = "";
                $balance=0;
                if(isset($items_array[$data["inv_items"][$data["invoices"][$i]["id"]][$k]["item_id"]])){
                    $descri = $items_array[$data["inv_items"][$data["invoices"][$i]["id"]][$k]["item_id"]];
                }else{
                    $descri = $data["inv_items"][$data["invoices"][$i]["id"]][$k]["description"];
                }
                
                array_push($data["table"], array("type"=>"inv","date"=>$dt[0],"description"=>$descri,"qty"=>($data["inv_items"][$data["invoices"][$i]["id"]][$k]["qty"]),"debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"timestamp"=>strtotime($data["invoices"][$i]["creation_date"]),"independant"=>0,"unit_price"=>$data["inv_items"][$data["invoices"][$i]["id"]][$k]["selling_price"]));
            }
            if($data["invoices"][$i]["invoice_discount"]<0){
                $debit = 0;
                $credit = abs($data["invoices"][$i]["invoice_discount"]);
                $balance=0;
                if($credit==""){
                    $credit=0;
                }
                array_push($data["table"], array("type"=>"inv_disc","date"=>$dt[0],"description"=>"Invoice Discount - ".$data["invoices"][$i]["id"],"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"timestamp"=>(strtotime($data["invoices"][$i]["creation_date"])+1),"independant"=>0,"unit_price"=>0));
            }
            if($data["invoices"][$i]["closed"]==1 && $data["invoices"][$i]["auto_closed"]==0){
                $debit = 0;
                $credit = $data["invoices"][$i]["total_value"]+$data["invoices"][$i]["invoice_discount"];
                if($credit==""){
                    $credit=0;
                }
                $balance=0;
                array_push($data["table"], array("type"=>"payment","date"=>$dt[0],"description"=>"Cash payment","qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"timestamp"=>(strtotime($data["invoices"][$i]["creation_date"])+2),"independant"=>0,"unit_price"=>0));
            }   
        }
        
      
        for($i=0;$i<count($data["payments"]);$i++){
            $dt= explode(" ", $data["payments"][$i]["balance_date"]);
            $debit = 0;
            $credit = $data["payments"][$i]["balance"]*$data["payments"][$i]["rate"];
            if($credit==""){
                $credit=0;
            }
            
            $note="";
            if(strlen($data["payments"][$i]["note"])){
                $note="<br/><small>".$data["payments"][$i]["note"]."</small>";
            }
            
            array_push($data["table"], array("type"=>"payment","date"=>$dt[0],"description"=>"Debt Payment - ".$data["payments"][$i]["id"].$note,"qty"=>0,"debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"timestamp"=>strtotime($data["payments"][$i]["balance_date"]),"independant"=>0,"unit_price"=>0));
        }
        
        
        usort($data["table"], function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        });
        
        
        $data["st_of_acc"] = array();

       $start_debit=0;
       $start_credit=0;
       if($data["customer"][0]["starting_balance"]>0){
            $start_debit=$data["customer"][0]["starting_balance"];
            $start_credit=0;
       }else{
            $start_debit=0;
            $start_credit=$data["customer"][0]["starting_balance"];
       } 
       array_push($data["st_of_acc"], array("date"=>"","description"=>"<b>Starting Balance</b>","qty"=>0,"debit"=>$start_debit,"credit"=>$start_credit,"balance"=>$data["customer"][0]["starting_balance"],"unit_price"=>0));  
       
       $previews_debit=0;
       $previews_credit=0;
       if($data["previews_balance"]>0){
            $previews_debit=$data["previews_balance"];
            $previewst_credit=0;
       }else{
            $previews_debit=0;
            $previewst_credit=$data["previews_balance"];
       } 

       array_push($data["st_of_acc"], array("date"=>"","description"=>"<b>Preview Balance Till ".date('Y-m-d', strtotime('-1 day', strtotime($data["start_date"])))."</b>","qty"=>0,"debit"=>$previews_debit,"credit"=>$previews_credit,"balance"=>$data["previews_balance"]+$data["customer"][0]["starting_balance"],"unit_price"=>0));
        
        
        $total_debit = $start_debit+$previews_debit;
        $total_credit = $start_credit+$previewst_credit;
        
        
        $balance = $data["previews_balance"]+$data["customer"][0]["starting_balance"];
        for($i=0;$i<count($data["table"]);$i++){
            if($data["table"][$i]["independant"]==0){
                $debit = $data["table"][$i]["debit"];
                $total_debit+=$debit;

                $credit = $data["table"][$i]["credit"];
                if($credit==""){
                    $credit=0;
                }
                $total_credit+=$credit;

                $balance += ($debit-$credit);

                if($debit==0){
                    //$debit="";
                }

                //if($credit==0){
                    //$credit="";
                //}

                array_push($data["st_of_acc"], array("date"=>$data["table"][$i]["date"],"description"=>$data["table"][$i]["description"],"qty"=>$data["table"][$i]["qty"],"debit"=>$debit,"credit"=>$credit,"balance"=>$balance,"unit_price"=>$data["table"][$i]["unit_price"]));
            }
        }
        
        $data["total_debit"]=$total_debit;
        $data["total_credit"]=$total_credit;
        
        $data["total_credit"]=$this->settings_info["number_of_decimal_points"];
        $data["self"]=$this;
        $this->view("print_templates/a4/customer_statement",$data);
    }
    
}
