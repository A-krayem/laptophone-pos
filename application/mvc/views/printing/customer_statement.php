<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>UPSILON - Print Sheet</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="resources/favicon.png">

        <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>

        <script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <link href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <link href="libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>

    
        <style type="text/css" media="print"> 
             body{
                 padding: 10px;
                 margin: 10px;
             }
        </style>
        <script type="text/javascript">
            
            $(document).ready(function () {
                <?php //if(strlen($this->settings_info["invoice_logo"])>0){ ?>
                //$("#logo_img").one('load', function() {
                    window.print(); 
                    window.close();
                //}).attr('src', "resources/<?php echo $this->settings_info["invoice_logo"];?>");
                <?php //}else{ ?>
                    //window.print(); 
                    //window.close();
                <?php //} ?>
            });
        </script>
    </head>
    
    <body>
        <?php echo "<b>Date:</b> ".date("Y-m-d"); ?>
        <br/>
        <?php echo "<b>Customer ID:</b> ".self::idFormat_customer($data["customer_info"][0]["id"]); ?>
        <br/>
        <?php echo "<b>Customer Name:</b> ". ucfirst($data["customer_info"][0]["name"])." ".ucfirst($data["customer_info"][0]["middle_name"])." ".ucfirst($data["customer_info"][0]["last_name"]); ?>
        <br/><br/>
        
        <table style="width: 100%;">
            <tr style="border-bottom: 1px solid #000; font-weight: bold">
                <td style="width: 80px;">Date</td>
                <td style="width: 110px;">Invoice #</td>
                <td>Description</td>
                <td style="width: 100px;">Charges</td>
                <td style="width: 100px;">Payments</td>
                <td style="width: 100px;">Remain</td>
            </tr>
            <?php 
             $total_remain = 0;
            foreach ($data["statement"] as $key => $value) {
                 
            if($data["currency_request_id"]!=$data["currency_system_default"] && $data["currency_request_id"]!=0){
                $this->settings_info["default_currency_symbol"] = $data["currencies"][$data["currency_request_id"]]["symbole"];
                $value["total_invoice_value"]=$value["total_invoice_value"]/$data["currencies"][$data["currency_request_id"]]["rate_to_system_default"];
                $value["total_payment_value"]=$value["total_payment_value"]/$data["currencies"][$data["currency_request_id"]]["rate_to_system_default"];
            }
                
            if($value["credit"]==1){
                if($value["deleted"]==0){
                    $total_remain += $value["total_invoice_value"];
                }
            }else{
                if($value["deleted"]==0){
                    $total_remain -= $value["total_payment_value"];
                }
            }    
                
            ?>
            <tr style="font-size: 12px;">
                <td><?php $dt = explode(" ", $value["creation_date"]); echo $dt[0]; ?></td>
                <td>
                    <?php
                        
                        $invoice_id = "";
                        if($value["invoice_id"]!=""){
                            $invoice_id = sprintf("%07s", $value["invoice_nb_official"]);//self::idFormat_invoice($value["invoice_id"]);
                        }else{
                            if($value["ref_payment"]!=""){
                                if($value["credit_note"]==0){
                                    $invoice_id = self::idFormat_customer_payment($value["ref_payment"]);
                                }else{
                                    $invoice_id = self::idFormat_creditnote($value["ref_payment"]);
                                }
                            }
                        }
                        
                        echo $invoice_id;
                    ?>
                </td>
                <td><?php echo $value["payment_note"]; ?></td>
                <td><?php 
                
                
                    
                echo self::value_format_custom($value["total_invoice_value"],$this->settings_info); ?>
                
                </td>
                <td>
                    <?php 
                    if($value["paid_directly"]==1){
                        echo self::value_format_custom($value["total_invoice_value"],$this->settings_info);

                    }else{
                        echo self::value_format_custom($value["total_payment_value"],$this->settings_info);
                    }
                    ?>
                </td>
                <td>
                    <?php
                        echo self::value_format_custom($total_remain,$this->settings_info);
                    ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        
    </body>
</html>
<?php
