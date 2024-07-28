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
echo "<!DOCTYPE HTML>\r\n<html>\r\n    <head>\r\n        <title>Transfer Details</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <title>Test</title>\r\n        <style type=\"text/css\">\r\n\r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n\r\n            .sheet {\r\n                margin: 0;\r\n                overflow: hidden;\r\n                position: relative;\r\n                box-sizing: border-box;\r\n                page-break-after: always;\r\n            }\r\n\r\n            body.A4               .sheet { width: 210mm;  } /*height: 296mm*/\r\n\r\n            /** Padding area **/\r\n            .sheet.padding-10mm { padding: 10mm }\r\n            .sheet.padding-15mm { padding: 15mm }\r\n            .sheet.padding-20mm { padding: 20mm }\r\n            .sheet.padding-25mm { padding: 25mm }\r\n\r\n            /** For screen preview **/\r\n            @media screen {\r\n                body { background: #e0e0e0 }\r\n                .sheet {\r\n                    background: white;\r\n                    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\r\n                    margin: 5mm auto;\r\n                }\r\n            }\r\n\r\n            /** Fix for Chrome issue #273306 **/\r\n            @media print {\r\n                body.A4 { width: 210mm }\r\n            }\r\n\r\n            .tdstyle{\r\n                border: 1px solid #000 !important;\r\n                padding-top: 2px !important;\r\n                padding-bottom:  2px !important;\r\n                padding-left:  2px !important;\r\n                padding-right:  2px !important;\r\n            }\r\n\r\n            .line{\r\n                width: 100%;\r\n                height: 1px;\r\n                border-bottom: 1px solid black;\r\n            }\r\n\r\n            table { page-break-inside:auto }\r\n            tr    { page-break-inside:avoid; page-break-after:auto }\r\n            thead { display:table-header-group }\r\n            tfoot { display:table-footer-group }\r\n        </style>\r\n    </head>\r\n    <body class=\"A4\">\r\n        <section class=\"sheet padding-10mm\">\r\n            \r\n            ";
if ($data["available"] == 0) {
    echo "            <table style=\"width: 100%; height: 60px;\">\r\n                <tr>\r\n                    <td style=\"font-size: 18px;width: 50%;font-weight: bold;padding-left: 5px;\">NO ITEMS AVAILABLE</td>\r\n                </tr>\r\n            </table>\r\n            ";
    exit;
}
echo "\r\n            <table style=\"width: 100%; height: 60px;\">\r\n                <tr>\r\n                    <td style=\"font-size: 18px;width: 50%;font-weight: bold;padding-left: 5px;\">";
echo $data["shop_name"];
echo "</td>\r\n                    <td style=\"font-size: 14px;width: 50%;text-align: right\"><b>Date:</b> ";
$datetime = new DateTime();
echo $datetime->format("Y-m-d H:i:s");
echo "</td>\r\n                </tr>\r\n            </table>\r\n\r\n            <div class=\"line\"></div>\r\n\r\n            <table style=\"width: 100%; margin-top: 20px;\">\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"font-size: 20px;font-weight: bold;text-align: center\">TRANSFER #";
echo $data["transfer_id"];
echo "</td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"font-size: 15px;text-align: center\">Transfer Date: ";
echo $data["creation_date"];
echo "</td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"font-size: 15px;text-align: center\">Created By: ";
echo $data["created_by"];
echo "</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-size: 14px;text-align: center; padding-top: 5px;\"><b>Transfer From:</b> ";
echo $data["from_store"];
echo " </td>\r\n                    <td style=\"font-size: 14px;text-align: center;padding-top: 5px\"><b>Transfer  To:</b> ";
echo $data["to_store"];
echo " </td>\r\n                </tr>\r\n            </table> \r\n\r\n            ";
if ($data["print_group"] == 1) {
    $array_group_printed = array();
    for ($i = 0; $i < count($data["transfer_details"]); $i++) {
        if (!in_array($data["transfer_details"][$i]["item_group"], $array_group_printed)) {
            for ($k = 0; $k < count($data["transfer_details"]); $k++) {
                if ($data["transfer_details"][$k]["item_group"] == $data["transfer_details"][$i]["item_group"]) {
                    if (!in_array($data["transfer_details"][$i]["item_group"], $array_group_printed)) {
                        array_push($array_group_printed, $data["transfer_details"][$i]["item_group"]);
                        echo "<b>" . $data["transfer_details"][$k]["item_name"] . "</b><br/>";
                    }
                    echo $data["transfer_details"][$k]["color_name"] . "/" . $data["transfer_details"][$k]["size_name"] . "/" . floatval($data["transfer_details"][$k]["qty"]) . " <b>#</b> ";
                }
            }
            echo "<br/><br/>";
        }
    }
} else {
    echo "            \r\n            <table style=\"width: 100%; margin-top: 0px;\">\r\n                <thead >\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </thead>\r\n                <tfoot>\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </tfoot>\r\n                <tbody>\r\n                    <tr style=\"height: 30px;\">\r\n                        <td class=\"tdstyle\" style=\"width: 85px;\"><b>Item ID</b></td>\r\n                        <td class=\"tdstyle\"><b>Description</b></td>\r\n                        \r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: left\"><b>Color</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: left\"><b>Size</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: center\"><b>Transfer Qty</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: center\"><b>Unit Price</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: center\"><b>Total Price</b></td>\r\n                    </tr>\r\n                    ";
    $tqty = 0;
    $tprice = 0;
    for ($i = 0; $i < count($data["transfer_details"]); $i++) {
        $tqty += $data["transfer_details"][$i]["qty"];
        $tprice += $data["transfer_details"][$i]["selling_price"] * $data["transfer_details"][$i]["qty"];
        echo "                    <tr style=\"height: 30px;\">\r\n                        <td class=\"tdstyle\" style=\"width: 85px;\">";
        echo $data["transfer_details"][$i]["item_id"];
        echo "</td>\r\n                        <td class=\"tdstyle\">";
        echo $data["transfer_details"][$i]["item_name"];
        echo "</td>\r\n                        \r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: left\">";
        echo $data["transfer_details"][$i]["color_name"];
        echo "</td>\r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: left\">";
        echo $data["transfer_details"][$i]["size_name"];
        echo "</td>\r\n                        <td class=\"tdstyle\" style=\"width: 45px; text-align: center\">";
        echo floatval($data["transfer_details"][$i]["qty"]);
        echo "</td>\r\n                        <td class=\"tdstyle\" style=\"width: 45px; text-align: center\">";
        echo floatval($data["transfer_details"][$i]["selling_price"]);
        echo "</td>\r\n                        <td class=\"tdstyle\" style=\"width: 45px; text-align: center\">";
        echo floatval($data["transfer_details"][$i]["selling_price"] * $data["transfer_details"][$i]["qty"]);
        echo "</td>\r\n                    </tr>\r\n                    ";
    }
    echo "                </tbody>\r\n            </table>\r\n            \r\n            <table style=\"width: 40%; margin-top: 0px; float: right\">\r\n                <thead >\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </thead>\r\n                <tfoot>\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </tfoot>\r\n                <tbody>\r\n                    <tr style=\"height: 30px;\">\r\n                        <td class=\"tdstyle\" style=\"width: 120px; text-align: center\"><b>Total Quantity</b></td>\r\n                        <td class=\"tdstyle\" style=\"text-align: center\"><b>Total Price</b></td>\r\n                    </tr>\r\n                    <tr style=\"height: 30px;\">\r\n                        <td class=\"tdstyle\" style=\"text-align: center\"><b>";
    echo number_format(floatval($tqty), 0);
    echo "</b></td>\r\n                        <td class=\"tdstyle\" style=\"text-align: center\"><b>";
    echo number_format(floatval($tprice), 0);
    echo "</b></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n                ";
}
echo "        </section>\r\n    </body>\r\n</html>\r\n";

?>