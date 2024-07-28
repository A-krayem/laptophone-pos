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
echo "<!DOCTYPE HTML>\r\n<html>\r\n    <head>\r\n        <title>Customer Statement</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <title>Test</title>\r\n        <style type=\"text/css\">\r\n\r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n\r\n            .sheet {\r\n                margin: 0;\r\n                overflow: hidden;\r\n                position: relative;\r\n                box-sizing: border-box;\r\n                page-break-after: always;\r\n            }\r\n\r\n            body.A4               .sheet { width: 210mm;  } /*height: 296mm*/\r\n\r\n            /** Padding area **/\r\n            .sheet.padding-5mm { padding: 5mm }\r\n            .sheet.padding-10mm { padding: 10mm }\r\n            .sheet.padding-15mm { padding: 15mm }\r\n            .sheet.padding-20mm { padding: 20mm }\r\n            .sheet.padding-25mm { padding: 25mm }\r\n\r\n            /** For screen preview **/\r\n            @media screen {\r\n                body { background: #e0e0e0 }\r\n                .sheet {\r\n                    background: white;\r\n                    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\r\n                    margin: 5mm auto;\r\n                }\r\n            }\r\n\r\n            /** Fix for Chrome issue #273306 **/\r\n            @media print {\r\n                body.A4 { width: 210mm }\r\n            }\r\n\r\n            .tdstyle{\r\n                border: 1px solid #000 !important;\r\n                padding-top: 2px !important;\r\n                padding-bottom:  2px !important;\r\n                padding-left:  2px !important;\r\n                padding-right:  2px !important;\r\n            }\r\n\r\n            .line{\r\n                width: 100%;\r\n                height: 1px;\r\n                border-bottom: 1px solid black;\r\n            }\r\n\r\n            table { page-break-inside:auto }\r\n            tr    { page-break-inside:avoid; page-break-after:auto }\r\n            thead { display:table-header-group }\r\n            tfoot { display:table-footer-group }\r\n            \r\n            \r\n            .det_table tr td{\r\n                 border: 1px solid #000 !important;\r\n                 font-size: 12px;\r\n                 height: 25px;\r\n                 padding-left: 5px;\r\n                 padding-right: 5px;\r\n                 vertical-align: middle;\r\n             }\r\n        </style>\r\n    </head>\r\n    <body class=\"A4\">\r\n        <section class=\"sheet padding-5mm\">\r\n\r\n            <table style=\"width: 100%; height: 80px;\">\r\n                <tr>\r\n                    <td style=\"font-size: 18px;width: 50%;font-weight: bold;padding-left: 5px;\">";
echo $data["settings"]["shop_name"];
echo "<br/>";
echo $data["settings"]["address"];
echo "<br/>";
echo $data["settings"]["phone_nb"];
echo "</td>\r\n                    <td style=\"font-size: 14px;width: 50%;text-align: right\"><b>Date:</b> ";
$datetime = new DateTime();
echo $datetime->format("Y-m-d H:i:s");
echo "</td>\r\n                </tr>\r\n            </table>\r\n\r\n            <div class=\"line\"></div>\r\n\r\n            <table style=\"width: 100%; margin-top: 20px;\">\r\n                <tr>\r\n                    <td style=\"font-size: 20px;font-weight: bold;text-align: center\">Statement of Account - ";
echo ucfirst($data["customer"][0]["name"]) . " " . ucfirst($data["customer"][0]["middle_name"]) . " " . ucfirst($data["customer"][0]["last_name"]);
echo "</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-size: 14px;text-align: center\"><b>From Date:</b> ";
echo $data["start_date"];
echo " - <b>To Date:</b> ";
echo $data["end_date"];
echo "</td>\r\n                </tr>\r\n            </table> \r\n\r\n            <table style=\"width: 100%; margin-top: 10px;\" class=\"det_table\">\r\n                <tbody>\r\n                    <tr style=\"height: 30px;background-color: #e5e3e3\">\r\n                        <td class=\"tdstyle\" style=\"width: 85px;\"><b>Date</b></td>\r\n                        <td class=\"tdstyle\"><b>Description</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 60px; text-align: center\"><b>Qty</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 70px; text-align: center\"><b>Unit price</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 80px; text-align: center\"><b>Debit</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 80px; text-align: center\"><b>Credit</b></td>\r\n                        <td class=\"tdstyle\" style=\"width: 100px; text-align: center\"><b>Balance</b></td>\r\n                    </tr>\r\n                    ";
for ($i = 0; $i < count($data["st_of_acc"]); $i++) {
    echo "                        <tr>\r\n                            <td class=\"tdstyle\">";
    echo $data["st_of_acc"][$i]["date"];
    echo "</td>\r\n                            <td class=\"tdstyle\">";
    echo $data["st_of_acc"][$i]["description"];
    echo "</td>                    \r\n                            <td class=\"tdstyle\" style=\"text-align: center\">\r\n                                ";
    if (floor($data["st_of_acc"][$i]["qty"]) != $data["st_of_acc"][$i]["qty"]) {
        echo self::global_number_formatter($data["st_of_acc"][$i]["qty"], $data["settings"]);
    } else {
        if (0 < $data["st_of_acc"][$i]["qty"]) {
            echo self::global_number_formatter($data["st_of_acc"][$i]["qty"], $data["settings"]);
        }
    }
    echo "                            </td>\r\n                            \r\n                            \r\n                            <td class=\"tdstyle\" style=\"text-align: right\">\r\n                                \r\n                                ";
    if ($data["st_of_acc"][$i]["unit_price"] == "") {
        $data["st_of_acc"][$i]["unit_price"] = 0;
    }
    if (floor($data["st_of_acc"][$i]["unit_price"]) != $data["st_of_acc"][$i]["unit_price"]) {
        echo self::global_number_formatter($data["st_of_acc"][$i]["unit_price"], $data["settings"]);
    } else {
        if (0 < $data["st_of_acc"][$i]["unit_price"]) {
            echo self::global_number_formatter($data["st_of_acc"][$i]["unit_price"], $data["settings"]);
        }
    }
    echo "                                \r\n                            \r\n                            </td>\r\n                            <td class=\"tdstyle\" style=\"text-align: left\">\r\n                                ";
    if (0 < round($data["st_of_acc"][$i]["debit"], $data["settings"]["round_val"])) {
        echo self::global_number_formatter($data["st_of_acc"][$i]["debit"], $data["settings"]);
    }
    echo "                            </td>\r\n                            <td class=\"tdstyle\" style=\"text-align: left\">\r\n                                ";
    if (0 < round($data["st_of_acc"][$i]["credit"], $data["settings"]["round_val"])) {
        echo self::global_number_formatter($data["st_of_acc"][$i]["credit"], $data["settings"]);
    }
    echo "                            </td>\r\n                            <td class=\"tdstyle\" style=\"text-align: left\">";
    echo self::global_number_formatter($data["st_of_acc"][$i]["balance"], $data["settings"]);
    echo "</td>\r\n                        </tr>      \r\n                    ";
}
echo " \r\n                    \r\n                </tbody>\r\n            </table>\r\n        </section>\r\n    </body>\r\n</html>\r\n";

?>