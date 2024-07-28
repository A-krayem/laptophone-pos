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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n\r\n<html>\r\n    <head>\r\n        <title>Invoice</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n\r\n        <!-- Normalize or reset CSS with your favorite library -->\r\n  \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <style type=\"text/css\">\r\n            \r\n            @font-face {\r\n                font-family: 'AlexandriaFLF';\r\n                    src: url('application/mvc/views/custom_libraries/font/Merchant.ttf') format('truetype');\r\n                font-weight: normal;\r\n                font-style: normal;\r\n                  }\r\n      \r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n            \r\n            .sheet {\r\n              margin: 0;\r\n              overflow: hidden;\r\n              position: relative;\r\n              box-sizing: border-box;\r\n              page-break-after: always;\r\n            }\r\n\r\n            /** Paper sizes **/\r\n            body.A3               .sheet { width: 297mm; height: 419mm }\r\n            body.A3.landscape     .sheet { width: 420mm; height: 296mm }\r\n            body.A4               .sheet { width: 210mm; height: 296mm }\r\n            body.A4.landscape     .sheet { width: 297mm; height: 209mm }\r\n            body.A5               .sheet { width: 148mm; height: 209mm }\r\n            body.A5.landscape     .sheet { width: 210mm; height: 147mm }\r\n            body.letter           .sheet { width: 216mm; height: 279mm }\r\n            body.letter.landscape .sheet { width: 280mm; height: 215mm }\r\n            body.legal            .sheet { width: 216mm; height: 356mm }\r\n            body.legal.landscape  .sheet { width: 357mm; height: 215mm }\r\n            \r\n            body.POS8             .sheet { width: 80mm;  }\r\n\r\n            /** Padding area **/\r\n            .sheet.padding-1mm { padding: 1mm }\r\n            .sheet.padding-5mm { padding: 5mm }\r\n            .sheet.padding-10mm { padding: 10mm }\r\n            .sheet.padding-15mm { padding: 15mm }\r\n            .sheet.padding-20mm { padding: 20mm }\r\n            .sheet.padding-25mm { padding: 25mm }\r\n\r\n            /** For screen preview **/\r\n            @media screen {\r\n              body { background: #e0e0e0 ;}\r\n              .sheet {\r\n                background: white;\r\n                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\r\n                margin: 0mm auto;\r\n              }\r\n            }\r\n\r\n            /** Fix for Chrome issue #273306 **/\r\n            @media print {\r\n                body.A3.landscape { width: 420mm }\r\n                body.A3, body.A4.landscape { width: 297mm }\r\n                body.A4, body.A5.landscape { width: 210mm }\r\n                body.A5                    { width: 148mm }\r\n                body.POS8                  { width: 80mm }\r\n                body.letter, body.legal    { width: 216mm }\r\n                body.letter.landscape      { width: 280mm }\r\n                body.legal.landscape       { width: 357mm }\r\n            }\r\n            \r\n            .line{\r\n                width: 100% !important;\r\n                height: 1px;\r\n                border-bottom: 1px solid #000;\r\n            }\r\n        </style>\r\n        \r\n        <style>\r\n            @page { size: POS8}\r\n\r\n            @media print {\r\n                body{\r\n                    \r\n                }\r\n            }\r\n        </style>\r\n        \r\n        <script type=\"text/javascript\">\r\n            \$(document).ready(function () {\r\n                window.print();\r\n                //setTimeout(function(){\r\n                    //window.close();\r\n                //},3000);\r\n            });\r\n        </script>\r\n        \r\n    </head>\r\n    <body class=\"POS8\">\r\n\r\n  <!-- Each sheet element should have the class \"sheet\" -->\r\n  <!-- \"padding-**mm\" is optional: you can set 10, 15, 20 or 25 -->\r\n        <section class=\"sheet padding-1mm\">\r\n        ";
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
echo "            \r\n            <tr>\r\n                <td style=\"height: 20px;\"></td>\r\n            </tr>\r\n            \r\n\r\n             \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 18px; text-align: center; font-weight: bold;line-height: 22px;\">\r\n                    SALES INVOICE                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\r\n                    ";
echo "DATE: " . $data["invoice"][0]["creation_date"];
echo "                </td>\r\n            </tr> \r\n            \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\r\n                    ";
echo "INVOICE NUMBER: " . self::idFormat_invoice($data["invoice"][0]["id"]);
echo "                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"text-align: center;font-size: 16px;text-align: center;line-height: 22px;\">\r\n                    ";
echo "VENDOR NAME: " . $data["employee_info"][$data["invoice"][0]["employee_id"]]["username"];
echo "                </td>\r\n            </tr>\r\n            \r\n            ";
if (0 < $data["invoice"][0]["employee_id"]) {
    echo "            <tr>\r\n                <td style=\"font-size: 18px; text-align: center\">\r\n                   ";
    echo "SALESMAN: " . $data["sales_man"][$data["invoice"][0]["sales_person"]]["first_name"] . $data["sales_man"][$data["invoice"][0]["sales_person"]]["last_name"];
    echo "                </td>\r\n            </tr>\r\n   \r\n        ";
}
echo "            \r\n            \r\n             ";
if (0 < strlen($data["customer_info"])) {
    echo "            <tr>\r\n                <td style=\"font-size: 16px; text-align: left;line-height: 22px; padding-left: 5px;\">\r\n                    \r\n                    <div class=\"line\"></div>\r\n                    ";
    echo "<br/><b>CUSTOMER:</b> " . $data["customer_info"] . "<br/>";
    if (0 < $data["total_balance"]) {
        echo "<b>BALANCE:</b> " . $data["total_balance"] . "<br/>";
    }
    if (0 < strlen($data["phone"])) {
        echo "<b>PHONE:</b> " . $data["phone"] . "<br/>";
    }
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
    echo "                </td>\r\n            </tr>\r\n            ";
}
echo "            \r\n            \r\n            \r\n            \r\n           \r\n            \r\n            \r\n        </table>\r\n        \r\n        <div class=\"line\"></div>\r\n        ";
$total_price = 0;
$getItems = NULL;
$total_items = 0;
$discount_percentage = number_format(abs(100 * $data["invoice"][0]["invoice_discount"] / $data["invoice"][0]["total_value"]), 2);
$discount_value = $data["invoice"][0]["invoice_discount"];
$total_qty = 0;
echo "        <table style=\"width: 100%; margin-top: 10px;\">\r\n            ";
$rate = $data["invoice"][0]["rate"];
$total_inv = 0;
$total_inv_usd = 0;
for ($i = 0; $i < count($data["items"]); $i++) {
    $data["invoice"][0]["rate"] = 1;
    echo "            \r\n            ";
    if (0 < $i) {
        echo "            <tr>\r\n                <td style=\"height: 0px;\"></td>\r\n            </tr>\r\n            \r\n           \r\n            ";
    }
    echo "            \r\n            <tr>\r\n                ";
    $total_qty += (double) $data["items"][$i]["qty"];
    if ($data["items"][$i]["item_id"] === NULL) {
        $description_item = $data["items"][$i]["description"];
    } else {
        $getItems = $data["items_info_class"]->get_item($data["items"][$i]["item_id"]);
        $description_item = $getItems[0]["description"];
        $bcode = "";
        if ($data["settings"]["show_barcode_in_invoice"] == 1) {
            $bcode = $getItems[0]["barcode"] . "-";
        }
        if (0 < strlen($getItems[0]["item_alias"])) {
            $description_item = $getItems[0]["item_alias"];
        }
    }
    $out = $description_item;
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
    echo "                <td style=\"width: 230px;font-size: 16px;line-height: 22px;\" colspan=\"2\">\r\n                    <span style=\"font-size: 12px;\">";
    echo $i + 1;
    echo "-</span> ";
    echo $bcode . $out;
    echo "                </td>\r\n            </tr>\r\n            \r\n            ";
    if ($data["gift_print"] == 0 && ($data["settings"]["show_usd_in_invoice"] == 0 || $data["settings"]["show_usd_in_invoice"] == 1)) {
        echo "                \r\n            <tr>\r\n                <td style=\"width: 160px;font-size: 14px;line-height: 22px;\">\r\n                    ";
        $usd_vl = "";
        echo "" . self::value_format_custom_no_currency(self::only_round_lbp($unit_price * $rate), $this->settings_info) . $usd_vl . " <b>X</b> " . (double) $data["items"][$i]["qty"];
        echo "</td>\r\n                <td style=\"font-size: 16px;\">";
        echo "Total:<span style='float:right;font-size:14px;'>" . self::value_format_custom_no_currency(self::only_round_lbp($unit_price * $rate) * (double) $data["items"][$i]["qty"], $this->settings_info) . " LBP</span>";
        echo "</td>\r\n            </tr>\r\n            ";
    }
    echo "            \r\n            ";
    if ($data["gift_print"] == 0) {
        echo "                ";
        if ($data["settings"]["show_usd_in_invoice"] == 2 || $data["settings"]["show_usd_in_invoice"] == 1) {
            echo "                <tr>\r\n                    <td style=\"width: 160px;font-size: 14px;line-height: 22px;\">\r\n                        ";
            $usd_vl = "";
            $usd_vl = "<small>" . number_format($unit_price, 2) . " USD</small>";
            echo "" . $usd_vl . " <b>X</b> " . (double) $data["items"][$i]["qty"];
            echo " ";
            if (0 < $data["items"][$i]["discount"]) {
                echo "<span style='font-size:10px;'>D:" . number_format($data["items"][$i]["discount"], 2) . "</span>";
            }
            echo "</td>\r\n                    <td style=\"font-size: 16px;\"><small>";
            echo "Total:<span style='float:right;font-size:14px;'>" . self::value_format_custom_no_currency($unit_price * (double) $data["items"][$i]["qty"], $this->settings_info) . " USD</span>";
            echo "</small></td>\r\n                </tr>\r\n                ";
        }
        echo "            ";
    }
    echo "            \r\n            ";
    $total_inv += self::only_round_lbp($unit_price * $rate) * (double) $data["items"][$i]["qty"];
    echo "            \r\n            ";
    if ($i < count($data["items"]) - 1) {
        echo "            <tr>\r\n                 <td colspan=\"2\" style=\"height: 1px; border-bottom: 1px dashed #000; width: 100%\"></td>\r\n            </tr>\r\n            ";
    }
    echo "            \r\n            ";
}
echo "        </table>\r\n        <div class=\"line\"></div>\r\n        \r\n        \r\n        \r\n        <table style=\"width: 100%; margin-top: 10px;\">\r\n            <tr>\r\n                <td  style=\"width: 150px;font-size: 15px;line-height: 22px;text-align: right\">\r\n                    <b>Items Quantity:&nbsp;</b>\r\n                <td>\r\n                <td colspan=\"2\" style=\"width: 160px;font-size: 15px;line-height: 22px;text-align: left\">&nbsp;\r\n                    ";
echo $total_qty;
echo "                </td>\r\n            </tr>\r\n        </table>\r\n        \r\n        ";
if ($data["gift_print"] == 0) {
    echo "        <table style=\"width: 100%; margin-top: 10px;\">\r\n            \r\n            \r\n            \r\n            ";
    if (0 < abs($discount_value)) {
        echo "            <tr>\r\n                <td  style=\"width: 150px;font-size: 15px;line-height: 16px;text-align: right\">\r\n                    <b>Discount:&nbsp;</b>\r\n                <td>\r\n                <td colspan=\"2\" style=\"width: 160px;font-size: 15px;line-height: 22px;text-align: left\">\r\n                    ";
        echo $discount_percentage . "%";
        echo "                </td>\r\n            </tr>\r\n            ";
    }
    echo "            \r\n            ";
    if (0 < abs($discount_value)) {
        echo "            <tr>\r\n                <td  style=\"width: 150px;font-size: 15px;line-height: 22px;text-align: right\">\r\n                    <b>Total (";
        echo $this->settings_info["default_currency_symbol"];
        echo "):&nbsp;</b>\r\n                <td>\r\n                <td colspan=\"2\" style=\"width: 160px;font-size: 16px;line-height: 22px;text-align: left\">\r\n                    ";
        if ($data["currency_system_default"] == 2) {
            echo self::value_format_custom_no_currency($total_price + $discount_value, $this->settings_info);
        } else {
            echo self::value_format_custom_no_currency($total_price + $discount_value, $this->settings_info);
        }
        echo "                </td>\r\n            </tr>\r\n            ";
    }
    echo "            \r\n            \r\n           \r\n            \r\n            ";
    if ($data["currency_system_default"] == 1) {
        $usd_rate = "";
        if ($data["settings"]["show_usd_in_invoice"] == 1) {
            $usd_rate = " <small>1 USD = " . self::value_format_custom_no_currency($rate, $this->settings_info) . " LBP</small>";
        }
        echo "            \r\n            ";
        if ($data["settings"]["show_usd_in_invoice"] == 1 || $data["settings"]["show_usd_in_invoice"] == 2) {
            echo "            <tr>\r\n                <td  style=\"width: 150px;font-size: 15px;line-height: 22px;text-align: right\">\r\n                    <b>Total USD amount:&nbsp;</b>\r\n                <td>\r\n                    <td colspan=\"2\" style=\"width: 160px;font-size: 15px;text-align: left\">\r\n                ";
            echo self::value_format_custom_no_currency($total_price, $this->settings_info);
            echo "  USD \r\n                 <td>\r\n            </tr>\r\n            \r\n            ";
        }
        echo "            \r\n            ";
        if ($data["settings"]["show_usd_in_invoice"] == 1 || $data["settings"]["show_usd_in_invoice"] == 0) {
            echo "            \r\n            <tr>\r\n                <td  style=\"width: 150px;font-size: 15px;line-height: 22px;text-align: right\">\r\n                    <b>RATE:&nbsp;</b>\r\n                <td>\r\n                    <td colspan=\"2\" style=\"width: 160px;font-size: 15px;text-align: left\">\r\n                ";
            echo $usd_rate;
            echo "   \r\n                 <td>\r\n            </tr>\r\n            <tr>\r\n                <td  style=\"width: 150px;font-size: 15px;line-height: 22px;text-align: right\">\r\n                    <b>Total LBP amount:&nbsp;</b>\r\n                <td>\r\n                    <td colspan=\"2\" style=\"width: 160px;font-size: 15px;text-align: left\">\r\n                ";
            echo self::value_format_custom_no_currency($total_price * $rate, $this->settings_info);
            echo " LBP\r\n                 <td>\r\n            </tr>\r\n            ";
        }
        echo " \r\n            \r\n            ";
    }
    echo "   \r\n        </table>\r\n        ";
}
echo "   \r\n        \r\n\t";
if ($data["gift_print"] == 0) {
    echo "        <table style=\"width: 100%; margin-top: 10px; border: 1px solid #000\">\r\n            <tr>\r\n                <td><b>IN USD:</b> ";
    echo number_format($data["in_out"][0]["cash_usd"], 2);
    echo "</td>\r\n                <td><b>OUT USD:</b> ";
    echo number_format($data["in_out"][0]["returned_cash_usd"], 2);
    echo "</td>\r\n            </tr>\r\n            <tr>\r\n                <td><b>IN LBP:</b> ";
    echo number_format($data["in_out"][0]["cash_lbp"], 0);
    echo "</td>\r\n                \r\n                <td><b>OUT LBP:</b> ";
    echo number_format($data["in_out"][0]["returned_cash_lbp"], 0);
    echo "</td>\r\n            </tr>\r\n        </table>\r\n        ";
}
echo "        \r\n        ";
if (0 < strlen($data["invoice"][0]["payment_note"]) && $data["settings"]["invoice_note"] == 1) {
    echo "        <table style=\"width: 100%; margin-top: 10px; border: 1px solid #000\">\r\n            <tr>\r\n                <td><b>Note</b></td>\r\n            </tr>\r\n            <tr>\r\n                <td>";
    echo $data["invoice"][0]["payment_note"];
    echo "</td>\r\n            </tr>\r\n        </table>\r\n        ";
}
echo "        \r\n        ";
if (0 < count($data["imei"])) {
    echo "        \r\n        <table style=\"width: 100%; margin-top: 10px; border: 1px solid #000\">\r\n            <tr>\r\n                <td colspan=\"2\"><b>IMEI INFO</b></td>\r\n            </tr>\r\n            ";
    for ($i = 0; $i < count($data["imei"]); $i++) {
        echo "            <tr>\r\n                <td>\r\n                    ";
        if (0 < strlen($data["imei"][$i]["code1"])) {
            $getItems = $data["items_info_class"]->get_item($data["imei"][$i]["item_id"]);
            $description_item = $getItems[0]["description"];
            echo $description_item . ": " . $data["imei"][$i]["code1"] . "<br/>";
        }
        if (0 < strlen($data["imei"][$i]["code2"])) {
            $getItems = $data["items_info_class"]->get_item($data["imei"][$i]["item_id"]);
            $description_item = $getItems[0]["description"];
            echo $description_item . ": " . $data["imei"][$i]["code2"] . "<br/>";
        }
        echo "                </td>\r\n            </tr>\r\n            ";
    }
    echo "        </table>\r\n        \r\n        ";
}
echo "        \r\n        \r\n        ";
if ($data["invoice"][0]["delivery"] == 1) {
    echo "        \r\n        <table style=\"width: 100%; margin-top: 10px; border: 1px solid #000\">\r\n            <tr>\r\n                <td>\r\n                    <b>Delivery Information</b>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td colspan=\"2\"><b>Reference:</b> ";
    echo $data["invoice"][0]["delivery_ref"];
    echo "</td>\r\n            </tr>\r\n            <tr>\r\n                <td colspan=\"2\"><b>Fee:</b> ";
    echo self::global_number_formatter($data["invoice"][0]["delivery_cost"], $this->settings_info);
    echo "                \r\n                \r\n                ";
    if ($data["currency_system_default"] == 1) {
        echo "USD";
    } else {
        echo "LBP";
    }
    echo "</td>\r\n            </tr>\r\n            \r\n        </table>\r\n        \r\n        ";
}
echo "        \r\n        \r\n        <table style=\"width: 100%; margin-top: 20px; \">\r\n            <tr>\r\n                <td style=\"font-size: 18px; direction: ";
echo $this->settings_info["footer_direction"];
echo "\">\r\n                   ";
echo $this->settings_info["invoice_footer"];
echo "                </td>\r\n            </tr>\r\n        </table>\r\n    </section>\r\n  </body>\r\n    \r\n \r\n</html>\r\n";

?>