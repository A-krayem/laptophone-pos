<?php
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
        <title>Print PI</title>
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

            body.A4               .sheet { width: 210mm;  } /*height: 296mm*/

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
                body.A4 { width: 210mm }
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
                 font-size: 10px;
                 height: 25px;
                 padding-left: 5px;
                 padding-right: 5px;
                 vertical-align: middle;
             }
             
             #pi_item tr td{
                 border: 1px solid #000;
                 padding-left: 5px;
                 height: 25px !important;
             }
        </style>
    </head>
    <body class="A4">
        <section class="sheet padding-10mm">
            <table  style="width: 100%;border: none; ">
                <tr>
                    <td style="width: 50%; font-size: 16px;">
                        <?php 
                        if(count($data["supplier"])>0){
                            echo "<b>Supplier:</b> ".$data["supplier"][0]["name"]; 
                        }else{
                            
                        }
                        
                        echo "<br/><b>Invoice Reference:</b> ".$data["pi"][0]["invoice_reference"];
                        
                        $rdate = explode(" ", $data["pi"][0]["receive_invoice_date"]);
                        echo "<br/><b>Received Date:</b> ".$rdate[0]; 
                        
                        
                        echo "<br/><b>Currency:</b> ".$data["currency"][0]["symbole"]; 
                        
                        
                        ?>
                    </td>
                    <td style=""></td>
                </tr>
            </table>
            <table id="pi_item" style="width: 100%; ">
                <tr>
                    <td style="width: 55px;"><b>Sys ID</b></td>
                    <td style=""><b>Description</b></td>
                    <td style=""><b>Price</b></td>
                    <td style="width: 45px;"><b>Qty</b></td>
                    <td style="width: 45px;"><b>FQty</b></td>
                    <td style="width: 50px;"><b>Disc</b></td>
                    <td style="width: 45px;"><b>Vat</b></td>
                    <td style="width: 45px;"><b>Disc</b></td>
                    <td style="width: 100px;"><b>Total</b></td>
                </tr>
                <?php 
                    $tt=0;
                    for($i=0;$i<count($data["pi_items"]);$i++){ 
                   
                    ?>
                <tr>
                    <td style="font-size: 10px;"><?php echo self::idFormat_item($data["pi_items"][$i]["id"]); ?></td>
                    <td><?php echo $data["items"][$data["pi_items"][$i]["item_id"]]["description"]; ?></td>
                    
                    
                    <?php
                    
                    if($_SESSION['hide_critical_data']==1){?>
                        <td>*****</</td>
                    <td style="text-align: center">*****</td>
                    <td style="text-align: center">*****</</td>
                    
                   <?php }else{ ?>
                       <td><?php echo self::value_format_custom_no_currency($data["pi_items"][$i]["cost"],$data["settings"]); ?></td>
                    <td style="text-align: center"><?php echo floatval($data["pi_items"][$i]["qty"]); ?></td>
                    <td style="text-align: center"><?php echo floatval($data["pi_items"][$i]["fqty"]); ?></td>
                    
                   <?php } ?>
                    
                    
                    
                    <td><?php echo floatval($data["pi_items"][$i]["discount_percentage"]); ?>%</td>
                    
                    <td>
                        <?php 
                        if($data["pi_items"][$i]["vat"]==1){
                            echo floatval(($data["pi"][0]["vat"]-1)*100)."%";
                        }else{
                            echo "";
                        }
                        ?>
                    </td>
                    
                    <td><?php echo floatval($data["pi_items"][$i]["discount_after_vat"]); ?>%</td>
                    
                    <?php 
                    
                    $total = $data["pi_items"][$i]["cost"];
                    if($data["pi_items"][$i]["discount_percentage"]>0){
                        $total = $data["pi_items"][$i]["cost"]*(1-($data["pi_items"][$i]["discount_percentage"]/100));
                    }
                    
                    if($data["pi_items"][$i]["vat"]==1){
                        $total = $total*$data["pi"][0]["vat"];
                    }
                    
                    if($data["pi_items"][$i]["discount_after_vat"]>0){
                        $total = $total*(1-($data["pi_items"][$i]["discount_after_vat"]/100));
                    }
                    
                    
                    
                    $tt+=$total*floatval($data["pi_items"][$i]["qty"]);
                    ?>
                    
                    
                    <?php
                    
                    if($_SESSION['hide_critical_data']==1){?>
                     
                    <td style="text-align: center">*****</</td>
                    
                   <?php }else{ ?>
                    <td><?php echo self::value_format_custom_no_currency($total*floatval($data["pi_items"][$i]["qty"]),$data["settings"]); ?></td>
                   <?php } ?>
                </tr>
                <?php } 
                
                    $st_to ="Total";
                    if(count($data["more_pi"])>0){
                        $st_to ="SubTotal";
                    }
               
                ?>
                
                <tr>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td colspan="2" style="text-align: right; padding-right: 5px;"><b><?php echo $st_to; ?></b></td>
                    
                    <?php
                    
                    if($_SESSION['hide_critical_data']==1){?>
                     
                    <td style="text-align: center">*****</</td>
                    
                   <?php }else{ ?>
                    <td><b> <?php echo self::value_format_custom_no_currency($tt,$data["settings"]); ?></b></td>
                   <?php } ?>
                    
                </tr>
                <?php 
                
                    
                    for($i=0;$i<count($data["more_pi"]);$i++){
                        
                        if($data["all_pi_more_types_array"][$data["more_pi"][$i]["type_id"]]["discount_fees"]==2){
                            $tt-=$data["more_pi"][$i]["value"];
                        }else{
                            $tt+=$data["more_pi"][$i]["value"];
                        }
                        
                        ?>
                    
                    <tr>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td colspan="2" style="text-align: right; padding-right: 5px;"><b><?php echo $data["all_pi_more_types_array"][$data["more_pi"][$i]["type_id"]]["description"] ?></b></td>
                        
                        <?php
                    
                    if($_SESSION['hide_critical_data']==1){?>
                     
                    <td style="text-align: center">*****</</td>
                    
                   <?php }else{ ?>
                        <td><b> <?php echo self::value_format_custom_no_currency(floatval($data["more_pi"][$i]["value"]),$data["settings"]); ?></b></td>
                   <?php } ?>
                        
                    </tr>
                <?php } ?>
                    
                    
                    <?php 
                    if(count($data["more_pi"])>0){
                    ?>
                    <tr>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td colspan="2" style="text-align: right; padding-right: 5px;"><b>Total</b></td>
                        
                        <?php
                    
                    if($_SESSION['hide_critical_data']==1){?>
                     
                    <td style="text-align: center">*****</</td>
                    
                   <?php }else{ ?>
                        <td><b> <?php echo self::value_format_custom_no_currency($tt,$data["settings"]); ?></b></td>
                   <?php } ?>
                        
                    </tr>
                    <?php 
                    }
                    ?>
            </table>
        
        </section>
    </body>
</html>


