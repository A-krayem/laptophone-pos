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
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n<html>\n    <head>\n        <title>";
echo $_SESSION["page_title"];
echo " Top customers</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\n        \n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\n\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\n\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\n        \n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        \n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\n        \n        <script src=\"application/mvc/views/custom_libraries/javascripts/global_functions.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\n\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        <link href=\"application/mvc/views/custom_libraries/css/global.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\n        \n         <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n        \n        <script src=\"libraries/numeric.js\" type=\"text/javascript\"></script>\n        \n        <script src=\"libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js\" type=\"text/javascript\"></script>\n        \n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\n       \n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\n        <script src=\"libraries/cleave.js-master/dist/cleave.min.js\" type=\"text/javascript\"></script>\n        <style type=\"text/css\">\n            .container,.panel {\n                height:100%;\n                width: 100%;\n                padding: 0px !important;\n                margin: 0px !important;\n            }\n            \n\n            \n            .table>tfoot>tr>th{\n                font-size: 12px !important;\n                background-color: #F3F3F3;\n                padding: 2px;\n                border: none !important;\n            }\n            \n            .dataTables_filter{ display: none; }\n            \n            .input-sm{\n                height: 25px !important;\n                font-size: 14px !important;\n            }\n            \n            .selected{\n                background-color: #337ab7 !important;\n                color: #ffffff !important;\n            }\n            \n            .search_filter{\n                width: 100% !important;\n                color: #000;\n            }\n            \n            \n            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */\n                color: #337ab7;\n                opacity: 0.9;\n            }\n            ::-moz-placeholder { /* Firefox 19+ */\n                color: #337ab7;\n                opacity: 0.9;\n            }\n            :-ms-input-placeholder { /* IE 10+ */\n                color: #337ab7;\n                opacity: 0.9;\n            }\n            :-moz-placeholder { /* Firefox 18- */\n                color: #337ab7;\n                opacity: 0.9;\n            }\n            \n            /* enable absolute positioning */\n            .inner-addon { \n                position: relative; \n            }\n\n            /* style icon */\n            .inner-addon .glyphicon {\n              position: absolute;\n              padding: 6px;\n              padding-top: 8px;\n              pointer-events: none;\n            }\n\n            /* align icon */\n            .left-addon .glyphicon  { left:  0px;}\n            .right-addon .glyphicon { right: 0px;}\n\n            /* add padding  */\n            .left-addon input  { padding-left:  20px; }\n            .right-addon input { padding-right: 30px; }\n            \n            .phone-input{\n                    margin-bottom:8px;\n            }\n            \n            .confirmation {\n                width: 160px;\n            }\n            \n            .list-group-item{\n                border: none !important;\n            }\n            \n            div.toolbar h3{\n                margin-top: 0px !important;\n            }\n            \n            #add_new_expenses input{\n                font-size: 16px !important;\n            }\n            \n            .table-nonfluid {\n                width: auto !important;\n             }\n             \n             .dt-center{\n                 text-align: center !important;\n             }\n        </style>\n\n        <script type=\"text/javascript\">\n            var report__by_day = null;\n            \n            var current_store_id = ";
echo $_SESSION["store_id"];
echo ";\n            var stores = [];\n           \n            \$(document).ready(function () {\n                \$(\".sk-circle\").center();\n                \$(\".sk-circle-layer\").show();\n                 init();\n            });\n            \n            function init(){\n                \$('#items_table').show();\n                \n                \$(\".sk-circle\").center();\n                \$(\".sk-circle-layer\").show();\n                \n                var search_fields = [0,1,2,3,4];\n                var index = 0;\n                \$('#report__by_day tfoot th').each( function () {\n                    if(jQuery.inArray(index, search_fields) !== -1){\n                        var title = \$(this).text();\n                        \$(this).html( '<div class=\"inner-addon left-addon\"><input style=\"width: 100% !important;\" class=\"form-control input_sm_search\" type=\"text\" placeholder=\" '+title+'\" /></div>' );\n                        index++;\n                    }\n                });\n                \n                report__by_day = \$('#report__by_day').dataTable({\n                    ajax: \"?r=reports&f=getTopCustomers&p0=\"+current_store_id,\n                    orderCellsTop: true,\n                    aoColumnDefs: [\n                        { \"targets\": [0], \"searchable\": true, \"orderable\": true, \"visible\": true },\n                        { \"targets\": [1], \"searchable\": true, \"orderable\": true, \"visible\": true },\n                        { \"targets\": [2], \"searchable\": true, \"orderable\": true, \"visible\": true },\n                        { \"targets\": [3], \"searchable\": true, \"orderable\": true, \"visible\": true },\n                    ],\n                    scrollY: '47vh',\n                    scrollCollapse: true,\n                    \"paging\": true,           // Enable pagination\n                    \"pageLength\": 50, \n                    \"lengthChange\": true,\n                    \"lengthMenu\": [50, 100, 250, 500], // Page length options \n                    //dom: '<\"toolbar\">frtlip',\n                    dom: '<\"row\"<\"col-sm-12 col-md-12\"<\"toolbar\">>><\"row\"<\"col-sm-12 col-md-6\"f>>' +\n                    '<\"row\"<\"col-sm-12\"tr>>' +\n                    '<\"row\"<\"col-sm-4 col-md-4\"l><\"col-sm-4 col-md-4\"i><\"col-sm-4 col-md-4\"p>>',\n                    initComplete: function( settings ) {\n                        var table = \$('#report__by_day').DataTable();\n                        table.row(':eq(0)', { page: 'current' }).select();\n                        \n\n                        \n                        \$(\"div.toolbar\").html('<h3><i class=\"glyphicon glyphicon-user\" style=\"font-size:26px;\"></i>&nbsp;Top customers</h3>\\n\\\n                        ');\n    \n                        \$('.selectpicker').selectpicker();\n                        \n                        \$('.datepicker').datepicker({\n                            format: 'yyyy-mm-dd',\n                            autoclose:true,\n                        });\n                        \$(\".datepicker\").datepicker( \"setDate\", new Date() ).attr('readonly','readonly');\n                        \n                        \n                        if(table.rows().count()==0)\n                            \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\n                        \n                        \$(\".sk-circle-layer\").hide();\n                        \n                    },\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\n                        \$(nRow).addClass(aData[0]);\n                    },\n                });\n                \n          \n                \$('#report__by_day').on( 'page.dt', function () {\n                    \$(\".selected\").removeClass(\"selected\");\n                    \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\n                } );\n                \n                \$('#report__by_day').DataTable().columns().every( function () {\n                    var that = this;\n                    \$( 'input', this.footer() ).on( 'keyup change', function () {\n                        if ( that.search() !== this.value ) {\n                            that.search( this.value ).draw();\n                        }\n                    } );\n                } );\n                \n                \$('#report__by_day').DataTable().on( 'select', function ( e, dt, type, indexes ) {\n                    if (type === 'row') {\n \n                       \$(\"#tab_toolbar button.blueB\").removeClass(\"disabled\");\n                    }\n                } );\n                \n                \$('#report__by_day').DataTable().on( 'deselect', function ( e, dt, type, indexes ) {\n                    if (type === 'row') {\n                        \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\n                    }\n                });\n  \n                \$('#report__by_day').DataTable().search( '' ).columns().search( '' ).draw();\n            }\n            \n            function updateTable(){\n                \$(\".sk-circle-layer\").show();\n                var table = \$('#report__by_day').DataTable();\n                table.ajax.url(\"?r=reports&f=getTopCustomers&p0=\"+current_store_id).load(function () {\n                    \$(\".sk-circle-layer\").hide();\n                }, false);\n            }\n            \n            function store_changed(){\n                updateTable();\n            }\n            \n        </script>\n    </head>\n    <body>\n        \n        <!-- Navbar fixed top -->\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\" >\n            <div class=\"panel panel-default\">\n                <div class=\"panel-body\" style=\"margin-top: 50px;\">\n                    <div class=\"row\"  >\n                        <div class=\"col-lg-12\" >           \n                            <table id=\"report__by_day\" style=\"width: 100%\" class=\"table table-striped table-bordered \" cellspacing=\"0\">\n                                <thead>\n                                    <tr>\n                                        <th>Customer name</th>\n                                        <th>Total payments</th>\n                                        <th>Total discount</th>\n                                        <th>Total profit</th>\n                                        <th>Total profit after discount</th>\n                                    </tr>\n                                </thead>\n                                <tfoot>\n                                    <tr>\n                                        <th>Customer name</th>\n                                        <th>Total payments</th>\n                                        <th>Total discount</th>\n                                        <th>Total profit</th>\n                                        <th>Total profit after discount</th>\n                                    </tr>\n                                </tfoot>\n                                <tbody></tbody>\n                            </table>\n                        </div>\n                    </div>\n                </div>\n            </div>\n        </div>\n        \n        <div class=\"sk-circle-layer\">\n            <div class=\"sk-circle\">\n                <div class=\"sk-circle1 sk-child\"></div>\n                <div class=\"sk-circle2 sk-child\"></div>\n                <div class=\"sk-circle3 sk-child\"></div>\n                <div class=\"sk-circle4 sk-child\"></div>\n                <div class=\"sk-circle5 sk-child\"></div>\n                <div class=\"sk-circle6 sk-child\"></div>\n                <div class=\"sk-circle7 sk-child\"></div>\n                <div class=\"sk-circle8 sk-child\"></div>\n                <div class=\"sk-circle9 sk-child\"></div>\n                <div class=\"sk-circle10 sk-child\"></div>\n                <div class=\"sk-circle11 sk-child\"></div>\n                <div class=\"sk-circle12 sk-child\"></div>\n            </div>\n        </div>\n    </body>\n</html>\n\n";

?>