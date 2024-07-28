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
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n\n<html>\n    <head>\n        <title>Manual Report</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\n\n        <!-- Normalize or reset CSS with your favorite library -->\n  \n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        \n        <style type=\"text/css\">\n            \n            @font-face {\n                font-family: 'AlexandriaFLF';\n                    src: url('application/mvc/views/custom_libraries/font/Merchant.ttf') format('truetype');\n                font-weight: normal;\n                font-style: normal;\n                  }\n      \n            @page { margin: 0 }\n            body { \n                margin: 0 ;\n                \n                /*font-family: 'DroidArabicKufiRegular';*/\n            }\n            \n            .sheet {\n              margin: 0;\n              overflow: hidden;\n              position: relative;\n              box-sizing: border-box;\n              page-break-after: always;\n            }\n\n            /** Paper sizes **/\n            body.A3               .sheet { width: 297mm; height: 419mm }\n            body.A3.landscape     .sheet { width: 420mm; height: 296mm }\n            body.A4               .sheet { width: 210mm; height: 296mm }\n            body.A4.landscape     .sheet { width: 297mm; height: 209mm }\n            body.A5               .sheet { width: 148mm; height: 209mm }\n            body.A5.landscape     .sheet { width: 210mm; height: 147mm }\n            body.letter           .sheet { width: 216mm; height: 279mm }\n            body.letter.landscape .sheet { width: 280mm; height: 215mm }\n            body.legal            .sheet { width: 216mm; height: 356mm }\n            body.legal.landscape  .sheet { width: 357mm; height: 215mm }\n            \n            body.POS8             .sheet { width: 80mm;  }\n\n            /** Padding area **/\n            .sheet.padding-1mm { padding: 1mm }\n            .sheet.padding-5mm { padding: 5mm }\n            .sheet.padding-10mm { padding: 10mm }\n            .sheet.padding-15mm { padding: 15mm }\n            .sheet.padding-20mm { padding: 20mm }\n            .sheet.padding-25mm { padding: 25mm }\n\n            /** For screen preview **/\n            @media screen {\n              body { background: #e0e0e0 ;}\n              .sheet {\n                background: white;\n                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\n                margin: 0mm auto;\n              }\n            }\n\n            /** Fix for Chrome issue #273306 **/\n            @media print {\n                body.A3.landscape { width: 420mm }\n                body.A3, body.A4.landscape { width: 297mm }\n                body.A4, body.A5.landscape { width: 210mm }\n                body.A5                    { width: 148mm }\n                body.POS8                  { width: 80mm }\n                body.letter, body.legal    { width: 216mm }\n                body.letter.landscape      { width: 280mm }\n                body.legal.landscape       { width: 357mm }\n            }\n            \n            .line{\n                width: 100% !important;\n                height: 1px;\n                border-bottom: 1px solid #000;\n            }\n        </style>\n        \n        <style>\n            @page { size: POS8}\n\n            @media print {\n                body{\n                    \n                }\n            }\n            \n            .fmid{\n                font-size: 13px;\n            }\n            \n            .flg{\n                font-size: 16px;\n            }\n            \n            .fsm{\n                font-size: 12px;\n            }\n            \n            li{\n                font-size: 12px;\n            }\n        </style>\n        \n        <script type=\"text/javascript\">\n            \$(document).ready(function () {\n                //window.print();\n                setTimeout(function(){\n                    //window.close();\n                },3000);\n            });\n        </script>\n        \n    </head>\n    <body class=\"POS8\">\n\n  <!-- Each sheet element should have the class \"sheet\" -->\n  <!-- \"padding-**mm\" is optional: you can set 10, 15, 20 or 25 -->\n    ";
$total_in_lbp = 0;
$total_in_usd = 0;
$total_out_lbp = 0;
$total_out_usd = 0;
echo "    <section class=\"sheet padding-1mm\">\n        <table style=\"width: 100%; margin-bottom: 10px;\">\n            ";
if ($this->settings_info["hide_shop_name_on_invoice"] == 0) {
    echo "            <tr>\n                <td class=\"flg\" style=\"text-align: center;font-weight: bold;\">\n                    ";
    echo $this->settings_info["shop_name"];
    echo "                </td>\n            </tr>\n            ";
}
echo " \n            ";
if (0 < strlen($this->settings_info["address"])) {
    echo "            <tr>\n                <td class=\"flg\" style=\"text-align: center;\">\n                    ";
    echo $this->settings_info["address"];
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            ";
if (0 < strlen($this->settings_info["phone_nb"])) {
    echo "            <tr>\n                <td class=\"flg\" style=\"text-align: center;\">\n                    ";
    echo $this->settings_info["phone_nb"];
    echo "                </td>\n            </tr>\n            ";
}
echo "            \n            \n        </table>\n        \n        <table style=\"width: 100%; border: 1px dashed #000\">\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; padding-right: 10px; font-weight: bold\">\n                    Cashbox ID:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left;\">\n                   ";
echo $data["cashbox_id"];
echo " \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                    Operator:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                   ";
echo $data["operator"];
echo " \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                    Start USD:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                   ";
echo number_format($data["start_cashbox"], 2);
echo " \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                    Start LBP:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                   ";
echo number_format($data["cashbox_lbp"], 0);
echo " \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; padding-right: 10px; font-weight: bold\">\n                    Open Date/Time:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left;\">\n                   ";
echo $data["start_report_date"];
echo " \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; padding-right: 10px; font-weight: bold\">\n                    Print Date:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left;\">\n                   ";
echo date("Y-m-d H:i:s");
echo " \n                </td>\n            </tr>\n            \n        </table>\n        \n        ";
if (0 < count($data["invoices"])) {
    echo "        <table style=\"width: 100%; margin-top: 20px;\">\n            <tr>\n                <td class=\"fmid\" colspan=\"2\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                   CASH SALES INVOICES\n                </td>\n            </tr>\n        \n        ";
    for ($i = 0; $i < count($data["invoices"]); $i++) {
        if ($data["invoices"][$i]["is_cash"] == 1) {
            echo "            <tr>\n                <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                    ";
            echo "<b>" . $data["invoices"][$i]["id"] . "</b> <span class='fsm'>" . $data["invoices"][$i]["creation_date"] . "</span>";
            echo "                </td>\n            </tr>\n            ";
            for ($k = 0; $k < count($data["invoices"][$i]["invoice_items"]); $k++) {
                echo "            <tr>\n                <td class=\"fmid\" style=\"text-align: left; \" colspan=\"2\">\n                    <ul style=\"margin-bottom: 1px;padding-left: 20px;\">\n                        <li>";
                $dsc = "";
                if (0 < $data["invoices"][$i]["invoice_items"][$k]["discount"]) {
                    $dsc = "(D:" . number_format(floatval($data["invoices"][$i]["invoice_items"][$k]["discount"]), 2) . ")";
                }
                echo "#" . $data["invoices"][$i]["invoice_items"][$k]["item_id"] . " " . $data["invoices"][$i]["invoice_items"][$k]["description"] . " (Q:" . floatval($data["invoices"][$i]["invoice_items"][$k]["qty"]) . ") " . $dsc . " (UP:" . floatval($data["invoices"][$i]["invoice_items"][$k]["final_price_disc_qty"] / $data["invoices"][$i]["invoice_items"][$k]["qty"]) . ")";
                echo "</li>\n                    </ul>\n                </td>\n            </tr>\n            ";
            }
            echo "            ";
            if (0 < count($data["invoices"][$i]["returned"])) {
                for ($k = 0; $k < count($data["invoices"][$i]["returned"]); $k++) {
                    echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                        <ul style=\"margin-bottom: 1px;padding-left: 20px;\">\n                            <li><b>R</b>-IT-";
                    echo $data["invoices"][$i]["returned"][$k]["item_id"];
                    echo " ";
                    echo "#" . $data["invoices"][$i]["returned"][$k]["selling_price"];
                    echo " ";
                    echo "" . $data["invoices"][$i]["returned"][$k]["item_info"][0]["description"];
                    echo "                             </li>\n                                                       \n                        </ul>\n                    </td>\n                </tr>\n                ";
                }
                echo "            ";
            }
            echo "                  \n            <tr>\n                <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                    <b>Amount: </b>";
            echo number_format($data["invoices"][$i]["total_value"], 2) . $data["symbol"];
            echo "                </td>\n            </tr>\n            \n            \n            ";
            if ($data["invoices"][$i]["discount"] != 0) {
                echo "            <tr>\n                <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                    <b>Discount: </b>";
                echo number_format($data["invoices"][$i]["discount"], 2) . $data["symbol"];
                echo "                </td>\n            </tr>\n            \n            <tr>\n                <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                    <b>Total Amount: </b>";
                echo number_format($data["invoices"][$i]["total_value"] - $data["invoices"][$i]["discount"], 2);
                echo "                </td>\n            </tr>\n            ";
            }
            echo "            \n            ";
            if (0 < $data["invoices"][$i]["rate"]) {
                echo "            <tr>\n                <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                    <b>Rate: </b>1 USD = ";
                echo number_format($data["invoices"][$i]["rate"], 0);
                echo " LBP\n                </td>\n            </tr>\n            ";
            }
            echo "\n            <tr>\n                <td class=\"fmid\" colspan=\"2\" style=\"border-top: 1px dotted #000; font-size: 12px;\">\n                    Original Payment\n                    ";
            if ($data["invoices"][$i]["alert"] == 1) {
                echo "<b>ALERT!!!!!!!!!!!!!!!</b>&nbsp;&nbsp; <b>Diff:</b> " . number_format($data["invoices"][$i]["total_value"] - $data["invoices"][$i]["alert_v"], 2);
            }
            echo "                </td>\n            </tr>\n             ";
            if (0 < $data["invoices"][$i]["cash_lbp"] || 0 < $data["invoices"][$i]["cash_usd"]) {
                $total_in_lbp += $data["invoices"][$i]["cash_lbp"];
                $total_in_usd += $data["invoices"][$i]["cash_usd"];
                echo "             <tr>\n                ";
                if (0 < $data["invoices"][$i]["cash_lbp"]) {
                    echo "                <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                    <b>CIN LBP: </b>";
                    echo number_format($data["invoices"][$i]["cash_lbp"], 0);
                    echo "                </td>\n                ";
                }
                echo "                \n                ";
                if (0 < $data["invoices"][$i]["cash_usd"]) {
                    echo "                <td class=\"fmid\" style=\"text-align: left;\">\n                    <b>CIN USD: </b>";
                    echo number_format($data["invoices"][$i]["cash_usd"], 2);
                    echo "                </td>\n                ";
                }
                echo "            </tr> \n            ";
            }
            echo "            \n            \n            ";
            if (0 < $data["invoices"][$i]["cash_lbp_out"] || 0 < $data["invoices"][$i]["cash_usd_out"]) {
                $total_out_lbp += $data["invoices"][$i]["cash_lbp_out"];
                $total_out_usd += $data["invoices"][$i]["cash_usd_out"];
                echo "             <tr>\n                ";
                if (0 < $data["invoices"][$i]["cash_lbp_out"]) {
                    echo "                <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                    <b>COUT LBP: </b>";
                    echo number_format($data["invoices"][$i]["cash_lbp_out"], 0);
                    echo "                </td>\n                ";
                }
                echo "                \n                ";
                if (0 < $data["invoices"][$i]["cash_usd_out"]) {
                    echo "                <td class=\"fmid\" style=\"text-align: left;\">\n                    <b>COUT USD: </b>";
                    echo number_format($data["invoices"][$i]["cash_usd_out"], 2);
                    echo "                </td>\n                ";
                }
                echo "            </tr> \n            ";
            }
            echo "            \n            ";
            if (0 < count($data["invoices"][$i]["cashin_out"])) {
                echo "            <tr>\n                <td class=\"fmid\" colspan=\"2\" style=\"border-top: 1px dotted #000;font-size: 12px;\">\n                    Change/Return\n                </td>\n            </tr>\n            ";
            }
            echo "            \n            ";
            for ($c = 0; $c < count($data["invoices"][$i]["cashin_out"]); $c++) {
                $cio = $data["invoices"][$i]["cashin_out"][$c];
                echo " \n            \n            ";
                if (0 < $cio["cash_lbp_in"] || 0 < $cio["cash_usd_in"]) {
                    $total_in_lbp += $cio["cash_lbp_in"];
                    $total_in_usd += $cio["cash_usd_in"];
                    echo "             <tr>\n                ";
                    if (0 < $cio["cash_lbp_in"]) {
                        echo "                <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                    <b>CIN LBP: </b>";
                        echo number_format($cio["cash_lbp_in"], 0);
                        echo "                </td>\n                ";
                    }
                    echo "                ";
                    if (0 < $cio["cash_usd_in"]) {
                        echo "                <td class=\"fmid\" style=\"text-align: left;\">\n                    <b>CIN USD: </b>";
                        echo number_format($cio["cash_usd_in"], 2);
                        echo "                </td>\n                ";
                    }
                    echo "            </tr>\n            ";
                }
                echo "            \n\n            ";
                if (0 < $cio["returned_cash_lbp"] || 0 < $cio["returned_cash_usd"]) {
                    $total_out_lbp += $cio["returned_cash_lbp"];
                    $total_out_usd += $cio["returned_cash_usd"];
                    echo "            <tr>\n                ";
                    if (0 < $cio["returned_cash_lbp"]) {
                        echo "                <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                    <b>COUT LBP: </b>";
                        echo number_format($cio["returned_cash_lbp"], 0);
                        echo "                </td>\n                ";
                    }
                    echo "                ";
                    if (0 < $cio["returned_cash_usd"]) {
                        echo "                <td class=\"fmid\" style=\"text-align: left;\">\n                    <b>COUT USD: </b>";
                        echo number_format($cio["returned_cash_usd"], 2);
                        echo "                </td>\n                ";
                    }
                    echo "            </tr>\n            ";
                }
                echo "                \n            ";
            }
            echo "          \n            <tr>\n                <td colspan=\"2\" class=\"fmid\" style=\"text-align: left; border-top: 1px solid #000; \">&nbsp;</td>\n            </tr>\n        ";
        }
    }
    echo "        </table>\n        ";
}
echo "        \n        ";
if (0 < $data["invoices_debt_exist"]) {
    echo "           \n            <table style=\"width: 100%; margin-top: 10px;\">\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                       DEBTS INVOICES\n                    </td>\n                </tr>\n\n            ";
    for ($i = 0; $i < count($data["invoices"]); $i++) {
        if ($data["invoices"][$i]["is_cash"] == 0) {
            echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\">\n                        ";
            echo "<b>" . $data["invoices"][$i]["id"] . "</b> <span class='fsm'>" . $data["invoices"][$i]["creation_date"] . "</span>";
            echo "                    </td>\n                </tr>\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">\n                        <b>Total Amount: </b>";
            echo number_format($data["invoices"][$i]["total_value"], 2) . $data["symbol"];
            echo "                    </td>\n                </tr>\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n            ";
        }
    }
    echo "            </table>\n            ";
}
echo "        \n        \n        ";
if (0 < count($data["return_another_shift"])) {
    echo "           \n            <table style=\"width: 100%; margin-top: 10px;\">\n                <tr>\n                    <td colspan=\"2\" class=\"fmid\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                       RETURNED FROM ANOTHER SHIFT\n                    </td>\n                </tr>\n\n            ";
    for ($i = 0; $i < count($data["return_another_shift"]); $i++) {
        $total_in_lbp += $data["return_another_shift"][$i]["cash_lbp_in"];
        $total_in_usd += $data["return_another_shift"][$i]["cash_usd_in"];
        $total_out_lbp += $data["return_another_shift"][$i]["returned_cash_lbp"];
        $total_out_usd += $data["return_another_shift"][$i]["returned_cash_usd"];
        echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                        <b>Invoice ID: </b>";
        echo $data["return_another_shift"][$i]["invoice_id"];
        echo "&nbsp;&nbsp;&nbsp;<b>Item ID: </b>";
        echo $data["return_another_shift"][$i]["item_id"];
        echo "                    </td>\n                </tr>\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \" colspan=\"2\">\n                        <b>Amount: </b>";
        echo number_format($data["return_another_shift"][$i]["selling_price"], 2);
        echo "</b>\n                    </td>\n                </tr>\n                \n                <tr>\n                ";
        if (0 < $data["return_another_shift"][$i]["returned_cash_lbp"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>COUT LBP: </b>";
            echo number_format($data["return_another_shift"][$i]["returned_cash_lbp"], 0);
            echo "                    </td>\n                    ";
        }
        echo "\n                    ";
        if (0 < $data["return_another_shift"][$i]["returned_cash_usd"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>COUT USD: </b>";
            echo number_format($data["return_another_shift"][$i]["returned_cash_usd"], 2);
            echo "                    </td>\n                    ";
        }
        echo "                </tr>\n                \n                <tr>\n                ";
        if (0 < $data["return_another_shift"][$i]["cash_lbp_in"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>CIN LBP: </b>";
            echo number_format($data["return_another_shift"][$i]["cash_lbp_in"], 0);
            echo "                    </td>\n                    ";
        }
        echo "\n                    ";
        if (0 < $data["return_another_shift"][$i]["cash_usd_in"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>CIN USD: </b>";
            echo number_format($data["return_another_shift"][$i]["cash_usd_in"], 2);
            echo "                    </td>\n                    ";
        }
        echo "                </tr>\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n            ";
    }
    echo "            </table>\n            ";
}
echo "        \n        \n        ";
if (0 < count($data["changes_another_shift"])) {
    echo "           \n            <table style=\"width: 100%; margin-top: 10px;\">\n                <tr>\n                    <td colspan=\"2\" class=\"fmid\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                       CHANGES FROM ANOTHER SHIFT\n                    </td>\n                </tr>\n\n            ";
    for ($i = 0; $i < count($data["changes_another_shift"]); $i++) {
        $total_in_lbp += $data["changes_another_shift"][$i]["cash_lbp_in"];
        $total_in_usd += $data["changes_another_shift"][$i]["cash_usd_in"];
        $total_out_lbp += $data["changes_another_shift"][$i]["returned_cash_lbp"];
        $total_out_usd += $data["changes_another_shift"][$i]["returned_cash_usd"];
        echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                        <b>Invoice ID: </b>";
        echo $data["changes_another_shift"][$i]["invoice_id"];
        echo "&nbsp;&nbsp;&nbsp;\n                    </td>\n                </tr>\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \" colspan=\"2\">\n                        <b>Difference  Amount: </b>";
        echo number_format(abs($data["changes_another_shift"][$i]["added_value"] - $data["changes_another_shift"][$i]["return_value"]), 2);
        echo "</b>\n                    </td>\n                </tr>\n                \n                <tr>\n                ";
        if (0 < $data["changes_another_shift"][$i]["returned_cash_lbp"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>COUT LBP: </b>";
            echo number_format($data["changes_another_shift"][$i]["returned_cash_lbp"], 0);
            echo "                    </td>\n                    ";
        }
        echo "\n                    ";
        if (0 < $data["changes_another_shift"][$i]["returned_cash_usd"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>COUT USD: </b>";
            echo number_format($data["changes_another_shift"][$i]["returned_cash_usd"], 2);
            echo "                    </td>\n                    ";
        }
        echo "                </tr>\n                \n                <tr>\n                ";
        if (0 < $data["changes_another_shift"][$i]["cash_lbp_in"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>CIN LBP: </b>";
            echo number_format($data["changes_another_shift"][$i]["cash_lbp_in"], 0);
            echo "                    </td>\n                    ";
        }
        echo "\n                    ";
        if (0 < $data["changes_another_shift"][$i]["cash_usd_in"]) {
            echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>CIN USD: </b>";
            echo number_format($data["changes_another_shift"][$i]["cash_usd_in"], 2);
            echo "                    </td>\n                    ";
        }
        echo "                </tr>\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n            ";
    }
    echo "            </table>\n            ";
}
echo "        \n        \n        \n        ";
if (0 < count($data["deleted_invoices"])) {
    echo "           \n            <table style=\"width: 100%; margin-top: 10px;\">\n                <tr>\n                    <td class=\"fmid\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                       DELETED INVOICES\n                    </td>\n                </tr>\n\n            ";
    for ($i = 0; $i < count($data["deleted_invoices"]); $i++) {
        echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>INVOICE ID:</b> ";
        echo $data["deleted_invoices"][$i]["id"];
        echo "                    </td>\n                </tr>\n            ";
    }
    echo "            </table>\n            ";
}
echo "        \n        \n            ";
if (0 < count($data["expenses"])) {
    echo "           \n            <table style=\"width: 100%; margin-top: 10px;\">\n                <tr>\n                    <td colspan=\"2\" class=\"fmid\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                       EXPENSES\n                    </td>\n                </tr>\n\n                ";
    for ($i = 0; $i < count($data["expenses"]); $i++) {
        $total_out_lbp += $data["expenses"][$i]["returned_cash_lbp"];
        $total_out_usd += $data["expenses"][$i]["returned_cash_usd"];
        $total_in_lbp += $data["expenses"][$i]["cash_lbp_in"];
        $total_in_usd += $data["expenses"][$i]["cash_usd_in"];
        echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                        ";
        echo $data["expenses"][$i]["description"];
        echo "                    </td>\n                </tr>\n                \n                ";
        if (0 < $data["expenses"][$i]["cash_lbp_in"] || 0 < $data["expenses"][$i]["cash_usd_in"]) {
            echo "                <tr>\n                    ";
            if (0 < $data["expenses"][$i]["cash_lbp_in"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>CIN LBP: </b>";
                echo number_format($data["expenses"][$i]["cash_lbp_in"], 0);
                echo "                    </td>\n                    ";
            }
            echo "                    ";
            if (0 < $data["expenses"][$i]["cash_usd_in"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>CIN USD: </b>";
                echo number_format($data["expenses"][$i]["cash_usd_in"], 2);
                echo "                    </td>\n                    ";
            }
            echo "                </tr>\n                ";
        }
        echo "                \n                \n                ";
        if (0 < $data["expenses"][$i]["returned_cash_lbp"] || 0 < $data["expenses"][$i]["returned_cash_usd"]) {
            echo "                \n                <tr>\n                    ";
            if (0 < $data["expenses"][$i]["returned_cash_lbp"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>COUT LBP: </b>";
                echo number_format($data["expenses"][$i]["returned_cash_lbp"], 0);
                echo "                    </td>\n                    ";
            }
            echo "                    ";
            if (0 < $data["expenses"][$i]["returned_cash_usd"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>COUT USD: </b>";
                echo number_format($data["expenses"][$i]["returned_cash_usd"], 2);
                echo "                    </td>\n                    ";
            }
            echo "                </tr>\n                    \n                ";
        }
        echo "            \n                ";
    }
    echo "                \n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n            </table>\n            ";
}
echo "        \n        \n        \n        ";
if (0 < count($data["customers_payments"])) {
    echo "           \n            <table style=\"width: 100%; margin-top: 10px;\">\n                <tr>\n                    <td colspan=\"2\" class=\"fmid\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                       CUSTOMERS PAYMENTS\n                    </td>\n                </tr>\n\n                ";
    for ($i = 0; $i < count($data["customers_payments"]); $i++) {
        $total_in_lbp += $data["customers_payments"][$i]["cash_in_lbp"];
        $total_in_usd += $data["customers_payments"][$i]["cash_in_usd"];
        $total_in_lbp -= $data["customers_payments"][$i]["returned_lbp"];
        $total_in_usd -= $data["customers_payments"][$i]["returned_usd"];
        echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                        ";
        echo $data["customers_payments"][$i]["name"];
        echo "                    </td>\n                </tr>\n                \n                ";
        if (0 < $data["customers_payments"][$i]["cash_in_lbp"] || 0 < $data["customers_payments"][$i]["cash_in_usd"]) {
            echo "                <tr>\n                    ";
            if (0 < $data["customers_payments"][$i]["cash_in_lbp"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>CIN LBP: </b>";
                echo number_format($data["customers_payments"][$i]["cash_in_lbp"], 0);
                echo "                    </td>\n                    ";
            }
            echo "                    ";
            if (0 < $data["customers_payments"][$i]["cash_in_usd"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>CIN USD: </b>";
                echo number_format($data["customers_payments"][$i]["cash_in_usd"], 2);
                echo "                    </td>\n                    ";
            }
            echo "                </tr>\n                ";
        }
        echo "                \n                \n                ";
        if (0 < $data["customers_payments"][$i]["returned_lbp"] || 0 < $data["customers_payments"][$i]["returned_usd"]) {
            echo "                \n                <tr>\n                    ";
            if (0 < $data["customers_payments"][$i]["returned_lbp"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>COUT LBP: </b>";
                echo number_format($data["customers_payments"][$i]["returned_lbp"], 0);
                echo "                    </td>\n                    ";
            }
            echo "                    ";
            if (0 < $data["customers_payments"][$i]["returned_usd"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>COUT USD: </b>";
                echo number_format($data["customers_payments"][$i]["returned_usd"], 2);
                echo "                    </td>\n                    ";
            }
            echo "                </tr>\n                \n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n                    \n                ";
        }
        echo "            \n                ";
    }
    echo "                \n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n            </table>\n            ";
}
echo "        \n        \n        \n        ";
if (0 < count($data["suppliers_payments"])) {
    echo "           \n            <table style=\"width: 100%; margin-top: 10px;\">\n                <tr>\n                    <td colspan=\"2\" class=\"fmid\" style=\"text-align: center; font-weight: bold; text-decoration:underline \">\n                       SUPPLIERS PAYMENTS\n                    </td>\n                </tr>\n\n                ";
    for ($i = 0; $i < count($data["suppliers_payments"]); $i++) {
        echo "                <tr>\n                    <td class=\"fmid\" style=\"text-align: left;\" colspan=\"2\">\n                        ";
        echo $data["suppliers_payments"][$i]["name"];
        echo "                    </td>\n                </tr>\n                \n                ";
        if (0 < $data["suppliers_payments"][$i]["cash_in_lbp"] || 0 < $data["suppliers_payments"][$i]["cash_in_usd"]) {
            echo "                <tr>\n                    ";
            if (0 < $data["suppliers_payments"][$i]["cash_in_lbp"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>CIN LBP: </b>";
                echo number_format($data["suppliers_payments"][$i]["cash_in_lbp"], 0);
                echo "                    </td>\n                    ";
            }
            echo "                    ";
            if (0 < $data["suppliers_payments"][$i]["cash_in_usd"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>CIN USD: </b>";
                echo number_format($data["suppliers_payments"][$i]["cash_in_usd"], 2);
                echo "                    </td>\n                    ";
            }
            echo "                </tr>\n                ";
        }
        echo "                \n                \n                ";
        if (0 < $data["suppliers_payments"][$i]["returned_lbp"] || 0 < $data["suppliers_payments"][$i]["returned_usd"]) {
            $total_out_lbp += $data["suppliers_payments"][$i]["returned_lbp"];
            $total_out_usd += $data["suppliers_payments"][$i]["returned_usd"];
            echo "                \n                <tr>\n                    ";
            if (0 < $data["suppliers_payments"][$i]["returned_lbp"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left; width: 50%\">\n                        <b>COUT LBP: </b>";
                echo number_format($data["suppliers_payments"][$i]["returned_lbp"], 0);
                echo "                    </td>\n                    ";
            }
            echo "                    ";
            if (0 < $data["suppliers_payments"][$i]["returned_usd"]) {
                echo "                    <td class=\"fmid\" style=\"text-align: left;\">\n                        <b>COUT USD: </b>";
                echo number_format($data["suppliers_payments"][$i]["returned_usd"], 2);
                echo "                    </td>\n                    ";
            }
            echo "                </tr>\n                \n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n                    \n                ";
        }
        echo "            \n                ";
    }
    echo "                \n                <tr>\n                    <td class=\"fmid\" style=\"text-align: left; \">&nbsp;</td>\n                </tr>\n            </table>\n            ";
}
echo "       \n        \n        <table style=\"width: 100%; border: 1px dashed #000; margin-top: 10px;\">\n            ";
if (0 < $data["total_alert"]) {
    echo " \n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                    TOTAL ALERTS:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                    ";
    echo number_format($data["total_alert"], 0);
    echo " \n                    \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" colspan=\"2\">\n                   &nbsp;\n                </td>\n            </tr>\n            ";
}
echo "            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                    Total IN USD:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                    ";
echo number_format($total_in_usd, 2);
echo " \n                    \n                </td>\n            </tr> \n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                     Total IN LBP:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                   ";
echo number_format($total_in_lbp, 0);
echo " \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                    Total OUT USD:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                    ";
echo number_format($total_out_usd, 2);
echo " \n                    \n                </td>\n            </tr> \n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                     Total OUT LBP:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                   ";
echo number_format($total_out_lbp, 0);
echo " \n                </td>\n            </tr>\n            <tr>\n                <td class=\"fmid\" colspan=\"2\">\n                   &nbsp;\n                </td>\n            </tr>\n\n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                    NET USD:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                    ";
echo number_format($total_in_usd - $total_out_usd + $data["start_cashbox"], 2);
echo " \n                    \n                </td>\n            </tr> \n            <tr>\n                <td class=\"fmid\" style=\"text-align: right; width: 140px;padding-right: 10px;font-weight: bold\">\n                     NET LBP:\n                </td>\n                <td class=\"fmid\" style=\"text-align: left; \">\n                   ";
echo number_format($total_in_lbp - $total_out_lbp + $data["cashbox_lbp"], 0);
echo " \n                </td>\n            </tr>\n        </table>\n    </section>\n  </body>\n    \n \n</html>\n";

?>