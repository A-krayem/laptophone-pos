<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 5.6
 * @ Decoder version: 1.0.4
 * @ Release: 02/06/2020
 *
 * @ ZendGuard Decoder PHP 5.6
 */

// Decoded file for php version 53.
echo "<!DOCTYPE HTML>\r\n<html>\r\n    <head>\r\n        <title>Customer Statement</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <!-- Include Date Range Picker -->\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/moment.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <style type=\"text/css\">\r\n\r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n\r\n            .sheet {\r\n                margin: 0;\r\n                overflow: hidden;\r\n                position: relative;\r\n                box-sizing: border-box;\r\n                page-break-after: always;\r\n            }\r\n\r\n            body.A4               .sheet { width: 210mm;} /*height: 296mm*/\r\n\r\n            /** Padding area **/\r\n            .sheet.padding-5mm { padding: 5mm }\r\n            .sheet.padding-10mm { padding: 10mm }\r\n            .sheet.padding-15mm { padding: 15mm }\r\n            .sheet.padding-20mm { padding: 20mm }\r\n            .sheet.padding-25mm { padding: 25mm }\r\n\r\n            /** For screen preview **/\r\n            @media screen {\r\n                body { background: #e0e0e0 }\r\n                .sheet {\r\n                    background: white;\r\n                    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\r\n                    margin: 5mm auto;\r\n                }\r\n            }\r\n\r\n            /** Fix for Chrome issue #273306 **/\r\n            @media print {\r\n                body.A4 { width: 210mm ;}\r\n                .tohide{\r\n                    display: none;\r\n                }\r\n            }\r\n\r\n            .tdstyle{\r\n                border: 1px solid #000 !important;\r\n                padding-top: 2px !important;\r\n                padding-bottom:  2px !important;\r\n                padding-left:  2px !important;\r\n                padding-right:  2px !important;\r\n            }\r\n\r\n            .line{\r\n                width: 100%;\r\n                height: 1px;\r\n                border-bottom: 1px solid black;\r\n            }\r\n            \r\n            .inv_table{\r\n                 width: 100%;\r\n             }\r\n             \r\n             .header_table{\r\n                 width: 100%;\r\n             }\r\n             \r\n             .header_table tr td{\r\n                 border: 0px solid #000 !important;\r\n                 font-size: 16px;\r\n                 height: 25px;\r\n                 padding-left: 5px;\r\n                 padding-right: 5px;\r\n                 vertical-align: middle;\r\n                 \r\n             }\r\n             \r\n             .det_table tr td{\r\n                 border: 1px solid #000 !important;\r\n                 font-size: 12px;\r\n                 height: 25px;\r\n                 padding-left: 5px;\r\n                 padding-right: 5px;\r\n                 vertical-align: middle;\r\n             }\r\n            \r\n            table { page-break-inside:auto; }\r\n            tr    { page-break-inside:avoid; page-break-after:auto }\r\n            thead { display:table-header-group }\r\n            tfoot { display:table-footer-group }     \r\n            \r\n            .bgselected{\r\n                background-color: #CCC !important;\r\n                \r\n            }\r\n            \r\n            .line{\r\n                width: 100%;\r\n                height: 1px;\r\n                border-bottom: 1px solid black;\r\n            }\r\n        </style>\r\n        <script type=\"text/javascript\">\r\n            \r\n            function row_select(object){\r\n                \$(\".bgselected\").removeClass(\"bgselected\");\r\n                \$(object).addClass(\"bgselected\");\r\n            }\r\n            \r\n            \$( document ).ready(function() {\r\n                \r\n                var start = moment();\r\n                var end = moment();\r\n                \r\n                var __start = \"\";\r\n                var __end= \"\";\r\n                ";
if ($_GET["p1"] != "today") {
    $daterange = $_GET["p1"];
    $date_range[0] = NULL;
    $date_range[1] = NULL;
    $date_range_tmp = explode(" - ", $daterange);
    $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
    $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
    $__start = strtotime($date_range[0]);
    $__end = strtotime($date_range[1]);
    echo "                        \r\n                ";
}
echo "                    \r\n                    \r\n                ";
if ($_GET["p1"] != "today") {
    echo "  \r\n                start = moment.unix(";
    echo $__start;
    echo ").format(\"YYYY-MM-DD\");\r\n                end =  moment.unix(";
    echo $__end;
    echo ").format(\"YYYY-MM-DD\");\r\n                ";
}
echo "                //var dateString = moment.unix(1569430304).format(\"YYYY-MM-DD\");\r\n                \r\n       \r\n                        \r\n                \$('#date_filter').daterangepicker({\r\n                    dateLimit:{month:12},\r\n                    startDate: start,\r\n                    endDate: end,\r\n                    locale: {\r\n                        format: 'YYYY-MM-DD'\r\n                    },\r\n                    ranges: {\r\n                        'Today': [moment(), moment()],\r\n                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],\r\n                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],\r\n                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],\r\n                        'This Month': [moment().startOf('month'), moment().endOf('month')],\r\n                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],\r\n                        'Since Creation': [moment().subtract(";
echo $data["start_date_client_days"];
echo ", 'days'), moment()]\r\n\r\n                     }\r\n                });\r\n\r\n                \$(\"#date_filter\").change(function() {\r\n                    refresh_statement();\r\n                    update_dt();\r\n                });\r\n                \r\n                update_dt();\r\n            });\r\n            \r\n            function update_dt(){\r\n                var tmpval = \$(\"#date_filter\").val();\r\n                tmpval = \$(\"#date_filter\").val().split(\" - \");\r\n                \r\n                \$(\"#sdate\").html(tmpval[0]);\r\n                \$(\"#edate\").html(tmpval[1]);\r\n            }\r\n            \r\n            function refresh_statement(){\r\n                window.location.replace(\"index.php?r=printing&f=print_customer_statement__&p0=";
echo $_GET["p0"];
echo "&p1=\"+\$(\"#date_filter\").val());\r\n            }\r\n            \r\n            \r\n        </script>\r\n    </head>\r\n    <body class=\"A4\" z>\r\n        <section class=\"sheet padding-5mm\">\r\n            <input autocomplete=\"off\" id=\"date_filter\" class=\"tohide\" type=\"text\" placeholder=\"\" style=\"cursor:pointer;width:200px;margin-bottom: 5px;\" value=\"\" />\r\n            <select id=\"currency\" onchange=\"refresh_statement()\" class=\"tohide\" style=\"width: 100px; height: 26px; display: none\">\r\n                <option ";
if ($_GET["p1"] == "1") {
    echo "selected";
}
echo " value=\"1\">";
echo $_SESSION["currency_symbol"];
echo "</option>\r\n                <option ";
if ($_GET["p1"] == "2") {
    echo "selected";
}
echo " value=\"2\">LBP</option>\r\n            </select>\r\n            \r\n            \r\n            <table style=\"width: 100%; height: 80px;\" dir=\"rtl\">\r\n                <tr>\r\n                    <td style=\"font-size: 20px;width: 50%;font-weight: bold;padding-left: 5px;\">";
echo $data["settings"]["shop_name"];
echo "<br/>";
echo $data["settings"]["address"];
echo "<br/>";
echo $data["settings"]["phone_nb"];
echo "</td>\r\n                    <td style=\"font-size: 14px;width: 50%;text-align: left\"><b>التاريخ:</b> ";
$datetime = new DateTime();
echo $datetime->format("Y-m-d H:i:s");
echo "</td>\r\n                </tr>\r\n            </table>\r\n            <div class=\"line\"></div>\r\n            \r\n            <table style=\"width: 100%; margin-top: 20px;\" dir=\"rtl\">\r\n                <tr>\r\n                    <td style=\"font-size: 20px;font-weight: bold;text-align: center\">كشف حساب - ";
echo ucfirst($data["customer"][0]["name"]) . " " . ucfirst($data["customer"][0]["middle_name"]) . " " . ucfirst($data["customer"][0]["last_name"]);
echo "</td>\r\n                </tr>\r\n                <!-- <tr>\r\n                    <td style=\"font-size: 17px;font-weight: bold;text-align: center\">Available Balance: ";
echo number_format($data["av_bal"], 2);
echo " </td>\r\n                </tr> -->\r\n                <tr>\r\n                    <td style=\"font-size: 14px;text-align: center\"><b>من:</b> ";
echo $data["start_date"];
echo " - <b>الى:</b> ";
echo $data["end_date"];
echo "</td>\r\n                </tr>\r\n            </table> \r\n            \r\n            <table class=\"det_table\" style=\"width: 100%; margin-top: 5px;margin-bottom: 20px;\" dir=\"rtl\">\r\n                <tr style=\"background-color: #e5e3e3\">\r\n                    <td style=\"width: 80px; text-align: center;font-size: 16px;\"><b>التاريخ</b></td>\r\n                    <td style=\"text-align: center;font-size: 16px;\"><b>المرجع</b></td>\r\n                    <td style=\"width: 75px;text-align: center; font-size: 16px;\"><b> مدين</b></td>\r\n                    <td style=\"width: 75px;text-align: center;font-size: 16px;\"><b>دائن</b></td>\r\n                    <td style=\"width: 90px;text-align: center;font-size: 16px;\"><b>الرصيد</b></td>\r\n                </tr>\r\n                \r\n                \r\n            \r\n               \r\n                ";
$to_debit = 0;
$to_credit = 0;
$balance = 0;
for ($i = 0; $i < count($data["st_of_acc"]); $i++) {
    echo "                <tr onclick=\"row_select(this)\">\r\n                        <td >";
    echo $data["st_of_acc"][$i]["date"];
    echo "</td>\r\n\r\n                        <td style=\"font-size: 14px;\">";
    echo $data["st_of_acc"][$i]["description"];
    echo "</td>\r\n\r\n                        <td>\r\n                            ";
    if ($data["st_of_acc"][$i]["debit"] == "") {
        $data["st_of_acc"][$i]["debit"] = 0;
    }
    $to_debit += $data["st_of_acc"][$i]["debit"];
    if (0 < round($data["st_of_acc"][$i]["debit"], $data["settings"]["round_val"])) {
        echo self::value_format_custom_no_currency($data["st_of_acc"][$i]["debit"], $data["settings"]);
    }
    echo "                        </td>\r\n\r\n                        <td>\r\n                            ";
    if ($data["st_of_acc"][$i]["credit"] == "") {
        $data["st_of_acc"][$i]["credit"] = 0;
    }
    $to_credit += $data["st_of_acc"][$i]["credit"];
    if (0 < round($data["st_of_acc"][$i]["credit"], $data["settings"]["round_val"])) {
        echo self::value_format_custom_no_currency($data["st_of_acc"][$i]["credit"], $data["settings"]);
    }
    echo "                        </td>\r\n\r\n                        <td>";
    $balance = $data["st_of_acc"][$i]["balance"];
    echo self::value_format_custom_no_currency($data["st_of_acc"][$i]["balance"], $data["settings"]);
    echo "</td>\r\n                    </tr>\r\n                    ";
}
echo "                    \r\n                    \r\n                    \r\n    \r\n            </table>\r\n            \r\n            <table class=\"det_table\" style=\"width: 100%; margin-top: 0px;margin-bottom: 40px;\" dir=\"rtl\">\r\n                <tr style=\"\">\r\n                    \r\n                    <td style=\"width: 80px; text-align: center;font-size: 15px;\">&nbsp;</td>\r\n                    <td style=\"text-align: center;font-size: 15px;\"><b>المجموع</b></td>\r\n                    <td style=\"width: 75px;text-align: center; font-size: 15px;\"><b>";
echo number_format($to_debit, 2);
echo "</b></td>\r\n                    <td style=\"width: 75px;text-align: center;font-size: 15px;\"><b>";
echo number_format($to_credit, 2);
echo "</b></td>\r\n                    <td style=\"width: 90px;text-align: center;font-size: 15px;\"><b>";
echo number_format($balance, 2);
echo "</b></td>\r\n\r\n                </tr>\r\n            </table>\r\n        </section>\r\n    </body>\r\n</html>";
function convertNumber($number)
{
    return $number;
}
function convertGroup($index)
{
    switch ($index) {
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
    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
        return "";
    }
    if ($digit1 != "0") {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0") {
            $buffer .= " and ";
        }
    }
    if ($digit2 != "0") {
        $buffer .= convertTwoDigit($digit2, $digit3);
    } else {
        if ($digit3 != "0") {
            $buffer .= convertDigit($digit3);
        }
    }
    return $buffer;
}
function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0") {
        switch ($digit1) {
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
    } else {
        if ($digit1 == "1") {
            switch ($digit2) {
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
        } else {
            $temp = convertDigit($digit2);
            switch ($digit1) {
                case "2":
                    return "twenty-" . $temp;
                case "3":
                    return "thirty-" . $temp;
                case "4":
                    return "forty-" . $temp;
                case "5":
                    return "fifty-" . $temp;
                case "6":
                    return "sixty-" . $temp;
                case "7":
                    return "seventy-" . $temp;
                case "8":
                    return "eighty-" . $temp;
                case "9":
                    return "ninety-" . $temp;
            }
        }
    }
}
function convertDigit($digit)
{
    switch ($digit) {
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