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
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n\n<html>\n    <head>\n        <title>Invoice</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\n\n        <!-- Normalize or reset CSS with your favorite library -->\n  \n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        \n        <style type=\"text/css\">\n            \n            @font-face {\n                font-family: 'AlexandriaFLF';\n                    src: url('application/mvc/views/custom_libraries/font/Merchant.ttf') format('truetype');\n                font-weight: normal;\n                font-style: normal;\n                  }\n      \n            @page { margin: 0 }\n            body { \n                margin: 0 ;\n                font-family:monospace !important;\n                /*font-family: 'DroidArabicKufiRegular';*/\n            }\n            \n            .sheet {\n              margin: 0;\n              overflow: hidden;\n              position: relative;\n              box-sizing: border-box;\n              page-break-after: always;\n            }\n\n            /** Paper sizes **/\n            body.A3               .sheet { width: 297mm; height: 419mm }\n            body.A3.landscape     .sheet { width: 420mm; height: 296mm }\n            body.A4               .sheet { width: 210mm; height: 296mm }\n            body.A4.landscape     .sheet { width: 297mm; height: 209mm }\n            body.A5               .sheet { width: 148mm; height: 209mm }\n            body.A5.landscape     .sheet { width: 210mm; height: 147mm }\n            body.letter           .sheet { width: 216mm; height: 279mm }\n            body.letter.landscape .sheet { width: 280mm; height: 215mm }\n            body.legal            .sheet { width: 216mm; height: 356mm }\n            body.legal.landscape  .sheet { width: 357mm; height: 215mm }\n            \n            body.POS8             .sheet { width: 80mm;  }\n\n            /** Padding area **/\n            .sheet.padding-1mm { padding: 1mm }\n            .sheet.padding-5mm { padding: 5mm }\n            .sheet.padding-10mm { padding: 10mm }\n            .sheet.padding-15mm { padding: 15mm }\n            .sheet.padding-20mm { padding: 20mm }\n            .sheet.padding-25mm { padding: 25mm }\n\n            /** For screen preview **/\n            @media screen {\n              body { background: #e0e0e0 ;font-family:monospace !important;}\n              .sheet {\n                background: white;\n                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\n                margin: 0mm auto;\n              }\n            }\n\n            /** Fix for Chrome issue #273306 **/\n            @media print {\n                body.A3.landscape { width: 420mm }\n                body.A3, body.A4.landscape { width: 297mm }\n                body.A4, body.A5.landscape { width: 210mm }\n                body.A5                    { width: 148mm }\n                body.POS8                  { width: 80mm }\n                body.letter, body.legal    { width: 216mm }\n                body.letter.landscape      { width: 280mm }\n                body.legal.landscape       { width: 357mm }\n            }\n            \n            .line{\n                width: 100% !important;\n                height: 1px;\n                border-bottom: 1px solid #000;\n            }\n        </style>\n        \n        <style>\n            @page { size: POS8}\n\n            @media print {\n                body{\n                    \n                }\n            }\n        </style>\n        \n        <script type=\"text/javascript\">\n            \$(document).ready(function () {\n                window.print();\n                setTimeout(function(){\n                    //window.close();\n                },3000);\n            });\n        </script>\n        \n    </head>\n    <body class=\"POS8\">\n\n  <!-- Each sheet element should have the class \"sheet\" -->\n  <!-- \"padding-**mm\" is optional: you can set 10, 15, 20 or 25 -->\n        <section class=\"sheet padding-1mm\">\n        <table style=\"width: 100%; margin-bottom: 10px;\">\n            ";
if ($this->settings_info["hide_shop_name_on_invoice"] == 0) {
    echo "            <tr>\n                <td style=\"text-align: center;font-size: 30px; font-weight: bold;line-height: 22px;\">\n                    ";
    echo $this->settings_info["shop_name"];
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            ";
if (0 < strlen($this->settings_info["address"])) {
    echo "            <tr>\n                <td style=\"text-align: center;font-size: 18px;line-height: 22px;\">\n                    ";
    echo $this->settings_info["address"];
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            ";
if (0 < strlen($this->settings_info["phone_nb"])) {
    echo "            <tr>\n                <td style=\"text-align: center;font-size: 18px;line-height: 22px;\">\n                    ";
    echo $this->settings_info["phone_nb"];
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            <tr>\n                <td style=\"height: 20px;\"></td>\n            </tr>\n            \n\n             \n            <tr>\n                <td style=\"text-align: center;font-size: 16px; text-align: center; font-weight: bold;line-height: 22px;\">\n                    SALES INVOICE                </td>\n            </tr>\n            \n            <tr>\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\n                    ";
echo "DATE: " . $data["invoice"][0]["creation_date"];
echo "                </td>\n            </tr> \n            \n            <tr>\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\n                    ";
echo "INVOICE NUMBER: " . self::idFormat_invoice($data["invoice"][0]["id"]);
echo "                </td>\n            </tr>\n            \n            \n             ";
if (0 < strlen($data["customer_info"])) {
    echo "            <tr>\n                <td style=\"font-size: 20px; text-align: left;line-height: 22px; padding-left: 5px;\">\n                    \n                    <div class=\"line\"></div>\n                    ";
    echo "<br/><b>CUSTOMER:</b> " . $data["customer_info"] . "<br/>";
    if (0 < strlen($data["customer_address"])) {
        echo "<b>ADDRESS:</b> " . $data["customer_address"] . "<br/>";
    }
    if (0 < strlen($data["address_area"])) {
        echo "<b>Area:</b> " . $data["address_area"] . "<br/>";
    }
    if (0 < strlen($data["address_city"])) {
        echo "<b>City:</b> " . $data["address_city"] . "<br/>";
    }
    if (0 < strlen($data["address_street"])) {
        echo "<b>Street:</b> " . $data["address_street"] . "<br/>";
    }
    if (0 < strlen($data["address_building"])) {
        echo "<b>Building:</b> " . $data["address_building"] . "<br/>";
    }
    if (0 < strlen($data["address_floor"])) {
        echo "<b>Floor:</b> " . $data["address_floor"] . "<br/>";
    }
    if (0 < strlen($data["address_note"])) {
        echo "<b>Note:</b> " . $data["address_note"] . "<br/>";
    }
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            \n            \n            \n           \n            \n            \n        </table>\n        \n        <div class=\"line\"></div>\n        ";
$total_price = 0;
$getItems = NULL;
$total_items = 0;
$discount_percentage = number_format(abs(100 * $data["invoice"][0]["invoice_discount"] / $data["invoice"][0]["total_value"]), 2);
$discount_value = $data["invoice"][0]["invoice_discount"];
$total_qty = 0;
echo "        <table style=\"width: 100%; margin-top: 10px;\">\n            ";
$rate = $data["invoice"][0]["rate"];
for ($i = 0; $i < count($data["items"]); $i++) {
    $data["invoice"][0]["rate"] = 1;
    echo "            \n            ";
    if (0 < $i) {
        echo "            <tr>\n                <td style=\"height: 0px;\"></td>\n            </tr>\n            \n           \n            ";
    }
    echo "            \n            <tr>\n                ";
    $total_qty += (double) $data["items"][$i]["qty"];
    if ($data["items"][$i]["item_id"] === NULL) {
        $description_item = $data["items"][$i]["description"];
    } else {
        $getItems = $data["items_info_class"]->get_item($data["items"][$i]["item_id"]);
        $description_item = $getItems[0]["description"];
        if (0 < strlen($getItems[0]["item_alias"])) {
            $description_item = $getItems[0]["item_alias"];
        }
    }
    $out = 40 < strlen($description_item) ? substr($description_item, 0, 40) : $description_item;
    $total_price += floatval($data["items"][$i]["final_price_disc_qty"]);
    if ($data["currency_system_default"] == 2) {
        if (0 < $data["items"][$i]["discount"]) {
            $unit_price = $data["items"][$i]["selling_price"] * (1 - $data["items"][$i]["discount"] / 100);
        } else {
            $unit_price = $data["items"][$i]["selling_price"];
        }
    } else {
        if (0 < $data["items"][$i]["discount"]) {
            $unit_price = $data["items"][$i]["selling_price"] * (1 - $data["items"][$i]["discount"] / 100) * $data["invoice"][0]["rate"];
        } else {
            $unit_price = $data["items"][$i]["selling_price"];
        }
    }
    if ($data["currency_system_default"] == 2) {
        $total = $data["items"][$i]["final_price_disc_qty"];
    } else {
        $total = $data["items"][$i]["final_price_disc_qty"] * $data["invoice"][0]["rate"];
    }
    echo "                <td style=\"width: 230px;font-size: 16px;line-height: 22px;\" colspan=\"2\">\n                    ";
    echo $data["items"][$i]["item_id"] . " - " . $out;
    echo "                </td>\n            </tr>\n            \n            <tr>\n                <td style=\"width: 160px;font-size: 16px;line-height: 22px;\">";
    echo "" . self::value_format_custom_no_currency($unit_price, $this->settings_info) . " <b>X</b> " . (double) $data["items"][$i]["qty"];
    echo "</td>\n                <td style=\"font-size: 16px;\">";
    echo "Total:<span style='float:right'>" . self::value_format_custom_no_currency($total, $this->settings_info) . " USD</span>";
    echo "</td>\n            </tr>\n            \n            ";
    if ($i < count($data["items"]) - 1) {
        echo "            <tr>\n                 <td colspan=\"2\" style=\"height: 1px; border-bottom: 1px dashed #000; width: 100%\"></td>\n            </tr>\n            ";
    }
    echo "            \n            ";
}
echo "        </table>\n        <div class=\"line\"></div>\n        \n        \n        \n        <table style=\"width: 100%; margin-top: 10px;\">\n            <tr>\n                <td  style=\"width: 160px;font-size: 16px;line-height: 22px;text-align: right\">\n                    <b>Items Quantity</b>\n                <td>\n                <td colspan=\"2\" style=\"width: 160px;font-size: 16px;line-height: 22px;text-align: right\">\n                    ";
echo $total_qty;
echo "                </td>\n            </tr>\n        </table>\n        \n        \n        <table style=\"width: 100%; margin-top: 10px;\">\n            \n            \n            \n            ";
if (0 < abs($discount_value)) {
    echo "            <tr>\n                <td  style=\"width: 160px;font-size: 18px;line-height: 22px;text-align: right\">\n                    <b>Discount:</b>\n                <td>\n                <td colspan=\"2\" style=\"width: 160px;font-size: 18px;line-height: 22px;text-align: right\">\n                    ";
    echo $discount_percentage . "%";
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            ";
if (0 < abs($discount_value)) {
    echo "            <tr>\n                <td  style=\"width: 160px;font-size: 18px;line-height: 22px;text-align: right\">\n                    <b>Total (";
    echo $this->settings_info["default_currency_symbol"];
    echo "):</b>\n                <td>\n                <td colspan=\"2\" style=\"width: 160px;font-size: 18px;line-height: 22px;text-align: right\">\n                    ";
    if ($data["currency_system_default"] == 2) {
        echo self::value_format_custom_no_currency($total_price + $discount_value, $this->settings_info);
    } else {
        echo self::value_format_custom_no_currency($total_price + $discount_value, $this->settings_info);
    }
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            \n           \n            \n            ";
if ($data["currency_system_default"] == 1) {
    echo "            \n            <tr>\n                <td  style=\"width: 160px;font-size: 16px;line-height: 22px;text-align: right\">\n                    <b>Total Amount:</b>\n                <td>\n                    <td colspan=\"2\" style=\"width: 160px;font-size: 16px;text-align: right\">\n                ";
    echo self::value_format_custom_no_currency($total_price + $discount_value, $this->settings_info) . " USD";
    echo "   \n                 <td>\n            </tr>\n            ";
}
echo "  \n            \n            \n          \n            \n        </table>\n        \n\t\t\n\n        \n        \n        <table style=\"width: 100%; margin-top: 20px;\">\n            <tr>\n                <td style=\"font-size: 18px; direction: ";
echo $this->settings_info["footer_direction"];
echo "\">\n                   ";
echo $this->settings_info["invoice_footer"];
echo "                </td>\n            </tr>\n        </table>\n    </section>\n  </body>\n    \n \n</html>\n";

?>