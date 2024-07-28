<?php

class reports_shareholders extends Controller {

    public $licenseExpired = false;
    public $settings_info = null;
    
    public function __construct() {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    
    
    public function delete_completely($_id){
        $id= filter_var($_id,FILTER_SANITIZE_NUMBER_INT);
        $shareholder = $this->model("shareholder"); 
        $shareholder->delete_completely($id);
        echo json_encode(array());
    }
    
    public function get_shareholders_details_log($_shareholder_id){
        $shareholder_id = filter_var($_shareholder_id, self::conversion_php_version_filter());
        $shareholder = $this->model("shareholder"); 
        
        $logs = $shareholder->get_shareholders_details_log($shareholder_id);
        $data_array["data"] = array();
        for ($i = 0; $i <count($logs); $i++) {
            $tmp = array();
            
            array_push($tmp, $logs[$i]["id"]);
            array_push($tmp, $logs[$i]["start_date"]);
            
            if($logs[$i]["end_date"]==NULL){
                array_push($tmp, "Still Active");
            }else{
                array_push($tmp, $logs[$i]["end_date"]);
            }
            
            
            array_push($tmp, floatval($logs[$i]["percentage"])." %");
              
            array_push($data_array["data"], $tmp);
        }
        
        echo json_encode($data_array); 
        
    }
    
    public function profit_distribution($_startdate,$_end_date){
        $startdate = filter_var($_startdate, self::conversion_php_version_filter());
        $end_date = filter_var($_end_date, self::conversion_php_version_filter());
        
        
        $data_return=array();
        $data_return["distribution"]=array();
        $data_return["total_profit"]= 0;
        
        $shareholder = $this->model("shareholder"); 
        
        $shareholder_info=$shareholder->get_all_share_holders_even_deleted();
        $shareholder_array=array();
        for($i=0;$i<count($shareholder_info);$i++){
            $shareholder_array[$shareholder_info[$i]["id"]]=$shareholder_info[$i];
        }
        
        
        
        
        
        
        $shareholders_distribution=array();
        
        $shareholder_p=0;
        
        
        $start_datetime = new DateTime($startdate);
        $end_datetime = new DateTime($end_date);
        $current_datetime = $start_datetime;
        while ($current_datetime <= $end_datetime) {
            $current_date_mysql_format = $current_datetime->format('Y-m-d');
            $shareholder_per_day=$shareholder->profit_distribution($current_date_mysql_format)[0]["sum"];
            $shareholder_p+=$shareholder_per_day;
            $current_datetime->modify('+1 day');
            
            $shareholders=$shareholder->get_share_holders_active($current_date_mysql_format);
           
            for($i=0;$i<count($shareholders);$i++){
                if(isset($shareholders_distribution[$shareholders[$i]["shareholder_id"]])){
                    $shareholders_distribution[$shareholders[$i]["shareholder_id"]]+=round($shareholder_per_day*$shareholders[$i]["percentage"]/100,2);
                }else{
                    $shareholders_distribution[$shareholders[$i]["shareholder_id"]]=round($shareholder_per_day*$shareholders[$i]["percentage"]/100,2);
                }
            } 
        }
        
        $total_profit=0;
        foreach ($shareholders_distribution as $key => $value) {
            $total_profit+=$value;
            array_push($data_return["distribution"],array("value"=> round($value,2),"name"=>$shareholder_array[$key]["name"]));
        }
        
        
        
        if($shareholder_p>$total_profit){
            array_push($data_return["distribution"],array("value"=> round($shareholder_p-$total_profit,2) ,"name"=>"Other"));
        }
        
        //$shareholder_p=$shareholder->profit_distribution($startdate);
        //$shareholders=$shareholder->get_share_holders_active($startdate);
     
        
        $data_return["total_profit"]= number_format($shareholder_p,2);
        
        
        //for($i=0;$i<count($shareholders);$i++){
            //$total_percentage+=$shareholders[$i]["percentage"];
            //array_push($data_return["distribution"],array("value"=> round($shareholder_p[0]["sum"]*$shareholders[$i]["percentage"]/100,2),"name"=>$shareholder_array[$shareholders[$i]["shareholder_id"]]["name"]));
        //}
        
       // if($total_percentage<100 && $shareholder_p[0]["sum"]>0){
           // array_push($data_return["distribution"],array("value"=> round($shareholder_p[0]["sum"]*(100-$total_percentage)/100,2),"name"=>"Other"));
        //}
        
        //$net_profit = $shareholder_info[0]["sum"];
        echo json_encode($data_return);
    }
    
    public function get_error_details($_date){
        $date = filter_var($_date, self::conversion_php_version_filter());
        $shareholder = $this->model("shareholder"); 
        
        $invoices=$shareholder->get_error_details($date);
        
        $data_array["data"] = array();
        for ($i = 0; $i <count($invoices); $i++) {
            $tmp = array();
            
            array_push($tmp, $invoices[$i]["invoice_id"]);
            array_push($tmp, $invoices[$i]["total_inv_amt"]);
            array_push($tmp, $invoices[$i]["sum"]);
            
     
              
            array_push($data_array["data"], $tmp);
        }
        
        echo json_encode($data_array); 
    }
    
    public function get_statistics($_date){
        $date = filter_var($_date, self::conversion_php_version_filter());
        $shareholder = $this->model("shareholder"); 
        
        
        $date_range_tmp = null;
        $start_date = null;
        $end_date = null;
        
        if($date=="today"){
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }else{
            $date_range_tmp = explode(" ", $date);
            $start_date = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
            $end_date = date('Y-m-d', strtotime(trim($date_range_tmp[2])));
        }
   
        
        $statistics_details = $shareholder->get_statistics($start_date,$end_date);
         
        $data_array["data"] = array();
        
        $total_income=0;
        $total_invoices_cost=0;
        $total_invoices_profit=0;
        $total_expenses=0;
        $net_profit=0;
        
        for ($i = 0; $i <count($statistics_details); $i++) {
            $tmp = array();
            
            $total_income+=$statistics_details[$i]["total_invoices_income"];
            $total_invoices_cost+=$statistics_details[$i]["total_invoices_cost"];
            $total_invoices_profit+=$statistics_details[$i]["total_invoices_profit"];
            $total_expenses+=$statistics_details[$i]["total_expenses"];
            
            if($statistics_details[$i]["net_profit"]>0){
                $net_profit+=$statistics_details[$i]["net_profit"];
            }
            
            $verfied=$statistics_details[$i]["total_invoices_income"]-($statistics_details[$i]["total_invoices_cost"]+$statistics_details[$i]["total_invoices_profit"]);
            
            
            $alert="";
            if($verfied!=0){
                $alert="<b class='text-danger' style='float:right; cursor:pointer' onclick='show_invoice_error(\"".$statistics_details[$i]["for_date"]."\")'>".$verfied."</b>";
            }
            
            array_push($tmp, $statistics_details[$i]["for_date"]);
            array_push($tmp, number_format($statistics_details[$i]["total_invoices_income"],2)." ".$alert);
            array_push($tmp, number_format($statistics_details[$i]["total_invoices_cost"],2));
            array_push($tmp, number_format($statistics_details[$i]["total_invoices_profit"],2));
            array_push($tmp, number_format($statistics_details[$i]["total_expenses"],2));
            
            
            array_push($tmp, number_format($statistics_details[$i]["net_profit"],2));
            
            
            
            
     
              
            array_push($data_array["data"], $tmp);
        }
        
        
        $tmp = array();
        array_push($tmp, "<b>TOTALS</b>");
        array_push($tmp, "<b>". number_format($total_income,2)."</b>");
        array_push($tmp, "<b>". number_format($total_invoices_cost,2)."</b>");
        array_push($tmp, "<b>". number_format($total_invoices_profit,2)."</b>");
        array_push($tmp, "<b>". number_format($total_expenses,2)."</b>");
        array_push($tmp, "<b>". number_format($net_profit,2)."</b>");
        array_push($data_array["data"], $tmp);
        
        echo json_encode($data_array); 
    }
    
    
    public function delete_shareholder($_id){
        $id= filter_var($_id,FILTER_SANITIZE_NUMBER_INT);
        $shareholder = $this->model("shareholder"); 
        $shareholder->delete_shareholder($id);
        echo json_encode(array());
    }
    
    public function add_new_shareholder(){
        $info=array();
        $info["id_to_edit"] = filter_input(INPUT_POST, 'id_to_edit', FILTER_SANITIZE_NUMBER_INT);
        $info["percentage"] = filter_input(INPUT_POST, 'sh_per', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
        $info["name"] = filter_input(INPUT_POST, 'sh_name', self::conversion_php_version_filter());
        $info["active_date"] = filter_input(INPUT_POST, 'active_date', self::conversion_php_version_filter());
        
        
        
        $info["created_by"]=$_SESSION["id"];
        $shareholder = $this->model("shareholder"); 
        
        if($info["id_to_edit"]==0){
            $shareholder->add_new_shareholder($info);
        }else{
            $shareholder->update_shareholder($info);
        }
        
        
        echo json_encode(array());
    }
    
    public function get_shareholder_by_id($_id){
        $id= filter_var($_id,FILTER_SANITIZE_NUMBER_INT);
        $shareholder = $this->model("shareholder"); 
        $info=$shareholder->get_shareholder_by_id($id);
        $info[0]["percentage"]=floatval($info[0]["percentage"]);
        echo json_encode($info);
    }
    
    public function get_shareholders(){
        $shareholder = $this->model("shareholder"); 
        
        /* calculation */
        $currentDate = new DateTime();
        $currentDate->modify('-30 days');
        $from_date = $currentDate->format('Y-m-d');
        
        $shareholder->reset_shareholders_details($from_date,date('Y-m-d'));
        $shareholder->prepare_all_expenses($from_date,date('Y-m-d'));
        $shareholder->prepare_all_invoices_payments($from_date,date('Y-m-d'));
        $shareholder->prepare_all_invoices_profits($from_date,date('Y-m-d'));
        $shareholder->update_net_profit($from_date,date('Y-m-d'));
        /* end calculation */
        
        $filter=array();
        $all=$shareholder->get_all_share_holders($filter);
       
        
        $data_array["data"] = array();
        for ($i = 0; $i <count($all); $i++) {
            $tmp = array();
            
            array_push($tmp, $all[$i]["id"]);
            array_push($tmp, $all[$i]["name"]);
            array_push($tmp, explode(" ", $all[$i]["active_date"])[0]);
            
            if($all[$i]["deleted_date"]!=null){
                array_push($tmp, explode(" ", $all[$i]["deleted_date"])[0]);
            }else{
                array_push($tmp, "");
            }
            
            
            array_push($tmp, number_format($all[$i]["percentage"],2)." %");
            
            array_push($tmp, "");

              
            array_push($data_array["data"], $tmp);
        }
        
        echo json_encode($data_array);
    }
    
    public function get_all_sales($_p0,$_p1,$_p2){
         $data_array["data"] = array();
        for ($i = 0; $i <20; $i++) {
            $tmp = array();
            
            array_push($tmp, "A".$i);
            array_push($tmp, "B");
            array_push($tmp, "C");
            array_push($tmp, "A");
            array_push($tmp, "B");
            array_push($tmp, "C");
            array_push($tmp, "A");
            array_push($tmp, "B");
            array_push($tmp, "C");
              
            array_push($data_array["data"], $tmp);
        }
        
        echo json_encode($data_array); 
    }
    
    public function dashboard(){
        if($this->settings_info["shareholders_report"]==0){
            exit;
        }
        $data=array();
        $this->view("reports/shareholders_report",$data);
    }


}
