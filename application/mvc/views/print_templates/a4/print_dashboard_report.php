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
echo "<!DOCTYPE HTML>\r\n<html>\r\n    <head>\r\n        <title>Print dashboard</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <title>Test</title>\r\n        <style type=\"text/css\">\r\n\r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n\r\n            .sheet {\r\n                margin: 0;\r\n                overflow: hidden;\r\n                position: relative;\r\n                box-sizing: border-box;\r\n                page-break-after: always;\r\n            }\r\n\r\n            body.A4               .sheet { width: 210mm;  } /*height: 296mm*/\r\n\r\n            /** Padding area **/\r\n            .sheet.padding-10mm { padding: 10mm }\r\n            .sheet.padding-15mm { padding: 15mm }\r\n            .sheet.padding-20mm { padding: 20mm }\r\n            .sheet.padding-25mm { padding: 25mm }\r\n\r\n            /** For screen preview **/\r\n            @media screen {\r\n                body { background: #e0e0e0 }\r\n                .sheet {\r\n                    background: white;\r\n                    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\r\n                    margin: 5mm auto;\r\n                }\r\n            }\r\n\r\n            /** Fix for Chrome issue #273306 **/\r\n            @media print {\r\n                body.A4 { width: 210mm }\r\n            }\r\n\r\n            .tdstyle{\r\n                border: 1px solid #000 !important;\r\n                padding-top: 2px !important;\r\n                padding-bottom:  2px !important;\r\n                padding-left:  2px !important;\r\n                padding-right:  2px !important;\r\n            }\r\n\r\n            .line{\r\n                width: 100%;\r\n                height: 1px;\r\n                border-bottom: 1px solid black;\r\n            }\r\n\r\n            table { page-break-inside:auto }\r\n            tr    { page-break-inside:avoid; page-break-after:auto }\r\n            thead { display:table-header-group }\r\n            tfoot { display:table-footer-group }\r\n        </style>\r\n    </head>\r\n    <body class=\"A4\">\r\n        <section class=\"sheet padding-10mm\">\r\n\r\n            <table style=\"width: 100%; height: 60px;\">\r\n                <tr>\r\n                    <td style=\"font-size: 20px;width: 50%;font-weight: bold;padding-left: 5px;padding-top: 0px; padding-bottom: 0px;\">";
echo $data["settings"]["shop_name"];
echo " - ";
echo $data["settings"]["address"];
echo "</td>\r\n                </tr>\r\n                <tr style=\"height: 22px;\">\r\n                    <td style=\"font-size: 12px;width: 50%;padding-left: 5px;padding-top: 0px; padding-bottom: 0px;\">From: ";
echo $data["date_from"];
echo " To ";
echo $data["date_to"];
echo "</td>\r\n                </tr>\r\n            </table>\r\n\r\n            <div class=\"line\"></div>\r\n\r\n            <table style=\"width: 100%; margin-top: 10px;\">\r\n                <thead >\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </thead>\r\n                <tfoot>\r\n                    <tr><th colspan=\"6\">&nbsp;</th></tr>\r\n                </tfoot>\r\n                <tbody>\r\n                    <tr style=\"height: 30px;\">\r\n                        <td class=\"tdstyle\" style=\"width: 200px;\"><b>Date</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 150px;\"><b>Number Of Invoices</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 150px;\"><b>Sold Quantity</b></td>\r\n                        <td class=\"tdstyle\" style=\"; text-align: left;\"><b>Total Amount</b></td>\r\n                    </tr>\r\n                    ";
for ($i = 0; $i < count($data["report_info"]); $i++) {
    echo "                        <tr>\r\n                            <td class=\"tdstyle\">";
    echo $data["report_info"][$i]["creation_date"];
    echo "</td>\r\n                            <td class=\"tdstyle\">";
    echo $data["report_info"][$i]["invoices_nb"];
    echo "</td>                    \r\n                            <td class=\"tdstyle\">";
    echo number_format($data["report_info"][$i]["soldqty"], 0);
    echo "</td>\r\n                            <td class=\"tdstyle\">";
    echo self::value_format_custom($data["report_info"][$i]["sum"], $data["settings"]);
    echo "</td>\r\n                        </tr>       \r\n                    ";
}
echo " \r\n                </tbody>\r\n            </table>\r\n        </section>\r\n    </body>\r\n</html>\r\n";

?>