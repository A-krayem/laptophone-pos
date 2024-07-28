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
        <title>Supplier Account Statement</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="resources/favicon.png">
        <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <link href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <!-- Include Date Range Picker -->
        <script src="libraries/bootstrap-plugins/daterangepicker-master/moment.min.js" type="text/javascript"></script>
        <script src="libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.js" type="text/javascript"></script>
        <link href="libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.css" rel="stylesheet" type="text/css"/>
        
        
        <script src="libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js" type="text/javascript"></script>
        <link href="libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css"/>

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

            /** Padding area **/
            .sheet.padding-5mm { padding: 5mm }
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
                body.A4 { width: 210mm ;}
                .tohide{
                    display: none;
                }
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
             
             .det_table tr td{
                 border: 1px solid #000 !important;
                 font-size: 12px;
                 height: 25px;
                 padding-left: 5px;
                 padding-right: 5px;
                 vertical-align: middle;
             }
            
            table { page-break-inside:auto; }
            tr    { page-break-inside:avoid; page-break-after:auto }
            thead { display:table-header-group }
            tfoot { display:table-footer-group }     
            
            .bgselected{
                background-color: #CCC !important;
                
            }
            
            .line{
                width: 100%;
                height: 1px;
                border-bottom: 1px solid black;
            }
        </style>
        <script type="text/javascript">
            function row_select(object){
                $(".bgselected").removeClass("bgselected");
                $(object).addClass("bgselected");
            }
            
            
            function delete_payment(id){
                swal({
                    title: "Are you sure?"+id,
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Delete it",
                    closeOnConfirm: true
                },
                function(isConfirm){
                   if(isConfirm){

                           $.getJSON("?r=suppliers&f=delete_supplier_payment&p0="+id, function (data) {

                            }).done(function () {
                           
                                refresh_statement();


                            });
                   }
                }); 
            }
            
            $( document ).ready(function() {
                
                var start = moment();
                var end = moment();
                
                var __start = "";
                var __end= "";
                <?php if($_GET["p2"]!="today"){ 
                    $daterange = $_GET["p2"];
                    $date_range[0] = null;
                    $date_range[1] = null;

                    $date_range_tmp = explode(" - ", $daterange);
                    $date_range[0] = date('Y-m-d', strtotime(trim($date_range_tmp[0])));
                    $date_range[1] = date('Y-m-d', strtotime(trim($date_range_tmp[1])));
                    
                    $__start = strtotime($date_range[0]);
                    $__end = strtotime($date_range[1]);
                    
                    ?>
                        
                <?php } ?>
                    
                    
                <?php if($_GET["p2"]!="today"){  ?>  
                start = moment.unix(<?php echo $__start; ?>).format("YYYY-MM-DD");
                end =  moment.unix(<?php echo $__end; ?>).format("YYYY-MM-DD");
                <?php } ?>
                //var dateString = moment.unix(1569430304).format("YYYY-MM-DD");
                
       
                        
                $('#date_filter').daterangepicker({
                    dateLimit:{month:12},
                    startDate: start,
                    endDate: end,
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'Since Creation': [moment().subtract(<?php echo $data["start_date_supplier_days"]; ?>, 'days'), moment()]

                     }
                });

                $("#date_filter").change(function() {
                    refresh_statement();
                    update_dt();
                });
                
                update_dt();
            });
            
            function update_dt(){
                var tmpval = $("#date_filter").val();
                tmpval = $("#date_filter").val().split(" - ");
                
                $("#sdate").html(tmpval[0]);
                $("#edate").html(tmpval[1]);
            }
            
            function refresh_statement(){
                window.location.replace("index.php?r=suppliers&f=print_supplier_statement&p0=<?php echo $data["supplier"][0]["id"]; ?>&p1="+$("#currency").val()+"&p2="+$("#date_filter").val());
            }
            
        </script>
    </head>
    <body class="A4">
        <section class="sheet padding-5mm">
            <input autocomplete="off" id="date_filter" class="tohide" type="text" placeholder="" style="cursor:pointer;width:200px;margin-bottom: 5px;" value="" />
            <select id="currency" onchange="refresh_statement()" class="tohide" style="width: 100px; height: 26px; display: none">
                <option <?php if($_GET["p1"]=="1") echo "selected"; ?> value="1"><?php echo $_SESSION['currency_symbol'];  ?></option>
                <option <?php if($_GET["p1"]=="2") echo "selected"; ?> value="2">LBP</option>
            </select>
            
            
            <table style="width: 100%; height: 80px;">
                <tr>
                    <td style="font-size: 20px;width: 50%;font-weight: bold;padding-left: 5px;"><?php echo $data["shop_name"]; ?></td>
                    <td style="font-size: 14px;width: 50%;text-align: right"><b>Date:</b> <?php $datetime = new DateTime();
                    echo $datetime->format('Y-m-d H:i:s'); ?></td>
                </tr>
            </table>
            <div class="line"></div>
            
            <table style="width: 100%; margin-top: 20px;">
                <tr>
                    <td style="font-size: 20px;font-weight: bold;text-align: center">Supplier Account Statement - <?php echo ucfirst($data["supplier"][0]["name"]); ?> - <?php if($_GET["p1"]=="1"){echo $_SESSION['currency_symbol']; ?> <?php }else{ echo "LBP"; } ?></td>
                </tr>
                <!-- <tr>
                    <td style="font-size: 17px;font-weight: bold;text-align: center">Available Balance: <?php echo number_format($data["av_bal"],2); ?> </td>
                </tr> -->
                <tr>
                    <td style="font-size: 14px;text-align: center"><b>From Date:</b> <span id="sdate"></span> - <b>To Date:</b> <span id="edate"></span></td>
                </tr>
            </table> 
            
            <table class="det_table" style="width: 100%; margin-top: 5px;margin-bottom: 50px;">
                <tr style="background-color: #e5e3e3">
                    <td style="width: 120px; text-align: center"><b>Transaction date</b></td>
                    <td style="width: 100px; text-align: center"><b>Created By</b></td>
                    <td style="text-align: center"><b>Reference</b></td>
                    <td style="text-align: center"><b>Description</b></td>
                    <td style="width: 75px;text-align: center"><b>Debit</b></td>
                    <td style="width: 75px;text-align: center"><b>Credit</b></td>
                    <td style="width: 90px;text-align: center"><b>Balance</b></td>
                </tr>
                
                <?php if($data["brought_balance_flag"]==-1){ ?>
                <tr onclick="row_select(this)">
                    <td colspan="6" style="text-align: left"><b>Brought Forward Balance</b></td>
                    <td>
                        <?php
                        if($_GET["p1"]=="1"){
                            echo $data["self"]->global_number_formatter($data["brought_balance"],$data["settings"]); 
                        }else{
                            echo $data["self"]->global_number_formatter($data["brought_balance"],$data["settings"]); 
                        }
                        
                        ?>
                    </td>
                </tr>
                <?php } ?>

                
                <?php 
                
                
                $total_debit=0;
                $total_credit=0;
                $bal=$data["brought_balance"];
                
                for($i=0;$i<count($data["info"]);$i++){ 

                    $date_time = explode(" ", $data["info"][$i]["creation_date"]);

                    if($data["info"][$i]["st_balance"]==0){
                        $total_debit=$data["info"][$i]["debit"];
                        $total_credit=$data["info"][$i]["credit"];
                        $bal+=($total_debit-$total_credit);
                        
                ?>
                <tr onclick="row_select(this)">
                    <td ><?php echo $date_time[0]; ?></td>
                    <td><?php 
                    
                    //$created_by_cashbox_id="Admin Account";
                    //if($data["info"][$i]["created_by"]>0){
                        //$created_by_cashbox_id=$data["cashboxes"][$data["info"][$i]["created_by"]];
                    //}
                    //var_dump(explode("-", $data["info"][$i]["created_by"]));
                    if(count(explode("-", $data["info"][$i]["created_by"]))>1){
                        $created_by_cashbox_id="Admin Account";
                            $created_by_cashbox_id=$data["cashboxes"][explode("-", $data["info"][$i]["created_by"])[1]];
                            echo $created_by_cashbox_id;
                        //echo $created_by_cashbox_id;
                    }else{
                       echo $data["users"][$data["info"][$i]["created_by"]];//$created_by_cashbox_id;
                    }
            
                    
                            
                            ?></td>
                    <td><?php echo $data["info"][$i]["reference"] ?></td>
                    
                    
                    
                    
                    
                    <td><?php echo $data["info"][$i]["desc"] ?></td>
                    
                    
                    <td>
                        <?php 
                        if($data["info"][$i]["debit"]>0){
                            
                            if($_GET["p1"]=="1"){
                                echo $data["self"]->global_number_formatter($data["info"][$i]["debit"],$data["settings"]);
                            }else{
                                echo $data["self"]->global_number_formatter($data["info"][$i]["debit"],$data["settings"]);
                            }
                             
                            
                        }else{
                            echo ""; 
                        }
                        
                        ?>
                    </td>
                    
                    
                    <td>
                        <?php 
                        if($data["info"][$i]["credit"]>0){
                            if($_GET["p1"]=="1"){
                                echo $data["self"]->global_number_formatter(abs($data["info"][$i]["credit"]),$data["settings"]); 
                            }else{
                                echo $data["self"]->global_number_formatter(abs($data["info"][$i]["credit"]),$data["settings"]); 
                            }
                        }else{
                            echo ""; 
                        }
                        
                        ?>
                    </td>
                    
                    
                    <td><?php 
                    
                    if($_GET["p1"]=="1"){
                        
                        echo $data["self"]->global_number_formatter($bal,$data["settings"]); 
                    }else{
              
                        echo $data["self"]->global_number_formatter($bal,$data["settings"]); 
                    }
                    
                    
                    ?></td>
                </tr>
                <?php } ?>
                
                <?php if($data["brought_balance_flag"]==0 && $i==0){ 
                    
                    
                    $total_debit=$data["info"][$i]["debit"];
                        $total_credit=$data["info"][$i]["credit"];
                        $bal+=($total_debit-$total_credit);
                        ?>
                <tr onclick="row_select(this)">
                    <td ><?php echo $date_time[0]; ?></td>
                    <td colspan="1">&nbsp;</td>
                    <td colspan="3" style="text-align: left"><b>Starting Balance</b></td>
                    
                    
                    <td><?php 
                    if($_GET["p1"]=="1"){
                        echo $data["self"]->global_number_formatter(abs($total_debit-$total_credit),$data["settings"]); 
                    }else{
                        echo $data["self"]->global_number_formatter(abs($total_debit-$total_credit),$data["settings"]); 
                    }
                    
                    ?></td>
                    <td ><?php 
                    if($_GET["p1"]=="1"){
                        echo $data["self"]->global_number_formatter($bal,$data["settings"]); 
                    }else{
                        echo $data["self"]->global_number_formatter($bal,$data["settings"]); 
                    }
                    
                    ?></td>
                </tr>
                <?php } ?>
                
                <?php } ?>
            </table>
        </section>
    </body>
</html>