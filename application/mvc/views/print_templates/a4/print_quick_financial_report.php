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
echo "<!DOCTYPE HTML>\r\n<html>\r\n    <head>\r\n        <title>Print Financial dashboard</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <title>Test</title>\r\n        <style type=\"text/css\">\r\n\r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n\r\n            .sheet {\r\n                margin: 0;\r\n                overflow: hidden;\r\n                position: relative;\r\n                box-sizing: border-box;\r\n                page-break-after: always;\r\n            }\r\n\r\n            body.A4               .sheet { width: 210mm;  } /*height: 296mm*/\r\n\r\n            /** Padding area **/\r\n            .sheet.padding-10mm { padding: 2mm }\r\n            .sheet.padding-15mm { padding: 2mm }\r\n            .sheet.padding-20mm { padding: 2mm }\r\n            .sheet.padding-25mm { padding: 2mm }\r\n\r\n            /** For screen preview **/\r\n            @media screen {\r\n                body { background: #e0e0e0 }\r\n                .sheet {\r\n                    background: white;\r\n                    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\r\n                    margin: 5mm auto;\r\n                }\r\n            }\r\n\r\n            /** Fix for Chrome issue #273306 **/\r\n            @media print {\r\n                body.A4 { width: 210mm }\r\n            }\r\n\r\n            .tdstyle{\r\n                border: 1px solid #000 !important;\r\n                padding-top: 2px !important;\r\n                padding-bottom:  2px !important;\r\n                padding-left:  2px !important;\r\n                padding-right:  2px !important;\r\n            }\r\n\r\n            .line{\r\n                width: 100%;\r\n                height: 1px;\r\n                border-bottom: 1px solid black;\r\n            }\r\n\r\n            table { page-break-inside:auto }\r\n            tr    { page-break-inside:avoid; page-break-after:auto }\r\n            thead { display:table-header-group }\r\n            tfoot { display:table-footer-group }\r\n        </style>\r\n    </head>\r\n    <body class=\"A4\">\r\n        <section class=\"sheet padding-10mm\">\r\n\r\n            <table style=\"width: 100%; height: 60px;\">\r\n                <tr>\r\n                    <td style=\"font-size: 20px;width: 50%;font-weight: bold;padding-left: 5px;padding-top: 0px; padding-bottom: 0px;\">";
echo $data["settings"]["shop_name"];
echo " - ";
echo $data["settings"]["address"];
echo "</td>\r\n                </tr>\r\n                <tr style=\"height: 22px;\">\r\n                    <td style=\"font-size: 12px;width: 50%;padding-left: 5px;padding-top: 0px; padding-bottom: 0px;\">From: ";
echo $data["date_from"];
echo " To ";
echo $data["date_to"];
echo "</td>\r\n                </tr>\r\n            </table>\r\n\r\n            <div class=\"line\"></div>\r\n\r\n            <table style=\"width: 100%; margin-top: 10px; font-size: 12px;\">\r\n                <thead >\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </thead>\r\n                <tfoot>\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </tfoot>\r\n                <tbody>\r\n                    <tr style=\"height: 30px;\">\r\n                        <td class=\"tdstyle\" style=\"width: 90px;\"><b>Date</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 80px;\"><b>Type</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 80px;\"><b>Qty In</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 80px;\"><b>Qty Out</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 120px;\"><b>Qty Variation</b></td>\r\n                        <td class=\"tdstyle\" style=\"; text-align: left;\"><b>Cash Inflows</b></td>\r\n                        <td class=\"tdstyle\" style=\"; text-align: left;\"><b>Cash Outflows</b></td>\r\n                        <td class=\"tdstyle\" style=\"; text-align: left;\"><b>Net</b></td>\r\n                    </tr>\r\n                    ";
$qty_var = 0;
$net = 0;
$total_invoices_amount = 0;
for ($i = 0; $i < count($data["details"]); $i++) {
    $creation_date = explode(" ", $data["details"][$i]["creation_date"]);
    $qty_var += $data["details"][$i]["qin"];
    $qty_var -= $data["details"][$i]["qout"];
    $net += $data["details"][$i]["total_amount_in"];
    $net -= $data["details"][$i]["total_amount_out"];
    if (0 < $data["details"][$i]["qin"]) {
        $qty_var = $data["details"][$i]["qin"];
        $net = 0 - $data["details"][$i]["total_amount_out"];
    }
    $total_invoices_amount += $data["details"][$i]["total_amount_in"];
    echo "                        <tr>\r\n                            <td class=\"tdstyle\">";
    echo $creation_date[0];
    echo "</td>\r\n                            <td class=\"tdstyle\">";
    echo $data["details"][$i]["desc"];
    echo "</td>\r\n                            <td class=\"tdstyle\" >";
    if ($data["details"][$i]["qin"] == 0) {
        echo "";
    } else {
        echo number_format(floatval($data["details"][$i]["qin"]), 2);
    }
    echo "</td>\r\n                            <td class=\"tdstyle\">";
    if ($data["details"][$i]["qout"] == 0) {
        echo "";
    } else {
        echo number_format(floatval($data["details"][$i]["qout"]), 2);
    }
    echo "</td>\r\n                            <td class=\"tdstyle\">";
    echo number_format($qty_var, 2);
    echo "</td>  \r\n                            <td class=\"tdstyle\">";
    if (0 < $data["details"][$i]["total_amount_in"]) {
        echo number_format($data["details"][$i]["total_amount_in"], 2);
    } else {
        echo "";
    }
    echo "</td>                    \r\n                            <td class=\"tdstyle\">";
    if (0 < $data["details"][$i]["total_amount_out"]) {
        echo number_format($data["details"][$i]["total_amount_out"], 2);
    } else {
        echo "";
    }
    echo "</td>                    \r\n                            <td class=\"tdstyle\">";
    echo number_format($net, 2);
    echo "</td>                    \r\n                        </tr>       \r\n                    ";
}
echo " \r\n                        <tr>\r\n                            <td class=\"tdstyle\"></td>\r\n                            <td class=\"tdstyle\"></td>\r\n                            <td class=\"tdstyle\" ></td>\r\n                            <td class=\"tdstyle\"></td>\r\n                            <td class=\"tdstyle\"></td>  \r\n                            <td class=\"tdstyle\"><b>Total</b><br/>";
echo number_format($total_invoices_amount, 2);
echo "</td>                    \r\n                            <td class=\"tdstyle\"></td>                    \r\n                            <td class=\"tdstyle\"></td>                    \r\n                        </tr>   \r\n                </tbody>\r\n            </table>\r\n        </section>\r\n    </body>\r\n</html>\r\n";

?>