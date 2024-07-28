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
echo "<!DOCTYPE html>\n<html>\n\n<head>\n    <title></title>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\n\n    <style>\n        body {\n            font-family: Arial, Helvetica, sans-serif;\n        }\n\n        table {\n            /* border: 1px solid black; */\n            border-collapse: collapse;\n            width: 100%;\n            /* margin-top: 20px; */\n        }\n\n        tr {\n            border: 0px solid black;\n            border-top: 1px solid #E4E8EB;\n            border-bottom: 1px solid #E4E8EB;\n\n\n        }\n\n        .quest_grp_name {\n            /* margin-top:20px; */\n            margin: 0px !important;\n            background-color: #EBEFF2;\n            padding: 5px;\n            padding-top: 20px;\n            padding-bottom: 20px;\n            text-transform: uppercase !important;\n\n\n        }\n\n        .table-col-1 {\n            width: 60%;\n            padding-left: 5px;\n        }\n\n        .tr-bg {\n            background-color: gray !important;\n        }\n        \n        .tr-bg td {\n            color: #ffffff;\n        }\n\n        .info_table,\n        .info_table tr,\n        .info_table td {\n            border: 1px solid gray;\n\n        }\n\n        .total_table tr {\n            border: 0px solid gray;\n\n        }\n\n        .total_table {\n            border: 1px solid gray;\n\n        }\n\n        .total_table .top_border {\n            border: 1px solid gray;\n\n        }\n\n        .pl-5 {\n            padding-left: 5px;\n        }\n\n        .pb-0 {\n            padding-bottom: 0px;\n        }\n\n\n        .mt-1 {\n            margin-top: 1px;\n        }\n\n        .m-0 {\n            margin: 0px;\n        }\n    </style>\n</head>\n\n<body>\n\n    ";
$store_name = $data["store_info"];
$invoice_items = $data["invoice_items"];
$client_info = $data["client_info"];
$invoice_data = $data["invoice_data"];
echo "\n\n    <div>\n        <div style=\"width:40%;float:left;\">\n            <h3 class=\"m-0\">";
echo $store_name["name"];
echo "</h3>\n            <p class=\"m-0 mt-1\">";
echo $store_name["location"];
echo "</p>\n            <p class=\"m-0 mt-1\">";
echo $store_name["address"];
echo "</p>\n            <p class=\"m-0 mt-1\">";
echo $store_name["phone"];
echo "</p>\n        </div>\n        <div style=\"width:30%;float:left;\">\n            <h2>HST#: ";
echo $data["vat_nb"];
echo "</h2>\n        </div>\n        <div style=\"width:30%;float:right;text-align: right\">\n            <h3 class=\"m-0\">INVOICE</h3>\n            <p class=\"m-0 mt-1\"><span>Number: </span>";
echo $invoice_data["invoice_nb"];
echo "</p>\n            <p class=\"m-0 mt-1\"><span>Date: </span>";
echo $invoice_data["date"];
echo "</p>\n        </div>\n    </div>\n\n    <div>\n        <hr>\n    </div>\n    <div>\n        <div style=\"width:45%;float:left;\">\n            <p class=\"m-0 mt-1\">q";
echo $client_info["company"];
echo "</p>\n            <p class=\"m-0 mt-1\">";
echo $client_info["name"];
echo "</p>\n            <p class=\"m-0 mt-1\">";
echo $client_info["phone"];
echo "</p>\n            <p class=\"m-0 mt-1\">";
echo $client_info["email"];
echo "</p>\n        </div>\n        <div style=\"width:45%;float:right;\">\n            <p class=\"m-0\"><span>Ship To: </span></p>\n            <p class=\"m-0\">";
echo str_replace("#", "<br/>", $client_info["address"]);
echo "</p>\n        </div>\n    </div>\n    <div>\n        <hr>\n    </div>\n    <div>\n\n        <div style=\"width:100%;\">\n\n            <table style=\"width:100%; \" class=\"info_table\">\n                <!-- <tr>\n                    <td rowspan=\"2\" class=\" pl-5\">DUE DATE <span> ";
echo $invoice_data["date"];
echo "</span></td>\n                    <td rowspan=\"2\" class=\"pl-5\">REFERENCE</td>\n                    <td class=\"pl-5\" style=\"\">SHIP BY ";
echo $store_name["employee_name"];
echo " </td>\n                    <td rowspan=\"2\" colspan=\"2\" class=\"pl-5\">TELEPHONE <br> ";
echo $store_name["phone"];
echo "</td>\n\n\n                </tr>\n                <tr>\n                    <td class=\"pl-5\">NOTES</td>\n\n                </tr>-->\n\n                <tr class=\"tr-bg\">\n                    <td class=\"pl-5\">CODE</td>\n                    <td class=\"pl-5\">DESCRIPTION</td>\n                    <td class=\"pl-5\" style=\"text-align:right;\">QUANTITY </td>\n                    <td class=\"pl-5\" style=\"width:20px;text-align:right;\">PRICE</td>\n                    <td class=\"pl-5\" style=\"width:20px;text-align:right;\">SUBTOTAL</td>\n\n                </tr>\n\n                ";
for ($i = 0; $i < count($invoice_items); $i++) {
    echo "                    <tr>\n\n                        <td class=\"pl-5\" style=\"width: 100px;\">";
    echo $invoice_items[$i]["id"];
    echo "</td>\n                        <td class=\"pl-5\">";
    echo $invoice_items[$i]["name"];
    echo "</td>\n                        <td class=\"pl-5\" style=\"text-align:right;width: 100px;\">";
    echo $invoice_items[$i]["quantity"];
    echo " </td>\n                        <td class=\"pl-5\" style=\"text-align:right;width: 100px;\">";
    echo $invoice_items[$i]["price"];
    echo "</td>\n                        <td class=\"pl-5\" style=\"text-align:right;width: 100px;\">";
    echo $invoice_items[$i]["subtotal"];
    echo "</td>\n                    </tr>\n                ";
}
echo "\n            </table>\n\n        </div>\n\n\n\n    </div>\n\n    <div style=\"width:60%;float:left;margin-top:10px;\">\n        <h3 style=\"text-align:center;margin-top:50px;\">THANK YOU !</h3>\n    </div>\n    <div style=\"width:40%;float:right;margin-top:10px;\">\n\n        <table style=\"width:100%\" class=\"total_table\">\n\n            <tr>\n                <td class=\"pl-5\" style=\"text-align:left;\">SUB-TOTAL </td>\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo $invoice_data["subtotal"];
echo "</td>\n            </tr>\n\n\n            <tr>\n                <td class=\"pl-5\" style=\"text-align:left;\">DISCOUNT </td>\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo number_format(abs($invoice_data["discount"]) / $invoice_data["subtotal"] * 100, 2) . "%";
echo "</td>\n            </tr>\n            \n            <tr>\n                <td class=\"pl-5\" style=\"text-align:left;\">TAX </td>\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo $invoice_data["tax"];
echo " %</td>\n            </tr>\n\n            <tr>\n                <td class=\"pl-5\" style=\"text-align:left;\">FREIGHT </td>\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo $invoice_data["freight"];
echo "</td>\n            </tr>\n\n            \n\n            <tr class=\"top_border\">\n                <td class=\"pl-5\" style=\"text-align:left;\">TOTAL </td>\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo $invoice_data["total"];
echo "</td>\n            </tr>\n\n\n\n            <tr>\n            </tr>\n\n\n\n        </table>\n\n    </div>\n\n\n\n    </div>\n\n</body>\n\n</html>";

?>