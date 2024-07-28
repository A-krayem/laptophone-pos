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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n<html>\r\n    <head>\r\n        <title>UPSILON - Print Sheet</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <style type=\"text/css\" media=\"print\">\r\n             @page { size: landscape; }\r\n             \r\n             body,document{\r\n                 padding: 5px;\r\n                 margin: 5px;\r\n             }\r\n             .sheet_table{\r\n                 width: 100%;\r\n             }\r\n             \r\n             .sheet_table tr td{\r\n                 border: 1px solid #000 !important;\r\n                 font-size: 13px;\r\n                 height: 25px;\r\n                 padding-left: 5px;\r\n                 padding-right: 5px;\r\n                 vertical-align: middle;\r\n             }\r\n        </style>\r\n        <script type=\"text/javascript\">\r\n            \r\n            \$(document).ready(function () {\r\n                \r\n                \$(\"#wassilli_img\").one('load', function() {\r\n                    window.print(); \r\n                    window.close();\r\n                   /* window.onafterprint = function(){\r\n                        alert(\"dsadas\");\r\n                        \$.getJSON(\"?r=delivery_items&f=update_print&p0=";
echo $data["delivery_info"][0]["id"];
echo "\", function (data) {\r\n                        }).done(function () {\r\n                           \r\n                        });\r\n                    };*/\r\n                }).attr('src', \"resources/overspeed.jpg\");\r\n                \r\n            });\r\n        </script>\r\n    </head>\r\n    <body>\r\n        <img src=\"resources/overspeed.jpg\" id=\"wassilli_img\" style=\"width: 130px; height: 75px; margin-bottom: 10px;\" />\r\n        <table class=\"sheet_table\">\r\n            <tr>\r\n                <td colspan=\"9\" style=\"text-align: center\"><b>Supplier name:</b> ";
echo $data["supplier"][$data["delivery_info"][0]["supplier_id"]]["name"];
echo "</td>\r\n            </tr>\r\n            <tr>\r\n                <td colspan=\"2\" ><b>Address:</b> ";
if (isset($data["supplier"][$data["delivery_info"][0]["supplier_id"]])) {
    echo $data["supplier"][$data["delivery_info"][0]["supplier_id"]]["address"];
}
echo "</td>\r\n                <td colspan=\"3\" ><b>Contact:</b> ";
if (isset($data["supplier"][$data["delivery_info"][0]["supplier_id"]])) {
    echo $data["supplier"][$data["delivery_info"][0]["supplier_id"]]["contact_name"];
}
echo "</td>\r\n                <td colspan=\"4\" ><b>Tel:</b> ";
if (isset($data["supplier_phone"][0]["phone_number"])) {
    echo $data["supplier_phone"][0]["phone_number"];
}
echo "</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"width: 100px\"><b>Send To</b></td>\r\n                <td><b>Address</b></td>\r\n                <td style=\"width: 100px\"><b>Tel</b></td>\r\n                <td style=\"width: 100px\"><b>Sending date</b></td>\r\n                <td style=\"width: 90px\"><b>WB number</b></td>\r\n                <td style=\"width: 100px;\"><b>Collection</b></td>\r\n                <td style=\"width: 130px\"><b>Delivery charge</b></td>\r\n                <td style=\"width: 100px\"><b>Net amount</b></td>\r\n                <td style=\"width: 100px\"><b>Note</b></td>\r\n            </tr>\r\n            ";
$total_collections = 0;
$total_delivery = 0;
$total_net_amount = 0;
for ($i = 0; $i < count($data["delivery_packages"]); $i++) {
    $total_collections += $data["delivery_packages"][$i]["collection_value"];
    $total_delivery += $data["delivery_packages"][$i]["delivery_charge"];
    $total_net_amount += $data["delivery_packages"][$i]["net_amout"];
    echo "            <tr>\r\n                <!--\r\n                <td>";
    if (0 < $data["delivery_packages"][0]["customer_id"]) {
        echo $data["customers"][$data["delivery_packages"][0]["customer_id"]]["name"];
    }
    echo "</td>\r\n                <td>";
    if (0 < $data["delivery_packages"][0]["customer_id"]) {
        echo $data["customers"][$data["delivery_packages"][0]["customer_id"]]["address"];
    }
    echo "</td>\r\n                <td>";
    if (0 < $data["delivery_packages"][0]["customer_id"]) {
        echo $data["customers"][$data["delivery_packages"][0]["customer_id"]]["phone"];
    }
    echo "</td>\r\n                -->\r\n                <td>";
    echo $data["delivery_packages"][$i]["customer_name"];
    echo "</td>\r\n                <td>";
    echo $data["delivery_packages"][$i]["customer_address"];
    echo "</td>\r\n                <td>";
    echo $data["delivery_packages"][$i]["customer_phone"];
    echo "</td>\r\n                \r\n                <td>";
    $dt = explode(" ", $data["delivery_packages"][$i]["sending_date"]);
    echo $dt[0];
    echo "</td>\r\n                <td>";
    echo $data["delivery_packages"][$i]["wb_number"];
    echo "</td>\r\n                <td style=\"text-align: right\">";
    echo number_format($data["delivery_packages"][$i]["collection_value"], $data["number_of_decimal_points"]) . " " . $data["currency"];
    echo "</td>\r\n                <td style=\"text-align: right\">";
    echo number_format($data["delivery_packages"][$i]["delivery_charge"], $data["number_of_decimal_points"]) . " " . $data["currency"];
    echo "</td>\r\n                <td style=\"text-align: right\">";
    echo number_format($data["delivery_packages"][$i]["net_amout"], $data["number_of_decimal_points"]) . " " . $data["currency"];
    echo "</td>\r\n                <td style=\"text-align: left\">";
    echo $data["delivery_packages"][$i]["note"];
    echo "</td>\r\n\r\n            </tr>\r\n            ";
}
echo "            <tr>\r\n                <td colspan=\"5\" style=\"text-align: center\"><b>Total</b></td>\r\n                <td style=\"text-align: right\">";
echo number_format($total_collections, $data["number_of_decimal_points"]) . " " . $data["currency"];
echo "</td>\r\n                <td style=\"text-align: right\">";
echo number_format($total_delivery, $data["number_of_decimal_points"]) . " " . $data["currency"];
echo "</td>\r\n                <td style=\"text-align: right\">";
echo number_format($total_net_amount, $data["number_of_decimal_points"]) . " " . $data["currency"];
echo "</td>\r\n                <td style=\"text-align: right\">&nbsp;</td>\r\n\r\n            </tr>\r\n        </table>\r\n    </body>\r\n</html>\r\n\r\n";

?>