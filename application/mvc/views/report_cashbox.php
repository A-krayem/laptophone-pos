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
echo " Cashbox Report</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <link href=\"libraries/bootstrap-plugins/export/buttons.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/export/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/jszip.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/vfs_fonts.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/buttons.html5.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        \r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\r\n\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global_functions.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <link href=\"application/mvc/views/custom_libraries/css/global.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n         <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/numeric.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n       \r\n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <style type=\"text/css\">\r\n            .container,.panel {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n            \r\n\r\n            \r\n            .table>tfoot>tr>th{\r\n                font-size: 12px !important;\r\n                background-color: #F3F3F3;\r\n                padding: 2px;\r\n                border: none !important;\r\n            }\r\n            \r\n            .dataTables_filter{ display: none; }\r\n            \r\n            .input-sm{\r\n                height: 25px !important;\r\n                font-size: 14px !important;\r\n            }\r\n            \r\n            .selected{\r\n                background-color: #337ab7 !important;\r\n                color: #ffffff !important;\r\n            }\r\n            \r\n            .search_filter{\r\n                width: 100% !important;\r\n                color: #000;\r\n            }\r\n            \r\n            \r\n            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            ::-moz-placeholder { /* Firefox 19+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-ms-input-placeholder { /* IE 10+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-moz-placeholder { /* Firefox 18- */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            \r\n            /* enable absolute positioning */\r\n            .inner-addon { \r\n                position: relative; \r\n            }\r\n\r\n            /* style icon */\r\n            .inner-addon .glyphicon {\r\n              position: absolute;\r\n              padding: 6px;\r\n              padding-top: 8px;\r\n              pointer-events: none;\r\n            }\r\n\r\n            /* align icon */\r\n            .left-addon .glyphicon  { left:  0px;}\r\n            .right-addon .glyphicon { right: 0px;}\r\n\r\n            /* add padding  */\r\n            .left-addon input  { padding-left:  20px; }\r\n            .right-addon input { padding-right: 30px; }\r\n            \r\n            .phone-input{\r\n                    margin-bottom:8px;\r\n            }\r\n            \r\n            .confirmation {\r\n                width: 160px;\r\n            }\r\n            \r\n            .list-group-item{\r\n                border: none !important;\r\n            }\r\n            \r\n            div.toolbar h3{\r\n                margin-top: 0px !important;\r\n            }\r\n            \r\n            #add_new_expenses input{\r\n                font-size: 16px !important;\r\n            }\r\n            \r\n            .table-nonfluid {\r\n                width: auto !important;\r\n             }\r\n             \r\n             .dt-center{\r\n                 text-align: center !important;\r\n             }\r\n             \r\n             .table>thead>tr>th{\r\n                 font-size: 14px !important;\r\n             }\r\n             \r\n             .table>tbody>tr>td{\r\n                 padding-left: 1px !important;\r\n                 padding-right: 0px !important;\r\n                 font-size: 14px !important;\r\n             }\r\n        </style>\r\n\r\n        <script type=\"text/javascript\">\r\n            var report__by_day = null;\r\n            \r\n            \r\n            var enable_cashbox_transactions=0;\r\n            \r\n            var current_store_id = null;\r\n            var current_vendor_id = null;\r\n            var stores = [];\r\n            var vendors = [];\r\n           \r\n            \$(document).ready(function () {\r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \$.getJSON(\"?r=store&f=getStores\", function (data) {\r\n                    \$.each(data, function (key, val) {\r\n                        if(current_store_id==null){\r\n                            current_store_id = val.id;\r\n                        }\r\n                        stores.push({id:val.id,location:val.location,name:val.name});\r\n                    });\r\n                }).done(function () {\r\n                    \$.getJSON(\"?r=reports&f=getVendors\", function (data) {\r\n                        \$.each(data, function (key, val) {\r\n                            if(current_vendor_id==null){\r\n                                current_vendor_id = val.id;\r\n                            }\r\n                            vendors.push({id:val.id,username:val.username});\r\n                        });\r\n                    }).done(function () {\r\n                        init();\r\n                    });\r\n                });\r\n            });\r\n            \r\n            function init(){\r\n                \$('#items_table').show();\r\n                \r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \r\n                var search_fields = [];\r\n                var index = 0;\r\n                \$('#report__by_day tfoot th').each( function () {\r\n                    if(jQuery.inArray(index, search_fields) !== -1){\r\n                        var title = \$(this).text();\r\n                        \$(this).html( '<div class=\"inner-addon left-addon\"><input style=\"width: 100% !important;\" class=\"form-control input-sm\" type=\"text\" placeholder=\" '+title+'\" /></div>' );\r\n                        index++;\r\n                    }\r\n                });\r\n                \r\n                var today = new Date();\r\n                var dd = today.getDate();\r\n                var mm = today.getMonth()+1;\r\n\r\n                var yyyy = today.getFullYear();\r\n                if(dd<10){dd='0'+dd;} \r\n                if(mm<10){mm='0'+mm;} \r\n                var current_date = yyyy+'-'+mm+'-'+dd;\r\n\r\n                \r\n                report__by_day = \$('#report__by_day').dataTable({\r\n                    ajax: \"?r=reports&f=getCashboxReport&p0=\"+current_store_id+\"&p1=\"+current_date+\"&p2=0\",\r\n                    orderCellsTop: true,\r\n                    aoColumnDefs: [\r\n                        { \"targets\": [0], \"searchable\": true, \"orderable\": true, \"visible\": false },\r\n                        { \"targets\": [1], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [2], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [3], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [4], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [5], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [6], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [7], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [8], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [9], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [10], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [11], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [12], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [13], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                        { \"targets\": [14], \"searchable\": true, \"orderable\": true, \"visible\": true},\r\n                    ],\r\n                    \r\n                    scrollY: '60vh',\r\n                    scrollX: true,\r\n                    scrollCollapse: true,\r\n                    bAutoWidth: true,\r\n                    paging: true,\r\n                    select: true,\r\n                    dom: '<\"toolbar\">frtip',\r\n                    initComplete: function( settings ) {\r\n                        var table = \$('#report__by_day').DataTable();\r\n                        table.row(':eq(0)', { page: 'current' }).select();\r\n                        \r\n                        var stores_option = \"\";\r\n                        for(var i=0;i<stores.length;i++){\r\n                            //stores_option+='<option value='+stores[i].id+' title=\"'+stores[i].name+'\">'+stores[i].name+'</option>';\r\n                        }\r\n                        \r\n                        var vendors_option = \"\";\r\n                        vendors_option+='<option value=\"0\" title=\"All Operators\">All Operators</option>';\r\n                        for(var i=0;i<vendors.length;i++){\r\n                            vendors_option+='<option value='+vendors[i].id+' title=\"'+vendors[i].username+'\">'+vendors[i].username+'</option>';\r\n                        }\r\n                        \r\n                        //                                    <select id=\"store_list\" class=\"selectpicker\" onchange=\"store_changed()\" style=\"display:none\">\\n\\\r\n                                      //  '+stores_option+'\\n\\\r\n                                  //  </select>\\n\\\r\n                        \r\n                        \$(\"div.toolbar\").html('<h3><i class=\"glyphicon glyphicon-object-align-vertical\" style=\"font-size:26px;\"></i>&nbsp;Cashbox Report</h3>\\n\\\r\n                        <div class=\"row\">\\n\\\r\n                            <div class=\"col-lg-8 col-md-8 col-sm-12\" >\\n\\\r\n                                <div class=\"btn-group\" role=\"group\" aria-label=\"\">\\n\\\r\n                                    <select id=\"vendors_list\" class=\"selectpicker\" onchange=\"vendor_changed()\">\\n\\\r\n                                        '+vendors_option+'\\n\\\r\n                                    </select>\\n\\\r\n                                    &nbsp;<input id=\"salesDate\"  class=\"form-control datepicker\" type=\"text\" placeholder=\"Select date\" style=\"cursor:pointer\">\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                            <div class=\"col-lg-4 col-md-4 col-sm-12\" >\\n\\\r\n                                <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100%\">\\n\\\r\n                                    <div class=\"btn-group\" id=\"buttons\" style=\"float:right\"></div>\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                        </div>\\n\\\r\n                        ');\r\n    \r\n    \r\n                        var buttons = new \$.fn.dataTable.Buttons(table, {\r\n                            buttons: [\r\n                              {\r\n                                    extend: 'excel',\r\n                                    text: 'Export excel',\r\n                                    className: 'exportExcel',\r\n                                    filename: 'Cashbox Report ',\r\n                                    customize: _customizeExcelOptions,\r\n                                    exportOptions: {\r\n                                        modifier: {\r\n                                            page: 'all'\r\n                                        },\r\n                                        //format: {\r\n                                            //body: function ( data, row, column, node ) {\r\n                                                // Strip \$ from salary column to make it numeric\r\n                                                ///return column === 6 ? data.replace( /[L.L.,]/g, '' ) : data;\r\n                                            //}\r\n                                        //}\r\n                                    }\r\n                              }\r\n                            ]\r\n                            \r\n                       }).container().appendTo(\$('#buttons'));\r\n                       \r\n                      function _customizeExcelOptions(xlsx) {\r\n                            var sheet = xlsx.xl.worksheets['sheet1.xml'];\r\n                            var clR = \$('row', sheet);\r\n                            //var r1 = Addrow(clR.length+2, [{key:'A',value: \"Total Sales\"},{key:'B',value: \$(\"#total_sales_total\").html()}]);\r\n                            //var r2 = Addrow(clR.length+3, [{key:'A',value: \"Total profit\"},{key:'B',value: \$(\"#total_profit\").html()}]);\r\n                            //var r3 = Addrow(clR.length+4, [{key:'A',value: \"Total Expenses\"},{key:'B',value: \$(\"#total_expenses\").html()}]);\r\n                            //var r4 = Addrow(clR.length+5, [{key:'A',value: \"Total Invoices Discounts\"},{key:'B',value: \$(\"#tm_discount\").html()}]);\r\n                            //var r5 = Addrow(clR.length+6, [{key:'A',value: \"Total Credit Notes\"},{key:'B',value: \$(\"#total_credit_notes\").html()}]);\r\n                            //sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1+ r2+ r3+ r4 + r5;\r\n                            \r\n                            //\$('row c[r^=\"A'+(clR.length+2)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+3)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+4)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+5)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+6)+'\"]', sheet).attr('s', '48');\r\n                            \r\n                            function Addrow(index, data) {\r\n                                var msg = '<row r=\"' + index + '\">'\r\n                                for (var i = 0; i < data.length; i++) {\r\n                                    var key = data[i].key;\r\n                                    var value = data[i].value;\r\n                                    msg += '<c t=\"inlineStr\" r=\"' + key + index + '\">';\r\n                                    msg += '<is>';\r\n                                    msg += '<t>' + value + '</t>';\r\n                                    msg += '</is>';\r\n                                    msg += '</c>';\r\n                                }\r\n                                msg += '</row>';\r\n                                return msg;\r\n                            }\r\n                        }\r\n    \r\n                        \$('.selectpicker').selectpicker();\r\n                        \r\n                        \$('.datepicker').datepicker({\r\n                            format: 'yyyy-mm-dd',\r\n                            autoclose:true,\r\n                        });\r\n                        \$(\".datepicker\").datepicker( \"setDate\", new Date() ).attr('readonly','readonly');\r\n                        \r\n                        \r\n                        \$( \"#salesDate\" ).change(function() {\r\n                            report_day_changed();\r\n                        });\r\n\r\n                        \r\n                        if(table.rows().count()==0)\r\n                            \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                        \r\n                        \$(\".sk-circle-layer\").hide();\r\n                        \r\n                    },\r\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\r\n                        \$(nRow).addClass(aData[0]);\r\n                    },\r\n                });\r\n                \r\n          \r\n                \$('#report__by_day').on( 'page.dt', function () {\r\n                    \$(\".selected\").removeClass(\"selected\");\r\n                    \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                } );\r\n                \r\n                \$('#report__by_day').DataTable().columns().every( function () {\r\n                    var that = this;\r\n                    \$( 'input', this.footer() ).on( 'keyup change', function () {\r\n                        if ( that.search() !== this.value ) {\r\n                            that.search( this.value ).draw();\r\n                        }\r\n                    } );\r\n                } );\r\n                \r\n                \$('#report__by_day').DataTable().on( 'select', function ( e, dt, type, indexes ) {\r\n                    if (type === 'row') {\r\n \r\n                       \$(\"#tab_toolbar button.blueB\").removeClass(\"disabled\");\r\n                    }\r\n                } );\r\n                \r\n                \$('#report__by_day').DataTable().on( 'deselect', function ( e, dt, type, indexes ) {\r\n                    if (type === 'row') {\r\n                        \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                    }\r\n                });\r\n  \r\n                \$('#report__by_day').DataTable().search( '' ).columns().search( '' ).draw();\r\n            }\r\n            \r\n            function updateTable(){\r\n                \$(\".sk-circle-layer\").show();\r\n                current_store_id = \$(\"#store_list\").val();\r\n                var table = \$('#report__by_day').DataTable();\r\n                table.ajax.url(\"?r=reports&f=getCashboxReport&p0=\"+current_store_id+\"&p1=\"+\$(\"#salesDate\").val()+\"&p2=\"+\$(\"#vendors_list\").val()).load(function () {\r\n                    \$(\".sk-circle-layer\").hide();\r\n                }, false);\r\n            }\r\n            \r\n            function vendor_changed(){\r\n               updateTable();\r\n            }\r\n            \r\n            function store_changed(){\r\n                current_store_id = \$(\"#store_list\").val();\r\n                updateTable();\r\n            }\r\n            \r\n            function report_day_changed(){\r\n                updateTable();\r\n            }\r\n   \r\n            \r\n        </script>\r\n    </head>\r\n    <body>\r\n        \r\n        <!-- Navbar fixed top -->\r\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\" >\r\n            <div class=\"panel panel-default\">\r\n                <div class=\"panel-body\" style=\"margin-top: 50px;\">\r\n                    <div class=\"row\"  >\r\n                        <div class=\"col-lg-12\">           \r\n                            <table id=\"report__by_day\" class=\"table table-striped table-bordered \" cellspacing=\"0\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th style=\"width: 10px;\">ID</th>\r\n                                        <th style=\"width: 10px;\">Operator</th>\r\n                                        <th style=\"width: 50px;\">Initial Cashbox</th>\r\n                                        <th style=\"width: 30px;\">Cashbox Status</th>\r\n                                        <th>Total Cash Sales</th>\r\n                                        <th>Credit Card Sales</th>\r\n                                        <th>Cheques Sales</th>\r\n                                        <th>Sales not Paid</th>\r\n                                        <th>Total Debts Payment</th>\r\n                                        <th style=\"width: 80px;\">Total Expenses</th>\r\n                                        <th style=\"width: 50px;\">Invoices Discounts</th>\r\n                                        <th>Total Of Returned</th>\r\n                                        <th>Suppliers Payments</th>\r\n                                        <th>Cash On Close</th>\r\n                                        <th style=\"width: 90px;\">Start/End Date</th>\r\n                                    </tr>\r\n                                </thead>\r\n                                \r\n                                <tbody></tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n        \r\n        <div class=\"sk-circle-layer\">\r\n            <div class=\"sk-circle\">\r\n                <div class=\"sk-circle1 sk-child\"></div>\r\n                <div class=\"sk-circle2 sk-child\"></div>\r\n                <div class=\"sk-circle3 sk-child\"></div>\r\n                <div class=\"sk-circle4 sk-child\"></div>\r\n                <div class=\"sk-circle5 sk-child\"></div>\r\n                <div class=\"sk-circle6 sk-child\"></div>\r\n                <div class=\"sk-circle7 sk-child\"></div>\r\n                <div class=\"sk-circle8 sk-child\"></div>\r\n                <div class=\"sk-circle9 sk-child\"></div>\r\n                <div class=\"sk-circle10 sk-child\"></div>\r\n                <div class=\"sk-circle11 sk-child\"></div>\r\n                <div class=\"sk-circle12 sk-child\"></div>\r\n            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>