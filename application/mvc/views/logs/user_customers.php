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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n<html>\r\n    <head>\r\n        <title>UPSILON Logs - Users/Customers</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        \r\n                <!-- Include Date Range Picker -->\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/moment.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global_functions.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/logs.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/customers.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <link href=\"application/mvc/views/custom_libraries/css/global.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n         <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/numeric.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n       \r\n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/jquery.mask.min.js\" type=\"text/javascript\"></script>\r\n        <style type=\"text/css\">\r\n            .container,.panel {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n            \r\n\r\n            \r\n            .table>tfoot>tr>th{\r\n                font-size: 12px !important;\r\n                background-color: #F3F3F3;\r\n                padding: 2px;\r\n                border: none !important;\r\n            }\r\n            \r\n            .dataTables_filter{ display: none; }\r\n            \r\n            .input-sm{\r\n                height: 25px !important;\r\n                font-size: 16px !important;\r\n            }\r\n            \r\n            .selected{\r\n                background-color: #337ab7 !important;\r\n                color: #ffffff !important;\r\n            }\r\n            \r\n            .search_filter{\r\n                width: 100% !important;\r\n                color: #000;\r\n            }\r\n            \r\n            \r\n            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            ::-moz-placeholder { /* Firefox 19+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-ms-input-placeholder { /* IE 10+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-moz-placeholder { /* Firefox 18- */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            \r\n            /* enable absolute positioning */\r\n            .inner-addon { \r\n                position: relative; \r\n            }\r\n\r\n            /* style icon */\r\n            .inner-addon .glyphicon {\r\n              position: absolute;\r\n              padding: 6px;\r\n              padding-top: 8px;\r\n              pointer-events: none;\r\n            }\r\n\r\n            /* align icon */\r\n            .left-addon .glyphicon  { left:  0px;}\r\n            .right-addon .glyphicon { right: 0px;}\r\n\r\n            /* add padding  */\r\n            .left-addon input  { padding-left:  15px; }\r\n            .right-addon input { padding-right: 30px; }\r\n            \r\n            .phone-input{\r\n                    margin-bottom:8px;\r\n            }\r\n            \r\n            .confirmation {\r\n                width: 160px;\r\n            }\r\n            \r\n            .list-group-item{\r\n                border: none !important;\r\n            }\r\n            \r\n            div.toolbar h3{\r\n                margin-top: 0px !important;\r\n            }\r\n            \r\n            #add_new_expenses input{\r\n                font-size: 16px !important;\r\n            }\r\n            \r\n            .cr{\r\n                font-weight: bold;\r\n                color: #009900;\r\n            }\r\n            \r\n            .selected .cr{\r\n                font-weight: bold;\r\n                color: #ffffff;\r\n            }\r\n            \r\n            .up{\r\n                font-weight: bold;\r\n                color: #ff9900;\r\n            }\r\n            \r\n            .selected .up{\r\n                font-weight: bold;\r\n                color: #ffffff;\r\n            }\r\n            \r\n            .dt-center{\r\n                text-align: center !important;\r\n            }\r\n            \r\n            .bootstrap-select{\r\n                width: 100% !important;\r\n            }\r\n            \r\n            label{\r\n                font-size: 15px !important;\r\n            }\r\n            \r\n            .plpr2{\r\n                padding-left: 2px !important;\r\n                padding-right: 2px !important;\r\n            }\r\n\r\n            .pr2{\r\n                padding-right: 2px !important;\r\n            }\r\n\r\n            .big_and_bold{\r\n                font-size: 18px !important;\r\n                font-weight: bold;\r\n                padding-left: 2px !important;\r\n                padding-right: 1px !important;\r\n            }\r\n        </style>\r\n\r\n        <script type=\"text/javascript\">\r\n            var users_customers_logs_table = null;\r\n            var users = [];\r\n            var current_user = 0;\r\n            \r\n            \$(document).ready(function () {\r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \r\n                \$.getJSON(\"?r=logs&f=get_needed_data\", function (data) {\r\n                    users = [];\r\n                    users.push({id:0,username:\"All Users\"});\r\n                    \$.each(data.users, function (key, val) {\r\n                        users.push({id:val.id,username:val.username});\r\n                    }); \r\n                }).done(function () {\r\n                    \r\n                    init();\r\n                });\r\n                \r\n     \r\n            });\r\n           \r\n            \r\n            \r\n            function init(){\r\n                \$('#items_table').show();\r\n                \r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \r\n                var search_fields = [0,1,2,3];\r\n                var index = 0;\r\n                \$('#users_customers_logs_table tfoot th').each( function () {\r\n                    if(jQuery.inArray(index, search_fields) !== -1){\r\n                        var title = \$(this).text();\r\n                        \$(this).html( '<div class=\"inner-addon left-addon\"><input style=\"width: 100% !important;\" class=\"form-control input_sm_search\" type=\"text\" placeholder=\" '+title+'\" /></div>' );\r\n                        index++;\r\n                    }\r\n                });\r\n                \r\n                users_customers_logs_table = \$('#users_customers_logs_table').dataTable({\r\n                    ajax: \"?r=logs&f=get_user_customers_logs&p0=today&p1=\"+current_user+\"&p2=0&p3=0\",\r\n                    orderCellsTop: true,\r\n                    aoColumnDefs: [\r\n                        { \"targets\": [0], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                        { \"targets\": [1], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [2], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                        { \"targets\": [3], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                        { \"targets\": [4], \"searchable\": true, \"orderable\": false, \"visible\": true ,\"className\": \"dt-center\" },\r\n                        { \"targets\": [5], \"searchable\": true, \"orderable\": false, \"visible\": false ,\"className\": \"dt-center\" },\r\n                    ],\r\n                    scrollY: '47vh',\r\n                    scrollCollapse: true,\r\n                    \"paging\": true,           // Enable pagination\r\n                    \"pageLength\": 50, \r\n                    \"lengthChange\": true,\r\n                    \"lengthMenu\": [50, 100, 250, 500], // Page length options \r\n                    //dom: '<\"toolbar\">frtlip',\r\n                    dom: '<\"row\"<\"col-sm-12 col-md-12\"<\"toolbar\">>><\"row\"<\"col-sm-12 col-md-6\"f>>' +\r\n                    '<\"row\"<\"col-sm-12\"tr>>' +\r\n                    '<\"row\"<\"col-sm-4 col-md-4\"l><\"col-sm-4 col-md-4\"i><\"col-sm-4 col-md-4\"p>>',\r\n                    initComplete: function( settings ) {\r\n                        var table = \$('#users_customers_logs_table').DataTable();\r\n                        table.row(':eq(0)', { page: 'current' }).select();\r\n                        \r\n                        var users_options = \"\";\r\n                        for(var i=0;i<users.length;i++){\r\n                            var sel = \"\";\r\n                            if(i==0){\r\n                                sel=\"selected\";\r\n                            }\r\n                            users_options+='<option '+sel+' value='+users[i].id+' title=\"'+users[i].username+'\">'+users[i].username+'</option>';\r\n                        }\r\n                        \r\n                        \$(\"div.toolbar\").html('<h3><i class=\"glyphicon glyphicon-align-justify\" style=\"font-size:26px;\"></i>&nbsp;Users/Customers Logs</h3>\\n\\\r\n                        <div class=\"row\">\\n\\\r\n                            <div class=\"col-lg-2 col-md-2 col-xs-2\" style=\"padding-left:15px;padding-right:5px;\">\\n\\\r\n                                <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100% !important;\">\\n\\\r\n                                    <select data-width=\"100%\" id=\"users_list\" class=\"selectpicker\" onchange=\"update_table()\">\\n\\\r\n                                        '+users_options+'\\n\\\r\n                                    </select>\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                            <div class=\"col-lg-2 col-md-2 col-xs-2\" style=\"padding-left:5px;padding-right:5px;\">\\n\\\r\n                                <input id=\"datefilter\" class=\"form-control datepicker\" type=\"text\" placeholder=\"Select date\" style=\"cursor:pointer;width:180px;\">\\n\\\r\n                            </div>\\n\\\r\n                        </div>\\n\\\r\n                        ');\r\n    \r\n                        \$('.selectpicker').selectpicker();\r\n                        \r\n                        \$('#datefilter').daterangepicker({\r\n                            //dateLimit:{month:12},\r\n                            locale: {\r\n                                format: 'YYYY-MM-DD'\r\n                            }\r\n                        });\r\n                        \r\n                        \$('#datefilter').change(function() {\r\n                            update_table();\r\n                        });\r\n                        \r\n                        \$(\".sk-circle-layer\").hide();\r\n                        \r\n                    },\r\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\r\n                        \$(nRow).addClass(aData[0]);\r\n                    },\r\n                    fnDrawCallback: updateRows,\r\n                });\r\n                \r\n          \r\n                \$('#users_customers_logs_table').on( 'page.dt', function () {\r\n                    \$(\".selected\").removeClass(\"selected\");\r\n                    \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                } );\r\n                \r\n                \$('#users_customers_logs_table').DataTable().columns().every( function () {\r\n                    var that = this;\r\n                    \$( 'input', this.footer() ).on( 'keyup change', function () {\r\n                        if ( that.search() !== this.value ) {\r\n                            that.search( this.value ).draw();\r\n                        }\r\n                    } );\r\n                } );\r\n                \r\n                \$('#users_customers_logs_table').on('click', 'td', function () {\r\n                    if (\$(this).index() == 4 ) {\r\n                        return false;\r\n                    }\r\n               });\r\n                \r\n            }\r\n            \r\n            function update_table(){\r\n                \$(\".sk-circle-layer\").show();\r\n                var table = \$('#users_customers_logs_table').DataTable();\r\n                table.ajax.url(\"?r=logs&f=get_user_customers_logs&p0=\"+\$(\"#datefilter\").val()+\"&p1=\"+\$(\"#users_list\").val()+\"&p2=0&p3=0\").load(function () {\r\n                    \$(\".sk-circle-layer\").hide();\r\n                }, false);\r\n            }\r\n            \r\n            function updateRows(){\r\n                var table = \$('#users_customers_logs_table').DataTable();\r\n                var p = table.rows({ page: 'current' }).nodes();\r\n                for (var k = 0; k < p.length; k++){\r\n                    var index = table.row(p[k]).index();\r\n                    table.cell(index, 4).data('<i class=\"glyphicon glyphicon-align-left\" title=\"Log\" onclick=\"customer_user_log(\\''+table.cell(index, 5).data()+'\\')\" style=\"cursor:pointer\"></i>&nbsp;&nbsp;<i class=\"glyphicon glyphicon-user\" title=\"Log\" onclick=\"editCustomer(\\'cu-'+table.cell(index, 5).data()+'\\')\" style=\"cursor:pointer\"></i>');\r\n                }\r\n            }\r\n            \r\n            function customer_user_log(id){\r\n                show_all_customer_user_log(id);\r\n            }\r\n\r\n        </script>\r\n    </head>\r\n    <body>\r\n        \r\n        <!-- Navbar fixed top -->\r\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\" >\r\n            <div class=\"panel panel-default\">\r\n                <div class=\"panel-body\" style=\"margin-top: 50px;\">\r\n                    <div class=\"row\"  >\r\n                        <div class=\"col-lg-12\" >           \r\n                            <table id=\"users_customers_logs_table\" class=\"table table-striped table-bordered\" cellspacing=\"0\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th style=\"width: 40px;\">Action</th>\r\n                                        <th style=\"width: 120px;\">Action Date</th>\r\n                                        <th>Description</th>\r\n                                        <th style=\"width: 250px;\">Customer</th>\r\n                                        <th style=\"width: 30px;\"></th>\r\n                                         <th style=\"width: 30px;\"></th>\r\n                                    </tr>\r\n                                </thead>\r\n                                <tfoot>\r\n                                    <tr>\r\n                                        <th>Action</th>\r\n                                        <th>Action Date</th>\r\n                                        <th>Description</th>\r\n                                        <th>Customer</th>\r\n                                        <th></th>\r\n                                        <th></th>\r\n                                    </tr>\r\n                                </tfoot>\r\n                                <tbody></tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n        \r\n        <div class=\"sk-circle-layer\">\r\n            <div class=\"sk-circle\">\r\n                <div class=\"sk-circle1 sk-child\"></div>\r\n                <div class=\"sk-circle2 sk-child\"></div>\r\n                <div class=\"sk-circle3 sk-child\"></div>\r\n                <div class=\"sk-circle4 sk-child\"></div>\r\n                <div class=\"sk-circle5 sk-child\"></div>\r\n                <div class=\"sk-circle6 sk-child\"></div>\r\n                <div class=\"sk-circle7 sk-child\"></div>\r\n                <div class=\"sk-circle8 sk-child\"></div>\r\n                <div class=\"sk-circle9 sk-child\"></div>\r\n                <div class=\"sk-circle10 sk-child\"></div>\r\n                <div class=\"sk-circle11 sk-child\"></div>\r\n                <div class=\"sk-circle12 sk-child\"></div>\r\n            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>