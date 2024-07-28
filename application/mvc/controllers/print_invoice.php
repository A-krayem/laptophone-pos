<?php

require 'libraries/printer_interface/escpos/autoload.php';
require_once("application/core/lib/I18N/Arabic.php");

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Devices\AuresCustomerDisplay;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintBuffers\ImagePrintBuffer;

class print_invoice extends Controller {
    
    public $settings_info = null;
    public $settings_info_local = null;
    
    public function __construct() { 
        $this->checkAuth();
        $this->settings_info = self::getSettings();
        $this->settings_info_local = self::get_settings_local();
    }
    
    public function testest(){
     
    }
    
    public function prepare_pdf_version($_invoice_id){
        require_once 'cronjob_para.php';
        $invoice_id = filter_var($_invoice_id, FILTER_SANITIZE_NUMBER_INT);
        $main_dir = "./";
        $website = WEBSITE;

        
        $generate_pdf = $this->model("generate_pdf");
        
        
        $generate_pdf->generate_pdf_invoice($invoice_id,$main_dir,$website);
        
        echo json_encode(array("data/invoice_".$invoice_id.".pdf"));
    }
    
    public function print_statement($_customer_id){
        $data_array["data"] = array();
        $customer_id = filter_var($_customer_id, FILTER_SANITIZE_NUMBER_INT);
        if($customer_id==0) {echo json_encode($data_array);return;}
        
        $settings = $this->model("settings");
        $settings_payments_methos = $settings->get_all_payment_method();
        $p_method = array();
        for($i=0;$i<count($settings_payments_methos);$i++){
            $p_method[$settings_payments_methos[$i]["id"]] = $settings_payments_methos[$i]["method_name"];
        }
        
        $store = $this->model("store");
        $stores = $store->getStores();
        $stores_info = array();
        for ($i = 0; $i < count($stores); $i++) {
            $stores_info[$stores[$i]["id"]] = $stores[$i]["name"];
        }
        
        
        $stm = self::get_customer_statement_data($customer_id);
        
        try {
            $connector = null;
            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);
            
            $this->settings_info["default_currency_symbol"] ="";
            
            
            /* Name of shop */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);

            if($this->settings_info["hide_shop_name_on_invoice"]==0){
                $printer -> text($this->settings_info["shop_name"]."\n\n");
            }

            if(strlen($this->settings_info["address"])>0){
                $printer -> text($this->settings_info["address"]."\n");
            }

            if(strlen($this->settings_info["phone_nb"])>0){
                $printer -> text($this->settings_info["phone_nb"]."\n");
            }

            $printer -> selectPrintMode();
            $printer -> feed();


            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION['store_id']);


            /* header info */
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Statement");
            $printer -> text("\n");
            $printer -> text("Branch: ".$store_info[0]["name"]);
            $printer -> feed(2);
            
            $printer -> selectPrintMode(Printer::MODE_FONT_A);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            
            $total_remain = 0;
            $cnt = 0;
            foreach ($stm as $key => $value) {
                
                if($value["credit"]==1){
                    if($value["deleted"]==0){
                        $total_remain += $value["total_invoice_value"];
                    }
                }else{
                    if($value["deleted"]==0){
                        $total_remain -= $value["total_payment_value"];
                    }
                }
                
              
                
                $payment = 0;
                if($value["deleted"]==0){
                    if($value["paid_directly"]==1){
                        $payment = self::value_format_custom((float)$value["total_invoice_value"],$this->settings_info);
                    }else{
                        $payment = "-".self::value_format_custom((float)$value["total_payment_value"],$this->settings_info);
                    }
                }
                
                $text = "";
                if($cnt==0){
                    $text = sprintf('%-10s %-9s %11s %-11s',explode(" ", $value["creation_date"])[0],"",self::value_format_custom((float)$value["total_invoice_value"]."",$this->settings_info),"Remain");
                } else {
                    if($value["total_invoice_value"]==0){
                        $text = sprintf('%-10s %-9s %11s %11s',explode(" ", $value["creation_date"])[0],"PAY-".$value["ref_payment"],$payment,self::value_format_custom((float)$total_remain."",$this->settings_info));
                    }else{
                        $text = sprintf('%-10s %-9s %11s %11s',explode(" ", $value["creation_date"])[0],"INV-".$value["invoice_id"],self::value_format_custom((float)$value["total_invoice_value"]."",$this->settings_info),self::value_format_custom((float)$total_remain."",$this->settings_info));
                    }  
                }
            
                if($cnt==0){
                    $printer -> text($text);
                     $printer->feed(1);
                    $printer -> text("------------------------------------------");
                }else{
                    $printer -> text($text);
                }
                

                $printer->feed(1);
                $cnt++;
            }
            
            $printer->feed(1);
            $printer -> text("Total Remain: ".self::value_format_custom((float)$total_remain,$this->settings_info));
            
            $printer->feed(4);
            $printer->cut();
            $printer->close();

        } catch (Exception $ex) {
            
        }
        
    }
    
    public function print_returned_item($returned_id){
        self::giveAccessTo(array(2,4));
        $user = $this->model("user");
        $invoice = $this->model("invoice");
         $items = $this->model("items");
         if($this->settings_info["return_report"]=="1"){
            try {
                    $connector = null;
                    $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                    $printer = new Printer($connector);

                    $vendor_id = $_SESSION['id'];

                    $vendor_d['id'] = $vendor_id;
                    $vendor_info = $user->get_user($vendor_d);

                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Report Returned item\n\n");

                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Vendor Name: ".$vendor_info[0]["name"]."\n\n");

                    $returned_item = $invoice->getReturnedItemsById($returned_id);

                    $invoice_details = $invoice->getInvoiceById($returned_item[0]["invoice_id"]);

                    $item_info = $items->get_item($returned_item[0]["item_id"]);

                    if(strlen($item_info[0]["description"])>18){
                        //$item_info[0]["description"] = substr($item_info[0]["description"], 0, 18)."..";
                    }

                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                    $printer -> text("Item ID:");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> text(" ".$item_info[0]["id"]."\n\n");

                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                    $printer -> text("Sale Date:");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> text(" ".$invoice_details[0]["creation_date"]."\n\n");

                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE); 
                    $printer -> text("Item Description:");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> text(" ".$item_info[0]["description"]."\n\n");


                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE); 
                    $printer -> text("Original Price:");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> text(" ".self::value_format_custom($returned_item[0]["selling_price"],$this->settings_info_local)."\n\n");

                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE); 
                    $printer -> text("Discount:");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> text(" ".number_format($returned_item[0]["discount"],2)."%\n\n");

                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE); 
                    $printer -> text("Total Price:");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> text(" ".self::value_format_custom($returned_item[0]["selling_price"]*(1-$returned_item[0]["discount"]/100),$this->settings_info_local)."\n");
                    $printer->feed(4);
                    $printer->cut();
                    $printer->close();

            } catch (Exception $e) {

            }
         }
    }
    
    public function print_preview_full_report_custom_with_vat($cashbox_id){
        self::giveAccessTo(array(2,4));
        if($this->settings_info["end_of_day_report"]=="1"){
            $invoice = $this->model("invoice");
            $items = $this->model("items");
            $cashbox = $this->model("cashbox");
            $user = $this->model("user");
            $expenses = $this->model("expenses");
            $customers = $this->model("customers");
            $settings = $this->model("settings");
            $suppliers = $this->model("suppliers");
            
            $payment_types = $settings->get_all_payment_method();
            $payment_info = array();
            for($i=0;$i<count($payment_types);$i++){
                $payment_info[$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
            }
            
            $vendor_id = $_SESSION['id'];
            $vendor_d['id'] = $vendor_id;
            $vendor_info = $user->get_user($vendor_d);
            $info = $cashbox->getTodayCashbox($_SESSION['store_id'],$vendor_id);   
          
            $total_cashback = $invoice->get_total_cashback($info[0]["id"]);
            
            $salesByCreditCard = $invoice->getSalesByCreditCard($info[0]["id"]);   
            $salesChequeSales = $invoice->getSalesByCheque($info[0]["id"]);    
            $salesByDebts = $invoice->getSalesNotPaidByCashboxID($info[0]["id"]);
            
            $suppliers_payments = $suppliers->getTotalSuppliersPaymentsByCashbox($info[0]["id"]);
            
            $total_invoice_discount = $invoice->getTotalInvoiceDiscountByCashboxID($info[0]["id"]);
            $expenses_value = $expenses->getSumOfExpensesByCashboxID($info[0]["id"]);
            
            $customers_payment_debts = $customers->getTotalPaymentBalanceByCashboxID($info[0]["id"]); 
            
            $customers_payment_debts_cash = $customers->getTotalPaymentBalanceByCashboxIDMethod($info[0]["id"],1); 
            $customers_payment_debts_cheque = $customers->getTotalPaymentBalanceByCashboxIDMethod($info[0]["id"],2); 
            $customers_payment_debts_cc = $customers->getTotalPaymentBalanceByCashboxIDMethod($info[0]["id"],3); 
            
            $suppliers_payments_cash = $suppliers->getTotalSuppliersPaymentsByCashboxMethod($info[0]["id"],1);
            $suppliers_payments_cheque = $suppliers->getTotalSuppliersPaymentsByCashboxMethod($info[0]["id"],2);
            $suppliers_payments_cc = $suppliers->getTotalSuppliersPaymentsByCashboxMethod($info[0]["id"],3);
            
            $total_return_and_changes = $cashbox->get_total_changes_and_return_for_another_shift($info[0]["id"]);
            $total_return_and_changes_another_branche = $cashbox->get_total_changes_and_return_for_another_branches($info[0]["id"]);
            
            $total_invoices_current_shift = $cashbox->get_total_invoices_current_shift($info[0]["id"]);
            
            
            $return_info = array();
            $return_info["starting_cashbox_date"] = $info[0]["starting_cashbox_date"];
            
            
            $return_info["customers_payment_debts_cash"] = self::value_format_custom($customers_payment_debts_cash,$this->settings_info_local);
           
            $return_info["customers_payment_debts_cheque"] = self::value_format_custom($customers_payment_debts_cheque,$this->settings_info_local);
            $return_info["customers_payment_debts_cc"] = self::value_format_custom($customers_payment_debts_cc,$this->settings_info_local);
            
            $return_info["total_debts_payment"] = self::value_format_custom($customers_payment_debts,$this->settings_info_local);
            
            $return_info["sales_creditcard"] = self::value_format_custom($salesByCreditCard,$this->settings_info_local);
            $return_info["sales_cheques_line"] = self::value_format_custom($salesChequeSales,$this->settings_info_local);
            $return_info["sales_notpaid_line"] = self::value_format_custom($salesByDebts,$this->settings_info_local);
            $return_info["total_invoice_discount"] = "-".self::value_format_custom(abs($total_invoice_discount),$this->settings_info_local);
            $return_info["total_expenses"] = "-".self::value_format_custom($expenses_value,$this->settings_info_local);
            $return_info["starting_cashbox"] = self::value_format_custom($info[0]["cash"],$this->settings_info_local);
            $return_info["total_cash_sale"] = self::value_format_custom($total_invoices_current_shift,$this->settings_info_local);
            $return_info["total_return"] =  self::value_format_custom(round(abs($total_return_and_changes),$this->settings_info_local["round_val"]),$this->settings_info_local);
            $return_info["total_return_another_branche"] =  self::value_format_custom(round(abs($total_return_and_changes_another_branche),$this->settings_info_local["round_val"]),$this->settings_info_local);
            $return_info["starting_cashbox_lbp"] = self::value_format_custom($info[0]["cashbox_lbp"],$this->settings_info_local);
            $return_info["total_cashback"] =  self::value_format_custom(round(abs($total_cashback),$this->settings_info_local["round_val"]),$this->settings_info_local);

            $return_info["total_supplier_payment"] = self::value_format_custom($suppliers_payments,$this->settings_info_local);
                    
            $return_info["total_supplier_payment_cash"] = self::value_format_custom($suppliers_payments_cash,$this->settings_info_local);
            $return_info["total_supplier_payment_cheque"] = self::value_format_custom($suppliers_payments_cheque,$this->settings_info_local);
            $return_info["total_supplier_payment_cc"] = self::value_format_custom($suppliers_payments_cc,$this->settings_info_local);
            
            echo json_encode($return_info);
        }
    }
    
    public function print_full_report_custom_with_vat_current($cashbox_id){
        self::giveAccessTo(array(2,4));
        
        if($this->settings_info["end_of_day_report"]=="1"){
            if($this->settings_info["daily_report_type"]=="1"){
                self::print_full_report_custom_with_vat_current_1($cashbox_id);
            }else if($this->settings_info["daily_report_type"]=="2"){
                self::print_full_report_custom_with_vat_current_2($cashbox_id);
            }else if($this->settings_info["daily_report_type"]=="3"){
                self::print_full_report_custom_with_vat_current_3($cashbox_id);
            }
        }
        
    }
    
    public function manual_print_report($cashbox_id){
        $data=array();
        
        $vendor_id = $_SESSION['id'];
        
        $cashbox = $this->model("cashbox");
        $cashinout = $this->model("cashinout");
        $expenses = $this->model("expenses");
        
        $customers = $this->model("customers");
        $suppliers = $this->model("suppliers");
        $items = $this->model("items");
        
                
        
        if($cashbox_id==0){
            $info = $cashbox->getTodayCashbox($_SESSION['store_id'],$vendor_id);
        }else{
            $info = $cashbox->geCashboxById($cashbox_id);
        }
        $cashbox->updateCashBox($info[0]["id"]);
        
        
        $cash_detail = $cashbox->get_cash_details($info[0]["id"]);
    
        $cash_detail_by_invoice_id=array();
        for($i=0;$i<count($cash_detail);$i++){
            $cash_detail_by_invoice_id[$cash_detail[$i]["invoice_id"]]=$cash_detail[$i];
        }
       
        
        
        
        $data["start_report_date"]=$info[0]["starting_cashbox_date"];
        $data["operator"]=$_SESSION['username'];
        $data["branche_name"]=$_SESSION['store_name'];
        $data["cashbox_id"]=$info[0]["id"];


        $data["start_cashbox"]=$info[0]["cash"];
        $data["cashbox_lbp"]=$info[0]["cashbox_lbp"];
        
        
        $data["invoices"]=array();
        $data["invoices_debts"]=array();
        
        $invoice = $this->model("invoice");
        $invoices = $invoice->getAllInvoicesByCashboxID($info[0]["id"]);
        $total_invoices=0;
        $total_to_add_to_cashsales = 0;
        
        $index=0;
        
        
        $data["symbol"]=" USD";
        $data["invoices_debt_exist"]=0;
        
        
        $data["total_cash_usd"]=$data["start_cashbox"];
        $data["total_cash_lbp"]=$data["cashbox_lbp"];
        
        $data["total_return_usd"]=0;
        
        
        
        
        
        /*$data["return_items"]=array();
        $returned_items = $invoice->getReturnedItemsByInvoiceId($invoices[$i]["id"],$info[0]["id"]);
        for($k=0;$k<count($returned_items);$k++){
            $data["return_items"][$k] = $returned_items[$k];
        }*/
        
        $data["return_items_cash_details"]=array();
        $returned_items_cash_details = $cashinout->getReturnedItemsCashDetails($info[0]["id"]);
        $data["returned_items_cash_details_array"] = array();
        for($i=0;$i<count($returned_items_cash_details);$i++){
            $data["returned_items_cash_details_array"][$returned_items_cash_details[$i]["invoice_item_return_id"]]=$returned_items_cash_details[$i];
        }
    
        for($i=0;$i<count($invoices);$i++){
            $data["invoices"][$index]["invoice_items"] = $invoice->getItemsOfInvoice_Basic_Details($invoices[$i]["id"]);
            $data["invoices"][$index]["returned"] = $invoice->getReturnedItemsByInvoiceId($invoices[$i]["id"],$info[0]["id"]);
            
            $data["invoices"][$index]["cashin_out"]=$cashinout->get_all_in_out_of_invoice($invoices[$i]["id"]);
            
            
            $data["invoices"][$index]["id"]=self::idFormat_invoice($invoices[$i]["id"]);
            $data["invoices"][$index]["creation_date"]=$invoices[$i]["creation_date"];
            $data["invoices"][$index]["total_value"]=$invoices[$i]["total_value"];
            $data["invoices"][$index]["rate"]=$invoices[$i]["rate"];
            $data["invoices"][$index]["total_return"]=0;
                        
            for($k=0;$k<count($data["invoices"][$index]["returned"]);$k++){
                $data["invoices"][$index]["returned"][$k]["item_info"]=$items->get_item($data["invoices"][$index]["returned"][$k]["item_id"]);
                $data["invoices"][$index]["returned"][$k]["selling_price"] = $data["invoices"][$index]["returned"][$k]["selling_price"]*(1-$data["invoices"][$index]["returned"][$k]["discount"]/100);
                $data["invoices"][$index]["returned"][$k]["selling_price_lbp"]=self::only_round_lbp($data["invoices"][$index]["returned"][$k]["selling_price"]*$data["invoices"][$index]["rate"]);
                $data["total_return_usd"]+=$data["invoices"][$index]["returned"][$k]["selling_price"];
                $data["invoices"][$index]["total_return"]+=$data["invoices"][$index]["returned"][$k]["selling_price"];
                
            }   
            
            if($invoices[$i]["closed"]==0 || $invoices[$i]["auto_closed"]==1){
                $data["invoices"][$index]["is_cash"]=0;
                $data["invoices_debt_exist"]=1;
                $data["invoices"][$index]["method"]="DEBIT";
                $data["invoices"][$index]["cash_lbp"]=0;
                $data["invoices"][$index]["cash_usd"]=0;
            }else{
                
                //$data["invoices"][$index]["total_value"]+=$data["invoices"][$index]["total_return"];
                $data["invoices"][$index]["is_cash"]=1;
                $data["invoices"][$index]["method"]="CASH";
                $data["invoices"][$index]["cash_lbp"]=$cash_detail_by_invoice_id[$invoices[$i]["id"]]["cash_lbp"];
                $data["invoices"][$index]["cash_usd"]=$cash_detail_by_invoice_id[$invoices[$i]["id"]]["cash_usd"];
                
                $data["invoices"][$index]["cash_lbp_out"]=$cash_detail_by_invoice_id[$invoices[$i]["id"]]["returned_cash_lbp"];
                $data["invoices"][$index]["cash_usd_out"]=$cash_detail_by_invoice_id[$invoices[$i]["id"]]["returned_cash_usd"];
                $data["invoices"][$index]["rate"]=$cash_detail_by_invoice_id[$invoices[$i]["id"]]["rate"];
                
                $data["invoices"][$i]["discount"]=$invoices[$i]["invoice_discount"]*-1;
                
                
                $data["invoices"][$index]["alert"]=0;
                $data["invoices"][$index]["alert_v"]=0;
                $data["total_alert"]=0;
                $tousd=(($data["invoices"][$index]["cash_lbp"]/$data["invoices"][$index]["rate"])+$data["invoices"][$index]["cash_usd"]-$data["invoices"][$index]["cash_usd_out"]-$data["invoices"][$index]["cash_lbp_out"]/$data["invoices"][$index]["rate"]);
                if(abs($data["invoices"][$index]["total_value"]-$tousd)<=0.1){
                    $tousd= floatval(round($tousd,2));
                }
               
                if($index==3){
                    //echo $tousd."!=".floatval($data["invoices"][$index]["total_value"]);exit;
                }
                    
                
                if(floatval($tousd)!=round(floatval($data["invoices"][$index]["total_value"]),2) && $tousd>0){
                    $data["invoices"][$index]["alert"]=1;
                    $data["invoices"][$index]["alert_v"]=$tousd;
                    $data["total_alert"]++;
                }
                
                if(count($data["invoices"][$index]["returned"])>0){
                    if($data["invoices"][$index]["cash_lbp"]>0 && $data["invoices"][$index]["cash_usd"]==0){
                        //$data["invoices"][$index]["cash_lbp"]-=self::only_round_lbp($data["invoices"][$index]["total_return"]*$data["invoices"][$index]["rate"]);
                    }
                    if($data["invoices"][$index]["cash_lbp"]==0 && $data["invoices"][$index]["cash_usd"]>0){
                        //$data["invoices"][$index]["cash_usd"]-=$data["invoices"][$index]["total_return"];
                    }
                    if($data["invoices"][$index]["cash_lbp"]>0 && $data["invoices"][$index]["cash_usd"]>0){
                        
                    }
                }
                
                if($data["invoices"][$index]["total_value"]>0){
                    $data["total_cash_lbp"]+=$data["invoices"][$index]["cash_lbp"];
                    $data["total_cash_usd"]+=$data["invoices"][$index]["cash_usd"];
                }
            }
           
            //var_dump($data["invoices"][$index]);exit;
            
            $index++;
            
        }
        
         
        
        
        $data["return_another_shift"]=$invoice->getReturnedItemsByAnotherCashbox_with_details($_SESSION['cashbox_id']);
         for($k=0;$k<count($data["return_another_shift"]);$k++){
             if($data["return_another_shift"][$k]["discount"]>0){
                $data["return_another_shift"][$k]["selling_price"]=(1-$data["return_another_shift"][$k]["discount"]/100)*$data["return_another_shift"][$k]["selling_price"];
             }
         }
        
        
        
        $data["changes_another_shift"]=$invoice->getChangesItemsByAnotherCashbox_with_details($_SESSION['cashbox_id']);
      
        
        $data["expenses"]=$expenses->getExpensesByCashboxID($_SESSION['cashbox_id']);
        
        $data["deleted_invoices"]=$invoice->getDeleted_invoices($_SESSION['cashbox_id']);
        
        $data["customers_payments"]=$customers->get_all_payment_of_customer_by_cashbox($_SESSION['cashbox_id']);
        $data["suppliers_payments"]=$suppliers->get_all_payment_of_suppliers_by_cashbox($_SESSION['cashbox_id']);
        
        $this->view("print_templates/pos8/manual_report",$data);
    }
    
    public function print_full_report_custom_with_vat_current_3($cashbox_id){
        $demo = 1;
        self::giveAccessTo(array(2,4));
        if($this->settings_info["end_of_day_report"]=="1"){
            try {
                if($demo==0){
                    $connector = null;
                    $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                    $printer = new Printer($connector);
                }
                $invoice = $this->model("invoice");
                $items = $this->model("items");
                $cashbox = $this->model("cashbox");
                $user = $this->model("user");
                $expenses = $this->model("expenses");
                $customers = $this->model("customers");
                $settings = $this->model("settings");
                $suppliers= $this->model("suppliers");
                
                $payment_types = $settings->get_all_payment_method();
                $payment_info = array();
                for($i=0;$i<count($payment_types);$i++){
                    $payment_info[$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
                }
                
                $vendor_id = $_SESSION['id'];
                $vendor_d['id'] = $vendor_id;
                $vendor_info = $user->get_user($vendor_d);
                
             
                if($cashbox_id==0){
                    $info = $cashbox->getTodayCashbox($_SESSION['store_id'],$vendor_id);
                }else{
                    $info = $cashbox->geCashboxById($cashbox_id);
                }
     
                $cashbox->updateCashBox($info[0]["id"]);
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Report\n\n");
                }else{
                    echo "<b>Report</b><br/><br/>";
                }
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Vendor Name: ".$_SESSION['username']."\n");
                    $printer -> text("Branche: ".$_SESSION['store_name']."\n\n");
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("CashBox ID: ".$info[0]["id"]."\n");
                    $printer -> text("Start Date: ".$info[0]["starting_cashbox_date"]."\n");
                    $printer -> text("End Date: ".$info[0]["ending_cashbox_date"]."\n\n");
                }else{
                    echo "<b>Vendor Name:</b> ".$vendor_info[0]["name"]."<br/><br/>";
                    echo "<b>CashBox ID:</b> ".$info[0]["id"]."<br/>";
                    echo "<b>Start Date:</b> ".$info[0]["starting_cashbox_date"]."<br/>";
                    echo "<b>End Date:</b> ".$info[0]["ending_cashbox_date"]."<br/><br/>";
                }
                
                if($demo==0){
                    //$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text("Sales Invoices:\n");
                }else{
                    //echo "<b>Sales Invoices:</b><br/>";
                }
                
                $invoices = $invoice->getAllInvoicesByCashboxID($info[0]["id"]);
                $total_invoices=0;
                $total_to_add_to_cashsales = 0;
                for($i=0;$i<count($invoices);$i++){
                    
                    if($demo==0){
                        //$printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                    }
                    if($invoices[$i]["closed"]==0 || $invoices[$i]["auto_closed"]==1){
                        $line1 = sprintf('%-12s %-12s',self::idFormat_invoice($invoices[$i]["id"]),"Payment: Not Paid");
                    }else{
                        $line1 = sprintf('%-12s %-12s',self::idFormat_invoice($invoices[$i]["id"]),"(".$payment_info[$invoices[$i]["payment_method"]].") ".$invoices[$i]["creation_date"]);

                    }
                    
                    if($demo==0){
                        //$printer -> text($line1."\n");
                        //$printer -> selectPrintMode(Printer::MODE_FONT_A);
                    }else{
                        //echo $line1."<br/>";
                    }
                    
                    $invoice_items = $invoice->getItemsOfInvoice_Basic($invoices[$i]["id"]);
                    
                    $total_items_value = 0;
                    for($k=0;$k<count($invoice_items);$k++){
                        
                        $discount_flag = "";
                        if($invoice_items[$k]["discount"]>0){
                            $discount_flag = " D". round($invoice_items[$k]["discount"],1)."%";
                        }
                        
                        if($invoice_items[$k]["vat"]==1){
                            $invoice_items[$k]["final_price_disc_qty"] = round($invoice_items[$k]["final_price_disc_qty"]*$invoice_items[$k]["vat_value"],$this->settings_info_local["round_val"]);
                        }
                        $total_items_value+=$invoice_items[$k]["final_price_disc_qty"];
                        if($invoice_items[$k]["item_id"]!=null){
                            $item_info = $items->get_item($invoice_items[$k]["item_id"]);
                            if(strlen($item_info[0]["description"])>11){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."";
                            }
                            $line = sprintf('%-8s %-9s x %-7s %12s',self::idFormat_item($invoice_items[$k]["item_id"]),$item_info[0]["description"],floor($invoice_items[$k]["qty"]).$discount_flag, self::value_format_custom(round($invoice_items[$k]["final_price_disc_qty"],$this->settings_info_local["round_val"]),$this->settings_info));
                        }else{
                            $line = sprintf('%-8s %-9s x %-7s %12s',"no Id",$invoice_items[$k]["description"],floor($invoice_items[$k]["qty"]).$discount_flag, self::value_format_custom(round($invoice_items[$k]["final_price_disc_qty"],$this->settings_info_local["round_val"]),$this->settings_info));
                        }
                        
                        if($demo==0){
                            //$printer -> text($line);
                            //$printer->feed(1);
                        }else{
                            //echo $line."<br/>";
                        }
                    }
                    
                    $returned_items = $invoice->getReturnedItemsByInvoiceId($invoices[$i]["id"],$info[0]["id"]);
                    
                    for($k=0;$k<count($returned_items);$k++){
                        $returned_items[$k]["selling_price"] = $returned_items[$k]["qty"]*$returned_items[$k]["selling_price"]*(1-$returned_items[$k]["discount"]/100);
                        if($returned_items[$k]["vat"]==1){
                            $returned_items[$k]["selling_price"] = $returned_items[$k]["selling_price"]*$returned_items[$k]["vat_value"];
                        }
                        //$total_items_value+=$returned_items[$k]["selling_price"];
                        $total_to_add_to_cashsales+=$returned_items[$k]["selling_price"];
                        if($returned_items[$k]["item_id"]!=null){
                            $item_info = $items->get_item($returned_items[$k]["item_id"]);
                            if(strlen($item_info[0]["description"])>11){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."..";
                            }
                            $line = sprintf('%-8s %-11s x %2s %15s',self::idFormat_item($returned_items[$k]["item_id"]),$item_info[0]["description"]."(R)",floor($returned_items[$k]["qty"]), self::value_format_custom($returned_items[$k]["selling_price"],$this->settings_info_local));
                        }else{
                            $line = sprintf('%-8s %-11s x %2s %15s',"no Id",$returned_items[$k]["description"]."(R)",floor($invoice_items[$k]["qty"]), self::value_format_custom($returned_items[$k]["selling_price"],$this->settings_info_local));
                        }
                        
                        if($demo==0){
                            //$printer -> text($line."\n");
                        }else{
                            //echo $line."<br/>";
                        }
                    }
                    $line2 = sprintf('%-15s %-15s',"Total: ".self::value_format_custom($total_items_value+$invoices[$i]["invoice_discount"],$this->settings_info),"Disc: ".self::value_format_custom($invoices[$i]["invoice_discount"],$this->settings_info));
                    
                    if($demo==0){
                        //$printer -> text($line2);
                    
                        //$printer->feed(2);
                    }else{
                        //echo $line2."<br/><br/>";
                    }
                    
                    
                    
                    $total_invoices+=$total_items_value+$invoices[$i]["invoice_discount"];
                }
                
                
                
                //if($demo==0)
                    //$printer->feed(1);
                
                
                if($demo==0){
                    //$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text("Total returns and changes:\n");
                }else{
                    //echo "<br/>";
                    //echo "<b>Total returns and changes:</b><br/>";
                }
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                }
                
                $diff_changed = $cashbox->get_total_changes_and_return_for_another_shift($info[0]["id"]);
                if($demo==0){
                    //$printer -> selectPrintMode(Printer::MODE_FONT_A);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text(self::value_format_custom(round($diff_changed,$this->settings_info_local["round_val"]),$this->settings_info_local));
                }else{
                    //echo self::value_format_custom(round($diff_changed,$this->settings_info_local["round_val"]),$this->settings_info_local);
                }
                
                if($demo==0){
                    //$printer->feed(2);
                    //$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text("Not Cash Payment:\n");
                }else{
                    //echo "<br/><br/><b>Not Cash Payment:</b><br/>";
                }
                
                if($demo==0){
                    //$printer->feed(1);
                }
                
                $salesByCreditCard = $invoice->getSalesByCreditCard($info[0]["id"]);
                
                $salesChequeSales = $invoice->getSalesByCheque($info[0]["id"]);
                
                $salesByDebts = $invoice->getSalesNotPaidByCashboxID($info[0]["id"]);
                
                if($demo==0){
                    //$printer -> selectPrintMode(Printer::MODE_FONT_A);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                }
                $sales_creditcard_line = sprintf('%-20s %-20s',"Total Credit Card Sales: ",self::value_format_custom($salesByCreditCard,$this->settings_info_local));
                //if($demo==0)
                    //$printer->text($sales_creditcard_line."\n");
                //else
                    //echo $sales_creditcard_line."<br/>";
                
                
                $sales_cheques_line = sprintf('%-20s %-20s',"Total Cheque Sales: ",self::value_format_custom($salesChequeSales,$this->settings_info_local));
                //if($demo==0)
                    //$printer->text($sales_cheques_line."\n");
                //else
                    //echo $sales_cheques_line."<br/>";
                
                $sales_notpaid_line = sprintf('%-20s %-20s',"Total Sales Not Paid: ",self::value_format_custom($salesByDebts,$this->settings_info_local));
                
                if($demo==0){
                    //$printer->text($sales_notpaid_line."\n\n");
                }else{
                    //echo $sales_notpaid_line."<br/><br/>";
                }
                
                
                $total_invoice_discount = $invoice->getTotalInvoiceDiscountByCashboxID($info[0]["id"]);
                
                if(round(abs($total_invoice_discount),$this->settings_info_local["round_val"])>0){
                    if($demo==0){
                        //$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                        //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                        //$printer -> text("Total Invoice Discounts:\n");
                    }else{
                        //echo "<b>Total Invoice Discounts:</b><br/>";
                    }

                    if($demo==0)
                        $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $cashbox_line = sprintf('%-18s',self::value_format_custom(abs($total_invoice_discount),$this->settings_info_local));
                    
                    
                    if($demo==0){
                        $printer->text($cashbox_line."\n");
                        $printer->feed(1);
                    }else{
                        echo $cashbox_line."<br/><br/><br/>";
                    }
                }
                
                $all_suppliers = $suppliers->getAllSuppliersEvenDeleted();
                $all_suppliers_array = array();
                for($i=0;$i<count($all_suppliers);$i++){
                    $all_suppliers_array[$all_suppliers[$i]["id"]]=$all_suppliers[$i];
                }
                
                $suppliers_payment = $suppliers->getAllSuppliersPaymentsByCashbox($info[0]["id"]);
                $total_suppliers_payment = 0;
                
                
                if(count($suppliers_payment)>0){
                    //$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text("Suppliers Payments Info:\n");
                    //$printer -> selectPrintMode(Printer::MODE_FONT_A);

                    for($i=0;$i<count($suppliers_payment);$i++){

                        $total_suppliers_payment+=$suppliers_payment[$i]["payment_value"]*$suppliers_payment[$i]["currency_rate"];
                        //$sp_line = sprintf('%-20s %15s',$all_suppliers_array[$suppliers_payment[$i]["supplier_id"]]["name"].": ",self::value_format_custom($suppliers_payment[$i]["payment_value"]*$suppliers_payment[$i]["currency_rate"],$this->settings_info_local));
                        //$printer->text($sp_line."\n");
                    }  
                    $printer->feed(2);  
                }
                 
                 
                $total_cashback=$invoice->get_total_cashback($info[0]["id"]);
                if($total_cashback>0){
                    //$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text("Total Cashback:\n");
                    //$printer->text(self::value_format_custom(abs($total_cashback),$this->settings_info_local)."\n");
                    //$printer->feed(2);  
                }
                    
                $expenses_value = $expenses->getSumOfExpensesByCashboxID($info[0]["id"]);
                $customers_payment_debts = $customers->getTotalPaymentBalanceByCashboxID($info[0]["id"]);
                
                if($demo==0){
                    //$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    //$printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("CashBox Info:\n");
                }else{
                    echo "<b>CashBox Info:</b><br/>";
                }
                
                if($demo==0)
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                $cashbox_line = sprintf('%-26s %15s',"Starting Cashbox: ",self::value_format_custom($info[0]["cash"],$this->settings_info_local));
                
                if($demo==0)
                    $printer->text($cashbox_line."\n");
                else
                    echo $cashbox_line."<br/>";
                
                
                //$totalvalue_line = sprintf('%-18s %20s',"Total Cash Sales: ",number_format($info[0]["current_cash_box_value"]+$expenses_value-$customers_payment_debts+$total_sales_and_return_for_same_cashbox,2));
                $totalvalue_line = sprintf('%-26s %15s',"Total Cash Sales: ",self::value_format_custom(round($total_invoices-$salesByDebts-$salesByCreditCard,$this->settings_info_local["round_val"]),$this->settings_info_local));
                
                if($demo==0)
                    $printer->text($totalvalue_line."\n");
                else
                    echo $totalvalue_line."<br/>";

                
                //$diff_changed = $cashbox->get_total_changes($info[0]["id"]);
                $totalchanges= sprintf('%-26s %15s',"Total Returns/Changes: ",self::value_format_custom($diff_changed,$this->settings_info_local));
                if($demo==0)
                    $printer->text($totalchanges."\n");
                else
                    echo $totalchanges."<br/>";
                
                $totalexpenses_line = sprintf('%-26s %15s',"Total Expenses: ","-".self::value_format_custom($expenses_value,$this->settings_info_local));
                
                if($demo==0)
                    $printer->text($totalexpenses_line."\n");
                else {
                    echo $totalexpenses_line."<br/>";
                }
                
                $totaldebts_payment_line = sprintf('%-26s %15s',"Total Debts Payment: ",self::value_format_custom($customers_payment_debts,$this->settings_info_local));

                $sp_line = sprintf('%-20s %15s',"Total Suppliers Payments: ",self::value_format_custom($total_suppliers_payment,$this->settings_info_local));
                
                                
                if($demo==0){
                    $printer->text($sp_line."\n");
                    $printer->text($totaldebts_payment_line."\n");
                }else{
                     echo $sp_line."<br/>";
                    echo $totaldebts_payment_line."<br/>";
                }
                
                $totalcasback_line = sprintf('%-26s %15s',"Total Cashback: ",self::value_format_custom( round($total_cashback),$this->settings_info_local));
                
                if($demo==0){
                     $printer -> text($totalcasback_line."\n");
                }else{
                    echo $totalcasback_line."<br/>";
                }
               
                
                $totalcashbox_line = sprintf('%-26s %15s',"Cash On Close: ",self::value_format_custom( round($total_invoices+$diff_changed-$expenses_value+$customers_payment_debts+$info[0]["cash"]-$salesByDebts-$total_suppliers_payment-$salesByCreditCard-$total_cashback,$this->settings_info_local["round_val"]),$this->settings_info_local));

                
             
                
                if($demo==0){
                    //$printer->text($totalcashbox_line."\n");
                }else {
                    echo $totalcashbox_line."<br/>";
                }
                
                if($demo==0){
                    $printer->feed(2);
                
                    $printer->cut();
                    $printer->close();
                }
                
            } catch (Exception $ex) {

            }
        }
    }
    
    public function print_full_report_custom_with_vat_current_2($cashbox_id){
       
       
        $invoice = $this->model("invoice");
        $items = $this->model("items");
        $cashbox = $this->model("cashbox");
        $user = $this->model("user");
        $expenses = $this->model("expenses");
        $categories = $this->model("categories");
        $settings = $this->model("settings");
        
        $categories_list = $categories->getAllCategories();
        
        $payment_types = $settings->get_all_payment_method();
        $payment_info = array();
        for($i=0;$i<count($payment_types);$i++){
            $payment_info[$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
        }

        $vendor_id = $_SESSION['id'];
        $vendor_d['id'] = $vendor_id;
        $vendor_info = $user->get_user($vendor_d);


        if($cashbox_id==0){
            $info = $cashbox->getTodayCashbox($_SESSION['store_id'],$vendor_id);
        }else{
            $info = $cashbox->geCashboxById($cashbox_id);
        }

        $cashbox->updateCashBox($info[0]["id"]);
        
        try {
            $connector = null;
            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);
            
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Report\n\n");
            
            $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            //$printer -> text("Vendor Name: ".$vendor_info[0]["name"]."\n\n");
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            //$printer -> text("CashBox ID: ".$info[0]["id"]."\n");
            //$printer -> text("Start Date: ".$info[0]["starting_cashbox_date"]."\n");
            //$printer -> text("End Date: ".$info[0]["ending_cashbox_date"]."\n\n");
            
            $total_items_value = 0;
            for($i=0;$i<count($categories_list);$i++){
                $invoices_items = $invoice->getInvoiceByCatAndParenCat($categories_list[$i]["id"],$info[0]["id"]);
                //$printer -> text($categories_list[$i]["id"]."\n");
                if(count($invoices_items)>0){
                    $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                    $printer -> text($categories_list[$i]["description"]."\n");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    for($k=0;$k<count($invoices_items);$k++){
                        $total_items_value+=$invoices_items[$k]["final_price_disc_qty"];
                        $item_info = $items->get_item($invoices_items[$k]["item_id"]);
                        $line = sprintf('%-8s %-16s x %2s %15s',self::idFormat_item($invoices_items[$k]["item_id"]),$item_info[0]["description"],floor($invoices_items[$k]["qty"]), number_format(round($invoices_items[$k]["final_price_disc_qty"],$this->settings_info_local["round_val"]),2)." ".$this->settings_info_local["default_currency_symbol"]);
                        $printer -> text($line."\n");
                    }
                    $printer->feed(1);
                }
            }
            
            $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            
            $cashbox_line = sprintf('%-26s %15s',"Total Sales: ",number_format($total_items_value,2)." ".$this->settings_info_local["default_currency_symbol"]);
            $printer -> text($cashbox_line."\n");
            $printer->feed(1);
                
            $printer->cut();
            $printer->close();
                    
        } catch (Exception $ex) {

        }
        
    }
    
    public function print_full_report_custom_with_vat_current_1($cashbox_id){
        $demo = 0;
        self::giveAccessTo(array(2,4));
        if($this->settings_info["end_of_day_report"]=="1"){
            try {
                if($demo==0){
                    $connector = null;
                    $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                    $printer = new Printer($connector);
                }
                $invoice = $this->model("invoice");
                $items = $this->model("items");
                $cashbox = $this->model("cashbox");
                $user = $this->model("user");
                $expenses = $this->model("expenses");
                $customers = $this->model("customers");
                $settings = $this->model("settings");
                $suppliers= $this->model("suppliers");
                
                $payment_types = $settings->get_all_payment_method();
                $payment_info = array();
                for($i=0;$i<count($payment_types);$i++){
                    $payment_info[$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
                }
                
                $vendor_id = $_SESSION['id'];
                $vendor_d['id'] = $vendor_id;
                $vendor_info = $user->get_user($vendor_d);
                
             
                if($cashbox_id==0){
                    $info = $cashbox->getTodayCashbox($_SESSION['store_id'],$vendor_id);
                }else{
                    $info = $cashbox->geCashboxById($cashbox_id);
                }
     
                $cashbox->updateCashBox($info[0]["id"]);
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Report\n\n");
                }else{
                    echo "<b>Report</b><br/><br/>";
                }
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Vendor Name: ".$_SESSION['username']."\n");
                    $printer -> text("Branche: ".$_SESSION['store_name']."\n\n");
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("CashBox ID: ".$info[0]["id"]."\n");
                    $printer -> text("Start Date: ".$info[0]["starting_cashbox_date"]."\n");
                    $printer -> text("End Date: ".$info[0]["ending_cashbox_date"]."\n\n");
                }else{
                    echo "<b>Vendor Name:</b> ".$vendor_info[0]["name"]."<br/><br/>";
                    echo "<b>CashBox ID:</b> ".$info[0]["id"]."<br/>";
                    echo "<b>Start Date:</b> ".$info[0]["starting_cashbox_date"]."<br/>";
                    echo "<b>End Date:</b> ".$info[0]["ending_cashbox_date"]."<br/><br/>";
                }
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Sales Invoices:\n");
                }else{
                    echo "<b>Sales Invoices:</b><br/>";
                }
                
                $invoices = $invoice->getAllInvoicesByCashboxID($info[0]["id"]);
                $total_invoices=0;
                $total_to_add_to_cashsales = 0;
                for($i=0;$i<count($invoices);$i++){
                    if($demo==0){
                        $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                    }
                    if($invoices[$i]["closed"]==0 || $invoices[$i]["auto_closed"]==1){
                        $line1 = sprintf('%-12s %-12s',self::idFormat_invoice($invoices[$i]["id"]),"Payment: Not Paid");
                    }else{
                        $line1 = sprintf('%-12s %-12s',self::idFormat_invoice($invoices[$i]["id"]),"(".$payment_info[$invoices[$i]["payment_method"]].") ".$invoices[$i]["creation_date"]);

                    }
                    
                    if($demo==0){
                        $printer -> text($line1."\n");
                        $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    }else{
                        echo $line1."<br/>";
                    }
                    
                    $invoice_items = $invoice->getItemsOfInvoice_Basic($invoices[$i]["id"]);
                    
                    $total_items_value = 0;
                    for($k=0;$k<count($invoice_items);$k++){
                        
                        $discount_flag = "";
                        if($invoice_items[$k]["discount"]>0){
                            $discount_flag = " D". round($invoice_items[$k]["discount"],1)."%";
                        }
                        
                        if($invoice_items[$k]["vat"]==1){
                            $invoice_items[$k]["final_price_disc_qty"] = round($invoice_items[$k]["final_price_disc_qty"]*$invoice_items[$k]["vat_value"],$this->settings_info_local["round_val"]);
                        }
                        $total_items_value+=$invoice_items[$k]["final_price_disc_qty"];
                        if($invoice_items[$k]["item_id"]!=null){
                            $item_info = $items->get_item($invoice_items[$k]["item_id"]);
                            if(strlen($item_info[0]["description"])>11){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."";
                            }
                            $line = sprintf('%-8s %-9s x %-7s %12s',self::idFormat_item($invoice_items[$k]["item_id"]),$item_info[0]["description"],floor($invoice_items[$k]["qty"]).$discount_flag, self::value_format_custom(round($invoice_items[$k]["final_price_disc_qty"],$this->settings_info_local["round_val"]),$this->settings_info));
                        }else{
                            $line = sprintf('%-8s %-9s x %-7s %12s',"no Id",$invoice_items[$k]["description"],floor($invoice_items[$k]["qty"]).$discount_flag, self::value_format_custom(round($invoice_items[$k]["final_price_disc_qty"],$this->settings_info_local["round_val"]),$this->settings_info));
                        }
                        
                        if($demo==0){
                            $printer -> text($line);
                            $printer->feed(1);
                        }else{
                            echo $line."<br/>";
                        }
                    }
                    
                    $returned_items = $invoice->getReturnedItemsByInvoiceId($invoices[$i]["id"],$info[0]["id"]);
                    
                    for($k=0;$k<count($returned_items);$k++){
                        $returned_items[$k]["selling_price"] = $returned_items[$k]["qty"]*$returned_items[$k]["selling_price"]*(1-$returned_items[$k]["discount"]/100);
                        if($returned_items[$k]["vat"]==1){
                            $returned_items[$k]["selling_price"] = $returned_items[$k]["selling_price"]*$returned_items[$k]["vat_value"];
                        }
                        //$total_items_value+=$returned_items[$k]["selling_price"];
                        $total_to_add_to_cashsales+=$returned_items[$k]["selling_price"];
                        if($returned_items[$k]["item_id"]!=null){
                            $item_info = $items->get_item($returned_items[$k]["item_id"]);
                            if(strlen($item_info[0]["description"])>11){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."..";
                            }
                            $line = sprintf('%-8s %-11s x %2s %15s',self::idFormat_item($returned_items[$k]["item_id"]),$item_info[0]["description"]."(R)",floor($returned_items[$k]["qty"]), self::value_format_custom($returned_items[$k]["selling_price"],$this->settings_info_local));
                        }else{
                            $line = sprintf('%-8s %-11s x %2s %15s',"no Id",$returned_items[$k]["description"]."(R)",floor($invoice_items[$k]["qty"]), self::value_format_custom($returned_items[$k]["selling_price"],$this->settings_info_local));
                        }
                        
                        if($demo==0){
                            $printer -> text($line."\n");
                        }else{
                            echo $line."<br/>";
                        }
                    }
                    $line2 = sprintf('%-15s %-15s',"Total: ".self::value_format_custom($total_items_value+$invoices[$i]["invoice_discount"],$this->settings_info),"Disc: ".self::value_format_custom($invoices[$i]["invoice_discount"],$this->settings_info));
                    
                    if($demo==0){
                        $printer -> text($line2);
                    
                        $printer->feed(2);
                    }else{
                        echo $line2."<br/><br/>";
                    }
                    
                    
                    
                    $total_invoices+=$total_items_value+$invoices[$i]["invoice_discount"];
                }
                
                
                
                //if($demo==0)
                    //$printer->feed(1);
                
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Total returns and changes:\n");
                }else{
                    //echo "<br/>";
                    echo "<b>Total returns and changes:</b><br/>";
                }
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                }
                
                $diff_changed = $cashbox->get_total_changes_and_return_for_another_shift($info[0]["id"]);
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text(self::value_format_custom(round($diff_changed,$this->settings_info_local["round_val"]),$this->settings_info_local));
                }else{
                    echo self::value_format_custom(round($diff_changed,$this->settings_info_local["round_val"]),$this->settings_info_local);
                }
                
                if($demo==0){
                    $printer->feed(2);
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Not Cash Payment:\n");
                }else{
                    echo "<br/><br/><b>Not Cash Payment:</b><br/>";
                }
                
                if($demo==0){
                    //$printer->feed(1);
                }
                
                $salesByCreditCard = $invoice->getSalesByCreditCard($info[0]["id"]);
                
                $salesChequeSales = $invoice->getSalesByCheque($info[0]["id"]);
                
                $salesByDebts = $invoice->getSalesNotPaidByCashboxID($info[0]["id"]);
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                }
                $sales_creditcard_line = sprintf('%-20s %-20s',"Total Credit Card Sales: ",self::value_format_custom($salesByCreditCard,$this->settings_info_local));
                if($demo==0)
                    $printer->text($sales_creditcard_line."\n");
                else
                    echo $sales_creditcard_line."<br/>";
                
                
                $sales_cheques_line = sprintf('%-20s %-20s',"Total Cheque Sales: ",self::value_format_custom($salesChequeSales,$this->settings_info_local));
                if($demo==0)
                    $printer->text($sales_cheques_line."\n");
                else
                    echo $sales_cheques_line."<br/>";
                
                $sales_notpaid_line = sprintf('%-20s %-20s',"Total Sales Not Paid: ",self::value_format_custom($salesByDebts,$this->settings_info_local));
                
                if($demo==0)
                    $printer->text($sales_notpaid_line."\n\n");
                else
                    echo $sales_notpaid_line."<br/><br/>";
                
                
                
                $total_invoice_discount = $invoice->getTotalInvoiceDiscountByCashboxID($info[0]["id"]);
                
                if(round(abs($total_invoice_discount),$this->settings_info_local["round_val"])>0){
                    if($demo==0){
                        $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text("Total Invoice Discounts:\n");
                    }else{
                        echo "<b>Total Invoice Discounts:</b><br/>";
                    }

                    if($demo==0)
                        $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $cashbox_line = sprintf('%-18s',self::value_format_custom(abs($total_invoice_discount),$this->settings_info_local));
                    
                    
                    if($demo==0){
                        $printer->text($cashbox_line."\n");
                        $printer->feed(1);
                    }else{
                        echo $cashbox_line."<br/><br/><br/>";
                    }
                }
                
                $all_suppliers = $suppliers->getAllSuppliersEvenDeleted();
                $all_suppliers_array = array();
                for($i=0;$i<count($all_suppliers);$i++){
                    $all_suppliers_array[$all_suppliers[$i]["id"]]=$all_suppliers[$i];
                }
                
                $suppliers_payment = $suppliers->getAllSuppliersPaymentsByCashbox($info[0]["id"]);
                $total_suppliers_payment = 0;
                
                
                if(count($suppliers_payment)>0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Suppliers Payments Info:\n");
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);

                    for($i=0;$i<count($suppliers_payment);$i++){

                        $total_suppliers_payment+=$suppliers_payment[$i]["payment_value"]*$suppliers_payment[$i]["currency_rate"];
                        $sp_line = sprintf('%-20s %15s',$all_suppliers_array[$suppliers_payment[$i]["supplier_id"]]["name"].": ",self::value_format_custom($suppliers_payment[$i]["payment_value"]*$suppliers_payment[$i]["currency_rate"],$this->settings_info_local));
                        $printer->text($sp_line."\n");
                    }  
                    $printer->feed(2);  
                }
                 
                 
                $total_cashback=$invoice->get_total_cashback($info[0]["id"]);
                if($total_cashback>0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Total Cashback:\n");
                    $printer->text(self::value_format_custom(abs($total_cashback),$this->settings_info_local)."\n");
                    $printer->feed(2);  
                }
                    
                $expenses_value = $expenses->getSumOfExpensesByCashboxID($info[0]["id"]);
                $customers_payment_debts = $customers->getTotalPaymentBalanceByCashboxID($info[0]["id"]);
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("CashBox Info:\n");
                }else{
                    echo "<b>CashBox Info:</b><br/>";
                }
                
                if($demo==0)
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                $cashbox_line = sprintf('%-26s %15s',"Starting Cashbox: ",self::value_format_custom($info[0]["cash"],$this->settings_info_local));
                
                if($demo==0)
                    $printer->text($cashbox_line."\n");
                else
                    echo $cashbox_line."<br/>";
                
                
                //$totalvalue_line = sprintf('%-18s %20s',"Total Cash Sales: ",number_format($info[0]["current_cash_box_value"]+$expenses_value-$customers_payment_debts+$total_sales_and_return_for_same_cashbox,2));
                $totalvalue_line = sprintf('%-26s %15s',"Total Cash Sales: ",self::value_format_custom(round($total_invoices-$salesByDebts-$salesByCreditCard,$this->settings_info_local["round_val"]),$this->settings_info_local));
                
                if($demo==0)
                    $printer->text($totalvalue_line."\n");
                else
                    echo $totalvalue_line."<br/>";

                
                //$diff_changed = $cashbox->get_total_changes($info[0]["id"]);
                $totalchanges= sprintf('%-26s %15s',"Total Returns/Changes: ",self::value_format_custom($diff_changed,$this->settings_info_local));
                if($demo==0)
                    $printer->text($totalchanges."\n");
                else
                    echo $totalchanges."<br/>";
                
                $totalexpenses_line = sprintf('%-26s %15s',"Total Expenses: ","-".self::value_format_custom($expenses_value,$this->settings_info_local));
                
                if($demo==0)
                    $printer->text($totalexpenses_line."\n");
                else {
                    echo $totalexpenses_line."<br/>";
                }
                
                $totaldebts_payment_line = sprintf('%-26s %15s',"Total Debts Payment: ",self::value_format_custom($customers_payment_debts,$this->settings_info_local));

                $sp_line = sprintf('%-20s %15s',"Total Suppliers Payments: ",self::value_format_custom($total_suppliers_payment,$this->settings_info_local));
                $printer->text($sp_line."\n");
                                
                if($demo==0)
                    $printer->text($totaldebts_payment_line."\n");
                else
                    echo $totaldebts_payment_line."<br/>";
                
                
                $totalcasback_line = sprintf('%-26s %15s',"Total Cashback: ",self::value_format_custom( round($total_cashback),$this->settings_info_local));
                $printer -> text($totalcasback_line."\n");
                
                $totalcashbox_line = sprintf('%-26s %15s',"Cash On Close: ",self::value_format_custom( round($total_invoices+$diff_changed-$expenses_value+$customers_payment_debts+$info[0]["cash"]-$salesByDebts-$total_suppliers_payment-$salesByCreditCard-$total_cashback,$this->settings_info_local["round_val"]),$this->settings_info_local));

                

                
                if($demo==0)
                    $printer->text($totalcashbox_line."\n");
                else 
                    echo $totalcashbox_line."<br/>";
                
                if($demo==0){
                    $printer->feed(2);
                
                    $printer->cut();
                    $printer->close();
                }
                
            } catch (Exception $ex) {

            }
        }
    }
    
    public function print_full_report_custom_with_vat($cashbox_id){
        $demo = 1;
        self::giveAccessTo(array(2,4));
        if($this->settings_info["end_of_day_report"]=="1"){
            try {
               
                if($demo==0){
                    $connector = null;
                    $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                    $printer = new Printer($connector);
                }
                
                $invoice = $this->model("invoice");
                $items = $this->model("items");
                $cashbox = $this->model("cashbox");
                $user = $this->model("user");
                $expenses = $this->model("expenses");
                $customers = $this->model("customers");
                $settings = $this->model("settings");
                
                $payment_types = $settings->get_all_payment_method();
                $payment_info = array();
                for($i=0;$i<count($payment_types);$i++){
                    $payment_info[$payment_types[$i]["id"]] = $payment_types[$i]["method_name"];
                }
                
                $vendor_id = $_SESSION['id'];
                $vendor_d['id'] = $vendor_id;
                $vendor_info = $user->get_user($vendor_d);
                
             
                if($cashbox_id==0){
                    $info = $cashbox->getTodayCashbox($_SESSION['store_id'],$vendor_id);
                }else{
                    $info = $cashbox->geCashboxById($cashbox_id);
                }
     
                $cashbox->updateCashBox($info[0]["id"]);
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Report\n\n");
                }else{
                    echo "<b>Report</b><br/><br/>";
                }
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("Vendor Name: ".$vendor_info[0]["name"]."\n\n");
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("CashBox ID: ".$info[0]["id"]."\n");
                    $printer -> text("Start Date: ".$info[0]["starting_cashbox_date"]."\n");
                    $printer -> text("End Date: ".$info[0]["ending_cashbox_date"]."\n\n");
                }else{
                    echo "<b>Vendor Name:</b> ".$vendor_info[0]["name"]."<br/><br/>";
                    echo "<b>CashBox ID:</b> ".$info[0]["id"]."<br/>";
                    echo "<b>Start Date:</b> ".$info[0]["starting_cashbox_date"]."<br/>";
                    echo "<b>End Date:</b> ".$info[0]["ending_cashbox_date"]."<br/><br/>";
                }
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Sales Invoices:\n");
                }else{
                    echo "<b>Sales Invoices:</b><br/>";
                }
                
                $invoices = $invoice->getAllInvoicesByCashboxID($info[0]["id"]);
                $total_sales_and_return_for_same_cashbox = 0;
                $total_sales_with_invoice_discount = 0;
                $cash_sales_per_invoice = 0;
                $total_sales_added_by_another_cashbox = 0;
                for($i=0;$i<count($invoices);$i++){
                    $cash_sales_per_invoice = 0;
                    if($demo==0){
                        $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                    }
                    if($invoices[$i]["closed"]==0 || $invoices[$i]["auto_closed"]==1){
                        $line1 = sprintf('%-12s %-12s',self::idFormat_invoice($invoices[$i]["id"]),"Payment: Not Paid");
                    }else{
                        $line1 = sprintf('%-12s %-12s',self::idFormat_invoice($invoices[$i]["id"]),"(".$payment_info[$invoices[$i]["payment_method"]].") ".$invoices[$i]["creation_date"]);

                    }
                    
                    if($demo==0){
                        $printer -> text($line1."\n");
                        $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    }else{
                        echo $line1."<br/>";
                    }
                    
                    $invoice_items = $invoice->getItemsOfInvoice_Basic($invoices[$i]["id"]);
                    
                    if($invoices[$i]["payment_method"]==1 && $invoices[$i]["closed"]==1 && $invoices[$i]["auto_closed"]==0){
                        $total_sales_with_invoice_discount+=$invoices[$i]["invoice_discount"];
                        
                    }
                    $cash_sales_per_invoice+=$invoices[$i]["invoice_discount"];
                    
                    for($k=0;$k<count($invoice_items);$k++){
                        if($invoice_items[$k]["vat"]==1){
                            $invoice_items[$k]["final_price_disc_qty"] = round($invoice_items[$k]["final_price_disc_qty"]*$invoice_items[$k]["vat_value"],$this->settings_info_local["round_val"]);
                        }
                        if($invoice_items[$k]["item_id"]!=null){
                            $item_info = $items->get_item($invoice_items[$k]["item_id"]);
                            if(strlen($item_info[0]["description"])>11){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."..";
                            }
                            $line = sprintf('%-8s %-11s x %2s %15s',self::idFormat_item($invoice_items[$k]["item_id"]),$item_info[0]["description"],floor($invoice_items[$k]["qty"]), number_format(round($invoice_items[$k]["final_price_disc_qty"],$this->settings_info_local["round_val"]),2)." ".$this->settings_info_local["default_currency_symbol"]);
                        }else{
                            $line = sprintf('%-8s %-11s x %2s %15s',"no Id",$invoice_items[$k]["description"],floor($invoice_items[$k]["qty"]), number_format(round($invoice_items[$k]["final_price_disc_qty"],$this->settings_info_local["round_val"]),2)." ".$this->settings_info_local["default_currency_symbol"]);
                        }
                        
                        if($invoices[$i]["payment_method"]==1 && $invoices[$i]["closed"]==1 && $invoices[$i]["auto_closed"]==0){
                            $total_sales_with_invoice_discount+=$invoice_items[$k]["final_price_disc_qty"];
                        }
                         $cash_sales_per_invoice+=$invoice_items[$k]["final_price_disc_qty"];

                        
                        if($demo==0){
                            $printer -> text($line);
                            $printer->feed(1);
                        }else{
                            echo $line."<br/>";
                        }
                        
                        if($invoices[$i]["cashbox_id"] != $invoice_items[$k]["item_change_cashbox"] && $invoice_items[$k]["item_change_cashbox"]!=0){
                            if($invoices[$i]["payment_method"]==1)
                                $total_sales_added_by_another_cashbox+=$invoice_items[$k]["final_price_disc_qty"];
                        }
                    }
                    
                    $returned_items = $invoice->getReturnedItemsByInvoiceId($invoices[$i]["id"],$info[0]["id"]);
                    
                    for($k=0;$k<count($returned_items);$k++){
                        $returned_items[$k]["selling_price"] = $returned_items[$k]["qty"]*$returned_items[$k]["selling_price"]*(1-$returned_items[$k]["discount"]/100);
                        if($returned_items[$k]["vat"]==1){
                            $returned_items[$k]["selling_price"] = $returned_items[$k]["selling_price"]*$returned_items[$k]["vat_value"];
                        }
                        
                        if($returned_items[$k]["item_id"]!=null){
                            $item_info = $items->get_item($returned_items[$k]["item_id"]);
                            if(strlen($item_info[0]["description"])>11){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."..";
                            }
                            $line = sprintf('%-8s %-11s x %2s %15s',self::idFormat_item($returned_items[$k]["item_id"]),$item_info[0]["description"]."(R)",floor($returned_items[$k]["qty"]), number_format($returned_items[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                        }else{
                            $line = sprintf('%-8s %-11s x %2s %15s',"no Id",$returned_items[$k]["description"]."(R)",floor($invoice_items[$k]["qty"]), number_format($returned_items[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                        }
                        
                        if( ($returned_items[$k]["old_cashbox_id"] == $returned_items[$k]["cashbox_id"]) && $invoices[$i]["payment_method"]==1 && $invoices[$i]["closed"]==1 && $invoices[$i]["auto_closed"]==0)
                            $total_sales_and_return_for_same_cashbox+=($returned_items[$k]["selling_price"]);
                        
                         //echo "***".$returned_items[$k]["selling_price"]."***";
                        //echo "**".$returned_items[$k]["qty"]*$returned_items[$k]["selling_price"]."**<br/>";
                        
                        if($invoices[$i]["payment_method"]==1 && $invoices[$i]["closed"]==1 && $invoices[$i]["auto_closed"]==0 ){
                            $total_sales_with_invoice_discount+=($returned_items[$k]["selling_price"]);  
                        }
                        //$cash_sales_per_invoice-=($returned_items[$k]["selling_price"]);
                        
                        if($demo==0){
                            $printer -> text($line."\n");
                        }else{
                            echo $line."<br/>";
                        }
                    }
                    $line2 = sprintf('%-15s %-15s',"Total: ".number_format(round($cash_sales_per_invoice,$this->settings_info_local["round_val"]),$this->settings_info_local["round_val"])." ".$this->settings_info_local["default_currency_symbol"],"Disc: ".number_format($invoices[$i]["invoice_discount"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                    
                    if($demo==0){
                        $printer -> text($line2);
                    
                        $printer->feed(2);
                    }else{
                        echo $line2."<br/><br/>";
                    }
                }
                
                if($demo==0)
                    $printer->feed(2);
                
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Returned Item:\n");
                }else{
                    //echo "<br/>";
                    echo "<b>Returned Item:</b><br/>";
                }
                
                if($demo==0)
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                
                for($i=0;$i<count($invoices);$i++){
                    $returned_items = $invoice->getReturnedItemsByInvoiceIdAndByCashbox($invoices[$i]["id"],$info[0]["id"]);
                    for($k=0;$k<count($returned_items);$k++){
                        
                        $returned_items[$k]["selling_price"] = $returned_items[$k]["qty"]*$returned_items[$k]["selling_price"]*(1-$returned_items[$k]["discount"]/100);
                        if($returned_items[$k]["vat"]==1){
                            $returned_items[$k]["selling_price"] = $returned_items[$k]["selling_price"]*$returned_items[$k]["vat_value"];
                        }
                        
                        if($returned_items[$k]["item_id"]!=null){
                            $item_info = $items->get_item($returned_items[$k]["item_id"]);
                            if(strlen($item_info[0]["description"])>11){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."..";
                            }
                            $line = sprintf('%-8s %-11s x %2s %15s',self::idFormat_item($returned_items[$k]["item_id"]),$item_info[0]["description"]." ",floor($returned_items[$k]["qty"]), number_format($returned_items[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                        }else{
                            $line = sprintf('%-8s %-11s x %2s %15s',"no Id",$returned_items[$k]["description"]."(R)",floor($invoice_items[$k]["qty"]), number_format($returned_items[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                        }
                        if($demo==0){
                            $printer -> text($line."\n");
                        }else{
                            echo $line."<br/>";
                        }
                    }
                }
                
                $total_return_for_anpther_cashbox = 0;
                
                if($demo==0)
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                $returned_items_by_another_cashbox = $invoice->getReturnedItemsByAnotherCashbox($info[0]["id"]);
                for($k=0;$k<count($returned_items_by_another_cashbox);$k++){
                    $returned_items_by_another_cashbox[$k]["selling_price"] = $returned_items_by_another_cashbox[$k]["qty"]*$returned_items_by_another_cashbox[$k]["selling_price"]*(1-$returned_items_by_another_cashbox[$k]["discount"]/100);
                    if($returned_items_by_another_cashbox[$k]["vat"]==1){
                        $returned_items_by_another_cashbox[$k]["selling_price"] = $returned_items_by_another_cashbox[$k]["selling_price"]*$returned_items_by_another_cashbox[$k]["vat_value"];
                    }
                        
                    if($returned_items_by_another_cashbox[$k]["item_id"]!=null){
                        $item_info = $items->get_item($returned_items_by_another_cashbox[$k]["item_id"]);
                        if(strlen($item_info[0]["description"])>11){
                            $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."..";
                        }
                        $line = sprintf('%-8s %-11s x %2s %15s',self::idFormat_item($returned_items_by_another_cashbox[$k]["item_id"]),$item_info[0]["description"]." ",floor($returned_items_by_another_cashbox[$k]["qty"]), number_format($returned_items_by_another_cashbox[$k]["qty"]*$returned_items_by_another_cashbox[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                    }else{
                        $line = sprintf('%-8s %-11s x %2s %15s',"no Id",$returned_items_by_another_cashbox[$k]["description"]." ",floor($invoice_items[$k]["qty"]), number_format($returned_items_by_another_cashbox[$k]["qty"]*$returned_items_by_another_cashbox[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                    }
                    
                    $total_return_for_anpther_cashbox+=$returned_items_by_another_cashbox[$k]["selling_price"];
                    
                    //echo "***".$total_return_for_anpther_cashbox."***";
                    
                    if($demo==0){
                        $printer -> text($line."\n");
                    }else {
                        echo $line."<br/>";
                    }
                }
                
                
                if($demo==0){
                    $printer->feed(1);
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Items added for different Cashbox:\n");
                }else{
                    echo "<br/>";
                    echo "<b>Items added for different Cashbox:</b><br/>";
                }
                
                $added_items_to_invoice_for_diffenrent_cashbox = $invoice->get_added_items_to_invoice_for_diffenrent_cashbox($info[0]["id"]);
                $total_added_to_another_cashbox = 0;
                for($k=0;$k<count($added_items_to_invoice_for_diffenrent_cashbox);$k++){
                    $added_items_to_invoice_for_diffenrent_cashbox[$k]["selling_price"] = $added_items_to_invoice_for_diffenrent_cashbox[$k]["qty"]*$added_items_to_invoice_for_diffenrent_cashbox[$k]["selling_price"]*(1-$added_items_to_invoice_for_diffenrent_cashbox[$k]["discount"]/100);
                    if($added_items_to_invoice_for_diffenrent_cashbox[$k]["vat"]==1){
                        $added_items_to_invoice_for_diffenrent_cashbox[$k]["selling_price"] = $added_items_to_invoice_for_diffenrent_cashbox[$k]["selling_price"]*$added_items_to_invoice_for_diffenrent_cashbox[$k]["vat_value"];
                    }
                    
                    $total_added_to_another_cashbox+=$added_items_to_invoice_for_diffenrent_cashbox[$k]["selling_price"];

                    if($added_items_to_invoice_for_diffenrent_cashbox[$k]["item_id"]!=null){
                        $item_info = $items->get_item($added_items_to_invoice_for_diffenrent_cashbox[$k]["item_id"]);
                        if(strlen($item_info[0]["description"])>11){
                            $item_info[0]["description"] = substr($item_info[0]["description"], 0, 11)."..";
                        }
                        $line = sprintf('%-8s %-11s x %2s %15s',self::idFormat_item($added_items_to_invoice_for_diffenrent_cashbox[$k]["item_id"]),$item_info[0]["description"]." ",floor($added_items_to_invoice_for_diffenrent_cashbox[$k]["qty"]), number_format($added_items_to_invoice_for_diffenrent_cashbox[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                    }else{
                        $line = sprintf('%-8s %-11s x %2s %15s',"no Id",$added_items_to_invoice_for_diffenrent_cashbox[$k]["description"]."(R)",floor($invoice_items[$k]["qty"]), number_format($added_items_to_invoice_for_diffenrent_cashbox[$k]["selling_price"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                    }
                    if($demo==0){
                        $printer -> text($line."\n");
                    }else{
                        echo $line."<br/>";
                    }
                }
                
                
                if($demo==0){
                    $printer->feed(2);
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Not Cash Payment:\n");
                }else{
                    echo "<br/><b>Not Cash Payment:</b><br/>";
                }
                
                $salesByCreditCard = $invoice->getSalesByCreditCard($info[0]["id"]);
                
                $salesChequeSales = $invoice->getSalesByCheque($info[0]["id"]);
                
                $salesByDebts = $invoice->getSalesNotPaidByCashboxID($info[0]["id"]);
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                }
                $sales_creditcard_line = sprintf('%-20s %-20s',"Total Credit Card Sales: ",number_format($salesByCreditCard,2)." ".$this->settings_info_local["default_currency_symbol"]);
                if($demo==0)
                    $printer->text($sales_creditcard_line."\n");
                else
                    echo $sales_creditcard_line."<br/>";
                
                
                $sales_cheques_line = sprintf('%-20s %-20s',"Total Cheque Sales: ",number_format($salesChequeSales,2)." ".$this->settings_info_local["default_currency_symbol"]);
                if($demo==0)
                    $printer->text($sales_cheques_line."\n");
                else
                    echo $sales_cheques_line."<br/>";
                
                $sales_notpaid_line = sprintf('%-20s %-20s',"Total Sales Not Paid: ",number_format($salesByDebts,2)." ".$this->settings_info_local["default_currency_symbol"]);
                
                if($demo==0)
                    $printer->text($sales_notpaid_line."\n\n\n");
                else
                    echo $sales_notpaid_line."<br/><br/><br/>";
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("Total Invoice Discounts:\n");
                }else{
                    echo "<b>Total Invoice Discounts:</b><br/>";
                }
                
                $total_invoice_discount = $invoice->getTotalInvoiceDiscountByCashboxID($info[0]["id"]);
                
                if($demo==0)
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                $cashbox_line = sprintf('%-18s',number_format(abs($total_invoice_discount),2)." ".$this->settings_info_local["default_currency_symbol"]);
                
                if($demo==0){
                    $printer->text($cashbox_line."\n");
                    $printer->feed(2);
                }else{
                    echo $cashbox_line."<br/><br/><br/>";
                }
                
                $expenses_value = $expenses->getSumOfExpensesByCashboxID($info[0]["id"]);
                $customers_payment_debts = $customers->getTotalPaymentBalanceByCashboxID($info[0]["id"]);
                
                if($demo==0){
                    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text("CashBox Info:\n");
                }else{
                    echo "<b>CashBox Info:</b><br/>";
                }
                
                if($demo==0)
                    $printer -> selectPrintMode(Printer::MODE_FONT_A);
                $cashbox_line = sprintf('%-26s %15s',"Starting Cashbox: ",number_format($info[0]["cash"],2)." ".$this->settings_info_local["default_currency_symbol"]);
                
                if($demo==0)
                    $printer->text($cashbox_line."\n");
                else
                    echo $cashbox_line."<br/>";
                
                //$totalvalue_line = sprintf('%-18s %20s',"Total Cash Sales: ",number_format($info[0]["current_cash_box_value"]+$expenses_value-$customers_payment_debts+$total_sales_and_return_for_same_cashbox,2)." ".$this->settings_info_local["default_currency_symbol"]);
                $totalvalue_line = sprintf('%-26s %15s',"Total Cash Sales: ",number_format(round($total_sales_with_invoice_discount-$total_sales_added_by_another_cashbox,0),$this->settings_info_local["round_val"])." ".$this->settings_info_local["default_currency_symbol"]);
                
                
                if($demo==0)
                    $printer->text($totalvalue_line."\n");
                else
                    echo $totalvalue_line."<br/>";

                
                $diff_changed = $cashbox->get_total_changes($info[0]["id"]);
                $totalchanges= sprintf('%-26s %15s',"Total Changes: ",number_format($diff_changed,2)." ".$this->settings_info_local["default_currency_symbol"]);
                if($demo==0)
                    $printer->text($totalchanges."\n");
                else
                    echo $totalchanges."<br/>";
                
                $totalexpenses_line = sprintf('%-26s %15s',"Total Expenses: ","-".number_format($expenses_value,2)." ".$this->settings_info_local["default_currency_symbol"]);
                
                if($demo==0)
                    $printer->text($totalexpenses_line."\n");
                else {
                    echo $totalexpenses_line."<br/>";
                }
                
                $totaldebts_payment_line = sprintf('%-26s %15s',"Debts Payment: ",number_format($customers_payment_debts,2)." ".$this->settings_info_local["default_currency_symbol"]);
                
                if($demo==0)
                    $printer->text($totaldebts_payment_line."\n");
                else
                    echo $totaldebts_payment_line."<br/>";
                
                
                if($cashbox_id==0){
                    $totalcashbox_line = sprintf('%-26s %15s',"Cash On Close: ",number_format( ($info[0]["cash"]+round($info[0]["current_cash_box_value"]+$diff_changed,$this->settings_info_local["round_val"])),2  )." ".$this->settings_info_local["default_currency_symbol"]);
                }else{
                    $totalcashbox_line = sprintf('%-26s %15s',"Cash On Close: ",number_format( (round($info[0]["cash_on_close"],$this->settings_info_local["round_val"])),2  )." ".$this->settings_info_local["default_currency_symbol"]);
                }
                
                if($demo==0)
                    $printer->text($totalcashbox_line."\n");
                else 
                    echo $totalcashbox_line."<br/>";
                
                if($demo==0){
                    $printer->feed(2);
                
                $printer->cut();
                $printer->close();
                }
                
            } catch (Exception $e) {

            }
        }
    }
    
    public function print_full_report($cashbox_id){
        self::giveAccessTo(array(2,4));
        if($this->settings_info["end_of_day_report"]=="1"){
            
            try {
                $connector = null;
                $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                $printer = new Printer($connector);

                $vendor_id = $_SESSION['id'];
                
                
                $invoice = $this->model("invoice");
                $items = $this->model("items");
                $cashbox = $this->model("cashbox");
                $user = $this->model("user");
                
                $vendor_d['id'] = $vendor_id;
                $vendor_info = $user->get_user($vendor_d);

                if($cashbox_id==0){
                    $info = $cashbox->getTodayCashbox($_SESSION['store_id'],$vendor_id);
                }else{
                    $info = $cashbox->geCashboxById($cashbox_id);
                }
                
                $invoices_items = $invoice->getAllInvoicesItemsByCashboxID($info[0]["id"]);     
                
                
                if($cashbox_id==0){
                    $returned_items = $invoice->getReturnedItemsByCashbox($info[0]["id"]);
                }else{
                    $returned_items = $invoice->getReturnedItemsByCashbox_old($info[0]["id"]);
                }
                

                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("Report\n\n");
                
                $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("Vendor Name: ".$vendor_info[0]["name"]."\n\n");
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> text("From: ".$info[0]["starting_cashbox_date"]."\n");
                $printer -> text("To: ".$info[0]["ending_cashbox_date"]."\n\n");
                
                $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> text("Sales:\n");
                
                $printer -> selectPrintMode(Printer::MODE_FONT_A);
               
                $total_val = 0;
                for($i=0;$i<count($invoices_items);$i++){
                    $item_info = $items->get_item($invoices_items[$i]["item_id"]);
                    
                    if(strlen($item_info[0]["description"])>18){
                        $item_info[0]["description"] = substr($item_info[0]["description"], 0, 18)."..";
                    }
                    
                    $line = sprintf('%-6s %-18s x %2s %15s',sprintf('%06d', $invoices_items[$i]["item_id"]),$item_info[0]["description"],floor($invoices_items[$i]["qty"]), number_format($invoices_items[$i]["final_price_disc_qty"])." ".$this->settings_info_local["default_currency_symbol"]);
                    $printer -> text($line);
                    $printer->feed(1);
                    
                    $total_val+=$invoices_items[$i]["final_price_disc_qty"];
                }
                
                if($cashbox_id>0 ){
                    for($i=0;$i<count($returned_items);$i++){
                        $item_info = $items->get_item($returned_items[$i]["item_id"]);
                        if(strlen($item_info[0]["description"])>18){
                            $item_info[0]["description"] = substr($item_info[0]["description"], 0, 18)."..";
                        }
                        $total_val+=$returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100);

                        $line = sprintf('%-6s %-18s x %2s %15s',sprintf('%06d', $returned_items[$i]["item_id"]),$item_info[0]["description"],floor($returned_items[$i]["qty"]), number_format($returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100))." ".$this->settings_info_local["default_currency_symbol"]);
                        $printer -> text($line);
                        $printer->feed(1);
                    }
                }
                
                if($cashbox_id==0 ){
                    for($i=0;$i<count($returned_items);$i++){
                        if($returned_items[$i]["cashbox_id"] == $returned_items[$i]["old_cashbox_id"]){
                            $item_info = $items->get_item($returned_items[$i]["item_id"]);
                            if(strlen($item_info[0]["description"])>18){
                                $item_info[0]["description"] = substr($item_info[0]["description"], 0, 18)."..";
                            }
                            $total_val+=$returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100);

                            $line = sprintf('%-6s %-18s x %2s %15s',sprintf('%06d', $returned_items[$i]["item_id"]),$item_info[0]["description"],floor($returned_items[$i]["qty"]), number_format($returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100))." ".$this->settings_info_local["default_currency_symbol"]);
                            $printer -> text($line);
                            $printer->feed(1);
                        }
                    }
                }
              
                
                
                $printer->feed(2);
                $printer -> selectPrintMode(Printer::MODE_UNDERLINE);
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> text("Returned Items:\n");
                $printer -> selectPrintMode(Printer::MODE_FONT_A);
                
                $total_return_money = 0;
                for($i=0;$i<count($returned_items);$i++){
                    if($cashbox_id>0 && $returned_items[$i]["cashbox_id"]==$cashbox_id){
                        $item_info = $items->get_item($returned_items[$i]["item_id"]);
                        if(strlen($item_info[0]["description"])>18){
                            $item_info[0]["description"] = substr($item_info[0]["description"], 0, 18)."..";
                        }
                        $total_return_money+=$returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100);
                        $line = sprintf('%-6s %-18s x %2s %15s',sprintf('%06d', $returned_items[$i]["item_id"]),$item_info[0]["description"],floor($returned_items[$i]["qty"]), "-".number_format($returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100))." ".$this->settings_info_local["default_currency_symbol"]);
                        $printer -> text($line);
                        $printer->feed(1);
                    }
                    if($cashbox_id==0){
                        $item_info = $items->get_item($returned_items[$i]["item_id"]);
                        if(strlen($item_info[0]["description"])>18){
                            $item_info[0]["description"] = substr($item_info[0]["description"], 0, 18)."..";
                        }
                        $total_return_money+=$returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100);
                        $line = sprintf('%-6s %-18s x %2s %15s',sprintf('%06d', $returned_items[$i]["item_id"]),$item_info[0]["description"],floor($returned_items[$i]["qty"]), "-".number_format($returned_items[$i]["selling_price"]*(1-$returned_items[$i]["discount"]/100))." ".$this->settings_info_local["default_currency_symbol"]);
                        $printer -> text($line);
                        $printer->feed(1);
                    }
                }
                $printer->feed(2);
                

                $cashbox_line = sprintf('%-20s %-20s',"Starting Cashbox: ",number_format($info[0]["cash"])." ".$this->settings_info_local["default_currency_symbol"]);
                $printer->text($cashbox_line."\n");
                $totalvalue_line = sprintf('%-20s %-20s',"Total Sales: ",number_format($total_val)." ".$this->settings_info_local["default_currency_symbol"]);
                $printer->text($totalvalue_line."\n");
                $totalreturn_line = sprintf('%-20s %-20s',"Total Return: ","-".number_format($total_return_money)." ".$this->settings_info_local["default_currency_symbol"]);
                $printer->text($totalreturn_line."\n\n");

                $totalreturn_line = sprintf('%-20s %-20s',"Total CashBox: ",number_format( ($info[0]["cash"]+$total_val-$total_return_money)  )." ".$this->settings_info_local["default_currency_symbol"]);
                $printer->text($totalreturn_line."\n");              
                
                $printer->cut();
                $printer->close();
            
            } catch (Exception $e) {
                
            }
        }
        
        
        
        //print_r($invoices);
        
    }
    
    public function init_customer_display(){
        exec('mode '.$this->settings_info["customer_display_name"].': baud=9600 data=8 stop=1 parity=n xon=on');
    }
    
    public function clear_customer_display(){
        $this->checkAuth();
        try {
            $connector = new FilePrintConnector($this->settings_info["customer_display_name"]);
            // Profile and display
            $profile = CapabilityProfile::load("OCD-300");
            $display = new AuresCustomerDisplay($connector, $profile);

            $display -> clear();

            $display -> text($this->settings_info["shop_name"]."\nWelcome");

            // Dont forget to close the device
            $display -> close();
        }catch (Exception $e) {
            //echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
    
    public function delete_to_customer_display($item_id,$_total){
        $this->checkAuth();
        try {
            $connector = new FilePrintConnector($this->settings_info["customer_display_name"]);
            // Profile and display
            $profile = CapabilityProfile::load("OCD-300");
            $display = new AuresCustomerDisplay($connector, $profile);


            $display -> clear();
            $items_info = $this->model("items");
            $getItems = $items_info->get_item($item_id);
            $description = strlen($getItems[0]["description"]) > 10 ? substr($getItems[0]["description"],0,10) : $getItems[0]["description"];

           // $total = sprintf('%-10s%10s\n%-10s',"Total",$_total,"Hello");

            $display -> text("Del ".$description."\n"."Total: ".round($_total, $this->settings_info_local["round_val"]));

            // Dont forget to close the device
            $display -> close();
        }catch (Exception $e) {
            //echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
    
    public function submit_to_customer_display($item_id,$qty,$price,$_total){
        $this->checkAuth();
        try {

            $connector = new FilePrintConnector($this->settings_info["customer_display_name"]);
            // Profile and display
            $profile = CapabilityProfile::load("OCD-300");
            $display = new AuresCustomerDisplay($connector, $profile);


            $display -> clear();
            $items_info = $this->model("items");
            $getItems = $items_info->get_item($item_id);
            $description = strlen($getItems[0]["description"]) > 7 ? substr($getItems[0]["description"],0,7) : $getItems[0]["description"];
            $info_item = sprintf('%-7s %-2s %6s',$description,"x".$qty,$price*$qty);

           // $total = sprintf('%-10s%10s\n%-10s',"Total",$_total,"Hello");

            $display -> text($info_item."\n"."Total: ".round($_total,$this->settings_info_local["round_val"]));

            // Dont forget to close the device
            $display -> close();
        }catch (Exception $e) {
            //echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
    
    public function open_cashDrawer(){
        try {
            // Enter the share name for your USB printer here
            $connector = null;
            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);

            $printer = new Printer($connector);
            
            /* Pulse */
            $printer -> pulse();
            
            /* Close printer */
            $printer->close();
            
        }catch (Exception $e) {
            //echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
        
    }
    
    public function arabic_print($line){
        self::giveAccessTo(array(2,4));
        mb_internal_encoding("UTF-8");
        $maxChars = 50;
        $textUtf8 = $line;
        $Arabic = new I18N_Arabic('Glyphs');
        $textLtr = $Arabic -> utf8Glyphs($textUtf8, $maxChars);
        $textLine = explode("\n", $textLtr);
        
        $fontPath = "fonts/ae_AlHor.ttf";

        $buffer = new ImagePrintBuffer();
        $buffer -> setFont($fontPath);
        $buffer -> setFontSize(18);

        try {
   
            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);
            $printer -> setPrintBuffer($buffer);
            //$printer -> text("Hello\n");
            foreach($textLine as $text) {
                $printer -> text($text . "\n");
            }

            $printer->feed(2);
            
            $printer->close();
        } catch (Exception $ex) {

        }
        
       
    }
    
    public function print_invoice_id($id_,$gift=0){
        
        self::giveAccessTo(array(2,4));
        
        for($k=0;$k<$this->settings_info["printer_receipt_copies"];$k++){
            
            if($this->settings_info_local["invoice_receipt_format"]=="1"){
             
                self::print_invoice_id_style_1($id_,$gift);
            }
            if($this->settings_info_local["invoice_receipt_format"]=="2"){
              
                self::print_invoice_id_full_details($id_,$gift);
            }

            if($this->settings_info_local["invoice_receipt_format"]=="3"){
           
                self::print_invoice_id_style_3($id_,$gift);
            }

            if($this->settings_info_local["invoice_receipt_format"]=="4"){
           
                self::print_invoice_id_style_4($id_,$gift);
            }

            if($this->settings_info_local["invoice_receipt_format"]=="5"){
                self::print_invoice_id_style_5($id_,$gift);
            }
			
           if($this->settings_info_local["invoice_receipt_format"]=="6"){
                self::print_invoice_id_style_6($id_,$gift);
            }
			
			
        }
        
    }
    
    public function print_invoice_id_style_5($id_,$gift=0){
	
        self::giveAccessTo(array(2,4));
        $this->settings_info["show_currency_in_report"] = $this->settings_info["show_currency_on_receipt"];
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            $employees = $this->model("employees");
            
            $customers = $this->model("customers");
            
            $subcategories = $this->model("categories");
            $info_subcategories = $subcategories->getAllCategories();
            $info_subcategories_label = array();
            for ($i = 0; $i < count($info_subcategories); $i++) {
                $info_subcategories_label[$info_subcategories[$i]["id"]] = $info_subcategories[$i];
            }

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            if($this->settings_info["print_invoice_lbp"]=="1"){
                $this->settings_info["default_currency_symbol"]="LBP";
                $info[0]["total_value"] = $info[0]["total_value"]*$info[0]["rate"];
                $info[0]["invoice_discount"] = $info[0]["invoice_discount"]*$info[0]["rate"];
                for($i=0;$i<count($items);$i++){ 
                    $items[$i]["selling_price"]=$items[$i]["selling_price"]*$info[0]["rate"];
                    $items[$i]["final_price_disc_qty"]=$items[$i]["final_price_disc_qty"]*$info[0]["rate"];
                    $items[$i]["price_after_manual_discount"]=$items[$i]["price_after_manual_discount"]*$info[0]["rate"];
                    $items[$i]["final_cost_vat_qty"]=$items[$i]["final_cost_vat_qty"]*$info[0]["rate"];
                }
            }
            
            
             $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION['store_id']);
            
            
            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);
            
            $printer__ = new Printer($connector);
            
            /* prepare arabic */
            mb_internal_encoding("UTF-8");
            $Arabic = new I18N_Arabic('Glyphs');
            $buffer = new ImagePrintBuffer();
            $printer__ -> setPrintBuffer($buffer);
            
            $buffer -> setFontSize(36);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            if($this->settings_info["hide_shop_name_on_invoice"]==0){
                $printer__ -> text($Arabic -> utf8Glyphs($this->settings_info["shop_name"], 200));
                $printer__ -> feed(1);
            }
            
            $buffer -> setFontSize(26);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> text("Branch ".$Arabic -> utf8Glyphs("الفرع ", 40).": ".$Arabic -> utf8Glyphs($this->settings_info["address"], 40)); 
           
            $printer__->text("Employee ".$Arabic -> utf8Glyphs("رقم الموظف ", 40).": ".$_SESSION['username']."");
            
            
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> text("Invoice ".$Arabic -> utf8Glyphs("رقم الفاتورة", 40).": ".self::idFormat_invoice($info[0]["id"]));
            
            $printer__ -> text("Date ".$Arabic -> utf8Glyphs("التاريخ", 40).": ".$info[0]["creation_date"]);
            $printer__ -> feed(1);
            
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> feed(1);
            $buffer -> setFontSize(26);
            $printer__ -> text($Arabic -> utf8Glyphs("الصنف", 40)."                          ".$Arabic -> utf8Glyphs("الكمية", 40)."      ".$Arabic -> utf8Glyphs("السعر", 40)."         ".$Arabic -> utf8Glyphs("المبلغ", 40));
     
            
            $line = sprintf('%-20s %4s %8s %8s',"Description", "QTY","Price","Total");
            $printer -> text($line);
            $printer->feed(1);
            $printer -> text("-----------------------------------------------");
            $printer->feed(1);
            
            for($i=0;$i<count($items);$i++){ 
                $description_item = "";
                $another_description_item = "";
                $barcode = "";
                $item_id = "";
                $barcode = "";
                if($items[$i]["item_id"] === NULL){
                    $description_item = $items[$i]["description"];
                    $another_description_item = "";
                    $item_id = "";
                    $barcode = "";
                }else{
                    $getItems = $items_info->get_item($items[$i]["item_id"]);
                    
                    $description_item = $getItems[0]["description"];//." ".$info_subcategories_label[$getItems[0]["item_category"]]["description"];
                    
                    $barcode = $getItems[0]["barcode"];
                    $another_description_item = $getItems[0]["another_description"];
                    if(strlen($getItems[0]["item_alias"])>0){
                        $description_item = $getItems[0]["item_alias"];
                    }
                    if($this->settings_info["show_barcode_receipt"]=="0"){
                        $item_id = self::idFormat_item($getItems[0]["id"]);
                    } 
                }
                
                if($this->settings_info["show_barcode_receipt"]=="1"){
                    $barcode = $getItems[0]["barcode"]." ";
                }
                
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                //$description = strlen($description_item) > 40 ? substr($description_item,0,40) : $description_item;
                $description = $description_item;
                
                
                $total_price+= floatval($items[$i]["final_price_disc_qty"]);

                $unit_price = self::value_format_custom($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),$this->settings_info);
                
                $total = self::value_format_custom($items[$i]["final_price_disc_qty"],$this->settings_info);

                if($gift){
                    $unit_price = "---";
                    $total = "---";
                }
                
                $show_discount = "";
                if($items[$i]["discount"]>0){
                    $show_discount = "(Dis: ".number_format($items[$i]["discount"],1)."%)";
                }

                //$printer -> text($barcode);
                //$printer->feed(1);
                
                $line = sprintf('%-20s %4d %8s %8s',"".$barcode,(float)$items[$i]["qty"],$unit_price,$total);//(float)$items[$i]["qty"]
                $printer -> text($line);
                $printer->feed(1);
                $printer__-> text($Arabic -> utf8Glyphs($description, 40));
            
                if($this->settings_info["item_another_description_lang"]=="1"){
                    //$printer__-> text($Arabic -> utf8Glyphs($another_description_item, 100));
                    $printer__-> text($another_description_item);
                    $printer__->feed(1);
                }
                
                
                $total_items+=$items[$i]["qty"]; 
            }
            
            $printer -> text("-----------------------------------------------");
            $printer->feed(2);
            
            
            $buffer -> setFontSize(30);
            $gross_total_item = sprintf('%-14s %17s',"Total Items ".$Arabic -> utf8Glyphs("العدد ", 40),$total_items);
            $printer__->text($gross_total_item."");
            if($gift==0){
                $gross = sprintf('%-14s %17s',"Gross total ".$Arabic -> utf8Glyphs("المجموع ", 40),self::value_format_custom($total_price,$this->settings_info));
                $printer__->text($gross."");
                if(abs($discount_value)>0){
                    $disc = sprintf('%-14s %25s','Discount '.$Arabic -> utf8Glyphs("الحسم ", 40),$discount_percentage."%");
                    $printer__->text($disc."");

                    $tot = sprintf('%-14s %17s',"Total ".$Arabic -> utf8Glyphs("المجموع النهائي ", 40),self::value_format_custom($total_price+$discount_value,$this->settings_info));
                    $printer__->text($tot."");
                }  
            }
            
            $printer->feed(1);
			
            if($info[0]['customer_id']>0){
                $customers_info = $customers->getCustomersById($info[0]['customer_id']);
				$printer__-> text($Arabic -> utf8Glyphs("\nCustomer name: ".$customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"]."\n", 200));
                //$printer->text("\nCustomer name: ".$customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"]."\n");
            }
            
            $printer->feed(1);
            $buffer -> setFontSize(28);
            $out_footer = $Arabic -> utf8Glyphs($this->settings_info["invoice_footer"], 40);
            $printer__->text($out_footer);
            
            
            $printer -> cut();
            $printer->close();

        }
    }
    
	
	public function print_invoice_id_style_6($id_,$gift=0){

        self::giveAccessTo(array(2,4));
        $this->settings_info["show_currency_in_report"] = $this->settings_info["show_currency_on_receipt"];
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            $employees = $this->model("employees");
            
            $customers = $this->model("customers");
            
            $subcategories = $this->model("categories");
            $info_subcategories = $subcategories->getAllCategories();
            $info_subcategories_label = array();
            for ($i = 0; $i < count($info_subcategories); $i++) {
                $info_subcategories_label[$info_subcategories[$i]["id"]] = $info_subcategories[$i];
            }

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            if($this->settings_info["print_invoice_lbp"]=="1"){
                $this->settings_info["default_currency_symbol"]="LBP";
                $info[0]["total_value"] = $info[0]["total_value"]*$info[0]["rate"];
                $info[0]["invoice_discount"] = $info[0]["invoice_discount"]*$info[0]["rate"];
                for($i=0;$i<count($items);$i++){ 
                    $items[$i]["selling_price"]=$items[$i]["selling_price"]*$info[0]["rate"];
                    $items[$i]["final_price_disc_qty"]=$items[$i]["final_price_disc_qty"]*$info[0]["rate"];
                    $items[$i]["price_after_manual_discount"]=$items[$i]["price_after_manual_discount"]*$info[0]["rate"];
                    $items[$i]["final_cost_vat_qty"]=$items[$i]["final_cost_vat_qty"]*$info[0]["rate"];
                }
            }
            
            
             $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION['store_id']);
            
            
            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);
            
            $printer__ = new Printer($connector);
            
            /* prepare arabic */
            mb_internal_encoding("UTF-8");
            $Arabic = new I18N_Arabic('Glyphs');
            $buffer = new ImagePrintBuffer();
            $printer__ -> setPrintBuffer($buffer);
            
            $buffer -> setFontSize(36);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            if($this->settings_info["hide_shop_name_on_invoice"]==0){
                $printer__ -> text($Arabic -> utf8Glyphs($this->settings_info["shop_name"], 200));
                $printer__ -> feed(1);
            }
            
            $buffer -> setFontSize(26);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> text("Branch ".$Arabic -> utf8Glyphs("الفرع ", 40).": ".$Arabic -> utf8Glyphs($this->settings_info["address"], 40)); 
           
            $printer__->text("Employee ".$Arabic -> utf8Glyphs("رقم الموظف ", 40).": ".$_SESSION['username']."");
            
           
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> text("Invoice ".$Arabic -> utf8Glyphs("رقم الفاتورة", 40).": ".self::idFormat_invoice($info[0]["id"]));
            
            $printer__ -> text("Date ".$Arabic -> utf8Glyphs("التاريخ", 40).": ".$info[0]["creation_date"]);
            $printer__ -> feed(1);
            
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> feed(1);
            $buffer -> setFontSize(26);
            $printer__ -> text($Arabic -> utf8Glyphs("الصنف", 40)."                 ".$Arabic -> utf8Glyphs("الكمية", 40)."      ".$Arabic -> utf8Glyphs("السعر", 40)."      ".$Arabic -> utf8Glyphs("حسم", 40)."        ".$Arabic -> utf8Glyphs("المبلغ", 40));
     
            
            $line = sprintf('%-13s %4s %8s %8s %8s',"Description", "QTY","Price","Discount","Total");
            $printer -> text($line);
            $printer->feed(1);
            $printer -> text("-----------------------------------------------");
            $printer->feed(1);
            
            for($i=0;$i<count($items);$i++){ 
                $description_item = "";
                $another_description_item = "";
                $barcode = "";
                $item_id = "";
                $barcode = "";
                if($items[$i]["item_id"] === NULL){
                    $description_item = $items[$i]["description"];
                    $another_description_item = "";
                    $item_id = "";
                    $barcode = "";
                }else{
                    $getItems = $items_info->get_item($items[$i]["item_id"]);
                    
                    $description_item = $getItems[0]["description"];//." ".$info_subcategories_label[$getItems[0]["item_category"]]["description"];
                    
                    $barcode = $getItems[0]["barcode"];
                    $another_description_item = $getItems[0]["another_description"];
                    if(strlen($getItems[0]["item_alias"])>0){
                        $description_item = $getItems[0]["item_alias"];
                    }
                    if($this->settings_info["show_barcode_receipt"]=="0"){
                        $item_id = self::idFormat_item($getItems[0]["id"]);
                    } 
                }
                
                if($this->settings_info["show_barcode_receipt"]=="1"){
                    $barcode = $getItems[0]["barcode"]." ";
                }
                
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                //$description = strlen($description_item) > 40 ? substr($description_item,0,40) : $description_item;
                $description = $description_item;
                
                
                $total_price+= floatval($items[$i]["final_price_disc_qty"]);

                $unit_price = number_format($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),0);
                
                $total = number_format($items[$i]["final_price_disc_qty"],0);

                if($gift){
                    $unit_price = "---";
                    $total = "---";
                }
                
                $show_discount = "";
                if($items[$i]["discount"]>0){
                    $show_discount = "(Dis: ".number_format($items[$i]["discount"],1)."%)";
                }

                //$printer -> text($barcode);
                //$printer->feed(1);
				$disc = floor($items[$i]["selling_price"]*($items[$i]["discount"]));

                $line = sprintf('%-13s %4d %8s %8s %8s',"".$barcode,(float)$items[$i]["qty"],$items[$i]["selling_price"],$disc,$total);//(float)$items[$i]["qty"]
                $printer -> text($line);
                $printer->feed(1);
                $printer__-> text($Arabic -> utf8Glyphs($description, 40));
            
                if($this->settings_info["item_another_description_lang"]=="1"){
                    //$printer__-> text($Arabic -> utf8Glyphs($another_description_item, 100));
                    $printer__-> text($another_description_item);
                    $printer__->feed(1);
                }
                
                
                $total_items+=$items[$i]["qty"]; 
            }
            
            $printer -> text("-----------------------------------------------");
            $printer->feed(2);
            
            
            $buffer -> setFontSize(30);
            $gross_total_item = sprintf('%-14s %17s',"Total Items ".$Arabic -> utf8Glyphs("العدد ", 40),$total_items);
            $printer__->text($gross_total_item."");
            if($gift==0){
                $gross = sprintf('%-14s %17s',"Gross total ".$Arabic -> utf8Glyphs("المجموع ", 40),self::value_format_custom($total_price,$this->settings_info));
                $printer__->text($gross."");
                if(abs($discount_value)>0){
                    $disc = sprintf('%-14s %25s','Discount '.$Arabic -> utf8Glyphs("الحسم ", 40),$discount_percentage."%");
                    $printer__->text($disc."");

                    $tot = sprintf('%-14s %17s',"Total ".$Arabic -> utf8Glyphs("المجموع النهائي ", 40),self::value_format_custom($total_price+$discount_value,$this->settings_info));
                    $printer__->text($tot."");
                }  
            }
            
            $printer->feed(1);
			
            if($info[0]['customer_id']>0){
                $customers_info = $customers->getCustomersById($info[0]['customer_id']);
				$printer__-> text($Arabic -> utf8Glyphs("\nCustomer name: ".$customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"]."\n", 200));
                //$printer->text("\nCustomer name: ".$customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"]."\n");
            }
            
            $printer->feed(1);
            $buffer -> setFontSize(28);
            $out_footer = $Arabic -> utf8Glyphs($this->settings_info["invoice_footer"], 40);
            $printer__->text($out_footer);
            
            
            $printer -> cut();
            $printer->close();

        }
    }
	
    public function print_invoice_id_style_4($id_,$gift=0){
        self::giveAccessTo(array(2,4));
        $this->settings_info["show_currency_in_report"] = $this->settings_info["show_currency_on_receipt"];
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            $employees = $this->model("employees");
            
            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            if($this->settings_info["print_invoice_lbp"]=="1"){
                $info[0]["total_value"] = $info[0]["total_value"]*$info[0]["rate"];
                $info[0]["invoice_discount"] = $info[0]["invoice_discount"]*$info[0]["rate"];
                $this->settings_info["default_currency_symbol"]="LBP";
                for($i=0;$i<count($items);$i++){ 
                    $items[$i]["selling_price"]=$items[$i]["selling_price"]*$info[0]["rate"];
                    $items[$i]["final_price_disc_qty"]=$items[$i]["final_price_disc_qty"]*$info[0]["rate"];
                    $items[$i]["price_after_manual_discount"]=$items[$i]["price_after_manual_discount"]*$info[0]["rate"];
                    $items[$i]["final_cost_vat_qty"]=$items[$i]["final_cost_vat_qty"]*$info[0]["rate"];
                }
            }
            
            
             $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION['store_id']);
            
            
            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);
            
            $printer__ = new Printer($connector);
            
            /* prepare arabic */
            mb_internal_encoding("UTF-8");
            
            $Arabic = new I18N_Arabic('Glyphs');
            $buffer = new ImagePrintBuffer();
           
            $printer__ -> setPrintBuffer($buffer);
            
            $buffer -> setFontSize(36);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            if($this->settings_info["hide_shop_name_on_invoice"]==0){
                $printer__ -> text($Arabic -> utf8Glyphs($this->settings_info["shop_name"], 80));
                $printer__ -> feed(1);
            }
            
            $buffer -> setFontSize(26);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> text("Branch ".$Arabic -> utf8Glyphs("الفرع ", 40).": ".$Arabic -> utf8Glyphs($this->settings_info["address"], 40)); 
           
            $printer__->text("Employee ".$Arabic -> utf8Glyphs("رقم الموظف ", 40).": ".$_SESSION['id']."");
            
            
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> text("Invoice ".$Arabic -> utf8Glyphs("رقم الفاتورة", 40).": ".self::idFormat_invoice($info[0]["id"]));
            
            $printer__ -> text("Date ".$Arabic -> utf8Glyphs("التاريخ", 40).": ".$info[0]["creation_date"]);
            $printer__ -> feed(1);
            
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer__ -> feed(1);
            $buffer -> setFontSize(26);
            $printer__ -> text($Arabic -> utf8Glyphs("الصنف", 40)."                                ".$Arabic -> utf8Glyphs("الكمية", 40)."      ".$Arabic -> utf8Glyphs("السعر", 40)."         ".$Arabic -> utf8Glyphs("المبلغ", 40));
     
            
            $line = sprintf('%-22s %3s %8s %8s',"Description", "QTY","Price","Total");
            $printer -> text($line);
            $printer->feed(1);
            $printer -> text("-----------------------------------------------");
            $printer->feed(1);
            
            for($i=0;$i<count($items);$i++){ 
                $description_item = "";
                $another_description_item = "";
                $barcode = "";
                $item_id = "";
                $barcode = "";
                if($items[$i]["item_id"] === NULL){
                    $description_item = $items[$i]["description"];
                    $another_description_item = "";
                    $item_id = "";
                    $barcode = "";
                }else{
                    $getItems = $items_info->get_item($items[$i]["item_id"]);
                    $description_item = $getItems[0]["description"];
                    $barcode = $getItems[0]["barcode"];
                    $another_description_item = $getItems[0]["another_description"];
                    if(strlen($getItems[0]["item_alias"])>0){
                        $description_item = $getItems[0]["item_alias"];
                    }
                    if($this->settings_info["show_barcode_receipt"]=="0"){
                        $item_id = self::idFormat_item($getItems[0]["id"]);
                    }
                    
                }
                
                if($this->settings_info["show_barcode_receipt"]=="1"){
                    $barcode = $getItems[0]["barcode"]." ";
                }
                
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                //$description = strlen($description_item) > 40 ? substr($description_item,0,40) : $description_item;
                $description = $description_item;
                
                
                $total_price+= floatval($items[$i]["final_price_disc_qty"]);

                $unit_price = self::value_format_custom($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),$this->settings_info);
                
                $total = self::value_format_custom($items[$i]["final_price_disc_qty"],$this->settings_info);

                if($gift){
                    $unit_price = "---";
                    $total = "---";
                }
                
                $show_discount = "";
                if($items[$i]["discount"]>0){
                    $show_discount = "(Dis: ".number_format($items[$i]["discount"],1)."%)";
                }

                //$printer -> text($barcode);
                //$printer->feed(1);
                
                $line = sprintf('%-22s %3d %8s %8s',$barcode,(float)$items[$i]["qty"],$unit_price,$total);//(float)$items[$i]["qty"]
                $printer -> text($line);
                $printer->feed(1);
                $printer__-> text($Arabic -> utf8Glyphs($description, 40));
            
                if($this->settings_info["item_another_description_lang"]=="1"){
                    //$printer__-> text($Arabic -> utf8Glyphs($another_description_item, 100));
                    $printer__-> text($Arabic -> utf8Glyphs($another_description_item, 40));
                    $printer__->feed(1);
                }
                
                
                $total_items+=$items[$i]["qty"]; 
            }
            
            $printer -> text("-----------------------------------------------");
            $printer->feed(2);
            
            
            $buffer -> setFontSize(30);
            $gross_total_item = sprintf('%-14s %17s',"Total Items ".$Arabic -> utf8Glyphs("العدد ", 40),$total_items);
            $printer__->text($gross_total_item."");
            if($gift==0){
                $gross = sprintf('%-14s %17s',"Gross total ".$Arabic -> utf8Glyphs("المجموع ", 40),self::value_format_custom($total_price,$this->settings_info));
                $printer__->text($gross."");
                if(abs($discount_value)>0){
                    $disc = sprintf('%-14s %25s','Discount '.$Arabic -> utf8Glyphs("الحسم ", 40),$discount_percentage."%");
                    $printer__->text($disc."");

                    $tot = sprintf('%-14s %17s',"Total ".$Arabic -> utf8Glyphs("المجموع النهائي ", 40),self::value_format_custom($total_price+$discount_value,$this->settings_info));
                    $printer__->text($tot."");
                }  
            }
			
			$printer->feed(2);
			if($info[0]['customer_id']>0){
				$customers = $this->model("customers");
				$customers_info = $customers->getCustomersById($info[0]['customer_id']);
				
				$printer__ -> setPrintBuffer($buffer);
				$printer__->text("\nCustomer: ".$Arabic -> utf8Glyphs($customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"], 80)."\n");
				
				
				
				$printer__ -> setPrintBuffer($buffer);
				$printer__->text("\nAddress: ".$Arabic -> utf8Glyphs($customers_info[0]["address"], 200)."\n");
			}
            
            $printer->feed(1);
            $buffer -> setFontSize(28);
            $out_footer = $Arabic -> utf8Glyphs($this->settings_info["invoice_footer"], 40);
            $printer__->text($out_footer);
            
            
            $printer -> cut();
            $printer->close();

        }
    }
    
    public function print_invoice_id_style_4__($id_,$gift=0){
        self::giveAccessTo(array(2,4));
        $this->settings_info["show_currency_in_report"] = $this->settings_info["show_currency_on_receipt"];
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            $employees = $this->model("employees");

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            $discount_percentage = number_format(abs((100*$info[0]["invoice_discount"])/$info[0]["total_value"]),2);
            $discount_value = $info[0]["invoice_discount"];

            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);

            $main_root = self::get_main_root();
            if(is_file($main_root."/resources/".$this->settings_info["logo_to_print_name"])){
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $main_root = self::get_main_root();
                $tux = EscposImage::load($main_root."/resources/".$this->settings_info["logo_to_print_name"], false);
                $printer -> bitImage($tux);
            }
            $printer -> feed(2);


            /* Name of shop */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            
            /* prepare arabic */
            mb_internal_encoding("UTF-8");
            $Arabic = new I18N_Arabic('Glyphs');
            $buffer = new ImagePrintBuffer();
            $printer -> setPrintBuffer($buffer);
            
            //echo $this->settings_info["shop_name"];exit;
            $buffer -> setFontSize(50);
            if($this->settings_info["hide_shop_name_on_invoice"]==0){
                $printer -> text($Arabic -> utf8Glyphs($this->settings_info["shop_name"], 200));
                $printer -> feed(1);
            }
            $buffer -> setFontSize(40);
            if(strlen($this->settings_info["address"])>0){
                $printer -> text($Arabic -> utf8Glyphs($this->settings_info["address"], 40));
                $printer -> feed(1);
            }

            if(strlen($this->settings_info["phone_nb"])>0){
                $printer -> text($this->settings_info["phone_nb"]);
                $printer -> feed(1);
            }

            $printer -> selectPrintMode();
            
            

            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION['store_id']);
            
            
            
            
            /* header info */
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $buffer -> setFontSize(34);
            $printer -> feed(1);
            $inv_receipt = "Sales Invoice ".$Arabic -> utf8Glyphs("الفاتورة", 40);
            $printer -> text($inv_receipt);
            $printer -> text("ID: ".self::idFormat_invoice($info[0]["id"]));
            $printer -> text("Branch ".$Arabic -> utf8Glyphs("الفرع ", 40).": ".$store_info[0]["name"]);
            $printer -> text("Date ".$Arabic -> utf8Glyphs("التاريخ", 40).": ".$info[0]["creation_date"]);
            $printer -> feed(1);

            $total_price = 0;
            $getItems = null;
            $total_items = 0;
            $printer -> text("____________________________________________");
            $printer->feed(2);
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $buffer -> setFontSize(26);
            $line = sprintf('%40s', $Arabic -> utf8Glyphs("المبلغ"."            "."السعر"."         "."الكمية"."                           "."الصنف", 100));
            //$printer->text($line);
            $line = sprintf('%-40s %-10s %-10s %-10s', "Description","QTY","Price","Total");
            //$printer->text($line);
            $printer->feed(1);
             
            /*
            $buffer -> setFontSize(26);
            for($i=0;$i<count($items);$i++){ 
                $description_item = "";
                $item_id = "";
                $barcode = "";
                if($items[$i]["item_id"] === NULL){
                    $description_item = $items[$i]["description"];
                    $item_id = "";
                }else{
                    $getItems = $items_info->get_item($items[$i]["item_id"]);
                    $description_item = $getItems[0]["description"];
                    if(strlen($getItems[0]["item_alias"])>0){
                        $description_item = $getItems[0]["item_alias"];
                    }
                    if($this->settings_info["show_barcode_receipt"]=="0"){
                        $item_id = self::idFormat_item($getItems[0]["id"]);
                    }
                    
                }
                
                if($this->settings_info["show_barcode_receipt"]=="1"){
                    $barcode = $getItems[0]["barcode"]." ";
                }
                
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $description = strlen($description_item) > 15 ? substr($description_item,0,15) : $description_item;
                //$printer -> text($barcode.$item_id."  ".$Arabic -> utf8Glyphs($description, 40));
                $total_price+= floatval($items[$i]["final_price_disc_qty"]);

                $unit_price = self::value_format_custom($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),$this->settings_info);
                
                $total = self::value_format_custom($items[$i]["final_price_disc_qty"],$this->settings_info);

                if($gift){
                    $unit_price = "---";
                    $total = "---";
                }
                
                $show_discount = "";
                if($items[$i]["discount"]>0){
                    $show_discount = "(Dis: ".number_format($items[$i]["discount"],1)."%)";
                }

                //$line = sprintf('%-24s %-4s %-10s',"Price ".$Arabic -> utf8Glyphs("السعر", 40).":".$unit_price. " ".$show_discount,"x ".(float)$items[$i]["qty"],"Total ".$Arabic -> utf8Glyphs("المجموع", 40).":".$total);
                $line = sprintf('%-40s* %5d* %15d* %15d*',$description,"","","");//(float)$items[$i]["qty"]
                
                
                


                $printer -> text($line);
                //$printer -> feed(1);
                $printer->feed(1);
                $total_items+=$items[$i]["qty"]; 
            }*/
            
            for($i=0;$i<count($items);$i++){ 
                $description_item = "";
                $item_id = "";
                $barcode = "";
                if($items[$i]["item_id"] === NULL){
                    $description_item = $items[$i]["description"];
                    $item_id = "";
                }else{
                    $getItems = $items_info->get_item($items[$i]["item_id"]);
                    $description_item = $getItems[0]["description"];
                    if(strlen($getItems[0]["item_alias"])>0){
                        $description_item = $getItems[0]["item_alias"];
                    }
                    if($this->settings_info["show_barcode_receipt"]=="0"){
                        $item_id = self::idFormat_item($getItems[0]["id"]);
                    }
                    
                }
                
                if($this->settings_info["show_barcode_receipt"]=="1"){
                    $barcode = $getItems[0]["barcode"]." ";
                }
                
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $description = strlen($description_item) > 36 ? substr($description_item,0,36) : $description_item;
                $printer -> text($barcode.$item_id."  ".$description . "\n");
                $total_price+= floatval($items[$i]["final_price_disc_qty"]);

                $unit_price = self::value_format_custom($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),$this->settings_info);
                
                $total = self::value_format_custom($items[$i]["final_price_disc_qty"],$this->settings_info);

                if($gift){
                    $unit_price = "---";
                    $total = "---";
                }
                
                $show_discount = "";
                if($items[$i]["discount"]>0){
                    $show_discount = "(Dis: ".number_format($items[$i]["discount"],1)."%)";
                }

                $line = sprintf('%-24s %-4s %-10s',"Price:".$unit_price. " ".$show_discount,"x ".(float)$items[$i]["qty"],"Total:".$total);
                //$printer -> setJustification(Printer::f);
                $printer -> text($line . "\n");
                $printer->feed(1);

                //$line = sprintf('%-5s %-10s x %-3d %8s %10s',$items[$i]["item_id"], $out, (float)$items[$i]["qty"], $unit_price, $total);
                //$printer -> text($line . "\n");

                $total_items+=$items[$i]["qty"];
                
            }
            
            $buffer -> setFontSize(30);
            $gross_total_item = sprintf('%-14s %17s',"Total Items ".$Arabic -> utf8Glyphs("العدد ", 40),$total_items);
            $printer->text($gross_total_item."");
            if($gift==0){
                $gross = sprintf('%-14s %17s',"Gross total ".$Arabic -> utf8Glyphs("المجموع ", 40),self::value_format_custom($total_price,$this->settings_info));
                $printer->text($gross."");
                if(abs($discount_value)>0){
                    $disc = sprintf('%-14s %25s','Discount '.$Arabic -> utf8Glyphs("الحسم ", 40),$discount_percentage."%");
                    $printer->text($disc."");

                    $tot = sprintf('%-14s %17s',"Total ".$Arabic -> utf8Glyphs("المجموع النهائي ", 40),self::value_format_custom($total_price+$discount_value,$this->settings_info));
                    $printer->text($tot."");
                }  
            }
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->feed(2);
            $printer->text("Vendor: ".$_SESSION['username']."");

            if($info[0]['sales_person']>0){
                $employees_info = $employees->get_employee_even_delete($info[0]['sales_person']);
                $printer->text("Salesperson: ".$employees_info[0]['first_name']." ".$employees_info[0]['last_name']."");
            }
            $printer->feed(1);
            $printer -> setPrintBuffer($buffer);
            
            $buffer -> setFontSize(28);
            $out_footer = $Arabic -> utf8Glyphs($this->settings_info["invoice_footer"], 40);
            
            $printer->text($out_footer);
                
            //$printer->text("\n".$this->settings_info["invoice_footer"]."\n");
            $printer->feed(2);

            $printer -> cut();

            $printer->close();
        }
    }
    
    public function print_invoice_id_style_3($id_,$gift=0){
        self::giveAccessTo(array(2,4));
        $this->settings_info["show_currency_in_report"] = $this->settings_info["show_currency_on_receipt"];
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            $employees = $this->model("employees");
            
            
            $customers = $this->model("customers");

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            if($this->settings_info["print_invoice_lbp"]=="1"){
                $info[0]["invoice_discount"] = $info[0]["invoice_discount"]*$info[0]["rate"];
                $info[0]["total_value"] = $info[0]["total_value"]*$info[0]["rate"];
                $this->settings_info["default_currency_symbol"]="LBP";
                for($i=0;$i<count($items);$i++){ 
                    $items[$i]["selling_price"]=$items[$i]["selling_price"]*$info[0]["rate"];
                    $items[$i]["final_price_disc_qty"]=$items[$i]["final_price_disc_qty"]*$info[0]["rate"];
                    $items[$i]["price_after_manual_discount"]=$items[$i]["price_after_manual_discount"]*$info[0]["rate"];
                    $items[$i]["final_cost_vat_qty"]=$items[$i]["final_cost_vat_qty"]*$info[0]["rate"];
                }
            }
            
            $discount_percentage = number_format(abs((100*$info[0]["invoice_discount"])/$info[0]["total_value"]),2);
            $discount_value = $info[0]["invoice_discount"];

            $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
            $printer = new Printer($connector);

            $main_root = self::get_main_root();
            if(is_file($main_root."/resources/".$this->settings_info["logo_to_print_name"])){
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $main_root = self::get_main_root();
                $tux = EscposImage::load($main_root."/resources/".$this->settings_info["logo_to_print_name"], false);
                $printer -> bitImage($tux);
            }
            $printer -> feed(2);


            /* Name of shop */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);

            if($this->settings_info["hide_shop_name_on_invoice"]==0){
                $printer -> text($this->settings_info["shop_name"]."\n\n");

            }

            if(strlen($this->settings_info["address"])>0){
                $printer -> text($this->settings_info["address"]."\n");
            }

            if(strlen($this->settings_info["phone_nb"])>0){
                $printer -> text($this->settings_info["phone_nb"]."\n");
            }

            $printer -> selectPrintMode();
            //$printer -> feed();
            
            /* prepare arabic */
            mb_internal_encoding("UTF-8");
            
            $Arabic = new I18N_Arabic('Glyphs');
            $buffer = new ImagePrintBuffer();
            
           
            

            $store = $this->model("store");
            $store_info = $store->getStoresById($_SESSION['store_id']);

            /* header info */
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Sales Invoice");
            $printer -> text("\n");
            $printer -> text("ID: ".self::idFormat_invoice($info[0]["id"]));
            $printer -> text("\n");
            $printer -> text("Branch: ".$store_info[0]["name"]);
            $printer -> text("\n");
            $printer -> text("Date: ".$info[0]["creation_date"]);
            $printer -> feed(1);

            $total_price = 0;
            $getItems = null;
            //$line = sprintf('%-5s %-10s %-3s %8s %10s',"ID", "Description", "QTY", "Unit P.", "Total P.");
            //$printer->text($line."\n");
            //$line = sprintf('%-5s %-10s %-3s %8s %10s',"--", "-----------", "---", "--------", "--------");
            //$printer->text($line."\n");
            $total_items = 0;
            
            $printer -> text("____________________________________________");
            $printer->feed(2);
             
            for($i=0;$i<count($items);$i++){ 
                $description_item = "";
                $item_id = "";
                $barcode = "";
                if($items[$i]["item_id"] === NULL){
                    $description_item = $items[$i]["description"];
                    $item_id = "";
                }else{
                    $getItems = $items_info->get_item($items[$i]["item_id"]);
                    $description_item = $getItems[0]["description"];
                    if(strlen($getItems[0]["item_alias"])>0){
                        $description_item = $getItems[0]["item_alias"];
                    }
                    if($this->settings_info["show_barcode_receipt"]=="0"){
                        $item_id = self::idFormat_item($getItems[0]["id"]);
                    }
                    
                }
                
                if($this->settings_info["show_barcode_receipt"]=="1"){
                    $barcode = $getItems[0]["barcode"]." ";
                }
                
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                
                //$description = strlen($description_item) > 36 ? substr($description_item,0,36) : $description_item;
                
                $printer -> setPrintBuffer($buffer);
                $printer -> text($barcode.$item_id."  ".$Arabic -> utf8Glyphs($description_item, 40) . "\n");
                $total_price+= floatval($items[$i]["final_price_disc_qty"]);

                $unit_price = self::value_format_custom($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),$this->settings_info);
                
                $total = self::value_format_custom($items[$i]["final_price_disc_qty"],$this->settings_info);

                if($gift){
                    $unit_price = "---";
                    $total = "---";
                }
                
                $show_discount = "";
                if($items[$i]["discount"]>0){
                    $show_discount = "(Dis: ".number_format($items[$i]["discount"],1)."%)";
                }

                $line = sprintf('%-24s %-4s %-10s',"Price:".$unit_price. " ".$show_discount,"x ".(float)$items[$i]["qty"],"Total:".$total);
                //$printer -> setJustification(Printer::f);
                $printer -> text($line . "\n");
                $printer->feed(1);

                //$line = sprintf('%-5s %-10s x %-3d %8s %10s',$items[$i]["item_id"], $out, (float)$items[$i]["qty"], $unit_price, $total);
                //$printer -> text($line . "\n");

                $total_items+=$items[$i]["qty"];
                
            }

            //$printer->feed(0);

            $gross_total_item = sprintf('%-14s %17s',"Total Items",$total_items);
            $printer->text($gross_total_item."\n");

            if($gift==0){
                $gross = sprintf('%-14s %17s',"Gross total:",self::value_format_custom($total_price,$this->settings_info));
                $printer->text($gross."\n");

                if(abs($discount_value)>0){
                    $disc = sprintf('Discount: %22s',$discount_percentage."%");
                    $printer->text($disc."\n");

                    $tot = sprintf('Total: %25s',self::value_format_custom($total_price+$discount_value,$this->settings_info));
                    $printer->text($tot."\n");
                }  
            }

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("\nVendor: ".$_SESSION['username']."\n");

            if($info[0]['sales_person']>0){
                $employees_info = $employees->get_employee_even_delete($info[0]['sales_person']);
                $printer->text("Salesperson: ".$employees_info[0]['first_name']." ".$employees_info[0]['last_name']."\n");
            }
            $printer->feed(1);
            if($info[0]['customer_id']>0){
                $customers_info = $customers->getCustomersById($info[0]['customer_id']);
				
				$printer -> setPrintBuffer($buffer);
                $printer -> text("\nCustomer name: ".$Arabic -> utf8Glyphs($customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"], 40) . "\n");
				$printer -> setPrintBuffer($buffer);
				$printer -> text("\nAddress: ".$Arabic -> utf8Glyphs($customers_info[0]["address"])."\n");
				
                //$printer->text("\nCustomer name: ".$customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"]."\n");
            }
            
            $printer->feed(1);
            $printer -> setPrintBuffer($buffer);
            
            $buffer -> setFontSize(28);
            $out_footer = $Arabic -> utf8Glyphs($this->settings_info["invoice_footer"], 40);
            
            $printer->text("\n".$out_footer."\n");
                
            //$printer->text("\n".$this->settings_info["invoice_footer"]."\n");
            $printer->feed(2);

            $printer -> cut();

            $printer->close();
        }
    }
    
    public function print_invoice_id_full_details___($id_,$gift=0){
        self::giveAccessTo(array(2,4));
        
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            $employees = $this->model("employees");

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            if($this->settings_info["print_invoice_lbp"]=="1"){
                $info[0]["total_value"] = $info[0]["total_value"]*$info[0]["rate"];
                $info[0]["invoice_discount"] = $info[0]["invoice_discount"]*$info[0]["rate"];
                $this->settings_info["default_currency_symbol"]="LBP";
                for($i=0;$i<count($items);$i++){ 
                    $items[$i]["selling_price"]=$items[$i]["selling_price"]*$info[0]["rate"];
                    $items[$i]["final_price_disc_qty"]=$items[$i]["final_price_disc_qty"]*$info[0]["rate"];
                    $items[$i]["price_after_manual_discount"]=$items[$i]["price_after_manual_discount"]*$info[0]["rate"];
                    $items[$i]["final_cost_vat_qty"]=$items[$i]["final_cost_vat_qty"]*$info[0]["rate"];
                }
            }
            
            $discount_percentage = number_format(abs((100*$info[0]["invoice_discount"])/$info[0]["total_value"]),2);
            $discount_value = $info[0]["invoice_discount"];
            
            //try {
                // Enter the share name for your USB printer here
                //$connector = null;
                
                
                mb_internal_encoding("UTF-8");
                $Arabic = new I18N_Arabic('Glyphs');
                $fontPath = "fonts/arabtype.ttf";
                $buffer = new ImagePrintBuffer();
                
        
                $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                $printer = new Printer($connector);
                
                 
               
                $main_root = self::get_main_root();
                if(is_file($main_root."/resources/".$this->settings_info["logo_to_print_name"])){
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $main_root = self::get_main_root();
                    $tux = EscposImage::load($main_root."/resources/".$this->settings_info["logo_to_print_name"], false);
                    $printer -> bitImage($tux);
                }

                $printer -> feed(2);

                /* Name of shop */
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                
                if($this->settings_info["hide_shop_name_on_invoice"]==0){
                    $printer -> text($this->settings_info["shop_name"]."\n\n");
                }
                
                if(strlen($this->settings_info["address"])>0){
                    $printer -> text($this->settings_info["address"]."\n");
                }
                
                if(strlen($this->settings_info["phone_nb"])>0){
                    $printer -> text($this->settings_info["phone_nb"]."\n");
                }

                $printer -> selectPrintMode();
                $printer -> feed();


                /* header info */
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("Sales Invoice");
                $printer -> text("\n");
                $printer -> text("ID: ".self::idFormat_invoice($info[0]["id"]));
                $printer -> text("\n");
                $printer -> text("Date: ".$info[0]["creation_date"]);
                $printer -> feed(2);
                 
                $total_price = 0;
                $getItems = null;
                //$line = sprintf('%-5s %-10s %-3s %8s %10s',"ID", "Description", "QTY", "Unit P.", "Total P.");
                //$printer->text($line."\n");
                //$line = sprintf('%-5s %-10s %-3s %8s %10s',"--", "-----------", "---", "--------", "--------");
                //$printer->text($line."\n");
		$total_items = 0;
                for($i=0;$i<count($items);$i++){ 
                    if($items[$i]["item_id"] === NULL){
                        $description_item = $items[$i]["description"];
                    }else{
                        $getItems = $items_info->get_item($items[$i]["item_id"]);
                        $description_item = $getItems[0]["description"];
                        if(strlen($getItems[0]["item_alias"])>0){
                            $description_item = $getItems[0]["item_alias"];
                        }
                    }
                    $total_price+= floatval($items[$i]["final_price_disc_qty"]);
                    
                    $unit_price = number_format($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)), $this->settings_info["number_of_decimal_points"]);

                    $total = number_format($items[$i]["final_price_disc_qty"], $this->settings_info["number_of_decimal_points"]);
                    
                    if($gift){
                        $unit_price = "---";
                        $total = "---";
                    }

                    $out = strlen($description_item) > 40 ? substr($description_item,0,40) : $description_item;
                    $printer -> setPrintBuffer($buffer);
                    $out = $Arabic -> utf8Glyphs($out, 40);
                    
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text($items[$i]["item_id"]."  ".$out. " x ".(float)$items[$i]["qty"] . "\n");

                    //$line = sprintf('*%-20s* *%15s*',$items[$i]["item_id"]."  ".$out. " x ".(float)$items[$i]["qty"], $total);
                    $line = sprintf($out);
                    $printer -> text($line . "\n");
                    $line = sprintf('%-10s %15s %15s',"ID: ".$items[$i]["item_id"],$unit_price." ".$this->settings_info["default_currency_symbol"]." x ".(float)$items[$i]["qty"], $total." ".$this->settings_info["default_currency_symbol"]);
                    $printer -> text($line . "\n");
                    $printer->feed(1);
                    //$textLine = explode("\n", $textLtr);
                    //foreach($textLine as $text) {
                        //$printer -> text($text . "\n");
                    //}
                    //$printer->feed(5);
                    //$printer->close();
                  
                    
                    //$printer->text($line."\n");
					
                    $total_items+=$items[$i]["qty"];
                }

                $printer->feed(2);
				
		$gross_total_item = sprintf('%-14s %17s',"Total Items",$total_items);
                $printer->text($gross_total_item."\n");
                
                if($gift==0){
                    $gross = sprintf('%-14s %17s',"Gross total:",number_format($total_price,2). " " . $this->settings_info["default_currency_symbol"]);
                    $printer->text($gross."\n");
                    
                    if($discount_value>0){
                        $disc = sprintf('Discount: %22s',$discount_percentage."%");
                        $printer->text($disc."\n");
                        
                        $tot = sprintf('Total: %25s',number_format($total_price+$discount_value,2). " " . $this->settings_info["default_currency_symbol"]);
                        $printer->text($tot."\n");
                    }  
                }
                
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("\nVendor: ".$_SESSION['username']."\n");
                
                if($info[0]['sales_person']>0){
                    $employees_info = $employees->get_employee_even_delete($info[0]['sales_person']);
                    $printer->text("Salesperson: ".$employees_info[0]['first_name']." ".$employees_info[0]['last_name']."\n");
                }
                
                //$buffer -> setFont($fontPath);
                //$buffer -> setFontSize(68);
                $printer->feed(2);
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $out_footer = $Arabic -> utf8Glyphs($this->settings_info["invoice_footer"], 40);
                $printer->text("\n".$out_footer."\n");
                $printer->feed(2);
                 
                $printer -> cut();

                $printer->close();
            //} catch (Exception $e) {
                
            //}
        }
    }
    
    public function print_invoice_id_full_details($id_,$gift=0){
        self::giveAccessTo(array(2,4));
        $this->settings_info["show_currency_in_report"] = $this->settings_info["show_currency_on_receipt"];
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            $employees = $this->model("employees");

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            if($this->settings_info["print_invoice_lbp"]=="1"){
                $info[0]["invoice_discount"] = $info[0]["invoice_discount"]*$info[0]["rate"];
                $info[0]["total_value"] = $info[0]["total_value"]*$info[0]["rate"];
                $this->settings_info["default_currency_symbol"]="LBP";
                for($i=0;$i<count($items);$i++){ 
                    $items[$i]["selling_price"]=$items[$i]["selling_price"]*$info[0]["rate"];
                    $items[$i]["final_price_disc_qty"]=$items[$i]["final_price_disc_qty"]*$info[0]["rate"];
                    $items[$i]["price_after_manual_discount"]=$items[$i]["price_after_manual_discount"]*$info[0]["rate"];
                    $items[$i]["final_cost_vat_qty"]=$items[$i]["final_cost_vat_qty"]*$info[0]["rate"];
                }
            }
            
            $discount_percentage = number_format(abs((100*$info[0]["invoice_discount"])/$info[0]["total_value"]),2);
            $discount_value = $info[0]["invoice_discount"];
            
            //try {
                // Enter the share name for your USB printer here
                //$connector = null;
                
                
                mb_internal_encoding("UTF-8");
                $Arabic = new I18N_Arabic('Glyphs');
                $fontPath = "fonts/arabtype.ttf";
                $buffer = new ImagePrintBuffer();
                
        
                $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                $printer = new Printer($connector);
                
                 
               
                $main_root = self::get_main_root();
                if(is_file($main_root."/resources/".$this->settings_info["logo_to_print_name"])){
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $main_root = self::get_main_root();
                    $tux = EscposImage::load($main_root."/resources/".$this->settings_info["logo_to_print_name"], false);
                    $printer -> bitImage($tux);
                }
                
                $printer -> feed(2);

                /* Name of shop */
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                
                if($this->settings_info["hide_shop_name_on_invoice"]==0){
                    $printer -> text($this->settings_info["shop_name"]."\n\n");
                }
                
                if(strlen($this->settings_info["address"])>0){
                    $printer -> text($this->settings_info["address"]."\n");
                }
                
                if(strlen($this->settings_info["phone_nb"])>0){
                    $printer -> text($this->settings_info["phone_nb"]."\n");
                }

                $printer -> selectPrintMode();
                $printer -> feed();
                
                $store = $this->model("store");
                $store_info = $store->getStoresById($_SESSION['store_id']);


                /* header info */
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("Sales Invoice");
                $printer -> text("\n");
                $printer -> text("ID: ".self::idFormat_invoice($info[0]["id"]));
                $printer -> text("\n");
                $printer -> text("Branch: ".$store_info[0]["name"]);
                $printer -> text("\n");
                $printer -> text("Date: ".$info[0]["creation_date"]);
                $printer -> feed(2);
                 
                $total_price = 0;
                $getItems = null;
                //$line = sprintf('%-5s %-10s %-3s %8s %10s',"ID", "Description", "QTY", "Unit P.", "Total P.");
                //$printer->text($line."\n");
                //$line = sprintf('%-5s %-10s %-3s %8s %10s',"--", "-----------", "---", "--------", "--------");
                //$printer->text($line."\n");
		$total_items = 0;
                for($i=0;$i<count($items);$i++){ 
                    if($items[$i]["item_id"] === NULL){
                        $description_item = $items[$i]["description"]." (".$getItems[0]["barcode"].")";
                    }else{
                        $getItems = $items_info->get_item($items[$i]["item_id"]);
                        $description_item = $getItems[0]["description"]." (".$getItems[0]["barcode"].")";
                        if(strlen($getItems[0]["item_alias"])>0){
                            $description_item = $getItems[0]["item_alias"]." (".$getItems[0]["barcode"].")";
                        }
                    }
                    $total_price+= floatval($items[$i]["final_price_disc_qty"]);
                    
                    $unit_price = self::value_format_custom($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),$this->settings_info);

                    $total = self::value_format_custom($items[$i]["final_price_disc_qty"],$this->settings_info);
                    
                    if($gift){
                        $unit_price = "---";
                        $total = "---";
                    }

                    $out = strlen($description_item) > 40 ? substr($description_item,0,40) : $description_item;
                    $printer -> setPrintBuffer($buffer);
                    if(self::is_arabic($out)){
                        $out = $Arabic -> utf8Glyphs($out, 40);
                    }
                    //$out = $Arabic -> utf8Glyphs($out, 40);
                    
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    //$printer -> text($items[$i]["item_id"]."  ".$out. " x ".(float)$items[$i]["qty"] . "\n");

                    //$line = sprintf('*%-20s* *%15s*',$items[$i]["item_id"]."  ".$out. " x ".(float)$items[$i]["qty"], $total);
                    $line = sprintf("ID: ".$items[$i]["item_id"]." - ".$out);
                    $printer -> text($line . "\n");
                    $line = sprintf('%-15s %15s %15s',$unit_price." x ".(float)$items[$i]["qty"], "Disc: ".number_format($items[$i]["discount"],1)."%", $total);
                    $printer -> text($line . "\n");
                    $printer->feed(1);
                    //$textLine = explode("\n", $textLtr);
                    //foreach($textLine as $text) {
                        //$printer -> text($text . "\n");
                    //}
                    //$printer->feed(5);
                    //$printer->close();
                  
                    
                    //$printer->text($line."\n");
					
                    $total_items+=$items[$i]["qty"];
                }

                //$printer->feed(2);
				
		$gross_total_item = sprintf('%-14s %17s',"Total Items",$total_items);
                $printer->text($gross_total_item."\n");
                
                if($gift==0){
                    $gross = sprintf('%-14s %17s',"Gross total:",self::value_format_custom($total_price,$this->settings_info));
                    $printer->text($gross."\n");
                    
                    if(abs($discount_value)>0){
                        $disc = sprintf('Discount: %22s',$discount_percentage."%");
                        $printer->text($disc."\n");
                        
                        $tot = sprintf('Total: %29s',self::value_format_custom($total_price+$discount_value,$this->settings_info));
                        $printer->text($tot."\n");
                    }  
                }
                
                $printer->feed(2);
                
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("\nVendor: ".$_SESSION['username']."\n");
                
                if($info[0]['sales_person']>0){
                    $employees_info = $employees->get_employee_even_delete($info[0]['sales_person']);
                    $printer->text("Salesperson: ".$employees_info[0]['first_name']." ".$employees_info[0]['last_name']."\n");
                }
                
                //$buffer -> setFont($fontPath);
                //$buffer -> setFontSize(68);
                $printer->feed(2);
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $out_footer = $Arabic -> utf8Glyphs($this->settings_info["invoice_footer"], 40);
                $printer->text("\n".$out_footer."\n");
                $printer->feed(2);
                 
                $printer -> cut();

                $printer->close();
            //} catch (Exception $e) {
                
            //}
        }
    }
    
    public function uniord($u) {
        // i just copied this function fron the php.net comments, but it should work fine!
        $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }
    
    public function is_arabic($str) {
        if(mb_detect_encoding($str) !== 'UTF-8') {
            $str = mb_convert_encoding($str,mb_detect_encoding($str),'UTF-8');
        }

        /*
        $str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
        $str = preg_split('//u',$str); <- this function woulrd probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
        */
        preg_match_all('/.|\n/u', $str, $matches);
        $chars = $matches[0];
        $arabic_count = 0;
        $latin_count = 0;
        $total_count = 0;
        foreach($chars as $char) {
            //$pos = ord($char); we cant use that, its not binary safe 
            $pos = self::uniord($char);
            //echo $char ." --> ".$pos.PHP_EOL;

            if($pos >= 1536 && $pos <= 1791) {
                $arabic_count++;
            } else if($pos > 123 && $pos < 123) {
                $latin_count++;
            }
            $total_count++;
        }
        if(($arabic_count/$total_count) > 0.6) {
            // 60% arabic chars, its probably arabic
            return true;
        }
        return false;
    }
    
    public function print_invoice_id_style_1($id_,$gift=0){
        self::giveAccessTo(array(2,4));
        $this->settings_info["show_currency_in_report"] = $this->settings_info["show_currency_on_receipt"];
        if($this->settings_info_local["auto_print"]=="1" || $this->settings_info_local["auto_print"]=="2"){
            
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");
            
            $employees = $this->model("employees");
            $customers = $this->model("customers");

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            if($this->settings_info["print_invoice_lbp"]=="1"){
                $this->settings_info["default_currency_symbol"]="LBP";
                $info[0]["total_value"] = $info[0]["total_value"]*$info[0]["rate"];
                $info[0]["invoice_discount"] = $info[0]["invoice_discount"]*$info[0]["rate"];
                for($i=0;$i<count($items);$i++){ 
                    $items[$i]["selling_price"]=$items[$i]["selling_price"]*$info[0]["rate"];
                    $items[$i]["final_price_disc_qty"]=$items[$i]["final_price_disc_qty"]*$info[0]["rate"];
                    $items[$i]["price_after_manual_discount"]=$items[$i]["price_after_manual_discount"]*$info[0]["rate"];
                    $items[$i]["final_cost_vat_qty"]=$items[$i]["final_cost_vat_qty"]*$info[0]["rate"];
                }
            }
            
            
            
            $discount_percentage = number_format(abs((100*$info[0]["invoice_discount"])/$info[0]["total_value"]),2);
            $discount_value = $info[0]["invoice_discount"];

                
                mb_internal_encoding("UTF-8");
                $Arabic = new I18N_Arabic('Glyphs');
                $fontPath = "fonts/ae_AlHor.ttf";
        
                $connector = new WindowsPrintConnector($this->settings_info_local["printer_name"]);
                $printer = new Printer($connector);
      
                 
               
                $main_root = self::get_main_root();
                if(is_file($main_root."/resources/".$this->settings_info["logo_to_print_name"])){
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $main_root = self::get_main_root();
                    $tux = EscposImage::load($main_root."/resources/".$this->settings_info["logo_to_print_name"], false);
                    $printer -> bitImage($tux);
                }
                $printer -> feed(2);
                

                /* Name of shop */
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                
                if($this->settings_info["hide_shop_name_on_invoice"]==0){
                    $printer -> text($this->settings_info["shop_name"]."\n\n");
                }
                
                if(strlen($this->settings_info["address"])>0){
                    $printer -> text($this->settings_info["address"]."\n");
                }
                
                if(strlen($this->settings_info["phone_nb"])>0){
                    $printer -> text($this->settings_info["phone_nb"]."\n");
                }

                $printer -> selectPrintMode();
                $printer -> feed();
                
                
                $store = $this->model("store");
                $store_info = $store->getStoresById($_SESSION['store_id']);


                /* header info */
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("Sales Invoice");
                $printer -> text("\n");
                $printer -> text("ID: ".self::idFormat_invoice($info[0]["id"]));
                $printer -> text("\n");
                $printer -> text("Branch: ".$store_info[0]["name"]);
                $printer -> text("\n");
                $printer -> text("Date: ".$info[0]["creation_date"]);
                $printer -> feed(2);
                 
                $total_price = 0;
                $getItems = null;
                $line = sprintf('%-5s %-10s %-3s %8s %10s',"ID", "Description", "QTY", "Unit P.", "Total P.");
                $printer->text($line."\n");
                $line = sprintf('%-5s %-10s %-3s %8s %10s',"--", "-----------", "---", "--------", "--------");
                $printer->text($line."\n");
		$total_items = 0;
                for($i=0;$i<count($items);$i++){ 
                    if($items[$i]["item_id"] === NULL){
                        $description_item = $items[$i]["description"];
                    }else{
                        $getItems = $items_info->get_item($items[$i]["item_id"]);
                        $description_item = $getItems[0]["description"];
                        if(strlen($getItems[0]["item_alias"])>0){
                            $description_item = $getItems[0]["item_alias"];
                        }
                    }
                    $total_price+= floatval($items[$i]["final_price_disc_qty"]);
                    
                    $unit_price = self::value_format_custom($items[$i]["selling_price"]*(1-($items[$i]["discount"]/100)),$this->settings_info);

                    $total = self::value_format_custom($items[$i]["final_price_disc_qty"]+0,$this->settings_info);
                    
                    if($gift){
                        $unit_price = "---";
                        $total = "---";
                    }

                    $out = strlen($description_item) > 10 ? substr($description_item,0,10) : $description_item;
                    
                    $line = sprintf('%-5s %-10s x %-3d %8s %10s',$items[$i]["item_id"], $out, (float)$items[$i]["qty"], $unit_price, $total);
                    $printer -> text($line . "\n");
					
                    $total_items+=$items[$i]["qty"];
                }

                $printer->feed(2);
				
		$gross_total_item = sprintf('%-14s %17s',"Total Items",$total_items);
                $printer->text($gross_total_item."\n");
                
                if($gift==0){
                    $gross = sprintf('%-14s %17s',"Gross total:",self::value_format_custom($total_price,$this->settings_info));
                    $printer->text($gross."\n");
                    
                    if(abs($discount_value)>0){
                        $disc = sprintf('Discount: %22s',$discount_percentage."%");
                        $printer->text($disc."\n");
                        $tot = sprintf('Total: %25s',self::value_format_custom($total_price+$discount_value,$this->settings_info));
                        $printer->text($tot."\n");
                    } 
                    
                    if($this->settings_info["add_vat_on_invoice_only_on_receipt"]==1){
                        $gross = sprintf('%-14s %17s',"VAT:",(($this->settings_info["vat"]-1)*100).' %');
                        $printer->text($gross."\n");
                        
                        $this->settings_info["round_val"]=0;
                        $gross = sprintf('%-14s %17s',"Total Amount:",self::value_format_custom(($total_price+$discount_value)*$this->settings_info["vat"],$this->settings_info));
                        $printer->text($gross."\n");
                    }
                    
                     
                }
                
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("\nVendor: ".$_SESSION['username']."\n");
                
                if($info[0]['sales_person']>0){
                    $employees_info = $employees->get_employee_even_delete($info[0]['sales_person']);
                    $printer->text("Salesperson: ".$employees_info[0]['first_name']." ".$employees_info[0]['last_name']."\n");
                }
                
                
                if($info[0]['customer_id']>0){
                    $customers_info = $customers->getCustomersById($info[0]['customer_id']);
                    $printer->text("\nCustomer name: ".$customers_info[0]["name"]." ".$customers_info[0]["middle_name"]." ".$customers_info[0]["last_name"]."\n");
                }
                
                
                $printer->text("\n".$this->settings_info["invoice_footer"]."\n");
                $printer->feed(2);
                 
                $printer -> cut();

                $printer->close();
            //} catch (Exception $e) {
                
            //}
        }
    }
    
    
    
    

    public function _print_invoice_id($id_){
        self::giveAccessTo(array(2,4));
        if($this->settings_info["auto_print"]=="1" || $this->settings_info["auto_print"]=="2"){
            $id = filter_var($id_, FILTER_SANITIZE_NUMBER_INT);
            $invoice = $this->model("invoice");
            $items_info = $this->model("items");

            $info = $invoice->getInvoiceById($id);
            $items = $invoice->getItemsOfInvoice($id);
            
            $discount_percentage = number_format(abs((100*$info[0]["invoice_discount"])/$info[0]["total_value"]),2);
            $discount_value = $info[0]["invoice_discount"];

            try {
                // Enter the share name for your USB printer here
                $connector = null;
                $connector = new WindowsPrintConnector($this->settings_info["printer_name"]);

                $printer = new Printer($connector);

                /* Name of shop */
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text($this->settings_info["shop_name"]."\n\n");
                $printer -> text($this->settings_info["address"]."\n");
                $printer -> text($this->settings_info["phone_nb"]."\n");
                $printer -> selectPrintMode();
                $printer -> feed();


                /* header info */
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("Sales Invoice");
                $printer -> text("\n");
                $printer -> text("ID: ".self::idFormat_invoice($info[0]["id"]));
                $printer -> text("\n");
                $printer -> text("Date: ".$info[0]["creation_date"]);
                $printer -> feed(2);

                /* Items */
                $total_price = 0;
                $getItems = null;
                for($i=0;$i<count($items);$i++){ 
                    if($items[$i]["item_id"] === NULL){
                        $description_item = $items[$i]["description"];
                    }else{
                        $getItems = $items_info->get_item($items[$i]["item_id"]);
                        $description_item = $getItems[0]["description"];
                    }

                    $total_price+= floatval($items[$i]["final_price_disc_qty"]);

                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> text($description_item." x ".(float)$items[$i]["qty"]);
                    $printer->feed(1);
                        $printer->setJustification(Printer::JUSTIFY_RIGHT);
                        $printer->text(number_format($items[$i]["final_price_disc_qty"],2) . " " . $this->settings_info["default_currency_symbol"]);
                        $printer->feed(1);
                    }
                    $printer->feed(2);

                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("Gross total: ");
                    //$printer->setJustification(Printer::JUSTIFY_RIGHT);
                    $printer->text(number_format($total_price, 2) . " " . $this->settings_info["default_currency_symbol"]);
                    $printer->feed(1);
                    $printer->text("Discount:    ".$discount_percentage."%");
                    $printer->feed(1);
                    $printer->text("Total:       ".number_format(($total_price+$discount_value),2)." ". $this->settings_info["default_currency_symbol"]);


                    $printer->feed(2);

                    /* Footer info */
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text($this->settings_info["footer_invoice"]."\n");
                    $printer->feed(2);
                    $printer -> cut();
                    
                    

                    /* Close printer */
                    $printer->close();
                } catch (Exception $e) {
                    //echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
                }
                //echo "HERE";
        }
    }
    
    
}
