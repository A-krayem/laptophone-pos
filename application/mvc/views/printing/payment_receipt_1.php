<?php
/**
 * English Number Converter - Collection of PHP functions to convert a number
 *                            into English text.
 *
 * This exact code is licensed under CC-Wiki on Stackoverflow.
 * http://creativecommons.org/licenses/by-sa/3.0/
 *
 * @link     http://stackoverflow.com/q/277569/367456
 * @question Is there an easy way to convert a number to a word in PHP?
 *
 * This file incorporates work covered by the following copyright and
 * permission notice:
 *
 *   Copyright 2007-2008 Brenton Fletcher. http://bloople.net/num2text
 *   You can use this freely and modify it however you want.
 */

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
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <title>Receipt</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="resources/favicon.png">

        <!-- Normalize or reset CSS with your favorite library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  
        <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <link href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        
        <style type="text/css">
            
            @font-face {
                font-family: 'DroidArabicKufiRegular';
                    src: url('application/mvc/views/font/DroidArabicKufiRegular.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
                  }
      
            @page { margin: 0 }
            body { 
                margin: 0 ;
                font-family: 'DroidArabicKufiRegular';
            }
            
            .sheet {
              margin: 0;
              overflow: hidden;
              position: relative;
              box-sizing: border-box;
              page-break-after: always;
            }

            /** Paper sizes **/
            body.A3               .sheet { width: 297mm; height: 419mm }
            body.A3.landscape     .sheet { width: 420mm; height: 296mm }
            body.A4               .sheet { width: 210mm; height: 296mm }
            body.A4.landscape     .sheet { width: 297mm; height: 209mm }
            body.A5               .sheet { width: 148mm; height: 209mm }
            body.A5.landscape     .sheet { width: 210mm; height: 147mm }
            body.letter           .sheet { width: 216mm; height: 279mm }
            body.letter.landscape .sheet { width: 280mm; height: 215mm }
            body.legal            .sheet { width: 216mm; height: 356mm }
            body.legal.landscape  .sheet { width: 357mm; height: 215mm }

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
            
            .table_receipt tr td{
                border: 1px solid #000;
                font-size: 16px;
                padding: 5px;
                padding-top: 3px !important;
                padding-bottom: 3px !important;
            }
        </style>
        
        <style>
            @page { size: A5 landscape}

            @media print {
                body{
                    font-family: 'DroidArabicKufiRegular';
                }
            }
        </style>
        
        <script type="text/javascript">
        
        </script>
        
    </head>
    <body class="A5 landscape">

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->

  <section class="sheet padding-10mm">

    <!-- Write HTML just like a web page -->
    <div style="width: 100%;text-align: center;font-size: 30px; margin-bottom: 0px; text-decoration: underline">
        <?php 
            if($this->settings_info["invoice_pdf_show_shopname"]==1){
                echo $this->settings_info["shop_name"]; 
            }
          ?>
    </div>

    <div style="width: 100%;text-align: center;font-size: 20px; margin-bottom: 10px; text-decoration: underline">Payment Receipt سند قبض  </div>
    <div style="width: 100%;text-align: center;font-size: 18px; margin-bottom: 10px; text-decoration: underline">#<?php echo self::idFormat_customer_payment($data["payment_info"][0]["id"]); ?></div>
    
    
    <table style="width: 100%; margin-top: 30px;">
        <tr>
            <td style="width: 33%; font-size: 20px">Received from Mr./Ms. </td>
            <td style="width: 33%; text-align: center;border-bottom: 1px dotted #000;font-size: 20px;"><?php if(!is_null($data["customer_info"])) echo ucwords ($data["customer_info"][0]["name"])." ".ucwords ($data["customer_info"][0]["middle_name"])." ".ucwords ($data["customer_info"][0]["last_name"]); ?></td>
            <td style="width: 33%; direction: rtl; font-size: 18px;">وصلنا من السيد (ة) </td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 40px;">
        <tr style="margin-top: 20px;">
            <td style="width: 33%; font-size: 20px">The sum  of </td>
            <td style="width: 33%; text-align: center;border-bottom: 1px dotted #000;font-size: 24px; font-weight: bold; direction: ltr;"><?php 
                        $this->settings_info["default_currency_symbol"] = $data["currencies"][$data["payment_info"][0]["currency_id"]]["symbole"];
                        echo self::value_format_custom($data["payment_info"][0]["balance"],$this->settings_info); 
                        ?></td>
            <td style="width: 33%; direction: rtl; font-size: 18px;">مبلغ وقدره </td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 40px;">
        <tr style="margin-top: 20px;">
            <td style="width: 33%; font-size: 20px; text-align: center"><?php echo convertNumber(number_format($data["payment_info"][0]["balance"],2, '.',''))." Only"; ?></td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 20px;">
        <tr style="margin-top: 20px;">
            <td style="width: 33%; font-size: 20px; text-align: right;direction: rtl">وذلك عن حساب سابق</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 30px;">
        <tr style="margin-top: 20px;">
            <td style="width: 33%; font-size: 20px; text-align: right;direction: rtl">التاريخ: <?php //$dt = explode(" ", $data["receipt_info"][0]["creation_date"]); echo $dt[0]; ?></td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 20px;">
        <tr style="margin-top: 20px;">
            <td style="width: 33%; font-size: 20px; text-align: right;direction: rtl">الامضاء: </td>
        </tr>
    </table>
  </section>

</body>
    
</html>

