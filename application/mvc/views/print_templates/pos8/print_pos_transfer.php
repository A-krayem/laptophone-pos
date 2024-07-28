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
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n\n<html>\n    <head>\n        <title>Transfer</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\n\n        <!-- Normalize or reset CSS with your favorite library -->\n  \n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        \n        <style type=\"text/css\">\n            \n            @font-face {\n                font-family: 'AlexandriaFLF';\n                    src: url('application/mvc/views/custom_libraries/font/Merchant.ttf') format('truetype');\n                font-weight: normal;\n                font-style: normal;\n                  }\n      \n            @page { margin: 0 }\n            body { \n                margin: 0 ;\n            }\n            \n            .sheet {\n              margin: 0;\n              overflow: hidden;\n              position: relative;\n              box-sizing: border-box;\n              page-break-after: always;\n            }\n\n            /** Paper sizes **/\n            body.A3               .sheet { width: 297mm; height: 419mm }\n            body.A3.landscape     .sheet { width: 420mm; height: 296mm }\n            body.A4               .sheet { width: 210mm; height: 296mm }\n            body.A4.landscape     .sheet { width: 297mm; height: 209mm }\n            body.A5               .sheet { width: 148mm; height: 209mm }\n            body.A5.landscape     .sheet { width: 210mm; height: 147mm }\n            body.letter           .sheet { width: 216mm; height: 279mm }\n            body.letter.landscape .sheet { width: 280mm; height: 215mm }\n            body.legal            .sheet { width: 216mm; height: 356mm }\n            body.legal.landscape  .sheet { width: 357mm; height: 215mm }\n            \n            body.POS8             .sheet { width: 80mm;  }\n\n            /** Padding area **/\n            .sheet.padding-1mm { padding: 1mm }\n            .sheet.padding-5mm { padding: 5mm }\n            .sheet.padding-10mm { padding: 10mm }\n            .sheet.padding-15mm { padding: 15mm }\n            .sheet.padding-20mm { padding: 20mm }\n            .sheet.padding-25mm { padding: 25mm }\n\n            /** For screen preview **/\n            @media screen {\n              body { background: #e0e0e0 ;}\n              .sheet {\n                background: white;\n                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\n                margin: 0mm auto;\n              }\n            }\n\n            /** Fix for Chrome issue #273306 **/\n            @media print {\n                body.A3.landscape { width: 420mm }\n                body.A3, body.A4.landscape { width: 297mm }\n                body.A4, body.A5.landscape { width: 210mm }\n                body.A5                    { width: 148mm }\n                body.POS8                  { width: 80mm }\n                body.letter, body.legal    { width: 216mm }\n                body.letter.landscape      { width: 280mm }\n                body.legal.landscape       { width: 357mm }\n            }\n            \n            .line{\n                width: 100% !important;\n                height: 1px;\n                border-bottom: 1px solid #000;\n            }\n        </style>\n        \n        <style>\n            @page { size: POS8}\n\n            @media print {\n                body{\n                    font-family: 'DroidArabicKufiRegular';\n                }\n            }\n        </style>\n        \n        <script type=\"text/javascript\">\n            \$(document).ready(function () {\n                window.print();\n                //setTimeout(function(){\n                    //window.close();\n                //},3000);\n            });\n        </script>\n        \n    </head>\n    <body class=\"POS8\">\n\n  <!-- Each sheet element should have the class \"sheet\" -->\n  <!-- \"padding-**mm\" is optional: you can set 10, 15, 20 or 25 -->\n        <section class=\"sheet padding-1mm\">\n        <br/>\n        <br/>\n        <table style=\"width: 100%; margin-bottom: 10px;\">\n            <tr>\n                <td style=\"text-align: center;font-size: 18px;line-height: 22px; width: 150px;\" colspan=\"2\">\n                    <b>Branch Transfer</b>\n                </td>\n            </tr>\n            <tr>\n                <td colspan=\"2\">\n                    &nbsp;\n                </td>\n            </tr>\n            <tr>\n                <td style=\"text-align: right;font-size: 14px;line-height: 22px; width: 150px;\">\n                    <b>Transfer NB: </b>\n                </td>\n                <td style=\"text-align: left;font-size: 14px;line-height: 22px; padding-left: 5px;\">\n                    ";
echo $data["transfer_id"];
echo "                </td>\n            </tr>\n            <tr>\n                <td style=\"text-align: right;font-size: 14px;line-height: 22px;\">\n                    <b>Transfer Date:</b>\n                </td>\n                <td style=\"text-align: left;font-size: 14px;line-height: 22px; padding-left: 5px;\">\n                    ";
echo $data["transfer_date"];
echo "                </td>\n            </tr>\n            <tr>\n                <td style=\"text-align: right;font-size: 14px;line-height: 22px;\">\n                    <b>From:</b> \n                </td>\n                <td style=\"text-align: left;font-size: 14px;line-height: 22px; padding-left: 5px;\">\n                    ";
echo $data["from_branch"];
echo "                </td>\n            </tr>\n           <tr>\n                <td style=\"text-align: right;font-size: 14px;line-height: 22px;\">\n                    <b>To:</b> \n                </td>\n                 <td style=\"text-align: left;font-size: 14px;line-height: 22px; padding-left: 5px;\">\n                    ";
echo $data["to_branch"];
echo "                </td>\n            </tr>\n            <tr>\n                <td style=\"text-align: right;font-size: 14px;line-height: 22px;\">\n                    <b>Vendor Name:</b> \n                </td>\n                <td style=\"text-align: left;font-size: 14px;line-height: 22px; padding-left: 5px;\">\n                    ";
echo $data["vendor"];
echo "                </td>\n            </tr>\n            \n            <tr>\n                <td style=\"height: 20px;\"></td>\n            </tr>\n            \n   \n            \n        </table>\n        \n        \n        <div class=\"line\"></div>\n        \n        \n        \n        <table style=\"width: 100%; margin-top: 10px;\">\n            <tr>\n                 <td style=\"text-align: center;font-size: 14px;line-height: 22px;\">\n                    <b>Item ID </b><br/>\n                     ";
echo $data["item"]["id"];
echo "                </td>\n            </tr>\n            <tr>\n                 <td style=\"text-align: center;font-size: 14px;line-height: 22px;\">\n                    <b>Description </b><br/>\n                     ";
echo $data["item"]["description"];
echo "                </td>\n            </tr>\n            <tr>\n                 <td style=\"text-align: center;font-size: 14px;line-height: 22px;\">\n                    <b>Barcode </b><br/>\n                     ";
echo $data["item"]["barcode"];
echo "                </td>\n            </tr>\n            <tr>\n                 <td style=\"text-align: center;font-size: 14px;line-height: 22px;\">\n                    <b>Quantity </b><br/>\n                     ";
echo $data["transfer_qty"];
echo "                </td>\n            </tr>\n        </table>\n        \n         \n        \n        <table style=\"width: 100%; margin-top: 20px;\">\n            <tr>\n                <td style=\"font-size: 18px; text-align: center\">\n                  Thank you\n                </td>\n            </tr>\n        </table>\n        <br/><br/>\n    </section>\n  </body>\n    \n \n</html>\n";

?>