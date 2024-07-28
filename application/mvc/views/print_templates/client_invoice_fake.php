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
echo "<!DOCTYPE html>\r\n<html>\r\n\r\n<head>\r\n    <title></title>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n\r\n    <style>\r\n        body {\r\n            font-family: Arial, Helvetica, sans-serif;\r\n        }\r\n\r\n        table {\r\n            /* border: 1px solid black; */\r\n            border-collapse: collapse;\r\n            width: 100%;\r\n            /* margin-top: 20px; */\r\n        }\r\n\r\n        tr {\r\n            border: 0px solid black;\r\n            border-top: 1px solid #E4E8EB;\r\n            border-bottom: 1px solid #E4E8EB;\r\n\r\n\r\n        }\r\n\r\n        .quest_grp_name {\r\n            /* margin-top:20px; */\r\n            margin: 0px !important;\r\n            background-color: #EBEFF2;\r\n            padding: 5px;\r\n            padding-top: 20px;\r\n            padding-bottom: 20px;\r\n            text-transform: uppercase !important;\r\n\r\n\r\n        }\r\n\r\n        .table-col-1 {\r\n            width: 60%;\r\n            padding-left: 5px;\r\n        }\r\n\r\n        .tr-bg {\r\n            background-color: gray !important;\r\n        }\r\n        \r\n        .tr-bg td {\r\n            color: #ffffff;\r\n        }\r\n\r\n        .info_table,\r\n        .info_table tr,\r\n        .info_table td {\r\n            border: 1px solid gray;\r\n\r\n        }\r\n\r\n        .total_table tr {\r\n            border: 0px solid gray;\r\n\r\n        }\r\n\r\n        .total_table {\r\n            border: 1px solid gray;\r\n\r\n        }\r\n\r\n        .total_table .top_border {\r\n            border: 1px solid gray;\r\n\r\n        }\r\n\r\n        .pl-5 {\r\n            padding-left: 5px;\r\n        }\r\n\r\n        .pb-0 {\r\n            padding-bottom: 0px;\r\n        }\r\n\r\n\r\n        .mt-1 {\r\n            margin-top: 1px;\r\n        }\r\n\r\n        .m-0 {\r\n            margin: 0px;\r\n        }\r\n    </style>\r\n</head>\r\n\r\n<body>\r\n\r\n    ";
$store_name = $data["store_info"];
$invoice_items = $data["invoice_items"];
$client_info = $data["client_info"];
$invoice_data = $data["invoice_data"];
echo "\r\n\r\n    <div>\r\n        <div style=\"width:40%;float:left;\">\r\n            <h3 class=\"m-0\">";
echo $store_name["name"];
echo "</h3>\r\n            <p class=\"m-0 mt-1\">";
echo $store_name["location"];
echo "</p>\r\n            <p class=\"m-0 mt-1\">";
echo $store_name["address"];
echo "</p>\r\n            <p class=\"m-0 mt-1\">";
echo $store_name["phone"];
echo "</p>\r\n        </div>\r\n\r\n        <div style=\"width:30%;float:right;text-align: right\">\r\n            <h3 class=\"m-0\">QUOTATION</h3>\r\n            <p class=\"m-0 mt-1\"><span>Number: </span>";
echo $invoice_data["invoice_nb"];
echo "</p>\r\n            <p class=\"m-0 mt-1\"><span>Date: </span>";
echo $invoice_data["date"];
echo "</p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <hr>\r\n    </div>\r\n    <div>\r\n        <div style=\"width:45%;float:left;\">\r\n            <p class=\"m-0 mt-1\">q";
echo $client_info["company"];
echo "</p>\r\n            <p class=\"m-0 mt-1\">";
echo $client_info["name"];
echo "</p>\r\n            <p class=\"m-0 mt-1\">";
echo $client_info["phone"];
echo "</p>\r\n            <p class=\"m-0 mt-1\">";
echo $client_info["email"];
echo "</p>\r\n        </div>\r\n        <div style=\"width:45%;float:right;\">\r\n            <p class=\"m-0\"><span>Ship To: </span></p>\r\n            <p class=\"m-0\">";
echo str_replace("#", "<br/>", $client_info["address"]);
echo "</p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <hr>\r\n    </div>\r\n    <div>\r\n\r\n        <div style=\"width:100%;\">\r\n\r\n            <table style=\"width:100%; \" class=\"info_table\">\r\n                <!-- <tr>\r\n                    <td rowspan=\"2\" class=\" pl-5\">DUE DATE <span> ";
echo $invoice_data["date"];
echo "</span></td>\r\n                    <td rowspan=\"2\" class=\"pl-5\">REFERENCE</td>\r\n                    <td class=\"pl-5\" style=\"\">SHIP BY ";
echo $store_name["employee_name"];
echo " </td>\r\n                    <td rowspan=\"2\" colspan=\"2\" class=\"pl-5\">TELEPHONE <br> ";
echo $store_name["phone"];
echo "</td>\r\n\r\n\r\n                </tr>\r\n                <tr>\r\n                    <td class=\"pl-5\">NOTES</td>\r\n\r\n                </tr>-->\r\n\r\n                <tr class=\"tr-bg\">\r\n                    <td class=\"pl-5\">CODE</td>\r\n                    <td class=\"pl-5\">DESCRIPTION</td>\r\n                    <td class=\"pl-5\" style=\"text-align:right;\">QUANTITY </td>\r\n                    <td class=\"pl-5\" style=\"width:20px;text-align:right;\">PRICE</td>\r\n                    <td class=\"pl-5\" style=\"width:20px;text-align:right;\">SUBTOTAL</td>\r\n\r\n                </tr>\r\n\r\n                ";
for ($i = 0; $i < count($invoice_items); $i++) {
    echo "                    <tr>\r\n\r\n                        <td class=\"pl-5\" style=\"width: 100px;\">";
    echo $invoice_items[$i]["id"];
    echo "</td>\r\n                        <td class=\"pl-5\">";
    echo $invoice_items[$i]["name"];
    echo "</td>\r\n                        <td class=\"pl-5\" style=\"text-align:right;width: 100px;\">";
    echo $invoice_items[$i]["quantity"];
    echo " </td>\r\n                        <td class=\"pl-5\" style=\"text-align:right;width: 100px;\">";
    echo $invoice_items[$i]["price"];
    echo "</td>\r\n                        <td class=\"pl-5\" style=\"text-align:right;width: 100px;\">";
    echo $invoice_items[$i]["subtotal"];
    echo "</td>\r\n                    </tr>\r\n                ";
}
echo "\r\n            </table>\r\n\r\n        </div>\r\n\r\n\r\n\r\n    </div>\r\n\r\n    <div style=\"width:60%;float:left;margin-top:10px;\">\r\n        <h3 style=\"text-align:center;margin-top:50px;\">THANK YOU !</h3>\r\n    </div>\r\n    <div style=\"width:40%;float:right;margin-top:10px;\">\r\n\r\n        <table style=\"width:100%\" class=\"total_table\">\r\n\r\n            <tr>\r\n                <td class=\"pl-5\" style=\"text-align:left;\">SUB-TOTAL </td>\r\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo $invoice_data["subtotal"];
echo "</td>\r\n            </tr>\r\n\r\n\r\n            <tr>\r\n                <td class=\"pl-5\" style=\"text-align:left;\">DISCOUNT </td>\r\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo number_format(abs($invoice_data["discount"]) / $invoice_data["subtotal"] * 100, 2) . "%";
echo "</td>\r\n            </tr>\r\n            \r\n     \r\n\r\n            <tr>\r\n                <td class=\"pl-5\" style=\"text-align:left;\">FREIGHT </td>\r\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo $invoice_data["freight"];
echo "</td>\r\n            </tr>\r\n\r\n            \r\n\r\n            <tr class=\"top_border\">\r\n                <td class=\"pl-5\" style=\"text-align:left;\">TOTAL </td>\r\n                <td class=\"pl-5\" style=\"text-align:right;\">";
echo $invoice_data["total_without_tax"];
echo "</td>\r\n            </tr>\r\n\r\n\r\n\r\n            <tr>\r\n            </tr>\r\n\r\n\r\n\r\n        </table>\r\n\r\n    </div>\r\n\r\n\r\n\r\n    </div>\r\n\r\n</body>\r\n\r\n</html>";

?>