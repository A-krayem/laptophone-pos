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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n<html>\r\n    <head>\r\n        <title>Report Template</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <link href=\"https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <script src=\"https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"https://cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js\" type=\"text/javascript\"></script>\r\n        <script src=\"https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <style type=\"text/css\">\r\n            body{\r\n                padding: 0px;\r\n                margin: 0px;\r\n            }\r\n            \r\n            table, tr, td, th, tbody, thead, tfoot {\r\n                page-break-inside: avoid !important;\r\n            }\r\n            \r\n            \r\n            \r\n            .table>tbody>tr>td{\r\n                padding: 3px !important;\r\n                font-size: 14px !important;\r\n            }\r\n            \r\n            .table>thead>tr>th{\r\n               background-color: #CCC\r\n            }\r\n        </style>\r\n        <script type=\"text/javascript\">\r\n            \$(document).ready(function () {\r\n                \$('#report').DataTable({\r\n                    \"bPaginate\": false,\r\n                    \"bLengthChange\": false,\r\n                    \"bFilter\": false,\r\n                    \"bInfo\": false,\r\n                    \"bAutoWidth\": false,\r\n                    \"paging\": false,\r\n                    \"ordering\": false,\r\n                    \"info\": false\r\n                });\r\n            });\r\n\r\n        </script>\r\n    </head>\r\n    <body>\r\n       <div class=\"container\" style=\"width: 100%\">\r\n            <table id=\"report\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\r\n                <thead>\r\n                    <tr>\r\n                        <th style=\"width: 60px;\">Item ID</th>\r\n                        <th style=\"width: 90px;\">Barcode</th>\r\n                        <th>Description</th>\r\n                        <th style=\"width: 100px;\">Unit Cost</th>\r\n                        <th style=\"width: 100px;\">Unit Price</th>\r\n                        <th style=\"width: 30px;\">Qty</th>\r\n                        <th style=\"width: 100px;\">Total Price</th>\r\n                    </tr>\r\n                </thead>\r\n                <tbody>\r\n                    ";
for ($i = 0; $i < count($data["sales_items"]); $i++) {
    echo "                    <tr>\r\n                        <td>";
    echo self::idFormat_item($data["sales_items"][$i]["item_id"]);
    echo "</td>\r\n                        <td>";
    echo $data["all_items"][$data["sales_items"][$i]["item_id"]]["barcode"];
    echo "</td>\r\n                        \r\n                        ";
    if (30 < strlen($data["all_items"][$data["sales_items"][$i]["item_id"]]["description"])) {
        $data["all_items"][$data["sales_items"][$i]["item_id"]]["description"] = substr($data["all_items"][$data["sales_items"][$i]["item_id"]]["description"], 0, 30) . " ...";
    }
    echo "                        <td>";
    echo $data["all_items"][$data["sales_items"][$i]["item_id"]]["description"];
    echo "</td>\r\n                        \r\n                        <td>";
    echo number_format($data["sales_items"][$i]["buying_cost"], 2) . " " . $data["settings_info"]["default_currency_symbol"];
    echo "</td>\r\n                        <td>";
    echo number_format($data["sales_items"][$i]["selling_price"], 2) . " " . $data["settings_info"]["default_currency_symbol"];
    echo "</td>\r\n                        <td>";
    echo floor($data["sales_items"][$i]["qty"]);
    echo "</td>\r\n                        <td>";
    echo number_format($data["sales_items"][$i]["final_price_disc_qty"], 2) . " " . $data["settings_info"]["default_currency_symbol"];
    echo "</td>\r\n                    </tr>\r\n                    ";
}
echo "                </tbody>\r\n            </table> \r\n        </div>\r\n        \r\n    </body>\r\n</html>\r\n";

?>