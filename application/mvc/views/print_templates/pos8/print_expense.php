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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n\r\n<html>\r\n    <head>\r\n        <title>Expense</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n\r\n        <!-- Normalize or reset CSS with your favorite library -->\r\n  \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <style type=\"text/css\">\r\n            \r\n            @font-face {\r\n                font-family: 'AlexandriaFLF';\r\n                    src: url('application/mvc/views/custom_libraries/font/Merchant.ttf') format('truetype');\r\n                font-weight: normal;\r\n                font-style: normal;\r\n                  }\r\n      \r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n            \r\n            .sheet {\r\n              margin: 0;\r\n              overflow: hidden;\r\n              position: relative;\r\n              box-sizing: border-box;\r\n              page-break-after: always;\r\n            }\r\n\r\n            /** Paper sizes **/\r\n            body.A3               .sheet { width: 297mm; height: 419mm }\r\n            body.A3.landscape     .sheet { width: 420mm; height: 296mm }\r\n            body.A4               .sheet { width: 210mm; height: 296mm }\r\n            body.A4.landscape     .sheet { width: 297mm; height: 209mm }\r\n            body.A5               .sheet { width: 148mm; height: 209mm }\r\n            body.A5.landscape     .sheet { width: 210mm; height: 147mm }\r\n            body.letter           .sheet { width: 216mm; height: 279mm }\r\n            body.letter.landscape .sheet { width: 280mm; height: 215mm }\r\n            body.legal            .sheet { width: 216mm; height: 356mm }\r\n            body.legal.landscape  .sheet { width: 357mm; height: 215mm }\r\n            \r\n            body.POS8             .sheet { width: 80mm;  }\r\n\r\n            /** Padding area **/\r\n            .sheet.padding-1mm { padding: 1mm }\r\n            .sheet.padding-5mm { padding: 5mm }\r\n            .sheet.padding-10mm { padding: 10mm }\r\n            .sheet.padding-15mm { padding: 15mm }\r\n            .sheet.padding-20mm { padding: 20mm }\r\n            .sheet.padding-25mm { padding: 25mm }\r\n\r\n            /** For screen preview **/\r\n            @media screen {\r\n              body { background: #e0e0e0 ;}\r\n              .sheet {\r\n                background: white;\r\n                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\r\n                margin: 0mm auto;\r\n              }\r\n            }\r\n\r\n            /** Fix for Chrome issue #273306 **/\r\n            @media print {\r\n                body.A3.landscape { width: 420mm }\r\n                body.A3, body.A4.landscape { width: 297mm }\r\n                body.A4, body.A5.landscape { width: 210mm }\r\n                body.A5                    { width: 148mm }\r\n                body.POS8                  { width: 80mm }\r\n                body.letter, body.legal    { width: 216mm }\r\n                body.letter.landscape      { width: 280mm }\r\n                body.legal.landscape       { width: 357mm }\r\n            }\r\n            \r\n            .line{\r\n                width: 100% !important;\r\n                height: 1px;\r\n                border-bottom: 1px solid #000;\r\n            }\r\n        </style>\r\n        \r\n        <style>\r\n            @page { size: POS8}\r\n\r\n            @media print {\r\n                body{\r\n                    \r\n                }\r\n            }\r\n        </style>\r\n        \r\n        <script type=\"text/javascript\">\r\n            \$(document).ready(function () {\r\n                window.print();\r\n                //setTimeout(function(){\r\n                    //window.close();\r\n                //},3000);\r\n            });\r\n        </script>\r\n        \r\n    </head>\r\n    <body class=\"POS8\">\r\n\r\n  <!-- Each sheet element should have the class \"sheet\" -->\r\n  <!-- \"padding-**mm\" is optional: you can set 10, 15, 20 or 25 -->\r\n        <section class=\"sheet padding-1mm\">\r\n        ";
if (0 < strlen($this->settings_info["invoice_logo"])) {
    echo "        <table style=\"width: 100%; margin-bottom: 10px;\">\r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 20px; font-weight: bold;line-height: 22px;\">\r\n                    <img width=\"150px;\" src=\" ";
    echo "resources/" . $this->settings_info["invoice_logo"];
    echo "\" />\r\n                </td>\r\n            </tr>\r\n        </table>\r\n        ";
}
echo "        \r\n        <table style=\"width: 100%; margin-bottom: 10px;\">\r\n            ";
if ($this->settings_info["hide_shop_name_on_invoice"] == 0) {
    echo "            <tr>\r\n                <td style=\"text-align: center;font-size: 20px; font-weight: bold;line-height: 22px;\">\r\n                    ";
    echo $this->settings_info["shop_name"];
    echo "                </td>\r\n            </tr>\r\n            ";
}
echo "            \r\n            ";
if (0 < strlen($this->settings_info["address"])) {
    echo "            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;line-height: 22px;\">\r\n                    ";
    echo $this->settings_info["address"];
    echo "                </td>\r\n            </tr>\r\n            ";
}
echo "            \r\n            ";
if (0 < strlen($this->settings_info["phone_nb"])) {
    echo "            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;line-height: 22px;\">\r\n                    ";
    echo $this->settings_info["phone_nb"];
    echo "                </td>\r\n            </tr>\r\n            ";
}
echo "            \r\n            <tr>\r\n                <td style=\"height: 20px;\"></td>\r\n            </tr>\r\n            \r\n\r\n             \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 18px; text-align: center; font-weight: bold;line-height: 22px;\">\r\n                    EXPENSE                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\r\n                    ";
echo "DATE: " . $data["expense_info"][0]["creation_date"];
echo "                </td>\r\n            </tr> \r\n            \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\r\n                    ";
echo "EXPENSE ID: " . $data["expense_info"][0]["id"];
echo "                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\r\n                    ";
echo "VENDOR NAME: " . $data["employee_info"][$data["expense_info"][0]["vendor_id"]]["username"];
echo "                </td>\r\n            </tr>\r\n            \r\n           \r\n            \r\n            \r\n        </table>\r\n        \r\n        <div class=\"line\"></div>\r\n                <table style=\"width: 100%; margin-top: 10px;\">\r\n\r\n            <tr>\r\n                <td style=\"height: 0px;\"></td>\r\n            </tr>\r\n            \r\n\r\n            <tr>\r\n                \r\n                <td style=\"width: 230px;font-size: 16px;line-height: 22px;\" colspan=\"2\">\r\n                    <b style=\"\">Description:</b> ";
echo $data["expense_info"][0]["description"];
echo "                </td>\r\n            </tr>\r\n            <tr>\r\n                \r\n                <td style=\"width: 230px;font-size: 16px;line-height: 22px;\" colspan=\"2\">\r\n                    <b style=\"\">Amount:</b> ";
echo $data["expense_info"][0]["value"];
echo "                </td>\r\n            </tr>\r\n\r\n       \r\n        </table>\r\n        <div class=\"line\"></div>\r\n        \r\n        \r\n  \r\n        <table style=\"width: 100%; margin-top: 10px; border: 1px solid #000\">\r\n            <tr>\r\n                <td><b>IN USD:</b> ";
echo number_format($data["expense_info"][0]["cash_usd_in"], 2);
echo "</td>\r\n                <td><b>OUT USD:</b> ";
echo number_format($data["expense_info"][0]["returned_cash_usd"], 2);
echo "</td>\r\n            </tr>\r\n            <tr>\r\n                <td><b>IN LBP:</b> ";
echo number_format($data["expense_info"][0]["cash_lbp_in"], 0);
echo "</td>\r\n                \r\n                <td><b>OUT LBP:</b> ";
echo number_format($data["expense_info"][0]["returned_cash_lbp"], 0);
echo "</td>\r\n            </tr>\r\n        </table>\r\n  \r\n        \r\n        <table style=\"width: 100%; margin-top: 20px; \">\r\n            <tr>\r\n                <td style=\"font-size: 18px; direction: ";
echo $this->settings_info["footer_direction"];
echo "\">\r\n                                   </td>\r\n            </tr>\r\n        </table>\r\n    </section>\r\n  </body>\r\n    \r\n \r\n</html>\r\n";

?>