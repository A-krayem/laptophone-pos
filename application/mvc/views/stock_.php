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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n<html>\r\n    <head>\r\n        <title>UPSILON</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <style type=\"text/css\">\r\n            html,body {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n\r\n            .container,.panel {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n\r\n            .panel-body{\r\n\r\n            }\r\n\r\n            .navbar{\r\n                border-radius: 0px !important;\r\n            }\r\n\r\n            .items_table{\r\n                padding-left: 15px;\r\n                padding-right: 15px;\r\n            }\r\n\r\n            .input-sm{\r\n                height: 25px !important;\r\n            }\r\n\r\n\r\n\r\n            .selected{\r\n                background-color: #999999 !important;\r\n                color: #ffffff !important;\r\n            }\r\n\r\n            .warning {\r\n                background-color: #F99 !important;\r\n            }\r\n        </style>\r\n\r\n        <script type=\"text/javascript\">\r\n            var itemTable = null;\r\n            \$(document).ready(function () {\r\n\r\n                \$('#items_table').show();\r\n                itemTable = \$('#items_table').dataTable({\r\n                    ajax: \"?r=stock&f=getItemsInStock\",\r\n                    aoColumnDefs: [\r\n                        {\r\n                            'bSortable': false, 'aTargets': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]\r\n                        },\r\n\r\n                        {\r\n                            \"aTargets\": [3, 5, 6, 8, 9],\r\n                            \"fnCreatedCell\": function (nTd, sData, oData, iRow, iCol) {\r\n                                var \$currencyCell = \$(nTd);\r\n                                var commaValue = \$currencyCell.text().replace(/(\\d)(?=(\\d\\d\\d)+(?!\\d))/g, \"\$1,\");\r\n                                \$currencyCell.text(commaValue);\r\n                            }\r\n                        }\r\n\r\n                    ],\r\n                    \"columnDefs\": [\r\n                        {\"type\": \"num-fmt\", \"symbols\": \"R\$\", \"targets\": 3}\r\n                    ],\r\n                    //serverSide: true,\r\n                    scrollY: '70vh',\r\n                    scrollCollapse: true,\r\n                    paging: false,\r\n                    select: true,\r\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\r\n                        //\$(nRow).addClass( \"i_\" + aData[0]);\r\n                    },\r\n                    initComplete: function (settings, json) {\r\n                        //\$('#items_table tbody').on( 'click', 'tr', function () {\r\n                        //if ( \$(this).hasClass('selected') ) {\r\n                        //\$(this).removeClass('selected');\r\n                        //}\r\n                        //else {\r\n                        //itemTable.\$('tr.selected').removeClass('selected');\r\n                        //\$(this).addClass('selected');\r\n                        //var d = \$('#items_table').DataTable().row(this).data();\r\n                        //alert(d[0]);\r\n                        //}\r\n                        //} );\r\n\r\n                        var table = \$('#example').DataTable();\r\n\r\n                        table.rows().every(function (rowIdx, tableLoop, rowLoop) {\r\n                            var cell = table.cell({row: rowIdx, column: 0}).node();\r\n                            \$(cell).addClass('warning');\r\n                        });\r\n                    }\r\n                });\r\n\r\n                \$(\"#button1\").click(function () {\r\n                    // \$('#items_table .s1020').get(0).scrollIntoView();\r\n                });\r\n            });\r\n        </script>\r\n    </head>\r\n    <body>\r\n\r\n        <!-- Navbar fixed top -->\r\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\" >\r\n            <div class=\"panel panel-default\">\r\n                <div class=\"panel-body\" style=\"margin-top: 50px;\">\r\n                    <div class=\"row\"  >\r\n                        <div class=\"col-lg-12\" >           \r\n                            <table style=\"display: none;\" id=\"items_table\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\" >\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th>Ref.</th>\r\n                                        <th>Supplier code</th>\r\n                                        <th>Barcode</th>\r\n                                        <th>Description</th>\r\n                                        <th>Quantity</th>\r\n                                        <th>P. Cost</th>\r\n                                        <th>VAT</th>\r\n                                        <th>F. Cost</th>\r\n                                        <th>S. Price</th>\r\n                                        <th>Dis.</th>\r\n                                        <th>F. Price</th>\r\n                                        <th>T. amount</th>\r\n                                    </tr>\r\n                                </thead>\r\n\r\n                                <tbody>\r\n\r\n                                </tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>