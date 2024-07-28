<?php
date_default_timezone_set("Asia/Beirut");

error_reporting(0);
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

require_once 'config/define.php';
require_once 'config/my_sql.php';
require_once 'application/mvc/models/invoiceModel.php';
require_once 'application/mvc/models/itemsModel.php';
require_once 'application/mvc/models/emailModel.php';
require_once 'application/mvc/models/telegramModel.php';
require_once 'application/mvc/models/customersModel.php';
require_once 'application/mvc/models/generate_pdfModel.php';
require_once 'application/core/lib/my_sql.php';
require_once 'cronjob_para.php';


$main_dir = __DIR__;
$website = WEBSITE;
 
if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 6) {
    require_once __DIR__ . '/vendor/autoload.php';   
}




$test_mode=0;



function get_settings(){
    $config = array();
    $query = "select * from settings";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    for($i=0;$i<count($result);$i++){
        $config["".$result[$i]["name"]]=$result[$i]["value"];
    }
    return $config;
}

function getItemsOfInvoiceDetails($invoice_id){
    $query = "select * from invoice_items where invoice_id=".$invoice_id." and deleted=0";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    return $result;
}

function get_customer($id){
    $query = "select * from customers where id=".$id;
    $result = my_sql::fetch_assoc(my_sql::query($query));
    return $result[0]["name"];
}

function get_salesman($id){
    $query = "select * from employees where id=".$id;
    $result = my_sql::fetch_assoc(my_sql::query($query));
    return $result[0]["first_name"]." ".$result[0]["last_name"];
}

function get_vendor_name($casbox_id){
    $query = "select vendor_id from cashbox where id=".$casbox_id;
    $result = my_sql::fetch_assoc(my_sql::query($query));
    
    $query_u = "select * from users where id=".$result[0]["vendor_id"];
    $result_u = my_sql::fetch_assoc(my_sql::query($query_u));
    
    return $result_u[0]["username"];
    
}

function send_invoice_to_email(){
    global $main_dir,$website;
    $email = new emailModel();
    $customers = new customersModel();
    $invoice = new invoiceModel();
    $generate_pdf = new generate_pdfModel();
    
    
    $invoices_to_send = $invoice->get_invoices_to_send_by_email();
    for($i=0;$i<count($invoices_to_send);$i++){
        $email_info=array();
        $customer_info = $customers->getCustomersById($invoices_to_send[$i]["customer_id"]);
        
        $email_info["invoice_id"]=$invoices_to_send[$i]["id"];

        $email_info["email_config_id"]=1;
        $email_info["main_dir"]=$main_dir;
        $email_info["send_to"]=$customer_info[0]["email"];
        
        $email_info["send_to_name"]=$customer_info[0]["name"]." ".$customer_info[0]["middle_name"]." ".$customer_info[0]["last_name"];
        
        $generate_pdf->generate_pdf_invoice($invoices_to_send[$i]["id"],$main_dir,$website);
        sleep(1);
        $result = $email->send_email($email_info);

        if($result){
            $invoice->set_invoice_sent_by_email($invoices_to_send[$i]["id"]);
        }
    }
}


function send_messages_to_telegram_new($settings){
    $telegram = new telegramModel();
    $telegram_messages = $telegram->get_all_pending_messages();
    
    $telegram_accounts = get_all_telegram_account_id();
    
    for($i=0;$i<count($telegram_messages);$i++){
        
        $chatid = explode(",", $telegram_accounts[$telegram_messages[$i]["telegram_id"]]["chat_id"]);
        $txt=$telegram_messages[$i]["message"];
        $token = explode(",", $telegram_accounts[$telegram_messages[$i]["telegram_id"]]["token"]);
        
        for($t=0;$t<count($chatid);$t++){
            $result = sendMessage($chatid[$t], $txt, $token[$t]);
            if($result){
                $telegram->set_as_sent($telegram_messages[$i]["id"]);
            }
        }  
    }
}

function send_messages_to_telegram($settings){
    $telegram = new telegramModel();
    $telegram_messages = $telegram->get_all_pending_messages();
    for($i=0;$i<count($telegram_messages);$i++){
        
        $chatid = explode(",", $settings["telegram_chatid"]);
        $txt=$telegram_messages[$i]["message"];
        $token = explode(",", $settings["telegram_token"]);
        
        for($t=0;$t<count($chatid);$t++){
            $result = sendMessage($chatid[$t], $txt, $token[$t]);
            if($result){
                $telegram->set_as_sent($telegram_messages[$i]["id"]);
            }
        }  
    }
}

function send_invoice_to_telegram($settings){
    global $test_mode;
    $invoice = new invoiceModel();
    $items = new itemsModel();
    
    $invoices_to_send = $invoice->get_invoices_to_send();
    for($i=0;$i<count($invoices_to_send);$i++){
         
        $items_details = getItemsOfInvoiceDetails($invoices_to_send[$i]["id"]);
        
        $tokens=array();
        $chatids=array();
        
        if($test_mode==0){
            $tokens= explode(",", $settings["telegram_token"]);
            $chatids=explode(",", $settings["telegram_chatid"]);
            //$token = $settings["telegram_token"];
            //$chatid = $settings["telegram_chatid"];
        }else{
            $tokens[0] = "5961648866:AAG1yP450kAnRU_k_ng7GZm5IuGSg0lKAmc";
            $chatids[0] = "589684836";
        }
        $txt = "<strong>INVOICE: #".$invoices_to_send[$i]["id"]."</strong> ".$invoices_to_send[$i]["creation_date"]." \n";
        if($invoices_to_send[$i]["customer_id"]>0){
            $txt .= "<strong>CUSTOMER: </strong>".get_customer($invoices_to_send[$i]["customer_id"])." \n";
        }
        
        
        if($invoices_to_send[$i]["cashbox_id"]>0){
            $txt .= "<strong>VENDOR: </strong>".get_vendor_name($invoices_to_send[$i]["cashbox_id"])." \n";
        }
        
        if($invoices_to_send[$i]["employee_id"]>0){
            $txt .= "<strong>SALESMAN: </strong>".get_salesman($invoices_to_send[$i]["sales_person"])." \n";
        }
        
        if($invoices_to_send[$i]["closed"]==1){
            $txt .= "<strong>PAYMENT: </strong>CASH \n\n";
        }else{
            $txt .= "<strong>PAYMENT: </strong>DEBT \n\n";
        }
        
        for($k=0;$k<count($items_details);$k++){
            
            if($items_details[$k]["item_id"]!=NULL){
                $item_info = $items->get_item($items_details[$k]["item_id"]);
                $txt.="<b>".$item_info[0]["description"]."(#".$items_details[$k]["item_id"].")</b> \n";
                $txt.="<pre>UNIT PRICE=". number_format($items_details[$k]["selling_price"],2)." USD</pre> \n";
                $txt.="<pre>UNIT COST=". number_format($items_details[$k]["buying_cost"],2)." USD</pre> \n";
                $txt.="<pre>QTY=". floatval($items_details[$k]["qty"])."</pre> \n";
                $txt.="<pre>TOTAL PRICE=".number_format($items_details[$k]["final_price_disc_qty"],2)." USD</pre>\n";
                $txt.="<pre>TOTAL COST=".number_format($items_details[$k]["final_cost_vat_qty"],2)." USD</pre>\n";
                $txt.= "<pre>PROFIT=". number_format($items_details[$k]["profit"],2)." USD</pre>\n\n";
            }
            
            
        }
        
        if($invoices_to_send[$i]["invoice_discount"]!=0){
            $txt .= "\n<b>INVOICE DSC:</b> ". number_format(abs($invoices_to_send[$i]["invoice_discount"]),2)." USD \n";
        }
        $txt .= "<b>T. AMOUNT:</b> ". number_format($invoices_to_send[$i]["total_value"]-abs($invoices_to_send[$i]["invoice_discount"]),2)." USD \n";
        if($invoices_to_send[$i]["invoice_discount"]!=0){
            $txt .= "<b>T. PROFIT BEFORE DSC:</b> ". number_format($invoices_to_send[$i]["total_profit"],2)." USD \n";
            $txt .= "<b>T. PROFIT AFTER DSC:</b> ". number_format($invoices_to_send[$i]["total_profit"]-abs($invoices_to_send[$i]["invoice_discount"]),2)." USD \n";
        }else{
            $txt .= "<b>T. PROFIT:</b> ". number_format($invoices_to_send[$i]["total_profit"],2)." USD \n";
        }
        
        if($invoices_to_send[$i]["cashbox_info"]!=null){
            $cashbox_info= json_decode($invoices_to_send[$i]["cashbox_info"],true);
            $txt .= "<b>CASHBOX NET USD:</b> ". number_format($cashbox_info["net_usd"],2)."  \n";
            $txt .= "<b>CASHBOX NET LBP:</b> ". number_format($cashbox_info["net_lbp"],2)."  \n";
        }
        
        
        for($t=0;$t<count($chatids);$t++){
            $result = sendMessage($chatids[$t], $txt, $tokens[$t]);
            if($result){
                $invoice->set_invoices_to_send($invoices_to_send[$i]["id"]);
            }
        }
        
        
    }
}  

function get_telegram_account_id($id){
    $query = "select * from telegram_accounts where id=".$id;
    $result = my_sql::fetch_assoc(my_sql::query($query));
    return $result;
}

function get_all_telegram_account_id(){
    $temp=array();
    $query = "select * from telegram_accounts";
    $result = my_sql::fetch_assoc(my_sql::query($query));
    for($i=0;$i<count($result);$i++){
        $temp[$result[$i]["id"]]=$result[$i];
    }
    return $temp;
}


function send_invoice_to_telegram_new($telegram_account_id){
    global $test_mode;
    $invoice = new invoiceModel();
    $items = new itemsModel();
    
    $telegram_account_info = get_telegram_account_id($telegram_account_id);
    
    $invoices_to_send = $invoice->get_invoices_to_send();
    for($i=0;$i<count($invoices_to_send);$i++){
         
        $items_details = getItemsOfInvoiceDetails($invoices_to_send[$i]["id"]);
        
        $tokens=array();
        $chatids=array();
        
        if($test_mode==0){
            $tokens= explode(",", $telegram_account_info[0]["token"]);
            $chatids=explode(",", $telegram_account_info[0]["chat_id"]);
        }else{
            $tokens[0] = "5961648866:AAG1yP450kAnRU_k_ng7GZm5IuGSg0lKAmc";
            $chatids[0] = "589684836";
        }
        $txt = "<strong>INVOICE: #".$invoices_to_send[$i]["id"]."</strong> ".$invoices_to_send[$i]["creation_date"]." \n";
        if($invoices_to_send[$i]["customer_id"]>0){
            $txt .= "<strong>CUSTOMER: </strong>".get_customer($invoices_to_send[$i]["customer_id"])." \n";
        }
        
        
        if($invoices_to_send[$i]["cashbox_id"]>0){
            $txt .= "<strong>VENDOR: </strong>".get_vendor_name($invoices_to_send[$i]["cashbox_id"])." \n";
        }
        
        if($invoices_to_send[$i]["employee_id"]>0){
            $txt .= "<strong>SALESMAN: </strong>".get_salesman($invoices_to_send[$i]["employee_id"])." \n";
        }
        
        if($invoices_to_send[$i]["closed"]==1){
            $txt .= "<strong>PAYMENT: </strong>CASH \n\n";
        }else{
            $txt .= "<strong>PAYMENT: </strong>DEBT \n\n";
        }
        
        for($k=0;$k<count($items_details);$k++){
            
            if($items_details[$k]["item_id"]!=NULL){
                $item_info = $items->get_item($items_details[$k]["item_id"]);
                $txt.="<b>".$item_info[0]["description"]."(#".$items_details[$k]["item_id"].")</b> \n";
                $txt.="<pre>UNIT PRICE=". number_format($items_details[$k]["selling_price"],2)." USD</pre> \n";
                $txt.="<pre>UNIT COST=". number_format($items_details[$k]["buying_cost"],2)." USD</pre> \n";
                $txt.="<pre>QTY=". floatval($items_details[$k]["qty"])."</pre> \n";
                $txt.="<pre>TOTAL PRICE=".number_format($items_details[$k]["final_price_disc_qty"],2)." USD</pre>\n";
                $txt.="<pre>TOTAL COST=".number_format($items_details[$k]["final_cost_vat_qty"],2)." USD</pre>\n";
                $txt.= "<pre>PROFIT=". number_format($items_details[$k]["profit"],2)." USD</pre>\n\n";
            }
            
            
        }
        
        if($invoices_to_send[$i]["invoice_discount"]!=0){
            $txt .= "\n<b>INVOICE DSC:</b> ". number_format(abs($invoices_to_send[$i]["invoice_discount"]),2)." USD \n";
        }
        $txt .= "<b>T. AMOUNT:</b> ". number_format($invoices_to_send[$i]["total_value"]-abs($invoices_to_send[$i]["invoice_discount"]),2)." USD \n";
        if($invoices_to_send[$i]["invoice_discount"]!=0){
            $txt .= "<b>T. PROFIT BEFORE DSC:</b> ". number_format($invoices_to_send[$i]["total_profit"],2)." USD \n";
            $txt .= "<b>T. PROFIT AFTER DSC:</b> ". number_format($invoices_to_send[$i]["total_profit"]-abs($invoices_to_send[$i]["invoice_discount"]),2)." USD \n";
        }else{
            $txt .= "<b>T. PROFIT:</b> ". number_format($invoices_to_send[$i]["total_profit"],2)." USD \n";
        }
        
        
        $cashbox_info= json_decode($invoices_to_send[$i]["cashbox_info"],true);
        $txt .= "\n<b>CASHBOX NET USD:</b> ". number_format($cashbox_info["net_usd"],0)."  \n";
        $txt .= "<b>CASHBOX NET LBP:</b> ". number_format($cashbox_info["net_lbp"],0)."  \n";
        
        for($t=0;$t<count($chatids);$t++){
            $result = sendMessage($chatids[$t], $txt, $tokens[$t]);
            if($result){
                $invoice->set_invoices_to_send($invoices_to_send[$i]["id"]);
            }
        }
        
        
    }
}

function sendMessage($chatID, $messaggio, $token) {
    $data = [
        'chat_id' => $chatID,
        'text' => $messaggio,
        'parse_mode'=>"html"
    ];
    $response = file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) );
    return $response;
}

$settings = get_settings();

if($settings["telegram_enable"]=='1'){
    if($settings["switch_to_new_telegram"]=='1'){
        send_invoice_to_telegram_new(1);
        send_messages_to_telegram_new($settings);
    }else{
        send_invoice_to_telegram($settings);
        send_messages_to_telegram($settings);
    }
}




if($settings["email_invoice_enable"]=='1'){
    
    send_invoice_to_email($settings);
}





/*
function sendMessage($chatID, $messaggio, $token) {
    $data = [
        'chat_id' => $chatID,
        'text' => $messaggio,
        'parse_mode'=>"html"
    ];
    $response = file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) );
    var_dump($response);
}

$token = "5961648866:AAG1yP450kAnRU_k_ng7GZm5IuGSg0lKAmc";
$chatid = "589684836";
$txt = "UPSILON TEST: #123 \n\n";
$txt .= "<b>ITEM-1:</b> <pre>UNIT PRICE(10,000 USD) QTY(5) TOTAL(50,000 USD)</pre> \n";
$txt .= "<b>ITEM-2:</b> <pre>UNIT PRICE(10,000 USD) QTY(5) TOTAL(50,000 USD)</pre> \n";
$txt .= "<b>ITEM-3:</b> <pre>UNIT PRICE(10,000 USD) QTY(5) TOTAL(50,000 USD)</pre> \n\n";

$txt .= "<b>INVOICE DISCOUNT:</b>1,000 USD \n";
$txt .= "<b>TOTAL AMOUNT:</b>149,000 USD";

sendMessage($chatid, $txt, $token);


//sendMessage("232057301", $txt, "5741000061:AAH-44ki1eCkmUlr_41c9OkVmuZ8T09tFvc");
///232057301
 * 
 */