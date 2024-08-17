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
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n";
$WIDTH_BC = "" . $data["print_barcode_in_browser_paper_width"];
$HEIGHT_BC = "" . $data["print_barcode_in_browser_paper_height"];
echo "<html>\n    <head>\n        <title>Barcode</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\n\n        <!-- Normalize or reset CSS with your favorite library -->\n  \n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        \n        <script src=\"libraries/JsBarcode-master/dist/JsBarcode.all.js\"></script>\n        <style type=\"text/css\">\n            \n            @font-face {\n                font-family: 'AlexandriaFLF';\n                    src: url('application/mvc/views/custom_libraries/font/Merchant.ttf') format('truetype');\n                font-weight: normal;\n                font-style: normal;\n                  }\n      \n            @page { margin: 0 }\n            body { \n                margin: 0 ;\n                /* font-family: AlexandriaFLF !important; */\n                /*font-family: 'DroidArabicKufiRegular';*/\n                \n            }\n            \n            .sheet {\n              margin: 0;\n              overflow: hidden;\n              position: relative;\n              box-sizing: border-box;\n              page-break-after: always;\n            }\n\n            /** Paper sizes **/\n            body.A3               .sheet { width: 297mm; height: 419mm }\n            body.A3.landscape     .sheet { width: 420mm; height: 296mm }\n            body.A4               .sheet { width: 210mm; height: 296mm }\n            body.A4.landscape     .sheet { width: 297mm; height: 209mm }\n            body.A5               .sheet { width: 148mm; height: 209mm }\n            body.A5.landscape     .sheet { width: 210mm; height: 147mm }\n            body.letter           .sheet { width: 216mm; height: 279mm }\n            body.letter.landscape .sheet { width: 280mm; height: 215mm }\n            body.legal            .sheet { width: 216mm; height: 356mm }\n            body.legal.landscape  .sheet { width: 357mm; height: 215mm }\n            \n            body.POS8             .sheet { width: 80mm;  }\n            body.BC            .sheet { width: ";
echo $WIDTH_BC;
echo "mm; height: ";
echo $HEIGHT_BC;
echo "mm  }\n\n            /** Padding area **/\n            .sheet.padding-1mm { padding: 1mm }\n            .sheet.padding-5mm { padding: 5mm }\n            .sheet.padding-10mm { padding: 10mm }\n            .sheet.padding-15mm { padding: 15mm }\n            .sheet.padding-20mm { padding: 20mm }\n            .sheet.padding-25mm { padding: 25mm }\n\n            /** For screen preview **/\n            @media screen {\n              body { background: #e0e0e0 ;}/* font-family: AlexandriaFLF !important; */\n              .sheet {\n                background: white;\n                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);\n                margin: 0mm auto;\n              }\n            }\n\n            /** Fix for Chrome issue #273306 **/\n            @media print {\n                body.A3.landscape { width: 420mm }\n                body.A3, body.A4.landscape { width: 297mm }\n                body.A4, body.A5.landscape { width: 210mm }\n                body.A5                    { width: 148mm }\n                body.POS8                  { width: 40mm }\n                body.BC                { width: ";
echo $WIDTH_BC;
echo "mm;height: ";
echo $HEIGHT_BC;
echo "mm  }\n                body.letter, body.legal    { width: 216mm }\n                body.letter.landscape      { width: 280mm }\n                body.legal.landscape       { width: 357mm }\n                \n            }\n            \n            .line{\n                width: 100% !important;\n                height: 1px;\n                border-bottom: 1px solid #000;\n            }\n        </style>\n        \n        <style>\n            @page { size: BC}\n\n            @media print {\n                body{\n                    \n                }\n            }\n        </style>\n    \n        <script type=\"text/javascript\">\n            \n            Number.prototype.zeroPadding = function(){\n                var ret = \"\" + this.valueOf();\n                return ret.length == 1 ? \"0\" + ret : ret;\n            };\n\n            function create_bc(bc,id){\n                JsBarcode(\"#barcode_\"+id, \"\"+bc, {\n                    format: \"EAN13\",width:1.4,height: 20,fontSize:10,marginLeft: 1,marginTop:2,font: \"cursive\", fontOptions: \"regular\"\n                }); \n            }\n            \n            function create_bc_upc(bc,id){\n                JsBarcode(\"#barcode_\"+id, \"\"+bc, {\n                    format: \"UPC\",width:1.4,height: 20,fontSize:10,marginLeft: 1,marginTop:2,font: \"cursive\", fontOptions: \"regular\"\n                }); \n            }\n            \n            function create_bc_CODE128C(bc,id){\n                JsBarcode(\"#barcode_\"+id, \"\"+bc, {\n                    format: \"CODE128C\",ean128: true,width:1.4,height: 20,fontSize:10,marginLeft: 1,marginTop:2,font: \"cursive\", fontOptions: \"regular\"\n                }); \n            }\n\n            \$( document ).ready(function() {\n                setTimeout(function(){\n                    window.print();\n                },400);\n            });\n\n        </script>\n        \n    </head>\n    <body class=\"BC\">\n        \n    ";
for ($i = 0; $i < count($data["items_to_print_details"]); $i++) {
    echo "    \n        \n\n    ";
    if ($data["barcode_type"] == "ean128") {
        echo "   \n    <section class=\"sheet padding-1mm\">\n        ";
        if ($data["size_enable"]) {
            echo "<span style='position:absolute;top:" . $data["size_y"] . "px;left:" . $data["size_x"] . "px;font-size:" . $data["size_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["size"] . "</span>";
        }
        echo "        ";
        if ($data["color_enable"]) {
            echo "<span style='position:absolute;top:" . $data["color_y"] . "px;left:" . $data["color_x"] . "px;font-size:" . $data["color_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["color"] . "</span>";
        }
        echo "        \n        ";
        if ($data["items_to_print_details"][$i]["enable_discount"]) {
            echo "<span style='position:absolute;top:" . $data["discount_y"] . "px;left:" . $data["discount_x"] . "px;font-size:" . $data["discount_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["discount"] . "</span>";
        }
        echo "        ";
        if ($data["items_to_print_details"][$i]["enable_discount"]) {
            echo "<span style='position:absolute;top:" . $data["price_after_discount_y"] . "px;left:" . $data["price_after_discount_x"] . "px;font-size:" . $data["price_after_discount_size"] . "px;'>" . $data["items_to_print_details"][$i]["price"] . "</span>";
        }
        echo "        ";
        if ($data["item_enable"]) {
            echo "<span style='position:absolute;top:" . $data["item_top"] . "px;left:" . $data["item_left"] . "px;font-size:" . $data["item_size"] . "px;'>" . $data["items_to_print_details"][$i]["description"] . "</span>";
        }
        echo "        ";
        if ($data["store_name_enable"]) {
            echo "<span style='position:absolute;top:" . $data["store_name_top"] . "px;left:" . $data["store_name_left"] . "px;font-size:" . $data["store_name_font_size"] . "px;font-weight:bold;'>" . $data["store_name"] . "</span>";
        }
        echo "        ";
        if ($data["price_enable"]) {
            echo "<span style='position:absolute;top:" . $data["price_top"] . "px;left:" . $data["price_left"] . "px;font-size:" . $data["price_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["price"] . "</span>";
        }
        echo "        <img style=\"position: absolute;left: ";
        echo $data["barcode_left"];
        echo "px;top: ";
        echo $data["barcode_top"];
        echo "px\" src=\"./barcodes/";
        echo $data["items_to_print_details"][$i]["barcode"];
        echo "\" />\n    </section>\n        \n    ";
    } else {
        if ($data["barcode_type"] == "upc") {
            echo "    <section class=\"sheet padding-1mm\" >\n        ";
            if ($data["size_enable"]) {
                echo "<span style='position:absolute;top:" . $data["size_y"] . "px;left:" . $data["size_x"] . "px;font-size:" . $data["size_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["size"] . "</span>";
            }
            echo "        ";
            if ($data["color_enable"]) {
                echo "<span style='position:absolute;top:" . $data["color_y"] . "px;left:" . $data["color_x"] . "px;font-size:" . $data["color_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["color"] . "</span>";
            }
            echo "        \n        ";
            if ($data["items_to_print_details"][$i]["enable_discount"]) {
                echo "<span style='position:absolute;top:" . $data["discount_y"] . "px;left:" . $data["discount_x"] . "px;font-size:" . $data["discount_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["discount"] . "</span>";
            }
            echo "        ";
            if ($data["items_to_print_details"][$i]["enable_discount"]) {
                echo "<span style='position:absolute;top:" . $data["price_after_discount_y"] . "px;left:" . $data["price_after_discount_x"] . "px;font-size:" . $data["price_after_discount_size"] . "px;'>" . $data["items_to_print_details"][$i]["price"] . "</span>";
            }
            echo "        \n        <div style=\"position: absolute;left: ";
            echo $data["barcode_left"];
            echo "px;top: ";
            echo $data["barcode_top"];
            echo "px;height:20px\"><svg  id=\"barcode_";
            echo $data["items_to_print_details"][$i]["barcode"];
            echo "\" ></svg></div>\n        ";
            if ($data["item_enable"]) {
                echo "<span style='position:absolute;top:" . $data["item_top"] . "px;left:" . $data["item_left"] . "px;font-size:" . $data["item_size"] . "px;'>" . $data["items_to_print_details"][$i]["description"] . "</span>";
            }
            echo "        ";
            if ($data["store_name_enable"]) {
                echo "<span style='position:absolute;top:" . $data["store_name_top"] . "px;left:" . $data["store_name_left"] . "px;font-size:" . $data["store_name_font_size"] . "px;font-weight:bold;'>" . $data["store_name"] . "</span>";
            }
            echo "        ";
            if ($data["price_enable"]) {
                echo "<span style='position:absolute;top:" . $data["price_top"] . "px;left:" . $data["price_left"] . "px;font-size:" . $data["price_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["price"] . "</span>";
            }
            echo "    </section>\n    <script type=\"text/javascript\">create_bc_upc(";
            echo $data["items_to_print_details"][$i]["barcode"];
            echo ",";
            echo $data["items_to_print_details"][$i]["barcode"];
            echo ");</script>\n    ";
        } else {
            if ($data["barcode_type"] == "CODE128C") {
                echo "    <section class=\"sheet padding-1mm\" >\n        ";
                if ($data["size_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["size_y"] . "px;left:" . $data["size_x"] . "px;font-size:" . $data["size_font_size"] . "px;'>SIZE:" . $data["items_to_print_details"][$i]["size"] . "</span>";
                }
                echo "        ";
                if ($data["color_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["color_y"] . "px;left:" . $data["color_x"] . "px;font-size:" . $data["color_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["color"] . "</span>";
                }
                echo "        ";
                if ($data["items_to_print_details"][$i]["enable_discount"]) {
                    echo "<span style='position:absolute;top:" . $data["discount_y"] . "px;left:" . $data["discount_x"] . "px;font-size:" . $data["discount_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["discount"] . "</span>";
                }
                echo "        ";
                if ($data["items_to_print_details"][$i]["enable_discount"]) {
                    echo "<span style='position:absolute;top:" . $data["price_after_discount_y"] . "px;left:" . $data["price_after_discount_x"] . "px;font-size:" . $data["price_after_discount_size"] . "px;'>" . $data["items_to_print_details"][$i]["final_price"] . " " . $data["default_currency_symbol"] . "</span>";
                }
                echo "        <div style=\"position: absolute;left: ";
                echo $data["barcode_left"];
                echo "px;top: ";
                echo $data["barcode_top"];
                echo "px;height:20px\"><svg  id=\"barcode_";
                echo $data["items_to_print_details"][$i]["barcode"];
                echo "\" ></svg></div>\n        ";
                if ($data["item_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["item_top"] . "px;left:" . $data["item_left"] . "px;font-size:" . $data["item_size"] . "px;'>" . $data["items_to_print_details"][$i]["description"] . "</span>";
                }
                echo "        ";
                if ($data["store_name_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["store_name_top"] . "px;left:" . $data["store_name_left"] . "px;font-size:" . $data["store_name_font_size"] . "px;font-weight:bold;'>" . $data["store_name"] . "</span>";
                }
                echo "        ";
                if ($data["enable_sku"]) {
                    echo "<span style='position:absolute;top:" . $data["sku_y"] . "px;left:" . $data["sku_x"] . "px;font-size:" . $data["sku_font_size"] . "px;font-weight:bold;'>" . $data["sku"] . "</span>";
                }
                echo "        ";
                if ($data["price_enable"]) {
                    $text_dec = "";
                    if ($data["items_to_print_details"][$i]["enable_discount"] == 1) {
                        $text_dec = ";text-decoration-line: line-through;";
                    }
                    echo "<span style='position:absolute;top:" . $data["price_top"] . "px;left:" . $data["price_left"] . "px;font-size:" . $data["price_font_size"] . "px;" . $text_dec . "'>" . $data["items_to_print_details"][$i]["price"] . " " . $data["default_currency_symbol"] . "</span>";
                }
                if($data["barcode_enable"]){
                    echo "    </section>\n    <script type=\"text/javascript\">create_bc_CODE128C(";
                    echo $data["items_to_print_details"][$i]["barcode"];
                    echo ",";
                    echo $data["items_to_print_details"][$i]["barcode"];
                    echo ");</script>\n    ";
                }
            } else {
                echo "    <section class=\"sheet padding-1mm\" >\n        ";
                if ($data["size_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["size_y"] . "px;left:" . $data["size_x"] . "px;font-size:" . $data["size_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["size"] . "</span>";
                }
                echo "        ";
                if ($data["color_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["color_y"] . "px;left:" . $data["color_x"] . "px;font-size:" . $data["color_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["color"] . "</span>";
                }
                echo "        \n        ";
                if ($data["items_to_print_details"][$i]["enable_discount"]) {
                    echo "<span style='position:absolute;top:" . $data["discount_y"] . "px;left:" . $data["discount_x"] . "px;font-size:" . $data["discount_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["discount"] . "</span>";
                }
                echo "        ";
                if ($data["items_to_print_details"][$i]["enable_discount"]) {
                    echo "<span style='position:absolute;top:" . $data["price_after_discount_y"] . "px;left:" . $data["price_after_discount_x"] . "px;font-size:" . $data["price_after_discount_size"] . "px;'>" . $data["items_to_print_details"][$i]["price"] . "</span>";
                }
                echo "        <div style=\"position: absolute;left: ";
                echo $data["barcode_left"];
                echo "px;top: ";
                echo $data["barcode_top"];
                echo "px;height:20px\"><svg  id=\"barcode_";
                echo $data["item_barcode"];
                echo "\" ></svg></div>\n        ";
                if ($data["item_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["item_top"] . "px;left:" . $data["item_left"] . "px;font-size:" . $data["item_size"] . "px;'>" . $data["items_to_print_details"][$i]["description"] . "</span>";
                }
                echo "        ";
                if ($data["store_name_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["store_name_top"] . "px;left:" . $data["store_name_left"] . "px;font-size:" . $data["store_name_font_size"] . "px;font-weight:bold;'>" . $data["store_name"] . "</span>";
                }
                echo "        ";
                if ($data["price_enable"]) {
                    echo "<span style='position:absolute;top:" . $data["price_top"] . "px;left:" . $data["price_left"] . "px;font-size:" . $data["price_font_size"] . "px;border:0px solid #000;padding:1px;'>" . $data["items_to_print_details"][$i]["price"] . "</span>";
                }
                echo "        ";
                if ($data["enable_sku"]) {
                    echo "<span style='position:absolute;top:" . $data["sku_y"] . "px;left:" . $data["sku_x"] . "px;font-size:" . $data["sku_font_size"] . "px;'>" . $data["items_to_print_details"][$i]["sku"] . "</span>";
                }
                echo "    </section>\n    <script type=\"text/javascript\">create_bc(";
                echo $data["items_to_print_details"][$i]["barcode"];
                echo ",";
                echo $data["items_to_print_details"][$i]["barcode"];
                echo ");</script>\n    ";
            }
        }
    }
    echo " \n    \n    ";
}
echo "</body>\n    \n \n</html>\n";

?>