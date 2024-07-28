<?php

$currency_symbole = "";
if($data["invoice"][0]["currency_id"]==1){
    $currency_symbole = " USD";
}else{
    $currency_symbole = " LBP";
}
function convertNumber($number)
{
    return $number;
    /* list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer{0} == "-")
    {
        $output = "negative ";
        $integer    = ltrim($integer, "-");
    }
    else if ($integer{0} == "+")
    {
        $output = "positive ";
        $integer    = ltrim($integer, "+");
    }

    if ($integer{0} == "0")
    {
        $output .= "zero";
    }
    else
    {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group   = rtrim(chunk_split($integer, 3, " "), " ");
        $groups  = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g)
        {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++)
        {
            if ($groups2[$z] != "")
            {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? " and "
                            : ", "
                    );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0)
    {
        $output .= " point";
        for ($i = 0; $i < strlen($fraction); $i++)
        {
            $output .= " " . convertDigit($fraction{$i});
        }
    }

    return $output;*/
}

function convertGroup($index)
{
    switch ($index)
    {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3)
{
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
    {
        return "";
    }

    if ($digit1 != "0")
    {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0")
        {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0")
    {
        $buffer .= convertTwoDigit($digit2, $digit3);
    }
    else if ($digit3 != "0")
    {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0")
    {
        switch ($digit1)
        {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else if ($digit1 == "1")
    {
        switch ($digit2)
        {
            case "1":
                return "eleven";
            case "2":
                return "twelve";
            case "3":
                return "thirteen";
            case "4":
                return "fourteen";
            case "5":
                return "fifteen";
            case "6":
                return "sixteen";
            case "7":
                return "seventeen";
            case "8":
                return "eighteen";
            case "9":
                return "nineteen";
        }
    } else
    {
        $temp = convertDigit($digit2);
        switch ($digit1)
        {
            case "2":
                return "twenty-$temp";
            case "3":
                return "thirty-$temp";
            case "4":
                return "forty-$temp";
            case "5":
                return "fifty-$temp";
            case "6":
                return "sixty-$temp";
            case "7":
                return "seventy-$temp";
            case "8":
                return "eighty-$temp";
            case "9":
                return "ninety-$temp";
        }
    }
}

function convertDigit($digit)
{
    switch ($digit)
    {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Invoice</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="resources/favicon.png">
        <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <link href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <title>Test</title>
        <style type="text/css">

            @page { margin: 0 }
            body { 
                margin: 0 ;
            }

            .sheet {
                margin: 0;
                overflow: hidden;
                position: relative;
                box-sizing: border-box;
                page-break-after: always;
            }

            body.A4               .sheet { width: 210mm;} /*height: 296mm*/
            body.A5               .sheet { width: 148mm; height: 209mm }
            body.A5.landscape     .sheet { width: 210mm; height: 147mm }

            /** Padding area **/
            .sheet.padding-10mm { padding: 10mm }
            .sheet.padding-15mm { padding: 15mm }
            .sheet.padding-20mm { padding: 20mm }
            .sheet.padding-25mm { padding: 25mm }

            /** For screen preview **/
            @media screen {
                body { background: #e0e0e0 }
                .sheet {
                    background: white;
                    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
                    margin: 5mm auto;
                }
            }

            /** Fix for Chrome issue #273306 **/
            @media print {
                body.A3.landscape { width: 420mm }
                body.A3, body.A4.landscape { width: 297mm }
                body.A4, body.A5.landscape { width: 210mm }
                body.A5                    { width: 148mm }
                body.letter, body.legal    { width: 216mm }
                body.letter.landscape      { width: 280mm }
                body.legal.landscape       { width: 357mm }
            }

            .tdstyle{
                border: 1px solid #000 !important;
                padding-top: 2px !important;
                padding-bottom:  2px !important;
                padding-left:  2px !important;
                padding-right:  2px !important;
            }

            .line{
                width: 100%;
                height: 1px;
                border-bottom: 1px solid black;
            }

            table { page-break-inside:auto }
            tr    { page-break-inside:avoid; page-break-after:auto }
            thead { display:table-header-group }
            tfoot { display:table-footer-group }
            
            
            .inv_table{
                 width: 100%;
             }
             
             .header_table{
                 width: 100%;
             }
             
             .header_table tr td{
                 border: 0px solid #000 !important;
                 font-size: 16px;
                 height: 25px;
                 padding-left: 5px;
                 padding-right: 5px;
                 vertical-align: middle;
                 
             }
             
             .inv_table tr td{
                 border: 1px solid #000 !important;
                 height: 25px;
                 padding-left: 5px;
                 padding-right: 5px;
                 vertical-align: middle;
             }
             /*
            table { page-break-inside:auto; }
            tr    { page-break-inside:avoid; page-break-after:auto }
            thead { display:table-header-group }
            tfoot { display:table-footer-group }*/      
             
            
            table { page-break-inside:auto; }
            tr    { page-break-inside:avoid; page-break-after:auto }
            thead { display:table-header-group }
            tfoot { display:table-footer-group }     
        </style>
        
        <style>
            @page { size: A5 landscape}

       
        </style>
    </head>
    <body class="A5 landscape">
        <section class="sheet padding-10mm">
            
            <?php 
            if(strlen($this->settings_info["invoice_logo"])>0){ 
                echo "<img id='logo_img' style='width:120px;margin-bottom: 10px;' src='resources/".$this->settings_info["invoice_logo"]."' />";
            }else{
                echo "<b style='font-size:30px;'>".$this->settings_info["shop_name"]."</b><br/>";
            }      
        ?>
        <table style="width: 100%">
            <tr>
                <td style="width: 49%">
                <?php
                    if(strlen($this->settings_info["phone_nb"])>0) {
                        echo "<b>Phone: </b> ".$this->settings_info["phone_nb"]."<br/>";
                    }
                ?>
                </td>
                <td style="width: 49%">
                <?php
                    if(strlen($this->settings_info["address"])>0) {
                        echo "<b>Address: </b>".$this->settings_info["address"]."</span><br/>"; 
                    }
                ?>
                </td>
            </tr>
            
            <tr>
                <td style="width: 49%">
                <?php
                    if(strlen($this->settings_info["invoice_pdf_MOF"])>0) {
                        echo "<b>MOF: </b>".$this->settings_info["invoice_pdf_MOF"]."<br/>"; 
                    }
                ?>
                </td>
                <td style="width: 49%">
                <?php
                    if(strlen($data["invoice"][0]["creation_date"])>0) {
                        echo "<b>Invoice date: </b>".$data["invoice"][0]["creation_date"]."<br/>"; 
                    }
                ?>
                </td>
            </tr>
            
            <tr>
                <td style="width: 49%">
                <?php
                    //echo "<b>s: </b>".$data["invoice"][0]["id"]."<br/>"; 
                ?>
                </td>
                <td style="width: 49%">

                    
                <?php
                    if($data["invoice"][0]["invoice_nb_official"]>0) {
                        $dt = explode("-", $data["invoice"][0]["creation_date"]);
                        echo "<b>Invoice Reference: </b>".$dt[0]."-".sprintf("%07s", $data["invoice"][0]["invoice_nb_official"])."<br/>"; 
                    }else{
                        echo "<b>Invoice Number: </b>".sprintf("%07s", $data["invoice"][0]["id"])."<br/>"; 
                    }
                    

                ?>
                </td>
            </tr>
            
            <tr>
                <td style="width: 49%">
                
                </td>
                <td style="width: 49%">

                </td>
            </tr>
        </table>
        
            
        <div class="row" style="margin-top: 20px;border-top: 1px solid">
            <div class="col-xs-6" style="margin-top: 10px;">
                <?php  if(!is_null($data["customer"])){ ?>
                <b>To: </b> <?php if(!is_null($data["customer"])) echo ucwords ($data["customer"][0]["name"])." ".ucwords ($data["customer"][0]["middle_name"])." ".ucwords ($data["customer"][0]["last_name"]); ?><br/>
                <b>Address: </b> <?php if(!is_null($data["customer"][0]["address"])) echo ucwords ($data["customer"][0]["address"]); ?><br/>
                <?php if(!is_null($data["customer"][0]["phone"]) && $data["customer"][0]["phone"]!="") echo "<b>PHONE: </b>".ucwords ($data["customer"][0]["phone"]); ?><br/>
                <?php if(!is_null($data["customer"][0]["mof"]) && $data["customer"][0]["mof"]!="-" && $data["customer"][0]["mof"]!="") echo "<b>MOF: </b>".ucwords ($data["customer"][0]["mof"]); ?>
                <b>Total Balance: </b><?php echo self::value_format_custom($data["total_balance"],$this->settings_info); ?>
                <?php } ?>
            </div>
        </div>
            
       
            
            <?php 
            $t_d=0;
                for($i=0;$i<count($data["invoice_items"]);$i++){ 
                    $t_d+=$data["invoice_items"][$i]["discount"];
                }
            ?>
        
        <table class="inv_table" style="margin-top: 5px;">
            <tr>
                <td style="font-size: 15px;"><b>Description</b></td>
                <td style="width: 100px;font-size: 15px;"><b>Note</b></td>
                <td style="width: 80px;font-size: 15px;text-align: center"><b>Qty</b></td>
                <td style="width: 90px;font-size: 15px; text-align: center"><b>Price/unit</b></td>
                <?php if($t_d>0){ ?>
                <td style="width: 20px;font-size: 15px;text-align: center"><b>Disc.</b></td>
                <?php } ?>
                <td style="width: 20px;font-size: 15px;text-align: center"><b>Total</b></td>
            </tr>
                <?php 
                
                
                
                
                $total_invoice_vat = 0;
                $total_after_vat = 0;
                $total_before_vat = 0;
                for($i=0;$i<count($data["invoice_items"]);$i++){ 
                            
                    $item_info = $data["items_instance"]->get_item($data["invoice_items"][$i]["item_id"]);
                    
                    if($data["invoice_items"][$i]["discount"]>=0){
                        $total_before_vat+= $data["invoice_items"][$i]["selling_price"]*(1-$data["invoice_items"][$i]["discount"]/100)*($data["invoice_items"][$i]["qty"]);
                    }else{
                        $total_before_vat+= $data["invoice_items"][$i]["selling_price"]*($data["invoice_items"][$i]["qty"]);
                    }
                    
                ?>
              <tr>
                  <td style="font-size: 14px;">
                      <?php 

                      
                        if($item_info[0]["description"]==NULL){
                            echo $data["invoice_items"][$i]["description"];
                        }else{
                            echo $item_info[0]["description"];
                        }
                         
                      ?>
                  </td>
                  <td style="font-size: 14px;">
                      <?php 
                        
                        if(strlen($data["invoice_items"][$i]["additional_description"])>0){
                            echo $data["invoice_items"][$i]["additional_description"];
                        }
                      
                      ?>
                  </td>
                  <td style="text-align: center;font-size: 14px;text-align: center"><?php echo  rtrim(rtrim($data["invoice_items"][$i]["qty"], '0'), '.');; ?></td>
                  
                  <td style="font-size: 14px;text-align: center">
                      <?php 
                      if($data["invoice_items"][$i]["discount"]<0){
                        echo self::value_format_custom_no_currency($data["invoice_items"][$i]["selling_price"],$this->settings_info); 
                      }else{
                        echo self::value_format_custom_no_currency($data["invoice_items"][$i]["selling_price"],$this->settings_info); 
                      }
                      ?>
                  </td>
                  
                  <?php if($t_d>0){ ?>
                  <td style="text-align: center;font-size: 14px;text-align: center">
                      <?php 
                        if($data["invoice_items"][$i]["discount"]<0){
                            echo "0 %";
                        }else{
                            echo round($data["invoice_items"][$i]["discount"],1)."%";
                        }
                      ?>
                  </td> 
                  <?php } ?>
                  
                  <td style="font-size: 14px;text-align: center">
                      <?php 
                      
                      if($data["invoice_items"][$i]["discount"]>0){
                        echo self::value_format_custom_no_currency(($data["invoice_items"][$i]["selling_price"]*(1-$data["invoice_items"][$i]["discount"]/100))*($data["invoice_items"][$i]["qty"]),$this->settings_info).$currency_symbole; 
                      }else{
                        echo self::value_format_custom_no_currency($data["invoice_items"][$i]["selling_price"]*($data["invoice_items"][$i]["qty"]),$this->settings_info).$currency_symbole; 
                      }
                      
                      ?>
                  </td>

                  
                  
                  <?php 
                      $fn = $data["invoice_items"][$i]["final_price_disc_qty"];

                      
                      ?>
                  
                  
               
                 <!--  <td>
                      <?php echo self::value_format_custom_no_currency($fn,$this->settings_info);  ?>
                  </td> -->
                
              </tr>
              <?php 
              $total_after_vat+=$fn; } 
              ?>
              
              <?php 
              
              if(round($data["invoice"][0]["invoice_discount"],0)!=0){ 
                  ?>
              <tr style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important; border-top: 0px !important;">
                  <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;text-align:right;border-top: 0px !important;" colspan="<?php if($t_d>0){echo "5";}else{echo "4";}; ?>" ><b>Invoice Discount: </b></td>
                  <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;width: 90px;;border-top: 0px !important;" ><b><?php echo self::value_format_custom_no_currency($data["invoice"][0]["invoice_discount"],$this->settings_info).$currency_symbole; ?></b></td>
              </tr>
              
              <?php } ?>

              
              <?php
                if($data["currency_system_default"]==1 && $data["invoice"][0]["vat_value"]>0){ ?>
                 <tr style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important; border-top: 0px !important;">
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;text-align:right;border-top: 0px !important; font-size: 14px" colspan="<?php if($t_d>0){echo "5";}else{echo "4";}; ?>" ><b>Total VAT USD <?php echo (($data["invoice"][0]["vat_value"]-1)*100) ?> %: </b></td>
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;width: 150px;;border-top: 0px !important;font-size: 14px" >
                        <b>
                        <?php 
                        if($data["invoice"][0]["vat_value"]>0){
                            $total_invoice_vat=($total_before_vat*$data["invoice"][0]["vat_value"]-$total_before_vat);
                            echo self::value_format_custom($total_invoice_vat,$this->settings_info).""; 
                        }
                        ?>
                        </b>
                    </td>
                </tr> 
                <?php } ?>
              
              <?php if($data["currency_system_default"]==1){ ?>
              
                <?php
                if($data["invoice"][0]["total_vat_value"]>0 && (($data["invoice"][0]["vat_value"]-1)*100)>0){ ?>
                 <tr style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important; border-top: 0px !important;">
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;text-align:right;border-top: 0px !important; font-size: 14px" colspan="<?php if($t_d>0){echo "5";}else{echo "4";}; ?>" ><b>Total VAT LBP <?php echo (($data["invoice"][0]["vat_value"]-1)*100) ?> %: </b></td>
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;width: 150px;;border-top: 0px !important;font-size: 14px" >
                        <b>
                        <?php 
                        if($data["invoice"][0]["total_vat_value"]>0){
                            echo self::value_format_custom(($total_before_vat*$data["invoice"][0]["vat_value"]-$total_before_vat)*$data["currencies"][1]["rate_to_system_default"]*($data["currencies"][2]["rate_to_system_default"]),$this->settings_info).""; 
                        }
                        ?>
                        </b>
                    </td>
                </tr> 
                <?php } ?>
                
         
                <?php if($data["total_balance"]>0){ ?>
                
                
                <tr style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important; border-top: 0px !important;">
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;text-align:right;border-top: 0px !important;font-size: 14px" colspan="<?php if($t_d>0){echo "5";}else{echo "4";}; ?>" ><b>Previous Balance: </b></td>
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;width: 150px;;border-top: 0px !important;font-size: 14px" ><b> <?php echo number_format(round($data["previews_balance"],2)); ?> <?php echo $currency_symbole; ?></b></td> 
                </tr>
                <tr style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important; border-top: 0px !important;">
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;text-align:right;border-top: 0px !important;font-size: 14px" colspan="<?php if($t_d>0){echo "5";}else{echo "4";}; ?>" ><b>Total Balance till <?php echo $data["invoice"][0]["creation_date"]; ?>: </b></td>
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;width: 150px;;border-top: 0px !important;font-size: 14px" ><b><?php echo self::value_format_custom(round($data["previews_balance"],2)+$total_before_vat+$total_invoice_vat+$data["invoice"][0]["invoice_discount"],$this->settings_info); ?></b></td> 
                </tr>
                <?php } ?>
                
                
                
               <?php } ?>
                
               <tr style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important; border-top: 0px !important;">
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;text-align:right;border-top: 0px !important;font-size: 14px" colspan="<?php if($t_d>0){echo "5";}else{echo "4";}; ?>" ><b>Net Amount: </b></td>
                 
                    <?php if($data["invoice"][0]["vat_value"]>0){ ?>
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;width: 150px;;border-top: 0px !important;font-size: 14px" ><b><?php echo self::value_format_custom_no_currency(($total_before_vat+$data["invoice"][0]["invoice_discount"])*$data["invoice"][0]["vat_value"],$this->settings_info).$currency_symbole; ?></b></td>
                    <?php }else{ ?>
                    <td style="border-left: 0px !important; border-right: 0px !important; border-bottom:0px !important;width: 150px;;border-top: 0px !important;font-size: 14px" ><b><?php echo self::value_format_custom_no_currency(($total_before_vat+$data["invoice"][0]["invoice_discount"]),$this->settings_info).$currency_symbole; ?></b></td>
                    <?php } ?>     
              </tr>
              
              
              <tr>
                    <td colspan="5" style="width: 90px; border-bottom: none !important;border-left: none !important;border-right: none !important;">
                    <?php echo convertNumber(number_format($total_after_vat+$data["invoice"][0]["invoice_discount"],2, '.',''))." ".$this->settings_info["default_currency_symbol"]." Only"; ?> <br>
                    </td>
              </tr>    
        </table>
        
        <p style="width: 100%; margin-top: 20px;">
            <?php if(strlen($data["invoice"][0]["payment_note"])>0){ ?>
            <?php echo "<b>Invoice Note:</b> ".$data["invoice"][0]["payment_note"]; ?>
            <?php } ?>
        </p>
        
        <p style="bottom: 0px; direction: <?php echo $this->settings_info["footer_direction"]; ?>; width: 100%; padding: 0px;">
            <?php echo $this->settings_info["footer_text"]; ?>
        </p>
        
        </section>
    </body>
</html>
