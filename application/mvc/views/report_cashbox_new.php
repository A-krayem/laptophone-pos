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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n<html>\r\n    <head>\r\n        <title>";
echo $_SESSION["page_title"];
echo " Cashbox Report</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <link href=\"libraries/bootstrap-plugins/export/buttons.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/export/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/jszip.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/vfs_fonts.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/buttons.html5.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        \r\n        <!-- Include Date Range Picker -->\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/moment.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        \r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\r\n\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global_functions.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <link href=\"application/mvc/views/custom_libraries/css/global.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/cashbox_report.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n         <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/numeric.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n       \r\n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <style type=\"text/css\">\r\n            .container,.panel {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n            \r\n\r\n            \r\n            .table>tfoot>tr>th{\r\n                font-size: 12px !important;\r\n                background-color: #F3F3F3;\r\n                padding: 2px;\r\n                border: none !important;\r\n            }\r\n            \r\n            .dataTables_filter{ display: none; }\r\n            \r\n            .input-sm{\r\n                height: 25px !important;\r\n                font-size: 14px !important;\r\n            }\r\n            \r\n            .selected{\r\n                background-color: #337ab7 !important;\r\n                color: #ffffff !important;\r\n            }\r\n            \r\n            .search_filter{\r\n                width: 100% !important;\r\n                color: #000;\r\n            }\r\n            \r\n            \r\n            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            ::-moz-placeholder { /* Firefox 19+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-ms-input-placeholder { /* IE 10+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-moz-placeholder { /* Firefox 18- */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            \r\n            /* enable absolute positioning */\r\n            .inner-addon { \r\n                position: relative; \r\n            }\r\n\r\n            /* style icon */\r\n            .inner-addon .glyphicon {\r\n              position: absolute;\r\n              padding: 6px;\r\n              padding-top: 8px;\r\n              pointer-events: none;\r\n            }\r\n\r\n            /* align icon */\r\n            .left-addon .glyphicon  { left:  0px;}\r\n            .right-addon .glyphicon { right: 0px;}\r\n\r\n            /* add padding  */\r\n            .left-addon input  { padding-left:  20px; }\r\n            .right-addon input { padding-right: 30px; }\r\n            \r\n            .phone-input{\r\n                    margin-bottom:8px;\r\n            }\r\n            \r\n            #drep_table__ tr.selected td {\r\n    color: #fff !important;\r\n}\r\n\r\n.cash_rep_sec{\r\n    font-size: 18px;\r\n    font-weight: bold;\r\n    color: #d43f3a !important;\r\n}\r\n\r\n#drep_table__ tr.selected td span {\r\n    color: #fff !important;\r\n}\r\n            \r\n            .selected .cus_name{\r\n                color: #ffffff !important;\r\n            }\r\n            \r\n            .large .modal-dialog {\r\n                width: 99% !important;\r\n                max-height: 550px;\r\n            }\r\n\r\n\r\n            .cin {\r\n                color: #419641 !important;\r\n            }\r\n\r\n            .cret {\r\n                color: #007ebd !important;\r\n            }\r\n\r\n            .cout {\r\n                color: #d43f3a !important;\r\n            }\r\n\r\n            .cwar {\r\n                color: #ff6633 !important;\r\n            }\r\n            \r\n            .confirmation {\r\n                width: 160px;\r\n            }\r\n            \r\n            .list-group-item{\r\n                border: none !important;\r\n            }\r\n            \r\n            div.toolbar h3{\r\n                margin-top: 0px !important;\r\n            }\r\n            \r\n            #add_new_expenses input{\r\n                font-size: 16px !important;\r\n            }\r\n            \r\n            .table-nonfluid {\r\n                width: auto !important;\r\n             }\r\n             \r\n             .dt-center{\r\n                 text-align: center !important;\r\n             }\r\n             \r\n             .table>thead>tr>th{\r\n                 font-size: 14px !important;\r\n             }\r\n             \r\n             .table>tbody>tr>td{\r\n                 padding-left: 1px !important;\r\n                 padding-right: 0px !important;\r\n                 font-size: 14px !important;\r\n             }\r\n        </style>\r\n\r\n        <script type=\"text/javascript\">\r\n            var report__by_day = null;\r\n            \r\n            var enable_cashbox_transactions=0;\r\n           \r\n            \$(document).ready(function () {\r\n                \$(\".sk-circle\").center();\r\n                init();\r\n            });\r\n            \r\n            function init(){\r\n                report__by_day = \$('#report__by_day').dataTable({\r\n                    ajax: \"?r=reports&f=getCashboxReport_new&p0=thismonth\",\r\n                    orderCellsTop: true,\r\n                    aoColumnDefs: [\r\n                        { \"targets\": [0], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [1], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [2], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [3], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [4], \"searchable\": true, \"orderable\": false, \"visible\": true},\r\n                    ],\r\n                    scrollY: '47vh',\r\n                    scrollCollapse: true,\r\n                    \"paging\": true,           // Enable pagination\r\n                    \"pageLength\": 50, \r\n                    \"lengthChange\": true,\r\n                    \"lengthMenu\": [50, 100, 250, 500], // Page length options \r\n                    //dom: '<\"toolbar\">frtlip',\r\n                    dom: '<\"row\"<\"col-sm-12 col-md-12\"<\"toolbar\">>><\"row\"<\"col-sm-12 col-md-6\"f>>' +\r\n                    '<\"row\"<\"col-sm-12\"tr>>' +\r\n                    '<\"row\"<\"col-sm-4 col-md-4\"l><\"col-sm-4 col-md-4\"i><\"col-sm-4 col-md-4\"p>>',\r\n                    initComplete: function( settings ) {\r\n\r\n                        \$(\"div.toolbar\").html('<h3>Cashbox Report</h3>\\n\\\r\n                            <div class=\"row\">\\n\\\r\n                               <div class=\"col-lg-2 col-md-2 col-sm-2\" style=\"padding-left:15px;padding-right:5px;\">\\n\\\r\n                                    <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100%\">\\n\\\r\n                                        <input id=\"cashboxDates\" class=\"form-control datepicker\" type=\"text\" placeholder=\"Select date\" style=\"cursor:pointer;width:100%;\" />\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                        </div>\\n\\\r\n                        ');\r\n                                            \r\n                         var defaultStart = moment().startOf('month');\r\n                        var end = moment();\r\n                        \r\n                        \$('.datepicker').daterangepicker({\r\n                            //dateLimit:{month:12},\r\n                            startDate: defaultStart,\r\n                            endDate: end,\r\n                            locale: {\r\n                                format: 'YYYY-MM-DD'\r\n                            },\r\n                            ranges: {\r\n                                'Today': [moment(), moment()],\r\n                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],\r\n                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],\r\n                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],\r\n                                'This Month': [moment().startOf('month'), moment().endOf('month')],\r\n                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]\r\n                             }\r\n                        });\r\n                        \r\n                        \$( \"#cashboxDates\" ).change(function() {\r\n                            updateTable();\r\n                        });\r\n                    },\r\n                    fnDrawCallback: updateRows,\r\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\r\n                        \r\n                    },\r\n                });\r\n          \r\n                \$('#report__by_day').on( 'page.dt', function () {\r\n                    \$(\".selected\").removeClass(\"selected\");\r\n                } );\r\n    \r\n                \r\n                \$('#report__by_day').DataTable().on( 'select', function ( e, dt, type, indexes ) {\r\n                    \r\n                } );\r\n            }\r\n            \r\n            function updateRows(){\r\n                var table = \$('#report__by_day').DataTable();\r\n                var p = table.rows({ page: 'current' }).nodes();\r\n                for (var k = 0; k < p.length; k++){\r\n                    var index = table.row(p[k]).index();\r\n                    table.cell(index, 4).data('<button style=\"width:100%\" onclick=\"show_cashbox('+table.cell(index, 0).data()+')\" type=\"button\" class=\"btn btn-xs btn-info\">Details</button>');\r\n                }\r\n            }\r\n            \r\n            function updateTable(){\r\n                \$(\".sk-circle-layer\").show();\r\n                var table = \$('#report__by_day').DataTable();\r\n                table.ajax.url(\"?r=reports&f=getCashboxReport_new&p0=\"+\$(\"#cashboxDates\").val()).load(function () {\r\n                    \$(\".sk-circle-layer\").hide();\r\n                }, false);\r\n            }\r\n   \r\n            function show_cashbox(id){\r\n                get_full_report(id);\r\n            }\r\n            \r\n        </script>\r\n    </head>\r\n    <body>\r\n        \r\n        <!-- Navbar fixed top -->\r\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\" >\r\n            <div class=\"panel panel-default\" style=\"background-color: #ffffff !important\">\r\n                <div class=\"panel-body\" style=\"margin-top: 50px; \">\r\n                    <div class=\"row\"  >\r\n                        <div class=\"col-lg-12\">           \r\n                            <table id=\"report__by_day\" class=\"table table-striped table-bordered \" cellspacing=\"0\" style=\"width: 100%\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th style=\"width: 50px;\">ID</th>\r\n                                        <th>Operator</th>\r\n                                        <th style=\"width: 120px;\">Open Date</th>\r\n                                        <th style=\"width: 120px;\">Close Date</th>\r\n                                        <th style=\"width: 80px;\">&nbsp;</th>\r\n                                    </tr>\r\n                                </thead>\r\n                                \r\n                                <tbody></tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n        \r\n        <div class=\"sk-circle-layer\">\r\n            <div class=\"sk-circle\">\r\n                <div class=\"sk-circle1 sk-child\"></div>\r\n                <div class=\"sk-circle2 sk-child\"></div>\r\n                <div class=\"sk-circle3 sk-child\"></div>\r\n                <div class=\"sk-circle4 sk-child\"></div>\r\n                <div class=\"sk-circle5 sk-child\"></div>\r\n                <div class=\"sk-circle6 sk-child\"></div>\r\n                <div class=\"sk-circle7 sk-child\"></div>\r\n                <div class=\"sk-circle8 sk-child\"></div>\r\n                <div class=\"sk-circle9 sk-child\"></div>\r\n                <div class=\"sk-circle10 sk-child\"></div>\r\n                <div class=\"sk-circle11 sk-child\"></div>\r\n                <div class=\"sk-circle12 sk-child\"></div>\r\n            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>